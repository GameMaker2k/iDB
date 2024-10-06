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

    $FileInfo: categories.php - Last Update: 8/26/2024 SVN 1048 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "categories.php" || $File3Name == "/categories.php") {
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
<?php if ($_GET['act'] == "addcategory" && $_POST['update'] != "now") {
    $admincptitle = " ".$ThemeSet['TitleDivider']." Adding new Category";
    ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=addcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Category Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=addcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Category Manager</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&#160;Adding new Category: </span>
<span style="float: right;">&#160;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=addcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CategoryID">Insert ID for category:</label></td>
	<td style="width: 50%;"><input type="number" name="CategoryID" class="TextBox" id="CategoryID" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="OrderID">Insert order id category:</label></td>
	<td style="width: 50%;"><input type="number" name="OrderID" class="TextBox" id="OrderID" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CategoryName">Insert name for category:</label></td>
	<td style="width: 50%;"><input type="text" name="CategoryName" class="TextBox" id="CategoryName" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CategoryDesc">Insert description for category:</label></td>
	<td style="width: 50%;"><input type="text" name="CategoryDesc" class="TextBox" id="CategoryDesc" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ShowCategory">Show category:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ShowCategory" id="ShowCategory">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CategoryType">Insert category type:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CategoryType" id="CategoryType">
	<option selected="selected" value="category">Category</option>
	<option value="subcategory">SubCategory</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="InSubCategory">In SubCategory:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="InSubCategory" id="InSubCategory">
	<option selected="selected" value="0">none</option>
<?php
    $ai = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."categories\" WHERE \"InSubCategory\"=0 AND \"CategoryType\"='subcategory' ORDER BY \"OrderID\" ASC, \"id\" ASC", null), $SQLStat);
    $fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" WHERE \"InSubCategory\"=0 AND \"CategoryType\"='subcategory' ORDER BY \"OrderID\" ASC, \"id\" ASC", null);
    $fr = sql_query($fq, $SQLStat);
    if ($ai > 0) { ?>
	<optgroup label="<?php echo $Settings['board_name']; ?>">
<?php }
    $fi = 0;
    while ($fi < $ai) {
        $fr_array = sql_fetch_assoc($fr);
        $InCategoryID = $fr_array['id'];
        $InCategoryName = $fr_array['Name'];
        $InCategoryType = $fr_array['CategoryType'];
        $AiFiInSubCategory = $fr_array['InSubCategory'];
        if ($AiFiInSubCategory == "0") {
            ?>
	<option value="<?php echo $InCategoryID; ?>"><?php echo $InCategoryName; ?></option>
<?php } ++$fi;
    }
    if ($ai > 0) { ?>
	</optgroup>
<?php }
    sql_free_result($fr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NumPostView">Number of posts to view category:</label></td>
	<td style="width: 50%;"><input type="number" class="TextBox" size="20" name="NumPostView" id="NumPostView" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NumKarmaView">Amount of karma to view category:</label></td>
	<td style="width: 50%;"><input type="number" class="TextBox" size="20" name="NumKarmaView" id="NumKarmaView" /></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="addcategory" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Add Category" name="Apply_Changes" />
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
<?php } if ($_POST['act'] == "addcategory" && $_POST['update'] == "now" && $_GET['act'] == "addcategory") {
    $_POST['CategoryName'] = stripcslashes(htmlspecialchars($_POST['CategoryName'], ENT_QUOTES, $Settings['charset']));
    //$_POST['CategoryName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['CategoryName']);
    $_POST['CategoryName'] = remove_spaces($_POST['CategoryName']);
    $_POST['CategoryDesc'] = stripcslashes(htmlspecialchars($_POST['CategoryDesc'], ENT_QUOTES, $Settings['charset']));
    //$_POST['CategoryDesc'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['CategoryDesc']);
    $_POST['CategoryDesc'] = remove_spaces($_POST['CategoryDesc']);
    $id_check = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i LIMIT 1", array($_POST['CategoryID'])), $SQLStat);
    $order_check = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."categories\" WHERE \"OrderID\"=%i LIMIT 1", array($_POST['OrderID'])), $SQLStat);
    /*$sql_id_check = sql_query(sql_pre_query("SELECT \"id\" FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i LIMIT 1", array($_POST['CategoryID'])),$SQLStat);
    sql_free_result($sql_id_check);
    $sql_order_check = sql_query(sql_pre_query("SELECT \"OrderID\" FROM \"".$Settings['sqltable']."categories\" WHERE \"OrderID\"=%i LIMIT 1", array($_POST['OrderID'])),$SQLStat); sql_free_result($sql_order_check);*/
    $errorstr = "";
    if ($_POST['NumPostView'] == null ||
        !is_numeric($_POST['NumPostView'])) {
        $_POST['NumPostView'] = 0;
    }
    if ($_POST['NumKarmaView'] == null ||
        !is_numeric($_POST['NumKarmaView'])) {
        $_POST['NumKarmaView'] = 0;
    }
    if ($_POST['CategoryName'] == null ||
        $_POST['CategoryName'] == "ShowMe") {
        $Error = "Yes";
        $errorstr = $errorstr."You need to enter a category name.<br />\n";
    }
    if ($_POST['CategoryDesc'] == null) {
        $Error = "Yes";
        $errorstr = $errorstr."You need to enter a description.<br />\n";
    }
    if ($_POST['CategoryID'] == null ||
        !is_numeric($_POST['CategoryID']) ||
        $_POST['CategoryID'] == 0 ||
        $_POST['CategoryID'] == "0") {
        $Error = "Yes";
        $errorstr = $errorstr."You need to enter a category id.<br />\n";
    }
    if ($id_check > 0) {
        $Error = "Yes";
        $errorstr = $errorstr."This ID number is already used.<br />\n";
    }
    if ($order_check > 0) {
        $Error = "Yes";
        $errorstr = $errorstr."This order number is already used.<br />\n";
    }
    if (pre_strlen($_POST['CategoryName']) > "150") {
        $Error = "Yes";
        $errorstr = $errorstr."Your category name is too big.<br />\n";
    }
    if (pre_strlen($_POST['CategoryDesc']) > "300") {
        $Error = "Yes";
        $errorstr = $errorstr."Your category description is too big.<br />\n";
    }
    if ($Error != "Yes") {
        redirect("refresh", $rbasedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false), "4");
        $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
        $query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."categories\" (\"id\", \"OrderID\", \"Name\", \"ShowCategory\", \"CategoryType\", \"SubShowForums\", \"InSubCategory\", \"PostCountView\", \"KarmaCountView\", \"Description\") VALUES\n".
        "(%i, %i, '%s', '%s', '%s', 'yes', %i, %i, %i, '%s')", array($_POST['CategoryID'],$_POST['OrderID'],$_POST['CategoryName'],$_POST['ShowCategory'],$_POST['CategoryType'],$_POST['InSubCategory'],$_POST['NumPostView'],$_POST['NumKarmaView'],$_POST['CategoryDesc']));
        sql_query($query, $SQLStat);
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
            $getperidnum = sql_count_rows(sql_pre_query("SELECT COUNT(DISTINCT \"PermissionID\") AS cnt FROM \"".$Settings['sqltable']."catpermissions\" ORDER BY \"PermissionID\" ASC", null), $SQLStat);
            $getperidq = sql_pre_query("SELECT DISTINCT \"PermissionID\" FROM \"".$Settings['sqltable']."catpermissions\" ORDER BY \"PermissionID\" ASC", null);
        }
        if ($Settings['sqltype'] == "cubrid" ||
            $Settings['sqltype'] == "cubrid_prepare" ||
            $Settings['sqltype'] == "pdo_cubrid") {
            $getperidnum = sql_count_rows(sql_pre_query("SELECT COUNT(DISTINCT \"permissionid\") AS cnt FROM \"".$Settings['sqltable']."catpermissions\" ORDER BY \"PermissionID\" ASC", null), $SQLStat);
            $getperidq = sql_pre_query("SELECT DISTINCT \"permissionid\" FROM \"".$Settings['sqltable']."catpermissions\" ORDER BY \"PermissionID\" ASC", null);
        }
        $getperidr = sql_query($getperidq, $SQLStat);
        if ($getperidnum == 0) {
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
                $getperidnum = sql_count_rows(sql_pre_query("SELECT COUNT(DISTINCT \"PermissionID\") AS cnt FROM \"".$Settings['sqltable']."groups\" ORDER BY \"PermissionID\" ASC", null), $SQLStat);
                $getperidq = sql_pre_query("SELECT DISTINCT \"PermissionID\" FROM \"".$Settings['sqltable']."groups\" ORDER BY \"PermissionID\" ASC", null);
            }
            if ($Settings['sqltype'] == "cubrid" ||
                $Settings['sqltype'] == "cubrid_prepare" ||
                $Settings['sqltype'] == "pdo_cubrid") {
                $getperidq = sql_count_rows(sql_pre_query("SELECT COUNT(DISTINCT \"permissionid\") AS cnt FROM \"".$Settings['sqltable']."groups\" ORDER BY \"PermissionID\" ASC", null), $SQLStat);
            }
            $getperidr = sql_query($getperidq, $SQLStat);
        }
        $getperidi = 0;
        //$nextperid = sql_get_next_id($Settings['sqltable'],"catpermissions",$SQLStat);
        $nextperid = null;
        while ($getperidi < $getperidnum) {
            $gerperid_array = sql_fetch_assoc($getperid);
            $getperidID = $getperidr_array['PermissionID'];
            $getperidnum2 = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."catpermissions\" WHERE \"PermissionID\"=%i", array($getperidID)), $SQLStat);
            $getperidq2 = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."catpermissions\" WHERE \"PermissionID\"=%i", array($getperidID));
            $getperidr2 = sql_query($getperidq2, $SQLStat);
            if ($getperidnum2 == 0) {
                $getperidnum2 = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."groups\" WHERE \"PermissionID\"=%i", array($getperidID)), $SQLStat);
                $getperidq2 = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"PermissionID\"=%i", array($getperidID));
                $getperidr2 = sql_query($getperidq2, $SQLStat);
            }
            $getperidr2_array = sql_fetch_assoc($getperidr2);
            $getperidName = $getperidr2_array['Name'];
            sql_free_result($getperidr2);
            $query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."catpermissions\" (\"PermissionID\", \"Name\", \"CategoryID\", \"CanViewCategory\") VALUES (%i, '%s', %i, 'yes')", array($getperidID,$getperidName,$_POST['CategoryID']));
            sql_query($query, $SQLStat);
            ++$getperidi; /*++$nextperid;*/
        }
        sql_free_result($getperidr);
    }
}
if ($_GET['act'] == "deletecategory" && $_POST['update'] != "now") {
    $admincptitle = " ".$ThemeSet['TitleDivider']." Deleting a Category";
    ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=addcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Category Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=addcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Category Manager</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&#160;Deleting a Category: </span>
<span style="float: right;">&#160;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=deletecategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelCategories">Delete all categories in subcategory:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelCategories" id="DelCategories">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelForums">Delete all forums in (sub)category:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelForums" id="DelForums">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelTopics">Delete all topics in (sub)category:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelTopics" id="DelTopics">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelPermission">Delete all permission sets in (sub)category:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelPermission" id="DelPermission">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelID">Delete Category:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelID" id="DelID">
<?php
    $ai = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."categories\" ORDER BY \"OrderID\" ASC, \"id\" ASC", null), $SQLStat);
    $fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" ORDER BY \"OrderID\" ASC, \"id\" ASC", null);
    $fr = sql_query($fq, $SQLStat);
    if ($ai > 0) { ?>
	<optgroup label="<?php echo $Settings['board_name']; ?>">
<?php }
    $fi = 0;
    while ($fi < $ai) {
        $fr_array = sql_fetch_assoc($fr);
        $InCategoryID = $fr_array['id'];
        $InCategoryName = $fr_array['Name'];
        $InCategoryType = $fr_array['CategoryType'];
        $AiFiInSubCategory = $fr_array['InSubCategory'];
        ?>
	<option value="<?php echo $InCategoryID; ?>"><?php echo $InCategoryName; ?></option>
<?php ++$fi;
    }
    if ($ai > 0) { ?>
	</optgroup>
<?php }
    sql_free_result($fr); ?>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="deletecategory" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Delete Category" name="Apply_Changes" />
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
<?php } if ($_GET['act'] == "deletecategory" && $_POST['update'] == "now" && $_GET['act'] == "deletecategory") {
    $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
    $prenum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i LIMIT 1", array($_POST['DelID'])), $SQLStat);
    $prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i LIMIT 1", array($_POST['DelID']));
    $preresult = sql_query($prequery, $SQLStat);
    $errorstr = "";
    $Error = null;
    if (!is_numeric($_POST['DelID'])) {
        $Error = "Yes";
        $errorstr = $errorstr."You need to enter a forum ID.<br />\n";
    }
    if ($prenum > 0 && $Error != "Yes") {
        $dtquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i", array($_POST['DelID']));
        sql_query($dtquery, $SQLStat);
        if ($_POST['DelCategories'] == "yes") {
            $dscquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."categories\" WHERE \"InSubCategory\"=%i", array($_POST['DelID']));
            sql_query($dscquery, $SQLStat);
        }
        if ($_POST['DelForums'] == "yes") {
            $dsfquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."forums\" WHERE \"CategoryID\"=%i", array($_POST['DelID']));
            sql_query($dsfquery, $SQLStat);
        }
        if ($_POST['DelForums'] == "yes") {
            $dstquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."topics\" WHERE \"CategoryID\"=%i", array($_POST['DelID']));
            sql_query($dstquery, $SQLStat);
        }
        if ($_POST['DelForums'] == "yes") {
            $dstquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."topics\" WHERE \"CategoryID\"=%i", array($_POST['DelID']));
            sql_query($dstquery, $SQLStat);
            $dstquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."posts\" WHERE \"CategoryID\"=%i", array($_POST['DelID']));
            sql_query($dstquery, $SQLStat);
        }
        if ($_POST['DelPermission'] == "yes") {
            $apcnum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."forums\" WHERE \"CategoryID\"=%i ORDER BY \"OrderID\" ASC, \"id\" ASC", array($_POST['DelID'])), $SQLStat);
            $apcquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"CategoryID\"=%i ORDER BY \"OrderID\" ASC, \"id\" ASC", array($_POST['DelID']));
            $apcresult = sql_query($apcquery, $SQLStat);
            $apci = 0;
            $apcl = 1;
            if ($apcnum >= 1) {
                while ($apci < $apcnum) {
                    $apcresult_array = sql_fetch_assoc($apcresult);
                    $DelForumID = $apcresult_array['id'];
                    if ($_POST['DelPermission'] == "yes") {
                        $dtquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."permissions\" WHERE \"ForumID\"=%i", array($DelForumID));
                        sql_query($dtquery, $SQLStat);
                    }
                    ++$apci;
                }
                sql_free_result($apcresult);
            }
        }
        $dtquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."catpermissions\" WHERE \"CategoryID\"=%i", array($_POST['DelID']));
        sql_query($dtquery, $SQLStat);
        if ($_POST['DelPermission'] == "yes") {
            $apcnum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."categories\" WHERE \"InSubCategory\"=%i ORDER BY \"OrderID\" ASC, \"id\" ASC", array($_POST['DelID'])), $SQLStat);
            $apcquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" WHERE \"InSubCategory\"=%i ORDER BY \"OrderID\" ASC, \"id\" ASC", array($_POST['DelID']));
            $apcresult = sql_query($apcquery, $SQLStat);
            $apci = 0;
            $apcl = 1;
            if ($apcnum >= 1) {
                while ($apci < $apcnum) {
                    $apcresult_array = sql_fetch_assoc($apcresult);
                    $DelSubsCategoryID = $apcresult_array['id'];
                    if ($_POST['DelPermission'] == "yes") {
                        $dtquery = sql_pre_query("DELETE FROM \"".$Settings['sqltable']."catpermissions\" WHERE \"CategoryID\"=%i", array($DelSubsCategoryID));
                        sql_query($dtquery, $SQLStat);
                    }
                    ++$apci;
                }
                sql_free_result($apcresult);
            }
        }
        ?>
