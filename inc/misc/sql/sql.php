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

    $FileInfo: sql.php - Last Update: 8/28/2024 SVN 1053 - Author: cooldude2k $
*/

$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "sql.php" || $File3Name == "/sql.php") {
    @header('Location: index.php');
    exit();
}

// Counters. Use $GLOBALS so these still work if sql.php is included from
// inside a function scope.
if (!isset($GLOBALS['NumQueriesArray']) || !is_array($GLOBALS['NumQueriesArray'])) {
    $GLOBALS['NumQueriesArray'] = array();
}
if (!isset($GLOBALS['NumPreQueriesArray']) || !is_array($GLOBALS['NumPreQueriesArray'])) {
    $GLOBALS['NumPreQueriesArray'] = array();
}
if (!isset($GLOBALS['NumQueries'])) {
    $GLOBALS['NumQueries'] = 0;
}
// BUGFIX: this used to test $NumQueries but assign $NumPreQueries.
if (!isset($GLOBALS['NumPreQueries'])) {
    $GLOBALS['NumPreQueries'] = 0;
}
if (!isset($GLOBALS['NumQueriesArray']['sql'])) {
    $GLOBALS['NumQueriesArray']['sql'] = 0;
}
if (!isset($GLOBALS['NumPreQueriesArray']['sql'])) {
    $GLOBALS['NumPreQueriesArray']['sql'] = 0;
}
if (!isset($GLOBALS['PDOResultBuffer']) || !is_array($GLOBALS['PDOResultBuffer'])) {
    $GLOBALS['PDOResultBuffer'] = array();
}

/* ------------------------------------------------------------------
   Shared helpers used by every driver so they all behave the same way
   ------------------------------------------------------------------ */

// Normalise the argument list of a *_func_query() call.
//
// All drivers now accept every one of these call styles:
//     func_query($sql)
//     func_query($sql, $link)
//     func_query($sql, $params)
//     func_query($sql, $params, $link)
//     func_query(array($sql, $params) [, $link])
//     func_query(array('sql' => ..., 'params' => ...) [, $link])
//
// Returns array($sql, $params, $link).
function sql_resolve_query_args($query, $params_or_link = null, $maybe_link = null)
{
    $params = array();
    $link = null;

    if ($maybe_link !== null) {
        $params = is_array($params_or_link) ? $params_or_link : array();
        $link = $maybe_link;
    } elseif (is_array($params_or_link)) {
        $params = $params_or_link;
    } elseif ($params_or_link !== null) {
        $link = $params_or_link;
    }

    if (is_array($query)) {
        if (isset($query['sql'])) {
            $sql = $query['sql'];
            if (isset($query['params']) && is_array($query['params'])) {
                $params = $query['params'];
            }
        } else {
            $sql = isset($query[0]) ? $query[0] : '';
            if (isset($query[1]) && is_array($query[1])) {
                $params = $query[1];
            }
        }
    } else {
        $sql = $query;
    }

    if (!is_array($params)) {
        $params = array();
    }

    return array($sql, array_values($params), $link);
}

// Quote a single value for inline interpolation. $escaper must return the
// escaped string *without* surrounding quotes.
function sql_quote_value($value, $escaper)
{
    if ($value === null) {
        return 'NULL';
    }
    if (is_bool($value)) {
        return $value ? '1' : '0';
    }
    if (is_int($value) || is_float($value)) {
        return (string)$value;
    }
    if ($escaper === null) {
        return "'" . str_replace("'", "''", (string)$value) . "'";
    }
    return "'" . call_user_func($escaper, (string)$value) . "'";
}

// Replace positional "?" placeholders with escaped literals. Used by the
// non-prepared drivers so they can still consume the array form returned by a
// prepared-style pre_query(). Question marks inside string literals are
// skipped.
function sql_bind_placeholders($sql, $params, $escaper)
{
    if (empty($params) || strpos($sql, '?') === false) {
        return $sql;
    }

    $params = array_values($params);
    $count = count($params);
    $index = 0;
    $out = '';
    $len = strlen($sql);
    $in_string = false;
    $quote_char = '';

    for ($i = 0; $i < $len; $i++) {
        $ch = $sql[$i];

        if ($in_string) {
            $out .= $ch;
            if ($ch === '\\' && $i + 1 < $len) {
                $out .= $sql[++$i];
                continue;
            }
            if ($ch === $quote_char) {
                $in_string = false;
            }
            continue;
        }

        if ($ch === "'" || $ch === '"') {
            $in_string = true;
            $quote_char = $ch;
            $out .= $ch;
            continue;
        }

        if ($ch === '?' && $index < $count) {
            $out .= sql_quote_value($params[$index++], $escaper);
            continue;
        }

        $out .= $ch;
    }

    return $out;
}

