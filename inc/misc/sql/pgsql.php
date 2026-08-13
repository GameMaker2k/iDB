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

    $FileInfo: pgsql.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/

$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "pgsql.php" || $File3Name == "/pgsql.php") {
    @header('Location: index.php');
    exit();
}

if (!isset($GLOBALS['NumPreQueriesArray']['pgsql'])) {
    $GLOBALS['NumPreQueriesArray']['pgsql'] = 0;
}
if (!isset($GLOBALS['NumQueriesArray']['pgsql'])) {
    $GLOBALS['NumQueriesArray']['pgsql'] = 0;
}

// pg_connect() returns a resource before PHP 8.1 and a PgSql\Connection after.
function pgsql_func_is_conn($conn)
{
    if (is_resource($conn)) {
        return true;
    }
    return is_object($conn) && (get_class($conn) === 'PgSql\\Connection');
}

function pgsql_func_conn($link = null)
{
    if (pgsql_func_is_conn($link)) {
        return $link;
    }
    if (isset($GLOBALS['SQLStat']) && pgsql_func_is_conn($GLOBALS['SQLStat'])) {
        return $GLOBALS['SQLStat'];
    }
    return null;
}

function pgsql_func_is_result($result)
{
    if (is_resource($result)) {
        return true;
    }
    return is_object($result) && (get_class($result) === 'PgSql\\Result');
}

// PostgreSQL Error handling functions
function pgsql_func_error($link = null)
{
    $connection = pgsql_func_conn($link);
    return $connection ? pg_last_error($connection) : "No valid PostgreSQL connection.";
}

// PostgreSQL has no numeric error codes; the SQLSTATE is the closest match.
function pgsql_func_errno($link = null)
{
    $connection = pgsql_func_conn($link);
    if (!$connection) {
        return 0;
    }
    $message = pg_last_error($connection);
    return ($message === '') ? 0 : $message;
}

function pgsql_func_errorno($link = null)
{
    $connection = pgsql_func_conn($link);
    if (!$connection) {
        return "No valid PostgreSQL connection.";
    }
    $message = pg_last_error($connection);
    return ($message === '') ? "" : $message;
}

function pgsql_func_query($query, $params_or_link = null, $maybe_link = null)
{
    list($sql, $params, $link) = sql_resolve_query_args($query, $params_or_link, $maybe_link);

    $connection = pgsql_func_conn($link);
    if (!$connection) {
        output_error("SQL Error: No valid PostgreSQL connection.", E_USER_ERROR);
        return false;
    }

    if (!is_string($sql) || trim($sql) === '') {
        output_error("SQL Error: Query is empty.", E_USER_ERROR);
        return false;
    }

    // If params came in from a prepared-style pre_query, run them through
    // pg_query_params so this driver stays call-compatible.
    if (!empty($params)) {
        $result = @pg_query_params($connection, $sql, pgsql_func_normalize_params($params));
    } else {
        $result = @pg_query($connection, $sql);
    }

    if ($result === false) {
        output_error("SQL Error: " . pgsql_func_error($connection), E_USER_ERROR);
        return false;
    }

    ++$GLOBALS['NumQueriesArray']['pgsql'];
    return $result;
}

// pg_* wants scalars/strings; booleans have to be sent as t/f.
function pgsql_func_normalize_params($params)
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

// Fetch Number of Rows
function pgsql_func_num_rows($result)
{
    if (!pgsql_func_is_result($result)) {
        return false;
    }
    $num = pg_num_rows($result);
    return ($num === false) ? false : $num;
}

// Connect to PostgreSQL database
function pgsql_func_connect_db($server, $username, $password, $database = null, $new_link = false)
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

function pgsql_func_disconnect_db($link = null)
{
    $connection = pgsql_func_conn($link);
    return $connection ? pg_close($connection) : false;
}

// Query Results
// BUGFIX: the field name used to be wrapped in literal double quotes before
// being handed to pg_fetch_result(), which made every named lookup fail.
function pgsql_func_result($result, $row, $field = 0)
{
    if (!pgsql_func_is_result($result)) {
        return null;
    }

    $value = @pg_fetch_result($result, $row, $field);
    return ($value === false) ? null : $value;
}

