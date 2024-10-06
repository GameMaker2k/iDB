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

    $FileInfo: sqlite.php - Last Update: 8/30/2024 SVN 1064 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "sqlite.php" || $File3Name == "/sqlite.php") {
    require('index.php');
    exit();
}
if (!isset($SetupDir['setup'])) {
    $SetupDir['setup'] = "setup/";
}
if (!isset($SetupDir['convert'])) {
    $SetupDir['convert'] = "setup/convert/";
}
/*
$query=sql_pre_query("ALTER DATABASE \"".$_POST['DatabaseName']."\" DEFAULT CHARACTER SET ".$SQLCharset." COLLATE ".$SQLCollate.";", null);
sql_query($query,$SQLStat);
*/
$parsestr = parse_url($YourWebsite);
if (!filter_var($parsestr['host'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6) || $parsestr['host'] == "localhost") {
    $GuestLocalIP = gethostbyname($parsestr['host']);
} else {
    $GuestLocalIP = $parsestr['host'];
}
$query = sql_pre_query("PRAGMA auto_vacuum = INCREMENTAL;", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("PRAGMA foreign_keys = 1;", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("PRAGMA locking_mode = NORMAL;", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("PRAGMA synchronous = NORMAL;", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("PRAGMA journal_size_limit = 1048576;", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("PRAGMA mmap_size = 268435456;", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("PRAGMA journal_mode = WAL;", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("PRAGMA fullfsync = OFF;", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("PRAGMA temp_store = MEMORY;", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."categories\" (\n".
"  \"id\" INTEGER PRIMARY KEY NOT NULL,\n".
"  \"OrderID\" INTEGER NOT NULL default '0',\n".
"  \"Name\" VARCHAR(150) NOT NULL default '',\n".
"  \"ShowCategory\" VARCHAR(5) NOT NULL default '',\n".
"  \"CategoryType\" VARCHAR(15) NOT NULL default '',\n".
"  \"SubShowForums\" VARCHAR(5) NOT NULL default '',\n".
"  \"InSubCategory\" INTEGER NOT NULL default '0',\n".
"  \"PostCountView\" INTEGER NOT NULL default '0',\n".
"  \"KarmaCountView\" INTEGER NOT NULL default '0',\n".
"  \"Description\" TEXT NOT NULL\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."catpermissions\" (\n".
"  \"id\" INTEGER PRIMARY KEY NOT NULL,\n".
"  \"PermissionID\" INTEGER NOT NULL default '0',\n".
"  \"Name\" VARCHAR(150) NOT NULL default '',\n".
"  \"CategoryID\" INTEGER NOT NULL default '0',\n".
"  \"CanViewCategory\" VARCHAR(5) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."events\" (\n".
"  \"id\" INTEGER PRIMARY KEY NOT NULL,\n".
"  \"UserID\" INTEGER NOT NULL default '0',\n".
"  \"GuestName\" VARCHAR(150) NOT NULL default '',\n".
"  \"EventName\" VARCHAR(150) NOT NULL default '',\n".
"  \"EventText\" TEXT NOT NULL,\n".
"  \"TimeStamp\" INTEGER NOT NULL default '0',\n".
"  \"TimeStampEnd\" INTEGER NOT NULL default '0',\n".
"  \"EventMonth\" INTEGER NOT NULL default '0',\n".
"  \"EventMonthEnd\" INTEGER NOT NULL default '0',\n".
"  \"EventDay\" INTEGER NOT NULL default '0',\n".
"  \"EventDayEnd\" INTEGER NOT NULL default '0',\n".
"  \"EventYear\" INTEGER NOT NULL default '0',\n".
"  \"EventYearEnd\" INTEGER NOT NULL default '0',\n".
"  \"IP\" VARCHAR(64) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."forums\" (\n".
"  \"id\" INTEGER PRIMARY KEY NOT NULL,\n".
"  \"CategoryID\" INTEGER NOT NULL default '0',\n".
"  \"OrderID\" INTEGER NOT NULL default '0',\n".
"  \"Name\" VARCHAR(150) NOT NULL default '',\n".
"  \"ShowForum\" VARCHAR(5) NOT NULL default '',\n".
"  \"ForumType\" VARCHAR(15) NOT NULL default '',\n".
"  \"InSubForum\" INTEGER NOT NULL default '0',\n".
"  \"RedirectURL\" TEXT NOT NULL,\n".
"  \"Redirects\" INTEGER NOT NULL default '0',\n".
"  \"NumViews\" INTEGER NOT NULL default '0',\n".
"  \"Description\" TEXT NOT NULL,\n".
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
"  \"id\" INTEGER PRIMARY KEY NOT NULL,\n".
"  \"Name\" VARCHAR(150) UNIQUE NOT NULL default '',\n".
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
"  \"id\" INTEGER PRIMARY KEY NOT NULL,\n".
"  \"Name\" VARCHAR(150) UNIQUE NOT NULL default '',\n".
"  \"PromoteTo\" INTEGER NOT NULL default '0',\n".
"  \"PromotePosts\" INTEGER NOT NULL default '0',\n".
"  \"PromoteKarma\" INTEGER NOT NULL default '0',\n".
"  \"DemoteTo\" INTEGER NOT NULL default '0',\n".
"  \"DemotePosts\" INTEGER NOT NULL default '0',\n".
"  \"DemoteKarma\" INTEGER NOT NULL default '0'\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."levels\" (\n".
"  \"id\" INTEGER PRIMARY KEY NOT NULL,\n".
"  \"Name\" VARCHAR(150) UNIQUE NOT NULL default '',\n".
"  \"PromoteTo\" INTEGER NOT NULL default '0',\n".
"  \"PromotePosts\" INTEGER NOT NULL default '0',\n".
"  \"PromoteKarma\" INTEGER NOT NULL default '0',\n".
"  \"DemoteTo\" INTEGER NOT NULL default '0',\n".
"  \"DemotePosts\" INTEGER NOT NULL default '0',\n".
"  \"DemoteKarma\" INTEGER NOT NULL default '0'\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."members\" (\n".
"  \"id\" INTEGER PRIMARY KEY NOT NULL,\n".
"  \"Name\" VARCHAR(150) UNIQUE NOT NULL default '',\n".
"  \"Handle\" VARCHAR(150) UNIQUE NOT NULL default '',\n".
"  \"UserPassword\" VARCHAR(256) NOT NULL default '',\n".
"  \"HashType\" VARCHAR(50) NOT NULL default '',\n".
"  \"Email\" VARCHAR(256) UNIQUE NOT NULL default '',\n".
"  \"GroupID\" INTEGER NOT NULL default '0',\n".
"  \"LevelID\" INTEGER NOT NULL default '0',\n".
"  \"RankID\" INTEGER NOT NULL default '0',\n".
"  \"Validated\" VARCHAR(20) NOT NULL default '',\n".
"  \"HiddenMember\" VARCHAR(20) NOT NULL default '',\n".
"  \"WarnLevel\" INTEGER NOT NULL default '0',\n".
"  \"Interests\" TEXT NOT NULL default '',\n".
"  \"Title\" VARCHAR(150) NOT NULL default '',\n".
"  \"Joined\" INTEGER NOT NULL default '0',\n".
"  \"LastActive\" INTEGER NOT NULL default '0',\n".
"  \"LastLogin\" INTEGER NOT NULL default '0',\n".
"  \"LastPostTime\" INTEGER NOT NULL default '0',\n".
"  \"BanTime\" INTEGER NOT NULL default '0',\n".
"  \"BirthDay\" INTEGER NOT NULL default '0',\n".
"  \"BirthMonth\" INTEGER NOT NULL default '0',\n".
"  \"BirthYear\" INTEGER NOT NULL default '0',\n".
"  \"Signature\" TEXT NOT NULL,\n".
"  \"Notes\" TEXT NOT NULL,\n".
"  \"Bio\" TEXT NOT NULL,\n".
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
"  \"id\" INTEGER PRIMARY KEY NOT NULL,\n".
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
"  \"id\" INTEGER PRIMARY KEY NOT NULL,\n".
"  \"DiscussionID\" INTEGER NOT NULL default '0',\n".
"  \"SenderID\" INTEGER NOT NULL default '0',\n".
"  \"ReciverID\" INTEGER NOT NULL default '0',\n".
"  \"GuestName\" VARCHAR(150) NOT NULL default '',\n".
"  \"MessageTitle\" VARCHAR(150) NOT NULL default '',\n".
"  \"MessageText\" TEXT NOT NULL,\n".
"  \"Description\" TEXT NOT NULL,\n".
"  \"DateSend\" INTEGER NOT NULL default '0',\n".
"  \"Read\" INTEGER NOT NULL default '0',\n".
"  \"IP\" VARCHAR(64) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."permissions\" (\n".
"  \"id\" INTEGER PRIMARY KEY NOT NULL,\n".
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
"  \"id\" INTEGER PRIMARY KEY NOT NULL,\n".
"  \"UserID\" INTEGER NOT NULL default '0',\n".
"  \"GuestName\" VARCHAR(150) NOT NULL default '',\n".
"  \"PollValues\" TEXT NOT NULL,\n".
"  \"Description\" TEXT NOT NULL,\n".
"  \"UsersVoted\" TEXT NOT NULL,\n".
"  \"IP\" VARCHAR(64) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."posts\" (\n".
"  \"id\" INTEGER PRIMARY KEY NOT NULL,\n".
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
"  \"Post\" TEXT NOT NULL,\n".
"  \"Description\" TEXT NOT NULL,\n".
"  \"IP\" VARCHAR(64) NOT NULL default '',\n".
"  \"EditIP\" VARCHAR(64) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."restrictedwords\" (\n".
"  \"id\" INTEGER PRIMARY KEY NOT NULL,\n".
"  \"Word\" TEXT NOT NULL,\n".
"  \"RestrictedUserName\" VARCHAR(5) NOT NULL default '',\n".
"  \"RestrictedTopicName\" VARCHAR(5) NOT NULL default '',\n".
"  \"RestrictedEventName\" VARCHAR(5) NOT NULL default '',\n".
"  \"RestrictedMessageName\" VARCHAR(5) NOT NULL default '',\n".
"  \"CaseInsensitive\" VARCHAR(5) NOT NULL default '',\n".
"  \"WholeWord\" VARCHAR(5) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."sessions\" (\n".
"  \"session_id\" VARCHAR(250) PRIMARY KEY NOT NULL default '',\n".
"  \"session_data\" TEXT NOT NULL,\n".
"  \"serialized_data\" TEXT NOT NULL,\n".
"  \"user_agent\" TEXT NOT NULL,\n".
"  \"client_hints\" TEXT NOT NULL,\n".
"  \"ip_address\" VARCHAR(64) NOT NULL default '',\n".
"  \"expires\" INTEGER NOT NULL default '0'\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."smileys\" (\n".
"  \"id\" INTEGER PRIMARY KEY NOT NULL,\n".
"  \"FileName\" TEXT NOT NULL,\n".
"  \"SmileName\" TEXT NOT NULL,\n".
"  \"SmileText\" TEXT NOT NULL,\n".
"  \"EmojiText\" TEXT NOT NULL,\n".
"  \"Directory\" TEXT NOT NULL,\n".
"  \"Display\" VARCHAR(5) NOT NULL default '',\n".
"  \"ReplaceCI\" VARCHAR(5) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
/*
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."tagboard\" (\n".
"  \"id\" INTEGER PRIMARY KEY NOT NULL,\n".
"  \"UserID\" INTEGER NOT NULL default '0',\n".
"  \"GuestName\" VARCHAR(150) NOT NULL default '',\n".
"  \"TimeStamp\" INTEGER NOT NULL default '0',\n".
"  \"Post\" TEXT NOT NULL,\n".
"  \"IP\" VARCHAR(64) NOT NULL default ''
");", null);
sql_query($query,$SQLStat);
*/
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."themes\" (\n".
"  \"id\" INTEGER PRIMARY KEY NOT NULL,\n".
"  \"Name\" VARCHAR(32) UNIQUE NOT NULL default '',\n".
"  \"ThemeName\" VARCHAR(150) NOT NULL default '',\n".
"  \"ThemeMaker\" VARCHAR(150) NOT NULL default '',\n".
"  \"ThemeVersion\" VARCHAR(150) NOT NULL default '',\n".
"  \"ThemeVersionType\" VARCHAR(150) NOT NULL default '',\n".
"  \"ThemeSubVersion\" VARCHAR(150) NOT NULL default '',\n".
"  \"MakerURL\" VARCHAR(150) NOT NULL default '',\n".
"  \"CopyRight\" VARCHAR(150) NOT NULL default '',\n".
"  \"WrapperString\" TEXT NOT NULL default '',\n".
"  \"CSS\" TEXT NOT NULL,\n".
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
"  \"id\" INTEGER PRIMARY KEY NOT NULL,\n".
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
"  \"Description\" TEXT NOT NULL,\n".
"  \"NumReply\" INTEGER NOT NULL default '0',\n".
"  \"NumViews\" INTEGER NOT NULL default '0',\n".
"  \"Pinned\" INTEGER NOT NULL default '0',\n".
"  \"Closed\" INTEGER NOT NULL default '0'\n".
");", null);
sql_query($query, $SQLStat);
$query = sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."wordfilter\" (\n".
"  \"id\" INTEGER PRIMARY KEY NOT NULL,\n".
"  \"FilterWord\" TEXT NOT NULL,\n".
"  \"Replacement\" TEXT NOT NULL,\n".
"  \"CaseInsensitive\" VARCHAR(5) NOT NULL default '',\n".
"  \"WholeWord\" VARCHAR(5) NOT NULL default ''\n".
");", null);
sql_query($query, $SQLStat);
$OptimizeTea = sql_query(sql_pre_query("VACUUM", null), $SQLStat);
