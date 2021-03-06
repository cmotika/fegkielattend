<?php
error_reporting(E_ERROR);


// Static configurations for FeG Kiel
	$mail_from_name = "noreply@feg-kiel.de";
	$mail_from_header = "From: FeG Kiel <noreply@feg-kiel.de>\r\n";
	$mail_from_title = "FeG Kiel";
	$youtube_link = "http://youtube.feg-kiel.de";

// Static internal configuration
	// Default file name
	$default_csv_file = "./data/defaults.csv";

	// Default dir for IP logging
	$ipfolder = "./admin/";

	// Default dir for mobile passwords
	$mobilefolder = "./mobile/";

	// Default time out for mobile passwords in minutes, typically 3*60 (3h)
	$mobiletimeout = 3*60;

	// Each sunday at 13:00, send backup email
	// #REQ072
	$mobilebackuptimesunday = 13;

	// Lock out IP conditiob
	$lockip_number_of_wrong_password_trials = 10;

	// Call site with test_secret to deactivate captcha
    $test_secret = "97y2o3lrnewdsa0AS8UAPOIHKNF3R9PHAOSD@!$$";

	// Disable this for production use
	$test_enabled = false;


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
global $mobileCode;
global $mobilehide;

// #REQ073
$button =  "<button name=\"btnmobilehide\" class=\"btn btn-primary\" type=\"button\" onclick=\"submit()\"  >Anwesende ausblenden</button>";
$button .= '<input name="mobilehide" type="hidden" id="mobilehide" value="1"/>';
if ($mobilehide == 1) {
	$button =  "<button name=\"btnmobilehide\" class=\"btn btn-primary\" type=\"button\" onclick=\"submit()\"  >Alle einblenden</button>";
	$button .= '<input name="mobilehide" type="hidden" id="mobilehide" value="0"/>';
}


print('
  <script>

  function edit(val) {
  	 document.getElementById("edit"+val).style.display="inline";
  	 document.getElementById("view"+val).style.display="none";
  }

  function view(val, defaultval) {
  	 document.getElementById("edit"+val).style.display="none";
  	 document.getElementById("view"+val).style.display="inline";

  	 document.getElementById("mobiletext"+val).value = defaultval;
  }

  </script>


<div class="text-center">
<form id="form1" name="form1" method="post" action="">
<input name="pw" type="hidden" id="pw"" value="'.$mobileCode.'"/>
'.$button.'
</form>
</div>
</BR>

  <table width="1" class="table">
  <thead class="thead-dark">
  <tr>
    <th width="62" scope="col"><div align="left">#</div></th>
    <th width="1" scope="col"><div align="left">Name</div></th>
    <th width="1" scope="col"><div align="left">Strasse und Ort</div></th>
    <th width="1" scope="col"><div align="left">Telefon und E-Mail</div></th>
  </tr>
  </thead><tbody>
  <div id="tablestart"></div>
  ');
}


// Helper function to display a mobile view entry of a cell with edit & save functionality
// #REQ071
function printValMobileHelper($mobileCode, $num, $col, $value) {
global $mobilehide;
		$returnvalue =  '<div id="view'.$num.'-'.$col.'">';
		$returnvalue .= '<button class="btn btn-light" type="button" name="btnedit'.$num.'-'.$col.'" onclick="edit(\''.$num.'-'.$col.'\')" />'.$value.'</button>';
		$returnvalue .= '</div>';
		$returnvalue .= '<div id="edit'.$num.'-'.$col.'" style="display:none">';
		$returnvalue .= '<form id="form1" name="form1" method="post" action="">';
		$returnvalue .= '<input name="pw" type="hidden" id="pw"" value="'.$mobileCode.'"/>';
		$returnvalue .= '<input name="mobileval" type="hidden" id="mobileval" value="'.$num.'"/>';
		$returnvalue .= '<input name="mobilehide" type="hidden" id="mobilehide" value="'.$mobilehide.'"/>';
		$returnvalue .= '<input name="mobilecmd" type="hidden" id="mobilecmd" value="'.$col.'"/>';
		$returnvalue .= '<input name="mobiletext" type="text" id="mobiletext'.$num.'-'.$col.'" value="'.$value.'"/>';
		$returnvalue .= '<div class="btn-group"><button class="btn btn-primary" type="button" name="mobileedit'.$num.'-'.$col.'" onclick="submit()" value="" />Speichern</button>';
		$returnvalue .= '<button class="btn btn-warning" type="button" onclick="view(\''.$num.'-'.$col.'\', \''.$value.'\')" />Abbrechen</button></div>';
		$returnvalue .= '</form></div>';
		$returnvalue .= '<script>';
		$returnvalue .= 'document.getElementById("edit'.$num.'-'.$col.'").style.display="none"; ';
		$returnvalue .= '</script>';
		return $returnvalue;
}

