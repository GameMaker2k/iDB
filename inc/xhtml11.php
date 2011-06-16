<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2011 iDB Support - http://idb.berlios.de/
    Copyright 2004-2011 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: xhtml11.php - Last Update: 06/16/2011 SVN 675 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="xhtml11.php"||$File3Name=="/xhtml11.php") {
	require('index.php');
	exit(); }
// Check to see if we serv the file as html or xhtml
// if we do xhtml we also check to see if user's browser 
// can dispay if or else fallback to html
if($Settings['output_type']!="xhtml") {
	$Settings['output_type'] = "xhtml"; }
if($Settings['output_type']=="html") {
	$ccstart = "//<!--"; $ccend = "//-->";
header("Content-Type: text/html; charset=".$Settings['charset']); }
if($Settings['output_type']=="xhtml") {
if(stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml")) {
	$ccstart = "//<![CDATA["; $ccend = "//]]>";
header("Content-Type: application/xhtml+xml; charset=".$Settings['charset']);
	xml_doc_start("1.0",$Settings['charset']); }
else { if (stristr($_SERVER["HTTP_USER_AGENT"],"W3C_Validator")) {
	$ccstart = "//<![CDATA["; $ccend = "//]]>";
   header("Content-Type: application/xhtml+xml; charset=".$Settings['charset']);
	xml_doc_start("1.0",$Settings['charset']);
} else { $ccstart = "//<!--"; $ccend = "//-->";
	header("Content-Type: text/html; charset=".$Settings['charset']); } } }
if($Settings['output_type']!="xhtml") {
	if($Settings['output_type']!="html") {
		$ccstart = "//<!--"; $ccend = "//-->";
header("Content-Type: text/html; charset=".$Settings['charset']); } }
if($checklowview===true) { $ThemeSet['CSSType'] = "lowview"; }
if($checklowview===true&&$_GET['act']=="lowview") { 
   $ThemeSet['CSSType'] = "lowview"; 
   $ThemeSet['ThemeName'] = $OrgName." Low Theme";
   $ThemeSet['ThemeMaker'] =$iDB_Author;
   $ThemeSet['ThemeVersion'] = $VER1[0].".".$VER1[1].".".$VER1[2];
   $ThemeSet['ThemeVersionType'] = $VER2[0];
   $ThemeSet['ThemeSubVersion'] = $VER2[2]." ".$SubVerN;
   $ThemeSet['MakerURL'] = $iDBHome."support/?act=lowview";
   $ThemeSet['CopyRight'] = $ThemeSet['ThemeName']." was made by <a href=\"".$ThemeSet['MakerURL']."\" title=\"".$ThemeSet['ThemeMaker']."\">".$ThemeSet['ThemeMaker']."</a>";
   $ThemeInfo['ThemeName'] = $ThemeSet['ThemeName'];
   $ThemeInfo['ThemeMaker'] = $ThemeSet['ThemeMaker'];
   $ThemeInfo['ThemeVersion'] = $ThemeSet['ThemeVersion'];
   $ThemeInfo['ThemeVersionType'] = $ThemeSet['ThemeVersionType'];
   $ThemeInfo['ThemeSubVersion'] = $ThemeSet['ThemeSubVersion'];
   $ThemeInfo['MakerURL'] = $ThemeSet['MakerURL'];
   $ThemeInfo['CopyRight'] = $ThemeSet['CopyRight']; }
if($ThemeSet['CSSType']!="import"&&
   $ThemeSet['CSSType']!="link"&&
   $ThemeSet['CSSType']!="lowview"&&
   $ThemeSet['CSSType']!="xml"&&
   $ThemeSet['CSSType']!="sql") { 
   $ThemeSet['CSSType'] = "import"; }
if($ThemeSet['CSSType']=="xhtml") {
   xml_tag_make("xml-stylesheet","type=text/css&href=".$ThemeSet['CSS']); }
