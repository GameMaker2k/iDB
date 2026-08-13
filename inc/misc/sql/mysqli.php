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

    $FileInfo: mysqli.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/

$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "mysqli.php" || $File3Name == "/mysqli.php") {
    @header('Location: index.php');
    exit();
}

if (!isset($GLOBALS['NumPreQueriesArray']['mysqli'])) {
    $GLOBALS['NumPreQueriesArray']['mysqli'] = 0;
}
if (!isset($GLOBALS['NumQueriesArray']['mysqli'])) {
    $GLOBALS['NumQueriesArray']['mysqli'] = 0;
}

// Resolve the connection to use: explicit link, else the global $SQLStat.
function mysqli_func_conn($link = null)
{
    if ($link instanceof mysqli) {
        return $link;
    }
    if (isset($GLOBALS['SQLStat']) && $GLOBALS['SQLStat'] instanceof mysqli) {
        return $GLOBALS['SQLStat'];
    }
    return null;
}

// MySQLi Error handling functions
function mysqli_func_error($link = null)
{
    $connection = mysqli_func_conn($link);
    return $connection ? mysqli_error($connection) : "No valid MySQLi connection.";
}

function mysqli_func_errno($link = null)
{
    $connection = mysqli_func_conn($link);
    return $connection ? mysqli_errno($connection) : 0;
}

function mysqli_func_errorno($link = null)
{
    $connection = mysqli_func_conn($link);
    if (!$connection) {
        return "No valid MySQLi connection.";
    }
    $result = mysqli_error($connection);
    $resultno = mysqli_errno($connection);
    return ($result == "" && (int)$resultno === 0) ? "" : "$resultno: $result";
}

// Execute a query.
// Accepts ($sql), ($sql, $link), ($sql, $params) and ($sql, $params, $link) as
// well as the array($sql, $params) form produced by a prepared-style
// pre_query(), so it is call-compatible with the other drivers.
function mysqli_func_query($query, $params_or_link = null, $maybe_link = null)
{
    list($sql, $params, $link) = sql_resolve_query_args($query, $params_or_link, $maybe_link);

    $connection = mysqli_func_conn($link);
    if (!$connection) {
        output_error("SQL Error: No valid MySQLi connection.", E_USER_ERROR);
        return false;
    }

    if (!is_string($sql) || trim($sql) === '') {
        output_error("SQL Error: Query is empty.", E_USER_ERROR);
        return false;
    }

    if (!empty($params)) {
        $sql = sql_bind_placeholders($sql, $params, function ($value) use ($connection) {
            return mysqli_real_escape_string($connection, $value);
        });
    }

    try {
        $result = mysqli_query($connection, $sql);
    } catch (Exception $e) {
        output_error("SQL Error: " . $e->getMessage(), E_USER_ERROR);
        return false;
    }

    if ($result === false) {
        output_error("SQL Error: " . mysqli_error($connection), E_USER_ERROR);
        return false;
    }

    ++$GLOBALS['NumQueriesArray']['mysqli'];
    return $result;
}

// Fetch Number of Rows
function mysqli_func_num_rows($result)
{
    if (!($result instanceof mysqli_result)) {
        return false;
    }
    $num = mysqli_num_rows($result);
    return ($num === false) ? false : $num;
}

// Connect to MySQLi database
function mysqli_func_connect_db($server, $username, $password, $database = null, $new_link = false)
{
    $myport = 3306;
    $hostex = explode(":", $server);

    if (isset($hostex[1])) {
        $server = $hostex[0];
        $myport = is_numeric($hostex[1]) ? (int)$hostex[1] : $myport;
    }

    // PHP 8.1 turns mysqli errors into exceptions by default; this driver
    // reports errors through output_error() instead.
    if (function_exists('mysqli_report')) {
        @mysqli_report(MYSQLI_REPORT_OFF);
    }

    try {
        $link = ($database === null)
            ? @mysqli_connect($server, $username, $password, null, $myport)
            : @mysqli_connect($server, $username, $password, $database, $myport);
    } catch (Exception $e) {
        output_error("MySQLi Error: " . $e->getMessage(), E_USER_ERROR);
        return false;
    }

    if (!$link) {
        output_error("MySQLi Error " . mysqli_connect_errno() . ": " . mysqli_connect_error(), E_USER_ERROR);
        return false;
    }

    $result = mysqli_func_query("SET SESSION SQL_MODE='ANSI,ANSI_QUOTES,TRADITIONAL,STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION,NO_AUTO_VALUE_ON_ZERO';", $link);

    if ($result === false) {
        output_error("SQL Error: " . mysqli_func_error($link), E_USER_ERROR);
        return false;
    }

    return $link;
}

function mysqli_func_disconnect_db($link = null)
{
    $connection = mysqli_func_conn($link);
    return $connection ? mysqli_close($connection) : false;
}

