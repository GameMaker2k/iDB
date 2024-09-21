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

    $FileInfo: functions.php - Last Update: 8/26/2024 SVN 1048 - Author: cooldude2k $
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
require($SettDir['sql']."sql.php");
require($SettDir['misc']."useragents.php");
require($SettDir['misc']."password.php");
/* 
if ($_GET['act']=="DeleteSession") { session_destroy(); }
if ($_GET['act']=="ResetSession") { session_unset(); }
if ($_GET['act']=="NewSessionID") { session_regenerate_id(); }
if ($_GET['act']=="PHPInfo") { phpinfo(); exit(); }
if ($_GET['act']=="phpinfo") { phpinfo(); exit(); }
if ($_GET['act']=="PHPCredits") { phpcredits(); exit(); }
if ($_GET['act']=="phpcredits") { phpcredits(); exit(); } 
*/
function header_protocol($header) {
if(isset($_SERVER['SERVER_PROTOCOL'])&&strstr($_SERVER['SERVER_PROTOCOL'],"/1.0")) {
	return "HTTP/1.0 ".$header; }
else {
	return "HTTP/1.1 ".$header; } }

// Helper function to get compressed output
function get_compressed_output($output, $gzip_type) {
    switch ($gzip_type) {
        case "brotli":
            return function_exists('brotli_compress') ? brotli_compress($output) : gzencode($output);
        case "zstd":
            return function_exists('zstd_compress') ? zstd_compress($output) : gzencode($output);
        case "deflate":
            return gzcompress($output);
        case "gzip":
        default:
            return gzencode($output);
    }
}

// Helper function to send headers and output the content
function send_output($output, $urlstatus, $gzip_type = "gzip", $use_gzip = false) {
    global $Settings;
    if ($use_gzip == "on") {
        $output = get_compressed_output($output, $gzip_type);
    }
    if(!isset($Settings['send_pagesize'])) {
        $Settings['send_pagesize'] = "off";
    }
    if ($Settings['send_pagesize'] == "on") {
        @header("Content-Length: " . strlen($output));
        @header("Content-MD5: " . base64_encode(md5($output)));
    }
    idb_log_maker($urlstatus, strlen($output));
    echo $output;
}

// Helper function to clean HTML using Tidy
function clean_html_output($output) {
    global $Settings;
	if(!isset($Settings['clean_html'])) {
		$Settings['clean_html'] = false;
	}
    if (extension_loaded('tidy') && $Settings['clean_html']) {
        $config = array(
            'indent' => true,
            'clean' => true,
            'output-xhtml' => ($Settings['output_type'] == "xhtml"),
            'show-body-only' => true,
            'wrap' => 0
        );
        $tidy = tidy_parse_string($output, $config, 'UTF8');
        $tidy->cleanRepair();
        return tidy_get_output($tidy);
    }
    return $output;
}

// Change the title and gzip page
function change_title($new_title, $use_gzip = "off", $gzip_type = "gzip") {
    if($use_gzip==null) {
        $use_gzip = false;
    }
    global $Settings, $urlstatus;
    $urlstatus = (isset($urlstatus) && is_numeric($urlstatus)) ? $urlstatus : 200;
    $gzip_type = in_array($gzip_type, ["gzip", "deflate", "brotli", "zstd"]) ? $gzip_type : "gzip";
    $output = trim(ob_get_clean());
    $output = preg_replace("/<title>(.*?)<\/title>/i", "<title>" . htmlentities($new_title, ENT_QUOTES, $Settings['charset']) . "</title>", $output);
    $meta_title = htmlentities($new_title, ENT_QUOTES, $Settings['charset']);
    $output = preg_replace("/<meta itemprop=\"title\" property=\"og:title\" content=\"(.*?)\" \/>/i", "<meta itemprop=\"title\" property=\"og:title\" content=\"" . $meta_title . "\" />", $output);
    $output = preg_replace("/<meta itemprop=\"title\" property=\"twitter:title\" content=\"(.*?)\" \/>/i", "<meta itemprop=\"title\" property=\"twitter:title\" content=\"" . $meta_title . "\" />", $output);
    $output = preg_replace("/<meta name=\"title\" content=\"(.*?)\" \/>/i", "<meta name=\"title\" content=\"" . $meta_title . "\" />", $output);
    $output = clean_html_output($output);
    send_output($output, $urlstatus, $gzip_type, $use_gzip);
}

