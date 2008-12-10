<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2008 Cool Dude 2k - http://idb.berlios.de/
    Copyright 2004-2008 Game Maker 2k - http://intdb.sourceforge.net/

    $FileInfo: setcheck.php - Last Update: 12/10/2008 SVN 209 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="setcheck.php"||$File3Name=="/setcheck.php") {
	require('index.php');
	exit(); }
if(isset($Settings['showverinfo'])) {
	$Settings['showversioninfo'] = $Settings['showverinfo']; }
if(!isset($Settings['showverinfo'])&&!isset($Settings['showversioninfo'])) {
	$Settings['showversioninfo'] = "off"; }
if(!isset($_GET['debug'])) { $_GET['debug'] = false; }
if(!isset($GZipEncode)) { $GZipEncode = array("Type" => "none"); }
if(!is_array($GZipEncode)) { $GZipEncode = array("Type" => "none"); }
if(!isset($preact)) { $preact = null; }
if(!isset($Settings['hash_type'])) { $Settings['hash_type'] = null; }
if(!isset($Error)) { $Error = null; }
if(!isset($passright)) { $passright = null; }
if(!isset($Settings['enable_pathinfo'])) {
  $Settings['enable_pathinfo'] = "off";  }
if($Settings['enable_pathinfo']!="on"&&
	$Settings['enable_pathinfo']!="off") {
  $Settings['enable_pathinfo'] = "off";  }
$Settings['sessionid_in_urls'] = "off";
if(!isset($Settings['sessionid_in_urls'])) {
  $Settings['sessionid_in_urls'] = "off";  }
if($Settings['sessionid_in_urls']!="on"&&
	$Settings['sessionid_in_urls']!="off") {
  $Settings['sessionid_in_urls'] = "off";  }
if(!isset($Settings['use_captcha'])) {
	$Settings['use_captcha'] = "on"; }
if(isset($Settings['use_captcha'])) {
	if($Settings['use_captcha']!="on"&&
		$Settings['use_captcha']!="off") {
	$Settings['use_captcha'] = "on"; } }
if(!isset($Settings['captcha_guest'])) {
	$Settings['captcha_guest'] = "on"; }
if(isset($Settings['captcha_guest'])) {
	if($Settings['captcha_guest']!="on"&&
		$Settings['captcha_guest']!="off") {
	$Settings['captcha_guest'] = "on"; } }
if(!isset($Settings['captcha_clean'])) {
	$Settings['captcha_clean'] = "off"; }
if(isset($Settings['captcha_clean'])) {
	if($Settings['captcha_clean']!="on"&&
		$Settings['captcha_clean']!="off") {
	$Settings['captcha_clean'] = "on"; } }
$sidurls = $Settings['sessionid_in_urls'];
if(!isset($Settings['board_offline'])) {
  $Settings['board_offline'] = "off";  }
if($Settings['board_offline']!="on"&&
	$Settings['board_offline']!="off") {
  $Settings['board_offline'] = "off";  }
$oldusername = null; $oldtopicname = null; $ext = null;
if($Settings['DefaultTheme']==null) {
	$Settings['DefaultTheme'] = "iDB"; }
if($Settings['DefaultTimeZone']==null) {
	$Settings['DefaultTimeZone'] = SeverOffSet(null); }
if($Settings['DefaultDST']!="on"&&
	$Settings['DefaultDST']!="off") { 
	$Settings['DefaultDST'] = "off"; }
if(!isset($Settings['use_captcha'])) {
	$Settings['use_captcha'] = "off"; }
if($Settings['use_captcha']!="on"&&
	$Settings['use_captcha']!="off") { 
	$Settings['use_captcha'] = "off"; }
if(!isset($Settings['captcha_clean'])) {
	$Settings['captcha_clean'] = "off"; }
if($Settings['captcha_clean']!="on"&&
	$Settings['captcha_clean']!="off") { 
	$Settings['captcha_clean'] = "off"; }
if($Settings['enable_rss']!="on"&&
	$Settings['enable_rss']!="on") { 
	$Settings['enable_rss'] = "off"; }
if($Settings['enable_rss']=="on") { 
    $Settings['enable_rss'] = "on"; }
if (file_exists("themes/iDB/settings.php")) {
	$FallBack['DefaultTheme'] = "iDB"; }
if (!file_exists("themes/iDB/settings.php")) {
	$FallBack['DefaultTheme'] = "Gray"; }
if($Settings['DefaultTheme']!=null) {
if (file_exists("themes/".$Settings['DefaultTheme']."/settings.php")) {
/* The file Skin Exists */ }
else { $Settings['DefaultTheme']=$FallBack['DefaultTheme'];
/* The file Skin Dose Not Exists */ } }
if($Settings['TestReferer']!="on"&&
	$Settings['TestReferer']!="off") {
	$Settings['TestReferer'] = "off"; }
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
@qstring($Settings['qstr'],$Settings['qsep']);
if(!isset($_GET['page'])) { $_GET['page'] = null; }
if(!isset($_GET['act'])) { $_GET['act'] = null; }
if(!isset($_POST['act'])) { $_POST['act'] = null; }
if(!isset($_GET['id'])) { $_GET['id'] = null; } 
if(!isset($_GET['debug'])) { $_GET['debug'] = "false"; }
if(!isset($_GET['post'])) { $_GET['post'] = null; }
if(!isset($_POST['License'])) { $_POST['License'] = null; } }
if(!isset($Settings['enable_https'])) {
  $Settings['enable_https'] = "off";  }
if($Settings['enable_https']!="on"&&
	$Settings['enable_https']!="off") {
  $Settings['enable_https'] = "off";  }
if(!isset($Settings['file_ext'])||
	$Settings['file_ext']==null) {
	$Settings['file_ext'] = ".php"; }
if(!isset($Settings['rss_ext'])||
	$Settings['rss_ext']==null) {
	$Settings['rss_ext'] = ".php"; }
if(!isset($Settings['js_ext'])||
	$Settings['js_ext']==null) {
	$Settings['js_ext'] = ".js"; }
if(!isset($Settings['add_power_by'])) {
  $Settings['add_power_by'] = "off";  }
if($Settings['add_power_by']=="on") {
$idbpowertitle = " (Powered by ".$iDB.")";
$itbpowertitle = " (Powered by ".$iTB.")"; }
if($Settings['add_power_by']!="on") {
$idbpowertitle = null;
$itbpowertitle = null; }
if($Settings['GuestGroup']==null) {
	$Settings['GuestGroup'] = "Guest"; }
if($Settings['MemberGroup']==null) {
	$Settings['MemberGroup'] = "Member"; }
if($Settings['ValidateGroup']==null&&
	$Settings['AdminValidate']=="on") {
$Settings['ValidateGroup'] = "Validate"; }
if($Settings['html_type']=="html4") { 
	$Settings['html_type'] = "html10"; }
/*if($_GET['debug']!="off"||$_GET['debug']=="on") {
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