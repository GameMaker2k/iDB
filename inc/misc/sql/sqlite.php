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

    $FileInfo: sqlite.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/

$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "sqlite.php" || $File3Name == "/sqlite.php") {
    @header('Location: index.php');
    exit();
}

// SQLite Functions
function sqlite_func_error($link = null) {
    global $SQLStat;
    return isset($link) 
        ? sqlite_error_string(sqlite_last_error($link)) 
        : sqlite_error_string(sqlite_last_error($SQLStat));
}

function sqlite_func_errno($link = null) {
    global $SQLStat;
    return isset($link) ? sqlite_last_error($link) : sqlite_last_error($SQLStat);
}

function sqlite_func_errorno($link = null) {
    global $SQLStat;
    return isset($link)
        ? sqlite_last_error($link) . ": " . sqlite_error_string(sqlite_last_error($link))
        : sqlite_last_error($SQLStat) . ": " . sqlite_error_string(sqlite_last_error($SQLStat));
}

// Execute a query
if (!isset($NumQueriesArray['sqlite'])) {
    $NumQueriesArray['sqlite'] = 0;
}

function sqlite_func_query($query, $link = null) {
    global $NumQueriesArray, $SQLStat;
    $result = isset($link) ? sqlite_query($link, $query) : sqlite_query($SQLStat, $query);

    if ($result === false) {
        output_error("SQL Error: " . sqlite_func_error(), E_USER_ERROR);
        return false;
    }

    ++$NumQueriesArray['sqlite'];
    return $result;
}

// Fetch Number of Rows
function sqlite_func_num_rows($result) {
    $num = sqlite_num_rows($result);

    if ($num === false) {
        output_error("SQL Error: " . sqlite_func_error(), E_USER_ERROR);
        return false;
    }

    return $num;
}

// Connect to SQLite database
function sqlite_func_connect_db($server, $username, $password, $database = null, $new_link = false) {
    if ($database === null) {
        return true;
    }

    $link = sqlite_open($database, 0666, $sqliteerror);
    
    if ($link === false) {
        output_error("Not connected: " . $sqliteerror, E_USER_ERROR);
        return false;
    }

    return $link;
}

function sqlite_func_disconnect_db($link = null) {
    return sqlite_close($link);
}

// Query Results
function sqlite_func_result($result, $row, $field = 0) {
    $check = sqlite_seek($result, $row);

    if ($check === false) {
        output_error("SQL Error: " . sqlite_func_error(), E_USER_ERROR);
        return false;
    }

    $trow = sqlite_fetch_array($result);
    return isset($trow[$field]) ? $trow[$field] : null;
}

// Free Results
function sqlite_func_free_result($result) {
    return true;
}

// Fetch Results to Array
function sqlite_func_fetch_array($result, $result_type = SQLITE_BOTH) {
    return sqlite_fetch_array($result, $result_type);
}

// Fetch Results to Associative Array
function sqlite_func_fetch_assoc($result) {
    return sqlite_fetch_array($result, SQLITE_ASSOC);
}

// Fetch Row Results
function sqlite_func_fetch_row($result) {
    return sqlite_fetch_array($result, SQLITE_NUM);
}

// Get Server Info
function sqlite_func_server_info($link = null) {
    return sqlite_libversion();
}

// Get Client Info
function sqlite_func_client_info($link = null) {
    return sqlite_libversion();
}

function sqlite_func_escape_string($string, $link = null) {
	if (!isset($string)) return null;
    return sqlite_escape_string($string);
}

// SafeSQL Lite Source Code by Cool Dude 2k
// Make SQL Query's safe
// SafeSQL Lite with additional SafeSQL features
function sqlite_func_pre_query($query_string, $query_vars) {
    // If no query variables are provided, initialize with a single element array containing null
    if ($query_vars == null) {
        $query_vars = array(null);
    }

    // Add support for multiple variable types like %c (comma-separated integers), %l (comma-separated strings), etc.
    $query_array = array(
        array("%i", "%I", "%F", "%S", "%c", "%l", "%q", "%n"),
        array("%d", "%d", "%f", "%s", "%s", "%s", "%s", "%s")
    );
    
    // Replace custom placeholders with appropriate sprintf format specifiers
    $query_string = str_replace($query_array[0], $query_array[1], $query_string);

    // Handle magic quotes if enabled (for backward compatibility with older PHP versions)
    if (get_magic_quotes_gpc()) {
        $query_vars = array_map("stripslashes", $query_vars);
    }

    // Escape each variable using sqlite_func_escape_string
    $query_vars = array_map("sqlite_func_escape_string", $query_vars);

    // Prepare the variables for sprintf
    $query_val = $query_vars;
    $query_num = count($query_val);
    $query_i = 0;

    // Replace variables in reverse order to avoid string position issues
    while ($query_i < $query_num) {
        $query_val[$query_i + 1] = _convert_var($query_vars[$query_i], $query_string);
        ++$query_i;
    }

    // Set the first element of the array to be the query string
    $query_val[0] = $query_string;

    // Use sprintf to inject the variables into the query string safely
    return call_user_func_array("sprintf", $query_val);
}

// Set Charset
function sqlite_func_set_charset($charset, $link = null) {
    return true;
}

// Get next id for stuff
function sqlite_func_get_next_id($tablepre, $table, $link = null) {
    return sqlite_last_insert_rowid($link);
}

// Get number of rows for table
function sqlite_func_get_num_rows($tablepre, $table, $link = null) {
    $getnextidq = sqlite_func_pre_query("SHOW TABLE STATUS LIKE '" . $tablepre . $table . "'", array());
    $getnextidr = isset($link) ? sqlite_func_query($getnextidq, $link) : sqlite_func_query($getnextidq);
    $getnextid = sqlite_func_fetch_assoc($getnextidr);
    return $getnextid['Rows'];
    @sqlite_func_result($getnextidr);
}

// Fetch Number of Rows using COUNT in a single query
function sqlite_func_count_rows($query, $link = null) {
    $result = sqlite_func_query($query, $link);
    $row = sqlite_func_result($result, 0, 'cnt');
    @sqlite_func_free_result($result);
    return $row;
}

function sqlite_func_count_rows_alt($query, $link = null) {
    $result = sqlite_func_query($query, $link);
    $row = sqlite_func_result($result, 0);
    @sqlite_func_free_result($result);
    return $row;
}
?>
