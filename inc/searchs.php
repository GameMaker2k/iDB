<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2010 iDB Support - http://idb.berlios.de/
    Copyright 2004-2010 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: searchs.php - Last Update: 09/05/2010 SVN 572 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="searchs.php"||$File3Name=="/searchs.php") {
	require('index.php');
	exit(); }
if($Settings['enable_search']=="off"||
	$GroupInfo['CanSearch']=="no") {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
header("Content-Type: text/plain; charset=".$Settings['charset']);
ob_clean(); echo "Sorry you do not have permission to do a search."; 
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
$pagenum = null;
if($Settings['enable_search']=="on"||
	$GroupInfo['CanSearch']=="yes") {
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=topics","&","=",$prexqstr['search'],$exqstr['search']);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['search'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['search']; }
$_SESSION['PreViewingTitle'] = "Searching";
$_SESSION['ViewingTitle'] = "Topics";
if($_GET['act']=="topics") {
	if($_GET['search']==null&&$_GET['type']==null) {
	?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">Board index</a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['search'],$Settings['file_ext'],"act=topics",$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search']); ?>">Search topics</a></div>
<div class="DivNavLinks">&nbsp;</div>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['search'],$Settings['file_ext'],"act=topics",$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search']); ?>">Topic Search</a></span></div>
<?php } ?>
<table class="Table1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr id="SearchStart" class="TableRow1">
<td class="TableColumn1" colspan="6"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['search'],$Settings['file_ext'],"act=topics",$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search']); ?>">Topic Search</a></span>
</td>
</tr><?php } ?>
<tr class="TableRow2">
<th class="TableColumn2" style="width: 100%; text-align: left;">&nbsp;Search for topic: </th>
</tr>
<tr class="TableRow3">
<td class="TableColumn3">
<form style="display: inline;" method="post" action="<?php echo url_maker($exfile['search'],$Settings['file_ext'],"act=topics",$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 30%;"><label class="TextBoxLabel" for="search">Enter SearchTerm: </label></td>
	<td style="width: 70%;"><input maxlength="35" class="TextBox" id="search" type="text" name="search" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 30%;"><label class="TextBoxLabel" for="msearch">Filter by Member (optional): </label></td>
	<td style="width: 70%;"><input maxlength="25" class="TextBox" id="msearch" type="text" name="msearch" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 30%;"><label class="TextBoxLabel" title="Wildcard is %" for="type">Search Type: </label></td>
	<td style="width: 70%;"><select id="type" name="type" class="TextBox">
<option value="normal">Normal Search</option>
<option value="wildcard">Wildcard Search</option>
</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="topics" style="display: none;" />
<input class="Button" type="submit" value="Search" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableRow4">
<td class="TableColumn4">&nbsp;</td>
</tr>
</table></div>
<?php } if($_GET['search']!=null&&$_GET['type']!=null) {
if(pre_strlen($_GET['msearch'])>="25") { 
	$_GET['msearch'] = null; }
if($_GET['msearch']!=null) {
$memsiquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"Name\"='%s' LIMIT 1", array($_GET['msearch']));
$memsiresult=sql_query($memsiquery,$SQLStat);
$memsinum=sql_num_rows($memsiresult);
$memsi=0;
if($memsinum==0) { $memsid = -1; }
if($memsinum!=0) {
$memsid=sql_result($memsiresult,$memsi,"id"); 
sql_free_result($memsiresult); } }
//Get SQL LIMIT Number
$nums = $_GET['page'] * $Settings['max_topics'];
$PageLimit = $nums - $Settings['max_topics'];
if($PageLimit<0) { $PageLimit = 0; }
if($_GET['msearch']==null) {
if($_GET['type']!="wildcard") {
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."topics\" WHERE \"TopicName\"='%s'".$ForumIgnoreList4." ORDER BY \"LastUpdate\" DESC ".$SQLimit, array($_GET['search'],$PageLimit,$Settings['max_topics'])); 
$rnquery = sql_pre_query("SELECT COUNT(*) FROM \"".$Settings['sqltable']."topics\" WHERE \"TopicName\"='%s'".$ForumIgnoreList4."", array($_GET['search'])); }
if($_GET['type']=="wildcard") {
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."topics\" WHERE \"TopicName\" LIKE '%s'".$ForumIgnoreList4." ORDER BY \"LastUpdate\" DESC ".$SQLimit, array($_GET['search'],$PageLimit,$Settings['max_topics'])); 
$rnquery = sql_pre_query("SELECT COUNT(*) FROM \"".$Settings['sqltable']."topics\" WHERE \"TopicName\" LIKE '%s'".$ForumIgnoreList4."", array($_GET['search'])); } }
if($_GET['msearch']!=null) {
if($_GET['type']!="wildcard") {
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."topics\" WHERE \"TopicName\"='%s' AND \"UserID\"=%i".$ForumIgnoreList4." ORDER BY \"LastUpdate\" DESC ".$SQLimit, array($_GET['search'],$memsid,$PageLimit,$Settings['max_topics']));
$rnquery = sql_pre_query("SELECT COUNT(*) FROM \"".$Settings['sqltable']."topics\" WHERE \"TopicName\"='%s' AND \"UserID\"=%i".$ForumIgnoreList4."", array($_GET['search']));
if($memsid==-1) {
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."topics\" WHERE \"TopicName\"='%s' AND \"GuestName\"='%s'".$ForumIgnoreList4." ORDER BY \"LastUpdate\" DESC ".$SQLimit, array($_GET['search'],$_GET['msearch'],$PageLimit,$Settings['max_topics'])); 
$rnquery = sql_pre_query("SELECT COUNT(*) FROM \"".$Settings['sqltable']."topics\" WHERE \"TopicName\"='%s' AND \"GuestName\"='%s'".$ForumIgnoreList4."", array($_GET['search'],$_GET['msearch'])); } }
if($_GET['type']=="wildcard") {
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."topics\" WHERE \"TopicName\" LIKE '%s' AND \"UserID\"=%i".$ForumIgnoreList4." ORDER BY \"LastUpdate\" DESC ".$SQLimit, array($_GET['search'],$memsid,$PageLimit,$Settings['max_topics']));
$rnquery = sql_pre_query("SELECT COUNT(*) FROM \"".$Settings['sqltable']."topics\" WHERE \"TopicName\" LIKE '%s' AND \"UserID\"=%i".$ForumIgnoreList4."", array($_GET['search'],$_GET['msearch']));
if($memsid==-1) {
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."topics\" WHERE \"TopicName\" LIKE '%s' AND \"GuestName\"='%s'".$ForumIgnoreList4." ORDER BY \"LastUpdate\" DESC ".$SQLimit, array($_GET['search'],$_GET['msearch'],$PageLimit,$Settings['max_topics'])); 
$rnquery = sql_pre_query("SELECT COUNT(*) FROM \"".$Settings['sqltable']."topics\" WHERE \"TopicName\" LIKE '%s' AND \"GuestName\"='%s'".$ForumIgnoreList4."", array($_GET['search'],$_GET['msearch'])); } } }
$result=sql_query($query,$SQLStat);
$rnresult=sql_query($rnquery,$SQLStat);
$NumberTopics = sql_result($rnresult,0);
sql_free_result($rnresult);
if($NumberTopics==null) { 
	$NumberTopics = 0; }
