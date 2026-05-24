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

    $FileInfo: sqlsrv.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/

$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "sqlsrv.php" || $File3Name == "/sqlsrv.php") {
    require('index.php');
    exit();
}

if ($_SESSION['UserGroup'] == $Settings['GuestGroup'] || $GroupInfo['HasAdminCP'] == "no") {
    redirect("location", $rbasedir . url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
    ob_clean();
    header("Content-Type: text/plain; charset=" . $Settings['charset']);
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    session_write_close();
    die();
}

if ($Settings['sqltype'] != "sqlsrv" && $Settings['sqltype'] != "pdo_sqlsrv") {
    redirect("location", $rbasedir . url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
    ob_clean();
    header("Content-Type: text/plain; charset=" . $Settings['charset']);
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

$TablePreFix = $Settings['sqltable'];
$TableChCk = array("categories", "catpermissions", "events", "forums", "groups", "levels", "members", "mempermissions", "messenger", "permissions", "polls", "posts", "ranks", "restrictedwords", "sessions", "smileys", "themes", "topics", "wordfilter");

function add_prefix($tarray)
{
    global $TablePreFix;
    return $TablePreFix . $tarray;
}

$TableChCk = array_map("add_prefix", $TableChCk);

if ($_GET['outtype'] == "UTF-8") {
    header("Content-Type: text/plain; charset=UTF-8");
} elseif ($_GET['outtype'] == "latin1") {
    header("Content-Type: text/plain; charset=ISO-8859-1");
} elseif ($_GET['outtype'] == "latin15") {
    header("Content-Type: text/plain; charset=ISO-8859-15");
}

$sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = 'dbo'";
$result = sql_query($sql, $SQLStat);
if (!$result) {
    echo "DB Error, could not list tables\n";
    echo 'SQL Server Error: ' . sql_error($SQLStat);
    exit;
}

$TableNames = array();
while ($row = sql_fetch_assoc($result)) {
    if (in_array($row['TABLE_NAME'], $TableChCk)) {
        $TableNames[] = $row['TABLE_NAME'];
    }
}

$sqldump = "-- SQL Server Dump by iDB Support\n";
$sqldump .= "-- Host: " . $Settings['sqlhost'] . "\n";
$sqldump .= "-- Generation Time: " . date('F d, Y \a\t h:i A') . "\n";
$sqldump .= "-- Server version: " . sql_server_info($SQLStat) . "\n";
$sqldump .= "-- PHP Version: " . phpversion() . "\n\n";

foreach ($TableNames as $tableName) {
    // Get table structure
    $sql = "SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_NAME = '$tableName'";
    $result = sql_query($sql, $SQLStat);

    $sqldump .= "--\n-- Table structure for table \"$tableName\"\n--\n";
    $createTable = "CREATE TABLE \"$tableName\" (\n";
    while ($row = sql_fetch_assoc($result)) {
        $createTable .= "    \"" . $row['COLUMN_NAME'] . "\" " . strtoupper($row['DATA_TYPE']);
        if ($row['IS_NULLABLE'] == 'NO') {
            $createTable .= " NOT NULL";
        }
        if (!is_null($row['COLUMN_DEFAULT'])) {
            $createTable .= " DEFAULT " . $row['COLUMN_DEFAULT'];
        }
        $createTable .= ",\n";
    }
    $createTable = rtrim($createTable, ",\n") . "\n);\n";
    $sqldump .= $createTable . "\n";

    // Get table data
    $sql = "SELECT * FROM \"$tableName\"";
    $result = sql_query($sql, $SQLStat);
    $numFields = sql_num_fields($result);

    $sqldump .= "-- Dumping data for table \"$tableName\"\n\n";
    while ($row = sql_fetch_assoc($result)) {
        $sqldump .= "INSERT INTO \"$tableName\" (";
        $fields = array_keys($row);
        $sqldump .= "\"" . implode('", "', $fields) . "\") VALUES (";

        $values = array();
        foreach ($row as $value) {
            if (is_null($value)) {
                $values[] = "NULL";
            } elseif (is_numeric($value)) {
                $values[] = $value;
            } else {
                $values[] = "'" . sql_escape_string($value, $SQLStat) . "'";
            }
        }
        $sqldump .= implode(", ", $values) . ");\n";
    }
    $sqldump .= "\n";
}

if ($_GET['compress'] == "none") {
    echo $sqldump;
} elseif ($_GET['compress'] == "gzencode") {
    echo gzencode($sqldump, $_GET['comlevel']);
} elseif ($_GET['compress'] == "gzcompress") {
    echo gzcompress($sqldump, $_GET['comlevel']);
} elseif ($_GET['compress'] == "gzdeflate") {
    echo gzdeflate($sqldump, $_GET['comlevel']);
} elseif ($_GET['compress'] == "bzcompress") {
    echo bzcompress($sqldump, $_GET['comlevel']);
}

fix_amp($Settings['use_gzip'], $GZipEncode['Type']);
