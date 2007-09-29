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

    $FileInfo: replys.php - Last Update: 09/29/2007 SVN 112 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="replys.php"||$File3Name=="/replys.php") {
	require('index.php');
	exit(); }
$prequery = query("SELECT * FROM `".$Settings['sqltable']."topics` WHERE `id`=%i", array($_GET['id']));
$preresult=mysql_query($prequery);
$prenum=mysql_num_rows($preresult);
if($prenum==0) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); @mysql_free_result($preresult);
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($prenum>=1) {
$TopicName=mysql_result($preresult,0,"TopicName");
$TopicID=mysql_result($preresult,0,"id");
$TopicForumID=mysql_result($preresult,0,"ForumID");
$TopicCatID=mysql_result($preresult,0,"CategoryID");
$TopicClosed=mysql_result($preresult,0,"Closed");
$NumberReplies=mysql_result($preresult,0,"NumReply");
$ViewTimes=mysql_result($preresult,0,"NumViews");
@mysql_free_result($preresult);
if(!isset($CatPermissionInfo['CanViewCategory'][$TopicCatID])) {
	$CatPermissionInfo['CanViewCategory'][$TopicCatID] = "no"; }
if($CatPermissionInfo['CanViewCategory'][$TopicCatID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$TopicCatID]!="yes") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if(!isset($PermissionInfo['CanViewForum'][$TopicForumID])) {
	$PermissionInfo['CanViewForum'][$TopicForumID] = "no"; }
if($PermissionInfo['CanViewForum'][$TopicForumID]=="no"||
	$PermissionInfo['CanViewForum'][$TopicForumID]!="yes") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
$CanMakeReply = null;
if($CatPermissionInfo['CanViewCategory'][$TopicCatID]=="yes"&&
	$PermissionInfo['CanViewForum'][$TopicForumID]=="yes") {
if($PermissionInfo['CanMakeReplys'][$TopicForumID]=="yes"||$PermissionInfo['CanMakeTopics'][$TopicForumID]=="yes") {
$CanMakeReply = "no";
if($TopicClosed==0&&$PermissionInfo['CanMakeReplys'][$TopicForumID]=="yes") {
	$CanMakeReply = "yes"; }
if($TopicClosed==1&&$PermissionInfo['CanMakeReplysClose'][$TopicForumID]=="yes"
	&&$PermissionInfo['CanMakeReplys'][$TopicForumID]=="yes") {
		$CanMakeReply = "yes"; }
if($PermissionInfo['CanMakeReplys'][$TopicForumID]=="yes"||$PermissionInfo['CanMakeTopics'][$TopicForumID]=="yes") {
?>
<table style="width: 100%;" class="Table2">
<tr>
 <td style="width: 0%; text-align: left;">&nbsp;</td>
 <td style="width: 100%; text-align: right;">
 <?php if($CanMakeReply=="yes") { ?>
 <a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=create&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>"><?php echo $ThemeSet['AddReply']; ?></a>
 <?php } if($PermissionInfo['CanMakeTopics'][$TopicForumID]=="yes") {
	if($CanMakeReply=="yes") { ?>
 <?php echo $ThemeSet['ButtonDivider']; } ?>
 <a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=create&id=".$TopicForumID,$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ThemeSet['NewTopic']; ?></a>
 <?php } ?></td>
</tr>
</table>
<div>&nbsp;</div>
<?php } }
if($_GET['act']=="view") {
$query = query("SELECT * FROM `".$Settings['sqltable']."posts` WHERE `TopicID`=%i ORDER BY `TimeStamp` ASC", array($_GET['id']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
//Start Reply Page Code (Will be used at later time)
if(!isset($Settings['max_posts'])) { $Settings['max_posts'] = 10; }
if($_GET['page']==null) { $_GET['page'] = 1; } 
if($_GET['page']<=0) { $_GET['page'] = 1; }
$nums = $_GET['page'] * $Settings['max_posts'];
if($nums>$num) { $nums = $num; }
$numz = $nums - $Settings['max_posts'];
if($numz<=0) { $numz = 0; }
$i=$numz;
if($nums<$num) { $nextpage = $_GET['page'] + 1; }
if($nums>=$num) { $nextpage = $_GET['page']; }
if($numz>=$Settings['max_posts']) { $backpage = $_GET['page'] - 1; }
if($_GET['page']<=1) { $backpage = 1; }
$pnum = $num; $l = 1; $Pages = null;
while ($pnum>0) {
if($pnum>=$Settings['max_posts']) { 
	$pnum = $pnum - $Settings['max_posts']; 
	$Pages[$l] = $l; ++$l; }
if($pnum<$Settings['max_posts']&&$pnum>0) { 
	$pnum = $pnum - $pnum; 
	$Pages[$l] = $l; ++$l; } }
//End Reply Page Code (Its not used yet but its still good to have :P )
$i=0;
if($num==0) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($num!=0) { 
if($ViewTimes==0||$ViewTimes==null) { $NewViewTimes = 1; }
if($ViewTimes!=0&&$ViewTimes!=null) { $NewViewTimes = $ViewTimes + 1; }
$viewsup = query("UPDATE `".$Settings['sqltable']."topics` SET `NumViews`='%s' WHERE `id`=%i", array($NewViewTimes,$_GET['id']));
mysql_query($viewsup); }
$pagenum=count($Pages);
$pagei=1; $pstring = "<div class=\"PageList\">Pages: ";
while ($pagei <= $pagenum) {
$pstring = $pstring."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$_GET['id']."&page=".$Pages[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">".$Pages[$pagei]."</a> ";
	++$pagei; } $pstring = $pstring."</div>";
while ($i < $num) {
$MyPostID=mysql_result($result,$i,"id");
$MyTopicID=mysql_result($result,$i,"TopicID");
$MyForumID=mysql_result($result,$i,"ForumID");
$MyCategoryID=mysql_result($result,$i,"CategoryID");
$MyUserID=mysql_result($result,$i,"UserID");
$MyGuestName=mysql_result($result,$i,"GuestName");
$MyTimeStamp=mysql_result($result,$i,"TimeStamp");
$MyEditTime=mysql_result($result,$i,"LastUpdate");
$MyEditUserID=mysql_result($result,$i,"EditUser");
$MyTimeStamp=GMTimeChange("M j, Y, g:i a",$MyTimeStamp,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$MyPost=mysql_result($result,$i,"Post");
$MyPost = preg_replace("/\<br\>/", "<br />\n", nl2br($MyPost));
$MyDescription=mysql_result($result,$i,"Description");
$requery = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i", array($MyUserID));
$reresult=mysql_query($requery);
$renum=mysql_num_rows($reresult);
$rei=0;
while ($rei < $renum) {
$User1ID=$MyUserID; $GuestName = $MyGuestName;
$User1Name=mysql_result($reresult,$rei,"Name");
$User1Email=mysql_result($reresult,$rei,"Email");
$User1Title=mysql_result($reresult,$rei,"Title");
$User1Joined=mysql_result($reresult,$rei,"Joined");
$User1Joined=GMTimeChange("M j Y",$User1Joined,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$User1GroupID=mysql_result($reresult,$rei,"GroupID");
$gquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i", array($User1GroupID));
$gresult=mysql_query($gquery);
$User1Group=mysql_result($gresult,0,"Name");
@mysql_free_result($gresult);
$User1Signature=mysql_result($reresult,$rei,"Signature");
$User1Avatar=mysql_result($reresult,$rei,"Avatar");
$User1AvatarSize=mysql_result($reresult,$rei,"AvatarSize");
if ($User1Avatar=="http://"||$User1Avatar==null) {
$User1Avatar=$ThemeSet['NoAvatar'];
$User1AvatarSize=$ThemeSet['NoAvatarSize']; }
$AvatarSize1=explode("x", $User1AvatarSize);
$AvatarSize1W=$AvatarSize1[0]; $AvatarSize1H=$AvatarSize1[1];
$User1Website=mysql_result($reresult,$rei,"Website");
$User1PostCount=mysql_result($reresult,$rei,"PostCount");
$User1IP=mysql_result($reresult,$rei,"IP");
++$rei; } @mysql_free_result($reresult);
if($User1Name=="Guest") { $User1Name=$GuestName;
if($User1Name==null) { $User1Name="Guest"; } }
$MySubPost = null;
if($MyEditTime!=$MyTimeStamp&&$MyEditUserID!=0) {
$euquery = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i", array($MyEditUserID));
$euresult = mysql_query($euquery);
$eunum = mysql_num_rows($euresult);
$eui=0; while ($eui < $eunum) {
	$EditUserID = $MyEditUserID;
	$EditUserName = mysql_result($euresult,$eui,"Name");
	++$eui; }
	$MyEditTime = GMTimeChange("M j, Y, g:i a",$MyEditTime,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
	$MySubPost = "<div class=\"EditReply\"><br />This post has been edited by <b>".$EditUserName."</b> on ".$MyEditTime."</div>"; }
$MyPost = text2icons($MyPost,$Settings['sqltable']);
if($MySubPost!=null) { $MyPost = $MyPost."\n".$MySubPost; }
$User1Signature = text2icons($User1Signature,$Settings['sqltable']);
$CanEditReply = false; $CanDeleteReply = false;
if($_SESSION['UserGroup']!=$Settings['GuestGroup']) {
if($PermissionInfo['CanEditReplys'][$MyForumID]=="yes"&&
	$_SESSION['UserID']==$MyUserID) { $CanEditReply = true; }
if($PermissionInfo['CanDeleteReplys'][$MyForumID]=="yes"&&
	$_SESSION['UserID']==$MyUserID) { $CanDeleteReply = true; }
if($PermissionInfo['CanModForum'][$MyForumID]=="yes") { 
	$CanEditReply = true; $CanDeleteReply = true; } }
if($_SESSION['UserID']==0) { 
	$CanEditReply = false; $CanDeleteReply = false; }
$ReplyNum = $i + 1;
?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1" colspan="2"><span style="font-weight: bold; float: left;"><?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."#reply".$ReplyNum; ?>"><?php echo $TopicName; ?></a> ( <?php echo $MyDescription; ?> )</span>
<span style="float: right;">&nbsp;</span></td>
</tr>
<tr class="TableRow2">
<td class="TableRow2" style="vertical-align: middle; width: 160px;">
&nbsp;<?php
if($User1ID!="-1") {
echo "<a href=\"";
echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$User1ID,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']);
echo "\">".$User1Name."</a>"; }
if($User1ID=="-1") {
echo "<span>".$User1Name."</span>"; }
?></td>
<td class="TableRow2" style="vertical-align: middle;">
<div style="text-align: left; float: left;" id="post<?php echo $MyPostID; ?>">
<a style="vertical-align: middle;" id="reply<?php echo $ReplyNum; ?>">
<span style="font-weight: bold;">Time Posted: </span><?php echo $MyTimeStamp; ?></a>
</div>
<div style="text-align: right;"><a href="#Act/Report"><?php echo $ThemeSet['Report']; ?></a><?php echo $ThemeSet['LineDividerTopic']; if($CanEditReply==true) { echo "<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=edit&id=".$MyTopicID."&post=".$MyPostID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">".$ThemeSet['EditReply']; ?></a><?php echo $ThemeSet['LineDividerTopic']; } if($CanDeleteReply==true) { echo "<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=delete&id=".$MyTopicID."&post=".$MyPostID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">".$ThemeSet['DeleteReply']; ?></a><?php echo $ThemeSet['LineDividerTopic']; } ?><a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=create&id=".$TopicID."&post=".$MyPostID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>"><?php echo $ThemeSet['QuoteReply']; ?></a>&nbsp;</div>
</td>
</tr>
<tr class="TableRow3">
<td class="TableRow3" style="vertical-align: top;">
 <?php  /* Avatar Table Thanks For SeanJ's Help at http://seanj.jcink.com/ */  ?>
 <table class="AvatarTable" style="width: 100px; height: 100px; text-align: center;">
	<tr class="AvatarRow" style="width: 100%; height: 100%;">
		<td class="AvatarRow" style="width: 100%; height: 100%; text-align: center; vertical-align: middle;">
		<img src="<?php echo $User1Avatar; ?>" alt="<?php echo $User1Name; ?>'s Avatar" title="<?php echo $User1Name; ?>'s Avatar" style="border: 0px; width: <?php echo $AvatarSize1W; ?>px; height: <?php echo $AvatarSize1H; ?>px;" />
		</td>
	</tr>
 </table><br />
User Title: <?php echo $User1Title; ?><br />
Group: <?php echo $User1Group; ?><br />
Member: <?php 
if($User1ID!="-1") { echo $User1ID; }
if($User1ID=="-1") { echo 0; }
?><br />
Posts: <?php echo $User1PostCount; ?><br />
Joined: <?php echo $User1Joined; ?><br /><br />
</td>
<td class="TableRow3" style="vertical-align: middle;">
<div class="replypost"><?php echo $MyPost; ?></div>
<?php if(isset($User1Signature)) { ?> <br />--------------------
<div class="signature"><?php echo $User1Signature; ?></div><?php } ?>
</td>
</tr>
<tr class="TableRow4">
<td class="TableRow4" colspan="2">
<span style="float: left;">&nbsp;<a href="<?php
if($User1ID!="-1") {
echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$User1ID,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); }
if($User1ID=="-1") {
echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); }
?>"><?php echo $ThemeSet['Profile']; ?></a><?php echo $ThemeSet['LineDividerTopic']; ?><a href="<?php echo $User1Website; ?>" onclick="window.open(this.href);return false;"><?php echo $ThemeSet['WWW']; ?></a><?php echo $ThemeSet['LineDividerTopic']; ?><a href="<?php
if($User1ID!="-1") {
echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=create&id=".$User1ID,$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); }
if($User1ID=="-1") {
echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); }
?>"><?php echo $ThemeSet['PM']; ?></a></span>
<span style="float: right;">&nbsp;</span></td>
</tr>
</table></div>
<div>&nbsp;</div>
<?php ++$i; } @mysql_free_result($result); 
if($CanMakeReply=="yes") {  
if(!isset($_GET['fastreply'])) { $_GET['fastreply'] = false; }
if($_GET['fastreply']==true||
	$_GET['fastreply']=="on") { $fps = " "; }
if($_GET['fastreply']!=true&&
	$_GET['fastreply']!="on") { $fps = " style=\"display: none;\" "; }
$QuoteReply = null; $QuoteDescription = null;
?>
<div class="Table1Border"<?php echo $fps; ?>id="FastReply">
<table class="Table1" id="MakeReply<?php echo $TopicForumID; ?>">
<tr class="TableRow1" id="ReplyStart<?php echo $TopicForumID; ?>">
<td class="TableRow1" colspan="2"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>#<?php echo $TopicID; ?>"><?php echo $TopicName; ?></a></span>
<?php echo "<span style=\"float: right;\">&nbsp;</span>"; ?></td>
</tr>
<tr id="MakeReplyRow<?php echo $TopicForumID; ?>" class="TableRow2">
<td class="TableRow2" colspan="2" style="width: 100%;">Making a Reply in Topic <?php echo $TopicName; ?></td>
</tr>
<tr class="TableRow3" id="MkReply<?php echo $TopicForumID; ?>">
<td class="TableRow3" style="width: 15%; vertical-align: middle; text-align: center;">
<div style="width: 100%; height: 160px; overflow: auto;"><?php
$renee_query=query("SELECT * FROM `".$Settings['sqltable']."smileys`", array(null));
$renee_result=mysql_query($renee_query);
$renee_num=mysql_num_rows($renee_result);
$renee_s=0; $SmileRow=1;
while ($renee_s < $renee_num) {
$FileName=mysql_result($renee_result,$renee_s,"FileName");
$SmileName=mysql_result($renee_result,$renee_s,"SmileName");
$SmileText=mysql_result($renee_result,$renee_s,"SmileText");
$SmileDirectory=mysql_result($renee_result,$renee_s,"Directory");
$ShowSmile=mysql_result($renee_result,$renee_s,"Show");
$ReplaceType=mysql_result($renee_result,$renee_s,"ReplaceCI");
if($SmileRow<5) { ?>
	<img src="<?php echo $SmileDirectory."".$FileName; ?>" style="vertical-align: middle; border: 0px; cursor: pointer;" title="<?php echo $SmileName; ?>" alt="<?php echo $SmileName; ?>" onclick="addsmiley('ReplyPost','&nbsp;<?php echo htmlspecialchars($SmileText); ?>&nbsp;')" />&nbsp;&nbsp;
	<?php } if($SmileRow==5) { ?>
	<img src="<?php echo $SmileDirectory."".$FileName; ?>" style="vertical-align: middle; border: 0px; cursor: pointer;" title="<?php echo $SmileName; ?>" alt="<?php echo $SmileName; ?>" onclick="addsmiley('ReplyPost','&nbsp;<?php echo htmlspecialchars($SmileText); ?>&nbsp;')" /><br />
	<?php $SmileRow=1; }
++$renee_s; ++$SmileRow; }
@mysql_free_result($renee_result);
?></div></td>
<td class="TableRow3" style="width: 85%;">
<form method="post" id="MkReplyForm" action="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=makereply&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ReplyDesc">Insert Reply Description:</label></td>
	<td style="width: 50%;"><input maxlength="45" type="text" name="ReplyDesc" class="TextBox" id="ReplyDesc" size="20" value="<?php echo $QuoteDescription; ?>" /></td>
</tr><?php if($_SESSION['UserGroup']==$Settings['GuestGroup']) { ?><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="GuestName">Insert Guest Name:</label></td>
	<td style="width: 50%;"><input maxlength="25" type="text" name="GuestName" class="TextBox" id="GuestName" size="20" /></td>
</tr><?php } ?>
</table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<label class="TextBoxLabel" for="ReplyPost">Insert Your Reply:</label><br />
<textarea rows="10" name="ReplyPost" id="ReplyPost" cols="40" class="TextBox"><?php echo $QuoteReply; ?></textarea><br />
<input type="hidden" name="act" value="makereplies" style="display: none;" />
<?php if($_SESSION['UserGroup']!=$Settings['GuestGroup']) { ?>
<input type="hidden" name="GuestName" value="null" style="display: none;" />
<?php } ?>
<input type="submit" class="Button" value="Make Reply" name="make_reply" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form></td></tr>
<tr id="MkReplyEnd<?php echo $TopicForumID; ?>" class="TableRow4">
<td class="TableRow4" colspan="5">&nbsp;</td>
</tr>
</table>
<div>&nbsp;</div>
</div>
<?php } } if($_GET['act']=="create") {
if($PermissionInfo['CanMakeReplys'][$TopicForumID]=="no") { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($PermissionInfo['CanMakeReplysClose'][$TopicForumID]=="no"&&$TopicClosed==1) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
$QuoteReply = null; $QuoteDescription = null;
if($_GET['post']!=null) {
$query = query("SELECT * FROM `".$Settings['sqltable']."posts` WHERE `id`=%i", array($_GET['post']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
$QuoteReplyID=mysql_result($result,0,"id");
$QuoteReplyFID=mysql_result($result,0,"ForumID");
$QuoteReplyCID=mysql_result($result,0,"CategoryID");
$QuoteUserID=mysql_result($result,0,"UserID");
$QuoteReply=mysql_result($result,0,"Post");
$QuoteDescription=mysql_result($result,0,"Description");
$QuoteGuestName=mysql_result($result,0,"GuestName");
$requery = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i", array($QuoteUserID));
$reresult=mysql_query($requery);
$renum=mysql_num_rows($reresult);
$QuoteUserName=mysql_result($reresult,0,"Name");
if($QuoteUserName=="Guest") { $QuoteUserName=$QuoteGuestName;
if($QuoteUserName==null) { $QuoteUserName="Guest"; } }
$QuoteUserName = stripcslashes(htmlspecialchars($QuoteUserName, ENT_QUOTES));
//$QuoteUserName = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $QuoteUserName);
$QuoteUserName = @remove_spaces($QuoteUserName);
/*$QuoteReply = stripcslashes(htmlspecialchars($QuoteReply, ENT_QUOTES));
$QuoteReply = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $QuoteReply);
//$QuoteReply = @remove_spaces($QuoteReply);*/
$QuoteReply = remove_bad_entities($QuoteReply);
$QuoteDescription = str_replace("Re: ","",$QuoteDescription);
$QuoteDescription = "Re: ".$QuoteDescription;
$QuoteReply = $QuoteUserName.":\n(&quot;".$QuoteReply."&quot;)"; 
if(!isset($PermissionInfo['CanViewForum'][$QuoteReplyFID])) {
	$PermissionInfo['CanViewForum'][$QuoteReplyFID] = "no"; }
if($PermissionInfo['CanViewForum'][$QuoteReplyFID]=="no") {
	$QuoteReply = null; $QuoteDescription = null; }
if(!isset($CatPermissionInfo['CanViewCategory'][$QuoteReplyCID])) {
	$CatPermissionInfo['CanViewCategory'][$QuoteReplyCID] = "no"; }
if($CatPermissionInfo['CanViewCategory'][$QuoteReplyCID]=="no") {
	$QuoteReply = null; $QuoteDescription = null; } }
if($_GET['post']==null) { $QuoteReply = null; $QuoteDescription = null; }
?>
<div class="Table1Border">
<table class="Table1" id="MakeReply<?php echo $TopicForumID; ?>">
<tr class="TableRow1" id="ReplyStart<?php echo $TopicForumID; ?>">
<td class="TableRow1" colspan="2"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>#<?php echo $TopicID; ?>"><?php echo $TopicName; ?></a></span>
<?php echo "<span style=\"float: right;\">&nbsp;</span>"; ?></td>
</tr>
<tr id="MakeReplyRow<?php echo $TopicForumID; ?>" class="TableRow2">
<td class="TableRow2" colspan="2" style="width: 100%;">Making a Reply in Topic <?php echo $TopicName; ?></td>
</tr>
<tr class="TableRow3" id="MkReply<?php echo $TopicForumID; ?>">
<td class="TableRow3" style="width: 15%; vertical-align: middle; text-align: center;">
<div style="width: 100%; height: 160px; overflow: auto;"><?php
$renee_query=query("SELECT * FROM `".$Settings['sqltable']."smileys`", array(null));
$renee_result=mysql_query($renee_query);
$renee_num=mysql_num_rows($renee_result);
$renee_s=0; $SmileRow=1;
while ($renee_s < $renee_num) {
$FileName=mysql_result($renee_result,$renee_s,"FileName");
$SmileName=mysql_result($renee_result,$renee_s,"SmileName");
$SmileText=mysql_result($renee_result,$renee_s,"SmileText");
$SmileDirectory=mysql_result($renee_result,$renee_s,"Directory");
$ShowSmile=mysql_result($renee_result,$renee_s,"Show");
$ReplaceType=mysql_result($renee_result,$renee_s,"ReplaceCI");
if($SmileRow<5) { ?>
	<img src="<?php echo $SmileDirectory."".$FileName; ?>" style="vertical-align: middle; border: 0px; cursor: pointer;" title="<?php echo $SmileName; ?>" alt="<?php echo $SmileName; ?>" onclick="addsmiley('ReplyPost','&nbsp;<?php echo htmlspecialchars($SmileText); ?>&nbsp;')" />&nbsp;&nbsp;
	<?php } if($SmileRow==5) { ?>
	<img src="<?php echo $SmileDirectory."".$FileName; ?>" style="vertical-align: middle; border: 0px; cursor: pointer;" title="<?php echo $SmileName; ?>" alt="<?php echo $SmileName; ?>" onclick="addsmiley('ReplyPost','&nbsp;<?php echo htmlspecialchars($SmileText); ?>&nbsp;')" /><br />
	<?php $SmileRow=1; }
++$renee_s; ++$SmileRow; }
@mysql_free_result($renee_result);
?></div></td>
<td class="TableRow3" style="width: 85%;">
<form method="post" id="MkReplyForm" action="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=makereply&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ReplyDesc">Insert Reply Description:</label></td>
	<td style="width: 50%;"><input maxlength="45" type="text" name="ReplyDesc" class="TextBox" id="ReplyDesc" size="20" value="<?php echo $QuoteDescription; ?>" /></td>
</tr><?php if($_SESSION['UserGroup']==$Settings['GuestGroup']) { ?><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="GuestName">Insert Guest Name:</label></td>
	<td style="width: 50%;"><input maxlength="25" type="text" name="GuestName" class="TextBox" id="GuestName" size="20" /></td>
</tr><?php } ?>
</table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<label class="TextBoxLabel" for="ReplyPost">Insert Your Reply:</label><br />
<textarea rows="10" name="ReplyPost" id="ReplyPost" cols="40" class="TextBox"><?php echo $QuoteReply; ?></textarea><br />
<input type="hidden" name="act" value="makereplies" style="display: none;" />
<?php if($_SESSION['UserGroup']!=$Settings['GuestGroup']) { ?>
<input type="hidden" name="GuestName" value="null" style="display: none;" />
<?php } ?>
<input type="submit" class="Button" value="Make Reply" name="make_reply" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form></td></tr>
<tr id="MkReplyEnd<?php echo $TopicForumID; ?>" class="TableRow4">
<td class="TableRow4" colspan="5">&nbsp;</td>
</tr>
</table></div>
<div>&nbsp;</div>
<?php } if($_GET['act']=="makereply"&&$_POST['act']=="makereplies") {
if($PermissionInfo['CanMakeReplys'][$TopicForumID]=="no") { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($PermissionInfo['CanMakeReplysClose'][$TopicForumID]=="no"&&$TopicClosed==1) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
$MyUsersID = $_SESSION['UserID']; if($MyUsersID=="0"||$MyUsersID==null) { $MyUsersID = -1; }
$REFERERurl = parse_url($_SERVER['HTTP_REFERER']);
$URL['REFERER'] = $REFERERurl['host'];
$URL['HOST'] = $_SERVER["SERVER_NAME"];
$REFERERurl = null; unset($REFERERurl);
if(!isset($_POST['ReplyDesc'])) { $_POST['ReplyDesc'] = null; }
if(!isset($_POST['ReplyPost'])) { $_POST['ReplyPost'] = null; }
if(!isset($_POST['GuestName'])) { $_POST['GuestName'] = null; }
?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>#<?php echo $TopicID; ?>"><?php echo $TopicName; ?></a></span>
<?php echo "<span style=\"float: right;\">&nbsp;</span>"; ?></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">&nbsp;Make Reply Message: </th>
</tr>
<?php if (strlen($_POST['ReplyDesc'])>="45") { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Your Reply Description is too big.<br />
	</span></td>
</tr>
<?php } if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	strlen($_POST['GuestName'])>="25") { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You Guest Name is too big.<br />
	</span></td>
</tr>
<?php } if ($Settings['TestReferer']==true) {
	if ($URL['HOST']!=$URL['REFERER']) { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Sorry the referering url dose not match our host name.<br />
	</span></td>
</tr>
<?php } }
$_POST['ReplyDesc'] = stripcslashes(htmlspecialchars($_POST['ReplyDesc'], ENT_QUOTES));
$_POST['ReplyDesc'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['ReplyDesc']);
$_POST['ReplyDesc'] = @remove_spaces($_POST['ReplyDesc']);
$_POST['GuestName'] = stripcslashes(htmlspecialchars($_POST['GuestName'], ENT_QUOTES));
//$_POST['GuestName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['GuestName']);
$_POST['GuestName'] = @remove_spaces($_POST['GuestName']);
$_POST['ReplyPost'] = stripcslashes(htmlspecialchars($_POST['ReplyPost'], ENT_QUOTES));
$_POST['ReplyPost'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['ReplyPost']);
//$_POST['ReplyPost'] = @remove_spaces($_POST['ReplyPost']);
$_POST['ReplyPost'] = remove_bad_entities($_POST['ReplyPost']);
if ($_POST['ReplyDesc']==null) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter a Reply Description.<br />
	</span></td>
</tr>
<?php } if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	$_POST['GuestName']==null) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter a Guest Name.<br />
	</span></td>
</tr>
<?php } if($PermissionInfo['CanMakeReplys'][$TopicForumID]=="no") { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You do not have permission to make a reply here.<br />
	</span></td>
</tr>
<?php } if($PermissionInfo['CanMakeReplysClose'][$TopicForumID]=="no"&&
	$TopicClosed==1) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You do not have permission to make a reply here.<br />
	</span></td>
</tr>
<?php } if ($_POST['ReplyPost']==null) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter a Reply.<br />
	</span></td>
</tr>
<?php } if ($Error=="Yes") {
@redirect("refresh",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false),"4"); }
if ($Error!="Yes") { $LastActive = GMTimeStamp();
$gnrquery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `id`=%i", array($TopicForumID));
$gnrresult=mysql_query($gnrquery); $gnrnum=mysql_num_rows($gnrresult);
$NumberPosts=mysql_result($gnrresult,0,"NumPosts"); 
$PostCountAdd=mysql_result($gnrresult,0,"PostCountAdd"); 
@mysql_free_result($gnrresult);
$postid = getnextid($Settings['sqltable'],"posts");
$requery = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i", array($MyUsersID));
$reresult=mysql_query($requery);
$renum=mysql_num_rows($reresult);
$rei=0;
while ($rei < $renum) {
$User1ID=$MyUsersID;
$User1Name=mysql_result($reresult,$rei,"Name");
if($_SESSION['UserGroup']==$Settings['GuestGroup']) { $User1Name = $_POST['GuestName']; }
$User1Email=mysql_result($reresult,$rei,"Email");
$User1Title=mysql_result($reresult,$rei,"Title");
$User1GroupID=mysql_result($reresult,$rei,"GroupID");
$PostCount=mysql_result($reresult,$rei,"PostCount");
$NewPostCount = null;
if($PostCountAdd=="on") { $NewPostCount = $PostCount + 1; }
if(!isset($NewPostCount)) { $NewPostCount = $PostCount; }
$gquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i", array($User1GroupID));
$gresult=mysql_query($gquery);
$User1Group=mysql_result($gresult,0,"Name");
@mysql_free_result($gresult);
$User1IP=$_SERVER['REMOTE_ADDR'];
++$rei; } @mysql_free_result($reresult);
$query = query("INSERT INTO `".$Settings['sqltable']."posts` VALUES (".$postid.",%i,%i,%i,%i,'%s',%i,%i,0,'%s','%s','%s','0')", array($TopicID,$TopicForumID,$TopicCatID,$User1ID,$User1Name,$LastActive,$LastActive,$_POST['ReplyPost'],$_POST['ReplyDesc'],$User1IP));
mysql_query($query);
if($User1ID!=0&&$User1ID!=-1) {
$queryupd = query("UPDATE `".$Settings['sqltable']."members` SET `LastActive`=%i,`IP`='%s',`PostCount`=%i WHERE `id`=%i", array($LastActive,$User1IP,$NewPostCount,$User1ID));
mysql_query($queryupd); }
$NewNumPosts = $NumberPosts + 1; $NewNumReplies = $NumberReplies + 1;
$queryupd = query("UPDATE `".$Settings['sqltable']."forums` SET `NumPosts`=%i WHERE `id`=%i", array($NewNumPosts,$TopicForumID));
mysql_query($queryupd);
$queryupd = query("UPDATE `".$Settings['sqltable']."topics` SET `NumReply`=%i,LastUpdate=%i WHERE `id`=%i", array($NewNumReplies,$LastActive,$TopicID));
mysql_query($queryupd);
$MyPostNum = $NewNumReplies + 1;
@redirect("refresh",$basedir.url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'],FALSE)."#reply".$MyPostNum,"3");
?><tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage"><br />
	Reply to Topic <?php echo $TopicName; ?> was posted.<br />
	Click <a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>#reply<?php echo $MyPostNum; ?>">here</a> to view your reply.<br />&nbsp;
	</span><br /></td>
</tr>
<?php } ?>
<tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table></div>
<div>&nbsp;</div>
<?php } if($_GET['act']=="delete") {
$predquery = query("SELECT * FROM `".$Settings['sqltable']."posts` WHERE `id`=%i", array($_GET['post']));
$predresult=mysql_query($predquery);
$prednum=mysql_num_rows($predresult);
$ReplyID=mysql_result($predresult,0,"id");
$ReplyTopicID=mysql_result($predresult,0,"TopicID");
$ReplyForumID=mysql_result($predresult,0,"ForumID");
$ReplyUserID=mysql_result($predresult,0,"UserID");
@mysql_free_result($predresult);
$CanDeleteReply = false;
if($_SESSION['UserID']!=0) {
if($_SESSION['UserGroup']!=$Settings['GuestGroup']) {
if($PermissionInfo['CanDeleteReplys'][$ReplyForumID]=="yes"&&
	$_SESSION['UserID']==$ReplyUserID) { $CanDeleteReply = true; } 
if($PermissionInfo['CanDeleteReplys'][$ReplyForumID]=="yes"&&
	$PermissionInfo['CanModForum'][$ReplyForumID]=="yes") { 
	$CanDeleteReply = true; } } 
	if($PermissionInfo['CanDeleteReplysClose'][$TopicForumID]=="no"&&
		$TopicClosed==1) { $CanDeleteReply = false; } }
if($_SESSION['UserID']==0) { $CanDeleteReply = false; }
if($CanDeleteReply==false) {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
$delquery = query("SELECT * FROM `".$Settings['sqltable']."posts` WHERE `TopicID`=%i ORDER BY `TimeStamp` ASC", array($_GET['id']));
$delresult=mysql_query($delquery);
$delnum=mysql_num_rows($delresult);
$DelTopic = false;
$gnrquery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `id`=%i", array($ReplyForumID));
$gnrresult=mysql_query($gnrquery); $gnrnum=mysql_num_rows($gnrresult);
$NumberPosts=mysql_result($gnrresult,0,"NumPosts"); $NumberTopics=mysql_result($gnrresult,0,"NumTopics"); 
@mysql_free_result($gnrresult);
$FReplyID=mysql_result($delresult,0,"id");
if($ReplyID==$FReplyID) { $DelTopic = true;
$gtsquery = query("SELECT * FROM `".$Settings['sqltable']."topics` WHERE `id`=%i", array($ReplyTopicID));
$gtsresult=mysql_query($gtsquery);
$gtsnum=mysql_num_rows($gtsresult);
$TUsersID=mysql_result($gtsresult,0,"UserID");
$CanDeleteTopics = false;
if($_SESSION['UserGroup']!=$Settings['GuestGroup']) {
if($PermissionInfo['CanDeleteTopics'][$ReplyForumID]=="yes"&&
	$_SESSION['UserID']==$TUsersID) { $CanDeleteTopics = true; }
if($PermissionInfo['CanDeleteTopics'][$ReplyForumID]=="yes"&&
	$PermissionInfo['CanModForum'][$ReplyForumID]=="yes") { 
	$CanDeleteTopics = true; }
	if($PermissionInfo['CanDeleteTopicsClose'][$TopicForumID]=="no"&&
		$TopicClosed==1) { $CanDeleteTopics = false; } }
if($_SESSION['UserID']==0) { $CanDeleteTopics = false; }
if($CanDeleteTopics==false) {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); @mysql_free_result($delresult);
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($CanDeleteTopics==true) { $NewNumTopics = $NumberTopics - 1; $NewNumPosts = $NumberPosts - $delnum;
$drquery = query("DELETE FROM `".$Settings['sqltable']."posts` WHERE `TopicID`=%i", array($ReplyTopicID));
mysql_query($drquery); 
$dtquery = query("DELETE FROM `".$Settings['sqltable']."topics` WHERE `id`=%i", array($ReplyTopicID));
mysql_query($dtquery);
$queryupd = query("UPDATE `".$Settings['sqltable']."forums` SET `NumPosts`=%i,`NumTopics`=%i WHERE `id`=%i", array($NewNumPosts,$NewNumTopics,$ReplyForumID));
mysql_query($queryupd); } }
if($ReplyID!=$FReplyID) {
$LReplyID=mysql_result($delresult,$delnum-1,"id");
$SLReplyID=mysql_result($delresult,$delnum-2,"id");
$NewLastUpdate=mysql_result($delresult,$delnum-2,"TimeStamp");
if($ReplyID==$LReplyID) { $NewNumReplies = $NumberReplies - 1; $NewNumPosts = $NumberPosts - 1;
$drquery = query("DELETE FROM `".$Settings['sqltable']."posts` WHERE `id`=%i", array($ReplyID));
mysql_query($drquery); 
$queryupd = query("UPDATE `".$Settings['sqltable']."forums` SET `NumPosts`=%i WHERE `id`=%i", array($NewNumPosts,$ReplyForumID));
mysql_query($queryupd);
$queryupd = query("UPDATE `".$Settings['sqltable']."topics` SET `LastUpdate`=%i,`NumReply`=%i WHERE `id`=%i", array($NewLastUpdate,$NewNumReplies,$ReplyTopicID));
mysql_query($queryupd); } }
if($ReplyID!=$FReplyID&&$ReplyID!=$LReplyID) { $NewNumReplies = $NumberReplies - 1; $NewNumPosts = $NumberPosts - 1;
$drquery = query("DELETE FROM `".$Settings['sqltable']."posts` WHERE `id`=%i", array($ReplyID));
mysql_query($drquery);
$queryupd = query("UPDATE `".$Settings['sqltable']."forums` SET `NumPosts`=%i WHERE `id`=%i", array($NewNumPosts,$ReplyForumID));
mysql_query($queryupd);
$queryupd = query("UPDATE `".$Settings['sqltable']."topics` SET `NumReply`=%i WHERE `id`=%i", array($NewNumReplies,$ReplyTopicID));
mysql_query($queryupd); }
@redirect("refresh",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],FALSE),"3");
@mysql_free_result($delresult);
?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>"><?php echo $TopicName; ?></a></span>
<?php echo "<span style=\"float: right;\">&nbsp;</span>"; ?></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">&nbsp;Delete Reply Message: </th>
</tr>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage"><br />
	Reply was deleted successfully.<br />
	Click <a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">here</a> to go back to index.<br />&nbsp;
	</span><br /></td>
</tr>
<tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table></div>
<?php } if($_GET['act']=="edit") {
if($PermissionInfo['CanEditReplys'][$TopicForumID]=="no"||$_SESSION['UserID']==0) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($PermissionInfo['CanEditReplysClose'][$TopicForumID]=="no"&&$TopicClosed==1) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
$ShowEditTopic = null;
if($PermissionInfo['CanEditTopics'][$TopicForumID]=="yes") {
$editquery = query("SELECT * FROM `".$Settings['sqltable']."posts` WHERE `TopicID`=%i ORDER BY `TimeStamp` ASC", array($TopicID));
$editresult=mysql_query($editquery);
$editnum=mysql_num_rows($editresult);
$FReplyID=mysql_result($editresult,0,"id");
@mysql_free_result($editresult);
if($_GET['post']==$FReplyID) { $ShowEditTopic = true; } }
if($PermissionInfo['CanEditTopics'][$TopicForumID]=="no") { $ShowEditTopic = null; }
$ersquery = query("SELECT * FROM `".$Settings['sqltable']."posts` WHERE `id`=%i", array($_GET['post']));
$ersresult=mysql_query($ersquery);
$ersnum=mysql_num_rows($ersresult);
if($ersnum==0) { @mysql_free_result($ersresult);
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
$ReplyPost=mysql_result($ersresult,0,"Post");
/*$ReplyPost = stripcslashes(htmlspecialchars($ReplyPost, ENT_QUOTES));
$ReplyPost = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $ReplyPost);
//$ReplyPost = @remove_spaces($ReplyPost);*/
$ReplyPost = remove_bad_entities($ReplyPost);
$ReplyDescription=mysql_result($ersresult,0,"Description");
$ReplyDescription = stripcslashes(htmlspecialchars($ReplyDescription, ENT_QUOTES));
$ReplyDescription = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $ReplyDescription);
$ReplyDescription = @remove_spaces($ReplyDescription);
$ReplyGuestName=mysql_result($ersresult,0,"GuestName");
$ReplyGuestName = stripcslashes(htmlspecialchars($ReplyGuestName, ENT_QUOTES));
//$ReplyGuestName = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $ReplyGuestName);
$ReplyGuestName = @remove_spaces($ReplyGuestName);
$ReplyUser=mysql_result($ersresult,0,"UserID");
if($_SESSION['UserID']!=$ReplyUser&&$PermissionInfo['CanModForum'][$TopicForumID]=="no") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
@mysql_free_result($ersresult);
if($ShowEditTopic==true) {
$gtsquery = query("SELECT * FROM `".$Settings['sqltable']."topics` WHERE `id`=%i", array($TopicID));
$gtsresult=mysql_query($gtsquery);
$gtsnum=mysql_num_rows($gtsresult);
$TUsersID=mysql_result($gtsresult,0,"UserID");
if($_SESSION['UserID']!=$TUsersID) { $ShowEditTopic = null; }
if($PermissionInfo['CanModForum'][$TopicForumID]=="yes"&&
	$PermissionInfo['CanEditTopics'][$TopicForumID]=="yes") { 
	$ShowEditTopic = true; } 
if($PermissionInfo['CanEditTopicsClose'][$TopicForumID]=="no"&&$TopicClosed==1) {
	$ShowEditTopic = null; } }
$TopicName = stripcslashes(htmlspecialchars($TopicName, ENT_QUOTES));
$TopicName = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $TopicName);
$TopicName = @remove_spaces($TopicName);
@mysql_free_result($gtsresult);
?>
<div class="Table1Border">
<table class="Table1" id="EditReply<?php echo $_GET['post']; ?>">
<tr class="TableRow1" id="ReplyEdit<?php echo $_GET['post']; ?>">
<td class="TableRow1" colspan="2"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>"><?php echo $TopicName; ?></a></span>
<?php echo "<span style=\"float: right;\">&nbsp;</span>"; ?></td>
</tr>
<tr id="EditReplyRow<?php echo $_GET['post']; ?>" class="TableRow2">
<td class="TableRow2" colspan="2" style="width: 100%;">Editing a Reply in Topic <?php echo $TopicName; ?></td>
</tr>
<tr class="TableRow3" id="EditReplies<?php echo $_GET['post']; ?>">
<td class="TableRow3" style="width: 15%; vertical-align: middle; text-align: center;">
<div style="width: 100%; height: 160px; overflow: auto;"><?php
$renee_query=query("SELECT * FROM `".$Settings['sqltable']."smileys`", array(null));
$renee_result=mysql_query($renee_query);
$renee_num=mysql_num_rows($renee_result);
$renee_s=0; $SmileRow=1;
while ($renee_s < $renee_num) {
$FileName=mysql_result($renee_result,$renee_s,"FileName");
$SmileName=mysql_result($renee_result,$renee_s,"SmileName");
$SmileText=mysql_result($renee_result,$renee_s,"SmileText");
$SmileDirectory=mysql_result($renee_result,$renee_s,"Directory");
$ShowSmile=mysql_result($renee_result,$renee_s,"Show");
$ReplaceType=mysql_result($renee_result,$renee_s,"ReplaceCI");
if($SmileRow<5) { ?>
	<img src="<?php echo $SmileDirectory."".$FileName; ?>" style="vertical-align: middle; border: 0px; cursor: pointer;" title="<?php echo $SmileName; ?>" alt="<?php echo $SmileName; ?>" onclick="addsmiley('ReplyPost','&nbsp;<?php echo htmlspecialchars($SmileText); ?>&nbsp;')" />&nbsp;&nbsp;
	<?php } if($SmileRow==5) { ?>
	<img src="<?php echo $SmileDirectory."".$FileName; ?>" style="vertical-align: middle; border: 0px; cursor: pointer;" title="<?php echo $SmileName; ?>" alt="<?php echo $SmileName; ?>" onclick="addsmiley('ReplyPost','&nbsp;<?php echo htmlspecialchars($SmileText); ?>&nbsp;')" /><br />
	<?php $SmileRow=1; }
++$renee_s; ++$SmileRow; }
@mysql_free_result($renee_result);
?></div></td>
<td class="TableRow3" style="width: 85%;">
<form method="post" id="EditReplyForm" action="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=editreply&id=".$TopicID."&post=".$_GET['post'],$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
<?php if($ShowEditTopic==true) { ?>
	<td style="width: 50%;"><label class="TextBoxLabel" for="TopicName">Insert Topic Name:</label></td>
	<td style="width: 50%;"><input maxlength="30" type="text" name="TopicName" class="TextBox" id="TopicName" size="20" value="<?php echo $TopicName; ?>" /></td>
</tr><tr><?php } ?>
	<td style="width: 50%;"><label class="TextBoxLabel" for="ReplyDesc">Insert Reply Description:</label></td>
	<td style="width: 50%;"><input maxlength="45" type="text" name="ReplyDesc" class="TextBox" id="ReplyDesc" size="20" value="<?php echo $ReplyDescription; ?>" /></td>
</tr><?php if($_SESSION['UserGroup']==$Settings['GuestGroup']) { ?><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="GuestName">Insert Guest Name:</label></td>
	<td style="width: 50%;"><input maxlength="25" type="text" name="GuestName" class="TextBox" id="GuestName" size="20" value="<?php echo $ReplyGuestName; ?>" /></td>
</tr><?php } ?>
</table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<label class="TextBoxLabel" for="ReplyPost">Insert Your Reply:</label><br />
<textarea rows="10" name="ReplyPost" id="ReplyPost" cols="40" class="TextBox"><?php echo $ReplyPost; ?></textarea><br />
<input type="hidden" name="act" value="editreplies" style="display: none;" />
<?php if($_SESSION['UserGroup']!=$Settings['GuestGroup']) { ?>
<input type="hidden" name="GuestName" value="null" style="display: none;" />
<?php } ?>
<input type="submit" class="Button" value="Edit Reply" name="edit_reply" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form></td></tr>
<tr id="EditReplyEnd<?php echo $_GET['post']; ?>" class="TableRow4">
<td class="TableRow4" colspan="5">&nbsp;</td>
</tr>
</table></div>
<div>&nbsp;</div>
<?php } if($_GET['act']=="editreply"&&$_POST['act']=="editreplies") {
if($PermissionInfo['CanEditReplys'][$TopicForumID]=="no"||$_SESSION['UserID']==0) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($PermissionInfo['CanEditReplysClose'][$TopicForumID]=="no"&&$TopicClosed==1) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
$REFERERurl = parse_url($_SERVER['HTTP_REFERER']);
$URL['REFERER'] = $REFERERurl['host'];
$URL['HOST'] = $_SERVER["SERVER_NAME"];
$REFERERurl = null; unset($REFERERurl);
if(!isset($_POST['ReplyDesc'])) { $_POST['ReplyDesc'] = null; }
if(!isset($_POST['ReplyPost'])) { $_POST['ReplyPost'] = null; }
if(!isset($_POST['GuestName'])) { $_POST['GuestName'] = null; }
if(!isset($_POST['TopicName'])) { $_POST['TopicName'] = null; }
$ShowEditTopic = null;
if($PermissionInfo['CanEditTopics'][$TopicForumID]=="yes") {
$editquery = query("SELECT * FROM `".$Settings['sqltable']."posts` WHERE `TopicID`=%i ORDER BY `TimeStamp` ASC", array($TopicID));
$editresult=mysql_query($editquery);
$editnum=mysql_num_rows($editresult);
$FReplyID=mysql_result($editresult,0,"id");
@mysql_free_result($editresult);
if($_GET['post']==$FReplyID) { $ShowEditTopic = true; } }
if($PermissionInfo['CanEditTopics'][$TopicForumID]=="no") { $ShowEditTopic = null; }
$ersquery = query("SELECT * FROM `".$Settings['sqltable']."posts` WHERE `id`=%i", array($_GET['post']));
$ersresult=mysql_query($ersquery);
$ersnum=mysql_num_rows($ersresult);
if($ersnum==0) { @mysql_free_result($ersresult);
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
$ReplyUser=mysql_result($ersresult,0,"UserID");
if($_SESSION['UserID']!=$ReplyUser&&$PermissionInfo['CanModForum'][$TopicForumID]=="no") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
@mysql_free_result($ersresult); 
if($ShowEditTopic==true) {
$gtsquery = query("SELECT * FROM `".$Settings['sqltable']."topics` WHERE `id`=%i", array($TopicID));
$gtsresult=mysql_query($gtsquery);
$gtsnum=mysql_num_rows($gtsresult);
$TUsersID=mysql_result($gtsresult,0,"UserID");
if($_SESSION['UserID']!=$TUsersID) { $ShowEditTopic = null; }
if($PermissionInfo['CanModForum'][$TopicForumID]=="yes"&&
	$PermissionInfo['CanEditTopics'][$TopicForumID]=="yes") { 
	$ShowEditTopic = true; } 
if($PermissionInfo['CanEditTopicsClose'][$TopicForumID]=="no"&&$TopicClosed==1) {
	$ShowEditTopic = null; } }
?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>"><?php echo $TopicName; ?></a></span>
<?php echo "<span style=\"float: right;\">&nbsp;</span>"; ?></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">&nbsp;Edit Reply Message: </th>
</tr>
<?php if (strlen($_POST['ReplyDesc'])>="45") { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Your Reply Description is too big.<br />
	</span></td>
</tr>
<?php } if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	strlen($_POST['GuestName'])>="25") { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You Guest Name is too big.<br />
	</span></td>
</tr>
<?php } if($ShowEditTopic==true&&
	strlen($_POST['TopicName'])>="30") { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You Topic Name is too big.<br />
	</span></td>
</tr>
<?php } if ($Settings['TestReferer']==true) {
	if ($URL['HOST']!=$URL['REFERER']) { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Sorry the referering url dose not match our host name.<br />
	</span></td>
</tr>
<?php } }
$_POST['ReplyDesc'] = stripcslashes(htmlspecialchars($_POST['ReplyDesc'], ENT_QUOTES));
$_POST['ReplyDesc'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['ReplyDesc']);
$_POST['ReplyDesc'] = @remove_spaces($_POST['ReplyDesc']);
$_POST['GuestName'] = stripcslashes(htmlspecialchars($_POST['GuestName'], ENT_QUOTES));
//$_POST['GuestName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['GuestName']);
$_POST['GuestName'] = @remove_spaces($_POST['GuestName']);
$_POST['ReplyPost'] = stripcslashes(htmlspecialchars($_POST['ReplyPost'], ENT_QUOTES));
$_POST['ReplyPost'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['ReplyPost']);
$_POST['ReplyPost'] = remove_bad_entities($_POST['ReplyPost']);
if($ShowEditTopic==true) {
$_POST['TopicName'] = stripcslashes(htmlspecialchars($_POST['TopicName'], ENT_QUOTES));
$_POST['TopicName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['TopicName']);
$_POST['TopicName'] = @remove_spaces($_POST['TopicName']); }
if ($_POST['ReplyDesc']==null) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter a Reply Description.<br />
	</span></td>
</tr>
<?php } if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	$_POST['GuestName']==null) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter a Guest Name.<br />
	</span></td>
</tr>
<?php } if($PermissionInfo['CanEditReplys'][$TopicForumID]=="no") { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You do not have permission to edit a reply here.<br />
	</span></td>
</tr>
<?php } if($PermissionInfo['CanEditReplysClose'][$TopicForumID]=="no"&&$TopicClosed==1) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You do not have permission to edit a reply here.<br />
	</span></td>
