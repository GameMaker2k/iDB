<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2022 iDB Support - https://idb.osdn.jp/support/category.php?act=view&id=1
    Copyright 2004-2022 Game Maker 2k - https://idb.osdn.jp/support/category.php?act=view&id=2

    $FileInfo: profile.php - Last Update: 4/9/2022 SVN 959 - Author: cooldude2k $
*/
if(ini_get("register_globals")) {
require_once('inc/misc/killglobals.php'); }
require('preindex.php');
$usefileext = $Settings['file_ext'];
if($ext=="noext"||$ext=="no ext"||$ext=="no+ext") { $usefileext = ""; }
$filewpath = $exfile['profile'].$usefileext.$_SERVER['PATH_INFO'];
$idbactcheck = array("view", "signature", "avatar", "settings", "profile", "userinfo");
?>
<?php $iWrappers['EXTRALINKS'] = null;
ob_start("idb_suboutput_handler");
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
require($SettDir['inc'].'navbar.php');
$iWrappers['NAVBAR'] = ob_get_clean();
ob_start("idb_suboutput_handler");
if($_SESSION['UserGroup']==$Settings['GuestGroup']||$GroupInfo['CanEditProfile']=="no") {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($_SESSION['UserGroup']!=$Settings['GuestGroup']||
	$GroupInfo['CanEditProfile']=="yes") {
if($_GET['act']==null||$_GET['act']=="notepad")
{ $_GET['act']="view"; }
if(!in_array($_GET['act'], $idbactcheck))
{ $_GET['act']="view"; }
if($_GET['act']=="view"||
$_GET['act']=="signature"||
$_GET['act']=="avatar"||
$_GET['act']=="settings"||
$_GET['act']=="profile"||
$_GET['act']=="userinfo")
{ require($SettDir['inc'].'profilemain.php'); } } 
$iWrappers['CONTENT'] = ob_get_clean();
ob_start("idb_suboutput_handler");
require($SettDir['inc'].'endpage.php'); 
$iWrappers['COPYRIGHT'] = ob_get_clean();
ob_start("idb_suboutput_handler");
if(!isset($profiletitle)) { $profiletitle = null; }
?>
</body>
</html>
<?php 
$iWrappers['HTMLEND'] = ob_get_clean();
require($SettDir['inc'].'iwrapper.php');
if($profiletitle==null) {
fix_amp($Settings['use_gzip'],$GZipEncode['Type']); }
if($profiletitle!=null) {
change_title($Settings['board_name'].$profiletitle,$Settings['use_gzip'],$GZipEncode['Type']); }
?>
