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

    $FileInfo: rssfeed.php - Last Update: 8/26/2024 SVN 1048 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "rssfeed.php" || $File3Name == "/rssfeed.php") {
    require('index.php');
    exit();
}
if (!isset($_GET['debug'])) {
    $_GET['debug'] = null;
}
if (!is_numeric($_GET['id'])) {
    $_GET['id'] = null;
}
$boardsname = htmlentities($Settings['board_name'], ENT_QUOTES, $Settings['charset']);
$boardsname = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $boardsname);
$_GET['feedtype'] = strtolower($_GET['feedtype']);
if ($_GET['feedtype'] != "rss" && $_GET['feedtype'] != "atom" && $_GET['feedtype'] != "oldrss" &&
    $_GET['feedtype'] != "opml" && $_GET['feedtype'] != "opensearch") {
    $_GET['feedtype'] = "rss";
}
//$basepath = pathinfo($_SERVER['REQUEST_URI']);
/*if(dirname($_SERVER['REQUEST_URI'])!="."||
    dirname($_SERVER['REQUEST_URI'])!=null) {
$basedir = dirname($_SERVER['REQUEST_URI'])."/"; }*/
if (dirname($_SERVER['SCRIPT_NAME']) != "." ||
    dirname($_SERVER['SCRIPT_NAME']) != null) {
    $basedir = dirname($_SERVER['SCRIPT_NAME'])."/";
}
if ($basedir == null || $basedir == ".") {
    if (dirname($_SERVER['SCRIPT_NAME']) == "." ||
        dirname($_SERVER['SCRIPT_NAME']) == null) {
        $basedir = dirname($_SERVER['PHP_SELF'])."/";
    }
}
if ($basedir == "\/") {
    $basedir = "/";
}
$basedir = str_replace("//", "/", $basedir);
if ($Settings['fixpathinfo'] != "on" &&
    $Settings['fixpathinfo'] != "off" &&
    $Settings['fixpathinfo'] !== null) {
    $basedir = "/";
} $BaseURL = $basedir;
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
    $BoardURL = $prehost.$_SERVER['HTTP_HOST'].$BaseURL;
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
if ($_GET['id'] == null) {
    $_GET['id'] = "1";
}
if ($rssurlon == "on") {
    $BoardURL =  $rssurl;
}
$feedsname = basename($_SERVER['SCRIPT_NAME']);
if ($_SERVER['PATH_INFO'] != null) {
    $feedsname .= htmlentities($_SERVER['PATH_INFO'], ENT_QUOTES, $Settings['charset']);
}
if ($_SERVER['QUERY_STRING'] != null) {
    $feedsname .= "?".htmlentities($_SERVER['QUERY_STRING'], ENT_QUOTES, $Settings['charset']);
}
$checkfeedtype = "application/rss+xml";
if ($_GET['feedtype'] == "oldrss") {
    $checkfeedtype = "application/xml";
}
if ($_GET['feedtype'] == "rss") {
    $checkfeedtype = "application/rss+xml";
}
if ($_GET['feedtype'] == "atom") {
    $checkfeedtype = "application/atom+xml";
}
if ($_GET['feedtype'] == "opml") {
    $checkfeedtype = "text/x-opml";
}
if ($_GET['feedtype'] == "opensearch") {
    $checkfeedtype = "application/opensearchdescription+xml";
}
if (stristr($_SERVER['HTTP_ACCEPT'], $checkfeedtype)) {
    header("Content-Type: ".$checkfeedtype."; charset=".$Settings['charset']);
} else {
    if (stristr($_SERVER['HTTP_ACCEPT'], "application/xml")) {
        header("Content-Type: application/xml; charset=".$Settings['charset']);
    } else {
        if (stristr($_SERVER['HTTP_USER_AGENT'], "FeedValidator")) {
            header("Content-Type: application/xml; charset=".$Settings['charset']);
        } else {
            header("Content-Type: text/xml; charset=".$Settings['charset']);
        }
    }
}
header("Content-Language: en");
header("Vary: Accept");
if ($_GET['feedtype'] == "oldrss" || $_GET['feedtype'] == "rss" || $_GET['feedtype'] == "atom" || $_GET['feedtype'] == "opml") {
    $prenum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i".$ForumIgnoreList2."", array($_GET['id'])), $SQLStat);
    $prequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"id\"=%i".$ForumIgnoreList2."", array($_GET['id']));
    $preresult = sql_query($prequery, $SQLStat);
    $prei = 0;
    if ($prenum == 0) {
        redirect("location", $rbasedir.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
        sql_free_result($preresult);
        ob_clean();
        header("Content-Type: text/plain; charset=".$Settings['charset']);
        $urlstatus = 302;
        gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        session_write_close();
        die();
    }
    $preresult_array = sql_fetch_assoc($preresult);
    $ForumID = $preresult_array['id'];
    $ForumName = $preresult_array['Name'];
    $ForumName = htmlentities($ForumName, ENT_QUOTES, $Settings['charset']);
    $ForumName = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $ForumName);
    $ForumCatID = $preresult_array['CategoryID'];
    $ForumType = $preresult_array['ForumType'];
    $ForumType = strtolower($ForumType);
    sql_free_result($preresult);
    if ($PermissionInfo['CanViewForum'][$ForumID] == "no" ||
        $PermissionInfo['CanViewForum'][$ForumID] != "yes") {
        redirect("location", $rbasedir.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
        ob_clean();
        header("Content-Type: text/plain; charset=".$Settings['charset']);
        $urlstatus = 302;
        gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        session_write_close();
        die();
    }
    if ($CatPermissionInfo['CanViewCategory'][$ForumCatID] == "no" ||
        $CatPermissionInfo['CanViewCategory'][$ForumCatID] != "yes") {
        redirect("location", $rbasedir.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
        ob_clean();
        header("Content-Type: text/plain; charset=".$Settings['charset']);
        $urlstatus = 302;
        gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        session_write_close();
        die();
    }
    $gltf = array(null);
    $gltf[0] = $ForumID;
    if ($ForumType == "subforum") {
        $apcnum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."forums\" WHERE \"ShowForum\"='yes' AND \"InSubForum\"=%i".$ForumIgnoreList2." ORDER BY \"id\"", array($ForumID)), $SQLStat);
        $apcquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."forums\" WHERE \"ShowForum\"='yes' AND \"InSubForum\"=%i".$ForumIgnoreList2." ORDER BY \"id\"", array($ForumID));
        $apcresult = sql_query($apcquery, $SQLStat);
        $apci = 0;
        $apcl = 1;
        if ($apcnum >= 1) {
            while ($apci < $apcnum) {
                $apcresult_array = sql_fetch_assoc($apcresult);
                $SubsForumID = $apcresult_array['id'];
                if (isset($PermissionInfo['CanViewForum'][$SubsForumID]) &&
                    $PermissionInfo['CanViewForum'][$SubsForumID] == "yes") {
                    $gltf[$apcl] = $SubsForumID;
                    ++$apcl;
                }
                ++$apci;
            }
            sql_free_result($apcresult);
        }
    }
    $Atom = null;
    $RSS = null;
    $PreRSS = null;
    $gltnum = count($gltf);
    $glti = 0;
    while ($glti < $gltnum) {
        $ExtraIgnores = null;
        if ($PermissionInfo['CanModForum'][$gltf[$glti]] == "no") {
            $ExtraIgnores = " AND \"Closed\"<>3";
        }
        $num = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."topics\" WHERE \"ForumID\"=%i".$ExtraIgnores.$ForumIgnoreList4." ORDER BY \"LastUpdate\" DESC LIMIT %i", array($gltf[$glti],$Settings['max_topics'])), $SQLStat);
        $query = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."topics\" WHERE \"ForumID\"=%i".$ExtraIgnores.$ForumIgnoreList4." ORDER BY \"LastUpdate\" DESC LIMIT %i", array($gltf[$glti],$Settings['max_topics']));
        $result = sql_query($query, $SQLStat);
        $i = 0;
        while ($i < $num) {
            $result_array = sql_fetch_assoc($result);
            $TopicID = $result_array['id'];
            $ForumID = $result_array['ForumID'];
            $CategoryID = $result_array['CategoryID'];
            $pnum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."posts\" WHERE \"TopicID\"=%i ORDER BY \"TimeStamp\" ASC LIMIT %i", array($TopicID,1)), $SQLStat);
            $pquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."posts\" WHERE \"TopicID\"=%i ORDER BY \"TimeStamp\" ASC LIMIT %i", array($TopicID,1));
            $presult = sql_query($pquery, $SQLStat);
            $presult_array = sql_fetch_assoc($presult);
            $MyDescription = $presult_array['Post'];
            $UsersID = $result_array['UserID'];
            $GuestsName = $result_array['GuestName'];
            $renum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($UsersID)), $SQLStat);
            $requery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($UsersID));
            $reresult = sql_query($requery, $SQLStat);
            if ($renum < 1) {
                $UsersID = -1;
                $renum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($UsersID)), $SQLStat);
                $requery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."members\" WHERE \"id\"=%i LIMIT 1", array($UsersID));
                $reresult = sql_query($requery, $SQLStat);
            }
            $memrenum = sql_count_rows(sql_pre_query("SELECT COUNT(*) AS cnt FROM \"".$Settings['sqltable']."mempermissions\" WHERE \"id\"=%i LIMIT 1", array($UsersID)), $SQLStat);
            $memrequery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."mempermissions\" WHERE \"id\"=%i LIMIT 1", array($UsersID));
            $memreresult = sql_query($memrequery, $SQLStat);
            $reresult_array = sql_fetch_assoc($reresult);
            $memreresult_array = sql_fetch_assoc($memreresult);
            $UsersName = $reresult_array['Name'];
            $UsersGroupID = $reresult_array['GroupID'];
            $PreUserCanExecPHP = $memreresult_array['CanExecPHP'];
            if ($PreUserCanExecPHP != "yes" && $PreUserCanExecPHP != "no" && $PreUserCanExecPHP != "group") {
                $PreUserCanExecPHP = "no";
            }
            $PreUserCanDoHTML = $memreresult_array['CanDoHTML'];
            if ($PreUserCanDoHTML != "yes" && $PreUserCanDoHTML != "no" && $PreUserCanDoHTML != "group") {
                $PreUserCanDoHTML = "no";
            }
            $PreUserCanUseBBTags = $memreresult_array['CanUseBBTags'];
            if ($PreUserCanUseBBTags != "yes" && $PreUserCanUseBBTags != "no" && $PreUserCanUseBBTags != "group") {
                $PreUserCanUseBBTags = "no";
            }
            sql_free_result($memreresult);
            if ($UsersName == "Guest") {
                $UsersName = $GuestsName;
                if ($UsersName == null) {
                    $UsersName = "Guest";
                }
            }
            sql_free_result($reresult);
            $gquery = sql_pre_query("SELECT * FROM \"".$Settings['sqltable']."groups\" WHERE \"id\"=%i LIMIT 1", array($UsersGroupID));
            $gresult = sql_query($gquery, $SQLStat);
            $gresult_array = sql_fetch_assoc($gresult);
            $UsersGroup = $gresult_array['Name'];
            $User1CanExecPHP = $PreUserCanExecPHP;
            if ($PreUserCanExecPHP == "group") {
                $User1CanExecPHP = $gresult_array['CanExecPHP'];
            }
            if ($User1CanExecPHP != "yes" && $User1CanExecPHP != "no") {
                $User1CanExecPHP = "no";
            }
            $User1CanDoHTML = $PreUserCanDoHTML;
            if ($PreUserCanDoHTML == "group") {
                $User1CanDoHTML = $gresult_array['CanDoHTML'];
            }
            if ($User1CanDoHTML != "yes" && $User1CanDoHTML != "no") {
                $User1CanDoHTML = "no";
            }
            $User1CanUseBBTags = $PreUserCanUseBBTags;
            if ($User1CanUseBBTags == "group") {
                $User1CanUseBBTags = $gresult_array['CanUseBBTags'];
            }
            if ($User1CanUseBBTags != "yes" && $User1CanUseBBTags != "no") {
                $User1CanUseBBTags = "no";
            }
            $GroupNamePrefix = $gresult_array['NamePrefix'];
            $GroupNameSuffix = $gresult_array['NameSuffix'];
            sql_free_result($gresult);
            if ($User1CanUseBBTags == "yes") {
                $MyDescription = bbcode_parser($MyDescription);
            }
            if ($User1CanExecPHP == "no") {
                $MyDescription = preg_replace("/\[ExecPHP\](.*?)\[\/ExecPHP\]/is", "<span style=\"color: red; font-weight: bold;\">ERROR:</span> cannot execute php code.", $MyDescription);
            }
            if ($User1CanExecPHP == "yes") {
                $MyDescription = php_execute($MyDescription);
            }
            if ($User1CanDoHTML == "no") {
                $MyDescription = preg_replace("/\[DoHTML\](.*?)\[\/DoHTML\]/is", "<span style=\"color: red; font-weight: bold;\">ERROR:</span> cannot execute html.", $MyDescription);
            }
            if ($User1CanDoHTML == "yes") {
                $MyDescription = do_html_bbcode($MyDescription);
            }
            $MyDescription = text2icons($MyDescription, $Settings['sqltable'], $SQLStat);
            $MyDescription = preg_replace("/\<br\>/", "<br />", nl2br($MyDescription));
            $MyDescription = url2link($MyDescription);
            if (isset($GroupNamePrefix) && $GroupNamePrefix != null) {
                $UsersName = $GroupNamePrefix.$UsersName;
            }
            if (isset($GroupNameSuffix) && $GroupNameSuffix != null) {
                $UsersName = $UsersName.$GroupNameSuffix;
            }
            $TheTime = $result_array['TimeStamp'];
            $atomcurtime = new DateTime();
            $atomcurtime->setTimestamp($TheTime);
            $atomcurtime->setTimezone($utctz);
            $AtomTime = $atomcurtime->format("Y-m-d\TH:i:s\Z");
            //$OldRSSTime=$atomcurtime->format("Y-m-d\TH:i:s+0:00");
            $OldRSSTime = $AtomTime;
            $TheTime = $atomcurtime->format("D, j M Y G:i:s \G\M\T");
            $TopicName = $result_array['TopicName'];
            $ForumDescription = $result_array['Description'];
            if (isset($PermissionInfo['CanViewForum'][$ForumID]) &&
                $PermissionInfo['CanViewForum'][$ForumID] == "yes" &&
                isset($CatPermissionInfo['CanViewCategory'][$CategoryID]) &&
                $CatPermissionInfo['CanViewCategory'][$CategoryID] == "yes") {
                if ($_GET['feedtype'] == "atom") {
                    $CDataDescription = "<![CDATA[\n".$MyDescription."\n]]>";
                    $Atom .= '<entry>'."\n".'<title>'.$TopicName.'</title>'."\n".'<summary>'.$CDataDescription.'</summary>'."\n".'<link rel="alternate" href="'.$BoardURL.url_maker($exfilerss['topic'], $Settings['file_ext'], "act=view&id=".$TopicID."&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstrrss['topic'], $exqstrrss['topic']).'" />'."\n".'<id>'.$BoardURL.url_maker($exfilerss['topic'], $Settings['file_ext'], "act=view&id=".$TopicID."&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstrrss['topic'], $exqstrrss['topic']).'</id>'."\n".'<author>'."\n".'<name>'.$UsersName.'</name>'."\n".'</author>'."\n".'<updated>'.$AtomTime.'</updated>'."\n".'</entry>'."\n";
                }
                if ($_GET['feedtype'] == "oldrss") {
                    $CDataDescription = "<![CDATA[\n".$MyDescription."\n]]>";
                    $PreRSS .= '      <rdf:li rdf:resource="'.$BoardURL.url_maker($exfilerss['topic'], $Settings['file_ext'], "act=view&id=".$TopicID."&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstrrss['topic'], $exqstrrss['topic']).'" />'."\n";
                    $RSS .= '<item rdf:about="'.$BoardURL.url_maker($exfilerss['topic'], $Settings['file_ext'], "act=view&id=".$TopicID."&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstrrss['topic'], $exqstrrss['topic']).'">'."\n".'<title>'.$TopicName.'</title>'."\n".'<description>'.$CDataDescription.'</description>'."\n".'<dc:publisher>'.$UsersName.'</dc:publisher>'."\n".'<dc:creator>'.$UsersName.'</dc:creator>'."\n".'<dc:date>'.$OldRSSTime.'</dc:date>'."\n".'</item>'."\n";
                }
                if ($_GET['feedtype'] == "rss") {
                    $CDataDescription = "<![CDATA[\n".$MyDescription."\n]]>";
                    $RSS .= '<item>'."\n".'<pubDate>'.$TheTime.'</pubDate>'."\n".'<author>'.$UsersName.'</author>'."\n".'<title>'.$TopicName.'</title>'."\n".'<description>'.$CDataDescription.'</description>'."\n".'<link>'.$BoardURL.url_maker($exfilerss['topic'], $Settings['file_ext'], "act=view&id=".$TopicID."&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstrrss['topic'], $exqstrrss['topic']).'</link>'."\n".'<guid>'.$BoardURL.url_maker($exfilerss['topic'], $Settings['file_ext'], "act=view&id=".$TopicID."&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstrrss['topic'], $exqstrrss['topic']).'</guid>'."\n".'</item>'."\n";
                }
            }
            ++$i;
            sql_free_result($presult);
        }
        sql_free_result($result);
        ++$glti;
    }
}
$endtag = " \n";
xml_doc_start("1.0", $Settings['charset']);
if ($Settings['showverinfo'] == "on") { ?>
<!-- generator="<?php echo $VerInfo['iDB_Ver_Show']; ?>" -->
<?php } if ($Settings['showverinfo'] != "on") { ?>
<!-- generator="<?php echo $iDB; ?>" -->
<?php } echo "\n";
if ($_GET['feedtype'] == "oldrss") { ?>
<rdf:RDF 
 xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
 xmlns="http://purl.org/rss/1.0/"
 xmlns:dc="http://purl.org/dc/elements/1.1/">
<channel rdf:about="<?php echo $BoardURL.$feedsname; ?>">
 <title><?php echo $boardsname." ".$ThemeSet['TitleDivider']; ?> Viewing Forum <?php echo $ForumName; ?></title>
  <link><?php echo $BoardURL.url_maker($exfile[$ForumType], $Settings['file_ext'], "act=view&id=".$ForumID, $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType]); ?></link>
  <description>RSS Feed of the Topics in Forum <?php echo $ForumName; ?></description>
  <image rdf:resource="<?php echo $AltBoardURL.$SettDir['inc']; ?>rss.png" />
 
  <items>
    <rdf:Seq>