// Mobile Print a file content - row part (repeated), this is the main content
function printTableRowMobile($num, $name, $street, $city, $phone, $email, $attended, $mobileCode, $mobileval) {
global $mobilehide;

// #REQ069
$nametr =  "<button name=\"btnred".$num."\" class=\"btn btn-danger\" type=\"button\" onclick=\"submit()\"  >".$name."</button>"; // red button if not present
$nametr .= '<input name="mobilecmd" type="hidden" id="mobilecmd" value="1"/>';   // action: attend, make green
$nametr .= '<input name="mobileattend" type="hidden" id="mobileattend" value="1"/>';
$nametr .= '<input name="mobilehide" type="hidden" id="mobilehide" value="'.$mobilehide.'"/>';
if ($attended == 1) {
	// #REQ070
	$nametr =  "<button name=\"btngreen".$num."\" class=\"btn btn-success\" type=\"button\" onclick=\"submit()\"  >".$name."</button>"; // green button if preset
	$nametr .= '<input name="mobilecmd" type="hidden" id="mobilecmd" value="0"/>';   // action:  notattend, make red
	$nametr .= '<input name="mobileattend" type="hidden" id="mobileattend" value="1"/>';
	$nametr .= '<input name="mobilehide" type="hidden" id="mobilehide" value="'.$mobilehide.'"/>';
}


$info = "";
if ($num == $mobileval) {
	$info = 'class="table-active"';
}

if ($mobilehide != 1 || $attended != 1) {
print('
    <tr '.$info.'>
    <td><font size=6>	<div id="'.$num.'"> '.$num.'</div></font></td>
	<form id="form1" name="form1" method="post" action="">
	<input name="pw" type="hidden" id="pw"" value="'.$mobileCode.'"/>
	<input name="mobileval" type="hidden" id="mobileval" value="'.$num.'"/>
    <td><b>'.$nametr.'</b></td>
	</form>
    <td>'.printValMobileHelper($mobileCode, $num, 2, $street).'
        '.printValMobileHelper($mobileCode, $num, 3, $city).'</td>
    <td>'.printValMobileHelper($mobileCode, $num, 4, $phone).'
        '.printValMobileHelper($mobileCode, $num, 5, $email).'</td>
  </tr>');
}


}



// Mobile Print a file content - footer part
function printTableFooterMobile($num) {
global $mobilehide;

	print('</tbody></table>');
	if ($mobilehide == 1) {
		print('<script>location.hash = "#tablestart"</script>');
	} else {
		print('<script>location.hash = "#'.($num).'"</script>');
	}
}

// Check if the name(s) are given corrently. Even if comma separated, they have to be full
// #REQ 018
function isValidName($name) {
	$name = trim($name);
	// #REQ47
	if (strpos($name, "+") > -1) {
		return 0;
	}
	// #REQ48
	if (strpos(strtoupper($name), " UND ") > -1) {
		return 0;
	}
	// #REQ035
	if (strlen($name) < 5) {
		return 0;
	}
	$names = explode(",", $name);
	foreach ($names as $name) {
		$name =	trim($name);
 		// #REQ036
		if (strlen($name) < 5) {
			return 0;
		}
		if (strpos($name, " ") <= 0) {
			return 0;
		}
	}
	return 1;
}

// Check if the street consists of a street name and a house number separated by space, in total >= 5 characters length.
// #REQ019
function isValidStreet($value) {
	$value = trim($value);
	// #REQ019
	if (strlen($value) < 5) {
		return 0;
	}
	// At least two parts, #REQ050
	if (strpos($value, " ") <= 0) {
		return 0;
	}
	$parts = explode(" ", $value);
	$foundnumber = 0;

	for ($c = 0; $c < count($parts); $c++)  {
		$number = preg_replace("/[^0-9]/", '', $parts[$c]);
		if (strlen($number) > 0) {
			$foundnumber = 1;
		}
	}
	// #REQ050
	if (!$foundnumber) {
		// not a digit house number
		return 0;
	}
	return 1;
}

// Check if the city entry consists of a digit zip code and a city name, separated by space, with city name at least length 3.
// #REQ020
function isValidCity($value) {
	$value = trim($value);
	// #REQ020
	if (strlen($value) < 5) {
		return 0;
	}
	// At least two parts, #REQ050
	if (strpos($value, " ") <= 0) {
		return 0;
	}
	$parts = explode(" ", $value);
	//#REQ050
	if (strlen($parts[1]) < 3) {
		// city name too short
		return 0;
	}
	$number = preg_replace("/[^0-9]/", '', $parts[0]);
	$number2 = (($number+1)-1);
	// #REQ050
	if ((strlen($number) != 5) || ($number2 != $number)) {
		// no valid zip code
		return 0;
	}
	return 1;
}

// Check if email consists of name, @ and valid host.
// #REQ022
function isValidEmail($value) {
	$value = trim($value);
	// #REQ020
	if (strlen($value) < 5) {
		return 0;
	}
	// At least an @
	if (strpos($value, "@") <= 0) {
		return 0;
	}
	// At least a .
	if (strpos($value, ".") <= 0) {
		return 0;
	}
	// Explore host
	$parts = explode("@", $value);
	$name = $parts[0];
	$host = $parts[1];
	// Host must contain .
	if (strpos($host, ".") <= 0) {
		return 0;
	}
	$hostparts = explode(".", $host);
	if (strlen(trim($hostparts[0])) < 1) {
		return 0;
	}
	if (strlen(trim($hostparts[1])) < 2) {
		return 0;
	}
	return 1;
}


 // Check if this is a valid phone number.
 // A phone number is considered valid, iff it contains at least 5 digits.
 // #REQ 021
function isValidPhoneNumnber($phone) {
	 $phone = preg_replace("/[^0-9]/", '', $phone);
	 // #REQ49
	 if (strlen($phone) >= 5) {
		return 1;
	 }
	 return 0;
 }

// Geta simple day with time  (day, month, year and hour, minutes)
function stringDateTimeSimple($timestamp) {
	return date("d.m.Y, H:i", $timestamp);
}


// Get a printable date in short format (only day and month)
function stringDate($timestamp) {
	if (isJoker()) {
		return stringDateTimeSimple($timestamp);
	}
	return date("d.m.", $timestamp);
}

// Get a printable date in long format (day, month, and year)
function stringDateFull($timestamp) {
	if (isJoker()) {
		return stringDateTimeSimple($timestamp);
	}
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
	$text = str_replace("\t", "", $text);
	$text = str_replace("\n", "", $text);
	$text = str_replace("\r", "", $text);
 	$text = trim($text);
	return $text;
}

//// Just a simple protection against spiders, creating various random files
// OBSOLETE
//function secuirtyDatePin($timestamp) {
//  $a =   date("y", $timestamp); // two digit year
//  $b =   date("n", $timestamp); // month no zero
//  $c =   date("j", $timestamp); // day no zero
//  return ($a + $b + $c);
//}

// #REQ077:
// Return a joker timestamp according to the optionally defined joker field (iff defined)
function isJoker() {
	global $djoker;
	return (trim($djoker) != "");
}

function getJokerTimeStamp() {
	global $djoker;
	if (isJoker()) {
		$jokerdate = date_create_from_format('d.m.Y H:i', trim($djoker));
		$jokertimestamp = $jokerdate->getTimestamp();
		return $jokertimestamp;
	}
	return "";
}



// Return the fixed date if it is entered valid, or 0 if no fixed date is given or invalid/
// It is valid if the user is the admin OR if a corresponding CSV file exists (created by the admin panel, #REQ076)
// #REQ075
function fixedDate() {
	global $specialdate;
	global $isAdmin;
 	// Special date
	 if ($specialdate != "" && ($isAdmin || file_exists(getCSVFile($specialdate))) ) {
	 	//possibly apply joker
		return $specialdate;
	 }
	 return 0;
}



// From a file name get the timestamp in order to dispay it e.g. in print mode
function getDateFromFile($csvfile) {
	$text = basename ($csvfile);
	$i = strpos ($text, ".");
	$text = substr($text, 0, $i);
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
// For a fixed date (#REQ075) return the given timestamp.
// #REQ077: If a joker field exists and $allowJokerOverride for visible date parts such as title, text and email, then use this joker date/time definition instead
function nextSunday($timestamp, $allowJokerOverride) {
    global $switchtime;

	// Normal sunday (*)
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

 	 // Special day
	 if (fixedDate() != 0) {
	 	$timestamp = fixedDate();
	 }
	 // #REQ077
	 if ($allowJokerOverride) {
	 	// Do joker override only if there is a joker field defined
		$jokertimestamp = getJokerTimeStamp();
		if ($jokertimestamp != "") {
			$timestamp = $jokertimestamp;
		}
	 }

	 return $timestamp;
}

// Retrieve the current file to write to (for adding new registrations or as a default for loading the current list)
function getCSVFile($timestamp) {
	return "./data/attend".fileDate($timestamp).".csv";
}

function currentFile() {
	return getCSVFile(nextSunday(time(),false));
}

// Retrieve the optional configuration file
function getCSVConfigFile($timestamp) {
	return "./data/attend".fileDate($timestamp).".config.csv";
}

function currentConfigFile() {
	return getCSVConfigFile(nextSunday(time(),false));
}

// Write the special day config file
// XXX
function writeConfigFile($dmax, $dday, $dtitle, $djoker) {
	$configFile = currentConfigFile();

	$handle = fopen($configFile, "w");
	fwrite($handle, $dmax."\n");
	fwrite($handle, $dday."\n");
	fwrite($handle, $dtitle."\n");
	fwrite($handle, $djoker."\n");
	fclose($handle);
}



// Read the special day config file, if it exists, set dmax and dday accordingly and return true, otherwise return false
// XXX
function readConfigFile() {
	global $dmax;
	global $dday;
	global $dtitle;
	global $djoker;
	$configFile = currentConfigFile();
	if (file_exists($configFile)) {
		$handle = fopen($configFile, "r");
		$line1 = fgets($handle);
		$line2 = fgets($handle);
		$line3 = fgets($handle);
		$line4 = fgets($handle);
		fclose($handle);
		// Validity check
		if (($line1 >= 1) && ($line2 > time() ))  {
			$dmax = $line1;
			$dday = $line2;
			$dtitle = $line3;
			$djoker = $line4;
			return true;
		}
	}
	return false;
}


// Retrieve the optional configuration file
function getCSVWaitinglistFile($timestamp) {
	return "./data/attend".fileDate($timestamp).".waiting.csv";
}

function currentWaitinglistFile() {
	return getCSVWaitinglistFile(nextSunday(time(),false));
}


// Get the maxnum general value from general config, or if special fixed day and special config exists, then this more specific one
// Also consider the possibly defined deadline here and return maxnum of zero, if the deadline has passed
function getCurrentMaxNum() {
	global $maxnum;
	global $dmax;
	global $dday;
	if (fixedDate()) {
		readConfigFile();
		if ($dmax >= 1) {
			// check deadline
			if ($dday > 0) {
				if (time() > $dday) {
					// No seats available after deadline!
					return 0;
				}
			}
			return $dmax;
		}
	}
	return $maxnum;
}


//---------------------------------------------------------------------------------------

function sendConfirmationMail($receipient, $name, $number) {
		global $baseurl;
		global $coronalink;
		global $mail_from_header;
		global $mail_from_title;

		// Possibly add fixed date to url
		$url = $baseurl;
		if (fixedDate()) {
			$url = $url."?d=".nextSunday(time(),false);
		}

 	 	$plural = (strpos($regnumber, ",") > -1);

  		$subject = $mail_from_title." - Anmeldung ".$number." von ".$name." fuer GoDi am ".stringDateFull(nextSunday(time(),true));
		// header
		$header = $mail_from_header;
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
		$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";

		// content - add the file tabbed for readability and afterwards add the csv raw data for enabling easy recover
		$nmessage = "Hallo ".$name.",\n\nDu bist mit der Anmeldenummer\n\n     ".$number."\n\nfuer den Gottesdienst in der Freien Evangelischen Gemeinde Kiel am\n\n     ".stringDateFull(nextSunday(time(),true))."\n\nregistriert.\n\nBitte bringe diese Nummer mit zum GoDi! Sie erleichtert die Anmeldung vor Ort vom Begruessungsteam erheblich. Bitte denke auch an die aktuellen Covid19-Bestimmungen (s. ".$coronalink.").\n\nWir freuen uns auf Deinen Besuch! :-)\n\nPS: Du brauchst die obige Nummer auch, solltest Du Dich wieder vom GoDi abmelden muessen. Dies kannst Du ebenfalls ueber die Webseite ".$url." tun. Dort gibst Du zur Abmeldung alle Deine Daten ein plus dieser Anmeldenummer und klickst auf den Button 'Abmelden vom Gottesdienst...'.";

		if ($plural) {
			$nmessage = "Hallo ".$name.",\n\Ihr seid mit den Anmeldenummern\n\n     ".$number."\n\nfuer den Gottesdienst in der Freien Evangelischen Gemeinde Kiel am\n\n     ".stringDateFull(nextSunday(time(),true))."\n\nregistriert.\n\nBitte bringt alle diese Nummern mit zum GoDi! Sie erleichtern die Anmeldung vor Ort vom Begruessungsteam erheblich. Bitte denkt auch an die aktuellen Covid19-Bestimmungen (s. ".$coronalink.").\n\nWir freuen uns auf Euren Besuch! :-)\n\nPS: Ihr  braucht die obigen Nummern auch, sollte sich einer von Euch wieder vom GoDi abmelden muessen. Dies kann ebenfalls ueber die Webseite ".$url." geschehen. Dort gebt Ihr zur Abmeldung alle Deine Daten einer Person ein plus dessen Anmeldenummer und klickt auf den Button 'Abmelden vom Gottesdienst...'. Jeder Person muss sich einzeln abmelden.";
		}


		// Aussen-GODI schlechtwetterabsgae disclaimer
	    $nmessage .= "\n\nACHTUNG : Sollte sich die Wetterlage verschlechtern, behalten wir uns vor, den OpenAir-Gottesdienst abzusagen! Das erfaehrst Du jeweils bis spaetestens um 18:00 Uhr am Samstag auf unserer Homepage. Falls wir den OpenAir-Gottesdienst absagen muessen, brauchst Du NICHT zur Gemeinde zu kommen, da wir dann nur einen Livestream-Gottesdienst feiern.";

    	$retval = mail($receipient, $subject, $nmessage, $header );
		return $retval;
}

//---------------------------------------------------------------------------------------
//                   W A I T I N G L I S T
//---------------------------------------------------------------------------------------


function sendWaitingListMail($receipient) {
		global $baseurl;
		global $mail_from_header;
		global $mail_from_title;

		$url = $baseurl;
		if (fixedDate()) {
			$url = $url."?d=".nextSunday(time(),false);
		}

		$subject = $mail_from_title." - Freie GoDi Plaetze fuer den ".stringDate(nextSunday(time(),true))."!";
		// header
		$header = $mail_from_header;
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
		$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";

		// content - add the file tabbed for readability and afterwards add the csv raw data for enabling easy recover
		$nmessage = "Hallo, \n\nes gibt gute Neuigkeiten fuer Dich!\nDurch eine Abmeldung sind neue Sitzplaetze frei geworden.\n\nSei schnell und melde Dich unter\n\n    ".$url." \n\nfuer den Gottesdiest vor Ort an :-).";
    	$retval = mail($receipient, $subject, $nmessage, $header );
		return $retval;
}

function sendWaitingListTestMail($receipient) {
		global $mail_from_header;
		global $mail_from_title;

  		$subject = $mail_from_title." - Warteliste fuer den ".stringDate(nextSunday(time(),true))."!";
		// header
		$header = $mail_from_header;
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
		$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";

		// content - add the file tabbed for readability and afterwards add the csv raw data for enabling easy recover
		$nmessage = "Diese Mail bestaetigt nur, dass Du fuer die Warteliste fuer den ".stringDate(nextSunday(time(),true))." eingetragen bist.\n\nSobald es (wieder) freie Plaetze geben sollte, wirst Du per E-Mail benachrichtigt. Du musst Dich dann aber noch fuer den Gottesdienst anmelden! \n\nSollte es keine freien Plaetze geben, hoffen wir, dass wir mit Dir ueber  http://youtube.feg-kiel.de zusammen online Gottesdienst feiern koennen und dass Du bald an einem der naechsten Sonntage wieder live vor Ort bist!";
    	$retval = mail($receipient, $subject, $nmessage, $header );
		return $retval;
}



// Send a mail to each waiting list member and delete the waitin list file
// #REQ063
function sendWaitingListMails($timestamp) {
	$waitinglistFile = getCSVWaitinglistFile($timestamp);

   if (file_exists($waitinglistFile)) {
		$handle = fopen($waitinglistFile, "r");
		while(!feof($handle) && $found == 0){
		  $line = fgets($handle);
		  if (trim($line) != "") {
				sendWaitingListMail($line);
		  }//end if
		}//end while
		fclose($handle);
		//unlink(waitinglistFile());
   }
}


// Write the configuration file (php).
// It is "read" by just including it
function writeConfig() {
 	global $adminpw;
 	global $maxnum;
 	global $switchtime;
 	global $banner;
 	global $coronalink;
	global $test_enabled;
	global $mail_to;
	global $baseurl;

	$myfile = fopen("attend.cfg.php", "w");
	fwrite($myfile, "<?php\n");
	fwrite($myfile, "$"."baseurl = \"".$baseurl."\";\n");
	fwrite($myfile, "$"."adminpw = \"".$adminpw."\";\n");
	fwrite($myfile, "$"."maxnum = \"".$maxnum."\";\n");
	fwrite($myfile, "$"."switchtime  = \"".$switchtime ."\";\n");
	fwrite($myfile, "$"."banner  = \"".$banner."\";\n");
	fwrite($myfile, "$"."test_enabled  = \"".$test_enabled ."\";\n");
	fwrite($myfile, "$"."mail_to = \"".$mail_to."\";\n");
	fwrite($myfile, "$"."coronalink  = \"".$coronalink."\";\n");
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


// Retrieve the maximum number of all the lines currently already in the file
function getMaxNum($file) {
	$maxnum = 0;
	if (file_exists($file)) {
		$handle = fopen($file, "r");
		while(!feof($handle)){
		  $line = fgets($handle);
		  //print("line=".$line."<BR>");
		  $cols = explode(";", $line);
		  $curnum = trim($cols[0]);
		  //print("cols[0]=".$cols[0]."<BR>");
		  //print("curnum=".$curnum."<BR>");
		  	if ($curnum >= $maxnum) {
				//print("$col[0] > $maxnum<BR>");
				$maxnum = $curnum;
		  	}
		}
		fclose($handle);
	}
	return $maxnum;
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


// Retrieve the number of lines corresponding to persons which attended
function getAttendeesFile($file) {
	$linecount = 0;
	if (file_exists($file)) {
		$handle = fopen($file, "r");
		while(!feof($handle)){
		  $line = fgets($handle);
     	  $cols = explode(";", $line);
		  if (count($cols) >= 7 && $cols[6] == 1) {
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
	$num = 0;
	if (file_exists($file)) {
		$handle = fopen($file, "r");
		while(!feof($handle) && $found <= 4){
		  $found = 0;
		  $line = fgets($handle);
 		  $num  = strpos($line, ";");
		  if ($num > 0) {
			  $num = substr($line, 0, $num);
		  }

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
	if ($found > 4) {
		return $num;
	}
	else {
		return -1;
	}
}

// Send confirmation emails also for defaulfs
// #REQ074
function sendDefaultConfirmationEMails($defaults) {
	$lines = explode("\n", $defaults);
	for ($c = 0; $c < count($lines); $c++) {
	 	$line = $lines[$c];
		if (trim($line) != "") {
			$cols = explode(";", $line);
			$number = "#".$cols[0];
			$name = $cols[1];
			$email = $cols[5];

//			print($line."<BR>");
//			print($number."<BR>");
//			print($name."<BR>");
//			print($email."<BR>");

			sendConfirmationMail($email, $name, $number);
		}
	}
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

			sendDefaultConfirmationEMails($defaults);
		}
		//fwrite($myfile, "\n");
		fclose($myfile);
	}
}

// Send a test mail for testing mail providers w.r.t their anti spam policy
function sendTestMail($email) {
		global $mail_from_header;
  		$subject = "FeG Anmeldeseite Testmail";
		// header
		$header = $mail_from_header;
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
function sendBackupMail($myfile, $onoff) {
 		global $mail_to;
		global $oldname;
		global $mail_from_header;

  		$subject = $onoff." - ".$oldname;

		// header
		$header = $mail_from_header;
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



// Remove a person from the file.
// Do this only if all information matches, including the registration number.
function signOff($file, $name, $number) {
	$content = "";
	$globalfound = 0;

	if (strlen(trim($number)) == 0) {
		return 0;
	}

	// Create a backup here	(will be overridden and expires)
	unlink($file.".BACKUP.csv");
	//copy($file, $file.".BACKUP.csv"); // no longer needed, system appears to be safe, 22. Nov 2020

	if (file_exists($file)) {
		$handle = fopen($file, "r");
		while(!feof($handle)) {
		  $found = 0;
		  $line = fgets($handle);
		  if (strpos($line, trim($number.";")) > -1) {
		  	  $found++;
		  }
		  if (strpos($line, trim($name)) > -1) {
		  	  $found++;
		  }
		  //if (strpos($line, trim($street)) > -1) {
		  //	  $found++;
		  //}
		  //if (strpos($line, trim($city)) > -1) {
		  //	  $found++;
		  //}
		  //if (strpos($line, trim($phone)) > -1) {
		  //	  $found++;
		  //}
		  //if (strpos($line, trim($email)) > -1) {
		  //	  $found++;
		  //}

		  if ($found == 2) {
		  		// Remove
				$globalfound = 1;
		  } else {
		  		// Copy
				$content = $content.$line;
		  }
		}
		fclose($handle);

		// Write back content without found line
		$myfile = fopen($file, "w");
		fwrite($myfile, $content);
		fclose($myfile);
	}

	return $globalfound;
}


// Cleans up mobile passwords older than defined mimutes
// Returns the mobile file linked to the deleted file iff a password was deleted, "" otherwise
// #REQ065
function cleanupMobilePassword() {
	global $mobiletimeout; // hours
	global $mobilefolder;
	$mobileFileLinkedToDeletedPW = "";
	$olderthanxxxweeks = 60*$mobiletimeout;
	foreach (glob($mobilefolder."*.txt") as $file) {
 		$diff = time() - filectime($file);
		if($diff > $olderthanxxxweeks){
			$handle = fopen($file, "r");
  		    $mobileFileLinkedToDeletedPW = trim(fgets($handle));
			fclose($handle);
			unlink($file);
		}
	}
	return $mobileFileLinkedToDeletedPW;
}

// Create a new mobile password to editing attendance list
function createMobilePassword($file) {
 	global $mobilefolder;
	$mobilePW = rand(1000, 9999);
    $ipfile = fopen($mobilefolder.$mobilePW.".txt", "w");
	fwrite($ipfile, $file."\n");
	fclose($ipfile);
	return $mobilePW;
}

// Return the file or "" if a wrong password is used
function getMobilePasswordFile($pw) {
 	global $mobilefolder;
    $mobilefile = $mobilefolder.$pw.".txt";
	if (file_exists($mobilefile)) {
		$file = "";
		$handle = fopen($mobilefile, "r");
  	    $file = trim(fgets($handle));
		fclose($handle);
		return $file;
	} else {
		// Not found / wrong password
		return "";
	}
}


function setValue($file, $number, $col, $val) {
	$content = "";
	$globalfound = 0;

	// Create a backup here	(will be overridden and expires)
	unlink($file.".BACKUP.csv");
	copy($file, $file.".BACKUP.csv");

	if (file_exists($file)) {
		$handle = fopen($file, "r");
		while(!feof($handle)) {
		  $found = 0;
		  $line = trim(fgets($handle));
		  // Remove line break
		  $line = str_replace("\n", "", $line);
 		  $line = str_replace("\r", "", $line);

		  $cols = explode(";", $line);

		  if (trim($cols[0]) == $number && $line != "") {
			   	$globalfound = 1;
		  		// number found, edit entry

				// Fill up, if additional columns are needed
				while(count($cols) < $col) {
					$line .= ";";
  				    $cols = explode(";", $line);
				}
				// Edit entry
				$cols[$col] = $val;
				// Rebuild line and add line break again
				$line = implode(";",$cols);
		  } // end if number found
		  // Build new content
		  $content = $content.$line."\n";
		} // end while
		fclose($handle);

		// Write back content without found line
		$myfile = fopen($file, "w");
		fwrite($myfile, $content);
		fclose($myfile);

	}
	return $globalfound;
}

function setAttendance($file, $number, $there) {
	return setValue($file, $number, 6, $there);
}


// In case of a wrong password, add IP to monitoring
function addWrongLogin($ip) {
	global $ipfolder;
    $ipfile = fopen($ipfolder.$ip.".txt", "a");
	$line =	stringDateTime(time());
	fwrite($ipfile, $line."\n");
	fclose($ipfile);
}

// Count already wrong passwords (within the last hour)
function countWrongLogins($ip) {
	global $ipfolder;
	return getLinesFile($ipfolder.$ip.".txt");
}

// Reset wrong passwords for this ip after successful login
function resetWrongLogins($ip) {
	global $ipfolder;
	unlink($ipfolder.$ip.".txt");
}

// Cleanup wrong password counter after one hour
function cleanupWrongLogins() {
	global $ipfolder;
	// Delete all wrong IP files older than 60 minutes (60 minutes * 60 seconds)
	$olderthanxxxseconds = 60*60;
	foreach (glob($ipfolder."*.txt") as $file) {
 		$diff = time() - filectime($file);
		if($diff > $olderthanxxxseconds){
			unlink($file);
   			//print("unlink:".$file." : ".$diff."<BR>");
 		} else {
			//print("NOT unlink:".$file." : ".$diff."<BR>");
		}
	}
}

// Test if the wrong password has been submitted from this IP already too often
function isLockedIp($ip) {
 global $lockip_number_of_wrong_password_trials;
 return (countWrongLogins($ip) >= $lockip_number_of_wrong_password_trials);
}

// Prints an error message, indicating that the wrong password has been entered too often
function lockIPPage() {
   header('HTTP/1.1 500 Internal Server Error');
   printf("<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">
<html><head>
<title>500 Internal Server Error</title>
</head><body>
<h1>Internal Server Error</h1>
<p>The server encountered an internal error or
misconfiguration and was unable to complete
your request.</p>
<p>Please contact the server administrator,
 [no address given] and inform them of the time the error occurred,
and anything you might have done that may have
caused the error.</p>
<p>More information about this error may be available
in the server error log.</p>
<hr>
<address>Apache/2.2.22 (Debian) Server at www.feg-kiel.de Port 443</address>
</body></html>
");
exit;
}


?>