<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the Revised BSD License for more details.

    Copyright 2004-2024 iDB Support - https://idb.osdn.jp/support/category.php?act=view&id=1
    Copyright 2004-2024 Game Maker 2k - https://idb.osdn.jp/support/category.php?act=view&id=2

    $FileInfo: cubrid_prepare.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/

$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "cubrid_prepare.php" || $File3Name == "/cubrid_prepare.php") {
    @header('Location: index.php');
    exit();
}

// CUBRID Error handling functions
function cubrid_prepare_func_error($link = null) {
    return cubrid_error_msg();
}

function cubrid_prepare_func_errno($link = null) {
    return cubrid_error_code();
}

function cubrid_prepare_func_errorno($link = null) {
    return cubrid_prepare_func_errno() . ": " . cubrid_prepare_func_error();
}

// Execute a prepared query
if (!isset($NumQueriesArray['cubrid'])) {
    $NumQueriesArray['cubrid'] = 0;
}

function cubrid_prepare_func_query($query, $params = [], $link = null) {
    global $NumQueriesArray, $SQLStat;
    $stmt = isset($link) ? cubrid_prepare($link, $query) : cubrid_prepare($SQLStat, $query);

    if ($stmt === false) {
        output_error("SQL Error: " . cubrid_prepare_func_error(), E_USER_ERROR);
        return false;
    }

    // Bind parameters dynamically based on their types
    foreach ($params as $key => $value) {
        if (is_int($value)) {
            cubrid_bind($stmt, $key + 1, $value, CUBRID_INTEGER);
        } elseif (is_bool($value)) {
            cubrid_bind($stmt, $key + 1, $value, CUBRID_BOOL);
        } elseif (is_null($value)) {
            cubrid_bind($stmt, $key + 1, $value, CUBRID_NULL);
        } else {
            cubrid_bind($stmt, $key + 1, $value, CUBRID_STRING);
        }
    }

    // Execute the statement
    if (!cubrid_execute($stmt)) {
        output_error("SQL Error: " . cubrid_prepare_func_error(), E_USER_ERROR);
        return false;
    }

    ++$NumQueriesArray['cubrid'];
    return $stmt;
}

// Fetch number of rows
function cubrid_prepare_func_num_rows($stmt) {
    return cubrid_num_rows($stmt);
}

// Connect to CUBRID database
function cubrid_prepare_func_connect_db($server, $username, $password, $database = null, $new_link = false) {
    $myport = "30000";
    $hostex = explode(":", $server);

    if (isset($hostex[1]) && !is_numeric($hostex[1])) {
        $hostex[1] = $myport;
    }
    if (isset($hostex[1])) {
        $server = $hostex[0];
        $myport = $hostex[1];
    }

    $link = cubrid_connect($server, $myport, $database, $username, $password);
    cubrid_set_autocommit($link, CUBRID_AUTOCOMMIT_TRUE);

    if ($link === false) {
        output_error("Not connected: " . cubrid_prepare_func_error(), E_USER_ERROR);
        return false;
    }

    return $link;
}

function cubrid_prepare_func_disconnect_db($link = null) {
    return cubrid_disconnect($link);
}

// Query Results
function cubrid_prepare_func_result($stmt, $row, $field = 0) {
    if (isset($field) && !is_numeric($field)) {
        $field = strtolower($field);
    }

    $value = cubrid_fetch($stmt, CUBRID_NUM);
    return ($value === false) ? false : $value[$field];
}

// Free Results
function cubrid_prepare_func_free_result($stmt) {
    cubrid_close_request($stmt);
    return cubrid_free_result($stmt);
}

// Fetch Results to Array
function cubrid_prepare_func_fetch_array($stmt, $result_type = CUBRID_BOTH) {
    return cubrid_fetch($stmt, $result_type);
}

// Fetch Results to Associative Array
function cubrid_prepare_func_fetch_assoc($stmt) {
    return cubrid_fetch($stmt, CUBRID_ASSOC);
}

// Fetch Row Results
function cubrid_prepare_func_fetch_row($stmt) {
    return cubrid_fetch($stmt, CUBRID_NUM);
}

// Get Server Info
function cubrid_prepare_func_server_info($link = null) {
    return isset($link) ? cubrid_get_server_info($link) : cubrid_get_server_info();
}

// Get Client Info
function cubrid_prepare_func_client_info($link = null) {
    return cubrid_get_client_info();
}

// Escape String
function cubrid_prepare_func_escape_string($string, $link = null) {
	if (!isset($string)) return null;
    return cubrid_real_escape_string($string, $link);
}

// SafeSQL Lite with additional SafeSQL features
function cubrid_prepare_func_pre_query($query_string, $query_vars) {
    // If no query variables are provided, initialize with an empty array
    if ($query_vars == null) {
        $query_vars = [];
    }

    // Handle multiple placeholders including %c, %l, %q, and %n
    $query_array = array(
        array("%i", "%I", "%F", "%S", "%c", "%l", "%q", "%n"),
        array("?", "?", "?", "?", "?", "?", "?", "?")
    );

    // Replace placeholders with positional `?` for prepared statements
    $query_string = str_replace($query_array[0], $query_array[1], $query_string);

    // Prepare variables for use in prepared statements
    $query_vars = array_map(function($val) {
        if (is_array($val)) {
            return implode(',', $val);  // Handle comma-separated cases (%c, %l)
        }
        return $val;
    }, $query_vars);

    return [$query_string, $query_vars];
}

// Get next id for stuff
function cubrid_prepare_func_get_next_id($tablepre, $table, $link = null) {
    $query = "SELECT " . $tablepre . $table . "_ai_id.current_value";
    $stmt = cubrid_prepare_func_query($query, [], $link);
    return cubrid_prepare_func_result($stmt, 0);
}

// Fetch Number of Rows using COUNT in a single query
function cubrid_prepare_func_count_rows($query, $params = [], $link = null) {
    $stmt = cubrid_prepare_func_query($query, $params, $link);
    $ret_num_result = cubrid_prepare_func_result($stmt, 0);
    cubrid_prepare_func_free_result($stmt);
    return $ret_num_result;
}

// Fetch Number of Rows using COUNT in a single query (alternative)
function cubrid_prepare_func_count_rows_alt($query, $params = [], $link = null) {
    $stmt = cubrid_prepare_func_query($query, $params, $link);
    $ret_num_result = cubrid_prepare_func_result($stmt, 0, 'cnt');
    cubrid_prepare_func_free_result($stmt);
    return $ret_num_result;
}
?>
