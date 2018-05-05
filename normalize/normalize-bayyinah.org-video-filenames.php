#!/usr/bin/php
<?php

if (! isset($argv[1])) {
    die("Please pass a string (filename) to this utility.\n");
}

normalize_filename($argv[1]);

/**
 * Example filename:
 * 'Baqarah - Ayah 125_dash-fastly_skyfire_h2_sep-video-620341672 - 1920x1080 (DASH video)+dash-fastly_skyfire_h2_sep-audio-620341676 - audio only (DASH audio).mp4'
 */

function normalize_filename($raw_filename)
{
//    $regex = '/^([^-|~|_]+)( )(\d+)( - )(.+)( -|~ )(.*Yasir Qadhi)( _ ([^-|~|_]+))?\.([^\.]+)$/';
    $regex = '/^Baqarah[ -]+.+$/';
    $album_name = '';
    $author = 'Nouman Ali Khan';
    $matches = array();
    if (1 === ($return = preg_match($regex, $raw_filename, $matches))) {
        var_dump($matches);
        exit;
        $title = trim(str_replace('Muhammed', 'Muhammad', $matches[5]));
        $index = str_pad($matches[3], 2, '0', STR_PAD_LEFT);
        $extension = $matches[10];
        $normalized_filename = "$album_name - $index - $title - $author.$extension";
        echo $normalized_filename . PHP_EOL;
    }
}
