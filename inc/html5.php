<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2012 iDB Support - http://idb.berlios.de/
    Copyright 2004-2012 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: html5.php - Last Update: 12/30/2011 SVN 781 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="xhtml10.php"||$File3Name=="/xhtml10.php") {
	require('index.php');
	exit(); }
$XHTML5 = false;
// Check to see if we serv the file as html or xhtml
// if we do xhtml we also check to see if user's browser 
// can dispay if or else fallback to html
if($Settings['output_type']=="html") {
	$ccstart = "//<!--"; $ccend = "//-->"; $XHTML5 = false;
header("Content-Type: text/html; charset=".$Settings['charset']); }
if($Settings['output_type']=="xhtml") {
if(stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml")) {
	$ccstart = "//<![CDATA["; $ccend = "//]]>"; $XHTML5 = true;
	header("Content-Type: application/xhtml+xml; charset=".$Settings['charset']); }
else { if (stristr($_SERVER["HTTP_USER_AGENT"],"W3C_Validator")) {
	$ccstart = "//<![CDATA["; $ccend = "//]]>"; $XHTML5 = true;
   header("Content-Type: application/xhtml+xml; charset=".$Settings['charset']);
} else { $ccstart = "//<!--"; $ccend = "//-->"; $XHTML5 = false;
	header("Content-Type: text/html; charset=".$Settings['charset']); } } }
