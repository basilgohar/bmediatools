#!/bin/env php
<?php
/*
   Copyright © 2010 Basil Mohamed Gohar <abu_hurayrah@hidayahonline.org>

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if (count($argv) < 2) {
    fputs(STDERR, "ERROR: Please specify at least one file.\n");
    exit(-1);
}

$args = $argv;
unset($args[0]);
$first = true;
foreach ($args as $file) {
    if (! file_exists($file)) {
            fputs(STDERR, "ERROR: File '$file' does not exist.\n");
            exit(-1);
    }
    if (! $y4mfp = fopen($file, 'r')) {
            fputs(STDERR, "ERROR: Unable to open file '$file'.\n");
            exit(-1);
    }
    //  Skip the header line for all files but the first.
    if (! $first) {
            //  Read through the file until we find a newline character or we reach the end of the file
            while ("\n" !== fgetc($y4mfp) && ! feof($y4mfp)) {
                    continue;
            }
            if (feof($y4mfp)) {
                    //  We've reached the end of the file without finding any frames.
                    fputs(STDERR, "No video data found for file '$file'.\n");
                    continue;       //  Proceed to the new file in the argument list, if any remain
            }
    }
    $first = false;
    stream_copy_to_stream($y4mfp, STDOUT);
    fclose($y4mfp);
}
