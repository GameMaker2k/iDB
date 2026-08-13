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

    $FileInfo: cubrid_prepare.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/

$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "cubrid_prepare.php" || $File3Name == "/cubrid_prepare.php") {
    @header('Location: index.php');
    exit();
}

if (!isset($GLOBALS['NumPreQueriesArray']['cubrid_prepare'])) {
    $GLOBALS['NumPreQueriesArray']['cubrid_prepare'] = 0;
}
if (!isset($GLOBALS['NumQueriesArray']['cubrid_prepare'])) {
    $GLOBALS['NumQueriesArray']['cubrid_prepare'] = 0;
}

function cubrid_prepare_func_conn($link = null)
{
    if ($link !== null && $link !== false) {
        return $link;
    }
    if (isset($GLOBALS['SQLStat']) && $GLOBALS['SQLStat']) {
        return $GLOBALS['SQLStat'];
    }
    return null;
}

// CUBRID Error handling functions
function cubrid_prepare_func_error($link = null)
{
    return cubrid_error_msg();
}

function cubrid_prepare_func_errno($link = null)
{
    return cubrid_error_code();
}

function cubrid_prepare_func_errorno($link = null)
{
    return cubrid_prepare_func_errno($link) . ": " . cubrid_prepare_func_error($link);
}

function cubrid_prepare_func_query($query, $params_or_link = null, $maybe_link = null)
{
    list($sql, $params, $link) = sql_resolve_query_args($query, $params_or_link, $maybe_link);

    $db = cubrid_prepare_func_conn($link);

    if (!$db) {
        output_error("SQL Error: No valid CUBRID connection.", E_USER_ERROR);
        return false;
    }

    if (!is_string($sql) || trim($sql) === '') {
        output_error("SQL Error: Query is empty.", E_USER_ERROR);
        return false;
    }

    $stmt = @cubrid_prepare($db, $sql);
    if ($stmt === false) {
        output_error("SQL Error (Prepare): " . cubrid_prepare_func_error($db), E_USER_ERROR);
        return false;
    }

    // Bind parameters dynamically.
    // BUGFIX: cubrid_bind() takes a *string* type name ("int", "string", ...),
    // not the CUBRID_INTEGER / CUBRID_BOOL / CUBRID_NULL constants that were
    // being passed before.
    $index = 1;
    foreach ($params as $value) {
        if (is_int($value)) {
            $ok = @cubrid_bind($stmt, $index, $value, 'int');
        } elseif (is_float($value)) {
            $ok = @cubrid_bind($stmt, $index, $value, 'double');
        } elseif (is_bool($value)) {
            $ok = @cubrid_bind($stmt, $index, $value ? 1 : 0, 'int');
        } elseif (is_null($value)) {
            $ok = @cubrid_bind($stmt, $index, null, 'null');
        } else {
            $ok = @cubrid_bind($stmt, $index, (string)$value, 'string');
        }

        if ($ok === false) {
            output_error("SQL Error (Bind): " . cubrid_prepare_func_error($db), E_USER_ERROR);
            @cubrid_close_request($stmt);
            return false;
        }

        $index++;
    }

    if (!@cubrid_execute($stmt)) {
        output_error("SQL Error (Execution): " . cubrid_prepare_func_error($db), E_USER_ERROR);
        @cubrid_close_request($stmt);
        return false;
    }

    ++$GLOBALS['NumQueriesArray']['cubrid_prepare'];
    return $stmt;
}

// Fetch number of rows
function cubrid_prepare_func_num_rows($stmt)
{
    if (!$stmt) {
        return false;
    }
    $num = @cubrid_num_rows($stmt);
    return ($num === false) ? false : $num;
}

// Connect to CUBRID database
function cubrid_prepare_func_connect_db($server, $username, $password, $database = null, $new_link = false)
{
    $myport = 30000;
    $hostex = explode(":", $server);

    if (isset($hostex[1])) {
        $server = $hostex[0];
        $myport = is_numeric($hostex[1]) ? (int)$hostex[1] : $myport;
    }

    $link = @cubrid_connect($server, $myport, $database, $username, $password);

    if (!$link) {
        output_error("Not connected: " . cubrid_prepare_func_error(), E_USER_ERROR);
        return false;
    }

    @cubrid_set_autocommit($link, CUBRID_AUTOCOMMIT_TRUE);

    return $link;
}

function cubrid_prepare_func_disconnect_db($link = null)
{
    $connection = cubrid_prepare_func_conn($link);
    return $connection ? cubrid_disconnect($connection) : false;
}

