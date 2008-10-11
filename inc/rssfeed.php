<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2008 Cool Dude 2k - http://idb.berlios.de/
    Copyright 2004-2008 Game Maker 2k - http://intdb.sourceforge.net/

    $FileInfo: rssfeed.php - Last Update: 10/10/2008 SVN 173 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="rssfeed.php"||$File3Name=="/rssfeed.php") {
	require('index.php');
	exit(); }
if(!is_numeric($_GET['id'])) { $_GET['id'] = null; }
$boardsname = htmlentities($Settings['board_name'], ENT_QUOTES, $Settings['charset']);
$boardsname = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $boardsname);
$_GET['feedtype'] = strtolower($_GET['feedtype']);
if($_GET['feedtype']!="rss"&&$_GET['feedtype']!="atom") { $_GET['feedtype'] = "rss"; }
//$basepath = pathinfo($_SERVER['REQUEST_URI']);
/*if(dirname($_SERVER['REQUEST_URI'])!="."||
	dirname($_SERVER['REQUEST_URI'])!=null) {
$basedir = dirname($_SERVER['REQUEST_URI'])."/"; }*/
if(dirname($_SERVER['SCRIPT_NAME'])!="."||
	dirname($_SERVER['SCRIPT_NAME'])!=null) {
$basedir = dirname($_SERVER['SCRIPT_NAME'])."/"; }
if($basedir==null||$basedir==".") {
if(dirname($_SERVER['SCRIPT_NAME'])=="."||
	dirname($_SERVER['SCRIPT_NAME'])==null) {
$basedir = dirname($_SERVER['PHP_SELF'])."/"; } }
if($basedir=="\/") { $basedir="/"; }
$basedir = str_replace("//", "/", $basedir);
if($Settings['fixpathinfo']!==true&&
	$Settings['fixpathinfo']!==false&&
	$Settings['fixpathinfo']!==null) {
		$basedir = "/"; } $BaseURL = $basedir;
if(isset($Settings['showverinfo'])) { $idbmisc['showverinfo'] = $Settings['showverinfo']; }
if(!isset($Settings['showverinfo'])) { $idbmisc['showverinfo'] = false; }
if(!isset($_SERVER['HTTPS'])) { $_SERVER['HTTPS'] = 'off'; }
if($_SERVER['HTTPS']=="on") { $prehost = "https://"; }
if($_SERVER['HTTPS']!="on") { $prehost = "http://"; }
if($Settings['idburl']=="localhost"||$Settings['idburl']==null) {
	$BoardURL = $prehost.$_SERVER["HTTP_HOST"].$BaseURL; }
if($Settings['idburl']!="localhost"&&$Settings['idburl']!=null) {
	$BoardURL = $Settings['idburl']; }
if ($_GET['id']==null) { $_GET['id']="1"; }
if($rssurlon===true) { $BoardURL =  $rssurl; }
$feedsname = basename($_SERVER['SCRIPT_NAME']);
if($_SERVER['PATH_INFO']!=null) {
$feedsname .= htmlentities($_SERVER['PATH_INFO'], ENT_QUOTES, $Settings['charset']); }
if($_SERVER['QUERY_STRING']!=null) {
$feedsname .= "?".htmlentities($_SERVER['QUERY_STRING'], ENT_QUOTES, $Settings['charset']); }
$checkfeedtype = "application/rss+xml";
if($_GET['feedtype']=="rss") { $checkfeedtype = "application/rss+xml"; }
if($_GET['feedtype']=="atom") { $checkfeedtype = "application/atom+xml"; }
if(stristr($_SERVER["HTTP_ACCEPT"],$checkfeedtype) ) {
@header("Content-Type: application/rss+xml; charset=".$Settings['charset']); }
else { if(stristr($_SERVER["HTTP_ACCEPT"],"application/xml") ) {
@header("Content-Type: application/xml; charset=".$Settings['charset']); }
else { if (stristr($_SERVER["HTTP_USER_AGENT"],"FeedValidator")) {
   @header("Content-Type: application/xml; charset=".$Settings['charset']);
} else { @header("Content-Type: text/xml; charset=".$Settings['charset']); } } }
@header("Content-Language: en");
@header("Vary: Accept");
$prequery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `id`=%i", array($_GET['id']));
$preresult=mysql_query($prequery);
$prenum=mysql_num_rows($preresult);
$prei=0;
$ForumID=mysql_result($preresult,0,"id");
$ForumName=mysql_result($preresult,0,"Name");
$ForumName = htmlentities($ForumName, ENT_QUOTES, $Settings['charset']);
$ForumName = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $ForumName);
$ForumCatID=mysql_result($preresult,0,"CategoryID");
$ForumType=mysql_result($preresult,0,"ForumType");
$ForumType = strtolower($ForumType);
@mysql_free_result($preresult);
if($PermissionInfo['CanViewForum'][$ForumID]=="no"||
	$PermissionInfo['CanViewForum'][$ForumID]!="yes") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($CatPermissionInfo['CanViewCategory'][$ForumCatID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$ForumCatID]!="yes") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
