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
    iDB Installer made by Game Maker 2k - http://idb.berlios.net/

    $FileInfo: presetup.php - Last Update: 12/12/2008 SVN 214 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="presetup.php"||$File3Name=="/presetup.php") {
	require('index.php');
	exit(); }
if(!isset($SetupDir['setup'])) { $SetupDir['setup'] = "setup/"; }
if(!isset($SetupDir['convert'])) { $SetupDir['convert'] = "setup/convert/"; }
if ($_POST['License']!="Agree") { $Error="Yes";  ?>
<tr class="TableRow3">
<td class="TableColumn3">
<span class="TableMessage">
<br />You need to  agree to the tos.<br /></span>
<?php }
if($Error!="Yes") {
?>
<tr class="TableRow3">
<td class="TableColumn3">
<form style="display: inline;" method="post" name="install" id="install" action="install.php?act=Part3">
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