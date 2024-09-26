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

    $FileInfo: function.php - Last Update: 8/30/2024 SVN 1058 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="function.php"||$File3Name=="/function.php") {
	require('index.php');
	exit(); }
require_once($SettDir['misc'].'functions.php');
require_once($SettDir['misc'].'ibbcode.php');
require_once($SettDir['misc'].'iuntar.php');
/* In php 6 and up the function get_magic_quotes_gpc dose not exist. 
   here we make a fake version that always sends false out. :P */
if(!function_exists('get_magic_quotes_gpc')) {
function get_magic_quotes_gpc() { return false; } }
/**
 * Undo the damage of magic_quotes_gpc if in effect
 * @return bool
 * @link http://www.charles-reace.com/blog/2010/07/13/undoing-magic-quotes/
 */
function fix_magic_quotes()
{
   if (get_magic_quotes_gpc()) {
      $func = create_function(
         '&$val, $key',
         'if(!is_numeric($val)) {$val = stripslashes($val);}'
      );
      array_walk_recursive($_GET, $func);
      array_walk_recursive($_POST, $func);
      array_walk_recursive($_COOKIE, $func);
   }
   return true;
}
fix_magic_quotes();
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
if(!isset($_SERVER['HTTP_REFERER'])) {
 $REFERERurl = null;
 $_SERVER['HTTP_REFERER'] = null; }
if(isset($_SERVER['HTTP_REFERER'])) {
 $REFERERurl = parse_url($_SERVER['HTTP_REFERER']); }
if(!isset($REFERERurl['host'])) { $REFERERurl['host'] = null; }
$URL['REFERER'] = $REFERERurl['host'];
$URL['HOST'] = $basecheck['host'];
$REFERERurl = null;
// Function made by Howard Yeend
// http://php.net/manual/en/function.trigger-error.php#92016
// http://www.puremango.co.uk/
function output_error($message, $level = E_USER_ERROR) {
    $backtrace = debug_backtrace();  // Capture the backtrace
    $caller = isset($backtrace[1]) ? $backtrace[1] : $backtrace[0];  // Get the caller info

    $callerFunction = isset($caller['function']) ? $caller['function'] : '(unknown function)';
    $callerFile = isset($caller['file']) ? $caller['file'] : '(unknown file)';
    $callerLine = isset($caller['line']) ? $caller['line'] : '(unknown line)';

    // Trigger an error with the detailed message
    trigger_error(
        $message . ' in <strong>' . $callerFunction . '</strong> called from <strong>' . $callerFile . '</strong> on line <strong>' . $callerLine . '</strong>' . "\n<br />error handler",
        $level
    );
}
// By s rotondo90 at gmail com at https://www.php.net/manual/en/function.random-int.php#119670
if (!function_exists('random_int')) {
    function random_int($min, $max) {
        if (!function_exists('mcrypt_create_iv')) {
            trigger_error(
                'mcrypt must be loaded for random_int to work', 
                E_USER_WARNING
            );
            return null;
        }
        
        if (!is_int($min) || !is_int($max)) {
            trigger_error('$min and $max must be integer values', E_USER_NOTICE);
            $min = (int)$min;
            $max = (int)$max;
        }
        
        if ($min > $max) {
            trigger_error('$max can\'t be lesser than $min', E_USER_WARNING);
            return null;
        }
        
        $range = $counter = $max - $min;
        $bits = 1;
        
        while ($counter >>= 1) {
            ++$bits;
        }
        
        $bytes = (int)max(ceil($bits/8), 1);
        $bitmask = pow(2, $bits) - 1;

        if ($bitmask >= PHP_INT_MAX) {
            $bitmask = PHP_INT_MAX;
        }

        do {
            $result = hexdec(
                bin2hex(
                    mcrypt_create_iv($bytes, MCRYPT_DEV_URANDOM)
                )
            ) & $bitmask;
        } while ($result > $range);

        return $result + $min;
    }
}
// http://us.php.net/manual/en/function.uniqid.php#94959
/**
  * Generates an UUID
  * 
  * @author     Andrew Moore
  * @url        http://us.php.net/manual/en/function.uniqid.php#94959
  */
function uuid_old($uuidver = "v4", $rndty = "rand", $namespace = null, $name = null) {
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
	$namespace = uuid_old("v4",$rndty); }
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

function uuid_new($uuidver = "v4", $rndty = "random_int", $namespace = null, $name = null) {
    // Validate the UUID version, default to "v4" if invalid
    if (!in_array($uuidver, ["v3", "v4", "v5"])) {
        $uuidver = "v4";
    }

    // Version 4 UUID (Randomly Generated)
    if ($uuidver == "v4") {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            $rndty(0, 0xffff), $rndty(0, 0xffff),
            $rndty(0, 0xffff),
            $rndty(0, 0x0fff) | 0x4000,  // Set version 4
            $rndty(0, 0x3fff) | 0x8000,  // Set variant DCE 1.1
            $rndty(0, 0xffff), $rndty(0, 0xffff), $rndty(0, 0xffff)
        );
    }

    // For v3 or v5 UUIDs, ensure namespace is a valid UUID
    if ($uuidver == "v3" || $uuidver == "v5") {
        if ($namespace === null || !uuid_is_valid($namespace)) {
            // If no valid namespace is provided, return false or generate a random v4 UUID as a fallback
            return false;
        }

        // Convert namespace to hexadecimal and binary string
        $nhex = str_replace(['-', '{', '}'], '', $namespace);
        $nstr = '';
        for ($i = 0; $i < strlen($nhex); $i += 2) {
            $nstr .= chr(hexdec($nhex[$i] . ($nhex[$i + 1] ?? '0')));
        }

        // Use a default random name if none is provided
        if ($name === null) {
            $name = bin2hex(random_bytes(16));  // Generate a 16-byte random string
        }

        // Calculate hash based on UUID version (v3 = MD5, v5 = SHA-1)
        if ($uuidver == "v3") {
            $uuidverid = 0x3000;
            $hash = hash('md5', $nstr . $name);
        } elseif ($uuidver == "v5") {
            $uuidverid = 0x5000;
            $hash = hash('sha1', $nstr . $name);
        }

        // Format the hash into the standard UUID structure
        return sprintf('%08s-%04s-%04x-%04x-%12s',
            substr($hash, 0, 8),    // time_low
            substr($hash, 8, 4),    // time_mid
            (hexdec(substr($hash, 12, 4)) & 0x0fff) | $uuidverid,  // time_hi_and_version
            (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,      // clk_seq_hi_res + variant
            substr($hash, 20, 12)   // node
        );
    }

    return false;
}

