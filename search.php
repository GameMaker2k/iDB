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

    $FileInfo: search.php - Last Update: 5/01/2009 SVN 247 - Author: cooldude2k $
*/
if(@ini_get("register_globals")) {
require_once('inc/misc/killglobals.php'); }
require('preindex.php');
$usefileext = $Settings['file_ext'];
if($ext=="noext"||$ext=="no ext"||$ext=="no+ext") { $usefileext = ""; }
$filewpath = $exfile['search'].$usefileext.$_SERVER['PATH_INFO'];
?>

<title> <?php echo $Settings['board_name'].$idbpowertitle; ?> </title>
</head>
<body>
<?php require($SettDir['inc'].'navbar.php');
if($Settings['enable_search']=="off"||
	$GroupInfo['CanSearch']=="no") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
@header("Content-Type: text/plain; charset=".$Settings['charset']);
ob_clean(); echo "Sorry you do not have permission to do a search."; 
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($Settings['enable_search']=="on"||$GroupInfo['CanSearch']=="yes") {
if(!isset($_GET['search'])) { $_GET['search'] = null; }
if(!isset($_POST['search'])) { $_POST['search'] = null; }
if($_GET['search']==null&&
	$_POST['search']!=null) { 
		$_GET['search'] = $_POST['search']; }
if(!isset($_GET['type'])) { $_GET['type'] = null; }
if(!isset($_POST['type'])) { $_POST['type'] = null; }
if($_GET['type']==null&&
	$_POST['type']!=null) { 
		$_GET['type'] = $_POST['type']; }
if(!isset($_POST['act'])) { $_POST['act'] = null; }
if($_GET['act']==null||$_GET['act']=="topic"||
	$_POST['act']=="topic"||$_POST['act']=="topics")
	{	$_GET['act']="topics";	}
if(!isset($_GET['msearch'])) { $_GET['msearch'] = null; }
if(!isset($_POST['msearch'])) { $_POST['msearch'] = null; }
if($_GET['msearch']==null&&
	$_POST['msearch']!=null) { 
		$_GET['msearch'] = $_POST['msearch']; }
if($_GET['act']=="topics") { 
require($SettDir['inc'].'searchs.php'); } }
require($SettDir['inc'].'endpage.php');
if(!isset($_GET['search'])) { $_GET['search'] = null; }
?>
</body>
</html>
<?php 
if($_GET['search']==null&&$_GET['type']==null) {
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Searching",$Settings['use_gzip'],$GZipEncode['Type']); }
if($_GET['search']!=null&&$_GET['type']!=null) {
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." ".$_GET['search'],$Settings['use_gzip'],$GZipEncode['Type']); }
?>