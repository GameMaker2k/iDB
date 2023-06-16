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

    $FileInfo: preinstall.php - Last Update: 6/16/2023 SVN 973 - Author: cooldude2k $
*/
//error_reporting(E_ALL ^ E_NOTICE);
/* Some ini setting changes uncomment if you need them. */
//ini_set('session.use_trans_sid', false);
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="preinstall.php"||$File3Name=="/preinstall.php") {
	header('Location: index.php');
	exit(); }

header("Cache-Control: private, no-cache, no-store, must-revalidate, pre-check=0, post-check=0, max-age=0");
header("Pragma: private, no-cache, no-store, must-revalidate, pre-check=0, post-check=0, max-age=0");
header("Date: ".$utccurtime->format("D, d M Y H:i:s")." GMT");
header("Last-Modified: ".$utccurtime->format("D, d M Y H:i:s")." GMT");
header("Expires: ".$utccurtime->format("D, d M Y H:i:s")." GMT");
output_reset_rewrite_vars();
if(!isset($Settings['send_pagesize'])) { $Settings['send_pagesize'] = "off"; }
if(!isset($Settings['fixbasedir'])) { $Settings['fixbasedir'] = null; }
if(!isset($Settings['fixcookiedir'])) { $Settings['fixcookiedir'] = null; }
if(!isset($Settings['rssurl'])) { $Settings['rssurl'] = null; }
if(!isset($Settings['showverinfo'])) { $Settings['showverinfo'] = null; }
if(!isset($Settings['sqltype'])) { $Settings['sqltype'] = null; }
if(!isset($Settings['fixredirectdir'])) { $Settings['fixredirectdir'] = null; }
if(!isset($Settings['file_ext'])) { $Settings['file_ext'] = null; }
if(!isset($Settings['html_level'])) { $Settings['html_level'] = null; }
if(!isset($Settings['GuestGroup'])) { $Settings['GuestGroup'] = null; }
if(!isset($Settings['enable_search'])) { $Settings['enable_search'] = null; }
if(!isset($Settings['qstr'])) { $Settings['qstr'] = null; }
if(!isset($_POST['SetupType'])) { $_POST['SetupType'] = "install"; }
if(!isset($_GET['debug'])) { $_GET['debug'] = null; }
$checklowview = false;
$dayconv = array("year" => 29030400, "month" => 2419200, "week" => 604800, "day" => 86400, "hour" => 3600, "minute" => 60, "second" => 1);
if(!isset($SettDir['inc'])) { $SettDir['inc'] = "inc/"; }
if(!isset($SettDir['misc'])) { $SettDir['misc'] = "inc/misc/"; }
if(!isset($SettDir['sql'])) { $SettDir['sql'] = "inc/misc/sql/"; }
if(!isset($SettDir['admin'])) { $SettDir['admin'] = "inc/admin/"; }
if(!isset($SettDir['sqldumper'])) { $SettDir['sqldumper'] = "inc/admin/sqldumper/"; }
if(!isset($SettDir['mod'])) { $SettDir['mod'] = "inc/mod/"; }
if(!isset($SettDir['mplayer'])) { $SettDir['mplayer'] = "inc/mplayer/"; }
if(!isset($SettDir['themes'])) { $SettDir['themes'] = "themes/"; }
if(!isset($_POST['License'])) { $_POST['License'] = null; }
if(isset($_POST['DatabaseType'])) { 
	$Settings['sqltype'] = $_POST['DatabaseType']; }
if(isset($Settings['sqltype'])) {
if($Settings['sqltype']!="mysql"&&
	$Settings['sqltype']!="mysqli"&&
	$Settings['sqltype']!="pgsql"&&
	$Settings['sqltype']!="sqlite"&&
	$Settings['sqltype']!="sqlite3"&&
	$Settings['sqltype']!="cubrid") {
	$Settings['sqltype'] = "mysql"; } }
$Settings['idb_time_format'] = "g:i A";
$iDBTheme = "iDB"; $AltiDBTheme = "Gray";
if(isset($Settings['usealtname'])&&$Settings['usealtname']=="yes") {
if(isset($iDBAltName['AltiDBTheme'])) { $AltiDBTheme = $iDBAltName['AltiDBTheme']; } 
$iDBTheme = $AltiDBTheme; }
if($iDBTheme!="iDB") {
if(file_exists($SettDir['themes'].$iDBTheme."/settings.php")) {
	require($SettDir['themes'].$iDBTheme."/settings.php"); } }
if($iDBTheme=="iDB") {
if(file_exists($SettDir['themes']."iDB/settings.php")) {
	require($SettDir['themes']."iDB/settings.php"); }
if(!file_exists($SettDir['themes']."iDB/settings.php")) {
	require($SettDir['themes']."Gray/settings.php"); } }
?>
