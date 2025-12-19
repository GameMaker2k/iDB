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

    $FileInfo: cubrid_prepare.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/

$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "cubrid_prepare.php" || $File3Name == "/cubrid_prepare.php") {
    @header('Location: index.php');
    exit();
}

// Execute a query
if (!isset($NumPreQueriesArray['cubrid_prepare'])) {
    $NumPreQueriesArray['cubrid_prepare'] = 0;
}

// CUBRID Error handling functions
function cubrid_prepare_func_error($link = null)
{
    return cubrid_error_msg();
}

function cubrid_prepare_func_errno($link = null)
{
    return cubrid_error_code();
}

function cubrid_prepare_func_errorno($link = null)
{
    return cubrid_prepare_func_errno() . ": " . cubrid_prepare_func_error();
}

// Execute a prepared query
if (!isset($NumQueriesArray['cubrid_prepare'])) {
    $NumQueriesArray['cubrid_prepare'] = 0;
}

function cubrid_prepare_func_query($query, $params = [], $link = null)
{
    global $NumQueriesArray, $SQLStat;

    $db = isset($link) ? $link : (isset($SQLStat) ? $SQLStat : null);

    if (!$db) {
        output_error("SQL Error: No valid CUBRID connection.", E_USER_ERROR);
        return false;
    }

    // Check if the query is an array (query string and parameters)
    if (is_array($query)) {
        list($query_string, $params) = $query;
    } else {
        $query_string = $query;
    }

    // Prepare the query
    $stmt = cubrid_prepare($db, $query_string);
    if ($stmt === false) {
        output_error("SQL Error (Prepare): " . cubrid_prepare_func_error(), E_USER_ERROR);
        return false;
    }

    // Bind parameters dynamically
    foreach ($params as $key => $value) {
        $paramKey = $key + 1; // CUBRID uses 1-based indexing for bind parameters
        if (is_int($value)) {
            cubrid_bind($stmt, $paramKey, $value, CUBRID_INTEGER);
        } elseif (is_bool($value)) {
            cubrid_bind($stmt, $paramKey, $value, CUBRID_BOOL);
        } elseif (is_null($value)) {
            cubrid_bind($stmt, $paramKey, $value, CUBRID_NULL);
        } else {
            cubrid_bind($stmt, $paramKey, $value, CUBRID_STRING);
        }
    }

    // Execute the query
    if (!cubrid_execute($stmt)) {
        output_error("SQL Error (Execution): " . cubrid_prepare_func_error(), E_USER_ERROR);
        return false;
    }

    ++$NumQueriesArray['cubrid_prepare'];
    return $stmt;
}

// Fetch number of rows
function cubrid_prepare_func_num_rows($stmt)
{
    return cubrid_num_rows($stmt);
}

// Connect to CUBRID database
function cubrid_prepare_func_connect_db($server, $username, $password, $database = null, $new_link = false)
{
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

function cubrid_prepare_func_disconnect_db($link = null)
{
    return cubrid_disconnect($link);
}

// Query Results
function cubrid_prepare_func_result($stmt, $row, $field = 0)
{
    if (isset($field) && !is_numeric($field)) {
        $field = strtolower($field);
    }

    $value = cubrid_fetch($stmt, CUBRID_NUM);
    return ($value === false) ? false : $value[$field];
}

// Free Results
function cubrid_prepare_func_free_result($stmt)
{
    cubrid_close_request($stmt);
    return cubrid_free_result($stmt);
}

// Fetch Results to Array
function cubrid_prepare_func_fetch_array($stmt, $result_type = CUBRID_BOTH)
{
	if($result_type==NULL) {
		$result_type = CUBRID_BOTH;
	}
    return cubrid_fetch($stmt, $result_type);
}

function cubrid_prepare_func_fetch_assoc($stmt)
{
    return cubrid_fetch($stmt, CUBRID_ASSOC);
}

function cubrid_prepare_func_fetch_row($stmt)
{
    return cubrid_fetch($stmt, CUBRID_NUM);
}

// Get Server Info
function cubrid_prepare_func_server_info($link = null)
{
    return isset($link) ? cubrid_get_server_info($link) : cubrid_get_server_info();
}

function cubrid_prepare_func_client_info($link = null)
{
    return cubrid_get_client_info();
}

// Escape String
function cubrid_prepare_func_escape_string($string, $link = null)
{
    if (!isset($string)) {
        return null;
    }
    return cubrid_real_escape_string($string, $link);
}

// SafeSQL Lite with additional SafeSQL features
function cubrid_prepare_func_pre_query($query_string, $query_vars)
{
    global $NumPreQueriesArray;

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

    ++$NumPreQueriesArray['cubrid_prepare'];

    // Return the query string and the array of variables
    return [$query_string, $query_vars];
}

// Get next id for stuff
function cubrid_prepare_func_get_next_id($tablepre, $table, $link = null)
{
    $query = "SELECT " . $tablepre . $table . "_ai_id.current_value";
    $stmt = cubrid_prepare_func_query($query, [], $link);
    return cubrid_prepare_func_result($stmt, 0);
}

function cubrid_prepare_func_count_rows($query, $link = null, $countname = "cnt")
{
    $result = cubrid_prepare_func_query($query, [], $link);  // Pass empty array for params
    $row = cubrid_prepare_func_fetch_assoc($result);

    if ($row === false) {
        return false;
    }

    $count = isset($row[$countname]) ? $row[$countname] : 0;

    @cubrid_prepare_func_free_result($result);
    return $count;
}

function cubrid_prepare_func_count_rows_alt($query, $link = null)
{
    $result = cubrid_prepare_func_query($query, [], $link);  // Pass empty array for params
    $row = cubrid_prepare_func_fetch_assoc($result);

    if ($row === false) {
        return false;
    }

    $count = reset($row);

    @cubrid_prepare_func_free_result($result);
    return $count;
}