<?php }
    } if ($_GET['act'] == "editcategory" && $_POST['update'] != "now") {
        $admincptitle = " ".$ThemeSet['TitleDivider']." Editing a Category";
        if (!isset($_POST['id'])) {
            ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Category Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Category Manager</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&#160;Editing a Category: </span>
<span style="float: right;">&#160;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="id">Category to Edit:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="id" id="id">
<?php
            $ai = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."categories\" ORDER BY \"OrderID\" ASC, \"id\" ASC", null), $SQLStat);
            $fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" ORDER BY \"OrderID\" ASC, \"id\" ASC", null);
            $fr = sql_query($fq, $SQLStat);
            if ($ai > 0) { ?>
	<optgroup label="<?php echo $Settings['board_name']; ?>">
<?php }
            $fi = 0;
            while ($fi < $ai) {
                $fr_array = sql_fetch_assoc($fr);
                $InCategoryID = $fr_array['id'];
                $InCategoryName = $fr_array['Name'];
                $InCategoryType = $fr_array['CategoryType'];
                $AiFiInSubCategory = $fr_array['InSubCategory'];
                ?>
	<option value="<?php echo $InCategoryID; ?>"><?php echo $InCategoryName; ?></option>
<?php ++$fi;
            }
            if ($ai > 0) { ?>
	</optgroup>
<?php }
            sql_free_result($fr); ?>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="editcategory" style="display: none;" />
