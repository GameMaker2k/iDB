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
    iDB Installer made by Game Maker 2k - http://idb.berlios.net/

    $FileInfo: license.php - Last Update: 8/23/2024 SVN 1023 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "presetup.php" || $File3Name == "/presetup.php") {
    require('index.php');
    exit();
}
if (!isset($SetupDir['setup'])) {
    $SetupDir['setup'] = "setup/";
}
if (!isset($SetupDir['convert'])) {
    $SetupDir['convert'] = "setup/convert/";
}
?>
<tr class="TableRow3">
<td class="TableColumn3">
<form style="display: inline;" method="post" id="install" action="<?php echo url_maker("install", ".php", "act=part2", "&", "=", null, null); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="LicenseBox">License - Please read fully and check 'I agree' box ONLY if you agree to license</label><br />
	<textarea rows="34" id="LicenseBox" name="LicenseBox" class="TextBox" cols="79" readonly="readonly" accesskey="L"><?php echo stripcslashes(htmlspecialchars(file_get_contents("LICENSE"), ENT_QUOTES, $Settings['charset'])); ?></textarea><br />
	<input type="checkbox" class="TextBox" name="License" value="Agree" id="License" /><label class="TextBoxLabel" for="License">I Agree</label><br/></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<?php if ($ConvertInfo['ConvertFile'] == null) { ?>
<input type="hidden" name="SetupType" value="install" style="display: none;" />
<?php } ?>
<input type="hidden" name="act" value="part2" style="display: none;" />
<input type="submit" class="Button" value="Next Page" name="Install_Board" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