// Fix amp => (&) to &amp; and gzip page
// Fix ampersand and gzip page
function fix_amp($use_gzip = "off", $gzip_type = "gzip") {
    if($use_gzip==null) {
        $use_gzip = false;
    }
    global $Settings, $urlstatus;
    $urlstatus = (isset($urlstatus) && is_numeric($urlstatus)) ? $urlstatus : 200;
    $gzip_type = in_array($gzip_type, ["gzip", "deflate", "brotli", "zstd"]) ? $gzip_type : "gzip";
    $output = trim(ob_get_clean());
    $SessName = session_name();
    if(!isset($Settings['qstr'])) {
        $Settings['qstr'] = "&";
    }
    $qstrcode = htmlentities($Settings['qstr'], ENT_QUOTES, $Settings['charset']);
    $output = str_replace($Settings['qstr'] . $SessName, $qstrcode . $SessName, $output);
    $output = clean_html_output($output);
    send_output($output, $urlstatus, $gzip_type, $use_gzip);
}

// GZip page for faster download
function gzip_page($use_gzip = "off", $gzip_type = "gzip") {
    if($use_gzip==null) {
        $use_gzip = false;
    }
    global $Settings, $urlstatus;
    $urlstatus = (isset($urlstatus) && is_numeric($urlstatus)) ? $urlstatus : 200;
    $gzip_type = in_array($gzip_type, ["gzip", "deflate", "brotli", "zstd"]) ? $gzip_type : "gzip";
    $output = trim(ob_get_clean());
    $output = clean_html_output($output);
    send_output($output, $urlstatus, $gzip_type, $use_gzip);
}

$foo="bar"; $$foo="foo";

// Kill bad vars for some functions
// Sanitize variable names to prevent injection of dangerous superglobals and characters
function killbadvars($varname) {
    // Remove dollar signs to prevent variable variables or eval-like behavior
    $varname = str_replace('$', '', $varname);
    // Define patterns for superglobals and other bad variable names to be removed
    $patterns = array(
        '/\b(_SERVER|_ENV|_COOKIE|_SESSION|_GET|_POST|_FILES|_REQUEST|GLOBALS)\b/i',
        '/\b(HTTP_SERVER_VARS|HTTP_ENV_VARS|HTTP_COOKIE_VARS|HTTP_SESSION_VARS|HTTP_GET_VARS|HTTP_POST_VARS|HTTP_POST_FILES)\b/i'
    );
    // Replace all matched patterns with an empty string
    $varname = preg_replace($patterns, '', $varname);
    return $varname;
}

// Trying to fix this bug. ^_^
// http://xforce.iss.net/xforce/xfdb/49697
if(!isset($Settings['DefaultTheme'])) {
	$Settings['DefaultTheme'] = "iDB"; }
// Change the text to icons(smileys)
function text2icons($Text,$sqlt,$link=null) {
global $SQLStat;
if(!isset($link)) { $link = $SQLStat; }
$melaniequery=sql_pre_query("SELECT * FROM \"".$sqlt."smileys\"", null);
$melanieresult=sql_query($melaniequery,$link);
$melanienum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$sqlt."smileys\"", null), $SQLStat);
$melanies=0;
while ($melanies < $melanienum) {
$melanieresult_array = sql_fetch_assoc($melanieresult);
$FileName=$melanieresult_array["FileName"];
$SmileName=$melanieresult_array["SmileName"];
$SmileText=$melanieresult_array["SmileText"];
$SmileDirectory=$melanieresult_array["Directory"];
$ShowSmile=$melanieresult_array["Display"];
$ReplaceType=$melanieresult_array["ReplaceCI"];
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
++$melanies; } return $Text; }

// Removes the bad stuff
// Disabling to relax harsh restrictions ^_^ 
// Remove specific bad or unnecessary HTML entities from the text
function remove_bad_entities($text) {
    /*// Array of HTML entities to remove (decimal, hex, and named versions)
    $entities = array(
        // Decimal entities
        '&#8238;', '&#8194;', '&#8195;', '&#8201;', '&#8204;', '&#8205;', '&#8206;', '&#8207;',
        // Hexadecimal entities
        '&#x202e;', '&#x2002;', '&#x2003;', '&#x2009;', '&#x200c;', '&#x200d;', '&#x200e;', '&#x200f;',
        // Named entities
        '&ensp;', '&emsp;', '&thinsp;', '&zwnj;', '&zwj;', '&lrm;', '&rlm;'
    );
    // Remove all listed entities by replacing them with an empty string
    $text = str_replace($entities, '', $text);*/
    return $text;
}