<input type="submit" class="Button" value="Edit Category" name="Apply_Changes" />
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
    $prenum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i LIMIT 1", array($_POST['id'])), $SQLStat);
    $prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i LIMIT 1", array($_POST['id']));
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
        $CategoryID = $preresult_array['id'];
        $CategoryOrder = $preresult_array['OrderID'];
        $CategoryName = $preresult_array['Name'];
        $ShowCategory = $preresult_array['ShowCategory'];
        $CategoryType = $preresult_array['CategoryType'];
        $SubShowForums = $preresult_array['SubShowForums'];
        $InSubCategory = $preresult_array['InSubCategory'];
        $CategoryDescription = $preresult_array['Description'];
        $KarmaCountView = $preresult_array['KarmaCountView'];
        $PostCountView = $preresult_array['PostCountView'];
        sql_free_result($preresult);
        $CategoryType = strtolower($CategoryType);
        ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Category Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Category Manager</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&#160;Editing a Category: </span>
<span style="float: right;">&#160;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CategoryID">Insert id for category:</label></td>
	<td style="width: 50%;"><input type="number" name="CategoryID" class="TextBox" id="CategoryID" size="20" value="<?php echo $CategoryID; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="OrderID">Insert order id category:</label></td>
	<td style="width: 50%;"><input type="number" name="OrderID" class="TextBox" id="OrderID" size="20" value="<?php echo $CategoryOrder; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CategoryName">Insert name for category:</label></td>
	<td style="width: 50%;"><input type="text" name="CategoryName" class="TextBox" id="CategoryName" size="20" value="<?php echo $CategoryName; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CategoryDesc">Insert description for category:</label></td>
	<td style="width: 50%;"><input type="text" name="CategoryDesc" class="TextBox" id="CategoryDesc" size="20" value="<?php echo $CategoryDescription; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ShowCategory">Show category:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ShowCategory" id="ShowCategory">
	<option <?php if ($ShowCategory == "yes") {
	    echo "selected=\"selected\" ";
	} ?>value="yes">yes</option>
	<option <?php if ($ShowCategory == "no") {
	    echo "selected=\"selected\" ";
	} ?>value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CategoryType">Insert category type:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CategoryType" id="CategoryType">
	<option <?php if ($CategoryType == "category") {
	    echo "selected=\"selected\" ";
	} ?>value="category">Category</option>
	<option <?php if ($CategoryType == "subcategory") {
	    echo "selected=\"selected\" ";
	} ?>value="subcategory">SubCategory</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="InSubCategory">In SubCategory:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="InSubCategory" id="InSubCategory">
	<option selected="selected" value="0">none</option>
