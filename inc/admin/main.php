<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2010 iDB Support - http://idb.berlios.de/
    Copyright 2004-2010 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: main.php - Last Update: 04/30/2010 SVN 472 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="main.php"||$File3Name=="/main.php") {
	require('index.php');
	exit(); }

// Check if we can goto admin cp
if($_SESSION['UserGroup']==$Settings['GuestGroup']||$GroupInfo['HasAdminCP']=="no") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if(!isset($_POST['update'])) { $_POST['update'] = null; }
if($_GET['act']=="sql"&&$GroupInfo['ViewDBInfo']!="yes") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if(!isset($_POST['update'])) { $_POST['update'] = null; }
$iDBRDate = $SVNDay[0]."/".$SVNDay[1]."/".$SVNDay[2];
$iDBRSVN = $VER2[2]." ".$SubVerN;
$OutPutLog = null;
$LastUpdateS = "Last Update: ".$iDBRDate." ".$iDBRSVN;
$pretext = "<?php\n/*\n    This program is free software; you can redistribute it and/or modify\n    it under the terms of the GNU General Public License as published by\n    the Free Software Foundation; either version 2 of the License, or\n    (at your option) any later version.\n\n    This program is distributed in the hope that it will be useful,\n    but WITHOUT ANY WARRANTY; without even the implied warranty of\n    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n    Revised BSD License for more details.\n\n    Copyright 2004-".$SVNDay[2]." iDB Support - http://idb.berlios.de/\n    Copyright 2004-".$SVNDay[2]." Game Maker 2k - http://gamemaker2k.org/\n    iDB Installer made by Game Maker 2k - http://idb.berlios.net/\n\n    \$FileInfo: settings.php & settingsbak.php - ".$LastUpdateS." - Author: cooldude2k \$\n*/\n";
$pretext2 = array("/*   Board Setting Section Begins   */\n\$Settings = array();","/*   Board Setting Section Ends  \n     Board Info Section Begins   */\n\$SettInfo = array();","/*   Board Setting Section Ends   \n     Board Dir Section Begins   */\n\$SettDir = array();","/*   Board Dir Section Ends   */");
$settcheck = "\$File3Name = basename(\$_SERVER['SCRIPT_NAME']);\nif (\$File3Name==\"settings.php\"||\$File3Name==\"/settings.php\"||\n    \$File3Name==\"settingsbak.php\"||\$File3Name==\"/settingsbak.php\") {\n    header('Location: index.php');\n    exit(); }\n";
if(!isset($_POST['update'])) { $_POST['update'] = null; }
function bool_string($boolean) {
if(!is_bool($boolean)) {
return $boolean; }
if(is_bool($boolean)) { 
if($boolean==0||$boolean===false) { 
return "false"; }
if($boolean==1||$boolean===true) { 
return "true"; } } }
function null_string($string) {
$strtype = strtolower(gettype($string));
if($strtype=="string") {
	return "'".$string."'"; }
if($strtype=="null") {
	return "null"; }
if($strtype=="integer") {
	return $string; } 
	return "null"; }
function rsq($string) {
$string = preg_replace("/^(\')|$(\')/i","\'",$string);
return $string; }
$KarmaExp = explode("&",$Settings['KarmaBoostDays']);
$KarmaNum = count($KarmaExp); 
$Karmai = 0; $KarmaNex = 0; $KarmaTemp = null;
while ($Karmai < $KarmaNum) {
if(is_numeric($KarmaExp[$Karmai])) {
$KarmaTemp[$KarmaNex] = $KarmaExp[$Karmai];
++$KarmaNex; }
++$Karmai; }
$KarmaExp = $KarmaTemp;
$Settings['KarmaBoostDays'] = implode("&",$KarmaExp);
$KBoostPercent = explode("|",$Settings['KBoostPercent']);
if(count($KBoostPercent)<1) { 
$Settings['KBoostPercent'] = "6|10"; }
if(!is_numeric($KBoostPercent[0])) {
$Settings['KBoostPercent'] = "6|10"; }
if(count($KBoostPercent)==1) { 
$Settings['KBoostPercent'] = "6|10"; }
if(!is_numeric($KBoostPercent[1])) {
$Settings['KBoostPercent'] = "6|10"; }
if(count($KBoostPercent)>2) { 
$Settings['KBoostPercent'] = "6|10"; }
if(!isset($Settings['sqltype'])) {
	$Settings['sqltype'] = "mysql"; }
