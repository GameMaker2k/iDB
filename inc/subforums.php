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

    $FileInfo: subforums.php - Last Update: 8/26/2024 SVN 1048 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="subforums.php"||$File3Name=="/subforums.php") {
	require('index.php');
	exit(); }
$viewvar = "view";
if($_GET['act']=="lowview") { 
	$viewvar = "lowview"; }
if(!is_numeric($_GET['id'])) { $_GET['id'] = 1; }
if(!isset($ThemeSet['ForumStyle'])) { $ThemeSet['ForumStyle'] = 1; }
if(!is_numeric($ThemeSet['ForumStyle'])) { $ThemeSet['ForumStyle'] = 1; }
if($ThemeSet['ForumStyle']>2||$ThemeSet['ForumStyle']<1) {
	$ThemeSet['ForumStyle'] = 1; }
$checkquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i".$ForumIgnoreList2." LIMIT 1", array($_GET['id']));
$checkresult=sql_query($checkquery,$SQLStat);
$checknum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i".$ForumIgnoreList2." LIMIT 1", array($_GET['id'])), $SQLStat);
if($checknum==0) { redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); sql_free_result($checkresult);
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($checknum>=1) {
$checkresult_array = sql_fetch_assoc($checkresult);
$ForumID=$checkresult_array['id'];
$SForumID=$ForumID;
$ForumName=$checkresult_array['Name'];
$ForumType=$checkresult_array['ForumType'];
$ForumShow=$checkresult_array['ShowForum'];
if($ForumShow=="no") { $_SESSION['ShowActHidden'] = "yes"; }
$InSubForum=$checkresult_array['InSubForum'];
$SFInSubForum=$InSubForum;
$CategoryID=$checkresult_array['CategoryID'];
$RedirectURL=$checkresult_array['RedirectURL'];
$RedirectTimes=$checkresult_array['Redirects'];
$CanHaveTopics=$checkresult_array['CanHaveTopics'];
$NumberViews=$checkresult_array['NumViews'];
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
redirect("location",url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
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
$CategoryID=$preresult_array['id'];
$CategoryName=$preresult_array['Name'];
$CategoryShow=$preresult_array['ShowCategory'];
$CategoryType=$preresult_array['CategoryType'];
$InSubCategory=$preresult_array['InSubCategory'];
if($CategoryShow=="no") { $_SESSION['ShowActHidden'] = "yes"; }
$CategoryDescription=$preresult_array['Description'];
if($InSubForum!="0") {
$isfquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i".$ForumIgnoreList2." LIMIT 1", array($InSubForum));
$isfresult=sql_query($isfquery,$SQLStat);
$isfnum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i".$ForumIgnoreList2." LIMIT 1", array($InSubForum)), $SQLStat);
if($isfnum>=1) {
$isfresult_array = sql_fetch_assoc($isfresult);
$isfForumID=$isfresult_array['id'];
$isfForumCatID=$isfresult_array['CategoryID'];
$isfForumName=$isfresult_array['Name'];
$isfForumType=$isfresult_array['ForumType'];
$isfForumType = strtolower($isfForumType);
$isfRedirectURL=$isfresult_array['RedirectURL']; }
if($isfnum<1) { $InSubForum = "0"; } 
sql_free_result($isfresult); }
if(isset($_SESSION['OldViewingPage'])) { $_SESSION['AncientViewingPage'] = $_SESSION['OldViewingPage']; } else { $_SESSION['AncientViewingPage'] = url_maker(null,"no+ext","act=".$viewvar,"&","=",$prexqstr['index'],$exqstr['index']); }
if(isset($_SESSION['OldViewingFile'])) { $_SESSION['AncientViewingFile'] = $_SESSION['OldViewingFile']; } else { 
	 if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
	    $_SESSION['AncientViewingFile'] = $exfile['index'].$Settings['file_ext']; }
	 if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
	    $_SESSION['AncientViewingFile'] = $exfile['index']; } }