// _convert_var handles the SafeSQL placeholder types.
//
// BUGFIX: the drivers used to pass the whole query string as $placeholder,
// so every value fell through to the default branch and %i/%c/%l/%q/%n never
// did anything. $placeholder is now the individual specifier, and the escaping
// happens here (per type) instead of blindly escaping every value first.
function _convert_var($var, $placeholder = '%s', $escaper = null)
{
    $esc = function ($value) use ($escaper) {
        if ($value === null) {
            return '';
        }
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }
        if ($escaper === null) {
            return str_replace("'", "''", (string)$value);
        }
        return call_user_func($escaper, (string)$value);
    };

    switch ($placeholder) {
        case '%i':
        case '%I':
        case '%d':
            settype($var, 'integer');
            return $var;

        case '%f':
        case '%F':
            settype($var, 'float');
            return $var;

        case '%c':
            // Comma-separated list of integers
            settype($var, 'array');
            return implode(',', array_map('intval', $var));

        case '%l':
            // Comma-separated, no quotes
            settype($var, 'array');
            return implode(',', array_map($esc, $var));

        case '%q':
            // Quote-comma separated strings
            settype($var, 'array');
            return implode("','", array_map($esc, $var));

        case '%n':
            // NULL passthrough
            if ($var === null || $var === 'NULL') {
                return 'NULL';
            }
            return $esc($var);

        default:
            return $esc($var);
    }
}

// SafeSQL Lite: build a finished query string by escaping and interpolating.
// Used by the non-prepared drivers (mysqli, pgsql, sqlite3, cubrid).
function sql_safe_pre_query($query_string, $query_vars, $escaper = null)
{
    if ($query_vars === null) {
        $query_vars = array();
    }
    if (!is_array($query_vars)) {
        $query_vars = array($query_vars);
    }
    $query_vars = array_values($query_vars);

    // BUGFIX: get_magic_quotes_gpc() was removed in PHP 8.0; calling it
    // unconditionally was a fatal error.
    if (function_exists('get_magic_quotes_gpc') && @get_magic_quotes_gpc()) {
        $query_vars = array_map('stripslashes', $query_vars);
    }

    // Walk the placeholders in order, converting each value according to its
    // own specifier. "%%" is a literal percent and consumes no variable.
    //
    // A null value is written as a bare SQL NULL (dropping the surrounding
    // quotes if the template had any), so the interpolating drivers store the
    // same thing the prepared drivers bind. Previously a null bound to %i was
    // silently written as 0 and a null bound to '%s' became the empty string.
    $index = 0;
    $args = array();
    $short = false;

    $query_string = preg_replace_callback(
        "/%%|'%[iIfFsSdclqn]'|%[iIfFsSdclqn]/",
        function ($matches) use (&$index, &$args, &$short, $query_vars, $escaper) {
            $match = $matches[0];
            if ($match === '%%') {
                return '%%';
            }

            $quoted = ($match[0] === "'");
            $spec = $quoted ? substr($match, 1, -1) : $match;

            if (!array_key_exists($index, $query_vars)) {
                $short = true;
                $index++;
                return $match;
            }

            $value = $query_vars[$index];
            $index++;

            if ($value === null) {
                return 'NULL';
            }

            $args[] = _convert_var($value, $spec, $escaper);

            switch ($spec) {
                case '%i':
                case '%I':
                case '%d':
                    $converted = '%d';
                    break;
                case '%f':
                case '%F':
                    $converted = '%f';
                    break;
                default:
                    $converted = '%s';
            }

            return $quoted ? "'" . $converted . "'" : $converted;
        },
        $query_string
    );

    if ($short) {
        output_error("SQL Placeholder Error: Mismatch between placeholders ($index) and parameters (" . count($query_vars) . ").", E_USER_ERROR);
        return false;
    }

    array_unshift($args, $query_string);
    return call_user_func_array('sprintf', $args);
}

