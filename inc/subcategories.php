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

    $FileInfo: subcategories.php - Last Update: 11/23/2009 SVN 357 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="subcategories.php"||$File3Name=="/subcategories.php") {
	require('index.php');
	exit(); }
if(!is_numeric($_GET['id'])) { $_GET['id'] = null; }
$checkquery = query("SELECT * FROM `".$Settings['sqltable']."categories` WHERE `id`=%i LIMIT 1", array($_GET['id']));
$checkresult=exec_query($checkquery);
$checknum=mysql_num_rows($checkresult);
if($checknum==0) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); mysql_free_result($checkresult);
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($checknum>=1) {
$CategoryID=mysql_result($checkresult,0,"id");
$CategoryName=mysql_result($checkresult,0,"Name");
$CategoryShow=mysql_result($checkresult,0,"ShowCategory");
$CategoryType=mysql_result($checkresult,0,"CategoryType");
$InSubCategory=mysql_result($checkresult,0,"InSubCategory");
$SubShowForums=mysql_result($checkresult,0,"SubShowForums");
$CategoryType = strtolower($CategoryType); $SubShowForums = strtolower($SubShowForums);
$SCategoryName = $CategoryName;
if(!isset($CatPermissionInfo['CanViewCategory'][$CategoryID])) {
	$CatPermissionInfo['CanViewCategory'][$CategoryID] = "no"; }
if($CatPermissionInfo['CanViewCategory'][$CategoryID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$CategoryID]!="yes") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($CatPermissionInfo['CanViewCategory'][$CategoryID]=="yes") {
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=view&id=".$CategoryID,"&","=",$prexqstr[$CategoryType],$exqstr[$CategoryType]);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile[$CategoryType].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile[$CategoryType]; }
$_SESSION['PreViewingTitle'] = "Viewing SubCategory:";
$_SESSION['ViewingTitle'] = $CategoryName;
if($InSubCategory!="0") {
$iscquery = query("SELECT * FROM `".$Settings['sqltable']."categories` WHERE `id`=%i LIMIT 1", array($InSubCategory));
$iscresult=exec_query($iscquery);
$iscnum=mysql_num_rows($iscresult);
if($iscnum>=1) {
$iscCategoryID=mysql_result($iscresult,0,"id");
$iscCategoryName=mysql_result($iscresult,0,"Name");
$iscCategoryShow=mysql_result($iscresult,0,"ShowCategory");
$iscCategoryType=mysql_result($iscresult,0,"CategoryType");
$iscCategoryType = strtolower($iscCategoryType); }
if($iscnum<1) { $InSubCategory = "0"; } 
mysql_free_result($iscresult); }
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">Board index</a><?php if($InSubCategory!="0") { echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$iscCategoryType],$Settings['file_ext'],"act=view&id=".$iscCategoryID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$iscCategoryType],$exqstr[$iscCategoryType]); ?>"><?php echo $iscCategoryName; ?></a><?php } echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$CategoryType],$Settings['file_ext'],"act=view&id=".$CategoryID,$Settings['qstr'],$Settings['qsep'],$prexqstr[$CategoryType],$exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a></div>
<div class="DivNavLinks">&nbsp;</div>
<?php
if($CategoryType=="category") {
redirect("location",$basedir.url_maker($exfile['category'],$Settings['file_ext'],"act=".$_GET['act']."&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['category'],$exqstr['category'],FALSE));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
mysql_free_result($checkresult);
$prequery = query("SELECT * FROM `".$Settings['sqltable']."categories` WHERE `ShowCategory`='yes' AND `InSubCategory`=%i ORDER BY `OrderID` ASC, `id` ASC", array($_GET['id']));
$preresult=exec_query($prequery);
$prenum=mysql_num_rows($preresult);
$prei=0;
while ($prei < $prenum) {
$CategoryID=mysql_result($preresult,$prei,"id");
$CategoryName=mysql_result($preresult,$prei,"Name");
$CategoryShow=mysql_result($preresult,$prei,"ShowCategory");
$CategoryType=mysql_result($preresult,$prei,"CategoryType");
$SSubShowForums=mysql_result($preresult,$prei,"SubShowForums");
$CategoryDescription=mysql_result($preresult,$prei,"Description");
$CategoryType = strtolower($CategoryType); $SubShowForums = strtolower($SubShowForums);
if(isset($CatPermissionInfo['CanViewCategory'][$CategoryID])&&
	$CatPermissionInfo['CanViewCategory'][$CategoryID]=="yes") {
$query = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `ShowForum`='yes' AND `CategoryID`=%i AND `InSubForum`=0 ORDER BY `OrderID` ASC, `id` ASC", array($CategoryID));
$result=exec_query($query);
$num=mysql_num_rows($result);
$i=0;
if($num>=1) {
?>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile[$CategoryType],$Settings['file_ext'],"act=view&id=".$CategoryID,$Settings['qstr'],$Settings['qsep'],$prexqstr[$CategoryType],$exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a></span></div>
<?php } ?>
<table class="Table1" id="SubCat<?php echo $CategoryID; ?>">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr id="SubCatStart<?php echo $CategoryID; ?>" class="TableRow1">
<td class="TableColumn1" colspan="5"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile[$CategoryType],$Settings['file_ext'],"act=view&id=".$CategoryID,$Settings['qstr'],$Settings['qsep'],$prexqstr[$CategoryType],$exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a></span>
</td>
</tr><?php } ?>
<tr id="ForumStatRow<?php echo $CategoryID; ?>" class="TableRow2">
<th class="TableColumn2" style="width: 4%;">&nbsp;</th>
<th class="TableColumn2" style="width: 58%;">Forum</th>
<th class="TableColumn2" style="width: 7%;">Topics</th>
<th class="TableColumn2" style="width: 7%;">Posts</th>
<th class="TableColumn2" style="width: 24%;">Last Topic</th>
</tr>
<?php }
while ($i < $num) {
$ForumID=mysql_result($result,$i,"id");
$ForumName=mysql_result($result,$i,"Name");
$ForumShow=mysql_result($result,$i,"ShowForum");
$ForumType=mysql_result($result,$i,"ForumType");
$ForumShowTopics=mysql_result($result,$i,"CanHaveTopics");
$ForumShowTopics = strtolower($ForumShowTopics);
$NumTopics=mysql_result($result,$i,"NumTopics");
$NumPosts=mysql_result($result,$i,"NumPosts");
$NumRedirects=mysql_result($result,$i,"Redirects");
$ForumDescription=mysql_result($result,$i,"Description");
$ForumType = strtolower($ForumType); $sflist = null;
$gltf = array(null); $gltf[0] = $ForumID;
if ($ForumType=="subforum") { 
$apcquery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `ShowForum`='yes' AND `InSubForum`=%i ORDER BY `OrderID` ASC, `id` ASC", array($ForumID));
$apcresult=exec_query($apcquery);
$apcnum=mysql_num_rows($apcresult);
$apci=0; $apcl=1; if($apcnum>=1) {
while ($apci < $apcnum) {
$NumsTopics=mysql_result($apcresult,$apci,"NumTopics");
$NumTopics = $NumsTopics + $NumTopics;
$NumsPosts=mysql_result($apcresult,$apci,"NumPosts");
$NumPosts = $NumsPosts + $NumPosts;
$SubsForumID=mysql_result($apcresult,$apci,"id");
$SubsForumName=mysql_result($apcresult,$apci,"Name");
$SubsForumType=mysql_result($apcresult,$apci,"ForumType");
if(isset($PermissionInfo['CanViewForum'][$SubsForumID])&&
	$PermissionInfo['CanViewForum'][$SubsForumID]=="yes") {
$sfurl = "<a href=\"";
$sfurl = url_maker($exfile[$SubsForumType],$Settings['file_ext'],"act=view&id=".$SubsForumID.$ExStr,$Settings['qstr'],$Settings['qsep'],$prexqstr[$SubsForumType],$exqstr[$SubsForumType]);
$sfurl = "<a href=\"".$sfurl."\">".$SubsForumName."</a>";
if($apcl==1) {
$sflist = "Subforums:";
$sflist = $sflist." ".$sfurl; }
if($apcl>1) {
$sflist = $sflist.", ".$sfurl; }
$gltf[$apcl] = $SubsForumID; ++$apcl; }
++$apci; }
mysql_free_result($apcresult); } }
$gltf = array(null); $gltf[0] = $ForumID;
if ($ForumType=="subforum") { 
$apcquery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `ShowForum`='yes' AND `InSubForum`=%i ORDER BY `OrderID` ASC, `id` ASC", array($ForumID));
$apcresult=exec_query($apcquery);
$apcnum=mysql_num_rows($apcresult);
$apci=0; $apcl=1; if($apcnum>=1) {
while ($apci < $apcnum) {
$NumsTopics=mysql_result($apcresult,$apci,"NumTopics");
$NumTopics = $NumsTopics + $NumTopics;
$NumsPosts=mysql_result($apcresult,$apci,"NumPosts");
$NumPosts = $NumsPosts + $NumPosts;
$SubsForumID=mysql_result($apcresult,$apci,"id");
$SubsForumName=mysql_result($apcresult,$apci,"Name");
$SubsForumType=mysql_result($apcresult,$apci,"ForumType");
if(isset($PermissionInfo['CanViewForum'][$SubsForumID])&&
	$PermissionInfo['CanViewForum'][$SubsForumID]=="yes") {
$sfurl = "<a href=\"";
$sfurl = url_maker($exfile[$SubsForumType],$Settings['file_ext'],"act=view&id=".$SubsForumID.$ExStr,$Settings['qstr'],$Settings['qsep'],$prexqstr[$SubsForumType],$exqstr[$SubsForumType]);
$sfurl = "<a href=\"".$sfurl."\">".$SubsForumName."</a>";
if($apcl==1) {
$sflist = "Subforums:";
$sflist = $sflist." ".$sfurl; }
if($apcl>1) {
$sflist = $sflist.", ".$sfurl; }
$gltf[$apcl] = $SubsForumID; ++$apcl; }
++$apci; }
mysql_free_result($apcresult); } }
if ($ForumType=="subforum") { 
$apcquery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `ShowForum`='yes' AND `InSubForum`=%i ORDER BY `OrderID` ASC, `id` ASC", array($ForumID));
$apcresult=exec_query($apcquery);
$apcnum=mysql_num_rows($apcresult);
$apci=0; $apcl=1; if($apcnum>=1) {
while ($apci < $apcnum) {
$NumsTopics=mysql_result($apcresult,$apci,"NumTopics");
$NumTopics = $NumsTopics + $NumTopics;
$NumsPosts=mysql_result($apcresult,$apci,"NumPosts");
$NumPosts = $NumsPosts + $NumPosts;
$SubsForumID=mysql_result($apcresult,$apci,"id");
if(isset($PermissionInfo['CanViewForum'][$SubsForumID])&&
	$PermissionInfo['CanViewForum'][$SubsForumID]=="yes") {
$gltf[$apcl] = $SubsForumID; ++$apcl; }
++$apci; }
mysql_free_result($apcresult); } }
if(isset($PermissionInfo['CanViewForum'][$ForumID])&&
	$PermissionInfo['CanViewForum'][$ForumID]=="yes") {
$LastTopic = "&nbsp;<br />&nbsp;<br />&nbsp;";
if(!isset($LastTopic)) { $LastTopic = null; }
$gltnum = count($gltf); $glti = 0; 
$OldUpdateTime = 0; $UseThisFonum = null;
if ($ForumType=="subforum") { 
while ($glti < $gltnum) {
$gltfoquery = query("SELECT * FROM `".$Settings['sqltable']."topics` WHERE `ForumID`=%i ORDER BY `LastUpdate` DESC LIMIT 1", array($gltf[$glti]));
$gltforesult=exec_query($gltfoquery);
$gltfonum=mysql_num_rows($gltforesult);
if($gltfonum>0) {
$NewUpdateTime=mysql_result($gltforesult,0,"LastUpdate");
if($NewUpdateTime>$OldUpdateTime) { 
	$UseThisFonum = $gltf[$glti]; 
$OldUpdateTime = $NewUpdateTime; } }
mysql_free_result($gltforesult);
++$glti; } }
if ($ForumType!="subforum"&&$ForumType!="redirect") { $UseThisFonum = $gltf[0]; }
if ($ForumType!="redirect") {
$gltquery = query("SELECT * FROM `".$Settings['sqltable']."topics` WHERE `ForumID`=%i ORDER BY `LastUpdate` DESC LIMIT 1", array($UseThisFonum));
$gltresult=exec_query($gltquery);
$gltnum=mysql_num_rows($gltresult);
if($gltnum>0){
$TopicID=mysql_result($gltresult,0,"id");
$TopicName=mysql_result($gltresult,0,"TopicName");
$NumReplys=mysql_result($gltresult,0,"NumReply");
$NumPages = null; $NumRPosts = $NumReplys + 1;
if(!isset($Settings['max_posts'])) { $Settings['max_posts'] = 10; }
if($NumRPosts>$Settings['max_posts']) {
$NumPages = ceil($NumRPosts/$Settings['max_posts']); }
if($NumRPosts<=$Settings['max_posts']) { $NumPages = 1; }
$TopicName1 = pre_substr($TopicName,0,20);
$oldtopicname=$TopicName;
if (pre_strlen($TopicName)>20) {
$TopicName1 = $TopicName1."..."; $TopicName=$TopicName1; }
$glrquery = query("SELECT * FROM `".$Settings['sqltable']."posts` WHERE `TopicID`=%i ORDER BY `TimeStamp` DESC LIMIT 1", array($TopicID));
$glrresult=exec_query($glrquery);
$glrnum=mysql_num_rows($glrresult);
if($glrnum>0){
$ReplyID=mysql_result($glrresult,0,"id");
$UsersID=mysql_result($glrresult,0,"UserID");
$GuestsName=mysql_result($glrresult,0,"GuestName");
$TimeStamp=mysql_result($glrresult,0,"TimeStamp");
$TimeStamp=GMTimeChange("F j Y, g:i a",$TimeStamp,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
mysql_free_result($glrresult); }
$PreUsersName = GetUserName($UsersID,$Settings['sqltable']);
if($PreUsersName['Name']===null) { $UsersID = -1;
$PreUsersName = GetUserName($UsersID,$Settings['sqltable']); }
$UsersName = $PreUsersName['Name'];
$UsersHidden = $PreUsersName['Hidden'];
if($UsersName=="Guest") { $UsersName=$GuestsName;
if($UsersName==null) { $UsersName="Guest"; } }
$UsersName1 = pre_substr($UsersName,0,20);
$oldusername=$UsersName;
if (pre_strlen($UsersName)>20) { 
$UsersName1 = $UsersName1."..."; $UsersName=$UsersName1; } 
$lul = null;
if($UsersID>0&&$UsersHidden=="no") {
$lul = url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$UsersID,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']);
$LastTopic = $TimeStamp."<br />\nTopic: <a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=".$NumPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']).$qstrhtml."&#35;reply".$NumRPosts."\" title=\"".$oldtopicname."\">".$TopicName."</a><br />\nUser: <a href=\"".$lul."\" title=\"".$oldusername."\">".$UsersName."</a>"; }
if($UsersID<=0||$UsersHidden=="yes") {
if($UsersID==-1) { $UserPre = "Guest:"; }
if(($UsersID<-1&&$UsersHidden=="yes")||$UsersID==0||($UsersID>0&&$UsersHidden=="yes")) { 
	$UserPre = "Hidden:"; }
$LastTopic = $TimeStamp."<br />\nTopic: <a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=".$NumPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']).$qstrhtml."&#35;reply".$NumRPosts."\" title=\"".$oldtopicname."\">".$TopicName."</a><br />\n".$UserPre." <span title=\"".$oldusername."\">".$UsersName."</span>"; } }
if($LastTopic==null) { $LastTopic = "&nbsp;<br />&nbsp;<br />&nbsp;"; } }
mysql_free_result($gltresult);
if ($ForumType=="redirect") { $LastTopic="&nbsp;<br />Redirects: ".$NumRedirects."<br />&nbsp;"; }
$PreForum = $ThemeSet['ForumIcon'];
if ($ForumType=="forum") { $PreForum=$ThemeSet['ForumIcon']; }
if ($ForumType=="subforum") { $PreForum=$ThemeSet['SubForumIcon']; }
if ($ForumType=="redirect") { $PreForum=$ThemeSet['RedirectIcon']; }
$ExStr = ""; if ($ForumType!="redirect"&&
	$ForumShowTopics!="no") { $ExStr = "&page=1"; }
?>
<tr class="TableRow3" id="Forum<?php echo $ForumID; ?>">
<td class="TableColumn3"><div class="forumicon">
<?php echo $PreForum; ?></div></td>
<td class="TableColumn3"><div class="forumname"><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=view&id=".$ForumID.$ExStr,$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"<?php if($ForumType=="redirect") { echo " onclick=\"window.open(this.href);return false;\""; } ?>><?php echo $ForumName; ?></a></div>
<div class="forumdescription"><?php echo $ForumDescription; ?><br />
<?php echo $sflist; ?></div></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $NumTopics; ?></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $NumPosts; ?></td>
<td class="TableColumn3"><?php echo $LastTopic; ?></td>
</tr>
<?php } ++$i; } mysql_free_result($result);
if($num>=1) {
?>
<tr id="SubCatEnd<?php echo $CategoryID; ?>" class="TableRow4">
<td class="TableColumn4" colspan="5">&nbsp;</td>
</tr>
</table></div>
<div class="DivSubCategories">&nbsp;</div>
<?php } } ++$prei; } }
mysql_free_result($preresult);
$CatCheck = "skip";
if($SubShowForums!="yes") { 
	$CategoryName = $SCategoryName; }
if($SubShowForums!="no") {
require($SettDir['inc'].'categories.php'); } }
?>