// Helper function to validate UUIDs
function uuid_is_valid($uuid) {
    return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?' .
                      '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
}

class UUID {
  public static function v3($namespace, $name) {
    if(!self::is_valid($namespace)) return false;

    // Get hexadecimal components of namespace
    $nhex = str_replace(array('-','{','}'), '', $namespace);

    // Binary Value
    $nstr = '';

    // Convert Namespace UUID to bits
    for($i = 0; $i < strlen($nhex); $i+=2) {
      $nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
    }

    // Calculate hash value
    $hash = md5($nstr . $name);

    return sprintf('%08s-%04s-%04x-%04x-%12s',

      // 32 bits for "time_low"
      substr($hash, 0, 8),

      // 16 bits for "time_mid"
      substr($hash, 8, 4),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 3
      (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

      // 48 bits for "node"
      substr($hash, 20, 12)
    );
  }

  public static function v4() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

      // 32 bits for "time_low"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff),

      // 16 bits for "time_mid"
      mt_rand(0, 0xffff),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 4
      mt_rand(0, 0x0fff) | 0x4000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      mt_rand(0, 0x3fff) | 0x8000,

      // 48 bits for "node"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
  }

  public static function v5($namespace, $name) {
    if(!self::is_valid($namespace)) return false;

    // Get hexadecimal components of namespace
    $nhex = str_replace(array('-','{','}'), '', $namespace);

    // Binary Value
    $nstr = '';

    // Convert Namespace UUID to bits
    for($i = 0; $i < strlen($nhex); $i+=2) {
      $nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
    }

    // Calculate hash value
    $hash = sha1($nstr . $name);

    return sprintf('%08s-%04s-%04x-%04x-%12s',

      // 32 bits for "time_low"
      substr($hash, 0, 8),

      // 16 bits for "time_mid"
      substr($hash, 8, 4),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 5
      (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

      // 48 bits for "node"
      substr($hash, 20, 12)
    );
  }

  public static function is_valid($uuid) {
    return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?'.
                      '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
  }
}
function uuid($uuidver = "v4", $rndty = "rand", $namespace = null, $name = null) {
    // Ensure the UUID version is valid, default to "v4"
    if(!in_array($uuidver, ["v3", "v4", "v5"])) {
        $uuidver = "v4";
    }

    // Generate a UUID based on the specified version
    if ($uuidver == "v3") {
        // Ensure namespace and name are provided for v3
        if ($namespace && $name) {
            return UUID::v3($namespace, $name); // v3 uses MD5 hashing
        }
        return false; // Return false if the required parameters are missing
    }

    if ($uuidver == "v4") {
        return UUID::v4(); // v4 generates a random UUID
    }

    if ($uuidver == "v5") {
        // Ensure namespace and name are provided for v5
        if ($namespace && $name) {
            return UUID::v5($namespace, $name); // v5 uses SHA-1 hashing
        }
        return false; // Return false if the required parameters are missing
    }

    return false; // If the UUID version is invalid or missing, return false
}

// By info at raymondrodgers dot com at https://www.php.net/manual/en/function.random-int.php#118636
function generateUUIDv4()
{
    if(version_compare(PHP_VERSION,'7.0.0', '<') )
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        
        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        
        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),
        
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,
        
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,
        
        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    else
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        
        // 32 bits for "time_low"
        random_int(0, 0xffff), random_int(0, 0xffff),
        
        // 16 bits for "time_mid"
        random_int(0, 0xffff),
        
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        random_int(0, 0x0fff) | 0x4000,
        
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        random_int(0, 0x3fff) | 0x8000,
        
