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

    $FileInfo: pdo_mysql.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/

$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "pdo_mysql.php" || $File3Name == "/pdo_mysql.php") {
    @header('Location: index.php');
    exit();
}

if (!isset($GLOBALS['NumPreQueriesArray']['pdo_mysql'])) {
    $GLOBALS['NumPreQueriesArray']['pdo_mysql'] = 0;
}
if (!isset($GLOBALS['NumQueriesArray']['pdo_mysql'])) {
    $GLOBALS['NumQueriesArray']['pdo_mysql'] = 0;
}

function pdo_mysql_func_conn($link = null)
{
    if ($link instanceof PDO) {
        return $link;
    }
    if (isset($GLOBALS['SQLStat']) && $GLOBALS['SQLStat'] instanceof PDO) {
        return $GLOBALS['SQLStat'];
    }
    return null;
}

// MySQL Error handling functions
// BUGFIX: errorInfo() returns an array; these used to return it directly (and
// errorno() concatenated it to a string, producing "Array").
function pdo_mysql_func_error($link = null)
{
    $pdo = pdo_mysql_func_conn($link);
    if (!$pdo) {
        return "No valid PDO connection.";
    }
    $info = $pdo->errorInfo();
    return isset($info[2]) ? (string)$info[2] : "";
}

function pdo_mysql_func_errno($link = null)
{
    $pdo = pdo_mysql_func_conn($link);
    if (!$pdo) {
        return 0;
    }
    $code = $pdo->errorCode();
    return ($code === null) ? 0 : $code;
}

function pdo_mysql_func_errorno($link = null)
{
    $pdo = pdo_mysql_func_conn($link);
    if (!$pdo) {
        return "No valid PDO connection.";
    }
    $code = pdo_mysql_func_errno($pdo);
    $message = pdo_mysql_func_error($pdo);
    return ($message === "" && ($code === 0 || $code === '00000')) ? "" : "$code: $message";
}

function pdo_mysql_func_query($query, $params_or_link = null, $maybe_link = null)
{
    list($sql, $params, $link) = sql_resolve_query_args($query, $params_or_link, $maybe_link);

    $pdo = pdo_mysql_func_conn($link);
    if (!$pdo) {
        output_error("SQL Error: No valid PDO connection.", E_USER_ERROR);
        return false;
    }

    if (!is_string($sql) || trim($sql) === '') {
        output_error("SQL Error: Query is empty.", E_USER_ERROR);
        return false;
    }

    // BUGFIX: with ERRMODE_EXCEPTION set at connect time, prepare()/execute()
    // throw instead of returning false, so the old error handling never ran.
    try {
        if (count($params) > 0) {
            $stmt = $pdo->prepare($sql);
            if ($stmt === false) {
                output_error("SQL Error: " . pdo_mysql_func_error($pdo), E_USER_ERROR);
                return false;
            }

            foreach ($params as $key => $value) {
                sql_pdo_bind_value($stmt, is_int($key) ? $key + 1 : $key, $value);
            }

            if ($stmt->execute() === false) {
                output_error("SQL Error: " . pdo_mysql_func_error($pdo), E_USER_ERROR);
                return false;
            }

            ++$GLOBALS['NumQueriesArray']['pdo_mysql'];
            return $stmt;
        }

        $result = $pdo->query($sql);
        if ($result === false) {
            output_error("SQL Error: " . pdo_mysql_func_error($pdo), E_USER_ERROR);
            return false;
        }

        ++$GLOBALS['NumQueriesArray']['pdo_mysql'];
        return $result;
    } catch (PDOException $e) {
        output_error("SQL Error: " . $e->getMessage(), E_USER_ERROR);
        return false;
    }
}

// Fetch number of rows for SELECT queries
function pdo_mysql_func_num_rows($result)
{
    return sql_pdo_num_rows($result);
}

// Connect to MySQL database using PDO and set SQL modes
function pdo_mysql_func_connect_db($server, $username, $password, $database = null, $new_link = false)
{
    $myport = null;
    $hostex = explode(":", $server);
    if (isset($hostex[1]) && is_numeric($hostex[1])) {
        $server = $hostex[0];
        $myport = (int)$hostex[1];
    } elseif (isset($hostex[1])) {
        $server = $hostex[0];
    }

    $dsn = "mysql:host=$server";
    if ($myport !== null) {
        $dsn .= ";port=$myport";
    }
    if ($database !== null) {
        $dsn .= ";dbname=$database";
    }

    try {
        $link = new PDO($dsn, $username, $password, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => (bool)$new_link
        ));

        $sqlModes = "ANSI,ANSI_QUOTES,TRADITIONAL,STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION,NO_AUTO_VALUE_ON_ZERO";
        $link->exec("SET SESSION SQL_MODE='$sqlModes';");

        $GLOBALS['SQLStat'] = $link;
        return $link;
    } catch (PDOException $e) {
        output_error("Connection failed: " . $e->getMessage(), E_USER_ERROR);
        return false;
    }
}

