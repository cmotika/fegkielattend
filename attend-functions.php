<?php

// Static configuration
	// Backup mail configuration
	$mail_to = "fegkiel@mail.de";
	
	// Default file name
	$default_csv_file = "./data/defaults.csv";


// Print a file content - header part
function printTableHeader() {
print('<table width="1000" border="2" cellspacing="2" cellpadding="2">
  <tr>
    <th width="62" scope="col"><div align="left">#</div></th>
    <th width="150" scope="col"><div align="left">Name</div></th>
    <th width="150" scope="col"><div align="left">Anschrift</div></th>
    <th width="150" scope="col"><div align="left">Ort</div></th>
    <th width="150" scope="col"><div align="left">Telefon</div></th>
    <th width="200" scope="col"><div align="left">E-Mail</div></th>
  </tr>');
}

// Print a file content - row part (repeated), this is the main content
function printTableRow($num, $name, $street, $city, $phone, $email) {
print('<tr>
    <td><font size=6> '.$num.'</font></td>
    <td>'.$name.'</td>
    <td>'.$street.'</td>
    <td>'.$city.'</td>
    <td>'.$phone.'</td>
    <td>'.$email.'</td>
  </tr>');
}

// Print a file content - footer part
function printTableFooter() {
	print('</table>');
}


// Mobile Print a file content - header part
function printTableHeaderMobile() {
print('<table width="100%" class="table">
  <thead>
  <tr>
    <th width="62" scope="col"><div align="left">#</div></th>
    <th width="150" scope="col"><div align="left">Name</div></th>
    <th width="150" scope="col"><div align="left">Anschrift</div></th>
    <th width="150" scope="col"><div align="left">Ort</div></th>
    <th width="150" scope="col"><div align="left">Telefon</div></th>
    <th width="200" scope="col"><div align="left">E-Mail</div></th>
  </tr>
  </thead><tbody>');
}

// Mobile Print a file content - row part (repeated), this is the main content
function printTableRowMobile($num, $name, $street, $city, $phone, $email) {
print('<tr>
    <td><font size=6> '.$num.'</font></td>
    <td><b>'.$name.'</b></td>
    <td>'.$street.'</td>
    <td>'.$city.'</td>
    <td>'.$phone.'</td>
    <td>'.$email.'</td>
  </tr>');
}

// Mobile Print a file content - footer part
function printTableFooterMobile() {
	print('</tbody></table>');
}


 // Check if this is a valid phone number.
 // A phone number is considered valid, iff it contains more than 5 digits.
function isValidPhoneNumnber($phone) {
	 $phone = preg_replace("/[^0-9]/", '', $phone);
	 $phone2 = $phone + 1;
	 $phone2 = $phone2 - 1;
	 if ($phone == $phone2) {
	    if (strlen($phone2) > 5) {
			return 1;
		}
	 }
	 return 0;
 }

// Get a printable date in short format (only day and month)
function stringDate($timestamp) {
	return date("d.m.", $timestamp);
}

// Get a printable date in long format (day, month, and year)
function stringDateFull($timestamp) {
	return date("d.m.Y", $timestamp);
}

// Geta printable day with time  (day, month, year and hour, minutes, seconds)
function stringDateTime($timestamp) {
	return date("d.m.Y, H:i:s", $timestamp);
}
 
// Erase ';' from a string which is gonna be part of a CSV file row,
// Thise prevents false-injection and coding corrpuption due to additional unwanted columns.
function fix($text) {
	$text = str_replace(";", "", $text);
	$text = str_replace("\n", "", $text);
	$text = str_replace("\t", "", $text);
	$text = str_replace("\r", "", $text);
 	$text = trim($text);
	return $text;
}
 

 
// From a file name get the timestamp in order to dispay it e.g. in print mode 
function getDateFromFile($csvfile) {
	$text = basename ($csvfile);
	$text = str_replace("attend_", "", $text);
	$text = str_replace(".csv", "", $text);
	$text = str_replace("_", "-", $text);
	$text = strtotime($text);
	return $text;
}

// Get a printable date from file to be used as the file name suffix (format: _Year_Month_day) 
function fileDate($timestamp) {
	return date("_Y_m_d", $timestamp);
}

// Retrieve the next sunday from a given (current!) timestamp. If the current timestamp is a sunday and we passed the switchtime, then return the next sunday. If the current timestamp is a sunday and we did not passe the switchtime, then return the current sunday.
function nextSunday($timestamp) {
     global $switchtime;
	 $currenthour = date("H", date($timestamp));
	 if ($currenthour >= $switchtime) {
	 	 // if we crossed the switch time on a sunday... pretend it is monday (=> next week)
	  	 $timestamp += 24*60*60;
	 }
	 $weekday =  date("w", $timestamp);
  	 while($weekday != 0) {
	  	 $timestamp += 24*60*60;
		 $weekday = date("w", $timestamp);
	 }
	 return $timestamp;
}

// Retriebe the current file to write to (for adding new registrations or as a default for loading the current list) 
function currentFile() {
	return "./data/attend".fileDate(nextSunday(time())).".csv";
}

// Write the configuration file (php).
// It is "read" by just including it
function writeConfig() {
 	global $adminpw;
 	global $maxnum;
 	global $switchtime ;
	
	$myfile = fopen("attend.cfg.php", "w");
	fwrite($myfile, "<?php\n");
	fwrite($myfile, "$"."adminpw = \"".$adminpw."\";\n");
	fwrite($myfile, "$"."maxnum = ".$maxnum.";\n");
	fwrite($myfile, "$"."switchtime  = ".$switchtime .";\n");
	fwrite($myfile, "?>\n");
	fclose($myfile);
}

// Verify a captcha code
function verifyCode($code, $verify) {
    $codefile = "./captcha/".$code.".txt";
	//print("codefile=".$codefile."<BR>");
	$returnValue = 0;
	if (file_exists($codefile)) {
		$handle = fopen($codefile, "r");
   	    $show = fgets($handle);
		$value = fgets($handle);
		//print("value=".$value."==".$verify."=verify<BR>");
		if ((trim($value) == trim($verify)) && (trim($value) != "")) {
			$returnValue = 1;
		} else {
		}
		fclose($handle);
	} 	
	return $returnValue;
}

// Retrieve the number of lines which corresponds to the number of registered people (for a given file/sunday)
function getLinesFile($file) {
	$linecount = 0;	
	if (file_exists($file)) {
		$handle = fopen($file, "r");
		while(!feof($handle)){
		  $line = fgets($handle);
		  if (trim($line) != "") {
			  $linecount++;
		  }
		}
		fclose($handle);
	}
	return $linecount; 
}

// Retrieve the number of lines/number of registred people of the current file/sunday
function getLines() {
 	 return getLinesFile(currentFile());
}

// Test if a person is already registered for a given file/sunday.
// This test only succeeeds, iff the name, street, city, phone, and email is the same
function isAlreadyRegistered($file, $name, $street, $city, $phone, $email) {
	$found = 0;	
	if (file_exists($file)) {
		$handle = fopen($file, "r");
		while(!feof($handle) && $found <= 4){
		  $found = 0;
		  $line = fgets($handle);
		  if (strpos($line, trim($name)) > -1) {
		  	  $found++;
		  }
		  if (strpos($line, trim($street)) > -1) {
		  	  $found++;
		  }
		  if (strpos($line, trim($city)) > -1) {
		  	  $found++;
		  }
		  if (strpos($line, trim($phone)) > -1) {
		  	  $found++;
		  }
		  if (strpos($line, trim($email)) > -1) {
		  	  $found++;
		  }
		}
		fclose($handle);
	}
	return $found; 
}

// Ensure the current file exits, if not create it and possibly immediately add the defaults
function ensureCurrentFileExists() {
	global $default_csv_file;
	if (!file_exists(currentFile())) {
		// File not yet exists ... create it
		$myfile = fopen(currentFile(), "a");
		// If default configuration file exists, then insert it first
		if (file_exists($default_csv_file)) {
			$defaults = file_get_contents($default_csv_file);
			fwrite($myfile, $defaults."\n");
		}
		//fwrite($myfile, "\n");
		fclose($myfile);
	}
}

// Send a test mail for testing mail providers w.r.t their anti spam policy
function sendTestMail($email) {
  		$subject = "FeG Anmeldeseite Testmail";
		// header
		$header = "From: FeG Anmeldung <feg@delphino.net>\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
		$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";

		// content - add the file tabbed for readability and afterwards add the csv raw data for enabling easy recover
		$nmessage = "Testmail";		
    	$retval = mail($email, $subject, $nmessage, $header );		
		return $retval;
}

// Tab the input text wrt its length for correct alignment in email display
function tabit($text) {
 	$ret = "";
 	if (strlen($text) < 24) {
	 	$ret .= "\t";
	}
 	if (strlen($text) < 16) {
	 	$ret .= "\t";
	}
 	if (strlen($text) < 8) {
	 	$ret .= "\t";
	}
	return $text.$ret;
}

// Send a backup of the current registrations PLUS the current new registration. Use this function after every new registration (see below)
function sendBackupMail($myfile) {
 		global $mail_to;
		global $oldname;
		
  		$subject = "Neue Anmeldung für ".stringDate(nextSunday(time()))." - ".$oldname;
		
		// header
		$header = "From: FeG Anmeldung <feg@delphino.net>\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
		$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";

		// content - add the file tabbed for readability and afterwards add the csv raw data for enabling easy recover
		$nmessage = "";		
		$content = "";
   		if (file_exists($myfile)) {
			$handle = fopen($myfile, "r");
			while(!feof($handle) && $found == 0){
		  		$line = fgets($handle);
				$content .= $line;
			  	if (trim($line) != "") {
				  	$cols = explode(";", $line);
				  	$nmessage .= $cols[0]."\t".tabit($cols[1]).tabit($cols[2]).tabit($cols[3]).tabit($cols[4]).$cols[5]."\n";
			  	}
			}
			fclose($handle);
	   	}		
  	   	$nmessage .= "\n\n\n\n\nCSV File (raw):\n\n".$content;
    	$retval = mail($mail_to, $subject, $nmessage, $header );		
		return $retval;
 }


?>