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
    
    $FileInfo: compression.php - Last Update: 8/23/2024 SVN 1023 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "compression.php" || $File3Name == "/compression.php") {
    require('index.php');
    exit();
}

// Check if zlib is loaded
if (extension_loaded("zlib")) {
    function gunzip($infile, $outfile)
    {
        $zp = gzopen($infile, "rb");
        if (!$zp) {
            echo "Failed to open gzip file for reading.";
            return false;
        }
        $outData = "";
        while (!gzeof($zp)) {
            $outData .= gzread($zp, 8192); // Increase buffer size for performance
        }
        gzclose($zp);
        file_put_contents($outfile, $outData); // Optimized file writing
        return true;
    }

    function gunzip2($infile, $outfile)
    {
        $outData = implode("", gzfile($infile)); // Efficiently gets content in one step
        if ($outData === false) {
            echo "Failed to read gzip file.";
            return false;
        }
        file_put_contents($outfile, $outData); // Optimized file writing
        return true;
    }

    function gzip($infile, $outfile, $param = 5)
    {
        $inData = file_get_contents($infile); // Optimized file reading
        if ($inData === false) {
            echo "Failed to read file for compression.";
            return false;
        }
        $zp = gzopen($outfile, "wb" . $param); // Use binary-safe writing
        if (!$zp) {
            echo "Failed to open gzip file for writing.";
            return false;
        }
        gzwrite($zp, $inData);
        gzclose($zp);
        return true;
    }
}

// Check if bz2 is loaded
if (extension_loaded("bz2")) {
    function bzip($infile, $outfile)
    {
        $inData = file_get_contents($infile); // Optimized file reading
        if ($inData === false) {
            echo "Failed to read file for compression.";
            return false;
        }
        $zp = bzopen($outfile, "w");
        if (!$zp) {
            echo "Failed to open bzip2 file for writing.";
            return false;
        }
        bzwrite($zp, $inData);
        bzclose($zp);
        return true;
    }

    function bunzip($infile, $outfile)
    {
        $zp = bzopen($infile, "r");
        if (!$zp) {
            echo "Failed to open bzip2 file for reading.";
            return false;
        }
        $outData = "";
        while (!feof($zp)) {
            $outData .= bzread($zp, 8192); // Increased buffer size for performance
        }
        bzclose($zp);
        file_put_contents($outfile, $outData); // Optimized file writing
        return true;
    }
}
