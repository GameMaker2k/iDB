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
if ($File3Name == "function.php" || $File3Name == "/function.php") {
    require('index.php');
    exit();
}
require_once($SettDir['misc'].'functions.php');
require_once($SettDir['misc'].'ibbcode.php');
require_once($SettDir['misc'].'iuntar.php');
/* In php 6 and up the function get_magic_quotes_gpc dose not exist.
   here we make a fake version that always sends false out. :P */
if (!function_exists('get_magic_quotes_gpc')) {
    function get_magic_quotes_gpc()
    {
        return false;
    }
}
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
if ($Settings['qstr'] != "/") {
    $iDBURLCHK = $Settings['idburl'];
}
if ($Settings['qstr'] == "/") {
    $iDBURLCHK = preg_replace("/\/$/", "", $Settings['idburl']);
}
$basecheck = parse_url($iDBURLCHK);
$basedir = $basecheck['path'];
$cbasedir = $basedir;
$rbasedir = $basedir;
if ($Settings['fixbasedir'] != null && $Settings['fixbasedir'] != "off") {
    $basedir = $Settings['fixbasedir'];
}
if ($Settings['fixcookiedir'] != null && $Settings['fixcookiedir'] != "") {
    $cbasedir = $Settings['fixcookiedir'];
}
if ($Settings['fixredirectdir'] != null) {
    $rbasedir = $Settings['fixredirectdir'];
}
$BaseURL = $basedir;
// Get our Host Name and Referer URL's Host Name
if (!isset($_SERVER['HTTP_REFERER'])) {
    $REFERERurl = null;
    $_SERVER['HTTP_REFERER'] = null;
}
if (isset($_SERVER['HTTP_REFERER'])) {
    $REFERERurl = parse_url($_SERVER['HTTP_REFERER']);
}
if (!isset($REFERERurl['host'])) {
    $REFERERurl['host'] = null;
}
$URL['REFERER'] = $REFERERurl['host'];
$URL['HOST'] = $basecheck['host'];
$REFERERurl = null;
// Function made by Howard Yeend
// http://php.net/manual/en/function.trigger-error.php#92016
// http://www.puremango.co.uk/
function output_error($message, $level = E_USER_ERROR)
{
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
    function random_int($min, $max)
    {
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

        $bytes = (int)max(ceil($bits / 8), 1);
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

function uuid_new($uuidver = "v4", $rndty = "random_int", $namespace = null, $name = null)
{
    // Validate the UUID version, default to "v4" if invalid
    if (!in_array($uuidver, ["v1", "v2", "v3", "v4", "v5", "guid"])) {
        $uuidver = "v4";
    }

    // Version 1 UUID (Time-based with MAC Address or Random Node)
    if ($uuidver == "v1") {
        // Get current time and convert to the appropriate format
        $time = microtime(true) * 10000000 + 0x01B21DD213814000;  // UUID epoch adjustment
        $timeHex = sprintf('%016x', $time);

        return sprintf(
            '%08s-%04s-%04x-%04x-%012x',
            substr($timeHex, 0, 8),        // time_low
            substr($timeHex, 8, 4),        // time_mid
            (hexdec(substr($timeHex, 12, 4)) & 0x0fff) | 0x1000,  // time_hi_and_version
            mt_rand(0, 0x3fff) | 0x8000,   // clk_seq_hi_res + variant
            mt_rand(0, 0xffffffffffff)     // node (random MAC or actual MAC address)
        );
    }

    // Version 2 UUID (DCE Security, similar to v1 but includes local domain identifier)
    if ($uuidver == "v2") {
        // Placeholder logic for Version 2, can be expanded for domain identifiers
        return uuid_new('v1');  // Typically handled similarly to v1
    }

    // Version 4 UUID (Randomly Generated) or GUID
    if ($uuidver == "v4" || $uuidver == "guid") {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            $rndty(0, 0xffff),
            $rndty(0, 0xffff),
            $rndty(0, 0xffff),
            $rndty(0, 0x0fff) | 0x4000,  // Set version 4
            $rndty(0, 0x3fff) | 0x8000,  // Set variant DCE 1.1
            $rndty(0, 0xffff),
            $rndty(0, 0xffff),
            $rndty(0, 0xffff)
        );
    }

    // Version 3 or Version 5 UUIDs (Name-based UUID)
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
        return sprintf(
            '%08s-%04s-%04x-%04x-%12s',
            substr($hash, 0, 8),    // time_low
            substr($hash, 8, 4),    // time_mid
            (hexdec(substr($hash, 12, 4)) & 0x0fff) | $uuidverid,  // time_hi_and_version
            (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,      // clk_seq_hi_res + variant
            substr($hash, 20, 12)   // node
        );
    }

    return false;
}

// Helper function to validate UUID format
function uuid_is_valid($uuid)
{
    return preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[1-5][a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}$/i', $uuid);
}

class UUID
{
    public static function v1()
    {
        // Get current time and convert to the appropriate format
        $time = microtime(true) * 10000000 + 0x01B21DD213814000;  // UUID epoch adjustment
        $timeHex = sprintf('%016x', $time);

        return sprintf(
            '%08s-%04s-%04x-%04x-%012x',
            substr($timeHex, 0, 8),        // time_low
            substr($timeHex, 8, 4),        // time_mid
            (hexdec(substr($timeHex, 12, 4)) & 0x0fff) | 0x1000,  // time_hi_and_version
            mt_rand(0, 0x3fff) | 0x8000,   // clk_seq_hi_res + variant
            mt_rand(0, 0xffffffffffff)     // node (random MAC or actual MAC address)
        );
    }

    public static function v2()
    {
        // Placeholder for UUID v2; DCE Security UUIDs typically involve POSIX UID/GID
        // Often similar to UUID v1, so we can return a v1 UUID for simplicity
        return self::v1();
    }

    public static function v3($namespace, $name)
    {
        if (!self::is_valid($namespace)) {
            return false;
        }

        // Get hexadecimal components of namespace
        $nhex = str_replace(array('-', '{', '}'), '', $namespace);

        // Binary Value
        $nstr = '';

        // Convert Namespace UUID to bits
        for ($i = 0; $i < strlen($nhex); $i += 2) {
            $nstr .= chr(hexdec($nhex[$i].$nhex[$i + 1]));
        }

        // Calculate hash value
        $hash = md5($nstr . $name);

        return sprintf(
            '%08s-%04s-%04x-%04x-%12s',
            substr($hash, 0, 8),    // time_low
            substr($hash, 8, 4),    // time_mid
            (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,  // time_hi_and_version
            (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,  // clk_seq_hi_res + variant
            substr($hash, 20, 12)   // node
        );
    }

    public static function v4()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),  // time_low
            mt_rand(0, 0xffff),                      // time_mid
            mt_rand(0, 0x0fff) | 0x4000,             // time_hi_and_version (v4)
            mt_rand(0, 0x3fff) | 0x8000,             // clk_seq_hi_res + variant
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)  // node
        );
    }

    public static function v5($namespace, $name)
    {
        if (!self::is_valid($namespace)) {
            return false;
        }

        // Get hexadecimal components of namespace
        $nhex = str_replace(array('-', '{', '}'), '', $namespace);

        // Binary Value
        $nstr = '';

        // Convert Namespace UUID to bits
        for ($i = 0; $i < strlen($nhex); $i += 2) {
            $nstr .= chr(hexdec($nhex[$i].$nhex[$i + 1]));
        }

        // Calculate hash value
        $hash = sha1($nstr . $name);

        return sprintf(
            '%08s-%04s-%04x-%04x-%12s',
            substr($hash, 0, 8),    // time_low
            substr($hash, 8, 4),    // time_mid
            (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,  // time_hi_and_version
            (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,  // clk_seq_hi_res + variant
            substr($hash, 20, 12)   // node
        );
    }

    public static function guid()
    {
        return self::v4();  // GUID follows the same structure as UUID v4
    }

    public static function is_valid($uuid)
    {
        return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $uuid) === 1;
    }
}

