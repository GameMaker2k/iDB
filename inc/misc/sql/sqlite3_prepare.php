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

    $FileInfo: sqlite3_prepare.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/

$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "sqlite3_prepare.php" || $File3Name == "/sqlite3_prepare.php") {
    @header('Location: index.php');
    exit();
}

if (!isset($GLOBALS['NumPreQueriesArray']['sqlite3_prepare'])) {
    $GLOBALS['NumPreQueriesArray']['sqlite3_prepare'] = 0;
}
if (!isset($GLOBALS['NumQueriesArray']['sqlite3_prepare'])) {
    $GLOBALS['NumQueriesArray']['sqlite3_prepare'] = 0;
}
// Keeps SQLite3Stmt objects alive for as long as their SQLite3Result is in use.
if (!isset($GLOBALS['SQLite3StmtKeep']) || !is_array($GLOBALS['SQLite3StmtKeep'])) {
    $GLOBALS['SQLite3StmtKeep'] = array();
}

function sqlite3_prepare_func_conn($link = null)
{
    if ($link instanceof SQLite3) {
        return $link;
    }
    if (isset($GLOBALS['SQLStat']) && $GLOBALS['SQLStat'] instanceof SQLite3) {
        return $GLOBALS['SQLStat'];
    }
    return null;
}

// SQLite Functions
function sqlite3_prepare_func_error($link = null)
{
    $connection = sqlite3_prepare_func_conn($link);
    return $connection ? $connection->lastErrorMsg() : "No valid SQLite3 connection.";
}

function sqlite3_prepare_func_errno($link = null)
{
    $connection = sqlite3_prepare_func_conn($link);
    return $connection ? $connection->lastErrorCode() : 0;
}

function sqlite3_prepare_func_errorno($link = null)
{
    $connection = sqlite3_prepare_func_conn($link);
    return $connection
        ? $connection->lastErrorCode() . ": " . $connection->lastErrorMsg()
        : "No valid SQLite3 connection.";
}

function sqlite3_prepare_func_query($query, $params_or_link = null, $maybe_link = null)
{
    list($sql, $params, $link) = sql_resolve_query_args($query, $params_or_link, $maybe_link);

    $db = sqlite3_prepare_func_conn($link);
    if (!$db) {
        output_error("SQL Error: Invalid SQLite3 connection.", E_USER_ERROR);
        return false;
    }

    if (!is_string($sql) || trim($sql) === '') {
        output_error("SQL Error: Query is empty.", E_USER_ERROR);
        return false;
    }

    $stmt = @$db->prepare($sql);
    if (!$stmt) {
        output_error("SQL Error (Prepare): " . sqlite3_prepare_func_error($db), E_USER_ERROR);
        return false;
    }

    foreach ($params as $key => $value) {
        $paramKey = is_int($key) ? $key + 1 : ':' . $key;
        if (is_int($value)) {
            $stmt->bindValue($paramKey, $value, SQLITE3_INTEGER);
        } elseif (is_float($value)) {
            $stmt->bindValue($paramKey, $value, SQLITE3_FLOAT);
        } elseif (is_bool($value)) {
            $stmt->bindValue($paramKey, $value ? 1 : 0, SQLITE3_INTEGER);
        } elseif (is_null($value)) {
            $stmt->bindValue($paramKey, null, SQLITE3_NULL);
        } else {
            $stmt->bindValue($paramKey, (string)$value, SQLITE3_TEXT);
        }
    }

    $result = @$stmt->execute();
    if ($result === false) {
        output_error("SQL Error (Execution): " . sqlite3_prepare_func_error($db), E_USER_ERROR);
        $stmt->close();
        return false;
    }

    // BUGFIX: the SQLite3Stmt was left to go out of scope, which can invalidate
    // the SQLite3Result it produced. Hold a reference until free_result().
    $key = function_exists('spl_object_id') ? spl_object_id($result) : spl_object_hash($result);
    $GLOBALS['SQLite3StmtKeep'][$key] = $stmt;

    ++$GLOBALS['NumQueriesArray']['sqlite3_prepare'];
    return $result;
}

// Fetch Number of Rows
function sqlite3_prepare_func_num_rows($result)
{
    if (!($result instanceof SQLite3Result)) {
        output_error("SQL Error: Invalid result set.", E_USER_ERROR);
        return false;
    }

    $num = 0;
    @$result->reset();
    while (@$result->fetchArray(SQLITE3_NUM)) {
        $num++;
    }
    @$result->reset();

    return $num;
}