// Prepared-statement style pre_query: turn the SafeSQL placeholders into
// driver placeholders and hand back array($sql, $params).
//
// $style is 'qmark' (?) or 'dollar' ($1, $2, ...).
//
// BUGFIX: the old versions only replaced "'%s'" (quoted) and left bare "%s"
// alone, and they stripped NULL values out of the parameter list -- which
// guaranteed a placeholder/parameter mismatch on any query with a nullable
// column. Prepared statements can bind NULL, so nothing is filtered now.
function sql_prepared_pre_query($query_string, $query_vars = array(), $style = 'qmark')
{
    if ($query_vars === null) {
        $query_vars = array();
    }
    if (!is_array($query_vars)) {
        $query_vars = array($query_vars);
    }
    $query_vars = array_values($query_vars);

    $pattern = "/%%|'%[iIfFsSdclqn]'|%[iIfFsSdclqn]/";

    if ($style === 'dollar') {
        $position = 0;
        $query_string = preg_replace_callback($pattern, function ($match) use (&$position) {
            if ($match[0] === '%%') {
                return '%';
            }
            $position++;
            return '$' . $position;
        }, $query_string);
        $placeholder_count = $position;
    } else {
        $placeholder_count = 0;
        $query_string = preg_replace_callback($pattern, function ($match) use (&$placeholder_count) {
            if ($match[0] === '%%') {
                return '%';
            }
            $placeholder_count++;
            return '?';
        }, $query_string);
    }

    $params_count = count($query_vars);

    if ($placeholder_count !== $params_count) {
        output_error("SQL Placeholder Error: Mismatch between placeholders ($placeholder_count) and parameters ($params_count).", E_USER_ERROR);
        return false;
    }

    return array($query_string, $query_vars);
}

// Drop bracketed [ ... ] sections whose placeholders have empty values.
//
// BUGFIX: the old implementation searched $query_vars for the *placeholder
// text* (array_search('%i', $query_vars)) which never matched anything, so the
// function silently did nothing. It now maps bracketed placeholders back to
// their positional value.
function handle_conditional_parts(&$query_string, &$query_vars)
{
    if (!is_array($query_vars)) {
        $query_vars = array();
    }
    if (strpos($query_string, '[') === false) {
        return;
    }

    $values = array_values($query_vars);
    $ph_pattern = '/%%|%[iIfFsSdclqn]/';

    // Number the placeholders across the whole string first.
    $offset_map = array();
    $index = 0;
    if (preg_match_all($ph_pattern, $query_string, $m, PREG_OFFSET_CAPTURE)) {
        foreach ($m[0] as $match) {
            if ($match[0] === '%%') {
                continue;
            }
            $offset_map[$match[1]] = $index++;
        }
    }

    if (!preg_match_all('/\[(.*?)\]/s', $query_string, $matches, PREG_OFFSET_CAPTURE)) {
        return;
    }

    $drop = array();
    foreach ($matches[1] as $key => $inner) {
        $start = $matches[0][$key][1];
        $end = $start + strlen($matches[0][$key][0]);
        $keep = true;

        foreach ($offset_map as $offset => $position) {
            if ($offset < $start || $offset >= $end) {
                continue;
            }
            if (!isset($values[$position]) || $values[$position] === null || $values[$position] === '') {
                $keep = false;
                break;
            }
        }

        $drop[$matches[0][$key][0]] = $keep ? $inner[0] : '';
    }

    foreach ($drop as $needle => $replacement) {
        $query_string = str_replace($needle, $replacement, $query_string);
    }
}

/* ---------------------------------------------------------------
   Shared PDO result helpers.

   PDOStatement::rowCount() is not reliable for SELECT statements on
   most drivers, and PDOStatement has no seek. These helpers buffer the
   rows once so num_rows()/result() behave like the native drivers and
   don't consume the cursor the fetch_* functions need.
   --------------------------------------------------------------- */

function sql_pdo_key($stmt)
{
    return function_exists('spl_object_id') ? spl_object_id($stmt) : spl_object_hash($stmt);
}

function sql_pdo_is_buffered($stmt)
{
    return ($stmt instanceof PDOStatement) && isset($GLOBALS['PDOResultBuffer'][sql_pdo_key($stmt)]);
}

