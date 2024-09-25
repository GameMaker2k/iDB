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

// SQLSRV Error handling functions
function sqlsrv_prepare_func_error($link = null) {
    return sqlsrv_errors(SQLSRV_ERR_ERRORS);
}

function sqlsrv_prepare_func_errorno($link = null) {
    return sqlsrv_errors(SQLSRV_ERR_ERRORS); // SQLSRV does not use separate error numbers.
}

function sqlsrv_prepare_func_errorno_full($link = null) {
    $error = sqlsrv_prepare_func_error($link);
    return $error ? json_encode($error) : "";
}

// Execute a query using prepared statements
if (!isset($NumQueriesArray['sqlsrv_prepare'])) {
    $NumQueriesArray['sqlsrv_prepare'] = 0;
}

function sqlsrv_prepare_func_query($query, $params = [], $link = null) {
    global $NumQueriesArray;

    // Prepare the statement
    $stmt = sqlsrv_prepare($link, $query, $params);
    if (!$stmt) {
        output_error("SQL Error (Prepare): " . print_r(sqlsrv_prepare_func_error($link), true), E_USER_ERROR);
        return false;
    }

    // Execute the prepared statement
    if (!sqlsrv_execute($stmt)) {
        output_error("SQL Error (Execution): " . print_r(sqlsrv_prepare_func_error($link), true), E_USER_ERROR);
        return false;
    }

    ++$NumQueriesArray['sqlsrv_prepare'];

    return $stmt;  // Return the prepared statement object
}

// Fetch number of rows for SELECT queries
function sqlsrv_prepare_func_num_rows($stmt) {
    $num = sqlsrv_num_rows($stmt);

    if ($num === false) {
        output_error("SQL Error: " . print_r(sqlsrv_prepare_func_error(), true), E_USER_ERROR);
        return false;
    }

    return $num;
}

// Connect to SQL Server database
function sqlsrv_prepare_func_connect_db($server, $username, $password, $database = null) {
    $connectionInfo = [
        "UID" => $username,
        "PWD" => $password,
        "Database" => $database,
        "CharacterSet" => "UTF-8"
    ];

    $link = sqlsrv_connect($server, $connectionInfo);

    if ($link === false) {
        output_error("SQLSRV Error: " . print_r(sqlsrv_errors(), true), E_USER_ERROR);
        return false;
    }

    return $link;
}

function sqlsrv_prepare_func_disconnect_db($link = null) {
    return sqlsrv_close($link);
}

// Query results fetching
function sqlsrv_prepare_func_result($stmt, $row, $field = 0) {
    $data = [];
    while ($result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
        $data[] = $result;
    }

    return $data[$row][$field] ?? null;
}

// Free results
function sqlsrv_prepare_func_free_result($stmt) {
    return sqlsrv_free_stmt($stmt);
}

// Fetch results as associative array
function sqlsrv_prepare_func_fetch_assoc($stmt) {
    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
}

// Fetch row results as a numeric array
function sqlsrv_prepare_func_fetch_row($stmt) {
    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);
}

// Escape string - SQLSRV uses parameterized queries, so this is not needed.
function sqlsrv_prepare_func_escape_string($string, $link = null) {
    return $string; // SQLSRV does not have a string escape function; use parameterized queries instead.
}

// SafeSQL Lite with prepared statements and placeholders
function sqlsrv_prepare_func_pre_query($query_string, $query_vars) {
    if ($query_vars === null || !is_array($query_vars)) {
        $query_vars = [];
    }

    // No need to convert placeholders in SQLSRV, as the use of '?' is standard in parameterized queries
    return [$query_string, $query_vars];
}

// Set Charset
function sqlsrv_prepare_func_set_charset($charset, $link = null) {
    // Charset is set in the connection string in SQLSRV; this function is a placeholder.
    return true;
}

// Get next ID after an insert
function sqlsrv_prepare_func_get_next_id($link = null) {
    $stmt = sqlsrv_query($link, "SELECT SCOPE_IDENTITY()");
    if ($stmt) {
        $result = sqlsrv_fetch_array($stmt);
        return $result[0] ?? null;
    }
    return null;
}

// Fetch Number of Rows using COUNT in a single query
function sqlsrv_prepare_func_count_rows($query, $link = null) {
    $result = sqlsrv_prepare_func_query($query, $link);
    $row = sqlsrv_prepare_func_result($result, 0, 'cnt');
    @sqlsrv_prepare_func_free_result($result);
    return $row;
}

function sqlsrv_prepare_func_count_rows_alt($query, $link = null) {
    $result = sqlsrv_prepare_func_query($query, $link);
    $row = sqlsrv_prepare_func_result($result, 0);
    @sqlsrv_prepare_func_free_result($result);
    return $row;
}
?>
