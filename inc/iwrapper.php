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

    $FileInfo: iwrapper.php - Last Update: 8/23/2024 SVN 1023 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "iwrapper.php" || $File3Name == "/iwrapper.php") {
    require('index.php');
    exit();
}

if (!isset($ThemeSet['WrapperString']) || $ThemeSet['WrapperString'] === null) {
    $ThemeSet['WrapperString'] = "<% HTMLSTART %>\n<% HTTPEQUIV %>\n<% METATAGS %>\n<% JAVASCRIPT %>\n<% LINKTAGS %>\n<% CSSTHEME %>\n<% FAVICON %>\n<% EXTRALINKS %>\n<% TITLETAG %>\n<% BODYTAG %>\n<% NAVBAR %>\n<% CONTENT %>\n<% COPYRIGHT %>\n<% HTMLEND %>";
}
$iWrappers['WrapperString'] = $ThemeSet['WrapperString'];
if (!isset($iWrappers['HTMLSTART'])) {
    $iWrappers['HTMLSTART'] = "";
}
$iWrappers['WrapperString'] = str_ireplace("<% HTMLSTART %>", trim($iWrappers['HTMLSTART']), $iWrappers['WrapperString']);
if (!isset($iWrappers['HTTPEQUIV'])) {
    $iWrappers['HTTPEQUIV'] = "";
}
$iWrappers['WrapperString'] = str_ireplace("<% HTTPEQUIV %>", trim($iWrappers['HTTPEQUIV']), $iWrappers['WrapperString']);
if (!isset($iWrappers['METATAGS'])) {
    $iWrappers['METATAGS'] = "";
}
$iWrappers['WrapperString'] = str_ireplace("<% METATAGS %>", trim($iWrappers['METATAGS']), $iWrappers['WrapperString']);
if (!isset($iWrappers['JAVASCRIPT'])) {
    $iWrappers['JAVASCRIPT'] = "";
}
$iWrappers['WrapperString'] = str_ireplace("<% JAVASCRIPT %>", trim($iWrappers['JAVASCRIPT']), $iWrappers['WrapperString']);
if (!isset($iWrappers['LINKTAGS'])) {
    $iWrappers['LINKTAGS'] = "";
}
$iWrappers['WrapperString'] = str_ireplace("<% LINKTAGS %>", trim($iWrappers['LINKTAGS']), $iWrappers['WrapperString']);
if (!isset($iWrappers['CSSTHEME'])) {
    $iWrappers['CSSTHEME'] = "";
}
$iWrappers['WrapperString'] = str_ireplace("<% CSSTHEME %>", trim($iWrappers['CSSTHEME']), $iWrappers['WrapperString']);
if (!isset($iWrappers['FAVICON'])) {
    $iWrappers['FAVICON'] = "";
}
$iWrappers['WrapperString'] = str_ireplace("<% FAVICON %>", trim($iWrappers['FAVICON']), $iWrappers['WrapperString']);
if (!isset($iWrappers['EXTRALINKS'])) {
    $iWrappers['EXTRALINKS'] = "";
}
$iWrappers['WrapperString'] = str_ireplace("<% EXTRALINKS %>", trim($iWrappers['EXTRALINKS']), $iWrappers['WrapperString']);
if (!isset($iWrappers['TITLETAG'])) {
    $iWrappers['TITLETAG'] = "";
}
$iWrappers['WrapperString'] = str_ireplace("<% TITLETAG %>", trim($iWrappers['TITLETAG']), $iWrappers['WrapperString']);
if (!isset($iWrappers['BODYTAG'])) {
    $iWrappers['BODYTAG'] = "";
}
$iWrappers['WrapperString'] = str_ireplace("<% BODYTAG %>", trim($iWrappers['BODYTAG']), $iWrappers['WrapperString']);
if (!isset($iWrappers['NAVBAR'])) {
    $iWrappers['NAVBAR'] = "";
}
$iWrappers['WrapperString'] = str_ireplace("<% NAVBAR %>", trim($iWrappers['NAVBAR']), $iWrappers['WrapperString']);
if (!isset($iWrappers['CONTENT'])) {
    $iWrappers['CONTENT'] = "";
}
$iWrappers['WrapperString'] = str_ireplace("<% CONTENT %>", trim($iWrappers['CONTENT']), $iWrappers['WrapperString']);
if (!isset($iWrappers['COPYRIGHT'])) {
    $iWrappers['COPYRIGHT'] = "";
}
$iWrappers['WrapperString'] = str_ireplace("<% COPYRIGHT %>", trim($iWrappers['COPYRIGHT']), $iWrappers['WrapperString']);
if (!isset($iWrappers['HTMLEND'])) {
    $iWrappers['HTMLEND'] = "";
}
$iWrappers['WrapperString'] = str_ireplace("<% HTMLEND %>", trim($iWrappers['HTMLEND']), $iWrappers['WrapperString']);
echo $iWrappers['WrapperString'];
// BUGFIX: array(null) leaves an element behind; array() actually clears it.
$iWrappers = array();
