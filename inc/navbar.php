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

    $FileInfo: navbar.php - Last Update: 12/10/2009 SVN 391 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="navbar.php"||$File3Name=="/navbar.php") {
	require('index.php');
	exit(); }
if($_SESSION['UserGroup']!=$Settings['GuestGroup']||$GroupInfo['CanPM']=="yes") {
$pmquery1 = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."messenger\" WHERE \"ReciverID\"=%i AND \"Read\"=0", array($_SESSION['UserID']));
$pmresult1=sql_query($pmquery1,$SQLStat);
$PMNumber=sql_num_rows($pmresult1);
sql_free_result($pmresult1); /*
$pmquery2 = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."messenger\" WHERE \"SenderID\"=%i AND \"Read\"=0", array($_SESSION['UserID']));
$pmresult2=sql_query($pmquery2,$SQLStat);
$SentPMNumber=sql_num_rows($pmresult2);
sql_free_result($pmresult2); */ }
if($ThemeSet['LogoStyle']==null) { $logostyle = ""; }
if($ThemeSet['LogoStyle']!=null) { $logostyle = "style=\"".$ThemeSet['LogoStyle']."\" "; }
// Am I hidden from everyone
if($_SESSION['UserGroup']!=$Settings['GuestGroup']) {
$PreAmIHidden = GetUserName($_SESSION['UserID'],$Settings['sqltable'],$SQLStat);
$AmIHidden = $PreAmIHidden['Hidden']; }
// Hide me from everyone! >_> ^_^ <_< 
?>
<div class="NavBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="NavBarRow1">
<span class="NavBarSpan1">
<?php echo $ThemeSet['PreLogo']; ?>
<a <?php echo $logostyle; ?>title="<?php echo $Settings['board_name'].$idbpowertitle; ?>" href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">
<?php echo $ThemeSet['Logo']; ?></a>
<?php echo $ThemeSet['SubLogo']; ?>
</span></div>
<?php } ?>
<table id="NavBarTable" class="NavBar">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="NavBarRow1">
<td id="NavBarLogo" class="NavBarColumn1"><?php echo $ThemeSet['PreLogo']; ?>
<a <?php echo $logostyle; ?>title="<?php echo $Settings['board_name'].$idbpowertitle; ?>" href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">
<?php echo $ThemeSet['Logo']; ?></a>
<?php echo $ThemeSet['SubLogo']; ?></td>
</tr><?php } ?>
<tr class="NavBarRow2">
<td id="NavBarLinks" class="NavBarColumn2">
<span style="float: left;">&nbsp;<?php if($_SESSION['UserGroup']==$Settings['GuestGroup']) {?>Welcome Guest ( <a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Log in</a><?php echo $ThemeSet['LineDivider']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=signup",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Register</a> )
<?php } if($_SESSION['UserGroup']!=$Settings['GuestGroup']) { ?>Logged as: <?php if($_SESSION['UserID']>0&&$AmIHidden=="no") { ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$_SESSION['UserID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>"><?php } if($_SESSION['UserID']<0||$AmIHidden=="yes") { echo "<span>"; } echo $_SESSION['MemberName']; ?><?php if($_SESSION['UserID']>0&&$AmIHidden=="no") { ?></a><?php } if($_SESSION['UserID']<0||$AmIHidden=="yes") { echo "</span>"; } ?> ( <a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=logout",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Log out</a><?php if($GroupInfo['HasAdminCP']=="yes") { ?><?php echo $ThemeSet['LineDivider']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=main",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Admin CP</a><?php } ?> )<?php } ?></span>
<span style="float: right;">
<?php
	if($Settings['enable_search']=="on"&&
	$GroupInfo['CanSearch']=="yes") { ?>
<a href="<?php echo url_maker($exfile['search'],$Settings['file_ext'],"act=topics",$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search']); ?>">Search</a><?php echo $ThemeSet['LineDivider']; }
	if($_SESSION['UserGroup']!=$Settings['GuestGroup']) { 
	if($GroupInfo['CanEditProfile']=="yes") { ?>
<a href="<?php echo url_maker($exfile['profile'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['profile'],$exqstr['profile']); ?>">Profile</a><?php echo $ThemeSet['LineDivider']; } 
		if($GroupInfo['CanPM']=="yes") { ?>
<a href="<?php echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=view&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); ?>" title="<?php echo "You have ".$PMNumber." new messages."; ?>">MailBox</a><?php echo $ThemeSet['LineDivider']; ?><?php } } ?>
<a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=list&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Members</a><?php echo $ThemeSet['LineDivider']; ?>
<a href="<?php echo url_maker($exfile['calendar'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['calendar'],$exqstr['calendar']); ?>">Calendar</a><?php if(isset($Settings['weburl'])) { echo $ThemeSet['LineDivider']; ?>
<a href="<?php echo $Settings['weburl']; ?>">Homepage</a><?php } ?>&nbsp;</span>
</td></tr>
</table></div>
<div class="DivNavBar">&nbsp;</div>
