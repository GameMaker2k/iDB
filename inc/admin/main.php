<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2008 Cool Dude 2k - http://idb.berlios.de/
    Copyright 2004-2008 Game Maker 2k - http://intdb.sourceforge.net/

    $FileInfo: main.php - Last Update: 05/31/2008 SVN 164 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="main.php"||$File3Name=="/main.php") {
	require('index.php');
	exit(); }

// Check if we can goto admin cp
if($_SESSION['UserGroup']==$Settings['GuestGroup']||$GroupInfo['HasAdminCP']=="no") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if(!isset($_POST['update'])) { $_POST['update'] = null; }
if($_GET['act']=="mysql"&&$GroupInfo['ViewDBInfo']!="yes") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if(!isset($_POST['update'])) { $_POST['update'] = null; }
$pretext = "<?php\n/*\n    This program is free software; you can redistribute it and/or modify\n    it under the terms of the GNU General Public License as published by\n    the Free Software Foundation; either version 2 of the License, or\n    (at your option) any later version.\n\n    This program is distributed in the hope that it will be useful,\n    but WITHOUT ANY WARRANTY; without even the implied warranty of\n    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n    Revised BSD License for more details.\n\n    Copyright 2004-2008 Cool Dude 2k - http://idb.berlios.de/\n    Copyright 2004-2008 Game Maker 2k - http://intdb.sourceforge.net/\n    iDB Installer made by Game Maker 2k - http://idb.berlios.net/\n\n    \$FileInfo: settings.php & settingsbak.php - Last Update: ".$SVNDay[0]."/".$SVNDay[1]."/".$SVNDay[2]." SVN ".$SubVerN." - Author: cooldude2k \$\n*/\n";
$pretext2 = array("/*   Board Setting Section Begins   */\n\$Settings = array();","/*   Board Setting Section Ends  \n     Board Info Section Begins   */\n\$SettInfo = array();","/*   Board Setting Section Ends   \n     Board Dir Section Begins   */\n\$SettDir = array();","/*   Board Dir Section Ends   */");
$settcheck = "\$File3Name = basename(\$_SERVER['SCRIPT_NAME']);\nif (\$File3Name==\"settings.php\"||\$File3Name==\"/settings.php\"||\n    \$File3Name==\"settingsbak.php\"||\$File3Name==\"/settingsbak.php\") {\n    @header('Location: index.php');\n    exit(); }\n";
if(!isset($_POST['update'])) { $_POST['update'] = null; }
function bool_string($boolean) {
if(!is_bool($boolean)) {
return $boolean; }
if(is_bool($boolean)) { 
if($boolean==0||$boolean===false) { 
return "false"; }
if($boolean==1||$boolean===true) { 
return "true"; } } }
function rsq($string) {
$string = str_replace("'", "\'", $string);
return $string; }
?>
<table class="Table3">
<tr style="width: 100%; vertical-align: top;">
	<td style="width: 15%; vertical-align: top;">
<?php 
require($SettDir['admin'].'table.php'); 
?>
</td>
	<td style="width: 85%; vertical-align: top;">
