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

    $FileInfo: topics.php - Last Update: 8/26/2024 SVN 1048 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="topics.php"||$File3Name=="/topics.php") {
	require('index.php');
	exit(); }
$pstring = null; $pagenum = null;
$viewvar = "view";
if($_GET['act']=="lowview") { 
	$viewvar = "lowview"; }
if(!is_numeric($_GET['id'])) { $_GET['id'] = null; }
if(!is_numeric($_GET['page'])) { $_GET['page'] = 1; }
if(!isset($_GET['st'])) { $_GET['st'] = 0; }
if(!is_numeric($_GET['st'])) { $_GET['st'] = 0; }
$prenum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i".$ForumIgnoreList2." LIMIT 1", array($_GET['id'])), $SQLStat);
$prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i".$ForumIgnoreList2." LIMIT 1", array($_GET['id']));
$preresult=sql_query($prequery,$SQLStat);
if($prenum==0) { redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); sql_free_result($preresult);
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($prenum>=1) {
$preresult_array = sql_fetch_assoc($preresult);
$ForumID=$preresult_array['id'];
$ForumCatID=$preresult_array['CategoryID'];
$ForumName=$preresult_array['Name'];
$ForumType=$preresult_array['ForumType'];
$ForumShow=$preresult_array['ShowForum'];
if($ForumShow=="no") { $_SESSION['ShowActHidden'] = "yes"; }
$InSubForum=$preresult_array['InSubForum'];
$RedirectURL=$preresult_array['RedirectURL'];
$RedirectTimes=$preresult_array['Redirects'];
$NumberViews=$preresult_array['NumViews'];
$NumberPosts=$preresult_array['NumPosts'];
$NumberTopics=$preresult_array['NumTopics'];
$PostCountAdd=$preresult_array['PostCountAdd'];
$CanHaveTopics=$preresult_array['CanHaveTopics'];
$HotTopicPosts=$preresult_array['HotTopicPosts'];
if($HotTopicPosts!=0&&is_numeric($HotTopicPosts)) {
	$Settings['hot_topic_num'] = $HotTopicPosts; }
if(!is_numeric($Settings['hot_topic_num'])) {
	$Settings['hot_topic_num'] = 15; }
$ForumPostCountView=$preresult_array['PostCountView'];
$ForumKarmaCountView=$preresult_array['KarmaCountView'];
sql_free_result($preresult);
$ForumType = strtolower($ForumType); $CanHaveTopics = strtolower($CanHaveTopics);
if($CanHaveTopics=="yes"&&$ForumType=="subforum") { 
if($_GET['act']=="create"||$_GET['act']=="maketopic"||
	$_POST['act']=="maketopics") { $ForumCheck = "skip"; } }
$catcheck = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."categories\" WHERE \"id\"=%i".$CatIgnoreList2."  LIMIT 1", array($ForumCatID));
$catresult=sql_query($catcheck,$SQLStat);
$catresult_array = sql_fetch_assoc($catresult);
$CategoryID=$catresult_array['id'];
$CategoryName=$catresult_array['Name'];
$CategoryShow=$catresult_array['ShowCategory'];
if($CategoryShow=="no") { $_SESSION['ShowActHidden'] = "yes"; }
$CategoryType=$catresult_array['CategoryType'];
$InSubCategory=$catresult_array['InSubCategory'];
$CategoryPostCountView=$catresult_array['PostCountView'];
$CategoryKarmaCountView=$catresult_array['KarmaCountView'];
sql_free_result($catresult);
if($GroupInfo['HasAdminCP']!="yes"||$GroupInfo['HasModCP']!="yes") {
if($MyPostCountChk==null) { $MyPostCountChk = 0; }
if($MyKarmaCount==null) { $MyKarmaCount = 0; }
if($ForumPostCountView!=0&&$MyPostCountChk<$ForumPostCountView) {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($CategoryPostCountView!=0&&$MyPostCountChk<$CategoryPostCountView) {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($ForumKarmaCountView!=0&&$MyKarmaCount<$ForumKarmaCountView) {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($CategoryKarmaCountView!=0&&$MyKarmaCount<$CategoryKarmaCountView) {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); } }
if($InSubForum!="0") {
$isfnum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i".$ForumIgnoreList2." LIMIT 1", array($InSubForum)), $SQLStat);
$isfquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i".$ForumIgnoreList2." LIMIT 1", array($InSubForum));
$isfresult=sql_query($isfquery,$SQLStat);
if($isfnum>=1) {
$isfresult_array = sql_fetch_assoc($isfresult);
$isfForumID=$isfresult_array['id'];
$isfForumCatID=$isfresult_array['CategoryID'];
$isfForumName=$isfresult_array['Name'];
$isfForumType=$isfresult_array['ForumType'];
$isfForumType = strtolower($isfForumType);
$isfRedirectURL=$isfresult_array['RedirectURL']; }
if($isfnum<1) { $InSubForum = "0"; } 
sql_free_result($isfresult); }
if($ForumCheck!="skip") {
if(isset($_SESSION['OldViewingPage'])) { $_SESSION['AncientViewingPage'] = $_SESSION['OldViewingPage']; } else { $_SESSION['AncientViewingPage'] = url_maker(null,"no+ext","act=".$viewvar,"&","=",$prexqstr['index'],$exqstr['index']); }
if(isset($_SESSION['OldViewingFile'])) { $_SESSION['AncientViewingFile'] = $_SESSION['OldViewingFile']; } else { 
	 if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
	    $_SESSION['AncientViewingFile'] = $exfile['index'].$Settings['file_ext']; }
	 if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
	    $_SESSION['AncientViewingFile'] = $exfile['index']; } }
if(isset($_SESSION['OldPreViewingTitle'])) { $_SESSION['AncientPreViewingTitle'] = $_SESSION['OldPreViewingTitle']; } else { $_SESSION['AncientPreViewingTitle'] = "Viewing"; }
if(isset($_SESSION['OldViewingTitle'])) { $_SESSION['AncientViewingTitle'] = $_SESSION['OldViewingTitle']; } else { $_SESSION['AncientViewingTitle'] = "Board index"; }
if(isset($_SESSION['OldExtraData'])) { $_SESSION['AncientExtraData'] = $_SESSION['OldExtraData']; } else { $_SESSION['AncientExtraData'] = "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;"; }
if(isset($_SESSION['ViewingPage'])) { $_SESSION['OldViewingPage'] = $_SESSION['ViewingPage']; } else { $_SESSION['OldViewingPage'] = url_maker(null,"no+ext","act=".$viewvar,"&","=",$prexqstr['index'],$exqstr['index']); }
if(isset($_SESSION['ViewingFile'])) { $_SESSION['OldViewingFile'] = $_SESSION['ViewingFile']; } else { 
	 if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
	    $_SESSION['OldViewingFile'] = $exfile['index'].$Settings['file_ext']; }
	 if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
	    $_SESSION['OldViewingFile'] = $exfile['index']; } }
if(isset($_SESSION['PreViewingTitle'])) { $_SESSION['OldPreViewingTitle'] = $_SESSION['PreViewingTitle']; } else { $_SESSION['OldPreViewingTitle'] = "Viewing"; }
if(isset($_SESSION['ViewingTitle'])) { $_SESSION['OldViewingTitle'] = $_SESSION['ViewingTitle']; } else { $_SESSION['OldViewingTitle'] = "Board index"; }
if(isset($_SESSION['ExtraData'])) { $_SESSION['OldExtraData'] = $_SESSION['ExtraData']; } else { $_SESSION['OldExtraData'] = "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;"; }
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=".$viewvar."&id=".$ForumID."&page=".$_GET['page'],"&","=",$prexqstr[$ForumType],$exqstr[$ForumType]);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile[$ForumType].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile[$ForumType]; }
$_SESSION['PreViewingTitle'] = "Viewing Forum:";
$_SESSION['ViewingTitle'] = $ForumName;
$_SESSION['ExtraData'] = "currentact:".$_GET['act']."; currentcategoryid:".$InSubCategory.",".$CategoryID."; currentforumid:".$InSubForum.",".$ForumID."; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;"; 
if($_GET['act']=="view") {
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$CategoryType],$Settings['file_ext'],"act=".$viewvar."&id=".$ForumCatID,$Settings['qstr'],$Settings['qsep'],$prexqstr[$CategoryType],$exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a><?php if($InSubForum!="0") { echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$isfForumType],$Settings['file_ext'],"act=".$viewvar."&id=".$isfForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$isfForumType],$exqstr[$isfForumType]); ?>"><?php echo $isfForumName; ?></a><?php } echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=".$viewvar."&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"><?php echo $ForumName; ?></a></div>
<div class="DivNavLinks">&#160;</div>
<?php } if($_GET['act']=="lowview") { ?>
<div style="font-size: 1.0em; font-weight: bold; margin-bottom: 10px; padding-top: 3px; width: auto;">Full Version: <a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=view&id=".$ForumID."&page=".$_GET['page'],$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ForumName; ?></a></div>
<div style="font-size: 11px; font-weight: bold; padding: 10px; border: 1px solid gray;"><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=".$_GET['act'],$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$CategoryType],$Settings['file_ext'],"act=".$_GET['act']."&id=".$ForumCatID,$Settings['qstr'],$Settings['qsep'],$prexqstr[$CategoryType],$exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a><?php if($InSubForum!="0") { echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$isfForumType],$Settings['file_ext'],"act=".$_GET['act']."&id=".$isfForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$isfForumType],$exqstr[$isfForumType]); ?>"><?php echo $isfForumName; ?></a><?php } echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=".$_GET['act']."&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>"><?php echo $ForumName; ?></a></div>
<div>&#160;</div>
<?php } }
if(!isset($CatPermissionInfo['CanViewCategory'][$ForumCatID])) {
	$CatPermissionInfo['CanViewCategory'][$ForumCatID] = "no"; }
