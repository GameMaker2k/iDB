<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2010 iDB Support - http://idb.berlios.de/
    Copyright 2004-2010 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: messenger.php - Last Update: 06/07/2010 SVN 520 - Author: cooldude2k $
*/
if(ini_get("register_globals")) {
require_once('inc/misc/killglobals.php'); }
require('preindex.php');
$usefileext = $Settings['file_ext'];
if($ext=="noext"||$ext=="no ext"||$ext=="no+ext") { $usefileext = ""; }
$filewpath = $exfile['messenger'].$usefileext.$_SERVER['PATH_INFO'];
?>

<title> <?php echo $Settings['board_name'].$idbpowertitle; ?> </title>
</head>
<body>
<?php require($SettDir['inc'].'navbar.php');
if($_SESSION['UserGroup']==$Settings['GuestGroup']||$GroupInfo['CanPM']=="no") {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
?>

<?php 
if($_SESSION['UserGroup']!=$Settings['GuestGroup']||
	$GroupInfo['CanPM']=="yes") {
if($_GET['act']==null)
{ $_GET['act']="view"; }
if(!is_numeric($_GET['id'])&&$_GET['act']!="create")
{ $_GET['id']="1"; }
if($_GET['act']=="view"||$_GET['act']=="viewsent")
{ require($SettDir['inc'].'pm.php'); }
if($_GET['act']=="read"||$_GET['act']=="create"||
	$_GET['act']=="sendmessage"||$_POST['act']=="sendmessages")
{ require($SettDir['inc'].'pm.php'); } }
require($SettDir['inc'].'endpage.php');
if(!isset($MessageName)) { $MessageName = null; }
?>

</body>
</html>
<?php 
if($_GET['act']=="read") {
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." ".$MessageName,$Settings['use_gzip'],$GZipEncode['Type']); }
if($_GET['act']=="viewsent") { 
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Viewing Sent MailBox",$Settings['use_gzip'],$GZipEncode['Type']); }
if($_GET['act']=="view") {
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Viewing MailBox",$Settings['use_gzip'],$GZipEncode['Type']); }
if($_GET['act']=="create") { 
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Making a Message",$Settings['use_gzip'],$GZipEncode['Type']); }
if($_GET['act']=="sendmessage"&&$_POST['act']=="sendmessages") { 
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Seanding a Message",$Settings['use_gzip'],$GZipEncode['Type']); }
?>
