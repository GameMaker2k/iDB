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

    $FileInfo: ibbcode.php - Last Update: 09/16/2011 SVN 758 - Author: cooldude2k $
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
function html_decode($matches) {
	global $BoardCharSet;
	$matches[1] = str_replace(array("\r", "\r\n", "\n"), " ", $matches[1]);
	return html_entity_decode($matches[1], ENT_QUOTES, $BoardCharSet); }
function do_html_bbcode($text) {
	return preg_replace_callback("/\[DoHTML\](.*?)\[\/DoHTML\]/is","html_decode",$text); }
function idb_exec_php_handler($buffer) { return $buffer; }
function exec_php($matches) {
	global $BoardCharSet;
	ob_start("idb_exec_php_handler");
	$matches[1] = html_entity_decode($matches[1], ENT_QUOTES, $BoardCharSet);
	@eval("?> ".$matches[1]);
	return ob_get_clean(); }
function php_execute($text) {
	return preg_replace_callback("/\[ExecPHP\](.*?)\[\/ExecPHP\]/is","exec_php",$text); }
function bbcode_rot13($matches) { 
	return str_rot13($matches[1]); }
function bbcode_base64encode($matches) { 
	return base64_encode($matches[1]); }
function bbcode_base64decode($matches) { 
	return base64_decode($matches[1]); }
function bbcode_urlencode($matches) { 
	return urlencode($matches[1]); }
function bbcode_urldecode($matches) { 
	return urldecode($matches[1]); }
