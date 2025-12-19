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

    $FileInfo: mysqli_prepare.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/

$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "mysqli_prepare.php" || $File3Name == "/mysqli_prepare.php") {
    @header('Location: index.php');
    exit();
}

// Execute a query
if (!isset($NumPreQueriesArray['mysqli_prepare'])) {
    $NumPreQueriesArray['mysqli_prepare'] = 0;
}

// MySQLi Error handling functions
function mysqli_prepare_func_error($link = null)
{
    global $SQLStat;
    $connection = ($link instanceof mysqli ? $link : $SQLStat);
    return $connection instanceof mysqli ? mysqli_error($connection) : false;
}

function mysqli_prepare_func_errno($link = null)
{
    global $SQLStat;
    $connection = ($link instanceof mysqli ? $link : $SQLStat);
    return $connection instanceof mysqli ? mysqli_errno($connection) : false;
}

function mysqli_prepare_func_errorno($link = null)
{
    global $SQLStat;
    $connection = ($link instanceof mysqli ? $link : $SQLStat);
    $result = $connection instanceof mysqli ? mysqli_prepare_func_error($connection) : false;
    $resultno = $connection instanceof mysqli ? mysqli_prepare_func_errno($connection) : false;

    return ($result == "" && $resultno === 0) ? "" : "$resultno: $result";
}

// Execute a query using prepared statements
if (!isset($NumQueriesArray['mysqli_prepare'])) {
    $NumQueriesArray['mysqli_prepare'] = 0;
}

function mysqli_prepare_func_query($query, $params_or_link = null, $maybe_link = null)
{
    global $NumQueriesArray, $SQLStat;

    if (!isset($NumQueriesArray['mysqli_prepare'])) {
        $NumQueriesArray['mysqli_prepare'] = 0;
    }

    // Detect call style
    // Style A: func_query($query, $link)
    // Style B: func_query($query, $params, $link)
    $params = [];
    $link = null;

    if ($maybe_link !== null) {
        // 3-arg style
        $params = is_array($params_or_link) ? $params_or_link : [];
        $link = $maybe_link;
    } else {
        // 2-arg style
        if ($params_or_link instanceof mysqli) {
            $link = $params_or_link;
        } else {
            // if someone passes params as 2nd arg without link
            $params = is_array($params_or_link) ? $params_or_link : [];
        }
    }

    $connection = ($link instanceof mysqli ? $link : $SQLStat);
    if (!($connection instanceof mysqli)) {
        output_error("SQL Error: No valid MySQLi connection in mysqli_prepare_func_query", E_USER_ERROR);
        return false;
    }

    // Support keyed format too: ['sql'=>..., 'params'=>...]
    if (is_array($query) && isset($query['sql'])) {
        $query = [$query['sql'], $query['params'] ?? []];
    }

    // Direct query (string)
    if (!is_array($query)) {
        if (!is_string($query) || trim($query) === '') {
            output_error("SQL Error: Query is empty", E_USER_ERROR);
            return false;
        }

        $result = mysqli_query($connection, $query);
        if ($result === false) {
            output_error("SQL Error: " . mysqli_error($connection), E_USER_ERROR);
            return false;
        }

        ++$NumQueriesArray['mysqli_prepare'];
        return $result;
    }

    // Prepared: [$sql, $params]
    $sql = $query[0] ?? null;
    $params = $query[1] ?? $params;

    if (!is_string($sql) || trim($sql) === '') {
        output_error("SQL Error: Query is empty", E_USER_ERROR);
        return false;
    }
    if (!is_array($params)) $params = [];

    $stmt = mysqli_prepare($connection, $sql);
    if (!$stmt) {
        output_error("SQL Error (Prepare): " . mysqli_error($connection), E_USER_ERROR);
        return false;
    }

    if (count($params) > 0) {
        $types = '';
        $bind = [];

        foreach ($params as $v) {
            if (is_int($v))       $types .= 'i';
            elseif (is_float($v)) $types .= 'd';
            else                  $types .= 's';
            $bind[] = $v;
        }

        $refs = [];
        foreach ($bind as $k => $v) {
            $refs[$k] = &$bind[$k];
        }
        array_unshift($refs, $types);

        if (!call_user_func_array([$stmt, 'bind_param'], $refs)) {
            $err = mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
            output_error("SQL Error (Bind): " . $err, E_USER_ERROR);
            return false;
        }
    }
    if (!mysqli_stmt_execute($stmt)) {
        $err = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        output_error("SQL Error (Execute): " . $err, E_USER_ERROR);
        return false;
    }

    ++$NumQueriesArray['mysqli_prepare'];


	// If this statement produces a result set (SELECT/SHOW/etc), return mysqli_result
	if (mysqli_stmt_field_count($stmt) > 0) {
		$res = mysqli_stmt_get_result($stmt);  // mysqlnd required (you have it)
		$err = mysqli_stmt_error($stmt);
		mysqli_stmt_close($stmt);              // close stmt ASAP

		if ($res === false) {
			output_error("SQL Error (Get Result): " . $err, E_USER_ERROR);
			return false;
		}

		++$NumQueriesArray['mysqli_prepare'];
		return $res; // <-- behaves like mysqli_query()
	}

	// Non-SELECT query: close stmt and return true
	mysqli_stmt_close($stmt);
	++$NumQueriesArray['mysqli_prepare'];
	return $stmt;
}

