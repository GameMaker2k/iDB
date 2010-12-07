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

    $FileInfo: sqlite.php - Last Update: 12/07/2010 SVN 600 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="sqlite.php"||$File3Name=="/sqlite.php") {
	require('index.php');
	exit(); }

if($_SESSION['UserGroup']==$Settings['GuestGroup']||$GroupInfo['HasAdminCP']=="no") {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if($Settings['sqltype']!="sqlite") {
redirect("location",$rbasedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],false));
ob_clean(); header("Content-Type: text/plain; charset=".$Settings['charset']);
gzip_page($Settings['use_gzip'],$GZipEncode['Type']); session_write_close(); die(); }
if(!isset($_GET['outtype'])) { $_GET['outtype'] = "UTF-8"; }
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("Cache-Control: private",false); 
header("Content-Description: File Transfer");
if(!isset($_GET['comlevel'])) {
	$_GET['comlevel'] = -1; }
if(!is_numeric($_GET['comlevel'])) {
	$_GET['comlevel'] = -1; }
if($_GET['comlevel']>9||$_GET['comlevel']<-1) {
	$_GET['comlevel'] = -1; }
if(!isset($_GET['compress'])) {
	$_GET['compress'] = "none"; }
if($_GET['compress']=="gzip") {
	$_GET['compress'] = "gzencode"; }
if($_GET['compress']=="bzip"||
	$_GET['compress']=="bzip2") {
	$_GET['compress'] = "bzcompress"; }
if($_GET['compress']!="none"&&
	$_GET['compress']!="gzencode"&&
	$_GET['compress']!="gzcompress"&&
	$_GET['compress']!="gzdeflate"&&
	$_GET['compress']!="bzcompress") {
	$_GET['compress'] = "none"; }
if(!extension_loaded("zlib")) {
if($_GET['compress']=="gzencode"&&
	$_GET['compress']=="gzcompress"&&
	$_GET['compress']=="gzdeflate") {
	$_GET['compress'] = "none"; } }
if(!extension_loaded("bz2")) {
if($_GET['compress']=="bzcompress") {
	$_GET['compress'] = "none"; } }
if($_GET['compress']=="bzcompress") {
if($_GET['comlevel']>9||$_GET['comlevel']<0) {
	$_GET['comlevel'] = 4; } }
$fname = null;
if(isset($Settings['sqldb'])&&$Settings['sqldb']!="") {
$fname = str_replace("_","", $Settings['sqldb'])."_"; }
if($_GET['compress']=="none") {
$fname .= str_replace("_","", $Settings['sqltable']).".sql"; }
if($_GET['compress']=="gzencode") {
$fname .= str_replace("_","", $Settings['sqltable']).".sql.gz"; }
if($_GET['compress']=="gzcompress") {
$fname .= str_replace("_","", $Settings['sqltable']).".sql.gz"; }
if($_GET['compress']=="gzdeflate") {
$fname .= str_replace("_","", $Settings['sqltable']).".sql.gz"; }
if($_GET['compress']=="bzcompress") {
$fname .= str_replace("_","", $Settings['sqltable']).".sql.bz2"; }
header("Content-Disposition: attachment; filename=".$fname);
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
$TableChCk = array("categories", "catpermissions", "events", "forums", "groups", "members", "messenger", "permissions", "posts", "restrictedwords", "sessions", "smileys", "themes", "topics", "wordfilter");
$TableChCk = array_map("add_prefix",$TableChCk);
if(!isset($_GET['outtype'])||$_GET['outtype']=="UTF-8") {
header("Content-Type: text/plain; charset=UTF-8"); }
if($_GET['outtype']=="latin1") {
header("Content-Type: text/plain; charset=ISO-8859-15"); }
if($_GET['outtype']=="latin15") {
header("Content-Type: text/plain; charset=ISO-8859-15"); }
$sli = 0; $slnum = count($TableChCk);
while ($sli < $slnum) {
/*
$FullTable[$sli] = "CREATE TABLE \"".$TableChCk[$sli]."\" (\n";
*/
$tabsta = sql_query("SELECT * FROM sqlite_master WHERE type=\"table\" and tbl_name=\"".$TableChCk[$sli]."\";",$SQLStat);
$tabstats = sql_fetch_array($tabsta);
$FullTable[$sli] = $tabstats['sql'].";\n";
/*
$zli = 0;
$tabsta = sql_query("PRAGMA table_info(\"".$TableChCk[$sli]."\");",$SQLStat);
while ($tabstats = sql_fetch_array($tabsta)) {
var_dump($tabstats);
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
*/
++$sli; }
$TableNames = $TableChCk;
$num = count($TableNames); $renee_s = 0;
$sqldump = "-- ".$OrgName." ".$SQLDumper."\n";
$sqldump .= "-- version ".$VerInfo['iDB_Ver_SVN']."\n";
$sqldump .= "-- ".$iDBHome."support/\n";
$sqldump .= "--\n";
$sqldump .= "-- Generation Time: ".GMTimeGet('F d, Y \a\t h:i A',$_SESSION['UserTimeZone'],0,$_SESSION['UserDST'])."\n";
$sqldump .= "-- SQLite Server version: ".sql_server_info($SQLStat)."\n";
$sqldump .= "-- PHP Version: ".phpversion()."\n\n";
$sqldump .= "--\n";
$sqldump .= "-- Database: \"".$Settings['sqldb']."\"\n";
$sqldump .= "--\n\n";
$sqldump .= "-- --------------------------------------------------------\n\n";
while ($renee_s < $num) { $tnum = $num - 1;
$trow = GetAllRows($TableNames[$renee_s]);
$numz = count($trow); $kazuki_p = 0;
$sqldump .= "--\n";
$sqldump .= "-- Table structure for table \"".$TableNames[$renee_s]."\"\n";
$sqldump .= "--\n\n";
$sqldump .= $FullTable[$renee_s]."\n";
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
$sqldump .= "--\n";
$sqldump .= "-- Dumping data for table \"".$TableNames[$renee_s]."\"\n";
$sqldump .= "--\n\n"; }
$sqldump .= $srow."\n";
$sqldump .= $srowvalue."\n";
if($kazuki_p==$tnumz&&$renee_s<$tnum) {
$sqldump .= "\n-- --------------------------------------------------------\n"; }
++$kazuki_p; }
if($numz===0) {
$sqldump .= "--\n";
$sqldump .= "-- Dumping data for table \"".$TableNames[$renee_s]."\"\n";
$sqldump .= "--\n\n";
$sqldump .= "\n-- --------------------------------------------------------\n"; }
$sqldump .= "\n";
++$renee_s; }
if($_GET['compress']=="none") { echo $sqldump; }
if($_GET['compress']=="gzencode") { echo gzencode($sqldump,$_GET['comlevel']); }
if($_GET['compress']=="gzcompress") { echo gzcompress($sqldump,$_GET['comlevel']); }
if($_GET['compress']=="gzdeflate") { echo gzdeflate($sqldump,$_GET['comlevel']); }
if($_GET['compress']=="bzcompress") { echo bzcompress($sqldump,$_GET['comlevel']); }
fix_amp($Settings['use_gzip'],$GZipEncode['Type']);
?>
