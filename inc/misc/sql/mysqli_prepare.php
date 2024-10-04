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

// MySQLi Error handling functions
function mysqli_prepare_func_error($link = null) {
    global $SQLStat;
    $connection = ($link instanceof mysqli ? $link : $SQLStat);
    return $connection instanceof mysqli ? mysqli_error($connection) : false;
}

function mysqli_prepare_func_errno($link = null) {
    global $SQLStat;
    $connection = ($link instanceof mysqli ? $link : $SQLStat);
    return $connection instanceof mysqli ? mysqli_errno($connection) : false;
}

function mysqli_prepare_func_errorno($link = null) {
    global $SQLStat;
    $connection = ($link instanceof mysqli ? $link : $SQLStat);
    $result = $connection instanceof mysqli ? mysqli_prepare_func_error($connection) : false;
    $resultno = $connection instanceof mysqli ? mysqli_prepare_func_errno($connection) : false;

    return ($result == "" && $resultno === 0) ? "" : "$resultno: $result";
}

// Execute a query using prepared statements
if (!isset($NumQueriesArray['mysqli_prepare'])) {
    $NumQueriesArray['mysqli_prepare'] = 0;
}

function mysqli_prepare_func_query($query, $params = [], $link = null) {
    global $NumQueriesArray, $SQLStat;

    $connection = ($link instanceof mysqli ? $link : $SQLStat);

    // Check if query is valid before preparing
    if (empty($query)) {
        output_error("SQL Error: Query is empty", E_USER_ERROR);
        return false;
    }

    // If query is an array, extract query string and parameters
    if (is_array($query)) {
        list($query_string, $params) = $query;
    } else {
        $query_string = $query;
    }

    // Prepare the statement
    $stmt = $connection instanceof mysqli ? mysqli_prepare($connection, $query_string) : false;

    if (!$stmt) {
        output_error("SQL Error (Prepare): " . mysqli_prepare_func_error($connection), E_USER_ERROR);
        return false;
    }

    ++$NumQueriesArray['mysqli_prepare'];
    return $stmt;
}

// Fetch number of rows for SELECT queries
function mysqli_prepare_func_num_rows($stmt) {
    mysqli_stmt_store_result($stmt);  // Ensure results are stored for row counting
    $num = mysqli_stmt_num_rows($stmt);

    if ($num === false) {
        output_error("SQL Error: " . mysqli_prepare_func_error(), E_USER_ERROR);
        return false;
    }

    return $num;
}

// Connect to MySQLi database
function mysqli_prepare_func_connect_db($server, $username, $password, $database = null, $new_link = false) {
    $myport = "3306";
    $hostex = explode(":", $server);
    
    if (isset($hostex[1]) && !is_numeric($hostex[1])) {
        $hostex[1] = $myport;
    }
    
    if (isset($hostex[1])) {
        $server = $hostex[0];
        $myport = $hostex[1];
    }

    $link = $database === null ? mysqli_connect($server, $username, $password, null, $myport) : mysqli_connect($server, $username, $password, $database, $myport);

    if ($link === false) {
        output_error("MySQLi Error " . mysqli_connect_errno() . ": " . mysqli_connect_error(), E_USER_ERROR);
        return false;
    }

    // Set MySQL session settings
    $result = mysqli_prepare_func_query("SET SESSION SQL_MODE='ANSI,ANSI_QUOTES,TRADITIONAL,STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION,NO_AUTO_VALUE_ON_ZERO';", [], $link);

    if ($result === false) {
        output_error("SQL Error: " . mysqli_prepare_func_error(), E_USER_ERROR);
        return false;
    }

    return $link;
}

function mysqli_prepare_func_disconnect_db($link = null) {
    return mysqli_close($link);
}

// Query results fetching
function mysqli_prepare_func_result($stmt, $row, $field = 0) {
    // Fetch all rows into an array
    mysqli_stmt_store_result($stmt);
    $meta = mysqli_stmt_result_metadata($stmt);
    $fields = mysqli_fetch_fields($meta);
    $result = [];

    $bindArray = [];
    foreach ($fields as $field) {
        $bindArray[] = &$result[$field->name];
    }

    call_user_func_array('mysqli_stmt_bind_result', array_merge([$stmt], $bindArray));

    // Fetch row
    for ($i = 0; $i <= $row; $i++) {
        mysqli_stmt_fetch($stmt);
    }

    return $result[$fields[$field]->name] ?? null;
}

// Free results
function mysqli_prepare_func_free_result($stmt) {
    mysqli_stmt_free_result($stmt);
    return true;
}