// Fetch number of rows for SELECT queries
function mysqli_prepare_func_num_rows($stmt)
{
    mysqli_stmt_store_result($stmt);  // Ensure results are stored for row counting
    $num = mysqli_stmt_num_rows($stmt);

    if ($num === false) {
        output_error("SQL Error: " . mysqli_prepare_func_error(), E_USER_ERROR);
        return false;
    }

    return $num;
}

// Connect to MySQLi database
function mysqli_prepare_func_connect_db($server, $username, $password, $database = null, $new_link = false)
{
    $myport = "3306";
    $hostex = explode(":", $server);

    if (isset($hostex[1]) && !is_numeric($hostex[1])) {
        $hostex[1] = $myport;
    }

    if (isset($hostex[1])) {
        $server = $hostex[0];
        $myport = $hostex[1];
    }

    $link = $database === null ? mysqli_connect($server, $username, $password, null, $myport) : mysqli_connect($server, $username, $password, $database, $myport);

    if ($link === false) {
        output_error("MySQLi Error " . mysqli_connect_errno() . ": " . mysqli_connect_error(), E_USER_ERROR);
        return false;
    }

    // Set MySQL session settings
    $result = mysqli_prepare_func_query("SET SESSION SQL_MODE='ANSI,ANSI_QUOTES,TRADITIONAL,STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION,NO_AUTO_VALUE_ON_ZERO';", [], $link);

    if ($result === false) {
        output_error("SQL Error: " . mysqli_prepare_func_error(), E_USER_ERROR);
        return false;
    }

    return $link;
}

function mysqli_prepare_func_disconnect_db($link = null)
{
    return mysqli_close($link);
}

// Query results fetching
function mysqli_prepare_func_result($result, $row, $field = 0)
{
    // If we already have a normal mysqli_result, behave like mysqli_func_result
    if ($result instanceof mysqli_result) {
        $check = mysqli_data_seek($result, $row);
        if ($check === false) {
            output_error("SQL Error: " . mysqli_prepare_func_error(), E_USER_ERROR);
            return false;
        }
        $trow = mysqli_fetch_array($result, MYSQLI_BOTH);
        return $trow[$field] ?? null;
    }

    // Otherwise, fall back to old stmt-based behavior
    if (!($result instanceof mysqli_stmt)) {
        return null;
    }

    mysqli_stmt_store_result($result);
    $meta = mysqli_stmt_result_metadata($result);
    $fields = mysqli_fetch_fields($meta);

    $bound = [];
    $rowData = [];
    foreach ($fields as $f) {
        $rowData[$f->name] = null;
        $bound[] = &$rowData[$f->name];
    }
    call_user_func_array('mysqli_stmt_bind_result', array_merge([$result], $bound));

    for ($i = 0; $i <= $row; $i++) {
        if (!mysqli_stmt_fetch($result)) {
            return null;
        }
    }

    $name = $fields[$field]->name ?? null;
    return $name !== null ? ($rowData[$name] ?? null) : null;
}

// Free results
function mysqli_prepare_func_free_result($result) {
    return ($result instanceof mysqli_result) ? mysqli_free_result($result) : true;
}

// Fetch results like mysqli_fetch_array() for prepared statements
function mysqli_prepare_func_fetch_array($result, $type = MYSQLI_BOTH) {
    if ($type === null) $type = MYSQLI_BOTH;
    return ($result instanceof mysqli_result) ? mysqli_fetch_array($result, $type) : null;
}

function mysqli_prepare_func_fetch_assoc_bind($stmt)
{
    static $cache = [];

    $id = spl_object_id($stmt);

    if (!isset($cache[$id])) {
        mysqli_stmt_store_result($stmt);
        $meta = mysqli_stmt_result_metadata($stmt);
        if (!$meta) return null;

        $fields = mysqli_fetch_fields($meta);

        $row = [];
        $bind = [];
        $keys = [];

        foreach ($fields as $f) {
            $keys[] = $f->name;
            $row[$f->name] = null;
            $bind[] = &$row[$f->name];
        }

        call_user_func_array('mysqli_stmt_bind_result', array_merge([$stmt], $bind));

        $cache[$id] = [$row, $keys];
    }

    if (!mysqli_stmt_fetch($stmt)) {
        return null;
    }

    [$row, $keys] = $cache[$id];

    // copy values (bound vars are by reference)
    $out = [];
    foreach ($keys as $k) $out[$k] = $row[$k];

    return $out;
}

