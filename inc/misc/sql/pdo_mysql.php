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

    $FileInfo: mysqli.php - Last Update: 8/30/2024 SVN 1063 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="mysqli.php"||$File3Name=="/mysqli.php") {
	@header('Location: index.php');
	exit(); }
function pdo_mysql_func_error($link=null) {
global $SQLStat;
if(isset($link)) {
	$result = $link->errorInfo(); }
if(!isset($link)) {
	$result = $SQLStat->errorInfo(); }
if ($result=="") {
	return ""; }
	return $result; }
function pdo_mysql_func_errno($link=null) {
global $SQLStat;
if(isset($link)) {
	$result = $link->errorCode(); }
if(!isset($link)) {
	$result = $SQLStat->errorCode(); }
if ($result===0) {
	return 0; }
	return $result; }
function pdo_mysql_func_errorno($link=null) {
global $SQLStat;
if(isset($link)) {
	$result = $link->errorCode().": ".$link->errorInfo(); }
if(!isset($link)) {
	$result = $SQLStat->errorCode().": ".$SQLStat->errorInfo(); }
if ($result=="") {
	return ""; }
	return $result; }
// Execute a query :P
if(!isset($NumQueries)) {
$NumQueries = 0; }
function pdo_mysql_func_query($query, $link = null) {
    global $NumQueries, $SQLStat;

    // Use the appropriate PDO connection
    $pdo = isset($link) ? $link : $SQLStat;

    // Check if the query is an array containing the query string and parameters
    if (is_array($query)) {
        list($query_string, $params) = $query;
        $stmt = $pdo->prepare($query_string);

        // Execute with parameters
        $result = $stmt->execute($params);

        if ($result === false) {
            // Improved error handling
            $errorInfo = $pdo->errorInfo();
            output_error("SQL Error: " . $errorInfo[2], E_USER_ERROR);
            return false;
        }
        ++$NumQueries;
        return $stmt;  // Return the statement for SELECT or data-fetching queries
    } else {
        // For direct queries without parameters
        $result = $pdo->query($query);

        if ($result === false) {
            // Improved error handling
            $errorInfo = $pdo->errorInfo();
            output_error("SQL Error: " . $errorInfo[2], E_USER_ERROR);
            return false;
        }

        // Check if it's a SELECT or other data-fetching query
        if ($result instanceof PDOStatement) {
            ++$NumQueries;
            return $result;
        }

        // For INSERT, UPDATE, DELETE queries, return true if successful
        return $result;
    }
}
//Fetch Number of Rows
function pdo_mysql_func_num_rows($result) {
    if ($result instanceof PDOStatement) {
        $num = $result->rowCount();
        if ($num === false) {
            output_error("SQL Error: " . pdo_mysql_func_error(), E_USER_ERROR);
            return false;
        }
        return $num;
    } else {
        output_error("SQL Error: Invalid result type. Expected PDOStatement, got " . gettype($result), E_USER_ERROR);
        return false;
    }
}
// Connect to MySQL database using PDO and set SQL modes
function pdo_mysql_func_connect_db($server, $username, $password, $database = null, $new_link = false) {
    global $SQLStat;

    // Set DSN (Data Source Name) for MySQL connection
    $dsn = "mysql:host=$server";

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

        // Set multiple SQL modes after a successful connection
        $sqlModes = "ANSI,ANSI_QUOTES,TRADITIONAL,STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION,NO_AUTO_VALUE_ON_ZERO";
        $result = $SQLStat->exec("SET SESSION SQL_MODE='$sqlModes';");

        // Check if setting SQL mode failed
        if ($result === false) {
            output_error("SQL Error: " . $SQLStat->errorInfo()[2], E_USER_ERROR);
            return false;
        }

        // Return the PDO instance (MySQL connection)
        return $SQLStat;

    } catch (PDOException $e) {
        // Output error if connection fails
        output_error("Connection failed: " . $e->getMessage(), E_USER_ERROR);
        return false;
    }
}
function pdo_mysql_func_disconnect_db($link = null) {
    global $SQLStat; // Assuming this is your PDO object

    // If a specific link is provided (assuming $link is a PDOStatement), close the cursor
    if (isset($link) && $link instanceof PDOStatement) {
        return $link->closeCursor();
    }
    
    // If no link is provided, we assume $SQLStat is a PDO object and we just nullify it to disconnect
    if (!isset($link) && isset($SQLStat)) {
        $SQLStat = null; // This effectively disconnects from the database
        return true;
    }
    
    return false;
}
// Query Results :P
function pdo_mysql_func_result($result, $row = 0, $field = 0) {
    // Ensure that $result is a PDOStatement before calling fetch
    if ($result instanceof PDOStatement) {
        // Use PDO::FETCH_BOTH to fetch rows with both numeric and associative keys
        // Seek to the requested row using fetch
        $result->fetch(PDO::FETCH_BOTH, PDO::FETCH_ORI_ABS, $row);
        
        // Fetch the specific row
        $fetchedRow = $result->fetch(PDO::FETCH_BOTH);
        
        if ($fetchedRow === false) {
            // If no row exists at the specified index
            return null;
        }
        
        // Return the specific field from the requested row
        return isset($fetchedRow[$field]) ? $fetchedRow[$field] : null;
    } else {
        // Handle the case where the result is not a PDOStatement (e.g., for non-SELECT queries)
        output_error("SQL Error: Invalid result type. Expected PDOStatement, got " . gettype($result), E_USER_ERROR);
        return false;
    }
}
// Free Results :P
function pdo_mysql_func_free_result($result) {
	return true; }
//Fetch Results to Array
function pdo_mysql_func_fetch_array($result,$result_type=PDO::FETCH_BOTH) {
if($result_type==null) { $result_type = PDO::FETCH_BOTH; }
$row = $result->fetch($result_type);
	return $row; }
