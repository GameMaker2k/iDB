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

    $FileInfo: mkconfig.php - Last Update: 8/30/2024 SVN 1064 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "mkconfig.php" || $File3Name == "/mkconfig.php") {
    require('index.php');
    exit();
}
require_once('settings.php');
if (!isset($SetupDir['setup'])) {
    $SetupDir['setup'] = "setup/";
}
if (!isset($SetupDir['sql'])) {
    $SetupDir['sql'] = "setup/sql/";
}
if (!isset($SetupDir['convert'])) {
    $SetupDir['convert'] = "setup/convert/";
}
$_POST['DatabaseHost'] = $Settings['sqlhost'];
$_POST['DatabaseUserName'] = $Settings['sqluser'];
$_POST['DatabasePassword'] = $Settings['sqlpass'];
$Settings['charset'] = $_POST['charset'];
$Settings['sqltype'] = $_POST['DatabaseType'];
if (!isset($_POST['DefaultTheme'])) {
    $_POST['DefaultTheme'] = "iDB";
}
if (isset($_POST['DefaultTheme'])) {
    $_POST['DefaultTheme'] = chack_themes($_POST['DefaultTheme']);
}
$Settings['vercheck'] = 2;
if (!isset($_POST['SQLThemes'])) {
    $_POST['SQLThemes'] = "off";
}
if ($_POST['SQLThemes'] != "on" && $_POST['SQLThemes'] != "off") {
    $_POST['SQLThemes'] = "off";
}
$disfunc = @ini_get("disable_functions");
$disfunc = @trim($disfunc);
$disfunc = @preg_replace("/([\\s+|\\t+|\\n+|\\r+|\\0+|\\x0B+])/i", "", $disfunc);
if ($disfunc != "ini_set") {
    $disfunc = explode(",", $disfunc);
}
if ($disfunc == "ini_set") {
    $disfunc = array("ini_set");
}
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
if ($_POST['tableprefix'] == null || $_POST['tableprefix'] == "_") {
    $_POST['tableprefix'] = "idb_";
}
if (!isset($_POST['sessprefix'])) {
    $_POST['sessprefix'] = null;
}
if ($_POST['sessprefix'] == null || $_POST['sessprefix'] == "_") {
    $_POST['sessprefix'] = "idb_";
}
$checkfile = "settings.php";
@chmod("settings.php", 0766);
@chmod("settingsbak.php", 0766);
if (!is_writable($checkfile)) {
    echo "<br />Settings is not writable.";
    @chmod("settings.php", 0766);
    $Error = "Yes";
    @chmod("settingsbak.php", 0766);
} else { /* settings.php is writable install iDB. ^_^ */
}
if (session_id()) {
    session_destroy();
}
session_name($_POST['tableprefix']."sess");
if (preg_match("/\/$/", $_POST['BoardURL']) < 1) {
    $_POST['BoardURL'] = $_POST['BoardURL']."/";
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
$OrgBoardURL = $_POST['BoardURL'];
$PreBestURL = parse_url($_POST['BoardURL']);
$PreServURL = parse_url((isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/') + 1));
if ($PreBestURL['host'] == "localhost.url" && str_replace("/", "", $PreBestURL['path']) == "localpath") {
    $PreBestURL['host'] = $PreServURL['host'];
    $PreBestURL['path'] = $PreServURL['path'];
    $_POST['BoardURL'] = unparse_url($PreBestURL);
}
if ($PreBestURL['host'] == "localhost.url" && str_replace("/", "", $PreBestURL['path']) != "localpath") {
    $PreBestURL['host'] = $PreServURL['host'];
    $_POST['BoardURL'] = unparse_url($PreBestURL);
}
if ($PreBestURL['host'] != "localhost.url" && str_replace("/", "", $PreBestURL['path']) == "localpath") {
    $PreBestURL['path'] = $PreServURL['path'];
    $_POST['BoardURL'] = unparse_url($PreBestURL);
}
$OrgWebSiteURL = $_POST['WebURL'];
$PreWestURL = parse_url($_POST['WebURL']);
if ($PreWestURL['host'] == "localhost.url" && str_replace("/", "", $PreWestURL['path']) == "localpath") {
    $PreWestURL['host'] = $PreServURL['host'];
    $PreWestURL['path'] = $PreServURL['path'];
    $_POST['WebURL'] = unparse_url($PreWestURL);
}
if ($PreWestURL['host'] == "localhost.url" && str_replace("/", "", $PreWestURL['path']) != "localpath") {
    $PreWestURL['host'] = $PreServURL['host'];
    $_POST['WebURL'] = unparse_url($PreWestURL);
}
if ($PreWestURL['host'] != "localhost.url" && str_replace("/", "", $PreWestURL['path']) == "localpath") {
    $PreWestURL['path'] = $PreServURL['path'];
    $_POST['WebURL'] = unparse_url($PreWestURL);
}
$URLsTest = parse_url($_POST['BoardURL']);
$this_dir = $URLsTest['path'];
$Settings['enable_https'] = "off";
if ($URLsTest['scheme'] == "https") {
    $Settings['enable_https'] = "on";
}
session_set_cookie_params(0, $this_dir, $URLsTest['host']);
session_cache_limiter("private, no-cache, no-store, must-revalidate, pre-check=0, post-check=0, max-age=0");
header("Cache-Control: private, no-cache, no-store, must-revalidate, pre-check=0, post-check=0, max-age=0");
header("Pragma: private, no-cache, no-store, must-revalidate, pre-check=0, post-check=0, max-age=0");
header("Date: ".$utccurtime->format("D, d M Y H:i:s")." GMT");
header("Last-Modified: ".$utccurtime->format("D, d M Y H:i:s")." GMT");
header("Expires: ".$utccurtime->format("D, d M Y H:i:s")." GMT");
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
        'name' => $_POST['tableprefix']."sess",
    ]);
}
//@register_shutdown_function("session_write_close");
if (pre_strlen($_POST['AdminPasswords']) < "3") {
    $Error = "Yes";
    echo "<br />Your password is too small.";
}
if (pre_strlen($_POST['AdminUser']) < "3") {
    $Error = "Yes";
    echo "<br />Your user name is too small.";
}
if (pre_strlen($_POST['AdminUser']) < "3") {
    $Error = "Yes";
    echo "<br />Your user name is too small.";
}
if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $_POST['AdminUser'])) {
    $Error = "Yes";
    echo "<br />Your handel is invalid.";
}
if (pre_strlen($_POST['AdminHandle']) < "3") {
    $Error = "Yes";
    echo "<br />Your email name is too small.";
}
if (!filter_var($_POST['AdminEmail'], FILTER_VALIDATE_EMAIL)) {
    $Error = "Yes";
    echo "<br />Your email is not a valid email address.";
}
if (!filter_var($_POST['BoardURL'], FILTER_VALIDATE_URL)) {
    $Error = "Yes";
    echo "<br />Your board url is not a valid web url.";
}
if (!filter_var($OrgBoardURL, FILTER_VALIDATE_URL)) {
    $Error = "Yes";
    echo "<br />Your board url is not a valid web url.";
}
if (!filter_var($_POST['WebURL'], FILTER_VALIDATE_URL) && $_POST['WebURL'] != "localhost") {
    $Error = "Yes";
    echo "<br />Your website url is not a valid web url.";
}
if (!filter_var($OrgWebSiteURL, FILTER_VALIDATE_URL) && $OrgWebSiteURL != "localhost") {
    $Error = "Yes";
    echo "<br />Your website url is not a valid web url.";
}
if (pre_strlen($_POST['AdminPasswords']) > "60") {
    $Error = "Yes";
    echo "<br />Your password is too big.";
}
if (pre_strlen($_POST['AdminUser']) > "30") {
    $Error = "Yes";
    echo "<br />Your user name is too big.";
}
if (pre_strlen($_POST['AdminHandle']) > "30") {
    $Error = "Yes";
    echo "<br />Your user name is too big.";
}
if ($_POST['AdminPasswords'] != $_POST['ReaPassword']) {
    $Error = "Yes";
    echo "<br />Your passwords did not match.";
}
if ($_POST['startblank'] != "yes" && $_POST['startblank'] != "no") {
    $_POST['startblank'] = "no";
}
if ($_POST['testdata'] != "yes" && $_POST['testdata'] != "no") {
    $_POST['testdata'] = "yes";
}
if ($_POST['startblank'] == "yes" && $_POST['testdata'] == "yes") {
    $_POST['testdata'] = "no";
}
if ($_POST['HTMLType'] == "html4") {
    $_POST['OutPutType'] = "html";
}
if ($_POST['HTMLType'] == "xhtml10") {
    $_POST['OutPutType'] = "xhtml";
}
if ($_POST['HTMLType'] == "xhtml11") {
    $_POST['OutPutType'] = "xhtml";
}
if ($_POST['HTMLType'] == "html5") {
    $_POST['OutPutType'] = "html";
}
if ($_POST['HTMLType'] == "xhtml5") {
    $_POST['OutPutType'] = "xhtml";
}
$_POST['BoardURL'] = htmlentities($_POST['BoardURL'], ENT_QUOTES, $Settings['charset']);
$_POST['BoardURL'] = remove_spaces($_POST['BoardURL']);
$_POST['BoardURL'] = addslashes($_POST['BoardURL']);
$OrgBoardURL = htmlentities($OrgBoardURL, ENT_QUOTES, $Settings['charset']);
$OrgBoardURL = remove_spaces($OrgBoardURL);
$OrgBoardURL = addslashes($OrgBoardURL);
$YourDate = $utccurtime->getTimestamp();
$YourEditDate = $YourDate + $dayconv['minute'];
$GSalt = salt_hmac();
$YourSalt = salt_hmac();
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
if (!function_exists('hash') && !function_exists('hash_algos')) {
    if ($_POST['usehashtype'] != "md5" &&
       $_POST['usehashtype'] != "sha1") {
        $_POST['usehashtype'] = "sha1";
    }
}
if (function_exists('hash') && function_exists('hash_algos')) {
    if (!in_array($_POST['usehashtype'], hash_algos())) {
        $_POST['usehashtype'] = "sha1";
    }
    if ($_POST['usehashtype'] != "md2" &&
       $_POST['usehashtype'] != "md4" &&
       $_POST['usehashtype'] != "md5" &&
       $_POST['usehashtype'] != "sha1" &&
       $_POST['usehashtype'] != "sha224" &&
       $_POST['usehashtype'] != "sha256" &&
       $_POST['usehashtype'] != "sha384" &&
       $_POST['usehashtype'] != "sha512" &&
       $_POST['usehashtype'] != "sha3-224" &&
       $_POST['usehashtype'] != "sha3-256" &&
       $_POST['usehashtype'] != "sha3-384" &&
       $_POST['usehashtype'] != "sha3-512" &&
       $_POST['usehashtype'] != "ripemd128" &&
       $_POST['usehashtype'] != "ripemd160" &&
       $_POST['usehashtype'] != "ripemd256" &&
       $_POST['usehashtype'] != "ripemd320" &&
       $_POST['usehashtype'] != "bcrypt" &&
       $_POST['usehashtype'] != "argon2i" &&
       $_POST['usehashtype'] != "argon2id") {
        $_POST['usehashtype'] = "sha1";
    }
}
if ($_POST['usehashtype'] == "md2") {
    $iDBHashType = "iDBH2";
}
if ($_POST['usehashtype'] == "md4") {
    $iDBHashType = "iDBH4";
}
if ($_POST['usehashtype'] == "md5") {
    $iDBHashType = "iDBH5";
}
if ($_POST['usehashtype'] == "sha1") {
    $iDBHashType = "iDBH";
}
if ($_POST['usehashtype'] == "sha224") {
    $iDBHashType = "iDBH224";
}
if ($_POST['usehashtype'] == "sha256") {
    $iDBHashType = "iDBH256";
}
if ($_POST['usehashtype'] == "sha384") {
    $iDBHashType = "iDBH384";
}
if ($_POST['usehashtype'] == "sha512") {
    $iDBHashType = "iDBH512";
}
if ($_POST['usehashtype'] == "sha3-224") {
    $iDBHashType = "iDBH3224";
}
if ($_POST['usehashtype'] == "sha3-256") {
    $iDBHashType = "iDBH3256";
}
if ($_POST['usehashtype'] == "sha3-384") {
    $iDBHashType = "iDBH3384";
}
if ($_POST['usehashtype'] == "sha3-512") {
    $iDBHashType = "iDBH3512";
}
if ($_POST['usehashtype'] == "ripemd128") {
    $iDBHashType = "iDBHRMD128";
}
if ($_POST['usehashtype'] == "ripemd160") {
    $iDBHashType = "iDBHRMD160";
}
if ($_POST['usehashtype'] == "ripemd256") {
    $iDBHashType = "iDBHRMD256";
}
if ($_POST['usehashtype'] == "ripemd320") {
    $iDBHashType = "iDBHRMD320";
}
if ($_POST['usehashtype'] == "bcrypt") {
    $iDBHashType = "iDBCRYPT";
}
if ($_POST['usehashtype'] == "argon2i") {
    $iDBHashType = "iDBARGON2I";
}
if ($_POST['usehashtype'] == "argon2id") {
    $iDBHashType = "iDBARGON2ID";
}
if ($_POST['AdminUser'] == "Guest") {
    $Error = "Yes";
    echo "<br />You can not use Guest as your name.";
}
if ($_POST['AdminHandle'] == "guest") {
    $Error = "Yes";
    echo "<br />You can not use guest as your name.";
}
/* We are done now with fixing the info. ^_^ */
$SQLStat = sql_connect_db($_POST['DatabaseHost'], $_POST['DatabaseUserName'], $_POST['DatabasePassword'], $_POST['DatabaseName']);
if (isset($_POST['sqlcollate'])) {
    $Settings['sql_collate'] = $_POST['sqlcollate'];
}
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
    if ($Settings['sql_charset'] == "utf8mb3" || $Settings['sql_charset'] == "utf8mb4") {
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
    $Error = "Yes";
    echo "<br />".sql_errorno($SQLStat)."\n";
}
if ($Error != "Yes") {
    $ServerUUID = rand_uuid("rand");
    $MyDay = $usercurtime->format("d");
    $MyMonth = $usercurtime->format("m");
    $MyYear = $usercurtime->format("Y");
    $MyYear10 = $MyYear + 10;
    $YourDateEnd = $YourDate;
    $EventMonth = $utccurtime->format("m");
    $EventMonthEnd = $utccurtime->format("m");
    $EventDay = $utccurtime->format("d");
    $EventDayEnd = $utccurtime->format("d");
    $EventYear = $utccurtime->format("Y");
    $EventYearEnd = $utccurtime->format("Y");
    $KarmaBoostDay = $EventMonth.$EventDay;
    $Settings['idb_time_format'] = "g:i A";
    if (!isset($_POST['iDBTimeFormat'])) {
        $_POST['iDBTimeFormat'] = "g:i A";
    }
    if (isset($_POST['iDBTimeFormat'])) {
        $_POST['iDBTimeFormat'] = convert_strftime($_POST['iDBTimeFormat']);
    }
    $Settings['idb_date_format'] = "F j Y";
    if (!isset($_POST['iDBDateFormat'])) {
        $_POST['iDBDateFormat'] = "F j Y";
    }
    if (isset($_POST['iDBDateFormat'])) {
        $_POST['iDBDateFormat'] = convert_strftime($_POST['iDBDateFormat']);
    }
    if (!isset($_POST['iDBHTTPLogger'])) {
        $_POST['iDBHTTPLogger'] = "off";
    }
    if (isset($_POST['iDBHTTPLogger']) && $_POST['iDBHTTPLogger'] != "on" && $_POST['iDBHTTPLogger'] != "off") {
        $_POST['iDBHTTPLogger'] = "off";
    }
    if (!isset($_POST['iDBLoggerFormat'])) {
        $_POST['iDBLoggerFormat'] = "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"";
    }
    $Settings['idb_time_format'] = $_POST['iDBTimeFormat'];
    $Settings['idb_date_format'] = $_POST['iDBDateFormat'];
    $NewPassword = b64e_hmac($_POST['AdminPasswords'], $YourDate, $YourSalt, $_POST['usehashtype']);
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
    $grand = rand(6, 16);
    $i = 0;
    $gpass = "";
    while ($i < $grand) {
        $csrand = rand(1, 3);
        if ($csrand != 1 && $csrand != 2 && $csrand != 3) {
            $csrand = 1;
        }
        if ($csrand == 1) {
            $gpass .= chr(rand(48, 57));
        }
        if ($csrand == 2) {
            $gpass .= chr(rand(65, 90));
        }
        if ($csrand == 3) {
            $gpass .= chr(rand(97, 122));
        }
        ++$i;
    } $GuestPassword = b64e_hmac($gpass, $YourDate, $GSalt, $_POST['usehashtype']);
    $url_this_dir = "http://".$_SERVER['HTTP_HOST'].$this_dir."index.php?act=view";
    $YourIP = $_SERVER['REMOTE_ADDR'];
    if ($Settings['sqltype'] == "mysqli" ||
        $Settings['sqltype'] == "mysqli_prepare" ||
        $Settings['sqltype'] == "pdo_mysql") {
        require($SetupDir['sql'].'mysql.php');
    }
    if ($Settings['sqltype'] == "pgsql" ||
        $Settings['sqltype'] == "pgsql_prepare" ||
        $Settings['sqltype'] == "pdo_pgsql") {
        require($SetupDir['sql'].'pgsql.php');
    }
    if ($Settings['sqltype'] == "sqlite3" ||
        $Settings['sqltype'] == "sqlite3_prepare" ||
        $Settings['sqltype'] == "pdo_sqlite3") {
        require($SetupDir['sql'].'sqlite.php');
    }
    if ($Settings['sqltype'] == "cubrid" ||
        $Settings['sqltype'] == "cubrid_prepare" ||
        $Settings['sqltype'] == "pdo_cubrid") {
        require($SetupDir['sql'].'cubrid.php');
    }
    if ($Settings['sqltype'] == "mysqli" ||
        $Settings['sqltype'] == "mysqli_prepare" ||
        $Settings['sqltype'] == "pdo_mysql" ||
        $Settings['sqltype'] == "pgsql" ||
        $Settings['sqltype'] == "pgsql_prepare" ||
        $Settings['sqltype'] == "pdo_pgsql" ||
        $Settings['sqltype'] == "sqlite3" ||
        $Settings['sqltype'] == "sqlite3_prepare" ||
        $Settings['sqltype'] == "pdo_sqlite3" ||
        $Settings['sqltype'] == "cubrid" ||
        $Settings['sqltype'] == "cubrid_prepare" ||
        $Settings['sqltype'] == "pdo_cubrid") {
        if ($_POST['startblank'] == "no") {
            $cnum = 0;
            if ($_POST['testdata'] == "yes") {
                $cnum = 1;
            }
        }
        if ($_POST['startblank'] == "no") {
            $query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."categories\" (\"OrderID\", \"Name\", \"ShowCategory\", \"CategoryType\", \"SubShowForums\", \"InSubCategory\", \"PostCountView\", \"KarmaCountView\", \"Description\")\n".
            "VALUES (1, 'A Test Category', 'yes', 'category', 'yes', 0, 0, 0, 'A test category that may be removed at any time.');", null);
            sql_query($query, $SQLStat);
        }
        $query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."catpermissions\" (\"PermissionID\", \"Name\", \"CategoryID\", \"CanViewCategory\") VALUES\n".
        "(1, 'Admin', 0, 'yes'),\n".
        "(2, 'Moderator', 0, 'yes'),\n".
        "(3, 'Member', 0, 'yes'),\n".
        "(4, 'Guest', 0, 'yes'),\n".
        "(5, 'Banned', 0, 'no'),\n".
        "(6, 'Validate', 0, 'yes');", null);
        sql_query($query, $SQLStat);
        if ($_POST['startblank'] == "no") {
            $query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."catpermissions\" (\"PermissionID\", \"Name\", \"CategoryID\", \"CanViewCategory\") VALUES\n".
            "(1, 'Admin', 1, 'yes'),\n".
            "(2, 'Moderator', 1, 'yes'),\n".
            "(3, 'Member', 1, 'yes'),\n".
            "(4, 'Guest', 1, 'yes'),\n".
            "(5, 'Banned', 1, 'no'),\n".
            "(6, 'Validate', 1, 'yes');", null);
            sql_query($query, $SQLStat);
        }
        if ($_POST['testdata'] == "yes") {
            $query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."events\" (\"UserID\", \"GuestName\", \"EventName\", \"EventText\", \"TimeStamp\", \"TimeStampEnd\", \"EventMonth\", \"EventMonthEnd\", \"EventDay\", \"EventDayEnd\", \"EventYear\", \"EventYearEnd\", \"IP\") VALUES\n".
            "(-1, '".$iDB_Author."', 'iDB Install', 'This is the start date of your board. ^_^', %i, %i, %i, %i, %i, %i, %i, %i, '%s');", array($YourDate,$YourDateEnd,$EventMonth,$EventMonthEnd,$EventDay,$EventDayEnd,$EventYear,$EventYearEnd,$GuestLocalIP));
            sql_query($query, $SQLStat);
        }
        if ($_POST['startblank'] == "no") {
            $query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."forums\" (\"CategoryID\", \"OrderID\", \"Name\", \"ShowForum\", \"ForumType\", \"InSubForum\", \"RedirectURL\", \"Redirects\", \"NumViews\", \"Description\", \"PostCountAdd\", \"PostCountView\", \"KarmaCountView\", \"CanHaveTopics\", \"HotTopicPosts\", \"NumPosts\", \"NumTopics\") VALUES\n".
            "(1, 1, 'A Test Forum', 'yes', 'forum', 0, 'http://', 0, 0, 'A test forum that may be removed at any time.', 'off', 0, 0, 'yes', 15, %i, %i);", array($cnum, $cnum));
            sql_query($query, $SQLStat);
        }
        $query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."groups\" (\"Name\", \"PermissionID\", \"NamePrefix\", \"NameSuffix\", \"CanViewBoard\", \"CanViewOffLine\", \"CanEditProfile\", \"CanAddEvents\", \"CanPM\", \"CanSearch\", \"CanExecPHP\", \"CanDoHTML\", \"CanUseBBTags\", \"CanModForum\", \"CanViewIPAddress\", \"CanViewUserAgent\", \"CanViewAnonymous\", \"FloodControl\", \"SearchFlood\", \"PromoteTo\", \"PromotePosts\", \"PromoteKarma\", \"DemoteTo\", \"DemotePosts\", \"DemoteKarma\", \"HasModCP\", \"HasAdminCP\", \"ViewDBInfo\") VALUES\n".
        "('Admin', 1, '', '', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 30, 30, 0, 0, 0, 0, 0, 0, 'yes', 'yes', 'yes'),\n".
        "('Moderator', 2, '', '', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 30, 30, 0, 0, 0, 0, 0, 0, 'yes', 'no', 'no'),\n".
        "('Member', 3, '', '', 'yes', 'no', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'yes', 'no', 'no', 'no', 'no', 30, 30, 0, 0, 0, 0, 0, 0, 'no', 'no', 'no'),\n".
        "('Guest', 4, '', '', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'no', 'no', 'no', 'no', 30, 30, 0, 0, 0, 0, 0, 0, 'no', 'no', 'no'),\n".
        "('Banned', 5, '', '', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 30, 30, 0, 0, 0, 0, 0, 0, 'no', 'no', 'no'),\n".
        "('Validate', 6, '', '', 'yes', 'no', 'yes', 'no', 'no', 'yes', 'no', 'no', 'yes', 'no', 'no', 'no', 'no', 30, 30, 0, 0, 0, 0, 0, 0, 'no', 'no', 'no');", null);
        sql_query($query, $SQLStat);
        $query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."members\" (\"id\", \"Name\", \"Handle\", \"UserPassword\", \"HashType\", \"Email\", \"GroupID\", \"LevelID\", \"RankID\", \"Validated\", \"HiddenMember\", \"WarnLevel\", \"Interests\", \"Title\", \"Joined\", \"LastActive\", \"LastLogin\", \"LastPostTime\", \"BanTime\", \"BirthDay\", \"BirthMonth\", \"BirthYear\", \"Signature\", \"Notes\", \"Bio\", \"Avatar\", \"AvatarSize\", \"Website\", \"Location\", \"Gender\", \"PostCount\", \"Karma\", \"KarmaUpdate\", \"RepliesPerPage\", \"TopicsPerPage\", \"MessagesPerPage\", \"TimeZone\", \"DateFormat\", \"TimeFormat\", \"UseTheme\", \"IgnoreSignitures\", \"IgnoreAdvatars\", \"IgnoreUsers\", \"IP\", \"Salt\") VALUES\n".
        "(-1, 'Guest', 'guest', '%s', 'GuestPassword', '%s', 4, -1, -1, 'no', 'yes', 0, 'Guest Account', 'Guest', %i, %i, %i, '0', '0', '0', '0', '0', '', 'Your Notes', '', 'http://', '100x100', '%s', '', 'Unknown', 0, 0, 0, 10, 10, 10, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'),\n".
        "(1, '%s', '%s', '%s', '%s', '%s', 1, 0, 0, 'yes', 'no', 0, '%s', 'Admin', %i, %i, %i, '0', '0', '0', '0', '0', '%s', 'Your Notes', '', '%s', '100x100', '%s', '', 'Unknown', 0, 0, 0, 10, 10, 10, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');", array($GuestPassword,$GEmail,$YourDate,$YourDate,$YourDate,$YourWebsite,$_POST['YourOffSet'],$_POST['iDBDateFormat'],$_POST['iDBTimeFormat'],$_POST['DefaultTheme'],'','','',$GuestLocalIP,$GSalt,$_POST['AdminUser'],$_POST['AdminHandle'],$NewPassword,$iDBHashType,$_POST['AdminEmail'],"",$YourDate,$YourDate,$YourDate,"","http://",$YourWebsite,$_POST['YourOffSet'],$_POST['iDBDateFormat'],$_POST['iDBTimeFormat'],$_POST['DefaultTheme'],'','','',$UserIP,$YourSalt));
        sql_query($query, $SQLStat);
        $query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."mempermissions\" (\"id\", \"PermissionID\", \"CanViewBoard\", \"CanViewOffLine\", \"CanEditProfile\", \"CanAddEvents\", \"CanPM\", \"CanSearch\", \"CanExecPHP\", \"CanDoHTML\", \"CanUseBBTags\", \"CanModForum\", \"CanViewIPAddress\", \"CanViewUserAgent\", \"CanViewAnonymous\", \"FloodControl\", \"SearchFlood\", \"HasModCP\", \"HasAdminCP\", \"ViewDBInfo\") VALUES\n".
        "(-1, 0, 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', -1, -1, 'group', 'group', 'group'),\n".
        "(1, 0, 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', 'group', -1, -1, 'group', 'group', 'group');", null);
        //"(-1, 0, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 30, 30, 'no', 'no', 'no'),\n".
        //"(1, 0, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'yes', 'yes', 30, 30, 'yes', 'yes', 'yes');", null);
        sql_query($query, $SQLStat);
        if ($_POST['testdata'] == "yes") {
            $query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."messenger\" (\"DiscussionID\", \"SenderID\", \"ReciverID\", \"GuestName\", \"MessageTitle\", \"MessageText\", \"Description\", \"DateSend\", \"Read\", \"IP\") VALUES\n".
            "(0, -1, 1, '".$iDB_Author."', 'Welcome', 'Welcome to your new Internet Discussion Board! :)', '%s', %i, 0, '%s');", array("Welcome ".$_POST['AdminUser'],$YourDate,$GuestLocalIP));
            sql_query($query, $SQLStat);
        }
        $query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."permissions\" (\"PermissionID\", \"Name\", \"ForumID\", \"CanViewForum\", \"CanMakePolls\", \"CanMakeTopics\", \"CanMakeReplys\", \"CanMakeReplysCT\", \"HideEditPostInfo\", \"CanEditTopics\", \"CanEditTopicsCT\", \"CanEditReplys\", \"CanEditReplysCT\", \"CanDeleteTopics\", \"CanDeleteTopicsCT\", \"CanDoublePost\", \"CanDoublePostCT\", \"GotoEditPost\", \"CanDeleteReplys\", \"CanDeleteReplysCT\", \"CanCloseTopics\", \"CanCloseTopicsCT\", \"CanPinTopics\", \"CanPinTopicsCT\", \"CanExecPHP\", \"CanDoHTML\", \"CanUseBBTags\", \"CanModForum\", \"CanReportPost\") VALUES\n".
        "(1, 'Admin', 0, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'),\n".
        "(2, 'Moderator', 0, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'yes', 'yes', 'yes'),\n".
        "(3, 'Member', 0, 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'no', 'yes', 'no', 'yes', 'no', 'yes', 'yes', 'no', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'no', 'yes'),\n".
        "(4, 'Guest', 0, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no'),\n".
        "(5, 'Banned', 0, 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no'),\n".
        "(6, 'Validate', 0, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no');", null);
        sql_query($query, $SQLStat);
        if ($_POST['startblank'] == "no") {
            $query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."permissions\" (\"PermissionID\", \"Name\", \"ForumID\", \"CanViewForum\", \"CanMakePolls\", \"CanMakeTopics\", \"CanMakeReplys\", \"CanMakeReplysCT\", \"HideEditPostInfo\", \"CanEditTopics\", \"CanEditTopicsCT\", \"CanEditReplys\", \"CanEditReplysCT\", \"CanDeleteTopics\", \"CanDeleteTopicsCT\", \"CanDoublePost\", \"CanDoublePostCT\", \"GotoEditPost\", \"CanDeleteReplys\", \"CanDeleteReplysCT\", \"CanCloseTopics\", \"CanCloseTopicsCT\", \"CanPinTopics\", \"CanPinTopicsCT\", \"CanExecPHP\", \"CanDoHTML\", \"CanUseBBTags\", \"CanModForum\", \"CanReportPost\") VALUES\n".
            "(1, 'Admin', 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'),\n".
            "(2, 'Moderator', 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'yes', 'yes', 'yes'),\n".
            "(3, 'Member', 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'no', 'yes', 'no', 'yes', 'no', 'yes', 'yes', 'no', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'no', 'yes'),\n".
            "(4, 'Guest', 1, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no'),\n".
            "(5, 'Banned', 1, 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no'),\n".
            "(6, 'Validate', 1, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no');", null);
            sql_query($query, $SQLStat);
        }
        if ($_POST['testdata'] == "yes") {
            $query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."posts\" (\"TopicID\", \"ForumID\", \"CategoryID\", \"UserID\", \"GuestName\", \"TimeStamp\", \"LastUpdate\", \"EditUser\", \"EditUserName\", \"Post\", \"Description\", \"IP\", \"EditIP\") VALUES\n".
            "(1, 1, 1, -1, '".$iDB_Author."', %i, %i, 1, '".$_POST['AdminUser']."', 'Welcome to your new Internet Discussion Board! :) ', '%s', '%s', '127.0.0.1');", array($YourDate,$YourEditDate,"Welcome ".$_POST['AdminUser'],$GuestLocalIP));
            sql_query($query, $SQLStat);
        }
        $query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."smileys\" (\"FileName\", \"SmileName\", \"SmileText\", \"EmojiText\", \"Directory\", \"Display\", \"ReplaceCI\") VALUES\n".
        "('angry.png', 'Angry', ':angry:', '😠', 'smileys/', 'yes', 'yes'),\n".
        "('closedeyes.png', 'Relieved', 'v_v', '😌', 'smileys/', 'yes', 'no'),\n".
        "('cool.png', 'Cool', 'B)', '😎', 'smileys/', 'yes', 'no'),\n".
        "('glare.png', 'Hmph', ':hmph:', '😑', 'smileys/', 'yes', 'yes'),\n".
        "('glare.png', 'Hmph', '&lt;_&lt;', '😑', 'smileys/', 'no', 'no'),\n".
        "('happy.png', 'Happy', '^_^', '😀', 'smileys/', 'yes', 'no'),\n".
        "('hmm.png', 'Hmm', ':unsure:', '🤔', 'smileys/', 'yes', 'yes'),\n".
        "('huh.png', 'Huh', ':huh:', '😕', 'smileys/', 'yes', 'yes'),\n".
        "('laugh.png', 'lol', ':laugh:', '😆', 'smileys/', 'yes', 'yes'),\n".
        "('lol.png', 'lol', ':lol:', '😂', 'smileys/', 'yes', 'yes'),\n".
        "('mad.png', 'Mad', ':mad:', '😡', 'smileys/', 'yes', 'yes'),\n".
        "('ninja.png', 'Ninja', ':ninja:', '🥷', 'smileys/', 'yes', 'yes'),\n".
        "('ohno.png', 'ohno', ':ohno:', '😨', 'smileys/', 'yes', 'yes'),\n".
        "('ohmy.png', 'ohmy', ':o', '😲', 'smileys/', 'yes', 'yes'),\n".
        "('sad.png', 'Sad', ':(', '😢', 'smileys/', 'yes', 'no'),\n".
        "('sleep.png', 'Sleep', '-_-', '😴', 'smileys/', 'yes', 'no'),\n".
        "('smile.png', 'Happy', ':)', '😊', 'smileys/', 'yes', 'no'),\n".
        "('sweat.png', 'Sweat', ':sweat:', '😅', 'smileys/', 'yes', 'yes'),\n".
        "('tongue.png', 'Tongue', ':P', '😛', 'smileys/', 'yes', 'no'),\n".
        "('wub.png', 'Wub', ':wub:', '😍', 'smileys/', 'yes', 'yes'),\n".
        "('x.png', 'X', ':x:', '😣', 'smileys/', 'yes', 'yes');", null);
        sql_query($query, $SQLStat);
        /*
        $query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."tagboard\" VALUES (1,-1,'".$iDB_Author."',".$YourDate.",'Welcome to Your New Tag Board. ^_^','127.0.0.1'), null);
        sql_query($query,$SQLStat);
        */
        if ($_POST['testdata'] == "yes") {
            $query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."topics\" (\"PollID\", \"ForumID\", \"CategoryID\", \"OldForumID\", \"OldCategoryID\", \"UserID\", \"GuestName\", \"TimeStamp\", \"LastUpdate\", \"TopicName\", \"Description\", \"NumReply\", \"NumViews\", \"Pinned\", \"Closed\") VALUES\n".
            "(0, 1, 1, 1, 1, -1, '".$iDB_Author."', %i, %i, 'Welcome', '%s', 0, 0, 1, 1);", array($YourDate,$YourDate,"Welcome ".$_POST['AdminUser']));
            sql_query($query, $SQLStat);
        }
        if ($Settings['sqltype'] == "mysqli" ||
            $Settings['sqltype'] == "mysqli_prepare" ||
            $Settings['sqltype'] == "pdo_mysql") {
            $OptimizeTea = sql_query(sql_pre_query("OPTIMIZE TABLE \"".$_POST['tableprefix']."themes\"", null), $SQLStat);
        }
        if ($Settings['sqltype'] == "pgsql" ||
            $Settings['sqltype'] == "pgsql_prepare" ||
            $Settings['sqltype'] == "pdo_pgsql") {
            $OptimizeTea = sql_query(sql_pre_query("VACUUM ANALYZE \"".$_POST['tableprefix']."themes\"", null), $SQLStat);
        }
        if ($Settings['sqltype'] == "sqlite3" ||
            $Settings['sqltype'] == "sqlite3_prepare" ||
            $Settings['sqltype'] == "pdo_sqlite3") {
            $OptimizeTea = sql_query(sql_pre_query("VACUUM", null), $SQLStat);
        }
        if ($Settings['sqltype'] == "cubrid" ||
            $Settings['sqltype'] == "cubrid_prepare" ||
            $Settings['sqltype'] == "pdo_cubrid") {
            $OptimizeTea = sql_query(sql_pre_query("UPDATE STATISTICS ON \"".$_POST['tableprefix']."themes\"", null), $SQLStat);
        }
        if ($Settings['sqltype'] == "sqlsrv_prepare" ||
            $Settings['sqltype'] == "pdo_sqlsrv") {
            $OptimizeTea = sql_query(sql_pre_query("ALTER INDEX ALL ON \"".$_POST['tableprefix']."\" REORGANIZE", null), $SQLStat);
        }
        if ($_POST['SQLThemes'] == "on") {
            $OldThemeSet = $ThemeSet;
            $Settings['board_name'] = $_POST['NewBoardName'];
            $skindir = dirname(realpath("system.php"))."/".$SettDir['themes'];
            if ($handle = opendir($skindir)) {
                $dirnum = null;
                while (false !== ($file = readdir($handle))) {
                    if ($dirnum == null) {
                        $dirnum = 0;
                    }
                    if (file_exists($skindir.$file."/info.php")) {
                        if ($file != "." && $file != "..") {
                            require($skindir.$file."/info.php");
                            $themelist[$dirnum] =  $file;
                            ++$dirnum;
                        }
                    }
                }
                closedir($handle);
                asort($themelist);
                $themenum = count($themelist);
                $themei = 0;
                while ($themei < $themenum) {
                    require($skindir.$themelist[$themei]."/settings.php");
                    $query = sql_pre_query("INSERT INTO \"".$_POST['tableprefix']."themes\" (\"Name\", \"ThemeName\", \"ThemeMaker\", \"ThemeVersion\", \"ThemeVersionType\", \"ThemeSubVersion\", \"MakerURL\", \"CopyRight\", \"WrapperString\", \"CSS\", \"CSSType\", \"FavIcon\", \"OpenGraph\", \"TableStyle\", \"MiniPageAltStyle\", \"PreLogo\", \"Logo\", \"LogoStyle\", \"SubLogo\", \"TopicIcon\", \"MovedTopicIcon\", \"HotTopic\", \"MovedHotTopic\", \"PinTopic\", \"AnnouncementTopic\", \"MovedPinTopic\", \"HotPinTopic\", \"MovedHotPinTopic\", \"ClosedTopic\", \"MovedClosedTopic\", \"HotClosedTopic\", \"MovedHotClosedTopic\", \"PinClosedTopic\", \"MovedPinClosedTopic\", \"HotPinClosedTopic\", \"MovedHotPinClosedTopic\", \"MessageRead\", \"MessageUnread\", \"Profile\", \"WWW\", \"PM\", \"TopicLayout\", \"AddReply\", \"FastReply\", \"NewTopic\", \"QuoteReply\", \"EditReply\", \"DeleteReply\", \"Report\", \"LineDivider\", \"ButtonDivider\", \"LineDividerTopic\", \"TitleDivider\", \"ForumStyle\", \"ForumIcon\", \"SubForumIcon\", \"RedirectIcon\", \"TitleIcon\", \"NavLinkIcon\", \"NavLinkDivider\", \"BoardStatsIcon\",  \"MemberStatsIcon\", \"BirthdayStatsIcon\", \"EventStatsIcon\", \"OnlineStatsIcon\", \"NoAvatar\", \"NoAvatarSize\") VALUES\n".
                    "('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %i, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');", array($themelist[$themei], $ThemeSet['ThemeName'], $ThemeSet['ThemeMaker'], $ThemeSet['ThemeVersion'], $ThemeSet['ThemeVersionType'], $ThemeSet['ThemeSubVersion'], $ThemeSet['MakerURL'], $ThemeSet['CopyRight'], $ThemeSet['WrapperString'], $ThemeSet['CSS'], $ThemeSet['CSSType'], $ThemeSet['FavIcon'], $ThemeSet['OpenGraph'], $ThemeSet['TableStyle'], $ThemeSet['MiniPageAltStyle'], $ThemeSet['PreLogo'], $ThemeSet['Logo'], $ThemeSet['LogoStyle'], $ThemeSet['SubLogo'], $ThemeSet['TopicIcon'], $ThemeSet['MovedTopicIcon'], $ThemeSet['HotTopic'], $ThemeSet['MovedHotTopic'], $ThemeSet['PinTopic'], $ThemeSet['AnnouncementTopic'], $ThemeSet['MovedPinTopic'], $ThemeSet['HotPinTopic'], $ThemeSet['MovedHotPinTopic'], $ThemeSet['ClosedTopic'], $ThemeSet['MovedClosedTopic'], $ThemeSet['HotClosedTopic'], $ThemeSet['MovedHotClosedTopic'], $ThemeSet['PinClosedTopic'], $ThemeSet['MovedPinClosedTopic'], $ThemeSet['HotPinClosedTopic'], $ThemeSet['MovedHotPinClosedTopic'], $ThemeSet['MessageRead'], $ThemeSet['MessageUnread'], $ThemeSet['Profile'], $ThemeSet['WWW'], $ThemeSet['PM'], $ThemeSet['TopicLayout'], $ThemeSet['AddReply'], $ThemeSet['FastReply'], $ThemeSet['NewTopic'], $ThemeSet['QuoteReply'], $ThemeSet['EditReply'], $ThemeSet['DeleteReply'], $ThemeSet['Report'], $ThemeSet['LineDivider'], $ThemeSet['ButtonDivider'], $ThemeSet['LineDividerTopic'], $ThemeSet['TitleDivider'], $ThemeSet['ForumStyle'], $ThemeSet['ForumIcon'], $ThemeSet['SubForumIcon'], $ThemeSet['RedirectIcon'], $ThemeSet['TitleIcon'], $ThemeSet['NavLinkIcon'], $ThemeSet['NavLinkDivider'], $ThemeSet['BoardStatsIcon'], $ThemeSet['MemberStatsIcon'], $ThemeSet['BirthdayStatsIcon'], $ThemeSet['EventStatsIcon'], $ThemeSet['OnlineStatsIcon'], $ThemeSet['NoAvatar'], $ThemeSet['NoAvatarSize']));
                    sql_query($query, $SQLStat);
                    ++$themei;
                }
            }
            $ThemeSet = $OldThemeSet;
        }
        if ($Settings['sqltype'] == "mysqli" ||
            $Settings['sqltype'] == "mysqli_prepare" ||
            $Settings['sqltype'] == "pdo_mysql") {
            $OptimizeTea = sql_query(sql_pre_query("OPTIMIZE TABLE \"".$_POST['tableprefix']."themes\"", null), $SQLStat);
        }
        if ($Settings['sqltype'] == "pgsql" ||
            $Settings['sqltype'] == "pgsql_prepare" ||
            $Settings['sqltype'] == "pdo_pgsql") {
            $OptimizeTea = sql_query(sql_pre_query("VACUUM ANALYZE \"".$_POST['tableprefix']."themes\"", null), $SQLStat);
        }
        if ($Settings['sqltype'] == "sqlite3" ||
            $Settings['sqltype'] == "sqlite3_prepare" ||
            $Settings['sqltype'] == "pdo_sqlite3") {
            $OptimizeTea = sql_query(sql_pre_query("VACUUM", null), $SQLStat);
        }
        if ($Settings['sqltype'] == "cubrid" ||
            $Settings['sqltype'] == "cubrid_prepare" ||
            $Settings['sqltype'] == "pdo_cubrid") {
            $OptimizeTea = sql_query(sql_pre_query("UPDATE STATISTICS ON \"".$_POST['tableprefix']."themes\"", null), $SQLStat);
        }
        if ($Settings['sqltype'] == "sqlsrv_prepare" ||
            $Settings['sqltype'] == "pdo_sqlsrv") {
            $OptimizeTea = sql_query(sql_pre_query("ALTER INDEX ALL ON \"".$_POST['tableprefix']."\" REORGANIZE", null), $SQLStat);
        }
        sql_disconnect_db($SQLStat);
    }
    $CHMOD = $_SERVER['PHP_SELF'];
    $iDBRDate = $SVNDay[0]."/".$SVNDay[1]."/".$SVNDay[2];
    $iDBRSVN = $VER2[2]." ".$SubVerN;
    $LastUpdateS = "Last Update: ".$iDBRDate." ".$iDBRSVN;
    $pretext = "<?php\n/*\n    This program is free software; you can redistribute it and/or modify\n    it under the terms of the GNU General Public License as published by\n    the Free Software Foundation; either version 2 of the License, or\n    (at your option) any later version.\n\n    This program is distributed in the hope that it will be useful,\n    but WITHOUT ANY WARRANTY; without even the implied warranty of\n    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n    Revised BSD License for more details.\n\n    Copyright 2004-".$SVNDay[2]." iDB Support - https://idb.osdn.jp/support/category.php?act=view&id=1\n    Copyright 2004-".$SVNDay[2]." Game Maker 2k - https://idb.osdn.jp/support/category.php?act=view&id=2\n    iDB Installer made by Game Maker 2k - http://idb.berlios.net/\n\n    \$FileInfo: settings.php & settingsbak.php - ".$LastUpdateS." - Author: cooldude2k \$\n*/\n";
    $pretext2 = array("/*   Board Setting Section Begins   */\n\$Settings = array();","/*   Board Setting Section Ends  \n     Board Info Section Begins   */\n\$SettInfo = array();","/*   Board Setting Section Ends   \n     Board Dir Section Begins   */\n\$SettDir = array();","/*   Board Dir Section Ends   */");
    $settcheck = "\$File3Name = basename(\$_SERVER['SCRIPT_NAME']);\nif (\$File3Name==\"settings.php\"||\$File3Name==\"/settings.php\"||\n    \$File3Name==\"settingsbak.php\"||\$File3Name==\"/settingsbak.php\") {\n    header('Location: index.php');\n    exit(); }\n";
    $BoardSettings = $pretext2[0]."\n".
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
    $fp = fopen("settings.php", "w+");
    fwrite($fp, $BoardSettings);
    fclose($fp);
    //	cp("settings.php","settingsbak.php");
    $fp = fopen("settingsbak.php", "w+");
    fwrite($fp, $BoardSettingsBak);
    fclose($fp);
    if ($_POST['storecookie'] == "true") {
        if ($URLsTest['host'] != "localhost.url") {
            setcookie("MemberName", $_POST['AdminUser'], time() + (7 * 86400), $this_dir, $URLsTest['host']);
            setcookie("UserID", 1, time() + (7 * 86400), $this_dir, $URLsTest['host']);
            setcookie("SessPass", $NewPassword, time() + (7 * 86400), $this_dir, $URLsTest['host']);
        }
        if ($URLsTest['host'] == "localhost.url") {
            setcookie("MemberName", $_POST['AdminUser'], time() + (7 * 86400), $this_dir, false);
            setcookie("UserID", 1, time() + (7 * 86400), $this_dir, false);
            setcookie("SessPass", $NewPassword, time() + (7 * 86400), $this_dir, false);
        }
    }
    $chdel = true;
    if ($Error != "Yes") {
        if ($_POST['unlink'] == "true") {
            if ($ConvertInfo['ConvertFile'] != null) {
                if (!@unlink($ConvertInfo['ConvertFile'])) {
                    $chdel = false;
                }
            }
            if (!@unlink($SetupDir['convert'].'index.php')) {
                $chdel = false;
            }
            if (!@unlink($SetupDir['convert'].'info.php')) {
                $chdel = false;
            }
            if (!@rmdir($SetupDir['convert'])) {
                $chdel = false;
            }
            if (!@unlink($SetupDir['sql'].'cubrid.php')) {
                $chdel = false;
            }
            if (!@unlink($SetupDir['sql'].'index.php')) {
                $chdel = false;
            }
            if (!@unlink($SetupDir['sql'].'mysql.php')) {
                $chdel = false;
            }
            if (!@unlink($SetupDir['sql'].'pgsql.php')) {
                $chdel = false;
            }
            if (!@unlink($SetupDir['sql'].'sqlite.php')) {
                $chdel = false;
            }
            if (!@unlink($SetupDir['sql'].'sqlsrv.php')) {
                $chdel = false;
            }
            if (!@rmdir($SetupDir['sql'])) {
                $chdel = false;
            }
            if (!@unlink($SetupDir['setup'].'index.php')) {
                $chdel = false;
            }
            if (!@unlink($SetupDir['setup'].'license.php')) {
                $chdel = false;
            }
            if (!@unlink($SetupDir['setup'].'mkconfig.php')) {
                $chdel = false;
            }
            if (!@unlink($SetupDir['setup'].'preinstall.php')) {
                $chdel = false;
            }
            if (!@unlink($SetupDir['setup'].'presetup.php')) {
                $chdel = false;
            }
            if (!@unlink($SetupDir['setup'].'setup.php')) {
                $chdel = false;
            }
            if (!@unlink($SetupDir['setup'].'html5.php')) {
                $chdel = false;
            }
            if (!@rmdir('setup')) {
                $chdel = false;
            }
            if (!@unlink('install.php')) {
                $chdel = false;
            }
        }
    }
    ?><span class="TableMessage">
<br />Install Finish <a href="index.php?act=view">Click here</a> to goto board. ^_^</span>
<?php if ($chdel === false) { ?><span class="TableMessage">
<br />Error: Cound not delete installer. Read readme.txt for more info.</span>
<?php } ?><br /><br />
</td>
</tr>
<?php } ?>
