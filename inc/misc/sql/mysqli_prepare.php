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

    $FileInfo: mysqli_prepare.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/

$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "mysqli_prepare.php" || $File3Name == "/mysqli_prepare.php") {
    @header('Location: index.php');
    exit();
}

if (!isset($GLOBALS['NumPreQueriesArray']['mysqli_prepare'])) {
    $GLOBALS['NumPreQueriesArray']['mysqli_prepare'] = 0;
}
if (!isset($GLOBALS['NumQueriesArray']['mysqli_prepare'])) {
    $GLOBALS['NumQueriesArray']['mysqli_prepare'] = 0;
}

function mysqli_prepare_func_conn($link = null)
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
function mysqli_prepare_func_error($link = null)
{
    $connection = mysqli_prepare_func_conn($link);
    return $connection ? mysqli_error($connection) : "No valid MySQLi connection.";
}

function mysqli_prepare_func_errno($link = null)
{
    $connection = mysqli_prepare_func_conn($link);
    return $connection ? mysqli_errno($connection) : 0;
}

function mysqli_prepare_func_errorno($link = null)
{
    $connection = mysqli_prepare_func_conn($link);
    if (!$connection) {
        return "No valid MySQLi connection.";
    }
    $result = mysqli_error($connection);
    $resultno = mysqli_errno($connection);
    return ($result == "" && (int)$resultno === 0) ? "" : "$resultno: $result";
}

// Execute a query using prepared statements.
// Returns a mysqli_result for statements that produce a result set, true for
// everything else, so the fetch_* helpers behave exactly like the non-prepared
// driver.
function mysqli_prepare_func_query($query, $params_or_link = null, $maybe_link = null)
{
    list($sql, $params, $link) = sql_resolve_query_args($query, $params_or_link, $maybe_link);

    $connection = mysqli_prepare_func_conn($link);
    if (!$connection) {
        output_error("SQL Error: No valid MySQLi connection.", E_USER_ERROR);
        return false;
    }

    if (!is_string($sql) || trim($sql) === '') {
        output_error("SQL Error: Query is empty.", E_USER_ERROR);
        return false;
    }

    if (function_exists('mysqli_report')) {
        @mysqli_report(MYSQLI_REPORT_OFF);
    }

    // No parameters: a plain query is cheaper and returns the same shape.
    if (count($params) === 0 && strpos($sql, '?') === false) {
        $result = mysqli_query($connection, $sql);
        if ($result === false) {
            output_error("SQL Error: " . mysqli_error($connection), E_USER_ERROR);
            return false;
        }
        ++$GLOBALS['NumQueriesArray']['mysqli_prepare'];
        return $result;
    }

    $stmt = mysqli_prepare($connection, $sql);
    if (!$stmt) {
        output_error("SQL Error (Prepare): " . mysqli_error($connection), E_USER_ERROR);
        return false;
    }

    if (count($params) > 0) {
        $types = '';
        $bind = array();

        foreach ($params as $value) {
            if (is_int($value)) {
                $types .= 'i';
            } elseif (is_float($value)) {
                $types .= 'd';
            } elseif (is_bool($value)) {
                $types .= 'i';
                $value = $value ? 1 : 0;
            } else {
                $types .= 's';
            }
            $bind[] = $value;
        }

        $refs = array();
        foreach ($bind as $key => $value) {
            $refs[$key] = &$bind[$key];
        }
        array_unshift($refs, $types);

        if (!call_user_func_array(array($stmt, 'bind_param'), $refs)) {
            $err = mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
            output_error("SQL Error (Bind): " . $err, E_USER_ERROR);
            return false;
        }
    }

    if (!mysqli_stmt_execute($stmt)) {
        $err = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        output_error("SQL Error (Execute): " . $err, E_USER_ERROR);
        return false;
    }

    // BUGFIX: the counter used to be incremented two or three times per query.
    ++$GLOBALS['NumQueriesArray']['mysqli_prepare'];

    if (mysqli_stmt_field_count($stmt) > 0) {
        $res = function_exists('mysqli_stmt_get_result') ? mysqli_stmt_get_result($stmt) : false;
        $err = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);

        if ($res === false) {
            output_error("SQL Error (Get Result): " . ($err !== '' ? $err : "mysqlnd is required for buffered results."), E_USER_ERROR);
            return false;
        }

        return $res;
    }

    // BUGFIX: this used to return the statement *after* closing it.
    mysqli_stmt_close($stmt);
    return true;
}

