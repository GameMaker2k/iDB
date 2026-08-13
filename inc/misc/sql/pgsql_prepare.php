<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2024 iDB Support - https://idb.osdn.jp/support/category.php?act=view&id=1
    Copyright 2004-2024 Game Maker 2k - https://idb.osdn.jp/support/category.php?act=view&id=2

    $FileInfo: pgsql_prepare.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/

// BUGFIX: this guard used to test for "pgsql.php", so the file could be
// requested directly without being redirected.
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "pgsql_prepare.php" || $File3Name == "/pgsql_prepare.php") {
    @header('Location: index.php');
    exit();
}

if (!isset($GLOBALS['NumPreQueriesArray']['pgsql_prepare'])) {
    $GLOBALS['NumPreQueriesArray']['pgsql_prepare'] = 0;
}
if (!isset($GLOBALS['NumQueriesArray']['pgsql_prepare'])) {
    $GLOBALS['NumQueriesArray']['pgsql_prepare'] = 0;
}
if (!isset($GLOBALS['PgSqlPreparedNames']) || !is_array($GLOBALS['PgSqlPreparedNames'])) {
    $GLOBALS['PgSqlPreparedNames'] = array();
}

function pgsql_prepare_func_is_conn($conn)
{
    if (is_resource($conn)) {
        return true;
    }
    return is_object($conn) && (get_class($conn) === 'PgSql\\Connection');
}

function pgsql_prepare_func_conn($link = null)
{
    if (pgsql_prepare_func_is_conn($link)) {
        return $link;
    }
    if (isset($GLOBALS['SQLStat']) && pgsql_prepare_func_is_conn($GLOBALS['SQLStat'])) {
        return $GLOBALS['SQLStat'];
    }
    return null;
}

function pgsql_prepare_func_is_result($result)
{
    if (is_resource($result)) {
        return true;
    }
    return is_object($result) && (get_class($result) === 'PgSql\\Result');
}

// PostgreSQL Error handling functions
function pgsql_prepare_func_error($link = null)
{
    $connection = pgsql_prepare_func_conn($link);
    return $connection ? pg_last_error($connection) : "No valid PostgreSQL connection.";
}

function pgsql_prepare_func_errno($link = null)
{
    $connection = pgsql_prepare_func_conn($link);
    if (!$connection) {
        return 0;
    }
    $message = pg_last_error($connection);
    return ($message === '') ? 0 : $message;
}

function pgsql_prepare_func_errorno($link = null)
{
    $connection = pgsql_prepare_func_conn($link);
    if (!$connection) {
        return "No valid PostgreSQL connection.";
    }
    return pg_last_error($connection);
}

function pgsql_prepare_func_normalize_params($params)
{
    $out = array();
    foreach ($params as $value) {
        if (is_bool($value)) {
            $out[] = $value ? 't' : 'f';
        } elseif ($value === null) {
            $out[] = null;
        } else {
            $out[] = (string)$value;
        }
    }
    return $out;
}