        // 48 bits for "node"
        random_int(0, 0xffff), random_int(0, 0xffff), random_int(0, 0xffff)
        );
    }
}
function rand_uuid_old($rndty = "rand", $namespace = null, $name = null) {
$rand_array = array(1 => "v3", 2 => "v4", 3 => "v5");
if($name===null) { $name = salt_hmac(); }
$my_uuid = $rand_array[$rndty(1,3)];
if($my_uuid=="v4") { return uuid_old("v4",$rndty); }
if($my_uuid=="v3"||$my_uuid=="v5") {
return uuid_old($my_uuid,$rndty,$name); } }
function rand_uuid($rndty = "rand", $namespace = null, $name = null) {
$rand_array = array(1 => "v3", 2 => "v4", 3 => "v5");
if($name===null) { $name = salt_hmac(); }
$my_uuid = $rand_array[$rndty(1,3)];
if($my_uuid=="v4") { return uuid("v4",$rndty); }
if($my_uuid=="v3"||$my_uuid=="v5") {
return uuid($my_uuid,$rndty,$name); } }
// unserialize sessions variables
// By: jason@joeymail.net
// URL: http://us2.php.net/manual/en/function.session-decode.php#101687
function unserialize_session_old($data)
{
    if(  strlen( $data) == 0)
    {
        return array();
    }
    // match all the session keys and offsets
    preg_match_all('/(^|;|\})([a-zA-Z0-9_]+)\|/i', $data, $matchesarray, PREG_OFFSET_CAPTURE);
    $returnArray = array();
    $lastOffset = null;
    $currentKey = '';
    foreach ( $matchesarray[2] as $value )
    {
        $offset = $value[1];
        if(!is_null( $lastOffset))
        {
            $valueText = substr($data, $lastOffset, $offset - $lastOffset );
            $returnArray[$currentKey] = unserialize($valueText);
        }
        $currentKey = $value[0];
        $lastOffset = $offset + strlen( $currentKey )+1;
    }
    $valueText = substr($data, $lastOffset );
    $returnArray[$currentKey] = unserialize($valueText);
    return $returnArray;
}
function unserialize_session($data)
{
    if (strlen($data) === 0) {
        return array();
    }

    // Match all session keys and offsets using a regex
    if (!preg_match_all('/(^|;|\})([a-zA-Z0-9_]+)\|/i', $data, $matchesarray, PREG_OFFSET_CAPTURE)) {
        // Return an empty array if the session format is incorrect
        return array();
    }

    $returnArray = array();
    $lastOffset = null;
    $currentKey = '';

    foreach ($matchesarray[2] as $match) {
        $offset = $match[1];

        if ($lastOffset !== null) {
            // Extract and unserialize the previous value
            $valueText = substr($data, $lastOffset, $offset - $lastOffset);
            $unserializedValue = @unserialize($valueText);  // Suppress errors with @
            
            // Handle unserialization failure (optional handling)
            if ($unserializedValue === false && $valueText !== 'b:0;') {
                // b:0; is valid boolean false, so we avoid marking it as an error
                throw new Exception("Failed to unserialize session data for key: $currentKey");
            }

            $returnArray[$currentKey] = $unserializedValue;
        }

        // Update the current key and last offset
        $currentKey = $match[0];
        $lastOffset = $offset + strlen($currentKey) + 1;
    }

    // Unserialize the last value in the session data
    $valueText = substr($data, $lastOffset);
    $unserializedValue = @unserialize($valueText);
    
    if ($unserializedValue === false && $valueText !== 'b:0;') {
        throw new Exception("Failed to unserialize session data for key: $currentKey");
    }

    $returnArray[$currentKey] = $unserializedValue;

    return $returnArray;
}
function build_old_format_from_unserialized_data($sessionData)
{
    if (!is_array($sessionData)) {
        // Unserialize the data if it's not already an array
        $sessionData = unserialize($sessionData);
    }

    $sessionString = '';

    // Iterate through the session data
    foreach ($sessionData as $key => $value) {
        // Serialize each value
        $serializedValue = serialize($value);

        // Combine the key and serialized value in the old format
        $sessionString .= $key . '|' . $serializedValue;
    }

    return $sessionString;
}

