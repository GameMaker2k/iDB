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

    $FileInfo: functions.php - Last Update: 08/05/2007 SVN 69 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="functions.php"||$File3Name=="/functions.php") {
	require('index.php');
	exit(); }
// Check the file names
function CheckFile($FileName) {
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name==$FileName||$File3Name=="/".$FileName) {
	require('index.php');
	exit(); }
return null; }
function CheckFiles($FileName) {
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name==$FileName||$File3Name=="/".$FileName) {
	return true; } }
CheckFile("functions.php");
require($SettDir['misc']."compression.php");
if ($_GET['act']=="DeleteSession") { @session_destroy(); }
if ($_GET['act']=="ResetSession") { @session_unset(); }
if ($_GET['act']=="NewSessionID") { @session_regenerate_id(); }
/* 
if ($_GET['act']=="PHPInfo") { @phpinfo(); exit(); }
if ($_GET['act']=="phpinfo") { @phpinfo(); exit(); }
if ($_GET['act']=="PHPCredits") { @phpcredits(); exit(); }
if ($_GET['act']=="phpcredits") { @phpcredits(); exit(); } 
*/// Connect to mysql database
function ConnectMysql($sqlhost,$sqluser,$sqlpass,$sqldb) {
$StatSQL = @mysql_connect($sqlhost,$sqluser,$sqlpass);
$StatBase = @mysql_select_db($sqldb);
if (!$StatSQL) { return false; }
if (!$StatBase) { return false; }
return true; }
	$Names['RS'] = "Renee Sabonis";
define("_renee_", $Names['RS']);
// Change the title and gzip page
function change_title($new_title,$use_gzip="off",$gzip_type="gzip") {
global $Settings;
if($gzip_type!="gzip") { if($gzip_type!="deflate") { $gzip_type = "gzip"; } }
$output = @ob_get_clean();
$output = preg_replace("/<title>(.*?)<\/title>/i", "<title>".$new_title."</title>", $output);
/* Change Some PHP Settings Fix the &PHPSESSID to &amp;PHPSESSID */
$SessName = @session_name();
$output = preg_replace("/&PHPSESSID/", "&amp;PHPSESSID", $output);
$qstrcode = htmlentities($Settings['qstr']);
$output = str_replace($Settings['qstr'].$SessName, $qstrcode.$SessName, $output);
if($use_gzip!="on") {
	echo $output; }
if($use_gzip=="on") {
	if($gzip_type=="gzip") {
	$goutput = gzencode($output); }
	if($gzip_type=="deflate") {
	$goutput = gzcompress($output); }
	echo $goutput; } }
// Fix amp => (&) to &amp; and gzip page
function fix_amp($use_gzip="off",$gzip_type="gzip") {
global $Settings;
if($gzip_type!="gzip") { if($gzip_type!="deflate") { $gzip_type = "gzip"; } }
$output = @ob_get_clean();
/* Change Some PHP Settings Fix the &PHPSESSID to &amp;PHPSESSID */
$SessName = @session_name();
$output = preg_replace("/&PHPSESSID/", "&amp;PHPSESSID", $output);
$qstrcode = htmlentities($Settings['qstr']);
$output = str_replace($Settings['qstr'].$SessName, $qstrcode.$SessName, $output);
if($use_gzip!="on") {
	echo $output; }
if($use_gzip=="on") {
	if($gzip_type=="gzip") {
	$goutput = gzencode($output); }
	if($gzip_type=="deflate") {
	$goutput = gzcompress($output); }
	echo $goutput; } }
$output = @ob_get_clean();
	$Names['RJ'] = "René Johnson";
define("_rene_", $Names['RJ']);
// GZip page for faster download
function gzip_page($use_gzip="off",$gzip_type="gzip") {
global $Settings;
$output = @ob_get_clean();
if($gzip_type!="gzip") { if($gzip_type!="deflate") { $gzip_type = "gzip"; } }
if($use_gzip!="on") {
	echo $output; }
if($use_gzip=="on") {
	if($gzip_type=="gzip") {
	$goutput = gzencode($output); }
	if($gzip_type=="deflate") {
	$goutput = gzcompress($output); }
	echo $goutput; } }
