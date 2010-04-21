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

    $FileInfo: topics.php - Last Update: 04/20/2010 SVN 468 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="topics.php"||$File3Name=="/topics.php") {
	require('index.php');
	exit(); }
$pstring = null; $pagenum = null;
if(!is_numeric($_GET['id'])) { $_GET['id'] = null; }
if(!is_numeric($_GET['page'])) { $_GET['page'] = 1; }
$prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i LIMIT 1", array($_GET['id']));
$preresult=sql_query($prequery,$SQLStat);
$prenum=sql_num_rows($preresult);
if($prenum==0) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); sql_free_result($preresult);
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($prenum>=1) {
$ForumID=sql_result($preresult,0,"id");
$ForumCatID=sql_result($preresult,0,"CategoryID");
$ForumName=sql_result($preresult,0,"Name");
$ForumType=sql_result($preresult,0,"ForumType");
$InSubForum=sql_result($preresult,0,"InSubForum");
$RedirectURL=sql_result($preresult,0,"RedirectURL");
$RedirectTimes=sql_result($preresult,0,"Redirects");
$NumberViews=sql_result($preresult,0,"NumViews");
$NumberPosts=sql_result($preresult,0,"NumPosts");
$NumberTopics=sql_result($preresult,0,"NumTopics");
$PostCountAdd=sql_result($preresult,0,"PostCountAdd");
$CanHaveTopics=sql_result($preresult,0,"CanHaveTopics");
$HotTopicPosts=sql_result($preresult,0,"HotTopicPosts");
if($HotTopicPosts!=0&&is_numeric($HotTopicPosts)) {
	$Settings['hot_topic_num'] = $HotTopicPosts; }
if(!is_numeric($Settings['hot_topic_num'])) {
	$Settings['hot_topic_num'] = 15; }
$ForumPostCountView=sql_result($preresult,0,"PostCountView");
$ForumKarmaCountView=sql_result($preresult,0,"KarmaCountView");
sql_free_result($preresult);
$ForumType = strtolower($ForumType); $CanHaveTopics = strtolower($CanHaveTopics);
if($CanHaveTopics=="yes"&&$ForumType=="subforum") { 
if($_GET['act']=="create"||$_GET['act']=="maketopic"||
	$_POST['act']=="maketopics") { $ForumCheck = "skip"; } }
$catcheck = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i  LIMIT 1", array($ForumCatID));
$catresult=sql_query($catcheck,$SQLStat);
$CategoryName=sql_result($catresult,0,"Name");
$CategoryType=sql_result($catresult,0,"CategoryType");
$CategoryPostCountView=sql_result($catresult,0,"PostCountView");
$CategoryKarmaCountView=sql_result($catresult,0,"KarmaCountView");
sql_free_result($catresult);
if($GroupInfo['HasAdminCP']!="yes"||$GroupInfo['HasModCP']!="yes") {
if($MyPostCountChk==null) { $MyPostCountChk = 0; }
if($MyKarmaCount==null) { $MyKarmaCount = 0; }
if($ForumPostCountView!=0&&$MyPostCountChk<$ForumPostCountView) {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($CategoryPostCountView!=0&&$MyPostCountChk<$CategoryPostCountView) {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($ForumKarmaCountView!=0&&$MyKarmaCount<$ForumKarmaCountView) {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($CategoryKarmaCountView!=0&&$MyKarmaCount<$CategoryKarmaCountView) {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); } }
if($InSubForum!="0") {
$isfquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i LIMIT 1", array($InSubForum));
$isfresult=sql_query($isfquery,$SQLStat);
$isfnum=sql_num_rows($isfresult);
if($isfnum>=1) {
$isfForumID=sql_result($isfresult,0,"id");
$isfForumCatID=sql_result($isfresult,0,"CategoryID");
$isfForumName=sql_result($isfresult,0,"Name");
$isfForumType=sql_result($isfresult,0,"ForumType");
$isfForumType = strtolower($isfForumType);
$isfRedirectURL=sql_result($isfresult,0,"RedirectURL"); }
if($isfnum<1) { $InSubForum = "0"; } 
sql_free_result($isfresult); }
if($ForumCheck!="skip") {
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=view&id=".$ForumID."&page=".$_GET['page'],"&","=",$prexqstr[$ForumType],$exqstr[$ForumType]);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile[$ForumType].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile[$ForumType]; }
$_SESSION['PreViewingTitle'] = "Viewing Forum:";
$_SESSION['ViewingTitle'] = $ForumName;
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">Board index</a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$CategoryType],$Settings['file_ext'],"act=view&id=".$ForumCatID,$Settings['qstr'],$Settings['qsep'],$prexqstr[$CategoryType],$exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a><?php if($InSubForum!="0") { echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$isfForumType],$Settings['file_ext'],"act=view&id=".$isfForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$isfForumType],$exqstr[$isfForumType]); ?>"><?php echo $isfForumName; ?></a><?php } echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=view&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"><?php echo $ForumName; ?></a></div>
<div class="DivNavLinks">&nbsp;</div>
<?php }
if(!isset($CatPermissionInfo['CanViewCategory'][$ForumCatID])) {
	$CatPermissionInfo['CanViewCategory'][$ForumCatID] = "no"; }
if($CatPermissionInfo['CanViewCategory'][$ForumCatID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$ForumCatID]!="yes") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if(!isset($PermissionInfo['CanViewForum'][$ForumID])) {
	$PermissionInfo['CanViewForum'][$ForumID] = "no"; }
if($PermissionInfo['CanViewForum'][$ForumID]=="no"||
	$PermissionInfo['CanViewForum'][$ForumID]!="yes") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($CatPermissionInfo['CanViewCategory'][$ForumCatID]=="yes"&&
	$PermissionInfo['CanViewForum'][$ForumID]=="yes") {
if($ForumType!="redirect") {
if($NumberViews==0||$NumberViews==null) { $NewNumberViews = 1; }
if($NumberViews!=0&&$NumberViews!=null) { $NewNumberViews = $NumberViews + 1; }
$viewup = sql_pre_query("UPDATE \"".$Settings['sqltable']."forums\" SET \"NumViews\"=%i WHERE \"id\"=%i", array($NewNumberViews,$_GET['id']));
sql_query($viewup,$SQLStat); }
if($ForumType=="redirect") {
if($RedirectTimes==0||$RedirectTimes==null) { $NewRedirTime = 1; }
if($RedirectTimes!=0&&$RedirectTimes!=null) { $NewRedirTime = $RedirectTimes + 1; }
$redirup = sql_pre_query("UPDATE \"".$Settings['sqltable']."forums\" SET \"Redirects\"=%i WHERE \"id\"=%i", array($NewRedirTime,$_GET['id']));
sql_query($redirup,$SQLStat);
if($RedirectURL!="http://"&&$RedirectURL!="") {
redirect("location",$RedirectURL,0,null,false); ob_clean();
header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($RedirectURL=="http://"||$RedirectURL=="") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); } }
if($ForumCheck!="skip") {
if($ForumType=="subforum") {
redirect("location",$basedir.url_maker($exfile['subforum'],$Settings['file_ext'],"act=".$_GET['act']."&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['subforum'],$exqstr['subforum'],FALSE));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); } }
if($_GET['act']!="view") { ?>
<table style="width: 100%;" class="Table2">
<tr>
 <td style="width: 30%; text-align: left;"><?php echo $pstring; ?></td>
 <td style="width: 70%; text-align: right;">
 <?php if($PermissionInfo['CanMakeTopics'][$ForumID]=="yes"&&$CanHaveTopics=="yes") { ?>
 <a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=create&id=".$ForumID,$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ThemeSet['NewTopic']; ?></a>
 <?php } ?></td>
</tr>
</table>
<div class="DivTable2">&nbsp;</div>
<?php } if($_GET['act']=="view") {
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
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."topics\" WHERE (\"ForumID\"=%i) OR (\"OldForumID\"=%i) ORDER BY \"Pinned\" DESC, \"LastUpdate\" DESC ".$SQLimit, array($_GET['id'],$_GET['id'],$PageLimit,$Settings['max_topics']));
$result=sql_query($query,$SQLStat);
$num=sql_num_rows($result);
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
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile[$ForumType],$Settings['file_ext'],"act=view&id=".$_GET['id']."&page=".$Pback,$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType])."\">&lt;</a></span> "; }
if($Pagez[$pagei]!=null&&
   $Pagez[$pagei]!="First"&&
   $Pagez[$pagei]!="Last") {
if($pagei!=3) { 
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile[$ForumType],$Settings['file_ext'],"act=view&id=".$_GET['id']."&page=".$Pagez[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType])."\">".$Pagez[$pagei]."</a></span> "; }
if($pagei==3) { 
$pstring = $pstring."<span class=\"pagecurrent\"><a href=\"".url_maker($exfile[$ForumType],$Settings['file_ext'],"act=view&id=".$_GET['id']."&page=".$Pagez[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType])."\">".$Pagez[$pagei]."</a></span> "; } }
if($Pagez[$pagei]=="First") {
$pstring = $pstring."<span class=\"pagelinklast\"><a href=\"".url_maker($exfile[$ForumType],$Settings['file_ext'],"act=view&id=".$_GET['id']."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType])."\">&laquo;</a></span> "; }
if($Pagez[$pagei]=="Last") {
$ptestnext = $pagenext + 1;
$paget = $pagei - 1;
$Pnext = $_GET['page'] + 1;
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile[$ForumType],$Settings['file_ext'],"act=view&id=".$_GET['id']."&page=".$Pnext,$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType])."\">&gt;</a></span> ";
if($ptestnext<$pagenum) {
$pstring = $pstring."<span class=\"pagelinklast\"><a href=\"".url_maker($exfile[$ForumType],$Settings['file_ext'],"act=view&id=".$_GET['id']."&page=".$pagenum,$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType])."\">&raquo;</a></span> "; } }
	++$pagei; } $pstring = $pstring."</div>"; }
