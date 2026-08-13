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

    $FileInfo: endpage.php - Last Update: 8/23/2024 SVN 1023 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "endpage.php" || $File3Name == "/endpage.php") {
    require('index.php');
    exit();
}
if (!isset($_GET['time'])) {
    $_GET['time'] = true;
}
// BUGFIX: "$_GET['time'] == true" is a loose comparison, and every non-empty
// string is loosely equal to true -- so ?time=hide and ?time=off still showed
// the clock and there was no way to turn it off.
if ($_GET['time'] === true || $_GET['time'] === "show"
    || $_GET['time'] === "true" || $_GET['time'] === "on" || $_GET['time'] === "1") {
    if (!isset($_SESSION['iDBTimeFormat'])) {
        $_SESSION['iDBTimeFormat'] = "g:i A";
    }
    if (!isset($_SESSION['iDBDateFormat'])) {
        $_SESSION['iDBDateFormat'] = "M jS Y";
    }
    $MyDST = $usercurtime->format("P");
    $MyTimeNow = $usercurtime->format($_SESSION['iDBTimeFormat']);
    $MyFullTimeNow = $usercurtime->format($_SESSION['iDBDateFormat'].", ".$_SESSION['iDBTimeFormat']);
    if (!isset($TimeSign)) {
        $TimeSign = "";
    }
    $endpagevar = $endpagevar."<br />The time now is <span class=\"ctimenow\" title=\"".$MyFullTimeNow."\">".$MyTimeNow."</span> ".$ThemeSet['LineDivider']." All times are UTC ".$TimeSign." ".$MyDST;
}
if (function_exists("bcsub") == false) {
    function bcsub($left_operand, $right_operand, $scale = 0)
    {
        $lof = floatval($left_operand);
        $rof = floatval($right_operand);
        return sprintf("%0.".$scale."f", $lof - $rof);
    }
}
if (!function_exists('execution_time')) {
    // BUGFIX: a bare declaration is a fatal redeclare if endpage.php is
    // included more than once in a request.
    function execution_time($starttime)
    {
        list($uetime, $etime) = explode(" ", microtime());
        $endtime = $uetime + $etime;
        return bcsub($endtime, $starttime, 4);
    }
}
if (!isset($_GET['debug'])) {
    $_GET['debug'] = null;
}
if ($_GET['debug'] === "true" || $_GET['debug'] === "on") {
    $endpagevar = $endpagevar."<br />\nNumber of Queries: ".$NumQueries." ".$ThemeSet['LineDivider']." Execution Time: ".execution_time($starttime).$ThemeSet['LineDivider']."<a href=\"http://validator.w3.org/check/referer?verbose=1\" title=\"Validate HTML\" onclick=\"window.open(this.href);return false;\">HTML</a>".$ThemeSet['LineDivider']."<a href=\"http://jigsaw.w3.org/css-validator/check/referer?profile=css3\" title=\"Validate CSS\" onclick=\"window.open(this.href);return false;\">CSS</a>";
}
$endpagevar = $endpagevar."</div><div class=\"DivEndPage\">&#160;</div>\n";
echo $endpagevar;
session_write_close();
//session_write_close();
