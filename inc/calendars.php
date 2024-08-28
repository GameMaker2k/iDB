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

    $FileInfo: calendars.php - Last Update: 8/26/2024 SVN 1048 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="calendars.php"||$File3Name=="/calendars.php") {
	require('index.php');
	exit(); }
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
$_SESSION['ViewingPage'] = url_maker(null,"no+ext","act=view","&","=",null,null);
if($Settings['file_ext']!="no+ext"&&$Settings['file_ext']!="no ext") {
$_SESSION['ViewingFile'] = $exfile['calendar'].$Settings['file_ext']; }
if($Settings['file_ext']=="no+ext"||$Settings['file_ext']=="no ext") {
$_SESSION['ViewingFile'] = $exfile['calendar']; }
$_SESSION['PreViewingTitle'] = "Viewing";
$_SESSION['ViewingTitle'] = "Calendar";
$_SESSION['ExtraData'] = "currentact:view; currentcategoryid:0; currentforumid:0; currenttopicid:0; currentmessageid:0; currenteventid:0; currentmemberid:0;";
$calcurtime = new DateTime();
$calcurtime->setTimestamp($defcurtime->getTimestamp());
$calcurtime->setTimezone($usertz);
if(!isset($_GET['caldate'])&&(!isset($_GET['calmonth']) && !isset($_GET['calyear']))) { 
    $_GET['caldate'] = $calcurtime->format("mY"); }
if(isset($_GET['caldate'])&&!is_numeric($_GET['caldate'])) { 
    $_GET['caldate'] = $calcurtime->format("mY"); }
