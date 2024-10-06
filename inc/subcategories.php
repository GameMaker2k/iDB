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

    $FileInfo: subcategories.php - Last Update: 8/26/2024 SVN 1048 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "subcategories.php" || $File3Name == "/subcategories.php") {
    require('index.php');
    exit();
}
$viewvar = "view";
if ($_GET['act'] == "lowview") {
    $viewvar = "lowview";
}
if (!is_numeric($_GET['id'])) {
    $_GET['id'] = 1;
}
if (!isset($ThemeSet['ForumStyle'])) {
    $ThemeSet['ForumStyle'] = 1;
}
if (!is_numeric($ThemeSet['ForumStyle'])) {
    $ThemeSet['ForumStyle'] = 1;
}
if ($ThemeSet['ForumStyle'] > 2 || $ThemeSet['ForumStyle'] < 1) {
    $ThemeSet['ForumStyle'] = 1;
}
$checknum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i".$CatIgnoreList2." LIMIT 1", array($_GET['id'])), $SQLStat);
$checkquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i".$CatIgnoreList2." LIMIT 1", array($_GET['id']));
$checkresult = sql_query($checkquery, $SQLStat);
if ($checknum == 0) {
    redirect("location", $rbasedir.url_maker($exfile['index'], $Settings['file_ext'], "act=".$viewvar, $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
    sql_free_result($checkresult);
    ob_clean();
    header("Content-Type: text/plain; charset=".$Settings['charset']);
    $urlstatus = 302;
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    session_write_close();
    die();
}
if ($checknum >= 1) {
    $checkresult_array = sql_fetch_assoc($checkresult);
    $CategoryID = $checkresult_array['id'];
    $CategoryName = $checkresult_array['Name'];
    $CategoryShow = $checkresult_array['ShowCategory'];
    if ($CategoryShow == "no") {
        $_SESSION['ShowActHidden'] = "yes";
    }
    $CategoryType = $checkresult_array['CategoryType'];
    $InSubCategory = $checkresult_array['InSubCategory'];
    $SubShowForums = $checkresult_array['SubShowForums'];
    $CategoryType = strtolower($CategoryType);
    $SubShowForums = strtolower($SubShowForums);
    $SCategoryName = $CategoryName;
    if (!isset($CatPermissionInfo['CanViewCategory'][$CategoryID])) {
        $CatPermissionInfo['CanViewCategory'][$CategoryID] = "no";
    }
    if ($CatPermissionInfo['CanViewCategory'][$CategoryID] == "no" ||
        $CatPermissionInfo['CanViewCategory'][$CategoryID] != "yes") {
        redirect("location", $rbasedir.url_maker($exfile['index'], $Settings['file_ext'], "act=".$viewvar, $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
        ob_clean();
        header("Content-Type: text/plain; charset=".$Settings['charset']);
        $urlstatus = 302;
        gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        session_write_close();
        die();
    }
    if ($CatPermissionInfo['CanViewCategory'][$CategoryID] == "yes") {
        if (isset($_SESSION['OldViewingPage'])) {
            $_SESSION['AncientViewingPage'] = $_SESSION['OldViewingPage'];
        } else {
            $_SESSION['AncientViewingPage'] = url_maker(null, "no+ext", "act=".$viewvar, "&", "=", $prexqstr['index'], $exqstr['index']);
        }
        if (isset($_SESSION['OldViewingFile'])) {
            $_SESSION['AncientViewingFile'] = $_SESSION['OldViewingFile'];
        } else {
            if ($Settings['file_ext'] != "no+ext" && $Settings['file_ext'] != "no ext") {
                $_SESSION['AncientViewingFile'] = $exfile['index'].$Settings['file_ext'];
            }
            if ($Settings['file_ext'] == "no+ext" || $Settings['file_ext'] == "no ext") {
                $_SESSION['AncientViewingFile'] = $exfile['index'];
            }
        }
        if (isset($_SESSION['OldPreViewingTitle'])) {
            $_SESSION['AncientPreViewingTitle'] = $_SESSION['OldPreViewingTitle'];
        } else {
            $_SESSION['AncientPreViewingTitle'] = "Viewing";
        }
        if (isset($_SESSION['OldViewingTitle'])) {
            $_SESSION['AncientViewingTitle'] = $_SESSION['OldViewingTitle'];
        } else {
            $_SESSION['AncientViewingTitle'] = "Board index";
        }
        if (isset($_SESSION['OldExtraData'])) {
            $_SESSION['AncientExtraData'] = $_SESSION['OldExtraData'];
        } else {
            $_SESSION['AncientExtraData'] = "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;";
        }
        if (isset($_SESSION['ViewingPage'])) {
            $_SESSION['OldViewingPage'] = $_SESSION['ViewingPage'];
        } else {
            $_SESSION['OldViewingPage'] = url_maker(null, "no+ext", "act=".$viewvar, "&", "=", $prexqstr['index'], $exqstr['index']);
        }
        if (isset($_SESSION['ViewingFile'])) {
            $_SESSION['OldViewingFile'] = $_SESSION['ViewingFile'];
        } else {
            if ($Settings['file_ext'] != "no+ext" && $Settings['file_ext'] != "no ext") {
                $_SESSION['OldViewingFile'] = $exfile['index'].$Settings['file_ext'];
            }
            if ($Settings['file_ext'] == "no+ext" || $Settings['file_ext'] == "no ext") {
                $_SESSION['OldViewingFile'] = $exfile['index'];
            }
        }
        if (isset($_SESSION['PreViewingTitle'])) {
            $_SESSION['OldPreViewingTitle'] = $_SESSION['PreViewingTitle'];
        } else {
            $_SESSION['OldPreViewingTitle'] = "Viewing";
        }
        if (isset($_SESSION['ViewingTitle'])) {
            $_SESSION['OldViewingTitle'] = $_SESSION['ViewingTitle'];
        } else {
            $_SESSION['OldViewingTitle'] = "Board index";
        }
        if (isset($_SESSION['ExtraData'])) {
            $_SESSION['OldExtraData'] = $_SESSION['ExtraData'];
        } else {
            $_SESSION['OldExtraData'] = "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;";
        }
        $_SESSION['ViewingPage'] = url_maker(null, "no+ext", "act=".$viewvar."&id=".$CategoryID, "&", "=", $prexqstr[$CategoryType], $exqstr[$CategoryType]);
        if ($Settings['file_ext'] != "no+ext" && $Settings['file_ext'] != "no ext") {
            $_SESSION['ViewingFile'] = $exfile[$CategoryType].$Settings['file_ext'];
        }
        if ($Settings['file_ext'] == "no+ext" || $Settings['file_ext'] == "no ext") {
            $_SESSION['ViewingFile'] = $exfile[$CategoryType];
        }
        $_SESSION['PreViewingTitle'] = "Viewing SubCategory:";
        $_SESSION['ViewingTitle'] = $CategoryName;
        $_SESSION['ExtraData'] = "currentact:".$_GET['act']."; currentcategoryid:".$InSubCategory.",".$CategoryID."; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;";
        if ($InSubCategory != "0") {
            $iscnum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i".$CatIgnoreList2." LIMIT 1", array($InSubCategory)), $SQLStat);
            $iscquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i".$CatIgnoreList2." LIMIT 1", array($InSubCategory));
            $iscresult = sql_query($iscquery, $SQLStat);
            if ($iscnum >= 1) {
                $iscresult_array = sql_fetch_assoc($iscresult);
                $iscCategoryID = $iscresult_array['id'];
                $iscCategoryName = $iscresult_array['Name'];
                $iscCategoryShow = $iscresult_array['ShowCategory'];
                $iscCategoryType = $iscresult_array['CategoryType'];
                $iscCategoryType = strtolower($iscCategoryType);
            }
            if ($iscnum < 1) {
                $InSubCategory = "0";
            }
            sql_free_result($iscresult);
        }
        if ($_GET['act'] == "view") {
            ?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'], $Settings['file_ext'], "act=".$viewvar, $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a><?php if ($InSubCategory != "0") {
    echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$iscCategoryType], $Settings['file_ext'], "act=".$viewvar."&id=".$iscCategoryID."&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstr[$iscCategoryType], $exqstr[$iscCategoryType]); ?>"><?php echo $iscCategoryName; ?></a><?php } echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$CategoryType], $Settings['file_ext'], "act=".$viewvar."&id=".$CategoryID, $Settings['qstr'], $Settings['qsep'], $prexqstr[$CategoryType], $exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a></div>
<div class="DivNavLinks">&#160;</div>
<?php } if ($_GET['act'] == "lowview") { ?>
<div style="font-size: 1.0em; font-weight: bold; margin-bottom: 10px; padding-top: 3px; width: auto;">Full Version: <a href="<?php echo url_maker($exfile[$CategoryType], $Settings['file_ext'], "act=view&id=".$CategoryID, $Settings['qstr'], $Settings['qsep'], $prexqstr[$CategoryType], $exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a></div>
<div style="font-size: 11px; font-weight: bold; padding: 10px; border: 1px solid gray;"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'], $Settings['file_ext'], "act=lowview", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a><?php if ($InSubCategory != "0") {
    echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$iscCategoryType], $Settings['file_ext'], "act=lowview&id=".$iscCategoryID."&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstr[$iscCategoryType], $exqstr[$iscCategoryType]); ?>"><?php echo $iscCategoryName; ?></a><?php } echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$CategoryType], $Settings['file_ext'], "act=lowview&id=".$CategoryID, $Settings['qstr'], $Settings['qsep'], $prexqstr[$CategoryType], $exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a></div>
<div>&#160;</div>
<?php }
if ($CategoryType == "category") {
    redirect("location", $rbasedir.url_maker($exfile['category'], $Settings['file_ext'], "act=".$_GET['act']."&id=".$_GET['id'], $Settings['qstr'], $Settings['qsep'], $prexqstr['category'], $exqstr['category'], false));
    ob_clean();
    header("Content-Type: text/plain; charset=".$Settings['charset']);
    $urlstatus = 302;
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    session_write_close();
    die();
}
        sql_free_result($checkresult);
        $prenum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."categories\" WHERE \"ShowCategory\"='yes' AND \"InSubCategory\"=%i".$CatIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($_GET['id'])), $SQLStat);
        $prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" WHERE \"ShowCategory\"='yes' AND \"InSubCategory\"=%i".$CatIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($_GET['id']));
        $preresult = sql_query($prequery, $SQLStat);
        $prei = 0;
        while ($prei < $prenum) {
            $preresult_array = sql_fetch_assoc($preresult);
            $CategoryID = $preresult_array['id'];
            $CategoryName = $preresult_array['Name'];
            $CategoryShow = $preresult_array['ShowCategory'];
            $CategoryType = $preresult_array['CategoryType'];
            $SSubShowForums = $preresult_array['SubShowForums'];
            $CategoryDescription = $preresult_array['Description'];
            $CategoryType = strtolower($CategoryType);
            $SubShowForums = strtolower($SubShowForums);
            if (isset($CatPermissionInfo['CanViewCategory'][$CategoryID]) &&
                $CatPermissionInfo['CanViewCategory'][$CategoryID] == "yes") {
                $num = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."forums\" WHERE \"ShowForum\"='yes' AND \"CategoryID\"=%i AND \"InSubForum\"=0".$ForumIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($CategoryID)), $SQLStat);
                $query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"ShowForum\"='yes' AND \"CategoryID\"=%i AND \"InSubForum\"=0".$ForumIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($CategoryID));
                $result = sql_query($query, $SQLStat);
                $i = 0;
                if ($num >= 1) {
                    if ($_GET['act'] == "view") {
                        ?>
<div class="Table1Border">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile[$CategoryType], $Settings['file_ext'], "act=".$viewvar."&id=".$CategoryID, $Settings['qstr'], $Settings['qsep'], $prexqstr[$CategoryType], $exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a></span></div>
<?php } ?>
<table class="Table1" id="SubCat<?php echo $CategoryID; ?>">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr id="SubCatStart<?php echo $CategoryID; ?>" class="TableRow1">
<td class="TableColumn1" colspan="5"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile[$CategoryType], $Settings['file_ext'], "act=".$viewvar."&id=".$CategoryID, $Settings['qstr'], $Settings['qsep'], $prexqstr[$CategoryType], $exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a></span>
</td>
</tr><?php } ?>
<tr id="ForumStatRow<?php echo $CategoryID; ?>" class="TableRow2">
<th class="TableColumn2" style="width: 4%;">&#160;</th>
<th class="TableColumn2" style="width: 58%;">Forum</th>
<th class="TableColumn2" style="width: 7%;">Topics</th>
<th class="TableColumn2" style="width: 7%;">Posts</th>
<th class="TableColumn2" style="width: 24%;">Last Topic</th>
</tr>
<?php } if ($_GET['act'] == "lowview") { ?>
<div style="padding: 10px; border: 1px solid gray;">
<ul style="list-style-type: none;">
<li style="font-weight: bold;"><a href="<?php echo url_maker($exfile[$CategoryType], $Settings['file_ext'], "act=lowview&id=".$CategoryID, $Settings['qstr'], $Settings['qsep'], $prexqstr[$CategoryType], $exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a></li><li>
<?php }
while ($i < $num) {
    $result_array = sql_fetch_assoc($result);
    $ForumID = $result_array['id'];
    $ForumName = $result_array['Name'];
    $ForumShow = $result_array['ShowForum'];
    $ForumType = $result_array['ForumType'];
    $ForumShowTopics = $result_array['CanHaveTopics'];
    $ForumShowTopics = strtolower($ForumShowTopics);
    $NumTopics = $result_array['NumTopics'];
    $NumPosts = $result_array['NumPosts'];
    $NumRedirects = $result_array['Redirects'];
    $ForumDescription = $result_array['Description'];
    $ForumType = strtolower($ForumType);
    $sflist = null;
    $gltf = array(null);
    $gltf[0] = $ForumID;
    if ($ForumType == "subforum") {
        $apcnum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."forums\" WHERE \"ShowForum\"='yes' AND \"InSubForum\"=%i".$ForumIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($ForumID)), $SQLStat);
        $apcquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"ShowForum\"='yes' AND \"InSubForum\"=%i".$ForumIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($ForumID));
        $apcresult = sql_query($apcquery, $SQLStat);
        $apci = 0;
        $apcl = 1;
        if ($apcnum >= 1) {
            while ($apci < $apcnum) {
                $apcresult_array = sql_fetch_assoc($apcresult);
                $NumsTopics = $apcresult_array['NumTopics'];
                $NumTopics = $NumsTopics + $NumTopics;
                $NumsPosts = $apcresult_array['NumPosts'];
                $NumPosts = $NumsPosts + $NumPosts;
                $SubsForumID = $apcresult_array['id'];
                $SubsForumName = $apcresult_array['Name'];
                $SubsForumType = $apcresult_array['ForumType'];
                $SubsForumShowTopics = $result_array['CanHaveTopics'];
                if (isset($PermissionInfo['CanViewForum'][$SubsForumID]) &&
                    $PermissionInfo['CanViewForum'][$SubsForumID] == "yes") {
                    $ExStr = "";
                    if ($SubsForumType != "redirect" &&
                        $SubsForumShowTopics != "no") {
                        $ExStr = "&page=1";
                    }
                    if ($_GET['act'] == "view") {
                        $sfurl = url_maker($exfile[$SubsForumType], $Settings['file_ext'], "act=".$viewvar."&id=".$SubsForumID.$ExStr, $Settings['qstr'], $Settings['qsep'], $prexqstr[$SubsForumType], $exqstr[$SubsForumType]);
                        $sfurl = "<a href=\"".$sfurl."\">".$SubsForumName."</a>";
                        if ($apcl == 1) {
                            $sflist = "Subforums:";
                            $sflist = $sflist." ".$sfurl;
                        }
                        if ($apcl > 1) {
                            $sflist = $sflist.", ".$sfurl;
                        }
                    }
                    if ($_GET['act'] == "lowview") {
                        $shownum = null;
                        if ($SubsForumType == "redirect") {
                            $shownum = "(".$NumRedirects." redirects)";
                        }
                        if ($SubsForumType != "redirect") {
                            $shownum = "(".$NumsPosts." posts)";
                        }
                        $sfurl = url_maker($exfile[$SubsForumType], $Settings['file_ext'], "act=lowview&id=".$SubsForumID.$ExStr, $Settings['qstr'], $Settings['qsep'], $prexqstr[$SubsForumType], $exqstr[$SubsForumType]);
                        $sfurl = "<li><ul style=\"list-style-type: none;\"><li><a href=\"".$sfurl."\">".$SubsForumName."</a> <span style=\"color: gray; font-size: 10px;\">".$shownum."</span></li></ul></li>";
                        if ($apcl == 1) {
                            $sflist = $sflist." ".$sfurl;
                        }
                        if ($apcl > 1) {
                            $sflist = $sflist." ".$sfurl;
                        }
                    }
                    $gltf[$apcl] = $SubsForumID;
                    ++$apcl;
                }
                ++$apci;
            }
            sql_free_result($apcresult);
        }
    }
    $gltf = array(null);
    $gltf[0] = $ForumID;
    if ($ForumType == "subforum") {
        $apcnum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."forums\" WHERE \"ShowForum\"='yes' AND \"InSubForum\"=%i".$ForumIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($ForumID)), $SQLStat);
        $apcquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"ShowForum\"='yes' AND \"InSubForum\"=%i".$ForumIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($ForumID));
        $apcresult = sql_query($apcquery, $SQLStat);
        $apci = 0;
        $apcl = 1;
        if ($apcnum >= 1) {
            while ($apci < $apcnum) {
                $apcresult_array = sql_fetch_assoc($apcresult);
                $NumsTopics = $apcresult_array['NumTopics'];
                $NumTopics = $NumsTopics + $NumTopics;
                $NumsPosts = $apcresult_array['NumPosts'];
                $NumPosts = $NumsPosts + $NumPosts;
                $SubsForumID = $apcresult_array['id'];
                $SubsForumName = $apcresult_array['Name'];
                $SubsForumType = $apcresult_array['ForumType'];
                if (isset($PermissionInfo['CanViewForum'][$SubsForumID]) &&
                    $PermissionInfo['CanViewForum'][$SubsForumID] == "yes") {
                    if ($_GET['act'] == "view") {
                        $sfurl = url_maker($exfile[$SubsForumType], $Settings['file_ext'], "act=".$viewvar."&id=".$SubsForumID.$ExStr, $Settings['qstr'], $Settings['qsep'], $prexqstr[$SubsForumType], $exqstr[$SubsForumType]);
                        $sfurl = "<a href=\"".$sfurl."\">".$SubsForumName."</a>";
                        if ($apcl == 1) {
                            $sflist = "Subforums:";
                            $sflist = $sflist." ".$sfurl;
                        }
                        if ($apcl > 1) {
                            $sflist = $sflist.", ".$sfurl;
                        }
                    }
                    $gltf[$apcl] = $SubsForumID;
                    ++$apcl;
                }
                ++$apci;
            }
            sql_free_result($apcresult);
        }
    }
    if ($ForumType == "subforum") {
        $apcnum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."forums\" WHERE \"ShowForum\"='yes' AND \"InSubForum\"=%i".$ForumIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($ForumID)), $SQLStat);
        $apcquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"ShowForum\"='yes' AND \"InSubForum\"=%i".$ForumIgnoreList2." ORDER BY \"OrderID\" ASC, \"id\" ASC", array($ForumID));
        $apcresult = sql_query($apcquery, $SQLStat);
        $apci = 0;
        $apcl = 1;
        if ($apcnum >= 1) {
            while ($apci < $apcnum) {
                $apcresult_array = sql_fetch_assoc($apcresult);
                $NumsTopics = $apcresult_array['NumTopics'];
                $NumTopics = $NumsTopics + $NumTopics;
                $NumsPosts = $apcresult_array['NumPosts'];
                $NumPosts = $NumsPosts + $NumPosts;
                $SubsForumID = $apcresult_array['id'];
                if (isset($PermissionInfo['CanViewForum'][$SubsForumID]) &&
                    $PermissionInfo['CanViewForum'][$SubsForumID] == "yes") {
                    $gltf[$apcl] = $SubsForumID;
                    ++$apcl;
                }
                ++$apci;
            }
            sql_free_result($apcresult);
        }
    }
    if (isset($PermissionInfo['CanViewForum'][$ForumID]) &&
        $PermissionInfo['CanViewForum'][$ForumID] == "yes") {
        $LastTopic = "&#160;<br />&#160;<br />&#160;";
        if (!isset($LastTopic)) {
            $LastTopic = null;
        }
        $gltnum = count($gltf);
        $glti = 0;
        $OldUpdateTime = 0;
        $UseThisFonum = null;
        if ($ForumType == "subforum") {
            while ($glti < $gltnum) {
                $ExtraIgnores = null;
                if ($PermissionInfo['CanModForum'][$gltf[$glti]] == "no") {
                    $ExtraIgnores = " AND \"Closed\"<>3";
                }
                $gltfonum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."topics\" WHERE \"ForumID\"=%i".$ExtraIgnores.$ForumIgnoreList4." ORDER BY \"LastUpdate\" DESC LIMIT 1", array($gltf[$glti])), $SQLStat);
                $gltfoquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."topics\" WHERE \"ForumID\"=%i".$ExtraIgnores.$ForumIgnoreList4." ORDER BY \"LastUpdate\" DESC LIMIT 1", array($gltf[$glti]));
                $gltforesult = sql_query($gltfoquery, $SQLStat);
                if ($gltfonum > 0) {
                    $gltforesult_array = sql_fetch_assoc($gltforesult);
                    $NewUpdateTime = $gltforesult_array['LastUpdate'];
                    if ($NewUpdateTime > $OldUpdateTime) {
                        $UseThisFonum = $gltf[$glti];
                        $OldUpdateTime = $NewUpdateTime;
                    }
                    sql_free_result($gltforesult);
                }
                ++$glti;
            }
            if ($UseThisFonum == 0) {
                $UseThisFonum = $gltf[0];
            }
        }
        if ($ForumType != "subforum" && $ForumType != "redirect") {
            $UseThisFonum = $gltf[0];
        }
        if ($ForumType != "redirect") {
            $ExtraIgnores = null;
            if ($PermissionInfo['CanModForum'][$UseThisFonum] == "no") {
                $ExtraIgnores = " AND \"Closed\"<>3";
            }
            $gltnum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."topics\" WHERE (\"ForumID\"=%i".$ExtraIgnores.$ForumIgnoreList4.") OR (\"OldForumID\"=%i".$ExtraIgnores.$ForumIgnoreList4.") ORDER BY \"LastUpdate\" DESC LIMIT 1", array($UseThisFonum,$UseThisFonum)), $SQLStat);
            $gltquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."topics\" WHERE (\"ForumID\"=%i".$ExtraIgnores.$ForumIgnoreList4.") OR (\"OldForumID\"=%i".$ExtraIgnores.$ForumIgnoreList4.") ORDER BY \"LastUpdate\" DESC LIMIT 1", array($UseThisFonum,$UseThisFonum));
            $gltresult = sql_query($gltquery, $SQLStat);
            if ($gltnum > 0) {
                $gltresult_array = sql_fetch_assoc($gltresult);
                $TopicID = $gltresult_array['id'];
                $TopicName = $gltresult_array['TopicName'];
                $NumReplys = $gltresult_array['NumReply'];
                $NumPages = null;
                $NumRPosts = $NumReplys + 1;
                if (!isset($Settings['max_posts'])) {
                    $Settings['max_posts'] = 10;
                }
                if ($NumRPosts > $Settings['max_posts']) {
                    $NumPages = ceil($NumRPosts / $Settings['max_posts']);
                }
                if ($NumRPosts <= $Settings['max_posts']) {
                    $NumPages = 1;
                }
                $TopicName1 = pre_substr($TopicName, 0, 20);
                $oldtopicname = $TopicName;
                if (pre_strlen($TopicName) > 20) {
                    $TopicName1 = $TopicName1."...";
                    $TopicName = $TopicName1;
                }
                $glrnum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."posts\" WHERE \"TopicID\"=%i ORDER BY \"TimeStamp\" DESC LIMIT 1", array($TopicID)), $SQLStat);
                $glrquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."posts\" WHERE \"TopicID\"=%i ORDER BY \"TimeStamp\" DESC LIMIT 1", array($TopicID));
                $glrresult = sql_query($glrquery, $SQLStat);
                if ($glrnum > 0) {
                    $glrresult_array = sql_fetch_assoc($glrresult);
                    $ReplyID = $glrresult_array['id'];
                    $UsersID = $glrresult_array['UserID'];
                    $GuestsName = $glrresult_array['GuestName'];
                    $TimeStamp = $glrresult_array['TimeStamp'];
                    $tmpusrcurtime = new DateTime();
                    $tmpusrcurtime->setTimestamp($TimeStamp);
                    $tmpusrcurtime->setTimezone($usertz);
                    $TimeStamp = $tmpusrcurtime->format($_SESSION['iDBDateFormat'].", ".$_SESSION['iDBTimeFormat']);
                    sql_free_result($glrresult);
                }
                $PreUsersName = GetUserName($UsersID, $Settings['sqltable'], $SQLStat);
                if ($PreUsersName['Name'] === null) {
                    $UsersID = -1;
                    $PreUsersName = GetUserName($UsersID, $Settings['sqltable'], $SQLStat);
                }
                $UsersName = $PreUsersName['Name'];
                $UsersHidden = $PreUsersName['Hidden'];
                if ($UsersName == "Guest") {
                    $UsersName = $GuestsName;
                    if ($UsersName == null) {
                        $UsersName = "Guest";
                    }
                }
                $UsersName1 = pre_substr($UsersName, 0, 20);
                $oldusername = $UsersName;
                if (pre_strlen($UsersName) > 20) {
                    $UsersName1 = $UsersName1."...";
                    $UsersName = $UsersName1;
                }
                $lul = null;
                if ($UsersID > 0 && $UsersHidden == "no") {
                    $lul = url_maker($exfile['member'], $Settings['file_ext'], "act=".$viewvar."&id=".$UsersID, $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member']);
                    $LastTopic = $TimeStamp."<br />\nTopic: <a href=\"".url_maker($exfile['topic'], $Settings['file_ext'], "act=".$viewvar."&id=".$TopicID."&page=".$NumPages, $Settings['qstr'], $Settings['qsep'], $prexqstr['topic'], $exqstr['topic']).$qstrhtml."&#35;reply".$NumRPosts."\" title=\"".$oldtopicname."\">".$TopicName."</a><br />\nUser: <a href=\"".$lul."\" title=\"".$oldusername."\">".$UsersName."</a>";
                }
                if ($UsersID <= 0 || $UsersHidden == "yes") {
                    if ($UsersID == -1) {
                        $UserPre = "Guest:";
                    }
                    if (($UsersID < -1 && $UsersHidden == "yes") || $UsersID == 0 || ($UsersID > 0 && $UsersHidden == "yes")) {
                        $UserPre = "Hidden:";
                    }
                    $LastTopic = $TimeStamp."<br />\nTopic: <a href=\"".url_maker($exfile['topic'], $Settings['file_ext'], "act=".$viewvar."&id=".$TopicID."&page=".$NumPages, $Settings['qstr'], $Settings['qsep'], $prexqstr['topic'], $exqstr['topic']).$qstrhtml."&#35;reply".$NumRPosts."\" title=\"".$oldtopicname."\">".$TopicName."</a><br />\n".$UserPre." <span title=\"".$oldusername."\">".$UsersName."</span>";
                }
            }
            if ($LastTopic == null) {
                $LastTopic = "&#160;<br />&#160;<br />&#160;";
            }
            sql_free_result($gltresult);
        }
        if ($ForumType == "redirect") {
            $LastTopic = "&#160;<br />Redirects: ".$NumRedirects."<br />&#160;";
        }
        $PreForum = $ThemeSet['ForumIcon'];
        if ($ForumType == "forum") {
            $PreForum = $ThemeSet['ForumIcon'];
        }
        if ($ForumType == "subforum") {
            $PreForum = $ThemeSet['SubForumIcon'];
        }
        if ($ForumType == "redirect") {
            $PreForum = $ThemeSet['RedirectIcon'];
        }
        $ExStr = "";
        if ($ForumType != "redirect" &&
            $ForumShowTopics != "no") {
            $ExStr = "&page=1";
        }
        if ($ThemeSet['ForumStyle'] == 1) {
            $ForumClass[1] = " class=\"TableColumn3\" ";
            $ForumClass[2] = " class=\"TableColumn3\" ";
            $ForumClass[3] = " class=\"TableColumn3\" ";
            $ForumClass[4] = " class=\"TableColumn3\" ";
            $ForumClass[5] = " class=\"TableColumn3\" ";
        }
        if ($ThemeSet['ForumStyle'] == 2) {
            $ForumClass[1] = " class=\"TableColumn3\" ";
            $ForumClass[2] = " class=\"TableColumn3\" ";
            $ForumClass[3] = " class=\"TableColumn3Alt\" ";
            $ForumClass[4] = " class=\"TableColumn3Alt\" ";
            $ForumClass[5] = " class=\"TableColumn3Alt\" ";
        }
        if ($_GET['act'] == "view") {
            ?>
<tr class="TableRow3" id="Forum<?php echo $ForumID; ?>">
<td<?php echo $ForumClass[1]; ?>><div class="forumicon">
<?php echo $PreForum; ?></div></td>
<td<?php echo $ForumClass[2]; ?>><div class="forumname"><a href="<?php echo url_maker($exfile[$ForumType], $Settings['file_ext'], "act=".$viewvar."&id=".$ForumID.$ExStr, $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType]); ?>"<?php if ($ForumType == "redirect") {
    echo " onclick=\"window.open(this.href);return false;\"";
} ?>><?php echo $ForumName; ?></a></div>
<div class="forumdescription"><?php echo $ForumDescription; ?><br />
<?php echo $sflist; ?></div></td>
<td<?php echo $ForumClass[3]; ?>style="text-align: center;"><?php echo $NumTopics; ?></td>
<td<?php echo $ForumClass[4]; ?>style="text-align: center;"><?php echo $NumPosts; ?></td>
<td<?php echo $ForumClass[5]; ?>><?php echo $LastTopic; ?></td>
</tr>
<?php }
        if ($num >= 1) {
            if ($_GET['act'] == "view") {
                ?>
<tr id="SubCatEnd<?php echo $CategoryID; ?>" class="TableRow4">
<td class="TableColumn4" colspan="5">&#160;</td>
</tr>
</table></div>
<div class="DivSubCategories">&#160;</div>
<?php } if ($_GET['act'] == "lowview") { ?>
<ul style="list-style-type: none;"><li>
<a href="<?php echo url_maker($exfile[$ForumType], $Settings['file_ext'], "act=lowview&id=".$ForumID.$ExStr, $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType]); ?>"<?php if ($ForumType == "redirect") {
    echo " onclick=\"window.open(this.href);return false;\"";
} ?>><?php echo $ForumName; ?></a> <span style="color: gray; font-size: 10px;"><?php echo $shownum; ?></span></li>
<?php echo $sflist; ?></ul>
<?php } ++$i;
        } sql_free_result($result);
    } ?>
</li></ul>
<ul style="list-style-type: none;">
<?php }
}
            } ++$prei;
        }
    }
    sql_free_result($preresult);
    $CatCheck = "skip";
    if ($SubShowForums != "yes") {
        $CategoryName = $SCategoryName;
    }
    if ($SubShowForums != "no") {
        require($SettDir['inc'].'categories.php');
    }
}
?>
