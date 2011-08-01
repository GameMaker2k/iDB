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

    $FileInfo: sql.php - Last Update: 07/31/2011 SVN 733 - Author: cooldude2k $
*/
/* Some ini setting changes uncomment if you need them. 
   Display PHP Errors */
$disfunc = @ini_get("disable_functions");
$disfunc = @trim($disfunc);
$disfunc = @preg_replace("/([\\s+|\\t+|\\n+|\\r+|\\0+|\\x0B+])/i", "", $disfunc);
if($disfunc!="ini_set") { $disfunc = explode(",",$disfunc); }
if($disfunc=="ini_set") { $disfunc = array("ini_set"); }
if(!in_array("ini_set", $disfunc)) {
@ini_set("html_errors", false);
@ini_set("track_errors", false);
@ini_set("display_errors", false);
@ini_set("report_memleaks", false);
@ini_set("display_startup_errors", false);
//@ini_set("error_log","logs/error.log"); 
//@ini_set("log_errors","On"); 
@ini_set("docref_ext", "");
@ini_set("docref_root", "http://php.net/"); }
@error_reporting(E_ALL ^ E_NOTICE);
/* Get rid of session id in urls */
if(!in_array("ini_set", $disfunc)) {
@ini_set("date.timezone","UTC"); 
@ini_set("default_mimetype","text/html"); 
@ini_set("zlib.output_compression", false);
@ini_set("zlib.output_compression_level", -1);
@ini_set("session.use_trans_sid", false);
@ini_set("session.use_cookies", true);
@ini_set("session.use_only_cookies", true);
@ini_set("url_rewriter.tags",""); 
@ini_set('zend.ze1_compatibility_mode', 0);
@ini_set("ignore_user_abort", 1); }
@set_time_limit(30); @ignore_user_abort(true);
/* Change session garbage collection settings */
if(!in_array("ini_set", $disfunc)) {
@ini_set("session.gc_probability", 1);
@ini_set("session.gc_divisor", 100);
@ini_set("session.gc_maxlifetime", 1440);
/* Change session hash type here */
@ini_set("session.hash_function", 1);
@ini_set("session.hash_bits_per_character", 6); }
/* Do not change anything below this line unless you know what you are doing */
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="sql.php"||$File3Name=="/sql.php") {
	header('Location: index.php');
	exit(); }
if(file_exists('settings.php')) {
	require_once('settings.php'); 
	if(file_exists('extrasettings.php')) {
		require_once('extrasettings.php'); }
	if(file_exists('extendsettings.php')) {
		require_once('extendsettings.php'); }
if(!in_array("ini_set", $disfunc)&&$Settings['qstr']!="/"&&$Settings['qstr']!="&") {
ini_set("arg_separator.output",htmlentities($Settings['qstr'], ENT_QUOTES, $Settings['charset']));
ini_set("arg_separator.input",$Settings['qstr']); } }
if(!isset($Settings['idburl'])) { $Settings['idburl'] = null; }
if(!isset($Settings['fixbasedir'])) { $Settings['fixbasedir'] = null; }
if(!isset($Settings['fixpathinfo'])) { $Settings['fixpathinfo'] = null; }
if(!isset($Settings['fixcookiedir'])) { $Settings['fixcookiedir'] = null; }
if(!isset($Settings['fixredirectdir'])) { $Settings['fixcookiedir'] = null; }
$Settings['bid'] = base64_encode(urlencode($Settings['idburl']));
if(!isset($Settings['idb_time_format'])) { $Settings['idb_time_format'] = "g:i A"; }
if(!isset($Settings['showverinfo'])) { 
	$Settings['showverinfo'] = "on"; }
if(!isset($Settings['sqldb'])) {
header("Content-Type: text/plain; charset=UTF-8");
header('Location: install.php'); }
if(!isset($Settings['fixpathinfo'])) {
	$Settings['fixpathinfo'] = "off"; }
if($Settings['fixpathinfo']=="off") {
	$Settings['fixpathinfo'] = null; }
if(!isset($Settings['fixbasedir'])) {
	$Settings['fixbasedir'] = "off"; }
if($Settings['fixbasedir']=="off") {
	$Settings['fixbasedir'] = null; }
if(!isset($Settings['fixcookiedir'])) {
	$Settings['fixcookiedir'] = "off"; }
if($Settings['fixcookiedir']=="off") {
	$Settings['fixcookiedir'] = null; }
if(!isset($Settings['fixredirectdir'])) {
	$Settings['fixredirectdir'] = "off"; }
if($Settings['fixredirectdir']=="off") {
	$Settings['fixredirectdir'] = null; }
$OldSettings['fixpathinfo'] = $Settings['fixpathinfo'];
$OldSettings['fixbasedir'] = $Settings['fixbasedir'];
$OldSettings['fixcookiedir'] = $Settings['fixcookiedir'];
$OldSettings['fixredirectdir'] = $Settings['fixredirectdir'];
if($Settings['idburl']=="localhost") { 
header("Content-Type: text/plain; charset=UTF-8");
echo "500 Error: URL is malformed. Try reinstalling iDB."; die(); }
if($Settings['fixbasedir']=="on") {
if($Settings['idburl']!=null&&$Settings['idburl']!="localhost") {
$PathsTest = parse_url($Settings['idburl']);
$Settings['fixbasedir'] = $PathsTest['path']."/"; 
$Settings['fixbasedir'] = str_replace("//", "/", $Settings['fixbasedir']); } }
if($Settings['fixcookiedir']=="on") {
if($Settings['idburl']!=null&&$Settings['idburl']!="localhost") {
$PathsTest = parse_url($Settings['idburl']);
$Settings['fixcookiedir'] = $PathsTest['path']."/"; 
$Settings['fixcookiedir'] = str_replace("//", "/", $Settings['fixcookiedir']); } }
if($Settings['fixredirectdir']=="on") {
if($Settings['idburl']!=null&&$Settings['idburl']!="localhost") {
$PathsTest = parse_url($Settings['idburl']);
$Settings['fixredirectdir'] = $PathsTest['path']."/"; 
$Settings['fixredirectdir'] = str_replace("//", "/", $Settings['fixredirectdir']); } }
if(!isset($Settings['charset'])) {
	$Settings['charset'] = "ISO-8859-15"; }