<?php
$ai = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."categories\" WHERE \"InSubCategory\"=0 AND \"id\"<>%i AND \"CategoryType\"='subcategory' ORDER BY \"OrderID\" ASC, \"id\" ASC", array($CategoryID)), $SQLStat);
        $fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" WHERE \"InSubCategory\"=0 AND \"id\"<>%i AND \"CategoryType\"='subcategory' ORDER BY \"OrderID\" ASC, \"id\" ASC", array($CategoryID));
        $fr = sql_query($fq, $SQLStat);
        if ($ai > 0) { ?>
	<optgroup label="<?php echo $Settings['board_name']; ?>">
<?php }
        $fi = 0;
        while ($fi < $ai) {
            $fr_array = sql_fetch_assoc($fr);
            $InCategoryID = $fr_array['id'];
            $InCategoryName = $fr_array['Name'];
            $InCategoryType = $fr_array['CategoryType'];
            $AiFiInSubCategory = $fr_array['InSubCategory'];
            if ($AiFiInSubCategory == "0") {
                if ($InSubCategory == $InCategoryID) {
                    ?>
	<option value="<?php echo $InCategoryID; ?>" selected="selected"><?php echo $InCategoryName; ?></option>
<?php } if ($InSubCategory != $InCategoryID) { ?>
	<option value="<?php echo $InCategoryID; ?>"><?php echo $InCategoryName; ?></option>
<?php }
} ++$fi;
        }
        if ($ai > 0) { ?>
	</optgroup>
<?php }
        sql_free_result($fr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NumPostView">Number of posts to view categories:</label></td>
	<td style="width: 50%;"><input type="number" class="TextBox" size="20" name="NumPostView" id="NumPostView" value="<?php echo $PostCountView; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NumKarmaView">Amount of karma to view categories:</label></td>
	<td style="width: 50%;"><input type="number" class="TextBox" size="20" name="NumKarmaView" id="NumKarmaView" value="<?php echo $KarmaCountView; ?>" /></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="editcategory" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="hidden" name="id" value="<?php echo $CategoryID; ?>" style="display: none;" />
<input type="submit" class="Button" value="Edit Category" name="Apply_Changes" />
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
    } if ($_POST['act'] == "editcategory" && $_POST['update'] == "now" && $_GET['act'] == "editcategory" &&
        isset($_POST['id'])) {
        $_POST['CategoryName'] = stripcslashes(htmlspecialchars($_POST['CategoryName'], ENT_QUOTES, $Settings['charset']));
        //$_POST['CategoryName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['CategoryName']);
        $_POST['CategoryName'] = remove_spaces($_POST['CategoryName']);
        $_POST['CategoryDesc'] = stripcslashes(htmlspecialchars($_POST['CategoryDesc'], ENT_QUOTES, $Settings['charset']));
        //$_POST['CategoryDesc'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['CategoryDesc']);
        $_POST['CategoryDesc'] = remove_spaces($_POST['CategoryDesc']);
        $prenum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i LIMIT 1", array($_POST['id'])), $SQLStat);
        $prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i LIMIT 1", array($_POST['id']));
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
            $OldID = $preresult_array['id'];
            $OldOrder = $preresult_array['OrderID'];
            sql_free_result($preresult);
            $id_check = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i LIMIT 1", array($_POST['ForumID'])), $SQLStat);
            $order_check = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."categories\" WHERE \"OrderID\"=%i LIMIT 1", array($_POST['OrderID'])), $SQLStat);
            /*$sql_id_check = sql_query(sql_pre_query("SELECT \"id\" FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i LIMIT 1", array($_POST['ForumID'])),$SQLStat);
            sql_free_result($sql_id_check);
            $sql_order_check = sql_query(sql_pre_query("SELECT \"OrderID\" FROM \"".$Settings['sqltable']."categories\" WHERE \"OrderID\"=%i LIMIT 1", array($_POST['OrderID'])),$SQLStat);
            sql_free_result($sql_order_check);*/
            if ($_POST['NumPostView'] == null ||
                !is_numeric($_POST['NumPostView'])) {
                $_POST['NumPostView'] = 0;
            }
            if ($_POST['NumKarmaView'] == null ||
                !is_numeric($_POST['NumKarmaView'])) {
                $_POST['NumKarmaView'] = 0;
            }
            if ($_POST['CategoryName'] == null ||
                $_POST['CategoryName'] == "ShowMe") {
                $Error = "Yes";
                $errorstr = $errorstr."You need to enter a category name.<br />\n";
            }
            if ($_POST['CategoryDesc'] == null) {
                $Error = "Yes";
                $errorstr = $errorstr."You need to enter a description.<br />\n";
            }
            if ($_POST['CategoryID'] == null ||
                !is_numeric($_POST['CategoryID'])) {
                $Error = "Yes";
                $errorstr = $errorstr."You need to enter a category ID.<br />\n";
            }
            if ($id_check > 0 && $_POST['CategoryID'] != $OldID) {
                $Error = "Yes";
                $errorstr = $errorstr."This ID number is already used.<br />\n";
            }
            if ($order_check > 0 && $_POST['OrderID'] != $OldOrder) {
                $Error = "Yes";
                $errorstr = $errorstr."This order number is already used.<br />\n";
            }
            if (pre_strlen($_POST['CategoryName']) > "150") {
                $Error = "Yes";
                $errorstr = $errorstr."Your category name is too big.<br />\n";
            }
            if (pre_strlen($_POST['CategoryDesc']) > "300") {
                $Error = "Yes";
                $errorstr = $errorstr."Your category description is too big.<br />\n";
            }
            if ($Error != "Yes") {
                redirect("refresh", $rbasedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false), "4");
                $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
                $query = sql_pre_query("UPDATE \"".$Settings['sqltable']."categories\" SET \"id\"=%i,\"OrderID\"=%i,\"Name\"='%s',\"ShowCategory\"='%s',\"CategoryType\"='%s',\"InSubCategory\"=%i,\"Description\"='%s',\"PostCountView\"=%i,\"KarmaCountView\"=%i WHERE \"id\"=%i", array($_POST['CategoryID'],$_POST['OrderID'],$_POST['CategoryName'],$_POST['ShowCategory'],$_POST['CategoryType'],$_POST['InSubCategory'],$_POST['CategoryDesc'],$_POST['NumPostView'],$_POST['NumKarmaView'],$_POST['id']));
                sql_query($query, $SQLStat);
                $queryz = sql_pre_query("UPDATE \"".$Settings['sqltable']."catpermissions\" SET \"CategoryID\"=%i WHERE \"CategoryID\"=%i", array($_POST['CategoryID'],$_POST['id']));
                sql_query($queryz, $SQLStat);
                $query = sql_pre_query("UPDATE \"".$Settings['sqltable']."forums\" SET \"CategoryID\"=%i WHERE \"CategoryID\"=%i", array($_POST['CategoryID'],$_POST['id']));
                sql_query($query, $SQLStat);
                $query = sql_pre_query("UPDATE \"".$Settings['sqltable']."topics\" SET \"CategoryID\"=%i,\"OldCategoryID\"=%i WHERE \"CategoryID\"=%i", array($_POST['CategoryID'],$_POST['CategoryID'],$_POST['id']));
                sql_query($query, $SQLStat);
                $query = sql_pre_query("UPDATE \"".$Settings['sqltable']."posts\" SET \"CategoryID\"=%i WHERE \"CategoryID\"=%i", array($_POST['CategoryID'],$_POST['id']));
                sql_query($query, $SQLStat);
            }
        }
    }
