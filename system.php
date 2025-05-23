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

    $FileInfo: system.php - Last Update: 5/14/2025 SVN 1341 - Author: cooldude2k $
*/
/* Some ini setting changes uncomment if you need them.
   Display PHP Errors */
$disfunc = @ini_get("disable_functions");
$disfunc = @preg_replace("/[\s\t\n\r\0\x0B]+/", "", $disfunc);
$disfunc = $disfunc ? explode(",", $disfunc) : [];
if (!in_array("ini_set", $disfunc)) {
    @ini_set("html_errors", false);
    @ini_set("track_errors", false);
    @ini_set("display_errors", false);
    @ini_set("report_memleaks", false);
    @ini_set("display_startup_errors", false);
    @ini_set("error_log", "logs/error.log");
    @ini_set("log_errors", "On");
    @ini_set("docref_ext", "");
    @ini_set("docref_root", "http://php.net/");

    /* Get rid of session id in URLs */
    @ini_set("default_mimetype", "text/html");
    @ini_set("zlib.output_compression", false);
    @ini_set("zlib.output_compression_level", -1);
    @ini_set("session.use_trans_sid", false);
    @ini_set("session.use_cookies", true);
    @ini_set("session.use_only_cookies", true);
    @ini_set("url_rewriter.tags", "");
    @ini_set('zend.ze1_compatibility_mode', 0);
    @ini_set("ignore_user_abort", 1);

    /* Change session garbage collection settings */
    @ini_set("session.gc_probability", 1);
    @ini_set("session.gc_divisor", 100);
    @ini_set("session.gc_maxlifetime", 1440);

    /* Change session hash type */
    @ini_set("session.hash_function", 1);
    @ini_set("session.hash_bits_per_character", 6);
}
if (!defined("E_DEPRECATED")) {
    define("E_DEPRECATED", 0);
}
@error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
@set_time_limit(30);
@ignore_user_abort(true);
/* Do not change anything below this line unless you know what you are doing */
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "system.php" || $File3Name == "/system.php") {
    header('Location: index.php');
    exit();
}
if (file_exists('settings.php')) {
    require_once('settings.php');
    if (file_exists('extrasettings.php')) {
        require_once('extrasettings.php');
    }
    if (file_exists('extendsettings.php')) {
        require_once('extendsettings.php');
    }
    // Custom error handler for non-fatal errors
    // Configuration settings
    $errorDisplay = true;  // Set to true to display errors on the screen
    $errorLogFile = true;  // Set to true to log errors to a file
    if (!isset($SettDir['logs'])) {
        $SettDir['logs'] = "./logs";
    }
    $logFilePath = $SettDir['logs'] . 'php_error_log.txt';  // Define your log file path

    // Custom Error Handler Function
    function customErrorHandler($errno, $errstr, $errfile, $errline)
    {
        global $errorDisplay, $errorLogFile, $logFilePath;

        // List of error types we want to handle
        $errorTypes = [
            E_ERROR => 'Fatal Error',
            E_WARNING => 'Warning',
            E_PARSE => 'Parse Error',
            E_NOTICE => 'Notice',
            E_CORE_ERROR => 'Core Error',
            E_CORE_WARNING => 'Core Warning',
            E_COMPILE_ERROR => 'Compile Error',
            E_COMPILE_WARNING => 'Compile Warning',
            E_USER_ERROR => 'User Error',
            E_USER_WARNING => 'User Warning',
            E_USER_NOTICE => 'User Notice',
            E_STRICT => 'Strict Notice',
            E_RECOVERABLE_ERROR => 'Recoverable Error',
            E_DEPRECATED => 'Deprecated Notice',
            E_USER_DEPRECATED => 'User Deprecated Notice'
        ];

        // Safely retrieve and clean the output buffer
        $output = '';
        if (ob_get_length()) {
            $output = ob_get_clean();  // Get and clear the buffer without sending it
        }

        // Check if the error type is in our list of handled types
        $errorType = isset($errorTypes[$errno]) ? $errorTypes[$errno] : 'Unknown Error';

        // Prepare the error message
        $errorMessage = "<b>{$errorType}:</b> [$errno] $errstr - $errfile:$errline<br>";

        // Get the backtrace
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $backtraceMessage = getBacktraceAsString($backtrace);

        // Display the error if enabled
        if ($errorDisplay) {
            echo $errorMessage;
            echo "<b>Backtrace:</b><br>";
            echo $backtraceMessage;
        }

        // Log the error to a file if enabled
        if ($errorLogFile) {
            logErrorToFile($logFilePath, $errorType, $errno, $errstr, $errfile, $errline, $backtrace);
        }

        // Output the captured content again if needed
        echo $output;

        // Depending on the error, you might want to stop the script
        if ($errno === E_ERROR || $errno === E_PARSE || $errno === E_CORE_ERROR || $errno === E_COMPILE_ERROR) {
            die();
        }

        // Return true to prevent the PHP internal error handler from executing
        return true;
    }

    // Custom Shutdown Handler Function
    function shutdownHandler()
    {
        global $errorDisplay, $errorLogFile, $logFilePath;

        $last_error = error_get_last();

        // Check if $last_error is not null before accessing its elements
        if ($last_error !== null) {
            // Check if the error type is E_ERROR or E_PARSE (fatal errors)
            if ($last_error['type'] === E_ERROR || $last_error['type'] === E_PARSE || $last_error['type'] === E_CORE_ERROR || $last_error['type'] === E_COMPILE_ERROR) {

                // Safely retrieve and clean the output buffer
                $output = '';
                if (ob_get_length()) {
                    $output = ob_get_clean();  // Get and clear the buffer without sending it
                }

                // Prepare the error message
                $errorMessage = "<b>Fatal Error:</b> {$last_error['message']} - {$last_error['file']}:{$last_error['line']}<br>";

                // Get the backtrace
                $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
                $backtraceMessage = getBacktraceAsString($backtrace);

                // Display the error if enabled
                if ($errorDisplay) {
                    echo $errorMessage;
                    echo "<b>Backtrace:</b><br>";
                    echo $backtraceMessage;
                }

                // Log the error to a file if enabled
                if ($errorLogFile) {
                    logErrorToFile($logFilePath, 'Fatal Error', $last_error['type'], $last_error['message'], $last_error['file'], $last_error['line'], $backtrace);
                }

                // Output the captured content again if needed
                echo $output;
            }
        }
    }

    // Custom Exception Handler Function
    // Custom Exception Handler Function
    function customExceptionHandler($exception)
    {
        global $errorDisplay, $errorLogFile, $logFilePath;

        // Safely retrieve and clean the output buffer
        $output = '';
        if (ob_get_length()) {
            $output = ob_get_clean();  // Get and clear the buffer without sending it
        }

        // Prepare the uncaught exception message
        $errorMessage = "<b>Uncaught Exception:</b> " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine() . "<br>";

        // Get the backtrace from the exception
        $backtrace = $exception->getTrace();
        $backtraceMessage = getBacktraceAsString($backtrace);

        // Display the exception if enabled
        if ($errorDisplay) {
            echo $errorMessage;
            echo "<b>Backtrace:</b><br>";
            echo $backtraceMessage;
        }

        // Log the exception to a file if enabled
        if ($errorLogFile) {
            logErrorToFile($logFilePath, 'Uncaught Exception', 0, $exception->getMessage(), $exception->getFile(), $exception->getLine(), $backtrace);
        }

        // Output the captured content again if needed
        echo $output;

        // Stop the script after an uncaught exception
        die();
    }

    // Function to Convert Backtrace Array to String for Display/Logging
    function getBacktraceAsString($backtrace)
    {
        $backtraceMessage = "";
        foreach ($backtrace as $trace) {
            if (isset($trace['file'])) {
                $backtraceMessage .= "Called in <b>{$trace['file']}</b> on line <b>{$trace['line']}</b>";
                if (isset($trace['function'])) {
                    $backtraceMessage .= " (function <b>{$trace['function']}</b>)";
                }
                $backtraceMessage .= "<br>";
            }
        }
        return $backtraceMessage;
    }

    // Function to Log Errors to a File
    function logErrorToFile($logFile, $errorType, $errno, $errstr, $errfile, $errline, $backtrace)
    {
        $logMessage = "[" . date('Y-m-d H:i:s') . "] {$errorType}: [{$errno}] {$errstr} in {$errfile} on line {$errline}\n";

        // Append backtrace to the log
        foreach ($backtrace as $trace) {
            if (isset($trace['file'])) {
                $logMessage .= "Called in {$trace['file']} on line {$trace['line']}";
                if (isset($trace['function'])) {
                    $logMessage .= " (function {$trace['function']})";
                }
                $logMessage .= "\n";
            }
        }

        // Append to the log file
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    // Set the custom error handler
    set_error_handler("customErrorHandler");

    // Register the shutdown function to catch fatal errors
    register_shutdown_function('shutdownHandler');

    // Set exception handler to catch uncaught exceptions
    set_exception_handler('customExceptionHandler');

    if (isset($Settings['qstr']) && isset($Settings['qstr'])) {
        if (!in_array("ini_set", $disfunc) && $Settings['qstr'] !== "/" && $Settings['qstr'] !== "&") {
            @ini_set("arg_separator.output", htmlentities($Settings['qstr'], ENT_QUOTES, $Settings['charset']));
            @ini_set("arg_separator.input", $Settings['qstr']);
        }
    }
}
if (!isset($Settings['idburl'])) {
    $Settings['idburl'] = null;
}
if (isset($Settings['BoardUUID'])) {
    $Settings['BoardUUID'] = base64_decode($Settings['BoardUUID']);
    header("Board-Unique-ID: ".$Settings['BoardUUID']);
}
function unparse_url($parsed_url)
{
    $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
    $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
    $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
    $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
    $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
    $pass     = ($user || $pass) ? "$pass@" : '';
    $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
    $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
    $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
    return $scheme.$user.$pass.$host.$port.$path.$query.$fragment;
}
$OrgBoardURL = $Settings['idburl'];
if (isset($Settings['idburl'])) {
    $PreBestURL = parse_url($Settings['idburl']);
}
$PreServURL = parse_url((isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/') + 1));
if (isset($PreBestURL['host']) && $PreBestURL['host'] == "localhost.url" && str_replace("/", "", $PreBestURL['path']) == "localpath") {
    $PreBestURL['host'] = "localhost";
    $PreBestURL['path'] = $PreServURL['path'];
    $Settings['idburl'] = unparse_url($PreBestURL);
}
if (isset($PreBestURL['host']) && $PreBestURL['host'] == "localhost.url" && str_replace("/", "", $PreBestURL['path']) != "localpath") {
    $PreBestURL['host'] = $PreServURL['host'];
    $Settings['idburl'] = unparse_url($PreBestURL);
}
if (isset($PreBestURL['host']) && $PreBestURL['host'] != "localhost.url" && str_replace("/", "", $PreBestURL['path']) == "localpath") {
    $PreBestURL['path'] = $PreServURL['path'];
    $Settings['idburl'] = unparse_url($PreBestURL);
}
if (isset($Settings['weburl'])) {
    $OrgWebSiteURL = $Settings['weburl'];
} else {
    $OrgWebSiteURL = "";
}
if (isset($Settings['idburl'])) {
    $PreWestURL = parse_url($Settings['weburl']);
}
if (isset($PreWestURL['host']) && $PreWestURL['host'] == "localhost.url" && str_replace("/", "", $PreWestURL['path']) == "localpath") {
    $PreWestURL['host'] = $PreServURL['host'];
    $PreWestURL['path'] = $PreServURL['path'];
    $Settings['weburl'] = unparse_url($PreWestURL);
}
if (isset($PreWestURL['host']) && $PreWestURL['host'] == "localhost.url" && str_replace("/", "", $PreWestURL['path']) != "localpath") {
    $PreWestURL['host'] = $PreServURL['host'];
    $Settings['weburl'] = unparse_url($PreWestURL);
}
if (isset($PreWestURL['host']) && $PreWestURL['host'] != "localhost.url" && str_replace("/", "", $PreWestURL['path']) == "localpath") {
    $PreWestURL['path'] = $PreServURL['path'];
    $Settings['weburl'] = unparse_url($PreWestURL);
}
if (!isset($Settings['fixbasedir'])) {
    $Settings['fixbasedir'] = null;
}
if (!isset($Settings['fixpathinfo'])) {
    $Settings['fixpathinfo'] = null;
}
if (!isset($Settings['fixcookiedir'])) {
    $Settings['fixcookiedir'] = null;
}
if (!isset($Settings['fixredirectdir'])) {
    $Settings['fixcookiedir'] = null;
}
if (!isset($Settings['idb_time_format'])) {
    $Settings['idb_time_format'] = "g:i A";
}
if (!isset($Settings['idb_date_format'])) {
    $Settings['idb_date_format'] = "F j Y";
}
if (!isset($Settings['showverinfo'])) {
    $Settings['showverinfo'] = "on";
}
if (!isset($Settings['sqldb'])) {
    header("Content-Type: text/plain; charset=UTF-8");
    header('Location: install.php?act=part1');
}
if (!isset($Settings['fixpathinfo'])) {
    $Settings['fixpathinfo'] = "off";
}
if ($Settings['fixpathinfo'] == "off") {
    $Settings['fixpathinfo'] = null;
}
if (!isset($Settings['fixbasedir'])) {
    $Settings['fixbasedir'] = "off";
}
if ($Settings['fixbasedir'] == "off") {
    $Settings['fixbasedir'] = null;
}
if (!isset($Settings['fixcookiedir'])) {
    $Settings['fixcookiedir'] = "off";
}
if ($Settings['fixcookiedir'] == "off") {
    $Settings['fixcookiedir'] = null;
}
if (!isset($Settings['fixredirectdir'])) {
    $Settings['fixredirectdir'] = "off";
}
if ($Settings['fixredirectdir'] == "off") {
    $Settings['fixredirectdir'] = null;
}
$OldSettings['fixpathinfo'] = $Settings['fixpathinfo'];
$OldSettings['fixbasedir'] = $Settings['fixbasedir'];
$OldSettings['fixcookiedir'] = $Settings['fixcookiedir'];
$OldSettings['fixredirectdir'] = $Settings['fixredirectdir'];
if ($Settings['idburl'] == "localhost") {
    header("Content-Type: text/plain; charset=UTF-8");
    echo "500 Error: URL is malformed. Try reinstalling iDB.";
    die();
}
if ($Settings['fixbasedir'] == "on") {
    if ($Settings['idburl'] != null && $Settings['idburl'] != "localhost") {
        $PathsTest = parse_url($Settings['idburl']);
        $Settings['fixbasedir'] = $PathsTest['path']."/";
        $Settings['fixbasedir'] = str_replace("//", "/", $Settings['fixbasedir']);
    }
}
if ($Settings['fixcookiedir'] == "on") {
    if ($Settings['idburl'] != null && $Settings['idburl'] != "localhost") {
        $PathsTest = parse_url($Settings['idburl']);
        $Settings['fixcookiedir'] = $PathsTest['path']."/";
        $Settings['fixcookiedir'] = str_replace("//", "/", $Settings['fixcookiedir']);
    }
}
if ($Settings['fixredirectdir'] == "on") {
    if ($Settings['idburl'] != null && $Settings['idburl'] != "localhost") {
        $PathsTest = parse_url($Settings['idburl']);
        $Settings['fixredirectdir'] = $PathsTest['path']."/";
        $Settings['fixredirectdir'] = str_replace("//", "/", $Settings['fixredirectdir']);
    }
}
if (!isset($Settings['charset'])) {
    $Settings['charset'] = "ISO-8859-15";
}
if (isset($Settings['charset'])) {
    if ($Settings['charset'] != "ISO-8859-15" && $Settings['charset'] != "ISO-8859-1" &&
        $Settings['charset'] != "UTF-8" && $Settings['charset'] != "CP866" &&
        $Settings['charset'] != "Windows-1251" && $Settings['charset'] != "Windows-1252" &&
        $Settings['charset'] != "KOI8-R" && $Settings['charset'] != "BIG5" &&
        $Settings['charset'] != "GB2312" && $Settings['charset'] != "BIG5-HKSCS" &&
        $Settings['charset'] != "Shift_JIS" && $Settings['charset'] != "EUC-JP") {
        $Settings['charset'] = "ISO-8859-15";
    }
}
$chkcharset = $Settings['charset'];
if (!in_array("ini_set", $disfunc)) {
    @ini_set('default_charset', $Settings['charset']);
}
//session_save_path($SettDir['inc']."temp/");
if (!isset($Settings['sqldb'])) {
    if (file_exists("install.php")) {
        header('Location: install.php?act=part1');
        die();
    }
    if (!file_exists("install.php")) {
        header("Content-Type: text/plain; charset=UTF-8");
        echo "403 Error: Sorry could not find install.php\nTry uploading files again and if that dose not work try download iDB again.";
        die();
    }
}
if (isset($Settings['sqldb'])) {
    $deftz = new DateTimeZone(date_default_timezone_get());
    $defcurtime = new DateTime();
    $defcurtime->setTimezone($deftz);
    $utctz = new DateTimeZone("UTC");
    $utccurtime = new DateTime();
    $utccurtime->setTimestamp($defcurtime->getTimestamp());
    $utccurtime->setTimezone($utctz);
    $servtz = new DateTimeZone($Settings['DefaultTimeZone']);
    $servcurtime = new DateTime();
    $servcurtime->setTimestamp($defcurtime->getTimestamp());
    $servcurtime->setTimezone($servtz);
    $usercurtime = new DateTime();
    $usercurtime->setTimestamp($defcurtime->getTimestamp());
}
if (!isset($Settings['sqlhost'])) {
    $Settings['sqlhost'] = "localhost";
}
if ($Settings['fixpathinfo'] == "on") {
    $_SERVER['PATH_INFO'] = $_SERVER['ORIG_PATH_INFO'];
    putenv("PATH_INFO=".$_SERVER['ORIG_PATH_INFO']);
}
// Check to see if variables are set
if (!isset($SettDir['inc'])) {
    $SettDir['inc'] = "inc/";
}
if (!isset($SettDir['archive'])) {
    $SettDir['archive'] = "archive/";
}
if (!isset($SettDir['misc'])) {
    $SettDir['misc'] = "inc/misc/";
}
if (!isset($SettDir['sql'])) {
    $SettDir['sql'] = "inc/misc/sql/";
}
if (!isset($SettDir['admin'])) {
    $SettDir['admin'] = "inc/admin/";
}
if (!isset($SettDir['sqldumper'])) {
    $SettDir['sqldumper'] = "inc/admin/sqldumper/";
}
if (!isset($SettDir['mod'])) {
    $SettDir['mod'] = "inc/mod/";
}
if (!isset($SettDir['mplayer'])) {
    $SettDir['mplayer'] = "inc/mplayer/";
}
if (!isset($SettDir['themes'])) {
    $SettDir['themes'] = "themes/";
}
if (!isset($SettDir['maindir']) || !file_exists($SettDir['maindir']) || !is_dir($SettDir['maindir'])) {
    $SettDir['maindir'] = addslashes(str_replace("\\", "/", dirname(__FILE__)."/"));
}
if (isset($SettDir['maindir'])) {
    @chdir($SettDir['maindir']);
}
if (!isset($Settings['use_iniset'])) {
    $Settings['use_iniset'] = null;
}
if (!isset($Settings['clean_ob'])) {
    $Settings['clean_ob'] = "off";
}
if (!isset($_SERVER['PATH_INFO'])) {
    $_SERVER['PATH_INFO'] = null;
}
if (!isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
    $_SERVER['HTTP_ACCEPT_ENCODING'] = null;
}
if (!isset($_SERVER['HTTP_ACCEPT'])) {
    $_SERVER['HTTP_ACCEPT'] = null;
}
if (!isset($_SERVER['HTTP_REFERER'])) {
    $_SERVER['HTTP_REFERER'] = null;
}
if (!isset($_GET['page'])) {
    $_GET['page'] = null;
}
if (!isset($_GET['act'])) {
    $_GET['act'] = null;
}
if (!isset($_POST['act'])) {
    $_POST['act'] = null;
}
if (!isset($_GET['modact'])) {
    $_GET['modact'] = null;
}
if (!isset($_POST['modact'])) {
    $_POST['modact'] = null;
}
if (!isset($_GET['id'])) {
    $_GET['id'] = null;
}
if (!isset($_GET['debug'])) {
    $_GET['debug'] = "off";
}
if (!isset($_GET['post'])) {
    $_GET['post'] = null;
}
if (!isset($_POST['License'])) {
    $_POST['License'] = null;
}
if (!isset($_SERVER['HTTPS'])) {
    $_SERVER['HTTPS'] = "off";
}
if (!isset($Settings['SQLThemes'])) {
    $Settings['SQLThemes'] = "off";
}
if ($Settings['SQLThemes'] != "on" && $Settings['SQLThemes'] != "off") {
    $Settings['SQLThemes'] = "off";
}
require_once($SettDir['misc'].'utf8.php');
require_once($SettDir['inc'].'filename.php');
if (!isset($Settings['use_hashtype'])) {
    $Settings['use_hashtype'] = "sha1";
}
if (!function_exists('hash') || !function_exists('hash_algos')) {
    if ($Settings['use_hashtype'] != "md5" &&
       $Settings['use_hashtype'] != "sha1" &&
       $Settings['use_hashtype'] != "bcrypt" &&
       $Settings['use_hashtype'] != "argon2i" &&
       $Settings['use_hashtype'] != "argon2id") {
        $Settings['use_hashtype'] = "sha1";
    }
}
if ((function_exists('hash') && function_exists('hash_algos')) || function_exists('password_hash')) {
    if (!in_array($Settings['use_hashtype'], hash_algos()) && $Settings['use_hashtype'] != "bcrypt") {
        $Settings['use_hashtype'] = "sha1";
    }
    if ($Settings['use_hashtype'] != "md2" &&
       $Settings['use_hashtype'] != "md4" &&
       $Settings['use_hashtype'] != "md5" &&
       $Settings['use_hashtype'] != "sha1" &&
       $Settings['use_hashtype'] != "sha224" &&
       $Settings['use_hashtype'] != "sha256" &&
       $Settings['use_hashtype'] != "sha384" &&
       $Settings['use_hashtype'] != "sha512" &&
       $Settings['use_hashtype'] != "sha3-224" &&
       $Settings['use_hashtype'] != "sha3-256" &&
       $Settings['use_hashtype'] != "sha3-384" &&
       $Settings['use_hashtype'] != "sha3-512" &&
       $Settings['use_hashtype'] != "ripemd128" &&
       $Settings['use_hashtype'] != "ripemd160" &&
       $Settings['use_hashtype'] != "ripemd256" &&
       $Settings['use_hashtype'] != "ripemd320" &&
       $Settings['use_hashtype'] != "bcrypt" &&
       $Settings['use_hashtype'] != "argon2i" &&
       $Settings['use_hashtype'] != "argon2id") {
        $Settings['use_hashtype'] = "sha1";
    }
}
// Check to see if variables are set
require_once($SettDir['misc'].'setcheck.php');
$dayconv = array("year" => 29030400, "month" => 2419200, "week" => 604800, "day" => 86400, "hour" => 3600, "minute" => 60, "second" => 1);
require_once($SettDir['inc'].'function.php');
$Settings['bid'] = base64_encode(urlencode($Settings['idburl'].url_maker($exfile['index'], $Settings['file_ext'], "act=versioninfo", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false)));
$Settings['ubid'] = base64_encode(urlencode($Settings['idburl'].url_maker($exfile['index'], $Settings['file_ext'], "act=versioninfo", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false)));
if ($Settings['enable_pathinfo'] == "on") {
    mrstring(); /* Change Path info to Get Vars :P */
}
// Check to see if variables are set
$qstrhtml = htmlentities($Settings['qstr'], ENT_QUOTES, $Settings['charset']);
if ($Settings['enable_https'] == "on" && $_SERVER['HTTPS'] == "on") {
    if ($Settings['idburl'] != null && $Settings['idburl'] != "localhost") {
        $HTTPsTest = parse_url($Settings['idburl']);
        if ($HTTPsTest['scheme'] == "http") {
            $Settings['idburl'] = preg_replace("/http\:\/\//i", "https://", $Settings['idburl']);
        }
    }
}
$cookieDomain = null;
$cookieSecure = false;
if ($Settings['idburl'] != null && $Settings['idburl'] != "localhost") {
    $URLsTest = parse_url($Settings['idburl']);
    $cookieDomain = $URLsTest['host'];
    if ($cookieDomain == "localhost") {
        $cookieDomain = false;
    }
    if ($Settings['enable_https'] == "on") {
        if ($URLsTest['scheme'] == "https") {
            $cookieSecure = true;
        }
        if ($URLsTest['scheme'] != "https") {
            $cookieSecure = false;
        }
    }
}
if (!in_array("ini_set", $disfunc)) {
    @ini_set('default_charset', $Settings['charset']);
}
$File1Name = dirname($_SERVER['SCRIPT_NAME'])."/";
$File2Name = $_SERVER['SCRIPT_NAME'];
/*$File3Name=str_replace($File1Name, null, $File2Name);
if ($File3Name=="system.php"||$File3Name=="/system.php") {
    header('Location: index.php');
    exit(); }*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "system.php" || $File3Name == "/system.php") {
    header('Location: index.php');
    exit();
}
//error_reporting(E_ERROR);
// Check if gzip is on and if user's browser can accept gzip pages
if ($_GET['act'] == "MkCaptcha" || $_GET['act'] == "Captcha") {
    $Settings['use_gzip'] = 'off';
}
if ($Settings['use_gzip'] == "on") {
    if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "br") && function_exists('brotli_compress')) {
        $GZipEncode['Type'] = "brotli";
    } elseif (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "zstd") && function_exists('zstd_compress')) {
        $GZipEncode['Type'] = "zstd";
    } else {
        if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip")) {
            $GZipEncode['Type'] = "gzip";
        } else {
            if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "deflate")) {
                $GZipEncode['Type'] = "deflate";
            } else {
                $Settings['use_gzip'] = "off";
                $GZipEncode['Type'] = "none";
            }
        }
    }
}
if ($Settings['use_gzip'] == "brotli" && function_exists('brotli_compress')) {
    if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "br")) {
        $Settings['use_brotli'] = "on";
        $GZipEncode['Type'] = "brotli";
    } else {
        $Settings['use_gzip'] = "off";
    }
}
if ($Settings['use_gzip'] == "zstd" && function_exists('zstd_compress')) {
    if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "br")) {
        $Settings['use_zstd'] = "on";
        $GZipEncode['Type'] = "zstd";
    } else {
        $Settings['use_gzip'] = "off";
    }
}
if ($Settings['use_gzip'] == "brotli" && !function_exists('brotli_compress')) {
    $GZipEncode['Type'] = "gzip";
}
if ($Settings['use_gzip'] == "zstd" && !function_exists('zstd_compress')) {
    $GZipEncode['Type'] = "gzip";
}
if ($Settings['use_gzip'] == "gzip") {
    if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip")) {
        $Settings['use_gzip'] = "on";
        $GZipEncode['Type'] = "gzip";
    } else {
        $Settings['use_gzip'] = "off";
    }
}
if ($Settings['use_gzip'] == "deflate") {
    if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "deflate")) {
        $Settings['use_gzip'] = "on";
        $GZipEncode['Type'] = "deflate";
    } else {
        $Settings['use_gzip'] = "off";
    }
}
$iWrappers = array(null);
function idb_output_handler($buffer)
{
    return $buffer;
}
function idb_suboutput_handler($buffer)
{
    return $buffer;
}
if ($Settings['clean_ob'] == "on") {
    /* Check for other output handlers/buffers are open
       and close and get the contents in an array */
    $numob = count(ob_list_handlers());
    $iob = 0;
    while ($iob < $numob) {
        $old_ob_var[$iob] = ob_get_clean();
        ++$iob;
    }
} ob_start("idb_output_handler");
if ($Settings['use_gzip'] == "on") {
    if ($GZipEncode['Type'] != "gzip") {
        if ($GZipEncode['Type'] != "deflate") {
            $GZipEncode['Type'] = "gzip";
        }
    }
    if ($GZipEncode['Type'] == "gzip") {
        header("Content-Encoding: gzip");
    }
    if ($GZipEncode['Type'] == "deflate") {
        header("Content-Encoding: deflate");
    }
}
/* if(eregi("msie",$browser) && !eregi("opera",$browser)){
header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"'); } */
// Some http stuff
$SQLStat = sql_connect_db($Settings['sqlhost'], $Settings['sqluser'], $Settings['sqlpass'], $Settings['sqldb']);
if (isset($Settings['sql_collate']) && !isset($Settings['sql_charset'])) {
    if ($Settings['sql_collate'] == "ascii_bin" ||
        $Settings['sql_collate'] == "ascii_generel_ci") {
        $Settings['sql_charset'] = "ascii";
    }
    if ($Settings['sql_collate'] == "latin1_bin" ||
        $Settings['sql_collate'] == "latin1_general_ci" ||
        $Settings['sql_collate'] == "latin1_general_cs") {
        $Settings['sql_charset'] = "latin1";
    }
    if ($Settings['sql_collate'] == "utf8mb3_bin" ||
        $Settings['sql_collate'] == "utf8mb3_general_ci" ||
        $Settings['sql_collate'] == "utf8mb3_unicode_ci") {
        $Settings['sql_charset'] = "utf8mb3";
    }
    if ($Settings['sql_collate'] == "utf8mb4_bin" ||
        $Settings['sql_collate'] == "utf8mb4_general_ci" ||
        $Settings['sql_collate'] == "utf8mb4_unicode_ci") {
        $Settings['sql_charset'] = "utf8mb4";
    }
}
if (isset($Settings['sql_collate']) && isset($Settings['sql_charset'])) {
    if ($Settings['sql_charset'] == "ascii") {
        if ($Settings['sql_collate'] != "ascii_bin" &&
            $Settings['sql_collate'] != "ascii_generel_ci") {
            $Settings['sql_collate'] = "ascii_generel_ci";
        }
    }
    if ($Settings['sql_charset'] == "latin1") {
        if ($Settings['sql_collate'] != "latin1_bin" &&
            $Settings['sql_collate'] != "latin1_general_ci" &&
            $Settings['sql_collate'] != "latin1_general_cs") {
            $Settings['sql_collate'] = "latin1_general_ci";
        }
    }
    if ($Settings['sql_charset'] == "utf8" || $Settings['sql_charset'] == "utf8mb4") {
        if ($Settings['sql_collate'] != "utf8mb3_bin" &&
            $Settings['sql_collate'] != "utf8mb3_general_ci" &&
            $Settings['sql_collate'] != "utf8mb3_unicode_ci" &&
            $Settings['sql_collate'] != "utf8mb4_bin" &&
            $Settings['sql_collate'] != "utf8mb4_general_ci" &&
            $Settings['sql_collate'] != "utf8mb4_unicode_ci") {
            $Settings['sql_collate'] = "utf8mb4_unicode_ci";
        }
    }
    if ($Settings['sql_collate'] == "utf8mb3_bin" ||
        $Settings['sql_collate'] == "utf8mb3_general_ci" ||
        $Settings['sql_collate'] == "utf8mb3_unicode_ci") {
        $Settings['sql_charset'] = "utf8mb3";
    }
    if ($Settings['sql_collate'] == "utf8mb4_bin" ||
        $Settings['sql_collate'] == "utf8mb4_general_ci" ||
        $Settings['sql_collate'] == "utf8mb4_unicode_ci") {
        $Settings['sql_charset'] = "utf8mb4";
    }
    $SQLCollate = $Settings['sql_collate'];
    $SQLCharset = $Settings['sql_charset'];
}
if (!isset($Settings['sql_collate']) || !isset($Settings['sql_charset'])) {
    $SQLCollate = "latin1_general_ci";
    $SQLCharset = "latin1";
    if ($Settings['charset'] == "ISO-8859-1") {
        $SQLCollate = "latin1_general_ci";
        $SQLCharset = "latin1";
    }
    if ($Settings['charset'] == "ISO-8859-15") {
        $SQLCollate = "latin1_general_ci";
        $SQLCharset = "latin1";
    }
    if ($Settings['charset'] == "UTF-8") {
        $SQLCollate = "utf8mb4_unicode_ci";
        $SQLCharset = "utf8mb4";
    }
    $Settings['sql_collate'] = $SQLCollate;
    $Settings['sql_charset'] = $SQLCharset;
}
sql_set_charset($SQLCharset, $SQLStat);
if ($SQLStat === false) {
    header("Content-Type: text/plain; charset=".$Settings['charset']);
    sql_free_result($peresult);
    ob_clean();
    echo "Sorry could not connect to sql database.\nContact the board admin about error. Error log below.";
    echo "\n".sql_errorno($SQLStat);
    $urlstatus = 503;
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    session_write_close();
    die();
}
$sqltable = $Settings['sqltable'];
$temp_user_ip = $_SERVER['REMOTE_ADDR'];
if (!isset($_SERVER['HTTP_USER_AGENT'])) {
    $_SERVER['HTTP_USER_AGENT'] = "";
}
// Create an array to store browser hints
$client_hints_json = [];
$client_hints = [
    'user_agent' => isset($_SERVER['HTTP_SEC_CH_UA']) ? $_SERVER['HTTP_SEC_CH_UA'] : null,
    'is_mobile' => isset($_SERVER['HTTP_SEC_CH_UA_MOBILE']) ? $_SERVER['HTTP_SEC_CH_UA_MOBILE'] : null,
    'full_version' => isset($_SERVER['HTTP_SEC_CH_UA_FULL_VERSION']) ? $_SERVER['HTTP_SEC_CH_UA_FULL_VERSION'] : null,
    'full_version_list' => isset($_SERVER['HTTP_SEC_CH_UA_FULL_VERSION_LIST']) ? $_SERVER['HTTP_SEC_CH_UA_FULL_VERSION_LIST'] : null,
    'platform' => isset($_SERVER['HTTP_SEC_CH_UA_PLATFORM']) ? $_SERVER['HTTP_SEC_CH_UA_PLATFORM'] : null,
    'platform_version' => isset($_SERVER['HTTP_SEC_CH_UA_PLATFORM_VERSION']) ? $_SERVER['HTTP_SEC_CH_UA_PLATFORM_VERSION'] : null,
    'architecture' => isset($_SERVER['HTTP_SEC_CH_UA_ARCH']) ? $_SERVER['HTTP_SEC_CH_UA_ARCH'] : null,
    'bitness' => isset($_SERVER['HTTP_SEC_CH_UA_BITNESS']) ? $_SERVER['HTTP_SEC_CH_UA_BITNESS'] : null,
    'wow64' => isset($_SERVER['HTTP_SEC_CH_UA_WOW64']) ? $_SERVER['HTTP_SEC_CH_UA_WOW64'] : null,
    'model' => isset($_SERVER['HTTP_SEC_CH_UA_MODEL']) ? $_SERVER['HTTP_SEC_CH_UA_MODEL'] : null,
    'form_factor' => isset($_SERVER['HTTP_SEC_CH_UA_FORM_FACTOR']) ? $_SERVER['HTTP_SEC_CH_UA_FORM_FACTOR'] : null,
    'lang' => isset($_SERVER['HTTP_SEC_CH_LANG']) ? $_SERVER['HTTP_SEC_CH_LANG'] : null,
    'save_data' => isset($_SERVER['HTTP_SEC_CH_SAVE_DATA']) ? $_SERVER['HTTP_SEC_CH_SAVE_DATA'] : null,
    'width' => isset($_SERVER['HTTP_SEC_CH_WIDTH']) ? $_SERVER['HTTP_SEC_CH_WIDTH'] : null,
    'viewport_width' => isset($_SERVER['HTTP_SEC_CH_VIEWPORT_WIDTH']) ? $_SERVER['HTTP_SEC_CH_VIEWPORT_WIDTH'] : null,
    'viewport_height' => isset($_SERVER['HTTP_SEC_CH_VIEWPORT_HEIGHT']) ? $_SERVER['HTTP_SEC_CH_VIEWPORT_HEIGHT'] : null,
    'dpr' => isset($_SERVER['HTTP_SEC_CH_DPR']) ? $_SERVER['HTTP_SEC_CH_DPR'] : null,
    'device_memory' => isset($_SERVER['HTTP_SEC_CH_DEVICE_MEMORY']) ? $_SERVER['HTTP_SEC_CH_DEVICE_MEMORY'] : null,
    'rtt' => isset($_SERVER['HTTP_SEC_CH_RTT']) ? $_SERVER['HTTP_SEC_CH_RTT'] : null,
    'downlink' => isset($_SERVER['HTTP_SEC_CH_DOWNLINK']) ? $_SERVER['HTTP_SEC_CH_DOWNLINK'] : null,
    'ect' => isset($_SERVER['HTTP_SEC_CH_ECT']) ? $_SERVER['HTTP_SEC_CH_ECT'] : null,
    'prefers_color_scheme' => isset($_SERVER['HTTP_SEC_CH_PREFERS_COLOR_SCHEME']) ? $_SERVER['HTTP_SEC_CH_PREFERS_COLOR_SCHEME'] : null,
    'prefers_reduced_motion' => isset($_SERVER['HTTP_SEC_CH_PREFERS_REDUCED_MOTION']) ? $_SERVER['HTTP_SEC_CH_PREFERS_REDUCED_MOTION'] : null,
    'prefers_reduced_transparency' => isset($_SERVER['HTTP_SEC_CH_PREFERS_REDUCED_TRANSPARENCY']) ? $_SERVER['HTTP_SEC_CH_PREFERS_REDUCED_TRANSPARENCY'] : null,
    'prefers_contrast' => isset($_SERVER['HTTP_SEC_CH_PREFERS_CONTRAST']) ? $_SERVER['HTTP_SEC_CH_PREFERS_CONTRAST'] : null,
    'forced_colors' => isset($_SERVER['HTTP_SEC_CH_FORCED_COLORS']) ? $_SERVER['HTTP_SEC_CH_FORCED_COLORS'] : null
];
$client_hints_json = json_encode($client_hints);
if ($client_hints_json == "") {
    $client_hints_json = [];
}
if (strpos($_SERVER['HTTP_USER_AGENT'], "msie") &&
    !strpos($_SERVER['HTTP_USER_AGENT'], "opera")) {
    header("X-UA-Compatible: IE=Edge");
}
if (strpos($_SERVER['HTTP_USER_AGENT'], "chromeframe")) {
    header("X-UA-Compatible: IE=Edge,chrome=1");
}
$temp_user_agent = $_SERVER['HTTP_USER_AGENT'];
if ($Settings['file_ext'] != "no+ext" && $Settings['file_ext'] != "no ext") {
    $MkIndexFile = $exfile['index'].$Settings['file_ext'];
}
if ($Settings['file_ext'] == "no+ext" || $Settings['file_ext'] == "no ext") {
    $MkIndexFile = $exfile['index'];
}
$temp_session_data = "ViewingPage|s:9:\"?act=view\";ViewingFile|s:".strlen($MkIndexFile).":\"".$MkIndexFile."\";PreViewingTitle|s:7:\"Viewing\";ViewingTitle|s:11:\"Board index\";UserID|s:1:\"0\";UserIP|s:".strlen($_SERVER['REMOTE_ADDR']).":\"".$_SERVER['REMOTE_ADDR']."\";UserGroup|s:".strlen($Settings['GuestGroup']).":\"".$Settings['GuestGroup']."\";UserGroupID|s:1:\"4\";UserTimeZone|s:".strlen($Settings['DefaultTimeZone']).":\"".$Settings['DefaultTimeZone']."\";";
$alt_temp_session_data['ViewingPage'] = "?act=view";
$alt_temp_session_data['ViewingFile'] = $MkIndexFile;
$alt_temp_session_data['PreViewingTitle'] = "Viewing";
$alt_temp_session_data['ViewingTitle'] = "Board index";
$alt_temp_session_data['UserID'] = "0";
$alt_temp_session_data['UserIP'] = $_SERVER['REMOTE_ADDR'];
$alt_temp_session_data['UserGroupID'] = "4";
$alt_temp_session_data['UserTimeZone'] = $Settings['DefaultTimeZone'];
$alttemp_session_data = serialize($alt_temp_session_data);
$alt_temp_session_data = $alttemp_session_data;
$alttemp_session_data = null;
$SQLSType = $Settings['sqltype'];
$use_old_session = true;

// -------------------- OLD SESSION FUNCTION SET --------------------
if ($use_old_session) {
    function sql_session_open_old($save_path, $session_name) {
        global $sess_save_path;
        $sess_save_path = $save_path;
        return true;
    }

    $iDBSessCloseDB = true;
    function sql_session_close_old() {
        global $SQLStat, $iDBSessCloseDB;
        if ($iDBSessCloseDB === true) {
            sql_disconnect_db($SQLStat);
        }
        return true;
    }

    function sql_session_read_old($id) {
        global $sqltable, $SQLStat, $temp_user_ip, $temp_user_agent, $client_hints_json, $temp_session_data, $alt_temp_session_data;
        $checkQuery = sql_pre_query("SELECT COUNT(*) AS cnt FROM \"$sqltable"."sessions\" WHERE \"session_id\" = '%s'", array($id));
        $sessionExists = sql_count_rows($checkQuery, $SQLStat);
        if ($sessionExists == 0) {
            sql_query(sql_pre_query("DELETE FROM \"$sqltable"."sessions\" WHERE \"session_id\" <> '%s' AND \"ip_address\" = '%s' AND \"user_agent\" = '%s'", array($id, $temp_user_ip, $temp_user_agent)), $SQLStat);
            $time = (new DateTime('now', new DateTimeZone("UTC")))->getTimestamp();
            sql_query(sql_pre_query("INSERT INTO \"$sqltable"."sessions\" (\"session_id\", \"session_data\", \"serialized_data\", \"user_agent\", \"client_hints\", \"ip_address\", \"expires\") VALUES ('%s', '%s', '%s', '%s', '%s', '%s', %i)", array($id, $temp_session_data, $alt_temp_session_data, $temp_user_agent, $client_hints_json, $temp_user_ip, $time)), $SQLStat);
            return '';
        } else {
            $query = sql_pre_query("SELECT * FROM \"$sqltable"."sessions\" WHERE \"session_id\" = '%s'", array($id));
            $rs = sql_query($query, $SQLStat);
            $row = sql_fetch_assoc($rs);
            sql_free_result($rs);
            return $row ? $row['session_data'] : '';
        }
    }

    function sql_session_write_old($id, $data) {
        global $sqltable, $SQLStat, $temp_user_ip, $temp_user_agent, $client_hints_json;
        $time = (new DateTime('now', new DateTimeZone("UTC")))->getTimestamp();
        $checkQuery = sql_pre_query("SELECT COUNT(*) AS cnt FROM \"$sqltable"."sessions\" WHERE \"session_id\" = '%s'", array($id));
        $sessionExists = sql_count_rows($checkQuery, $SQLStat);
        if ($sessionExists == 0) {
            sql_query(sql_pre_query("INSERT INTO \"$sqltable"."sessions\" (\"session_id\", \"session_data\", \"serialized_data\", \"user_agent\", \"client_hints\", \"ip_address\", \"expires\") VALUES ('%s', '%s', '%s', '%s', '%s', '%s', %i)", array($id, $data, serialize($_SESSION), $temp_user_agent, $client_hints_json, $temp_user_ip, $time)), $SQLStat);
        } else {
            sql_query(sql_pre_query("UPDATE \"$sqltable"."sessions\" SET \"session_data\" = '%s', \"serialized_data\" = '%s', \"user_agent\" = '%s', \"client_hints\" = '%s', \"ip_address\" = '%s', \"expires\" = %i WHERE \"session_id\" = '%s'", array($data, serialize($_SESSION), $temp_user_agent, $client_hints_json, $temp_user_ip, $time, $id)), $SQLStat);
        }
        return true;
    }

    function sql_session_destroy_old($id) {
        global $sqltable, $SQLStat;
        sql_query(sql_pre_query("DELETE FROM \"$sqltable"."sessions\" WHERE \"session_id\" = '%s'", array($id)), $SQLStat);
        return true;
    }

    function sql_session_gc_old($maxlifetime) {
        global $sqltable, $SQLStat;
        $time = (new DateTime('now', new DateTimeZone("UTC")))->getTimestamp() - $maxlifetime;
        sql_query(sql_pre_query("DELETE FROM \"$sqltable"."sessions\" WHERE \"expires\" < %i", array($time)), $SQLStat);
        return true;
    }
} else {
// -------------------- NEW SESSION FUNCTION SET --------------------
    function sql_session_open_new($save_path, $session_name) {
        global $sess_save_path;
        $sess_save_path = $save_path;
        return true;
    }

    function sql_session_close_new() {
        global $SQLStat;
        sql_disconnect_db($SQLStat);
        return true;
    }

    function sql_session_read_new($id) {
        global $sqltable, $SQLStat, $temp_user_ip, $temp_user_agent, $client_hints_json, $temp_session_data, $alt_temp_session_data;
        $checkQuery = sql_pre_query("SELECT COUNT(*) AS cnt FROM \"$sqltable"."sessions\" WHERE \"session_id\" = '%s'", array($id));
        $sessionExists = sql_count_rows($checkQuery, $SQLStat);
        if ($sessionExists == 0) {
            sql_query(sql_pre_query("DELETE FROM \"$sqltable"."sessions\" WHERE \"session_id\" <> '%s' AND \"ip_address\" = '%s' AND \"user_agent\" = '%s'", array($id, $temp_user_ip, $temp_user_agent)), $SQLStat);
            $time = (new DateTime('now', new DateTimeZone("UTC")))->getTimestamp();
            sql_query(sql_pre_query("INSERT INTO \"$sqltable"."sessions\" (\"session_id\", \"session_data\", \"serialized_data\", \"user_agent\", \"client_hints\", \"ip_address\", \"expires\") VALUES ('%s', '%s', '%s', '%s', '%s', '%s', %i)", array($id, $temp_session_data, $alt_temp_session_data, $temp_user_agent, $client_hints_json, $temp_user_ip, $time)), $SQLStat);
            return '';
        } else {
            $query = sql_pre_query("SELECT * FROM \"$sqltable"."sessions\" WHERE \"session_id\" = '%s'", array($id));
            $rs = sql_query($query, $SQLStat);
            $row = sql_fetch_assoc($rs);
            sql_free_result($rs);
            return $row ? $row['session_data'] : '';
        }
    }

    function sql_session_write_new($id, $data) {
        global $sqltable, $SQLStat, $temp_user_ip, $temp_user_agent, $client_hints_json;
        $time = (new DateTime('now', new DateTimeZone("UTC")))->getTimestamp();
        $checkQuery = sql_pre_query("SELECT COUNT(*) AS cnt FROM \"$sqltable"."sessions\" WHERE \"session_id\" = '%s'", array($id));
        $sessionExists = sql_count_rows($checkQuery, $SQLStat);
        if ($sessionExists == 0) {
            sql_query(sql_pre_query("INSERT INTO \"$sqltable"."sessions\" (\"session_id\", \"session_data\", \"serialized_data\", \"user_agent\", \"client_hints\", \"ip_address\", \"expires\") VALUES ('%s', '%s', '%s', '%s', '%s', '%s', %i)", array($id, $data, serialize($_SESSION), $temp_user_agent, $client_hints_json, $temp_user_ip, $time)), $SQLStat);
        } else {
            sql_query(sql_pre_query("UPDATE \"$sqltable"."sessions\" SET \"session_data\" = '%s', \"serialized_data\" = '%s', \"user_agent\" = '%s', \"client_hints\" = '%s', \"ip_address\" = '%s', \"expires\" = %i WHERE \"session_id\" = '%s'", array($data, serialize($_SESSION), $temp_user_agent, $client_hints_json, $temp_user_ip, $time, $id)), $SQLStat);
        }
        return true;
    }

    function sql_session_destroy_new($id) {
        global $sqltable, $SQLStat;
        sql_query(sql_pre_query("DELETE FROM \"$sqltable"."sessions\" WHERE \"session_id\" = '%s'", array($id)), $SQLStat);
        return true;
    }

    function sql_session_gc_new($maxlifetime) {
        global $sqltable, $SQLStat;
        $time = (new DateTime('now', new DateTimeZone("UTC")))->getTimestamp() - $maxlifetime;
        sql_query(sql_pre_query("DELETE FROM \"$sqltable"."sessions\" WHERE \"expires\" < %i", array($time)), $SQLStat);
        return true;
    }
}

// -------------------- ACTIVE FUNCTION ALIASES --------------------
if ($use_old_session) {
    function sql_session_open(...$args) { return sql_session_open_old(...$args); }
    function sql_session_close(...$args) { return sql_session_close_old(...$args); }
    function sql_session_read(...$args) { return sql_session_read_old(...$args); }
    function sql_session_write(...$args) { return sql_session_write_old(...$args); }
    function sql_session_destroy(...$args) { return sql_session_destroy_old(...$args); }
    function sql_session_gc(...$args) { return sql_session_gc_old(...$args); }
} else {
    function sql_session_open(...$args) { return sql_session_open_new(...$args); }
    function sql_session_close(...$args) { return sql_session_close_new(...$args); }
    function sql_session_read(...$args) { return sql_session_read_new(...$args); }
    function sql_session_write(...$args) { return sql_session_write_new(...$args); }
    function sql_session_destroy(...$args) { return sql_session_destroy_new(...$args); }
    function sql_session_gc(...$args) { return sql_session_gc_new(...$args); }
}

// -------------------- SESSION HANDLER REGISTRATION --------------------
class SQLSessionHandler implements SessionHandlerInterface {
    public function open(string $path, string $name): bool {
        return sql_session_open($path, $name);
    }

    public function close(): bool {
        return sql_session_close();
    }

    public function read(string $id): string|false {
        return sql_session_read($id);
    }

    public function write(string $id, string $data): bool {
        return sql_session_write($id, $data);
    }

    public function destroy(string $id): bool {
        return sql_session_destroy($id);
    }

    public function gc(int $max_lifetime): int|false {
        return sql_session_gc($max_lifetime);
    }
}

// Register session handler functions
if (PHP_VERSION_ID >= 80400) {
    session_set_save_handler(new SQLSessionHandler(), true);
} else {
    session_set_save_handler(
        'sql_session_open',
        'sql_session_close',
        'sql_session_read',
        'sql_session_write',
        'sql_session_destroy',
        'sql_session_gc'
    );
}
if ($cookieDomain == null) {
    session_set_cookie_params(0, $cbasedir);
}
if ($cookieDomain != null) {
    if ($cookieSecure === true) {
        session_set_cookie_params(0, $cbasedir, $cookieDomain, 1);
    }
    if ($cookieSecure === false) {
        session_set_cookie_params(0, $cbasedir, $cookieDomain);
    }
}
session_cache_limiter("private, no-cache, no-store, must-revalidate, pre-check=0, post-check=0, max-age=0");
header("Cache-Control: private, no-cache, no-store, must-revalidate, pre-check=0, post-check=0, max-age=0");
header("Pragma: private, no-cache, no-store, must-revalidate, pre-check=0, post-check=0, max-age=0");
header("P3P: CP=\"IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT\"");
header("Date: ".gmdate("D, d M Y H:i:s")." GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Expires: ".gmdate("D, d M Y H:i:s")." GMT");
if (!isset($_COOKIE[$Settings['sqltable']."sess"])) {
    $exptime = $utccurtime->getTimestamp() - ini_get("session.gc_maxlifetime");
    sql_query(sql_pre_query("DELETE FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" < %i OR \"ip_address\"='%s' AND \"user_agent\"='%s'", array($exptime,$temp_user_ip,$temp_user_agent)), $SQLStat);
}
if (!isset($_SESSION['CheckCookie'])) {
    if (isset($_COOKIE['SessPass']) && isset($_COOKIE['MemberName'])) {
        if (PHP_VERSION_ID >= 80400) {
			session_set_save_handler(new SQLSessionHandler(), true);
		} else {
			session_set_save_handler(
				'sql_session_open',
				'sql_session_close',
				'sql_session_read',
				'sql_session_write',
				'sql_session_destroy',
				'sql_session_gc'
			);
		}
        session_name($Settings['sqltable']."sess");
        if (version_compare(phpversion(), '7.0', '<')) {
            session_start();
        } else {
            session_start([
                'use_trans_sid' => false,
                'use_cookies' => true,
                'use_only_cookies' => true,
                'gc_probability' => 1,
                'gc_divisor' => 100,
                'gc_maxlifetime' => 1440,
                //'hash_function' => 1,
                //'hash_bits_per_character' => 6,
                'name' => $Settings['sqltable']."sess",
            ]);
        }
        if (!isset($_SESSION['UserFormID'])) {
            $_SESSION['UserFormID'] = null;
        }
        $iDBSessCloseDB = false;
        $_SESSION['ShowActHidden'] = "no";
        require($SettDir['inc'].'prelogin.php');
        session_write_close();
    }
}
if (PHP_VERSION_ID >= 80400) {
    session_set_save_handler(new SQLSessionHandler(), true);
} else {
    session_set_save_handler(
        'sql_session_open',
        'sql_session_close',
        'sql_session_read',
        'sql_session_write',
        'sql_session_destroy',
        'sql_session_gc'
    );
}
session_name($Settings['sqltable']."sess");
if (version_compare(phpversion(), '7.0', '<')) {
    session_start();
} else {
    session_start([
        'use_trans_sid' => false,
        'use_cookies' => true,
        'use_only_cookies' => true,
        'gc_probability' => 1,
        'gc_divisor' => 100,
        'gc_maxlifetime' => 1440,
        //'hash_function' => 1,
        //'hash_bits_per_character' => 6,
        'name' => $Settings['sqltable']."sess",
    ]);
}
if (!isset($_SESSION['UserFormID'])) {
    $_SESSION['UserFormID'] = null;
}
$iDBSessCloseDB = true;
output_reset_rewrite_vars();
//@register_shutdown_function("session_write_close");
//header("Set-Cookie: PHPSESSID=" . session_id() . "; path=".$cbasedir);
if (!in_array("ini_set", $disfunc)) {
    // Set user agent if ini_set is available and HTTP requests are required.
    $iverstring = $Settings['hideverinfohttp'] === "on" ? "FR 0.0.0 ".$VER2[2]." 0" : $VER2[1]." ".$VER1[0].".".$VER1[1].".".$VER1[2]." ".$VER2[2]." ".$SubVerN;

    $qstrtest = htmlentities($Settings['qstr'], ENT_QUOTES, $Settings['charset']);
    $qseptest = htmlentities($Settings['qsep'], ENT_QUOTES, $Settings['charset']);

    $isiteurl = $Settings['idburl'] . url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index']);

    @ini_set("user_agent", "Mozilla/5.0 (compatible; ".$UserAgentName."/".$iverstring."; +".$isiteurl.")");

    if (function_exists("stream_context_create")) {
        $iopts = array(
            'http' => array(
                'method' => "GET",
                'header' =>
                    "Accept-Language: *\r\n" .
                    "User-Agent: Mozilla/5.0 (compatible; ".$UserAgentName."/".$iverstring."; +".$isiteurl.")\r\n" .
                    "Accept: */*\r\n" .
                    "Connection: keep-alive\r\n" .
                    "Referer: ".$isiteurl."\r\n" .
                    "From: ".$isiteurl."\r\n" .
                    "Via: ".$_SERVER['REMOTE_ADDR']."\r\n" .
                    "Forwarded: ".$_SERVER['REMOTE_ADDR']."\r\n" .
                    "X-Real-IP: ".$_SERVER['REMOTE_ADDR']."\r\n" .
                    "X-Forwarded-For: ".$_SERVER['REMOTE_ADDR']."\r\n" .
                    "X-Forwarded-Host: ".$URLsTest['host']."\r\n" .
                    "X-Forwarded-Proto: ".$URLsTest['scheme']."\r\n" .
                    "Board-Unique-ID: ".$Settings['BoardUUID']."\r\n" .
                    "Client-IP: ".$_SERVER['REMOTE_ADDR']."\r\n"
            )
        );
        $icontext = stream_context_create($iopts);

        function file_get_contents_alt($filename, $use_include_path = null, $offset = -1, $maxlen = null)
        {
            global $icontext;
            return $maxlen !== null
                ? file_get_contents($filename, $use_include_path, $icontext, $offset, $maxlen)
                : file_get_contents($filename, $use_include_path, $icontext, $offset);
        }
    }
}
$iDBVerName = $VerCheckName."|".$VER2[1]."|".$VER1[0].".".$VER1[1].".".$VER1[2]."|".$VER2[2]."|".$SubVerN;
/*
This way checks iDB version by sending the iDBVerName to the iDB Version Checker.
$Settings['vercheck'] = 1;
This way checks iDB version by sending the board url to the iDB Version Checker.
$Settings['vercheck'] = 2;
*/
if (!isset($Settings['vercheck'])) {
    $Settings['vercheck'] = 2;
}
if ($Settings['vercheck'] != 1 &&
    $Settings['vercheck'] != 2) {
    $Settings['vercheck'] = 2;
}
if ($Settings['vercheck'] === 2) {
    if ($_GET['act'] == "vercheckxsl") {
        if (stristr($_SERVER['HTTP_ACCEPT'], "application/xml")) {
            header("Content-Type: application/xml; charset=".$Settings['charset']);
        } else {
            header("Content-Type: text/xml; charset=".$Settings['charset']);
        }
        xml_doc_start("1.0", $Settings['charset']);
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
          Board Name: <a href="<?php echo url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index']); ?>"><xsl:value-of select="title"/></a></span>
    </div>
   </xsl:for-each>
  </body>
 </html>
</xsl:template>

</xsl:stylesheet>
<?php gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        session_write_close();
        die();
    }
    if ($_GET['act'] == "versioninfo") {
        if (stristr($_SERVER['HTTP_ACCEPT'], "application/xml")) {
            header("Content-Type: application/xml; charset=".$Settings['charset']);
        } else {
            header("Content-Type: text/xml; charset=".$Settings['charset']);
        }
        xml_doc_start("1.0", $Settings['charset']);
        echo '<?xml-stylesheet type="text/xsl" href="'.url_maker($exfile['index'], $Settings['file_ext'], "act=vercheckxsl", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index']).'"?>'."\n"; ?>

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
<?php gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        session_write_close();
        die();
    }
}
if ($_GET['act'] == "status") {
    $_GET['act'] = "view";
    $_GET['status'] = null;
}
$statusCodes = array(
    100 => "100 Continue",
    101 => "101 Switching Protocols",
    102 => "102 Processing",
    103 => "103 Early Hints",
    200 => "200 OK",
    201 => "201 Created",
    202 => "202 Accepted",
    203 => "203 Non-Authoritative Information",
    204 => "204 No Content",
    205 => "205 Reset Content",
    206 => "206 Partial Content",
    207 => "207 Multi-Status",
    208 => "208 Already Reported",
    226 => "226 IM Used",
    300 => "300 Multiple Choices",
    301 => "301 Moved Permanently",
    302 => "302 Found",
    303 => "303 See Other",
    304 => "304 Not Modified",
    305 => "305 Use Proxy",
    306 => "306 (Unused)",
    307 => "307 Temporary Redirect",
    308 => "308 Permanent Redirect",
    400 => "400 Bad Request",
    401 => "401 Unauthorized",
    402 => "402 Payment Required",
    403 => "403 Forbidden",
    404 => "404 Not Found",
    405 => "405 Method Not Allowed",
    406 => "406 Not Acceptable",
    407 => "407 Proxy Authentication Required",
    408 => "408 Request Timeout",
    409 => "409 Conflict",
    410 => "410 Gone",
    411 => "411 Length Required",
    412 => "412 Precondition Failed",
    413 => "413 Payload Too Large",
    414 => "414 URI Too Long",
    415 => "415 Unsupported Media Type",
    416 => "416 Range Not Satisfiable",
    417 => "417 Expectation Failed",
    418 => "418 I'm a teapot",
    421 => "421 Misdirected Request",
    422 => "422 Unprocessable Entity",
    423 => "423 Locked",
    424 => "424 Failed Dependency",
    425 => "425 Too Early",
    426 => "426 Upgrade Required",
    428 => "428 Precondition Required",
    429 => "429 Too Many Requests",
    431 => "431 Request Header Fields Too Large",
    451 => "451 Unavailable For Legal Reasons",
    500 => "500 Internal Server Error",
    501 => "501 Not Implemented",
    502 => "502 Bad Gateway",
    503 => "503 Service Unavailable",
    504 => "504 Gateway Timeout",
    505 => "505 HTTP Version Not Supported",
    506 => "506 Variant Also Negotiates",
    507 => "507 Insufficient Storage",
    508 => "508 Loop Detected",
    510 => "510 Not Extended",
    511 => "511 Network Authentication Required"
);

if ($_GET['act'] == "status") {
    // Check if 'status' is set and valid, otherwise default to 200
    if (!isset($_GET['status']) || !array_key_exists((int)$_GET['status'], $statusCodes)) {
        $_GET['status'] = 200; // Default to 200 OK
    } else {
        $_GET['status'] = (int)$_GET['status']; // Cast to int if valid
    }
    ?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title><?php echo $statusCodes[$_GET['status']]; ?></title>
    <meta charset="UTF-8">
    <meta name="author" content="Null">
    <meta name="keywords" content="Null">
    <meta name="description" content="Null">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: black;
            color: skyblue;
        }
        table {
            height: 100%;
            width: 100%;
            text-align: center;
        }
        a {
            font-size: 100px;
            font-weight: bold;
            text-decoration: none;
            color: skyblue;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td><a href="index.php?act=View"><?php echo $statusCodes[$_GET['status']]; ?></a></td>
        </tr>
    </table>
</body>
</html>
<?php
        $urlstatus = $_GET['status'];
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']); // Assuming gzip_page function exists
    session_write_close();
    die();
}
if ($Settings['vercheck'] === 1) {
    if ($_GET['act'] == "versioninfo") {
        header("Content-Type: text/plain; charset=UTF-8");
        header("Location: ".$VerCheckURL."&name=".urlencode($iDBVerName));
        $urlstatus = 302;
        gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        session_write_close();
        die();
    }
}
if ($_GET['act'] == "homepage") {
    header("Content-Type: text/plain; charset=UTF-8");
    header("Location: ".$Settings['weburl']);
    $urlstatus = 302;
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    session_write_close();
    die();
}
if ($_GET['act'] == "bsdl" || $_GET['act'] == "BSDL" || $_GET['act'] == "license" ||
    $_GET['act'] == "LICENSE" || $_GET['act'] == "License") {
    $_GET['act'] = "bsd";
}
if ($_GET['act'] == "bsd") {
    header("Content-Type: text/plain; charset=".$Settings['charset']);
    require("LICENSE");
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    die();
}
if ($_GET['act'] == "README" || $_GET['act'] == "ReadME") {
    $_GET['act'] = "readme";
}
if ($_GET['act'] == "readme" || $_GET['act'] == "ReadMe") {
    header("Content-Type: text/plain; charset=".$Settings['charset']);
    require("README");
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    die();
}
if ($_GET['act'] == "js" || $_GET['act'] == "javascript") {
    header("Content-Script-Type: text/javascript");
    if (stristr($_SERVER['HTTP_ACCEPT'], "application/x-javascript")) {
        header("Content-Type: application/x-javascript; charset=".$Settings['charset']);
    } else {
        if (stristr($_SERVER['HTTP_ACCEPT'], "application/javascript")) {
            header("Content-Type: application/javascript; charset=".$Settings['charset']);
        } else {
            header("Content-Type: text/javascript; charset=".$Settings['charset']);
        }
    }
    require($SettDir['inc'].'javascript.php');
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    die();
}
$Settings['use_captcha'] = "off";
/*if($Settings['use_captcha']=="on") {
if($_GET['act']=="MkCaptcha"||$_GET['act']=="Captcha") {
    if($Settings['captcha_clean']=="on") { ob_clean(); }
    require($SettDir['inc']."captcha.php");
    $aFontDir = dirname(__FILE__)."/inc/fonts/";
    $aFonts = array($aFontDir.'VeraBd.ttf', $aFontDir.'VeraBI.ttf', $aFontDir.'VeraIt.ttf', $aFontDir.'Vera.ttf');
    $oPhpCaptcha = new PhpCaptcha($aFonts, 200, 60);
    $RNumSize = rand(7,17); $i=0; $RandNum = null;
    while ($i <= $RNumSize) {
    $RandNum=$RandNum.dechex(rand(1,15)); ++$i; }
    $RandNum=strtoupper($RandNum);
    $oPhpCaptcha->SetOwnerText("Fake Code: ".$RandNum);
    $oPhpCaptcha->UseColour(true);
    $oPhpCaptcha->Create(); session_write_close(); die(); } }*/
require($SettDir['inc'].'groupsetup.php');
if ($Settings['board_offline'] == "on" && $GroupInfo['CanViewOffLine'] != "yes") {
    header("Content-Type: text/plain; charset=".$Settings['charset']);
    sql_free_result($peresult);
    ob_clean();
    if (!isset($Settings['offline_text'])) {
        echo "Sorry the board is off line.\nIf you are a admin you can login by the admin cp.";
    }
    if (isset($Settings['offline_text'])) {
        echo $Settings['offline_text'];
    } $urlstatus = 503;
    //echo "\n".sql_errorno($SQLStat);
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    session_write_close();
    die();
}
//Time Format Set
if (!isset($_SESSION['iDBDateFormat'])) {
    if (isset($Settings['idb_date_format'])) {
        $_SESSION['iDBDateFormat'] = $Settings['idb_date_format'];
        if (!isset($Settings['idb_date_format'])) {
            $_SESSION['iDBDateFormat'] = "g:i A";
        }
    }
}
if (!isset($_SESSION['iDBTimeFormat'])) {
    if (isset($Settings['idb_time_format'])) {
        $_SESSION['iDBTimeFormat'] = $Settings['idb_time_format'];
        if (!isset($Settings['idb_time_format'])) {
            $_SESSION['iDBTimeFormat'] = "F j Y";
        }
    }
}
//Time Zone Set
if (!isset($_SESSION['UserTimeZone'])) {
    if (isset($Settings['DefaultTimeZone'])) {
        $_SESSION['UserTimeZone'] = $Settings['DefaultTimeZone'];
        if (!isset($Settings['DefaultTimeZone'])) {
            $_SESSION['UserTimeZone'] = date_default_timezone_get();
        }
    }
}
$usertz = new DateTimeZone($_SESSION['UserTimeZone']);
$usercurtime->setTimestamp($defcurtime->getTimestamp());
$usercurtime->setTimezone($usertz);
// Guest Stuff
if (isset($_SESSION['MemberName']) ||
   isset($_COOKIE['MemberName'])) {
    $_SESSION['GuestName'] = null;
    $_COOKIE['GuestName'] = null;
}
if (!isset($_SESSION['MemberName']) && !isset($_COOKIE['MemberName'])) {
    if (!isset($_SESSION['GuestName']) && isset($_COOKIE['GuestName'])) {
        $_SESSION['GuestName'] = $_COOKIE['GuestName'];
    }
}
if (!isset($_SESSION['LastPostTime'])) {
    $_SESSION['LastPostTime'] = "0";
}
// Skin Stuff
if (!isset($_SESSION['Theme'])) {
    $_SESSION['Theme'] = null;
}
if (!isset($_GET['theme'])) {
    $_GET['theme'] = null;
}
if (!isset($_POST['theme'])) {
    $_POST['theme'] = null;
}
if (!isset($_GET['skin'])) {
    $_GET['skin'] = null;
}
if (!isset($_POST['skin'])) {
    $_POST['skin'] = null;
}
if (!isset($_GET['style'])) {
    $_GET['style'] = null;
}
if (!isset($_POST['style'])) {
    $_POST['style'] = null;
}
if (!isset($_GET['css'])) {
    $_GET['css'] = null;
}
if (!isset($_POST['css'])) {
    $_POST['css'] = null;
}
if ($_GET['theme'] == null) {
    if ($_POST['theme'] != null) {
        $_GET['theme'] = $_POST['theme'];
    }
    if ($_POST['skin'] != null) {
        $_GET['theme'] = $_POST['skin'];
    }
    if ($_POST['style'] != null) {
        $_GET['theme'] = $_POST['style'];
    }
    if ($_POST['css'] != null) {
        $_GET['theme'] = $_POST['css'];
    }
    if ($_GET['skin'] != null) {
        $_GET['theme'] = $_GET['skin'];
    }
    if ($_GET['style'] != null) {
        $_GET['theme'] = $_GET['style'];
    }
    if ($_GET['css'] != null) {
        $_GET['theme'] = $_GET['css'];
    }
}
if ($Settings['SQLThemes'] == "off") {
    if ($_GET['theme'] != null) {
        $_GET['theme'] = chack_themes($_GET['theme']);
        if ($_GET['theme'] == "../" || $_GET['theme'] == "./") {
            $_GET['theme'] = $Settings['DefaultTheme'];
            $_SESSION['Theme'] = $Settings['DefaultTheme'];
        }
        if (file_exists($SettDir['themes'].$_GET['theme']."/settings.php")) {
            if ($_SESSION['UserGroup'] != $Settings['GuestGroup']) {
                $NewDay = $utccurtime->getTimestamp();
                $qnewskin = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"UseTheme\"='%s',\"LastActive\"='%s' WHERE \"id\"=%i", array($_GET['theme'],$NewDay,$_SESSION['UserID']));
                sql_query($qnewskin, $SQLStat);
            }
            /* The file Theme Exists */
        } else {
            $_GET['theme'] = $Settings['DefaultTheme'];
            $_SESSION['Theme'] = $Settings['DefaultTheme'];
            /* The file Theme Dose Not Exists */
        }
    }
    if ($_GET['theme'] == null) {
        if ($_SESSION['Theme'] != null) {
            $OldTheme = $_SESSION['Theme'];
            $_SESSION['Theme'] = chack_themes($_SESSION['Theme']);
            if ($_SESSION['UserGroup'] != $Settings['GuestGroup']) {
                if ($OldTheme != $_SESSION['Theme']) {
                    $NewDay = $utccurtime->getTimestamp();
                    $qnewskin = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"UseTheme\"='%s',\"LastActive\"='%s' WHERE \"id\"=%i", array($_SESSION['Theme'],$NewDay,$_SESSION['UserID']));
                    sql_query($qnewskin, $SQLStat);
                }
            }
            $_GET['theme'] = $_SESSION['Theme'];
        }
        if ($_SESSION['Theme'] == null) {
            $_SESSION['Theme'] = $Settings['DefaultTheme'];
            $_GET['theme'] = $Settings['DefaultTheme'];
        }
    }
    $PreSkin['skindir1'] = $_SESSION['Theme'];
    $PreSkin['skindir2'] = $SettDir['themes'].$_SESSION['Theme'];
    require($SettDir['themes'].$_GET['theme']."/settings.php");
}
if ($Settings['SQLThemes'] == "on") {
    if ($_GET['theme'] == null && $_SESSION['Theme'] == null) {
        $_GET['theme'] = $Settings['DefaultTheme'];
        $_SESSION['Theme'] = $Settings['DefaultTheme'];
    }
    if ($_GET['theme'] != null) {
        $themequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."themes\" WHERE \"Name\"='%s'", array($_GET['theme']));
    }
    $themenum = 0;
    if ($_GET['theme'] == null) {
        if ($_SESSION['Theme'] != null) {
            $themenum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."themes\" WHERE \"Name\"='%s'", array($_SESSION['Theme'])), $SQLStat);
            $themequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."themes\" WHERE \"Name\"='%s'", array($_SESSION['Theme']));
        }
    }
    $themeresult = sql_query($themequery, $SQLStat);
    if ($themenum <= 0) {
        $_GET['theme'] = $Settings['DefaultTheme'];
        $_SESSION['Theme'] = $Settings['DefaultTheme'];
        if ($_SESSION['UserGroup'] != $Settings['GuestGroup']) {
            $NewDay = $utccurtime->getTimestamp();
            $qnewskin = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"UseTheme\"='%s',\"LastActive\"='%s' WHERE \"id\"=%i", array($_SESSION['Theme'],$NewDay,$_SESSION['UserID']));
            sql_query($qnewskin, $SQLStat);
        }
        $themenum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."themes\" WHERE \"Name\"='%s'", array($_SESSION['Theme'])), $SQLStat);
        $themequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."themes\" WHERE \"Name\"='%s'", array($_GET['theme']));
        $themeresult = sql_query($themequery, $SQLStat);
    } else {
        if ($_GET['theme'] == null) {
            if ($_SESSION['Theme'] != null) {
                $_GET['theme'] = $_SESSION['Theme'];
            }
        }
        if ($_SESSION['UserGroup'] != $Settings['GuestGroup']) {
            $NewDay = $utccurtime->getTimestamp();
            $qnewskin = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"UseTheme\"='%s',\"LastActive\"='%s' WHERE \"id\"=%i", array($_GET['theme'],$NewDay,$_SESSION['UserID']));
            sql_query($qnewskin, $SQLStat);
        }
    }
    require($SettDir['inc'].'sqlthemes.php');
    sql_free_result($themeresult);
}
$_SESSION['Theme'] = $_GET['theme'];
function get_theme_values($matches)
{
    global $ThemeSet;
    $return_text = null;
    if (isset($ThemeSet[$matches[1]])) {
        $return_text = $ThemeSet[$matches[1]];
    }
    if (!isset($ThemeSet[$matches[1]])) {
        $return_text = null;
    }
    return $return_text;
}
foreach ($ThemeSet as $key => $value) {
    if (isset($ThemeSet[$key])) {
        $ThemeSet[$key] = preg_replace("/%%/s", "{percent}p", $ThemeSet[$key]);
        $ThemeSet[$key] = preg_replace_callback("/%\{([^\}]*)\}T/s", "get_theme_values", $ThemeSet[$key]);
        $ThemeSet[$key] = preg_replace_callback("/%\{([^\}]*)\}e/s", "get_env_values", $ThemeSet[$key]);
        $ThemeSet[$key] = preg_replace_callback("/%\{([^\}]*)\}i/s", "get_server_values", $ThemeSet[$key]);
        $ThemeSet[$key] = preg_replace_callback("/%\{([^\}]*)\}s/s", "get_setting_values", $ThemeSet[$key]);
        $ThemeSet[$key] = preg_replace_callback("/%\{([^\}]*)\}t/s", "get_time", $ThemeSet[$key]);
        $ThemeSet[$key] = preg_replace("/\{percent\}p/s", "%", $ThemeSet[$key]);
    }
}
if (!isset($ThemeSet['TableStyle'])) {
    $ThemeSet['TableStyle'] = "table";
}
if (isset($ThemeSet['TableStyle'])) {
    if ($ThemeSet['TableStyle'] != "div" &&
        $ThemeSet['TableStyle'] != "table") {
        $ThemeSet['TableStyle'] = "table";
    }
}
if (!isset($_SESSION['DBName'])) {
    $_SESSION['DBName'] = null;
}
if ($_SESSION['DBName'] == null) {
    $_SESSION['DBName'] = $Settings['sqldb'];
}
if ($_SESSION['DBName'] != null) {
    if ($_SESSION['DBName'] != $Settings['sqldb']) {
        redirect("location", $rbasedir.url_maker($exfile['member'], $Settings['file_ext'], "act=logout", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'], false));
    }
}
?>
