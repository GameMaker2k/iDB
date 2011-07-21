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

    $FileInfo: preinstall.php - Last Update: 07/21/2011 SVN 725 - Author: cooldude2k $
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
header("Date: ".gmdate("D, d M Y H:i:s")." GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Expires: ".gmdate("D, d M Y H:i:s")." GMT");
output_reset_rewrite_vars();
if(!isset($SettDir['inc'])) { $SettDir['inc'] = "inc/"; }
if(!isset($SettDir['misc'])) { $SettDir['misc'] = "inc/misc/"; }
if(!isset($SettDir['sql'])) { $SettDir['sql'] = "inc/misc/sql/"; }
if(!isset($SettDir['admin'])) { $SettDir['admin'] = "inc/admin/"; }
if(!isset($SettDir['sqldumper'])) { $SettDir['sqldumper'] = "inc/admin/sqldumper/"; }
if(!isset($SettDir['mod'])) { $SettDir['mod'] = "inc/mod/"; }
if(!isset($SettDir['themes'])) { $SettDir['themes'] = "themes/"; }
if(!isset($_POST['License'])) { $_POST['License'] = null; }
if(isset($_POST['DatabaseType'])) { 
	$Settings['sqltype'] = $_POST['DatabaseType']; }
if(isset($Settings['sqltype'])) {
if($Settings['sqltype']!="mysql"&&
	$Settings['sqltype']!="mysqli"&&
	$Settings['sqltype']!="pgsql"&&
	$Settings['sqltype']!="sqlite"&&
	$Settings['sqltype']!="cubrid") {
	$Settings['sqltype'] = "mysql"; } }
if($iDBTheme!="iDB") {
if(file_exists($SettDir['themes'].$iDBTheme."/settings.php")) {
	require($SettDir['themes'].$iDBTheme."/settings.php"); } }
if($iDBTheme=="iDB") {
if(file_exists($SettDir['themes']."iDB/settings.php")) {
	require($SettDir['themes']."iDB/settings.php"); }
if(!file_exists($SettDir['themes']."iDB/settings.php")) {
	require($SettDir['themes']."Gray/settings.php"); } }
?>