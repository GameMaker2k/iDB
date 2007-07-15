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

    $FileInfo: replys.php - Last Update: 07/15/2007 SVN 44 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="replys.php"||$File3Name=="/replys.php") {
	require('index.php');
	exit(); }
$prequery = query("select * from `".$Settings['sqltable']."topics` where `id`=%i", array($_GET['id']));
$preresult=mysql_query($prequery);
$prenum=mysql_num_rows($preresult);
$prei=0;
while ($prei < $prenum) {
$TopicName=mysql_result($preresult,$prei,"TopicName");
$TopicForumID=mysql_result($preresult,$prei,"ForumID");
$TopicCatID=mysql_result($preresult,$prei,"CategoryID");
$ViewTimes=mysql_result($preresult,$prei,"NumViews");
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
if($CatPermissionInfo['CanViewCategory'][$TopicCatID]=="yes"&&
	$PermissionInfo['CanViewForum'][$TopicForumID]=="yes") {
?>
<table style="width: 100%;" class="Table2">
<tr>
 <td style="width: 0%; text-align: left;">&nbsp;</td>
 <td style="width: 100%; text-align: right;"><a href="#Act/Reply"><?php echo $ThemeSet['AddReply']; ?></a><?php echo $ThemeSet['ButtonDivider']; ?><a href="#Act/Topic"><?php echo $ThemeSet['NewTopic']; ?></a></td>
</tr>
</table>
<div>&nbsp;</div>
<?php
$query = query("select * from `".$Settings['sqltable']."posts` where `TopicID`=%i ORDER BY `TimeStamp` ASC", array($_GET['id']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
//Start Reply Page Code (Will be used at later time)
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
//End Reply Page Code (Its not used yet but its still good to have :P )
$i=0;
if($num==0) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($num!=0) { 
if($ViewTimes==0||$ViewTimes==null) { $NewViewTimes = 1; }
if($ViewTimes!=0&&$ViewTimes!=null) { $NewViewTimes = $ViewTimes + 1; }
$viewsup = query("update `".$Settings['sqltable']."topics` set `NumViews`='%s' WHERE `id`=%i", array($NewViewTimes,$_GET['id']));
mysql_query($viewsup); }
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
$MyDescription=mysql_result($result,$i,"Description");
$requery = query("select * from `".$Settings['sqltable']."members` where `id`=%i", array($MyUserID));
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
$gquery = query("select * from `".$Settings['sqltable']."groups` where `id`=%i", array($User1GroupID));
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
$euquery = query("select * from `".$Settings['sqltable']."members` where `id`=%i", array($MyEditUserID));
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
?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1" colspan="2"><span style="font-weight: bold; float: left;"><?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>"><?php echo $TopicName; ?></a> ( <?php echo $MyDescription; ?> )</span>
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
<div style="text-align: left; float: left;">
<a style="vertical-align: middle;" id="post<?php echo $i+1; ?>">
<span style="font-weight: bold;">Time Posted: </span><?php echo $MyTimeStamp; ?></a>
</div>
<div style="text-align: right;"><a href="#Act/Report"><?php echo $ThemeSet['Report']; ?></a><?php echo $ThemeSet['LineDividerTopic']; ?><a href="#Act/Quote"><?php echo $ThemeSet['QuoteReply']; ?></a>&nbsp;</div>
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
?>"><?php echo $ThemeSet['Profile']; ?></a><?php echo $ThemeSet['LineDividerTopic']; ?><a href="<?php echo $User1Website; ?>" onclick="window.open(this.href);return false;"><?php echo $ThemeSet['WWW']; ?></a><?php echo $ThemeSet['LineDividerTopic']; ?><a href="#Act/PM"><?php echo $ThemeSet['PM']; ?></a></span>
<span style="float: right;">&nbsp;</span></td>
</tr>
</table></div>
<div>&nbsp;</div>
<?php ++$i; } @mysql_free_result($result); ?>
<table class="Table2" style="width: 100%;">
<tr>
 <td style="width: 0%; text-align: left;">&nbsp;</td>
 <td style="width: 100%; text-align: right;"><a href="#Act/Reply"><?php echo $ThemeSet['AddReply']; ?></a><?php echo $ThemeSet['ButtonDivider']; ?><a href="#Act/Reply"><?php echo $ThemeSet['FastReply']; ?></a><?php echo $ThemeSet['ButtonDivider']; ?><a href="#Act/Topic"><?php echo $ThemeSet['NewTopic']; ?></a></td>
</tr>
</table>
<div>&nbsp;</div>
<?php } ++$prei; } 
@mysql_free_result($preresult);
?>