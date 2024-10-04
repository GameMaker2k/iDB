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

    $FileInfo: members.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="members.php"||$File3Name=="/members.php") {
	require('index.php');
	exit(); }
$pagenum = null;
if(!is_numeric($_GET['id'])) { $_GET['id'] = null; }
if(!is_numeric($_GET['page'])) { $_GET['page'] = 1; }
if($_GET['act']=="list"||$_GET['act']=="getactive") {
$orderlist = null;
$orderlist = "order by \"ID\" asc";
if(!isset($_GET['list'])) { $_GET['list'] = "members"; }
if(!isset($_GET['orderby'])) { $_GET['orderby'] = null; }
if(!isset($_GET['sorttype'])) { $_GET['sorttype'] = null; }
if(!isset($_GET['ordertype'])) { $_GET['ordertype'] = null; }
if(!isset($_GET['orderby'])) { $_GET['orderby'] = null; }
if(!isset($_GET['sortby'])) { $_GET['sortby'] = null; }
if(!isset($_GET['gid'])) { $_GET['gid'] = null; }
if(!isset($_GET['groupid'])) { $_GET['groupid'] = null; }
if($_GET['orderby']==null) { 
	if($_GET['sortby']!=null) { 
		$_GET['orderby'] = $_GET['sortby']; } }
if($_GET['orderby']==null) { $_GET['orderby'] = "joined"; }
if($_GET['orderby']!=null) {
if($_GET['orderby']=="id") { $orderlist = "order by \"ID\""; }
if($_GET['orderby']=="name") { $orderlist = "order by \"Name\""; }
if($_GET['orderby']=="joined") { $orderlist = "order by \"Joined\""; }
if($_GET['orderby']=="active") { $orderlist = "order by \"LastActive\""; }
if($_GET['orderby']=="post") { $orderlist = "order by \"PostCount\""; }
if($_GET['orderby']=="posts") { $orderlist = "order by \"PostCount\""; }
if($_GET['orderby']=="karma") { $orderlist = "order by \"Karma\""; }
if($_GET['orderby']=="offset") { $orderlist = "order by \"TimeZone\""; } }
if($_GET['ordertype']==null) { 
	if($_GET['sorttype']!=null) { 
		$_GET['ordertype'] = $_GET['sorttype']; } }
if($_GET['ordertype']==null) { $_GET['ordertype'] = "asc"; }
if($_GET['ordertype']!=null) {
if($_GET['ordertype']=="ascending") { $orderlist .= " asc"; }
if($_GET['ordertype']=="descending") { $orderlist .= " desc"; }
if($_GET['ordertype']=="asc") { $orderlist .= " asc"; }
if($_GET['ordertype']=="desc") { $orderlist .= " desc"; } }
if(!is_numeric($_GET['gid'])) { $_GET['gid'] = null; }
if($_GET['gid']!=null&&$_GET['groupid']==null) { $_GET['groupid'] = $_GET['gid']; }
if(!is_numeric($_GET['groupid'])) { $_GET['groupid'] = null; }
$ggquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"Name\"='%s'", array($Settings['GuestGroup']));
$ggresult=sql_query($ggquery,$SQLStat);
$ggresult_array = sql_fetch_assoc($ggresult);
$GGroup=$ggresult_array['id'];
sql_free_result($ggresult);
// Get SQL LIMIT Number
$nums = $_GET['page'] * $Settings['max_memlist'];
$PageLimit = max(0, $nums - $Settings['max_memlist']);
$SQLimit = getSQLLimitClause($Settings['sqltype'], $Settings['max_memlist'], $PageLimit);

// Handling different actions for member list
if ($_GET['act'] == "list") {
    $query = "";
    $NumberMembers = 0;
    
    if (empty($_GET['groupid'])) {
        // Fetch all members that are not in the restricted group
        $NumberMembers = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"GroupID\"<>%i AND \"id\">=0 AND \"HiddenMember\"='no'", array($GGroup)), $SQLStat);
        $query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"GroupID\"<>%i AND \"id\">=0 AND \"HiddenMember\"='no' ".$orderlist." ".$SQLimit, array($GGroup));
    } else {
        // Fetch members from a specific group
        $NumberMembers = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"GroupID\"=%i AND \"GroupID\"<>%i AND \"id\">=0", array($_GET['groupid'], $GGroup)), $SQLStat);
        $query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"GroupID\"=%i AND \"GroupID\"<>%i AND \"id\">=0 ".$orderlist." ".$SQLimit, array($_GET['groupid'], $GGroup));
    }
}

if ($_GET['act'] == "getactive") {
    $active_start = mktime(0, 0, 0, $usercurtime->format("m"), $usercurtime->format("d"), $usercurtime->format("Y"));
    $active_end = mktime(23, 59, 59, $usercurtime->format("m"), $usercurtime->format("d"), $usercurtime->format("Y"));
    
    if (empty($_GET['groupid'])) {
        // Fetch all active members
        $NumberMembers = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"GroupID\"<>%i AND \"id\">=0 AND \"HiddenMember\"='no' AND (\"LastActive\">=%i AND \"LastActive\"<=%i)", array($GGroup, $active_start, $active_end)), $SQLStat);
        $query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"GroupID\"<>%i AND \"id\">=0 AND \"HiddenMember\"='no' AND (\"LastActive\">=%i AND \"LastActive\"<=%i) ".$orderlist." ".$SQLimit, array($GGroup, $active_start, $active_end));
    } else {
        // Fetch active members from a specific group
        $NumberMembers = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"GroupID\"=%i AND \"GroupID\"<>%i AND \"id\">=0 AND (\"LastActive\">=%i AND \"LastActive\"<=%i)", array($_GET['groupid'], $GGroup, $active_start, $active_end)), $SQLStat);
        $query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"GroupID\"=%i AND \"GroupID\"<>%i AND \"id\">=0 AND (\"LastActive\">=%i AND \"LastActive\"<=%i) ".$orderlist." ".$SQLimit, array($_GET['groupid'], $GGroup, $active_start, $active_end));
    }
}

// Execute query and fetch results
$result = sql_query($query, $SQLStat);

// Session handling
$_SESSION['AncientViewingPage'] = $_SESSION['OldViewingPage'] ?? url_maker(null, "no+ext", "act=view", "&", "=", $prexqstr['index'], $exqstr['index']);
$_SESSION['AncientViewingFile'] = $_SESSION['OldViewingFile'] ?? ($Settings['file_ext'] != "no+ext" ? $exfile['index'].$Settings['file_ext'] : $exfile['index']);
$_SESSION['AncientPreViewingTitle'] = $_SESSION['OldPreViewingTitle'] ?? "Viewing";
$_SESSION['AncientViewingTitle'] = $_SESSION['OldViewingTitle'] ?? "Board index";
$_SESSION['AncientExtraData'] = $_SESSION['OldExtraData'] ?? "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;";
$_SESSION['ViewingPage'] = url_maker(null, "no+ext", "act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page=".$_GET['page'], "&", "=", $prexqstr['member'], $exqstr['member']);
$_SESSION['ViewingFile'] = $Settings['file_ext'] != "no+ext" ? $exfile['member'].$Settings['file_ext'] : $exfile['member'];
$_SESSION['PreViewingTitle'] = "Viewing";
$_SESSION['ViewingTitle'] = "Member List";
$_SESSION['ExtraData'] = "currentact:".$_GET['act']."; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;";

// Member List Pagination
$NumberMembers = $NumberMembers ?? 0;
$num = $NumberMembers;
$Settings['max_memlist'] = $Settings['max_memlist'] ?? 10;

$_GET['page'] = max(1, (int)($_GET['page'] ?? 1));
$nums = $_GET['page'] * $Settings['max_memlist'];
$nums = min($nums, $num);
$numz = max(0, $nums - $Settings['max_memlist']);

// Calculate next and previous pages
$nextpage = ($nums < $num) ? $_GET['page'] + 1 : $_GET['page'];
$backpage = ($_GET['page'] > 1) ? $_GET['page'] - 1 : 1;

// Generate pagination
$Pages = [];
for ($l = 1, $pnum = $num; $pnum > 0; ++$l) {
    $pnum = max(0, $pnum - $Settings['max_memlist']);
    $Pages[$l] = $l;
}

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
            $pstring .= "<span class=\"pagelink\"><a href=\"".url_maker($exfile['member'], $Settings['file_ext'], "act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page={$Pback}", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'])."\">&lt;</a></span> ";
        }

        if ($Pagez[$pagei] !== null && $Pagez[$pagei] != "First" && $Pagez[$pagei] != "Last") {
            $pstring .= ($pagei == 3)
                ? "<span class=\"pagecurrent\"><a href=\"".url_maker($exfile['member'], $Settings['file_ext'], "act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page={$Pagez[$pagei]}", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'])."\">{$Pagez[$pagei]}</a></span> "
                : "<span class=\"pagelink\"><a href=\"".url_maker($exfile['member'], $Settings['file_ext'], "act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page={$Pagez[$pagei]}", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'])."\">{$Pagez[$pagei]}</a></span> ";
        }

        if ($Pagez[$pagei] == "First") {
            $pstring .= "<span class=\"pagelinklast\"><a href=\"".url_maker($exfile['member'], $Settings['file_ext'], "act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'])."\">&laquo;</a></span> ";
        }

        if ($Pagez[$pagei] == "Last") {
            $Pnext = $_GET['page'] + 1;
            $pstring .= "<span class=\"pagelink\"><a href=\"".url_maker($exfile['member'], $Settings['file_ext'], "act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page={$Pnext}", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'])."\">&gt;</a></span> ";
            $pstring .= "<span class=\"pagelinklast\"><a href=\"".url_maker($exfile['member'], $Settings['file_ext'], "act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page={$pagenum}", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'])."\">&raquo;</a></span> ";
        }
    }
    $pstring .= "</div>";
}
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['member'], $Settings['file_ext'], "act=list&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member']); ?>">Member list</a></div>
<div class="DivNavLinks">&#160;</div>
<?php
echo $pstring;
// List Page Number Code End
$i=0;
if ($pagenum > 1) {
?>
<div class="DivPageLinks">&#160;</div>
<?php } ?>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page=".$_GET['page'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Member List</a>
</span></div>
<?php } ?>
<table class="Table1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1">
<td class="TableColumn1" colspan="8"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page=".$_GET['page'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Member List</a>
</span></td>
</tr><?php } ?>
<tr id="Member" class="TableRow2">
<th class="TableColumn2" style="width: 28%;">Name</th>
<th class="TableColumn2" style="width: 10%;">Group</th>
<th class="TableColumn2" style="width: 5%;">Posts</th>
<th class="TableColumn2" style="width: 5%;">Karma</th>
<th class="TableColumn2" style="width: 20%;">Joined</th>
<th class="TableColumn2" style="width: 20%;">Last Active</th>
<th class="TableColumn2" style="width: 7%;">Website</th>
<th class="TableColumn2" style="width: 5%;">Message</th>
</tr>
<?php
while ($i < $num) {
$result_array = sql_fetch_assoc($result);
$MemList['ID']=$result_array['id'];
$MemList['Name']=$result_array['Name'];
$MemList['Email']=$result_array['Email'];
$MemList['GroupID']=$result_array['GroupID'];
$MemList['HiddenMember']=$result_array['HiddenMember'];
$MemList['WarnLevel']=$result_array['WarnLevel'];
$MemList['Interests']=$result_array['Interests'];
$MemList['Title']=$result_array['Title'];
$MemList['Joined']=$result_array['Joined'];
$tmpusrcurtime = new DateTime();
$tmpusrcurtime->setTimestamp($MemList['Joined']);
$tmpusrcurtime->setTimezone($usertz);
$MemList['Joined']=$tmpusrcurtime->format($_SESSION['iDBDateFormat'].", ".$_SESSION['iDBTimeFormat']);
$MemList['LastActive']=$result_array['LastActive'];
$tmpusrcurtime = new DateTime();
$tmpusrcurtime->setTimestamp($MemList['LastActive']);
$tmpusrcurtime->setTimezone($usertz);
$MemList['LastActive']=$tmpusrcurtime->format($_SESSION['iDBDateFormat'].", ".$_SESSION['iDBTimeFormat']);
$MemList['Website']=$result_array['Website'];
if($MemList['Website']=="http://") { 
	$MemList['Website'] = $Settings['idburl']; }
$MemList['Website'] = urlcheck($MemList['Website']);
$BoardWWWChCk = parse_url($Settings['idburl']);
$MemsWWWChCk = parse_url($MemList['Website']);
$opennew = " onclick=\"window.open(this.href);return false;\"";
if($BoardWWWChCk['host']==$MemsWWWChCk['host']) {
	$opennew = null; }
$MemList['Gender']=$result_array['Gender'];
$MemList['PostCount']=$result_array['PostCount'];
$MemList['Karma']=$result_array['Karma'];
$MemList['TimeZone']=$result_array['TimeZone'];
$MemList['IP']=$result_array['IP'];
$gquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($MemList['GroupID']));
$gresult=sql_query($gquery,$SQLStat);
$gresult_array = sql_fetch_assoc($gresult);
$MemList['Group']=$gresult_array['Name'];
$GroupNamePrefix=$gresult_array['NamePrefix'];
$GroupNameSuffix=$gresult_array['NameSuffix'];
sql_free_result($gresult);
if(isset($GroupNamePrefix)&&$GroupNamePrefix!=null) {
	$MemList['Name'] = $GroupNamePrefix.$MemList['Name']; }
if(isset($GroupNameSuffix)&&$GroupNameSuffix!=null) {
	$MemList['Name'] = $MemList['Name'].$GroupNameSuffix; }
$membertitle = " ".$ThemeSet['TitleDivider']." Member List";
if($MemList['Group']!=$Settings['GuestGroup']) {
?>
<tr class="TableRow3" id="Member<?php echo $MemList['ID']; ?>">
<td class="TableColumn3" style="text-align: center;">&#160;<a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$MemList['ID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>"><?php echo $MemList['Name']; ?></a> <?php if($GroupInfo['CanViewIPAddress']=="yes") { ?> ( <a title="<?php echo $MemList['IP']; ?>" onclick="window.open(this.href);return false;" href="<?php echo sprintf($IPCheckURL,$MemList['IP']); ?>"><?php echo $MemList['IP']; ?></a> )<?php } ?></td>
<td class="TableColumn3" style="text-align: center;"><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=list&gid=".$MemList['GroupID']."&page=".$_GET['page'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>"><?php echo $MemList['Group']; ?></a></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $MemList['PostCount']; ?></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $MemList['Karma']; ?></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $MemList['Joined']; ?></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $MemList['LastActive']; ?></td>
<td class="TableColumn3" style="text-align: center;"><a href="<?php echo $MemList['Website']; ?>"<?php echo $opennew; ?>>Website</a></td>
<?php if($MemList['ID']>0&&$MemList['HiddenMember']=="no") { ?>
<td class="TableColumn3" style="text-align: center;"><a href="<?php echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=create&id=".$MemList['ID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); ?>"<?php echo $opennew; ?>>PM</a></td>
<?php } if($MemList['ID']<=0||$MemList['HiddenMember']=="yes") { ?>
<td class="TableColumn3" style="text-align: center;"><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>"<?php echo $opennew; ?>>PM</a></td>
<?php } ?>
</tr>
<?php }
++$i; } sql_free_result($result);
?>
<tr id="MemEnd" class="TableRow4">
<td class="TableColumn4" colspan="8">&#160;</td>
</tr>
</table></div>
<?php 
if($pagenum>1) {
?>
<div class="DivMembers">&#160;</div>
<?php }
echo $pstring;
//List Page Number Code end
if($pagenum>1) {
?>
<div class="DivPageLinks">&#160;</div>
<?php } }
if($_GET['act']=="online") {
if($_GET['list']!="all"&&$_GET['list']!="members"&&$_GET['list']!="guests") {
	$_GET['list'] = "members"; }
// Get SQL LIMIT Number
$nums = $_GET['page'] * $Settings['max_memlist'];
$PageLimit = max(0, $nums - $Settings['max_memlist']);
$SQLimit = getSQLLimitClause($Settings['sqltype'], $Settings['max_memlist'], $PageLimit);

$uolcuttime = $utccurtime->getTimestamp();
$uoltime = $uolcuttime - ini_get("session.gc_maxlifetime");

// Query based on the list type (members, guests, all)
switch ($_GET['list']) {
    case "members":
        $NumberMembers = sql_count_rows(
            sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" >= %i AND \"serialized_data\" NOT LIKE '%s'", 
            array($uoltime, "%UserGroup|s:".strlen($Settings['GuestGroup']).":\"".$Settings['GuestGroup']."\";%")), 
            $SQLStat
        );
        $query = sql_pre_query(
            "SELECT * FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" >= %i AND \"serialized_data\" NOT LIKE '%s' ORDER BY \"expires\" DESC ".$SQLimit, 
            array($uoltime, "%UserGroup|s:".strlen($Settings['GuestGroup']).":\"".$Settings['GuestGroup']."\";%")
        );
        break;
    
    case "guests":
        $NumberMembers = sql_count_rows(
            sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" >= %i AND \"serialized_data\" LIKE '%s'", 
            array($uoltime, "%UserGroup|s:".strlen($Settings['GuestGroup']).":\"".$Settings['GuestGroup']."\";%")), 
            $SQLStat
        );
        $query = sql_pre_query(
            "SELECT * FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" >= %i AND \"serialized_data\" LIKE '%s' ORDER BY \"expires\" DESC ".$SQLimit, 
            array($uoltime, "%UserGroup|s:".strlen($Settings['GuestGroup']).":\"".$Settings['GuestGroup']."\";%")
        );
        break;

    case "all":
    default:
        $NumberMembers = sql_count_rows(
            sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" >= %i", 
            array($uoltime)), 
            $SQLStat
        );
        $query = sql_pre_query(
            "SELECT * FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" >= %i ORDER BY \"expires\" DESC ".$SQLimit, 
            array($uoltime)
        );
        break;
}

