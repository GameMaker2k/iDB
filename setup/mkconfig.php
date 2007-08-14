<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2007 Cool Dude 2k - http://intdb.sourceforge.net/
    Copyright 2004-2007 Game Maker 2k - http://upload.idb.s1.jcink.com/
    iDB Installer made by Game Maker 2k - http://idb.berlios.net/

    $FileInfo: mkconfig.php - Last Update: 08/13/2007 SVN 78 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="mkconfig.php"||$File3Name=="/mkconfig.php") {
	require('index.php');
	exit(); }
require_once('settings.php');
if(!isset($SetupDir['setup'])) { $SetupDir['setup'] = "setup/"; }
if(!isset($SetupDir['convert'])) { $SetupDir['convert'] = "setup/convert/"; }
$_POST['DatabaseHost'] = $Settings['sqlhost'];
$_POST['DatabaseUserName'] = $Settings['sqluser'];
$_POST['DatabasePassword'] = $Settings['sqlpass'];
?>
<tr class="TableRow3" style="text-align: center;">
<td class="TableRow3" colspan="2">
<?php
$dayconv = array('second' => 1, 'minute' => 60, 'hour' => 3600, 'day' => 86400, 'week' => 604800, 'month' => 2630880, 'year' => 31570560, 'decade' => 15705600);
$_POST['tableprefix'] = preg_replace("/[^A-Za-z0-9_$]/", "", $_POST['tableprefix']);
if($_POST['tableprefix']==null) { $_POST['tableprefix']="idb_"; }
if($_POST['sessprefix']==null) { $_POST['sessprefix']="idb_"; }
$checkfile="settings.php";
if (!is_writable($checkfile)) {
   echo "<br />Settings is not writable.";
   @chmod("settings.php",0755); $Error="Yes";
   @chmod("settingsbak.php",0755);
} else { /* settings.php is writable install iDB. ^_^ */ }
@session_name($_POST['tableprefix']."sess");
@session_set_cookie_params(0, $this_dir);
@session_cache_limiter("private, must-revalidate");
@header("Cache-Control: private, must-revalidate"); // IE 6 Fix
@header("Pragma: private, must-revalidate");
@header("Date: ".gmdate("D, d M Y H:i:s")." GMT");
@header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
@header("Expires: ".gmdate("D, d M Y H:i:s")." GMT");
@session_start();
if (strlen($_POST['AdminPasswords'])<="3") { $Error="Yes";
echo "<br />Your password is too small."; }
if (strlen($_POST['AdminUser'])<="3") { $Error="Yes";
echo "<br />Your user name is too small."; }
if (strlen($_POST['AdminPasswords'])>="30") { $Error="Yes";
echo "<br />Your password is too big."; }
if (strlen($_POST['AdminUser'])>="20") { $Error="Yes";
echo "<br />Your user name is too big."; }
if ($_POST['AdminPasswords']!=$_POST['ReaPassword']) { $Error="Yes";
echo "<br />Your passwords did not match."; }
if($_POST['HTMLType']=="xhtml11") { $_POST['HTMLLevel']="Strict"; }
if($_POST['BoardURL']=="http://localhost/"||$_POST['BoardURL']=="http://localhost") {
	$_POST['BoardURL'] = "localhost"; }
if($_POST['BoardURL']=="https://localhost/"||$_POST['BoardURL']=="https://localhost") {
	$_POST['BoardURL'] = "localhost"; }
if($_POST['WebURL']=="http://localhost/"||$_POST['WebURL']=="http://localhost") {
	$_POST['WebURL'] = "localhost"; }
if($_POST['WebURL']=="https://localhost/"||$_POST['WebURL']=="https://localhost") {
	$_POST['WebURL'] = "localhost"; }