// Free Results
function pgsql_func_free_result($result)
{
    if (pgsql_func_is_result($result)) {
        @pg_free_result($result);
    }
    return true;
}

// Fetch Results to Array
// BUGFIX: the null default fell back to CUBRID_BOTH.
function pgsql_func_fetch_array($result, $result_type = PGSQL_BOTH)
{
    if ($result_type === null) {
        $result_type = PGSQL_BOTH;
    }
    return pgsql_func_is_result($result) ? pg_fetch_array($result, null, $result_type) : false;
}

function pgsql_func_fetch_assoc($result)
{
    return pgsql_func_is_result($result) ? pg_fetch_assoc($result) : false;
}

function pgsql_func_fetch_row($result)
{
    return pgsql_func_is_result($result) ? pg_fetch_row($result) : false;
}

// Get Server Info
function pgsql_func_server_info($link = null)
{
    $connection = pgsql_func_conn($link);
    if (!$connection) {
        return false;
    }
    $result = pg_version($connection);
    return isset($result['server']) ? $result['server'] : false;
}

// Get Client Info
function pgsql_func_client_info($link = null)
{
    $connection = pgsql_func_conn($link);
    if (!$connection) {
        return false;
    }
    $result = pg_version($connection);
    return isset($result['client']) ? $result['client'] : false;
}

// Escape String
function pgsql_func_escape_string($string, $link = null)
{
    if ($string === null) {
        return null;
    }
    $connection = pgsql_func_conn($link);
    return $connection
        ? pg_escape_string($connection, (string)$string)
        : pg_escape_string((string)$string);
}

// SafeSQL Lite Source Code by Cool Dude 2k
function pgsql_func_pre_query($query_string, $query_vars = array())
{
    $result = sql_safe_pre_query($query_string, $query_vars, function ($value) {
        return pgsql_func_escape_string($value);
    });

    if ($result === false) {
        return false;
    }

    ++$GLOBALS['NumPreQueriesArray']['pgsql'];
    return $result;
}

// Set Charset
function pgsql_func_set_charset($charset, $link = null)
{
    $connection = pgsql_func_conn($link);
    if (!$connection) {
        return true;
    }
    return (pg_set_client_encoding($connection, $charset) === 0);
}

// Get next id for stuff
function pgsql_func_get_next_id($tablepre, $table, $link = null)
{
    $connection = pgsql_func_conn($link);
    if (!$connection) {
        return false;
    }

    $sequence = pg_escape_string($connection, $tablepre . $table . "_id_seq");
    $result = pgsql_func_query("SELECT currval('" . $sequence . "') AS cnt;", $connection);
    if ($result === false) {
        return false;
    }

    $value = pgsql_func_result($result, 0, 0);
    pgsql_func_free_result($result);
    return $value;
}

// Get number of rows for table
// BUGFIX: this used MySQL's "SHOW TABLE STATUS", which is not valid on
// PostgreSQL, and had unreachable code after the return statement.
function pgsql_func_get_num_rows($tablepre, $table, $link = null)
{
    $connection = pgsql_func_conn($link);
    if (!$connection) {
        return false;
    }

    $sql = "SELECT COUNT(*) AS cnt FROM " . sql_quote_identifier($tablepre . $table, 'double');
    $result = pgsql_func_query($sql, $connection);
    if ($result === false) {
        return false;
    }

    $row = pgsql_func_fetch_assoc($result);
    pgsql_func_free_result($result);

    return (is_array($row) && isset($row['cnt'])) ? (int)$row['cnt'] : 0;
}

function pgsql_func_count_rows($query, $link = null, $countname = "cnt")
{
    $result = pgsql_func_query($query, $link);
    if ($result === false) {
        return false;
    }

    $row = pgsql_func_fetch_assoc($result);
    $count = (is_array($row) && isset($row[$countname])) ? $row[$countname] : 0;

    pgsql_func_free_result($result);
    return $count;
}

function pgsql_func_count_rows_alt($query, $link = null)
{
    $result = pgsql_func_query($query, $link);
    if ($result === false) {
        return false;
    }

    $row = pgsql_func_fetch_assoc($result);
    $count = is_array($row) ? reset($row) : 0;

    pgsql_func_free_result($result);
    return $count;
}