// Query Results
function mysqli_func_result($result, $row, $field = 0)
{
    if (!($result instanceof mysqli_result)) {
        return null;
    }

    if (mysqli_data_seek($result, $row) === false) {
        return null;
    }

    $trow = mysqli_fetch_array($result, MYSQLI_BOTH);
    if (!is_array($trow)) {
        return null;
    }

    return isset($trow[$field]) ? $trow[$field] : null;
}

// Free Results
function mysqli_func_free_result($result)
{
    if ($result instanceof mysqli_result) {
        mysqli_free_result($result);
    }
    return true;
}

// Fetch Results to Array
// BUGFIX: the null default used to fall back to CUBRID_BOTH, which is only
// defined when the CUBRID extension is loaded.
function mysqli_func_fetch_array($result, $result_type = MYSQLI_BOTH)
{
    if ($result_type === null) {
        $result_type = MYSQLI_BOTH;
    }
    return ($result instanceof mysqli_result) ? mysqli_fetch_array($result, $result_type) : false;
}

function mysqli_func_fetch_assoc($result)
{
    return ($result instanceof mysqli_result) ? mysqli_fetch_assoc($result) : false;
}

function mysqli_func_fetch_row($result)
{
    return ($result instanceof mysqli_result) ? mysqli_fetch_row($result) : false;
}

// Get Server Info
function mysqli_func_server_info($link = null)
{
    $connection = mysqli_func_conn($link);
    return $connection ? mysqli_get_server_info($connection) : false;
}

// Get Client Info
function mysqli_func_client_info($link = null)
{
    return mysqli_get_client_info();
}

// Escape String
function mysqli_func_escape_string($string, $link = null)
{
    if ($string === null) {
        return null;
    }
    $connection = mysqli_func_conn($link);
    if (!$connection) {
        return false;
    }
    return mysqli_real_escape_string($connection, (string)$string);
}

// SafeSQL Lite Source Code by Cool Dude 2k
function mysqli_func_pre_query($query_string, $query_vars = array())
{
    $result = sql_safe_pre_query($query_string, $query_vars, function ($value) {
        $escaped = mysqli_func_escape_string($value);
        return ($escaped === false) ? str_replace("'", "''", (string)$value) : $escaped;
    });

    if ($result === false) {
        return false;
    }

    ++$GLOBALS['NumPreQueriesArray']['mysqli'];
    return $result;
}

// Set Charset
function mysqli_func_set_charset($charset, $link = null)
{
    $connection = mysqli_func_conn($link);
    if (!$connection) {
        output_error("SQL Error: No valid MySQLi connection.", E_USER_ERROR);
        return false;
    }

    if (function_exists('mysqli_set_charset')) {
        if (mysqli_set_charset($connection, $charset) === false) {
            output_error("SQL Error: " . mysqli_func_error($connection), E_USER_ERROR);
            return false;
        }
        return true;
    }

    $escaped = mysqli_real_escape_string($connection, $charset);
    if (mysqli_func_query("SET CHARACTER SET '$escaped'", $connection) === false) {
        return false;
    }
    if (mysqli_func_query("SET NAMES '$escaped'", $connection) === false) {
        return false;
    }
    return true;
}

// Get next id for stuff
// BUGFIX: mysqli_insert_id(null) is a TypeError on PHP 8; fall back to $SQLStat.
function mysqli_func_get_next_id($tablepre, $table, $link = null)
{
    $connection = mysqli_func_conn($link);
    return $connection ? mysqli_insert_id($connection) : false;
}

// Get number of rows for table
// BUGFIX: used $SQLStat without declaring it global, and SHOW TABLE STATUS
// only returns an estimate on InnoDB.
function mysqli_func_get_num_rows($tablepre, $table, $link = null)
{
    $connection = mysqli_func_conn($link);
    if (!$connection) {
        return false;
    }

    $sql = "SELECT COUNT(*) AS cnt FROM " . sql_quote_identifier($tablepre . $table, 'backtick');
    $result = mysqli_func_query($sql, $connection);
    if ($result === false) {
        return false;
    }

    $row = mysqli_func_fetch_assoc($result);
    mysqli_func_free_result($result);

    return (is_array($row) && isset($row['cnt'])) ? (int)$row['cnt'] : 0;
}

// Fetch Number of Rows using COUNT in a single query
// BUGFIX: these used to call mysqli_func_query($query, [], $link), which put
// an empty array into the $link slot.
function mysqli_func_count_rows($query, $link = null, $countname = "cnt")
{
    $result = mysqli_func_query($query, $link);
    if ($result === false) {
        return false;
    }

    $row = mysqli_func_fetch_assoc($result);
    $count = (is_array($row) && isset($row[$countname])) ? $row[$countname] : 0;

    mysqli_func_free_result($result);
    return $count;
}

function mysqli_func_count_rows_alt($query, $link = null)
{
    $result = mysqli_func_query($query, $link);
    if ($result === false) {
        return false;
    }

    $row = mysqli_func_fetch_assoc($result);
    $count = is_array($row) ? reset($row) : 0;

    mysqli_func_free_result($result);
    return $count;
}
