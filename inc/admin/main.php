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

    $FileInfo: main.php - Last Update: 8/26/2024 SVN 1048 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "main.php" || $File3Name == "/main.php") {
    require('index.php');
    exit();
}

// Check if we can goto admin cp
if ($_SESSION['UserGroup'] == $Settings['GuestGroup'] || $GroupInfo['HasAdminCP'] == "no") {
    redirect("location", $rbasedir.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
    ob_clean();
    header("Content-Type: text/plain; charset=".$Settings['charset']);
    $urlstatus = 302;
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    session_write_close();
    die();
}
if (!isset($_POST['update'])) {
    $_POST['update'] = null;
}
if ($_GET['act'] == "sql" && $GroupInfo['ViewDBInfo'] != "yes") {
    redirect("location", $rbasedir.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
    ob_clean();
    header("Content-Type: text/plain; charset=".$Settings['charset']);
    $urlstatus = 302;
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    session_write_close();
    die();
}
if (!isset($_POST['update'])) {
    $_POST['update'] = null;
}
if ($_GET['act'] == "resyncthemes" && $Settings['SQLThemes'] != "on") {
    $_GET['act'] = "enablesthemes";
}
if ($_GET['act'] == "enablesthemes" && $Settings['SQLThemes'] != "off") {
    $_GET['act'] = "resyncthemes";
}
$iDBRDate = $SVNDay[0]."/".$SVNDay[1]."/".$SVNDay[2];
$iDBRSVN = $VER2[2]." ".$SubVerN;
$OutPutLog = null;
$LastUpdateS = "Last Update: ".$iDBRDate." ".$iDBRSVN;
$pretext = "<?php\n/*\n    This program is free software; you can redistribute it and/or modify\n    it under the terms of the GNU General Public License as published by\n    the Free Software Foundation; either version 2 of the License, or\n    (at your option) any later version.\n\n    This program is distributed in the hope that it will be useful,\n    but WITHOUT ANY WARRANTY; without even the implied warranty of\n    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n    Revised BSD License for more details.\n\n    Copyright 2004-".$SVNDay[2]." iDB Support - https://idb.osdn.jp/support/category.php?act=view&id=1\n    Copyright 2004-".$SVNDay[2]." Game Maker 2k - https://idb.osdn.jp/support/category.php?act=view&id=2\n    iDB Installer made by Game Maker 2k - http://idb.berlios.net/\n\n    \$FileInfo: settings.php & settingsbak.php - ".$LastUpdateS." - Author: cooldude2k \$\n*/\n";
$pretext2 = array("/*   Board Setting Section Begins   */\n\$Settings = array();","/*   Board Setting Section Ends  \n     Board Info Section Begins   */\n\$SettInfo = array();","/*   Board Setting Section Ends   \n     Board Dir Section Begins   */\n\$SettDir = array();","/*   Board Dir Section Ends   */");
$settcheck = "\$File3Name = basename(\$_SERVER['SCRIPT_NAME']);\nif (\$File3Name==\"settings.php\"||\$File3Name==\"/settings.php\"||\n    \$File3Name==\"settingsbak.php\"||\$File3Name==\"/settingsbak.php\") {\n    header('Location: index.php');\n    exit(); }\n";
if (!isset($_POST['update'])) {
    $_POST['update'] = null;
}
function bool_string($boolean)
{
    if (!is_bool($boolean)) {
        return $boolean;
    }
    if (is_bool($boolean)) {
        if ($boolean == 0 || $boolean === false) {
            return "false";
        }
        if ($boolean == 1 || $boolean === true) {
            return "true";
        }
    }
}
function null_string($string)
{
    $strtype = strtolower(gettype($string));
    if ($strtype == "string") {
        return "'".$string."'";
    }
    if ($strtype == "null") {
        return "null";
    }
    if ($strtype == "integer") {
        return $string;
    }
    return "null";
}
function rsq($string)
{
    if ($string != null) {
        $string = preg_replace("/^(\')|$(\')/i", "\'", $string);
    }
    return $string;
}
$KarmaExp = explode("&", $Settings['KarmaBoostDays']);
$KarmaNum = count($KarmaExp);
$Karmai = 0;
$KarmaNex = 0;
$KarmaTemp = null;
while ($Karmai < $KarmaNum) {
    if (is_numeric($KarmaExp[$Karmai])) {
        $KarmaTemp[$KarmaNex] = $KarmaExp[$Karmai];
        ++$KarmaNex;
    }
    ++$Karmai;
}
$KarmaExp = $KarmaTemp;
$Settings['KarmaBoostDays'] = implode("&", $KarmaExp);
$KBoostPercent = explode("|", $Settings['KBoostPercent']);
if (count($KBoostPercent) < 1) {
    $Settings['KBoostPercent'] = "6|10";
}
if (!is_numeric($KBoostPercent[0])) {
    $Settings['KBoostPercent'] = "6|10";
}
if (count($KBoostPercent) == 1) {
    $Settings['KBoostPercent'] = "6|10";
}
if (!is_numeric($KBoostPercent[1])) {
    $Settings['KBoostPercent'] = "6|10";
}
if (count($KBoostPercent) > 2) {
    $Settings['KBoostPercent'] = "6|10";
}
if ($Settings['html_type'] == "html5") {
    $Settings['output_type'] = "html";
}
if (!isset($Settings['sqltype'])) {
    $Settings['sqltype'] = "mysqli";
}
$Settings['sqltype'] = strtolower($Settings['sqltype']);
if ($Settings['sqltype'] != "mysqli" &&
    $Settings['sqltype'] != "mysqli_prepare" &&
    $Settings['sqltype'] != "pdo_mysql" &&
    $Settings['sqltype'] != "pgsql" &&
    $Settings['sqltype'] != "pgsql_prepare" &&
    $Settings['sqltype'] != "pdo_pgsql" &&
    $Settings['sqltype'] != "sqlite3" &&
    $Settings['sqltype'] != "sqlite3_prepare" &&
    $Settings['sqltype'] != "pdo_sqlite3" &&
    $Settings['sqltype'] != "cubrid" &&
    $Settings['sqltype'] != "cubrid_prepare" &&
    $Settings['sqltype'] != "pdo_cubrid" &&
    $Settings['sqltype'] != "sqlsrv_prepare" &&
    $Settings['sqltype'] != "pdo_sqlsrv") {
    $Settings['sqltype'] = "mysqli";
}
$DBType['Server'] = "";
$DBType['Client'] = "";
$DBType['PHP'] = "";
$SQLString = "";
if ($Settings['sqltype'] == "mysqli_prepare" ||
    $Settings['sqltype'] == "sqlsrv_prepare" ||
    $Settings['sqltype'] == "pgsql_prepare" ||
    $Settings['sqltype'] == "sqlite3_prepare" ||
    $Settings['sqltype'] == "cubrid_prepare") {
    $SQLString = "Prepared ";
}
if ($Settings['sqltype'] == "mysqli_prepare" ||
    $Settings['sqltype'] == "sqlsrv_prepare" ||
    $Settings['sqltype'] == "pgsql_prepare" ||
    $Settings['sqltype'] == "sqlite3_prepare" ||
    $Settings['sqltype'] == "cubrid_prepare") {
    $SQLString = "PDO ";
}
if ($Settings['sqltype'] == "mysqli" ||
    $Settings['sqltype'] == "mysqli_prepare" ||
    $Settings['sqltype'] == "pdo_mysql") {
    $DBType['Server'] = "MySQL ".$SQLString.sql_server_info($SQLStat);
    $DBType['Client'] = "MySQL ".$SQLString.sql_client_info($SQLStat);
}
if ($Settings['sqltype'] == "sqlsrv_prepare" ||
    $Settings['sqltype'] == "pdo_sqlsrv") {
    $DBType['Server'] = "SQL Server ".$SQLString.sql_server_info($SQLStat);
    $DBType['Client'] = "SQL Server ".$SQLString.sql_client_info($SQLStat);
}
if ($Settings['sqltype'] == "pgsql" ||
    $Settings['sqltype'] == "pgsql_prepare" ||
    $Settings['sqltype'] == "pdo_pgsql") {
    $DBType['Server'] = "Postgres ".$SQLString.sql_server_info($SQLStat);
    $DBType['Client'] = "Postgres ".$SQLString.sql_client_info($SQLStat);
}
if ($Settings['sqltype'] == "sqlite3_prepare" ||
    $Settings['sqltype'] == "sqlite3" ||
    $Settings['sqltype'] == "pdo_sqlite3") {
    $DBType['Server'] = "SQLite ".$SQLString.sql_server_info($SQLStat);
    $DBType['Client'] = "SQLite ".$SQLString.sql_client_info($SQLStat);
}
if ($Settings['sqltype'] == "cubrid" ||
    $Settings['sqltype'] == "cubrid_prepare" ||
    $Settings['sqltype'] == "pdo_cubrid") {
    $DBType['Server'] = "CUBRID ".$SQLString.sql_server_info($SQLStat);
    $DBType['Client'] = "CUBRID ".$SQLString.sql_client_info($SQLStat);
    $DBType['PHP'] = "CUBRID ".$SQLString.cubrid_version();
}
if (!isset($Settings['vercheck'])) {
    $Settings['vercheck'] = 2;
}
if ($Settings['vercheck'] != 1 &&
    $Settings['vercheck'] != 2) {
    $Settings['vercheck'] = 2;
}
if (!isset($Settings['start_date'])) {
    $Settings['start_date'] = $utccurtime->getTimestamp();
}
if (!isset($Settings['SQLThemes'])) {
    $Settings['SQLThemes'] = 'off';
}
if ($Settings['SQLThemes'] != "on" &&
    $Settings['SQLThemes'] != "off") {
    $Settings['SQLThemes'] = 'off';
}
if (!isset($Settings['board_name']) && isset($SettInfo['board_name'])) {
    $Settings['board_name'] = $SettInfo['board_name'];
}
if (!isset($SettInfo['board_name']) && isset($Settings['board_name'])) {
    $SettInfo['board_name'] = $Settings['board_name'];
}
if ($Settings['board_name'] != $SettInfo['board_name']) {
    $SettInfo['board_name'] = $Settings['board_name'];
}
if (!isset($Settings['VerCheckURL'])) {
    $Settings['VerCheckURL'] = "";
}
if (!isset($Settings['IPCheckURL'])) {
    $Settings['IPCheckURL'] = "";
}
if (!isset($Settings['log_config_format'])) {
    $Settings['log_config_format'] = "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"";
}
if (!isset($Settings['idb_time_format'])) {
    $Settings['idb_time_format'] = "g:i A";
}
if (!isset($Settings['idb_date_format'])) {
    $Settings['idb_date_format'] = "F j Y";
}
?>
<table class="Table3">
<tr style="width: 100%; vertical-align: top;">
	<td style="width: 15%; vertical-align: top;">
<?php
require($SettDir['admin'].'table.php');
if ($_GET['act'] == "delsessions" && $GroupInfo['ViewDBInfo'] == "yes") {
    $time = $utccurtime->getTimestamp() - ini_get("session.gc_maxlifetime");
    //$sqlg = sql_pre_query('DELETE FROM \"'.$Settings['sqltable'].'sessions\" WHERE \"expires\" < UNIX_TIMESTAMP();', null);
    $sqlgc = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" < %i", array($time));
    sql_query($sqlgc, $SQLStat);
    $_POST['update'] = "now";
    $_GET['act'] = "optimize";
}
if ($_GET['act'] == "enablesthemes" && $GroupInfo['ViewDBInfo'] == "yes" && $Settings['SQLThemes'] == "off") {
    $Settings['board_name'] = htmlspecialchars($Settings['board_name'], ENT_QUOTES, $Settings['charset']);
    $Settings['board_name'] = fixbamps($Settings['board_name']);
    $Settings['board_name'] = remove_spaces($Settings['board_name']);
    $Settings['board_name'] = str_replace("\&#039;", "&#039;", $Settings['board_name']);
    $SettInfo['board_name'] = htmlspecialchars($SettInfo['board_name'], ENT_QUOTES, $Settings['charset']);
    $SettInfo['board_name'] = fixbamps($SettInfo['board_name']);
    $SettInfo['board_name'] = remove_spaces($SettInfo['board_name']);
    $SettInfo['board_name'] = str_replace("\&#039;", "&#039;", $SettInfo['board_name']);
    $SettInfo['Author'] = htmlspecialchars($SettInfo['Author'], ENT_QUOTES, $Settings['charset']);
    $SettInfo['Author'] = fixbamps($SettInfo['Author']);
    $SettInfo['Author'] = remove_spaces($SettInfo['Author']);
    $SettInfo['Author'] = str_replace("\&#039;", "&#039;", $SettInfo['Author']);
    $SettInfo['Keywords'] = htmlspecialchars($SettInfo['Keywords'], ENT_QUOTES, $Settings['charset']);
    $SettInfo['Keywords'] = fixbamps($SettInfo['Keywords']);
    $SettInfo['Keywords'] = remove_spaces($SettInfo['Keywords']);
    $SettInfo['Keywords'] = str_replace("\&#039;", "&#039;", $SettInfo['Keywords']);
    $SettInfo['Description'] = htmlspecialchars($SettInfo['Description'], ENT_QUOTES, $Settings['charset']);
    $SettInfo['Description'] = fixbamps($SettInfo['Description']);
    $SettInfo['Description'] = remove_spaces($SettInfo['Description']);
    $SettInfo['Description'] = str_replace("\&#039;", "&#039;", $SettInfo['Description']);
    $BoardSettings = $pretext2[0]."\n".
    "\$Settings['sqlhost'] = ".null_string($Settings['sqlhost']).";\n".
    "\$Settings['sqldb'] = ".null_string($Settings['sqldb']).";\n".
    "\$Settings['sqltable'] = ".null_string($Settings['sqltable']).";\n".
    "\$Settings['sqluser'] = ".null_string($Settings['sqluser']).";\n".
    "\$Settings['sqlpass'] = ".null_string($Settings['sqlpass']).";\n".
    "\$Settings['sqltype'] = ".null_string($Settings['sqltype']).";\n".
    "\$Settings['board_name'] = ".null_string($Settings['board_name']).";\n".
    "\$Settings['idbdir'] = ".null_string($Settings['idbdir']).";\n".
    "\$Settings['idburl'] = ".null_string($Settings['idburl']).";\n".
    "\$Settings['enable_https'] = ".null_string($Settings['enable_https']).";\n".
    "\$Settings['weburl'] = ".null_string($Settings['weburl']).";\n".
    "\$Settings['SQLThemes'] = 'on';\n".
    "\$Settings['use_gzip'] = ".null_string($Settings['use_gzip']).";\n".
    "\$Settings['html_type'] = ".null_string($Settings['html_type']).";\n".
    "\$Settings['html_level'] = ".null_string($Settings['html_level']).";\n".
    "\$Settings['output_type'] = ".null_string($Settings['output_type']).";\n".
    "\$Settings['GuestGroup'] = ".null_string($Settings['GuestGroup']).";\n".
    "\$Settings['MemberGroup'] = ".null_string($Settings['MemberGroup']).";\n".
    "\$Settings['ValidateGroup'] = ".null_string($Settings['ValidateGroup']).";\n".
    "\$Settings['AdminValidate'] = ".null_string($Settings['AdminValidate']).";\n".
    "\$Settings['TestReferer'] = ".null_string($Settings['TestReferer']).";\n".
    "\$Settings['DefaultTheme'] = ".null_string($Settings['DefaultTheme']).";\n".
    "\$Settings['DefaultTimeZone'] = ".null_string($Settings['DefaultTimeZone']).";\n".
    "\$Settings['start_date'] = ".null_string($Settings['start_date']).";\n".
    "\$Settings['idb_time_format'] = ".null_string($Settings['idb_time_format']).";\n".
    "\$Settings['idb_date_format'] = ".null_string($Settings['idb_date_format']).";\n".
    "\$Settings['use_hashtype'] = ".null_string($Settings['use_hashtype']).";\n".
    "\$Settings['charset'] = ".null_string($Settings['charset']).";\n".
    "\$Settings['sql_collate'] = ".null_string($Settings['sql_collate']).";\n".
    "\$Settings['sql_charset'] = ".null_string($Settings['sql_charset']).";\n".
    "\$Settings['add_power_by'] = ".null_string($Settings['add_power_by']).";\n".
    "\$Settings['send_pagesize'] = ".null_string($Settings['send_pagesize']).";\n".
    "\$Settings['max_posts'] = ".null_string($Settings['max_posts']).";\n".
    "\$Settings['max_topics'] = ".null_string($Settings['max_topics']).";\n".
    "\$Settings['max_memlist'] = ".null_string($Settings['max_memlist']).";\n".
    "\$Settings['max_pmlist'] = ".null_string($Settings['max_pmlist']).";\n".
    "\$Settings['hot_topic_num'] = ".null_string($Settings['hot_topic_num']).";\n".
    "\$Settings['qstr'] = ".null_string($Settings['qstr']).";\n".
    "\$Settings['qsep'] = ".null_string($Settings['qsep']).";\n".
    "\$Settings['file_ext'] = ".null_string($Settings['file_ext']).";\n".
    "\$Settings['rss_ext'] = ".null_string($Settings['rss_ext']).";\n".
    "\$Settings['js_ext'] = ".null_string($Settings['js_ext']).";\n".
    "\$Settings['showverinfo'] = ".null_string($Settings['showverinfo']).";\n".
    "\$Settings['vercheck'] = ".null_string($Settings['vercheck']).";\n".
    "\$Settings['enable_rss'] = ".null_string($Settings['enable_rss']).";\n".
    "\$Settings['enable_search'] = ".null_string($Settings['enable_search']).";\n".
    "\$Settings['sessionid_in_urls'] = ".null_string($Settings['sessionid_in_urls']).";\n".
    "\$Settings['fixpathinfo'] = ".null_string($OldSettings['fixpathinfo']).";\n".
    "\$Settings['fixbasedir'] = ".null_string($OldSettings['fixbasedir']).";\n".
    "\$Settings['fixcookiedir'] = ".null_string($OldSettings['fixcookiedir']).";\n".
    "\$Settings['fixredirectdir'] = ".null_string($OldSettings['fixredirectdir']).";\n".
    "\$Settings['enable_pathinfo'] = ".null_string($Settings['enable_pathinfo']).";\n".
    "\$Settings['rssurl'] = ".null_string($Settings['rssurl']).";\n".
    "\$Settings['board_offline'] = ".null_string($Settings['board_offline']).";\n".
    "\$Settings['VerCheckURL'] = ".null_string($Settings['VerCheckURL']).";\n".
    "\$Settings['IPCheckURL'] = ".null_string($Settings['IPCheckURL']).";\n".
    "\$Settings['log_http_request'] = ".null_string($Settings['log_http_request']).";\n".
    "\$Settings['log_config_format'] = ".null_string($Settings['log_config_format']).";\n".
    "\$Settings['BoardUUID'] = ".null_string(base64_encode($Settings['BoardUUID'])).";\n".
    "\$Settings['KarmaBoostDays'] = ".null_string($Settings['KarmaBoostDays']).";\n".
    "\$Settings['KBoostPercent'] = ".null_string($Settings['KBoostPercent']).";\n".$pretext2[1]."\n".
    "\$SettInfo['board_name'] = ".null_string($SettInfo['board_name']).";\n".
    "\$SettInfo['Author'] = ".null_string($SettInfo['Author']).";\n".
    "\$SettInfo['Keywords'] = ".null_string($SettInfo['Keywords']).";\n".
    "\$SettInfo['Description'] = ".null_string($SettInfo['Description']).";\n".$pretext2[2]."\n".
    "\$SettDir['maindir'] = ".null_string($SettDir['maindir']).";\n".
    "\$SettDir['inc'] = ".null_string($SettDir['inc']).";\n".
    "\$SettDir['logs'] = ".null_string($SettDir['logs']).";\n".
    "\$SettDir['archive'] = ".null_string($SettDir['archive']).";\n".
    "\$SettDir['misc'] = ".null_string($SettDir['misc']).";\n".
    "\$SettDir['sql'] = ".null_string($SettDir['sql']).";\n".
    "\$SettDir['admin'] = ".null_string($SettDir['admin']).";\n".
    "\$SettDir['sqldumper'] = ".null_string($SettDir['sqldumper']).";\n".
    "\$SettDir['mod'] = ".null_string($SettDir['mod']).";\n".
    "\$SettDir['mplayer'] = ".null_string($SettDir['mplayer']).";\n".
    "\$SettDir['themes'] = ".null_string($SettDir['themes']).";\n".$pretext2[3]."\n?>";
    $BoardSettingsBak = $pretext.$settcheck.$BoardSettings;
    $BoardSettings = $pretext.$settcheck.$BoardSettings;
    $fp = fopen("settings.php", "w+");
    fwrite($fp, $BoardSettings);
    fclose($fp);
    //	cp("settings.php","settingsbak.php");
    $fp = fopen("settingsbak.php", "w+");
    fwrite($fp, $BoardSettingsBak);
    fclose($fp);
    $Settings['SQLThemes'] = "on";
    $_POST['update'] = "now";
    $_GET['act'] = "resyncthemes";
}
if (($_GET['act'] == "themelist" && $GroupInfo['ViewDBInfo'] == "yes") ||
    ($_GET['act'] == "gettheme" && $_POST['act'] == "gettheme" && $GroupInfo['ViewDBInfo'] == "yes")) {
    if ($_GET['act'] == "gettheme" && $_POST['act'] == "gettheme" && $_POST['GetTheme'] == null) {
        $_GET['act'] = "themelist";
        $_POST['act'] = "";
    }
    if ($_GET['act'] == "gettheme" && $_POST['act'] == "gettheme" && $_POST['GetTheme'] == "None") {
        $_GET['act'] = "themelist";
        $_POST['act'] = "";
    }
    $conn_id = ftp_connect("ftp.berlios.de", 21, 90);
    ftp_login($conn_id, "anonymous", "anonymous");
    ftp_pasv($conn_id, true);
    if ($_GET['act'] == "themelist") {
        ftp_chdir($conn_id, "/pub/idb/themes/");
    }
    if ($_GET['act'] == "gettheme" && $_POST['act'] == "gettheme") {
        ftp_chdir($conn_id, "/pub/idb/themes/".$_POST['GetTheme']."/");
        ftp_get($conn_id, $SettDir['archive'].$_POST['GetTheme'].".tar", "./".$_POST['GetTheme'].".tar", FTP_BINARY);
        untar($SettDir['archive'].$_POST['GetTheme'].".tar", $SettDir['themes'].$_POST['GetTheme']."/");
        unlink($SettDir['archive'].$_POST['GetTheme'].".tar");
        if ($Settings['SQLThemes'] == "off") {
            $_POST['update'] = "now";
        }
        if ($Settings['SQLThemes'] == "on") {
            $_POST['update'] = "now";
            $_GET['act'] = "resyncthemes";
        }
    }
    if ($_GET['act'] == "themelist") {
        $themelist = ftp_nlist($conn_id, ".");
        $it = 0;
        $numt = count($themelist);
        $themeact = url_maker($exfile['admin'], $Settings['file_ext'], "act=gettheme", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']);
        $admincptitle = " ".$ThemeSet['TitleDivider']." Theme Setup";
        ?>
</td>
	<td style="width: 85%; vertical-align: top;">
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo $themeact; ?>">Theme Setup</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo $themeact; ?>">Theme Setup</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr id="ProfileTitle" class="TableMenuRow2">
<th class="TableMenuColumn2">Theme Setup</th>
</tr>
<tr class="TableMenuRow3" id="NotePadRow">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" action="<?php echo $themeact; ?>"><div style="text-align: center;">
<label class="TextBoxLabel" for="GetTheme">Install Theme:</label><br />
<select size="1" name="GetTheme" id="GetTheme" class="TextBox">
<option value="None">None</option>
<?php
while ($it < $numt) {
    if (!file_exists($SettDir['themes'].$themelist[$it])) {
        echo "<option value=\"".$themelist[$it]."\">".$themelist[$it]."</option>\n";
    }
    ++$it;
}
        ?>
</select><br />
<input type="hidden" name="act" value="gettheme" style="display: none;" />
<input type="submit" value="Install" />
</div></form></td>
</tr>
<tr id="ProfileEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&#160;</td>
</tr>
</table>
</div>
<?php } ftp_close($conn_id);
}
if ($_GET['act'] == "resyncthemes" && $GroupInfo['ViewDBInfo'] == "yes" && $Settings['SQLThemes'] == "on") {
    $time = $utccurtime->getTimestamp() - ini_get("session.gc_maxlifetime");
    //$sqlg = sql_pre_query('DELETE FROM \"'.$Settings['sqltable'].'sessions\" WHERE \"expires\" < UNIX_TIMESTAMP();', null);
    if ($Settings['sqltype'] == "mysqli" ||
        $Settings['sqltype'] == "mysqli_prepare" ||
        $Settings['sqltype'] == "pdo_mysql" ||
        $Settings['sqltype'] == "cubrid" ||
        $Settings['sqltype'] == "cubrid_prepare" ||
        $Settings['sqltype'] == "pdo_cubrid") {
        $sqlgc = sql_pre_query("TRUNCATE TABLE \"".$Settings['sqltable']."themes\"", null);
        sql_query($sqlgc, $SQLStat);
        $sqlgc = sql_pre_query("ALTER TABLE \"".$Settings['sqltable']."themes\" AUTO_INCREMENT=1", null);
        sql_query($sqlgc, $SQLStat);
    }
    if ($Settings['sqltype'] == "pgsql" ||
        $Settings['sqltype'] == "pgsql_prepare" ||
        $Settings['sqltype'] == "pdo_pgsql") {
        $sqlgc = sql_pre_query("TRUNCATE TABLE \"".$Settings['sqltable']."themes\"", null);
        sql_query($sqlgc, $SQLStat);
        $sqlgc = sql_pre_query("SELECT setval('".$Settings['sqltable']."themes_id_seq', 1, false);", null);
        sql_query($sqlgc, $SQLStat);
    }
    if ($Settings['sqltype'] == "sqlite3" ||
        $Settings['sqltype'] == "sqlite3_prepare" ||
        $Settings['sqltype'] == "pdo_sqlite3") {
        $sqlgc = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."themes\";", null);
        sql_query($sqlgc, $SQLStat);
    }
    if ($Settings['sqltype'] == "sqlsrv_prepare" ||
       $Settings['sqltype'] == "pdo_sqlsrv") {
        // Truncate the table, which automatically resets the identity column
        $sqlgc = sql_pre_query("TRUNCATE TABLE \"".$Settings['sqltable']."themes\"", null);
        sql_query($sqlgc, $SQLStat);

        // If you need to explicitly reseed the identity (to set it to 1 or another value), use DBCC CHECKIDENT
        $sqlgc = sql_pre_query("DBCC CHECKIDENT ('".$Settings['sqltable']."themes', RESEED, 1);", null);
        sql_query($sqlgc, $SQLStat);
    }
    $skindir = dirname(realpath("system.php"))."/".$SettDir['themes'];
    if ($handle = opendir($skindir)) {
        $dirnum = null;
        while (false !== ($file = readdir($handle))) {
            if ($dirnum == null) {
                $dirnum = 0;
            }
            if (is_dir($skindir.$file)) {
                if (file_exists($skindir.$file."/info.php")) {
                    if ($file != "." && $file != "..") {
                        require($skindir.$file."/info.php");
                        $themelist[$dirnum] =  $file;
                        ++$dirnum;
                    }
                }
            }
        }
        closedir($handle);
        asort($themelist);
        $themenum = count($themelist);
        $themei = 0;
        while ($themei < $themenum) {
            require($skindir.$themelist[$themei]."/settings.php");
            $query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."themes\" (\"Name\", \"ThemeName\", \"ThemeMaker\", \"ThemeVersion\", \"ThemeVersionType\", \"ThemeSubVersion\", \"MakerURL\", \"CopyRight\", \"WrapperString\", \"CSS\", \"CSSType\", \"FavIcon\", \"OpenGraph\", \"TableStyle\", \"MiniPageAltStyle\", \"PreLogo\", \"Logo\", \"LogoStyle\", \"SubLogo\", \"TopicIcon\", \"MovedTopicIcon\", \"HotTopic\", \"MovedHotTopic\", \"PinTopic\", \"AnnouncementTopic\", \"MovedPinTopic\", \"HotPinTopic\", \"MovedHotPinTopic\", \"ClosedTopic\", \"MovedClosedTopic\", \"HotClosedTopic\", \"MovedHotClosedTopic\", \"PinClosedTopic\", \"MovedPinClosedTopic\", \"HotPinClosedTopic\", \"MovedHotPinClosedTopic\", \"MessageRead\", \"MessageUnread\", \"Profile\", \"WWW\", \"PM\", \"TopicLayout\", \"AddReply\", \"FastReply\", \"NewTopic\", \"QuoteReply\", \"EditReply\", \"DeleteReply\", \"Report\", \"LineDivider\", \"ButtonDivider\", \"LineDividerTopic\", \"TitleDivider\", \"ForumStyle\", \"ForumIcon\", \"SubForumIcon\", \"RedirectIcon\", \"TitleIcon\", \"NavLinkIcon\", \"NavLinkDivider\", \"BoardStatsIcon\", \"MemberStatsIcon\", \"BirthdayStatsIcon\", \"EventStatsIcon\", \"NoAvatar\", \"NoAvatarSize\") VALUES\n".
            "('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');", array($themelist[$themei], $ThemeSet['ThemeName'], $ThemeSet['ThemeMaker'], $ThemeSet['ThemeVersion'], $ThemeSet['ThemeVersionType'], $ThemeSet['ThemeSubVersion'], $ThemeSet['MakerURL'], $ThemeSet['CopyRight'], $ThemeSet['WrapperString'], $ThemeSet['CSS'], $ThemeSet['CSSType'], $ThemeSet['FavIcon'], $ThemeSet['OpenGraph'], $ThemeSet['TableStyle'], $ThemeSet['MiniPageAltStyle'], $ThemeSet['PreLogo'], $ThemeSet['Logo'], $ThemeSet['LogoStyle'], $ThemeSet['SubLogo'], $ThemeSet['TopicIcon'], $ThemeSet['MovedTopicIcon'], $ThemeSet['HotTopic'], $ThemeSet['MovedHotTopic'], $ThemeSet['PinTopic'], $ThemeSet['AnnouncementTopic'], $ThemeSet['MovedPinTopic'], $ThemeSet['HotPinTopic'], $ThemeSet['MovedHotPinTopic'], $ThemeSet['ClosedTopic'], $ThemeSet['MovedClosedTopic'], $ThemeSet['HotClosedTopic'], $ThemeSet['MovedHotClosedTopic'], $ThemeSet['PinClosedTopic'], $ThemeSet['MovedPinClosedTopic'], $ThemeSet['HotPinClosedTopic'], $ThemeSet['MovedHotPinClosedTopic'], $ThemeSet['MessageRead'], $ThemeSet['MessageUnread'], $ThemeSet['Profile'], $ThemeSet['WWW'], $ThemeSet['PM'], $ThemeSet['TopicLayout'], $ThemeSet['AddReply'], $ThemeSet['FastReply'], $ThemeSet['NewTopic'], $ThemeSet['QuoteReply'], $ThemeSet['EditReply'], $ThemeSet['DeleteReply'], $ThemeSet['Report'], $ThemeSet['LineDivider'], $ThemeSet['ButtonDivider'], $ThemeSet['LineDividerTopic'], $ThemeSet['TitleDivider'], $ThemeSet['ForumStyle'], $ThemeSet['ForumIcon'], $ThemeSet['SubForumIcon'], $ThemeSet['RedirectIcon'], $ThemeSet['TitleIcon'], $ThemeSet['NavLinkIcon'], $ThemeSet['NavLinkDivider'], $ThemeSet['BoardStatsIcon'], $ThemeSet['MemberStatsIcon'], $ThemeSet['BirthdayStatsIcon'], $ThemeSet['EventStatsIcon'], $ThemeSet['NoAvatar'], $ThemeSet['NoAvatarSize']));
            sql_query($query, $SQLStat);
            ++$themei;
        }
    }
    $themenum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."themes\" WHERE \"Name\"='%s'", array($_GET['theme'])), $SQLStat);
    $themequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."themes\" WHERE \"Name\"='%s'", array($_GET['theme']));
    $themeresult = sql_query($themequery, $SQLStat);
    require($SettDir['inc'].'sqlthemes.php');
    $_POST['update'] = "now";
    $_GET['act'] = "optimize";
}
if ($_GET['act'] == "optimize" && $GroupInfo['ViewDBInfo'] == "yes") {
    $TablePreFix = $Settings['sqltable'];
    function add_prefix($tarray)
    {
        global $TablePreFix;
        return $TablePreFix.$tarray;
    }
    $TableChCk = array("categories", "catpermissions", "events", "forums", "groups", "levels", "members", "mempermissions", "messenger", "permissions", "polls", "posts", "ranks", "restrictedwords", "sessions", "smileys", "themes", "topics", "wordfilter");
    $TableChCk = array_map("add_prefix", $TableChCk);
    $tcount = count($TableChCk);
    $ti = 0;
    $TblOptimized = 0;
    while ($ti < $tcount) {
        if (isset($OptimizeAr['Msg_text'])) {
            unset($OptimizeAr['Msg_text']);
        }
        if (isset($OptimizeAr[3])) {
            unset($OptimizeAr[3]);
        }
        if ($Settings['sqltype'] == "mysqli" ||
            $Settings['sqltype'] == "mysqli_prepare" ||
            $Settings['sqltype'] == "pdo_mysql") {
            if (isset($_GET['subact']) && $_GET['subact'] == "repair") {
                $RepairTea = sql_query(sql_pre_query("REPAIR TABLE \"".$TableChCk[$ti]."\"", null), $SQLStat);
            }
            $OptimizeTea = sql_query(sql_pre_query("OPTIMIZE TABLE \"".$TableChCk[$ti]."\"", null), $SQLStat);
        }
        if ($Settings['sqltype'] == "cubrid" ||
            $Settings['sqltype'] == "cubrid_prepare" ||
            $Settings['sqltype'] == "pdo_cubrid") {
            $OptimizeTea = sql_query(sql_pre_query("UPDATE STATISTICS ON \"".$TableChCk[$ti]."\"", null), $SQLStat);
        }
        if ($Settings['sqltype'] == "sqlsrv_prepare" ||
            $Settings['sqltype'] == "pdo_sqlsrv") {
            $OptimizeTea = sql_query(sql_pre_query("ALTER INDEX ALL ON \"".$TableChCk[$ti]."\" REORGANIZE", null), $SQLStat);
        }
        if ($Settings['sqltype'] == "pgsql" ||
            $Settings['sqltype'] == "pgsql_prepare" ||
            $Settings['sqltype'] == "pdo_pgsql") {
            $OptimizeTea = sql_query(sql_pre_query("VACUUM ANALYZE \"".$TableChCk[$ti]."\"", null), $SQLStat);
        }
        if ($Settings['sqltype'] == "mysqli" ||
            $Settings['sqltype'] == "mysqli_prepare" ||
            $Settings['sqltype'] == "pdo_mysql" ||
            $Settings['sqltype'] == "cubrid" ||
            $Settings['sqltype'] == "cubrid_prepare" ||
            $Settings['sqltype'] == "pdo_cubrid") {
            $OptimizeAr = sql_fetch_array($OptimizeTea);
            if (!isset($OptimizeAr['Msg_text']) &&
                isset($OptimizeAr[3])) {
                $OptimizeAr['Msg_text'] = $OptimizeAr[3];
            }
            if ($OptimizeAr['Msg_text'] == "OK") {
                ++$TblOptimized;
            }
        } ++$ti;
    }
    if ($Settings['sqltype'] == "sqlite3" ||
        $Settings['sqltype'] == "sqlite3_prepare" ||
        $Settings['sqltype'] == "pdo_sqlite3") {
        sql_disconnect_db($SQLStat);
        $SQLStat = sql_connect_db($Settings['sqlhost'], $Settings['sqluser'], $Settings['sqlpass'], $Settings['sqldb']);
        $OptimizeTea = sql_query(sql_pre_query("VACUUM", null), $SQLStat);
    }
    if ($Settings['sqltype'] == "mysqli" ||
        $Settings['sqltype'] == "cubrid") {
        $OutPutLog = "MySQL Output: ".$TblOptimized." tables optimized.";
    }
    if ($Settings['sqltype'] == "pgsql") {
        $OutPutLog = "PGSQL Output: All tables optimized.";
    }
    if ($Settings['sqltype'] == "sqlite3" ||
        $Settings['sqltype'] == "sqlite3_prepare" ||
        $Settings['sqltype'] == "pdo_sqlite3") {
        $OutPutLog = "SQLite Output: All tables optimized.";
    }
    if ($Settings['sqltype'] == "cubrid") {
        $OutPutLog = "CUBRID Output: All tables optimized.";
    }
    $_POST['update'] = "now";
    $_GET['act'] = "view";
}
?>
</td>
	<td style="width: 85%; vertical-align: top;">
