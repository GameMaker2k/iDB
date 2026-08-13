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

    $FileInfo: javascript.php - Last Update: 8/23/2024 SVN 1023 - Author: cooldude2k $
*/
// BUGFIX: this file emits JavaScript but never sent a Content-Type, so served
// as .php it inherited text/html. The board also sends
// "X-Content-Type-Options: nosniff", which makes browsers *refuse* to execute
// a script delivered as text/html -- so none of the functions below loaded.
if (!isset($Settings['charset']) || $Settings['charset'] == "") {
    $Settings['charset'] = "UTF-8";
}
header("Content-Type: text/javascript; charset=".$Settings['charset']);
header("Content-Language: en");
header("Vary: Accept, Accept-Encoding");
?>
// Utility function to fetch a DOM element by ID
function getid(id) {
    return document.getElementById(id);
}

// Toggle the visibility of an element by ID
function toggletag(id) {
    const itm = getid(id);
    if (itm) {
        itm.style.display = (itm.style.display === "none") ? "" : "none";
    }
}

// Change the background color of an element by ID
function bgchange(id, color) {
    const itm = getid(id);
    if (itm) {
        itm.style.backgroundColor = color; // No need to concatenate empty strings
    }
}

// Change the inner HTML of elements with a specific tag name
function innerchange(tag, text1, text2) {
    // BUGFIX: getElementsByTagName() returns a *live* collection. Rewriting
    // innerHTML while iterating it can add or remove matching elements
    // mid-loop, so entries were skipped. Snapshot it first.
    const elements = Array.from(document.getElementsByTagName(tag));
    for (const element of elements) {
        if (element.innerHTML === text1) {
            element.innerHTML = text2;
        }
    }
}

// Append a code (e.g., smiley) to the value of an input/textarea by ID
function addsmiley_old(id, code) {
    const itm = getid(id);
    if (itm) {
        itm.value += code;
    }
}

function addsmiley(id, code) {
    const itm = getid(id);
    if (!itm) return;

    // For input or textarea elements
    if (typeof itm.selectionStart === "number" && typeof itm.selectionEnd === "number") {
        const startPos = itm.selectionStart;
        const endPos = itm.selectionEnd;
        const preText = itm.value.substring(0, startPos);
        const postText = itm.value.substring(endPos, itm.value.length);

        // Insert the smiley code at the cursor position
        itm.value = preText + code + postText;

        // Move the cursor to the end of the inserted smiley code
        itm.selectionStart = itm.selectionEnd = startPos + code.length;
    } else {
        // If the browser does not support selectionStart/selectionEnd, append at the end
        itm.value += code;
    }

    // Focus back to the textarea/input after inserting the smiley
    itm.focus();
}

// Get the user's time zone and set it in the "YourOffSet" input
function GetUserTimeZone() {
    try {
        const tzname = Intl.DateTimeFormat().resolvedOptions().timeZone;
        if (tzname) {
            const offsetElement = getid("YourOffSet");
            if (offsetElement) {
                offsetElement.value = tzname;
            }
            return true;
        }
    } catch (ex) {
        console.error('Error detecting timezone:', ex);
    }
    return false;
}

<?php
// BUGFIX: every other caller passes the negotiated encoding as the second
// argument. Omitting it here made gzip_page() fall back to "gzip" even when
// the client had not offered it.
if (!isset($Settings['use_gzip'])) {
    $Settings['use_gzip'] = "off";
}
if (!isset($GZipEncode['Type'])) {
    $GZipEncode = array("Type" => "none");
}
gzip_page($Settings['use_gzip'], $GZipEncode['Type']); ?>
