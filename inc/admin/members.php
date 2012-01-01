<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2012 iDB Support - http://idb.berlios.de/
    Copyright 2004-2012 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: members.php - Last Update: 01/01/2012 SVN 782 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="members.php"||$File3Name=="/members.php") {
	require('index.php');
	exit(); }

// Check if we can goto admin cp
if($_SESSION['UserGroup']==$Settings['GuestGroup']||$GroupInfo['HasAdminCP']=="no") {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if(!isset($_POST['update'])) { $_POST['update'] = null; }
if(!isset($_POST['gid'])) { $_POST['gid'] = "0"; }
if(!isset($_POST['search'])) { $_POST['search'] = "%"; }
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
$gquerys = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"Name\"='%s' LIMIT 1", array($Settings['ValidateGroup']));
$gresults=sql_query($gquerys,$SQLStat);
$VGroupID=sql_result($gresults,0,"id");
sql_free_result($gresults);
$getmemidq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE (\"GroupID\"=%i AND \"id\"<>-1) OR (\"Validated\"='no' AND \"id\"<>-1)", array($VGroupID));
$getmemidr=sql_query($getmemidq,$SQLStat);
$getmemidnum=sql_num_rows($getmemidr);
$getmemidi = 0;
if($getmemidnum<1) { ?>
	<option value="0">None</option>
<?php }
while ($getmemidi < $getmemidnum) {
$getmemidID=sql_result($getmemidr,$getmemidi,"id");
$getmemidName=sql_result($getmemidr,$getmemidi,"Name");
?>
<option value="<?php echo $getmemidID; ?>"><?php echo $getmemidName; ?></option>
<?php ++$getmemidi; }
sql_free_result($getmemidr); ?>
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
$mguerys = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"Name\"='%s' LIMIT 1", array($Settings['MemberGroup']));
$mgresults=sql_query($mguerys,$SQLStat);
$MGroupID=sql_result($mgresults,0,"id");
sql_free_result($mgresults);
$gquerys = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"Name\"='%s' LIMIT 1", array($Settings['ValidateGroup']));
$gresults=sql_query($gquerys,$SQLStat);
$VGroupID=sql_result($gresults,0,"id");
sql_free_result($gresults);
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($_POST['id']));
$result=sql_query($query,$SQLStat);
$num=sql_num_rows($result);
$i=0;
$VMemName=sql_result($result,$i,"Name");
$VMemGroup=sql_result($result,$i,"GroupID");
$VMemValidated=sql_result($result,$i,"Validated");
$admincptitle = " ".$ThemeSet['TitleDivider']." Validating Members";
redirect("refresh",$rbasedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=members",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],FALSE),"4");
if($VMemGroup==$VGroupID) {
$query = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"GroupID\"='%s', \"Validated\"='%s' WHERE \"id\"=%i", array($MGroupID, "yes", $_POST['id']));
sql_query($query,$SQLStat); }
if($VMemGroup!=$VGroupID&&$VMemValidated=="no") {
$query = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"Validated\"='%s' WHERE \"id\"=%i", array("yes", $_POST['id']));
sql_query($query,$SQLStat); }
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
	<br /><?php echo $VMemName; ?> was validated successfully.<br /> <a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=members",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to back to admin cp.<br />&nbsp;
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
$getmemidq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE (\"id\"<>-1 AND \"id\"<>1)", array(null));
$getmemidr=sql_query($getmemidq,$SQLStat);
$getmemidnum=sql_num_rows($getmemidr);
$getmemidi = 0;
if($getmemidnum<1) { ?>
	<option value="0">None</option>
<?php }
while ($getmemidi < $getmemidnum) {
$getmemidID=sql_result($getmemidr,$getmemidi,"id");
$getmemidName=sql_result($getmemidr,$getmemidi,"Name");
?>
<option value="<?php echo $getmemidID; ?>"><?php echo $getmemidName; ?></option>
<?php ++$getmemidi; }
sql_free_result($getmemidr); ?>
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
$DMemName = $DMemName['Name'];
if($DMemName!==null&&($_POST['id']!="0"||$_POST['id']!="1"||$_POST['id']!="-1")) { 
$dmquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i", array($_POST['id']));
sql_query($dmquery,$SQLStat);
$dmquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."mempermissions\" WHERE \"id\"=%i", array($_POST['id']));
sql_query($dmquery,$SQLStat); 