if($CatPermissionInfo['CanViewCategory'][$ForumCatID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$ForumCatID]!="yes") {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if(!isset($PermissionInfo['CanViewForum'][$ForumID])) {
	$PermissionInfo['CanViewForum'][$ForumID] = "no"; }
if($PermissionInfo['CanViewForum'][$ForumID]=="no"||
	$PermissionInfo['CanViewForum'][$ForumID]!="yes") {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($CatPermissionInfo['CanViewCategory'][$ForumCatID]=="yes"&&
	$PermissionInfo['CanViewForum'][$ForumID]=="yes") {
if($ForumType!="redirect") {
if($NumberViews==0||$NumberViews==null) { $NewNumberViews = 1; }
if($NumberViews!=0&&$NumberViews!=null) { $NewNumberViews = $NumberViews + 1; }
$viewup = sql_pre_query("UPDATE \"".$Settings['sqltable']."forums\" SET \"NumViews\"=%i WHERE \"id\"=%i", array($NewNumberViews,$_GET['id']));
sql_query($viewup,$SQLStat); }
if($ForumType=="redirect") {
if($RedirectTimes==0||$RedirectTimes==null) { $NewRedirTime = 1; }
if($RedirectTimes!=0&&$RedirectTimes!=null) { $NewRedirTime = $RedirectTimes + 1; }
$redirup = sql_pre_query("UPDATE \"".$Settings['sqltable']."forums\" SET \"Redirects\"=%i WHERE \"id\"=%i", array($NewRedirTime,$_GET['id']));
sql_query($redirup,$SQLStat);
if($RedirectURL!="http://"&&$RedirectURL!="") {
redirect("location",$RedirectURL,0,null,false); ob_clean();
header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($RedirectURL=="http://"||$RedirectURL=="") {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); } }
if($ForumCheck!="skip") {
if($ForumType=="subforum") {
redirect("location",$rbasedir.url_maker($exfile['subforum'],$Settings['file_ext'],"act=".$_GET['act']."&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['subforum'],$exqstr['subforum'],FALSE));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); } }
if($_GET['act']!="view"&&$_GET['act']!="lowview") { ?>
<table style="width: 100%;" class="Table2">
<tr>
 <td style="width: 30%; text-align: left;"><?php echo $pstring; ?></td>
 <td style="width: 70%; text-align: right;">
 <?php if($PermissionInfo['CanMakeTopics'][$ForumID]=="yes"&&$CanHaveTopics=="yes") { ?>
 <a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=create&id=".$ForumID,$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ThemeSet['NewTopic']; ?></a>
 <?php } ?></td>
</tr>
</table>
<div class="DivTable2">&#160;</div>
<?php } if($_GET['act']=="view"||$_GET['act']=="lowview") {
// Initialize NumberTopics and Pagination Settings
$NumberTopics = $NumberTopics ?? 0;
$num = $NumberTopics;
$Settings['max_topics'] = $Settings['max_topics'] ?? 10;

// Validate current page number
$_GET['page'] = max(1, (int)($_GET['page'] ?? 1));

// Determine the start and end limits for topics on the current page
$nums = $_GET['st'] > 0 ? (int)$_GET['st'] : $_GET['page'] * $Settings['max_topics'];
$nums = min($nums, $num);
$numz = max(0, $nums - $Settings['max_topics']);

// Calculate the next and previous page numbers
$nextpage = ($nums < $num) ? $_GET['page'] + 1 : $_GET['page'];
$backpage = ($_GET['page'] > 1) ? $_GET['page'] - 1 : 1;

// Calculate total pages and initialize Pages array
$Pages = [];
for ($l = 1, $pnum = $num; $pnum > 0; ++$l) {
    $pnum = max(0, $pnum - $Settings['max_topics']);
    $Pages[$l] = $l;
}

// Calculate the page limit for SQL query
$snumber = $_GET['page'] - 1;
$PageLimit = $_GET['st'] > 0 ? (int)$_GET['st'] : max(0, $Settings['max_topics'] * $snumber);

// Fetch topics with pagination
$SQLimit = getSQLLimitClause($Settings['sqltype'], $Settings['max_topics'], $PageLimit);
$ExtraIgnores = $PermissionInfo['CanModForum'][$_GET['id']] == "no" ? " AND \"Closed\"<>3" : "";

$num = sql_count_rows(sql_pre_query(
    "SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."topics\" 
    WHERE (\"ForumID\"=%i".$ExtraIgnores.$ForumIgnoreList4.") 
    OR (\"OldForumID\"=%i".$ExtraIgnores.$ForumIgnoreList4.") 
    OR (\"Pinned\"=2".$ExtraIgnores.$ForumIgnoreList4.") 
    ORDER BY \"Pinned\" DESC, \"LastUpdate\" DESC", 
    array($_GET['id'], $_GET['id'])
), $SQLStat);

$query = sql_pre_query(
    "SELECT * FROM \"".$Settings['sqltable']."topics\" 
    WHERE (\"ForumID\"=%i".$ExtraIgnores.$ForumIgnoreList4.") 
    OR (\"OldForumID\"=%i".$ExtraIgnores.$ForumIgnoreList4.") 
    OR (\"Pinned\"=2".$ExtraIgnores.$ForumIgnoreList4.") 
    ORDER BY \"Pinned\" DESC, \"LastUpdate\" DESC ".$SQLimit, 
    array($_GET['id'], $_GET['id'])
);
$result = sql_query($query, $SQLStat);

// List Page Number Code Start
$pagenum = count($Pages);
$_GET['page'] = min($_GET['page'], $pagenum);
$pstring = null;

if ($pagenum > 1) {
    $pstring = "<div class=\"PageList\"><span class=\"pagelink\">{$pagenum} Pages:</span> ";
    $Pagez = [
        0 => ($_GET['page'] >= 4) ? "First" : null,
        1 => ($_GET['page'] >= 3) ? $_GET['page'] - 2 : null,
        2 => ($_GET['page'] >= 2) ? $_GET['page'] - 1 : null,
        3 => $_GET['page'],
        4 => ($_GET['page'] < $pagenum) ? $_GET['page'] + 1 : null,
        5 => ($_GET['page'] + 1 < $pagenum) ? $_GET['page'] + 2 : null,
        6 => ($_GET['page'] < $pagenum) ? "Last" : null
    ];

    // Build pagination links
    for ($pagei = 0, $pagenumi = count($Pagez); $pagei < $pagenumi; ++$pagei) {
        if ($pagei == 1 && $_GET['page'] > 1) {
            $Pback = $_GET['page'] - 1;
            $pstring .= "<span class=\"pagelink\"><a href=\"".url_maker($exfile[$ForumType], $Settings['file_ext'], "act=".$viewvar."&id={$_GET['id']}&page={$Pback}", $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType])."\">&lt;</a></span> ";
        }

        if ($Pagez[$pagei] !== null && $Pagez[$pagei] != "First" && $Pagez[$pagei] != "Last") {
            $pstring .= ($pagei == 3)
                ? "<span class=\"pagecurrent\"><a href=\"".url_maker($exfile[$ForumType], $Settings['file_ext'], "act=".$viewvar."&id={$_GET['id']}&page={$Pagez[$pagei]}", $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType])."\">{$Pagez[$pagei]}</a></span> "
                : "<span class=\"pagelink\"><a href=\"".url_maker($exfile[$ForumType], $Settings['file_ext'], "act=".$viewvar."&id={$_GET['id']}&page={$Pagez[$pagei]}", $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType])."\">{$Pagez[$pagei]}</a></span> ";
        }

        if ($Pagez[$pagei] == "First") {
            $pstring .= "<span class=\"pagelinklast\"><a href=\"".url_maker($exfile[$ForumType], $Settings['file_ext'], "act=".$viewvar."&id={$_GET['id']}&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType])."\">&laquo;</a></span> ";
        }

        if ($Pagez[$pagei] == "Last") {
            $Pnext = $_GET['page'] + 1;
            $pstring .= "<span class=\"pagelink\"><a href=\"".url_maker($exfile[$ForumType], $Settings['file_ext'], "act=".$viewvar."&id={$_GET['id']}&page={$Pnext}", $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType])."\">&gt;</a></span> ";
            $pstring .= "<span class=\"pagelinklast\"><a href=\"".url_maker($exfile[$ForumType], $Settings['file_ext'], "act=".$viewvar."&id={$_GET['id']}&page={$pagenum}", $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType])."\">&raquo;</a></span> ";
        }
    }
    $pstring .= "</div>";
}
// List Page Number Code End
$i=0;
if($pstring!=null||$PermissionInfo['CanMakeTopics'][$ForumID]=="yes"&&$CanHaveTopics=="yes") {
if($_GET['act']=="view") {
?>
<table style="width: 100%;" class="Table2">
<tr>
 <td style="width: 30%; text-align: left;"><?php echo $pstring; ?></td>
 <td style="width: 70%; text-align: right;">
 <?php if($PermissionInfo['CanMakeTopics'][$ForumID]=="yes"&&$CanHaveTopics=="yes") { ?>
 <a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=create&id=".$ForumID,$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ThemeSet['NewTopic']; ?></a>
 <?php } ?></td>
</tr>
</table>
<?php
//List Page Number Code end
?>
<?php /*<div class="DivPageLinks">&#160;</div>*/?>
<div class="DivTable2">&#160;</div>
<?php } if($_GET['act']=="lowview") { ?>
<div style="font-size: 11px; font-weight: bold; padding: 10px; border: 1px solid gray;">
<?php echo $pstring; ?></div>
<div>&#160;</div>
<div style="padding: 10px; border: 1px solid gray;">
<?php if($num<=0) { ?>
<ul style="list-style-type: none;">
<li>&#160;</li>
<?php } if($num>0) { ?>
<ul style="list-style-type: decimal;">
<?php } } } if($_GET['act']=="view") { ?>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=".$viewvar."&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>#<?php echo $ForumID; ?>"><?php echo $ForumName; ?></a></span></div>
<?php } ?>
<table class="Table1" id="Forum<?php echo $ForumID; ?>">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr id="ForumStart<?php echo $ForumID; ?>" class="TableRow1">
<td class="TableColumn1" colspan="6"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=".$viewvar."&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>#<?php echo $ForumID; ?>"><?php echo $ForumName; ?></a></span>
</td>
</tr><?php } ?>
<tr id="TopicStatRow<?php echo $ForumID; ?>" class="TableRow2">
<th class="TableColumn2" style="width: 4%;">State</th>
<th class="TableColumn2" style="width: 36%;">Topic Name</th>
<th class="TableColumn2" style="width: 15%;">Author</th>
<th class="TableColumn2" style="width: 15%;">Time</th>
<th class="TableColumn2" style="width: 5%;">Replys</th>
<th class="TableColumn2" style="width: 25%;">Last Reply</th>
</tr>
<?php }
while ($i < $num) {
$result_array = sql_fetch_assoc($result);
$TopicID=$result_array['id'];
$TForumID=$result_array['ForumID'];
$OldForumID=$result_array['OldForumID'];
$UsersID=$result_array['UserID'];
$GuestsName=$result_array['GuestName'];
$TheTime=$result_array['TimeStamp'];
$tmpusrcurtime = new DateTime();
$tmpusrcurtime->setTimestamp($TheTime);
$tmpusrcurtime->setTimezone($usertz);
$TheTime=$tmpusrcurtime->format($_SESSION['iDBDateFormat'].", ".$_SESSION['iDBTimeFormat']);
$NumReply=$result_array['NumReply'];
$NumberPosts=$NumReply + 1;
$prepagelist = null;
if(!isset($Settings['max_posts'])) { 
	$Settings['max_posts'] = 10; }
if(!isset($ThemeSet['MiniPageAltStyle'])) { 
	$ThemeSet['MiniPageAltStyle'] = "off"; }
if($ThemeSet['MiniPageAltStyle']!="on"&&
	$ThemeSet['MiniPageAltStyle']!="off") { 
	$ThemeSet['MiniPageAltStyle'] = "off"; }
if($NumberPosts>$Settings['max_posts']) {
$NumberPages = ceil($NumberPosts/$Settings['max_posts']); }
if($NumberPosts<=$Settings['max_posts']) {
$NumberPages = 1; }
if($NumberPages>4) {
	$prepagelist = " &#160;"; }
if($NumberPages>=2) {
	if($ThemeSet['MiniPageAltStyle']=="off") { 
	$prepagelist = "<span class=\"small\">(Pages: "; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<span class=\"minipagelink\">"; }
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=".$viewvar."&id=".$TopicID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">1</a>";
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; }
	if($ThemeSet['MiniPageAltStyle']=="off") { $prepagelist = $prepagelist." "; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<span class=\"minipagelink\">"; }
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=".$viewvar."&id=".$TopicID."&page=2",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">2</a>";
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; }
	if($NumberPages>=3) {
	if($ThemeSet['MiniPageAltStyle']=="off") { $prepagelist = $prepagelist." "; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<span class=\"minipagelink\">"; }
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=".$viewvar."&id=".$TopicID."&page=3",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">3</a>";
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; } }
	if($NumberPages==4) {
	if($ThemeSet['MiniPageAltStyle']=="off") { $prepagelist = $prepagelist." "; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<span class=\"minipagelinklast\">"; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=".$viewvar."&id=".$TopicID."&page=4",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">4</a>"; }
	if($ThemeSet['MiniPageAltStyle']=="off") {
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=".$viewvar."&id=".$TopicID."&page=4",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\"> ...4</a>"; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; } }
	if($NumberPages>4) {
	if($ThemeSet['MiniPageAltStyle']=="off") { $prepagelist = $prepagelist." "; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<span class=\"minipagelinklast\">"; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=".$viewvar."&id=".$TopicID."&page=".$NumberPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">&raquo; ".$NumberPages."</a>"; }
	if($ThemeSet['MiniPageAltStyle']=="off") {
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=".$viewvar."&id=".$TopicID."&page=".$NumberPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\"> ...".$NumberPages."</a>"; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; } }
	if($ThemeSet['MiniPageAltStyle']=="off") { 
	$prepagelist = $prepagelist.")</span>"; } }
$TopicName=$result_array['TopicName'];
$TopicDescription=$result_array['Description'];
$PinnedTopic=$result_array['Pinned'];
$TopicStat=$result_array['Closed'];
$renum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($UsersID)), $SQLStat);
$requery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($UsersID));
$reresult=sql_query($requery,$SQLStat);
if($renum<1) { $UsersID = -1;
$renum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($UsersID)), $SQLStat);
$requery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($UsersID));
$reresult=sql_query($requery,$SQLStat); }
$reresult_array = sql_fetch_assoc($reresult);
if($renum<1) { 
	$reresult_array = array('UserID' => -1,
		'Name' => "Guest",
		'IP' => "127.0.0.1",
		'Email' => "",
		'Title' => "Guest",
		'Joined' => $Settings['start_date'],
		'HiddenMember' => "yes",
		'LevelID' => 0,
		'RankID' => "0",
		'GroupID' => $Settings['GuestGroupID'],
		'Signature' => "",
		'Avatar' => $ThemeSet['NoAvatar'],
		'AvatarSize' => $ThemeSet['NoAvatarSize'],
		'Website' => $Settings['weburl'],
		'PostCount' => 0,
		'Karma' => 0); }
$UserHidden=$reresult_array['HiddenMember'];
$UserGroupID=$reresult_array['GroupID'];
sql_free_result($reresult);
$gquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($UserGroupID));
$gresult=sql_query($gquery,$SQLStat);
$gresult_array = sql_fetch_assoc($gresult);
$User1Group=$gresult_array['Name'];
$GroupNamePrefix=$gresult_array['NamePrefix'];
$GroupNameSuffix=$gresult_array['NameSuffix'];
sql_free_result($gresult);
$PreUsersName = GetUserName($UsersID,$Settings['sqltable'],$SQLStat);
if($PreUsersName['Name']===null) { $UsersID = -1;
$PreUsersName = GetUserName($UsersID,$Settings['sqltable'],$SQLStat); }
if($PreUsersName['Name']===null) { $UsersID = -1;
	$PreUsersName = array('UserID' => -1,
		'Name' => "Guest",
		'Hidden' => "yes"); }
$UsersName = $PreUsersName['Name'];
$UsersHidden = $PreUsersName['Hidden'];
if($UsersName=="Guest") { $UsersName=$GuestsName;
if($UsersName==null) { $UsersName="Guest"; } }
if(isset($GroupNamePrefix)&&$GroupNamePrefix!=null) {
	$UsersName = $GroupNamePrefix.$UsersName; }
if(isset($GroupNameSuffix)&&$GroupNameSuffix!=null) {
	$UsersName = $UsersName.$GroupNameSuffix; }
$LastReply = "&#160;<br />&#160;";
$glrnum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."posts\" WHERE \"TopicID\"=%i ORDER BY \"TimeStamp\" DESC LIMIT 1", array($TopicID)), $SQLStat);
$glrquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."posts\" WHERE \"TopicID\"=%i ORDER BY \"TimeStamp\" DESC LIMIT 1", array($TopicID));
$glrresult=sql_query($glrquery,$SQLStat);
if($glrnum>0){
$glrresult_array = sql_fetch_assoc($glrresult);
$ReplyID1=$glrresult_array['id'];
$UsersID1=$glrresult_array['UserID'];
$PreUsersName1 = GetUserName($UsersID1,$Settings['sqltable'],$SQLStat);
if($PreUsersName1['Name']===null) { $UsersID1 = -1;
$PreUsersName1 = GetUserName($UsersID1,$Settings['sqltable'],$SQLStat); }
if($PreUsersName1['Name']===null) { $UsersID = -1;
	$PreUsersName1 = array('UserID' => -1,
		'Name' => "Guest",
		'Hidden' => "yes"); }
$UsersName1 = $PreUsersName1['Name'];
$UsersHidden1 = $PreUsersName1['Hidden'];
$GuestsName1=$glrresult_array['GuestName'];
$TimeStamp1=$glrresult_array['TimeStamp'];
$tmpusrcurtime = new DateTime();
$tmpusrcurtime->setTimestamp($TimeStamp1);
$tmpusrcurtime->setTimezone($usertz);
$TimeStamp1=$tmpusrcurtime->format($_SESSION['iDBDateFormat'].", ".$_SESSION['iDBTimeFormat']); }
$NumPages = null; $NumRPosts = $NumReply + 1;
if(!isset($Settings['max_posts'])) { $Settings['max_posts'] = 10; }
if($NumRPosts>$Settings['max_posts']) {
$NumPages = ceil($NumRPosts/$Settings['max_posts']); }
if($NumRPosts<=$Settings['max_posts']) { $NumPages = 1; }
$Users_Name1 = pre_substr($UsersName1,0,20);
if($UsersName1=="Guest") { $UsersName1=$GuestsName1;
if($UsersName1==null) { $UsersName1="Guest"; } }
$oldusername=$UsersName1;
if (pre_strlen($UsersName1)>20) { 
$Users_Name1 = $Users_Name1."..."; $UsersName1=$Users_Name1; } $lul = null;
if($TimeStamp1!=null) { $lul = null;
if($UsersID1>0&&$UsersHidden1=="no") {
$lul = url_maker($exfile['member'],$Settings['file_ext'],"act=".$viewvar."&id=".$UsersID1,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']);
$luln = url_maker($exfile['topic'],$Settings['file_ext'],"act=".$viewvar."&id=".$TopicID."&page=".$NumPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']).$qstrhtml."&#35;reply".$NumRPosts;
$LastReply = "<a href=\"".$luln."\">".$TimeStamp1."</a><br />\nUser: <a href=\"".$lul."\" title=\"".$oldusername."\">".$UsersName1."</a>"; }
if($UsersID1<=0||$UsersHidden1=="yes") {
if($UsersID1==-1) { $UserPre = "Guest:"; }
if(($UsersID1<-1&&$UsersHidden1=="yes")||$UsersID1==0||($UsersID1>0&&$UsersHidden1=="yes")) { 
	$UserPre = "Hidden:"; }
$lul = url_maker($exfile['member'],$Settings['file_ext'],"act=".$viewvar."&id=".$UsersID1,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']);
$luln = url_maker($exfile['topic'],$Settings['file_ext'],"act=".$viewvar."&id=".$TopicID."&page=".$NumPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']).$qstrhtml."&#35;reply".$NumRPosts;
$LastReply = "<a href=\"".$luln."\">".$TimeStamp1."</a><br />\n".$UserPre." <span title=\"".$oldusername."\">".$UsersName1."</span>"; } }
sql_free_result($glrresult);
if(!isset($TimeStamp1)) { $TimeStamp1 = null; } if(!isset($LastReply)) { $LastReply = "&#160;<br />&#160;"; }
if($TimeStamp1==null) { $LastReply = "&#160;<br />&#160;"; }
$PreTopic = $ThemeSet['TopicIcon'];
if ($PinnedTopic>2) { $PinnedTopic = 1; } 
if ($PinnedTopic<0) { $PinnedTopic = 0; }
if(!is_numeric($PinnedTopic)) { $PinnedTopic = 0; }
if ($TopicStat>3) { $TopicStat = 1; } 
if ($TopicStat<0) { $TopicStat = 0; }
if(!is_numeric($TopicStat)) { $TopicStat = 1; }
if(!is_numeric($OldForumID)) { $OldForumID = $ForumID; }
if($OldForumID=="0") { $OldForumID = $ForumID; }
if ($OldForumID!=$ForumID||$TForumID==$ForumID) {
$PreTopic = $ThemeSet['TopicIcon'];
$PreTopicText = null;
if ($PinnedTopic>0&&$PinnedTopic<3&&$TopicStat==0) {
	if($NumReply>=$Settings['hot_topic_num']) {
		$PreTopicText = "<span style=\"font-weight: bold;\">Pinned: </span>";
		$PreTopic=$ThemeSet['HotPinTopic']; }
	if($NumReply<$Settings['hot_topic_num']) {
		$PreTopicText = "<span style=\"font-weight: bold;\">Pinned: </span>";
		$PreTopic=$ThemeSet['PinTopic']; } }
if ($TopicStat>0&&$TopicStat<=3&&$PinnedTopic==0) {
	if($NumReply>=$Settings['hot_topic_num']) {
		$PreTopic=$ThemeSet['HotClosedTopic']; }
	if($NumReply<$Settings['hot_topic_num']) {
		$PreTopic=$ThemeSet['ClosedTopic']; } }
if ($PinnedTopic==0&&$TopicStat==0) {
		if($NumReply>=$Settings['hot_topic_num']) {
			$PreTopic=$ThemeSet['HotTopic']; }
		if($NumReply<$Settings['hot_topic_num']) {
			$PreTopic=$ThemeSet['TopicIcon']; } }
if ($PinnedTopic>0&&$PinnedTopic<3&&$TopicStat>0&&$TopicStat<=3) {
		if($NumReply>=$Settings['hot_topic_num']) {
			$PreTopicText = "<span style=\"font-weight: bold;\">Pinned: </span>";
			$PreTopic=$ThemeSet['HotPinClosedTopic']; }
		if($NumReply<$Settings['hot_topic_num']) {
			$PreTopicText = "<span style=\"font-weight: bold;\">Pinned: </span>";
			$PreTopic=$ThemeSet['PinClosedTopic']; } } 
		if($PinnedTopic==2) {
			$PreTopicText = null;
			$PreTopic=$ThemeSet['AnnouncementTopic']; } }
if ($OldForumID==$ForumID&&$TForumID!=$ForumID) {
$PreTopicText = "<span>Moved: </span>";
$PreTopic = $ThemeSet['MovedTopicIcon'];
if ($PinnedTopic>0&&$PinnedTopic<3&&$TopicStat==0) {
	if($NumReply>=$Settings['hot_topic_num']) {
		$PreTopic=$ThemeSet['MovedHotPinTopic']; }
	if($NumReply<$Settings['hot_topic_num']) {
		$PreTopic=$ThemeSet['MovedPinTopic']; } }
if ($TopicStat>0&&$TopicStat<=3&&$PinnedTopic==0) {
	if($NumReply>=$Settings['hot_topic_num']) {
		$PreTopic=$ThemeSet['MovedHotClosedTopic']; }
	if($NumReply<$Settings['hot_topic_num']) {
		$PreTopic=$ThemeSet['MovedClosedTopic']; } }
if ($PinnedTopic==0&&$TopicStat==0) {
		if($NumReply>=$Settings['hot_topic_num']) {
			$PreTopic=$ThemeSet['MovedHotTopic']; }
		if($NumReply<$Settings['hot_topic_num']) {
			$PreTopic=$ThemeSet['MovedTopicIcon']; } }
if ($PinnedTopic>0&&$PinnedTopic<3&&$TopicStat>0&&$TopicStat<=3) {
		if($NumReply>=$Settings['hot_topic_num']) {
			$PreTopic=$ThemeSet['MovedHotPinClosedTopic']; }
		if($NumReply<$Settings['hot_topic_num']) {
			$PreTopic=$ThemeSet['MovedPinClosedTopic']; } } }
if($_GET['act']=="lowview") {
$PreTopic = null;
if ($PinnedTopic>2) { $PinnedTopic = 1; } 
if ($PinnedTopic<0) { $PinnedTopic = 0; }
if(!is_numeric($PinnedTopic)) { $PinnedTopic = 0; }
if ($TopicStat>3) { $TopicStat = 1; } 
if ($TopicStat<0) { $TopicStat = 0; }
if(!is_numeric($TopicStat)) { $TopicStat = 1; }
if ($PinnedTopic>0&&$PinnedTopic<3) { $PreTopic="<span style=\"font-weight: bold;\">Pinned: </span>"; }
if ($PinnedTopic==0) { $PreTopic=null; }
if ($OldForumID==$ForumID&&$TForumID!=$ForumID) { $PreTopic="<span>Moved: </span>"; } }
if($_GET['act']=="view") {
?>
<tr class="TableRow3" id="Topic<?php echo $TopicID; ?>">
<td class="TableColumn3"><div class="topicstate">
<?php echo $PreTopic; ?></div></td>
<td class="TableColumn3"><div class="topicname">
<?php echo $PreTopicText; ?><a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=".$viewvar."&id=".$TopicID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>"><?php echo $TopicName; ?></a>
<?php if($prepagelist!==null) { echo $prepagelist; } ?></div>
<div class="topicdescription"><?php echo $TopicDescription; ?></div></td>
<td class="TableColumn3" style="text-align: center;"><?php
if($UsersID>0&&$UserHidden=="no") {
echo "<a href=\"";
echo url_maker($exfile['member'],$Settings['file_ext'],"act=".$viewvar."&id=".$UsersID,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']);
echo "\">".$UsersName."</a>"; }
if($UsersID<=0||$UserHidden=="yes") {
echo "<span>".$UsersName."</span>"; }
?></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $TheTime; ?></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $NumReply; ?></td>
<td class="TableColumn3"><?php echo $LastReply; ?></td>
</tr>
<?php } if($_GET['act']=="lowview") { ?>
<li><?php echo $PreTopic; ?><a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=".$_GET['act']."&id=".$TopicID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>"><?php echo $TopicName; ?></a> <span style="color: gray; font-size: 10px;">(<?php echo $NumReply; ?> replies)</span></li>
<?php } ++$i; } if($_GET['act']=="view") { ?>
<tr id="ForumEnd<?php echo $ForumID; ?>" class="TableRow4">
<td class="TableColumn4" colspan="6">&#160;</td>
</tr>
</table></div>
<div class="DivTopics">&#160;</div>
<?php } if($_GET['act']=="lowview") { ?>
</ul></div><div>&#160;</div>
<div style="font-size: 11px; font-weight: bold; padding: 10px; border: 1px solid gray;">
<?php echo $pstring; ?></div>
<div>&#160;</div>
<?php }
sql_free_result($result); }
if(($utccurtime->getTimestamp()<$_SESSION['LastPostTime']&&$_SESSION['LastPostTime']!=0)&&($_GET['act']=="create"||$_GET['act']=="maketopic")) { 
$_GET['act'] = "view"; $_POST['act'] = null; 
redirect("refresh",$rbasedir.url_maker($exfile['forum'],$Settings['file_ext'],"act=".$viewvar."&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum'],FALSE),"3"); ?>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=".$viewvar."&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ForumName; ?></a></span></div>
<?php } ?>
<table class="Table1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1">
<td class="TableColumn1"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=".$viewvar."&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ForumName; ?></a></span>
</td>
</tr><?php } ?>
<tr class="TableRow2">
<th class="TableColumn2" style="width: 100%; text-align: left;">&#160;Make Reply Message: </th>
</tr>
<tr class="TableRow3">
<td class="TableColumn3">
<table style="width: 100%; height: 25%; text-align: center;">
<tr>
	<td><span class="TableMessage"><br />
	You have to wait before making another topic.<br />
	Click <a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=".$viewvar."&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>">here</a> to go back to forum.<br />&#160;
	</span><br /></td>
</tr>
</table>
</td></tr>
<tr class="TableRow4">
<td class="TableColumn4">&#160;</td>
</tr>
</table></div>
<div class="DivMkReply">&#160;</div>
<?php } if($_GET['act']=="create") {
if($GroupInfo['HasAdminCP']!="yes"||$GroupInfo['HasModCP']!="yes") {
if($ForumPostCountView!=0&&$MyPostCountChk<$ForumPostCountView) {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($CategoryPostCountView!=0&&$MyPostCountChk<$CategoryPostCountView) {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($ForumKarmaCountView!=0&&$MyKarmaCount<$ForumKarmaCountView) {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); }
if($CategoryKarmaCountView!=0&&$MyKarmaCount<$CategoryKarmaCountView) {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); } }
if($PermissionInfo['CanMakeTopics'][$ForumID]=="no"||$CanHaveTopics=="no") { redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
$UFID = rand_uuid("rand");
$_SESSION['UserFormID'] = $UFID;
?>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=".$viewvar."&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ForumName; ?></a></span></div>
<?php } ?>
<table class="Table1" id="MakeTopic<?php echo $ForumID; ?>">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1" id="TopicStart<?php echo $ForumID; ?>">
<td class="TableColumn1" colspan="2"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=".$viewvar."&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ForumName; ?></a></span>
</td>
</tr><?php } ?>
<tr id="MakeTopicRow<?php echo $ForumID; ?>" class="TableRow2">
<td class="TableColumn2" colspan="2" style="width: 100%;">Making a Topic in <?php echo $ForumName; ?></td>
</tr>
<tr class="TableRow3" id="MkTopic<?php echo $ForumID; ?>">
<td class="TableColumn3" style="width: 15%; vertical-align: middle; text-align: center;">
<div style="width: 100%; height: 160px; overflow: auto;">
<table style="width: 100%; text-align: center;"><?php
$melanie_num=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."smileys\" WHERE \"Display\"='yes'", null), $SQLStat);
$melanie_query=sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."smileys\" WHERE \"Display\"='yes'", null);
$melanie_result=sql_query($melanie_query,$SQLStat);
$melanie_p=0; $SmileRow=0; $SmileCRow=0;
while ($melanie_p < $melanie_num) { ++$SmileRow;
$melanie_result_array = sql_fetch_assoc($melanie_result);
$FileName=$melanie_result_array['FileName'];
$SmileName=$melanie_result_array['SmileName'];
$SmileText=$melanie_result_array['SmileText'];
$SmileDirectory=$melanie_result_array['Directory'];
$ShowSmile=$melanie_result_array['Display'];
$ReplaceType=$melanie_result_array['ReplaceCI'];
if($SmileRow==1) { ?><tr>
	<?php } if($SmileRow<5) { ++$SmileCRow; ?>
	<td><img src="<?php echo $SmileDirectory."".$FileName; ?>" style="vertical-align: middle; border: 0px; cursor: pointer;" title="<?php echo $SmileName; ?>" alt="<?php echo $SmileName; ?>" onclick="addsmiley('TopicPost','&#160;<?php echo htmlspecialchars($SmileText, ENT_QUOTES, $Settings['charset']); ?>&#160;')" /></td>
	<?php } if($SmileRow==5) { ++$SmileCRow; ?>
	<td><img src="<?php echo $SmileDirectory."".$FileName; ?>" style="vertical-align: middle; border: 0px; cursor: pointer;" title="<?php echo $SmileName; ?>" alt="<?php echo $SmileName; ?>" onclick="addsmiley('TopicPost','&#160;<?php echo htmlspecialchars($SmileText, ENT_QUOTES, $Settings['charset']); ?>&#160;')" /></td></tr>
	<?php $SmileCRow=0; $SmileRow=0; }
++$melanie_p; }
if($SmileCRow<5&&$SmileCRow!=0) {
$SmileCRowL = 5 - $SmileCRow;
echo "<td colspan=\"".$SmileCRowL."\">&#160;</td></tr>"; }
echo "</table>";
sql_free_result($melanie_result);
?></div></td>
<td class="TableColumn3" style="width: 85%;">
<form style="display: inline;" method="post" id="MkTopicForm" action="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=maketopic&id=".$ForumID,$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="TopicName">Insert Topic Name:</label></td>
	<td style="width: 50%;"><input maxlength="30" type="text" name="TopicName" class="TextBox" id="TopicName" size="20" /></td>
