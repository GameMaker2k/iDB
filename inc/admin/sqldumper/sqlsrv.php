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

if (!isset($_SESSION['UserGroup']) || $_SESSION['UserGroup'] == $Settings['GuestGroup'] || !isset($GroupInfo['HasAdminCP']) || $GroupInfo['HasAdminCP'] == "no") {
    redirect("location", $rbasedir.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
    ob_clean();
    header("Content-Type: text/plain; charset=".$Settings['charset']);
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    session_write_close();
    die();
}
// BUGFIX: the guard tested for "sqlsrv", which is not a registered sqltype -- the driver is called sqlsrv_prepare.
if ($Settings['sqltype'] != "sqlsrv_prepare" && $Settings['sqltype'] != "pdo_sqlsrv") {
    redirect("location", $rbasedir.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
    ob_clean();
    header("Content-Type: text/plain; charset=".$Settings['charset']);
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    session_write_close();
    die();
}

/* ------------------------------------------------------------------
   Shared dump helpers (identical in every dumper).
   ------------------------------------------------------------------ */
if (!function_exists('sqldump_quote_ident')) {
    function sqldump_quote_ident($name)
    {
        return '"' . str_replace('"', '""', (string)$name) . '"';
    }

    function sqldump_target_charset()
    {
        $out = isset($_GET['outtype']) ? $_GET['outtype'] : 'UTF-8';
        // BUGFIX: latin1 used to be mapped to ISO-8859-15.
        $map = array('UTF-8' => 'UTF-8', 'latin1' => 'ISO-8859-1', 'latin15' => 'ISO-8859-15');
        return isset($map[$out]) ? $map[$out] : 'UTF-8';
    }

    // BUGFIX: replaces utf8_encode(), deprecated in PHP 8.2 and removed in 9.
    // It also only ever handled ISO-8859-1 -> UTF-8, and was applied *after*
    // escaping, which can corrupt multi-byte escape sequences.
    function sqldump_convert_charset($value)
    {
        global $Settings;
        if (!is_string($value) || $value === '') {
            return $value;
        }
        $from = (isset($Settings['charset']) && $Settings['charset'] != '') ? $Settings['charset'] : 'UTF-8';
        $to = sqldump_target_charset();
        if (strcasecmp($from, $to) === 0) {
            return $value;
        }
        if (function_exists('mb_convert_encoding')) {
            $converted = @mb_convert_encoding($value, $to, $from);
            return ($converted === false) ? $value : $converted;
        }
        if (function_exists('iconv')) {
            $converted = @iconv($from, $to . '//TRANSLIT', $value);
            return ($converted === false) ? $value : $converted;
        }
        return $value;
    }

    // Does the active driver's escape function already add surrounding quotes?
    // PDO::quote() does; mysqli_real_escape_string() and friends do not.
    // BUGFIX: the dumpers used to append their own quotes unconditionally, so
    // every PDO-backed dump came out double-quoted ("'value'" -> "''value''").
    function sqldump_escape_adds_quotes()
    {
        static $adds = null;
        if ($adds === null) {
            global $SQLStat;
            $test = sql_escape_string('x', $SQLStat);
            $adds = (is_string($test) && strlen($test) > 1 && $test[0] === "'" && substr($test, -1) === "'");
        }
        return $adds;
    }

    // BUGFIX: NULL columns used to be written as '' instead of NULL, and the
    // is_numeric() test emitted values like "0123" unquoted, losing the
    // leading zero on restore. Strings are always quoted now; SQL engines
    // coerce '123' into numeric columns without trouble.
    function sqldump_quote_value($value)
    {
        global $SQLStat;
        if ($value === null) {
            return 'NULL';
        }
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }
        if (is_int($value) || is_float($value)) {
            return (string)$value;
        }
        $escaped = sql_escape_string((string)$value, $SQLStat);
        if (!is_string($escaped)) {
            return "'" . str_replace("'", "''", (string)$value) . "'";
        }
        return sqldump_escape_adds_quotes() ? $escaped : "'" . $escaped . "'";
    }

    // BUGFIX: the old loop escaped the *column name* and then used the escaped
    // string as the array key ($trownew[$trowrname]), so every value lookup
    // missed as soon as escaping changed the name at all -- which it always
    // does under PDO.
    function sqldump_insert_row($table, $row)
    {
        $names = array();
        $values = array();
        foreach ($row as $name => $value) {
            if (is_int($name)) {
                continue; // skip the numeric half of a BOTH-style fetch
            }
            $names[] = sqldump_quote_ident($name);
            $values[] = sqldump_quote_value(sqldump_convert_charset($value));
        }
        if (count($names) === 0) {
            return '';
        }
        return "INSERT INTO " . sqldump_quote_ident($table) . " (" . implode(", ", $names) . ") VALUES\n("
            . implode(", ", $values) . ");\n";
    }

    function sqldump_compress_output($sqldump)
    {
        $level = isset($_GET['comlevel']) ? (int)$_GET['comlevel'] : -1;
        switch ($_GET['compress']) {
            case 'gzencode':
                return gzencode($sqldump, $level);
            case 'gzcompress':
                return gzcompress($sqldump, $level);
            case 'gzdeflate':
                return gzdeflate($sqldump, $level);
            case 'bzcompress':
                return bzcompress($sqldump, $level);
        }
        return $sqldump;
    }
}

/* ---------------- output type / compression options ---------------- */
if (!isset($_GET['outtype']) || !in_array($_GET['outtype'], array("UTF-8", "latin1", "latin15"), true)) {
    $_GET['outtype'] = "UTF-8";
}
if (!isset($_GET['compress'])) {
    $_GET['compress'] = "none";
}
if ($_GET['compress'] == "gzip") {
    $_GET['compress'] = "gzencode";
}
if ($_GET['compress'] == "bzip" || $_GET['compress'] == "bzip2") {
    $_GET['compress'] = "bzcompress";
}
if (!in_array($_GET['compress'], array("none", "gzencode", "gzcompress", "gzdeflate", "bzcompress"), true)) {
    $_GET['compress'] = "none";
}
// BUGFIX: this test used && between three comparisons of the same value, so it
// could never be true and an unsupported method was never downgraded.
if (!extension_loaded("zlib") || !function_exists("gzencode")) {
    if (in_array($_GET['compress'], array("gzencode", "gzcompress", "gzdeflate"), true)) {
        $_GET['compress'] = "none";
    }
}
if (!extension_loaded("bz2") || !function_exists("bzcompress")) {
    if ($_GET['compress'] == "bzcompress") {
        $_GET['compress'] = "none";
    }
}
if (!isset($_GET['comlevel']) || !is_numeric($_GET['comlevel'])) {
    $_GET['comlevel'] = -1;
}
$_GET['comlevel'] = (int)$_GET['comlevel'];
if ($_GET['comlevel'] > 9 || $_GET['comlevel'] < -1) {
    $_GET['comlevel'] = -1;
}
if ($_GET['compress'] == "bzcompress" && ($_GET['comlevel'] > 9 || $_GET['comlevel'] < 1)) {
    $_GET['comlevel'] = 4;
}

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);
header("Content-Description: File Transfer");

