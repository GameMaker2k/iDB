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

    $FileInfo: sql.php - Last Update: 8/28/2024 SVN 1053 - Author: cooldude2k $
*/

$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "sql.php" || $File3Name == "/sql.php") {
    @header('Location: index.php');
    exit();
}

// _convert_var handles additional variable types for different placeholders
function _convert_var($var, $placeholder) {
    switch ($placeholder) {
        case '%i':
        case '%I':
            // Cast to integer
            settype($var, 'integer');
            return $var;

        case '%f':
        case '%F':
            // Cast to float
            settype($var, 'float');
            return $var;

        case '%c':
            // Comma-separate an array of integers
            settype($var, 'array');
            $var = array_map('intval', $var);
            return implode(',', $var);

        case '%l':
            // Comma-separate without quotes
            settype($var, 'array');
            return implode(',', $var);

        case '%q':
            // Quote-comma separate strings
            settype($var, 'array');
            return implode("','", $var);

        case '%n':
            // Wrap value in single quotes unless it's NULL
            return $var === 'NULL' ? 'NULL' : $var;

        default:
            // Treat as a string (default behavior)
            return $var;
    }
}

// Example for dropping parts of the query based on variables
function handle_conditional_parts(&$query_string, &$query_vars) {
    preg_match_all('/\[(.*?)\]/', $query_string, $matches, PREG_OFFSET_CAPTURE);
    
    foreach ($matches[1] as $match) {
        // Check if all placeholders inside brackets correspond to non-empty values
        $optional_part = $match[0];
        $bracketed_vars = [];
        preg_match_all('/%[iIflqcn]/', $optional_part, $var_placeholders);

        foreach ($var_placeholders[0] as $ph) {
            // Find the corresponding value for the placeholder
            $pos = array_search($ph, $query_vars);
            if ($pos !== false && empty($query_vars[$pos])) {
                // If a variable inside the brackets is empty, remove the whole part
                $query_string = str_replace("[$optional_part]", '', $query_string);
                return;
            }
        }

        // If all variables inside the brackets are non-empty, remove only the brackets
        $query_string = str_replace("[$optional_part]", $optional_part, $query_string);
    }
}

$NumQueriesArray = array();
$NumPreQueriesArray = array();
if (file_exists($SettDir['sql'] . "pdo_mysql.php")&&extension_loaded("PDO")&&extension_loaded("PDO_MYSQL")) {
    require($SettDir['sql'] . "pdo_mysql.php");
}
if (file_exists($SettDir['sql'] . "mysqli.php")&&function_exists("mysqli_connect")) {
    require($SettDir['sql'] . "mysqli.php");
}
if (file_exists($SettDir['sql'] . "mysqli_prepare.php")&&function_exists("mysqli_connect")) {
    require($SettDir['sql'] . "mysqli_prepare.php");
}
if (file_exists($SettDir['sql'] . "pgsql.php")&&function_exists("pg_connect")) {
    require($SettDir['sql'] . "pgsql.php");
}
if (file_exists($SettDir['sql'] . "pgsql_prepare.php")&&function_exists("pg_connect")) {
    require($SettDir['sql'] . "pgsql_prepare.php");
}
if(file_exists($SettDir['sql']."pdo_pgsql.php")&&extension_loaded("PDO")&&extension_loaded("PDO_PGSQL")) {
    require($SettDir['sql']."pdo_pgsql.php");
}
if (file_exists($SettDir['sql'] . "sqlite3.php")&&class_exists('SQLite3')) {
    require($SettDir['sql'] . "sqlite3.php");
}
if (file_exists($SettDir['sql'] . "sqlite3_prepare.php")&&class_exists('SQLite3')) {
    require($SettDir['sql'] . "sqlite3_prepare.php");
}
if (file_exists($SettDir['sql'] . "pdo_sqlite3.php")&&extension_loaded("PDO")&&extension_loaded("PDO_SQLITE")) {
    require($SettDir['sql'] . "pdo_sqlite3.php");
}
if (file_exists($SettDir['sql'] . "cubrid.php")&&function_exists("cubrid_connect")) {
    require($SettDir['sql'] . "cubrid.php");
}
if (file_exists($SettDir['sql'] . "cubrid_prepare.php")&&function_exists("cubrid_connect")) {
    require($SettDir['sql'] . "cubrid_prepare.php");
}
if(file_exists($SettDir['sql']."pdo_cubrid.php")&&extension_loaded("PDO")&&extension_loaded("PDO_CUBRID")) {
    require($SettDir['sql']."pdo_cubrid.php");
}
if (file_exists($SettDir['sql'] . "pdo_sqlsrv.php")&&extension_loaded("PDO")&&extension_loaded("PDO_SQLSRV")) {
    require($SettDir['sql'] . "pdo_sqlsrv.php");
}
if (file_exists($SettDir['sql'] . "sqlsrv_prepare.php")&&function_exists("sqlsrv_connect")) {
    require($SettDir['sql'] . "sqlsrv_prepare.php");
}

