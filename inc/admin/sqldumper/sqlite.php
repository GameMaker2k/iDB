<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2009 iDB Support - http://idb.berlios.de/
    Copyright 2004-2009 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: sqlite.php - Last Update: 12/13/2009 SVN 404 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="sqlite.php"||$File3Name=="/sqlite.php") {
	require('index.php');
	exit(); }

if($_SESSION['UserGroup']==$Settings['GuestGroup']||$GroupInfo['HasAdminCP']=="no") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($Settings['sqltype']!="sqlite") {
redirect("location",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if(!isset($_GET['outtype'])) { $_GET['outtype'] = "UTF-8"; }
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("Cache-Control: private",false); 
header("Content-Description: File Transfer");
$fname = str_replace("_","", $Settings['sqltable']);
header("Content-Disposition: attachment; filename=".$fname.".sql");
header("Content-Type: application/octet-stream");
header("Content-Transfer-Encoding: binary");
$SQLDumper = "SQL Dumper";
function GetAllRows($table) { $rene_j = 0; $trowout = null;
global $SQLStat;
$tresult = sql_query("SELECT * FROM \"".$table."\"",$SQLStat);
while ($trow = sql_fetch_assoc($tresult)) {
$trowout[$rene_j] = $trow;
++$rene_j; }
sql_free_result($tresult);
return $trowout; }
$TablePreFix = $Settings['sqltable'];
function add_prefix($tarray) {
global $TablePreFix;
return $TablePreFix.$tarray; }
$TableChCk = array("categories", "catpermissions", "events", "forums", "groups", "members", "messenger", "permissions", "posts", "restrictedwords", "sessions", "smileys", "topics", "wordfilter");
$TableChCk = array_map("add_prefix",$TableChCk);
if(!isset($_GET['outtype'])||$_GET['outtype']=="UTF-8") {
header("Content-Type: text/plain; charset=UTF-8"); }
if($_GET['outtype']=="latin1") {
header("Content-Type: text/plain; charset=ISO-8859-15"); }
if($_GET['outtype']=="latin15") {
header("Content-Type: text/plain; charset=ISO-8859-15"); }
if($Settings['sqltype']=="sqlite") {
$sli = 0; $slnum = count($TableChCk);
while ($sli < $slnum) {
$FullTable[$sli] = "CREATE TABLE \"".$TableChCk[$sli]."\" (\n";
$tabsta = sql_query("PRAGMA table_info(\"".$TableChCk[$sli]."\");",$SQLStat);
$zli = 0;
while ($tabstats = sql_fetch_array($tabsta)) {
if($zli>0) { $FullTable[$sli] .= ",\n"; }
$SQLDefault = null; $PrimeKey = " ";
if($tabstats['dflt_value']!==null) {
$SQLDefault = " default '".$tabstats['dflt_value']."'"; }
if($tabstats['dflt_value']===null) {
$SQLDefault = ""; }
if($tabstats['pk']=="1") {
$PrimeKey = " PRIMARY KEY "; }
$FullTable[$sli] .= "  \"".$tabstats['name']."\" ".$tabstats['type'].$PrimeKey."NOT NULL".$SQLDefault;
++$zli; }
$FullTable[$sli] .= "\n);\n";
++$sli; }
$TableNames = $TableChCk; }
$num = count($TableNames); $renee_s = 0;
echo "-- ".$OrgName." ".$SQLDumper."\n";
echo "-- version ".$VerInfo['iDB_Ver_SVN']."\n";
echo "-- ".$iDBHome."support/\n";
echo "--\n";
echo "-- Generation Time: ".GMTimeGet('F d, Y \a\t h:i A',$_SESSION['UserTimeZone'],0,$_SESSION['UserDST'])."\n";
echo "-- SQLite Server version: ".sql_server_info($SQLStat)."\n";
echo "-- PHP Version: ".phpversion()."\n\n";
echo "--\n";
echo "-- Database: \"".$Settings['sqldb']."\"\n";
echo "--\n\n";
echo "-- --------------------------------------------------------\n\n";
while ($renee_s < $num) { $tnum = $num - 1;
$trow = GetAllRows($TableNames[$renee_s]);
$numz = count($trow); $kazuki_p = 0;
echo "--\n";
echo "-- Table structure for table \"".$TableNames[$renee_s]."\"\n";
echo "--\n\n";
echo $FullTable[$renee_s]."\n";
while ($kazuki_p < $numz) { $tnumz = $numz - 1;
$srow = null; $srowvalue = null;
$trownew = $trow[$kazuki_p];
$trowname = array_keys($trownew);
$nums = count($trownew); $il = 0;
while ($il < $nums) { $tnums = $nums - 1;
$trowrname = sql_escape_string($trowname[$il],$SQLStat);
$trowrvalue = sql_escape_string($trownew[$trowrname],$SQLStat);
if($_GET['outtype']=="UTF-8"&&$Settings['charset']!="UTF-8") {
$trowrvalue = utf8_encode($trowrvalue); }
$trowrvalue = str_replace( array("\n", "\r"), array('\n', '\r'), $trowrvalue);
if($il===0) { $srow = "INSERT INTO \"".$TableNames[$renee_s]."\" ("; }
if($il<$tnums&&$il!=$tnums) { $srow .= "\"".$trowrname."\", "; }
if($il==$tnums) { $srow .= "\"".$trowrname."\") VALUES"; }
if($il===0) { $srowvalue = "("; }
if(!is_numeric($trowrvalue)) { $trowrvalue = "'".$trowrvalue."'"; }
if($il<$tnums) { $srowvalue .= $trowrvalue.", "; }
if($il==$tnums) { $srowvalue .= $trowrvalue;
if($kazuki_p<$tnumz) { $srowvalue .= ");"; }
if($kazuki_p==$tnumz) { $srowvalue .= ");"; } }
++$il; }
if($kazuki_p===0) {
echo "--\n";
echo "-- Dumping data for table \"".$TableNames[$renee_s]."\"\n";
echo "--\n\n"; }
echo $srow."\n";
echo $srowvalue."\n";
if($kazuki_p==$tnumz&&$renee_s<$tnum) {
echo "\n-- --------------------------------------------------------\n"; }
++$kazuki_p; }
if($numz===0) {
echo "--\n";
echo "-- Dumping data for table \"".$TableNames[$renee_s]."\"\n";
echo "--\n\n";
echo "\n-- --------------------------------------------------------\n"; }
echo "\n";
++$renee_s; }
fix_amp($Settings['use_gzip'],$GZipEncode['Type']);
?>
