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

    $FileInfo: subcategory.php - Last Update: 07/21/2011 SVN 725 - Author: cooldude2k $
*/
if(ini_get("register_globals")) {
require_once('inc/misc/killglobals.php'); }
$checklowview = true;
require('preindex.php');
$usefileext = $Settings['file_ext'];
if($ext=="noext"||$ext=="no ext"||$ext=="no+ext") { $usefileext = ""; }
$filewpath = $exfile['category'].$usefileext.$_SERVER['PATH_INFO'];
if(!is_numeric($_GET['id'])) { $_GET['id']="1"; }
$idbactcheck = array("view", "lowview", "stats");
?>

<title> <?php echo $Settings['board_name'].$idbpowertitle; ?> </title>
</head>
<body>
<?php if($_GET['act']!="lowview") {
require($SettDir['inc'].'navbar.php'); }
$CatCheck = null;
if($_GET['act']==null)
{ $_GET['act']="view"; }
if(!in_array($_GET['act'], $idbactcheck))
{ $_GET['act']="view"; }
if(!is_numeric($_GET['id'])) { $_GET['id']="1"; }
if($_GET['act']=="view")
{ require($SettDir['inc'].'subcategories.php'); }
if($_GET['act']=="lowview")
{ require($SettDir['inc'].'lowsubcategories.php'); }
if($_GET['act']=="view"||$_GET['act']=="stats")
{ require($SettDir['inc'].'stats.php'); }
require($SettDir['inc'].'endpage.php');
if(!isset($CategoryName)) { $CategoryName = null; }
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." ".$CategoryName,$Settings['use_gzip'],$GZipEncode['Type']);
?>
