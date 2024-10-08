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

    $FileInfo: filename.php - Last Update: 8/23/2024 SVN 1023 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "filename.php" || $File3Name == "/filename.php") {
    require('index.php');
    exit();
}
// Check and set stuff
if (dirname($_SERVER['SCRIPT_NAME']) != ".") {
    $basedir = dirname($_SERVER['SCRIPT_NAME'])."/";
}
if (dirname($_SERVER['SCRIPT_NAME']) == ".") {
    $basedir = dirname($_SERVER['PHP_SELF'])."/";
}
if ($basedir == "\/") {
    $basedir = "/";
}
$basedir = str_replace("//", "/", $basedir);
$cbasedir = $basedir;
if ($Settings['fixbasedir'] != null && $Settings['fixbasedir'] != "off") {
    $basedir = $Settings['fixbasedir'];
}
if ($Settings['fixcookiedir'] != null && $Settings['fixcookiedir'] != "") {
    $cbasedir = $Settings['fixcookiedir'];
}
$BaseURL = $basedir;
if (!isset($_SERVER['HTTPS'])) {
    $_SERVER['HTTPS'] = 'off';
}
if ($_SERVER['HTTPS'] == "on") {
    $prehost = "https://";
}
if ($_SERVER['HTTPS'] != "on") {
    $prehost = "http://";
}
if ($Settings['idburl'] == "localhost" || $Settings['idburl'] == null) {
    $rssurl = $prehost.$_SERVER['HTTP_HOST'].$BaseURL;
}
if ($Settings['idburl'] != "localhost" && $Settings['idburl'] != null) {
    $rssurlon = "on";
    $rssurl = $Settings['idburl'];
}
if ($Settings['rssurl'] != null && $Settings['rssurl'] != "") {
    $rssurlon = "on";
    $rssurl = $Settings['rssurl'];
}
function getGitRevision($GitRevN)
{
    // Use a regular expression to extract the revision number
    if (preg_match('/\$Id:\s+([a-f0-9]{40})\s+\$/', $GitRevN, $matches)) {
        return $matches[1];
    }
    return null; // Return null if no match is found
}
require_once($SettDir['inc'].'versioninfo.php');
//File naming stuff. <_<
$exfile = array();
$exfilerss = array();
$exqstr = array();
$exqstrrss = array();
$exfile['calendar'] = 'calendar';
$prexqstr['calendar'] = null;
$exqstr['calendar'] = null;
$exfile['category'] = 'category';
$prexqstr['category'] = null;
$exqstr['category'] = null;
$exfile['event'] = 'event';
$prexqstr['event'] = null;
$exqstr['event'] = null;
$exfile['forum'] = 'forum';
$prexqstr['forum'] = null;
$exqstr['forum'] = null;
$exfile['index'] = 'index';
$prexqstr['index'] = null;
$exqstr['index'] = null;
$exfile['member'] = 'member';
$prexqstr['member'] = null;
$exqstr['member'] = null;
$exfile['messenger'] = 'messenger';
$prexqstr['messenger'] = null;
$exqstr['messenger'] = null;
$exfile['profile'] = 'profile';
$prexqstr['profile'] = null;
$exqstr['profile'] = null;
$exfile['rss'] = 'rss';
$prexqstr['rss'] = null;
$exqstr['rss'] = null;
$exfile['search'] = 'search';
$prexqstr['search'] = null;
$exqstr['search'] = null;
$exfile['subforum'] = 'subforum';
$prexqstr['subforum'] = null;
$exqstr['subforum'] = null;
$exfile['subcategory'] = 'subcategory';
$prexqstr['subcategory'] = null;
$exqstr['subcategory'] = null;
$exfile['topic'] = 'topic';
$prexqstr['topic'] = null;
$exqstr['topic'] = null;
$exfile['redirect'] = 'forum';
$prexqstr['redirect'] = null;
$exqstr['redirect'] = null;
$exfile['admin'] = 'admin';
$prexqstr['admin'] = null;
$exqstr['admin'] = null;
$exfile['modcp'] = 'modcp';
$prexqstr['modcp'] = null;
$exqstr['modcp'] = null;
$exfilejs['javascript'] = 'javascript';
$prexqstrjs['javascript'] = null;
$exqstrjs['javascript'] = null;
$exfilerss['forum'] = 'forum';
$prexqstrrss['forum'] = null;
$exqstrrss['forum'] = null;
$exfilerss['subforum'] = 'subforum';
$prexqstrrss['subforum'] = null;
$exqstrrss['subforum'] = null;
$exfilerss['subcategory'] = 'subcategory';
$prexqstrrss['subcategory'] = null;
$exqstrrss['subcategory'] = null;
$exfilerss['redirect'] = 'forum';
$prexqstrrss['redirect'] = null;
$exqstrrss['redirect'] = null;
$exfilerss['topic'] = 'topic';
$prexqstrrss['topic'] = null;
$exqstrrss['topic'] = null;
$exfilerss['category'] = 'category';
$prexqstrrss['category'] = null;
$exqstrrss['category'] = null;
$exfilerss['event'] = 'event';
$prexqstrrss['event'] = null;
$exqstrrss['event'] = null;
