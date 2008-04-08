<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2008 Cool Dude 2k - http://idb.berlios.de/
    Copyright 2004-2008 Game Maker 2k - http://intdb.sourceforge.net/

    $FileInfo: stats.php - Last Update: 04/08/2008 SVN 158 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="stats.php"||$File3Name=="/stats.php") {
	require('index.php');
	exit(); }
if($_GET['act']=="view"||$_GET['act']=="stats") {
$ntquery = query("SELECT COUNT(*) FROM `".$Settings['sqltable']."topics`", array(null));
$ntresult = mysql_query($ntquery);
$numtopics = mysql_result($ntresult,0);
$npquery = query("SELECT COUNT(*) FROM `".$Settings['sqltable']."posts`", array(null));
$npresult = mysql_query($npquery);
$numposts = mysql_result($npresult,0);
$nmquery = query("SELECT SQL_CALC_FOUND_ROWS * FROM `".$Settings['sqltable']."members` order by `Joined` DESC LIMIT 1", array(null));
$rnmquery = query("SELECT FOUND_ROWS();", array(null));
$nmresult = mysql_query($nmquery);
$rnmresult = mysql_query($rnmquery);
//$nummembers = mysql_num_rows($nmresult);
$nummembers = mysql_result($rnmresult,0);
$NewestMem = array(null);
$NewestMem['ID']=mysql_result($nmresult,0,"id");
$NewestMem['Name']=mysql_result($nmresult,0,"Name");
if($NewestMem['ID']=="-1") { $NewestMem['ID'] = "0"; $NewestMem['Name'] = "Cool Dude 2k"; }
$sql_guest_check = mysql_query(query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i LIMIT 1", array("-1")));
$guest_check = mysql_num_rows($sql_guest_check); @mysql_free_result($sql_guest_check);
if($guest_check > 0) { $nummembers = $nummembers - 1; }
?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1" colspan="2"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a id="bstats" href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=stats",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>#bstats">Board Statistics</a></span>
<?php echo "<span style=\"float: right;\">&nbsp;</span>"; ?></td>
</tr>
<tr id="Stats1" class="TableRow2">
<td class="TableRow2" colspan="2" style="width: 100%; font-weight: bold;">Board Stats</td>
</tr>
<tr class="TableRow3" id="Stats2">
<td style="width: 4%;" class="TableRow3"><div class="statsicon">
<?php echo $ThemeSet['StatsIcon']; ?></div></td>
<td style="width: 96%;" class="TableRow3"><div class="statsinfo">
&nbsp;Our members have made a total of <?php echo $numposts; ?> posts<br />
&nbsp;We have a total of <?php echo $numtopics; ?> topics made<br />
&nbsp;We have <?php echo $nummembers; ?> registered members<br />
&nbsp;Our newest member is <a href="<?php echo url_maker($exfile['member'],$Settings['file_ext'],"act=view&id=".$NewestMem['ID'],$Settings['qstr'],$Settings['qsep'],$prexqstr['member'],$exqstr['member']); ?>"><?php echo $NewestMem['Name']; ?></a>
</div></td>
</tr>
<tr id="Stats3" class="TableRow4">
<td class="TableRow4" colspan="2">&nbsp;</td>
</tr>
</table></div>
<div>&nbsp;</div>
<?php
@mysql_free_result($ntresult);
@mysql_free_result($npresult);
@mysql_free_result($nmresult); }
?>