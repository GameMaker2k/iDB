<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2019 iDB Support - https://idb.osdn.jp/support/category.php?act=view&id=1
    Copyright 2004-2019 Game Maker 2k - https://idb.osdn.jp/support/category.php?act=view&id=2

    $FileInfo: calendar.php - Last Update: 11/27/2020 SVN 925 - Author: cooldude2k $
*/
if(ini_get("register_globals")) {
require_once('inc/misc/killglobals.php'); }
require('preindex.php');
$usefileext = $Settings['file_ext'];
if($ext=="noext"||$ext=="no ext"||$ext=="no+ext") { $usefileext = ""; }
$filewpath = $exfile['calendar'].$usefileext.$_SERVER['PATH_INFO'];
$idbactcheck = array("view", "create");
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
if($_GET['act']==null) {
$_GET['act']="view"; }
if(!in_array($_GET['act'], $idbactcheck))
{ $_GET['act']="view"; }
if($_GET['act']=="view")
{ require($SettDir['inc'].'calendars.php'); }
if($_GET['act']=="create") 
{ require($SettDir['inc'].'events.php'); }
$iWrappers['CONTENT'] = ob_get_clean();
ob_start("idb_suboutput_handler");
require($SettDir['inc'].'endpage.php'); 
$iWrappers['COPYRIGHT'] = ob_get_clean();
ob_start("idb_suboutput_handler"); ?>
</body>
</html>
<?php
$iWrappers['HTMLEND'] = ob_get_clean();
require($SettDir['inc'].'iwrapper.php');
if($_GET['act']=="view") {
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Viewing Calendar",$Settings['use_gzip'],$GZipEncode['Type']); }
if($_GET['act']=="create") {
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Making a Event",$Settings['use_gzip'],$GZipEncode['Type']); }
?>