function uuid($uuidver = "v4", $rndty = "rand", $namespace = null, $name = null)
{
    // Ensure the UUID version is valid, default to "v4"
    if (!in_array($uuidver, ["v1", "v2", "v3", "v4", "v5", "guid"])) {
        $uuidver = "v4";
    }

    // Handle UUID generation based on version
    switch ($uuidver) {
        case "v1":
            return UUID::v1();
        case "v2":
            return UUID::v2();
        case "v3":
            if ($namespace && $name) {
                return UUID::v3($namespace, $name);
            }
            return false;
        case "v4":
            return UUID::v4();
        case "v5":
            if ($namespace && $name) {
                return UUID::v5($namespace, $name);
            }
            return false;
        case "guid":
            return UUID::guid();
        default:
            return false;
    }
}

function rand_uuid($rndty = "rand", $namespace = null, $name = null)
{
    // Extend the random UUID version selection to include v1, v2, v3, v4, v5, and guid
    $rand_array = array(1 => "v1", 2 => "v2", 3 => "v3", 4 => "v4", 5 => "v5", 6 => "guid");

    // Ensure $name is not null by setting a default if necessary
    if ($name === null) {
        $name = salt_hmac();
    }

    // Randomly select a UUID version from the array
    $my_uuid = $rand_array[$rndty(1, 6)];

    // Handle each UUID version accordingly
    if ($my_uuid == "v4" || $my_uuid == "guid") {
        return uuid($my_uuid, $rndty);  // v4 and GUID are handled similarly
    }

    if ($my_uuid == "v3" || $my_uuid == "v5") {
        // v3 and v5 require a namespace
        if ($namespace === null) {
            // Fallback to a random v4 UUID if no namespace is provided
            $namespace = uuid("v4", $rndty);
        }
        return uuid($my_uuid, $rndty, $namespace, $name);  // Return v3 or v5 UUID
    }

    if ($my_uuid == "v1" || $my_uuid == "v2") {
        // v1 and v2 don't require a namespace or name
        return uuid($my_uuid, $rndty);
    }

    return false;  // Fallback in case something goes wrong
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
if ($Settings['qstr'] != "&" &&
    $Settings['qstr'] != "/") {
    qstring($Settings['qstr'], $Settings['qsep']);
    if (!isset($_GET['page'])) {
        $_GET['page'] = null;
    }
    if (!isset($_GET['act'])) {
        $_GET['act'] = null;
    }
    if (!isset($_POST['act'])) {
        $_POST['act'] = null;
    }
    if (!isset($_GET['id'])) {
        $_GET['id'] = null;
    }
    if (!isset($_GET['debug'])) {
        $_GET['debug'] = "false";
    }
    if (!isset($_GET['post'])) {
        $_GET['post'] = null;
    }
    if (!isset($_POST['License'])) {
        $_POST['License'] = null;
    }
}
if (isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] == null) {
    if (getenv('PATH_INFO') != null && getenv('PATH_INFO') != "1") {
        $_SERVER['PATH_INFO'] = getenv('PATH_INFO');
    }
    if (getenv('PATH_INFO') == null) {
        $myscript = $_SERVER['SCRIPT_NAME'];
        $myphpath = $_SERVER['PHP_SELF'];
        $mypathinfo = str_replace($myscript, "", $myphpath);
        @putenv("PATH_INFO=".$mypathinfo);
    }
}
// Change raw post data to POST array
// Not sure why I made but alwell. :P
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
function mrstring()
{
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
// Redirect to another file with either a timed or immediate redirect
function redirect($type, $file, $time = 0, $url = null, $dbsr = true)
{
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
function redirects($type, $url, $time = 0)
{
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
function html_tag_make($name = "br", $emptytag = true, $attbvar = null, $attbval = null, $extratest = null)
{
    // Check if both attributes and values are null or their counts mismatch
    if ($attbvar !== null && $attbval !== null && count($attbvar) !== count($attbval)) {
        trigger_error("Error: Number of attributes and values don't match!", E_USER_ERROR);
        return false;
    }

    // Initialize the tag with the tag name
    $mytag = "<" . $name;

    // Append attributes if provided
    if ($attbvar !== null && $attbval !== null) {
        $attributes = [];
        foreach ($attbvar as $i => $attb) {
            $attributes[] = $attb . '="' . htmlspecialchars($attbval[$i]) . '"';
        }
        $mytag .= " " . implode(" ", $attributes);
    }

    // Determine if tag is self-closing
    if ($emptytag) {
        $mytag .= " />";
    } else {
        $mytag .= ">";
        if ($extratest !== null) {
            $mytag .= $extratest;
        }
        $mytag .= "</" . $name . ">";
    }

    return $mytag;
}

// Start a xml document
function xml_tag_make($type, $attbs, $retval = false)
{
    // Split attributes string by & and then by = for name-value pairs
    $attblist = "";
    if (!empty($attbs)) {
        $attributes = array_map(function ($attb) {
            list($key, $val) = explode("=", $attb);
            return $key . '="' . htmlspecialchars($val) . '"';
        }, explode("&", $attbs));

        $attblist = " " . implode(" ", $attributes);
    }

    // Build the final XML declaration
    $xml_declaration = '<?' . $type . $attblist . '?>' . "\n";

    if ($retval) {
        return $xml_declaration;
    } else {
        echo $xml_declaration;
    }
}

// Start a generic XML tag
function xml_tag_make_alt($name, $attributes = null, $content = null, $emptytag = false)
{
    // Initialize the XML tag
    $tag = "<" . $name;

    // Add attributes if provided
    if ($attributes !== null) {
        foreach ($attributes as $attr => $value) {
            $tag .= ' ' . $attr . '="' . htmlspecialchars($value) . '"';
        }
    }

    // Check if tag is self-closing or has content
    if ($emptytag) {
        $tag .= " />";
    } else {
        $tag .= ">";
        if ($content !== null) {
            $tag .= htmlspecialchars($content);  // Escaping content to prevent XML injection
        }
        $tag .= "</" . $name . ">";
    }

    return $tag;
}

// Start a xml document (wrapper for xml_tag_make)
function xml_doc_start($ver, $encode, $retval = false)
{
    // Call the xml_tag_make function to generate the XML document declaration
    return xml_tag_make('xml', 'version=' . $ver . '&encoding=' . $encode, $retval);
}

$icharset = $Settings['charset'];
$debug_on = false;
if (isset($_GET['debug'])) {
    if ($_GET['debug'] == "true" ||
        $_GET['debug'] == "on") {
        $debug_on = true;
    }
}
$BoardURL = $Settings['idburl'];
// Change URLs to Links
function pre_url2link($matches)
{
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

function url2link($string)
{
    // Optimized regex for URL matching
    return preg_replace_callback(
        "/\b([a-zA-Z]+):\/\/([a-zA-Z0-9\-\.@\:]+)(\:[0-9]+)?(\/[A-Za-z0-9\.\/%\?\!\$\(\)\*\-_\:;,\+\@~]*)?(\?)?([A-Za-z0-9\.\/%&=\?\!\$\(\)\*\-_\:;,\+\@~]*)?(\#)?([A-Za-z0-9\.\/%&=\?\!\$\(\)\*\-_\:;,\+\@~]*)?/is",
        "pre_url2link",
        $string
    );
}

function urlcheck($string)
{
    global $BoardURL;

    // Simplified regex for extracting the first URL
    preg_match("/\b([a-zA-Z]+):\/\/([a-zA-Z0-9\-\.@\:]+)(\:[0-9]+)?(\/[A-Za-z0-9\.\/%\?\!\$\(\)\*\-_\:;,\+\@~]*)?(\?)?([A-Za-z0-9\.\/%&=\?\!\$\(\)\*\-_\:;,\+\@~]*)?(\#)?([A-Za-z0-9\.\/%&=\?\!\$\(\)\*\-_\:;,\+\@~]*)?/is", $string, $urlcheck);

    // Return the found URL or the default Board URL
    return $urlcheck[0] ?? $BoardURL;
}

// Check to make sure theme exists
$BoardTheme = $Settings['DefaultTheme'];
$ThemeDir = $SettDir['themes'];

function chack_themes($theme)
{
    global $BoardTheme, $ThemeDir;

    if (!isset($theme)) {
        $theme = null;
    }

    if (preg_match("/([a-zA-Z]+)\:/isU", $theme)) {
        $theme = $BoardTheme;
    }

    if (!preg_match("/^[a-z0-9]+$/isU", $theme)) {
        $theme = $BoardTheme;
    }

    require('settings.php');
    $ckskindir = dirname(realpath("settings.php")) . "/" . $ThemeDir;

    if ($handle = opendir($ckskindir)) {
        $dirnum = null;
        while (false !== ($ckfile = readdir($handle))) {
            if ($dirnum == null) {
                $dirnum = 0;
            }
            if (is_dir($ckskindir . $ckfile) && file_exists($ckskindir . $ckfile . "/info.php")) {
                if ($ckfile != "." && $ckfile != "..") {
                    //require($ckskindir.$ckfile."/info.php");
                    $cktheme[$dirnum] = $ckfile;
                    ++$dirnum;
                }
            }
        }
        closedir($handle);
        asort($cktheme);
    }

    $theme = preg_replace("/(.*?)\.\/(.*?)/", $BoardTheme, $theme);

    if (!in_array($theme, $cktheme) || strlen($theme) > 26) {
        $theme = $BoardTheme;
    }

    return $theme;
}

// Move append_query() outside to avoid redeclaration error
function append_query($queryStr, $qstr, $qsep, &$fileurl)
{
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
function url_maker($file = "index", $ext = ".php", $qvarstr = null, $qstr = ";", $qsep = "=", $prexqstr = null, $exqstr = null, $fixhtml = true)
{
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

function log_fix_quotes($logtxt)
{
    return str_replace(['"', "'"], ['\\\"', ''], $logtxt);
}

function get_server_values($matches)
{
    return $_SERVER[$matches[1]] ?? "-";
}

function get_cookie_values($matches)
{
    return $_COOKIE[$matches[1]] ?? null;
}

function get_env_values($matches)
{
    return getenv($matches[1]) ?? "-";
}

function get_setting_values($matches)
{
    global $Settings;
    $key = str_replace("sqlpass", "sqluser", $matches[1]);
    return $Settings[$key] ?? null;
}

function log_fix_get_server_values($matches)
{
    return log_fix_quotes(get_server_values($matches));
}

function log_fix_get_cookie_values($matches)
{
    return log_fix_quotes(get_cookie_values($matches));
}

function log_fix_get_env_values($matches)
{
    return log_fix_quotes(get_env_values($matches));
}

function log_fix_get_setting_values($matches)
{
    return log_fix_quotes(get_setting_values($matches));
}

function get_time($matches)
{
    return date(convert_strftime($matches[1]));
}

function convert_strftime($strftime)
{
    $replace_pairs = [
        "%%" => "{percent\}p", "%a" => "D", "%A" => "l", "%d" => "d", "%e" => "j",
        "%j" => "z", "%u" => "w", "%w" => "w", "%U" => "W", "%V" => "W", "%W" => "W",
        "%b" => "M", "%B" => "F", "%h" => "M", "%m" => "m", "%g" => "y", "%G" => "Y",
        "%y" => "y", "%Y" => "Y", "%H" => "H", "%I" => "h", "%l" => "g", "%M" => "i",
        "%p" => "A", "%P" => "a", "%r" => "h:i:s A", "%R" => "H:i", "%S" => "s",
        "%T" => "H:i:s", "%X" => "H:i:s", "%z" => "O", "%Z" => "O", "%c" => "D M j H:i:s Y",
        "%D" => "m/d/y", "%F" => "Y-m-d", "%x" => "m/d/y", "%n" => "\n", "%t" => "\t"
    ];
    $strftime = strtr($strftime, $replace_pairs);
    return str_replace("{percent\}p", "%", $strftime);
}

function apache_log_maker($logtxt, $logfile = null, $status = 200, $contentsize = "-", $headersize = 0)
{
    global $Settings;

    $servtz = new DateTimeZone($Settings['DefaultTimeZone'] ?? date_default_timezone_get());
    $servcurtime = new DateTime();
    $servcurtime->setTimezone($servtz);

    $LOG_URL_REFERER = log_fix_quotes($_SERVER['HTTP_REFERER'] ?? "-");
    $LOG_AUTH_USER = log_fix_quotes($_SERVER['PHP_AUTH_USER'] ?? "-");
    $LOG_USER_AGENT = log_fix_quotes($_SERVER['HTTP_USER_AGENT'] ?? "-");

    $LogMemName = log_fix_quotes($_SESSION['MemberName'] ?? "-");
    $LogMemID = log_fix_quotes($_SESSION['UserID'] ?? "-");
    $LogGroupName = log_fix_quotes($_SESSION['UserGroup'] ?? "-");
    $LogGroupID = log_fix_quotes($_SESSION['UserGroupID'] ?? "-");

    $LOG_QUERY_STRING = log_fix_quotes($_SERVER['QUERY_STRING'] ? "?" . $_SERVER['QUERY_STRING'] : "");

    if ($contentsize === 0) {
        $contentsize = "-";
    }

    $fullsitesize = ($contentsize !== "-" && $headersize !== 0) ? $contentsize + $headersize : $headersize;

    $HTTP_REQUEST_LINE = log_fix_quotes($_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI'] . " " . $_SERVER['SERVER_PROTOCOL']);

    $logtxt = preg_replace([
        "/%%/s", "/%([\<\>]*?)a/s", "/%([\<\>]*?)A/s", "/%([\<\>]*?)B/s", "/%([\<\>]*?)b/s",
        "/%([\<\>]*?)f/s", "/%([\<\>]*?)h/s", "/%([\<\>]*?)H/s", "/%([\<\>]*?)\{Referer\}i/s",
        "/%([\<\>]*?)\{User-Agent\}i/s", "/%([\<\>]*?)l/s", "/%([\<\>]*?)m/s", "/%([\<\>]*?)p/s",
        "/%([\<\>]*?)q/s", "/%([\<\>]*?)r/s", "/%([\<\>]*?)s/s", "/%([\<\>]*?)t/s",
        "/%([\<\>]*?)u/s", "/%([\<\>]*?)U/s", "/%([\<\>]*?)v/s", "/%([\<\>]*?)V/s", "/%([\<\>]*?)O/s"
    ], [
        "{percent}p", $_SERVER['REMOTE_ADDR'], $_SERVER['SERVER_ADDR'], $contentsize, $contentsize,
        log_fix_quotes($_SERVER['SCRIPT_FILENAME']), $_SERVER['REMOTE_ADDR'], $_SERVER['SERVER_PROTOCOL'],
        $LOG_URL_REFERER, $LOG_USER_AGENT, "-", $_SERVER['REQUEST_METHOD'], $_SERVER['SERVER_PORT'],
        $LOG_QUERY_STRING, $HTTP_REQUEST_LINE, $status, "[" . $servcurtime->format("d/M/Y:H:i:s O") . "]",
        $LOG_AUTH_USER, log_fix_quotes($_SERVER['PHP_SELF']), $_SERVER['SERVER_NAME'], $_SERVER['SERVER_NAME'],
        $fullsitesize
    ], $logtxt);

    $logtxt = preg_replace_callback("/%([\<\>]*?)\{([^\}]*)\}C/s", "get_cookie_values", $logtxt);
    $logtxt = preg_replace_callback("/%([\<\>]*?)\{([^\}]*)\}e/s", "get_env_values", $logtxt);
    $logtxt = preg_replace_callback("/%([\<\>]*?)\{([^\}]*)\}i/s", "get_server_values", $logtxt);
    $logtxt = preg_replace_callback("/%([\<\>]*?)\{([^\}]*)\}t/s", "get_time", $logtxt);
    $logtxt = preg_replace_callback("/%([\<\>]*?)\{([^\}]*)\}s/s", "get_setting_values", $logtxt);

    $logtxt = preg_replace([
        "/\%\{UserName\}m/s", "/\%\{MemberName\}m/s", "/\%\{UserID\}m/s", "/\%\{MemberID\}m/s",
        "/\%\{UserGroup\}m/s", "/\%\{MemberGroup\}m/s", "/\%\{UserGroupID\}m/s", "/\%\{MemberGroupID\}m/s"
    ], [
        $LogMemName, $LogMemName, $LogMemID, $LogMemID, $LogGroupName, $LogGroupName,
        $LogGroupID, $LogGroupID
    ], $logtxt);

    $logtxt = str_replace("{percent}p", "%", $logtxt);

    if ($logfile) {
        $fp = fopen($logfile, "a+");
        if (flock($fp, LOCK_EX)) {
            fwrite($fp, $logtxt . "\r\n");
            flock($fp, LOCK_UN);
        }
        fclose($fp);
        @chmod($logfile, 0666);
    }
    return $logtxt;
}

function idb_log_maker($status = 200, $contentsize = "-")
{
    global $Settings, $SettDir;

    $servtz = new DateTimeZone($Settings['DefaultTimeZone'] ?? date_default_timezone_get());
    $servcurtime = new DateTime();
    $servcurtime->setTimezone($servtz);

    $Settings['log_http_request'] = $Settings['log_http_request'] ?? "off";
    $Settings['log_config_format'] = $Settings['log_config_format'] ?? "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"";

    if ($Settings['log_http_request'] === "on") {
        return apache_log_maker($Settings['log_config_format'], $SettDir['logs'] . $Settings['sqltable'] . $servcurtime->format("Ym") . ".log", $status, $contentsize, strlen(implode("\r\n", headers_list()) . "\r\n\r\n"));
    }

    if ($Settings['log_http_request'] !== "off") {
        $Settings['log_http_request'] = preg_replace_callback("/" . preg_quote("%{", "/") . "([^\}]*)" . preg_quote("}t", "/") . "/s", "get_time", $Settings['log_http_request']);
        $Settings['log_http_request'] = preg_replace_callback("/" . preg_quote("%{", "/") . "([^\}]*)" . preg_quote("}s", "/") . "/s", "get_setting_values", $Settings['log_http_request']);
        return apache_log_maker($Settings['log_config_format'], $SettDir['logs'] . $Settings['log_http_request'], $status, $contentsize, strlen(implode("\r\n", headers_list()) . "\r\n\r\n"));
    }
}

// Function to return the LIMIT and OFFSET clause in ANSI format or SQL Server-specific syntax
function getSQLLimitClause($sqltype, $limit, $offset)
{
    // Check if LIMIT and OFFSET are provided, set default values if necessary
    $limit = isset($limit) ? intval($limit) : 10; // Default limit value, e.g., 10
    $offset = isset($offset) ? intval($offset) : 0; // Default offset value, e.g., 0

    // Construct the LIMIT OFFSET clause based on the SQL type
    if ($sqltype == "mysql" ||
        $sqltype == "mysqli" ||
        $sqltype == "mysqli_prepare" ||
        $sqltype == "pdo_mysql" ||
        $sqltype == "sqlite" ||
        $sqltype == "sqlite3" ||
        $sqltype == "sqlite3_prepare" ||
        $sqltype == "pdo_sqlite3" ||
        $sqltype == "cubrid" ||
        $sqltype == "cubrid_prepare" ||
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