$foo="bar"; $$foo="foo";
// SafeSQL Lite Source Code by Cool Dude 2k
// Make SQL Query's safe
function query($query_string,$query_vars) {
   $query_array = array(array("%i","%I","%F","%S"),array("%d","%d","%f","%s"));
   $query_string = str_replace($query_array[0], $query_array[1], $query_string);
   if (get_magic_quotes_gpc()) {
       $query_vars  = array_map("stripslashes", $query_vars); }
   $query_vars = array_map("mysql_real_escape_string", $query_vars);
   $query_val = $query_vars;
$query_num = count($query_val);
$query_i = 0;
while ($query_i < $query_num) {
$query_is = $query_i+1;
$query_val[$query_is] = $query_vars[$query_i];
++$query_i; }
   $query_val[0] = $query_string;
   return call_user_func_array("sprintf",$query_val); }
// Kill bad vars for some functions
function killbadvars($varname) {
$badphp1 = array('$'); $badphp2 = array(null);
$varname = str_replace($badphp1, $badphp2, $varname);
$varname = preg_replace("/(_SERVER|_ENV|_COOKIE|_SESSION)/i", null, $varname);
$varname = preg_replace("/(_GET|_POST|_FILES|_REQUEST|GLOBALS)/i", null, $varname);
$varname = preg_replace("/(HTTP_SERVER_VARS|HTTP_ENV_VARS)/i", null, $varname);
$varname = preg_replace("/(HTTP_COOKIE_VARS|HTTP_SESSION_VARS)/i", null, $varname);
$varname = preg_replace("/(HTTP_GET_VARS|HTTP_POST_VARS|HTTP_POST_FILES)/i", null, $varname);
	return $varname; }