function bbcode_date_time($matches) { 
	return GMTimeGet($matches[1],$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']); }
function bbcode_random($matches) { 
	if(!isset($matches[2])) {
	return rand(0,$matches[1]); }
	if(isset($matches[2])) {
	return rand($matches[1],$matches[2]); } }
// Pre URL and IMG tags
if(!function_exists("urlcheck2")) {
function urlcheck2($matches) {
global $BoardURL;
$retnum = preg_match_all("/([a-zA-Z]+)\:\/\/([a-z0-9\-\.@\:]+)(\:[0-9]+)?\/([A-Za-z0-9\.\/%\?\!\$\(\)\*\-_\:;,\+\@~]+)?(\?)?([A-Za-z0-9\.\/%&\=\?\!\$\(\)\*\-_\:;,\+\@~]+)?(\#)?([A-Za-z0-9\.\/%&\=\?\!\$\(\)\*\-_\:;,\+\@~]+)?/is", $matches[1], $urlcheck); 
if(isset($urlcheck[0][0])) { 
$matches[0] = preg_replace("/\[URL\](.*?)\[\/URL\]/is", " \\1", $matches[0]);
$matches[0] = preg_replace("/\[URL\=(.*?)\](.*?)\[\/URL\]/is", "<a href=\"\\1\">\\2</a>", $matches[0]);
$matches[0] = preg_replace("/\[IMG](.*?)\[\/IMG\]/is", "<img src=\"\\1\" alt=\"user posted image\" title=\"user posted image\" />", $matches[0]); 
$matches[0] = preg_replace("/\[IMG\=(.*?)]([A-Za-z0-9\.\/%\?\!\$\(\)\*\-_\:;,\+\@~\s]+)\[\/IMG\]/is", "<img src=\"\\1\" alt=\"\\2\" title=\"\\2\" />", $matches[0]); }
return $matches[0]; } }
function bbcode_parser($text) {
global $Settings;
$text = preg_replace("/\[EmbedVideo\=&quot;([A-Za-z0-9\.\-_]+)&quot;\]([A-Za-z0-9\.\-_]+)\[\/EmbedVideo\]/is", "[\\1]\\2[/\\1]", $text);
$text = preg_replace("/\[EmbedVideo\=([A-Za-z0-9\.\-_]+)\]([A-Za-z0-9\.\-_]+)\[\/EmbedVideo\]/is", "[\\1]\\2[/\\1]", $text);
$text = preg_replace("/\[EmbedMusic\=&quot;([A-Za-z0-9\.\-_]+)&quot;\]([A-Za-z0-9\.\-_]+)\[\/EmbedMusic\]/is", "[\\1]\\2[/\\1]", $text);
$text = preg_replace("/\[EmbedMusic\=([A-Za-z0-9\.\-_]+)\]([A-Za-z0-9\.\-_]+)\[\/EmbedMusic\]/is", "[\\1]\\2[/\\1]", $text);
$text = preg_replace("/\[Kiwi6\]([A-Za-z0-9\.\-_]+)\[\/Kiwi6\]/is", "\n<object type=\"application/x-shockwave-flash\" height=\"24\" width=\"290\" data=\"http://kiwi6.com/swf/player.swf\">\n<param name=\"movie\" value=\"http://kiwi6.com/swf/player.swf\" />\n<param name=\"FlashVars\" value=\"playerID=audioplayer&amp;soundFile=http%3A%2F%2Fk003.kiwi6.com%2Fuploads%2Fhotlink%2F\\1\" />\n<param name=\"quality\" value=\"high\" />\n<param name=\"menu\" value=\"false\" />\n<param name=\"allowscriptaccess\" value=\"always\" />\n<param name=\"wmode\" value=\"transparent\" />\n</object>\n", $text);
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
$text = preg_replace("/\{BoardName\}/is", $Settings['board_name'], $text);
$text = preg_replace("/\{BoardURL\}/is", $Settings['idburl'], $text);
$text = preg_replace("/\{WebSiteURL\}/is", $Settings['weburl'], $text);
$text = preg_replace("/\[DATE\]/is", GMTimeGet('M j Y',$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']), $text);
//$text = preg_replace("/\[DATE\=(.*?)\]/is", GMTimeGet("${1}",$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']), $text);
$text = preg_replace("/\[TIME\]/is", GMTimeGet('g:i a',$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']), $text);
//$text = preg_replace("/\[TIME\=(.*?)\]/is", GMTimeGet("${1}",$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']), $text);
$text = preg_replace_callback("/\[DATE\=&quot;(.*?)&quot;\]/is", "bbcode_date_time", $text);
$text = preg_replace_callback("/\[TIME\=&quot;(.*?)&quot;\]/is", "bbcode_date_time", $text);
$text = preg_replace_callback("/\[DATE\=(.*?)\]/is", "bbcode_date_time", $text);
$text = preg_replace_callback("/\[TIME\=(.*?)\]/is", "bbcode_date_time", $text);
$text = preg_replace("/\{DATE\}/is", GMTimeGet('g:i a',$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']), $text);
//$text = preg_replace("/\{DATE\=(.*?)\}/is", GMTimeGet("${1}",$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']), $text);
$text = preg_replace("/\{TIME\}/is", GMTimeGet('M j Y',$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']), $text);
//$text = preg_replace("/\{TIME\=(.*?)\}/is", GMTimeGet("${1}",$_SESSION['UserTimeZone'],0,$_SESSION['UserDST']), $text);
$text = preg_replace_callback("/\{DATE\=&quot;(.*?)\"\}/is", "bbcode_date_time", $text);
$text = preg_replace_callback("/\{TIME\=&quot;(.*?)\"\}/is", "bbcode_date_time", $text);
$text = preg_replace_callback("/\[RAND\=&quot;([\-]?[0-9]+)&quot;\]/is", "bbcode_random", $text);
$text = preg_replace_callback("/\[RAND\=&quot;([\-]?[0-9]+)&quot;,&quot;([\-]?[0-9]+)&quot;\]/is", "bbcode_random", $text);
$text = preg_replace_callback("/\[RAND\=&quot;([\-]?[0-9]+),([\-]?[0-9]+)&quot;\]/is", "bbcode_random", $text);
$text = preg_replace_callback("/\{RAND\=&quot;([\-]?[0-9]+)\"\}/is", "bbcode_random", $text);
$text = preg_replace_callback("/\{RAND\=&quot;([\-]?[0-9]+),([\-]?[0-9]+)\"\}/is", "bbcode_random", $text);
$text = preg_replace("/\[Entity\=&quot;([A-Za-z0-9\#]+)&quot;\]/is", "&\\1;", $text);
$text = preg_replace("/\{Entity\=&quot;([A-Za-z0-9\#]+)\"\}/is", "&\\1;", $text);
$text = preg_replace_callback("/\{DATE\=(.*?)\}/is", "bbcode_date_time", $text);
$text = preg_replace_callback("/\{TIME\=(.*?)\}/is", "bbcode_date_time", $text);
$text = preg_replace_callback("/\[RAND\=([\-]?[0-9]+)\]/is", "bbcode_random", $text);
$text = preg_replace_callback("/\[RAND\=([\-]?[0-9]+),([\-]?[0-9]+)\]/is", "bbcode_random", $text);
$text = preg_replace_callback("/\{RAND\=([\-]?[0-9]+)\}/is", "bbcode_random", $text);
$text = preg_replace_callback("/\{RAND\=([\-]?[0-9]+),([\-]?[0-9]+)\}/is", "bbcode_random", $text);
$text = preg_replace("/\[Entity\=([A-Za-z0-9\#]+)\]/is", "&\\1;", $text);
$text = preg_replace("/\{Entity\=([A-Za-z0-9\#]+)\}/is", "&\\1;", $text);
$text = preg_replace("/\[B\](.*?)\[\/B\]/is", "<span style=\"font-weight: bold;\">\\1</span>", $text);
$text = preg_replace("/\[BOLD\](.*?)\[\/BOLD\]/is", "<span style=\"font-weight: bold;\">\\1</span>", $text);
$text = preg_replace("/\[I\](.*?)\[\/I\]/is", "<span style=\"font-style: italic;\">\\1</span>", $text);
$text = preg_replace("/\[ITALIC\](.*?)\[\/ITALIC\]/is", "<span style=\"font-style: italic;\">\\1</span>", $text);
$text = preg_replace("/\[OBLIQUE\](.*?)\[\/OBLIQUE\]/is", "<span style=\"font-style: oblique;\">\\1</span>", $text);
$text = preg_replace("/\[S\](.*?)\[\/S\]/is", "<span style=\"font-style: strike;\">\\1</span>", $text);
$text = preg_replace("/\[STRIKE\](.*?)\[\/STRIKE\]/is", "<span style=\"font-style: strike;\">\\1</span>", $text);
$text = preg_replace("/\[U\](.*?)\[\/U\]/is", "<span style=\"text-decoration: underline;\">\\1</span>", $text);
$text = preg_replace("/\[O\](.*?)\[\/O\]/is", "<span style=\"text-decoration: overline;\">\\1</span>", $text);
$text = preg_replace("/\[CENTER\](.*?)\[\/CENTER\]/is", "<span style=\"text-align: center;\">\\1</span>", $text);
$text = preg_replace("/\[LTR\](.*?)\[\/LTR\]/is", "<span style=\"direction: rtl;\">\\1</span>", $text);
$text = preg_replace("/\[FONT\=&quot;([A-Za-z0-9\,\s]+)&quot;\](.*?)\[\/FONT\]/is", "<span style=\"font-family: \\1px;\">\\2</span>", $text);
$text = preg_replace("/\[DIV\=&quot;([A-Za-z0-9,\.%\-_\:;~\(\)#\s]+)&quot;\](.*?)\[\/DIV\]/is", "<div style=\"\\1\">\\2</div>", $text);
$text = preg_replace("/\[SPAN\=&quot;([A-Za-z0-9,\.%\-_\:;~\(\)#\s]+)&quot;\](.*?)\[\/SPAN\]/is", "<span style=\"\\1\">\\2</span>", $text);
$text = preg_replace("/\[SIZE\=&quot;([0-9]+)&quot;\](.*?)\[\/SIZE\]/is", "<span style=\"font-size: \\1px;\">\\2</span>", $text);
$text = preg_replace("/\[SIZE\=&quot;([0-9]+)\%&quot;\](.*?)\[\/SIZE\]/is", "<span style=\"font-size: \\1%;\">\\2</span>", $text);
$text = preg_replace("/\[SIZE\=&quot;([0-9]+)(em|pt|px)&quot;\](.*?)\[\/SIZE\]/is", "<span style=\"font-size: \\1\\2;\">\\3</span>", $text);
$text = preg_replace("/\[COLOR\=&quot;([A-Za-z0-9]+)&quot;\](.*?)\[\/COLOR\]/is", "<span style=\"color: \\1;\">\\2</span>", $text);
$text = preg_replace("/\[COLOR\=&quot;\#([A-Za-z0-9]+)&quot;\](.*?)\[\/COLOR\]/is", "<span style=\"color: #\\1;\">\\2</span>", $text);
$text = preg_replace("/\[COLOR\=&quot;rgb\(([0-9\,\s]+)\"\)\](.*?)\[\/COLOR\]/is", "<span style=\"color: rgb(\\1);\">\\2</span>", $text);
$text = preg_replace("/\[BGCOLOR\=&quot;([A-Za-z0-9]+)&quot;\](.*?)\[\/BGCOLOR\]/is", "<span style=\"background-color: \\1;\">\\2</span>", $text);
$text = preg_replace("/\[BGCOLOR\=&quot;\#([A-Za-z0-9]+)&quot;\](.*?)\[\/BGCOLOR\]/is", "<span style=\"background-color: #\\1;\">\\2</span>", $text);
$text = preg_replace("/\[BGCOLOR\=&quot;rgb\(([0-9\,\s]+)\"\)\](.*?)\[\/BGCOLOR\]/is", "<span style=\"background-color: rgb(\\1);\">\\2</span>", $text);
$text = preg_replace("/\[COLOUR\=&quot;([A-Za-z0-9]+)&quot;\](.*?)\[\/COLOUR\]/is", "<span style=\"color: \\1;\">\\2</span>", $text);
$text = preg_replace("/\[COLOUR\=&quot;\#([A-Za-z0-9]+)&quot;\](.*?)\[\/COLOUR\]/is", "<span style=\"color: #\\1;\">\\2</span>", $text);
$text = preg_replace("/\[COLOUR\=&quot;rgb\(([0-9\,\s]+)\"\)\](.*?)\[\/COLOUR\]/is", "<span style=\"color: rgb(\\1);\">\\2</span>", $text);
$text = preg_replace("/\[BGCOLOUR\=&quot;([A-Za-z0-9]+)&quot;\](.*?)\[\/BGCOLOUR\]/is", "<span style=\"background-color: \\1;\">\\2</span>", $text);
$text = preg_replace("/\[BGCOLOUR\=&quot;\#([A-Za-z0-9]+)&quot;\](.*?)\[\/BGCOLOUR\]/is", "<span style=\"background-color: #\\1;\">\\2</span>", $text);
$text = preg_replace("/\[BGCOLOUR\=&quot;rgb\(([0-9\,\s]+)\"\)\](.*?)\[\/BGCOLOUR\]/is", "<span style=\"background-color: rgb(\\1);\">\\2</span>", $text);
$text = preg_replace("/\[ALIGN=\"(left|center|right|justify)&quot;\](.*?)\[\/ALIGN\]/is", "<div style=\"text-align: \\1;\">\\2</div>", $text);
$text = preg_replace("/\[VALIGN=\"(.*?)&quot;\](.*?)\[\/VALIGN\]/is", "<div style=\"vertical-align: \\1;\">\\2</div>", $text);
$text = preg_replace("/\[FLOAT=\"(left|right)&quot;\](.*?)\[\/FLOAT\]/is", "<div style=\"float: \\1;\">\\2</div>", $text);
$text = preg_replace("/\[FONT\=([A-Za-z0-9\,\s]+)\](.*?)\[\/FONT\]/is", "<span style=\"font-family: \\1px;\">\\2</span>", $text);
$text = preg_replace("/\[DIV\=([A-Za-z0-9,\.%\-_\:;~\(\)#\s]+)\](.*?)\[\/DIV\]/is", "<div style=\"\\1\">\\2</div>", $text);
$text = preg_replace("/\[SPAN\=([A-Za-z0-9,\.%\-_\:;~\(\)#\s]+)\](.*?)\[\/SPAN\]/is", "<span style=\"\\1\">\\2</span>", $text);
$text = preg_replace("/\[COMMENT\](.*?)\[COMMENT\]/is", "<!--\\1-->", $text);
$text = preg_replace("/\[SIZE\=([0-9]+)\](.*?)\[\/SIZE\]/is", "<span style=\"font-size: \\1px;\">\\2</span>", $text);
$text = preg_replace("/\[SIZE\=([0-9]+)\%\](.*?)\[\/SIZE\]/is", "<span style=\"font-size: \\1%;\">\\2</span>", $text);
$text = preg_replace("/\[SIZE\=([0-9]+)(em|pt|px)\](.*?)\[\/SIZE\]/is", "<span style=\"font-size: \\1\\2;\">\\3</span>", $text);
$text = preg_replace("/\[COLOR\=([A-Za-z0-9]+)\](.*?)\[\/COLOR\]/is", "<span style=\"color: \\1;\">\\2</span>", $text);
$text = preg_replace("/\[COLOR\=\#([A-Za-z0-9]+)\](.*?)\[\/COLOR\]/is", "<span style=\"color: #\\1;\">\\2</span>", $text);
$text = preg_replace("/\[COLOR\=rgb\(([0-9\,\s]+)\)\](.*?)\[\/COLOR\]/is", "<span style=\"color: rgb(\\1);\">\\2</span>", $text);
$text = preg_replace("/\[BGCOLOR\=([A-Za-z0-9]+)\](.*?)\[\/BGCOLOR\]/is", "<span style=\"background-color: \\1;\">\\2</span>", $text);
$text = preg_replace("/\[BGCOLOR\=\#([A-Za-z0-9]+)\](.*?)\[\/BGCOLOR\]/is", "<span style=\"background-color: #\\1;\">\\2</span>", $text);
$text = preg_replace("/\[BGCOLOR\=rgb\(([0-9\,\s]+)\)\](.*?)\[\/BGCOLOR\]/is", "<span style=\"background-color: rgb(\\1);\">\\2</span>", $text);
$text = preg_replace("/\[COLOUR\=([A-Za-z0-9]+)\](.*?)\[\/COLOUR\]/is", "<span style=\"color: \\1;\">\\2</span>", $text);
$text = preg_replace("/\[COLOUR\=\#([A-Za-z0-9]+)\](.*?)\[\/COLOUR\]/is", "<span style=\"color: #\\1;\">\\2</span>", $text);
$text = preg_replace("/\[COLOUR\=rgb\(([0-9\,\s]+)\)\](.*?)\[\/COLOUR\]/is", "<span style=\"color: rgb(\\1);\">\\2</span>", $text);
$text = preg_replace("/\[BGCOLOUR\=([A-Za-z0-9]+)\](.*?)\[\/BGCOLOUR\]/is", "<span style=\"background-color: \\1;\">\\2</span>", $text);
$text = preg_replace("/\[BGCOLOUR\=\#([A-Za-z0-9]+)\](.*?)\[\/BGCOLOUR\]/is", "<span style=\"background-color: #\\1;\">\\2</span>", $text);
$text = preg_replace("/\[BGCOLOUR\=rgb\(([0-9\,\s]+)\)\](.*?)\[\/BGCOLOUR\]/is", "<span style=\"background-color: rgb(\\1);\">\\2</span>", $text);
$text = preg_replace("/\[ALIGN=(left|center|right|justify)\](.*?)\[\/ALIGN\]/is", "<div style=\"text-align: \\1;\">\\2</div>", $text);
$text = preg_replace("/\[VALIGN=(.*?)\](.*?)\[\/VALIGN\]/is", "<div style=\"vertical-align: \\1;\">\\2</div>", $text);
$text = preg_replace("/\[FLOAT=(left|right)\](.*?)\[\/FLOAT\]/is", "<div style=\"float: \\1;\">\\2</div>", $text);
// Sub URL and IMG tags
$text = preg_replace_callback("/\[URL](.*?)\[\/URL\]/is", "urlcheck2", $text);
$text = preg_replace("/\[URL\=&quot;(.*?)&quot;\](.*?)\[\/URL\]/is", "[URL=\\1]\\2[/URL]", $text);
$text = preg_replace_callback("/\[URL\=(.*?)\](.*?)\[\/URL\]/is", "urlcheck2", $text);
$text = preg_replace_callback("/\[IMG](.*?)\[\/IMG\]/is", "urlcheck2", $text);
$text = preg_replace("/\[IMG\=&quot;(.*?)&quot;\](.*?)\[\/IMG\]/is", "[IMG=\\1]\\2[/IMG]", $text);
$text = preg_replace_callback("/\[IMG\=(.*?)](.*?)\[\/IMG\]/is", "urlcheck2", $text);
$text = preg_replace_callback("/\[URLENCODE\](.*?)\[\/URLENCODE\]/is","bbcode_urlencode",$text);
$text = preg_replace_callback("/\[URLDECODE\](.*?)\[\/URLDECODE\]/is","bbcode_urldecode",$text);
$text = preg_replace_callback("/\[BASE64\](.*?)\[\/BASE64\]/is","bbcode_base64encode",$text);
$text = preg_replace_callback("/\[BASE64\=ENCODE\](.*?)\[\/BASE64\]/is","bbcode_base64encode",$text);
$text = preg_replace_callback("/\[BASE64\=DECODE\](.*?)\[\/BASE64\]/is","bbcode_base64decode",$text);
$text = preg_replace_callback("/\[ROT13\](.*?)\[\/ROT13\]/is","bbcode_rot13",$text);
$text = preg_replace("/\[JavaScript\](.*?)\[\/JavaScript\]/is","[DoHTML]\n&lt;script type=&quot;text/javascript&quot;&gt;\n\\1\n&lt;/script&gt;\n[/DoHTML]",$text);
return $text; }
function ibbcode_parser($text) { return bbcode_parser($text); }
?>