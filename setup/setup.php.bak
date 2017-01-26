<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2015 iDB Support - http://idb.berlios.de/
    Copyright 2004-2015 Game Maker 2k - http://gamemaker2k.org/
    iDB Installer made by Game Maker 2k - http://idb.berlios.net/

    $FileInfo: setup.php - Last Update: 01/26/2017 SVN 810 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="setup.php"||$File3Name=="/setup.php") {
	require('index.php');
	exit(); }
if(!isset($SetupDir['setup'])) { $SetupDir['setup'] = "setup/"; }
if(!isset($SetupDir['convert'])) { $SetupDir['convert'] = "setup/convert/"; }
?>
<tr class="TableRow3">
<td class="TableColumn3">
<?php
$checkfile="settings.php";
$iDBRDate = $SVNDay[0]."/".$SVNDay[1]."/".$SVNDay[2];
$iDBRSVN = $VER2[2]." ".$SubVerN;
$LastUpdateS = "Last Update: ".$iDBRDate." ".$iDBRSVN;
$pretext = "<?php\n/*\n    This program is free software; you can redistribute it and/or modify\n    it under the terms of the GNU General Public License as published by\n    the Free Software Foundation; either version 2 of the License, or\n    (at your option) any later version.\n\n    This program is distributed in the hope that it will be useful,\n    but WITHOUT ANY WARRANTY; without even the implied warranty of\n    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n    Revised BSD License for more details.\n\n    Copyright 2004-".$SVNDay[2]." iDB Support - http://idb.berlios.de/\n    Copyright 2004-".$SVNDay[2]." Game Maker 2k - http://gamemaker2k.org/\n    iDB Installer made by Game Maker 2k - http://idb.berlios.net/\n\n    \$FileInfo: settings.php & settingsbak.php - ".$LastUpdateS." - Author: cooldude2k \$\n*/\n";
$pretext2 = array("/*   Board Setting Section Begins   */\n\$Settings = array();","/*   Board Setting Section Ends  \n     Board Info Section Begins   */\n\$SettInfo = array();","/*   Board Setting Section Ends   \n     Board Dir Section Begins   */\n\$SettDir = array();","/*   Board Dir Section Ends   */");
$settcheck = "\$File3Name = basename(\$_SERVER['SCRIPT_NAME']);\nif (\$File3Name==\"settings.php\"||\$File3Name==\"/settings.php\"||\n    \$File3Name==\"settingsbak.php\"||\$File3Name==\"/settingsbak.php\") {\n    header('Location: index.php');\n    exit(); }\n";
$BoardSettingsBak = $pretext.$settcheck;
$BoardSettings = $pretext.$settcheck;
$fp = fopen("settings.php","w+");
fwrite($fp, $BoardSettings);
fclose($fp);
//	cp("settings.php","settingsbak.php");
$fp = fopen("settingsbak.php","w+");
fwrite($fp, $BoardSettingsBak);
fclose($fp);
if (!is_writable($checkfile)) {
   echo "<br />Settings is not writable.";
   chmod("settings.php",0755); $Error="Yes";
   chmod("settingsbak.php",0755);
} else { /* settings.php is writable install iDB. ^_^ */ }
if(!function_exists("mysql_connect")&&!function_exists("mysqli_connect")&&
!function_exists("pg_connect")&&!function_exists("sqlite_open")&&
!function_exists("cubrid_connect")) { $Error="Yes";
echo "<span class=\"TableMessage\">You need to enbale a database php extension to install ".$VerInfo['iDB_Ver_Show']." on this server.<br />\n"; 
echo "You can use MySQL, MySQLi, PostgreSQL, or SQLite</span>"; }
if ($Error!="Yes") {
$StatSQL = sql_connect_db($_POST['DatabaseHost'],$_POST['DatabaseUserName'],$_POST['DatabasePassword']);
if(!$StatSQL) { $Error="Yes";
echo "<span class=\"TableMessage\">";
echo "<br />".sql_errorno($StatSQL)."\n</span>\n"; } }
if ($Error!="Yes") {
$iDBRDate = $SVNDay[0]."/".$SVNDay[1]."/".$SVNDay[2];
$iDBRSVN = $VER2[2]." ".$SubVerN;
$LastUpdateS = "Last Update: ".$iDBRDate." ".$iDBRSVN;
$pretext = "<?php\n/*\n    This program is free software; you can redistribute it and/or modify\n    it under the terms of the GNU General Public License as published by\n    the Free Software Foundation; either version 2 of the License, or\n    (at your option) any later version.\n\n    This program is distributed in the hope that it will be useful,\n    but WITHOUT ANY WARRANTY; without even the implied warranty of\n    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n    Revised BSD License for more details.\n\n    Copyright 2004-".$SVNDay[2]." iDB Support - http://idb.berlios.de/\n    Copyright 2004-".$SVNDay[2]." Game Maker 2k - http://gamemaker2k.org/\n    iDB Installer made by Game Maker 2k - http://idb.berlios.net/\n\n    \$FileInfo: settings.php & settingsbak.php - ".$LastUpdateS." - Author: cooldude2k \$\n*/\n";
$BoardSettings=$pretext."\$Settings = array();\n\$Settings['sqlhost'] = '".$_POST['DatabaseHost']."';\n\$Settings['sqluser'] = '".$_POST['DatabaseUserName']."';\n\$Settings['sqlpass'] = '".$_POST['DatabasePassword']."';\n?>";
$fp = fopen("./settings.php","w+");
fwrite($fp, $BoardSettings);
fclose($fp);
//	cp("settings.php","settingsbak.php");
$fp = fopen("./settingsbak.php","w+");
fwrite($fp, $BoardSettings);
fclose($fp);
// http://www.tutorialspoint.com/php/php_function_timezone_identifiers_list.htm
$timezone_identifiers = DateTimeZone::listIdentifiers();
//$timezone_identifiers = timezone_identifiers_list();
$zonelist['africa'] = array();
$zonelist['america'] = array();
$zonelist['antarctica'] = array();
$zonelist['asia'] = array();
$zonelist['atlantic'] = array();
$zonelist['australia'] = array();
$zonelist['europe'] = array();
$zonelist['indian'] = array();
$zonelist['pacific'] = array();
$zonelist['etcetera'] = array();
for ($i=0; $i < count($timezone_identifiers); $i++) {
    $zonelookup = explode("/", $timezone_identifiers[$i]);
    if(count($zonelookup)==1) { array_push($zonelist['etcetera'], array($timezone_identifiers[$i], $timezone_identifiers[$i])); }
    if(count($zonelookup)>1) { 
        if($zonelookup[0]=="Africa") {
            if(count($zonelookup)==2) {
                array_push($zonelist['africa'], array($zonelookup[1], $timezone_identifiers[$i])); }
            if(count($zonelookup)==3) {
                array_push($zonelist['africa'], array($zonelookup[2].", ".$zonelookup[1], $timezone_identifiers[$i])); } }
        if($zonelookup[0]=="America") {
            if(count($zonelookup)==2) {
                array_push($zonelist['america'], array($zonelookup[1], $timezone_identifiers[$i])); }
            if(count($zonelookup)==3) {
                array_push($zonelist['america'], array($zonelookup[2].", ".$zonelookup[1], $timezone_identifiers[$i])); } }
        if($zonelookup[0]=="Antarctica") {
            if(count($zonelookup)==2) {
                array_push($zonelist['antarctica'], array($zonelookup[1], $timezone_identifiers[$i])); }
            if(count($zonelookup)==3) {
                array_push($zonelist['antarctica'], array($zonelookup[2].", ".$zonelookup[1], $timezone_identifiers[$i])); } }
        if($zonelookup[0]=="Asia") {
            if(count($zonelookup)==2) {
                array_push($zonelist['asia'], array($zonelookup[1], $timezone_identifiers[$i])); }
            if(count($zonelookup)==3) {
                array_push($zonelist['asia'], array($zonelookup[2].", ".$zonelookup[1], $timezone_identifiers[$i])); } }
        if($zonelookup[0]=="Atlantic") {
            if(count($zonelookup)==2) {
                array_push($zonelist['atlantic'], array($zonelookup[1], $timezone_identifiers[$i])); }
            if(count($zonelookup)==3) {
                array_push($zonelist['atlantic'], array($zonelookup[2].", ".$zonelookup[1], $timezone_identifiers[$i])); } }
        if($zonelookup[0]=="Australia") {
            if(count($zonelookup)==2) {
                array_push($zonelist['australia'], array($zonelookup[1], $timezone_identifiers[$i])); }
            if(count($zonelookup)==3) {
                array_push($zonelist['australia'], array($zonelookup[2].", ".$zonelookup[1], $timezone_identifiers[$i])); } }
        if($zonelookup[0]=="Europe") {
            if(count($zonelookup)==2) {
                array_push($zonelist['europe'], array($zonelookup[1], $timezone_identifiers[$i])); }
            if(count($zonelookup)==3) {
                array_push($zonelist['europe'], array($zonelookup[2].", ".$zonelookup[1], $timezone_identifiers[$i])); } }
        if($zonelookup[0]=="Indian") {
            if(count($zonelookup)==2) {
                array_push($zonelist['indian'], array($zonelookup[1], $timezone_identifiers[$i])); }
            if(count($zonelookup)==3) {
                array_push($zonelist['indian'], array($zonelookup[2].", ".$zonelookup[1], $timezone_identifiers[$i])); } }
        if($zonelookup[0]=="Pacific") {
            if(count($zonelookup)==2) {
                array_push($zonelist['pacific'], array($zonelookup[1], $timezone_identifiers[$i])); }
            if(count($zonelookup)==3) {
                array_push($zonelist['pacific'], array($zonelookup[2].", ".$zonelookup[1], $timezone_identifiers[$i])); } }
    }
}
?>
<form style="display: inline;" method="post" id="install" action="install.php?act=Part4">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NewBoardName">Insert Board Name:</label></td>
	<td style="width: 50%;"><input type="text" name="NewBoardName" class="TextBox" id="NewBoardName" size="20" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="DatabaseName">Insert Database Name:</label></td>
	<td style="width: 50%;"><input type="text" name="DatabaseName" class="TextBox" id="DatabaseName" size="20" />
	<?php /*<select id="dblist" name="dblist" class="TextBox" onchange="document.install.DatabaseName.value=this.value">
	<option value=" ">none on list</option>
	<?php $dblist = sql_list_dbs();
	$num = count($dblist); $i = 0;
	while ($i < $num) {
		echo "<option value=\"".$dblist[$i]."\">";
		echo $dblist[$i]."</option>\n";
		++$i;
	} ?></select><?php */ ?></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="tableprefix">Insert Table Prefix:<br /></label></td>
	<td style="width: 50%;"><input type="text" name="tableprefix" class="TextBox" id="tableprefix" value="idb_" size="20" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="AdminUser">Insert Admin User Name:</label></td>
	<td style="width: 50%;"><input type="text" name="AdminUser" class="TextBox" id="AdminUser" size="20" /></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="AdminEmail">Insert Admin Email:</label></td>
	<td style="width: 70%;"><input type="text" class="TextBox" name="AdminEmail" size="20" id="AdminEmail" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="AdminPassword">Insert Admin Password:</label></td>
	<td style="width: 50%;"><input type="password" name="AdminPasswords" class="TextBox" id="AdminPassword" size="20" maxlength="30" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="ReaPassword">ReInsert Admin Password:</label></td>
	<td style="width: 50%;"><input type="password" class="TextBox" name="ReaPassword" size="20" id="ReaPassword" maxlength="30" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="BoardURL">Insert The Board URL:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="BoardURL" size="20" id="BoardURL" value="<?php echo $prehost.$_SERVER['HTTP_HOST'].$this_dir; ?>" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="WebURL">Insert The WebSite URL:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="WebURL" size="20" id="WebURL" value="<?php echo $prehost.$_SERVER['HTTP_HOST']."/"; ?>" /></td>
</tr><?php if($_POST['DatabaseType']=="mysql"||$_POST['DatabaseType']=="mysqli") { ?><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="sqlcollate">MySQL Collate:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="sqlcollate" id="sqlcollate">
	<?php if($_POST['charset']=="ISO-8859-1"||$_POST['charset']=="ISO-8859-15") { ?>
	<option value="latin1_general_ci">Latin1 Case-Insensitive</option>
	<option value="latin1_general_cs">Latin1 Case-Sensitive</option>
	<option value="latin1_bin">Latin1 Binary</option>
	<option value="ascii_generel_ci">ASCII Case-Insensitive</option>
	<option value="ascii_bin">ASICC Binary</option>
	<?php } if($_POST['charset']=="UTF-8") { ?>
	<option value="utf8_unicode_ci">UTF-8 Unicode Case-Insensitive</option>
	<option value="utf8_general_ci">UTF-8 General Case-Insensitive</option>
	<option value="utf8_bin">UTF-8 Binary</option>
	<option value="utf8mb4_unicode_ci">UTF-8 Multibyte Unicode Case-Insensitive</option>
	<option value="utf8mb4_general_ci">UTF-8 Multibyte General Case-Insensitive</option>
	<option value="utf8mb4_bin">UTF-8 Multibyte Binary</option>
	<?php } ?>
	</select></td>
</tr><?php } ?><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" title="Can save some bandwidth." for="UseGzip">Enable HTTP Compression:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="GZip" id="UseGzip">
	<option value="off">No</option>
	<option value="on">Yes</option>
	<option value="gzip">Only GZip</option>
	<option value="deflate">Only Deflate</option>
	</select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="HTMLType">HTML Type to use:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="HTMLType" id="HTMLType">
	<!--<option value="xhtml10">XHTML 1.0</option>-->
	<!--<option value="xhtml11">XHTML 1.1</option>-->
	<option value="html5">HTML 5</option>
	<option value="xhtml5">XHTML 5</option>
	</select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="OutPutType">Output file as:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="OutPutType" id="OutPutType">
	<option value="html">HTML</option>
	<option value="xhtml">XHTML</option>
	</select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" title="Store userinfo as a cookie so you dont need to login again." for="storecookie">Store as cookie?</label></td>
	<td style="width: 50%;"><select id="storecookie" name="storecookie" class="TextBox">
<option value="true">Yes</option>
<option value="false">No</option>
</select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="usehashtype">Hash user passwords with?</label></td>
	<td style="width: 50%;"><select id="usehashtype" name="usehashtype" class="TextBox">
<?php // PHP 5 hash algorithms to functions :o 
if(function_exists('hash')&&function_exists('hash_algos')) {
if(in_array("md2",hash_algos())) { ?>
<option value="md2">MD2</option>
<?php } if(in_array("md4",hash_algos())) { ?>
<option value="md4">MD4</option>
<?php } if(in_array("md5",hash_algos())) { ?>
<option value="md5">MD5</option>
<?php } if(in_array("gost",hash_algos())) { ?>
<option value="gost">GOST</option>
<?php } if(in_array("joaat",hash_algos())) { ?>
<option value="joaat">JOAAT</option>
<?php } if(in_array("sha1",hash_algos())) { ?>
<option value="sha1">SHA1</option>
<?php } if(in_array("sha224",hash_algos())) { ?>
<option value="sha224">SHA224</option>
<?php } if(in_array("sha256",hash_algos())) { ?>
<option value="sha256" selected="selected">SHA256</option>
<?php } if(in_array("sha384",hash_algos())) { ?>
<option value="sha384">SHA384</option>
<?php } if(in_array("sha512",hash_algos())) { ?>
<option value="sha512">SHA512</option>
<?php } if(in_array("ripemd128",hash_algos())) { ?>
<option value="ripemd128">RIPEMD128</option>
<?php } if(in_array("ripemd160",hash_algos())) { ?>
<option value="ripemd160">RIPEMD160</option>
<?php } if(in_array("ripemd256",hash_algos())) { ?>
<option value="ripemd256">RIPEMD256</option>
<?php } if(in_array("ripemd320",hash_algos())) { ?>
<option value="ripemd320">RIPEMD320</option>
<?php } } 
if(!function_exists('hash')&&!function_exists('hash_algos')) { ?>
<option value="md5">MD5</option>
<option value="sha1" selected="selected">SHA1</option>
<?php } ?>
</select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="YourOffSet">Your TimeZone:</label></td>
	<td style="width: 50%;"><select id="YourOffSet" name="YourOffSet" class="TextBox">
<optgroup label="Africa">
<?php
$optsel="";
for ($i=0; $i < count($zonelist['africa']); $i++) {
    if(date_default_timezone_get()==$zonelist['africa'][$i][1]) { $optsel = " selected=\"selected\""; }
    echo "<option".$optsel." value=\"".$zonelist['africa'][$i][1]."\">".str_replace("_", " ", $zonelist['africa'][$i][0])."</option>\n"; 
    $optsel=""; }
?>
</optgroup>
<optgroup label="America">
<?php
$optsel="";
for ($i=0; $i < count($zonelist['america']); $i++) {
    if(date_default_timezone_get()==$zonelist['america'][$i][1]) { $optsel = " selected=\"selected\""; }
    echo "<option".$optsel." value=\"".$zonelist['america'][$i][1]."\">".str_replace("_", " ", $zonelist['america'][$i][0])."</option>\n"; 
    $optsel=""; }
?>
</optgroup>
<optgroup label="Antarctica">
<?php
$optsel="";
for ($i=0; $i < count($zonelist['antarctica']); $i++) {
    if(date_default_timezone_get()==$zonelist['antarctica'][$i][1]) { $optsel = " selected=\"selected\""; }
    echo "<option".$optsel." value=\"".$zonelist['antarctica'][$i][1]."\">".str_replace("_", " ", $zonelist['antarctica'][$i][0])."</option>\n"; 
    $optsel=""; }
?>
</optgroup>
<optgroup label="Asia">
<?php
for ($i=0; $i < count($zonelist['asia']); $i++) {
    if(date_default_timezone_get()==$zonelist['asia'][$i][1]) { $optsel = " selected=\"selected\""; }
    echo "<option".$optsel." value=\"".$zonelist['asia'][$i][1]."\">".str_replace("_", " ", $zonelist['asia'][$i][0])."</option>\n"; 
    $optsel=""; }
?>
</optgroup>
<optgroup label="Atlantic">
<?php
$optsel="";
for ($i=0; $i < count($zonelist['atlantic']); $i++) {
    if(date_default_timezone_get()==$zonelist['atlantic'][$i][1]) { $optsel = " selected=\"selected\""; }
    echo "<option".$optsel." value=\"".$zonelist['atlantic'][$i][1]."\">".str_replace("_", " ", $zonelist['atlantic'][$i][0])."</option>\n"; 
    $optsel=""; }
?>
</optgroup>
<optgroup label="Australia">
<?php
$optsel="";
for ($i=0; $i < count($zonelist['australia']); $i++) {
    if(date_default_timezone_get()==$zonelist['australia'][$i][1]) { $optsel = " selected=\"selected\""; }
    echo "<option".$optsel." value=\"".$zonelist['australia'][$i][1]."\">".str_replace("_", " ", $zonelist['australia'][$i][0])."</option>\n"; 
    $optsel=""; }
?>
</optgroup>
<optgroup label="Europe">
<?php
$optsel="";
for ($i=0; $i < count($zonelist['europe']); $i++) {
    if(date_default_timezone_get()==$zonelist['europe'][$i][1]) { $optsel = " selected=\"selected\""; }
    echo "<option".$optsel." value=\"".$zonelist['europe'][$i][1]."\">".str_replace("_", " ", $zonelist['europe'][$i][0])."</option>\n"; 
    $optsel=""; }
?>
</optgroup>
<optgroup label="Indian">
<?php
$optsel="";
for ($i=0; $i < count($zonelist['indian']); $i++) {
    if(date_default_timezone_get()==$zonelist['indian'][$i][1]) { $optsel = " selected=\"selected\""; }
    echo "<option".$optsel." value=\"".$zonelist['indian'][$i][1]."\">".str_replace("_", " ", $zonelist['indian'][$i][0])."</option>\n"; 
    $optsel=""; }
?>
</optgroup>
<optgroup label="Pacific">
<?php
$optsel="";
for ($i=0; $i < count($zonelist['pacific']); $i++) {
    if(date_default_timezone_get()==$zonelist['pacific'][$i][1]) { $optsel = " selected=\"selected\""; }
    echo "<option".$optsel." value=\"".$zonelist['pacific'][$i][1]."\">".str_replace("_", " ", $zonelist['pacific'][$i][0])."</option>\n"; 
    $optsel=""; }
?>
</optgroup>
<optgroup label="Etcetera">
<?php
$optsel="";
for ($i=0; $i < count($zonelist['etcetera']); $i++) {
    if(date_default_timezone_get()==$zonelist['etcetera'][$i][1]) { $optsel = " selected=\"selected\""; }
    echo "<option".$optsel." value=\"".$zonelist['etcetera'][$i][1]."\">".str_replace("_", " ", $zonelist['etcetera'][$i][0])."</option>\n"; 
    $optsel=""; }
?>
</optgroup>
</select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="iDBTimeFormat">Insert time format string:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="iDBTimeFormat" size="20" id="iDBTimeFormat" value="<?php echo "g:i A"; ?>" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="iDBDateFormat">Insert date format string:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="iDBDateFormat" size="20" id="iDBDateFormat" value="<?php echo "F j Y"; ?>" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="TestReferer">Test Referering URL:</label></td>
	<td style="width: 50%;"><select id="TestReferer" name="TestReferer" class="TextBox">
<option selected="selected" value="off">off</option>
<option value="on">on</option>
</select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="iDBHTTPLogger">Log Every HTTP Requests:</label></td>
	<td style="width: 50%;"><select id="iDBHTTPLogger" name="iDBHTTPLogger" class="TextBox">
<option value="off">off</option>
<option value="on">on</option>
</select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="iDBLoggerFormat">Insert The Format for HTTP Logger:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="iDBLoggerFormat" size="20" id="iDBLoggerFormat" value="<?php echo "%h %l %u %t &quot;%r&quot; %&gt;s %b &quot;%{Referer}i&quot; &quot;%{User-Agent}i&quot;"; ?>" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="DefaultTheme">Default Theme</label></td>
	<td style="width: 50%;"><select id="DefaultTheme" name="DefaultTheme" class="TextBox"><?php
$skindir = dirname(realpath("settings.php"))."/".$SettDir['themes'];
if ($handle = opendir($skindir)) { $dirnum = null;
   while (false !== ($file = readdir($handle))) {
	   $selected = null;
	   if ($dirnum==null) { $dirnum = 0; }
	   if (file_exists($skindir.$file."/info.php")) {
		   if ($file != "." && $file != "..") {
	   include($skindir.$file."/info.php");
	   if($file=="iDB") { 
       $themelist[$dirnum] =  "<option value=\"".$file."\" selected=\"selected\">".$ThemeInfo['ThemeName']."</option>"; }
	   if($file!="iDB") {
	   $themelist[$dirnum] =  "<option value=\"".$file."\">".$ThemeInfo['ThemeName']."</option>"; }
	   ++$dirnum; } } }
   closedir($handle); asort($themelist);
   $themenum=count($themelist); $themei=0; 
   while ($themei < $themenum) {
   echo $themelist[$themei]."\n";
   ++$themei; }
} ?></select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="SQLThemes">Store Themes in SQL Database:</label></td>
	<td style="width: 50%;"><select id="SQLThemes" name="SQLThemes" class="TextBox">
<option selected="selected" value="off">off</option>
<option value="on">on</option>
</select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" title="Might not work" for="unlink">Delete Installer?</label></td>
	<td style="width: 50%;"><select id="unlink" name="unlink" class="TextBox">
<option value="true">Yes</option>
<option value="false">No</option>
</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="charset" value="<?php echo $_POST['charset']; ?>" style="display: none;" />
<input type="hidden" name="SetupType" value="install" style="display: none;" />
<input type="hidden" name="DatabaseType" value="<?php echo $Settings['sqltype']; ?>" style="display: none;" />
<input type="hidden" name="act" value="Part4" style="display: none;" />
<input type="submit" class="Button" value="Install Board" name="Install_Board" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<?php } ?>
