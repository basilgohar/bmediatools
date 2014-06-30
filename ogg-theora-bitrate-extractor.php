#!/usr/bin/env php
<?php

/*
Example of a line we're looking for:
00:50:09.881: serialno 1602811675, calc. gpos 72001|164, packetno 72167: 1.999 kB
*/

$match_regex = '/^([[:digit:]:.]+): serialno [[:digit:]]+, [[:alpha:]. ]+ (\d+\|\d+), packetno (\d+): (.+)$/';
$replace_regex = '/[^[:digit:].]+/';

while (false !== ($line = fgets(STDIN))) {
	if (preg_match($match_regex, $line, $matches)) {
		$timestamp = $matches[1];
		$granulepos = $matches[2];
		$packetno = $matches[3];
		$packetsize = $matches[4];

		//  Convert timestamp to a normal float
		$timestamp_array = explode(':', $timestamp);
		$seconds = ($timestamp_array[0] * 3600) + ($timestamp_array[1] * 60) + $timestamp_array[2];

		//  Normalize packetsize to bytes
		$packetsize_numerical = preg_replace($replace_regex, '', $packetsize);

		if (false !== strpos($packetsize, 'bytes')) {
			//  Bytes
			$packetsize_unit_size = 1;
		} elseif (false !== strpos($packetsize, 'kB')) {
			//  Kibibytes
			$packetsize_unit_size = 1024;
		} elseif (false !== strpos($packetsize, 'MB')) {
			//  Mebibytes
			$packetsize_unit_size = 1024 * 1024;
		} elseif (false !== strpos($packetsize, 'GB')) {
			// Gibibytes
			$packetsize_unit_size = 1024 * 1024 * 1024;
		} else {
			//  Comment this next line out if it's causing problems...at this writing, oggz-dump doesn't use any unit larger than a GiB
			exit('Unknown units for packetsize!!!');
		}

		$packetsize_bytes = (int) ($packetsize_numerical * $packetsize_unit_size);
	
		echo "$seconds\t$packetsize_bytes\n";
	}
}
