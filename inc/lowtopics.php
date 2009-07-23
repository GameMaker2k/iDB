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

    $FileInfo: lowtopics.php - Last Update: 7/23/2009 SVN 280 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="lowtopics.php"||$File3Name=="/lowtopics.php") {
	require('index.php');
	exit(); }
$pstring = null; $pagenum = null;
if(!is_numeric($_GET['id'])) { $_GET['id'] = null; }
if(!is_numeric($_GET['page'])) { $_GET['page'] = null; }
$prequery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `id`=%i LIMIT 1", array($_GET['id']));
$preresult=mysql_query($prequery);
$prenum=mysql_num_rows($preresult);
if($prenum==0) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); @mysql_free_result($preresult);
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($prenum>=1) {
$ForumID=mysql_result($preresult,0,"id");
$ForumCatID=mysql_result($preresult,0,"CategoryID");
$ForumName=mysql_result($preresult,0,"Name");
$ForumType=mysql_result($preresult,0,"ForumType");
$InSubForum=mysql_result($preresult,0,"InSubForum");
$RedirectURL=mysql_result($preresult,0,"RedirectURL");
$RedirectTimes=mysql_result($preresult,0,"Redirects");
$NumberViews=mysql_result($preresult,0,"NumViews");
$NumberPosts=mysql_result($preresult,0,"NumPosts");
$NumberTopics=mysql_result($preresult,0,"NumTopics");
$PostCountAdd=mysql_result($preresult,0,"PostCountAdd");
$CanHaveTopics=mysql_result($preresult,0,"CanHaveTopics");
$HotTopicPosts=mysql_result($preresult,0,"HotTopicPosts");
if($HotTopicPosts!=0&&is_numeric($HotTopicPosts)) {
	$Settings['hot_topic_num'] = $HotTopicPosts; }
if(!is_numeric($Settings['hot_topic_num'])) {
	$Settings['hot_topic_num'] = 15; }
$ForumPostCountView=mysql_result($preresult,0,"PostCountView");
$ForumKarmaCountView=mysql_result($preresult,0,"KarmaCountView");
@mysql_free_result($preresult);
$ForumType = strtolower($ForumType); $CanHaveTopics = strtolower($CanHaveTopics);
$catcheck = query("SELECT * FROM `".$Settings['sqltable']."categories` WHERE `id`=%i  LIMIT 1", array($ForumCatID));
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
if($InSubForum!="0") {
$isfquery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `id`=%i LIMIT 1", array($InSubForum));
$isfresult=mysql_query($isfquery);
$isfnum=mysql_num_rows($isfresult);
if($isfnum>=1) {
$isfForumID=mysql_result($isfresult,0,"id");
$isfForumCatID=mysql_result($isfresult,0,"CategoryID");
$isfForumName=mysql_result($isfresult,0,"Name");
$isfForumType=mysql_result($isfresult,0,"ForumType");
$isfRedirectURL=mysql_result($isfresult,0,"RedirectURL"); }
if($isfnum<1) { $InSubForum = "0"; } } 
@mysql_free_result($isfresult); }
?>
<div style="font-size: 1.0em; font-weight: bold; margin-bottom: 10px; padding-top: 3px; width: auto;">Full Version: <a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=view&id=".$ForumID."&page=".$_GET['page'],$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ForumName; ?></a></div>
<div style="padding: 10px; border: 1px solid gray;"><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">Board index</a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$CategoryType],$Settings['file_ext'],"act=lowview&id=".$ForumCatID,$Settings['qstr'],$Settings['qsep'],$prexqstr[$CategoryType],$exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a><?php if($InSubForum!="0") { echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$isfForumType],$Settings['file_ext'],"act=view&id=".$isfForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$isfForumType],$exqstr[$isfForumType]); ?>"><?php echo $isfForumName; ?></a><?php } echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=lowview&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"><?php echo $ForumName; ?></a></div>
<div>&nbsp;</div>
<?php
if(!isset($CatPermissionInfo['CanViewCategory'][$ForumCatID])) {
	$CatPermissionInfo['CanViewCategory'][$ForumCatID] = "no"; }
if($CatPermissionInfo['CanViewCategory'][$ForumCatID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$ForumCatID]!="yes") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if(!isset($PermissionInfo['CanViewForum'][$ForumID])) {
	$PermissionInfo['CanViewForum'][$ForumID] = "no"; }
