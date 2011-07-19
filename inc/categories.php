<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2011 iDB Support - http://idb.berlios.de/
    Copyright 2004-2011 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: categories.php - Last Update: 07/18/2011 SVN 719 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="categories.php"||$File3Name=="/categories.php") {
	require('index.php');
	exit(); }
if(!is_numeric($_GET['id'])) { $_GET['id'] = null; }
if(!isset($ThemeSet['ForumStyle'])) { $ThemeSet['ForumStyle'] = 1; }
if(!is_numeric($ThemeSet['ForumStyle'])) { $ThemeSet['ForumStyle'] = 1; }
if($ThemeSet['ForumStyle']>2||$ThemeSet['ForumStyle']<1) {
	$ThemeSet['ForumStyle'] = 1; }
$prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i".$CatIgnoreList2." LIMIT 1", array($_GET['id']));
$preresult=sql_query($prequery,$SQLStat);
$prenum=sql_num_rows($preresult);
if($prenum==0) { redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); sql_free_result($preresult);
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($prenum>=1) {
$CategoryID=sql_result($preresult,0,"id");
$CategoryName=sql_result($preresult,0,"Name");
$CategoryShow=sql_result($preresult,0,"ShowCategory");
if($CategoryShow=="yes") { $_SESSION['ShowActHidden'] = "yes"; }
$CategoryType=sql_result($preresult,0,"CategoryType");
$InSubCategory=sql_result($preresult,0,"InSubCategory");
$SubShowForums=sql_result($preresult,0,"SubShowForums");
$CategoryDescription=sql_result($preresult,0,"Description");
$CategoryType = strtolower($CategoryType); $SubShowForums = strtolower($SubShowForums);
$CategoryPostCountView=sql_result($preresult,0,"PostCountView");
$CategoryKarmaCountView=sql_result($preresult,0,"KarmaCountView");
if($MyPostCountChk==null) { $MyPostCountChk = 0; }
if($MyKarmaCount==null) { $MyKarmaCount = 0; }
if($GroupInfo['HasAdminCP']!="yes"||$GroupInfo['HasModCP']!="yes") {
if($CategoryPostCountView!=0&&$MyPostCountChk<$CategoryPostCountView) {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($CategoryKarmaCountView!=0&&$MyKarmaCount<$CategoryKarmaCountView) {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); } }
if(!isset($CatPermissionInfo['CanViewCategory'][$CategoryID])) {
	$CatPermissionInfo['CanViewCategory'][$CategoryID] = "no"; }
if($CatPermissionInfo['CanViewCategory'][$CategoryID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$CategoryID]!="yes") {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($CatPermissionInfo['CanViewCategory'][$CategoryID]=="yes") {
if(!isset($CatCheck)) { $CatCheck = null; } 
if($CatCheck!="skip") {
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=view&id=".$CategoryID,"&","=",$prexqstr[$CategoryType],$exqstr[$CategoryType]);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile[$CategoryType].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile[$CategoryType]; }
$_SESSION['PreViewingTitle'] = "Viewing Category:";
$_SESSION['ViewingTitle'] = $CategoryName;
if($InSubCategory!="0") {
$iscquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i".$CatIgnoreList2." LIMIT 1", array($InSubCategory));
$iscresult=sql_query($iscquery,$SQLStat);
$iscnum=sql_num_rows($iscresult);
if($iscnum>=1) {
$iscCategoryID=sql_result($iscresult,0,"id");
$iscCategoryName=sql_result($iscresult,0,"Name");
$iscCategoryShow=sql_result($iscresult,0,"ShowCategory");
$iscCategoryType=sql_result($iscresult,0,"CategoryType");
$iscCategoryType = strtolower($iscCategoryType); }
if($iscnum<1) { $InSubCategory = "0"; } 
sql_free_result($iscresult); }
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a><?php if($InSubCategory!="0") { echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$iscCategoryType],$Settings['file_ext'],"act=view&id=".$iscCategoryID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$iscCategoryType],$exqstr[$iscCategoryType]); ?>"><?php echo $iscCategoryName; ?></a><?php } echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$CategoryType],$Settings['file_ext'],"act=view&id=".$CategoryID,$Settings['qstr'],$Settings['qsep'],$prexqstr[$CategoryType],$exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a></div>
<div class="DivNavLinks">&nbsp;</div>
<?php
if($CategoryType=="subcategory") {
redirect("location",$rbasedir.url_maker($exfile['subcategory'],$Settings['file_ext'],"act=".$_GET['act']."&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['subcategory'],$exqstr['subcategory'],FALSE));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); } }
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"ShowForum\"='yes' AND \"CategoryID\"=%i AND \"InSubForum\"=0".$ForumIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($CategoryID));
$result=sql_query($query,$SQLStat);
$num=sql_num_rows($result);
$i=0;
if($num>=1) {
?>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile[$CategoryType],$Settings['file_ext'],"act=view&id=".$CategoryID,$Settings['qstr'],$Settings['qsep'],$prexqstr[$CategoryType],$exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a></span></div>
<?php } ?>
<table id="Cat<?php echo $CategoryID; ?>" class="Table1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr id="CatStart<?php echo $CategoryID; ?>" class="TableRow1">
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
$apcquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"ShowForum\"='yes' AND \"InSubForum\"=%i".$ForumIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($ForumID));
$apcresult=sql_query($apcquery,$SQLStat);
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
$SubsForumShowTopics=sql_result($result,$i,"CanHaveTopics");
if(isset($PermissionInfo['CanViewForum'][$SubsForumID])&&
	$PermissionInfo['CanViewForum'][$SubsForumID]=="yes") {
$ExStr = ""; if ($SubsForumType!="redirect"&&
    $SubsForumShowTopics!="no") { $ExStr = "&page=1"; }
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
sql_free_result($apcresult); } }
if(isset($PermissionInfo['CanViewForum'][$ForumID])&&
	$PermissionInfo['CanViewForum'][$ForumID]=="yes") {
$LastTopic = "&nbsp;<br />&nbsp;<br />&nbsp;";
if(!isset($LastTopic)) { $LastTopic = null; }
$gltnum = count($gltf); $glti = 0; 
$OldUpdateTime = 0; $UseThisFonum = null;
if ($ForumType=="subforum") { 
while ($glti < $gltnum) {
$ExtraIgnores = null;
if($PermissionInfo['CanModForum'][$gltf[$glti]]=="no") {
	$ExtraIgnores = " AND \"Closed\"<>3"; }
$gltfoquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."topics\" WHERE \"ForumID\"=%i".$ExtraIgnores.$ForumIgnoreList4." ORDER BY \"LastUpdate\" DESC LIMIT 1", array($gltf[$glti]));
$gltforesult=sql_query($gltfoquery,$SQLStat);
$gltfonum=sql_num_rows($gltforesult);
if($gltfonum>0) {
$NewUpdateTime=sql_result($gltforesult,0,"LastUpdate");
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
$gltnum=sql_num_rows($gltresult);
if($gltnum>0){
$TopicID=sql_result($gltresult,0,"id");
$TopicName=sql_result($gltresult,0,"TopicName");
$NumReplys=sql_result($gltresult,0,"NumReply");
$NumPages = null; $NumRPosts = $NumReplys + 1;
if(!isset($Settings['max_posts'])) { $Settings['max_posts'] = 10; }
if($NumRPosts>$Settings['max_posts']) {
$NumPages = ceil($NumRPosts/$Settings['max_posts']); }
if($NumRPosts<=$Settings['max_posts']) { $NumPages = 1; }
$TopicName1 = pre_substr($TopicName,0,20);
$oldtopicname=$TopicName;
if (pre_strlen($TopicName)>20) {
$TopicName1 = $TopicName1."..."; $TopicName=$TopicName1; }
$glrquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."posts\" WHERE \"TopicID\"=%i ORDER BY \"TimeStamp\" DESC LIMIT 1", array($TopicID));
$glrresult=sql_query($glrquery,$SQLStat);
$glrnum=sql_num_rows($glrresult);
if($glrnum>0){
$ReplyID=sql_result($glrresult,0,"id");
$UsersID=sql_result($glrresult,0,"UserID");
$GuestsName=sql_result($glrresult,0,"GuestName");
$TimeStamp=sql_result($glrresult,0,"TimeStamp");
$TimeStamp=GMTimeChange("F j Y, g:i a",$TimeStamp,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
sql_free_result($glrresult); }
$PreUsersName = GetUserName($UsersID,$Settings['sqltable'],$SQLStat);
if($PreUsersName['Name']===null) { $UsersID = -1;
$PreUsersName = GetUserName($UsersID,$Settings['sqltable'],$SQLStat); }
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
if($LastTopic==null) { $LastTopic = "&nbsp;<br />&nbsp;<br />&nbsp;"; }
sql_free_result($gltresult); }
if ($ForumType=="redirect") { $LastTopic="&nbsp;<br />Redirects: ".$NumRedirects."<br />&nbsp;"; }
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
<tr class="TableRow3" id="Forum<?php echo $ForumID; ?>">
<td<?php echo $ForumClass[1]; ?>><div class="forumicon">
<?php echo $PreForum; ?></div></td>
<td<?php echo $ForumClass[2]; ?>><div class="forumname"><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=view&id=".$ForumID.$ExStr,$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"<?php if($ForumType=="redirect") { echo " onclick=\"window.open(this.href);return false;\""; } ?>><?php echo $ForumName; ?></a></div>
<div class="forumdescription">
<?php echo $ForumDescription; ?><br />
<?php echo $sflist; ?></div></td>
<td<?php echo $ForumClass[3]; ?>style="text-align: center;"><?php echo $NumTopics; ?></td>
<td<?php echo $ForumClass[4]; ?>style="text-align: center;"><?php echo $NumPosts; ?></td>
<td<?php echo $ForumClass[5]; ?>><?php echo $LastTopic; ?></td>
</tr>
<?php } ++$i; } sql_free_result($result);
if($num>=1) { ?>
<tr id="CatEnd<?php echo $CategoryID; ?>" class="TableRow4">
<td class="TableColumn4" colspan="5">&nbsp;</td>
</tr>
</table></div>
<div class="DivCategories">&nbsp;</div>
<?php } } }
sql_free_result($preresult); ?>
