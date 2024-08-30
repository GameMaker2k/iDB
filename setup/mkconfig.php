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
    iDB Installer made by Game Maker 2k - http://idb.berlios.net/

    $FileInfo: mkconfig.php - Last Update: 8/28/2024 SVN 1051 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="mkconfig.php"||$File3Name=="/mkconfig.php") {
	require('index.php');
	exit(); }
require_once('settings.php');
if(!isset($SetupDir['setup'])) { $SetupDir['setup'] = "setup/"; }
if(!isset($SetupDir['sql'])) { $SetupDir['sql'] = "setup/sql/"; }
if(!isset($SetupDir['convert'])) { $SetupDir['convert'] = "setup/convert/"; }
$_POST['DatabaseHost'] = $Settings['sqlhost'];
$_POST['DatabaseUserName'] = $Settings['sqluser'];
$_POST['DatabasePassword'] = $Settings['sqlpass'];
$Settings['charset'] = $_POST['charset'];
$Settings['sqltype'] = $_POST['DatabaseType'];
if(!isset($_POST['DefaultTheme'])) { $_POST['DefaultTheme'] = "iDB"; }
if(isset($_POST['DefaultTheme'])) { 
	$_POST['DefaultTheme'] = chack_themes($_POST['DefaultTheme']); }
$Settings['vercheck'] = 2;
if(!isset($_POST['SQLThemes'])) { $_POST['SQLThemes'] = "off"; }
if($_POST['SQLThemes']!="on"&&$_POST['SQLThemes']!="off") { 
	$_POST['SQLThemes'] = "off"; }
$disfunc = @ini_get("disable_functions");
$disfunc = @trim($disfunc);
$disfunc = @preg_replace("/([\\s+|\\t+|\\n+|\\r+|\\0+|\\x0B+])/i", "", $disfunc);
if($disfunc!="ini_set") { $disfunc = explode(",",$disfunc); }
if($disfunc=="ini_set") { $disfunc = array("ini_set"); }
$servtz = new DateTimeZone($_POST['YourOffSet']);
$servcurtime->setTimezone($servtz);
$usertz = new DateTimeZone($_POST['YourOffSet']);
$usercurtime->setTimezone($usertz);
?>
<tr class="TableRow3" style="text-align: center;">
<td class="TableColumn3" colspan="2">
<?php
$dayconv = array('second' => 1, 'minute' => 60, 'hour' => 3600, 'day' => 86400, 'week' => 604800, 'month' => 2630880, 'year' => 31570560, 'decade' => 315705600);
$_POST['tableprefix'] = strtolower($_POST['tableprefix']);
$_POST['tableprefix'] = preg_replace("/[^A-Za-z0-9_$]/", "", $_POST['tableprefix']);
if($_POST['tableprefix']==null||$_POST['tableprefix']=="_") { $_POST['tableprefix']="idb_"; }
if(!isset($_POST['sessprefix'])) { $_POST['sessprefix'] = null; }
if($_POST['sessprefix']==null||$_POST['sessprefix']=="_") { $_POST['sessprefix']="idb_"; }
$checkfile="settings.php";
@chmod("settings.php",0766);
@chmod("settingsbak.php",0766);
if (!is_writable($checkfile)) {
   echo "<br />Settings is not writable.";
   @chmod("settings.php",0766); $Error="Yes";
   @chmod("settingsbak.php",0766);
} else { /* settings.php is writable install iDB. ^_^ */ }
if (session_id()) { session_destroy(); }
session_name($_POST['tableprefix']."sess");
if(preg_match("/\/$/", $_POST['BoardURL'])<1) { 
	$_POST['BoardURL'] = $_POST['BoardURL']."/"; } 
