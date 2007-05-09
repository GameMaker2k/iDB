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

    $FileInfo: stats.php - Last Update: 05/09/2007 SVN 1 - Author: cooldude2k $
*/
$File1Name = dirname($_SERVER['SCRIPT_NAME'])."/";
$File2Name = $_SERVER['SCRIPT_NAME'];
$File3Name=str_replace($File1Name, null, $File2Name);
if ($File3Name=="stats.php"||$File3Name=="/stats.php") {
	require('index.php');
	exit(); }
if($_GET['act']=="view"||$_GET['act']=="stats") { $toggle = null; 
$togglecode = "<span style=\"float: right;\">&nbsp;</span>";
if($ThemeSet['EnableToggle']==true) {
$toggle = "toggletag('Stats1'),toggletag('Stats2'),toggletag('Stats3');return false;";
$togglecode = "<span style=\"float: right;\"><a href=\"".$filewpath."#Toggle\" onclick=\"".$toggle."\">".$ThemeSet['Toggle']."</a>".$ThemeSet['ToggleExt']."</span>"; }
if($ThemeSet['EnableToggle']==false) { $toggle = null;
$togglecode = "<span style=\"float: right;\">&nbsp;</span>"; }
$ntquery = query("select * from ".$Settings['sqltable']."topics", array(null));
$ntresult = mysql_query($ntquery);
$numtopics = mysql_num_rows($ntresult);
$npquery = query("select * from ".$Settings['sqltable']."posts", array(null));
$npresult = mysql_query($npquery);
$numposts = mysql_num_rows($npresult);
$nmquery = query("select * from ".$Settings['sqltable']."members", array(null));
$nmresult = mysql_query($nmquery);
$nummembers = mysql_num_rows($nmresult);
$sql_guest_check = mysql_query(query("select * from ".$Settings['sqltable']."members where id = '%s'", array("-1")));
$guest_check = mysql_num_rows($sql_guest_check); @mysql_free_result($sql_guest_check);
if($guest_check > 0) { $nummembers = $nummembers - 1; }
?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1" colspan="2"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a id="bstats" href="<?php echo url_maker($exfile['index'],$Settings['file_ext'],"act=stats",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>#bstats">Board Statistics</a></span>
<?php echo $togglecode; ?></td>
</tr>
<tr id="Stats1" class="TableRow2">
<td class="TableRow2" colspan="2" style="width: 100%; font-weight: bold;">Board Stats</td>
</tr>
<tr class="TableRow3" id="Stats2">
<td style="width: 4%;" class="TableRow3"><div class="forumicon">
<?php echo $ThemeSet['StatsIcon']; ?>&nbsp;</div></td>
<td style="width: 96%;" class="TableRow3"><div class="forumname">
&nbsp;Our members have made a total of <?php echo $numposts; ?> post(s)<br />
&nbsp;We have a total of <?php echo $numtopics; ?> topic(s) made<br />
&nbsp;We have <?php echo $nummembers; ?> registered members<br />
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