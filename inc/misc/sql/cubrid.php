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
if ($File3Name=="cubrid.php"||$File3Name=="/cubrid.php") {
	@header('Location: index.php');
	exit(); }
// CUBRID Functions.
function cubrid_func_error($link=null) {
 global $SQLStat;
	$result = cubrid_error_msg();
	return $result; }
function cubrid_func_errno($link=null) {
 global $SQLStat;
	$result = cubrid_error_code();
	return $result; }
function cubrid_func_errorno($link=null) {
 global $SQLStat;
	$result = cubrid_func_error();
	$resultno = cubrid_func_errno();
	$result = $resultno.": ".$result;
	return $result; }
// Execute a query :P
if(!isset($NumQueriesArray['cubrid'])) {
    $NumQueriesArray['cubrid'] = 0; }
function cubrid_func_query($query,$link=null) {
global $NumQueriesArray,$SQLStat;;
if(isset($link)) {
	$result = cubrid_query($query,$link); }
if(!isset($link)) {
	$result = cubrid_query($query,$SQLStat); }
if ($result===false) {
    output_error("SQL Error: ".cubrid_func_error(),E_USER_ERROR);
	return false; }
if ($result!==false) {
	++$NumQueriesArray['cubrid'];
	return $result; } }
//Fetch Number of Rows
function cubrid_func_num_rows($result) {
$num = cubrid_num_rows($result);
if ($num===false) {
    output_error("SQL Error: ".cubrid_func_error(),E_USER_ERROR);
	return false; }
	return $num; }
// Connect to mysql database
function cubrid_func_connect_db($server,$username,$password,$database=null,$new_link=false) {
if($new_link!==true) { $new_link = false; }
if($database===null) {
return true; }
if($database!==null) {
$myport = "30000";
$hostex = explode(":", $server);
if(isset($hostex[1])&&
	!is_numeric($hostex[1])) {
	$hostex[1] = $myport; }
if(isset($hostex[1])) { 
	$server = $hostex[0];
	$myport = $hostex[1]; }
$link = cubrid_connect($server,$myport,$database,$username,$password); 
cubrid_set_autocommit($link,CUBRID_AUTOCOMMIT_TRUE); }
if ($link===false) {
    output_error("Not connected: ".$sqliteerror,E_USER_ERROR);
	return false; }
return $link; }
function cubrid_func_disconnect_db($link=null) {
return cubrid_disconnect($link); }
// Query Results :P
function cubrid_func_result($result,$row,$field=0) {
if(isset($field)&&!is_numeric($field)) {
	$field = strtolower($field); }
$value = cubrid_result($result, $row, $field);
if ($value===false) { 
    output_error("SQL Error: ".cubrid_func_error(),E_USER_ERROR);
	return false; }
	return $value; }
// Free Results :P
function cubrid_func_free_result($result) {
$fresult = cubrid_free_result($result);
if ($fresult===false) {
    output_error("SQL Error: ".cubrid_func_error(),E_USER_ERROR);
	return false; }
$fresult = cubrid_close_request($result);
if ($fresult===false) {
    output_error("SQL Error: ".cubrid_func_error(),E_USER_ERROR);
	return false; }
if ($fresult===true) {
	return true; } }
//Fetch Results to Array
function cubrid_func_fetch_array($result,$result_type=CUBRID_BOTH) {
$row = cubrid_fetch_array($result,$result_type);
	return $row; }
//Fetch Results to Associative Array
function cubrid_func_fetch_assoc($result) {
$row = cubrid_fetch_assoc($result);
	return $row; }
//Fetch Row Results
function cubrid_func_fetch_row($result) {
$row = cubrid_fetch_row($result);
	return $row; }
//Get Server Info
function cubrid_func_server_info($link=null) {
if(isset($link)) {
	$result = cubrid_get_server_info($link); }
if(!isset($link)) {
	$result = cubrid_get_server_info(); }
	return $result; }
//Get Client Info
function cubrid_func_client_info($link=null) {
	$result = cubrid_get_client_info();
	return $result; }
function cubrid_func_escape_string($string,$link=null) {
if(isset($string)) {
 if(isset($link)) {
 	$string = cubrid_real_escape_string($string,$link); }
 if(!isset($link)) {
 	$string = cubrid_real_escape_string($string); } }
if ($string===false) {
    output_error("SQL Error: ".cubrid_func_error(),E_USER_ERROR);
	return false; }
	return $string; }
// SafeSQL Lite Source Code by Cool Dude 2k
// Make SQL Query's safe
function cubrid_func_pre_query($query_string,$query_vars) {
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
function cubrid_func_set_charset($charset,$link=null) {
	return true; }
/*
function cubrid_func_set_charset($charset,$link=null) {
if(function_exists('mysql_set_charset')===false) {
if(!isset($link)) {
	$result = cubrid_func_query("SET CHARACTER SET '".$charset."'"); }
if(isset($link)) {
	$result = cubrid_func_query("SET CHARACTER SET '".$charset."'",$link); }
if ($result===false) {
    output_error("SQL Error: ".cubrid_func_error(),E_USER_ERROR);
	return false; }
if(!isset($link)) {
	$result = cubrid_func_query("SET NAMES '".$charset."'"); }
if(isset($link)) {
	$result = cubrid_func_query("SET NAMES '".$charset."'",$link); } 
if ($result===false) {
    output_error("SQL Error: ".cubrid_func_error(),E_USER_ERROR);
	return false; }
	return true; }
if(function_exists('mysql_set_charset')===true) {
if(isset($link)) {
	$result = mysql_set_charset($charset,$link); }
if(!isset($link)) {
	$result = mysql_set_charset($charset); }
if ($result===false) {
    output_error("SQL Error: ".cubrid_func_error(),E_USER_ERROR);
	return false; }
	return true; }
if(function_exists('mysql_set_charset')===false) {
function mysql_set_charset($charset,$link) {
if(isset($link)) {
	$result = cubrid_func_set_charset($charset,$link); }
if(!isset($link)) {
	$result = cubrid_func_set_charset($charset); }
if ($result===false) {
    output_error("SQL Error: ".cubrid_func_error(),E_USER_ERROR);
	return false; }
	return true; } }
*/
// Get next id for stuff
function cubrid_func_get_next_id($tablepre,$table,$link=null) {
   $getnextidq = cubrid_func_pre_query("SELECT ".$tablepre.$table."_ai_id.current_value;", array());
if(!isset($link)) {
	$result = cubrid_func_query($getnextidq); }
if(isset($link)) {
	$getnextidr = cubrid_func_query($getnextidq,$link); } 
	return cubrid_func_result($getnextidr,0);
	cubrid_func_result($getnextidr); }
// Get number of rows for table
function cubrid_func_get_num_rows($tablepre,$table,$link=null) {
   $getnextidq = cubrid_func_pre_query("SHOW TABLE STATUS LIKE '".$tablepre.$table."'", array());
if(!isset($link)) {
	$getnextidr = cubrid_func_query($getnextidq); }
if(isset($link)) {
	$getnextidr = cubrid_func_query($getnextidq,$link); } 
   $getnextid = cubrid_func_fetch_assoc($getnextidr);
   return $getnextid['Rows'];
   @cubrid_func_result($getnextidr); }
?>
