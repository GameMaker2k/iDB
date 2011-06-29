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

    $FileInfo: function.php - Last Update: 06/29/2011 SVN 688 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="function.php"||$File3Name=="/function.php") {
	require('index.php');
	exit(); }
require_once($SettDir['misc'].'functions.php');
require_once($SettDir['misc'].'ibbcode.php');
require_once($SettDir['misc'].'iuntar.php');
/* Change Some PHP Settings Fix the & to &amp;
if($Settings['use_iniset']==true&&$Settings['qstr']!="/") {
ini_set("arg_separator.output",htmlentities($Settings['qstr'], ENT_QUOTES, $Settings['charset']));
ini_set("arg_separator.input",$Settings['qstr']);
ini_set("arg_separator",htmlentities($Settings['qstr'], ENT_QUOTES, $Settings['charset'])); }
//$basepath = pathinfo($_SERVER['REQUEST_URI']);
if(dirname($_SERVER['REQUEST_URI'])!="."||
	dirname($_SERVER['REQUEST_URI'])!=null) {
$basedir = dirname($_SERVER['REQUEST_URI'])."/"; }*/
// Get the base dir name
/*if(dirname($_SERVER['SCRIPT_NAME'])!="."||
	dirname($_SERVER['SCRIPT_NAME'])!=null) {
$basedir = dirname($_SERVER['SCRIPT_NAME'])."/"; }
if($basedir==null||$basedir==".") {
if(dirname($_SERVER['SCRIPT_NAME'])=="."||
	dirname($_SERVER['SCRIPT_NAME'])==null) {
$basedir = dirname($_SERVER['PHP_SELF'])."/"; } }
if($basedir=="\/") { $basedir="/"; }
$basedir = str_replace("//", "/", $basedir);*/
if($Settings['qstr']!="/") {
$iDBURLCHK = $Settings['idburl']; }
if($Settings['qstr']=="/") {
$iDBURLCHK = preg_replace("/\/$/","",$Settings['idburl']); }
$basecheck = parse_url($iDBURLCHK);
$basedir = $basecheck['path'];
$cbasedir = $basedir;
$rbasedir = $basedir;
if($Settings['fixbasedir']!=null&&$Settings['fixbasedir']!="off") {
		$basedir = $Settings['fixbasedir']; }
if($Settings['fixcookiedir']!=null&&$Settings['fixcookiedir']!="") {
		$cbasedir = $Settings['fixcookiedir']; }
if($Settings['fixredirectdir']!=null) {
		$rbasedir = $Settings['fixredirectdir']; }
$BaseURL = $basedir;
// Get our Host Name and Referer URL's Host Name
if(!isset($_SERVER['HTTP_REFERER'])) { $_SERVER['HTTP_REFERER'] = null; }
$REFERERurl = parse_url($_SERVER['HTTP_REFERER']);
if(!isset($REFERERurl['host'])) { $REFERERurl['host'] = null; }
$URL['REFERER'] = $REFERERurl['host'];
$URL['HOST'] = $basecheck['host'];
$REFERERurl = null;
// Function made by Howard Yeend
// http://php.net/manual/en/function.trigger-error.php#92016
// http://www.puremango.co.uk/
function output_error($message, $level=E_USER_ERROR) {
    $caller = next(debug_backtrace());
    trigger_error($message.' in <strong>'.$caller['function'].'</strong> called from <strong>'.$caller['file'].'</strong> on line <strong>'.$caller['line'].'</strong>'."\n<br />error handler", $level); }
	$Names['D'] = "Dagmara";
define("_dagmara_", $Names['D']);
// http://us.php.net/manual/en/function.uniqid.php#94959
/**
  * Generates an UUID
  * 
  * @author     Andrew Moore
  * @url        http://us.php.net/manual/en/function.uniqid.php#94959
  */
