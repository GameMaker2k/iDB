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

    $FileInfo: cubrid.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/

$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "cubrid.php" || $File3Name == "/cubrid.php") {
    @header('Location: index.php');
    exit();
}

if (!isset($GLOBALS['NumPreQueriesArray']['cubrid'])) {
    $GLOBALS['NumPreQueriesArray']['cubrid'] = 0;
}
if (!isset($GLOBALS['NumQueriesArray']['cubrid'])) {
    $GLOBALS['NumQueriesArray']['cubrid'] = 0;
}

function cubrid_func_conn($link = null)
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
function cubrid_func_error($link = null)
{
    return cubrid_error_msg();
}

function cubrid_func_errno($link = null)
{
    return cubrid_error_code();
}

function cubrid_func_errorno($link = null)
{
    return cubrid_func_errno($link) . ": " . cubrid_func_error($link);
}

function cubrid_func_query($query, $params_or_link = null, $maybe_link = null)
{
    list($sql, $params, $link) = sql_resolve_query_args($query, $params_or_link, $maybe_link);

    $connection = cubrid_func_conn($link);

    if (!$connection) {
        output_error("SQL Error: No valid CUBRID connection.", E_USER_ERROR);
        return false;
    }

    if (!is_string($sql) || trim($sql) === '') {
        output_error("SQL Error: Query is empty.", E_USER_ERROR);
        return false;
    }

    if (!empty($params)) {
        $sql = sql_bind_placeholders($sql, $params, function ($value) use ($connection) {
            return cubrid_real_escape_string($value, $connection);
        });
    }

    $result = @cubrid_query($sql, $connection);

    if ($result === false) {
        output_error("SQL Error: " . cubrid_func_error($connection), E_USER_ERROR);
        return false;
    }

    ++$GLOBALS['NumQueriesArray']['cubrid'];
    return $result;
}

// Fetch number of rows
function cubrid_func_num_rows($result)
{
    if (!$result) {
        return false;
    }
    $num = @cubrid_num_rows($result);
    return ($num === false) ? false : $num;
}

// Connect to CUBRID database
function cubrid_func_connect_db($server, $username, $password, $database = null, $new_link = false)
{
    $myport = 30000;
    $hostex = explode(":", $server);

    if (isset($hostex[1])) {
        $server = $hostex[0];
        $myport = is_numeric($hostex[1]) ? (int)$hostex[1] : $myport;
    }

    $link = @cubrid_connect($server, $myport, $database, $username, $password);

    // BUGFIX: cubrid_set_autocommit() used to run before the connection was
    // validated, so a failed connect produced a second error instead.
    if (!$link) {
        output_error("Not connected: " . cubrid_func_error(), E_USER_ERROR);
        return false;
    }

    @cubrid_set_autocommit($link, CUBRID_AUTOCOMMIT_TRUE);

    return $link;
}

function cubrid_func_disconnect_db($link = null)
{
    $connection = cubrid_func_conn($link);
    return $connection ? cubrid_disconnect($connection) : false;
}

// Query Results
function cubrid_func_result($result, $row, $field = 0)
{
    if (!$result) {
        return null;
    }

    if ($field !== null && !is_numeric($field)) {
        $field = strtolower($field);
    }

    $value = @cubrid_result($result, $row, $field);
    return ($value === false) ? null : $value;
}

// Free Results
// BUGFIX: this used to call both cubrid_free_result() and
// cubrid_close_request() on the same handle -- a double free. Closing the
// request releases the result.
function cubrid_func_free_result($result)
{
    if (!$result) {
        return true;
    }
    @cubrid_close_request($result);
    return true;
}

// Fetch Results to Array
function cubrid_func_fetch_array($result, $result_type = CUBRID_BOTH)
{
    if ($result_type === null) {
        $result_type = CUBRID_BOTH;
    }
    return $result ? cubrid_fetch_array($result, $result_type) : false;
}

function cubrid_func_fetch_assoc($result)
{
    return $result ? cubrid_fetch_assoc($result) : false;
}

function cubrid_func_fetch_row($result)
{
    return $result ? cubrid_fetch_row($result) : false;
}

// Get Server Info
function cubrid_func_server_info($link = null)
{
    $connection = cubrid_func_conn($link);
    return $connection ? cubrid_get_server_info($connection) : cubrid_get_server_info();
}

function cubrid_func_client_info($link = null)
{
    return cubrid_get_client_info();
}

// Escape String
function cubrid_func_escape_string($string, $link = null)
{
    if ($string === null) {
        return null;
    }
    $connection = cubrid_func_conn($link);
    return $connection
        ? cubrid_real_escape_string((string)$string, $connection)
        : cubrid_real_escape_string((string)$string);
}

// SafeSQL Lite Source Code by Cool Dude 2k
function cubrid_func_pre_query($query_string, $query_vars = array())
{
    $result = sql_safe_pre_query($query_string, $query_vars, function ($value) {
        return cubrid_func_escape_string($value);
    });

    if ($result === false) {
        return false;
    }

    ++$GLOBALS['NumPreQueriesArray']['cubrid'];
    return $result;
}

// Set Charset (no-op)
function cubrid_func_set_charset($charset, $link = null)
{
    return true;
}

// Get next id for stuff
function cubrid_func_get_next_id($tablepre, $table, $link = null)
{
    $connection = cubrid_func_conn($link);
    if (!$connection) {
        return false;
    }

    $sql = "SELECT " . sql_quote_identifier($tablepre . $table . "_ai_id", 'double') . ".current_value AS cnt";
    $result = cubrid_func_query($sql, $connection);
    if ($result === false) {
        return false;
    }

    $value = cubrid_func_result($result, 0, 0);
    cubrid_func_free_result($result);
    return $value;
}

// Get number of rows for table
// BUGFIX: this used MySQL's "SHOW TABLE STATUS", which CUBRID does not
// support.
function cubrid_func_get_num_rows($tablepre, $table, $link = null)
{
    $connection = cubrid_func_conn($link);
    if (!$connection) {
        return false;
    }

    $sql = "SELECT COUNT(*) AS cnt FROM " . sql_quote_identifier($tablepre . $table, 'double');
    $result = cubrid_func_query($sql, $connection);
    if ($result === false) {
        return false;
    }

    $row = cubrid_func_fetch_assoc($result);
    cubrid_func_free_result($result);

    return (is_array($row) && isset($row['cnt'])) ? (int)$row['cnt'] : 0;
}

function cubrid_func_count_rows($query, $link = null, $countname = "cnt")
{
    $result = cubrid_func_query($query, $link);
    if ($result === false) {
        return false;
    }

    $row = cubrid_func_fetch_assoc($result);
    $count = (is_array($row) && isset($row[$countname])) ? $row[$countname] : 0;

    cubrid_func_free_result($result);
    return $count;
}

function cubrid_func_count_rows_alt($query, $link = null)
{
    $result = cubrid_func_query($query, $link);
    if ($result === false) {
        return false;
    }

    $row = cubrid_func_fetch_assoc($result);
    $count = is_array($row) ? reset($row) : 0;

    cubrid_func_free_result($result);
    return $count;
}
