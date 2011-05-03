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
	iBBCode / iBBTags by Kazuki Przyborowski - http://idb.berlios.net/

    $FileInfo: ibbcode.php - Last Update: 05/02/2011 SVN 644 - Author: cooldude2k $
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
$BoardCharSet = $Settings['charset'];
function do_html_bbcode($text)
{
$text = preg_replace_callback("/\[DoHTML\](.*?)\[\/DoHTML\]/is","html_decode",$text);
return $text;
}
function html_decode($matches)
{
global $BoardCharSet;
$matches[1] = str_replace(array("\r", "\r\n", "\n"), " ", $matches[1]);
return html_entity_decode($matches[1], ENT_QUOTES, $BoardCharSet);
}
function bbcode_rot13($matches)
{ return str_rot13($matches[1]); }
function bbcode_base64encode($matches)
{ return base64_encode($matches[1]); }
function bbcode_base64decode($matches)
{ return base64_decode($matches[1]); }
function bbcode_urlencode($matches)
{ return urlencode($matches[1]); }
function bbcode_urldecode($matches)
{ return urldecode($matches[1]); }
// Pre URL and IMG tags
if(!function_exists("urlcheck2")) {
function urlcheck2($matches) {
global $BoardURL;
$retnum = preg_match_all("/([a-zA-Z]+)\:\/\/([a-z0-9\-\.]+)(\:[0-9]+)?\/([A-Za-z0-9\.\/%\?\-_\:;\~]+)?(\?)?([A-Za-z0-9\.\/%&=\?\-_\:;]+)?(\#)?([A-Za-z0-9\.\/%&=\?\-_\:;]+)?/is", $matches[1], $urlcheck); 
if(isset($urlcheck[0][0])) { 
$matches[0] = preg_replace("/\[URL\](.*?)\[\/URL\]/is", " \\1", $matches[0]);
$matches[0] = preg_replace("/\[URL\=(.*?)\](.*?)\[\/URL\]/is", "<a href=\"\\1\">\\2</a>", $matches[0]);
$matches[0] = preg_replace("/\[IMG](.*?)\[\/IMG\]/is", "<img src=\"\\1\" alt=\"user posted image\" title=\"user posted image\" />", $matches[0]); 
$matches[0] = preg_replace("/\[IMG=(.*?)]([A-Za-z0-9\.\/%\?\-_\:;\~\s]+)\[\/IMG\]/is", "<img src=\"\\1\" alt=\"\\2\" title=\"\\2\" />", $matches[0]); }
return $matches[0]; } }
$UsersSettings['UserTimeZone'] = $_SESSION['UserTimeZone'];
$UsersSettings['UserDST'] = $_SESSION['UserDST'];
function bbcode_parser($text)
{
global $Settings;
$text = preg_replace("/\[EmbedVideo\=([A-Za-z0-9\.\-_]+)\]([A-Za-z0-9\.\-_]+)\[\/EmbedVideo\]/is", "[\\1]\\2[/\\1]", $text);
$text = preg_replace("/\[YouTube\]([A-Za-z0-9\.\-_]+)\[\/YouTube\]/is", "\n<object type=\"application/x-shockwave-flash\" width=\"480\" height=\"385\" data=\"http://www.youtube.com/v/\\1?fs=1&amp;hl=en_US\">\n<param name=\"\\1\" value=\"http://www.youtube.com/v/\\1?fs=1&amp;hl=en_US\" />\n</object>\n", $text);
$text = preg_replace("/\[DailyMotion\]([A-Za-z0-9\.\-_]+)\[\/DailyMotion\]/is", "\n<object type=\"application/x-shockwave-flash\" width=\"480\" height=\"385\" data=\"http://www.dailymotion.com/swf/video/\\1\">\n<param name=\"\\1\" value=\"http://www.dailymotion.com/swf/video/\\1\" />\n<param name=\"allowFullScreen\" value=\"true\" />\n<param name=\"allowScriptAccess\" value=\"always\" />\n<param name=\"wmode\" value=\"transparent\" />\n</object>\n", $text);
$text = preg_replace("/\[Vimeo\]([A-Za-z0-9\.\-_]+)\[\/Vimeo\]/is", "\n<object type=\"application/x-shockwave-flash\" width=\"400\" height=\"225\" data=\"http://vimeo.com/moogaloop.swf?clip_id=\\1&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=1&amp;color=00adef&amp;fullscreen=1&amp;autoplay=0&amp;loop=0\">\n<param name=\"\\1\" value=\"http://vimeo.com/moogaloop.swf?clip_id=\\1&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=1&amp;color=00adef&amp;fullscreen=1&amp;autoplay=0&amp;loop=0\" />\n<param name=\"allowfullscreen\" value=\"true\" />\n<param name=\"allowscriptaccess\" value=\"always\" />\n</object>\n", $text);
$text = preg_replace("/\[TinyPic\]([A-Za-z0-9\.\-_]+)\,([A-Za-z0-9\.\-_]+)\[\/TinyPic\]/is", "<img src=\"http://\\1.tinypic.com/\\2\" alt=\"\\2\" title=\"\\2\" />", $text);
$text = preg_replace("/\[BR\]/is", "<br />", $text);
$text = preg_replace("/\[HR\]/is", "<hr />", $text);
$text = preg_replace("/\[SUP\](.*?)\[\/SUP\]/is", "<sup>\\1</sup>", $text);
$text = preg_replace("/\[SUB\](.*?)\[\/SUB\]/is", "<sub>\\1</sub>", $text);
$text = preg_replace("/\[BoardName\]/is", $Settings['board_name'], $text);
$text = preg_replace("/\[BoardURL\]/is", $Settings['idburl'], $text);
$text = preg_replace("/\[WebSiteURL\]/is", $Settings['weburl'], $text);
$text = preg_replace("/\[DATE\]/is", GMTimeGet('M j Y',$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']), $text);
$text = preg_replace("/\[DATE\=(.*?)\]/is", GMTimeGet('\\1',$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']), $text);
$text = preg_replace("/\[TIME\]/is", GMTimeGet('g:i a',$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']), $text);
$text = preg_replace("/\[TIME\=(.*?)\]/is", GMTimeGet('\\1',$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']), $text);
$text = preg_replace("/\{BoardName\}/is", $Settings['board_name'], $text);
$text = preg_replace("/\{BoardURL\}/is", $Settings['idburl'], $text);
$text = preg_replace("/\{WebSiteURL\}/is", $Settings['weburl'], $text);
$text = preg_replace("/\{DATE\}/is", GMTimeGet('g:i a',$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']), $text);
$text = preg_replace("/\{DATE\=(.*?)\}/is", GMTimeGet('\\1',$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']), $text);
$text = preg_replace("/\{TIME\}/is", GMTimeGet('M j Y',$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']), $text);
$text = preg_replace("/\{TIME\=(.*?)\}/is", GMTimeGet('\\1',$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']), $text);
$text = preg_replace("/\[B\](.*?)\[\/B\]/is", "<span style=\"font-weight: bold;\">\\1</span>", $text);
$text = preg_replace("/\[I\](.*?)\[\/I\]/is", "<span style=\"font-style: italic;\">\\1</span>", $text);
$text = preg_replace("/\[S\](.*?)\[\/S\]/is", "<span style=\"font-style: strike;\">\\1</span>", $text);
$text = preg_replace("/\[U\](.*?)\[\/U\]/is", "<span style=\"text-decoration: underline;\">\\1</span>", $text);
$text = preg_replace("/\[O\](.*?)\[\/O\]/is", "<span style=\"text-decoration: overline;\">\\1</span>", $text);
$text = preg_replace("/\[CENTER\](.*?)\[\/CENTER\]/is", "<span style=\"text-align: center;\">\\1</span>", $text);
$text = preg_replace("/\[SIZE\=([0-9]+)\](.*?)\[\/SIZE\]/is", "<span style=\"font-size: \\1px;\">\\2</span>", $text);
$text = preg_replace("/\[SIZE\=([0-9]+)\%\](.*?)\[\/SIZE\]/is", "<span style=\"font-size: \\1%;\">\\2</span>", $text);
$text = preg_replace("/\[SIZE\=([0-9]+)(em|pt|px)\](.*?)\[\/SIZE\]/is", "<span style=\"font-size: \\1\\2;\">\\3</span>", $text);
$text = preg_replace("/\[COLOR\=([A-Za-z0-9]+)\](.*?)\[\/COLOR\]/is", "<span style=\"color: \\1;\">\\2</span>", $text);
$text = preg_replace("/\[COLOR\=\#([A-Za-z0-9]+)\](.*?)\[\/COLOR\]/is", "<span style=\"color: #\\1;\">\\2</span>", $text);
$text = preg_replace("/\[ALIGN=(.*?)\](.*?)\[\/ALIGN\]/is", "<span style=\"text-align: \\1;\">\\2</span>", $text);
// Sub URL and IMG tags
$text = preg_replace_callback("/\[URL](.*?)\[\/URL\]/is", "urlcheck2", $text);
$text = preg_replace_callback("/\[URL\=(.*?)\](.*?)\[\/URL\]/is", "urlcheck2", $text);
$text = preg_replace_callback("/\[IMG](.*?)\[\/IMG\]/is", "urlcheck2", $text);
$text = preg_replace_callback("/\[IMG=(.*?)]([A-Za-z0-9\.\/%\?\-_\:;\~\s]+)\[\/IMG\]/is", "urlcheck2", $text);
$text = preg_replace_callback("/\[URLENCODE\](.*?)\[\/URLENCODE\]/is","bbcode_urlencode",$text);
$text = preg_replace_callback("/\[URLDECODE\](.*?)\[\/URLDECODE\]/is","bbcode_urldecode",$text);
$text = preg_replace_callback("/\[BASE64\](.*?)\[\/BASE64\]/is","bbcode_base64encode",$text);
$text = preg_replace_callback("/\[BASE64=ENCODE\](.*?)\[\/BASE64\]/is","bbcode_base64encode",$text);
$text = preg_replace_callback("/\[BASE64=DECODE\](.*?)\[\/BASE64\]/is","bbcode_base64decode",$text);
$text = preg_replace_callback("/\[ROT13\](.*?)\[\/ROT13\]/is","bbcode_rot13",$text);
return $text;
}
?>