// Fetch number of rows for SELECT queries
function mysqli_prepare_func_num_rows($result)
{
    if ($result instanceof mysqli_result) {
        return mysqli_num_rows($result);
    }
    if ($result instanceof mysqli_stmt) {
        mysqli_stmt_store_result($result);
        return mysqli_stmt_num_rows($result);
    }
    return false;
}

// Connect to MySQLi database
function mysqli_prepare_func_connect_db($server, $username, $password, $database = null, $new_link = false)
{
    $myport = 3306;
    $hostex = explode(":", $server);

    if (isset($hostex[1])) {
        $server = $hostex[0];
        $myport = is_numeric($hostex[1]) ? (int)$hostex[1] : $myport;
    }

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

    $result = mysqli_prepare_func_query("SET SESSION SQL_MODE='ANSI,ANSI_QUOTES,TRADITIONAL,STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION,NO_AUTO_VALUE_ON_ZERO';", array(), $link);

    if ($result === false) {
        output_error("SQL Error: " . mysqli_prepare_func_error($link), E_USER_ERROR);
        return false;
    }

    return $link;
}

function mysqli_prepare_func_disconnect_db($link = null)
{
    $connection = mysqli_prepare_func_conn($link);
    return $connection ? mysqli_close($connection) : false;
}

// Query results fetching
function mysqli_prepare_func_result($result, $row, $field = 0)
{
    if ($result instanceof mysqli_result) {
        if (mysqli_data_seek($result, $row) === false) {
            return null;
        }
        $trow = mysqli_fetch_array($result, MYSQLI_BOTH);
        if (!is_array($trow)) {
            return null;
        }
        return isset($trow[$field]) ? $trow[$field] : null;
    }

    if (!($result instanceof mysqli_stmt)) {
        return null;
    }

    mysqli_stmt_store_result($result);
    $meta = mysqli_stmt_result_metadata($result);
    if (!$meta) {
        return null;
    }
    $fields = mysqli_fetch_fields($meta);

    $bound = array();
    $rowData = array();
    foreach ($fields as $f) {
        $rowData[$f->name] = null;
        $bound[] = &$rowData[$f->name];
    }
    call_user_func_array('mysqli_stmt_bind_result', array_merge(array($result), $bound));

    mysqli_stmt_data_seek($result, $row);
    if (!mysqli_stmt_fetch($result)) {
        return null;
    }

    if (is_int($field)) {
        $name = isset($fields[$field]) ? $fields[$field]->name : null;
    } else {
        $name = $field;
    }

    return ($name !== null && array_key_exists($name, $rowData)) ? $rowData[$name] : null;
}

// Free results
function mysqli_prepare_func_free_result($result)
{
    if ($result instanceof mysqli_result) {
        mysqli_free_result($result);
    } elseif ($result instanceof mysqli_stmt) {
        mysqli_stmt_free_result($result);
    }
    return true;
}

// Fetch results
function mysqli_prepare_func_fetch_array($result, $result_type = MYSQLI_BOTH)
{
    if ($result_type === null) {
        $result_type = MYSQLI_BOTH;
    }
    return ($result instanceof mysqli_result) ? mysqli_fetch_array($result, $result_type) : false;
}

function mysqli_prepare_func_fetch_assoc($result)
{
    return ($result instanceof mysqli_result) ? mysqli_fetch_assoc($result) : false;
}

function mysqli_prepare_func_fetch_row($result)
{
    return ($result instanceof mysqli_result) ? mysqli_fetch_row($result) : false;
}

// Bound-result fetch, kept for callers that hold a mysqli_stmt directly.
function mysqli_prepare_func_fetch_assoc_bind($stmt)
{
    static $cache = array();

    if (!($stmt instanceof mysqli_stmt)) {
        return null;
    }

    $id = function_exists('spl_object_id') ? spl_object_id($stmt) : spl_object_hash($stmt);

    if (!isset($cache[$id])) {
        mysqli_stmt_store_result($stmt);
        $meta = mysqli_stmt_result_metadata($stmt);
        if (!$meta) {
            return null;
        }

        $fields = mysqli_fetch_fields($meta);

        $row = array();
        $bind = array();
        $keys = array();

        foreach ($fields as $f) {
            $keys[] = $f->name;
            $row[$f->name] = null;
            $bind[] = &$row[$f->name];
        }

        call_user_func_array('mysqli_stmt_bind_result', array_merge(array($stmt), $bind));

        // Keep the referenced row array alive in the cache.
        $cache[$id] = array('row' => &$row, 'keys' => $keys);
    }

    if (!mysqli_stmt_fetch($stmt)) {
        return null;
    }

    $out = array();
    foreach ($cache[$id]['keys'] as $key) {
        $out[$key] = $cache[$id]['row'][$key];
    }

    return $out;
}

