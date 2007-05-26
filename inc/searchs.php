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

    $FileInfo: searchs.php - Last Update: 05/26/2007 SVN 15 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="searchs.php"||$File3Name=="/searchs.php") {
	require('index.php');
	exit(); }
if($Settings['enable_search']!=true) {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($Settings['enable_search']==true) {
if($_GET['act']=="topics") {
	if($_GET['search']==null&&$_GET['type']==null) {
	?>
	<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['search'],$Settings['file_ext'],"act=topics",$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search']); ?>">Topic Search</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">&nbsp;Search for topic: </th>
</tr>
<tr class="TableRow3">
<td class="TableRow3">
<form method="post" action="<?php echo url_maker($exfile['search'],$Settings['file_ext'],"act=topics",$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 30%;"><label class="TextBoxLabel" for="search">Enter SearchTerm: </label></td>
	<td style="width: 70%;"><input class="TextBox" id="search" type="text" name="search" /></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" title="Wildcard is %" for="type">Search Type: </label></td>
	<td style="width: 70%;"><select id="type" name="type" class="TextBox">
<option value="normal">Normal Search</option>
<option value="wildcard">Wildcard Search</option>
</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="topics" style="display: none;" />
<input class="Button" type="submit" value="Search" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table></div>
<?php } if($_GET['search']!=null&&$_GET['type']!=null) {
if($_GET['type']!="wildcard") {
$query = query("select * from ".$Settings['sqltable']."topics where TopicName='%s' ORDER BY Pinned DESC, LastUpdate DESC", array($_GET['search'])); }
if($_GET['type']=="wildcard") {
$query = query("select * from ".$Settings['sqltable']."topics where TopicName LIKE '%s' ORDER BY Pinned DESC, LastUpdate DESC", array($_GET['search'])); }
$result=mysql_query($query);
$num=mysql_num_rows($result);
//Start Topic Page Code (Will be used at later time)
if($_GET['page']==null) { $_GET['page'] = 1; } 
if($_GET['page']<=0) { $_GET['page'] = 1; }
$nums = $_GET['page'] * $Settings['max_topics'];
if($nums>$num) { $nums = $num; }
$numz = $nums - $Settings['max_topics'];
if($numz<=0) { $numz = 0; }
$i=$numz;
if($nums<$num) { $nextpage = $_GET['page'] + 1; }
if($nums>=$num) { $nextpage = $_GET['page']; }
if($numz>=$Settings['max_topics']) { $backpage = $_GET['page'] - 1; }
if($_GET['page']<=1) { $backpage = 1; }
//End Topic Page Code (Its not used yet but its still good to have :P )
$i=0;
?>
<div class="Table1Border">
<table class="Table1" id="Search">
<tr id="SearchStart" class="TableRow1">
<td class="TableRow1" colspan="6"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type'],$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search']); ?>">Searching for <?php echo $_GET['search']; ?></a></span>
<?php echo "<span style=\"float: right;\">&nbsp;</span>"; ?></td>
</tr>
<tr id="SearchStatRow" class="TableRow2">
<th class="TableRow2" style="width: 4%;">State</th>
<th class="TableRow2" style="width: 36%;">Topic Name</th>
<th class="TableRow2" style="width: 15%;">Author</th>
<th class="TableRow2" style="width: 15%;">Time</th>
<th class="TableRow2" style="width: 5%;">Replys</th>
<th class="TableRow2" style="width: 25%;">Last Reply</th>
</tr>
<?php
while ($i < $num) {
$TopicID=mysql_result($result,$i,"id");
$ForumID=mysql_result($result,$i,"ForumID");
$UsersID=mysql_result($result,$i,"UserID");
$GuestName=mysql_result($result,$i,"GuestName");
$TheTime=mysql_result($result,$i,"TimeStamp");
$TheTime=GMTimeChange("F j, Y",$TheTime,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$NumReply=mysql_result($result,$i,"NumReply");
$TopicName=mysql_result($result,$i,"TopicName");
$TopicDescription=mysql_result($result,$i,"Description");
$PinnedTopic=mysql_result($result,$i,"Pinned");
$TopicStat=mysql_result($result,$i,"Closed");
$UsersName = GetUserName($UsersID,$Settings['sqltable']);
if($UsersName=="Guest") { $UsersName=$GuestName;
if($UsersName==null) { $UsersName="Guest"; } }
$glrquery = query("select * from ".$Settings['sqltable']."posts where ForumID=%i and TopicID=%i ORDER BY TimeStamp DESC", array($ForumID,$TopicID));
$glrresult=mysql_query($glrquery);
$glrnum=mysql_num_rows($glrresult);
if($glrnum>0){
$ReplyID1=mysql_result($glrresult,0,"id");
$UsersID1=mysql_result($glrresult,0,"UserID");
$GuestName1=mysql_result($glrresult,0,"GuestName");
$TimeStamp1=mysql_result($glrresult,0,"TimeStamp");
$TimeStamp1=GMTimeChange("F j, Y",$TimeStamp1,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$UsersName1 = GetUserName($UsersID1,$Settings['sqltable']); }
if($UsersName1=="Guest") { $UsersName1=$GuestName1;
if($UsersName1==null) { $UsersName1="Guest"; } }
if($TimeStamp1!=null) { $lul = null;
if($UsersID1!="-1") {
$lul = url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$UsersID1,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); }
if($UsersID1=="-1") {
$lul = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); }
$LastReply = "User: <a href=\"".$lul."\">".$UsersName1."</a><br />\nTime: ".$TimeStamp1; }
@mysql_free_result($glrresult);
if($TimeStamp1==null) { $LastReply = "&nbsp;<br />&nbsp;"; }
$PreTopic = $ThemeSet['TopicIcon'];
if ($PinnedTopic==1) {
	if($NumReply>=$Settings['hot_topic_num']) {
		$PreTopic=$ThemeSet['HotPinTopic']; }
	if($NumReply<$Settings['hot_topic_num']) {
		$PreTopic=$ThemeSet['PinTopic']; } }
if ($TopicStat==1) {
	if($NumReply>=$Settings['hot_topic_num']) {
		$PreTopic=$ThemeSet['HotClosedTopic']; }
	if($NumReply<$Settings['hot_topic_num']) {
		$PreTopic=$ThemeSet['ClosedTopic']; } }
if ($PinnedTopic==0) {
	if ($TopicStat==0) {
		if($NumReply>=$Settings['hot_topic_num']) {
			$PreTopic=$ThemeSet['HotTopic']; }
		if($NumReply<$Settings['hot_topic_num']) {
			$PreTopic=$ThemeSet['TopicIcon']; } } }
if ($PinnedTopic==1) {
	if ($TopicStat==1) {
		if($NumReply>=$Settings['hot_topic_num']) {
			$PreTopic=$ThemeSet['HotPinClosedTopic']; }
		if($NumReply<$Settings['hot_topic_num']) {
			$PreTopic=$ThemeSet['PinClosedTopic']; } } }
?>
<tr class="TableRow3" id="Topic<?php echo $TopicID; ?>">
<td class="TableRow3"><div class="topicstate">
<?php echo $PreTopic; ?></div></td>
<td class="TableRow3"><div class="topicname">
<a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>"><?php echo $TopicName; ?></a></div>
<div class="topicdescription"><?php echo $TopicDescription; ?></div></td>
<td class="TableRow3" style="text-align: center;"><a href="<?php
if($UsersID!="-1") {
echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$UsersID,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); }
if($UsersID=="-1") {
echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); }
?>"><?php echo $UsersName; ?></a></td>
<td class="TableRow3" style="text-align: center;"><?php echo $TheTime; ?></td>
<td class="TableRow3" style="text-align: center;"><?php echo $NumReply; ?></td>
<td class="TableRow3"><?php echo $LastReply; ?></td>
</tr>
<?php ++$i; }
?>
<tr id="SearchEnd" class="TableRow4">
<td class="TableRow4" colspan="6">&nbsp;</td>
</tr>
</table></div>
<?php
@mysql_free_result($result);
?>
<div>&nbsp;</div>
<table class="Table2" style="width: 100%;">
<tr>
 <td style="width: 0%; text-align: left;">&nbsp;</td>
 <td style="width: 100%; text-align: right;"><a href="#Act/Topic"><?php echo $ThemeSet['NewTopic']; ?></a></td>
</tr>
</table>
<div>&nbsp;</div>
<?php } } } ?>