if ($_GET['act'] == "cpermissions" && $_POST['update'] != "now") {
    $admincptitle = " ".$ThemeSet['TitleDivider']." Category Permissions Manager";
    if (!isset($_POST['id'])) {
        ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Category Permissions Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Category Permissions Manager</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&#160;Category Permissions Manager: </span>
<span style="float: right;">&#160;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="id">Permission to view:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="id" id="id">
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
    $getperidnum = sql_count_rows(sql_pre_query("SELECT COUNT(DISTINCT \"PermissionID\") AS cnt FROM \"".$Settings['sqltable']."catpermissions\"", null), $SQLStat);
    $getperidq = sql_pre_query("SELECT DISTINCT \"PermissionID\" FROM \"".$Settings['sqltable']."catpermissions\"", null);
}
        if ($Settings['sqltype'] == "cubrid" ||
            $Settings['sqltype'] == "cubrid_prepare" ||
            $Settings['sqltype'] == "pdo_cubrid") {
            $getperidnum = sql_count_rows(sql_pre_query("SELECT COUNT(DISTINCT \"permissionid\") AS cnt FROM \"".$Settings['sqltable']."catpermissions\"", null), $SQLStat);
            $getperidq = sql_pre_query("SELECT DISTINCT \"permissionid\" FROM \"".$Settings['sqltable']."catpermissions\"", null);
        }
        $getperidr = sql_query($getperidq, $SQLStat);
        $getperidi = 0;
        while ($getperidi < $getperidnum) {
            $getperidr_array = sql_fetch_assoc($getperidr);
            $getperidID = $getperidr_array['PermissionID'];
            $getperidnum2 = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."catpermissions\" WHERE \"PermissionID\"=%i ORDER BY \"CategoryID\" ASC", array($getperidID)), $SQLStat);
            $getperidq2 = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."catpermissions\" WHERE \"PermissionID\"=%i ORDER BY \"CategoryID\" ASC", array($getperidID));
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
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="cpermissions" style="display: none;" />
<input type="submit" class="Button" value="View Permission" name="Apply_Changes" />
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
<?php } if (isset($_POST['id']) && $_POST['subact'] == null) { ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Category Permissions Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Category Permissions Manager</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&#160;Category Permissions Manager: </span>
<span style="float: right;">&#160;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<?php
        $ai = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."categories\" ORDER BY \"OrderID\" ASC, \"id\" ASC", null), $SQLStat);
    $fq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" ORDER BY \"OrderID\" ASC, \"id\" ASC", null);
    $fr = sql_query($fq, $SQLStat);
    $fi = 0;
    while ($fi < $ai) {
        $fr_array = sql_fetch_assoc($fr);
        $InCategoryID = $fr_array['id'];
        $InCategoryName = $fr_array['Name'];
        $getperidnum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."catpermissions\" WHERE \"PermissionID\"=%i AND \"CategoryID\"=%i LIMIT 1", array($_POST['id'],$InCategoryID)), $SQLStat);
        $getperidq = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."catpermissions\" WHERE \"PermissionID\"=%i AND \"CategoryID\"=%i LIMIT 1", array($_POST['id'],$InCategoryID));
        $getperidr = sql_query($getperidq, $SQLStat);
        $getperidNumz = null;
        $getperidID = null;
        if ($getperidnum > 0) {
            $getperidr_array = sql_fetch_assoc($getperidr);
            $getperidNumz = $getperidr_array['id'];
            $getperidID = $getperidr_array['PermissionID'];
        }
        ?>
