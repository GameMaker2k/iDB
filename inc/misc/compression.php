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
    GZip and Zlib by Jean-loup Gailly (compression) and Mark Adler (decompression) http://www.zlib.net/
	BZip2 and libbzip2 by Julian Seward http://www.bzip.org/

    $FileInfo: compression.php - Last Update: 05/09/2007 SVN 1 - Author: cooldude2k $
*/
$File1Name = dirname($_SERVER['SCRIPT_NAME'])."/";
$File2Name = $_SERVER['SCRIPT_NAME'];
$File3Name=str_replace($File1Name, null, $File2Name);
if ($File3Name=="compression.php"||$File3Name=="/compression.php") {
	require('index.php');
	exit(); }

if(@extension_loaded("zlib")) {
function gunzip($infile, $outfile) {
  $zp = gzopen($infile, "r");
  while(!gzeof($zp))
       $string .= gzread($zp, 4096);
  gzclose($zp);
  $fp = fopen($outfile, "w");
  fwrite($fp, $string, strlen($string));
  fclose($fp);
}

function gunzip2($infile, $outfile) {
 $string = implode("", gzfile($infile));
 $fp = fopen($outfile, "w");
 fwrite($fp, $string, strlen($string));
 fclose($fp);
}
function gzip($infile, $outfile, $param = 5)
{
 $fp = fopen($infile, "r");
 $data = fread ($fp, filesize($infile));
 fclose($fp);
 $zp = gzopen($outfile, "w".$param);
 gzwrite($zp, $data);
 gzclose($zp);
} }

if(@extension_loaded("bz2")) {
function bzip($infile, $outfile)
{
 $fp = fopen($infile, "r");
 $data = fread($fp, filesize($infile));
 fclose($fp);
 $zp = bzopen($outfile, "w");
 bzwrite($zp, $data);
 bzclose($zp);
}

function bunzip($infile, $outfile) {
  $zp = bzopen($infile, "r");
  while(!feof($zp))
       $string .= bzread($zp, 4096);
  bzclose($zp);
  $fp = fopen($outfile, "w");
  fwrite($fp, $string, strlen($string));
  fclose($fp);
} }

if(@extension_loaded("zip")) {
/* Nothing for now... :P */ }

if(@extension_loaded("rar")) {
/* Nothing for now... :P */ }

?>