if(isset($_SESSION['OldPreViewingTitle'])) { $_SESSION['AncientPreViewingTitle'] = $_SESSION['OldPreViewingTitle']; } else { $_SESSION['AncientPreViewingTitle'] = "Viewing"; }
if(isset($_SESSION['OldViewingTitle'])) { $_SESSION['AncientViewingTitle'] = $_SESSION['OldViewingTitle']; } else { $_SESSION['AncientViewingTitle'] = "Board index"; }
if(isset($_SESSION['OldExtraData'])) { $_SESSION['AncientExtraData'] = $_SESSION['OldExtraData']; } else { $_SESSION['AncientExtraData'] = "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;"; }
if(isset($_SESSION['ViewingPage'])) { $_SESSION['OldViewingPage'] = $_SESSION['ViewingPage']; } else { $_SESSION['OldViewingPage'] = url_maker(null,"no+ext","act=".$viewvar,"&","=",$prexqstr['index'],$exqstr['index']); }
if(isset($_SESSION['ViewingFile'])) { $_SESSION['OldViewingFile'] = $_SESSION['ViewingFile']; } else { 
	 if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
	    $_SESSION['OldViewingFile'] = $exfile['index'].$Settings['file_ext']; }
	 if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
	    $_SESSION['OldViewingFile'] = $exfile['index']; } }
if(isset($_SESSION['PreViewingTitle'])) { $_SESSION['OldPreViewingTitle'] = $_SESSION['PreViewingTitle']; } else { $_SESSION['OldPreViewingTitle'] = "Viewing"; }
if(isset($_SESSION['ViewingTitle'])) { $_SESSION['OldViewingTitle'] = $_SESSION['ViewingTitle']; } else { $_SESSION['OldViewingTitle'] = "Board index"; }
if(isset($_SESSION['ExtraData'])) { $_SESSION['OldExtraData'] = $_SESSION['ExtraData']; } else { $_SESSION['OldExtraData'] = "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;"; }
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=".$viewvar."&id=".$ForumID."&page=".$_GET['page'],"&","=",$prexqstr[$ForumType],$exqstr[$ForumType]);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile[$ForumType].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile[$ForumType]; }
$_SESSION['PreViewingTitle'] = "Viewing SubForum:";
$_SESSION['ViewingTitle'] = $ForumName;
$_SESSION['ExtraData'] = "currentact:".$_GET['act']."; currentcategoryid:".$InSubCategory.",".$CategoryID."; currentforumid:".$InSubForum.",".$ForumID."; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;"; 
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$CategoryType],$Settings['file_ext'],"act=".$viewvar."&id=".$CategoryID,$Settings['qstr'],$Settings['qsep'],$prexqstr[$CategoryType],$exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a><?php if($InSubForum!="0") { echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$isfForumType],$Settings['file_ext'],"act=".$viewvar."&id=".$isfForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$isfForumType],$exqstr[$isfForumType]); ?>"><?php echo $isfForumName; ?></a><?php } echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=".$viewvar."&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"><?php echo $ForumName; ?></a></div>
<div class="DivNavLinks">&#160;</div>
<?php
if(!isset($CatPermissionInfo['CanViewCategory'][$CategoryID])) {
	$CatPermissionInfo['CanViewCategory'][$CategoryID] = "no"; }
