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

    $FileInfo: forums.php - Last Update: 02/17/2008 SVN 149 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="forums.php"||$File3Name=="/forums.php") {
	require('index.php');
	exit(); }

// Check if we can goto admin cp
if($_SESSION['UserGroup']==$Settings['GuestGroup']||$GroupInfo['HasAdminCP']=="no") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if(!isset($_POST['update'])) { $_POST['update'] = null; }
?>
<table class="Table3">
<tr style="width: 100%; vertical-align: top;">
	<td style="width: 15%; vertical-align: top;">
<?php 
require($SettDir['admin'].'table.php'); 
?>
</td>
	<td style="width: 85%; vertical-align: top;">
<?php if($_POST['update']=="now"&&$_GET['act']!=null) {
$updateact = url_maker($exfile['profile'],$Settings['file_ext'],"act=".$_GET['act'],$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']);
$admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
@redirect("refresh",$basedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act'],$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],FALSE),"3");
?>
<div class="Table1Border">
<table class="Table1" style="width: 100%;">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act'],$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Updating Settings</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr id="ProfileTitle" class="TableRow2">
<th class="TableRow2">Updating Settings</th>
</tr>
<tr class="TableRow3" id="ProfileUpdate">
<td class="TableRow3">
<div style="text-align: center;">
<br />The action was completed successfully <a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">click here</a> to go back. ^_^<br />&nbsp;</div>
<?php } if($_GET['act']=="addforum"&&$_POST['update']!="now") { ?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
&nbsp;<a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=addforum",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Database Manager</a></span>
<span style="float: right;">&nbsp;</span></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing MySQL Settings for iDB: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableRow3">
<td class="TableRow3">
<form style="display: inline;" method="post" name="install" id="install" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=mysql",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DatabaseUserName">Insert Database User Name:</label></td>
	<td style="width: 50%;"><input type="text" name="DatabaseUserName" class="TextBox" id="DatabaseUserName" size="20" value="<?php echo $Settings['sqluser']; ?>" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="DatabasePassword">Insert Database Password:</label></td>
	<td style="width: 50%;"><input type="password" name="DatabasePassword" class="TextBox" id="DatabasePassword" size="20" value="<?php echo $Settings['sqlpass']; ?>" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="DatabaseName">Insert Database Name:</label></td>
	<td style="width: 50%;"><input type="text" name="DatabaseName" class="TextBox" id="DatabaseName" size="20" value="<?php echo $Settings['sqldb']; ?>" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="DatabaseHost">Insert Database Host:</label></td>
	<td style="width: 50%;"><input type="text" name="DatabaseHost" class="TextBox" id="DatabaseHost" size="20" value="<?php echo $Settings['sqlhost']; ?>" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="tableprefix">Insert Table Prefix:<br /></label></td>
	<td style="width: 50%;"><input type="text" name="tableprefix" class="TextBox" id="tableprefix" size="20" value="<?php echo $Settings['sqltable']; ?>" /></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="addforum" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Apply" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table>
</div>
<?php }
if($_POST['update']=="now"&&$_GET['act']!=null) {
	$profiletitle = " ".$ThemeSet['TitleDivider']." Updating Settings"; ?>
</td></tr>
<tr id="ProfileTitleEnd" class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr></table></div><?php } ?>
</td></tr>
</table>
<div>&nbsp;</div>