// Remove the bad stuff
// Remove unnecessary spaces, tabs, newlines, and control characters from the text
function remove_spaces($text) {
    // Trim whitespace characters (tabs, newlines, carriage returns, spaces) from both ends
    $text = preg_replace('/^\s+|\s+$/u', '', $text);
    // Replace multiple tabs, newlines, or carriage returns with a single space
    $text = preg_replace('/[\t\n\r]+/u', ' ', $text);
    // Replace multiple spaces with a single space
    $text = preg_replace('/\s{2,}/u', ' ', $text);
    // Trim control characters (ASCII 0-31)
    $text = trim($text, "\x00..\x1F");
    // Remove bad entities (assuming this is a user-defined function)
    $text = remove_bad_entities($text);
    return $text;
}

// Fix some chars
// Correct double-encoded HTML entities to their proper HTML representation
function fixbamps($text) {
    // Direct replacements for common HTML entities
    $directReplacements = array(
        '&amp;copy;'   => '&#169;',
        '&amp;reg;'    => '&reg;',
        '&amp;trade;'  => '&trade;',
        '&amp;quot;'   => '&quot;',
        '&amp;amp;'    => '&amp;',
        '&amp;lt;'     => '&lt;',
        '&amp;gt;'     => '&gt;',
        '&amp;aring;'  => '&aring;',
        '&amp;aelig;'  => '&aelig;',
        '&amp;ccedil;' => '&ccedil;',
        '&amp;eth;'    => '&eth;',
        '&amp;oslash;' => '&oslash;',
        '&amp;szlig;'  => '&szlig;',
        '&amp;thorn;'  => '&thorn;'
    );
    // Perform direct replacements
    $text = str_replace(array_keys($directReplacements), array_values($directReplacements), $text);
    // Correct encoded character entities with regex patterns
    $patternReplacements = array(
        '/&amp;([aeiouy])acute;/i'  => '&\1acute;',
        '/&amp;([aeiou])grave;/i'   => '&\1grave;',
        '/&amp;([aeiou])circ;/i'    => '&\1circ;',
        '/&amp;([aeiouy])uml;/i'    => '&\1uml;',
        '/&amp;([aon])tilde;/i'     => '&\1tilde;'
    );
    // Perform pattern-based replacements
    foreach ($patternReplacements as $pattern => $replacement) {
        $text = preg_replace($pattern, $replacement, $text);
    }
    // Fix double-encoded numeric entities
    $text = preg_replace('/&amp;#(x[a-f0-9]+|[0-9]+);/i', '&#$1;', $text);
    return $text;
}

$utshour = $dayconv['hour'];
$utsminute = $dayconv['minute'];

function GMTimeChange($format, $timestamp, $offset, $minoffset = 0, $dst = "off") {
    // Define conversion constants for hour and minute
    $secondsPerHour = 3600;
    $secondsPerMinute = 60;
    // Validate and normalize the offset input
    $ts_array = explode(":", $offset);
    $hourOffset = isset($ts_array[0]) && is_numeric($ts_array[0]) ? (int)$ts_array[0] : 0;
    $minuteOffset = isset($ts_array[1]) && is_numeric($ts_array[1]) && $ts_array[1] >= 0 ? (int)$ts_array[1] : 0;
    // Validate DST input
    $dst = ($dst === "on") ? 1 : 0;
    // Calculate total offset in seconds
    $totalOffset = ($hourOffset * $secondsPerHour) + ($minuteOffset * $secondsPerMinute) + ($minoffset * $secondsPerMinute);
    // Add DST adjustment (if applicable)
    if ($dst) {
        $totalOffset += $secondsPerHour; // Add one hour for DST
    }
    // Adjust the timestamp by the calculated offset
    $adjustedTimestamp = $timestamp + $totalOffset;
    // Return formatted time
    return date($format, $adjustedTimestamp);
}

// Change Time Stamp to a readable time
// Simplified wrapper for GMTimeChange with added default behavior or validation
function TimeChange($format, $timestamp, $offset, $minoffset = 0, $dst = "off") {
    // Optionally, add validation or handle special cases here before calling GMTimeChange
    return GMTimeChange($format, $timestamp, $offset, $minoffset, $dst);
}

/// Make a GMT timestamp for the current time
function GMTimeStamp() {
    // Get the current Unix timestamp and format it as GMT using gmdate()
    return time();
}

// Make a GMT Time Stamp alt version
function GMTimeStampS() { return time() - date('Z', time()); }