// Change the text to icons(smileys)
function text2icons($Text,$sqlt) {
global $Settings;
$reneequery=query("select * from `".$sqlt."smileys`", array(null));
$reneeresult=mysql_query($reneequery);
$reneenum=mysql_num_rows($reneeresult);
$renees=0;
while ($renees < $reneenum) {
$FileName=mysql_result($reneeresult,$renees,"FileName");
$SmileName=mysql_result($reneeresult,$renees,"SmileName");
$SmileText=mysql_result($reneeresult,$renees,"SmileText");
$SmileDirectory=mysql_result($reneeresult,$renees,"Directory");
$ShowSmile=mysql_result($reneeresult,$renees,"Show");
$ReplaceType=mysql_result($reneeresult,$renees,"ReplaceCI");
if($ReplaceType=="on") { $ReplaceType = "yes"; }
if($ReplaceType=="off") { $ReplaceType = "no"; }
if($ReplaceType!="yes"||$ReplaceType!="no") { $ReplaceType = "no"; }
$Smile1 = $SmileText;
$Smile2 = '<img src="'.$SmileDirectory.''.$FileName.'" style="vertical-align: middle; border: 0px;" title="'.$SmileName.'" alt="'.$SmileName.'" />';
if($ReplaceType=="no") {
$Text = str_replace($Smile1, $Smile2, $Text); }
if($ReplaceType=="yes") {
	$Smile1 = preg_quote($SmileText,"/");
$Text = preg_replace("/".$Smile1."/i",$Smile2,$Text); }
++$renees; } return $Text; }
// Removes the bad stuff
function remove_bad_entities($Text) {
//HTML Entities Dec Version
$Text = preg_replace("/&#8238;/isU","",$Text);
$Text = preg_replace("/&#8194;/isU","",$Text);
$Text = preg_replace("/&#8195;/isU","",$Text);
$Text = preg_replace("/&#8201;/isU","",$Text);
$Text = preg_replace("/&#8204;/isU","",$Text);
$Text = preg_replace("/&#8205;/isU","",$Text);
$Text = preg_replace("/&#8206;/isU","",$Text);
$Text = preg_replace("/&#8207;/isU","",$Text);
//HTML Entities Hex Version
$Text = preg_replace("/&#x202e;/isU","",$Text);
$Text = preg_replace("/&#x2002;/isU","",$Text);
$Text = preg_replace("/&#x2003;/isU","",$Text);
$Text = preg_replace("/&#x2009;/isU","",$Text);
$Text = preg_replace("/&#x200c;/isU","",$Text);
$Text = preg_replace("/&#x200d;/isU","",$Text);
$Text = preg_replace("/&#x200e;/isU","",$Text);
$Text = preg_replace("/&#x200f;/isU","",$Text);
//HTML Entities Name Version
$Text = preg_replace("/&ensp;/isU","",$Text);
$Text = preg_replace("/&emsp;/isU","",$Text);
$Text = preg_replace("/&thinsp;/isU","",$Text);
$Text = preg_replace("/&zwnj;/isU","",$Text);
$Text = preg_replace("/&zwj;/isU","",$Text);
$Text = preg_replace("/&lrm;/isU","",$Text);
$Text = preg_replace("/&rlm;/isU","",$Text);
return $Text; }
// Remove the bad stuff
function remove_spaces($Text) {
$Text = preg_replace("/(^\t+|\t+$)/","",$Text);
$Text = preg_replace("/(^\n+|\n+$)/","",$Text);
$Text = preg_replace("/(^\r+|\r+$)/","",$Text);
$Text = preg_replace("/(\r|\n|\t)+/"," ",$Text);
$Text = preg_replace("/\s\s+/"," ",$Text);
$Text = preg_replace("/(^\s+|\s+$)/","",$Text);
$Text = @remove_bad_entities($Text);
return $Text; }
// Fix some chars
function fixbamps($text) {
$fixamps1 = array("&amp;copy;","&amp;reg;","&amp;trade;","&amp;quot;","&amp;amp;","&amp;lt;","&amp;gt;","&amp;(a|e|i|o|u|y)acute;","&amp;(a|e|i|o|u)grave;","&amp;(a|e|i|o|u)circ;","&amp;(a|e|i|o|u|y)uml;","&amp;(a|o|n)tilde;","&amp;aring;","&amp;aelig;","&amp;ccedil;","&amp;eth;","&amp;oslash;","&amp;szlig;","&amp;thorn;");
$fixamps2 = array("&copy;","&reg;","&trade;","&quot;","&amp;","&lt;","&gt;","&\\1acute;","&\\1grave;","&\\1circ;","&\\1uml;","&\\1tilde;","&aring;","&aelig;","&ccedil;","&eth;","&oslash;","&szlig;","&thorn;");
$ampnum = count($fixamps1); $ampi=0;
while ($ampi < $ampnum) {
$text = preg_replace("/".$fixamps1[$ampi]."/i", $fixamps2[$ampi], $text);
++$ampi; }
$text = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $text);
return $text; }
// Get next id for stuff
function getnextid($tablepre,$table) {
   $getnextidq = query("SHOW TABLE STATUS LIKE '".$tablepre.$table."'", array());
   $getnextidr = mysql_query($getnextidq);
   $getnextid = mysql_fetch_assoc($getnextidr);
   return $getnextid['Auto_increment'];
   @mysql_free_result($getnextidr); }
	$Names['RSA'] = "Rachel Sabonis";
define("_rachel_", $Names['RSA']);
// Change Time Stamp to a readable time
function GMTimeChange($format,$timestamp,$offset,$minoffset=null,$dst=null) {
$TCHour = date("H",$timestamp);
$TCMinute = date("i",$timestamp);
$TCSecond = date("s",$timestamp);
$TCMonth = date("n",$timestamp);
$TCDay = date("d",$timestamp);
$TCYear = date("Y",$timestamp);
unset($dstake); $dstake = null;
if(!is_numeric($minoffset)) { $minoffset = "00"; }
$ts_array = explode(":",$offset);
if(count($ts_array)!=2) {
	if(!isset($ts_array[0])) { $ts_array[0] = "0"; }
	if(!isset($ts_array[1])) { $ts_array[1] = "00"; }
	$offset = $ts_array[0].":".$ts_array[1]; }
if(!is_numeric($ts_array[0])) { $ts_array[0] = "0"; }
if($ts_array[0]>12) { $ts_array[0] = "12"; $offset = $ts_array[0].":".$ts_array[1]; }
if($ts_array[0]<-12) { $ts_array[0] = "-12"; $offset = $ts_array[0].":".$ts_array[1]; }
if(!is_numeric($ts_array[1])) { $ts_array[1] = "00"; }
if($ts_array[1]>59) { $ts_array[1] = "59"; $offset = $ts_array[0].":".$ts_array[1]; }
if($ts_array[1]<0) { $ts_array[1] = "00"; $offset = $ts_array[0].":".$ts_array[1]; }
$tsa = array("offset" => $offset, "hour" => $ts_array[0], "minute" => $ts_array[1]);
//$tsa['minute'] = $tsa['minute'] + $minoffset;
if($dst!="on"&&$dst!="off") { $dst = "off"; }
if($dst=="on") { if($dstake!="done") { 
	$dstake = "done"; $tsa['hour'] = $tsa['hour']+1; } }
$TCHour = $TCHour + $tsa['hour'];
$TCMinute = $TCMinute + $tsa['minute'];
return date($format,mktime($TCHour,$TCMinute,$TCSecond,$TCMonth,$TCDay,$TCYear)); }
	$Names['CK'] = "Christine";
