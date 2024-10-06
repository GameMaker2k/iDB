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

    $FileInfo: pgsql.php - Last Update: 8/30/2024 SVN 1064 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="pgsql.php"||$File3Name=="/pgsql.php") {
	require('index.php');
	exit(); }
if(!isset($SetupDir['setup'])) { $SetupDir['setup'] = "setup/"; }
if(!isset($SetupDir['convert'])) { $SetupDir['convert'] = "setup/convert/"; }
/*
$query=sql_pre_query("ALTER DATABASE \"".$_POST['DatabaseName']."\" DEFAULT CHARACTER SET ".$SQLCharset." COLLATE ".$SQLCollate.";", null);
sql_query($query,$SQLStat);
*/
$parsestr = parse_url($YourWebsite);
if (!filter_var($parsestr['host'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6) || $parsestr['host'] == "localhost") {
	$GuestLocalIP = gethostbyname($parsestr['host']); } else { $GuestLocalIP = $parsestr['host']; }
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."categories\" (\n".
"  \"id\" SERIAL PRIMARY KEY NOT NULL,\n".
"  \"OrderID\" numeric(15) NOT NULL default '0',\n".
"  \"Name\" varchar(150) NOT NULL default '',\n".
"  \"ShowCategory\" varchar(5) NOT NULL default '',\n".
"  \"CategoryType\" varchar(15) NOT NULL default '',\n".
"  \"SubShowForums\" varchar(5) NOT NULL default '',\n".
"  \"InSubCategory\" numeric(15) NOT NULL default '0',\n".
"  \"PostCountView\" numeric(15) NOT NULL default '0',\n".
"  \"KarmaCountView\" numeric(15) NOT NULL default '0',\n".
"  \"Description\" text NOT NULL\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."catpermissions\" (\n".
"  \"id\" SERIAL PRIMARY KEY NOT NULL,\n".
"  \"PermissionID\" numeric(15) NOT NULL default '0',\n".
"  \"Name\" varchar(150) NOT NULL default '',\n".
"  \"CategoryID\" numeric(15) NOT NULL default '0',\n".
"  \"CanViewCategory\" varchar(5) NOT NULL default ''\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."events\" (\n".
"  \"id\" SERIAL PRIMARY KEY NOT NULL,\n".
"  \"UserID\" numeric(15) NOT NULL default '0',\n".
"  \"GuestName\" varchar(150) NOT NULL default '',\n".
"  \"EventName\" varchar(150) NOT NULL default '',\n".
"  \"EventText\" text NOT NULL,\n".
"  \"TimeStamp\" numeric(15) NOT NULL default '0',\n".
"  \"TimeStampEnd\" numeric(15) NOT NULL default '0',\n".
"  \"EventMonth\" numeric(5) NOT NULL default '0',\n".
"  \"EventMonthEnd\" numeric(5) NOT NULL default '0',\n".
"  \"EventDay\" numeric(5) NOT NULL default '0',\n".
"  \"EventDayEnd\" numeric(5) NOT NULL default '0',\n".
"  \"EventYear\" numeric(5) NOT NULL default '0',\n".
"  \"EventYearEnd\" numeric(5) NOT NULL default '0',\n".
"  \"IP\" varchar(64) NOT NULL default ''\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."forums\" (\n".
"  \"id\" SERIAL PRIMARY KEY NOT NULL,\n".
"  \"CategoryID\" numeric(15) NOT NULL default '0',\n".
"  \"OrderID\" numeric(15) NOT NULL default '0',\n".
"  \"Name\" varchar(150) NOT NULL default '',\n".
"  \"ShowForum\" varchar(5) NOT NULL default '',\n".
"  \"ForumType\" varchar(15) NOT NULL default '',\n".
"  \"InSubForum\" numeric(15) NOT NULL default '0',\n".
"  \"RedirectURL\" text NOT NULL,\n".
"  \"Redirects\" numeric(15) NOT NULL default '0',\n".
"  \"NumViews\" numeric(15) NOT NULL default '0',\n".
"  \"Description\" text NOT NULL,\n".
"  \"PostCountAdd\" varchar(15) NOT NULL default '',\n".
"  \"PostCountView\" numeric(15) NOT NULL default '0',\n".
"  \"KarmaCountView\" numeric(15) NOT NULL default '0',\n".
"  \"CanHaveTopics\" varchar(5) NOT NULL default '',\n".
"  \"HotTopicPosts\" numeric(15) NOT NULL default '0',\n".
"  \"NumPosts\" numeric(15) NOT NULL default '0',\n".
"  \"NumTopics\" numeric(15) NOT NULL default '0'\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."groups\" (\n".
"  \"id\" SERIAL PRIMARY KEY NOT NULL,\n".
"  \"Name\" varchar(150) NOT NULL default '',\n".
"  \"PermissionID\" numeric(15) NOT NULL default '0',\n".
"  \"NamePrefix\" varchar(150) NOT NULL default '',\n".
"  \"NameSuffix\" varchar(150) NOT NULL default '',\n".
"  \"CanViewBoard\" varchar(5) NOT NULL default '',\n".
"  \"CanViewOffLine\" varchar(5) NOT NULL default '',\n".
"  \"CanEditProfile\" varchar(5) NOT NULL default '',\n".
"  \"CanAddEvents\" varchar(5) NOT NULL default '',\n".
"  \"CanPM\" varchar(5) NOT NULL default '',\n".
"  \"CanSearch\" varchar(5) NOT NULL default '',\n".
"  \"CanExecPHP\" varchar(5) NOT NULL default '',\n".
"  \"CanDoHTML\" varchar(5) NOT NULL default '',\n".
"  \"CanUseBBTags\" varchar(5) NOT NULL default '',\n".
"  \"CanModForum\" varchar(5) NOT NULL default '',\n".
"  \"CanViewIPAddress\" varchar(5) NOT NULL default '',\n".
"  \"CanViewUserAgent\" varchar(5) NOT NULL default '',\n".
"  \"CanViewAnonymous\" varchar(5) NOT NULL default '',\n".
"  \"FloodControl\" numeric(5) NOT NULL default '0',\n".
"  \"SearchFlood\" numeric(5) NOT NULL default '0',\n".
"  \"PromoteTo\" numeric(15) NOT NULL default '0',\n".
"  \"PromotePosts\" numeric(15) NOT NULL default '0',\n".
"  \"PromoteKarma\" numeric(15) NOT NULL default '0',\n".
"  \"DemoteTo\" numeric(15) NOT NULL default '0',\n".
"  \"DemotePosts\" numeric(15) NOT NULL default '0',\n".
"  \"DemoteKarma\" numeric(15) NOT NULL default '0',\n".
"  \"HasModCP\" varchar(5) NOT NULL default '',\n".
"  \"HasAdminCP\" varchar(5) NOT NULL default '',\n".
"  \"ViewDBInfo\" varchar(5) NOT NULL default '',\n".
"  UNIQUE (\"Name\")\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."ranks\" (\n".
"  \"id\" SERIAL PRIMARY KEY NOT NULL,\n".
"  \"Name\" varchar(150) NOT NULL default '',\n".
"  \"PromoteTo\" numeric(15) NOT NULL default '0',\n".
"  \"PromotePosts\" numeric(15) NOT NULL default '0',\n".
"  \"PromoteKarma\" numeric(15) NOT NULL default '0',\n".
"  \"DemoteTo\" numeric(15) NOT NULL default '0',\n".
"  \"DemotePosts\" numeric(15) NOT NULL default '0',\n".
"  \"DemoteKarma\" numeric(15) NOT NULL default '0',\n".
"  UNIQUE (\"Name\")\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."levels\" (\n".
"  \"id\" SERIAL PRIMARY KEY NOT NULL,\n".
"  \"Name\" varchar(150) NOT NULL default '',\n".
"  \"PromoteTo\" numeric(15) NOT NULL default '0',\n".
"  \"PromotePosts\" numeric(15) NOT NULL default '0',\n".
"  \"PromoteKarma\" numeric(15) NOT NULL default '0',\n".
"  \"DemoteTo\" numeric(15) NOT NULL default '0',\n".
"  \"DemotePosts\" numeric(15) NOT NULL default '0',\n".
"  \"DemoteKarma\" numeric(15) NOT NULL default '0',\n".
"  UNIQUE (\"Name\")\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."members\" (\n".
"  \"id\" SERIAL PRIMARY KEY NOT NULL,\n".
"  \"Name\" varchar(150) NOT NULL default '',\n".
"  \"Handle\" varchar(150) NOT NULL default '',\n".
"  \"UserPassword\" varchar(256) NOT NULL default '',\n".
"  \"HashType\" varchar(50) NOT NULL default '',\n".
"  \"Email\" varchar(256) NOT NULL default '',\n".
"  \"GroupID\" numeric(15) NOT NULL default '0',\n".
"  \"LevelID\" numeric(15) NOT NULL default '0',\n".
"  \"RankID\" numeric(15) NOT NULL default '0',\n".
"  \"Validated\" varchar(20) NOT NULL default '',\n".
"  \"HiddenMember\" varchar(20) NOT NULL default '',\n".
"  \"WarnLevel\" numeric(15) NOT NULL default '0',\n".
"  \"Interests\" text NOT NULL default '',\n".
"  \"Title\" varchar(150) NOT NULL default '',\n".
"  \"Joined\" numeric(15) NOT NULL default '0',\n".
"  \"LastActive\" numeric(15) NOT NULL default '0',\n".
"  \"LastLogin\" numeric(15) NOT NULL default '0',\n".
"  \"LastPostTime\" numeric(15) NOT NULL default '0',\n".
"  \"BanTime\" numeric(15) NOT NULL default '0',\n".
"  \"BirthDay\" numeric(5) NOT NULL default '0',\n".
"  \"BirthMonth\" numeric(5) NOT NULL default '0',\n".
"  \"BirthYear\" numeric(5) NOT NULL default '0',\n".
"  \"Signature\" text NOT NULL,\n".
"  \"Notes\" text NOT NULL,\n".
"  \"Bio\" text NOT NULL,\n".
"  \"Avatar\" varchar(150) NOT NULL default '',\n".
"  \"AvatarSize\" varchar(10) NOT NULL default '',\n".
"  \"Website\" varchar(150) NOT NULL default '',\n".
"  \"Location\" varchar(150) NOT NULL default '',\n".
"  \"Gender\" varchar(15) NOT NULL default '',\n".
"  \"PostCount\" numeric(15) NOT NULL default '0',\n".
"  \"Karma\" numeric(15) NOT NULL default '0',\n".
"  \"KarmaUpdate\" numeric(15) NOT NULL default '0',\n".
"  \"RepliesPerPage\" numeric(5) NOT NULL default '0',\n".
"  \"TopicsPerPage\" numeric(5) NOT NULL default '0',\n".
"  \"MessagesPerPage\" numeric(5) NOT NULL default '0',\n".
"  \"TimeZone\" varchar(256) NOT NULL default '',\n".
"  \"DateFormat\" VARCHAR(15) NOT NULL default '',\n".
"  \"TimeFormat\" VARCHAR(15) NOT NULL default '',\n".
"  \"UseTheme\" varchar(32) NOT NULL default '',\n".
"  \"IgnoreSignitures\" varchar(32) NOT NULL default '',\n".
"  \"IgnoreAdvatars\" varchar(32) NOT NULL default '',\n".
"  \"IgnoreUsers\" varchar(32) NOT NULL default '',\n".
"  \"IP\" varchar(64) NOT NULL default '',\n".
"  \"Salt\" varchar(50) NOT NULL default '',\n".
"  UNIQUE (\"Name\"),\n".
"  UNIQUE (\"Handle\"),\n".
"  UNIQUE (\"Email\")\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."mempermissions\" (\n".
"  \"id\" SERIAL PRIMARY KEY NOT NULL,\n".
"  \"PermissionID\" numeric(15) NOT NULL default '0',\n".
"  \"CanViewBoard\" varchar(5) NOT NULL default '',\n".
"  \"CanViewOffLine\" varchar(5) NOT NULL default '',\n".
"  \"CanEditProfile\" varchar(5) NOT NULL default '',\n".
"  \"CanAddEvents\" varchar(5) NOT NULL default '',\n".
"  \"CanPM\" varchar(5) NOT NULL default '',\n".
"  \"CanSearch\" varchar(5) NOT NULL default '',\n".
"  \"CanExecPHP\" varchar(5) NOT NULL default '',\n".
"  \"CanDoHTML\" varchar(5) NOT NULL default '',\n".
"  \"CanUseBBTags\" varchar(5) NOT NULL default '',\n".
"  \"CanModForum\" varchar(5) NOT NULL default '',\n".
"  \"CanViewIPAddress\" varchar(5) NOT NULL default '',\n".
"  \"CanViewUserAgent\" varchar(5) NOT NULL default '',\n".
"  \"CanViewAnonymous\" varchar(5) NOT NULL default '',\n".
"  \"FloodControl\" numeric(5) NOT NULL default '0',\n".
"  \"SearchFlood\" numeric(5) NOT NULL default '0',\n".
"  \"HasModCP\" varchar(5) NOT NULL default '',\n".
"  \"HasAdminCP\" varchar(5) NOT NULL default '',\n".
"  \"ViewDBInfo\" varchar(5) NOT NULL default ''\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."messenger\" (\n".
"  \"id\" SERIAL PRIMARY KEY NOT NULL,\n".
"  \"DiscussionID\" numeric(15) NOT NULL default '0',\n".
"  \"SenderID\" numeric(15) NOT NULL default '0',\n".
"  \"ReciverID\" numeric(15) NOT NULL default '0',\n".
"  \"GuestName\" varchar(150) NOT NULL default '',\n".
"  \"MessageTitle\" varchar(150) NOT NULL default '',\n".
"  \"MessageText\" text NOT NULL,\n".
"  \"Description\" text NOT NULL,\n".
"  \"DateSend\" numeric(15) NOT NULL default '0',\n".
"  \"Read\" numeric(5) NOT NULL default '0',\n".
"  \"IP\" varchar(64) NOT NULL default ''\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."permissions\" (\n".
"  \"id\" SERIAL PRIMARY KEY NOT NULL,\n".
"  \"PermissionID\" numeric(15) NOT NULL default '0',\n".
"  \"Name\" varchar(150) NOT NULL default '',\n".
"  \"ForumID\" numeric(15) NOT NULL default '0',\n".
"  \"CanViewForum\" varchar(5) NOT NULL default '',\n".
"  \"CanMakePolls\" varchar(5) NOT NULL default '',\n".
"  \"CanMakeTopics\" varchar(5) NOT NULL default '',\n".
"  \"CanMakeReplys\" varchar(5) NOT NULL default '',\n".
"  \"CanMakeReplysCT\" varchar(5) NOT NULL default '',\n".
"  \"HideEditPostInfo\" varchar(5) NOT NULL default '',\n".
"  \"CanEditTopics\" varchar(5) NOT NULL default '',\n".
"  \"CanEditTopicsCT\" varchar(5) NOT NULL default '',\n".
"  \"CanEditReplys\" varchar(5) NOT NULL default '',\n".
"  \"CanEditReplysCT\" varchar(5) NOT NULL default '',\n".
"  \"CanDeleteTopics\" varchar(5) NOT NULL default '',\n".
"  \"CanDeleteTopicsCT\" varchar(5) NOT NULL default '',\n".
"  \"CanDeleteReplys\" varchar(5) NOT NULL default '',\n".
"  \"CanDeleteReplysCT\" varchar(5) NOT NULL default '',\n".
"  \"CanDoublePost\" varchar(5) NOT NULL default '',\n".
"  \"CanDoublePostCT\" varchar(5) NOT NULL default '',\n".
"  \"GotoEditPost\" varchar(5) NOT NULL default '',\n".
"  \"CanCloseTopics\" varchar(5) NOT NULL default '',\n".
"  \"CanCloseTopicsCT\" varchar(5) NOT NULL default '',\n".
"  \"CanPinTopics\" varchar(5) NOT NULL default '',\n".
"  \"CanPinTopicsCT\" varchar(5) NOT NULL default '',\n".
"  \"CanExecPHP\" varchar(5) NOT NULL default '',\n".
"  \"CanDoHTML\" varchar(5) NOT NULL default '',\n".
"  \"CanUseBBTags\" varchar(5) NOT NULL default '',\n".
"  \"CanModForum\" varchar(5) NOT NULL default '',\n".
"  \"CanReportPost\" varchar(5) NOT NULL default ''\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."polls\" (\n".
"  \"id\" SERIAL PRIMARY KEY NOT NULL,\n".
"  \"UserID\" numeric(15) NOT NULL default '0',\n".
"  \"GuestName\" varchar(150) NOT NULL default '',\n".
"  \"PollValues\" text NOT NULL,\n".
"  \"Description\" text NOT NULL,\n".
"  \"UsersVoted\" text NOT NULL,\n".
"  \"IP\" varchar(64) NOT NULL default ''\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."posts\" (\n".
"  \"id\" SERIAL PRIMARY KEY NOT NULL,\n".
"  \"TopicID\" numeric(15) NOT NULL default '0',\n".
"  \"ForumID\" numeric(15) NOT NULL default '0',\n".
"  \"CategoryID\" numeric(15) NOT NULL default '0',\n".
"  \"ReplyID\" numeric(15) NOT NULL default '0',\n".
"  \"UserID\" numeric(15) NOT NULL default '0',\n".
"  \"GuestName\" varchar(150) NOT NULL default '',\n".
"  \"TimeStamp\" numeric(15) NOT NULL default '0',\n".
"  \"LastUpdate\" numeric(15) NOT NULL default '0',\n".
"  \"EditUser\" numeric(15) NOT NULL default '0',\n".
"  \"EditUserName\" varchar(150) NOT NULL default '',\n".
"  \"Post\" text NOT NULL,\n".
"  \"Description\" text NOT NULL,\n".
"  \"IP\" varchar(64) NOT NULL default '',\n".
"  \"EditIP\" varchar(64) NOT NULL default ''\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."restrictedwords\" (\n".
"  \"id\" SERIAL PRIMARY KEY NOT NULL,\n".
"  \"Word\" text NOT NULL,\n".
"  \"RestrictedUserName\" varchar(5) NOT NULL default '',\n".
"  \"RestrictedTopicName\" varchar(5) NOT NULL default '',\n".
"  \"RestrictedEventName\" varchar(5) NOT NULL default '',\n".
"  \"RestrictedMessageName\" varchar(5) NOT NULL default '',\n".
"  \"CaseInsensitive\" varchar(5) NOT NULL default '',\n".
"  \"WholeWord\" varchar(5) NOT NULL default ''\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."sessions\" (\n".
"  \"session_id\" varchar(250) PRIMARY KEY NOT NULL default '',\n".
"  \"session_data\" text NOT NULL,\n".
"  \"serialized_data\" text NOT NULL,\n".
"  \"user_agent\" text NOT NULL,\n".
"  \"client_hints\" text NOT NULL,\n".
"  \"ip_address\" varchar(64) NOT NULL default '',\n".
"  \"expires\" numeric(15) NOT NULL default '0'\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."smileys\" (\n".
"  \"id\" SERIAL PRIMARY KEY NOT NULL,\n".
"  \"FileName\" text NOT NULL,\n".
"  \"SmileName\" text NOT NULL,\n".
"  \"SmileText\" text NOT NULL,\n".
"  \"EmojiText\" text NOT NULL,\n".
"  \"Directory\" text NOT NULL,\n".
"  \"Display\" varchar(5) NOT NULL default '',\n".
"  \"ReplaceCI\" varchar(5) NOT NULL default ''\n".
");", null);
sql_query($query,$SQLStat);
/*
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."tagboard\" (\n".
"  \"id\" SERIAL PRIMARY KEY NOT NULL,\n".
"  \"UserID\" numeric(15) NOT NULL default '0',\n".
"  \"GuestName\" varchar(150) NOT NULL default '',\n".
"  \"TimeStamp\" numeric(15) NOT NULL default '0',\n".
"  \"Post\" text NOT NULL,\n".
"  \"IP\" varchar(64) NOT NULL default ''
");", null);
sql_query($query,$SQLStat);
*/
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."themes\" (\n".
"  \"id\" SERIAL PRIMARY KEY NOT NULL,\n".
"  \"Name\" varchar(32) NOT NULL default '',\n".
"  \"ThemeName\" varchar(32) NOT NULL default '',\n".
"  \"ThemeMaker\" varchar(150) NOT NULL default '',\n".
"  \"ThemeVersion\" varchar(150) NOT NULL default '',\n".
"  \"ThemeVersionType\" varchar(150) NOT NULL default '',\n".
"  \"ThemeSubVersion\" varchar(150) NOT NULL default '',\n".
"  \"MakerURL\" varchar(150) NOT NULL default '',\n".
"  \"CopyRight\" varchar(150) NOT NULL default '',\n".
"  \"WrapperString\" text NOT NULL,\n".
"  \"CSS\" text NOT NULL,\n".
"  \"CSSType\" varchar(150) NOT NULL default '',\n".
"  \"FavIcon\" varchar(150) NOT NULL default '',\n".
"  \"OpenGraph\" varchar(150) NOT NULL default '',\n".
"  \"TableStyle\" varchar(150) NOT NULL default '',\n".
"  \"MiniPageAltStyle\" varchar(150) NOT NULL default '',\n".
"  \"PreLogo\" varchar(150) NOT NULL default '',\n".
"  \"Logo\" varchar(150) NOT NULL default '',\n".
"  \"LogoStyle\" varchar(150) NOT NULL default '',\n".
"  \"SubLogo\" varchar(150) NOT NULL default '',\n".
"  \"TopicIcon\" varchar(150) NOT NULL default '',\n".
"  \"MovedTopicIcon\" varchar(150) NOT NULL default '',\n".
"  \"HotTopic\" varchar(150) NOT NULL default '',\n".
"  \"MovedHotTopic\" varchar(150) NOT NULL default '',\n".
"  \"PinTopic\" varchar(150) NOT NULL default '',\n".
"  \"AnnouncementTopic\" varchar(150) NOT NULL default '',\n".
"  \"MovedPinTopic\" varchar(150) NOT NULL default '',\n".
"  \"HotPinTopic\" varchar(150) NOT NULL default '',\n".
"  \"MovedHotPinTopic\" varchar(150) NOT NULL default '',\n".
"  \"ClosedTopic\" varchar(150) NOT NULL default '',\n".
"  \"MovedClosedTopic\" varchar(150) NOT NULL default '',\n".
"  \"HotClosedTopic\" varchar(150) NOT NULL default '',\n".
"  \"MovedHotClosedTopic\" varchar(150) NOT NULL default '',\n".
"  \"PinClosedTopic\" varchar(150) NOT NULL default '',\n".
"  \"MovedPinClosedTopic\" varchar(150) NOT NULL default '',\n".
"  \"HotPinClosedTopic\" varchar(150) NOT NULL default '',\n".
"  \"MovedHotPinClosedTopic\" varchar(150) NOT NULL default '',\n".
"  \"MessageRead\" varchar(150) NOT NULL default '',\n".
"  \"MessageUnread\" varchar(150) NOT NULL default '',\n".
"  \"Profile\" varchar(150) NOT NULL default '',\n".
"  \"WWW\" varchar(150) NOT NULL default '',\n".
"  \"PM\" varchar(150) NOT NULL default '',\n".
"  \"TopicLayout\" varchar(150) NOT NULL default '',\n".
"  \"AddReply\" varchar(150) NOT NULL default '',\n".
"  \"FastReply\" varchar(150) NOT NULL default '',\n".
"  \"NewTopic\" varchar(150) NOT NULL default '',\n".
"  \"QuoteReply\" varchar(150) NOT NULL default '',\n".
"  \"EditReply\" varchar(150) NOT NULL default '',\n".
"  \"DeleteReply\" varchar(150) NOT NULL default '',\n".
"  \"Report\" varchar(150) NOT NULL default '',\n".
"  \"LineDivider\" varchar(150) NOT NULL default '',\n".
"  \"ButtonDivider\" varchar(150) NOT NULL default '',\n".
"  \"LineDividerTopic\" varchar(150) NOT NULL default '',\n".
"  \"TitleDivider\" varchar(150) NOT NULL default '',\n".
"  \"ForumStyle\" varchar(150) NOT NULL default '',\n".
"  \"ForumIcon\" varchar(150) NOT NULL default '',\n".
"  \"SubForumIcon\" varchar(150) NOT NULL default '',\n".
"  \"RedirectIcon\" varchar(150) NOT NULL default '',\n".
"  \"TitleIcon\" varchar(150) NOT NULL default '',\n".
"  \"NavLinkIcon\" varchar(150) NOT NULL default '',\n".
"  \"NavLinkDivider\" varchar(150) NOT NULL default '',\n".
"  \"BoardStatsIcon\" varchar(150) NOT NULL default '',\n".
"  \"MemberStatsIcon\" varchar(150) NOT NULL default '',\n".
"  \"BirthdayStatsIcon\" varchar(150) NOT NULL default '',\n".
"  \"EventStatsIcon\" varchar(150) NOT NULL default '',\n".
"  \"OnlineStatsIcon\" varchar(150) NOT NULL default '',\n".
"  \"NoAvatar\" varchar(150) NOT NULL default '',\n".
"  \"NoAvatarSize\" varchar(150) NOT NULL default '',\n".
"  UNIQUE (\"Name\")\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."topics\" (\n".
"  \"id\" SERIAL PRIMARY KEY NOT NULL,\n".
"  \"PollID\" numeric(15) NOT NULL default '0',\n".
"  \"ForumID\" numeric(15) NOT NULL default '0',\n".
"  \"CategoryID\" numeric(15) NOT NULL default '0',\n".
"  \"OldForumID\" numeric(15) NOT NULL default '0',\n".
"  \"OldCategoryID\" numeric(15) NOT NULL default '0',\n".
"  \"UserID\" numeric(15) NOT NULL default '0',\n".
"  \"GuestName\" varchar(150) NOT NULL default '',\n".
"  \"TimeStamp\" numeric(15) NOT NULL default '0',\n".
"  \"LastUpdate\" numeric(15) NOT NULL default '0',\n".
"  \"TopicName\" varchar(150) NOT NULL default '',\n".
"  \"Description\" text NOT NULL,\n".
"  \"NumReply\" numeric(15) NOT NULL default '0',\n".
"  \"NumViews\" numeric(15) NOT NULL default '0',\n".
"  \"Pinned\" numeric(5) NOT NULL default '0',\n".
"  \"Closed\" numeric(5) NOT NULL default '0'\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."wordfilter\" (\n".
"  \"id\" SERIAL PRIMARY KEY NOT NULL,\n".
"  \"FilterWord\" text NOT NULL,\n".
"  \"Replacement\" text NOT NULL,\n".
"  \"CaseInsensitive\" varchar(5) NOT NULL default '',\n".
"  \"WholeWord\" varchar(5) NOT NULL default ''\n".
");", null);
sql_query($query,$SQLStat);
$TableChCk = array("categories", "catpermissions", "events", "forums", "groups", "levels", "members", "mempermissions", "messenger", "permissions", "polls", "posts", "ranks", "restrictedwords", "sessions", "smileys", "themes", "topics", "wordfilter");
$TablePreFix = $_POST['tableprefix'];
function add_prefix($tarray) {
global $TablePreFix;
return $TablePreFix.$tarray; }
$TableChCk = array_map("add_prefix",$TableChCk);
$tcount = count($TableChCk); $ti = 0;
while ($ti < $tcount) {
$OptimizeTea = sql_query(sql_pre_query("VACUUM ANALYZE \"".$TableChCk[$ti]."\"", null),$SQLStat);
++$ti; }
?>