// Get GMT Time
// Get formatted GMT time with optional adjustments
function GMTimeGet($format, $offset, $minoffset = null, $dst = null, $taddon = null) {
    // Ensure $taddon is a numeric value or null
    $taddon = is_numeric($taddon) ? $taddon : null;
    // Calculate the timestamp with optional additional time adjustment
    $timestamp = GMTimeStamp() + ($taddon ?? 0);
    // Return the formatted time using GMTimeChange
    return GMTimeChange($format, $timestamp, $offset, $minoffset, $dst);
}

// Get GMT Time alt version
// Alternative version to get GMT time with optional adjustments
function GMTimeGetS($format, $offset, $minoffset = 0, $dst = "off") {
    // Constants for seconds in an hour and a minute
    $secondsPerHour = 3600;
    $secondsPerMinute = 60;
    // Validate and normalize the offset input
    $ts_array = explode(":", $offset);
    $hourOffset = isset($ts_array[0]) && is_numeric($ts_array[0]) ? (int)$ts_array[0] : 0;
    $minuteOffset = isset($ts_array[1]) && is_numeric($ts_array[1]) && $ts_array[1] >= 0 ? (int)$ts_array[1] : 0;
    // Validate DST input
    $dstAdjustment = ($dst === "on") ? $secondsPerHour : 0;
    // Calculate total offset in seconds
    $totalOffset = ($hourOffset * $secondsPerHour) + ($minuteOffset * $secondsPerMinute) + ($minoffset * $secondsPerMinute) + $dstAdjustment;
    // Get current GMT timestamp
    $gmtTimestamp = time() - date('Z');
    // Adjust the GMT timestamp by the total offset
    $adjustedTimestamp = $gmtTimestamp + $totalOffset;
    // Return the formatted time
    return date($format, $adjustedTimestamp);
}

// Get Server offset
// Get server's time zone offset from GMT in hours
function GetServerZone() {
    // Get the current timestamp and the GMT offset in seconds
    $timezoneOffsetSeconds = date('Z');
    // Convert seconds to hours
    $timezoneOffsetHours = $timezoneOffsetSeconds / 3600;
    return $timezoneOffsetHours;
}

// Get Server offset alt version
// Get server's time zone offset from GMT in hours (alternative version)
function SeverOffSet() {
    return GetServerZone();
}

// Get Server offset new version
// Get server's time zone offset from GMT in hours (correct version)
function SeverOffSetNew() {
    // Get the server's timezone offset in seconds and convert it to hours
    return date('Z') / 3600;
}

function gmtime() { 
    return time() - (int) date('Z'); 
}

// Acts like highlight_file();
// Acts like highlight_file(), with improved error handling
function file_get_source($filename, $return = FALSE) {
    // Check if the file exists and is readable
    if (!file_exists($filename) || !is_readable($filename)) {
        return $return ? "Error: Unable to read the file." : print "Error: Unable to read the file.";
    }
    // Get the PHP source code from the file
    $phpsrc = file_get_contents($filename);
    // Highlight the PHP source code
    $phpsrcs = highlight_string($phpsrc, $return);
    return $phpsrcs;
}

// Also acts like highlight_file(); but valid xhtml
// Acts like highlight_file() but returns valid XHTML
function valid_get_source($filename) {
    // Get the highlighted source code
    $phpsrcs = file_get_source($filename, TRUE);
    // Change <font> tags to <span> tags for valid XHTML
    $dom = new DOMDocument();
    @$dom->loadHTML('<?xml encoding="UTF-8">' . $phpsrcs);
    // Replace all <font> elements with <span> elements and transfer the "color" attribute to "style"
    foreach ($dom->getElementsByTagName('font') as $font) {
        $span = $dom->createElement('span', $font->nodeValue); // Create a new <span> element with the same content
        $span->setAttribute('style', 'color: ' . $font->getAttribute('color') . ';'); // Set the style attribute
        $font->parentNode->replaceChild($span, $font); // Replace <font> with <span>
    }
    // Return the XHTML-compliant highlighted source code
    return $dom->saveHTML();
}

// Check to see if the user is hidden/shy/timid. >_> | ^_^ | <_<
function GetUserName($idu,$sqlt,$link=null) { $UsersName = null;
global $SQLStat;
if(!isset($link)) { $link = $SQLStat; }
$gunquery = sql_pre_query("SELECT * FROM \"".$sqlt."members\" WHERE \"id\"=%i LIMIT 1", array($idu));
$gunresult=sql_query($gunquery,$link);
$gunnum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$sqlt."members\" WHERE \"id\"=%i LIMIT 1", array($idu)), $SQLStat);
// I'm now hidden from you. ^_^ | <_< I cant find you.
$UsersHidden = "yes";
if($gunnum>0){
$gunresult_array = sql_fetch_assoc($gunresult);
$UsersName=$gunresult_array["Name"];
// Am i still hidden. o_O <_< I can see you.
$UsersHidden=$gunresult_array["HiddenMember"]; }
sql_free_result($gunresult);
$UsersInfo['Name'] = $UsersName;
$UsersInfo['Hidden'] = $UsersHidden;
return $UsersInfo; }