function uuid($uuidver = "v4", $rndty = "rand", $namespace = null, $name = null) {
if($uuidver!="v3"&&$uuidver!="v4"&&$uuidver!="v5") { $uuidver = "v4"; }
if($uuidver=="v4") {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      $rndty(0, 0xffff), $rndty(0, 0xffff),
      $rndty(0, 0xffff),
      $rndty(0, 0x0fff) | 0x4000,
      $rndty(0, 0x3fff) | 0x8000,
      $rndty(0, 0xffff), $rndty(0, 0xffff), $rndty(0, 0xffff) ); }
if($uuidver=="v3"||$uuidver=="v5") {
	if($namespace===null) {
	$namespace = uuid("v4",$rndty); }
    $nhex = str_replace(array('-','{','}'), '', $namespace);
    $nstr = '';
    for($i = 0; $i < strlen($nhex); $i+=2) {
      if(isset($nhex[$i+1])) {
	  $nstr .= chr(hexdec($nhex[$i].$nhex[$i+1])); }
      if(!isset($nhex[$i+1])) {
	  $nstr .= chr(hexdec($nhex[$i])); }
    }
	if($name===null) { $name = salt_hmac(); }
    // Calculate hash value
	if($uuidver=="v3") {
	$uuidverid = 0x3000;
	if (function_exists('hash')) {
	$hash = hash("md5", $nstr . $name); }
	if (!function_exists('hash')) {
	$hash = md5($nstr . $name); } }
	if($uuidver=="v5") {
	$uuidverid = 0x5000;
	if (function_exists('hash')) {
	$hash = hash("sha1", $nstr . $name); }
	if (!function_exists('hash')) {
	$hash = sha1($nstr . $name); } }
    return sprintf('%08s-%04s-%04x-%04x-%12s',
      substr($hash, 0, 8),
      substr($hash, 8, 4),
      (hexdec(substr($hash, 12, 4)) & 0x0fff) | $uuidverid,
      (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
      substr($hash, 20, 12) ); } }
