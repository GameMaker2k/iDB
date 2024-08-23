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

    $FileInfo: sqlthemes.php - Last Update: 8/23/2024 SVN 1023 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="sqlthemes.php"||$File3Name=="/sqlthemes.php") {
	require('index.php');
	exit(); }
// Change SQLThemes to iDB Themes Settings
$ThemeSet = array();
$ThemeSet['ThemeName'] = sql_result($themeresult,0,"ThemeName");
if($ThemeSet['ThemeName']=="") { $ThemeSet['ThemeName'] = null; }
$ThemeSet['ThemeMaker'] = sql_result($themeresult,0,"ThemeMaker");
if($ThemeSet['ThemeMaker']=="") { $ThemeSet['ThemeMaker'] = null; }
$ThemeSet['ThemeVersion'] = sql_result($themeresult,0,"ThemeVersion");
if($ThemeSet['ThemeVersion']=="") { $ThemeSet['ThemeVersion'] = null; }
$ThemeSet['ThemeVersionType'] = sql_result($themeresult,0,"ThemeVersionType");
if($ThemeSet['ThemeVersionType']=="") { $ThemeSet['ThemeVersionType'] = null; }
$ThemeSet['ThemeSubVersion'] = sql_result($themeresult,0,"ThemeSubVersion");
if($ThemeSet['ThemeSubVersion']=="") { $ThemeSet['ThemeSubVersion'] = null; }
$ThemeSet['MakerURL'] = sql_result($themeresult,0,"MakerURL");
if($ThemeSet['MakerURL']=="") { $ThemeSet['MakerURL'] = null; }
$ThemeSet['CopyRight'] = sql_result($themeresult,0,"CopyRight");
if($ThemeSet['CopyRight']=="") { $ThemeSet['CopyRight'] = null; }
$ThemeSet['WrapperString'] = sql_result($themeresult,0,"WrapperString");
if($ThemeSet['WrapperString']=="") { $ThemeSet['WrapperString'] = null; }
$ThemeSet['CSS'] = sql_result($themeresult,0,"CSS");
if($ThemeSet['CSS']=="") { $ThemeSet['CSS'] = null; }
$ThemeSet['CSSType'] = sql_result($themeresult,0,"CSSType");
if($ThemeSet['CSSType']=="") { $ThemeSet['CSSType'] = null; }
$ThemeSet['FavIcon'] = sql_result($themeresult,0,"FavIcon");
if($ThemeSet['FavIcon']=="") { $ThemeSet['FavIcon'] = null; }
$ThemeSet['OpenGraph'] = sql_result($themeresult,0,"OpenGraph");
if($ThemeSet['OpenGraph']=="") { $ThemeSet['OpenGraph'] = null; }
$ThemeSet['TableStyle'] = sql_result($themeresult,0,"TableStyle");
if($ThemeSet['TableStyle']=="") { $ThemeSet['TableStyle'] = null; }
$ThemeSet['MiniPageAltStyle'] = sql_result($themeresult,0,"MiniPageAltStyle");
if($ThemeSet['MiniPageAltStyle']=="") { $ThemeSet['MiniPageAltStyle'] = null; }
$ThemeSet['PreLogo'] = sql_result($themeresult,0,"PreLogo");
if($ThemeSet['PreLogo']=="") { $ThemeSet['PreLogo'] = null; }
$ThemeSet['Logo'] = sql_result($themeresult,0,"Logo");
if($ThemeSet['Logo']=="") { $ThemeSet['Logo'] = null; }
$ThemeSet['LogoStyle'] = sql_result($themeresult,0,"LogoStyle");
if($ThemeSet['LogoStyle']=="") { $ThemeSet['LogoStyle'] = null; }
$ThemeSet['SubLogo'] = sql_result($themeresult,0,"SubLogo");
if($ThemeSet['SubLogo']=="") { $ThemeSet['SubLogo'] = null; }
$ThemeSet['TopicIcon'] = sql_result($themeresult,0,"TopicIcon");
if($ThemeSet['TopicIcon']=="") { $ThemeSet['TopicIcon'] = null; }
$ThemeSet['MovedTopicIcon'] = sql_result($themeresult,0,"MovedTopicIcon");
if($ThemeSet['MovedTopicIcon']=="") { $ThemeSet['MovedTopicIcon'] = $ThemeSet['TopicIcon']; }
$ThemeSet['HotTopic'] = sql_result($themeresult,0,"HotTopic");
if($ThemeSet['HotTopic']=="") { $ThemeSet['HotTopic'] = null; }
$ThemeSet['MovedHotTopic'] = sql_result($themeresult,0,"MovedHotTopic");
if($ThemeSet['MovedHotTopic']=="") { $ThemeSet['MovedHotTopic'] = $ThemeSet['HotTopic']; }
$ThemeSet['PinTopic'] = sql_result($themeresult,0,"PinTopic");
if($ThemeSet['PinTopic']=="") { $ThemeSet['PinTopic'] = null; }
$ThemeSet['AnnouncementTopic'] = sql_result($themeresult,0,"AnnouncementTopic");
if($ThemeSet['AnnouncementTopic']=="") { $ThemeSet['AnnouncementTopic'] = $ThemeSet['PinTopic']; }
$ThemeSet['MovedPinTopic'] = sql_result($themeresult,0,"MovedPinTopic");
if($ThemeSet['MovedPinTopic']=="") { $ThemeSet['MovedPinTopic'] = $ThemeSet['PinTopic']; }
$ThemeSet['HotPinTopic'] = sql_result($themeresult,0,"HotPinTopic");
if($ThemeSet['HotPinTopic']=="") { $ThemeSet['HotPinTopic'] = null; }
$ThemeSet['MovedHotPinTopic'] = sql_result($themeresult,0,"MovedHotPinTopic");
if($ThemeSet['MovedHotPinTopic']=="") { $ThemeSet['MovedHotPinTopic'] = $ThemeSet['HotPinTopic']; }
$ThemeSet['ClosedTopic'] = sql_result($themeresult,0,"ClosedTopic");
if($ThemeSet['ClosedTopic']=="") { $ThemeSet['ClosedTopic'] = null; }
$ThemeSet['MovedClosedTopic'] = sql_result($themeresult,0,"MovedClosedTopic");
if($ThemeSet['MovedClosedTopic']=="") { $ThemeSet['MovedClosedTopic'] = $ThemeSet['ClosedTopic']; }
$ThemeSet['HotClosedTopic'] = sql_result($themeresult,0,"HotClosedTopic");
if($ThemeSet['HotClosedTopic']=="") { $ThemeSet['HotClosedTopic'] = null; }
$ThemeSet['MovedHotClosedTopic'] = sql_result($themeresult,0,"MovedHotClosedTopic");
if($ThemeSet['MovedHotClosedTopic']=="") { $ThemeSet['MovedHotClosedTopic'] = $ThemeSet['HotClosedTopic']; }
$ThemeSet['PinClosedTopic'] = sql_result($themeresult,0,"PinClosedTopic");
if($ThemeSet['PinClosedTopic']=="") { $ThemeSet['PinClosedTopic'] = null; }
$ThemeSet['MovedPinClosedTopic'] = sql_result($themeresult,0,"MovedPinClosedTopic");
if($ThemeSet['MovedPinClosedTopic']=="") { $ThemeSet['MovedPinClosedTopic'] = $ThemeSet['PinClosedTopic']; }
$ThemeSet['HotPinClosedTopic'] = sql_result($themeresult,0,"HotPinClosedTopic");
if($ThemeSet['HotPinClosedTopic']=="") { $ThemeSet['HotPinClosedTopic'] = null; }
$ThemeSet['MovedHotPinClosedTopic'] = sql_result($themeresult,0,"MovedHotPinClosedTopic");
if($ThemeSet['MovedHotPinClosedTopic']=="") { $ThemeSet['MovedHotPinClosedTopic'] = $ThemeSet['HotPinClosedTopic']; }
$ThemeSet['MessageRead'] = sql_result($themeresult,0,"MessageRead");
if($ThemeSet['MessageRead']=="") { $ThemeSet['MessageRead'] = null; }
$ThemeSet['MessageUnread'] = sql_result($themeresult,0,"MessageUnread");
if($ThemeSet['MessageUnread']=="") { $ThemeSet['MessageUnread'] = null; }
$ThemeSet['Profile'] = sql_result($themeresult,0,"Profile");
if($ThemeSet['Profile']=="") { $ThemeSet['Profile'] = null; }
$ThemeSet['WWW'] = sql_result($themeresult,0,"WWW");
if($ThemeSet['WWW']=="") { $ThemeSet['WWW'] = null; }
$ThemeSet['PM'] = sql_result($themeresult,0,"PM");
if($ThemeSet['PM']=="") { $ThemeSet['PM'] = null; }
$ThemeSet['TopicLayout'] = sql_result($themeresult,0,"TopicLayout");
if($ThemeSet['TopicLayout']=="") { $ThemeSet['TopicLayout'] = null; }
$ThemeSet['AddReply'] = sql_result($themeresult,0,"AddReply");
if($ThemeSet['AddReply']=="") { $ThemeSet['AddReply'] = null; }
$ThemeSet['FastReply'] = sql_result($themeresult,0,"FastReply");
if($ThemeSet['FastReply']=="") { $ThemeSet['FastReply'] = null; }
$ThemeSet['NewTopic'] = sql_result($themeresult,0,"NewTopic");
if($ThemeSet['NewTopic']=="") { $ThemeSet['NewTopic'] = null; }
$ThemeSet['QuoteReply'] = sql_result($themeresult,0,"QuoteReply");
if($ThemeSet['QuoteReply']=="") { $ThemeSet['QuoteReply'] = null; }
$ThemeSet['EditReply'] = sql_result($themeresult,0,"EditReply");
if($ThemeSet['EditReply']=="") { $ThemeSet['EditReply'] = null; }
$ThemeSet['DeleteReply'] = sql_result($themeresult,0,"DeleteReply");
if($ThemeSet['DeleteReply']=="") { $ThemeSet['DeleteReply'] = null; }
$ThemeSet['Report'] = sql_result($themeresult,0,"Report");
if($ThemeSet['Report']=="") { $ThemeSet['Report'] = null; }
$ThemeSet['LineDivider'] = sql_result($themeresult,0,"LineDivider");
if($ThemeSet['LineDivider']=="") { $ThemeSet['LineDivider'] = null; }
$ThemeSet['ButtonDivider'] = sql_result($themeresult,0,"ButtonDivider");
if($ThemeSet['ButtonDivider']=="") { $ThemeSet['ButtonDivider'] = null; }
$ThemeSet['LineDividerTopic'] = sql_result($themeresult,0,"LineDividerTopic");
if($ThemeSet['LineDividerTopic']=="") { $ThemeSet['LineDividerTopic'] = null; }
$ThemeSet['TitleDivider'] = sql_result($themeresult,0,"TitleDivider");
if($ThemeSet['TitleDivider']=="") { $ThemeSet['TitleDivider'] = null; }
$ThemeSet['ForumStyle'] = sql_result($themeresult,0,"ForumStyle");
if($ThemeSet['ForumStyle']=="") { $ThemeSet['ForumStyle'] = null; }
$ThemeSet['ForumIcon'] = sql_result($themeresult,0,"ForumIcon");
if($ThemeSet['ForumIcon']=="") { $ThemeSet['ForumIcon'] = null; }
$ThemeSet['SubForumIcon'] = sql_result($themeresult,0,"SubForumIcon");
if($ThemeSet['SubForumIcon']=="") { $ThemeSet['SubForumIcon'] = null; }
$ThemeSet['RedirectIcon'] = sql_result($themeresult,0,"RedirectIcon");
if($ThemeSet['RedirectIcon']=="") { $ThemeSet['RedirectIcon'] = null; }
$ThemeSet['TitleIcon'] = sql_result($themeresult,0,"TitleIcon");
if($ThemeSet['TitleIcon']=="") { $ThemeSet['TitleIcon'] = null; }
$ThemeSet['NavLinkIcon'] = sql_result($themeresult,0,"NavLinkIcon");
if($ThemeSet['NavLinkIcon']=="") { $ThemeSet['NavLinkIcon'] = null; }
$ThemeSet['NavLinkDivider'] = sql_result($themeresult,0,"NavLinkDivider");
if($ThemeSet['NavLinkDivider']=="") { $ThemeSet['NavLinkDivider'] = null; }
$ThemeSet['BoardStatsIcon'] = sql_result($themeresult,0,"BoardStatsIcon");
if($ThemeSet['BoardStatsIcon']=="") { $ThemeSet['BoardStatsIcon'] = null; }
$ThemeSet['MemberStatsIcon'] = sql_result($themeresult,0,"MemberStatsIcon");
if($ThemeSet['MemberStatsIcon']=="") { $ThemeSet['MemberStatsIcon'] = null; }
$ThemeSet['BirthdayStatsIcon'] = sql_result($themeresult,0,"BirthdayStatsIcon");
if($ThemeSet['BirthdayStatsIcon']=="") { $ThemeSet['BirthdayStatsIcon'] = null; }
$ThemeSet['EventStatsIcon'] = sql_result($themeresult,0,"EventStatsIcon");
if($ThemeSet['EventStatsIcon']=="") { $ThemeSet['EventStatsIcon'] = null; }
$ThemeSet['OnlineStatsIcon'] = sql_result($themeresult,0,"OnlineStatsIcon");
if($ThemeSet['OnlineStatsIcon']=="") { $ThemeSet['OnlineStatsIcon'] = null; }
$ThemeSet['NoAvatar'] = sql_result($themeresult,0,"NoAvatar");
if($ThemeSet['NoAvatar']=="") { $ThemeSet['NoAvatar'] = null; }
$ThemeSet['NoAvatarSize'] = sql_result($themeresult,0,"NoAvatarSize");
if($ThemeSet['NoAvatarSize']=="") { $ThemeSet['NoAvatarSize'] = null; }
?>