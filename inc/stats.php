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

    $FileInfo: stats.php - Last Update: 06/10/2010 SVN 524 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="stats.php"||$File3Name=="/stats.php") {
	require('index.php');
	exit(); }
if($_GET['act']=="stats") {
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=stats","&","=",$prexqstr['index'],$exqstr['index']);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['index'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['index']; }
$_SESSION['PreViewingTitle'] = "Viewing";
$_SESSION['ViewingTitle'] = "Board Stats"; }
$uolcuttime = GMTimeStamp();
$uoltime = $uolcuttime - ini_get("session.gc_maxlifetime");
$uolquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."sessions\" WHERE \"expires\" >= %i ORDER BY \"expires\" DESC", array($uoltime));
$uolresult=sql_query($uolquery,$SQLStat);
$uolnum=sql_num_rows($uolresult);
$uoli=0; $olmn = 0; $olgn = 0; $olan = 0; $olmbn = 0;
$MembersOnline = null; $GuestsOnline = null;
while ($uoli < $uolnum) {
$session_data=sql_result($uolresult,$uoli,"session_data"); 
$session_user_agent=sql_result($uolresult,$uoli,"user_agent"); 
$UserSessInfo = unserialize_session($session_data);
$AmIHiddenUser = "no";
$user_agent_check = false;
if(user_agent_check($session_user_agent)) {
	$user_agent_check = user_agent_check($session_user_agent); }
if($UserSessInfo['UserGroup']!=$Settings['GuestGroup']||$user_agent_check!==false) {
$PreAmIHiddenUser = GetUserName($UserSessInfo['UserID'],$Settings['sqltable'],$SQLStat);
$AmIHiddenUser = $PreAmIHiddenUser['Hidden'];
if(($AmIHiddenUser=="no"&&$UserSessInfo['UserID']>0)||$user_agent_check!==false) {
if($olmbn>0) { $MembersOnline .= ", "; }
if($user_agent_check===false) {
$MembersOnline .= "<a href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$UserSessInfo['UserID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">".$UserSessInfo['MemberName']."</a>"; 
++$olmn; ++$olmbn; }
if($user_agent_check!==false) {
$MembersOnline .= "<span>".$user_agent_check."</span>"; 
++$olmbn; } }
if($UserSessInfo['UserID']<=0||$AmIHiddenUser=="yes") {
if($user_agent_check===false) {
++$olan; } } }
if($UserSessInfo['UserGroup']==$Settings['GuestGroup']) {
/*$GuestsOnline .= "<a href=\"".url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$MemList['ID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member'])."\">".$MemList['Name']."</a>";*/
++$olgn; }
++$uoli; }
if($_GET['act']=="view"||$_GET['act']=="stats") {
$ntquery = sql_pre_query("SELECT COUNT(*) FROM \"".$Settings['sqltable']."topics\"", array(null));
$ntresult = sql_query($ntquery,$SQLStat);
$numtopics = sql_result($ntresult,0);
sql_free_result($ntresult);
$npquery = sql_pre_query("SELECT COUNT(*) FROM \"".$Settings['sqltable']."posts\"", array(null));
$npresult = sql_query($npquery,$SQLStat);
$numposts = sql_result($npresult,0);
sql_free_result($npresult);
if($Settings['AdminValidate']=="on") {
$nmquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\">=%i AND \"HiddenMember\"='no' AND \"Validated\"='yes' AND \"GroupID\"<>%i ORDER BY \"Joined\" DESC LIMIT 1", array(1,$Settings['ValidateGroup'])); 
$rnmquery = sql_pre_query("SELECT COUNT(*) FROM \"".$Settings['sqltable']."members\" WHERE \"id\">=%i AND \"HiddenMember\"='no' AND \"Validated\"='yes' AND \"GroupID\"<>%i", array(1,$Settings['ValidateGroup'])); }
if($Settings['AdminValidate']!="on") {
$nmquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\">=%i AND \"HiddenMember\"='no' ORDER BY \"Joined\" DESC LIMIT 1", array(1,$Settings['ValidateGroup'])); 
$rnmquery = sql_pre_query("SELECT COUNT(*) FROM \"".$Settings['sqltable']."members\" WHERE \"id\">=%i AND \"HiddenMember\"='no'", array(1,$Settings['ValidateGroup'])); }
$nmresult = sql_query($nmquery,$SQLStat);
$rnmresult = sql_query($rnmquery,$SQLStat);
//$nummembers = sql_num_rows($nmresult);
$nummembers = sql_result($rnmresult,0);
sql_free_result($rnmresult);
$NewestMem = array(null);
$NewestMem['ID'] = "0"; $NewestMem['Name'] = "Anonymous";
if($nummembers>0) {
$NewestMem['ID']=sql_result($nmresult,0,"id");
$NewestMem['Name']=sql_result($nmresult,0,"Name"); }
if($nummembers<=0) { $NewestMem['ID'] = 0; }
if($NewestMem['ID']<=0) { $NewestMem['ID'] = "0"; $NewestMem['Name'] = "Anonymous"; }
?>
<div class="StatsBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableStatsRow1">
<span style="text-align: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a id="bstats" href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=stats",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>#bstats">Board Statistics</a></span></div>
<?php } ?>
<table class="TableStats1">
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
<?php echo $ThemeSet['StatsIcon']; ?></div></td>
<td style="width: 96%;" class="TableStatsColumn3"><div class="statsinfo">
&nbsp;<span style="font-weight: bold;"><?php echo $olgn; ?></span> guests, <span style="font-weight: bold;"><?php echo $olmn; ?></span> members, <span style="font-weight: bold;"><?php echo $olan; ?></span> anonymous members <br />
<?php if($MembersOnline!=null) { ?>&nbsp;<?php echo $MembersOnline."\n<br />"; } ?>
&nbsp;Show detailed by: <a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=online&list=all&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Last Click</a>, <a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=online&list=members&page=1",$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>">Member Name</a>
</div></td>
</tr>
<tr id="Stats3" class="TableStatsRow2">
<td class="TableStatsColumn2" colspan="2" style="width: 100%; font-weight: bold;">Board Stats</td>
</tr>
<tr class="TableStatsRow3" id="Stats4">
<td style="width: 4%;" class="TableStatsColumn3"><div class="statsicon">
<?php echo $ThemeSet['StatsIcon']; ?></div></td>
<td style="width: 96%;" class="TableStatsColumn3"><div class="statsinfo">
&nbsp;Our members have made a total of <?php echo $numposts; ?> posts<br />
&nbsp;We have a total of <?php echo $numtopics; ?> topics made<br />
&nbsp;We have <?php echo $nummembers; ?> registered members<br />
&nbsp;Our newest member is <a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$NewestMem['ID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>"><?php echo $NewestMem['Name']; ?></a>
</div></td>
</tr>
<tr id="Stats5" class="TableStatsRow4">
<td class="TableStatsColumn4" colspan="2">&nbsp;</td>
</tr>
</table></div>
<div class="DivStats">&nbsp;</div>
<?php } ?>