$dmgquery = sql_pre_query("UPDATE \"".$Settings['sqltable']."events\" SET \"GuestName\"='%s',\"UserID\"=-1 WHERE \"UserID\"=%i", array($DMemName,$_POST['id']));
sql_query($dmgquery,$SQLStat);
$dmgquery = sql_pre_query("UPDATE \"".$Settings['sqltable']."messenger\" SET \"GuestName\"='%s',\"SenderID\"=-1 WHERE \"SenderID\"=%i", array($DMemName,$_POST['id']));
sql_query($dmgquery,$SQLStat);
$dmgquery = sql_pre_query("UPDATE \"".$Settings['sqltable']."posts\" SET \"GuestName\"='%s',\"UserID\"=-1 WHERE \"UserID\"=%i", array($DMemName,$_POST['id']));
sql_query($dmgquery,$SQLStat);
$dmgquery = sql_pre_query("UPDATE \"".$Settings['sqltable']."topics\" SET \"GuestName\"='%s',\"UserID\"=-1 WHERE \"UserID\"=%i", array($DMemName,$_POST['id']));
sql_query($dmgquery,$SQLStat); }
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
	<br /><?php echo $DMemName; ?> was deleted successfully.<br /> <a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=members",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to back to admin cp.<br />&nbsp;
	</div>
</td></tr>
<tr id="ProfileTitleEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr></table></div>
<?php } if($_POST['act']=="editmember"&&$_POST['update']=="now"&&$_GET['act']=="editmember"&&
	($_POST['id']=="0"||$_POST['id']=="-1")) {
	$_POST['act'] = null; $_POST['update'] = null; }
