#!/usr/bin/php
<?php

if (! isset($argv[1])) {
    die("Please pass a string (filename) to this utility.\n");
}

normalize_filename($argv[1]);


function normalize_filename($raw_filename)
{
    $regex = '/^([^-|~|_]+)( )(\d+)( - )(.+)( -|~ )(.*Yasir Qadhi)( _ ([^-|~|_]+))?\.([^\.]+)$/';
    $album_name = 'Seerah of Prophet Muhammad';
    $author = 'Dr. Yasir Qadhi';
    $matches = array();
    if (1 === ($return = preg_match($regex, $raw_filename, $matches))) {
        $title = trim(str_replace('Muhammed', 'Muhammad', $matches[5]));
        $index = str_pad($matches[3], 2, '0', STR_PAD_LEFT);
        $extension = $matches[10];
        $normalized_filename = "$album_name - $index - $title - $author.$extension";
        echo $normalized_filename . PHP_EOL;
    }
}
