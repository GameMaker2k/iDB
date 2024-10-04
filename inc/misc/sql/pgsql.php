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
function pgsql_func_error($link = null) {
    global $SQLStat;
    return isset($link) ? pg_last_error($link) : pg_last_error($SQLStat);
}

function pgsql_func_errno($link = null) {
    global $SQLStat;
    return isset($link) ? pg_last_error($link) : pg_last_error($SQLStat);
}

function pgsql_func_errorno($link = null) {
    global $SQLStat;
    return isset($link) ? pg_last_error($link) : pg_last_error($SQLStat);
}

// Execute a query
if (!isset($NumQueriesArray['pgsql'])) {
    $NumQueriesArray['pgsql'] = 0;
}

function pgsql_func_query($query, $link = null) {
    global $NumQueriesArray, $SQLStat;
    
    $result = isset($link) ? pg_query($link, $query) : pg_query(null, $query);

    if ($result === false) {
        output_error("SQL Error: " . pgsql_func_error(), E_USER_ERROR);
        return false;
    }

    if ($result !== false) {
        ++$NumQueriesArray['pgsql'];
        return $result;
    }
}

// Fetch Number of Rows
function pgsql_func_num_rows($result) {
    $num = pg_num_rows($result);
    
    if ($num === false) {
        output_error("SQL Error: " . pgsql_func_error(), E_USER_ERROR);
        return false;
    }
    
    return $num;
}

// Connect to PostgreSQL database
function pgsql_func_connect_db($server, $username, $password, $database = null, $new_link = false) {
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
        output_error("Not connected: " . pgsql_func_error(), E_USER_ERROR);
        return false;
    }

    return $link;
}

function pgsql_func_disconnect_db($link = null) {
    return pg_close($link);
}

// Query Results
function pgsql_func_result($result, $row, $field = 0) {
    $value = is_numeric($field)
        ? pg_fetch_result($result, $row, $field)
        : pg_fetch_result($result, $row, "\"$field\"");
    
    if ($value === false) {
        output_error("SQL Error: " . pgsql_func_error(), E_USER_ERROR);
        return false;
    }
    
    return $value;
}

// Free Results
function pgsql_func_free_result($result) {
    $fresult = pg_free_result($result);

    if ($fresult === false) {
        output_error("SQL Error: " . pgsql_func_error(), E_USER_ERROR);
        return false;
    }

    return true;
}

// Fetch Results to Array
function pgsql_func_fetch_array($result, $result_type = PGSQL_BOTH) {
    return pg_fetch_array($result, $result_type);
}

// Fetch Results to Associative Array
function pgsql_func_fetch_assoc($result) {
    return pg_fetch_assoc($result);
}

// Fetch Row Results
function pgsql_func_fetch_row($result) {
    return pg_fetch_row($result);
}

// Get Server Info
function pgsql_func_server_info($link = null) {
    $result = isset($link) ? pg_version($link) : pg_version();
    return $result['server'];
}

// Get Client Info
function pgsql_func_client_info($link = null) {
    $result = isset($link) ? pg_version($link) : pg_version();
    return $result['client'];
}

// Escape String
function pgsql_func_escape_string($string, $link = null) {
    global $SQLStat;
	if (!isset($string)) return null;
    return isset($link) ? pg_escape_string($link, $string) : pg_escape_string($SQLStat, $string);
}

// SafeSQL Lite Source Code by Cool Dude 2k
// Make SQL Query's safe
// SafeSQL Lite with additional SafeSQL features
function pgsql_func_pre_query($query_string, $query_vars) {
    // If no query variables are provided, initialize with a single element array containing null
    if ($query_vars == null) {
        $query_vars = array(null);
    }

    // Add support for multiple variable types like %c (comma-separated integers), %l (comma-separated strings), etc.
    $query_array = array(
        array("%i", "%I", "%F", "%S", "%c", "%l", "%q", "%n"),
        array("%d", "%d", "%f", "%s", "%s", "%s", "%s", "%s")
    );
    
    // Replace custom placeholders with appropriate sprintf format specifiers
    $query_string = str_replace($query_array[0], $query_array[1], $query_string);

    // Handle magic quotes if enabled (for backward compatibility with older PHP versions)
    if (get_magic_quotes_gpc()) {
        $query_vars = array_map("stripslashes", $query_vars);
    }

    // Escape each variable using sqlite_func_escape_string
    $query_vars = array_map("pgsql_func_escape_string", $query_vars);

    // Prepare the variables for sprintf
    $query_val = $query_vars;
    $query_num = count($query_val);
    $query_i = 0;

    // Replace variables in reverse order to avoid string position issues
    while ($query_i < $query_num) {
        $query_val[$query_i + 1] = _convert_var($query_vars[$query_i], $query_string);
        ++$query_i;
    }

    // Set the first element of the array to be the query string
    $query_val[0] = $query_string;

    // Use sprintf to inject the variables into the query string safely
    return call_user_func_array("sprintf", $query_val);
}

// Set Charset
function pgsql_func_set_charset($charset, $link = null) {
    return true;
}

// Get next id for stuff
function pgsql_func_get_next_id($tablepre, $table, $link = null) {
    $getnextidq = pgsql_func_pre_query("SELECT currval('" . $tablepre . $table . "_id_seq');", array());
    $getnextidr = isset($link) ? pgsql_func_query($getnextidq, $link) : pgsql_func_query($getnextidq);
    return pgsql_func_result($getnextidr, 0);
}

// Get number of rows for table
function pgsql_func_get_num_rows($tablepre, $table, $link = null) {
    $getnextidq = pgsql_func_pre_query("SHOW TABLE STATUS LIKE '" . $tablepre . $table . "'", array());
    $getnextidr = isset($link) ? pgsql_func_query($getnextidq, $link) : pgsql_func_query($getnextidq);
    $getnextid = pgsql_func_fetch_assoc($getnextidr);
    return $getnextid['Rows'];
    @pgsql_func_result($getnextidr);
}


// Fetch Number of Rows using COUNT in a single query (uses pgsql_func_fetch_assoc)
function pgsql_func_count_rows($query, $link = null, $countname = "cnt") {
    $result = pgsql_func_query($query, [], $link);  // Pass empty array for params
    $row = pgsql_func_fetch_assoc($result);

    if ($row === false) {
        return false;  // Handle case if no row is returned
    }

    // Use the dynamic column name provided by $countname
    $count = isset($row[$countname]) ? $row[$countname] : 0;

    @pgsql_func_free_result($result);
    return $count;
}

// Alternative version using pgsql_func_fetch_assoc
function pgsql_func_count_rows_alt($query, $link = null) {
    $result = pgsql_func_query($query, [], $link);  // Pass empty array for params
    $row = pgsql_func_fetch_assoc($result);
    
    if ($row === false) {
        return false;  // Handle case if no row is returned
    }
    
    // Return first column (assuming single column result like COUNT or similar)
    $count = reset($row);

    @pgsql_func_free_result($result);
    return $count;
}
?>
