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

    $FileInfo: xhtml10.php - Last Update: 05/26/2007 SVN 15 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="xhtml10.php"||$File3Name=="/xhtml10.php") {
	require('index.php');
	exit(); }
if($Settings['output_type']=="html") {
	$ccstart = "//<!--"; $ccend = "//-->";
@header("Content-Type: text/html; charset=iso-8859-15"); }
if($Settings['output_type']=="xhtml") {
if(stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml")) {
	$ccstart = "//<![CDATA["; $ccend = "//]]>";
	@header("Content-Type: application/xhtml+xml; charset=iso-8859-15");
	xml_doc_start("1.0","iso-8859-15"); }
else { if (stristr($_SERVER["HTTP_USER_AGENT"],"W3C_Validator")) {
	$ccstart = "//<![CDATA["; $ccend = "//]]>";
   @header("Content-Type: application/xhtml+xml; charset=iso-8859-15");
	xml_doc_start("1.0","iso-8859-15");
} else { $ccstart = "//<!--"; $ccend = "//-->";
	@header("Content-Type: text/html; charset=iso-8859-15"); } } }
if($Settings['output_type']!="xhtml") {
	if($Settings['output_type']!="html") {
		$ccstart = "//<!--"; $ccend = "//-->";
@header("Content-Type: text/html; charset=iso-8859-15"); } }
@header("Content-Script-Type: text/javascript");
if(isset($Settings['showverinfo'])) { $idbmisc['showverinfo'] = $Settings['showverinfo']; }
if(!isset($Settings['showverinfo'])) { $idbmisc['showverinfo'] = false; }
if($Settings['showverinfo']!=true) {
$iDBURL1 = "<a href=\"http://intdb.sourceforge.net/\" title=\"".$iDB."\" onclick=\"window.open(this.href);return false;\">"; }
if($Settings['showverinfo']==true) {
$iDBURL1 = "<a href=\"http://intdb.sourceforge.net/\" title=\"".$VerInfo['iDB_Ver_Show']."\" onclick=\"window.open(this.href);return false;\">"; }
$GM2kURL = "<a href=\"http://upload.idb.s1.jcink.com/\" title=\"".$GM2k."\" onclick=\"window.open(this.href);return false;\">".$GM2k."</a>";
$csryear = "2004"; $cryear = date("Y"); if($cryear<=2004) { $cryear = "2005"; }
$endpagevar = "<div class=\"copyright\">Powered by ".$iDBURL1."iDB</a> &copy; ".$GM2kURL." @ ".$csryear." - ".$cryear." <a href=\"".url_maker($exfile['index'],$Settings['file_ext'],"act=bsd",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'])."\" title=\"iDB is licensed under the Revised BSD License\">BSDL</a> <br />\n".$ThemeSet['CopyRight'];
@header("Content-Language: en");
@header("Vary: Accept");
if($_SERVER['HTTPS']=="on") { $prehost = "https://"; }
if($_SERVER['HTTPS']!="on") { $prehost = "http://"; }
if($Settings['idburl']=="localhost"||$Settings['idburl']==null) {
	$BoardURL = $prehost.$_SERVER["HTTP_HOST"].$basedir; }
if($Settings['idburl']!="localhost"&&$Settings['idburl']!=null) {
	$BoardURL = $Settings['idburl']; }
if($Settings['html_level']!="Strict") {
	if($Settings['html_level']!="Transitional") {
		$Settings['html_level'] = "Transitional"; } }
?>
<?php if($Settings['html_level']=="Strict") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php } if($Settings['html_level']=="Transitional") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php } if($Settings['html_level']=="Frameset") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<?php } ?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Language" content="en" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $Settings['charset']; ?>" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<base href="<?php echo $BoardURL; ?>" />
<?php if($Settings['showverinfo']==true) { ?>
<meta name="Generator" content="<?php echo $VerInfo['iDB_Ver_Show']; ?>" />
<?php } if($Settings['showverinfo']!=true) { ?>
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
<?php if($Settings['showverinfo']==true) { ?>
<!-- generator="<?php echo $VerInfo['iDB_Ver_Show']; ?>" -->
<?php } if($Settings['showverinfo']!=true) { ?>
<!-- generator="<?php echo $iDB; ?>" -->
<?php } echo "\n"; ?>

<script type="text/javascript" src="<?php echo url_maker($exfilejs['javascript'],$Settings['js_ext'],null,$Settings['qstr'],$Settings['qsep'],$prexqstrjs['javascript'],$exqstrjs['javascript']); ?>"></script>
<?php if($ThemeSet['CSSType']!="import"&&$ThemeSet['CSSType']!="link") { 
$ThemeSet['CSSType'] = "import"; } if($ThemeSet['CSSType']=="import") { ?>
<style type="text/css"><?php echo "\n@import url(\"".$ThemeSet['CSS']."\");\n"; ?></style>
<?php } if($ThemeSet['CSSType']=="link") { ?>
<link rel="prefetch alternate stylesheet" href="<?php echo $BoardURL.$ThemeSet['CSS']; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $BoardURL.$ThemeSet['CSS']; ?>" />
<?php } if($ThemeSet['FavIcon']!=null) { ?>
<link rel="icon" href="<?php echo $ThemeSet['FavIcon']; ?>" />
<link rel="shortcut icon" href="<?php echo $ThemeSet['FavIcon']; ?>" />
<?php } ?>