// Fetch results as associative array
function mysqli_prepare_func_fetch_assoc($stmt) {
    mysqli_stmt_store_result($stmt);
    $meta = mysqli_stmt_result_metadata($stmt);
    $fields = mysqli_fetch_fields($meta);
    $result = [];

    $bindArray = [];
    foreach ($fields as $field) {
        $bindArray[] = &$result[$field->name];
    }

    call_user_func_array('mysqli_stmt_bind_result', array_merge([$stmt], $bindArray));
    mysqli_stmt_fetch($stmt);

    // After fetching, free the result
    mysqli_stmt_free_result($stmt); // Freeing result here to prevent errors

    return $result;
}

// Fetch row results as a numeric array
function mysqli_prepare_func_fetch_row($stmt) {
    mysqli_stmt_store_result($stmt);
    $meta = mysqli_stmt_result_metadata($stmt);
    $fields = mysqli_fetch_fields($meta);
    $result = [];

    $bindArray = [];
    foreach ($fields as $field) {
        $bindArray[] = &$result[$field->name];
    }

    call_user_func_array('mysqli_stmt_bind_result', array_merge([$stmt], $bindArray));
    mysqli_stmt_fetch($stmt);

    return array_values($result);
}

// Escape string
function mysqli_prepare_func_escape_string($string, $link = null) {
    global $SQLStat;
    if (!isset($string)) return null;
    $connection = ($link instanceof mysqli ? $link : $SQLStat);
    return $connection instanceof mysqli ? mysqli_real_escape_string($connection, $string) : false;
}

// SafeSQL Lite with prepared statements and placeholders
function mysqli_prepare_func_pre_query($query_string, $query_vars) {
    if ($query_vars === null || !is_array($query_vars)) {
        $query_vars = [];
    }

    // SQLite only supports `?` or named placeholders like `:param`
    // Replace complex placeholders with `?`
    $query_string = str_replace(["'%s'", '%d', '%i', '%f'], ['?', '?', '?', '?'], $query_string);

    // Filter out null values in the query_vars array
    $query_vars = array_filter($query_vars, function ($value) {
        return $value !== null;
    });

    // Count the number of `?` placeholders
    $placeholder_count = substr_count($query_string, '?');
    $params_count = count($query_vars);

    // Check for mismatch between placeholders and parameters
    if ($placeholder_count !== $params_count) {
        output_error("SQL Placeholder Error: Mismatch between placeholders ($placeholder_count) and parameters ($params_count).", E_USER_ERROR);
        return false;
    }

    // Return the query string and the array of variables
    return [$query_string, $query_vars];
}

// Set Charset using Prepared Statements
function mysqli_prepare_func_set_charset($charset, $link = null) {
    global $SQLStat;

    // Determine which connection to use, link or global SQLStat
    $connection = ($link instanceof mysqli ? $link : $SQLStat);

    // Fallback if mysqli_set_charset function does not exist
    if (function_exists('mysqli_set_charset') === false) {
        // If mysqli_set_charset does not exist, fall back to manual SQL queries
        $result = $connection instanceof mysqli ? mysqli_prepare_func_query("SET CHARACTER SET '%s'", [$charset], $connection) : false;

        if ($result === false) {
            output_error("SQL Error: " . mysqli_prepare_func_error($connection), E_USER_ERROR);
            return false;
        }

        $result = $connection instanceof mysqli ? mysqli_prepare_func_query("SET NAMES '%s'", [$charset], $connection) : false;

        if ($result === false) {
            output_error("SQL Error: " . mysqli_prepare_func_error($connection), E_USER_ERROR);
            return false;
        }

        return true;
    }

    // Use mysqli_set_charset if the function exists
    $result = $connection instanceof mysqli ? mysqli_set_charset($connection, $charset) : false;

    if ($result === false) {
        output_error("SQL Error: " . mysqli_prepare_func_error($connection), E_USER_ERROR);
        return false;
    }

    return true;
}

// Get next ID after an insert
function mysqli_prepare_func_get_next_id($link = null) {
    return mysqli_insert_id($link);
}

// Fetch Number of Rows using COUNT in a single query (uses mysqli_prepare_func_fetch_assoc)
function mysqli_prepare_func_count_rows($query, $link = null, $countname = "cnt") {
    $result = mysqli_prepare_func_query($query, [], $link);  // Pass empty array for params
    $row = mysqli_prepare_func_fetch_assoc($result);

    if ($row === false) {
        return false;  // Handle case if no row is returned
    }

    // Use the dynamic column name provided by $countname
    $count = isset($row[$countname]) ? $row[$countname] : 0;

    @mysqli_prepare_func_free_result($result);
    return $count;
}

// Alternative version using mysqli_prepare_func_fetch_assoc
function mysqli_prepare_func_count_rows_alt($query, $link = null) {
    $result = mysqli_prepare_func_query($query, [], $link);  // Pass empty array for params
    $row = mysqli_prepare_func_fetch_assoc($result);
    
    if ($row === false) {
        return false;  // Handle case if no row is returned
    }
    
    // Return first column (assuming single column result like COUNT or similar)
    $count = reset($row);

    @mysqli_prepare_func_free_result($result);
    return $count;
}
?>
