<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2009 Cool Dude 2k - http://idb.berlios.de/
    Copyright 2004-2009 Game Maker 2k - http://intdb.sourceforge.net/

    $FileInfo: replies.php - Last Update: 5/04/2009 SVN 249 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="replies.php"||$File3Name=="/replies.php") {
	require('index.php');
	exit(); }
$pstring = null; $pagenum = null;
if(!is_numeric($_GET['id'])) { $_GET['id'] = null; }
if(!is_numeric($_GET['post'])) { $_GET['post'] = null; }
if(!is_numeric($_GET['page'])) { $_GET['page'] = null; }
if(!isset($_GET['modact'])) { $_GET['modact'] = null; }
if($_GET['modact']=="pin"||$_GET['modact']=="unpin"||$_GET['modact']=="open"||
	$_GET['modact']=="close"||$_GET['modact']=="edit"||$_GET['modact']=="delete")
		{ $_GET['act'] = $_GET['modact']; }
if(!isset($ForumCheck)) { $ForumCheck = null; }
$prequery = query("SELECT * FROM `".$Settings['sqltable']."topics` WHERE `id`=%i LIMIT 1", array($_GET['id']));
$preresult=mysql_query($prequery);
$prenum=mysql_num_rows($preresult);
if($prenum==0) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); @mysql_free_result($preresult);
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
$forumcheckx = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `id`=%i  LIMIT 1", array($TopicForumID));
$fmckresult=mysql_query($forumcheckx);
$ForumName=mysql_result($fmckresult,0,"Name");
$ForumType=mysql_result($fmckresult,0,"ForumType");
$CanHaveTopics=mysql_result($fmckresult,0,"CanHaveTopics");
$ForumPostCountView=mysql_result($fmckresult,0,"PostCountView");
$ForumKarmaCountView=mysql_result($fmckresult,0,"KarmaCountView");
@mysql_free_result($fmckresult);
$catcheck = query("SELECT * FROM `".$Settings['sqltable']."categories` WHERE `id`=%i  LIMIT 1", array($TopicCatID));
$catresult=mysql_query($catcheck);
$CategoryName=mysql_result($catresult,0,"Name");
$CategoryType=mysql_result($catresult,0,"CategoryType");
$CategoryPostCountView=mysql_result($catresult,0,"PostCountView");
$CategoryKarmaCountView=mysql_result($catresult,0,"KarmaCountView");
@mysql_free_result($catresult);
if($GroupInfo['HasAdminCP']!="yes"||$GroupInfo['HasModCP']!="yes") {
if($MyPostCountChk==null) { $MyPostCountChk = 0; }
if($MyKarmaCount==null) { $MyKarmaCount = 0; }
if($ForumPostCountView!=0&&$MyPostCountChk<$ForumPostCountView) {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($CategoryPostCountView!=0&&$MyPostCountChk<$CategoryPostCountView) {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($ForumKarmaCountView!=0&&$MyKarmaCount<$ForumKarmaCountView) {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($CategoryKarmaCountView!=0&&$MyKarmaCount<$CategoryKarmaCountView) {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); } }
if($ForumCheck!="skip") {
?>
<div style="font-size: 1.0em; font-weight: bold; margin-bottom: 10px; padding-top: 3px; width: auto;">Full Version: <a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>"><?php echo $TopicName; ?></a></div>
<div style="padding: 10px; border: 1px solid gray;"><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">Board index</a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$CategoryType],$Settings['file_ext'],"act=lowview&id=".$TopicCatID,$Settings['qstr'],$Settings['qsep'],$prexqstr[$CategoryType],$exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=lowview&id=".$TopicForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"><?php echo $ForumName; ?></a></div>
<div>&nbsp;</div>
<?php }
if(!isset($CatPermissionInfo['CanViewCategory'][$TopicCatID])) {
	$CatPermissionInfo['CanViewCategory'][$TopicCatID] = "no"; }
if($CatPermissionInfo['CanViewCategory'][$TopicCatID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$TopicCatID]!="yes") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if(!isset($PermissionInfo['CanViewForum'][$TopicForumID])) {
	$PermissionInfo['CanViewForum'][$TopicForumID] = "no"; }
if($PermissionInfo['CanViewForum'][$TopicForumID]=="no"||
	$PermissionInfo['CanViewForum'][$TopicForumID]!="yes") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($_GET['act']!="view") { 
$CanMakeReply = "no"; $CanMakeTopic = "no";
if($PermissionInfo['CanMakeTopics'][$TopicForumID]=="yes"&&$CanHaveTopics=="yes") { 
	$CanMakeTopic = "yes"; }
if($TopicClosed==0&&$PermissionInfo['CanMakeReplys'][$TopicForumID]=="yes") {
	$CanMakeReply = "yes"; }
if($TopicClosed==1&&$PermissionInfo['CanMakeReplysClose'][$TopicForumID]=="yes"
	&&$PermissionInfo['CanMakeReplys'][$TopicForumID]=="yes") {
		$CanMakeReply = "yes"; } ?>
<div style="padding: 10px; border: 1px solid gray;">
<?php echo $pstring; ?></div>
<div>&nbsp;</div>
<div style="padding: 10px; border: 1px solid gray;">
<?php } if($_GET['act']=="lowview") {
if($NumberReplies==null) { 
	$NumberReplies = 0; }
$num=$NumberReplies+1;
//Start Reply Page Code
if(!isset($Settings['max_posts'])) { $Settings['max_posts'] = 10; }
if($_GET['page']==null) { $_GET['page'] = 1; } 
if($_GET['page']<=0) { $_GET['page'] = 1; }
$nums = $_GET['page'] * $Settings['max_posts'];
if($nums>$num) { $nums = $num; }
$numz = $nums - $Settings['max_posts'];
if($numz<=0) { $numz = 0; }
//$i=$numz;
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
$snumber = $_GET['page'] - 1;
$PageLimit = $Settings['max_posts'] * $snumber;
if($PageLimit<0) { $PageLimit = 0; }
//End Reply Page Code
$i=0;
$query = query("SELECT * FROM `".$Settings['sqltable']."posts` WHERE `TopicID`=%i ORDER BY `TimeStamp` ASC LIMIT %i,%i", array($_GET['id'],$PageLimit,$Settings['max_posts']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
if($num==0) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($num!=0) { 
if($ViewTimes==0||$ViewTimes==null) { $NewViewTimes = 1; }
if($ViewTimes!=0&&$ViewTimes!=null) { $NewViewTimes = $ViewTimes + 1; }
$viewsup = query("UPDATE `".$Settings['sqltable']."topics` SET `NumViews`='%s' WHERE `id`=%i", array($NewViewTimes,$_GET['id']));
mysql_query($viewsup); }
//List Page Number Code Start
$pagenum=count($Pages);
if($_GET['page']>$pagenum) {
	$_GET['page'] = $pagenum; }
$pagei=0; $pstring = null;
if($pagenum>1) {
$pstring = "<div class=\"PageList\"><span class=\"pagelink\">".$pagenum." Pages:</span> "; }
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
$pstring = null; }
if($pagenum>1) {
while ($pagei < $pagenumi) {
if($_GET['page']!=1&&$pagei==1) {
$Pback = $_GET['page'] - 1;
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=lowview&id=".$_GET['id']."&page=".$Pback,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">&lt;</a></span> "; }
if($Pagez[$pagei]!=null&&
   $Pagez[$pagei]!="First"&&
   $Pagez[$pagei]!="Last") {
if($pagei!=3) { 
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=lowview&id=".$_GET['id']."&page=".$Pagez[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">".$Pagez[$pagei]."</a></span> "; }
if($pagei==3) { 
$pstring = $pstring."<span class=\"pagecurrent\"><a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=lowview&id=".$_GET['id']."&page=".$Pagez[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">".$Pagez[$pagei]."</a></span> "; } }
if($Pagez[$pagei]=="First") {
$pstring = $pstring."<span class=\"pagelinklast\"><a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=lowview&id=".$_GET['id']."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">&laquo;</a></span> "; }
if($Pagez[$pagei]=="Last") {
$ptestnext = $pagenext + 1;
$paget = $pagei - 1;
$Pnext = $_GET['page'] + 1;
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=lowview&id=".$_GET['id']."&page=".$Pnext,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">&gt;</a></span> ";
if($ptestnext<$pagenum) {
$pstring = $pstring."<span class=\"pagelinklast\"><a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=lowview&id=".$_GET['id']."&page=".$pagenum,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">&raquo;</a></span> "; } }
	++$pagei; } $pstring = $pstring."</div>"; }
//List Page Number Code end
$CanMakeReply = "no"; $CanMakeTopic = "no";
if($PermissionInfo['CanMakeTopics'][$TopicForumID]=="yes"&&$CanHaveTopics=="yes") { 
	$CanMakeTopic = "yes"; }
if($TopicClosed==0&&$PermissionInfo['CanMakeReplys'][$TopicForumID]=="yes") {
	$CanMakeReply = "yes"; }
if($TopicClosed==1&&$PermissionInfo['CanMakeReplysClose'][$TopicForumID]=="yes"
	&&$PermissionInfo['CanMakeReplys'][$TopicForumID]=="yes") {
		$CanMakeReply = "yes"; }
if($pstring!=null||$CanMakeReply=="yes"||$CanMakeTopic=="yes") {
/* ?>
<div style="padding: 10px; border: 1px solid gray;">
<?php echo $pstring; ?></div>
<div>&nbsp;</div>
<div style="padding: 10px; border: 1px solid gray;">
<ul style="list-style-type: none;">
<?php */ } while ($i < $num) {
$MyPostID=mysql_result($result,$i,"id");
$MyTopicID=mysql_result($result,$i,"TopicID");
$MyPostIP=mysql_result($result,$i,"IP");
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
$requery = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i LIMIT 1", array($MyUserID));
$reresult=mysql_query($requery);
$renum=mysql_num_rows($reresult);
$rei=0; $ipshow = "two";
$User1ID=$MyUserID; $GuestName = $MyGuestName;
$User1Name=mysql_result($reresult,$rei,"Name");
$User1IP=mysql_result($reresult,$rei,"IP");
if($User1IP==$MyPostIP) { $ipshow = "one"; }
$User1Email=mysql_result($reresult,$rei,"Email");
$User1Title=mysql_result($reresult,$rei,"Title");
$User1Joined=mysql_result($reresult,$rei,"Joined");
$User1Joined=GMTimeChange("M j Y",$User1Joined,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$User1GroupID=mysql_result($reresult,$rei,"GroupID");
$gquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i LIMIT 1", array($User1GroupID));
$gresult=mysql_query($gquery);
$User1Group=mysql_result($gresult,0,"Name");
$GroupNamePrefix=mysql_result($gresult,0,"NamePrefix");
$GroupNameSuffix=mysql_result($gresult,0,"NameSuffix");
@mysql_free_result($gresult);
$User1Signature=mysql_result($reresult,$rei,"Signature");
$User1Avatar=mysql_result($reresult,$rei,"Avatar");
$User1AvatarSize=mysql_result($reresult,$rei,"AvatarSize");
if ($User1Avatar=="http://"||$User1Avatar==null||
	strtolower($User1Avatar)=="noavatar") {
$User1Avatar=$ThemeSet['NoAvatar'];
$User1AvatarSize=$ThemeSet['NoAvatarSize']; }
$AvatarSize1=explode("x", $User1AvatarSize);
$AvatarSize1W=$AvatarSize1[0]; $AvatarSize1H=$AvatarSize1[1];
$User1Website=mysql_result($reresult,$rei,"Website");
$User1PostCount=mysql_result($reresult,$rei,"PostCount");
$User1Karma=mysql_result($reresult,$rei,"Karma");
$User1IP=mysql_result($reresult,$rei,"IP");
@mysql_free_result($reresult);
if($User1Name=="Guest") { $User1Name=$GuestName;
if($User1Name==null) { $User1Name="Guest"; } }
if(isset($GroupNamePrefix)&&$GroupNamePrefix!=null) {
	$User1Name = $GroupNamePrefix.$User1Name; }
if(isset($GroupNameSuffix)&&$GroupNameSuffix!=null) {
	$User1Name = $User1Name.$GroupNameSuffix; }
$MySubPost = null;
if($MyEditTime!=$MyTimeStamp&&$MyEditUserID!=0) {
$euquery = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i LIMIT 1", array($MyEditUserID));
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
$User1Signature = preg_replace("/\<br\>/", "<br />\n", nl2br($User1Signature));
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
$ReplyNum = $i + $PageLimit + 1;
?>
<div style="border: 1px solid #E6E3E4; padding:1px; margin-bottom: 15px; background-color: #E6E3E4; padding: 6px;">
<div style="font-weight: bold; font-size: 0.8em; width: auto; float: left;"><?php echo $User1Name; ?></div>
<div style="width:auto; font-size: 0.8em; color: gray; text-align:right;"><?php echo $MyTimeStamp; ?></div>
</div>
<div style="padding: 6px; font-size: 0.8em;"><?php echo $MyPost; ?></div>
<?php ++$i; } @mysql_free_result($result); 
?></div><div>&nbsp;</div><?php } } ?>