$Settings['sqltype'] = strtolower($Settings['sqltype']);
if($Settings['sqltype']!="mysql"&&
	$Settings['sqltype']!="mysqli"&&
	$Settings['sqltype']!="pgsql"&&
	$Settings['sqltype']!="sqlite") {
	$Settings['sqltype'] = "mysql"; }
if($Settings['sqltype']=="mysql"||
	$Settings['sqltype']=="mysqli") {
$DBType['Server'] = "MySQL version ".sql_server_info($SQLStat);
$DBType['Client'] = "MySQL version ".sql_client_info($SQLStat); }
if($Settings['sqltype']=="pgsql") {
$DBType['Server'] = "PostgreSQL version ".sql_server_info($SQLStat);
$DBType['Client'] = "PostgreSQL version ".sql_client_info($SQLStat); }
if($Settings['sqltype']=="sqlite") {
$DBType['Server'] = "SQLite version ".sql_server_info($SQLStat);
$DBType['Client'] = sql_client_info($SQLStat); }
if(!isset($Settings['vercheck'])) { 
	$Settings['vercheck'] = 2; }
if($Settings['vercheck']!=1&&
	$Settings['vercheck']!=2) {
	$Settings['vercheck'] = 2; }
?>
<table class="Table3">
<tr style="width: 100%; vertical-align: top;">
	<td style="width: 15%; vertical-align: top;">
<?php 
require($SettDir['admin'].'table.php'); 
if($_GET['act']=="delsessions"&&$GroupInfo['ViewDBInfo']=="yes") {
$time = GMTimeStamp() - ini_get("session.gc_maxlifetime");
//$sqlg = sql_pre_query('DELETE FROM \"'.$Settings['sqltable'].'sessions\" WHERE \"expires\" < UNIX_TIMESTAMP();', array(null));
$sqlgc = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" < %i", array($time));
sql_query($sqlgc,$SQLStat);
$_POST['update'] = "now"; $_GET['act'] = "view"; }
if($_GET['act']=="optimize"&&$GroupInfo['ViewDBInfo']=="yes") {
$TablePreFix = $Settings['sqltable'];
function add_prefix($tarray) {
global $TablePreFix;
return $TablePreFix.$tarray; }
$TableChCk = array("categories", "catpermissions", "events", "forums", "groups", "members", "messenger", "permissions", "posts", "restrictedwords", "sessions", "smileys", "topics", "wordfilter");
$TableChCk = array_map("add_prefix",$TableChCk);
$tcount = count($TableChCk); $ti = 0;
$TblOptimized = 0;
if($Settings['sqltype']!="sqlite") {
while ($ti < $tcount) {
if(isset($OptimizeAr["Msg_text"])) { unset($OptimizeAr["Msg_text"]); }
if(isset($OptimizeAr[3])) { unset($OptimizeAr[3]); }
if($Settings['sqltype']=="mysql"||
	$Settings['sqltype']=="mysqli") {
$OptimizeTea = sql_query(sql_pre_query("OPTIMIZE TABLE \"".$TableChCk[$ti]."\"", array(null)),$SQLStat); }
if($Settings['sqltype']=="pgsql") {
$OptimizeTea = sql_query(sql_pre_query("VACUUM ANALYZE \"".$TableChCk[$ti]."\"", array(null)),$SQLStat); }
if($Settings['sqltype']=="mysql"||
	$Settings['sqltype']=="mysqli") {
$OptimizeAr = sql_fetch_array($OptimizeTea);
if(!isset($OptimizeAr["Msg_text"])&&
	isset($OptimizeAr[3])) { $OptimizeAr["Msg_text"] = $OptimizeAr[3]; }
if($OptimizeAr["Msg_text"]=="OK") { 
	++$TblOptimized; } } ++$ti; } }
if($Settings['sqltype']=="sqlite") {
$OptimizeTea = sql_query(sql_pre_query("VACUUM", array(null)),$SQLStat); }
if($Settings['sqltype']=="mysql"||
	$Settings['sqltype']=="mysqli") {
$OutPutLog = "MySQL Output: ".$TblOptimized." tables optimized."; }
if($Settings['sqltype']=="pgsql") {
$OutPutLog = "PGSQL Output: All tables optimized."; }
if($Settings['sqltype']=="sqlite") {
$OutPutLog = "SQLite Output: All tables optimized."; }
$_POST['update'] = "now"; $_GET['act'] = "view"; }
?>
</td>
	<td style="width: 85%; vertical-align: top;">
