<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2007 Cool Dude 2k - http://intdb.sourceforge.net/
    Copyright 2004-2007 Game Maker 2k - http://upload.idb.s1.jcink.com/

    $FileInfo: profilemain.php - Last Update: 05/19/2007 SVN 8 - Author: cooldude2k $
*/
$File1Name = dirname($_SERVER['SCRIPT_NAME'])."/";
$File2Name = $_SERVER['SCRIPT_NAME'];
$File3Name=str_replace($File1Name, null, $File2Name);
if ($File3Name=="profilemain.php"||$File3Name=="/profilemain.php") {
	require('index.php');
	exit(); }
?>
<table class="Table3">
<tr style="width: 100%; vertical-align: top;">
	<td style="width: 15%; vertical-align: top;">
	<table id="ProfileLinks" class="Table1" style="width: 100%; float: left; vertical-align: top;">
<tr class="TableRow1">
<td class="TableRow1"><?php echo $ThemeSet['TitleIcon'] ?>Profile Settings</td>
</tr><tr class="TableRow2">
<td class="TableRow2">&nbsp;</td>
</tr><tr class="TableRow3">
<td class="TableRow3"><a href="<?php echo url_maker($exfile['profile'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']); ?>">Edit NotePad</a></td>
</tr><tr class="TableRow3">
<td class="TableRow3"><a href="<?php echo url_maker($exfile['profile'],$Settings['file_ext'],"act=profile",$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']); ?>">Edit Profile</a></td>
</tr><tr class="TableRow3">
<td class="TableRow3"><a href="<?php echo url_maker($exfile['profile'],$Settings['file_ext'],"act=signature",$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']); ?>">Edit Signature</a></td>
</tr><tr class="TableRow3">
<td class="TableRow3"><a href="<?php echo url_maker($exfile['profile'],$Settings['file_ext'],"act=avatar",$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']); ?>">Edit Avatar</a></td>
</tr><tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr></table><div>&nbsp;</div>
<table class="Table1" style="width: 100%; float: left; vertical-align: top;">
<tr class="TableRow1">
<td class="TableRow1"><?php echo $ThemeSet['TitleIcon'] ?>Board Settings</td>
</tr><tr class="TableRow2">
<td class="TableRow2">&nbsp;</td>
</tr><tr class="TableRow3">
<td class="TableRow3"><a href="<?php echo url_maker($exfile['profile'],$Settings['file_ext'],"act=settings",$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']); ?>">Board Settings</a></td>
</tr><tr class="TableRow3">
<td class="TableRow3"><a href="<?php echo url_maker($exfile['profile'],$Settings['file_ext'],"act=userinfo",$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']); ?>">Change User Info</a></td>
</tr><tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr></table>
</td>
	<td style="width: 85%; vertical-align: top;">
