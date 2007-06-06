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

    $FileInfo: rss2.php - Last Update: 06/06/2007 SVN 19 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="rssfeed.php"||$File3Name=="/rssfeed.php") {
	require('index.php');
	exit(); }
$boardsname = htmlentities($Settings['board_name']);
$boardsname = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $boardsname);
$_GET['feedtype'] = strtolower($_GET['feedtype']);
if($_GET['feedtype']!="rss"&&
$_GET['feedtype']!="atom") { $_GET['feedtype'] = "rss"; }
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
if($Settings['fixpathinfo']!=true&&
	$Settings['fixpathinfo']!=false&&
	$Settings['fixpathinfo']!=null) {
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
if($rssurlon==true) { $BoardURL =  $rssurl; }
$feedsname = basename($_SERVER['SCRIPT_NAME']);
if($_SERVER['PATH_INFO']!=null) {
$feedsname .= htmlentities($_SERVER['PATH_INFO']); }
if($_SERVER['QUERY_STRING']!=null) {
$feedsname .= "?".htmlentities($_SERVER['QUERY_STRING']); }
$checkfeedtype = "application/rss+xml";
if($_GET['feedtype']=="rss") { $checkfeedtype = "application/rss+xml"; }
if($_GET['feedtype']=="atom") { $checkfeedtype = "application/atom+xml"; }
if(stristr($_SERVER["HTTP_ACCEPT"],$checkfeedtype) ) {
@header("Content-Type: application/rss+xml; charset=".$Settings['charset']); }
else{ if(stristr($_SERVER["HTTP_ACCEPT"],"application/xml") ) {
@header("Content-Type: application/xml; charset=".$Settings['charset']); }
else { if (stristr($_SERVER["HTTP_USER_AGENT"],"FeedValidator")) {
   @header("Content-Type: application/xml; charset=".$Settings['charset']);
} else { @header("Content-Type: text/xml; charset=".$Settings['charset']); } } }
@header("Content-Language: en");
@header("Vary: Accept");
$query = query("select * from ".$Settings['sqltable']."topics where ForumID=%i ORDER BY Pinned DESC, LastUpdate DESC", array($_GET['id']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
$Atom = null; $RSS = null; $i=0;
while ($i < $num) {
$TopicID=mysql_result($result,$i,"id");
$CategoryID=mysql_result($result,$i,"CategoryID");
$UsersID=mysql_result($result,$i,"UserID");
$GuestName=mysql_result($result,$i,"GuestName");
$TheTime=mysql_result($result,$i,"TimeStamp");
$TheTime=GMTimeChange("D, j M Y G:i:s \G\M\T",$TheTime,0);
$TopicName=mysql_result($result,$i,"TopicName");
$ForumDescription=mysql_result($result,$i,"Description");
$Atom .= '<entry>'."\n".'<title>'.htmlentities($TopicName).'</title>'."\n".'<summary>'.htmlentities($ForumDescription).'</summary>'."\n".'<link rel="alternate" href="'.$BoardURL.url_maker($exfilerss['topic'],$Settings['file_ext'],"act=view&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstrrss['topic'],$exqstrrss['topic']).'" />'."\n".'<id>'.$BoardURL.url_maker($exfilerss['topic'],$Settings['file_ext'],"act=view&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstrrss['topic'],$exqstrrss['topic']).'</id>'."\n".'<author>'."\n".'<name>'.$SettInfo['Author'].'</name>'."\n".'</author>'."\n".'<updated>'.gmdate("Y-m-d\TH:i:s\Z").'</updated>'."\n".'</entry>'."\n";
$RSS .= '<item>'."\n".'<title>'.htmlentities($TopicName).'</title>'."\n".'<description>'.htmlentities($ForumDescription).'</description>'."\n".'<link>'.$BoardURL.url_maker($exfilerss['topic'],$Settings['file_ext'],"act=view&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstrrss['topic'],$exqstrrss['topic']).'</link>'."\n".'<guid>'.$BoardURL.url_maker($exfilerss['topic'],$Settings['file_ext'],"act=view&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstrrss['topic'],$exqstrrss['topic']).'</guid>'."\n".'</item>'."\n";
++$i; } @mysql_free_result($result);
xml_doc_start("1.0",$Settings['charset']);
if($Settings['showverinfo']==true) { ?>
<!-- generator="<?php echo $VerInfo['iDB_Ver_Show']; ?>" -->
<?php } if($Settings['showverinfo']!=true) { ?>
<!-- generator="<?php echo $iDB; ?>" -->
<?php } echo "\n"; if($_GET['feedtype']=="rss") { ?>
<rss version="2.0">
<channel>
   <title><?php echo $boardsname; ?></title>
   <description>RSS Feed of the Topics in Board <?php echo $boardsname; ?></description>
   <link><?php echo $BoardURL; ?></link>
   <language>en</language>
   <?php if($Settings['showverinfo']==true) { ?>
   <generator><?php echo $VerInfo['iDB_Ver_Show']; ?></generator>
   <?php } if($Settings['showverinfo']!=true) { ?>
   <generator><?php echo $iDB; ?></generator>
   <?php } echo "\n"; ?>
   <copyright><?php echo $SettInfo['Author']; ?></copyright>
   <ttl>120</ttl>
   <image>
	<url><?php echo $BoardURL.$SettDir['rss']; ?>rss.gif</url>
	<title><?php echo $boardsname; ?></title>
	<link><?php echo $BoardURL; ?></link>
   </image>
 <?php echo "\n".$RSS."\n"; ?></channel>
</rss>
<?php } if($_GET['feedtype']=="atom") { ?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title><?php echo $boardsname; ?></title>
   <subtitle>RSS Feed of the Topics in Board <?php echo $boardsname; ?></subtitle>
   <link rel="self" href="<?php echo $feedsname; ?>" />
   <id><?php echo $BoardURL; ?></id>
   <updated><?php echo gmdate("Y-m-d\TH:i:s\Z"); ?></updated>
   <?php if($Settings['showverinfo']==true) { ?>
   <generator><?php echo $VerInfo['iDB_Ver_Show']; ?></generator>
   <?php } if($Settings['showverinfo']!=true) { ?>
   <generator><?php echo $iDB; ?></generator>
   <?php } echo "\n"; ?>
  <icon><?php echo $BoardURL; ?>inc/rss.gif</icon>
 <?php echo "\n".$Atom."\n"; ?>
</feed>
<?php } mysql_close();
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); ?>