if($_GET['act']=="editmember"&&$_POST['update']!="now"&&!isset($_POST['id'])) { 
$admincptitle = " ".$ThemeSet['TitleDivider']." Editing Members";
$_POST['search'] = stripcslashes(htmlspecialchars($_POST['search'], ENT_QUOTES, $Settings['charset']));
//$_POST['search'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['search']);
$_POST['search'] = remove_spaces($_POST['search']);
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
<form style="display: inline;" method="post" id="acpstool" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editmember",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="search">Search for member name:</label></td>
	<td style="width: 50%;"><input type="text" name="search" class="TextBox" id="search" size="20" value="<?php echo $_POST['search']; ?>" /></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="submit" class="Button" value="Search" name="Apply_Changes" />
</td></tr></table>
</form>
<?php if(isset($_POST['search'])) { ?>
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editmember",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="id">Member to edit:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="id" id="id">
<?php 
$getmemidq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"Name\" LIKE '%s' AND (\"id\"<>-1)", array($_POST['search']));
$getmemidr=sql_query($getmemidq,$SQLStat);
$getmemidnum=sql_num_rows($getmemidr);
$getmemidi = 0;
if($getmemidnum<1) { ?>
	<option value="0">None</option>
<?php }
while ($getmemidi < $getmemidnum) {
$getmemidID=sql_result($getmemidr,$getmemidi,"id");
$getmemidName=sql_result($getmemidr,$getmemidi,"Name");
?>
<option value="<?php echo $getmemidID; ?>"><?php echo $getmemidName; ?></option>
<?php ++$getmemidi; }
sql_free_result($getmemidr); ?>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="editmember" style="display: none;" />
<input type="submit" class="Button" value="Edit Member" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form><?php } ?>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if($_POST['act']=="editmember"&&$_POST['update']!="now"&&$_GET['act']=="editmember"&&
	($_POST['id']!="0"||$_POST['id']!="-1")) { 
$admincptitle = " ".$ThemeSet['TitleDivider']." Editing Members";
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($_POST['id']));
$result=sql_query($query,$SQLStat);
$num=sql_num_rows($result);
if($num<1) {
redirect("location",$rbasedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=editmember",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
$EditMem['ID']=sql_result($result,0,"id");
$EditMem['Name']=sql_result($result,0,"Name");
$EditMem['Email']=sql_result($result,0,"Email");
$EditMem['GroupID']=sql_result($result,0,"GroupID");
$gquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($EditMem['GroupID']));
$gresult=sql_query($gquery,$SQLStat);
$EditMem['Group']=sql_result($gresult,0,"Name");
sql_free_result($gresult);
$EditMem['Validated']=sql_result($result,0,"Validated");
$EditMem['HiddenMember']=sql_result($result,0,"HiddenMember");
$EditMem['WarnLevel']=sql_result($result,0,"WarnLevel");
$EditMem['BanTime']=sql_result($result,0,"BanTime");
if($EditMem['BanTime']!=""&&$EditMem['BanTime']>1) {
$BanMonth=GMTimeChange("m",$EditMem['BanTime'],0,0,"off");
$BanDay=GMTimeChange("d",$EditMem['BanTime'],0,0,"off");
$BanYear=GMTimeChange("Y",$EditMem['BanTime'],0,0,"off");
$EditMem['BanTime'] = $BanMonth."/".$BanDay."/".$BanYear; }
$EditMem['Interests']=sql_result($result,0,"Interests");
$EditMem['Signature']=sql_result($result,0,"Signature");
$EditMem['Avatar']=sql_result($result,0,"Avatar");
$EditMem['AvatarSize']=sql_result($result,0,"AvatarSize");
$EditMem['Title']=sql_result($result,0,"Title");
$EditMem['Website']=sql_result($result,0,"Website");
$EditMem['Gender']=sql_result($result,0,"Gender");
$EditMem['PostCount']=sql_result($result,0,"PostCount");
$EditMem['Karma']=sql_result($result,0,"Karma");
$EditMem['TimeZone']=sql_result($result,0,"TimeZone");
$EditMem['DST']=sql_result($result,0,"DST");
$EditMem['IP']=sql_result($result,0,"IP");
$mpquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."mempermissions\" WHERE \"id\"=%i LIMIT 1", array($_POST['id']));
$mpresult=sql_query($mpquery,$SQLStat);
$mpnum=sql_num_rows($mpresult);
$EditMemPerm['PermissionID'] = sql_result($mpresult,0,"PermissionID");
$EditMemPerm['CanViewBoard'] = sql_result($mpresult,0,"CanViewBoard");
$EditMemPerm['CanViewOffLine'] = sql_result($mpresult,0,"CanViewOffLine");
$EditMemPerm['CanEditProfile'] = sql_result($mpresult,0,"CanEditProfile");
$EditMemPerm['CanAddEvents'] = sql_result($mpresult,0,"CanAddEvents");
$EditMemPerm['CanPM'] = sql_result($mpresult,0,"CanPM");
$EditMemPerm['CanSearch'] = sql_result($mpresult,0,"CanSearch");
$EditMemPerm['CanDoHTML'] = sql_result($mpresult,0,"CanDoHTML");
$EditMemPerm['CanUseBBags'] = sql_result($mpresult,0,"CanUseBBags");
$EditMemPerm['CanViewIPAddress'] = sql_result($mpresult,0,"CanViewIPAddress");
$EditMemPerm['CanViewUserAgent'] = sql_result($mpresult,0,"CanViewUserAgent");
$EditMemPerm['FloodControl'] = sql_result($mpresult,0,"FloodControl");
$EditMemPerm['SearchFlood'] = sql_result($mpresult,0,"SearchFlood");
$EditMemPerm['HasModCP'] = sql_result($mpresult,0,"HasModCP");
$EditMemPerm['HasAdminCP'] = sql_result($mpresult,0,"HasAdminCP");
$EditMemPerm['ViewDBInfo'] = sql_result($mpresult,0,"ViewDBInfo");
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
<?php if($GroupInfo['CanViewIPAddress']=="yes") { ?>
<tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Members IP:</span></td>
	<td style="width: 50%;"><a onclick="window.open(this.href);return false;" href="<?php echo sprintf($IPCheckURL,$EditMem['IP']); ?>"><?php echo $EditMem['IP']; ?></a></td>
</tr><?php } ?><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="MemName">Members Name:</label></td>
	<td style="width: 50%;"><input type="text" name="MemName" class="TextBox" id="MemName" size="20" value="<?php echo $EditMem['Name']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="MemEmail">Members Email:</label></td>
	<td style="width: 50%;"><input type="text" name="MemEmail" class="TextBox" id="MemEmail" size="20" value="<?php echo $EditMem['Email']; ?>" /></td>
