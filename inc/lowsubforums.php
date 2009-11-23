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

    $FileInfo: lowsubforums.php - Last Update: 11/23/2009 SVN 359 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="lowsubforums.php"||$File3Name=="/lowsubforums.php") {
	require('index.php');
	exit(); }
if(!is_numeric($_GET['id'])) { $_GET['id'] = null; }
$checkquery = sql_pre_query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `id`=%i LIMIT 1", array($_GET['id']));
$checkresult=sql_query($checkquery);
$checknum=sql_num_rows($checkresult);
if($checknum==0) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); sql_free_result($checkresult);
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($checknum>=1) {
$ForumID=sql_result($checkresult,0,"id");
$ForumName=sql_result($checkresult,0,"Name");
$ForumType=sql_result($checkresult,0,"ForumType");
$InSubForum=sql_result($checkresult,0,"InSubForum");
$CategoryID=sql_result($checkresult,0,"CategoryID");
$RedirectURL=sql_result($checkresult,0,"RedirectURL");
$RedirectTimes=sql_result($checkresult,0,"Redirects");
$CanHaveTopics=sql_result($checkresult,0,"CanHaveTopics");
$NumberViews=sql_result($checkresult,0,"NumViews");
$SForumName = $ForumName;
$ForumType = strtolower($ForumType); $CanHaveTopics = strtolower($CanHaveTopics);
if($CanHaveTopics!="yes"&&$ForumType!="redirect") {
if($NumberViews==0||$NumberViews==null) { $NewNumberViews = 1; }
if($NumberViews!=0&&$NumberViews!=null) { $NewNumberViews = $NumberViews + 1; }
$viewup = sql_pre_query("UPDATE `".$Settings['sqltable']."forums` SET `NumViews`='%s' WHERE `id`=%i", array($NewNumberViews,$_GET['id']));
sql_query($viewup); }
if($ForumType=="redirect") {
if($RedirectTimes==0||$RedirectTimes==null) { $NewRedirTime = 1; }
if($RedirectTimes!=0&&$RedirectTimes!=null) { $NewRedirTime = $RedirectTimes + 1; }
$redirup = sql_pre_query("UPDATE `".$Settings['sqltable']."forums` SET `Redirects`='%s' WHERE `id`=%i", array($NewRedirTime,$_GET['id']));
sql_query($redirup);
if($RedirectURL!="http://"&&$RedirectURL!="") {
redirect("location",$RedirectURL,0,null,false); ob_clean();
header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($RedirectURL=="http://"||$RedirectURL=="") {
redirect("location",url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); } }
if($ForumType=="forum") {
redirect("location",$basedir.url_maker($exfile['forum'],$Settings['file_ext'],"act=".$_GET['act']."&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum'],FALSE));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
sql_free_result($checkresult);
$prequery = sql_pre_query("SELECT * FROM `".$Settings['sqltable']."categories` WHERE `ShowCategory`='yes' AND `id`=%i ORDER BY `OrderID` ASC, `id` ASC", array($CategoryID));
$preresult=sql_query($prequery);
$prenum=sql_num_rows($preresult);
$prei=0;
$CategoryID=sql_result($preresult,0,"id");
$CategoryType=sql_result($preresult,0,"CategoryType");
$CategoryName=sql_result($preresult,0,"Name");
$CategoryShow=sql_result($preresult,0,"ShowCategory");
$CategoryDescription=sql_result($preresult,0,"Description");
if($InSubForum!="0") {
$isfquery = sql_pre_query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `id`=%i LIMIT 1", array($InSubForum));
$isfresult=sql_query($isfquery);
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
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=lowview&id=".$ForumID."&page=".$_GET['page'],"&","=",$prexqstr[$ForumType],$exqstr[$ForumType]);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile[$ForumType].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile[$ForumType]; }
$_SESSION['PreViewingTitle'] = "Viewing SubForum:";
$_SESSION['ViewingTitle'] = $ForumName;
?>
<div style="font-size: 1.0em; font-weight: bold; margin-bottom: 10px; padding-top: 3px; width: auto;">Full Version: <a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=view&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"><?php echo $ForumName; ?></a></div>
<div style="font-size: 11px; font-weight: bold; padding: 10px; border: 1px solid gray;"><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">Board index</a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$CategoryType],$Settings['file_ext'],"act=lowview&id=".$CategoryID,$Settings['qstr'],$Settings['qsep'],$prexqstr[$CategoryType],$exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a><?php if($InSubForum!="0") { echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$isfForumType],$Settings['file_ext'],"act=view&id=".$isfForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$isfForumType],$exqstr[$isfForumType]); ?>"><?php echo $isfForumName; ?></a><?php } echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=lowview&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"><?php echo $ForumName; ?></a></div>
<div>&nbsp;</div>
<div style="padding: 10px; border: 1px solid gray;">
<ul style="list-style-type: none;">
<?php
if(!isset($CatPermissionInfo['CanViewCategory'][$CategoryID])) {
	$CatPermissionInfo['CanViewCategory'][$CategoryID] = "no"; }
if($CatPermissionInfo['CanViewCategory'][$CategoryID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$CategoryID]!="yes") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if(!isset($PermissionInfo['CanViewForum'][$_GET['id']])) {
	$PermissionInfo['CanViewForum'][$_GET['id']] = "no"; }
if($PermissionInfo['CanViewForum'][$_GET['id']]=="no"||
	$PermissionInfo['CanViewForum'][$_GET['id']]!="yes") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($CatPermissionInfo['CanViewCategory'][$CategoryID]=="yes"&&
	$PermissionInfo['CanViewForum'][$_GET['id']]=="yes") {
$query = sql_pre_query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `ShowForum`='yes' AND `CategoryID`=%i AND `InSubForum`=%i ORDER BY `OrderID` ASC, `id` ASC", array($CategoryID,$_GET['id']));
$result=sql_query($query);
$num=sql_num_rows($result);
$i=0;
?>
<li style="font-weight: bold;"><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=lowview&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"><?php echo $ForumName; ?></a></li><li>
<?php
while ($i < $num) {
$ForumID=sql_result($result,$i,"id");
$ForumName=sql_result($result,$i,"Name");
$ForumShow=sql_result($result,$i,"ShowForum");
$ForumType=sql_result($result,$i,"ForumType");
$ForumShowTopics=sql_result($result,$i,"CanHaveTopics");
$ForumShowTopics = strtolower($ForumShowTopics);
$NumTopics=sql_result($result,$i,"NumTopics");
$NumPosts=sql_result($result,$i,"NumPosts");
$NumRedirects=sql_result($result,$i,"Redirects");
$ForumDescription=sql_result($result,$i,"Description");
$ForumType = strtolower($ForumType); $sflist = null;
$gltf = array(null); $gltf[0] = $ForumID;
if ($ForumType=="subforum") { 
$apcquery = sql_pre_query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `ShowForum`='yes' AND `InSubForum`=%i ORDER BY `OrderID` ASC, `id` ASC", array($ForumID));
$apcresult=sql_query($apcquery);
$apcnum=sql_num_rows($apcresult);
$apci=0; $apcl=1; if($apcnum>=1) {
while ($apci < $apcnum) {
$NumsTopics=sql_result($apcresult,$apci,"NumTopics");
$NumTopics = $NumsTopics + $NumTopics;
$NumsPosts=sql_result($apcresult,$apci,"NumPosts");
$NumPosts = $NumsPosts + $NumPosts;
$SubsForumID=sql_result($apcresult,$apci,"id");
$SubsForumName=sql_result($apcresult,$apci,"Name");
$SubsForumType=sql_result($apcresult,$apci,"ForumType");
if(isset($PermissionInfo['CanViewForum'][$SubsForumID])&&
	$PermissionInfo['CanViewForum'][$SubsForumID]=="yes") {
$shownum = null;
if ($SubsForumType=="redirect") { $shownum = "(".$NumRedirects." redirects)"; }
if ($SubsForumType!="redirect") { $shownum = "(".$NumPosts." posts)"; }
$sfurl = "<a href=\"";
$sfurl = url_maker($exfile[$SubsForumType],$Settings['file_ext'],"act=lowview&id=".$SubsForumID.$ExStr,$Settings['qstr'],$Settings['qsep'],$prexqstr[$SubsForumType],$exqstr[$SubsForumType]);
$sfurl = "<li><ul style=\"list-style-type: none;\"><li><a href=\"".$sfurl."\">".$SubsForumName."</a> <span style=\"color: gray; font-size: 10px;\">".$shownum."</span></li></ul></li>";
if($apcl==1) {
$sflist = "Subforums:";
$sflist = $sflist." ".$sfurl; }
if($apcl>1) {
$sflist = $sflist." ".$sfurl; }
$gltf[$apcl] = $SubsForumID; ++$apcl; }
++$apci; }
sql_free_result($apcresult); } }
$shownum = null;
if ($ForumType=="redirect") { $shownum = "(".$NumRedirects." redirects)"; }
if ($ForumType!="redirect") { $shownum = "(".$NumPosts." posts)"; }
$PreForum = $ThemeSet['ForumIcon'];
if ($ForumType=="forum") { $PreForum=$ThemeSet['ForumIcon']; }
if ($ForumType=="subforum") { $PreForum=$ThemeSet['SubForumIcon']; }
if ($ForumType=="redirect") { $PreForum=$ThemeSet['RedirectIcon']; }
$ExStr = ""; if ($ForumType!="redirect"&&
	$ForumShowTopics!="no") { $ExStr = "&page=1"; }
?>
<ul style="list-style-type: none;"><li>
<a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=lowview&id=".$ForumID.$ExStr,$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"<?php if($ForumType=="redirect") { echo " onclick=\"window.open(this.href);return false;\""; } ?>><?php echo $ForumName; ?> <span style="color: gray; font-size: 10px;"><?php echo $shownum; ?></span></a></li>
<?php echo $sflist; ?></ul>
<?php ++$i; } sql_free_result($result);
?>
</li></ul></div>
<div>&nbsp;</div>
<?php } sql_free_result($preresult);
$ForumCheck = "skip";
if($CanHaveTopics!="yes") { 
	$ForumName = $SForumName; }
if($CanHaveTopics!="no") {
require($SettDir['inc'].'topics.php'); } }
?>