header("Content-Style-Type: text/css");
header("Content-Script-Type: text/javascript");
if($Settings['showverinfo']!="on") {
$iDBURL1 = "<a href=\"".$iDBHome."\" title=\"".$iDB."\" onclick=\"window.open(this.href);return false;\">"; }
if($Settings['showverinfo']=="on") {
$iDBURL1 = "<a href=\"".$iDBHome."\" title=\"".$VerInfo['iDB_Ver_Show']."\" onclick=\"window.open(this.href);return false;\">"; }
$GM2kURL = "<a href=\"".$iDBHome."support/category.php?act=view&amp;id=2\" title=\"".$GM2k."\" onclick=\"window.open(this.href);return false;\">".$GM2k."</a>";
$csryear = "2004"; $cryear = date("Y"); if($cryear<=2004) { $cryear = "2005"; }
$BSDL = "<a href=\"".url_maker($exfile['index'],$Settings['file_ext'],"act=bsd",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'])."\" title=\"".$RName." is dual-licensed under the Revised BSD License\">BSDL</a>";
$GPL = "<a href=\"".url_maker($exfile['index'],$Settings['file_ext'],"act=bsd",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'])."\" title=\"".$RName." is dual-licensed under the Gnu General Public License\">GPL</a>";
$DualLicense = $BSDL." &amp; ".$GPL;
$endpagevar = "<div class=\"copyright\">Powered by ".$iDBURL1.$RName."</a> &copy; ".$GM2kURL." @ ".$csryear." - ".$extext = null;
if($checklowview!==true) { $extext = "<a href=\"".url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'])."\">Low-Version</a>"; }
if($checklowview===true&&$_GET['act']!="lowview") { $extext = "<a href=\"".url_maker($exfile['index'],$Settings['file_ext'],"act=lowview",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'])."\">Low-Version</a>"; }
if($checklowview===true&&$_GET['act']=="lowview") {  $extext = "<a href=\"".url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'])."\">High-Version</a>"; }
$endpagevar = "<div class=\"copyright\">Powered by ".$iDBURL1.$RName."</a> &copy; ".$GM2kURL." @ ".$csryear." - ".$cryear." <br />\n".$ThemeSet['CopyRight']." | ".$extext; 
header("Content-Language: en");
header("Vary: Accept-Encoding");
// Check if we are on a secure HTTP connection
if($_SERVER['HTTPS']=="on") { $prehost = "https://"; }
if($_SERVER['HTTPS']!="on") { $prehost = "http://"; }
// Get the board's url
if($Settings['idburl']=="localhost"||$Settings['idburl']==null) {
	$BoardURL = $prehost.$_SERVER["HTTP_HOST"].$basedir; }
if($Settings['idburl']!="localhost"&&$Settings['idburl']!=null) {
	$BoardURL = $Settings['idburl']; 
	if($Settings['qstr']!="/") {
	$AltBoardURL = $BoardURL; } 
	if($Settings['qstr']=="/") { 
	$AltBoardURL = preg_replace("/\/$/","",$BoardURL); } }
 // HTML Document Starts, HTML meta tags and other html, head tags ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
   "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Language" content="en" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $Settings['charset']; ?>" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Cache-Control" content="private, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0" />
<meta http-equiv="Pragma" content="private, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0" />
<meta http-equiv="Expires" content="<?php echo gmdate("D, d M Y H:i:s")." GMT"; ?>" />
<meta http-equiv="P3P" content='CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"' /> 
<meta http-equiv="P3P" name="CP" content="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT" />
<meta http-equiv="Expires" content="<?php echo gmdate("D, d M Y H:i:s")." GMT"; ?>" />
<?php 
if(!isset($_SERVER['HTTP_USER_AGENT'])) {
	$_SERVER['HTTP_USER_AGENT'] = ""; }
if(strpos($_SERVER['HTTP_USER_AGENT'], "msie") && 
	!strpos($_SERVER['HTTP_USER_AGENT'], "opera")){ ?>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php } if(strpos($_SERVER['HTTP_USER_AGENT'], "chromeframe")) { ?>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
<?php } ?>
<base href="<?php echo $BoardURL; ?>" />
<?php if($Settings['showverinfo']=="on") { ?>
<meta name="Generator" content="<?php echo $VerInfo['iDB_Ver_Show']; ?>" />
<?php } if($Settings['showverinfo']!="on") { ?>
<meta name="Generator" content="<?php echo $iDB; ?>" />
<?php } echo "\n"; ?>
<meta name="Author" content="<?php echo $SettInfo['Author']; ?>" />
<meta name="Keywords" content="<?php echo $SettInfo['Keywords']; ?>" />
<meta name="Description" content="<?php echo $SettInfo['Description']; ?>" />
<meta name="ROBOTS" content="Index, FOLLOW" />
<meta name="revisit-after" content="1 days" />
<meta name="GOOGLEBOT" content="Index, FOLLOW" />
<meta name="resource-type" content="document" />
<meta name="distribution" content="global" />
<?php if($Settings['showverinfo']=="on") { ?>
<!-- generator="<?php echo $VerInfo['iDB_Ver_Show']; ?>" -->
<?php } if($Settings['showverinfo']!="on") { ?>
<!-- generator="<?php echo $iDB; ?>" -->
<?php } echo "\n"; ?>

<script type="text/javascript" src="<?php echo url_maker($exfilejs['javascript'],$Settings['js_ext'],null,$Settings['qstr'],$Settings['qsep'],$prexqstrjs['javascript'],$exqstrjs['javascript']); ?>"></script>
<link rel="Start" href="<?php echo $AltBoardURL.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>" title="<?php echo $Settings['board_name'].$idbpowertitle; ?>" />
<link rel="Copyright" href="<?php echo $AltBoardURL.url_maker($exfile['index'],$Settings['file_ext'],"act=bsd",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>" title="Copyright Notice" />
<?php if($Settings['showverinfo']=="on") { ?>
<link rel="Generator" href="<?php echo $iDBHome; ?>" title="<?php echo $VerInfo['iDB_Ver_Show']; ?>" />
<?php } if($Settings['showverinfo']!="on") { ?>
<link rel="Generator" href="<?php echo $iDBHome; ?>" title="<?php echo $iDB; ?>" />
<?php } echo "\n"; ?>
<!-- ^_^ Stephanie Braun -->
<?php if($ThemeSet['CSSType']=="import") { ?>
<style type="text/css">
/* Import the theme css file */
<?php echo "\n@import url(\"".$ThemeSet['CSS']."\");\n"; ?>
</style>
<?php } if($ThemeSet['CSSType']=="sql") { ?>
<style type="text/css">
<?php echo $ThemeSet['CSS']; ?>
</style>
<?php } if($ThemeSet['CSSType']=="link") { ?>
<link rel="prefetch alternate stylesheet" href="<?php echo $ThemeSet['CSS']; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $ThemeSet['CSS']; ?>" />
<?php } if($ThemeSet['CSSType']=="lowview") { ?>
<style type="text/css">
/* (Low View / Lo-Fi ) version start */
body {
background-color: #FFFFFF;
color: #000000;
font-family: Verdana, Tahoma, Arial, Trebuchet MS, Sans-Serif, Georgia, Courier, Times New Roman, Serif;
font-size: 16px;
margin: 20px;
padding: 0px;
}
.copyright {
text-align: center;
font-family: Sans-Serif;
font-size: 12px;
line-height: 11px;
color: #000000;
}
.EditReply {
color: #000000;
font-size: 9px;
}
</style>
<?php } if($ThemeSet['FavIcon']!=null) { ?>
<link rel="icon" href="<?php echo $ThemeSet['FavIcon']; ?>" />
<link rel="shortcut icon" href="<?php echo $ThemeSet['FavIcon']; ?>" />
<?php } ?>
<!-- Renee Sabonis ^_^ -->