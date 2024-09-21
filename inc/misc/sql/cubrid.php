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

    $FileInfo: cubrid.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "cubrid.php" || $File3Name == "/cubrid.php") {
    @header('Location: index.php');
    exit();
}

// CUBRID Error handling functions
function cubrid_func_error($link = null) {
    return cubrid_error_msg();
}

function cubrid_func_errno($link = null) {
    return cubrid_error_code();
}

function cubrid_func_errorno($link = null) {
    $result = cubrid_func_errno() . ": " . cubrid_func_error();
    return $result;
}

// Execute a query
if (!isset($NumQueriesArray['cubrid'])) {
    $NumQueriesArray['cubrid'] = 0;
}

function cubrid_func_query($query, $link = null) {
    global $NumQueriesArray, $SQLStat;

    $result = isset($link) ? cubrid_query($query, $link) : cubrid_query($query, $SQLStat);

    if ($result === false) {
        output_error("SQL Error: " . cubrid_func_error(), E_USER_ERROR);
        return false;
    }

    if ($result !== false) {
        ++$NumQueriesArray['cubrid'];
        return $result;
    }
}

// Fetch number of rows
function cubrid_func_num_rows($result) {
    $num = cubrid_num_rows($result);
    return ($num === false) ? false : $num;
}

// Connect to CUBRID database
function cubrid_func_connect_db($server, $username, $password, $database = null, $new_link = false) {
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
        output_error("Not connected: " . cubrid_func_error(), E_USER_ERROR);
        return false;
    }

    return $link;
}

function cubrid_func_disconnect_db($link = null) {
    return cubrid_disconnect($link);
}

// Query Results
function cubrid_func_result($result, $row, $field = 0) {
    if (isset($field) && !is_numeric($field)) {
        $field = strtolower($field);
    }

    $value = cubrid_result($result, $row, $field);
    return ($value === false) ? false : $value;
}

// Free Results
function cubrid_func_free_result($result) {
    $fresult = cubrid_free_result($result);
    $closeReq = cubrid_close_request($result);

    if ($fresult === false || $closeReq === false) {
        output_error("SQL Error: " . cubrid_func_error(), E_USER_ERROR);
        return false;
    }

    return true;
}

// Fetch Results to Array
function cubrid_func_fetch_array($result, $result_type = CUBRID_BOTH) {
    return cubrid_fetch_array($result, $result_type);
}

// Fetch Results to Associative Array
function cubrid_func_fetch_assoc($result) {
    return cubrid_fetch_assoc($result);
}

// Fetch Row Results
function cubrid_func_fetch_row($result) {
    return cubrid_fetch_row($result);
}

// Get Server Info
function cubrid_func_server_info($link = null) {
    return isset($link) ? cubrid_get_server_info($link) : cubrid_get_server_info();
}

// Get Client Info
function cubrid_func_client_info($link = null) {
    return cubrid_get_client_info();
}

// Escape String
function cubrid_func_escape_string($string, $link = null) {
    return isset($link) ? cubrid_real_escape_string($string, $link) : cubrid_real_escape_string($string);
}

// SafeSQL Lite Source Code by Cool Dude 2k
// Make SQL Query's safe
function cubrid_func_pre_query($query_string, $query_vars) {
    if ($query_vars == null) {
        $query_vars = array(null);
    }
    
    $query_array = array(array("%i", "%I", "%F", "%S"), array("%d", "%d", "%f", "%s"));
    $query_string = str_replace($query_array[0], $query_array[1], $query_string);

    if (get_magic_quotes_gpc()) {
        $query_vars = array_map("stripslashes", $query_vars);
    }

    $query_vars = array_map("cubrid_func_escape_string", $query_vars);
    $query_val = $query_vars;
    $query_num = count($query_val);
    $query_i = 0;

    while ($query_i < $query_num) {
        $query_val[$query_i + 1] = $query_vars[$query_i];
        ++$query_i;
    }

    $query_val[0] = $query_string;
    return call_user_func_array("sprintf", $query_val);
}

// Set Charset (dummy function)
function cubrid_func_set_charset($charset, $link = null) {
    return true;
}

// Get next id for stuff
function cubrid_func_get_next_id($tablepre, $table, $link = null) {
    $getnextidq = cubrid_func_pre_query("SELECT " . $tablepre . $table . "_ai_id.current_value;", array());
    $getnextidr = cubrid_func_query($getnextidq, $link);
    return cubrid_func_result($getnextidr, 0);
}

// Get number of rows for table
function cubrid_func_get_num_rows($tablepre, $table, $link = null) {
    $getnextidq = cubrid_func_pre_query("SHOW TABLE STATUS LIKE '" . $tablepre . $table . "'", array());
    $getnextidr = cubrid_func_query($getnextidq, $link);
    $getnextid = cubrid_func_fetch_assoc($getnextidr);
    return $getnextid['Rows'] ?? 0;
}

// Fetch Number of Rows using COUNT in a single query
function cubrid_func_count_rows($query, $link = null) {
    $get_num_result = cubrid_func_query($query, $link);
    $ret_num_result = cubrid_func_result($get_num_result, 0);
    @cubrid_func_free_result($get_num_result);
    return $ret_num_result;
}

function cubrid_func_count_rows_alt($query, $link = null) {
    $get_num_result = cubrid_func_query($query, $link);
    $ret_num_result = cubrid_func_result($get_num_result, 0, 'cnt');
    @cubrid_func_free_result($get_num_result);
    return $ret_num_result;
}
?>
