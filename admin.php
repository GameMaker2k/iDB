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

    $FileInfo: admin.php - Last Update: 08/15/2011 SVN 752 - Author: cooldude2k $
*/
if(ini_get("register_globals")) {
require_once('inc/misc/killglobals.php'); }
require('preindex.php');
$usefileext = $Settings['file_ext'];
if($ext=="noext"||$ext=="no ext"||$ext=="no+ext") { $usefileext = ""; }
$filewpath = $exfile['admin'].$usefileext.$_SERVER['PATH_INFO'];
if($GroupInfo['ViewDBInfo']=="yes") {
if($_GET['act']=="settings"||$_GET['act']=="sql") {
?>

<?php $iWrappers['EXTRALINKS'] = null;
if($Settings['vercheck']===1) { 
ob_start("idb_suboutput_handler"); ?>
<script type="text/javascript" src="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=vercheck&redirect=js",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>"></script>
<?php $iWrappers['EXTRALINKS'] = ob_get_clean(); } if($Settings['vercheck']===2) { 
ob_start("idb_suboutput_handler"); ?>
<script type="text/javascript" src="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=vercheck&vercheck=newtype&redirect=js",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>"></script>
<?php $iWrappers['EXTRALINKS'] = ob_get_clean(); } } } ?>
<?php
ob_start("idb_suboutput_handler"); ?>
<title> <?php echo $Settings['board_name'].$idbpowertitle; ?> </title>
<?php $iWrappers['TITLETAG'] = ob_get_clean(); 
ob_start("idb_suboutput_handler"); ?>
</head>
<body>
<?php $iWrappers['BODYTAG'] = ob_get_clean();
ob_start("idb_suboutput_handler");
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=view","&","=",$prexqstr['index'],$exqstr['index']);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['index'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['index']; }
$_SESSION['PreViewingTitle'] = "Viewing";
$_SESSION['ViewingTitle'] = "Board index";
if(!isset($_GET['subact'])) { $_GET['subact'] = null; }
if(!isset($_POST['subact'])) { $_POST['subact'] = null; }
if(!isset($_GET['menu'])) { $_GET['menu'] = null; }
$AdminMenu = null;
require($SettDir['inc'].'navbar.php');
$iWrappers['NAVBAR'] = ob_get_clean();
ob_start("idb_suboutput_handler");
if($_SESSION['UserGroup']==$Settings['GuestGroup']||$GroupInfo['HasAdminCP']=="no") {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($_GET['act']==null) {
	$_GET['act']="view"; }
if($_GET['act']=="view"&&$GroupInfo['ViewDBInfo']!="yes") {
	$_GET['act']="view"; }
if($_GET['act']=="vercheck"&&$GroupInfo['ViewDBInfo']=="yes") {
	if($Settings['vercheck']!=1&&$Settings['vercheck']!=2) {
	$Settings['vercheck'] = 1; }
	if($Settings['vercheck']===1) {
	$addredirect = null;
	if(isset($_GET['redirect'])) { $addredirect = "&redirect=".urlencode($_GET['redirect']); }
	header("Location: ".$VerCheckURL."&name=".urlencode($iDBVerName).$addredirect); $urlstatus = 302; 
	gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
	if($Settings['vercheck']===2) {
	$addredirect = null;
	if(isset($_GET['redirect'])) { $addredirect = "&redirect=".urlencode($_GET['redirect']); }
	header("Location: ".$VerCheckURL."&bid=".$Settings['bid']."&vercheck=newtype".$addredirect); $urlstatus = 302; 
	gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); } }
if($_GET['act']=="view")
{ $AdminMenu = "menu";
if($_GET['menu']==null) {
   $AdminMenu = "main"; }
require($SettDir['admin'].'main.php'); }
if($_GET['act']=="settings"||
	$_GET['act']=="sql"||
	$_GET['act']=="info"||
	$_GET['act']=="gettheme"||
	$_GET['act']=="optimize"||
	$_GET['act']=="themelist"||
	$_GET['act']=="delsessions"||
	$_GET['act']=="resyncthemes"||
	$_GET['act']=="enablesthemes")
{ $AdminMenu = "main";
require($SettDir['admin'].'main.php'); }
if($_GET['act']=="addforum"||
	$_GET['act']=="editforum"||
	$_GET['act']=="deleteforum"||
	$_GET['act']=="retopics"||
	$_GET['act']=="rereplies"||
	$_GET['act']=="fixrnames"||
	$_GET['act']=="fixtnames"||
	$_GET['act']=="fpermissions")
{ $AdminMenu = "forums";
require($SettDir['admin'].'forums.php'); }
if($_GET['act']=="addcategory"||
	$_GET['act']=="editcategory"||
	$_GET['act']=="deletecategory"||
	$_GET['act']=="cpermissions")
{ $AdminMenu = "categories";
require($SettDir['admin'].'categories.php'); }
if($_GET['act']=="validate"||
	$_GET['act']=="editmember"||
	$_GET['act']=="deletemember")
{ $AdminMenu = "members";
require($SettDir['admin'].'members.php'); }
if($_GET['act']=="addgroup"||
	$_GET['act']=="editgroup"||
	$_GET['act']=="deletegroup")
{ $AdminMenu = "groups";
require($SettDir['admin'].'groups.php'); }
$iWrappers['CONTENT'] = ob_get_clean();
ob_start("idb_suboutput_handler");
require($SettDir['inc'].'endpage.php'); 
$iWrappers['COPYRIGHT'] = ob_get_clean();
ob_start("idb_suboutput_handler");
if(!isset($admincptitle)) { $admincptitle = null; }
?>
</body>
</html>
<?php
$iWrappers['HTMLEND'] = ob_get_clean();
require($SettDir['inc'].'iwrapper.php');
if($admincptitle==null) {
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Admin CP",$Settings['use_gzip'],$GZipEncode['Type']); }
if($admincptitle!=null) {
change_title($Settings['board_name'].$admincptitle,$Settings['use_gzip'],$GZipEncode['Type']); }
?>
