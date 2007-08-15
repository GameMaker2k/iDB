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

    $FileInfo: calendars.php - Last Update: 08/15/2007 SVN 83 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="calendars.php"||$File3Name=="/calendars.php") {
	require('index.php');
	exit(); }
if(!isset($_GET['HighligtDay'])) { $_GET['HighligtDay'] = null; }
// Count the Days in this month
$MyTimeStamp = GMTimeStamp();
$CountDays = GMTimeGet("t",$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$MyDay = GMTimeGet("j",$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$MyDay2 = GMTimeGet("jS",$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$MyDayNum = GMTimeGet("d",$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$MyDayName = GMTimeGet("l",$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$MyYear = GMTimeGet("Y",$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$MyYear2 = GMTimeGet("y",$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$MyMonth = GMTimeGet("m",$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$MyTimeStamp1 = mktime("0","0","0",$MyMonth,"1",$MyYear);
$MyTimeStamp2 = mktime("24","59","59",$MyMonth,$CountDays,$MyYear);
$MyMonthName = GMTimeGet("F",$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']);
$FirstDayThisMonth = date("w", mktime(0, 0, 0, $MyMonth, 1, $MyYear));
$EventsName = array();
$query = query("SELECT * FROM `".$Settings['sqltable']."events` WHERE (`EventMonth`>=%i) OR (`EventMonth`<=%i AND `EventMonthEnd`>=%i)", array($MyMonth,$MyMonth,$MyMonth));
$result=mysql_query($query);
$num=mysql_num_rows($result);
$is=0;
while ($is < $num) {
$EventID=mysql_result($result,$is,"id");
$EventUser=mysql_result($result,$is,"UserID");
$EventGuest=mysql_result($result,$is,"GuestName");
$EventName=mysql_result($result,$is,"EventName");
$EventText=mysql_result($result,$is,"EventText");
$EventStart=mysql_result($result,$is,"TimeStamp");
$EventEnd=mysql_result($result,$is,"TimeStampEnd");
$EventMonth=mysql_result($result,$is,"EventMonth");
$EventMonthEnd=mysql_result($result,$is,"EventMonthEnd");
$EventDay=mysql_result($result,$is,"EventDay");
$EventDayEnd=mysql_result($result,$is,"EventDayEnd");
$EventYear=mysql_result($result,$is,"EventYear");
$EventYearEnd=mysql_result($result,$is,"EventYearEnd");
if($EventMonthEnd!=$MyMonth) { $EventDayEnd = $CountDays; }
if($EventMonth<$MyMonth) { $EventDay = 1; }
$oldeventname=$EventName;
$EventName1 = substr($EventName,0,10);
if (strlen($EventName)>10) { $EventName1 = $EventName1."..."; }
$EventName=$EventName1;
if(!isset($EventsName[$EventDay])) { $EventsName[$EventDay] = null; }
if ($EventsName[$EventDay] != null) {
	$EventsName[$EventDay] .= ", <a href=\"".url_maker($exfile['event'],$Settings['file_ext'],"act=event&id=".$EventID,$Settings['qstr'],$Settings['qsep'],$prexqstr['event'],$exqstr['event'])."\" style=\"font-size: 9px;\" title=\"View Event ".$oldeventname.".\">".$EventName."</a>";	 }
if ($EventsName[$EventDay] == null) {
	$EventsName[$EventDay] = "<a href=\"".url_maker($exfile['event'],$Settings['file_ext'],"act=event&id=".$EventID,$Settings['qstr'],$Settings['qsep'],$prexqstr['event'],$exqstr['event'])."\" style=\"font-size: 9px;\" title=\"View Event ".$oldeventname.".\">".$EventName."</a>"; }
if ($EventDay<$EventDayEnd) {
$NextDay = $EventDay+1;
$EventDayEnd = $EventDayEnd+1;
while ($NextDay < $EventDayEnd) {
if(!isset($EventsName[$NextDay])) { $EventsName[$NextDay] = null; }
if ($EventsName[$NextDay] != null) {
	$EventsName[$NextDay] .= ", <a href=\"".url_maker($exfile['event'],$Settings['file_ext'],"act=event&id=".$EventID,$Settings['qstr'],$Settings['qsep'],$prexqstr['event'],$exqstr['event'])."\" style=\"font-size: 9px;\" title=\"View Event ".$oldeventname.".\">".$EventName."</a>";	 }
if ($EventsName[$NextDay] == null) {
	$EventsName[$NextDay] = "<a href=\"".url_maker($exfile['event'],$Settings['file_ext'],"act=event&id=".$EventID,$Settings['qstr'],$Settings['qsep'],$prexqstr['event'],$exqstr['event'])."\" style=\"font-size: 9px;\" title=\"View Event ".$oldeventname.".\">".$EventName."</a>"; }
$NextDay++; } }
$EventsID[$EventDay] = $EventID;
++$is; } 
@mysql_free_result($result);
$bdquery = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `BirthMonth`=%i", array($MyMonth));
$bdresult=mysql_query($bdquery);
$bdnum=mysql_num_rows($bdresult);
$bdi=0;
while ($bdi < $bdnum) {
$UserNamebd=mysql_result($bdresult,$bdi,"Name");
$BirthDay=mysql_result($bdresult,$bdi,"BirthDay");
$BirthMonth=mysql_result($bdresult,$bdi,"BirthMonth");
$BirthYear=mysql_result($bdresult,$bdi,"BirthYear");
$oldusername=$UserNamebd;
$UserNamebd1 = substr($UserNamebd,0,10);
if (strlen($UserNamebd)>10) { $UserNamebd1 = $UserNamebd1."..."; }
$UserNamebd=$UserNamebd1;
if(!isset($EventsName[$BirthDay])) { $EventsName[$BirthDay] = null; }
if ($EventsName[$BirthDay] != null) {
	$EventsName[$BirthDay] .= ", <span title=\"".$oldusername."'s birthday.\">".$UserNamebd1."</span>";	 }
if ($EventsName[$BirthDay] == null) {
	$EventsName[$BirthDay] = "<span title=\"".$oldusername."'s birthday.\">".$UserNamebd1."</span>"; }
++$bdi; } 
@mysql_free_result($bdresult);
$MyDays = array();
$MyDays[] = "Sunday";
$MyDays[] = "Monday";
$MyDays[] = "Tuesday";
$MyDays[] = "Wednesday";
$MyDays[] = "Thursday";
$MyDays[] = "Friday";
$MyDays[] = "Saturday";
$DayNames = "";
foreach ($MyDays as $x => $y) {
    $DayNames .= '<th class="TableRow2" style="width: 12%;">' . $y . '</th>'."\r\n";
}
$WeekDays = "";
$i = $FirstDayThisMonth + 1;
if ($FirstDayThisMonth != "0") {
    $WeekDays .= '<td class="TableRow3" style="height: 90px; text-align: center;" colspan="' . $FirstDayThisMonth . '">&nbsp;</td>'."\r\n";
}
$Day_i = "1";
$ii = $i;
for ($i; $i <= ($CountDays + $FirstDayThisMonth) ;$i++) {
if ($ii == 8) {
$WeekDays .= "</tr><tr class=\"TableRow3\">"."\r\n";
$ii = 1; }
 if ($MyDay == $Day_i) {
$Extra = 'CalTableRow1'; }
else {
$Extra = 'CalTableRow2'; }
if ($Day_i != $_GET['HighligtDay']) {
if(!isset($EventsName[$Day_i])) { $EventsName[$Day_i] = null; }
if($EventsName[$Day_i]!=null) { $EventsName[$Day_i] = "&nbsp;( ".$EventsName[$Day_i]." )"; }
if ($Day_i != $MyDay) {
$WeekDays .= '<td class="'.$Extra.'" style="height: 90px; vertical-align: top;">' . $Day_i . $EventsName[$Day_i] . '</td>'."\r\n";	 }	}
if ($Day_i == $MyDay) {
$WeekDays .= '<td class="'.$Extra.'" style="height: 90px; vertical-align: top;">' . $Day_i . $EventsName[$Day_i] . '</td>'."\r\n";	 }
$Day_i++;
$ii++;
}
if ((8 - $ii) >= "1") {
$WeekDays .= '<td class="TableRow3" style="height: 90px; text-align: center;" colspan="' . (8 - $ii) . '">&nbsp;</td>'."\r\n"; } ?>
<div class="Table1Border">
<table class="Table1"><tr class="TableRow1">
<th class="TableRow1" colspan="7">
<span style="float: left;"><?php echo $ThemeSet['TitleIcon']; ?><?php echo "Today is ".$MyDayName." the ".$MyDay2." of ".$MyMonthName.", ".$MyYear; ?></span>
<span style="float: right;"><?php echo "The time is ".GMTimeGet('g:i a',$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']); ?>&nbsp;</span>
</th>
</tr><tr class="TableRow2">
<?php echo $DayNames; ?>
</tr><tr class="TableRow3">
<?php echo $WeekDays; ?>
</tr>
<tr class="TableRow4">
<td class="TableRow4" colspan="7">&nbsp;</td>
</tr>
</table></div>
<div>&nbsp;</div>
