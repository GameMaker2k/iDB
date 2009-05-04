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

    $FileInfo: index.php - Last Update: 5/04/2009 SVN 249 - Author: cooldude2k $
*/
if(@ini_get("register_globals")) {
require_once('inc/misc/killglobals.php'); }
$checklowview = true;
require('preindex.php');
$usefileext = $Settings['file_ext'];
if($ext=="noext"||$ext=="no ext"||$ext=="no+ext") { $usefileext = ""; }
$filewpath = $exfile['index'].$usefileext.$_SERVER['PATH_INFO'];
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
