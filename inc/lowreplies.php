<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2023 iDB Support - https://idb.osdn.jp/support/category.php?act=view&id=1
    Copyright 2004-2023 Game Maker 2k - https://idb.osdn.jp/support/category.php?act=view&id=2

    $FileInfo: lowreplies.php - Last Update: 6/22/2023 SVN 984 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="replies.php"||$File3Name=="/replies.php") {
	require('index.php');
	exit(); }
$pstring = null; $pagenum = null;
if(!is_numeric($_GET['id'])) { $_GET['id'] = null; }
if(!is_numeric($_GET['post'])) { $_GET['post'] = null; }
if(!is_numeric($_GET['page'])) { $_GET['page'] = 1; }
if(!isset($_GET['st'])) { $_GET['st'] = 0; }
if(!is_numeric($_GET['st'])) { $_GET['st'] = 0; }
if(!isset($_GET['modact'])) { $_GET['modact'] = null; }
if($_GET['modact']=="pin"||$_GET['modact']=="unpin"||$_GET['modact']=="open"||
	$_GET['modact']=="close"||$_GET['modact']=="edit"||$_GET['modact']=="delete")
		{ $_GET['act'] = $_GET['modact']; }
$prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."topics\" WHERE \"id\"=%i".$ForumIgnoreList4." LIMIT 1", array($_GET['id']));
$preresult=sql_query($prequery,$SQLStat);
$prenum=sql_num_rows($preresult);
if($prenum==0) { redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); sql_free_result($preresult);
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($prenum>=1) {
$TopicName=sql_result($preresult,0,"TopicName");
$TopicID=sql_result($preresult,0,"id");
$TopicForumID=sql_result($preresult,0,"ForumID");
$TopicCatID=sql_result($preresult,0,"CategoryID");
$TopicClosed=sql_result($preresult,0,"Closed");
if($TopicClosed==3&&$PermissionInfo['CanModForum'][$TopicForumID]=="no") { 
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); sql_free_result($preresult);
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if(!isset($_GET['post'])||$_GET['post']!==null) {
$NumberReplies=sql_result($preresult,0,"NumReply"); }
if(isset($_GET['post'])&&$_GET['post']!==null) {
$NumberReplies=1; }
$ViewTimes=sql_result($preresult,0,"NumViews");
sql_free_result($preresult);
$forumcheckx = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i".$ForumIgnoreList2."  LIMIT 1", array($TopicForumID));
$fmckresult=sql_query($forumcheckx,$SQLStat);
$fmcknum=sql_num_rows($fmckresult);
if($fmcknum==0) { redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); sql_free_result($preresult);
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
$ForumID=sql_result($fmckresult,0,"id");
$ForumName=sql_result($fmckresult,0,"Name");
$ForumType=sql_result($fmckresult,0,"ForumType");
$ForumShow=sql_result($fmckresult,0,"ShowForum");
$InSubForum=sql_result($fmckresult,0,"InSubForum");
if($InSubForum!=0) {
$subforumcheckx = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i".$ForumIgnoreList2."  LIMIT 1", array($InSubForum));
$subfmckresult=sql_query($subforumcheckx,$SQLStat);
$subfmcknum=sql_num_rows($subfmckresult);
$SubForumName=sql_result($subfmckresult,0,"Name");
$SubForumType=sql_result($subfmckresult,0,"ForumType");
$InSubCategory=sql_result($catresult,0,"InSubCategory");
$SubForumShow=sql_result($subfmckresult,0,"ShowForum");
sql_free_result($subfmckresult); }
if($ForumShow=="no") { $_SESSION['ShowActHidden'] = "yes"; }
$CanHaveTopics=sql_result($fmckresult,0,"CanHaveTopics");
$ForumPostCountView=sql_result($fmckresult,0,"PostCountView");
$ForumKarmaCountView=sql_result($fmckresult,0,"KarmaCountView");
sql_free_result($fmckresult);
$catcheck = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i".$CatIgnoreList2."  LIMIT 1", array($TopicCatID));
$catresult=sql_query($catcheck,$SQLStat);
$CategoryID=sql_result($catresult,0,"id");
$CategoryName=sql_result($catresult,0,"Name");
$CategoryShow=sql_result($catresult,0,"ShowCategory");
if($CategoryShow=="no") { $_SESSION['ShowActHidden'] = "yes"; }
$CategoryType=sql_result($catresult,0,"CategoryType");
$CategoryPostCountView=sql_result($catresult,0,"PostCountView");
$CategoryKarmaCountView=sql_result($catresult,0,"KarmaCountView");
sql_free_result($catresult);
if($GroupInfo['HasAdminCP']!="yes"||$GroupInfo['HasModCP']!="yes") {
if($MyPostCountChk==null) { $MyPostCountChk = 0; }
if($MyKarmaCount==null) { $MyKarmaCount = 0; }
if($ForumPostCountView!=0&&$MyPostCountChk<$ForumPostCountView) {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($CategoryPostCountView!=0&&$MyPostCountChk<$CategoryPostCountView) {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($ForumKarmaCountView!=0&&$MyKarmaCount<$ForumKarmaCountView) {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($CategoryKarmaCountView!=0&&$MyKarmaCount<$CategoryKarmaCountView) {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); } }
if($ForumCheck!="skip") {
if(isset($_SESSION['OldViewingPage'])) { $_SESSION['AncientViewingPage'] = $_SESSION['OldViewingPage']; } else { $_SESSION['AncientViewingPage'] = url_maker(null,"no+ext","act=view","&","=",$prexqstr['index'],$exqstr['index']); }
if(isset($_SESSION['OldViewingFile'])) { $_SESSION['AncientViewingFile'] = $_SESSION['OldViewingFile']; } else { 
	 if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
	    $_SESSION['AncientViewingFile'] = $exfile['index'].$Settings['file_ext']; }
	 if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
	    $_SESSION['AncientViewingFile'] = $exfile['index']; } }
if(isset($_SESSION['OldPreViewingTitle'])) { $_SESSION['AncientPreViewingTitle'] = $_SESSION['OldPreViewingTitle']; } else { $_SESSION['AncientPreViewingTitle'] = "Viewing"; }
if(isset($_SESSION['OldViewingTitle'])) { $_SESSION['AncientViewingTitle'] = $_SESSION['OldViewingTitle']; } else { $_SESSION['AncientViewingTitle'] = "Board index"; }
if(isset($_SESSION['OldExtraData'])) { $_SESSION['AncientExtraData'] = $_SESSION['OldExtraData']; } else { $_SESSION['AncientExtraData'] = "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;"; }
if(isset($_SESSION['ViewingPage'])) { $_SESSION['OldViewingPage'] = $_SESSION['ViewingPage']; } else { $_SESSION['OldViewingPage'] = url_maker(null,"no+ext","act=view","&","=",$prexqstr['index'],$exqstr['index']); }
if(isset($_SESSION['ViewingFile'])) { $_SESSION['OldViewingFile'] = $_SESSION['ViewingFile']; } else { 
	 if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
	    $_SESSION['OldViewingFile'] = $exfile['index'].$Settings['file_ext']; }
	 if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
	    $_SESSION['OldViewingFile'] = $exfile['index']; } }
