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

    $FileInfo: main.php - Last Update: 08/31/2007 SVN 93 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="main.php"||$File3Name=="/main.php") {
	require('index.php');
	exit(); }

// Check if we can edit the profile
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
<?php if($_GET['act']=="view") {
if($_POST['update']!="now") {
$query = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i", array($_SESSION['UserID']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
$i=0;
$YourID=mysql_result($result,$i,"id");
$Notes=mysql_result($result,$i,"Notes");
$noteact = url_maker($exfile['profile'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']);
$notepadact = $noteact; $profiletitle = " ".$ThemeSet['TitleDivider']." NotePad";
?>
<div class="Table1Border">
<table class="Table1" style="width: 100%;">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo $noteact; ?>">NotePad</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr id="ProfileTitle" class="TableRow2">
<th class="TableRow2">NotePad</th>
</tr>
<tr class="TableRow3" id="NotePadRow">
<td class="TableRow3">
<form method="post" action="<?php echo $notepadact; ?>"><div style="text-align: center;">
<label class="TextBoxLabel" for="NotePad">Your NotePad</label><br />
<textarea class="TextBox" name="NotePad" id="NotePad" style="width: 75%; height: 128px;" rows="10" cols="84"><?php echo $Notes; ?></textarea>
<input type="hidden" name="act" value="view" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<br /><input type="submit" class="Button" value="Save" />&nbsp;<input class="Button" type="reset" />
</div></form></td>
</tr>
<tr id="ProfileEnd" class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table>
</div>
<?php } } ?>
</td></tr>
</table>
<div>&nbsp;</div>