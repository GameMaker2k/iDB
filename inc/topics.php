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

    $FileInfo: topics.php - Last Update: 10/11/2008 SVN 174 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="topics.php"||$File3Name=="/topics.php") {
	require('index.php');
	exit(); }
if(!is_numeric($_GET['id'])) { $_GET['id'] = null; }
if(!is_numeric($_GET['page'])) { $_GET['page'] = null; }
$prequery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `id`=%i LIMIT 1", array($_GET['id']));
$preresult=mysql_query($prequery);
$prenum=mysql_num_rows($preresult);
if($prenum==0) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); @mysql_free_result($preresult);
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($prenum>=1) {
$ForumID=mysql_result($preresult,0,"id");
$ForumCatID=mysql_result($preresult,0,"CategoryID");
$ForumName=mysql_result($preresult,0,"Name");
$ForumType=mysql_result($preresult,0,"ForumType");
$RedirectURL=mysql_result($preresult,0,"RedirectURL");
$RedirectTimes=mysql_result($preresult,0,"Redirects");
$NumberViews=mysql_result($preresult,0,"NumViews");
$NumberPosts=mysql_result($preresult,0,"NumPosts");
$NumberTopics=mysql_result($preresult,0,"NumTopics");
$PostCountAdd=mysql_result($preresult,0,"PostCountAdd");
$CanHaveTopics=mysql_result($preresult,0,"CanHaveTopics");
@mysql_free_result($preresult);
$ForumType = strtolower($ForumType); $CanHaveTopics = strtolower($CanHaveTopics);
if($CanHaveTopics=="yes"&&$ForumType=="subforum") { 
if($_GET['act']=="create"||$_GET['act']=="maketopic"||
	$_POST['act']=="maketopics") { $ForumCheck = "skip"; } }
if(!isset($CatPermissionInfo['CanViewCategory'][$ForumCatID])) {
	$CatPermissionInfo['CanViewCategory'][$ForumCatID] = "no"; }
if($CatPermissionInfo['CanViewCategory'][$ForumCatID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$ForumCatID]!="yes") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if(!isset($PermissionInfo['CanViewForum'][$ForumID])) {
	$PermissionInfo['CanViewForum'][$ForumID] = "no"; }
if($PermissionInfo['CanViewForum'][$ForumID]=="no"||
	$PermissionInfo['CanViewForum'][$ForumID]!="yes") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($CatPermissionInfo['CanViewCategory'][$ForumCatID]=="yes"&&
	$PermissionInfo['CanViewForum'][$ForumID]=="yes") {
if($ForumType!="redirect") {
if($NumberViews==0||$NumberViews==null) { $NewNumberViews = 1; }
if($NumberViews!=0&&$NumberViews!=null) { $NewNumberViews = $NumberViews + 1; }
$viewup = query("UPDATE `".$Settings['sqltable']."forums` SET `NumViews`=%i WHERE `id`=%i", array($NewNumberViews,$_GET['id']));
mysql_query($viewup); }
if($ForumType=="redirect") {
if($RedirectTimes==0||$RedirectTimes==null) { $NewRedirTime = 1; }
if($RedirectTimes!=0&&$RedirectTimes!=null) { $NewRedirTime = $RedirectTimes + 1; }
$redirup = query("UPDATE `".$Settings['sqltable']."forums` SET `Redirects`=%i WHERE `id`=%i", array($NewRedirTime,$_GET['id']));
mysql_query($redirup);
if($RedirectURL!="http://"&&$RedirectURL!="") {
redirect("location",$RedirectURL,0,null,false); ob_clean();
@header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($RedirectURL=="http://"||$RedirectURL=="") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); } }
if($ForumCheck!="skip") {
if($ForumType=="subforum") {
redirect("location",$basedir.url_maker($exfile['subforum'],$Settings['file_ext'],"act=".$_GET['act']."&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['subforum'],$exqstr['subforum'],FALSE));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); } }
if($PermissionInfo['CanMakeTopics'][$ForumID]=="yes"&&$CanHaveTopics=="yes") {
?>
<table style="width: 100%;" class="Table2">
<tr>
 <td style="width: 0%; text-align: left;">&nbsp;</td>
 <td style="width: 100%; text-align: right;">
 <?php if($PermissionInfo['CanMakeTopics'][$ForumID]=="yes"&&$CanHaveTopics=="yes") { ?>
 <a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=create&id=".$ForumID,$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ThemeSet['NewTopic']; ?></a>
 <?php } ?></td>
</tr>
</table>
<div>&nbsp;</div>
<?php }
if($_GET['act']=="view") {
if($NumberTopics==null) { 
	$NumberTopics = 0; }
$num=$NumberTopics;
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
$PageLimit = $nums - $Settings['max_posts'];
if($PageLimit<0) { $PageLimit = 0; }
//End Topic Page Code
$i=0;
$query = query("SELECT * FROM `".$Settings['sqltable']."topics` WHERE `ForumID`=%i ORDER BY `Pinned` DESC, `LastUpdate` DESC LIMIT %i,%i", array($_GET['id'],$PageLimit,$Settings['max_topics']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
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
if($NumberTopics==0) {
$pagenumi = 0;
$pstring = $pstring."<a href=\"".url_maker($exfile[$ForumType],$Settings['file_ext'],"act=view&id=".$_GET['id']."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType])."\">1</a> "; }
while ($pagei < $pagenumi) {
if($Pagez[$pagei]!=null&&
   $Pagez[$pagei]!="First"&&
   $Pagez[$pagei]!="Last") {
$pstring = $pstring."<a href=\"".url_maker($exfile[$ForumType],$Settings['file_ext'],"act=view&id=".$_GET['id']."&page=".$Pagez[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType])."\">".$Pagez[$pagei]."</a> "; }
if($Pagez[$pagei]=="First") {
$pstring = $pstring."<a href=\"".url_maker($exfile[$ForumType],$Settings['file_ext'],"act=view&id=".$_GET['id']."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType])."\">&lt; First</a> ... "; }
if($Pagez[$pagei]=="Last") {
$pstring = $pstring."... <a href=\"".url_maker($exfile[$ForumType],$Settings['file_ext'],"act=view&id=".$_GET['id']."&page=".$pagenum,$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType])."\">Last &gt;</a> "; }
	++$pagei; } $pstring = $pstring."</div>";
echo $pstring;
//List Page Number Code end
?>
<div class="Table1Border">
<table class="Table1" id="Forum<?php echo $ForumID; ?>">
<tr id="ForumStart<?php echo $ForumID; ?>" class="TableRow1">
<td class="TableRow1" colspan="6"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=view&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>#<?php echo $ForumID; ?>"><?php echo $ForumName; ?></a></span>
<?php echo "<span style=\"float: right;\">&nbsp;</span>"; ?></td>
</tr>
<tr id="TopicStatRow<?php echo $ForumID; ?>" class="TableRow2">
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
$glrquery = query("SELECT * FROM `".$Settings['sqltable']."posts` WHERE `TopicID`=%i ORDER BY `TimeStamp` DESC LIMIT 1", array($TopicID));
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
if($UsersName1=="Guest") { $UsersName1=$GuestName1;
if($UsersName1==null) { $UsersName1="Guest"; } }
if (pre_strlen($UsersName1)>20) { $Users_Name1 = $Users_Name1."...";
$oldusername=$UsersName1; $UsersName1=$Users_Name1; } $lul = null;
if($TimeStamp1!=null) { $lul = null;
if($UsersID1!="-1") {
$lul = url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$UsersID1,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']);
$luln = url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=".$NumPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."&#35;reply".$NumRPosts;
$LastReply = "User: <a href=\"".$lul."\" title=\"".$oldusername."\">".$UsersName1."</a><br />\nTime: <a href=\"".$luln."\">".$TimeStamp1."</a>"; }
if($UsersID1=="-1") {
$lul = url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$UsersID1,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']);
$luln = url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=".$NumPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."&#35;reply".$NumRPosts;
$LastReply = "Guest: <span title=\"".$oldusername."\">".$UsersName1."</span><br />\nTime: <a href=\"".$luln."\">".$TimeStamp1."</a>"; } }
@mysql_free_result($glrresult);
if(!isset($TimeStamp1)) { $TimeStamp1 = null; } if(!isset($LastReply)) { $LastReply = null; }
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
<a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>"><?php echo $TopicName; ?></a></div>
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
<?php ++$i; } 
?>
<tr id="ForumEnd<?php echo $ForumID; ?>" class="TableRow4">
<td class="TableRow4" colspan="6">&nbsp;</td>
</tr>
</table></div>
<div>&nbsp;</div>
<?php
@mysql_free_result($result); }
if($_GET['act']=="create") {
if($PermissionInfo['CanMakeTopics'][$ForumID]=="no"||$CanHaveTopics=="no") { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
?>
<div class="Table1Border">
<table class="Table1" id="MakeTopic<?php echo $ForumID; ?>">
<tr class="TableRow1" id="TopicStart<?php echo $ForumID; ?>">
<td class="TableRow1" colspan="2"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=view&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ForumName; ?></a></span>
<?php echo "<span style=\"float: right;\">&nbsp;</span>"; ?></td>
</tr>
<tr id="MakeTopicRow<?php echo $ForumID; ?>" class="TableRow2">
<td class="TableRow2" colspan="2" style="width: 100%;">Making a Topic in <?php echo $ForumName; ?></td>
</tr>
<tr class="TableRow3" id="MkTopic<?php echo $ForumID; ?>">
<td class="TableRow3" style="width: 15%; vertical-align: middle; text-align: center;">
<div style="width: 100%; height: 160px; overflow: auto;">
<table style="width: 100%; text-align: center;"><?php
$renee_query=query("SELECT * FROM `".$Settings['sqltable']."smileys` WHERE `Show`='yes'", array(null));
$renee_result=mysql_query($renee_query);
$renee_num=mysql_num_rows($renee_result);
$renee_s=0; $SmileRow=0; $SmileCRow=0;
while ($renee_s < $renee_num) { ++$SmileRow;
$FileName=mysql_result($renee_result,$renee_s,"FileName");
$SmileName=mysql_result($renee_result,$renee_s,"SmileName");
$SmileText=mysql_result($renee_result,$renee_s,"SmileText");
$SmileDirectory=mysql_result($renee_result,$renee_s,"Directory");
$ShowSmile=mysql_result($renee_result,$renee_s,"Show");
$ReplaceType=mysql_result($renee_result,$renee_s,"ReplaceCI");
if($SmileRow==1) { ?><tr>
	<?php } if($SmileRow<5) { ++$SmileCRow; ?>
	<td>&nbsp;<img src="<?php echo $SmileDirectory."".$FileName; ?>" style="vertical-align: middle; border: 0px; cursor: pointer;" title="<?php echo $SmileName; ?>" alt="<?php echo $SmileName; ?>" onclick="addsmiley('TopicPost','&nbsp;<?php echo htmlspecialchars($SmileText, ENT_QUOTES, $Settings['charset']); ?>&nbsp;')" />&nbsp;</td>
	<?php } if($SmileRow==5) { ++$SmileCRow; ?>
	<td>&nbsp;<img src="<?php echo $SmileDirectory."".$FileName; ?>" style="vertical-align: middle; border: 0px; cursor: pointer;" title="<?php echo $SmileName; ?>" alt="<?php echo $SmileName; ?>" onclick="addsmiley('TopicPost','&nbsp;<?php echo htmlspecialchars($SmileText, ENT_QUOTES, $Settings['charset']); ?>&nbsp;')" />&nbsp;</td></tr>
	<?php $SmileCRow=0; $SmileRow=0; }
++$renee_s; }
if($SmileCRow<5&&$SmileCRow!=0) {
$SmileCRowL = 5 - $SmileCRow;
echo "<td colspan=\"".$SmileCRowL."\">&nbsp;</td></tr>"; }
echo "</table>";
@mysql_free_result($renee_result);
?></div></td>
<td class="TableRow3" style="width: 85%;">
<form style="display: inline;" method="post" id="MkTopicForm" action="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=maketopic&id=".$ForumID,$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="TopicName">Insert Topic Name:</label></td>
	<td style="width: 50%;"><input maxlength="30" type="text" name="TopicName" class="TextBox" id="TopicName" size="20" /></td>
</tr><?php if($_SESSION['UserGroup']==$Settings['GuestGroup']) { ?><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="GuestName">Insert Guest Name:</label></td>
	<?php if(!isset($_SESSION['GuestName'])) { ?>
	<td style="width: 50%;"><input maxlength="25" type="text" name="GuestName" class="TextBox" id="GuestName" size="20" /></td>
	<?php } if(isset($_SESSION['GuestName'])) { ?>
	<td style="width: 50%;"><input maxlength="25" type="text" name="GuestName" class="TextBox" id="GuestName" size="20" value="<?php echo $_SESSION['GuestName']; ?>" /></td>
</tr><?php } } ?><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="TopicDesc">Insert Topic Description:</label></td>
	<td style="width: 50%;"><input maxlength="45" type="text" name="TopicDesc" class="TextBox" id="TopicDesc" size="20" /></td>
</tr>
</table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<?php if($_SESSION['UserGroup']==$Settings['GuestGroup']&&$Settings['captcha_guest']=="on") { ?>
<label class="TextBoxLabel" for="signcode"><img src="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=MkCaptcha",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>" alt="CAPTCHA Code" title="CAPTCHA Code" /></label><br />
<input maxlength="25" type="text" class="TextBox" name="signcode" size="20" id="signcode" value="Enter SignCode" /><br />
<?php } ?>
<label class="TextBoxLabel" for="TopicPost">Insert Your Post:</label><br />
<textarea rows="10" name="TopicPost" id="TopicPost" cols="40" class="TextBox"></textarea><br />
<input type="hidden" name="act" value="maketopics" style="display: none;" />
<?php if($_SESSION['UserGroup']!=$Settings['GuestGroup']) { ?>
<input type="hidden" name="GuestName" value="null" style="display: none;" />
<?php } ?>
<input type="submit" class="Button" value="Make Topic" name="make_topic" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form></td></tr>
<tr id="MkTopicEnd<?php echo $ForumID; ?>" class="TableRow4">
<td class="TableRow4" colspan="2">&nbsp;</td>
</tr>
</table></div>
<div>&nbsp;</div>
<?php } if($_GET['act']=="maketopic"&&$_POST['act']=="maketopics") {
if($PermissionInfo['CanMakeTopics'][$ForumID]=="no"||$CanHaveTopics=="no") { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
$MyUserID = $_SESSION['UserID']; if($MyUserID=="0"||$MyUserID==null) { $MyUserID = -1; }
$REFERERurl = parse_url($_SERVER['HTTP_REFERER']);
$URL['REFERER'] = $REFERERurl['host'];
$URL['HOST'] = $_SERVER["SERVER_NAME"];
$REFERERurl = null;
if(!isset($_POST['TopicName'])) { $_POST['TopicName'] = null; }
if(!isset($_POST['TopicDesc'])) { $_POST['TopicDesc'] = null; }
if(!isset($_POST['TopicPost'])) { $_POST['TopicPost'] = null; }
if(!isset($_POST['GuestName'])) { $_POST['GuestName'] = null; }
if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	$Settings['captcha_guest']=="on") {
require($SettDir['inc']."captcha.php"); }
?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=view&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ForumName; ?></a></span>
<?php echo "<span style=\"float: right;\">&nbsp;</span>"; ?></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">&nbsp;Make Topic Message: </th>
</tr>
<tr class="TableRow3">
<td class="TableRow3">
<table style="width: 100%; height: 25%; text-align: center;">
<?php if (pre_strlen($_POST['TopicName'])>="30") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your Topic Name is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	$Settings['captcha_guest']=="on") {
if (PhpCaptcha::Validate($_POST['signcode'])) {
//echo 'Valid code entered';
} else { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />Invalid code entered<br />
	</span>&nbsp;</td>
</tr>
<?php } } if (pre_strlen($_POST['TopicDesc'])>="45") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your Topic Description is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	pre_strlen($_POST['GuestName'])>="25") { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You Guest Name is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($Settings['TestReferer']===true) {
	if ($URL['HOST']!=$URL['REFERER']) { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Sorry the referering url dose not match our host name.<br />
	</span>&nbsp;</td>
</tr>
<?php } }
$_POST['TopicName'] = stripcslashes(htmlspecialchars($_POST['TopicName'], ENT_QUOTES, $Settings['charset']));
//$_POST['TopicName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['TopicName']);
$_POST['TopicName'] = @remove_spaces($_POST['TopicName']);
$_POST['TopicDesc'] = stripcslashes(htmlspecialchars($_POST['TopicDesc'], ENT_QUOTES, $Settings['charset']));
//$_POST['TopicDesc'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['TopicDesc']);
$_POST['TopicDesc'] = @remove_spaces($_POST['TopicDesc']);
$_POST['GuestName'] = stripcslashes(htmlspecialchars($_POST['GuestName'], ENT_QUOTES, $Settings['charset']));
//$_POST['GuestName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['GuestName']);
$_POST['GuestName'] = @remove_spaces($_POST['GuestName']);
$_POST['TopicPost'] = stripcslashes(htmlspecialchars($_POST['TopicPost'], ENT_QUOTES, $Settings['charset']));
//$_POST['TopicPost'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['TopicPost']);
$_POST['TopicPost'] = remove_bad_entities($_POST['TopicPost']);
//$_POST['TopicPost'] = @remove_spaces($_POST['TopicPost']);
if($_SESSION['UserGroup']==$Settings['GuestGroup']) {
if(isset($_POST['GuestName'])&&$_POST['GuestName']!=null) {
@setcookie("GuestName", $_POST['GuestName'], time() + (7 * 86400), $cbasedir);
$_SESSION['GuestName']=$_POST['GuestName']; } }
/*    <_<  iWordFilter  >_>      
   by Kazuki Przyborowski - Cool Dude 2k */
$katarzynaqy=query("SELECT * FROM `".$Settings['sqltable']."wordfilter`", array(null));
$katarzynart=mysql_query($katarzynaqy);
$katarzynanm=mysql_num_rows($katarzynart);
$katarzynas=0;
while ($katarzynas < $katarzynanm) {
$Filter=mysql_result($katarzynart,$katarzynas,"Filter");
$Replace=mysql_result($katarzynart,$katarzynas,"Replace");
$CaseInsensitive=mysql_result($katarzynart,$katarzynas,"CaseInsensitive");
if($CaseInsensitive=="on") { $CaseInsensitive = "yes"; }
if($CaseInsensitive=="off") { $CaseInsensitive = "no"; }
if($CaseInsensitive!="yes"||$CaseInsensitive!="no") { $CaseInsensitive = "no"; }
$WholeWord=mysql_result($katarzynart,$katarzynas,"WholeWord");
if($WholeWord=="on") { $WholeWord = "yes"; }
if($WholeWord=="off") { $WholeWord = "no"; }
if($WholeWord!="yes"&&$WholeWord!="no") { $WholeWord = "no"; }
$Filter = preg_quote($Filter, "/");
if($CaseInsensitive!="yes"&&$WholeWord=="yes") {
$_POST['TopicDesc'] = preg_replace("/\b(".$Filter.")\b/", $Replace, $_POST['TopicDesc']); 
$_POST['TopicPost'] = preg_replace("/\b(".$Filter.")\b/", $Replace, $_POST['TopicPost']); }
if($CaseInsensitive=="yes"&&$WholeWord=="yes") {
$_POST['TopicDesc'] = preg_replace("/\b(".$Filter.")\b/i", $Replace, $_POST['TopicDesc']); 
$_POST['TopicPost'] = preg_replace("/\b(".$Filter.")\b/i", $Replace, $_POST['TopicPost']); }
if($CaseInsensitive!="yes"&&$WholeWord!="yes") {
$_POST['TopicDesc'] = preg_replace("/".$Filter."/", $Replace, $_POST['TopicDesc']); 
$_POST['TopicPost'] = preg_replace("/".$Filter."/", $Replace, $_POST['TopicPost']); }
if($CaseInsensitive=="yes"&&$WholeWord!="yes") {
$_POST['TopicDesc'] = preg_replace("/".$Filter."/i", $Replace, $_POST['TopicDesc']); 
$_POST['TopicPost'] = preg_replace("/".$Filter."/i", $Replace, $_POST['TopicPost']); }
++$katarzynas; } @mysql_free_result($katarzynart);
$lonewolfqy=query("SELECT * FROM `".$Settings['sqltable']."restrictedwords` WHERE `RestrictedTopicName`='yes' or `RestrictedUserName`='yes'", array(null));
$lonewolfrt=mysql_query($lonewolfqy);
$lonewolfnm=mysql_num_rows($lonewolfrt);
$lonewolfs=0; $RMatches = null; $RGMatches = null;
while ($lonewolfs < $lonewolfnm) {
$RWord=mysql_result($lonewolfrt,$lonewolfs,"Word");
$RCaseInsensitive=mysql_result($lonewolfrt,$lonewolfs,"CaseInsensitive");
if($RCaseInsensitive=="on") { $RCaseInsensitive = "yes"; }
if($RCaseInsensitive=="off") { $RCaseInsensitive = "no"; }
if($RCaseInsensitive!="yes"||$RCaseInsensitive!="no") { $RCaseInsensitive = "no"; }
$RWholeWord=mysql_result($lonewolfrt,$lonewolfs,"WholeWord");
if($RWholeWord=="on") { $RWholeWord = "yes"; }
if($RWholeWord=="off") { $RWholeWord = "no"; }
if($RWholeWord!="yes"||$RWholeWord!="no") { $RWholeWord = "no"; }
$RestrictedTopicName=mysql_result($lonewolfrt,$lonewolfs,"RestrictedTopicName");
if($RestrictedTopicName=="on") { $RestrictedTopicName = "yes"; }
if($RestrictedTopicName=="off") { $RestrictedTopicName = "no"; }
if($RestrictedTopicName!="yes"||$RestrictedTopicName!="no") { $RestrictedTopicName = "no"; }
$RestrictedUserName=mysql_result($lonewolfrt,$lonewolfs,"RestrictedUserName");
if($RestrictedUserName=="on") { $RestrictedUserName = "yes"; }
if($RestrictedUserName=="off") { $RestrictedUserName = "no"; }
if($RestrictedUserName!="yes"||$RestrictedUserName!="no") { $RestrictedUserName = "no"; }
$RWord = preg_quote($RWord, "/");
if($RCaseInsensitive!="yes"&&$RWholeWord=="yes") {
if($RestrictedTopicName=="yes") {
$RMatches = preg_match("/\b(".$RWord.")\b/", $_POST['TopicName']);
	if($RMatches==true) { break 1; } }
if($RestrictedUserName=="yes") {
$RGMatches = preg_match("/\b(".$RWord.")\b/", $_POST['GuestName']);
	if($RGMatches==true) { break 1; } } }
if($RCaseInsensitive=="yes"&&$RWholeWord=="yes") {
if($RestrictedTopicName=="yes") {
$RMatches = preg_match("/\b(".$RWord.")\b/i", $_POST['TopicName']);
	if($RMatches==true) { break 1; } }
if($RestrictedUserName=="yes") {
$RGMatches = preg_match("/\b(".$RWord.")\b/i", $_POST['GuestName']);
	if($RGMatches==true) { break 1; } } }
if($RCaseInsensitive!="yes"&&$RWholeWord!="yes") {
if($RestrictedTopicName=="yes") {
$RMatches = preg_match("/".$RWord."/", $_POST['TopicName']);
	if($RMatches==true) { break 1; } }
if($RestrictedUserName=="yes") {
$RGMatches = preg_match("/".$RWord."/", $_POST['GuestName']);
	if($RGMatches==true) { break 1; } } }
if($RCaseInsensitive=="yes"&&$RWholeWord!="yes") {
if($RestrictedTopicName=="yes") {
$RMatches = preg_match("/".$RWord."/i", $_POST['TopicName']);
	if($RMatches==true) { break 1; } }
if($RestrictedUserName=="yes") {
$RGMatches = preg_match("/".$RWord."/i", $_POST['GuestName']);
	if($RGMatches==true) { break 1; } } }
++$lonewolfs; } @mysql_free_result($lonewolfrt);
if ($_POST['TopicName']==null) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a Topic Name.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($_POST['TopicDesc']==null) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a Topic Description.<br />
	</span>&nbsp;</td>
</tr>
<?php } if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	$_POST['GuestName']==null) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a Guest Name.<br />
	</span>&nbsp;</td>
</tr>
<?php } if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	$RGMatches==true) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />This Guest Name is restricted to use.<br />
	</span>&nbsp;</td>
</tr>
<?php } if($PermissionInfo['CanMakeTopics'][$ForumID]=="no"||$CanHaveTopics=="no") { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You do not have permission to make a topic here.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($_POST['TopicPost']==null) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a Topic Post.<br />
	</span>&nbsp;</td>
</tr>
<?php } if($RMatches==true) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />This Topic Name is restricted to use.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($Error=="Yes") {
@redirect("refresh",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false),"4"); ?>
<tr>
	<td><span class="TableMessage">
	<br />Click <a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">here</a> to goto index page.<br />&nbsp;
	</span><br /></td>
</tr>
<?php } if ($Error!="Yes") { $LastActive = GMTimeStamp();
$topicid = getnextid($Settings['sqltable'],"topics");
$postid = getnextid($Settings['sqltable'],"posts");
$requery = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i LIMIT 1", array($MyUserID));
$reresult=mysql_query($requery);
$renum=mysql_num_rows($reresult);
$rei=0;
while ($rei < $renum) {
$User1ID=$MyUserID;
$User1Name=mysql_result($reresult,$rei,"Name");
if($_SESSION['UserGroup']==$Settings['GuestGroup']) { $User1Name = $_POST['GuestName']; }
$User1Email=mysql_result($reresult,$rei,"Email");
$User1Title=mysql_result($reresult,$rei,"Title");
$User1GroupID=mysql_result($reresult,$rei,"GroupID");
$PostCount=mysql_result($reresult,$rei,"PostCount");
if($PostCountAdd=="on") { $NewPostCount = $PostCount + 1; }
if(!isset($NewPostCount)) { $NewPostCount = $PostCount; }
$gquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i LIMIT 1", array($User1GroupID));
$gresult=mysql_query($gquery);
$User1Group=mysql_result($gresult,0,"Name");
@mysql_free_result($gresult);
$User1IP=$_SERVER['REMOTE_ADDR'];
++$rei; } @mysql_free_result($reresult);
$query = query("INSERT INTO `".$Settings['sqltable']."topics` VALUES (".$topicid.",%i,%i,%i,'%s',%i,%i,'%s','%s',0,0,0,0)", array($ForumID,$ForumCatID,$User1ID,$User1Name,$LastActive,$LastActive,$_POST['TopicName'],$_POST['TopicDesc']));
mysql_query($query);
$query = query("INSERT INTO `".$Settings['sqltable']."posts` VALUES (".$postid.",".$topicid.",%i,%i,%i,'%s',%i,%i,0,'%s','%s','%s','0')", array($ForumID,$ForumCatID,$User1ID,$User1Name,$LastActive,$LastActive,$_POST['TopicPost'],$_POST['TopicDesc'],$User1IP));
mysql_query($query);
if($User1ID!=0&&$User1ID!=-1) {
$queryupd = query("UPDATE `".$Settings['sqltable']."members` SET `LastActive`=%i,`IP`='%s',`PostCount`=%i WHERE `id`=%i", array($LastActive,$User1IP,$NewPostCount,$User1ID));
mysql_query($queryupd); }
$NewNumPosts = $NumberPosts + 1; $NewNumTopics = $NumberTopics + 1;
$queryupd = query("UPDATE `".$Settings['sqltable']."forums` SET `NumPosts`=%i,`NumTopics`=%i WHERE `id`=%i", array($NewNumPosts,$NewNumTopics,$ForumID));
mysql_query($queryupd);
@redirect("refresh",$basedir.url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$topicid."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'],FALSE),"3");
?><tr>
	<td><span class="TableMessage"><br />
	Topic <?php echo $_POST['TopicName']; ?> was started.<br />
	Click <a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$topicid."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>">here</a> to continue to topic.<br />&nbsp;
	</span><br /></td>
</tr>
<?php } ?>
</table>
</td></tr>
<tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table></div>
<div>&nbsp;</div>
<?php }
if($PermissionInfo['CanMakeTopics'][$ForumID]=="yes"&&$CanHaveTopics=="yes") { ?>
<table class="Table2" style="width: 100%;">
<tr>
 <td style="width: 0%; text-align: left;">&nbsp;</td>
 <td style="width: 100%; text-align: right;">
 <?php if($PermissionInfo['CanMakeTopics'][$ForumID]=="yes"&&$CanHaveTopics=="yes") { ?>
 <a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=create&id=".$ForumID,$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ThemeSet['NewTopic']; ?></a>
 <?php } ?></td>
</tr>
</table>
<div>&nbsp;</div>
<?php } } } ?>