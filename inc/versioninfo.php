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

    $FileInfo: versioninfo.php - Last Update: 9/18/2024 SVN 1196 - Author: cooldude2k $
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
	if($showsvn!==true&&$showsvn!==null) { $return_var .= " ".$showsvn." ".$svnver; }
	return $return_var; }
// Version number and date stuff. :P
$VER1[0] = 0; $VER1[1] = 6; $VER1[2] = 8; $VERFull[1] = $VER1[0].".".$VER1[1].".".$VER1[2];
$VER2[0] = "Alpha"; $VER2[1] = "Al"; $VER2[2] = "SVN"; $SubVerN = 1196; $GitRevPreN = '$Id$';
$GitRevN = getGitRevision($GitRevPreN);
$SVNDay[0] = 9; $SVNDay[1] = 18; $SVNDay[2] = 2024; $SVNDay[3] = $SVNDay[0]."/".$SVNDay[1]."/".$SVNDay[2];
$AltName = "DF2k"; $AltName2 = "DF2k"; $RName = "iDB"; $SFName = "IntDB";
$RFullName = "Internet Discussion Boards"; $AltFullName = "Discussion Forums 2k";
$AltGM2k = "Game Maker 2k"; $AltGM2kShort = "GM2k";
$VerCheckName = "iDB"; $AltVerCheckName = "DF2k"; $AltiDBHome = "https://idb.osdn.jp/";
$AltGM2kHome = "https://idb.osdn.jp/"; $AltGM2kURL = "<a href=\"".$AltGM2kHome."\" title=\"".$AltGM2k."\" onclick=\"window.open(this.href);return false;\">".$AltGM2k."</a>";
$iDBTheme = "iDB"; $AltiDBTheme = "Gray"; 
$UserAgentName = "iDB-Forum"; $AltUserAgentName = "DF2k-Forum";
$AltNameJP = "ディーエフ二千"; $AltName2JP = "ディーエフ二千"; $RNameJP = "アイディービー"; $SFNameJP = "インティービー";
$RFullNameJP = "インターネットディスカッションボード"; $AltFullNameJP = "ディスカッションフォーラム二千";
$AltGM2kJP = "ゲームメーカー二千"; $AltGM2kShortJP = "ジーエム二千";
$AltNameKO = "디에프 이천"; $AltName2KO = "디에프 이천"; $RNameKO = "아이디비"; $SFNameKO = "인티비";
$RFullNameKO = "인터넷 토론 게시판"; $AltFullNameKO = "토론 포럼 이천";
$AltGM2kKO = "게임 메이커 이천"; $AltGM2kShortKO = "지엠 이천";
$AltNameSC = "DF二千"; $AltName2SC = "DF二千"; $RNameSC = "i讨论板"; $SFNameSC = "Int标签板";
$RFullNameSC = "互联网讨论板"; $AltFullNameSC = "讨论论坛二千";
$AltGM2kSC = "游戏制作二千"; $AltGM2kShortSC = "GM二千";
$AltNameTC = "DF二千"; $AltName2TC = "DF二千"; $RNameTC = "i討論板"; $SFNameTC = "Int標籤板";
$RFullNameTC = "互聯網討論板"; $AltFullNameTC = "討論論壇二千";
$AltGM2kTC = "遊戲製作二千"; $AltGM2kShortTC = "GM二千";
if(!isset($Settings['usealtname'])) { $Settings['usealtname'] = "no"; }
if(isset($Settings['usealtname'])&&$Settings['usealtname']=="yes") {
if(isset($iDBAltName['VER1'][0])) { $VER1[0] = $iDBAltName['VER1'][0]; }
if(isset($iDBAltName['VER1'][1])) { $VER1[1] = $iDBAltName['VER1'][1]; }
if(isset($iDBAltName['VER1'][2])) { $VER1[2] = $iDBAltName['VER1'][2]; }
if(isset($iDBAltName['VER1'][0])&&
	isset($iDBAltName['VER1'][1])&&
	isset($iDBAltName['VER1'][2])) { 
	$VERFull[1] = $VER1[0].".".$VER1[1].".".$VER1[2]; }
if(isset($iDBAltName['VER2'][0])) { $VER2[0] = $iDBAltName['VER2'][0]; }
if(isset($iDBAltName['VER2'][1])) { $VER2[1] = $iDBAltName['VER2'][1]; }
if(isset($iDBAltName['VER2'][2])) { $VER2[2] = $iDBAltName['VER2'][2]; }
if(isset($iDBAltName['SubVerN'])) { $SubVerN = $iDBAltName['SubVerN']; }
if(isset($iDBAltName['SVNDay'][0])) { $SVNDay[0] = $iDBAltName['SVNDay'][0]; }
if(isset($iDBAltName['SVNDay'][1])) { $SVNDay[1] = $iDBAltName['SVNDay'][1]; }
if(isset($iDBAltName['SVNDay'][2])) { $SVNDay[2] = $iDBAltName['SVNDay'][2]; }
if(isset($iDBAltName['SVNDay'][0])&&
	isset($iDBAltName['SVNDay'][1])&&
	isset($iDBAltName['SVNDay'][2])) { 
	$SVNDay[3] = $SVNDay[0]."/".$SVNDay[1]."/".$SVNDay[2]; }
if(isset($iDBAltName['AltName'])) { $AltName = $iDBAltName['AltName']; }
if(isset($iDBAltName['AltName2'])) { $AltName2 = $iDBAltName['AltName2']; }
if(isset($iDBAltName['AltFullName'])) { $AltFullName = $iDBAltName['AltFullName']; } 
if(isset($iDBAltName['AltVerCheckName'])) { $AltVerCheckName = $iDBAltName['AltVerCheckName']; } 
if(isset($iDBAltName['AltUserAgentName'])) { $AltUserAgentName = $iDBAltName['AltUserAgentName']; } 
if(isset($iDBAltName['AltiDBHome'])) { $AltiDBHome = $iDBAltName['AltiDBHome']; } 
if(isset($iDBAltName['AltGM2k'])) { $AltGM2k = $iDBAltName['AltGM2k']; } 
if(isset($iDBAltName['AltGM2kHome'])) { $AltGM2kHome = $iDBAltName['AltGM2kHome']; } 
if(isset($iDBAltName['AltGM2kURL'])) { $AltGM2kURL = $iDBAltName['AltGM2kURL']; } 
if(isset($iDBAltName['AltiDBTheme'])) { $AltiDBTheme = $iDBAltName['AltiDBTheme']; } 
if(isset($iDBAltName['VerCheckURL'])) { $Settings['VerCheckURL'] = $iDBAltName['VerCheckURL']; } }
if(isset($Settings['usealtname'])&&$Settings['usealtname']=="yes") {
	$RName = $AltName2; $SFName = $AltName; $RFullName = $AltFullName; $VerCheckName = $AltVerCheckName; $UserAgentName = $AltUserAgentName; }