//Fetch Results to Associative Array
function pdo_mysql_func_fetch_assoc($result) {
$row = $result->fetch(PDO::FETCH_ASSOC);
	return $row; }
//Fetch Row Results
function pdo_mysql_func_fetch_row($result) {
$row = $result->fetch(PDO::FETCH_NUM);
	return $row; }
//Get Server Info
function pdo_mysql_func_server_info($link=null) {
	$result = $link->query('select sqlite_version()')->fetch()[0];
	return $result; }
//Get Client Info
function pdo_mysql_func_client_info($link=null) {
	return null; }
function pdo_mysql_func_escape_string($string,$link=null) {
global $SQLStat;
 if(isset($string)&&!is_null($string)) {
	if(isset($link)) {
		$string = $link->quote($string); }
	if(!isset($link)) {
		$string = $SQLStat->quote($string); } }
if ($string===false) {
    output_error("SQL Error: ".
pdo_mysql_func_error(),E_USER_ERROR);
	return false; }
	return $string; }
function pdo_mysql_func_pre_query($query_string, $query_vars = []) {
    if ($query_vars === null || !is_array($query_vars)) { 
        $query_vars = []; 
    }

    if (is_array($query_vars) && count($query_vars) > 0 && $query_vars[0] === null) {
        $query_vars = [];
    }

    // Escape literal ? and empty strings
    $query_string = str_replace(['\?', "''"], ['{LITERAL_QUESTION_MARK}', "{LITERAL_EMPTY_STRING}"], $query_string);

    // Replace placeholders
    $query_string = str_replace(["'%s'", '%d', '%i', '%f'], ['?', '?', '?', '?'], $query_string);

    // Remove null values from query_vars
    $query_vars = array_filter($query_vars, function($value) {
        return $value !== null;
    });

    // Count placeholders and check mismatch
    $placeholder_count = substr_count($query_string, '?');
    $params_count = count($query_vars);

    if ($placeholder_count !== $params_count) {
        output_error("SQL Placeholder Error: Mismatch between placeholders ($placeholder_count) and parameters ($params_count).", E_USER_ERROR);
        return false;
    }

    // Restore the literal ? and empty strings
    $query_string = str_replace(['{LITERAL_QUESTION_MARK}', '{LITERAL_EMPTY_STRING}'], ['?', "''"], $query_string);

    return [$query_string, $query_vars];
}
function pdo_mysql_func_set_charset($charset, $pdo = null) {
    // Check if PDO instance is provided
    if (!isset($pdo) || !($pdo instanceof PDO)) {
        output_error("Invalid PDO instance provided.", E_USER_ERROR);
        return false;
    }

    try {
        // Set the character set using an SQL command
        $result = $pdo->exec("SET NAMES '" . $charset . "'");
        if ($result === false) {
            $errorInfo = $pdo->errorInfo();
            output_error("SQL Error: " . $errorInfo[2], E_USER_ERROR);
            return false;
        }

        // Optionally set character set collation
        $result = $pdo->exec("SET CHARACTER SET '" . $charset . "'");
        if ($result === false) {
            $errorInfo = $pdo->errorInfo();
            output_error("SQL Error: " . $errorInfo[2], E_USER_ERROR);
            return false;
        }

        return true;
    } catch (PDOException $e) {
        // Handle any PDO exception
        output_error("PDO Exception: " . $e->getMessage(), E_USER_ERROR);
        return false;
    }
}

/*
if(function_exists('mysqli_set_charset')===false) {
function mysqli_set_charset($charset,$link) {
if(isset($link)) {
	$result = mysqli_func_set_charset($charset,$link); }
if(!isset($link)) {
	$result = mysqli_func_set_charset($charset); }
if ($result===false) {
    output_error("SQL Error: ".mysqli_func_error(),E_USER_ERROR);
	return false; }
	return true; } }
*/
// Get next id for stuff
function pdo_mysql_func_get_next_id($tablepre,$table,$link=null) {
	global $SQLStat;
	if(isset($link)) {
		$nid = $link->lastInsertId(); }
	if(!isset($link)) {
		$nid = $SQLStat->lastInsertId(); }
	return $nid; }
// Get number of rows for table
function pdo_mysql_func_get_num_rows($tablepre,$table,$link=null) {
   $getnextidq = 
pdo_mysql_func_pre_query("SHOW TABLE STATUS LIKE '".$tablepre.$table."'", array());
if(!isset($link)) {
	$getnextidr = 
pdo_mysql_func_query($getnextidq); }
if(isset($link)) {
	$getnextidr = 
pdo_mysql_func_query($getnextidq,$link); } 
   $getnextid = 
pdo_mysql_func_fetch_assoc($getnextidr);
   return $getnextid['Rows'];
   @pdo_mysql_func_result($getnextidr); }
// Fetch Number of Rows using COUNT in a single query
function pdo_mysql_func_count_rows($query, $link = null) {
    // Execute the query using sql_query
    $get_num_result = pdo_mysql_func_query($query, $link);
    // Fetch the count result
    $ret_num_result = pdo_mysql_func_result($get_num_result, 0);
    // Free the result resource
    @pdo_mysql_func_free_result($get_num_result); 
    return $ret_num_result; }
// Fetch Number of Rows using COUNT in a single query
function pdo_mysql_func_count_rows_alt($query, $link = null) {
    // Execute the query using sql_query
    $get_num_result = pdo_mysql_func_query($query, $link);
    // Fetch the count result
    $ret_num_result = pdo_mysql_func_result($get_num_result, 0, 'cnt');
    // Free the result resource
    @pdo_mysql_func_free_result($get_num_result); 
    return $ret_num_result; }
?>
