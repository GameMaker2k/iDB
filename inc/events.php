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

    $FileInfo: events.php - Last Update: 8/26/2024 SVN 1048 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="events.php"||$File3Name=="/events.php") {
	require('index.php');
	exit(); }
if(!is_numeric($_GET['id'])) { $_GET['id'] = null; }
if($_GET['act']=="view"||$_GET['act']==null) {
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."events\" WHERE \"id\"=%i LIMIT 1", array($_GET['id']));
$result=sql_query($query,$SQLStat);
$num=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."events\" WHERE \"id\"=%i LIMIT 1", array($_GET['id'])), $SQLStat);
$is=0;
if($num==0) { redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); sql_free_result($result);
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['event'],$Settings['file_ext'],"act=view&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['event'],$exqstr['event']); ?>">Viewing Event</a></div>
<div class="DivNavLinks">&#160;</div>
<?php
while ($is < $num) {
$result_array = sql_fetch_assoc($result);
$EventID=$result_array['id'];
$EventIP=$result_array['IP'];
$EventUser=$result_array['UserID'];
$EventGuest=$result_array['GuestName'];
$EventName=$result_array['EventName'];
$EventText=$result_array['EventText'];
$EventStart=$result_array['TimeStamp'];
$EventEnd=$result_array['TimeStampEnd'];
$eventstartcurtime = new DateTime();
$eventstartcurtime->setTimestamp($EventStart);
$eventstartcurtime->setTimezone($usertz);
$EventStart = $eventstartcurtime->format($_SESSION['iDBDateFormat']);
$eventendcurtime = new DateTime();
$eventendcurtime->setTimestamp($EventEnd);
$eventendcurtime->setTimezone($usertz);
$EventEnd = $eventendcurtime->format($_SESSION['iDBDateFormat']);
$ipshow = "two";
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
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=view&id=".$_GET['id'],"&","=",$prexqstr['event'],$exqstr['event']);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['event'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['event']; }
$_SESSION['PreViewingTitle'] = "Viewing Event:";
$_SESSION['ViewingTitle'] = $EventName;
$_SESSION['ExtraData'] = "currentact:".$_GET['act']."; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:".$EventID.";";
$requery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($EventUser));
$reresult=sql_query($requery,$SQLStat);
$renum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($EventUser)), $SQLStat);
if($renum<1) { $EventUser = -1;
$requery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($EventUser));
$reresult=sql_query($requery,$SQLStat);
$renum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($EventUser)), $SQLStat); }
$memrequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."mempermissions\" WHERE \"id\"=%i LIMIT 1", array($EventUser));
$memreresult=sql_query($memrequery,$SQLStat);
$memrenum=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."mempermissions\" WHERE \"id\"=%i LIMIT 1", array($EventUser)), $SQLStat);
$rei=0;
$User1ID=$EventUser;
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
$User1Name=$reresult_array['Name'];
$User1IP=$reresult_array['IP'];
if($User1IP==$EventIP) { $ipshow = "one"; }
$User1Email=$reresult_array['Email'];
$User1Title=$reresult_array['Title'];
$memreresult_array = sql_fetch_assoc($memreresult);
$PreUserCanExecPHP=$memreresult_array['CanExecPHP'];
if($PreUserCanExecPHP!="yes"&&$PreUserCanExecPHP!="no"&&$PreUserCanExecPHP!="group") {
	$PreUserCanExecPHP = "no"; }
$PreUserCanDoHTML=$memreresult_array['CanDoHTML'];
if($PreUserCanDoHTML!="yes"&&$PreUserCanDoHTML!="no"&&$PreUserCanDoHTML!="group") {
	$PreUserCanDoHTML = "no"; }
$PreUserCanUseBBTags=$memreresult_array['CanUseBBTags'];
if($PreUserCanUseBBTags!="yes"&&$PreUserCanUseBBTags!="no"&&$PreUserCanUseBBTags!="group") {
	$PreUserCanUseBBTags = "no"; }
sql_free_result($memreresult);
$User1Joined=$reresult_array['Joined'];
$tmpusrcurtime = new DateTime();
$tmpusrcurtime->setTimestamp($User1Joined);
$tmpusrcurtime->setTimezone($usertz);
$User1Joined=$tmpusrcurtime->format($_SESSION['iDBDateFormat']);
$User1GroupID=$reresult_array['GroupID'];
$gquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($User1GroupID));
$gresult=sql_query($gquery,$SQLStat);
$User1Hidden=$reresult_array['HiddenMember'];
$gresult_array = sql_fetch_assoc($gresult);
$User1Group=$gresult_array['Name'];
$User1CanExecPHP = $PreUserCanExecPHP;
if($PreUserCanExecPHP=="group") {
$User1CanExecPHP=$gresult_array['CanExecPHP']; }
if($User1CanExecPHP!="yes"&&$User1CanExecPHP!="no") {
	$User1CanExecPHP = "no"; }
