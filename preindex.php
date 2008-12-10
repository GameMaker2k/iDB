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

    $FileInfo: preindex.php - Last Update: 12/09/2008 SVN 207 - Author: cooldude2k $
*/
$pretime = explode(" ", microtime());
$utime = $pretime[0];
$time = $pretime[1];
$_POST['starttime'] = $utime + $time;
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="preindex.php"||$File3Name=="/preindex.php") {
	@header('Location: index.php');
	exit(); }
require('mysql.php');
if($Settings['enable_rss']=="on") {
if(!isset($_GET['feed'])) { $_GET['feed'] = null; }
if($_GET['feed']=="rss"||$_GET['act']=="Feed"||$_GET['feed']=="atom") {
	require($SettDir['inc'].'rssfeed.php'); } }
if($Settings['output_type']=="htm") {
	$Settings['output_type'] = "html"; }
if($Settings['output_type']=="xhtm") {
	$Settings['output_type'] = "xhtml"; }
if($Settings['output_type']=="xml+htm") {
	$Settings['output_type'] = "xhtml"; }
if($Settings['html_type']=="xhtml10") {
require($SettDir['inc'].'xhtml10.php'); }
if($Settings['html_type']=="xhtml11") {
if(stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml")) {
$ccstart = "//<![CDATA["; $ccend = "//]]>";
require($SettDir['inc'].'xhtml11.php'); } else {
if (stristr($_SERVER["HTTP_USER_AGENT"],"W3C_Validator")) {
	$ccstart = "//<![CDATA["; $ccend = "//]]>";
   require($SettDir['inc'].'xhtml11.php'); } else { 
	   $ccstart = "//<!--"; $ccend = "//-->";
	   $Settings['html_type']="xhtml10";
	   $Settings['html_level']="Strict";
	   require($SettDir['inc'].'xhtml10.php'); } } }
if($Settings['html_type']!="xhtml10") {
	if($Settings['html_type']!="xhtml11") {
	$ccstart = "//<!--"; $ccend = "//-->";
	require($SettDir['inc'].'xhtml10.php'); } }
?>