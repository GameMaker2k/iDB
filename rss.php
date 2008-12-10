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

    $FileInfo: rss.php - Last Update: 12/09/2008 SVN 207 - Author: cooldude2k $
*/
//@ini_set("display_errors", true); 
//@ini_set("display_startup_errors", true);
@error_reporting(E_ALL ^ E_NOTICE);
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