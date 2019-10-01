<?php
	// Get a file into an array
	$lines = file('ccrs.dat');

	// Loop through our array
	foreach ($lines as $line_num => $line) {
		$pos = strpos($line, ',');

		if ($pos === false) {
			$data = split(' ', $line);
			$last_name = $data[0];
			$first_name = $data[1];
			echo $first_name." ".$last_name."\r\n";
		}
		else {
                       $data = split(',', $line);
                       $last_name = $data[0];
                       $first_name = $data[1];
                       echo $first_name." ".$last_name."\r\n";			
		}
	}
?>
