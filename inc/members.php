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

    $FileInfo: members.php - Last Update: 11/14/2009 SVN 348 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="members.php"||$File3Name=="/members.php") {
	require('index.php');
	exit(); }
$pagenum = null;
if(!is_numeric($_GET['id'])) { $_GET['id'] = null; }
if(!is_numeric($_GET['page'])) { $_GET['page'] = 1; }
if($_GET['act']=="list") {
$orderlist = null;
$orderlist = "order by `ID` asc";
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
if($_GET['orderby']=="id") { $orderlist = "order by `ID`"; }
if($_GET['orderby']=="name") { $orderlist = "order by `Name`"; }
if($_GET['orderby']=="joined") { $orderlist = "order by `Joined`"; }
if($_GET['orderby']=="active") { $orderlist = "order by `LastActive`"; }
if($_GET['orderby']=="post") { $orderlist = "order by `PostCount`"; }
if($_GET['orderby']=="posts") { $orderlist = "order by `PostCount`"; }
if($_GET['orderby']=="karma") { $orderlist = "order by `Karma`"; }
if($_GET['orderby']=="offset") { $orderlist = "order by `TimeZone`"; } }
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
$ggquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `Name`='%s'", array($Settings['GuestGroup']));
$ggresult=exec_query($ggquery);
$GGroup=mysql_result($ggresult,0,"id");
@mysql_free_result($ggresult);
//Get SQL LIMIT Number
$nums = $_GET['page'] * $Settings['max_memlist'];
$PageLimit = $nums - $Settings['max_memlist'];
if($PageLimit<0) { $PageLimit = 0; }
$i=0;
if($_GET['groupid']==null) {
$query = query("SELECT SQL_CALC_FOUND_ROWS * FROM `".$Settings['sqltable']."members` WHERE `GroupID`<>%i AND `id`>=0 AND `HiddenMember`='no' ".$orderlist." LIMIT %i,%i", array($GGroup,$PageLimit,$Settings['max_memlist'])); }
if($_GET['groupid']!=null) {
$query = query("SELECT SQL_CALC_FOUND_ROWS * FROM `".$Settings['sqltable']."members` WHERE `GroupID`=%i AND `GroupID`<>%i AND `id`>=0 ".$orderlist." LIMIT %i,%i", array($_GET['groupid'],$GGroup,$PageLimit,$Settings['max_memlist'])); }
$rnquery = query("SELECT FOUND_ROWS();", array(null));
$result=exec_query($query);
$rnresult=exec_query($rnquery);
$NumberMembers = mysql_result($rnresult,0);
@mysql_free_result($rnresult);
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page=".$_GET['page'],"&","=",$prexqstr['member'],$exqstr['member']);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['member'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['member']; }
$_SESSION['PreViewingTitle'] = "Viewing";
$_SESSION['ViewingTitle'] = "Member List";
if($NumberMembers==null) { 
	$NumberMembers = 0; }
$num = $NumberMembers;
//Start MemberList Page Code
if(!isset($Settings['max_memlist'])) { $Settings['max_memlist'] = 10; }
if($_GET['page']==null) { $_GET['page'] = 1; } 
if($_GET['page']<=0) { $_GET['page'] = 1; }
$nums = $_GET['page'] * $Settings['max_memlist'];
if($nums>$num) { $nums = $num; }
$numz = $nums - $Settings['max_memlist'];
if($numz<=0) { $numz = 0; }
//$i=$numz;
if($nums<$num) { $nextpage = $_GET['page'] + 1; }
if($nums>=$num) { $nextpage = $_GET['page']; }
if($numz>=$Settings['max_memlist']) { $backpage = $_GET['page'] - 1; }
if($_GET['page']<=1) { $backpage = 1; }
$pnum = $num; $l = 1; $Pages = null;
while ($pnum>0) {
if($pnum>=$Settings['max_memlist']) { 
	$pnum = $pnum - $Settings['max_memlist']; 
	$Pages[$l] = $l; ++$l; }
if($pnum<$Settings['max_memlist']&&$pnum>0) { 
	$pnum = $pnum - $pnum; 
	$Pages[$l] = $l; ++$l; } }
$nums = $_GET['page'] * $Settings['max_memlist'];
//End MemberList Page Code
$num=mysql_num_rows($result);
//List Page Number Code Start
$pagenum=count($Pages);
if($_GET['page']>$pagenum) {
	$_GET['page'] = $pagenum; }
