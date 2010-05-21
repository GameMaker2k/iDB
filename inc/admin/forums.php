<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2010 iDB Support - http://idb.berlios.de/
    Copyright 2004-2010 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: forums.php - Last Update: 05/21/2010 SVN 495 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="forums.php"||$File3Name=="/forums.php") {
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
<?php if($_GET['act']=="retopics") { 
$admincptitle = " ".$ThemeSet['TitleDivider']." Recounting Topics";
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" ORDER BY \"OrderID\" ASC, \"id\" ASC", array(null));
$result=sql_query($query,$SQLStat);
$num=sql_num_rows($result);
$i=0;
while ($i < $num) {
$ForumID=sql_result($result,$i,"id");
$tquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."topics\" WHERE \"ForumID\"=%i ORDER BY \"Pinned\" DESC, \"LastUpdate\" DESC", array($ForumID));
$tresult=sql_query($tquery,$SQLStat);
$tnum=sql_num_rows($tresult);
$rquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."posts\" WHERE \"ForumID\"=%i ORDER BY \"TimeStamp\" ASC", array($ForumID));
$rresult=sql_query($rquery,$SQLStat);
$rnum=sql_num_rows($rresult);
$query = sql_pre_query("UPDATE \"".$Settings['sqltable']."forums\" SET \"NumPosts\"=%i,\"NumTopics\"=%i WHERE \"id\"=%i", array($rnum,$tnum,$ForumID));
sql_query($query,$SQLStat);
sql_free_result($tresult);
sql_free_result($rresult);
++$i; }
sql_free_result($result);
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Recounting Topics</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Recounting Topics</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Recounting Topics: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<div style="text-align: center;">
	<br />Forums Topics &amp; Posts stats recounted.<br />
	<a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to back to admin cp.<br />&nbsp;
</div>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if($_GET['act']=="rereplies") { 
$admincptitle = " ".$ThemeSet['TitleDivider']." Recounting Replies";
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."topics\" ORDER BY \"Pinned\" DESC, \"LastUpdate\" DESC", array(null));
$result=sql_query($query,$SQLStat);
$num=sql_num_rows($result);
$i=0;
while ($i < $num) {
$TopicID=sql_result($result,$i,"id");
$rquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."posts\" WHERE \"TopicID\"=%i ORDER BY \"TimeStamp\" ASC", array($TopicID));
$rresult=sql_query($rquery,$SQLStat);
$rnum=sql_num_rows($rresult);
$Nrnum = $rnum - 1;
$query = sql_pre_query("UPDATE \"".$Settings['sqltable']."topics\" SET \"NumReply\"=%i WHERE \"id\"=%i", array($Nrnum,$TopicID));
sql_query($query,$SQLStat);
sql_free_result($rresult);
++$i; }
sql_free_result($result);
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Recounting Replies</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Recounting Replies</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Recounting Replies: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<div style="text-align: center;">
	<br />Topics Replys stats recounted.<br />
	<a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to back to admin cp.<br />&nbsp;
</div>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if($_GET['act']=="fixtnames") { 
$admincptitle = " ".$ThemeSet['TitleDivider']." Fixing Topic User Names";
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."topics\" ORDER BY \"TimeStamp\" ASC", array(null));
$result=sql_query($query,$SQLStat);
$num=sql_num_rows($result);
$i=0;
while ($i < $num) {
$TopicID=sql_result($result,$i,"id");
$UsersID=sql_result($result,$i,"UserID");
$GuestsName=sql_result($result,$i,"GuestName");
$NewUserID = $UsersID;
$NewGuestsName = $GuestsName;
$NewGuestsName = GetUserName($NewUserID,$Settings['sqltable']);
$NewGuestsName = $NewGuestsName['Name'];
if($UsersID==-1&&$GuestsName!=null) { $NewGuestsName = $GuestsName; }
if($NewGuestsName==null&&$GuestsName!=null&&$UsersID!==0) {
$NewUserID = -1; $NewGuestsName = $GuestsName; }
if($UsersID==-1&&$GuestsName==null) {
$NewUserID = -1; $NewGuestsName = "Guest"; }
if($UsersID===0&&$GuestsName!=null) {
$NewUserID = -1; $NewGuestsName = "Guest"; }
if($UsersID===0&&$GuestsName==null) {
$NewUserID = -1; $NewGuestsName = "Guest"; }
if($UsersID==$NewUserID&&$GuestsName==$NewGuestsName) {
$NewUserID = $UsersID; $NewGuestsName = $GuestsName; }
$query = sql_pre_query("UPDATE \"".$Settings['sqltable']."topics\" SET \"UserID\"=%i,\"GuestName\"='%s' WHERE \"id\"=%i", array($NewUserID,$NewGuestsName,$TopicID));
sql_query($query,$SQLStat);
++$i; }
sql_free_result($result);
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Fixing Topic User Names</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Fixing Topic User Names</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Fixing Topic User Names: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<div style="text-align: center;">
	<br />Topic User Names fixed.<br />
	<a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to back to admin cp.<br />&nbsp;
</div>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if($_GET['act']=="fixrnames") { 
$admincptitle = " ".$ThemeSet['TitleDivider']." Fixing Reply User Names";
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."posts\" ORDER BY \"TimeStamp\" ASC", array(null));
$result=sql_query($query,$SQLStat);
$num=sql_num_rows($result);
$i=0;
while ($i < $num) {
$PostID=sql_result($result,$i,"id");
$UsersID=sql_result($result,$i,"UserID");
$GuestsName=sql_result($result,$i,"GuestName");
$NewUserID = $UsersID;
$NewGuestsName = $GuestsName;
$NewGuestsName = GetUserName($NewUserID,$Settings['sqltable']);
$NewGuestsName = $NewGuestsName['Name'];
if($UsersID==-1&&$GuestsName!=null) { $NewGuestsName = $GuestsName; }
if($NewGuestsName==null&&$GuestsName!=null&&$UsersID!==0) {
$NewUserID = -1; $NewGuestsName = $GuestsName; }
if($UsersID==-1&&$GuestsName==null) {
$NewUserID = -1; $NewGuestsName = "Guest"; }
if($UsersID===0&&$GuestsName!=null) {
$NewUserID = -1; $NewGuestsName = "Guest"; }
if($UsersID===0&&$GuestsName==null) {
$NewUserID = -1; $NewGuestsName = "Guest"; }
if($UsersID==$NewUserID&&$GuestsName==$NewGuestsName) {
$NewUserID = $UsersID; $NewGuestsName = $GuestsName; }
$EditUserID=sql_result($result,$i,"EditUser");
$EditUserName=sql_result($result,$i,"EditUserName");
$NewEditUserID = $EditUserID;
$NewEditUserName = $EditUserName;
$NewEditUserName = GetUserName($NewEditUserID,$Settings['sqltable']);
$NewEditUserName = $NewEditUserName['Name'];
if($EditUserID==-1&&$EditUserName!=null) { $NewEditUserName = $EditUserName; }
if($NewEditUserName==null&&$EditUserName!=null&&$EditUserID!==0) {
$NewEditUserID = -1; $NewEditUserName = $EditUserName; }
if($EditUserID==-1&&$EditUserName==null) {
$NewEditUserID = -1; $NewEditUserName = "Guest"; }
if($EditUserID===0&&$EditUserName!=null) {
$NewEditUserID = "0"; $NewEditUserName = null; }
if($EditUserID===0&&$EditUserName==null) {
$NewEditUserID = "0"; $NewEditUserName = null; }
if($EditUserID==$NewEditUserID&&$EditUserName==$NewEditUserName) {
$NewEditUserID = $EditUserID; $NewEditUserName = $EditUserName; }
$query = sql_pre_query("UPDATE \"".$Settings['sqltable']."posts\" SET \"UserID\"=%i,\"GuestName\"='%s',\"EditUser\"=%i,\"EditUserName\"='%s' WHERE \"id\"=%i", array($NewUserID,$NewGuestsName,$NewEditUserID,$NewEditUserName,$PostID));
sql_query($query,$SQLStat);
++$i; }
sql_free_result($result);
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Fixing Reply User Names</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Fixing Reply User Names</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Fixing Reply User Names: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<div style="text-align: center;">
	<br />Reply User Names fixed.<br />
	<a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to back to admin cp.<br />&nbsp;