</tr>
<?php } if($ShowEditTopic==true&&$_POST['TopicName']==null) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter a Topic Name.<br />
	</span></td>
</tr>
<?php } if ($_POST['ReplyPost']==null) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter a Reply.<br />
	</span></td>
</tr>
<?php } if ($Error=="Yes") {
@redirect("refresh",$basedir.url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'],false)."#post".$_GET['post'],"4"); }
if ($Error!="Yes") { $LastActive = GMTimeStamp();
$requery = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i", array($_SESSION['UserID']));
$reresult=mysql_query($requery);
$renum=mysql_num_rows($reresult);
$rei=0;
while ($rei < $renum) {
$User1ID=$_SESSION['UserID'];
$User1Name=mysql_result($reresult,$rei,"Name");
if($_SESSION['UserGroup']==$Settings['GuestGroup']) { $User1Name = $_POST['GuestName']; }
++$rei; }
@mysql_free_result($reresult);
$EditUserIP=$_SERVER['REMOTE_ADDR'];
$queryupd = query("UPDATE `".$Settings['sqltable']."posts` SET `LastUpdate`=%i,`EditUser`=%i,`Post`='%s',`Description`='%s',`EditIP`='%s' WHERE `id`=%i", array($LastActive,$_SESSION['UserID'],$_POST['ReplyPost'],$_POST['ReplyDesc'],$EditUserIP,$_GET['post']));
mysql_query($queryupd);
if($ShowEditTopic==true) {
$queryupd = query("UPDATE `".$Settings['sqltable']."topics` SET `TopicName`='%s',`Description`='%s' WHERE `id`=%i", array($_POST['TopicName'],$_POST['ReplyDesc'],$TopicID));
mysql_query($queryupd); } } 
@redirect(url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."#post".$_GET['post'],$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'],FALSE)."#post".$_GET['post'],"3");
?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage"><br />
	Reply to Topic <?php echo $TopicName; ?> was edited.<br />
	Click <a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."#post".$_GET['post']; ?>">here</a> to view topic.<br />&nbsp;
	</span><br /></td>
</tr>
<tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table></div>
<?php }
if($PermissionInfo['CanMakeReplys'][$TopicForumID]=="yes"||$PermissionInfo['CanMakeTopics'][$TopicForumID]=="yes") { ?>
<table class="Table2" style="width: 100%;">
<tr>
 <td style="width: 0%; text-align: left;">&nbsp;</td>
 <td style="width: 100%; text-align: right;">
 <?php if($CanMakeReply=="yes") { ?>
 <a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=create&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>"><?php echo $ThemeSet['AddReply']; ?></a>
 <?php echo $ThemeSet['ButtonDivider']; ?>
 <a href="javascript:%20<?php echo urlencode("toggletag('FastReply');"); ?>"><?php echo $ThemeSet['FastReply']; ?></a>
 <?php } if($PermissionInfo['CanMakeTopics'][$TopicForumID]=="yes") {
	if($CanMakeReply=="yes") { ?>
 <?php echo $ThemeSet['ButtonDivider']; } ?>
 <a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=create&id=".$TopicForumID,$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ThemeSet['NewTopic']; ?></a>
 <?php } ?></td>
</tr>
</table>
<div>&nbsp;</div>
<?php } } } ?>