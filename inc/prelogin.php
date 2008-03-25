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

    $FileInfo: prelogin.php - Last Update: 03/25/2008 SVN 155 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="prelogin.php"||$File3Name=="/prelogin.php") {
	require('index.php');
	exit(); }
$_SESSION['CheckCookie']="done";
$querylog2 = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `Name`='%s' AND `Password`='%s' AND `id`=%i LIMIT 1", array($_COOKIE['MemberName'],$_COOKIE['SessPass'],$_COOKIE['UserID']));
$resultlog2=mysql_query($querylog2);
$numlog2=mysql_num_rows($resultlog2);
if($numlog2==1) {
$YourIDAM=mysql_result($resultlog2,0,"id");
$YourGroupAM=mysql_result($resultlog2,0,"GroupID");
$YourPassAM=mysql_result($resultlog2,0,"Password");
$gquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i LIMIT 1", array($YourGroupAM));
$gresult=mysql_query($gquery);
$YourGroupAM=mysql_result($gresult,0,"Name");
@mysql_free_result($gresult); $BanError = null;
$YourTimeZoneAM=mysql_result($resultlog2,0,"TimeZone");
$UseThemeAM=mysql_result($resultlog2,0,"UseTheme");
$YourDSTAM=mysql_result($resultlog2,0,"DST");
$YourBanTime=mysql_result($resultlog2,0,"BanTime");
$CGMTime = GMTimeStamp();
if($YourBanTime!=0&&$YourBanTime!=null) {
if($YourBanTime>=$CGMTime) { $BanError = "yes"; } }
$NewDay=GMTimeStamp();
$NewIP=$_SERVER['REMOTE_ADDR'];
if($BanError!="yes") {
$queryup = query("UPDATE `".$Settings['sqltable']."members` SET `LastActive`=%i,`IP`='%s' WHERE `id`=%i", array($NewDay,$NewIP,$YourIDAM));
$_SESSION['Theme']=$UseThemeAM;
$_SESSION['MemberName']=$_COOKIE['MemberName'];
$_SESSION['UserID']=$YourIDAM;
$_SESSION['UserTimeZone']=$YourTimeZoneAM;
$_SESSION['UserGroup']=$YourGroupAM;
$_SESSION['UserDST']=$YourDSTAM;
$_SESSION['UserPass']=$YourPassAM;
if($cookieDomain==null) {
@setcookie("MemberName", $YourNameM, time() + (7 * 86400), $cbasedir);
@setcookie("UserID", $YourIDAM, time() + (7 * 86400), $cbasedir);
@setcookie("SessPass", $YourPassAM, time() + (7 * 86400), $cbasedir); }
if($cookieDomain!=null) {
if($cookieSecure==true) {
@setcookie("MemberName", $YourNameM, time() + (7 * 86400), $cbasedir, $cookieDomain, 1);
@setcookie("UserID", $YourIDAM, time() + (7 * 86400), $cbasedir, $cookieDomain, 1);
@setcookie("SessPass", $YourPassAM, time() + (7 * 86400), $cbasedir, $cookieDomain, 1); }
if($cookieSecure==false) {
@setcookie("MemberName", $YourNameM, time() + (7 * 86400), $cbasedir, $cookieDomain);
@setcookie("UserID", $YourIDAM, time() + (7 * 86400), $cbasedir, $cookieDomain);
@setcookie("SessPass", $YourPassAM, time() + (7 * 86400), $cbasedir, $cookieDomain); } }
} } if($numlog2<=0||$numlog2>1||$BanError=="yes") { @session_unset();
if($cookieDomain==null) {
@setcookie("MemberName", null, GMTimeStamp() - 3600, $cbasedir);
@setcookie("UserID", null, GMTimeStamp() - 3600, $cbasedir);
@setcookie("SessPass", null, GMTimeStamp() - 3600, $cbasedir);
@setcookie(session_name(), "", GMTimeStamp() - 3600, $cbasedir); }
if($cookieDomain!=null) {
if($cookieSecure==true) {
@setcookie("MemberName", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1);
@setcookie("UserID", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1);
@setcookie("SessPass", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1);
@setcookie(session_name(), "", GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1); }
if($cookieSecure==false) {
@setcookie("MemberName", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain);
@setcookie("UserID", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain);
@setcookie("SessPass", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain);
@setcookie(session_name(), "", GMTimeStamp() - 3600, $cbasedir, $cookieDomain); } }
unset($_COOKIE[session_name()]);
$_SESSION = array(); @session_unset(); @session_destroy();
@redirect("location",$basedir.url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],false)); @mysql_free_result($resultlog2); @mysql_free_result($gresult);
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
@mysql_free_result($resultlog2); @mysql_free_result($gresult);
?>
