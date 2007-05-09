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
    iDB Installer made by Game Maker 2k - http://idb.berlios.net/

    $FileInfo: presetup.php - Last Update: 05/09/2007 SVN 1 - Author: cooldude2k $
*/
$File1Name = dirname($_SERVER['SCRIPT_NAME'])."/";
$File2Name = $_SERVER['SCRIPT_NAME'];
$File3Name=str_replace($File1Name, null, $File2Name);
if ($File3Name=="presetup.php"||$File3Name=="/presetup.php") {
	require('index.php');
	exit(); }
if($SetupDir['setup']==null) { $SetupDir['setup'] = "setup/"; }
if($SetupDir['convert']==null) { $SetupDir['convert'] = "setup/convert/"; }
if ($_POST['License']!="Agree") { $Error="Yes";  ?>
<tr class="TableRow3">
<td class="TableRow3">
<span class="TableMessage">
<br />You need to  agree to the tos.<br /></span>
<?php }
if($Error!="Yes") {
?>
<tr class="TableRow3">
<td class="TableRow3">
<form method="post" name="install" id="install" action="install.php?act=Part3">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DatabaseUserName">Insert Database User Name:</label></td>
	<td style="width: 50%;"><input type="text" name="DatabaseUserName" class="TextBox" id="DatabaseUserName" size="20" /></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="DatabasePassword">Insert Database Password:</label></td>
	<td style="width: 50%;"><input type="password" name="DatabasePassword" class="TextBox" id="DatabasePassword" size="20" /></td>

</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="DatabaseHost">Insert Database Host:</label></td>
	<td style="width: 50%;"><input type="text" name="DatabaseHost" class="TextBox" id="DatabaseHost" size="20" value="localhost" /></td>
	<?php if($ConvertInfo['ConvertFile']!=null) { ?>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="SetupType">Type of install to do:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="SetupType" id="SetupType">
	<option value="convert" selected="selected"><?php echo $ConvertInfo['ConvertName']; ?></option>
	<option value="install">Install iDB</option>
	</select></td>
	<?php } ?>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<?php if($ConvertInfo['ConvertFile']==null) { ?>
<input type="hidden" name="SetupType" value="install" style="display: none;" />
<?php } ?>
<input type="hidden" name="act" value="Part3" style="display: none;" />
<input type="submit" class="Button" value="Next Page" name="Install_Board" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<?php } ?>