$User1CanDoHTML = $PreUserCanDoHTML;
if($PreUserCanDoHTML=="group") {
$User1CanDoHTML=$gresult_array['CanDoHTML']; }
if($User1CanDoHTML!="yes"&&$User1CanDoHTML!="no") {
	$User1CanDoHTML = "no"; }
$User1CanUseBBTags = $PreUserCanUseBBTags;
if($User1CanUseBBTags=="group") {
$User1CanUseBBTags=$gresult_array['CanUseBBTags']; }
if($User1CanUseBBTags!="yes"&&$User1CanUseBBTags!="no") {
	$User1CanUseBBTags = "no"; }
$GroupNamePrefix=$gresult_array['NamePrefix'];
$GroupNameSuffix=$gresult_array['NameSuffix'];
sql_free_result($gresult);
if($User1Title=="") { $User1Title = $User1Group; }
$User1Signature=$reresult_array['Signature'];
$User1Signature = preg_replace("/\<br\>/", "<br />", nl2br($User1Signature));
$User1Avatar=$reresult_array['Avatar'];
$User1AvatarSize=$reresult_array['AvatarSize'];
if ($User1Avatar=="http://"||$User1Avatar==null||
	strtolower($User1Avatar)=="noavatar") {
$User1Avatar=$ThemeSet['NoAvatar'];
$User1AvatarSize=$ThemeSet['NoAvatarSize']; }
$AvatarSize1=explode("x", $User1AvatarSize);
$AvatarSize1W=$AvatarSize1[0]; $AvatarSize1H=$AvatarSize1[1];
$User1Website=$reresult_array['Website'];
if($User1Website=="http://") { 
	$User1Website = $Settings['idburl']; }
$User1Website = urlcheck($User1Website);
$BoardWWWChCk = parse_url($Settings['idburl']);
$User1WWWChCk = parse_url($User1Website);
$opennew = " onclick=\"window.open(this.href);return false;\"";
if($BoardWWWChCk['host']==$User1WWWChCk['host']) {
	$opennew = null; }
$User1PostCount=$reresult_array['PostCount'];
$User1IP=$reresult_array['IP'];
sql_free_result($reresult);
++$is; } sql_free_result($result);
if($User1Name=="Guest") { $User1Name=$EventGuest;
if($User1Name==null) { $User1Name="Guest"; } }
if(isset($GroupNamePrefix)&&$GroupNamePrefix!=null) {
	$User1Name = $GroupNamePrefix.$User1Name; }
if(isset($GroupNameSuffix)&&$GroupNameSuffix!=null) {
	$User1Name = $User1Name.$GroupNameSuffix; }