<?php if($_POST['update']=="now"&&$_GET['act']!=null) {
$updateact = url_maker($exfile['profile'],$Settings['file_ext'],"act=".$_GET['act'],$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']);
$admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
@redirect("refresh",$basedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act'],$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],FALSE),"3");
?>
<div class="Table1Border">
<table class="Table1" style="width: 100%;">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act'],$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Updating Settings</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr id="ProfileTitle" class="TableRow2">
<th class="TableRow2">Updating Settings</th>
</tr>
<tr class="TableRow3" id="ProfileUpdate">
<td class="TableRow3">
<div style="text-align: center;">
<br />Settings have been updated <a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act'],$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">click here</a> to go back. ^_^<br />&nbsp;</div>
<?php } if($_GET['act']=="view"&&$_POST['update']!="now") {
$query = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i LIMIT 1", array($_SESSION['UserID']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
$i=0;
$YourID=mysql_result($result,$i,"id");
$Notes=mysql_result($result,$i,"Notes");
$noteact = url_maker($exfile['profile'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']);
$notepadact = $noteact; $profiletitle = " ".$ThemeSet['TitleDivider']." NotePad";
$admincptitle = " ".$ThemeSet['TitleDivider']." Admin CP";
?>
<div class="Table1Border">
<table class="Table1" style="width: 100%;">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo $noteact; ?>">NotePad</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr id="ProfileTitle" class="TableRow2">
<th class="TableRow2">NotePad</th>
</tr>
<tr class="TableRow3" id="NotePadRow">
<td class="TableRow3">
<form method="post" action="<?php echo $notepadact; ?>"><div style="text-align: center;">
<label class="TextBoxLabel" for="NotePad">Your NotePad</label><br />
<textarea class="TextBox" name="NotePad" id="NotePad" style="width: 75%; height: 128px;" rows="10" cols="84"><?php echo $Notes; ?></textarea>
<input type="hidden" name="act" value="view" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<br /><input type="submit" class="Button" value="Save" />&nbsp;<input class="Button" type="reset" />
</div></form></td>
</tr>
<tr id="ProfileEnd" class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if($_GET['act']=="settings"&&$_POST['update']!="now") {
require('settings.php'); $admincptitle = " ".$ThemeSet['TitleDivider']." Settings Manager";
$ts_array = explode(":",$Settings['DefaultTimeZone']);
if(count($ts_array)!=2) {
	if(!isset($ts_array[0])) { $ts_array[0] = "0"; }
	if(!isset($ts_array[1])) { $ts_array[1] = "00"; }
	$Settings['DefaultTimeZone'] = $ts_array[0].":".$ts_array[1]; }
if(!is_numeric($ts_array[0])) { $ts_array[0] = "0"; }
if($ts_array[0]>12) { $ts_array[0] = "12"; $Settings['DefaultTimeZone'] = $ts_array[0].":".$ts_array[1]; }
if($ts_array[0]<-12) { $ts_array[0] = "-12"; $Settings['DefaultTimeZone'] = $ts_array[0].":".$ts_array[1]; }
if(!is_numeric($ts_array[1])) { $ts_array[1] = "00"; }
if($ts_array[1]>59) { $ts_array[1] = "59"; $Settings['DefaultTimeZone'] = $ts_array[0].":".$ts_array[1]; }
if($ts_array[1]<0) { $ts_array[1] = "00"; $Settings['DefaultTimeZone'] = $ts_array[0].":".$ts_array[1]; }
$tsa = array("offset" => $Settings['DefaultTimeZone'], "hour" => $ts_array[0], "minute" => $ts_array[1]);
?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
&nbsp;<a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=settings",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Settings Manager</a></span>
<span style="float: right;">&nbsp;</span></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing Setting for iDB: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableRow3">
<td class="TableRow3">
<form style="display: inline;" method="post" name="install" id="install" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=settings",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="BoardURL">Insert The Board URL or localhost to use any url:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="BoardURL" size="20" id="BoardURL" value="<?php echo $Settings['idburl']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="WebURL">Insert The WebSite URL:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="WebURL" size="20" id="WebURL" value="<?php echo $Settings['weburl']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="GuestGroup">Insert The Guest Group:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="GuestGroup" size="20" id="GuestGroup" value="<?php echo $Settings['GuestGroup']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="MemberGroup">Insert The Member Group:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="MemberGroup" size="20" id="MemberGroup" value="<?php echo $Settings['MemberGroup']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ValidateGroup">Insert The Validate Group:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="ValidateGroup" size="20" id="ValidateGroup" value="<?php echo $Settings['ValidateGroup']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="AdminValidate">Do you want to validate new members:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="AdminValidate" id="AdminValidate">
	<option<?php if($Settings['AdminValidate']===false) { echo " selected=\"selected\""; } ?> value="false">no</option>
	<option<?php if($Settings['AdminValidate']===true) { echo " selected=\"selected\""; } ?> value="true">yes</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="max_posts">Max replies per page:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="max_posts" size="20" id="max_posts" value="<?php echo $Settings['max_posts']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="max_topics">Max topics per page:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="max_topics" size="20" id="max_topics" value="<?php echo $Settings['max_topics']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="max_memlist">Max members per page:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="max_memlist" size="20" id="max_memlist" value="<?php echo $Settings['max_memlist']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="max_pmlist">Max pms per page:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="max_pmlist" size="20" id="max_pmlist" value="<?php echo $Settings['max_pmlist']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="hot_topic_num">Number of replies for hot topic:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="hot_topic_num" size="20" id="hot_topic_num" value="<?php echo $Settings['hot_topic_num']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" title="Can save some bandwidth." for="UseGzip">Do you want to HTTP Content Compression:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="UseGzip" id="UseGzip">
	<option<?php if($Settings['use_gzip']=="off") { echo " selected=\"selected\""; } ?> value="off">No</option>
	<option<?php if($Settings['use_gzip']=="on") { echo " selected=\"selected\""; } ?> value="on">Yes</option>
	<option<?php if($Settings['use_gzip']=="gzip") { echo " selected=\"selected\""; } ?> value="gzip">Only GZip</option>
	<option<?php if($Settings['use_gzip']=="deflate") { echo " selected=\"selected\""; } ?> value="deflate">Only Deflate</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="HTMLType">HTML Type to use:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="HTMLType" id="HTMLType">
	<option<?php if($Settings['html_type']=="xhtml10") { echo " selected=\"selected\""; } ?> value="xhtml10">XHTML 1.0</option>
	<option<?php if($Settings['html_type']=="xhtml11") { echo " selected=\"selected\""; } ?> value="xhtml11">XHTML 1.1</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="HTMLLevel">HTML level only for XHTML 1.0:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="HTMLLevel" id="HTMLLevel">
	<option<?php if($Settings['html_level']=="Transitional") { echo " selected=\"selected\""; } ?> value="Transitional">Transitional</option>
	<option<?php if($Settings['html_level']=="Strict") { echo " selected=\"selected\""; } ?> value="Strict">Strict</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="OutPutType">Output file as:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="OutPutType" id="OutPutType">
	<option<?php if($Settings['output_type']=="html") { echo " selected=\"selected\""; } ?> value="html">HTML</option>
	<option<?php if($Settings['output_type']=="xhtml") { echo " selected=\"selected\""; } ?> value="xhtml">XHTML</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="YourOffSet">Your TimeZone:</label></td>
	<td style="width: 50%;"><select id="YourOffSet" name="YourOffSet" class="TextBox"><?php
$myofftime = $tsa['hour']; $mydstime = "off";
$plusi = 1; $minusi = 12;
$plusnum = 13; $minusnum = 0;
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
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="MinOffSet">Minute OffSet:</label></td>
	<td style="width: 50%;"><select id="MinOffSet" name="MinOffSet" class="TextBox"><?php
$mini = 0; $minnum = 60; $mymin = $tsa['minute'];
while ($mini < $minnum) {
if(strlen($mini)==2) { $showmin = $mini; }
if(strlen($mini)==1) { $showmin = "0".$mini; }
if($mini==$mymin) {
echo "\n<option selected=\"selected\" value=\"".$showmin."\">0:".$showmin." minutes</option>\n"; }
if($mini!=$mymin) {
echo "<option value=\"".$showmin."\">0:".$showmin." minutes</option>\n"; }
++$mini; }
?></select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DST">Is <span title="Daylight Savings Time">DST</span> / <span title="Summer Time">ST</span> on or off:</label></td>
	<td style="width: 50%;"><select id="DST" name="DST" class="TextBox"><?php echo "\n" ?>
<option<?php if($Settings['DefaultDST']=="off") { echo " selected=\"selected\""; } ?> value="off">off</option>
<option<?php if($Settings['DefaultDST']=="on") { echo " selected=\"selected\""; } ?> value="on">on</option>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DefaultTheme">Default CSS Theme for board:</label></td>
	<td style="width: 50%;"><select id="DefaultTheme" name="DefaultTheme" class="TextBox"><?php
$skindir = dirname(realpath("settings.php"))."/".$SettDir['themes'];
if ($handle = opendir($skindir)) { $dirnum = null;
   while (false !== ($file = readdir($handle))) {
	   if ($dirnum==null) { $dirnum = 0; }
	   if (file_exists($skindir.$file."/info.php")) {
		   if ($file != "." && $file != "..") {
	   include($skindir.$file."/info.php");
	   if($Settings['DefaultTheme']==$file) {
	   $themelist[$dirnum] =  "<option selected=\"selected\" value=\"".$file."\">".$ThemeInfo['ThemeName']."</option>"; }
	   if($Settings['DefaultTheme']!=$file) {
       $themelist[$dirnum] =  "<option value=\"".$file."\">".$ThemeInfo['ThemeName']."</option>"; }
	   ++$dirnum; } } }
   closedir($handle); asort($themelist);
   $themenum=count($themelist); $themei=0; 
   while ($themei < $themenum) {
   echo $themelist[$themei]."\n";
   ++$themei; }
} ?></select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="enable_https">Enable https:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="enable_https" id="enable_https">
	<option<?php if($Settings['enable_https']===true) { echo " selected=\"selected\""; } ?> value="true">on</option>
	<option<?php if($Settings['enable_https']===false) { echo " selected=\"selected\""; } ?> value="false">off</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="enable_rss">Enable RSS:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="enable_rss" id="enable_rss">
	<option<?php if($Settings['enable_rss']===true) { echo " selected=\"selected\""; } ?> value="true">on</option>
	<option<?php if($Settings['enable_rss']===false) { echo " selected=\"selected\""; } ?> value="false">off</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="enable_search">Enable search:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="enable_search" id="enable_search">
	<option<?php if($Settings['enable_search']===true) { echo " selected=\"selected\""; } ?> value="true">on</option>
	<option<?php if($Settings['enable_search']===false) { echo " selected=\"selected\""; } ?> value="false">off</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="TestReferer">Test Referering URL with host name:</label></td>
	<td style="width: 50%;"><select id="TestReferer" name="TestReferer" class="TextBox">
<option<?php if($Settings['TestReferer']===true) { echo " selected=\"selected\""; } ?> value="on">on</option>
<option<?php if($Settings['TestReferer']===false) { echo " selected=\"selected\""; } ?> value="off">off</option>
</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="settings" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Apply" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if($_POST['act']=="settings"&&$_POST['update']=="now"&&$_GET['act']=="settings"&&
	$_SESSION['UserGroup']!=$Settings['GuestGroup']&&$GroupInfo['HasAdminCP']=="yes") {
$Settings['fixpathinfo'] = bool_string($Settings['fixpathinfo']);
if($Settings['fixpathinfo']!="true"&&$Settings['fixpathinfo']!="false") {
   $Settings['fixpathinfo']="'".$Settings['fixpathinfo']."'"; }
$Settings['fixbasedir'] = bool_string($Settings['fixbasedir']);
if($Settings['fixbasedir']!="true"&&$Settings['fixbasedir']!="false") {
   $Settings['fixbasedir']="'".$Settings['fixbasedir']."'"; }
$Settings['fixcookiedir'] = bool_string($Settings['fixcookiedir']);
if($Settings['fixcookiedir']!="true"&&$Settings['fixcookiedir']!="false") {
   $Settings['fixcookiedir']="'".$Settings['fixcookiedir']."'"; }
$Settings['rssurl'] = bool_string($Settings['rssurl']);
if($Settings['rssurl']!="true"&&$Settings['rssurl']!="false") {
   $Settings['rssurl']="'".$Settings['rssurl']."'"; }
$Settings['showverinfo'] = bool_string($Settings['showverinfo']);
if($Settings['showverinfo']!="true"&&$Settings['showverinfo']!="false") {
   $Settings['showverinfo']="'".$Settings['showverinfo']."'"; }
$_POST  = array_map("rsq", $_POST);
$BoardSettings=$pretext2[0]."\n\$Settings['sqlhost'] = '".$Settings['sqlhost']."';\n\$Settings['sqldb'] = '".$Settings['sqldb']."';\n\$Settings['sqltable'] = '".$Settings['sqltable']."';\n\$Settings['sqluser'] = '".$Settings['sqluser']."';\n\$Settings['sqlpass'] = '".$Settings['sqlpass']."';\n\$Settings['board_name'] = '".$Settings['board_name']."';\n\$Settings['idbdir'] = '".$Settings['idbdir']."';\n\$Settings['idburl'] = '".$_POST['BoardURL']."';\n\$Settings['enable_https'] = ".bool_string($_POST['enable_https']).";\n\$Settings['weburl'] = '".$_POST['WebURL']."';\n\$Settings['use_gzip'] = '".$_POST['UseGzip']."';\n\$Settings['html_type'] = '".$_POST['HTMLType']."';\n\$Settings['html_level'] = '".$_POST['HTMLLevel']."';\n\$Settings['output_type'] = '".$_POST['OutPutType']."';\n\$Settings['GuestGroup'] = '".$_POST['GuestGroup']."';\n\$Settings['MemberGroup'] = '".$_POST['MemberGroup']."';\n\$Settings['ValidateGroup'] = '".$_POST['ValidateGroup']."';\n\$Settings['AdminValidate'] = ".bool_string($_POST['AdminValidate']).";\n\$Settings['TestReferer'] = '".$_POST['TestReferer']."';\n\$Settings['DefaultTheme'] = '".$_POST['DefaultTheme']."';\n\$Settings['DefaultTimeZone'] = '".$_POST['YourOffSet'].":".$_POST['MinOffSet']."';\n\$Settings['DefaultDST'] = '".$_POST['DST']."';\n\$Settings['charset'] = '".$Settings['charset']."';\n\$Settings['add_power_by'] = ".bool_string($Settings['add_power_by']).";\n\$Settings['send_pagesize'] = ".bool_string($Settings['send_pagesize']).";\n\$Settings['max_posts'] = '".$_POST['max_posts']."';\n\$Settings['max_topics'] = '".$_POST['max_topics']."';\n\$Settings['max_memlist'] = '".$_POST['max_memlist']."';\n\$Settings['max_pmlist'] = '".$_POST['max_pmlist']."';\n\$Settings['hot_topic_num'] = '".$_POST['hot_topic_num']."';\n\$Settings['qstr'] = '".$Settings['qstr']."';\n\$Settings['qsep'] = '".$Settings['qsep']."';\n\$Settings['file_ext'] = '".$Settings['file_ext']."';\n\$Settings['rss_ext'] = '".$Settings['rss_ext']."';\n\$Settings['js_ext'] = '".$Settings['js_ext']."';\n\$Settings['showverinfo'] = ".$Settings['showverinfo'].";\n\$Settings['enable_rss'] = ".bool_string($_POST['enable_rss']).";\n\$Settings['enable_search'] = ".bool_string($_POST['enable_search']).";\n\$Settings['sessionid_in_urls'] = ".bool_string($Settings['sessionid_in_urls']).";\n\$Settings['fixpathinfo'] = ".bool_string($Settings['fixpathinfo']).";\n\$Settings['fixbasedir'] = ".bool_string($Settings['fixbasedir']).";\n\$Settings['fixcookiedir'] = ".bool_string($Settings['fixcookiedir']).";\n\$Settings['enable_pathinfo'] = ".bool_string($Settings['enable_pathinfo']).";\n\$Settings['rssurl'] = ".bool_string($Settings['rssurl']).";\n\$Settings['board_offline'] = ".bool_string($Settings['board_offline']).";\n".$pretext2[1]."\n\$SettInfo['board_name'] = '".$SettInfo['board_name']."';\n\$SettInfo['Author'] = '".$SettInfo['Author']."';\n\$SettInfo['Keywords'] = '".$SettInfo['Keywords']."';\n\$SettInfo['Description'] = '".$SettInfo['Description']."';\n".$pretext2[2]."\n\$SettDir['maindir'] = '".$SettDir['maindir']."';\n\$SettDir['inc'] = '".$SettDir['inc']."';\n\$SettDir['misc'] = '".$SettDir['misc']."';\n\$SettDir['admin'] = '".$SettDir['admin']."';\n\$SettDir['mod'] = '".$SettDir['mod']."';\n\$SettDir['themes'] = '".$SettDir['themes']."';\n".$pretext2[3]."\n?>";
$BoardSettingsBak = $pretext.$settcheck.$BoardSettings;
$BoardSettings = $pretext.$settcheck.$BoardSettings;
$fp = fopen("settings.php","w+");
fwrite($fp, $BoardSettings);
fclose($fp);
//	@cp("settings.php","settingsbak.php");
$fp = fopen("settingsbak.php","w+");
fwrite($fp, $BoardSettingsBak);
fclose($fp); } if($_GET['act']=="mysql"&&$_POST['update']!="now"&&$GroupInfo['ViewDBInfo']=="yes") {
require('settings.php'); $admincptitle = " ".$ThemeSet['TitleDivider']." Database Manager";
?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
&nbsp;<a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=mysql",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Database Manager</a></span>
<span style="float: right;">&nbsp;</span></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing MySQL Settings for iDB: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableRow3">
<td class="TableRow3">
<form style="display: inline;" method="post" name="install" id="install" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=mysql",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
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
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="mysql" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Apply" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if($_POST['act']=="mysql"&&$_POST['update']=="now"&&$_GET['act']=="mysql"&&
	$_SESSION['UserGroup']!=$Settings['GuestGroup']&&$GroupInfo['HasAdminCP']=="yes"&&
	$GroupInfo['ViewDBInfo']=="yes") {
$Settings['fixpathinfo'] = bool_string($Settings['fixpathinfo']);
if($Settings['fixpathinfo']!="true"&&$Settings['fixpathinfo']!="false") {
   $Settings['fixpathinfo']="'".$Settings['fixpathinfo']."'"; }
$Settings['fixbasedir'] = bool_string($Settings['fixbasedir']);
if($Settings['fixbasedir']!="true"&&$Settings['fixbasedir']!="false") {
   $Settings['fixbasedir']="'".$Settings['fixbasedir']."'"; }
$Settings['fixcookiedir'] = bool_string($Settings['fixcookiedir']);
if($Settings['fixcookiedir']!="true"&&$Settings['fixcookiedir']!="false") {
   $Settings['fixcookiedir']="'".$Settings['fixcookiedir']."'"; }
$Settings['rssurl'] = bool_string($Settings['rssurl']);
if($Settings['rssurl']!="true"&&$Settings['rssurl']!="false") {
   $Settings['rssurl']="'".$Settings['rssurl']."'"; }
$Settings['showverinfo'] = bool_string($Settings['showverinfo']);
if($Settings['showverinfo']!="true"&&$Settings['showverinfo']!="false") {
   $Settings['showverinfo']="'".$Settings['showverinfo']."'"; }
$_POST  = array_map("rsq", $_POST);
$BoardSettings=$pretext2[0]."\n\$Settings['sqlhost'] = '".$_POST['DatabaseHost']."';\n\$Settings['sqldb'] = '".$_POST['DatabaseName']."';\n\$Settings['sqltable'] = '".$_POST['tableprefix']."';\n\$Settings['sqluser'] = '".$_POST['DatabaseUserName']."';\n\$Settings['sqlpass'] = '".$_POST['DatabasePassword']."';\n\$Settings['board_name'] = '".$Settings['board_name']."';\n\$Settings['idbdir'] = '".$Settings['idbdir']."';\n\$Settings['idburl'] = '".$Settings['idburl']."';\n\$Settings['enable_https'] = ".bool_string($Settings['enable_https']).";\n\$Settings['weburl'] = '".$Settings['weburl']."';\n\$Settings['use_gzip'] = '".$Settings['use_gzip']."';\n\$Settings['html_type'] = '".$Settings['html_type']."';\n\$Settings['html_level'] = '".$Settings['html_level']."';\n\$Settings['output_type'] = '".$Settings['output_type']."';\n\$Settings['GuestGroup'] = '".$Settings['GuestGroup']."';\n\$Settings['MemberGroup'] = '".$Settings['MemberGroup']."';\n\$Settings['ValidateGroup'] = '".$Settings['ValidateGroup']."';\n\$Settings['AdminValidate'] = ".bool_string($Settings['AdminValidate']).";\n\$Settings['TestReferer'] = '".$Settings['TestReferer']."';\n\$Settings['DefaultTheme'] = '".$Settings['DefaultTheme']."';\n\$Settings['DefaultTimeZone'] = '".$Settings['DefaultTimeZone']."';\n\$Settings['DefaultDST'] = '".$Settings['DefaultDST']."';\n\$Settings['charset'] = '".$Settings['charset']."';\n\$Settings['add_power_by'] = ".bool_string($Settings['add_power_by']).";\n\$Settings['send_pagesize'] = ".bool_string($Settings['send_pagesize']).";\n\$Settings['max_posts'] = '".$Settings['max_posts']."';\n\$Settings['max_topics'] = '".$Settings['max_topics']."';\n\$Settings['max_memlist'] = '".$Settings['max_memlist']."';\n\$Settings['max_pmlist'] = '".$Settings['max_pmlist']."';\n\$Settings['hot_topic_num'] = '".$Settings['hot_topic_num']."';\n\$Settings['qstr'] = '".$Settings['qstr']."';\n\$Settings['qsep'] = '".$Settings['qsep']."';\n\$Settings['file_ext'] = '".$Settings['file_ext']."';\n\$Settings['rss_ext'] = '".$Settings['rss_ext']."';\n\$Settings['js_ext'] = '".$Settings['js_ext']."';\n\$Settings['showverinfo'] = ".$Settings['showverinfo'].";\n\$Settings['enable_rss'] = ".bool_string($Settings['enable_rss']).";\n\$Settings['enable_search'] = ".bool_string($Settings['enable_search']).";\n\$Settings['sessionid_in_urls'] = ".bool_string($Settings['sessionid_in_urls']).";\n\$Settings['fixpathinfo'] = ".bool_string($Settings['fixpathinfo']).";\n\$Settings['fixbasedir'] = ".bool_string($Settings['fixbasedir']).";\n\$Settings['fixcookiedir'] = ".bool_string($Settings['fixcookiedir']).";\n\$Settings['enable_pathinfo'] = ".bool_string($Settings['enable_pathinfo']).";\n\$Settings['rssurl'] = ".bool_string($Settings['rssurl']).";\n\$Settings['board_offline'] = ".bool_string($Settings['board_offline']).";\n".$pretext2[1]."\n\$SettInfo['board_name'] = '".$SettInfo['board_name']."';\n\$SettInfo['Author'] = '".$SettInfo['Author']."';\n\$SettInfo['Keywords'] = '".$SettInfo['Keywords']."';\n\$SettInfo['Description'] = '".$SettInfo['Description']."';\n".$pretext2[2]."\n\$SettDir['maindir'] = '".$SettDir['maindir']."';\n\$SettDir['inc'] = '".$SettDir['inc']."';\n\$SettDir['misc'] = '".$SettDir['misc']."';\n\$SettDir['admin'] = '".$SettDir['admin']."';\n\$SettDir['mod'] = '".$SettDir['mod']."';\n\$SettDir['themes'] = '".$SettDir['themes']."';\n".$pretext2[3]."\n?>";
$BoardSettingsBak = $pretext.$settcheck.$BoardSettings;
$BoardSettings = $pretext.$settcheck.$BoardSettings;
$fp = fopen("settings.php","w+");
fwrite($fp, $BoardSettings);
fclose($fp);
//	@cp("settings.php","settingsbak.php");
$fp = fopen("settingsbak.php","w+");
fwrite($fp, $BoardSettingsBak);
fclose($fp); } if($_GET['act']=="info"&&$_POST['update']!="now") {
require('settings.php'); $admincptitle = " ".$ThemeSet['TitleDivider']." Board Info Manager";
?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
&nbsp;<a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=info",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Board Info Manager</a></span>
<span style="float: right;">&nbsp;</span></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing Board Info: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableRow3">
<td class="TableRow3">
<form style="display: inline;" method="post" name="install" id="install" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=info",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
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
<tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if($_POST['act']=="info"&&$_POST['update']=="now"&&$_GET['act']=="info"&&
	$_SESSION['UserGroup']!=$Settings['GuestGroup']&&$GroupInfo['HasAdminCP']=="yes") {
$Settings['fixpathinfo'] = bool_string($Settings['fixpathinfo']);
if($Settings['fixpathinfo']!="true"&&$Settings['fixpathinfo']!="false") {
   $Settings['fixpathinfo']="'".$Settings['fixpathinfo']."'"; }
$Settings['fixbasedir'] = bool_string($Settings['fixbasedir']);
if($Settings['fixbasedir']!="true"&&$Settings['fixbasedir']!="false") {
   $Settings['fixbasedir']="'".$Settings['fixbasedir']."'"; }
$Settings['fixcookiedir'] = bool_string($Settings['fixcookiedir']);
if($Settings['fixcookiedir']!="true"&&$Settings['fixcookiedir']!="false") {
   $Settings['fixcookiedir']="'".$Settings['fixcookiedir']."'"; }
$Settings['rssurl'] = bool_string($Settings['rssurl']);
if($Settings['rssurl']!="true"&&$Settings['rssurl']!="false") {
   $Settings['rssurl']="'".$Settings['rssurl']."'"; }
$Settings['showverinfo'] = bool_string($Settings['showverinfo']);
if($Settings['showverinfo']!="true"&&$Settings['showverinfo']!="false") {
   $Settings['showverinfo']="'".$Settings['showverinfo']."'"; }
$_POST  = array_map("rsq", $_POST);
$BoardSettings=$pretext2[0]."\n\$Settings['sqlhost'] = '".$Settings['sqlhost']."';\n\$Settings['sqldb'] = '".$Settings['sqldb']."';\n\$Settings['sqltable'] = '".$Settings['sqltable']."';\n\$Settings['sqluser'] = '".$Settings['sqluser']."';\n\$Settings['sqlpass'] = '".$Settings['sqlpass']."';\n\$Settings['board_name'] = '".$_POST['board_name']."';\n\$Settings['idbdir'] = '".$Settings['idbdir']."';\n\$Settings['idburl'] = '".$Settings['idburl']."';\n\$Settings['enable_https'] = ".bool_string($Settings['enable_https']).";\n\$Settings['weburl'] = '".$Settings['weburl']."';\n\$Settings['use_gzip'] = '".$Settings['use_gzip']."';\n\$Settings['html_type'] = '".$Settings['html_type']."';\n\$Settings['html_level'] = '".$Settings['html_level']."';\n\$Settings['output_type'] = '".$Settings['output_type']."';\n\$Settings['GuestGroup'] = '".$Settings['GuestGroup']."';\n\$Settings['MemberGroup'] = '".$Settings['MemberGroup']."';\n\$Settings['ValidateGroup'] = '".$Settings['ValidateGroup']."';\n\$Settings['AdminValidate'] = ".bool_string($Settings['AdminValidate']).";\n\$Settings['TestReferer'] = '".$Settings['TestReferer']."';\n\$Settings['DefaultTheme'] = '".$Settings['DefaultTheme']."';\n\$Settings['DefaultTimeZone'] = '".$Settings['DefaultTimeZone']."';\n\$Settings['DefaultDST'] = '".$Settings['DefaultDST']."';\n\$Settings['charset'] = '".$Settings['charset']."';\n\$Settings['add_power_by'] = ".bool_string($Settings['add_power_by']).";\n\$Settings['send_pagesize'] = ".bool_string($Settings['send_pagesize']).";\n\$Settings['max_posts'] = '".$Settings['max_posts']."';\n\$Settings['max_topics'] = '".$Settings['max_topics']."';\n\$Settings['max_memlist'] = '".$Settings['max_memlist']."';\n\$Settings['max_pmlist'] = '".$Settings['max_pmlist']."';\n\$Settings['hot_topic_num'] = '".$Settings['hot_topic_num']."';\n\$Settings['qstr'] = '".$Settings['qstr']."';\n\$Settings['qsep'] = '".$Settings['qsep']."';\n\$Settings['file_ext'] = '".$Settings['file_ext']."';\n\$Settings['rss_ext'] = '".$Settings['rss_ext']."';\n\$Settings['js_ext'] = '".$Settings['js_ext']."';\n\$Settings['showverinfo'] = ".$Settings['showverinfo'].";\n\$Settings['enable_rss'] = ".bool_string($Settings['enable_rss']).";\n\$Settings['enable_search'] = ".bool_string($Settings['enable_search']).";\n\$Settings['sessionid_in_urls'] = ".bool_string($Settings['sessionid_in_urls']).";\n\$Settings['fixpathinfo'] = ".bool_string($Settings['fixpathinfo']).";\n\$Settings['fixbasedir'] = ".bool_string($Settings['fixbasedir']).";\n\$Settings['fixcookiedir'] = ".bool_string($Settings['fixcookiedir']).";\n\$Settings['enable_pathinfo'] = ".bool_string($Settings['enable_pathinfo']).";\n\$Settings['rssurl'] = ".bool_string($Settings['rssurl']).";\n\$Settings['board_offline'] = ".bool_string($Settings['board_offline']).";\n".$pretext2[1]."\n\$SettInfo['board_name'] = '".$_POST['board_name']."';\n\$SettInfo['Author'] = '".$_POST['Author']."';\n\$SettInfo['Keywords'] = '".$_POST['Keywords']."';\n\$SettInfo['Description'] = '".$_POST['Description']."';\n".$pretext2[2]."\n\$SettDir['maindir'] = '".$SettDir['maindir']."';\n\$SettDir['inc'] = '".$SettDir['inc']."';\n\$SettDir['misc'] = '".$SettDir['misc']."';\n\$SettDir['admin'] = '".$SettDir['admin']."';\n\$SettDir['mod'] = '".$SettDir['mod']."';\n\$SettDir['themes'] = '".$SettDir['themes']."';\n".$pretext2[3]."\n?>";
$BoardSettingsBak = $pretext.$settcheck.$BoardSettings;
$BoardSettings = $pretext.$settcheck.$BoardSettings;
$fp = fopen("settings.php","w+");
fwrite($fp, $BoardSettings);
fclose($fp);
//	@cp("settings.php","settingsbak.php");
$fp = fopen("settingsbak.php","w+");
fwrite($fp, $BoardSettingsBak);
fclose($fp); } if($_POST['update']=="now"&&$_GET['act']!=null) {
	$profiletitle = " ".$ThemeSet['TitleDivider']." Updating Settings"; ?>
</td></tr>
<tr id="ProfileTitleEnd" class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr></table></div><?php } ?>
</td></tr>
</table>
<div>&nbsp;</div>