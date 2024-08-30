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

    $FileInfo: pdo_sqlite3.php - Last Update: 8/30/2024 SVN 1058 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="pdo_sqlite3.php"||$File3Name=="/pdo_sqlite3.php") {
	@header('Location: index.php');
	exit(); }
// SQLite Functions.
function pdo_sqlite3_func_error($link=null) {
global $SQLStat;
if(isset($link)) {
	$result = $link->errorInfo(); }
if(!isset($link)) {
	$result = $SQLStat->errorInfo(); }
if ($result=="") {
	return ""; }
	return $result; }
function pdo_sqlite3_func_errno($link=null) {
global $SQLStat;
if(isset($link)) {
	$result = $link->errorCode(); }
if(!isset($link)) {
	$result = $SQLStat->errorCode(); }
if ($result===0) {
	return 0; }
	return $result; }
function pdo_sqlite3_func_errorno($link=null) {
global $SQLStat;
if(isset($link)) {
	$result = $link->errorCode().": ".$link->errorInfo(); }
if(!isset($link)) {
	$result = $SQLStat->errorCode().": ".$SQLStat->errorInfo(); }
if ($result=="") {
	return ""; }
	return $result; }
// Execute a query :P
$NumQueries = 0;
function pdo_sqlite3_func_query($query, $link = null) {
    global $NumQueries, $SQLStat;
    
    // Use the appropriate PDO connection
    $pdo = isset($link) ? $link : $SQLStat;
    
    // Check if the query is an array containing the query string and parameters
    if (is_array($query)) {
        list($query_string, $params) = $query;
        $stmt = $pdo->prepare($query_string);

        // Debugging: Log the query and parameters
        error_log("Executing SQL: " . $query_string);
        error_log("With Parameters: " . implode(", ", $params));

        // Execute with parameters
        $result = $stmt->execute($params);
    } else {
        // For direct queries without parameters
        $result = $pdo->query($query);
    }

    if ($result === false) {
        // Improved error handling
        $errorInfo = $pdo->errorInfo();
        output_error("SQL Error: " . $errorInfo[2], E_USER_ERROR);
        return false;
    }

    if ($result !== false) {
        ++$NumQueries;
        return $result;
    }
}
//Fetch Number of Rows
function pdo_sqlite3_func_num_rows($result) {
    if ($result instanceof PDOStatement) {
        $num = $result->rowCount();
        if ($num === false) {
            output_error("SQL Error: " . pdo_sqlite3_func_error(), E_USER_ERROR);
            return false;
        }
        return $num;
    } else {
        output_error("SQL Error: Invalid result type. Expected PDOStatement, got " . gettype($result), E_USER_ERROR);
        return false;
    }
}
// Connect to sqlite database
function pdo_sqlite3_func_connect_db($server,$username,$password,$database=null,$new_link=false) {
global $SQLStat;
if($new_link!==true) { $new_link = false; }
if($database===null) {
return true; }
if($database!==null) {
$link = new PDO("sqlite:".$database); }
if ($link===false) {
    output_error("Not connected: ".$sqliteerror,E_USER_ERROR);
	return false; }
return $link; }
function pdo_sqlite3_func_disconnect_db($link = null) {
    global $SQLStat; // Assuming this is your PDO object

    // If a specific link is provided (assuming $link is a PDOStatement), close the cursor
    if (isset($link) && $link instanceof PDOStatement) {
        return $link->closeCursor();
    }
    
    // If no link is provided, we assume $SQLStat is a PDO object and we just nullify it to disconnect
    if (!isset($link) && isset($SQLStat)) {
        $SQLStat = null; // This effectively disconnects from the database
        return true;
    }
    
    return false;
}
// Query Results :P
function pdo_sqlite3_func_result($result,$row,$field=0) {
$check = true;
$num = 0;
$result->reset();
while ($num<$row) {
	$result->fetch(PDO::FETCH_BOTH);
    $num++; }
if ($check===false) {
    output_error("SQL Error: ".pdo_sqlite3_func_error(),E_USER_ERROR);
	return false; }
$trow = $result->fetch(PDO::FETCH_BOTH);
if(!isset($trow[$field])) { $trow[$field] = null; }
$retval = $trow[$field]; 
return $retval; }
// Free Results :P
function pdo_sqlite3_func_free_result($result) {
	return true; }
//Fetch Results to Array
function pdo_sqlite3_func_fetch_array($result,$result_type=SQLITE3_BOTH) {
$row = $result->fetch($result_type);
	return $row; }
//Fetch Results to Associative Array
function pdo_sqlite3_func_fetch_assoc($result) {
$row = $result->fetch(PDO::FETCH_ASSOC);
	return $row; }
//Fetch Row Results
function pdo_sqlite3_func_fetch_row($result) {
$row = $result->fetch(PDO::FETCH_NUM);
	return $row; }
