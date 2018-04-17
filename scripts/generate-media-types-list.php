#!/usr/bin/env php
<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     17/04/2018
// Time:     09:58
// Project:  MimeTypeLookup
//
declare(strict_types=1);
namespace MediaTypeLookup;

/*
 * Config
 */
const APACHE_MIME_TYPES_URL = 'https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types';
const MEDIA_TYPES_PATH = __DIR__.'/../assets/media-types.json';

/*
 * CLI only!
 */
if (php_sapi_name() != 'cli') {
    die('This script is command line only');
}

/*
 * Reading the list from Apache SVN repo
 */
echo "Reading the MIME types from Apache SVN repo\n";
$f = fopen(APACHE_MIME_TYPES_URL, 'r');
$types = [];
$i = $j = 0;
while ($line = fgets($f)) {
    $line = trim($line);
    if (substr($line, 0, 1) != '#' && preg_match('|^([^\\s]+/[^\\s]+)\\s+(.*)$|ui', $line, $matches)) {
        if ($matches[2]) {
            echo "Processsing $matches[1] [$matches[2]]\n";
            foreach (explode(' ', $matches[2]) as $extension) {
                $types[strtolower($extension)] = strtolower($matches[1]);
                $i++;
            }
            $j++;
        }
    }
}
fclose($f);

/*
 * Writing the JSON list
 */
echo "$j media types and $i extensions processed\n";
if (file_put_contents(MEDIA_TYPES_PATH, json_encode($types, JSON_PRETTY_PRINT))) {
    echo "Writing the media types to '".realpath(MEDIA_TYPES_PATH)."'\n";
}
else {
    die("Error while writing the media types to '".realpath(MEDIA_TYPES_PATH)."'\n");
}

echo "All done!\n";
