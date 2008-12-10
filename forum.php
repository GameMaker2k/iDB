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

    $FileInfo: forum.php - Last Update: 12/09/2008 SVN 207 - Author: cooldude2k $
*/
require('preindex.php');
$usefileext = $Settings['file_ext'];
if($ext=="noext"||$ext=="no ext"||$ext=="no+ext") { $usefileext = ""; }
$filewpath = $exfile['forum'].$usefileext.$_SERVER['PATH_INFO'];
if(!is_numeric($_GET['id']))
{ $_GET['id']="1"; }
if($Settings['enable_rss']=="on") {
?>
<link rel="alternate" type="application/xml" title="Forum Topics RSS 1.0 Feed" href="<?php echo url_maker($exfile['rss'],$Settings['rss_ext'],"act=oldrss&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['rss'],$exqstr['rss']); ?>" />
<link rel="alternate" type="application/rss+xml" title="Forum Topics RSS 2.0 Feed" href="<?php echo url_maker($exfile['rss'],$Settings['rss_ext'],"act=rss&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['rss'],$exqstr['rss']); ?>" />
<link rel="alternate" type="application/atom+xml" title="Forum Topics Atom Feed" href="<?php echo url_maker($exfile['rss'],$Settings['rss_ext'],"act=atom&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['rss'],$exqstr['rss']); ?>" />
<?php } ?>
<title> <?php echo $Settings['board_name'].$idbpowertitle; ?> </title>
</head>
<body>
<?php require($SettDir['inc'].'navbar.php');
$ForumCheck = null;
if($_GET['act']==null)
{ $_GET['act']="view"; }
if(!is_numeric($_GET['id']))
{ $_GET['id']="1"; }
if($_GET['act']=="view"||$_GET['act']=="create"||
	$_GET['act']=="maketopic"||$_POST['act']=="maketopics")
{ require($SettDir['inc'].'topics.php'); } 
require($SettDir['inc'].'endpage.php');
if(!isset($ForumName)) { $ForumName = null; }
?>

</body>
</html>
<?php 
if($_GET['act']=="view") {
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." ".$ForumName,$Settings['use_gzip'],$GZipEncode['Type']); } 
if($_GET['act']=="create") {
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Making a Topic",$Settings['use_gzip'],$GZipEncode['Type']); }
if($_GET['act']=="maketopic"&&$_POST['act']=="maketopics") {
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Making a Topic",$Settings['use_gzip'],$GZipEncode['Type']); }
?>
