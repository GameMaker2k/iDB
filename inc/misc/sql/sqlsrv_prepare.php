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

    $FileInfo: sqlsrv_prepare.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/

$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "sqlsrv_prepare.php" || $File3Name == "/sqlsrv_prepare.php") {
    @header('Location: index.php');
    exit();
}

if (!isset($GLOBALS['NumPreQueriesArray']['sqlsrv_prepare'])) {
    $GLOBALS['NumPreQueriesArray']['sqlsrv_prepare'] = 0;
}
if (!isset($GLOBALS['NumQueriesArray']['sqlsrv_prepare'])) {
    $GLOBALS['NumQueriesArray']['sqlsrv_prepare'] = 0;
}
// Keeps the bound parameter variables alive: sqlsrv_prepare() binds by
// reference, so the array must not be garbage collected before execute().
if (!isset($GLOBALS['SqlSrvParamKeep']) || !is_array($GLOBALS['SqlSrvParamKeep'])) {
    $GLOBALS['SqlSrvParamKeep'] = array();
}

function sqlsrv_prepare_func_conn($link = null)
{
    if ($link !== null && $link !== false) {
        return $link;
    }
    if (isset($GLOBALS['SQLStat']) && $GLOBALS['SQLStat']) {
        return $GLOBALS['SQLStat'];
    }
    return null;
}

// Flatten sqlsrv_errors() into a readable string so this driver returns the
// same kind of value as every other one.
function sqlsrv_prepare_func_format_errors($errors)
{
    if (!is_array($errors) || count($errors) === 0) {
        return "";
    }

    $parts = array();
    foreach ($errors as $error) {
        $code = isset($error['code']) ? $error['code'] : '0';
        $message = isset($error['message']) ? $error['message'] : '';
        $parts[] = $code . ": " . $message;
    }
    return implode(" | ", $parts);
}

// SQLSRV Error handling functions
function sqlsrv_prepare_func_error($link = null)
{
    return sqlsrv_prepare_func_format_errors(sqlsrv_errors(SQLSRV_ERR_ERRORS));
}

// Was missing entirely, so sql_errno() threw for this driver.
function sqlsrv_prepare_func_errno($link = null)
{
    $errors = sqlsrv_errors(SQLSRV_ERR_ERRORS);
    if (!is_array($errors) || count($errors) === 0) {
        return 0;
    }
    return isset($errors[0]['code']) ? $errors[0]['code'] : 0;
}

function sqlsrv_prepare_func_errorno($link = null)
{
    return sqlsrv_prepare_func_error($link);
}

function sqlsrv_prepare_func_errorno_full($link = null)
{
    $errors = sqlsrv_errors(SQLSRV_ERR_ERRORS);
    return $errors ? json_encode($errors) : "";
}