// Make the Query String if we are not useing &=
function qstring_old($qstr=";",$qsep="=")
{ $_GET = array(); $_GET = null;
if (!isset($_SERVER['QUERY_STRING'])) {
$_SERVER['QUERY_STRING'] = getenv('QUERY_STRING'); }
ini_set("arg_separator.input", $qstr);
$_SERVER['QUERY_STRING'] = urldecode($_SERVER['QUERY_STRING']);
$preqs = explode($qstr,$_SERVER['QUERY_STRING']);
$qsnum = count($preqs); $qsi = 0;
while ($qsi < $qsnum) {
$preqst = explode($qsep,$preqs[$qsi],2);
$fix1 = array(" ",'$'); $fix2  = array("_","_");
$preqst[0] = str_replace($fix1, $fix2, $preqst[0]);
$preqst[0] = killbadvars($preqst[0]);
if($preqst[0]!=null) {
$_GET[$preqst[0]] = $preqst[1]; }
++$qsi; } return true; }
function qstring($qstr = ';', $qsep = '=')
{
    // Clear $_GET properly
    $_GET = array();

    // Check if query string is available
    if (!isset($_SERVER['QUERY_STRING'])) {
        $_SERVER['QUERY_STRING'] = getenv('QUERY_STRING');
    }

    // If query string is still not set, return false early
    if (!$_SERVER['QUERY_STRING']) {
        return false;
    }

    // Apply urldecode to handle encoded characters
    $queryString = urldecode($_SERVER['QUERY_STRING']);

    // Split query string into individual parameters using $qstr as the separator
    $preqs = explode($qstr, $queryString);

    // Iterate through each query parameter
    foreach ($preqs as $param) {
        // Split parameter into key and value by the $qsep separator (only split the first occurrence)
        $preqst = explode($qsep, $param, 2);

        // If no key is present or the split failed (e.g., no '=' in the parameter), skip this entry
        if (empty($preqst[0])) {
            continue;
        }

        // Replace unwanted characters in the key (e.g., spaces or $ signs)
        $fix1 = array(" ", '$');
        $fix2 = array("_", "_");
        $key = str_replace($fix1, $fix2, $preqst[0]);

        // Sanitize the key using the killbadvars function (ensure this function is safe)
        $key = killbadvars($key);

        // If the sanitized key is null or invalid, skip this entry
        if ($key === null || $key === '') {
            continue;
        }

        // Handle cases where a parameter might not have a value
        $value = isset($preqst[1]) ? $preqst[1] : null;

        // Assign the key-value pair to the $_GET array
        $_GET[$key] = $value;
    }

    return true;
}
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
if(isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO']==null) {
	if(getenv('PATH_INFO')!=null&&getenv('PATH_INFO')!="1") {
$_SERVER['PATH_INFO'] = getenv('PATH_INFO'); }
if(getenv('PATH_INFO')==null) {
$myscript = $_SERVER['SCRIPT_NAME'];
$myphpath = $_SERVER['PHP_SELF'];
$mypathinfo = str_replace($myscript, "", $myphpath);
@putenv("PATH_INFO=".$mypathinfo); } }
// Change raw post data to POST array
// Not sure why I made but alwell. :P 
function parse_post_data_old()
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
// Manually parse raw POST data from php://input and populate the $_POST array.
// Useful for non-standard POST content types or when the default PHP POST handling is insufficient.
// This function decodes URL-encoded data, replaces certain characters in the keys, 
// and sanitizes the keys using killbadvars().
function parse_post_data()
{
    // Properly clear $_POST without setting it to null
    $_POST = array();

    // Retrieve the raw POST data
    $postdata = file_get_contents("php://input");
    if (!$postdata) {
        return false; // Return false if there's no POST data
    }

    // Decode the URL-encoded POST data
    $postdata = urldecode($postdata);

    // Split the POST data into key-value pairs
    $preqs = explode("&", $postdata);

    // Iterate over each key-value pair
    foreach ($preqs as $param) {
        // Split the key-value pair by the "=" sign
        $preqst = explode("=", $param, 2);

        // If no key is found, skip the iteration
        if (empty($preqst[0])) {
            continue;
        }

        // Replace spaces and dollar signs in the key
        $fix1 = array(" ", '$');
        $fix2 = array("_", "_");
        $key = str_replace($fix1, $fix2, $preqst[0]);

        // Sanitize the key using killbadvars
        $key = killbadvars($key);

        // If the key is invalid, skip it
        if ($key === null || $key === '') {
            continue;
        }

        // Set the key-value pair in the $_POST array
        $value = isset($preqst[1]) ? $preqst[1] : null;
        $_POST[$key] = $value;
    }

    return true;
}
// Change Path info to Get Vars :
function mrstring_old() {
if($_SERVER['PATH_INFO']==null) {
$urlvar = explode('/',$_SERVER['PATH_INFO']); }
else {
$urlvar = null; }
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
// Change PATH_INFO to $_GET variables
function mrstring() {
    // Check if PATH_INFO exists
    if ($_SERVER['PATH_INFO'] != null) {
        $urlvar = explode('/', trim($_SERVER['PATH_INFO'], '/')); // Remove leading/trailing slashes
    } else {
        return false; // Return false if there's no PATH_INFO
    }

    $num = count($urlvar);
    $i = 0; // Start from index 0

    while ($i < $num) {
        if (isset($urlvar[$i])) {
            // Sanitize and prepare key
            $fix1 = array(" ", '$');
            $fix2 = array("_", "_");
            $key = str_replace($fix1, $fix2, $urlvar[$i]);
            $key = killbadvars($key);

            // Set the next element as the value, if it exists
            $value = isset($urlvar[$i + 1]) ? $urlvar[$i + 1] : null;

            // Add to $_GET
            $_GET[$key] = $value;

            // Skip to the next key-value pair
            $i += 2;
        }
    }

    return true;
}
// Redirect to another file with ether timed or nontimed redirect
function redirect_old($type,$file,$time=0,$url=null,$dbsr=true) {
if($type!="location"&&$type!="refresh") { $type="location"; }
if($url!=null) { $file = $url.$file; }
if($dbsr===true) { $file = str_replace("//", "/", $file); }
if($type=="refresh") { header("Refresh: ".$time."; URL=".$file); }
if($type=="location") { session_write_close(); 
header("Location: ".$file); } return true; }
function redirects_old($type,$url,$time=0) {
if($type!="location"&&$type!="refresh") { $type=="location"; }
if($type=="refresh") { header("Refresh: ".$time."; URL=".$url); }
if($type=="location") { idb_log_maker(302,"-"); }
if($type=="location") { header("Location: ".$url); } return true; }
// Redirect to another file with either a timed or immediate redirect
function redirect($type, $file, $time = 0, $url = null, $dbsr = true) {
    // Validate type and default to "location"
    if ($type != "location" && $type != "refresh") {
        $type = "location";
    }

    // If a base URL is provided, prepend it to the file
    if ($url != null) {
        $file = rtrim($url, '/') . '/' . ltrim($file, '/'); // Ensure URL is well-formed
    }

    // Sanitize double slashes in the path, but avoid modifying valid URLs
    if ($dbsr === true && parse_url($file, PHP_URL_SCHEME) === null) {
        $file = str_replace("//", "/", $file); // Only apply if there's no scheme (http, https, etc.)
    }

    // Handle timed redirect using Refresh header
    if ($type == "refresh") {
        header("Refresh: " . (int) $time . "; URL=" . $file);
    }

    // Handle immediate redirect using Location header
    if ($type == "location") {
        session_write_close(); // Ensure session data is saved
        header("Location: " . $file);
        exit(); // Ensure script stops after redirect
    }

    return true;
}

// Simplified redirect with logging
function redirects($type, $url, $time = 0) {
    // Validate type and default to "location"
    if ($type != "location" && $type != "refresh") {
        $type = "location";
    }

    // Handle timed redirect using Refresh header
    if ($type == "refresh") {
        header("Refresh: " . (int) $time . "; URL=" . $url);
    }

    // Handle immediate redirect using Location header, with logging
    if ($type == "location") {
        if (function_exists('idb_log_maker')) {
            idb_log_maker(302, "-"); // Optional logging, if function exists
        }
        header("Location: " . $url);
        exit(); // Ensure script stops after redirect
    }

    return true;
}

// Function to log the web access
function logWebAccess($logFile, $format = '%h %l %u %t "%r" %>s %b "%{Referer}i" "%{User-Agent}i"')
{
    // Get the necessary information from the $_SERVER superglobal array
    $ip = $_SERVER['REMOTE_ADDR'];
    $timestamp = date('d/M/Y:H:i:s O');
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];
    $protocol = $_SERVER['SERVER_PROTOCOL'];
    $status = http_response_code();
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '-';
    $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '-';

    // Replace placeholders in the log format with actual values
    $placeholders = array(
        '%h' => $ip,
        '%l' => '-',
        '%u' => '-',
        '%t' => "[$timestamp]",
        '%r' => "$method $uri $protocol",
        '%>s' => $status,
        '%b' => '-',
        '%{Referer}i' => $referer,
        '%{User-Agent}i' => $userAgent,
    );
    $logEntry = strtr($format, $placeholders);

    // Open the log file in append mode
    $logFileHandle = fopen($logFile, 'a');

    if ($logFileHandle) {  // Check if the file opened successfully
        // Acquire an exclusive lock
        if (flock($logFileHandle, LOCK_EX)) {
            // Write the log entry to the file
            fwrite($logFileHandle, $logEntry . PHP_EOL);
            
            // Release the lock
            flock($logFileHandle, LOCK_UN);
        } else {
            // Handle the error if lock acquisition fails
            error_log("Could not lock the log file: " . $logFile);
        }

        // Close the log file
        fclose($logFileHandle);
    } else {
        // Handle the error if file opening fails
        error_log("Could not open the log file: " . $logFile);
    }
}
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
	$melanie1 = explode("&",$attbs);
	$melanienum=count($melanie1);
	$melaniei=0; $attblist = null;
	while ($melaniei < $melanienum) {
	$melanie2 = explode("=",$melanie1[$melaniei]);
	if($melanie2[0]!=null||$melanie2[1]!=null) {
	$attblist = $attblist.' '.$melanie2[0].'="'.$melanie2[1].'"'; }
	++$melaniei; }
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
function pre_url2link_old($matches) {
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
function url2link_old($string) {
return preg_replace_callback("/(?<![\">])\b([a-zA-Z]+)\:\/\/([a-z0-9\-\.@\:]+)(\:[0-9]+)?\/([A-Za-z0-9\.\/%\?\!\$\(\)\*\-_\:;,\+\@~]+)?(\?)?([A-Za-z0-9\.\/%&\=\?\!\$\(\)\*\-_\:;,\+\@~]+)?(\#)?([A-Za-z0-9\.\/%&\=\?\!\$\(\)\*\-_\:;,\+\@~]+)?/is", "pre_url2link_old", $string); }
function urlcheck_old($string) {
global $BoardURL;
$retnum = preg_match_all("/([a-zA-Z]+)\:\/\/([a-z0-9\-\.@\:]+)(\:[0-9]+)?\/([A-Za-z0-9\.\/%\?\!\$\(\)\*\-_\:;,\+\@~]+)?(\?)?([A-Za-z0-9\.\/%&\=\?\!\$\(\)\*\-_\:;,\+\@~]+)?(\#)?([A-Za-z0-9\.\/%&\=\?\!\$\(\)\*\-_\:;,\+\@~]+)?/is", $string, $urlcheck); 
if(isset($urlcheck[0][0])) { $url = $urlcheck[0][0]; }
if(!isset($urlcheck[0][0])) { $url = $BoardURL; }
return $url; }
// Change URLs to Links
function pre_url2link($matches) {
    global $BoardURL;
    
    // Parse the board URL and the matched URL
    $burlCHCK = parse_url($BoardURL);
    $urlCHCK = parse_url($matches[0]);
    
    // Determine if the link should open in a new window
    $opennew = $urlCHCK['host'] !== $burlCHCK['host'];
    
    // Rebuild the URL
    $outurl = $urlCHCK['scheme'] . "://";
    
    // If user credentials exist, add them to the URL
    if (isset($urlCHCK['user'])) {
        $outurl .= $urlCHCK['user'];
        if (isset($urlCHCK['pass'])) {
            $outurl .= ":" . $urlCHCK['pass'];
        }
        $outurl .= "@";
    }

    // Add host and path
    $outurl .= $urlCHCK['host'] ?? '';
    $outurl .= $urlCHCK['path'] ?? '/';
    
    // If query or fragment exists, append them
    if (isset($urlCHCK['query'])) {
        $outurl .= '?' . str_replace(' ', '+', $urlCHCK['query']);
    }
    if (isset($urlCHCK['fragment'])) {
        $outurl .= '#' . str_replace(' ', '+', $urlCHCK['fragment']);
    }

    // Generate the HTML anchor tag
    $outlink = "<a href=\"{$outurl}\"" . ($opennew ? " onclick=\"window.open(this.href); return false;\"" : "") . ">{$outurl}</a>";
    
    return $outlink;
}

function url2link($string) {
    // Optimized regex for URL matching
    return preg_replace_callback(
        "/\b([a-zA-Z]+):\/\/([a-zA-Z0-9\-\.@\:]+)(\:[0-9]+)?(\/[A-Za-z0-9\.\/%\?\!\$\(\)\*\-_\:;,\+\@~]*)?(\?)?([A-Za-z0-9\.\/%&=\?\!\$\(\)\*\-_\:;,\+\@~]*)?(\#)?([A-Za-z0-9\.\/%&=\?\!\$\(\)\*\-_\:;,\+\@~]*)?/is",
        "pre_url2link",
        $string
    );
}

function urlcheck($string) {
    global $BoardURL;
    
    // Simplified regex for extracting the first URL
    preg_match("/\b([a-zA-Z]+):\/\/([a-zA-Z0-9\-\.@\:]+)(\:[0-9]+)?(\/[A-Za-z0-9\.\/%\?\!\$\(\)\*\-_\:;,\+\@~]*)?(\?)?([A-Za-z0-9\.\/%&=\?\!\$\(\)\*\-_\:;,\+\@~]*)?(\#)?([A-Za-z0-9\.\/%&=\?\!\$\(\)\*\-_\:;,\+\@~]*)?/is", $string, $urlcheck);
    
    // Return the found URL or the default Board URL
    return $urlcheck[0] ?? $BoardURL;
}
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
	   if (is_dir($ckskindir.$ckfile)&&file_exists($ckskindir.$ckfile."/info.php")) {
		   if ($ckfile != "." && $ckfile != "..") {
	   //require($ckskindir.$ckfile."/info.php");
       $cktheme[$dirnum] =  $ckfile;
	   ++$dirnum; } } }
   closedir($handle); asort($cktheme); }
