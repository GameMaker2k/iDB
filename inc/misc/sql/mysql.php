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

    $FileInfo: mysql.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/

$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "mysql.php" || $File3Name == "/mysql.php") {
    @header('Location: index.php');
    exit();
}

// MySQL Error handling functions
function mysql_func_error($link = null) {
    global $SQLStat;
    return isset($link) ? mysql_error($link) : mysql_error($SQLStat);
}

function mysql_func_errno($link = null) {
    global $SQLStat;
    return isset($link) ? mysql_errno($link) : mysql_errno($SQLStat);
}

function mysql_func_errorno($link = null) {
    global $SQLStat;
    $result = isset($link) ? mysql_func_error($link) : mysql_func_error($SQLStat);
    $resultno = isset($link) ? mysql_func_errno($link) : mysql_func_errno($SQLStat);

    return ($result == "" && $resultno === 0) ? "" : "$resultno: $result";
}

// Execute a query
if (!isset($NumQueriesArray['mysql'])) {
    $NumQueriesArray['mysql'] = 0;
}

function mysql_func_query($query, $link = null) {
    global $NumQueriesArray, $SQLStat;
    
    $result = isset($link) ? mysql_query($query, $link) : mysql_query($query, $SQLStat);

    if ($result === false) {
        output_error("SQL Error: " . mysql_func_error(), E_USER_ERROR);
        return false;
    }

    if ($result !== false) {
        ++$NumQueriesArray['mysql'];
        return $result;
    }
}

// Fetch Number of Rows
function mysql_func_num_rows($result) {
    $num = mysql_num_rows($result);
    
    if ($num === false) {
        output_error("SQL Error: " . mysql_func_error(), E_USER_ERROR);
        return false;
    }
    
    return $num;
}

// Connect to MySQL database
function mysql_func_connect_db($server, $username, $password, $database = null, $new_link = false) {
    $link = $new_link === true ? mysql_connect($server, $username, $password, $new_link) : mysql_connect($server, $username, $password);

    if ($link === false) {
        output_error("Not connected: " . mysql_func_error(), E_USER_ERROR);
        return false;
    }

    if ($database !== null) {
        $dlink = mysql_select_db($database, $link);
        if ($dlink === false) {
            output_error("Can't use database $database: " . mysql_func_error(), E_USER_ERROR);
            return false;
        }
    }

    $result = mysql_func_query("SET SESSION SQL_MODE='ANSI,ANSI_QUOTES,TRADITIONAL,STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION,NO_AUTO_VALUE_ON_ZERO';", $link);

    if ($result === false) {
        output_error("SQL Error: " . mysql_func_error(), E_USER_ERROR);
        return false;
    }

    return $link;
}

function mysql_func_disconnect_db($link = null) {
    return mysql_close($link);
}

// Query Results
function mysql_func_result($result, $row, $field = 0) {
    $value = mysql_result($result, $row, $field);
    
    if ($value === false) {
        output_error("SQL Error: " . mysql_func_error(), E_USER_ERROR);
        return false;
    }
    
    return $value;
}

// Free Results
function mysql_func_free_result($result) {
    $fresult = mysql_free_result($result);

    if ($fresult === false) {
        output_error("SQL Error: " . mysql_func_error(), E_USER_ERROR);
        return false;
    }

    return true;
}

// Fetch Results to Array
function mysql_func_fetch_array($result, $result_type = MYSQL_BOTH) {
    return mysql_fetch_array($result, $result_type);
}

// Fetch Results to Associative Array
function mysql_func_fetch_assoc($result) {
    return mysql_fetch_assoc($result);
}

// Fetch Row Results
function mysql_func_fetch_row($result) {
    return mysql_fetch_row($result);
}

// Get Server Info
function mysql_func_server_info($link = null) {
    return isset($link) ? mysql_get_server_info($link) : mysql_get_server_info();
}

// Get Client Info
function mysql_func_client_info($link = null) {
    return mysql_get_client_info();
}

// Escape String
function mysql_func_escape_string($string, $link = null) {
	if (!isset($string)) return null;
    return isset($link) ? mysql_real_escape_string($string, $link) : mysql_real_escape_string($string);
}

// SafeSQL Lite Source Code by Cool Dude 2k
// Make SQL Queries safe
// SafeSQL Lite with additional SafeSQL features
function mysql_func_pre_query($query_string, $query_vars) {
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

    // Escape each variable using mysql_func_escape_string
    $query_vars = array_map("mysql_func_escape_string", $query_vars);

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
function mysql_func_set_charset($charset, $link = null) {
    if (function_exists('mysql_set_charset') === false) {
        $result = isset($link) ? mysql_func_query("SET CHARACTER SET '$charset'", $link) : mysql_func_query("SET CHARACTER SET '$charset'");

        if ($result === false) {
            output_error("SQL Error: " . mysql_func_error(), E_USER_ERROR);
            return false;
        }

        $result = isset($link) ? mysql_func_query("SET NAMES '$charset'", $link) : mysql_func_query("SET NAMES '$charset'");

        if ($result === false) {
            output_error("SQL Error: " . mysql_func_error(), E_USER_ERROR);
            return false;
        }

        return true;
    }

    $result = isset($link) ? mysql_set_charset($charset, $link) : mysql_set_charset($charset);

    if ($result === false) {
        output_error("SQL Error: " . mysql_func_error(), E_USER_ERROR);
        return false;
    }

    return true;
}

// Get next id for stuff
function mysql_func_get_next_id($tablepre, $table, $link = null) {
    return mysql_insert_id($link);
}

// Get number of rows for table
function mysql_func_get_num_rows($tablepre, $table, $link = null) {
    $getnextidq = mysql_func_pre_query("SHOW TABLE STATUS LIKE '$tablepre$table'", array());
    $getnextidr = isset($link) ? mysql_func_query($getnextidq, $link) : mysql_func_query($getnextidq);
    $getnextid = mysql_func_fetch_assoc($getnextidr);
    return $getnextid['Rows'];
    @mysql_free_result($getnextidr);
}

// Fetch Number of Rows using COUNT in a single query
function mysql_func_count_rows($query, $link = null) {
    $get_num_result = mysql_func_query($query, $link);
    $ret_num_result = mysql_func_result($get_num_result, 0);
    @mysql_func_free_result($get_num_result);
    return $ret_num_result;
}

// Fetch Number of Rows using COUNT in a single query
function mysql_func_count_rows_alt($query, $link = null) {
    $get_num_result = mysql_func_query($query, $link);
    $ret_num_result = mysql_func_result($get_num_result, 0, 'cnt');
    @mysql_func_free_result($get_num_result);
    return $ret_num_result;
}
?>