<?php echo $PreRSS; ?>
    </rdf:Seq>
  </items>
</channel>

<image rdf:about="<?php echo $AltBoardURL.$SettDir['inc']; ?>rss.png">
  <title><?php echo $boardsname; ?></title>
  <link><?php echo $BoardURL.url_maker($exfile[$ForumType], $Settings['file_ext'], "act=view&id=".$ForumID, $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType]); ?></link>
  <url><?php echo $AltBoardURL.$SettDir['inc']; ?>rss.png</url>
</image>
<?php echo $endtag; ?>
<?php echo "\n".$RSS."\n"; ?>/rdf:RDF>
<?php } if ($_GET['feedtype'] == "rss") { ?>
<rss version="2.0">
<channel>
   <title><?php echo $boardsname." ".$ThemeSet['TitleDivider']; ?> Viewing Forum <?php echo $ForumName; ?></title>
   <description>RSS Feed of the Topics in Forum <?php echo $ForumName; ?></description>
   <link><?php echo $BoardURL.url_maker($exfile[$ForumType], $Settings['file_ext'], "act=view&id=".$ForumID, $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType]); ?></link>
   <language>en</language>
   <?php if ($Settings['showverinfo'] == "on") { ?>
   <generator><?php echo $VerInfo['iDB_Ver_Show']; ?></generator>
   <?php } if ($Settings['showverinfo'] != "on") { ?>
   <generator><?php echo $iDB; ?></generator>
   <?php } echo "\n"; ?>
   <copyright><?php echo $SettInfo['Author']; ?></copyright>
   <ttl>120</ttl>
   <image>
	<url><?php echo $AltBoardURL.$SettDir['inc']; ?>rss.png</url>
	<title><?php echo $boardsname; ?></title>
	<link><?php echo $AltBoardURL; ?></link>
   </image>
 <?php echo $endtag; ?>
 <?php echo "\n".$RSS."\n"; ?></channel>
