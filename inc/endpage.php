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

    $FileInfo: endpage.php - Last Update: 05/26/2007 SVN 15 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="endpage.php"||$File3Name=="/endpage.php") {
	require('index.php');
	exit(); }
if($_GET['debug']=="true"||$_GET['debug']=="on") {
	$endpagevar=$endpagevar."<br />\nFiles included: ".count_included_files()." &amp; Extensions Enabled: ".count_extensions().$ThemeSet['LineDivider']."<a href=\"http://validator.w3.org/check/referer?verbose=1\" title=\"Validate HTML\" onclick=\"window.open(this.href);return false;\">HTML</a>".$ThemeSet['LineDivider']."<a href=\"http://jigsaw.w3.org/css-validator/check/referer?profile=css3\" title=\"Validate CSS\" onclick=\"window.open(this.href);return false;\">CSS</a>"; }
	$endpagevar=$endpagevar."</div>\n";
echo $endpagevar;
@mysql_close();
?>