$_POST['BoardURL'] = addslashes($_POST['BoardURL']);
$YourDate = GMTimeStamp();
$YourEditDate = $YourDate + $dayconv['minute'];
$GSalt = salt_hmac(); $YourSalt = salt_hmac();
/* Fix The User Info for iDB */
$_POST['NewBoardName'] = htmlentities($_POST['NewBoardName'], ENT_QUOTES);
$_POST['NewBoardName'] = fixbamps($_POST['NewBoardName']);
$_POST['NewBoardName'] = @remove_spaces($_POST['NewBoardName']);
$_POST['NewBoardName'] = str_replace("\'", "'", $_POST['NewBoardName']);
//$_POST['AdminPassword'] = stripcslashes(htmlentities($_POST['AdminPassword'], ENT_QUOTES));
//$_POST['AdminPassword'] = preg_replace("/\&amp;#(.*?);/is", "&#$1;", $_POST['AdminPassword']);
$_POST['AdminUser'] = stripcslashes(htmlspecialchars($_POST['AdminUser'], ENT_QUOTES));
$_POST['AdminUser'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['AdminUser']);
$_POST['AdminUser'] = @remove_spaces($_POST['AdminUser']);
if ($_POST['AdminUser']=="Guest") { $Error="Yes";
echo "<br />You can not use Guest as your name."; }
/* We are done now with fixing the info. ^_^ */
$mydbtest = @ConnectMysql($_POST['DatabaseHost'],$_POST['DatabaseUserName'],$_POST['DatabasePassword'],$_POST['DatabaseName']);
if($mydbtest!=true) { $Error="Yes";
echo "<br />".mysql_errno().": ".mysql_error()."\n"; }
if ($Error!="Yes") {
require($SetupDir['setup'].'mktable.php');
/*
$query = query("INSERT INTO ".$_POST['tableprefix']."tagboard VALUES (1,-1,'Cool Dude 2k',".$YourDate.",'Welcome to Your New Tag Board. ^_^','127.0.0.1'), array(null)); 
*/
$query = query("INSERT INTO ".$_POST['tableprefix']."categories VALUES (1,'Main','yes','category','yes',0,'The Main Category.')", array(null));
mysql_query($query);
if(!is_numeric($_POST['YourOffSet'])) { $_POST['YourOffSet'] = "0"; }
if($_POST['YourOffSet']>12) { $_POST['YourOffSet'] = "12"; }
if($_POST['YourOffSet']<-12) { $_POST['YourOffSet'] = "-12"; }
if(!is_numeric($_POST['MinOffSet'])) { $_POST['MinOffSet'] = "00"; }
if($_POST['MinOffSet']>59) { $_POST['MinOffSet'] = "59"; }
if($_POST['MinOffSet']<0) { $_POST['MinOffSet'] = "00"; }
$YourOffSet = $_POST['YourOffSet'].":".$_POST['MinOffSet'];
$AdminDST = $_POST['DST'];
$MyDay = GMTimeGet("d",$YourOffSet,0,$AdminDST);
$MyMonth = GMTimeGet("m",$YourOffSet,0,$AdminDST);
$MyYear = GMTimeGet("Y",$YourOffSet,0,$AdminDST);
$MyYear10 = $MyYear+10;
$YourDateEnd = $YourDate + $dayconv['month'];
$query = query("INSERT INTO ".$_POST['tableprefix']."events VALUES (1, -1, 'Cool Dude 2k', 'Opening', 'This is the day the Board was made. ^_^', %i, %i)", array($YourDate,$YourDateEnd));
mysql_query($query);
$query = query("INSERT INTO ".$_POST['tableprefix']."forums VALUES (1,1,'Test/Spam','yes','forum',0,'http://',0,0,'A Test Board.','off','yes',1,1)", array(null));
mysql_query($query);
$query = query("INSERT INTO ".$_POST['tableprefix']."topics VALUES (1,1,1,-1,'Cool Dude 2k',%i,%i,'Welcome','Install was successful',0,0,1,1)", array($YourDate,$YourDate));
mysql_query($query);
$query = query("INSERT INTO ".$_POST['tableprefix']."posts VALUES (1,1,1,1,-1,'Cool Dude 2k',%i,%i,1,'Welcome to Your Message Board. :) ','Install was successful','127.0.0.1')", array($YourDate,$YourEditDate)); 
mysql_query($query);
$NewPassword = b64e_hmac($_POST['AdminPasswords'],$YourDate,$YourSalt,"sha1");
//$Name = stripcslashes(htmlspecialchars($AdminUser, ENT_QUOTES));
$YourWebsite = "http://".$_SERVER['HTTP_HOST'].$this_dir."index.php?act=view";
$UserIP = $_SERVER['REMOTE_ADDR'];
$PostCount = 2;
$Email = "admin@".$_SERVER['HTTP_HOST'];
$AdminTime = $_POST['YourOffSet'].":".$_POST['MinOffSet'];
$GEmail = "guest@".$_SERVER['HTTP_HOST'];
$grand = rand(6,16); $i = 0; $gpass = "";
while ($i < $grand) {
$csrand = rand(1,3);
if($csrand!=1&&$csrand!=2&&$csrand!=3) { $csrand=1; }
if($csrand==1) { $gpass .= chr(rand(48,57)); }
if($csrand==2) { $gpass .= chr(rand(65,90)); }
if($csrand==3) { $gpass .= chr(rand(97,122)); }
++$i; } $GuestPassword = b64e_hmac($gpass,$YourDate,$GSalt,"sha1");
$url_this_dir = "http://".$_SERVER['HTTP_HOST'].$this_dir."index.php?act=view";
$YourIP = $_SERVER['REMOTE_ADDR'];
$query = query("INSERT INTO ".$_POST['tableprefix']."members VALUES (-1,'Guest','%s','iDBH','%s',4,'no',0,'Guest Account','Guest',%i,%i,'0','[B]Test[/B] :)','Your Notes','http://','100x100','%s','UnKnow',1,'%s','%s','iDB','127.0.0.1','%s')", array($GuestPassword,$GEmail,$YourDate,$YourDate,"http://".$_SERVER['HTTP_HOST']."/",$AdminTime,$AdminDST,$GSalt));
mysql_query($query);
$query = query("INSERT INTO ".$_POST['tableprefix']."members VALUES (1,'%s','%s','iDBH','%s',1,'yes',0,'%s','Admin',%i,%i,'0','%s','Your Notes','%s','100x100','%s','UnKnow',0,'%s','%s','iDB','%s','%s')", array($_POST['AdminUser'],$NewPassword,$Email,$Interests,$YourDate,$YourDate,$NewSignature,$Avatar,$YourWebsite,$AdminTime,$AdminDST,$UserIP,$YourSalt));
mysql_query($query);
$query = query("INSERT INTO ".$_POST['tableprefix']."messenger VALUES (1,-1,1,'Cool Dude 2k','Test','Hello Welcome to your board.\n\rThis is a Test PM. :P ','Hello Welcome',%i,0)", array($YourDate));
mysql_query($query);
$CHMOD = $_SERVER['PHP_SELF'];
$pretext = "<?php\n/*\n    This program is free software; you can redistribute it and/or modify\n    it under the terms of the GNU General Public License as published by\n    the Free Software Foundation; either version 2 of the License, or\n    (at your option) any later version.\n\n    This program is distributed in the hope that it will be useful,\n    but WITHOUT ANY WARRANTY; without even the implied warranty of\n    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n    Revised BSD License for more details.\n\n    Copyright 2004-2007 Cool Dude 2k - http://intdb.sourceforge.net/\n    Copyright 2004-2007 Game Maker 2k - http://upload.idb.s1.jcink.com/\n    iDB Installer made by Game Maker 2k - http://idb.berlios.net/\n\n    \$FileInfo: settings.php & settingsbak.php - Last Update: 05/22/2007 SVN 11 - Author: cooldude2k \$\n*/\n";
$pretext2 = array("/*   Board Setting Section Begins   */\n\$Settings = array();","/*   Board Setting Section Ends  \n     Board Info Section Begins   */\n\$SettInfo = array();","/*   Board Setting Section Ends   \n     Board Dir Section Begins   */\n\$SettDir = array();","/*   Board Dir Section Ends   */");
$settcheck = "\$File3Name = basename(\$_SERVER['SCRIPT_NAME']);\nif (\$File3Name==\"settings.php\"||\$File3Name==\"/settings.php\"||\n    \$File3Name==\"settingsbak.php\"||\$File3Name==\"/settingsbak.php\") {\n    @header('Location: index.php');\n    exit(); }\n";
$BoardSettings=$pretext2[0]."\n\$Settings['sqlhost'] = '".$_POST['DatabaseHost']."';\n\$Settings['sqldb'] = '".$_POST['DatabaseName']."';\n\$Settings['sqltable'] = '".$_POST['tableprefix']."';\n\$Settings['sqluser'] = '".$_POST['DatabaseUserName']."';\n\$Settings['sqlpass'] = '".$_POST['DatabasePassword']."';\n\$Settings['board_name'] = '".$_POST['NewBoardName']."';\n\$Settings['idbdir'] = '".$idbdir."';\n\$Settings['idburl'] = '".$_POST['BoardURL']."';\n\$Settings['weburl'] = '".$_POST['WebURL']."';\n\$Settings['use_gzip'] = '".$_POST['GZip']."';\n\$Settings['html_type'] = '".$_POST['HTMLType']."';\n\$Settings['html_level'] = '".$_POST['HTMLLevel']."';\n\$Settings['output_type'] = '".$_POST['OutPutType']."';\n\$Settings['GuestGroup'] = 'Guest';\n\$Settings['MemberGroup'] = 'Member';\n\$Settings['ValidateGroup'] = 'Validate';\n\$Settings['AdminValidate'] = false;\n\$Settings['TestReferer'] = ".$_POST['TestReferer'].";\n\$Settings['DefaultTheme'] = 'iDB';\n\$Settings['DefaultTimeZone'] = '".$AdminTime."';\n\$Settings['DefaultDST'] = '".$AdminDST."';\n\$Settings['charset'] = 'iso-8859-15';\n\$Settings['add_power_by'] = false;\n\$Settings['send_pagesize'] = false;\n\$Settings['max_posts'] = '10';\n\$Settings['max_topics'] = '10';\n\$Settings['max_memlist'] = '10';\n\$Settings['hot_topic_num'] = '15';\n\$Settings['qstr'] = '&';\n\$Settings['qsep'] = '=';\n\$Settings['file_ext'] = '.php';\n\$Settings['rss_ext'] = '.php';\n\$Settings['js_ext'] = '.js';\n\$Settings['showverinfo'] = true;\n\$Settings['enable_rss'] = true;\n\$Settings['enable_search'] = true;\n\$Settings['sessionid_in_urls'] = false;\n\$Settings['fixpathinfo'] = false;\n\$Settings['fixbasedir'] = false;\n\$Settings['enable_pathinfo'] = false;\n\$Settings['rssurl'] = false;\n\$Settings['board_offline'] = false;\n".$pretext2[1]."\n\$SettInfo['board_name'] = '".$_POST['NewBoardName']."';\n\$SettInfo['Author'] = '".$_POST['AdminUser']."';\n\$SettInfo['Keywords'] = '".$_POST['NewBoardName'].",".$_POST['AdminUser']."';\n\$SettInfo['Description'] = '".$_POST['NewBoardName'].",".$_POST['AdminUser']."';\n".$pretext2[2]."\n\$SettDir['maindir'] = '".$idbdir."';\n\$SettDir['inc'] = 'inc/';\n\$SettDir['misc'] = 'inc/misc/';\n\$SettDir['admin'] = 'inc/admin/';\n\$SettDir['mod'] = 'inc/mod/';\n\$SettDir['themes'] = 'themes/';\n".$pretext2[3]."\n?>";
$BoardSettingsBak = $pretext.$settcheck.$BoardSettings;
$BoardSettings = $pretext.$settcheck.$BoardSettings;
$fp = fopen("settings.php","w+");
fwrite($fp, $BoardSettings);
fclose($fp);
//	@cp("settings.php","settingsbak.php");
$fp = fopen("settingsbak.php","w+");
fwrite($fp, $BoardSettingsBak);
fclose($fp);
$_SESSION['MemberName']=$_POST['AdminUser'];
$_SESSION['UserID']=1;
$_SESSION['UserGroup']="Admin";
$_SESSION['UserTimeZone']=$AdminTime;
$_SESSION['UserDST'] = $AdminDST;
$_SESSION['DBName'] = $_POST['DatabaseName'];
if($_POST['storecookie']==true) {
@setcookie("MemberName", $_POST['AdminUser'], time() + (7 * 86400), $this_dir);
@setcookie("UserID", 1, time() + (7 * 86400), $this_dir);
@setcookie("SessPass", $NewPassword, time() + (7 * 86400), $this_dir); }
@mysql_close(); $chdel = true;
if($Error!="Yes") {
if($_POST['unlink']==true) {
$chdel1 = @unlink($SetupDir['setup'].'presetup.php'); $chdel2 = @unlink($SetupDir['setup'].'setup.php');
$chdel3 = @unlink($SetupDir['setup'].'mkconfig.php'); $chdel4 = @unlink($SetupDir['setup'].'mktable.php');
$chdel5 = @unlink($SetupDir['setup'].'index.php'); $chdel6 = @unlink($SetupDir['setup'].'license.php');
$chdel7 = @unlink($SetupDir['setup'].'preinstall.php'); $chdel8 = @unlink($SetupDir['convert'].'index.php');
if($ConvertInfo['ConvertFile']!=null) { $chdel0 = @unlink($ConvertInfo['ConvertFile']); }
$chdel9 = @unlink($SetupDir['convert'].'info.php'); 
$chdel10 = @rmdir($SetupDir['convert']); $chdel11 = @rmdir('setup');
$chdel12 = @unlink('install.php'); } }
if($chdel1==false||$chdel2==false||$chdel3==false||$chdel4==false) { $chdel = false; }
if($chdel5==false||$chdel6==false||$chdel7==false||$chdel8==false) { $chdel = false; }
if($chdel9==false||$chdel10==false||$chdel11==false||$chdel12==false) { $chdel = false; }
if($ConvertInfo['ConvertFile']!=null) { if($chdel0==false) { $chdel = false; } }
?><span class="TableMessage">
<br />Install Finish <a href="index.php?act=view">Click here</a> to goto board. ^_^</span>
<?php if($chdel==false) { ?><span class="TableMessage">
<br />Error: Cound not delete installer. Read readme.txt for more info.</span>
<?php } ?><br /><br />
</td>
</tr>
<?php } ?>