$gltf = array(null); $gltf[0] = $ForumID;
if ($ForumType=="subforum") { 
$apcquery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `ShowForum`='yes' AND `InSubForum`=%i ORDER BY `id`", array($ForumID));
$apcresult=mysql_query($apcquery);
$apcnum=mysql_num_rows($apcresult);
$apci=0; $apcl=1; if($apcnum>=1) {
while ($apci < $apcnum) {
$SubsForumID=mysql_result($apcresult,$apci,"id");
if(isset($PermissionInfo['CanViewForum'][$SubsForumID])&&
	$PermissionInfo['CanViewForum'][$SubsForumID]=="yes") {
$gltf[$apcl] = $SubsForumID; ++$apcl; }
++$apci; }
@mysql_free_result($apcresult); } }
$Atom = null; $RSS = null; 
$gltnum = count($gltf); $glti = 0; 
while ($glti < $gltnum) {
$query = query("SELECT * FROM `".$Settings['sqltable']."topics` WHERE `ForumID`=%i ORDER BY `Pinned` DESC, `LastUpdate` DESC", array($gltf[$glti]));
$result=mysql_query($query);
$num=mysql_num_rows($result); $i=0;
while ($i < $num) {
$TopicID=mysql_result($result,$i,"id");
$ForumID=mysql_result($result,$i,"ForumID");
$CategoryID=mysql_result($result,$i,"CategoryID");
$UsersID=mysql_result($result,$i,"UserID");
$GuestName=mysql_result($result,$i,"GuestName");
$TheTime=mysql_result($result,$i,"TimeStamp");
$TheTime=GMTimeChange("D, j M Y G:i:s \G\M\T",$TheTime,0);
$TopicName=mysql_result($result,$i,"TopicName");
$ForumDescription=mysql_result($result,$i,"Description");
if(isset($PermissionInfo['CanViewForum'][$ForumID])&&
	$PermissionInfo['CanViewForum'][$ForumID]=="yes"&&
	isset($CatPermissionInfo['CanViewCategory'][$CategoryID])&&
	$CatPermissionInfo['CanViewCategory'][$CategoryID]=="yes") {
$Atom .= '<entry>'."\n".'<title>'.htmlentities($TopicName, ENT_QUOTES, $Settings['charset']).'</title>'."\n".'<summary>'.htmlentities($ForumDescription, ENT_QUOTES, $Settings['charset']).'</summary>'."\n".'<link rel="alternate" href="'.$BoardURL.url_maker($exfilerss['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstrrss['topic'],$exqstrrss['topic']).'" />'."\n".'<id>'.$BoardURL.url_maker($exfilerss['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstrrss['topic'],$exqstrrss['topic']).'</id>'."\n".'<author>'."\n".'<name>'.$SettInfo['Author'].'</name>'."\n".'</author>'."\n".'<updated>'.gmdate("Y-m-d\TH:i:s\Z").'</updated>'."\n".'</entry>'."\n";
$RSS .= '<item>'."\n".'<title>'.htmlentities($TopicName, ENT_QUOTES, $Settings['charset']).'</title>'."\n".'<description>'.htmlentities($ForumDescription, ENT_QUOTES, $Settings['charset']).'</description>'."\n".'<link>'.$BoardURL.url_maker($exfilerss['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstrrss['topic'],$exqstrrss['topic']).'</link>'."\n".'<guid>'.$BoardURL.url_maker($exfilerss['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstrrss['topic'],$exqstrrss['topic']).'</guid>'."\n".'</item>'."\n"; }
++$i; } @mysql_free_result($result);
++$glti; }
xml_doc_start("1.0",$Settings['charset']);
if($Settings['showverinfo']===true) { ?>
<!-- generator="<?php echo $VerInfo['iDB_Ver_Show']; ?>" -->
<?php } if($Settings['showverinfo']!==true) { ?>
<!-- generator="<?php echo $iDB; ?>" -->
<?php } echo "\n"; if($_GET['feedtype']=="rss") { ?>
<rss version="2.0">
<channel>
   <title><?php echo $boardsname." ".$ThemeSet['TitleDivider']; ?> Viewing Forum <?php echo $ForumName; ?></title>
   <description>RSS Feed of the Topics in Forum <?php echo $ForumName; ?></description>
   <link><?php echo $BoardURL; ?></link>
   <language>en</language>
   <?php if($Settings['showverinfo']===true) { ?>
   <generator><?php echo $VerInfo['iDB_Ver_Show']; ?></generator>
   <?php } if($Settings['showverinfo']!==true) { ?>
   <generator><?php echo $iDB; ?></generator>
   <?php } echo "\n"; ?>
   <copyright><?php echo $SettInfo['Author']; ?></copyright>
   <ttl>120</ttl>
   <image>
	<url><?php echo $BoardURL.$SettDir['inc']; ?>rss.gif</url>
	<title><?php echo $boardsname; ?></title>
	<link><?php echo $BoardURL; ?></link>
   </image>
 <?php echo "\n".$RSS."\n"; ?></channel>
</rss>
<?php } if($_GET['feedtype']=="atom") { ?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title><?php echo $boardsname." ".$ThemeSet['TitleDivider']; ?> Viewing Forum <?php echo $ForumName; ?></title>
   <subtitle>Atom Feed of the Topics in Forum <?php echo $ForumName; ?></subtitle>
   <link rel="self" href="<?php echo $BoardURL.$feedsname; ?>" />
   <id><?php echo $BoardURL; ?></id>
   <updated><?php echo gmdate("Y-m-d\TH:i:s\Z"); ?></updated>
   <?php if($Settings['showverinfo']===true) { ?>
   <generator><?php echo $VerInfo['iDB_Ver_Show']; ?></generator>
   <?php } if($Settings['showverinfo']!==true) { ?>
   <generator><?php echo $iDB; ?></generator>
   <?php } echo "\n"; ?>
  <icon><?php echo $BoardURL.$SettDir['inc']; ?>rss.gif</icon>
 <?php echo "\n".$Atom."\n"; ?>
</feed>
<?php } mysql_close();
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); ?>
