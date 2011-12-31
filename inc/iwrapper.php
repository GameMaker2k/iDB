<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2012 iDB Support - http://idb.berlios.de/
    Copyright 2004-2012 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: iwrapper.php - Last Update: 08/01/2011 SVN 736 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="iwrapper.php"||$File3Name=="/iwrapper.php") {
	require('index.php');
	exit(); }

if(!isset($ThemeSet['WrapperString'])||$ThemeSet['WrapperString']===null) {
$ThemeSet['WrapperString'] = "<% HTMLSTART %>\n<% HTTPEQUIV %>\n<% METATAGS %>\n<% JAVASCRIPT %>\n<% LINKTAGS %>\n<% CSSTHEME %>\n<% FAVICON %>\n<% EXTRALINKS %>\n<% TITLETAG %>\n<% BODYTAG %>\n<% NAVBAR %>\n<% CONTENT %>\n<% COPYRIGHT %>\n<% HTMLEND %>"; }
$iWrappers['WrapperString'] = $ThemeSet['WrapperString'];
if(!isset($iWrappers['HTMLSTART'])) { $iWrappers['HTMLSTART'] = null; }
$iWrappers['WrapperString'] = preg_replace("/\<% HTMLSTART %\>/is", trim($iWrappers['HTMLSTART']), $iWrappers['WrapperString']);
if(!isset($iWrappers['HTTPEQUIV'])) { $iWrappers['HTTPEQUIV'] = null; }
$iWrappers['WrapperString'] = preg_replace("/\<% HTTPEQUIV %\>/is", trim($iWrappers['HTTPEQUIV']), $iWrappers['WrapperString']);
if(!isset($iWrappers['METATAGS'])) { $iWrappers['METATAGS'] = null; }
$iWrappers['WrapperString'] = preg_replace("/\<% METATAGS %\>/is", trim($iWrappers['METATAGS']), $iWrappers['WrapperString']);
if(!isset($iWrappers['JAVASCRIPT'])) { $iWrappers['JAVASCRIPT'] = null; }
$iWrappers['WrapperString'] = preg_replace("/\<% JAVASCRIPT %\>/is", trim($iWrappers['JAVASCRIPT']), $iWrappers['WrapperString']);
if(!isset($iWrappers['LINKTAGS'])) { $iWrappers['LINKTAGS'] = null; }
$iWrappers['WrapperString'] = preg_replace("/\<% LINKTAGS %\>/is", trim($iWrappers['LINKTAGS']), $iWrappers['WrapperString']);
if(!isset($iWrappers['CSSTHEME'])) { $iWrappers['CSSTHEME'] = null; }
$iWrappers['WrapperString'] = preg_replace("/\<% CSSTHEME %\>/is", trim($iWrappers['CSSTHEME']), $iWrappers['WrapperString']);
if(!isset($iWrappers['FAVICON'])) { $iWrappers['FAVICON'] = null; }
$iWrappers['WrapperString'] = preg_replace("/\<% FAVICON %\>/is", trim($iWrappers['FAVICON']), $iWrappers['WrapperString']);
if(!isset($iWrappers['EXTRALINKS'])) { $iWrappers['EXTRALINKS'] = null; }
$iWrappers['WrapperString'] = preg_replace("/\<% EXTRALINKS %\>/is", trim($iWrappers['EXTRALINKS']), $iWrappers['WrapperString']);
if(!isset($iWrappers['TITLETAG'])) { $iWrappers['TITLETAG'] = null; }
$iWrappers['WrapperString'] = preg_replace("/\<% TITLETAG %\>/is", trim($iWrappers['TITLETAG']), $iWrappers['WrapperString']);
if(!isset($iWrappers['BODYTAG'])) { $iWrappers['BODYTAG'] = null; }
$iWrappers['WrapperString'] = preg_replace("/\<% BODYTAG %\>/is", trim($iWrappers['BODYTAG']), $iWrappers['WrapperString']);
if(!isset($iWrappers['NAVBAR'])) { $iWrappers['NAVBAR'] = null; }
$iWrappers['WrapperString'] = preg_replace("/\<% NAVBAR %\>/is", trim($iWrappers['NAVBAR']), $iWrappers['WrapperString']);
if(!isset($iWrappers['CONTENT'])) { $iWrappers['CONTENT'] = null; }
$iWrappers['WrapperString'] = preg_replace("/\<% CONTENT %\>/is", trim($iWrappers['CONTENT']), $iWrappers['WrapperString']);
if(!isset($iWrappers['COPYRIGHT'])) { $iWrappers['COPYRIGHT'] = null; }
$iWrappers['WrapperString'] = preg_replace("/\<% COPYRIGHT %\>/is", trim($iWrappers['COPYRIGHT']), $iWrappers['WrapperString']);
if(!isset($iWrappers['HTMLEND'])) { $iWrappers['HTMLEND'] = null; }
$iWrappers['WrapperString'] = preg_replace("/\<% HTMLEND %\>/is", trim($iWrappers['HTMLEND']), $iWrappers['WrapperString']);
echo $iWrappers['WrapperString'];
$iWrappers = array(null);
?>
