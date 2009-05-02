<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2009 Cool Dude 2k - http://idb.berlios.de/
    Copyright 2004-2009 Game Maker 2k - http://intdb.sourceforge.net/

    $FileInfo: rss.php - Last Update: 5/01/2009 SVN 247 - Author: cooldude2k $
*/
if(@ini_get("register_globals")) {
require_once('inc/misc/killglobals.php'); }
$pretime = explode(" ", microtime());
$utime = $pretime[0];
$time = $pretime[1];
$_POST['starttime'] = $utime + $time;
require_once('mysql.php');
if($Settings['enable_rss']=="off") {
@header("Content-Type: text/plain; charset=".$Settings['charset']); 
ob_clean(); echo "Sorry RSS Feeds are not enabled for this board."; 
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($_GET['act']==null) { $_GET['act'] = "rss"; }
if($_GET['act']=="rss"||$_GET['act']=="oldrss"||$_GET['act']=="atom") {
	$_GET['feedtype'] = $_GET['act']; $Feed['Feed']="Done";
	require($SettDir['inc'].'rssfeed.php'); }
?>