$fname = null;
if (isset($Settings['sqldb']) && $Settings['sqldb'] != "") {
    $fname = str_replace("_", "", $Settings['sqldb'])."_";
}
$fname .= str_replace("_", "", $Settings['sqltable']);
switch ($_GET['compress']) {
    case 'gzencode':
    case 'gzcompress':
    case 'gzdeflate':
        $fname .= ".sql.gz";
        break;
    case 'bzcompress':
        $fname .= ".sql.bz2";
        break;
    default:
        $fname .= ".sql";
}

// BUGFIX: the originals sent Content-Type twice (octet-stream, then text/plain
// further down), so the download headers were overridden.
header("Content-Disposition: attachment; filename=\"".$fname."\"");
if ($_GET['compress'] == "none") {
    header("Content-Type: text/plain; charset=".sqldump_target_charset());
} else {
    header("Content-Type: application/octet-stream");
    header("Content-Transfer-Encoding: binary");
}

$SQLDumper = (isset($AltSQLDumper) && $AltSQLDumper !== null) ? $AltSQLDumper : "SQL Dumper";
$OrgNameOut = isset($OrgName) ? $OrgName : "iDB";
$VerOut = isset($VerInfo['iDB_Ver_SVN']) ? $VerInfo['iDB_Ver_SVN'] : "";
$HomeOut = isset($iDBHome) ? $iDBHome : "";
$GenTime = (isset($usercurtime) && is_object($usercurtime))
    ? $usercurtime->format('F d, Y \a\t h:i A')
    : date('F d, Y \a\t h:i A');

$TablePreFix = $Settings['sqltable'];
if (!function_exists('add_prefix')) {
    function add_prefix($tarray)
    {
        global $TablePreFix;
        return $TablePreFix.$tarray;
    }
}
$TableChCk = array("categories", "catpermissions", "events", "forums", "groups", "levels", "members", "mempermissions", "messenger", "permissions", "polls", "posts", "ranks", "restrictedwords", "sessions", "smileys", "themes", "topics", "wordfilter");
$TableChCk = array_map("add_prefix", $TableChCk);


