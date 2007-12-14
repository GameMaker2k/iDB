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

    $FileInfo: versioninfo.php - Last Update: 12/14/2007 SVN 136 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="versioninfo.php"||$File3Name=="/versioninfo.php") {
	require('index.php');
	exit(); }
	$rssurlon = false;
// Version info stuff. :P 
function version_info($proname,$subver,$ver,$supver,$reltype,$svnver,$showsvn) {
	$return_var = $proname." ".$reltype." ".$subver.".".$ver.".".$supver;
	if($showsvn==false) { $showsvn = null; }
	if($showsvn==true) { $return_var .= " SVN ".$svnver; }
	if($showsvn!=true&&$showsvn!=null) { $return_var .= " ".$showsvn." ".$svnver; }
	return $return_var; }
// Version number and date stuff. :P
$VER1[0] = 0; $VER1[1] = 2; $VER1[2] = 1; $VERFull[1] = $VER1[0].".".$VER1[1].".".$VER1[2];
$VER2[0] = "Pre-Alpha"; $VER2[1] = "PA"; $VER2[2] = "SVN"; $SubVerN = 136; $RName = "iDB"; $SFName = "IntDB";
$SVNDay[0] = 12; $SVNDay[1] = 14; $SVNDay[2] = 2007; $SVNDay[3] = $SVNDay[0]."/".$SVNDay[1]."/".$SVNDay[2];
$VerInfo['iDB_Ver'] = version_info($RName,$VER1[0],$VER1[1],$VER1[2],$VER2[1],$SubVerN,false);
$VerInfo['iDB_Ver_SVN'] = version_info($RName,$VER1[0],$VER1[1],$VER1[2],$VER2[1],$SubVerN,true);
$VerInfo['iDB_Full_Ver'] = version_info($RName,$VER1[0],$VER1[1],$VER1[2],$VER2[0],$SubVerN,false);
$VerInfo['iDB_Full_Ver_SVN'] = version_info($RName,$VER1[0],$VER1[1],$VER1[2],$VER2[0],$SubVerN,true);
$VerInfo['iDB_Ver_Show'] = $VerInfo['iDB_Ver_SVN']; $VerInfo['iDB_Full_Ver_Show'] = $VerInfo['iDB_Full_Ver_SVN'];
if(isset($Settings['showverinfo'])) { $idbmisc['showverinfo'] = $Settings['showverinfo']; }
if(!isset($Settings['showverinfo'])) { $idbmisc['showverinfo'] = false; }
// URLs and names and stuff. :P
$CD2k = "Cool Dude 2k"; $GM2k = "Game Maker 2k";
$iDB = "Internet Discussion Boards"; $iTB = "Internet Tag Boards"; $DF2k = "Discussion Forums 2k"; $TB2k = "Tag Boards 2k";
$iDBURL1 = "<a href=\"http://intdb.sourceforge.net/\" onclick=\"window.open(this.href);return false;\">"; $iDBURL2 = $iDBURL1.$iDB."</a>";
$DF2kURL1 = "<a href=\"http://df2k.berlios.de/\" onclick=\"window.open(this.href);return false;\">"; $DF2kURL2 = $DF2kURL1.$DF2k."</a>";
$GM2kURL = "<a href=\"http://upload.idb.s1.jcink.com/\" title=\"".$GM2k."\" onclick=\"window.open(this.href);return false;\">".$GM2k."</a>";
$iDBURL3 = "<a href=\"http://idb.berlios.de/\" title=\"".$iDB."\" onclick=\"window.open(this.href);return false;\">".$iDB."</a>";
$PHPQA = "PHP-Quick-Arcade"; $PHPV1 = @phpversion(); $PHPV2 = "PHP ".$PHPV1; $OSType = PHP_OS; // Check OS Name
if($OSType=="WINNT") { $OSType="Windows NT"; } if($OSType=="WIN32") { $OSType="Windows 9x"; }
$OSType2 = $PHPV2." / ".$OSType; $ZENDV1 = @zend_version(); $ZENDV2 = "Zend engine ".$ZENDV1;
// Show or hide the version number
if($idbmisc['showverinfo']==true) {
@header("X-".$RName."-Powered-By: ".$VerInfo['iDB_Ver_Show']);
@header("Generator: ".$VerInfo['iDB_Ver_Show']); }
if($idbmisc['showverinfo']!=true) {
@header("X-".$RName."-Powered-By: ".$RName);
//@header("X-Powered-By: PHP");
@header("Generator: ".$iDB); }
?>