// Execute a prepared query.
//
// BUGFIX: pg_prepare() was called on every execution with a name derived from
// md5($sql), so the second call with the same SQL failed with
// "prepared statement already exists". Names are now tracked per connection.
function pgsql_prepare_func_query($query, $params_or_link = null, $maybe_link = null)
{
    list($sql, $params, $link) = sql_resolve_query_args($query, $params_or_link, $maybe_link);

    $connection = pgsql_prepare_func_conn($link);
    if (!$connection) {
        output_error("SQL Error: No valid PostgreSQL connection.", E_USER_ERROR);
        return false;
    }

    if (!is_string($sql) || trim($sql) === '') {
        output_error("SQL Error: Query is empty.", E_USER_ERROR);
        return false;
    }

    $params = pgsql_prepare_func_normalize_params($params);

    // Statements without parameters do not need the prepare/execute round trip.
    if (count($params) === 0 && strpos($sql, '$1') === false) {
        $result = @pg_query($connection, $sql);
        if ($result === false) {
            output_error("SQL Execute Error: " . pgsql_prepare_func_error($connection), E_USER_ERROR);
            return false;
        }
        ++$GLOBALS['NumQueriesArray']['pgsql_prepare'];
        return $result;
    }

    $conn_key = is_object($connection) ? spl_object_hash($connection) : (string)$connection;
    $stmt_name = 'idb_' . md5($sql);
    $cache_key = $conn_key . '|' . $stmt_name;

    if (!isset($GLOBALS['PgSqlPreparedNames'][$cache_key])) {
        $prepare = @pg_prepare($connection, $stmt_name, $sql);
        if ($prepare === false) {
            output_error("SQL Prepare Error: " . pgsql_prepare_func_error($connection), E_USER_ERROR);
            return false;
        }
        $GLOBALS['PgSqlPreparedNames'][$cache_key] = true;
    }

    $result = @pg_execute($connection, $stmt_name, $params);

    if ($result === false) {
        output_error("SQL Execute Error: " . pgsql_prepare_func_error($connection), E_USER_ERROR);
        return false;
    }

    ++$GLOBALS['NumQueriesArray']['pgsql_prepare'];
    return $result;
}

// Fetch Number of Rows
function pgsql_prepare_func_num_rows($result)
{
    if (!pgsql_prepare_func_is_result($result)) {
        return false;
    }
    $num = pg_num_rows($result);
    return ($num === false) ? false : $num;
}

// Connect to PostgreSQL database
function pgsql_prepare_func_connect_db($server, $username, $password, $database = null, $new_link = false)
{
    $pgport = 5432;
    $hostex = explode(":", $server);

    if (isset($hostex[1])) {
        $server = $hostex[0];
        $pgport = is_numeric($hostex[1]) ? (int)$hostex[1] : $pgport;
    }

    $pgstring = $database === null
        ? "host=$server port=$pgport user=$username password=$password"
        : "host=$server port=$pgport dbname=$database user=$username password=$password";

    $link = $new_link
        ? @pg_connect($pgstring, PGSQL_CONNECT_FORCE_NEW)
        : @pg_connect($pgstring);

    if ($link === false) {
        output_error("Not connected: " . (function_exists('pg_last_error') ? @pg_last_error() : "connection failed"), E_USER_ERROR);
        return false;
    }

    return $link;
}

function pgsql_prepare_func_disconnect_db($link = null)
{
    $connection = pgsql_prepare_func_conn($link);
    if (!$connection) {
        return false;
    }

    // Drop the cached statement names for this connection.
    $conn_key = is_object($connection) ? spl_object_hash($connection) : (string)$connection;
    foreach (array_keys($GLOBALS['PgSqlPreparedNames']) as $key) {
        if (strpos($key, $conn_key . '|') === 0) {
            unset($GLOBALS['PgSqlPreparedNames'][$key]);
        }
    }

    return pg_close($connection);
}

// Query Results
function pgsql_prepare_func_result($result, $row, $field = 0)
{
    if (!pgsql_prepare_func_is_result($result)) {
        return null;
    }

    $value = @pg_fetch_result($result, $row, $field);
    return ($value === false) ? null : $value;
}

// Free Results
function pgsql_prepare_func_free_result($result)
{
    if (pgsql_prepare_func_is_result($result)) {
        @pg_free_result($result);
    }
    return true;
}

// Fetch Results to Array
function pgsql_prepare_func_fetch_array($result, $result_type = PGSQL_BOTH)
{
    if ($result_type === null) {
        $result_type = PGSQL_BOTH;
    }
    return pgsql_prepare_func_is_result($result) ? pg_fetch_array($result, null, $result_type) : false;
}

function pgsql_prepare_func_fetch_assoc($result)
{
    return pgsql_prepare_func_is_result($result) ? pg_fetch_assoc($result) : false;
}

