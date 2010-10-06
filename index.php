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

    $FileInfo: index.php - Last Update: 09/06/2010 SVN 578 - Author: cooldude2k $
*/
if(ini_get("register_globals")) {
require_once('inc/misc/killglobals.php'); }
$checklowview = true;
require('preindex.php');
$usefileext = $Settings['file_ext'];
if($ext=="noext"||$ext=="no ext"||$ext=="no+ext") { $usefileext = ""; }
$filewpath = $exfile['index'].$usefileext.$_SERVER['PATH_INFO'];

if(isset($_GET['showcategory'])&&is_numeric($_GET['showcategory'])) {
$showact = "view";
if($_GET['act']=="lowview") { $showact = "lowview"; }
redirect("location",$rbasedir.url_maker($exfile['category'],$Settings['file_ext'],"act=".$showact."&id=".$_GET['showcategory'],$Settings['qstr'],$Settings['qsep'],$prexqstr['category'],$exqstr['category'],FALSE));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }

if(isset($_GET['showforum'])&&is_numeric($_GET['showforum'])) {
$showact = "view";
if($_GET['act']=="lowview") { $showact = "lowview"; }
redirect("location",$rbasedir.url_maker($exfile['forum'],$Settings['file_ext'],"act=".$showact."&id=".$_GET['showforum'],$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum'],FALSE));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }

if(isset($_GET['showtopic'])&&is_numeric($_GET['showtopic'])) {
$showact = "view";
if($_GET['act']=="lowview") { $showact = "lowview"; }
if(isset($_GET['showpost'])&&is_numeric($_GET['showpost'])) {
redirect("location",$rbasedir.url_maker($exfile['topic'],$Settings['file_ext'],"act=".$showact."&id=".$_GET['showtopic']."&post=".$_GET['showpost'],$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'],FALSE));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); }
if(!isset($_GET['showpost'])) { $_GET['showpost'] = null; }
if(!isset($_GET['showpost'])||!is_numeric($_GET['showpost'])) {
if(!isset($_GET['showpage'])) { $_GET['showpage'] = 1; }
if(!isset($_GET['showpage'])||!is_numeric($_GET['showpage'])) { $_GET['showpage'] = 1; }
redirect("location",$rbasedir.url_maker($exfile['topic'],$Settings['file_ext'],"act=".$showact."&id=".$_GET['showtopic']."&page=".$_GET['showpage'],$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'],FALSE));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); }
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }

if(isset($_GET['showuser'])&&is_numeric($_GET['showuser'])) {
redirect("location",$rbasedir.url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$_GET['showuser'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],FALSE));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }

if(isset($_GET['showevent'])&&is_numeric($_GET['showevent'])) {
redirect("location",$rbasedir.url_maker($exfile['event'],$Settings['file_ext'],"act=view&id=".$_GET['showevent'],$Settings['qstr'],$Settings['qsep'],$prexqstr['event'],$exqstr['event'],FALSE));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
?>

<title> <?php echo $Settings['board_name'].$idbpowertitle; ?> </title>
</head>
<body>
<?php if($_GET['act']!="lowview") {
require($SettDir['inc'].'navbar.php'); }

if($_GET['act']==null)
{ $_GET['act']="view"; }
if($_GET['act']=="view")
{ require($SettDir['inc'].'forums.php'); }
if($_GET['act']=="lowview")
{ require($SettDir['inc'].'lowforums.php'); }
if($_GET['act']=="view"||$_GET['act']=="stats")
{ require($SettDir['inc'].'stats.php'); }
require($SettDir['inc'].'endpage.php');
?>
</body>
</html>
<?php fix_amp($Settings['use_gzip'],$GZipEncode['Type']); ?>
