<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2011 iDB Support - http://idb.berlios.de/
    Copyright 2004-2011 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: iwrapper.php - Last Update: 07/30/2011 SVN 729 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="iwrapper.php"||$File3Name=="/iwrapper.php") {
	require('index.php');
	exit(); }

if(!isset($ThemeSet['WrapperString'])||$ThemeSet['WrapperString']===null) {
$ThemeSet['WrapperString'] = "<% HTMLSTART %>\n<% HTTPEQUIV %>\n<% METATAGS %>\n<% JAVASCRIPT %>\n<% LINKTAGS %>\n<% CSSTHEME %>\n<% FAVICON %>\n<% EXTRALINKS %>\n<% TITLETAG %>\n<% BODYTAG %>\n<% NAVBAR %>\n<% CONTENT %>\n<% COPYRIGHT %>\n<% HTMLEND %>"; }
if(!isset($iWrappers['HTMLSTART'])) { $iWrappers['HTMLSTART'] = null; }
$ThemeSet['WrapperString'] = preg_replace("/\<% HTMLSTART %\>/is", trim($iWrappers['HTMLSTART']), $ThemeSet['WrapperString']);
if(!isset($iWrappers['HTMLSTART'])) { $iWrappers['HTTPEQUIV'] = null; }
$ThemeSet['WrapperString'] = preg_replace("/\<% HTTPEQUIV %\>/is", trim($iWrappers['HTTPEQUIV']), $ThemeSet['WrapperString']);
if(!isset($iWrappers['HTMLSTART'])) { $iWrappers['METATAGS'] = null; }
$ThemeSet['WrapperString'] = preg_replace("/\<% METATAGS %\>/is", trim($iWrappers['METATAGS']), $ThemeSet['WrapperString']);
if(!isset($iWrappers['HTMLSTART'])) { $iWrappers['JAVASCRIPT'] = null; }
$ThemeSet['WrapperString'] = preg_replace("/\<% JAVASCRIPT %\>/is", trim($iWrappers['JAVASCRIPT']), $ThemeSet['WrapperString']);
if(!isset($iWrappers['HTMLSTART'])) { $iWrappers['LINKTAGS'] = null; }
$ThemeSet['WrapperString'] = preg_replace("/\<% LINKTAGS %\>/is", trim($iWrappers['LINKTAGS']), $ThemeSet['WrapperString']);
if(!isset($iWrappers['HTMLSTART'])) { $iWrappers['CSSTHEME'] = null; }
$ThemeSet['WrapperString'] = preg_replace("/\<% CSSTHEME %\>/is", trim($iWrappers['CSSTHEME']), $ThemeSet['WrapperString']);
if(!isset($iWrappers['HTMLSTART'])) { $iWrappers['FAVICON'] = null; }
$ThemeSet['WrapperString'] = preg_replace("/\<% FAVICON %\>/is", trim($iWrappers['FAVICON']), $ThemeSet['WrapperString']);
if(!isset($iWrappers['EXTRALINKS'])) { $iWrappers['EXTRALINKS'] = null; }
$ThemeSet['WrapperString'] = preg_replace("/\<% EXTRALINKS %\>/is", trim($iWrappers['EXTRALINKS']), $ThemeSet['WrapperString']);
if(!isset($iWrappers['HTMLSTART'])) { $iWrappers['TITLETAG'] = null; }
$ThemeSet['WrapperString'] = preg_replace("/\<% TITLETAG %\>/is", trim($iWrappers['TITLETAG']), $ThemeSet['WrapperString']);
if(!isset($iWrappers['HTMLSTART'])) { $iWrappers['BODYTAG'] = null; }
$ThemeSet['WrapperString'] = preg_replace("/\<% BODYTAG %\>/is", trim($iWrappers['BODYTAG']), $ThemeSet['WrapperString']);
if(!isset($iWrappers['HTMLSTART'])) { $iWrappers['NAVBAR'] = null; }
$ThemeSet['WrapperString'] = preg_replace("/\<% NAVBAR %\>/is", trim($iWrappers['NAVBAR']), $ThemeSet['WrapperString']);
if(!isset($iWrappers['HTMLSTART'])) { $iWrappers['CONTENT'] = null; }
$ThemeSet['WrapperString'] = preg_replace("/\<% CONTENT %\>/is", trim($iWrappers['CONTENT']), $ThemeSet['WrapperString']);
if(!isset($iWrappers['HTMLSTART'])) { $iWrappers['COPYRIGHT'] = null; }
$ThemeSet['WrapperString'] = preg_replace("/\<% COPYRIGHT %\>/is", trim($iWrappers['COPYRIGHT']), $ThemeSet['WrapperString']);
if(!isset($iWrappers['HTMLSTART'])) { $iWrappers['HTMLEND'] = null; }
$ThemeSet['WrapperString'] = preg_replace("/\<% HTMLEND %\>/is", trim($iWrappers['HTMLEND']), $ThemeSet['WrapperString']);
echo $ThemeSet['WrapperString'];
?>