function sql_pdo_buffer($stmt)
{
    if (!($stmt instanceof PDOStatement)) {
        return null;
    }
    $key = sql_pdo_key($stmt);
    if (!isset($GLOBALS['PDOResultBuffer'][$key])) {
        $rows = array();
        try {
            if ($stmt->columnCount() > 0) {
                $rows = $stmt->fetchAll(PDO::FETCH_BOTH);
            }
        } catch (PDOException $e) {
            $rows = array();
        }
        $GLOBALS['PDOResultBuffer'][$key] = array('rows' => $rows, 'pos' => 0);
    }
    return $GLOBALS['PDOResultBuffer'][$key];
}

function sql_pdo_filter_row($row, $mode)
{
    if (!is_array($row)) {
        return $row;
    }
    if ($mode == PDO::FETCH_ASSOC) {
        $out = array();
        foreach ($row as $key => $value) {
            if (!is_int($key)) {
                $out[$key] = $value;
            }
        }
        return $out;
    }
    if ($mode == PDO::FETCH_NUM) {
        $out = array();
        foreach ($row as $key => $value) {
            if (is_int($key)) {
                $out[$key] = $value;
            }
        }
        return $out;
    }
    if ($mode == PDO::FETCH_COLUMN) {
        return isset($row[0]) ? $row[0] : null;
    }
    return $row;
}

function sql_pdo_num_rows($stmt)
{
    if (!($stmt instanceof PDOStatement)) {
        return false;
    }
    if ($stmt->columnCount() === 0) {
        return $stmt->rowCount();
    }
    $buffer = sql_pdo_buffer($stmt);
    return count($buffer['rows']);
}

function sql_pdo_fetch($stmt, $mode = null)
{
    if (!($stmt instanceof PDOStatement)) {
        return false;
    }
    if ($mode === null) {
        $mode = PDO::FETCH_BOTH;
    }

    if (sql_pdo_is_buffered($stmt)) {
        $key = sql_pdo_key($stmt);
        $pos = $GLOBALS['PDOResultBuffer'][$key]['pos'];
        if (!isset($GLOBALS['PDOResultBuffer'][$key]['rows'][$pos])) {
            return false;
        }
        $GLOBALS['PDOResultBuffer'][$key]['pos'] = $pos + 1;
        return sql_pdo_filter_row($GLOBALS['PDOResultBuffer'][$key]['rows'][$pos], $mode);
    }

    try {
        $row = $stmt->fetch($mode);
    } catch (PDOException $e) {
        return false;
    }
    return ($row === null) ? false : $row;
}

function sql_pdo_result($stmt, $row = 0, $field = 0)
{
    if (!($stmt instanceof PDOStatement)) {
        return false;
    }
    $buffer = sql_pdo_buffer($stmt);
    if (!isset($buffer['rows'][$row])) {
        return null;
    }
    $data = $buffer['rows'][$row];
    return isset($data[$field]) ? $data[$field] : null;
}

function sql_pdo_free($stmt)
{
    if (!($stmt instanceof PDOStatement)) {
        return true;
    }
    unset($GLOBALS['PDOResultBuffer'][sql_pdo_key($stmt)]);
    try {
        $stmt->closeCursor();
    } catch (PDOException $e) {
        // Nothing useful to do here.
    }
    return true;
}

// Bind a value on a PDOStatement using a sensible PDO type.
function sql_pdo_bind_value($stmt, $key, $value)
{
    if (is_int($value)) {
        return $stmt->bindValue($key, $value, PDO::PARAM_INT);
    }
    if (is_bool($value)) {
        return $stmt->bindValue($key, $value, PDO::PARAM_BOOL);
    }
    if (is_null($value)) {
        return $stmt->bindValue($key, $value, PDO::PARAM_NULL);
    }
    if (is_float($value)) {
        return $stmt->bindValue($key, (string)$value, PDO::PARAM_STR);
    }
    return $stmt->bindValue($key, $value, PDO::PARAM_STR);
}

// Quote an identifier (table/column name) for a given quoting style.
function sql_quote_identifier($name, $style = 'double')
{
    switch ($style) {
        case 'backtick':
            return '`' . str_replace('`', '``', $name) . '`';
        case 'bracket':
            return '[' . str_replace(']', ']]', $name) . ']';
        default:
            return '"' . str_replace('"', '""', $name) . '"';
    }
}