// Query Results
// BUGFIX: the old version fetched whatever row the cursor happened to be on
// and ignored $row entirely, and fetched with CUBRID_NUM while allowing a
// column name for $field.
function cubrid_prepare_func_result($stmt, $row, $field = 0)
{
    if (!$stmt) {
        return null;
    }

    if ($field !== null && !is_numeric($field)) {
        $field = strtolower($field);
    }

    // Move the cursor to the requested row (CUBRID rows are 1-based).
    if (!@cubrid_move_cursor($stmt, $row + 1, CUBRID_CURSOR_FIRST)) {
        return null;
    }

    $value = @cubrid_fetch($stmt, CUBRID_BOTH);
    if (!is_array($value)) {
        return null;
    }

    return isset($value[$field]) ? $value[$field] : null;
}

// Free Results
// BUGFIX: was calling both cubrid_close_request() and cubrid_free_result().
function cubrid_prepare_func_free_result($stmt)
{
    if (!$stmt) {
        return true;
    }
    @cubrid_close_request($stmt);
    return true;
}

// Fetch Results to Array
function cubrid_prepare_func_fetch_array($stmt, $result_type = CUBRID_BOTH)
{
    if ($result_type === null) {
        $result_type = CUBRID_BOTH;
    }
    return $stmt ? cubrid_fetch($stmt, $result_type) : false;
}

function cubrid_prepare_func_fetch_assoc($stmt)
{
    return $stmt ? cubrid_fetch($stmt, CUBRID_ASSOC) : false;
}

function cubrid_prepare_func_fetch_row($stmt)
{
    return $stmt ? cubrid_fetch($stmt, CUBRID_NUM) : false;
}

// Get Server Info
function cubrid_prepare_func_server_info($link = null)
{
    $connection = cubrid_prepare_func_conn($link);
    return $connection ? cubrid_get_server_info($connection) : cubrid_get_server_info();
}

function cubrid_prepare_func_client_info($link = null)
{
    return cubrid_get_client_info();
}

// Escape String
function cubrid_prepare_func_escape_string($string, $link = null)
{
    if ($string === null) {
        return null;
    }
    $connection = cubrid_prepare_func_conn($link);
    return $connection
        ? cubrid_real_escape_string((string)$string, $connection)
        : cubrid_real_escape_string((string)$string);
}

// SafeSQL Lite with additional SafeSQL features
function cubrid_prepare_func_pre_query($query_string, $query_vars = array())
{
    $result = sql_prepared_pre_query($query_string, $query_vars, 'qmark');
    if ($result === false) {
        return false;
    }

    ++$GLOBALS['NumPreQueriesArray']['cubrid_prepare'];
    return $result;
}

// Set Charset (was missing entirely; no-op like the non-prepared driver)
function cubrid_prepare_func_set_charset($charset, $link = null)
{
    return true;
}

// Get next id for stuff
function cubrid_prepare_func_get_next_id($tablepre, $table, $link = null)
{
    $connection = cubrid_prepare_func_conn($link);
    if (!$connection) {
        return false;
    }

    $sql = "SELECT " . sql_quote_identifier($tablepre . $table . "_ai_id", 'double') . ".current_value AS cnt";
    $stmt = cubrid_prepare_func_query($sql, array(), $connection);
    if ($stmt === false) {
        return false;
    }

    $value = cubrid_prepare_func_result($stmt, 0, 0);
    cubrid_prepare_func_free_result($stmt);
    return $value;
}

// Get number of rows for table (was missing entirely)
function cubrid_prepare_func_get_num_rows($tablepre, $table, $link = null)
{
    $connection = cubrid_prepare_func_conn($link);
    if (!$connection) {
        return false;
    }

    $sql = "SELECT COUNT(*) AS cnt FROM " . sql_quote_identifier($tablepre . $table, 'double');
    $stmt = cubrid_prepare_func_query($sql, array(), $connection);
    if ($stmt === false) {
        return false;
    }

    $row = cubrid_prepare_func_fetch_assoc($stmt);
    cubrid_prepare_func_free_result($stmt);

    return (is_array($row) && isset($row['cnt'])) ? (int)$row['cnt'] : 0;
}

function cubrid_prepare_func_count_rows($query, $link = null, $countname = "cnt")
{
    $result = cubrid_prepare_func_query($query, $link);
    if ($result === false) {
        return false;
    }

    $row = cubrid_prepare_func_fetch_assoc($result);
    $count = (is_array($row) && isset($row[$countname])) ? $row[$countname] : 0;

    cubrid_prepare_func_free_result($result);
    return $count;
}

function cubrid_prepare_func_count_rows_alt($query, $link = null)
{
    $result = cubrid_prepare_func_query($query, $link);
    if ($result === false) {
        return false;
    }

    $row = cubrid_prepare_func_fetch_assoc($result);
    $count = is_array($row) ? reset($row) : 0;

    cubrid_prepare_func_free_result($result);
    return $count;
}
