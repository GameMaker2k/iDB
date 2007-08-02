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

    $FileInfo: events.php - Last Update: 08/02/2007 SVN 61 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="events.php"||$File3Name=="/events.php") {
	require('index.php');
	exit(); }
if($_GET['act']=="view"||$_GET['act']==null) {
$query = query("select * from `".$Settings['sqltable']."events` where `id`=%i", array($_GET['id']));
$result=mysql_query($query);
$num=mysql_num_rows($result);
$is=0;
if($num==0) { redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
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
$EventStart=mysql_result($result,$is,"TimeStamp");
$EventEnd=mysql_result($result,$is,"TimeStampEnd");
$EventStart = GMTimeChange("M. j Y",$EventStart,null);
$EventEnd = GMTimeChange("M. j Y",$EventEnd,null);
$requery = query("select * from `".$Settings['sqltable']."members` where `id`=%i", array($EventUser));
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
$gquery = query("select * from `".$Settings['sqltable']."groups` where `id`=%i", array($User1GroupID));
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
if($User1Name=="Guest") { $User1Name=$EventGuest;
if($User1Name==null) { $User1Name="Guest"; } }
$EventText = text2icons($EventText,$Settings['sqltable']); $User1Signature = text2icons($User1Signature,$Settings['sqltable']);
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
<td class="TableRow3" style="vertical-align: top;">
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
<?php } ?>
</table></div>