</tr><?php if($_SESSION['UserGroup']==$Settings['GuestGroup']) { ?><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="GuestName">Insert Guest Name:</label></td>
	<?php if(!isset($_SESSION['GuestName'])) { ?>
	<td style="width: 50%;"><input maxlength="25" type="text" name="GuestName" class="TextBox" id="GuestName" size="20" /></td>
	<?php } if(isset($_SESSION['GuestName'])) { ?>
	<td style="width: 50%;"><input maxlength="25" type="text" name="GuestName" class="TextBox" id="GuestName" size="20" value="<?php echo $_SESSION['GuestName']; ?>" /></td>
<?php } ?></tr><?php } ?><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="TopicDesc">Insert Topic Description:</label></td>
	<td style="width: 50%;"><input maxlength="45" type="text" name="TopicDesc" class="TextBox" id="TopicDesc" size="20" /></td>
</tr>
</table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<label class="TextBoxLabel" for="TopicPost">Insert Your Post:</label><br />
<textarea rows="10" name="TopicPost" id="TopicPost" cols="40" class="TextBox"></textarea><br />
<?php if($_SESSION['UserGroup']==$Settings['GuestGroup']&&$Settings['captcha_guest']=="on") { ?>
<label class="TextBoxLabel" for="signcode"><img src="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=MkCaptcha",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>" alt="CAPTCHA Code" title="CAPTCHA Code" /></label><br />
<input maxlength="25" type="text" class="TextBox" name="signcode" size="20" id="signcode" value="Enter SignCode" /><br />-->
<?php } ?>
<input type="hidden" name="act" value="maketopics" style="display: none;" />
<input type="hidden" style="display: none;" name="fid" value="<?php echo $UFID; ?>" />
<input type="hidden" style="display: none;" name="ubid" value="<?php echo $Settings['BoardUUID']; ?>" />
<?php if($_SESSION['UserGroup']!=$Settings['GuestGroup']) { ?>
<input type="hidden" name="GuestName" value="null" style="display: none;" />
<?php } ?>
<input type="submit" class="Button" value="Make Topic" name="make_topic" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form></td></tr>
<tr id="MkTopicEnd<?php echo $ForumID; ?>" class="TableRow4">
<td class="TableColumn4" colspan="2">&#160;</td>
</tr>
</table></div>
<div class="DivMkTopics">&#160;</div>
<?php } if($_GET['act']=="maketopic"&&$_POST['act']=="maketopics") {
if($_POST['TopicDesc']==""&&$_POST['TopicName']!="") {
	$_POST['TopicDesc'] = $_POST['TopicName']; }
if($_POST['TopicDesc']!=""&&$_POST['TopicName']=="") {
	$_POST['TopicName'] = $_POST['TopicDesc']; }
if($PermissionInfo['CanMakeTopics'][$ForumID]=="no"||$CanHaveTopics=="no") { redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
$MyUserID = $_SESSION['UserID']; if($MyUserID=="0"||$MyUserID==null) { $MyUserID = -1; }
$REFERERurl = parse_url($_SERVER['HTTP_REFERER']);
$URL['REFERER'] = $REFERERurl['host'];
$URL['HOST'] = $_SERVER['SERVER_NAME'];
$REFERERurl = null;
if(!isset($_POST['TopicName'])) { $_POST['TopicName'] = null; }
if(!isset($_POST['TopicDesc'])) { $_POST['TopicDesc'] = null; }
if(!isset($_POST['TopicPost'])) { $_POST['TopicPost'] = null; }
if(!isset($_POST['GuestName'])) { $_POST['GuestName'] = null; }
if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	$Settings['captcha_guest']=="on") {
require($SettDir['inc']."captcha.php"); }
?>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=".$viewvar."&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ForumName; ?></a></span></div>
<?php } ?>
<table class="Table1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1">
<td class="TableColumn1"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=".$viewvar."&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ForumName; ?></a></span>
</td>
</tr><?php } ?>
<tr class="TableRow2">
<th class="TableColumn2" style="width: 100%; text-align: left;">&#160;Make Topic Message: </th>
</tr>
<tr class="TableRow3">
<td class="TableColumn3">
<table style="width: 100%; height: 25%; text-align: center;">
<?php if (pre_strlen($_POST['TopicName'])>"50") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your Topic Name is too big.<br />
	</span>&#160;</td>
