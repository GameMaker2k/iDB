<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2009 iDB Support - http://idb.berlios.de/
    Copyright 2004-2009 Game Maker 2k - http://gamemaker2k.org/
    iDB Installer made by Game Maker 2k - http://idb.berlios.net/

    $FileInfo: setup.php - Last Update: 12/06/2009 SVN 379 - Author: cooldude2k $
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
if (!is_writable($checkfile)) {
   echo "<br />Settings is not writable.";
   chmod("settings.php",0755); $Error="Yes";
   chmod("settingsbak.php",0755);
} else { /* settings.php is writable install iDB. ^_^ */ }
$StatSQL = sql_connect_db($_POST['DatabaseHost'],$_POST['DatabaseUserName'],$_POST['DatabasePassword']);
if(!$StatSQL) { $Error="Yes";
echo "<span class=\"TableMessage\">";
echo "<br />".sql_errorno()."\n</span>\n"; }
if ($Error!="Yes") {
$pretext = "<?php\n/*\n    This program is free software; you can redistribute it and/or modify\n    it under the terms of the Revised BSD License.\n\n    This program is distributed in the hope that it will be useful,\n    but WITHOUT ANY WARRANTY; without even the implied warranty of\n    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n    Revised BSD License for more details.\n\n    Copyright 2004-2007 Cool Dude 2k - http://intdb.sourceforge.net/\n    Copyright 2004-2007 Game Maker 2k - http://idb.berlios.de/support/category.php?act=view&id=2\n    Emoticons made by Jcink http://tfbb.jcink.com/\n*/\n";
$BoardSettings=$pretext."\$Settings = array();\n\$Settings['sqlhost'] = '".$_POST['DatabaseHost']."';\n\$Settings['sqluser'] = '".$_POST['DatabaseUserName']."';\n\$Settings['sqlpass'] = '".$_POST['DatabasePassword']."';\n?>";
$fp = fopen("./settings.php","w+");
fwrite($fp, $BoardSettings);
fclose($fp);
//	cp("settings.php","settingsbak.php");
$fp = fopen("./settingsbak.php","w+");
fwrite($fp, $BoardSettings);
fclose($fp);
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
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" title="Can save some bandwidth." for="UseGzip">Do you want to HTTP Content Compression:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="GZip" id="UseGzip">
	<option value="off">No</option>
	<option value="on">Yes</option>
	<option value="gzip">Only GZip</option>
	<option value="deflate">Only Deflate</option>
	</select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="HTMLType">HTML Type to use:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="HTMLType" id="HTMLType">
	<option value="xhtml10">XHTML 1.0</option>
	<option value="xhtml11">XHTML 1.1</option>
	</select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="HTMLLevel">HTML level only for XHTML 1.0:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="HTMLLevel" id="HTMLLevel">
	<option value="Transitional">Transitional</option>
	<option value="Strict">Strict</option>
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
	<td style="width: 50%;"><label class="TextBoxLabel"" for="usehashtype">Hash user passwords with?</label></td>
	<td style="width: 50%;"><select id="usehashtype" name="usehashtype" class="TextBox">
<option value="sha256" selected="selected">SHA256</option>
<option value="sha1">SHA1</option>
<option value="md5">MD5</option>
<?php // PHP 5 hash algorithms to functions :o 
if(function_exists('hash')&&function_exists('hash_algos')) {
if(in_array("md2",hash_algos())) { ?>
<option value="md2">MD2</option>
<?php } if(in_array("md4",hash_algos())) { ?>
<option value="md4">MD4</option>
<?php } if(in_array("sha384",hash_algos())) { ?>
<option value="sha384">SHA386</option>
<?php } if(in_array("sha512",hash_algos())) { ?>
<option value="sha512">SHA512</option>
<?php } } ?>
</select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="YourOffSet">Your TimeZone:</label></td>
	<td style="width: 50%;"><select id="YourOffSet" name="YourOffSet" class="TextBox"><?php
if(date("I")!=1) { $myofftime = SeverOffSet(); $mydstime = "off"; }
if(date("I")==1) { $myofftime = SeverOffSet()-1; $mydstime = "on"; }
$plusi = 1; $minusi = 12;
$plusnum = 15; $minusnum = 0;
while ($minusi > $minusnum) {
if($myofftime==-$minusi) {
echo "<option selected=\"selected\" value=\"-".$minusi."\">GMT - ".$minusi.":00 hours</option>\n"; }
if($myofftime!=-$minusi) {
echo "<option value=\"-".$minusi."\">GMT - ".$minusi.":00 hours</option>\n"; }
--$minusi; }
if($myofftime==0) { ?>
<option selected="selected" value="0">GMT +/- 0:00 hours</option>
<?php } if($myofftime!=0) { ?>
<option value="0">GMT +/- 0:00 hours</option>
<?php }
while ($plusi < $plusnum) {
if($myofftime==$plusi) {
echo "<option selected=\"selected\" value=\"".$plusi."\">GMT + ".$plusi.":00 hours</option>\n"; }
if($myofftime!=$plusi) {
echo "<option value=\"".$plusi."\">GMT + ".$plusi.":00 hours</option>\n"; }
++$plusi; }
?></select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="MinOffSet">Minute OffSet:</label></td>
	<td style="width: 50%;"><select id="MinOffSet" name="MinOffSet" class="TextBox"><?php
$mini = 0; $minnum = 60;
while ($mini < $minnum) {
if(strlen($mini)==2) { $showmin = $mini; }
if(strlen($mini)==1) { $showmin = "0".$mini; }
if($mini==0) {
echo "\n<option selected=\"selected\" value=\"".$showmin."\">0:".$showmin." minutes</option>\n"; }
if($mini!=0) {
echo "<option value=\"".$showmin."\">0:".$showmin." minutes</option>\n"; }
++$mini; }
?></select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="DST">Is <span title="Daylight Savings Time">DST</span> / <span title="Summer Time">ST</span> on or off:</label></td>
	<td style="width: 50%;"><select id="DST" name="DST" class="TextBox"><?php echo "\n" ?>
<?php if($mydstime=="off"||$mydstime!="on") { ?>
<option selected="selected" value="off">off</option><?php echo "\n" ?><option value="on">on</option>
<?php } if($mydstime=="on") { ?>
<option selected="selected" value="on">on</option><?php echo "\n" ?><option value="off">off</option>
<?php } echo "\n" ?></select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="TestReferer">Test Referering URL with host name:</label></td>
	<td style="width: 50%;"><select id="TestReferer" name="TestReferer" class="TextBox">
<option selected="selected" value="off">off</option>
<option value="on">on</option>
</select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" title="Might not work" for="unlink">Delete Installer When Done? (Might not work)</label></td>
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
<input type="hidden" name="act" value="Part4" style="display: none;" />
<input type="submit" class="Button" value="Install Board" name="Install_Board" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<?php } ?>
