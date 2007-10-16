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

    $FileInfo: license.php - Last Update: 10/16/2007 SVN 118 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="presetup.php"||$File3Name=="/presetup.php") {
	require('index.php');
	exit(); }
if(!isset($SetupDir['setup'])) { $SetupDir['setup'] = "setup/"; }
if(!isset($SetupDir['convert'])) { $SetupDir['convert'] = "setup/convert/"; }
?>
<tr class="TableRow3">
<td class="TableRow3">
<form style="display: inline;" method="post" name="install" id="install" action="install.php?act=Part2">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="LicenseBox">License - Please read fully and check 'I agree' box ONLY if you agree to license</label><br />
	<textarea rows="34" id="LicenseBox" name="LicenseBox" class="TextBox" cols="79" readonly="readonly" accesskey="L">
	<?php echo file_get_contents("LICENSE"); ?></textarea><br />
	<input type="checkbox" class="TextBox" name="License" value="Agree" id="License" /><label class="TextBoxLabel" for="License">I Agree</label><br/></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<?php if($ConvertInfo['ConvertFile']==null) { ?>
<input type="hidden" name="SetupType" value="install" style="display: none;" />
<?php } ?>
<input type="hidden" name="act" value="Part2" style="display: none;" />
<input type="submit" class="Button" value="Next Page" name="Install_Board" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>