//List Page Number Code end
if($pstring!=null||$PermissionInfo['CanMakeTopics'][$ForumID]=="yes"&&$CanHaveTopics=="yes") {
?>
<table style="width: 100%;" class="Table2">
<tr>
 <td style="width: 30%; text-align: left;"><?php echo $pstring; ?></td>
 <td style="width: 70%; text-align: right;">
 <?php if($PermissionInfo['CanMakeTopics'][$ForumID]=="yes"&&$CanHaveTopics=="yes") { ?>
 <a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=create&id=".$ForumID,$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ThemeSet['NewTopic']; ?></a>
 <?php } ?></td>
</tr>
</table>
<?php
//List Page Number Code end
?>
<?php /*<div class="DivPageLinks">&nbsp;</div>*/?>
<div class="DivTable2">&nbsp;</div>
<?php } ?>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=view&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>#<?php echo $ForumID; ?>"><?php echo $ForumName; ?></a></span></div>
<?php } ?>
<table class="Table1" id="Forum<?php echo $ForumID; ?>">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr id="ForumStart<?php echo $ForumID; ?>" class="TableRow1">
<td class="TableColumn1" colspan="6"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=view&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>#<?php echo $ForumID; ?>"><?php echo $ForumName; ?></a></span>
</td>
</tr><?php } ?>
<tr id="TopicStatRow<?php echo $ForumID; ?>" class="TableRow2">
<th class="TableColumn2" style="width: 4%;">State</th>
<th class="TableColumn2" style="width: 36%;">Topic Name</th>
<th class="TableColumn2" style="width: 15%;">Author</th>
<th class="TableColumn2" style="width: 15%;">Time</th>
<th class="TableColumn2" style="width: 5%;">Replys</th>
<th class="TableColumn2" style="width: 25%;">Last Reply</th>
</tr>
<?php
while ($i < $num) {
$TopicID=sql_result($result,$i,"id");
$UsersID=sql_result($result,$i,"UserID");
$GuestsName=sql_result($result,$i,"GuestName");
$TheTime=sql_result($result,$i,"TimeStamp");
$TheTime=GMTimeChange("F j Y, g:i a",$TheTime,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$NumReply=sql_result($result,$i,"NumReply");
$NumberPosts=$NumReply + 1;
$prepagelist = null;
if(!isset($Settings['max_posts'])) { 
	$Settings['max_posts'] = 10; }
if(!isset($ThemeSet['MiniPageAltStyle'])) { 
	$ThemeSet['MiniPageAltStyle'] = "off"; }
if($ThemeSet['MiniPageAltStyle']!="on"&&
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
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">1</a>";
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; }
	if($ThemeSet['MiniPageAltStyle']=="off") { $prepagelist = $prepagelist." "; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<span class=\"minipagelink\">"; }
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=2",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">2</a>";
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; }
	if($NumberPages>=3) {
	if($ThemeSet['MiniPageAltStyle']=="off") { $prepagelist = $prepagelist." "; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<span class=\"minipagelink\">"; }
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=3",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">3</a>";
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; } }
	if($NumberPages==4) {
	if($ThemeSet['MiniPageAltStyle']=="off") { $prepagelist = $prepagelist." "; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<span class=\"minipagelinklast\">"; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=4",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">4</a>"; }
	if($ThemeSet['MiniPageAltStyle']=="off") {
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=4",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\"> ...4</a>"; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; } }
	if($NumberPages>4) {
	if($ThemeSet['MiniPageAltStyle']=="off") { $prepagelist = $prepagelist." "; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<span class=\"minipagelinklast\">"; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=".$NumberPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">&raquo; ".$NumberPages."</a>"; }
	if($ThemeSet['MiniPageAltStyle']=="off") {
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=".$NumberPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\"> ...".$NumberPages."</a>"; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; } }
	if($ThemeSet['MiniPageAltStyle']=="off") { 
	$prepagelist = $prepagelist.")</span>"; } }
$TopicName=sql_result($result,$i,"TopicName");
$TopicDescription=sql_result($result,$i,"Description");
$PinnedTopic=sql_result($result,$i,"Pinned");
$TopicStat=sql_result($result,$i,"Closed");
$requery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($UsersID));
$reresult=sql_query($requery,$SQLStat);
$renum=sql_num_rows($reresult);
if($renum<1) { $UsersID = -1;
$requery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($UsersID));
$reresult=sql_query($requery,$SQLStat);
$renum=sql_num_rows($reresult); }
$UserHidden=sql_result($reresult,0,"HiddenMember");
$UserGroupID=sql_result($reresult,0,"GroupID");
sql_free_result($reresult);
$gquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($UserGroupID));
$gresult=sql_query($gquery,$SQLStat);
$User1Group=sql_result($gresult,0,"Name");
$GroupNamePrefix=sql_result($gresult,0,"NamePrefix");
$GroupNameSuffix=sql_result($gresult,0,"NameSuffix");
sql_free_result($gresult);
$PreUsersName = GetUserName($UsersID,$Settings['sqltable'],$SQLStat);
if($PreUsersName['Name']===null) { $UsersID = -1;
$PreUsersName = GetUserName($UsersID,$Settings['sqltable'],$SQLStat); }
$UsersName = $PreUsersName['Name'];
$UsersHidden = $PreUsersName['Hidden'];
if($UsersName=="Guest") { $UsersName=$GuestsName;
if($UsersName==null) { $UsersName="Guest"; } }
if(isset($GroupNamePrefix)&&$GroupNamePrefix!=null) {
	$UsersName = $GroupNamePrefix.$UsersName; }