if($User1CanUseBBTags=="yes") { $EventText = bbcode_parser($EventText); }
if($User1CanExecPHP=="no") {
$EventText = preg_replace("/\[ExecPHP\](.*?)\[\/ExecPHP\]/is","<span style=\"color: red; font-weight: bold;\">ERROR:</span> cannot execute php code.",$EventText); }
if($User1CanExecPHP=="yes") { $EventText = php_execute($EventText); }
if($User1CanDoHTML=="no") {
$EventText = preg_replace("/\[DoHTML\](.*?)\[\/DoHTML\]/is","<span style=\"color: red; font-weight: bold;\">ERROR:</span> cannot execute html.",$EventText); }
if($User1CanDoHTML=="yes") { $EventText = do_html_bbcode($EventText); }
$EventText = text2icons($EventText,$Settings['sqltable'],$SQLStat);
$EventText = preg_replace("/\<br\>/", "<br />", nl2br($EventText));
$EventText = url2link($EventText);
if($User1CanUseBBTags=="yes") { $User1Signature = bbcode_parser($User1Signature); }
if($User1CanExecPHP=="no") {
$User1Signature = preg_replace("/\[ExecPHP\](.*?)\[\/ExecPHP\]/is","<span style=\"color: red; font-weight: bold;\">ERROR:</span> cannot execute php code.",$User1Signature); }
if($User1CanExecPHP=="yes") { $User1Signature = php_execute($User1Signature); }
if($User1CanDoHTML=="no") {
$User1Signature = preg_replace("/\[DoHTML\](.*?)\[\/DoHTML\]/is","<span style=\"color: red; font-weight: bold;\">ERROR:</span> cannot execute html.",$User1Signature); }
if($User1CanDoHTML=="yes") { $User1Signature = do_html_bbcode($User1Signature); }
$User1Signature = text2icons($User1Signature,$Settings['sqltable'],$SQLStat);
$User1Signature = preg_replace("/\<br\>/", "<br />", nl2br($User1Signature));
$User1Signature = url2link($User1Signature);
?>
<div class="TableInfo1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableInfoRow1">
<span style="font-weight: bold; text-align: left;"><?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['event'],$Settings['file_ext'],"act=view&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['event'],$exqstr['event']); ?>"><?php echo $EventName; ?></a></span></div>
<?php } ?>
<table class="TableInfo1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableInfoRow1">
<td class="TableInfoColumn1" colspan="2"><span style="font-weight: bold; text-align: left;"><?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['event'],$Settings['file_ext'],"act=view&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['event'],$exqstr['event']); ?>"><?php echo $EventName; ?></a></span>
</td>
</tr><?php } ?>
<tr class="TableInfoRow2">
<td class="TableInfoColumn2" style="vertical-align: middle; width: 160px;">
&#160;<?php
if($User1ID>0&&$User1Hidden=="no") {
echo "<a href=\"";
echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$User1ID,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']);
echo "\">".$User1Name."</a>"; }
if($User1ID<=0||$User1Hidden=="yes") {
echo "<span>".$User1Name."</span>"; }
?></td>
<td class="TableInfoColumn2" style="vertical-align: middle;">
<div style="float: left; text-align: left;">
<span style="font-weight: bold;">Event Start: </span><?php echo $EventStart; ?><?php echo $ThemeSet['LineDividerTopic']; ?><span style="font-weight: bold;">Event End: </span><?php echo $EventEnd; ?>
</div>
<div style="text-align: right;">&#160;</div>
</td>
</tr>
<tr class="TableInfoRow3">
<td class="TableInfoColumn3" style="vertical-align: top; width: 180px;">
<?php  // Avatar Table Thanks For SeanJ's Help at http://seanj.jcink.com/  
 ?>
 <table class="AvatarTable" style="width: 100px; height: 100px; text-align: center;">
	<tr class="AvatarRow" style="width: 100%; height: 100%;">
		<td class="AvatarRow" style="width: 100%; height: 100%; text-align: center; vertical-align: middle;">
		<img src="<?php echo $User1Avatar; ?>" alt="<?php echo $User1Name; ?>'s Avatar" title="<?php echo $User1Name; ?>'s Avatar" style="border: 0px; width: <?php echo $AvatarSize1W; ?>px; height: <?php echo $AvatarSize1H; ?>px;" />
		</td>
	</tr>
 </table><br />
