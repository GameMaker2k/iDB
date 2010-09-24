<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2009 iDB Support - http://idb.berlios.de/
    Copyright 2004-2009 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: table.php - Last Update: 09/24/2010 SVN 562 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name=="table.php"||$File3Name=="/table.php") {
	require('index.php');
	exit(); }
?>
<div class="TableSMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableSMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?>Admin CP Menu</div>
<?php } ?>
<table id="AdminMenu" class="TableSMenu" style="width: 100%; text-align: left; vertical-align: top;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableSMenuRow1">
<td class="TableSMenuColumn1"><?php echo $ThemeSet['TitleIcon']; ?>Admin CP Menu</td>
</tr><?php } ?>
<tr class="TableSMenuRow2">
<td class="TableSMenuColumn2">&nbsp;</td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=main",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Main Settings</a></td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=forums",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Forum Menu</a></td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=categories",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Category Menu</a></td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=members",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Memeber Menu</a></td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=view&menu=groups",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Group Menu</a></td>
</tr><tr class="TableSMenuRow4">
<td class="TableSMenuColumn4">&nbsp;</td>
</tr></table></div>
<div>&nbsp;</div>
<?php if($AdminMenu=="main"||$_GET['menu']=="main") { ?>
<div class="TableSMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableSMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?>Main Settings</div>
<?php } ?>
<table id="AdminLinks" class="TableSMenu" style="width: 100%; text-align: left; vertical-align: top;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableSMenuRow1">
<td class="TableSMenuColumn1"><?php echo $ThemeSet['TitleIcon']; ?>Main Settings</td>
</tr><?php } ?>
<tr class="TableSMenuRow2">
<td class="TableSMenuColumn2">&nbsp;</td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=settings",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Edit Settings</a></td>
<?php if($GroupInfo['ViewDBInfo']=="yes") { ?>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=sql",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">SQL Settings</a></td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=vercheck",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>" onclick="window.open(this.href);return false;">Version Checker</a></td>
<?php if($Settings['sqltype']=="mysql"||
	$Settings['sqltype']=="mysqli"||
	$Settings['sqltype']=="pgsql"||
	$Settings['sqltype']=="sqlite") { ?>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=sqldumper",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">SQL Dumper</a></td>
<?php } ?>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=delsessions",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Clean Sessions</a></td>
<?php if($Settings['SQLThemes']=="off") { ?>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=resyncthemes",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Enable SQLThemes</a></td>
<?php } ?>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=themelist",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Download Themes</a></td>
<?php if($Settings['SQLThemes']=="on") { ?>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=enablesthemes",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Resync Themes</a></td>
<?php } ?>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=optimize",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Optimize Tables</a></td>
<?php } ?>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=info",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Edit Board Info</a></td>
</tr><tr class="TableSMenuRow4">
<td class="TableSMenuColumn4">&nbsp;</td>
</tr></table></div>
<div>&nbsp;</div>
<?php } if($AdminMenu=="forums"||$_GET['menu']=="forums") { ?>
<div class="TableSMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableSMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?>Forum Menu</div>
<?php } ?>
<table id="ForumMenu" class="TableSMenu" style="width: 100%; text-align: left; vertical-align: top;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableSMenuRow1">
<td class="TableSMenuColumn1"><?php echo $ThemeSet['TitleIcon']; ?>Forum Menu</td>
</tr><?php } ?><tr class="TableSMenuRow2">
<td class="TableSMenuColumn2">&nbsp;</td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=addforum",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Add Forums</a></td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editforum",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Edit Forums</a></td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=deleteforum",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Delete Forums</a></td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=retopics",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Recount Topics</a></td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=rereplies",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Recount Replies</a></td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=fixtnames",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Fix Topic User Names</a></td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=fixrnames",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Fix Reply User Names</a></td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=fpermissions",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Forum Permissions</a></td>
</tr><tr class="TableSMenuRow4">
<td class="TableSMenuColumn4">&nbsp;</td>
</tr></table></div>
<div>&nbsp;</div>
<?php } if($AdminMenu=="categories"||$_GET['menu']=="categories") { ?>
<div class="TableSMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableSMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?>Category Menu</div>
<?php } ?>
<table id="CategoryMenu" class="TableSMenu" style="width: 100%; text-align: left; vertical-align: top;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableSMenuRow1">
<td class="TableSMenuColumn1"><?php echo $ThemeSet['TitleIcon']; ?>Category Menu</td>
</tr><?php } ?><tr class="TableSMenuRow2">
<td class="TableSMenuColumn2">&nbsp;</td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=addcategory",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Add Categories</a></td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editcategory",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Edit Categories</a></td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=deletecategory",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Delete Categories</a></td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=cpermissions",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Category Permissions</a></td>
</tr><tr class="TableSMenuRow4">
<td class="TableSMenuColumn4">&nbsp;</td>
</tr></table></div>
<div>&nbsp;</div>
<?php } if($AdminMenu=="members"||$_GET['menu']=="members") { ?>
<div class="TableSMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableSMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?>Memeber Menu</div>
<?php } ?>
<table id="MemberMenu" class="TableSMenu" style="width: 100%; text-align: left; vertical-align: top;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableSMenuRow1">
<td class="TableSMenuColumn1"><?php echo $ThemeSet['TitleIcon']; ?>Memeber Menu</td>
</tr><?php } ?><tr class="TableSMenuRow2">
<td class="TableSMenuColumn2">&nbsp;</td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=validate",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Validate Menu</a></td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editmember",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Edit Member</a></td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=deletemember",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Delete Member</a></td>
</tr><tr class="TableSMenuRow4">
<td class="TableSMenuColumn4">&nbsp;</td>
</tr></table></div>
<?php } if($AdminMenu=="groups"||$_GET['menu']=="groups") { ?>
<div class="TableSMenuBorder">
<?php if($ThemeSet['TableStyle']=="div") { ?>
<div class="TableSMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?>Group Menu</div>
<?php } ?>
<table id="GroupMenu" class="TableSMenu" style="width: 100%; text-align: left; vertical-align: top;">
<?php if($ThemeSet['TableStyle']=="table") { ?>
<tr class="TableSMenuRow1">
<td class="TableSMenuColumn1"><?php echo $ThemeSet['TitleIcon']; ?>Group Menu</td>
</tr><?php } ?><tr class="TableSMenuRow2">
<td class="TableSMenuColumn2">&nbsp;</td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=addgroup",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Add Groups</a></td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=editgroup",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Edit Groups</a></td>
</tr><tr class="TableSMenuRow3">
<td class="TableSMenuColumn3"><a href="<?php echo url_maker($exfile['admin'],$Settings['file_ext'],"act=deletegroup",$Settings['qstr'],$Settings['qsep'],$prexqstr['admin'],$exqstr['admin']); ?>">Delete Groups</a></td>
</tr><tr class="TableSMenuRow4">
<td class="TableSMenuColumn4">&nbsp;</td>
</tr></table></div>
<?php } ?>
