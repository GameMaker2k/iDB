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

    $FileInfo: useragents.php - Last Update: 8/23/2024 SVN 1023 - Author: cooldude2k $
*/
// User Agent Checker
function user_agent_check($user_agent)
{
    global $Settings, $UserAgentName, $AltUserAgentName, $RName, $AltName;
    if (!isset($Settings['log_spiders_bots'])) {
        $Settings['log_spiders_bots'] = "yes";
    }
    if ($Settings['log_spiders_bots'] !== "yes") {
        return false;
    }
    if (preg_match("/".preg_quote("; 008/", "/")."/i", $user_agent)) {
        return "80legs";
    }
    if (preg_match("/".preg_quote("Aboundex", "/")."/i", $user_agent)) {
        return "Aboundex";
    }
    if (preg_match("/".preg_quote("AboutUsBot", "/")."/i", $user_agent)) {
        return "AboutUs";
    }
    if (preg_match("/".preg_quote("AhrefsBot", "/")."/i", $user_agent)) {
        return "Ahrefs";
    }
    if (preg_match("/".preg_quote("ia_archiver", "/")."/i", $user_agent)) {
        return "Alexa";
    }
    if (preg_match("/".preg_quote("aiHitBot", "/")."/i", $user_agent)) {
        return "aiHit";
    }
    if (preg_match("/".preg_quote("AlkalineBot", "/")."/i", $user_agent)) {
        return "Alkaline";
    }
    if (preg_match("/".preg_quote("AltaVista", "/")."/i", $user_agent)) {
        return "AltaVista";
    }
    if (preg_match("/".preg_quote("Ask Jeeves", "/")."/i", $user_agent)) {
        return "Ask Jeeves";
    }
    if (preg_match("/".preg_quote("Teoma", "/")."/i", $user_agent)) {
        return "Ask Jeeves";
    }
    if (preg_match("/".preg_quote("WhatCMS", "/")."/i", $user_agent)) {
        return "WhatCMS";
    }
    if (preg_match("/".preg_quote("askpeter_bot", "/")."/i", $user_agent)) {
        return "Ask Peter";
    }
    if (preg_match("/".preg_quote("ASPseek", "/")."/i", $user_agent)) {
        return "ASPseek";
    }
    if (preg_match("/".preg_quote("Baiduspider", "/")."/i", $user_agent)) {
        return "Baidu";
    }
    if (preg_match("/".preg_quote("BaiduImagespider", "/")."/i", $user_agent)) {
        return "Baidu";
    }
    if (preg_match("/".preg_quote("Baidu", "/")."/i", $user_agent)) {
        if (!preg_match("/".preg_quote("BIDUBrowser", "/")."/i", $user_agent)) {
            return false;
        }
        return "Baidu";
    }
    if (preg_match("/".preg_quote("bingbot", "/")."/i", $user_agent)) {
        return "Bing";
    }
    if (preg_match("/".preg_quote("msnbot", "/")."/i", $user_agent)) {
        return "Bing";
    }
    if (preg_match("/".preg_quote("AdIdxBot", "/")."/i", $user_agent)) {
        return "Bing";
    }
    if (preg_match("/".preg_quote("CatchBot", "/")."/i", $user_agent)) {
        return "CatchBot";
    }
    if (preg_match("/".preg_quote("Cityreview Robot", "/")."/i", $user_agent)) {
        return "Cityreview";
    }
    if (preg_match("/".preg_quote("CCBot", "/")."/i", $user_agent)) {
        return "CommonCrawl";
    }
    if (preg_match("/".preg_quote("cmsworldmap.com", "/")."/i", $user_agent)) {
        return "CMSWorldMap";
    }
    if (preg_match("/".preg_quote("CSE HTML Validator", "/")."/i", $user_agent)) {
        return "CSE HTML Validator";
    }
    if (preg_match("/".preg_quote("discobot", "/")."/i", $user_agent)) {
        return "DiscoveryEngine";
    }
    if (preg_match("/".preg_quote("DnsQueries", "/")."/i", $user_agent)) {
        return "DNSQueries";
    }
    if (preg_match("/".preg_quote("Domnutch-Bot", "/")."/i", $user_agent)) {
        return "Nutch";
    }
    if (preg_match("/".preg_quote("DotBot", "/")."/i", $user_agent)) {
        return "DotBot";
    }
    if (preg_match("/".preg_quote("DuckDuckBot", "/")."/i", $user_agent)) {
        return "DuckDuckGo";
    }
    if (preg_match("/".preg_quote("Exabot", "/")."/i", $user_agent)) {
        return "Exabot";
    }
    if (preg_match("/".preg_quote("Ezooms", "/")."/i", $user_agent)) {
        return "Ezooms";
    }
    if (preg_match("/".preg_quote("facebookexternalhit", "/")."/i", $user_agent)) {
        return "Facebook";
    }
    if (preg_match("/".preg_quote("Feedtrace-bot", "/")."/i", $user_agent)) {
        return "FeedtraceBot";
    }
    if (preg_match("/".preg_quote("findfiles.net", "/")."/i", $user_agent)) {
        return "FindFiles Bot";
    }
    if (preg_match("/".preg_quote("FreeWebMonitoring", "/")."/i", $user_agent)) {
        return "FreeWebMonitoring";
    }
    if (preg_match("/".preg_quote("FlightDeckReportsBot", "/")."/i", $user_agent)) {
        return "FlightDeck Reports";
    }
    if (preg_match("/".preg_quote("GIDBot", "/")."/i", $user_agent)) {
        return "GIDNetwork";
    }
    if (preg_match("/".preg_quote("ichiro", "/")."/i", $user_agent)) {
        return "Goo Japan";
    }
    if (preg_match("/".preg_quote("moget", "/")."/i", $user_agent)) {
        return "Goo Japan";
    }
    if (preg_match("/".preg_quote("mogimogi", "/")."/i", $user_agent)) {
        return "Goo Japan";
    }
    if (preg_match("/".preg_quote("AdsBot-Google", "/")."/i", $user_agent)) {
        return "Google";
    }
    if (preg_match("/".preg_quote("FeedFetcher-Google", "/")."/i", $user_agent)) {
        return "Google";
    }
    if (preg_match("/".preg_quote("GoogleProducer", "/")."/i", $user_agent)) {
        return "Google";
    }
    if (preg_match("/".preg_quote("Storebot-Google", "/")."/i", $user_agent)) {
        return "Google";
    }
    if (preg_match("/".preg_quote("APIs-Google", "/")."/i", $user_agent)) {
        return "Google";
    }
    if (preg_match("/".preg_quote("Google-InspectionTool", "/")."/i", $user_agent)) {
        return "Google";
    }
    if (preg_match("/".preg_quote("AppEngine-Google", "/")."/i", $user_agent)) {
        return "Google";
    }
    if (preg_match("/".preg_quote("Googlebot", "/")."/i", $user_agent)) {
        return "Google";
    }
    if (preg_match("/".preg_quote("Mediapartners-Google", "/")."/i", $user_agent)) {
        return "Google";
    }
    if (preg_match("/".preg_quote("ChatGPT-User", "/")."/i", $user_agent)) {
        return "ChatGPT";
    }
    if (preg_match("/".preg_quote("Gigabot", "/")."/i", $user_agent)) {
        return "Gigablast";
    }
    if (preg_match("/".preg_quote("Grub", "/")."/i", $user_agent)) {
        return "GrubBot";
    }
    if (preg_match("/".preg_quote("Hailoobot", "/")."/i", $user_agent)) {
        return "Hailoobot";
    }
    if (preg_match("/".preg_quote("AOLBuild", "/")."/i", $user_agent)) {
        return "AOL";
    }
    if (preg_match("|".preg_quote("HTML/XML Validator", "|")."|i", $user_agent)) {
        return "HTML/XML Validator";
    }
    if (preg_match("/".preg_quote("Huaweisymantecspider", "/")."/i", $user_agent)) {
        return "Huaweisymantecspider";
    }
    if (preg_match("|".preg_quote("iDB-VerCheck", "|")."|i", $user_agent)) {
        return "iDB Version Checker";
    }
    if (preg_match("|".preg_quote("iDB-Forum", "|")."|i", $user_agent)) {
        return "iDB Message Board";
    }
    if (preg_match("|".preg_quote("DF2k-VerCheck", "|")."|i", $user_agent)) {
        return "DF2k Version Checker";
    }
    if (preg_match("|".preg_quote("DF2k-Forum", "|")."|i", $user_agent)) {
        return "DF2k Message Board";
    }
    if (preg_match("|".preg_quote($UserAgentName, "|")."|i", $user_agent)) {
        return $RName." Message Board";
    }
    if (preg_match("|".preg_quote($AltUserAgentName, "|")."|i", $user_agent)) {
        return $AltName." Message Board";
    }
    if (preg_match("/".preg_quote("Infoseek", "/")."/i", $user_agent)) {
        return "InfoSeek";
    }
    if (preg_match("/".preg_quote("IRLbot", "/")."/i", $user_agent)) {
        return "IRLbot";
    }
    if (preg_match("/".preg_quote("Linguee Bot", "/")."/i", $user_agent)) {
        return "Linguee";
    }
    if (preg_match("/".preg_quote("Lycos", "/")."/i", $user_agent)) {
        return "Lycos";
    }
    if (preg_match("/".preg_quote("LexxeBot", "/")."/i", $user_agent)) {
        return "LexxeBot";
    }
    if (preg_match("/".preg_quote("MJ12bot", "/")."/i", $user_agent)) {
        return "Majestic-12";
    }
    if (preg_match("/".preg_quote("MLBot", "/")."/i", $user_agent)) {
        return "MLBot";
    }
    if (preg_match("/".preg_quote("montastic-monitor", "/")."/i", $user_agent)) {
        return "Montastic";
    }
    if (preg_match("/".preg_quote("montastic-webmonitor", "/")."/i", $user_agent)) {
        return "Montastic";
    }
    if (preg_match("/".preg_quote("mozDex", "/")."/i", $user_agent)) {
        return "Mozdex";
    }
    if (preg_match("/".preg_quote("Mail.Ru", "/")."/i", $user_agent)) {
        return "Mail RU";
    }
    if (preg_match("/".preg_quote("MP3Bot", "/")."/i", $user_agent)) {
        return "MP3Realm";
    }
    if (preg_match("/".preg_quote("mxbot", "/")."/i", $user_agent)) {
        return "mxbot";
    }
    if (preg_match("/".preg_quote("NaverBot", "/")."/i", $user_agent)) {
        return "NaverBot";
    }
    if (preg_match("/".preg_quote("NetSprint", "/")."/i", $user_agent)) {
        return "NetSprint";
    }
    if (preg_match("/".preg_quote("NextGenSearchBot", "/")."/i", $user_agent)) {
        return "ZoomInfo";
    }
    if (preg_match("/".preg_quote("; oBot\/", "/")."/i", $user_agent)) {
        return "oBot";
    }
    if (preg_match("/".preg_quote("Openbot", "/")."/i", $user_agent)) {
        return "OpenFind";
    }
    if (preg_match("/".preg_quote("Page2RSS", "/")."/i", $user_agent)) {
        return "Page2RSS";
    }
    if (preg_match("/".preg_quote("Peew", "/")."/i", $user_agent)) {
        return "Peew";
    }
    if (preg_match("/".preg_quote("PicoSearch", "/")."/i", $user_agent)) {
        return "PicoSearch";
    }
    if (preg_match("/".preg_quote("Plukkie", "/")."/i", $user_agent)) {
        return "Plukkie";
    }
    if (preg_match("/".preg_quote("Purebot", "/")."/i", $user_agent)) {
        return "PuritySearch";
    }
    if (preg_match("/".preg_quote("QweeryBot", "/")."/i", $user_agent)) {
        return "QweeryBot";
    }
    if (preg_match("/".preg_quote("R6_FeedFetcher", "/")."/i", $user_agent)) {
        return "Radian6";
    }
    if (preg_match("/".preg_quote("radian6_linkcheck", "/")."/i", $user_agent)) {
        return "Radian6";
    }
    if (preg_match("/".preg_quote("smerity", "/")."/i", $user_agent)) {
        return "Schwa Lab";
    }
    if (preg_match("/".preg_quote("ScoutJet", "/")."/i", $user_agent)) {
        return "ScoutJet";
    }
    if (preg_match("/".preg_quote("Search17Bot", "/")."/i", $user_agent)) {
        return "Search17";
    }
    if (preg_match("/".preg_quote("Setooz", "/")."/i", $user_agent)) {
        return "Setooz";
    }
    if (preg_match("/".preg_quote("Speedy Spider", "/")."/i", $user_agent)) {
        return "Entireweb";
    }
    if (preg_match("/".preg_quote("Argus", "/")."/i", $user_agent)) {
        return "Simpy";
    }
    if (preg_match("/".preg_quote("seexie.com_bot", "/")."/i", $user_agent)) {
        return "Seexie";
    }
    if (preg_match("/".preg_quote("SemrushBot", "/")."/i", $user_agent)) {
        return "SemrushBot";
    }
    if (preg_match("/".preg_quote("SiteBot", "/")."/i", $user_agent)) {
        return "SiteBot";
    }
    if (preg_match("/".preg_quote("Sogou", "/")."/i", $user_agent)) {
        return "Sogou";
    }
    if (preg_match("/".preg_quote("Sosospider", "/")."/i", $user_agent)) {
        return "Soso";
    }
    if (preg_match("/".preg_quote("spbot", "/")."/i", $user_agent)) {
        return "spbot";
    }
    if (preg_match("/".preg_quote("Speedy Spider", "/")."/i", $user_agent)) {
        return "Speedy";
    }
    if (preg_match("/".preg_quote("SpiderMonkey", "/")."/i", $user_agent)) {
        return "SpiderMonkey";
    }
    if (preg_match("/".preg_quote("Spider_Monkey", "/")."/i", $user_agent)) {
        return "SpiderMonkey";
    }
    if (preg_match("/".preg_quote("SISTRIX Crawler", "/")."/i", $user_agent)) {
        return "SISTRIX Crawler";
    }
    if (preg_match("/".preg_quote("Szukacz", "/")."/i", $user_agent)) {
        return "Szukacz.pl";
    }
    if (preg_match("/".preg_quote("tangyang9@gmail.com", "/")."/i", $user_agent)) {
        return "tangyangbot";
    }
    if (preg_match("/".preg_quote("Thriceler", "/")."/i", $user_agent)) {
        return "Thriceler";
    }
    if (preg_match("/".preg_quote("TotalValidator", "/")."/i", $user_agent)) {
        return "Total Validator";
    }
    if (preg_match("/".preg_quote("Twitterbot", "/")."/i", $user_agent)) {
        return "Twitterbot";
    }
    if (preg_match("/".preg_quote("Ultraseek", "/")."/i", $user_agent)) {
        return "UltraSeek";
    }
    if (preg_match("/".preg_quote("Validator.nu", "/")."/i", $user_agent)) {
        return "Validator.nu";
    }
    if (preg_match("/".preg_quote("Voyager", "/")."/i", $user_agent)) {
        return "Kosmix";
    }
    if (preg_match("/".preg_quote("W3CRobot", "/")."/i", $user_agent)) {
        return "W3C Checklink";
    }
    if (preg_match("/".preg_quote("W3C-checklink", "/")."/i", $user_agent)) {
        return "W3C Checklink";
    }
    if (preg_match("/".preg_quote("FeedValidator", "/")."/i", $user_agent)) {
        return "W3C Validator";
    }
    if (preg_match("/".preg_quote("W3C_CSS_Validator", "/")."/i", $user_agent)) {
        return "W3C Validator";
    }
    if (preg_match("/".preg_quote("W3C_Validator", "/")."/i", $user_agent)) {
        return "W3C Validator";
    }
    if (preg_match("/".preg_quote("WDG_SiteValidator", "/")."/i", $user_agent)) {
        return "WDG Validator";
    }
    if (preg_match("/".preg_quote("WDG_Validator", "/")."/i", $user_agent)) {
        return "WDG Validator";
    }
    if (preg_match("/".preg_quote("HTTP Compression Test", "/")."/i", $user_agent)) {
        return "WhatsMyIP";
    }
    if (preg_match("/".preg_quote("HuaweiSymantecSpider", "/")."/i", $user_agent)) {
        return "Huawei Symantec";
    }
    if (preg_match("/".preg_quote("WhatsMyIP.org", "/")."/i", $user_agent)) {
        return "WhatsMyIP";
    }
    if (preg_match("/".preg_quote("WTABOT", "/")."/i", $user_agent)) {
        return "WTABOT";
    }
    if (preg_match("/".preg_quote("Yahoo", "/")."/i", $user_agent)) {
        return "Yahoo";
    }
    if (preg_match("/".preg_quote("Slurp", "/")."/i", $user_agent)) {
        return "Yahoo";
    }
    if (preg_match("/".preg_quote("Yandex", "/")."/i", $user_agent)) {
        return "Yandex";
    }
    if (preg_match("/".preg_quote("YaDirectFetcher", "/")."/i", $user_agent)) {
        return "Yandex";
    }
    if (preg_match("/".preg_quote("YoudaoBot", "/")."/i", $user_agent)) {
        return "YoudaoBot";
    }
    if (preg_match("/".preg_quote("YodaoBot", "/")."/i", $user_agent)) {
        return "YodaoBot";
    }
    if (preg_match("/".preg_quote("PyWWW-Get", "/")."/i", $user_agent)) {
        return "PyWWW-Get";
    }
    if (preg_match("/".preg_quote("curl", "/")."/i", $user_agent)) {
        return "cURL";
    }
    if (preg_match("/".preg_quote("Yeti", "/")."/i", $user_agent)) {
        return "NHN Naver";
    }
    return false;
}