<?php echo $User1Title; ?><br />
Group: <?php echo $User1Group; ?><br />
Member: <?php 
if($User1ID>0&&$User1Hidden=="no") { echo $User1ID; }
if($User1ID<=0||$User1Hidden=="yes") { echo 0; }
?><br />
Posts: <?php echo $User1PostCount; ?><br />
Joined: <?php echo $User1Joined; ?><br />
<?php if($GroupInfo['CanViewIPAddress']=="yes") { ?>
User IP: <a onclick="window.open(this.href);return false;" href="<?php echo sprintf($IPCheckURL,$User1IP); ?>">
<?php echo $User1IP; ?></a><br />
<?php if($ipshow=="two") { ?>
Event IP: <a onclick="window.open(this.href);return false;" href="<?php echo sprintf($IPCheckURL,$EventIP); ?>">
<?php echo $EventIP; ?></a><br />
<?php } } ?><br />
</td>
<td class="TableInfoColumn3" style="vertical-align: middle;">
<div class="eventpost"><?php echo $EventText; ?></div>
<?php if(isset($User1Signature)&&$User1Signature!="") { ?> <br />--------------------
<div class="signature"><?php echo $User1Signature; ?></div><?php } ?>
</td>
</tr>
<tr class="TableInfoRow4">
<td class="TableInfoColumn4" colspan="2">
<span style="text-align: left;">&#160;<a href="<?php
if($User1ID>0&&$User1Hidden=="no"&&isset($ThemeSet['Profile'])&&$ThemeSet['Profile']!=null) {
echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$User1ID,$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); }
if(($User1ID<=0||$User1Hidden=="yes")&&isset($ThemeSet['Profile'])&&$ThemeSet['Profile']!=null) {
echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); }
?>"><?php echo $ThemeSet['Profile']; ?></a>
<?php if(isset($ThemeSet['WWW'])&&$ThemeSet['WWW']!=null) {
echo $ThemeSet['LineDividerTopic']; ?><a href="<?php echo $User1Website; ?>"<?php echo $opennew; ?>><?php echo $ThemeSet['WWW']; ?></a><?php } echo $ThemeSet['LineDividerTopic']; ?><a href="<?php
if($User1ID>0&&$User1Hidden=="no"&&isset($ThemeSet['PM'])&&$ThemeSet['PM']!=null) {
echo url_maker($exfile['messenger'],$Settings['file_ext'],"act=create&id=".$User1ID,$Settings['qstr'],$Settings['qsep'],$prexqstr['messenger'],$exqstr['messenger']); }
if(($User1ID<=0||$User1Hidden=="yes")&&isset($ThemeSet['PM'])&&$ThemeSet['PM']!=null) {
echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); }
?>"><?php echo $ThemeSet['PM']; ?></a></span>
</td>
</tr>
</table></div>
<?php } if($_GET['act']=="create") { 
if($GroupInfo['CanAddEvents']=="no") { redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
$UFID = rand_uuid("rand");
$_SESSION['UserFormID'] = $UFID;
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['event'],$Settings['file_ext'],"act=create",$Settings['qstr'],$Settings['qsep'],$prexqstr['event'],$exqstr['event']); ?>">Making a Event</a></div>
<div class="DivNavLinks">&#160;</div>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['calendar'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['calendar'],$exqstr['calendar']); ?>">Making a Event</a></span></div>
<?php } ?>
<table class="Table1" id="MakeEvent">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1" id="EventStart">
<td class="TableColumn1" colspan="2"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['calendar'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['calendar'],$exqstr['calendar']); ?>">Making a Event</a></span>
</td>
</tr><?php } ?>
<tr id="MakeEventRow" class="TableRow2">
<td class="TableColumn2" colspan="2" style="width: 100%;">Making a Event</td>
</tr>
<tr class="TableRow3" id="MkEvent">
<td class="TableColumn3" style="width: 15%; vertical-align: middle; text-align: center;">
<div style="width: 100%; height: 160px; overflow: auto;">
<table style="width: 100%; text-align: center;"><?php
$melanie_query=sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."smileys\" WHERE \"Display\"='yes'", null);
$melanie_result=sql_query($melanie_query,$SQLStat);
$melanie_num=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."smileys\" WHERE \"Display\"='yes'", null), $SQLStat);
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
	<td><img src="<?php echo $SmileDirectory."".$FileName; ?>" style="vertical-align: middle; border: 0px; cursor: pointer;" title="<?php echo $SmileName; ?>" alt="<?php echo $SmileName; ?>" onclick="addsmiley('EventText','&#160;<?php echo htmlspecialchars($SmileText, ENT_QUOTES, $Settings['charset']); ?>&#160;')" /></td>
	<?php } if($SmileRow==5) { ++$SmileCRow; ?>
	<td><img src="<?php echo $SmileDirectory."".$FileName; ?>" style="vertical-align: middle; border: 0px; cursor: pointer;" title="<?php echo $SmileName; ?>" alt="<?php echo $SmileName; ?>" onclick="addsmiley('EventText','&#160;<?php echo htmlspecialchars($SmileText, ENT_QUOTES, $Settings['charset']); ?>&#160;')" /></td></tr>
	<?php $SmileCRow=0; $SmileRow=0; }
++$melanie_p; }
if($SmileCRow<5&&$SmileCRow!=0) {
$SmileCRowL = 5 - $SmileCRow;
echo "<td colspan=\"".$SmileCRowL."\">&#160;</td></tr>"; }
echo "</table>";
sql_free_result($melanie_result);
?></div></td>
<td class="TableColumn3" style="width: 85%;">
<form style="display: inline;" method="post" id="MkEventForm" action="<?php echo url_maker($exfile['event'],$Settings['file_ext'],"act=makeevent",$Settings['qstr'],$Settings['qsep'],$prexqstr['event'],$exqstr['event']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="EventName">Insert Event Name:</label></td>
	<td style="width: 50%;"><input maxlength="30" type="text" name="EventName" class="TextBox" id="EventName" size="20" /></td>
