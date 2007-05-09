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
    iDB Installer made by Game Maker 2k - http://upload.idb.s1.jcink.com/

    $FileInfo: install.php - Last Update: 05/09/2007 SVN 1 - Author: cooldude2k $
*/
@error_reporting(E_ALL ^ E_NOTICE); unset($preact['idb']);
if ($_GET['act']!="Part4"&&$_POST['act']!="Part4") {
	$preact['idb'] = "installing";	}
if ($_GET['act']==null||$_GET['act']=="view") { $_GET['act']="Part1"; }
if ($_POST['act']==null||$_POST['act']=="view") { $_POST['act']="Part1"; }
require('preindex.php');
$SetupDir['setup'] = "setup/"; $SetupDir['convert'] = "setup/convert/";
$ConvertDir['setup'] = "setup/"; $ConvertDir['convert'] = "setup/convert/";
require($SetupDir['convert'].'info.php');
unset($Error); ?>

<title> <?php echo "Installing ".$VerInfo['iDB_Ver_Show']." on ".$OSType2; ?> </title>
</head>
<body>
<?php require($SettDir['inc'].'navbar.php'); ?>

<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
&nbsp;<a href="Install.php">Install <?php echo $VerInfo['iDB_Ver_Show']." on ".$OSType2; ?> </a></span>
<span style="float: right;">&nbsp;</span></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Inert your install info: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<?php
if($_SERVER['HTTPS']=="on") { $prehost = "https://"; }
if($_SERVER['HTTPS']!="on") { $prehost = "http://"; }
if(dirname($_SERVER['SCRIPT_NAME'])!=".") {
$this_dir = dirname($_SERVER['SCRIPT_NAME'])."/"; }
if(dirname($_SERVER['SCRIPT_NAME'])==".") {
$this_dir = dirname($_SERVER['PHP_SELF'])."/"; }
if($this_dir=="\/") { $this_dir="/"; }
$idbdir = addslashes(str_replace("\\","/",dirname(__FILE__)."/"));
function sql_list_dbs() {
   $result = mysql_query("SHOW DATABASES;");
   while( $data = mysql_fetch_row($result) ) {
       $array[] = $data[0];
   } return $array; }
if ($_GET['act']!="Part2"&&$_POST['act']!="Part2") {
if ($_GET['act']!="Part3"&&$_POST['act']!="Part3") {
if ($_GET['act']!="Part4"&&$_POST['act']!="Part4") {
   require($SetupDir['setup'].'license.php'); } } }
if ($_GET['act']=="Part2"&&$_POST['act']=="Part2") {
if ($_GET['act']!="Part3"&&$_POST['act']!="Part3") {
if ($_GET['act']!="Part4"&&$_POST['act']!="Part4") {
   require($SetupDir['setup'].'presetup.php'); } } }
if($_POST['SetupType']=="convert") {
	require($ConvertInfo['ConvertFile']); }
if($_POST['SetupType']=="install") {
if ($_GET['act']!="Part2"&&$_POST['act']!="Part2") {
if ($_GET['act']=="Part3"&&$_POST['act']=="Part3") {
if ($_GET['act']!="Part4"&&$_POST['act']!="Part4") {
   require($SetupDir['setup'].'setup.php'); } } } }
if($_POST['SetupType']=="install") {
if ($_GET['act']!="Part2"&&$_POST['act']!="Part2") {
if ($_GET['act']!="Part3"&&$_POST['act']!="Part3") {
if ($_GET['act']=="Part4"&&$_POST['act']=="Part4") {
   require($SetupDir['setup'].'/mkconfig.php'); } } } }
if ($Error=="Yes") { ?>
<br />Install Failed with errors. <a href="install.php?act=view">Click here</a> to restart install. &lt;_&lt;
<br /><br />
</td>
</tr>
<?php } ?>
<tr class="TableRow4">
<td class="TableRow4" colspan="2">&nbsp;<a href="index.php?act=ReadMe">Readme.txt</a>&nbsp;</td>
</tr>
</table>
<div>&nbsp;</div>
<?php require($SettDir['inc'].'endpage.php'); ?>
</body>
</html>
<?php fix_amp(null); ?>
