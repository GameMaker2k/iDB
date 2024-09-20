<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2024 iDB Support - https://idb.osdn.jp/support/category.php?act=view&id=1
    Copyright 2004-2024 Game Maker 2k - https://idb.osdn.jp/support/category.php?act=view&id=2

    $FileInfo: lowsubforums.php - Last Update: 8/26/2024 SVN 1048 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="lowsubforums.php"||$File3Name=="/lowsubforums.php") {
	require('index.php');
	exit(); }
if(!is_numeric($_GET['id'])) { $_GET['id'] = null; }
$checkquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i".$ForumIgnoreList2." LIMIT 1", array($_GET['id']));
$checkresult=sql_query($checkquery,$SQLStat);
$checknum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i".$ForumIgnoreList2." LIMIT 1", array($_GET['id'])), $SQLStat);
if($checknum==0) { redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); sql_free_result($checkresult);
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($checknum>=1) {
$checkresult_array = sql_fetch_assoc($checkresult);
$ForumID=$checkresult_array["id"];
$ForumName=$checkresult_array["Name"];
$ForumType=$checkresult_array["ForumType"];
$ForumShow=$checkresult_array["ShowForum"];
if($ForumShow=="no") { $_SESSION['ShowActHidden'] = "yes"; }
$InSubForum=$checkresult_array["InSubForum"];
$CategoryID=$checkresult_array["CategoryID"];
$RedirectURL=$checkresult_array["RedirectURL"];
$RedirectTimes=$checkresult_array["Redirects"];
$CanHaveTopics=$checkresult_array["CanHaveTopics"];
$NumberViews=$checkresult_array["NumViews"];
$SForumName = $ForumName;
$ForumType = strtolower($ForumType); $CanHaveTopics = strtolower($CanHaveTopics);
if($CanHaveTopics!="yes"&&$ForumType!="redirect") {
if($NumberViews==0||$NumberViews==null) { $NewNumberViews = 1; }
if($NumberViews!=0&&$NumberViews!=null) { $NewNumberViews = $NumberViews + 1; }
$viewup = sql_pre_query("UPDATE \"".$Settings['sqltable']."forums\" SET \"NumViews\"='%s' WHERE \"id\"=%i", array($NewNumberViews,$_GET['id']));
sql_query($viewup,$SQLStat); }
if($ForumType=="redirect") {
if($RedirectTimes==0||$RedirectTimes==null) { $NewRedirTime = 1; }
if($RedirectTimes!=0&&$RedirectTimes!=null) { $NewRedirTime = $RedirectTimes + 1; }
$redirup = sql_pre_query("UPDATE \"".$Settings['sqltable']."forums\" SET \"Redirects\"='%s' WHERE \"id\"=%i", array($NewRedirTime,$_GET['id']));
sql_query($redirup,$SQLStat);
if($RedirectURL!="http://"&&$RedirectURL!="") {
redirect("location",$RedirectURL,0,null,false); ob_clean();
header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($RedirectURL=="http://"||$RedirectURL=="") {
redirect("location",url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); } }
if($ForumType=="forum") {
redirect("location",$rbasedir.url_maker($exfile['forum'],$Settings['file_ext'],"act=".$_GET['act']."&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum'],FALSE));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
sql_free_result($checkresult);
$prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" WHERE \"ShowCategory\"='yes' AND \"id\"=%i".$CatIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($CategoryID));
$preresult=sql_query($prequery,$SQLStat);
$prenum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."categories\" WHERE \"ShowCategory\"='yes' AND \"id\"=%i".$CatIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($CategoryID)), $SQLStat);
$preresult_array = sql_fetch_assoc($preresult);
$CategoryID=$preresult_array["id"];
$CategoryName=$preresult_array["Name"];
$CategoryShow=$preresult_array["ShowCategory"];
$CategoryType=$preresult_array["CategoryType"];
$InSubCategory=$preresult_array["InSubCategory"];
if($CategoryShow=="no") { $_SESSION['ShowActHidden'] = "yes"; }
$CategoryDescription=$preresult_array["Description"];
if($InSubForum!="0") {
$isfquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i".$ForumIgnoreList2." LIMIT 1", array($InSubForum));
$isfresult=sql_query($isfquery,$SQLStat);
$isfnum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i".$ForumIgnoreList2." LIMIT 1", array($InSubForum)), $SQLStat);
if($isfnum>=1) {
$isfresult_array = sql_fetch_assoc($isfresult);
$isfForumID=$isfresult_array["id"];
$isfForumCatID=$isfresult_array["CategoryID"];
$isfForumName=$isfresult_array["Name"];
$isfForumType=$isfresult_array["ForumType"];
$isfForumType = strtolower($isfForumType);
$isfRedirectURL=$isfresult_array["RedirectURL"]; }
if($isfnum<1) { $InSubForum = "0"; } 
sql_free_result($isfresult); }
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
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=lowview&id=".$ForumID."&page=".$_GET['page'],"&","=",$prexqstr[$ForumType],$exqstr[$ForumType]);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile[$ForumType].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile[$ForumType]; }
$_SESSION['PreViewingTitle'] = "Viewing SubForum:";
$_SESSION['ViewingTitle'] = $ForumName;
$_SESSION['ExtraData'] = "currentact:".$_GET['act']."; currentcategoryid:".$InSubCategory.",".$CategoryID."; currentforumid:".$InSubForum.",".$ForumID."; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;"; 
?>
<div style="font-size: 1.0em; font-weight: bold; margin-bottom: 10px; padding-top: 3px; width: auto;">Full Version: <a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=view&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"><?php echo $ForumName; ?></a></div>
<div style="font-size: 11px; font-weight: bold; padding: 10px; border: 1px solid gray;"><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$CategoryType],$Settings['file_ext'],"act=lowview&id=".$CategoryID,$Settings['qstr'],$Settings['qsep'],$prexqstr[$CategoryType],$exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a><?php if($InSubForum!="0") { echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$isfForumType],$Settings['file_ext'],"act=lowview&id=".$isfForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$isfForumType],$exqstr[$isfForumType]); ?>"><?php echo $isfForumName; ?></a><?php } echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=lowview&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"><?php echo $ForumName; ?></a></div>
<div>&#160;</div>
<?php
if(!isset($CatPermissionInfo['CanViewCategory'][$CategoryID])) {
	$CatPermissionInfo['CanViewCategory'][$CategoryID] = "no"; }