// Fetch results as associative array
function mysqli_prepare_func_fetch_assoc($result) {
    return ($result instanceof mysqli_result) ? mysqli_fetch_assoc($result) : null;
}

// Fetch row results as a numeric array
function mysqli_prepare_func_fetch_row($result) {
    return ($result instanceof mysqli_result) ? mysqli_fetch_row($result) : null;
}

// Get Server Info
function mysqli_prepare_func_server_info($link = null)
{
    global $SQLStat;
    $connection = ($link instanceof mysqli ? $link : $SQLStat);
    return $connection instanceof mysqli ? mysqli_get_server_info($connection) : false;
}

// Get Client Info
function mysqli_prepare_func_client_info($link = null)
{
    return mysqli_get_client_info();
}

// Escape string
function mysqli_prepare_func_escape_string($string, $link = null)
{
    global $NumPreQueriesArray;

    global $SQLStat;
    if (!isset($string)) {
        return null;
    }
    $connection = ($link instanceof mysqli ? $link : $SQLStat);
    return $connection instanceof mysqli ? mysqli_real_escape_string($connection, $string) : false;
}

// Execute a query
if (!isset($NumPreQueriesArray['mysqli_prepare'])) {
    $NumPreQueriesArray['mysqli_prepare'] = 0;
}

// SafeSQL Lite with prepared statements and placeholders
function mysqli_prepare_func_pre_query($query_string, $query_vars = [])
{
    global $NumPreQueriesArray;

    if (!isset($NumPreQueriesArray['mysqli_prepare'])) {
        $NumPreQueriesArray['mysqli_prepare'] = 0;
    }

    if ($query_vars === null || !is_array($query_vars)) {
        $query_vars = [];
    }

    // Convert SafeSQL placeholders to positional placeholders
    // Keep behavior similar to your PDO pre_query
    $query_string = str_replace(["'%s'", "%s", "%d", "%i", "%f"], ["?", "?", "?", "?", "?"], $query_string);

    // IMPORTANT: Do not remove NULLs (prepared statements can bind NULL)
    ++$NumPreQueriesArray['mysqli_prepare'];

    return [$query_string, $query_vars];
}

// Set Charset using Prepared Statements
function mysqli_prepare_func_set_charset($charset, $link = null)
{
    global $SQLStat;

    // Determine which connection to use, link or global SQLStat
    $connection = ($link instanceof mysqli ? $link : $SQLStat);

    // Fallback if mysqli_set_charset function does not exist
    if (function_exists('mysqli_set_charset') === false) {
        // If mysqli_set_charset does not exist, fall back to manual SQL queries
        $result = $connection instanceof mysqli ? mysqli_prepare_func_query("SET CHARACTER SET '%s'", [$charset], $connection) : false;

        if ($result === false) {
            output_error("SQL Error: " . mysqli_prepare_func_error($connection), E_USER_ERROR);
            return false;
        }

        $result = $connection instanceof mysqli ? mysqli_prepare_func_query("SET NAMES '%s'", [$charset], $connection) : false;

        if ($result === false) {
            output_error("SQL Error: " . mysqli_prepare_func_error($connection), E_USER_ERROR);
            return false;
        }

        return true;
    }

    // Use mysqli_set_charset if the function exists
    $result = $connection instanceof mysqli ? mysqli_set_charset($connection, $charset) : false;

    if ($result === false) {
        output_error("SQL Error: " . mysqli_prepare_func_error($connection), E_USER_ERROR);
        return false;
    }

    return true;
}

// Get next ID after an insert
function mysqli_prepare_func_get_next_id($link = null)
{
    return mysqli_insert_id($link);
}

// Fetch Number of Rows using COUNT in a single query (uses mysqli_prepare_func_fetch_assoc)
function mysqli_prepare_func_count_rows($query, $link = null, $countname = "cnt")
{
    $result = mysqli_prepare_func_query($query, $link);
    if ($result === false) return false;

    $row = mysqli_prepare_func_fetch_assoc($result);
    $count = (is_array($row) && isset($row[$countname])) ? (int)$row[$countname] : 0;

    mysqli_prepare_func_free_result($result);
    return $count;
}

// Alternative version using mysqli_prepare_func_fetch_assoc
function mysqli_prepare_func_count_rows_alt($query, $link = null)
{
    $result = mysqli_prepare_func_query($query, [], $link);  // Pass empty array for params
    $row = mysqli_prepare_func_fetch_assoc($result);

    if ($row === false) {
        return false;  // Handle case if no row is returned
    }

    // Return first column (assuming single column result like COUNT or similar)
    $count = reset($row);

    @mysqli_prepare_func_free_result($result);
    return $count;
}
