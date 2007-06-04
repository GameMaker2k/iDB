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

    $FileInfo: filename.php - Last Update: 06/04/2007 SVN 18 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="filename.php"||$File3Name=="/filename.php") {
	require('index.php');
	exit(); }
	$rssurlon = false;
if(dirname($_SERVER['SCRIPT_NAME'])!=".") {
$basedir = dirname($_SERVER['SCRIPT_NAME'])."/"; }
if(dirname($_SERVER['SCRIPT_NAME'])==".") {
$basedir = dirname($_SERVER['PHP_SELF'])."/"; }
if($basedir=="\/") { $basedir="/"; }
$basedir = str_replace("//", "/", $basedir);
if($Settings['fixbasedir']!=null&&$Settings['fixbasedir']!="") {
		$basedir = $Settings['fixbasedir']; }
$BaseURL = $basedir;
if($_SERVER['HTTPS']=="on") { $prehost = "https://"; }
if($_SERVER['HTTPS']!="on") { $prehost = "http://"; }
if($Settings['idburl']=="localhost"||$Settings['idburl']==null) {
	$rssurl = $prehost.$_SERVER["HTTP_HOST"].$BaseURL; }
if($Settings['idburl']!="localhost"&&$Settings['idburl']!=null) {
	$rssurlon = "on"; $rssurl = $Settings['idburl']; }
if($Settings['rssurl']!=null&&$Settings['rssurl']!="") {
	$rssurlon = "on"; $rssurl = $Settings['rssurl']; }
//Version info stuff. :P 
function version_info($proname,$subver,$ver,$supver,$reltype,$svnver,$showsvn) {
	$return_var = $proname." ".$reltype." ".$subver.".".$ver.".".$supver;
	if($showsvn==false) { $showsvn = null; }
	if($showsvn==true) { $return_var .= " SVN ".$svnver; }
	if($showsvn!=true&&$showsvn!=null) { $return_var .= " ".$showsvn." ".$svnver; }
	return $return_var; }
