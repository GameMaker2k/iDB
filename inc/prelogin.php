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

    $FileInfo: prelogin.php - Last Update: 06/18/2007 SVN 26 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="prelogin.php"||$File3Name=="/prelogin.php") {
	require('index.php');
	exit(); }
$_SESSION['CheckCookie']="done";
$querylog2 = query("select * from ".$Settings['sqltable']."members where `Name` = '%s' and `Password`='%s'", array($_COOKIE['MemberName'],$_COOKIE['SessPass']));
$resultlog2=mysql_query($querylog2);
$numlog2=mysql_num_rows($resultlog2);
if($numlog2>=1) {
$il=0;
$YourIDAM=mysql_result($resultlog2,$il,"id");
$YourGroupAM=mysql_result($resultlog2,$il,"GroupID");
$YourPassAM=mysql_result($resultlog2,$il,"Password");
$gquery = query("select * from ".$Settings['sqltable']."groups where `id`=%i", array($YourGroupAM));
$gresult=mysql_query($gquery);
$YourGroupAM=mysql_result($gresult,0,"Name");
@mysql_free_result($gresult);
$YourTimeZoneAM=mysql_result($resultlog2,$il,"TimeZone");
$UseThemeAM=mysql_result($resultlog2,$il,"UseTheme");
$YourDSTAM=mysql_result($resultlog2,$il,"DST");
$NewDay=GMTimeStamp();
$NewIP=$_SERVER['REMOTE_ADDR'];
$queryup = query("update ".$Settings['sqltable']."members set `LastActive`='%s',`IP`='%s' WHERE `id`='%s'", array($NewDay,$NewIP,$YourIDAM));
$_SESSION['Theme']=$UseThemeAM;
$_SESSION['MemberName']=$_COOKIE['MemberName'];
$_SESSION['UserID']=$YourIDAM;
$_SESSION['UserTimeZone']=$YourTimeZoneAM;
$_SESSION['UserGroup']=$YourGroupAM;
$_SESSION['UserDST']=$YourDSTAM;
setcookie("MemberName", $YourNameM, time() + (7 * 86400), $basedir);
setcookie("UserID", $YourIDAM, time() + (7 * 86400), $basedir);
setcookie("SessPass", $YourPassAM, time() + (7 * 86400), $basedir);
} if($numlog2<=0) {
redirect("location",$basedir.url_maker($exfile['member'],$Settings['file_ext'],"act=logout",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],FALSE));
} @mysql_free_result($resultlog2);
?>
