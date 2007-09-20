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

    $FileInfo: prelogin.php - Last Update: 09/20/2007 SVN 106 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="prelogin.php"||$File3Name=="/prelogin.php") {
	require('index.php');
	exit(); }
$_SESSION['CheckCookie']="done";
$querylog2 = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `Name`='%s' AND `Password`='%s' AND `id`=%i", array($_COOKIE['MemberName'],$_COOKIE['SessPass'],$_COOKIE['UserID']));
$resultlog2=mysql_query($querylog2);
$numlog2=mysql_num_rows($resultlog2);
if($numlog2==1) {
$YourIDAM=mysql_result($resultlog2,0,"id");
$YourGroupAM=mysql_result($resultlog2,0,"GroupID");
$YourPassAM=mysql_result($resultlog2,0,"Password");
$gquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i", array($YourGroupAM));
$gresult=mysql_query($gquery);
$YourGroupAM=mysql_result($gresult,0,"Name");
@mysql_free_result($gresult); $BanError = null;
$YourTimeZoneAM=mysql_result($resultlog2,0,"TimeZone");
$UseThemeAM=mysql_result($resultlog2,0,"UseTheme");
$YourDSTAM=mysql_result($resultlog2,0,"DST");
$YourBanTime=mysql_result($resultlog2,0,"BanTime");
if($YourBanTime!=0&&$YourBanTime!=null) {
$CMonth = GMTimeGet("m",$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$CDay = GMTimeGet("d",$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$CYear = GMTimeGet("Y",$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$BMonth = GMTimeChange("m",$YourBanTime,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$BDay = GMTimeChange("d",$YourBanTime,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$BYear = GMTimeChange("Y",$YourBanTime,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
if($BYear<$CYear) { $BanError = "yes"; }
if($BYear<=$CYear&&$BMonth<$CMonth&&$BanError!="yes") { $BanError = "yes"; }
if($BYear<=$CYear&&$BMonth<=$CMonth&&$BDay<=$CDay&&$BanError!="yes") { $BanError = "yes"; } }
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
@setcookie("MemberName", $YourNameM, time() + (7 * 86400), $basedir);
@setcookie("UserID", $YourIDAM, time() + (7 * 86400), $basedir);
@setcookie("SessPass", $YourPassAM, time() + (7 * 86400), $basedir); }
if($cookieDomain!=null) {
if($cookieSecure==true) {
@setcookie("MemberName", $YourNameM, time() + (7 * 86400), $basedir, $cookieDomain, 1);
@setcookie("UserID", $YourIDAM, time() + (7 * 86400), $basedir, $cookieDomain, 1);
@setcookie("SessPass", $YourPassAM, time() + (7 * 86400), $basedir, $cookieDomain, 1); }
if($cookieSecure==false) {
@setcookie("MemberName", $YourNameM, time() + (7 * 86400), $basedir, $cookieDomain);
@setcookie("UserID", $YourIDAM, time() + (7 * 86400), $basedir, $cookieDomain);
@setcookie("SessPass", $YourPassAM, time() + (7 * 86400), $basedir, $cookieDomain); } }
} } if($numlog2<=0||$numlog2>1||$BanError=="yes") { @session_unset();
if($cookieDomain==null) {
@setcookie("MemberName", null, GMTimeStamp() - 3600, $basedir);
@setcookie("UserID", null, GMTimeStamp() - 3600, $basedir);
@setcookie("SessPass", null, GMTimeStamp() - 3600, $basedir);
@setcookie(session_name(), "", GMTimeStamp() - 3600, $basedir); }
if($cookieDomain!=null) {
if($cookieSecure==true) {
@setcookie("MemberName", null, GMTimeStamp() - 3600, $basedir, $cookieDomain, 1);
@setcookie("UserID", null, GMTimeStamp() - 3600, $basedir, $cookieDomain, 1);
@setcookie("SessPass", null, GMTimeStamp() - 3600, $basedir, $cookieDomain, 1);
@setcookie(session_name(), "", GMTimeStamp() - 3600, $basedir, $cookieDomain, 1); }
if($cookieSecure==false) {
@setcookie("MemberName", null, GMTimeStamp() - 3600, $basedir, $cookieDomain);
@setcookie("UserID", null, GMTimeStamp() - 3600, $basedir, $cookieDomain);
@setcookie("SessPass", null, GMTimeStamp() - 3600, $basedir, $cookieDomain);
@setcookie(session_name(), "", GMTimeStamp() - 3600, $basedir, $cookieDomain); } }
unset($_COOKIE[session_name()]);
$_SESSION = array(); @session_unset(); @session_destroy();
@redirect("location",$basedir.url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],false)); @mysql_free_result($resultlog2); @mysql_free_result($gresult);
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
@mysql_free_result($resultlog2); @mysql_free_result($gresult);
?>
