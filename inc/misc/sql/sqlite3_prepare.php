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

    $FileInfo: sqlite3_prepare.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/

$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "sqlite3_prepare.php" || $File3Name == "/sqlite3_prepare.php") {
    @header('Location: index.php');
    exit();
}

// SQLite Functions
function sqlite3_prepare_func_error($link = null) {
    global $SQLStat;
    return isset($link) ? $link->lastErrorMsg() : $SQLStat->lastErrorMsg();
}

function sqlite3_prepare_func_errno($link = null) {
    global $SQLStat;
    return isset($link) ? $link->lastErrorCode() : $SQLStat->lastErrorCode();
}

function sqlite3_prepare_func_errorno($link = null) {
    global $SQLStat;
    return isset($link)
        ? $link->lastErrorCode() . ": " . $link->lastErrorMsg()
        : $SQLStat->lastErrorCode() . ": " . $SQLStat->lastErrorMsg();
}

// Execute a query
if (!isset($NumQueriesArray['sqlite3_prepare'])) {
    $NumQueriesArray['sqlite3_prepare'] = 0;
}

function sqlite3_prepare_func_query($query, $params = [], $link = null) {
    global $NumQueriesArray, $SQLStat;
    $db = isset($link) ? $link : $SQLStat;

    // Check if $query is an array returned from `sqlite3_prepare_func_pre_query()`
    if (is_array($query)) {
        // Extract query string and parameters
        list($query_string, $params) = $query;
    } else {
        // If query is already a string, use it as is
        $query_string = $query;
    }

    // Prepare the query
    $stmt = $db->prepare($query_string);
    if (!$stmt) {
        output_error("SQL Error (Prepare): " . sqlite3_prepare_func_error($db), E_USER_ERROR);
        return false;
    }

    // Bind parameters dynamically
    foreach ($params as $key => $value) {
        $paramKey = is_int($key) ? $key + 1 : ':' . $key; // SQLite uses 1-based indexing for positional placeholders
        if (is_int($value)) {
            $stmt->bindValue($paramKey, $value, SQLITE3_INTEGER);
        } elseif (is_float($value)) {
            $stmt->bindValue($paramKey, $value, SQLITE3_FLOAT);
        } elseif (is_null($value)) {
            $stmt->bindValue($paramKey, $value, SQLITE3_NULL);
        } else {
            $stmt->bindValue($paramKey, $value, SQLITE3_TEXT);
        }
    }

    // Execute the query
    $result = $stmt->execute();
    if ($result === false) {
        output_error("SQL Error (Execution): " . sqlite3_prepare_func_error($db), E_USER_ERROR);
        return false;
    }

    ++$NumQueriesArray['sqlite3_prepare'];
    return $result;
}

// Fetch Number of Rows
function sqlite3_prepare_func_num_rows($result) {
    $num = 0;
    $result->reset();
    while ($result->fetchArray()) {
        $num++;
    }
    $result->reset();

    if ($num === false) {
        output_error("SQL Error: " . sqlite3_prepare_func_error(), E_USER_ERROR);
        return false;
    }

    return $num;
}

// Connect to SQLite database
function sqlite3_prepare_func_connect_db($server, $username, $password, $database = null, $new_link = false) {
    if ($database === null) {
        return true;
    }

    $link = new SQLite3($database, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
    
    if ($link === false) {
        output_error("Not connected: " . sqlite3_prepare_func_error($link), E_USER_ERROR);
        return false;
    }

    return $link;
}

function sqlite3_prepare_func_disconnect_db($link = null) {
    return isset($link) ? $link->close() : $SQLStat->close();
}

// Query Results
function sqlite3_prepare_func_result($result, $row, $field = 0) {
    $num = 0;
    $result->reset();

    while ($num < $row) {
        $result->fetchArray();
        $num++;
    }

    $trow = $result->fetchArray();
    return isset($trow[$field]) ? $trow[$field] : null;
}

// Free Results
function sqlite3_prepare_func_free_result($result) {
    return true;
}

// Fetch Results to Array
function sqlite3_prepare_func_fetch_array($result, $result_type = SQLITE3_BOTH) {
    return $result->fetchArray($result_type);
}

// Fetch Results to Associative Array
function sqlite3_prepare_func_fetch_assoc($result) {
    return $result->fetchArray(SQLITE3_ASSOC);
}

// Fetch Row Results
function sqlite3_prepare_func_fetch_row($result) {
    return $result->fetchArray(SQLITE3_NUM);
}

// Get Server Info
function sqlite3_prepare_func_server_info($link = null) {
    return SQLite3::version()['versionString'];
}

// Get Client Info
function sqlite3_prepare_func_client_info($link = null) {
    return SQLite3::version()['versionString'];
}

function sqlite3_prepare_func_escape_string($string, $link = null) {
	if (!isset($string)) return null;
    return SQLite3::escapeString($string);
}

// SafeSQL Lite Source Code by Cool Dude 2k
// Make SQL Query's safe
function sqlite3_prepare_func_pre_query($query_string, $query_vars = []) {
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

// Set Charset (dummy for SQLite3)
function sqlite3_prepare_func_set_charset($charset, $link = null) {
    return true;
}

// Get next id after insert
function sqlite3_prepare_func_get_next_id($tablepre, $table, $link = null) {
    global $SQLStat;
    $db = isset($link) ? $link : $SQLStat;
    return $db->lastInsertRowID();
}

// Fetch Number of Rows using COUNT in a single query
function sqlite3_prepare_func_count_rows($query, $link = null) {
    $result = sqlite3_prepare_func_query($query, $link);
    $row = sqlite3_prepare_func_result($result, 0, 'cnt');
    @sqlite3_prepare_func_free_result($result);
    return $row;
}

function sqlite3_prepare_func_count_rows_alt($query, $link = null) {
    $result = sqlite3_prepare_func_query($query, $link);
    $row = sqlite3_prepare_func_result($result, 0);
    @sqlite3_prepare_func_free_result($result);
    return $row;
}
?>