$result = sql_query($query, $SQLStat);

// Set session variables for navigation and viewing titles
$_SESSION['AncientViewingPage'] = $_SESSION['OldViewingPage'] ?? url_maker(null, "no+ext", "act=view", "&", "=", $prexqstr['index'], $exqstr['index']);
$_SESSION['AncientViewingFile'] = $_SESSION['OldViewingFile'] ?? ($Settings['file_ext'] !== "no+ext" ? $exfile['index'].$Settings['file_ext'] : $exfile['index']);
$_SESSION['AncientPreViewingTitle'] = $_SESSION['OldPreViewingTitle'] ?? "Viewing";
$_SESSION['AncientViewingTitle'] = $_SESSION['OldViewingTitle'] ?? "Board index";
$_SESSION['AncientExtraData'] = $_SESSION['OldExtraData'] ?? "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;";

$_SESSION['OldViewingPage'] = $_SESSION['ViewingPage'] ?? url_maker(null, "no+ext", "act=view", "&", "=", $prexqstr['index'], $exqstr['index']);
$_SESSION['OldViewingFile'] = $_SESSION['ViewingFile'] ?? ($Settings['file_ext'] !== "no+ext" ? $exfile['index'].$Settings['file_ext'] : $exfile['index']);
$_SESSION['OldPreViewingTitle'] = $_SESSION['PreViewingTitle'] ?? "Viewing";
$_SESSION['OldViewingTitle'] = $_SESSION['ViewingTitle'] ?? "Board index";
$_SESSION['OldExtraData'] = $_SESSION['ExtraData'] ?? "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;";

$_SESSION['ViewingPage'] = url_maker(null, "no+ext", "act=online&list=".$_GET['list']."&page=".$_GET['page'], "&", "=", $prexqstr['member'], $exqstr['member']);
$_SESSION['ViewingFile'] = $Settings['file_ext'] !== "no+ext" ? $exfile['member'].$Settings['file_ext'] : $exfile['member'];
$_SESSION['PreViewingTitle'] = "Viewing";
$_SESSION['ViewingTitle'] = "Online Member List";
$_SESSION['ExtraData'] = "currentact:".$_GET['act']."; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;";

// Handle pagination for members list
$NumberMembers = $NumberMembers ?? 0;
$num = $NumberMembers;

$Settings['max_memlist'] = $Settings['max_memlist'] ?? 10;
$_GET['page'] = max(1, (int)($_GET['page'] ?? 1));

$nums = min($_GET['page'] * $Settings['max_memlist'], $num);
$numz = max(0, $nums - $Settings['max_memlist']);

$nextpage = ($nums < $num) ? $_GET['page'] + 1 : $_GET['page'];
$backpage = ($_GET['page'] > 1) ? $_GET['page'] - 1 : 1;

// Pagination calculation
$Pages = [];
for ($l = 1, $pnum = $num; $pnum > 0; ++$l) {
    $pnum = max(0, $pnum - $Settings['max_memlist']);
    $Pages[$l] = $l;
}

// Generate pagination HTML
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

    for ($pagei = 0, $pagenumi = count($Pagez); $pagei < $pagenumi; ++$pagei) {
        if ($pagei == 1 && $_GET['page'] > 1) {
            $Pback = $_GET['page'] - 1;
            $pstring .= "<span class=\"pagelink\"><a href=\"".url_maker($exfile['member'], $Settings['file_ext'], "act=online&list=".$_GET['list']."&page={$Pback}", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'])."\">&lt;</a></span> ";
        }

        if ($Pagez[$pagei] !== null && $Pagez[$pagei] != "First" && $Pagez[$pagei] != "Last") {
            $pstring .= ($pagei == 3)
                ? "<span class=\"pagecurrent\"><a href=\"".url_maker($exfile['member'], $Settings['file_ext'], "act=online&list=".$_GET['list']."&page={$Pagez[$pagei]}", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'])."\">{$Pagez[$pagei]}</a></span> "
                : "<span class=\"pagelink\"><a href=\"".url_maker($exfile['member'], $Settings['file_ext'], "act=online&list=".$_GET['list']."&page={$Pagez[$pagei]}", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'])."\">{$Pagez[$pagei]}</a></span> ";
        }

        if ($Pagez[$pagei] == "First") {
            $pstring .= "<span class=\"pagelinklast\"><a href=\"".url_maker($exfile['member'], $Settings['file_ext'], "act=online&list=".$_GET['list']."&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'])."\">&laquo;</a></span> ";
        }

        if ($Pagez[$pagei] == "Last") {
            $Pnext = $_GET['page'] + 1;
            $pstring .= "<span class=\"pagelink\"><a href=\"".url_maker($exfile['member'], $Settings['file_ext'], "act=online&list=".$_GET['list']."&page={$Pnext}", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'])."\">&gt;</a></span> ";
            $pstring .= "<span class=\"pagelinklast\"><a href=\"".url_maker($exfile['member'], $Settings['file_ext'], "act=online&list=".$_GET['list']."&page={$pagenum}", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'])."\">&raquo;</a></span> ";
        }
    }
    $pstring .= "</div>";
}

// Display the navigation and page links
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['member'], $Settings['file_ext'], "act=online&list=all&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member']); ?>">Online Member List</a></div>
<div class="DivNavLinks">&#160;</div>

<?php echo $pstring; ?>

<?php if ($pagenum > 1) { ?>
    <div class="DivPageLinks">&#160;</div>
<?php } $i=0; ?>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=online&list=".$_GET['list']."&page=".$_GET['page'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Online Member List</a>
</span></div>
<?php } ?>
<table class="Table1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1">
<td class="TableColumn1" colspan="8"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=online&list=".$_GET['list']."&page=".$_GET['page'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Online Member List</a>
</span></td>
</tr><?php } ?>
<tr id="Member" class="TableRow2">
<th class="TableColumn2" style="width: 28%;">Member Name</th>
<th class="TableColumn2" style="width: 10%;">Group Name</th>
<th class="TableColumn2" style="width: 26%;">Location</th>
<th class="TableColumn2" style="width: 24%;">Time</th>
<th class="TableColumn2" style="width: 7%;">Website</th>
<th class="TableColumn2" style="width: 5%;">Message</th>
</tr>
<?php
while ($i < $num) {
$AmIHiddenUser = "no";
$result_array = sql_fetch_assoc($result);
$get_session_id=$result_array['session_id'];
$session_data=$result_array['session_data'];
$serialized_data=$result_array['serialized_data'];
$session_user_agent=$result_array['user_agent'];
$session_client_hints=json_decode($result_array['client_hints']);
$session_ip_address=$result_array['ip_address']; 
$session_expires=$result_array['expires']; 
$tmpusrcurtime = new DateTime();
$tmpusrcurtime->setTimestamp($session_expires);
$tmpusrcurtime->setTimezone($usertz);
$session_expires = $tmpusrcurtime->format($_SESSION['iDBDateFormat'].", ".$_SESSION['iDBTimeFormat']);
if(isset($UserSessInfo)) { $UserSessInfo = null; }
//$UserSessInfo = unserialize_session($session_data);
$UserSessInfo = unserialize($serialized_data);
if(!isset($UserSessInfo['ShowActHidden'])) { $UserSessInfo['ShowActHidden'] = "no"; }
if(!isset($UserSessInfo['UserGroup'])) { 
	$UserSessInfo['UserGroup'] = $Settings['GuestGroup']; }
if(!isset($session_ip_address)) { 
	$session_ip_address = "127.0.0.1"; }
$ViewSessMem['Website'] = $Settings['idburl'];
$opennew = null;
if($UserSessInfo['UserGroup']!=$Settings['GuestGroup']) {
//$sess_num=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($_GET['id'])), $SQLStat);
$sess_query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($_GET['id']));
$sess_result=sql_query($sess_query,$SQLStat);
$sess_result_array = sql_fetch_assoc($sess_result);
$ViewSessMem['ID']=$sess_result_array['id'];
$ViewSessMem['Name']=$sess_result_array['Name'];
$ViewSessMem['GroupID']=$sess_result_array['GroupID'];
$ViewSessMem['HiddenMember']=$sess_result_array['HiddenMember'];
$ViewSessMem['WarnLevel']=$sess_result_array['WarnLevel'];
$ViewSessMem['Joined']=$sess_result_array['Joined'];
$tmpusrcurtime = new DateTime();
$tmpusrcurtime->setTimestamp($ViewSessMem['Joined']);
$tmpusrcurtime->setTimezone($usertz);
$ViewSessMem['Joined']=$tmpusrcurtime->format("M j Y, ".$_SESSION['iDBTimeFormat']);
$ViewSessMem['LastActive']=$sess_result_array['LastActive'];
$tmpusrcurtime = new DateTime();
$tmpusrcurtime->setTimestamp($ViewSessMem['LastActive']);
$tmpusrcurtime->setTimezone($usertz);
$ViewSessMem['LastActive']=$tmpusrcurtime->format("M j Y, ".$_SESSION['iDBTimeFormat']);
$ViewSessMem['Website']=$sess_result_array['Website'];
if($ViewSessMem['Website']=="http://") { 
	$ViewSessMem['Website'] = $Settings['idburl']; }
$ViewSessMem['Website'] = urlcheck($ViewSessMem['Website']);
$BoardWWWChCk = parse_url($Settings['idburl']);
$MemsWWWChCk = parse_url($ViewSessMem['Website']);
$opennew = " onclick=\"window.open(this.href);return false;\"";
if($BoardWWWChCk['host']==$MemsWWWChCk['host']) {
	$opennew = null; }
$ViewSessMem['Gender']=$sess_result_array['Gender'];
$ViewSessMem['PostCount']=$sess_result_array['PostCount'];
$ViewSessMem['Karma']=$sess_result_array['Karma'];
$ViewSessMem['TimeZone']=$sess_result_array['TimeZone'];
$ViewSessMem['IP']=$sess_result_array['IP'];
$gsess_query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($ViewSessMem['GroupID']));
$gsess_result=sql_query($gsess_query,$SQLStat);
$gsess_result_array = sql_fetch_assoc($gsess_result);
$ViewSessMem['Group']=$sess_result_array['Name'];
$PreAmIHiddenUser = GetUserName($UserSessInfo['UserID'],$Settings['sqltable'],$SQLStat);
$AmIHiddenUser = $PreAmIHiddenUser['Hidden']; }
if(!isset($AmIHiddenUser)) { $AmIHiddenUser = "no"; }
if($AmIHiddenUser===null) { $AmIHiddenUser = "no"; }
if(isset($UserSessInfo['AnonymousLogin']) && $UserSessInfo['AnonymousLogin']=="yes") { $AmIHiddenUser = "yes"; }
if(!isset($UserSessInfo['ViewingPage'])) {
	$UserSessInfo['ViewingPage'] = url_maker(null,"no+ext","act=view","&","=",$prexqstr['index'],$exqstr['index']); }
if(!isset($UserSessInfo['ViewingFile'])) {
	if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
	$UserSessInfo['ViewingFile'] = $exfile['index'].$Settings['file_ext']; }
	if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
	$UserSessInfo['ViewingFile'] = $exfile['index']; } }