if(isset($GroupNameSuffix)&&$GroupNameSuffix!=null) {
	$UsersName = $UsersName.$GroupNameSuffix; }
$LastReply = "&nbsp;<br />&nbsp;";
$glrquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."posts\" WHERE \"TopicID\"=%i ORDER BY \"TimeStamp\" DESC LIMIT 1", array($TopicID));
$glrresult=sql_query($glrquery,$SQLStat);
$glrnum=sql_num_rows($glrresult);
if($glrnum>0){
$ReplyID1=sql_result($glrresult,0,"id");
$UsersID1=sql_result($glrresult,0,"UserID");
$PreUsersName1 = GetUserName($UsersID1,$Settings['sqltable'],$SQLStat);
if($PreUsersName1['Name']===null) { $UsersID1 = -1;
$PreUsersName1 = GetUserName($UsersID1,$Settings['sqltable'],$SQLStat); }
$UsersName1 = $PreUsersName1['Name'];
$UsersHidden1 = $PreUsersName1['Hidden'];
$GuestsName1=sql_result($glrresult,0,"GuestName");
$TimeStamp1=sql_result($glrresult,0,"TimeStamp");
$TimeStamp1=GMTimeChange("F j Y, g:i a",$TimeStamp1,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']); }
$NumPages = null; $NumRPosts = $NumReply + 1;
if(!isset($Settings['max_posts'])) { $Settings['max_posts'] = 10; }
if($NumRPosts>$Settings['max_posts']) {
$NumPages = ceil($NumRPosts/$Settings['max_posts']); }
if($NumRPosts<=$Settings['max_posts']) { $NumPages = 1; }
$Users_Name1 = pre_substr($UsersName1,0,20);
if($UsersName1=="Guest") { $UsersName1=$GuestsName1;
if($UsersName1==null) { $UsersName1="Guest"; } }
$oldusername=$UsersName1;
if (pre_strlen($UsersName1)>20) { 
$Users_Name1 = $Users_Name1."..."; $UsersName1=$Users_Name1; } $lul = null;
if($TimeStamp1!=null) { $lul = null;
if($UsersID1>0&&$UsersHidden1=="no") {
$lul = url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$UsersID1,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']);
$luln = url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=".$NumPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']).$qstrhtml."&#35;reply".$NumRPosts;
$LastReply = "<a href=\"".$luln."\">".$TimeStamp1."</a><br />\nUser: <a href=\"".$lul."\" title=\"".$oldusername."\">".$UsersName1."</a>"; }
if($UsersID1<=0||$UsersHidden1=="yes") {
if($UsersID1==-1) { $UserPre = "Guest:"; }
if(($UsersID1<-1&&$UsersHidden1=="yes")||$UsersID1==0||($UsersID1>0&&$UsersHidden1=="yes")) { 
	$UserPre = "Hidden:"; }
$lul = url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$UsersID1,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']);
$luln = url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=".$NumPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']).$qstrhtml."&#35;reply".$NumRPosts;
$LastReply = "<a href=\"".$luln."\">".$TimeStamp1."</a><br />\n".$UserPre." <span title=\"".$oldusername."\">".$UsersName1."</span>"; } }
sql_free_result($glrresult);
if(!isset($TimeStamp1)) { $TimeStamp1 = null; } if(!isset($LastReply)) { $LastReply = "&nbsp;<br />&nbsp;"; }
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
<td class="TableColumn3"><div class="topicstate">
<?php echo $PreTopic; ?></div></td>
<td class="TableColumn3"><div class="topicname">
<a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>"><?php echo $TopicName; ?></a>
<?php if($prepagelist!==null) { echo $prepagelist; } ?></div>
<div class="topicdescription"><?php echo $TopicDescription; ?></div></td>
<td class="TableColumn3" style="text-align: center;"><?php
if($UsersID>0&&$UserHidden=="no") {
echo "<a href=\"";
echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$UsersID,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']);
echo "\">".$UsersName."</a>"; }
if($UsersID<=0||$UserHidden=="yes") {
echo "<span>".$UsersName."</span>"; }
?></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $TheTime; ?></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $NumReply; ?></td>
<td class="TableColumn3"><?php echo $LastReply; ?></td>
</tr>
<?php ++$i; } 
?>
<tr id="ForumEnd<?php echo $ForumID; ?>" class="TableRow4">
<td class="TableColumn4" colspan="6">&nbsp;</td>
</tr>
</table></div>
<div class="DivTopics">&nbsp;</div>
<?php
sql_free_result($result); }
if((GMTimeStamp()<$_SESSION['LastPostTime']&&$_SESSION['LastPostTime']!=0)&&($_GET['act']=="create"||$_GET['act']=="maketopic")) { 
$_GET['act'] = "view"; $_POST['act'] = null; 
redirect("refresh",$basedir.url_maker($exfile['forum'],$Settings['file_ext'],"act=view&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum'],FALSE),"3"); ?>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=view&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ForumName; ?></a></span></div>
<?php } ?>
<table class="Table1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1">
<td class="TableColumn1"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=view&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ForumName; ?></a></span>
</td>
</tr><?php } ?>
<tr class="TableRow2">
<th class="TableColumn2" style="width: 100%; text-align: left;">&nbsp;Make Reply Message: </th>
</tr>
<tr class="TableRow3">
<td class="TableColumn3">
<table style="width: 100%; height: 25%; text-align: center;">
<tr>
	<td><span class="TableMessage"><br />
	You have to wait before making another topic.<br />
	Click <a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=view&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>">here</a> to go back to forum.<br />&nbsp;
	</span><br /></td>
</tr>
</table>
</td></tr>
<tr class="TableRow4">
<td class="TableColumn4">&nbsp;</td>
</tr>
</table></div>
<div class="DivMkReply">&nbsp;</div>
<?php } if($_GET['act']=="create") {
if($GroupInfo['HasAdminCP']!="yes"||$GroupInfo['HasModCP']!="yes") {
if($ForumPostCountView!=0&&$MyPostCountChk<$ForumPostCountView) {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($CategoryPostCountView!=0&&$MyPostCountChk<$CategoryPostCountView) {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($ForumKarmaCountView!=0&&$MyKarmaCount<$ForumKarmaCountView) {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($CategoryKarmaCountView!=0&&$MyKarmaCount<$CategoryKarmaCountView) {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); } }
if($PermissionInfo['CanMakeTopics'][$ForumID]=="no"||$CanHaveTopics=="no") { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
$UFID = rand_uuid("rand");
$_SESSION['UserFormID'] = $UFID;
?>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=view&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ForumName; ?></a></span></div>
<?php } ?>
<table class="Table1" id="MakeTopic<?php echo $ForumID; ?>">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1" id="TopicStart<?php echo $ForumID; ?>">
<td class="TableColumn1" colspan="2"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=view&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ForumName; ?></a></span>
</td>
</tr><?php } ?>
<tr id="MakeTopicRow<?php echo $ForumID; ?>" class="TableRow2">
<td class="TableColumn2" colspan="2" style="width: 100%;">Making a Topic in <?php echo $ForumName; ?></td>
</tr>
<tr class="TableRow3" id="MkTopic<?php echo $ForumID; ?>">
<td class="TableColumn3" style="width: 15%; vertical-align: middle; text-align: center;">
<div style="width: 100%; height: 160px; overflow: auto;">
<table style="width: 100%; text-align: center;"><?php
$renee_query=sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."smileys\" WHERE \"Display\"='yes'", array(null));
$renee_result=sql_query($renee_query,$SQLStat);
$renee_num=sql_num_rows($renee_result);
$renee_s=0; $SmileRow=0; $SmileCRow=0;
while ($renee_s < $renee_num) { ++$SmileRow;
$FileName=sql_result($renee_result,$renee_s,"FileName");
$SmileName=sql_result($renee_result,$renee_s,"SmileName");
$SmileText=sql_result($renee_result,$renee_s,"SmileText");
$SmileDirectory=sql_result($renee_result,$renee_s,"Directory");
$ShowSmile=sql_result($renee_result,$renee_s,"Display");
$ReplaceType=sql_result($renee_result,$renee_s,"ReplaceCI");
if($SmileRow==1) { ?><tr>
	<?php } if($SmileRow<5) { ++$SmileCRow; ?>
	<td><img src="<?php echo $SmileDirectory."".$FileName; ?>" style="vertical-align: middle; border: 0px; cursor: pointer;" title="<?php echo $SmileName; ?>" alt="<?php echo $SmileName; ?>" onclick="addsmiley('TopicPost','&nbsp;<?php echo htmlspecialchars($SmileText, ENT_QUOTES, $Settings['charset']); ?>&nbsp;')" /></td>
	<?php } if($SmileRow==5) { ++$SmileCRow; ?>
	<td><img src="<?php echo $SmileDirectory."".$FileName; ?>" style="vertical-align: middle; border: 0px; cursor: pointer;" title="<?php echo $SmileName; ?>" alt="<?php echo $SmileName; ?>" onclick="addsmiley('TopicPost','&nbsp;<?php echo htmlspecialchars($SmileText, ENT_QUOTES, $Settings['charset']); ?>&nbsp;')" /></td></tr>
	<?php $SmileCRow=0; $SmileRow=0; }
++$renee_s; }
if($SmileCRow<5&&$SmileCRow!=0) {
$SmileCRowL = 5 - $SmileCRow;
echo "<td colspan=\"".$SmileCRowL."\">&nbsp;</td></tr>"; }
echo "</table>";
sql_free_result($renee_result);
?></div></td>
<td class="TableColumn3" style="width: 85%;">
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
<?php } ?></tr><?php } ?><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="TopicDesc">Insert Topic Description:</label></td>
	<td style="width: 50%;"><input maxlength="45" type="text" name="TopicDesc" class="TextBox" id="TopicDesc" size="20" /></td>
