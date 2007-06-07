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

    $FileInfo: category.php - Last Update: 06/07/2007 SVN 20 - Author: cooldude2k $
*/
require('preindex.php');
$usefileext = $Settings['file_ext'];
if($ext=="noext"||$ext=="no ext"||$ext=="no+ext") { $usefileext = ""; }
$filewpath = $exfile['category'].$usefileext.$_SERVER['PATH_INFO'];
if(!is_numeric($_GET['id']))
{ $_GET['id']="1"; }
?>

<title> <?php echo $Settings['board_name'].$idbpowertitle; ?> </title>
</head>
<body>
<?php require($SettDir['inc'].'navbar.php');
if($_GET['act']==null)
{ $_GET['act']="view"; }
if(!is_numeric($_GET['id']))
{ $_GET['id']="1"; }
if($_GET['act']=="view")
{ require($SettDir['inc'].'categories.php'); }
require($SettDir['inc'].'endpage.php');
if(!isset($CategoryName)) { $CategoryName = null; }
?>

</body>
</html>
<?php
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Viewing Category ".$CategoryName,$Settings['use_gzip'],$GZipEncode['Type']);
?>