<?php if($EditMem['ID']!=1) { ?>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="gid">New Group for Member:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="gid" id="gid">
<?php 
$getgrpidq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE (\"Name\"<>'%s' AND \"Name\"<>'%s')", array($Settings['GuestGroup'],$Settings['ValidateGroup']));
$getgrpidr=sql_query($getgrpidq,$SQLStat);
$getgrpidnum=sql_num_rows($getgrpidr);
$getgrpidi = 0;
if($getgrpidnum<1) { ?>
	<option value="0">None</option>
<?php }
while ($getgrpidi < $getgrpidnum) {
$getgrpidID=sql_result($getgrpidr,$getgrpidi,"id");
$getgrpidName=sql_result($getgrpidr,$getgrpidi,"Name");
$GIDselected = null;
if($getgrpidID==$EditMem['GroupID']) { 
	$GIDselected = " selected=\"selected\""; }
?>
<option value="<?php echo $getgrpidID; ?>"<?php echo $GIDselected; ?>><?php echo $getgrpidName; ?></option>
<?php ++$getgrpidi; }
sql_free_result($getgrpidr); ?>
	</select></td>
<?php } ?>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="MemHidden">Hidden Member:</label></td>
	<td style="width: 50%;"><select id="MemHidden" name="MemHidden" class="TextBox">
<option selected="selected" value="<?php echo $EditMem['HiddenMember']; ?>">Old Value (<?php echo $EditMem['HiddenMember']; ?>)</option>
<option value="no">No</option>
<option value="yes">Yes</option>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="MemWarnLevel">Members Warn Level:</label></td>
	<td style="width: 50%;"><input type="text" name="MemWarnLevel" class="TextBox" id="MemWarnLevel" size="20" value="<?php echo $EditMem['WarnLevel']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="MemBanTime" title="Enter date till user is banned in MM/DD/YYYY format. 0 means no ban and -1 means permanent ban.">Members Ban Time:</label></td>
	<td style="width: 50%;"><input type="text" name="MemBanTime" class="TextBox" id="MemBanTime" size="20" value="<?php echo $EditMem['BanTime']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="MemPostCount">Members Post Count:</label></td>
	<td style="width: 50%;"><input type="text" name="MemPostCount" class="TextBox" id="MemPostCount" size="20" value="<?php echo $EditMem['PostCount']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="MemKarma">Members Karma Count:</label></td>
	<td style="width: 50%;"><input type="text" name="MemKarma" class="TextBox" id="MemKarma" size="20" value="<?php echo $EditMem['Karma']; ?>" /></td>
<?php if($EditMem['ID']!=1) { ?>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="MemPermID">Members Permission ID:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="MemPermID" id="MemPermID">
	<option <?php if($EditMemPerm['PermissionID']=="0") { echo "selected=\"selected\" "; } ?>value="0">use group info</option>
<?php 
if($Settings['sqltype']=="mysql"||$Settings['sqltype']=="mysqli"||
	$Settings['sqltype']=="pgsql"||$Settings['sqltype']=="sqlite") {
$getperidq = sql_pre_query("SELECT DISTINCT \"PermissionID\" FROM \"".$Settings['sqltable']."permissions\"", array(null)); }
if($Settings['sqltype']=="cubrid") {
$getperidq = sql_pre_query("SELECT DISTINCT \"permissionid\" FROM \"".$Settings['sqltable']."permissions\"", array(null)); }
$getperidr=sql_query($getperidq,$SQLStat);
$getperidnum=sql_num_rows($getperidr);
$getperidi = 0;
while ($getperidi < $getperidnum) {
if($Settings['sqltype']=="mysql"||$Settings['sqltype']=="mysqli"||
	$Settings['sqltype']=="pgsql"||$Settings['sqltype']=="cubrid") {
$getperidID=sql_result($getperidr,$getperidi,"PermissionID"); }
if($Settings['sqltype']=="sqlite") {
$getperidID=sql_result($getperidr,$getperidi,"\"PermissionID\""); }
$getperidq2 = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."permissions\" WHERE \"PermissionID\"=%i ORDER BY \"PermissionID\" ASC", array($getperidID));
$getperidr2=sql_query($getperidq2,$SQLStat);
$getperidnum2=sql_num_rows($getperidr2);
$getperidName=sql_result($getperidr2,0,"Name");
sql_free_result($getperidr2);
?>
	<option <?php if($EditMemPerm['PermissionID']==$getperidID) { echo "selected=\"selected\" "; } ?>value="<?php echo $getperidID; ?>"><?php echo $getperidName; ?></option>
<?php ++$getperidi; }
sql_free_result($getperidr); ?>
	</select></td>