if(!isset($UserSessInfo['PreViewingTitle'])) {
	$UserSessInfo['PreViewingTitle'] = "Viewing"; }
if(!isset($UserSessInfo['ViewingTitle'])) {
	$UserSessInfo['ViewingTitle'] = "Board index"; }
$PreExpPage = explode("?",$UserSessInfo['ViewingPage']);
$PreFileName = $UserSessInfo['ViewingFile'];
$qstr = htmlentities("&", ENT_QUOTES, $Settings['charset']);
$qsep = htmlentities("=", ENT_QUOTES, $Settings['charset']);
$PreExpPage = preg_replace("/^\?/","",$UserSessInfo['ViewingPage']);
$PreExpPage = str_replace($qstr, "&", $PreExpPage);
$PreExpPage = str_replace($qsep, "=", $PreExpPage);
parse_str($PreExpPage,$ChkID);
if($PreFileName==$exfile['topic'].$Settings['file_ext']) {
if(isset($ChkID['id'])) { $ChkID = $ChkID['id']; 
$prenum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."topics\" WHERE \"id\"=%i LIMIT 1", array($ChkID)), $SQLStat);
$prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."topics\" WHERE \"id\"=%i LIMIT 1", array($ChkID));
$preresult=sql_query($prequery,$SQLStat);
if($prenum>=1) {
$preresult_array = sql_fetch_assoc($preresult);
$TopicForumID=gsess_result_array["ForumID"];
$TopicCatID=gsess_result_array["CategoryID"]; }
if($prenum<1) {
$TopicForumID=0;
$TopicCatID=0; }
if($CatPermissionInfo['CanViewCategory'][$TopicCatID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$TopicCatID]!="yes") {
	$PreFileName = $exfile['index'].$Settings['file_ext'];
	$PreExpPage = "act=view";
	$UserSessInfo['ViewingPage'] = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']);
	$UserSessInfo['PreViewingTitle'] = "Viewing";
	$UserSessInfo['ViewingTitle'] = "Board index"; }
if($PermissionInfo['CanViewForum'][$TopicForumID]=="no"||
	$PermissionInfo['CanViewForum'][$TopicForumID]!="yes") {
	$PreFileName = $exfile['index'].$Settings['file_ext'];
	$PreExpPage = "act=view";
	$UserSessInfo['ViewingPage'] = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']);
	$UserSessInfo['PreViewingTitle'] = "Viewing";
	$UserSessInfo['ViewingTitle'] = "Board index"; } } }
if($PreFileName==$exfile['forum'].$Settings['file_ext']) {
if(isset($ChkID['id'])) { $ChkID = $ChkID['id']; 
$prenum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i LIMIT 1", array($ChkID)), $SQLStat);
$prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i LIMIT 1", array($ChkID));
$preresult=sql_query($prequery,$SQLStat);
$preresult_array = sql_fetch_assoc($preresult);
$ForumCatID=gsess_result_array["CategoryID"];
sql_free_result($preresult);
if($CatPermissionInfo['CanViewCategory'][$ForumCatID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$ForumCatID]!="yes") {
	$PreFileName = $exfile['index'].$Settings['file_ext'];
	$PreExpPage = "act=view";
	$UserSessInfo['ViewingPage'] = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']);
	$UserSessInfo['PreViewingTitle'] = "Viewing";
	$UserSessInfo['ViewingTitle'] = "Board index"; }
if($PermissionInfo['CanViewForum'][$ChkID]=="no"||
	$PermissionInfo['CanViewForum'][$ChkID]!="yes") {
	$PreFileName = $exfile['index'].$Settings['file_ext'];
	$PreExpPage = "act=view";
	$UserSessInfo['ViewingPage'] = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']);
	$UserSessInfo['PreViewingTitle'] = "Viewing";
	$UserSessInfo['ViewingTitle'] = "Board index"; } } }
if($PreFileName==$exfile['subforum'].$Settings['file_ext']) {
if(isset($ChkID['id'])) { $ChkID = $ChkID['id']; 
$prenum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i LIMIT 1", array($ChkID)), $SQLStat);
$prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i LIMIT 1", array($ChkID));
$preresult=sql_query($prequery,$SQLStat);
$preresult_array = sql_fetch_assoc($preresult);
$ForumCatID=gsess_result_array["CategoryID"];
sql_free_result($preresult);
if($CatPermissionInfo['CanViewCategory'][$ForumCatID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$ForumCatID]!="yes") {
	$PreFileName = $exfile['index'].$Settings['file_ext'];
	$PreExpPage = "act=view";
	$UserSessInfo['ViewingPage'] = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']);
	$UserSessInfo['PreViewingTitle'] = "Viewing";
	$UserSessInfo['ViewingTitle'] = "Board index"; }
if($PermissionInfo['CanViewForum'][$ChkID]=="no"||
	$PermissionInfo['CanViewForum'][$ChkID]!="yes") {
	$PreFileName = $exfile['index'].$Settings['file_ext'];
	$PreExpPage = "act=view";
	$UserSessInfo['ViewingPage'] = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']);
	$UserSessInfo['PreViewingTitle'] = "Viewing";
	$UserSessInfo['ViewingTitle'] = "Board index"; } } }
if($PreFileName==$exfile['category'].$Settings['file_ext']) {
if(isset($ChkID['id'])) { $ChkID = $ChkID['id']; 
if($CatPermissionInfo['CanViewCategory'][$ChkID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$ChkID]!="yes") {
	$PreFileName = $exfile['index'].$Settings['file_ext'];
	$PreExpPage = "act=view";
	$UserSessInfo['ViewingPage'] = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']);
	$UserSessInfo['PreViewingTitle'] = "Viewing";
	$UserSessInfo['ViewingTitle'] = "Board index"; } } }
if($PreFileName==$exfile['subcategory'].$Settings['file_ext']) {
if(isset($ChkID['id'])) { $ChkID = $ChkID['id']; 
if($CatPermissionInfo['CanViewCategory'][$ChkID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$ChkID]!="yes") {
	$PreFileName = $exfile['index'].$Settings['file_ext'];
	$PreExpPage = "act=view";
	$UserSessInfo['ViewingPage'] = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']);
	$UserSessInfo['PreViewingTitle'] = "Viewing";
	$UserSessInfo['ViewingTitle'] = "Board index"; } } }
if($GroupInfo['HasAdminCP']!="yes"&&$UserSessInfo['ShowActHidden']=="yes") {
	$PreFileName = $exfile['index'].$Settings['file_ext'];
	$PreExpPage = "act=view";
	$UserSessInfo['ViewingPage'] = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); }
if($_GET['list']=="all"||$_GET['list']=="members") {
if($UserSessInfo['UserGroup']!=$Settings['GuestGroup']) {
if(($AmIHiddenUser=="no"||$GroupInfo['CanViewAnonymous']=="yes"||$_SESSION['UserID']==$UserSessInfo['UserID'])&&$UserSessInfo['UserID']>0) { 
?>
<tr id="Member<?php echo $i; ?>" class="TableRow3">
<td class="TableColumn3" style="text-align: center;"><a<?php if($GroupInfo['HasAdminCP']=="yes") { ?> title="<?php echo htmlentities($session_user_agent, ENT_QUOTES, $Settings['charset']); ?>"<?php } ?> href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$UserSessInfo['UserID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>"><?php if($AmIHiddenUser=="yes") { echo "*"; } echo $UserSessInfo['MemberName']; ?></a>
<?php if($GroupInfo['CanViewIPAddress']=="yes") { ?> ( <a title="<?php echo $session_ip_address; ?>" onclick="window.open(this.href);return false;" href="<?php echo sprintf($IPCheckURL,$session_ip_address); ?>"><?php echo $session_ip_address; ?></a> )<?php } ?></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $UserSessInfo['UserGroup']; ?></td>
<td class="TableColumn3" style="text-align: center;"><?php if($get_session_id!=session_id()) { ?><a href="<?php echo url_maker($PreFileName,"no+ext",$PreExpPage,$Settings['qstr'],$Settings['qsep'],null,null); ?>"><?php echo $UserSessInfo['PreViewingTitle']; ?> <?php echo $UserSessInfo['ViewingTitle']; ?></a><?php } if($get_session_id==session_id()) { ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=online&list=".$_GET['list']."&page=".$_GET['page'],"&","=",$prexqstr['member'],$exqstr['member']); ?>">Viewing Online Member List</a><?php } ?></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $session_expires; ?></td>
<td class="TableColumn3" style="text-align: center;"><a href="<?php echo $ViewSessMem['Website']; ?>"<?php echo $opennew; ?>>Website</a></td>
<?php if($UserSessInfo['UserID']>0&&$AmIHiddenUser=="no") { ?>
<td class="TableColumn3" style="text-align: center;"><a href="<?php echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=create&id=".$UserSessInfo['UserID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); ?>"<?php echo $opennew; ?>>PM</a></td>
<?php } if($UserSessInfo['UserID']<=0||$AmIHiddenUser=="yes") { ?>
<td class="TableColumn3" style="text-align: center;"><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">PM</a></td>
<?php } ?>
</tr>
<?php } } }
if($UserSessInfo['UserGroup']==$Settings['GuestGroup']) {
if(!isset($UserSessInfo['GuestName'])) { 
	$UserSessInfo['GuestName'] = "Guest"; }
if(!isset($UserSessInfo['UserID'])) { 
	$UserSessInfo['UserID'] = "0"; }
if($_GET['list']=="all"||$_GET['list']=="guests") {
if(user_agent_check($session_user_agent)) {
	$UserSessInfo['GuestName'] = user_agent_check($session_user_agent); }
?>
<tr id="Member<?php echo $i; ?>" class="TableRow3">
<td class="TableColumn3" style="text-align: center;"><span<?php if($GroupInfo['HasAdminCP']=="yes") { ?> title="<?php echo htmlentities($session_user_agent, ENT_QUOTES, $Settings['charset']); ?>"<?php } ?>><?php echo $UserSessInfo['GuestName']; ?></span>
<?php if($GroupInfo['CanViewIPAddress']=="yes") { ?> ( <a title="<?php echo $session_ip_address; ?>" onclick="window.open(this.href);return false;" href="<?php echo sprintf($IPCheckURL,$session_ip_address); ?>"><?php echo $session_ip_address; ?></a> )<?php } ?></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $UserSessInfo['UserGroup']; ?></td>
<td class="TableColumn3" style="text-align: center;"><?php if($get_session_id!=session_id()) { ?><a href="<?php echo url_maker($PreFileName,"no+ext",$PreExpPage,$Settings['qstr'],$Settings['qsep'],null,null); ?>"><?php echo $UserSessInfo['PreViewingTitle']; ?> <?php echo $UserSessInfo['ViewingTitle']; ?></a><?php } if($get_session_id==session_id()) { ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=online&list=".$_GET['list']."&page=".$_GET['page'],"&","=",$prexqstr['member'],$exqstr['member']); ?>">Viewing Online Member List</a><?php } ?></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $session_expires; ?></td>
<td class="TableColumn3" style="text-align: center;"><a href="<?php echo $ViewSessMem['Website']; ?>"<?php echo $opennew; ?>>Website</a></td>
<?php if($UserSessInfo['UserID']>0&&$AmIHiddenUser=="no") { ?>
<td class="TableColumn3" style="text-align: center;"><a href="<?php echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=create&id=".$UserSessInfo['UserID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); ?>"<?php echo $opennew; ?>>PM</a></td>
<?php } if($UserSessInfo['UserID']<=0||$AmIHiddenUser=="yes") { ?>
<td class="TableColumn3" style="text-align: center;"><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">PM</a></td>
<?php } ?>
</tr>
<?php } }
++$i; }
?>
<tr id="MemEnd" class="TableRow4">
<td class="TableColumn4" colspan="8">&#160;</td>
</tr>
</table></div>
<?php 
if($pagenum>1) {
?>
<div class="DivMembers">&#160;</div>
<?php }
echo $pstring;
//List Page Number Code end
if($pagenum>1) {
?>
<div class="DivPageLinks">&#160;</div>
<?php } }
if($_GET['act']=="view") { 
$num=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($_GET['id'])), $SQLStat);
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($_GET['id']));
$result=sql_query($query,$SQLStat);
$i=0;
if($num==0||$_GET['id']<=0) { redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
$result_array = sql_fetch_assoc($result);
$ViewMem['ID']=$result_array['id'];
$ViewMem['Name']=$result_array['Name'];
$ViewMem['Signature']=$result_array['Signature'];
$ViewMem['Avatar']=$result_array['Avatar'];
$ViewMem['AvatarSize']=$result_array['AvatarSize'];
$ViewMem['Email']=$result_array['Email'];
$ViewMem['GroupID']=$result_array['GroupID'];
$ViewMem['LevelID']=$result_array['LevelID'];
if(!is_numeric($ViewMem['LevelID'])) { $ViewMem['LevelID'] = null; }
$ViewMem['RankID']=$result_array['RankID'];
if(!is_numeric($ViewMem['RankID'])) { $ViewMem['RankID'] = null; }
$ViewMem['HiddenMember']=$result_array['HiddenMember'];
$ViewMem['WarnLevel']=$result_array['WarnLevel'];
$ViewMem['Interests']=$result_array['Interests'];
$ViewMem['Title']=$result_array['Title'];
$ViewMem['Joined']=$result_array['Joined'];
$tmpusrcurtime = new DateTime();
$tmpusrcurtime->setTimestamp($ViewMem['Joined']);
$tmpusrcurtime->setTimezone($usertz);
$ViewMem['Joined']=$tmpusrcurtime->format("M j Y, ".$_SESSION['iDBTimeFormat']);
$ViewMem['LastActive']=$result_array['LastActive'];
$tmpusrcurtime = new DateTime();
$tmpusrcurtime->setTimestamp($ViewMem['LastActive']);
$tmpusrcurtime->setTimezone($usertz);
$ViewMem['LastActive']=$tmpusrcurtime->format("M j Y, ".$_SESSION['iDBTimeFormat']);
$ViewMem['Website']=$result_array['Website'];
if($ViewMem['Website']=="http://") { 
	$ViewMem['Website'] = $Settings['idburl']; }
$ViewMem['Website'] = urlcheck($ViewMem['Website']);
$BoardWWWChCk = parse_url($Settings['idburl']);
$MemsWWWChCk = parse_url($ViewMem['Website']);
$opennew = " onclick=\"window.open(this.href);return false;\"";
if($BoardWWWChCk['host']==$MemsWWWChCk['host']) {
	$opennew = null; }
$ViewMem['Gender']=$result_array['Gender'];
$ViewMem['PostCount']=$result_array['PostCount'];
$ViewMem['Karma']=$result_array['Karma'];
$ViewMem['TimeZone']=$result_array['TimeZone'];
$viewmemcurtime = new DateTime();
$viewmemcurtime->setTimezone(new DateTimeZone($ViewMem['TimeZone']));
$ViewMem['IP']=$result_array['IP'];
if($ViewMem['LevelID']!==null&&$ViewMem['LevelID']!=0) {
$lnum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."levels\" WHERE \"id\"=%i LIMIT 1", array($ViewMem['LevelID'])), $SQLStat);
$lquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."levels\" WHERE \"id\"=%i LIMIT 1", array($ViewMem['LevelID']));
$lresult=sql_query($lquery,$SQLStat);
if ($lresult !== false && $lnum > 0) {
$lresult_array = sql_fetch_assoc($lresult);
$ViewMem['Level']=$lresult_array['Name']; } else { $ViewMem['Level'] = ""; }
sql_free_result($lresult); } else {
 $ViewMem['Level'] = ""; }