// Fallback hash function for environments without the hash extension
// Drop-in replacement for the hash() function if it does not exist
if (!function_exists('hash')) {
    function hash($algo, $data, $raw_output = false) {
        // Supported algorithms for the fallback implementation
        $supported_algos = array('md5', 'sha1');

        // Check if the algorithm is supported
        if (!in_array($algo, $supported_algos)) {
            trigger_error("Unsupported hashing algorithm. Defaulting to 'md5'.", E_USER_WARNING);
            $algo = 'md5';  // Default to 'md5' if unsupported
        }

        // Hash the data using the specified algorithm
        $hash = ($algo == 'md5') ? md5($data) : sha1($data);

        // Handle raw output (binary) if requested
        return $raw_output ? hex2bin($hash) : $hash;
    }
}

// Drop-in replacement for the hash_hmac() function if it does not exist
if (!function_exists('hash_hmac')) {
    function hash_hmac($hash, $data, $key, $raw_output = false) {
        // Normalize SHA-3 algorithm names for compatibility with hash()
        $hash = str_replace(['sha3-224', 'sha3-256', 'sha3-384', 'sha3-512'], ['sha3224', 'sha3256', 'sha3384', 'sha3512'], $hash);

        // Check if hash() function is available
        if (!function_exists('hash')) {
            trigger_error("hash() function not available. Cannot perform HMAC.", E_USER_WARNING);
            return false;
        }

        // Determine the block size for the hash function
        $blocksize = 64; // Most hash block sizes are 64 bytes, except SHA-512 and some others
        if (in_array($hash, ['sha384', 'sha512', 'sha3384', 'sha3512'])) {
            $blocksize = 128; // For SHA-512 and similar algorithms
        }

        // Hash the key if it is longer than the block size
        if (strlen($key) > $blocksize) {
            $key = pack('H*', hash($hash, $key));
        }

        // Pad the key to the block size
        $key = str_pad($key, $blocksize, chr(0x00));
        $ipad = str_repeat(chr(0x36), $blocksize);
        $opad = str_repeat(chr(0x5c), $blocksize);

        // Perform inner and outer hash calculations manually
        $inner = hash($hash, ($key ^ $ipad) . $data);
        $hmac = hash($hash, ($key ^ $opad) . pack('H*', $inner));

        // Restore original SHA-3 names for consistency
        $hash = str_replace(['sha3224', 'sha3256', 'sha3384', 'sha3512'], ['sha3-224', 'sha3-256', 'sha3-384', 'sha3-512'], $hash);

        return $raw_output ? hex2bin($hmac) : $hmac;
    }
}

// Define hmac() function as a custom implementation
function hmac($data, $key, $hash = 'sha1', $blocksize = 64) {
    // If hash_hmac() is available, use it directly
    if (function_exists('hash_hmac')) {
        return hash_hmac($hash, $data, $key);
    }

    // Otherwise, use the custom implementation for HMAC
    // Normalize SHA-3 algorithm names for compatibility with hash()
    $hash = str_replace(['sha3-224', 'sha3-256', 'sha3-384', 'sha3-512'], ['sha3224', 'sha3256', 'sha3384', 'sha3512'], $hash);

    // Check if hash() function is available
    if (!function_exists('hash')) {
        trigger_error("hash() function not available. Cannot perform HMAC.", E_USER_WARNING);
        return false;
    }

    // Determine the block size for the hash function
    if (in_array($hash, ['sha384', 'sha512', 'sha3384', 'sha3512'])) {
        $blocksize = 128; // For SHA-512 and similar algorithms
    }

    // Hash the key if it is longer than the block size
    if (strlen($key) > $blocksize) {
        $key = pack('H*', hash($hash, $key));
    }

    // Pad the key to the block size
    $key = str_pad($key, $blocksize, chr(0x00));
    $ipad = str_repeat(chr(0x36), $blocksize);
    $opad = str_repeat(chr(0x5c), $blocksize);

    // Perform inner and outer hash calculations manually
    $inner = hash($hash, ($key ^ $ipad) . $data);
    $hmac = hash($hash, ($key ^ $opad) . pack('H*', $inner));

    // Restore original SHA-3 names for consistency
    $hash = str_replace(['sha3224', 'sha3256', 'sha3384', 'sha3512'], ['sha3-224', 'sha3-256', 'sha3-384', 'sha3-512'], $hash);

    return $hmac;
}

