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

// MySQLi Error handling functions
function mysqli_func_error($link = null) {
    global $SQLStat;
    $connection = ($link instanceof mysqli ? $link : $SQLStat);
    return $connection instanceof mysqli ? mysqli_error($connection) : false;
}

function mysqli_func_errno($link = null) {
    global $SQLStat;
    $connection = ($link instanceof mysqli ? $link : $SQLStat);
    return $connection instanceof mysqli ? mysqli_errno($connection) : false;
}

function mysqli_func_errorno($link = null) {
    global $SQLStat;

    $connection = ($link instanceof mysqli ? $link : $SQLStat);
    
    $result = $connection instanceof mysqli ? mysqli_func_error($connection) : mysqli_func_error();
    $resultno = $connection instanceof mysqli ? mysqli_func_errno($connection) : mysqli_func_errno();

    return ($result == "" && $resultno === 0) ? "" : "$resultno: $result";
}

// Execute a query
if (!isset($NumQueriesArray['mysqli'])) {
    $NumQueriesArray['mysqli'] = 0;
}

function mysqli_func_query($query, $link = null) {
    global $NumQueriesArray, $SQLStat;
    
    $result = ($link instanceof mysqli ? $link : $SQLStat) instanceof mysqli ? mysqli_query(($link instanceof mysqli ? $link : $SQLStat), $query) : false;

    if ($result === false) {
        output_error("SQL Error: " . mysqli_func_error(), E_USER_ERROR);
        return false;
    }

    if ($result !== false) {
        ++$NumQueriesArray['mysqli'];
        return $result;
    }
}

// Fetch Number of Rows
function mysqli_func_num_rows($result) {
    $num = mysqli_num_rows($result);
    
    if ($num === false) {
        output_error("SQL Error: " . mysqli_func_error(), E_USER_ERROR);
        return false;
    }
    
    return $num;
}

// Connect to MySQLi database
function mysqli_func_connect_db($server, $username, $password, $database = null, $new_link = false) {
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

    $result = mysqli_func_query("SET SESSION SQL_MODE='ANSI,ANSI_QUOTES,TRADITIONAL,STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION,NO_AUTO_VALUE_ON_ZERO';", $link);

    if ($result === false) {
        output_error("SQL Error: " . mysqli_func_error(), E_USER_ERROR);
        return false;
    }

    return $link;
}

function mysqli_func_disconnect_db($link = null) {
    return mysqli_close($link);
}

// Query Results
function mysqli_func_result($result, $row, $field = 0) {
    $check = mysqli_data_seek($result, $row);
    
    if ($check === false) {
        output_error("SQL Error: " . mysqli_func_error(), E_USER_ERROR);
        return false;
    }

    $trow = mysqli_fetch_array($result);
    return $trow[$field] ?? null;
}

// Free Results
function mysqli_func_free_result($result) {
    $fresult = mysqli_free_result($result);

    if ($fresult === false) {
        output_error("SQL Error: " . mysqli_func_error(), E_USER_ERROR);
        return false;
    }

    return true;
}

// Fetch Results to Array
function mysqli_func_fetch_array($result, $result_type = MYSQLI_BOTH) {
    return mysqli_fetch_array($result, $result_type);
}

// Fetch Results to Associative Array
function mysqli_func_fetch_assoc($result) {
    return mysqli_fetch_assoc($result);
}

// Fetch Row Results
function mysqli_func_fetch_row($result) {
    return mysqli_fetch_row($result);
}

// Get Server Info
function mysqli_func_server_info($link = null) {
    global $SQLStat;
    $connection = ($link instanceof mysqli ? $link : $SQLStat);
    return $connection instanceof mysqli ? mysqli_get_server_info($connection) : false;
}

// Get Client Info
function mysqli_func_client_info($link = null) {
    return mysqli_get_client_info();
}

// Escape String
function mysqli_func_escape_string($string, $link = null) {
    global $SQLStat;
    if (!isset($string)) return null;
    $connection = ($link instanceof mysqli ? $link : $SQLStat);
    return $connection instanceof mysqli ? mysqli_real_escape_string($connection, $string) : false;
}

// SafeSQL Lite Source Code by Cool Dude 2k
// Make SQL Queries safe
// SafeSQL Lite with additional SafeSQL features
function mysqli_func_pre_query($query_string, $query_vars) {
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

    // Escape each variable using mysqli_func_escape_string
    $query_vars = array_map("mysqli_func_escape_string", $query_vars);

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
function mysqli_func_set_charset($charset, $link = null) {
    global $SQLStat;

    // Determine which connection to use, link or global SQLStat
    $connection = ($link instanceof mysqli ? $link : $SQLStat);

    // Fallback if mysqli_set_charset function does not exist
    if (function_exists('mysqli_set_charset') === false) {
        $result = $connection instanceof mysqli ? mysqli_func_query("SET CHARACTER SET '$charset'", $connection) : mysqli_func_query("SET CHARACTER SET '$charset'");

        if ($result === false) {
            output_error("SQL Error: " . mysqli_func_error($connection), E_USER_ERROR);
            return false;
        }

        $result = $connection instanceof mysqli ? mysqli_func_query("SET NAMES '$charset'", $connection) : mysqli_func_query("SET NAMES '$charset'");

        if ($result === false) {
            output_error("SQL Error: " . mysqli_func_error($connection), E_USER_ERROR);
            return false;
        }

        return true;
    }

    // Use mysqli_set_charset if available
    $result = $connection instanceof mysqli ? mysqli_set_charset($connection, $charset) : false;

    if ($result === false) {
        output_error("SQL Error: " . mysqli_func_error($connection), E_USER_ERROR);
        return false;
    }

    return true;
}

// Get next id for stuff
function mysqli_func_get_next_id($tablepre, $table, $link = null) {
    return mysqli_insert_id($link);
}

// Get number of rows for table
function mysqli_func_get_num_rows($tablepre, $table, $link = null) {
    $connection = ($link instanceof mysqli ? $link : $SQLStat);

    $getnextidq = mysqli_func_pre_query("SHOW TABLE STATUS LIKE '$tablepre$table'", array());
    $getnextidr = $connection instanceof mysqli ? mysqli_func_query($getnextidq, $connection) : false;

    if ($getnextidr === false) {
        return false; // Handle query failure
    }

    $getnextid = mysqli_func_fetch_assoc($getnextidr);
    @mysqli_func_free_result($getnextidr);

    return $getnextid['Rows'] ?? 0;
}

// Fetch Number of Rows using COUNT in a single query (uses mysqli_func_fetch_assoc)
function mysqli_func_count_rows($query, $link = null, $countname = "cnt") {
    $result = mysqli_func_query($query, [], $link);  // Pass empty array for params
    $row = mysqli_func_fetch_assoc($result);

    if ($row === false) {
        return false;  // Handle case if no row is returned
    }

    // Use the dynamic column name provided by $countname
    $count = isset($row[$countname]) ? $row[$countname] : 0;

    @mysqli_func_free_result($result);
    return $count;
}

// Alternative version using mysqli_func_fetch_assoc
function mysqli_func_count_rows_alt($query, $link = null) {
    $result = mysqli_func_query($query, [], $link);  // Pass empty array for params
    $row = mysqli_func_fetch_assoc($result);
    
    if ($row === false) {
        return false;  // Handle case if no row is returned
    }
    
    // Return first column (assuming single column result like COUNT or similar)
    $count = reset($row);

    @mysqli_func_free_result($result);
    return $count;
}
?>
