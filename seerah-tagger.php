#!/usr/bin/php
<?php

$extension = 'mp4';
$regex = '/^([^-|~|_]+)( )(\d+)( - )(.+)( -|~ )(.*Yasir Qadhi)( _ ([^-|~|_]+))?\.([^\.]+)$/';

$di = new DirectoryIterator(__DIR__);

foreach ($di as $file) {
    if ($file->isDir()) {
        continue;
    }
    $filename = $file->getFilename();
    $pathparts = pathinfo($filename);
    if ($pathparts['extension'] === $extension) {
        $matches = array();
        $return = preg_match($regex, $filename, $matches);
        var_dump($matches);
        if (! $return) {
            die('No match: ' . $filename . PHP_EOL);
        }
    }
}
