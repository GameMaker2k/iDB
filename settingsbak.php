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

    $FileInfo: settings.php & settingsbak.php - Last Update: 05/26/2007 SVN 15 - Author: cooldude2k $
*/
/*	You Need to install iDB. Run install.php	*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="settings.php"||$File3Name=="/settings.php"||
    $File3Name=="settingsbak.php"||$File3Name=="/settingsbak.php") {
    @header('Location: index.php');
    exit(); }
?>