</tr><?php if($_SESSION['UserGroup']==$Settings['GuestGroup']) { ?><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="GuestName">Insert Guest Name:</label></td>
	<?php if(!isset($_SESSION['GuestName'])) { ?>
	<td style="width: 50%;"><input maxlength="25" type="text" name="GuestName" class="TextBox" id="GuestName" size="20" /></td>
	<?php } if(isset($_SESSION['GuestName'])) { ?>
	<td style="width: 50%;"><input maxlength="25" type="text" name="GuestName" class="TextBox" id="GuestName" size="20" value="<?php echo $_SESSION['GuestName']; ?>" /></td>
<?php } ?></tr><?php } ?><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="EventStart">Insert Event Start:</label></td>
	<td style="width: 50%;"><input maxlength="10" type="date" name="EventStart" class="TextBox" id="EventStart" size="20" value="MM/DD/YYYY" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="EventEnd">Insert Event End:</label></td>
	<td style="width: 50%;"><input maxlength="10" type="date" name="EventEnd" class="TextBox" id="EventEnd" size="20" value="MM/DD/YYYY" /></td>
</tr>
</table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<label class="TextBoxLabel" for="EventText">Insert Event Text:</label><br />
<textarea rows="10" name="EventText" id="EventText" cols="40" class="TextBox"></textarea><br />
<?php if($_SESSION['UserGroup']==$Settings['GuestGroup']&&$Settings['captcha_guest']=="on") { ?>
<label class="TextBoxLabel" for="signcode"><img src="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=MkCaptcha",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>" alt="CAPTCHA Code" title="CAPTCHA Code" /></label><br />
<input maxlength="25" type="text" class="TextBox" name="signcode" size="20" id="signcode" value="Enter SignCode" /><br />
<?php } ?>
<input type="hidden" name="act" value="makeevents" style="display: none;" />
<input type="hidden" style="display: none;" name="fid" value="<?php echo $UFID; ?>" />
<input type="hidden" style="display: none;" name="ubid" value="<?php echo $Settings['BoardUUID']; ?>" />
<?php if($_SESSION['UserGroup']!=$Settings['GuestGroup']) { ?>
<input type="hidden" name="GuestName" value="null" style="display: none;" />
<?php } ?>
<input type="submit" class="Button" value="Make Event" name="make_event" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form></td></tr>
<tr id="MkEventEnd" class="TableRow4">
<td class="TableColumn4" colspan="2">&#160;</td>
</tr>
</table></div>
<?php }  if($_GET['act']=="makeevent"&&$_POST['act']=="makeevents") {
if(preg_match("/([0-9]{4})\-([0-9]{2})\-([0-9]{2})/", $_POST['EventStart'])) { $_POST['EventStart'] = preg_replace("/([0-9]{4})\-([0-9]{2})\-([0-9]{2})/", "$2/$3/$1", $_POST['EventStart']); }
if(preg_match("/([0-9]{4})\-([0-9]{2})\-([0-9]{2})/", $_POST['EventEnd'])) { $_POST['EventEnd'] = preg_replace("/([0-9]{4})\-([0-9]{2})\-([0-9]{2})/", "$2/$3/$1", $_POST['EventEnd']); }
if($GroupInfo['CanAddEvents']=="no") { redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']); $urlstatus = 302;
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
$MyUserID = $_SESSION['UserID']; if($MyUserID=="0"||$MyUserID==null) { $MyUserID = -1; }
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
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=view","&","=",$prexqstr['index'],$exqstr['index']);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['index'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['index']; }
$_SESSION['PreViewingTitle'] = "Making";
$_SESSION['ViewingTitle'] = "Event";
$_SESSION['ExtraData'] = "currentact:".$_GET['act']."; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;";
$REFERERurl = parse_url($_SERVER['HTTP_REFERER']);
$URL['REFERER'] = $REFERERurl['host'];
$URL['HOST'] = $_SERVER['SERVER_NAME'];
$REFERERurl = null;
if(!isset($_POST['EventName'])) { $_POST['EventName'] = null; }
if(!isset($_POST['EventStart'])) { $_POST['EventStart'] = null; }
if(!isset($_POST['EventEnd'])) { $_POST['EventEnd'] = null; }
if(!isset($_POST['EventText'])) { $_POST['EventText'] = null; }
if(!isset($_POST['GuestName'])) { $_POST['GuestName'] = null; }
$TimeIn = explode("/",$_POST['EventStart']);
$TimeOut = explode("/",$_POST['EventEnd']);
if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	$Settings['captcha_guest']=="on") {
require($SettDir['inc']."captcha.php"); }
?>
<div class="Table1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['calendar'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['calendar'],$exqstr['calendar']); ?>">Making a Event</a></span></div>
<?php } ?>
<table class="Table1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableRow1">
<td class="TableColumn1"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['calendar'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['calendar'],$exqstr['calendar']); ?>">Making a Event</a></span>
</td>
</tr><?php } ?>
<tr class="TableRow2">
<th class="TableColumn2" style="width: 100%; text-align: left;">&#160;Make Event Message: </th>
</tr>
<tr class="TableRow3">
<td class="TableColumn3">
<table style="width: 100%; height: 25%; text-align: center;">
<?php if (pre_strlen($_POST['EventName'])>="30") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your Event Name is too big.<br />
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
<?php } }*/ if (pre_strlen($TimeIn[0])<"2") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Event Start Month is too small.<br />
	</span>&#160;</td>