/* ---------------------------- table list ---------------------------- */
$result = sql_query(sql_pre_query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = 'dbo' ORDER BY TABLE_NAME", array()), $SQLStat);
if (!$result) {
    echo "DB Error, could not list tables\n";
    echo 'SQL Server Error: ' . sql_error($SQLStat);
    exit;
}

$TableNames = array();
while ($row = sql_fetch_assoc($result)) {
    if (isset($row['TABLE_NAME']) && in_array($row['TABLE_NAME'], $TableChCk, true)) {
        $TableNames[] = $row['TABLE_NAME'];
    }
}
sql_free_result($result);

// BUGFIX: the old structure query had no ORDER BY (so columns came back in an
// arbitrary order), ignored TABLE_SCHEMA, dropped every length/precision from
// the type, and never emitted a primary key.
function BuildCreateTable($tableName)
{
    global $SQLStat;

    $result = sql_query(sql_pre_query(
        "SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION, NUMERIC_SCALE, IS_NULLABLE, COLUMN_DEFAULT
         FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA = 'dbo' AND TABLE_NAME = '%s'
         ORDER BY ORDINAL_POSITION",
        array($tableName)
    ), $SQLStat);

    if ($result === false) {
        return null;
    }

    $columns = array();
    while ($row = sql_fetch_assoc($result)) {
        $columns[] = $row;
    }
    sql_free_result($result);

    if (count($columns) === 0) {
        return null;
    }

    $primary = array();
    $constraints = sql_query(sql_pre_query(
        "SELECT kcu.COLUMN_NAME
         FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc
         JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
           ON tc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
          AND tc.TABLE_SCHEMA = kcu.TABLE_SCHEMA
         WHERE tc.TABLE_SCHEMA = 'dbo' AND tc.TABLE_NAME = '%s'
           AND tc.CONSTRAINT_TYPE = 'PRIMARY KEY'
         ORDER BY kcu.ORDINAL_POSITION",
        array($tableName)
    ), $SQLStat);

    if ($constraints !== false) {
        while ($row = sql_fetch_assoc($constraints)) {
            $primary[] = $row['COLUMN_NAME'];
        }
        sql_free_result($constraints);
    }

    $sized = array('char', 'varchar', 'nchar', 'nvarchar', 'binary', 'varbinary');
    $scaled = array('decimal', 'numeric');

    $lines = array();
    foreach ($columns as $column) {
        $type = strtoupper($column['DATA_TYPE']);
        $lower = strtolower($column['DATA_TYPE']);

        if (in_array($lower, $sized, true) && $column['CHARACTER_MAXIMUM_LENGTH'] !== null) {
            $length = ((int)$column['CHARACTER_MAXIMUM_LENGTH'] === -1) ? 'MAX' : (int)$column['CHARACTER_MAXIMUM_LENGTH'];
            $type .= "(" . $length . ")";
        } elseif (in_array($lower, $scaled, true) && $column['NUMERIC_PRECISION'] !== null) {
            $type .= "(" . (int)$column['NUMERIC_PRECISION'] . "," . (int)$column['NUMERIC_SCALE'] . ")";
        }

        $line = "  " . sqldump_quote_ident($column['COLUMN_NAME']) . " " . $type;
        if ($column['IS_NULLABLE'] == 'NO') {
            $line .= " NOT NULL";
        }
        if ($column['COLUMN_DEFAULT'] !== null && $column['COLUMN_DEFAULT'] !== '') {
            $line .= " DEFAULT " . $column['COLUMN_DEFAULT'];
        }
        $lines[] = $line;
    }

    if (count($primary) > 0) {
        $quoted = array_map('sqldump_quote_ident', $primary);
        $lines[] = "  PRIMARY KEY (" . implode(", ", $quoted) . ")";
    }

    return "CREATE TABLE " . sqldump_quote_ident($tableName) . " (\n" . implode(",\n", $lines) . "\n);\n";
}

/* --------------------------- dump header --------------------------- */
$sqldump  = "-- ".$OrgNameOut." ".$SQLDumper."\n";
$sqldump .= "-- version ".$VerOut."\n";
$sqldump .= "-- ".$HomeOut."support/\n";
$sqldump .= "--\n";
$sqldump .= "-- Host: ".$Settings['sqlhost']."\n";
$sqldump .= "-- Generation Time: ".$GenTime."\n";
$sqldump .= "-- Server version: ".sql_server_info($SQLStat)."\n";
$sqldump .= "-- PHP Version: ".phpversion()."\n\n";
$sqldump .= "--\n";
$sqldump .= "-- Database: \"".$Settings['sqldb']."\"\n";
$sqldump .= "--\n\n";
$sqldump .= "-- --------------------------------------------------------\n\n";

/* ------------------------------- dump ------------------------------- */
$num = count($TableNames);
$melanie_p = 0;
while ($melanie_p < $num) {
    $tableName = $TableNames[$melanie_p];
    $createTable = BuildCreateTable($tableName);

    if ($createTable === null) {
        ++$melanie_p;
        continue;
    }

    $sqldump .= "--\n";
    $sqldump .= "-- Table structure for table \"".$tableName."\"\n";
    $sqldump .= "--\n\n";
    $sqldump .= $createTable."\n";

    $sqldump .= "--\n";
    $sqldump .= "-- Dumping data for table \"".$tableName."\"\n";
    $sqldump .= "--\n\n";

    // BUGFIX: the old GetAllRows() buffered every row of every table into
    // memory before emitting anything. Stream the rows instead.
    $tresult = sql_query("SELECT * FROM ".sqldump_quote_ident($tableName), $SQLStat);
    if ($tresult !== false) {
        while ($trow = sql_fetch_assoc($tresult)) {
            $sqldump .= sqldump_insert_row($tableName, $trow);
        }
        sql_free_result($tresult);
    }

    $sqldump .= "\n";
    if ($melanie_p < $num - 1) {
        $sqldump .= "-- --------------------------------------------------------\n\n";
    }
    ++$melanie_p;
}

echo sqldump_compress_output($sqldump);

fix_amp($Settings['use_gzip'], $GZipEncode['Type']);