// Execute a query using prepared statements.
//
// BUGFIX: the old version called sqlsrv_prepare() twice (leaking the first
// statement) and built the typed parameter array *after* the first prepare.
// It also never requested a scrollable cursor, so sqlsrv_num_rows() and
// row-addressed result() could not work.
function sqlsrv_prepare_func_query($query, $params_or_link = null, $maybe_link = null)
{
    list($sql, $params, $link) = sql_resolve_query_args($query, $params_or_link, $maybe_link);

    $connection = sqlsrv_prepare_func_conn($link);
    if (!$connection) {
        output_error("SQL Error: Invalid SQLSRV connection.", E_USER_ERROR);
        return false;
    }

    if (!is_string($sql) || trim($sql) === '') {
        output_error("SQL Error: Query is empty.", E_USER_ERROR);
        return false;
    }

    // Build the typed parameter array first, keeping references alive.
    $bind = array();
    $values = array();
    foreach ($params as $key => $value) {
        $values[$key] = $value;
        if (is_int($value)) {
            $bind[] = array(&$values[$key], SQLSRV_PARAM_IN, null, SQLSRV_SQLTYPE_INT);
        } elseif (is_float($value)) {
            $bind[] = array(&$values[$key], SQLSRV_PARAM_IN, null, SQLSRV_SQLTYPE_FLOAT);
        } elseif (is_bool($value)) {
            $values[$key] = $value ? 1 : 0;
            $bind[] = array(&$values[$key], SQLSRV_PARAM_IN, null, SQLSRV_SQLTYPE_INT);
        } elseif (is_null($value)) {
            $bind[] = array(&$values[$key], SQLSRV_PARAM_IN, null, SQLSRV_SQLTYPE_VARCHAR('max'));
        } else {
            $values[$key] = (string)$value;
            $bind[] = array(&$values[$key], SQLSRV_PARAM_IN, null, SQLSRV_SQLTYPE_VARCHAR('max'));
        }
    }

    // A static cursor lets sqlsrv_num_rows() and absolute row seeks work.
    $options = array();
    if (preg_match('/^\s*(\(|SELECT|WITH|SHOW|EXEC)/i', $sql)) {
        $options["Scrollable"] = SQLSRV_CURSOR_STATIC;
    }

    $stmt = sqlsrv_prepare($connection, $sql, $bind, $options);
    if (!$stmt) {
        output_error("SQL Error (Prepare): " . sqlsrv_prepare_func_error($connection), E_USER_ERROR);
        return false;
    }

    if (!sqlsrv_execute($stmt)) {
        output_error("SQL Error (Execution): " . sqlsrv_prepare_func_error($connection), E_USER_ERROR);
        sqlsrv_free_stmt($stmt);
        return false;
    }

    // Hold the bound values until the statement is freed.
    $GLOBALS['SqlSrvParamKeep'][(string)(int)$stmt] = $values;

    ++$GLOBALS['NumQueriesArray']['sqlsrv_prepare'];

    return $stmt;
}

// Fetch number of rows for SELECT queries
function sqlsrv_prepare_func_num_rows($stmt)
{
    if (!$stmt) {
        return false;
    }

    $num = @sqlsrv_num_rows($stmt);

    if ($num === false) {
        output_error("SQL Error: " . sqlsrv_prepare_func_error(), E_USER_ERROR);
        return false;
    }

    return $num;
}

// Connect to SQL Server database using sqlsrv
function sqlsrv_prepare_func_connect_db($server, $username = null, $password = null, $database = null, $new_link = false)
{
    $connectionInfo = array(
        "CharacterSet" => "UTF-8",
        "TrustServerCertificate" => true
    );

    if (!empty($username)) {
        $connectionInfo["UID"] = $username;
        $connectionInfo["PWD"] = $password;
    }

    if ($database !== null) {
        $connectionInfo["Database"] = $database;
    }

    if ($new_link) {
        $connectionInfo["ConnectionPooling"] = 0;
    }

    $link = sqlsrv_connect($server, $connectionInfo);

    if ($link === false) {
        output_error("SQLSRV Error: " . sqlsrv_prepare_func_format_errors(sqlsrv_errors()), E_USER_ERROR);
        return false;
    }

    return $link;
}

function sqlsrv_prepare_func_disconnect_db($link = null)
{
    $connection = sqlsrv_prepare_func_conn($link);
    return $connection ? sqlsrv_close($connection) : false;
}

// Query results fetching
// BUGFIX: the old version drained the whole statement into an array on every
// call, so the second call always returned null.
function sqlsrv_prepare_func_result($stmt, $row, $field = 0)
{
    if (!$stmt) {
        return null;
    }

    $data = @sqlsrv_fetch_array($stmt, SQLSRV_FETCH_BOTH, SQLSRV_SCROLL_ABSOLUTE, $row);

    if (!is_array($data)) {
        return null;
    }

    return isset($data[$field]) ? $data[$field] : null;
}

// Free results
function sqlsrv_prepare_func_free_result($stmt)
{
    if (!$stmt) {
        return true;
    }
    unset($GLOBALS['SqlSrvParamKeep'][(string)(int)$stmt]);
    return sqlsrv_free_stmt($stmt);
}

// Fetch results (fetch_array was missing entirely)
function sqlsrv_prepare_func_fetch_array($stmt, $result_type = SQLSRV_FETCH_BOTH)
{
    if ($result_type === null) {
        $result_type = SQLSRV_FETCH_BOTH;
    }
    return $stmt ? sqlsrv_fetch_array($stmt, $result_type) : false;
}