</tr>
<?php } if (pre_strlen($TimeIn[0])>"2") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Event Start Month is too big.<br />
	</span>&#160;</td>
</tr>
<?php } if (pre_strlen($TimeIn[1])<"2") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Event Start Day is too small.<br />
	</span>&#160;</td>
</tr>
<?php } if (pre_strlen($TimeIn[1])>"2") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Event Start Day is too big.<br />
	</span>&#160;</td>
</tr>
<?php } if (pre_strlen($TimeIn[2])<"4") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Event Start Year is too small.<br />
	</span>&#160;</td>
</tr>
<?php } if (pre_strlen($TimeIn[2])>"4") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Event Start Year is too big.<br />
	</span>&#160;</td>
</tr>
<?php } if (pre_strlen($TimeOut[0])<"2") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Event End Month is too small.<br />
	</span>&#160;</td>
</tr>
<?php } if (pre_strlen($TimeOut[0])>"2") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Event End Month is too big.<br />
	</span>&#160;</td>
</tr>
<?php } if (pre_strlen($TimeOut[1])<"2") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Event End Day is too small.<br />
	</span>&#160;</td>
</tr>
<?php } if (pre_strlen($TimeOut[1])>"2") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Event End Day is too big.<br />
	</span>&#160;</td>
</tr>
<?php } if (pre_strlen($TimeOut[2])<"4") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Event End Year is too small.<br />
	</span>&#160;</td>
