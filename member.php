<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2014 iDB Support - http://idb.berlios.de/
    Copyright 2004-2014 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: member.php - Last Update: 07/21/2014 SVN 791 - Author: cooldude2k $
*/
if(ini_get("register_globals")) {
require_once('inc/misc/killglobals.php'); }
require('preindex.php');
$usefileext = $Settings['file_ext'];
if($ext=="noext"||$ext=="no ext"||$ext=="no+ext") { $usefileext = ""; }
$filewpath = $exfile['member'].$Settings['file_ext'].$_SERVER['PATH_INFO'];
if($_GET['act']==null) { $_GET['act'] = "login"; }
if(!isset($_GET['view'])) { $_GET['view'] = null; }
$idbactcheck = array("view", "signup", "login", "login_now", "logout", "online", "list", "getactive", "makemember", "makemembers");
if(!in_array($_GET['act'], $idbactcheck))
{ $_GET['act']="login"; }
?>
<?php $iWrappers['EXTRALINKS'] = null;
ob_start("idb_suboutput_handler"); ?>
<title> <?php echo $Settings['board_name'].$idbpowertitle; ?> </title>
<?php $iWrappers['TITLETAG'] = ob_get_clean(); 
ob_start("idb_suboutput_handler"); ?>
</head>
<body>
<?php $iWrappers['BODYTAG'] = ob_get_clean();
ob_start("idb_suboutput_handler");
if($_GET['act']=="register")
{ $_GET['act']="signup"; }
if($_GET['act']=="signin")
{ $_GET['act']="login"; }
if($_GET['act']=="signout")
{ $_GET['act']="logout"; }
if(!is_numeric($_GET['id']))
{ $_GET['id']="1"; }
require($SettDir['inc'].'navbar.php');
$iWrappers['NAVBAR'] = ob_get_clean();
ob_start("idb_suboutput_handler");
if($_GET['act']=="login"||
$_GET['act']=="online"||
$_POST['act']=="loginmember"||
$_GET['act']=="logout")
{ require($SettDir['inc'].'members.php'); } 
if($_GET['act']=="list"||
$_GET['act']=="getactive"||
$_GET['act']=="view"||
$_GET['act']=="signup")
{ require($SettDir['inc'].'members.php'); } 
if($_GET['act']=="makemember") {
if($_POST['act']=="makemembers") {
require($SettDir['inc'].'members.php'); } }
$iWrappers['CONTENT'] = ob_get_clean();
ob_start("idb_suboutput_handler");
require($SettDir['inc'].'endpage.php');
$iWrappers['COPYRIGHT'] = ob_get_clean();
ob_start("idb_suboutput_handler");
if(!isset($membertitle)) { $membertitle = null; }
?>
</body>
</html>
<?php 
$iWrappers['HTMLEND'] = ob_get_clean();
require($SettDir['inc'].'iwrapper.php');
if($membertitle==null) {
fix_amp($Settings['use_gzip'],$GZipEncode['Type']); }
if($membertitle!=null) {
change_title($Settings['board_name'].$membertitle,$Settings['use_gzip'],$GZipEncode['Type']); }
?>
