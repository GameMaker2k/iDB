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

    $FileInfo: stats.php - Last Update: 8/26/2024 SVN 1048 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="stats.php"||$File3Name=="/stats.php") {
	require('index.php');
	exit(); }
if($_GET['act']=="stats") {
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
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=stats","&","=",$prexqstr['index'],$exqstr['index']);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['index'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['index']; }
$_SESSION['PreViewingTitle'] = "Viewing";
$_SESSION['ViewingTitle'] = "Board Stats"; 
$_SESSION['ExtraData'] = "currentact:".$_GET['act']."; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;";
?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=stats",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>#bstats">Board Statistics</a></div>
<div class="DivNavLinks">&#160;</div>
<?php }
$uolcuttime = $utccurtime->getTimestamp();
$uoltime = $uolcuttime - ini_get("session.gc_maxlifetime");
$uolquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" >= %i ORDER BY \"expires\" DESC", array($uoltime));
$uolresult=sql_query($uolquery,$SQLStat);
$uolnum=sql_num_rows($uolresult);
$uoli=0; $olmn = 0; $olgn = 0; $olan = 0; $olmbn = 0;
$MembersOnline = null; $GuestsOnline = null;
while ($uoli < $uolnum) {
$session_data=sql_result($uolresult,$uoli,"session_data"); 
$serialized_data=sql_result($uolresult,$uoli,"serialized_data");
$session_user_agent=sql_result($uolresult,$uoli,"user_agent"); 
$session_ip_address=sql_result($uolresult,$uoli,"ip_address");
//$UserSessInfo = unserialize_session($session_data);
$UserSessInfo = unserialize($serialized_data);
if(!isset($UserSessInfo['UserGroup'])) { $UserSessInfo['UserGroup'] = $Settings['GuestGroup']; }
$AmIHiddenUser = "no";
$user_agent_check = false;
if(user_agent_check($session_user_agent)) {
	$user_agent_check = user_agent_check($session_user_agent); }
if($UserSessInfo['UserGroup']!=$Settings['GuestGroup']||$user_agent_check!==false) {
$PreAmIHiddenUser = GetUserName($UserSessInfo['UserID'],$Settings['sqltable'],$SQLStat);
$AmIHiddenUser = $PreAmIHiddenUser['Hidden'];
if(isset($UserSessInfo['AnonymousLogin']) && $UserSessInfo['AnonymousLogin']=="yes") { $AmIHiddenUser = "yes"; }
if((($AmIHiddenUser=="no"||$GroupInfo['CanViewAnonymous']=="yes"||$_SESSION['UserID']==$UserSessInfo['UserID'])&&$UserSessInfo['UserID']>0)||$user_agent_check!==false) {
if($olmbn>0) { $MembersOnline .= ", "; }
$userprestring = "";
if($AmIHiddenUser=="yes") { $userprestring = "*"; }
if($user_agent_check===false) {
$uatitleadd = null;
if($GroupInfo['CanViewUserAgent']=="yes") { $uatitleadd = " title=\"".htmlentities($session_user_agent, ENT_QUOTES, $Settings['charset'])."\""; }
$MembersOnline .= "<a".$uatitleadd." href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$UserSessInfo['UserID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">".$userprestring.$UserSessInfo['MemberName']."</a>"; 
if($GroupInfo['CanViewIPAddress']=="yes") {
$MembersOnline .= " (<a title=\"".$session_ip_address."\" onclick=\"window.open(this.href);return false;\" href=\"".sprintf($IPCheckURL,$session_ip_address)."\">".$session_ip_address."</a>)"; }
if($AmIHiddenUser=="no") { ++$olmn; ++$olmbn; } }
if($user_agent_check!==false) {
$uatitleadd = null;
if($GroupInfo['CanViewUserAgent']=="yes") { $uatitleadd = " title=\"".htmlentities($session_user_agent, ENT_QUOTES, $Settings['charset'])."\""; }
$MembersOnline .= "<span".$uatitleadd.">".$user_agent_check."</span>"; 
if($GroupInfo['CanViewIPAddress']=="yes") {
$MembersOnline .= " (<a title=\"".$session_ip_address."\" onclick=\"window.open(this.href);return false;\" href=\"".sprintf($IPCheckURL,$session_ip_address)."\">".$session_ip_address."</a>)"; }
++$olmbn; } }
if($UserSessInfo['UserID']<=0||$AmIHiddenUser=="yes") {
if($user_agent_check===false) {
++$olan; } } }
if($UserSessInfo['UserGroup']==$Settings['GuestGroup']) {
/*$uatitleadd = null;
if($GroupInfo['CanViewUserAgent']=="yes") { $uatitleadd = " title=\"".htmlentities($session_user_agent, ENT_QUOTES, $Settings['charset'])."\""; }
$GuestsOnline .= "<a".$uatitleadd." href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$MemList['ID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">".$MemList['Name']."</a>";
if($GroupInfo['CanViewIPAddress']=="yes") {
$GuestsOnline .= " (<a title=\"".$session_ip_address."\" onclick=\"window.open(this.href);return false;\" href=\"".sprintf($IPCheckURL,$session_ip_address)."\">".$session_ip_address."</a>)"; } */
++$olgn; }
++$uoli; }
if($_GET['act']=="view"||$_GET['act']=="stats") {
$ntquery = sql_pre_query("SELECT COUNT(*) FROM \"".$Settings['sqltable']."topics\"".$ForumIgnoreList3, null);
$ntresult = sql_query($ntquery,$SQLStat);
$numtopics = sql_result($ntresult,0);
sql_free_result($ntresult);
$npquery = sql_pre_query("SELECT COUNT(*) FROM \"".$Settings['sqltable']."posts\"".$ForumIgnoreList3, null);
$npresult = sql_query($npquery,$SQLStat);
$numposts = sql_result($npresult,0);
sql_free_result($npresult);
$caniview = "AND \"HiddenMember\"='no'";
if($GroupInfo['CanViewAnonymous']=="yes") {
 $caniview = ""; }
if($Settings['AdminValidate']=="on") {
$nmquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\">=%i ".$caniview." AND \"Validated\"='yes' AND \"GroupID\"<>%i ORDER BY \"Joined\" DESC LIMIT 1", array(1,$Settings['ValidateGroup'])); 
$rnmquery = sql_pre_query("SELECT COUNT(*) FROM \"".$Settings['sqltable']."members\" WHERE \"id\">=%i ".$caniview." AND \"Validated\"='yes' AND \"GroupID\"<>%i", array(1,$Settings['ValidateGroup'])); }
if($Settings['AdminValidate']!="on") {
$nmquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\">=%i ".$caniview." ORDER BY \"Joined\" DESC LIMIT 1", array(1,$Settings['ValidateGroup'])); 
$rnmquery = sql_pre_query("SELECT COUNT(*) FROM \"".$Settings['sqltable']."members\" WHERE \"id\">=%i ".$caniview, array(1,$Settings['ValidateGroup'])); }
$nmresult = sql_query($nmquery,$SQLStat);
$rnmresult = sql_query($rnmquery,$SQLStat);
//$nummembers = sql_num_rows($nmresult);
$nummembers = sql_result($rnmresult,0);
sql_free_result($rnmresult);
$NewestMem = array(null);
$NewestMem['ID'] = "0"; $NewestMem['Name'] = "Anonymous";
if($nummembers>0) {
$NewestMem['ID']=sql_result($nmresult,0,"id");
$NewestMem['Name']=sql_result($nmresult,0,"Name");
$NewestMem['IP']=sql_result($nmresult,0,"IP"); }
if($nummembers<=0) { $NewestMem['ID'] = 0; }
if($NewestMem['ID']<=0) { $NewestMem['ID'] = "0"; $NewestMem['Name'] = "Anonymous"; $NewestMem['IP'] = "127.0.0.1"; }
$NewestMemTitle = null;
$NewestMemExtraIP = null;
if($GroupInfo['CanViewIPAddress']=="yes") {
$NewestMemTitle = " title=\"".$NewestMem['IP']."\"";
$NewestMemExtraIP = " (<a title=\"".$NewestMem['IP']."\" onclick=\"window.open(this.href);return false;\" href=\"".sprintf($IPCheckURL,$NewestMem['IP'])."\">".$NewestMem['IP']."</a>)"; }
$bdMonthChCk = $usercurtime->format("m");
$bdDayChCk = $usercurtime->format("d");
if($Settings['AdminValidate']=="on") {
$bdquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"BirthDay\"=%i AND \"BirthMonth\"=%i ".$caniview." AND \"Validated\"='yes' AND \"GroupID\"<>%i ORDER BY \"id\"", array($bdDayChCk,$bdMonthChCk,$Settings['ValidateGroup'])); } 
if($Settings['AdminValidate']!="on") {
$bdquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"BirthDay\"=%i AND \"BirthMonth\"=%i ".$caniview." ORDER BY \"id\"", array($bdDayChCk,$bdMonthChCk)); } 
$bdresult = sql_query($bdquery,$SQLStat);
$bdmembers = sql_num_rows($bdresult); $bdi = 0;
if($bdmembers>0) { $bdstring = "&#160;".$bdmembers." member(s) have a birthday today"; }
if($bdmembers<=0) { $bdstring = "<div>&#160;</div>&#160;No members have a birthday today<div>&#160;</div>"; }
while ($bdi < $bdmembers) {
$bdmemberz = $bdmembers - 1;
$birthday['ID']=sql_result($bdresult,$bdi,"id");
$birthday['Name']=sql_result($bdresult,$bdi,"Name");
$birthday['IP']=sql_result($bdresult,$bdi,"IP");
$birthday['BirthYear']=sql_result($bdresult,$bdi,"BirthYear");
$bdThisYear = $usercurtime->format("Y");
$birthday['Age'] = $bdThisYear - $birthday['BirthYear'];
$bdMemTitle = null;
if($GroupInfo['HasAdminCP']=="yes") {
$bdMemTitle = " title=\"".$birthday['IP']."\""; }
if($bdi===0) { $bdstring = $bdstring."\n<br />&#160;"; }
$bdMemURL = "<a".$bdMemTitle." href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$birthday['ID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">".$birthday['Name']."</a>";
if($bdi<$bdmemberz) { $bdstring = $bdstring.$bdMemURL." (<span style=\"font-weight: bold;\" title=\"".$birthday['Name']." is ".$birthday['Age']." years old\">".$birthday['Age']."</span>), "; }
if($bdi==$bdmemberz) { $bdstring = $bdstring.$bdMemURL." (<span style=\"font-weight: bold;\" title=\"".$birthday['Name']." is ".$birthday['Age']." years old\">".$birthday['Age']."</span>)"; }
++$bdi; }
sql_free_result($bdresult);
$evcur_month = $usercurtime->format("m");
$evcur_day = $usercurtime->format("d");
$evcur_year = $usercurtime->format("Y");
$evcur_start = mktime(0,0,0,$evcur_month,$evcur_day,$evcur_year);
$evcur_start_month = date("m", $evcur_start);
$evcur_start_day = date("d", $evcur_start);
$evcur_start_year = date("Y", $evcur_start);
$evcur_end = mktime(23,59,59,$evcur_month,$evcur_day+15,$evcur_year);
$evcur_end_month = date("m", $evcur_end);
$evcur_end_day = date("d", $evcur_end);
$evcur_end_year = date("Y", $evcur_end);
$evquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."events\" WHERE (\"TimeStamp\">=%i AND \"TimeStamp\"<=%i) OR (\"TimeStampEnd\">=%i AND \"TimeStampEnd\"<=%i) ORDER BY \"TimeStamp\"", array($evcur_start,$evcur_end,$evcur_start,$evcur_end)); 
$evresult = sql_query($evquery,$SQLStat);
$evevents = sql_num_rows($evresult); $evi = 0;
if($evevents>0) { $evstring = "&#160;".$evevents." upcoming events"; }
if($evevents<=0) { $evstring = "<div>&#160;</div>&#160;There are no upcoming calendar events<div>&#160;</div>"; }
while ($evi < $evevents) {
$eveventz = $evevents - 1;
$getevent['ID']=sql_result($evresult,$evi,"id");
$getevent['EventName']=sql_result($evresult,$evi,"EventName");
$getevent['TimeStamp']=sql_result($evresult,$evi,"TimeStamp");
$getevent['TimeStampEnd']=sql_result($evresult,$evi,"TimeStampEnd");
$eventstartcurtime = new DateTime();
$eventstartcurtime->setTimestamp($getevent['TimeStamp']);
$eventstartcurtime->setTimezone($usertz);
$eventendcurtime = new DateTime();
$eventendcurtime->setTimestamp($getevent['TimeStampEnd']);
$eventendcurtime->setTimezone($usertz);
$GetEventStart=$eventstartcurtime->format($Settings['idb_date_format']);
$GetEventEnd=$eventendcurtime->format($Settings['idb_date_format']);
if($GetEventStart==$GetEventEnd) {
 $evEventTitle = " title=\"Event Start: ".$GetEventStart."\""; }
if($GetEventStart!=$GetEventEnd) {
 $evEventTitle = " title=\"Event Start: ".$GetEventStart." | Event End: ".$GetEventEnd."\""; }
if($evi===0) { $evstring = $evstring."\n<br />&#160;"; }
$evEventURL = "<a".$evEventTitle." href=\"".url_maker($exfile['event'],$Settings['file_ext'],"act=event&id=".$getevent['ID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['event'],$exqstr['event'])."\">".$getevent['EventName']."</a>";
if($evi<$eveventz) { $evstring = $evstring.$evEventURL.", "; }
if($evi==$eveventz) { $evstring = $evstring.$evEventURL; }
++$evi; }
sql_free_result($evresult);
$active_month = $usercurtime->format("m");
$active_day = $usercurtime->format("d");
$active_year = $usercurtime->format("Y");
$active_start = mktime(0,0,0,$active_month,$active_day,$active_year);
$active_end = mktime(23,59,59,$active_month,$active_day,$active_year);
$tdMembersOnline = null;
$ggquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"Name\"='%s'", array($Settings['GuestGroup']));
$ggresult=sql_query($ggquery,$SQLStat);
$GGroup=sql_result($ggresult,0,"id");
sql_free_result($ggresult);
$tdquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"GroupID\"<>%i AND \"id\">=0 ".$caniview." AND (\"LastActive\">=%i AND \"LastActive\"<=%i) ORDER BY \"LastActive\" DESC", array($GGroup,$active_start,$active_end)); 
$tdrnquery = sql_pre_query("SELECT COUNT(*) FROM \"".$Settings['sqltable']."members\" WHERE \"GroupID\"<>%i AND \"id\">=0 ".$caniview." AND (\"LastActive\">=%i AND \"LastActive\"<=%i)", array($GGroup,$active_start,$active_end));
$tdrnresult=sql_query($tdrnquery,$SQLStat);
$tdNumberMembers=sql_result($tdrnresult,0);
$tdresult=sql_query($tdquery,$SQLStat);
$tdnum=sql_num_rows($tdresult);
$tdi=0;
while($tdi < $tdnum) {
$tdMemList['ID']=sql_result($tdresult,$tdi,"id");
$tdMemList['Name']=sql_result($tdresult,$tdi,"Name");
$tdMemList['IP']=sql_result($tdresult,$tdi,"IP");
$tdMemList['LastActive']=sql_result($tdresult,$tdi,"LastActive");
$tmpusrcurtime = new DateTime();
$tmpusrcurtime->setTimestamp($tdMemList['LastActive']);
$tmpusrcurtime->setTimezone($usertz);
$tdMemList['LastActive']=$tmpusrcurtime->format("M j Y, ".$_SESSION['iDBTimeFormat']);
if($tdi>0) { $tdMembersOnline .= ", "; }
$tdMembersOnline .= "<a title=\"".$tdMemList['Name']." was last active at ".$tdMemList['LastActive']."\" href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$tdMemList['ID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">".$tdMemList['Name']."</a>"; 
if($GroupInfo['CanViewIPAddress']=="yes") {
$tdMembersOnline .= " (<a title=\"".$tdMemList['IP']."\" onclick=\"window.open(this.href);return false;\" href=\"".sprintf($IPCheckURL,$tdMemList['IP'])."\">".$tdMemList['IP']."</a>)"; }
++$tdi; }
?>
<div class="StatsBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableStatsRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a id="bstats" href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=stats",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>#bstats">Board Statistics</a></span></div>
<?php } ?>
<table id="BoardStats" class="TableStats1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableStatsRow1">
<td class="TableStatsColumn1" colspan="2"><span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a id="bstats" href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=stats",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>#bstats">Board Statistics</a></span>
</td>
</tr><?php } ?>
<tr id="Stats1" class="TableStatsRow2">
<td class="TableStatsColumn2" colspan="2" style="width: 100%; font-weight: bold;"><?php echo $uolnum; ?> users online</td>
</tr>
<tr class="TableStatsRow3" id="Stats2">
<td style="width: 4%;" class="TableStatsColumn3"><div class="statsicon">
<?php echo $ThemeSet['MemberStatsIcon']; ?></div></td>
<td style="width: 96%;" class="TableStatsColumn3"><div class="statsinfo">
&#160;<span style="font-weight: bold;"><?php echo $olgn; ?></span> guests, <span style="font-weight: bold;"><?php echo $olmn; ?></span> members, <span style="font-weight: bold;"><?php echo $olan; ?></span> anonymous members <br />
<?php if($MembersOnline==null) { ?>&#160;<?php echo "\n<br />"; } ?>
<?php if($MembersOnline!=null) { ?>&#160;<?php echo $MembersOnline."\n<br />"; } ?>
&#160;Show detailed by: <a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=online&list=all&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Last Click</a>, <a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=online&list=members&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Member Name</a>
</div></td>
</tr>
<tr id="Stats3" class="TableStatsRow2">
<td class="TableStatsColumn2" colspan="2" style="width: 100%; font-weight: bold;">Today's Birthdays</td>
</tr>
<tr class="TableStatsRow3" id="Stats4">
<td style="width: 4%;" class="TableStatsColumn3"><div class="statsicon">
<?php echo $ThemeSet['BirthdayStatsIcon']; ?></div></td>
<td style="width: 96%;" class="TableStatsColumn3"><div class="statsinfo">
<?php echo $bdstring; ?>
</div></td>
</tr>
<tr id="Stats5" class="TableStatsRow2">
<td class="TableStatsColumn2" colspan="2" style="width: 100%; font-weight: bold;">Upcoming Events</td>
</tr>
<tr class="TableStatsRow3" id="Stats6">
<td style="width: 4%;" class="TableStatsColumn3"><div class="statsicon">
<?php echo $ThemeSet['EventStatsIcon']; ?></div></td>
<td style="width: 96%;" class="TableStatsColumn3"><div class="statsinfo">
<?php echo $evstring; ?>
</div></td>
</tr>
<tr id="Stats7" class="TableStatsRow2">
<td class="TableStatsColumn2" colspan="2" style="width: 100%; font-weight: bold;">Board Stats</td>
</tr>
<tr class="TableStatsRow3" id="Stats8">
<td style="width: 4%;" class="TableStatsColumn3"><div class="statsicon">
<?php echo $ThemeSet['BoardStatsIcon']; ?></div></td>
<td style="width: 96%;" class="TableStatsColumn3"><div class="statsinfo">
&#160;Our members have made a total of <?php echo $numposts; ?> posts<br />
&#160;Our members have made a total of <?php echo $numtopics; ?> topics<br />
&#160;We have <?php echo $nummembers; ?> registered members<br />
&#160;Our newest member is <a<?php echo $NewestMemTitle; ?> href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$NewestMem['ID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>"><?php echo $NewestMem['Name']; ?></a><?php echo $NewestMemExtraIP; ?>
</div></td>
</tr>
<tr id="Stats9" class="TableStatsRow2">
<td class="TableStatsColumn2" colspan="2" style="width: 100%; font-weight: bold;">Members Online Today: <?php echo $tdNumberMembers; ?></td>
</tr>
<tr class="TableStatsRow3" id="Stats10">
<td style="width: 4%;" class="TableStatsColumn3"><div class="statsicon">
<?php echo $ThemeSet['OnlineStatsIcon']; ?></div></td>
<td style="width: 96%;" class="TableStatsColumn3"><div class="statsinfo">
&#160;Number of members online today: <?php echo $tdNumberMembers; ?><br />
&#160;The following members have visited today:<br />
&#160;<?php echo $tdMembersOnline; ?>
</div></td>
</tr>
<tr id="Stats11" class="TableStatsRow4">
<td class="TableStatsColumn4" colspan="2">&#160;</td>
</tr>
</table></div>
<div class="DivStats">&#160;</div>
<?php } ?>
