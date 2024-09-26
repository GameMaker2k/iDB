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

    $FileInfo: preindex.php - Last Update: 8/23/2024 SVN 1023 - Author: cooldude2k $
*/
$pretime = explode(" ", microtime());
$utime = $pretime[0];
$time = $pretime[1];
$starttime = $utime + $time;
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="preindex.php"||$File3Name=="/preindex.php") {
	header('Location: index.php');
	exit(); }
require('sql.php');
if($_GET['act']=="sqldumper"&&$Settings['sqltype']=="cubrid") { $_GET['act'] = "view"; }
if($_GET['act']=="sqldumper"&&$_SESSION['UserGroup']!=$Settings['GuestGroup']&&
	$GroupInfo['HasAdminCP']=="yes") { 
	if($Settings['sqltype']=="mysql"||
		$Settings['sqltype']=="mysqli"||
		$Settings['sqltype']=="mysqli_prepare"||
		$Settings['sqltype']=="pdo_mysql") {
	require($SettDir['sqldumper'].'mysql.php'); }
	if($Settings['sqltype']=="pgsql"||
		$Settings['sqltype']=="pgsql_prepare"||
		$Settings['sqltype']=="pdo_pgsql") {
	require($SettDir['sqldumper'].'pgsql.php'); } 
	if($Settings['sqltype']=="sqlite"||
		$Settings['sqltype']=="sqlite3"||
		$Settings['sqltype']=="sqlite3_prepare"||
		$Settings['sqltype']=="pdo_sqlite3") {
	require($SettDir['sqldumper'].'sqlite.php'); } 
	if($Settings['sqltype']=="cubrid"||
		$Settings['sqltype']=="cubrid_prepare"||
		$Settings['sqltype']=="pdo_cubrid") {
	require($SettDir['sqldumper'].'cubrid.php'); }
	if($Settings['sqltype']=="sqlsrv"||
		$Settings['sqltype']=="sqlsrv_prepare") {
	require($SettDir['sqldumper'].'cubrid.php'); }}
if(!isset($checklowview)) {
	$checklowview = false; }
if($checklowview!==false&&$checklowview!==true) {
	$checklowview = false; }
if($_GET['act']=="lofi"||$_GET['act']=="lo-fi"||
	$_GET['act']=="LoFi"||$_GET['act']=="Lo-Fi"||
	$_GET['act']=="lores"||$_GET['act']=="lo-res"||
	$_GET['act']=="LoRes"||$_GET['act']=="Lo-Res"||
	$_GET['act']=="LowView"||$_GET['act']=="low-view"||
	$_GET['act']=="Low-View") { $_GET['act'] = "lowview"; }
if($_GET['act']!="lowview") { 
	$checklowview = false; }
if($Settings['enable_rss']=="on") {
if(!isset($_GET['feed'])) { $_GET['feed'] = null; }
if($_GET['feed']=="rss"||$_GET['act']=="feed"||
	$_GET['feed']=="oldrss"||$_GET['feed']=="atom"||
	$_GET['feed']=="opml"||$_GET['feed']=="opensearch") {
	$_GET['feedtype'] = $_GET['feed']; }
if($_GET['feed']=="rss"||$_GET['act']=="Feed"||
	$_GET['feed']=="oldrss"||$_GET['feed']=="atom"||
	$_GET['feed']=="opml"||$_GET['feed']=="opensearch"||
	$_GET['act']=="feed") {
	require($SettDir['inc'].'rssfeed.php'); } }
if($Settings['output_type']=="htm") {
	$Settings['output_type'] = "html"; }
if($Settings['output_type']=="xhtm") {
	$Settings['output_type'] = "xhtml"; }
if($Settings['output_type']=="xml+htm") {
	$Settings['output_type'] = "xhtml"; }
if($Settings['html_type']=="html5"||
	$Settings['html_type']=="xhtml5"||
	($Settings['html_type']!="html5"&&
	 $Settings['html_type']!="xhtml5")) {
require($SettDir['inc'].'html5.php'); }
?>
