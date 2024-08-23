<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2024 iDB Support - https://idb.osdn.jp/support/category.php?act=view&id=1
    Copyright 2004-2024 Game Maker 2k - https://idb.osdn.jp/support/category.php?act=view&id=2

    $FileInfo: forum.php - Last Update: 8/23/2024 SVN 1023 - Author: cooldude2k $
*/
if(ini_get("register_globals")) {
require_once('inc/misc/killglobals.php'); }
$checklowview = true;
require('preindex.php');
$usefileext = $Settings['file_ext'];
if($ext=="noext"||$ext=="no ext"||$ext=="no+ext") { $usefileext = ""; }
$filewpath = $exfile['forum'].$usefileext.$_SERVER['PATH_INFO'];
if(!is_numeric($_GET['id'])) { $_GET['id']="1"; }
$idbactcheck = array("view", "create", "maketopic", "lowview", "oldrss", "rss", "atom", "opml");
$iWrappers['EXTRALINKS'] = null;
if($Settings['enable_rss']=="on") {
ob_start("idb_suboutput_handler");
?>
<link rel="alternate" type="application/xml" title="Forum Topics RSS 1.0 Feed" href="<?php echo url_maker($exfile['rss'],$Settings['rss_ext'],"act=oldrss&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['rss'],$exqstr['rss']); ?>" />
<link rel="alternate" type="application/rss+xml" title="Forum Topics RSS 2.0 Feed" href="<?php echo url_maker($exfile['rss'],$Settings['rss_ext'],"act=rss&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['rss'],$exqstr['rss']); ?>" />
<link rel="alternate" type="application/atom+xml" title="Forum Topics Atom Feed" href="<?php echo url_maker($exfile['rss'],$Settings['rss_ext'],"act=atom&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['rss'],$exqstr['rss']); ?>" />
<?php $iWrappers['EXTRALINKS'] = ob_get_clean(); } ?>
<?php ob_start("idb_suboutput_handler");
$title_html = htmlentities($Settings['board_name'].$idbpowertitle, ENT_QUOTES, $Settings['charset']);
?>
<meta itemprop="title" property="og:title" content="<?php echo $title_html; ?>" />
<meta itemprop="sitename" property="og:site_name" content="<?php echo $title_html; ?>" />
<meta itemprop="title" property="twitter:title" content="<?php echo $title_html; ?>" />
<meta name="title" content="<?php echo $title_html; ?>" />
<title> <?php echo $Settings['board_name'].$idbpowertitle; ?> </title>
<?php $iWrappers['TITLETAG'] = ob_get_clean(); 
ob_start("idb_suboutput_handler"); ?>
</head>
<body>
<?php $iWrappers['BODYTAG'] = ob_get_clean();
ob_start("idb_suboutput_handler");
if($_GET['act']!="lowview") {
require($SettDir['inc'].'navbar.php'); }
$iWrappers['NAVBAR'] = ob_get_clean();
ob_start("idb_suboutput_handler");
$ForumCheck = null;
if($_GET['act']==null)
{ $_GET['act']="view"; }
if(!in_array($_GET['act'], $idbactcheck))
{ $_GET['act']="view"; }
if(!is_numeric($_GET['id'])) { $_GET['id']="1"; }
if($_GET['act']=="view"||$_GET['act']=="create"||
	$_GET['act']=="maketopic"||$_POST['act']=="maketopics")
{ require($SettDir['inc'].'topics.php'); } 
if($_GET['act']=="lowview")
{ require($SettDir['inc'].'lowtopics.php'); }
if($_GET['act']=="oldrss"||$_GET['act']=="rss"||$_GET['act']=="atom"||$_GET['act']=="opml") {
redirect("location",$rbasedir.url_maker($exfile['rss'],$Settings['file_ext'],"act=".$_GET['act']."&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['rss'],$exqstr['rss'],FALSE));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
$iWrappers['CONTENT'] = ob_get_clean();
ob_start("idb_suboutput_handler");
require($SettDir['inc'].'endpage.php');
$iWrappers['COPYRIGHT'] = ob_get_clean();
ob_start("idb_suboutput_handler");
if(!isset($ForumName)) { $ForumName = null; }
?>
</body>
</html>
<?php 
$iWrappers['HTMLEND'] = ob_get_clean();
require($SettDir['inc'].'iwrapper.php');
if($_GET['act']=="view"||$_GET['act']=="lowview") {
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." ".$ForumName,$Settings['use_gzip'],$GZipEncode['Type']); } 
if($_GET['act']=="create") {
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Making a Topic",$Settings['use_gzip'],$GZipEncode['Type']); }
if($_GET['act']=="maketopic"&&$_POST['act']=="maketopics") {
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Making a Topic",$Settings['use_gzip'],$GZipEncode['Type']); }
?>