if($CatPermissionInfo['CanViewCategory'][$CategoryID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$CategoryID]!="yes") {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if(!isset($PermissionInfo['CanViewForum'][$_GET['id']])) {
	$PermissionInfo['CanViewForum'][$_GET['id']] = "no"; }
if($PermissionInfo['CanViewForum'][$_GET['id']]=="no"||
	$PermissionInfo['CanViewForum'][$_GET['id']]!="yes") {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($CatPermissionInfo['CanViewCategory'][$CategoryID]=="yes"&&
	$PermissionInfo['CanViewForum'][$_GET['id']]=="yes") {
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"ShowForum\"='yes' AND \"CategoryID\"=%i AND \"InSubForum\"=%i".$ForumIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($CategoryID,$_GET['id']));
$result=sql_query($query,$SQLStat);
$num=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."forums\" WHERE \"ShowForum\"='yes' AND \"CategoryID\"=%i AND \"InSubForum\"=%i".$ForumIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($CategoryID,$_GET['id'])), $SQLStat);
$i=0;
if($num>=1) {
?>
<div style="padding: 10px; border: 1px solid gray;">
<ul style="list-style-type: none;">
<li style="font-weight: bold;"><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=lowview&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"><?php echo $ForumName; ?></a></li><li>
<?php
while ($i < $num) {
$result_array = sql_fetch_assoc($result);
$ForumID=$result_array["id"];
$ForumName=$result_array["Name"];
$ForumShow=$result_array["ShowForum"];
$ForumType=$result_array["ForumType"];
$ForumShowTopics=$result_array["CanHaveTopics"];
$ForumShowTopics = strtolower($ForumShowTopics);
$NumTopics=$result_array["NumTopics"];
$NumPosts=$result_array["NumPosts"];
$NumRedirects=$result_array["Redirects"];
$ForumDescription=$result_array["Description"];
$ForumType = strtolower($ForumType); $sflist = null;
$gltf = array(null); $gltf[0] = $ForumID;
if ($ForumType=="subforum") { 
$apcquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"ShowForum\"='yes' AND \"InSubForum\"=%i".$ForumIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($ForumID));
$apcresult=sql_query($apcquery);
$apcnum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."forums\" WHERE \"ShowForum\"='yes' AND \"InSubForum\"=%i".$ForumIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($ForumID)), $SQLStat);
$apci=0; $apcl=1; if($apcnum>=1) {
while ($apci < $apcnum) {
$apcresult_array = sql_fetch_assoc($apcresult);
$NumsTopics=$apcresult_array["NumTopics"];
$NumTopics = $NumsTopics + $NumTopics;
$NumsPosts=$apcresult_array["NumPosts"];
$NumPosts = $NumsPosts + $NumPosts;
$SubsForumID=$apcresult_array["id"];
$SubsForumName=$apcresult_array["Name"];
$SubsForumType=$apcresult_array["ForumType"];
$SubsForumShowTopics=$result_array["CanHaveTopics"];
if(isset($PermissionInfo['CanViewForum'][$SubsForumID])&&
	$PermissionInfo['CanViewForum'][$SubsForumID]=="yes") {
$ExStr = ""; if ($SubsForumType!="redirect"&&
    $SubsForumShowTopics!="no") { $ExStr = "&page=1"; }
$shownum = null;
if ($SubsForumType=="redirect") { $shownum = "(".$NumRedirects." redirects)"; }
if ($SubsForumType!="redirect") { $shownum = "(".$NumsPosts." posts)"; }
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
<a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=lowview&id=".$ForumID.$ExStr,$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"<?php if($ForumType=="redirect") { echo " onclick=\"window.open(this.href);return false;\""; } ?>><?php echo $ForumName; ?></a> <span style="color: gray; font-size: 10px;"><?php echo $shownum; ?></span></li>
<?php echo $sflist; ?></ul>
<?php ++$i; } sql_free_result($result);
?>
</li></ul></div>
<div>&#160;</div>
<?php } } sql_free_result($preresult);
$ForumCheck = "skip";
if($CanHaveTopics!="yes") { 
	$ForumName = $SForumName; }
if($CanHaveTopics!="no") {
require($SettDir['inc'].'lowtopics.php'); } }
?>