if(isset($Settings['charset'])) {
if($Settings['charset']!="ISO-8859-15"&&$Settings['charset']!="ISO-8859-1"&&
	$Settings['charset']!="UTF-8"&&$Settings['charset']!="CP866"&&
	$Settings['charset']!="Windows-1251"&&$Settings['charset']!="Windows-1252"&&
	$Settings['charset']!="KOI8-R"&&$Settings['charset']!="BIG5"&&
	$Settings['charset']!="GB2312"&&$Settings['charset']!="BIG5-HKSCS"&&
	$Settings['charset']!="Shift_JIS"&&$Settings['charset']!="EUC-JP") {
	$Settings['charset'] = "ISO-8859-15"; } }
	$chkcharset = $Settings['charset'];
if(!in_array("ini_set", $disfunc)) {
@ini_set('default_charset', $Settings['charset']); }
//session_save_path($SettDir['inc']."temp/");
if(!isset($Settings['sqldb'])) { 
if(file_exists("install.php")) { header('Location: install.php'); die(); } 
if(!file_exists("install.php")) { header("Content-Type: text/plain; charset=UTF-8");
echo "403 Error: Sorry could not find install.php\nTry uploading files again and if that dose not work try download iDB again."; die(); } }
if(isset($Settings['sqldb'])&&
	function_exists("date_default_timezone_set")) { 
	@date_default_timezone_set("UTC"); }
if(!isset($Settings['sqlhost'])) { $Settings['sqlhost'] = "localhost"; }
if($Settings['fixpathinfo']=="on") {
	$_SERVER['PATH_INFO'] = $_SERVER['ORIG_PATH_INFO'];
	putenv("PATH_INFO=".$_SERVER['ORIG_PATH_INFO']); }
// Check to see if variables are set
if(!isset($SettDir['inc'])) { $SettDir['inc'] = "inc/"; }
if(!isset($SettDir['archive'])) { $SettDir['archive'] = "archive/"; }
if(!isset($SettDir['misc'])) { $SettDir['misc'] = "inc/misc/"; }
if(!isset($SettDir['sql'])) { $SettDir['sql'] = "inc/misc/sql/"; }
if(!isset($SettDir['admin'])) { $SettDir['admin'] = "inc/admin/"; }
if(!isset($SettDir['sqldumper'])) { $SettDir['sqldumper'] = "inc/admin/sqldumper/"; }
if(!isset($SettDir['mod'])) { $SettDir['mod'] = "inc/mod/"; }
if(!isset($SettDir['themes'])) { $SettDir['themes'] = "themes/"; }
if(!isset($SettDir['maindir'])||!file_exists($SettDir['maindir'])||!is_dir($SettDir['maindir'])) { 
	$SettDir['maindir'] = addslashes(str_replace("\\","/",dirname(__FILE__)."/")); }
if(isset($SettDir['maindir'])) { @chdir($SettDir['maindir']); }
if(!isset($Settings['use_iniset'])) { $Settings['use_iniset'] = null; }
if(!isset($Settings['clean_ob'])) { $Settings['clean_ob'] = "off"; }
if(!isset($_SERVER['PATH_INFO'])) { $_SERVER['PATH_INFO'] = null; }
if(!isset($_SERVER['HTTP_ACCEPT_ENCODING'])) { 
	$_SERVER['HTTP_ACCEPT_ENCODING'] = null; }
if(!isset($_SERVER["HTTP_ACCEPT"])) { $_SERVER["HTTP_ACCEPT"] = null; }
if(!isset($_SERVER['HTTP_REFERER'])) { $_SERVER['HTTP_REFERER'] = null; }
if(!isset($_GET['page'])) { $_GET['page'] = null; }
if(!isset($_GET['act'])) { $_GET['act'] = null; }
if(!isset($_POST['act'])) { $_POST['act'] = null; }
if(!isset($_GET['modact'])) { $_GET['modact'] = null; }
if(!isset($_POST['modact'])) { $_POST['modact'] = null; }
if(!isset($_GET['id'])) { $_GET['id'] = null; }
if(!isset($_GET['debug'])) { $_GET['debug'] = "off"; }
if(!isset($_GET['post'])) { $_GET['post'] = null; }
if(!isset($_POST['License'])) { $_POST['License'] = null; }
if(!isset($_SERVER['HTTPS'])) { $_SERVER['HTTPS'] = "off"; }
if(!isset($Settings['SQLThemes'])) { $Settings['SQLThemes'] = "off"; }
if($Settings['SQLThemes']!="on"&&$Settings['SQLThemes']!="off") { 
	$Settings['SQLThemes'] = "off"; }
require_once($SettDir['misc'].'utf8.php');
require_once($SettDir['inc'].'filename.php');
if(!isset($Settings['use_hashtype'])) {
	$Settings['use_hashtype'] = "sha1"; }
