<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2011 iDB Support - http://idb.berlios.de/
    Copyright 2004-2011 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: pgsql.php - Last Update: 12/07/2010 SVN 600 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="pgsql.php"||$File3Name=="/pgsql.php") {
	@header('Location: index.php');
	exit(); }
// MySQL Functions.
function sql_error($link=null) {
if(isset($link)) {
	$result = pg_last_error($link); }
if(!isset($link)) {
	$result = pg_last_error(); }
if ($result=="") {
	return ""; }
	return $result; }
function sql_errno($link=null) {
if(isset($link)) {
	$result = pg_last_error($link); }
if(!isset($link)) {
	$result = pg_last_error(); }
if ($result===0) {
	return 0; }
	return $result; }
function sql_errorno($link=null) {
if(isset($link)) {
	$result = pg_last_error($link); }
if(!isset($link)) {
	$result = pg_last_error(); }
if ($result=="") {
	return ""; }
	return $result; }
// Execute a query :P
$NumQueries = 0;
function sql_query($query,$link=null) {
global $NumQueries;
if(isset($link)) {
	$result = pg_query($link,$query); }
if(!isset($link)) {
	$result = pg_query(null,$query); }
if ($result===false) {
    output_error("SQL Error: ".sql_error(),E_USER_ERROR);
	return false; }
if ($result!==false) {
	++$NumQueries;
	return $result; } }
//Fetch Number of Rows
function sql_num_rows($result) {
$num = pg_num_rows($result);
if ($num===false) {
    output_error("SQL Error: ".sql_error(),E_USER_ERROR);
	return false; }
	return $num; }
// Connect to pgsql database
function sql_connect_db($server,$username,$password,$database=null,$new_link=false) {
if($new_link!==true) { $new_link = false; }
$pgport = "5432";
$hostex = explode(":", $server);
if(isset($hostex[1])&&
	!is_numeric($hostex[1])) {
	$hostex[1] = $pgport; }
if(isset($hostex[1])) { 
	$server = $hostex[0];
	$pgport = $hostex[1]; }
$pgstring = null;
if($database===null) {
$pgstring = "host=".$server." port=".$pgport." user=".$username." password=".$password; }
if($database!==null) {
$pgstring = "host=".$server." port=".$pgport." dbname=".$database." user=".$username." password=".$password; }
$link = pg_connect($pgstring);
if ($link===false) {
    output_error("Not connected: ".sql_error(),E_USER_ERROR);
	return false; }
return $link; }
// Query Results :P
function sql_result($result,$row,$field=0) {
if(is_numeric($field)) {
$value = pg_fetch_result($result, $row, $field); }
if(!is_numeric($field)) {
$value = pg_fetch_result($result, $row, "\"".$field."\""); }
if ($value===false) { 
    output_error("SQL Error: ".sql_error(),E_USER_ERROR);
	return false; }
	return $value; }
// Free Results :P
function sql_free_result($result) {
$fresult = pg_free_result($result);
if ($fresult===false) {
    output_error("SQL Error: ".sql_error(),E_USER_ERROR);
	return false; }
if ($fresult===true) {
	return true; } }
//Fetch Results to Array
function sql_fetch_array($result,$result_type=MYSQL_BOTH) {
$row = pg_fetch_array($result,$result_type);
	return $row; }
//Fetch Results to Associative Array
function sql_fetch_assoc($result) {
$row = pg_fetch_assoc($result);
	return $row; }
//Fetch Row Results
function sql_fetch_row($result) {
$row = pg_fetch_row($result);
	return $row; }
//Get Server Info
function sql_server_info($link=null) {
if(isset($link)) {
	$result = pg_version($link); }
if(!isset($link)) {
	$result = pg_version(); }
	return $result['server']; }
//Get Client Info
function sql_client_info($link=null) {
if(isset($link)) {
	$result = pg_version($link); }
if(!isset($link)) {
	$result = pg_version(); }
	return $result['client']; }
function sql_escape_string($string,$link=null) {
global $SQLStat;
if(!isset($link)) { $link = $SQLStat; }
if(isset($link)) {
	$string = pg_escape_string($link,$string); }
if(!isset($link)) {
	$string = pg_escape_string($SQLStat,$string); }
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
   $getnextidq = sql_pre_query("SELECT currval('".$tablepre.$table."_id_seq');", array());
if(!isset($link)) {
	$result = sql_query($getnextidq); }
if(isset($link)) {
	$getnextidr = sql_query($getnextidq,$link); } 
	return sql_result($getnextidr,0);
	sql_free_result($getnextidr); }
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