$VerInfo['iDB_Ver'] = version_info($RName,$VER1[0],$VER1[1],$VER1[2],$VER2[1],$SubVerN,false);
$VerInfo['iDB_Ver_SVN'] = version_info($RName,$VER1[0],$VER1[1],$VER1[2],$VER2[1],$SubVerN,$VER2[2]);
$VerInfo['iDB_Full_Ver'] = version_info($RName,$VER1[0],$VER1[1],$VER1[2],$VER2[0],$SubVerN,false);
$VerInfo['iDB_Full_Ver_SVN'] = version_info($RName,$VER1[0],$VER1[1],$VER1[2],$VER2[0],$SubVerN,$VER2[2]);
$VerInfo['iDB_Ver_Show'] = $VerInfo['iDB_Ver_SVN']; $VerInfo['iDB_Full_Ver_Show'] = $VerInfo['iDB_Full_Ver_SVN'];
define("_iDB_Ver_", $VerInfo['iDB_Ver']); define("_iDB_Ver_SVN_", $VerInfo['iDB_Ver_SVN']);
define("_iDB_Full_Ver_", $VerInfo['iDB_Full_Ver']); define("_iDB_Full_Ver_SVN_", $VerInfo['iDB_Full_Ver_SVN']);
define("_iDB_Ver_Show_", $VerInfo['iDB_Ver_Show']); define("_iDB_Full_Ver_Show_", $VerInfo['iDB_Full_Ver_Show']);
/* 
URLs and names and stuff. :P 
$KSP = "Kazuki Suzuki Przyborowski";
$KSPAlt = "Kazuki Suzuki Przyborowski";
*/
$iDBHome = "https://idb.osdn.jp/"; $DF2kHome = "https://idb.osdn.jp/"; 
$OrgName = "iDB"; $AltOrgName = "DF2k"; $AltiDB = "Discussion Forums 2k";
$AltSQLDumper = null;
if(isset($Settings['usealtname'])&&$Settings['usealtname']=="yes") {
if(isset($iDBAltName['AltOrgName'])) { $AltOrgName = $iDBAltName['AltOrgName']; }
if(isset($iDBAltName['AltiDB'])) { $AltiDB = $iDBAltName['AltiDB']; }
if(isset($iDBAltName['AltSQLDumperName'])) { $AltSQLDumper = $iDBAltName['AltSQLDumperName']; } }
if(!isset($Settings['VerCheckURL'])||
	$Settings['VerCheckURL']==="") {
$VerCheckURL = $iDBHome."?act=vercheck"; }
if(isset($Settings['VerCheckURL'])&&
	$Settings['VerCheckURL']!=="") {
$VerCheckURL = $Settings['VerCheckURL']; }
$VerCheckQuery = parse_url($VerCheckURL);
$VerCheckQuery = $VerCheckQuery['query'];
if($VerCheckQuery=="") { $VerCheckURL = $VerCheckURL."?"; }
if(!isset($Settings['IPCheckURL'])||
	$Settings['IPCheckURL']==="") {
//$IPCheckURL = 'http://cqcounter.com/whois/?query=%s';
$IPCheckURL = 'https://tools.keycdn.com/geo?host=%s'; }
if(isset($Settings['IPCheckURL'])&&
	$Settings['IPCheckURL']!=="") {
$IPCheckURL = $Settings['IPCheckURL']; }
$CD2k = "Kazuki Przyborowski"; $CD2k_Full = "Kazuki Suzuki Przyborowski";
$GM2k = "Game Maker 2k"; $iDB_Author = "Kazuki";
$iDB = "Internet Discussion Boards"; $iTB = "Internet Tag Boards"; 
$DF2k = "Discussion Forums 2k"; $TB2k = "Tag Boards 2k";
$GM2kJP = "ゲームメーカー二千";
$iDBJP = "インターネットディスカッションボード"; $iTBJP = "インターネットタグボード"; 
$DF2kJP = "ディスカッションフォーラム二千"; $TB2kJP = "タグボード二千";
$GM2kKO = "게임 메이커 이천";
$iDBKO = "인터넷 토론 게시판"; $iTBKO = "인터넷 태그 게시판"; 
$DF2kKO = "토론 포럼 이천"; $TB2kKO = "태그 게시판 이천";
$GM2kSC = "游戏制作二千";
$iDBSC = "互联网讨论板"; $iTBSC = "互联网标签板"; 
$DF2kSC = "讨论论坛二千"; $TB2kSC = "标签板二千";
$GM2kTC = "遊戲製作二千";
$iDBTC = "互聯網討論板"; $iTBTC = "互聯網標籤板"; 
$DF2kTC = "討論論壇二千"; $TB2kTC = "標籤板二千";
$TheProgrammerNapsAlt = "The programmer is taking a short nap. \nHang in there! You’ve got this, programmer! ";
$TheProgrammerNaps = "The programmer has a nap. \nHold out! Programmer! ";
$TheProgrammerNapsJP = "プログラマーは昼寝しています。\n頑張れ！プログラマー！ ";
$TheProgrammerNapsKO = "프로그래머는 낮잠을 자고 있어요.\n힘내요! 프로그래머! ";
$TheProgrammerNapsSC = "程序员正在小憩。\n加油！程序员！";
$TheProgrammerNapsTC = "程式設計師正在小憩。\n加油！程式設計師！ ";
if(isset($Settings['usealtname'])&&$Settings['usealtname']=="yes") { 
	$iDB = $AltiDB; $OrgName = $AltOrgName; $iDBTheme = $AltiDBTheme; }