function sqlsrv_prepare_func_fetch_assoc($stmt)
{
    return $stmt ? sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) : false;
}

function sqlsrv_prepare_func_fetch_row($stmt)
{
    return $stmt ? sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC) : false;
}

// Get Server Info (was missing entirely)
function sqlsrv_prepare_func_server_info($link = null)
{
    $connection = sqlsrv_prepare_func_conn($link);
    if (!$connection) {
        return false;
    }
    $info = sqlsrv_server_info($connection);
    return isset($info['SQLServerVersion']) ? $info['SQLServerVersion'] : false;
}

// Get Client Info (was missing entirely)
function sqlsrv_prepare_func_client_info($link = null)
{
    $info = sqlsrv_client_info(sqlsrv_prepare_func_conn($link));
    return isset($info['DriverVer']) ? $info['DriverVer'] : false;
}

// Escape string - SQLSRV uses parameterized queries.
function sqlsrv_prepare_func_escape_string($string, $link = null)
{
    if ($string === null) {
        return null;
    }
    // There is no sqlsrv escape function; doubling quotes is the safe minimum
    // for the rare inline case. Prefer bound parameters.
    return str_replace("'", "''", (string)$string);
}

// Pre-process Query for SQLSRV
function sqlsrv_prepare_func_pre_query($query_string, $query_vars = array())
{
    $result = sql_prepared_pre_query($query_string, $query_vars, 'qmark');
    if ($result === false) {
        return false;
    }

    ++$GLOBALS['NumPreQueriesArray']['sqlsrv_prepare'];
    return $result;
}

// Set Charset (set in the connection string for SQLSRV)
function sqlsrv_prepare_func_set_charset($charset, $link = null)
{
    return true;
}

// Get next ID after an insert
// BUGFIX: the signature was ($link = null) but sql_get_next_id() always calls
// it with ($tablepre, $table, $link).
function sqlsrv_prepare_func_get_next_id($tablepre, $table, $link = null)
{
    $connection = sqlsrv_prepare_func_conn($link);
    if (!$connection) {
        return false;
    }

    $stmt = sqlsrv_query($connection, "SELECT SCOPE_IDENTITY() AS cnt");
    if (!$stmt) {
        return null;
    }

    $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);
    sqlsrv_free_stmt($stmt);

    return isset($result[0]) ? $result[0] : null;
}

// Get number of rows for table (was missing entirely)
function sqlsrv_prepare_func_get_num_rows($tablepre, $table, $link = null)
{
    $connection = sqlsrv_prepare_func_conn($link);
    if (!$connection) {
        return false;
    }

    $sql = "SELECT COUNT(*) AS cnt FROM " . sql_quote_identifier($tablepre . $table, 'bracket');
    $stmt = sqlsrv_prepare_func_query($sql, array(), $connection);
    if ($stmt === false) {
        return false;
    }

    $row = sqlsrv_prepare_func_fetch_assoc($stmt);
    sqlsrv_prepare_func_free_result($stmt);

    return (is_array($row) && isset($row['cnt'])) ? (int)$row['cnt'] : 0;
}

function sqlsrv_prepare_func_count_rows($query, $link = null, $countname = "cnt")
{
    $result = sqlsrv_prepare_func_query($query, $link);
    if ($result === false) {
        return false;
    }

    $row = sqlsrv_prepare_func_fetch_assoc($result);
    $count = (is_array($row) && isset($row[$countname])) ? $row[$countname] : 0;

    sqlsrv_prepare_func_free_result($result);
    return $count;
}

function sqlsrv_prepare_func_count_rows_alt($query, $link = null)
{
    $result = sqlsrv_prepare_func_query($query, $link);
    if ($result === false) {
        return false;
    }

    $row = sqlsrv_prepare_func_fetch_assoc($result);
    $count = is_array($row) ? reset($row) : 0;

    sqlsrv_prepare_func_free_result($result);
    return $count;
}
