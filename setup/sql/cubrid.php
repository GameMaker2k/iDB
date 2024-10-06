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
    iDB Installer made by Game Maker 2k - http://idb.berlios.net/

    $FileInfo: cubrid.php - Last Update: 8/30/2024 SVN 1064 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "cubrid.php" || $File3Name == "/cubrid.php") {
    require('index.php');
    exit();
}
if (!isset($SetupDir['setup'])) {
    $SetupDir['setup'] = "setup/";
}
if (!isset($SetupDir['convert'])) {
    $SetupDir['convert'] = "setup/convert/";
}
//$query=sql_pre_query("ALTER DATABASE \"".$_POST['DatabaseName']."\" DEFAULT CHARACTER SET ".$Settings['sql_charset']." COLLATE ".$Settings['sql_collate'].";", null);
//sql_query($query,$SQLStat);
$parsestr = parse_url($YourWebsite);
if (!filter_var($parsestr['host'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6) || $parsestr['host'] == "localhost") {
    $GuestLocalIP = gethostbyname($parsestr['host']);
} else {
    $GuestLocalIP = $parsestr['host'];
}
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."categories\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"OrderID\" INTEGER NOT NULL default '0',\n".
"  \"Name\" VARCHAR(150) NOT NULL default '',\n".
"  \"ShowCategory\" VARCHAR(5) NOT NULL default '',\n".
"  \"CategoryType\" VARCHAR(15) NOT NULL default '',\n".
"  \"SubShowForums\" VARCHAR(5) NOT NULL default '',\n".
"  \"InSubCategory\" INTEGER NOT NULL default '0',\n".
"  \"PostCountView\" INTEGER NOT NULL default '0',\n".
"  \"KarmaCountView\" INTEGER NOT NULL default '0',\n".
"  \"Description\" STRING NOT NULL\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."catpermissions\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"PermissionID\" INTEGER NOT NULL default '0',\n".
"  \"Name\" VARCHAR(150) NOT NULL default '',\n".
"  \"CategoryID\" INTEGER NOT NULL default '0',\n".
"  \"CanViewCategory\" VARCHAR(5) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."events\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"UserID\" INTEGER NOT NULL default '0',\n".
"  \"GuestName\" VARCHAR(150) NOT NULL default '',\n".
"  \"EventName\" VARCHAR(150) NOT NULL default '',\n".
"  \"EventText\" STRING NOT NULL,\n".
"  \"TimeStamp\" INTEGER NOT NULL default '0',\n".
"  \"TimeStampEnd\" INTEGER NOT NULL default '0',\n".
"  \"EventMonth\" INTEGER NOT NULL default '0',\n".
"  \"EventMonthEnd\" INTEGER NOT NULL default '0',\n".
"  \"EventDay\" INTEGER NOT NULL default '0',\n".
"  \"EventDayEnd\" INTEGER NOT NULL default '0',\n".
"  \"EventYear\" INTEGER NOT NULL default '0',\n".
"  \"EventYearEnd\" INTEGER NOT NULL default '0',\n".
"  \"IP\" varchar(64) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."forums\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"CategoryID\" INTEGER NOT NULL default '0',\n".
"  \"OrderID\" INTEGER NOT NULL default '0',\n".
"  \"Name\" VARCHAR(150) NOT NULL default '',\n".
"  \"ShowForum\" VARCHAR(5) NOT NULL default '',\n".
"  \"ForumType\" VARCHAR(15) NOT NULL default '',\n".
"  \"InSubForum\" INTEGER NOT NULL default '0',\n".
"  \"RedirectURL\" STRING NOT NULL,\n".
"  \"Redirects\" INTEGER NOT NULL default '0',\n".
"  \"NumViews\" INTEGER NOT NULL default '0',\n".
"  \"Description\" STRING NOT NULL,\n".
"  \"PostCountAdd\" VARCHAR(15) NOT NULL default '',\n".
"  \"PostCountView\" INTEGER NOT NULL default '0',\n".
"  \"KarmaCountView\" INTEGER NOT NULL default '0',\n".
"  \"CanHaveTopics\" VARCHAR(5) NOT NULL default '',\n".
"  \"HotTopicPosts\" INTEGER NOT NULL default '0',\n".
"  \"NumPosts\" INTEGER NOT NULL default '0',\n".
"  \"NumTopics\" INTEGER NOT NULL default '0'\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."groups\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"Name\" VARCHAR(150) NOT NULL default '' UNIQUE,\n".
"  \"PermissionID\" INTEGER NOT NULL default '0',\n".
"  \"NamePrefix\" VARCHAR(150) NOT NULL default '',\n".
"  \"NameSuffix\" VARCHAR(150) NOT NULL default '',\n".
"  \"CanViewBoard\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanViewOffLine\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanEditProfile\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanAddEvents\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanPM\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanSearch\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanExecPHP\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanDoHTML\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanUseBBTags\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanModForum\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanViewIPAddress\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanViewUserAgent\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanViewAnonymous\" VARCHAR(5) NOT NULL default '',\n".
"  \"FloodControl\" INTEGER NOT NULL default '0',\n".
"  \"SearchFlood\" INTEGER NOT NULL default '0',\n".
"  \"PromoteTo\" INTEGER NOT NULL default '0',\n".
"  \"PromotePosts\" INTEGER NOT NULL default '0',\n".
"  \"PromoteKarma\" INTEGER NOT NULL default '0',\n".
"  \"DemoteTo\" INTEGER NOT NULL default '0',\n".
"  \"DemotePosts\" INTEGER NOT NULL default '0',\n".
"  \"DemoteKarma\" INTEGER NOT NULL default '0',\n".
"  \"HasModCP\" VARCHAR(5) NOT NULL default '',\n".
"  \"HasAdminCP\" VARCHAR(5) NOT NULL default '',\n".
"  \"ViewDBInfo\" VARCHAR(5) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."ranks\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"Name\" VARCHAR(150) NOT NULL default '' UNIQUE,\n".
"  \"PromoteTo\" INTEGER NOT NULL default '0',\n".
"  \"PromotePosts\" INTEGER NOT NULL default '0',\n".
"  \"PromoteKarma\" INTEGER NOT NULL default '0',\n".
"  \"DemoteTo\" INTEGER NOT NULL default '0',\n".
"  \"DemotePosts\" INTEGER NOT NULL default '0',\n".
"  \"DemoteKarma\" INTEGER NOT NULL default '0'\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."levels\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"Name\" VARCHAR(150) NOT NULL default '' UNIQUE,\n".
"  \"PromoteTo\" INTEGER NOT NULL default '0',\n".
"  \"PromotePosts\" INTEGER NOT NULL default '0',\n".
"  \"PromoteKarma\" INTEGER NOT NULL default '0',\n".
"  \"DemoteTo\" INTEGER NOT NULL default '0',\n".
"  \"DemotePosts\" INTEGER NOT NULL default '0',\n".
"  \"DemoteKarma\" INTEGER NOT NULL default '0'\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."members\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"Name\" VARCHAR(150) NOT NULL default '' UNIQUE,\n".
"  \"Handle\" VARCHAR(150) NOT NULL default '' UNIQUE,\n".
"  \"UserPassword\" VARCHAR(256) NOT NULL default '',\n".
"  \"HashType\" VARCHAR(50) NOT NULL default '',\n".
"  \"Email\" VARCHAR(256) NOT NULL default '' UNIQUE,\n".
"  \"GroupID\" INTEGER NOT NULL default '0',\n".
"  \"LevelID\" INTEGER NOT NULL default '0',\n".
"  \"RankID\" INTEGER NOT NULL default '0',\n".
"  \"Validated\" VARCHAR(20) NOT NULL default '',\n".
"  \"HiddenMember\" VARCHAR(20) NOT NULL default '',\n".
"  \"WarnLevel\" INTEGER NOT NULL default '0',\n".
"  \"Interests\" STRING NOT NULL default '',\n".
"  \"Title\" VARCHAR(150) NOT NULL default '',\n".
"  \"Joined\" INTEGER NOT NULL default '0',\n".
"  \"LastActive\" INTEGER NOT NULL default '0',\n".
"  \"LastLogin\" INTEGER NOT NULL default '0',\n".
"  \"LastPostTime\" INTEGER NOT NULL default '0',\n".
"  \"BanTime\" INTEGER NOT NULL default '0',\n".
"  \"BirthDay\" INTEGER NOT NULL default '0',\n".
"  \"BirthMonth\" INTEGER NOT NULL default '0',\n".
"  \"BirthYear\" INTEGER NOT NULL default '0',\n".
"  \"Signature\" STRING NOT NULL,\n".
"  \"Notes\" STRING NOT NULL,\n".
"  \"Bio\" STRING NOT NULL,\n".
"  \"Avatar\" VARCHAR(150) NOT NULL default '',\n".
"  \"AvatarSize\" VARCHAR(10) NOT NULL default '',\n".
"  \"Website\" VARCHAR(150) NOT NULL default '',\n".
"  \"Location\" VARCHAR(150) NOT NULL default '',\n".
"  \"Gender\" VARCHAR(15) NOT NULL default '',\n".
"  \"PostCount\" INTEGER NOT NULL default '0',\n".
"  \"Karma\" INTEGER NOT NULL default '0',\n".
"  \"KarmaUpdate\" INTEGER NOT NULL default '0',\n".
"  \"RepliesPerPage\" INTEGER NOT NULL default '0',\n".
"  \"TopicsPerPage\" INTEGER NOT NULL default '0',\n".
"  \"MessagesPerPage\" INTEGER NOT NULL default '0',\n".
"  \"TimeZone\" VARCHAR(256) NOT NULL default '',\n".
"  \"DateFormat\" VARCHAR(15) NOT NULL default '',\n".
"  \"TimeFormat\" VARCHAR(15) NOT NULL default '',\n".
"  \"UseTheme\" VARCHAR(32) NOT NULL default '',\n".
"  \"IgnoreSignitures\" VARCHAR(32) NOT NULL default '',\n".
"  \"IgnoreAdvatars\" VARCHAR(32) NOT NULL default '',\n".
"  \"IgnoreUsers\" VARCHAR(32) NOT NULL default '',\n".
"  \"IP\" VARCHAR(64) NOT NULL default '',\n".
"  \"Salt\" VARCHAR(50) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."mempermissions\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"PermissionID\" INTEGER NOT NULL default '0',\n".
"  \"CanViewBoard\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanViewOffLine\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanEditProfile\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanAddEvents\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanPM\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanSearch\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanExecPHP\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanDoHTML\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanUseBBTags\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanModForum\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanViewIPAddress\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanViewUserAgent\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanViewAnonymous\" VARCHAR(5) NOT NULL default '',\n".
"  \"FloodControl\" INTEGER NOT NULL default '0',\n".
"  \"SearchFlood\" INTEGER NOT NULL default '0',\n".
"  \"HasModCP\" VARCHAR(5) NOT NULL default '',\n".
"  \"HasAdminCP\" VARCHAR(5) NOT NULL default '',\n".
"  \"ViewDBInfo\" VARCHAR(5) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."messenger\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"DiscussionID\" INTEGER NOT NULL default '0',\n".
"  \"SenderID\" INTEGER NOT NULL default '0',\n".
"  \"ReciverID\" INTEGER NOT NULL default '0',\n".
"  \"GuestName\" VARCHAR(150) NOT NULL default '',\n".
"  \"MessageTitle\" VARCHAR(150) NOT NULL default '',\n".
"  \"MessageText\" STRING NOT NULL,\n".
"  \"Description\" STRING NOT NULL,\n".
"  \"DateSend\" INTEGER NOT NULL default '0',\n".
"  \"Read\" INTEGER NOT NULL default '0',\n".
"  \"IP\" VARCHAR(64) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."permissions\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"PermissionID\" INTEGER NOT NULL default '0',\n".
"  \"Name\" VARCHAR(150) NOT NULL default '',\n".
"  \"ForumID\" INTEGER NOT NULL default '0',\n".
"  \"CanViewForum\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanMakePolls\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanMakeTopics\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanMakeReplys\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanMakeReplysCT\" VARCHAR(5) NOT NULL default '',\n".
"  \"HideEditPostInfo\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanEditTopics\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanEditTopicsCT\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanEditReplys\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanEditReplysCT\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanDeleteTopics\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanDeleteTopicsCT\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanDeleteReplys\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanDeleteReplysCT\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanDoublePost\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanDoublePostCT\" VARCHAR(5) NOT NULL default '',\n".
"  \"GotoEditPost\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanCloseTopics\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanCloseTopicsCT\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanPinTopics\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanPinTopicsCT\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanExecPHP\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanDoHTML\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanUseBBTags\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanModForum\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanReportPost\" VARCHAR(5) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."polls\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"UserID\" INTEGER NOT NULL default '0',\n".
"  \"GuestName\" VARCHAR(150) NOT NULL default '',\n".
"  \"PollValues\" STRING NOT NULL,\n".
"  \"Description\" STRING NOT NULL,\n".
"  \"UsersVoted\" STRING NOT NULL,\n".
"  \"IP\" VARCHAR(64) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."posts\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"TopicID\" INTEGER NOT NULL default '0',\n".
"  \"ForumID\" INTEGER NOT NULL default '0',\n".
"  \"CategoryID\" INTEGER NOT NULL default '0',\n".
"  \"ReplyID\" INTEGER NOT NULL default '0',\n".
"  \"UserID\" INTEGER NOT NULL default '0',\n".
"  \"GuestName\" VARCHAR(150) NOT NULL default '',\n".
"  \"TimeStamp\" INTEGER NOT NULL default '0',\n".
"  \"LastUpdate\" INTEGER NOT NULL default '0',\n".
"  \"EditUser\" INTEGER NOT NULL default '0',\n".
"  \"EditUserName\" VARCHAR(150) NOT NULL default '',\n".
"  \"Post\" STRING NOT NULL,\n".
"  \"Description\" STRING NOT NULL,\n".
"  \"IP\" VARCHAR(64) NOT NULL default '',\n".
"  \"EditIP\" VARCHAR(64) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."restrictedwords\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"Word\" STRING NOT NULL,\n".
"  \"RestrictedUserName\" VARCHAR(5) NOT NULL default '',\n".
"  \"RestrictedTopicName\" VARCHAR(5) NOT NULL default '',\n".
"  \"RestrictedEventName\" VARCHAR(5) NOT NULL default '',\n".
"  \"RestrictedMessageName\" VARCHAR(5) NOT NULL default '',\n".
"  \"CaseInsensitive\" VARCHAR(5) NOT NULL default '',\n".
"  \"WholeWord\" VARCHAR(5) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."sessions\" (\n".
"  \"session_id\" VARCHAR(250) NOT NULL default '' PRIMARY KEY,\n".
"  \"session_data\" STRING NOT NULL,\n".
"  \"serialized_data\" STRING NOT NULL,\n".
"  \"user_agent\" STRING NOT NULL,\n".
"  \"client_hints\" STRING NOT NULL,\n".
"  \"ip_address\" VARCHAR(64) NOT NULL default '',\n".
"  \"expires\" INTEGER NOT NULL default '0'\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."smileys\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"FileName\" STRING NOT NULL,\n".
"  \"SmileName\" STRING NOT NULL,\n".
"  \"SmileText\" STRING NOT NULL,\n".
"  \"EmojiText\" STRING NOT NULL,\n".
"  \"Directory\" STRING NOT NULL,\n".
"  \"Display\" VARCHAR(5) NOT NULL default '',\n".
"  \"ReplaceCI\" VARCHAR(5) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
/*
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."tagboard\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"UserID\" INTEGER NOT NULL default '0',\n".
"  \"GuestName\" VARCHAR(150) NOT NULL default '',\n".
"  \"TimeStamp\" INTEGER NOT NULL default '0',\n".
"  \"Post\" STRING NOT NULL,\n".
"  \"IP\" VARCHAR(64) NOT NULL default ''\n".
");", null);
sql_query($query,$SQLStat);
*/
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."themes\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"Name\" VARCHAR(32) NOT NULL default '' UNIQUE,\n".
"  \"ThemeName\" VARCHAR(150) NOT NULL default '',\n".
"  \"ThemeMaker\" VARCHAR(150) NOT NULL default '',\n".
"  \"ThemeVersion\" VARCHAR(150) NOT NULL default '',\n".
"  \"ThemeVersionType\" VARCHAR(150) NOT NULL default '',\n".
"  \"ThemeSubVersion\" VARCHAR(150) NOT NULL default '',\n".
"  \"MakerURL\" VARCHAR(150) NOT NULL default '',\n".
"  \"CopyRight\" VARCHAR(150) NOT NULL default '',\n".
"  \"WrapperString\" STRING NOT NULL,\n".
"  \"CSS\" STRING NOT NULL,\n".
"  \"CSSType\" VARCHAR(150) NOT NULL default '',\n".
"  \"FavIcon\" VARCHAR(150) NOT NULL default '',\n".
"  \"OpenGraph\" VARCHAR(150) NOT NULL default '',\n".
"  \"TableStyle\" VARCHAR(150) NOT NULL default '',\n".
"  \"MiniPageAltStyle\" VARCHAR(150) NOT NULL default '',\n".
"  \"PreLogo\" VARCHAR(150) NOT NULL default '',\n".
"  \"Logo\" VARCHAR(150) NOT NULL default '',\n".
"  \"LogoStyle\" VARCHAR(150) NOT NULL default '',\n".
"  \"SubLogo\" VARCHAR(150) NOT NULL default '',\n".
"  \"TopicIcon\" VARCHAR(150) NOT NULL default '',\n".
"  \"MovedTopicIcon\" VARCHAR(150) NOT NULL default '',\n".
"  \"HotTopic\" VARCHAR(150) NOT NULL default '',\n".
"  \"MovedHotTopic\" VARCHAR(150) NOT NULL default '',\n".
"  \"PinTopic\" VARCHAR(150) NOT NULL default '',\n".
"  \"AnnouncementTopic\" VARCHAR(150) NOT NULL default '',\n".
"  \"MovedPinTopic\" VARCHAR(150) NOT NULL default '',\n".
"  \"HotPinTopic\" VARCHAR(150) NOT NULL default '',\n".
"  \"MovedHotPinTopic\" VARCHAR(150) NOT NULL default '',\n".
"  \"ClosedTopic\" VARCHAR(150) NOT NULL default '',\n".
"  \"MovedClosedTopic\" VARCHAR(150) NOT NULL default '',\n".
"  \"HotClosedTopic\" VARCHAR(150) NOT NULL default '',\n".
"  \"MovedHotClosedTopic\" VARCHAR(150) NOT NULL default '',\n".
"  \"PinClosedTopic\" VARCHAR(150) NOT NULL default '',\n".
"  \"MovedPinClosedTopic\" VARCHAR(150) NOT NULL default '',\n".
"  \"HotPinClosedTopic\" VARCHAR(150) NOT NULL default '',\n".
"  \"MovedHotPinClosedTopic\" VARCHAR(150) NOT NULL default '',\n".
"  \"MessageRead\" VARCHAR(150) NOT NULL default '',\n".
"  \"MessageUnread\" VARCHAR(150) NOT NULL default '',\n".
"  \"Profile\" VARCHAR(150) NOT NULL default '',\n".
"  \"WWW\" VARCHAR(150) NOT NULL default '',\n".
"  \"PM\" VARCHAR(150) NOT NULL default '',\n".
"  \"TopicLayout\" VARCHAR(150) NOT NULL default '',\n".
"  \"AddReply\" VARCHAR(150) NOT NULL default '',\n".
"  \"FastReply\" VARCHAR(150) NOT NULL default '',\n".
"  \"NewTopic\" VARCHAR(150) NOT NULL default '',\n".
"  \"QuoteReply\" VARCHAR(150) NOT NULL default '',\n".
"  \"EditReply\" VARCHAR(150) NOT NULL default '',\n".
"  \"DeleteReply\" VARCHAR(150) NOT NULL default '',\n".
"  \"Report\" VARCHAR(150) NOT NULL default '',\n".
"  \"LineDivider\" VARCHAR(150) NOT NULL default '',\n".
"  \"ButtonDivider\" VARCHAR(150) NOT NULL default '',\n".
"  \"LineDividerTopic\" VARCHAR(150) NOT NULL default '',\n".
"  \"TitleDivider\" VARCHAR(150) NOT NULL default '',\n".
"  \"ForumStyle\" VARCHAR(150) NOT NULL default '',\n".
"  \"ForumIcon\" VARCHAR(150) NOT NULL default '',\n".
"  \"SubForumIcon\" VARCHAR(150) NOT NULL default '',\n".
"  \"RedirectIcon\" VARCHAR(150) NOT NULL default '',\n".
"  \"TitleIcon\" VARCHAR(150) NOT NULL default '',\n".
"  \"NavLinkIcon\" VARCHAR(150) NOT NULL default '',\n".
"  \"NavLinkDivider\" VARCHAR(150) NOT NULL default '',\n".
"  \"BoardStatsIcon\" VARCHAR(150) NOT NULL default '',\n".
"  \"MemberStatsIcon\" VARCHAR(150) NOT NULL default '',\n".
"  \"BirthdayStatsIcon\" VARCHAR(150) NOT NULL default '',\n".
"  \"EventStatsIcon\" VARCHAR(150) NOT NULL default '',\n".
"  \"OnlineStatsIcon\" VARCHAR(150) NOT NULL default '',\n".
"  \"NoAvatar\" VARCHAR(150) NOT NULL default '',\n".
"  \"NoAvatarSize\" VARCHAR(150) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."topics\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"PollID\" INTEGER NOT NULL default '0',\n".
"  \"ForumID\" INTEGER NOT NULL default '0',\n".
"  \"CategoryID\" INTEGER NOT NULL default '0',\n".
"  \"OldForumID\" INTEGER NOT NULL default '0',\n".
"  \"OldCategoryID\" INTEGER NOT NULL default '0',\n".
"  \"UserID\" INTEGER NOT NULL default '0',\n".
"  \"GuestName\" VARCHAR(150) NOT NULL default '',\n".
"  \"TimeStamp\" INTEGER NOT NULL default '0',\n".
"  \"LastUpdate\" INTEGER NOT NULL default '0',\n".
"  \"TopicName\" VARCHAR(150) NOT NULL default '',\n".
"  \"Description\" STRING NOT NULL,\n".
"  \"NumReply\" INTEGER NOT NULL default '0',\n".
"  \"NumViews\" INTEGER NOT NULL default '0',\n".
"  \"Pinned\" INTEGER NOT NULL default '0',\n".
"  \"Closed\" INTEGER NOT NULL default '0'\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."wordfilter\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"FilterWord\" STRING NOT NULL,\n".
"  \"Replacement\" STRING NOT NULL,\n".
"  \"CaseInsensitive\" VARCHAR(5) NOT NULL default '',\n".
"  \"WholeWord\" VARCHAR(5) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
$TableChCk = array("categories", "catpermissions", "events", "forums", "groups", "levels", "members", "mempermissions", "messenger", "permissions", "polls", "posts", "ranks", "restrictedwords", "sessions", "smileys", "themes", "topics", "wordfilter");
$TablePreFix = $_POST['tableprefix'];
function add_prefix($tarray)
{
    global $TablePreFix;
    return $TablePreFix.$tarray;
}
$TableChCk = array_map("add_prefix", $TableChCk);
$tcount = count($TableChCk);
$ti = 0;
while ($ti < $tcount) {
    $OptimizeTea = sql_query(sql_pre_query("UPDATE STATISTICS ON \"".$TableChCk[$ti]."\"", null), $SQLStat);
    ++$ti;
}
