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

    $FileInfo: searchs.php - Last Update: 06/03/2008 SVN 165 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="searchs.php"||$File3Name=="/searchs.php") {
	require('index.php');
	exit(); }
if($Settings['enable_search']===false||
	$GroupInfo['CanSearch']=="no") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
@header("Content-Type: text/plain; charset=".$Settings['charset']);
ob_clean(); echo "Sorry you do not have permission to do a search."; 
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($Settings['enable_search']===true||
	$GroupInfo['CanSearch']=="yes") {
if($_GET['act']=="topics") {
	if($_GET['search']==null&&$_GET['type']==null) {
	?>
	<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['search'],$Settings['file_ext'],"act=topics",$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search']); ?>">Topic Search</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">&nbsp;Search for topic: </th>
</tr>
<tr class="TableRow3">
<td class="TableRow3">
<form style="display: inline;" method="post" action="<?php echo url_maker($exfile['search'],$Settings['file_ext'],"act=topics",$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 30%;"><label class="TextBoxLabel" for="search">Enter SearchTerm: </label></td>
	<td style="width: 70%;"><input maxlength="35" class="TextBox" id="search" type="text" name="search" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 30%;"><label class="TextBoxLabel" for="msearch">Filter by Member (optional): </label></td>
	<td style="width: 70%;"><input maxlength="25" class="TextBox" id="msearch" type="text" name="msearch" /></td>
</tr><tr style="text-align: left;">
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
if(pre_strlen($_GET['msearch'])>="25") { 
	$_GET['msearch'] = null; }
if($_GET['msearch']!=null) {
$memsiquery = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `Name`='%s' LIMIT 1", array($_GET['msearch']));
$memsiresult=mysql_query($memsiquery);
$memsinum=mysql_num_rows($memsiresult);
$memsi=0;
if($memsinum==0) { $_GET['msearch'] = null; }
if($memsinum!=0) {
$memsid=mysql_result($memsiresult,$memsi,"id"); 
@mysql_free_result($memsiresult); } }
//Get SQL LIMIT Number
$nums = $_GET['page'] * $Settings['max_topics'];
$PageLimit = $nums - $Settings['max_topics'];
if($PageLimit<0) { $PageLimit = 0; }
if($_GET['msearch']==null) {
if($_GET['type']!="wildcard") {
$query = query("SELECT SQL_CALC_FOUND_ROWS * FROM `".$Settings['sqltable']."topics` WHERE `TopicName`='%s' ORDER BY `Pinned` DESC, `LastUpdate` DESC LIMIT %i,%i", array($_GET['search'],$PageLimit,$Settings['max_topics'])); }
if($_GET['type']=="wildcard") {
$query = query("SELECT SQL_CALC_FOUND_ROWS * FROM `".$Settings['sqltable']."topics` WHERE `TopicName` LIKE '%s' ORDER BY `Pinned` DESC, `LastUpdate` DESC LIMIT %i,%i", array($_GET['search'],$PageLimit,$Settings['max_topics'])); } }
if($_GET['msearch']!=null) {
if($_GET['type']!="wildcard") {
$query = query("SELECT SQL_CALC_FOUND_ROWS * FROM `".$Settings['sqltable']."topics` WHERE `TopicName`='%s' AND `UserID`=%i ORDER BY `Pinned` DESC, `LastUpdate` DESC LIMIT %i,%i", array($_GET['search'],$memsid,$PageLimit,$Settings['max_topics'])); }
if($_GET['type']=="wildcard") {
$query = query("SELECT SQL_CALC_FOUND_ROWS * FROM `".$Settings['sqltable']."topics` WHERE `TopicName` LIKE '%s' AND `UserID`=%i ORDER BY `Pinned` DESC, `LastUpdate` DESC LIMIT %i,%i", array($_GET['search'],$memsid,$PageLimit,$Settings['max_topics'])); } }
$rnquery = query("SELECT FOUND_ROWS();", array(null));
$result=mysql_query($query);
$rnresult=mysql_query($rnquery);
$NumberTopics = mysql_result($rnresult,0);
@mysql_free_result($rnresult);
if($NumberTopics==null) { 
	$NumberTopics = 0; }
$num = $NumberTopics;
//Start Topic Page Code
if(!isset($Settings['max_topics'])) { $Settings['max_topics'] = 10; }
if($_GET['page']==null) { $_GET['page'] = 1; } 
if($_GET['page']<=0) { $_GET['page'] = 1; }
$nums = $_GET['page'] * $Settings['max_topics'];
if($nums>$num) { $nums = $num; }
$numz = $nums - $Settings['max_topics'];
if($numz<=0) { $numz = 0; }
//$i=$numz;
if($nums<$num) { $nextpage = $_GET['page'] + 1; }
if($nums>=$num) { $nextpage = $_GET['page']; }
if($numz>=$Settings['max_topics']) { $backpage = $_GET['page'] - 1; }
if($_GET['page']<=1) { $backpage = 1; }
$pnum = $num; $l = 1; $Pages = null;
while ($pnum>0) {
if($pnum>=$Settings['max_topics']) { 
	$pnum = $pnum - $Settings['max_topics']; 
	$Pages[$l] = $l; ++$l; }
if($pnum<$Settings['max_topics']&&$pnum>0) { 
	$pnum = $pnum - $pnum; 
	$Pages[$l] = $l; ++$l; } }
//End Topic Page Code
$num=mysql_num_rows($result);
if($num<=0) { 
redirect("location",$basedir.url_maker($exfile['search'],$Settings['file_ext'],"act=topics",$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'],false));
@header("Content-Type: text/plain; charset=".$Settings['charset']);
ob_clean(); echo "Sorry could not find any search results."; @mysql_free_result($result);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
$i=0;
//List Page Number Code Start
$pagenum=count($Pages);
if($_GET['page']>$pagenum) {
	$_GET['page'] = $pagenum; }
$pagei=0; $pstring = "<div class=\"PageList\">Pages: ";
if($_GET['page']<4) { $Pagez[0] = null; }
if($_GET['page']>=4) { $Pagez[0] = "First"; }
if($_GET['page']>=3) {
$Pagez[1] = $_GET['page'] - 2; }
if($_GET['page']<3) {
$Pagez[1] = null; }
if($_GET['page']>=2) {
$Pagez[2] = $_GET['page'] - 1; }
if($_GET['page']<2) {
$Pagez[2] = null; }
$Pagez[3] = $_GET['page'];
if($_GET['page']<$pagenum) {
$Pagez[4] = $_GET['page'] + 1; }
if($_GET['page']>=$pagenum) {
$Pagez[4] = null; }
$pagenext = $_GET['page'] + 1;
if($pagenext<$pagenum) {
$Pagez[5] = $_GET['page'] + 2; }
if($pagenext>=$pagenum) {
$Pagez[5] = null; }
if($_GET['page']<$pagenum) { $Pagez[6] = "Last"; }
if($_GET['page']>=$pagenum) { $Pagez[6] = null; }
$pagenumi=count($Pagez);
if($num==0) {
$pagenumi = 0;
if($_GET['msearch']==null) { 
$pstring = $pstring."<a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">1</a> "; }
if($_GET['msearch']!=null) {
$pstring = $pstring."<a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&msearch=".$_GET['msearch']."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">1</a> "; }
}
while ($pagei < $pagenumi) {
if($Pagez[$pagei]!=null&&
   $Pagez[$pagei]!="First"&&
   $Pagez[$pagei]!="Last") {
if($_GET['msearch']==null) {
$pstring = $pstring."<a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&page=".$Pagez[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">".$Pagez[$pagei]."</a> "; }
if($_GET['msearch']!=null) {
$pstring = $pstring."<a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&msearch=".$_GET['msearch']."&page=".$Pagez[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">".$Pagez[$pagei]."</a> "; } }
if($Pagez[$pagei]=="First") {
if($_GET['msearch']==null) {
$pstring = $pstring."<a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">&lt; First</a> ... "; }
if($_GET['msearch']!=null) {
$pstring = $pstring."<a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&msearch=".$_GET['msearch']."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">&lt; First</a> ... "; } }
if($Pagez[$pagei]=="Last") {
if($_GET['msearch']==null) {
$pstring = $pstring."... <a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&page=".$pagenum,$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">Last &gt;</a> "; }
if($_GET['msearch']!=null) {
$pstring = $pstring."... <a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&msearch=".$_GET['msearch']."&page=".$pagenum,$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">Last &gt;</a> "; } }
	++$pagei; } $pstring = $pstring."</div>";
echo $pstring;
//List Page Number Code end
?>
<div class="Table1Border">
<table class="Table1" id="Search">
<tr id="SearchStart" class="TableRow1">
<td class="TableRow1" colspan="6"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'];
if($_GET['msearch']==null) { ?>
<a href="<?php echo url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&page=".$_GET['page'],$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search']); ?>">Searching for <?php echo $_GET['search']; ?></a>
<?php } if($_GET['msearch']!=null) { ?>
<a href="<?php echo url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&msearch=".$_GET['msearch']."&page=".$_GET['page'],$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search']); ?>">Searching for <?php echo $_GET['search']; ?> by <?php echo $_GET['msearch']; ?></a>
<?php } ?></span>
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
$CategoryID=mysql_result($result,$i,"CategoryID");
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
if(isset($PermissionInfo['CanViewForum'][$ForumID])&&
	$PermissionInfo['CanViewForum'][$ForumID]=="yes"&&
	isset($CatPermissionInfo['CanViewCategory'][$CategoryID])&&
	$CatPermissionInfo['CanViewCategory'][$CategoryID]=="yes") {
$glrquery = query("SELECT * FROM `".$Settings['sqltable']."posts` WHERE `ForumID`=%i AND `TopicID`=%i ORDER BY `TimeStamp` DESC LIMIT 1", array($ForumID,$TopicID));
$glrresult=mysql_query($glrquery);
$glrnum=mysql_num_rows($glrresult);
if($glrnum>0){
$ReplyID1=mysql_result($glrresult,0,"id");
$UsersID1=mysql_result($glrresult,0,"UserID");
$GuestName1=mysql_result($glrresult,0,"GuestName");
$TimeStamp1=mysql_result($glrresult,0,"TimeStamp");
$TimeStamp1=GMTimeChange("F j, Y",$TimeStamp1,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$UsersName1 = GetUserName($UsersID1,$Settings['sqltable']); }
$NumPages = null; $NumRPosts = $NumReply + 1;
if(!isset($Settings['max_posts'])) { $Settings['max_posts'] = 10; }
if($NumRPosts>$Settings['max_posts']) {
$NumPages = ceil($NumRPosts/$Settings['max_posts']); }
if($NumRPosts<=$Settings['max_posts']) {
$NumPages = 1; }
$Users_Name1 = pre_substr($UsersName1,0,20);
if($UsersName1=="Guest") { $UsersName1=$GuestName;
if($UsersName1==null) { $UsersName1="Guest"; } }
if (pre_strlen($UsersName1)>20) { $Users_Name1 = $Users_Name1."...";
$oldusername=$UsersName1; $UsersName1=$Users_Name1; } $lul = null;
if($TimeStamp1!=null) { $lul = null;
if($UsersID1!="-1") {
$lul = url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$UsersID1,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']);
$luln = url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=".$NumPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."&#35;reply".$NumRPosts;
$LastReply = "User: <a href=\"".$lul."\" title=\"".$oldusername."\">".$UsersName1."</a><br />\nTime: <a href=\"".$luln."\">".$TimeStamp1."</a>"; }
if($UsersID1=="-1") {
$lul = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']);
$luln = url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=".$NumPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."&#35;reply".$NumRPosts;
$LastReply = "User: <span title=\"".$oldusername."\">".$UsersName1."</span><br />\nTime: <a href=\"".$luln."\">".$TimeStamp1."</a>"; } }
@mysql_free_result($glrresult);
if($TimeStamp1==null) { $LastReply = "&nbsp;<br />&nbsp;"; }
$PreTopic = $ThemeSet['TopicIcon'];
if ($PinnedTopic>1) { $PinnedTopic = 1; } 
if ($PinnedTopic<0) { $PinnedTopic = 0; }
if(!is_numeric($PinnedTopic)) { $PinnedTopic = 0; }
if ($TopicStat>1) { $TopicStat = 1; } 
if ($TopicStat<0) { $TopicStat = 0; }
if(!is_numeric($TopicStat)) { $TopicStat = 1; }
if ($PinnedTopic==1&&$TopicStat==0) {
	if($NumReply>=$Settings['hot_topic_num']) {
		$PreTopic=$ThemeSet['HotPinTopic']; }
	if($NumReply<$Settings['hot_topic_num']) {
		$PreTopic=$ThemeSet['PinTopic']; } }
if ($TopicStat==1&&$PinnedTopic==0) {
	if($NumReply>=$Settings['hot_topic_num']) {
		$PreTopic=$ThemeSet['HotClosedTopic']; }
	if($NumReply<$Settings['hot_topic_num']) {
		$PreTopic=$ThemeSet['ClosedTopic']; } }
if ($PinnedTopic==0&&$TopicStat==0) {
		if($NumReply>=$Settings['hot_topic_num']) {
			$PreTopic=$ThemeSet['HotTopic']; }
		if($NumReply<$Settings['hot_topic_num']) {
			$PreTopic=$ThemeSet['TopicIcon']; } }
if ($PinnedTopic==1&&$TopicStat==1) {
		if($NumReply>=$Settings['hot_topic_num']) {
			$PreTopic=$ThemeSet['HotPinClosedTopic']; }
		if($NumReply<$Settings['hot_topic_num']) {
			$PreTopic=$ThemeSet['PinClosedTopic']; } }
?>
<tr class="TableRow3" id="Topic<?php echo $TopicID; ?>">
<td class="TableRow3"><div class="topicstate">
<?php echo $PreTopic; ?></div></td>
<td class="TableRow3"><div class="topicname">
<a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>"><?php echo $TopicName; ?></a></div>
<div class="topicdescription"><?php echo $TopicDescription; ?></div></td>
<td class="TableRow3" style="text-align: center;"><?php
if($UsersID!="-1") {
echo "<a href=\"";
echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$UsersID,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']);
echo "\">".$UsersName."</a>"; }
if($UsersID=="-1") {
echo "<span>".$UsersName."</span>"; }
?></td>
<td class="TableRow3" style="text-align: center;"><?php echo $TheTime; ?></td>
<td class="TableRow3" style="text-align: center;"><?php echo $NumReply; ?></td>
<td class="TableRow3"><?php echo $LastReply; ?></td>
</tr>
<?php } ++$i; }
?>
<tr id="SearchEnd" class="TableRow4">
<td class="TableRow4" colspan="6">&nbsp;</td>
</tr>
</table></div>
<?php
@mysql_free_result($result); } } } 
?>
<div>&nbsp;</div>