</tr>
<?php } if (pre_strlen($TimeOut[2])>"4") { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Event End Year is too big.<br />
	</span>&#160;</td>
</tr>
<?php } if (checkdate($TimeIn[0],$TimeIn[1],$TimeIn[2])===false) { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Sorry the event start date is not valid.<br />
	</span>&#160;</td>
</tr>
<?php } if (checkdate($TimeOut[0],$TimeOut[1],$TimeOut[2])===false) { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Sorry the event end date is not valid.<br />
	</span>&#160;</td>
</tr>
<?php } if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	pre_strlen($_POST['GuestName'])>="25") { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You Guest Name is too big.<br />
	</span>&#160;</td>
</tr>
<?php } if ($Settings['TestReferer']===true) {
	if ($URL['HOST']!=$URL['REFERER']) { $Error="Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Sorry the referering url dose not match our host name.<br />
	</span>&#160;</td>
</tr>
<?php } }
$_POST['EventName'] = stripcslashes(htmlspecialchars($_POST['EventName'], ENT_QUOTES, $Settings['charset']));
//$_POST['EventName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['EventName']);
$_POST['EventName'] = remove_spaces($_POST['EventName']);
$_POST['GuestName'] = stripcslashes(htmlspecialchars($_POST['GuestName'], ENT_QUOTES, $Settings['charset']));
//$_POST['GuestName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['GuestName']);
$_POST['GuestName'] = remove_spaces($_POST['GuestName']);
$_POST['EventText'] = stripcslashes(htmlspecialchars($_POST['EventText'], ENT_QUOTES, $Settings['charset']));
//$_POST['EventText'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['EventText']);
$_POST['EventText'] = remove_bad_entities($_POST['EventText']);
//$_POST['EventText'] = remove_spaces($_POST['EventText']);
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
$melanieqy=sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."wordfilter\"", null);
$melaniert=sql_query($melanieqy,$SQLStat);
$melanienm=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."wordfilter\"", null), $SQLStat);
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
$_POST['EventText'] = preg_replace("/\b(".$Filter.")\b/", $Replace, $_POST['EventText']); }
if($CaseInsensitive=="yes"&&$WholeWord=="yes") {
$_POST['EventText'] = preg_replace("/\b(".$Filter.")\b/i", $Replace, $_POST['EventText']); }
if($CaseInsensitive!="yes"&&$WholeWord!="yes") {
$_POST['EventText'] = preg_replace("/".$Filter."/", $Replace, $_POST['EventText']); }
if($CaseInsensitive=="yes"&&$WholeWord!="yes") {
$_POST['EventText'] = preg_replace("/".$Filter."/i", $Replace, $_POST['EventText']); }
++$melanies; } sql_free_result($melaniert);
$lonewolfqy=sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."restrictedwords\" WHERE \"RestrictedEventName\"='yes' or \"RestrictedUserName\"='yes'", null);
$lonewolfrt=sql_query($lonewolfqy,$SQLStat);
$lonewolfnm=sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."restrictedwords\" WHERE \"RestrictedEventName\"='yes' or \"RestrictedUserName\"='yes'", null), $SQLStat);
$lonewolfs=0; $RMatches = null; $RGMatches = null;
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
$RestrictedEventName=$lonewolfrt_array['RestrictedEventName'];
if($RestrictedEventName=="on") { $RestrictedEventName = "yes"; }
if($RestrictedEventName=="off") { $RestrictedEventName = "no"; }
if($RestrictedEventName!="yes"||$RestrictedEventName!="no") { $RestrictedEventName = "no"; }
$RestrictedUserName=$lonewolfrt_array['RestrictedUserName'];
if($RestrictedUserName=="on") { $RestrictedUserName = "yes"; }
if($RestrictedUserName=="off") { $RestrictedUserName = "no"; }
if($RestrictedUserName!="yes"||$RestrictedUserName!="no") { $RestrictedUserName = "no"; }
$RWord = preg_quote($RWord, "/");
if($RCaseInsensitive!="yes"&&$RWholeWord=="yes") {
if($RestrictedEventName=="yes") {
$RMatches = preg_match("/\b(".$RWord.")\b/", $_POST['EventName']);
	if($RMatches==true) { break 1; } }
if($RestrictedUserName=="yes") {
$RGMatches = preg_match("/\b(".$RWord.")\b/", $_POST['GuestName']);
	if($RGMatches==true) { break 1; } } }
if($RCaseInsensitive=="yes"&&$RWholeWord=="yes") {
if($RestrictedEventName=="yes") {
$RMatches = preg_match("/\b(".$RWord.")\b/i", $_POST['EventName']);
	if($RMatches==true) { break 1; } }
if($RestrictedUserName=="yes") {
$RGMatches = preg_match("/\b(".$RWord.")\b/i", $_POST['GuestName']);
	if($RGMatches==true) { break 1; } } }
if($RCaseInsensitive!="yes"&&$RWholeWord!="yes") {
if($RestrictedEventName=="yes") {
$RMatches = preg_match("/".$RWord."/", $_POST['EventName']);
	if($RMatches==true) { break 1; } }
if($RestrictedUserName=="yes") {
$RGMatches = preg_match("/".$RWord."/", $_POST['GuestName']);
	if($RGMatches==true) { break 1; } } }
if($RCaseInsensitive=="yes"&&$RWholeWord!="yes") {
if($RestrictedEventName=="yes") {
$RMatches = preg_match("/".$RWord."/i", $_POST['EventName']);
	if($RMatches==true) { break 1; } }
if($RestrictedUserName=="yes") {
$RGMatches = preg_match("/".$RWord."/i", $_POST['GuestName']);
	if($RGMatches==true) { break 1; } } }
++$lonewolfs; } sql_free_result($lonewolfrt);
if ($_POST['EventName']==null) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a Event Name.<br />
	</span>&#160;</td>
</tr>
<?php } if ($_POST['EventText']==null) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a Event Text.<br />
	</span>&#160;</td>
</tr>
<?php } if ($_POST['EventStart']==null) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter date for event to start in MM/DD/YYYY format.<br />
	</span>&#160;</td>
</tr>
<?php } if ($_POST['EventEnd']==null) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter date for event to end in MM/DD/YYYY format.<br />
	</span>&#160;</td>
</tr>
<?php } if (count($TimeIn)!="3") { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter valid date for event to start in MM/DD/YYYY format.<br />
	</span>&#160;</td>
</tr>
<?php } if (count($TimeOut)!="3") { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter valid date for event to end in MM/DD/YYYY format.<br />
	</span>&#160;</td>
</tr>
<?php } if (!is_numeric($TimeIn[0])||!is_numeric($TimeIn[1])||!is_numeric($TimeIn[2])) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter valid date for event to start in MM/DD/YYYY format.<br />
	</span>&#160;</td>
