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

    $FileInfo: html5.php - Last Update: 8/23/2024 SVN 1023 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "html5.php" || $File3Name == "/html5.php") {
    require('index.php');
    exit();
}
$XHTML5 = false;
// Check to see if we serv the file as html or xhtml
// if we do xhtml we also check to see if user's browser
// can dispay if or else fallback to html
if ($Settings['output_type'] == "html") {
    $ccstart = "//<!--";
    $ccend = "//-->";
    $XHTML5 = false;
    header("Content-Type: text/html; charset=".$Settings['charset']);
}
if ($Settings['output_type'] == "xhtml") {
    if (stristr($_SERVER['HTTP_ACCEPT'], "application/xhtml+xml")) {
        $ccstart = "//<![CDATA[";
        $ccend = "//]]>";
        $XHTML5 = true;
        header("Content-Type: application/xhtml+xml; charset=".$Settings['charset']);
    } else {
        if (stristr($_SERVER['HTTP_USER_AGENT'], "W3C_Validator")) {
            $ccstart = "//<![CDATA[";
            $ccend = "//]]>";
            $XHTML5 = true;
            header("Content-Type: application/xhtml+xml; charset=".$Settings['charset']);
        } else {
            $ccstart = "//<!--";
            $ccend = "//-->";
            $XHTML5 = false;
            header("Content-Type: text/html; charset=".$Settings['charset']);
        }
    }
}
if ($Settings['output_type'] != "xhtml") {
    if ($Settings['output_type'] != "html") {
        $ccstart = "//<!--";
        $ccend = "//-->";
        $XHTML5 = false;
        header("Content-Type: text/html; charset=".$Settings['charset']);
    }
}
if ($checklowview === true && $_GET['act'] == "lowview") {
    $ThemeSet['CSSType'] = "lowview";
    $ThemeSet['ThemeName'] = $OrgName." Low Theme";
    $ThemeSet['ThemeMaker'] = $iDB_Author;
    $ThemeSet['ThemeVersion'] = $VER1[0].".".$VER1[1].".".$VER1[2];
    $ThemeSet['ThemeVersionType'] = $VER2[0];
    $ThemeSet['ThemeSubVersion'] = $VER2[2]." ".$SubVerN;
    $ThemeSet['MakerURL'] = $iDBHome."support/?act=lowview";
    $ThemeSet['CopyRight'] = $ThemeSet['ThemeName']." was made by <a href=\"".$ThemeSet['MakerURL']."\" title=\"".$ThemeSet['ThemeMaker']."\">".$ThemeSet['ThemeMaker']."</a>";
    $ThemeInfo['ThemeName'] = $ThemeSet['ThemeName'];
    $ThemeInfo['ThemeMaker'] = $ThemeSet['ThemeMaker'];
    $ThemeInfo['ThemeVersion'] = $ThemeSet['ThemeVersion'];
    $ThemeInfo['ThemeVersionType'] = $ThemeSet['ThemeVersionType'];
    $ThemeInfo['ThemeSubVersion'] = $ThemeSet['ThemeSubVersion'];
    $ThemeInfo['MakerURL'] = $ThemeSet['MakerURL'];
    $ThemeInfo['CopyRight'] = $ThemeSet['CopyRight'];
}
if ($ThemeSet['CSSType'] != "import" &&
   $ThemeSet['CSSType'] != "link" &&
   $ThemeSet['CSSType'] != "lowview" &&
   $ThemeSet['CSSType'] != "xml" &&
   $ThemeSet['CSSType'] != "sql") {
    $ThemeSet['CSSType'] = "import";
}
header("Content-Style-Type: text/css");
header("Content-Script-Type: text/javascript");
if ($Settings['showverinfo'] != "on") {
    $iDBURL1 = "<a href=\"".$iDBHome."\" title=\"".$iDB."\" onclick=\"window.open(this.href);return false;\">";
}
if ($Settings['showverinfo'] == "on") {
    $iDBURL1 = "<a href=\"".$iDBHome."\" title=\"".$VerInfo['iDB_Ver_Show']."\" onclick=\"window.open(this.href);return false;\">";
}
$GM2kURL = "<a href=\"".$GM2kHome."\" title=\"".$GM2k."\" onclick=\"window.open(this.href);return false;\">".$GM2k."</a>";
$csryear = "2004";
$cryear = date("Y");
if ($cryear <= 2004) {
    $cryear = "2005";
}
$BSDL = "<a href=\"".url_maker($exfile['index'], $Settings['file_ext'], "act=bsd", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'])."\" title=\"".$RName." is dual-licensed under the Revised BSD License\">BSDL</a>";
$GPL = "<a href=\"".url_maker($exfile['index'], $Settings['file_ext'], "act=bsd", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'])."\" title=\"".$RName." is dual-licensed under the Gnu General Public License\">GPL</a>";
$DualLicense = $BSDL." &amp; ".$GPL;
$extext = null;
if ($checklowview !== true) {
    $extext = "<a href=\"".url_maker($exfile['index'], $Settings['file_ext'], "act=lowview", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'])."\">Low-Version</a>";
}
if ($checklowview === true && $_GET['act'] != "lowview") {
    $extext = "<a href=\"".url_maker($exfile['index'], $Settings['file_ext'], "act=lowview", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'])."\">Low-Version</a>";
}
if ($checklowview === true && $_GET['act'] == "lowview") {
    $extext = "<a href=\"".url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'])."\">High-Version</a>";
}
$endpagevar = "<div class=\"copyright\">Powered by ".$iDBURL1.$RName."</a> &#169; ".$GM2kURL." @ ".$csryear." - ".$cryear." <br />\n".$ThemeSet['CopyRight'];
header("Content-Language: en");
header("X-Robots-Tag: noindex, noarchive, nofollow, noimageindex, notranslate, nosnippet");
header("X-Frame-Options: SAMEORIGIN");
header("Cross-Origin-Resource-Policy: same-origin");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: no-referrer-when-downgrade");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("X-Content-Type-Options: nosniff");
header("Vary: Accept-Language, Accept-Encoding, User-Agent, Cookie, Referer, X-Requested-With");
header("Accept-CH: Accept-CH: Sec-CH-UA, Sec-CH-UA-Platform, Sec-CH-UA-Mobile, Sec-CH-UA-Full-Version, Sec-CH-UA-Full-Version-List, Sec-CH-UA-Platform-Version, Sec-CH-UA-Arch, Sec-CH-UA-Bitness, Sec-CH-UA-Model, Sec-CH-Viewport-Width, Sec-CH-Viewport-Height, Sec-CH-Lang, Sec-CH-Save-Data, Sec-CH-Width, Sec-CH-DPR, Sec-CH-Device-Memory, Sec-CH-RTT, Sec-CH-Downlink, Sec-CH-ECT, Sec-CH-Prefers-Color-Scheme, Sec-CH-Prefers-Reduced-Motion, Sec-CH-Prefers-Reduced-Transparency, Sec-CH-Prefers-Contrast, Sec-CH-Forced-Colors");
// Check if we are on a secure HTTP connection
if (isset($_SERVER['HTTPS'])) {
    $prehost = "https://";
    ;
}
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
    $prehost = "https://";
}
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "off") {
    $prehost = "http://";
}
if (!isset($_SERVER['HTTPS'])) {
    $prehost = "http://";
}
// Get the board's url
if ($Settings['idburl'] == "localhost" || $Settings['idburl'] == null) {
    $BoardURL = $prehost.$_SERVER['HTTP_HOST'].$basedir;
}
if ($Settings['idburl'] != "localhost" && $Settings['idburl'] != null) {
    $BoardURL = $Settings['idburl'];
    if ($Settings['qstr'] != "/") {
        $AltBoardURL = $BoardURL;
    }
    if ($Settings['qstr'] == "/") {
        $AltBoardURL = preg_replace("/\/$/", "", $BoardURL);
    }
}
// Get the html level
if ($Settings['html_level'] != "Strict") {
    if ($Settings['html_level'] != "Transitional") {
        $Settings['html_level'] = "Transitional";
    }
}
// HTML Document Starts
if ($XHTML5 === false) {
    ?>
<!DOCTYPE html>
<?php // HTML meta tags and other html, head tags?>
<html lang="en">
<?php } if ($XHTML5 === true) { ?>
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<?php } ?>
<head>
<?php if ($XHTML5 === false) { ?>
<meta charset="<?php echo $Settings['charset']; ?>">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $Settings['charset']; ?>">
<meta name="language" content="english">
<meta name="viewport" id="viewport" content="width=device-width, initial-scale=0.5">
<?php
if (!isset($_SERVER['HTTP_USER_AGENT'])) {
    $_SERVER['HTTP_USER_AGENT'] = "";
}
    if (strpos($_SERVER['HTTP_USER_AGENT'], "msie") &&
        !strpos($_SERVER['HTTP_USER_AGENT'], "opera")) { ?>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<?php } if (strpos($_SERVER['HTTP_USER_AGENT'], "chromeframe")) { ?>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<?php }
} if ($XHTML5 === true) { ?>
<meta charset="<?php echo $Settings['charset']; ?>" />
<meta http-equiv="X-Content-Type-Options" content="nosniff">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $Settings['charset']; ?>" />
<meta name="language" content="english" />
<?php
if (!isset($_SERVER['HTTP_USER_AGENT'])) {
    $_SERVER['HTTP_USER_AGENT'] = "";
}
    if (strpos($_SERVER['HTTP_USER_AGENT'], "msie") &&
        !strpos($_SERVER['HTTP_USER_AGENT'], "opera")) { ?>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php } if (strpos($_SERVER['HTTP_USER_AGENT'], "chromeframe")) { ?>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
<?php }
} ?>
<meta itemprop="url" property="og:url" content="<?php echo $BoardURL; ?>" />
<base href="<?php echo $BoardURL; ?>" />
<?php if ($Settings['showverinfo'] == "on") { ?>
<meta name="Generator" content="<?php echo $VerInfo['iDB_Ver_Show']; ?>" />
<?php } if ($Settings['showverinfo'] != "on") { ?>
<meta name="Generator" content="<?php echo $iDB; ?>" />
<?php } echo "\n";
if (!isset($SettInfo['Author'])) {
    $SettInfo['Author'] = "";
}
if (!isset($ThemeSet['ThemeMaker'])) {
    $ThemeSet['ThemeMaker'] = "";
}
if (!isset($SettInfo['Keywords'])) {
    $SettInfo['Keywords'] = "";
}
if (!isset($SettInfo['Description'])) {
    $SettInfo['Description'] = "";
}
if (!isset($Settings['js_ext'])) {
    $Settings['js_ext'] = ".js";
}
?>
<meta name="Author" content="<?php echo $SettInfo['Author']; ?>" />
<meta name="web_author" content="<?php echo $SettInfo['Author']; ?>" />
<meta name="rating" content="general" />
<meta name="Designer" content="<?php echo $ThemeSet['ThemeMaker']; ?>" />
<meta name="Publisher" content="<?php echo $GM2k; ?>" />
<meta name="Keywords" content="<?php echo $SettInfo['Keywords']; ?>" />
<meta name="Description" content="<?php echo $SettInfo['Description']; ?>" />
<meta itemprop="description" property="og:description" content="<?php echo $SettInfo['Description']; ?>" />
<meta itemprop="description" property="twitter:description" content="<?php echo $SettInfo['Description']; ?>" />
<meta name="viewport" id="viewport" content="width=device-width, initial-scale=1.0">
<style type="text/css">
  /* Apply styles to devices without hover (touch devices like smartphones and tablets) */
  @media (hover: none) and (orientation: portrait) {
    body {
      width: 200%; /* Make the body wider in portrait */
    }
  }

  @media (hover: none) and (orientation: landscape) {
    body {
      width: 100%; /* Fit to the screen in landscape */
    }
  }
</style>
<meta name="HandheldFriendly" content="true" />
<meta name="ROBOTS" content="Index, FOLLOW" />
<meta name="GOOGLEBOT" content="Index, FOLLOW" />
<meta name="revisit-after" content="7 days" />
<meta name="distribution" content="web" />
<meta itemprop="type" property="og:type" content="forum" />
<meta itemprop="card" property="twitter:card" content="summary_large_image" />
<?php if ($Settings['showverinfo'] == "on") { ?>
<!-- generator="<?php echo $VerInfo['iDB_Ver_Show']; ?>" -->
<?php } if ($Settings['showverinfo'] != "on") { ?>
<!-- generator="<?php echo $iDB; ?>" -->
<?php } echo "\n"; ?>

<script type="text/javascript" src="<?php echo url_maker($exfilejs['javascript'], $Settings['js_ext'], null, $Settings['qstr'], $Settings['qsep'], $prexqstrjs['javascript'], $exqstrjs['javascript']); ?>"></script>
<script type="text/javascript">
 var tzname=Intl.DateTimeFormat().resolvedOptions().timeZone;
 document.cookie = "getusertz="+tzname;
</script>
<?php echo "\n";
if ($ThemeSet['CSSType'] == "import") { ?>
<style type="text/css">
/* Import the theme css file */
<?php echo "\n@import url(\"".$ThemeSet['CSS']."\");\n"; ?>
</style>
<?php } if ($ThemeSet['CSSType'] == "sql") { ?>
<style type="text/css">
<?php echo $ThemeSet['CSS']; ?>
</style>
<?php } if ($ThemeSet['CSSType'] == "link") { ?>
<link rel="prefetch alternate stylesheet" href="<?php echo $ThemeSet['CSS']; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $ThemeSet['CSS']; ?>" />
<?php } if ($ThemeSet['CSSType'] == "lowview") { ?>
<style type="text/css">
/* (Low View / Lo-Fi ) version start */
body {
background-color: #FFFFFF;
color: #000000;
font-family: Verdana, Tahoma, Arial, Trebuchet MS, Sans-Serif, Georgia, Courier, Times New Roman, Serif;
font-size: 16px;
margin: 20px;
padding: 0px;
}
.copyright {
text-align: center;
font-family: Sans-Serif;
font-size: 12px;
line-height: 11px;
color: #000000;
}
.EditReply {
color: #000000;
font-size: 9px;
}
</style>
<?php }
if ($ThemeSet['FavIcon'] != null) { ?>
<link rel="icon" href="<?php echo $ThemeSet['FavIcon']; ?>" />
<link rel="shortcut icon" href="<?php echo $ThemeSet['FavIcon']; ?>" />
<meta itemprop="image" property="og:image" content="<?php echo $BoardURL.$ThemeSet['OpenGraph']; ?>" />
<meta itemprop="image" property="twitter:image" content="<?php echo $BoardURL.$ThemeSet['OpenGraph']; ?>" />
<?php } ?>