if(!function_exists('hash')||!function_exists('hash_algos')) {
if($Settings['use_hashtype']!="md5"&&
   $Settings['use_hashtype']!="sha1") {
	$Settings['use_hashtype'] = "sha1"; } }
if(function_exists('hash')&&function_exists('hash_algos')) {
if(!in_array($Settings['use_hashtype'],hash_algos())) {
	$Settings['use_hashtype'] = "sha1"; }
if($Settings['use_hashtype']!="md2"&&
   $Settings['use_hashtype']!="md4"&&
   $Settings['use_hashtype']!="md5"&&
   $Settings['use_hashtype']!="sha1"&&
   $Settings['use_hashtype']!="sha224"&&
   $Settings['use_hashtype']!="sha256"&&
   $Settings['use_hashtype']!="sha384"&&
   $Settings['use_hashtype']!="sha512"&&
   $Settings['use_hashtype']!="ripemd128"&&
   $Settings['use_hashtype']!="ripemd160"&&
   $Settings['use_hashtype']!="ripemd256"&&
   $Settings['use_hashtype']!="ripemd320"&&
   $Settings['use_hashtype']!="salsa10"&&
   $Settings['use_hashtype']!="salsa20"&&
   $Settings['use_hashtype']!="snefru"&&
   $Settings['use_hashtype']!="snefru256"&&
   $Settings['use_hashtype']!="gost") {
	$Settings['use_hashtype'] = "sha1"; } }
// Check to see if variables are set
require_once($SettDir['misc'].'setcheck.php');
$dayconv = array("year" => 29030400, "month" => 2419200, "week" => 604800, "day" => 86400, "hour" => 3600, "minute" => 60, "second" => 1);
require_once($SettDir['inc'].'function.php');
if($Settings['enable_pathinfo']=="on") { 
	mrstring(); /* Change Path info to Get Vars :P */ }
// Check to see if variables are set
$qstrhtml = htmlentities($Settings['qstr'], ENT_QUOTES, $Settings['charset']);
if($Settings['enable_https']=="on"&&$_SERVER['HTTPS']=="on") {
if($Settings['idburl']!=null&&$Settings['idburl']!="localhost") {
$HTTPsTest = parse_url($Settings['idburl']); if($HTTPsTest['scheme']=="http") {
$Settings['idburl'] = preg_replace("/http\:\/\//i", "https://", $Settings['idburl']); } } }
$cookieDomain = null; $cookieSecure = false;
if($Settings['idburl']!=null&&$Settings['idburl']!="localhost") {
$URLsTest = parse_url($Settings['idburl']); 
$cookieDomain = $URLsTest['host'];
if($cookieDomain=="localhost") { $cookieDomain = false; }
if($Settings['enable_https']=="on") {
 if($URLsTest['scheme']=="https") { $cookieSecure = true; }
 if($URLsTest['scheme']!="https") { $cookieSecure = false; } } }
if(!in_array("ini_set", $disfunc)) {
@ini_set('default_charset', $Settings['charset']); }
$File1Name = dirname($_SERVER['SCRIPT_NAME'])."/";
$File2Name = $_SERVER['SCRIPT_NAME'];
$File3Name=str_replace($File1Name, null, $File2Name);
if ($File3Name=="sql.php"||$File3Name=="/sql.php") {
	header('Location: index.php');
	exit(); }
//error_reporting(E_ERROR);
// Check if gzip is on and if user's browser can accept gzip pages
if($_GET['act']=="MkCaptcha"||$_GET['act']=="Captcha") {
	$Settings['use_gzip'] = 'off'; }
if($Settings['use_gzip']=="on") {
if(strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip")) { 
	$GZipEncode['Type'] = "gzip"; } else { 
	if(strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "deflate")) { 
	$GZipEncode['Type'] = "deflate"; } else { 
		$Settings['use_gzip'] = "off"; $GZipEncode['Type'] = "none"; } } }
if($Settings['use_gzip']=="gzip") {
if(strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip")) { $Settings['use_gzip'] = "on";
	$GZipEncode['Type'] = "gzip"; } else { $Settings['use_gzip'] = "off"; } }
if($Settings['use_gzip']=="deflate") {
if(strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "deflate")) { $Settings['use_gzip'] = "on";
	$GZipEncode['Type'] = "deflate"; } else { $Settings['use_gzip'] = "off"; } }
$iWrappers = array(null);
function idb_output_handler($buffer) { return $buffer; }
function idb_suboutput_handler($buffer) { return $buffer; }
if($Settings['clean_ob']=="on") {
/* Check for other output handlers/buffers are open
   and close and get the contents in an array */
$numob = count(ob_list_handlers()); $iob = 0; 
while ($iob < $numob) { 
	$old_ob_var[$iob] = ob_get_clean(); 
	++$iob; } } ob_start("idb_output_handler");
if($Settings['use_gzip']=="on") { 
if($GZipEncode['Type']!="gzip") { if($GZipEncode['Type']!="deflate") { $GZipEncode['Type'] = "gzip"; } }
	if($GZipEncode['Type']=="gzip") {
	header("Content-Encoding: gzip"); }
	if($GZipEncode['Type']=="deflate") {
	header("Content-Encoding: deflate"); } }
