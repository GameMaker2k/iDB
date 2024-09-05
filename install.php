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
    iDB Installer made by Game Maker 2k - https://idb.osdn.jp/support/category.php?act=view&id=2support/category.php?act=view&id=2

    $FileInfo: install.php - Last Update: 8/26/2024 SVN 1048 - Author: cooldude2k $
*//*
if(ini_get("register_globals")) {
require_once('inc/misc/killglobals.php'); }
*//* Some ini setting changes uncomment if you need them. 
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
if(!defined("E_DEPRECATED")) { define("E_DEPRECATED", 0); }
@error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
/* Get rid of session id in urls */
if(!in_array("ini_set", $disfunc)) {
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
if(file_exists('extrasettings.php')) {
	require_once('extrasettings.php'); }
if(file_exists('extendsettings.php')) {
	require_once('extendsettings.php'); }
// Custom error handler for non-fatal errors
// Configuration settings
$errorDisplay = true;  // Set to true to display errors on the screen
$errorLogFile = true;  // Set to true to log errors to a file
$logFilePath = __DIR__ . '/logs/php_error_log.txt';  // Define your log file path

// Custom Error Handler Function
function customErrorHandler($errno, $errstr, $errfile, $errline) {
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

    // Flush the output buffer if it exists
    while (ob_get_level()) {
        ob_end_flush();
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

    // Depending on the error, you might want to stop the script
    if ($errno === E_ERROR || $errno === E_PARSE || $errno === E_CORE_ERROR || $errno === E_COMPILE_ERROR) {
        die();
    }

    // Return true to prevent the PHP internal error handler from executing
    return true;
}

// Custom Shutdown Handler Function
function shutdownHandler() {
    global $errorDisplay, $errorLogFile, $logFilePath;

    $last_error = error_get_last();

    // Check if $last_error is not null before accessing its elements
    if ($last_error !== null) {
        // Check if the error type is E_ERROR or E_PARSE (fatal errors)
        if ($last_error['type'] === E_ERROR || $last_error['type'] === E_PARSE || $last_error['type'] === E_CORE_ERROR || $last_error['type'] === E_COMPILE_ERROR) {
            // Flush the output buffer if it exists
            while (ob_get_level()) {
                ob_end_flush();
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
        }
    }
}

// Custom Exception Handler Function
function customExceptionHandler($exception) {
    global $errorDisplay, $errorLogFile, $logFilePath;

    // Flush the output buffer if it exists
    while (ob_get_level()) {
        ob_end_flush();
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

    // Stop the script after an uncaught exception
    die();
}

// Function to Convert Backtrace Array to String for Display/Logging
function getBacktraceAsString($backtrace) {
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
function logErrorToFile($logFile, $errorType, $errno, $errstr, $errfile, $errline, $backtrace) {
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
if(!isset($Settings['qstr'])) { $Settings['qstr'] = null; }
if(!isset($Settings['send_pagesize'])) { $Settings['send_pagesize'] = "off"; }
$deftz = new DateTimeZone(date_default_timezone_get());
$defcurtime = new DateTime();
$defcurtime->setTimezone($deftz);
$utctz = new DateTimeZone("UTC");
$utccurtime = new DateTime();
$utccurtime->setTimestamp($defcurtime->getTimestamp());
$utccurtime->setTimezone($utctz);
$servcurtime = new DateTime();
$servcurtime->setTimestamp($defcurtime->getTimestamp());
$usercurtime = new DateTime();
$usercurtime->setTimestamp($defcurtime->getTimestamp());
/* Do not change anything below this line unless you know what you are doing */
if(!isset($Settings['clean_ob'])) { $Settings['clean_ob'] = "off"; }
function idb_output_handler($buffer) { return $buffer; }
function idb_suboutput_handler($buffer) { return $buffer; }
if($Settings['clean_ob']=="on") {
/* Check for other output handlers/buffers are open
   and close and get the contents in an array */
$numob = count(ob_list_handlers()); $iob = 0; 
while ($iob < $numob) { 
	$old_ob_var[$iob] = ob_get_clean(); 
	++$iob; } } ob_start("idb_output_handler");
if(ini_get("register_globals")) { 
	if(!isset($SettDir['misc'])) { $SettDir['misc'] = "inc/misc/"; }
	require_once($SettDir['misc'].'killglobals.php'); }
if(!isset($preact['idb'])) { $preact['idb'] = null; }
if(!isset($_GET['act'])) { $_GET['act'] = null; }
if(!isset($_POST['act'])) { $_POST['act'] = null; }
if ($_GET['act']==null||$_GET['act']=="view") { $_GET['act']="Part1"; }
if ($_POST['act']==null||$_POST['act']=="view") { $_POST['act']="Part1"; }
$_TEG = array(null); $_TEG['part'] = preg_replace("/Part(1|2|3|4)/","\\1",$_GET['act']);
$_GET['act'] = strtolower($_GET['act']); if(isset($_TEG['part'])) {
if($_TEG['part']<=4&&$_TEG['part']>=1) { $_GET['act'] = "Part".$_TEG['part']; } }
if ($_GET['act']!="Part4"&&$_POST['act']!="Part4") {
	$preact['idb'] = "installing";	}
$SetupDir['setup'] = "setup/"; $ConvertDir['setup'] = $SetupDir['setup']; $SetupDir['sql'] = "setup/sql/"; 
$SetupDir['convert'] = "setup/convert/"; $ConvertDir['convert'] = $SetupDir['convert']; $ConvertDir['sql'] = $SetupDir['sql'];
$Settings['output_type'] = "html"; $Settings['html_type'] = "html5";
if(isset($iD)) {
$Settings['board_name'] = $iDB; }
if(!isset($Settings['charset'])) {
	$Settings['charset'] = "ISO-8859-15"; 
	header("Content-Type: text/html; charset=ISO-8859-15"); }
if(isset($Settings['charset'])) {
if($Settings['charset']!="ISO-8859-15"&&$Settings['charset']!="ISO-8859-1"&&
	$Settings['charset']!="UTF-8"&&$Settings['charset']!="CP866"&&
	$Settings['charset']!="Windows-1251"&&$Settings['charset']!="Windows-1252"&&
	$Settings['charset']!="KOI8-R"&&$Settings['charset']!="BIG5"&&
	$Settings['charset']!="GB2312"&&$Settings['charset']!="BIG5-HKSCS"&&
	$Settings['charset']!="Shift_JIS"&&$Settings['charset']!="EUC-JP") {
	$Settings['charset'] = "ISO-8859-15"; 
	header("Content-Type: text/html; charset=ISO-8859-15"); } }
$SQLCharset = "latin1";
if(isset($_POST['charset'])) { 
if($_POST['charset']=="ISO-8859-1") {
	$SQLCharset = "latin1"; }
if($_POST['charset']=="ISO-8859-15") {
	$SQLCharset = "latin1"; }
if($_POST['charset']=="UTF-8") {
	$SQLCharset = "utf8"; }
	$Settings['charset'] = $_POST['charset']; }
$ServHTTPS = "off";
if(isset($_SERVER['HTTPS'])) { $ServHTTPS=="on"; }
if(isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']=="on") { $ServHTTPS=="on"; }
if(isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']=="off") { $ServHTTPS=="off"; }
if(!isset($_SERVER['HTTPS'])) { $ServHTTPS=="off"; }
if($ServHTTPS=="on") { $prehost = "https://"; }
if($ServHTTPS=="off") { $prehost = "http://"; }
$this_dir = null;
if(dirname($_SERVER['SCRIPT_NAME'])!="."||
	dirname($_SERVER['SCRIPT_NAME'])!=null) {
$this_dir = dirname($_SERVER['SCRIPT_NAME'])."/"; }
if($this_dir==null||$this_dir==".") {
if(dirname($_SERVER['SCRIPT_NAME'])=="."||
	dirname($_SERVER['SCRIPT_NAME'])==null) {
$this_dir = dirname($_SERVER['PHP_SELF'])."/"; } }
if($this_dir=="\/") { $this_dir="/"; }
$this_dir = str_replace("//", "/", $this_dir);
$idbdir = addslashes(str_replace("\\","/",dirname(__FILE__)."/"));
if(!isset($_POST['BoardURL'])) { 
   $Settings['idburl'] = $prehost.$_SERVER["HTTP_HOST"].$this_dir; }
if(isset($_POST['BoardURL'])) { 
   $Settings['idburl'] = $_POST['BoardURL']; }
$Settings['qstr'] = "&";
$Settings['qsep'] = "=";
require($SetupDir['setup'].'preinstall.php');
require_once($SettDir['misc'].'utf8.php');
require_once($SettDir['inc'].'filename.php');
require_once($SettDir['inc'].'function.php');
if($_GET['act']=="README"||$_GET['act']=="ReadME") { $_GET['act']="readme"; }
if($_GET['act']=="readme"||$_GET['act']=="ReadMe") {
header("Content-Type: text/plain; charset=".$Settings['charset']);
require("README"); fix_amp(null); die(); }
if($_GET['act']=="LICENSE"||$_GET['act']=="License") { $_GET['act']="license"; }
if($_GET['act']=="license"||$_GET['act']=="BSD") {
header("Content-Type: text/plain; charset=".$Settings['charset']);
require("LICENSE"); fix_amp(null); die(); }
if($_GET['act']=="TOS"||$_GET['act']=="ToS") { $_GET['act']="tos"; }
if($_GET['act']=="tos"||$_GET['act']=="terms") {
header("Content-Type: text/plain; charset=".$Settings['charset']);
require("TOS"); fix_amp(null); die(); }
$Settings['board_name'] = $RFullName;
function get_theme_values($matches) {
	global $ThemeSet;
	$return_text = null;
	if(isset($ThemeSet[$matches[1]])) { $return_text = $ThemeSet[$matches[1]]; }
	if(!isset($ThemeSet[$matches[1]])) { $return_text = null; }
	return $return_text; }
foreach($ThemeSet AS $key => $value) {
	if(isset($ThemeSet[$key])) {
	$ThemeSet[$key] = preg_replace("/%%/s", "{percent}p", $ThemeSet[$key]);
	$ThemeSet[$key] = preg_replace_callback("/%\{([^\}]*)\}T/s", "get_theme_values", $ThemeSet[$key]);
	$ThemeSet[$key] = preg_replace_callback("/%\{([^\}]*)\}e/s", "get_env_values", $ThemeSet[$key]);
	$ThemeSet[$key] = preg_replace_callback("/%\{([^\}]*)\}i/s", "get_server_values", $ThemeSet[$key]);
	$ThemeSet[$key] = preg_replace_callback("/%\{([^\}]*)\}s/s", "get_setting_values", $ThemeSet[$key]);
	$ThemeSet[$key] = preg_replace_callback("/%\{([^\}]*)\}t/s", "get_time", $ThemeSet[$key]); 
	$ThemeSet[$key] = preg_replace("/\{percent\}p/s", "%", $ThemeSet[$key]); } }
require($SetupDir['convert'].'info.php');
require($SetupDir['setup'].'html5.php');
$Error = null; $_GET['time'] = false;
$title_html = htmlentities("Installing ".$VerInfo['iDB_Ver_Show'], ENT_QUOTES, $Settings['charset']);
?>
<meta itemprop="title" property="og:title" content="<?php echo $title_html; ?>" />
<meta itemprop="sitename" property="og:site_name" content="<?php echo $title_html; ?>" />
<meta itemprop="title" property="twitter:title" content="<?php echo $title_html; ?>" />
<meta name="title" content="<?php echo $title_html; ?>" />
<title> <?php echo "Installing ".$VerInfo['iDB_Ver_Show']; ?> </title>
</head>
<body>
<?php require($SettDir['inc'].'navbar.php'); ?>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="font-weight: bold; text-align: left;"><?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker("install",".php","act=Part1","&","=",null,null); ?>">Install <?php echo $VerInfo['iDB_Ver_Show']; ?> </a></span>
</div>
<?php } ?>
<table class="Table1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1">
<td class="TableColumn1"><span style="font-weight: bold; text-align: left;"><?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker("install",".php","act=Part1","&","=",null,null); ?>">Install <?php echo $VerInfo['iDB_Ver_Show']; ?> </a></span>
</td>
</tr><?php } ?>
<tr class="TableRow2">
<th class="TableColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&#160;Inert your install info: </span>
<span style="float: right;">&#160;</span>
</th>
</tr>
<?php
if($ServHTTPS=="on") { $prehost = "https://"; }
if($ServHTTPS!="on") { $prehost = "http://"; }
$this_dir = null;
if(dirname($_SERVER['SCRIPT_NAME'])!="."||
	dirname($_SERVER['SCRIPT_NAME'])!=null) {
$this_dir = dirname($_SERVER['SCRIPT_NAME'])."/"; }
if($this_dir==null||$this_dir==".") {
if(dirname($_SERVER['SCRIPT_NAME'])=="."||
	dirname($_SERVER['SCRIPT_NAME'])==null) {
$this_dir = dirname($_SERVER['PHP_SELF'])."/"; } }
if($this_dir=="\/") { $this_dir="/"; }
$this_dir = str_replace("//", "/", $this_dir);
$idbdir = addslashes(str_replace("\\","/",dirname(__FILE__)."/"));
function sql_list_dbs() {
   $result = sql_query("SHOW DATABASES;",$SQLStat);
   while( $data = sql_fetch_row($result) ) {
       $array[] = $data[0];
   } return $array; }
if ($_GET['act']=="Part1"&&$_POST['act']=="Part1") {
if ($_GET['act']!="Part2"&&$_POST['act']!="Part2") {
if ($_GET['act']!="Part3"&&$_POST['act']!="Part3") {
if ($_GET['act']!="Part4"&&$_POST['act']!="Part4") {
   require($SetupDir['setup'].'license.php'); } } } }
if ($_GET['act']!="Part1"&&$_POST['act']!="Part1") {
if ($_GET['act']=="Part2"&&$_POST['act']=="Part2") {
if ($_GET['act']!="Part3"&&$_POST['act']!="Part3") {
if ($_GET['act']!="Part4"&&$_POST['act']!="Part4") {
   require($SetupDir['setup'].'presetup.php'); } } } }
if($_POST['SetupType']=="convert") {
	require($ConvertInfo['ConvertFile']); }
if($_POST['SetupType']=="install") {
if ($_GET['act']!="Part1"&&$_POST['act']!="Part1") {
if ($_GET['act']!="Part2"&&$_POST['act']!="Part2") {
if ($_GET['act']=="Part3"&&$_POST['act']=="Part3") {
if ($_GET['act']!="Part4"&&$_POST['act']!="Part4") {
   require($SetupDir['setup'].'setup.php'); } } } } }
if($_POST['SetupType']=="install") {
if ($_GET['act']!="Part1"&&$_POST['act']!="Part1") {
if ($_GET['act']!="Part2"&&$_POST['act']!="Part2") {
if ($_GET['act']!="Part3"&&$_POST['act']!="Part3") {
if ($_GET['act']=="Part4"&&$_POST['act']=="Part4") {
   require($SetupDir['setup'].'mkconfig.php'); } } } } }
if ($Error=="Yes") { ?>
<br />Install Failed with errors. <a href="<?php echo url_maker("install",".php","act=Part1","&","=",null,null); ?>">Click here</a> to restart install. &lt;_&lt;
<br /><br />
</td>
</tr>
<?php } ?>
<tr class="TableRow4">
<td class="TableColumn4">&#160;<a href="<?php echo url_maker("install",".php","act=ReadMe","&","=",null,null); ?>">Readme.txt</a>&#160;|&#160;<a href="<?php echo url_maker("install",".php","act=License","&","=",null,null); ?>">License.txt</a>&#160;</td>
</tr>
</table></div>
<div>&#160;</div>
<?php 
require($SettDir['inc'].'endpage.php'); 
?>
</body>
</html>
<?php
fix_amp(null);
?>
