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

    $FileInfo: sqlsrv.php - Last Update: 8/30/2024 SVN 1064 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="mysql.php"||$File3Name=="/mysql.php") {
	require('index.php');
	exit(); }
if(!isset($SetupDir['setup'])) { $SetupDir['setup'] = "setup/"; }
if(!isset($SetupDir['convert'])) { $SetupDir['convert'] = "setup/convert/"; }
$parsestr = parse_url($YourWebsite);
if (!filter_var($parsestr['host'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6) || $parsestr['host'] == "localhost") {
	$GuestLocalIP = gethostbyname($parsestr['host']); } else { $GuestLocalIP = $parsestr['host']; }
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."categories\" (\n".
"  \"id\" int(15) NOT NULL IDENTITY(1,1),\n".
"  \"OrderID\" int(15) NOT NULL default '0',\n".
"  \"Name\" nvarchar(150) NOT NULL default '',\n".
"  \"ShowCategory\" nvarchar(5) NOT NULL default '',\n".
"  \"CategoryType\" nvarchar(15) NOT NULL default '',\n".
"  \"SubShowForums\" nvarchar(5) NOT NULL default '',\n".
"  \"InSubCategory\" int(15) NOT NULL default '0',\n".
"  \"PostCountView\" int(15) NOT NULL default '0',\n".
"  \"KarmaCountView\" int(15) NOT NULL default '0',\n".
"  \"Description\" nvarchar(max) NOT NULL,\n".
"  PRIMARY KEY  (\"id\")\n".
");", null);
sql_query($query,$SQLStat);
if($_POST['startblank']=="no") {
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."categories\" (\"OrderID\", \"Name\", \"ShowCategory\", \"CategoryType\", \"SubShowForums\", \"InSubCategory\", \"PostCountView\", \"KarmaCountView\", \"Description\")\n". 
"VALUES (1, 'A Test Category', 'yes', 'category', 'yes', 0, 0, 0, 'A test category that may be removed at any time.');", null);
sql_query($query,$SQLStat); }
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."catpermissions\" (\n".
"  \"id\" int(15) NOT NULL IDENTITY(1,1),\n".
"  \"PermissionID\" int(15) NOT NULL default '0',\n".
"  \"Name\" nvarchar(150) NOT NULL default '',\n".
"  \"CategoryID\" int(15) NOT NULL default '0',\n".
"  \"CanViewCategory\" nvarchar(5) NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
");", null);
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."catpermissions\" (\"PermissionID\", \"Name\", \"CategoryID\", \"CanViewCategory\") VALUES\n".
"(1, 'Admin', 0, 'yes'),\n".
"(2, 'Moderator', 0, 'yes'),\n".
"(3, 'Member', 0, 'yes'),\n".
"(4, 'Guest', 0, 'yes'),\n".
"(5, 'Banned', 0, 'no'),\n".
"(6, 'Validate', 0, 'yes');", null); 
sql_query($query,$SQLStat);
if($_POST['startblank']=="no") {
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."catpermissions\" (\"PermissionID\", \"Name\", \"CategoryID\", \"CanViewCategory\") VALUES\n".
"(1, 'Admin', 1, 'yes'),\n".
"(2, 'Moderator', 1, 'yes'),\n".
"(3, 'Member', 1, 'yes'),\n".
"(4, 'Guest', 1, 'yes'),\n".
"(5, 'Banned', 1, 'no'),\n".
"(6, 'Validate', 1, 'yes');", null); 
sql_query($query,$SQLStat); }
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."events\" (\n".
"  \"id\" int(15) NOT NULL IDENTITY(1,1),\n".
"  \"UserID\" int(15) NOT NULL default '0',\n".
"  \"GuestName\" nvarchar(150) NOT NULL default '',\n".
"  \"EventName\" nvarchar(150) NOT NULL default '',\n".
"  \"EventText\" nvarchar(max) NOT NULL,\n".
"  \"TimeStamp\" int(15) NOT NULL default '0',\n".
"  \"TimeStampEnd\" int(15) NOT NULL default '0',\n".
"  \"EventMonth\" int(5) NOT NULL default '0',\n".
"  \"EventMonthEnd\" int(5) NOT NULL default '0',\n".
"  \"EventDay\" int(5) NOT NULL default '0',\n".
"  \"EventDayEnd\" int(5) NOT NULL default '0',\n".
"  \"EventYear\" int(5) NOT NULL default '0',\n".
"  \"EventYearEnd\" int(5) NOT NULL default '0',\n".
"  \"IP\" nvarchar(64) NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
");", null);
sql_query($query,$SQLStat);
if($_POST['testdata']=="yes") {
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."events\" (\"UserID\", \"GuestName\", \"EventName\", \"EventText\", \"TimeStamp\", \"TimeStampEnd\", \"EventMonth\", \"EventMonthEnd\", \"EventDay\", \"EventDayEnd\", \"EventYear\", \"EventYearEnd\", \"IP\") VALUES\n".
"(-1, '".$iDB_Author."', 'iDB Install', 'This is the start date of your board. ^_^', %i, %i, %i, %i, %i, %i, %i, %i, '%s');", array($YourDate,$YourDateEnd,$EventMonth,$EventMonthEnd,$EventDay,$EventDayEnd,$EventYear,$EventYearEnd,$GuestLocalIP));
sql_query($query,$SQLStat); }
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."forums\" (\n".
"  \"id\" int(15) NOT NULL IDENTITY(1,1),\n".
"  \"CategoryID\" int(15) NOT NULL default '0',\n".
"  \"OrderID\" int(15) NOT NULL default '0',\n".
"  \"Name\" nvarchar(150) NOT NULL default '',\n".
"  \"ShowForum\" nvarchar(5) NOT NULL default '',\n".
"  \"ForumType\" nvarchar(15) NOT NULL default '',\n".
"  \"InSubForum\" int(15) NOT NULL default '0',\n".
"  \"RedirectURL\" nvarchar(max) NOT NULL,\n".
"  \"Redirects\" int(15) NOT NULL default '0',\n".
"  \"NumViews\" int(15) NOT NULL default '0',\n".
"  \"Description\" nvarchar(max) NOT NULL,\n".
"  \"PostCountAdd\" nvarchar(15) NOT NULL default '',\n".
"  \"PostCountView\" int(15) NOT NULL default '0',\n".
"  \"KarmaCountView\" int(15) NOT NULL default '0',\n".
"  \"CanHaveTopics\" nvarchar(5) NOT NULL default '',\n".
"  \"HotTopicPosts\" int(15) NOT NULL default '0',\n".
"  \"NumPosts\" int(15) NOT NULL default '0',\n".
"  \"NumTopics\" int(15) NOT NULL default '0',\n".
"  PRIMARY KEY  (\"id\")\n".
");", null);
sql_query($query,$SQLStat);
if($_POST['startblank']=="no") {
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."forums\" (\"CategoryID\", \"OrderID\", \"Name\", \"ShowForum\", \"ForumType\", \"InSubForum\", \"RedirectURL\", \"Redirects\", \"NumViews\", \"Description\", \"PostCountAdd\", \"PostCountView\", \"KarmaCountView\", \"CanHaveTopics\", \"HotTopicPosts\", \"NumPosts\", \"NumTopics\") VALUES\n".
"(1, 1, 'A Test Forum', 'yes', 'forum', 0, 'http://', 0, 0, 'A test forum that may be removed at any time.', 'off', 0, 0, 'yes', 15, 1, 1);", null);
sql_query($query,$SQLStat); }
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."groups\" (\n".
"  \"id\" int(15) NOT NULL IDENTITY(1,1),\n".
"  \"Name\" nvarchar(150) NOT NULL default '',\n".
"  \"PermissionID\" int(15) NOT NULL default '0',\n".
"  \"NamePrefix\" nvarchar(150) NOT NULL default '',\n".
"  \"NameSuffix\" nvarchar(150) NOT NULL default '',\n".
"  \"CanViewBoard\" nvarchar(5) NOT NULL default '',\n".
"  \"CanViewOffLine\" nvarchar(5) NOT NULL default '',\n".
"  \"CanEditProfile\" nvarchar(5) NOT NULL default '',\n".
"  \"CanAddEvents\" nvarchar(5) NOT NULL default '',\n".
"  \"CanPM\" nvarchar(5) NOT NULL default '',\n".
"  \"CanSearch\" nvarchar(5) NOT NULL default '',\n".
"  \"CanExecPHP\" nvarchar(5) NOT NULL default '',\n".
"  \"CanDoHTML\" nvarchar(5) NOT NULL default '',\n".
"  \"CanUseBBTags\" nvarchar(5) NOT NULL default '',\n".
"  \"CanModForum\" nvarchar(5) NOT NULL default '',\n".
"  \"CanViewIPAddress\" nvarchar(5) NOT NULL default '',\n".
"  \"CanViewUserAgent\" nvarchar(5) NOT NULL default '',\n".
"  \"CanViewAnonymous\" nvarchar(5) NOT NULL default '',\n".
"  \"FloodControl\" int(5) NOT NULL default '0',\n".
"  \"SearchFlood\" int(5) NOT NULL default '0',\n".
"  \"PromoteTo\" int(15) NOT NULL default '0',\n".
"  \"PromotePosts\" int(15) NOT NULL default '0',\n".
"  \"PromoteKarma\" int(15) NOT NULL default '0',\n".
"  \"DemoteTo\" int(15) NOT NULL default '0',\n".
"  \"DemotePosts\" int(15) NOT NULL default '0',\n".
"  \"DemoteKarma\" int(15) NOT NULL default '0',\n".
"  \"HasModCP\" nvarchar(5) NOT NULL default '',\n".
"  \"HasAdminCP\" nvarchar(5) NOT NULL default '',\n".
"  \"ViewDBInfo\" nvarchar(5) NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\"),\n".
"  UNIQUE KEY \"Name\" (\"Name\")\n".
");", null);
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."groups\" (\"Name\", \"PermissionID\", \"NamePrefix\", \"NameSuffix\", \"CanViewBoard\", \"CanViewOffLine\", \"CanEditProfile\", \"CanAddEvents\", \"CanPM\", \"CanSearch\", \"CanExecPHP\", \"CanDoHTML\", \"CanUseBBTags\", \"CanModForum\", \"CanViewIPAddress\", \"CanViewUserAgent\", \"CanViewAnonymous\", \"FloodControl\", \"SearchFlood\", \"PromoteTo\", \"PromotePosts\", \"PromoteKarma\", \"DemoteTo\", \"DemotePosts\", \"DemoteKarma\", \"HasModCP\", \"HasAdminCP\", \"ViewDBInfo\") VALUES\n".
"('Admin', 1, '', '', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 30, 30, 0, 0, 0, 0, 0, 0, 'yes', 'yes', 'yes'),\n".
"('Moderator', 2, '', '', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 30, 30, 0, 0, 0, 0, 0, 0, 'yes', 'no', 'no'),\n".
"('Member', 3, '', '', 'yes', 'no', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'yes', 'no', 'no', 'no', 'no', 30, 30, 0, 0, 0, 0, 0, 0, 'no', 'no', 'no'),\n".
"('Guest', 4, '', '', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'no', 'no', 'no', 'no', 30, 30, 0, 0, 0, 0, 0, 0, 'no', 'no', 'no'),\n".
"('Banned', 5, '', '', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 30, 30, 0, 0, 0, 0, 0, 0, 'no', 'no', 'no'),\n".
"('Validate', 6, '', '', 'yes', 'no', 'yes', 'no', 'no', 'yes', 'no', 'no', 'yes', 'no', 'no', 'no', 'no', 30, 30, 0, 0, 0, 0, 0, 0, 'no', 'no', 'no');", null); 
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."ranks\" (\n".
"  \"id\" int(15) NOT NULL IDENTITY(1,1),\n".
"  \"Name\" nvarchar(150) NOT NULL default '',\n".
"  \"PromoteTo\" int(15) NOT NULL default '0',\n".
"  \"PromotePosts\" int(15) NOT NULL default '0',\n".
"  \"PromoteKarma\" int(15) NOT NULL default '0',\n".
"  \"DemoteTo\" int(15) NOT NULL default '0',\n".
"  \"DemotePosts\" int(15) NOT NULL default '0',\n".
"  \"DemoteKarma\" int(15) NOT NULL default '0',\n".
"  PRIMARY KEY  (\"id\"),\n".
"  UNIQUE KEY \"Name\" (\"Name\")\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."levels\" (\n".
"  \"id\" int(15) NOT NULL IDENTITY(1,1),\n".
"  \"Name\" nvarchar(150) NOT NULL default '',\n".
"  \"PromoteTo\" int(15) NOT NULL default '0',\n".
"  \"PromotePosts\" int(15) NOT NULL default '0',\n".
"  \"PromoteKarma\" int(15) NOT NULL default '0',\n".
"  \"DemoteTo\" int(15) NOT NULL default '0',\n".
"  \"DemotePosts\" int(15) NOT NULL default '0',\n".
"  \"DemoteKarma\" int(15) NOT NULL default '0',\n".
"  PRIMARY KEY  (\"id\"),\n".
"  UNIQUE KEY \"Name\" (\"Name\")\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."members\" (\n".
"  \"id\" int(15) NOT NULL IDENTITY(1,1),\n".
"  \"Name\" nvarchar(150) NOT NULL default '',\n".
"  \"Handle\" nvarchar(150) NOT NULL default '',\n".
"  \"UserPassword\" nvarchar(256) NOT NULL default '',\n".
"  \"HashType\" nvarchar(50) NOT NULL default '',\n".
"  \"Email\" nvarchar(256) NOT NULL default '',\n".
"  \"GroupID\" int(15) NOT NULL default '0',\n".
"  \"LevelID\" int(15) NOT NULL default '0',\n".
"  \"RankID\" int(15) NOT NULL default '0',\n".
"  \"Validated\" nvarchar(20) NOT NULL default '',\n".
"  \"HiddenMember\" nvarchar(20) NOT NULL default '',\n".
"  \"WarnLevel\" int(15) NOT NULL default '0',\n".
"  \"Interests\" nvarchar(max) NOT NULL default '',\n".
"  \"Title\" nvarchar(150) NOT NULL default '',\n".
"  \"Joined\" int(15) NOT NULL default '0',\n".
"  \"LastActive\" int(15) NOT NULL default '0',\n".
"  \"LastLogin\" int(15) NOT NULL default '0',\n".
"  \"LastPostTime\" int(15) NOT NULL default '0',\n".
"  \"BanTime\" int(15) NOT NULL default '0',\n".
"  \"BirthDay\" int(5) NOT NULL default '0',\n".
"  \"BirthMonth\" int(5) NOT NULL default '0',\n".
"  \"BirthYear\" int(5) NOT NULL default '0',\n".
"  \"Signature\" nvarchar(max) NOT NULL,\n".
"  \"Notes\" nvarchar(max) NOT NULL,\n".
"  \"Bio\" nvarchar(max) NOT NULL,\n".
"  \"Avatar\" nvarchar(150) NOT NULL default '',\n".
"  \"AvatarSize\" nvarchar(10) NOT NULL default '',\n".
"  \"Website\" nvarchar(150) NOT NULL default '',\n".
"  \"Location\" nvarchar(150) NOT NULL default '',\n".
"  \"Gender\" nvarchar(15) NOT NULL default '',\n".
"  \"PostCount\" int(15) NOT NULL default '0',\n".
"  \"Karma\" int(15) NOT NULL default '0',\n".
"  \"KarmaUpdate\" int(15) NOT NULL default '0',\n".
"  \"RepliesPerPage\" int(5) NOT NULL default '0',\n".
"  \"TopicsPerPage\" int(5) NOT NULL default '0',\n".
"  \"MessagesPerPage\" int(5) NOT NULL default '0',\n".
"  \"TimeZone\" nvarchar(256) NOT NULL default '',\n".
"  \"DateFormat\" nvarchar(15) NOT NULL default '',\n".
"  \"TimeFormat\" nvarchar(15) NOT NULL default '',\n".
"  \"UseTheme\" nvarchar(32) NOT NULL default '',\n".
"  \"IgnoreSignitures\" nvarchar(32) NOT NULL default '',\n".
"  \"IgnoreAdvatars\" nvarchar(32) NOT NULL default '',\n".
"  \"IgnoreUsers\" nvarchar(32) NOT NULL default '',\n".
"  \"IP\" nvarchar(64) NOT NULL default '',\n".
"  \"Salt\" nvarchar(50) NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\"),\n".
"  UNIQUE KEY \"Name\" (\"Name\"),\n".
"  UNIQUE KEY \"Handle\" (\"Name\"),\n".
"  UNIQUE KEY \"Email\" (\"Email\")\n".
");", null);
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."members\" (\"id\", \"Name\", \"Handle\", \"UserPassword\", \"HashType\", \"Email\", \"GroupID\", \"LevelID\", \"RankID\", \"Validated\", \"HiddenMember\", \"WarnLevel\", \"Interests\", \"Title\", \"Joined\", \"LastActive\", \"LastLogin\", \"LastPostTime\", \"BanTime\", \"BirthDay\", \"BirthMonth\", \"BirthYear\", \"Signature\", \"Notes\", \"Bio\", \"Avatar\", \"AvatarSize\", \"Website\", \"Location\", \"Gender\", \"PostCount\", \"Karma\", \"KarmaUpdate\", \"RepliesPerPage\", \"TopicsPerPage\", \"MessagesPerPage\", \"TimeZone\", \"DateFormat\", \"TimeFormat\", \"UseTheme\", \"IgnoreSignitures\", \"IgnoreAdvatars\", \"IgnoreUsers\", \"IP\", \"Salt\") VALUES\n".
"(-1, 'Guest', 'guest', '%s', 'GuestPassword', '%s', 4, -1, -1, 'no', 'yes', 0, 'Guest Account', 'Guest', %i, %i, %i, '0', '0', '0', '0', '0', '', 'Your Notes', '', 'http://', '100x100', '%s', '', 'Unknown', 0, 0, 0, 10, 10, 10, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'),\n".
"(1, '%s', '%s', '%s', '%s', '%s', 1, 0, 0, 'yes', 'no', 0, '%s', 'Admin', %i, %i, %i, '0', '0', '0', '0', '0', '%s', 'Your Notes', '', '%s', '100x100', '%s', '', 'Unknown', 0, 0, 0, 10, 10, 10, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');", array($GuestPassword,$GEmail,$YourDate,$YourDate,$YourDate,$YourWebsite,$_POST['YourOffSet'],$_POST['iDBDateFormat'],$_POST['iDBTimeFormat'],$_POST['DefaultTheme'],'','','',$GuestLocalIP,$GSalt,$_POST['AdminUser'],$_POST['AdminHandle'],$NewPassword,$iDBHashType,$_POST['AdminEmail'],"",$YourDate,$YourDate,$YourDate,"","http://",$YourWebsite,$_POST['YourOffSet'],$_POST['iDBDateFormat'],$_POST['iDBTimeFormat'],$_POST['DefaultTheme'],'','','',$UserIP,$YourSalt));
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."mempermissions\" (\n".
"  \"id\" int(15) NOT NULL IDENTITY(1,1),\n".
"  \"PermissionID\" int(15) NOT NULL default '0',\n".
"  \"CanViewBoard\" nvarchar(5) NOT NULL default '',\n".
"  \"CanViewOffLine\" nvarchar(5) NOT NULL default '',\n".
"  \"CanEditProfile\" nvarchar(5) NOT NULL default '',\n".
"  \"CanAddEvents\" nvarchar(5) NOT NULL default '',\n".
"  \"CanPM\" nvarchar(5) NOT NULL default '',\n".
"  \"CanSearch\" nvarchar(5) NOT NULL default '',\n".
"  \"CanExecPHP\" nvarchar(5) NOT NULL default '',\n".
"  \"CanDoHTML\" nvarchar(5) NOT NULL default '',\n".
"  \"CanUseBBTags\" nvarchar(5) NOT NULL default '',\n".
"  \"CanModForum\" nvarchar(5) NOT NULL default '',\n".
"  \"CanViewIPAddress\" nvarchar(5) NOT NULL default '',\n".
"  \"CanViewUserAgent\" nvarchar(5) NOT NULL default '',\n".
"  \"CanViewAnonymous\" nvarchar(5) NOT NULL default '',\n".
"  \"FloodControl\" int(5) NOT NULL default '0',\n".
"  \"SearchFlood\" int(5) NOT NULL default '0',\n".
"  \"HasModCP\" nvarchar(5) NOT NULL default '',\n".
"  \"HasAdminCP\" nvarchar(5) NOT NULL default '',\n".
"  \"ViewDBInfo\" nvarchar(5) NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
");", null);
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."mempermissions\" (\"id\", \"PermissionID\", \"CanViewBoard\", \"CanViewOffLine\", \"CanEditProfile\", \"CanAddEvents\", \"CanPM\", \"CanSearch\", \"CanExecPHP\", \"CanDoHTML\", \"CanUseBBTags\", \"CanModForum\", \"CanViewIPAddress\", \"CanViewUserAgent\", \"CanViewAnonymous\", \"FloodControl\", \"SearchFlood\", \"HasModCP\", \"HasAdminCP\", \"ViewDBInfo\") VALUES\n".
"(-1, 0, 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', -1, -1, 'group', 'group', 'group'),\n".
"(1, 0, 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', -1, -1, 'group', 'group', 'group');", null);
//"(-1, 0, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 30, 30, 'no', 'no', 'no'),\n".
//"(1, 0, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'yes', 'yes', 30, 30, 'yes', 'yes', 'yes');", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."messenger\" (\n".
"  \"id\" int(15) NOT NULL IDENTITY(1,1),\n".
"  \"DiscussionID\" int(15) NOT NULL default '0',\n".
"  \"SenderID\" int(15) NOT NULL default '0',\n".
"  \"ReciverID\" int(15) NOT NULL default '0',\n".
"  \"GuestName\" nvarchar(150) NOT NULL default '',\n".
"  \"MessageTitle\" nvarchar(150) NOT NULL default '',\n".
"  \"MessageText\" nvarchar(max) NOT NULL,\n".
"  \"Description\" nvarchar(max) NOT NULL,\n".
"  \"DateSend\" int(15) NOT NULL default '0',\n".
"  \"Read\" int(5) NOT NULL default '0',\n".
"  \"IP\" nvarchar(64) NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
");", null);
sql_query($query,$SQLStat);
if($_POST['testdata']=="yes") {
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."messenger\" (\"DiscussionID\", \"SenderID\", \"ReciverID\", \"GuestName\", \"MessageTitle\", \"MessageText\", \"Description\", \"DateSend\", \"Read\", \"IP\") VALUES\n".
"(0, -1, 1, '".$iDB_Author."', 'Welcome', 'Welcome to your new Internet Discussion Board! :)', '%s', %i, 0, '%s');", array("Welcome ".$_POST['AdminUser'],$YourDate,$GuestLocalIP));
sql_query($query,$SQLStat); }
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."permissions\" (\n".
"  \"id\" int(15) NOT NULL IDENTITY(1,1),\n".
"  \"PermissionID\" int(15) NOT NULL default '0',\n".
"  \"Name\" nvarchar(150) NOT NULL default '',\n".
"  \"ForumID\" int(15) NOT NULL default '0',\n".
"  \"CanViewForum\" nvarchar(5) NOT NULL default '',\n".
"  \"CanMakePolls\" nvarchar(5) NOT NULL default '',\n".
"  \"CanMakeTopics\" nvarchar(5) NOT NULL default '',\n".
"  \"CanMakeReplys\" nvarchar(5) NOT NULL default '',\n".
"  \"CanMakeReplysCT\" nvarchar(5) NOT NULL default '',\n".
"  \"HideEditPostInfo\" nvarchar(5) NOT NULL default '',\n".
"  \"CanEditTopics\" nvarchar(5) NOT NULL default '',\n".
"  \"CanEditTopicsCT\" nvarchar(5) NOT NULL default '',\n".
"  \"CanEditReplys\" nvarchar(5) NOT NULL default '',\n".
"  \"CanEditReplysCT\" nvarchar(5) NOT NULL default '',\n".
"  \"CanDeleteTopics\" nvarchar(5) NOT NULL default '',\n".
"  \"CanDeleteTopicsCT\" nvarchar(5) NOT NULL default '',\n".
"  \"CanDeleteReplys\" nvarchar(5) NOT NULL default '',\n".
"  \"CanDeleteReplysCT\" nvarchar(5) NOT NULL default '',\n".
"  \"CanDoublePost\" nvarchar(5) NOT NULL default '',\n".
"  \"CanDoublePostCT\" nvarchar(5) NOT NULL default '',\n".
"  \"GotoEditPost\" nvarchar(5) NOT NULL default '',\n".
"  \"CanCloseTopics\" nvarchar(5) NOT NULL default '',\n".
"  \"CanCloseTopicsCT\" nvarchar(5) NOT NULL default '',\n".
"  \"CanPinTopics\" nvarchar(5) NOT NULL default '',\n".
"  \"CanPinTopicsCT\" nvarchar(5) NOT NULL default '',\n".
"  \"CanExecPHP\" nvarchar(5) NOT NULL default '',\n".
"  \"CanDoHTML\" nvarchar(5) NOT NULL default '',\n".
"  \"CanUseBBTags\" nvarchar(5) NOT NULL default '',\n".
"  \"CanModForum\" nvarchar(5) NOT NULL default '',\n".
"  \"CanReportPost\" nvarchar(5) NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
");", null);
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."permissions\" (\"PermissionID\", \"Name\", \"ForumID\", \"CanViewForum\", \"CanMakePolls\", \"CanMakeTopics\", \"CanMakeReplys\", \"CanMakeReplysCT\", \"HideEditPostInfo\", \"CanEditTopics\", \"CanEditTopicsCT\", \"CanEditReplys\", \"CanEditReplysCT\", \"CanDeleteTopics\", \"CanDeleteTopicsCT\", \"CanDoublePost\", \"CanDoublePostCT\", \"GotoEditPost\", \"CanDeleteReplys\", \"CanDeleteReplysCT\", \"CanCloseTopics\", \"CanCloseTopicsCT\", \"CanPinTopics\", \"CanPinTopicsCT\", \"CanExecPHP\", \"CanDoHTML\", \"CanUseBBTags\", \"CanModForum\", \"CanReportPost\") VALUES\n".
"(1, 'Admin', 0, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'),\n".
"(2, 'Moderator', 0, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'yes', 'yes', 'yes'),\n".
"(3, 'Member', 0, 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'no', 'yes', 'no', 'yes', 'no', 'yes', 'yes', 'no', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'no', 'yes'),\n".
"(4, 'Guest', 0, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no'),\n".
"(5, 'Banned', 0, 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no'),\n".
"(6, 'Validate', 0, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no');", null); 
sql_query($query,$SQLStat);
if($_POST['startblank']=="no") {
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."permissions\" (\"PermissionID\", \"Name\", \"ForumID\", \"CanViewForum\", \"CanMakePolls\", \"CanMakeTopics\", \"CanMakeReplys\", \"CanMakeReplysCT\", \"HideEditPostInfo\", \"CanEditTopics\", \"CanEditTopicsCT\", \"CanEditReplys\", \"CanEditReplysCT\", \"CanDeleteTopics\", \"CanDeleteTopicsCT\", \"CanDoublePost\", \"CanDoublePostCT\", \"GotoEditPost\", \"CanDeleteReplys\", \"CanDeleteReplysCT\", \"CanCloseTopics\", \"CanCloseTopicsCT\", \"CanPinTopics\", \"CanPinTopicsCT\", \"CanExecPHP\", \"CanDoHTML\", \"CanUseBBTags\", \"CanModForum\", \"CanReportPost\") VALUES\n".
"(1, 'Admin', 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'),\n".
"(2, 'Moderator', 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'yes', 'yes', 'yes'),\n".
"(3, 'Member', 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'no', 'yes', 'no', 'yes', 'no', 'yes', 'yes', 'no', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'no', 'yes'),\n".
"(4, 'Guest', 1, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no'),\n".
"(5, 'Banned', 1, 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no'),\n".
"(6, 'Validate', 1, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no');", null); 
sql_query($query,$SQLStat); }
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."polls\" (\n".
"  \"id\" int(15) NOT NULL IDENTITY(1,1),\n".
"  \"UserID\" int(15) NOT NULL default '0',\n".
"  \"GuestName\" nvarchar(150) NOT NULL default '',\n".
"  \"PollValues\" nvarchar(max) NOT NULL,\n".
"  \"Description\" nvarchar(max) NOT NULL,\n".
"  \"UsersVoted\" nvarchar(max) NOT NULL,\n".
"  \"IP\" nvarchar(64) NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."posts\" (\n".
"  \"id\" int(15) NOT NULL IDENTITY(1,1),\n".
"  \"TopicID\" int(15) NOT NULL default '0',\n".
"  \"ForumID\" int(15) NOT NULL default '0',\n".
"  \"CategoryID\" int(15) NOT NULL default '0',\n".
"  \"UserID\" int(15) NOT NULL default '0',\n".
"  \"GuestName\" nvarchar(150) NOT NULL default '',\n".
"  \"TimeStamp\" int(15) NOT NULL default '0',\n".
"  \"LastUpdate\" int(15) NOT NULL default '0',\n".
"  \"EditUser\" int(15) NOT NULL default '0',\n".
"  \"EditUserName\" nvarchar(150) NOT NULL default '',\n".
"  \"Post\" nvarchar(max) NOT NULL,\n".
"  \"Description\" nvarchar(max) NOT NULL,\n".
"  \"IP\" nvarchar(64) NOT NULL default '',\n".
"  \"EditIP\" nvarchar(64) NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
");", null);
sql_query($query,$SQLStat);
if($_POST['testdata']=="yes") {
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."posts\" (\"TopicID\", \"ForumID\", \"CategoryID\", \"UserID\", \"GuestName\", \"TimeStamp\", \"LastUpdate\", \"EditUser\", \"EditUserName\", \"Post\", \"Description\", \"IP\", \"EditIP\") VALUES\n".
"(1, 1, 1, -1, '".$iDB_Author."', %i, %i, 1, '".$_POST['AdminUser']."', 'Welcome to your new Internet Discussion Board! :) ', '%s', '%s', '127.0.0.1');", array($YourDate,$YourEditDate,"Welcome ".$_POST['AdminUser'],$GuestLocalIP)); 
sql_query($query,$SQLStat); }
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."restrictedwords\" (\n".
"  \"id\" int(15) NOT NULL IDENTITY(1,1),\n".
"  \"Word\" nvarchar(max) NOT NULL,\n".
"  \"RestrictedUserName\" nvarchar(5) NOT NULL default '',\n".
"  \"RestrictedTopicName\" nvarchar(5) NOT NULL default '',\n".
"  \"RestrictedEventName\" nvarchar(5) NOT NULL default '',\n".
"  \"RestrictedMessageName\" nvarchar(5) NOT NULL default '',\n".
"  \"CaseInsensitive\" nvarchar(5) NOT NULL default '',\n".
"  \"WholeWord\" nvarchar(5) NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."sessions\" (\n".
"  \"session_id\" nvarchar(250) NOT NULL default '',\n".
"  \"session_data\" nvarchar(max) NOT NULL,\n".
"  \"serialized_data\" nvarchar(max) NOT NULL,\n".
"  \"user_agent\" nvarchar(max) NOT NULL,\n".
"  \"client_hints\" nvarchar(max) NOT NULL,\n".
"  \"ip_address\" nvarchar(64) NOT NULL default '',\n".
"  \"expires\" int(15) NOT NULL default '0',\n".
"  PRIMARY KEY  (\"session_id\")\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."smileys\" (\n".
"  \"id\" int(15) NOT NULL IDENTITY(1,1),\n".
"  \"FileName\" nvarchar(max) NOT NULL,\n".
"  \"SmileName\" nvarchar(max) NOT NULL,\n".
"  \"SmileText\" nvarchar(max) NOT NULL,\n".
"  \"EmojiText\" nvarchar(max) NOT NULL,\n".
"  \"Directory\" nvarchar(max) NOT NULL,\n".
"  \"Display\" nvarchar(5) NOT NULL default '',\n".
"  \"ReplaceCI\" nvarchar(5) NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
");", null);
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."smileys\" (\"FileName\", \"SmileName\", \"SmileText\", \"EmojiText\", \"Directory\", \"Display\", \"ReplaceCI\") VALUES\n".
"('angry.png', 'Angry', ':angry:', '😠', 'smileys/', 'yes', 'yes'),\n".
"('closedeyes.png', 'Relieved', 'v_v', '😌', 'smileys/', 'yes', 'no'),\n".
"('cool.png', 'Cool', 'B)', '😎', 'smileys/', 'yes', 'no'),\n".
"('glare.png', 'Hmph', ':hmph:', '😑', 'smileys/', 'yes', 'yes'),\n".
"('glare.png', 'Hmph', '&lt;_&lt;', '😑', 'smileys/', 'no', 'no'),\n".
"('happy.png', 'Happy', '^_^', '😀', 'smileys/', 'yes', 'no'),\n".
"('hmm.png', 'Hmm', ':unsure:', '🤔', 'smileys/', 'yes', 'yes'),\n".
"('huh.png', 'Huh', ':huh:', '😕', 'smileys/', 'yes', 'yes'),\n".
"('laugh.png', 'lol', ':laugh:', '😆', 'smileys/', 'yes', 'yes'),\n".
"('lol.png', 'lol', ':lol:', '😂', 'smileys/', 'yes', 'yes'),\n".
"('mad.png', 'Mad', ':mad:', '😡', 'smileys/', 'yes', 'yes'),\n".
"('ninja.png', 'Ninja', ':ninja:', '🥷', 'smileys/', 'yes', 'yes'),\n".
"('ohno.png', 'ohno', ':ohno:', '😨', 'smileys/', 'yes', 'yes'),\n".
"('ohmy.png', 'ohmy', ':o', '😲', 'smileys/', 'yes', 'yes'),\n".
"('sad.png', 'Sad', ':(', '😢', 'smileys/', 'yes', 'no'),\n".
"('sleep.png', 'Sleep', '-_-', '😴', 'smileys/', 'yes', 'no'),\n".
"('smile.png', 'Happy', ':)', '😊', 'smileys/', 'yes', 'no'),\n".
"('sweat.png', 'Sweat', ':sweat:', '😅', 'smileys/', 'yes', 'yes'),\n".
"('tongue.png', 'Tongue', ':P', '😛', 'smileys/', 'yes', 'no'),\n".
"('wub.png', 'Wub', ':wub:', '😍', 'smileys/', 'yes', 'yes'),\n".
"('x.png', 'X', ':x:', '😣', 'smileys/', 'yes', 'yes');", null);
sql_query($query,$SQLStat);
/*
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."tagboard\" (\n".
"  \"id\" int(15) NOT NULL IDENTITY(1,1),\n".
"  \"UserID\" int(15) NOT NULL default '0',\n".
"  \"GuestName\" nvarchar(150) NOT NULL default '',\n".
"  \"TimeStamp\" int(15) NOT NULL default '0',\n".
"  \"Post\" nvarchar(max) NOT NULL,\n".
"  \"IP\" nvarchar(64) NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
");", null);
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."tagboard\" VALUES (1,-1,'".$iDB_Author."',".$YourDate.",'Welcome to Your New Tag Board. ^_^','127.0.0.1'), null); 
sql_query($query,$SQLStat);
*/
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."themes\" (\n".
"  \"id\" int(15) NOT NULL IDENTITY(1,1),\n".
"  \"Name\" nvarchar(32) NOT NULL default '',\n".
"  \"ThemeName\" nvarchar(150) NOT NULL default '',\n".
"  \"ThemeMaker\" nvarchar(150) NOT NULL default '',\n".
"  \"ThemeVersion\" nvarchar(150) NOT NULL default '',\n".
"  \"ThemeVersionType\" nvarchar(150) NOT NULL default '',\n".
"  \"ThemeSubVersion\" nvarchar(150) NOT NULL default '',\n".
"  \"MakerURL\" nvarchar(150) NOT NULL default '',\n".
"  \"CopyRight\" nvarchar(150) NOT NULL default '',\n".
"  \"WrapperString\" nvarchar(max) NOT NULL,\n".
"  \"CSS\" nvarchar(max) NOT NULL,\n".
"  \"CSSType\" nvarchar(150) NOT NULL default '',\n".
"  \"FavIcon\" nvarchar(150) NOT NULL default '',\n".
"  \"OpenGraph\" nvarchar(150) NOT NULL default '',\n".
"  \"TableStyle\" nvarchar(150) NOT NULL default '',\n".
"  \"MiniPageAltStyle\" nvarchar(150) NOT NULL default '',\n".
"  \"PreLogo\" nvarchar(150) NOT NULL default '',\n".
"  \"Logo\" nvarchar(150) NOT NULL default '',\n".
"  \"LogoStyle\" nvarchar(150) NOT NULL default '',\n".
"  \"SubLogo\" nvarchar(150) NOT NULL default '',\n".
"  \"TopicIcon\" nvarchar(150) NOT NULL default '',\n".
"  \"MovedTopicIcon\" nvarchar(150) NOT NULL default '',\n".
"  \"HotTopic\" nvarchar(150) NOT NULL default '',\n".
"  \"MovedHotTopic\" nvarchar(150) NOT NULL default '',\n".
"  \"PinTopic\" nvarchar(150) NOT NULL default '',\n".
"  \"AnnouncementTopic\" nvarchar(150) NOT NULL default '',\n".
"  \"MovedPinTopic\" nvarchar(150) NOT NULL default '',\n".
"  \"HotPinTopic\" nvarchar(150) NOT NULL default '',\n".
"  \"MovedHotPinTopic\" nvarchar(150) NOT NULL default '',\n".
"  \"ClosedTopic\" nvarchar(150) NOT NULL default '',\n".
"  \"MovedClosedTopic\" nvarchar(150) NOT NULL default '',\n".
"  \"HotClosedTopic\" nvarchar(150) NOT NULL default '',\n".
"  \"MovedHotClosedTopic\" nvarchar(150) NOT NULL default '',\n".
"  \"PinClosedTopic\" nvarchar(150) NOT NULL default '',\n".
"  \"MovedPinClosedTopic\" nvarchar(150) NOT NULL default '',\n".
"  \"HotPinClosedTopic\" nvarchar(150) NOT NULL default '',\n".
"  \"MovedHotPinClosedTopic\" nvarchar(150) NOT NULL default '',\n".
"  \"MessageRead\" nvarchar(150) NOT NULL default '',\n".
"  \"MessageUnread\" nvarchar(150) NOT NULL default '',\n".
"  \"Profile\" nvarchar(150) NOT NULL default '',\n".
"  \"WWW\" nvarchar(150) NOT NULL default '',\n".
"  \"PM\" nvarchar(150) NOT NULL default '',\n".
"  \"TopicLayout\" nvarchar(150) NOT NULL default '',\n".
"  \"AddReply\" nvarchar(150) NOT NULL default '',\n".
"  \"FastReply\" nvarchar(150) NOT NULL default '',\n".
"  \"NewTopic\" nvarchar(150) NOT NULL default '',\n".
"  \"QuoteReply\" nvarchar(150) NOT NULL default '',\n".
"  \"EditReply\" nvarchar(150) NOT NULL default '',\n".
"  \"DeleteReply\" nvarchar(150) NOT NULL default '',\n".
"  \"Report\" nvarchar(150) NOT NULL default '',\n".
"  \"LineDivider\" nvarchar(150) NOT NULL default '',\n".
"  \"ButtonDivider\" nvarchar(150) NOT NULL default '',\n".
"  \"LineDividerTopic\" nvarchar(150) NOT NULL default '',\n".
"  \"TitleDivider\" nvarchar(150) NOT NULL default '',\n".
"  \"ForumStyle\" nvarchar(150) NOT NULL default '',\n".
"  \"ForumIcon\" nvarchar(150) NOT NULL default '',\n".
"  \"SubForumIcon\" nvarchar(150) NOT NULL default '',\n".
"  \"RedirectIcon\" nvarchar(150) NOT NULL default '',\n".
"  \"TitleIcon\" nvarchar(150) NOT NULL default '',\n".
"  \"NavLinkIcon\" nvarchar(150) NOT NULL default '',\n".
"  \"NavLinkDivider\" nvarchar(150) NOT NULL default '',\n".
"  \"BoardStatsIcon\" nvarchar(150) NOT NULL default '',\n".
"  \"MemberStatsIcon\" nvarchar(150) NOT NULL default '',\n".
"  \"BirthdayStatsIcon\" nvarchar(150) NOT NULL default '',\n".
"  \"EventStatsIcon\" nvarchar(150) NOT NULL default '',\n".
"  \"OnlineStatsIcon\" nvarchar(150) NOT NULL default '',\n".
"  \"NoAvatar\" nvarchar(150) NOT NULL default '',\n".
"  \"NoAvatarSize\" nvarchar(150) NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\"),\n".
"  UNIQUE KEY \"Name\" (\"Name\")\n".
");", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."topics\" (\n".
"  \"id\" int(15) NOT NULL IDENTITY(1,1),\n".
"  \"PollID\" int(15) NOT NULL default '0',\n".
"  \"ForumID\" int(15) NOT NULL default '0',\n".
"  \"CategoryID\" int(15) NOT NULL default '0',\n".
"  \"OldForumID\" int(15) NOT NULL default '0',\n".
"  \"OldCategoryID\" int(15) NOT NULL default '0',\n".
"  \"UserID\" int(15) NOT NULL default '0',\n".
"  \"GuestName\" nvarchar(150) NOT NULL default '',\n".
"  \"TimeStamp\" int(15) NOT NULL default '0',\n".
"  \"LastUpdate\" int(15) NOT NULL default '0',\n".
"  \"TopicName\" nvarchar(150) NOT NULL default '',\n".
"  \"Description\" nvarchar(max) NOT NULL,\n".
"  \"NumReply\" int(15) NOT NULL default '0',\n".
"  \"NumViews\" int(15) NOT NULL default '0',\n".
"  \"Pinned\" int(5) NOT NULL default '0',\n".
"  \"Closed\" int(5) NOT NULL default '0',\n".
"  PRIMARY KEY  (\"id\")\n".
");", null);
sql_query($query,$SQLStat);
if($_POST['testdata']=="yes") {
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."topics\" (\"PollID\", \"ForumID\", \"CategoryID\", \"OldForumID\", \"OldCategoryID\", \"UserID\", \"GuestName\", \"TimeStamp\", \"LastUpdate\", \"TopicName\", \"Description\", \"NumReply\", \"NumViews\", \"Pinned\", \"Closed\") VALUES\n".
"(0, 1, 1, 1, 1, -1, '".$iDB_Author."', %i, %i, 'Welcome', '%s', 0, 0, 1, 1);", array($YourDate,$YourDate,"Welcome ".$_POST['AdminUser']));
sql_query($query,$SQLStat); }
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."wordfilter\" (\n".
"  \"id\" int(15) NOT NULL IDENTITY(1,1),\n".
"  \"FilterWord\" nvarchar(max) NOT NULL,\n".
"  \"Replacement\" nvarchar(max) NOT NULL,\n".
"  \"CaseInsensitive\" nvarchar(5) NOT NULL default '',\n".
"  \"WholeWord\" nvarchar(5) NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
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
$OptimizeTea = sql_query(sql_pre_query("ALTER INDEX ALL ON \"".$TableChCk[$ti]."\" REORGANIZE", null),$SQLStat);
++$ti; }
?>