/* if(eregi("msie",$browser) && !eregi("opera",$browser)){
header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"'); } */
// Some http stuff
$SQLStat = sql_connect_db($Settings['sqlhost'],$Settings['sqluser'],$Settings['sqlpass'],$Settings['sqldb']);
if(isset($Settings['sql_collate'])&&!isset($Settings['sql_charset'])) {
	if($Settings['sql_collate']=="ascii_bin"||
		$Settings['sql_collate']=="ascii_generel_ci") {
		$Settings['sql_charset'] = "ascii"; }
	if($Settings['sql_collate']=="latin1_bin"||
		$Settings['sql_collate']=="latin1_general_ci"||
		$Settings['sql_collate']=="latin1_general_cs") {
		$Settings['sql_charset'] = "latin1"; }
	if($Settings['sql_collate']=="utf8_bin"||
		$Settings['sql_collate']=="utf8_general_ci"||
		$Settings['sql_collate']=="utf8_unicode_ci") {
		$Settings['sql_charset'] = "utf8"; } }
if(isset($Settings['sql_collate'])&&isset($Settings['sql_charset'])) {
	if($Settings['sql_charset']=="ascii") {
	if($Settings['sql_collate']!="ascii_bin"&&
		$Settings['sql_collate']!="ascii_generel_ci") {
		$Settings['sql_collate'] = "ascii_generel_ci"; } }
	if($Settings['sql_charset']=="latin1") {
	if($Settings['sql_collate']!="latin1_bin"&&
		$Settings['sql_collate']!="latin1_general_ci"&&
		$Settings['sql_collate']!="latin1_general_cs") {
		$Settings['sql_collate'] = "latin1_general_ci"; } }
	if($Settings['sql_charset']=="utf8") {
	if($Settings['sql_collate']!="utf8_bin"&&
		$Settings['sql_collate']!="utf8_general_ci"&&
		$Settings['sql_collate']!="utf8_unicode_ci") {
		$Settings['sql_collate'] = "utf8_unicode_ci"; } }
	$SQLCollate = $Settings['sql_collate'];
	$SQLCharset = $Settings['sql_charset']; }