if(isset($_SESSION['PreViewingTitle'])) { $_SESSION['OldPreViewingTitle'] = $_SESSION['PreViewingTitle']; } else { $_SESSION['OldPreViewingTitle'] = "Viewing"; }
if(isset($_SESSION['ViewingTitle'])) { $_SESSION['OldViewingTitle'] = $_SESSION['ViewingTitle']; } else { $_SESSION['OldViewingTitle'] = "Board index"; }
if(isset($_SESSION['ExtraData'])) { $_SESSION['OldExtraData'] = $_SESSION['ExtraData']; } else { $_SESSION['OldExtraData'] = "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;"; }
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=view&id=".$_GET['id']."&page=".$_GET['page'],"&","=",$prexqstr['topic'],$exqstr['topic']);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['topic'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['topic']; }
$_SESSION['PreViewingTitle'] = "Viewing Topic:";
$_SESSION['ViewingTitle'] = $TopicName;
if(isset($InSubCategory)) {
$_SESSION['ExtraData'] = "currentact:".$_GET['act']."; currentcategoryid:".$InSubCategory.",".$CategoryID."; currentforumid:".$InSubForum.",".$ForumID."; currenttopicid:".$TopicID."; currentmessageid:0; currenteventid:0; currentmemberid:0;"; } else {
$_SESSION['ExtraData'] = "currentact:".$_GET['act']."; currentcategoryid:0,".$CategoryID."; currentforumid:".$InSubForum.",".$ForumID."; currenttopicid:".$TopicID."; currentmessageid:0; currenteventid:0; currentmemberid:0;"; }
?>
<div style="font-size: 1.0em; font-weight: bold; margin-bottom: 10px; padding-top: 3px; width: auto;">Full Version: <a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>"><?php echo $TopicName; ?></a></div>
<div style="font-size: 11px; font-weight: bold; padding: 10px; border: 1px solid gray;"><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$CategoryType],$Settings['file_ext'],"act=lowview&id=".$TopicCatID,$Settings['qstr'],$Settings['qsep'],$prexqstr[$CategoryType],$exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a><?php echo $ThemeSet['NavLinkDivider']; if($InSubForum!=0 && $subfmcknum>0) { ?><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=lowview&id=".$InSubForum."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"><?php echo $SubForumName; ?></a><?php echo $ThemeSet['NavLinkDivider']; } ?><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=lowview&id=".$TopicForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"><?php echo $ForumName; ?></a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=lowview&id=".$_GET['id']."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>"><?php echo $TopicName; ?></a></div>
<div>&#160;</div>
<?php }
if(!isset($CatPermissionInfo['CanViewCategory'][$TopicCatID])) {
	$CatPermissionInfo['CanViewCategory'][$TopicCatID] = "no"; }