<form style="display: inline;" method="post" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<?php if ($getperidnum > 0) { ?>
Permissions for <?php echo $InCategoryName; ?> are set: <br />
<input type="hidden" name="act" value="cpermissions" style="display: none;" />
<input type="hidden" name="subact" value="edit" style="display: none;" />
<input type="hidden" name="id" value="<?php echo $getperidNumz; ?>" style="display: none;" />
<input type="submit" class="Button" value="Edit Permissions" name="Apply_Changes" />
<?php } if ($getperidnum <= 0) { ?>
Permissions for <?php echo $InCategoryName; ?> are not set: <br />
<input type="hidden" name="act" value="cpermissions" style="display: none;" />
<input type="hidden" name="subact" value="create" style="display: none;" />
<input type="hidden" name="permid" value="<?php echo $_POST['id']; ?>" style="display: none;" />
<input type="hidden" name="id" value="<?php echo $InCategoryID; ?>" style="display: none;" />
<input type="submit" class="Button" value="Create Permissions" name="Apply_Changes" />
<?php } ?>
</td></tr></table>
</form>
<?php
        sql_free_result($getperidr);
        ++$fi;
    }
    sql_free_result($fr); ?>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&#160;</td>
</tr>
</table>
</div>
<?php } if (isset($_POST['id']) && $_POST['subact'] == "edit") {
    $prenum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."catpermissions\" WHERE \"id\"=%i LIMIT 1", array($_POST['id'])), $SQLStat);
    $prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."catpermissions\" WHERE \"id\"=%i LIMIT 1", array($_POST['id']));
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
        $PermissionNum = $preresult_array['id'];
        $PermissionID = $preresult_array['PermissionID'];
        $PermissionName = $preresult_array['Name'];
        $PermissionCategoryID = $preresult_array['CategoryID'];
        $CanViewCategory = $preresult_array['CanViewCategory'];
        sql_free_result($preresult);
    }
    $PermissionName = stripcslashes(htmlspecialchars($PermissionName, ENT_QUOTES, $Settings['charset']));
    //$_POST['CategoryName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['CategoryName']);
    ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Category Permissions Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Category Permissions Manager</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&#160;Editing Category Permissions: </span>
