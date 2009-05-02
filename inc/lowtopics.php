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

    $FileInfo: lowtopics.php - Last Update: 5/01/2009 SVN 247 - Author: cooldude2k $
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
?>
<div style="font-size: 1.0em; font-weight: bold; margin-bottom: 10px; padding-top: 3px; width: auto;">Full Version: <a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=view&id=".$ForumID."&page=".$_GET['page'],$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $Settings['board_name']; ?></a></div>
<div style="padding: 10px; border: 1px solid gray;"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">Board index</a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=lowview&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"><?php echo $ForumName; ?></a></div>
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
<li>There are now topics in this forum. :( </li>
<?php } if($num>0) { ?>
<ul style="list-style-type: decimal;">
<?php }
while ($i < $num) {
$TopicID=mysql_result($result,$i,"id");
$UsersID=mysql_result($result,$i,"UserID");
$GuestName=mysql_result($result,$i,"GuestName");
$TheTime=mysql_result($result,$i,"TimeStamp");
$TheTime=GMTimeChange("F j Y, g:i a",$TheTime,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$NumReply=mysql_result($result,$i,"NumReply");
$NumberPosts=$NumReply + 1;
$prepagelist = null;
if(!isset($Settings['max_posts'])) { 
	$Settings['max_posts'] = 10; }
if(!isset($ThemeSet['MiniPageAltStyle'])) { 
	$ThemeSet['MiniPageAltStyle'] = "off"; }
if($ThemeSet['MiniPageAltStyle']!="on"||
	$ThemeSet['MiniPageAltStyle']!="off") { 
	$ThemeSet['MiniPageAltStyle'] = "off"; }
if($NumberPosts>$Settings['max_posts']) {
$NumberPages = ceil($NumberPosts/$Settings['max_posts']); }
if($NumberPosts<=$Settings['max_posts']) {
$NumberPages = 1; }
if($NumberPages>4) {
	$prepagelist = " &nbsp;"; }
if($NumberPages>=2) {
	if($ThemeSet['MiniPageAltStyle']=="off") { 
	$prepagelist = "<span class=\"small\">(Pages: "; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<span class=\"minipagelink\">"; }
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=lowview&id=".$TopicID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">1</a>";
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; }
	if($ThemeSet['MiniPageAltStyle']=="off") { $prepagelist = $prepagelist." "; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<span class=\"minipagelink\">"; }
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=lowview&id=".$TopicID."&page=2",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">2</a>";
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; }
	if($NumberPages>=3) {
	if($ThemeSet['MiniPageAltStyle']=="off") { $prepagelist = $prepagelist." "; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<span class=\"minipagelink\">"; }
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=lowview&id=".$TopicID."&page=3",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">3</a>";
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; } }
	if($NumberPages==4) {
	if($ThemeSet['MiniPageAltStyle']=="off") { $prepagelist = $prepagelist." "; }
	$prepagelist = $prepagelist."<span class=\"minipagelinklast\">";
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=lowview&id=".$TopicID."&page=4",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">4</a>"; }
	if($ThemeSet['MiniPageAltStyle']=="off") {
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=lowview&id=".$TopicID."&page=4",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\"> ...4</a>"; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; } }
	if($NumberPages>4) {
	if($ThemeSet['MiniPageAltStyle']=="off") { $prepagelist = $prepagelist." "; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<span class=\"minipagelinklast\">"; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=lowview&id=".$TopicID."&page=".$NumberPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">&raquo; ".$NumberPages."</a>"; }
	if($ThemeSet['MiniPageAltStyle']=="off") {
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=lowview&id=".$TopicID."&page=".$NumberPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\"> ...".$NumberPages."</a>"; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; } }
	if($ThemeSet['MiniPageAltStyle']=="off") { 
	$prepagelist = $prepagelist.")</span>"; } }
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
if($UsersName=="Guest") { $UsersName=$GuestName;
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
<li><a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=lowview&id=".$TopicID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>"><?php echo $PreTopic.$TopicName; ?></a>(<?php echo $NumReply; ?> replies)</li>
<?php ++$i; } ?>
</ul></div><div>&nbsp;</div>
<?php @mysql_free_result($result); }/*
if($_GET['act']=="maketopic"&&$_POST['act']=="maketopics") {
if($PermissionInfo['CanMakeTopics'][$ForumID]=="no"||$CanHaveTopics=="no") { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
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
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=lowview&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ForumName; ?></a></span></div>
<?php } ?>
<table class="Table1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1">
<td class="TableColumn1"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=lowview&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ForumName; ?></a></span>
</td>
</tr><?php } ?>
<tr class="TableRow2">
<th class="TableColumn2" style="width: 100%; text-align: left;">&nbsp;Make Topic Message: </th>
</tr>
<tr class="TableRow3">
<td class="TableColumn3">
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
<?php } if ($Settings['TestReferer']=="on") {
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
$_SESSION['GuestName']=$_POST['GuestName']; } }*/
/*    <_<  iWordFilter  >_>      
   by Kazuki Przyborowski - Cool Dude 2k *//*
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
@redirect("refresh",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false),"4"); ?>
<tr>
	<td><span class="TableMessage">
	<br />Click <a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">here</a> to goto index page.<br />&nbsp;
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
$query = query("INSERT INTO `".$Settings['sqltable']."topics` VALUES (".$topicid.",%i,%i,0,%i,'%s',%i,%i,'%s','%s',0,0,0,0)", array($ForumID,$ForumCatID,$User1ID,$User1Name,$LastActive,$LastActive,$_POST['TopicName'],$_POST['TopicDesc']));
mysql_query($query);
$query = query("INSERT INTO `".$Settings['sqltable']."posts` VALUES (".$postid.",".$topicid.",%i,%i,%i,'%s',%i,%i,0,'%s','%s','%s','0')", array($ForumID,$ForumCatID,$User1ID,$User1Name,$LastActive,$LastActive,$_POST['TopicPost'],$_POST['TopicDesc'],$User1IP));
mysql_query($query);
if($User1ID!=0&&$User1ID!=-1) {
$queryupd = query("UPDATE `".$Settings['sqltable']."members` SET `LastActive`=%i,`IP`='%s',`PostCount`=%i WHERE `id`=%i", array($LastActive,$User1IP,$NewPostCount,$User1ID));
mysql_query($queryupd); }
$NewNumPosts = $NumberPosts + 1; $NewNumTopics = $NumberTopics + 1;
$queryupd = query("UPDATE `".$Settings['sqltable']."forums` SET `NumPosts`=%i,`NumTopics`=%i WHERE `id`=%i", array($NewNumPosts,$NewNumTopics,$ForumID));
mysql_query($queryupd);
@redirect("refresh",$basedir.url_maker($exfile['topic'],$Settings['file_ext'],"act=lowview&id=".$topicid."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'],FALSE),"3");
?><tr>
	<td><span class="TableMessage"><br />
	Topic <?php echo $_POST['TopicName']; ?> was started.<br />
	Click <a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=lowview&id=".$topicid."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>">here</a> to continue to topic.<br />&nbsp;
	</span><br /></td>
</tr>
<?php }  ?>
</ul></div>
<?php } */ } } ?>
