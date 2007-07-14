<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2007 Cool Dude 2k - http://intdb.sourceforge.net/
    Copyright 2004-2007 Game Maker 2k - http://upload.idb.s1.jcink.com/
    iDB Installer made by Game Maker 2k - http://idb.berlios.net/

    $FileInfo: mktable.php - Last Update: 07/14/2007 SVN 43 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="mktable.php"||$File3Name=="/mktable.php") {
	require('index.php');
	exit(); }
if(!isset($SetupDir['setup'])) { $SetupDir['setup'] = "setup/"; }
if(!isset($SetupDir['convert'])) { $SetupDir['convert'] = "setup/convert/"; }
$query="CREATE TABLE `".$_POST['tableprefix']."categories` ( `id` int(15) NOT NULL auto_increment, `Name` varchar(150) NOT NULL default '', `ShowCategory` varchar(5) NOT NULL default '', `CategoryType` varchar(15) NOT NULL default '', `SubShowForums` varchar(5) NOT NULL default '', `InSubCategory` int(15) NOT NULL default '0', `Description` text NOT NULL, PRIMARY KEY  (`id`)) TYPE=`MyISAM` ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."forums` ( `id` int(15) NOT NULL auto_increment, `CategoryID` int(15) NOT NULL default '0', `Name` varchar(150) NOT NULL default '', `ShowForum` varchar(5) NOT NULL default '', `ForumType` varchar(15) NOT NULL default '', `InSubForum` int(15) NOT NULL default '0', `RedirectURL` text NOT NULL, `Redirects` int(15) NOT NULL default '0', `NumViews` int(15) NOT NULL default '0', `Description` text NOT NULL, `PostCountAdd` varchar(15) NOT NULL default '', `CanHaveTopics` varchar(5) NOT NULL default '', `NumPosts` int(15) NOT NULL default '0', `NumTopics` int(15) NOT NULL default '0', PRIMARY KEY  (`id`)) TYPE=`MyISAM` ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."events` ( `id` int(15) NOT NULL auto_increment, `UserID` int(15) NOT NULL default '0', `GuestName` varchar(150) NOT NULL default '', `EventName` varchar(150) NOT NULL default '', `EventText` text NOT NULL, `TimeStamp` int(15) NOT NULL default '0', `TimeStampEnd` int(15) NOT NULL default '0', PRIMARY KEY  (`id`)) TYPE=`MyISAM` ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."members` ( `id` int(15) NOT NULL auto_increment, `Name` varchar(150) NOT NULL default '', `Password` varchar(150) NOT NULL default '', `HashType` varchar(50) NOT NULL default '', `Email` varchar(150) NOT NULL default '', `GroupID` int(15) NOT NULL default '0', `Validated` varchar(20) NOT NULL default '', `WarnLevel` int(10) NOT NULL default '0', `Interests` varchar(150) NOT NULL default '', `Title` varchar(150) NOT NULL default '', `Joined` int(15) NOT NULL default '0', `LastActive` int(15) NOT NULL default '0', `BirthDay` int(15) NOT NULL default '0', `Signature` text NOT NULL, `Notes` text NOT NULL, `Avatar` varchar(150) NOT NULL default '', `AvatarSize` varchar(10) NOT NULL default '', `Website` varchar(150) NOT NULL default '', `Gender` varchar(15) NOT NULL default '', `PostCount` int(15) NOT NULL default '0', `TimeZone` varchar(5) NOT NULL default '0', `DST` varchar(5) NOT NULL default '0', `UseTheme` varchar(5) NOT NULL default '0', `IP` varchar(20) NOT NULL default '', `Salt` varchar(50) NOT NULL default '', PRIMARY KEY  (`id`)) TYPE=`MyISAM` ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."messenger` ( `id` int(15) NOT NULL auto_increment, `SenderID` int(15) NOT NULL default '0', `PMSentID` int(15) NOT NULL default '0', `GuestName` varchar(150) NOT NULL default '', `MessageTitle` varchar(150) NOT NULL default '', `MessageText` text NOT NULL, `Description` text NOT NULL, `DateSend` int(15) NOT NULL default '0', `Read` int(5) NOT NULL default '0', PRIMARY KEY  (`id`)) TYPE=`MyISAM` ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."posts` ( `id` int(15) NOT NULL auto_increment, `TopicID` int(15) NOT NULL default '0', `ForumID` int(15) NOT NULL default '0', `CategoryID` int(15) NOT NULL default '0', `UserID` int(15) NOT NULL default '0', `GuestName` varchar(150) NOT NULL default '', `TimeStamp` int(15) NOT NULL default '0', `LastUpdate` int(15) NOT NULL default '0', `EditUser` int(15) NOT NULL default '0', `Post` text NOT NULL, `Description` text NOT NULL, `IP` varchar(20) NOT NULL default '', PRIMARY KEY  (`id`)) TYPE=`MyISAM` ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."smileys` ( `id` int(15) NOT NULL auto_increment, `FileName` text NOT NULL, `SmileName` text NOT NULL, `SmileText` text NOT NULL, `Directory` text NOT NULL, `Show` varchar(5) NOT NULL default '', `ReplaceCI` varchar(5) NOT NULL default '', PRIMARY KEY  (`id`)) TYPE=`MyISAM` ;";
mysql_query($query);
/*
$query="CREATE TABLE `".$_POST['tableprefix']."tagboard` ( `id` int(15) NOT NULL auto_increment, `UserID` int(15) NOT NULL default '0', `GuestName` varchar(150) NOT NULL default '', `TimeStamp` int(15) NOT NULL default '0', `Post` text NOT NULL, `IP` varchar(20) NOT NULL default '', PRIMARY KEY  (`id`)) TYPE=`MyISAM` ;";
*/
$query="CREATE TABLE `".$_POST['tableprefix']."topics` ( `id` int(15) NOT NULL auto_increment, `ForumID` int(15) NOT NULL default '0', `CategoryID` int(15) NOT NULL default '0', `UserID` int(15) NOT NULL default '0', `GuestName` varchar(150) NOT NULL default '', `TimeStamp` int(15) NOT NULL default '0', `LastUpdate` int(15) NOT NULL default '0', `TopicName` varchar(150) NOT NULL default '', `Description` text NOT NULL, `NumReply` int(15) NOT NULL default '0', `NumViews` int(15) NOT NULL default '0', `Pinned` int(5) NOT NULL default '0', `Closed` int(5) NOT NULL default '0', PRIMARY KEY  (`id`)) TYPE=`MyISAM` ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."sessions` ( `SessionID` varchar(255) NOT NULL default '', `SessID` varchar(255) NOT NULL default '', `LastUpdated` int(15) NOT NULL default '0', `DataValue` text NOT NULL, PRIMARY KEY (`SessionID`)) TYPE=`MyISAM` ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."groups` ( `id` int(15) NOT NULL auto_increment, `Name` varchar(150) NOT NULL default '', `PermissionID` int(15) NOT NULL default '0', `NamePrefix` varchar(150) NOT NULL default '', `NameSuffix` varchar(150) NOT NULL default '', `CanViewBoard` varchar(5) NOT NULL default '', `CanEditProfile` varchar(5) NOT NULL default '', `CanAddEvents` varchar(5) NOT NULL default '', `CanPM` varchar(5) NOT NULL default '', `CanSearch` varchar(5) NOT NULL default '', `PromoteTo` varchar(150) NOT NULL default '', `PromotePosts` int(15) NOT NULL default '0', `HasModCP` varchar(5) NOT NULL default '', `HasAdminCP` varchar(5) NOT NULL default '', `ViewDBInfo` varchar(5) NOT NULL default '', PRIMARY KEY  (`id`)) TYPE=`MyISAM` ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."permissions` ( `id` int(15) NOT NULL auto_increment, `PermissionID` int(15) NOT NULL default '0', `Name` varchar(150) NOT NULL default '', `ForumID` int(15) NOT NULL default '0', `CanViewForum` varchar(5) NOT NULL default '', `CanMakeTopics` varchar(5) NOT NULL default '', `CanMakeReplys` varchar(5) NOT NULL default '', `CanEditTopics` varchar(5) NOT NULL default '', `CanEditReplys` varchar(5) NOT NULL default '', `CanDeleteTopics` varchar(5) NOT NULL default '', `CanDeleteReplys` varchar(5) NOT NULL default '', `CanDohtml` varchar(5) NOT NULL default '', `CanUseBBags` varchar(5) NOT NULL default '', `CanModForum` varchar(5) NOT NULL default '', PRIMARY KEY  (`id`)) TYPE=`MyISAM` ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."catpermissions` ( `id` int(15) NOT NULL auto_increment, `PermissionID` int(15) NOT NULL default '0', `Name` varchar(150) NOT NULL default '', `CategoryID` int(15) NOT NULL default '0', `CanViewCategory` varchar(5) NOT NULL default '', PRIMARY KEY  (`id`)) TYPE=`MyISAM` ;";
mysql_query($query);
$query = "INSERT INTO ".$_POST['tableprefix']."groups VALUES (1, 'Admin', 1, '', '', 'yes', 'yes', 'yes', 'yes', 'yes', 'none', 0, 'yes', 'yes', 'yes'), (2, 'Moderator', 2, '', '', 'yes', 'yes', 'yes', 'yes', 'yes', 'none', 0, 'yes', 'no', 'no'), (3, 'Member', 3, '', '', 'yes', 'yes', 'yes', 'yes', 'yes', 'none', 0, 'no', 'no', 'no'), (4, 'Guest', 4, '', '', 'yes', 'no', 'no', 'no', 'yes', 'none', 0, 'no', 'no', 'no'), (5, 'Banned', 5, '', '', 'no', 'no', 'no', 'no', 'no', 'none', 0, 'no', 'no', 'no'), (6, 'Validate', 6, '', '', 'yes', 'yes', 'no', 'no', 'yes', 'none', 0, 'no', 'no', 'no');"; 
mysql_query($query);
$query = "INSERT INTO ".$_POST['tableprefix']."permissions VALUES (1, 1, 'Admin', 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'), (2, 2, 'Moderator', 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'), (3, 3, 'Member', 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'no'), (4, 4, 'Guest', 1, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no'), (5, 5, 'Banned', 1, 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no'), (6, 6, 'Validate', 1, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no');"; 
mysql_query($query);
$query = "INSERT INTO ".$_POST['tableprefix']."catpermissions VALUES (1, 1, 'Admin', 1, 'yes'), (2, 2, 'Moderator', 1, 'yes'), (3, 3, 'Member', 1, 'yes'), (4, 4, 'Guest', 1, 'yes'), (5, 5, 'Banned', 1, 'no'), (6, 6, 'Validate', 1, 'yes');"; 
mysql_query($query);
$query = "INSERT INTO ".$_POST['tableprefix']."smileys VALUES (1, 'smile.gif', 'Happy', ':)', 'smileys/', 'yes', 'no'), (2, 'tongue.gif', 'Tongue', ':P', 'smileys/', 'yes', 'yes'), (3, 'tongue2.gif', 'Tongue', ':tongue:', 'smileys/', 'yes', 'yes'), (4, 'sweat.gif', 'Sweat', ':sweat:', 'smileys/', 'yes', 'yes'), (5, 'sweat.gif', 'Sweat', '^_^', 'smileys/', 'no', 'yes'), (6, 'laugh.gif', 'lol', ':lol:', 'smileys/', 'yes', 'yes'), (7, 'cool.gif', 'Cool', 'B)', 'smileys/', 'yes', 'no'), (8, 'sleep.gif', 'Sleep', '-_-', 'smileys/', 'yes', 'no'), (9, 'sad.gif', 'Sad', ':(', 'smileys/', 'yes', 'no'), (10, 'angry.gif', 'Angry', ':angry:', 'smileys/', 'yes', 'yes'), (11, 'huh.gif', 'huh', ':huh:', 'smileys/', 'yes', 'yes'), (12, 'ohmy.gif', 'ohmy', ':o', 'smileys/', 'yes', 'yes'), (13, 'hmm.gif', 'hmm', ':unsure:', 'smileys/', 'yes', 'yes'), (14, 'mad.gif', 'Mad', ':mad:', 'smileys/', 'yes', 'yes'), (15, 'wub.gif', 'Wub', ':wub:', 'smileys/', 'yes', 'yes'), (16, 'x.gif', 'X', ':x:', 'smileys/', 'yes', 'yes');";
mysql_query($query);
?>