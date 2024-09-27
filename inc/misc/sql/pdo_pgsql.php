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

    $FileInfo: pdo_pgsql.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "pdo_pgsql.php" || $File3Name == "/pdo_pgsql.php") {
    @header('Location: index.php');
    exit();
}

// PostgreSQL Error handling functions
function pdo_pgsql_func_error($link = null) {
    global $SQLStat;
    $result = isset($link) ? $link->errorInfo() : $SQLStat->errorInfo();
    return ($result == "") ? "" : $result;
}

function pdo_pgsql_func_errno($link = null) {
    global $SQLStat;
    $result = isset($link) ? $link->errorCode() : $SQLStat->errorCode();
    return ($result === 0) ? 0 : $result;
}

// Execute a query
if (!isset($NumQueriesArray['pdo_pgsql'])) {
    $NumQueriesArray['pdo_pgsql'] = 0;
}

function pdo_pgsql_func_query($query, $link = null) {
    global $NumQueriesArray, $SQLStat;

    // Use the appropriate PDO connection
    $pdo = isset($link) ? $link : $SQLStat;

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

        ++$NumQueriesArray['pdo_pgsql'];
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

        ++$NumQueriesArray['pdo_pgsql'];
        return $result;
    }
}

// Fetch number of rows for SELECT queries
function pdo_pgsql_func_num_rows($result) {
    if ($result instanceof PDOStatement) {
        $num = $result->rowCount();
        return $num !== false ? $num : 0;
    }
    return false;
}

// Connect to PostgreSQL database
function pdo_pgsql_func_connect_db($server, $username, $password, $database = null, $new_link = false) {
    global $SQLStat;
    $dsn = "pgsql:host=$server";

    // If a database is specified, include it in the DSN
    if ($database !== null) {
        $dsn .= ";dbname=$database";
    }

    try {
        // Create a new PDO instance with the DSN, username, and password
        $SQLStat = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Set error mode to exceptions
            PDO::ATTR_PERSISTENT => $new_link            // Use persistent connections if requested
        ]);

        // Return the PDO instance (PostgreSQL connection)
        return $SQLStat;

    } catch (PDOException $e) {
        // Output error if connection fails
        output_error("Connection failed: " . $e->getMessage(), E_USER_ERROR);
        return false;
    }
}

function pdo_pgsql_func_disconnect_db($link = null) {
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

// Query result fetching for both associative and numeric arrays
function pdo_pgsql_func_result($result, $row = 0, $field = 0) {
    if ($result instanceof PDOStatement) {
        $rows = $result->fetchAll(PDO::FETCH_BOTH);

        if (!isset($rows[$row])) {
            return null;
        }

        return $rows[$row][$field] ?? null;
    }
    return false;
}

// Fetch row results as an array
function pdo_pgsql_func_fetch_array($result, $result_type = PDO::FETCH_BOTH) {
    return $result->fetch($result_type);
}

// Fetch row results as an associative array
function pdo_pgsql_func_fetch_assoc($result) {
    return $result->fetch(PDO::FETCH_ASSOC);
}

// Fetch row results as a numeric array
function pdo_pgsql_func_fetch_row($result) {
    return $result->fetch(PDO::FETCH_NUM);
}

// Escape a string for PostgreSQL queries
function pdo_pgsql_func_escape_string($string, $link = null) {
    global $SQLStat;
    if (!isset($string)) return null;

    $pdo = isset($link) ? $link : $SQLStat;
    $escaped_string = $pdo->quote($string);

    if ($escaped_string === false) {
        output_error("SQL Error: " . pdo_pgsql_func_error(), E_USER_ERROR);
        return false;
    }
    return $escaped_string;
}

// SafeSQL Lite with additional SafeSQL features for PostgreSQL
function pdo_pgsql_func_pre_query($query_string, $query_vars) {
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

    // Return the query string and variables for further execution
    return [$query_string, $query_vars];
}

// Fetch the next ID from the sequence
function pdo_pgsql_func_get_next_id($tablepre, $table, $link = null) {
    $query = pdo_pgsql_func_pre_query("SELECT currval(pg_get_serial_sequence('" . $tablepre . $table . "', 'id'))", []);
    $result = pdo_pgsql_func_query($query, $link);
    $row = pdo_pgsql_func_fetch_assoc($result);
    return $row['currval'] ?? 0;
}

// Set Charset (dummy function)
function pdo_pgsql_func_set_charset($charset, $link = null) {
    return true;
}

// Fetch Number of Rows using COUNT in a single query
function pdo_pgsql_func_count_rows($query, $link = null) {
    $result = pdo_pgsql_func_query($query, $link);
    $row = pdo_pgsql_func_result($result, 0, 'cnt');
    @pdo_pgsql_func_free_result($result);
    return $row;
}

function pdo_pgsql_func_count_rows_alt($query, $link = null) {
    $result = pdo_pgsql_func_query($query, $link);
    $row = pdo_pgsql_func_result($result, 0);
    @pdo_pgsql_func_free_result($result);
    return $row;
}

function pdo_pgsql_func_free_result($result) {
    return true;
}

// Get Server Info for PDO PostgreSQL
function pdo_pgsql_func_server_info($link = null) {
    $result = $link->query('SELECT version()')->fetch(PDO::FETCH_COLUMN);
    return $result;
}

// Get Client Info for PDO PostgreSQL
function pdo_pgsql_func_client_info($link = null) {
    return $link->getAttribute(PDO::ATTR_CLIENT_VERSION);
}

?>