// Helper function to map SQL library to its function prefix
function get_sql_function_prefix($sqllib) {
    $prefixes = array(
        'mysqli_prepare' => 'mysqli_prepare_func',
        'mysqli' => 'mysqli_func',
        'pdo_mysql' => 'pdo_mysql_func',
        'pgsql' => 'pgsql_func',
        'pgsql_prepare' => 'pgsql_prepare_func',
        'pdo_pgsql' => 'pdo_pgsql_func',
        'sqlite3_prepare' => 'sqlite3_prepare_func',
        'sqlite3' => 'sqlite3_func',
        'pdo_sqlite3' => 'pdo_sqlite3_func',
        'cubrid' => 'cubrid_func',
        'cubrid_prepare' => 'cubrid_prepare_func',
        'pdo_cubrid' => 'pdo_cubrid_func',
        'sqlsrv_prepare' => 'sqlsrv_prepare_func',
        'pdo_sqlsrv' => 'pdo_sqlsrv_func'
    );
    return isset($prefixes[$sqllib]) ? $prefixes[$sqllib] : null;
}

// Function to dynamically call the appropriate function based on $sqllib
function call_sql_function($func, $sqllib = null, ...$params) {
    if ($sqllib === null) {
        global $Settings;
        $sqllib = $Settings['sqltype'];
    }
    $prefix = get_sql_function_prefix($sqllib);
    if ($prefix) {
        $functionName = $prefix . '_' . $func;
        if (function_exists($functionName)) {
            return $functionName(...$params);
        } else {
            error_log("SQL function $functionName does not exist for $sqllib");
            throw new Exception("SQL function $functionName not found for library $sqllib.");
        }
    }
    throw new Exception("Invalid SQL library: $sqllib");
}

// Wrapper functions
function sql_error($link = null, $sqllib = null) {
    return call_sql_function('error', $sqllib, $link);
}

function sql_errno($link = null, $sqllib = null) {
    return call_sql_function('errno', $sqllib, $link);
}

function sql_errorno($link = null, $sqllib = null) {
    return call_sql_function('errorno', $sqllib, $link);
}

if (!isset($NumQueries)) {
    $NumQueries = 0;
}
if (!isset($NumQueriesArray['sql'])) {
    $NumQueriesArray['sql'] = 0;
}

function sql_query($query, $link = null, $sqllib = null) {
    global $NumQueries, $NumQueriesArray;
    $returnval = call_sql_function('query', $sqllib, $query, $link);
    if ($returnval) {
        ++$NumQueries;
		++$NumQueriesArray['sql'];
    }
    return $returnval;
}

function sql_num_rows($result, $sqllib = null) {
    return call_sql_function('num_rows', $sqllib, $result);
}

function sql_connect_db($server, $username, $password, $database = null, $new_link = false, $sqllib = null) {
    return call_sql_function('connect_db', $sqllib, $server, $username, $password, $database, $new_link);
}

function sql_result($result, $row, $field = 0, $sqllib = null) {
    return call_sql_function('result', $sqllib, $result, $row, $field);
}

function sql_disconnect_db($link = null, $sqllib = null) {
    return call_sql_function('disconnect_db', $sqllib, $link);
}

function sql_free_result($result, $sqllib = null) {
    return call_sql_function('free_result', $sqllib, $result);
}

function sql_fetch_array($result, $result_type = null, $sqllib = null) {
    return call_sql_function('fetch_array', $sqllib, $result, $result_type);
}

function sql_fetch_assoc($result, $sqllib = null) {
    return call_sql_function('fetch_assoc', $sqllib, $result);
}

function sql_fetch_row($result, $sqllib = null) {
    return call_sql_function('fetch_row', $sqllib, $result);
}

function sql_server_info($link = null, $sqllib = null) {
    return call_sql_function('server_info', $sqllib, $link);
}

function sql_client_info($link = null, $sqllib = null) {
    return call_sql_function('client_info', $sqllib, $link);
}

function sql_escape_string($string, $link = null, $sqllib = null) {
    return call_sql_function('escape_string', $sqllib, $string, $link);
}

if (!isset($NumQueries)) {
    $NumPreQueries = 0;
}
if (!isset($NumPreQueriesArray['sql'])) {
    $NumPreQueriesArray['sql'] = 0;
}

function sql_pre_query($query_string, $query_vars, $sqllib = null) {
	global $NumPreQueries, $NumPreQueriesArray;

    $returnval = call_sql_function('pre_query', $sqllib, $query_string, $query_vars);
    if ($returnval) {
        ++$NumPreQueries;
        ++$NumPreQueriesArray['sql'];
    }
    return $returnval;
}

function sql_set_charset($charset, $link = null, $sqllib = null) {
    global $NumPreQueries, $NumQueriesArray;
    return call_sql_function('set_charset', $sqllib, $charset, $link);
}

function sql_get_next_id($tablepre, $table, $link = null, $sqllib = null) {
    return call_sql_function('get_next_id', $sqllib, $tablepre, $table, $link);
}

function sql_get_num_rows($tablepre, $table, $link = null, $sqllib = null) {
    return call_sql_function('get_num_rows', $sqllib, $tablepre, $table, $link);
}

function sql_count_rows($query, $link = null, $countname = "cnt", $sqllib = null) {
    return call_sql_function('count_rows', $sqllib, $query, $link, $countname);
}

function sql_count_rows_alt($query, $link = null, $sqllib = null) {
    return call_sql_function('count_rows_alt', $sqllib, $query, $link);
}
?>
