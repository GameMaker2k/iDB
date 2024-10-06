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

    $FileInfo: cubrid.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/

$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "cubrid.php" || $File3Name == "/cubrid.php") {
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
if ($Settings['sqltype'] != "cubrid") {
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

if (!isset($_GET['compress'])) {
    $_GET['compress'] = "none";
}

if ($_GET['compress'] == "gzip") {
    $_GET['compress'] = "gzencode";
}

if ($_GET['compress'] == "bzip" || $_GET['compress'] == "bzip2") {
    $_GET['compress'] = "bzcompress";
}

if ($_GET['compress'] != "none" && $_GET['compress'] != "gzencode" &&
   $_GET['compress'] != "gzcompress" && $_GET['compress'] != "gzdeflate" &&
   $_GET['compress'] != "bzcompress") {
    $_GET['compress'] = "none";
}

if (!extension_loaded("zlib")) {
    if ($_GET['compress'] == "gzencode" || $_GET['compress'] == "gzcompress" || $_GET['compress'] == "gzdeflate") {
        $_GET['compress'] = "none";
    }
}

if (!extension_loaded("bz2")) {
    if ($_GET['compress'] == "bzcompress") {
        $_GET['compress'] = "none";
    }
}

// Connect to CUBRID database
$conn = cubrid_connect("localhost", 33000, $Settings['sqldb'], "dba", "");
if (!$conn) {
    die("Connection failed: " . cubrid_error_msg());
}

$TableChCk = array("categories", "catpermissions", "events", "forums", "groups", "levels", "members", "mempermissions", "messenger", "permissions", "polls", "posts", 'ranks', "restrictedwords", "sessions", "smileys", "themes", "topics", "wordfilter");

$TablePreFix = $Settings['sqltable'];
$TableChCk = array_map(function ($table) use ($TablePreFix) {
    return $TablePreFix . $table;
}, $TableChCk);

$fname = str_replace("_", "", $Settings['sqldb'])."_".str_replace("_", "", $Settings['sqltable']);
switch ($_GET['compress']) {
    case 'none':
        $fname .= ".sql";
        break;
    case 'gzencode':
    case 'gzcompress':
    case 'gzdeflate':
        $fname .= ".sql.gz";
        break;
    case 'bzcompress':
        $fname .= ".sql.bz2";
        break;
}

header("Content-Disposition: attachment; filename=".$fname);
header("Content-Type: application/octet-stream");
header("Content-Transfer-Encoding: binary");

function getCreateTableSQL($conn, $tableName)
{
    $sql = "SHOW CREATE TABLE " . $tableName;
    $result = cubrid_execute($conn, $sql);

    if ($result) {
        $row = cubrid_fetch_assoc($result);
        cubrid_free_result($result);
        return $row['Create Table'] ?? null;
    } else {
        return null;
    }
}

$tablesResult = cubrid_execute($conn, "SHOW TABLES");
$sqldump = "-- CUBRID SQL Dump\n\n";

if ($tablesResult) {
    while ($table = cubrid_fetch_assoc($tablesResult)) {
        $tableName = $table['Name'];

        // Only dump tables with the specified prefix
        if (in_array($tableName, $TableChCk)) {
            $createTableSQL = getCreateTableSQL($conn, $tableName);
            $sqldump .= "-- Table structure for table `".$tableName."`\n";
            $sqldump .= $createTableSQL . ";\n\n";

            // Fetch and insert data for each table
            $rows = cubrid_execute($conn, "SELECT * FROM " . $tableName);
            if ($rows) {
                $sqldump .= "-- Dumping data for table `".$tableName."`\n";
                while ($row = cubrid_fetch_assoc($rows)) {
                    $values = array_map(function ($val) use ($conn) { return "'" . cubrid_real_escape_string($val, $conn) . "'"; }, $row);
                    $sqldump .= "INSERT INTO `".$tableName."` VALUES (" . implode(", ", $values) . ");\n";
                }
                cubrid_free_result($rows);
            }
            $sqldump .= "\n-- --------------------------------------------------------\n\n";
        }
    }
    cubrid_free_result($tablesResult);
}

cubrid_disconnect($conn);

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
