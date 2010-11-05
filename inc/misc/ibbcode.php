<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2010 iDB Support - http://idb.berlios.de/
    Copyright 2004-2010 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: ibbcode.php - Last Update: 11/05/2010 SVN 596 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="ibbcode.php"||$File3Name=="/ibbcode.php") {
	require('index.php');
	exit(); }

/*
This File has all the Functions for BBTags
Thanks for the Help of czambran at: 
http://www.phpfreaks.com/forums/index.php?action=profile;u=15535
*/ 
function html_decode($matches)
{
return html_entity_decode($matches[1]);
}
function do_html_bbcode($text)
{
$text = preg_replace_callback("/\[DoHTML\](.*?)\[\/DoHTML\]/is","html_decode",$text);
return $text;
}
function bbcode_parser($text)
{
$text = preg_replace("/\[YouTube\]([A-Za-z0-9\.\-_]+)\[\/YouTube\]/is", "\n<object type=\"application/x-shockwave-flash\" width=\"480\" height=\"385\" data=\"http://www.youtube.com/v/\\1?fs=1&amp;hl=en_US\">\n<param name=\"\\1\" value=\"http://www.youtube.com/v/\\1?fs=1&amp;hl=en_US\" />\n</object>\n", $text);
return $text;
}
?>