#!/bin/env php
<?php
/*
   Copyright © 2011 Basil Mohamed Gohar <abu_hurayrah@hidayahonline.org>

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

// Read the first line from the input
$firstline = '';
while ("\n" !== ($chr = fgetc(STDIN))) {
    $firstline .= $chr;
}

// Check that this is indeed a YUV4MPEG2 file, first

if (0 !== strpos($firstline, 'YUV4MPEG2')) {
    fputs(STDERR, "ERROR: File is not a valid YUV4MPEG2 file.\n");
    exit(-1);
}

//  The entire first line has been captured, so we will now look for the interlacing token

$tokens = explode(' ', $firstline);
foreach ($tokens as $token) {
    if ("I" === $token[0]) {
        //  Found the interlacing token.  Let's check it's type.
        switch ($token[1]) {
            case 'p':
                fputs(STDERR, "INFO: File is already progressively formatted.  No changes need to be made.\n");
                $newfirstline = $firstline;
                break;
            case 't':
                fputs(STDERR, "INFO: File is top-field-first formatted.  Losslessly changing it to progressive (assuming it is originally progressive!)\n");
                $newfirstline = str_replace("It", "Ip", $firstline);
                break;
            default:
                fputs(STDERR, "ERROR: File is neither progressively formatted nor top-field-first formatted.  I cannot do anything with this file.  I'm sorry.\n");
                exit(-1);
                break;
        }
        //  No need to continue to any other tokens, as we've already found the interlacing format token.
        break;
    }
}

//  Copy the (possibly) modified first line to STDOUT
fputs(STDOUT, $newfirstline);
//  Copy the rest of the input to STDOUT
stream_copy_to_stream(STDIN, STDOUT);

exit(0);    //  Exit normally.
