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
    iDB Installer made by Game Maker 2k - http://idb.berlios.net/

    $FileInfo: cubrid.php - Last Update: 08/12/2011 SVN 748 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="cubrid.php"||$File3Name=="/cubrid.php") {
	require('index.php');
	exit(); }
if(!isset($SetupDir['setup'])) { $SetupDir['setup'] = "setup/"; }
if(!isset($SetupDir['convert'])) { $SetupDir['convert'] = "setup/convert/"; }
//$query=sql_pre_query("ALTER DATABASE \"".$_POST['DatabaseName']."\" DEFAULT CHARACTER SET ".$Settings['sql_charset']." COLLATE ".$Settings['sql_collate'].";", array(null));
//sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."categories\" (\n".
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
");", array(null));
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."categories\" (\"OrderID\", \"Name\", \"ShowCategory\", \"CategoryType\", \"SubShowForums\", \"InSubCategory\", \"PostCountView\", \"KarmaCountView\", \"Description\")\n". 
"VALUES (1, 'A Test Category', 'yes', 'category', 'yes', 0, 0, 0, 'A test category that may be removed at any time.');", array(null));
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."catpermissions\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"PermissionID\" INTEGER NOT NULL default '0',\n".
"  \"Name\" VARCHAR(150) NOT NULL default '',\n".
"  \"CategoryID\" INTEGER NOT NULL default '0',\n".
"  \"CanViewCategory\" VARCHAR(5) NOT NULL default ''\n".
");", array(null));
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."catpermissions\" (\"PermissionID\", \"Name\", \"CategoryID\", \"CanViewCategory\") VALUES\n".
"(1, 'Admin', 1, 'yes'),\n".
"(2, 'Moderator', 1, 'yes'),\n".
"(3, 'Member', 1, 'yes'),\n".
"(4, 'Guest', 1, 'yes'),\n".
"(5, 'Banned', 1, 'no'),\n".
"(6, 'Validate', 1, 'yes');", array(null)); 
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."events\" (\n".
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
"  \"IP\" VARCHAR(50) NOT NULL default ''\n".
");", array(null));
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."events\" (\"UserID\", \"GuestName\", \"EventName\", \"EventText\", \"TimeStamp\", \"TimeStampEnd\", \"EventMonth\", \"EventMonthEnd\", \"EventDay\", \"EventDayEnd\", \"EventYear\", \"EventYearEnd\", \"IP\") VALUES\n".
"(-1, '".$iDB_Author."', 'iDB Install', 'This is the start date of your board. ^_^', %i, %i, %i, %i, %i, %i, %i, %i, '127.0.0.1');", array($YourDate,$YourDateEnd,$EventMonth,$EventMonthEnd,$EventDay,$EventDayEnd,$EventYear,$EventYearEnd));
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."forums\" (\n".
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
");", array(null));
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."forums\" (\"CategoryID\", \"OrderID\", \"Name\", \"ShowForum\", \"ForumType\", \"InSubForum\", \"RedirectURL\", \"Redirects\", \"NumViews\", \"Description\", \"PostCountAdd\", \"PostCountView\", \"KarmaCountView\", \"CanHaveTopics\", \"HotTopicPosts\", \"NumPosts\", \"NumTopics\") VALUES\n".
"(1, 1, 'A Test Forum', 'yes', 'forum', 0, 'http://', 0, 0, 'A test forum that may be removed at any time.', 'off', 0, 0, 'yes', 15, 1, 1);", array(null));
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."groups\" (\n".
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
"  \"CanUseBBags\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanModForum\" VARCHAR(5) NOT NULL default '',\n".
"  \"FloodControl\" INTEGER NOT NULL default '0',\n".
"  \"SearchFlood\" INTEGER NOT NULL default '0',\n".
"  \"PromoteTo\" INTEGER NOT NULL default '0',\n".
"  \"PromotePosts\" INTEGER NOT NULL default '0',\n".
"  \"PromoteKarma\" INTEGER NOT NULL default '0',\n".
"  \"HasModCP\" VARCHAR(5) NOT NULL default '',\n".
"  \"HasAdminCP\" VARCHAR(5) NOT NULL default '',\n".
"  \"ViewDBInfo\" VARCHAR(5) NOT NULL default ''\n".
");", array(null));
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."groups\" (\"Name\", \"PermissionID\", \"NamePrefix\", \"NameSuffix\", \"CanViewBoard\", \"CanViewOffLine\", \"CanEditProfile\", \"CanAddEvents\", \"CanPM\", \"CanSearch\", \"CanExecPHP\", \"CanDoHTML\", \"CanUseBBags\", \"CanModForum\", \"FloodControl\", \"SearchFlood\", \"PromoteTo\", \"PromotePosts\", \"PromoteKarma\", \"HasModCP\", \"HasAdminCP\", \"ViewDBInfo\") VALUES\n".
"('Admin', 1, '', '', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'yes', 'yes', 30, 30, 0, 0, 0, 'yes', 'yes', 'yes'),\n".
"('Moderator', 2, '', '', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'yes', 'yes', 30, 30, 0, 0, 0, 'yes', 'no', 'no'),\n".
"('Member', 3, '', '', 'yes', 'no', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'yes', 'no', 30, 30, 0, 0, 0, 'no', 'no', 'no'),\n".
"('Guest', 4, '', '', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'no', 30, 30, 0, 0, 0, 'no', 'no', 'no'),\n".
"('Banned', 5, '', '', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 30, 30, 0, 0, 0, 'no', 'no', 'no'),\n".
"('Validate', 6, '', '', 'yes', 'no', 'yes', 'no', 'no', 'yes', 'no', 'no', 'yes', 'no', 30, 30, 0, 0, 0, 'no', 'no', 'no');", array(null)); 
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."members\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"Name\" VARCHAR(150) NOT NULL default '' UNIQUE,\n".
"  \"UserPassword\" VARCHAR(250) NOT NULL default '',\n".
"  \"HashType\" VARCHAR(50) NOT NULL default '',\n".
"  \"Email\" VARCHAR(256) NOT NULL default '' UNIQUE,\n".
"  \"GroupID\" INTEGER NOT NULL default '0',\n".
"  \"Validated\" VARCHAR(20) NOT NULL default '',\n".
"  \"HiddenMember\" VARCHAR(20) NOT NULL default '',\n".
"  \"WarnLevel\" INTEGER NOT NULL default '0',\n".
"  \"Interests\" STRING NOT NULL default '',\n".
"  \"Title\" VARCHAR(150) NOT NULL default '',\n".
"  \"Joined\" INTEGER NOT NULL default '0',\n".
"  \"LastActive\" INTEGER NOT NULL default '0',\n".
"  \"LastPostTime\" INTEGER NOT NULL default '0',\n".
"  \"BanTime\" INTEGER NOT NULL default '0',\n".
"  \"BirthDay\" INTEGER NOT NULL default '0',\n".
"  \"BirthMonth\" INTEGER NOT NULL default '0',\n".
"  \"BirthYear\" INTEGER NOT NULL default '0',\n".
"  \"Signature\" STRING NOT NULL,\n".
"  \"Notes\" STRING NOT NULL,\n".
"  \"Avatar\" VARCHAR(150) NOT NULL default '',\n".
"  \"AvatarSize\" VARCHAR(10) NOT NULL default '',\n".
"  \"Website\" VARCHAR(150) NOT NULL default '',\n".
"  \"Gender\" VARCHAR(15) NOT NULL default '',\n".
"  \"PostCount\" INTEGER NOT NULL default '0',\n".
"  \"Karma\" INTEGER NOT NULL default '0',\n".
"  \"KarmaUpdate\" INTEGER NOT NULL default '0',\n".
"  \"RepliesPerPage\" INTEGER NOT NULL default '0',\n".
"  \"TopicsPerPage\" INTEGER NOT NULL default '0',\n".
"  \"MessagesPerPage\" INTEGER NOT NULL default '0',\n".
"  \"TimeZone\" VARCHAR(5) NOT NULL default '0',\n".
"  \"DateFormat\" VARCHAR(15) NOT NULL default '0',\n".
"  \"TimeFormat\" VARCHAR(15) NOT NULL default '0',\n".
"  \"DST\" VARCHAR(5) NOT NULL default '0',\n".
"  \"UseTheme\" VARCHAR(32) NOT NULL default '0',\n".
"  \"IP\" VARCHAR(50) NOT NULL default '',\n".
"  \"Salt\" VARCHAR(50) NOT NULL default ''\n".
");", array(null));
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."members\" (\"id\", \"Name\", \"UserPassword\", \"HashType\", \"Email\", \"GroupID\", \"Validated\", \"HiddenMember\", \"WarnLevel\", \"Interests\", \"Title\", \"Joined\", \"LastActive\", \"LastPostTime\", \"BanTime\", \"BirthDay\", \"BirthMonth\", \"BirthYear\", \"Signature\", \"Notes\", \"Avatar\", \"AvatarSize\", \"Website\", \"Gender\", \"PostCount\", \"Karma\", \"KarmaUpdate\", \"RepliesPerPage\", \"TopicsPerPage\", \"MessagesPerPage\", \"TimeZone\", \"DateFormat\", \"TimeFormat\", \"DST\", \"UseTheme\", \"IP\", \"Salt\") VALUES\n".
"(-1, 'Guest', '%s', 'GuestPassword', '%s', 4, 'no', 'yes', 0, 'Guest Account', 'Guest', %i, %i, '0', '0', '0', '0', '0', '[B]Test[/B] :)', 'Your Notes', 'http://', '100x100', '%s', 'UnKnow', 1, 0, 0, 10, 10, 10, '%s', '%s', '%s', '%s', '%s', '127.0.0.1', '%s'),\n".
"(1, '%s', '%s', '".$iDBHashType."', '%s', 1, 'yes', 'no', 0, '%s', 'Admin', %i, %i, '0', '0', '0', '0', '0', '%s', 'Your Notes', '%s', '100x100', '%s', 'UnKnow', 0, 0, 0, 10, 10, 10, '%s', '%s', '%s', '%s', '%s', '%s', '%s');", array($GuestPassword,$GEmail,$YourDate,$YourDate,$YourWebsite,$AdminTime,$_POST['iDBDateFormat'],$_POST['iDBTimeFormat'],$AdminDST,$_POST['DefaultTheme'],$GSalt,$_POST['AdminUser'],$NewPassword,$_POST['AdminEmail'],$Interests,$YourDate,$YourDate,$NewSignature,$Avatar,$YourWebsite,$AdminTime,$_POST['iDBDateFormat'],$_POST['iDBTimeFormat'],$AdminDST,$_POST['DefaultTheme'],$UserIP,$YourSalt));
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."mempermissions\" (\n".
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
"  \"CanUseBBags\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanModForum\" VARCHAR(5) NOT NULL default '',\n".
"  \"FloodControl\" INTEGER NOT NULL default '0',\n".
"  \"SearchFlood\" INTEGER NOT NULL default '0',\n".
"  \"HasModCP\" VARCHAR(5) NOT NULL default '',\n".
"  \"HasAdminCP\" VARCHAR(5) NOT NULL default '',\n".
"  \"ViewDBInfo\" VARCHAR(5) NOT NULL default ''\n".
");", array(null));
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."mempermissions\" (\"id\", \"PermissionID\", \"CanViewBoard\", \"CanViewOffLine\", \"CanEditProfile\", \"CanAddEvents\", \"CanPM\", \"CanSearch\", \"CanExecPHP\", \"CanDoHTML\", \"CanUseBBags\", \"CanModForum\", \"FloodControl\", \"SearchFlood\", \"HasModCP\", \"HasAdminCP\", \"ViewDBInfo\") VALUES\n".
"(-1, 0, 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', -1, -1, 'group', 'group', 'group'),\n".
"(1, 0, 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', -1, -1, 'group', 'group', 'group');", array(null));
//"(-1, 0, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 30, 30, 'no', 'no', 'no'),\n".
//"(1, 0, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'yes', 'yes', 30, 30, 'yes', 'yes', 'yes');", array(null));
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."messenger\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"SenderID\" INTEGER NOT NULL default '0',\n".
"  \"ReciverID\" INTEGER NOT NULL default '0',\n".
"  \"GuestName\" VARCHAR(150) NOT NULL default '',\n".
"  \"MessageTitle\" VARCHAR(150) NOT NULL default '',\n".
"  \"MessageText\" STRING NOT NULL,\n".
"  \"Description\" STRING NOT NULL,\n".
"  \"DateSend\" INTEGER NOT NULL default '0',\n".
"  \"Read\" INTEGER NOT NULL default '0',\n".
"  \"IP\" VARCHAR(50) NOT NULL default ''\n".
");", array(null));
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."messenger\" (\"SenderID\", \"ReciverID\", \"GuestName\", \"MessageTitle\", \"MessageText\", \"Description\", \"DateSend\", \"Read\", \"IP\") VALUES\n".
"(-1, 1, '".$iDB_Author."', 'Welcome', 'Welcome to your new Internet Discussion Board! :)\r\nThis is a Test PM. :P ', 'Welcome %s', %i, 0, '127.0.0.1');", array($_POST['AdminUser'],$YourDate));
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."permissions\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"PermissionID\" INTEGER NOT NULL default '0',\n".
"  \"Name\" VARCHAR(150) NOT NULL default '',\n".
"  \"ForumID\" INTEGER NOT NULL default '0',\n".
"  \"CanViewForum\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanMakePolls\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanMakeTopics\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanMakeReplys\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanMakeReplysCT\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanEditTopics\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanEditTopicsCT\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanEditReplys\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanEditReplysCT\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanDeleteTopics\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanDeleteTopicsCT\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanDeleteReplys\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanDeleteReplysCT\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanCloseTopics\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanPinTopics\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanExecPHP\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanDoHTML\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanUseBBags\" VARCHAR(5) NOT NULL default '',\n".
"  \"CanModForum\" VARCHAR(5) NOT NULL default ''\n".
");", array(null));
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."permissions\" (\"PermissionID\", \"Name\", \"ForumID\", \"CanViewForum\", \"CanMakePolls\", \"CanMakeTopics\", \"CanMakeReplys\", \"CanMakeReplysCT\", \"CanEditTopics\", \"CanEditTopicsCT\", \"CanEditReplys\", \"CanEditReplysCT\", \"CanDeleteTopics\", \"CanDeleteTopicsCT\", \"CanDeleteReplys\", \"CanDeleteReplysCT\", \"CanCloseTopics\", \"CanPinTopics\", \"CanExecPHP\", \"CanDoHTML\", \"CanUseBBags\", \"CanModForum\") VALUES\n".
"(1, 'Admin', 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'),\n".
"(2, 'Moderator', 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'yes', 'yes'),\n".
"(3, 'Member', 1, 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'no', 'yes', 'no', 'yes', 'no', 'yes', 'no', 'no', 'no', 'no', 'no', 'yes', 'no'),\n".
"(4, 'Guest', 1, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no'),\n".
"(5, 'Banned', 1, 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no'),\n".
"(6, 'Validate', 1, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no');", array(null)); 
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."polls\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"UserID\" INTEGER NOT NULL default '0',\n".
"  \"GuestName\" VARCHAR(150) NOT NULL default '',\n".
"  \"PollValues\" STRING NOT NULL,\n".
"  \"Description\" STRING NOT NULL,\n".
"  \"UsersVoted\" STRING NOT NULL,\n".
"  \"IP\" VARCHAR(50) NOT NULL default ''\n".
");", array(null));
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."posts\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"TopicID\" INTEGER NOT NULL default '0',\n".
"  \"ForumID\" INTEGER NOT NULL default '0',\n".
"  \"CategoryID\" INTEGER NOT NULL default '0',\n".
"  \"UserID\" INTEGER NOT NULL default '0',\n".
"  \"GuestName\" VARCHAR(150) NOT NULL default '',\n".
"  \"TimeStamp\" INTEGER NOT NULL default '0',\n".
"  \"LastUpdate\" INTEGER NOT NULL default '0',\n".
"  \"EditUser\" INTEGER NOT NULL default '0',\n".
"  \"EditUserName\" VARCHAR(150) NOT NULL default '',\n".
"  \"Post\" STRING NOT NULL,\n".
"  \"Description\" STRING NOT NULL,\n".
"  \"IP\" VARCHAR(50) NOT NULL default '',\n".
"  \"EditIP\" VARCHAR(50) NOT NULL default ''\n".
");", array(null));
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."posts\" (\"TopicID\", \"ForumID\", \"CategoryID\", \"UserID\", \"GuestName\", \"TimeStamp\", \"LastUpdate\", \"EditUser\", \"EditUserName\", \"Post\", \"Description\", \"IP\", \"EditIP\") VALUES\n".
"(1, 1, 1, -1, '".$iDB_Author."', %i, %i, 1, '".$_POST['AdminUser']."', 'Welcome to your new Internet Discussion Board! :) ', 'Welcome %s', '127.0.0.1', '127.0.0.1');", array($YourDate,$YourEditDate,$_POST['AdminUser'])); 
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."restrictedwords\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"Word\" STRING NOT NULL,\n".
"  \"RestrictedUserName\" VARCHAR(5) NOT NULL default '',\n".
"  \"RestrictedTopicName\" VARCHAR(5) NOT NULL default '',\n".
"  \"RestrictedEventName\" VARCHAR(5) NOT NULL default '',\n".
"  \"RestrictedMessageName\" VARCHAR(5) NOT NULL default '',\n".
"  \"CaseInsensitive\" VARCHAR(5) NOT NULL default '',\n".
"  \"WholeWord\" VARCHAR(5) NOT NULL default ''\n".
");", array(null));
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."sessions\" (\n".
"  \"session_id\" VARCHAR(250) NOT NULL default '' PRIMARY KEY,\n".
"  \"session_data\" STRING NOT NULL,\n".
"  \"user_agent\" STRING NOT NULL,\n".
"  \"ip_address\" VARCHAR(20) NOT NULL default '',\n".
"  \"expires\" INTEGER NOT NULL default '0'\n".
");", array(null));
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."smileys\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"FileName\" STRING NOT NULL,\n".
"  \"SmileName\" STRING NOT NULL,\n".
"  \"SmileText\" STRING NOT NULL,\n".
"  \"Directory\" STRING NOT NULL,\n".
"  \"Display\" VARCHAR(5) NOT NULL default '',\n".
"  \"ReplaceCI\" VARCHAR(5) NOT NULL default ''\n".
");", array(null));
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."smileys\" (\"FileName\", \"SmileName\", \"SmileText\", \"Directory\", \"Display\", \"ReplaceCI\") VALUES\n".
"('angry.png', 'Angry', ':angry:', 'smileys/', 'yes', 'yes'),\n".
"('closedeyes.png', 'Sleep', 'v_v', 'smileys/', 'yes', 'no'),\n".
"('cool.png', 'Cool', 'B)', 'smileys/', 'yes', 'no'),\n".
"('glare.png', 'Hmph', ':hmph:', 'smileys/', 'yes', 'yes'),\n".
"('happy.png', 'Happy', '^_^', 'smileys/', 'yes', 'no'),\n".
"('hmm.png', 'Hmm', ':unsure:', 'smileys/', 'yes', 'yes'),\n".
"('huh.png', 'Huh', ':huh:', 'smileys/', 'yes', 'yes'),\n".
"('laugh.png', 'lol', ':laugh:', 'smileys/', 'yes', 'yes'),\n".
"('lol.png', 'lol', ':lol:', 'smileys/', 'yes', 'yes'),\n".
"('mad.png', 'Mad', ':mad:', 'smileys/', 'yes', 'yes'),\n".
"('ninja.png', 'Ninja', ':ninja:', 'smileys/', 'yes', 'yes'),\n".
"('ohno.png', 'ohno', ':ohno:', 'smileys/', 'yes', 'yes'),\n".
"('ohmy.png', 'ohmy', ':o', 'smileys/', 'yes', 'yes'),\n".
"('sad.png', 'Sad', ':(', 'smileys/', 'yes', 'no'),\n".
"('sleep.png', 'Sleep', '-_-', 'smileys/', 'yes', 'no'),\n".
"('smile.png', 'Happy', ':)', 'smileys/', 'yes', 'no'),\n".
"('sweat.png', 'Sweat', ':sweat:', 'smileys/', 'yes', 'yes'),\n".
"('tongue.png', 'Tongue', ':P', 'smileys/', 'yes', 'no'),\n".
"('wub.png', 'Wub', ':wub:', 'smileys/', 'yes', 'yes'),\n".
"('x.png', 'X', ':x:', 'smileys/', 'yes', 'yes');", array(null));
sql_query($query,$SQLStat);
/*
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."tagboard\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"UserID\" INTEGER NOT NULL default '0',\n".
"  \"GuestName\" VARCHAR(150) NOT NULL default '',\n".
"  \"TimeStamp\" INTEGER NOT NULL default '0',\n".
"  \"Post\" STRING NOT NULL,\n".
"  \"IP\" VARCHAR(50) NOT NULL default ''\n".
");", array(null));
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."tagboard\" VALUES (1,-1,'".$iDB_Author."',".$YourDate.",'Welcome to Your New Tag Board. ^_^','127.0.0.1'), array(null)); 
sql_query($query,$SQLStat);
*/
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."themes\" (\n".
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
"  \"StatsIcon\" VARCHAR(150) NOT NULL default '',\n".
"  \"NoAvatar\" VARCHAR(150) NOT NULL default '',\n".
"  \"NoAvatarSize\" VARCHAR(150) NOT NULL default ''\n".
");", array(null));
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."topics\" (\n".
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
");", array(null));
sql_query($query,$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."topics\" (\"PollID\", \"ForumID\", \"CategoryID\", \"OldForumID\", \"OldCategoryID\", \"UserID\", \"GuestName\", \"TimeStamp\", \"LastUpdate\", \"TopicName\", \"Description\", \"NumReply\", \"NumViews\", \"Pinned\", \"Closed\") VALUES\n".
"(0, 1, 1, 1, 1, -1, '".$iDB_Author."', %i, %i, 'Welcome', 'Welcome %s', 0, 0, 1, 1);", array($YourDate,$YourDate,$_POST['AdminUser']));
sql_query($query,$SQLStat);
$query=sql_pre_query("CREATE TABLE \"".$_POST['tableprefix']."wordfilter\" (\n".
"  \"id\" INTEGER AUTO_INCREMENT PRIMARY KEY,\n".
"  \"FilterWord\" STRING NOT NULL,\n".
"  \"Replacement\" STRING NOT NULL,\n".
"  \"CaseInsensitive\" VARCHAR(5) NOT NULL default '',\n".
"  \"WholeWord\" VARCHAR(5) NOT NULL default ''\n".
");", array(null));
sql_query($query,$SQLStat);

$TableChCk = array("categories", "catpermissions", "events", "forums", "groups", "members", "mempermissions", "messenger", "permissions", "polls", "posts", "restrictedwords", "sessions", "smileys", "topics", "wordfilter");
$TablePreFix = $_POST['tableprefix'];
function add_prefix($tarray) {
global $TablePreFix;
return $TablePreFix.$tarray; }
$TableChCk = array_map("add_prefix",$TableChCk);
$tcount = count($TableChCk); $ti = 0;
while ($ti < $tcount) {
$OptimizeTea = sql_query(sql_pre_query("UPDATE STATISTICS ON \"".$TableChCk[$ti]."\"", array(null)),$SQLStat);
++$ti; }
?>
