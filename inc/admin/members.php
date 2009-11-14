<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2009 iDB Support - http://idb.berlios.de/
    Copyright 2004-2009 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: members.php - Last Update: 11/14/2009 SVN 346 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="members.php"||$File3Name=="/members.php") {
	require('index.php');
	exit(); }

// Check if we can goto admin cp
if($_SESSION['UserGroup']==$Settings['GuestGroup']||$GroupInfo['HasAdminCP']=="no") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @session_write_close(); die(); }
if(!isset($_POST['update'])) { $_POST['update'] = null; }
if(!isset($_POST['gid'])) { $_POST['gid'] = "0"; }
if(!is_numeric($_POST['gid'])) { $_POST['gid'] = "0"; }
$Error = null; $errorstr = null;
?>
<table class="Table3">
<tr style="width: 100%; vertical-align: top;">
	<td style="width: 15%; vertical-align: top;">
<?php 
require($SettDir['admin'].'table.php'); 
?>
</td>
	<td style="width: 85%; vertical-align: top;">
<?php if($_POST['act']=="validate"&&$_POST['update']=="now"&&$_GET['act']=="validate"&&$_POST['id']=="0") {
	$_POST['act'] = null; $_POST['update'] = null; }
if($_GET['act']=="validate"&&$_POST['update']!="now") { 
$admincptitle = " ".$ThemeSet['TitleDivider']." Validating Members";
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=validate",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Validating Members Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=validate",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Validating Members Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Validating Members Manager: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=validate",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="id">Member to validate:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="id" id="id">
<?php 
$gquerys = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `Name`='%s' LIMIT 1", array($Settings['ValidateGroup']));
$gresults=mysql_query($gquerys);
$VGroupID=mysql_result($gresults,0,"id");
@mysql_free_result($gresults);
$getmemidq = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE (`GroupID`=%i AND `id`<>-1) OR (`Validated`='no' AND `id`<>-1)", array($VGroupID));
$getmemidr=mysql_query($getmemidq);
$getmemidnum=mysql_num_rows($getmemidr);
$getmemidi = 0;
if($getmemidnum<1) { ?>
	<option value="0">None</option>
<?php }
while ($getmemidi < $getmemidnum) {
$getmemidID=mysql_result($getmemidr,$getmemidi,"id");
$getmemidName=mysql_result($getmemidr,$getmemidi,"Name");
?>
<option value="<?php echo $getmemidID; ?>"><?php echo $getmemidName; ?></option>
<?php ++$getmemidi; }
@mysql_free_result($getmemidr); ?>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="validate" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Validate Member" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if($_POST['act']=="validate"&&$_POST['update']=="now"&&$_GET['act']=="validate"&&$_POST['id']!="0") { 
$mguerys = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `Name`='%s' LIMIT 1", array($Settings['MemberGroup']));
$mgresults=mysql_query($mguerys);
$MGroupID=mysql_result($mgresults,0,"id");
@mysql_free_result($mgresults);
$gquerys = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `Name`='%s' LIMIT 1", array($Settings['ValidateGroup']));
$gresults=mysql_query($gquerys);
$VGroupID=mysql_result($gresults,0,"id");
@mysql_free_result($gresults);
$query = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i LIMIT 1", array($_POST['id']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
$i=0;
$VMemName=mysql_result($result,$i,"Name");
$VMemGroup=mysql_result($result,$i,"GroupID");
$VMemValidated=mysql_result($result,$i,"Validated");
$admincptitle = " ".$ThemeSet['TitleDivider']." Validating Members";
@redirect("refresh",$basedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=members",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],FALSE),"4");
if($VMemGroup==$VGroupID) {
$query = query("UPDATE `".$Settings['sqltable']."members` SET `GroupID`='%s', `Validated`='%s' WHERE `id`=%i", array($MGroupID, "yes", $_POST['id']));
mysql_query($query); }
if($VMemGroup!=$VGroupID&&$VMemValidated=="no") {
$query = query("UPDATE `".$Settings['sqltable']."members` SET `Validated`='%s' WHERE `id`=%i", array("yes", $_POST['id']));
mysql_query($query); }
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=members",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Updating Settings</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=members",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Updating Settings</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr id="ProfileTitle" class="TableMenuRow2">
<th class="TableMenuColumn2">Updating Settings</th>
</tr>
<tr class="TableMenuRow3" id="ProfileUpdate">
<td class="TableMenuColumn3">
<div style="text-align: center;">
	<br /><?php echo $VMemName; ?> was validated successfully. <a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=members",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to back to admin cp.<br />&nbsp;
	</div>
</td></tr>
<tr id="ProfileTitleEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr></table></div>
<?php } if($_POST['act']=="deletemember"&&$_POST['update']=="now"&&$_GET['act']=="deletemember"&&
	($_POST['id']=="0"||$_POST['id']=="1"||$_POST['id']=="-1")) {
	$_POST['act'] = null; $_POST['update'] = null; }
if($_GET['act']=="deletemember"&&$_POST['update']!="now") { 
$admincptitle = " ".$ThemeSet['TitleDivider']." Deleting Members";
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=deletemember",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Deleting Members Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=deletemember",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Deleting Members Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Deleting Members Manager: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=deletemember",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="id">Member to delete:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="id" id="id">
<?php 
$getmemidq = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE (`id`<>-1 AND `id`<>1)", array(null));
$getmemidr=mysql_query($getmemidq);
$getmemidnum=mysql_num_rows($getmemidr);
$getmemidi = 0;
if($getmemidnum<1) { ?>
	<option value="0">None</option>
<?php }
while ($getmemidi < $getmemidnum) {
$getmemidID=mysql_result($getmemidr,$getmemidi,"id");
$getmemidName=mysql_result($getmemidr,$getmemidi,"Name");
?>
<option value="<?php echo $getmemidID; ?>"><?php echo $getmemidName; ?></option>
<?php ++$getmemidi; }
@mysql_free_result($getmemidr); ?>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="deletemember" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Delete Member" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if($_POST['act']=="deletemember"&&$_POST['update']=="now"&&$_GET['act']=="deletemember"&&
	($_POST['id']!="0"||$_POST['id']!="1"||$_POST['id']!="-1")) { 
$DMemName = GetUserName($_POST['id'],$Settings['sqltable']);
if($DMemName!==null&&($_POST['id']!="0"||$_POST['id']!="1"||$_POST['id']!="-1")) { 
$dmquery = query("DELETE FROM `".$Settings['sqltable']."members` WHERE `id`=%i", array($_POST['id']));
mysql_query($dmquery); }
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=members",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Updating Settings</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=members",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Updating Settings</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr id="ProfileTitle" class="TableMenuRow2">
<th class="TableMenuColumn2">Updating Settings</th>
</tr>
<tr class="TableMenuRow3" id="ProfileUpdate">
<td class="TableMenuColumn3">
<div style="text-align: center;">
	<br /><?php echo $DMemName; ?> was deleted successfully. <a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=members",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to back to admin cp.<br />&nbsp;
	</div>
</td></tr>
<tr id="ProfileTitleEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr></table></div>
<?php } 

if($_POST['act']=="editmember"&&$_POST['update']=="now"&&$_GET['act']=="editmember"&&
	($_POST['id']=="0"||$_POST['id']=="1"||$_POST['id']=="-1")) {
	$_POST['act'] = null; $_POST['update'] = null; }
if($_GET['act']=="editmember"&&$_POST['update']!="now") { 
$admincptitle = " ".$ThemeSet['TitleDivider']." Editing Members";
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editmember",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Editing Members Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editmember",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Editing Members Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing Members Manager: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editmember",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="id">Member to edit:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="id" id="id">
<?php 
$getmemidq = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE (`id`<>-1 AND `id`<>1)", array(null));
$getmemidr=mysql_query($getmemidq);
$getmemidnum=mysql_num_rows($getmemidr);
$getmemidi = 0;
if($getmemidnum<1) { ?>
	<option value="0">None</option>
<?php }
while ($getmemidi < $getmemidnum) {
$getmemidID=mysql_result($getmemidr,$getmemidi,"id");
$getmemidName=mysql_result($getmemidr,$getmemidi,"Name");
?>
<option value="<?php echo $getmemidID; ?>"><?php echo $getmemidName; ?></option>
<?php ++$getmemidi; }
@mysql_free_result($getmemidr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="gid">New Group for Member:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="gid" id="gid">
<?php 
$getgrpidq = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE (`Name`<>'%s' AND `Name`<>'%s')", array($Settings['GuestGroup'],$Settings['ValidateGroup']));
$getgrpidr=mysql_query($getgrpidq);
$getgrpidnum=mysql_num_rows($getgrpidr);
$getgrpidi = 0;
if($getgrpidnum<1) { ?>
	<option value="0">None</option>
<?php }
while ($getgrpidi < $getgrpidnum) {
$getgrpidID=mysql_result($getgrpidr,$getgrpidi,"id");
$getgrpidName=mysql_result($getgrpidr,$getgrpidi,"Name");
?>
<option value="<?php echo $getgrpidID; ?>"><?php echo $getgrpidName; ?></option>
<?php ++$getgrpidi; }
@mysql_free_result($getgrpidr); ?>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="editmember" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Edit Member" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if($_POST['act']=="editmember"&&$_POST['update']=="now"&&$_GET['act']=="editmember"&&
	($_POST['id']!="0"||$_POST['id']!="1"||$_POST['id']!="-1")) { 
$ggidquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `name`='%s' LIMIT 1", array($Settings['GuestGroup']));
$ggidresult=mysql_query($ggidquery);
$GuestGroupID=mysql_result($ggidresult,0,"id");
@mysql_free_result($ggidresult);
$vgidquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `name`='%s' LIMIT 1", array($Settings['ValidateGroup']));
$vgidresult=mysql_query($vgidquery);
$ValidateGroupID=mysql_result($vgidresult,0,"id");
@mysql_free_result($vgidresult);
$DMemName = GetUserName($_POST['id'],$Settings['sqltable']);
if($DMemName!==null&&($_POST['id']!="0"||$_POST['id']!="1"||$_POST['id']!="-1")&&
	($_POST['gid']!=$GuestGroupID||$_POST['gid']!=$ValidateGroupID)) { 
$dmquery = query("UPDATE `".$Settings['sqltable']."members` SET GroupID=%i WHERE `id`=%i", array($_POST['gid'],$_POST['id']));
mysql_query($dmquery); }
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=members",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Updating Settings</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=members",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Updating Settings</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr id="ProfileTitle" class="TableMenuRow2">
<th class="TableMenuColumn2">Updating Settings</th>
</tr>
<tr class="TableMenuRow3" id="ProfileUpdate">
<td class="TableMenuColumn3">
<div style="text-align: center;">
	<br /><?php echo $DMemName; ?>&#39;s group id was changed successfully.<br /> <a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=members",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to back to admin cp.<br />&nbsp;
	</div>
</td></tr>
<tr id="ProfileTitleEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr></table></div>
<?php } ?>
</td></tr>
</table>
<div>&nbsp;</div>