<?php if ($_POST['update'] == "now" && $_GET['act'] != null) {
    $updateact = url_maker($exfile['profile'], $Settings['file_ext'], "act=".$_GET['act']."&menu=main", $Settings['qstr'], $Settings['qsep'], $prexqstr['profile'], $exqstr['profile']);
    $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
    redirect("refresh", $rbasedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act'], $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false), "3");
    ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=main", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Updating Settings</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=main", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Updating Settings</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr id="ProfileTitle" class="TableMenuRow2">
<th class="TableMenuColumn2">Updating Settings</th>
</tr>
<tr class="TableMenuRow3" id="ProfileUpdate">
<td class="TableMenuColumn3">
<div style="text-align: center;">
<?php if (isset($OutPutLog)) {
    echo "<br />".$OutPutLog;
} ?>
<br />Settings have been updated <a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=main", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">click here</a> to go back. ^_^<br />&#160;</div>
<?php } if ($_GET['act'] == "view" && $_POST['update'] != "now") {
    $num = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($_SESSION['UserID'])), $SQLStat);
    $query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($_SESSION['UserID']));
    $result = sql_query($query, $SQLStat);
    $result_array = sql_fetch_assoc($result);
    $YourID = $result_array['id'];
    $Notes = $result_array['Notes'];
    $noteact = url_maker($exfile['profile'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['profile'], $exqstr['profile']);
    $notepadact = $noteact;
    $profiletitle = " ".$ThemeSet['TitleDivider']." NotePad";
    $admincptitle = " ".$ThemeSet['TitleDivider']." Admin CP";
    ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo $noteact; ?>">NotePad</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo $noteact; ?>">NotePad</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr id="ProfileTitle" class="TableMenuRow2">
<th class="TableMenuColumn2">NotePad</th>
</tr>
<tr class="TableMenuRow3" id="NotePadRow">
<td class="TableMenuColumn3">
<form method="post" action="<?php echo $notepadact; ?>"><div style="text-align: center;">
<label class="TextBoxLabel" for="NotePad">Your NotePad</label><br />
<textarea class="TextBox" name="NotePad" id="NotePad" style="width: 75%; height: 128px;" rows="10" cols="84"><?php echo $Notes; ?></textarea>
<input type="hidden" name="act" value="view" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<br /><input type="submit" class="Button" value="Save" />&#160;<input class="Button" type="reset" />
</div></form></td>
</tr>
<tr id="ProfileEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&#160;</td>
</tr>
</table>
</div>
<?php } if ($_GET['act'] == "settings" && $_POST['update'] != "now") {
    $admincptitle = " ".$ThemeSet['TitleDivider']." Settings Manager";
    $mnum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."groups\" WHERE (\"Name\"<>'%s') ORDER BY \"id\" ASC", array("Admin")), $SQLStat);
    $mguerys = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE (\"Name\"<>'%s') ORDER BY \"id\" ASC", array("Admin"));
    $mgresults = sql_query($mguerys, $SQLStat);
    $mi = 0;
    while ($mi < $mnum) {
        $mgresults_array = sql_fetch_assoc($mgresults);
        $MGroups[$mi] = $mgresults_array['Name'];
        ++$mi;
    }
    sql_free_result($mgresults);
    $AdminCheckURL = "";
    if ($Settings['vercheck'] === 1) {
        $AdminCheckURL = url_maker($exfile['admin'], $Settings['file_ext'], "act=vercheck&redirect=on", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']);
    }
    if ($Settings['vercheck'] === 2) {
        $AdminCheckURL = url_maker($exfile['admin'], $Settings['file_ext'], "act=vercheck&vercheck=newtype&redirect=on", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']);
    }
    $AddChkURL = null;
    if (isset($_GET['menu']) && $_GET['menu'] == "main") {
        $AddChkURL = "&menu=main";
    }
    $deftzstarttime = new DateTime();
    $deftzstarttime->setTimestamp($Settings['start_date']);
    $deftzstarttime->setTimezone($deftz);
    $utctzstarttime = new DateTime();
    $utctzstarttime->setTimestamp($Settings['start_date']);
    $utctzstarttime->setTimezone($utctz);
    $servtzstarttime = new DateTime();
    $servtzstarttime->setTimestamp($Settings['start_date']);
    $servtzstarttime->setTimezone($servtz);
    $usertzstarttime = new DateTime();
    $usertzstarttime->setTimestamp($Settings['start_date']);
    $usertzstarttime->setTimezone($usertz);
    $PreBorgURL = parse_url($OrgBoardURL);
    $PreBetURL = parse_url($Settings['idburl']);
    if ($PreBorgURL['host'] == "localhost.url" && str_replace("/", "", $PreBorgURL['path']) == "localpath") {
        $PreBetURL['host'] = $PreBorgURL['host'];
        $PreBetURL['path'] = $PreBorgURL['path'];
        $Settings['idburl'] = unparse_url($PreBetURL);
    }
    if ($PreBorgURL['host'] == "localhost.url" && str_replace("/", "", $PreBorgURL['path']) != "localpath") {
        $PreBorgURL['host'] = $PreBorgURL['host'];
        $Settings['idburl'] = unparse_url($PreBetURL);
    }
    if ($PreBorgURL['host'] != "localhost.url" && str_replace("/", "", $PreBorgURL['path']) == "localpath") {
        $PreBetURL['path'] = $PreBorgURL['path'];
        $Settings['idburl'] = unparse_url($PreBetURL);
    }
    //$PreWorgURL = parse_url($PreWestURL);
    $PreWorgURL = parse_url($OrgWebSiteURL);
    $PreBetURL = parse_url($Settings['weburl']);
    if ($PreWorgURL['host'] == "localhost.url" && str_replace("/", "", $PreWorgURL['path']) == "localpath") {
        $PreBetURL['host'] = $PreWorgURL['host'];
        $PreBetURL['path'] = $PreWorgURL['path'];
        $Settings['weburl'] = unparse_url($PreBetURL);
    }
    if ($PreWorgURL['host'] == "localhost.url" && str_replace("/", "", $PreWorgURL['path']) != "localpath") {
        $PreWorgURL['host'] = $PreWorgURL['host'];
        $Settings['weburl'] = unparse_url($PreBetURL);
    }
    if ($PreWorgURL['host'] != "localhost.url" && str_replace("/", "", $PreWorgURL['path']) == "localpath") {
        $PreBetURL['path'] = $PreWorgURL['path'];
        $Settings['weburl'] = unparse_url($PreBetURL);
    }
    ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=settings", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Settings Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=settings", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Settings Manager</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&#160;Editing Setting for iDB: </span>
<span style="float: right;">&#160;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=settings", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr>
	<td style="width: 50%;"><span class="TextBoxLabel" title="Using User Time Zone">[User TimeZone] Install Date:</span></td>
	<td style="width: 50%;"><?php echo $usertzstarttime->format($_SESSION['iDBDateFormat'].", ".$_SESSION['iDBTimeFormat']." P"); ?></td>
</tr><tr>
	<td style="width: 50%;"><span class="TextBoxLabel" title="Using Board Time Zone">[Board TimeZone] Install Date:</span></td>
	<td style="width: 50%;"><?php echo $servtzstarttime->format($_SESSION['iDBDateFormat'].", ".$_SESSION['iDBTimeFormat']." P"); ?></td>
</tr><tr>
	<td style="width: 50%;"><span class="TextBoxLabel" title="Using Server Time Zone">[Server TimeZone] Install Date:</span></td>
	<td style="width: 50%;"><?php echo $deftzstarttime->format($_SESSION['iDBDateFormat'].", ".$_SESSION['iDBTimeFormat']." P"); ?></td>
</tr><tr>
	<td style="width: 50%;"><span class="TextBoxLabel" title="Using UTC Time Zone">[UTC TimeZone] Install Date:</span></td>
	<td style="width: 50%;"><?php echo $utctzstarttime->format($_SESSION['iDBDateFormat'].", ".$_SESSION['iDBTimeFormat']." P"); ?></td>
</tr><?php if ($GroupInfo['ViewDBInfo'] == "yes") {
    if ($AdminCheckURL == "") { ?><tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Forum Software Version:</span></td>
	<td style="width: 50%;"><?php echo "<span title=\"".$VerInfo['iDB_Full_Ver_Show']."\">".$VerInfo['iDB_Ver_Show']."</span>"; ?></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Forum Software GIT Revision:</span></td>
	<td style="width: 50%;"><?php echo "<span title=\"GIT ".$GitRevN."\">".$GitRevN."</span>"; ?></td>
</tr><?php } else { ?><tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Forum Software Version:</span></td>
	<td style="width: 50%;"><?php echo "<span title=\"".$VerInfo['iDB_Full_Ver_Show']."\">".$VerInfo['iDB_Ver_Show']."</span>"; ?>&#160;<a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=vercheck", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>" onclick="window.open(this.href);return false;"><img src="<?php echo $AdminCheckURL; ?>" alt="Version Check: Click to see more info." title="Version Check: Click to see more info." /></a></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Forum Software GIT Revision:</span></td>
	<td style="width: 50%;"><?php echo "<span title=\"GIT ".$GitRevN."\">".$GitRevN."</span>"; ?>&#160;<a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=vercheck", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>" onclick="window.open(this.href);return false;"><img src="<?php echo $AdminCheckURL; ?>" alt="Version Check: Click to see more info." title="Version Check: Click to see more info." /></a></td>
</tr><?php } ?><tr>
	<td style="width: 50%;"><span class="TextBoxLabel">Forum UUID:</span></td>
	<td style="width: 50%;"><?php echo $Settings['BoardUUID']; ?></td>
</tr><tr id="clickhere" style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Version Checker:</span></td>
	<td style="width: 50%;"><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=settings".$AddChkURL, $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>#iverinfo" onclick="idbvercheck(); document.getElementById('clickhere').style.display = 'none';">Click Here</a></td>
</tr><?php if ($OSType != "" && isset($OSType)) {
    ?><tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Server Operating System:</span></td>
	<td style="width: 50%;"><?php echo $OSType; ?></td>
</tr><?php } if (isset($_SERVER['SERVER_SOFTWARE'])) {
    ?><tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Server Software:</span></td>
	<td style="width: 50%;"><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
</tr><?php } if (isset($_SERVER['SERVER_SIGNATURE'])) {
    ?><tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Server Signature:</span></td>
	<td style="width: 50%;"><?php echo $_SERVER['SERVER_SIGNATURE']; ?></td>
</tr><?php } if (function_exists('apache_get_version')) {
    ?><tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Apache Version:</span></td>
	<td style="width: 50%;"><?php echo apache_get_version(); ?></td>
</tr><?php } ?><tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Current PHP Version:</span></td>
	<td style="width: 50%;"><?php echo "PHP Version ".phpversion(); ?></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Zend Engine Version:</span></td>
	<td style="width: 50%;"><?php echo "Zend Version ".zend_version(); ?></td>
</tr><?php } ?><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="BoardURL">Insert The Board URL:</label></td>
	<td style="width: 50%;"><input type="url" class="TextBox" name="BoardURL" size="20" id="BoardURL" value="<?php echo $Settings['idburl']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="WebURL">Insert The WebSite URL:</label></td>
	<td style="width: 50%;"><input type="url" class="TextBox" name="WebURL" size="20" id="WebURL" value="<?php echo $Settings['weburl']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="PassHashType">Hash passwords with:</label></td>
	<td style="width: 50%;"><select id="PassHashType" name="PassHashType" class="TextBox">
<?php // PHP 5 hash algorithms to functions :o
    if (function_exists('hash') && function_exists('hash_algos')) {
        if (in_array("md2", hash_algos())) { ?>
<option<?php if ($Settings['use_hashtype'] == "md2") {
    echo " selected=\"selected\"";
} ?> value="md2">MD2</option>
<?php } if (in_array("md4", hash_algos())) { ?>
<option<?php if ($Settings['use_hashtype'] == "md4") {
    echo " selected=\"selected\"";
} ?> value="md4">MD4</option>
<?php } if (in_array("md5", hash_algos())) { ?>
<option<?php if ($Settings['use_hashtype'] == "md5") {
    echo " selected=\"selected\"";
} ?> value="md5">MD5</option>
<?php } if (in_array("gost", hash_algos())) { ?>
<option<?php if ($Settings['use_hashtype'] == "gost") {
    echo " selected=\"selected\"";
} ?> value="gost">GOST</option>
<?php } if (in_array("joaat", hash_algos())) { ?>
<option<?php if ($Settings['use_hashtype'] == "joaat") {
    echo " selected=\"selected\"";
} ?> value="joaat">JOAAT</option>
<?php } if (in_array("sha1", hash_algos())) { ?>
<option<?php if ($Settings['use_hashtype'] == "sha1") {
    echo " selected=\"selected\"";
} ?> value="sha1">SHA1</option>
<?php } if (in_array("sha224", hash_algos())) { ?>
<option<?php if ($Settings['use_hashtype'] == "sha224") {
    echo " selected=\"selected\"";
} ?> value="sha224">SHA224</option>
<?php } if (in_array("sha256", hash_algos())) { ?>
<option<?php if ($Settings['use_hashtype'] == "sha256") {
    echo " selected=\"selected\"";
} ?> value="sha256">SHA256</option>
<?php } if (in_array("sha384", hash_algos())) { ?>
<option<?php if ($Settings['use_hashtype'] == "sha384") {
    echo " selected=\"selected\"";
} ?> value="sha384">SHA384</option>
<?php } if (in_array("sha512", hash_algos())) { ?>
<option<?php if ($Settings['use_hashtype'] == "sha512") {
    echo " selected=\"selected\"";
} ?> value="sha512">SHA512</option>
<?php } if (in_array("sha3-224", hash_algos())) { ?>
<option<?php if ($Settings['use_hashtype'] == "sha3-224") {
    echo " selected=\"selected\"";
} ?> value="sha3-224">SHA3-224</option>
<?php } if (in_array("sha3-256", hash_algos())) { ?>
<option<?php if ($Settings['use_hashtype'] == "sha3-256") {
    echo " selected=\"selected\"";
} ?> value="sha3-256">SHA3-256</option>
<?php } if (in_array("sha3-384", hash_algos())) { ?>
<option<?php if ($Settings['use_hashtype'] == "sha3-384") {
    echo " selected=\"selected\"";
} ?> value="sha3-384">SHA3-384</option>
<?php } if (in_array("sha3-512", hash_algos())) { ?>
<option<?php if ($Settings['use_hashtype'] == "sha3-512") {
    echo " selected=\"selected\"";
} ?> value="sha3-512">SHA3-512</option>
<?php } if (in_array("ripemd128", hash_algos())) { ?>
<option<?php if ($Settings['use_hashtype'] == "ripemd128") {
    echo " selected=\"selected\"";
} ?> value="ripemd128">RIPEMD128</option>
<?php } if (in_array("ripemd160", hash_algos())) { ?>
<option<?php if ($Settings['use_hashtype'] == "ripemd160") {
    echo " selected=\"selected\"";
} ?> value="ripemd160">RIPEMD160</option>
<?php } if (in_array("ripemd256", hash_algos())) { ?>
<option<?php if ($Settings['use_hashtype'] == "ripemd256") {
    echo " selected=\"selected\"";
} ?> value="ripemd256">RIPEMD256</option>
<?php } if (in_array("ripemd320", hash_algos())) { ?>
<option<?php if ($Settings['use_hashtype'] == "ripemd320") {
    echo " selected=\"selected\"";
} ?> value="ripemd320">RIPEMD320</option>
<?php } if (function_exists('password_hash') && defined('PASSWORD_BCRYPT')) { ?>
<option<?php if ($Settings['use_hashtype'] == "bcrypt") {
    echo " selected=\"selected\"";
} ?> value="bcrypt">BCRYPT</option>
<?php } if (function_exists('password_hash') && defined('PASSWORD_ARGON2I')) { ?>
<option<?php if ($Settings['use_hashtype'] == "argon2i") {
    echo " selected=\"selected\"";
} ?> value="argon2i">ARGON2I</option>
<?php } if (function_exists('password_hash') && defined('PASSWORD_ARGON2ID')) { ?>
<option<?php if ($Settings['use_hashtype'] == "argon2id") {
    echo " selected=\"selected\"";
} ?> value="argon2id">ARGON2ID</option>
<?php }
}
    if (!function_exists('hash') && !function_exists('hash_algos')) { ?>
<option<?php if ($Settings['use_hashtype'] == "md5") {
    echo " selected=\"selected\"";
} ?> value="md5">MD5</option>
<option<?php if ($Settings['use_hashtype'] == "sha1") {
    echo " selected=\"selected\"";
} ?> value="sha1">SHA1</option>
<?php if (function_exists('password_hash') && defined('PASSWORD_BCRYPT')) { ?>
<option<?php if ($Settings['use_hashtype'] == "bcrypt") {
    echo " selected=\"selected\"";
} ?> value="bcrypt">BCRYPT</option>
<?php } if (function_exists('password_hash') && defined('PASSWORD_ARGON2I')) { ?>
<option<?php if ($Settings['use_hashtype'] == "argon2i") {
    echo " selected=\"selected\"";
} ?> value="argon2i">ARGON2I</option>
<?php } if (function_exists('password_hash') && defined('PASSWORD_ARGON2ID')) { ?>
<option<?php if ($Settings['use_hashtype'] == "argon2id") {
    echo " selected=\"selected\"";
} ?> value="argon2id">ARGON2ID</option>
<?php }
} ?>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="GuestGroup">Insert The Guest Group:</label></td>
	<td style="width: 50%;"><select id="GuestGroup" name="GuestGroup" class="TextBox">
<option selected="selected" value="<?php echo $Settings['GuestGroup']; ?>">Old Value (<?php echo $Settings['GuestGroup']; ?>)</option>
<?php $gi = 0;
    $gnum = count($MGroups);
    while ($gi < $gnum) { ?>
<option value="<?php echo $MGroups[$gi]; ?>"><?php echo $MGroups[$gi]; ?></option>
<?php ++$gi;
    } ?>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="MemberGroup">Insert The Member Group:</label></td>
	<td style="width: 50%;"><select id="MemberGroup" name="MemberGroup" class="TextBox">
<option selected="selected" value="<?php echo $Settings['MemberGroup']; ?>">Old Value (<?php echo $Settings['MemberGroup']; ?>)</option>
<?php $gi = 0;
    $gnum = count($MGroups);
    while ($gi < $gnum) { ?>
<option value="<?php echo $MGroups[$gi]; ?>"><?php echo $MGroups[$gi]; ?></option>
<?php ++$gi;
    } ?>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ValidateGroup">Insert The Validate Group:</label></td>
	<td style="width: 50%;"><select id="ValidateGroup" name="ValidateGroup" class="TextBox">
<option selected="selected" value="<?php echo $Settings['ValidateGroup']; ?>">Old Value (<?php echo $Settings['ValidateGroup']; ?>)</option>
<?php $gi = 0;
    $gnum = count($MGroups);
    while ($gi < $gnum) { ?>
<option value="<?php echo $MGroups[$gi]; ?>"><?php echo $MGroups[$gi]; ?></option>
<?php ++$gi;
    } ?>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="AdminValidate">Enable validate new members:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="AdminValidate" id="AdminValidate">
	<option<?php if ($Settings['AdminValidate'] == "off") {
	    echo " selected=\"selected\"";
	} ?> value="off">No</option>
	<option<?php if ($Settings['AdminValidate'] == "on") {
	    echo " selected=\"selected\"";
	} ?> value="on">Yes</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="max_posts">Max replies per page:</label></td>
	<td style="width: 50%;"><select id="max_posts" name="max_posts" class="TextBox">
<option selected="selected" value="<?php echo $Settings['max_posts']; ?>">Old Value (<?php echo $Settings['max_posts']; ?>)</option>
<option value="5">5</option>
<option value="10">10</option>
<option value="15">15</option>
<option value="20">20</option>
<option value="25">25</option>
<option value="30">30</option>
<option value="30">35</option>
<option value="30">40</option>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="max_topics">Max topics per page:</label></td>
	<td style="width: 50%;"><select id="max_topics" name="max_topics" class="TextBox">
<option selected="selected" value="<?php echo $Settings['max_topics']; ?>">Old Value (<?php echo $Settings['max_topics']; ?>)</option>
<option value="5">5</option>
<option value="10">10</option>
<option value="15">15</option>
<option value="20">20</option>
<option value="25">25</option>
<option value="30">30</option>
<option value="30">35</option>
<option value="30">40</option>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="max_memlist">Max members per page:</label></td>
	<td style="width: 50%;"><select id="max_memlist" name="max_memlist" class="TextBox">
<option selected="selected" value="<?php echo $Settings['max_memlist']; ?>">Old Value (<?php echo $Settings['max_memlist']; ?>)</option>
<option value="5">5</option>
<option value="10">10</option>
<option value="15">15</option>
<option value="20">20</option>
<option value="25">25</option>
<option value="30">30</option>
<option value="30">35</option>
<option value="30">40</option>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="max_pmlist">Max pms per page:</label></td>
	<td style="width: 50%;"><select id="max_pmlist" name="max_pmlist" class="TextBox">
<option selected="selected" value="<?php echo $Settings['max_pmlist']; ?>">Old Value (<?php echo $Settings['max_pmlist']; ?>)</option>
<option value="5">5</option>
<option value="10">10</option>
<option value="15">15</option>
<option value="20">20</option>
<option value="25">25</option>
<option value="30">30</option>
<option value="30">35</option>
<option value="30">40</option>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="hot_topic_num">Number of replies for hot topic:</label></td>
	<td style="width: 50%;"><select id="hot_topic_num" name="hot_topic_num" class="TextBox">
<option selected="selected" value="<?php echo $Settings['hot_topic_num']; ?>">Old Value (<?php echo $Settings['hot_topic_num']; ?>)</option>
<option value="5">5</option>
<option value="10">10</option>
<option value="15">15</option>
<option value="20">20</option>
<option value="25">25</option>
<option value="30">30</option>
<option value="30">35</option>
<option value="30">40</option>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" title="Can save some bandwidth." for="UseGzip">Enable HTTP Compression:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="UseGzip" id="UseGzip">
	<option<?php if ($Settings['use_gzip'] == "off") {
	    echo " selected=\"selected\"";
	} ?> value="off">No</option>
	<option<?php if ($Settings['use_gzip'] == "on") {
	    echo " selected=\"selected\"";
	} ?> value="on">Yes</option>
	<option<?php if ($Settings['use_gzip'] == "gzip") {
	    echo " selected=\"selected\"";
	} ?> value="gzip">Only GZip</option>
	<option<?php if ($Settings['use_gzip'] == "deflate") {
	    echo " selected=\"selected\"";
	} ?> value="deflate">Only Deflate</option>
    <?php if (function_exists('brotli_compress')) { ?>
	<option<?php if ($Settings['use_gzip'] == "brotli") {
	    echo " selected=\"selected\"";
	} ?> value="brotli">Only Brotli</option>
    <?php } if (function_exists('zstd_compress')) { ?>
	<option<?php if ($Settings['use_gzip'] == "zstd") {
	    echo " selected=\"selected\"";
	} ?> value="brotli">Only Zstandard</option>
    <?php } ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="HTMLType">HTML Type to use:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="HTMLType" id="HTMLType">
	<!--<option<?php if ($Settings['html_type'] == "xhtml10") {
	    echo " selected=\"selected\"";
	} ?> value="xhtml10">XHTML 1.0</option>-->
	<!--<option<?php if ($Settings['html_type'] == "xhtml11") {
	    echo " selected=\"selected\"";
	} ?> value="xhtml11">XHTML 1.1</option>-->
	<option<?php if ($Settings['html_type'] == "html5") {
	    echo " selected=\"selected\"";
	} ?> value="html5">HTML 5</option>
	<option<?php if ($Settings['html_type'] == "xhtml5") {
	    echo " selected=\"selected\"";
	} ?> value="xhtml5">XHTML 5</option>
	</select></td>
</tr><!--<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="OutPutType">Output file as:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="OutPutType" id="OutPutType">
	<option<?php if ($Settings['output_type'] == "html") {
	    echo " selected=\"selected\"";
	} ?> value="html">HTML</option>
	<option<?php if ($Settings['output_type'] == "xhtml") {
	    echo " selected=\"selected\"";
	} ?> value="xhtml">XHTML</option>
	</select></td>
</tr>--><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="YourOffSet">Your TimeZone:</label></td>
 <td style="width: 50%;">
    <select id="YourOffSet" name="YourOffSet" class="TextBox">
        <?php
        // List of all region labels
        $regions = ['africa', 'america', 'antarctica', 'arctic', 'asia', 'atlantic', 'australia', 'europe', 'indian', 'pacific', 'etcetera'];

    // Generate optgroups and options for each region
    foreach ($regions as $region) {
        echo '<optgroup label="' . ucfirst($region) . '">' . "\n";
        echo generateOptions($region, $zonelist, $Settings['DefaultTimeZone']); // Using $Settings['DefaultTimeZone'] for selected timezone
        echo '</optgroup>' . "\n";
    }
    ?>
    </select>
</td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DefaultTheme">Default Theme:</label></td>
	<td style="width: 50%;"><select id="DefaultTheme" name="DefaultTheme" class="TextBox"><?php
if ($Settings['SQLThemes'] == "off") {
    $skindir = dirname(realpath("settings.php"))."/".$SettDir['themes'];
    if ($handle = opendir($skindir)) {
        $dirnum = null;
        while (false !== ($file = readdir($handle))) {
            if ($dirnum == null) {
                $dirnum = 0;
            }
            if (is_dir($skindir.$file) && file_exists($skindir.$file."/info.php")) {
                if ($file != "." && $file != "..") {
                    require($skindir.$file."/info.php");
                    if ($Settings['DefaultTheme'] == $file) {
                        $themelist[$dirnum] =  "<option selected=\"selected\" value=\"".$file."\">".$ThemeInfo['ThemeName']."</option>";
                    }
                    if ($Settings['DefaultTheme'] != $file) {
                        $themelist[$dirnum] =  "<option value=\"".$file."\">".$ThemeInfo['ThemeName']."</option>";
                    }
                    ++$dirnum;
                }
            }
        }
        closedir($handle);
        asort($themelist);
        $themenum = count($themelist);
        $themei = 0;
        while ($themei < $themenum) {
            echo $themelist[$themei]."\n";
            ++$themei;
        }
    }
}
    if ($Settings['SQLThemes'] == "on") {
        $sknum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."themes\" ORDER BY \"id\" ASC, \"Name\" ASC", null), $SQLStat);
        $sknquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."themes\" ORDER BY \"id\" ASC, \"Name\" ASC", null);
        $sknresult = sql_query($sknquery, $SQLStat);
        $skni = 0;
        while ($skni < $sknum) {
            $sknresult_array = sql_fetch_assoc($sknresult);
            $ThemeInfo['Name'] = $sknresult_array['Name'];
            $ThemeInfo['ThemeName'] = $sknresult_array['ThemeName'];
            if ($Settings['DefaultTheme'] == $ThemeInfo['Name']) {
                echo "<option selected=\"selected\" value=\"".$ThemeInfo['Name']."\">".$ThemeInfo['ThemeName']."</option>\n";
            }
            if ($Settings['DefaultTheme'] != $ThemeInfo['Name']) {
                echo "<option value=\"".$ThemeInfo['Name']."\">".$ThemeInfo['ThemeName']."</option>\n";
            }
            ++$skni;
        }
    } ?></select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="enable_https">Enable HTTPS:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="enable_https" id="enable_https">
	<option<?php if ($Settings['enable_https'] == "on") {
	    echo " selected=\"selected\"";
	} ?> value="on">On</option>
	<option<?php if ($Settings['enable_https'] == "off") {
	    echo " selected=\"selected\"";
	} ?> value="off">Off</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="enable_rss">Enable RSS:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="enable_rss" id="enable_rss">
	<option<?php if ($Settings['enable_rss'] == "on") {
	    echo " selected=\"selected\"";
	} ?> value="on">On</option>
	<option<?php if ($Settings['enable_rss'] == "off") {
	    echo " selected=\"selected\"";
	} ?> value="off">Off</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="enable_search">Enable search:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="enable_search" id="enable_search">
	<option<?php if ($Settings['enable_search'] == "on") {
	    echo " selected=\"selected\"";
	} ?> value="on">On</option>
	<option<?php if ($Settings['enable_search'] == "off") {
	    echo " selected=\"selected\"";
	} ?> value="off">Off</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="TestReferer">Test Referering URL:</label></td>
	<td style="width: 50%;"><select id="TestReferer" name="TestReferer" class="TextBox">