<span style="float: right;">&#160;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewCategory">Can view Category:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewCategory" id="CanViewCategory">
	<option <?php if ($CanViewCategory == "yes") {
	    echo "selected=\"selected\" ";
	} ?>value="yes">yes</option>
	<option <?php if ($CanViewCategory == "no") {
	    echo "selected=\"selected\" ";
	} ?>value="no">no</option>
	</select></td>
</tr> 
</table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="cpermissions" style="display: none;" />
<input type="hidden" name="subact" value="editnow" style="display: none;" />
<input type="hidden" name="id" value="<?php echo $PermissionNum; ?>" style="display: none;" />
<input type="submit" class="Button" value="Edit Permissions" name="Apply_Changes" />
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
<?php } if (isset($_POST['id']) && $_POST['subact'] == "editnow") {
    $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
    redirect("refresh", $rbasedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false), "4");
    $query = sql_pre_query("UPDATE \"".$Settings['sqltable']."catpermissions\" SET \"CanViewCategory\"='%s' WHERE \"id\"=%i", array($_POST['CanViewCategory'], $_POST['id']));
    sql_query($query, $SQLStat);
} if (isset($_POST['id']) && $_POST['subact'] == "create") {
    ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Category Permissions Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Category Permissions Manager</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&#160;Editing Category Permissions: </span>
<span style="float: right;">&#160;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewCategory">Can view category:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewCategory" id="CanViewCategory">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="cpermissions" style="display: none;" />
<input type="hidden" name="subact" value="makenow" style="display: none;" />
<input type="hidden" name="id" value="<?php echo $_POST['id']; ?>" style="display: none;" />
<input type="hidden" name="permid" value="<?php echo $_POST['permid']; ?>" style="display: none;" />
<input type="submit" class="Button" value="Create Permissions" name="Apply_Changes" />
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
<?php } if (isset($_POST['id']) && isset($_POST['permid']) && $_POST['subact'] == "makenow") {
    $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
    redirect("refresh", $rbasedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false), "4");
    $prenum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."catpermissions\" WHERE \"id\"=%i LIMIT 1", array($_POST['permid'])), $SQLStat);
    $prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."catpermissions\" WHERE \"id\"=%i LIMIT 1", array($_POST['permid']));
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
        $PermissionName = $preresult_array['Name'];
        sql_free_result($preresult);
    }
    //$nextidnum = sql_get_next_id($Settings['sqltable'],"catpermissions",$SQLStat);
    $query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."catpermissions\" (\"PermissionID\", \"Name\", \"CategoryID\", \"CanViewCategory\") VALUES\n".
    "(%i, '%s', %i, '%s')", array($_POST['permid'], $PermissionName, $_POST['id'], $_POST['CanViewCategory']));
    sql_query($query, $SQLStat);
}
} $doupdate = false;
if (isset($_POST['id']) && $_POST['subact'] == "editnow") {
    $doupdate = true;
}
if (isset($_POST['id']) && isset($_POST['permid']) && $_POST['subact'] == "makenow") {
    $doupdate = true;
}
if ($_POST['act'] == "addcategory" && $_POST['update'] == "now" && $_GET['act'] == "addcategory") {
    $doupdate = true;
}
if ($_GET['act'] == "deletecategory" && $_POST['update'] == "now" && $_GET['act'] == "deletecategory") {
    $doupdate = true;
}
if ($_POST['act'] == "editcategory" && $_POST['update'] == "now" && $_GET['act'] == "editcategory" &&
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
<?php if (isset($_POST['id']) && $_POST['subact'] == "editnow") { ?>
<div style="text-align: center;">
	<br />The permission was edited successfully. <a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&#160;
	</div>
<?php } if (isset($_POST['id']) && isset($_POST['permid']) && $_POST['subact'] == "makenow") { ?>
<div style="text-align: center;">
	<br />The permission was created successfully. <a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&#160;
	</div>
<?php } if ($_POST['act'] == "addcategory" && $_POST['update'] == "now" && $_GET['act'] == "addcategory") { ?>
<div style="text-align: center;">
	<br />The category was created successfully. <a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&#160;
	</div>
<?php } if ($_GET['act'] == "deletecategory" && $_POST['update'] == "now" && $_GET['act'] == "deletecategory") { ?>
<div style="text-align: center;">
	<br />The category was deleted successfully. <a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&#160;
	</div>
<?php } if ($_POST['act'] == "editcategory" && $_POST['update'] == "now" && $_GET['act'] == "editcategory" &&
    isset($_POST['id'])) { ?>
<div style="text-align: center;">
	<br />The category was edited successfully. <a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&#160;
	</div>
<?php } ?>
</td></tr>
<tr id="ProfileTitleEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&#160;</td>
</tr></table></div>
<?php } if ($_GET['act'] != null && $Error == "Yes") {
    redirect("refresh", $rbasedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false), "4");
    $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
    ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Updating Settings</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Updating Settings</a>
</span><span style="float: right;">&#160;</span></td>
</tr><?php } ?>
<tr id="ProfileTitle" class="TableMenuRow2">
<th class="TableMenuColumn2">Updating Settings</th>
</tr>
<tr class="TableMenuRow3" id="ProfileUpdate">
<td class="TableMenuColumn3">
<div style="text-align: center;">
	<br /><?php echo $errorstr; ?>
	<a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Click here</a> to back to admin cp.<br />&#160;
	</div>
</td></tr>
<tr id="ProfileTitleEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&#160;</td>
</tr></table></div>
<?php } ?>
</td></tr>
</table>
<div>&#160;</div>