function pgsql_prepare_func_fetch_row($result)
{
    return pgsql_prepare_func_is_result($result) ? pg_fetch_row($result) : false;
}

// Get Server Info
function pgsql_prepare_func_server_info($link = null)
{
    $connection = pgsql_prepare_func_conn($link);
    if (!$connection) {
        return false;
    }
    $result = pg_version($connection);
    return isset($result['server']) ? $result['server'] : false;
}

// Get Client Info
function pgsql_prepare_func_client_info($link = null)
{
    $connection = pgsql_prepare_func_conn($link);
    if (!$connection) {
        return false;
    }
    $result = pg_version($connection);
    return isset($result['client']) ? $result['client'] : false;
}

// Escape String
function pgsql_prepare_func_escape_string($string, $link = null)
{
    if ($string === null) {
        return null;
    }
    $connection = pgsql_prepare_func_conn($link);
    return $connection
        ? pg_escape_string($connection, (string)$string)
        : pg_escape_string((string)$string);
}

// SafeSQL Lite with additional SafeSQL features for PostgreSQL.
//
// BUGFIX: the old loop ran two preg_replace() calls per variable -- the first
// turned '%s' into the *quoted* literal '$1' (which PostgreSQL reads as the
// string "$1", not a parameter), and the second then consumed a second
// placeholder for the same variable.
function pgsql_prepare_func_pre_query($query_string, $query_vars = array())
{
    $result = sql_prepared_pre_query($query_string, $query_vars, 'dollar');
    if ($result === false) {
        return false;
    }

    ++$GLOBALS['NumPreQueriesArray']['pgsql_prepare'];
    return $result;
}

// Set Charset (was missing entirely)
function pgsql_prepare_func_set_charset($charset, $link = null)
{
    $connection = pgsql_prepare_func_conn($link);
    if (!$connection) {
        return true;
    }
    return (pg_set_client_encoding($connection, $charset) === 0);
}

// Get next ID for a table (e.g., from a sequence)
function pgsql_prepare_func_get_next_id($tablepre, $table, $link = null)
{
    $connection = pgsql_prepare_func_conn($link);
    if (!$connection) {
        return false;
    }

    $result = pgsql_prepare_func_query("SELECT currval($1) AS cnt;", array($tablepre . $table . "_id_seq"), $connection);
    if ($result === false) {
        return false;
    }

    $value = pgsql_prepare_func_result($result, 0, 0);
    pgsql_prepare_func_free_result($result);
    return $value;
}

// Get number of rows for table (was missing entirely)
function pgsql_prepare_func_get_num_rows($tablepre, $table, $link = null)
{
    $connection = pgsql_prepare_func_conn($link);
    if (!$connection) {
        return false;
    }

    $sql = "SELECT COUNT(*) AS cnt FROM " . sql_quote_identifier($tablepre . $table, 'double');
    $result = pgsql_prepare_func_query($sql, array(), $connection);
    if ($result === false) {
        return false;
    }

    $row = pgsql_prepare_func_fetch_assoc($result);
    pgsql_prepare_func_free_result($result);

    return (is_array($row) && isset($row['cnt'])) ? (int)$row['cnt'] : 0;
}

function pgsql_prepare_func_count_rows($query, $link = null, $countname = "cnt")
{
    $result = pgsql_prepare_func_query($query, $link);
    if ($result === false) {
        return false;
    }

    $row = pgsql_prepare_func_fetch_assoc($result);
    $count = (is_array($row) && isset($row[$countname])) ? $row[$countname] : 0;

    pgsql_prepare_func_free_result($result);
    return $count;
}

function pgsql_prepare_func_count_rows_alt($query, $link = null)
{
    $result = pgsql_prepare_func_query($query, $link);
    if ($result === false) {
        return false;
    }

    $row = pgsql_prepare_func_fetch_assoc($result);
    $count = is_array($row) ? reset($row) : 0;

    pgsql_prepare_func_free_result($result);
    return $count;
}