<?php if($_POST['update']=="now"&&$_GET['act']!=null) {
$updateact = url_maker($exfile['profile'],$Settings['file_ext'],"act=".$_GET['act'],$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']);
$profiletitle = " - Updating Settings";
@redirect("refresh",$basedir.url_maker($exfile['profile'],$Settings['file_ext'],"act=".$_GET['act'],$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile'],FALSE),"3");
$noteact = url_maker($exfile['profile'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']);
$profiletitle = " ".$ThemeSet['TitleDivider']." NotePad";
?>
<div class="Table1Border">
<table class="Table1" style="width: 100%;">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo $updateact; ?>">Updating Settings</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr id="ProfileTitle" class="TableRow2">
<th class="TableRow2">Updating Settings</th>
</tr>
<tr class="TableRow3" id="ProfileUpdate">
<td class="TableRow3">
<div style="text-align: center;">
<br />Profile updated <a href="<?php echo $updateact; ?>">click here</a> to go back. ^_^<br />&nbsp;</div>
<?php } if($_GET['act']=="view") {
if($_POST['update']!="now") {
$query = query("select * from ".$Settings['sqltable']."members where `id`=%i", array($_SESSION['UserID']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
$i=0;
$YourID=mysql_result($result,$i,"id");
$Notes=mysql_result($result,$i,"Notes");
$noteact = url_maker($exfile['profile'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']);
$profiletitle = " ".$ThemeSet['TitleDivider']." NotePad";
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
<?php @mysql_free_result($result); }
if($_POST['update']=="now") {
if($_POST['act']=="view"&&
	$_SESSION['UserGroup']!=$Settings['GuestGroup']) {
	$_POST['NotePad'] = htmlentities($_POST['NotePad'], ENT_QUOTES);
	$NewDay=GMTimeStamp();
	$NewIP=$_SERVER['REMOTE_ADDR'];
	$querynewskin = query("update ".$Settings['sqltable']."members set Notes='%s',LastActive='%s',IP='%s' WHERE id=%i", array($_POST['NotePad'],$NewDay,$NewIP,$_SESSION['UserID']));
		mysql_query($querynewskin); } } }
if($_GET['act']=="signature") {
if($_POST['update']!="now") {
$query = query("select * from ".$Settings['sqltable']."members where `id`=%i", array($_SESSION['UserID']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
$i=0;
$YourID=mysql_result($result,$i,"id");
$Signature=mysql_result($result,$i,"Signature"); 
$signatureact = url_maker($exfile['profile'],$Settings['file_ext'],"act=signature",$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']);
$profiletitle = " ".$ThemeSet['TitleDivider']." Signature Editor";
?>
<div class="Table1Border">
<table class="Table1" style="width: 100%;">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo $signatureact; ?>">Signature Editer</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr id="ProfileTitle" class="TableRow2">
<th class="TableRow2">Signature Editor</th>
</tr>
<tr class="TableRow3" id="SignatureRow">
<td class="TableRow3">
<form method="post" action="<?php echo $signatureact; ?>"><div style="text-align: center;">
<label class="TextBoxLabel" for="Signature">Your Signature</label><br />
<textarea class="TextBox" name="Signature" id="Signature" style="width: 75%; height: 128px;" rows="10" cols="84"><?php echo $Signature; ?></textarea>
<input type="hidden" name="act" value="signature" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<br /><input type="submit" class="Button" value="Save" />&nbsp;<input class="Button" type="reset" />
</div></form></td>
</tr>
<tr id="ProfileEnd" class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table>
</div>
<?php @mysql_free_result($result); }
if($_POST['update']=="now") {
if($_POST['act']=="signature"&&
	$_SESSION['UserGroup']!=$Settings['GuestGroup']) {
	$_POST['Signature'] = htmlentities($_POST['Signature'], ENT_QUOTES);
	$_POST['Signature'] = preg_replace("/\t+/"," ",$_POST['Signature']);
	$_POST['Signature'] = preg_replace("/\s\s+/"," ",$_POST['Signature']);
	$NewDay=GMTimeStamp();
	$NewIP=$_SERVER['REMOTE_ADDR'];
	$querynewskin = query("update ".$Settings['sqltable']."members set Signature='%s',LastActive='%s',IP='%s' WHERE id=%i", array($_POST['Signature'],$NewDay,$NewIP,$_SESSION['UserID']));
	mysql_query($querynewskin); } } }
if($_GET['act']=="avatar") {
if($_POST['update']!="now") {
$query = query("select * from ".$Settings['sqltable']."members where `id`=%i", array($_SESSION['UserID']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
$i=0;
$YourID=mysql_result($result,$i,"id");
$User1Avatar=mysql_result($result,$i,"Avatar"); 
$User1AvatarSize=mysql_result($result,$i,"AvatarSize");
$avataract = url_maker($exfile['profile'],$Settings['file_ext'],"act=avatar",$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']);
$profiletitle = " ".$ThemeSet['TitleDivider']." Avatar Editor";
$Pre1Avatar = $User1Avatar;
if ($User1Avatar==null) { $User1Avatar="http://"; }
if ($Pre1Avatar=="http://"||$Pre1Avatar==null) {
$Pre1Avatar=$ThemeSet['NoAvatar'];
$User1AvatarSize=$ThemeSet['NoAvatarSize']; }
$AvatarSize1=explode("x", $User1AvatarSize);
$AvatarSize1W=$AvatarSize1[0]; $AvatarSize1H=$AvatarSize1[1];
?>
<div class="Table1Border">
<table class="Table1" style="width: 100%;">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo $avataract; ?>">Avatar Editer</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr id="ProfileTitle" class="TableRow2">
<th class="TableRow2">Avatar Editor</th>
</tr>
<tr class="TableRow3" id="AvatarEditor">
<td class="TableRow3">
<form method="post" action="<?php echo $avataract; ?>">
 <?php  /* Avatar Table Thanks For SeanJ's Help at http://seanj.jcink.com/ */  ?>
 <table class="AvatarTable" style="width: 100px; height: 100px; text-align: center;">
	<tr class="AvatarRow" style="width: 100%; height: 100%;">
		<td class="AvatarRow" style="width: 100%; height: 100%; text-align: center; vertical-align: middle;">
		<img src="<?php echo $Pre1Avatar; ?>" alt="<?php echo $_SESSION['MemberName']; ?>'s Avatar" title="<?php echo $_SESSION['MemberName']; ?>'s Avatar" style="border: 0px; width: <?php echo $AvatarSize1W; ?>px; height: <?php echo $AvatarSize1H; ?>px;" />
		</td>
	</tr>
 </table>
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 40%;"><label class="TextBoxLabel" for="Avatar">Your Avatar</label></td>
	<td style="width: 60%;"><input type="text" class="TextBox" name="Avatar" id="Avatar" value="<?php echo $User1Avatar; ?>" size="20" /></td>
	</tr><tr style="text-align: left;">
	<td style="width: 40%;"><label class="TextBoxLabel" for="AvatarSizeW">Avatar Width</label></td>
	<td style="width: 60%;"><select size="1" name="AvatarSizeW" id="AvatarSizeW" class="TextBox">
	<option value="<?php echo $AvatarSize1W; ?>" selected="selected"><?php echo $AvatarSize1W; ?></option><?php echo "\n"; $r=1; while ($r <= 100) { ?><option value="<?php echo $r ?>"><?php echo $r; ?></option><?php echo "\n"; ++$r; } ?>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 40%;"><label class="TextBoxLabel" for="AvatarSizeH">Avatar Height</label></td>
	<td style="width: 60%;"><select size="1" name="AvatarSizeH" id="AvatarSizeH" class="TextBox">
<option value="<?php echo $AvatarSize1H; ?>" selected="selected"><?php echo $AvatarSize1H; ?></option><?php echo "\n"; $s=1; while ($s <= 100) { ?><option value="<?php echo $s ?>"><?php echo $s; ?></option><?php echo "\n"; ++$s; } ?>
</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="avatar" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Save" />
<input class="Button" type="reset" />
</td></tr></table>
</form></td>
</tr>
<tr id="ProfileEnd" class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table>
</div>
<?php @mysql_free_result($result); }
if($_POST['update']=="now") {
if($_POST['Avatar']!=null&&$_POST['AvatarSizeW']!=null&&$_POST['AvatarSizeH']!=null&&
	$_SESSION['UserGroup']!=$Settings['GuestGroup']) {
	if($_POST['AvatarSizeW']>=100) { $_POST['AvatarSizeW']=100; }
	if($_POST['AvatarSizeH']>=100) { $_POST['AvatarSizeH']=100; }
	$fullavatarsize = $_POST['AvatarSizeW']."x".$_POST['AvatarSizeH'];
	$_POST['Avatar'] = htmlentities($_POST['Avatar'], ENT_QUOTES);
	$NewDay=GMTimeStamp();
	$NewIP=$_SERVER['REMOTE_ADDR'];
	$_POST['Avatar'] = @remove_spaces($_POST['Avatar']);
	$querynewskin = query("update ".$Settings['sqltable']."members set Avatar='%s',AvatarSize='%s',LastActive='%s',IP='%s' WHERE id=%i", array($_POST['Avatar'],$fullavatarsize,$NewDay,$NewIP,$_SESSION['UserID']));
	mysql_query($querynewskin); } } }
if($_GET['act']=="settings") {
if($_POST['update']!="now") {
$query = query("select * from ".$Settings['sqltable']."members where `id`=%i", array($_SESSION['UserID']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
$i=0;
$YourID=mysql_result($result,$i,"id");
$User1TimeZone=mysql_result($result,$i,"TimeZone"); 
$User1DST=mysql_result($result,$i,"DST");
$settingsact = url_maker($exfile['profile'],$Settings['file_ext'],"act=settings",$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']);
$profiletitle = " ".$ThemeSet['TitleDivider']." Board Settings"; ?>
<div class="Table1Border">
<table class="Table1" style="width: 100%;">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo $settingsact; ?>">Board Settings</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr id="ProfileTitle" class="TableRow2">
<th class="TableRow2">Board Settings</th>
</tr>
<tr class="TableRow3" id="BoardSettings">
<td class="TableRow3">
<form method="post" action="<?php echo $settingsact; ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 40%;"><label class="TextBoxLabel" for="YourOffSet">Your TimeZone:</label></td>
	<td style="width: 60%;"><select id="YourOffSet" name="YourOffSet" class="TextBox">
<option selected="selected" value="<?php echo $User1TimeZone; ?>">Old Value (<?php echo $User1TimeZone.":00 hours"; ?>)</option>
<?php
$plusi = 1; $minusi = 12;
$plusnum = 13; $minusnum = 0;
while ($minusi > $minusnum) {
echo "<option value=\"-".$minusi."\">GMT - ".$minusi.":00 hours</option>\n";
--$minusi; }
?>
<option value="0">GMT +/- 0:00 hours</option>
<?php
while ($plusi < $plusnum) {
echo "<option value=\"".$plusi."\">GMT + ".$plusi.":00 hours</option>\n";
++$plusi; }
?></select></td>
</tr><tr style="text-align: left;">
	<td style="width: 40%;"><label class="TextBoxLabel" for="skin">Pick a CSS Theme</label></td>
	<td style="width: 60%;"><select id="skin" name="skin" class="TextBox">
<option selected="selected" value="<?php echo $_SESSION['Theme']; ?>">Old Value (<?php echo $_SESSION['Theme']; ?>)</option><?php
$skindir = dirname(realpath("settings.php"))."/".$SettDir['themes'];
if ($handle = opendir($skindir)) {
   while (false !== ($file = readdir($handle))) {
	   if ($dirnum==null) { $dirnum = 0; }
	   if (file_exists($skindir.$file."/info.php")) {
		   if ($file != "." && $file != "..") {
	   include($skindir.$file."/info.php");
       $themelist[$dirnum] =  "<option value=\"".$file."\">".$ThemeInfo['ThemeName']."</option>";
	   ++$dirnum;
   } } }
   closedir($handle); asort($themelist);
   $themenum=count($themelist); $themei=0; 
   while ($themei < $themenum) {
   echo $themelist[$themei]."\n";
   ++$themei; }
} ?></select></td>
</tr><tr style="text-align: left;">
	<td style="width: 40%;"><label class="TextBoxLabel" for="DST">Is <span title="Daylight Savings Time">DST</span> / <span title="Summer Time">ST</span> on or off:</label></td>
	<td style="width: 60%;"><select id="DST" name="DST" class="TextBox"><?php echo "\n" ?>
<?php if($User1DST=="off"||$User1DST!="on") { ?>
<option selected="selected" value="off">off</option><?php echo "\n" ?><option value="on">on</option>
<?php } if($User1DST=="on") { ?>
<option selected="selected" value="on">on</option><?php echo "\n" ?><option value="off">off</option>
<?php } echo "\n" ?></select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="settings" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Save" />
<input class="Button" type="reset" />
</td></tr></table>
</form></td>
</tr>
<tr id="ProfileEnd" class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table>
</div>
<?php @mysql_free_result($result); }
if($_POST['update']=="now") {
if($_POST['act']=="settings"&&
	$_SESSION['UserGroup']!=$Settings['GuestGroup']) {
	$NewDay=GMTimeStamp();
	$NewIP=$_SERVER['REMOTE_ADDR'];
	$querynewskin = query("update ".$Settings['sqltable']."members set UseTheme='%s',TimeZone='%s',DST='%s',LastActive='%s',IP='%s' WHERE id=%i", array($_POST['skin'],$_POST['YourOffSet'],$_POST['DST'],$NewDay,$NewIP,$_SESSION['UserID']));
	mysql_query($querynewskin); } } }
if($_GET['act']=="profile") {
if($_POST['update']!="now") {
$query = query("select * from ".$Settings['sqltable']."members where `id`=%i", array($_SESSION['UserID']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
$i=0;
$YourID=mysql_result($result,$i,"id");
$User1Interests=mysql_result($result,$i,"Interests"); 
$User1Title=mysql_result($result,$i,"Title");
$User1Website=mysql_result($result,$i,"Website"); 
$User1Gender=mysql_result($result,$i,"Gender");
$User1TimeZone=mysql_result($result,$i,"TimeZone"); 
$User1DST=mysql_result($result,$i,"DST");
$profileact = url_maker($exfile['profile'],$Settings['file_ext'],"act=profile",$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']);
$profiletitle = " ".$ThemeSet['TitleDivider']." Profile Editor";
?>
<div class="Table1Border">
<table class="Table1" style="width: 100%;">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo $profileact; ?>">Profile Editer</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr id="ProfileTitle" class="TableRow2">
<th class="TableRow2">Profile Editor</th>
</tr>
<tr class="TableRow3" id="ProfileEditor">
<td class="TableRow3">
<form method="post" action="<?php echo $profileact; ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 40%;"><label class="TextBoxLabel" for="Interests">Your Interests</label></td>
	<td style="width: 60%;"><input type="text" class="TextBox" name="Interests" id="Interests" value="<?php echo $User1Interests; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 40%;"><label class="TextBoxLabel" for="Title">Your Title</label></td>
	<td style="width: 60%;"><input type="text" class="TextBox" name="Title" id="Title" value="<?php echo $User1Title; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 40%;"><label class="TextBoxLabel" for="Website">Your Website</label></td>
	<td style="width: 60%;"><input type="text" class="TextBox" name="Website" id="Website" value="<?php echo $User1Website; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 40%;"><label class="TextBoxLabel" for="YourOffSet">Your TimeZone:</label></td>
	<td style="width: 60%;"><select id="YourOffSet" name="YourOffSet" class="TextBox">
<option selected="selected" value="<?php echo $User1TimeZone; ?>">Old Value (<?php echo $User1TimeZone.":00 hours"; ?>)</option>
<?php
$plusi = 1; $minusi = 12;
$plusnum = 13; $minusnum = 0;
while ($minusi > $minusnum) {
echo "<option value=\"-".$minusi."\">GMT - ".$minusi.":00 hours</option>\n";
--$minusi; }
?>
<option value="0">GMT +/- 0:00 hours</option>
<?php
while ($plusi < $plusnum) {
echo "<option value=\"".$plusi."\">GMT + ".$plusi.":00 hours</option>\n";
++$plusi; }
?></select></td>
</tr><tr style="text-align: left;">
	<td style="width: 40%;"><label class="TextBoxLabel" for="YourGender">Your Gender:</label></td>
	<td style="width: 60%;"><select id="YourGender" name="YourGender" class="TextBox">
<option selected="selected" value="<?php echo $User1Gender; ?>">Old Value (<?php echo $User1Gender; ?>)</option>
<option value="Male">Male</option>
<option value="Female">Female</option>
<option value="Unknow">Unknow</option>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 40%;"><label class="TextBoxLabel" for="DST">Is <span title="Daylight Savings Time">DST</span> / <span title="Summer Time">ST</span> on or off:</label></td>
	<td style="width: 60%;"><select id="DST" name="DST" class="TextBox"><?php echo "\n" ?>
<?php if($User1DST=="off"||$User1DST!="on") { ?>
<option selected="selected" value="off">off</option><?php echo "\n" ?><option value="on">on</option>
<?php } if($User1DST=="on") { ?>
<option selected="selected" value="on">on</option><?php echo "\n" ?><option value="off">off</option>
<?php } echo "\n" ?></select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="profile" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Save" />
<input class="Button" type="reset" />
</td></tr></table>
</form></td>
</tr>
<tr id="ProfileEnd" class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table>
</div>
<?php @mysql_free_result($result); }
if($_POST['update']=="now") {
if($_POST['act']=="profile"&&
	$_SESSION['UserGroup']!=$Settings['GuestGroup']) {
	$_POST['Interests'] = htmlentities($_POST['Interests'], ENT_QUOTES);
	$_POST['Interests'] = @remove_spaces($_POST['Interests']);
	$_POST['Title'] = htmlentities($_POST['Title'], ENT_QUOTES);
	$_POST['Title'] = @remove_spaces($_POST['Title']);
	$_POST['Website'] = htmlentities($_POST['Website'], ENT_QUOTES);
	$_POST['Website'] = @remove_spaces($_POST['Website']);
	$_SESSION['UserTimeZone'] = $_POST['YourOffSet'];
	$_SESSION['UserDST'] = $_POST['DST'];
	$NewDay=GMTimeStamp();
	$NewIP=$_SERVER['REMOTE_ADDR'];
	$querynewprofile = query("update ".$Settings['sqltable']."members set Interests='%s',Title='%s',Website='%s',TimeZone='%s',Gender='%s',DST='%s',LastActive='%s',IP='%s' WHERE id=%i", array($_POST['Interests'],$_POST['Title'],$_POST['Website'],$_POST['YourOffSet'],$_POST['YourGender'],$_POST['DST'],$NewDay,$NewIP,$_SESSION['UserID']));
	mysql_query($querynewprofile); } } }
if($_GET['act']=="userinfo") {
if($_POST['update']!="now") {
$query = query("select * from ".$Settings['sqltable']."members where `id`=%i", array($_SESSION['UserID']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
$i=0;
$YourID=mysql_result($result,$i,"id");
$User1Email=mysql_result($result,$i,"Email"); 
$userinfoact = url_maker($exfile['profile'],$Settings['file_ext'],"act=userinfo",$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']);
$profiletitle = " ".$ThemeSet['TitleDivider']." User Info Editer";
?>
<div class="Table1Border">
<table class="Table1" style="width: 100%;">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo $userinfoact; ?>">User Info Editer</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr id="ProfileTitle" class="TableRow2">
<th class="TableRow2">User Info Editer</th>
</tr>
<tr class="TableRow3" id="UserInfoEditor">
<td class="TableRow3">
<form method="post" action="<?php echo $userinfoact; ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 40%;"><label class="TextBoxLabel" for="OldPass">Insert old Password:</label></td>
	<td style="width: 60%;"><input type="password" class="TextBox" name="OldPass" size="20" id="OldPass" maxlength="30" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 40%;"><label class="TextBoxLabel" for="Password">Insert a Password:</label></td>
	<td style="width: 60%;"><input type="password" class="TextBox" name="Password" size="20" id="Password" maxlength="30" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 40%;"><label class="TextBoxLabel" for="RePassword">ReInsert a Password:</label></td>
	<td style="width: 60%;"><input type="password" class="TextBox" name="RePassword" size="20" id="RePassword" maxlength="30" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 40%;"><label class="TextBoxLabel" for="Email">Insert Your Email:</label></td>
	<td style="width: 60%;"><input type="text" class="TextBox" name="Email" size="20" id="Email" value="<?php echo $User1Email; ?>" /></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="userinfo" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Save" />
<input class="Button" type="reset" />
</td></tr></table>
</form></td>
</tr>
<tr id="ProfileEnd" class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table>
</div>
<?php @mysql_free_result($result); }
if($_POST['update']=="now") {
if($_POST['act']=="userinfo"&&
	$_SESSION['UserGroup']!=$Settings['GuestGroup']) {
	$query = query("select * from ".$Settings['sqltable']."members where `id`=%i", array($_SESSION['UserID']));
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	$i=0;
	$OldPassword=mysql_result($result,$i,"Password");
	$OldHashType=mysql_result($result,$i,"HashType");
	$OldJoined=mysql_result($result,$i,"Joined");
	$OldSalt=mysql_result($result,$i,"Salt");
	$UpdateHash = false; $NewSalt = salt_hmac(); 
if($OldHashType=="ODFH") { 
	$YourPassword = sha1(md5($_POST['OldPass']));
	$NewPassword = b64e_hmac($_POST['Password'],$OldJoined,$NewSalt,"sha1"); }
if($OldHashType=="DF4H") { 
	$YourPassword = b64e_hmac($_POST['OldPass'],$OldJoined,$OldSalt,"sha1");
	$NewPassword = b64e_hmac($_POST['Password'],$OldJoined,$NewSalt,"sha1"); }
if($OldHashType=="iDBH"&&$UpdateHash!=true) { 
	$YourPassword = b64e_hmac($_POST['OldPass'],$OldJoined,$OldSalt,"sha1");
	$NewPassword = b64e_hmac($_POST['Password'],$OldJoined,$NewSalt,"sha1"); }
if($YourPassword!=$OldPassword) { $Error="Yes"; ?>
<div class="TableMessage">Your old Password did not match.<br />&nbsp;</div>
<?php } if(strlen($_POST['Password'])=="30") { $Error="Yes"; ?>
<div class="TableMessage">Your password is too big.<br />&nbsp;</div>
<?php } if(strlen($_POST['OldPass'])=="30") { $Error="Yes"; ?>
<div class="TableMessage">Your old password is too big.<br />&nbsp;</div>
<?php } if ($_POST['Password']!=$_POST['RePassword']) { $Error="Yes";  ?>
<div class="TableMessage">Your passwords did not match.<br />&nbsp;</div>
<?php }
	$NewDay=GMTimeStamp();
	$NewIP=$_SERVER['REMOTE_ADDR'];
	if ($Error!="Yes") {
	setcookie("SessPass", $NewPassword, time() + (7 * 86400), $basedir);
	$_POST['Email'] = @remove_spaces($_POST['Email']);
	$querynewuserinfo = query("update ".$Settings['sqltable']."members set Password='%s',HashType='iDBH',Email='%s',LastActive='%s',IP='%s',Salt='%s' WHERE id=%i", array($NewPassword,$_POST['Email'],$NewDay,$NewIP,$NewSalt,$_SESSION['UserID']));
	mysql_query($querynewuserinfo); } } } }
?>
<?php if($_POST['update']=="now"&&$_GET['act']!=null) {
	$profiletitle = " - Updating Settings"; ?>
</td></tr>
<tr id="ProfileTitleEnd" class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr></table></div><?php } ?>
</td></tr>
</table>