if($ViewMem['RankID']!==null&&$ViewMem['RankID']!=0) {
$rnum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."ranks\" WHERE \"id\"=%i LIMIT 1", array($ViewMem['RankID'])), $SQLStat);
$rquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."ranks\" WHERE \"id\"=%i LIMIT 1", array($ViewMem['RankID']));
$rresult=sql_query($rquery,$SQLStat);
if ($rresult !== false && $rnum > 0) {
$rresult_array = sql_fetch_assoc($rresult);
$ViewMem['Rank']=$rresult_array['Name']; } else { $ViewMem['Rank'] = ""; }
sql_free_result($rresult); } else {
 $ViewMem['Rank'] = ""; }
$gquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($ViewMem['GroupID']));
$gresult=sql_query($gquery,$SQLStat);
$gresult_array = sql_fetch_assoc($gresult);
$ViewMem['Group']=$gresult_array['Name'];
/*
$GroupNamePrefix=$gresult_array['NamePrefix'];
$GroupNameSuffix=$gresult_array['NameSuffix'];
*/
sql_free_result($gresult);
if($ViewMem['Title']=="") { $ViewMem['Title'] = $ViewMem['Group']; }
/*
if(isset($GroupNamePrefix)&&$GroupNamePrefix!=null) {
	$ViewMem['Name'] = $GroupNamePrefix.$ViewMem['Name']; }
if(isset($GroupNameSuffix)&&$GroupNameSuffix!=null) {
	$ViewMem['Name'] = $ViewMem['Name'].$GroupNameSuffix; }
*/
if($ViewMem['HiddenMember']=="yes") { redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
$membertitle = " ".$ThemeSet['TitleDivider']." ".$ViewMem['Name'];	
if ($ViewMem['Avatar']=="http://"||$ViewMem['Avatar']==null||
	strtolower($ViewMem['Avatar'])=="noavatar") {
$ViewMem['Avatar']=$ThemeSet['NoAvatar'];
$ViewMem['AvatarSize']=$ThemeSet['NoAvatarSize']; }
$AvatarSize1=explode("x", $ViewMem['AvatarSize']);
$AvatarSize1W=$AvatarSize1[0]; $AvatarSize1H=$AvatarSize1[1];
$ViewMem['Signature'] = text2icons($ViewMem['Signature'],$Settings['sqltable'],$SQLStat);
$ViewMem['Signature'] = url2link($ViewMem['Signature']);
if($_GET['view']==null) { $_GET['view'] = "profile"; }
if($_GET['view']!="profile"&&$_GET['view']!="avatar"&&
	$_GET['view']!="website"&&$_GET['view']!="homepage") { $_GET['view'] = "profile"; }
if($_GET['view']=="avatar") { 
	session_write_close(); $urlstatus = 302;
	header("Location: ".$ViewMem['Avatar']);
	gzip_page($Settings['use_gzip'],$GZipEncode['Type']); die(); }
if($_GET['view']=="website"||$_GET['view']=="homepage") { 
	if ($ViewMem['Website']!="http://"&&$ViewMem['Website']!=null) {
	session_write_close(); $urlstatus = 302;
	header("Location: ".$ViewMem['Website']); 
	gzip_page($Settings['use_gzip'],$GZipEncode['Type']); die(); }
	if ($ViewMem['Website']=="http://"||$ViewMem['Website']==null||
	strtolower($ViewMem['Avatar'])=="noavatar") {
	session_write_close(); $urlstatus = 302;
	header("Location: ".$BoardURL."index.php?act=view"); 
	gzip_page($Settings['use_gzip'],$GZipEncode['Type']); die(); } }
if(isset($_SESSION['OldViewingPage'])) { $_SESSION['AncientViewingPage'] = $_SESSION['OldViewingPage']; } else { $_SESSION['AncientViewingPage'] = url_maker(null,"no+ext","act=view","&","=",$prexqstr['index'],$exqstr['index']); }
if(isset($_SESSION['OldViewingFile'])) { $_SESSION['AncientViewingFile'] = $_SESSION['OldViewingFile']; } else { 
	 if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
	    $_SESSION['AncientViewingFile'] = $exfile['index'].$Settings['file_ext']; }
	 if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
	    $_SESSION['AncientViewingFile'] = $exfile['index']; } }
if(isset($_SESSION['OldPreViewingTitle'])) { $_SESSION['AncientPreViewingTitle'] = $_SESSION['OldPreViewingTitle']; } else { $_SESSION['AncientPreViewingTitle'] = "Viewing"; }
if(isset($_SESSION['OldViewingTitle'])) { $_SESSION['AncientViewingTitle'] = $_SESSION['OldViewingTitle']; } else { $_SESSION['AncientViewingTitle'] = "Board index"; }
if(isset($_SESSION['OldExtraData'])) { $_SESSION['AncientExtraData'] = $_SESSION['OldExtraData']; } else { $_SESSION['AncientExtraData'] = "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;"; }
if(isset($_SESSION['ViewingPage'])) { $_SESSION['OldViewingPage'] = $_SESSION['ViewingPage']; } else { $_SESSION['OldViewingPage'] = url_maker(null,"no+ext","act=view","&","=",$prexqstr['index'],$exqstr['index']); }
if(isset($_SESSION['ViewingFile'])) { $_SESSION['OldViewingFile'] = $_SESSION['ViewingFile']; } else { 
	 if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
	    $_SESSION['OldViewingFile'] = $exfile['index'].$Settings['file_ext']; }
	 if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
	    $_SESSION['OldViewingFile'] = $exfile['index']; } }
if(isset($_SESSION['PreViewingTitle'])) { $_SESSION['OldPreViewingTitle'] = $_SESSION['PreViewingTitle']; } else { $_SESSION['OldPreViewingTitle'] = "Viewing"; }
if(isset($_SESSION['ViewingTitle'])) { $_SESSION['OldViewingTitle'] = $_SESSION['ViewingTitle']; } else { $_SESSION['OldViewingTitle'] = "Board index"; }
if(isset($_SESSION['ExtraData'])) { $_SESSION['OldExtraData'] = $_SESSION['ExtraData']; } else { $_SESSION['OldExtraData'] = "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;"; }
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=view&id=".$_GET['id'],"&","=",$prexqstr['member'],$exqstr['member']);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['member'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['member']; }
$_SESSION['PreViewingTitle'] = "Viewing Profile:";
$_SESSION['ViewingTitle'] = $ViewMem['Name'];
$_SESSION['ExtraData'] = "currentact:".$_GET['act']."; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:".$ViewMem['ID'].";";
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Viewing Profile</a></div>
<div class="DivNavLinks">&#160;</div>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Viewing profile<?php echo $ThemeSet['NavLinkDivider']; ?><?php echo $ViewMem['Name']; ?></a>
</span></div>
<?php } ?>
<table class="Table1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1">
<td class="TableColumn1" colspan="2"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Viewing profile<?php echo $ThemeSet['NavLinkDivider']; ?><?php echo $ViewMem['Name']; ?></a>
</span></td>
</tr><?php } ?>
<tr id="Member" class="TableRow2">
<th class="TableColumn2" style="width: 50%;">Avatar</th>
<th class="TableColumn2" style="width: 50%;">User Info</th>
</tr>
<tr class="TableRow3" id="MemberProfile">
<td class="TableColumn3">
<?php  // Avatar Table Thanks For SeanJ's Help at http://seanj.jcink.com/  
 ?>
 <table class="AvatarTable" style="width: 100%; height: 100px; text-align: center;">
	<tr class="AvatarRow" style="width: 100px; height: 100px;">
		<td class="AvatarRow" style="width: 100%; height: 100%; text-align: center; vertical-align: middle;">
		<img src="<?php echo $ViewMem['Avatar']; ?>" alt="<?php echo $ViewMem['Name']; ?>'s Avatar" title="<?php echo $ViewMem['Name']; ?>'s Avatar" style="border: 0px; width: <?php echo $AvatarSize1W; ?>px; height: <?php echo $AvatarSize1H; ?>px;" />
		</td>
	</tr>
 </table>