function unparse_url($parsed_url) {
  $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
  $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
  $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
  $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
  $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
  $pass     = ($user || $pass) ? "$pass@" : '';
  $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
  $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
  $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
  return $scheme.$user.$pass.$host.$port.$path.$query.$fragment;
} 
$OrgBoardURL = $_POST['BoardURL'];
$PreBestURL = parse_url($_POST['BoardURL']);
$PreServURL = parse_url((isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/') + 1));
if($PreBestURL['host']=="localhost.url"&&str_replace("/", "", $PreBestURL['path'])=="localpath") {
   $PreBestURL['host'] = $PreServURL['host'];
   $PreBestURL['path'] = $PreServURL['path'];
   $_POST['BoardURL'] = unparse_url($PreBestURL); }
if($PreBestURL['host']=="localhost.url"&&str_replace("/", "", $PreBestURL['path'])!="localpath") {
   $PreBestURL['host'] = $PreServURL['host'];
   $_POST['BoardURL'] = unparse_url($PreBestURL); }
if($PreBestURL['host']!="localhost.url"&&str_replace("/", "", $PreBestURL['path'])=="localpath") {
   $PreBestURL['path'] = $PreServURL['path'];
   $_POST['BoardURL'] = unparse_url($PreBestURL); }
$OrgWebSiteURL = $_POST['WebURL'];
$PreWestURL = parse_url($_POST['WebURL']);
if($PreWestURL['host']=="localhost.url"&&str_replace("/", "", $PreWestURL['path'])=="localpath") {
   $PreWestURL['host'] = $PreServURL['host'];
   $PreWestURL['path'] = $PreServURL['path'];
   $_POST['WebURL'] = unparse_url($PreWestURL); }
if($PreWestURL['host']=="localhost.url"&&str_replace("/", "", $PreWestURL['path'])!="localpath") {
   $PreWestURL['host'] = $PreServURL['host'];
   $_POST['WebURL'] = unparse_url($PreWestURL); }
if($PreWestURL['host']!="localhost.url"&&str_replace("/", "", $PreWestURL['path'])=="localpath") {
   $PreWestURL['path'] = $PreServURL['path'];
   $_POST['WebURL'] = unparse_url($PreWestURL); }
$URLsTest = parse_url($_POST['BoardURL']);
$this_dir = $URLsTest['path'];
$Settings['enable_https'] = "off";
if($URLsTest['scheme']=="https") {
	$Settings['enable_https'] = "on"; }
session_set_cookie_params(0, $this_dir, $URLsTest['host']);
session_cache_limiter("private, no-cache, no-store, must-revalidate, pre-check=0, post-check=0, max-age=0");
header("Cache-Control: private, no-cache, no-store, must-revalidate, pre-check=0, post-check=0, max-age=0");
header("Pragma: private, no-cache, no-store, must-revalidate, pre-check=0, post-check=0, max-age=0");
header("Date: ".$utccurtime->format("D, d M Y H:i:s")." GMT");
header("Last-Modified: ".$utccurtime->format("D, d M Y H:i:s")." GMT");
header("Expires: ".$utccurtime->format("D, d M Y H:i:s")." GMT");
if (version_compare(phpversion(), '7.0', '<')) { session_start(); } else {
session_start([
    'use_trans_sid' => false,
    'use_cookies' => true,
    'use_only_cookies' => true,
    'gc_probability' => 1,
    'gc_divisor' => 100,
    'gc_maxlifetime' => 1440,
    //'hash_function' => 1,
    //'hash_bits_per_character' => 6,
    'name' => $_POST['tableprefix']."sess",
]); }
//@register_shutdown_function("session_write_close");
if (pre_strlen($_POST['AdminPasswords'])<"3") { $Error="Yes";
echo "<br />Your password is too small."; }
if (pre_strlen($_POST['AdminUser'])<"3") { $Error="Yes";
echo "<br />Your user name is too small."; }
if (pre_strlen($_POST['AdminUser'])<"3") { $Error="Yes";
echo "<br />Your user name is too small."; }
if (pre_strlen($_POST['AdminEmail'])<"3") { $Error="Yes";
echo "<br />Your email name is too small."; }
if (!filter_var($_POST['AdminEmail'], FILTER_VALIDATE_EMAIL)) { $Error="Yes";
echo "<br />Your email is not a valid email address."; }
if (!filter_var($_POST['BoardURL'], FILTER_VALIDATE_URL)) { $Error="Yes";
echo "<br />Your board url is not a valid web url."; }
if (!filter_var($OrgBoardURL, FILTER_VALIDATE_URL)) { $Error="Yes";
echo "<br />Your board url is not a valid web url."; }
if (!filter_var($_POST['WebURL'], FILTER_VALIDATE_URL)&&$_POST['WebURL']!="localhost") { $Error="Yes";
echo "<br />Your website url is not a valid web url."; }
if (!filter_var($OrgWebSiteURL, FILTER_VALIDATE_URL)&&$OrgWebSiteURL!="localhost") { $Error="Yes";
echo "<br />Your website url is not a valid web url."; }
if (pre_strlen($_POST['AdminPasswords'])>"60") { $Error="Yes";
echo "<br />Your password is too big."; }
if (pre_strlen($_POST['AdminUser'])>"30") { $Error="Yes";
echo "<br />Your user name is too big."; }
if ($_POST['AdminPasswords']!=$_POST['ReaPassword']) { $Error="Yes";
echo "<br />Your passwords did not match."; }
if($_POST['startblank']!="yes"&&$_POST['startblank']!="no") { $_POST['startblank'] = "yes"; }
if($_POST['HTMLType']=="html4") { $_POST['OutPutType'] = "html"; }
if($_POST['HTMLType']=="xhtml10") { $_POST['OutPutType'] = "xhtml"; }
if($_POST['HTMLType']=="xhtml11") { $_POST['OutPutType'] = "xhtml"; }
if($_POST['HTMLType']=="html5") { $_POST['OutPutType'] = "html"; }
if($_POST['HTMLType']=="xhtml5") { $_POST['OutPutType'] = "xhtml"; }
$_POST['BoardURL'] = htmlentities($_POST['BoardURL'], ENT_QUOTES, $Settings['charset']);
$_POST['BoardURL'] = remove_spaces($_POST['BoardURL']);
$_POST['BoardURL'] = addslashes($_POST['BoardURL']);
$OrgBoardURL = htmlentities($OrgBoardURL, ENT_QUOTES, $Settings['charset']);
$OrgBoardURL = remove_spaces($OrgBoardURL);
$OrgBoardURL = addslashes($OrgBoardURL);
$YourDate = $utccurtime->getTimestamp();
$YourEditDate = $YourDate + $dayconv['minute'];
$GSalt = salt_hmac(); $YourSalt = salt_hmac();
/* Fix The User Info for iDB */
$_POST['NewBoardName'] = stripcslashes(htmlspecialchars($_POST['NewBoardName'], ENT_QUOTES, $Settings['charset']));
//$_POST['NewBoardName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['NewBoardName']);
$_POST['NewBoardName'] = remove_spaces($_POST['NewBoardName']);
//$_POST['AdminPassword'] = stripcslashes(htmlspecialchars($_POST['AdminPassword'], ENT_QUOTES, $Settings['charset']));
//$_POST['AdminPassword'] = preg_replace("/\&amp;#(.*?);/is", "&#$1;", $_POST['AdminPassword']);
$_POST['AdminUser'] = stripcslashes(htmlspecialchars($_POST['AdminUser'], ENT_QUOTES, $Settings['charset']));
//$_POST['AdminUser'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['AdminUser']);
$_POST['AdminUser'] = remove_spaces($_POST['AdminUser']);
$_POST['AdminEmail'] = remove_spaces($_POST['AdminEmail']);
if(!function_exists('hash')&&!function_exists('hash_algos')) {
if($_POST['usehashtype']!="md5"&&
   $_POST['usehashtype']!="sha1") {
	$_POST['usehashtype'] = "sha1"; } }
if(function_exists('hash')&&function_exists('hash_algos')) {
if(!in_array($_POST['usehashtype'],hash_algos())) {
	$_POST['usehashtype'] = "sha1"; }
if($_POST['usehashtype']!="md2"&&
   $_POST['usehashtype']!="md4"&&
   $_POST['usehashtype']!="md5"&&
   $_POST['usehashtype']!="sha1"&&
   $_POST['usehashtype']!="sha224"&&
   $_POST['usehashtype']!="sha256"&&
   $_POST['usehashtype']!="sha384"&&
   $_POST['usehashtype']!="sha512"&&
   $_POST['usehashtype']!="sha3-224"&&
   $_POST['usehashtype']!="sha3-256"&&
   $_POST['usehashtype']!="sha3-384"&&
   $_POST['usehashtype']!="sha3-512"&&
   $_POST['usehashtype']!="ripemd128"&&
   $_POST['usehashtype']!="ripemd160"&&
   $_POST['usehashtype']!="ripemd256"&&
   $_POST['usehashtype']!="ripemd320"&&
   $_POST['usehashtype']!="bcrypt") {
	$_POST['usehashtype'] = "sha1"; } }
if($_POST['usehashtype']=="md2") { $iDBHashType = "iDBH2"; }
if($_POST['usehashtype']=="md4") { $iDBHashType = "iDBH4"; }
if($_POST['usehashtype']=="md5") { $iDBHashType = "iDBH5"; }
if($_POST['usehashtype']=="sha1") { $iDBHashType = "iDBH"; }
if($_POST['usehashtype']=="sha224") { $iDBHashType = "iDBH224"; }
if($_POST['usehashtype']=="sha256") { $iDBHashType = "iDBH256"; }
if($_POST['usehashtype']=="sha384") { $iDBHashType = "iDBH384"; }
if($_POST['usehashtype']=="sha512") { $iDBHashType = "iDBH512"; }
if($_POST['usehashtype']=="sha3-224") { $iDBHashType = "iDBH3224"; }
if($_POST['usehashtype']=="sha3-256") { $iDBHashType = "iDBH3256"; }
if($_POST['usehashtype']=="sha3-384") { $iDBHashType = "iDBH3384"; }
if($_POST['usehashtype']=="sha3-512") { $iDBHashType = "iDBH3512"; }
if($_POST['usehashtype']=="ripemd128") { $iDBHashType = "iDBHRMD128"; }
if($_POST['usehashtype']=="ripemd160") { $iDBHashType = "iDBHRMD160"; }
if($_POST['usehashtype']=="ripemd256") { $iDBHashType = "iDBHRMD256"; }
if($_POST['usehashtype']=="ripemd320") { $iDBHashType = "iDBHRMD320"; }
if($_POST['usehashtype']=="bcrypt") { $iDBHashType = "iDBCRYPT"; }
if ($_POST['AdminUser']=="Guest") { $Error="Yes";
echo "<br />You can not use Guest as your name."; }
/* We are done now with fixing the info. ^_^ */
$SQLStat = sql_connect_db($_POST['DatabaseHost'],$_POST['DatabaseUserName'],$_POST['DatabasePassword'],$_POST['DatabaseName']);
if(isset($_POST['sqlcollate'])) { $Settings['sql_collate'] = $_POST['sqlcollate']; }
if(isset($Settings['sql_collate'])&&!isset($Settings['sql_charset'])) {
	if($Settings['sql_collate']=="ascii_bin"||
		$Settings['sql_collate']=="ascii_generel_ci") {
		$Settings['sql_charset'] = "ascii"; }
	if($Settings['sql_collate']=="latin1_bin"||
		$Settings['sql_collate']=="latin1_general_ci"||
		$Settings['sql_collate']=="latin1_general_cs") {
		$Settings['sql_charset'] = "latin1"; }
	if($Settings['sql_collate']=="utf8mb3_bin"||
		$Settings['sql_collate']=="utf8mb3_general_ci"||
		$Settings['sql_collate']=="utf8mb3_unicode_ci") {
		$Settings['sql_charset'] = "utf8mb3"; }
	if($Settings['sql_collate']=="utf8mb4_bin"||
		$Settings['sql_collate']=="utf8mb4_general_ci"||
		$Settings['sql_collate']=="utf8mb4_unicode_ci") {
		$Settings['sql_charset'] = "utf8mb4"; } }
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
	if($Settings['sql_charset']=="utf8mb3" || $Settings['sql_charset']=="utf8mb4") {
	if($Settings['sql_collate']!="utf8mb3_bin"&&
		$Settings['sql_collate']!="utf8mb3_general_ci"&&
		$Settings['sql_collate']!="utf8mb3_unicode_ci"&&
		$Settings['sql_collate']!="utf8mb4_bin"&&
		$Settings['sql_collate']!="utf8mb4_general_ci"&&
		$Settings['sql_collate']!="utf8mb4_unicode_ci") {
		$Settings['sql_collate'] = "utf8mb4_unicode_ci"; } }
	if($Settings['sql_collate']=="utf8mb3_bin"||
		$Settings['sql_collate']=="utf8mb3_general_ci"||
		$Settings['sql_collate']=="utf8mb3_unicode_ci") {
		$Settings['sql_charset'] = "utf8mb3"; }
	if($Settings['sql_collate']=="utf8mb4_bin"||
		$Settings['sql_collate']=="utf8mb4_general_ci"||
		$Settings['sql_collate']=="utf8mb4_unicode_ci") {
		$Settings['sql_charset'] = "utf8mb4"; }
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
	$SQLCollate = "utf8mb4_unicode_ci";
	$SQLCharset = "utf8mb4"; } 
$Settings['sql_collate'] = $SQLCollate;
$Settings['sql_charset'] = $SQLCharset; }
sql_set_charset($SQLCharset,$SQLStat);
if($SQLStat===false) { $Error="Yes";
echo "<br />".sql_errorno($SQLStat)."\n"; }
if ($Error!="Yes") {
$ServerUUID = rand_uuid("rand");
$MyDay = $usercurtime->format("d");
$MyMonth = $usercurtime->format("m");
$MyYear = $usercurtime->format("Y");
$MyYear10 = $MyYear+10;
$YourDateEnd = $YourDate;
$EventMonth = $utccurtime->format("m");
$EventMonthEnd = $utccurtime->format("m");
$EventDay = $utccurtime->format("d");
$EventDayEnd = $utccurtime->format("d");
$EventYear = $utccurtime->format("Y");
$EventYearEnd = $utccurtime->format("Y");
$KarmaBoostDay = $EventMonth.$EventDay;
$Settings['idb_time_format'] = "g:i A";
if(!isset($_POST['iDBTimeFormat'])) { 
	$_POST['iDBTimeFormat'] = "g:i A"; }
if(isset($_POST['iDBTimeFormat'])) { 
	$_POST['iDBTimeFormat'] = convert_strftime($_POST['iDBTimeFormat']); }
$Settings['idb_date_format'] = "F j Y";
if(!isset($_POST['iDBDateFormat'])) { 
	$_POST['iDBDateFormat'] = "F j Y"; }
if(isset($_POST['iDBDateFormat'])) { 
	$_POST['iDBDateFormat'] = convert_strftime($_POST['iDBDateFormat']); }
if(!isset($_POST['iDBHTTPLogger'])) { 
	$_POST['iDBHTTPLogger'] = "off"; }
if(isset($_POST['iDBHTTPLogger'])&&$_POST['iDBHTTPLogger']!="on"&&$_POST['iDBHTTPLogger']!="off") {
	$_POST['iDBHTTPLogger'] = "off"; }
if(!isset($_POST['iDBLoggerFormat'])) { 
	$_POST['iDBLoggerFormat'] = "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\""; }
$Settings['idb_time_format'] = $_POST['iDBTimeFormat'];
$Settings['idb_date_format'] = $_POST['iDBDateFormat'];
$NewPassword = b64e_hmac($_POST['AdminPasswords'],$YourDate,$YourSalt,$_POST['usehashtype']);
//$Name = stripcslashes(htmlspecialchars($AdminUser, ENT_QUOTES, $Settings['charset']));
//$YourWebsite = "http://".$_SERVER['HTTP_HOST'].$this_dir."index.php?act=view";
$_POST['WebURL'] = htmlentities($_POST['WebURL'], ENT_QUOTES, $Settings['charset']);
$_POST['WebURL'] = remove_spaces($_POST['WebURL']);
$YourWebsite = $_POST['WebURL'];
$OrgWebSiteURL = htmlentities($OrgWebSiteURL, ENT_QUOTES, $Settings['charset']);
$OrgWebSiteURL = remove_spaces($OrgWebSiteURL);
$UserIP = $_SERVER['REMOTE_ADDR'];
$PostCount = 2;
$Email = "admin@".$_SERVER['HTTP_HOST'];
$GEmail = "guest@".$_SERVER['HTTP_HOST'];
$grand = rand(6,16); $i = 0; $gpass = "";
while ($i < $grand) {
$csrand = rand(1,3);
if($csrand!=1&&$csrand!=2&&$csrand!=3) { $csrand=1; }
if($csrand==1) { $gpass .= chr(rand(48,57)); }
if($csrand==2) { $gpass .= chr(rand(65,90)); }
if($csrand==3) { $gpass .= chr(rand(97,122)); }
++$i; } $GuestPassword = b64e_hmac($gpass,$YourDate,$GSalt,$_POST['usehashtype']);
$url_this_dir = "http://".$_SERVER['HTTP_HOST'].$this_dir."index.php?act=view";
$YourIP = $_SERVER['REMOTE_ADDR'];
/*
if($Settings['sqltype']!="cubrid") {
@unlink($SettDir['sqldumper'].'cubrid.php');
@unlink($SettDir['sql'].'cubrid.php'); }
if($Settings['sqltype']!="mysql"&&
	$Settings['sqltype']!="mysqli") {
@unlink($SettDir['sqldumper'].'mysql.php'); }
if($Settings['sqltype']!="mysql") {
@unlink($SettDir['sql'].'mysql.php'); }
if($Settings['sqltype']!="mysqli") {
@unlink($SettDir['sql'].'mysqli.php'); }
if($Settings['sqltype']!="pgsql") {
@unlink($SettDir['sqldumper'].'pgsql.php');
@unlink($SettDir['sql'].'pgsql.php'); }
if($Settings['sqltype']!="sqlite") {
if($Settings['sqltype']!="sqlite3"||$Settings['sqltype']=="pdo_sqlite3") {
@unlink($SettDir['sqldumper'].'sqlite.php'); }
@unlink($SettDir['sql'].'sqlite.php'); }
if($Settings['sqltype']!="sqlite3") {
@unlink($SettDir['sql'].'sqlite3.php'); }
*/
if($Settings['sqltype']=="mysql"||
	$Settings['sqltype']=="mysqli"||
	$Settings['sqltype']=="pdo_mysql") {
require($SetupDir['sql'].'mysql.php'); }
if($Settings['sqltype']=="pgsql") {
require($SetupDir['sql'].'pgsql.php'); }
if($Settings['sqltype']=="sqlite"||$Settings['sqltype']=="sqlite3"||$Settings['sqltype']=="pdo_sqlite3") {
require($SetupDir['sql'].'sqlite.php'); }
if($Settings['sqltype']=="cubrid") {
require($SetupDir['sql'].'cubrid.php'); }
if($_POST['SQLThemes']=="on") {
$OldThemeSet = $ThemeSet; 
$Settings['board_name'] = $_POST['NewBoardName'];
$skindir = dirname(realpath("sql.php"))."/".$SettDir['themes'];
if ($handle = opendir($skindir)) { $dirnum = null;
   while (false !== ($file = readdir($handle))) {
	   if ($dirnum==null) { $dirnum = 0; }
	   if (file_exists($skindir.$file."/info.php")) {
		   if ($file != "." && $file != "..") {
	   require($skindir.$file."/info.php");
       $themelist[$dirnum] =  $file;
	   ++$dirnum; } } }
   closedir($handle); asort($themelist);
   $themenum=count($themelist); $themei=0; 
   while ($themei < $themenum) {
   require($skindir.$themelist[$themei]."/settings.php");
   $query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."themes\" (\"Name\", \"ThemeName\", \"ThemeMaker\", \"ThemeVersion\", \"ThemeVersionType\", \"ThemeSubVersion\", \"MakerURL\", \"CopyRight\", \"WrapperString\", \"CSS\", \"CSSType\", \"FavIcon\", \"OpenGraph\", \"TableStyle\", \"MiniPageAltStyle\", \"PreLogo\", \"Logo\", \"LogoStyle\", \"SubLogo\", \"TopicIcon\", \"MovedTopicIcon\", \"HotTopic\", \"MovedHotTopic\", \"PinTopic\", \"AnnouncementTopic\", \"MovedPinTopic\", \"HotPinTopic\", \"MovedHotPinTopic\", \"ClosedTopic\", \"MovedClosedTopic\", \"HotClosedTopic\", \"MovedHotClosedTopic\", \"PinClosedTopic\", \"MovedPinClosedTopic\", \"HotPinClosedTopic\", \"MovedHotPinClosedTopic\", \"MessageRead\", \"MessageUnread\", \"Profile\", \"WWW\", \"PM\", \"TopicLayout\", \"AddReply\", \"FastReply\", \"NewTopic\", \"QuoteReply\", \"EditReply\", \"DeleteReply\", \"Report\", \"LineDivider\", \"ButtonDivider\", \"LineDividerTopic\", \"TitleDivider\", \"ForumStyle\", \"ForumIcon\", \"SubForumIcon\", \"RedirectIcon\", \"TitleIcon\", \"NavLinkIcon\", \"NavLinkDivider\", \"BoardStatsIcon\",  \"MemberStatsIcon\", \"BirthdayStatsIcon\", \"EventStatsIcon\", \"OnlineStatsIcon\", \"NoAvatar\", \"NoAvatarSize\") VALUES\n".
   "('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');", array($themelist[$themei], $ThemeSet['ThemeName'], $ThemeSet['ThemeMaker'], $ThemeSet['ThemeVersion'], $ThemeSet['ThemeVersionType'], $ThemeSet['ThemeSubVersion'], $ThemeSet['MakerURL'], $ThemeSet['CopyRight'], $ThemeSet['WrapperString'], $ThemeSet['CSS'], $ThemeSet['CSSType'], $ThemeSet['FavIcon'], $ThemeSet['OpenGraph'], $ThemeSet['TableStyle'], $ThemeSet['MiniPageAltStyle'], $ThemeSet['PreLogo'], $ThemeSet['Logo'], $ThemeSet['LogoStyle'], $ThemeSet['SubLogo'], $ThemeSet['TopicIcon'], $ThemeSet['MovedTopicIcon'], $ThemeSet['HotTopic'], $ThemeSet['MovedHotTopic'], $ThemeSet['PinTopic'], $ThemeSet['AnnouncementTopic'], $ThemeSet['MovedPinTopic'], $ThemeSet['HotPinTopic'], $ThemeSet['MovedHotPinTopic'], $ThemeSet['ClosedTopic'], $ThemeSet['MovedClosedTopic'], $ThemeSet['HotClosedTopic'], $ThemeSet['MovedHotClosedTopic'], $ThemeSet['PinClosedTopic'], $ThemeSet['MovedPinClosedTopic'], $ThemeSet['HotPinClosedTopic'], $ThemeSet['MovedHotPinClosedTopic'], $ThemeSet['MessageRead'], $ThemeSet['MessageUnread'], $ThemeSet['Profile'], $ThemeSet['WWW'], $ThemeSet['PM'], $ThemeSet['TopicLayout'], $ThemeSet['AddReply'], $ThemeSet['FastReply'], $ThemeSet['NewTopic'], $ThemeSet['QuoteReply'], $ThemeSet['EditReply'], $ThemeSet['DeleteReply'], $ThemeSet['Report'], $ThemeSet['LineDivider'], $ThemeSet['ButtonDivider'], $ThemeSet['LineDividerTopic'], $ThemeSet['TitleDivider'], $ThemeSet['ForumStyle'], $ThemeSet['ForumIcon'], $ThemeSet['SubForumIcon'], $ThemeSet['RedirectIcon'], $ThemeSet['TitleIcon'], $ThemeSet['NavLinkIcon'], $ThemeSet['NavLinkDivider'], $ThemeSet['BoardStatsIcon'], $ThemeSet['MemberStatsIcon'], $ThemeSet['BirthdayStatsIcon'], $ThemeSet['EventStatsIcon'], $ThemeSet['OnlineStatsIcon'], $ThemeSet['NoAvatar'], $ThemeSet['NoAvatarSize']));
   sql_query($query,$SQLStat);
   ++$themei; } }
sql_disconnect_db($SQLStat);
$ThemeSet = $OldThemeSet; }
$CHMOD = $_SERVER['PHP_SELF'];
$iDBRDate = $SVNDay[0]."/".$SVNDay[1]."/".$SVNDay[2];
$iDBRSVN = $VER2[2]." ".$SubVerN;
$LastUpdateS = "Last Update: ".$iDBRDate." ".$iDBRSVN;
$pretext = "<?php\n/*\n    This program is free software; you can redistribute it and/or modify\n    it under the terms of the GNU General Public License as published by\n    the Free Software Foundation; either version 2 of the License, or\n    (at your option) any later version.\n\n    This program is distributed in the hope that it will be useful,\n    but WITHOUT ANY WARRANTY; without even the implied warranty of\n    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n    Revised BSD License for more details.\n\n    Copyright 2004-".$SVNDay[2]." iDB Support - https://idb.osdn.jp/support/category.php?act=view&id=1\n    Copyright 2004-".$SVNDay[2]." Game Maker 2k - https://idb.osdn.jp/support/category.php?act=view&id=2\n    iDB Installer made by Game Maker 2k - http://idb.berlios.net/\n\n    \$FileInfo: settings.php & settingsbak.php - ".$LastUpdateS." - Author: cooldude2k \$\n*/\n";
$pretext2 = array("/*   Board Setting Section Begins   */\n\$Settings = array();","/*   Board Setting Section Ends  \n     Board Info Section Begins   */\n\$SettInfo = array();","/*   Board Setting Section Ends   \n     Board Dir Section Begins   */\n\$SettDir = array();","/*   Board Dir Section Ends   */");
$settcheck = "\$File3Name = basename(\$_SERVER['SCRIPT_NAME']);\nif (\$File3Name==\"settings.php\"||\$File3Name==\"/settings.php\"||\n    \$File3Name==\"settingsbak.php\"||\$File3Name==\"/settingsbak.php\") {\n    header('Location: index.php');\n    exit(); }\n";
$BoardSettings=$pretext2[0]."\n".
"\$Settings['sqlhost'] = '".$_POST['DatabaseHost']."';\n".
"\$Settings['sqldb'] = '".$_POST['DatabaseName']."';\n".
"\$Settings['sqltable'] = '".$_POST['tableprefix']."';\n".
"\$Settings['sqluser'] = '".$_POST['DatabaseUserName']."';\n".
"\$Settings['sqlpass'] = '".$_POST['DatabasePassword']."';\n".
"\$Settings['sqltype'] = '".$_POST['DatabaseType']."';\n".
"\$Settings['board_name'] = '".$_POST['NewBoardName']."';\n".
"\$Settings['idbdir'] = '".$idbdir."';\n".
"\$Settings['idburl'] = '".$OrgBoardURL."';\n".
"\$Settings['enable_https'] = '".$Settings['enable_https']."';\n".
"\$Settings['weburl'] = '".$OrgWebSiteURL."';\n".
"\$Settings['SQLThemes'] = '".$_POST['SQLThemes']."';\n".
"\$Settings['use_gzip'] = '".$_POST['GZip']."';\n".
"\$Settings['html_type'] = '".$_POST['HTMLType']."';\n".
"\$Settings['output_type'] = '".$_POST['OutPutType']."';\n".
"\$Settings['GuestGroup'] = 'Guest';\n".
"\$Settings['MemberGroup'] = 'Member';\n".
"\$Settings['ValidateGroup'] = 'Validate';\n".
"\$Settings['AdminValidate'] = 'off';\n".
"\$Settings['TestReferer'] = '".$_POST['TestReferer']."';\n".
"\$Settings['DefaultTheme'] = '".$_POST['DefaultTheme']."';\n".
"\$Settings['DefaultTimeZone'] = '".$_POST['YourOffSet']."';\n".
"\$Settings['start_date'] = ".$YourDate.";\n".
"\$Settings['idb_time_format'] = '".$Settings['idb_time_format']."';\n".
"\$Settings['idb_date_format'] = '".$Settings['idb_date_format']."';\n".
"\$Settings['use_hashtype'] = '".$_POST['usehashtype']."';\n".
"\$Settings['charset'] = '".$Settings['charset']."';\n".
"\$Settings['sql_collate'] = '".$Settings['sql_collate']."';\n".
"\$Settings['sql_charset'] = '".$Settings['sql_charset']."';\n".
"\$Settings['add_power_by'] = 'off';\n".
"\$Settings['send_pagesize'] = 'off';\n".
"\$Settings['max_posts'] = '10';\n".
"\$Settings['max_topics'] = '10';\n".
"\$Settings['max_memlist'] = '10';\n".
"\$Settings['max_pmlist'] = '10';\n".
"\$Settings['hot_topic_num'] = '15';\n".
"\$Settings['qstr'] = '&';\n".
"\$Settings['qsep'] = '=';\n".
"\$Settings['file_ext'] = '.php';\n".
"\$Settings['rss_ext'] = '.php';\n".
"\$Settings['js_ext'] = '.js';\n".
"\$Settings['showverinfo'] = 'on';\n".
"\$Settings['vercheck'] = 1;\n".
"\$Settings['enable_rss'] = 'on';\n".
"\$Settings['enable_search'] = 'on';\n".
"\$Settings['sessionid_in_urls'] = 'off';\n".
"\$Settings['fixpathinfo'] = 'off';\n".
"\$Settings['fixbasedir'] = 'off';\n".
"\$Settings['fixcookiedir'] = 'off';\n".
"\$Settings['fixredirectdir'] = 'off';\n".
"\$Settings['enable_pathinfo'] = 'off';\n".
"\$Settings['rssurl'] = 'off';\n".
"\$Settings['board_offline'] = 'off';\n".
"\$Settings['VerCheckURL'] = '';\n".
"\$Settings['IPCheckURL'] = '';\n".
"\$Settings['log_http_request'] = '".$_POST['iDBHTTPLogger']."';\n".
"\$Settings['log_config_format'] = '".$_POST['iDBLoggerFormat']."';\n".
"\$Settings['BoardUUID'] = '".base64_encode($ServerUUID)."';\n".
"\$Settings['KarmaBoostDays'] = '".$KarmaBoostDay."';\n".
"\$Settings['KBoostPercent'] = '6|10';\n".$pretext2[1]."\n".
"\$SettInfo['board_name'] = '".$_POST['NewBoardName']."';\n".
"\$SettInfo['Author'] = '".$_POST['AdminUser']."';\n".
"\$SettInfo['Keywords'] = '".$_POST['NewBoardName'].",".$_POST['AdminUser']."';\n".
"\$SettInfo['Description'] = '".$_POST['NewBoardName'].",".$_POST['AdminUser']."';\n".$pretext2[2]."\n".
"\$SettDir['maindir'] = '".$idbdir."';\n".
"\$SettDir['inc'] = 'inc/';\n".
"\$SettDir['logs'] = 'logs/';\n".
"\$SettDir['archive'] = 'archive/';\n".
"\$SettDir['misc'] = 'inc/misc/';\n".
"\$SettDir['sql'] = 'inc/misc/sql/';\n".
"\$SettDir['admin'] = 'inc/admin/';\n".
"\$SettDir['sqldumper'] = 'inc/admin/sqldumper/';\n".
"\$SettDir['mod'] = 'inc/mod/';\n".
"\$SettDir['mplayer'] = 'inc/mplayer/';\n".
"\$SettDir['themes'] = 'themes/';\n".$pretext2[3]."\n?>";
$BoardSettingsBak = $pretext.$settcheck.$BoardSettings;
$BoardSettings = $pretext.$settcheck.$BoardSettings;
$fp = fopen("settings.php","w+");
fwrite($fp, $BoardSettings);
fclose($fp);
//	cp("settings.php","settingsbak.php");
$fp = fopen("settingsbak.php","w+");
fwrite($fp, $BoardSettingsBak);
fclose($fp);
if($_POST['storecookie']=="true") {
if($URLsTest['host']!="localhost.url") {
setcookie("MemberName", $_POST['AdminUser'], time() + (7 * 86400), $this_dir, $URLsTest['host']);
setcookie("UserID", 1, time() + (7 * 86400), $this_dir, $URLsTest['host']);
setcookie("SessPass", $NewPassword, time() + (7 * 86400), $this_dir, $URLsTest['host']); }
if($URLsTest['host']=="localhost.url") {
setcookie("MemberName", $_POST['AdminUser'], time() + (7 * 86400), $this_dir, false);
setcookie("UserID", 1, time() + (7 * 86400), $this_dir, false);
setcookie("SessPass", $NewPassword, time() + (7 * 86400), $this_dir, false); } }
$chdel = true;
if($Error!="Yes") {
if($_POST['unlink']=="true") {
if($ConvertInfo['ConvertFile']!=null) { 
if(!@unlink($ConvertInfo['ConvertFile'])) { $chdel = false; } }
if(!@unlink($SetupDir['convert'].'index.php')) { $chdel = false; }
if(!@unlink($SetupDir['convert'].'info.php')) { $chdel = false; }
if(!@rmdir($SetupDir['convert'])) { $chdel = false; }
if(!@unlink($SetupDir['sql'].'cubrid.php')) { $chdel = false; }
if(!@unlink($SetupDir['sql'].'index.php')) { $chdel = false; }
if(!@unlink($SetupDir['sql'].'mysql.php')) { $chdel = false; }
if(!@unlink($SetupDir['sql'].'pgsql.php')) { $chdel = false; }
if(!@unlink($SetupDir['sql'].'sqlite.php')) { $chdel = false; }
if(!@rmdir($SetupDir['sql'])) { $chdel = false; }
if(!@unlink($SetupDir['setup'].'index.php')) { $chdel = false; }
if(!@unlink($SetupDir['setup'].'license.php')) { $chdel = false; }
if(!@unlink($SetupDir['setup'].'mkconfig.php')) { $chdel = false; }
if(!@unlink($SetupDir['setup'].'preinstall.php')) { $chdel = false; }
if(!@unlink($SetupDir['setup'].'presetup.php')) { $chdel = false; }
if(!@unlink($SetupDir['setup'].'setup.php')) { $chdel = false; }
if(!@unlink($SetupDir['setup'].'html5.php')) { $chdel = false; }
if(!@rmdir('setup')) { $chdel = false; }
if(!@unlink('install.php')) { $chdel = false; } } }
?><span class="TableMessage">
<br />Install Finish <a href="index.php?act=view">Click here</a> to goto board. ^_^</span>
<?php if($chdel===false) { ?><span class="TableMessage">
<br />Error: Cound not delete installer. Read readme.txt for more info.</span>
<?php } ?><br /><br />
</td>
</tr>
<?php } ?>
