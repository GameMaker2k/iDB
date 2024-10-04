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

    $FileInfo: sqlite3.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/

$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "sqlite3.php" || $File3Name == "/sqlite3.php") {
    @header('Location: index.php');
    exit();
}

// SQLite Functions
function sqlite3_func_error($link = null) {
    global $SQLStat;
    $connection = ($link instanceof SQLite3 ? $link : ($SQLStat instanceof SQLite3 ? $SQLStat : null));
    return $connection ? $connection->lastErrorMsg() : "No valid SQLite3 connection.";
}

function sqlite3_func_errno($link = null) {
    global $SQLStat;
    $connection = ($link instanceof SQLite3 ? $link : ($SQLStat instanceof SQLite3 ? $SQLStat : null));
    return $connection ? $connection->lastErrorCode() : "No valid SQLite3 connection.";
}

function sqlite3_func_errorno($link = null) {
    global $SQLStat;
    $connection = ($link instanceof SQLite3 ? $link : ($SQLStat instanceof SQLite3 ? $SQLStat : null));
    return $connection ? $connection->lastErrorCode() . ": " . $connection->lastErrorMsg() : "No valid SQLite3 connection.";
}

// Execute a query
if (!isset($NumQueriesArray['sqlite3'])) {
    $NumQueriesArray['sqlite3'] = 0;
}

function sqlite3_func_query($query, $link = null) {
    global $NumQueriesArray, $SQLStat;

    // Ensure that the connection is a valid SQLite3 object
    $connection = ($link instanceof SQLite3 ? $link : ($SQLStat instanceof SQLite3 ? $SQLStat : null));

    // Check if connection is null
    if ($connection === null) {
        output_error("SQL Error: Invalid SQLite3 connection.", E_USER_ERROR);
        return false;
    }

    // Execute the query
    $result = $connection->query($query);

    if ($result === false) {
        output_error("SQL Error: " . sqlite3_func_error($connection), E_USER_ERROR);
        return false;
    }

    ++$NumQueriesArray['sqlite3'];
    return $result;
}

// Fetch Number of Rows
function sqlite3_func_num_rows($result) {
    $num = 0;
    if (!$result) {
        output_error("SQL Error: Invalid result set.", E_USER_ERROR);
        return false;
    }

    // Reset result pointer and count rows
    $result->reset();
    while ($result->fetchArray()) {
        $num++;
    }
    $result->reset();

    return $num;
}

// Connect to SQLite database
function sqlite3_func_connect_db($server, $username, $password, $database = null, $new_link = false) {
    if ($database === null) {
        return true;
    }

    $link = new SQLite3($database, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
    
    if (!$link) {
        output_error("Not connected: " . $link->lastErrorMsg(), E_USER_ERROR);
        return false;
    }

    return $link;
}

function sqlite3_func_disconnect_db($link = null) {
    global $SQLStat;
    $connection = ($link instanceof SQLite3 ? $link : ($SQLStat instanceof SQLite3 ? $SQLStat : null));

    return $connection ? $connection->close() : false;
}

// Query Results
function sqlite3_func_result($result, $row, $field = 0) {
    if (!$result) {
        output_error("SQL Error: Invalid result set.", E_USER_ERROR);
        return null;
    }

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
function sqlite3_func_free_result($result) {
    return true;
}

// Fetch Results to Array
function sqlite3_func_fetch_array($result, $result_type = SQLITE3_BOTH) {
    return $result ? $result->fetchArray($result_type) : false;
}

function sqlite3_func_fetch_assoc($result) {
    return $result ? $result->fetchArray(SQLITE3_ASSOC) : false;
}

function sqlite3_func_fetch_row($result) {
    return $result ? $result->fetchArray(SQLITE3_NUM) : false;
}

// Get Server Info
function sqlite3_func_server_info($link = null) {
    return SQLite3::version()['versionString'];
}

// Get Client Info
function sqlite3_func_client_info($link = null) {
    return SQLite3::version()['versionString'];
}

function sqlite3_func_escape_string($string, $link = null) {
	if (!isset($string)) return null;
    return SQLite3::escapeString($string);
}

// SafeSQL Lite Source Code by Cool Dude 2k
// Make SQL Query's safe
// SafeSQL Lite with additional SafeSQL features
function sqlite3_func_pre_query($query_string, $query_vars) {
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
    $query_vars = array_map("sqlite3_func_escape_string", $query_vars);

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
function sqlite3_func_set_charset($charset, $link = null) {
    return true; // SQLite3 doesn't require charset setting like MySQL.
}

// Get next id for stuff
function sqlite3_func_get_next_id($tablepre, $table, $link = null) {
    global $SQLStat;
    $connection = ($link instanceof SQLite3 ? $link : ($SQLStat instanceof SQLite3 ? $SQLStat : null));

    return $connection ? $connection->lastInsertRowID() : false;
}

// Get number of rows for table
function sqlite3_func_get_num_rows($tablepre, $table, $link = null) {
    $getnextidq = sqlite3_func_pre_query("SELECT COUNT(*) AS Rows FROM '" . $tablepre . $table . "'", array());
    $getnextidr = sqlite3_func_query($getnextidq, $link);
    $getnextid = sqlite3_func_fetch_assoc($getnextidr);

    return $getnextid ? $getnextid['Rows'] : 0;
}

function sqlite3_func_count_rows($query, $link = null, $countname = "cnt") {
    $result = sqlite3_func_query($query, $link);  // Use prepared query
    $row = sqlite3_func_fetch_assoc($result);

    if ($row === false) {
        return false;  // Handle case if no row is returned
    }

    // Use the dynamic column name provided by $countname
    $count = isset($row[$countname]) ? $row[$countname] : 0;

    @sqlite3_func_free_result($result);
    return $count;
}

function sqlite3_func_count_rows_alt($query, $link = null) {
    $result = sqlite3_func_query($query, $link);  // Use prepared query
    $row = sqlite3_func_fetch_assoc($result);
    
    if ($row === false) {
        return false;  // Handle case if no row is returned
    }
    
    // Return first column (assuming single column result like COUNT or similar)
    $count = reset($row);

    @sqlite3_func_free_result($result);
    return $count;
}
?>
