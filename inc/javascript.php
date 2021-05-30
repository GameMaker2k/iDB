<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2019 iDB Support - https://idb.osdn.jp/support/category.php?act=view&id=1
    Copyright 2004-2019 Game Maker 2k - https://idb.osdn.jp/support/category.php?act=view&id=2

    $FileInfo: javascript.php - Last Update: 5/31/2021 SVN 931 - Author: cooldude2k $
*/
header("Content-Language: en");
header("Vary: Accept");
?>
function getid(id) {
var itm;
itm = document.getElementById(id);
return itm; }

function toggletag(id) {
var itm;
itm = document.getElementById(id);
if (itm.style.display == "none") {
itm.style.display = ""; }
else {
itm.style.display = "none"; } }

function bgchange(id,color) {
var itm;
itm = document.getElementById(id);
itm.style.backgroundColor = ''+color+''; }

function innerchange(tag,text1,text2) {
var usrname;
usrname = document.getElementsByTagName(tag);
for (var i = 0; i < usrname.length; i++) {
if(usrname[i].innerHTML==text1) {
usrname[i].innerHTML = text2; } } }

function addsmiley(id,code) {
var itm;
itm = document.getElementById(id);
var pretext = itm.value;
itm.value = pretext + code; }

function GetUserTimeZone() {
    if (!Intl || !Intl.DateTimeFormat().resolvedOptions().timeZone) {
        throw 'Time zones are not available in this environment';
    }

    try {
        tzname = Intl.DateTimeFormat().resolvedOptions().timeZone;
    }
    catch (ex) {
        tzname = false;
        return false;
    }
    if(tzname!=false)
    {
    document.getElementById("YourOffSet").value = tzname;
    return true;
    }
}

<?php gzip_page($Settings['use_gzip']); ?>