if($CatPermissionInfo['CanViewCategory'][$TopicCatID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$TopicCatID]!="yes") {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if(!isset($PermissionInfo['CanViewForum'][$TopicForumID])) {
	$PermissionInfo['CanViewForum'][$TopicForumID] = "no"; }
if($PermissionInfo['CanViewForum'][$TopicForumID]=="no"||
	$PermissionInfo['CanViewForum'][$TopicForumID]!="yes") {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($_GET['act']!="view") { 
$CanMakeReply = "no"; $CanMakeTopic = "no";
if($PermissionInfo['CanMakeTopics'][$TopicForumID]=="yes"&&$CanHaveTopics=="yes") { 
	$CanMakeTopic = "yes"; }
if($TopicClosed==0&&$PermissionInfo['CanMakeReplys'][$TopicForumID]=="yes") {
	$CanMakeReply = "yes"; }
if($TopicClosed==1&&$PermissionInfo['CanMakeReplysClose'][$TopicForumID]=="yes"
	&&$PermissionInfo['CanMakeReplys'][$TopicForumID]=="yes") {
		$CanMakeReply = "yes"; } } 
if($_GET['act']=="lowview") {
if($NumberReplies==null) { 
	$NumberReplies = 0; }
$num=$NumberReplies+1;
//Start Reply Page Code
if(!isset($Settings['max_posts'])) { $Settings['max_posts'] = 10; }
if($_GET['page']==null) { $_GET['page'] = 1; } 
if($_GET['page']<=0) { $_GET['page'] = 1; }
if($_GET['st']<=0||!isset($_GET['st'])) {
$nums = $_GET['page'] * $Settings['max_posts']; }
if($_GET['st']>0&&isset($_GET['st'])) {
$nums = $_GET['st']; }
if($nums>$num) { $nums = $num; }
$numz = $nums - $Settings['max_posts'];
if($numz<=0) { $numz = 0; }
//$i=$numz;
if($nums<$num) { $nextpage = $_GET['page'] + 1; }
if($nums>=$num) { $nextpage = $_GET['page']; }
if($numz>=$Settings['max_posts']) { $backpage = $_GET['page'] - 1; }
if($_GET['page']<=1) { $backpage = 1; }
$pnum = $num; $l = 1; $Pages = array();;
while ($pnum>0) {
if($pnum>=$Settings['max_posts']) { 
	$pnum = $pnum - $Settings['max_posts']; 
	$Pages[$l] = $l; ++$l; }
if($pnum<$Settings['max_posts']&&$pnum>0) { 
	$pnum = $pnum - $pnum; 
	$Pages[$l] = $l; ++$l; } }
$snumber = $_GET['page'] - 1;
if($_GET['st']<=0||!isset($_GET['st'])) {
$PageLimit = $Settings['max_posts'] * $snumber; }
if($_GET['st']>0&&isset($_GET['st'])) {
$PageLimit = $_GET['st']; }
if($PageLimit<0) { $PageLimit = 0; }
//End Reply Page Code
$i=0;
if(!isset($_GET['post'])||$_GET['post']!==null) {
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."posts\" WHERE \"TopicID\"=%i ORDER BY \"TimeStamp\" ASC ".$SQLimit, array($_GET['id'],$PageLimit,$Settings['max_posts'])); }
if(isset($_GET['post'])&&$_GET['post']!==null) {
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."posts\" WHERE \"TopicID\"=%i AND \"id\"=%i ORDER BY \"TimeStamp\" ASC ".$SQLimit, array($_GET['id'],$_GET['post'],$PageLimit,$Settings['max_posts'])); }
$result=sql_query($query,$SQLStat);
$num=sql_num_rows($result);
if($num==0) { redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($num!=0) { 
if($ViewTimes==0||$ViewTimes==null) { $NewViewTimes = 1; }
if($ViewTimes!=0&&$ViewTimes!=null) { $NewViewTimes = $ViewTimes + 1; }
$viewsup = sql_pre_query("UPDATE \"".$Settings['sqltable']."topics\" SET \"NumViews\"='%s' WHERE \"id\"=%i", array($NewViewTimes,$_GET['id']));
sql_query($viewsup,$SQLStat); }
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
?>
<div style="font-size: 11px; font-weight: bold; padding: 10px; border: 1px solid gray;">
<?php echo $pstring; ?></div>
<div>&#160;</div>
<div style="padding: 10px; border: 1px solid gray;">
<?php while ($i < $num) {
$MyPostID=sql_result($result,$i,"id");
$MyTopicID=sql_result($result,$i,"TopicID");
$MyPostIP=sql_result($result,$i,"IP");
$MyForumID=sql_result($result,$i,"ForumID");
$MyCategoryID=sql_result($result,$i,"CategoryID");
$MyUserID=sql_result($result,$i,"UserID");
$MyGuestName=sql_result($result,$i,"GuestName");
$MyTimeStamp=sql_result($result,$i,"TimeStamp");
$MyEditTime=sql_result($result,$i,"LastUpdate");
$MyEditUserID=sql_result($result,$i,"EditUser");
$MyEditUserName=sql_result($result,$i,"EditUserName");
$tmpusrcurtime = new DateTime();
$tmpusrcurtime->setTimestamp($MyTimeStamp);
$tmpusrcurtime->setTimezone($usertz);
$MyTimeStamp=$tmpusrcurtime->format($_SESSION['iDBDateFormat'].", ".$_SESSION['iDBTimeFormat']);
$MyPost=sql_result($result,$i,"Post");
$MyDescription=sql_result($result,$i,"Description");
$requery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($MyUserID));
$reresult=sql_query($requery,$SQLStat);
$renum=sql_num_rows($reresult);
if($renum<1) { $MyUserID = -1;
$requery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($MyUserID));
$reresult=sql_query($requery,$SQLStat);
$renum=sql_num_rows($reresult); }
$memrequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."mempermissions\" WHERE \"id\"=%i LIMIT 1", array($MyUserID));
$memreresult=sql_query($memrequery,$SQLStat);
$memrenum=sql_num_rows($memreresult);
$rei=0; $ipshow = "two";
$User1ID=$MyUserID; $GuestsName = $MyGuestName;
$User1Name=sql_result($reresult,$rei,"Name");
$User1IP=sql_result($reresult,$rei,"IP");
if($User1IP==$MyPostIP) { $ipshow = "one"; }
$User1Email=sql_result($reresult,$rei,"Email");
$User1Title=sql_result($reresult,$rei,"Title");
$PreUserCanExecPHP=sql_result($memreresult,$rei,"CanExecPHP");
if($PreUserCanExecPHP!="yes"&&$PreUserCanExecPHP!="no"&&$PreUserCanExecPHP!="group") {
	$PreUserCanExecPHP = "no"; }
