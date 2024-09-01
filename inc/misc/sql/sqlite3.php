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
if ($File3Name=="sqlite3.php"||$File3Name=="/sqlite3.php") {
	@header('Location: index.php');
	exit(); }
// SQLite Functions.
function sqlite3_func_error($link=null) {
global $SQLStat;
if(isset($link)) {
	$result = $link->lastErrorMsg(); }
if(!isset($link)) {
	$result = $SQLStat->lastErrorMsg(); }
if ($result=="") {
	return ""; }
	return $result; }
function sqlite3_func_errno($link=null) {
global $SQLStat;
if(isset($link)) {
	$result = $link->lastErrorCode(); }
if(!isset($link)) {
	$result = $SQLStat->lastErrorCode(); }
if ($result===0) {
	return 0; }
	return $result; }
function sqlite3_func_errorno($link=null) {
global $SQLStat;
if(isset($link)) {
	$result = $link->lastErrorCode().": ".$link->lastErrorMsg(); }
if(!isset($link)) {
	$result = $SQLStat->lastErrorCode().": ".$SQLStat->lastErrorMsg(); }
if ($result=="") {
	return ""; }
	return $result; }
// Execute a query :P
if(!isset($NumQueriesArray['sqlite3'])) {
    $NumQueriesArray['sqlite3'] = 0; };
function sqlite3_func_query($query,$link=null) {
global $NumQueriesArray,$SQLStat;
if(isset($link)) {
	$result = $link->query($query); }
if(!isset($link)) {
	$result = $SQLStat->query($query); }
if ($result===false) {
    output_error("SQL Error: ".sqlite3_func_error(),E_USER_ERROR);
	return false; }
if ($result!==false) {
	++$NumQueriesArray['sqlite3'];
	return $result; } }
//Fetch Number of Rows
function sqlite3_func_num_rows($result) {
$num = 0;
$result->reset();
while ($result->fetchArray()) {
    $num++; }
$result->reset();
if ($num===false) {
    output_error("SQL Error: ".sqlite3_func_error(),E_USER_ERROR);
	return false; }
	return $num; }
// Connect to sqlite database
function sqlite3_func_connect_db($server,$username,$password,$database=null,$new_link=false) {
if($new_link!==true) { $new_link = false; }
if($database===null) {
return true; }
if($database!==null) {
$link = new SQLite3($database,SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE); }
if ($link===false) {
    output_error("Not connected: ".$sqliteerror,E_USER_ERROR);
	return false; }
return $link; }
function sqlite3_func_disconnect_db($link=null) {
if(isset($link)) {
	return $link->close(); }
if(!isset($link)) {
	return $SQLStat->close(); } }
// Query Results :P
function sqlite3_func_result($result,$row,$field=0) {
$check = true;
$num = 0;
$result->reset();
while ($num<$row) {
	$result->fetchArray();
    $num++; }
if ($check===false) {
    output_error("SQL Error: ".sqlite3_func_error(),E_USER_ERROR);
	return false; }
$trow = $result->fetchArray();
if(!isset($trow[$field])) { $trow[$field] = null; }
$retval = $trow[$field]; 
return $retval; }
// Free Results :P
function sqlite3_func_free_result($result) {
	return true; }
//Fetch Results to Array
function sqlite3_func_fetch_array($result,$result_type=SQLITE3_BOTH) {
$row = $result->fetchArray($result_type);
	return $row; }
//Fetch Results to Associative Array
function sqlite3_func_fetch_assoc($result) {
$row = $result->fetchArray(SQLITE3_ASSOC);
	return $row; }
//Fetch Row Results
function sqlite3_func_fetch_row($result) {
$row = $result->fetchArray(SQLITE3_NUM);
	return $row; }
//Get Server Info
function sqlite3_func_server_info($link=null) {
	$result = SQLite3::version()['versionString'];
	return $result; }
//Get Client Info
function sqlite3_func_client_info($link=null) {
	return null; }
function sqlite3_func_escape_string($string,$link=null) {
 if(isset($string)) {
 	$string = SQLite3::escapeString($string); }
if ($string===false) {
    output_error("SQL Error: ".sqlite3_func_error(),E_USER_ERROR);
	return false; }
	return $string; }
// SafeSQL Lite Source Code by Cool Dude 2k
// Make SQL Query's safe
function sqlite3_func_pre_query($query_string,$query_vars) {
   if($query_vars==null) { $query_vars = array(null); }
   $query_array = array(array("%i","%I","%F","%S"),array("%d","%d","%f","%s"));
   $query_string = str_replace($query_array[0], $query_array[1], $query_string);
   if (get_magic_quotes_gpc()) {
       $query_vars  = array_map("stripslashes", $query_vars); }
   $query_vars = array_map("sql_escape_string", $query_vars);
   $query_val = $query_vars;
$query_num = count($query_val);
$query_i = 0;
while ($query_i < $query_num) {
$query_is = $query_i+1;
$query_val[$query_is] = $query_vars[$query_i];
++$query_i; }
   $query_val[0] = $query_string;
   return call_user_func_array("sprintf",$query_val); }
function sqlite3_func_set_charset($charset,$link=null) {
	return true; }
/*
function sqlite3_func_set_charset($charset,$link=null) {
if(function_exists('mysql_set_charset')===false) {
if(!isset($link)) {
	$result = sqlite3_func_query("SET CHARACTER SET '".$charset."'"); }
if(isset($link)) {
	$result = sqlite3_func_query("SET CHARACTER SET '".$charset."'",$link); }
if ($result===false) {
    output_error("SQL Error: ".sqlite3_func_error(),E_USER_ERROR);
	return false; }
if(!isset($link)) {
	$result = sqlite3_func_query("SET NAMES '".$charset."'"); }
if(isset($link)) {
	$result = sqlite3_func_query("SET NAMES '".$charset."'",$link); } 
if ($result===false) {
    output_error("SQL Error: ".sqlite3_func_error(),E_USER_ERROR);
	return false; }
	return true; }
if(function_exists('mysql_set_charset')===true) {
if(isset($link)) {
	$result = mysql_set_charset($charset,$link); }
if(!isset($link)) {
	$result = mysql_set_charset($charset); }
if ($result===false) {
    output_error("SQL Error: ".sqlite3_func_error(),E_USER_ERROR);
	return false; }
	return true; }
if(function_exists('mysql_set_charset')===false) {
function mysql_set_charset($charset,$link) {
if(isset($link)) {
	$result = sqlite3_func_set_charset($charset,$link); }
if(!isset($link)) {
	$result = sqlite3_func_set_charset($charset); }
if ($result===false) {
    output_error("SQL Error: ".sqlite3_func_error(),E_USER_ERROR);
	return false; }
	return true; } }
*/
// Get next id for stuff
function sqlite3_func_get_next_id($tablepre,$table,$link=null) {
	if(isset($link)) {
		$nid = $link->lastInsertRowID(); }
	if(!isset($link)) {
		$nid = $SQLStat->lastInsertRowID(); }
	return $nid; }
// Get number of rows for table
function sqlite3_func_get_num_rows($tablepre,$table,$link=null) {
   $getnextidq = sqlite3_func_pre_query("SHOW TABLE STATUS LIKE '".$tablepre.$table."'", array());
if(!isset($link)) {
	$getnextidr = sqlite3_func_query($getnextidq); }
if(isset($link)) {
	$getnextidr = sqlite3_func_query($getnextidq,$link); } 
   $getnextid = sqlite3_func_fetch_assoc($getnextidr);
   return $getnextid['Rows'];
   @sql_free_result($getnextidr); }
?>