$theme=preg_replace("/(.*?)\.\/(.*?)/", $BoardTheme, $theme);
if(!in_array($theme,$cktheme)||strlen($theme)>26) {
	$theme = $BoardTheme; } return $theme; }
// Make a url with query string
function url_maker_old($file="index",$ext=".php",$qvarstr=null,$qstr=";",$qsep="=",$prexqstr=null,$exqstr=null,$fixhtml=true) {
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
// Move append_query() outside to avoid redeclaration error
function append_query($queryStr, $qstr, $qsep, &$fileurl) {
    if ($queryStr) {
        $params = explode("&", $queryStr);
        foreach ($params as $index => $param) {
            [$key, $value] = explode("=", $param);
            $key = urlencode($key);
            $value = urlencode($value);
            $fileurl .= ($qstr === "/") ? "$key/$value/" : "$key$qsep$value";
            if ($index < count($params) - 1 && $qstr !== "/") {
                $fileurl .= $qstr;
            }
        }
    }
}

// Now modify url_maker function
function url_maker($file = "index", $ext = ".php", $qvarstr = null, $qstr = ";", $qsep = "=", $prexqstr = null, $exqstr = null, $fixhtml = true) {
    global $sidurls, $icharset, $debug_on;

    // Handle the file extension
    if ($ext === "noext" || $ext === "no ext" || $ext === "no+ext") {
        $ext = null;
    } else {
        $ext = $ext ?? ".php";
    }
    $file = $file . $ext;

    // Build the base file URL
    $fileurl = $file;

    // If sidurls is on, include the session ID in the query string
    if ($sidurls === "on" && $qstr !== "/" && defined('SID')) {
        $qvarstr = $qvarstr ? SID . "&" . $qvarstr : SID;
    }

    // Add debug mode query string if enabled
    if ($debug_on === true) {
        $qvarstr = $qvarstr ? $qvarstr . "&debug=on" : "debug=on";
    }

    // Apply htmlentities to make the query string HTML-safe
    if ($fixhtml === true) {
        $qstr = htmlentities($qstr, ENT_QUOTES, $icharset);
        $qsep = htmlentities($qsep, ENT_QUOTES, $icharset);
    }

    // Append pre-existing query string, main query string, and extra query string
    if ($prexqstr || $qvarstr || $exqstr) {
        $fileurl .= ($qstr === "/") ? "/" : "?";
    }

    append_query($prexqstr, $qstr, $qsep, $fileurl);
    append_query($qvarstr, $qstr, $qsep, $fileurl);
    append_query($exqstr, $qstr, $qsep, $fileurl);

    return $fileurl;
}

// Get the Query String
function GetQueryStrOld($qstr=";",$qsep="=",$fixhtml=true)
{ $pregqstr = preg_quote($qstr,"/");
$pregqsep = preg_quote($qsep,"/");
$oqstr = $qstr; $oqsep = $qsep;
if($fixhtml===true||$fixhtml==null) {
$qstr = htmlentities($qstr, ENT_QUOTES, $icharset);
$qsep = htmlentities($qsep, ENT_QUOTES, $icharset); }
$OldBoardQuery = preg_replace("/".$pregqstr."/isxS", $qstr, $_SERVER['QUERY_STRING']);
$OldBoardQuery = preg_replace("/".$pregqsep."/isxS", $qsep, $OldBoardQuery);
$BoardQuery = "?".$OldBoardQuery;
return $BoardQuery; }
function GetQueryStr($qstr = ";", $qsep = "=", $fixhtml = true)
{
    global $icharset;

    // Ensure QUERY_STRING is available and non-empty
    if (!isset($_SERVER['QUERY_STRING']) || empty($_SERVER['QUERY_STRING'])) {
        return ''; // Return an empty string if no query string is present
    }

    // If fixhtml is enabled, convert separators to HTML-safe equivalents
    if ($fixhtml === true || $fixhtml == null) {
        $qstr = htmlentities($qstr, ENT_QUOTES, $icharset);
        $qsep = htmlentities($qsep, ENT_QUOTES, $icharset);
    }

    // Get the current query string from the server
    $OldBoardQuery = $_SERVER['QUERY_STRING'];

    // Replace the default separators ('&' and '=') with the custom ones
    $OldBoardQuery = str_replace('&', $qstr, $OldBoardQuery);
    $OldBoardQuery = str_replace('=', $qsep, $OldBoardQuery);

    // Only prepend '?' if the query string is not empty after modifications
    if (!empty($OldBoardQuery)) {
        $BoardQuery = '?' . $OldBoardQuery;
    } else {
        $BoardQuery = ''; // No need for '?' if there's no query string
    }

    return $BoardQuery;
}

function log_fix_quotes($logtxt) {
	$logtxt = str_replace("\"", "\\\"", $logtxt);
	$logtxt = str_replace("'", "", $logtxt);
	return $logtxt; }
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
function log_fix_get_server_values($matches) {
	return log_fix_quotes(get_server_values($matches)); }
function log_fix_get_cookie_values($matches) {
	return log_fix_quotes(get_cookie_values($matches)); }
function log_fix_get_env_values($matches) {
	return log_fix_quotes(get_env_values($matches)); }
function log_fix_get_setting_values($matches) {
	return log_fix_quotes(get_setting_values($matches)); }
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
$strftime = preg_replace("/\{percent\}p/s", "%", $strftime);
return $strftime; }
function apache_log_maker($logtxt,$logfile=null,$status=200,$contentsize="-",$headersize=0) {
global $Settings;
if(isset($Settings['DefaultTimeZone'])) {
$servtz = new DateTimeZone($Settings['DefaultTimeZone']); }
if(!isset($Settings['DefaultTimeZone'])) {
$servtz = new DateTimeZone(date_default_timezone_get()); }
$servcurtime = new DateTime();
$servcurtime->setTimezone($servtz);
if(!isset($_SERVER['HTTP_REFERER'])) { $LOG_URL_REFERER = "-"; }
if(isset($_SERVER['HTTP_REFERER'])) { $LOG_URL_REFERER = $_SERVER['HTTP_REFERER']; }
if($LOG_URL_REFERER==""||$LOG_URL_REFERER==null) { $LOG_URL_REFERER = "-"; }
if(trim($LOG_URL_REFERER, "\x00..\x1F") == "") { $LOG_URL_REFERER = "-"; }
$LOG_URL_REFERER = log_fix_quotes($LOG_URL_REFERER);
if(!isset($_SERVER['PHP_AUTH_USER'])) { $LOG_AUTH_USER = "-"; }
if(isset($_SERVER['PHP_AUTH_USER'])) { $LOG_AUTH_USER = $_SERVER['PHP_AUTH_USER']; }
if($LOG_AUTH_USER==""||$LOG_AUTH_USER==null) { $LOG_AUTH_USER = "-"; }
if(trim($LOG_AUTH_USER, "\x00..\x1F") == "") { $LOG_AUTH_USER = "-"; }
$LOG_AUTH_USER = log_fix_quotes($LOG_AUTH_USER);
if(!isset($_SERVER['HTTP_USER_AGENT'])) { $LOG_USER_AGENT = "-"; }
if(isset($_SERVER['HTTP_USER_AGENT'])) { $LOG_USER_AGENT = $_SERVER['HTTP_USER_AGENT']; }
if($LOG_USER_AGENT==""||$LOG_USER_AGENT==null) { $LOG_USER_AGENT = "-"; }
if(trim($LOG_USER_AGENT, "\x00..\x1F") == "") { $LOG_USER_AGENT = "-"; }
$LOG_USER_AGENT = log_fix_quotes($LOG_USER_AGENT);
$LogMemName = "-";
if(!isset($_SESSION['MemberName'])) {
	$_SESSION['MemberName'] = null; }
if($_SESSION['MemberName']===null) {
	$LogMemName = "-"; }
if(isset($_SESSION['MemberName'])&&$_SESSION['MemberName']!==null) {
	$LogMemName = $_SESSION['MemberName']; }
if(trim($LogMemName, "\x00..\x1F") == "") { $LogMemName = "-"; }
$LogMemName = log_fix_quotes($LogMemName);
$LogMemID = "-";
if(!isset($_SESSION['UserID'])) {
	$_SESSION['UserID'] = 0; }
if($_SESSION['UserID']===null||$_SESSION['UserID']===0) {
	$LogMemID = "-"; }
if(isset($_SESSION['UserID'])&&$_SESSION['UserID']!==null&&$_SESSION['UserID']!==0) {
	$LogMemID = $_SESSION['UserID']; }
if(trim($LogMemID, "\x00..\x1F") == "") { $LogMemID = "-"; }
$LogMemID = log_fix_quotes($LogMemID);
$LogGroupName = "-";
if(!isset($_SESSION['UserGroup'])) {
	$LogGroupName = "-"; }
if(isset($_SESSION['UserGroup'])&&$_SESSION['UserGroup']===null) {
	$LogGroupName = "-"; }
if(isset($_SESSION['UserGroup'])&&$_SESSION['UserGroup']!==null) {
	$LogGroupName = $_SESSION['UserGroup']; }
if(trim($LogGroupName, "\x00..\x1F") == "") { $LogGroupName = "-"; }
$LogGroupName = log_fix_quotes($LogGroupName);
$LogGroupID = "-";
if(!isset($_SESSION['UserGroupID'])) {
	$LogGroupID = "-"; }
if(isset($_SESSION['UserGroupID'])&&$_SESSION['UserGroupID']===null) {
	$LogGroupID = "-"; }
if(isset($_SESSION['UserGroupID'])&&$_SESSION['UserGroupID']!==null) {
	$LogGroupID = $_SESSION['UserGroupID']; }
if(trim($LogGroupID, "\x00..\x1F") == "") { $LogGroupID = "-"; }
$LogGroupID = log_fix_quotes($LogGroupID);
$LOG_QUERY_STRING = "";
if($_SERVER['QUERY_STRING']!=="") {
$LOG_QUERY_STRING = "?".$_SERVER['QUERY_STRING']; }
if(trim($LOG_QUERY_STRING, "\x00..\x1F") == "") { $LOG_QUERY_STRING = ""; }
$LOG_QUERY_STRING = log_fix_quotes($LOG_QUERY_STRING);
$oldcontentsize = $contentsize;
if($oldcontentsize=="-") { $oldcontentsize = 0; }
if($contentsize===0) { $contentsize = "-"; }
if($contentsize=="-"&&$headersize!==0) { $fullsitesize = $headersize; }
if($contentsize!="-"&&$headersize!==0) { $fullsitesize = $contentsize + $headersize; }
if($status=="302") { $contentsize = "-"; }
$HTTP_REQUEST_LINE = $_SERVER['REQUEST_METHOD']." ".$_SERVER['REQUEST_URI']." ".$_SERVER['SERVER_PROTOCOL'];
$HTTP_REQUEST_LINE = log_fix_quotes($HTTP_REQUEST_LINE);
$logtxt = preg_replace("/%%/s", "{percent}p", $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)a/s", $_SERVER['REMOTE_ADDR'], $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)A/s", $_SERVER['SERVER_ADDR'], $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)B/s", $oldcontentsize, $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)b/s", $contentsize, $logtxt);
$logtxt = preg_replace_callback("/%([\<\>]*?)\{([^\}]*)\}C/s", "get_cookie_values", $logtxt);
$logtxt = preg_replace_callback("/%([\<\>]*?)\{([^\}]*)\}e/s", "get_env_values", $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)f/s", log_fix_quotes($_SERVER['SCRIPT_FILENAME']), $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)h/s", $_SERVER['REMOTE_ADDR'], $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)H/s", $_SERVER['SERVER_PROTOCOL'], $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)\{Referer\}i/s", $LOG_URL_REFERER, $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)\{User-Agent\}i/s", $LOG_USER_AGENT, $logtxt);
$logtxt = preg_replace_callback("/%([\<\>]*?)\{([^\}]*)\}i/s", "get_server_values", $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)l/s", "-", $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)m/s", $_SERVER['REQUEST_METHOD'], $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)p/s", $_SERVER['SERVER_PORT'], $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)q/s", $LOG_QUERY_STRING, $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)r/s", $HTTP_REQUEST_LINE, $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)s/s", $status, $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)t/s", "[".$servcurtime->format("d/M/Y:H:i:s O")."]", $logtxt);
$logtxt = preg_replace_callback("/%([\<\>]*?)\{([^\}]*)\}t/s", "get_time", $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)u/s", $LOG_AUTH_USER, $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)U/s", log_fix_quotes($_SERVER['PHP_SELF']), $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)v/s", $_SERVER['SERVER_NAME'], $logtxt);
$logtxt = preg_replace("/%([\<\>]*?)V/s", $_SERVER['SERVER_NAME'], $logtxt);
// Not what it should be but PHP dose not have variable to get Apache ServerName config value. :( 
$logtxt = preg_replace("/%([\<\>]*?)O/s", $fullsitesize, $logtxt);
$logtxt = preg_replace_callback("/%([\<\>]*?)\{([^\}]*)\}s/s", "get_setting_values", $logtxt);
$logtxt = preg_replace("/\%\{UserName\}m/s", $LogMemName, $logtxt);
$logtxt = preg_replace("/\%\{MemberName\}m/s", $LogMemName, $logtxt);
$logtxt = preg_replace("/\%\{UserID\}m/s", $LogMemID, $logtxt);
$logtxt = preg_replace("/\%\{MemberID\}m/s", $LogMemID, $logtxt);
$logtxt = preg_replace("/\%\{UserGroup\}m/s", $LogGroupName, $logtxt);
$logtxt = preg_replace("/\%\{MemberGroup\}m/s", $LogGroupName, $logtxt);
$logtxt = preg_replace("/\%\{UserGroupID\}m/s", $LogGroupID, $logtxt);
$logtxt = preg_replace("/\%\{MemberGroupID\}m/s", $LogGroupID, $logtxt);
$logtxt = preg_replace("/\{percent\}p/s", "%", $logtxt);
if(isset($logfile)&&$logfile!==null) {
	$fp = fopen($logfile, "a+");
	if (flock($fp, LOCK_EX)) {
		$logtxtnew = $logtxt."\r\n";
		fwrite($fp, $logtxtnew, strlen($logtxtnew)); 
		flock($fp, LOCK_UN); }
	fclose($fp); 
	@chmod($logfile, 0666); }