function rand_uuid($rndty = "rand", $namespace = null, $name = null) {
$rand_array = array(1 => "v3", 2 => "v4", 3 => "v5");
if($name===null) { $name = salt_hmac(); }
$my_uuid = $rand_array[$rndty(1,3)];
if($my_uuid=="v4") { return uuid("v4",$rndty); }
if($my_uuid=="v3"||$my_uuid=="v5") {
return uuid($my_uuid,$rndty,$name); } }
// unserialize sessions variables
function unserialize_session($data) {
    $vars=preg_split('/([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff^|]*)\|/',
              $data,-1,PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    $i = 0;
    for($i=0; isset($vars[$i]); $i++) {
	$l = $i + 1;
	if(!isset($vars[$i])) { $vars[$i] = null; }
	if(!isset($vars[$l])) { $vars[$l] = null; }
	$result[$vars[$i]]=unserialize($vars[$l]);
	$i++; }
	if(!isset($result)) { $result = null; }
    return $result;
}
// Make the Query String if we are not useing &=
function qstring($qstr=";",$qsep="=")
{ $_GET = array(); $_GET = null;
if (!isset($_SERVER['QUERY_STRING'])) {
$_SERVER['QUERY_STRING'] = getenv('QUERY_STRING'); }
ini_set("arg_separator.input", $qstr);
$_SERVER['QUERY_STRING'] = urldecode($_SERVER['QUERY_STRING']);
$preqs = explode($qstr,$_SERVER["QUERY_STRING"]);
$qsnum = count($preqs); $qsi = 0;
while ($qsi < $qsnum) {
$preqst = explode($qsep,$preqs[$qsi],2);
$fix1 = array(" ",'$'); $fix2  = array("_","_");
$preqst[0] = str_replace($fix1, $fix2, $preqst[0]);
$preqst[0] = killbadvars($preqst[0]);
if($preqst[0]!=null) {
$_GET[$preqst[0]] = $preqst[1]; }
++$qsi; } return true; }
if($Settings['qstr']!="&"&&
	$Settings['qstr']!="/") {
qstring($Settings['qstr'],$Settings['qsep']); 
if(!isset($_GET['page'])) { $_GET['page'] = null; }
if(!isset($_GET['act'])) { $_GET['act'] = null; }
if(!isset($_POST['act'])) { $_POST['act'] = null; }
if(!isset($_GET['id'])) { $_GET['id'] = null; } 
if(!isset($_GET['debug'])) { $_GET['debug'] = "false"; }
if(!isset($_GET['post'])) { $_GET['post'] = null; }
if(!isset($_POST['License'])) { $_POST['License'] = null; } }
if($_SERVER['PATH_INFO']==null) {
	if(getenv('PATH_INFO')!=null&&getenv('PATH_INFO')!="1") {
$_SERVER['PATH_INFO'] = getenv('PATH_INFO'); }
if(getenv('PATH_INFO')==null) {
$myscript = $_SERVER["SCRIPT_NAME"];
$myphpath = $_SERVER["PHP_SELF"];
$mypathinfo = str_replace($myscript, "", $myphpath);
@putenv("PATH_INFO=".$mypathinfo); } }
// Change raw post data to POST array
// Not sure why I made but alwell. :P 
function parse_post_data()
{ $_POST = array(); $_POST = null;
$postdata = file_get_contents("php://input");
if (!isset($postdata)) { $postdata = null; }
$postdata = urldecode($postdata);
$preqs = explode("&",$postdata);
$qsnum = count($preqs); $qsi = 0;
while ($qsi < $qsnum) {
$preqst = explode("=",$preqs[$qsi],2);
$fix1 = array(" ",'$'); $fix2  = array("_","_");
$preqst[0] = str_replace($fix1, $fix2, $preqst[0]);
$preqst[0] = killbadvars($preqst[0]);
if($preqst[0]!=null) {
$_POST[$preqst[0]] = $preqst[1]; }
++$qsi; } return true; }
// Change Path info to Get Vars :
function mrstring() {
$urlvar = explode('/',$_SERVER['PATH_INFO']);
$num=count($urlvar); $i=1;
while ($i < $num) {
//$urlvar[$i] = urldecode($urlvar[$i]);
if(!isset($_GET[$urlvar[$i]])) { $_GET[$urlvar[$i]] = null; }
if(!isset($urlvar[$i])) { $urlvar[$i] = null; }
if($_GET[$urlvar[$i]]==null&&$urlvar[$i]!=null) {
$fix1 = array(" ",'$'); $fix2  = array("_","_");
$urlvar[$i] = str_replace($fix1, $fix2, $urlvar[$i]);
$urlvar[$i] = killbadvars($urlvar[$i]);
	$_GET[$urlvar[$i]] = $urlvar[$i+1]; }
++$i; ++$i; } return true; }
// Redirect to another file with ether timed or nontimed redirect
function redirect($type,$file,$time=0,$url=null,$dbsr=true) {
if($type!="location"&&$type!="refresh") { $type=="location"; }
if($url!=null) { $file = $url.$file; }
if($dbsr===true) { $file = str_replace("//", "/", $file); }
if($type=="refresh") { header("Refresh: ".$time."; URL=".$file); }
if($type=="location") { session_write_close(); 
header("Location: ".$file); } return true; }
function redirects($type,$url,$time=0) {
if($type!="location"&&$type!="refresh") { $type=="location"; }
if($type=="refresh") { header("Refresh: ".$time."; URL=".$url); }
if($type=="location") { idb_log_maker(302,"-"); }
if($type=="location") { header("Location: ".$url); } return true; }
// Make xhtml tags
function html_tag_make($name="br",$emptytag=true,$attbvar=null,$attbval=null,$extratest=null) {
	$var_num = count($attbvar); $value_num = count($attbval);
	if($var_num!=$value_num) { 
		output_error("Erorr Number of Var and Values dont match!",E_USER_ERROR);
	return false; } $i = 0;
	while ($i < $var_num) {
	if($i==0) { $mytag = "<".$name." ".$attbvar[$i]."=\"".$attbval[$i]."\""; }
	if($i>=1) { $mytag = $mytag." ".$attbvar[$i]."=\"".$attbval[$i]."\""; }
	if($i==$var_num-1) { 
	if($emptytag===false) { $mytag = $mytag.">"; }
	if($emptytag===true) { $mytag = $mytag." />"; } }	++$i; }
	if($attbvar==null&&$attbval==null) { $mytag = "<".$name;
	if($emptytag===true) { $mytag = $mytag." />"; }
	if($emptytag===false) { $mytag = $mytag.">"; } }
	if($emptytag===false&&$extratest!=null) { 
	$mytag = $mytag.$extratest; $mytag = $mytag."</".$name.">"; } 
	return $mytag; }
// Start a xml document
function xml_tag_make($type,$attbs,$retval=false) {
	$renee1 = explode("&",$attbs);
	$reneenum=count($renee1);
	$reneei=0; $attblist = null;
	while ($reneei < $reneenum) {
	$renee2 = explode("=",$renee1[$reneei]);
	if($renee2[0]!=null||$renee2[1]!=null) {
	$attblist = $attblist.' '.$renee2[0].'="'.$renee2[1].'"'; }
	++$reneei; }
	if($retval!==false&&$retval!==true) { $retval=false; }
	if($retval===false) {
	echo '<?'.$type.$attblist.'?>'."\n"; }
	if($retval===true) {
	return '<?'.$type.$attblist.'?>'."\n"; } }
// Start a xml document (old version)
function xml_doc_start($ver,$encode,$retval=false) {
	if($retval===false) {
	echo xml_tag_make('xml','version='.$ver.'&encoding='.$encode,true); }
	if($retval===true) {
	return xml_tag_make('xml','version='.$ver.'&encoding='.$encode,true); } }
$icharset = $Settings['charset'];
$debug_on = false;
if(isset($_GET['debug'])) {
if($_GET['debug']=="true"||
	$_GET['debug']=="on") {
$debug_on = true; } }
$BoardURL = $Settings['idburl'];
// Change URLs to Links
function pre_url2link($matches) {
global $BoardURL; $opennew = true;
$burlCHCK = parse_url($BoardURL);
$urlCHCK = parse_url($matches[0]);
if($urlCHCK['host']==$burlCHCK['host']) {
	$opennew = false; }
$outurl = $urlCHCK['scheme']."://";
if(isset($urlCHCK['user'])) {
$outurl = $outurl.$urlCHCK['user'];
if(isset($urlCHCK['pass'])) {
$outurl = $outurl.":".$urlCHCK['pass']; }
$outurl = $outurl."@"; }
$outurl = $outurl.$urlCHCK['host'];
if(isset($urlCHCK['path'])) {
$outurl = $outurl.$urlCHCK['path']; }
if(!isset($urlCHCK['path'])) {
$outurl = $outurl."/"; }
if(isset($urlCHCK['query'])) {
$urlCHCK['query'] = str_replace(" ", "+", $urlCHCK['query']);
$outurl = $outurl."?".$urlCHCK['query']; }
if(isset($urlCHCK['fragment'])) {
$urlCHCK['fragment'] = str_replace(" ", "+", $urlCHCK['fragment']);
$outurl = $outurl."#".$urlCHCK['fragment']; }
if($opennew===true) {
$outlink = "<a onclick=\"window.open(this.href); return false;\" href=\"".$outurl."\">".$outurl."</a>"; }
if($opennew===false) {
$outlink = "<a href=\"".$outurl."\">".$outurl."</a>"; }
return $outlink; }
function url2link($string) {
return preg_replace_callback("/(?<![\">])\b([a-zA-Z]+)\:\/\/([a-z0-9\-\.]+)(\:[0-9]+)?\/([A-Za-z0-9\.\/%\?\-_\:;\~]+)?(\?)?([A-Za-z0-9\.\/%&=\?\-_\:;\+]+)?(\#)?([A-Za-z0-9\.\/%&=\?\-_\:;\+]+)?/is", "pre_url2link", $string); }
function urlcheck($string) {
global $BoardURL;
$retnum = preg_match_all("/([a-zA-Z]+)\:\/\/([a-z0-9\-\.]+)(\:[0-9]+)?\/([A-Za-z0-9\.\/%\?\-_\:;\~]+)?(\?)?([A-Za-z0-9\.\/%&=\?\-_\:;\+]+)?(\#)?([A-Za-z0-9\.\/%&=\?\-_\:;\+]+)?/is", $string, $urlcheck); 
if(isset($urlcheck[0][0])) { $url = $urlcheck[0][0]; }
if(!isset($urlcheck[0][0])) { $url = $BoardURL; }
return $url; }
//Check to make sure theme exists
$BoardTheme = $Settings['DefaultTheme'];
$ThemeDir = $SettDir['themes'];
function chack_themes($theme) {
global $BoardTheme,$ThemeDir;
if(!isset($theme)) { $theme = null; }
if(preg_match("/([a-zA-Z]+)\:/isU",$theme)) {
	$theme = $BoardTheme; }
if(!preg_match("/^[a-z0-9]+$/isU",$theme)) {
	$theme = $BoardTheme; }
require('settings.php');
$ckskindir = dirname(realpath("settings.php"))."/".$ThemeDir;
if ($handle = opendir($ckskindir)) { $dirnum = null;
   while (false !== ($ckfile = readdir($handle))) {
	   if ($dirnum==null) { $dirnum = 0; }
	   if (file_exists($ckskindir.$ckfile."/info.php")) {
		   if ($ckfile != "." && $ckfile != "..") {
	   //include($ckskindir.$ckfile."/info.php");
       $cktheme[$dirnum] =  $ckfile;
	   ++$dirnum; } } }
   closedir($handle); asort($cktheme); }
$theme=preg_replace("/(.*?)\.\/(.*?)/", $BoardTheme, $theme);
if(!in_array($theme,$cktheme)||strlen($theme)>26) {
	$theme = $BoardTheme; } return $theme; }
// Make a url with query string
function url_maker($file="index",$ext=".php",$qvarstr=null,$qstr=";",$qsep="=",$prexqstr=null,$exqstr=null,$fixhtml=true) {
global $sidurls, $icharset, $debug_on;
$fileurl = null; if(!isset($ext)) { $ext = null; }
if($ext==null) { $ext = ".php"; } 
if($ext=="noext"||$ext=="no ext"||$ext=="no+ext") { $ext = null; }
$file = $file.$ext;
if($sidurls=="on"&&$qstr!="/") { 
	if(defined('SID')) {
if($qvarstr==null) { $qvarstr = SID; }
if($qvarstr!=null) { $qvarstr = SID."&".$qvarstr; } } }
if($debug_on===true) {
if($qvarstr==null) { $qvarstr = "debug=on"; }
if($qvarstr!=null) { $qvarstr = $qvarstr."&debug=on"; } }
if($qvarstr==null) { $fileurl = $file; }
if($fixhtml===true) {
$qstr = htmlentities($qstr, ENT_QUOTES, $icharset);
$qsep = htmlentities($qsep, ENT_QUOTES, $icharset); }
if($prexqstr!=null) { 
$rene1 = explode("&",$prexqstr);
$renenum=count($rene1);
$renei=0;
$reneqstr = "index.php?";
if($qstr!="/") { $fileurl = $file."?"; }
if($qstr=="/") { $fileurl = $file."/"; }
while ($renei < $renenum) {
	$rene2 = explode("=",$rene1[$renei]);
	if(!isset($rene2[0])) { $rene2[0] = null; }
	$rene2[1] = urlencode($rene2[1]);
	if(!isset($rene2[0])) { $rene2[0] = null; }
	$rene2[1] = urlencode($rene2[1]);
	if($qstr!="/") {
	$fileurl = $fileurl.$rene2[0].$qsep.$rene2[1]; }
	if($qstr=="/") {
	$fileurl = $fileurl.$rene2[0]."/".$rene2[1]."/"; }
	$reneis = $renei + 1;
	if($qstr!="/") {
	if($reneis < $renenum) { $fileurl = $fileurl.$qstr; } }
	++$renei; } }
if($qvarstr!=null&&$qstr!="/") { $fileurl = $fileurl.$qstr; }
if($qvarstr!=null) { 
if($prexqstr==null) {
if($qstr!="/") { $fileurl = $file."?"; }
if($qstr=="/") { $fileurl = $file."/"; } }
$cind1 = explode("&",$qvarstr);
$cindnum=count($cind1);
$cindi=0;
$cindqstr = "index.php?";
while ($cindi < $cindnum) {
	$cind2 = explode("=",$cind1[$cindi]);
	if(!isset($cind2[0])) { $cind2[0] = null; }
	$cind2[0] = urlencode($cind2[0]);
	if(!isset($cind2[1])) { $cind2[1] = null; }
	$cind2[1] = urlencode($cind2[1]);
	if($qstr!="/") {
	$fileurl = $fileurl.$cind2[0].$qsep.$cind2[1]; }
	if($qstr=="/") {
	$fileurl = $fileurl.$cind2[0]."/".$cind2[1]."/"; }
	$cindis = $cindi + 1;
	if($qstr!="/") {
	if($cindis < $cindnum) { $fileurl = $fileurl.$qstr; } }
	++$cindi; } }
if($exqstr!=null&&$qstr!="/") { $fileurl = $fileurl.$qstr; }
if($exqstr!=null) { 
if($qvarstr==null&&$prexqstr==null) {
if($qstr!="/") { $fileurl = $file."?"; }
if($qstr=="/") { $fileurl = $file."/"; } }
$sand1 = explode("&",$exqstr);
$sanum=count($sand1);
$sandi=0;
$sandqstr = "index.php?";
while ($sandi < $sanum) {
	$sand2 = explode("=",$sand1[$sandi]);
	if(!isset($sand2[0])) { $sand2[0] = null; }
	$sand2[0] = urlencode($sand2[0]);
	if(!isset($sand2[1])) { $sand2[1] = null; }
	$sand2[1] = urlencode($sand2[1]);
	if($qstr!="/") {
	$fileurl = $fileurl.$sand2[0].$qsep.$sand2[1]; }
	if($qstr=="/") {
	$fileurl = $fileurl.$sand2[0]."/".$sand2[1]."/"; }
	$sandis = $sandi + 1;
	if($qstr!="/") {
	if($sandis < $sanum) { $fileurl = $fileurl.$qstr; } }
	++$sandi; } }
return $fileurl; }
$thisdir = dirname(realpath("Preindex.php"))."/";
// Get the Query String
function GetQueryStr($qstr=";",$qsep="=",$fixhtml=true)
{ $pregqstr = preg_quote($qstr,"/");
$pregqsep = preg_quote($qsep,"/");
$oqstr = $qstr; $oqsep = $qsep;
if($fixhtml===true||$fixhtml==null) {
$qstr = htmlentities($qstr, ENT_QUOTES, $icharset);
$qsep = htmlentities($qsep, ENT_QUOTES, $icharset); }
$OldBoardQuery = preg_replace("/".$pregqstr."/isxS", $qstr, $_SERVER['QUERY_STRING']);
$BoardQuery = "?".$OldBoardQuery;
return $BoardQuery; }
function get_server_values($matches) {
	$return_text = "-";
	if(isset($_SERVER[$matches[1]])) { $return_text = $_SERVER[$matches[1]]; }
	if(!isset($_SERVER[$matches[1]])) { $return_text = "-"; }
	return $return_text; }
function get_cookie_values($matches) {
	$return_text = null;
	if(isset($_COOKIE[$matches[1]])) { $return_text = $_COOKIE[$matches[1]]; }
	if(!isset($_COOKIE[$matches[1]])) { $return_text = null; }
	return $return_text; }
function get_env_values($matches) {
	$return_text = getenv($matches[1]);
	if(!isset($return_text)) { $return_text = "-"; }
	return $return_text; }
function get_setting_values($matches) {
	global $Settings;
	$return_text = null;
	$matches[1] = str_replace("sqlpass", "sqluser", $matches[1]);
	if(isset($Settings[$matches[1]])) { $return_text = $Settings[$matches[1]]; }
	if(!isset($Settings[$matches[1]])) { $return_text = null; }
	return $return_text; }
function get_time($matches) {
	return date(convert_strftime($matches[1])); }
function convert_strftime($strftime) {
$strftime = str_replace("%%", "{percent\}p", $strftime);
$strftime = str_replace("%a", "D", $strftime);
$strftime = str_replace("%A", "l", $strftime);
$strftime = str_replace("%d", "d", $strftime);
$strftime = str_replace("%e", "j", $strftime);
$strftime = str_replace("%j", "z", $strftime);
$strftime = str_replace("%u", "w", $strftime);
$strftime = str_replace("%w", "w", $strftime);
$strftime = str_replace("%U", "W", $strftime);
$strftime = str_replace("%V", "W", $strftime);
$strftime = str_replace("%W", "W", $strftime);
$strftime = str_replace("%b", "M", $strftime);
$strftime = str_replace("%B", "F", $strftime);
$strftime = str_replace("%h", "M", $strftime);
$strftime = str_replace("%m", "m", $strftime);
$strftime = str_replace("%g", "y", $strftime);
$strftime = str_replace("%G", "Y", $strftime);
$strftime = str_replace("%y", "y", $strftime);
$strftime = str_replace("%Y", "Y", $strftime);
$strftime = str_replace("%H", "H", $strftime);
$strftime = str_replace("%I", "h", $strftime);
$strftime = str_replace("%l", "g", $strftime);
$strftime = str_replace("%M", "i", $strftime);
$strftime = str_replace("%p", "A", $strftime);
$strftime = str_replace("%P", "a", $strftime);
$strftime = str_replace("%r", "h:i:s A", $strftime);
$strftime = str_replace("%R", "H:i", $strftime);
$strftime = str_replace("%S", "s", $strftime);
$strftime = str_replace("%T", "H:i:s", $strftime);
$strftime = str_replace("%X", "H:i:s", $strftime);
$strftime = str_replace("%z", "O", $strftime);
$strftime = str_replace("%Z", "O", $strftime);
$strftime = str_replace("%c", "D M j H:i:s Y", $strftime);
$strftime = str_replace("%D", "m/d/y", $strftime);
$strftime = str_replace("%F", "Y-m-d", $strftime);
$strftime = str_replace("%x", "m/d/y", $strftime);
$strftime = str_replace("%n", "\n", $strftime);
$strftime = str_replace("%t", "\t", $strftime);
$logtxt = preg_replace("/\{percent\}p/s", "%", $logtxt);
return $strftime; }
function apache_log_maker($logtxt,$logfile=null,$status=200,$contentsize="-",$headersize=0) {
global $Settings;
if(!isset($_SERVER['HTTP_REFERER'])) { $URL_REFERER = "-"; }
if(isset($_SERVER['HTTP_REFERER'])) { $URL_REFERER = $_SERVER['HTTP_REFERER']; }
if(!isset($_SERVER['PHP_AUTH_USER'])) { $AUTH_USER = "-"; }
if(isset($_SERVER['PHP_AUTH_USER'])) { $AUTH_USER = $_SERVER['PHP_AUTH_USER']; }
$LOG_QUERY_STRING = "";
if($_SERVER["QUERY_STRING"]!=="") {
$LOG_QUERY_STRING = "?".$_SERVER["QUERY_STRING"]; }
$oldcontentsize = $contentsize;
if($oldcontentsize=="-") { $oldcontentsize = 0; }
if($contentsize===0) { $contentsize = "-"; }
if($contentsize=="-"&&$headersize!==0) { $fullsitesize = $headersize; }
if($contentsize!="-"&&$headersize!==0) { $fullsitesize = $contentsize + $headersize; }
$HTTP_REQUEST_LINE = $_SERVER["REQUEST_METHOD"]." ".$_SERVER["REQUEST_URI"]." ".$_SERVER["SERVER_PROTOCOL"];
$logtxt = preg_replace("/%%/s", "{percent}p", $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)a/s", $_SERVER['REMOTE_ADDR'], $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)A/s", $_SERVER["SERVER_ADDR"], $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)B/s", $oldcontentsize, $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)b/s", $contentsize, $logtxt);
$logtxt = preg_replace_callback("/%([\<\>]*?)\{([^\}]*)\}C/s", "get_env_values", $logtxt);
$logtxt = preg_replace_callback("/%([\<\>]*?)\{([^\}]*)\}e/s", "get_env_values", $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)f/s", $_SERVER["SCRIPT_FILENAME"], $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)h/s", $_SERVER['REMOTE_ADDR'], $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)H/s", $_SERVER["SERVER_PROTOCOL"], $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)\{Referer\}i/s", $URL_REFERER, $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)\{User-Agent\}i/s", $_SERVER["HTTP_USER_AGENT"], $logtxt);
$logtxt = preg_replace_callback("/%([\<\>]*?)\{([^\}]*)\}i/s", "get_server_values", $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)l/s", "-", $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)m/s", $_SERVER["REQUEST_METHOD"], $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)p/s", $_SERVER["SERVER_PORT"], $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)q/s", $LOG_QUERY_STRING, $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)r/s", $HTTP_REQUEST_LINE, $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)s/s", $status, $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)t/s", "[".date("d/M/Y:H:i:s O")."]", $logtxt);
$logtxt = preg_replace_callback("/%([\<\>]*?)\{([^\}]*)\}t/s", "get_time", $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)u/s", $AUTH_USER, $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)U/s", $_SERVER["PHP_SELF"], $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)v/s", $_SERVER["SERVER_NAME"], $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)V/s", $_SERVER["SERVER_NAME"], $logtxt);
// Not what it should be but PHP dose not have variable to get Apache ServerName config value. :( 
$logtxt = preg_replace("/%([\<\>]*?)O/s", $fullsitesize, $logtxt);
$logtxt = preg_replace_callback("/%([\<\>]*?)\{([^\}]*)\}s/s", "get_setting_values", $logtxt);
$logtxt = preg_replace("/\{percent\}p/s", "%", $logtxt);
if(isset($logfile)&&$logfile!==null) {
	$fp = fopen($logfile, "a+");
	$logtxtnew = $logtxt."\r\n";
	fwrite($fp, $logtxtnew, strlen($logtxtnew));
	fclose($fp); }
