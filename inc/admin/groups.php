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

    $FileInfo: groups.php - Last Update: 8/26/2024 SVN 1048 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "groups.php" || $File3Name == "/groups.php") {
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
$Error = null;
$errorstr = null;
?>
<table class="Table3">
<tr style="width: 100%; vertical-align: top;">
	<td style="width: 15%; vertical-align: top;">
<?php
require($SettDir['admin'].'table.php');
?>
</td>
	<td style="width: 85%; vertical-align: top;">
<?php if ($_GET['act'] == "addgroup" && $_POST['update'] != "now") {
    $admincptitle = " ".$ThemeSet['TitleDivider']." Adding new Group";
    ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=addgroup", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Group Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=addgroup", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Group Manager</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&#160;Adding new Group: </span>
<span style="float: right;">&#160;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=addgroup", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="GroupName">Insert name for group:</label></td>
	<td style="width: 50%;"><input type="text" name="GroupName" class="TextBox" id="GroupName" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="GroupPerm">Copy Permissions from:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="GroupPerm" id="GroupPerm">
	<option selected="selected" value="0">none</option>
<?php
if ($Settings['sqltype'] == "mysqli" ||
        $Settings['sqltype'] == "mysqli_prepare" ||
        $Settings['sqltype'] == "pdo_mysql" ||
        $Settings['sqltype'] == "pgsql" ||
        $Settings['sqltype'] == "pgsql_prepare" ||
        $Settings['sqltype'] == "pdo_pgsql" ||
        $Settings['sqltype'] == "sqlite3" ||
        $Settings['sqltype'] == "sqlite3_prepare" ||
        $Settings['sqltype'] == "pdo_sqlite3" ||
        $Settings['sqltype'] == "sqlsrv_prepare" ||
        $Settings['sqltype'] == "pdo_sqlsrv") {
    $getperidnum = sql_count_rows(sql_pre_query("SELECT COUNT(DISTINCT \"PermissionID\") AS cnt FROM \"".$Settings['sqltable']."permissions\"", null), $SQLStat);
    $getperidq = sql_pre_query("SELECT DISTINCT \"PermissionID\" FROM \"".$Settings['sqltable']."permissions\"", null);
}
    if ($Settings['sqltype'] == "cubrid" ||
        $Settings['sqltype'] == "cubrid_prepare" ||
        $Settings['sqltype'] == "pdo_cubrid") {
        $getperidnum = sql_count_rows(sql_pre_query("SELECT COUNT(DISTINCT \"permissionid\") AS cnt FROM \"".$Settings['sqltable']."permissions\"", null), $SQLStat);
        $getperidq = sql_pre_query("SELECT DISTINCT \"permissionid\" FROM \"".$Settings['sqltable']."permissions\"", null);
    }
    $getperidr = sql_query($getperidq, $SQLStat);
    $getperidi = 0;
    while ($getperidi < $getperidnum) {
        $getperidr_array = sql_fetch_assoc($getperidr);
        $getperidID = $getperidr_array['PermissionID'];
        $getperidnum2 = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."permissions\" WHERE \"PermissionID\"=%i ORDER BY \"ForumID\" ASC", array($getperidID)), $SQLStat);
        $getperidq2 = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."permissions\" WHERE \"PermissionID\"=%i ORDER BY \"ForumID\" ASC", array($getperidID));
        $getperidr2 = sql_query($getperidq2, $SQLStat);
        $getperidr2_array = sql_fetch_assoc($getperidr2);
        $getperidName = $getperidr2_array['Name'];
        sql_free_result($getperidr2);
        ?>
	<option value="<?php echo $getperidID; ?>"><?php echo $getperidName; ?></option>
