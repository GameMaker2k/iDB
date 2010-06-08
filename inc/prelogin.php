<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2010 iDB Support - http://idb.berlios.de/
    Copyright 2004-2010 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: prelogin.php - Last Update: 06/07/2010 SVN 520 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="prelogin.php"||$File3Name=="/prelogin.php") {
	require('index.php');
	exit(); }
$_SESSION['CheckCookie']="done";
$querylog2 = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"Name\"='%s' AND \"UserPassword\"='%s' AND \"id\"=%i LIMIT 1", array($_COOKIE['MemberName'],$_COOKIE['SessPass'],$_COOKIE['UserID']));
$resultlog2=sql_query($querylog2,$SQLStat);
$numlog2=sql_num_rows($resultlog2);
if($numlog2==1) {
$YourIDAM=sql_result($resultlog2,0,"id");
$YourNameAM=sql_result($resultlog2,0,"Name");
$YourGroupAM=sql_result($resultlog2,0,"GroupID");
$YourGroupIDAM=$YourGroupAM;
$YourPassAM=sql_result($resultlog2,0,"UserPassword");
$gquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($YourGroupAM));
$gresult=sql_query($gquery,$SQLStat);
$YourGroupAM=sql_result($gresult,0,"Name");
sql_free_result($gresult);
$BanError = null;
$YourTimeZoneAM=sql_result($resultlog2,0,"TimeZone");
$UseThemeAM=sql_result($resultlog2,0,"UseTheme");
$YourDSTAM=sql_result($resultlog2,0,"DST");
$YourLastPostTime=sql_result($resultlog2,0,"LastPostTime");
$YourBanTime=sql_result($resultlog2,0,"BanTime");
sql_free_result($resultlog2);
$CGMTime = GMTimeStamp();
if($YourBanTime!=0&&$YourBanTime!=null) {
if($YourBanTime>=$CGMTime) { $BanError = "yes"; } 
if($YourBanTime<0) { $BanError = "yes"; } }
$NewDay=GMTimeStamp();
$NewIP=$_SERVER['REMOTE_ADDR'];
if($BanError!="yes") {
$queryup = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"LastActive\"=%i,\"IP\"='%s' WHERE \"id\"=%i", array($NewDay,$NewIP,$YourIDAM));
$_SESSION['Theme']=$UseThemeAM;
$_SESSION['MemberName']=$_COOKIE['MemberName'];
$_SESSION['UserID']=$YourIDAM;
$_SESSION['UserIP']=$_SERVER['REMOTE_ADDR'];
$_SESSION['UserTimeZone']=$YourTimeZoneAM;
$_SESSION['UserGroup']=$YourGroupAM;
$_SESSION['UserGroupID']=$YourGroupIDAM;
$_SESSION['UserDST']=$YourDSTAM;
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
setcookie("MemberName", $YourNameAM, time() + (7 * 86400), $cbasedir, $cookieDomain);
setcookie("UserID", $YourIDAM, time() + (7 * 86400), $cbasedir, $cookieDomain);
setcookie("SessPass", $YourPassAM, time() + (7 * 86400), $cbasedir, $cookieDomain); } }
} } if($numlog2<=0||$numlog2>1||$BanError=="yes") { session_unset();
if($cookieDomain==null) {
setcookie("MemberName", null, GMTimeStamp() - 3600, $cbasedir);
setcookie("UserID", null, GMTimeStamp() - 3600, $cbasedir);
setcookie("SessPass", null, GMTimeStamp() - 3600, $cbasedir);
setcookie(session_name(), "", GMTimeStamp() - 3600, $cbasedir); }
if($cookieDomain!=null) {
if($cookieSecure===true) {
setcookie("MemberName", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1);
setcookie("UserID", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1);
setcookie("SessPass", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1);
setcookie(session_name(), "", GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1); }
if($cookieSecure===false) {
setcookie("MemberName", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain);
setcookie("UserID", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain);
setcookie("SessPass", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain);
setcookie(session_name(), "", GMTimeStamp() - 3600, $cbasedir, $cookieDomain); } }
unset($_COOKIE[session_name()]);
$_SESSION = array(); //session_unset(); session_destroy();
$temp_user_ip = $_SERVER['REMOTE_ADDR'];
$exptime = GMTimeStamp() - ini_get("session.gc_maxlifetime");
sql_query(sql_pre_query("DELETE FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" < %i OR ip_address='%s'", array($exptime,$temp_user_ip)),$SQLStat);
redirect("location",$rbasedir.url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],false)); sql_free_result($resultlog2); sql_free_result($gresult);
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
?>
