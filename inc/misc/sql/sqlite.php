<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2023 iDB Support - https://idb.osdn.jp/support/category.php?act=view&id=1
    Copyright 2004-2023 Game Maker 2k - https://idb.osdn.jp/support/category.php?act=view&id=2

    $FileInfo: sqlite.php - Last Update: 6/22/2023 SVN 984 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="sqlite.php"||$File3Name=="/sqlite.php") {
	@header('Location: index.php');
	exit(); }
// SQLite Functions.
function sql_error($link=null) {
global $SQLStat;
if(isset($link)) {
	$result = sqlite_error_string(sqlite_last_error($link)); }
if(!isset($link)) {
	$result = sqlite_error_string(sqlite_last_error($SQLStat)); }
if ($result=="") {
	return ""; }
	return $result; }
function sql_errno($link=null) {
global $SQLStat;
if(isset($link)) {
	$result = sqlite_last_error($link); }
if(!isset($link)) {
	$result = sqlite_last_error($SQLStat); }
if ($result===0) {
	return 0; }
	return $result; }
function sql_errorno($link=null) {
global $SQLStat;
if(isset($link)) {
	$result = sqlite_last_error($link).": ".sqlite_error_string(sqlite_last_error($link)); }
if(!isset($link)) {
	$result = sqlite_last_error($SQLStat).": ".sqlite_error_string(sqlite_last_error($SQLStat)); }
if ($result=="") {
	return ""; }
	return $result; }
// Execute a query :P
$NumQueries = 0;
function sql_query($query,$link=null) {
global $NumQueries,$SQLStat;
if(isset($link)) {
	$result = sqlite_query($link,$query); }
if(!isset($link)) {
	$result = sqlite_query($SQLStat,$query); }
if ($result===false) {
    output_error("SQL Error: ".sql_error(),E_USER_ERROR);
	return false; }
if ($result!==false) {
	++$NumQueries;
	return $result; } }
//Fetch Number of Rows
function sql_num_rows($result) {
$num = sqlite_num_rows($result);
if ($num===false) {
    output_error("SQL Error: ".sql_error(),E_USER_ERROR);
	return false; }
	return $num; }
// Connect to sqlite database
function sql_connect_db($server,$username,$password,$database=null,$new_link=false) {
if($new_link!==true) { $new_link = false; }
if($database===null) {
return true; }
if($database!==null) {
$link = sqlite_open($database,0666,$sqliteerror); }
if ($link===false) {
    output_error("Not connected: ".$sqliteerror,E_USER_ERROR);
	return false; }
return $link; }
function sql_disconnect_db($link=null) {
return sqlite_close($link); }
// Query Results :P
function sql_result($result,$row,$field=0) {
$check = sqlite_seek($result,$row);
if ($check===false) {
    output_error("SQL Error: ".sql_error(),E_USER_ERROR);
	return false; }
$trow = sqlite_fetch_array($result);
if(!isset($trow[$field])) { $trow[$field] = null; }
$retval = $trow[$field]; 
return $retval; }
// Free Results :P
function sql_free_result($result) {
	return true; }
//Fetch Results to Array
function sql_fetch_array($result,$result_type=SQLITE_BOTH) {
$row = sqlite_fetch_array($result,$result_type);
	return $row; }
//Fetch Results to Associative Array
function sql_fetch_assoc($result) {
$row = sqlite_fetch_array($result,SQLITE_ASSOC);
	return $row; }
//Fetch Row Results
function sql_fetch_row($result) {
$row = sqlite_fetch_array($result,SQLITE_NUM);
	return $row; }
//Get Server Info
function sql_server_info($link=null) {
	$result = sqlite_libversion();
	return $result; }
//Get Client Info
function sql_client_info($link=null) {
	return null; }
function sql_escape_string($string,$link=null) {
 if(isset($string)) {
 	$string = sqlite_escape_string($string); }
if ($string===false) {
    output_error("SQL Error: ".sql_error(),E_USER_ERROR);
	return false; }
	return $string; }
// SafeSQL Lite Source Code by Cool Dude 2k
// Make SQL Query's safe
function sql_pre_query($query_string,$query_vars) {
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
function sql_set_charset($charset,$link=null) {
	return true; }
/*
function sql_set_charset($charset,$link=null) {
if(function_exists('mysql_set_charset')===false) {
if(!isset($link)) {
	$result = sql_query("SET CHARACTER SET '".$charset."'"); }
if(isset($link)) {
	$result = sql_query("SET CHARACTER SET '".$charset."'",$link); }
if ($result===false) {
    output_error("SQL Error: ".sql_error(),E_USER_ERROR);
	return false; }
if(!isset($link)) {
	$result = sql_query("SET NAMES '".$charset."'"); }
if(isset($link)) {
	$result = sql_query("SET NAMES '".$charset."'",$link); } 
if ($result===false) {
    output_error("SQL Error: ".sql_error(),E_USER_ERROR);
	return false; }
	return true; }
if(function_exists('mysql_set_charset')===true) {
if(isset($link)) {
	$result = mysql_set_charset($charset,$link); }
if(!isset($link)) {
	$result = mysql_set_charset($charset); }
if ($result===false) {
    output_error("SQL Error: ".sql_error(),E_USER_ERROR);
	return false; }
	return true; }
if(function_exists('mysql_set_charset')===false) {
function mysql_set_charset($charset,$link) {
if(isset($link)) {
	$result = sql_set_charset($charset,$link); }
if(!isset($link)) {
	$result = sql_set_charset($charset); }
if ($result===false) {
    output_error("SQL Error: ".sql_error(),E_USER_ERROR);
	return false; }
	return true; } }
*/
// Get next id for stuff
function sql_get_next_id($tablepre,$table,$link=null) {
	$nid = sqlite_last_insert_rowid($link);
	return $nid; }
// Get number of rows for table
function sql_get_num_rows($tablepre,$table,$link=null) {
   $getnextidq = sql_pre_query("SHOW TABLE STATUS LIKE '".$tablepre.$table."'", array());
if(!isset($link)) {
	$getnextidr = sql_query($getnextidq); }
if(isset($link)) {
	$getnextidr = sql_query($getnextidq,$link); } 
   $getnextid = sql_fetch_assoc($getnextidr);
   return $getnextid['Rows'];
   @sql_free_result($getnextidr); }
?>
