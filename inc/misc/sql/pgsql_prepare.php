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
    @header('Location: index.php');
    exit();
}

// PostgreSQL Error handling functions
function pgsql_prepare_func_error($link = null)
{
    global $SQLStat;
    return isset($link) ? pg_last_error($link) : pg_last_error($SQLStat);
}

function pgsql_prepare_func_errno($link = null)
{
    global $SQLStat;
    return isset($link) ? pg_last_error($link) : pg_last_error($SQLStat);
}

function pgsql_prepare_func_errorno($link = null)
{
    global $SQLStat;
    return isset($link) ? pg_last_error($link) : pg_last_error($SQLStat);
}

// Execute a prepared query
if (!isset($NumQueriesArray['pgsql_prepare'])) {
    $NumQueriesArray['pgsql_prepare'] = 0;
}

function pgsql_prepare_func_query($query, $params = [], $link = null)
{
    global $NumQueriesArray, $SQLStat;

    $connection = isset($link) ? $link : $SQLStat;

    // If the query is provided as an array (query string and parameters)
    if (is_array($query)) {
        list($query_string, $params) = $query;
    } else {
        $query_string = $query;
    }

    // Prepare a statement with a unique name
    $stmt_name = md5($query_string); // Generate a unique statement name based on the query
    $prepare = pg_prepare($connection, $stmt_name, $query_string);

    if ($prepare === false) {
        output_error("SQL Prepare Error: " . pgsql_prepare_func_error($connection), E_USER_ERROR);
        return false;
    }

    // Execute the prepared statement
    $result = pg_execute($connection, $stmt_name, $params);

    if ($result === false) {
        output_error("SQL Execute Error: " . pgsql_prepare_func_error($connection), E_USER_ERROR);
        return false;
    }

    ++$NumQueriesArray['pgsql_prepare'];
    return $result;
}

// Fetch Number of Rows
function pgsql_prepare_func_num_rows($result)
{
    $num = pg_num_rows($result);

    if ($num === false) {
        output_error("SQL Error: " . pgsql_prepare_func_error(), E_USER_ERROR);
        return false;
    }

    return $num;
}

// Connect to PostgreSQL database
function pgsql_prepare_func_connect_db($server, $username, $password, $database = null, $new_link = false)
{
    $pgport = "5432";
    $hostex = explode(":", $server);

    if (isset($hostex[1]) && !is_numeric($hostex[1])) {
        $hostex[1] = $pgport;
    }

    if (isset($hostex[1])) {
        $server = $hostex[0];
        $pgport = $hostex[1];
    }

    $pgstring = $database === null
        ? "host=$server port=$pgport user=$username password=$password"
        : "host=$server port=$pgport dbname=$database user=$username password=$password";

    $link = pg_connect($pgstring);

    if ($link === false) {
        output_error("Not connected: " . pgsql_prepare_func_error(), E_USER_ERROR);
        return false;
    }

    return $link;
}

function pgsql_prepare_func_disconnect_db($link = null)
{
    return pg_close($link);
}

// Query Results
function pgsql_prepare_func_result($result, $row, $field = 0)
{
    $value = is_numeric($field)
        ? pg_fetch_result($result, $row, $field)
        : pg_fetch_result($result, $row, "\"$field\"");

    if ($value === false) {
        output_error("SQL Error: " . pgsql_prepare_func_error(), E_USER_ERROR);
        return false;
    }

    return $value;
}

// Free Results
function pgsql_prepare_func_free_result($result)
{
    $fresult = pg_free_result($result);

    if ($fresult === false) {
        output_error("SQL Error: " . pgsql_prepare_func_error(), E_USER_ERROR);
        return false;
    }

    return true;
}

// Fetch Results to Array
function pgsql_prepare_func_fetch_array($result, $result_type = PGSQL_BOTH)
{
    return pg_fetch_array($result, $result_type);
}

// Fetch Results to Associative Array
function pgsql_prepare_func_fetch_assoc($result)
{
    return pg_fetch_assoc($result);
}

// Fetch Row Results
function pgsql_prepare_func_fetch_row($result)
{
    return pg_fetch_row($result);
}

// Get Server Info
function pgsql_prepare_func_server_info($link = null)
{
    $result = isset($link) ? pg_version($link) : pg_version();
    return $result['server'];
}

// Get Client Info
function pgsql_prepare_func_client_info($link = null)
{
    $result = isset($link) ? pg_version($link) : pg_version();
    return $result['client'];
}

// Escape String
function pgsql_prepare_func_escape_string($string, $link = null)
{
    global $SQLStat;
    if (!isset($string)) {
        return null;
    }
    return isset($link) ? pg_escape_string($link, $string) : pg_escape_string($SQLStat, $string);
}

// Execute a query
if (!isset($NumPreQueriesArray['pgsql_prepare'])) {
    $NumPreQueriesArray['pgsql_prepare'] = 0;
}

// SafeSQL Lite with additional SafeSQL features for PostgreSQL
function pgsql_prepare_func_pre_query($query_string, $query_vars)
{
    global $NumPreQueriesArray;

    if ($query_vars === null || !is_array($query_vars)) {
        $query_vars = [];
    }

    // Replace '%s', '%d', '%i', '%f' with $1, $2, $3... based on the number of query_vars
    foreach ($query_vars as $key => $value) {
        $position = $key + 1; // PostgreSQL uses 1-based index for placeholders

        // Handle the case where '%s' is inside quotes, like \'%s\'
        $query_string = preg_replace("/'(%[sdif])'/", "'\$$position'", $query_string, 1);

        // Handle other placeholders without quotes
        $query_string = preg_replace('/%[sdif]/', "\$$position", $query_string, 1);
    }

    // Filter out null values in the $query_vars array (if needed)
    $query_vars = array_filter($query_vars, function ($value) {
        return $value !== null;
    });

    ++$NumPreQueriesArray['pgsql_prepare'];

    // Return the modified query string and the variables for further execution
    return [$query_string, $query_vars];
}

// Get next ID for a table (e.g., from a sequence)
function pgsql_prepare_func_get_next_id($tablepre, $table, $link = null)
{
    $getnextidq = pgsql_prepare_func_pre_query("SELECT currval('" . $tablepre . $table . "_id_seq');", []);
    $getnextidr = isset($link) ? pgsql_prepare_func_query($getnextidq[0], $getnextidq[1], $link) : pgsql_prepare_func_query($getnextidq[0], $getnextidq[1]);
    return pgsql_prepare_func_result($getnextidr, 0);
}


// Fetch Number of Rows using COUNT in a single query (uses pgsql_prepare_func_fetch_assoc)
function pgsql_prepare_func_count_rows($query, $link = null, $countname = "cnt")
{
    $result = pgsql_prepare_func_query($query, [], $link);  // Pass empty array for params
    $row = pgsql_prepare_func_fetch_assoc($result);

    if ($row === false) {
        return false;  // Handle case if no row is returned
    }

    // Use the dynamic column name provided by $countname
    $count = isset($row[$countname]) ? $row[$countname] : 0;

    @pgsql_prepare_func_free_result($result);
    return $count;
}

// Alternative version using pgsql_prepare_func_fetch_assoc
function pgsql_prepare_func_count_rows_alt($query, $link = null)
{
    $result = pgsql_prepare_func_query($query, [], $link);  // Pass empty array for params
    $row = pgsql_prepare_func_fetch_assoc($result);

    if ($row === false) {
        return false;  // Handle case if no row is returned
    }

    // Return first column (assuming single column result like COUNT or similar)
    $count = reset($row);

    @pgsql_prepare_func_free_result($result);
    return $count;
}