</tr>
<?php } /*if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	$Settings['captcha_guest']=="on") {
if (PhpCaptcha::Validate($_POST['signcode'])) {
//echo 'Valid code entered';
} else { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />Invalid code entered<br />
	</span>&#160;</td>
</tr>
<?php } }*/ if (pre_strlen($_POST['TopicDesc'])>"80") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your Topic Description is too big.<br />
	</span>&#160;</td>
</tr>
<?php } if($_POST['fid']!=$_SESSION['UserFormID']) { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Sorry the referering url dose not match our host name.<br />
	</span>&#160;</td>
</tr>
<?php } if($_POST['ubid']!=$Settings['BoardUUID']) { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Sorry the referering url dose not match our host name.<br />
	</span>&#160;</td>
</tr>
<?php } if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	pre_strlen($_POST['GuestName'])>"30") { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You Guest Name is too big.<br />
	</span>&#160;</td>
</tr>
<?php } if ($Settings['TestReferer']=="on") {
	if ($URL['HOST']!=$URL['REFERER']) { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Sorry the referering url dose not match our host name.<br />
	</span>&#160;</td>
</tr>
<?php } }
$_POST['TopicName'] = stripcslashes(htmlspecialchars($_POST['TopicName'], ENT_QUOTES, $Settings['charset']));
//$_POST['TopicName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['TopicName']);
$_POST['TopicName'] = remove_spaces($_POST['TopicName']);
$_POST['TopicDesc'] = stripcslashes(htmlspecialchars($_POST['TopicDesc'], ENT_QUOTES, $Settings['charset']));
//$_POST['TopicDesc'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['TopicDesc']);
$_POST['TopicDesc'] = remove_spaces($_POST['TopicDesc']);
$_POST['GuestName'] = stripcslashes(htmlspecialchars($_POST['GuestName'], ENT_QUOTES, $Settings['charset']));
//$_POST['GuestName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['GuestName']);
$_POST['GuestName'] = remove_spaces($_POST['GuestName']);
$_POST['TopicPost'] = stripcslashes(htmlspecialchars($_POST['TopicPost'], ENT_QUOTES, $Settings['charset']));
//$_POST['TopicPost'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['TopicPost']);
$_POST['TopicPost'] = remove_bad_entities($_POST['TopicPost']);
//$_POST['TopicPost'] = remove_spaces($_POST['TopicPost']);
if($_SESSION['UserGroup']==$Settings['GuestGroup']) {
if(isset($_POST['GuestName'])&&$_POST['GuestName']!=null) {
if($cookieDomain==null) {
setcookie("GuestName", $_POST['GuestName'], time() + (7 * 86400), $cbasedir); }
if($cookieDomain!=null) {
if($cookieSecure===true) {
setcookie("GuestName", $_POST['GuestName'], time() + (7 * 86400), $cbasedir, $cookieDomain, 1); }
if($cookieSecure===false) {
setcookie("GuestName", $_POST['GuestName'], time() + (7 * 86400), $cbasedir, $cookieDomain, 0); } }
$_SESSION['GuestName']=$_POST['GuestName']; } }
/*    <_<  iWordFilter  >_>      
   by Kazuki Przyborowski - Cool Dude 2k */
