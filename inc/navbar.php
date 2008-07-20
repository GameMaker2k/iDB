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

    $FileInfo: navbar.php - Last Update: 07/20/2008 SVN 169 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="navbar.php"||$File3Name=="/navbar.php") {
	require('index.php');
	exit(); }
if($_SESSION['UserGroup']!=$Settings['GuestGroup']||$GroupInfo['CanPM']=="yes") {
$pmquery1 = query("SELECT * FROM `".$Settings['sqltable']."messenger` WHERE `PMSentID`=%i AND `Read`=0", array($_SESSION['UserID']));
$pmresult1=mysql_query($pmquery1);
$PMNumber=mysql_num_rows($pmresult1);
@mysql_free_result($pmresult1); /*
$pmquery2 = query("SELECT * FROM `".$Settings['sqltable']."messenger` WHERE `SenderID`=%i AND `Read`=0", array($_SESSION['UserID']));
$pmresult2=mysql_query($pmquery2);
$SentPMNumber=mysql_num_rows($pmresult2);
@mysql_free_result($pmresult2); */ }
if($ThemeSet['LogoStyle']==null) { $logostyle = ""; }
if($ThemeSet['LogoStyle']!=null) { $logostyle = "style=\"".$ThemeSet['LogoStyle']."\" "; }
?>
<div class="NavBorder">
<table id="NavBarTable" class="NavBar1">
<tr class="NavBar2">
<td id="NavBarLogo" class="NavBar2"><?php echo $ThemeSet['PreLogo']; ?>
<a <?php echo $logostyle; ?>title="<?php echo $Settings['board_name'].$idbpowertitle; ?>" href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">
<?php echo $ThemeSet['Logo']; ?></a>
<?php echo $ThemeSet['SubLogo']; ?></td>
</tr>
<tr class="NavBar3">
<td id="NavBarLinks" class="NavBar3">
<span style="float: left;">&nbsp;<?php if($_SESSION['UserGroup']==$Settings['GuestGroup']) {?>Welcome Guest ( <a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Log in</a><?php echo $ThemeSet['LineDivider']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=signup",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Register</a> )
<?php } if($_SESSION['UserGroup']!=$Settings['GuestGroup']) { ?>Logged as: <a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$_SESSION['UserID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>"><?php echo $_SESSION['MemberName']; ?></a> ( <a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=logout",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Log out</a><?php if($GroupInfo['HasAdminCP']=="yes") { ?><?php echo $ThemeSet['LineDivider']; ?><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Admin CP</a><?php } ?> )<?php } ?></span>
<span style="float: right;">
<?php
	if($Settings['enable_search']===true&&
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
<div>&nbsp;</div>