define("_christine_", $Names['CK']);
// Change Time Stamp to a readable time
function TimeChange($format,$timestamp,$offset,$minoffset=null,$dst=null) {
return GMTimeChange($format,$timestamp,$offset,$minoffset,$dst); }
// Make a GMT Time Stamp
function GMTimeStamp() {
$GMTHour = gmdate("H");
$GMTMinute = gmdate("i");
$GMTSecond = gmdate("s");
$GMTMonth = gmdate("n");
$GMTDay = gmdate("d");
$GMTYear = gmdate("Y");
return mktime($GMTHour,$GMTMinute,$GMTSecond,$GMTMonth,$GMTDay,$GMTYear); }
function GMTimeStampS() { return time() - date('Z', time()); }
function GMTimeGet($format,$offset,$minoffset=null,$dst=null) { 
	return GMTimeChange($format,GMTimeStamp(),$offset,$minoffset,$dst); }
function GMTimeGetS($format,$offset,$minoffset=null,$dst=null) {
unset($dstake); $dstake = null;
if(!is_numeric($offset)) { $offset = "0"; }
if(!is_numeric($minoffset)) { $minoffset = "00"; }
$ts_array = explode(":",$offset);
if(count($ts_array)!=2) {
	if(!isset($ts_array[0])) { $ts_array[0] = "0"; }
	if(!isset($ts_array[1])) { $ts_array[1] = "00"; }
	$offset = $ts_array[0].":".$ts_array[1]; }
if(!is_numeric($ts_array[0])) { $ts_array[0] = "0"; }
if($ts_array[0]>12) { $ts_array[0] = "12"; $offset = $ts_array[0].":".$ts_array[1]; }
if($ts_array[0]<-12) { $ts_array[0] = "-12"; $offset = $ts_array[0].":".$ts_array[1]; }
if(!is_numeric($ts_array[1])) { $ts_array[1] = "00"; }
if($ts_array[1]>59) { $ts_array[1] = "59"; $offset = $ts_array[0].":".$ts_array[1]; }
if($ts_array[1]<0) { $ts_array[1] = "00"; $offset = $ts_array[0].":".$ts_array[1]; }
$tsa = array("offset" => $offset, "hour" => $ts_array[0], "minute" => $ts_array[1]);
//$tsa['minute'] = $tsa['minute'] + $minoffset;
if($dst!="on"&&$dst!="off") { $dst = "off"; }
if($dst=="on") { if($dstake!="done") { 
	$dstake = "done"; $tsa['hour'] = $tsa['hour']+1; } }
return date($format,mktime(gmdate('h')+$tsa['hour'],gmdate('i')+$tsa['minute'],gmdate('s'),gmdate('n'),gmdate('j'),gmdate('Y'))); }
// Get Server offset
function GetSeverZone() {
$TestHour1 = date("H");
@putenv("OTZ=".@getenv("TZ"));
@putenv("TZ=GMT");
$TestHour2 = date("H");
@putenv("TZ=".@getenv("OTZ"));
$TestHour3 = $TestHour1-$TestHour2;
return $TestHour3; }
function SeverOffSet() {
$TestHour1 = date("H");
$TestHour2 = gmdate("H");
$TestHour3 = $TestHour1-$TestHour2;
return $TestHour3; }
function SeverOffSetNew() {
return gmdate("g",mktime(0,date("Z"))); }
function gmtime() { return time() - (int) date('Z'); }
function file_get_source($filename,$return = FALSE) {
// Acts like highlight_file();
$phpsrc = file_get_contents($filename);
$phpsrcs = highlight_string($phpsrc,$return);
return $phpsrcs; }
function valid_get_source($filename) {
$phpsrcs = file_get_source($filename,TRUE);
// Change font tag to span tag for valid xhtml
$phpsrcs = preg_replace("/\<font color=\"(.*?)\"\>/i", "<span style=\"color: \\1;\">", $phpsrcs);
$phpsrcs = preg_replace("/\<\/font>/i", "</span>", $phpsrcs);
return $phpsrcs; }
function GetUserName($idu,$sqlt) {
$gunquery = query("select * from `".$sqlt."members` WHERE `id`=%i", array($idu));
$gunresult=mysql_query($gunquery);
$gunnum=mysql_num_rows($gunresult);
if($gunnum>0){
$UsersName=mysql_result($gunresult,$gunnum-1,"Name"); }
@mysql_free_result($gunresult);
return $UsersName; }
// hmac hash function
function hmac($data,$key,$hash='sha1',$blocksize=64) {
  if (strlen($key)>$blocksize) {
  $key=pack('H*',$hash($key)); }
  $key=str_pad($key, $blocksize, chr(0x00));
  $ipad=str_repeat(chr(0x36),$blocksize);
  $opad=str_repeat(chr(0x5c),$blocksize);
  return $hash(($key^$opad).pack('H*',$hash(($key^$ipad).$data))); }