if(!isset($Settings['sql_collate'])||!isset($Settings['sql_charset'])) {
$SQLCollate = "latin1_general_ci";
$SQLCharset = "latin1"; 
if($Settings['charset']=="ISO-8859-1") {
	$SQLCollate = "latin1_general_ci";
	$SQLCharset = "latin1"; }
if($Settings['charset']=="ISO-8859-15") {
	$SQLCollate = "latin1_general_ci";
	$SQLCharset = "latin1"; }
if($Settings['charset']=="UTF-8") {
	$SQLCollate = "utf8_unicode_ci";
	$SQLCharset = "utf8"; } 
$Settings['sql_collate'] = $SQLCollate;
$Settings['sql_charset'] = $SQLCharset; }
sql_set_charset($SQLCharset,$SQLStat);
if($SQLStat===false) {
header("Content-Type: text/plain; charset=".$Settings['charset']); sql_free_result($peresult);
ob_clean(); echo "Sorry could not connect to sql database.\nContact the board admin about error. Error log below.";
echo "\n".sql_errorno($SQLStat); $urlstatus = 503;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
$sqltable = $Settings['sqltable'];
$temp_user_ip = $_SERVER['REMOTE_ADDR'];
if(!isset($_SERVER['HTTP_USER_AGENT'])) {
	$_SERVER['HTTP_USER_AGENT'] = ""; }
if(strpos($_SERVER['HTTP_USER_AGENT'], "msie") && 
	!strpos($_SERVER['HTTP_USER_AGENT'], "opera")){
	header("X-UA-Compatible: IE=Edge"); }
if(strpos($_SERVER['HTTP_USER_AGENT'], "chromeframe")) {
	header("X-UA-Compatible: IE=Edge,chrome=1"); }
$temp_user_agent = $_SERVER['HTTP_USER_AGENT'];
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$MkIndexFile = $exfile['index'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$MkIndexFile = $exfile['index']; }
$temp_session_data = "ViewingPage|s:9:\"?act=view\";ViewingFile|s:".strlen($MkIndexFile).":\"".$MkIndexFile."\";PreViewingTitle|s:7:\"Viewing\";ViewingTitle|s:11:\"Board index\";UserID|s:1:\"0\";UserIP|s:".strlen($_SERVER['REMOTE_ADDR']).":\"".$_SERVER['REMOTE_ADDR']."\";UserGroup|s:".strlen($Settings['GuestGroup']).":\"".$Settings['GuestGroup']."\";UserGroupID|s:1:\"4\";UserTimeZone|s:".strlen($Settings['DefaultTimeZone']).":\"".$Settings['DefaultTimeZone']."\";UserDST|s:".strlen($Settings['DefaultDST']).":\"".$Settings['DefaultDST']."\";";
$SQLSType = $Settings['sqltype'];
//Session Open Function
function sql_session_open($save_path, $session_name ) {
global $sess_save_path;
$sess_save_path = $save_path;
return true; }
//Session Close Function
$iDBSessCloseDB = true;
function sql_session_close() {
global $SQLStat,$iDBSessCloseDB;
if($iDBSessCloseDB===true) {
sql_disconnect_db($SQLStat); }
return true; }
//Session Read Function
function sql_session_read($id) {
global $sqltable,$SQLStat,$SQLSType,$temp_user_ip,$temp_user_agent,$temp_session_data;
$result = sql_query(sql_pre_query("SELECT * FROM \"".$sqltable."sessions\" WHERE \"session_id\" = '%s'", array($id)),$SQLStat);
if (!sql_num_rows($result)) {
sql_query(sql_pre_query("DELETE FROM \"".$sqltable."sessions\" WHERE \"session_id\"<>'%s' AND \"ip_address\"='%s' AND \"user_agent\"='%s'", array($id,$temp_user_ip,$temp_user_agent)),$SQLStat);
$time = GMTimeStamp();
sql_query(sql_pre_query("INSERT INTO \"".$sqltable."sessions\" (\"session_id\", \"session_data\", \"user_agent\", \"ip_address\", \"expires\") VALUES\n".
"('%s', '%s', '%s', '%s', %i)", array($id,$temp_session_data,$temp_user_agent,$temp_user_ip,$time)),$SQLStat);
return '';
} else {
$time = GMTimeStamp();
$predata = sql_num_rows($result);
$data = "";
if($predata > 0) {
$row = sql_fetch_assoc($result);
$data = $row['session_data']; }
/*sql_query(sql_pre_query("UPDATE \"".$sqltable."sessions\" SET \"session_data\"='%s',\"expires\"=%i WHERE \"session_id\"='%s'", array($data,$time,$id)),$SQLStat);*/
return $data; } }
//Session Write Function
function sql_session_write($id,$data) {
global $sqltable,$SQLStat,$SQLSType,$temp_user_ip,$temp_user_agent;
$time = GMTimeStamp();
$rs = sql_query(sql_pre_query("UPDATE \"".$sqltable."sessions\" SET \"session_data\"='%s',\"user_agent\"='%s',\"ip_address\"='%s',\"expires\"=%i WHERE \"session_id\"='%s'", array($data,$temp_user_agent,$temp_user_ip,$time,$id)),$SQLStat);
return true; }
//Session Destroy Function
function sql_session_destroy($id) {
global $sqltable,$SQLStat;
sql_query(sql_pre_query("DELETE FROM \"".$sqltable."sessions\" WHERE \"session_id\" = '$id'", array($id)),$SQLStat);
return true; }
//Session Garbage Collection Function
function sql_session_gc($maxlifetime) {
global $sqltable,$SQLStat;
$time = GMTimeStamp() - $maxlifetime;
//sql_query(sql_pre_query('DELETE FROM \"'.$sqltable.'sessions\" WHERE \"expires\" < UNIX_TIMESTAMP();', array(null)),$SQLStat);
sql_query(sql_pre_query("DELETE FROM \"".$sqltable."sessions\" WHERE \"expires\" < %i", array($time)),$SQLStat);
return true; }
if (session_id()) { session_destroy(); }
session_set_save_handler("sql_session_open", "sql_session_close", "sql_session_read", "sql_session_write", "sql_session_destroy", "sql_session_gc");
if($cookieDomain==null) {
session_set_cookie_params(0, $cbasedir); }
if($cookieDomain!=null) {
if($cookieSecure===true) {
session_set_cookie_params(0, $cbasedir, $cookieDomain, 1); }
if($cookieSecure===false) {
session_set_cookie_params(0, $cbasedir, $cookieDomain); } }
session_cache_limiter("private, no-cache, no-store, must-revalidate, pre-check=0, post-check=0, max-age=0");
header("Cache-Control: private, no-cache, no-store, must-revalidate, pre-check=0, post-check=0, max-age=0");
header("Pragma: private, no-cache, no-store, must-revalidate, pre-check=0, post-check=0, max-age=0");
header("P3P: CP=\"IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT\"");
header("Date: ".gmdate("D, d M Y H:i:s")." GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Expires: ".gmdate("D, d M Y H:i:s")." GMT");
if(!isset($_COOKIE[$Settings['sqltable']."sess"])) {
$exptime = GMTimeStamp() - ini_get("session.gc_maxlifetime");
sql_query(sql_pre_query("DELETE FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" < %i OR \"ip_address\"='%s' AND \"user_agent\"='%s'", array($exptime,$temp_user_ip,$temp_user_agent)),$SQLStat); }
if(!isset($_SESSION['CheckCookie'])) {
if(isset($_COOKIE['SessPass'])&&isset($_COOKIE['MemberName'])) {
session_set_save_handler("sql_session_open", "sql_session_close", "sql_session_read", "sql_session_write", "sql_session_destroy", "sql_session_gc");
session_name($Settings['sqltable']."sess");
session_start();
if(!isset($_SESSION['UserFormID'])) { $_SESSION['UserFormID'] = null; }
$iDBSessCloseDB = false;
$_SESSION['ShowActHidden'] = "no";
output_reset_rewrite_vars();
require($SettDir['inc'].'prelogin.php'); 
session_write_close(); } }
session_set_save_handler("sql_session_open", "sql_session_close", "sql_session_read", "sql_session_write", "sql_session_destroy", "sql_session_gc");
session_name($Settings['sqltable']."sess");
session_start();
if(!isset($_SESSION['UserFormID'])) { $_SESSION['UserFormID'] = null; }
$iDBSessCloseDB = true;
output_reset_rewrite_vars();
//@register_shutdown_function("session_write_close");
//header("Set-Cookie: PHPSESSID=" . session_id() . "; path=".$cbasedir);
if(!in_array("ini_set", $disfunc)) {
// Set user agent if we can use ini_set and have to do any http requests. :P 
$iverstring = "FR 0.0.0 ".$VER2[2]." 0";
if($Settings['hideverinfohttp']=="off") {
	$iverstring = $VER2[1]." ".$VER1[0].".".$VER1[1].".".$VER1[2]." ".$VER2[2]." ".$SubVerN; }
if($Settings['hideverinfohttp']=="on") {
	$iverstring = "FR 0.0.0 ".$VER2[2]." 0"; }
$qstrtest = htmlentities($Settings['qstr'], ENT_QUOTES, $Settings['charset']);
$qseptest = htmlentities($Settings['qsep'], ENT_QUOTES, $Settings['charset']);
$isiteurl = $Settings['idburl']."?act".$qseptest."view";
@ini_set("user_agent", "Mozilla/5.0 (compatible; ".$VerCheckName."/".$iverstring."; +".$isiteurl.")"); 
if (function_exists("stream_context_create")) {
$iopts = array(
  'http' => array(
    'method' => "GET",
    'header' => "Accept-Language: *\r\n".
                "User-Agent: Mozilla/5.0 (compatible; ".$VerCheckName."/".$iverstring."; +".$isiteurl.")\r\n".
                "Accept: */*\r\n".
                "Connection: keep-alive\r\n".
                "Referer: ".$isiteurl."\r\n".
                "From: ".$isiteurl."\r\n"
  )
);
$icontext = stream_context_create($iopts); } }
$iDBVerName = $VerCheckName."|".$VER2[1]."|".$VER1[0].".".$VER1[1].".".$VER1[2]."|".$VER2[2]."|".$SubVerN;
/* 
This way checks iDB version by sending the iDBVerName to the iDB Version Checker.
$Settings['vercheck'] = 1; 
This way checks iDB version by sending the board url to the iDB Version Checker.
$Settings['vercheck'] = 2;
*/
if(!isset($Settings['vercheck'])) { 
	$Settings['vercheck'] = 2; }
if($Settings['vercheck']!=1&&
	$Settings['vercheck']!=2) {
	$Settings['vercheck'] = 2; }
if($Settings['vercheck']===2) {
if($_GET['act']=="vercheckxsl") {
if(stristr($_SERVER["HTTP_ACCEPT"],"application/xml") ) {
header("Content-Type: application/xml; charset=".$Settings['charset']); }
else { header("Content-Type: text/xml; charset=".$Settings['charset']); }
xml_doc_start("1.0",$Settings['charset']);
echo "\n"; ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
 <html xsl:version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">
  <body style="font-family:Arial;font-size:12pt;background-color:#EEEEEE">
   <xsl:for-each select="versioninfo/version">
    <div style="background-color:teal;color:white;padding:4px">
     <span style="font-weight:bold"><xsl:value-of select="vname"/></span>
    </div>
    <div style="margin-left:20px;margin-bottom:1em;font-size:10pt">
     <span style="font-style:italic">
          Board Name: <a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>"><xsl:value-of select="title"/></a></span>
    </div>
   </xsl:for-each>
  </body>
 </html>
</xsl:template>

</xsl:stylesheet>
<?php gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); } 
if($_GET['act']=="versioninfo") {
if(stristr($_SERVER["HTTP_ACCEPT"],"application/xml") ) {
header("Content-Type: application/xml; charset=".$Settings['charset']); }
else { header("Content-Type: text/xml; charset=".$Settings['charset']); }
xml_doc_start("1.0",$Settings['charset']);
echo '<?xml-stylesheet type="text/xsl" href="'.url_maker($exfile['index'],$Settings['file_ext'],"act=vercheckxsl",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']).'"?>'."\n"; ?>

<!DOCTYPE versioninfo [
<!ELEMENT versioninfo (version*)>
<!ELEMENT version (charset,title,name,vname)>
<!ELEMENT charset (#PCDATA)>
<!ELEMENT title (#PCDATA)>
<!ELEMENT name (#PCDATA)>
<!ELEMENT vname (#PCDATA)>
]>

<versioninfo>

<version>
 <charset><?php echo $Settings['charset']; ?></charset> 
  <title><?php echo $Settings['board_name']; ?></title> 
  <?php echo "<name>".$iDBVerName."</name>\n"; ?>
  <vname><?php echo $VerCheckName; ?> Version Checker</vname>
</version>

</versioninfo>
<?php gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); } } 
if($Settings['vercheck']===1) {
if($_GET['act']=="versioninfo") { header("Content-Type: text/plain; charset=UTF-8");
header("Location: ".$VerCheckURL."&name=".urlencode($iDBVerName)); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); } }
if($_GET['act']=="homepage") { header("Content-Type: text/plain; charset=UTF-8");
header("Location: ".$Settings['weburl']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($_GET['act']=="bsdl"||$_GET['act']=="BSDL"||$_GET['act']=="license"||
	$_GET['act']=="LICENSE"||$_GET['act']=="License") { $_GET['act']="bsd"; }
if($_GET['act']=="bsd") {
header("Content-Type: text/plain; charset=".$Settings['charset']);
require("LICENSE"); gzip_page($Settings['use_gzip'],$GZipEncode['Type']); die(); }
if($_GET['act']=="README"||$_GET['act']=="ReadME") { $_GET['act']="readme"; }
if($_GET['act']=="readme"||$_GET['act']=="ReadMe") {
header("Content-Type: text/plain; charset=".$Settings['charset']);
require("README"); gzip_page($Settings['use_gzip'],$GZipEncode['Type']); die(); }
if($_GET['act']=="js"||$_GET['act']=="javascript") {
header("Content-Script-Type: text/javascript");
if(stristr($_SERVER["HTTP_ACCEPT"],"application/x-javascript") ) {
header("Content-Type: application/x-javascript; charset=".$Settings['charset']); } else {
if(stristr($_SERVER["HTTP_ACCEPT"],"application/javascript") ) {
header("Content-Type: application/javascript; charset=".$Settings['charset']); } else {
header("Content-Type: text/javascript; charset=".$Settings['charset']); } }
require($SettDir['inc'].'javascript.php');
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); die(); }
if($Settings['use_captcha']=="on") {
if($_GET['act']=="MkCaptcha"||$_GET['act']=="Captcha") {
	if($Settings['captcha_clean']=="on") { ob_clean(); }
	require($SettDir['inc']."captcha.php");
	$aFonts = array('inc/fonts/VeraBd.ttf', 'inc/fonts/VeraBI.ttf', 'inc/fonts/VeraIt.ttf', 'inc/fonts/Vera.ttf');
	$oPhpCaptcha = new PhpCaptcha($aFonts, 200, 60);
	$RNumSize = rand(7,17); $i=0; $RandNum = null;
	while ($i <= $RNumSize) {
	$RandNum=$RandNum.dechex(rand(1,15)); ++$i; }
	$RandNum=strtoupper($RandNum);
	$oPhpCaptcha->SetOwnerText("Fake Code: ".$RandNum);
	$oPhpCaptcha->UseColour(true);
	$oPhpCaptcha->Create(); session_write_close(); die(); } }
require($SettDir['inc'].'groupsetup.php');
if($Settings['board_offline']=="on"&&$GroupInfo['CanViewOffLine']!="yes") {
header("Content-Type: text/plain; charset=".$Settings['charset']); sql_free_result($peresult);
ob_clean(); if(!isset($Settings['offline_text'])) {
echo "Sorry the board is off line.\nIf you are a admin you can login by the admin cp."; }
if(isset($Settings['offline_text'])) { echo $Settings['offline_text']; } $urlstatus = 503;
//echo "\n".sql_errorno($SQLStat);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
//Time Zone Set
if(!isset($_SESSION['UserTimeZone'])) { 
	if(isset($Settings['DefaultTimeZone'])) { 
	$_SESSION['UserTimeZone'] = $Settings['DefaultTimeZone'];
	if(!isset($Settings['DefaultTimeZone'])) { 
	$_SESSION['UserTimeZone'] = SeverOffSet().":00"; } } }
$checktime = explode(":",$_SESSION['UserTimeZone']);
if(count($checktime)!=2) {
	if(!isset($checktime[0])) { $checktime[0] = "0"; }
	if(!isset($checktime[1])) { $checktime[1] = "00"; }
	$_SESSION['UserTimeZone'] = $checktime[0].":".$checktime[1]; }
if(!is_numeric($checktime[0])) { $checktime[0] = "0"; }
if(!is_numeric($checktime[1])) { $checktime[1] = "00"; }
if($checktime[1]<0) { $checktime[1] = "00"; $_SESSION['UserTimeZone'] = $checktime[0].":".$checktime[1]; }
$checktimea = array("offset" => $_SESSION['UserTimeZone'], "hour" => $checktime[0], "minute" => $checktime[1]);
if(!isset($_SESSION['UserDST'])) { $_SESSION['UserDST'] = null; }
if($_SESSION['UserDST']==null) {
if($Settings['DefaultDST']=="off") { 
	$_SESSION['UserDST'] = "off"; }
if($Settings['DefaultDST']=="on") { 
	$_SESSION['UserDST'] = "on"; } }
// Guest Stuff
if(isset($_SESSION['MemberName'])||
   isset($_COOKIE['MemberName'])) {
	$_SESSION['GuestName'] = null;
	$_COOKIE['GuestName'] = null; }
if(!isset($_SESSION['MemberName'])&&!isset($_COOKIE['MemberName'])) {
if(!isset($_SESSION['GuestName'])&&isset($_COOKIE['GuestName'])) {
	$_SESSION['GuestName'] = $_COOKIE['GuestName']; } }
if(!isset($_SESSION['LastPostTime'])) { $_SESSION['LastPostTime'] = "0"; }
// Skin Stuff
if(!isset($_SESSION['Theme'])) { $_SESSION['Theme'] = null; }
if(!isset($_GET['theme'])) { $_GET['theme'] = null; }
if(!isset($_POST['theme'])) { $_POST['theme'] = null; }
if(!isset($_GET['skin'])) { $_GET['skin'] = null; }
if(!isset($_POST['skin'])) { $_POST['skin'] = null; }
if(!isset($_GET['style'])) { $_GET['style'] = null; }
if(!isset($_POST['style'])) { $_POST['style'] = null; }
if(!isset($_GET['css'])) { $_GET['css'] = null; }
if(!isset($_POST['css'])) { $_POST['css'] = null; }
if($_GET['theme']==null) {
	if($_POST['theme']!=null) {
		$_GET['theme'] = $_POST['theme']; }
	if($_POST['skin']!=null) {
		$_GET['theme'] = $_POST['skin']; }
	if($_POST['style']!=null) {
		$_GET['theme'] = $_POST['style']; }
	if($_POST['css']!=null) {
		$_GET['theme'] = $_POST['css']; }
	if($_GET['skin']!=null) {
		$_GET['theme'] = $_GET['skin']; }
	if($_GET['style']!=null) {
		$_GET['theme'] = $_GET['style']; }
	if($_GET['css']!=null) {
		$_GET['theme'] = $_GET['css']; } }
if($Settings['SQLThemes']=="off") {
if($_GET['theme']!=null) {
$_GET['theme'] = chack_themes($_GET['theme']);
if($_GET['theme']=="../"||$_GET['theme']=="./") {
$_GET['theme']=$Settings['DefaultTheme']; $_SESSION['Theme']=$Settings['DefaultTheme']; }
if (file_exists($SettDir['themes'].$_GET['theme']."/settings.php")) {
if($_SESSION['UserGroup']!=$Settings['GuestGroup']) {
$NewDay=GMTimeStamp();
$qnewskin = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"UseTheme\"='%s',\"LastActive\"='%s' WHERE \"id\"=%i", array($_GET['theme'],$NewDay,$_SESSION['UserID']));
sql_query($qnewskin,$SQLStat); }
/* The file Theme Exists */ }
else { $_GET['theme'] = $Settings['DefaultTheme']; 
$_SESSION['Theme'] = $Settings['DefaultTheme'];
/* The file Theme Dose Not Exists */ } }
if($_GET['theme']==null) { 
if($_SESSION['Theme']!=null) {
$OldTheme = $_SESSION['Theme'];
$_SESSION['Theme'] = chack_themes($_SESSION['Theme']);
if($_SESSION['UserGroup']!=$Settings['GuestGroup']) {
if($OldTheme!=$_SESSION['Theme']) { 
$NewDay=GMTimeStamp();
$qnewskin = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"UseTheme\"='%s',\"LastActive\"='%s' WHERE \"id\"=%i", array($_SESSION['Theme'],$NewDay,$_SESSION['UserID']));
sql_query($qnewskin,$SQLStat); } }
$_GET['theme']=$_SESSION['Theme']; }
if($_SESSION['Theme']==null) {
$_SESSION['Theme']=$Settings['DefaultTheme'];
$_GET['theme']=$Settings['DefaultTheme']; } }
$PreSkin['skindir1'] = $_SESSION['Theme'];
$PreSkin['skindir2'] = $SettDir['themes'].$_SESSION['Theme'];
require($SettDir['themes'].$_GET['theme']."/settings.php"); }
if($Settings['SQLThemes']=="on") {
if($_GET['theme']==null&&$_SESSION['Theme']==null) { 
	$_GET['theme'] = $Settings['DefaultTheme']; 
	$_SESSION['Theme'] = $Settings['DefaultTheme']; }
if($_GET['theme']!=null) {
$themequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."themes\" WHERE \"Name\"='%s'", array($_GET['theme'])); }
if($_GET['theme']==null) { 
if($_SESSION['Theme']!=null) {
$themequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."themes\" WHERE \"Name\"='%s'", array($_SESSION['Theme'])); } }
$themeresult=sql_query($themequery,$SQLStat);
$themenum=sql_num_rows($themeresult);
if($themenum<=0) {
$_GET['theme'] = $Settings['DefaultTheme']; 
$_SESSION['Theme'] = $Settings['DefaultTheme']; 
if($_SESSION['UserGroup']!=$Settings['GuestGroup']) {
$NewDay=GMTimeStamp();
$qnewskin = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"UseTheme\"='%s',\"LastActive\"='%s' WHERE \"id\"=%i", array($_SESSION['Theme'],$NewDay,$_SESSION['UserID']));
sql_query($qnewskin,$SQLStat); }
$themequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."themes\" WHERE \"Name\"='%s'", array($_GET['theme']));
$themeresult=sql_query($themequery,$SQLStat);
$themenum=sql_num_rows($themeresult); } 
else {
if($_GET['theme']==null) { 
if($_SESSION['Theme']!=null) {
$_GET['theme'] = $_SESSION['Theme']; } }
if($_SESSION['UserGroup']!=$Settings['GuestGroup']) {
$NewDay=GMTimeStamp();
$qnewskin = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"UseTheme\"='%s',\"LastActive\"='%s' WHERE \"id\"=%i", array($_GET['theme'],$NewDay,$_SESSION['UserID']));
sql_query($qnewskin,$SQLStat); } } 
require($SettDir['inc'].'sqlthemes.php');
sql_free_result($themeresult); }
$_SESSION['Theme'] = $_GET['theme'];
function get_theme_values($matches) {
	global $ThemeSet;
	$return_text = null;
	if(isset($ThemeSet[$matches[1]])) { $return_text = $ThemeSet[$matches[1]]; }
	if(!isset($ThemeSet[$matches[1]])) { $return_text = null; }
	return $return_text; }