<div style="text-align: center;">
Name: <?php echo $ViewMem['Name']; ?><br />
Title: <?php echo $ViewMem['Title']; ?>
<?php if($GroupInfo['CanViewIPAddress']=="yes") { ?>
<br />User IP: <a onclick="window.open(this.href);return false;" href="<?php echo sprintf($IPCheckURL,$ViewMem['IP']); ?>">
<?php echo $ViewMem['IP']; echo "</a>"; } ?></div>
</td>
<td class="TableColumn3">
&#160;User Name: <?php echo $ViewMem['Name']; ?><br />
&#160;User Title: <?php echo $ViewMem['Title']; ?><br />
&#160;User Group: <?php echo $ViewMem['Group']; ?><br />
<?php if($ViewMem['Level']!="") { ?>&#160;User Level: <?php echo $ViewMem['Level']; ?><br /><?php } ?>
&#160;User Joined: <?php echo $ViewMem['Joined']; ?><br />
&#160;Last Active: <?php echo $ViewMem['LastActive']; ?><br />
&#160;User Time: <?php echo $viewmemcurtime->format("M j Y, ".$_SESSION['iDBTimeFormat']); ?><br />
&#160;User Website: <a href="<?php echo $ViewMem['Website']; ?>"<?php echo $opennew; ?>>Website</a><br />
&#160;Post Count: <?php echo $ViewMem['PostCount']; ?><br />
&#160;Karma: <?php echo $ViewMem['Karma']; ?><br />
<?php if($ViewMem['Rank']!="") { ?>&#160;Karma Level: <?php echo $ViewMem['Rank']; ?><br /><?php } ?>
&#160;Interests: <?php echo $ViewMem['Interests']; ?><br />
&#160;Topics: <?php if($Settings['enable_search']=="on"&&$GroupInfo['CanSearch']=="yes") { ?>
<a href="<?php echo url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=%&type=wildcard&memid=".$ViewMem['ID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search']); ?>">Find Topics</a>
<?php } ?>
</td>
</tr>
<tr class="TableRow4">
<td class="TableColumn4" colspan="2">&#160;</td>
</tr>
</table></div>
<?php sql_free_result($result); } 
if($_GET['act']=="logout") {
session_unset();
header("Clear-Site-Data: \"cache\", \"cookies\"");
if($cookieDomain==null) {
setcookie("MemberName", "", $utccurtime->getTimestamp() - 3600, $cbasedir);
setcookie("UserID", "", $utccurtime->getTimestamp() - 3600, $cbasedir);
setcookie("SessPass", "", $utccurtime->getTimestamp() - 3600, $cbasedir);
if(isset($_COOKIE['AnonymousLogin'])) {
setcookie("AnonymousLogin", "", $utccurtime->getTimestamp() - 3600, $cbasedir); }
setcookie(session_name(), "", $utccurtime->getTimestamp() - 3600, $cbasedir); }
if($cookieDomain!=null) {
if($cookieSecure===true) {
setcookie("MemberName", "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 1);
setcookie("UserID", "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 1);
setcookie("SessPass", "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 1);
if(isset($_COOKIE['AnonymousLogin'])) {
setcookie("AnonymousLogin", "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 1); }
setcookie(session_name(), "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 1); }
if($cookieSecure===false) {
setcookie("MemberName", "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 0);
setcookie("UserID", "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 0);
setcookie("SessPass", "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 0);
if(isset($_COOKIE['AnonymousLogin'])) {
setcookie("AnonymousLogin", "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 0); }
setcookie(session_name(), "", $utccurtime->getTimestamp() - 3600, $cbasedir, $cookieDomain, 0); } }
unset($_COOKIE[session_name()]);
$_SESSION = array();
//session_unset();
//session_destroy();
$temp_user_ip = $_SERVER['REMOTE_ADDR'];
$exptime = $utccurtime->getTimestamp() - ini_get("session.gc_maxlifetime");
sql_query(sql_pre_query("DELETE FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" < %i OR ip_address='%s'", array($exptime,$temp_user_ip)),$SQLStat);
redirect("location",$rbasedir.url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($_GET['act']=="login") {
if($_SESSION['UserID']!=0&&$_SESSION['UserID']!=null) { 
redirect("location",$rbasedir.url_maker($exfile['member'],$Settings['file_ext'],"act=logout",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($_SESSION['UserID']==0||$_SESSION['UserID']==null) {
if(isset($_SESSION['OldViewingPage'])) { $_SESSION['AncientViewingPage'] = $_SESSION['OldViewingPage']; } else { $_SESSION['AncientViewingPage'] = url_maker(null,"no+ext","act=view","&","=",$prexqstr['index'],$exqstr['index']); }
if(isset($_SESSION['OldViewingFile'])) { $_SESSION['AncientViewingFile'] = $_SESSION['OldViewingFile']; } else { 
	 if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
	    $_SESSION['AncientViewingFile'] = $exfile['index'].$Settings['file_ext']; }
	 if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
	    $_SESSION['AncientViewingFile'] = $exfile['index']; } }
if(isset($_SESSION['OldPreViewingTitle'])) { $_SESSION['AncientPreViewingTitle'] = $_SESSION['OldPreViewingTitle']; } else { $_SESSION['AncientPreViewingTitle'] = "Viewing"; }
if(isset($_SESSION['OldViewingTitle'])) { $_SESSION['AncientViewingTitle'] = $_SESSION['OldViewingTitle']; } else { $_SESSION['AncientViewingTitle'] = "Board index"; }
if(isset($_SESSION['OldExtraData'])) { $_SESSION['AncientExtraData'] = $_SESSION['OldExtraData']; } else { $_SESSION['AncientExtraData'] = "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;"; }
if(isset($_SESSION['ViewingPage'])) { $_SESSION['OldViewingPage'] = $_SESSION['ViewingPage']; } else { $_SESSION['OldViewingPage'] = url_maker(null,"no+ext","act=view","&","=",$prexqstr['index'],$exqstr['index']); }
if(isset($_SESSION['ViewingFile'])) { $_SESSION['OldViewingFile'] = $_SESSION['ViewingFile']; } else { 
	 if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
	    $_SESSION['OldViewingFile'] = $exfile['index'].$Settings['file_ext']; }
	 if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
	    $_SESSION['OldViewingFile'] = $exfile['index']; } }
if(isset($_SESSION['PreViewingTitle'])) { $_SESSION['OldPreViewingTitle'] = $_SESSION['PreViewingTitle']; } else { $_SESSION['OldPreViewingTitle'] = "Viewing"; }
if(isset($_SESSION['ViewingTitle'])) { $_SESSION['OldViewingTitle'] = $_SESSION['ViewingTitle']; } else { $_SESSION['OldViewingTitle'] = "Board index"; }
if(isset($_SESSION['ExtraData'])) { $_SESSION['OldExtraData'] = $_SESSION['ExtraData']; } else { $_SESSION['OldExtraData'] = "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;"; }
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=login","&","=",$prexqstr['member'],$exqstr['member']);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['member'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['member']; }
$_SESSION['PreViewingTitle'] = "Act: ";
$_SESSION['ViewingTitle'] = "Logging in";
$_SESSION['ExtraData'] = "currentact:".$_GET['act']."; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;";
$membertitle = " ".$ThemeSet['TitleDivider']." Login";
$UFID = rand_uuid("rand");
$_SESSION['UserFormID'] = $UFID;
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Login</a></div>
<div class="DivNavLinks">&#160;</div>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Log in</a>
</span></div>
<?php } ?>
<table class="Table1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1">
<td class="TableColumn1"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Log in</a>
</span></td>
</tr><?php } ?>
<tr class="TableRow2">
<th class="TableColumn2" style="width: 100%; text-align: left;">&#160;Inert your login info: </th>
</tr>
<tr class="TableRow3">
<td class="TableColumn3">
<form style="display: inline;" method="post" action="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=login_now",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 30%;"><label class="TextBoxLabel" for="username">Enter User Name: </label></td>
	<td style="width: 70%;"><input maxlength="256" class="TextBox" id="username" type="text" name="username" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 30%;"><label class="TextBoxLabel" for="userpass">Enter Password: </label></td>
	<td style="width: 70%;"><input maxlength="30" class="TextBox" id="userpass" type="password" name="userpass" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 30%;"><label class="TextBoxLabel" title="Store userinfo as a cookie so you dont need to login again." for="storecookie">Store as cookie?</label></td>
	<td style="width: 70%;"><select id="storecookie" name="storecookie" class="TextBox">
<option value="true">Yes</option>
<option value="false">No</option>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 30%;"><label class="TextBoxLabel" title="Use your Email address for username." for="loginemail">Login by Email?</label></td>
	<td style="width: 70%;"><select id="loginemail" name="loginemail" class="TextBox">
<option value="false">No</option>
<option value="true">Yes</option>
</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 30%;"><label class="TextBoxLabel" title="Login anonymously?" for="anonymouslogin">Login anonymously?</label></td>
	<td style="width: 70%;"><select id="anonymouslogin" name="anonymouslogin" class="TextBox">
<option value="false">No</option>
<option value="true">Yes</option>
</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="loginmember" style="display: none;" />
<input type="hidden" style="display: none;" name="fid" value="<?php echo $UFID; ?>" />
<input type="hidden" style="display: none;" name="ubid" value="<?php echo $Settings['BoardUUID']; ?>" />
<input class="Button" type="submit" value="Log in" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableRow4">
<td class="TableColumn4">&#160;</td>
</tr>
</table></div>
<?php } } if($_POST['act']=="loginmember"&&$_GET['act']=="login_now") {
if($_SESSION['UserID']!=0&&$_SESSION['UserID']!=null) { 
redirect("location",$rbasedir.url_maker($exfile['member'],$Settings['file_ext'],"act=logout",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($_SESSION['UserID']==0||$_SESSION['UserID']==null) {
if(isset($_SESSION['OldViewingPage'])) { $_SESSION['AncientViewingPage'] = $_SESSION['OldViewingPage']; } else { $_SESSION['AncientViewingPage'] = url_maker(null,"no+ext","act=view","&","=",$prexqstr['index'],$exqstr['index']); }
if(isset($_SESSION['OldViewingFile'])) { $_SESSION['AncientViewingFile'] = $_SESSION['OldViewingFile']; } else { 
	 if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
	    $_SESSION['AncientViewingFile'] = $exfile['index'].$Settings['file_ext']; }
	 if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
	    $_SESSION['AncientViewingFile'] = $exfile['index']; } }
if(isset($_SESSION['OldPreViewingTitle'])) { $_SESSION['AncientPreViewingTitle'] = $_SESSION['OldPreViewingTitle']; } else { $_SESSION['AncientPreViewingTitle'] = "Viewing"; }
if(isset($_SESSION['OldViewingTitle'])) { $_SESSION['AncientViewingTitle'] = $_SESSION['OldViewingTitle']; } else { $_SESSION['AncientViewingTitle'] = "Board index"; }
if(isset($_SESSION['OldExtraData'])) { $_SESSION['AncientExtraData'] = $_SESSION['OldExtraData']; } else { $_SESSION['AncientExtraData'] = "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;"; }
if(isset($_SESSION['ViewingPage'])) { $_SESSION['OldViewingPage'] = $_SESSION['ViewingPage']; } else { $_SESSION['OldViewingPage'] = url_maker(null,"no+ext","act=view","&","=",$prexqstr['index'],$exqstr['index']); }
if(isset($_SESSION['ViewingFile'])) { $_SESSION['OldViewingFile'] = $_SESSION['ViewingFile']; } else { 
	 if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
	    $_SESSION['OldViewingFile'] = $exfile['index'].$Settings['file_ext']; }
	 if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
	    $_SESSION['OldViewingFile'] = $exfile['index']; } }
if(isset($_SESSION['PreViewingTitle'])) { $_SESSION['OldPreViewingTitle'] = $_SESSION['PreViewingTitle']; } else { $_SESSION['OldPreViewingTitle'] = "Viewing"; }
if(isset($_SESSION['ViewingTitle'])) { $_SESSION['OldViewingTitle'] = $_SESSION['ViewingTitle']; } else { $_SESSION['OldViewingTitle'] = "Board index"; }
if(isset($_SESSION['ExtraData'])) { $_SESSION['OldExtraData'] = $_SESSION['ExtraData']; } else { $_SESSION['OldExtraData'] = "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;"; }
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=login","&","=",$prexqstr['member'],$exqstr['member']);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['member'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['member']; }
if($_POST['anonymouslogin']=="true") {
$_SESSION['AnonymousLogin'] = "yes"; $_COOKIE['AnonymousLogin'] = "yes"; } else {
$_SESSION['AnonymousLogin'] = "no"; $_COOKIE['AnonymousLogin'] = "no"; }
$_SESSION['PreViewingTitle'] = "Act: ";
$_SESSION['ViewingTitle'] = "Logging in";
$_SESSION['ExtraData'] = "currentact:".$_GET['act']."; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;";
$membertitle = " ".$ThemeSet['TitleDivider']." Login";
$REFERERurl = parse_url($_SERVER['HTTP_REFERER']);
$URL['REFERER'] = $REFERERurl['host'];
$URL['HOST'] = $_SERVER['SERVER_NAME'];
$REFERERurl = null;
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Login</a></div>
<div class="DivNavLinks">&#160;</div>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">&#160;<a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Log in</a></span>
</div>
<?php } ?>
<table class="Table1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1">
<td class="TableColumn1">
<span style="text-align: left;">&#160;<a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Log in</a></span>
</td>
</tr><?php } ?>
<tr class="TableRow2">
<th class="TableColumn2" style="width: 100%; text-align: left;">&#160;Login Message: </th>
</tr>
<tr class="TableRow3">
<td class="TableColumn3">
<table style="width: 100%; height: 25%; text-align: center;">
<?php
if(!isset($_POST['loginemail'])) { $_POST['loginemail'] = "false"; }
if (pre_strlen($_POST['userpass'])>"60") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your password is too big.<br />
	</span>&#160;</td>
</tr>
<?php } if (pre_strlen($_POST['username'])>"30") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your user name is too big.<br />
	</span>&#160;</td>
</tr>
<?php } if ($_POST['loginemail']=="true"&&filter_var($_POST['loginemail'], FILTER_VALIDATE_EMAIL)) { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your email is not a valid email address.<br />
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
<?php } if ($Settings['TestReferer']=="on") {
	if ($URL['HOST']!=$URL['REFERER']) { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Sorry the referering url dose not match our host name.<br />
	</span>&#160;</td>
</tr>
<?php } } $BanError = null;
if ($Error=="Yes") {
redirect("refresh",$rbasedir.url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],false),"4"); }
if($Error!="Yes"){
$YourName = stripcslashes(htmlspecialchars($_POST['username'], ENT_QUOTES, $Settings['charset']));
//$YourName = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $YourName);
$YourName = remove_spaces($YourName);
$passtype="ODFH";
if($_POST['loginemail']!="true") {
$numlog=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"Name\"='%s' LIMIT 1", array($YourName)), $SQLStat);
$querylog = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"Name\"='%s' LIMIT 1", array($YourName)); }
if($_POST['loginemail']=="true") {
$numlog=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"Email\"='%s' LIMIT 1", array($YourName)), $SQLStat);
$querylog = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"Email\"='%s' LIMIT 1", array($YourName)); }
$resultlog=sql_query($querylog,$SQLStat);
if($numlog>=1) {
$resultlog_array = sql_fetch_assoc($resultlog);
$YourName=$resultlog_array['Name'];
$YourPassTry=$resultlog_array['UserPassword'];
$HashType=$resultlog_array['HashType'];
$HashType=str_replace("IntDBH", "iDBH", $HashType);
$JoinedPass=$resultlog_array['Joined'];
$HashSalt=$resultlog_array['Salt'];
$UpdateHash = false; $YourPassword = null;
//Used if you forget your password will change on next login.
if($HashType=="NoHash") { $YourPassword = $_POST['userpass']; }
if($HashType=="NoHASH") { $YourPassword = $_POST['userpass']; }
if($HashType=="PlainText") { $YourPassword = $_POST['userpass']; }
//Used to not allow guest user number -1 to login.
if($HashType=="NoPass") { $YourPassword = null; $UpdateHash = false; }
if($HashType=="NoPassword") { $YourPassword = null; $UpdateHash = false; }
if($HashType=="GuestPass") { $YourPassword = null; $UpdateHash = false; }
if($HashType=="GuestPassword") { $YourPassword = null; $UpdateHash = false; }
//iDB hashing system
if($HashType=="ODFH") { $YourPassword = PassHash2x($_POST['userpass']); }
if($HashType=="IPB2") { $YourPassword = hash2xkey($_POST['userpass'],$HashSalt); }
if($HashType=="DF4H") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"sha1"); }
if($HashType=="iDBH2") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"md2"); }
if($HashType=="iDBH4") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"md4"); }
if($HashType=="iDBH5") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"md5"); }
if($HashType=="iDBH") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"sha1"); }
if($HashType=="iDBH1") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"sha1"); }
if($HashType=="iDBH224") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"sha224"); }
if($HashType=="iDBH256") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"sha256"); }
if($HashType=="iDBH384") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"sha384"); }
if($HashType=="iDBH512") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"sha512"); }
if($HashType=="iDBH3224") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"sha3-224"); }
if($HashType=="iDBH3256") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"sha3-256"); }
if($HashType=="iDBH3384") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"sha3-384"); }
if($HashType=="iDBH3512") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"sha3-512"); }
if($HashType=="iDBHRMD128") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"ripemd128"); }
if($HashType=="iDBHRMD160") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"ripemd160"); }
if($HashType=="iDBHRMD256") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"ripemd256"); }
if($HashType=="iDBHRMD320") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"ripemd320"); }
if($HashType=="iDBCRYPT") { $YourPassword = neo_b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"bcrypt"); }
if($HashType=="iDBARGON2I") { $YourPassword = neo_b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"argon2i"); }
if($HashType=="iDBARGON2ID") { $YourPassword = neo_b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"argon2id"); }
if($HashType=="NoPass") { $YourPassword = "iDB"; $YourPassTry = "IntDB"; }
if($HashType=="NoPassword") { $YourPassword = "iDB"; $YourPassTry = "IntDB"; }
if($HashType=="GuestPass") { $YourPassword = "iDB"; $YourPassTry = "IntDB"; }
if($HashType=="GuestPassword") { $YourPassword = "iDB"; $YourPassTry = "IntDB"; }
/*if($YourPassword!=$YourPassTry) { $passright = false; } 
if($YourPassword==$YourPassTry) { $passright = true; */
if(hash_equals($YourPassTry, $YourPassword)==false) { $passright = false; } 
if(hash_equals($YourPassTry, $YourPassword)==true) { $passright = true;
$YourIDM=$resultlog_array['id'];
$YourNameM=$resultlog_array['Name'];
$YourPassM=$resultlog_array['UserPassword'];
$PostCount=$resultlog_array['PostCount'];
$YourGroupM=$resultlog_array['GroupID'];
$YourGroupIDM=$YourGroupM;
$YourLastPostTime=$resultlog_array['LastPostTime'];
$YourBanTime=$resultlog_array['BanTime'];
$CGMTime = $utccurtime->getTimestamp();
if($YourBanTime!=0&&$YourBanTime!=null) {
if($YourBanTime>=$CGMTime) { $BanError = "yes"; }
if($YourBanTime<0) { $BanError = "yes"; } }
$gquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($YourGroupM));
$gresult=sql_query($gquery,$SQLStat);
$gresult_array = sql_fetch_assoc($gresult);
$YourGroupM=$gresult_array['Name'];
sql_free_result($gresult);
$YourTimeZoneM=$resultlog_array['TimeZone'];
$JoinedDate=$resultlog_array['Joined'];
$UseTheme=$resultlog_array['UseTheme'];
$NewHashSalt = salt_hmac();
if($Settings['use_hashtype']=="md2") { $iDBHash = "iDBH2";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"md2"); }
if($Settings['use_hashtype']=="md4") { $iDBHash = "iDBH4";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"md4"); }
if($Settings['use_hashtype']=="md5") { $iDBHash = "iDBH5";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"md5"); }
if($Settings['use_hashtype']=="sha1") { $iDBHash = "iDBH";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"sha1"); }
if($Settings['use_hashtype']=="sha224") { $iDBHash = "iDBH224";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"sha224"); }
if($Settings['use_hashtype']=="sha256") { $iDBHash = "iDBH256";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"sha256"); }
if($Settings['use_hashtype']=="sha384") { $iDBHash = "iDBH384";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"sha384"); }
if($Settings['use_hashtype']=="sha512") { $iDBHash = "iDBH512";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"sha512"); }
if($Settings['use_hashtype']=="sha3-224") { $iDBHash = "iDBH3224";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"sha3-224"); }
if($Settings['use_hashtype']=="sha3-256") { $iDBHash = "iDBH3256";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"sha3-256"); }
if($Settings['use_hashtype']=="sha3-384") { $iDBHash = "iDBH3384";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"sha3-384"); }
if($Settings['use_hashtype']=="sha3-512") { $iDBHash = "iDBH3512";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"sha3-512"); }
if($Settings['use_hashtype']=="ripemd128") { $iDBHash = "iDBHRMD128";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"ripemd128"); }
if($Settings['use_hashtype']=="ripemd160") { $iDBHash = "iDBHRMD160";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"ripemd160"); }
if($Settings['use_hashtype']=="ripemd256") { $iDBHash = "iDBHRMD256";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"ripemd256"); }
if($Settings['use_hashtype']=="ripemd320") { $iDBHash = "iDBHRMD320";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"ripemd320"); }
if($Settings['use_hashtype']=="bcrypt") { $iDBHash = "iDBCRYPT";
$NewPassword = neo_b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"bcrypt"); }
if($Settings['use_hashtype']=="argon2i") { $iDBHash = "iDBARGON2I";
$NewPassword = neo_b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"argon2i"); }
if($Settings['use_hashtype']=="argon2id") { $iDBHash = "iDBARGON2ID";
$NewPassword = neo_b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"argon2id"); }
$NewDay=$utccurtime->getTimestamp();
$NewIP=$_SERVER['REMOTE_ADDR'];
if($BanError!="yes") {
$queryup = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"UserPassword\"='%s',\"HashType\"='%s',\"LastActive\"=%i,\"LastLogin\"=%i,\"IP\"='%s',\"Salt\"='%s' WHERE \"id\"=%i", array($NewPassword,$iDBHash,$NewDay,$NewDay,$NewIP,$NewHashSalt,$YourIDM));
sql_query($queryup,$SQLStat);
sql_free_result($resultlog);
//session_regenerate_id();
$_SESSION['Theme']=$UseTheme;
$_SESSION['MemberName']=$YourNameM;
$_SESSION['UserID']=$YourIDM;
$_SESSION['UserIP']=$_SERVER['REMOTE_ADDR'];
$_SESSION['UserTimeZone']=$YourTimeZoneM;
$usertz = new DateTimeZone($_SESSION['UserTimeZone']);
$usercurtime->setTimestamp($defcurtime->getTimestamp());
$usercurtime->setTimezone($usertz);
$_SESSION['UserGroup']=$YourGroupM;
$_SESSION['UserGroupID']=$YourGroupIDM;
$_SESSION['UserPass']=$NewPassword;
$_SESSION['LastPostTime'] = $YourLastPostTime;
$_SESSION['DBName']=$Settings['sqldb'];
if($_POST['storecookie']=="true") {
if($cookieDomain==null) {
setcookie("MemberName", $YourNameM, time() + (7 * 86400), $cbasedir);
setcookie("UserID", $YourIDM, time() + (7 * 86400), $cbasedir);
setcookie("SessPass", $NewPassword, time() + (7 * 86400), $cbasedir); }
if($cookieDomain!=null) {
if($cookieSecure===true) {
setcookie("MemberName", $YourNameM, time() + (7 * 86400), $cbasedir, $cookieDomain, 1);
setcookie("UserID", $YourIDM, time() + (7 * 86400), $cbasedir, $cookieDomain, 1);
setcookie("SessPass", $NewPassword, time() + (7 * 86400), $cbasedir, $cookieDomain, 1); }
if($cookieSecure===false) {
setcookie("MemberName", $YourNameM, time() + (7 * 86400), $cbasedir, $cookieDomain, 0);
setcookie("UserID", $YourIDM, time() + (7 * 86400), $cbasedir, $cookieDomain, 0);
setcookie("SessPass", $NewPassword, time() + (7 * 86400), $cbasedir, $cookieDomain, 0); } } } }
} } if($numlog<=0) {
//echo "Password was not right or user not found!! <_< ";
} ?>
<?php if($passright===true&&$BanError!="yes") {
redirect("refresh",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false),"3"); ?>
<tr>
	<td><span class="TableMessage">
	<br />Welcome to the Board <?php echo $_SESSION['MemberName']; ?>. ^_^<br />
	Click <a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">here</a> to continue to board.<br />&#160;
	</span><br /></td>