$PreUserCanDoHTML=sql_result($memreresult,$rei,"CanDoHTML");
if($PreUserCanDoHTML!="yes"&&$PreUserCanDoHTML!="no"&&$PreUserCanDoHTML!="group") {
	$PreUserCanDoHTML = "no"; }
$PreUserCanUseBBTags=sql_result($memreresult,$rei,"CanUseBBTags");
if($PreUserCanUseBBTags!="yes"&&$PreUserCanUseBBTags!="no"&&$PreUserCanUseBBTags!="group") {
	$PreUserCanUseBBTags = "no"; }
sql_free_result($memreresult);
$User1Joined=sql_result($reresult,$rei,"Joined");
$tmpusrcurtime = new DateTime();
$tmpusrcurtime->setTimestamp($User1Joined);
$tmpusrcurtime->setTimezone($usertz);
$User1Joined=$tmpusrcurtime->format($_SESSION['iDBDateFormat']);
$User1GroupID=sql_result($reresult,$rei,"GroupID");
$gquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($User1GroupID));
$gresult=sql_query($gquery,$SQLStat);
$User1Group=sql_result($gresult,0,"Name");
$User1CanExecPHP = $PreUserCanExecPHP;
if($PreUserCanExecPHP=="group") {
$User1CanExecPHP=sql_result($gresult,0,"CanExecPHP"); }
if($User1CanExecPHP!="yes"&&$User1CanExecPHP!="no") {
	$User1CanExecPHP = "no"; }