</rss>
<?php } if ($_GET['feedtype'] == "atom") { ?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title><?php echo $boardsname." ".$ThemeSet['TitleDivider']; ?> Viewing Forum <?php echo $ForumName; ?></title>
   <subtitle>Atom Feed of the Topics in Forum <?php echo $ForumName; ?></subtitle>
   <link rel="self" href="<?php echo $BoardURL.$feedsname; ?>" />
   <id><?php echo $BoardURL.url_maker($exfile[$ForumType], $Settings['file_ext'], "act=view&id=".$ForumID, $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType]); ?></id>
   <updated><?php echo gmdate("Y-m-d\TH:i:s\Z"); ?></updated>
   <?php if ($Settings['showverinfo'] == "on") { ?>
   <generator><?php echo $VerInfo['iDB_Ver_Show']; ?></generator>
   <?php } if ($Settings['showverinfo'] != "on") { ?>
   <generator><?php echo $iDB; ?></generator>
   <?php } ?>
  <icon><?php echo $AltBoardURL.$SettDir['inc']; ?>rss.png</icon>
 <?php echo $endtag; ?>
 <?php echo "\n".$Atom."\n"; ?>
</feed>
<?php } if ($_GET['feedtype'] == "opml") { ?>
<opml version="1.0">
  <head>
    <dateCreated><?php echo gmdate("d-M-Y"); ?></dateCreated>
  </head>
  <body>
    <?php /*<!--
    <outline text="<?php echo $ForumName; ?> Topics RSS 1.0 Feed" type="link" url="<?php echo $BoardURL.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index']); ?>" htmlUrl="<?php echo $BoardURL.url_maker($exfile[$ForumType],$Settings['file_ext'],"act=view&id=".$ForumID,$Settings['qstr'],$Settings['qsep'],$prexqstr[$ForumType],$exqstr[$ForumType]); ?>" xmlUrl="<?php echo $BoardURL.url_maker($exfile['rss'],$Settings['rss_ext'],"act=oldrss&id=".$ForumID,$Settings['qstr'],$Settings['qsep'],$prexqstr['rss'],$exqstr['rss']); ?>" />
    -->*/ ?>
    <outline text="<?php echo $ForumName; ?> Topics RSS 2.0 Feed" type="rss" url="<?php echo $BoardURL.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index']); ?>" htmlUrl="<?php echo $BoardURL.url_maker($exfile[$ForumType], $Settings['file_ext'], "act=view&id=".$ForumID, $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType]); ?>" xmlUrl="<?php echo $BoardURL.url_maker($exfile['rss'], $Settings['rss_ext'], "act=rss&id=".$ForumID, $Settings['qstr'], $Settings['qsep'], $prexqstr['rss'], $exqstr['rss']); ?>" />
    <outline text="<?php echo $ForumName; ?> Topics Atom Feed" type="atom" url="<?php echo $BoardURL.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index']); ?>" htmlUrl="<?php echo $BoardURL.url_maker($exfile[$ForumType], $Settings['file_ext'], "act=view&id=".$ForumID, $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType]); ?>" xmlUrl="<?php echo $BoardURL.url_maker($exfile['rss'], $Settings['rss_ext'], "act=atom&id=".$ForumID, $Settings['qstr'], $Settings['qsep'], $prexqstr['rss'], $exqstr['rss']); ?>" />
  </body>
</opml>
<?php echo $endtag; ?>
<?php } if ($_GET['feedtype'] == "opensearch") { ?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/"
                       xmlns:moz="http://www.mozilla.org/2006/browser/search/">
<ShortName><?php echo $boardsname." ".$ThemeSet['TitleDivider']; ?> Search</ShortName>
<Description><?php echo $SettInfo['Description']; ?></Description>
<InputEncoding><?php echo $Settings['charset']; ?></InputEncoding>
<Image width="16" height="16" type="image/x-icon"><?php echo $AltBoardURL.$ThemeSet['FavIcon']; ?></Image>
<Url type="text/html" method="POST" template="<?php echo $BoardURL.url_maker("search", $Settings['file_ext'], null, "search", "search"); ?>">
  <Param name="act" value="topics"/>
  <Param name="search" value="{searchTerms}"/>
  <Param name="type" value="wildcard"/>
  <Param name="page" value="1"/>
</Url>
<?php echo $endtag; ?>
<moz:SearchForm><?php echo $BoardURL.url_maker("search", $Settings['file_ext'], null, "search", "search"); ?></moz:SearchForm>
</OpenSearchDescription>
<?php } if ($_GET['debug'] == "true" || $_GET['debug'] == "on") {
    function execution_time($starttime)
    {
        list($uetime, $etime) = explode(" ", microtime());
        $endtime = $uetime + $etime;
        return bcsub($endtime, $starttime, 4);
    }
    echo "<!-- execution_time=\"".execution_time($starttime)."\" -->";
}
gzip_page($Settings['use_gzip'], $GZipEncode['Type']); ?>
