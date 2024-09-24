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
$themeresult_array = sql_fetch_assoc($themeresult);
$ThemeSet['ThemeName'] = $themeresult_array['ThemeName'];
if($ThemeSet['ThemeName']=="") { $ThemeSet['ThemeName'] = null; }
$ThemeSet['ThemeMaker'] = $themeresult_array['ThemeMaker'];
if($ThemeSet['ThemeMaker']=="") { $ThemeSet['ThemeMaker'] = null; }
$ThemeSet['ThemeVersion'] = $themeresult_array['ThemeVersion'];
if($ThemeSet['ThemeVersion']=="") { $ThemeSet['ThemeVersion'] = null; }
$ThemeSet['ThemeVersionType'] = $themeresult_array['ThemeVersionType'];
if($ThemeSet['ThemeVersionType']=="") { $ThemeSet['ThemeVersionType'] = null; }
$ThemeSet['ThemeSubVersion'] = $themeresult_array['ThemeSubVersion'];
if($ThemeSet['ThemeSubVersion']=="") { $ThemeSet['ThemeSubVersion'] = null; }
$ThemeSet['MakerURL'] = $themeresult_array['MakerURL'];
if($ThemeSet['MakerURL']=="") { $ThemeSet['MakerURL'] = null; }
$ThemeSet['CopyRight'] = $themeresult_array['CopyRight'];
if($ThemeSet['CopyRight']=="") { $ThemeSet['CopyRight'] = null; }
$ThemeSet['WrapperString'] = $themeresult_array['WrapperString'];
if($ThemeSet['WrapperString']=="") { $ThemeSet['WrapperString'] = null; }
$ThemeSet['CSS'] = $themeresult_array['CSS'];
if($ThemeSet['CSS']=="") { $ThemeSet['CSS'] = null; }
$ThemeSet['CSSType'] = $themeresult_array['CSSType'];
if($ThemeSet['CSSType']=="") { $ThemeSet['CSSType'] = null; }
$ThemeSet['FavIcon'] = $themeresult_array['FavIcon'];
if($ThemeSet['FavIcon']=="") { $ThemeSet['FavIcon'] = null; }
$ThemeSet['OpenGraph'] = $themeresult_array['OpenGraph'];
if($ThemeSet['OpenGraph']=="") { $ThemeSet['OpenGraph'] = null; }
$ThemeSet['TableStyle'] = $themeresult_array['TableStyle'];
if($ThemeSet['TableStyle']=="") { $ThemeSet['TableStyle'] = null; }
$ThemeSet['MiniPageAltStyle'] = $themeresult_array['MiniPageAltStyle'];
if($ThemeSet['MiniPageAltStyle']=="") { $ThemeSet['MiniPageAltStyle'] = null; }
$ThemeSet['PreLogo'] = $themeresult_array['PreLogo'];
if($ThemeSet['PreLogo']=="") { $ThemeSet['PreLogo'] = null; }
$ThemeSet['Logo'] = $themeresult_array['Logo'];
if($ThemeSet['Logo']=="") { $ThemeSet['Logo'] = null; }
$ThemeSet['LogoStyle'] = $themeresult_array['LogoStyle'];
if($ThemeSet['LogoStyle']=="") { $ThemeSet['LogoStyle'] = null; }
$ThemeSet['SubLogo'] = $themeresult_array['SubLogo'];
if($ThemeSet['SubLogo']=="") { $ThemeSet['SubLogo'] = null; }
$ThemeSet['TopicIcon'] = $themeresult_array['TopicIcon'];
if($ThemeSet['TopicIcon']=="") { $ThemeSet['TopicIcon'] = null; }
$ThemeSet['MovedTopicIcon'] = $themeresult_array['MovedTopicIcon'];
if($ThemeSet['MovedTopicIcon']=="") { $ThemeSet['MovedTopicIcon'] = $ThemeSet['TopicIcon']; }
$ThemeSet['HotTopic'] = $themeresult_array['HotTopic'];
if($ThemeSet['HotTopic']=="") { $ThemeSet['HotTopic'] = null; }
$ThemeSet['MovedHotTopic'] = $themeresult_array['MovedHotTopic'];
if($ThemeSet['MovedHotTopic']=="") { $ThemeSet['MovedHotTopic'] = $ThemeSet['HotTopic']; }
$ThemeSet['PinTopic'] = $themeresult_array['PinTopic'];
if($ThemeSet['PinTopic']=="") { $ThemeSet['PinTopic'] = null; }
$ThemeSet['AnnouncementTopic'] = $themeresult_array['AnnouncementTopic'];
if($ThemeSet['AnnouncementTopic']=="") { $ThemeSet['AnnouncementTopic'] = $ThemeSet['PinTopic']; }
$ThemeSet['MovedPinTopic'] = $themeresult_array['MovedPinTopic'];
if($ThemeSet['MovedPinTopic']=="") { $ThemeSet['MovedPinTopic'] = $ThemeSet['PinTopic']; }
$ThemeSet['HotPinTopic'] = $themeresult_array['HotPinTopic'];
if($ThemeSet['HotPinTopic']=="") { $ThemeSet['HotPinTopic'] = null; }
$ThemeSet['MovedHotPinTopic'] = $themeresult_array['MovedHotPinTopic'];
if($ThemeSet['MovedHotPinTopic']=="") { $ThemeSet['MovedHotPinTopic'] = $ThemeSet['HotPinTopic']; }
$ThemeSet['ClosedTopic'] = $themeresult_array['ClosedTopic'];
if($ThemeSet['ClosedTopic']=="") { $ThemeSet['ClosedTopic'] = null; }
$ThemeSet['MovedClosedTopic'] = $themeresult_array['MovedClosedTopic'];
if($ThemeSet['MovedClosedTopic']=="") { $ThemeSet['MovedClosedTopic'] = $ThemeSet['ClosedTopic']; }
$ThemeSet['HotClosedTopic'] = $themeresult_array['HotClosedTopic'];
if($ThemeSet['HotClosedTopic']=="") { $ThemeSet['HotClosedTopic'] = null; }
$ThemeSet['MovedHotClosedTopic'] = $themeresult_array['MovedHotClosedTopic'];
if($ThemeSet['MovedHotClosedTopic']=="") { $ThemeSet['MovedHotClosedTopic'] = $ThemeSet['HotClosedTopic']; }
$ThemeSet['PinClosedTopic'] = $themeresult_array['PinClosedTopic'];
if($ThemeSet['PinClosedTopic']=="") { $ThemeSet['PinClosedTopic'] = null; }
$ThemeSet['MovedPinClosedTopic'] = $themeresult_array['MovedPinClosedTopic'];
if($ThemeSet['MovedPinClosedTopic']=="") { $ThemeSet['MovedPinClosedTopic'] = $ThemeSet['PinClosedTopic']; }
$ThemeSet['HotPinClosedTopic'] = $themeresult_array['HotPinClosedTopic'];
if($ThemeSet['HotPinClosedTopic']=="") { $ThemeSet['HotPinClosedTopic'] = null; }
$ThemeSet['MovedHotPinClosedTopic'] = $themeresult_array['MovedHotPinClosedTopic'];
if($ThemeSet['MovedHotPinClosedTopic']=="") { $ThemeSet['MovedHotPinClosedTopic'] = $ThemeSet['HotPinClosedTopic']; }
$ThemeSet['MessageRead'] = $themeresult_array['MessageRead'];
if($ThemeSet['MessageRead']=="") { $ThemeSet['MessageRead'] = null; }
$ThemeSet['MessageUnread'] = $themeresult_array['MessageUnread'];
if($ThemeSet['MessageUnread']=="") { $ThemeSet['MessageUnread'] = null; }
$ThemeSet['Profile'] = $themeresult_array['Profile'];
if($ThemeSet['Profile']=="") { $ThemeSet['Profile'] = null; }
$ThemeSet['WWW'] = $themeresult_array['WWW'];
if($ThemeSet['WWW']=="") { $ThemeSet['WWW'] = null; }
$ThemeSet['PM'] = $themeresult_array['PM'];
if($ThemeSet['PM']=="") { $ThemeSet['PM'] = null; }
$ThemeSet['TopicLayout'] = $themeresult_array['TopicLayout'];
if($ThemeSet['TopicLayout']=="") { $ThemeSet['TopicLayout'] = null; }
$ThemeSet['AddReply'] = $themeresult_array['AddReply'];
if($ThemeSet['AddReply']=="") { $ThemeSet['AddReply'] = null; }
$ThemeSet['FastReply'] = $themeresult_array['FastReply'];
if($ThemeSet['FastReply']=="") { $ThemeSet['FastReply'] = null; }
$ThemeSet['NewTopic'] = $themeresult_array['NewTopic'];
if($ThemeSet['NewTopic']=="") { $ThemeSet['NewTopic'] = null; }
$ThemeSet['QuoteReply'] = $themeresult_array['QuoteReply'];
if($ThemeSet['QuoteReply']=="") { $ThemeSet['QuoteReply'] = null; }
$ThemeSet['EditReply'] = $themeresult_array['EditReply'];
if($ThemeSet['EditReply']=="") { $ThemeSet['EditReply'] = null; }
$ThemeSet['DeleteReply'] = $themeresult_array['DeleteReply'];
if($ThemeSet['DeleteReply']=="") { $ThemeSet['DeleteReply'] = null; }
$ThemeSet['Report'] = $themeresult_array['Report'];
if($ThemeSet['Report']=="") { $ThemeSet['Report'] = null; }
$ThemeSet['LineDivider'] = $themeresult_array['LineDivider'];
if($ThemeSet['LineDivider']=="") { $ThemeSet['LineDivider'] = null; }
$ThemeSet['ButtonDivider'] = $themeresult_array['ButtonDivider'];
if($ThemeSet['ButtonDivider']=="") { $ThemeSet['ButtonDivider'] = null; }
$ThemeSet['LineDividerTopic'] = $themeresult_array['LineDividerTopic'];
if($ThemeSet['LineDividerTopic']=="") { $ThemeSet['LineDividerTopic'] = null; }
$ThemeSet['TitleDivider'] = $themeresult_array['TitleDivider'];
if($ThemeSet['TitleDivider']=="") { $ThemeSet['TitleDivider'] = null; }
$ThemeSet['ForumStyle'] = $themeresult_array['ForumStyle'];
if($ThemeSet['ForumStyle']=="") { $ThemeSet['ForumStyle'] = null; }
$ThemeSet['ForumIcon'] = $themeresult_array['ForumIcon'];
if($ThemeSet['ForumIcon']=="") { $ThemeSet['ForumIcon'] = null; }
$ThemeSet['SubForumIcon'] = $themeresult_array['SubForumIcon'];
if($ThemeSet['SubForumIcon']=="") { $ThemeSet['SubForumIcon'] = null; }
$ThemeSet['RedirectIcon'] = $themeresult_array['RedirectIcon'];
if($ThemeSet['RedirectIcon']=="") { $ThemeSet['RedirectIcon'] = null; }
$ThemeSet['TitleIcon'] = $themeresult_array['TitleIcon'];
if($ThemeSet['TitleIcon']=="") { $ThemeSet['TitleIcon'] = null; }
$ThemeSet['NavLinkIcon'] = $themeresult_array['NavLinkIcon'];
if($ThemeSet['NavLinkIcon']=="") { $ThemeSet['NavLinkIcon'] = null; }
$ThemeSet['NavLinkDivider'] = $themeresult_array['NavLinkDivider'];
if($ThemeSet['NavLinkDivider']=="") { $ThemeSet['NavLinkDivider'] = null; }
$ThemeSet['BoardStatsIcon'] = $themeresult_array['BoardStatsIcon'];
if($ThemeSet['BoardStatsIcon']=="") { $ThemeSet['BoardStatsIcon'] = null; }
$ThemeSet['MemberStatsIcon'] = $themeresult_array['MemberStatsIcon'];
if($ThemeSet['MemberStatsIcon']=="") { $ThemeSet['MemberStatsIcon'] = null; }
$ThemeSet['BirthdayStatsIcon'] = $themeresult_array['BirthdayStatsIcon'];
if($ThemeSet['BirthdayStatsIcon']=="") { $ThemeSet['BirthdayStatsIcon'] = null; }
$ThemeSet['EventStatsIcon'] = $themeresult_array['EventStatsIcon'];
if($ThemeSet['EventStatsIcon']=="") { $ThemeSet['EventStatsIcon'] = null; }
$ThemeSet['OnlineStatsIcon'] = $themeresult_array['OnlineStatsIcon'];
if($ThemeSet['OnlineStatsIcon']=="") { $ThemeSet['OnlineStatsIcon'] = null; }
$ThemeSet['NoAvatar'] = $themeresult_array['NoAvatar'];
if($ThemeSet['NoAvatar']=="") { $ThemeSet['NoAvatar'] = null; }
$ThemeSet['NoAvatarSize'] = $themeresult_array['NoAvatarSize'];
if($ThemeSet['NoAvatarSize']=="") { $ThemeSet['NoAvatarSize'] = null; }
?>