</tr>
<?php } if($passright===false||$BanError=="yes"||$numlog<=0) { ?>
<tr>
	<td><span class="TableMessage">
	<br />Password was not right or user not found or user is banned!! &lt;_&lt;<br />
	Click <a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$exqstr['member'],$prexqstr['member']); ?>">here</a> to try again.<br />&#160;
	</span><br /></td>
</tr>
<?php } } ?>
</table>
</td></tr>
<tr class="TableRow4">
<td class="TableColumn4">&#160;</td>
</tr>
</table></div>
<?php } } if($_GET['act']=="signup") { 
$membertitle = " ".$ThemeSet['TitleDivider']." Signing up"; 
if($_SESSION['UserID']!=0&&$_SESSION['UserID']!=null) { 
redirect("location",$rbasedir.url_maker($exfile['member'],$Settings['file_ext'],"act=logout",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($_SESSION['UserID']==0||$_SESSION['UserID']==null) {
if(isset($_SESSION['OldViewingPage'])) { $_SESSION['AncientViewingPage'] = $_SESSION['OldViewingPage']; } else { $_SESSION['AncientViewingPage'] = url_maker(null,"no+ext","act=view","&","=",$prexqstr['index'],$exqstr['index']); }
if(isset($_SESSION['OldViewingFile'])) { $_SESSION['AncientViewingFile'] = $_SESSION['OldViewingFile']; } else { 
	 if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
	    $_SESSION['AncientViewingFile'] = $exfile['index'].$Settings['file_ext']; }
	 if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
	    $_SESSION['AncientViewingFile'] = $exfile['index']; } }
if(isset($_SESSION['OldPreViewingTitle'])) { $_SESSION['AncientPreViewingTitle'] = $_SESSION['OldPreViewingTitle']; } else { $_SESSION['AncientPreViewingTitle'] = "Viewing"; }
if(isset($_SESSION['OldViewingTitle'])) { $_SESSION['AncientViewingTitle'] = $_SESSION['OldViewingTitle']; } else { $_SESSION['AncientViewingTitle'] = "Board index"; }
if(isset($_SESSION['OldExtraData'])) { $_SESSION['AncientExtraData'] = $_SESSION['OldExtraData']; } else { $_SESSION['AncientExtraData'] = "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;"; }
if(isset($_SESSION['ViewingPage'])) { $_SESSION['OldViewingPage'] = $_SESSION['ViewingPage']; } else { $_SESSION['OldViewingPage'] = url_maker(null,"no+ext","act=view","&","=",$prexqstr['index'],$exqstr['index']); }
if(isset($_SESSION['ViewingFile'])) { $_SESSION['OldViewingFile'] = $_SESSION['ViewingFile']; } else { 
	 if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
	    $_SESSION['OldViewingFile'] = $exfile['index'].$Settings['file_ext']; }
	 if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
	    $_SESSION['OldViewingFile'] = $exfile['index']; } }
if(isset($_SESSION['PreViewingTitle'])) { $_SESSION['OldPreViewingTitle'] = $_SESSION['PreViewingTitle']; } else { $_SESSION['OldPreViewingTitle'] = "Viewing"; }
if(isset($_SESSION['ViewingTitle'])) { $_SESSION['OldViewingTitle'] = $_SESSION['ViewingTitle']; } else { $_SESSION['OldViewingTitle'] = "Board index"; }
if(isset($_SESSION['ExtraData'])) { $_SESSION['OldExtraData'] = $_SESSION['ExtraData']; } else { $_SESSION['OldExtraData'] = "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;"; }
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=signup","&","=",$prexqstr['member'],$exqstr['member']);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['member'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['member']; }
$_SESSION['PreViewingTitle'] = "Act: ";
$_SESSION['ViewingTitle'] = "Signing up";
$_SESSION['ExtraData'] = "currentact:".$_GET['act']."; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;";
$UFID = rand_uuid("rand");
$_SESSION['UserFormID'] = $UFID;
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=signup",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Signup</a></div>
<div class="DivNavLinks">&#160;</div>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=signup",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Register</a>
</span></div>
<?php } ?>
<table class="Table1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1">
<td class="TableColumn1"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=signup",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Register</a>
</span></td>
</tr><?php } ?>
<tr class="TableRow2">
<th class="TableColumn2" style="width: 100%; text-align: left;">&#160;Inert your user info: </th>
</tr>
<tr class="TableRow3">
<td class="TableColumn3">
<form style="display: inline;" method="post" action="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=makemember",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 30%;"><label class="TextBoxLabel" for="Name">Insert a User Name:</label></td>
	<?php if(!isset($_SESSION['GuestName'])) { ?>
	<td style="width: 70%;"><input maxlength="24" type="text" class="TextBox" name="Name" size="20" id="Name" /></td>
	<?php } if(isset($_SESSION['GuestName'])) { ?>
	<td style="width: 70%;"><input maxlength="24" type="text" class="TextBox" name="Name" size="20" id="Name" value="<?php echo $_SESSION['GuestName']; ?>" /></td>
	<?php } ?>
</tr><tr style="text-align: left;">
	<td style="width: 30%;"><label class="TextBoxLabel" for="Handle">Insert a User Handle</label></td>
	<td style="width: 70%;"><input maxlength="24" type="text" class="TextBox" name="Handle" pattern="[a-zA-Z0-9_]{3,20}" size="20" id="Handle" /></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="Password">Insert a Password:</label></td>
	<td style="width: 70%;"><input maxlength="30" type="password" class="TextBox" name="Password" size="20" id="Password" /></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="RePassword">ReInsert a Password:</label></td>
	<td style="width: 70%;"><input maxlength="30" type="password" class="TextBox" name="RePassword" size="20" id="RePassword" /></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="Email">Insert Your Email:</label></td>
	<td style="width: 70%;"><input type="email" class="TextBox" name="Email" size="20" id="Email" /></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="YourOffSet">Your TimeZone:</label></td>
 <td style="width: 70%;">
    <select id="YourOffSet" name="YourOffSet" class="TextBox">
        <?php
        // List of all region labels
        $regions = ['africa', 'america', 'antarctica', 'arctic', 'asia', 'atlantic', 'australia', 'europe', 'indian', 'pacific', 'etcetera'];
        
        // Generate optgroups and options for each region
        foreach ($regions as $region) {
            echo '<optgroup label="' . ucfirst($region) . '">' . "\n";
            echo generateOptions($region, $zonelist, $gettzinfofromjs); // Using $gettzinfofromjs for selected timezone
            echo '</optgroup>' . "\n";
        }
        ?>
    </select>
