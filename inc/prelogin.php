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

    $FileInfo: prelogin.php - Last Update: 8/26/2024 SVN 1048 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="prelogin.php"||$File3Name=="/prelogin.php") {
	require('index.php');
	exit(); }
$_SESSION['CheckCookie']="done";
if(!isset($_COOKIE['UserID'])) { $_COOKIE['UserID'] = 0; }
if($_COOKIE['UserID']!=0&&$_COOKIE['UserID']!=null) {
$numlog2=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"Name\"='%s' AND \"UserPassword\"='%s' AND \"id\"=%i LIMIT 1", array($_COOKIE['MemberName'],$_COOKIE['SessPass'],$_COOKIE['UserID'])), $SQLStat);
$querylog2 = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"Name\"='%s' AND \"UserPassword\"='%s' AND \"id\"=%i LIMIT 1", array($_COOKIE['MemberName'],$_COOKIE['SessPass'],$_COOKIE['UserID']));
$resultlog2=sql_query($querylog2,$SQLStat); }
else { $numlog2 = 0; }
if($numlog2==1) {
$resultlog2_array = sql_fetch_assoc($resultlog2);
$YourIDAM=$resultlog2_array['id'];
$YourNameAM=$resultlog2_array['Name'];
$YourGroupAM=$resultlog2_array['GroupID'];
$YourGroupIDAM=$YourGroupAM;
$YourPassAM=$resultlog2_array['UserPassword'];
$gquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($YourGroupAM));
$gresult=sql_query($gquery,$SQLStat);
$gresult_array = sql_fetch_assoc($gresult);
$YourGroupAM=$gresult_array['Name'];
sql_free_result($gresult);
$BanError = null;
$YourTimeZoneAM=$resultlog2_array['TimeZone'];
$UseThemeAM=$resultlog2_array['UseTheme'];
//$YourDSTAM=$resultlog2_array['DST'];
$YourLastPostTime=$resultlog2_array['LastPostTime'];
$YourBanTime=$resultlog2_array['BanTime'];
sql_free_result($resultlog2);
$CGMTime = $utccurtime->getTimestamp();
if($YourBanTime!=0&&$YourBanTime!=null) {
if($YourBanTime>=$CGMTime) { $BanError = "yes"; } 
if($YourBanTime<0) { $BanError = "yes"; } }
$NewDay=$utccurtime->getTimestamp();
$NewIP=$_SERVER['REMOTE_ADDR'];
if($BanError!="yes") {
$queryup = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"LastActive\"=%i,\"IP\"='%s' WHERE \"id\"=%i", array($NewDay,$NewIP,$YourIDAM));
$_SESSION['Theme']=$UseThemeAM;
$_SESSION['MemberName']=$_COOKIE['MemberName'];
if(isset($_COOKIE['AnonymousLogin'])) { $_SESSION['AnonymousLogin'] = $_COOKIE['AnonymousLogin']; }
$_SESSION['UserID']=$YourIDAM;
$_SESSION['UserIP']=$_SERVER['REMOTE_ADDR'];
$_SESSION['UserTimeZone']=$YourTimeZoneAM;
$_SESSION['UserGroup']=$YourGroupAM;
$_SESSION['UserGroupID']=$YourGroupIDAM;
//$_SESSION['UserDST']=$YourDSTAM;
$_SESSION['UserPass']=$YourPassAM;
$_SESSION['LastPostTime'] = $YourLastPostTime;
$_SESSION['DBName']=$Settings['sqldb'];
if($cookieDomain==null) {
setcookie("MemberName", $YourNameAM, time() + (7 * 86400), $cbasedir);
setcookie("UserID", $YourIDAM, time() + (7 * 86400), $cbasedir);
setcookie("SessPass", $YourPassAM, time() + (7 * 86400), $cbasedir); }
if($cookieDomain!=null) {
if($cookieSecure===true) {
setcookie("MemberName", $YourNameAM, time() + (7 * 86400), $cbasedir, $cookieDomain, 1);
setcookie("UserID", $YourIDAM, time() + (7 * 86400), $cbasedir, $cookieDomain, 1);
setcookie("SessPass", $YourPassAM, time() + (7 * 86400), $cbasedir, $cookieDomain, 1); }
if($cookieSecure===false) {
setcookie("MemberName", $YourNameAM, time() + (7 * 86400), $cbasedir, $cookieDomain, 0);
setcookie("UserID", $YourIDAM, time() + (7 * 86400), $cbasedir, $cookieDomain, 0);
setcookie("SessPass", $YourPassAM, time() + (7 * 86400), $cbasedir, $cookieDomain, 0); } }
/*redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die();*/
} } if($numlog2<=0||$numlog2>1||$BanError=="yes") { session_unset();
if($cookieDomain==null) {
setcookie("MemberName", "", $utccurtime->getTimestamp() - 3600, $cbasedir);
setcookie("UserID", "", $utccurtime->getTimestamp() - 3600, $cbasedir);
setcookie("SessPass", "", $utccurtime->getTimestamp() - 3600, $cbasedir);
if(isset($_COOKIE['AnonymousLogin'])) {
setcookie("AnonymousLogin", "", $utccurtime->getTimestamp() - 3600, $cbasedir); }
setcookie(session_name(), "", $utccurtime->getTimestamp() - 3600, $cbasedir); }
if($cookieDomain!=null) {
if($cookieSecure===true) {
setcookie("MemberName", "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 1);
setcookie("UserID", "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 1);
setcookie("SessPass", "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 1);
if(isset($_COOKIE['AnonymousLogin'])) {
setcookie("AnonymousLogin", "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 1); }
setcookie(session_name(), "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 1); }
if($cookieSecure===false) {
setcookie("MemberName", "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 0);
setcookie("UserID", "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 0);
setcookie("SessPass", "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 0);
if(isset($_COOKIE['AnonymousLogin'])) {
setcookie("AnonymousLogin", "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 0); }
setcookie(session_name(), "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 0); } }
unset($_COOKIE[session_name()]);
$_SESSION = array(); //session_unset(); session_destroy();
$temp_user_ip = $_SERVER['REMOTE_ADDR'];
$exptime = $utccurtime->getTimestamp() - ini_get("session.gc_maxlifetime");
sql_query(sql_pre_query("DELETE FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" < %i OR ip_address='%s'", array($exptime,$temp_user_ip)),$SQLStat);
redirect("location",$rbasedir.url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],false)); sql_free_result($resultlog2); sql_free_result($gresult);
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
?>