// b64hmac hash function
function b64e_hmac($data,$key,$extdata,$hash='sha1',$blocksize=64) {
	$extdata2 = hexdec($extdata); $key = $key.$extdata2;
  return base64_encode(hmac($data,$key,$hash,$blocksize).$extdata); }
// salt hmac hash function
function salt_hmac($size1=4,$size2=6) {
$hprand = rand(4,6); $i = 0; $hpass = "";
while ($i < $hprand) {
$hspsrand = rand(1,2);
if($hspsrand!=1&&$hspsrand!=2) { $hspsrand=1; }
if($hspsrand==1) { $hpass .= chr(rand(48,57)); }
if($hspsrand==2) { $hpass .= chr(rand(65,70)); }
++$i; } return $hpass; }
/* is_empty by M at http://us2.php.net/manual/en/function.empty.php#74093 */
function is_empty($var) {
    if (((is_null($var) || rtrim($var) == "") &&
		$var !== false) || (is_array($var) && empty($var))) {
        return true; } else { return false; } }
// Hash two times with md5 and sha1
function PassHash2x($Text) {
$Text = md5($Text);
$Text = sha1($Text);
return $Text; }
// Hash two times with hmac-md5 and hmac-sha1
function PassHash2x2($data,$key,$extdata,$blocksize=64) {
$extdata2 = hexdec($extdata); $key = $key.$extdata2;
$Text = hmac($data,$key,"md5").$extdata; 
$Text = hmac($Text,$key,"sha1").$extdata;
return base64_encode($Text); }
function cp($infile,$outfile,$mode="w") { 
   $contents = file_get_contents($infile);
   $cpfp = fopen($outfile,$mode);
   fwrite($cpfp, $contents);
   fclose($cpfp);
   return true; }
/* str_ireplace for PHP below ver. 5 updated // 
//       by René Johnson - Cool Dude 2k      //
//      and upaded by René Johnson again     */
if(!function_exists('str_ireplace')) {
function str_ireplace($search,$replace,$subject) {
if(!is_array($search)&&is_array($replace)) {
	$search = array($search); }
if(is_array($search)&&!is_array($replace)) {
	$replace = array($replace); }
if(is_array($search)&&is_array($replace)) {
	$sc=count($search); $rc=count($replace); $sn=0;
	if($sc!=$rc) { return false; }
while ($sc > $sn) {
	$search[$sn] = preg_quote($search[$sn], "/");
	$subject = preg_replace("/".$search[$sn]."/i", $replace[$sn], $subject);
	++$sn; } }
if(!is_array($search)&&!is_array($replace)) {
$search = preg_quote($search, "/");
$subject = preg_replace("/".$search."/i", $replace, $subject); }
return $subject; } }
$foobar="fubar"; $$foobar="foobar";
// Debug info
function dump_included_files() {	return var_dump(get_included_files()); }
function count_included_files() {	return count(get_included_files()); }
function dump_extensions() {	return var_dump(get_loaded_extensions()); }
function count_extensions() {	return count(get_loaded_extensions()); }
?>