<?php if($_POST['update']=="now"&&$_GET['act']!=null) {
$updateact = url_maker($exfile['profile'],$Settings['file_ext'],"act=".$_GET['act']."&menu=main",$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']);
$admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
redirect("refresh",$basedir.url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act'],$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin'],FALSE),"3");
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=main",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Updating Settings</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=main",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Updating Settings</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr id="ProfileTitle" class="TableMenuRow2">
<th class="TableMenuColumn2">Updating Settings</th>
</tr>
<tr class="TableMenuRow3" id="ProfileUpdate">
<td class="TableMenuColumn3">
<div style="text-align: center;">
<?php if(isset($OutPutLog)) { echo "<br />".$OutPutLog; } ?>
<br />Settings have been updated <a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=".$_GET['act']."&menu=main",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">click here</a> to go back. ^_^<br />&nbsp;</div>
<?php } if($_GET['act']=="view"&&$_POST['update']!="now") {
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($_SESSION['UserID']));
$result=sql_query($query,$SQLStat);
$num=sql_num_rows($result);
$i=0;
$YourID=sql_result($result,$i,"id");
$Notes=sql_result($result,$i,"Notes");
$noteact = url_maker($exfile['profile'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']);
$notepadact = $noteact; $profiletitle = " ".$ThemeSet['TitleDivider']." NotePad";
$admincptitle = " ".$ThemeSet['TitleDivider']." Admin CP";
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo $noteact; ?>">NotePad</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo $noteact; ?>">NotePad</a>
</span><span style="float: right;">&nbsp;</span></td>
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
<br /><input type="submit" class="Button" value="Save" />&nbsp;<input class="Button" type="reset" />
</div></form></td>
</tr>
<tr id="ProfileEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
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
$mguerys = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE (\"Name\"<>'%s') ORDER BY \"id\" ASC", array("Admin"));
$mgresults=sql_query($mguerys,$SQLStat);
$mnum=sql_num_rows($mgresults);
$mi = 0;
while ($mi < $mnum) {
$MGroups[$mi]=sql_result($mgresults,$mi,"Name");
++$mi; }
sql_free_result($mgresults);
if($Settings['vercheck']===1) {
$AdminCheckURL = url_maker($exfile['admin'],$Settings['file_ext'],"act=vercheck&redirect=on",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); }
if($Settings['vercheck']===2) {
$AdminCheckURL = url_maker($exfile['admin'],$Settings['file_ext'],"act=vercheck&vercheck=newtype&redirect=on",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); }
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=settings",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Settings Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=settings",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Settings Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing Setting for iDB: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=settings",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<?php if($GroupInfo['ViewDBInfo']=="yes") { 
?><tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Forum Software Version:</span></td>
	<td style="width: 50%;"><?php echo $VerInfo['iDB_Ver_Show']; ?>&nbsp;<a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=vercheck",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>" onclick="window.open(this.href);return false;"><img src="<?php echo $AdminCheckURL; ?>" alt="Version Check: Click to see more info." title="Version Check: Click to see more info." /></a></td>
</tr><tr>
	<td style="width: 50%;"><span class="TextBoxLabel">Forum UUID:</span></td>
	<td style="width: 50%;"><?php echo $Settings['BoardUUID']; ?></td>
</tr><tr id="clickhere" style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Version Checker:</span></td>
	<td style="width: 50%;"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=settings",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>#iverinfo" onclick="idbvercheck(); document.getElementById('clickhere').style.display = 'none';">Click Here</a></td>
</tr><?php if($OSType!=""&&isset($OSType)) { 
?><tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Server Operating System:</span></td>
	<td style="width: 50%;"><?php echo $OSType; ?></td>
</tr><?php } } ?><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="BoardURL">Insert The Board URL:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="BoardURL" size="20" id="BoardURL" value="<?php echo $Settings['idburl']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="WebURL">Insert The WebSite URL:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" name="WebURL" size="20" id="WebURL" value="<?php echo $Settings['weburl']; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="GuestGroup">Insert The Guest Group:</label></td>
	<td style="width: 50%;"><select id="GuestGroup" name="GuestGroup" class="TextBox">
<option selected="selected" value="<?php echo $Settings['GuestGroup']; ?>">Old Value (<?php echo $Settings['GuestGroup']; ?>)</option>
<?php $gi = 0; $gnum = count($MGroups);
while ($gi < $gnum) { ?>
<option value="<?php echo $MGroups[$gi]; ?>"><?php echo $MGroups[$gi]; ?></option>
<?php ++$gi; } ?>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="MemberGroup">Insert The Member Group:</label></td>
	<td style="width: 50%;"><select id="MemberGroup" name="MemberGroup" class="TextBox">
<option selected="selected" value="<?php echo $Settings['MemberGroup']; ?>">Old Value (<?php echo $Settings['MemberGroup']; ?>)</option>
<?php $gi = 0; $gnum = count($MGroups);
while ($gi < $gnum) { ?>
<option value="<?php echo $MGroups[$gi]; ?>"><?php echo $MGroups[$gi]; ?></option>
<?php ++$gi; } ?>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ValidateGroup">Insert The Validate Group:</label></td>
	<td style="width: 50%;"><select id="ValidateGroup" name="ValidateGroup" class="TextBox">
<option selected="selected" value="<?php echo $Settings['ValidateGroup']; ?>">Old Value (<?php echo $Settings['ValidateGroup']; ?>)</option>
<?php $gi = 0; $gnum = count($MGroups);
while ($gi < $gnum) { ?>
<option value="<?php echo $MGroups[$gi]; ?>"><?php echo $MGroups[$gi]; ?></option>
<?php ++$gi; } ?>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="AdminValidate">Enable validate new members:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="AdminValidate" id="AdminValidate">
	<option<?php if($Settings['AdminValidate']=="off") { echo " selected=\"selected\""; } ?> value="off">no</option>
	<option<?php if($Settings['AdminValidate']=="on") { echo " selected=\"selected\""; } ?> value="on">yes</option>
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
	<td style="width: 50%;"><label class="TextBoxLabel" for="HTMLLevel">HTML Level only for XHTML 1.0:</label></td>
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
	<td style="width: 50%;"><label class="TextBoxLabel" for="DefaultTheme">Default Theme:</label></td>
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
	<option<?php if($Settings['enable_https']=="on") { echo " selected=\"selected\""; } ?> value="on">on</option>
	<option<?php if($Settings['enable_https']=="off") { echo " selected=\"selected\""; } ?> value="off">off</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="enable_rss">Enable RSS:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="enable_rss" id="enable_rss">
	<option<?php if($Settings['enable_rss']=="on") { echo " selected=\"selected\""; } ?> value="on">on</option>
	<option<?php if($Settings['enable_rss']=="off") { echo " selected=\"selected\""; } ?> value="off">off</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="enable_search">Enable search:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="enable_search" id="enable_search">
	<option<?php if($Settings['enable_search']=="on") { echo " selected=\"selected\""; } ?> value="on">on</option>
	<option<?php if($Settings['enable_search']=="off") { echo " selected=\"selected\""; } ?> value="off">off</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="TestReferer">Test Referering URL:</label></td>
	<td style="width: 50%;"><select id="TestReferer" name="TestReferer" class="TextBox">
<option<?php if($Settings['TestReferer']=="on") { echo " selected=\"selected\""; } ?> value="on">on</option>
<option<?php if($Settings['TestReferer']=="off") { echo " selected=\"selected\""; } ?> value="off">off</option>
</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<?php if($GroupInfo['ViewDBInfo']=="yes") { ?>
<span style="display: none;" id="iverinfo"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=settings",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>#" onclick="idbvercheck();">Version Checker: Click Here</a><br /><br /></span>
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
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if($_POST['act']=="settings"&&$_POST['update']=="now"&&$_GET['act']=="settings"&&
	$_SESSION['UserGroup']!=$Settings['GuestGroup']&&$GroupInfo['HasAdminCP']=="yes") {
$_POST  = array_map("rsq", $_POST);
if(!isset($Settings['BoardUUID'])||$Settings['BoardUUID']===null) {
	$Settings['BoardUUID'] = rand_uuid("rand"); }
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
$BoardSettings=$pretext2[0]."\n".
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
"\$Settings['html_level'] = ".null_string($_POST['HTMLLevel']).";\n".
"\$Settings['output_type'] = ".null_string($_POST['OutPutType']).";\n".
"\$Settings['GuestGroup'] = ".null_string($_POST['GuestGroup']).";\n".
"\$Settings['MemberGroup'] = ".null_string($_POST['MemberGroup']).";\n".
"\$Settings['ValidateGroup'] = ".null_string($_POST['ValidateGroup']).";\n".
"\$Settings['AdminValidate'] = ".null_string($_POST['AdminValidate']).";\n".
"\$Settings['TestReferer'] = ".null_string($_POST['TestReferer']).";\n".
"\$Settings['DefaultTheme'] = ".null_string($_POST['DefaultTheme']).";\n".
"\$Settings['DefaultTimeZone'] = ".null_string($_POST['YourOffSet'].":".$_POST['MinOffSet']).";\n".
"\$Settings['DefaultDST'] = ".null_string($_POST['DST']).";\n".
"\$Settings['use_hashtype'] = ".null_string($Settings['use_hashtype']).";\n".
"\$Settings['charset'] = ".null_string($Settings['charset']).";\n".
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
"\$Settings['fixpathinfo'] = ".null_string($Settings['fixpathinfo']).";\n".
"\$Settings['fixbasedir'] = ".null_string($Settings['fixbasedir']).";\n".
"\$Settings['fixcookiedir'] = ".null_string($Settings['fixcookiedir']).";\n".
"\$Settings['enable_pathinfo'] = ".null_string($Settings['enable_pathinfo']).";\n".
"\$Settings['rssurl'] = ".null_string($Settings['rssurl']).";\n".
"\$Settings['board_offline'] = ".null_string($Settings['board_offline']).";\n".
"\$Settings['BoardUUID'] = ".null_string($Settings['BoardUUID']).";\n".
"\$Settings['KarmaBoostDays'] = ".null_string($Settings['KarmaBoostDays']).";\n".
"\$Settings['KBoostPercent'] = ".null_string($Settings['KBoostPercent']).";\n".$pretext2[1]."\n".
"\$SettInfo['board_name'] = ".null_string($SettInfo['board_name']).";\n".
"\$SettInfo['Author'] = ".null_string($SettInfo['Author']).";\n".
"\$SettInfo['Keywords'] = ".null_string($SettInfo['Keywords']).";\n".
"\$SettInfo['Description'] = ".null_string($SettInfo['Description']).";\n".$pretext2[2]."\n".
"\$SettDir['maindir'] = ".null_string($SettDir['maindir']).";\n".
"\$SettDir['inc'] = ".null_string($SettDir['inc']).";\n".
"\$SettDir['misc'] = ".null_string($SettDir['misc']).";\n".
"\$SettDir['sql'] = ".null_string($SettDir['sql']).";\n".
"\$SettDir['admin'] = ".null_string($SettDir['admin']).";\n".
"\$SettDir['sqldumper'] = ".null_string($SettDir['sqldumper']).";\n".
"\$SettDir['mod'] = ".null_string($SettDir['mod']).";\n".
"\$SettDir['themes'] = ".null_string($SettDir['themes']).";\n".$pretext2[3]."\n?>";
$BoardSettingsBak = $pretext.$settcheck.$BoardSettings;
$BoardSettings = $pretext.$settcheck.$BoardSettings;
$fp = fopen("settings.php","w+");
fwrite($fp, $BoardSettings);
fclose($fp);
//	cp("settings.php","settingsbak.php");
$fp = fopen("settingsbak.php","w+");
fwrite($fp, $BoardSettingsBak);
fclose($fp); } if($_GET['act']=="sql"&&$_POST['update']!="now"&&$GroupInfo['ViewDBInfo']=="yes") {
require('settings.php'); $admincptitle = " ".$ThemeSet['TitleDivider']." Database Manager";
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=sql",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Database Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=sql",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">iDB Database Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing SQL Settings for iDB: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=sql",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Database Server:</span></td>
	<td style="width: 50%;"><?php echo $DBType['Server']; ?></td>
</tr><?php if($Settings['sqltype']=="mysql"||
	$Settings['sqltype']=="mysqli"||
	$Settings['sqltype']=="pgsql") { 
?><tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Database Client:</span></td>
	<td style="width: 50%;"><?php echo $DBType['Client']; ?></td>
</tr><?php } if($Settings['sqltype']=="sqlite") { 
?><tr style="text-align: left;">
	<td style="width: 50%;"><span class="TextBoxLabel">Database File Size:</span></td>
	<td style="width: 50%;"><?php echo sprintf("%u", filesize($Settings['sqldb']))." bytes"; ?></td>
</tr><?php } ?><tr style="text-align: left;">
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
<input type="hidden" name="act" value="sql" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Apply" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if($_POST['act']=="sql"&&$_POST['update']=="now"&&$_GET['act']=="sql"&&
	$_SESSION['UserGroup']!=$Settings['GuestGroup']&&$GroupInfo['HasAdminCP']=="yes"&&
	$GroupInfo['ViewDBInfo']=="yes") {
$_POST  = array_map("rsq", $_POST);
if(!isset($Settings['BoardUUID'])||$Settings['BoardUUID']===null) {
	$Settings['BoardUUID'] = rand_uuid("rand"); }
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
$BoardSettings=$pretext2[0]."\n".
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
"\$Settings['DefaultDST'] = ".null_string($Settings['DefaultDST']).";\n".
"\$Settings['use_hashtype'] = ".null_string($Settings['use_hashtype']).";\n".
"\$Settings['charset'] = ".null_string($Settings['charset']).";\n".
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
"\$Settings['fixpathinfo'] = ".null_string($Settings['fixpathinfo']).";\n".
"\$Settings['fixbasedir'] = ".null_string($Settings['fixbasedir']).";\n".
"\$Settings['fixcookiedir'] = ".null_string($Settings['fixcookiedir']).";\n".
"\$Settings['enable_pathinfo'] = ".null_string($Settings['enable_pathinfo']).";\n".
"\$Settings['rssurl'] = ".null_string($Settings['rssurl']).";\n".
"\$Settings['board_offline'] = ".null_string($Settings['board_offline']).";\n".
"\$Settings['BoardUUID'] = ".null_string($Settings['BoardUUID']).";\n".
"\$Settings['KarmaBoostDays'] = ".null_string($Settings['KarmaBoostDays']).";\n".
"\$Settings['KBoostPercent'] = ".null_string($Settings['KBoostPercent']).";\n".$pretext2[1]."\n".
"\$SettInfo['board_name'] = ".null_string($SettInfo['board_name']).";\n".
"\$SettInfo['Author'] = ".null_string($SettInfo['Author']).";\n".
"\$SettInfo['Keywords'] = ".null_string($SettInfo['Keywords']).";\n".
"\$SettInfo['Description'] = ".null_string($SettInfo['Description']).";\n".$pretext2[2]."\n".
"\$SettDir['maindir'] = ".null_string($SettDir['maindir']).";\n".
"\$SettDir['inc'] = ".null_string($SettDir['inc']).";\n".
"\$SettDir['misc'] = ".null_string($SettDir['misc']).";\n".
"\$SettDir['sql'] = ".null_string($SettDir['sql']).";\n".
"\$SettDir['admin'] = ".null_string($SettDir['admin']).";\n".
"\$SettDir['sqldumper'] = ".null_string($SettDir['sqldumper']).";\n".
"\$SettDir['mod'] = ".null_string($SettDir['mod']).";\n".
"\$SettDir['themes'] = ".null_string($SettDir['themes']).";\n".$pretext2[3]."\n?>";
$BoardSettingsBak = $pretext.$settcheck.$BoardSettings;
$BoardSettings = $pretext.$settcheck.$BoardSettings;
$fp = fopen("settings.php","w+");
fwrite($fp, $BoardSettings);
fclose($fp);
//	cp("settings.php","settingsbak.php");
$fp = fopen("settingsbak.php","w+");
fwrite($fp, $BoardSettingsBak);
fclose($fp); } if($_GET['act']=="info"&&$_POST['update']!="now") {
require('settings.php'); $admincptitle = " ".$ThemeSet['TitleDivider']." Board Info Manager";
?>
<div class="TableMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=info",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Board Info Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=info",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Board Info Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing Board Info: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=info",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">
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
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if($_POST['act']=="info"&&$_POST['update']=="now"&&$_GET['act']=="info"&&
	$_SESSION['UserGroup']!=$Settings['GuestGroup']&&$GroupInfo['HasAdminCP']=="yes") {
$_POST  = array_map("rsq", $_POST);
if(!isset($Settings['BoardUUID'])||$Settings['BoardUUID']===null) {
	$Settings['BoardUUID'] = rand_uuid("rand"); }
$_POST['board_name'] = htmlspecialchars($_POST['board_name'], ENT_QUOTES, $Settings['charset']);
$_POST['board_name'] = fixbamps($_POST['board_name']);
$_POST['board_name'] = remove_spaces($_POST['board_name']);
$_POST['board_name'] = str_replace("\&#039;", "&#039;", $_POST['board_name']);
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
$BoardSettings=$pretext2[0]."\n".
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
"\$Settings['DefaultDST'] = ".null_string($Settings['DefaultDST']).";\n".
"\$Settings['use_hashtype'] = ".null_string($Settings['use_hashtype']).";\n".
"\$Settings['charset'] = ".null_string($Settings['charset']).";\n".
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
"\$Settings['fixpathinfo'] = ".null_string($Settings['fixpathinfo']).";\n".
"\$Settings['fixbasedir'] = ".null_string($Settings['fixbasedir']).";\n".
"\$Settings['fixcookiedir'] = ".null_string($Settings['fixcookiedir']).";\n".
"\$Settings['enable_pathinfo'] = ".null_string($Settings['enable_pathinfo']).";\n".
"\$Settings['rssurl'] = ".null_string($Settings['rssurl']).";\n".
"\$Settings['board_offline'] = ".null_string($Settings['board_offline']).";\n".
"\$Settings['BoardUUID'] = ".null_string($Settings['BoardUUID']).";\n".
"\$Settings['KarmaBoostDays'] = ".null_string($Settings['KarmaBoostDays']).";\n".
"\$Settings['KBoostPercent'] = ".null_string($Settings['KBoostPercent']).";\n".$pretext2[1]."\n".
"\$SettInfo['board_name'] = ".null_string($_POST['board_name']).";\n".
"\$SettInfo['Author'] = ".null_string($_POST['Author']).";\n".
"\$SettInfo['Keywords'] = ".null_string($_POST['Keywords']).";\n".
"\$SettInfo['Description'] = ".null_string($_POST['Description']).";\n".$pretext2[2]."\n".
"\$SettDir['maindir'] = ".null_string($SettDir['maindir']).";\n".
"\$SettDir['inc'] = ".null_string($SettDir['inc']).";\n".
"\$SettDir['misc'] = ".null_string($SettDir['misc']).";\n".
"\$SettDir['sql'] = ".null_string($SettDir['sql']).";\n".
"\$SettDir['admin'] = ".null_string($SettDir['admin']).";\n".
"\$SettDir['sqldumper'] = ".null_string($SettDir['sqldumper']).";\n".
"\$SettDir['mod'] = ".null_string($SettDir['mod']).";\n".
"\$SettDir['themes'] = ".null_string($SettDir['themes']).";\n".$pretext2[3]."\n?>";
$BoardSettingsBak = $pretext.$settcheck.$BoardSettings;
$BoardSettings = $pretext.$settcheck.$BoardSettings;
$fp = fopen("settings.php","w+");
fwrite($fp, $BoardSettings);
fclose($fp);
//	cp("settings.php","settingsbak.php");
$fp = fopen("settingsbak.php","w+");
fwrite($fp, $BoardSettingsBak);
fclose($fp); } if($_POST['update']=="now"&&$_GET['act']!=null) {
	$profiletitle = " ".$ThemeSet['TitleDivider']." Updating Settings"; ?>
</td></tr>
<tr id="ProfileTitleEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr></table></div><?php } ?>
</td></tr>
</table>
<div>&nbsp;</div>
