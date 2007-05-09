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

    $FileInfo: member.php - Last Update: 05/09/2007 SVN 1 - Author: cooldude2k $
*/
require('preindex.php');
$usefileext = $Settings['file_ext'];
if($ext=="noext"||$ext=="no ext"||$ext=="no+ext") { $usefileext = ""; }
$filewpath = $exfile['member'].$Settings['file_ext'].$_SERVER['PATH_INFO'];
if($_GET['act']==null) { $_GET['act'] = "login"; }
?>

<title> <?php echo $Settings['board_name'].$idbpowertitle; ?> </title>
</head>
<body>
<?php if($_GET['act']==null)
{ $_GET['act']="loginmember"; }
if(!is_numeric($_GET['id']))
{ $_GET['id']="1"; }
require($SettDir['inc'].'navbar.php');
if($_GET['act']=="login"||
$_POST['act']=="loginmember"||
$_GET['act']=="logout")
{ require($SettDir['inc'].'members.php'); } 
if($_GET['act']=="list"||
$_GET['act']=="view"||
$_GET['act']=="signup")
{ require($SettDir['inc'].'members.php'); } 
if($_GET['act']=="makemember") {
if($_POST['act']=="makemembers") {
require($SettDir['inc'].'members.php'); } } ?>
<div>&nbsp;</div>
<?php require($SettDir['inc'].'endpage.php');
?>

</body>
</html>
<?php 
if($membertitle==null) {
fix_amp($Settings['use_gzip']); }
if($membertitle!=null) {
change_title($Settings['board_name'].$membertitle,$Settings['use_gzip']); }
?>
