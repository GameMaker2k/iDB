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

    $FileInfo: pdo_sqlsrv.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "pdo_sqlsrv.php" || $File3Name == "/pdo_sqlsrv.php") {
    @header('Location: index.php');
    exit();
}

// Execute a query
if (!isset($NumPreQueriesArray['pdo_sqlsrv'])) {
    $NumPreQueriesArray['pdo_sqlsrv'] = 0;
}

// SQLSRV Error handling functions
function pdo_sqlsrv_func_error($link = null)
{
    global $SQLStat;
    $result = isset($link) ? $link->errorInfo() : $SQLStat->errorInfo();
    return ($result == "") ? "" : $result;
}

function pdo_sqlsrv_func_errno($link = null)
{
    global $SQLStat;
    $result = isset($link) ? $link->errorCode() : $SQLStat->errorCode();
    return ($result === 0) ? 0 : $result;
}

function pdo_sqlsrv_func_errorno($link = null)
{
    global $SQLStat;
    $result = isset($link) ? $link->errorCode() . ": " . $link->errorInfo() : $SQLStat->errorCode() . ": " . $SQLStat->errorInfo();
    return ($result == "") ? "" : $result;
}

// Execute a query
if (!isset($NumQueriesArray['pdo_sqlsrv'])) {
    $NumQueriesArray['pdo_sqlsrv'] = 0;
}

function pdo_sqlsrv_func_query($query, $link = null)
{
    global $NumQueriesArray, $SQLStat;

    // Use the appropriate PDO connection
    $pdo = isset($link) && $link instanceof PDO ? $link : $SQLStat;

    // If the query is an array (with query and parameters)
    if (is_array($query)) {
        list($query_string, $params) = $query;
        $stmt = $pdo->prepare($query_string);

        // Bind parameters dynamically based on their type
        foreach ($params as $key => $value) {
            $paramKey = is_int($key) ? $key + 1 : $key;  // For positional keys, shift index to start at 1
            if (is_int($value)) {
                $stmt->bindValue($paramKey, $value, PDO::PARAM_INT);
            } elseif (is_bool($value)) {
                $stmt->bindValue($paramKey, $value, PDO::PARAM_BOOL);
            } elseif (is_null($value)) {
                $stmt->bindValue($paramKey, $value, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue($paramKey, $value, PDO::PARAM_STR);
            }
        }

        // Execute the prepared statement with bound parameters
        $result = $stmt->execute();

        // Error handling
        if ($result === false) {
            $errorInfo = $pdo->errorInfo();
            output_error("SQL Error: " . $errorInfo[2], E_USER_ERROR);
            return false;
        }

        ++$NumQueriesArray['pdo_sqlsrv'];
        return $stmt;  // Return the statement for SELECT or data-fetching queries
    } else {
        // For direct queries without parameters
        $result = $pdo->query($query);

        // Error handling
        if ($result === false) {
            $errorInfo = $pdo->errorInfo();
            output_error("SQL Error: " . $errorInfo[2], E_USER_ERROR);
            return false;
        }

        ++$NumQueriesArray['pdo_sqlsrv'];
        return $result;
    }
}

// Fetch number of rows for SELECT queries
function pdo_sqlsrv_func_num_rows($result)
{
    if ($result instanceof PDOStatement) {
        $num = $result->rowCount();
        return $num !== false ? $num : 0;
    }
    return false;
}

// Connect to SQL Server using PDO and set session options
function pdo_sqlsrv_func_connect_db($server, $username = null, $password = null, $database = null, $new_link = false)
{
    global $SQLStat;

    // Set DSN (Data Source Name) for SQLSRV connection
    $dsn = "sqlsrv:Server=$server";

    // If a database is specified, include it in the DSN
    if ($database !== null) {
        $dsn .= ";Database=$database";
    }

    try {
        // Connection options for SQL Authentication
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Set error mode to exceptions
            PDO::ATTR_PERSISTENT => $new_link             // Use persistent connections if requested
        ];

        // Check if PDO::SQLSRV_ATTR_TRUST_SERVER_CERTIFICATE is supported and set it
        if (defined('PDO::SQLSRV_ATTR_TRUST_SERVER_CERTIFICATE')) {
            $options[PDO::SQLSRV_ATTR_TRUST_SERVER_CERTIFICATE] = true; // Trust server certificate if available
        }

        // Check if PDO::SQLSRV_ATTR_ENCODING is supported and set it to UTF-8
        if (defined('PDO::SQLSRV_ATTR_ENCODING')) {
            $options[PDO::SQLSRV_ATTR_ENCODING] = PDO::SQLSRV_ENCODING_UTF8; // Set UTF-8 encoding if available
        }

        // Use SQL Authentication if username and password are provided
        if (!empty($username) && !empty($password)) {
            $SQLStat = new PDO($dsn, $username, $password, $options);
        } else {
            // Use Windows Authentication (omit username and password)
            $SQLStat = new PDO($dsn, null, null, $options);
        }

        return $SQLStat;

    } catch (PDOException $e) {
        output_error("Connection failed: " . $e->getMessage(), E_USER_ERROR);
        return false;
    }
}

function pdo_sqlsrv_func_disconnect_db($link = null)
{
    global $SQLStat;
    if (isset($link) && $link instanceof PDOStatement) {
        return $link->closeCursor();
    }

    if (!isset($link) && isset($SQLStat)) {
        $SQLStat = null;
        return true;
    }

    return false;
}