//Get Server Info
function pdo_sqlite3_func_server_info($link=null) {
	$result = $link->query('select sqlite_version()')->fetch()[0];
	return $result; }
//Get Client Info
function pdo_sqlite3_func_client_info($link=null) {
	return null; }
function pdo_sqlite3_func_escape_string($string,$link=null) {
global $SQLStat;
 if(isset($string)&&!is_null($string)) {
	if(isset($link)) {
		$string = $link->quote($string); }
	if(!isset($link)) {
		$string = $SQLStat->quote($string); } }
if ($string===false) {
    output_error("SQL Error: ".pdo_sqlite3_func_error(),E_USER_ERROR);
	return false; }
	return $string; }
function pdo_sqlite3_func_pre_query($query_string, $query_vars = []) {
    if ($query_vars === null || !is_array($query_vars)) { 
        $query_vars = []; 
    }

    if (is_array($query_vars) && count($query_vars) > 0 && $query_vars[0] === null) {
        $query_vars = [];
    }

    // Escape literal ? and empty strings
    $query_string = str_replace(['\?', "''"], ['{LITERAL_QUESTION_MARK}', "{LITERAL_EMPTY_STRING}"], $query_string);

    // Replace placeholders
    $query_string = str_replace(["'%s'", '%d', '%i', '%f'], ['?', '?', '?', '?'], $query_string);

    // Remove null values from query_vars
    $query_vars = array_filter($query_vars, function($value) {
        return $value !== null;
    });

    // Count placeholders and check mismatch
    $placeholder_count = substr_count($query_string, '?');
    $params_count = count($query_vars);

    if ($placeholder_count !== $params_count) {
        output_error("SQL Placeholder Error: Mismatch between placeholders ($placeholder_count) and parameters ($params_count).", E_USER_ERROR);
        return false;
    }

    // Restore the literal ? and empty strings
    $query_string = str_replace(['{LITERAL_QUESTION_MARK}', '{LITERAL_EMPTY_STRING}'], ['?', "''"], $query_string);

    return [$query_string, $query_vars];
}

function pdo_sqlite3_func_set_charset($charset,$link=null) {
	return true; }
/*
function pdo_sqlite3_func_set_charset($charset,$link=null) {
if(function_exists('mysql_set_charset')===false) {
if(!isset($link)) {
	$result = pdo_sqlite3_func_query("SET CHARACTER SET '".$charset."'"); }
if(isset($link)) {
	$result = pdo_sqlite3_func_query("SET CHARACTER SET '".$charset."'",$link); }
if ($result===false) {
    output_error("SQL Error: ".pdo_sqlite3_func_error(),E_USER_ERROR);
	return false; }
if(!isset($link)) {
	$result = pdo_sqlite3_func_query("SET NAMES '".$charset."'"); }
if(isset($link)) {
	$result = pdo_sqlite3_func_query("SET NAMES '".$charset."'",$link); } 
if ($result===false) {
    output_error("SQL Error: ".pdo_sqlite3_func_error(),E_USER_ERROR);
	return false; }
	return true; }
if(function_exists('mysql_set_charset')===true) {
if(isset($link)) {
	$result = mysql_set_charset($charset,$link); }
if(!isset($link)) {
	$result = mysql_set_charset($charset); }
if ($result===false) {
    output_error("SQL Error: ".pdo_sqlite3_func_error(),E_USER_ERROR);
	return false; }
	return true; }
if(function_exists('mysql_set_charset')===false) {
function mysql_set_charset($charset,$link) {
if(isset($link)) {
	$result = pdo_sqlite3_func_set_charset($charset,$link); }
if(!isset($link)) {
	$result = pdo_sqlite3_func_set_charset($charset); }
if ($result===false) {
    output_error("SQL Error: ".pdo_sqlite3_func_error(),E_USER_ERROR);
	return false; }
	return true; } }
*/
// Get next id for stuff
function pdo_sqlite3_func_get_next_id($tablepre,$table,$link=null) {
	global $SQLStat;
	if(isset($link)) {
		$nid = $link->lastInsertId(); }
	if(!isset($link)) {
		$nid = $SQLStat->lastInsertId(); }
	return $nid; }
// Get number of rows for table
function pdo_sqlite3_func_get_num_rows($tablepre,$table,$link=null) {
   $getnextidq = pdo_sqlite3_func_pre_query("SHOW TABLE STATUS LIKE '".$tablepre.$table."'", array());
if(!isset($link)) {
	$getnextidr = pdo_sqlite3_func_query($getnextidq); }
if(isset($link)) {
	$getnextidr = pdo_sqlite3_func_query($getnextidq,$link); } 
   $getnextid = pdo_sqlite3_func_fetch_assoc($getnextidr);
   return $getnextid['Rows'];
   @sql_free_result($getnextidr); }
?>
