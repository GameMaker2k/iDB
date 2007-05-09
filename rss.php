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

    $FileInfo: rss.php - Last Update: 05/09/2007 SVN 1 - Author: cooldude2k $
*/
@error_reporting(E_ALL ^ E_NOTICE);
if(@ini_get("register_globals")) {
	require_once('inc/misc/killglobals.php'); }
require_once('mysql.php');
if($SettDir['inc']==null) { $SettDir['inc'] = "inc/"; }
if($SettDir['misc']==null) { $SettDir['misc'] = "inc/misc/"; }
if($SettDir['admin']==null) { $SettDir['admin'] = "inc/admin/"; }
if($SettDir['mod']==null) { $SettDir['mod'] = "inc/mod/"; }
if($SettDir['themes']==null) { $SettDir['themes'] = "themes/"; }
if($Settings['enable_rss']==false) {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($_GET['feed']!="rss"&&$_GET['feed']!="atom") {
	$_GET['feed'] = "rss"; }
if($_GET['feedtype']!="rss"&&$_GET['feedtype']!="atom") {
	if($_GET['feed']=="rss"||$_GET['feed']=="atom") { $_GET['feedtype'] = $_GET['feed']; }
	if($_GET['act']=="rss"||$_GET['act']=="atom") { $_GET['feedtype'] = $_GET['act']; }
	if($_GET['feedtype']!="rss"&&$_GET['feedtype']!="atom") { $_GET['feedtype'] = "rss"; } }
if($_GET['feed']=="rss"||$_GET['act']=="Feed"||$_GET['feed']=="atom") {
	$_GET['feedtype'] = $_GET['feed']; $Feed['Feed']="Done";
	require($SettDir['inc'].'rssfeed.php'); }
?>