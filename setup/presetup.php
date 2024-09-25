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

    $FileInfo: presetup.php - Last Update: 8/23/2024 SVN 1023 - Author: cooldude2k $
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
<form style="display: inline;" method="post" id="install" action="<?php echo url_maker("install",".php","act=Part3","&","=",null,null); ?>">
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
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="DatabaseType">Select Database Type:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DatabaseType" id="DatabaseType">
	<?php if(function_exists("mysql_func_connect_db")) { ?>
	<option value="mysql">MySQL Database</option>
	<?php } if(function_exists("mysqli_func_connect_db")) { ?>
	<option value="mysqli">MySQLi Database</option>
	<?php } if(function_exists("mysqli_prepare_func_connect_db")) { ?>
	<option value="mysqli_prepare">MySQLi Alt Database</option>
	<?php } if(function_exists("pdo_mysql_func_connect_db")) { ?>
	<option value="pdo_mysql">PDO MySQL Database</option>
	<?php } if(function_exists("pgsql_func_connect_db")) { ?>
	<option value="pgsql">PostgreSQL Database</option>
	<?php } if(function_exists("pgsql_prepare_func_connect_db")) { ?>
	<option value="pgsql_prepare">PostgreSQL Alt Database</option>
	<?php } if(function_exists("pdo_pgsql_func_connect_db")) { ?>
	<option value="pdo_pgsql">PDO PostgreSQL Database</option>
	<?php } if(function_exists("sqlite_func_connect_db")) { ?>
	<option value="sqlite">SQLite Database</option>
	<?php } if(function_exists("sqlite3_func_connect_db")) { ?>
	<option value="sqlite3">SQLite 3 Database</option>
	<?php } if(function_exists("sqlite3_prepare_func_connect_db")) { ?>
	<option value="sqlite3_prepare">SQLite 3 Alt Database</option>
	<?php } if(function_exists("pdo_sqlite3_func_connect_db")) { ?>
	<option value="pdo_sqlite3">PDO SQLite 3 Database</option>
	<?php } if(function_exists("cubrid_func_connect_db")) { ?>
	<option value="cubrid">CUBRID Database</option>
	<?php } if(function_exists("cubrid_prepare_func_connect_db")) { ?>
	<option value="cubrid_prepare">CUBRID Alt Database</option>
	<?php } if(function_exists("pdo_cubrid_func_connect_db")) { ?>
	<option value="pdo_cubrid">PDO CUBRID Database</option>
	<?php } if(!function_exists("mysql_func_connect_db")&&
		   !function_exists("mysqli_func_connect_db")&&
		   !function_exists("pgsql_func_connect_db")&&
		   !function_exists("sqlite_func_connect_db")&&
		   !function_exists("sqlite3_func_connect_db")&&
		   !function_exists("cubrid_prepare_func_connect_db")&&
		   !function_exists("mysqli_prepare_func_connect_db")&&
		   !function_exists("pgsql_prepare_func_connect_db")&&
		   !function_exists("sqlite3_prepare_func_connect_db")&&
		   !function_exists("pdo_cubrid_func_connect_db")&&
		   !function_exists("pdo_mysql_func_connect_db")&&
		   !function_exists("pdo_pgsql_func_connect_db")&&
		   !function_exists("pdo_sqlite3_func_connect_db")) { ?>
	<option value="none">No Database Available</option>
	<?php } ?>
	</select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="charset">Select html charset:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="charset" id="charset">
	<option value="UTF-8">Unicode (UTF-8)</option>
	<option value="ISO-8859-15">Latin-9 (ISO-8859-15)</option>
	<option value="ISO-8859-1">Latin-1 (ISO-8859-1)</option>
	</select></td>
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
