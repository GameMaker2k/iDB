<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2009 iDB Support - http://idb.berlios.de/
    Copyright 2004-2009 Game Maker 2k - http://gamemaker2k.org/
    iDB Installer made by Game Maker 2k - http://idb.berlios.net/

    $FileInfo: settings.php & settingsbak.php - Last Update: 11/23/2009 SVN 357 - Author: cooldude2k $
*/
/*	You Need to install iDB. Run install.php	*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="settings.php"||$File3Name=="/settings.php"||
    $File3Name=="settingsbak.php"||$File3Name=="/settingsbak.php") {
    header('Location: index.php');
    exit(); }
?>