// BUGFIX: this tested for PDOStatement, so passing the actual PDO connection
// (what connect_db returns) always fell through and returned false.
function pdo_mysql_func_disconnect_db($link = null)
{
    if ($link instanceof PDOStatement) {
        return sql_pdo_free($link);
    }

    if ($link instanceof PDO) {
        if (isset($GLOBALS['SQLStat']) && $GLOBALS['SQLStat'] === $link) {
            $GLOBALS['SQLStat'] = null;
        }
        return true;
    }

    if ($link === null && isset($GLOBALS['SQLStat'])) {
        $GLOBALS['SQLStat'] = null;
        return true;
    }

    return false;
}

// Query Results
function pdo_mysql_func_result($result, $row = 0, $field = 0)
{
    return sql_pdo_result($result, $row, $field);
}

// Free Results
function pdo_mysql_func_free_result($result)
{
    return sql_pdo_free($result);
}

// Fetch Results to Array
// BUGFIX: the null default fell back to CUBRID_BOTH.
function pdo_mysql_func_fetch_array($result, $result_type = PDO::FETCH_BOTH)
{
    if ($result_type === null) {
        $result_type = PDO::FETCH_BOTH;
    }
    return sql_pdo_fetch($result, $result_type);
}

function pdo_mysql_func_fetch_assoc($result)
{
    return sql_pdo_fetch($result, PDO::FETCH_ASSOC);
}

function pdo_mysql_func_fetch_row($result)
{
    return sql_pdo_fetch($result, PDO::FETCH_NUM);
}

// Get Server Info
function pdo_mysql_func_server_info($link = null)
{
    $pdo = pdo_mysql_func_conn($link);
    if (!$pdo) {
        return false;
    }
    try {
        return $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
    } catch (PDOException $e) {
        return false;
    }
}

// Get Client Info
function pdo_mysql_func_client_info($link = null)
{
    $pdo = pdo_mysql_func_conn($link);
    if (!$pdo) {
        return false;
    }
    try {
        return $pdo->getAttribute(PDO::ATTR_CLIENT_VERSION);
    } catch (PDOException $e) {
        return false;
    }
}

// Escape String
function pdo_mysql_func_escape_string($string, $link = null)
{
    if ($string === null) {
        return null;
    }
    $pdo = pdo_mysql_func_conn($link);
    if (!$pdo) {
        return false;
    }
    return $pdo->quote((string)$string);
}

// Pre-process Query for MySQL
function pdo_mysql_func_pre_query($query_string, $query_vars = array())
{
    $result = sql_prepared_pre_query($query_string, $query_vars, 'qmark');
    if ($result === false) {
        return false;
    }

    ++$GLOBALS['NumPreQueriesArray']['pdo_mysql'];
    return $result;
}

// Set Charset
// BUGFIX: this used to hard-fail when no PDO instance was passed, instead of
// falling back to the global connection like every other driver.
function pdo_mysql_func_set_charset($charset, $link = null)
{
    $pdo = pdo_mysql_func_conn($link);
    if (!$pdo) {
        output_error("SQL Error: No valid PDO connection.", E_USER_ERROR);
        return false;
    }

    try {
        $quoted = $pdo->quote($charset);
        $pdo->exec("SET NAMES $quoted");
        return true;
    } catch (PDOException $e) {
        output_error("PDO Exception: " . $e->getMessage(), E_USER_ERROR);
        return false;
    }
}

// Get next id for stuff
function pdo_mysql_func_get_next_id($tablepre, $table, $link = null)
{
    $pdo = pdo_mysql_func_conn($link);
    return $pdo ? $pdo->lastInsertId() : false;
}

// Get number of rows for table
function pdo_mysql_func_get_num_rows($tablepre, $table, $link = null)
{
    $sql = "SELECT COUNT(*) AS cnt FROM " . sql_quote_identifier($tablepre . $table, 'backtick');
    $result = pdo_mysql_func_query($sql, $link);
    if ($result === false) {
        return false;
    }

    $row = pdo_mysql_func_fetch_assoc($result);
    pdo_mysql_func_free_result($result);

    return (is_array($row) && isset($row['cnt'])) ? (int)$row['cnt'] : 0;
}

function pdo_mysql_func_count_rows($query, $link = null, $countname = "cnt")
{
    $result = pdo_mysql_func_query($query, $link);
    if ($result === false) {
        return false;
    }

    $row = pdo_mysql_func_fetch_assoc($result);
    $count = (is_array($row) && isset($row[$countname])) ? $row[$countname] : 0;

    pdo_mysql_func_free_result($result);
    return $count;
}

function pdo_mysql_func_count_rows_alt($query, $link = null)
{
    $result = pdo_mysql_func_query($query, $link);
    if ($result === false) {
        return false;
    }

    $row = pdo_mysql_func_fetch_assoc($result);
    $count = is_array($row) ? reset($row) : 0;

    pdo_mysql_func_free_result($result);
    return $count;
}
