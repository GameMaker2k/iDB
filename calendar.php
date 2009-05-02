<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2009 Cool Dude 2k - http://idb.berlios.de/
    Copyright 2004-2009 Game Maker 2k - http://intdb.sourceforge.net/

    $FileInfo: calendar.php - Last Update: 5/01/2009 SVN 247 - Author: cooldude2k $
*/
if(@ini_get("register_globals")) {
require_once('inc/misc/killglobals.php'); }
require('preindex.php');
$usefileext = $Settings['file_ext'];
if($ext=="noext"||$ext=="no ext"||$ext=="no+ext") { $usefileext = ""; }
$filewpath = $exfile['calendar'].$usefileext.$_SERVER['PATH_INFO'];
?>

<title> <?php echo $Settings['board_name'].$idbpowertitle; ?> </title>
</head>
<body>
<?php
require($SettDir['inc'].'navbar.php');

if($_GET['act']==null) {
$_GET['act']="view"; }
if($_GET['act']=="view")
{ require($SettDir['inc'].'calendars.php'); }
if($_GET['act']=="create") 
{ require($SettDir['inc'].'events.php'); }
require($SettDir['inc'].'endpage.php'); ?>
</body>
</html>
<?php
if($_GET['act']=="view") {
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Viewing Calendar",$Settings['use_gzip'],$GZipEncode['Type']); }
if($_GET['act']=="create") {
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Making a Event",$Settings['use_gzip'],$GZipEncode['Type']); }
?>