return $logtxt; }
function idb_log_maker($status=200,$contentsize="-") {
global $Settings,$SettDir;
if(!isset($Settings['log_http_request'])) {
	$Settings['log_http_request'] = "off"; }
if(!isset($Settings['log_config_format'])) {
	$Settings['log_config_format'] = "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\""; }
if(isset($Settings['log_http_request'])&&$Settings['log_http_request']=="on"&&
	$Settings['log_http_request']!==null&&$Settings['log_http_request']!="off") {
return apache_log_maker($Settings['log_config_format'], $SettDir['logs'].$Settings['sqltable'].date("YW").".log", $status, $contentsize, strlen(implode("\r\n",headers_list())."\r\n\r\n")); }
if(isset($Settings['log_http_request'])&&$Settings['log_http_request']!="on"&&
	$Settings['log_http_request']!==null&&$Settings['log_http_request']!="off") {
$Settings['log_http_request'] = preg_replace_callback("/".preg_quote("%{", "/")."([^\}]*)".preg_quote("}t", "/")."/s", "get_time", $Settings['log_http_request']);
$Settings['log_http_request'] = preg_replace_callback("/".preg_quote("%{", "/")."([^\}]*)".preg_quote("}s", "/")."/s", "get_setting_values", $Settings['log_http_request']);
return apache_log_maker($Settings['log_config_format'], $SettDir['logs'].$Settings['log_http_request'], $status, $contentsize, strlen(implode("\r\n",headers_list())."\r\n\r\n")); } }
?>