$User1CanDoHTML = $PreUserCanDoHTML;
if($PreUserCanDoHTML=="group") {
$User1CanDoHTML=sql_result($gresult,0,"CanDoHTML"); }
if($User1CanDoHTML!="yes"&&$User1CanDoHTML!="no") {
	$User1CanDoHTML = "no"; }
$User1CanUseBBTags = $PreUserCanUseBBTags;
if($User1CanUseBBTags=="group") {
$User1CanUseBBTags=sql_result($gresult,0,"CanUseBBTags"); }
if($User1CanUseBBTags!="yes"&&$User1CanUseBBTags!="no") {
	$User1CanUseBBTags = "no"; }
$GroupNamePrefix=sql_result($gresult,0,"NamePrefix");
$GroupNameSuffix=sql_result($gresult,0,"NameSuffix");
$User1PermissionID=sql_result($gresult,0,"PermissionID");
sql_free_result($gresult);
$per1query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."permissions\" WHERE \"PermissionID\"=%i LIMIT 1", array($User1PermissionID));
$per1esult=sql_query($per1query,$SQLStat);
$per1num=sql_num_rows($per1esult);
$User1CanDoHTML1=sql_result($per1esult,0,"CanDoHTML");
if($User1CanDoHTML1!="yes"&&$User1CanDoHTML1!="no") {
	$User1CanDoHTML1 = "no"; }
$User1CanUseBBTags1=sql_result($per1esult,0,"CanUseBBTags");
if($User1CanUseBBTags1!="yes"&&$User1CanUseBBTags1!="no") {
	$User1CanUseBBTags1 = "no"; }
sql_free_result($per1esult);
$User1Signature=sql_result($reresult,$rei,"Signature");
$User1Avatar=sql_result($reresult,$rei,"Avatar");
$User1AvatarSize=sql_result($reresult,$rei,"AvatarSize");
if ($User1Avatar=="http://"||$User1Avatar==null||
	strtolower($User1Avatar)=="noavatar") {
$User1Avatar=$ThemeSet['NoAvatar'];
$User1AvatarSize=$ThemeSet['NoAvatarSize']; }
$AvatarSize1=explode("x", $User1AvatarSize);
$AvatarSize1W=$AvatarSize1[0]; $AvatarSize1H=$AvatarSize1[1];
$User1Website=sql_result($reresult,$rei,"Website");
$BoardWWWChCk = parse_url($Settings['idburl']);
if($User1Website=="http://") { 
	$User1Website = $Settings['idburl']; }
