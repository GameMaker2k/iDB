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

    $FileInfo: mysql.php - Last Update: 8/30/2024 SVN 1064 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="mysql.php"||$File3Name=="/mysql.php") {
	require('index.php');
	exit(); }
if(!isset($SetupDir['setup'])) { $SetupDir['setup'] = "setup/"; }
if(!isset($SetupDir['convert'])) { $SetupDir['convert'] = "setup/convert/"; }
$query=sql_pre_query("ALTER DATABASE \"".$_POST['DatabaseName']."\" DEFAULT CHARACTER SET ".$Settings['sql_charset']." COLLATE ".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
if(isset($Settings['sql_storage_engine'])) {
// Execute the query to get the list of engines
$result = sql_query(sql_pre_query("SHOW ENGINES;", null), $SQLStat);
// Fetch all rows into an array
$engines = array();
while ($row = sql_fetch_assoc($result)) {
    $engines[] = $row;
}
// Count the number of rows
$num = count($engines);
// Process the results
$SQLEngines = null;
for ($i = 0; $i < $num; ++$i) {
    $SQLEngines[$i] = $engines[$i]['Engine'];
}
// Free the result set
sql_free_result($result);
if (!in_array($Settings['sql_storage_engine'], $SQLEngines)) {
    $Settings['sql_storage_engine'] = "InnoDB"; } }
if(!isset($Settings['sql_storage_engine'])) {
	$Settings['sql_storage_engine'] = "InnoDB"; }
// You can set this to MyISAM or Maria/Aria
if($Settings['sql_storage_engine']=="CSV") {
	$SQLStorageEngine = "CSV"; }
if($Settings['sql_storage_engine']=="Maria") {
	$SQLStorageEngine = "Maria"; }
if($Settings['sql_storage_engine']=="Aria") {
	$SQLStorageEngine = "Aria"; }
if($Settings['sql_storage_engine']=="MyISAM") {
	$SQLStorageEngine = "MyISAM"; }
if($Settings['sql_storage_engine']=="InnoDB") {
	$SQLStorageEngine = "InnoDB"; }
$parsestr = parse_url($YourWebsite);
if (!filter_var($parsestr['host'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6) || $parsestr['host'] == "localhost") {
	$GuestLocalIP = gethostbyname($parsestr['host']); } else { $GuestLocalIP = $parsestr['host']; }
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."categories\" (\n".
"  \"id\" int(15) NOT NULL auto_increment,\n".
"  \"OrderID\" int(15) NOT NULL default '0',\n".
"  \"Name\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"ShowCategory\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CategoryType\" varchar(15) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"SubShowForums\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"InSubCategory\" int(15) NOT NULL default '0',\n".
"  \"PostCountView\" int(15) NOT NULL default '0',\n".
"  \"KarmaCountView\" int(15) NOT NULL default '0',\n".
"  \"Description\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  PRIMARY KEY  (\"id\")\n".
") ENGINE=".$SQLStorageEngine."  DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."catpermissions\" (\n".
"  \"id\" int(15) NOT NULL auto_increment,\n".
"  \"PermissionID\" int(15) NOT NULL default '0',\n".
"  \"Name\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CategoryID\" int(15) NOT NULL default '0',\n".
"  \"CanViewCategory\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
") ENGINE=".$SQLStorageEngine."  DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."events\" (\n".
"  \"id\" int(15) NOT NULL auto_increment,\n".
"  \"UserID\" int(15) NOT NULL default '0',\n".
"  \"GuestName\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"EventName\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"EventText\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"TimeStamp\" int(15) NOT NULL default '0',\n".
"  \"TimeStampEnd\" int(15) NOT NULL default '0',\n".
"  \"EventMonth\" int(5) NOT NULL default '0',\n".
"  \"EventMonthEnd\" int(5) NOT NULL default '0',\n".
"  \"EventDay\" int(5) NOT NULL default '0',\n".
"  \"EventDayEnd\" int(5) NOT NULL default '0',\n".
"  \"EventYear\" int(5) NOT NULL default '0',\n".
"  \"EventYearEnd\" int(5) NOT NULL default '0',\n".
"  \"IP\" varchar(64) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
") ENGINE=".$SQLStorageEngine."  DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."forums\" (\n".
"  \"id\" int(15) NOT NULL auto_increment,\n".
"  \"CategoryID\" int(15) NOT NULL default '0',\n".
"  \"OrderID\" int(15) NOT NULL default '0',\n".
"  \"Name\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"ShowForum\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"ForumType\" varchar(15) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"InSubForum\" int(15) NOT NULL default '0',\n".
"  \"RedirectURL\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"Redirects\" int(15) NOT NULL default '0',\n".
"  \"NumViews\" int(15) NOT NULL default '0',\n".
"  \"Description\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"PostCountAdd\" varchar(15) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"PostCountView\" int(15) NOT NULL default '0',\n".
"  \"KarmaCountView\" int(15) NOT NULL default '0',\n".
"  \"CanHaveTopics\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"HotTopicPosts\" int(15) NOT NULL default '0',\n".
"  \"NumPosts\" int(15) NOT NULL default '0',\n".
"  \"NumTopics\" int(15) NOT NULL default '0',\n".
"  PRIMARY KEY  (\"id\")\n".
") ENGINE=".$SQLStorageEngine."  DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."groups\" (\n".
"  \"id\" int(15) NOT NULL auto_increment,\n".
"  \"Name\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"PermissionID\" int(15) NOT NULL default '0',\n".
"  \"NamePrefix\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"NameSuffix\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanViewBoard\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanViewOffLine\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanEditProfile\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanAddEvents\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanPM\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanSearch\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanExecPHP\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanDoHTML\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanUseBBTags\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanModForum\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanViewIPAddress\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanViewUserAgent\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanViewAnonymous\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"FloodControl\" int(5) NOT NULL default '0',\n".
"  \"SearchFlood\" int(5) NOT NULL default '0',\n".
"  \"PromoteTo\" int(15) NOT NULL default '0',\n".
"  \"PromotePosts\" int(15) NOT NULL default '0',\n".
"  \"PromoteKarma\" int(15) NOT NULL default '0',\n".
"  \"DemoteTo\" int(15) NOT NULL default '0',\n".
"  \"DemotePosts\" int(15) NOT NULL default '0',\n".
"  \"DemoteKarma\" int(15) NOT NULL default '0',\n".
"  \"HasModCP\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"HasAdminCP\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"ViewDBInfo\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\"),\n".
"  UNIQUE KEY \"Name\" (\"Name\")\n".
") ENGINE=".$SQLStorageEngine."  DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."ranks\" (\n".
"  \"id\" int(15) NOT NULL auto_increment,\n".
"  \"Name\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"PromoteTo\" int(15) NOT NULL default '0',\n".
"  \"PromotePosts\" int(15) NOT NULL default '0',\n".
"  \"PromoteKarma\" int(15) NOT NULL default '0',\n".
"  \"DemoteTo\" int(15) NOT NULL default '0',\n".
"  \"DemotePosts\" int(15) NOT NULL default '0',\n".
"  \"DemoteKarma\" int(15) NOT NULL default '0',\n".
"  PRIMARY KEY  (\"id\"),\n".
"  UNIQUE KEY \"Name\" (\"Name\")\n".
") ENGINE=".$SQLStorageEngine."  DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."levels\" (\n".
"  \"id\" int(15) NOT NULL auto_increment,\n".
"  \"Name\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"PromoteTo\" int(15) NOT NULL default '0',\n".
"  \"PromotePosts\" int(15) NOT NULL default '0',\n".
"  \"PromoteKarma\" int(15) NOT NULL default '0',\n".
"  \"DemoteTo\" int(15) NOT NULL default '0',\n".
"  \"DemotePosts\" int(15) NOT NULL default '0',\n".
"  \"DemoteKarma\" int(15) NOT NULL default '0',\n".
"  PRIMARY KEY  (\"id\"),\n".
"  UNIQUE KEY \"Name\" (\"Name\")\n".
") ENGINE=".$SQLStorageEngine."  DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."members\" (\n".
"  \"id\" int(15) NOT NULL auto_increment,\n".
"  \"Name\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"Handle\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"UserPassword\" varchar(256) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"HashType\" varchar(50) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"Email\" varchar(256) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"GroupID\" int(15) NOT NULL default '0',\n".
"  \"LevelID\" int(15) NOT NULL default '0',\n".
"  \"RankID\" int(15) NOT NULL default '0',\n".
"  \"Validated\" varchar(20) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"HiddenMember\" varchar(20) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"WarnLevel\" int(15) NOT NULL default '0',\n".
"  \"Interests\" text COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"Title\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"Joined\" int(15) NOT NULL default '0',\n".
"  \"LastActive\" int(15) NOT NULL default '0',\n".
"  \"LastLogin\" int(15) NOT NULL default '0',\n".
"  \"LastPostTime\" int(15) NOT NULL default '0',\n".
"  \"BanTime\" int(15) NOT NULL default '0',\n".
"  \"BirthDay\" int(5) NOT NULL default '0',\n".
"  \"BirthMonth\" int(5) NOT NULL default '0',\n".
"  \"BirthYear\" int(5) NOT NULL default '0',\n".
"  \"Signature\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"Notes\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"Bio\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"Avatar\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"AvatarSize\" varchar(10) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"Website\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"Location\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"Gender\" varchar(15) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"PostCount\" int(15) NOT NULL default '0',\n".
"  \"Karma\" int(15) NOT NULL default '0',\n".
"  \"KarmaUpdate\" int(15) NOT NULL default '0',\n".
"  \"RepliesPerPage\" int(5) NOT NULL default '0',\n".
"  \"TopicsPerPage\" int(5) NOT NULL default '0',\n".
"  \"MessagesPerPage\" int(5) NOT NULL default '0',\n".
"  \"TimeZone\" varchar(256) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"DateFormat\" varchar(15) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"TimeFormat\" varchar(15) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"UseTheme\" varchar(32) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"IgnoreSignitures\" varchar(32) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"IgnoreAdvatars\" varchar(32) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"IgnoreUsers\" varchar(32) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"IP\" varchar(64) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"Salt\" varchar(50) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\"),\n".
"  UNIQUE KEY \"Name\" (\"Name\"),\n".
"  UNIQUE KEY \"Handle\" (\"Name\"),\n".
"  UNIQUE KEY \"Email\" (\"Email\")\n".
") ENGINE=".$SQLStorageEngine."  DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."mempermissions\" (\n".
"  \"id\" int(15) NOT NULL auto_increment,\n".
"  \"PermissionID\" int(15) NOT NULL default '0',\n".
"  \"CanViewBoard\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanViewOffLine\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanEditProfile\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanAddEvents\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanPM\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanSearch\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanExecPHP\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanDoHTML\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanUseBBTags\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanModForum\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanViewIPAddress\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanViewUserAgent\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanViewAnonymous\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"FloodControl\" int(5) NOT NULL default '0',\n".
"  \"SearchFlood\" int(5) NOT NULL default '0',\n".
"  \"HasModCP\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"HasAdminCP\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"ViewDBInfo\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
") ENGINE=".$SQLStorageEngine."  DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."messenger\" (\n".
"  \"id\" int(15) NOT NULL auto_increment,\n".
"  \"DiscussionID\" int(15) NOT NULL default '0',\n".
"  \"SenderID\" int(15) NOT NULL default '0',\n".
"  \"ReciverID\" int(15) NOT NULL default '0',\n".
"  \"GuestName\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"MessageTitle\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"MessageText\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"Description\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"DateSend\" int(15) NOT NULL default '0',\n".
"  \"Read\" int(5) NOT NULL default '0',\n".
"  \"IP\" varchar(64) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
") ENGINE=".$SQLStorageEngine."  DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."permissions\" (\n".
"  \"id\" int(15) NOT NULL auto_increment,\n".
"  \"PermissionID\" int(15) NOT NULL default '0',\n".
"  \"Name\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"ForumID\" int(15) NOT NULL default '0',\n".
"  \"CanViewForum\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanMakePolls\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanMakeTopics\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanMakeReplys\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanMakeReplysCT\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"HideEditPostInfo\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanEditTopics\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanEditTopicsCT\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanEditReplys\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanEditReplysCT\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanDeleteTopics\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanDeleteTopicsCT\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanDeleteReplys\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanDeleteReplysCT\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanDoublePost\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanDoublePostCT\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"GotoEditPost\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanCloseTopics\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanCloseTopicsCT\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanPinTopics\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanPinTopicsCT\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanExecPHP\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanDoHTML\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanUseBBTags\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanModForum\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CanReportPost\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
") ENGINE=".$SQLStorageEngine."  DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."polls\" (\n".
"  \"id\" int(15) NOT NULL auto_increment,\n".
"  \"UserID\" int(15) NOT NULL default '0',\n".
"  \"GuestName\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"PollValues\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"Description\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"UsersVoted\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"IP\" varchar(64) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
") ENGINE=".$SQLStorageEngine."  DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."posts\" (\n".
"  \"id\" int(15) NOT NULL auto_increment,\n".
"  \"TopicID\" int(15) NOT NULL default '0',\n".
"  \"ForumID\" int(15) NOT NULL default '0',\n".
"  \"CategoryID\" int(15) NOT NULL default '0',\n".
"  \"UserID\" int(15) NOT NULL default '0',\n".
"  \"GuestName\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"TimeStamp\" int(15) NOT NULL default '0',\n".
"  \"LastUpdate\" int(15) NOT NULL default '0',\n".
"  \"EditUser\" int(15) NOT NULL default '0',\n".
"  \"EditUserName\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"Post\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"Description\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"IP\" varchar(64) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"EditIP\" varchar(64) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
") ENGINE=".$SQLStorageEngine."  DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."restrictedwords\" (\n".
"  \"id\" int(15) NOT NULL auto_increment,\n".
"  \"Word\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"RestrictedUserName\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"RestrictedTopicName\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"RestrictedEventName\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"RestrictedMessageName\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CaseInsensitive\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"WholeWord\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
") ENGINE=".$SQLStorageEngine."  DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."sessions\" (\n".
"  \"session_id\" varchar(250) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"session_data\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"serialized_data\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"user_agent\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"client_hints\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"ip_address\" varchar(64) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"expires\" int(15) NOT NULL default '0',\n".
"  PRIMARY KEY  (\"session_id\")\n".
") ENGINE=".$SQLStorageEngine." DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."smileys\" (\n".
"  \"id\" int(15) NOT NULL auto_increment,\n".
"  \"FileName\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"SmileName\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"SmileText\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"EmojiText\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"Directory\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"Display\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"ReplaceCI\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
") ENGINE=".$SQLStorageEngine."  DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
/*
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."tagboard\" (\n".
"  \"id\" int(15) NOT NULL auto_increment,\n".
"  \"UserID\" int(15) NOT NULL default '0',\n".
"  \"GuestName\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"TimeStamp\" int(15) NOT NULL default '0',\n".
"  \"Post\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"IP\" varchar(64) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
") ENGINE=".$SQLStorageEngine."  DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
*/
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."themes\" (\n".
"  \"id\" int(15) NOT NULL auto_increment,\n".
"  \"Name\" varchar(32) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"ThemeName\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"ThemeMaker\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"ThemeVersion\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"ThemeVersionType\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"ThemeSubVersion\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"MakerURL\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"CopyRight\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"WrapperString\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"CSS\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"CSSType\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"FavIcon\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"OpenGraph\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"TableStyle\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"MiniPageAltStyle\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"PreLogo\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"Logo\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"LogoStyle\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"SubLogo\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"TopicIcon\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"MovedTopicIcon\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"HotTopic\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"MovedHotTopic\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"PinTopic\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"AnnouncementTopic\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"MovedPinTopic\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"HotPinTopic\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"MovedHotPinTopic\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"ClosedTopic\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"MovedClosedTopic\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"HotClosedTopic\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"MovedHotClosedTopic\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"PinClosedTopic\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"MovedPinClosedTopic\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"HotPinClosedTopic\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"MovedHotPinClosedTopic\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"MessageRead\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"MessageUnread\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"Profile\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"WWW\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"PM\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"TopicLayout\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"AddReply\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"FastReply\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"NewTopic\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"QuoteReply\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"EditReply\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"DeleteReply\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"Report\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"LineDivider\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"ButtonDivider\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"LineDividerTopic\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"TitleDivider\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"ForumStyle\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"ForumIcon\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"SubForumIcon\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"RedirectIcon\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"TitleIcon\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"NavLinkIcon\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"NavLinkDivider\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"BoardStatsIcon\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"MemberStatsIcon\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"BirthdayStatsIcon\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"EventStatsIcon\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"OnlineStatsIcon\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"NoAvatar\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"NoAvatarSize\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\"),\n".
"  UNIQUE KEY \"Name\" (\"Name\")\n".
") ENGINE=".$SQLStorageEngine."  DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."topics\" (\n".
"  \"id\" int(15) NOT NULL auto_increment,\n".
"  \"PollID\" int(15) NOT NULL default '0',\n".
"  \"ForumID\" int(15) NOT NULL default '0',\n".
"  \"CategoryID\" int(15) NOT NULL default '0',\n".
"  \"OldForumID\" int(15) NOT NULL default '0',\n".
"  \"OldCategoryID\" int(15) NOT NULL default '0',\n".
"  \"UserID\" int(15) NOT NULL default '0',\n".
"  \"GuestName\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"TimeStamp\" int(15) NOT NULL default '0',\n".
"  \"LastUpdate\" int(15) NOT NULL default '0',\n".
"  \"TopicName\" varchar(150) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"Description\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"NumReply\" int(15) NOT NULL default '0',\n".
"  \"NumViews\" int(15) NOT NULL default '0',\n".
"  \"Pinned\" int(5) NOT NULL default '0',\n".
"  \"Closed\" int(5) NOT NULL default '0',\n".
"  PRIMARY KEY  (\"id\")\n".
") ENGINE=".$SQLStorageEngine."  DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE IF NOT EXISTS \"".$_POST['tableprefix']."wordfilter\" (\n".
"  \"id\" int(15) NOT NULL auto_increment,\n".
"  \"FilterWord\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"Replacement\" text COLLATE ".$Settings['sql_collate']." NOT NULL,\n".
"  \"CaseInsensitive\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  \"WholeWord\" varchar(5) COLLATE ".$Settings['sql_collate']." NOT NULL default '',\n".
"  PRIMARY KEY  (\"id\")\n".
") ENGINE=".$SQLStorageEngine."  DEFAULT CHARSET=".$Settings['sql_charset']." COLLATE=".$Settings['sql_collate'].";", null);
sql_query($query,$SQLStat);
$TableChCk = array("categories", "catpermissions", "events", "forums", "groups", "levels", "members", "mempermissions", "messenger", "permissions", "polls", "posts", "ranks", "restrictedwords", "sessions", "smileys", "themes", "topics", "wordfilter");
$TablePreFix = $_POST['tableprefix'];
function add_prefix($tarray) {
global $TablePreFix;
return $TablePreFix.$tarray; }
$TableChCk = array_map("add_prefix",$TableChCk);
$tcount = count($TableChCk); $ti = 0;
while ($ti < $tcount) {
$OptimizeTea = sql_query(sql_pre_query("OPTIMIZE TABLE \"".$TableChCk[$ti]."\"", null),$SQLStat);
++$ti; }
?>
