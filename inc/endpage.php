<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2009 iDB Support - http://idb.berlios.de/
    Copyright 2004-2009 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: endpage.php - Last Update: 8/6/2009 SVN 293 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="endpage.php"||$File3Name=="/endpage.php") {
	require('index.php');
	exit(); }
if(!isset($_GET['time'])) { $_GET['time'] = true; }
if($_GET['time']=="show"||$_GET['time']==true) {
if($_SESSION['UserDST']=="on") { $MyDST = $checktimea['hour']+1; }
if($_SESSION['UserDST']=="off") { $MyDST = $checktimea['hour']; }
$MyDST = $MyDST.":".$checktimea['minute'];
$MyTimeNow = GMTimeGet('g:i a',$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$endpagevar=$endpagevar."<br />The time now is ".$MyTimeNow." ".$ThemeSet['LineDivider']." All times are GMT ".$MyDST; }
function execution_time($starttime) {
list($uetime, $etime) = explode(" ", microtime());
$endtime = $uetime + $etime;
return bcsub($endtime, $starttime, 4); }
if($_GET['debug']=="true"||$_GET['debug']=="on") {
	$endpagevar=$endpagevar."<br />\nFiles included: ".count_included_files()." ".$ThemeSet['LineDivider']." Execution Time: ".execution_time($starttime).$ThemeSet['LineDivider']."<a href=\"http://validator.w3.org/check/referer?verbose=1\" title=\"Validate HTML\" onclick=\"window.open(this.href);return false;\">HTML</a>".$ThemeSet['LineDivider']."<a href=\"http://jigsaw.w3.org/css-validator/check/referer?profile=css3\" title=\"Validate CSS\" onclick=\"window.open(this.href);return false;\">CSS</a>"; }
	$endpagevar=$endpagevar."</div>\n";
echo $endpagevar;
session_write_close();
//@session_write_close();
?>
