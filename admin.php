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

    $FileInfo: admin.php - Last Update: 02/17/2008 SVN 149 - Author: cooldude2k $
*/
require('preindex.php');
$usefileext = $Settings['file_ext'];
if($ext=="noext"||$ext=="no ext"||$ext=="no+ext") { $usefileext = ""; }
$filewpath = $exfile['admin'].$usefileext.$_SERVER['PATH_INFO'];
?>

<title> <?php echo $Settings['board_name'].$idbpowertitle; ?> </title>
</head>
<body>
<?php
if(!isset($_GET['subact'])) { $_GET['subact'] = null; }
require($SettDir['inc'].'navbar.php');
if($_SESSION['UserGroup']==$Settings['GuestGroup']||$GroupInfo['HasAdminCP']=="no") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($_GET['act']==null) {
$_GET['act']="view"; }
if($_GET['act']=="view"||
	$_GET['act']=="settings"||
	$_GET['act']=="mysql"||
	$_GET['act']=="info")
{ require($SettDir['admin'].'main.php'); }
if($_GET['act']=="addforum"||
	$_GET['act']=="editforum"||
	$_GET['act']=="deleteforum"||
	$_GET['act']=="fpermissions")
{ require($SettDir['admin'].'forums.php'); }
require($SettDir['inc'].'endpage.php'); 
if(!isset($admincptitle)) { $admincptitle = null; }
?>
</body>
</html>
<?php
if($admincptitle==null) {
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Admin CP",$Settings['use_gzip'],$GZipEncode['Type']); }
if($admincptitle!=null) {
change_title($Settings['board_name'].$admincptitle,$Settings['use_gzip'],$GZipEncode['Type']); }
?>