if($CatPermissionInfo['CanViewCategory'][$CategoryID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$CategoryID]!="yes") {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if(!isset($PermissionInfo['CanViewForum'][$_GET['id']])) {
	$PermissionInfo['CanViewForum'][$_GET['id']] = "no"; }
if($PermissionInfo['CanViewForum'][$_GET['id']]=="no"||
	$PermissionInfo['CanViewForum'][$_GET['id']]!="yes") {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
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
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['category'],$Settings['file_ext'],"act=".$viewvar."&id=".$CategoryID,$Settings['qstr'],$Settings['qsep'],$prexqstr['category'],$exqstr['category']); ?>"><?php echo $CategoryName; ?></a></span></div>
<?php } ?>
<table class="Table1" id="Cat<?php echo $CategoryID; ?>">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1" id="CatStart<?php echo $CategoryID; ?>">
<td class="TableColumn1" colspan="5"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['category'],$Settings['file_ext'],"act=".$viewvar."&id=".$CategoryID,$Settings['qstr'],$Settings['qsep'],$prexqstr['category'],$exqstr['category']); ?>"><?php echo $CategoryName; ?></a></span>
</td>
</tr><?php } ?>
<tr id="ForumStatRow<?php echo $CategoryID; ?>" class="TableRow2">
<th class="TableColumn2" style="width: 4%;">&#160;</th>
<th class="TableColumn2" style="width: 58%;">Forum</th>
<th class="TableColumn2" style="width: 7%;">Topics</th>
<th class="TableColumn2" style="width: 7%;">Posts</th>
<th class="TableColumn2" style="width: 24%;">Last Topic</th>
</tr>
<?php
while ($i < $num) {
$result_array = sql_fetch_assoc($result);
$ForumID=$result_array['id'];
$ForumName=$result_array['Name'];
$ForumShow=$result_array['ShowForum'];
$ForumType=$result_array['ForumType'];
$ForumShowTopics=$result_array['CanHaveTopics'];
$ForumShowTopics = strtolower($ForumShowTopics);
$NumTopics=$result_array['NumTopics'];
$NumPosts=$result_array['NumPosts'];
$NumRedirects=$result_array['Redirects'];
$ForumDescription=$result_array['Description'];
$ForumType = strtolower($ForumType); $sflist = null;
$gltf = array(null); $gltf[0] = $ForumID;
if ($ForumType=="subforum") { 
$apcquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"ShowForum\"='yes' AND \"InSubForum\"=%i".$ForumIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($ForumID));
$apcresult=sql_query($apcquery,$SQLStat);
$apcnum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."forums\" WHERE \"ShowForum\"='yes' AND \"InSubForum\"=%i".$ForumIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($ForumID)), $SQLStat);
$apci=0; $apcl=1; if($apcnum>=1) {
while ($apci < $apcnum) {
$apcresult_array = sql_fetch_assoc($apcresult);
$NumsTopics=$apcresult_array['NumTopics'];
$NumTopics = $NumsTopics + $NumTopics;
$NumsPosts=$apcresult_array['NumPosts'];
$NumPosts = $NumsPosts + $NumPosts;
$SubsForumID=$apcresult_array['id'];
$SubsForumName=$apcresult_array['Name'];
$SubsForumType=$apcresult_array['ForumType'];
$SubsForumShowTopics=$apcresult_array['CanHaveTopics'];
$SubsForumDescription=$apcresult_array['Description'];
if(isset($PermissionInfo['CanViewForum'][$SubsForumID])&&
	$PermissionInfo['CanViewForum'][$SubsForumID]=="yes") {
$ExStr = ""; if ($SubsForumType!="redirect"&&
    $SubsForumShowTopics!="no") { $ExStr = "&page=1"; }
//$sfurl = "<a href=\"";
$sfurl = url_maker($exfile[$SubsForumType],$Settings['file_ext'],"act=".$viewvar."&id=".$SubsForumID.$ExStr,$Settings['qstr'],$Settings['qsep'],$prexqstr[$SubsForumType],$exqstr[$SubsForumType]);
$sfurl = "<a title=\"".$SubsForumDescription."\" href=\"".$sfurl."\">".$SubsForumName."</a>";
if($apcl==1) {
$sflist = "Subforums:";
$sflist = $sflist." ".$sfurl; }
if($apcl>1) {
$sflist = $sflist.", ".$sfurl; }
$gltf[$apcl] = $SubsForumID; ++$apcl; }
++$apci; }
sql_free_result($apcresult); } }
if(isset($PermissionInfo['CanViewForum'][$ForumID])&&
	$PermissionInfo['CanViewForum'][$ForumID]=="yes") {
$LastTopic = "&#160;<br />&#160;<br />&#160;";
if(!isset($LastTopic)) { $LastTopic = null; }
$gltnum = count($gltf); $glti = 0; 
$OldUpdateTime = 0; $UseThisFonum = null;
if ($ForumType=="subforum") { 
while ($glti < $gltnum) {
$ExtraIgnores = null;
if($PermissionInfo['CanModForum'][$gltf[$glti]]=="no") {
	$ExtraIgnores = " AND \"Closed\"<>3"; }
$gltfoquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."topics\" WHERE \"CategoryID\"=%i".$ExtraIgnores.$ForumIgnoreList4." AND \"ForumID\"=%i ORDER BY \"LastUpdate\" DESC LIMIT 1", array($CategoryID,$gltf[$glti]));
$gltforesult=sql_query($gltfoquery,$SQLStat);
$gltfonum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."topics\" WHERE \"CategoryID\"=%i".$ExtraIgnores.$ForumIgnoreList4." AND \"ForumID\"=%i ORDER BY \"LastUpdate\" DESC LIMIT 1", array($CategoryID,$gltf[$glti])), $SQLStat);
if($gltfonum>0) {
$ltforesult_array = sql_fetch_assoc($ltforesult);
$NewUpdateTime=$gltforesult_array['LastUpdate'];
if($NewUpdateTime>$OldUpdateTime) { 
	$UseThisFonum = $gltf[$glti]; 
$OldUpdateTime = $NewUpdateTime; }
sql_free_result($gltforesult); }
++$glti; } 
if($UseThisFonum==0) {
	$UseThisFonum = $gltf[0]; } }
