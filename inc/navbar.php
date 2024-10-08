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

    $FileInfo: navbar.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "navbar.php" || $File3Name == "/navbar.php") {
    require('index.php');
    exit();
}
if (isset($Settings['sqldb']) && ($_SESSION['UserGroup'] != $Settings['GuestGroup'] || $GroupInfo['CanPM'] == "yes")) {
    $PMNumber = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."messenger\" WHERE \"ReciverID\"=%i AND \"Read\"=0", array($_SESSION['UserID'])), $SQLStat);
    $pmquery1 = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."messenger\" WHERE \"ReciverID\"=%i AND \"Read\"=0", array($_SESSION['UserID']));
    $pmresult1 = sql_query($pmquery1, $SQLStat);
    sql_free_result($pmresult1); /*
$SentPMNumber=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."messenger\" WHERE \"SenderID\"=%i AND \"Read\"=0", array($_SESSION['UserID'])), $SQLStat);
$pmquery2 = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."messenger\" WHERE \"SenderID\"=%i AND \"Read\"=0", array($_SESSION['UserID']));
$pmresult2=sql_query($pmquery2,$SQLStat);
sql_free_result($pmresult2); */
}
if ($ThemeSet['LogoStyle'] == null) {
    $logostyle = "";
}
if ($ThemeSet['LogoStyle'] != null) {
    $logostyle = "style=\"".$ThemeSet['LogoStyle']."\" ";
}
// Am I hidden from everyone
if (isset($Settings['sqldb']) && $_SESSION['UserGroup'] != $Settings['GuestGroup']) {
    $PreAmIHidden = GetUserName($_SESSION['UserID'], $Settings['sqltable'], $SQLStat);
    $AmIHidden = $PreAmIHidden['Hidden'];
}
// Hide me from everyone! >_> ^_^ <_<
$NavBarCurMonth = $usercurtime->format("m");
$NavBarCurYear = $usercurtime->format("Y");
$NavBarCurDate = $NavBarCurMonth.$NavBarCurYear;
if (!isset($idbpowertitle)) {
    $idbpowertitle = null;
}
?>
<div class="NavBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="NavBarRow1">
<span class="NavBarSpan1">
<?php echo $ThemeSet['PreLogo']; ?>
<a <?php echo $logostyle; ?>title="<?php echo $Settings['board_name'].$idbpowertitle; ?>" href="<?php echo url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index']); ?>">
<?php echo $ThemeSet['Logo']; ?></a>
<?php echo $ThemeSet['SubLogo']; ?>
</span></div>
<?php } ?>
<table id="NavBarTable" class="NavBar">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="NavBarRow1">
<td id="NavBarLogo" class="NavBarColumn1"><?php echo $ThemeSet['PreLogo']; ?>
<a <?php echo $logostyle; ?>title="<?php echo $Settings['board_name'].$idbpowertitle; ?>" href="<?php echo url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index']); ?>">
<?php echo $ThemeSet['Logo']; ?></a>
<?php echo $ThemeSet['SubLogo']; ?></td>
</tr><?php } ?>
<tr class="NavBarRow2">
<td id="NavBarLinks" class="NavBarColumn2">
<span style="float: left;">&#160;<?php if (isset($Settings['sqldb']) && $_SESSION['UserGroup'] == $Settings['GuestGroup']) {
    if (!isset($_SESSION['GuestName'])) { ?>Welcome Guest
<?php } if (isset($_SESSION['GuestName'])) { ?>Welcome <?php echo $_SESSION['GuestName'];
} ?> ( <a href="<?php echo url_maker($exfile['member'], $Settings['file_ext'], "act=login", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member']); ?>">Log in</a><?php echo $ThemeSet['LineDivider']; ?><a href="<?php echo url_maker($exfile['member'], $Settings['file_ext'], "act=signup", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member']); ?>">Register</a> )
<?php } if (isset($Settings['sqldb']) && $_SESSION['UserGroup'] != $Settings['GuestGroup']) { ?>Logged as: <?php if ($_SESSION['UserID'] > 0 && $AmIHidden == "no") { ?><a href="<?php echo url_maker($exfile['member'], $Settings['file_ext'], "act=view&id=".$_SESSION['UserID'], $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member']); ?>"><?php } if ($_SESSION['UserID'] < 0 || $AmIHidden == "yes") {
    echo "<span>";
} echo $_SESSION['MemberName']; ?><?php if ($_SESSION['UserID'] > 0 && $AmIHidden == "no") { ?></a><?php } if ($_SESSION['UserID'] < 0 || $AmIHidden == "yes") {
    echo "</span>";
} ?> ( <a href="<?php echo url_maker($exfile['member'], $Settings['file_ext'], "act=logout", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member']); ?>">Log out</a><?php if ($GroupInfo['HasAdminCP'] == "yes") { ?><?php echo $ThemeSet['LineDivider']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=view&menu=main", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Admin CP</a><?php } ?> )<?php } ?></span>
<span style="float: right;">
<?php
    if ($Settings['enable_search'] == "on" &&
    $GroupInfo['CanSearch'] == "yes") { ?>
<a href="<?php echo url_maker($exfile['search'], $Settings['file_ext'], "act=topics", $Settings['qstr'], $Settings['qsep'], $prexqstr['search'], $exqstr['search']); ?>">Search</a><?php echo $ThemeSet['LineDivider'];
    }
if (isset($Settings['sqldb']) && $_SESSION['UserGroup'] != $Settings['GuestGroup']) {
    if ($GroupInfo['CanEditProfile'] == "yes") { ?>
<a href="<?php echo url_maker($exfile['profile'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['profile'], $exqstr['profile']); ?>">Profile</a><?php echo $ThemeSet['LineDivider'];
    }
    if ($GroupInfo['CanPM'] == "yes") { ?>
<a href="<?php echo url_maker($exfile['messenger'], $Settings['file_ext'], "act=view&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstr['messenger'], $exqstr['messenger']); ?>" title="<?php echo "You have ".$PMNumber." new messages."; ?>">Mailbox</a><?php echo $ThemeSet['LineDivider']; ?><?php }
    } if ($File3Name != "install.php" && $File3Name != "/install.php") { ?>
<a href="<?php echo url_maker($exfile['member'], $Settings['file_ext'], "act=list&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member']); ?>">Members</a><?php echo $ThemeSet['LineDivider']; ?>
<a href="<?php echo url_maker($exfile['calendar'], $Settings['file_ext'], "act=view&caldate=".$NavBarCurDate, $Settings['qstr'], $Settings['qsep'], $prexqstr['calendar'], $exqstr['calendar']); ?>">Calendar</a><?php if (isset($Settings['weburl'])) {
    echo $ThemeSet['LineDivider']; ?>
<a href="<?php echo $Settings['weburl']; ?>">Homepage</a><?php }
} ?>&#160;</span>
</td></tr>
</table></div>
<div class="DivNavBar">&#160;</div>
