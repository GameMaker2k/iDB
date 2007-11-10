<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2007 Cool Dude 2k - http://intdb.sourceforge.net/
    Copyright 2004-2007 Game Maker 2k - http://upload.idb.s1.jcink.com/

    $FileInfo: pm.php - Last Update: 11/10/2007 SVN 124 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="pm.php"||$File3Name=="/pm.php") {
	require('index.php');
	exit(); }
// Check if we can read/send PM
if($_SESSION['UserGroup']==$Settings['GuestGroup']||$GroupInfo['CanPM']=="no") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
if($_GET['act']=="view"||$_GET['act']=="viewsent"||$_GET['act']=="read") {
?>
<table class="Table3">
<tr style="width: 100%; vertical-align: top;">
	<td style="width: 15%; vertical-align: top;">
	<div class="Table1Border">
	<table id="MessengerLinks" class="Table1" style="width: 100%; float: left; vertical-align: top;">
<tr class="TableRow1">
<td class="TableRow1"><?php echo $ThemeSet['TitleIcon'] ?>Messenger</td>
</tr><tr class="TableRow2">
<td class="TableRow2">&nbsp;</td>
</tr><tr class="TableRow3">
<td class="TableRow3"><a href="<?php echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=view&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); ?>">View MailBox</a></td>
</tr><tr class="TableRow3">
<td class="TableRow3"><a href="<?php echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=viewsent&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); ?>">View SentBox</a></td>
</tr><tr class="TableRow3">
<td class="TableRow3"><a href="<?php echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=create",$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); ?>">Send Message</a></td>
</tr><tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr></table></div>
</td>
	<td style="width: 85%; vertical-align: top;">
