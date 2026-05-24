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
    Kill Register Globals (Register Globals are very lame we dont need them anyways. :P)

    $FileInfo: killglobals.php - Last Update: 8/23/2024 SVN 1023 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "killqlobals.php" || $File3Name == "/killqlobals.php") {
    require('index.php');
    exit();
}
/*
function unregister_globals() {
   if (ini_get('register_globals')) {
   $REQUEST = $_REQUEST;
   $GET = $_GET;
   $POST = $_POST;
   $COOKIE = $_COOKIE;
   if(isset($_SESSION)) {
   $SESSION = $_SESSION; }
   $FILES = $_FILES;
   $ENV = $_ENV;
   $SERVER = $_SERVER;
   foreach($GLOBALS as $key => $value) {
   if($key!='GLOBALS') {
   unset($GLOBALS[$key]); } }
   $_REQUEST = $REQUEST;
   $_GET = $GET;
   $_POST = $POST;
   $_COOKIE = $COOKIE;
   if(isset($SESSION)) {
   $_SESSION = $SESSION; }
   $_FILES = $FILES;
   $_ENV = $ENV;
   $_SERVER = $SERVER; } }
unregister_globals();
*//*
unction unregister_globals() {
    if (ini_get('register_globals')) {
        foreach (array('_REQUEST', '_GET', '_POST', '_COOKIE', '_FILES', '_ENV', '_SERVER') as $superglobal) {
            foreach ($GLOBALS[$superglobal] as $key => $value) {
                unset($GLOBALS[$key]);
            }
        }
        if (isset($_SESSION)) {
            foreach ($_SESSION as $key => $value) {
                unset($GLOBALS[$key]);
            }
        }
    }
}
unregister_globals();
*/
function unregister_globals_advanced()
{
    if (ini_get('register_globals')) {
        $superglobals = array('_REQUEST', '_GET', '_POST', '_COOKIE', '_SESSION', '_FILES', '_ENV', '_SERVER');
        foreach ($GLOBALS as $key => $value) {
            if (!in_array($key, $superglobals) && $key != 'GLOBALS') {
                unset($GLOBALS[$key]);
            }
        }
    }
}
unregister_globals_advanced();