/* --------------------------- driver loading --------------------------- */

if (file_exists($SettDir['sql'] . "pdo_mysql.php") && extension_loaded("PDO") && extension_loaded("pdo_mysql")) {
    require($SettDir['sql'] . "pdo_mysql.php");
}
if (file_exists($SettDir['sql'] . "mysqli.php") && function_exists("mysqli_connect")) {
    require($SettDir['sql'] . "mysqli.php");
}
if (file_exists($SettDir['sql'] . "mysqli_prepare.php") && function_exists("mysqli_connect")) {
    require($SettDir['sql'] . "mysqli_prepare.php");
}
if (file_exists($SettDir['sql'] . "pgsql.php") && function_exists("pg_connect")) {
    require($SettDir['sql'] . "pgsql.php");
}
if (file_exists($SettDir['sql'] . "pgsql_prepare.php") && function_exists("pg_connect")) {
    require($SettDir['sql'] . "pgsql_prepare.php");
}
if (file_exists($SettDir['sql'] . "pdo_pgsql.php") && extension_loaded("PDO") && extension_loaded("pdo_pgsql")) {
    require($SettDir['sql'] . "pdo_pgsql.php");
}
if (file_exists($SettDir['sql'] . "sqlite3.php") && class_exists('SQLite3')) {
    require($SettDir['sql'] . "sqlite3.php");
}
if (file_exists($SettDir['sql'] . "sqlite3_prepare.php") && class_exists('SQLite3')) {
    require($SettDir['sql'] . "sqlite3_prepare.php");
}
if (file_exists($SettDir['sql'] . "pdo_sqlite3.php") && extension_loaded("PDO") && extension_loaded("pdo_sqlite")) {
    require($SettDir['sql'] . "pdo_sqlite3.php");
}
if (file_exists($SettDir['sql'] . "cubrid.php") && function_exists("cubrid_connect")) {
    require($SettDir['sql'] . "cubrid.php");
}
if (file_exists($SettDir['sql'] . "cubrid_prepare.php") && function_exists("cubrid_connect")) {
    require($SettDir['sql'] . "cubrid_prepare.php");
}
if (file_exists($SettDir['sql'] . "pdo_cubrid.php") && extension_loaded("PDO") && extension_loaded("pdo_cubrid")) {
    require($SettDir['sql'] . "pdo_cubrid.php");
}
if (file_exists($SettDir['sql'] . "pdo_sqlsrv.php") && extension_loaded("PDO") && extension_loaded("pdo_sqlsrv")) {
    require($SettDir['sql'] . "pdo_sqlsrv.php");
}
if (file_exists($SettDir['sql'] . "sqlsrv_prepare.php") && function_exists("sqlsrv_connect")) {
    require($SettDir['sql'] . "sqlsrv_prepare.php");
}

// Helper function to map SQL library to its function prefix
function get_sql_function_prefix($sqllib)
{
    $prefixes = array(
        'mysqli_prepare' => 'mysqli_prepare_func',
        'mysqli' => 'mysqli_func',
        'pdo_mysql' => 'pdo_mysql_func',
        'pgsql' => 'pgsql_func',
        'pgsql_prepare' => 'pgsql_prepare_func',
        'pdo_pgsql' => 'pdo_pgsql_func',
        'sqlite3_prepare' => 'sqlite3_prepare_func',
        'sqlite3' => 'sqlite3_func',
        'pdo_sqlite3' => 'pdo_sqlite3_func',
        'cubrid' => 'cubrid_func',
        'cubrid_prepare' => 'cubrid_prepare_func',
        'pdo_cubrid' => 'pdo_cubrid_func',
        'sqlsrv_prepare' => 'sqlsrv_prepare_func',
        'pdo_sqlsrv' => 'pdo_sqlsrv_func'
    );
    return isset($prefixes[$sqllib]) ? $prefixes[$sqllib] : null;
}

// Function to dynamically call the appropriate function based on $sqllib
function call_sql_function($func, $sqllib = null, ...$params)
{
    if ($sqllib === null) {
        global $Settings;
        $sqllib = isset($Settings['sqltype']) ? $Settings['sqltype'] : null;
    }
    $prefix = get_sql_function_prefix($sqllib);
    if ($prefix) {
        $functionName = $prefix . '_' . $func;
        if (function_exists($functionName)) {
            return $functionName(...$params);
        }
        error_log("SQL function $functionName does not exist for $sqllib");
        throw new Exception("SQL function $functionName not found for library $sqllib.");
    }
    throw new Exception("Invalid SQL library: " . (string)$sqllib);
}