return $logtxt; }
function idb_log_maker($status=200,$contentsize="-") {
global $Settings,$SettDir;
if(isset($Settings['DefaultTimeZone'])) {
$servtz = new DateTimeZone($Settings['DefaultTimeZone']); }
if(!isset($Settings['DefaultTimeZone'])) {
$servtz = new DateTimeZone(date_default_timezone_get()); }
$servcurtime = new DateTime();
$servcurtime->setTimezone($servtz);
if(!isset($Settings['log_http_request'])) {
	$Settings['log_http_request'] = "off"; }
if(!isset($Settings['log_config_format'])) {
	$Settings['log_config_format'] = "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\""; }
if(isset($Settings['log_http_request'])&&$Settings['log_http_request']=="on"&&
	$Settings['log_http_request']!==null&&$Settings['log_http_request']!="off") {
return apache_log_maker($Settings['log_config_format'], $SettDir['logs'].$Settings['sqltable'].$servcurtime->format("Ym").".log", $status, $contentsize, strlen(implode("\r\n",headers_list())."\r\n\r\n")); }
if(isset($Settings['log_http_request'])&&$Settings['log_http_request']!="on"&&
	$Settings['log_http_request']!==null&&$Settings['log_http_request']!="off") {
$Settings['log_http_request'] = preg_replace_callback("/".preg_quote("%{", "/")."([^\}]*)".preg_quote("}t", "/")."/s", "get_time", $Settings['log_http_request']);
$Settings['log_http_request'] = preg_replace_callback("/".preg_quote("%{", "/")."([^\}]*)".preg_quote("}s", "/")."/s", "get_setting_values", $Settings['log_http_request']);
return apache_log_maker($Settings['log_config_format'], $SettDir['logs'].$Settings['log_http_request'], $status, $contentsize, strlen(implode("\r\n",headers_list())."\r\n\r\n")); } }

