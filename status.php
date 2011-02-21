<?php

/* 	
	Doorduino Status Script
	Revision 0.1
	Ben Woodruff <ben@interlockroc.org>
	http://www.interlockroc.org/
*/

// error_reporting(0);

$interlockIP = "::1";//"173.85.245.53";
$word[1] = "open! :)";
$word[0] = "closed. :(";
$statusFile = "/Users/bdw/Projects/Interlock-Doorduino/status.txt";//"status.txt";

function checkFile() {

	/*
	// THIS SHOULD WORK BUT IT DOESN'T ON MY SYSTEM
	$fileCheck = 1;
	if(!file_exists($statusFile)) {
		$fileCheck = 0;
	}
	if(!is_readable($statusFile)) {
		$fileCheck = 0;
	}
	if(!is_writable($statusFile)) {
		$fileCheck = 0;
	}
	
	return $fileCheck;
	*/

	return true;
}

if(isset($_GET['status'])) {
	if($_SERVER['REMOTE_ADDR'] == $interlockIP) {
		if(checkFile()) {
			$file = fopen($statusFile, "w+");

			if($_GET[status] == "1") {
				fwrite($file, "1");
			}
			elseif($_GET[status] == "0") {
				fwrite($file, "0");
			}
			else {
				exit("5"); // 5 = problem with the request from the doorduino
			}
			
			fclose($file);
		
			exit("0");
		
		}

		else {
			exit("1"); // 1 = error reading from / writing to the file. check if it exists
		}

	}

	else {
		exit("2"); // 2 = request is not coming from Interlock
	}
}

else {
	if(checkFile()) {
		$file = fopen($statusFile, "r");
		$display = fread($file, 1);
		fclose($file);
		if(isset($word[$display])) {
			exit($word[$display]);
		}
		else {
			exit("4"); // 4 = problem with the script. we didn't properly define variables at the top
		}
	}
	else {
		exit("3"); // 3 = problem with data returned from file. check file
	}
}

?>