if(!isset($_GET['HighligtDay'])) { $_GET['HighligtDay'] = null; }
if(!isset($_GET['calmadd'])) { $_GET['calmadd'] = 0; }
if(!is_numeric($_GET['calmadd'])) { $_GET['calmadd'] = 0; }
if((!isset($_GET['calmonth']) && !isset($_GET['calyear'])) && 
isset($_GET['caldate']) && strlen($_GET['caldate'])==2) {
$_GET['caldate'] = $_GET['caldate'].$calcurtime->format("Y"); }
if((!isset($_GET['calmonth']) && !isset($_GET['calyear'])) && 
isset($_GET['caldate']) && strlen($_GET['caldate'])==6) {
preg_match_all("/([0-9]{2})([0-9]{4})/is", $_GET['caldate'], $datecheck);
$_GET['calmonth'] = $datecheck[1][0];
$_GET['calyear'] = $datecheck[2][0]; }
if((isset($_GET['calmonth']) && isset($_GET['calyear'])) && 
   (strlen($_GET['calmonth'])==2&&strlen($_GET['calyear'])==4) ) {
$year1 = date("Y", strtotime($_GET['calyear']."-".$_GET['calmonth']."-01"));
$year2 = date("Y", $utccurtime->getTimestamp());
$month1 = date("m", strtotime($_GET['calyear']."-".$_GET['calmonth']."-01"));
$month2 = date("m", $utccurtime->getTimestamp());
$redirdate = ((($year2 - $year1) * 12) + ($month2 - $month1)) * -1;
$_GET['calmadd'] = $redirdate; }
$nextcalm = $_GET['calmadd'] + 1;
$backcalm = $_GET['calmadd'] - 1;
$calmcount = abs($_GET['calmadd']);
$getcurmonth = $usercurtime->format("m");
$getcuryear = $usercurtime->format("y");
$getcurtmsp = mktime(0, 0, 0, $getcurmonth, 1, $getcuryear);
$getnextmsp = mktime(0, 0, 0, ($getcurmonth + $nextcalm), 1, $getcuryear);
$nexmonthnum = date("m", $getnextmsp);
$nexyearnum = date("Y", $getnextmsp);
$nexcaldate = $nexmonthnum.$nexyearnum;
$getbactmsp = mktime(0, 0, 0, ($getcurmonth + $backcalm), 1, $getcuryear);
$bacmonthnum = date("m", $getbactmsp);
$bacyearnum = date("Y", $getbactmsp);
$baccaldate = $bacmonthnum.$bacyearnum;
$tmpcalmnum = 0;
$tmpcalmadd = 0;
$tmpcalcount = 1;
if($_GET['calmadd']>0) {
while($tmpcalcount <= $calmcount) {
$calcurtime->setTimestamp($defcurtime->getTimestamp()+$tmpcalmadd);
$tmpdaystart = $calcurtime->format("d");
$tmpdaycount = $calcurtime->format("t");
if($tmpdaystart>=1) { $tmpcalmnum += ($tmpdaycount - $tmpdaystart) + 1; }
if($tmpdaystart<1) { $tmpcalmnum += $tmpdaycount; }
$tmpcalmadd = $tmpcalmnum * $dayconv['day'];
++$tmpcalcount; }
$calmounthaddd = $tmpcalmadd; }
if($_GET['calmadd']<0) {
while($tmpcalcount <= $calmcount) {
$calcurtime->setTimestamp($defcurtime->getTimestamp()+$tmpcalmadd);
$tmpdaystart = $calcurtime->format("d");
$tmpdaycount = $calcurtime->format("t");
if($tmpdaystart>=1) { $tmpcalmnum -= $tmpdaystart + 1; }
if($tmpdaystart<1) { $tmpcalmnum -= $tmpdaycount; }
$tmpcalmadd = $tmpcalmnum * $dayconv['day'];
++$tmpcalcount; }
$calmounthaddd = $tmpcalmadd; }
// Extra month stuff
$MyRealMonthNum1 = $usercurtime->format("m");
$MyRealYear = $usercurtime->format("Y");
// Count the Days in this month
if(!isset($calmounthaddd)) { $calmounthaddd = 0; }
$MyTimeStamp = $utccurtime->getTimestamp() + $calmounthaddd;
//$calcurtime->setTimestamp($defcurtime->getTimestamp()+$calmounthaddd);
$calcurtime->setDate($_GET['calyear'], $_GET['calmonth'], 1);
$CountDays = $calcurtime->format("t");
$MyDay = $calcurtime->format("j");
$MyDay2 = $calcurtime->format("jS");
$MyDayNum = $calcurtime->format("d");
$MyDayName = $calcurtime->format("l");
$MyYear = $calcurtime->format("Y");
$MyYear2 = $calcurtime->format("y");
$MyMonth = $calcurtime->format("m");
$MyTimeStamp1 = mktime(0,0,0,$MyMonth,1,$MyYear);
$MyTimeStamp2 = mktime(23,59,59,$MyMonth,$CountDays,$MyYear);
$MyMonthName = $calcurtime->format("F");
$MyMonthNum1 = $calcurtime->format("m");
$MyMonthNum2 = $calcurtime->format("n");
$FirstDayThisMonth = date("w", mktime(0, 0, 0, $MyMonth, 1, $MyYear));
$MyCurDay = $usercurtime->format("j");
$MyCurYear = $usercurtime->format("Y");
$MyCurMonth = $usercurtime->format("m");
$EventsName = array();
$query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."events\" WHERE (\"EventMonth\">=%i AND \"EventYear\"<%i AND \"EventYearEnd\">=%i) OR (\"EventMonth\"<=%i AND \"EventMonthEnd\">=%i AND \"EventYearEnd\">=%i) OR (\"EventMonth\"<=%i AND \"EventMonthEnd\"<=%i AND \"EventYear\"<=%i AND \"EventYearEnd\">%i)",  array($MyMonth,$MyYear,$MyYear,$MyMonth,$MyMonth,$MyYear,$MyMonth,$MyMonth,$MyYear,$MyYear));
$result=sql_query($query,$SQLStat);
$num=sql_num_rows($result);
$is=0;
while ($is < $num) {
$EventID=sql_result($result,$is,"id");
$EventUser=sql_result($result,$is,"UserID");
$EventGuest=sql_result($result,$is,"GuestName");
$EventName=sql_result($result,$is,"EventName");
$EventText=sql_result($result,$is,"EventText");
$EventStart=sql_result($result,$is,"TimeStamp");
$EventEnd=sql_result($result,$is,"TimeStampEnd");
$eventstartcurtime = new DateTime();
$eventstartcurtime->setTimestamp($EventStart);
$eventstartcurtime->setTimezone($usertz);
$eventendcurtime = new DateTime();
$eventendcurtime->setTimestamp($EventEnd);
$eventendcurtime->setTimezone($usertz);
//$EventMonth=sql_result($result,$is,"EventMonth");
$EventMonth=$eventstartcurtime->format("m");
//$EventMonthEnd=sql_result($result,$is,"EventMonthEnd");
$EventMonthEnd=$eventendcurtime->format("m");
//$EventDay=sql_result($result,$is,"EventDay");
$EventDay=$eventstartcurtime->format("j");
//$EventDayEnd=sql_result($result,$is,"EventDayEnd");
$EventDayEnd=$eventendcurtime->format("j");
//$EventYear=sql_result($result,$is,"EventYear");
$EventYear=$eventstartcurtime->format("Y");
//$EventYearEnd=sql_result($result,$is,"EventYearEnd");
$EventYearEnd=$eventendcurtime->format("Y");
if($EventMonthEnd!=$MyMonth) { $EventDayEnd = $CountDays; }
if($EventMonth<$MyMonth) { $EventDay = 1; }
$oldeventname=$EventName;
$EventName1 = pre_substr($EventName,0,20);
if (pre_strlen($EventName)>20) { $EventName1 = $EventName1."..."; }
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
sql_free_result($result);
$bdquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"BirthMonth\"=%i AND \"BirthYear\"<=%i", array($MyMonth, $MyYear));
$bdresult=sql_query($bdquery,$SQLStat);
$bdnum=sql_num_rows($bdresult);
$bdi=0;
while ($bdi < $bdnum) {
$UserNamebd=sql_result($bdresult,$bdi,"Name");
$BirthDay=sql_result($bdresult,$bdi,"BirthDay");
$BirthMonth=sql_result($bdresult,$bdi,"BirthMonth");
$BirthYear=sql_result($bdresult,$bdi,"BirthYear");
$UserCurAge=$MyYear-$BirthYear;
$oldusername=$UserNamebd;
$UserNamebd1 = pre_substr($UserNamebd,0,20);
if (pre_strlen($UserNamebd)>20) { $UserNamebd1 = $UserNamebd1."..."; }
$UserNamebd=$UserNamebd1;
if(!isset($EventsName[$BirthDay])) { $EventsName[$BirthDay] = null; }
if ($EventsName[$BirthDay] != null) {
	$EventsName[$BirthDay] .= ", <span title=\"".$oldusername." is ".$UserCurAge." years old\">".$UserNamebd1."</span>";	 }
if ($EventsName[$BirthDay] == null) {
	$EventsName[$BirthDay] = "<span title=\"".$oldusername." is ".$UserCurAge." years old\">".$UserNamebd1."</span>"; }
++$bdi; } 
sql_free_result($bdresult);
$MyDays = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
$DayNames = "";
foreach ($MyDays as $x => $y) {
    $DayNames .= '<th class="CalTableColumn2" style="width: 12%;">' . $y . '</th>'."\r\n";
}
$WeekDays = "";
$i = $FirstDayThisMonth + 1;
if ($FirstDayThisMonth != "0") {
    $WeekDays .= '<td class="CalTableColumn3Blank" style="text-align: center;" colspan="' . $FirstDayThisMonth . '">&#160;</td>'."\r\n";
}
$Day_i = "1";
$ii = $i;
for ($i; $i <= ($CountDays + $FirstDayThisMonth) ;$i++) {
if ($ii == 8) {
$WeekDays .= "</tr><tr class=\"CalTableRow3\">"."\r\n";
$ii = 1; }
 if ($MyCurDay == $Day_i && $MyCurMonth == $MyRealMonthNum1 && $MyCurYear == $MyRealYear) {
$Extra = 'CalTableColumn3Current'; }
else {
$Extra = 'CalTableColumn3'; }
if ($Day_i != $_GET['HighligtDay']) {
if(!isset($EventsName[$Day_i])) { $EventsName[$Day_i] = null; }
if($EventsName[$Day_i]!=null) { $EventsName[$Day_i] = "&#160;( ".$EventsName[$Day_i]." )"; }
if ($Day_i != $MyCurDay) {
$WeekDays .= '<td class="'.$Extra.'" style="vertical-align: top;"><div class="CalDate">' . $Day_i . '</div>' . $EventsName[$Day_i] . '</td>'."\r\n";	 }	}
if ($Day_i == $MyCurDay) {
$WeekDays .= '<td class="'.$Extra.'" style="vertical-align: top;"><div class="CalDateCurrent">' . $Day_i  . '</div>' . $EventsName[$Day_i] . '</td>'."\r\n";	 }
$Day_i++;
$ii++;
}
if ((8 - $ii) >= "1") {
$WeekDays .= '<td class="CalTableColumn3Blank" style="text-align: center;" colspan="' . (8 - $ii) . '">&#160;</td>'."\r\n"; } ?>
<div class="NavLinks"><?php echo $ThemeSet['NavLinkIcon']; ?><a href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a><?php echo $ThemeSet['NavLinkDivider']; ?><a href="<?php echo url_maker($exfile['calendar'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['calendar'],$exqstr['calendar']); ?>">Calendar</a></div>
<div class="DivNavLinks">&#160;</div>
<div class="CalTable1Border">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="CalTableRow1" style="font-weight: bold;">
<span style="float: left;"><?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['calendar'],$Settings['file_ext'],"act=view&caldate=".$MyMonth.$MyYear,$Settings['qstr'],$Settings['qsep'],$prexqstr['calendar'],$exqstr['calendar']); ?>" title="<?php echo "Viewing ".$MyMonthName." ".$MyYear; ?>"><?php echo "Viewing ".$MyMonthName." ".$MyYear; ?></a>&#160;</span>&#160;
<span style="float: right;">&#160;<a href="<?php echo url_maker($exfile['calendar'],$Settings['file_ext'],"act=view&caldate=".$baccaldate,$Settings['qstr'],$Settings['qsep'],$prexqstr['calendar'],$exqstr['calendar']); ?>">&lt;</a><?php echo $ThemeSet['LineDivider']; ?><a href="<?php echo url_maker($exfile['calendar'],$Settings['file_ext'],"act=view&caldate=".$nexcaldate,$Settings['qstr'],$Settings['qsep'],$prexqstr['calendar'],$exqstr['calendar']); ?>">&gt;</a>&#160;</span>&#160;</div>
<?php } ?>
<table class="CalTable1">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="CalTableRow1">
<th class="CalTableColumn1" colspan="7">
<span style="float: left;"><?php echo $ThemeSet['TitleIcon']; ?><?php echo "Viewing ".$MyMonthName." ".$MyYear; ?>&#160;</span>&#160;
<span style="float: right;">&#160;<a href="<?php echo url_maker($exfile['calendar'],$Settings['file_ext'],"act=view&caldate=".$baccaldate,$Settings['qstr'],$Settings['qsep'],$prexqstr['calendar'],$exqstr['calendar']); ?>">&lt;</a><?php echo $ThemeSet['LineDivider']; ?><a href="<?php echo url_maker($exfile['calendar'],$Settings['file_ext'],"act=view&caldate=".$nexcaldate,$Settings['qstr'],$Settings['qsep'],$prexqstr['calendar'],$exqstr['calendar']); ?>">&gt;</a>&#160;</span>&#160;
</th>
</tr><?php } ?>
<tr class="CalTableRow2">
<?php echo $DayNames; ?>
</tr><tr class="CalTableRow3">
<?php echo $WeekDays; ?>
</tr>
<tr class="CalTableRow4">
<td class="CalTableColumn4" colspan="7">&#160;</td>
</tr>
</table></div>
<div class="DivCalendar">&#160;</div>
