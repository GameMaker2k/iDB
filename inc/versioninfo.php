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

    $FileInfo: versioninfo.php - Last Update: 12/02/2009 SVN 371 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="versioninfo.php"||$File3Name=="/versioninfo.php") {
	require('index.php');
	exit(); }
	$rssurlon = "off";
// Version info stuff. :P 
function version_info($proname,$subver,$ver,$supver,$reltype,$svnver,$showsvn) {
	$return_var = $proname." ".$reltype." ".$subver.".".$ver.".".$supver;
	if($showsvn===false) { $showsvn = null; }
	if($showsvn===true) { $return_var .= " SVN ".$svnver; }
	if($showsvn!==true&&$showsvn!=null) { $return_var .= " ".$showsvn." ".$svnver; }
	return $return_var; }
// Version number and date stuff. :P
$VER1[0] = 0; $VER1[1] = 3; $VER1[2] = 2; $VERFull[1] = $VER1[0].".".$VER1[1].".".$VER1[2];
$VER2[0] = "Alpha"; $VER2[1] = "Al"; $VER2[2] = "SVN"; $SubVerN = 371; $RName = "iDB"; $SFName = "IntDB";
$SVNDay[0] = 12; $SVNDay[1] = 02; $SVNDay[2] = 2009; $SVNDay[3] = $SVNDay[0]."/".$SVNDay[1]."/".$SVNDay[2];
$VerInfo['iDB_Ver'] = version_info($RName,$VER1[0],$VER1[1],$VER1[2],$VER2[1],$SubVerN,false);
$VerInfo['iDB_Ver_SVN'] = version_info($RName,$VER1[0],$VER1[1],$VER1[2],$VER2[1],$SubVerN,true);
$VerInfo['iDB_Full_Ver'] = version_info($RName,$VER1[0],$VER1[1],$VER1[2],$VER2[0],$SubVerN,false);
$VerInfo['iDB_Full_Ver_SVN'] = version_info($RName,$VER1[0],$VER1[1],$VER1[2],$VER2[0],$SubVerN,true);
$VerInfo['iDB_Ver_Show'] = $VerInfo['iDB_Ver_SVN']; $VerInfo['iDB_Full_Ver_Show'] = $VerInfo['iDB_Full_Ver_SVN'];
// URLs and names and stuff. :P $KSP = "Kazuki Sabonis Przyborowski";
$iDBHome = "http://idb.berlios.de/"; $DF2kHome = "http://df2k.berlios.de/";
$VerCheckURL = $iDBHome."?act=vercheck"; $OrgName = "iDB"; 
$CD2k = "Kazuki Przyborowski"; $GM2k = "Game Maker 2k"; $iDB_Author = "Kazuki";
$iDB = "Internet Discussion Boards"; $iTB = "Internet Tag Boards"; $DF2k = "Discussion Forums 2k"; $TB2k = "Tag Boards 2k";
$iDBURL1 = "<a href=\"".$iDBHome."\" onclick=\"window.open(this.href);return false;\">"; $iDBURL2 = $iDBURL1.$iDB."</a>";
$DF2kURL1 = "<a href=\"".$DF2kHome."\" onclick=\"window.open(this.href);return false;\">"; $DF2kURL2 = $DF2kURL1.$DF2k."</a>";
$GM2kURL = "<a href=\"".$iDBHome."support/category.php?act=view&amp;id=2\" title=\"".$GM2k."\" onclick=\"window.open(this.href);return false;\">".$GM2k."</a>";
$iDBURL3 = "<a href=\"".$iDBHome."\" title=\"".$iDB."\" onclick=\"window.open(this.href);return false;\">".$iDB."</a>";
$PHPQA = "PHP-Quick-Arcade|http://quickarcade.jcink.com/"; $TFBB = "TextFileBB|https://launchpad.net/tfbb";
$PHPQA = explode("|",$PHPQA); $TFBB = explode("|",$TFBB);
$PHPQA = "<a href=\"".$PHPQA[1]."\" title=\"".$PHPQA[0]."\" onclick=\"window.open(this.href);return false;\">".$PHPQA[0]."</a>";
$TFBB = "<a href=\"".$TFBB[1]."\" title=\"".$TFBB[0]."\" onclick=\"window.open(this.href);return false;\">".$TFBB[0]."</a>";
$PHPV1 = phpversion(); $PHPV2 = "PHP ".$PHPV1; $OSType = PHP_OS; // Check OS Name
if($OSType=="WINNT") { $OSType="Windows NT"; } if($OSType=="WIN32") { $OSType="Windows 9x"; }
$OSType2 = $PHPV2." / ".$OSType; $ZENDV1 = zend_version(); $ZENDV2 = "Zend engine ".$ZENDV1;
// Show or hide the version number
if($Settings['showverinfo']=="on") {
//header("X-".$RName."-Powered-By: ".$VerInfo['iDB_Ver_Show']);
header("Generator: ".$VerInfo['iDB_Ver_Show']); }
if($Settings['showverinfo']!="on") {
//header("X-".$RName."-Powered-By: ".$RName);
//header("X-Powered-By: PHP");
header("Generator: ".$RName); }
?>
