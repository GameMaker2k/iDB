<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2023 iDB Support - https://idb.osdn.jp/support/category.php?act=view&id=1
    Copyright 2004-2023 Game Maker 2k - https://idb.osdn.jp/support/category.php?act=view&id=2

    $FileInfo: iwrapper.php - Last Update: 6/22/2023 SVN 984 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="iwrapper.php"||$File3Name=="/iwrapper.php") {
	require('index.php');
	exit(); }

if(!isset($ThemeSet['WrapperString'])||$ThemeSet['WrapperString']===null) {
$ThemeSet['WrapperString'] = "<% HTMLSTART %>\n<% HTTPEQUIV %>\n<% METATAGS %>\n<% JAVASCRIPT %>\n<% LINKTAGS %>\n<% CSSTHEME %>\n<% FAVICON %>\n<% EXTRALINKS %>\n<% TITLETAG %>\n<% BODYTAG %>\n<% NAVBAR %>\n<% CONTENT %>\n<% COPYRIGHT %>\n<% HTMLEND %>"; }
$iWrappers['WrapperString'] = $ThemeSet['WrapperString'];
if(!isset($iWrappers['HTMLSTART'])) { $iWrappers['HTMLSTART'] = ""; }
$iWrappers['WrapperString'] = preg_replace("/\<% HTMLSTART %\>/is", trim($iWrappers['HTMLSTART']), $iWrappers['WrapperString']);
if(!isset($iWrappers['HTTPEQUIV'])) { $iWrappers['HTTPEQUIV'] = ""; }
$iWrappers['WrapperString'] = preg_replace("/\<% HTTPEQUIV %\>/is", trim($iWrappers['HTTPEQUIV']), $iWrappers['WrapperString']);
if(!isset($iWrappers['METATAGS'])) { $iWrappers['METATAGS'] = ""; }
$iWrappers['WrapperString'] = preg_replace("/\<% METATAGS %\>/is", trim($iWrappers['METATAGS']), $iWrappers['WrapperString']);
if(!isset($iWrappers['JAVASCRIPT'])) { $iWrappers['JAVASCRIPT'] = ""; }
$iWrappers['WrapperString'] = preg_replace("/\<% JAVASCRIPT %\>/is", trim($iWrappers['JAVASCRIPT']), $iWrappers['WrapperString']);
if(!isset($iWrappers['LINKTAGS'])) { $iWrappers['LINKTAGS'] = ""; }
$iWrappers['WrapperString'] = preg_replace("/\<% LINKTAGS %\>/is", trim($iWrappers['LINKTAGS']), $iWrappers['WrapperString']);
if(!isset($iWrappers['CSSTHEME'])) { $iWrappers['CSSTHEME'] = ""; }
$iWrappers['WrapperString'] = preg_replace("/\<% CSSTHEME %\>/is", trim($iWrappers['CSSTHEME']), $iWrappers['WrapperString']);
if(!isset($iWrappers['FAVICON'])) { $iWrappers['FAVICON'] = ""; }
$iWrappers['WrapperString'] = preg_replace("/\<% FAVICON %\>/is", trim($iWrappers['FAVICON']), $iWrappers['WrapperString']);
if(!isset($iWrappers['EXTRALINKS'])) { $iWrappers['EXTRALINKS'] = ""; }
$iWrappers['WrapperString'] = preg_replace("/\<% EXTRALINKS %\>/is", trim($iWrappers['EXTRALINKS']), $iWrappers['WrapperString']);
if(!isset($iWrappers['TITLETAG'])) { $iWrappers['TITLETAG'] = ""; }
$iWrappers['WrapperString'] = preg_replace("/\<% TITLETAG %\>/is", trim($iWrappers['TITLETAG']), $iWrappers['WrapperString']);
if(!isset($iWrappers['BODYTAG'])) { $iWrappers['BODYTAG'] = ""; }
$iWrappers['WrapperString'] = preg_replace("/\<% BODYTAG %\>/is", trim($iWrappers['BODYTAG']), $iWrappers['WrapperString']);
if(!isset($iWrappers['NAVBAR'])) { $iWrappers['NAVBAR'] = ""; }
$iWrappers['WrapperString'] = preg_replace("/\<% NAVBAR %\>/is", trim($iWrappers['NAVBAR']), $iWrappers['WrapperString']);
if(!isset($iWrappers['CONTENT'])) { $iWrappers['CONTENT'] = ""; }
$iWrappers['WrapperString'] = preg_replace("/\<% CONTENT %\>/is", trim($iWrappers['CONTENT']), $iWrappers['WrapperString']);
if(!isset($iWrappers['COPYRIGHT'])) { $iWrappers['COPYRIGHT'] = ""; }
$iWrappers['WrapperString'] = preg_replace("/\<% COPYRIGHT %\>/is", trim($iWrappers['COPYRIGHT']), $iWrappers['WrapperString']);
if(!isset($iWrappers['HTMLEND'])) { $iWrappers['HTMLEND'] = ""; }
$iWrappers['WrapperString'] = preg_replace("/\<% HTMLEND %\>/is", trim($iWrappers['HTMLEND']), $iWrappers['WrapperString']);
echo $iWrappers['WrapperString'];
$iWrappers = array(null);
?>
