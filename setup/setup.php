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

    $FileInfo: setup.php - Last Update: 8/30/2024 SVN 1064 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "setup.php" || $File3Name == "/setup.php") {
    require('index.php');
    exit();
}
if (!isset($SetupDir['setup'])) {
    $SetupDir['setup'] = "setup/";
}
if (!isset($SetupDir['convert'])) {
    $SetupDir['convert'] = "setup/convert/";
}
?>
<tr class="TableRow3">
<td class="TableColumn3">
<?php
$checkfile = "settings.php";
$iDBRDate = $SVNDay[0]."/".$SVNDay[1]."/".$SVNDay[2];
$iDBRSVN = $VER2[2]." ".$SubVerN;
$LastUpdateS = "Last Update: ".$iDBRDate." ".$iDBRSVN;
$pretext = "<?php\n/*\n    This program is free software; you can redistribute it and/or modify\n    it under the terms of the GNU General Public License as published by\n    the Free Software Foundation; either version 2 of the License, or\n    (at your option) any later version.\n\n    This program is distributed in the hope that it will be useful,\n    but WITHOUT ANY WARRANTY; without even the implied warranty of\n    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n    Revised BSD License for more details.\n\n    Copyright 2004-".$SVNDay[2]." iDB Support - https://idb.osdn.jp/support/category.php?act=view&id=1\n    Copyright 2004-".$SVNDay[2]." Game Maker 2k - https://idb.osdn.jp/support/category.php?act=view&id=2\n    iDB Installer made by Game Maker 2k - http://idb.berlios.net/\n\n    \$FileInfo: settings.php & settingsbak.php - ".$LastUpdateS." - Author: cooldude2k \$\n*/\n";
$pretext2 = array("/*   Board Setting Section Begins   */\n\$Settings = array();","/*   Board Setting Section Ends  \n     Board Info Section Begins   */\n\$SettInfo = array();","/*   Board Setting Section Ends   \n     Board Dir Section Begins   */\n\$SettDir = array();","/*   Board Dir Section Ends   */");
$settcheck = "\$File3Name = basename(\$_SERVER['SCRIPT_NAME']);\nif (\$File3Name==\"settings.php\"||\$File3Name==\"/settings.php\"||\n    \$File3Name==\"settingsbak.php\"||\$File3Name==\"/settingsbak.php\") {\n    header('Location: index.php');\n    exit(); }\n";
$BoardSettingsBak = $pretext.$settcheck;
$BoardSettings = $pretext.$settcheck;
$fp = fopen("settings.php", "w+");
fwrite($fp, $BoardSettings);
fclose($fp);
//	cp("settings.php","settingsbak.php");
$fp = fopen("settingsbak.php", "w+");
fwrite($fp, $BoardSettingsBak);
fclose($fp);
if (!is_writable($checkfile)) {
    echo "<br />Settings is not writable.";
    chmod("settings.php", 0755);
    $Error = "Yes";
    chmod("settingsbak.php", 0755);
} else { /* settings.php is writable install iDB. ^_^ */
}
if (!function_exists("mysqli_func_connect_db") &&
           !function_exists("pgsql_func_connect_db") &&
           !function_exists("sqlite3_func_connect_db") &&
           !function_exists("cubrid_prepare_func_connect_db") &&
           !function_exists("mysqli_prepare_func_connect_db") &&
           !function_exists("pgsql_prepare_func_connect_db") &&
           !function_exists("sqlite3_prepare_func_connect_db") &&
           !function_exists("sqlsrv_prepare_func_connect_db") &&
           !function_exists("pdo_cubrid_func_connect_db") &&
           !function_exists("pdo_mysql_func_connect_db") &&
           !function_exists("pdo_pgsql_func_connect_db") &&
           !function_exists("pdo_sqlite3_func_connect_db") &&
           !function_exists("pdo_sqlsrv_func_connect_db")) {
    $Error = "Yes";
    echo "<span class=\"TableMessage\">You need to enbale a database php extension to install ".$VerInfo['iDB_Ver_Show']." on this server.<br />\n";
    echo "You can use MySQL, MySQLi, PostgreSQL, CUBRID, or SQLite 3</span>";
}
if ($Error != "Yes") {
    $StatSQL = sql_connect_db($_POST['DatabaseHost'], $_POST['DatabaseUserName'], $_POST['DatabasePassword']);
    if (!$StatSQL) {
        $Error = "Yes";
        echo "<span class=\"TableMessage\">";
        echo "<br />".sql_errorno($StatSQL)."\n</span>\n";
    }
}
if ($Error != "Yes") {
    $iDBRDate = $SVNDay[0]."/".$SVNDay[1]."/".$SVNDay[2];
    $iDBRSVN = $VER2[2]." ".$SubVerN;
    $LastUpdateS = "Last Update: ".$iDBRDate." ".$iDBRSVN;
    $pretext = "<?php\n/*\n    This program is free software; you can redistribute it and/or modify\n    it under the terms of the GNU General Public License as published by\n    the Free Software Foundation; either version 2 of the License, or\n    (at your option) any later version.\n\n    This program is distributed in the hope that it will be useful,\n    but WITHOUT ANY WARRANTY; without even the implied warranty of\n    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n    Revised BSD License for more details.\n\n    Copyright 2004-".$SVNDay[2]." iDB Support - https://idb.osdn.jp/support/category.php?act=view&id=1\n    Copyright 2004-".$SVNDay[2]." Game Maker 2k - https://idb.osdn.jp/support/category.php?act=view&id=2\n    iDB Installer made by Game Maker 2k - http://idb.berlios.net/\n\n    \$FileInfo: settings.php & settingsbak.php - ".$LastUpdateS." - Author: cooldude2k \$\n*/\n";
    $BoardSettings = $pretext."\$Settings = array();\n\$Settings['sqlhost'] = '".$_POST['DatabaseHost']."';\n\$Settings['sqluser'] = '".$_POST['DatabaseUserName']."';\n\$Settings['sqlpass'] = '".$_POST['DatabasePassword']."';\n?>";
    $fp = fopen("./settings.php", "w+");
    fwrite($fp, $BoardSettings);
    fclose($fp);
    //	cp("settings.php","settingsbak.php");
    $fp = fopen("./settingsbak.php", "w+");
    fwrite($fp, $BoardSettings);
    fclose($fp);
    ?>
<form style="display: inline;" method="post" id="install" action="<?php echo url_maker("install", ".php", "act=part4", "&", "=", null, null); ?>">
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
	<td style="width: 50%;"><label class="TextBoxLabel" for="AdminHandle">Insert Admin User Handel:</label></td>
	<td style="width: 50%;"><input type="text" name="AdminHandle" pattern="[a-zA-Z0-9_]{3,20}" class="TextBox" id="AdminHandle" size="20" /></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="AdminEmail">Insert Admin Email:</label></td>
	<td style="width: 70%;"><input type="email" class="TextBox" name="AdminEmail" size="20" id="AdminEmail" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="AdminPassword">Insert Admin Password:</label></td>
	<td style="width: 50%;"><input type="password" name="AdminPasswords" class="TextBox" id="AdminPassword" size="20" maxlength="30" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="ReaPassword">ReInsert Admin Password:</label></td>
	<td style="width: 50%;"><input type="password" class="TextBox" name="ReaPassword" size="20" id="ReaPassword" maxlength="30" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="BoardURL">Insert The Board URL:</label></td>
	<td style="width: 50%;"><input type="url" class="TextBox" name="BoardURL" size="20" id="BoardURL" value="<?php echo $prehost.$_SERVER['HTTP_HOST'].str_replace('%2F', '/', rawurlencode($this_dir)); ?>" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="WebURL">Insert The WebSite URL:</label></td>
	<td style="width: 50%;"><input type="url" class="TextBox" name="WebURL" size="20" id="WebURL" value="<?php echo $prehost.$_SERVER['HTTP_HOST']."/"; ?>" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="startblank">Start blank:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="startblank" id="startblank">
	<option value="no">No</option>
	<option value="yes">Yes</option>
	</select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="testdata">Start with test data:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="testdata" id="testdata">
	<option value="yes">Yes</option>
	<option value="no">No</option>
	</select></td>
</tr><?php if ($_POST['DatabaseType'] == "pdo_mysql" || $_POST['DatabaseType'] == "mysqli" || $_POST['DatabaseType'] == "mysqli_prepare") { ?><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="sqlcollate">MySQL Collate:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="sqlcollate" id="sqlcollate">
	<?php if ($_POST['charset'] == "ISO-8859-1" || $_POST['charset'] == "ISO-8859-15") { ?>
	<option value="latin1_general_ci">Latin1 Case-Insensitive</option>
	<option value="latin1_general_cs">Latin1 Case-Sensitive</option>
	<option value="latin1_bin">Latin1 Binary</option>
	<option value="ascii_generel_ci">ASCII Case-Insensitive</option>
	<option value="ascii_bin">ASICC Binary</option>
	<?php } if ($_POST['charset'] == "UTF-8") { ?>
	<option value="utf8mb3_unicode_ci">UTF-8 Unicode Case-Insensitive</option>
	<option value="utf8mb3_general_ci">UTF-8 General Case-Insensitive</option>
	<option value="utf8mb3_bin">UTF-8 Binary</option>
	<option value="utf8mb4_unicode_ci">UTF-8 Multibyte Unicode Case-Insensitive</option>
	<option value="utf8mb4_general_ci" selected="selected">UTF-8 Multibyte General Case-Insensitive</option>
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
    <?php if (function_exists('brotli_compress')) { ?>
	<option value="brotli">Only Brotli</option>
    <?php } if (function_exists('brotli_compress')) { ?>
	<option value="zstd">Only Zstandard</option>
    <?php } ?>
	</select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="HTMLType">HTML Type to use:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="HTMLType" id="HTMLType">
	<!--<option value="xhtml10">XHTML 1.0</option>-->
	<!--<option value="xhtml11">XHTML 1.1</option>-->
	<option value="html5">HTML 5</option>
	<option value="xhtml5">XHTML 5</option>
	</select></td>
</tr><!--<tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="OutPutType">Output file as:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="OutPutType" id="OutPutType">
	<option value="html">HTML</option>
	<option value="xhtml">XHTML</option>
	</select></td>
</tr>--><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" title="Store userinfo as a cookie so you dont need to login again." for="storecookie">Store as cookie?</label></td>
	<td style="width: 50%;"><select id="storecookie" name="storecookie" class="TextBox">
<option value="true">Yes</option>
<option value="false">No</option>
</select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="usehashtype">Hash user passwords with?</label></td>
	<td style="width: 50%;"><select id="usehashtype" name="usehashtype" class="TextBox">
<?php // PHP 5 hash algorithms to functions :o
    if (function_exists('hash') && function_exists('hash_algos')) {
        if (in_array("md2", hash_algos())) { ?>
<option value="md2">MD2</option>
<?php } if (in_array("md4", hash_algos())) { ?>
<option value="md4">MD4</option>
<?php } if (in_array("md5", hash_algos())) { ?>
<option value="md5">MD5</option>
<?php } if (in_array("gost", hash_algos())) { ?>
<option value="gost">GOST</option>
<?php } if (in_array("joaat", hash_algos())) { ?>
<option value="joaat">JOAAT</option>
<?php } if (in_array("sha1", hash_algos())) { ?>
<option value="sha1">SHA1</option>
<?php } if (in_array("sha224", hash_algos())) { ?>
<option value="sha224">SHA224</option>
<?php } if (in_array("sha256", hash_algos())) { ?>
<option value="sha256" selected="selected">SHA256</option>
<?php } if (in_array("sha384", hash_algos())) { ?>
<option value="sha384">SHA384</option>
<?php } if (in_array("sha512", hash_algos())) { ?>
<option value="sha512">SHA512</option>
<?php } if (in_array("sha3-224", hash_algos())) { ?>
<option value="sha3-224">SHA3-224</option>
<?php } if (in_array("sha3-256", hash_algos())) { ?>
<option value="sha3-256">SHA3-256</option>
<?php } if (in_array("sha3-384", hash_algos())) { ?>
<option value="sha3-384">SHA3-384</option>
<?php } if (in_array("sha3-512", hash_algos())) { ?>
<option value="sha3-512">SHA3-512</option>
<?php } if (in_array("ripemd128", hash_algos())) { ?>
<option value="ripemd128">RIPEMD128</option>
<?php } if (in_array("ripemd160", hash_algos())) { ?>
<option value="ripemd160">RIPEMD160</option>
<?php } if (in_array("ripemd256", hash_algos())) { ?>
<option value="ripemd256">RIPEMD256</option>
<?php } if (in_array("ripemd320", hash_algos())) { ?>
<option value="ripemd320">RIPEMD320</option>
<?php }
} if (function_exists('password_hash') && defined('PASSWORD_BCRYPT')) { ?>
<option value="bcrypt">BCRYPT</option>
<?php } if (function_exists('password_hash') && defined('PASSWORD_ARGON2I')) { ?>
<option value="argon2i">ARGON2I</option>
<?php } if (function_exists('password_hash') && defined('PASSWORD_ARGON2ID')) { ?>
<option value="argon2id">ARGON2ID</option>
<?php }
if (!function_exists('hash') && !function_exists('hash_algos')) { ?>
<option value="md5">MD5</option>
<option value="sha1" selected="selected">SHA1</option>
<?php } ?>
</select></td>
</tr><tr>
    <td style="width: 50%;"><label class="TextBoxLabel" for="YourOffSet">Your TimeZone:</label></td>
    <td style="width: 50%;">
        <select id="YourOffSet" name="YourOffSet" class="TextBox">
            <?php
            // List of all region labels
            $regions = ['africa', 'america', 'antarctica', 'arctic', 'asia', 'atlantic', 'australia', 'europe', 'indian', 'pacific', 'etcetera'];

    // Generate optgroups and options for each region
    foreach ($regions as $region) {
        echo '<optgroup label="' . ucfirst($region) . '">' . "\n";
        echo generateOptions($region, $zonelist, $gettzinfofromjs); // Using $gettzinfofromjs for selected timezone
        echo '</optgroup>' . "\n";
    }
    ?>
        </select>
    </td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="iDBTimeFormat">Insert time format string:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="iDBTimeFormat" size="20" id="iDBTimeFormat" value="<?php echo "g:i A"; ?>" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="iDBDateFormat">Insert date format string:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="iDBDateFormat" size="20" id="iDBDateFormat" value="<?php echo "F j Y"; ?>" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="TestReferer">Test Referering URL:</label></td>
	<td style="width: 50%;"><select id="TestReferer" name="TestReferer" class="TextBox">
<option selected="selected" value="off">Off</option>
<option value="on">On</option>
</select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="iDBHTTPLogger">Log Every HTTP Requests:</label></td>
	<td style="width: 50%;"><select id="iDBHTTPLogger" name="iDBHTTPLogger" class="TextBox">
<option value="off">Off</option>
<option value="on">On</option>
</select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="iDBLoggerFormat">Insert The Format for HTTP Logger:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="iDBLoggerFormat" size="20" id="iDBLoggerFormat" value="<?php echo "%h %l %u %t &quot;%r&quot; %&gt;s %b &quot;%{Referer}i&quot; &quot;%{User-Agent}i&quot;"; ?>" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="DefaultTheme">Default Theme</label></td>
	<td style="width: 50%;"><select id="DefaultTheme" name="DefaultTheme" class="TextBox"><?php
$skindir = dirname(realpath("settings.php"))."/".$SettDir['themes'];
    if ($handle = opendir($skindir)) {
        $dirnum = null;
        while (false !== ($file = readdir($handle))) {
            $selected = null;
            if ($dirnum == null) {
                $dirnum = 0;
            }
            if (file_exists($skindir.$file."/info.php")) {
                if ($file != "." && $file != "..") {
                    require($skindir.$file."/info.php");
                    if ($file == "iDB") {
                        $themelist[$dirnum] =  "<option value=\"".$file."\" selected=\"selected\">".$ThemeInfo['ThemeName']."</option>";
                    }
                    if ($file != "iDB") {
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
    } ?></select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="SQLThemes">Store Themes in SQL Database:</label></td>
	<td style="width: 50%;"><select id="SQLThemes" name="SQLThemes" class="TextBox">
<option selected="selected" value="off">Off</option>
<option value="on">On</option>
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
<input type="hidden" name="act" value="part4" style="display: none;" />
<input type="submit" class="Button" value="Install Board" name="Install_Board" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<?php } ?>