<?php ++$getperidi;
    }
    sql_free_result($getperidr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="PermissionID">Permission ID:</label></td>
	<td style="width: 50%;"><input type="number" name="PermissionID" class="TextBox" id="PermissionID" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NamePrefix">Name Prefix:</label></td>
	<td style="width: 50%;"><input type="text" name="NamePrefix" class="TextBox" id="NamePrefix" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NameSuffix">Name Subfix:</label></td>
	<td style="width: 50%;"><input type="text" name="NameSuffix" class="TextBox" id="NameSuffix" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewBoard">Can View Board:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewBoard" id="CanViewBoard">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewOffLine">Can View OffLine Board:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewOffLine" id="CanViewOffLine">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanEditProfile">Can Edit Profile:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanEditProfile" id="CanEditProfile">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanAddEvents">Can Add Events:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanAddEvents" id="CanAddEvents">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanPM">Can PM:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanPM" id="CanPM">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanSearch">Can Search:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanSearch" id="CanSearch">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanDoHTML">Can DoHTML:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanDoHTML" id="CanDoHTML">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanUseBBTags">Can use BBTags:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanUseBBTags" id="CanUseBBTags">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="FloodControl">Flood Control in seconds:</label></td>
	<td style="width: 50%;"><input type="text" name="FloodControl" class="TextBox" id="FloodControl" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="SearchFlood">Search Flood Control in seconds:</label></td>
	<td style="width: 50%;"><input type="text" name="SearchFlood" class="TextBox" id="SearchFlood" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="PromoteTo">Promote To Group:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="PromoteTo" id="PromoteTo">
	<option selected="selected" value="0">none</option>
<?php
    $ai = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."groups\" ORDER BY \"id\" ASC", null), $SQLStat);
    $fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" ORDER BY \"id\" ASC", null);
    $fr = sql_query($fq, $SQLStat);
    $fi = 0;
    while ($fi < $ai) {
        $fr_array = sql_fetch_assoc($fr);
        $ProGroupID = $fr_array['id'];
        $ProGroupName = $fr_array['Name'];
        ?>
	<option value="<?php echo $ProGroupID; ?>"><?php echo $ProGroupName; ?></option>
<?php ++$fi;
    }
    sql_free_result($fr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="PromotePosts">Amount of Posts needed:</label></td>
	<td style="width: 50%;"><input type="number" name="PromotePosts" class="TextBox" id="PromotePosts" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="PromoteKarma">Amount of Karma needed:</label></td>
	<td style="width: 50%;"><input type="number" name="PromoteKarma" class="TextBox" id="PromoteKarma" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DemoteTo">Demote To Group:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DemoteTo" id="DemoteTo">
	<option selected="selected" value="0">none</option>
<?php
    $ai = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."groups\" ORDER BY \"id\" ASC", null), $SQLStat);
    $fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" ORDER BY \"id\" ASC", null);
    $fr = sql_query($fq, $SQLStat);
    $fi = 0;
    while ($fi < $ai) {
        $fr_array = sql_fetch_assoc($fr);
        $ProGroupID = $fr_array['id'];
        $ProGroupName = $fr_array['Name'];
        ?>
	<option value="<?php echo $ProGroupID; ?>"><?php echo $ProGroupName; ?></option>
<?php ++$fi;
    }
    sql_free_result($fr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DemotePosts">Amount of Posts needed:</label></td>
	<td style="width: 50%;"><input type="number" name="DemotePosts" class="TextBox" id="DemotePosts" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DemoteKarma">Amount of Karma needed:</label></td>
	<td style="width: 50%;"><input type="number" name="DemoteKarma" class="TextBox" id="DemoteKarma" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="HasModCP">Can view Mod CP:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="HasModCP" id="HasModCP">
	<option selected="selected" value="off">no</option>
	<option value="on">yes</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="HasAdminCP">Can view Admin CP:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="HasAdminCP" id="HasAdminCP">
	<option selected="selected" value="off">no</option>
	<option value="on">yes</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewIPAddress">Can view IP Address:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewIPAddress" id="CanViewIPAddress">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewUserAgent">Can view user agent:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewUserAgent" id="CanViewUserAgent">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewAnonymous">Can view anonymous users:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewAnonymous" id="CanViewAnonymous">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ViewDBInfo">Can view Database info:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ViewDBInfo" id="ViewDBInfo">
	<option selected="selected" value="off">no</option>
	<option value="on">yes</option>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="addgroup" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Add Group" name="Apply_Changes" />
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
<?php } if ($_POST['act'] == "addgroup" && $_POST['update'] == "now" && $_GET['act'] == "addgroup") {
    $_POST['GroupName'] = stripcslashes(htmlspecialchars($_POST['GroupName'], ENT_QUOTES, $Settings['charset']));
    //$_POST['GroupName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['GroupName']);
    $_POST['GroupName'] = remove_spaces($_POST['GroupName']);
    $_POST['NamePrefix'] = stripcslashes(htmlspecialchars($_POST['NamePrefix'], ENT_QUOTES, $Settings['charset']));
    //$_POST['NamePrefix'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['NamePrefix']);
    $_POST['NamePrefix'] = remove_spaces($_POST['NamePrefix']);
    $_POST['NameSuffix'] = stripcslashes(htmlspecialchars($_POST['NameSuffix'], ENT_QUOTES, $Settings['charset']));
    //$_POST['NameSuffix'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['NameSuffix']);
    $_POST['NameSuffix'] = remove_spaces($_POST['NameSuffix']);
    $name_check = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."groups\" WHERE \"Name\"='%s'", array($_POST['GroupName'])), $SQLStat);
    $id_check = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."permissions\" WHERE \"PermissionID\"=%i LIMIT 1", array($_POST['PermissionID'])), $SQLStat);
    /*$sql_name_check = sql_query(sql_pre_query("SELECT \"Name\" FROM \"".$Settings['sqltable']."groups\" WHERE \"Name\"='%s'", array($_POST['GroupName'])),$SQLStat);
    sql_free_result($sql_name_check);
    $sql_id_check = sql_query(sql_pre_query("SELECT \"id\" FROM \"".$Settings['sqltable']."permissions\" WHERE \"PermissionID\"=%i LIMIT 1", array($_POST['PermissionID'])),$SQLStat);
    sql_free_result($sql_id_check);*/
    $errorstr = "";
    if (!isset($_POST['PromotePosts'])) {
        $_POST['PromotePosts'] = 0;
    }
    if ($_POST['PromotePosts'] == null ||
        !is_numeric($_POST['PromotePosts'])) {
        $_POST['PromotePosts'] = 0;
    }
    if (!isset($_POST['PromoteKarma'])) {
        $_POST['PromoteKarma'] = 0;
    }
    if ($_POST['PromoteKarma'] == null ||
        !is_numeric($_POST['PromoteKarma'])) {
        $_POST['NPromoteKarma'] = 0;
    }
    if (!isset($_POST['DemotePosts'])) {
        $_POST['DemotePosts'] = 0;
    }
    if ($_POST['DemotePosts'] == null ||
        !is_numeric($_POST['DemotePosts'])) {
        $_POST['DemotePosts'] = 0;
    }
    if (!isset($_POST['DemoteKarma'])) {
        $_POST['DemoteKarma'] = 0;
    }
    if ($_POST['DemoteKarma'] == null ||
        !is_numeric($_POST['DemoteKarma'])) {
        $_POST['NDemoteKarma'] = 0;
    }
    if ($_POST['GroupName'] == null ||
        $_POST['GroupName'] == "ShowMe") {
        $Error = "Yes";
        $errorstr = $errorstr."You need to enter a forum name.<br />\n";
    }
    if ($id_check > 0) {
        $Error = "Yes";
        $errorstr = $errorstr."This ID number is already used.<br />\n";
    }
    if ($name_check > 0) {
        $Error = "Yes";
        $errorstr = $errorstr."This Group Name is already used.<br />\n";
    }
    if (pre_strlen($_POST['GroupName']) > "150") {
        $Error = "Yes";
        $errorstr = $errorstr."Your Group Name is too big.<br />\n";
    }
    if ($Error != "Yes") {
        redirect("refresh", $rbasedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view&menu=groups", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false), "4");
        $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
        $query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."groups\" (\"Name\", \"PermissionID\", \"NamePrefix\", \"NameSuffix\", \"CanViewBoard\", \"CanViewOffLine\", \"CanEditProfile\", \"CanAddEvents\", \"CanPM\", \"CanSearch\", \"CanExecPHP\", \"CanDoHTML\", \"CanUseBBTags\", \"CanModForum\", \"CanViewIPAddress\", \"CanViewUserAgent\", \"CanViewAnonymous\", \"FloodControl\", \"SearchFlood\", \"PromoteTo\", \"PromotePosts\", \"PromoteKarma\", \"DemoteTo\", \"DemotePosts\", \"DemoteKarma\", \"HasModCP\", \"HasAdminCP\", \"ViewDBInfo\") VALUES\n".
        "('%s', %i, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', 'no', '%s', '%s', '%s', '%s', '%s', %i, %i, %i, %i, %i, %i, %i, %i, '%s', '%s', '%s')", array($_POST['GroupName'],$_POST['PermissionID'],$_POST['NamePrefix'],$_POST['NameSuffix'],$_POST['CanViewBoard'],$_POST['CanViewOffLine'],$_POST['CanEditProfile'],$_POST['CanAddEvents'],$_POST['CanPM'],$_POST['CanSearch'],$_POST['CanDoHTML'],$_POST['CanUseBBTags'],$_POST['HasModCP'],$_POST['CanViewIPAddress'],$_POST['CanViewUserAgent'],$_POST['CanViewAnonymous'],$_POST['FloodControl'],$_POST['SearchFlood'],$_POST['PromoteTo'],$_POST['PromotePosts'],$_POST['PromoteKarma'],$_POST['DemoteTo'],$_POST['DemotePosts'],$_POST['DemoteKarma'],$_POST['HasModCP'],$_POST['HasAdminCP'],$_POST['ViewDBInfo']));
        sql_query($query, $SQLStat);
        if (!is_numeric($_POST['GroupPerm'])) {
            $_POST['GroupPerm'] = "0";
        }
        $getperidnum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."forums\" ORDER BY \"id\" ASC", null), $SQLStat);
        $getperidq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" ORDER BY \"id\" ASC", null);
        $getperidr = sql_query($getperidq, $SQLStat);
        $getperidi = 0;
        $nextperid = null;
        while ($getperidi < $getperidnum) {
            $getperidr_array = sql_fetch_assoc($getperidr);
            $getperidID = $getperidr_array['id'];
            if ($_POST['GroupPerm'] != "0") {
                $getperidnum2 = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."permissions\" WHERE \"PermissionID\"=%i AND \"ForumID\"=%i", array($_POST['GroupPerm'],$getperidID)), $SQLStat);
                $getperidq2 = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."permissions\" WHERE \"PermissionID\"=%i AND \"ForumID\"=%i", array($_POST['GroupPerm'],$getperidID));
                $getperidr2 = sql_query($getperidq2, $SQLStat);
                $getperidr2_array = sql_fetch_assoc($getperidr2);
                $PermissionNum = $getperidr2_array['id'];
                $PermissionID = $_POST['PermissionID'];
                $PermissionName = $_POST['GroupName'];
                $PermissionForumID = $getperidr2_array['ForumID'];
                $CanViewForum = $getperidr2_array['CanViewForum'];
                $CanMakePolls = $getperidr2_array['CanMakePolls'];
                $CanMakeTopics = $getperidr2_array['CanMakeTopics'];
                $CanMakeReplys = $getperidr2_array['CanMakeReplys'];
                $CanMakeReplysCT = $getperidr2_array['CanMakeReplysCT'];
                $HideEditPostInfo = $getperidr2_array['HideEditPostInfo'];
                $CanEditTopics = $getperidr2_array['CanEditTopics'];
                $CanEditTopicsCT = $getperidr2_array['CanEditTopicsCT'];
                $CanEditReplys = $getperidr2_array['CanEditReplys'];
                $CanEditReplysCT = $getperidr2_array['CanEditReplysCT'];
                $CanDeleteTopics = $getperidr2_array['CanDeleteTopics'];
                $CanDeleteTopicsCT = $getperidr2_array['CanDeleteTopicsCT'];
                $CanDeleteReplys = $getperidr2_array['CanDeleteReplys'];
                $CanDeleteReplysCT = $getperidr2_array['CanDeleteReplysCT'];
                $CanDoublePost = $getperidr2_array['CanDoublePost'];
                $CanDoublePostCT = $getperidr2_array['CanDoublePostCT'];
                $GotoEditPost = $getperidr2_array['GotoEditPost'];
                $CanCloseTopics = $getperidr2_array['CanCloseTopics'];
                $CanPinTopics = $getperidr2_array['CanPinTopics'];
                $CanExecPHP = $getperidr2_array['CanExecPHP'];
                $CanDoHTML = $getperidr2_array['CanDoHTML'];
                $CanUseBBTags = $getperidr2_array['CanUseBBTags'];
                $CanModForum = $getperidr2_array['CanModForum'];
                $CanReportPost = $getperidr2_array['CanReportPost'];
                sql_free_result($getperidr2);
            }
            if ($getperidi == 0) {
                if ($_POST['GroupPerm'] == "0") {
                    $PermissionID = $_POST['PermissionID'];
                    $PermissionName = $_POST['GroupName'];
                    $query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."permissions\" (\"PermissionID\", \"Name\", \"ForumID\", \"CanViewForum\", \"CanMakePolls\", \"CanMakeTopics\", \"CanMakeReplys\", \"CanMakeReplysCT\", \"HideEditPostInfo\", \"CanEditTopics\", \"CanEditTopicsCT\", \"CanEditReplys\", \"CanEditReplysCT\", \"CanDeleteTopics\", \"CanDeleteTopicsCT\", \"CanDoublePost\", \"CanDoublePostCT\", \"GotoEditPost\", \"CanDeleteReplys\", \"CanDeleteReplysCT\", \"CanCloseTopics\", \"CanPinTopics\", \"CanExecPHP\", \"CanDoHTML\", \"CanUseBBTags\", \"CanModForum\", \"CanReportPost\") VALUES (%i, '%s', 0, 'yes', 'no', 'no', 'no', 'no', 'no, 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no')", array($PermissionID,$PermissionName));
                }
            }
            if ($_POST['GroupPerm'] == "0") {
                $PermissionID = $_POST['PermissionID'];
                $PermissionName = $_POST['GroupName'];
                $query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."permissions\" (\"PermissionID\", \"Name\", \"ForumID\", \"CanViewForum\", \"CanMakePolls\", \"CanMakeTopics\", \"CanMakeReplys\", \"CanMakeReplysCT\", \"HideEditPostInfo\", \"CanEditTopics\", \"CanEditTopicsCT\", \"CanEditReplys\", \"CanEditReplysCT\", \"CanDeleteTopics\", \"CanDeleteTopicsCT\", \"CanDoublePost\", \"CanDoublePostCT\", \"GotoEditPost\", \"CanDeleteReplys\", \"CanDeleteReplysCT\", \"CanCloseTopics\", \"CanPinTopics\", \"CanExecPHP\", \"CanDoHTML\", \"CanUseBBTags\", \"CanModForum\", \"CanReportPost\") VALUES (%i, '%s', %i, 'yes', 'no', 'no', 'no', 'no', 'no, 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no')", array($PermissionID,$PermissionName,$getperidID));
            }
            if ($_POST['GroupPerm'] != "0") {
                if ($getperidnum2 > 0) {
                    $query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."permissions\" (\"PermissionID\", \"Name\", \"ForumID\", \"CanViewForum\", \"CanMakePolls\", \"CanMakeTopics\", \"CanMakeReplys\", \"CanMakeReplysCT\", \"HideEditPostInfo\", \"CanEditTopics\", \"CanEditTopicsCT\", \"CanEditReplys\", \"CanEditReplysCT\", \"CanDeleteTopics\", \"CanDeleteTopicsCT\", \"CanDoublePost\", \"CanDoublePostCT\", \"GotoEditPost\", \"CanDeleteReplys\", \"CanDeleteReplysCT\", \"CanCloseTopics\", \"CanPinTopics\", \"CanExecPHP\", \"CanDoHTML\", \"CanUseBBTags\", \"CanModForum\", \"CanReportPost\") VALUES (%i, '%s', %i, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", array($PermissionID,$PermissionName,$getperidID,$CanViewForum,$CanMakePolls,$CanMakeTopics,$CanMakeReplys,$CanMakeReplysCT,$HideEditPostInfo,$CanEditTopics,$CanEditTopicsCT,$CanEditReplys,$CanEditReplysCT,$CanDeleteTopics,$CanDeleteTopicsCT,$CanDeleteReplys,$CanDeleteReplysCT,$CanDoublePost,$CanDoublePostCT,$GotoEditPost,$CanCloseTopics,$CanPinTopics,$CanExecPHP,$CanDoHTML,$CanUseBBTags,$CanModForum,$CanReportPost));
                }
                if ($getperidnum2 <= 0) {
                    $query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."permissions\" (\"PermissionID\", \"Name\", \"ForumID\", \"CanViewForum\", \"CanMakePolls\", \"CanMakeTopics\", \"CanMakeReplys\", \"CanMakeReplysCT\", \"HideEditPostInfo\", \"CanEditTopics\", \"CanEditTopicsCT\", \"CanEditReplys\", \"CanEditReplysCT\", \"CanDeleteTopics\", \"CanDeleteTopicsCT\", \"CanDoublePost\", \"CanDoublePostCT\", \"GotoEditPost\", \"CanDeleteReplys\", \"CanDeleteReplysCT\", \"CanCloseTopics\", \"CanPinTopics\", \"CanExecPHP\", \"CanDoHTML\", \"CanUseBBTags\", \"CanModForum\", \"CanReportPost\") VALUES (%i, '%s', %i, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no')", array($PermissionID,$PermissionName,$getperidID));
                }
            }
            sql_query($query, $SQLStat);
            ++$getperidi; /*++$nextperid;*/
        }
        sql_free_result($getperidr);
        if (!is_numeric($_POST['GroupPerm'])) {
            $_POST['GroupPerm'] = "0";
        }
        $getperidnum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."categories\" ORDER BY \"id\" ASC", null), $SQLStat);
        $getperidq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" ORDER BY \"id\" ASC", null);
        $getperidr = sql_query($getperidq, $SQLStat);
        $getperidi = 0;
        $nextperid = null;
        while ($getperidi < $getperidnum) {
            $getperidr_array = sql_fetch_assoc($getperidr);
            $getperidID = $getperidr_array['id'];
            if ($_POST['GroupPerm'] != "0") {
                $getperidnum2 = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."catpermissions\" WHERE \"PermissionID\"=%i AND \"CategoryID\"=%i", array($_POST['GroupPerm'],$getperidID)), $SQLStat);
                $getperidq2 = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."catpermissions\" WHERE \"PermissionID\"=%i AND \"CategoryID\"=%i", array($_POST['GroupPerm'],$getperidID));
                $getperidr2 = sql_query($getperidq2, $SQLStat);
                $getperidr2_array = sql_fetch_assoc($getperidr2);
                $PermissionNum = $getperidr2_array['id'];
                $PermissionID = $_POST['PermissionID'];
                $PermissionName = $_POST['GroupName'];
                $PermissionCatID = $getperidr2_array['CategoryID'];
                $CanViewCategory = $getperidr2_array['CanViewCategory'];
                sql_free_result($getperidr2);
            }
            if ($_POST['GroupPerm'] == "0") {
                $PermissionID = $_POST['PermissionID'];
                $PermissionName = $_POST['GroupName'];
                $query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."catpermissions\" (\"PermissionID\", \"Name\", \"CategoryID\", \"CanViewCategory\") VALUES (%i, '%s', %i, 'yes')", array($PermissionID,$PermissionName,$getperidID));
            }
            if ($_POST['GroupPerm'] != "0") {
                if ($getperidnum2 > 0) {
                    $query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."catpermissions\" (\"PermissionID\", \"Name\", \"CategoryID\", \"CanViewCategory\") VALUES (%i, '%s', %i, '%s')", array($PermissionID,$PermissionName,$getperidID,$CanViewCategory));
                }
                if ($getperidnum2 <= 0) {
                    $query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."catpermissions\" (\"PermissionID\", \"Name\", \"CategoryID\", \"CanViewCategory\") VALUES (%i, '%s', %i, 'yes')", array($PermissionID,$PermissionName,$getperidID));
                }
            }
            sql_query($query, $SQLStat);
            ++$getperidi; /*++$nextperid;*/
        }
        sql_free_result($getperidr);
    }
}
if ($_GET['act'] == "deletegroup" && $_POST['update'] != "now") {
    $admincptitle = " ".$ThemeSet['TitleDivider']." Deleting a Forum";
    ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=addgroup", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Group Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=addgroup", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Group Manager</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&#160;Deleting a Group: </span>
<span style="float: right;">&#160;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=deletegroup", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelID">Delete Group:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelID" id="DelID">
<?php
    $ai = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."groups\" WHERE (\"Name\"<>'%s' AND \"Name\"<>'%s' AND \"Name\"<>'%s' AND \"Name\"<>'%s') ORDER BY \"id\" ASC", array($Settings['GuestGroup'],$Settings['MemberGroup'],$Settings['ValidateGroup'],"Admin")), $SQLStat);
    $fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE (\"Name\"<>'%s' AND \"Name\"<>'%s' AND \"Name\"<>'%s' AND \"Name\"<>'%s') ORDER BY \"id\" ASC", array($Settings['GuestGroup'],$Settings['MemberGroup'],$Settings['ValidateGroup'],"Admin"));
    $fr = sql_query($fq, $SQLStat);
    $fi = 0;
    while ($fi < $ai) {
        $fr_array = sql_fetch_assoc($fr);
        $GroupID = $fr_array['id'];
        $GroupName = $fr_array['Name'];
        ?>
	<option value="<?php echo $GroupID; ?>"><?php echo $GroupName; ?></option>
<?php ++$fi;
    }
    sql_free_result($fr); ?>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="deletegroup" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Delete Group" name="Apply_Changes" />
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
<?php } if ($_GET['act'] == "deletegroup" && $_POST['update'] == "now" && $_GET['act'] == "deletegroup") {
    $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
    $prenum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i AND (\"Name\"<>'%s' AND \"Name\"<>'%s' AND \"Name\"<>'%s' AND \"Name\"<>'%s') LIMIT 1", array($_POST['DelID'],$Settings['GuestGroup'],$Settings['MemberGroup'],$Settings['ValidateGroup'],"Admin")), $SQLStat);
    $prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i AND (\"Name\"<>'%s' AND \"Name\"<>'%s' AND \"Name\"<>'%s' AND \"Name\"<>'%s') LIMIT 1", array($_POST['DelID'],$Settings['GuestGroup'],$Settings['MemberGroup'],$Settings['ValidateGroup'],"Admin"));
    $preresult = sql_query($prequery, $SQLStat);
    $preresult_array = sql_fetch_assoc($preresult);
    $GroupName = $preresult_array['Name'];
    $errorstr = "";
    $Error = null;
    if (!is_numeric($_POST['DelID'])) {
        $Error = "Yes";
        $errorstr = $errorstr."You need to enter a group ID.<br />\n";
    }
    if ($prenum > 0 && $Error != "Yes") {
        $dtquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i", array($_POST['DelID']));
        sql_query($dtquery, $SQLStat);
        $dtquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."catpermissions\" WHERE \"Name\"='%s'", array($GroupName));
        sql_query($dtquery, $SQLStat);
        $dtquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."permissions\" WHERE \"Name\"='%s'", array($GroupName));
        sql_query($dtquery, $SQLStat);
        $gquerys = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"Name\"='%s' LIMIT 1", array($Settings['MemberGroup']));
        $gresults = sql_query($gquerys, $SQLStat);
        $gresult_array = sql_fetch_assoc($gresult);
        $MemGroup = $gresults_array['id'];
        sql_free_result($gresults);
        $dtquery = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"GroupID\"=%i WHERE \"GroupID\"=%i", array($MemGroup,$_POST['DelID']));
        sql_query($dtquery, $SQLStat);
    }
}
if ($_GET['act'] == "editgroup" && $_POST['update'] != "now") {
    $admincptitle = " ".$ThemeSet['TitleDivider']." Editing a Group";
    if (!isset($_POST['id'])) {
        ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editgroup", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Group Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editgroup", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Group Manager</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&#160;Editing a Group: </span>
<span style="float: right;">&#160;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editgroup", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="id">Group to Edit:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="id" id="id">
<?php
        $ai = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."groups\" ORDER BY \"id\" ASC", null), $SQLStat);
        $fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" ORDER BY \"id\" ASC", null);
        $fr = sql_query($fq, $SQLStat);
        $fi = 0;
        while ($fi < $ai) {
            $fr_array = sql_fetch_assoc($fr);
            $GroupID = $fr_array['id'];
            $GroupName = $fr_array['Name'];
            ?>
	<option value="<?php echo $GroupID; ?>"><?php echo $GroupName; ?></option>
<?php ++$fi;
        }
        sql_free_result($fr); ?>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="editgroup" style="display: none;" />
