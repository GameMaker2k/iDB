<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2007 Cool Dude 2k - http://intdb.sourceforge.net/
    Copyright 2004-2007 Game Maker 2k - http://upload.idb.s1.jcink.com/

    $FileInfo: setcheck.php - Last Update: 06/06/2007 SVN 19 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="setcheck.php"||$File3Name=="/setcheck.php") {
	require('index.php');
	exit(); }
if(!isset($_GET['debug'])) { $_GET['debug'] = false; }
if(!isset($GZipEncode)) { $GZipEncode = 'none'; }
if(!isset($preact)) { $preact = null; }
if(!isset($Settings['hash_type'])) { $Settings['hash_type'] = null; }
if(!isset($Error)) { $Error = null; }
if(!isset($passright)) { $passright = null; }
$oldusername = null; $oldtopicname = null; $ext = null;
if($Settings['DefaultTheme']==null) {
	$Settings['DefaultTheme'] = "iDB"; }
if($Settings['DefaultTimeZone']==null) {
	$Settings['DefaultTimeZone'] = SeverOffSet(null); }
if(!is_numeric($Settings['DefaultTimeZone'])) {
	$Settings['DefaultTimeZone'] = SeverOffSet(null); }
if($Settings['DefaultDST']!="on"&&
	$Settings['DefaultDST']!="off") { 
	$Settings['DefaultDST'] = "off"; }
if($Settings['enable_rss']!="on"&&
	$Settings['enable_rss']!=true) { 
	$Settings['enable_rss'] = false; }
if($Settings['enable_rss']=="on") { 
    $Settings['enable_rss'] = true; }
if (file_exists("themes/iDB/settings.php")) {
	$FallBack['DefaultTheme'] = "iDB"; }
if (!file_exists("themes/iDB/settings.php")) {
	$FallBack['DefaultTheme'] = "Gray"; }
if($Settings['DefaultTheme']!=null) {
if (file_exists("themes/".$Settings['DefaultTheme']."/settings.php")) {
/* The file Skin Exists */ }
else { $Settings['DefaultTheme']=$FallBack['DefaultTheme'];
/* The file Skin Dose Not Exists */ } }
if($Settings['TestReferer']!=true&&
	$Settings['TestReferer']!=false) {
	$Settings['TestReferer'] = false; }
if($Settings['charset']==null) {
	$Settings['charset'] = "iso-8859-15"; }
if($Settings['qstr']==null) {
	$Settings['qstr'] = "&"; }
if($Settings['qsep']==null) {
	$Settings['qsep'] = "="; }
if($Settings['qsep']=="#"||
	$Settings['qstr']=="#") {
	$Settings['qstr'] = "&";
	$Settings['qsep'] = "="; }
if($Settings['qsep']==$Settings['qstr']) {
	$Settings['qstr'] = "&";
	$Settings['qsep'] = "="; }
if($Settings['qstr']=="/"||
	$Settings['qstr']=="&") {
	$Settings['qsep'] = "="; }
if($Settings['qstr']!="&"&&
	$Settings['qstr']!="/") {
@qstring($Settings['qstr'],$Settings['qsep']); }
if($Settings['file_ext']==null) {
	$Settings['file_ext'] = ".php"; }
if($Settings['rss_ext']==null) {
	$Settings['rss_ext'] = ".php"; }
if($Settings['js_ext']==null) {
	$Settings['js_ext'] = ".js"; }
if($Settings['add_power_by']==true) {
$idbpowertitle = " (Powered by ".$iDB.")";
$itbpowertitle = " (Powered by ".$iTB.")"; }
if($Settings['add_power_by']!=true) {
$idbpowertitle = null;
$itbpowertitle = null; }
if($Settings['GuestGroup']==null) {
	$Settings['GuestGroup'] = "Guest"; }
if($Settings['MemberGroup']==null) {
	$Settings['MemberGroup'] = "Member"; }
if($Settings['ValidateGroup']==null&&
	$Settings['AdminValidate']==true) {
$Settings['ValidateGroup'] = "Validate"; }
if($Settings['fixpathinfo']==null) {
	$Settings['fixpathinfo'] = false; }
if($Settings['fixbasedir']==null) {
	$Settings['fixbasedir'] = false; }
if($Settings['html_type']=="html4") { 
	$Settings['html_type'] = "html10"; }
/*if($_GET['debug']=="true"||$_GET['debug']=="on") {
	output_add_rewrite_var("amp;debug",$_GET['debug']); }*/
if (!isset($_GET['action'])) { $_GET['action'] = null; }
if (!isset($_GET['activity'])) { $_GET['activity'] = null; }
if (!isset($_GET['function'])) { $_GET['function'] = null; }
if (!isset($_GET['mode'])) { $_GET['mode'] = null; }
if (!isset($_GET['show'])) { $_GET['show'] = null; }
if (!isset($_GET['do'])) { $_GET['do'] = null; }
if ($_GET['act']==null&&$_GET['action']!=null) { $_GET['act']=$_GET['action']; }
if ($_GET['act']==null&&$_GET['activity']!=null) { $_GET['act']=$_GET['activity']; }
if ($_GET['act']==null&&$_GET['function']!=null) { $_GET['act']=$_GET['function']; }
if ($_GET['act']==null&&$_GET['mode']!=null) { $_GET['act']=$_GET['mode']; }
if ($_GET['act']==null&&$_GET['show']!=null) { $_GET['act']=$_GET['show']; }
if ($_GET['act']==null&&$_GET['do']!=null) { $_GET['act']=$_GET['do']; }
if ($_GET['act']=="idx"||$_GET['act']=="View") { $_GET['act']="view"; }
if ($_GET['act']=="iDBInfo") { @header('Location: http://sourceforge.net/projects/intdb/'); }
if ($_GET['act']=="iDBSite") { @header('Location: http://intdb.sourceforge.net/'); }
if ($_GET['act']=="OldiDBInfo") { @header('Location: http://developer.berlios.de/projects/idb/'); }
if ($_GET['act']=="OldiDBSite") { @header('Location: http://idb.berlios.de/'); }
if ($_GET['act']=="DF2kInfo") { @header('Location: http://developer.berlios.de/projects/df2k/'); }
if ($_GET['act']=="DF2kSite") { @header('Location: http://df2k.berlios.de/'); }
if ($_GET['act']=="GM2kSite") { @header('Location: http://upload.idb.s1.jcink.com/'); }
?>