</td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="YourGender">Your Gender:</label></td>
	<td style="width: 70%;"><select id="YourGender" name="YourGender" class="TextBox">
<option value="Male">Male</option>
<option value="Female">Female</option>
<option value="Unknow">Unknown</option>
</select></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="Website">Insert your Website:</label></td>
	<td style="width: 70%;"><input type="url" class="TextBox" name="Website" size="20" value="" id="Website" /></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="Avatar">Insert a URL for Avatar:</label></td>
	<td style="width: 70%;"><input type="url" class="TextBox" name="Avatar" size="20" value="" id="Avatar" /></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" title="Store userinfo as a cookie so you dont need to login again." for="storecookie">Store as cookie?</label></td>
	<td style="width: 70%;"><select id="storecookie" name="storecookie" class="TextBox">
<option value="true">Yes</option>
<option value="false">No</option>
</select></td>
</tr>
</table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<label class="TextBoxLabel" for="TOSBox">TOS - Please read fully and check 'I agree' box ONLY if you agree to terms</label><br />
<textarea rows="10" cols="58" id="TOSBox" name="TOSBox" class="TextBox" readonly="readonly" accesskey="T"><?php 
	echo file_get_contents("TOS");	?></textarea><br />
<input type="checkbox" class="TextBox" name="TOS" value="Agree" id="TOS" /><label class="TextBoxLabel" for="TOS">I Agree</label>
<?php if($Settings['use_captcha']!="on") { ?><br />
<?php } if($Settings['use_captcha']=="on") { ?>
</td></tr>
<tr style="text-align: left;">
<td style="width: 100%;">
<label class="TextBoxLabel" for="signcode"><img src="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=MkCaptcha",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>" alt="CAPTCHA Code" title="CAPTCHA Code" /></label><br />
<input maxlength="25" type="text" class="TextBox" name="signcode" size="20" id="signcode" value="Enter SignCode" /><br />--><?php } ?>
<input type="hidden" style="display: none;" name="act" value="makemembers" />
<input type="hidden" style="display: none;" name="fid" value="<?php echo $UFID; ?>" />
<input type="hidden" style="display: none;" name="ubid" value="<?php echo $Settings['BoardUUID']; ?>" />
<?php if(isset($_GET['referrerid'])&&is_numeric($_GET['referrerid'])) { ?>
<input type="hidden" style="display: none;" name="referrerid" value="<?php echo $_GET['referrerid']; ?>" />
<?php } ?>
<input type="submit" class="Button" value="Sign UP" />
</td></tr>
</table>
</form>
</td>
</tr>
<tr class="TableRow4">
<td class="TableColumn4">&#160;</td>
</tr>
</table></div>
<?php } } if($_GET['act']=="makemember") {
	if($_POST['act']=="makemembers") {
if($_SESSION['UserID']!=0&&$_SESSION['UserID']!=null) { 
redirect("location",$rbasedir.url_maker($exfile['member'],$Settings['file_ext'],"act=logout",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($_SESSION['UserID']==0||$_SESSION['UserID']==null) {
if(isset($_SESSION['OldViewingPage'])) { $_SESSION['AncientViewingPage'] = $_SESSION['OldViewingPage']; } else { $_SESSION['AncientViewingPage'] = url_maker(null,"no+ext","act=view","&","=",$prexqstr['index'],$exqstr['index']); }
if(isset($_SESSION['OldViewingFile'])) { $_SESSION['AncientViewingFile'] = $_SESSION['OldViewingFile']; } else { 
	 if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
	    $_SESSION['AncientViewingFile'] = $exfile['index'].$Settings['file_ext']; }
	 if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
	    $_SESSION['AncientViewingFile'] = $exfile['index']; } }
if(isset($_SESSION['OldPreViewingTitle'])) { $_SESSION['AncientPreViewingTitle'] = $_SESSION['OldPreViewingTitle']; } else { $_SESSION['AncientPreViewingTitle'] = "Viewing"; }
if(isset($_SESSION['OldViewingTitle'])) { $_SESSION['AncientViewingTitle'] = $_SESSION['OldViewingTitle']; } else { $_SESSION['AncientViewingTitle'] = "Board index"; }
if(isset($_SESSION['OldExtraData'])) { $_SESSION['AncientExtraData'] = $_SESSION['OldExtraData']; } else { $_SESSION['AncientExtraData'] = "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;"; }
if(isset($_SESSION['ViewingPage'])) { $_SESSION['OldViewingPage'] = $_SESSION['ViewingPage']; } else { $_SESSION['OldViewingPage'] = url_maker(null,"no+ext","act=view","&","=",$prexqstr['index'],$exqstr['index']); }
if(isset($_SESSION['ViewingFile'])) { $_SESSION['OldViewingFile'] = $_SESSION['ViewingFile']; } else { 
	 if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
	    $_SESSION['OldViewingFile'] = $exfile['index'].$Settings['file_ext']; }
	 if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
	    $_SESSION['OldViewingFile'] = $exfile['index']; } }
if(isset($_SESSION['PreViewingTitle'])) { $_SESSION['OldPreViewingTitle'] = $_SESSION['PreViewingTitle']; } else { $_SESSION['OldPreViewingTitle'] = "Viewing"; }
if(isset($_SESSION['ViewingTitle'])) { $_SESSION['OldViewingTitle'] = $_SESSION['ViewingTitle']; } else { $_SESSION['OldViewingTitle'] = "Board index"; }
if(isset($_SESSION['ExtraData'])) { $_SESSION['OldExtraData'] = $_SESSION['ExtraData']; } else { $_SESSION['OldExtraData'] = "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;"; }
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=signup","&","=",$prexqstr['member'],$exqstr['member']);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['member'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['member']; }
$_SESSION['PreViewingTitle'] = "Act: ";
$_SESSION['ViewingTitle'] = "Signing up";
$_SESSION['ExtraData'] = "currentact:".$_GET['act']."; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;";
$membertitle = " ".$ThemeSet['TitleDivider']." Signing up";
$REFERERurl = parse_url($_SERVER['HTTP_REFERER']);
$URL['REFERER'] = $REFERERurl['host'];
$URL['HOST'] = $_SERVER['SERVER_NAME'];
$REFERERurl = null;
if(!isset($_POST['username'])) { $_POST['username'] = null; }
if(!isset($_POST['TOS'])) { $_POST['TOS'] = null; }
if($Settings['use_captcha']=="on") {
require($SettDir['inc']."captcha.php"); }
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=signup",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Signup</a></div>
<div class="DivNavLinks">&#160;</div>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
&#160;<a href="<?php echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=signup",$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); ?>">Register</a></span></div>
<?php } ?>
<table class="Table1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1">
<td class="TableColumn1"><span style="text-align: left;">
&#160;<a href="<?php echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=signup",$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); ?>">Register</a></span></td>
</tr><?php } ?>
<tr class="TableRow2">
<th class="TableColumn2" style="width: 100%; text-align: left;">&#160;Signup Message: </th>
</tr>
<tr class="TableRow3">
<td class="TableColumn3">
<table style="width: 100%; height: 25%; text-align: center;">
<?php 
$Handle = stripcslashes(htmlspecialchars($_POST['Handle'], ENT_QUOTES, $Settings['charset']));
//$Handle = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $Handle);
$Handle = remove_spaces($Handle);
// Check if the username matches the pattern
if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $Handle)) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />Your handel is invalid.<br />
	</span>&#160;</td>
</tr>
<?php } if (pre_strlen($Handle)>"20") { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />Your handel is too big.<br />
	</span>&#160;</td>
</tr>
<?php } if (pre_strlen($_POST['Password'])>"60") { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />Your password is too big.<br />
	</span>&#160;</td>
</tr>
<?php } if (!filter_var($_POST['Email'], FILTER_VALIDATE_EMAIL)) { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your email is not a valid email address.<br />
	</span>&#160;</td>
</tr>
<?php } if($_POST['Website']=="") { $_POST['Website'] = "http://"; }
if (!filter_var($_POST['Website'], FILTER_VALIDATE_URL)&&$_POST['Website']!="http://"&&$_POST['Website']!="https://") { var_dump($_POST['Website']); $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your website url is not a valid web url.<br />
	</span>&#160;</td>
</tr>
<?php } if($_POST['Avatar']=="") { $_POST['Avatar'] = "http://"; }
if (!filter_var($_POST['Avatar'], FILTER_VALIDATE_URL)&&$_POST['Avatar']!="http://"&&$_POST['Avatar']!="https://") { var_dump($_POST['Avatar']); $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your avatar url is not a valid web url.<br />
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
<?php } if (pre_strlen($_POST['username'])>"30") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your user name is too big.<br />
	</span>&#160;</td>
</tr>
<?php } if ($_POST['Password']!=$_POST['RePassword']) { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your passwords did not match.<br />
	</span>&#160;</td>
</tr>
<?php } /*if($Settings['use_captcha']=="on") {
if (PhpCaptcha::Validate($_POST['signcode'])) {
//echo 'Valid code entered';
} else { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />Invalid code entered<br />
	</span>&#160;</td>
</tr>
<?php } }*/ if ($Settings['TestReferer']=="on") {
	if ($URL['HOST']!=$URL['REFERER']) { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Sorry the referering url dose not match our host name.<br />
	</span>&#160;</td>
</tr>
<?php } }
if($_POST['Website']=="") { $_POST['Website'] = "http://"; }
if($_POST['Avatar']=="") { $_POST['Avatar'] = "http://"; }
$Name = stripcslashes(htmlspecialchars($_POST['Name'], ENT_QUOTES, $Settings['charset']));
//$Name = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $Name);
$Name = remove_spaces($Name);
$lonewolfnm=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."restrictedwords\" WHERE \"RestrictedUserName\"='yes'", null), $SQLStat);
$lonewolfqy=sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."restrictedwords\" WHERE \"RestrictedUserName\"='yes'", null);
$lonewolfrt=sql_query($lonewolfqy,$SQLStat);
$lonewolfs=0; $RMatches = null;
while ($lonewolfs < $lonewolfnm) {
$lonewolfrt_array = sql_fetch_assoc($lonewolfrt);
$RWord=$lonewolfrt_array['Word'];
$RCaseInsensitive=$lonewolfrt_array['CaseInsensitive'];
if($RCaseInsensitive=="on") { $RCaseInsensitive = "yes"; }
if($RCaseInsensitive=="off") { $RCaseInsensitive = "no"; }
if($RCaseInsensitive!="yes"||$RCaseInsensitive!="no") { $RCaseInsensitive = "no"; }
$RWholeWord=$lonewolfrt_array['WholeWord'];
if($RWholeWord=="on") { $RWholeWord = "yes"; }
if($RWholeWord=="off") { $RWholeWord = "no"; }
if($RWholeWord!="yes"||$RWholeWord!="no") { $RWholeWord = "no"; }
$RWord = preg_quote($RWord, "/");
if($RCaseInsensitive!="yes"&&$RWholeWord=="yes") {
$RMatches = preg_match("/\b(".$RWord.")\b/", $Name);
	if($RMatches==true) { break 1; } }
if($RCaseInsensitive=="yes"&&$RWholeWord=="yes") {
$RMatches = preg_match("/\b(".$RWord.")\b/i", $Name);
	if($RMatches==true) { break 1; } }
if($RCaseInsensitive!="yes"&&$RWholeWord!="yes") {
$RMatches = preg_match("/".$RWord."/", $Name);
	if($RMatches==true) { break 1; } }
if($RCaseInsensitive=="yes"&&$RWholeWord!="yes") {
$RMatches = preg_match("/".$RWord."/i", $Name);
	if($RMatches==true) { break 1; } }
if($RCaseInsensitive!="yes"&&$RWholeWord=="yes") {
$RMatches = preg_match("/\b(".$RWord.")\b/", $Handle);
	if($RMatches==true) { break 1; } }
if($RCaseInsensitive=="yes"&&$RWholeWord=="yes") {
$RMatches = preg_match("/\b(".$RWord.")\b/i", $Handle);
	if($RMatches==true) { break 1; } }
if($RCaseInsensitive!="yes"&&$RWholeWord!="yes") {
$RMatches = preg_match("/".$RWord."/", $Handle);
	if($RMatches==true) { break 1; } }
if($RCaseInsensitive=="yes"&&$RWholeWord!="yes") {
$RMatches = preg_match("/".$RWord."/i", $Handle);
	if($RMatches==true) { break 1; } }
++$lonewolfs; } sql_free_result($lonewolfrt);
$email_check = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"Email\"='%s'", array($_POST['Email'])), $SQLStat); 
$username_check = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"Name\"='%s'", array($Name)), $SQLStat);
$handle_check = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"Handle\"='%s'", array($Handle)), $SQLStat);
/*$sql_email_check = sql_query(sql_pre_query("SELECT \"Email\" FROM \"".$Settings['sqltable']."members\" WHERE \"Email\"='%s'", array($_POST['Email'])),$SQLStat);
sql_free_result($sql_email_check);
$sql_username_check = sql_query(sql_pre_query("SELECT \"Name\" FROM \"".$Settings['sqltable']."members\" WHERE \"Name\"='%s'", array($Name)),$SQLStat);
sql_free_result($sql_username_check);
$sql_handle_check = sql_query(sql_pre_query("SELECT \"Name\" FROM \"".$Settings['sqltable']."members\" WHERE \"Handle\"='%s'", array($Handle)),$SQLStat);
sql_free_result($sql_handle_check);*/
if ($_POST['TOS']!="Agree") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to  agree to the tos.<br />
	</span>&#160;</td>
</tr>
<?php } if ($_POST['Name']==null) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a name.<br />
	</span>&#160;</td>
</tr>
<?php } if ($_POST['Name']=="ShowMe") { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a name.<br />
	</span>&#160;</td>
</tr>
<?php } if ($_POST['Password']==null) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a password.<br />
	</span>&#160;</td>
