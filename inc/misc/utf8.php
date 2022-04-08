<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2019 iDB Support - https://idb.osdn.jp/support/category.php?act=view&id=1
    Copyright 2004-2019 Game Maker 2k - https://idb.osdn.jp/support/category.php?act=view&id=2

    $FileInfo: utf8.php - Last Update: 4/8/2022 SVN 948 - Author: cooldude2k $
*/
// UTF8 helper functions
// author: Scott Michael Reynen "scott@randomchaos.com"
// url: http://www.randomchaos.com/document.php?source=php_and_unicode
// utf8_substr by frank at jkelloggs dot dk
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="utf8.php"||$File3Name=="/utf8.php") {
	require('index.php');
	exit(); }

function utf8_strlen($str) {
if(isset($str)) {
return strlen(utf8_decode($str)); }
if(!isset($str)) {
return $str; } }
function pre_strlen($str) {
global $chkcharset;
if($chkcharset=="UTF-8") {
if(!defined('UTF8_NOMBSTRING')&&function_exists('mb_strlen')) {
if(isset($str)) {
return mb_strlen($str,'utf-8'); }
if(!isset($str)) {
return $str; } }
else { 
if(isset($str)) {
return utf8_strlen($str); }
if(!isset($str)) {
return $str; } } } }
if($chkcharset!="UTF-8") { return strlen($str); } }

// utf8_substr by frank at jkelloggs dot dk
// http://us3.php.net/manual/en/function.substr.php#55107
function utf8_substr($str,$start)
{
   preg_match_all("/./su", $str, $ar);
   if(func_num_args() >= 3) {
       $end = func_get_arg(2);
       return join("",array_slice($ar[0],$start,$end));
   } else {
       return join("",array_slice($ar[0],$start));
   }
}

function pre_substr($string,$start,$length) {
   global $chkcharset;
   if($chkcharset=="UTF-8") {
      if(!defined('UTF8_NOMBSTRING')&&function_exists('mb_substr')) {
      return mb_substr($string,$start,$length,'utf-8'); }
   else { return utf8_substr($string,$start,$length); } }
   if($chkcharset!="UTF-8") { return substr($string,$start,$length); } }
      if(isset($_GET['text'])) {
   echo pre_substr($_GET['text'],0,6); }

// author: Scott Michael Reynen "scott@randomchaos.com"
// url: http://www.randomchaos.com/document.php?source=php_and_unicode
function utf8_strpos($haystack, $needle,$offset=0) {
  if(!defined('UTF8_NOMBSTRING')&&function_exists('mb_strpos')) {
  return mb_strpos($haystack,$needle,$offset,'utf-8'); }
  $haystack = utf8_to_unicode($haystack);
  $needle   = utf8_to_unicode($needle);
  $position = $offset;
  $found = false;
  while( (! $found ) && ( $position < count( $haystack ) ) ) {
    if ( $needle[0] == $haystack[$position] ) {
      for ($i = 1; $i < count( $needle ); $i++ ) {
        if ( $needle[$i] != $haystack[ $position + $i ] ) break;
      } // for
      if ( $i == count( $needle ) ) {
        $found = true;
        $position--;
      } // if
    } // if
    $position++;
  } // while
  return ( $found == true ) ? $position : false;
} // strpos_unicode

// author: Scott Michael Reynen "scott@randomchaos.com"
// url: http://www.randomchaos.com/document.php?source=php_and_unicode
function utf8_to_unicode( $str ) {
  $unicode = array();  
  $values = array();
  $lookingFor = 1;
  for ($i = 0; $i < strlen( $str ); $i++ ) {
    $thisValue = ord( $str[ $i ] );
    if ( $thisValue < 128 ) $unicode[] = $thisValue;
    else {
      if ( count( $values ) == 0 ) $lookingFor = ( $thisValue < 224 ) ? 2 : 3;
      $values[] = $thisValue;
      if ( count( $values ) == $lookingFor ) {
  $number = ( $lookingFor == 3 ) ?
    ( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ):
  	( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64 );
  $unicode[] = $number;
  $values = array();
  $lookingFor = 1;
      } // if
    } // if
  } // for
  return $unicode;
} // utf8_to_unicode

// author: Scott Michael Reynen "scott@randomchaos.com"
// url: http://www.randomchaos.com/document.php?source=php_and_unicode
function unicode_to_utf8( $str ) {
  $utf8 = '';
  foreach( $str as $unicode ) {
    if ( $unicode < 128 ) {
      $utf8.= chr( $unicode );
    } elseif ( $unicode < 2048 ) {
      $utf8.= chr( 192 +  ( ( $unicode - ( $unicode % 64 ) ) / 64 ) );
      $utf8.= chr( 128 + ( $unicode % 64 ) );
    } else {
      $utf8.= chr( 224 + ( ( $unicode - ( $unicode % 4096 ) ) / 4096 ) );
      $utf8.= chr( 128 + ( ( ( $unicode % 4096 ) - ( $unicode % 64 ) ) / 64 ) );
      $utf8.= chr( 128 + ( $unicode % 64 ) );
    } // if
  } // foreach
  return $utf8;
} // unicode_to_utf8
?>