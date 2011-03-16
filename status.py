#	Doorduino Status Script (Python)
#	Revision 0.3.1
#	Ben Woodruff <ben@interlockroc.org>
#	http://www.interlockroc.org/

interlockIP = "173.85.245.53";
word[1] = "open! :)";
word[0] = "closed. :(";
statusFile = "status.txt";

##### DO NOT EDIT BELOW THIS LINE #####
# (unless you know what you're doing) #


# enable debugging
import cgitb
cgitb.enable()

print "Content-Type: text/plain;charset=utf-8"

def checkFile():
	fileCheck = 1;
	try:
	    fh = open(statusFile, r+)
	except IOError as e:
		fileCheck = 0
	fh.close()	
	
	return fileCheck;

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
				exit("5"); # 5 = problem with the request from the doorduino
			}
			
			fclose($file);
		
			exit("0");
		
		}

		else {
			exit("1"); # 1 = error reading from / writing to the file. check if it exists
		}

	}

	else {
		exit("2"); # 2 = request is not coming from Interlock
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
			exit("4"); # 4 = problem with the script. we didn't properly define variables at the top
		}
	}
	else {
		exit("3"); # 3 = problem with data returned from file. check file
	}
}

?>