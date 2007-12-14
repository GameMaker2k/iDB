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

    $FileInfo: events.php - Last Update: 12/14/2007 SVN 138 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="events.php"||$File3Name=="/events.php") {
	require('index.php');
	exit(); }
if($_GET['act']=="view"||$_GET['act']==null) {
$query = query("SELECT * FROM `".$Settings['sqltable']."events` WHERE `id`=%i", array($_GET['id']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
$is=0;
if($num==0) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false)); @mysql_free_result($result);
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
?>
<div class="Table1Border">
<table class="Table1">
<?php
while ($is < $num) {
$EventID=mysql_result($result,$is,"id");
$EventUser=mysql_result($result,$is,"UserID");
$EventGuest=mysql_result($result,$is,"GuestName");
$EventName=mysql_result($result,$is,"EventName");
$EventText=mysql_result($result,$is,"EventText");
$EventText = preg_replace("/\<br\>/", "<br />\n", nl2br($EventText));
$EventStart=mysql_result($result,$is,"TimeStamp");
$EventEnd=mysql_result($result,$is,"TimeStampEnd");
$EventStart = GMTimeChange("M. j Y",$EventStart,null);
$EventEnd = GMTimeChange("M. j Y",$EventEnd,null);
$requery = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i", array($EventUser));
$reresult=mysql_query($requery);
$renum=mysql_num_rows($reresult);
$rei=0;
while ($rei < $renum) {
$User1ID=$EventUser;
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
$User1Signature = preg_replace("/\<br\>/", "<br />\n", nl2br($User1Signature));
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
if($User1Name=="Guest") { $User1Name=$EventGuest;
if($User1Name==null) { $User1Name="Guest"; } }
$EventText = text2icons($EventText,$Settings['sqltable']); 
$User1Signature = text2icons($User1Signature,$Settings['sqltable']);
?>
<tr class="TableRow1">
<td class="TableRow1" colspan="2"><span style="font-weight: bold; float: left;"><?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['event'],$Settings['file_ext'],"act=view&id=".$_GET['id'],$Settings['qstr'],$Settings['qsep'],$prexqstr['event'],$exqstr['event']); ?>"><?php echo $EventName; ?></a></span>
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
<span style="font-weight: bold;">Event Start: </span><?php echo $EventStart; ?><?php echo $ThemeSet['LineDividerTopic']; ?><span style="font-weight: bold;">Event End: </span><?php echo $EventEnd; ?>
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
<div class="eventpost"><?php echo $EventText; ?></div>
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
<span style="float: right;">&nbsp;</span></td>
</tr>
</table></div>
<?php } if($_GET['act']=="create") { 
if($GroupInfo['CanAddEvents']=="no") { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }	
?>
<div class="Table1Border">
<table class="Table1" id="MakeEvent">
<tr class="TableRow1" id="EventStart">
<td class="TableRow1" colspan="2"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['calendar'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['calendar'],$exqstr['calendar']); ?>">Making a Event</a></span>
<?php echo "<span style=\"float: right;\">&nbsp;</span>"; ?></td>
</tr>
<tr id="MakeEventRow" class="TableRow2">
<td class="TableRow2" colspan="2" style="width: 100%;">Making a Event</td>
</tr>
<tr class="TableRow3" id="MkEvent">
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
<form style="display: inline;" method="post" id="MkEventForm" action="<?php echo url_maker($exfile['event'],$Settings['file_ext'],"act=makeevent",$Settings['qstr'],$Settings['qsep'],$prexqstr['event'],$exqstr['event']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="EventName">Insert Event Name:</label></td>
	<td style="width: 50%;"><input maxlength="30" type="text" name="EventName" class="TextBox" id="EventName" size="20" /></td>
</tr><?php if($_SESSION['UserGroup']==$Settings['GuestGroup']) { ?><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="GuestName">Insert Guest Name:</label></td>
	<td style="width: 50%;"><input maxlength="25" type="text" name="GuestName" class="TextBox" id="GuestName" size="20" /></td>
</tr><?php } ?><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="EventStart">Insert Event Start:</label></td>
	<td style="width: 50%;"><input maxlength="10" type="text" name="EventStart" class="TextBox" id="EventStart" size="20" value="MM/DD/YYYY" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="EventEnd">Insert Event End:</label></td>
	<td style="width: 50%;"><input maxlength="10" type="text" name="EventEnd" class="TextBox" id="EventEnd" size="20" value="MM/DD/YYYY" /></td>
</tr>
</table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<label class="TextBoxLabel" for="EventText">Insert Event Text:</label><br />
<textarea rows="10" name="EventText" id="EventText" cols="40" class="TextBox"></textarea><br />
<input type="hidden" name="act" value="makeevents" style="display: none;" />
<?php if($_SESSION['UserGroup']!=$Settings['GuestGroup']) { ?>
<input type="hidden" name="GuestName" value="null" style="display: none;" />
<?php } ?>
<input type="submit" class="Button" value="Make Event" name="make_event" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form></td></tr>
<tr id="MkEventEnd" class="TableRow4">
<td class="TableRow4" colspan="2">&nbsp;</td>
</tr>
</table></div>
<?php }  if($_GET['act']=="makeevent"&&$_POST['act']=="makeevents") {
if($GroupInfo['CanAddEvents']=="no") { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); @header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); @mysql_close(); die(); }
$MyUserID = $_SESSION['UserID']; if($MyUserID=="0"||$MyUserID==null) { $MyUserID = -1; }
$REFERERurl = parse_url($_SERVER['HTTP_REFERER']);
$URL['REFERER'] = $REFERERurl['host'];
$URL['HOST'] = $_SERVER["SERVER_NAME"];
$REFERERurl = null; unset($REFERERurl);
if(!isset($_POST['EventName'])) { $_POST['EventName'] = null; }
if(!isset($_POST['EventStart'])) { $_POST['EventStart'] = null; }
if(!isset($_POST['EventEnd'])) { $_POST['EventEnd'] = null; }
if(!isset($_POST['EventText'])) { $_POST['EventText'] = null; }
if(!isset($_POST['GuestName'])) { $_POST['GuestName'] = null; }
$TimeIn = explode("/",$_POST['EventStart']);
$TimeOut = explode("/",$_POST['EventEnd']);
?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['calendar'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['calendar'],$exqstr['calendar']); ?>">Making a Event</a></span>
<?php echo "<span style=\"float: right;\">&nbsp;</span>"; ?></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">&nbsp;Make Event Message: </th>
</tr>
<tr class="TableRow3">
<td class="TableRow3">
<table style="width: 100%; height: 25%; text-align: center;">
<?php if (pre_strlen($_POST['EventName'])>="30") { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Your Event Name is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (pre_strlen($TimeIn[0])<"2") { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Event Start Month is too small.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (pre_strlen($TimeIn[0])>"2") { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Event Start Month is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (pre_strlen($TimeIn[1])<"2") { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Event Start Day is too small.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (pre_strlen($TimeIn[1])>"2") { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Event Start Day is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (pre_strlen($TimeIn[2])<"4") { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Event Start Year is too small.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (pre_strlen($TimeIn[2])>"4") { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Event Start Year is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (pre_strlen($TimeOut[0])<"2") { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Event End Month is too small.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (pre_strlen($TimeOut[0])>"2") { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Event End Month is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (pre_strlen($TimeOut[1])<"2") { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Event End Day is too small.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (pre_strlen($TimeOut[1])>"2") { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Event End Day is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (pre_strlen($TimeOut[2])<"4") { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Event End Year is too small.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (pre_strlen($TimeOut[2])>"4") { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Event End Year is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	pre_strlen($_POST['GuestName'])>="25") { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You Guest Name is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($Settings['TestReferer']==true) {
	if ($URL['HOST']!=$URL['REFERER']) { $Error="Yes";  ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />Sorry the referering url dose not match our host name.<br />
	</span>&nbsp;</td>
</tr>
<?php } }
$_POST['EventName'] = stripcslashes(htmlspecialchars($_POST['EventName'], ENT_QUOTES, $Settings['charset']));
//$_POST['EventName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['EventName']);
$_POST['EventName'] = @remove_spaces($_POST['EventName']);
$_POST['GuestName'] = stripcslashes(htmlspecialchars($_POST['GuestName'], ENT_QUOTES, $Settings['charset']));
//$_POST['GuestName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['GuestName']);
$_POST['GuestName'] = @remove_spaces($_POST['GuestName']);
$_POST['EventText'] = stripcslashes(htmlspecialchars($_POST['EventText'], ENT_QUOTES, $Settings['charset']));
//$_POST['EventText'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['EventText']);
$_POST['EventText'] = remove_bad_entities($_POST['EventText']);
//$_POST['EventText'] = @remove_spaces($_POST['EventText']);
/*    <_<  iWordFilter  >_>      
   by René Johnson - Cool Dude 2k */
$katarzynaqy=query("SELECT * FROM `".$Settings['sqltable']."wordfilter`", array(null));
$katarzynart=mysql_query($katarzynaqy);
$katarzynanm=mysql_num_rows($katarzynart);
$katarzynas=0;
while ($katarzynas < $katarzynanm) {
$Filter=mysql_result($katarzynart,$katarzynas,"Filter");
$Replace=mysql_result($katarzynart,$katarzynas,"Replace");
$CaseInsensitive=mysql_result($katarzynart,$katarzynas,"CaseInsensitive");
if($CaseInsensitive=="on") { $CaseInsensitive = "yes"; }
if($CaseInsensitive=="off") { $CaseInsensitive = "no"; }
if($CaseInsensitive!="yes"||$CaseInsensitive!="no") { $CaseInsensitive = "no"; }
$WholeWord=mysql_result($katarzynart,$katarzynas,"WholeWord");
if($WholeWord=="on") { $WholeWord = "yes"; }
if($WholeWord=="off") { $WholeWord = "no"; }
if($WholeWord!="yes"||$WholeWord!="no") { $WholeWord = "no"; }
$Filter = preg_quote($Filter, "/");
if($CaseInsensitive!="yes"&&$WholeWord=="yes") {
$_POST['EventText'] = preg_replace("/\b(".$Filter.")\b/", $Replace, $_POST['EventText']); }
if($CaseInsensitive=="yes"&&$WholeWord=="yes") {
$_POST['EventText'] = preg_replace("/\b(".$Filter.")\b/i", $Replace, $_POST['EventText']); }
if($CaseInsensitive!="yes"&&$WholeWord!="yes") {
$_POST['EventText'] = preg_replace("/".$Filter."/", $Replace, $_POST['EventText']); }
if($CaseInsensitive=="yes"&&$WholeWord!="yes") {
$_POST['EventText'] = preg_replace("/".$Filter."/i", $Replace, $_POST['EventText']); }
++$katarzynas; } @mysql_free_result($katarzynart);
$lonewolfqy=query("SELECT * FROM `".$Settings['sqltable']."restrictedwords` WHERE `RestrictedEventName`='yes' or `RestrictedUserName`='yes'", array(null));
$lonewolfrt=mysql_query($lonewolfqy);
$lonewolfnm=mysql_num_rows($lonewolfrt);
$lonewolfs=0; $RMatches = null; $RGMatches = null;
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
$RestrictedEventName=mysql_result($lonewolfrt,$lonewolfs,"RestrictedEventName");
if($RestrictedEventName=="on") { $RestrictedEventName = "yes"; }
if($RestrictedEventName=="off") { $RestrictedEventName = "no"; }
if($RestrictedEventName!="yes"||$RestrictedEventName!="no") { $RestrictedEventName = "no"; }
$RestrictedUserName=mysql_result($lonewolfrt,$lonewolfs,"RestrictedUserName");
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
++$lonewolfs; } @mysql_free_result($lonewolfrt);
if ($_POST['EventName']==null) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter a Event Name.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($_POST['EventText']==null) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter a Event Text.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($_POST['EventStart']==null) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter date for event to start in MM/DD/YYYY format.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($_POST['EventEnd']==null) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter date for event to end in MM/DD/YYYY format.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (count($TimeIn)!="3") { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter valid date for event to start in MM/DD/YYYY format.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (count($TimeOut)!="3") { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter valid date for event to end in MM/DD/YYYY format.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (!is_numeric($TimeIn[0])||!is_numeric($TimeIn[1])||!is_numeric($TimeIn[2])) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter valid date for event to start in MM/DD/YYYY format.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (!is_numeric($TimeOut[0])||!is_numeric($TimeOut[1])||!is_numeric($TimeOut[2])) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter valid date for event to end in MM/DD/YYYY format.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (!isset($TimeIn[0])||!isset($TimeIn[1])||!isset($TimeIn[2])) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter valid date for event to start in MM/DD/YYYY format.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (!isset($TimeOut[0])||!isset($TimeOut[1])||!isset($TimeOut[2])) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter valid date for event to end in MM/DD/YYYY format.<br />
	</span>&nbsp;</td>
</tr>
<?php } if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	$_POST['GuestName']==null) { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You need to enter a Guest Name.<br />
	</span>&nbsp;</td>
</tr>
<?php } if($_SESSION['UserGroup']==$Settings['GuestGroup']&&
	$RGMatches==true) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />This Guest Name is restricted to use.<br />
	</span>&nbsp;</td>
</tr>
<?php } if($GroupInfo['CanAddEvents']=="no") { $Error="Yes"; ?>
<tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage">
	<br />You do not have permission to make a event here.<br />
	</span>&nbsp;</td>
</tr>
<?php } if($RMatches==true) { $Error="Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />This User Name is restricted to use.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($Error=="Yes") {
@redirect("refresh",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false),"4"); ?>
<tr>
	<td><span class="TableMessage">
	<br />Click <a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>">here</a> to goto index page.<br />&nbsp;
	</span><br /></td>
</tr>
<?php } if ($Error!="Yes") { 
$TimeSIn = mktime(0,0,0,$TimeIn[0],$TimeIn[1],$TimeIn[2]);
$TimeSOut = mktime(23,59,59,$TimeOut[0],$TimeOut[1],$TimeOut[2]);
$EventMonth=GMTimeChange("m",$TimeSIn,0,0,"off");
$EventMonthEnd=GMTimeChange("m",$TimeSOut,0,0,"off");
$EventDay=GMTimeChange("d",$TimeSIn,0,0,"off");
$EventDayEnd=GMTimeChange("d",$TimeSOut,0,0,"off");
$EventYear=GMTimeChange("Y",$TimeSIn,0,0,"off");
$EventYearEnd=GMTimeChange("Y",$TimeSOut,0,0,"off");
$eventid = getnextid($Settings['sqltable'],"events");
$User1ID=$MyUserID;
if($_SESSION['UserGroup']==$Settings['GuestGroup']) { $User1Name = $_POST['GuestName']; }
if($_SESSION['UserGroup']!=$Settings['GuestGroup']) { $User1Name = $_SESSION['MemberName']; }
$query = query("INSERT INTO ".$Settings['sqltable']."events VALUES (%i, %i, '%s', '%s', '%s', %i, %i, %i, %i, %i, %i, %i, %i)", array($eventid,$User1ID,$User1Name,$_POST['EventName'],$_POST['EventText'],$TimeSIn,$TimeSOut,$EventMonth,$EventMonthEnd,$EventDay,$EventDayEnd,$EventYear,$EventYearEnd));
mysql_query($query);
@redirect("refresh",$basedir.url_maker($exfile['event'],$Settings['file_ext'],"act=event&id=".$eventid,$Settings['qstr'],$Settings['qsep'],$prexqstr['event'],$exqstr['event'],FALSE),"3");
?><tr style="text-align: center;">
	<td style="text-align: center;"><span class="TableMessage"><br />
	Event <?php echo $_POST['EventName']; ?> was started.<br />
	Click <a href="<?php echo url_maker($exfile['event'],$Settings['file_ext'],"act=event&id=".$eventid,$Settings['qstr'],$Settings['qsep'],$prexqstr['event'],$exqstr['event']); ?>">here</a> to continue to event.<br />&nbsp;
	</span><br /></td>
</tr>
<?php } ?>
</table>
</td></tr>
<tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table></div>
<?php } ?>
<div>&nbsp;</div>