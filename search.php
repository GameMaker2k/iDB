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

    $FileInfo: search.php - Last Update: 8/23/2024 SVN 1023 - Author: cooldude2k $
*/
if (ini_get("register_globals")) {
    require_once('inc/misc/killglobals.php');
}
require('preindex.php');
$usefileext = $Settings['file_ext'];
if ($ext == "noext" || $ext == "no ext" || $ext == "no+ext") {
    $usefileext = "";
}
$filewpath = $exfile['search'].$usefileext.$_SERVER['PATH_INFO'];
$idbactcheck = array("topic", "topics", "getactive");
ob_start("idb_suboutput_handler");
?>
<link rel="search" type="application/opensearchdescription+xml" title="<?php echo $Settings['board_name']." ".$ThemeSet['TitleDivider']; ?> Search" href="<?php echo url_maker($exfile['rss'], $Settings['rss_ext'], "act=opensearch", $Settings['qstr'], $Settings['qsep'], $prexqstr['rss'], $exqstr['rss']); ?>" />
<?php $iWrappers['EXTRALINKS'] = ob_get_clean();
ob_start("idb_suboutput_handler");
$title_html = htmlentities($Settings['board_name'].$idbpowertitle, ENT_QUOTES, $Settings['charset']);
?>
<meta itemprop="title" property="og:title" content="<?php echo $title_html; ?>" />
<meta itemprop="sitename" property="og:site_name" content="<?php echo $title_html; ?>" />
<meta itemprop="title" property="twitter:title" content="<?php echo $title_html; ?>" />
<meta name="title" content="<?php echo $title_html; ?>" />
<title> <?php echo $Settings['board_name'].$idbpowertitle; ?> </title>
<?php $iWrappers['TITLETAG'] = ob_get_clean();
ob_start("idb_suboutput_handler"); ?>
</head>
<body>
<?php $iWrappers['BODYTAG'] = ob_get_clean();
ob_start("idb_suboutput_handler");
require($SettDir['inc'].'navbar.php');
$iWrappers['NAVBAR'] = ob_get_clean();
ob_start("idb_suboutput_handler");
if ($Settings['enable_search'] == "off" ||
    $GroupInfo['CanSearch'] == "no") {
    redirect("location", $rbasedir.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
    header("Content-Type: text/plain; charset=".$Settings['charset']);
    ob_clean();
    echo "Sorry you do not have permission to do a search.";
    $urlstatus = 302;
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    session_write_close();
    die();
}
if ($Settings['enable_search'] == "on" || $GroupInfo['CanSearch'] == "yes") {
    if ($_GET['act'] == "getactive") {
        $_GET['act'] = "topics";
        $_GET['type'] = "getactive";
    }
    if (isset($_GET['type']) && $_GET['type'] == "getactive") {
        $_POST['type'] = "getactive";
    }
    if (!isset($_GET['search'])) {
        $_GET['search'] = null;
    }
    if (!isset($_POST['search'])) {
        $_POST['search'] = null;
    }
    if ($_GET['search'] == null &&
        $_POST['search'] != null) {
        $_GET['search'] = $_POST['search'];
    }
    if (!isset($_GET['type'])) {
        $_GET['type'] = null;
    }
    if (!isset($_POST['type'])) {
        $_POST['type'] = null;
    }
    if ($_GET['type'] == null &&
        $_POST['type'] != null) {
        $_GET['type'] = $_POST['type'];
    }
    if (!isset($_POST['act'])) {
        $_POST['act'] = null;
    }
    if ($_GET['act'] == null || $_GET['act'] == "topic" ||
        $_POST['act'] == "topic" || $_POST['act'] == "topics") {
        $_GET['act'] = "topics";
    }
    if (!in_array($_GET['act'], $idbactcheck)) {
        $_GET['act'] = "topics";
    }
    if (!isset($_GET['msearch'])) {
        $_GET['msearch'] = null;
    }
    if (!isset($_POST['msearch'])) {
        $_POST['msearch'] = null;
    }
    if ($_GET['msearch'] == null &&
        $_POST['msearch'] != null) {
        $_GET['msearch'] = $_POST['msearch'];
    }
    if ($_GET['act'] == "topics") {
        require($SettDir['inc'].'searches.php');
    }
}
if ($_GET['act'] == "opensearch") {
    redirect("location", $rbasedir.url_maker($exfile['rss'], $Settings['file_ext'], "act=".$_GET['act'], $Settings['qstr'], $Settings['qsep'], $prexqstr['rss'], $exqstr['rss'], false));
    ob_clean();
    header("Content-Type: text/plain; charset=".$Settings['charset']);
    $urlstatus = 302;
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    session_write_close();
    die();
}
$iWrappers['CONTENT'] = ob_get_clean();
ob_start("idb_suboutput_handler");
require($SettDir['inc'].'endpage.php');
$iWrappers['COPYRIGHT'] = ob_get_clean();
ob_start("idb_suboutput_handler");
if (!isset($_GET['search'])) {
    $_GET['search'] = null;
}
?>
</body>
</html>
<?php
$iWrappers['HTMLEND'] = ob_get_clean();
require($SettDir['inc'].'iwrapper.php');
if ($_GET['search'] == null && $_GET['type'] == null) {
    change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Searching", $Settings['use_gzip'], $GZipEncode['Type']);
}
if ($_GET['search'] != null && $_GET['type'] != null) {
    change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." ".$_GET['search'], $Settings['use_gzip'], $GZipEncode['Type']);
}
if ($_GET['type'] == "getactive") {
    change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Todays Active Topics", $Settings['use_gzip'], $GZipEncode['Type']);
}
?>