$pagei=0; $pstring = null;
if($pagenum>1) {
$pstring = "<div class=\"PageList\"><span class=\"pagelink\">".$pagenum." Pages:</span> "; }
if($_GET['page']<4) { $Pagez[0] = null; }
if($_GET['page']>=4) { $Pagez[0] = "First"; }
if($_GET['page']>=3) {
$Pagez[1] = $_GET['page'] - 2; }
if($_GET['page']<3) {
$Pagez[1] = null; }
if($_GET['page']>=2) {
$Pagez[2] = $_GET['page'] - 1; }
if($_GET['page']<2) {
$Pagez[2] = null; }
$Pagez[3] = $_GET['page'];
if($_GET['page']<$pagenum) {
$Pagez[4] = $_GET['page'] + 1; }
if($_GET['page']>=$pagenum) {
$Pagez[4] = null; }
$pagenext = $_GET['page'] + 1;
if($pagenext<$pagenum) {
$Pagez[5] = $_GET['page'] + 2; }
if($pagenext>=$pagenum) {
$Pagez[5] = null; }
if($_GET['page']<$pagenum) { $Pagez[6] = "Last"; }
if($_GET['page']>=$pagenum) { $Pagez[6] = null; }
$pagenumi=count($Pagez);
if($NumberMembers==0) {
$pagenumi = 0;
$pstring = null; }
if($pagenum>1) {
while ($pagei < $pagenumi) {
if($_GET['page']!=1&&$pagei==1) {
$Pback = $_GET['page'] - 1;
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page=".$Pback,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">&lt;</a></span> "; }
if($Pagez[$pagei]!=null&&
   $Pagez[$pagei]!="First"&&
   $Pagez[$pagei]!="Last") {
if($pagei!=3) { 
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page=".$Pagez[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">".$Pagez[$pagei]."</a></span> "; }
if($pagei==3) { 
$pstring = $pstring."<span class=\"pagecurrent\"><a href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page=".$Pagez[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">".$Pagez[$pagei]."</a></span> "; } }
if($Pagez[$pagei]=="First") {
$pstring = $pstring."<span class=\"pagelinklast\"><a href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">&laquo;</a></span> "; }
if($Pagez[$pagei]=="Last") {
$ptestnext = $pagenext + 1;
$paget = $pagei - 1;
$Pnext = $_GET['page'] + 1;
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page=".$Pnext,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">&gt;</a></span> ";
if($ptestnext<$pagenum) {
$pstring = $pstring."<span class=\"pagelinklast\"><a href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page=".$pagenum,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">&raquo;</a></span> "; } }
	++$pagei; } $pstring = $pstring."</div>"; }
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">Board index</a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=list&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Member list</a></div>
<div class="DivNavLinks">&nbsp;</div>
<?php
echo $pstring;
//List Page Number Code end
if($pagenum>1) {
?>
<div class="DivPageLinks">&nbsp;</div>
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
<th class="TableColumn2" style="width: 5%;">ID</th>
<th class="TableColumn2" style="width: 28%;">Name</th>
<th class="TableColumn2" style="width: 10%;">Group</th>
<th class="TableColumn2" style="width: 5%;">Posts</th>
<th class="TableColumn2" style="width: 5%;">Karma</th>
<th class="TableColumn2" style="width: 20%;">Joined</th>
<th class="TableColumn2" style="width: 20%;">Last Active</th>
<th class="TableColumn2" style="width: 7%;">Website</th>
</tr>
<?php
while ($i < $num) {
$MemList['ID']=mysql_result($result,$i,"id");
$MemList['Name']=mysql_result($result,$i,"Name");
$MemList['Email']=mysql_result($result,$i,"Email");
$MemList['GroupID']=mysql_result($result,$i,"GroupID");
$MemList['WarnLevel']=mysql_result($result,$i,"WarnLevel");
$MemList['Interests']=mysql_result($result,$i,"Interests");
$MemList['Title']=mysql_result($result,$i,"Title");
$MemList['Joined']=mysql_result($result,$i,"Joined");
$MemList['Joined']=GMTimeChange("F j Y, g:i a",$MemList['Joined'],$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$MemList['LastActive']=mysql_result($result,$i,"LastActive");
$MemList['LastActive']=GMTimeChange("F j Y, g:i a",$MemList['LastActive'],$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$MemList['Website']=mysql_result($result,$i,"Website");
$MemList['Gender']=mysql_result($result,$i,"Gender");
$MemList['PostCount']=mysql_result($result,$i,"PostCount");
$MemList['Karma']=mysql_result($result,$i,"Karma");
$MemList['TimeZone']=mysql_result($result,$i,"TimeZone");
$MemList['DST']=mysql_result($result,$i,"DST");
$MemList['IP']=mysql_result($result,$i,"IP");
$gquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i LIMIT 1", array($MemList['GroupID']));
$gresult=exec_query($gquery);
$MemList['Group']=mysql_result($gresult,0,"Name");
$GroupNamePrefix=mysql_result($gresult,0,"NamePrefix");
$GroupNameSuffix=mysql_result($gresult,0,"NameSuffix");
@mysql_free_result($gresult);
if(isset($GroupNamePrefix)&&$GroupNamePrefix!=null) {
	$MemList['Name'] = $GroupNamePrefix.$MemList['Name']; }
if(isset($GroupNameSuffix)&&$GroupNameSuffix!=null) {
	$MemList['Name'] = $MemList['Name'].$GroupNameSuffix; }
$membertitle = " ".$ThemeSet['TitleDivider']." Member List";
if($MemList['Group']!=$Settings['GuestGroup']) {
?>
<tr class="TableRow3" id="Member<?php echo $MemList['ID']; ?>">
<td class="TableColumn3" style="text-align: center;"><?php echo $MemList['ID']; ?></td>
<td class="TableColumn3">&nbsp;<a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$MemList['ID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>"><?php echo $MemList['Name']; ?></a></td>
<td class="TableColumn3" style="text-align: center;"><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=list&gid=".$MemList['GroupID']."&page=".$_GET['page'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>"><?php echo $MemList['Group']; ?></a></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $MemList['PostCount']; ?></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $MemList['Karma']; ?></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $MemList['Joined']; ?></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $MemList['LastActive']; ?></td>
<td class="TableColumn3" style="text-align: center;"><a href="<?php echo $MemList['Website']; ?>" onclick="window.open(this.href);return false;">Website</a></td>
</tr>
<?php }
++$i; } @mysql_free_result($result);
?>
<tr id="MemEnd" class="TableRow4">
<td class="TableColumn4" colspan="8">&nbsp;</td>
</tr>
</table></div>
<?php 
if($pagenum>1) {
?>
<div class="DivMembers">&nbsp;</div>
<?php }
echo $pstring;
//List Page Number Code end
if($pagenum>1) {
?>
<div class="DivPageLinks">&nbsp;</div>
<?php } }
if($_GET['act']=="online") {
if($_GET['list']!="all"&&$_GET['list']!="members") {
	$_GET['list'] = "members"; }
//Get SQL LIMIT Number
$nums = $_GET['page'] * $Settings['max_memlist'];
$PageLimit = $nums - $Settings['max_memlist'];
if($PageLimit<0) { $PageLimit = 0; }
$i=0;
$uolcuttime = GMTimeStamp();
$uoltime = $uolcuttime - ini_get("session.gc_maxlifetime");
$query = query("SELECT SQL_CALC_FOUND_ROWS * FROM `".$Settings['sqltable']."sessions` WHERE `expires` >= %i ORDER BY `expires` DESC LIMIT %i,%i", array($uoltime,$PageLimit,$Settings['max_memlist']));
$rnquery = query("SELECT FOUND_ROWS();", array(null));
$result=exec_query($query);
$rnresult=exec_query($rnquery);
$NumberMembers = mysql_result($rnresult,0);
@mysql_free_result($rnresult);
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=online&list=".$_GET['list']."&page=".$_GET['page'],"&","=",$prexqstr['member'],$exqstr['member']);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['member'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['member']; }
$_SESSION['PreViewingTitle'] = "Viewing";
$_SESSION['ViewingTitle'] = "Online Member List";
if($NumberMembers==null) { 
	$NumberMembers = 0; }
$num = $NumberMembers;
//Start MemberList Page Code
if(!isset($Settings['max_memlist'])) { $Settings['max_memlist'] = 10; }
if($_GET['page']==null) { $_GET['page'] = 1; } 
if($_GET['page']<=0) { $_GET['page'] = 1; }
$nums = $_GET['page'] * $Settings['max_memlist'];
if($nums>$num) { $nums = $num; }
$numz = $nums - $Settings['max_memlist'];
if($numz<=0) { $numz = 0; }
//$i=$numz;
if($nums<$num) { $nextpage = $_GET['page'] + 1; }
if($nums>=$num) { $nextpage = $_GET['page']; }
if($numz>=$Settings['max_memlist']) { $backpage = $_GET['page'] - 1; }
if($_GET['page']<=1) { $backpage = 1; }
$pnum = $num; $l = 1; $Pages = null;
while ($pnum>0) {
if($pnum>=$Settings['max_memlist']) { 
	$pnum = $pnum - $Settings['max_memlist']; 
	$Pages[$l] = $l; ++$l; }
if($pnum<$Settings['max_memlist']&&$pnum>0) { 
	$pnum = $pnum - $pnum; 
	$Pages[$l] = $l; ++$l; } }
$nums = $_GET['page'] * $Settings['max_memlist'];
//End MemberList Page Code
$num=mysql_num_rows($result);
//List Page Number Code Start
$pagenum=count($Pages);
if($_GET['page']>$pagenum) {
	$_GET['page'] = $pagenum; }
$pagei=0; $pstring = null;
if($pagenum>1) {
$pstring = "<div class=\"PageList\"><span class=\"pagelink\">".$pagenum." Pages:</span> "; }
if($_GET['page']<4) { $Pagez[0] = null; }
if($_GET['page']>=4) { $Pagez[0] = "First"; }
if($_GET['page']>=3) {
$Pagez[1] = $_GET['page'] - 2; }
if($_GET['page']<3) {
$Pagez[1] = null; }
if($_GET['page']>=2) {
$Pagez[2] = $_GET['page'] - 1; }
if($_GET['page']<2) {
$Pagez[2] = null; }
$Pagez[3] = $_GET['page'];
if($_GET['page']<$pagenum) {
$Pagez[4] = $_GET['page'] + 1; }
if($_GET['page']>=$pagenum) {
$Pagez[4] = null; }
$pagenext = $_GET['page'] + 1;
if($pagenext<$pagenum) {
$Pagez[5] = $_GET['page'] + 2; }
if($pagenext>=$pagenum) {
$Pagez[5] = null; }
if($_GET['page']<$pagenum) { $Pagez[6] = "Last"; }
if($_GET['page']>=$pagenum) { $Pagez[6] = null; }
$pagenumi=count($Pagez);
if($NumberMembers==0) {
$pagenumi = 0;
$pstring = null; }
if($pagenum>1) {
while ($pagei < $pagenumi) {
if($_GET['page']!=1&&$pagei==1) {
$Pback = $_GET['page'] - 1;
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=online&list=".$_GET['list']."&page=".$Pback,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">&lt;</a></span> "; }
if($Pagez[$pagei]!=null&&
   $Pagez[$pagei]!="First"&&
   $Pagez[$pagei]!="Last") {
if($pagei!=3) { 
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=online&list=".$_GET['list']."&page=".$Pagez[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">".$Pagez[$pagei]."</a></span> "; }
if($pagei==3) { 
$pstring = $pstring."<span class=\"pagecurrent\"><a href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=online&list=".$_GET['list']."&page=".$Pagez[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">".$Pagez[$pagei]."</a></span> "; } }
if($Pagez[$pagei]=="First") {
$pstring = $pstring."<span class=\"pagelinklast\"><a href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=online&list=".$_GET['list']."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">&laquo;</a></span> "; }
if($Pagez[$pagei]=="Last") {
$ptestnext = $pagenext + 1;
$paget = $pagei - 1;
$Pnext = $_GET['page'] + 1;
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=online&list=".$_GET['list']."&page=".$Pnext,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">&gt;</a></span> ";
if($ptestnext<$pagenum) {
$pstring = $pstring."<span class=\"pagelinklast\"><a href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=online&list=".$_GET['list']."&page=".$pagenum,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">&raquo;</a></span> "; } }
	++$pagei; } $pstring = $pstring."</div>"; }
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">Board index</a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=online&list=all&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Online Member List</a></div>
<div class="DivNavLinks">&nbsp;</div>
<?php
echo $pstring;
//List Page Number Code end
if($pagenum>1) {
?>
<div class="DivPageLinks">&nbsp;</div>
<?php } ?>
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
<th class="TableColumn2" style="width: 5%;">ID</th>
<th class="TableColumn2" style="width: 24%;">Member Name</th>
<th class="TableColumn2" style="width: 24%;">Group Name</th>
<th class="TableColumn2" style="width: 24%;">Location</th>
<th class="TableColumn2" style="width: 23%;">Time</th>
</tr>
<?php
while ($i < $num) {
$session_data=mysql_result($result,$i,"session_data"); 
$session_expires=mysql_result($result,$i,"expires"); 
$session_expires = GMTimeChange("F j Y, g:i a",$session_expires,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$UserSessInfo = unserialize_session($session_data);
if(!isset($UserSessInfo['UserGroup'])) { 
	$UserSessInfo['UserGroup'] = $Settings['GuestGroup']; }
if(!isset($UserSessInfo['UserIP'])) { 
	$UserSessInfo['UserIP'] = "127.0.0.1"; }
if($UserSessInfo['UserGroup']!=$Settings['GuestGroup']) {
$AmIHiddenUser = GetHiddenMember($UserSessInfo['UserID'],$Settings['sqltable']); }
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
if(isset($ChkID["id"])) { $ChkID = $ChkID["id"]; 
$prequery = query("SELECT * FROM `".$Settings['sqltable']."topics` WHERE `id`=%i LIMIT 1", array($ChkID));
$preresult=exec_query($prequery);
$prenum=mysql_num_rows($preresult);
if($prenum>=1) {
$TopicForumID=mysql_result($preresult,0,"ForumID");
$TopicCatID=mysql_result($preresult,0,"CategoryID"); }
if($prenum<1) {
$TopicForumID=0;
$TopicCatID=0; }
if($CatPermissionInfo['CanViewCategory'][$TopicCatID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$TopicCatID]!="yes") {
	$UserSessInfo['ViewingPage'] = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']);
	$UserSessInfo['PreViewingTitle'] = "Viewing";
	$UserSessInfo['ViewingTitle'] = "Board index"; }
if($PermissionInfo['CanViewForum'][$TopicForumID]=="no"||
	$PermissionInfo['CanViewForum'][$TopicForumID]!="yes") {
	$UserSessInfo['ViewingPage'] = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']);
	$UserSessInfo['PreViewingTitle'] = "Viewing";
	$UserSessInfo['ViewingTitle'] = "Board index"; } } }
if($PreFileName==$exfile['forum'].$Settings['file_ext']) {
if(isset($ChkID["id"])) { $ChkID = $ChkID["id"]; 
$prequery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `id`=%i LIMIT 1", array($ChkID));
$preresult=exec_query($prequery);
$prenum=mysql_num_rows($preresult);
$ForumCatID=mysql_result($preresult,0,"CategoryID");
@mysql_free_result($preresult);
if($CatPermissionInfo['CanViewCategory'][$ForumCatID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$ForumCatID]!="yes") {
	$UserSessInfo['ViewingPage'] = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']);
	$UserSessInfo['PreViewingTitle'] = "Viewing";
	$UserSessInfo['ViewingTitle'] = "Board index"; }
if($PermissionInfo['CanViewForum'][$ChkID]=="no"||
	$PermissionInfo['CanViewForum'][$ChkID]!="yes") {
	$UserSessInfo['ViewingPage'] = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']);
	$UserSessInfo['PreViewingTitle'] = "Viewing";
	$UserSessInfo['ViewingTitle'] = "Board index"; } } }
if($PreFileName==$exfile['subforum'].$Settings['file_ext']) {
if(isset($ChkID["id"])) { $ChkID = $ChkID["id"]; 
$prequery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `id`=%i LIMIT 1", array($ChkID));
$preresult=exec_query($prequery);
$prenum=mysql_num_rows($preresult);
$ForumCatID=mysql_result($preresult,0,"CategoryID");
@mysql_free_result($preresult);
if($CatPermissionInfo['CanViewCategory'][$ForumCatID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$ForumCatID]!="yes") {
	$UserSessInfo['ViewingPage'] = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']);
	$UserSessInfo['PreViewingTitle'] = "Viewing";
	$UserSessInfo['ViewingTitle'] = "Board index"; }
if($PermissionInfo['CanViewForum'][$ChkID]=="no"||
	$PermissionInfo['CanViewForum'][$ChkID]!="yes") {
	$UserSessInfo['ViewingPage'] = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']);
	$UserSessInfo['PreViewingTitle'] = "Viewing";
	$UserSessInfo['ViewingTitle'] = "Board index"; } } }
if($PreFileName==$exfile['category'].$Settings['file_ext']) {
if(isset($ChkID["id"])) { $ChkID = $ChkID["id"]; 
if($CatPermissionInfo['CanViewCategory'][$ChkID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$ChkID]!="yes") {
	$UserSessInfo['ViewingPage'] = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']);
	$UserSessInfo['PreViewingTitle'] = "Viewing";
	$UserSessInfo['ViewingTitle'] = "Board index"; } } }
if($PreFileName==$exfile['subcategory'].$Settings['file_ext']) {
if(isset($ChkID["id"])) { $ChkID = $ChkID["id"]; 
if($CatPermissionInfo['CanViewCategory'][$ChkID]=="no"||
	$CatPermissionInfo['CanViewCategory'][$ChkID]!="yes") {
	$UserSessInfo['ViewingPage'] = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']);
	$UserSessInfo['PreViewingTitle'] = "Viewing";
	$UserSessInfo['ViewingTitle'] = "Board index"; } } }
if($UserSessInfo['UserGroup']!=$Settings['GuestGroup']) {
if($AmIHiddenUser=="no"&&$UserSessInfo['UserID']>0) { 
?>
<tr id="Member<?php echo $i; ?>" class="TableRow3">
<td class="TableColumn3" style="text-align: center;"><?php echo $UserSessInfo['UserID']; ?></td>
<td class="TableColumn3" style="text-align: center;"><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$UserSessInfo['UserID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>"><?php echo $UserSessInfo['MemberName']; ?></a>
<?php if($GroupInfo['HasAdminCP']=="yes") { ?> ( <a onclick="window.open(this.href);return false;" href="http://cqcounter.com/whois/?query=<?php echo $UserSessInfo['UserIP']; ?>"><?php echo $UserSessInfo['UserIP']; ?></a> )<?php } ?></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $UserSessInfo['UserGroup']; ?></td>
<td class="TableColumn3" style="text-align: center;"><a href="<?php echo url_maker($PreFileName,"no+ext",$PreExpPage,$Settings['qstr'],$Settings['qsep'],null,null); ?>"><?php echo $UserSessInfo['PreViewingTitle']; ?> <?php echo $UserSessInfo['ViewingTitle']; ?></a></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $session_expires; ?></td>
</tr>
<?php } }
if($UserSessInfo['UserGroup']==$Settings['GuestGroup']) {
if(!isset($UserSessInfo['GuestName'])) { 
	$UserSessInfo['GuestName'] = "Guest"; }
if(!isset($UserSessInfo['UserID'])) { 
	$UserSessInfo['UserID'] = "0"; }
if($_GET['list']=="all") {
?>
<tr id="Member<?php echo $i; ?>" class="TableRow3">
<td class="TableColumn3" style="text-align: center;"><?php echo $UserSessInfo['UserID']; ?></td>
<td class="TableColumn3" style="text-align: center;"><span><?php echo $UserSessInfo['GuestName']; ?></span>
<?php if($GroupInfo['HasAdminCP']=="yes") { ?> ( <a onclick="window.open(this.href);return false;" href="http://cqcounter.com/whois/?query=<?php echo $UserSessInfo['UserIP']; ?>"><?php echo $UserSessInfo['UserIP']; ?></a> )<?php } ?></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $UserSessInfo['UserGroup']; ?></td>
<td class="TableColumn3" style="text-align: center;"><a href="<?php echo url_maker($PreFileName,"no+ext",$PreExpPage,$Settings['qstr'],$Settings['qsep'],null,null); ?>"><?php echo $UserSessInfo['PreViewingTitle']; ?> <?php echo $UserSessInfo['ViewingTitle']; ?></a></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $session_expires; ?></td>
</tr>
<?php } }
++$i; }
?>
<tr id="MemEnd" class="TableRow4">
<td class="TableColumn4" colspan="8">&nbsp;</td>
</tr>
</table></div>
<?php 
if($pagenum>1) {
?>
<div class="DivMembers">&nbsp;</div>
<?php }
echo $pstring;
//List Page Number Code end
if($pagenum>1) {
?>
<div class="DivPageLinks">&nbsp;</div>
<?php } }
if($_GET['act']=="view") { 
$query = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i LIMIT 1", array($_GET['id']));
$result=exec_query($query);
$num=mysql_num_rows($result);
$i=0;
if($num==0||$_GET['id']<=0) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @session_write_close(); die(); }
$ViewMem['ID']=mysql_result($result,$i,"id");
$ViewMem['Name']=mysql_result($result,$i,"Name");
$ViewMem['Signature']=mysql_result($result,$i,"Signature");
$ViewMem['Avatar']=mysql_result($result,$i,"Avatar");
$ViewMem['AvatarSize']=mysql_result($result,$i,"AvatarSize");
$ViewMem['Email']=mysql_result($result,$i,"Email");
$ViewMem['GroupID']=mysql_result($result,$i,"GroupID");
$ViewMem['HiddenMember']=mysql_result($result,$i,"HiddenMember");
$ViewMem['WarnLevel']=mysql_result($result,$i,"WarnLevel");
$ViewMem['Interests']=mysql_result($result,$i,"Interests");
$ViewMem['Title']=mysql_result($result,$i,"Title");
$ViewMem['Joined']=mysql_result($result,$i,"Joined");
$ViewMem['Joined']=GMTimeChange("M j Y, g:i a",$ViewMem['Joined'],$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$ViewMem['LastActive']=mysql_result($result,$i,"LastActive");
$ViewMem['LastActive']=GMTimeChange("M j Y, g:i a",$ViewMem['LastActive'],$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$ViewMem['Website']=mysql_result($result,$i,"Website");
$ViewMem['Gender']=mysql_result($result,$i,"Gender");
$ViewMem['PostCount']=mysql_result($result,$i,"PostCount");
$ViewMem['Karma']=mysql_result($result,$i,"Karma");
$ViewMem['TimeZone']=mysql_result($result,$i,"TimeZone");
$ViewMem['DST']=mysql_result($result,$i,"DST");
$ViewMem['IP']=mysql_result($result,$i,"IP");
$gquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i LIMIT 1", array($ViewMem['GroupID']));
$gresult=exec_query($gquery);
$ViewMem['Group']=mysql_result($gresult,0,"Name");
/*
$GroupNamePrefix=mysql_result($gresult,0,"NamePrefix");
$GroupNameSuffix=mysql_result($gresult,0,"NameSuffix");
*/
@mysql_free_result($gresult);
/*
if(isset($GroupNamePrefix)&&$GroupNamePrefix!=null) {
	$ViewMem['Name'] = $GroupNamePrefix.$ViewMem['Name']; }
if(isset($GroupNameSuffix)&&$GroupNameSuffix!=null) {
	$ViewMem['Name'] = $ViewMem['Name'].$GroupNameSuffix; }
*/
if($ViewMem['HiddenMember']=="yes") { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @session_write_close(); die(); }
$membertitle = " ".$ThemeSet['TitleDivider']." ".$ViewMem['Name'];	
if ($ViewMem['Avatar']=="http://"||$ViewMem['Avatar']==null||
	strtolower($ViewMem['Avatar'])=="noavatar") {
$ViewMem['Avatar']=$ThemeSet['NoAvatar'];
$ViewMem['AvatarSize']=$ThemeSet['NoAvatarSize']; }
$AvatarSize1=explode("x", $ViewMem['AvatarSize']);
$AvatarSize1W=$AvatarSize1[0]; $AvatarSize1H=$AvatarSize1[1];
$ViewMem['Signature'] = text2icons($ViewMem['Signature'],$Settings['sqltable']);
if($_GET['view']==null) { $_GET['view'] = "profile"; }
if($_GET['view']!="profile"&&$_GET['view']!="avatar"&&
	$_GET['view']!="website"&&$_GET['view']!="homepage") { $_GET['view'] = "profile"; }
if($_GET['view']=="avatar") { 
	@session_write_close();
	@header("Location: ".$ViewMem['Avatar']); }
if($_GET['view']=="website"||$_GET['view']=="homepage") { 
	if ($ViewMem['Website']!="http://"&&$ViewMem['Website']!=null) {
	@session_write_close();
	@header("Location: ".$ViewMem['Website']); }
	if ($ViewMem['Website']=="http://"||$ViewMem['Website']==null||
	strtolower($ViewMem['Avatar'])=="noavatar") {
	@session_write_close();
	@header("Location: ".$BoardURL."index.php?act=view"); } }
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=view&id=".$_GET['id'],"&","=",$prexqstr['member'],$exqstr['member']);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['member'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['member']; }
$_SESSION['PreViewingTitle'] = "Viewing Profile:";
$_SESSION['ViewingTitle'] = $ViewMem['Name'];
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">Board index</a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Viewing profile</a></div>
<div class="DivNavLinks">&nbsp;</div>
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
<?php  /* Avatar Table Thanks For SeanJ's Help at http://seanj.jcink.com/ */  ?>
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
<?php if($GroupInfo['HasAdminCP']=="yes") { ?>
<br />User IP: <a onclick="window.open(this.href);return false;" href="http://cqcounter.com/whois/?query=<?php echo $ViewMem['IP']; ?>">
<?php echo $ViewMem['IP']; echo "</a>"; } ?></div>
</td>
<td class="TableColumn3">
&nbsp;User Name: <?php echo $ViewMem['Name']; ?><br />
&nbsp;User Title: <?php echo $ViewMem['Title']; ?><br />
&nbsp;User Group: <?php echo $ViewMem['Group']; ?><br />
&nbsp;User Joined: <?php echo $ViewMem['Joined']; ?><br />
&nbsp;Last Active: <?php echo $ViewMem['LastActive']; ?><br />
&nbsp;User Time: <?php echo GMTimeGet("M j Y, g:i a",$ViewMem['TimeZone'],0,$ViewMem['DST']); ?><br />
&nbsp;User Website: <a href="<?php echo $ViewMem['Website']; ?>" onclick="window.open(this.href);return false;">Website</a><br />
&nbsp;Post Count: <?php echo $ViewMem['PostCount']; ?><br />
&nbsp;Karma: <?php echo $ViewMem['Karma']; ?><br />
&nbsp;Interests: <?php echo $ViewMem['Interests']; ?><br />
</td>
</tr>
<tr class="TableRow4">
<td class="TableColumn4" colspan="2">&nbsp;</td>
</tr>
</table></div>
<?php } @mysql_free_result($result);
if($_GET['act']=="logout") {
@session_unset();
if($cookieDomain==null) {
@setcookie("MemberName", null, GMTimeStamp() - 3600, $cbasedir);
@setcookie("UserID", null, GMTimeStamp() - 3600, $cbasedir);
@setcookie("SessPass", null, GMTimeStamp() - 3600, $cbasedir);
@setcookie(session_name(), "", GMTimeStamp() - 3600, $cbasedir); }
if($cookieDomain!=null) {
if($cookieSecure===true) {
@setcookie("MemberName", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1);
@setcookie("UserID", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1);
@setcookie("SessPass", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1);
@setcookie(session_name(), "", GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1); }
if($cookieSecure===false) {
@setcookie("MemberName", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain);
@setcookie("UserID", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain);
@setcookie("SessPass", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain);
@setcookie(session_name(), "", GMTimeStamp() - 3600, $cbasedir, $cookieDomain); } }
unset($_COOKIE[session_name()]);
$_SESSION = array();
@session_unset();
@session_destroy();
@redirect("location",$basedir.url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @session_write_close(); die(); }
if($_GET['act']=="login") {
if($_SESSION['UserID']!=0&&$_SESSION['UserID']!=null) { 
redirect("location",$basedir.url_maker($exfile['member'],$Settings['file_ext'],"act=logout",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @session_write_close(); die(); }
if($_SESSION['UserID']==0||$_SESSION['UserID']==null) {
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=login","&","=",$prexqstr['member'],$exqstr['member']);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['member'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['member']; }
$_SESSION['PreViewingTitle'] = "Act: ";
$_SESSION['ViewingTitle'] = "Logging in";
$membertitle = " ".$ThemeSet['TitleDivider']." Login";
$UFID = uuid(false,true,false,$Settings['use_hashtype'],null);
$_SESSION['UserFormID'] = $UFID;
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">Board index</a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Login</a></div>
<div class="DivNavLinks">&nbsp;</div>
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
<th class="TableColumn2" style="width: 100%; text-align: left;">&nbsp;Inert your login info: </th>
</tr>
<tr class="TableRow3">
<td class="TableColumn3">
<form style="display: inline;" method="post" action="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=login_now",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 30%;"><label class="TextBoxLabel" for="username">Enter UserName: </label></td>
	<td style="width: 70%;"><input maxlength="24" class="TextBox" id="username" type="text" name="username" /></td>
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
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="loginmember" style="display: none;" />
<input type="hidden" style="display: none;" name="fid" value="<?php echo $UFID; ?>" />
<input class="Button" type="submit" value="Log in" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableRow4">
<td class="TableColumn4">&nbsp;</td>
</tr>
</table></div>
<?php } } if($_POST['act']=="loginmember"&&$_GET['act']=="login_now") {
if($_SESSION['UserID']!=0&&$_SESSION['UserID']!=null) { 
redirect("location",$basedir.url_maker($exfile['member'],$Settings['file_ext'],"act=logout",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @session_write_close(); die(); }
if($_SESSION['UserID']==0||$_SESSION['UserID']==null) {
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=login","&","=",$prexqstr['member'],$exqstr['member']);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['member'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['member']; }
$_SESSION['PreViewingTitle'] = "Act: ";
$_SESSION['ViewingTitle'] = "Logging in";
$membertitle = " ".$ThemeSet['TitleDivider']." Login";
$REFERERurl = parse_url($_SERVER['HTTP_REFERER']);
$URL['REFERER'] = $REFERERurl['host'];
$URL['HOST'] = $_SERVER["SERVER_NAME"];
$REFERERurl = null;
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">Board index</a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Login</a></div>
<div class="DivNavLinks">&nbsp;</div>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">&nbsp;<a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Log in</a></span>
</div>
<?php } ?>
<table class="Table1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1">
<td class="TableColumn1">
<span style="text-align: left;">&nbsp;<a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Log in</a></span>
</td>
</tr><?php } ?>
<tr class="TableRow2">
<th class="TableColumn2" style="width: 100%; text-align: left;">&nbsp;Login Message: </th>
</tr>
<tr class="TableRow3">
<td class="TableColumn3">
<table style="width: 100%; height: 25%; text-align: center;">
<?php
if (pre_strlen($_POST['userpass'])>"60") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your password is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (pre_strlen($_POST['username'])>"30") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your user name is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if($_POST['fid']!=$_SESSION['UserFormID']) { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Sorry the referering url dose not match our host name.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($Settings['TestReferer']=="on") {
	if ($URL['HOST']!=$URL['REFERER']) { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Sorry the referering url dose not match our host name.<br />
	</span>&nbsp;</td>
</tr>
<?php } } $BanError = null;
if ($Error=="Yes") {
@redirect("refresh",$basedir.url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],false),"4"); }
if($Error!="Yes"){
$YourName = stripcslashes(htmlspecialchars($_POST['username'], ENT_QUOTES, $Settings['charset']));
//$YourName = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $YourName);
$YourName = @remove_spaces($YourName);
$passtype="ODFH";
if(!isset($_POST['loginemail'])) { $_POST['loginemail'] = "false"; }
if($_POST['loginemail']!="true") {
$querylog = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `Name`='%s' LIMIT 1", array($YourName)); }
if($_POST['loginemail']=="true") {
$querylog = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `Email`='%s' LIMIT 1", array($YourName)); }
$resultlog=exec_query($querylog);
$numlog=mysql_num_rows($resultlog);
if($numlog>=1) {
$i=0;
$YourName=mysql_result($resultlog,$i,"Name");
$YourPassTry=mysql_result($resultlog,$i,"Password");
$HashType=mysql_result($resultlog,$i,"HashType");
$JoinedPass=mysql_result($resultlog,$i,"Joined");
$HashSalt=mysql_result($resultlog,$i,"Salt");
$UpdateHash = false;
if($HashType=="ODFH") { $YourPassword = PassHash2x($_POST['userpass']); }
if($HashType=="IPB2") { $YourPassword = hash2xkey($_POST['userpass'],$HashSalt); }
if($HashType=="DF4H") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"sha1"); }
if($HashType=="iDBH2") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"md2"); }
if($HashType=="iDBH4") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"md4"); }
if($HashType=="iDBH5") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"md5"); }
if($HashType=="iDBH") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"sha1"); }
if($HashType=="iDBH256") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"sha256"); }
if($HashType=="iDBH386") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"sha386"); }
if($HashType=="iDBH512") { $YourPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$HashSalt,"sha512"); }
if($YourPassword!=$YourPassTry) { $passright = false; } 
if($YourPassword==$YourPassTry) { $passright = true;
$YourIDM=mysql_result($resultlog,$i,"id");
$YourNameM=mysql_result($resultlog,$i,"Name");
$YourPassM=mysql_result($resultlog,$i,"Password");
$PostCount=mysql_result($resultlog,$i,"PostCount");
$YourGroupM=mysql_result($resultlog,$i,"GroupID");
$YourGroupIDM=$YourGroupM;
$YourLastPostTime=mysql_result($resultlog,$i,"LastPostTime");
$YourBanTime=mysql_result($resultlog,$i,"BanTime");
$CGMTime = GMTimeStamp();
if($YourBanTime!=0&&$YourBanTime!=null) {
if($YourBanTime>=$CGMTime) { $BanError = "yes"; } }
$gquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i LIMIT 1", array($YourGroupM));
$gresult=exec_query($gquery);
$YourGroupM=mysql_result($gresult,0,"Name");
@mysql_free_result($gresult);
$YourTimeZoneM=mysql_result($resultlog,$i,"TimeZone");
$YourDSTM=mysql_result($resultlog,$i,"DST");
$JoinedDate=mysql_result($resultlog,$i,"Joined");
$UseTheme=mysql_result($resultlog,$i,"UseTheme");
$NewHashSalt = salt_hmac();
if($Settings['use_hashtype']=="md2") { $iDBHash = "iDBH2";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"md2"); }
if($Settings['use_hashtype']=="md4") { $iDBHash = "iDBH4";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"md4"); }
if($Settings['use_hashtype']=="md5") { $iDBHash = "iDBH5";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"md5"); }
if($Settings['use_hashtype']=="sha1") { $iDBHash = "iDBH";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"sha1"); }
if($Settings['use_hashtype']=="sha256") { $iDBHash = "iDBH256";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"sha256"); }
if($Settings['use_hashtype']=="sha386") { $iDBHash = "iDBH386";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"sha386"); }
if($Settings['use_hashtype']=="sha512") { $iDBHash = "iDBH512";
$NewPassword = b64e_hmac($_POST['userpass'],$JoinedPass,$NewHashSalt,"sha512"); }
$NewDay=GMTimeStamp();
$NewIP=$_SERVER['REMOTE_ADDR'];
if($BanError!="yes") {
$queryup = query("UPDATE `".$Settings['sqltable']."members` SET `Password`='%s',`HashType`='%s',`LastActive`=%i,`IP`='%s',`Salt`='%s' WHERE `id`=%i", array($NewPassword,$iDBHash,$NewDay,$NewIP,$NewHashSalt,$YourIDM));
exec_query($queryup);
@mysql_free_result($resultlog); @mysql_free_result($queryup);
//session_regenerate_id();
$_SESSION['Theme']=$UseTheme;
$_SESSION['MemberName']=$YourNameM;
$_SESSION['UserID']=$YourIDM;
$_SESSION['UserIP']=$_SERVER['REMOTE_ADDR'];
$_SESSION['UserTimeZone']=$YourTimeZoneM;
$_SESSION['UserGroup']=$YourGroupM;
$_SESSION['UserGroupID']=$YourGroupIDM;
$_SESSION['UserDST']=$YourDSTM;
$_SESSION['UserPass']=$NewPassword;
$_SESSION['LastPostTime'] = $YourLastPostTime;
$_SESSION['DBName']=$Settings['sqldb'];
if($_POST['storecookie']=="true") {
if($cookieDomain==null) {
@setcookie("MemberName", $YourNameM, time() + (7 * 86400), $cbasedir);
@setcookie("UserID", $YourIDM, time() + (7 * 86400), $cbasedir);
@setcookie("SessPass", $NewPassword, time() + (7 * 86400), $cbasedir); }
if($cookieDomain!=null) {
if($cookieSecure===true) {
@setcookie("MemberName", $YourNameM, time() + (7 * 86400), $cbasedir, $cookieDomain, 1);
@setcookie("UserID", $YourIDM, time() + (7 * 86400), $cbasedir, $cookieDomain, 1);
@setcookie("SessPass", $NewPassword, time() + (7 * 86400), $cbasedir, $cookieDomain, 1); }
if($cookieSecure===false) {
@setcookie("MemberName", $YourNameM, time() + (7 * 86400), $cbasedir, $cookieDomain);
@setcookie("UserID", $YourIDM, time() + (7 * 86400), $cbasedir, $cookieDomain);
@setcookie("SessPass", $NewPassword, time() + (7 * 86400), $cbasedir, $cookieDomain); } } } }
} } if($numlog<=0) {
//echo "Password was not right or user not found!! <_< ";
} ?>
<?php if($passright===true&&$BanError!="yes") {
@redirect("refresh",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false),"3"); ?>
<tr>
	<td><span class="TableMessage">
	<br />Welcome to the Board <?php echo $_SESSION['MemberName']; ?>. ^_^<br />
	Click <a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">here</a> to continue to board.<br />&nbsp;
	</span><br /></td>