$melanienm=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."wordfilter\"", null), $SQLStat);
$melanieqy=sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."wordfilter\"", null);
$melaniert=sql_query($melanieqy,$SQLStat);
$melanies=0;
while ($melanies < $melanienm) {
$melaniert_array = sql_fetch_assoc($melaniert);
$Filter=$melaniert_array['FilterWord'];
$Replace=$melaniert_array['Replacement'];
$CaseInsensitive=$melaniert_array['CaseInsensitive'];
if($CaseInsensitive=="on") { $CaseInsensitive = "yes"; }
if($CaseInsensitive=="off") { $CaseInsensitive = "no"; }
if($CaseInsensitive!="yes"||$CaseInsensitive!="no") { $CaseInsensitive = "no"; }
$WholeWord=$melaniert_array['WholeWord'];
if($WholeWord=="on") { $WholeWord = "yes"; }
if($WholeWord=="off") { $WholeWord = "no"; }
if($WholeWord!="yes"&&$WholeWord!="no") { $WholeWord = "no"; }
$Filter = preg_quote($Filter, "/");
if($CaseInsensitive!="yes"&&$WholeWord=="yes") {
$_POST['TopicDesc'] = preg_replace("/\b(".$Filter.")\b/", $Replace, $_POST['TopicDesc']); 
$_POST['TopicPost'] = preg_replace("/\b(".$Filter.")\b/", $Replace, $_POST['TopicPost']); }
if($CaseInsensitive=="yes"&&$WholeWord=="yes") {
$_POST['TopicDesc'] = preg_replace("/\b(".$Filter.")\b/i", $Replace, $_POST['TopicDesc']); 
$_POST['TopicPost'] = preg_replace("/\b(".$Filter.")\b/i", $Replace, $_POST['TopicPost']); }
if($CaseInsensitive!="yes"&&$WholeWord!="yes") {
$_POST['TopicDesc'] = preg_replace("/".$Filter."/", $Replace, $_POST['TopicDesc']); 
$_POST['TopicPost'] = preg_replace("/".$Filter."/", $Replace, $_POST['TopicPost']); }
if($CaseInsensitive=="yes"&&$WholeWord!="yes") {
$_POST['TopicDesc'] = preg_replace("/".$Filter."/i", $Replace, $_POST['TopicDesc']); 
$_POST['TopicPost'] = preg_replace("/".$Filter."/i", $Replace, $_POST['TopicPost']); }
++$melanies; } sql_free_result($melaniert);
$lonewolfnm=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."restrictedwords\" WHERE \"RestrictedTopicName\"='yes' or \"RestrictedUserName\"='yes'", null), $SQLStat);
$lonewolfqy=sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."restrictedwords\" WHERE \"RestrictedTopicName\"='yes' or \"RestrictedUserName\"='yes'", null);
$lonewolfrt=sql_query($lonewolfqy,$SQLStat);
$lonewolfs=0; $RMatches = null; $RGMatches = null;
while ($lonewolfs < $lonewolfnm) {
$lonewolfs_array = sql_fetch_assoc($lonewolfs);
$RWord=$lonewolfrt_array['Word'];
$RCaseInsensitive=$lonewolfrt_array['CaseInsensitive'];
if($RCaseInsensitive=="on") { $RCaseInsensitive = "yes"; }
if($RCaseInsensitive=="off") { $RCaseInsensitive = "no"; }
if($RCaseInsensitive!="yes"||$RCaseInsensitive!="no") { $RCaseInsensitive = "no"; }
$RWholeWord=$lonewolfrt_array['WholeWord'];
if($RWholeWord=="on") { $RWholeWord = "yes"; }
if($RWholeWord=="off") { $RWholeWord = "no"; }
if($RWholeWord!="yes"||$RWholeWord!="no") { $RWholeWord = "no"; }
$RestrictedTopicName=$lonewolfrt_array['RestrictedTopicName'];
if($RestrictedTopicName=="on") { $RestrictedTopicName = "yes"; }
if($RestrictedTopicName=="off") { $RestrictedTopicName = "no"; }
if($RestrictedTopicName!="yes"||$RestrictedTopicName!="no") { $RestrictedTopicName = "no"; }
$RestrictedUserName=$lonewolfrt_array['RestrictedUserName'];
if($RestrictedUserName=="on") { $RestrictedUserName = "yes"; }
if($RestrictedUserName=="off") { $RestrictedUserName = "no"; }
if($RestrictedUserName!="yes"||$RestrictedUserName!="no") { $RestrictedUserName = "no"; }
$RWord = preg_quote($RWord, "/");
if($RCaseInsensitive!="yes"&&$RWholeWord=="yes") {
if($RestrictedTopicName=="yes") {
$RMatches = preg_match("/\b(".$RWord.")\b/", $_POST['TopicName']);
	if($RMatches==true) { break 1; } }
if($RestrictedUserName=="yes") {
$RGMatches = preg_match("/\b(".$RWord.")\b/", $_POST['GuestName']);
	if($RGMatches==true) { break 1; } } }
if($RCaseInsensitive=="yes"&&$RWholeWord=="yes") {
if($RestrictedTopicName=="yes") {
$RMatches = preg_match("/\b(".$RWord.")\b/i", $_POST['TopicName']);
	if($RMatches==true) { break 1; } }
if($RestrictedUserName=="yes") {
$RGMatches = preg_match("/\b(".$RWord.")\b/i", $_POST['GuestName']);
	if($RGMatches==true) { break 1; } } }
if($RCaseInsensitive!="yes"&&$RWholeWord!="yes") {
if($RestrictedTopicName=="yes") {
$RMatches = preg_match("/".$RWord."/", $_POST['TopicName']);
	if($RMatches==true) { break 1; } }
if($RestrictedUserName=="yes") {
$RGMatches = preg_match("/".$RWord."/", $_POST['GuestName']);
	if($RGMatches==true) { break 1; } } }
if($RCaseInsensitive=="yes"&&$RWholeWord!="yes") {
if($RestrictedTopicName=="yes") {
$RMatches = preg_match("/".$RWord."/i", $_POST['TopicName']);
	if($RMatches==true) { break 1; } }
if($RestrictedUserName=="yes") {
$RGMatches = preg_match("/".$RWord."/i", $_POST['GuestName']);
	if($RGMatches==true) { break 1; } } }
++$lonewolfs; } sql_free_result($lonewolfrt);
if ($_POST['TopicName']==null) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a Topic Name.<br />
	</span>&#160;</td>
