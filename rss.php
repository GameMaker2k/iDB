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

    $FileInfo: rss.php - Last Update: 06/17/2011 SVN 676 - Author: cooldude2k $
*/
if(ini_get("register_globals")) {
require_once('inc/misc/killglobals.php'); }
$pretime = explode(" ", microtime());
$utime = $pretime[0];
$time = $pretime[1];
$starttime = $utime + $time;
require_once('sql.php');
$idbactcheck = array("rss", "oldrss", "atom", "opml", "opensearch");
/*if($Settings['enable_search']=="off"||$GroupInfo['CanSearch']=="no") {
header("Content-Type: text/plain; charset=".$Settings['charset']); 
ob_clean(); echo "Sorry you can not search on this board."; $urlstatus = 503;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }*/
if($Settings['enable_rss']=="off") {
header("Content-Type: text/plain; charset=".$Settings['charset']); 
ob_clean(); echo "Sorry RSS Feeds are not enabled for this board."; $urlstatus = 503;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($_GET['act']==null) { $_GET['act'] = "rss"; }
if(!in_array($_GET['act'], $idbactcheck)) { $_GET['act']="rss"; }
if($_GET['act']=="rss"||$_GET['act']=="oldrss"||$_GET['act']=="atom"||
	$_GET['act']=="opml"||$_GET['act']=="opensearch") {
	$_GET['feedtype'] = $_GET['act']; $Feed['Feed']="Done";
	require($SettDir['inc'].'rssfeed.php'); }
?>