<?php
if($_GET['act']=="view") {
$query = query("SELECT * FROM `".$Settings['sqltable']."messenger` WHERE `PMSentID`=%i ORDER BY `DateSend` DESC", array($_SESSION['UserID']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
//Start MessengerList Page Code
if(!isset($Settings['max_pmlist'])) { $Settings['max_pmlist'] = 10; }
if($_GET['page']==null) { $_GET['page'] = 1; } 
if($_GET['page']<=0) { $_GET['page'] = 1; }
$nums = $_GET['page'] * $Settings['max_pmlist'];
if($nums>$num) { $nums = $num; }
$numz = $nums - $Settings['max_pmlist'];
if($numz<=0) { $numz = 0; }
$i=$numz;
if($nums<$num) { $nextpage = $_GET['page'] + 1; }
if($nums>=$num) { $nextpage = $_GET['page']; }
if($numz>=$Settings['max_pmlist']) { $backpage = $_GET['page'] - 1; }
if($_GET['page']<=1) { $backpage = 1; }
$pnum = $num; $l = 1; $Pages = null;
while ($pnum>0) {
if($pnum>=$Settings['max_pmlist']) { 
	$pnum = $pnum - $Settings['max_pmlist']; 
	$Pages[$l] = $l; ++$l; }
if($pnum<$Settings['max_pmlist']&&$pnum>0) { 
	$pnum = $pnum - $pnum; 
	$Pages[$l] = $l; ++$l; } }
//End MessengerList Page Code
//$i=0;
$pagenum=count($Pages);
$pagei=1; $pstring = "<div class=\"PageList\">Pages: ";
$Pagez[1] = 1;
if($pagenum>=2) { $Pagez[2] = 2; }
if($pagenum>=3) { $Pagez[3] = 3; }
if($pagenum>=4) { $Pagez[4] = 4; }
if($pagenum>=5&&$_GET['page']>=4) {
$page_back_one = $_GET['page']-1;
$page_now = $_GET['page'];
$page_up_one = $_GET['page']+1;
$page_up_two = $_GET['page']+2;
$page_up_three = $_GET['page']+3;
if($pagenum>=$page_now&&$page_back_one>4) { 
	$Pagez[5] = $page_back_one; }
if($pagenum>=$page_now&&$page_back_one<=4) { 
	$Pagez[5] = null; }
if($pagenum>=$page_now&&$page_now>4) { 
	$Pagez[6] = $page_now; }
if($pagenum>=$page_now&&$page_now<=4) { 
	$Pagez[6] = null; }
if($pagenum>=$page_up_one) { $Pagez[7] = $page_up_one; }
if($pagenum>=$page_up_two) { $Pagez[8] = $page_up_two; }
if($pagenum>=$page_up_three) { $Pagez[9] = $page_up_three; } }
$pagenum=count($Pagez);
while ($pagei <= $pagenum) {
if($Pagez[$pagei]!=null) {
$pstring = $pstring."<a href=\"".url_maker($exfile['messenger'],$Settings['file_ext'],"act=view&page=".$Pagez[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger'])."\">".$Pagez[$pagei]."</a> "; }
	++$pagei; } $pstring = $pstring."</div>";
echo $pstring;
?>
<div class="Table1Border">
<table class="Table1" style="width: 100%;">
<tr class="TableRow1">
<td class="TableRow1" colspan="4"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=view&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); ?>">MailBox&nbsp;(<?php echo $PMNumber; ?>)</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr id="Messenger" class="TableRow2">
<th class="TableRow2" style="width: 4%;">State</th>
<th class="TableRow2" style="width: 46%;">Message Name</th>
<th class="TableRow2" style="width: 25%;">Sender</th>
<th class="TableRow2" style="width: 25%;">Time</th>
</tr>
<?php
while ($i < $nums) {
$PMID=mysql_result($result,$i,"id");
$SenderID=mysql_result($result,$i,"SenderID");
$SenderName = GetUserName($SenderID,$Settings['sqltable']);
$SentToID=mysql_result($result,$i,"PMSentID");
$SentToName = GetUserName($SentToID,$Settings['sqltable']);
$PMGuest=mysql_result($result,$i,"GuestName");
$MessageName=mysql_result($result,$i,"MessageTitle");
$MessageDesc=mysql_result($result,$i,"Description");
$DateSend=mysql_result($result,$i,"DateSend");
$DateSend=GMTimeChange("F j, Y, g:i a",$DateSend,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$MessageStat=mysql_result($result,$i,"Read");
if($SenderName=="Guest") { $SenderName=$PMGuest;
if($SenderName==null) { $SenderName="Guest"; } }
$PreMessage = $ThemeSet['MessageUnread'];
if ($MessageStat==0) {
	$PreMessage=$ThemeSet['MessageUnread']; }
if ($MessageStat==1) {
	$PreMessage=$ThemeSet['MessageRead']; }
?>
<tr class="TableRow3" id="Message<?php echo $PMID; ?>">
<td class="TableRow3"><div class="messagestate">
<?php echo $PreMessage; ?></div></td>
<td class="TableRow3"><div class="messagename">
<a href="<?php echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=read&id=".$PMID,$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); ?>"><?php echo $MessageName; ?></a></div>
<div class="messagedesc"><?php echo $MessageDesc; ?></div></td>
<td class="TableRow3" style="text-align: center;"><?php
if($SenderID!="-1") {
echo "<a href=\"";
echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$SenderID,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']);
echo "\">".$SenderName."</a>"; }
if($SenderID=="-1") {
echo "<span>".$SenderName."</span>"; }
?></td>
<td class="TableRow3" style="text-align: center;"><?php echo $DateSend; ?></td>
</tr>
<?php ++$i; } @mysql_free_result($result); ?>
<tr id="MessengerEnd" class="TableRow4">
<td class="TableRow4" colspan="4">&nbsp;</td>
</tr>
<?php } 
if($_GET['act']=="viewsent") {
$query = query("SELECT * FROM `".$Settings['sqltable']."messenger` WHERE `SenderID`=%i ORDER BY `DateSend` DESC", array($_SESSION['UserID']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
//Start MessengerList Page Code (Will be used at later time)
if(!isset($Settings['max_pmlist'])) { $Settings['max_pmlist'] = 10; }
if($_GET['page']==null) { $_GET['page'] = 1; } 
if($_GET['page']<=0) { $_GET['page'] = 1; }
$nums = $_GET['page'] * $Settings['max_pmlist'];
if($nums>$num) { $nums = $num; }
$numz = $nums - $Settings['max_pmlist'];
if($numz<=0) { $numz = 0; }
$i=$numz;
if($nums<$num) { $nextpage = $_GET['page'] + 1; }
if($nums>=$num) { $nextpage = $_GET['page']; }
if($numz>=$Settings['max_pmlist']) { $backpage = $_GET['page'] - 1; }
if($_GET['page']<=1) { $backpage = 1; }
$pnum = $num; $l = 1; $Pages = null;
while ($pnum>0) {
if($pnum>=$Settings['max_pmlist']) { 
	$pnum = $pnum - $Settings['max_pmlist']; 
	$Pages[$l] = $l; ++$l; }
if($pnum<$Settings['max_pmlist']&&$pnum>0) { 
	$pnum = $pnum - $pnum; 
	$Pages[$l] = $l; ++$l; } }
//End MessengerList Page Code (Its not used yet but its still good to have :P )
$i=0;
$pagenum=count($Pages);
$pagei=1; $pstring = "<div class=\"PageList\">Pages: ";
while ($pagei <= $pagenum) {
$pstring = $pstring."<a href=\"".url_maker($exfile['messenger'],$Settings['file_ext'],"act=viewsent&page=".$Pages[$pagei],$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger'])."\">".$Pages[$pagei]."</a> ";
	++$pagei; } $pstring = $pstring."</div>";
echo $pstring;
?>
<div class="Table1Border">
<table class="Table1" style="width: 100%;">
<tr class="TableRow1">
<td class="TableRow1" colspan="4"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=view&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); ?>">MailBox&nbsp;(<?php echo $PMNumber; ?>)</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr id="Messenger" class="TableRow2">
<th class="TableRow2" style="width: 4%;">State</th>
<th class="TableRow2" style="width: 46%;">Message Name</th>
<th class="TableRow2" style="width: 25%;">Sent To</th>
<th class="TableRow2" style="width: 25%;">Time</th>
</tr>
<?php
while ($i < $nums) {
$PMID=mysql_result($result,$i,"id");
$SenderID=mysql_result($result,$i,"SenderID");
$SenderName = GetUserName($SenderID,$Settings['sqltable']);
$SentToID=mysql_result($result,$i,"PMSentID");
$SentToName = GetUserName($SentToID,$Settings['sqltable']);
$PMGuest=mysql_result($result,$i,"GuestName");
$MessageName=mysql_result($result,$i,"MessageTitle");
$MessageDesc=mysql_result($result,$i,"Description");
$DateSend=mysql_result($result,$i,"DateSend");
$DateSend=GMTimeChange("F j, Y, g:i a",$DateSend,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$MessageStat=mysql_result($result,$i,"Read");
if($SenderName=="Guest") { $SenderName=$PMGuest;
if($SenderName==null) { $SenderName="Guest"; } }
$PreMessage = $ThemeSet['MessageUnread'];
if ($MessageStat==0) {
	$PreMessage=$ThemeSet['MessageUnread']; }
if ($MessageStat==1) {
	$PreMessage=$ThemeSet['MessageRead']; }
?>
<tr class="TableRow3" id="Message<?php echo $PMID; ?>">
<td class="TableRow3"><div class="messagestate">
<?php echo $PreMessage; ?></div></td>
<td class="TableRow3"><div class="messagename">
<a href="<?php echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=read&id=".$PMID,$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); ?>"><?php echo $MessageName; ?></a></div>
<div class="messagedesc"><?php echo $MessageDesc; ?></div></td>
<td class="TableRow3" style="text-align: center;"><?php
if($SentToID!="-1") {
echo "<a href=\"";
echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$SentToID,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']);
echo "\">".$SentToName."</a>"; }
if($SentToID=="-1") {
echo "<span>".$SentToName."</span>"; }
?></td>
<td class="TableRow3" style="text-align: center;"><?php echo $DateSend; ?></td>
</tr>
<?php ++$i; } ?>
<tr id="MessengerEnd" class="TableRow4">
<td class="TableRow4" colspan="4">&nbsp;</td>
</tr>
<?php } @mysql_free_result($result);
if($_GET['act']=="read") {
$query = query("SELECT * FROM `".$Settings['sqltable']."messenger` WHERE `id`=%i", array($_GET['id']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
$is=0;
if($num==0) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
while ($is < $num) {
$PMID=mysql_result($result,$is,"id");
$SenderID=mysql_result($result,$is,"SenderID");
$SenderName = GetUserName($SenderID,$Settings['sqltable']);
$SentToID=mysql_result($result,$is,"PMSentID");
$SentToName = GetUserName($SentToID,$Settings['sqltable']);
$PMGuest=mysql_result($result,$is,"GuestName");
$MessageName=mysql_result($result,$is,"MessageTitle");
$DateSend=mysql_result($result,$is,"DateSend");
$DateSend=GMTimeChange("F j, Y, g:i a",$DateSend,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$MessageText=mysql_result($result,$is,"MessageText");
$MessageText = preg_replace("/\<br\>/", "<br />\n", nl2br($MessageText));
$MessageDesc=mysql_result($result,$is,"Description");
$requery = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i", array($SenderID));
$reresult=mysql_query($requery);
$renum=mysql_num_rows($reresult);
$rei=0;
if($_SESSION['UserID']!=$SentToID&&
	$_SESSION['UserID']!=$SenderID) {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
while ($rei < $renum) {
$User1ID=$SenderID;
$User1Name=mysql_result($reresult,$rei,"Name");
$User1Email=mysql_result($reresult,$rei,"Email");
$User1Title=mysql_result($reresult,$rei,"Title");
$User1Joined=mysql_result($reresult,$rei,"Joined");
$User1Joined=GMTimeChange("M j Y",$User1Joined,$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$User1GroupID=mysql_result($reresult,$rei,"GroupID");
$gquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i", array($User1GroupID));
$gresult=mysql_query($gquery);
$User1Group=mysql_result($gresult,0,"Name");
@mysql_free_result($gresult);
$User1Signature=mysql_result($reresult,$rei,"Signature");
$User1Avatar=mysql_result($reresult,$rei,"Avatar");
$User1AvatarSize=mysql_result($reresult,$rei,"AvatarSize");
if ($User1Avatar=="http://"||$User1Avatar==null) {
$User1Avatar=$ThemeSet['NoAvatar'];
$User1AvatarSize=$ThemeSet['NoAvatarSize']; }
$AvatarSize1=explode("x", $User1AvatarSize);
$AvatarSize1W=$AvatarSize1[0]; $AvatarSize1H=$AvatarSize1[1];
$User1Website=mysql_result($reresult,$rei,"Website");
$User1PostCount=mysql_result($reresult,$rei,"PostCount");
$User1IP=mysql_result($reresult,$rei,"IP");
++$rei; } @mysql_free_result($reresult);
++$is; } @mysql_free_result($result);
if($_SESSION['UserID']==$SentToID) {
$queryup = query("UPDATE `".$Settings['sqltable']."messenger` SET `Read`=%i WHERE `id`=%i", array(1,$_GET['id']));
mysql_query($queryup); }
if($User1Name=="Guest") { $User1Name=$PMGuest;
if($User1Name==null) { $User1Name="Guest"; } }
$MessageText = text2icons($MessageText,$Settings['sqltable']);
$User1Signature = preg_replace("/\<br\>/", "<br />\n", nl2br($User1Signature));
$User1Signature = text2icons($User1Signature,$Settings['sqltable']);
?>
<div class="Table1Border">
<table class="Table1" style="width: 100%;">
<tr class="TableRow1">
<td class="TableRow1" colspan="2"><span style="font-weight: bold; float: left;"><?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=view&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); ?>"><?php echo $MessageName; ?></a> ( <?php echo $MessageDesc; ?> )</span>
<span style="float: right;">&nbsp;</span></td>
</tr>
<tr class="TableRow2">
<td class="TableRow2" style="vertical-align: middle; width: 160px;">
&nbsp;<?php
if($User1ID!="-1") {
echo "<a href=\"";
echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$User1ID,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']);
echo "\">".$User1Name."</a>"; }
if($User1ID=="-1") {
echo "<span>".$User1Name."</span>"; }
?></td>
<td class="TableRow2" style="vertical-align: middle;">
<div style="text-align: left; float: left;">
<span style="font-weight: bold;">Time Sent: </span><?php echo $DateSend; ?>
</div>
<div style="text-align: right;">&nbsp;</div>
</td>
</tr>
<tr>
<td class="TableRow3" style="vertical-align: top; width: 180px;">
 <?php  /* Avatar Table Thanks For SeanJ's Help at http://seanj.jcink.com/ */  ?>
 <table class="AvatarTable" style="width: 100px; height: 100px; text-align: center;">
	<tr class="AvatarRow" style="width: 100%; height: 100%;">
		<td class="AvatarRow" style="width: 100%; height: 100%; text-align: center; vertical-align: middle;">
		<img src="<?php echo $User1Avatar; ?>" alt="<?php echo $User1Name; ?>'s Avatar" title="<?php echo $User1Name; ?>'s Avatar" style="border: 0px; width: <?php echo $AvatarSize1W; ?>px; height: <?php echo $AvatarSize1H; ?>px;" />
		</td>
	</tr>
 </table><br />
User Title: <?php echo $User1Title; ?><br />
Group: <?php echo $User1Group; ?><br />
Member: <?php 
if($User1ID!="-1") { echo $User1ID; }
if($User1ID=="-1") { echo 0; }
?><br />
Posts: <?php echo $User1PostCount; ?><br />
Joined: <?php echo $User1Joined; ?><br /><br />
</td>
<td class="TableRow3" style="vertical-align: middle;">
<div class="pmpost"><?php echo $MessageText; ?></div>
<?php if(isset($User1Signature)) { ?> <br />--------------------
<div class="signature"><?php echo $User1Signature; ?></div><?php } ?>
</td>
</tr>
<tr class="TableRow4">
<td class="TableRow4" colspan="2">
<span style="float: left;">&nbsp;<a href="<?php
if($User1ID!="-1") {
echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$User1ID,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); }
if($User1ID=="-1") {
echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); }
?>"><?php echo $ThemeSet['Profile']; ?></a><?php echo $ThemeSet['LineDividerTopic']; ?><a href="<?php echo $User1Website; ?>" onclick="window.open(this.href);return false;"><?php echo $ThemeSet['WWW']; ?></a><?php echo $ThemeSet['LineDividerTopic']; ?><a href="<?php
if($User1ID!="-1") {
echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=create&id=".$User1ID,$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); }
if($User1ID=="-1") {
echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); }
?>"><?php echo $ThemeSet['PM']; ?></a></span>
<span style="float: right;">&nbsp;</span></td></tr>
<?php } ?>
</table></div>
</td></tr>
</table>
<?php } if($_GET['act']=="create") { 
$SendMessageTo = null;
if($_GET['id']!=null&&$_GET['id']!="-1") {
$requery = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i", array($_GET['id']));
$reresult=mysql_query($requery);
$renum=mysql_num_rows($reresult);
$rei=0;
while ($rei < $renum) {
$SendMessageTo = mysql_result($reresult,$rei,"Name");
$SendMessageTo = htmlspecialchars($SendMessageTo, ENT_QUOTES, $Settings['charset']);
$SendToGroupID = mysql_result($reresult,$rei,"GroupID");
++$rei; } } @mysql_free_result($reresult);
if(!isset($renum)) { $renum = 0; }
if($renum==0) { $SendMessageTo = null; }
?>
<div class="Table1Border">
<table class="Table1" id="MakeMessage">
<tr class="TableRow1" id="MessageStart">
<td class="TableRow1" colspan="2"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=create",$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); ?>">Seanding a Message</a></span>
<?php echo "<span style=\"float: right;\">&nbsp;</span>"; ?></td>
</tr>
<tr id="MakeMessageRow" class="TableRow2">
<td class="TableRow2" colspan="2" style="width: 100%;">Making a Message</td>
</tr>
<tr class="TableRow3" id="MkMessage">
<td class="TableRow3" style="width: 15%; vertical-align: middle; text-align: center;">
<div style="width: 100%; height: 160px; overflow: auto;">
<table style="width: 100%; text-align: center;"><?php
$renee_query=query("SELECT * FROM `".$Settings['sqltable']."smileys` WHERE `Show`='yes'", array(null));
$renee_result=mysql_query($renee_query);
$renee_num=mysql_num_rows($renee_result);
$renee_s=0; $SmileRow=0; $SmileCRow=0;
while ($renee_s < $renee_num) { ++$SmileRow;
$FileName=mysql_result($renee_result,$renee_s,"FileName");
$SmileName=mysql_result($renee_result,$renee_s,"SmileName");
$SmileText=mysql_result($renee_result,$renee_s,"SmileText");
$SmileDirectory=mysql_result($renee_result,$renee_s,"Directory");
$ShowSmile=mysql_result($renee_result,$renee_s,"Show");
$ReplaceType=mysql_result($renee_result,$renee_s,"ReplaceCI");
if($SmileRow==1) { ?><tr>
	<?php } if($SmileRow<5) { ++$SmileCRow; ?>
	<td>&nbsp;<img src="<?php echo $SmileDirectory."".$FileName; ?>" style="vertical-align: middle; border: 0px; cursor: pointer;" title="<?php echo $SmileName; ?>" alt="<?php echo $SmileName; ?>" onclick="addsmiley('ReplyPost','&nbsp;<?php echo htmlspecialchars($SmileText, ENT_QUOTES, $Settings['charset']); ?>&nbsp;')" />&nbsp;</td>
	<?php } if($SmileRow==5) { ++$SmileCRow; ?>
	<td>&nbsp;<img src="<?php echo $SmileDirectory."".$FileName; ?>" style="vertical-align: middle; border: 0px; cursor: pointer;" title="<?php echo $SmileName; ?>" alt="<?php echo $SmileName; ?>" onclick="addsmiley('ReplyPost','&nbsp;<?php echo htmlspecialchars($SmileText, ENT_QUOTES, $Settings['charset']); ?>&nbsp;')" />&nbsp;</td></tr>
	<?php $SmileCRow=0; $SmileRow=0; }
++$renee_s; }
if($SmileCRow<5&&$SmileCRow!=0) {
$SmileCRowL = 5 - $SmileCRow;
echo "<td colspan=\"".$SmileCRowL."\">&nbsp;</td></tr>"; }
echo "</table>";
@mysql_free_result($renee_result);
?></div></td>
<td class="TableRow3" style="width: 85%;">
<form style="display: inline;" method="post" id="MkReplyForm" action="<?php echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=sendmessage",$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="SendMessageTo">Insert UserName:</label></td>
	<td style="width: 50%;"><input maxlength="25" type="text" name="SendMessageTo" class="TextBox" id="SendMessageTo" size="20" value="<?php echo $SendMessageTo; ?>" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="MessageName">Insert Message Name:</label></td>
	<td style="width: 50%;"><input maxlength="30" type="text" name="MessageName" class="TextBox" id="MessageName" size="20" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="MessageDesc">Insert Message Description:</label></td>
	<td style="width: 50%;"><input maxlength="45" type="text" name="MessageDesc" class="TextBox" id="MessageDesc" size="20" /></td>
</tr><?php if($_SESSION['UserGroup']==$Settings['GuestGroup']) { ?><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="GuestName">Insert Guest Name:</label></td>
	<td style="width: 50%;"><input maxlength="25" type="text" name="GuestName" class="TextBox" id="GuestName" size="20" /></td>
</tr><?php } ?>
</table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<label class="TextBoxLabel" for="Message">Insert Your Message:</label><br />
<textarea rows="10" name="Message" id="Message" cols="40" class="TextBox"></textarea><br />
<input type="hidden" name="act" value="sendmessages" style="display: none;" />
<?php if($_SESSION['UserGroup']!=$Settings['GuestGroup']) { ?>
<input type="hidden" name="GuestName" value="null" style="display: none;" />
<?php } ?>
<input type="submit" class="Button" value="Send Message" name="send_message" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form></td></tr>
<tr id="MkReplyEnd" class="TableRow4">
<td class="TableRow4" colspan="2">&nbsp;</td>
</tr>
</table></div>
<?php } if($_GET['act']=="sendmessage"&&$_POST['act']=="sendmessages") {
$REFERERurl = parse_url($_SERVER['HTTP_REFERER']);
$URL['REFERER'] = $REFERERurl['host'];
$URL['HOST'] = $_SERVER["SERVER_NAME"];
$REFERERurl = null; unset($REFERERurl);
if(!isset($_POST['SendMessageTo'])) { $_POST['SendMessageTo'] = null; }
if(!isset($_POST['MessageName'])) { $_POST['MessageName'] = null; }
if(!isset($_POST['MessageDesc'])) { $_POST['MessageDesc'] = null; }
if(!isset($_POST['Message'])) { $_POST['Message'] = null; }
if(!isset($_POST['GuestName'])) { $_POST['GuestName'] = null; }
?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=sendmessage",$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); ?>">Making a Message</a></span>
<?php echo "<span style=\"float: right;\">&nbsp;</span>"; ?></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">&nbsp;Make Message: </th>
</tr>
<?php if (strlen($_POST['SendMessageTo'])>="25") { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Send to user name too big.<br />
	</span></td>
</tr>
<?php } if ($_POST['SendMessageTo']==null) { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter a user name to send message to.<br />
	</span></td>
</tr>
<?php } if (strlen($_POST['MessageName'])>="30") { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Message Name is too big.<br />
	</span></td>
</tr>
<?php } if (strlen($_POST['MessageDesc'])>="45") { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Message Description is too big.<br />
	</span></td>
</tr>
<?php } if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	strlen($_POST['GuestName'])>="25") { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You Guest Name is too big.<br />
	</span></td>
</tr>
<?php } if ($Settings['TestReferer']==true) {
	if ($URL['HOST']!=$URL['REFERER']) { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Sorry the referering url dose not match our host name.<br />
	</span></td>
</tr>
<?php } }
$_POST['MessageName'] = stripcslashes(htmlspecialchars($_POST['MessageName'], ENT_QUOTES, $Settings['charset']));
//$_POST['MessageName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['MessageName']);
$_POST['MessageName'] = @remove_spaces($_POST['MessageName']);
$_POST['MessageDesc'] = stripcslashes(htmlspecialchars($_POST['MessageDesc'], ENT_QUOTES, $Settings['charset']));
//$_POST['MessageDesc'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['MessageDesc']);
$_POST['MessageDesc'] = @remove_spaces($_POST['MessageDesc']);
$_POST['SendMessageTo'] = stripcslashes(htmlspecialchars($_POST['SendMessageTo'], ENT_QUOTES, $Settings['charset']));
//$_POST['SendMessageTo'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['SendMessageTo']);
$_POST['SendMessageTo'] = @remove_spaces($_POST['SendMessageTo']);
$_POST['GuestName'] = stripcslashes(htmlspecialchars($_POST['GuestName'], ENT_QUOTES, $Settings['charset']));
//$_POST['GuestName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['GuestName']);
$_POST['GuestName'] = @remove_spaces($_POST['GuestName']);
$_POST['Message'] = stripcslashes(htmlspecialchars($_POST['Message'], ENT_QUOTES, $Settings['charset']));
//$_POST['Message'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['Message']);
//$_POST['Message'] = @remove_spaces($_POST['Message']);
$_POST['Message'] = remove_bad_entities($_POST['Message']);
$requery = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `Name`='%s'", array($_POST['SendMessageTo']));
$reresult=mysql_query($requery);
$renum=mysql_num_rows($reresult);
$rei=0;
while ($rei < $renum) {
$SendMessageToID = mysql_result($reresult,$rei,"id");
$SendToGroupID = mysql_result($reresult,$rei,"GroupID");
$gquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i", array($SendToGroupID));
$gresult=mysql_query($gquery);
$SendUserCanPM=mysql_result($gresult,0,"CanPM");
$SendUserCanPM = strtolower($SendUserCanPM);
if($SendUserCanPM!="yes"&&$SendUserCanPM!="no") {
	$SendUserCanPM = "no"; }
@mysql_free_result($gresult);
++$rei; } @mysql_free_result($reresult);
if($renum==0) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Cound not find users name.<br />
	</span></td>
</tr>
<?php } if ($_POST['MessageName']==null) { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter a Message Name.<br />
	</span></td>
</tr>
<?php } if ($_POST['MessageDesc']==null) { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter a Message Description.<br />
	</span></td>
</tr>
<?php } if ($SendUserCanPM=="no") { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />User Name enter can not get messages.<br />
	</span></td>
</tr>
<?php } if ($_POST['Message']==null) { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter a Message.<br />
	</span></td>
</tr>
<?php } if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	$_POST['GuestName']==null) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter a Guest Name.<br />
	</span></td>
</tr>
<?php } if ($Error=="Yes") {
@redirect("refresh",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false),"4"); }
if ($Error!="Yes") { $LastActive = GMTimeStamp();
$messageid = getnextid($Settings['sqltable'],"messenger");
if($_SESSION['UserGroup']==$Settings['GuestGroup']) { $User1Name = $_POST['GuestName']; }
if($_SESSION['UserGroup']!=$Settings['GuestGroup']) { $User1Name = $_SESSION['MemberName']; }
$query = query("INSERT INTO `".$Settings['sqltable']."messenger` VALUES (".$messageid.",%i,%i,'%s','%s','%s','%s',%i,%i)", array($_SESSION['UserID'],$SendMessageToID,$_SESSION['MemberName'],$_POST['MessageName'],$_POST['Message'],$_POST['MessageDesc'],$LastActive,0));
mysql_query($query);
?><tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage"><br />
	Message sent to user <?php echo $_POST['SendMessageTo']; ?>.<br />
	Click <a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">here</a> to go back to index.<br />&nbsp;
	</span><br /></td>
</tr>
<?php } ?>
<tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table></div>
<?php } ?>
<div>&nbsp;</div>