</tr>
<?php } if ($_POST['TopicDesc']==null) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a Topic Description.<br />
	</span>&#160;</td>
</tr>
<?php } if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	$_POST['GuestName']==null) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a Guest Name.<br />
	</span>&#160;</td>
</tr>
<?php } if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	$RGMatches==true) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />This Guest Name is restricted to use.<br />
	</span>&#160;</td>
</tr>
<?php } if($PermissionInfo['CanMakeTopics'][$ForumID]=="no"||$CanHaveTopics=="no") { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You do not have permission to make a topic here.<br />
	</span>&#160;</td>
</tr>
<?php } if ($_POST['TopicPost']==null) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a Topic Post.<br />
	</span>&#160;</td>
</tr>
<?php } if($RMatches==true) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />This Topic Name is restricted to use.<br />
	</span>&#160;</td>
</tr>
<?php } if ($Error=="Yes") {
redirect("refresh",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false),"4"); ?>
<tr>
	<td><span class="TableMessage">
	<br />Click <a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=".$viewvar,$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">here</a> to goto index page.<br />&#160;
	</span><br /></td>
</tr>
<?php } if ($Error!="Yes") { $LastActive = $utccurtime->getTimestamp();
$renum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($MyUserID)), $SQLStat);
$requery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($MyUserID));
$reresult=sql_query($requery,$SQLStat);
$rei=0;
while ($rei < $renum) {
$User1ID=$MyUserID;
$reresult_array = sql_fetch_assoc($reresult);
$User1Name=$reresult_array['Name'];
if($_SESSION['UserGroup']==$Settings['GuestGroup']) { $User1Name = $_POST['GuestName']; }
$User1Email=$reresult_array['Email'];
$User1Title=$reresult_array['Title'];
$User1GroupID=$reresult_array['GroupID'];
$PostCount=$reresult_array['PostCount'];
if($PostCountAdd=="on") { $NewPostCount = $PostCount + 1; }
if(!isset($NewPostCount)) { $NewPostCount = $PostCount; }
$gquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($User1GroupID));
$gresult=sql_query($gquery,$SQLStat);
$gresult_array = sql_fetch_assoc($gresult);
$User1Group=$gresult_array['Name'];
sql_free_result($gresult);
$User1IP=$_SERVER['REMOTE_ADDR'];
++$rei; } sql_free_result($reresult);
$query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."topics\" (\"PollID\", \"ForumID\", \"CategoryID\", \"OldForumID\", \"OldCategoryID\", \"UserID\", \"GuestName\", \"TimeStamp\", \"LastUpdate\", \"TopicName\", \"Description\", \"NumReply\", \"NumViews\", \"Pinned\", \"Closed\") VALUES\n".
"(0, %i, %i, %i, %i, %i, '%s', %i, %i, '%s', '%s', 0, 0, 0, 0)", array($ForumID,$ForumCatID,$ForumID,$ForumCatID,$User1ID,$User1Name,$LastActive,$LastActive,$_POST['TopicName'],$_POST['TopicDesc']));
sql_query($query,$SQLStat);
$topicid = sql_get_next_id($Settings['sqltable'],"topics",$SQLStat);
$query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."posts\" (\"TopicID\", \"ForumID\", \"CategoryID\", \"ReplyID\", \"UserID\", \"GuestName\", \"TimeStamp\", \"LastUpdate\", \"EditUser\", \"EditUserName\", \"Post\", \"Description\", \"IP\", \"EditIP\") VALUES\n".
"(%i, %i, %i, %i, %i, '%s', %i, %i, 0, '', '%s', '%s', '%s', '0')", array($topicid,$ForumID,$ForumCatID,0,$User1ID,$User1Name,$LastActive,$LastActive,$_POST['TopicPost'],$_POST['TopicDesc'],$User1IP));
sql_query($query,$SQLStat);
$postid = sql_get_next_id($Settings['sqltable'],"posts",$SQLStat);
$_SESSION['LastPostTime'] = $utccurtime->getTimestamp() + $GroupInfo['FloodControl'];
if($User1ID!=0&&$User1ID!=-1) {
$queryupd = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"LastActive\"=%i,\"IP\"='%s',\"PostCount\"=%i,\"LastPostTime\"=%i WHERE \"id\"=%i", array($LastActive,$User1IP,$NewPostCount,$_SESSION['LastPostTime'],$User1ID));
sql_query($queryupd,$SQLStat); }
$NewNumPosts = $NumberPosts + 1; $NewNumTopics = $NumberTopics + 1;
$queryupd = sql_pre_query("UPDATE \"".$Settings['sqltable']."forums\" SET \"NumPosts\"=%i,\"NumTopics\"=%i WHERE \"id\"=%i", array($NewNumPosts,$NewNumTopics,$ForumID));
sql_query($queryupd,$SQLStat);
redirect("refresh",$rbasedir.url_maker($exfile['topic'],$Settings['file_ext'],"act=".$viewvar."&id=".$topicid."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'],FALSE),"3");
?><tr>
	<td><span class="TableMessage"><br />
	Topic <?php echo $_POST['TopicName']; ?> was started.<br />
	Click <a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=".$viewvar."&id=".$topicid."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>">here</a> to continue to topic.<br />&#160;
	</span><br /></td>
</tr>
<?php } ?>
</table>
</td></tr>
<tr class="TableRow4">
<td class="TableColumn4">&#160;</td>
</tr>
</table></div>
<div class="DivMkTopics">&#160;</div>
<?php } if($_GET['act']!="lowview") { ?>
<table style="width: 100%;" class="Table2">
<tr>
 <td style="width: 30%; text-align: left;"><?php echo $pstring; ?></td>
 <td style="width: 70%; text-align: right;">
 <?php if($PermissionInfo['CanMakeTopics'][$ForumID]=="yes"&&$CanHaveTopics=="yes") { ?>
 <a href="<?php echo url_maker($exfile['forum'],$Settings['file_ext'],"act=create&id=".$ForumID,$Settings['qstr'],$Settings['qsep'],$prexqstr['forum'],$exqstr['forum']); ?>"><?php echo $ThemeSet['NewTopic']; ?></a>
 <?php } ?></td>
</tr>
</table>
<?php
//List Page Number Code end
if($pstring!=null||($_GET['act']!="view"&&$_GET['act']!="lowview")||
	$PermissionInfo['CanMakeTopics'][$ForumID]=="yes"&&$CanHaveTopics=="yes") {
?>
<?php /*<div class="DivPageLinks">&#160;</div>*/ ?>
<div class="DivTable2">&#160;</div>
<?php } 
$uviewlcuttime = $utccurtime->getTimestamp();
$uviewltime = $uviewlcuttime - ini_get("session.gc_maxlifetime");
$uviewlnum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" >= %i AND \"session_id\"<>'%s' AND (\"serialized_data\" LIKE '%s' OR \"serialized_data\" LIKE '%s') ORDER BY \"expires\" DESC", array($uviewltime, session_id(), "%currentforumid:".$InSubForum.",".$ForumID.";%", "%currentforumid:0,".$ForumID.";")), $SQLStat);
if($InSubForum==0) {
$uviewlquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" >= %i AND \"session_id\"<>'%s' AND (\"serialized_data\" LIKE '%s' OR \"serialized_data\" LIKE '%s') ORDER BY \"expires\" DESC", array($uviewltime, session_id(), "%currentforumid:0,".$ForumID.";%", "%currentforumid:".$ForumID.",%")); }
if($InSubForum!=0) {
$uviewlquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" >= %i AND \"session_id\"<>'%s' AND (\"serialized_data\" LIKE '%s' OR \"serialized_data\" LIKE '%s') ORDER BY \"expires\" DESC", array($uviewltime, session_id(), "%currentforumid:".$InSubForum.",".$ForumID.";%", "%currentforumid:0,".$ForumID.";")); }
$uviewlresult=sql_query($uviewlquery,$SQLStat);
$uviewli=0; $uviewlmn = 0; $uviewlgn = 0; $uviewlan = 0; $uviewlmbn = 0;
$MembersViewList = null; $GuestsOnline = null;
while ($uviewli < $uviewlnum) {
$uviewlresult_array = sql_fetch_assoc($uviewlresult);
$session_data=$uviewlresult_array['session_data']; 
$serialized_data=$uviewlresult_array['serialized_data'];
$session_user_agent=$uviewlresult_array['user_agent']; 
$session_client_hints=json_decode($uviewlresult_array['client_hints']);
$session_ip_address=$uviewlresult_array['ip_address'];
//$UserSessInfo = unserialize_session($session_data);
$UserSessInfo = unserialize($serialized_data);
if(!isset($UserSessInfo['UserGroup'])) { $UserSessInfo['UserGroup'] = $Settings['GuestGroup']; }
$AmIHiddenUser = "no";
$user_agent_check = false;
if (user_agent_check($session_user_agent)) {
    // Use the result from user_agent_check if it's valid
    $user_agent_check = user_agent_check($session_user_agent);
} else {
    // Check if browscap is available
    if (ini_get('browscap')) {
        // Attempt to use get_browser() if browscap is set
        $pre_user_agent = @get_browser($session_user_agent, true);
        if ($pre_user_agent !== false) {
            // Use get_browser result if available
            $session_user_agent = $pre_user_agent['parent'] . " on " . $pre_user_agent['platform'];
        }
        unset($pre_user_agent);
    }
    // If browscap is not set or get_browser() fails, retain $session_user_agent
    // from the SQL select.
}
if($UserSessInfo['UserGroup']!=$Settings['GuestGroup']||$user_agent_check!==false) {
$PreAmIHiddenUser = GetUserName($UserSessInfo['UserID'],$Settings['sqltable'],$SQLStat);
$AmIHiddenUser = $PreAmIHiddenUser['Hidden'];
if(isset($UserSessInfo['AnonymousLogin']) && $UserSessInfo['AnonymousLogin']=="yes") { $AmIHiddenUser = "yes"; }
if((($AmIHiddenUser=="no"||$GroupInfo['CanViewAnonymous']=="yes")&&$UserSessInfo['UserID']>0)||$user_agent_check!==false) {
if($uviewlmbn>0) { $MembersViewList .= ", "; }
$userprestring = "";
if($AmIHiddenUser=="yes") { $userprestring = "*"; }
if($user_agent_check===false) {
$uatitleadd = null;
if($GroupInfo['CanViewUserAgent']=="yes") { $uatitleadd = " title=\"".htmlentities($session_user_agent, ENT_QUOTES, $Settings['charset'])."\""; }
$MembersViewList .= "<a".$uatitleadd." href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=".$viewvar."&id=".$UserSessInfo['UserID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">".$userprestring.$UserSessInfo['MemberName']."</a>"; 
if($GroupInfo['CanViewIPAddress']=="yes") {
$MembersViewList .= " (<a title=\"".$session_ip_address."\" onclick=\"window.open(this.href);return false;\" href=\"".sprintf($IPCheckURL,$session_ip_address)."\">".$session_ip_address."</a>)"; }
++$uviewlmn; ++$uviewlmbn; }
if($user_agent_check!==false) {
$uatitleadd = null;
if($GroupInfo['CanViewUserAgent']=="yes") { $uatitleadd = " title=\"".htmlentities($session_user_agent, ENT_QUOTES, $Settings['charset'])."\""; }
$MembersViewList .= "<span".$uatitleadd.">".$user_agent_check."</span>"; 
if($GroupInfo['CanViewIPAddress']=="yes") {
$MembersViewList .= " (<a title=\"".$session_ip_address."\" onclick=\"window.open(this.href);return false;\" href=\"".sprintf($IPCheckURL,$session_ip_address)."\">".$session_ip_address."</a>)"; }
++$uviewlmbn; } }
if($UserSessInfo['UserID']<=0||$AmIHiddenUser=="yes") {
if($user_agent_check===false) {
++$uviewlan; } } }
if($UserSessInfo['UserGroup']==$Settings['GuestGroup']) {
/*$uatitleadd = null;
if($GroupInfo['CanViewUserAgent']=="yes") { $uatitleadd = " title=\"".htmlentities($session_user_agent, ENT_QUOTES, $Settings['charset'])."\""; }
$GuestsViewList .= "<a".$uatitleadd." href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=".$viewvar."&id=".$MemList['ID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">".$MemList['Name']."</a>";
if($GroupInfo['CanViewIPAddress']=="yes") {
$GuestsViewList .= " (<a title=\"".$session_ip_address."\" onclick=\"window.open(this.href);return false;\" href=\"".sprintf($IPCheckURL,$session_ip_address)."\">".$session_ip_address."</a>)"; } */
++$uviewlgn; }
++$uviewli; }
if(!isset($_SESSION['UserGroup'])) { $_SESSION['UserGroup'] = $Settings['GuestGroup']; }
$AmIHiddenUser = "no";
$user_agent_check = false;
if(user_agent_check($_SERVER['HTTP_USER_AGENT'])) {
	$user_agent_check = user_agent_check($_SERVER['HTTP_USER_AGENT']); }
if($_SESSION['UserGroup']!=$Settings['GuestGroup']||$user_agent_check!==false) {
$PreAmIHiddenUser = GetUserName($_SESSION['UserID'],$Settings['sqltable'],$SQLStat);
$AmIHiddenUser = $PreAmIHiddenUser['Hidden'];
if((($AmIHiddenUser=="no"||$GroupInfo['CanViewAnonymous']=="yes")&&$_SESSION['UserID']>0)||$user_agent_check!==false) {
if($uviewlmbn>0) { $MembersViewList = ", ".$MembersViewList; }
if($user_agent_check===false) {
$uatitleadd = null;
if($GroupInfo['CanViewUserAgent']=="yes") { $uatitleadd = " title=\"".htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, $Settings['charset'])."\""; }
if($GroupInfo['CanViewIPAddress']=="yes") {
$MembersViewList = " (<a title=\"".$_SERVER['REMOTE_ADDR']."\" onclick=\"window.open(this.href);return false;\" href=\"".sprintf($IPCheckURL,$_SERVER['REMOTE_ADDR'])."\">".$_SERVER['REMOTE_ADDR']."</a>)".$MembersViewList; }
$MembersViewList = "<a".$uatitleadd." href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=".$viewvar."&id=".$_SESSION['UserID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">".$_SESSION['MemberName']."</a>".$MembersViewList; 
++$uviewlmn; ++$uviewlmbn; }
if($user_agent_check!==false) {
$uatitleadd = null;
if($GroupInfo['CanViewIPAddress']=="yes") {
$MembersViewList = " (<a title=\"".$_SERVER['REMOTE_ADDR']."\" onclick=\"window.open(this.href);return false;\" href=\"".sprintf($IPCheckURL,$_SERVER['REMOTE_ADDR'])."\">".$_SERVER['REMOTE_ADDR']."</a>)".$MembersViewList; }
if($GroupInfo['CanViewUserAgent']=="yes") { $uatitleadd = " title=\"".htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, $Settings['charset'])."\""; }
$MembersViewList = "<span".$uatitleadd.">".$user_agent_check."</span>".$MembersViewList; 
++$uviewlmbn; } }
if($_SESSION['UserID']<=0||$AmIHiddenUser=="yes") {
if($user_agent_check===false) {
++$uviewlan; } } }
if($_SESSION['UserGroup']==$Settings['GuestGroup']) {
/*$uatitleadd = null;
if($GroupInfo['CanViewUserAgent']=="yes") { $uatitleadd = " title=\"".htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, $Settings['charset'])."\""; }
if($GroupInfo['CanViewIPAddress']=="yes") {
$GuestsViewList = " (<a title=\"".$_SERVER['REMOTE_ADDR']."\" onclick=\"window.open(this.href);return false;\" href=\"".sprintf($IPCheckURL,$_SERVER['REMOTE_ADDR'])."\">".$_SERVER['REMOTE_ADDR']."</a>)".$GuestsViewList; }
$GuestsViewList = "<a".$uatitleadd." href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=".$viewvar."&id=".$MemList['ID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">".$MemList['Name']."</a>".$GuestsViewList; */
++$uviewlgn; }
++$uviewlnum;
?>
<div class="StatsBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableStatsRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=".$viewvar."&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>">Forum Statistics</a></span></div>
<?php } ?>
<table id="BoardStats" class="TableStats1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableStatsRow1">
<td class="TableStatsColumn1" colspan="2"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile[$ForumType],$Settings['file_ext'],"act=".$viewvar."&id=".$ForumID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>">Forum Statistics</a></span>
</td>
</tr><?php } ?>
<tr id="Stats1" class="TableStatsRow2">
<td class="TableStatsColumn2" colspan="2" style="width: 100%; font-weight: bold;"><?php echo $uviewlnum; ?> users viewing forum</td>
</tr>
<tr class="TableStatsRow3" id="Stats2">
<td style="width: 4%;" class="TableStatsColumn3"><div class="statsicon">
<?php echo $ThemeSet['BoardStatsIcon']; ?></div></td>
<td style="width: 96%;" class="TableStatsColumn3"><div class="statsinfo">
&#160;<span style="font-weight: bold;"><?php echo $uviewlgn; ?></span> guests, <span style="font-weight: bold;"><?php echo $uviewlmn; ?></span> members, <span style="font-weight: bold;"><?php echo $uviewlan; ?></span> anonymous members <br />
<?php if($MembersViewList!=null) { ?>&#160;<?php echo $MembersViewList."\n<br />"; } ?>
</div></td>
</tr>
<tr id="Stats7" class="TableStatsRow4">
<td class="TableStatsColumn4" colspan="2">&#160;</td>
</tr>
</table></div>
<div class="DivStats">&#160;</div>
<?php } } } ?>