$User1WWWChCk = parse_url($User1Website);
$User1Website = urlcheck($User1Website);
$opennew = " onclick=\"window.open(this.href);return false;\"";
if($BoardWWWChCk['host']==$User1WWWChCk['host']) {
	$opennew = null; }
$User1PostCount=sql_result($reresult,$rei,"PostCount");
$User1Karma=sql_result($reresult,$rei,"Karma");
$User1IP=sql_result($reresult,$rei,"IP");
sql_free_result($reresult);
if($User1Name=="Guest") { $User1Name=$GuestsName;
if($User1Name==null) { $User1Name="Guest"; } }
if(isset($GroupNamePrefix)&&$GroupNamePrefix!=null) {
	$User1Name = $GroupNamePrefix.$User1Name; }
if(isset($GroupNameSuffix)&&$GroupNameSuffix!=null) {
	$User1Name = $User1Name.$GroupNameSuffix; }
$MySubPost = null;
if($MyEditTime!=$MyTimeStamp&&$MyEditUserID!=0) {
if($MyEditUserID!=$MyUserID) {
$euquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($MyEditUserID));
$euresult = sql_query($euquery,$SQLStat);
$eunum = sql_num_rows($euresult);
if($eunum<1) { $MyEditUserID = -1;
$euquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($MyEditUserID));
$euresult = sql_query($euquery,$SQLStat);
$eunum = sql_num_rows($euresult); }
	$EditUserID = $MyEditUserID;
	$EditUserGroupID = sql_result($euresult,0,"GroupID");
	$EditUserHidden=sql_result($euresult,0,"HiddenMember");
	$EditUserName = sql_result($euresult,0,"Name");
	sql_free_result($euresult);
	$eugquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($EditUserGroupID));
	$eugresult=sql_query($eugquery,$SQLStat);
	$EditUserGroup=sql_result($eugresult,0,"Name");
	$EditUserNamePrefix=sql_result($eugresult,0,"NamePrefix");
	$EditUserNameSuffix=sql_result($eugresult,0,"NameSuffix");
	sql_free_result($eugresult);	}
	if($MyEditUserID==$MyUserID) {
	$EditUserID = $User1ID;
	$EditUserGroupID = $User1GroupID;
	//$EditUserHidden=$User1Hidden;
	$EditUserName = $User1Name;
	$EditUserGroup=$User1Group;
	$EditUserNamePrefix=null;
	$EditUserNameSuffix=null; }
	if($EditUserName=="Guest") { $EditUserName=$MyEditUserName;
	if($EditUserName==null) { $EditUserName="Guest"; } }
	if(isset($GroupNamePrefix)&&$GroupNamePrefix!=null) {
		$EditUserName = $EditUserNamePrefix.$EditUserName; }
	if(isset($GroupNameSuffix)&&$GroupNameSuffix!=null) {
		$EditUserName = $EditUserName.$EditUserNameSuffix; }
	$tmpusrcurtime = new DateTime();
	$tmpusrcurtime->setTimestamp($MyEditTime);
	$tmpusrcurtime->setTimezone($usertz);
	$MyEditTime = $tmpusrcurtime->format($_SESSION['iDBDateFormat'].", ".$_SESSION['iDBTimeFormat']);
	$MySubPost = "<div class=\"EditReply\"><br />This post has been edited by <b>".$EditUserName."</b> on ".$MyEditTime."</div>"; }
