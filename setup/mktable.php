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
    iDB Installer made by Game Maker 2k - http://idb.berlios.net/

    $FileInfo: mktable.php - Last Update: 11/12/2009 SVN 341 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="mktable.php"||$File3Name=="/mktable.php") {
	require('index.php');
	exit(); }
if(!isset($SetupDir['setup'])) { $SetupDir['setup'] = "setup/"; }
if(!isset($SetupDir['convert'])) { $SetupDir['convert'] = "setup/convert/"; }
$query=query("ALTER DATABASE `".$_POST['DatabaseName']."` DEFAULT CHARACTER SET ".$SQLCharset." COLLATE ".$SQLCollate.";", array(null));
mysql_query($query);
$query=query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."categories` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `OrderID` int(15) NOT NULL default '0',\n".
"  `Name` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `ShowCategory` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CategoryType` varchar(15) collate ".$SQLCollate." NOT NULL default '',\n".
"  `SubShowForums` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `InSubCategory` int(15) NOT NULL default '0',\n".
"  `PostCountView` int(15) NOT NULL default '0',\n".
"  `KarmaCountView` int(15) NOT NULL default '0',\n".
"  `Description` text collate ".$SQLCollate." NOT NULL,\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
mysql_query($query);
$query=query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."catpermissions` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `PermissionID` int(15) NOT NULL default '0',\n".
"  `Name` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CategoryID` int(15) NOT NULL default '0',\n".
"  `CanViewCategory` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
mysql_query($query);
$query=query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."events` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `UserID` int(15) NOT NULL default '0',\n".
"  `GuestName` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `EventName` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `EventText` text collate ".$SQLCollate." NOT NULL,\n".
"  `TimeStamp` int(15) NOT NULL default '0',\n".
"  `TimeStampEnd` int(15) NOT NULL default '0',\n".
"  `EventMonth` int(5) NOT NULL default '0',\n".
"  `EventMonthEnd` int(5) NOT NULL default '0',\n".
"  `EventDay` int(5) NOT NULL default '0',\n".
"  `EventDayEnd` int(5) NOT NULL default '0',\n".
"  `EventYear` int(5) NOT NULL default '0',\n".
"  `EventYearEnd` int(5) NOT NULL default '0',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
mysql_query($query);
$query=query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."forums` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `CategoryID` int(15) NOT NULL default '0',\n".
"  `OrderID` int(15) NOT NULL default '0',\n".
"  `Name` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `ShowForum` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `ForumType` varchar(15) collate ".$SQLCollate." NOT NULL default '',\n".
"  `InSubForum` int(15) NOT NULL default '0',\n".
"  `RedirectURL` text collate ".$SQLCollate." NOT NULL,\n".
"  `Redirects` int(15) NOT NULL default '0',\n".
"  `NumViews` int(15) NOT NULL default '0',\n".
"  `Description` text collate ".$SQLCollate." NOT NULL,\n".
"  `PostCountAdd` varchar(15) character set ".$SQLCharset." NOT NULL default '',\n".
"  `PostCountView` int(15) NOT NULL default '0',\n".
"  `KarmaCountView` int(15) NOT NULL default '0',\n".
"  `CanHaveTopics` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `HotTopicPosts` int(15) NOT NULL default '0',\n".
"  `NumPosts` int(15) NOT NULL default '0',\n".
"  `NumTopics` int(15) NOT NULL default '0',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
mysql_query($query);
$query=query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."groups` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `Name` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `PermissionID` int(15) NOT NULL default '0',\n".
"  `NamePrefix` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `NameSuffix` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanViewBoard` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanViewOffLine` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanEditProfile` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanAddEvents` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanPM` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanSearch` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `FloodControl` int(5) NOT NULL default '0',\n".
"  `SearchFlood` int(5) NOT NULL default '0',\n".
"  `PromoteTo` int(15) NOT NULL default '0',\n".
"  `PromotePosts` int(15) NOT NULL default '0',\n".
"  `PromoteKarma` int(15) NOT NULL default '0',\n".
"  `HasModCP` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `HasAdminCP` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `ViewDBInfo` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  PRIMARY KEY  (`id`),\n".
"  UNIQUE KEY `Name` (`Name`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
mysql_query($query);
$query=query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."members` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `Name` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `Password` varchar(250) collate ".$SQLCollate." NOT NULL default '',\n".
"  `HashType` varchar(50) collate ".$SQLCollate." NOT NULL default '',\n".
"  `Email` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `GroupID` int(15) NOT NULL default '0',\n".
"  `Validated` varchar(20) collate ".$SQLCollate." NOT NULL default '',\n".
"  `HiddenMember` varchar(20) collate ".$SQLCollate." NOT NULL default '',\n".
"  `WarnLevel` int(10) NOT NULL default '0',\n".
"  `Interests` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `Title` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `Joined` int(15) NOT NULL default '0',\n".
"  `LastActive` int(15) NOT NULL default '0',\n".
"  `LastPostTime` int(15) NOT NULL default '0',\n".
"  `BanTime` int(15) NOT NULL default '0',\n".
"  `BirthDay` int(5) NOT NULL default '0',\n".
"  `BirthMonth` int(5) NOT NULL default '0',\n".
"  `BirthYear` int(5) NOT NULL default '0',\n".
"  `Signature` text collate ".$SQLCollate." NOT NULL,\n".
"  `Notes` text collate ".$SQLCollate." NOT NULL,\n".
"  `Avatar` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `AvatarSize` varchar(10) collate ".$SQLCollate." NOT NULL default '',\n".
"  `Website` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `Gender` varchar(15) collate ".$SQLCollate." NOT NULL default '',\n".
"  `PostCount` int(15) NOT NULL default '0',\n".
"  `Karma` int(15) NOT NULL default '0',\n".
"  `KarmaUpdate` int(15) NOT NULL default '0',\n".
"  `RepliesPerPage` int(5) NOT NULL default '0',\n".
"  `TopicsPerPage` int(5) NOT NULL default '0',\n".
"  `MessagesPerPage` int(5) NOT NULL default '0',\n".
"  `TimeZone` varchar(5) collate ".$SQLCollate." NOT NULL default '0',\n".
"  `DST` varchar(5) collate ".$SQLCollate." NOT NULL default '0',\n".
"  `UseTheme` varchar(26) collate ".$SQLCollate." NOT NULL default '0',\n".
"  `IP` varchar(20) collate ".$SQLCollate." NOT NULL default '',\n".
"  `Salt` varchar(50) collate ".$SQLCollate." NOT NULL default '',\n".
"  PRIMARY KEY  (`id`),\n".
"  UNIQUE KEY `Name` (`Name`),\n".
"  UNIQUE KEY `Email` (`Email`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
mysql_query($query);
$query=query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."messenger` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `SenderID` int(15) NOT NULL default '0',\n".
"  `ReciverID` int(15) NOT NULL default '0',\n".
"  `GuestName` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `MessageTitle` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `MessageText` text collate ".$SQLCollate." NOT NULL,\n".
"  `Description` text collate ".$SQLCollate." NOT NULL,\n".
"  `DateSend` int(15) NOT NULL default '0',\n".
"  `Read` int(5) NOT NULL default '0',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
mysql_query($query);
$query=query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."permissions` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `PermissionID` int(15) NOT NULL default '0',\n".
"  `Name` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `ForumID` int(15) NOT NULL default '0',\n".
"  `CanViewForum` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanMakeTopics` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanMakeReplys` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanMakeReplysCT` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanEditTopics` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanEditTopicsCT` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanEditReplys` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanEditReplysCT` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanDeleteTopics` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanDeleteTopicsCT` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanDeleteReplys` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanDeleteReplysCT` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanCloseTopics` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanPinTopics` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanDohtml` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanUseBBags` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanModForum` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
mysql_query($query);
$query=query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."posts` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `TopicID` int(15) NOT NULL default '0',\n".
"  `ForumID` int(15) NOT NULL default '0',\n".
"  `CategoryID` int(15) NOT NULL default '0',\n".
"  `UserID` int(15) NOT NULL default '0',\n".
"  `GuestName` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `TimeStamp` int(15) NOT NULL default '0',\n".
"  `LastUpdate` int(15) NOT NULL default '0',\n".
"  `EditUser` int(15) NOT NULL default '0',\n".
"  `EditUserName` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `Post` text collate ".$SQLCollate." NOT NULL,\n".
"  `Description` text collate ".$SQLCollate." NOT NULL,\n".
"  `IP` varchar(20) collate ".$SQLCollate." NOT NULL default '',\n".
"  `EditIP` varchar(20) collate ".$SQLCollate." NOT NULL default '',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
mysql_query($query);
$query=query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."restrictedwords` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `Word` text collate ".$SQLCollate." NOT NULL,\n".
"  `RestrictedUserName` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `RestrictedTopicName` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `RestrictedEventName` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `RestrictedMessageName` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CaseInsensitive` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `WholeWord` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
mysql_query($query);
$query=query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."sessions` (\n".
"  `session_id` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `session_data` text collate ".$SQLCollate." NOT NULL,\n".
"  `expires` int(15) NOT NULL default '0',\n".
"  PRIMARY KEY  (`session_id`)\n".
") ENGINE=MyISAM DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
mysql_query($query);
$query=query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."smileys` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `FileName` text character set ".$SQLCharset." collate latin1_general_cs NOT NULL,\n".
"  `SmileName` text collate ".$SQLCollate." NOT NULL,\n".
"  `SmileText` text collate ".$SQLCollate." NOT NULL,\n".
"  `Directory` text collate ".$SQLCollate." NOT NULL,\n".
"  `Show` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `ReplaceCI` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
mysql_query($query);
$query=query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."topics` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `ForumID` int(15) NOT NULL default '0',\n".
"  `CategoryID` int(15) NOT NULL default '0',\n".
"  `OldForumID` int(15) NOT NULL default '0',\n".
"  `OldCategoryID` int(15) NOT NULL default '0',\n".
"  `UserID` int(15) NOT NULL default '0',\n".
"  `GuestName` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `TimeStamp` int(15) NOT NULL default '0',\n".
"  `LastUpdate` int(15) NOT NULL default '0',\n".
"  `TopicName` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `Description` text collate ".$SQLCollate." NOT NULL,\n".
"  `NumReply` int(15) NOT NULL default '0',\n".
"  `NumViews` int(15) NOT NULL default '0',\n".
"  `Pinned` int(5) NOT NULL default '0',\n".
"  `Closed` int(5) NOT NULL default '0',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
mysql_query($query);
$query=query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."wordfilter` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `Filter` text collate ".$SQLCollate." NOT NULL,\n".
"  `Replace` text collate ".$SQLCollate." NOT NULL,\n".
"  `CaseInsensitive` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `WholeWord` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
mysql_query($query);
/*
$query=query("CREATE TABLE `".$_POST['tableprefix']."tagboard` ( `id` int(15) NOT NULL auto_increment, `UserID` int(15) NOT NULL default '0', `GuestName` varchar(150) NOT NULL default '', `TimeStamp` int(15) NOT NULL default '0', `Post` text NOT NULL, `IP` varchar(20) NOT NULL default '', PRIMARY KEY  (`id`)) TYPE=`MyISAM` DEFAULT CHARACTER SET ".$SQLCharset." COLLATE ".$SQLCollate.";", array(null));
*/
$query = query("INSERT INTO `".$_POST['tableprefix']."groups` VALUES (1, 'Admin', 1, '', '', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 30, 30, 0, 0, 0, 'yes', 'yes', 'yes'), (2, 'Moderator', 2, '', '', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 30, 30, 0, 0, 0, 'yes', 'no', 'no'), (3, 'Member', 3, '', '', 'yes', 'no', 'yes', 'yes', 'yes', 'yes', 30, 30, 0, 0, 0, 'no', 'no', 'no'), (4, 'Guest', 4, '', '', 'yes', 'no', 'no', 'no', 'no', 'no', 30, 30, 0, 0, 0, 'no', 'no', 'no'), (5, 'Banned', 5, '', '', 'no', 'no', 'no', 'no', 'no', 'no', 30, 30, 0, 0, 0, 'no', 'no', 'no'), (6, 'Validate', 6, '', '', 'yes', 'no', 'yes', 'no', 'no', 'yes', 30, 30, 0, 0, 0, 'no', 'no', 'no');", array(null)); 
mysql_query($query);
$query = query("INSERT INTO `".$_POST['tableprefix']."permissions` VALUES (1, 1, 'Admin', 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'), (2, 2, 'Moderator', 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'), (3, 3, 'Member', 1, 'yes', 'yes', 'yes', 'no', 'yes', 'no', 'yes', 'no', 'yes', 'no', 'yes', 'no', 'no', 'no', 'no', 'yes', 'no'), (4, 4, 'Guest', 1, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no'), (5, 5, 'Banned', 1, 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no'), (6, 6, 'Validate', 1, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no');", array(null)); 
mysql_query($query);
$query = query("INSERT INTO `".$_POST['tableprefix']."catpermissions` VALUES (1, 1, 'Admin', 1, 'yes'), (2, 2, 'Moderator', 1, 'yes'), (3, 3, 'Member', 1, 'yes'), (4, 4, 'Guest', 1, 'yes'), (5, 5, 'Banned', 1, 'no'), (6, 6, 'Validate', 1, 'yes');", array(null)); 
mysql_query($query);
$query = query("INSERT INTO `".$_POST['tableprefix']."smileys` VALUES (1, 'smile.gif', 'Happy', ':)', 'smileys/', 'yes', 'no'), (2, 'tongue.gif', 'Tongue', ':P', 'smileys/', 'yes', 'yes'), (3, 'tongue2.gif', 'Tongue', ':tongue:', 'smileys/', 'no', 'yes'), (4, 'sweat.gif', 'Sweat', ':sweat:', 'smileys/', 'yes', 'yes'), (5, 'sweat.gif', 'Sweat', '^_^', 'smileys/', 'no', 'yes'), (6, 'laugh.gif', 'lol', ':lol:', 'smileys/', 'yes', 'yes'), (7, 'cool.gif', 'Cool', 'B)', 'smileys/', 'yes', 'no'), (8, 'sleep.gif', 'Sleep', '-_-', 'smileys/', 'yes', 'no'), (9, 'sad.gif', 'Sad', ':(', 'smileys/', 'yes', 'no'), (10, 'angry.gif', 'Angry', ':angry:', 'smileys/', 'yes', 'yes'), (11, 'huh.gif', 'huh', ':huh:', 'smileys/', 'yes', 'yes'), (12, 'ohmy.gif', 'ohmy', ':o', 'smileys/', 'yes', 'yes'), (13, 'hmm.gif', 'hmm', ':unsure:', 'smileys/', 'yes', 'yes'), (14, 'mad.gif', 'Mad', ':mad:', 'smileys/', 'yes', 'yes'), (15, 'wub.gif', 'Wub', ':wub:', 'smileys/', 'yes', 'yes'), (16, 'x.gif', 'X', ':x:', 'smileys/', 'yes', 'yes');", array(null));
mysql_query($query);
?>
