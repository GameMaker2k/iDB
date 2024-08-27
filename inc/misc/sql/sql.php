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

    $FileInfo: sql.php - Last Update: 8/26/2024 SVN 1035 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="sql.php"||$File3Name=="/sql.php") {
	@header('Location: index.php');
	exit(); }

if(file_exists($SettDir['sql']."mysql.php")) {
	require($SettDir['sql']."mysql.php"); }
/*if(file_exists($SettDir['sql']."pdo_mysql.php")) {
	require($SettDir['sql']."pdo_mysql.php"); }*/
if(file_exists($SettDir['sql']."mysqli.php")) {
	require($SettDir['sql']."mysqli.php"); }
if(file_exists($SettDir['sql']."pgsql.php")) {
	require($SettDir['sql']."pgsql.php"); }
/*if(file_exists($SettDir['sql']."pdo_pgsql.php")) {
	require($SettDir['sql']."pdo_pgsql.php"); }*/
if(file_exists($SettDir['sql']."sqlite.php")) {
	require($SettDir['sql']."sqlite.php"); }
if(file_exists($SettDir['sql']."sqlite3.php")) {
	require($SettDir['sql']."sqlite3.php"); }
/*if(file_exists($SettDir['sql']."pdo_sqlite3.php")) {
	require($SettDir['sql']."pdo_sqlite3.php"); }*/
if(file_exists($SettDir['sql']."cubrid.php")) {
	require($SettDir['sql']."cubrid.php"); }
/*if(file_exists($SettDir['sql']."pdo_cubrid.php")) {
	require($SettDir['sql']."pdo_cubrid.php"); }*/

// Helper function to map sql library to its function prefix
function get_sql_function_prefix($sqllib) {
    $prefixes = array(
        'mysql' => 'mysql_func',
        'mysqli' => 'mysqli_func',
        'pdo_mysql' => 'pdo_mysql_func',
        'pgsql' => 'pgsql_func',
        'pdo_pgsql' => 'pdo_pgsql_func',
        'sqlite' => 'sqlite_func',
        'sqlite3' => 'sqlite3_func',
        'pdo_sqlite3' => 'pdo_sqlite3_func',
        'cubrid' => 'cubrid_func',
        'pdo_cubrid' => 'pdo_cubrid_func'
    );

    return isset($prefixes[$sqllib]) ? $prefixes[$sqllib] : null;
}

// Function to dynamically call the appropriate function based on $sqllib
function call_sql_function($func, $link = null, $sqllib = null, ...$params) {
    if ($sqllib === null) {
        global $Settings;
        $sqllib = $Settings['sqltype'];
    }
    $prefix = get_sql_function_prefix($sqllib);
    if ($prefix) {
        $functionName = $prefix . '_' . $func;
        if (function_exists($functionName)) {
            return $functionName($link, ...$params);
        } else {
            // Handle the case where the function does not exist
            return null; // or you can log an error, throw an exception, etc.
        }
    }
    return null; // or handle the error appropriately
}

// Wrapper functions
function sql_error($link = null, $sqllib = null) {
    return call_sql_function('error', $link, $sqllib);
}

function sql_errno($link = null, $sqllib = null) {
    return call_sql_function('errno', $link, $sqllib);
}

function sql_errorno($link = null, $sqllib = null) {
    return call_sql_function('errorno', $link, $sqllib);
}

function sql_query($query, $link = null, $sqllib = null) {
    return call_sql_function('query', $link, $sqllib, $query);
}

function sql_num_rows($result, $sqllib = null) {
    return call_sql_function('num_rows', null, $sqllib, $result);
}

function sql_connect_db($server, $username, $password, $database = null, $new_link = false, $sqllib = null) {
    var_dump($server, $username, $password, $database, $new_link, $sqllib);
	return call_sql_function('connect_db', null, $sqllib, $server, $username, $password, $database, $new_link);
}

function sql_disconnect_db($link = null, $sqllib = null) {
    return call_sql_function('disconnect_db', $link, $sqllib);
}

function sql_free_result($result, $sqllib = null) {
    return call_sql_function('free_result', null, $sqllib, $result);
}

function sql_fetch_array($result, $result_type = SQLITE_BOTH, $sqllib = null) {
    return call_sql_function('fetch_array', null, $sqllib, $result, $result_type);
}

function sql_fetch_assoc($result, $sqllib = null) {
    return call_sql_function('fetch_assoc', null, $sqllib, $result);
}

function sql_fetch_row($result, $sqllib = null) {
    return call_sql_function('fetch_row', null, $sqllib, $result);
}

function sql_server_info($link = null, $sqllib = null) {
    return call_sql_function('server_info', $link, $sqllib);
}

function sql_client_info($link = null, $sqllib = null) {
    return call_sql_function('client_info', $link, $sqllib);
}

function sql_escape_string($string, $link = null, $sqllib = null) {
    return call_sql_function('escape_string', $link, $sqllib, $string);
}

function sql_pre_query($query_string, $query_vars, $sqllib = null) {
    return call_sql_function('pre_query', null, $sqllib, $query_string, $query_vars);
}

function sql_set_charset($charset, $link = null, $sqllib = null) {
    return call_sql_function('set_charset', $link, $sqllib, $charset);
}

function sql_get_next_id($tablepre, $table, $link = null, $sqllib = null) {
    return call_sql_function('get_next_id', $link, $sqllib, $tablepre, $table);
}

function sql_get_num_rows($tablepre, $table, $link = null, $sqllib = null) {
    return call_sql_function('get_num_rows', $link, $sqllib, $tablepre, $table);
}
?>