</tr>
</table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<label class="TextBoxLabel" for="TopicPost">Insert Your Post:</label><br />
<textarea rows="10" name="TopicPost" id="TopicPost" cols="40" class="TextBox"></textarea><br />
<?php if($_SESSION['UserGroup']==$Settings['GuestGroup']&&$Settings['captcha_guest']=="on") { ?>
<label class="TextBoxLabel" for="signcode"><img src="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=MkCaptcha",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>" alt="CAPTCHA Code" title="CAPTCHA Code" /></label><br />
<input maxlength="25" type="text" class="TextBox" name="signcode" size="20" id="signcode" value="Enter SignCode" /><br />
<?php } ?>
<input type="hidden" name="act" value="maketopics" style="display: none;" />
<input type="hidden" style="display: none;" name="fid" value="<?php echo $UFID; ?>" />
<?php if($_SESSION['UserGroup']!=$Settings['GuestGroup']) { ?>
<input type="hidden" name="GuestName" value="null" style="display: none;" />
<?php } ?>
<input type="submit" class="Button" value="Make Topic" name="make_topic" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form></td></tr>
<tr id="MkTopicEnd<?php echo $ForumID; ?>" class="TableRow4">
<td class="TableColumn4" colspan="2">&nbsp;</td>
</tr>
</table></div>
<div class="DivMkTopics">&nbsp;</div>
<?php } if($_GET['act']=="maketopic"&&$_POST['act']=="maketopics") {
if($_POST['TopicDesc']==""&&$_POST['TopicName']!="") {
	$_POST['TopicDesc'] = $_POST['TopicName']; }
if($_POST['TopicDesc']!=""&&$_POST['TopicName']=="") {
	$_POST['TopicName'] = $_POST['TopicDesc']; }
if($PermissionInfo['CanMakeTopics'][$ForumID]=="no"||$CanHaveTopics=="no") { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
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
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=view&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ForumName; ?></a></span></div>
<?php } ?>
<table class="Table1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1">
<td class="TableColumn1"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=view&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ForumName; ?></a></span>
</td>
</tr><?php } ?>
<tr class="TableRow2">
<th class="TableColumn2" style="width: 100%; text-align: left;">&nbsp;Make Topic Message: </th>
</tr>
<tr class="TableRow3">
<td class="TableColumn3">
<table style="width: 100%; height: 25%; text-align: center;">
<?php if (pre_strlen($_POST['TopicName'])>"50") { $Error="Yes";  ?>
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
<?php } } if (pre_strlen($_POST['TopicDesc'])>"80") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your Topic Description is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if($_POST['fid']!=$_SESSION['UserFormID']) { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Sorry the referering url dose not match our host name.<br />
	</span>&nbsp;</td>
</tr>
<?php } if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	pre_strlen($_POST['GuestName'])>"30") { $Error="Yes"; ?>
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
$_POST['TopicName'] = remove_spaces($_POST['TopicName']);
$_POST['TopicDesc'] = stripcslashes(htmlspecialchars($_POST['TopicDesc'], ENT_QUOTES, $Settings['charset']));
//$_POST['TopicDesc'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['TopicDesc']);
$_POST['TopicDesc'] = remove_spaces($_POST['TopicDesc']);
$_POST['GuestName'] = stripcslashes(htmlspecialchars($_POST['GuestName'], ENT_QUOTES, $Settings['charset']));
//$_POST['GuestName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['GuestName']);
$_POST['GuestName'] = remove_spaces($_POST['GuestName']);
$_POST['TopicPost'] = stripcslashes(htmlspecialchars($_POST['TopicPost'], ENT_QUOTES, $Settings['charset']));
//$_POST['TopicPost'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['TopicPost']);
$_POST['TopicPost'] = remove_bad_entities($_POST['TopicPost']);
//$_POST['TopicPost'] = remove_spaces($_POST['TopicPost']);
if($_SESSION['UserGroup']==$Settings['GuestGroup']) {
if(isset($_POST['GuestName'])&&$_POST['GuestName']!=null) {
if($cookieDomain==null) {
setcookie("GuestName", $_POST['GuestName'], time() + (7 * 86400), $cbasedir); }
if($cookieDomain!=null) {
if($cookieSecure===true) {
setcookie("GuestName", $_POST['GuestName'], time() + (7 * 86400), $cbasedir, $cookieDomain, 1); }
if($cookieSecure===false) {
setcookie("GuestName", $_POST['GuestName'], time() + (7 * 86400), $cbasedir, $cookieDomain); } }
$_SESSION['GuestName']=$_POST['GuestName']; } }
/*    <_<  iWordFilter  >_>      
   by Kazuki Przyborowski - Cool Dude 2k */
$katarzynaqy=sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."wordfilter\"", array(null));
$katarzynart=sql_query($katarzynaqy,$SQLStat);
$katarzynanm=sql_num_rows($katarzynart);
$katarzynas=0;
while ($katarzynas < $katarzynanm) {
$Filter=sql_result($katarzynart,$katarzynas,"FilterWord");
$Replace=sql_result($katarzynart,$katarzynas,"Replacement");
$CaseInsensitive=sql_result($katarzynart,$katarzynas,"CaseInsensitive");
if($CaseInsensitive=="on") { $CaseInsensitive = "yes"; }
if($CaseInsensitive=="off") { $CaseInsensitive = "no"; }
if($CaseInsensitive!="yes"||$CaseInsensitive!="no") { $CaseInsensitive = "no"; }
$WholeWord=sql_result($katarzynart,$katarzynas,"WholeWord");
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
++$katarzynas; } sql_free_result($katarzynart);
$lonewolfqy=sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."restrictedwords\" WHERE \"RestrictedTopicName\"='yes' or \"RestrictedUserName\"='yes'", array(null));
$lonewolfrt=sql_query($lonewolfqy,$SQLStat);
$lonewolfnm=sql_num_rows($lonewolfrt);
$lonewolfs=0; $RMatches = null; $RGMatches = null;
while ($lonewolfs < $lonewolfnm) {
$RWord=sql_result($lonewolfrt,$lonewolfs,"Word");
$RCaseInsensitive=sql_result($lonewolfrt,$lonewolfs,"CaseInsensitive");
if($RCaseInsensitive=="on") { $RCaseInsensitive = "yes"; }
if($RCaseInsensitive=="off") { $RCaseInsensitive = "no"; }
if($RCaseInsensitive!="yes"||$RCaseInsensitive!="no") { $RCaseInsensitive = "no"; }
$RWholeWord=sql_result($lonewolfrt,$lonewolfs,"WholeWord");
if($RWholeWord=="on") { $RWholeWord = "yes"; }
if($RWholeWord=="off") { $RWholeWord = "no"; }
if($RWholeWord!="yes"||$RWholeWord!="no") { $RWholeWord = "no"; }
$RestrictedTopicName=sql_result($lonewolfrt,$lonewolfs,"RestrictedTopicName");
if($RestrictedTopicName=="on") { $RestrictedTopicName = "yes"; }
if($RestrictedTopicName=="off") { $RestrictedTopicName = "no"; }
if($RestrictedTopicName!="yes"||$RestrictedTopicName!="no") { $RestrictedTopicName = "no"; }
$RestrictedUserName=sql_result($lonewolfrt,$lonewolfs,"RestrictedUserName");
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
++$lonewolfs; } sql_free_result($lonewolfrt);
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
redirect("refresh",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false),"4"); ?>
<tr>
	<td><span class="TableMessage">
	<br />Click <a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">here</a> to goto index page.<br />&nbsp;
	</span><br /></td>
