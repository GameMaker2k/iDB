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

    $FileInfo: groups.php - Last Update: 01/19/2010 SVN 441 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="groups.php"||$File3Name=="/groups.php") {
	require('index.php');
	exit(); }

// Check if we can goto admin cp
if($_SESSION['UserGroup']==$Settings['GuestGroup']||$GroupInfo['HasAdminCP']=="no") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if(!isset($_POST['update'])) { $_POST['update'] = null; }
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
<?php if($_GET['act']=="addgroup"&&$_POST['update']!="now") { 
$admincptitle = " ".$ThemeSet['TitleDivider']." Adding new Group";
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=addgroup",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Group Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=addgroup",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Group Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Adding new Group: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=addgroup",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="GroupName">Insert name for group:</label></td>
	<td style="width: 50%;"><input type="text" name="GroupName" class="TextBox" id="GroupName" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="GroupPerm">Copy Permissions from:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="GroupPerm" id="GroupPerm">
	<option selected="selected" value="0">none</option>
<?php 
$getperidq = sql_pre_query("SELECT DISTINCT \"PermissionID\" FROM \"".$Settings['sqltable']."permissions\"", array(null));
$getperidr=sql_query($getperidq,$SQLStat);
$getperidnum=sql_num_rows($getperidr);
$getperidi = 0;
while ($getperidi < $getperidnum) {
if($Settings['sqltype']=="mysql"||$Settings['sqltype']=="mysqli"
	||$Settings['sqltype']=="pgsql") {
$getperidID=sql_result($getperidr,$getperidi,"PermissionID"); }
if($Settings['sqltype']=="sqlite") {
$getperidID=sql_result($getperidr,$getperidi,"\"PermissionID\""); }
$getperidq2 = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."permissions\" WHERE \"PermissionID\"=%i ORDER BY \"ForumID\" ASC", array($getperidID));
$getperidr2=sql_query($getperidq2,$SQLStat);
$getperidnum2=sql_num_rows($getperidr2);
$getperidName=sql_result($getperidr2,0,"Name");
sql_free_result($getperidr2);
?>
	<option value="<?php echo $getperidID; ?>"><?php echo $getperidName; ?></option>
<?php ++$getperidi; }
sql_free_result($getperidr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="PermissionID">Permission ID:</label></td>
	<td style="width: 50%;"><input type="text" name="PermissionID" class="TextBox" id="PermissionID" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NamePrefix">Name Prefix:</label></td>
	<td style="width: 50%;"><input type="text" name="NamePrefix" class="TextBox" id="NamePrefix" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NameSuffix">Name Subfix:</label></td>
	<td style="width: 50%;"><input type="text" name="NameSuffix" class="TextBox" id="NameSuffix" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewBoard">Can View Board:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewBoard" id="CanViewBoard">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewOffLine">Can View OffLine Board:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewOffLine" id="CanViewOffLine">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanEditProfile">Can Edit Profile:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanEditProfile" id="CanEditProfile">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanAddEvents">Can Add Events:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanAddEvents" id="CanAddEvents">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanPM">Can PM:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanPM" id="CanPM">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanSearch">Can Search:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanSearch" id="CanSearch">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="FloodControl">Flood Control in seconds:</label></td>
	<td style="width: 50%;"><input type="text" name="FloodControl" class="TextBox" id="FloodControl" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="SearchFlood">Search Flood Control in seconds:</label></td>
	<td style="width: 50%;"><input type="text" name="SearchFlood" class="TextBox" id="SearchFlood" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="PromoteTo">Promote To Group:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="PromoteTo" id="PromoteTo">
	<option selected="selected" value="0">none</option>
<?php 
$fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" ORDER BY \"id\" ASC", array(null));
$fr=sql_query($fq,$SQLStat);
$ai=sql_num_rows($fr);
$fi=0;
while ($fi < $ai) {
$ProGroupID=sql_result($fr,$fi,"id");
$ProGroupName=sql_result($fr,$fi,"Name");
?>
	<option value="<?php echo $ProGroupID; ?>"><?php echo $ProGroupName; ?></option>
<?php ++$fi; }
sql_free_result($fr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="PromotePosts">Amount of Posts needed:</label></td>
	<td style="width: 50%;"><input type="text" name="PromotePosts" class="TextBox" id="PromotePosts" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="PromoteKarma">Amount of Karma needed:</label></td>
	<td style="width: 50%;"><input type="text" name="PromoteKarma" class="TextBox" id="PromoteKarma" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="HasModCP">Can view Mod CP:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="HasModCP" id="HasModCP">
	<option selected="selected" value="off">no</option>
	<option value="on">yes</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="HasAdminCP">Can view Admin CP:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="HasAdminCP" id="HasAdminCP">
	<option selected="selected" value="off">no</option>
	<option value="on">yes</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ViewDBInfo">Can view Database info:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ViewDBInfo" id="ViewDBInfo">
	<option selected="selected" value="off">no</option>
	<option value="on">yes</option>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="addgroup" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Add Group" name="Apply_Changes" />
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
<?php } if($_POST['act']=="addgroup"&&$_POST['update']=="now"&&$_GET['act']=="addgroup") {
$_POST['GroupName'] = stripcslashes(htmlspecialchars($_POST['GroupName'], ENT_QUOTES, $Settings['charset']));
//$_POST['GroupName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['GroupName']);
$_POST['GroupName'] = remove_spaces($_POST['GroupName']);
$_POST['NamePrefix'] = stripcslashes(htmlspecialchars($_POST['NamePrefix'], ENT_QUOTES, $Settings['charset']));
//$_POST['NamePrefix'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['NamePrefix']);
$_POST['NamePrefix'] = remove_spaces($_POST['NamePrefix']);
$_POST['NameSuffix'] = stripcslashes(htmlspecialchars($_POST['NameSuffix'], ENT_QUOTES, $Settings['charset']));
//$_POST['NameSuffix'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['NameSuffix']);
$_POST['NameSuffix'] = remove_spaces($_POST['NameSuffix']);
$sql_name_check = sql_query(sql_pre_query("SELECT \"Name\" FROM \"".$Settings['sqltable']."groups\" WHERE \"Name\"='%s'", array($_POST['GroupName'])),$SQLStat);
$sql_id_check = sql_query(sql_pre_query("SELECT \"id\" FROM \"".$Settings['sqltable']."permissions\" WHERE \"PermissionID\"=%i LIMIT 1", array($_POST['PermissionID'])),$SQLStat);
$name_check = sql_num_rows($sql_name_check); $id_check = sql_num_rows($sql_id_check);
sql_free_result($sql_name_check);
$errorstr = "";
if ($_POST['PromotePosts']==null||
	!is_numeric($_POST['PromotePosts'])) {
	$_POST['PromotePosts'] = 0; }
if ($_POST['PromoteKarma']==null||
	!is_numeric($_POST['PromoteKarma'])) {
	$_POST['NPromoteKarma'] = 0; }
if ($_POST['GroupName']==null||
	$_POST['GroupName']=="ShowMe") { $Error="Yes";
$errorstr = $errorstr."You need to enter a forum name.<br />\n"; } 
if($id_check > 0) { $Error="Yes";
$errorstr = $errorstr."This ID number is already used.<br />\n"; } 
if($name_check > 0) { $Error="Yes";
$errorstr = $errorstr."This Group Name is already used.<br />\n"; } 
if (pre_strlen($_POST['GroupName'])>"150") { $Error="Yes";
$errorstr = $errorstr."Your Group Name is too big.<br />\n"; } 
if ($Error!="Yes") {
redirect("refresh",$basedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=groups",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],FALSE),"4");
$admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
$query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."groups\" (\"Name\", \"PermissionID\", \"NamePrefix\", \"NameSuffix\", \"CanViewBoard\", \"CanViewOffLine\", \"CanEditProfile\", \"CanAddEvents\", \"CanPM\", \"CanSearch\", \"FloodControl\", \"SearchFlood\", \"PromoteTo\", \"PromotePosts\", \"PromoteKarma\", \"HasModCP\", \"HasAdminCP\", \"ViewDBInfo\") VALUES\n".
"('%s', %i, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %i, %i, %i, %i, %i, '%s', '%s', '%s')", array($_POST['GroupName'],$_POST['PermissionID'],$_POST['NamePrefix'],$_POST['NameSuffix'],$_POST['CanViewBoard'],$_POST['CanViewOffLine'],$_POST['CanEditProfile'],$_POST['CanAddEvents'],$_POST['CanPM'],$_POST['CanSearch'],$_POST['FloodControl'],$_POST['SearchFlood'],$_POST['PromoteTo'],$_POST['PromotePosts'],$_POST['PromoteKarma'],$_POST['HasModCP'],$_POST['HasAdminCP'],$_POST['ViewDBInfo']));
sql_query($query,$SQLStat);
if(!is_numeric($_POST['GroupPerm'])) { $_POST['GroupPerm'] = "0"; }
$getperidq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" ORDER BY \"id\" ASC", array(null));
$getperidr=sql_query($getperidq,$SQLStat);
$getperidnum=sql_num_rows($getperidr);
$getperidi = 0; 
$nextperid = null;
/*
if($Settings['sqltype']=="mysql"||$Settings['sqltype']=="mysqli"
	||$Settings['sqltype']=="pgsql") {
$nextperid = sql_get_next_id($Settings['sqltable'],"permissions",$SQLStat); }
if($Settings['sqltype']=="sqlite") {
$nextperid = sql_get_next_id($Settings['sqltable'],"\"permissions\"",$SQLStat); }
*/
while ($getperidi < $getperidnum) {
$getperidID=sql_result($getperidr,$getperidi,"id");
if($_POST['GroupPerm']!="0") {
$getperidq2 = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."permissions\" WHERE \"PermissionID\"=%i AND \"ForumID\"=%i", array($_POST['GroupPerm'],$getperidID));
$getperidr2=sql_query($getperidq2,$SQLStat);
$getperidnum2=sql_num_rows($getperidr2);
$PermissionNum=sql_result($getperidr2,0,"id"); 
$PermissionID=$_POST['PermissionID']; 
$PermissionName=$_POST['GroupName']; 
$PermissionForumID=sql_result($getperidr2,0,"ForumID"); 
$CanViewForum=sql_result($getperidr2,0,"CanViewForum"); 
$CanMakeTopics=sql_result($getperidr2,0,"CanMakeTopics"); 
$CanMakeReplys=sql_result($getperidr2,0,"CanMakeReplys"); 
$CanMakeReplysCT=sql_result($getperidr2,0,"CanMakeReplysCT"); 
$CanEditTopics=sql_result($getperidr2,0,"CanEditTopics"); 
$CanEditTopicsCT=sql_result($getperidr2,0,"CanEditTopicsCT"); 
$CanEditReplys=sql_result($getperidr2,0,"CanEditReplys"); 
$CanEditReplysCT=sql_result($getperidr2,0,"CanEditReplysCT"); 
$CanDeleteTopics=sql_result($getperidr2,0,"CanDeleteTopics"); 
$CanDeleteTopicsCT=sql_result($getperidr2,0,"CanDeleteTopicsCT"); 
$CanDeleteReplys=sql_result($getperidr2,0,"CanDeleteReplys"); 
$CanDeleteReplysCT=sql_result($getperidr2,0,"CanDeleteReplysCT"); 
$CanCloseTopics=sql_result($getperidr2,0,"CanCloseTopics"); 
$CanPinTopics=sql_result($getperidr2,0,"CanPinTopics"); 
$CanDohtml=sql_result($getperidr2,0,"CanDohtml"); 
$CanUseBBags=sql_result($getperidr2,0,"CanUseBBags"); 
$CanModForum=sql_result($getperidr2,0,"CanModForum"); 
sql_free_result($getperidr2); }
if($_POST['GroupPerm']=="0") {
$PermissionID=$_POST['PermissionID']; 
$PermissionName=$_POST['GroupName']; 
$query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."permissions\" (\"PermissionID\", \"Name\", \"ForumID\", \"CanViewForum\", \"CanMakeTopics\", \"CanMakeReplys\", \"CanMakeReplysCT\", \"CanEditTopics\", \"CanEditTopicsCT\", \"CanEditReplys\", \"CanEditReplysCT\", \"CanDeleteTopics\", \"CanDeleteTopicsCT\", \"CanDeleteReplys\", \"CanDeleteReplysCT\", \"CanCloseTopics\", \"CanPinTopics\", \"CanDohtml\", \"CanUseBBags\", \"CanModForum\") VALUES (%i, '%s', %i, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no')", array($PermissionID,$PermissionName,$getperidID)); }
if($_POST['GroupPerm']!="0") {
if($getperidnum2>0) {
$query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."permissions\" (\"PermissionID\", \"Name\", \"ForumID\", \"CanViewForum\", \"CanMakeTopics\", \"CanMakeReplys\", \"CanMakeReplysCT\", \"CanEditTopics\", \"CanEditTopicsCT\", \"CanEditReplys\", \"CanEditReplysCT\", \"CanDeleteTopics\", \"CanDeleteTopicsCT\", \"CanDeleteReplys\", \"CanDeleteReplysCT\", \"CanCloseTopics\", \"CanPinTopics\", \"CanDohtml\", \"CanUseBBags\", \"CanModForum\") VALUES (%i, '%s', %i, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", array($PermissionID,$PermissionName,$getperidID,$CanViewForum,$CanMakeTopics,$CanMakeReplys,$CanMakeReplysCT,$CanEditTopics,$CanEditTopicsCT,$CanEditReplys,$CanEditReplysCT,$CanDeleteTopics,$CanDeleteTopicsCT,$CanDeleteReplys,$CanDeleteReplysCT,$CanCloseTopics,$CanPinTopics,$CanDohtml,$CanUseBBags,$CanModForum)); }
if($getperidnum2<=0) {
$query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."permissions\" (\"PermissionID\", \"Name\", \"ForumID\", \"CanViewForum\", \"CanMakeTopics\", \"CanMakeReplys\", \"CanMakeReplysCT\", \"CanEditTopics\", \"CanEditTopicsCT\", \"CanEditReplys\", \"CanEditReplysCT\", \"CanDeleteTopics\", \"CanDeleteTopicsCT\", \"CanDeleteReplys\", \"CanDeleteReplysCT\", \"CanCloseTopics\", \"CanPinTopics\", \"CanDohtml\", \"CanUseBBags\", \"CanModForum\") VALUES (%i, '%s', %i, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no')", array($PermissionID,$PermissionName,$getperidID)); } }
sql_query($query,$SQLStat);
++$getperidi; /*++$nextperid;*/ }
sql_free_result($getperidr);
if(!is_numeric($_POST['GroupPerm'])) { $_POST['GroupPerm'] = "0"; }
$getperidq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" ORDER BY \"id\" ASC", array(null));
$getperidr=sql_query($getperidq,$SQLStat);
$getperidnum=sql_num_rows($getperidr);
$getperidi = 0; 
$nextperid = null;
/*
if($Settings['sqltype']=="mysql"||$Settings['sqltype']=="mysqli"
	||$Settings['sqltype']=="pgsql") {
$nextperid = sql_get_next_id($Settings['sqltable'],"permissions",$SQLStat); }
if($Settings['sqltype']=="sqlite") {
$nextperid = sql_get_next_id($Settings['sqltable'],"\"permissions\"",$SQLStat); }
*/
while ($getperidi < $getperidnum) {
$getperidID=sql_result($getperidr,$getperidi,"id");
if($_POST['GroupPerm']!="0") {
$getperidq2 = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."catpermissions\" WHERE \"PermissionID\"=%i AND \"CategoryID\"=%i", array($_POST['GroupPerm'],$getperidID));
$getperidr2=sql_query($getperidq2,$SQLStat);
$getperidnum2=sql_num_rows($getperidr2);
$PermissionNum=sql_result($getperidr2,0,"id"); 
$PermissionID=$_POST['PermissionID']; 
$PermissionName=$_POST['GroupName']; 
$PermissionCatID=sql_result($getperidr2,0,"CategoryID"); 
$CanViewCategory=sql_result($getperidr2,0,"CanViewCategory"); 
sql_free_result($getperidr2); }
if($_POST['GroupPerm']=="0") {
$PermissionID=$_POST['PermissionID']; 
$PermissionName=$_POST['GroupName']; 
$query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."catpermissions\" (\"PermissionID\", \"Name\", \"CategoryID\", \"CanViewCategory\") VALUES (%i, '%s', %i, 'yes')", array($PermissionID,$PermissionName,$getperidID)); }
if($_POST['GroupPerm']!="0") {
if($getperidnum2>0) {
$query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."catpermissions\" (\"PermissionID\", \"Name\", \"CategoryID\", \"CanViewCategory\") VALUES (%i, '%s', %i, '%s')", array($PermissionID,$PermissionName,$getperidID,$CanViewCategory)); }
if($getperidnum2<=0) {
$query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."catpermissions\" (\"PermissionID\", \"Name\", \"CategoryID\", \"CanViewCategory\") VALUES (%i, '%s', %i, 'yes')", array($PermissionID,$PermissionName,$getperidID)); } }
sql_query($query,$SQLStat);
++$getperidi; /*++$nextperid;*/ }
sql_free_result($getperidr); } } 
if($_GET['act']=="deletegroup"&&$_POST['update']!="now") { 
$admincptitle = " ".$ThemeSet['TitleDivider']." Deleting a Forum";
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=addgroup",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Group Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=addgroup",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Group Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Deleting a Group: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=deletegroup",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelID">Delete Group:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelID" id="DelID">
<?php 
$fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE (\"Name\"<>'%s' AND \"Name\"<>'%s' AND \"Name\"<>'%s' AND \"Name\"<>'%s') ORDER BY \"id\" ASC", array($Settings['GuestGroup'],$Settings['MemberGroup'],$Settings['ValidateGroup'],"Admin"));
$fr=sql_query($fq,$SQLStat);
$ai=sql_num_rows($fr);
$fi=0;
while ($fi < $ai) {
$GroupID=sql_result($fr,$fi,"id");
$GroupName=sql_result($fr,$fi,"Name");
?>
	<option value="<?php echo $GroupID; ?>"><?php echo $GroupName; ?></option>
<?php ++$fi; }
sql_free_result($fr); ?>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="deletegroup" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Delete Group" name="Apply_Changes" />
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
<?php } if($_GET['act']=="deletegroup"&&$_POST['update']=="now"&&$_GET['act']=="deletegroup") { 
$admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
$prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i AND (\"Name\"<>'%s' AND \"Name\"<>'%s' AND \"Name\"<>'%s' AND \"Name\"<>'%s') LIMIT 1", array($_POST['DelID'],$Settings['GuestGroup'],$Settings['MemberGroup'],$Settings['ValidateGroup'],"Admin"));
$preresult=sql_query($prequery,$SQLStat);
$prenum=sql_num_rows($preresult);
$GroupName=sql_result($preresult,0,"Name");
$errorstr = ""; $Error = null;
if (!is_numeric($_POST['DelID'])) { $Error="Yes";
$errorstr = $errorstr."You need to enter a group ID.<br />\n"; } 
if($prenum>0&&$Error!="Yes") {
$dtquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i", array($_POST['DelID']));
sql_query($dtquery,$SQLStat);
$dtquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."catpermissions\" WHERE \"Name\"='%s'", array($GroupName));
sql_query($dtquery,$SQLStat);
$dtquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."permissions\" WHERE \"Name\"='%s'", array($GroupName));
sql_query($dtquery,$SQLStat);
$gquerys = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"Name\"='%s' LIMIT 1", array($Settings['MemberGroup']));
$gresults=sql_query($gquerys,$SQLStat);
$MemGroup=sql_result($gresults,0,"id");
sql_free_result($gresults);
$dtquery = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"GroupID\"=%i WHERE \"GroupID\"=%i", array($MemGroup,$_POST['DelID']));
sql_query($dtquery,$SQLStat); } }
if($_GET['act']=="editgroup"&&$_POST['update']!="now") {
$admincptitle = " ".$ThemeSet['TitleDivider']." Editing a Group";
if(!isset($_POST['id'])) {
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editgroup",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Group Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editgroup",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Group Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing a Group: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editgroup",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="id">Group to Edit:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="id" id="id">
<?php 
$fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" ORDER BY \"id\" ASC", array(null));
$fr=sql_query($fq,$SQLStat);
$ai=sql_num_rows($fr);
$fi=0;
while ($fi < $ai) {
$GroupID=sql_result($fr,$fi,"id");
$GroupName=sql_result($fr,$fi,"Name");
?>
	<option value="<?php echo $GroupID; ?>"><?php echo $GroupName; ?></option>
<?php ++$fi; }
sql_free_result($fr); ?>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="editgroup" style="display: none;" />
<input type="submit" class="Button" value="Edit Group" name="Apply_Changes" />
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
<?php } if(isset($_POST['id'])) { 
$prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($_POST['id']));
$preresult=sql_query($prequery,$SQLStat);
$prenum=sql_num_rows($preresult);
if($prenum==0) { redirect("location",$basedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],false)); sql_free_result($preresult);
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($prenum>=1) {
$GroupID = sql_result($preresult,0,"id");
$GroupName = sql_result($preresult,0,"Name");
$PermissionID = sql_result($preresult,0,"PermissionID");
$NamePrefix = sql_result($preresult,0,"NamePrefix");
$NameSuffix = sql_result($preresult,0,"NameSuffix");
$CanViewBoard = sql_result($preresult,0,"CanViewBoard");
$CanViewOffLine = sql_result($preresult,0,"CanViewOffLine");
$CanEditProfile = sql_result($preresult,0,"CanEditProfile");
$CanAddEvents = sql_result($preresult,0,"CanAddEvents");
$CanPM = sql_result($preresult,0,"CanPM");
$CanSearch = sql_result($preresult,0,"CanSearch");
$FloodControl = sql_result($preresult,0,"FloodControl");
$SearchFlood = sql_result($preresult,0,"SearchFlood");
$PromoteTo = sql_result($preresult,0,"PromoteTo");
$PromotePosts = sql_result($preresult,0,"PromotePosts");
$PromoteKarma = sql_result($preresult,0,"PromoteKarma");
$HasModCP = sql_result($preresult,0,"HasModCP");
$HasAdminCP = sql_result($preresult,0,"HasAdminCP");
$ViewDBInfo = sql_result($preresult,0,"ViewDBInfo");
sql_free_result($preresult);
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editgroup",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Group Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editgroup",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Group Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing a Group: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editgroup",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="GroupName">Insert name for group:</label></td>
	<td style="width: 50%;"><input type="text" name="GroupName" class="TextBox" id="GroupName" size="20" value="<?php echo $GroupName; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NamePrefix">Name Prefix:</label></td>
	<td style="width: 50%;"><input type="text" name="NamePrefix" class="TextBox" id="NamePrefix" size="20" value="<?php echo $NamePrefix; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NameSuffix">Name Subfix:</label></td>
	<td style="width: 50%;"><input type="text" name="NameSuffix" class="TextBox" id="NameSuffix" size="20" value="<?php echo $NameSuffix; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewBoard">Can View Board:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewBoard" id="CanViewBoard">
	<option selected="selected" value="<?php echo $CanViewBoard; ?>">Old Value (<?php echo $CanViewBoard; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewOffLine">Can View OffLine Board:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewOffLine" id="CanViewOffLine">
	<option selected="selected" value="<?php echo $CanViewOffLine; ?>">Old Value (<?php echo $CanViewOffLine; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanEditProfile">Can Edit Profile:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanEditProfile" id="CanEditProfile">
	<option selected="selected" value="<?php echo $CanEditProfile; ?>">Old Value (<?php echo $CanEditProfile; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanAddEvents">Can Add Events:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanAddEvents" id="CanAddEvents">
	<option selected="selected" value="<?php echo $CanAddEvents; ?>">Old Value (<?php echo $CanAddEvents; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanPM">Can PM:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanPM" id="CanPM">
	<option selected="selected" value="<?php echo $CanPM; ?>">Old Value (<?php echo $CanPM; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanSearch">Can Search:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanSearch" id="CanSearch">
	<option selected="selected" value="<?php echo $CanSearch; ?>">Old Value (<?php echo $CanSearch; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="FloodControl">Flood Control in seconds:</label></td>
	<td style="width: 50%;"><input type="text" name="FloodControl" class="TextBox" id="FloodControl" size="20" value="<?php echo $FloodControl; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="SearchFlood">Search Flood Control in seconds:</label></td>
	<td style="width: 50%;"><input type="text" name="SearchFlood" class="TextBox" id="SearchFlood" size="20" value="<?php echo $SearchFlood; ?>" /></td>
<?php if($_POST['id']!=1) { ?>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="PromoteTo">Promote To Group:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="PromoteTo" id="PromoteTo">
	<option selected="selected" value="<?php echo $PromoteTo; ?>">Old Value (<?php echo $PromoteTo; ?>)</option>
	<option value="0">none</option>
<?php 
$fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" ORDER BY \"id\" ASC", array(null));
$fr=sql_query($fq,$SQLStat);
$ai=sql_num_rows($fr);
$fi=0;
while ($fi < $ai) {
$ProGroupID=sql_result($fr,$fi,"id");
$ProGroupName=sql_result($fr,$fi,"Name");
?>
	<option value="<?php echo $ProGroupID; ?>"><?php echo $ProGroupName; ?></option>
<?php ++$fi; }
sql_free_result($fr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="PromotePosts">Amount of Posts needed:</label></td>
	<td style="width: 50%;"><input type="text" name="PromotePosts" class="TextBox" id="PromotePosts" size="20" value="<?php echo $PromotePosts; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="PromoteKarma">Amount of Karma needed:</label></td>
	<td style="width: 50%;"><input type="text" name="PromoteKarma" class="TextBox" id="PromoteKarma" size="20" value="<?php echo $PromoteKarma; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="HasModCP">Can view Mod CP:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="HasModCP" id="HasModCP">
	<option selected="selected" value="<?php echo $HasModCP; ?>">Old Value (<?php echo $HasModCP; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="HasAdminCP">Can view Admin CP:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="HasAdminCP" id="HasAdminCP">
	<option selected="selected" value="<?php echo $HasAdminCP; ?>">Old Value (<?php echo $HasAdminCP; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ViewDBInfo">Can view Database info:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ViewDBInfo" id="ViewDBInfo">
	<option selected="selected" value="<?php echo $ViewDBInfo; ?>">Old Value (<?php echo $ViewDBInfo; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
<?php } ?>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="editgroup" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="hidden" name="id" value="<?php echo $GroupID; ?>" style="display: none;" />
<input type="submit" class="Button" value="Edit Group" name="Apply_Changes" />
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
<?php } } } if($_POST['act']=="editgroup"&&$_POST['update']=="now"&&$_GET['act']=="editgroup"&&
	isset($_POST['id'])) {
$_POST['GroupName'] = stripcslashes(htmlspecialchars($_POST['GroupName'], ENT_QUOTES, $Settings['charset']));
//$_POST['GroupName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['GroupName']);
$_POST['GroupName'] = remove_spaces($_POST['GroupName']);
$_POST['NamePrefix'] = stripcslashes(htmlspecialchars($_POST['NamePrefix'], ENT_QUOTES, $Settings['charset']));
//$_POST['NamePrefix'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['NamePrefix']);
$_POST['NamePrefix'] = remove_spaces($_POST['NamePrefix']);
$_POST['NameSuffix'] = stripcslashes(htmlspecialchars($_POST['NameSuffix'], ENT_QUOTES, $Settings['charset']));
//$_POST['NameSuffix'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['NameSuffix']);
$_POST['NameSuffix'] = remove_spaces($_POST['NameSuffix']);
$name_check = 0;
$prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($_POST['id']));
$preresult=sql_query($prequery,$SQLStat);
$prenum=sql_num_rows($preresult);
if($prenum==0) { redirect("location",$basedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],false)); sql_free_result($preresult);
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($prenum>=1) {
$OldGroupName=sql_result($preresult,0,"Name");
sql_free_result($preresult);
if($_POST['GroupName']!=$OldGroupName) {
$sql_name_check = sql_query(sql_pre_query("SELECT \"Name\" FROM \"".$Settings['sqltable']."groups\" WHERE \"Name\"='%s'", array($_POST['GroupName'])),$SQLStat);
$name_check = sql_num_rows($sql_name_check);
sql_free_result($sql_name_check); }
$errorstr = "";
if ($_POST['PromotePosts']==null||
	!is_numeric($_POST['PromotePosts'])) {
	$_POST['PromotePosts'] = 0; }
if ($_POST['PromoteKarma']==null||
	!is_numeric($_POST['PromoteKarma'])) {
	$_POST['NPromoteKarma'] = 0; }
if ($_POST['GroupName']==null||
	$_POST['GroupName']=="ShowMe") { $Error="Yes";
$errorstr = $errorstr."You need to enter a forum name.<br />\n"; } 
if($name_check > 0) { $Error="Yes";
$errorstr = $errorstr."This Group Name is already used.<br />\n"; } 
if (pre_strlen($_POST['GroupName'])>"150") { $Error="Yes";
$errorstr = $errorstr."Your Group Name is too big.<br />\n"; } 
if ($Error!="Yes") {
redirect("refresh",$basedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=groups",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],FALSE),"4");
$admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
if($_POST['GroupName']!=$OldGroupName) {
$query = sql_pre_query("UPDATE \"".$Settings['sqltable']."permissions\" SET \"Name\"='%s' WHERE \"Name\"='%s'", array($_POST['GroupName'],$OldGroupName));
sql_query($query,$SQLStat);
$query = sql_pre_query("UPDATE \"".$Settings['sqltable']."catpermissions\" SET \"Name\"='%s' WHERE \"Name\"='%s'", array($_POST['GroupName'],$OldGroupName));
sql_query($query,$SQLStat); }
if($_POST['id']!=1) {
$query = sql_pre_query("UPDATE \"".$Settings['sqltable']."groups\" SET \"Name\"='%s',\"NamePrefix\"='%s',\"NameSuffix\"='%s',\"CanViewBoard\"='%s',\"CanViewOffLine\"='%s',\"CanEditProfile\"='%s',\"CanAddEvents\"='%s',\"CanPM\"='%s',\"CanSearch\"='%s',\"FloodControl\"=%i,\"SearchFlood\"=%i,\"PromoteTo\"=%i,\"PromotePosts\"=%i,\"PromoteKarma\"=%i,\"HasModCP\"='%s',\"HasAdminCP\"='%s',\"ViewDBInfo\"='%s' WHERE \"id\"=%i", array($_POST['GroupName'],$_POST['NamePrefix'],$_POST['NameSuffix'],$_POST['CanViewBoard'],$_POST['CanViewOffLine'],$_POST['CanEditProfile'],$_POST['CanAddEvents'],$_POST['CanPM'],$_POST['CanSearch'],$_POST['FloodControl'],$_POST['SearchFlood'],$_POST['PromoteTo'],$_POST['PromotePosts'],$_POST['PromoteKarma'],$_POST['HasModCP'],$_POST['HasAdminCP'],$_POST['ViewDBInfo'],$_POST['id'])); }
if($_POST['id']==1) {
$query = sql_pre_query("UPDATE \"".$Settings['sqltable']."groups\" SET \"Name\"='%s',\"NamePrefix\"='%s',\"NameSuffix\"='%s',\"CanViewBoard\"='%s',\"CanViewOffLine\"='%s',\"CanEditProfile\"='%s',\"CanAddEvents\"='%s',\"CanPM\"='%s',\"CanSearch\"='%s',\"FloodControl\"=%i,\"SearchFlood\"=%i WHERE \"id\"=%i", array($_POST['GroupName'],$_POST['NamePrefix'],$_POST['NameSuffix'],$_POST['CanViewBoard'],$_POST['CanViewOffLine'],$_POST['CanEditProfile'],$_POST['CanAddEvents'],$_POST['CanPM'],$_POST['CanSearch'],$_POST['FloodControl'],$_POST['SearchFlood'],$_POST['id'])); }
sql_query($query,$SQLStat); } } }  
$doupdate = false;
if(isset($_POST['id'])&&$_POST['subact']=="editnow") { 
	$doupdate = true; }
if(isset($_POST['id'])&&isset($_POST['permid'])&&$_POST['subact']=="makenow") { 
	$doupdate = true; }
if($_POST['act']=="addgroup"&&$_POST['update']=="now"&&$_GET['act']=="addgroup") { 
	$doupdate = true; }
if($_GET['act']=="deletegroup"&&$_POST['update']=="now"&&$_GET['act']=="deletegroup") { 
	$doupdate = true; }
if($_POST['act']=="editgroup"&&$_POST['update']=="now"&&$_GET['act']=="editgroup"&&
	isset($_POST['id'])) { 
	$doupdate = true; }
if($doupdate===true&&$Error!="Yes") { ?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Updating Settings</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Updating Settings</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr id="ProfileTitle" class="TableMenuRow2">
<th class="TableMenuColumn2">Updating Settings</th>
</tr>
<tr class="TableMenuRow3" id="ProfileUpdate">
<td class="TableMenuColumn3">
<?php if($_POST['act']=="addgroup"&&$_POST['update']=="now"&&$_GET['act']=="addgroup") { ?>
<div style="text-align: center;">
	<br />The group was created successfully. <a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=groups",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&nbsp;
	</div>
<?php } if($_GET['act']=="deletegroup"&&$_POST['update']=="now"&&$_GET['act']=="deletegroup") { ?>
<div style="text-align: center;">
	<br />The group was deleted successfully. <a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=groups",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&nbsp;
	</div>
<?php } if($_POST['act']=="editgroup"&&$_POST['update']=="now"&&$_GET['act']=="editgroup"&&
	isset($_POST['id'])) { ?>
<div style="text-align: center;">
	<br />The group was edited successfully. <a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=groups",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&nbsp;
	</div>
<?php } ?>
</td></tr>
<tr id="ProfileTitleEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr></table></div>
<?php } if ($_GET['act']!=null&&$Error=="Yes") {
redirect("refresh",$basedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=groups",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],FALSE),"4");
$admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=groups",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Updating Settings</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=groups",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Updating Settings</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr id="ProfileTitle" class="TableMenuRow2">
<th class="TableMenuColumn2">Updating Settings</th>
</tr>
<tr class="TableMenuRow3" id="ProfileUpdate">
<td class="TableMenuColumn3">
<div style="text-align: center;">
	<br /><?php echo $errorstr; ?>
	<a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=groups",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to back to admin cp.<br />&nbsp;
	</div>
</td></tr>
<tr id="ProfileTitleEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr></table></div>
<?php } ?>
</td></tr>
</table>
<div>&nbsp;</div>