// Query Results
function pdo_sqlsrv_func_result($result, $row = 0, $field = 0)
{
    if ($result instanceof PDOStatement) {
        $rows = $result->fetchAll(PDO::FETCH_BOTH);

        if (!isset($rows[$row])) {
            return null;
        }

        return $rows[$row][$field] ?? null;
    }
    return false;
}

// Free Results
function pdo_sqlsrv_func_free_result($result)
{
    return true;
}

// Fetch Results to Array
function pdo_sqlsrv_func_fetch_array($result, $result_type = PDO::FETCH_BOTH)
{
	if($result_type==NULL) {
		$result_type = CUBRID_BOTH;
	}
    return $result->fetch($result_type);
}

// Fetch Results to Associative Array
function pdo_sqlsrv_func_fetch_assoc($result)
{
    return $result->fetch(PDO::FETCH_ASSOC);
}

// Fetch Row Results
function pdo_sqlsrv_func_fetch_row($result)
{
    return $result->fetch(PDO::FETCH_NUM);
}

// Get Server Info
function pdo_sqlsrv_func_server_info($link = null)
{
    $result = $link->query('select @@version')->fetch()[0];
    return $result;
}

// Get Client Info for PDO SQLSRV
function pdo_sqlsrv_func_client_info($link = null)
{
    return $link->getAttribute(PDO::ATTR_CLIENT_VERSION);
}

// Escape String
function pdo_sqlsrv_func_escape_string($string, $link = null)
{
    global $SQLStat;
    $pdo = isset($link) && $link instanceof PDO ? $link : $SQLStat;
    return $pdo->quote($string);
}

// Pre-process Query for SQLSRV
function pdo_sqlsrv_func_pre_query($query_string, $query_vars = [])
{
    global $NumPreQueriesArray;

    if ($query_vars === null || !is_array($query_vars)) {
        $query_vars = [];
    }

    // Handle placeholders like %s, %d, %i, %f and convert them to PDO's positional placeholders (?)
    $query_string = str_replace(["'%s'", '%d', '%i', '%f'], ['?', '?', '?', '?'], $query_string);

    // If the query contains named placeholders (e.g., :name), we won't replace those
    // Filter out null values in $query_vars array
    $query_vars = array_filter($query_vars, function ($value) {
        return $value !== null;
    });

    // Check for mismatch between placeholders and variables
    $placeholder_count = substr_count($query_string, '?');
    $params_count = count($query_vars);

    if ($placeholder_count !== $params_count) {
        output_error("SQL Placeholder Error: Mismatch between placeholders ($placeholder_count) and parameters ($params_count).", E_USER_ERROR);
        return false;
    }

    ++$NumPreQueriesArray['pdo_sqlsrv'];

    // Return the query string and variables for further execution
    return [$query_string, $query_vars];
}

function pdo_sqlsrv_func_set_charset($charset, $pdo = null)
{
    if (!isset($pdo) || !($pdo instanceof PDO)) {
        output_error("Invalid PDO instance provided.", E_USER_ERROR);
        return false;
    }

    try {
        $result = $pdo->exec("SET NAMES '" . $charset . "'");
        if ($result === false) {
            $errorInfo = $pdo->errorInfo();
            output_error("SQL Error: " . $errorInfo[2], E_USER_ERROR);
            return false;
        }

        $result = $pdo->exec("SET CHARACTER SET '" . $charset . "'");
        if ($result === false) {
            $errorInfo = $pdo->errorInfo();
            output_error("SQL Error: " . $errorInfo[2], E_USER_ERROR);
            return false;
        }

        return true;
    } catch (PDOException $e) {
        output_error("PDO Exception: " . $e->getMessage(), E_USER_ERROR);
        return false;
    }
}

// Get next id for stuff
function pdo_sqlsrv_func_get_next_id($tablepre, $table, $link = null)
{
    global $SQLStat;
    return isset($link) ? $link->lastInsertId() : $SQLStat->lastInsertId();
}

// Get number of rows for table
function pdo_sqlsrv_func_get_num_rows($tablepre, $table, $link = null)
{
    $query = pdo_sqlsrv_func_pre_query("SELECT COUNT(*) AS cnt FROM " . $tablepre . $table, []);
    $result = pdo_sqlsrv_func_query($query, $link);
    $row = pdo_sqlsrv_func_fetch_assoc($result);
    return $row['cnt'] ?? 0;
}


// Fetch Number of Rows using COUNT in a single query (uses pdo_sqlsrv_func_fetch_assoc)
function pdo_sqlsrv_func_count_rows($query, $link = null, $countname = "cnt")
{
    $result = pdo_sqlsrv_func_query($query, [], $link);  // Pass empty array for params
    $row = pdo_sqlsrv_func_fetch_assoc($result);

    if ($row === false) {
        return false;  // Handle case if no row is returned
    }

    // Use the dynamic column name provided by $countname
    $count = isset($row[$countname]) ? $row[$countname] : 0;

    @pdo_sqlsrv_func_free_result($result);
    return $count;
}

// Alternative version using pdo_sqlsrv_func_fetch_assoc
function pdo_sqlsrv_func_count_rows_alt($query, $link = null)
{
    $result = pdo_sqlsrv_func_query($query, [], $link);  // Pass empty array for params
    $row = pdo_sqlsrv_func_fetch_assoc($result);

    if ($row === false) {
        return false;  // Handle case if no row is returned
    }

    // Return first column (assuming single column result like COUNT or similar)
    $count = reset($row);

    @pdo_sqlsrv_func_free_result($result);
    return $count;
}