</tr>
<?php } if ($Error!="Yes") { $LastActive = GMTimeStamp();
$requery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($MyUserID));
$reresult=sql_query($requery,$SQLStat);
$renum=sql_num_rows($reresult);
$rei=0;
while ($rei < $renum) {
$User1ID=$MyUserID;
$User1Name=sql_result($reresult,$rei,"Name");
if($_SESSION['UserGroup']==$Settings['GuestGroup']) { $User1Name = $_POST['GuestName']; }
$User1Email=sql_result($reresult,$rei,"Email");
$User1Title=sql_result($reresult,$rei,"Title");
$User1GroupID=sql_result($reresult,$rei,"GroupID");
$PostCount=sql_result($reresult,$rei,"PostCount");
if($PostCountAdd=="on") { $NewPostCount = $PostCount + 1; }
if(!isset($NewPostCount)) { $NewPostCount = $PostCount; }
$gquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($User1GroupID));
$gresult=sql_query($gquery,$SQLStat);
$User1Group=sql_result($gresult,0,"Name");
sql_free_result($gresult);
$User1IP=$_SERVER['REMOTE_ADDR'];
++$rei; } sql_free_result($reresult);
$query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."topics\" (\"ForumID\", \"CategoryID\", \"OldForumID\", \"OldCategoryID\", \"UserID\", \"GuestName\", \"TimeStamp\", \"LastUpdate\", \"TopicName\", \"Description\", \"NumReply\", \"NumViews\", \"Pinned\", \"Closed\") VALUES\n".
"(%i, %i, %i, %i, %i, '%s', %i, %i, '%s', '%s', 0, 0, 0, 0)", array($ForumID,$ForumCatID,$ForumID,$ForumCatID,$User1ID,$User1Name,$LastActive,$LastActive,$_POST['TopicName'],$_POST['TopicDesc']));
sql_query($query,$SQLStat);
$topicid = sql_get_next_id($Settings['sqltable'],"topics",$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."posts\" (\"TopicID\", \"ForumID\", \"CategoryID\", \"UserID\", \"GuestName\", \"TimeStamp\", \"LastUpdate\", \"EditUser\", \"EditUserName\", \"Post\", \"Description\", \"IP\", \"EditIP\") VALUES\n".
"(".$topicid.", %i, %i, %i, '%s', %i, %i, 0, '', '%s', '%s', '%s', '0')", array($ForumID,$ForumCatID,$User1ID,$User1Name,$LastActive,$LastActive,$_POST['TopicPost'],$_POST['TopicDesc'],$User1IP));
sql_query($query,$SQLStat);
$postid = sql_get_next_id($Settings['sqltable'],"posts",$SQLStat);
$_SESSION['LastPostTime'] = GMTimeStamp() + $GroupInfo['FloodControl'];
if($User1ID!=0&&$User1ID!=-1) {
$queryupd = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"LastActive\"=%i,\"IP\"='%s',\"PostCount\"=%i,\"LastPostTime\"=%i WHERE \"id\"=%i", array($LastActive,$User1IP,$NewPostCount,$_SESSION['LastPostTime'],$User1ID));
sql_query($queryupd,$SQLStat); }
$NewNumPosts = $NumberPosts + 1; $NewNumTopics = $NumberTopics + 1;
$queryupd = sql_pre_query("UPDATE \"".$Settings['sqltable']."forums\" SET \"NumPosts\"=%i,\"NumTopics\"=%i WHERE \"id\"=%i", array($NewNumPosts,$NewNumTopics,$ForumID));
sql_query($queryupd,$SQLStat);
redirect("refresh",$basedir.url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$topicid."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'],FALSE),"3");
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
<td class="TableColumn4">&nbsp;</td>
</tr>
</table></div>
<div class="DivMkTopics">&nbsp;</div>
<?php } ?>
<table style="width: 100%;" class="Table2">
<tr>
 <td style="width: 30%; text-align: left;"><?php echo $pstring; ?></td>
 <td style="width: 70%; text-align: right;">
 <?php if($PermissionInfo['CanMakeTopics'][$ForumID]=="yes"&&$CanHaveTopics=="yes") { ?>
 <a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=create&id=".$ForumID,$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ThemeSet['NewTopic']; ?></a>
 <?php } ?></td>
</tr>
</table>
<?php
//List Page Number Code end
if($pstring!=null||$_GET['act']!="view"||
	$PermissionInfo['CanMakeTopics'][$ForumID]=="yes"&&$CanHaveTopics=="yes") {
?>
<?php /*<div class="DivPageLinks">&nbsp;</div>*/ ?>
<div class="DivTable2">&nbsp;</div>
<?php } } } ?>