foreach($ThemeSet AS $key => $value) {
	$ThemeSet[$key] = preg_replace("/%%/s", "{percent}p", $ThemeSet[$key]);
	$ThemeSet[$key] = preg_replace_callback("/%\{([^\}]*)\}T/s", "get_theme_values", $ThemeSet[$key]);
	$ThemeSet[$key] = preg_replace_callback("/%\{([^\}]*)\}e/s", "get_env_values", $ThemeSet[$key]);
	$ThemeSet[$key] = preg_replace_callback("/%\{([^\}]*)\}i/s", "get_server_values", $ThemeSet[$key]);
	$ThemeSet[$key] = preg_replace_callback("/%\{([^\}]*)\}s/s", "get_setting_values", $ThemeSet[$key]);
	$ThemeSet[$key] = preg_replace_callback("/%\{([^\}]*)\}t/s", "get_time", $ThemeSet[$key]); 
	$ThemeSet[$key] = preg_replace("/\{percent\}p/s", "%", $ThemeSet[$key]); }
if(!isset($ThemeSet['TableStyle'])) {
	$ThemeSet['TableStyle'] = "table"; }
if(isset($ThemeSet['TableStyle'])) {
if($ThemeSet['TableStyle']!="div"&&
	$ThemeSet['TableStyle']!="table") {
	$ThemeSet['TableStyle'] = "table"; } }
if(!isset($_SESSION['DBName'])) { $_SESSION['DBName'] = null; }
if($_SESSION['DBName']==null) {
	$_SESSION['DBName'] = $Settings['sqldb']; }
if($_SESSION['DBName']!=null) {
	if($_SESSION['DBName']!=$Settings['sqldb']) {
redirect("location",$rbasedir.url_maker($exfile['member'],$Settings['file_ext'],"act=logout",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],false)); } }
?>