</div>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if($_GET['act']=="addforum"&&$_POST['update']!="now") { 
$admincptitle = " ".$ThemeSet['TitleDivider']." Adding new Forum";
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=addforum",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Forum Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=addforum",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Forum Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Adding new Forum: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=addforum",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumID">Insert ID for forum:</label></td>
	<td style="width: 50%;"><input type="text" name="ForumID" class="TextBox" id="ForumID" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="OrderID">Insert order id forum:</label></td>
	<td style="width: 50%;"><input type="text" name="OrderID" class="TextBox" id="OrderID" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumCatID">Select category for forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ForumCatID" id="ForumCatID">
<?php 
$cq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" ORDER BY \"OrderID\" ASC, \"id\" ASC", array(null));
$cr=sql_query($cq,$SQLStat);
$eu=sql_num_rows($cr);
$nu=0;
while ($nu < $eu) {
$InCatID=sql_result($cr,$nu,"id");
$InCatName=sql_result($cr,$nu,"Name");
$EuNuMai = "Eu nu mai vreau";
?>
	<option value="<?php echo $InCatID; ?>"><?php echo $InCatName; ?></option>
<?php ++$nu; }
sql_free_result($cr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumName">Insert name for forum:</label></td>
	<td style="width: 50%;"><input type="text" name="ForumName" class="TextBox" id="ForumName" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumDesc">Insert description for forum:</label></td>
	<td style="width: 50%;"><input type="text" name="ForumDesc" class="TextBox" id="ForumDesc" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ShowForum">Show forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ShowForum" id="ShowForum">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumType">Insert forum type:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ForumType" id="ForumType">
	<option selected="selected" value="forum">Forum</option>
	<option value="subforum">SubForum</option>
	<option value="redirect">Redirect</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="RedirectURL">Insert Redirect URL for redirect forum:</label></td>
	<td style="width: 50%;"><input type="text" name="RedirectURL" class="TextBox" id="RedirectURL" size="20" value="http://" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="InSubForum">In SubForum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="InSubForum" id="InSubForum">
	<option selected="selected" value="0">none</option>
<?php 
$fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"InSubForum\"=0 AND \"ForumType\"='subforum' ORDER BY \"OrderID\" ASC, \"id\" ASC", array(null));
$fr=sql_query($fq,$SQLStat);
$ai=sql_num_rows($fr);
$fi=0;
while ($fi < $ai) {
$InForumID=sql_result($fr,$fi,"id");
$InForumName=sql_result($fr,$fi,"Name");
$InForumType=sql_result($fr,$fi,"ForumType");
$AiFiInSubForum=sql_result($fr,$fi,"InSubForum");
if ($InForumType!="redirect"&&$AiFiInSubForum=="0") {
?>
	<option value="<?php echo $InForumID; ?>"><?php echo $InForumName; ?></option>
<?php } ++$fi; }
sql_free_result($fr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="PostCountAdd">Add to post count:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="PostCountAdd" id="PostCountAdd">
	<option selected="selected" value="on">yes</option>
	<option value="off">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NumPostView">Number of posts to view forum:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" size="20" name="NumPostView" id="NumPostView" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NumKarmaView">Amount of karma to view forum:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" size="20" name="NumKarmaView" id="NumKarmaView" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanHaveTopics">Allow topics in forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanHaveTopics" id="CanHaveTopics">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NumPostHotTopic">Number of posts for hot topic:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" size="20" name="NumPostHotTopic" id="NumPostHotTopic" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CPermissions">Copy permissions from:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CPermissions" id="CPermissions">
	<option selected="selected" value="0">none</option>
<?php 
$fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" ORDER BY \"OrderID\" ASC, \"id\" ASC", array(null));
$fr=sql_query($fq,$SQLStat);
$ai=sql_num_rows($fr);
$fi=0;
while ($fi < $ai) {
$InForumID=sql_result($fr,$fi,"id");
$InForumName=sql_result($fr,$fi,"Name");
$InForumType=sql_result($fr,$fi,"ForumType");
$AiFiInSubForum=sql_result($fr,$fi,"InSubForum");
?>
	<option value="<?php echo $InForumID; ?>"><?php echo $InForumName; ?></option>
<?php ++$fi; }
sql_free_result($fr); ?>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="addforum" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Add Forum" name="Apply_Changes" />
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
<?php } if($_POST['act']=="addforum"&&$_POST['update']=="now"&&$_GET['act']=="addforum") {
$_POST['ForumName'] = stripcslashes(htmlspecialchars($_POST['ForumName'], ENT_QUOTES, $Settings['charset']));
//$_POST['ForumName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['ForumName']);
$_POST['ForumName'] = remove_spaces($_POST['ForumName']);
$_POST['ForumDesc'] = stripcslashes(htmlspecialchars($_POST['ForumDesc'], ENT_QUOTES, $Settings['charset']));
//$_POST['ForumDesc'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['ForumDesc']);
$_POST['ForumDesc'] = remove_spaces($_POST['ForumDesc']);
$sql_id_check = sql_query(sql_pre_query("SELECT \"id\" FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i LIMIT 1", array($_POST['ForumID'])),$SQLStat);
$sql_order_check = sql_query(sql_pre_query("SELECT \"OrderID\" FROM \"".$Settings['sqltable']."forums\" WHERE \"OrderID\"=%i LIMIT 1", array($_POST['OrderID'])),$SQLStat);
$id_check = sql_num_rows($sql_id_check); $order_check = sql_num_rows($sql_order_check);
sql_free_result($sql_id_check); sql_free_result($sql_order_check);
$errorstr = "";
if ($_POST['NumPostView']==null||
	!is_numeric($_POST['NumPostView'])) {
	$_POST['NumPostView'] = 0; }
if ($_POST['NumKarmaView']==null||
	!is_numeric($_POST['NumKarmaView'])) {
	$_POST['NumKarmaView'] = 0; }
if ($Settings['hot_topic_num']==null||
	!is_numeric($Settings['hot_topic_num'])) {
	$Settings['hot_topic_num'] = 10; }
if ($_POST['NumPostHotTopic']==null||
	!is_numeric($_POST['NumPostHotTopic'])) {
	$_POST['NumPostHotTopic'] = $Settings['hot_topic_num']; }
if ($_POST['ForumName']==null||
	$_POST['ForumName']=="ShowMe") { $Error="Yes";
$errorstr = $errorstr."You need to enter a forum name.<br />\n"; } 
if ($_POST['ForumDesc']==null) { $Error="Yes";
$errorstr = $errorstr."You need to enter a description.<br />\n"; } 
if ($_POST['ForumID']==null||
	!is_numeric($_POST['ForumID'])) { $Error="Yes";
$errorstr = $errorstr."You need to enter a forum ID.<br />\n"; } 
if($id_check > 0) { $Error="Yes";
$errorstr = $errorstr."This ID number is already used.<br />\n"; } 
if($order_check > 0) { $Error="Yes"; 
$errorstr = $errorstr."This order number is already used.<br />\n"; } 
if (pre_strlen($_POST['ForumName'])>"150") { $Error="Yes";
$errorstr = $errorstr."Your Forum Name is too big.<br />\n"; } 
if (pre_strlen($_POST['ForumDesc'])>"300") { $Error="Yes";
$errorstr = $errorstr."Your Forum Description is too big.<br />\n"; } 
if ($Error!="Yes") {
redirect("refresh",$basedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],FALSE),"4");
$admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
$query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."forums\" (\"id\", \"CategoryID\", \"OrderID\", \"Name\", \"ShowForum\", \"ForumType\", \"InSubForum\", \"RedirectURL\", \"Redirects\", \"NumViews\", \"Description\", \"PostCountAdd\", \"PostCountView\", \"KarmaCountView\", \"CanHaveTopics\", \"HotTopicPosts\", \"NumPosts\", \"NumTopics\") VALUES\n".
"(%i, %i, %i, '%s', '%s', '%s', %i, '%s', 0, 0, '%s', '%s', %i, %i, '%s', %i, 0, 0)", array($_POST['ForumID'],$_POST['ForumCatID'],$_POST['OrderID'],$_POST['ForumName'],$_POST['ShowForum'],$_POST['ForumType'],$_POST['InSubForum'],$_POST['RedirectURL'],$_POST['ForumDesc'],$_POST['PostCountAdd'],$_POST['NumPostView'],$_POST['NumKarmaView'],$_POST['CanHaveTopics'],$_POST['NumPostHotTopic']));
sql_query($query,$SQLStat);
if(!is_numeric($_POST['CPermissions'])) { $_POST['CPermissions'] = "0"; }
$getperidq = sql_pre_query("SELECT DISTINCT \"PermissionID\" FROM \"".$Settings['sqltable']."permissions\" ORDER BY \"PermissionID\" ASC", array(null));
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
if($Settings['sqltype']=="mysql"||$Settings['sqltype']=="mysqli"
	||$Settings['sqltype']=="pgsql") {
$getperidID=sql_result($getperidr,$getperidi,"PermissionID"); }
if($Settings['sqltype']=="sqlite") {
$getperidID=sql_result($getperidr,$getperidi,"\"PermissionID\""); }
if($_POST['CPermissions']=="0") {
$getperidq2 = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."permissions\" WHERE \"PermissionID\"=%i", array($getperidID)); }
if($_POST['CPermissions']!="0") {
$getperidq2 = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."permissions\" WHERE \"PermissionID\"=%i AND \"ForumID\"=%i", array($getperidID,$_POST['CPermissions'])); }
$getperidr2=sql_query($getperidq2,$SQLStat);
$getperidnum2=sql_num_rows($getperidr2);
$PermissionNum=sql_result($getperidr2,0,"id"); 
$PermissionID=sql_result($getperidr2,0,"PermissionID"); 
$PermissionName=sql_result($getperidr2,0,"Name"); 
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
sql_free_result($getperidr2);
if($_POST['CPermissions']=="0") {
$query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."permissions\" (\"PermissionID\", \"Name\", \"ForumID\", \"CanViewForum\", \"CanMakeTopics\", \"CanMakeReplys\", \"CanMakeReplysCT\", \"CanEditTopics\", \"CanEditTopicsCT\", \"CanEditReplys\", \"CanEditReplysCT\", \"CanDeleteTopics\", \"CanDeleteTopicsCT\", \"CanDeleteReplys\", \"CanDeleteReplysCT\", \"CanCloseTopics\", \"CanPinTopics\", \"CanDohtml\", \"CanUseBBags\", \"CanModForum\") VALUES (%i, '%s', %i, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no')", array($PermissionID,$PermissionName,$_POST['ForumID'])); }
if($_POST['CPermissions']!="0") {
if($getperidnum2>0) {
$query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."permissions\" (\"PermissionID\", \"Name\", \"ForumID\", \"CanViewForum\", \"CanMakeTopics\", \"CanMakeReplys\", \"CanMakeReplysCT\", \"CanEditTopics\", \"CanEditTopicsCT\", \"CanEditReplys\", \"CanEditReplysCT\", \"CanDeleteTopics\", \"CanDeleteTopicsCT\", \"CanDeleteReplys\", \"CanDeleteReplysCT\", \"CanCloseTopics\", \"CanPinTopics\", \"CanDohtml\", \"CanUseBBags\", \"CanModForum\") VALUES (%i, '%s', %i, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", array($PermissionID,$PermissionName,$_POST['ForumID'],$CanViewForum,$CanMakeTopics,$CanMakeReplys,$CanMakeReplysCT,$CanEditTopics,$CanEditTopicsCT,$CanEditReplys,$CanEditReplysCT,$CanDeleteTopics,$CanDeleteTopicsCT,$CanDeleteReplys,$CanDeleteReplysCT,$CanCloseTopics,$CanPinTopics,$CanDohtml,$CanUseBBags,$CanModForum)); }
if($getperidnum2<=0) {
$query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."permissions\" (\"PermissionID\", \"Name\", \"ForumID\", \"CanViewForum\", \"CanMakeTopics\", \"CanMakeReplys\", \"CanMakeReplysCT\", \"CanEditTopics\", \"CanEditTopicsCT\", \"CanEditReplys\", \"CanEditReplysCT\", \"CanDeleteTopics\", \"CanDeleteTopicsCT\", \"CanDeleteReplys\", \"CanDeleteReplysCT\", \"CanCloseTopics\", \"CanPinTopics\", \"CanDohtml\", \"CanUseBBags\", \"CanModForum\") VALUES (%i, '%s', %i, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no')", array($PermissionID,$PermissionName,$_POST['ForumID'])); } }
sql_query($query,$SQLStat);
++$getperidi; /*++$nextperid;*/ }
sql_free_result($getperidr); } } 
if($_GET['act']=="deleteforum"&&$_POST['update']!="now") { 
$admincptitle = " ".$ThemeSet['TitleDivider']." Deleting a Forum";
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=addforum",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Forum Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=addforum",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Forum Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Deleting a Forum: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=deleteforum",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelForums">Delete all forums in subforum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelForums" id="DelForums">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelTopics">Delete all topics in (sub)forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelTopics" id="DelTopics">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelPermission">Delete all permission sets in (sub)forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelPermission" id="DelPermission">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelID">Delete Forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelID" id="DelID">
<?php 
$fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" ORDER BY \"OrderID\" ASC, \"id\" ASC", array(null));
$fr=sql_query($fq,$SQLStat);
$ai=sql_num_rows($fr);
$fi=0;
while ($fi < $ai) {
$InForumID=sql_result($fr,$fi,"id");
$InForumName=sql_result($fr,$fi,"Name");
$InForumType=sql_result($fr,$fi,"ForumType");
$AiFiInSubForum=sql_result($fr,$fi,"InSubForum");
?>
	<option value="<?php echo $InForumID; ?>"><?php echo $InForumName; ?></option>
<?php ++$fi; }
sql_free_result($fr); ?>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="deleteforum" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Delete Forum" name="Apply_Changes" />
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
<?php } if($_GET['act']=="deleteforum"&&$_POST['update']=="now"&&$_GET['act']=="deleteforum") { 
$admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
$prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i LIMIT 1", array($_POST['DelID']));
$preresult=sql_query($prequery,$SQLStat);
$prenum=sql_num_rows($preresult);
$errorstr = ""; $Error = null;
if (!is_numeric($_POST['DelID'])) { $Error="Yes";
$errorstr = $errorstr."You need to enter a forum ID.<br />\n"; } 
if($prenum>0&&$Error!="Yes") {
$dtquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i", array($_POST['DelID']));
sql_query($dtquery,$SQLStat);
if($_POST['DelForums']=="yes") {
$dtquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."topics\" WHERE \"ForumID\"=%i", array($_POST['DelID']));
sql_query($dtquery,$SQLStat);
$dtquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."posts\" WHERE \"ForumID\"=%i", array($_POST['DelID']));
sql_query($dtquery,$SQLStat); }
if($_POST['DelPermission']=="yes") {
$dtquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."permissions\" WHERE \"ForumID\"=%i", array($_POST['DelID']));
sql_query($dtquery,$SQLStat); }
if($_POST['DelForums']=="yes") {
$apcquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"InSubForum\"=%i ORDER BY \"OrderID\" ASC, \"id\" ASC", array($_POST['DelID']));
$apcresult=sql_query($apcquery,$SQLStat);
$apcnum=sql_num_rows($apcresult);
$apci=0; $apcl=1; if($apcnum>=1) {
while ($apci < $apcnum) {
$DelSubsForumID=sql_result($apcresult,$apci,"id");
if($_POST['DelForums']=="yes") {
$dtquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."topics\" WHERE \"ForumID\"=%i", array($DelSubsForumID));
sql_query($dtquery,$SQLStat);
$dtquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."posts\" WHERE \"ForumID\"=%i", array($DelSubsForumID));
sql_query($dtquery,$SQLStat); }
if($_POST['DelPermission']=="yes") {
$dtquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."permissions\" WHERE \"ForumID\"=%i", array($DelSubsForumID));
sql_query($dtquery,$SQLStat); }
$dtquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i", array($DelSubsForumID));
sql_query($dtquery,$SQLStat);
++$apci; }
sql_free_result($apcresult); } }
?>
<?php } } if($_GET['act']=="editforum"&&$_POST['update']!="now") {
$admincptitle = " ".$ThemeSet['TitleDivider']." Editing a Forum";
if(!isset($_POST['id'])) {
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editforum",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Forum Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editforum",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Forum Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing a Forum: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editforum",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="id">Forum to Edit:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="id" id="id">
<?php 
$fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" ORDER BY \"OrderID\" ASC, \"id\" ASC", array(null));
$fr=sql_query($fq,$SQLStat);
$ai=sql_num_rows($fr);
$fi=0;
while ($fi < $ai) {
$InForumID=sql_result($fr,$fi,"id");
$InForumName=sql_result($fr,$fi,"Name");
$InForumType=sql_result($fr,$fi,"ForumType");
$AiFiInSubForum=sql_result($fr,$fi,"InSubForum");
?>
	<option value="<?php echo $InForumID; ?>"><?php echo $InForumName; ?></option>
<?php ++$fi; }
sql_free_result($fr); ?>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="editforum" style="display: none;" />
<input type="submit" class="Button" value="Edit Forum" name="Apply_Changes" />
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
$prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i LIMIT 1", array($_POST['id']));
$preresult=sql_query($prequery,$SQLStat);
$prenum=sql_num_rows($preresult);
if($prenum==0) { redirect("location",$basedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],false)); sql_free_result($preresult);
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($prenum>=1) {
$ForumID=sql_result($preresult,0,"id");
$ForumCatID=sql_result($preresult,0,"CategoryID");
$ForumOrder=sql_result($preresult,0,"OrderID");
$ForumName=sql_result($preresult,0,"Name");
$ShowForum=sql_result($preresult,0,"ShowForum");
$ForumType=sql_result($preresult,0,"ForumType");
$InSubForum=sql_result($preresult,0,"InSubForum");
$RedirectURL=sql_result($preresult,0,"RedirectURL");
$RedirectTimes=sql_result($preresult,0,"Redirects");
$NumberViews=sql_result($preresult,0,"NumViews");
$ForumDescription=sql_result($preresult,0,"Description");
$PostCountAdd=sql_result($preresult,0,"PostCountAdd");
$PostCountView=sql_result($preresult,0,"PostCountView");
$KarmaCountView=sql_result($preresult,0,"KarmaCountView");
$CanHaveTopics=sql_result($preresult,0,"CanHaveTopics");
$HotTopicPosts=sql_result($preresult,0,"HotTopicPosts");
$NumberPosts=sql_result($preresult,0,"NumPosts");
$NumberTopics=sql_result($preresult,0,"NumTopics");
sql_free_result($preresult);
$ForumType = strtolower($ForumType); $CanHaveTopics = strtolower($CanHaveTopics);
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editforum",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Forum Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editforum",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Forum Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing a Forum: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editforum",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumID">Insert ID for forum:</label></td>
	<td style="width: 50%;"><input type="text" name="ForumID" class="TextBox" id="ForumID" size="20" value="<?php echo $ForumID; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="OrderID">Insert order id forum:</label></td>
	<td style="width: 50%;"><input type="text" name="OrderID" class="TextBox" id="OrderID" size="20" value="<?php echo $ForumOrder; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumCatID">Select category for forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ForumCatID" id="ForumCatID">
<?php 
$cq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" ORDER BY \"OrderID\" ASC, \"id\" ASC", array(null));
$cr=sql_query($cq,$SQLStat);
$eu=sql_num_rows($cr);
$nu=0;
while ($nu < $eu) {
$InCatID=sql_result($cr,$nu,"id");
$InCatName=sql_result($cr,$nu,"Name");
$EuNuMai = "Eu nu mai vreau";
if($ForumCatID==$InCatID) {
?>
	<option value="<?php echo $InCatID; ?>" selected="selected"><?php echo $InCatName; ?></option>
<?php } if($ForumCatID!=$InCatID) { ?>
	<option value="<?php echo $InCatID; ?>"><?php echo $InCatName; ?></option>
<?php } ++$nu; }
sql_free_result($cr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumName">Insert name for forum:</label></td>
	<td style="width: 50%;"><input type="text" name="ForumName" class="TextBox" id="ForumName" size="20" value="<?php echo $ForumName; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumDesc">Insert description for forum:</label></td>
	<td style="width: 50%;"><input type="text" name="ForumDesc" class="TextBox" id="ForumDesc" size="20" value="<?php echo $ForumDescription; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ShowForum">Show forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ShowForum" id="ShowForum">
	<option <?php if($ShowForum=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($ShowForum=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumType">Insert forum type:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ForumType" id="ForumType">
	<option <?php if($ForumType=="forum") { echo "selected=\"selected\" "; } ?>value="forum">Forum</option>
	<option <?php if($ForumType=="subforum") { echo "selected=\"selected\" "; } ?>value="subforum">SubForum</option>
	<option <?php if($ForumType=="redirect") { echo "selected=\"selected\" "; } ?>value="redirect">Redirect</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="RedirectURL">Insert Redirect URL for redirect forum:</label></td>
	<td style="width: 50%;"><input type="text" name="RedirectURL" class="TextBox" id="RedirectURL" size="20" value="<?php echo htmlentities($RedirectURL, ENT_QUOTES, $Settings['charset']); ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="InSubForum">In SubForum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="InSubForum" id="InSubForum">
	<option selected="selected" value="0">none</option>
<?php 
$fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"InSubForum\"=0 AND \"id\"<>%i AND \"ForumType\"='subforum' ORDER BY \"OrderID\" ASC, \"id\" ASC", array($ForumID));
$fr=sql_query($fq,$SQLStat);
$ai=sql_num_rows($fr);
$fi=0;
while ($fi < $ai) {
$InForumID=sql_result($fr,$fi,"id");
$InForumName=sql_result($fr,$fi,"Name");
$InForumType=sql_result($fr,$fi,"ForumType");
$AiFiInSubForum=sql_result($fr,$fi,"InSubForum");
if ($InForumType!="redirect"&&$AiFiInSubForum=="0") {
if($InSubForum==$InForumID) {
?>
	<option value="<?php echo $InForumID; ?>" selected="selected"><?php echo $InForumName; ?></option>
<?php } if($InSubForum!=$InForumID) { ?>
	<option value="<?php echo $InForumID; ?>"><?php echo $InForumName; ?></option>
<?php } } ++$fi; }
sql_free_result($fr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="PostCountAdd">Add to post count:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="PostCountAdd" id="PostCountAdd">
	<option <?php if($PostCountAdd=="on") { echo "selected=\"selected\" "; } ?>value="on">yes</option>
	<option <?php if($PostCountAdd=="off") { echo "selected=\"selected\" "; } ?>value="off">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NumPostView">Number of posts to view forum:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" size="20" name="NumPostView" id="NumPostView" value="<?php echo $PostCountView; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NumKarmaView">Amount of karma to view forum:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" size="20" name="NumKarmaView" id="NumKarmaView" value="<?php echo $KarmaCountView; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanHaveTopics">Allow topics in forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanHaveTopics" id="CanHaveTopics">
	<option <?php if($CanHaveTopics=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($CanHaveTopics=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NumPostHotTopic">Number of posts for hot topic:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" size="20" name="NumPostHotTopic" id="NumPostHotTopic" value="<?php echo $HotTopicPosts; ?>" /></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="editforum" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="hidden" name="id" value="<?php echo $ForumID; ?>" style="display: none;" />
<input type="submit" class="Button" value="Edit Forum" name="Apply_Changes" />
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
<?php } } } if($_POST['act']=="editforum"&&$_POST['update']=="now"&&$_GET['act']=="editforum"&&
	isset($_POST['id'])) {
$_POST['ForumName'] = stripcslashes(htmlspecialchars($_POST['ForumName'], ENT_QUOTES, $Settings['charset']));
//$_POST['ForumName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['ForumName']);
$_POST['ForumName'] = remove_spaces($_POST['ForumName']);
$_POST['ForumDesc'] = stripcslashes(htmlspecialchars($_POST['ForumDesc'], ENT_QUOTES, $Settings['charset']));
//$_POST['ForumDesc'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['ForumDesc']);
$_POST['ForumDesc'] = remove_spaces($_POST['ForumDesc']);
$prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i LIMIT 1", array($_POST['id']));
$preresult=sql_query($prequery,$SQLStat);
$prenum=sql_num_rows($preresult);
if($prenum==0) { redirect("location",$basedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],false)); sql_free_result($preresult);
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($prenum>=1) {
$OldID=sql_result($preresult,0,"id");
$OldOrder=sql_result($preresult,0,"OrderID");
sql_free_result($preresult);
$sql_id_check = sql_query(sql_pre_query("SELECT \"id\" FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i LIMIT 1", array($_POST['ForumID'])),$SQLStat);
$sql_order_check = sql_query(sql_pre_query("SELECT \"OrderID\" FROM \"".$Settings['sqltable']."forums\" WHERE \"OrderID\"=%i LIMIT 1", array($_POST['OrderID'])),$SQLStat);
$id_check = sql_num_rows($sql_id_check); $order_check = sql_num_rows($sql_order_check);
sql_free_result($sql_id_check); sql_free_result($sql_order_check);
if ($_POST['NumPostView']==null||
	!is_numeric($_POST['NumPostView'])) {
	$_POST['NumPostView'] = 0; }
if ($_POST['NumKarmaView']==null||
	!is_numeric($_POST['NumKarmaView'])) {
	$_POST['NumKarmaView'] = 0; }
if ($Settings['hot_topic_num']==null||
	!is_numeric($Settings['hot_topic_num'])) {
	$Settings['hot_topic_num'] = 10; }
if ($_POST['NumPostHotTopic']==null||
	!is_numeric($_POST['NumPostHotTopic'])) {
	$_POST['NumPostHotTopic'] = $Settings['hot_topic_num']; }
if ($_POST['ForumName']==null||
	$_POST['ForumName']=="ShowMe") { $Error="Yes";
$errorstr = $errorstr."You need to enter a forum name.<br />\n"; } 
if ($_POST['ForumDesc']==null) { $Error="Yes";
$errorstr = $errorstr."You need to enter a description.<br />\n"; } 
if ($_POST['ForumID']==null||
	!is_numeric($_POST['ForumID'])) { $Error="Yes";
$errorstr = $errorstr."You need to enter a forum ID.<br />\n"; } 
if($id_check > 0&&$_POST['ForumID']!=$OldID) { $Error="Yes";
$errorstr = $errorstr."This ID number is already used.<br />\n"; } 
if($order_check > 0&&$_POST['OrderID']!=$OldOrder) { $Error="Yes"; 
$errorstr = $errorstr."This order number is already used.<br />\n"; } 
if (pre_strlen($_POST['ForumName'])>"150") { $Error="Yes";
$errorstr = $errorstr."Your Forum Name is too big.<br />\n"; } 
if (pre_strlen($_POST['ForumDesc'])>"300") { $Error="Yes";
$errorstr = $errorstr."Your Forum Description is too big.<br />\n"; } 
if ($Error!="Yes") {
redirect("refresh",$basedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],FALSE),"4");
$admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
$query = sql_pre_query("UPDATE \"".$Settings['sqltable']."forums\" SET \"id\"=%i,\"CategoryID\"=%i,\"OrderID\"=%i,\"Name\"='%s',\"ShowForum\"='%s',\"ForumType\"='%s',\"InSubForum\"=%i,\"RedirectURL\"='%s',\"Description\"='%s',\"PostCountAdd\"='%s',\"PostCountView\"=%i,\"KarmaCountView\"=%i,\"CanHaveTopics\"='%s',\"HotTopicPosts\"=%i WHERE \"id\"=%i", array($_POST['ForumID'],$_POST['ForumCatID'],$_POST['OrderID'],$_POST['ForumName'],$_POST['ShowForum'],$_POST['ForumType'],$_POST['InSubForum'],$_POST['RedirectURL'],$_POST['ForumDesc'],$_POST['PostCountAdd'],$_POST['NumPostView'],$_POST['NumKarmaView'],$_POST['CanHaveTopics'],$_POST['NumPostHotTopic'],$_POST['id']));
sql_query($query,$SQLStat);
if($_POST['ForumID']!=$_POST['id']) { 
$query = sql_pre_query("UPDATE \"".$Settings['sqltable']."forums\" SET \"InSubForum\"=%i WHERE \"InSubForum\"=%i", array($_POST['ForumID'],$_POST['id']));
sql_query($query,$SQLStat);
$query = sql_pre_query("UPDATE \"".$Settings['sqltable']."topics\" SET \"ForumID\"=%i WHERE \"ForumID\"=%i", array($_POST['ForumID'],$_POST['id']));
sql_query($query,$SQLStat);
$query = sql_pre_query("UPDATE \"".$Settings['sqltable']."posts\" SET \"ForumID\"=%i WHERE \"ForumID\"=%i", array($_POST['ForumID'],$_POST['id']));
sql_query($query,$SQLStat); }
$queryz = sql_pre_query("UPDATE \"".$Settings['sqltable']."permissions\" SET \"ForumID\"=%i WHERE \"ForumID\"=%i", array($_POST['ForumID'],$_POST['id']));
sql_query($queryz,$SQLStat); } } } 
if($_GET['act']=="fpermissions"&&$_POST['update']!="now") {
$admincptitle = " ".$ThemeSet['TitleDivider']." Forum Permissions Manager";
if(!isset($_POST['id'])) {
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=fpermissions",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Forum Permissions Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=fpermissions",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Forum Permissions Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Forum Permissions Manager: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=fpermissions",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="id">Permission to view:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="id" id="id">
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
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="fpermissions" style="display: none;" />
<input type="submit" class="Button" value="View Permission" name="Apply_Changes" />
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
<?php } if(isset($_POST['id'])&&$_POST['subact']==null) { ?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=fpermissions",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Forum Permissions Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=fpermissions",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Forum Permissions Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Forum Permissions Manager: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<?php 
$fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" ORDER BY \"OrderID\" ASC, \"id\" ASC", array(null));
$fr=sql_query($fq,$SQLStat);
$ai=sql_num_rows($fr);
$fi=0;
while ($fi < $ai) {
$InForumID=sql_result($fr,$fi,"id");
$InForumName=sql_result($fr,$fi,"Name");
$getperidq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."permissions\" WHERE \"PermissionID\"=%i AND \"ForumID\"=%i LIMIT 1", array($_POST['id'],$InForumID));
$getperidr=sql_query($getperidq,$SQLStat);
$getperidnum=sql_num_rows($getperidr);
$getperidNumz = null;
$getperidID = null;
if($getperidnum>0) {
$getperidNumz=sql_result($getperidr,0,"id");
$getperidID=sql_result($getperidr,0,"PermissionID"); }
?>
<form style="display: inline;" method="post" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=fpermissions",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<?php if($getperidnum>0) { ?>
Permissions for <?php echo $InForumName; ?> are set: <br />
<input type="hidden" name="act" value="fpermissions" style="display: none;" />
<input type="hidden" name="subact" value="edit" style="display: none;" />
<input type="hidden" name="id" value="<?php echo $getperidNumz; ?>" style="display: none;" />
<input type="submit" class="Button" value="Edit Permissions" name="Apply_Changes" />
<?php } if($getperidnum<=0) { ?>
Permissions for <?php echo $InForumName; ?> are not set: <br />
<input type="hidden" name="act" value="fpermissions" style="display: none;" />
<input type="hidden" name="subact" value="create" style="display: none;" />
<input type="hidden" name="permid" value="<?php echo $_POST['id']; ?>" style="display: none;" />
<input type="hidden" name="id" value="<?php echo $InForumID; ?>" style="display: none;" />
<input type="submit" class="Button" value="Create Permissions" name="Apply_Changes" />
<?php } ?>
</td></tr></table>
</form>
<?php 
sql_free_result($getperidr);
++$fi; }
sql_free_result($fr); ?>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if(isset($_POST['id'])&&$_POST['subact']=="edit") {
$prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."permissions\" WHERE \"id\"=%i LIMIT 1", array($_POST['id']));
$preresult=sql_query($prequery,$SQLStat);
$prenum=sql_num_rows($preresult);
if($prenum==0) { redirect("location",$basedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],false)); sql_free_result($preresult);
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($prenum>=1) {
$PermissionNum=sql_result($preresult,0,"id"); 
$PermissionID=sql_result($preresult,0,"PermissionID"); 
$PermissionName=sql_result($preresult,0,"Name"); 
$PermissionForumID=sql_result($preresult,0,"ForumID"); 
$CanViewForum=sql_result($preresult,0,"CanViewForum"); 
$CanMakeTopics=sql_result($preresult,0,"CanMakeTopics"); 
$CanMakeReplys=sql_result($preresult,0,"CanMakeReplys"); 
$CanMakeReplysCT=sql_result($preresult,0,"CanMakeReplysCT"); 
$CanEditTopics=sql_result($preresult,0,"CanEditTopics"); 
$CanEditTopicsCT=sql_result($preresult,0,"CanEditTopicsCT"); 
$CanEditReplys=sql_result($preresult,0,"CanEditReplys"); 
$CanEditReplysCT=sql_result($preresult,0,"CanEditReplysCT"); 
$CanDeleteTopics=sql_result($preresult,0,"CanDeleteTopics"); 
$CanDeleteTopicsCT=sql_result($preresult,0,"CanDeleteTopicsCT"); 
$CanDeleteReplys=sql_result($preresult,0,"CanDeleteReplys"); 
$CanDeleteReplysCT=sql_result($preresult,0,"CanDeleteReplysCT"); 
$CanCloseTopics=sql_result($preresult,0,"CanCloseTopics"); 
$CanPinTopics=sql_result($preresult,0,"CanPinTopics"); 
$CanDohtml=sql_result($preresult,0,"CanDohtml"); 
$CanUseBBags=sql_result($preresult,0,"CanUseBBags"); 
$CanModForum=sql_result($preresult,0,"CanModForum"); 
sql_free_result($preresult); }
$PermissionName = stripcslashes(htmlspecialchars($PermissionName, ENT_QUOTES, $Settings['charset']));
//$_POST['ForumName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['ForumName']);
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=fpermissions",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Forum Permissions Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=fpermissions",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Forum Permissions Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing Forum Permissions: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=fpermissions",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewForum">Can view forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewForum" id="CanViewForum">
	<option <?php if($CanViewForum=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($CanViewForum=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanMakeTopics">Can make topics:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanMakeTopics" id="CanMakeTopics">
	<option <?php if($CanMakeTopics=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($CanMakeTopics=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanMakeReplys">Can make replys in own:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanMakeReplys" id="CanMakeReplys">
	<option <?php if($CanMakeReplys=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($CanMakeReplys=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanMakeReplysCT">Can make replys other users topic:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanMakeReplysCT" id="CanMakeReplysCT">
	<option <?php if($CanMakeReplysCT=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($CanMakeReplysCT=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanEditTopics">Can edit own topics:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanEditTopics" id="CanEditTopics">
	<option <?php if($CanEditTopics=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($CanEditTopics=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanEditTopicsCT">Can edit other users topics:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanEditTopicsCT" id="CanEditTopicsCT">
	<option <?php if($CanEditTopicsCT=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($CanEditTopicsCT=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanEditReplys">Can edit own replys:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanEditReplys" id="CanEditReplys">
	<option <?php if($CanEditReplys=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($CanEditReplys=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanEditReplysCT">Can edit other users replys:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanEditReplysCT" id="CanEditReplysCT">
	<option <?php if($CanEditReplysCT=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($CanEditReplysCT=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanDeleteTopics">Can delete own topics:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanDeleteTopics" id="CanDeleteTopics">
	<option <?php if($CanDeleteTopics=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($CanDeleteTopics=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="Can delete other users topics">Can delete other users topics:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanDeleteTopicsCT" id="CanDeleteTopicsCT">
	<option <?php if($CanDeleteTopicsCT=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($CanDeleteTopicsCT=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanDeleteReplys">Can delete own replys:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanDeleteReplys" id="CanDeleteReplys">
	<option <?php if($CanDeleteReplys=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($CanDeleteReplys=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanDeleteReplysCT">Can delete other users replys:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanDeleteReplysCT" id="CanDeleteReplysCT">
	<option <?php if($CanDeleteReplysCT=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($CanDeleteReplysCT=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanCloseTopics">Can close topics:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanCloseTopics" id="CanCloseTopics">
	<option <?php if($CanCloseTopics=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($CanCloseTopics=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanPinTopics">Can pin topics:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanPinTopics" id="CanPinTopics">
	<option <?php if($CanPinTopics=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($CanPinTopics=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanDohtml">Can DoHTML:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanDohtml" id="CanDohtml">
	<option <?php if($CanDohtml=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($CanDohtml=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanUseBBags">Can use BBags:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanUseBBags" id="CanUseBBags">
	<option <?php if($CanUseBBags=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($CanUseBBags=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanModForum">Can moderate forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanModForum" id="CanModForum">
	<option <?php if($CanModForum=="yes") { echo "selected=\"selected\" "; } ?>value="yes">yes</option>
	<option <?php if($CanModForum=="no") { echo "selected=\"selected\" "; } ?>value="no">no</option>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="fpermissions" style="display: none;" />
<input type="hidden" name="subact" value="editnow" style="display: none;" />
<input type="hidden" name="id" value="<?php echo $PermissionNum; ?>" style="display: none;" />
<input type="submit" class="Button" value="Edit Permissions" name="Apply_Changes" />
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
<?php } if(isset($_POST['id'])&&$_POST['subact']=="editnow") {
$admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
redirect("refresh",$basedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],FALSE),"4");
$query = sql_pre_query("UPDATE \"".$Settings['sqltable']."permissions\" SET \"CanViewForum\"='%s', \"CanMakeTopics\"='%s', \"CanMakeReplys\"='%s', \"CanMakeReplysCT\"='%s', \"CanEditTopics\"='%s', \"CanEditTopicsCT\"='%s', \"CanEditReplys\"='%s', \"CanEditReplysCT\"='%s', \"CanDeleteTopics\"='%s', \"CanDeleteTopicsCT\"='%s', \"CanDeleteReplys\"='%s', \"CanDeleteReplysCT\"='%s', \"CanCloseTopics\"='%s', \"CanPinTopics\"='%s', \"CanDohtml\"='%s', \"CanUseBBags\"='%s', \"CanModForum\"='%s' WHERE \"id\"=%i", array($_POST['CanViewForum'], $_POST['CanMakeTopics'], $_POST['CanMakeReplys'], $_POST['CanMakeReplysCT'], $_POST['CanEditTopics'], $_POST['CanEditTopicsCT'], $_POST['CanEditReplys'], $_POST['CanEditReplysCT'], $_POST['CanDeleteTopics'], $_POST['CanDeleteTopicsCT'], $_POST['CanDeleteReplys'], $_POST['CanDeleteReplysCT'], $_POST['CanCloseTopics'], $_POST['CanPinTopics'], $_POST['CanDohtml'], $_POST['CanUseBBags'], $_POST['CanModForum'], $_POST['id']));
sql_query($query,$SQLStat); } if(isset($_POST['id'])&&$_POST['subact']=="create") { 
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=fpermissions",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Forum Permissions Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=fpermissions",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Forum Permissions Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing Forum Permissions: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=fpermissions",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewForum">Can view forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewForum" id="CanViewForum">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanMakeTopics">Can make topics:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanMakeTopics" id="CanMakeTopics">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanMakeReplys">Can make replys in own:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanMakeReplys" id="CanMakeReplys">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanMakeReplysCT">Can make replys other users topic:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanMakeReplysCT" id="CanMakeReplysCT">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanEditTopics">Can edit own topics:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanEditTopics" id="CanEditTopics">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanEditTopicsCT">Can edit other users topics:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanEditTopicsCT" id="CanEditTopicsCT">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanEditReplys">Can edit own replys:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanEditReplys" id="CanEditReplys">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanEditReplysCT">Can edit other users replys:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanEditReplysCT" id="CanEditReplysCT">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanDeleteTopics">Can delete own topics:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanDeleteTopics" id="CanDeleteTopics">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="Can delete other users topics">Can delete other users topics:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanDeleteTopicsCT" id="CanDeleteTopicsCT">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanDeleteReplys">Can delete own replys:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanDeleteReplys" id="CanDeleteReplys">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanDeleteReplysCT">Can delete other users replys:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanDeleteReplysCT" id="CanDeleteReplysCT">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanCloseTopics">Can close topics:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanCloseTopics" id="CanCloseTopics">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanPinTopics">Can pin topics:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanPinTopics" id="CanPinTopics">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanDohtml">Can DoHTML:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanDohtml" id="CanDohtml">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanUseBBags">Can use BBags:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanUseBBags" id="CanUseBBags">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr> 
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanModForum">Can moderate forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanModForum" id="CanModForum">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="fpermissions" style="display: none;" />
<input type="hidden" name="subact" value="makenow" style="display: none;" />
<input type="hidden" name="id" value="<?php echo $_POST['id']; ?>" style="display: none;" />
<input type="hidden" name="permid" value="<?php echo $_POST['permid']; ?>" style="display: none;" />
<input type="submit" class="Button" value="Create Permissions" name="Apply_Changes" />
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
<?php } if(isset($_POST['id'])&&isset($_POST['permid'])&&$_POST['subact']=="makenow") {
$admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
redirect("refresh",$basedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],FALSE),"4");
$prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."permissions\" WHERE \"id\"=%i LIMIT 1", array($_POST['permid']));
$preresult=sql_query($prequery,$SQLStat);
$prenum=sql_num_rows($preresult);
if($prenum==0) { redirect("location",$basedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],false)); sql_free_result($preresult);
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($prenum>=1) {
$PermissionName=sql_result($preresult,0,"Name"); 
sql_free_result($preresult); }
//$nextidnum = sql_get_next_id($Settings['sqltable'],"permissions",$SQLStat);
$nextidnum = null;
$query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."permissions\" (\"PermissionID\", \"Name\", \"ForumID\", \"CanViewForum\", \"CanMakeTopics\", \"CanMakeReplys\", \"CanMakeReplysCT\", \"CanEditTopics\", \"CanEditTopicsCT\", \"CanEditReplys\", \"CanEditReplysCT\", \"CanDeleteTopics\", \"CanDeleteTopicsCT\", \"CanDeleteReplys\", \"CanDeleteReplysCT\", \"CanCloseTopics\", \"CanPinTopics\", \"CanDohtml\", \"CanUseBBags\", \"CanModForum\") VALUES\n".
"(%i, '%s', %i, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", array($_POST['permid'], $PermissionName, $_POST['id'], $_POST['CanViewForum'], $_POST['CanMakeTopics'], $_POST['CanMakeReplys'], $_POST['CanMakeReplysCT'], $_POST['CanEditTopics'], $_POST['CanEditTopicsCT'], $_POST['CanEditReplys'], $_POST['CanEditReplysCT'], $_POST['CanDeleteTopics'], $_POST['CanDeleteTopicsCT'], $_POST['CanDeleteReplys'], $_POST['CanDeleteReplysCT'], $_POST['CanCloseTopics'], $_POST['CanPinTopics'], $_POST['CanDohtml'], $_POST['CanUseBBags'], $_POST['CanModForum'])); 
sql_query($query,$SQLStat); } } 
$doupdate = false;
if(isset($_POST['id'])&&$_POST['subact']=="editnow") { 
	$doupdate = true; }
if(isset($_POST['id'])&&isset($_POST['permid'])&&$_POST['subact']=="makenow") { 
	$doupdate = true; }
if($_POST['act']=="addforum"&&$_POST['update']=="now"&&$_GET['act']=="addforum") { 
	$doupdate = true; }
if($_GET['act']=="deleteforum"&&$_POST['update']=="now"&&$_GET['act']=="deleteforum") { 
	$doupdate = true; }
if($_POST['act']=="editforum"&&$_POST['update']=="now"&&$_GET['act']=="editforum"&&
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
<?php if(isset($_POST['id'])&&$_POST['subact']=="editnow") { ?>
<div style="text-align: center;">
	<br />The permission was edited successfully. <a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&nbsp;
	</div>
<?php } if(isset($_POST['id'])&&isset($_POST['permid'])&&$_POST['subact']=="makenow") { ?>
<div style="text-align: center;">
	<br />The permission was created successfully. <a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&nbsp;
	</div>
<?php } if($_POST['act']=="addforum"&&$_POST['update']=="now"&&$_GET['act']=="addforum") { ?>
<div style="text-align: center;">
	<br />The forum was created successfully. <a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&nbsp;
	</div>
<?php } if($_GET['act']=="deleteforum"&&$_POST['update']=="now"&&$_GET['act']=="deleteforum") { ?>
<div style="text-align: center;">
	<br />The forum was deleted successfully. <a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&nbsp;
	</div>
<?php } if($_POST['act']=="editforum"&&$_POST['update']=="now"&&$_GET['act']=="editforum"&&
	isset($_POST['id'])) { ?>
<div style="text-align: center;">
	<br />The forum was edited successfully. <a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&nbsp;
	</div>
<?php } ?>
</td></tr>
<tr id="ProfileTitleEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr></table></div>
<?php } if ($_GET['act']!=null&&$Error=="Yes") {
redirect("refresh",$basedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],FALSE),"4");
$admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Updating Settings</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Updating Settings</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr id="ProfileTitle" class="TableMenuRow2">
<th class="TableMenuColumn2">Updating Settings</th>
</tr>
<tr class="TableMenuRow3" id="ProfileUpdate">
<td class="TableMenuColumn3">
<div style="text-align: center;">
	<br /><?php echo $errorstr; ?>
	<a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Click here</a> to back to admin cp.<br />&nbsp;
	</div>
</td></tr>
<tr id="ProfileTitleEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr></table></div>
<?php } ?>
</td></tr>
</table>
<div>&nbsp;</div>