$iDBURL1 = "<a href=\"".$iDBHome."\" onclick=\"window.open(this.href);return false;\">"; $iDBURL2 = $iDBURL1.$iDB."</a>";
$DF2kURL1 = "<a href=\"".$DF2kHome."\" onclick=\"window.open(this.href);return false;\">"; $DF2kURL2 = $DF2kURL1.$DF2k."</a>";
$GM2kHome = $iDBHome."support/category.php?act=view&amp;id=2";
$GM2kURL = "<a href=\"".$GM2kHome."\" title=\"".$GM2k."\" onclick=\"window.open(this.href);return false;\">".$GM2k."</a>";
$iDBURL3 = "<a href=\"".$iDBHome."\" title=\"".$iDB."\" onclick=\"window.open(this.href);return false;\">".$iDB."</a>";
$PHPQA = "PHP-Quick-Arcade|http://quickarcade.jcink.com/"; $TFBB = "TextFileBB|https://launchpad.net/tfbb";
$PHPQA = explode("|",$PHPQA); $TFBB = explode("|",$TFBB);
$PHPQA = "<a href=\"".$PHPQA[1]."\" title=\"".$PHPQA[0]."\" onclick=\"window.open(this.href);return false;\">".$PHPQA[0]."</a>";
$TFBB = "<a href=\"".$TFBB[1]."\" title=\"".$TFBB[0]."\" onclick=\"window.open(this.href);return false;\">".$TFBB[0]."</a>";
if(isset($Settings['usealtname'])&&$Settings['usealtname']=="yes") { 
	$iDBHome = $AltiDBHome; $GM2k = $AltGM2k; $GM2kHome = $AltGM2kHome; $GM2kURL = $AltGM2kURL; }
$PHPV1 = phpversion(); $PHPV2 = "PHP ".$PHPV1; $OSType = @php_uname("s"); $OSType .= " ".@php_uname("r");
$OSType .= " ".@php_uname("m"); if($OSType==""||!isset($OSType)) { $OSType = PHP_OS; } // Check OS Name
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
if(!isset($Settings['hideverinfohttp'])) {
	$Settings['hideverinfohttp'] = "off"; }
if($Settings['hideverinfohttp']=="on") {
header("X-Powered-By: ");
header("Generator: "); }
?>