</tr>
<?php } if (!is_numeric($TimeOut[0])||!is_numeric($TimeOut[1])||!is_numeric($TimeOut[2])) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter valid date for event to end in MM/DD/YYYY format.<br />
	</span>&#160;</td>
</tr>
<?php } if (!isset($TimeIn[0])||!isset($TimeIn[1])||!isset($TimeIn[2])) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter valid date for event to start in MM/DD/YYYY format.<br />
	</span>&#160;</td>
</tr>
<?php } if (!isset($TimeOut[0])||!isset($TimeOut[1])||!isset($TimeOut[2])) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter valid date for event to end in MM/DD/YYYY format.<br />
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
<?php } if($GroupInfo['CanAddEvents']=="no") { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You do not have permission to make a event here.<br />
	</span>&#160;</td>
</tr>
<?php } if($RMatches==true) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />This User Name is restricted to use.<br />
	</span>&#160;</td>
</tr>
<?php } if ($Error=="Yes") {
redirect("refresh",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false),"4"); ?>
<tr>
	<td><span class="TableMessage">
	<br />Click <a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">here</a> to goto index page.<br />&#160;
	</span><br /></td>
</tr>
<?php } if ($Error!="Yes") { 
$TimeSIn = mktime(0,0,0,$TimeIn[0],$TimeIn[1],$TimeIn[2]);
$TimeSOut = mktime(23,59,59,$TimeOut[0],$TimeOut[1],$TimeOut[2]);
$eventstartcurtime = new DateTime();
$eventstartcurtime->setTimestamp($TimeSIn);
$eventstartcurtime->setTimezone($utctz);
$eventendcurtime = new DateTime();
$eventendcurtime->setTimestamp($TimeSOut);
$eventendcurtime->setTimezone($utctz);
$EventMonth=$eventstartcurtime->format("m");
$EventMonthEnd=$eventendcurtime->format("m");
$EventDay=$eventstartcurtime->format("d");
$EventDayEnd=$eventendcurtime->format("d");
$EventYear=$eventstartcurtime->format("Y");
$EventYearEnd=$eventendcurtime->format("Y");
$User1ID=$MyUserID;
$User1IP=$_SERVER['REMOTE_ADDR'];
if($_SESSION['UserGroup']==$Settings['GuestGroup']) { $User1Name = $_POST['GuestName']; }
if($_SESSION['UserGroup']!=$Settings['GuestGroup']) { $User1Name = $_SESSION['MemberName']; }
$query = sql_pre_query("INSERT INTO ".$Settings['sqltable']."events (\"UserID\", \"GuestName\", \"EventName\", \"EventText\", \"TimeStamp\", \"TimeStampEnd\", \"EventMonth\", \"EventMonthEnd\", \"EventDay\", \"EventDayEnd\", \"EventYear\", \"EventYearEnd\", \"IP\") VALUES\n".
"(%i, '%s', '%s', '%s', %i, %i, %i, %i, %i, %i, %i, %i, '%s')", array($User1ID,$User1Name,$_POST['EventName'],$_POST['EventText'],$TimeSIn,$TimeSOut,$EventMonth,$EventMonthEnd,$EventDay,$EventDayEnd,$EventYear,$EventYearEnd,$User1IP));
sql_query($query,$SQLStat);
$eventid = sql_get_next_id($Settings['sqltable'],"events",$SQLStat);
redirect("refresh",$rbasedir.url_maker($exfile['event'],$Settings['file_ext'],"act=event&id=".$eventid,$Settings['qstr'],$Settings['qsep'],$prexqstr['event'],$exqstr['event'],FALSE),"3");
?><tr>
	<td><span class="TableMessage"><br />
	Event <?php echo $_POST['EventName']; ?> was started.<br />
	Click <a href="<?php echo url_maker($exfile['event'],$Settings['file_ext'],"act=event&id=".$eventid,$Settings['qstr'],$Settings['qsep'],$prexqstr['event'],$exqstr['event']); ?>">here</a> to continue to event.<br />&#160;
	</span><br /></td>
</tr>
<?php } ?>
</table>
</td></tr>
<tr class="TableRow4">
<td class="TableColumn4">&#160;</td>
</tr>
</table></div>
<?php } ?>
<div class="DivEvents">&#160;</div>