$num = $NumberTopics;
//Start Topic Page Code
if(!isset($Settings['max_topics'])) { $Settings['max_topics'] = 10; }
if($_GET['page']==null) { $_GET['page'] = 1; } 
if($_GET['page']<=0) { $_GET['page'] = 1; }
$nums = $_GET['page'] * $Settings['max_topics'];
if($nums>$num) { $nums = $num; }
$numz = $nums - $Settings['max_topics'];
if($numz<=0) { $numz = 0; }
//$i=$numz;
if($nums<$num) { $nextpage = $_GET['page'] + 1; }
if($nums>=$num) { $nextpage = $_GET['page']; }
if($numz>=$Settings['max_topics']) { $backpage = $_GET['page'] - 1; }
if($_GET['page']<=1) { $backpage = 1; }
$pnum = $num; $l = 1; $Pages = null;
while ($pnum>0) {
if($pnum>=$Settings['max_topics']) { 
	$pnum = $pnum - $Settings['max_topics']; 
	$Pages[$l] = $l; ++$l; }
if($pnum<$Settings['max_topics']&&$pnum>0) { 
	$pnum = $pnum - $pnum; 
	$Pages[$l] = $l; ++$l; } }
//End Topic Page Code
$num=sql_num_rows($result);
if($num<=0) { 
redirect("location",$rbasedir.url_maker($exfile['search'],$Settings['file_ext'],"act=topics",$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'],false));
header("Content-Type: text/plain; charset=".$Settings['charset']);
ob_clean(); echo "Sorry could not find any search results."; sql_free_result($result);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
$i=0;
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
if($num==0) {
$pagenumi = 0;
if($_GET['msearch']==null) { 
$pstring = null; }
if($_GET['msearch']!=null) {
$pstring = null; }
}
if($pagenum>1) {
while ($pagei < $pagenumi) {
if($_GET['msearch']==null) {
if($_GET['page']!=1&&$pagei==1) {
$Pback = $_GET['page'] - 1;
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&page=".$Pback,$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">&lt;</a></span> "; } }
if($_GET['msearch']!=null) {
if($_GET['page']!=1&&$pagei==1) {
$Pback = $_GET['page'] - 1;
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&msearch=".$_GET['msearch']."&page=".$Pback,$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">&lt;</a></span> "; } }
if($Pagez[$pagei]!=null&&
   $Pagez[$pagei]!="First"&&
   $Pagez[$pagei]!="Last") {
if($_GET['msearch']==null) {
if($pagei!=3) { 
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&page=".$Pagez[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">".$Pagez[$pagei]."</a></span> "; }
if($pagei==3) { 
$pstring = $pstring."<span class=\"pagecurrent\"><a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&page=".$Pagez[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">".$Pagez[$pagei]."</a></span> "; } }
if($_GET['msearch']!=null) {
if($pagei!=3) { 
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&msearch=".$_GET['msearch']."&page=".$Pagez[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">".$Pagez[$pagei]."</a></span> "; } 
if($pagei==3) { 
$pstring = $pstring."<span class=\"pagecurrent\"><a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&msearch=".$_GET['msearch']."&page=".$Pagez[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">".$Pagez[$pagei]."</a></span> "; } } }
if($Pagez[$pagei]=="First") {
if($_GET['msearch']==null) {
$pstring = $pstring."<span class=\"pagelinklast\"><a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">&laquo;</a></span> "; }
if($_GET['msearch']!=null) {
$pstring = $pstring."<span class=\"pagelinklast\"><a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&msearch=".$_GET['msearch']."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">&laquo;</a></span> "; } }
if($Pagez[$pagei]=="Last") {
if($_GET['msearch']==null) {
$ptestnext = $pagenext + 1;
$paget = $pagei - 1;
$Pnext = $_GET['page'] + 1;
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&page=".$Pnext,$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">&gt;</a></span> "; 
if($ptestnext<$pagenum) {
$pstring = $pstring."<span class=\"pagelinklast\"><a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&page=".$pagenum,$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">&raquo;</a></span> "; } }
if($_GET['msearch']!=null) {
$ptestnext = $pagenext + 1;
$paget = $pagei - 1;
$Pnext = $_GET['page'] + 1;
$pstring = $pstring."<span class=\"pagelink\"><a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&msearch=".$_GET['msearch']."&page=".$Pnext,$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">&gt;</a></span> "; 
if($ptestnext<$pagenum) {
$pstring = $pstring."<span class=\"pagelinklast\"><a href=\"".url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&msearch=".$_GET['msearch']."&page=".$pagenum,$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search'])."\">&raquo;</a></span> "; } } }
	++$pagei; } $pstring = $pstring."</div>"; }
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">Board index</a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['search'],$Settings['file_ext'],"act=topics",$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search']); ?>">Search topics</a></div>
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
<?php echo $ThemeSet['TitleIcon'];
if($_GET['msearch']==null) { ?>
<a href="<?php echo url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&page=".$_GET['page'],$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search']); ?>">Searching for <?php echo $_GET['search']; ?></a>
<?php } if($_GET['msearch']!=null) { ?>
<a href="<?php echo url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&msearch=".$_GET['msearch']."&page=".$_GET['page'],$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search']); ?>">Searching for <?php echo $_GET['search']; ?> by <?php echo $_GET['msearch']; ?></a>
<?php } ?></span></div>
<?php } ?>
<table class="Table1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr id="SearchStart" class="TableRow1">
<td class="TableColumn1" colspan="6"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon'];
if($_GET['msearch']==null) { ?>
<a href="<?php echo url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&page=".$_GET['page'],$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search']); ?>">Searching for <?php echo $_GET['search']; ?></a>
<?php } if($_GET['msearch']!=null) { ?>
<a href="<?php echo url_maker($exfile['search'],$Settings['file_ext'],"act=topics&search=".$_GET['search']."&type=".$_GET['type']."&msearch=".$_GET['msearch']."&page=".$_GET['page'],$Settings['qstr'],$Settings['qsep'],$prexqstr['search'],$exqstr['search']); ?>">Searching for <?php echo $_GET['search']; ?> by <?php echo $_GET['msearch']; ?></a>
<?php } ?></span>
</td>
</tr><?php } ?>
<tr id="SearchStatRow" class="TableRow2">
<th class="TableColumn2" style="width: 4%;">State</th>
<th class="TableColumn2" style="width: 36%;">Topic Name</th>
<th class="TableColumn2" style="width: 15%;">Author</th>
<th class="TableColumn2" style="width: 15%;">Time</th>
<th class="TableColumn2" style="width: 5%;">Replys</th>
<th class="TableColumn2" style="width: 25%;">Last Reply</th>
</tr>
<?php
while ($i < $num) {
$TopicID=sql_result($result,$i,"id");
$ForumID=sql_result($result,$i,"ForumID");
$prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i".$ForumIgnoreList2." LIMIT 1", array($ForumID));
$preresult=sql_query($prequery,$SQLStat);
$prenum=sql_num_rows($preresult);
$HotTopicPosts = $Settings['hot_topic_num'];
if($prenum > 0) {
$HotTopicPosts = sql_result($preresult,0,"HotTopicPosts"); }
sql_free_result($preresult);
if($HotTopicPosts!=0&&is_numeric($HotTopicPosts)) {
	$Settings['hot_topic_num'] = $HotTopicPosts; }
if(!is_numeric($Settings['hot_topic_num'])) {
	$Settings['hot_topic_num'] = 15; }
$CategoryID=sql_result($result,$i,"CategoryID");
$UsersID=sql_result($result,$i,"UserID");
$GuestsName=sql_result($result,$i,"GuestName");
$TheTime=sql_result($result,$i,"TimeStamp");
$TheTime=GMTimeChange("F j Y, g:i a",$TheTime,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$NumReply=sql_result($result,$i,"NumReply");
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
	$prepagelist = " &nbsp;"; }
if($NumberPages>=2) {
	if($ThemeSet['MiniPageAltStyle']=="off") { 
	$prepagelist = "<span class=\"small\">(Pages: "; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<span class=\"minipagelink\">"; }
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">1</a>";
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; }
	if($ThemeSet['MiniPageAltStyle']=="off") { $prepagelist = $prepagelist." "; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<span class=\"minipagelink\">"; }
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=2",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">2</a>";
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; }
	if($NumberPages>=3) {
	if($ThemeSet['MiniPageAltStyle']=="off") { $prepagelist = $prepagelist." "; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<span class=\"minipagelink\">"; }
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=3",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">3</a>";
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; } }
	if($NumberPages==4) {
	if($ThemeSet['MiniPageAltStyle']=="off") { $prepagelist = $prepagelist." "; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<span class=\"minipagelinklast\">"; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=4",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">4</a>"; }
	if($ThemeSet['MiniPageAltStyle']=="off") {
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=4",$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\"> ...4</a>"; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; } }
	if($NumberPages>4) {
	if($ThemeSet['MiniPageAltStyle']=="off") { $prepagelist = $prepagelist." "; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<span class=\"minipagelinklast\">"; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=".$NumberPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\">&raquo; ".$NumberPages."</a>"; }
	if($ThemeSet['MiniPageAltStyle']=="off") {
	$prepagelist = $prepagelist."<a href=\"".url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=".$NumberPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic'])."\"> ...".$NumberPages."</a>"; }
	if($ThemeSet['MiniPageAltStyle']=="on") {
	$prepagelist = $prepagelist."</span>"; } }
	if($ThemeSet['MiniPageAltStyle']=="off") { 
	$prepagelist = $prepagelist.")</span>"; } }
$TopicName=sql_result($result,$i,"TopicName");
$TopicDescription=sql_result($result,$i,"Description");
$PinnedTopic=sql_result($result,$i,"Pinned");
if ($PinnedTopic>2) { $PinnedTopic = 1; } 
if ($PinnedTopic<0) { $PinnedTopic = 0; }
if(!is_numeric($PinnedTopic)) { $PinnedTopic = 0; }
$TopicStat=sql_result($result,$i,"Closed");
if ($TopicStat>3) { $TopicStat = 1; } 
if ($TopicStat<0) { $TopicStat = 0; }
if(!is_numeric($TopicStat)) { $TopicStat = 1; }
$PreUsersName = GetUserName($UsersID,$Settings['sqltable'],$SQLStat);
if($PreUsersName['Name']===null) { $UsersID = -1;
$PreUsersName = GetUserName($UsersID,$Settings['sqltable'],$SQLStat); }
$UsersName = $PreUsersName['Name'];
$UsersHidden = $PreUsersName['Hidden'];
if($UsersName=="Guest") { $UsersName=$GuestsName;
if($UsersName==null) { $UsersName="Guest"; } }
if(($PermissionInfo['CanViewForum'][$ForumID]=="yes"&&
	$CatPermissionInfo['CanViewCategory'][$CategoryID]=="yes"&&
	$TopicStat>=0&&$TopicStat<3)||
	($PermissionInfo['CanViewForum'][$ForumID]=="yes"&&
	$CatPermissionInfo['CanViewCategory'][$CategoryID]=="yes"&&
	$PermissionInfo['CanModForum'][$ForumID]=="yes"&&$TopicStat==3)) {
$LastReply = "&nbsp;<br />&nbsp;";
$glrquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."posts\" WHERE \"TopicID\"=%i ORDER BY \"TimeStamp\" DESC LIMIT 1", array($TopicID));
$glrresult=sql_query($glrquery,$SQLStat);
$glrnum=sql_num_rows($glrresult);
if($glrnum>0){
$ReplyID1=sql_result($glrresult,0,"id");
$UsersID1=sql_result($glrresult,0,"UserID");
$GuestsName1=sql_result($glrresult,0,"GuestName");
$TimeStamp1=sql_result($glrresult,0,"TimeStamp");
$TimeStamp1=GMTimeChange("F j Y, g:i a",$TimeStamp1,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$PreUsersName1 = GetUserName($UsersID1,$Settings['sqltable'],$SQLStat);
if($PreUsersName1['Name']===null) { $UsersID1 = -1;
$PreUsersName1 = GetUserName($UsersID1,$Settings['sqltable'],$SQLStat); }
$UsersName1 = $PreUsersName1['Name'];
$UsersHidden1 = $PreUsersName1['Hidden']; }
$NumPages = null; $NumRPosts = $NumReply + 1;
if(!isset($Settings['max_posts'])) { $Settings['max_posts'] = 10; }
if($NumRPosts>$Settings['max_posts']) {
$NumPages = ceil($NumRPosts/$Settings['max_posts']); }
if($NumRPosts<=$Settings['max_posts']) { $NumPages = 1; }
$Users_Name1 = pre_substr($UsersName1,0,20);
if($UsersName1=="Guest") { $UsersName1=$GuestsName1;
if($UsersName1==null) { $UsersName1="Guest"; } }
if (pre_strlen($UsersName1)>20) { $Users_Name1 = $Users_Name1."...";
$oldusername=$UsersName1; $UsersName1=$Users_Name1; } $lul = null;
if($TimeStamp1!=null) { $lul = null;
if($UsersID1>0&&$UsersHidden1=="no") {
$lul = url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$UsersID1,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']);
$luln = url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=".$NumPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']).$qstrhtml."&#35;reply".$NumRPosts;
$LastReply = "Time: <a href=\"".$luln."\">".$TimeStamp1."</a><br />\nUser: <a href=\"".$lul."\" title=\"".$oldusername."\">".$UsersName1."</a>"; }
if($UsersID1<=0||$UsersHidden1=="yes") {
if($UsersID1==-1) { $UserPre = "Guest:"; }
if(($UsersID1<-1&&$UsersHidden1=="yes")||$UsersID1==0||($UsersID1>0&&$UsersHidden1=="yes")) { 
	$UserPre = "Hidden:"; }
$lul = url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']);
$luln = url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID."&page=".$NumPages,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']).$qstrhtml."&#35;reply".$NumRPosts;
$LastReply = "Time: <a href=\"".$luln."\">".$TimeStamp1."</a><br />\n".$UserPre." <span title=\"".$oldusername."\">".$UsersName1."</span>"; } }
sql_free_result($glrresult);
if($TimeStamp1==null) { $LastReply = "&nbsp;<br />&nbsp;"; }
$PreTopic = $ThemeSet['TopicIcon'];
if ($PinnedTopic>0&&$PinnedTopic<3&&$TopicStat==0) {
	if($NumReply>=$Settings['hot_topic_num']) {
		$PreTopic=$ThemeSet['HotPinTopic']; }
	if($NumReply<$Settings['hot_topic_num']) {
		$PreTopic=$ThemeSet['PinTopic']; } }
if ($TopicStat>=0&&$TopicStat<=3&&$PinnedTopic==0) {
	if($NumReply>=$Settings['hot_topic_num']) {
		$PreTopic=$ThemeSet['HotClosedTopic']; }
	if($NumReply<$Settings['hot_topic_num']) {
		$PreTopic=$ThemeSet['ClosedTopic']; } }
if ($PinnedTopic==0&&$TopicStat==0) {
		if($NumReply>=$Settings['hot_topic_num']) {
			$PreTopic=$ThemeSet['HotTopic']; }
		if($NumReply<$Settings['hot_topic_num']) {
			$PreTopic=$ThemeSet['TopicIcon']; } }
if ($PinnedTopic>0&&$PinnedTopic<3&&$TopicStat>=0&&$TopicStat<=3) {
		if($NumReply>=$Settings['hot_topic_num']) {
			$PreTopic=$ThemeSet['HotPinClosedTopic']; }
		if($NumReply<$Settings['hot_topic_num']) {
			$PreTopic=$ThemeSet['PinClosedTopic']; } }
?>
<tr class="TableRow3" id="Topic<?php echo $TopicID; ?>">
<td class="TableColumn3"><div class="topicstate">
<?php echo $PreTopic; ?></div></td>
<td class="TableColumn3"><div class="topicname">
<a href="<?php echo url_maker($exfile['topic'],$Settings['file_ext'],"act=view&id=".$TopicID,$Settings['qstr'],$Settings['qsep'],$prexqstr['topic'],$exqstr['topic']); ?>"><?php echo $TopicName; ?></a>
<?php if($prepagelist!==null) { echo $prepagelist; } ?></div>
<div class="topicdescription"><?php echo $TopicDescription; ?></div></td>
<td class="TableColumn3" style="text-align: center;"><?php
if($UsersID>0) {
echo "<a href=\"";
echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$UsersID,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']);
echo "\">".$UsersName."</a>"; }
if($UsersID<=0) {
echo "<span>".$UsersName."</span>"; }
?></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $TheTime; ?></td>
<td class="TableColumn3" style="text-align: center;"><?php echo $NumReply; ?></td>
<td class="TableColumn3"><?php echo $LastReply; ?></td>
</tr>
<?php } ++$i; }
?>
<tr id="SearchEnd" class="TableRow4">
<td class="TableColumn4" colspan="6">&nbsp;</td>
</tr>
</table></div>
<?php if($pagenum>1) { ?>
<div class="DivSearch">&nbsp;</div>
<?php }
echo $pstring;
//List Page Number Code end
if($pagenum>1) {
?>
<div class="DivPageLinks">&nbsp;</div>
<?php }
sql_free_result($result); } } } 
if($pagenum<=1) { ?>
<div class="DivSearch">&nbsp;</div>
<?php } ?>
