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

    $FileInfo: pgsql.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "pgsql.php" || $File3Name == "/pgsql.php") {
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
// BUGFIX: the guard only allowed "pgsql", locking out pgsql_prepare and pdo_pgsql.
if ($Settings['sqltype'] != "pgsql" && $Settings['sqltype'] != "pgsql_prepare" && $Settings['sqltype'] != "pdo_pgsql") {
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
$result = sql_query(sql_pre_query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' AND table_type = 'BASE TABLE' ORDER BY table_name", array()), $SQLStat);
if (!$result) {
    echo "DB Error, could not list tables\n";
    echo 'PostgreSQL Error: ' . sql_error($SQLStat);
    exit;
}

$TableNames = array();
while ($row = sql_fetch_assoc($result)) {
    if (isset($row['table_name']) && in_array($row['table_name'], $TableChCk, true)) {
        $TableNames[] = $row['table_name'];
    }
}
sql_free_result($result);

// Build a CREATE TABLE statement from information_schema.
//
// BUGFIX: the old builder walked the column list using a row count from a
// separate COUNT(*) query, marked every column NOT NULL regardless of
// is_nullable, emitted a bare " DEFAULT " when a column had no default, reset
// $UniKeyRow on each iteration (so only the last UNIQUE column survived), and
// called pg_num_rows()/pg_fetch_assoc() directly -- bypassing the abstraction
// and breaking under pgsql_prepare and pdo_pgsql.
function BuildCreateTable($tableName)
{
    global $SQLStat;

    $result = sql_query(sql_pre_query(
        "SELECT column_name, udt_name, character_maximum_length, numeric_precision, numeric_scale, is_nullable, column_default
         FROM information_schema.columns
         WHERE table_schema = 'public' AND table_name = '%s'
         ORDER BY ordinal_position",
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

    // One query for every key constraint on the table.
    $primary = array();
    $unique = array();
    $constraints = sql_query(sql_pre_query(
        "SELECT kcu.column_name, tc.constraint_type
         FROM information_schema.table_constraints tc
         JOIN information_schema.key_column_usage kcu
           ON tc.constraint_name = kcu.constraint_name
          AND tc.table_schema = kcu.table_schema
         WHERE tc.table_schema = 'public' AND tc.table_name = '%s'
           AND tc.constraint_type IN ('PRIMARY KEY', 'UNIQUE')
         ORDER BY kcu.ordinal_position",
        array($tableName)
    ), $SQLStat);

    if ($constraints !== false) {
        while ($row = sql_fetch_assoc($constraints)) {
            if ($row['constraint_type'] == 'PRIMARY KEY') {
                $primary[] = $row['column_name'];
            } elseif (!in_array($row['column_name'], $unique, true)) {
                $unique[] = $row['column_name'];
            }
        }
        sql_free_result($constraints);
    }

    $lines = array();
    foreach ($columns as $column) {
        $type = $column['udt_name'];
        $default = ($column['column_default'] === null) ? '' : $column['column_default'];

        // Strip the ::type cast PostgreSQL adds to defaults.
        $cast = explode("::", $default);
        if (count($cast) > 1) {
            $default = $cast[0];
        }

        // A nextval() default means the column is a serial.
        if ($default !== '' && preg_match("/nextval\(.*_seq/i", $default)) {
            $type = ($type == 'int8') ? 'BIGSERIAL' : 'SERIAL';
            $default = '';
        } elseif ($type == 'varchar' && $column['character_maximum_length'] !== null) {
            $type .= "(" . $column['character_maximum_length'] . ")";
        } elseif ($type == 'numeric' && $column['numeric_precision'] !== null) {
            $type .= "(" . $column['numeric_precision'] . "," . (int)$column['numeric_scale'] . ")";
        }

        if ($type == 'text') {
            $default = '';
        }

        $line = "  " . sqldump_quote_ident($column['column_name']) . " " . $type;
        if ($column['is_nullable'] == 'NO' && strpos($type, 'SERIAL') === false) {
            $line .= " NOT NULL";
        }
        if ($default !== '') {
            $line .= " DEFAULT " . $default;
        }
        $lines[] = $line;
    }

    if (count($primary) > 0) {
        $quoted = array_map('sqldump_quote_ident', $primary);
        $lines[] = "  PRIMARY KEY (" . implode(", ", $quoted) . ")";
    }
    foreach ($unique as $column) {
        if (in_array($column, $primary, true)) {
            continue;
        }
        $lines[] = "  UNIQUE (" . sqldump_quote_ident($column) . ")";
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
    $sqldump .= "DROP TABLE IF EXISTS ".sqldump_quote_ident($tableName).";\n";
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