</tr>
<?php } if($passright===false||$BanError=="yes"||$numlog<=0) { ?>
<tr>
	<td><span class="TableMessage">
	<br />Password was not right or user not found or user is banned!! &lt;_&lt;<br />
	Click <a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=login",$Settings['qstr'],$Settings['qsep'],$exqstr['member'],$prexqstr['member']); ?>">here</a> to try again.<br />&nbsp;
	</span><br /></td>
</tr>
<?php } } ?>
</table>
</td></tr>
<tr class="TableRow4">
<td class="TableColumn4">&nbsp;</td>
</tr>
</table></div>
<?php } } if($_GET['act']=="signup") { 
$membertitle = " ".$ThemeSet['TitleDivider']." Signing up"; 
if($_SESSION['UserID']!=0&&$_SESSION['UserID']!=null) { 
redirect("location",$basedir.url_maker($exfile['member'],$Settings['file_ext'],"act=logout",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @session_write_close(); die(); }
if($_SESSION['UserID']==0||$_SESSION['UserID']==null) {
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=signup","&","=",$prexqstr['member'],$exqstr['member']);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['member'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['member']; }
$_SESSION['PreViewingTitle'] = "Act: ";
$_SESSION['ViewingTitle'] = "Signing up";
$UFID = uuid(false,true,false,$Settings['use_hashtype'],null);
$_SESSION['UserFormID'] = $UFID;
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">Board index</a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=signup",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Signup</a></div>
<div class="DivNavLinks">&nbsp;</div>
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
<th class="TableColumn2" style="width: 100%; text-align: left;">&nbsp;Inert your user info: </th>
</tr>
<tr class="TableRow3">
<td class="TableColumn3">
<form style="display: inline;" method="post" action="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=makemember",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 30%;"><label class="TextBoxLabel" for="Name">Insert a UserName:</label></td>
	<?php if(!isset($_SESSION['GuestName'])) { ?>
	<td style="width: 70%;"><input maxlength="24" type="text" class="TextBox" name="Name" size="20" id="Name" /></td>
	<?php } if(isset($_SESSION['GuestName'])) { ?>
	<td style="width: 70%;"><input maxlength="24" type="text" class="TextBox" name="Name" size="20" id="Name" value="<?php echo $_SESSION['GuestName']; ?>" /></td>
	<?php } ?>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="Password">Insert a Password:</label></td>
	<td style="width: 70%;"><input maxlength="30" type="password" class="TextBox" name="Password" size="20" id="Password" /></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="RePassword">ReInsert a Password:</label></td>
	<td style="width: 70%;"><input maxlength="30" type="password" class="TextBox" name="RePassword" size="20" id="RePassword" /></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="Email">Insert Your Email:</label></td>
	<td style="width: 70%;"><input type="text" class="TextBox" name="Email" size="20" id="Email" /></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="YourOffSet">Your TimeZone:</label></td>
	<td style="width: 70%;"><select id="YourOffSet" name="YourOffSet" class="TextBox"><?php
$tsa_mem = explode(":",$Settings['DefaultTimeZone']);
$TimeZoneArray = array("offset" => $Settings['DefaultTimeZone'], "hour" => $tsa_mem[0], "minute" => $tsa_mem[1]);
$plusi = 1; $minusi = 12;
$plusnum = 15; $minusnum = 0;
while ($minusi > $minusnum) {
if($TimeZoneArray['hour']==-$minusi) {
echo "<option selected=\"selected\" value=\"-".$minusi."\">GMT - ".$minusi.":00 hours</option>\n"; }
if($TimeZoneArray['hour']!=-$minusi) {
echo "<option value=\"-".$minusi."\">GMT - ".$minusi.":00 hours</option>\n"; }
--$minusi; }
if($TimeZoneArray['hour']==0) { ?>
<option selected="selected" value="0">GMT +/- 0:00 hours</option>
<?php } if($TimeZoneArray['hour']!=0) { ?>
<option value="0">GMT +/- 0:00 hours</option>
<?php }
while ($plusi < $plusnum) {
if($TimeZoneArray['hour']==$plusi) {
echo "<option selected=\"selected\" value=\"".$plusi."\">GMT + ".$plusi.":00 hours</option>\n"; }
if($TimeZoneArray['hour']!=$plusi) {
echo "<option value=\"".$plusi."\">GMT + ".$plusi.":00 hours</option>\n"; }
++$plusi; }
?></select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="MinOffSet">Minute OffSet:</label></td>
	<td style="width: 50%;"><select id="MinOffSet" name="MinOffSet" class="TextBox"><?php
$mini = 0; $minnum = 60;
while ($mini < $minnum) {
if(pre_strlen($mini)==2) { $showmin = $mini; }
if(pre_strlen($mini)==1) { $showmin = "0".$mini; }
if($mini==$TimeZoneArray['minute']) {
echo "\n<option selected=\"selected\" value=\"".$showmin."\">0:".$showmin." minutes</option>\n"; }
if($mini!=$TimeZoneArray['minute']) {
echo "<option value=\"".$showmin."\">0:".$showmin." minutes</option>\n"; }
++$mini; }
?></select></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="DST">Is <span title="Daylight Savings Time">DST</span> / <span title="Summer Time">ST</span> on or off:</label></td>
	<td style="width: 70%;"><select id="DST" name="DST" class="TextBox"><?php echo "\n" ?>
<?php if($Settings['DefaultDST']=="off"||$Settings['DefaultDST']!="on") { ?>
<option selected="selected" value="off">off</option><?php echo "\n" ?><option value="on">on</option>
<?php } if($Settings['DefaultDST']=="on") { ?>
<option selected="selected" value="on">on</option><?php echo "\n" ?><option value="off">off</option>
<?php } echo "\n" ?></select></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="YourGender">Your Gender:</label></td>
	<td style="width: 70%;"><select id="YourGender" name="YourGender" class="TextBox">
<option value="Male">Male</option>
<option value="Female">Female</option>
<option value="Unknow">Unknown</option>
</select></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="Website">Insert your Website:</label></td>
	<td style="width: 70%;"><input type="text" class="TextBox" name="Website" size="20" value="http://" id="Website" /></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="Avatar">Insert a URL for Avatar:</label></td>
	<td style="width: 70%;"><input type="text" class="TextBox" name="Avatar" size="20" value="http://" id="Avatar" /></td>
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
<input maxlength="25" type="text" class="TextBox" name="signcode" size="20" id="signcode" value="Enter SignCode" /><br /><?php } ?>
<input type="hidden" style="display: none;" name="act" value="makemembers" />
<input type="hidden" style="display: none;" name="fid" value="<?php echo $UFID; ?>" />
<input type="submit" class="Button" value="Sign UP" />
</td></tr>
</table>
</form>
</td>
</tr>
<tr class="TableRow4">
<td class="TableColumn4">&nbsp;</td>
</tr>
</table></div>
<?php } } if($_GET['act']=="makemember") {
	if($_POST['act']=="makemembers") {
if($_SESSION['UserID']!=0&&$_SESSION['UserID']!=null) { 
redirect("location",$basedir.url_maker($exfile['member'],$Settings['file_ext'],"act=logout",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @session_write_close(); die(); }
if($_SESSION['UserID']==0||$_SESSION['UserID']==null) {
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=signup","&","=",$prexqstr['member'],$exqstr['member']);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['member'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['member']; }
$_SESSION['PreViewingTitle'] = "Act: ";
$_SESSION['ViewingTitle'] = "Signing up";
$membertitle = " ".$ThemeSet['TitleDivider']." Signing up";
$REFERERurl = parse_url($_SERVER['HTTP_REFERER']);
$URL['REFERER'] = $REFERERurl['host'];
$URL['HOST'] = $_SERVER["SERVER_NAME"];
$REFERERurl = null;
if(!isset($_POST['username'])) { $_POST['username'] = null; }
if(!isset($_POST['TOS'])) { $_POST['TOS'] = null; }
if($Settings['use_captcha']=="on") {
require($SettDir['inc']."captcha.php"); }
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">Board index</a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=signup",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Signup</a></div>
<div class="DivNavLinks">&nbsp;</div>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
&nbsp;<a href="<?php echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=signup",$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); ?>">Register</a></span></div>
<?php } ?>
<table class="Table1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1">
<td class="TableColumn1"><span style="text-align: left;">
&nbsp;<a href="<?php echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=signup",$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); ?>">Register</a></span></td>
</tr><?php } ?>
<tr class="TableRow2">
<th class="TableColumn2" style="width: 100%; text-align: left;">&nbsp;Signup Message: </th>
</tr>
<tr class="TableRow3">
<td class="TableColumn3">
<table style="width: 100%; height: 25%; text-align: center;">
<?php if (pre_strlen($_POST['Password'])>"60") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your password is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if($_POST['fid']!=$_SESSION['UserFormID']) { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Sorry the referering url dose not match our host name.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (pre_strlen($_POST['username'])>"30") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your user name is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($_POST['Password']!=$_POST['RePassword']) { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your passwords did not match.<br />
	</span>&nbsp;</td>
</tr>
<?php } if($Settings['use_captcha']=="on") {
if (PhpCaptcha::Validate($_POST['signcode'])) {
//echo 'Valid code entered';
} else { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />Invalid code entered<br />
	</span>&nbsp;</td>
</tr>
<?php } } if ($Settings['TestReferer']=="on") {
	if ($URL['HOST']!=$URL['REFERER']) { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Sorry the referering url dose not match our host name.<br />
	</span>&nbsp;</td>
</tr>
<?php } }
$Name = stripcslashes(htmlspecialchars($_POST['Name'], ENT_QUOTES, $Settings['charset']));
//$Name = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $Name);
$Name = @remove_spaces($Name);
$lonewolfqy=query("SELECT * FROM `".$Settings['sqltable']."restrictedwords` WHERE `RestrictedUserName`='yes'", array(null));
$lonewolfrt=exec_query($lonewolfqy);
$lonewolfnm=mysql_num_rows($lonewolfrt);
$lonewolfs=0; $RMatches = null;
while ($lonewolfs < $lonewolfnm) {
$RWord=mysql_result($lonewolfrt,$lonewolfs,"Word");
$RCaseInsensitive=mysql_result($lonewolfrt,$lonewolfs,"CaseInsensitive");
if($RCaseInsensitive=="on") { $RCaseInsensitive = "yes"; }
if($RCaseInsensitive=="off") { $RCaseInsensitive = "no"; }
if($RCaseInsensitive!="yes"||$RCaseInsensitive!="no") { $RCaseInsensitive = "no"; }
$RWholeWord=mysql_result($lonewolfrt,$lonewolfs,"WholeWord");
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
++$lonewolfs; } @mysql_free_result($lonewolfrt);
$sql_email_check = exec_query(query("SELECT `Email` FROM `".$Settings['sqltable']."members` WHERE `Email`='%s'", array($_POST['Email'])));
$sql_username_check = exec_query(query("SELECT `Name` FROM `".$Settings['sqltable']."members` WHERE `Name`='%s'", array($Name)));
$email_check = mysql_num_rows($sql_email_check); 
$username_check = mysql_num_rows($sql_username_check);
@mysql_free_result($sql_email_check); @mysql_free_result($sql_username_check);
if ($_POST['TOS']!="Agree") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to  agree to the tos.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($_POST['Name']==null) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a name.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($_POST['Name']=="ShowMe") { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a name.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($_POST['Password']==null) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a password.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($_POST['Email']==null) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a email.<br />
	</span>&nbsp;</td>
</tr>
<?php } if($email_check > 0) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />Email address is already used.<br />
	</span>&nbsp;</td>
</tr>
<?php } if($username_check > 0) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />User Name is already used.<br />
	</span>&nbsp;</td>
</tr>
<?php } if($RMatches==true) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />This User Name is restricted to use.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($Error=="Yes") {
@redirect("refresh",$basedir.url_maker($exfile['member'],$Settings['file_ext'],"act=signup",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'],FALSE),"4"); ?>
<tr>
	<td><span class="TableMessage">
	<br />Click <a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=signup",$Settings['qstr'],$Settings['qsep'],$exqstr['member'],$prexqstr['member']); ?>">here</a> to try again.<br />&nbsp;
	</span><br /></td>
</tr>
<?php } if ($Error!="Yes") {
$_POST['UserIP'] = $_SERVER['REMOTE_ADDR'];
$_POST['Group'] = $Settings['MemberGroup'];
$_POST['Joined'] = GMTimeStamp(); $_POST['LastActive'] = GMTimeStamp();
$_POST['Signature'] = ""; $_POST['Interests'] = "";
$_POST['Title'] = ""; $_POST['PostCount'] = "0";
if(!isset($Settings['AdminValidate'])) { $Settings['AdminValidate'] = "off"; }
if($Settings['AdminValidate']=="on"||$Settings['AdminValidate']!="off")
{ $ValidateStats="no"; $yourgroup=$Settings['ValidateGroup']; }
if($Settings['AdminValidate']=="off"||$Settings['AdminValidate']!="on")
{ $ValidateStats="yes"; $yourgroup=$Settings['MemberGroup']; }
$HideMe = "no"; $HashSalt = salt_hmac();
if($Settings['use_hashtype']=="md2") { $iDBHash = "iDBH2";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"md2"); }
if($Settings['use_hashtype']=="md4") { $iDBHash = "iDBH4";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"md4"); }
if($Settings['use_hashtype']=="md5") { $iDBHash = "iDBH5";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"md5"); }
if($Settings['use_hashtype']=="sha1") { $iDBHash = "iDBH";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"sha1"); }
if($Settings['use_hashtype']=="sha256") { $iDBHash = "iDBH256";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"sha256"); }
if($Settings['use_hashtype']=="sha386") { $iDBHash = "iDBH386";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"sha386"); }
if($Settings['use_hashtype']=="sha512") { $iDBHash = "iDBH512";
$NewPassword = b64e_hmac($_POST['Password'],$_POST['Joined'],$HashSalt,"sha512"); }
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
$Avatar = @remove_spaces($Avatar);
$Website = stripcslashes(htmlspecialchars($_POST['Website'], ENT_QUOTES, $Settings['charset']));
//$Website = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $Website);
$Website = @remove_spaces($Website);
$gquerys = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `Name`='%s' LIMIT 1", array($yourgroup));
$gresults=exec_query($gquerys);
$yourgroup=mysql_result($gresults,0,"id");
@mysql_free_result($gresults);
$yourid = getnextid($Settings['sqltable'],"members");
$_POST['Interests'] = @remove_spaces($_POST['Interests']);
$_POST['Title'] = @remove_spaces($_POST['Title']);
$_POST['Email'] = @remove_spaces($_POST['Email']);
if(!is_numeric($_POST['YourOffSet'])) { $_POST['YourOffSet'] = "0"; }
if($_POST['YourOffSet']>12) { $_POST['YourOffSet'] = "12"; }
if($_POST['YourOffSet']<-12) { $_POST['YourOffSet'] = "-12"; }
if(!is_numeric($_POST['MinOffSet'])) { $_POST['MinOffSet'] = "00"; }
if($_POST['MinOffSet']>59) { $_POST['MinOffSet'] = "59"; }
if($_POST['MinOffSet']<0) { $_POST['MinOffSet'] = "00"; }
$_POST['YourOffSet'] = $_POST['YourOffSet'].":".$_POST['MinOffSet'];
$query = query("INSERT INTO `".$Settings['sqltable']."members` (`Name`, `Password`, `HashType`, `Email`, `GroupID`, `Validated`, `HiddenMember`, `WarnLevel`, `Interests`, `Title`, `Joined`, `LastActive`, `LastPostTime`, `BanTime`, `BirthDay`, `BirthMonth`, `BirthYear`, `Signature`, `Notes`, `Avatar`, `AvatarSize`, `Website`, `Gender`, `PostCount`, `Karma`, `KarmaUpdate`, `RepliesPerPage`, `TopicsPerPage`, `MessagesPerPage`, `TimeZone`, `DST`, `UseTheme`, `IP`, `Salt`) VALUES\n". 
"('%s', '%s', '%s', '%s', '%s', '%s', '%s', %i, '%s', '%s', %i, %i, '0', '0', '0', '0', '0', '%s', '%s', '%s', '%s', '%s', '%s', %i, 0, 0, 10, 10, 10, '%s', '%s', '%s', '%s', '%s')", array($Name,$NewPassword,$iDBHash,$_POST['Email'],$yourgroup,$ValidateStats,$HideMe,"0",$_POST['Interests'],$_POST['Title'],$_POST['Joined'],$_POST['LastActive'],$NewSignature,'Your Notes',$Avatar,"100x100",$Website,$_POST['YourGender'],$_POST['PostCount'],$_POST['YourOffSet'],$_POST['DST'],$Settings['DefaultTheme'],$_POST['UserIP'],$HashSalt));
exec_query($query);
$querylogr = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `Name`='%s' AND `Password`='%s' LIMIT 1", array($Name,$NewPassword));
$resultlogr=exec_query($querylogr);
$numlogr=mysql_num_rows($resultlogr);
if($numlogr>=1) {
$ir=0;
$YourIDMr=mysql_result($resultlogr,$ir,"id");
$YourNameMr=mysql_result($resultlogr,$ir,"Name");
$YourGroupMr=mysql_result($resultlogr,$ir,"GroupID");
$YourGroupIDMr=$YourGroupMr;
$gquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i LIMIT 1", array($YourGroupMr));
$gresult=exec_query($gquery);
$YourGroupMr=mysql_result($gresult,0,"Name");
@mysql_free_result($gresult);
$YourTimeZoneMr=mysql_result($resultlogr,$ir,"TimeZone");
$YourDSTMr=mysql_result($resultlogr,$ir,"DST"); }
@mysql_free_result($resultlogr);
@session_regenerate_id(true);
$_SESSION['Loggedin']=true;
$_SESSION['MemberName']=$YourNameMr;
$_SESSION['UserID']=$YourIDMr;
$_SESSION['UserIP']=$_SERVER['REMOTE_ADDR'];
$_SESSION['UserTimeZone']=$YourTimeZoneMr;
$_SESSION['UserDST']=$YourDSTMr;
$_SESSION['UserGroup']=$YourGroupMr;
$_SESSION['UserGroupID']=$YourGroupIDMr;
$_SESSION['UserPass']=$NewPassword;
$_SESSION['DBName']=$Settings['sqldb'];
if($_POST['storecookie']=="true") {
if($cookieDomain==null) {
@setcookie("MemberName", $YourNameM, time() + (7 * 86400), $cbasedir);
@setcookie("UserID", $YourIDM, time() + (7 * 86400), $cbasedir);
@setcookie("SessPass", $NewPassword, time() + (7 * 86400), $cbasedir); }
if($cookieDomain!=null) {
if($cookieSecure===true) {
@setcookie("MemberName", $YourNameM, time() + (7 * 86400), $cbasedir, $cookieDomain, 1);
@setcookie("UserID", $YourIDM, time() + (7 * 86400), $cbasedir, $cookieDomain, 1);
@setcookie("SessPass", $NewPassword, time() + (7 * 86400), $cbasedir, $cookieDomain, 1); }
if($cookieSecure===false) {
@setcookie("MemberName", $YourNameM, time() + (7 * 86400), $cbasedir, $cookieDomain);
@setcookie("UserID", $YourIDM, time() + (7 * 86400), $cbasedir, $cookieDomain);
@setcookie("SessPass", $NewPassword, time() + (7 * 86400), $cbasedir, $cookieDomain); } } }
@redirect("refresh",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],FALSE),"3");
?>
<tr>
	<td><span class="TableMessage">
	<br />Welcome to the Board <?php echo $_SESSION['MemberName']; ?>. ^_^<br />
	Click <a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">here</a> to continue to board.<?php echo "\n"; 
	if($Settings['AdminValidate']=="on"||$Settings['AdminValidate']!="off") {
	echo "<br />The admin has to validate your account befoure you can post.\n";
	echo "<br />The admin has been notified of your registration.\n"; } ?>
	<br />&nbsp;
	</span><br /></td>
</tr>
<?php } ?>
</table>
</td></tr>
<tr class="TableRow4">
<td class="TableColumn4">&nbsp;</td>
</tr>
</table></div>
<?php } } }
if($pagenum<=1) { ?>
<div class="DivMembers">&nbsp;</div>
<?php } ?>