if($MyEditTime!=$MyTimeStamp&&$MyEditUserID!=0&&$MyEditUserID!=$MyUserID) {
$requery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($MyUserID));
$reresult=sql_query($requery,$SQLStat);
$renum=sql_num_rows($reresult);
$rei=0; $ipshow = "two";
$User1ID=$MyUserID; $GuestsName = $MyGuestName;
$User1Name=sql_result($reresult,$rei,"Name");
$User1IP=sql_result($reresult,$rei,"IP");
if($User1IP==$MyPostIP) { $ipshow = "one"; }
$User1Email=sql_result($reresult,$rei,"Email");
$User1Title=sql_result($reresult,$rei,"Title");
$User1Joined=sql_result($reresult,$rei,"Joined");
$tmpusrcurtime = new DateTime();
$tmpusrcurtime->setTimestamp($User1Joined);
$tmpusrcurtime->setTimezone($usertz);
$User1Joined=$tmpusrcurtime->format($_SESSION['iDBDateFormat']);
$User1Hidden=sql_result($reresult,$rei,"HiddenMember");
$User1GroupID=sql_result($reresult,$rei,"GroupID");
$gquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($User1GroupID));
$gresult=sql_query($gquery,$SQLStat);
$User1Group=sql_result($gresult,0,"Name");
$GroupNamePrefix=sql_result($gresult,0,"NamePrefix");
$GroupNameSuffix=sql_result($gresult,0,"NameSuffix");
sql_free_result($gresult); }
if($User1CanUseBBTags1=="yes") { $MyPost = bbcode_parser($MyPost); }
if($User1CanExecPHP=="no") {
$MyPost = preg_replace("/\[ExecPHP\](.*?)\[\/ExecPHP\]/is","<span style=\"color: red; font-weight: bold;\">ERROR:</span> cannot execute php code.",$MyPost); }
if($User1CanExecPHP=="yes") { $MyPost = php_execute($MyPost); }
if($User1CanDoHTML1=="no") {
$MyPost = preg_replace("/\[DoHTML\](.*?)\[\/DoHTML\]/is","<span style=\"color: red; font-weight: bold;\">ERROR:</span> cannot execute html.",$MyPost); }
if($User1CanDoHTML1=="yes") { $MyPost = do_html_bbcode($MyPost); }
$MyPost = text2icons($MyPost,$Settings['sqltable'],$SQLStat);
$MyPost = preg_replace("/\<br\>/", "<br />", nl2br($MyPost));
$MyPost = url2link($MyPost);
if($MySubPost!=null) { $MyPost = $MyPost."\n".$MySubPost; }
if($User1CanUseBBTags=="yes") { $User1Signature = bbcode_parser($User1Signature); }
if($User1CanExecPHP=="no") {
$User1Signature = preg_replace("/\[ExecPHP\](.*?)\[\/ExecPHP\]/is","<span style=\"color: red; font-weight: bold;\">ERROR:</span> cannot execute php code.",$User1Signature); }
if($User1CanExecPHP=="yes") { $User1Signature = php_execute($User1Signature); }
if($User1CanDoHTML1=="no") {
$User1Signature = preg_replace("/\[DoHTML\](.*?)\[\/DoHTML\]/is","<span style=\"color: red; font-weight: bold;\">ERROR:</span> cannot execute html.",$User1Signature); }
if($User1CanDoHTML=="yes") { $User1Signature = do_html_bbcode($User1Signature); }
$User1Signature = text2icons($User1Signature,$Settings['sqltable'],$SQLStat);
$User1Signature = preg_replace("/\<br\>/", "<br />", nl2br($User1Signature));
$User1Signature = url2link($User1Signature);
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
<div style="border:1px solid #E6E3E4; padding:1px; margin-bottom: 15px;" id="reply<?php echo $ReplyNum; ?>">
<div style="border: 1px solid #E6E3E4; padding:1px; margin-bottom: 15px; background-color: #E6E3E4; padding: 6px;" id="post<?php echo $MyPostID; ?>">
<div style="font-weight: bold; font-size: 0.8em; width: auto; float: left;"><?php echo $User1Name; ?></div>
<div style="width:auto; font-size: 0.8em; color: gray; text-align:right;"><?php echo $MyTimeStamp; ?></div>
</div>
<div style="padding: 6px; font-size: 0.8em;"><?php echo $MyPost; ?></div></div>
<?php ++$i; } sql_free_result($result); 
?></div><div>&#160;</div>
<div style="font-size: 11px; font-weight: bold; padding: 10px; border: 1px solid gray;">
<?php echo $pstring; ?></div>
<div>&#160;</div><?php } } ?>