if ($ForumType!="subforum"&&$ForumType!="redirect") { $UseThisFonum = $gltf[0]; }
if ($ForumType!="redirect") {
$ExtraIgnores = null;
if($PermissionInfo['CanModForum'][$UseThisFonum]=="no") {
	$ExtraIgnores = " AND \"Closed\"<>3"; }
$gltquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."topics\" WHERE (\"ForumID\"=%i".$ExtraIgnores.$ForumIgnoreList4.") OR (\"OldForumID\"=%i".$ExtraIgnores.$ForumIgnoreList4.") ORDER BY \"LastUpdate\" DESC LIMIT 1", array($UseThisFonum,$UseThisFonum));
$gltresult=sql_query($gltquery,$SQLStat);
$gltnum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."topics\" WHERE (\"ForumID\"=%i".$ExtraIgnores.$ForumIgnoreList4.") OR (\"OldForumID\"=%i".$ExtraIgnores.$ForumIgnoreList4.") ORDER BY \"LastUpdate\" DESC LIMIT 1", array($UseThisFonum,$UseThisFonum)), $SQLStat);
if($gltnum>0){
$gltresult_array = sql_fetch_assoc($gltresult);
$TopicID=$gltresult_array['id'];
$TopicName=$gltresult_array['TopicName'];
$NumReplys=$gltresult_array['NumReply'];
$TopicName1 = pre_substr($TopicName,0,20);
$oldtopicname=$TopicName; $NumRPosts = $NumReplys + 1;
if(!isset($Settings['max_posts'])) { $Settings['max_posts'] = 10; }
if($NumRPosts>$Settings['max_posts']) {
$NumPages = ceil($NumRPosts/$Settings['max_posts']); }
if($NumRPosts<=$Settings['max_posts']) { $NumPages = 1; }
if (pre_strlen($TopicName)>20) { 
$TopicName1 = $TopicName1."..."; $TopicName=$TopicName1; }
$glrquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."posts\" WHERE \"TopicID\"=%i ORDER BY \"TimeStamp\" DESC LIMIT 1", array($TopicID));
$glrresult=sql_query($glrquery,$SQLStat);
$glrnum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."posts\" WHERE \"TopicID\"=%i ORDER BY \"TimeStamp\" DESC LIMIT 1", array($TopicID)), $SQLStat);
if($glrnum>0){
$glrresult_array = sql_fetch_assoc($glrresult);
$ReplyID=$glrresult_array['id'];
$UsersID=$glrresult_array['UserID'];
$GuestsName=$glrresult_array['GuestName'];
$TimeStamp=$glrresult_array['TimeStamp'];
$tmpusrcurtime = new DateTime();
$tmpusrcurtime->setTimestamp($TimeStamp);
$tmpusrcurtime->setTimezone($usertz);
$TimeStamp=$tmpusrcurtime->format($_SESSION['iDBDateFormat'].", ".$_SESSION['iDBTimeFormat']);
sql_free_result($glrresult); }
$PreUsersName = GetUserName($UsersID,$Settings['sqltable'],$SQLStat);
if($PreUsersName['Name']===null) { $UsersID = -1;
$PreUsersName = GetUserName($UsersID,$Settings['sqltable'],$SQLStat); }
$UsersName = $PreUsersName['Name'];
$UsersHidden = $PreUsersName['Hidden'];
$UsersName1 = pre_substr($UsersName,0,20);
if($UsersName=="Guest") { $UsersName=$GuestsName;
if($UsersName==null) { $UsersName="Guest"; } }
$oldusername=$UsersName;
if (pre_strlen($UsersName)>20) {
$UsersName1 = $UsersName1."..."; $UsersName=$UsersName1; } 
$lul = null;
if($UsersID>0&&$UsersHidden=="no") {
$lul = url_maker($exfile['member'],$Settings['file_ext'],"act=".$viewvar."&id=".$UsersID,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']);
$LastTopic = $TimeStamp."<br />\nTopic: <a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=".$viewvar."&id=".$TopicID."&page=".$NumPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']).$qstrhtml."&#35;reply".$NumRPosts."\" title=\"".$oldtopicname."\">".$TopicName."</a><br />\nUser: <a href=\"".$lul."\" title=\"".$oldusername."\">".$UsersName."</a>"; }
if($UsersID<=0||$UsersHidden=="yes") {
if($UsersID==-1) { $UserPre = "Guest:"; }
if(($UsersID<-1&&$UsersHidden=="yes")||$UsersID==0||($UsersID>0&&$UsersHidden=="yes")) { 
	$UserPre = "Hidden:"; }
$LastTopic = $TimeStamp."<br />\nTopic: <a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=".$viewvar."&id=".$TopicID."&page=".$NumPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']).$qstrhtml."&#35;reply".$NumRPosts."\" title=\"".$oldtopicname."\">".$TopicName."</a><br />\n".$UserPre." <span title=\"".$oldusername."\">".$UsersName."</span>"; } }
if($LastTopic==null) { $LastTopic = "&#160;<br />&#160;<br />&#160;"; }
sql_free_result($gltresult); }
if ($ForumType=="redirect") { $LastTopic="&#160;<br />Redirects: ".$NumRedirects."<br />&#160;"; }
$PreForum = $ThemeSet['ForumIcon'];
if ($ForumType=="forum") { $PreForum=$ThemeSet['ForumIcon']; }
if ($ForumType=="subforum") { $PreForum=$ThemeSet['SubForumIcon']; }
if ($ForumType=="redirect") { $PreForum=$ThemeSet['RedirectIcon']; }
$ExStr = ""; if ($ForumType!="redirect"&&
	$ForumShowTopics!="no") { $ExStr = "&page=1"; }
if($ThemeSet['ForumStyle']==1) {
	$ForumClass[1] = " class=\"TableColumn3\" ";
	$ForumClass[2] = " class=\"TableColumn3\" ";
	$ForumClass[3] = " class=\"TableColumn3\" ";
	$ForumClass[4] = " class=\"TableColumn3\" ";
	$ForumClass[5] = " class=\"TableColumn3\" "; }
if($ThemeSet['ForumStyle']==2) {
	$ForumClass[1] = " class=\"TableColumn3\" ";
	$ForumClass[2] = " class=\"TableColumn3\" ";
	$ForumClass[3] = " class=\"TableColumn3Alt\" ";
	$ForumClass[4] = " class=\"TableColumn3Alt\" ";
	$ForumClass[5] = " class=\"TableColumn3Alt\" "; }
?>
<tr class="TableRow3" id="SubForum<?php echo $ForumID; ?>">
<td<?php echo $ForumClass[1]; ?>><div class="forumicon">
<?php echo $PreForum; ?></div></td>
<td<?php echo $ForumClass[2]; ?>><div class="forumname"><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=".$viewvar."&id=".$ForumID.$ExStr,$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"<?php if($ForumType=="redirect") { echo " onclick=\"window.open(this.href);return false;\""; } ?>><?php echo $ForumName; ?></a></div>
<div class="forumdescription">
<?php echo $ForumDescription; ?><br />
<?php echo $sflist; ?></div></td>
<td<?php echo $ForumClass[3]; ?>style="text-align: center;"><?php echo $NumTopics; ?></td>
<td<?php echo $ForumClass[4]; ?>style="text-align: center;"><?php echo $NumPosts; ?></td>
<td<?php echo $ForumClass[5]; ?>><?php echo $LastTopic; ?></td>
</tr>
<?php } ++$i; } sql_free_result($result);
?>
<tr id="CatEnd<?php echo $CategoryID; ?>" class="TableRow4">
<td class="TableColumn4" colspan="5">&#160;</td>
</tr>
</table></div>
<div class="DivSubForums">&#160;</div>
<?php } } sql_free_result($preresult);
$ForumCheck = "skip";
if($CanHaveTopics!="yes") { 
$ForumName = $SForumName; $ForumID = $SForumID; $InSubForum = $SFInSubForum;
$uviewlcuttime = $utccurtime->getTimestamp();
$uviewltime = $uviewlcuttime - ini_get("session.gc_maxlifetime");
if($InSubForum==0) {
$uviewlquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" >= %i AND \"session_id\"<>'%s' AND (\"serialized_data\" LIKE '%s' OR \"serialized_data\" LIKE '%s') ORDER BY \"expires\" DESC", array($uviewltime, session_id(), "%currentforumid:0,".$ForumID.";%", "%currentforumid:".$ForumID.",%")); 
$uviewlnum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" >= %i AND \"session_id\"<>'%s' AND (\"serialized_data\" LIKE '%s' OR \"serialized_data\" LIKE '%s') ORDER BY \"expires\" DESC", array($uviewltime, session_id(), "%currentforumid:0,".$ForumID.";%", "%currentforumid:".$ForumID.",%")), $SQLStat); }
if($InSubForum!=0) {
$uviewlquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" >= %i AND \"session_id\"<>'%s' AND (\"serialized_data\" LIKE '%s' OR \"serialized_data\" LIKE '%s') ORDER BY \"expires\" DESC", array($uviewltime, session_id(), "%currentforumid:".$InSubForum.",".$ForumID.";%", "%currentforumid:0,".$ForumID.";"));
$uviewlnum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" >= %i AND \"session_id\"<>'%s' AND (\"serialized_data\" LIKE '%s' OR \"serialized_data\" LIKE '%s') ORDER BY \"expires\" DESC", array($uviewltime, session_id(), "%currentforumid:".$InSubForum.",".$ForumID.";%", "%currentforumid:0,".$ForumID.";")), $SQLStat); } }
$uviewlresult=sql_query($uviewlquery,$SQLStat);
$uviewli=0; $uviewlmn = 0; $uviewlgn = 0; $uviewlan = 0; $uviewlmbn = 0;
$MembersViewList = null; $GuestsOnline = null;
while ($uviewli < $uviewlnum) {
$uviewlresult_array = sql_fetch_assoc($uviewlresult);
$session_data=$uviewlresult_array['session_data']; 
$serialized_data=$uviewlresult_array['serialized_data'];
$session_user_agent=$uviewlresult_array['user_agent']; 
$session_client_hints=json_decode($uviewlresult_array['client_hints']);
$session_ip_address=$uviewlresult_array['ip_address'];
//$UserSessInfo = unserialize_session($session_data);
$UserSessInfo = unserialize($serialized_data);
if(!isset($UserSessInfo['UserGroup'])) { $UserSessInfo['UserGroup'] = $Settings['GuestGroup']; }
$AmIHiddenUser = "no";
$user_agent_check = false;
if (user_agent_check($session_user_agent)) {
    // Use the result from user_agent_check if it's valid
    $user_agent_check = user_agent_check($session_user_agent);
} else {
    // Check if browscap is available
    if (ini_get('browscap')) {
        // Attempt to use get_browser() if browscap is set
        $pre_user_agent = @get_browser($session_user_agent, true);
        if ($pre_user_agent !== false) {
            // Use get_browser result if available
            $session_user_agent = $pre_user_agent['parent'] . " on " . $pre_user_agent['platform'];
        }
        unset($pre_user_agent);
    }
    // If browscap is not set or get_browser() fails, retain $session_user_agent
    // from the SQL select.
}
if($UserSessInfo['UserGroup']!=$Settings['GuestGroup']||$user_agent_check!==false) {
$PreAmIHiddenUser = GetUserName($UserSessInfo['UserID'],$Settings['sqltable'],$SQLStat);
$AmIHiddenUser = $PreAmIHiddenUser['Hidden'];
if(isset($UserSessInfo['AnonymousLogin']) && $UserSessInfo['AnonymousLogin']=="yes") { $AmIHiddenUser = "yes"; }
if((($AmIHiddenUser=="no"||$GroupInfo['CanViewAnonymous']=="yes")&&$UserSessInfo['UserID']>0)||$user_agent_check!==false) {
if($uviewlmbn>0) { $MembersViewList .= ", "; }
$userprestring = "";
if($AmIHiddenUser=="yes") { $userprestring = "*"; }
if($user_agent_check===false) {
$uatitleadd = null;
if($GroupInfo['CanViewUserAgent']=="yes") { $uatitleadd = " title=\"".htmlentities($session_user_agent, ENT_QUOTES, $Settings['charset'])."\""; }
$MembersViewList .= "<a".$uatitleadd." href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=".$viewvar."&id=".$UserSessInfo['UserID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">".$userprestring.$UserSessInfo['MemberName']."</a>"; 
if($GroupInfo['CanViewIPAddress']=="yes") {
$MembersViewList .= " (<a title=\"".$session_ip_address."\" onclick=\"window.open(this.href);return false;\" href=\"".sprintf($IPCheckURL,$session_ip_address)."\">".$session_ip_address."</a>)"; }
++$uviewlmn; ++$uviewlmbn; }
if($user_agent_check!==false) {
$uatitleadd = null;
if($GroupInfo['CanViewUserAgent']=="yes") { $uatitleadd = " title=\"".htmlentities($session_user_agent, ENT_QUOTES, $Settings['charset'])."\""; }
$MembersViewList .= "<span".$uatitleadd.">".$user_agent_check."</span>"; 
if($GroupInfo['CanViewIPAddress']=="yes") {
$MembersViewList .= " (<a title=\"".$session_ip_address."\" onclick=\"window.open(this.href);return false;\" href=\"".sprintf($IPCheckURL,$session_ip_address)."\">".$session_ip_address."</a>)"; }
++$uviewlmbn; } }
if($UserSessInfo['UserID']<=0||$AmIHiddenUser=="yes") {
if($user_agent_check===false) {
++$uviewlan; } } }
if($UserSessInfo['UserGroup']==$Settings['GuestGroup']) {
/*$uatitleadd = null;
if($GroupInfo['CanViewUserAgent']=="yes") { $uatitleadd = " title=\"".htmlentities($session_user_agent, ENT_QUOTES, $Settings['charset'])."\""; }
$GuestsViewList .= "<a".$uatitleadd." href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=".$viewvar."&id=".$MemList['ID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">".$MemList['Name']."</a>";
if($GroupInfo['CanViewIPAddress']=="yes") {
$GuestsViewList .= " (<a title=\"".$session_ip_address."\" onclick=\"window.open(this.href);return false;\" href=\"".sprintf($IPCheckURL,$session_ip_address)."\">".$session_ip_address."</a>)"; } */
++$uviewlgn; }
++$uviewli; }
if(!isset($_SESSION['UserGroup'])) { $_SESSION['UserGroup'] = $Settings['GuestGroup']; }
$AmIHiddenUser = "no";
$user_agent_check = false;
if(user_agent_check($_SERVER['HTTP_USER_AGENT'])) {
	$user_agent_check = user_agent_check($_SERVER['HTTP_USER_AGENT']); }
if($_SESSION['UserGroup']!=$Settings['GuestGroup']||$user_agent_check!==false) {
$PreAmIHiddenUser = GetUserName($_SESSION['UserID'],$Settings['sqltable'],$SQLStat);
$AmIHiddenUser = $PreAmIHiddenUser['Hidden'];
if((($AmIHiddenUser=="no"||$GroupInfo['CanViewAnonymous']=="yes")&&$_SESSION['UserID']>0)||$user_agent_check!==false) {
if($uviewlmbn>0) { $MembersViewList = ", ".$MembersViewList; }
if($user_agent_check===false) {
$uatitleadd = null;
if($GroupInfo['CanViewUserAgent']=="yes") { $uatitleadd = " title=\"".htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, $Settings['charset'])."\""; }
if($GroupInfo['CanViewIPAddress']=="yes") {
$MembersViewList = " (<a title=\"".$_SERVER['REMOTE_ADDR']."\" onclick=\"window.open(this.href);return false;\" href=\"".sprintf($IPCheckURL,$_SERVER['REMOTE_ADDR'])."\">".$_SERVER['REMOTE_ADDR']."</a>)".$MembersViewList; }
$MembersViewList = "<a".$uatitleadd." href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=".$viewvar."&id=".$_SESSION['UserID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">".$_SESSION['MemberName']."</a>".$MembersViewList; 
++$uviewlmn; ++$uviewlmbn; }
if($user_agent_check!==false) {
$uatitleadd = null;
if($GroupInfo['CanViewIPAddress']=="yes") {
$MembersViewList = " (<a title=\"".$_SERVER['REMOTE_ADDR']."\" onclick=\"window.open(this.href);return false;\" href=\"".sprintf($IPCheckURL,$_SERVER['REMOTE_ADDR'])."\">".$_SERVER['REMOTE_ADDR']."</a>)".$MembersViewList; }
if($GroupInfo['CanViewUserAgent']=="yes") { $uatitleadd = " title=\"".htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, $Settings['charset'])."\""; }
$MembersViewList = "<span".$uatitleadd.">".$user_agent_check."</span>".$MembersViewList; 
++$uviewlmbn; } }
if($_SESSION['UserID']<=0||$AmIHiddenUser=="yes") {
if($user_agent_check===false) {
++$uviewlan; } } }
if($_SESSION['UserGroup']==$Settings['GuestGroup']) {
/*$uatitleadd = null;
if($GroupInfo['CanViewUserAgent']=="yes") { $uatitleadd = " title=\"".htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, $Settings['charset'])."\""; }
if($GroupInfo['CanViewIPAddress']=="yes") {
$GuestsViewList = " (<a title=\"".$_SERVER['REMOTE_ADDR']."\" onclick=\"window.open(this.href);return false;\" href=\"".sprintf($IPCheckURL,$_SERVER['REMOTE_ADDR'])."\">".$_SERVER['REMOTE_ADDR']."</a>)".$GuestsViewList; }
$GuestsViewList = "<a".$uatitleadd." href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=".$viewvar."&id=".$MemList['ID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">".$MemList['Name']."</a>".$GuestsViewList; */
++$uviewlgn; }
++$uviewlnum;
?>
<div class="StatsBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableStatsRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=".$viewvar."&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>">Forum Statistics</a></span></div>
<?php } ?>
<table id="BoardStats" class="TableStats1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableStatsRow1">
<td class="TableStatsColumn1" colspan="2"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=".$viewvar."&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>">Forum Statistics</a></span>
</td>
</tr><?php } ?>
<tr id="Stats1" class="TableStatsRow2">
<td class="TableStatsColumn2" colspan="2" style="width: 100%; font-weight: bold;"><?php echo $uviewlnum; ?> users viewing forum</td>
</tr>
<tr class="TableStatsRow3" id="Stats2">
<td style="width: 4%;" class="TableStatsColumn3"><div class="statsicon">
<?php echo $ThemeSet['BoardStatsIcon']; ?></div></td>
<td style="width: 96%;" class="TableStatsColumn3"><div class="statsinfo">
&#160;<span style="font-weight: bold;"><?php echo $uviewlgn; ?></span> guests, <span style="font-weight: bold;"><?php echo $uviewlmn; ?></span> members, <span style="font-weight: bold;"><?php echo $uviewlan; ?></span> anonymous members <br />
<?php if($MembersViewList!=null) { ?>&#160;<?php echo $MembersViewList."\n<br />"; } ?>
</div></td>
</tr>
<tr id="Stats7" class="TableStatsRow4">
<td class="TableStatsColumn4" colspan="2">&#160;</td>
</tr>
</table></div>
<div class="DivStats">&#160;</div>
<?php }
if($CanHaveTopics!="no") {
require($SettDir['inc'].'topics.php'); } }
?>