// Fallback function to return supported hash algorithms in this environment
if (!function_exists('hash_algos')) {
    function hash_algos() {
        return array('md5', 'sha1');
    }
}
	
// b64hmac hash function
function b64e_hmac($data,$key,$extdata,$hash='sha1',$blocksize=64) {
	$extdata2 = hexdec($extdata); $key = $key.$extdata2;
  return base64_encode(hmac($data,$key,$hash,$blocksize).$extdata); }
// b64hmac rot13 hash function
function b64e_rot13_hmac($data,$key,$extdata,$hash='sha1',$blocksize=64) {
	$data = str_rot13($data);
	$extdata2 = hexdec($extdata); $key = $key.$extdata2;
  return base64_encode(hmac($data,$key,$hash,$blocksize).$extdata); }
// salt hmac hash function
function salt_hmac($size1=6,$size2=12) {
$hprand = rand($size1,$size2); $i = 0; $hpass = "";
while ($i < $hprand) {
$hspsrand = rand(1,2);
if($hspsrand!=1&&$hspsrand!=2) { $hspsrand=1; }
if($hspsrand==1) { $hpass .= chr(rand(48,57)); }
/* if($hspsrand==2) { $hpass .= chr(rand(65,70)); } */
if($hspsrand==2) { $hpass .= chr(rand(97,102)); }
++$i; } return $hpass; }
/* is_empty by M at http://us2.php.net/manual/en/function.empty.php#74093 */
function is_empty($var) {
    if (((is_null($var) || rtrim($var) == "") &&
		$var !== false) || (is_array($var) && empty($var))) {
        return true; } else { return false; } }