if($PermissionInfo['CanViewForum'][$ForumID]=="no"||
	$PermissionInfo['CanViewForum'][$ForumID]!="yes") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
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
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); } }
if($ForumCheck!="skip") {
if($ForumType=="subforum") {
redirect("location",$basedir.url_maker($exfile['subforum'],$Settings['file_ext'],"act=".$_GET['act']."&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['subforum'],$exqstr['subforum'],FALSE));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); } }
if($_GET['act']=="lowview") {
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
$snumber = $_GET['page'] - 1;
$PageLimit = $Settings['max_topics'] * $snumber;
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
if($NumberTopics==0) {
$pagenumi = 0;
$pstring = null; }
if($pagenum>1) {
while ($pagei < $pagenumi) {
if($_GET['page']!=1&&$pagei==1) {
$Pback = $_GET['page'] - 1;
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile[$ForumType],$Settings['file_ext'],"act=lowview&id=".$_GET['id']."&page=".$Pback,$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType])."\">&lt;</a></span> "; }
if($Pagez[$pagei]!=null&&
   $Pagez[$pagei]!="First"&&
   $Pagez[$pagei]!="Last") {
if($pagei!=3) { 
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile[$ForumType],$Settings['file_ext'],"act=lowview&id=".$_GET['id']."&page=".$Pagez[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType])."\">".$Pagez[$pagei]."</a></span> "; }
if($pagei==3) { 
$pstring = $pstring."<span class=\"pagecurrent\"><a href=\"".url_maker($exfile[$ForumType],$Settings['file_ext'],"act=lowview&id=".$_GET['id']."&page=".$Pagez[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType])."\">".$Pagez[$pagei]."</a></span> "; } }
if($Pagez[$pagei]=="First") {
$pstring = $pstring."<span class=\"pagelinklast\"><a href=\"".url_maker($exfile[$ForumType],$Settings['file_ext'],"act=lowview&id=".$_GET['id']."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType])."\">&laquo;</a></span> "; }
if($Pagez[$pagei]=="Last") {
$ptestnext = $pagenext + 1;
$paget = $pagei - 1;
$Pnext = $_GET['page'] + 1;
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile[$ForumType],$Settings['file_ext'],"act=lowview&id=".$_GET['id']."&page=".$Pnext,$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType])."\">&gt;</a></span> ";
if($ptestnext<$pagenum) {
$pstring = $pstring."<span class=\"pagelinklast\"><a href=\"".url_maker($exfile[$ForumType],$Settings['file_ext'],"act=lowview&id=".$_GET['id']."&page=".$pagenum,$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType])."\">&raquo;</a></span> "; } }
	++$pagei; } $pstring = $pstring."</div>"; }
?>
<div style="padding: 10px; border: 1px solid gray;">
<?php echo $pstring; ?></div>
<div>&nbsp;</div>
<div style="padding: 10px; border: 1px solid gray;">
<?php if($num<=0) { ?>
<ul style="list-style-type: none;">
<li>&nbsp;</li>
<?php } if($num>0) { ?>
<ul style="list-style-type: decimal;">
<?php }
while ($i < $num) {
$TopicID=mysql_result($result,$i,"id");
$UsersID=mysql_result($result,$i,"UserID");
$GuestsName=mysql_result($result,$i,"GuestName");
$TheTime=mysql_result($result,$i,"TimeStamp");
$TheTime=GMTimeChange("F j Y, g:i a",$TheTime,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$NumReply=mysql_result($result,$i,"NumReply");
$NumberPosts=$NumReply + 1;
$prepagelist = null;
if(!isset($Settings['max_posts'])) { 
	$Settings['max_posts'] = 10; }
$TopicName=mysql_result($result,$i,"TopicName");
$TopicDescription=mysql_result($result,$i,"Description");
$PinnedTopic=mysql_result($result,$i,"Pinned");
$TopicStat=mysql_result($result,$i,"Closed");
$requery = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i LIMIT 1", array($UsersID));
$reresult=mysql_query($requery);
$renum=mysql_num_rows($reresult);
$UserGroupID=mysql_result($reresult,0,"GroupID");
@mysql_free_result($reresult);
$gquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i LIMIT 1", array($UserGroupID));
$gresult=mysql_query($gquery);
$User1Group=mysql_result($gresult,0,"Name");
$GroupNamePrefix=mysql_result($gresult,0,"NamePrefix");
$GroupNameSuffix=mysql_result($gresult,0,"NameSuffix");
@mysql_free_result($gresult);
$UsersName = GetUserName($UsersID,$Settings['sqltable']);
if($UsersName=="Guest") { $UsersName=$GuestsName;
if($UsersName==null) { $UsersName="Guest"; } }
if(isset($GroupNamePrefix)&&$GroupNamePrefix!=null) {
	$UsersName = $GroupNamePrefix.$UsersName; }
if(isset($GroupNameSuffix)&&$GroupNameSuffix!=null) {
	$UsersName = $UsersName.$GroupNameSuffix; }
$PreTopic = null;
if ($PinnedTopic>1) { $PinnedTopic = 1; } 
if ($PinnedTopic<0) { $PinnedTopic = 0; }
if(!is_numeric($PinnedTopic)) { $PinnedTopic = 0; }
if ($TopicStat>1) { $TopicStat = 1; } 
if ($TopicStat<0) { $TopicStat = 0; }
if(!is_numeric($TopicStat)) { $TopicStat = 1; }
if ($PinnedTopic==1) { $PreTopic="Pinned: "; }
if ($PinnedTopic==0) { $PreTopic=null; }
?>
<li><a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=lowview&id=".$TopicID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>"><?php echo $PreTopic.$TopicName; ?></a> <span style="color: gray; font-size: 10px;">(<?php echo $NumReply; ?> replies)</span></li>
<?php ++$i; } ?>
</ul></div><div>&nbsp;</div>
<?php @mysql_free_result($result); } } } ?>