if($Settings['output_type']!="xhtml") {
	if($Settings['output_type']!="html") {
		$ccstart = "//<!--"; $ccend = "//-->"; $XHTML5 = false;
header("Content-Type: text/html; charset=".$Settings['charset']); } }
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
header("Content-Style-Type: text/css");
header("Content-Script-Type: text/javascript");
if($Settings['showverinfo']!="on") {
$iDBURL1 = "<a href=\"".$iDBHome."\" title=\"".$iDB."\" onclick=\"window.open(this.href);return false;\">"; }
if($Settings['showverinfo']=="on") {
$iDBURL1 = "<a href=\"".$iDBHome."\" title=\"".$VerInfo['iDB_Ver_Show']."\" onclick=\"window.open(this.href);return false;\">"; }
$GM2kURL = "<a href=\"".$GM2kHome."\" title=\"".$GM2k."\" onclick=\"window.open(this.href);return false;\">".$GM2k."</a>";
$csryear = "2004"; $cryear = date("Y"); if($cryear<=2004) { $cryear = "2005"; }
$BSDL = "<a href=\"".url_maker($exfile['index'],$Settings['file_ext'],"act=bsd",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'])."\" title=\"".$RName." is dual-licensed under the Revised BSD License\">BSDL</a>";
$GPL = "<a href=\"".url_maker($exfile['index'],$Settings['file_ext'],"act=bsd",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'])."\" title=\"".$RName." is dual-licensed under the Gnu General Public License\">GPL</a>";
$DualLicense = $BSDL." &amp; ".$GPL;
$extext = null;
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
// Get the html level
if($Settings['html_level']!="Strict") {
	if($Settings['html_level']!="Transitional") {
		$Settings['html_level'] = "Transitional"; } }
// HTML Document Starts
ob_start("idb_suboutput_handler");
if($XHTML5===false) {
?>
<!DOCTYPE html>
<?php // HTML meta tags and other html, head tags ?>
<html lang="en">
<?php } if($XHTML5===true) { ?>
<!DOCTYPE html [
<!ENTITY nbsp "&#160;">
<!ENTITY copy "&#169;">
<!ENTITY reg "&#174;">
<!ENTITY Aacute "&#193;">
<!ENTITY aacute "&#225;">
<!ENTITY Agrave "&#224;">
<!ENTITY agrave "&#192;">
<!ENTITY Acirc "&#194;">
<!ENTITY acirc "&#226;">
<!ENTITY Auml "&#196;">
<!ENTITY auml "&#228;">
<!ENTITY Atilde "&#195;">
<!ENTITY atilde "&#227;">
<!ENTITY Aring "&#197;">
<!ENTITY aring "&#229;">
<!ENTITY Aelig "&#198;">
<!ENTITY aelig "&#230;">
<!ENTITY Ccedil "&#199;">
<!ENTITY ccedil "&#231;">
<!ENTITY Eth "&#208;">
<!ENTITY eth "&#240;">
<!ENTITY Eacute "&#201;">
<!ENTITY eacute "&#233;">
<!ENTITY Egrave "&#200;">
<!ENTITY egrave "&#232;">
<!ENTITY Ecirc "&#202;">
<!ENTITY ecirc "&#234;">
<!ENTITY Euml "&#203;">
<!ENTITY euml "&#235;">
<!ENTITY Iacute "&#205;">
<!ENTITY iacute "&#237;">
<!ENTITY Igrave "&#204;">
<!ENTITY igrave "&#236;">
<!ENTITY Icirc "&#206;">
<!ENTITY icirc "&#238;">
<!ENTITY Iuml "&#207;">
<!ENTITY iuml "&#239;">
<!ENTITY Ntilde "&#209;">
<!ENTITY ntilde "&#241;">
<!ENTITY Oacute "&#211;">
<!ENTITY oacute "&#243;">
<!ENTITY Ograve "&#210;">
<!ENTITY ograve "&#242;">
<!ENTITY Ocirc "&#212;">
<!ENTITY ocirc "&#244;">
<!ENTITY Ouml "&#214;">
<!ENTITY ouml "&#246;">
<!ENTITY Otilde "&#213;">
<!ENTITY otilde "&#245;">
<!ENTITY Oslash "&#216;">
<!ENTITY oslash "&#248;">
<!ENTITY szlig "&#223;">
<!ENTITY Thorn "&#222;">
<!ENTITY thorn "&#254;">
<!ENTITY Uacute "&#218;">
<!ENTITY uacute "&#250;">
<!ENTITY Ugrave "&#217;">
<!ENTITY ugrave "&#249;">
<!ENTITY Ucirc "&#219;">
<!ENTITY ucirc "&#251;">
<!ENTITY Uuml "&#220;">
<!ENTITY uuml "&#252;">
<!ENTITY Yacute "&#221;">
<!ENTITY yacute "&#253;">
<!ENTITY yuml "&#255;">
]>
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<?php } ?>
<head>
<?php $iWrappers['HTMLSTART'] = ob_get_clean();
ob_start("idb_suboutput_handler");
if($XHTML5===false) { ?>
<meta charset="<?php echo $Settings['charset']; ?>">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $Settings['charset']; ?>">
<?php 
if(!isset($_SERVER['HTTP_USER_AGENT'])) {
	$_SERVER['HTTP_USER_AGENT'] = ""; }
if(strpos($_SERVER['HTTP_USER_AGENT'], "msie") && 
	!strpos($_SERVER['HTTP_USER_AGENT'], "opera")){ ?>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<?php } if(strpos($_SERVER['HTTP_USER_AGENT'], "chromeframe")) { ?>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<?php } } if($XHTML5===true) { ?>
<meta charset="<?php echo $Settings['charset']; ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $Settings['charset']; ?>" />
<?php 
if(!isset($_SERVER['HTTP_USER_AGENT'])) {
	$_SERVER['HTTP_USER_AGENT'] = ""; }
if(strpos($_SERVER['HTTP_USER_AGENT'], "msie") && 
	!strpos($_SERVER['HTTP_USER_AGENT'], "opera")){ ?>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php } if(strpos($_SERVER['HTTP_USER_AGENT'], "chromeframe")) { ?>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
<?php } } $iWrappers['HTTPEQUIV'] = ob_get_clean(); 
ob_start("idb_suboutput_handler"); ?>
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
<meta name="GOOGLEBOT" content="Index, FOLLOW" />
<?php if($Settings['showverinfo']=="on") { ?>
<!-- generator="<?php echo $VerInfo['iDB_Ver_Show']; ?>" -->
<?php } if($Settings['showverinfo']!="on") { ?>
<!-- generator="<?php echo $iDB; ?>" -->
<?php } echo "\n"; $iWrappers['METATAGS'] = ob_get_clean(); 
ob_start("idb_suboutput_handler"); ?>

<script type="text/javascript" src="<?php echo url_maker($exfilejs['javascript'],$Settings['js_ext'],null,$Settings['qstr'],$Settings['qsep'],$prexqstrjs['javascript'],$exqstrjs['javascript']); ?>"></script>
<?php echo "\n"; ?>
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
<?php } $iWrappers['CSSTHEME'] = ob_get_clean();
ob_start("idb_suboutput_handler");
if($ThemeSet['FavIcon']!=null) { ?>
<link rel="icon" href="<?php echo $ThemeSet['FavIcon']; ?>" />
<link rel="shortcut icon" href="<?php echo $ThemeSet['FavIcon']; ?>" />
<?php } ?>
<!-- Renee Sabonis ^_^ -->
<?php $iWrappers['FAVICON'] = ob_get_clean(); ?>