<input type="submit" class="Button" value="Edit Group" name="Apply_Changes" />
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
<?php } if (isset($_POST['id'])) {
    $prenum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($_POST['id'])), $SQLStat);
    $prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($_POST['id']));
    $preresult = sql_query($prequery, $SQLStat);
    if ($prenum == 0) {
        redirect("location", $rbasedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false));
        sql_free_result($preresult);
        ob_clean();
        header("Content-Type: text/plain; charset=".$Settings['charset']);
        $urlstatus = 302;
        gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        session_write_close();
        die();
    }
    if ($prenum >= 1) {
        $preresult_array = sql_fetch_assoc($preresult);
        $GroupID = $preresult_array['id'];
        $GroupName = $preresult_array['Name'];
        $PermissionID = $preresult_array['PermissionID'];
        $NamePrefix = $preresult_array['NamePrefix'];
        $NameSuffix = $preresult_array['NameSuffix'];
        $CanViewBoard = $preresult_array['CanViewBoard'];
        $CanViewOffLine = $preresult_array['CanViewOffLine'];
        $CanEditProfile = $preresult_array['CanEditProfile'];
        $CanAddEvents = $preresult_array['CanAddEvents'];
        $CanPM = $preresult_array['CanPM'];
        $CanSearch = $preresult_array['CanSearch'];
        $CanDoHTML = $preresult_array['CanDoHTML'];
        $CanUseBBTags = $preresult_array['CanUseBBTags'];
        $CanViewIPAddress = $preresult_array['CanViewIPAddress'];
        $CanViewUserAgent = $preresult_array['CanViewUserAgent'];
        $CanViewAnonymous = $preresult_array['CanViewAnonymous'];
        $FloodControl = $preresult_array['FloodControl'];
        $SearchFlood = $preresult_array['SearchFlood'];
        $PromoteTo = $preresult_array['PromoteTo'];
        $PromotePosts = $preresult_array['PromotePosts'];
        $PromoteKarma = $preresult_array['PromoteKarma'];
        $DemoteTo = $preresult_array['DemoteTo'];
        $DemotePosts = $preresult_array['DemotePosts'];
        $DemoteKarma = $preresult_array['DemoteKarma'];
        $HasModCP = $preresult_array['HasModCP'];
        $HasAdminCP = $preresult_array['HasAdminCP'];
        $ViewDBInfo = $preresult_array['ViewDBInfo'];
        sql_free_result($preresult);
        ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editgroup", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Group Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editgroup", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Group Manager</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&#160;Editing a Group: </span>
<span style="float: right;">&#160;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editgroup", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="GroupName">Insert name for group:</label></td>
	<td style="width: 50%;"><input type="text" name="GroupName" class="TextBox" id="GroupName" size="20" value="<?php echo $GroupName; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NamePrefix">Name Prefix:</label></td>
	<td style="width: 50%;"><input type="text" name="NamePrefix" class="TextBox" id="NamePrefix" size="20" value="<?php echo $NamePrefix; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NameSuffix">Name Subfix:</label></td>
	<td style="width: 50%;"><input type="text" name="NameSuffix" class="TextBox" id="NameSuffix" size="20" value="<?php echo $NameSuffix; ?>" /></td>
<?php if ($GroupID != 1) { ?>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewBoard">Can View Board:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewBoard" id="CanViewBoard">
	<option selected="selected" value="<?php echo $CanViewBoard; ?>">Old Value (<?php echo $CanViewBoard; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewOffLine">Can View OffLine Board:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewOffLine" id="CanViewOffLine">
	<option selected="selected" value="<?php echo $CanViewOffLine; ?>">Old Value (<?php echo $CanViewOffLine; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanEditProfile">Can Edit Profile:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanEditProfile" id="CanEditProfile">
	<option selected="selected" value="<?php echo $CanEditProfile; ?>">Old Value (<?php echo $CanEditProfile; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanAddEvents">Can Add Events:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanAddEvents" id="CanAddEvents">
	<option selected="selected" value="<?php echo $CanAddEvents; ?>">Old Value (<?php echo $CanAddEvents; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanPM">Can PM:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanPM" id="CanPM">
	<option selected="selected" value="<?php echo $CanPM; ?>">Old Value (<?php echo $CanPM; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanSearch">Can Search:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanSearch" id="CanSearch">
	<option selected="selected" value="<?php echo $CanSearch; ?>">Old Value (<?php echo $CanSearch; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
<?php } ?>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanDoHTML">Can DoHTML:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanDoHTML" id="CanDoHTML">
	<option <?php if ($CanDoHTML == "yes") {
	    echo "selected=\"selected\" ";
	} ?>value="yes">yes</option>
	<option <?php if ($CanDoHTML == "no") {
	    echo "selected=\"selected\" ";
	} ?>value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanUseBBTags">Can use BBTags:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanUseBBTags" id="CanUseBBTags">
	<option <?php if ($CanUseBBTags == "yes") {
	    echo "selected=\"selected\" ";
	} ?>value="yes">yes</option>
	<option <?php if ($CanUseBBTags == "no") {
	    echo "selected=\"selected\" ";
	} ?>value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="FloodControl">Flood Control in seconds:</label></td>
	<td style="width: 50%;"><input type="text" name="FloodControl" class="TextBox" id="FloodControl" size="20" value="<?php echo $FloodControl; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="SearchFlood">Search Flood Control in seconds:</label></td>
	<td style="width: 50%;"><input type="text" name="SearchFlood" class="TextBox" id="SearchFlood" size="20" value="<?php echo $SearchFlood; ?>" /></td>
<?php if ($GroupID != 1) { ?>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="PromoteTo">Promote To Group:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="PromoteTo" id="PromoteTo">
	<option selected="selected" value="<?php echo $PromoteTo; ?>">Old Value (<?php echo $PromoteTo; ?>)</option>
	<option value="0">none</option>
<?php
$ai = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."groups\" ORDER BY \"id\" ASC", null), $SQLStat);
    $fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" ORDER BY \"id\" ASC", null);
    $fr = sql_query($fq, $SQLStat);
    $fi = 0;
    while ($fi < $ai) {
        $fr_array = sql_fetch_assoc($fr);
        $ProGroupID = $fr_array['id'];
        $ProGroupName = $fr_array['Name'];
        ?>
	<option value="<?php echo $ProGroupID; ?>"><?php echo $ProGroupName; ?></option>
<?php ++$fi;
    }
    sql_free_result($fr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="PromotePosts">Amount of Posts needed:</label></td>
	<td style="width: 50%;"><input type="number" name="PromotePosts" class="TextBox" id="PromotePosts" size="20" value="<?php echo $PromotePosts; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="PromoteKarma">Amount of Karma needed:</label></td>
	<td style="width: 50%;"><input type="number" name="PromoteKarma" class="TextBox" id="PromoteKarma" size="20" value="<?php echo $PromoteKarma; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DemoteTo">Demote To Group:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DemoteTo" id="DemoteTo">
	<option selected="selected" value="<?php echo $DemoteTo; ?>">Old Value (<?php echo $DemoteTo; ?>)</option>
	<option value="0">none</option>
<?php
    $ai = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."groups\" ORDER BY \"id\" ASC", null), $SQLStat);
    $fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" ORDER BY \"id\" ASC", null);
    $fr = sql_query($fq, $SQLStat);
    $fi = 0;
    while ($fi < $ai) {
        $fr_array = sql_fetch_assoc($fr);
        $ProGroupID = $fr_array['id'];
        $ProGroupName = $fr_array['Name'];
        ?>
	<option value="<?php echo $ProGroupID; ?>"><?php echo $ProGroupName; ?></option>
<?php ++$fi;
    }
    sql_free_result($fr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DemotePosts">Amount of Posts needed:</label></td>
	<td style="width: 50%;"><input type="number" name="DemotePosts" class="TextBox" id="DemotePosts" size="20" value="<?php echo $DemotePosts; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DemoteKarma">Amount of Karma needed:</label></td>
	<td style="width: 50%;"><input type="number" name="DemoteKarma" class="TextBox" id="DemoteKarma" size="20" value="<?php echo $DemoteKarma; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="HasModCP">Can view Mod CP:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="HasModCP" id="HasModCP">
	<option selected="selected" value="<?php echo $HasModCP; ?>">Old Value (<?php echo $HasModCP; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="HasAdminCP">Can view Admin CP:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="HasAdminCP" id="HasAdminCP">
	<option selected="selected" value="<?php echo $HasAdminCP; ?>">Old Value (<?php echo $HasAdminCP; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewIPAddress">Can view IP Address:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewIPAddress" id="CanViewIPAddress">
	<option selected="selected" value="<?php echo $CanViewIPAddress; ?>">Old Value (<?php echo $CanViewIPAddress; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewUserAgent">Can view user agent:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewUserAgent" id="CanViewUserAgent">
	<option selected="selected" value="<?php echo $CanViewUserAgent; ?>">Old Value (<?php echo $CanViewUserAgent; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewAnonymous">Can view user agent:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewAnonymous" id="CanViewAnonymous">
	<option selected="selected" value="<?php echo $CanViewAnonymous; ?>">Old Value (<?php echo $CanViewAnonymous; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ViewDBInfo">Can view Database info:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ViewDBInfo" id="ViewDBInfo">
	<option selected="selected" value="<?php echo $ViewDBInfo; ?>">Old Value (<?php echo $ViewDBInfo; ?>)</option>
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
<?php } ?>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="editgroup" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="hidden" name="id" value="<?php echo $GroupID; ?>" style="display: none;" />
<input type="submit" class="Button" value="Edit Group" name="Apply_Changes" />
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
<?php }
}
} if ($_POST['act'] == "editgroup" && $_POST['update'] == "now" && $_GET['act'] == "editgroup" &&
    isset($_POST['id'])) {
    $_POST['GroupName'] = stripcslashes(htmlspecialchars($_POST['GroupName'], ENT_QUOTES, $Settings['charset']));
    //$_POST['GroupName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['GroupName']);
    $_POST['GroupName'] = remove_spaces($_POST['GroupName']);
    $_POST['NamePrefix'] = stripcslashes(htmlspecialchars($_POST['NamePrefix'], ENT_QUOTES, $Settings['charset']));
    //$_POST['NamePrefix'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['NamePrefix']);
    $_POST['NamePrefix'] = remove_spaces($_POST['NamePrefix']);
    $_POST['NameSuffix'] = stripcslashes(htmlspecialchars($_POST['NameSuffix'], ENT_QUOTES, $Settings['charset']));
    //$_POST['NameSuffix'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['NameSuffix']);
    $_POST['NameSuffix'] = remove_spaces($_POST['NameSuffix']);
    $name_check = 0;
    $prenum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($_POST['id'])), $SQLStat);
    $prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($_POST['id']));
    $preresult = sql_query($prequery, $SQLStat);
    if ($prenum == 0) {
        redirect("location", $rbasedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false));
        sql_free_result($preresult);
        ob_clean();
        header("Content-Type: text/plain; charset=".$Settings['charset']);
        $urlstatus = 302;
        gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        session_write_close();
        die();
    }
    if ($prenum >= 1) {
        $preresult_array = sql_fetch_assoc($preresult);
        $OldGroupName = $preresult_array['Name'];
        sql_free_result($preresult);
        if ($_POST['GroupName'] != $OldGroupName) {
            $name_check = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."groups\" WHERE \"Name\"='%s'", array($_POST['GroupName'])), $SQLStat);
            /*$sql_name_check = sql_query(sql_pre_query("SELECT \"Name\" FROM \"".$Settings['sqltable']."groups\" WHERE \"Name\"='%s'", array($_POST['GroupName'])),$SQLStat);
            sql_free_result($sql_name_check);*/
        }
        $errorstr = "";
        if (!isset($_POST['PromotePosts'])) {
            $_POST['PromotePosts'] = 0;
        }
        if ($_POST['PromotePosts'] == null ||
            !is_numeric($_POST['PromotePosts'])) {
            $_POST['PromotePosts'] = 0;
        }
        if (!isset($_POST['PromoteKarma'])) {
            $_POST['PromoteKarma'] = 0;
        }
        if ($_POST['PromoteKarma'] == null ||
            !is_numeric($_POST['PromoteKarma'])) {
            $_POST['NPromoteKarma'] = 0;
        }
        if (!isset($_POST['DemotePosts'])) {
            $_POST['DemotePosts'] = 0;
        }
        if ($_POST['DemotePosts'] == null ||
            !is_numeric($_POST['DemotePosts'])) {
            $_POST['DemotePosts'] = 0;
        }
        if (!isset($_POST['DemoteKarma'])) {
            $_POST['DemoteKarma'] = 0;
        }
        if ($_POST['DemoteKarma'] == null ||
            !is_numeric($_POST['DemoteKarma'])) {
            $_POST['NDemoteKarma'] = 0;
        }
        if ($_POST['GroupName'] == null ||
            $_POST['GroupName'] == "ShowMe") {
            $Error = "Yes";
            $errorstr = $errorstr."You need to enter a forum name.<br />\n";
        }
        if ($name_check > 0) {
            $Error = "Yes";
            $errorstr = $errorstr."This Group Name is already used.<br />\n";
        }
        if (pre_strlen($_POST['GroupName']) > "150") {
            $Error = "Yes";
            $errorstr = $errorstr."Your Group Name is too big.<br />\n";
        }
        if ($Error != "Yes") {
            redirect("refresh", $rbasedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view&menu=groups", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false), "4");
            $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
            if ($_POST['GroupName'] != $OldGroupName) {
                $query = sql_pre_query("UPDATE \"".$Settings['sqltable']."permissions\" SET \"Name\"='%s' WHERE \"Name\"='%s'", array($_POST['GroupName'],$OldGroupName));
                sql_query($query, $SQLStat);
                $query = sql_pre_query("UPDATE \"".$Settings['sqltable']."catpermissions\" SET \"Name\"='%s' WHERE \"Name\"='%s'", array($_POST['GroupName'],$OldGroupName));
                sql_query($query, $SQLStat);
            }
            if ($_POST['id'] != 1) {
                $query = sql_pre_query("UPDATE \"".$Settings['sqltable']."groups\" SET \"Name\"='%s',\"NamePrefix\"='%s',\"NameSuffix\"='%s',\"CanViewBoard\"='%s',\"CanViewOffLine\"='%s',\"CanEditProfile\"='%s',\"CanAddEvents\"='%s',\"CanPM\"='%s',\"CanSearch\"='%s',\"CanDoHTML\"='%s',\"CanUseBBTags\"='%s',CanViewIPAddress='%s',CanViewUserAgent='%s',CanViewAnonymous='%s',\"FloodControl\"=%i,\"SearchFlood\"=%i,\"PromoteTo\"=%i,\"PromotePosts\"=%i,\"PromoteKarma\"=%i,\"DemoteTo\"=%i,\"DemotePosts\"=%i,\"DemoteKarma\"=%i,\"HasModCP\"='%s',\"HasAdminCP\"='%s',\"ViewDBInfo\"='%s' WHERE \"id\"=%i", array($_POST['GroupName'],$_POST['NamePrefix'],$_POST['NameSuffix'],$_POST['CanViewBoard'],$_POST['CanViewOffLine'],$_POST['CanEditProfile'],$_POST['CanAddEvents'],$_POST['CanPM'],$_POST['CanSearch'],$_POST['CanDoHTML'],$_POST['CanUseBBTags'],$_POST['CanViewIPAddress'],$_POST['CanViewUserAgent'],$_POST['CanViewAnonymous'],$_POST['FloodControl'],$_POST['SearchFlood'],$_POST['PromoteTo'],$_POST['PromotePosts'],$_POST['PromoteKarma'],$_POST['DemoteTo'],$_POST['DemotePosts'],$_POST['DemoteKarma'],$_POST['HasModCP'],$_POST['HasAdminCP'],$_POST['ViewDBInfo'],$_POST['id']));
            }
            if ($_POST['id'] == 1) {
                $query = sql_pre_query("UPDATE \"".$Settings['sqltable']."groups\" SET \"Name\"='%s',\"NamePrefix\"='%s',\"NameSuffix\"='%s',\"CanDoHTML\"='%s',\"CanUseBBTags\"='%s',\"FloodControl\"=%i,\"SearchFlood\"=%i WHERE \"id\"=%i", array($_POST['GroupName'],$_POST['NamePrefix'],$_POST['NameSuffix'],$_POST['CanDoHTML'],$_POST['CanUseBBTags'],$_POST['FloodControl'],$_POST['SearchFlood'],$_POST['id']));
            }
            sql_query($query, $SQLStat);
        }
    }
}
$doupdate = false;
if (isset($_POST['id']) && $_POST['subact'] == "editnow") {
    $doupdate = true;
}
if (isset($_POST['id']) && isset($_POST['permid']) && $_POST['subact'] == "makenow") {
    $doupdate = true;
}
if ($_POST['act'] == "addgroup" && $_POST['update'] == "now" && $_GET['act'] == "addgroup") {
    $doupdate = true;
}
if ($_GET['act'] == "deletegroup" && $_POST['update'] == "now" && $_GET['act'] == "deletegroup") {
    $doupdate = true;
}
if ($_POST['act'] == "editgroup" && $_POST['update'] == "now" && $_GET['act'] == "editgroup" &&
    isset($_POST['id'])) {
    $doupdate = true;
}
if ($doupdate === true && $Error != "Yes") { ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Updating Settings</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Updating Settings</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr id="ProfileTitle" class="TableMenuRow2">
<th class="TableMenuColumn2">Updating Settings</th>
</tr>
<tr class="TableMenuRow3" id="ProfileUpdate">
<td class="TableMenuColumn3">
<?php if ($_POST['act'] == "addgroup" && $_POST['update'] == "now" && $_GET['act'] == "addgroup") { ?>
<div style="text-align: center;">
	<br />The group was created successfully. <a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=groups", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&#160;
	</div>
<?php } if ($_GET['act'] == "deletegroup" && $_POST['update'] == "now" && $_GET['act'] == "deletegroup") { ?>
<div style="text-align: center;">
	<br />The group was deleted successfully. <a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=groups", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&#160;
	</div>
<?php } if ($_POST['act'] == "editgroup" && $_POST['update'] == "now" && $_GET['act'] == "editgroup" &&
    isset($_POST['id'])) { ?>
<div style="text-align: center;">
	<br />The group was edited successfully. <a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=groups", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&#160;
	</div>
<?php } ?>
</td></tr>
<tr id="ProfileTitleEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&#160;</td>
</tr></table></div>
<?php } if ($_GET['act'] != null && $Error == "Yes") {
    redirect("refresh", $rbasedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=groups", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false), "4");
    $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
    ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=groups", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Updating Settings</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=groups", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Updating Settings</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr id="ProfileTitle" class="TableMenuRow2">
<th class="TableMenuColumn2">Updating Settings</th>
</tr>
<tr class="TableMenuRow3" id="ProfileUpdate">
<td class="TableMenuColumn3">
<div style="text-align: center;">
	<br /><?php echo $errorstr; ?>
	<a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=groups", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Click here</a> to back to admin cp.<br />&#160;
	</div>
</td></tr>
<tr id="ProfileTitleEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&#160;</td>
</tr></table></div>
<?php } ?>
</td></tr>
</table>
<div>&#160;</div>
