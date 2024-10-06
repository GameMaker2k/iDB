<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2024 iDB Support - https://idb.osdn.jp/support/category.php?act=view&id=1
    Copyright 2004-2024 Game Maker 2k - https://idb.osdn.jp/support/category.php?act=view&id=2

    $FileInfo: mysql.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "mysql.php" || $File3Name == "/mysql.php") {
    require('index.php');
    exit();
}

if ($_SESSION['UserGroup'] == $Settings['GuestGroup'] || $GroupInfo['HasAdminCP'] == "no") {
    redirect("location", $rbasedir.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
    ob_clean();
    header("Content-Type: text/plain; charset=".$Settings['charset']);
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    session_write_close();
    die();
}
if ($Settings['sqltype'] != "pdo_mysql" && $Settings['sqltype'] != "mysqli" && $Settings['sqltype'] != "mysqli_prepare") {
    redirect("location", $rbasedir.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
    ob_clean();
    header("Content-Type: text/plain; charset=".$Settings['charset']);
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    session_write_close();
    die();
}
if (!isset($_GET['outtype'])) {
    $_GET['outtype'] = "UTF-8";
}
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);
header("Content-Description: File Transfer");
if (!isset($_GET['comlevel'])) {
    $_GET['comlevel'] = -1;
}
if (!is_numeric($_GET['comlevel'])) {
    $_GET['comlevel'] = -1;
}
if ($_GET['comlevel'] > 9 || $_GET['comlevel'] < -1) {
    $_GET['comlevel'] = -1;
}
if (!isset($_GET['compress'])) {
    $_GET['compress'] = "none";
}
if ($_GET['compress'] == "gzip") {
    $_GET['compress'] = "gzencode";
}
if ($_GET['compress'] == "bzip" ||
    $_GET['compress'] == "bzip2") {
    $_GET['compress'] = "bzcompress";
}
if ($_GET['compress'] != "none" &&
    $_GET['compress'] != "gzencode" &&
    $_GET['compress'] != "gzcompress" &&
    $_GET['compress'] != "gzdeflate" &&
    $_GET['compress'] != "bzcompress") {
    $_GET['compress'] = "none";
}
if (!extension_loaded("zlib")) {
    if ($_GET['compress'] == "gzencode" &&
        $_GET['compress'] == "gzcompress" &&
        $_GET['compress'] == "gzdeflate") {
        $_GET['compress'] = "none";
    }
}
if (!extension_loaded("bz2")) {
    if ($_GET['compress'] == "bzcompress") {
        $_GET['compress'] = "none";
    }
}
if ($_GET['compress'] == "bzcompress") {
    if ($_GET['comlevel'] > 9 || $_GET['comlevel'] < 0) {
        $_GET['comlevel'] = 4;
    }
}
$fname = null;
if (isset($Settings['sqldb']) && $Settings['sqldb'] != "") {
    $fname = str_replace("_", "", $Settings['sqldb'])."_";
}
if ($_GET['compress'] == "none") {
    $fname .= str_replace("_", "", $Settings['sqltable']).".sql";
}
if ($_GET['compress'] == "gzencode") {
    $fname .= str_replace("_", "", $Settings['sqltable']).".sql.gz";
}
if ($_GET['compress'] == "gzcompress") {
    $fname .= str_replace("_", "", $Settings['sqltable']).".sql.gz";
}
if ($_GET['compress'] == "gzdeflate") {
    $fname .= str_replace("_", "", $Settings['sqltable']).".sql.gz";
}
if ($_GET['compress'] == "bzcompress") {
    $fname .= str_replace("_", "", $Settings['sqltable']).".sql.bz2";
}
header("Content-Disposition: attachment; filename=".$fname);
header("Content-Type: application/octet-stream");
header("Content-Transfer-Encoding: binary");
if (!isset($AltSQLDumper) || $AltSQLDumper === null) {
    $SQLDumper = "SQL Dumper";
}
if (isset($AltSQLDumper) && $AltSQLDumper !== null) {
    $SQLDumper = $AltSQLDumper;
}
function GetAllRows($table)
{
    $rene_j = 0;
    $trowout = array();
    global $SQLStat;
    $tresult = sql_query("SELECT * FROM \"".$table."\"", $SQLStat);
    while ($trow = sql_fetch_assoc($tresult)) {
        $trowout[$rene_j] = $trow;
        ++$rene_j;
    }
    sql_free_result($tresult);
    return $trowout;
}
$TablePreFix = $Settings['sqltable'];
function add_prefix($tarray)
{
    global $TablePreFix;
    return $TablePreFix.$tarray;
}
$TableChCk = array("categories", "catpermissions", "events", "forums", "groups", "levels", "members", "mempermissions", "messenger", "permissions", "polls", "posts", 'ranks', "restrictedwords", "sessions", "smileys", "themes", "topics", "wordfilter");

$TableChCk = array_map("add_prefix", $TableChCk);
if (!isset($_GET['outtype']) || $_GET['outtype'] == "UTF-8") {
    header("Content-Type: text/plain; charset=UTF-8");
}
if ($_GET['outtype'] == "latin1") {
    header("Content-Type: text/plain; charset=ISO-8859-1");
}
if ($_GET['outtype'] == "latin15") {
    header("Content-Type: text/plain; charset=ISO-8859-15");
}
$sql = "SHOW TABLES LIKE '".$Settings['sqltable']."%'";
$result = sql_query($sql, $SQLStat);
if (!$result) {
    echo "DB Error, could not list tables\n";
    echo 'MySQL Error: ' . sql_error($SQLStat);
    exit;
}
$DropTable = null;
$CreateTable = null;
$TableNames = array(null);
$l = 0;
while ($row = sql_fetch_row($result)) {
    if (in_array($row[0], $TableChCk)) {
        $TableNames[$l] = $row[0];
        $DropTable[$l] = "DROP TABLE IF EXISTS \"".$row[0]."\";\n";
        $CreateTable[$l] = "CREATE TABLE IF NOT EXISTS \"".$row[0]."\" (\n";
        $CreateTable[$l] = null;
        $result2 = sql_query("SHOW COLUMNS FROM ".$row[0], $SQLStat);
        $tabsta = sql_query("SHOW TABLE STATUS LIKE '".$row[0]."'", $SQLStat);
        $tabstats = sql_fetch_assoc($tabsta);
        $AutoIncrement = " ";
        $tabstaz = sql_query("SHOW CREATE TABLE \"".$row[0]."\"", $SQLStat);
        $tabstatz = sql_fetch_assoc($tabstaz);
        $FullTable[$l] = $DropTable[$l].$tabstatz[1].";\n";
        $tabstats = sql_fetch_assoc($tabsta);
        $AutoIncrement = " ";
        /*
        if($tabstats['Auto_increment']!="") {
        $AutoIncrement = " AUTO_INCREMENT=".$tabstats['Auto_increment']." "; }
            $TableInfo[$l] = null; $TableStats = null; $i = 0;
            while ($row2 = sql_fetch_assoc($result2)) {
                $row2['Default'] = "'".$row2['Default']."'";
                if($i==0) { $row2['Default'] = null; } $DefaVaule = null;
                if($row2['Default']!=null) { $DefaVaule = " default ".$row2['Default']; }
                if($row2['Extra']!="") { $row2['Extra'] = " ".$row2['Extra']; }
            if($row2['Type']=="text") { $DefaVaule = null; }
            if(isset($PrimaryKey[$l])) {
            if($row2['Key']=="PRI"||$row2['Key']=="UNI") {
            $PrimaryKey[$l] .= ",\n"; } }
            if(!isset($PrimaryKey[$l])) { $PrimaryKey[$l] = null; }
                $TableInfo[$l] .= "  \"".$row2['Field']."\" ".$row2['Type']." NOT NULL".$DefaVaule.$row2['Extra'].",\n";
                if($row2['Key']=="PRI") { $PrimaryKey[$l] .= "  PRIMARY KEY (\"".$row2['Field']."\")"; }
                if($row2['Key']=="UNI") { $PrimaryKey[$l] .= "  UNIQUE KEY \"".$row2['Field']."\" (\"".$row2['Field']."\")"; }
            ++$i; } */
        /*
        $TableStats[$l] = ") ENGINE=".$tabstats['Engine']." DEFAULT CHARSET=".mysql_client_encoding()." COLLATE=".$tabstats['Collation'].$AutoIncrement.";\n";
        $TableInfo[$l] .= $PrimaryKey[$l]."\n".$TableStats[$l];
        $FullTable[$l] = $DropTable[$l].$CreateTable[$l].$TableInfo[$l];
         }
        $TableStats[$l] = ") ENGINE=".$tabstats['Engine']." DEFAULT CHARSET=".mysql_client_encoding()." COLLATE=".$tabstats['Collation'].$AutoIncrement.";\n";
        $TableInfo[$l] .= $PrimaryKey[$l]."\n".$TableStats[$l];
        $FullTable[$l] = $DropTable[$l].$CreateTable[$l].$TableInfo[$l]; */
    }
    if (!$result2) {
        echo 'Could not run query: ' . sql_error($SQLStat);
        exit;
    }
    sql_free_result($result2);
    sql_free_result($tabsta);
    ++$l;
} $tableout = null;
$num = count($TableNames);
$melanie_p = 0;
$sqldump = "-- ".$OrgName." ".$SQLDumper."\n";
$sqldump .= "-- version ".$VerInfo['iDB_Ver_SVN']."\n";
$sqldump .= "-- ".$iDBHome."support/\n";
$sqldump .= "--\n";
$sqldump .= "-- Host: ".$Settings['sqlhost']."\n";
$sqldump .= "-- Generation Time: ".$usercurtime->format('F d, Y \a\t h:i A')."\n";
$sqldump .= "-- Server version: ".sql_server_info($SQLStat)."\n";
$sqldump .= "-- PHP Version: ".phpversion()."\n\n";
$sqldump .= "SET SESSION SQL_MODE='ANSI_QUOTES,NO_AUTO_VALUE_ON_ZERO';\n\n";
$sqldump .= "--\n";
$sqldump .= "-- Database: \"".$Settings['sqldb']."\"\n";
$sqldump .= "--\n\n";
$sqldump .= "-- --------------------------------------------------------\n\n";
while ($melanie_p < $num) {
    $tnum = $num - 1;
    $trow = GetAllRows($TableNames[$melanie_p]);
    $numz = count($trow);
    $kazuki_p = 0;
    $sqldump .= "--\n";
    $sqldump .= "-- Table structure for table \"".$TableNames[$melanie_p]."\"\n";
    $sqldump .= "--\n\n";
    $sqldump .= $FullTable[$melanie_p]."\n";
    while ($kazuki_p < $numz) {
        $tnumz = $numz - 1;
        $srow = null;
        $srowvalue = null;
        $trownew = $trow[$kazuki_p];
        $trowname = array_keys($trownew);
        $nums = count($trownew);
        $il = 0;
        while ($il < $nums) {
            $tnums = $nums - 1;
            $trowrname = sql_escape_string($trowname[$il], $SQLStat);
            $trowrvalue = sql_escape_string($trownew[$trowrname], $SQLStat);
            if ($_GET['outtype'] == "UTF-8" && $Settings['charset'] != "UTF-8") {
                $trowrvalue = utf8_encode($trowrvalue);
            }
            $trowrvalue = str_replace(array("\n", "\r"), array('\n', '\r'), $trowrvalue);
            /*if($kazuki_p===0) {*/
            if ($il === 0) {
                $srow = "INSERT INTO \"".$TableNames[$melanie_p]."\" (";
            }
            if ($il < $tnums && $il != $tnums) {
                $srow .= "\"".$trowrname."\", ";
            }
            if ($il == $tnums) {
                $srow .= "\"".$trowrname."\") VALUES";
            } /*}*/
            if ($il === 0) {
                $srowvalue = "(";
            }
            if (!is_numeric($trowrvalue)) {
                $trowrvalue = "'".$trowrvalue."'";
            }
            if ($il < $tnums) {
                $srowvalue .= $trowrvalue.", ";
            }
            if ($il == $tnums) {
                $srowvalue .= $trowrvalue;
                /*if($kazuki_p<$tnumz) { $srowvalue .= "),"; }*/
                /*if($kazuki_p==$tnumz) {*/ $srowvalue .= ");"; /*}*/
            }
            ++$il;
        }
        if ($kazuki_p === 0) {
            $sqldump .= "--\n";
            $sqldump .= "-- Dumping data for table \"".$TableNames[$melanie_p]."\"\n";
            $sqldump .= "--\n\n";
        }
        $sqldump .= $srow."\n"; /*}*/
        $sqldump .= $srowvalue."\n";
        if ($kazuki_p == $tnumz && $melanie_p < $tnum) {
            $sqldump .= "\n-- --------------------------------------------------------\n";
        }
        ++$kazuki_p;
    }
    if ($numz === 0) {
        $sqldump .= "--\n";
        $sqldump .= "-- Dumping data for table \"".$TableNames[$melanie_p]."\"\n";
        $sqldump .= "--\n\n";
        $sqldump .= "\n-- --------------------------------------------------------\n";
    }
    $sqldump .= "\n";
    ++$melanie_p;
}
if ($_GET['compress'] == "none") {
    echo $sqldump;
}
if ($_GET['compress'] == "gzencode") {
    echo gzencode($sqldump, $_GET['comlevel']);
}
if ($_GET['compress'] == "gzcompress") {
    echo gzcompress($sqldump, $_GET['comlevel']);
}
if ($_GET['compress'] == "gzdeflate") {
    echo gzdeflate($sqldump, $_GET['comlevel']);
}
if ($_GET['compress'] == "bzcompress") {
    echo bzcompress($sqldump, $_GET['comlevel']);
}
fix_amp($Settings['use_gzip'], $GZipEncode['Type']);