// Function to return the LIMIT and OFFSET clause in ANSI format or SQL Server-specific syntax
function getSQLLimitClause($sqltype, $limit, $offset) {
    // Check if LIMIT and OFFSET are provided, set default values if necessary
    $limit = isset($limit) ? intval($limit) : 10; // Default limit value, e.g., 10
    $offset = isset($offset) ? intval($offset) : 0; // Default offset value, e.g., 0

    // Construct the LIMIT OFFSET clause based on the SQL type
    if ($sqltype == "mysql"||
		$sqltype == "mysqli"||
		$sqltype == "mysqli_prepare"||
		$sqltype == "pdo_mysql"||
        $sqltype == "sqlite"||
		$sqltype == "sqlite3"||
		$sqltype == "sqlite3_prepare"||
		$sqltype == "pdo_sqlite3"||
        $sqltype == "cubrid"||
        $sqltype == "cubrid_prepare"||
		$sqltype == "pdo_cubrid") {
        // MySQL, SQLite, and Cubrid support ANSI LIMIT x OFFSET y syntax
        return sprintf("LIMIT %d OFFSET %d", $limit, $offset);
    } elseif ($sqltype == "pgsql" || $sqltype == "pdo_pgsql") {
        // PostgreSQL also supports ANSI LIMIT x OFFSET y syntax
        return sprintf("LIMIT %d OFFSET %d", $limit, $offset);
    } elseif ($sqltype == "sqlsrv" || $sqltype == "pdo_sqlsrv") {
        // SQL Server (sqlsrv) uses OFFSET x ROWS FETCH NEXT y ROWS ONLY syntax
        return sprintf("OFFSET %d ROWS FETCH NEXT %d ROWS ONLY", $offset, $limit);
    } else {
        // If the SQL type is unknown or unsupported, return an empty string (no limit)
        return "";
    }
}
?>
