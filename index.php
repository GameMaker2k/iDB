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

    $FileInfo: index.php - Last Update: 8/23/2024 SVN 1023 - Author: cooldude2k $
*/
if (ini_get("register_globals")) {
    require_once('inc/misc/killglobals.php');
}
$checklowview = true;
require('preindex.php');
$usefileext = $Settings['file_ext'];
if ($ext == "noext" || $ext == "no ext" || $ext == "no+ext") {
    $usefileext = "";
}
$filewpath = $exfile['index'].$usefileext.$_SERVER['PATH_INFO'];
$idbactcheck = array("view", "lowview", "stats");

if (isset($_GET['showcategory']) && is_numeric($_GET['showcategory'])) {
    $showact = "view";
    if ($_GET['act'] == "lowview") {
        $showact = "lowview";
    }
    redirect("location", $rbasedir.url_maker($exfile['category'], $Settings['file_ext'], "act=".$showact."&id=".$_GET['showcategory'], $Settings['qstr'], $Settings['qsep'], $prexqstr['category'], $exqstr['category'], false));
    ob_clean();
    header("Content-Type: text/plain; charset=".$Settings['charset']);
    $urlstatus = 302;
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    session_write_close();
    die();
}

if (isset($_GET['showforum']) && is_numeric($_GET['showforum'])) {
    $showact = "view";
    if ($_GET['act'] == "lowview") {
        $showact = "lowview";
    }
    redirect("location", $rbasedir.url_maker($exfile['forum'], $Settings['file_ext'], "act=".$showact."&id=".$_GET['showforum'], $Settings['qstr'], $Settings['qsep'], $prexqstr['forum'], $exqstr['forum'], false));
    ob_clean();
    header("Content-Type: text/plain; charset=".$Settings['charset']);
    $urlstatus = 302;
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    session_write_close();
    die();
}

if (isset($_GET['showtopic']) && is_numeric($_GET['showtopic'])) {
    $showact = "view";
    if ($_GET['act'] == "lowview") {
        $showact = "lowview";
    }
    if (isset($_GET['showpost']) && is_numeric($_GET['showpost'])) {
        redirect("location", $rbasedir.url_maker($exfile['topic'], $Settings['file_ext'], "act=".$showact."&id=".$_GET['showtopic']."&post=".$_GET['showpost'], $Settings['qstr'], $Settings['qsep'], $prexqstr['topic'], $exqstr['topic'], false));
        ob_clean();
        header("Content-Type: text/plain; charset=".$Settings['charset']);
    }
    if (!isset($_GET['showpost'])) {
        $_GET['showpost'] = null;
    }
    if (!isset($_GET['showpost']) || !is_numeric($_GET['showpost'])) {
        if (!isset($_GET['showpage'])) {
            $_GET['showpage'] = 1;
        }
        if (!isset($_GET['showpage']) || !is_numeric($_GET['showpage'])) {
            $_GET['showpage'] = 1;
        } $urlstatus = 302;
        redirect("location", $rbasedir.url_maker($exfile['topic'], $Settings['file_ext'], "act=".$showact."&id=".$_GET['showtopic']."&page=".$_GET['showpage'], $Settings['qstr'], $Settings['qsep'], $prexqstr['topic'], $exqstr['topic'], false));
        ob_clean();
        header("Content-Type: text/plain; charset=".$Settings['charset']);
    }
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    session_write_close();
    die();
}

if (isset($_GET['showuser']) && is_numeric($_GET['showuser'])) {
    redirect("location", $rbasedir.url_maker($exfile['member'], $Settings['file_ext'], "act=view&id=".$_GET['showuser'], $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'], false));
    ob_clean();
    header("Content-Type: text/plain; charset=".$Settings['charset']);
    $urlstatus = 302;
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    session_write_close();
    die();
}

if (isset($_GET['showevent']) && is_numeric($_GET['showevent'])) {
    redirect("location", $rbasedir.url_maker($exfile['event'], $Settings['file_ext'], "act=view&id=".$_GET['showevent'], $Settings['qstr'], $Settings['qsep'], $prexqstr['event'], $exqstr['event'], false));
    ob_clean();
    header("Content-Type: text/plain; charset=".$Settings['charset']);
    $urlstatus = 302;
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    session_write_close();
    die();
}
?>

<?php $iWrappers['EXTRALINKS'] = null;
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
if ($_GET['act'] != "lowview") {
    require($SettDir['inc'].'navbar.php');
}
$iWrappers['NAVBAR'] = ob_get_clean();
ob_start("idb_suboutput_handler");
if ($_GET['act'] == null) {
    $_GET['act'] = "view";
}
if (!in_array($_GET['act'], $idbactcheck)) {
    $_GET['act'] = "view";
}
if ($_GET['act'] == "view" ||
    $_GET['act'] == "lowview") {
    require($SettDir['inc'].'forums.php');
}
if ($_GET['act'] == "view" || $_GET['act'] == "stats") {
    require($SettDir['inc'].'stats.php');
}
$iWrappers['CONTENT'] = ob_get_clean();
ob_start("idb_suboutput_handler");
require($SettDir['inc'].'endpage.php');
$iWrappers['COPYRIGHT'] = ob_get_clean();
ob_start("idb_suboutput_handler");
?>
</body>
</html>
<?php $iWrappers['HTMLEND'] = ob_get_clean();
require($SettDir['inc'].'iwrapper.php');
fix_amp($Settings['use_gzip'], $GZipEncode['Type']); ?>
