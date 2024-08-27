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

    $FileInfo: pdo_mysql.php - Last Update: 8/26/2024 SVN 1035 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="pdo_mysql.php"||$File3Name=="/pdo_mysql.php") {
	@header('Location: index.php');
	exit(); }
// MySQL Functions.
function pdo_mysql_func_error($link=null) {
if(isset($link)) {
	$result = mysql_error($link); }
if(!isset($link)) {
	$result = mysql_error(); }
if ($result=="") {
	return ""; }
	return $result; }
function pdo_mysql_func_errno($link=null) {
if(isset($link)) {
	$result = mysql_errno($link); }
if(!isset($link)) {
	$result = mysql_errno(); }
if ($result===0) {
	return 0; }
	return $result; }
function pdo_mysql_func_errorno($link=null) {
if(isset($link)) {
	$result = pdo_mysql_func_error($link);
	$resultno = pdo_mysql_func_errno($link); }
if(!isset($link)) {
	$result = pdo_mysql_func_error();
	$resultno = pdo_mysql_func_errno(); }
if ($result==""&&$result===0) {
	return ""; }
if ($result!=""&&$result!==0) {
	$result = $resultno.": ".$result; }
	return $result; }
// Execute a query :P
$NumQueries = 0;
function pdo_mysql_func_query($query,$link=null) {
global $NumQueries;
if(isset($link)) {
	$result = mysql_query($query,$link); }
if(!isset($link)) {
	$result = mysql_query($query); }
if ($result===false) {
    output_error("SQL Error: ".pdo_mysql_func_error(),E_USER_ERROR);
	return false; }
if ($result!==false) {
	++$NumQueries;
	return $result; } }
//Fetch Number of Rows
function pdo_mysql_func_num_rows($result) {
$num = mysql_num_rows($result);
if ($num===false) {
    output_error("SQL Error: ".pdo_mysql_func_error(),E_USER_ERROR);
	return false; }
	return $num; }
// Connect to mysql database
function pdo_mysql_func_connect_db($server,$username,$password,$database=null,$new_link=false) {
if($new_link!==true) { $new_link = false; }
if($new_link!==true||$new_link===false) {
$link = mysql_connect($server,$username,$password); }
if($new_link===true) {
$link = mysql_connect($server,$username,$password,$new_link); }
if ($link===false) {
    output_error("Not connected: ".pdo_mysql_func_error(),E_USER_ERROR);
	return false; }
if($database!==null) {
$dlink = mysql_select_db($database,$link);
if ($dlink===false) {
    output_error("Can't use database ".$database.": ".pdo_mysql_func_error(),E_USER_ERROR);
	return false; } }
$result = pdo_mysql_func_query("SET SESSION SQL_MODE='ANSI_QUOTES,NO_AUTO_VALUE_ON_ZERO';",$link);
if ($result===false) {
    output_error("SQL Error: ".pdo_mysql_func_error(),E_USER_ERROR);
	return false; }
return $link; }
function pdo_mysql_func_disconnect_db($link=null) {
return mysql_close($link); }
// Query Results :P
function pdo_mysql_func_result($result,$row,$field=0) {
$value = mysql_result($result, $row, $field);
if ($value===false) { 
    output_error("SQL Error: ".pdo_mysql_func_error(),E_USER_ERROR);
	return false; }
	return $value; }
// Free Results :P
function pdo_mysql_func_free_result($result) {
$fresult = mysql_free_result($result);
if ($fresult===false) {
    output_error("SQL Error: ".pdo_mysql_func_error(),E_USER_ERROR);
	return false; }
if ($fresult===true) {
	return true; } }
//Fetch Results to Array
function pdo_mysql_func_fetch_array($result,$result_type=MYSQL_BOTH) {
$row = mysql_fetch_array($result,$result_type);
	return $row; }
//Fetch Results to Associative Array
function pdo_mysql_func_fetch_assoc($result) {
$row = mysql_fetch_assoc($result);
	return $row; }
//Fetch Row Results
function pdo_mysql_func_fetch_row($result) {
$row = mysql_fetch_row($result);
	return $row; }
//Get Server Info
function pdo_mysql_func_server_info($link=null) {
if(isset($link)) {
	$result = mysql_get_server_info($link); }
if(!isset($link)) {
	$result = mysql_get_server_info(); }
	return $result; }
//Get Client Info
function pdo_mysql_func_client_info($link=null) {
	$result = mysql_get_client_info();
	return $result; }
function pdo_mysql_func_escape_string($string,$link=null) {
if(isset($string)) {
 if(isset($link)) {
 	$string = mysql_real_escape_string($string,$link); }
 if(!isset($link)) {
 	$string = mysql_real_escape_string($string); } }
if ($string===false) {
    output_error("SQL Error: ".pdo_mysql_func_error(),E_USER_ERROR);
	return false; }
	return $string; }
// SafeSQL Lite Source Code by Cool Dude 2k
// Make SQL Query's safe
function pdo_mysql_func_pre_query($query_string,$query_vars) {
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
function pdo_mysql_func_set_charset($charset,$link=null) {
if(function_exists('mysql_set_charset')===false) {
if(!isset($link)) {
	$result = pdo_mysql_func_query("SET CHARACTER SET '".$charset."'"); }
if(isset($link)) {
	$result = pdo_mysql_func_query("SET CHARACTER SET '".$charset."'",$link); }
if ($result===false) {
    output_error("SQL Error: ".pdo_mysql_func_error(),E_USER_ERROR);
	return false; }
if(!isset($link)) {
	$result = pdo_mysql_func_query("SET NAMES '".$charset."'"); }
if(isset($link)) {
	$result = pdo_mysql_func_query("SET NAMES '".$charset."'",$link); } 
if ($result===false) {
    output_error("SQL Error: ".pdo_mysql_func_error(),E_USER_ERROR);
	return false; }
	return true; }
if(function_exists('mysql_set_charset')===true) {
if(isset($link)) {
	$result = mysql_set_charset($charset,$link); }
if(!isset($link)) {
	$result = mysql_set_charset($charset); }
if ($result===false) {
    output_error("SQL Error: ".pdo_mysql_func_error(),E_USER_ERROR);
	return false; }
	return true; } }
/*
if(function_exists('mysql_set_charset')===false) {
function mysql_set_charset($charset,$link) {
if(isset($link)) {
	$result = pdo_mysql_func_set_charset($charset,$link); }
if(!isset($link)) {
	$result = pdo_mysql_func_set_charset($charset); }
if ($result===false) {
    output_error("SQL Error: ".pdo_mysql_func_error(),E_USER_ERROR);
	return false; }
	return true; } }
*/
// Get next id for stuff
function pdo_mysql_func_get_next_id($tablepre,$table,$link=null) {
	$nid = mysql_insert_id($link);
	return $nid; }
// Get number of rows for table
function pdo_mysql_func_get_num_rows($tablepre,$table,$link=null) {
   $getnextidq = pdo_mysql_func_pre_query("SHOW TABLE STATUS LIKE '".$tablepre.$table."'", array());
if(!isset($link)) {
	$getnextidr = pdo_mysql_func_query($getnextidq); }
if(isset($link)) {
	$getnextidr = pdo_mysql_func_query($getnextidq,$link); } 
   $getnextid = pdo_mysql_func_fetch_assoc($getnextidr);
   return $getnextid['Rows'];
   @sql_free_result($getnextidr); }
?>