// Get Server Info
function mysqli_prepare_func_server_info($link = null)
{
    $connection = mysqli_prepare_func_conn($link);
    return $connection ? mysqli_get_server_info($connection) : false;
}

// Get Client Info
function mysqli_prepare_func_client_info($link = null)
{
    return mysqli_get_client_info();
}

// Escape string
function mysqli_prepare_func_escape_string($string, $link = null)
{
    if ($string === null) {
        return null;
    }
    $connection = mysqli_prepare_func_conn($link);
    if (!$connection) {
        return false;
    }
    return mysqli_real_escape_string($connection, (string)$string);
}

// SafeSQL Lite with prepared statements and placeholders
function mysqli_prepare_func_pre_query($query_string, $query_vars = array())
{
    $result = sql_prepared_pre_query($query_string, $query_vars, 'qmark');
    if ($result === false) {
        return false;
    }

    ++$GLOBALS['NumPreQueriesArray']['mysqli_prepare'];
    return $result;
}

// Set Charset
function mysqli_prepare_func_set_charset($charset, $link = null)
{
    $connection = mysqli_prepare_func_conn($link);
    if (!$connection) {
        output_error("SQL Error: No valid MySQLi connection.", E_USER_ERROR);
        return false;
    }

    if (function_exists('mysqli_set_charset')) {
        if (mysqli_set_charset($connection, $charset) === false) {
            output_error("SQL Error: " . mysqli_prepare_func_error($connection), E_USER_ERROR);
            return false;
        }
        return true;
    }

    // SET NAMES/CHARACTER SET do not accept bound parameters, so escape inline.
    $escaped = mysqli_real_escape_string($connection, $charset);
    if (mysqli_prepare_func_query("SET CHARACTER SET '$escaped'", array(), $connection) === false) {
        return false;
    }
    if (mysqli_prepare_func_query("SET NAMES '$escaped'", array(), $connection) === false) {
        return false;
    }
    return true;
}

// Get next ID after an insert
// BUGFIX: the signature was ($link = null) but sql_get_next_id() always calls
// it with ($tablepre, $table, $link), so $link received the table prefix.
function mysqli_prepare_func_get_next_id($tablepre, $table, $link = null)
{
    $connection = mysqli_prepare_func_conn($link);
    return $connection ? mysqli_insert_id($connection) : false;
}

// Get number of rows for table (was missing entirely)
function mysqli_prepare_func_get_num_rows($tablepre, $table, $link = null)
{
    $connection = mysqli_prepare_func_conn($link);
    if (!$connection) {
        return false;
    }

    $sql = "SELECT COUNT(*) AS cnt FROM " . sql_quote_identifier($tablepre . $table, 'backtick');
    $result = mysqli_prepare_func_query($sql, array(), $connection);
    if ($result === false) {
        return false;
    }

    $row = mysqli_prepare_func_fetch_assoc($result);
    mysqli_prepare_func_free_result($result);

    return (is_array($row) && isset($row['cnt'])) ? (int)$row['cnt'] : 0;
}

function mysqli_prepare_func_count_rows($query, $link = null, $countname = "cnt")
{
    $result = mysqli_prepare_func_query($query, $link);
    if ($result === false) {
        return false;
    }

    $row = mysqli_prepare_func_fetch_assoc($result);
    $count = (is_array($row) && isset($row[$countname])) ? (int)$row[$countname] : 0;

    mysqli_prepare_func_free_result($result);
    return $count;
}

function mysqli_prepare_func_count_rows_alt($query, $link = null)
{
    $result = mysqli_prepare_func_query($query, $link);
    if ($result === false) {
        return false;
    }

    $row = mysqli_prepare_func_fetch_assoc($result);
    $count = is_array($row) ? reset($row) : 0;

    mysqli_prepare_func_free_result($result);
    return $count;
}
