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

    $FileInfo: mktable.php - Last Update: 05/09/2007 SVN 1 - Author: cooldude2k $
*/
$File1Name = dirname($_SERVER['SCRIPT_NAME'])."/";
$File2Name = $_SERVER['SCRIPT_NAME'];
$File3Name=str_replace($File1Name, null, $File2Name);
if ($File3Name=="mktable.php"||$File3Name=="/mktable.php") {
	require('index.php');
	exit(); }
if($SetupDir['setup']==null) { $SetupDir['setup'] = "setup/"; }
if($SetupDir['convert']==null) { $SetupDir['convert'] = "setup/convert/"; }
$query="CREATE TABLE `".$_POST['tableprefix']."categories` ( `id` int(15) NOT NULL auto_increment, `Name` varchar(150) NOT NULL default '', `ShowCategory` varchar(5) NOT NULL default '', `CategoryType` varchar(15) NOT NULL default '', `SubShowForums` varchar(5) NOT NULL default '', `InSubCategory` int(15) NOT NULL default '0', `Description` text NOT NULL, PRIMARY KEY  (`id`)) TYPE=MyISAM ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."forums` ( `id` int(15) NOT NULL auto_increment, `CategoryID` int(15) NOT NULL default '0', `Name` varchar(150) NOT NULL default '', `ShowForum` varchar(5) NOT NULL default '', `ForumType` varchar(15) NOT NULL default '', `InSubForum` int(15) NOT NULL default '0', `RedirectURL` text NOT NULL, `Redirects` int(15) NOT NULL default '0', `NumViews` int(15) NOT NULL default '0', `Description` text NOT NULL, `PostCountAdd` varchar(15) NOT NULL default '', `CanHaveTopics` varchar(5) NOT NULL default '', `NumPosts` int(15) NOT NULL default '0', `NumTopics` int(15) NOT NULL default '0', PRIMARY KEY  (`id`)) TYPE=MyISAM ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."events` ( `id` int(15) NOT NULL auto_increment, `UserID` int(15) NOT NULL default '0', `GuestName` varchar(150) NOT NULL default '', `EventName` varchar(150) NOT NULL default '', `EventText` text NOT NULL, `TimeStamp` int(15) NOT NULL default '0', `TimeStampEnd` int(15) NOT NULL default '0', PRIMARY KEY  (`id`)) TYPE=MyISAM ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."members` ( `id` int(15) NOT NULL auto_increment, `Name` varchar(150) NOT NULL default '', `Password` varchar(150) NOT NULL default '', `HashType` varchar(50) NOT NULL default '', `Email` varchar(150) NOT NULL default '', `GroupID` int(15) NOT NULL default '0', `Validated` varchar(20) NOT NULL default '', `WarnLevel` int(10) NOT NULL default '0', `Interests` varchar(150) NOT NULL default '', `Title` varchar(150) NOT NULL default '', `Joined` int(15) NOT NULL default '0', `LastActive` int(15) NOT NULL default '0', `BirthDay` int(15) NOT NULL default '0', `Signature` text NOT NULL, `Notes` text NOT NULL, `Avatar` varchar(150) NOT NULL default '', `AvatarSize` varchar(10) NOT NULL default '', `Website` varchar(150) NOT NULL default '', `Gender` varchar(15) NOT NULL default '', `PostCount` int(15) NOT NULL default '0', `TimeZone` varchar(5) NOT NULL default '0', `DST` varchar(5) NOT NULL default '0', `UseTheme` varchar(5) NOT NULL default '0', `IP` varchar(20) NOT NULL default '', `Salt` varchar(50) NOT NULL default '', PRIMARY KEY  (`id`)) TYPE=MyISAM ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."messenger` ( `id` int(15) NOT NULL auto_increment, `SenderID` int(15) NOT NULL default '0', `PMSentID` int(15) NOT NULL default '0', `GuestName` varchar(150) NOT NULL default '', `MessageTitle` varchar(150) NOT NULL default '', `MessageText` text NOT NULL, `Description` text NOT NULL, `DateSend` int(15) NOT NULL default '0', `Read` int(5) NOT NULL default '0', PRIMARY KEY  (`id`)) TYPE=MyISAM ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."posts` ( `id` int(15) NOT NULL auto_increment, `TopicID` int(15) NOT NULL default '0', `ForumID` int(15) NOT NULL default '0', `CategoryID` int(15) NOT NULL default '0', `UserID` int(15) NOT NULL default '0', `GuestName` varchar(150) NOT NULL default '', `TimeStamp` int(15) NOT NULL default '0', `LastUpdate` int(15) NOT NULL default '0', `EditUser` int(15) NOT NULL default '0', `Post` text NOT NULL, `Description` text NOT NULL, `IP` varchar(20) NOT NULL default '', PRIMARY KEY  (`id`)) TYPE=MyISAM ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."smileys` ( `id` int(15) NOT NULL auto_increment, `FileName` text NOT NULL, `SmileName` text NOT NULL, `SmileText` text NOT NULL, `Directory` text NOT NULL, `Show` varchar(15) NOT NULL default '', PRIMARY KEY  (`id`)) TYPE=MyISAM ;";
mysql_query($query);
/*
$query="CREATE TABLE `".$_POST['tableprefix']."tagboard` ( `id` int(15) NOT NULL auto_increment, `UserID` int(15) NOT NULL default '0', `GuestName` varchar(150) NOT NULL default '', `TimeStamp` int(15) NOT NULL default '0', `Post` text NOT NULL, `IP` varchar(20) NOT NULL default '', PRIMARY KEY  (`id`)) TYPE=MyISAM ;";
*/
$query="CREATE TABLE `".$_POST['tableprefix']."topics` ( `id` int(15) NOT NULL auto_increment, `ForumID` int(15) NOT NULL default '0', `CategoryID` int(15) NOT NULL default '0', `UserID` int(15) NOT NULL default '0', `GuestName` varchar(150) NOT NULL default '', `TimeStamp` int(15) NOT NULL default '0', `LastUpdate` int(15) NOT NULL default '0', `TopicName` varchar(150) NOT NULL default '', `Description` text NOT NULL, `NumReply` int(15) NOT NULL default '0', `NumViews` int(15) NOT NULL default '0', `Pinned` int(5) NOT NULL default '0', `Closed` int(5) NOT NULL default '0', PRIMARY KEY  (`id`)) TYPE=MyISAM ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."sessions` ( `SessionID` varchar(255) NOT NULL default '', `SessID` varchar(255) NOT NULL default '', `LastUpdated` int(15) NOT NULL default '0', `DataValue` text NOT NULL, PRIMARY KEY (`SessionID`)) TYPE=MyISAM ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."groups` ( `id` int(15) NOT NULL auto_increment, `Name` varchar(150) NOT NULL default '', `PermissionID` int(15) NOT NULL default '0', `NamePrefix` varchar(150) NOT NULL default '', `NameSuffix` varchar(150) NOT NULL default '', `CanViewBoard` varchar(5) NOT NULL default '', `CanEditProfile` varchar(5) NOT NULL default '', `CanAddEvents` varchar(5) NOT NULL default '', `CanPM` varchar(5) NOT NULL default '', `PromoteTo` varchar(150) NOT NULL default '', `PromotePosts` int(15) NOT NULL default '0', `HasModCP` varchar(5) NOT NULL default '', `HasAdminCP` varchar(5) NOT NULL default '', `ViewDBInfo` varchar(5) NOT NULL default '', PRIMARY KEY  (`id`)) TYPE=MyISAM ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."permissions` ( `id` int(15) NOT NULL auto_increment, `PermissionID` int(15) NOT NULL default '0', `Name` varchar(150) NOT NULL default '', `ForumID` int(15) NOT NULL default '0', `CanViewForum` varchar(5) NOT NULL default '', `CanMakeTopics` varchar(5) NOT NULL default '', `CanMakeReplys` varchar(5) NOT NULL default '', `CanEditTopics` varchar(5) NOT NULL default '', `CanEditReplys` varchar(5) NOT NULL default '', `CanDeleteTopics` varchar(5) NOT NULL default '', `CanDeleteReplys` varchar(5) NOT NULL default '', `CanDohtml` varchar(5) NOT NULL default '', `CanUseBBags` varchar(5) NOT NULL default '', `CanModForum` varchar(5) NOT NULL default '', PRIMARY KEY  (`id`)) TYPE=MyISAM ;";
mysql_query($query);
$query="CREATE TABLE `".$_POST['tableprefix']."catpermissions` ( `id` int(15) NOT NULL auto_increment, `PermissionID` int(15) NOT NULL default '0', `Name` varchar(150) NOT NULL default '', `CategoryID` int(15) NOT NULL default '0', `CanViewCategory` varchar(5) NOT NULL default '', PRIMARY KEY  (`id`)) TYPE=MyISAM ;";
mysql_query($query);
$query = "INSERT INTO ".$_POST['tableprefix']."groups VALUES (1, 'Admin', 1, '', '', 'yes', 'yes', 'yes', 'yes', 'none', 0, 'yes', 'yes', 'no'), (2, 'Moderator', 2, '', '', 'yes', 'yes', 'yes', 'yes', 'none', 0, 'yes', 'no', 'no'), (3, 'Member', 3, '', '', 'yes', 'yes', 'yes', 'yes', 'none', 0, 'no', 'no', 'no'), (4, 'Guest', 4, '', '', 'yes', 'no', 'no', 'no', 'none', 0, 'no', 'no', 'no'), (5, 'Banned', 5, '', '', 'no', 'no', 'no', 'no', 'none', 0, 'no', 'no', 'no'), (6, 'Validate', 6, '', '', 'yes', 'yes', 'no', 'no', 'none', 0, 'no', 'no', 'no');"; 
mysql_query($query);
$query = "INSERT INTO ".$_POST['tableprefix']."permissions VALUES (1, 1, 'Admin', 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'), (2, 2, 'Moderator', 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'), (3, 3, 'Member', 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'no'), (4, 4, 'Guest', 1, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no'), (5, 5, 'Banned', 1, 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no'), (6, 6, 'Validate', 1, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no');"; 
mysql_query($query);
$query = "INSERT INTO ".$_POST['tableprefix']."catpermissions VALUES (1, 1, 'Admin', 1, 'yes'), (2, 2, 'Moderator', 1, 'yes'), (3, 3, 'Member', 1, 'yes'), (4, 4, 'Guest', 1, 'yes'), (5, 5, 'Banned', 1, 'no'), (6, 6, 'Validate', 1, 'yes');"; 
mysql_query($query);
$query = "INSERT INTO ".$_POST['tableprefix']."smileys VALUES (1, 'smile.gif', 'Happy', ':)', 'smileys/', 'yes'), (2, 'tongue.gif', 'Tongue', ':P', 'smileys/', 'yes'), (3, 'tongue2.gif', 'Tongue', ':tongue:', 'smileys/', 'yes'), (4, 'sweat.gif', 'Sweat', ':sweat:', 'smileys/', 'yes'), (5, 'laugh.gif', 'lol', ':lol:', 'smileys/', 'yes'), (6, 'cool.gif', 'Cool', 'B)', 'smileys/', 'yes'), (7, 'sleep.gif', 'Sleep', '-_-', 'smileys/', 'yes'), (8, 'sad.gif', 'Sad', ':(', 'smileys/', 'yes'), (9, 'angry.gif', 'Angry', ':angry:', 'smileys/', 'yes'), (10, 'huh.gif', 'huh', ':huh:', 'smileys/', 'yes'), (11, 'ohmy.gif', 'ohmy', ':o', 'smileys/', 'yes'), (12, 'hmm.gif', 'hmm', ':unsure:', 'smileys/', 'yes'), (13, 'mad.gif', 'Mad', ':mad:', 'smileys/', 'yes'), (14, 'wub.gif', 'Wub', ':wub:', 'smileys/', 'yes'), (15, 'x.gif', 'X', ':x:', 'smileys/', 'yes');";
mysql_query($query);
?>