// Connect to SQLite database
function sqlite3_prepare_func_connect_db($server, $username, $password, $database = null, $new_link = false)
{
    if ($database === null) {
        return true;
    }

    try {
        $link = new SQLite3($database, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
    } catch (Exception $e) {
        output_error("Not connected: " . $e->getMessage(), E_USER_ERROR);
        return false;
    }

    return $link;
}

function sqlite3_prepare_func_disconnect_db($link = null)
{
    $connection = sqlite3_prepare_func_conn($link);
    return $connection ? $connection->close() : false;
}

// Query Results
function sqlite3_prepare_func_result($result, $row, $field = 0)
{
    if (!($result instanceof SQLite3Result)) {
        output_error("SQL Error: Invalid result set.", E_USER_ERROR);
        return null;
    }

    $num = 0;
    @$result->reset();

    while ($num < $row) {
        if (@$result->fetchArray(SQLITE3_NUM) === false) {
            return null;
        }
        $num++;
    }

    $trow = @$result->fetchArray(SQLITE3_BOTH);
    if (!is_array($trow)) {
        return null;
    }

    return isset($trow[$field]) ? $trow[$field] : null;
}

// Free Results
function sqlite3_prepare_func_free_result($result)
{
    if ($result instanceof SQLite3Result) {
        $key = function_exists('spl_object_id') ? spl_object_id($result) : spl_object_hash($result);
        @$result->finalize();
        if (isset($GLOBALS['SQLite3StmtKeep'][$key])) {
            @$GLOBALS['SQLite3StmtKeep'][$key]->close();
            unset($GLOBALS['SQLite3StmtKeep'][$key]);
        }
    }
    return true;
}

// Fetch Results to Array
// BUGFIX: the null default fell back to CUBRID_BOTH.
function sqlite3_prepare_func_fetch_array($result, $result_type = SQLITE3_BOTH)
{
    if ($result_type === null) {
        $result_type = SQLITE3_BOTH;
    }
    return ($result instanceof SQLite3Result) ? $result->fetchArray($result_type) : false;
}

function sqlite3_prepare_func_fetch_assoc($result)
{
    return ($result instanceof SQLite3Result) ? $result->fetchArray(SQLITE3_ASSOC) : false;
}

function sqlite3_prepare_func_fetch_row($result)
{
    return ($result instanceof SQLite3Result) ? $result->fetchArray(SQLITE3_NUM) : false;
}

// Get Server Info
function sqlite3_prepare_func_server_info($link = null)
{
    $version = SQLite3::version();
    return $version['versionString'];
}

// Get Client Info
function sqlite3_prepare_func_client_info($link = null)
{
    $version = SQLite3::version();
    return $version['versionString'];
}

function sqlite3_prepare_func_escape_string($string, $link = null)
{
    if ($string === null) {
        return null;
    }
    return SQLite3::escapeString((string)$string);
}

// SafeSQL Lite Source Code by Cool Dude 2k
function sqlite3_prepare_func_pre_query($query_string, $query_vars = array())
{
    $result = sql_prepared_pre_query($query_string, $query_vars, 'qmark');
    if ($result === false) {
        return false;
    }

    ++$GLOBALS['NumPreQueriesArray']['sqlite3_prepare'];
    return $result;
}

// Set Charset (dummy for SQLite3)
function sqlite3_prepare_func_set_charset($charset, $link = null)
{
    return true;
}

// Get next id after insert
function sqlite3_prepare_func_get_next_id($tablepre, $table, $link = null)
{
    $db = sqlite3_prepare_func_conn($link);
    return $db ? $db->lastInsertRowID() : false;
}

// Get number of rows for table (was missing entirely)
function sqlite3_prepare_func_get_num_rows($tablepre, $table, $link = null)
{
    $db = sqlite3_prepare_func_conn($link);
    if (!$db) {
        return false;
    }

    $sql = "SELECT COUNT(*) AS cnt FROM " . sql_quote_identifier($tablepre . $table, 'double');
    $result = sqlite3_prepare_func_query($sql, array(), $db);
    if ($result === false) {
        return false;
    }

    $row = sqlite3_prepare_func_fetch_assoc($result);
    sqlite3_prepare_func_free_result($result);

    return (is_array($row) && isset($row['cnt'])) ? (int)$row['cnt'] : 0;
}

function sqlite3_prepare_func_count_rows($query, $link = null, $countname = "cnt")
{
    $result = sqlite3_prepare_func_query($query, $link);
    if ($result === false) {
        return false;
    }

    $row = sqlite3_prepare_func_fetch_assoc($result);
    $count = (is_array($row) && isset($row[$countname])) ? $row[$countname] : 0;

    sqlite3_prepare_func_free_result($result);
    return $count;
}

function sqlite3_prepare_func_count_rows_alt($query, $link = null)
{
    $result = sqlite3_prepare_func_query($query, $link);
    if ($result === false) {
        return false;
    }

    $row = sqlite3_prepare_func_fetch_assoc($result);
    $count = is_array($row) ? reset($row) : 0;

    sqlite3_prepare_func_free_result($result);
    return $count;
}