<?php } if($EditMem['ID']!=1) { ?>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewBoard">Can View Board:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewBoard" id="CanViewBoard">
	<option selected="selected" value="<?php echo $EditMemPerm['CanViewBoard']; ?>">Old Value (<?php echo $EditMemPerm['CanViewBoard']; ?>)</option>
	<option value="group">use group info</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewOffLine">Can View OffLine Board:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewOffLine" id="CanViewOffLine">
	<option selected="selected" value="<?php echo $EditMemPerm['CanViewOffLine']; ?>">Old Value (<?php echo $EditMemPerm['CanViewOffLine']; ?>)</option>
	<option value="group">use group info</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanEditProfile">Can Edit Profile:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanEditProfile" id="CanEditProfile">
	<option selected="selected" value="<?php echo $EditMemPerm['CanEditProfile']; ?>">Old Value (<?php echo $EditMemPerm['CanEditProfile']; ?>)</option>
	<option value="group">use group info</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanAddEvents">Can Add Events:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanAddEvents" id="CanAddEvents">
	<option selected="selected" value="<?php echo $EditMemPerm['CanAddEvents']; ?>">Old Value (<?php echo $EditMemPerm['CanAddEvents']; ?>)</option>
	<option value="group">use group info</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanPM">Can PM:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanPM" id="CanPM">
	<option selected="selected" value="<?php echo $EditMemPerm['CanPM']; ?>">Old Value (<?php echo $EditMemPerm['CanPM']; ?>)</option>
	<option value="group">use group info</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanSearch">Can Search:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanSearch" id="CanSearch">
	<option selected="selected" value="<?php echo $EditMemPerm['CanSearch']; ?>">Old Value (<?php echo $EditMemPerm['CanSearch']; ?>)</option>
	<option value="group">use group info</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
<?php } ?>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanDoHTML">Can DoHTML:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanDoHTML" id="CanDoHTML">
	<option <?php if($EditMemPerm['CanDoHTML']=="group") { echo "selected=\"selected\" "; } ?>value="group">use group info</option>
	<option <?php if($EditMemPerm['CanDoHTML']=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($EditMemPerm['CanDoHTML']=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanUseBBags">Can use BBags:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanUseBBags" id="CanUseBBags">
	<option <?php if($EditMemPerm['CanUseBBags']=="group") { echo "selected=\"selected\" "; } ?>value="group">use group info</option>
	<option <?php if($EditMemPerm['CanUseBBags']=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($EditMemPerm['CanUseBBags']=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
<?php if($EditMem['ID']!=1) { ?>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewIPAddress">Can view IP Address:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewIPAddress" id="CanViewIPAddress">
	<option <?php if($EditMemPerm['CanViewIPAddress']=="group") { echo "selected=\"selected\" "; } ?>value="group">use group info</option>
	<option <?php if($EditMemPerm['CanViewIPAddress']=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($EditMemPerm['CanViewIPAddress']=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewUserAgent">Can view user agent:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewUserAgent" id="CanViewUserAgent">
	<option <?php if($EditMemPerm['CanViewUserAgent']=="group") { echo "selected=\"selected\" "; } ?>value="group">use group info</option>
	<option <?php if($EditMemPerm['CanViewUserAgent']=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($EditMemPerm['CanViewUserAgent']=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
<?php } ?>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="FloodControl">Flood Control in seconds:</label></td>
	<td style="width: 50%;"><input type="text" name="FloodControl" class="TextBox" id="FloodControl" size="20" value="<?php echo $EditMemPerm['FloodControl']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="SearchFlood">Search Flood Control in seconds:</label></td>
	<td style="width: 50%;"><input type="text" name="SearchFlood" class="TextBox" id="SearchFlood" size="20" value="<?php echo $EditMemPerm['SearchFlood']; ?>" /></td>
<?php if($EditMem['ID']!=1) { ?>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="HasModCP">Can view Mod CP:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="HasModCP" id="HasModCP">
	<option selected="selected" value="<?php echo $EditMemPerm['HasModCP']; ?>">Old Value (<?php echo $EditMemPerm['HasModCP']; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="HasAdminCP">Can view Admin CP:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="HasAdminCP" id="HasAdminCP">
	<option selected="selected" value="<?php echo $EditMemPerm['HasAdminCP']; ?>">Old Value (<?php echo $EditMemPerm['HasAdminCP']; ?>)</option>
	<option value="group">use group info</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ViewDBInfo">Can view Database info:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ViewDBInfo" id="ViewDBInfo">
	<option selected="selected" value="<?php echo $EditMemPerm['ViewDBInfo']; ?>">Old Value (<?php echo $EditMemPerm['ViewDBInfo']; ?>)</option>
	<option value="group">use group info</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
<?php } ?>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="editmember" style="display: none;" />
<input type="hidden" name="id" value="<?php echo $_POST['id']; ?>" style="display: none;" />
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
	($_POST['id']!="0"||$_POST['id']!="-1")) { 
$ggidquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"Name\"='%s' LIMIT 1", array($Settings['GuestGroup']));
$ggidresult=sql_query($ggidquery,$SQLStat);
$GuestGroupID=sql_result($ggidresult,0,"id");
sql_free_result($ggidresult);
$vgidquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"Name\"='%s' LIMIT 1", array($Settings['ValidateGroup']));
$vgidresult=sql_query($vgidquery,$SQLStat);
$ValidateGroupID=sql_result($vgidresult,0,"id");
sql_free_result($vgidresult);
$DMemName = GetUserName($_POST['id'],$Settings['sqltable']);
$DMemName = $DMemName['Name'];
$_POST['MemName'] = stripcslashes(htmlspecialchars($_POST['MemName'], ENT_QUOTES, $Settings['charset']));
//$_POST['MemName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['MemName']);
$_POST['MemName'] = remove_spaces($_POST['MemName']);
$_POST['MemEmail'] = remove_spaces($_POST['MemEmail']);
$username_check = null;
if($_POST['MemName']!=$DMemName) {
$tquery = sql_pre_query("UPDATE \"".$Settings['sqltable']."topics\" SET \"GuestName\"='%s' WHERE \"UserID\"=%i", array($_POST['MemName'],$_POST['id']));
sql_query($tquery,$SQLStat);
$r1query = sql_pre_query("UPDATE \"".$Settings['sqltable']."posts\" SET \"GuestName\"='%s' WHERE \"UserID\"=%i", array($_POST['MemName'],$_POST['id']));
sql_query($r1query,$SQLStat);
$r2query = sql_pre_query("UPDATE \"".$Settings['sqltable']."posts\" SET \"EditUserName\"='%s' WHERE \"EditUser\"=%i", array($_POST['MemName'],$_POST['id']));
sql_query($r2query,$SQLStat);
$sql_username_check = sql_query(sql_pre_query("SELECT \"Name\" FROM \"".$Settings['sqltable']."members\" WHERE \"Name\"='%s'", array($_POST['MemName'])),$SQLStat);
$username_check = sql_num_rows($sql_username_check);
sql_free_result($sql_username_check); }
if($_POST['MemHidden']!="yes"&&$_POST['MemHidden']!="no") {
	$_POST['MemHidden'] = "no"; }
if(!is_numeric($_POST['MemWarnLevel'])) { $_POST['MemWarnLevel'] = "0"; }
if(!is_numeric($_POST['MemPostCount'])) { $_POST['MemPostCount'] = "0"; }
if(!is_numeric($_POST['MemKarma'])) { $_POST['MemKarma'] = "0"; }
	if($_POST['MemBanTime']!=null&&$_POST['MemBanTime']>1) {
	$BirthExpl = explode("/",$_POST['MemBanTime']);
	if(count($BirthExpl)!="3") { 
	$_POST['MemBanTime'] = "0"; $BirthExpl[0] = "0"; $BirthExpl[1] = "0"; $BirthExpl[2] = "0"; }
	if(!is_numeric($BirthExpl[0])) { $BirthExpl[0] = "0"; }
	if(!is_numeric($BirthExpl[1])) { $BirthExpl[1] = "0"; }
	if(!is_numeric($BirthExpl[2])) { $BirthExpl[2] = "0"; }
	if(count($BirthExpl)=="3"&&checkdate($BirthExpl[0],$BirthExpl[1],$BirthExpl[2])===true) {
	if(is_numeric($BirthExpl[0])&&is_numeric($BirthExpl[1])&&is_numeric($BirthExpl[2])) {
	if(pre_strlen($BirthExpl[0])=="1") { $BirthExpl[0] = "0".$BirthExpl[0]; }
	if(pre_strlen($BirthExpl[1])=="1") { $BirthExpl[1] = "0".$BirthExpl[1]; }
	if(pre_strlen($BirthExpl[0])=="2"&&pre_strlen($BirthExpl[1])=="2"&&pre_strlen($BirthExpl[2])=="4") {
	$BirthIn = mktime(12,12,12,$BirthExpl[0],$BirthExpl[1],$BirthExpl[2]);
	$BirthMonth=GMTimeChange("m",$BirthIn,0,0,"off");
	$BirthDay=GMTimeChange("d",$BirthIn,0,0,"off");
	$BirthYear=GMTimeChange("Y",$BirthIn,0,0,"off"); 
	$_POST['MemBanTime'] = $BirthIn; }
	if(pre_strlen($BirthExpl[0])!="2"||pre_strlen($BirthExpl[1])!="2"||pre_strlen($BirthExpl[2])!="4") { 
		$_POST['MemBanTime'] = "0"; $BirthMonth="0"; $BirthDay="0"; $BirthYear="0"; } }
	if (!is_numeric($BirthExpl[0])||!is_numeric($BirthExpl[1])||!is_numeric($BirthExpl[2])) { 
		$_POST['MemBanTime'] = "0"; $BirthMonth="0"; $BirthDay="0"; $BirthYear="0"; } }
	if(count($BirthExpl)=="3"&&
	checkdate($BirthExpl[0],$BirthExpl[1],$BirthExpl[2])===false) {
	$_POST['MemBanTime'] = "0"; $BirthMonth="0"; $BirthDay="0"; $BirthYear="0"; }
	if(count($BirthExpl)!="3") { 
	$_POST['MemBanTime'] = "0"; $BirthMonth="0"; $BirthDay="0"; $BirthYear="0"; } }
if($DMemName!==null&&($_POST['id']!="0"||$_POST['id']!="-1")&&
	($_POST['gid']!=$GuestGroupID||$_POST['gid']!=$ValidateGroupID)) { 
if($_POST['MemName']==$DMemName||$username_check>=1) {
if($_POST['id']!=1) {
if(!is_numeric($_POST['MemPermID'])) { $_POST['MemPermID'] = "0"; }
$dmquery = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"GroupID\"=%i,\"HiddenMember\"='%s',\"WarnLevel\"=%i,\"BanTime\"=%i,\"PostCount\"=%i,\"Karma\"=%i WHERE \"id\"=%i", array($_POST['gid'],$_POST['MemHidden'],$_POST['MemWarnLevel'],$_POST['MemBanTime'],$_POST['MemPostCount'],$_POST['MemKarma'],$_POST['id'])); 
$dpmquery = sql_pre_query("UPDATE \"".$Settings['sqltable']."mempermissions\" SET \"PermissionID\"=%i,\"CanViewBoard\"='%s',\"CanViewOffLine\"='%s',\"CanEditProfile\"='%s',\"CanAddEvents\"='%s',\"CanPM\"='%s',\"CanSearch\"='%s',\"CanDoHTML\"='%s',\"CanUseBBags\"='%s',\"CanViewIPAddress\"='%s',\"CanViewUserAgent\"='%s',\"FloodControl\"=%i,\"SearchFlood\"=%i,\"HasModCP\"='%s',\"HasAdminCP\"='%s',\"ViewDBInfo\"='%s' WHERE \"id\"=%i", array($_POST['MemPermID'],$_POST['CanViewBoard'],$_POST['CanViewOffLine'],$_POST['CanEditProfile'],$_POST['CanAddEvents'],$_POST['CanPM'],$_POST['CanSearch'],$_POST['CanDoHTML'],$_POST['CanUseBBags'],$_POST['CanViewIPAddress'],$_POST['CanViewUserAgent'],$_POST['FloodControl'],$_POST['SearchFlood'],$_POST['HasModCP'],$_POST['HasAdminCP'],$_POST['ViewDBInfo'],$_POST['id'])); }
if($_POST['id']==1) {
$dmquery = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"HiddenMember\"='%s',\"WarnLevel\"=%i,\"BanTime\"=%i,\"PostCount\"=%i,\"Karma\"=%i WHERE \"id\"=%i", array($_POST['MemHidden'],$_POST['MemWarnLevel'],$_POST['MemBanTime'],$_POST['MemPostCount'],$_POST['MemKarma'],$_POST['id'])); 
$dpmquery = sql_pre_query("UPDATE \"".$Settings['sqltable']."mempermissions\" SET \"CanDoHTML\"='%s',\"CanUseBBags\"='%s',\"FloodControl\"=%i,\"SearchFlood\"=%i WHERE \"id\"=%i", array($_POST['CanDoHTML'],$_POST['CanUseBBags'],$_POST['FloodControl'],$_POST['SearchFlood'],$_POST['id'])); } }
if($_POST['MemName']!=$DMemName&&$username_check<1) {
if($_POST['id']!=1) {
if(!is_numeric($_POST['MemPermID'])) { $_POST['MemPermID'] = "0"; }
$dmquery = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"Name\"='%s',\"GroupID\"=%i,\"HiddenMember\"='%s',\"WarnLevel\"=%i,\"BanTime\"=%i,\"PostCount\"=%i,\"Karma\"=%i WHERE \"id\"=%i", array($_POST['MemName'],$_POST['gid'],$_POST['MemHidden'],$_POST['MemWarnLevel'],$_POST['MemBanTime'],$_POST['MemPostCount'],$_POST['MemKarma'],$_POST['id'])); 
$dpmquery = sql_pre_query("UPDATE \"".$Settings['sqltable']."mempermissions\" SET \"PermissionID\"=%i,\"CanViewBoard\"='%s',\"CanViewOffLine\"='%s',\"CanEditProfile\"='%s',\"CanAddEvents\"='%s',\"CanPM\"='%s',\"CanSearch\"='%s',\"CanDoHTML\"='%s',\"CanUseBBags\"='%s',\"CanViewIPAddress\"='%s',\"CanViewUserAgent\"='%s',\"FloodControl\"=%i,\"SearchFlood\"=%i,\"HasModCP\"='%s',\"HasAdminCP\"='%s',\"ViewDBInfo\"='%s' WHERE \"id\"=%i", array($_POST['MemPermID'],$_POST['CanViewBoard'],$_POST['CanViewOffLine'],$_POST['CanEditProfile'],$_POST['CanAddEvents'],$_POST['CanPM'],$_POST['CanSearch'],$_POST['CanDoHTML'],$_POST['CanUseBBags'],$_POST['CanViewIPAddress'],$_POST['CanViewUserAgent'],$_POST['FloodControl'],$_POST['SearchFlood'],$_POST['HasModCP'],$_POST['HasAdminCP'],$_POST['ViewDBInfo'],$_POST['id'])); } 
if($_POST['id']==1) {
$dmquery = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"Name\"='%s',\"HiddenMember\"='%s',\"WarnLevel\"=%i,\"BanTime\"=%i,\"PostCount\"=%i,\"Karma\"=%i WHERE \"id\"=%i", array($_POST['MemName'],$_POST['MemHidden'],$_POST['MemWarnLevel'],$_POST['MemBanTime'],$_POST['MemPostCount'],$_POST['MemKarma'],$_POST['id'])); 
$dpmquery = sql_pre_query("UPDATE \"".$Settings['sqltable']."mempermissions\" SET \"CanViewBoard\"='%s',\"CanViewOffLine\"='%s',\"CanEditProfile\"='%s',\"CanAddEvents\"='%s',\"CanPM\"='%s',\"CanSearch\"='%s',\"CanDoHTML\"='%s',\"CanUseBBags\"='%s',\"CanViewIPAddress\"='%s',\"CanViewUserAgent\"='%s',\"FloodControl\"=%i,\"SearchFlood\"=%i WHERE \"id\"=%i", array($_POST['CanViewBoard'],$_POST['CanViewOffLine'],$_POST['CanEditProfile'],$_POST['CanAddEvents'],$_POST['CanPM'],$_POST['CanSearch'],$_POST['CanDoHTML'],$_POST['CanUseBBags'],$_POST['CanViewIPAddress'],$_POST['CanViewUserAgent'],$_POST['FloodControl'],$_POST['SearchFlood'],$_POST['id'])); } }
sql_query($dmquery,$SQLStat);
sql_query($dpmquery,$SQLStat); }
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
	<br /><?php echo $DMemName; ?>&#39;s member info was changed successfully.<br /> <a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=members",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to back to admin cp.<br />&nbsp;
	</div>
</td></tr>
<tr id="ProfileTitleEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr></table></div>
<?php } ?>
</td></tr>
</table>
<div>&nbsp;</div>
