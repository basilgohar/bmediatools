#!/usr/bin/php
<?php

require_once __DIR__ . '/suwar-config.php';

if (empty(SUWAR_CSV_FILE)) {
    die('"SUWAR_CSV_FILE" constant may not be empty!' . PHP_EOL);
}

if (! file_exists(SUWAR_CSV_FILE)) {
    die('There is no file "' . SUWAR_CSV_FILE . '".' . PHP_EOL); 
}

if (! is_readable(SUWAR_CSV_FILE)) {
    die('File "' . SUWAR_CSV_FILE . '" is not readable.' . PHP_EOL);
}

if ($fpcsv = (fopen(SUWAR_CSV_FILE, 'r'))) {
    $suwar_array = array();
    while (false !== ($csvrow = fgetcsv($fpcsv))) {
        $suwar_array[$csvrow[0]] = array('arabic' => $csvrow[1], 'english' => $csvrow[2]);
    }
}

if (! isset($argv[1])) {
    die('No file to tag specified.' . PHP_EOL);
}

$filename = $argv[1];

if (! file_exists($filename)) {
    die("File '$filename' does not exist!" . PHP_EOL);
}

if (! is_writeable($filename)) {
    die("File '$filename' is not writeable!" . PHP_EOL);
}

$path_parts = pathinfo($filename);

if ('flac' !== $path_parts['extension']) {
    die("Only FLAC files (ending with .flac) are supported currently." . PHP_EOL);
}

$int_filename = (int)$path_parts['filename'];
$formatted_int_filename = str_pad($int_filename, 3, '0', STR_PAD_LEFT);

if (isset($suwar_array[$int_filename])) {
    $title = "$formatted_int_filename. {$suwar_array[$int_filename]['english']}";
    $tag_command = 'metaflac --set-tag=TITLE=' . escapeshellarg($title) . ' ' . escapeshellarg($filename);
    echo $tag_command . PHP_EOL;
    `$tag_command`;
}