// PHP 5 hash algorithms to functions :o 
// Automatically define hash functions for all supported algorithms
if (function_exists('hash') && function_exists('hash_algos')) {
    $algos = hash_algos();  // Get the list of available hash algorithms

    foreach ($algos as $algo) {
        // Format function names: Remove non-alphanumeric characters for function names
        $function_name = preg_replace('/[^a-zA-Z0-9]/', '', $algo);

        // Check if the function already exists to avoid redeclaration
        if (!function_exists($function_name)) {
            eval("
                function $function_name(\$data, \$raw_output = false) {
                    return hash('$algo', \$data, \$raw_output);
                }
            ");
        }
    }
}
// Try and convert IPB 2.0.0 style passwords to iDB style passwords
function hash2xkey($data,$key,$hash1='md5',$hash2='md5') {
  return $hash1($hash2($key).$hash2($data)); }
// Hash two times with md5 and sha1 for DF2k
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

// b64hmac hash function
function neo_b64e_hmac($data,$key,$extdata,$hash='sha1',$blocksize=64) {
	$extdata2 = hexdec($extdata); $key = $key.$extdata2;
  return base64_encode(password_hash($data.$extdata, PASSWORD_BCRYPT)); }

function neo_b64e_bcrypt_hmac($data,$key,$extdata,$hash='sha1',$blocksize=64) {
  return neo_b64e_hmac($data,$key,$extdata,$hash,$blocksize); }

function neo_b64e_argon2i_hmac($data,$key,$extdata,$hash='sha1',$blocksize=64) {
	$extdata2 = hexdec($extdata); $key = $key.$extdata2;
  return base64_encode(password_hash($data.$extdata, PASSWORD_ARGON2I)); }

function neo_b64e_argon2id_hmac($data,$key,$extdata,$hash='sha1',$blocksize=64) {
	$extdata2 = hexdec($extdata); $key = $key.$extdata2;
  return base64_encode(password_hash($data.$extdata, PASSWORD_ARGON2ID)); }

// b64hmac rot13 hash function
function neo_b64e_rot13_hmac($data,$key,$extdata,$hash='sha1',$blocksize=64) {
	$data = str_rot13($data);
	$extdata2 = hexdec($extdata); $key = $key.$extdata2;
  return base64_encode(password_hash($data.$extdata, PASSWORD_BCRYPT)); }

function neo_b64e_rot13_bcrypt_hmac($data,$key,$extdata,$hash='sha1',$blocksize=64) {
  return neo_b64e_rot13_hmac($data,$key,$extdata,$hash,$blocksize); }

function neo_b64e_rot13_argon2i_hmac($data,$key,$extdata,$hash='sha1',$blocksize=64) {
	$data = str_rot13($data);
	$extdata2 = hexdec($extdata); $key = $key.$extdata2;
  return base64_encode(password_hash($data.$extdata, PASSWORD_ARGON2I)); }

function neo_b64e_rot13_argon2id_hmac($data,$key,$extdata,$hash='sha1',$blocksize=64) {
	$data = str_rot13($data);
	$extdata2 = hexdec($extdata); $key = $key.$extdata2;
  return base64_encode(password_hash($data.$extdata, PASSWORD_ARGON2ID)); }

if (function_exists('password_hash')) {
    if (defined('PASSWORD_BCRYPT')) {
        function bcrypt($data) {
            return password_hash($data, PASSWORD_BCRYPT);
        }
    } else {
        function bcrypt($data) {
            return false;
        }
    }

    if (defined('PASSWORD_ARGON2I')) {
        function argon2i($data) {
            return password_hash($data, PASSWORD_ARGON2I);
        }
    } else {
        function argon2i($data) {
            return false;
        }
    }

    if (defined('PASSWORD_ARGON2ID')) {
        function argon2id($data) {
            return password_hash($data, PASSWORD_ARGON2ID);
        }
    } else {
        function argon2id($data) {
            return false;
        }
    }

    if (defined('PASSWORD_DEFAULT')) {
        function defpass($data) {
            return password_hash($data, PASSWORD_DEFAULT);
        }
    } else {
        function defpass($data) {
            return false;
        }
    }
}

/* is_empty by s rotondo90 at gmail com at https://www.php.net/manual/en/function.hash-equals.php#119576*/
if(!function_exists('hash_equals')) {
    function hash_equals($known_string, $user_string) {
        $ret = 0;
        if (strlen($known_string) !== strlen($user_string)) {
            $user_string = $known_string;
            $ret = 1;
        }
        $res = $known_string ^ $user_string;
        for ($i = strlen($res) - 1; $i >= 0; --$i) {
            $ret |= ord($res[$i]);
        }
        return !$ret;
    }
}
/* str_ireplace for PHP below ver. 5 updated // 
//       by Kazuki Przyborowski - Cool Dude 2k      //
//      and upaded by Kazuki Przyborowski again     */
// Optimized str_ireplace function for PHP versions that lack it
if (!function_exists('str_ireplace')) {
    function str_ireplace($search, $replace, $subject) {
        // Ensure both $search and $replace are arrays
        $search = (array) $search;
        $replace = (array) $replace;
        // If the number of search and replace items do not match, return false
        if (count($search) !== count($replace)) {
            return false;
        }
        // Iterate through each search item and perform the case-insensitive replacement
        foreach ($search as $key => $value) {
            // Escape special regex characters in the search value
            $pattern = '/' . preg_quote($value, '/') . '/i';
            // Perform the replacement
            $subject = preg_replace($pattern, $replace[$key], $subject);
        }
        return $subject;
    }
}
/*   Adds httponly to PHP below Ver. 5.2.0   // 
//       by Kazuki Przyborowski - Cool Dude 2k      */
function http_set_cookie($name, $value = null, $expire = null, $path = null, $domain = null, $secure = false, $httponly = false) {
    if (!isset($name)) {
        trigger_error("Error: You need to enter a name for the cookie.", E_USER_ERROR);
        return false;
    }
    if (!isset($expire)) {
        trigger_error("Error: You need to enter a time for the cookie to expire.", E_USER_ERROR);
        return false;
    }

    $expireGMT = gmdate("D, d-M-Y H:i:s \G\M\T", $expire);
    
    // If headers have already been sent, return false to avoid errors
    if (headers_sent()) {
        trigger_error("Error: Headers have already been sent. Cannot set cookie.", E_USER_WARNING);
        return false;
    }

    if ($httponly === false) {
        // Set the cookie without HttpOnly
        setcookie($name, $value, $expire, $path, $domain, $secure);
        return true;
    } 

    if (version_compare(PHP_VERSION, "5.2.0", ">=") && $httponly === true) {
        // PHP 5.2.0+ natively supports HttpOnly flag
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
        return true;
    }

    if (version_compare(PHP_VERSION, "5.2.0", "<") && $httponly === true) {
        // Manually construct the Set-Cookie header for older PHP versions
        $mkcookie = "Set-Cookie: " . rawurlencode($name) . "=" . rawurlencode($value);
        $mkcookie .= "; expires=" . $expireGMT;
        if ($path !== null) { $mkcookie .= "; path=" . $path; }
        if ($domain !== null) { $mkcookie .= "; domain=" . $domain; }
        if ($secure === true) { $mkcookie .= "; secure"; }
        $mkcookie .= "; HttpOnly";
        header($mkcookie);
        return true;
    }

    return false; // Fallback if nothing matched
}
$foobar="fubar"; $$foobar="foobar";
// Debug info
function dump_included_files($type="var_dump") {
	if(!isset($type)) { $type = "var_dump"; }
	if($type=="print_r") { return print_r(get_included_files()); }
	if($type=="var_dump") { return var_dump(get_included_files()); }
	if($type=="var_export") { return var_export(get_included_files()); } }
function count_included_files() {	return count(get_included_files()); }
function dump_extensions($type="var_dump") {
	if(!isset($type)) { $type = "var_dump"; }
	if($type=="print_r") { return print_r(get_loaded_extensions()); }
	if($type=="var_dump") { return var_dump(get_loaded_extensions()); }
	if($type=="var_export") { return var_export(get_loaded_extensions()); } }
function count_extensions() {	return count(get_loaded_extensions()); }
// human_filesize by evgenij at kostanay dot kz 
// URL: https://www.php.net/manual/en/function.filesize.php#120250
function human_filesize($bytes, $decimals = 2) {
    // Ensure $bytes is numeric, else return false
    if (!is_numeric($bytes)) {
        return false;
    }

    // Explicitly cast to an integer to avoid unexpected behavior
    $bytes = (int)$bytes;

    // Handle edge case where the size is zero
    if ($bytes === 0) {
        return '0 B';
    }

    // Use logarithm to determine the size factor (KB, MB, etc.)
    $factor = (int) floor(log($bytes, 1024));

    // Array for human-readable units
    $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB'];

    // Ensure factor is within the bounds of the array
    if ($factor > count($sizes) - 1) {
        $factor = count($sizes) - 1;
    }

    // Return the formatted size, using the correct unit
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . $sizes[$factor];
}

// Function to add timezone to the appropriate region
function addTimezoneToList($region, $location, &$zonelist) {
    if (count($location) == 2) {
        array_push($zonelist[strtolower($region)], [$location[1], implode('/', $location)]);
    } elseif (count($location) == 3) {
        array_push($zonelist[strtolower($region)], [$location[2] . ", " . $location[1], implode('/', $location)]);
    }
}

// Unified function to generate <option> tags for a given region
function generateOptions($region, $zonelist, $selectedTimezone) {
    $options = '';
    foreach ($zonelist[$region] as $timezone) {
        // Check if this option should be selected
        $isSelected = ($selectedTimezone == $timezone[1]) ? ' selected="selected"' : '';
        
        // Generate the <option> tag
        $options .= '<option' . $isSelected . ' value="' . htmlspecialchars($timezone[1]) . '">' 
                  . htmlspecialchars(str_replace("_", " ", $timezone[0])) . '</option>' . "\n";
    }
    return $options;
}

if(isset($Settings['DefaultTimeZone'])) {
 $gettzinfofromjs = $Settings['DefaultTimeZone']; } 
 else {
 $gettzinfofromjs = date_default_timezone_get(); }
if(isset($_COOKIE['getusertz']) && in_array($_COOKIE['getusertz'], DateTimeZone::listIdentifiers())) {
   $gettzinfofromjs = $_COOKIE['getusertz']; }
// http://www.tutorialspoint.com/php/php_function_timezone_identifiers_list.htm
// Retrieve all timezone identifiers
$timezone_identifiers = DateTimeZone::listIdentifiers();

// Initialize the timezone list array
$zonelist = [
    'africa' => [],
    'america' => [],
    'antarctica' => [],
    'arctic' => [],
    'asia' => [],
    'atlantic' => [],
    'australia' => [],
    'europe' => [],
    'indian' => [],
    'pacific' => [],
    'etcetera' => []
];

// Loop through timezone identifiers and categorize them
foreach ($timezone_identifiers as $timezone) {
    $zonelookup = explode("/", $timezone);
    
    if (count($zonelookup) == 1) {
        // Timezones without a region prefix go to 'etcetera'
        array_push($zonelist['etcetera'], [$timezone, $timezone]);
    } else {
        // Add timezone to the appropriate region
        addTimezoneToList($zonelookup[0], $zonelookup, $zonelist);
    }
}

?>
