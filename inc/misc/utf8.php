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

    $FileInfo: utf8.php - Last Update: 11/20/2007 SVN 129 - Author: cooldude2k $
	$Function Name: strlen_utf8 - Author: anpaza at mail dot ru $
	$Function Name: utf8_substr - Author: felipe at spdata dot com dot br $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="utf8.php"||$File3Name=="/utf8.php") {
	require('index.php');
	exit(); }
/*   strlen for UTF-8 - by: anpaza @ http://us2.php.net/manual/en/function.strlen.php#59258   */
if($Settings['charset']=="UTF-8") {
function strlen_utf8($str)
{
    $i = 0;
    $count = 0;
    $len = strlen ($str);
    while ($i < $len)
    {
    $chr = ord ($str[$i]);
    $count++;
    $i++;
    if ($i >= $len)
        break;

    if ($chr & 0x80)
    {
        $chr <<= 1;
        while ($chr & 0x80)
        {
        $i++;
        $chr <<= 1;
        }
    }
    }
    return $count;
} }

/*   substr for UTF-8 - by: felipe @ http://us2.php.net/manual/en/function.substr.php#57899   */
if($Settings['charset']=="UTF-8") {
function utf8_substr($str,$from,$len){
# utf8 substr
# www.yeap.lv
  return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
                       '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
                       '$1',$str);
} }

function pre_substr($str,$from,$len) {
global $chkcharset;
if($chkcharset=="UTF-8") {
return utf8_substr($str,$from,$len); }
if($chkcharset!="UTF-8") {
return substr($str,$from,$len); } }

function pre_strlen($str) {
global $chkcharset;
if($chkcharset=="UTF-8") {
return strlen_utf8($str); }
if($chkcharset!="UTF-8") {
return strlen($str); } }

?>