// Is a given driver available?
function sql_function_exists($func, $sqllib = null)
{
    if ($sqllib === null) {
        global $Settings;
        $sqllib = isset($Settings['sqltype']) ? $Settings['sqltype'] : null;
    }
    $prefix = get_sql_function_prefix($sqllib);
    return $prefix ? function_exists($prefix . '_' . $func) : false;
}

/* --------------------------- wrapper functions --------------------------- */

function sql_error($link = null, $sqllib = null)
{
    return call_sql_function('error', $sqllib, $link);
}

function sql_errno($link = null, $sqllib = null)
{
    return call_sql_function('errno', $sqllib, $link);
}

function sql_errorno($link = null, $sqllib = null)
{
    return call_sql_function('errorno', $sqllib, $link);
}

// $link may also be an array of parameters, or you can pass both:
//     sql_query($sql, $params, $sqllib, $link)
function sql_query($query, $link = null, $sqllib = null, $params = null)
{
    $returnval = ($params === null)
        ? call_sql_function('query', $sqllib, $query, $link)
        : call_sql_function('query', $sqllib, $query, $params, $link);

    if ($returnval !== false) {
        ++$GLOBALS['NumQueries'];
        ++$GLOBALS['NumQueriesArray']['sql'];
    }
    return $returnval;
}

function sql_num_rows($result, $sqllib = null)
{
    return call_sql_function('num_rows', $sqllib, $result);
}

function sql_connect_db($server, $username, $password, $database = null, $new_link = false, $sqllib = null)
{
    return call_sql_function('connect_db', $sqllib, $server, $username, $password, $database, $new_link);
}

function sql_result($result, $row, $field = 0, $sqllib = null)
{
    return call_sql_function('result', $sqllib, $result, $row, $field);
}

function sql_disconnect_db($link = null, $sqllib = null)
{
    return call_sql_function('disconnect_db', $sqllib, $link);
}

function sql_free_result($result, $sqllib = null)
{
    return call_sql_function('free_result', $sqllib, $result);
}

function sql_fetch_array($result, $result_type = null, $sqllib = null)
{
    return call_sql_function('fetch_array', $sqllib, $result, $result_type);
}

function sql_fetch_assoc($result, $sqllib = null)
{
    return call_sql_function('fetch_assoc', $sqllib, $result);
}

function sql_fetch_row($result, $sqllib = null)
{
    return call_sql_function('fetch_row', $sqllib, $result);
}

function sql_server_info($link = null, $sqllib = null)
{
    return call_sql_function('server_info', $sqllib, $link);
}

function sql_client_info($link = null, $sqllib = null)
{
    return call_sql_function('client_info', $sqllib, $link);
}

function sql_escape_string($string, $link = null, $sqllib = null)
{
    return call_sql_function('escape_string', $sqllib, $string, $link);
}

function sql_pre_query($query_string, $query_vars = array(), $sqllib = null)
{
    $returnval = call_sql_function('pre_query', $sqllib, $query_string, $query_vars);
    if ($returnval !== false) {
        ++$GLOBALS['NumPreQueries'];
        ++$GLOBALS['NumPreQueriesArray']['sql'];
    }
    return $returnval;
}

function sql_set_charset($charset, $link = null, $sqllib = null)
{
    return call_sql_function('set_charset', $sqllib, $charset, $link);
}

function sql_get_next_id($tablepre, $table, $link = null, $sqllib = null)
{
    return call_sql_function('get_next_id', $sqllib, $tablepre, $table, $link);
}

function sql_get_num_rows($tablepre, $table, $link = null, $sqllib = null)
{
    return call_sql_function('get_num_rows', $sqllib, $tablepre, $table, $link);
}

function sql_count_rows($query, $link = null, $countname = "cnt", $sqllib = null)
{
    return call_sql_function('count_rows', $sqllib, $query, $link, $countname);
}

function sql_count_rows_alt($query, $link = null, $sqllib = null)
{
    return call_sql_function('count_rows_alt', $sqllib, $query, $link);
}