<option<?php if ($Settings['TestReferer'] == "on") {
    echo " selected=\"selected\"";
} ?> value="on">On</option>
<option<?php if ($Settings['TestReferer'] == "off") {
    echo " selected=\"selected\"";
} ?> value="off">Off</option>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="iDBTimeFormat">Insert time format string:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="iDBTimeFormat" size="20" id="iDBTimeFormat" value="<?php echo htmlentities($Settings['idb_time_format'], ENT_QUOTES, $Settings['charset']); ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="iDBDateFormat">Insert date format string:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="iDBDateFormat" size="20" id="iDBDateFormat" value="<?php echo htmlentities($Settings['idb_date_format'], ENT_QUOTES, $Settings['charset']); ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="iDBHTTPLogger">Log Every HTTP Requests:</label></td>
	<td style="width: 50%;"><select id="iDBHTTPLogger" name="iDBHTTPLogger" class="TextBox">
<option<?php if ($Settings['log_http_request'] == "on") {
    echo " selected=\"selected\"";
} ?> value="on">On</option>
<option<?php if ($Settings['log_http_request'] == "off") {
    echo " selected=\"selected\"";
} ?> value="off">Off</option>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="iDBLoggerFormat">Insert The Format for HTTP Logger:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="iDBLoggerFormat" size="20" id="iDBLoggerFormat" value="<?php echo htmlentities($Settings['log_config_format'], ENT_QUOTES, $Settings['charset']); ?>" /></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<?php if ($GroupInfo['ViewDBInfo'] == "yes") { ?>
<span style="display: none;" id="iverinfo"><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=settings", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>#" onclick="idbvercheck();">Version Checker: Click Here</a><br /><br /></span>
<?php } ?>
<input type="hidden" name="act" value="settings" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Apply" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&#160;</td>
</tr>
</table>
</div>
<?php } if ($_POST['act'] == "settings" && $_POST['update'] == "now" && $_GET['act'] == "settings" &&
    $_SESSION['UserGroup'] != $Settings['GuestGroup'] && $GroupInfo['HasAdminCP'] == "yes") {
    $_POST  = array_map("rsq", $_POST);
    if (!isset($Settings['BoardUUID']) || $Settings['BoardUUID'] === null) {
        $Settings['BoardUUID'] = rand_uuid("rand");
    }
    $Settings['board_name'] = htmlspecialchars($Settings['board_name'], ENT_QUOTES, $Settings['charset']);
    $Settings['board_name'] = fixbamps($Settings['board_name']);
    $Settings['board_name'] = remove_spaces($Settings['board_name']);
    $Settings['board_name'] = str_replace("\&#039;", "&#039;", $Settings['board_name']);
    $SettInfo['board_name'] = htmlspecialchars($SettInfo['board_name'], ENT_QUOTES, $Settings['charset']);
    $SettInfo['board_name'] = fixbamps($SettInfo['board_name']);
    $SettInfo['board_name'] = remove_spaces($SettInfo['board_name']);
    $SettInfo['board_name'] = str_replace("\&#039;", "&#039;", $SettInfo['board_name']);
    $SettInfo['Author'] = htmlspecialchars($SettInfo['Author'], ENT_QUOTES, $Settings['charset']);
    $SettInfo['Author'] = fixbamps($SettInfo['Author']);
    $SettInfo['Author'] = remove_spaces($SettInfo['Author']);
    $SettInfo['Author'] = str_replace("\&#039;", "&#039;", $SettInfo['Author']);
    $SettInfo['Keywords'] = htmlspecialchars($SettInfo['Keywords'], ENT_QUOTES, $Settings['charset']);
    $SettInfo['Keywords'] = fixbamps($SettInfo['Keywords']);
    $SettInfo['Keywords'] = remove_spaces($SettInfo['Keywords']);
    $SettInfo['Keywords'] = str_replace("\&#039;", "&#039;", $SettInfo['Keywords']);
    $SettInfo['Description'] = htmlspecialchars($SettInfo['Description'], ENT_QUOTES, $Settings['charset']);
    $SettInfo['Description'] = fixbamps($SettInfo['Description']);
    $SettInfo['Description'] = remove_spaces($SettInfo['Description']);
    $SettInfo['Description'] = str_replace("\&#039;", "&#039;", $SettInfo['Description']);
    $_POST['BoardURL'] = htmlentities($_POST['BoardURL'], ENT_QUOTES, $Settings['charset']);
    $_POST['BoardURL'] = remove_spaces($_POST['BoardURL']);
    $_POST['WebURL'] = htmlentities($_POST['WebURL'], ENT_QUOTES, $Settings['charset']);
    $_POST['WebURL'] = remove_spaces($_POST['WebURL']);
    $_POST['iDBTimeFormat'] = convert_strftime($_POST['iDBTimeFormat']);
    $_POST['iDBDateFormat'] = convert_strftime($_POST['iDBDateFormat']);
    $Settings['idb_time_format'] = $_POST['iDBTimeFormat'];
    $Settings['idb_date_format'] = $_POST['iDBDateFormat'];
    $Settings['log_http_request'] = $_POST['iDBHTTPLogger'];
    $Settings['log_config_format'] = $_POST['iDBLoggerFormat'];
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
    if (!isset($_POST['PassHashType'])) {
        $_POST['PassHashType'] = "sha1";
    }
    if (!function_exists('hash') || !function_exists('hash_algos')) {
        if ($_POST['PassHashType'] != "md5" &&
           $_POST['PassHashType'] != "sha1") {
            $_POST['PassHashType'] = "sha1";
        }
    }
    if (function_exists('hash') && function_exists('hash_algos')) {
        if (!in_array($_POST['PassHashType'], hash_algos())) {
            $_POST['PassHashType'] = "sha1";
        }
        if ($_POST['PassHashType'] != "md2" &&
           $_POST['PassHashType'] != "md4" &&
           $_POST['PassHashType'] != "md5" &&
           $_POST['PassHashType'] != "sha1" &&
           $_POST['PassHashType'] != "sha224" &&
           $_POST['PassHashType'] != "sha256" &&
           $_POST['PassHashType'] != "sha384" &&
           $_POST['PassHashType'] != "sha512" &&
           $_POST['PassHashType'] != "sha3-224" &&
           $_POST['PassHashType'] != "sha3-256" &&
           $_POST['PassHashType'] != "sha3-384" &&
           $_POST['PassHashType'] != "sha3-512" &&
           $_POST['PassHashType'] != "ripemd128" &&
           $_POST['PassHashType'] != "ripemd160" &&
           $_POST['PassHashType'] != "ripemd256" &&
           $_POST['PassHashType'] != "ripemd320" &&
           $_POST['PassHashType'] != "bcrypt" &&
           $_POST['PassHashType'] != "argon2i" &&
           $_POST['PassHashType'] != "argon2id") {
            $_POST['PassHashType'] = "sha1";
        }
    }
    $BoardSettings = $pretext2[0]."\n".
    "\$Settings['sqlhost'] = ".null_string($Settings['sqlhost']).";\n".
    "\$Settings['sqldb'] = ".null_string($Settings['sqldb']).";\n".
    "\$Settings['sqltable'] = ".null_string($Settings['sqltable']).";\n".
    "\$Settings['sqluser'] = ".null_string($Settings['sqluser']).";\n".
    "\$Settings['sqlpass'] = ".null_string($Settings['sqlpass']).";\n".
    "\$Settings['sqltype'] = ".null_string($Settings['sqltype']).";\n".
    "\$Settings['board_name'] = ".null_string($Settings['board_name']).";\n".
    "\$Settings['idbdir'] = ".null_string($Settings['idbdir']).";\n".
    "\$Settings['idburl'] = ".null_string($_POST['BoardURL']).";\n".
    "\$Settings['enable_https'] = ".null_string($_POST['enable_https']).";\n".
    "\$Settings['weburl'] = ".null_string($_POST['WebURL']).";\n".
    "\$Settings['SQLThemes'] = ".null_string($Settings['SQLThemes']).";\n".
    "\$Settings['use_gzip'] = ".null_string($_POST['UseGzip']).";\n".
    "\$Settings['html_type'] = ".null_string($_POST['HTMLType']).";\n".
    "\$Settings['output_type'] = ".null_string($_POST['OutPutType']).";\n".
    "\$Settings['GuestGroup'] = ".null_string($_POST['GuestGroup']).";\n".
    "\$Settings['MemberGroup'] = ".null_string($_POST['MemberGroup']).";\n".
    "\$Settings['ValidateGroup'] = ".null_string($_POST['ValidateGroup']).";\n".
    "\$Settings['AdminValidate'] = ".null_string($_POST['AdminValidate']).";\n".
    "\$Settings['TestReferer'] = ".null_string($_POST['TestReferer']).";\n".
    "\$Settings['DefaultTheme'] = ".null_string($_POST['DefaultTheme']).";\n".
    "\$Settings['DefaultTimeZone'] = ".null_string($_POST['YourOffSet']).";\n".
    "\$Settings['start_date'] = ".null_string($Settings['start_date']).";\n".
    "\$Settings['idb_time_format'] = ".null_string($Settings['idb_time_format']).";\n".
    "\$Settings['idb_date_format'] = ".null_string($Settings['idb_date_format']).";\n".
    "\$Settings['use_hashtype'] = ".null_string($_POST['PassHashType']).";\n".
    "\$Settings['charset'] = ".null_string($Settings['charset']).";\n".
    "\$Settings['sql_collate'] = ".null_string($Settings['sql_collate']).";\n".
    "\$Settings['sql_charset'] = ".null_string($Settings['sql_charset']).";\n".
    "\$Settings['add_power_by'] = ".null_string($Settings['add_power_by']).";\n".
    "\$Settings['send_pagesize'] = ".null_string($Settings['send_pagesize']).";\n".
    "\$Settings['max_posts'] = ".null_string($_POST['max_posts']).";\n".
    "\$Settings['max_topics'] = ".null_string($_POST['max_topics']).";\n".
    "\$Settings['max_memlist'] = ".null_string($_POST['max_memlist']).";\n".
    "\$Settings['max_pmlist'] = ".null_string($_POST['max_pmlist']).";\n".
    "\$Settings['hot_topic_num'] = ".null_string($_POST['hot_topic_num']).";\n".
    "\$Settings['qstr'] = ".null_string($Settings['qstr']).";\n".
    "\$Settings['qsep'] = ".null_string($Settings['qsep']).";\n".
    "\$Settings['file_ext'] = ".null_string($Settings['file_ext']).";\n".
    "\$Settings['rss_ext'] = ".null_string($Settings['rss_ext']).";\n".
    "\$Settings['js_ext'] = ".null_string($Settings['js_ext']).";\n".
    "\$Settings['showverinfo'] = ".null_string($Settings['showverinfo']).";\n".
    "\$Settings['vercheck'] = ".null_string($Settings['vercheck']).";\n".
    "\$Settings['enable_rss'] = ".null_string($_POST['enable_rss']).";\n".
    "\$Settings['enable_search'] = ".null_string($_POST['enable_search']).";\n".
    "\$Settings['sessionid_in_urls'] = ".null_string($Settings['sessionid_in_urls']).";\n".
    "\$Settings['fixpathinfo'] = ".null_string($OldSettings['fixpathinfo']).";\n".
    "\$Settings['fixbasedir'] = ".null_string($OldSettings['fixbasedir']).";\n".
    "\$Settings['fixcookiedir'] = ".null_string($OldSettings['fixcookiedir']).";\n".
    "\$Settings['fixredirectdir'] = ".null_string($OldSettings['fixredirectdir']).";\n".
    "\$Settings['enable_pathinfo'] = ".null_string($Settings['enable_pathinfo']).";\n".
    "\$Settings['rssurl'] = ".null_string($Settings['rssurl']).";\n".
    "\$Settings['board_offline'] = ".null_string($Settings['board_offline']).";\n".
    "\$Settings['VerCheckURL'] = ".null_string($Settings['VerCheckURL']).";\n".
    "\$Settings['IPCheckURL'] = ".null_string($Settings['IPCheckURL']).";\n".
    "\$Settings['log_http_request'] = ".null_string($Settings['log_http_request']).";\n".
    "\$Settings['log_config_format'] = ".null_string($Settings['log_config_format']).";\n".
    "\$Settings['BoardUUID'] = ".null_string(base64_encode($Settings['BoardUUID'])).";\n".
    "\$Settings['KarmaBoostDays'] = ".null_string($Settings['KarmaBoostDays']).";\n".
    "\$Settings['KBoostPercent'] = ".null_string($Settings['KBoostPercent']).";\n".$pretext2[1]."\n".
    "\$SettInfo['board_name'] = ".null_string($SettInfo['board_name']).";\n".
    "\$SettInfo['Author'] = ".null_string($SettInfo['Author']).";\n".
    "\$SettInfo['Keywords'] = ".null_string($SettInfo['Keywords']).";\n".
    "\$SettInfo['Description'] = ".null_string($SettInfo['Description']).";\n".$pretext2[2]."\n".
    "\$SettDir['maindir'] = ".null_string($SettDir['maindir']).";\n".
    "\$SettDir['inc'] = ".null_string($SettDir['inc']).";\n".
    "\$SettDir['logs'] = ".null_string($SettDir['logs']).";\n".
    "\$SettDir['archive'] = ".null_string($SettDir['archive']).";\n".
    "\$SettDir['misc'] = ".null_string($SettDir['misc']).";\n".
    "\$SettDir['sql'] = ".null_string($SettDir['sql']).";\n".
    "\$SettDir['admin'] = ".null_string($SettDir['admin']).";\n".
    "\$SettDir['sqldumper'] = ".null_string($SettDir['sqldumper']).";\n".
    "\$SettDir['mod'] = ".null_string($SettDir['mod']).";\n".
    "\$SettDir['themes'] = ".null_string($SettDir['themes']).";\n".$pretext2[3]."\n?>";
    $BoardSettingsBak = $pretext.$settcheck.$BoardSettings;
    $BoardSettings = $pretext.$settcheck.$BoardSettings;
    $fp = fopen("settings.php", "w+");
    fwrite($fp, $BoardSettings);
    fclose($fp);
    //	cp("settings.php","settingsbak.php");
    $fp = fopen("settingsbak.php", "w+");
    fwrite($fp, $BoardSettingsBak);
    fclose($fp);
} if ($_GET['act'] == "sql" && $_POST['update'] != "now" && $GroupInfo['ViewDBInfo'] == "yes") {
    $admincptitle = " ".$ThemeSet['TitleDivider']." Database Manager";
    ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=sql", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Database Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=sql", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Database Manager</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&#160;Editing SQL Settings for iDB: </span>
<span style="float: right;">&#160;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=sql", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Database Server:</span></td>
	<td style="width: 50%;"><?php echo $DBType['Server']; ?></td>
</tr><?php if ($Settings['sqltype'] == "mysqli" ||
        $Settings['sqltype'] == "mysqli_prepare" ||
        $Settings['sqltype'] == "pdo_mysql" ||
        $Settings['sqltype'] == "pgsql" ||
        $Settings['sqltype'] == "pgsql_prepare" ||
        $Settings['sqltype'] == "pdo_pgsql" ||
        $Settings['sqltype'] == "cubrid" ||
        $Settings['sqltype'] == "cubrid_prepare" ||
        $Settings['sqltype'] == "pdo_cubrid" ||
        $Settings['sqltype'] == "sqlsrv_prepare" ||
        $Settings['sqltype'] == "pdo_sqlsrv") {
    ?><tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Database Client:</span></td>
	<td style="width: 50%;"><?php echo $DBType['Client']; ?></td>
</tr><?php } if ($Settings['sqltype'] == "sqlite3" ||
        $Settings['sqltype'] == "sqlite3_prepare" ||
        $Settings['sqltype'] == "pdo_sqlite3") {
    ?><tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Database File Size:</span></td>
	<td style="width: 50%;"><?php echo sprintf("%u", filesize($Settings['sqldb']))." bytes"; ?></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Human Readable File Size in Decimal:</span></td>
	<td style="width: 50%;"><?php echo human_filesize(filesize($Settings['sqldb']), 2, false); ?></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Human Readable File Size in Binary:</span></td>
	<td style="width: 50%;"><?php echo human_filesize(filesize($Settings['sqldb']), 2, true); ?></td>
</tr><?php } if ($Settings['sqltype'] == "cubrid") { ?><tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">CUBRID PHP:</span></td>
	<td style="width: 50%;"><?php echo $DBType['PHP']; ?></td>
</tr><?php } if ($Settings['sqltype'] != "sqlite3" &&
        $Settings['sqltype'] != "sqlite3_prepare" &&
        $Settings['sqltype'] != "pdo_sqlite3") {  ?><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DatabaseUserName">Insert Database User Name:</label></td>
	<td style="width: 50%;"><input type="text" name="DatabaseUserName" class="TextBox" id="DatabaseUserName" size="20" value="<?php echo $Settings['sqluser']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DatabasePassword">Insert Database Password:</label></td>
	<td style="width: 50%;"><input type="password" name="DatabasePassword" class="TextBox" id="DatabasePassword" size="20" value="<?php echo $Settings['sqlpass']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DatabaseName">Insert Database Name:</label></td>
	<td style="width: 50%;"><input type="text" name="DatabaseName" class="TextBox" id="DatabaseName" size="20" value="<?php echo $Settings['sqldb']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DatabaseHost">Insert Database Host:</label></td>
	<td style="width: 50%;"><input type="text" name="DatabaseHost" class="TextBox" id="DatabaseHost" size="20" value="<?php echo $Settings['sqlhost']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="tableprefix">Insert Table Prefix:<br /></label></td>
	<td style="width: 50%;"><input type="text" name="tableprefix" class="TextBox" id="tableprefix" size="20" value="<?php echo $Settings['sqltable']; ?>" /></td>
</tr><?php } if ($Settings['sqltype'] == "sqlite3" ||
        $Settings['sqltype'] == "sqlite3_prepare" ||
        $Settings['sqltype'] == "pdo_sqlite3") {  ?><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DatabaseName">Insert Database FileName:</label></td>
	<td style="width: 50%;"><input type="text" name="DatabaseName" class="TextBox" id="DatabaseName" size="20" value="<?php echo $Settings['sqldb']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="tableprefix">Insert Table Prefix:<br /></label></td>
	<td style="width: 50%;"><input type="text" name="tableprefix" class="TextBox" id="tableprefix" size="20" value="<?php echo $Settings['sqltable']; ?>" /></td>
</tr><?php } ?></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<?php if ($Settings['sqltype'] == "sqlite3" ||
        $Settings['sqltype'] == "sqlite3_prepare" ||
        $Settings['sqltype'] == "pdo_sqlite3") {  ?>
<input type="hidden" name="DatabaseUserName" class="TextBox" id="DatabaseUserName" size="20" value="<?php echo $Settings['sqluser']; ?>" />
<input type="hidden" name="DatabasePassword" class="TextBox" id="DatabasePassword" size="20" value="<?php echo $Settings['sqlpass']; ?>" />
<input type="hidden" name="DatabaseHost" class="TextBox" id="DatabaseHost" size="20" value="<?php echo $Settings['sqlhost']; ?>" />
<?php } ?>
<input type="hidden" name="act" value="sql" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Apply" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&#160;</td>
</tr>
</table>
</div>
<?php } if ($_POST['act'] == "sql" && $_POST['update'] == "now" && $_GET['act'] == "sql" &&
        $_SESSION['UserGroup'] != $Settings['GuestGroup'] && $GroupInfo['HasAdminCP'] == "yes" &&
        $GroupInfo['ViewDBInfo'] == "yes") {
    $_POST  = array_map("rsq", $_POST);
    if (!isset($Settings['BoardUUID']) || $Settings['BoardUUID'] === null) {
        $Settings['BoardUUID'] = rand_uuid("rand");
    }
    $Settings['board_name'] = htmlspecialchars($Settings['board_name'], ENT_QUOTES, $Settings['charset']);
    $Settings['board_name'] = fixbamps($Settings['board_name']);
    $Settings['board_name'] = remove_spaces($Settings['board_name']);
    $Settings['board_name'] = str_replace("\&#039;", "&#039;", $Settings['board_name']);
    $SettInfo['board_name'] = htmlspecialchars($SettInfo['board_name'], ENT_QUOTES, $Settings['charset']);
    $SettInfo['board_name'] = fixbamps($SettInfo['board_name']);
    $SettInfo['board_name'] = remove_spaces($SettInfo['board_name']);
    $SettInfo['board_name'] = str_replace("\&#039;", "&#039;", $SettInfo['board_name']);
    $SettInfo['Author'] = htmlspecialchars($SettInfo['Author'], ENT_QUOTES, $Settings['charset']);
    $SettInfo['Author'] = fixbamps($SettInfo['Author']);
    $SettInfo['Author'] = remove_spaces($SettInfo['Author']);
    $SettInfo['Author'] = str_replace("\&#039;", "&#039;", $SettInfo['Author']);
    $SettInfo['Keywords'] = htmlspecialchars($SettInfo['Keywords'], ENT_QUOTES, $Settings['charset']);
    $SettInfo['Keywords'] = fixbamps($SettInfo['Keywords']);
    $SettInfo['Keywords'] = remove_spaces($SettInfo['Keywords']);
    $SettInfo['Keywords'] = str_replace("\&#039;", "&#039;", $SettInfo['Keywords']);
    $SettInfo['Description'] = htmlspecialchars($SettInfo['Description'], ENT_QUOTES, $Settings['charset']);
    $SettInfo['Description'] = fixbamps($SettInfo['Description']);
    $SettInfo['Description'] = remove_spaces($SettInfo['Description']);
    $SettInfo['Description'] = str_replace("\&#039;", "&#039;", $SettInfo['Description']);
    $BoardSettings = $pretext2[0]."\n".
    "\$Settings['sqlhost'] = ".null_string($_POST['DatabaseHost']).";\n".
    "\$Settings['sqldb'] = ".null_string($_POST['DatabaseName']).";\n".
    "\$Settings['sqltable'] = ".null_string($_POST['tableprefix']).";\n".
    "\$Settings['sqluser'] = ".null_string($_POST['DatabaseUserName']).";\n".
    "\$Settings['sqlpass'] = ".null_string($_POST['DatabasePassword']).";\n".
    "\$Settings['sqltype'] = ".null_string($Settings['sqltype']).";\n".
    "\$Settings['board_name'] = ".null_string($Settings['board_name']).";\n".
    "\$Settings['idbdir'] = ".null_string($Settings['idbdir']).";\n".
    "\$Settings['idburl'] = ".null_string($Settings['idburl']).";\n".
    "\$Settings['enable_https'] = ".null_string($Settings['enable_https']).";\n".
    "\$Settings['weburl'] = ".null_string($Settings['weburl']).";\n".
    "\$Settings['SQLThemes'] = ".null_string($Settings['SQLThemes']).";\n".
    "\$Settings['use_gzip'] = ".null_string($Settings['use_gzip']).";\n".
    "\$Settings['html_type'] = ".null_string($Settings['html_type']).";\n".
    "\$Settings['html_level'] = ".null_string($Settings['html_level']).";\n".
    "\$Settings['output_type'] = ".null_string($Settings['output_type']).";\n".
    "\$Settings['GuestGroup'] = ".null_string($Settings['GuestGroup']).";\n".
    "\$Settings['MemberGroup'] = ".null_string($Settings['MemberGroup']).";\n".
    "\$Settings['ValidateGroup'] = ".null_string($Settings['ValidateGroup']).";\n".
    "\$Settings['AdminValidate'] = ".null_string($Settings['AdminValidate']).";\n".
    "\$Settings['TestReferer'] = ".null_string($Settings['TestReferer']).";\n".
    "\$Settings['DefaultTheme'] = ".null_string($Settings['DefaultTheme']).";\n".
    "\$Settings['DefaultTimeZone'] = ".null_string($Settings['DefaultTimeZone']).";\n".
    "\$Settings['start_date'] = ".null_string($Settings['start_date']).";\n".
    "\$Settings['idb_time_format'] = ".null_string($Settings['idb_time_format']).";\n".
    "\$Settings['idb_date_format'] = ".null_string($Settings['idb_date_format']).";\n".
    "\$Settings['use_hashtype'] = ".null_string($Settings['use_hashtype']).";\n".
    "\$Settings['charset'] = ".null_string($Settings['charset']).";\n".
    "\$Settings['sql_collate'] = ".null_string($Settings['sql_collate']).";\n".
    "\$Settings['sql_charset'] = ".null_string($Settings['sql_charset']).";\n".
    "\$Settings['add_power_by'] = ".null_string($Settings['add_power_by']).";\n".
    "\$Settings['send_pagesize'] = ".null_string($Settings['send_pagesize']).";\n".
    "\$Settings['max_posts'] = ".null_string($Settings['max_posts']).";\n".
    "\$Settings['max_topics'] = ".null_string($Settings['max_topics']).";\n".
    "\$Settings['max_memlist'] = ".null_string($Settings['max_memlist']).";\n".
    "\$Settings['max_pmlist'] = ".null_string($Settings['max_pmlist']).";\n".
    "\$Settings['hot_topic_num'] = ".null_string($Settings['hot_topic_num']).";\n".
    "\$Settings['qstr'] = ".null_string($Settings['qstr']).";\n".
    "\$Settings['qsep'] = ".null_string($Settings['qsep']).";\n".
    "\$Settings['file_ext'] = ".null_string($Settings['file_ext']).";\n".
    "\$Settings['rss_ext'] = ".null_string($Settings['rss_ext']).";\n".
    "\$Settings['js_ext'] = ".null_string($Settings['js_ext']).";\n".
    "\$Settings['showverinfo'] = ".null_string($Settings['showverinfo']).";\n".
    "\$Settings['vercheck'] = ".null_string($Settings['vercheck']).";\n".
    "\$Settings['enable_rss'] = ".null_string($Settings['enable_rss']).";\n".
    "\$Settings['enable_search'] = ".null_string($Settings['enable_search']).";\n".
    "\$Settings['sessionid_in_urls'] = ".null_string($Settings['sessionid_in_urls']).";\n".
    "\$Settings['fixpathinfo'] = ".null_string($OldSettings['fixpathinfo']).";\n".
    "\$Settings['fixbasedir'] = ".null_string($OldSettings['fixbasedir']).";\n".
    "\$Settings['fixcookiedir'] = ".null_string($OldSettings['fixcookiedir']).";\n".
    "\$Settings['fixredirectdir'] = ".null_string($OldSettings['fixredirectdir']).";\n".
    "\$Settings['enable_pathinfo'] = ".null_string($Settings['enable_pathinfo']).";\n".
    "\$Settings['rssurl'] = ".null_string($Settings['rssurl']).";\n".
    "\$Settings['board_offline'] = ".null_string($Settings['board_offline']).";\n".
    "\$Settings['VerCheckURL'] = ".null_string($Settings['VerCheckURL']).";\n".
    "\$Settings['IPCheckURL'] = ".null_string($Settings['IPCheckURL']).";\n".
    "\$Settings['log_http_request'] = ".null_string($Settings['log_http_request']).";\n".
    "\$Settings['log_config_format'] = ".null_string($Settings['log_config_format']).";\n".
    "\$Settings['BoardUUID'] = ".null_string(base64_encode($Settings['BoardUUID'])).";\n".
    "\$Settings['KarmaBoostDays'] = ".null_string($Settings['KarmaBoostDays']).";\n".
    "\$Settings['KBoostPercent'] = ".null_string($Settings['KBoostPercent']).";\n".$pretext2[1]."\n".
    "\$SettInfo['board_name'] = ".null_string($SettInfo['board_name']).";\n".
    "\$SettInfo['Author'] = ".null_string($SettInfo['Author']).";\n".
    "\$SettInfo['Keywords'] = ".null_string($SettInfo['Keywords']).";\n".
    "\$SettInfo['Description'] = ".null_string($SettInfo['Description']).";\n".$pretext2[2]."\n".
    "\$SettDir['maindir'] = ".null_string($SettDir['maindir']).";\n".
    "\$SettDir['inc'] = ".null_string($SettDir['inc']).";\n".
    "\$SettDir['logs'] = ".null_string($SettDir['logs']).";\n".
    "\$SettDir['archive'] = ".null_string($SettDir['archive']).";\n".
    "\$SettDir['misc'] = ".null_string($SettDir['misc']).";\n".
    "\$SettDir['sql'] = ".null_string($SettDir['sql']).";\n".
    "\$SettDir['admin'] = ".null_string($SettDir['admin']).";\n".
    "\$SettDir['sqldumper'] = ".null_string($SettDir['sqldumper']).";\n".
    "\$SettDir['mod'] = ".null_string($SettDir['mod']).";\n".
    "\$SettDir['themes'] = ".null_string($SettDir['themes']).";\n".$pretext2[3]."\n?>";
    $BoardSettingsBak = $pretext.$settcheck.$BoardSettings;
    $BoardSettings = $pretext.$settcheck.$BoardSettings;
    $fp = fopen("settings.php", "w+");
    fwrite($fp, $BoardSettings);
    fclose($fp);
    //	cp("settings.php","settingsbak.php");
    $fp = fopen("settingsbak.php", "w+");
    fwrite($fp, $BoardSettingsBak);
    fclose($fp);
} if ($_GET['act'] == "info" && $_POST['update'] != "now") {
    $admincptitle = " ".$ThemeSet['TitleDivider']." Board Info Manager";
    ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=info", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Board Info Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=info", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Board Info Manager</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&#160;Editing Board Info: </span>
<span style="float: right;">&#160;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=info", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="board_name">Insert board name:</label></td>
	<td style="width: 50%;"><input type="text" name="board_name" class="TextBox" id="board_name" size="20" value="<?php echo $SettInfo['board_name']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="Author">Insert boards admin name:</label></td>
	<td style="width: 50%;"><input type="text" name="Author" class="TextBox" id="Author" size="20" value="<?php echo $SettInfo['Author']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="Keywords">Insert keywords about this board:</label></td>
	<td style="width: 50%;"><input type="text" name="Keywords" class="TextBox" id="Keywords" size="20" value="<?php echo $SettInfo['Keywords']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="Description">Insert description about this board:<br /></label></td>
	<td style="width: 50%;"><input type="text" name="Description" class="TextBox" id="Description" size="20" value="<?php echo $SettInfo['Description']; ?>" /></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="info" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Apply" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&#160;</td>
</tr>
</table>
</div>
<?php } if ($_POST['act'] == "info" && $_POST['update'] == "now" && $_GET['act'] == "info" &&
        $_SESSION['UserGroup'] != $Settings['GuestGroup'] && $GroupInfo['HasAdminCP'] == "yes") {
    $_POST  = array_map("rsq", $_POST);
    if (!isset($Settings['BoardUUID']) || $Settings['BoardUUID'] === null) {
        $Settings['BoardUUID'] = rand_uuid("rand");
    }
    $_POST['board_name'] = htmlspecialchars($_POST['board_name'], ENT_QUOTES, $Settings['charset']);
    $_POST['board_name'] = fixbamps($_POST['board_name']);
    $_POST['board_name'] = remove_spaces($_POST['board_name']);
    $_POST['board_name'] = str_replace("\&#039;", "&#039;", $_POST['board_name']);
    if ($_POST['board_name'] != $Settings['board_name'] &&
        $Settings['SQLThemes'] == "on") {
        $logoquery = sql_pre_query("UPDATE \"".$Settings['sqltable']."themes\" SET \"Logo\"='%s' WHERE \"Logo\"='%s'", array($_POST['board_name'],$Settings['board_name']));
        sql_query($logo, $SQLStat);
    }
    $_POST['Author'] = htmlspecialchars($_POST['Author'], ENT_QUOTES, $Settings['charset']);
    $_POST['Author'] = fixbamps($_POST['Author']);
    $_POST['Author'] = remove_spaces($_POST['Author']);
    $_POST['Author'] = str_replace("\&#039;", "&#039;", $_POST['Author']);
    $_POST['Keywords'] = htmlspecialchars($_POST['Keywords'], ENT_QUOTES, $Settings['charset']);
    $_POST['Keywords'] = fixbamps($_POST['Keywords']);
    $_POST['Keywords'] = remove_spaces($_POST['Keywords']);
    $_POST['Keywords'] = str_replace("\&#039;", "&#039;", $_POST['Keywords']);
    $_POST['Description'] = htmlspecialchars($_POST['Description'], ENT_QUOTES, $Settings['charset']);
    $_POST['Description'] = fixbamps($_POST['Description']);
    $_POST['Description'] = remove_spaces($_POST['Description']);
    $_POST['Description'] = str_replace("\&#039;", "&#039;", $_POST['Description']);
    $BoardSettings = $pretext2[0]."\n".
    "\$Settings['sqlhost'] = ".null_string($Settings['sqlhost']).";\n".
    "\$Settings['sqldb'] = ".null_string($Settings['sqldb']).";\n".
    "\$Settings['sqltable'] = ".null_string($Settings['sqltable']).";\n".
    "\$Settings['sqluser'] = ".null_string($Settings['sqluser']).";\n".
    "\$Settings['sqlpass'] = ".null_string($Settings['sqlpass']).";\n".
    "\$Settings['sqltype'] = ".null_string($Settings['sqltype']).";\n".
    "\$Settings['board_name'] = ".null_string($_POST['board_name']).";\n".
    "\$Settings['idbdir'] = ".null_string($Settings['idbdir']).";\n".
    "\$Settings['idburl'] = ".null_string($Settings['idburl']).";\n".
    "\$Settings['enable_https'] = ".null_string($Settings['enable_https']).";\n".
    "\$Settings['weburl'] = ".null_string($Settings['weburl']).";\n".
    "\$Settings['SQLThemes'] = ".null_string($Settings['SQLThemes']).";\n".
    "\$Settings['use_gzip'] = ".null_string($Settings['use_gzip']).";\n".
    "\$Settings['html_type'] = ".null_string($Settings['html_type']).";\n".
    "\$Settings['html_level'] = ".null_string($Settings['html_level']).";\n".
    "\$Settings['output_type'] = ".null_string($Settings['output_type']).";\n".
    "\$Settings['GuestGroup'] = ".null_string($Settings['GuestGroup']).";\n".
    "\$Settings['MemberGroup'] = ".null_string($Settings['MemberGroup']).";\n".
    "\$Settings['ValidateGroup'] = ".null_string($Settings['ValidateGroup']).";\n".
    "\$Settings['AdminValidate'] = ".null_string($Settings['AdminValidate']).";\n".
    "\$Settings['TestReferer'] = ".null_string($Settings['TestReferer']).";\n".
    "\$Settings['DefaultTheme'] = ".null_string($Settings['DefaultTheme']).";\n".
    "\$Settings['DefaultTimeZone'] = ".null_string($Settings['DefaultTimeZone']).";\n".
    "\$Settings['start_date'] = ".null_string($Settings['start_date']).";\n".
    "\$Settings['idb_time_format'] = ".null_string($Settings['idb_time_format']).";\n".
    "\$Settings['idb_date_format'] = ".null_string($Settings['idb_date_format']).";\n".
    "\$Settings['use_hashtype'] = ".null_string($Settings['use_hashtype']).";\n".
    "\$Settings['charset'] = ".null_string($Settings['charset']).";\n".
    "\$Settings['sql_collate'] = ".null_string($Settings['sql_collate']).";\n".
    "\$Settings['sql_charset'] = ".null_string($Settings['sql_charset']).";\n".
    "\$Settings['add_power_by'] = ".null_string($Settings['add_power_by']).";\n".
    "\$Settings['send_pagesize'] = ".null_string($Settings['send_pagesize']).";\n".
    "\$Settings['max_posts'] = ".null_string($Settings['max_posts']).";\n".
    "\$Settings['max_topics'] = ".null_string($Settings['max_topics']).";\n".
    "\$Settings['max_memlist'] = ".null_string($Settings['max_memlist']).";\n".
    "\$Settings['max_pmlist'] = ".null_string($Settings['max_pmlist']).";\n".
    "\$Settings['hot_topic_num'] = ".null_string($Settings['hot_topic_num']).";\n".
    "\$Settings['qstr'] = ".null_string($Settings['qstr']).";\n".
    "\$Settings['qsep'] = ".null_string($Settings['qsep']).";\n".
    "\$Settings['file_ext'] = ".null_string($Settings['file_ext']).";\n".
    "\$Settings['rss_ext'] = ".null_string($Settings['rss_ext']).";\n".
    "\$Settings['js_ext'] = ".null_string($Settings['js_ext']).";\n".
    "\$Settings['showverinfo'] = ".null_string($Settings['showverinfo']).";\n".
    "\$Settings['vercheck'] = ".null_string($Settings['vercheck']).";\n".
    "\$Settings['enable_rss'] = ".null_string($Settings['enable_rss']).";\n".
    "\$Settings['enable_search'] = ".null_string($Settings['enable_search']).";\n".
    "\$Settings['sessionid_in_urls'] = ".null_string($Settings['sessionid_in_urls']).";\n".
    "\$Settings['fixpathinfo'] = ".null_string($OldSettings['fixpathinfo']).";\n".
    "\$Settings['fixbasedir'] = ".null_string($OldSettings['fixbasedir']).";\n".
    "\$Settings['fixcookiedir'] = ".null_string($OldSettings['fixcookiedir']).";\n".
    "\$Settings['fixredirectdir'] = ".null_string($OldSettings['fixredirectdir']).";\n".
    "\$Settings['enable_pathinfo'] = ".null_string($Settings['enable_pathinfo']).";\n".
    "\$Settings['rssurl'] = ".null_string($Settings['rssurl']).";\n".
    "\$Settings['board_offline'] = ".null_string($Settings['board_offline']).";\n".
    "\$Settings['VerCheckURL'] = ".null_string($Settings['VerCheckURL']).";\n".
    "\$Settings['IPCheckURL'] = ".null_string($Settings['IPCheckURL']).";\n".
    "\$Settings['log_http_request'] = ".null_string($Settings['log_http_request']).";\n".
    "\$Settings['log_config_format'] = ".null_string($Settings['log_config_format']).";\n".
    "\$Settings['BoardUUID'] = ".null_string(base64_encode($Settings['BoardUUID'])).";\n".
    "\$Settings['KarmaBoostDays'] = ".null_string($Settings['KarmaBoostDays']).";\n".
    "\$Settings['KBoostPercent'] = ".null_string($Settings['KBoostPercent']).";\n".$pretext2[1]."\n".
    "\$SettInfo['board_name'] = ".null_string($_POST['board_name']).";\n".
    "\$SettInfo['Author'] = ".null_string($_POST['Author']).";\n".
    "\$SettInfo['Keywords'] = ".null_string($_POST['Keywords']).";\n".
    "\$SettInfo['Description'] = ".null_string($_POST['Description']).";\n".$pretext2[2]."\n".
    "\$SettDir['maindir'] = ".null_string($SettDir['maindir']).";\n".
    "\$SettDir['inc'] = ".null_string($SettDir['inc']).";\n".
    "\$SettDir['logs'] = ".null_string($SettDir['logs']).";\n".
    "\$SettDir['archive'] = ".null_string($SettDir['archive']).";\n".
    "\$SettDir['misc'] = ".null_string($SettDir['misc']).";\n".
    "\$SettDir['sql'] = ".null_string($SettDir['sql']).";\n".
    "\$SettDir['admin'] = ".null_string($SettDir['admin']).";\n".
    "\$SettDir['sqldumper'] = ".null_string($SettDir['sqldumper']).";\n".
    "\$SettDir['mod'] = ".null_string($SettDir['mod']).";\n".
    "\$SettDir['themes'] = ".null_string($SettDir['themes']).";\n".$pretext2[3]."\n?>";
    $BoardSettingsBak = $pretext.$settcheck.$BoardSettings;
    $BoardSettings = $pretext.$settcheck.$BoardSettings;
    $fp = fopen("settings.php", "w+");
    fwrite($fp, $BoardSettings);
    fclose($fp);
    //	cp("settings.php","settingsbak.php");
    $fp = fopen("settingsbak.php", "w+");
    fwrite($fp, $BoardSettingsBak);
    fclose($fp);
} if ($_POST['update'] == "now" && $_GET['act'] != null) {
    $profiletitle = " ".$ThemeSet['TitleDivider']." Updating Settings"; ?>
</td></tr>
<tr id="ProfileTitleEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&#160;</td>
</tr></table></div><?php } ?>
</td></tr>
</table>
<div>&#160;</div>