</tr>
<?php } if (!filter_var($_POST['Email'], FILTER_VALIDATE_EMAIL)) { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your email is not a valid email address.<br />
	</span>&#160;</td>
</tr>
<?php } if ($_POST['Email']==null) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a email.<br />
	</span>&#160;</td>
</tr>
<?php } if($email_check > 0) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />Email address is already used.<br />
	</span>&#160;</td>
</tr>
<?php } if($username_check > 0) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />User Name is already used.<br />
	</span>&#160;</td>
</tr>
<?php } if($handle_check > 0) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />Handle is already used.<br />
	</span>&#160;</td>
</tr>
<?php } if($RMatches==true) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />This User Name is restricted to use.<br />
	</span>&#160;</td>
</tr>
<?php } if ($Error=="Yes") {
redirect("refresh",$rbasedir.url_maker($exfile['member'],$Settings['file_ext'],"act=signup",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],FALSE),"4"); ?>
<tr>
	<td><span class="TableMessage">
	<br />Click <a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=signup",$Settings['qstr'],$Settings['qsep'],$exqstr['member'],$prexqstr['member']); ?>">here</a> to try again.<br />&#160;
	</span><br /></td>
</tr>
<?php } if ($Error!="Yes") {
$_POST['UserIP'] = $_SERVER['REMOTE_ADDR'];
$_POST['Group'] = $Settings['MemberGroup'];
$_POST['Joined'] = $utccurtime->getTimestamp(); $_POST['LastActive'] = $utccurtime->getTimestamp();
$_POST['Signature'] = ""; $_POST['Interests'] = "";
$_POST['Title'] = ""; $_POST['PostCount'] = "0";
if(!isset($Settings['AdminValidate'])) { $Settings['AdminValidate'] = "off"; }
if($Settings['AdminValidate']=="on"||$Settings['AdminValidate']!="off")
{ $ValidateStats="no"; $yourgroupname=$Settings['ValidateGroup']; }
if($Settings['AdminValidate']=="off"||$Settings['AdminValidate']!="on")
{ $ValidateStats="yes"; $yourgroupname=$Settings['MemberGroup']; }
$gmidquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"Name\"='%s' LIMIT 1", array($yourgroupname));
$gmidresult=sql_query($gmidquery,$SQLStat);
$gmidresult_array = sql_fetch_assoc($gmidresult);
$yourgroup=$gmidresult_array['id'];
$HideMe = "no"; $HashSalt = salt_hmac();
if($Settings['use_hashtype']=="md2") { $iDBHash = "iDBH2";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"md2"); }
if($Settings['use_hashtype']=="md4") { $iDBHash = "iDBH4";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"md4"); }
if($Settings['use_hashtype']=="md5") { $iDBHash = "iDBH5";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"md5"); }
if($Settings['use_hashtype']=="sha1") { $iDBHash = "iDBH";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"sha1"); }
if($Settings['use_hashtype']=="sha224") { $iDBHash = "iDBH224";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"sha224"); }
if($Settings['use_hashtype']=="sha256") { $iDBHash = "iDBH256";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"sha256"); }
if($Settings['use_hashtype']=="sha384") { $iDBHash = "iDBH384";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"sha384"); }
if($Settings['use_hashtype']=="sha512") { $iDBHash = "iDBH512";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"sha512"); }
if($Settings['use_hashtype']=="sha3-224") { $iDBHash = "iDBH3224";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"sha3-224"); }
if($Settings['use_hashtype']=="sha3-256") { $iDBHash = "iDBH3256";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"sha3-256"); }
if($Settings['use_hashtype']=="sha3-384") { $iDBHash = "iDBH3384";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"sha3-384"); }
if($Settings['use_hashtype']=="sha3-512") { $iDBHash = "iDBH3512";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"sha3-512"); }
if($Settings['use_hashtype']=="ripemd128") { $iDBHash = "iDBHRMD128";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"ripemd128"); }
if($Settings['use_hashtype']=="ripemd160") { $iDBHash = "iDBHRMD160";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"ripemd160"); }
if($Settings['use_hashtype']=="ripemd256") { $iDBHash = "iDBHRMD256";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"ripemd256"); }
if($Settings['use_hashtype']=="ripemd320") { $iDBHash = "iDBHRMD320";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"ripemd320"); }
if($Settings['use_hashtype']=="bcrypt") { $iDBHash = "iDBCRYPT";
$NewPassword = neo_b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"bcrypt"); }
if($Settings['use_hashtype']=="argon2i") { $iDBHash = "iDBARGON2I";
$NewPassword = neo_b64e_hmac($_POST['Password'],$JoinedPass,$NewHashSalt,"argon2i"); }
if($Settings['use_hashtype']=="argon2id") { $iDBHash = "iDBARGON2ID";
$NewPassword = neo_b64e_hmac($_POST['Password'],$JoinedPass,$NewHashSalt,"argon2id"); }
$_GET['YourPost'] = $_POST['Signature'];
//require( './'.$SettDir['misc'].'HTMLTags.php');
$_GET['YourPost'] = htmlspecialchars($_GET['YourPost'], ENT_QUOTES, $Settings['charset']);
//$_GET['YourPost'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_GET['YourPost']);
$NewSignature = $_GET['YourPost'];
$_GET['YourPost'] = preg_replace("/\t+/"," ",$_GET['YourPost']);
$_GET['YourPost'] = preg_replace("/\s\s+/"," ",$_GET['YourPost']);
$_GET['YourPost'] = remove_bad_entities($_GET['YourPost']);
$Avatar = stripcslashes(htmlspecialchars($_POST['Avatar'], ENT_QUOTES, $Settings['charset']));
//$Avatar = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $Avatar);
$Avatar = remove_spaces($Avatar);
$Website = stripcslashes(htmlspecialchars($_POST['Website'], ENT_QUOTES, $Settings['charset']));
//$Website = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $Website);
$Website = remove_spaces($Website);
$_POST['Interests'] = remove_spaces($_POST['Interests']);
$_POST['Title'] = remove_spaces($_POST['Title']);
$_POST['Email'] = remove_spaces($_POST['Email']);
$query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."members\" (\"Name\", \"Handle\", \"UserPassword\", \"HashType\", \"Email\", \"GroupID\", \"LevelID\", \"RankID\", \"Validated\", \"HiddenMember\", \"WarnLevel\", \"Interests\", \"Title\", \"Joined\", \"LastActive\", \"LastLogin\", \"LastPostTime\", \"BanTime\", \"BirthDay\", \"BirthMonth\", \"BirthYear\", \"Signature\", \"Notes\", \"Bio\", \"Avatar\", \"AvatarSize\", \"Website\", \"Location\", \"Gender\", \"PostCount\", \"Karma\", \"KarmaUpdate\", \"RepliesPerPage\", \"TopicsPerPage\", \"MessagesPerPage\", \"TimeZone\", \"DateFormat\", \"TimeFormat\", \"UseTheme\", \"IgnoreSignitures\", \"IgnoreAdvatars\", \"IgnoreUsers\", \"IP\", \"Salt\") VALUES\n". 
"('%s', '%s', '%s', '%s', '%s', '%s', 0, 0, '%s', '%s', %i, '%s', '%s', %i, %i, %i, '0', '0', '0', '0', '0', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %i, 0, 0, 10, 10, 10, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", array($Name,$Handle,$NewPassword,$iDBHash,$_POST['Email'],$yourgroup,$ValidateStats,$HideMe,"0",$_POST['Interests'],$_POST['Title'],$_POST['Joined'],$_POST['LastActive'],$_POST['LastActive'],$NewSignature,'Your Notes','',$Avatar,"100x100",$Website,'',$_POST['YourGender'],$_POST['PostCount'],$_POST['YourOffSet'],$Settings['idb_date_format'],$Settings['idb_time_format'],$Settings['DefaultTheme'],$_POST['UserIP'],'','','',$HashSalt));
sql_query($query,$SQLStat);
$yourid = sql_get_next_id($Settings['sqltable'],"members",$SQLStat);
$idnum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"Name\"='%s' AND \"UserPassword\"='%s' LIMIT 1", array($Name,$NewPassword)), $SQLStat);
$idquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"Name\"='%s' AND \"UserPassword\"='%s' LIMIT 1", array($Name,$NewPassword));
$idresult=sql_query($idquery,$SQLStat);
$idcheck = $yourid;
$idncheck = 0;
if($idnum>=1) {
$idresult_array = sql_fetch_assoc($idresult);
$idncheck = $idresult_array['id']; 
$idncheck = intval($idncheck); }
sql_free_result($idresult);
if($yourid!=$idncheck) { $yourid = $idncheck; }
$query = sql_pre_query("INSERT INTO \"".$Settings['sqltable']."mempermissions\" (\"id\", \"PermissionID\", \"CanViewBoard\", \"CanViewOffLine\", \"CanEditProfile\", \"CanAddEvents\", \"CanPM\", \"CanSearch\", \"CanExecPHP\", \"CanDoHTML\", \"CanUseBBTags\", \"CanModForum\", \"CanViewIPAddress\", \"CanViewUserAgent\", \"CanViewAnonymous\", \"FloodControl\", \"SearchFlood\", \"HasModCP\", \"HasAdminCP\", \"ViewDBInfo\") VALUES\n". 
"(%i, %i, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %i, %i, '%s', '%s', '%s')", array($yourid, 0, "group", "group", "group", "group", "group", "group", "group", "group", "group", "group", "group", "group", "group", -1, -1, "group", "group", "group"));
sql_query($query,$SQLStat);
if(isset($_POST['referrerid'])&&is_numeric($_POST['referrerid'])) {
	$rfidnum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($_POST['referrerid'])), $SQLStat);
	$rfidquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($_POST['referrerid']));
	$rfidresult=sql_query($rfidquery,$SQLStat);
	if($rfidnum>=1) {
		$rfidresult_array = sql_fetch_assoc($rfidresult);
		$rfidKarma=$rfidresult_array['Karma'];
		sql_free_result($rfidresult);
		if(!is_numeric($rfidKarma)) { $rfidKarma = 0; }
		$rfidKarma = $rfidKarma + 1;
		$querykup = sql_pre_query("UPDATE \"".$Settings['sqltable']."members\" SET \"Karma\"=%i WHERE \"id\"=%i", array($rfidKarma,$_POST['referrerid']));
		sql_query($querykup,$SQLStat); } }
$numlogr=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"Name\"='%s' AND \"UserPassword\"='%s' LIMIT 1", array($Name,$NewPassword)), $SQLStat);
$querylogr = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"Name\"='%s' AND \"UserPassword\"='%s' LIMIT 1", array($Name,$NewPassword));
$resultlogr=sql_query($querylogr,$SQLStat);
if($numlogr>=1) {
$resultlog_array = sql_fetch_assoc($resultlog);
$YourIDMr=$resultlog_array['id'];
$YourNameMr=$resultlog_array['Name'];
$YourGroupMr=$resultlog_array['GroupID'];
$YourGroupIDMr=$YourGroupMr;
$gquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($YourGroupMr));
$gresult=sql_query($gquery,$SQLStat);
$gresult_array = sql_fetch_assoc($gresult);
$YourGroupMr=$gresult_array['Name'];
sql_free_result($gresult);
$resultlogr_array = sql_fetch_assoc($resultlogr);
$YourTimeZoneMr=$resultlogr_array['TimeZone']; }
sql_free_result($resultlogr);
session_regenerate_id(true);
$_SESSION['Loggedin']=true;
$_SESSION['MemberName']=$YourNameMr;
$_SESSION['UserID']=$YourIDMr;
$_SESSION['UserIP']=$_SERVER['REMOTE_ADDR'];
$_SESSION['UserTimeZone']=$YourTimeZoneMr;
$usertz = new DateTimeZone($_SESSION['UserTimeZone']);
$usercurtime->setTimestamp($defcurtime->getTimestamp());
$usercurtime->setTimezone($usertz);
$_SESSION['UserGroup']=$YourGroupMr;
$_SESSION['UserGroupID']=$YourGroupIDMr;
$_SESSION['UserPass']=$NewPassword;
$_SESSION['DBName']=$Settings['sqldb'];
if($_POST['storecookie']=="true") {
if($cookieDomain==null) {
setcookie("MemberName", $YourNameMr, time() + (7 * 86400), $cbasedir);
setcookie("UserID", $YourIDMr, time() + (7 * 86400), $cbasedir);
setcookie("SessPass", $NewPassword, time() + (7 * 86400), $cbasedir); }
if($cookieDomain!=null) {
if($cookieSecure===true) {
setcookie("MemberName", $YourNameMr, time() + (7 * 86400), $cbasedir, $cookieDomain, 1);
setcookie("UserID", $YourIDMr, time() + (7 * 86400), $cbasedir, $cookieDomain, 1);
setcookie("SessPass", $NewPassword, time() + (7 * 86400), $cbasedir, $cookieDomain, 1); }
if($cookieSecure===false) {
setcookie("MemberName", $YourNameMr, time() + (7 * 86400), $cbasedir, $cookieDomain, 0);
setcookie("UserID", $YourIDMr, time() + (7 * 86400), $cbasedir, $cookieDomain, 0);
setcookie("SessPass", $NewPassword, time() + (7 * 86400), $cbasedir, $cookieDomain, 0); } } }
redirect("refresh",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],FALSE),"3");
?>
<tr>
	<td><span class="TableMessage">
	<br />Welcome to the Board <?php echo $_SESSION['MemberName']; ?>. ^_^<br />
	Click <a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">here</a> to continue to board.<?php echo "\n"; 
	if($Settings['AdminValidate']=="on"||$Settings['AdminValidate']!="off") {
	echo "<br />The admin has to validate your account befoure you can post.\n";
	echo "<br />The admin has been notified of your registration.\n"; } ?>
	<br />&#160;
	</span><br /></td>
</tr>
<?php } ?>
</table>
</td></tr>
<tr class="TableRow4">
<td class="TableColumn4">&#160;</td>
</tr>
</table></div>
<?php } } }
if($pagenum<=1) { ?>
<div class="DivMembers">&#160;</div>
<?php } ?>
