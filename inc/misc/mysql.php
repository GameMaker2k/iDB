<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2009 iDB Support - http://idb.berlios.de/
    Copyright 2004-2009 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: mysql.php - Last Update: 11/23/2009 SVN 359 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="mysql.php"||$File3Name=="/mysql.php") {
	@header('Location: index.php');
	exit(); }
// MySQL Functions.
function sql_error($link=null) {
if(isset($link)) {
	$result = mysql_error($link); }
if(!isset($link)) {
	$result = mysql_error(); }
if ($result=="") {
	return ""; }
	return $result; }
function sql_errno($link=null) {
if(isset($link)) {
	$result = mysql_errno($link); }
if(!isset($link)) {
	$result = mysql_errno(); }
if ($result===0) {
	return 0; }
	return $result; }
function sql_errorno($link=null) {
if(isset($link)) {
	$result = mysql_error($link);
	$resultno = mysql_errno($link); }
if(!isset($link)) {
	$result = mysql_error();
	$resultno = mysql_errno(); }
if ($result==""&&$result===0) {
	return ""; }
if ($result!=""&&$result!==0) {
	$result = $resultno.": ".$result; }
	return $result; }
// Execute a query :P
$NumQueries = 0;
function sql_query($query,$link=null) {
global $NumQueries;
if(isset($link)) {
	$result = mysql_query($query,$link); }
if(!isset($link)) {
	$result = mysql_query($query); }
if ($result===false) {
    trigger_error("Invalid query: ".sql_error(),E_USER_ERROR);
	return false; }
if ($result!==false) {
	++$NumQueries;
	return $result; } }
//Fetch Number of Rows
function sql_num_rows($result) {
$num = mysql_num_rows($result);
if ($num===false) {
    trigger_error("Invalid query: ".sql_error(),E_USER_ERROR);
	return false; }
	return $num; }
// Connect to mysql database
function sql_connect_db($server,$username,$password,$database,$new_link=false) {
if($new_link!==true) { $new_link = false; }
if($new_link!==true||$new_link===false) {
$link = mysql_connect($server,$username,$password); }
if($new_link===true) {
$link = mysql_connect($server,$username,$password,$new_link); }
if ($link===false) {
    trigger_error("Not connected: ".sql_error(),E_USER_ERROR);
	return false; }
$dlink = mysql_select_db($database,$link);
if ($dlink===false) {
    trigger_error("Can't use database ".$database.": ".sql_error(),E_USER_ERROR);
	return false; }
return $link; }
// Query Results :P
function sql_result($result,$row,$field=0) {
$value = mysql_result($result, $row, $field);
if ($value===false) { 
    trigger_error("Invalid query: ".sql_error(),E_USER_ERROR);
	return false; }
	return $value; }
// Free Results :P
function sql_free_result($result) {
$fresult = mysql_free_result($result);
if ($fresult===false) {
    trigger_error("Invalid query: ".sql_error(),E_USER_ERROR);
	return false; }
if ($fresult===true) {
	return true; } }
//Fetch Results to Array
function sql_fetch_array($result,$result_type=MYSQL_BOTH) {
$row = mysql_fetch_array($result,$result_type);
	return $row; }
//Fetch Results to Associative Array
function sql_fetch_assoc($result) {
$row = mysql_fetch_assoc($result);
	return $row; }
//Fetch Row Results
function sql_fetch_row($result) {
$row = mysql_fetch_row($result);
	return $row; }
//Fetch Row Results
function sql_server_info($link) {
if(isset($link)) {
	$result = mysql_get_server_info($link); }
if(!isset($link)) {
	$result = mysql_get_server_info(); }
	return $result; }
function sql_escape_string($string,$link=null) {
if(isset($link)) {
	$string = mysql_real_escape_string($string,$link); }
if(!isset($link)) {
	$string = mysql_real_escape_string($string); }
if ($string===false) {
    trigger_error("Invalid query: ".sql_error(),E_USER_ERROR);
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
if(function_exists('mysql_set_charset')===false) {
	$result = sql_query("SET CHARACTER SET '".$charset."'");
if ($result===false) {
    trigger_error("Invalid query: ".sql_error(),E_USER_ERROR);
	return false; }
	$result = sql_query("SET NAMES '".$charset."'"); 
if ($result===false) {
    trigger_error("Invalid query: ".sql_error(),E_USER_ERROR);
	return false; }
	return true; }
if(function_exists('mysql_set_charset')===true) {
if(isset($link)) {
	$result = mysql_set_charset($charset,$link); }
if(!isset($link)) {
	$result = mysql_set_charset($charset); }
if ($result===false) {
    trigger_error("Invalid query: ".sql_error(),E_USER_ERROR);
	return false; }
	return true; } }
/*
if(function_exists('mysql_set_charset')===false) {
function mysql_set_charset($charset,$link) {
if(isset($link)) {
	$result = sql_set_charset($charset,$link); }
if(!isset($link)) {
	$result = sql_set_charset($charset); }
if ($result===false) {
    trigger_error("Invalid query: ".sql_error(),E_USER_ERROR);
	return false; }
	return true; } }
*/
// Get next id for stuff
function sql_get_next_id($tablepre,$table) {
   $getnextidq = sql_pre_query("SHOW TABLE STATUS LIKE '".$tablepre.$table."'", array());
   $getnextidr = sql_query($getnextidq);
   $getnextid = sql_fetch_assoc($getnextidr);
   return $getnextid['Auto_increment'];
   @sql_free_result($getnextidr); }
// Get number of rows for table
function sql_get_num_rows($tablepre,$table) {
   $getnextidq = sql_pre_query("SHOW TABLE STATUS LIKE '".$tablepre.$table."'", array());
   $getnextidr = sql_query($getnextidq);
   $getnextid = sql_fetch_assoc($getnextidr);
   return $getnextid['Rows'];
   @sql_free_result($getnextidr); }
?>