$VER1[0] = 0; $VER1[1] = 1; $VER1[2] = 5; $VERFull[1] = $VER1[0].".".$VER1[1].".".$VER1[2];
$VER2[0] = "Pre-Alpha"; $VER2[1] = "PA"; $VER2[2] = "SVN"; $SubVerN = 18; $RName = "iDB"; $SFName = "IntDB";
$SVNDay[0] = 6; $SVNDay[1] = 04; $SVNDay[2] = 2007; $SVNDay[3] = $SVNDay[0]."/".$SVNDay[1]."/".$SVNDay[2];
$VerInfo['iDB_Ver'] = version_info($RName,$VER1[0],$VER1[1],$VER1[2],$VER2[1],$SubVerN,false);
$VerInfo['iDB_Ver_SVN'] = version_info($RName,$VER1[0],$VER1[1],$VER1[2],$VER2[1],$SubVerN,true);
$VerInfo['iDB_Full_Ver'] = version_info($RName,$VER1[0],$VER1[1],$VER1[2],$VER2[0],$SubVerN,false);
$VerInfo['iDB_Full_Ver_SVN'] = version_info($RName,$VER1[0],$VER1[1],$VER1[2],$VER2[0],$SubVerN,true);
$VerInfo['iDB_Ver_Show'] = $VerInfo['iDB_Ver_SVN']; $VerInfo['iDB_Full_Ver_Show'] = $VerInfo['iDB_Full_Ver_SVN'];
if(isset($Settings['showverinfo'])) { $idbmisc['showverinfo'] = $Settings['showverinfo']; }
if(!isset($Settings['showverinfo'])) { $idbmisc['showverinfo'] = false; }
$CD2k = "Cool Dude 2k"; $GM2k = "Game Maker 2k";
$iDB = "Internet Discussion Boards"; $iTB = "Internet Tag Boards"; $DF2k = "Discussion Forums 2k"; $TB2k = "Tag Boards 2k";
$iDBURL1 = "<a href=\"http://intdb.sourceforge.net/\" onclick=\"window.open(this.href);return false;\">"; $iDBURL2 = $iDBURL1.$iDB."</a>";
$DF2kURL1 = "<a href=\"http://df2k.berlios.de/\" onclick=\"window.open(this.href);return false;\">"; $DF2kURL2 = $DF2kURL1.$DF2k."</a>";
$GM2kURL = "<a href=\"http://upload.idb.s1.jcink.com/\" title=\"".$GM2k."\" onclick=\"window.open(this.href);return false;\">".$GM2k."</a>";
$iDBURL3 = "<a href=\"http://idb.berlios.de/\" title=\"".$iDB."\" onclick=\"window.open(this.href);return false;\">".$iDB."</a>";
$PHPQA = "PHP-Quick-Arcade"; $PHPV1 = @phpversion(); $PHPV2 = "PHP ".$PHPV1; $OSType = PHP_OS;
if($OSType=="WINNT") { $OSType="Windows NT"; } if($OSType=="WIN32") { $OSType="Windows 9x"; }
$OSType2 = $PHPV2." / ".$OSType; $ZENDV1 = @zend_version(); $ZENDV2 = "Zend engine ".$ZENDV1;
if($idbmisc['showverinfo']==true) {
@header("X-iDB-Powered-By: ".$VerInfo['iDB_Ver_Show']);
@header("Generator: ".$VerInfo['iDB_Ver_Show']); }
if($idbmisc['showverinfo']!=true) {
@header("X-iDB-Powered-By: iDB");
//@header("X-Powered-By: PHP");
@header("Generator: iDB"); }
//File naming stuff. <_< 
$exfile = array(); $exfilerss = array();
$exqstr = array(); $exqstrrss = array();
$exfile['calendar'] = 'calendar';
$prexqstr['calendar'] = null; $exqstr['calendar'] = null;
$exfile['category'] = 'category';
$prexqstr['category'] = null; $exqstr['category'] = null;
$exfile['event'] = 'event';
$prexqstr['event'] = null; $exqstr['event'] = null;
$exfile['forum'] = 'forum';
$prexqstr['forum'] = null; $exqstr['forum'] = null;
$exfile['index'] = 'index';
$prexqstr['index'] = null; $exqstr['index'] = null;
$exfile['member'] = 'member';
$prexqstr['member'] = null; $exqstr['member'] = null;
$exfile['messenger'] = 'messenger';
$prexqstr['messenger'] = null; $exqstr['messenger'] = null;
$exfile['profile'] = 'profile';
$prexqstr['profile'] = null; $exqstr['profile'] = null;
$exfile['rss'] = 'rss';
$prexqstr['rss'] = null; $exqstr['rss'] = null;
$exfile['search'] = 'search';
$prexqstr['search'] = null; $exqstr['search'] = null;
$exfile['subforum'] = 'subforum';
$prexqstr['subforum'] = null; $exqstr['subforum'] = null;
$exfile['subcategory'] = 'subcategory';
$prexqstr['subcategory'] = null; $exqstr['subcategory'] = null;
$exfile['topic'] = 'topic';
$prexqstr['topic'] = null; $exqstr['topic'] = null;
$exfile['redirect'] = 'forum';
$prexqstr['redirect'] = null; $exqstr['redirect'] = null;
$exfilejs['javascript'] = 'javascript';
$prexqstrjs['javascript'] = null; $exqstrjs['javascript'] = null;
$exfilerss['forum'] = 'forum'; 
$prexqstrrss['forum'] = null; $exqstrrss['forum'] = null;
$exfilerss['subforum'] = "subforum";
$prexqstrrss['subforum'] = null; $exqstrrss['subforum'] = null;
$exfilerss['subcategory'] = "subcategory";
$prexqstrrss['subcategory'] = null; $exqstrrss['subcategory'] = null;
$exfilerss['redirect'] = 'forum';
$prexqstrrss['redirect'] = null; $exqstrrss['redirect'] = null;
$exfilerss['topic'] = "topic";
$prexqstrrss['topic'] = null; $exqstrrss['topic'] = null;
$exfilerss['category'] = 'category';
$prexqstrrss['category'] = null; $exqstrrss['category'] = null;
$exfilerss['event'] = 'event';
$prexqstrrss['event'] = null; $exqstrrss['event'] = null;
?>