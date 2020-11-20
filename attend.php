<?php

$pw = $_POST['pw'];
if ($pw == "") {
	$pw = $_GET['pw'];
}
$verify = $_POST['verify'];
$code = $_POST['code'];
$val = $_POST['val'];
$submit = $_POST['submit'];
$waitinglist = $_POST['waitinglist'];
$download = $_POST['download'];
$print = $_POST['print'];
$mobile = $_POST['mobile'];
$mobilecmd = $_POST['mobilecmd'];
$mobilehide = $_POST['mobilehide'];
$mobileval = $_POST['mobileval'];
$mobileedit = $_POST['mobileedit'];
$mobiletext = $_POST['mobiletext'];
$mobileattend = $_POST['mobileattend'];
$csvfile = $_POST['csvfile'];
$npw1 = $_POST['npw1'];
$npw2 = $_POST['npw2'];
$filecontent = $_POST['filecontent'];

$nmaxnum = $_POST['nmaxnum'];
$nswitchtime = $_POST['nswitchtime'];
$nbanner = $_POST['nbanner'];

$signoff = $_POST['signoff'];
$number = $_POST['number'];


$save = $_POST['save'];
$savefile = $_POST['savefile'];
$name = $_POST['name'];
$street = $_POST['street'];
$city = $_POST['city'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$test = $_POST['test'];
if ($test == "") {
	$test = $_GET['test'];
}

$specialdate = $_POST['d'];
if ($specialdate == "") {
	$specialdate = $_GET['d'];
}
$specialdatesubmit = $_POST['specialdatesubmit'];



 // Load helper functions
 require("attend-functions.php");
 // Load configuration
 // #REQ015
 require("attend.cfg.php");
 
 // Delete all old mobile passwords
// #REQ065
$mobileFileCleanedUp = cleanupMobilePassword();
if ($mobileFileCleanedUp != "") {
	// #REQ072
	$mobiledate = stringDateFull(getDateFromFile($mobileFileCleanedUp));
	$oldname = "ANWESENHEITSLISTE ".$mobiledate;
	sendBackupMail($mobileFileCleanedUp, "BACKUP ");
}

 // Test if we have a correct mobile password
 // #REQ067
 // #REQ068
 $mobileFile = getMobilePasswordFile($pw);
 $isMobilePrint = ($mobileFile != "");
 if ($isMobilePrint) {
	$mobileCode = $pw; // save this for FORM POST
 	$pw = ""; // clear pw so that we do not run into locking us up ... it has not been the admin mode
 }
 
 // Test if we are the admin, i.e., if the entered password corresponds to the admin pw
 // #REQ001 
 // #REQ002
 $isAdmin = (md5($pw) == $adminpw) || ($pw == $adminpw && $adminpw == "admin");
 
 $testmode = false;
 // Notify viewer about test mode
 if ($test == $test_secret && $test_enabled == 1) {
 		$testmode = true;
 }

 
 $ip = $_SERVER['REMOTE_ADDR'];
 if ($pw == "" || $isAdmin) {
	 // Cleanup old trials after one hour
	 // #REQ003
	 cleanupWrongLogins();
 }
 // Check if locked
 if (isLockedIp($ip)) {
 	 // If too many wrong logins, lock the server for one hour
	 // #REQ004
	 // #REQ005
	 lockIPPage();
 }
 $wrongadminpassword = false;
 if ($pw != "" && !$isAdmin) {
 	// Wrong password => count up for this ip
	// #REQ005
	addWrongLogin($ip);
	// #REQ055
	 $wrongadminpassword = true;
 } else if ($isAdmin) {
 	// Reset counter on successfull login
	 // #REQ005
	 // #REQ006
	resetWrongLogins($ip);
 }
 if (!$isAdmin) {	
 	$pw = "";
 }
 
 
 
// #REQ076
// Create special date if valid input
if ($specialdatesubmit != "") {
		$tmpdate = date_create_from_format('d.m.Y', $specialdate);
		// Overwrite special date now with timestamp
		$specialdate = $tmpdate->getTimestamp();
		// already fill file
		$csvfile = currentFile();

		if ($specialdate > time()) {
			// Only if it is in the future, create the CSV file:
			// As current file is referred to as the $specialdate ("d"-parameter) if (a) the file already exists or (b) the user is the admin
			// here the user is the admin (b) and the file will be created. 
			// After that, the succeding link is usable by non-administrators. 
			ensureCurrentFileExists();
			
			// Display the link.
		    print("<center>FeG Kiel<BR>Anmeldeliste f&uuml;r den Spezialtermin am<BR>");
		    print("<font size=7>".stringDateFull(getDateFromFile($csvfile))."</font><br>");
		    print("<br><br>Nutze den folgenden Link f&uuml;r diesen Termin:<br><br>");

			print("<b><a href='".$baseurl."?d=".$specialdate."'>".$baseurl."?d=".$specialdate."</a></b>");
			print("</H1></center>");
			exit;
		}
}
 
 
 // If no csv file is selected, open the current file/sunday as the default
if ($csvfile == "") {
	$csvfile = currentFile();
}
 
if ($isAdmin && $savefile != "") {
	// On save: Replace the content of the csv file with the content of the textfield
	$freebefore = $maxnum-getLines();
	file_put_contents($csvfile, $filecontent);
	$freeafter = $maxnum-getLines();
	if ($freebefore < $freeafter) {
		// send waiting list notification
		// #REQ063
		sendWaitingListMails();
	}
} else if ($isAdmin) {
	// On non-save: Open/load the (selected or default) csv file
	$filecontent = file_get_contents($csvfile);
}


// Testmail enabled for debugging only
//sendTestMail("delphino@gmx.de");
//sendTestMail("delphino79@googlemail.com");
//sendTestMail("delphino79@gmail.com");

 // If download mode: Then return the requested csv file. Do this only for admins.
 // #REQ006
 if ($isAdmin && $download != "") {
	header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($csvfile));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: '.filesize($csvfile));
    print(file_get_contents($csvfile));
    exit;
 }
 
 // If print mode: Print the requested csv file. Do this only for admins.
 // #REQ007
 if ($isAdmin && $print != "") {
   print("<center>FeG Kiel<BR>Anmeldeliste f&uuml;r den GoDi am<BR>");
   print("<font size=7>".stringDateFull(getDateFromFile($csvfile))."</font><br>");
   print(getLinesFile($csvfile)." Personen angemeldet.<BR>Stand vom ".stringDateTime(time()).".");
   print("<br><br>");
   printTableHeader();
   if (file_exists($csvfile)) {
		$handle = fopen($csvfile, "r");
		while(!feof($handle) && $found == 0){
		  $line = fgets($handle);
		  if (trim($line) != "") {
			  $cols = explode(";", $line);
			  printTableRow($cols[0], $cols[1], $cols[2], $cols[3], $cols[4], $cols[5]);
		  }
		}
		fclose($handle);
   }
   printTableFooter();
   print("</center>");
   // Do not call print dialog 	in test mode to prevent hang of cypress (not closing the native print window)
   if (!$testmode) {
     	print("<script>window.print()</script>");
   }
   exit;
 }	
?>



<?php
 // If mobile button from admin is pressed, a mobile admin code is created
 // #REQ066
 if ($isAdmin && $mobile != "") {
	$mobilePW = createMobilePassword($csvfile);
    print("<center>FeG Kiel<BR>Anmeldeliste f&uuml;r den GoDi am<BR>");
    print("<font size=7>".stringDateFull(getDateFromFile($csvfile))."</font><br>");
    print(getLinesFile($csvfile)." Personen angemeldet.<BR>");
    print("<br><br>");


	print("<a href='".$baseurl."?pw=".$mobilePW."'>".$baseurl."?pw=".$mobilePW."</a>");
	print("<BR><BR><BR><H1>Mobile Password: <b><div name='mobilepw'>".$mobilePW."</div></b></H1></center>");
	exit;
 }

// #REQ071
 if ($isMobilePrint && $mobiletext != "") {
 	// save changed
	//setValue($file, $number, $col, $val) 
	setValue($mobileFile, $mobileval, $mobilecmd, $mobiletext);
 }

 // #REQ069
 // #REQ070
 if ($isMobilePrint && $mobileattend != "") {
	setAttendance($mobileFile, $mobileval, $mobilecmd);
 }

 // If mobile print mode: Print the requested csv file. Do this only for admins.
 // #REQ008
 if ($isMobilePrint) {
	print('<!doctype html>');
	print('<html lang="de">');
	print('<head>');
	print('	<meta charset="utf-8">');
	print('    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">');
	print('	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">');
	print('	<title>Anmelden zum Gottesdienst</title>');
	print('<meta name="viewport" content="width=600">');
	print('</head>');
	print('<body>');
   print("<div class=\"text-center\">FeG Kiel<BR>Anmeldeliste f&uuml;r den GoDi am<BR>");
   print("<font size=7>".stringDateFull(getDateFromFile($mobileFile))."</font><br>");
   print(getLinesFile($mobileFile)." Personen angemeldet.<BR>");
   print(getAttendeesFile($mobileFile)." Personen erschienen.<BR>Stand vom ".stringDateTime(time()).".");

   
   print("<br><br></div>");
   printTableHeaderMobile();
   if (file_exists($mobileFile)) {
		$handle = fopen($mobileFile, "r");
		while(!feof($handle) && $found == 0){
		  $line = fgets($handle);
		  if (trim($line) != "") {
			  $cols = explode(";", $line);
			  printTableRowMobile($cols[0], $cols[1], $cols[2], $cols[3], $cols[4], $cols[5], $cols[6], $mobileCode, $mobileval);
		  }
		}
		fclose($handle);
   }
   printTableFooterMobile($mobileval); // $mobileval is the num element to scroll to
   print("</body>");
   exit;
 }	
?>


<!doctype html>
<html lang="de">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<title>Anmelden zum Gottesdienst</title>
    <style type="text/css">
<!--
.style1 {color: #FFFFFF}

.blink_text {

    animation:1s blinker linear infinite;
    -webkit-animation:1s blinker linear infinite;
    -moz-animation:1s blinker linear infinite;

     color: white;
    }

    @-moz-keyframes blinker {  
     40% { opacity: 1.0; }
     50% { opacity: 0.0; }
     100% { opacity: 1.0; }
     }

    @-webkit-keyframes blinker {  
     40% { opacity: 1.0; }
     50% { opacity: 0.0; }
     100% { opacity: 1.0; }
     }

    @keyframes blinker {  
     40% { opacity: 1.0; }
     50% { opacity: 0.0; }
     100% { opacity: 1.0; }
     }

-->
    </style>
</head>
<body>
<?php
if ($testmode) {
	print('<table width="100%" border="0" cellpadding="10" cellspacing="0" bgcolor="#FF0000"><tr><th scope="col"><span class="style1"><center>TEST MODE ONLY - FOR PRODUCTION USE, DISABLE TEST_ENABLED IN CONFIG  </center></span></th></tr></table>');
}
if (trim($banner) != "") {
	print('<table width="100%" border="1" cellpadding="10" cellspacing="0" bgcolor="#00AA00"><tr><th scope="col"><p style="font-size:1px"><br></p><span class="style1">
<center><h4><span class="blink_text">'.$banner.'</span></h4></center></span></th></tr></table>');
}
?>

<p>&nbsp;</p>
<div class="container">
<h1>Anmeldung f&uuml;r <b><?php print(stringDate(nextSunday(time()))); ?></b> 
<?php
$freeseats = $maxnum-getLines();
if ($freeseats > 1) {
	// #REQ054
	print('<span class="badge badge-success">Noch '.$freeseats.' Pl&auml;tze frei</span>');
} else if ($freeseats == 1) {
	// #REQ054
	print('<span class="badge badge-success">Noch '.$freeseats.' Platz frei</span>');
}
else {
	// #REQ054
	// #REQ052
	print('<span class="badge badge-danger">Keine freien Pl&auml;tze</span>');
}
?>
</h1>
<p>



<?php
if ($waitinglist != "" || $maxnum-getLines() == 0) {
	print("<p><b>MOMENTAN NUR WARTELISTE</b> (siehe unten)</p>");
}

if (!fixedDate()) {
	// normal sunday text
	print('Registriere Dich <b>(bis sp&auml;testens sonntags um 10:00 Uhr)</b> mit dem untenstehenden Formular, wenn Du kommenden <b>Sonntag, den '.(stringDate(nextSunday(time()))).'</b>, vor Ort am Gottesdienst teilnehmen m&ouml;chtest. ');
}
else {
	// fixed date text
	print('Registriere Dich mit dem untenstehenden Formular, wenn Du am <b>'.(stringDate(nextSunday(time()))).'</b>, vor Ort am Gottesdienst teilnehmen m&ouml;chtest. ');
}
?>
Du stimmst damit zu, Dich an die g&uuml;ltigen Corona-Richtlinien zu halten. Diese findest Du auf unserer <a href="https://feg-kiel.de/2020-10-30-neue-corona-richtlinien-fuer-unsere-gottesdienste-11-20" target="_blank">Website</a>. Alternativ bist Du eingeladen, den Gottesdienst auf unserem Youtube-Kanal zu verfolgen unter <a href="http://youtube.feg-kiel.de">youtube.feg-kiel.de</a>.</p>
<p>Mit Deiner Registrierung erkl&auml;rst Du Dich au&szlig;erdem einverstanden, da&szlig; Deine pers&ouml;nlichen Daten im Rahmen der Corona-Landesverordnung für vier Wochen gespeichert werden und nur von berechtigten Personen zu administrativen Zwecken eingesehen werden können. Nach Ablauf der vier Wochen werden Deine Daten automatisch gel&ouml;scht.</p>

<?php  

if ($waitinglist != "" || $maxnum-getLines() == 0) {
	print("<p><b>MOMENTAN NUR WARTELISTE</b></p><p>Momentan sind keine oder nicht ausreichend Sitzpl&auml;tze vor Ort verf&uuml;gbar. Du kannst Dich deshalb momentan nur mit Deiner E-Mail-Adresse auf die Warteliste eintragen. Falls sich jemand wieder vom Live-GoDi abmeldet, wirst Du per E-Mail informiert und kannst Dich dann hier ggf. noch anmelden. <BR><i>Achtung: Ein Platz auf der Warteliste reicht <u>nicht</u> aus, um vor Ort zum GoDi zu kommen!</i></p>");
}

// Bootstrap styles
define("DIV_ALERT_DANGER", "<div class='alert alert-danger' role='alert'>");
define("DIV_ALERT_SUCCESS", "<div class='alert alert-success' role='alert'>");
define("DIV_ALERT_WARNING", "<div class='alert alert-warning' role='alert'>");
define("DIV_ALERT_INFO", "<div class='alert alert-info' role='alert'>");
define("END_DIV", "</div>");



// Delete all captcha files older than 5 minutes (5 minutes * 60 seconds)
// #REQ009
$olderthanxxxseconds = 60*5;
foreach (glob("./captcha/*.txt") as $file) {
 	$diff = time() - filectime($file);
	if($diff > $olderthanxxxseconds){
		unlink($file);
   		//print("unlink:".$file." : ".$diff."<BR>");
 	}
}


// Delete all CSV files that are older than 5 weeks (5 weeks * 7 days * 24 hours * 60 minutes * 60 seconds
// #REQ010
$olderthanxxxweeks = 60*60*24*7*5;
foreach (glob("./data/*.csv") as $file) {
// 	$diff = time() - filectime($file); // OLD from file creation time
	$diff = time() - getDateFromFile($file); // NEW from file name
	//print("<br><p style=\"color:white\"> test:".$file." : '".$diff."'</p>");
	if($diff > $olderthanxxxweeks){
		$found = ($file == $default_csv_file);
		$found =  $found || ($file == waitingListFile());
		//print("<p style=\"color:white\"> unlink test:".$file." : '".$found."'</p>");
		if (!$found) {
			// Do not delete the defaults file
			unlink($file);
   			//print("<p style=\"color:white\">... UNLINK:".$file." : ".$diff."</p>");
		} else {
   			//print("<p style=\"color:white\">... REMAIN:".$file." : ".$diff."</p>");
		}
 	} else {
   			//print("<p style=\"color:white\">... REMAIN:".$file." : ".$diff."</p>");
	}
}

// Make sure current file exists
// #REQ011
// #REQ062
ensureCurrentFileExists();

// Captcha verification of last captcha 
// #REQ012
$codecorrect = verifyCode($code, $verify);
$oldcode = $code;
 
// On every loading of the page, create a new captcha and save it in apropriate directory
// #REQ013
$z1 = rand(1, 300);
$z2 = rand(1, 100);
$code = rand(10000, 99999);
$myfile = fopen("./captcha/".$code.".txt", "w");
fwrite($myfile, $z1."+".$z2."=?\n");
fwrite($myfile, ($z1+$z2)."\n");
fclose($myfile);

// Save configuration if admin
// #REQ014
if ($save != "" && $isAdmin) {
    if ($npw1 != "" && $npw1 == $npw2) {
		// Change password if np1 and np2 are present and equal
		$adminpw = md5($npw1);
	}
	$maxnum = $nmaxnum;
	$switchtime = $nswitchtime;
	$banner = $nbanner;
	writeConfig();
	print("Saved");
}

// Current (succesffull) registration number
$regnumber = "";
// The name of the current (successfull) registration
$oldname = "";

$err = 0;

// Error text fields to be positions at the corresponding fields
$errTextGeneral = "";
$errTextName = "";
$errTextStreet = "";
$errTextCity = "";
$errTextPhone = "";
$errTextEmail = "";
$errTextCaptcha = "";
$waitinglistbutton = 0; // if != 0 then display this instead of submit button


// If the sumbit or signoff button was pressed
if ($submit != "" || $signoff != "" ||  $waitinglist != "") {
	// Prevent any attacks by fixing the fields
	// #REQ016
    $name = fix($name);
    $street = fix($street);
    $city = fix($city);
    $phone = fix($phone);
    $email = fix($email);
	// Display any errors in red to the user, so he or she can correct it
    $err = 0;
	
	// #REQ017
	if ($submit != "") { 
		$num = isAlreadyRegistered(currentFile(), $name, $street, $city, $phone, $email);
		if ($num > 0) {
			$errTextGeneral .= DIV_ALERT_DANGER . "Du bist f&uuml;r den ".stringDateFull(nextSunday(time()))." schon unter #".$num." angemeldet!" . END_DIV;
			$err = 1;
		}
	}
	if ($waitinglist == "") {
		// #REQ018
	 	if (!isValidName($name)) {
			$errTextName .= DIV_ALERT_WARNING . "Gib Deinen Vor- UND Nachnamen an." . END_DIV;
			$err = 1;
		}
		// #REQ019
	 	if (!isValidStreet($street)) {
			$errTextStreet .= DIV_ALERT_WARNING . "Gib Deine Stra&szlig;e und Hausnummer an." . END_DIV;
			$err = 1;
		}
		// #REQ020
	 	if (!isValidCity($city)) {
			$errTextCity .=  DIV_ALERT_WARNING . "Gib Deine Postleitzahl und Stadt an." . END_DIV;
			$err = 1;
		}
		// #REQ021
	 	if (!isValidPhoneNumnber($phone)) {
			$errTextPhone .= DIV_ALERT_WARNING . "Gib Deine Telefonnummer an." . END_DIV;
			$err = 1;
		}
	}
	// #REQ022
 	if (!isValidEmail($email)) {
		$errTextEmail .= DIV_ALERT_WARNING . "Gib Deine E-Mail-Adresse an." . END_DIV;
		$err = 1;
	}
	// Only in submit mode test, if enough seats are available
	if ($submit != "") {
		// #REQ060
		$waitinglistlink = " <a href=\"#waitinglistsection\"><br>M&ouml;chtest Du bei einem freien Platz benachrichtigt werden? Dann trage unten Deine E-Mail-Addresse ein, um auf die Warteliste zu kommen!</a>";
		// #REQ023
		// #REQ024
		$numpersons = count(explode(",", $name));
		if (($maxnum-getLines()-$numpersons) < 0) {
			if ($maxnum-getLines() == 0) {
				// #REQ024
				$errTextGeneral .= DIV_ALERT_DANGER . "Es sind leider schon alle Pl&auml;tze vergeben." .$waitinglistlink. END_DIV;
				$waitinglistbutton = 1;
			} else {
				// #REQ023
				$errTextGeneral .= DIV_ALERT_DANGER . "Es sind leider nicht gen&uuml;gend freie Pl&auml;tze vorhanden." .$waitinglistlink. END_DIV;
				$waitinglistbutton = 1;
			}
			// #REQ053 
			$err = 1;
		}
	}
	if ($codecorrect == 0 && !$testmode) {
		// #REQ012
		$errTextCaptcha .= DIV_ALERT_WARNING . "Bitte Rechenaufgabe korrekt l&ouml;sen!" . END_DIV;
		$err = 1;
	}
}

// #REQ056
if ($signoff != "" && $err == 0) {
	if (strlen(trim($number)) == 0) {
		// #REQ059
		print(DIV_ALERT_WARNING . "Zur Abmeldung gib bitte Deine Registrirungsnummer an!" . END_DIV);
	}
	else {
		$success = signOff(currentFile(), $name, $street, $city, $phone, $email, $number);
		if ($success) {
			$oldname = $name;
			// #REQ057
		   	sendBackupMail(currentFile(), "ABMELDUNG für ".stringDate(nextSunday(time())));
			print(DIV_ALERT_SUCCESS . $oldname." erfolgreich abgemeldet." . END_DIV);
			
			// #REQ063
			sendWaitingListMails();
		
			// Reset the text fields 
			// #REQXXX
			$name = "";
			$street = "";
			$city = "";
			$phone = "";
			$email = "";
		}
		else {
	    	// #REQ058
			print(DIV_ALERT_WARNING . "Anmeldung f&uuml;r ".$name." mit #".$number." nicht gefunden." . END_DIV);
	  	}
	}
}

if ($waitinglist != "" && $err == 0) {
	// #REQ061
	$myfile = fopen(waitingListFile(), "a");
	fwrite($myfile, $email."\n");
	fclose($myfile);	
	print(DIV_ALERT_SUCCESS . "'".$email."' erfolgreich auf der Warteliste f&uuml;r den ".stringDate(nextSunday(time()))." eingetragen!<br><br>Eine Testmail wurde an die Adresse geschickt. Bitte sorge daf&uuml;r, da&szlig; <i>noreply@feg-kiel.de</i> in Deiner Whitelist ist oder schaue in Deinem Spam-Ordner nach (Hinweis: GMX blockiert unsere Mails derzeit leider). Falls diese Testmail nicht ankam, wird auch eine evtl. Benachrichtigung nicht ankommen." . END_DIV);
	
	//send test mail
	// #REQ064
	sendWaitingListTestMail($email);
	
	// delete current captcha, prevent re-submission if "reload"/F5
	// #REQ025
	unlink("./captcha/".$oldcode.".txt");
			
	// Reset the text fields 
	$name = "";
	$street = "";
	$city = "";
	$phone = "";
	$email = "";
}

if ($submit != "" && $err == 0) {
	//$err = 0;
	// #REQ026
			// Iff no error was detected, then insert new registration to current file/sunday

		// delete current captcha, prevent re-submission if "reload"/F5
		// #REQ025
		unlink("./captcha/".$oldcode.".txt");

	    $regnumber = "";
		$curregnumber = getMaxNum(currentFile());
		$oldname = $name;
		// #REQ027
        $names = explode(",", $name);
		foreach ($names as $name) {
			if (strlen($regnumber) > 0) {
		 		$regnumber .= ", ";
			} 
			$curregnumber = ($curregnumber + 1);
			// #REQ027
			$regnumber .= "#".$curregnumber;
			$myfile = fopen(currentFile(), "a");
			fwrite($myfile, $curregnumber.";".$name."; ".$street."; ".$city."; ".$phone."; ".$email."\n");
			fclose($myfile);
		}
		
		// send anmelde info
 		sendConfirmationMail($email, $oldname, $regnumber);
		
		// Reset the text fields 
		// #REQ028
		// #REQ031
		$name = "";
		$street = "";
		$city = "";
		$phone = "";
		$email = "";
		
		// Send a backup mail with the just updated file
		// #REQ030
    	sendBackupMail(currentFile(), "Neue Anmeldung für ".stringDate(nextSunday(time())));
 }
 
 // On successfull registration, print the name (backup is in oldname) and the current new registration number under which he or she is listed
 // #REQ027
 if ($regnumber != "") {
 	 $plural = (strpos($regnumber, ",") > -1);
	 if (!$plural) {
		 print("<h3>Hallo ".$oldname."! Du bist nun erfolgreich f&uuml;r den GoDi am ".stringDate(nextSunday(time()))." angemeldet und zwar unter der Nummer ");
		 print("<span class='badge badge-success'>".$regnumber."</span>. ");
		 print("<b>Bitte bringe die Nummer zum Gottesdienst mit</b>! Wir freuen uns auf Dich!</h3>");
	 } else {
		 print("<h3>Hallo ".$oldname."! Ihr seid nun erfolgreich f&uuml;r den GoDi am ".stringDate(nextSunday(time()))." angemeldet und zwar unter den Nummern ");
		 print("<span class='badge badge-success'>".$regnumber."</span>. ");
		 print("<b>Bitte bringt die Nummern zum Gottesdienst mit!</b> Wir freuen uns auf Euch!</h3>");
	 }
 }
 
  
// The following are the raw input fields and the administration pan or password entry (if not logged in as admin)
// #REQ031
// Old entries are printed if not cleared
?>

<form id="form1" name="form1" method="post" action="">

<?php print($errTextGeneral); ?>
<div id="secname" class="form-group">
	<label for="name">Vorname Nachname <font color =#55BB55>(mehrere Personen durch <b>Komma</b> trennen!)</font></label>
	<?php print($errTextName); ?>
	<input class="form-control" name="name" type="text" id="name" value="<?php print($name);?>" placeholder="Lieschen M&uuml;ller, Max M&uuml;ller"/>
</div>
<div id="secstreet" class="form-group">
	<label for="street">Stra&szlig;e Hausnummer</label>
<?php print($errTextStreet); ?>
	<input class="form-control" name="street" type="text" id="street" value="<?php print($street);?>" placeholder="M&uuml;llerstra&szlig;e 42"/>
</div>
<div id="seccity" class="form-group">
	<label for="city">PLZ Ort</label>
<?php print($errTextCity); ?>
	<input class="form-control" name="city" type="text" id="city" value="<?php print($city);?>" placeholder="42424 M&uuml;llerstadt"/>
</div>
<div id="secphone" class="form-group">
	<label for="phone">Telefonnummer</label>
<?php print($errTextPhone); ?>
	<input class="form-control" name="phone" type="text" id="phone" value="<?php print($phone);?>" placeholder="0171424242"/>
</div>
<div class="form-group">
	<label for="email">E-Mail</label>
<?php print($errTextEmail); ?>
	<input class="form-control" name="email" type="text" id="email" value="<?php print($email);?>" placeholder="lieschenmueller@gmx.de"/>
</div>
<div class="form-group">
	<label for="verify"><img src="attend-code.php?code=<?php print($code); if($testmode){print("&test=".$test);}?>"/></label>
<?php print($errTextCaptcha); ?>
	<input name="code" type="hidden" id="code" value="<?php print($code);?>"/>
<?php
if($testmode) {
	print('<input name="test" type="hidden" id="code" value="'.$test.'"/>');
}
?>	
	
	<input class="form-control" name="verify" type="text" autocomplete="off" id="verify"  placeholder="Hier Ergebnis der Rechenaufgabe eintragen"/>
</div>

<script>
function waitinglistvisibility() {
 document.getElementById("secname").style.display="none"; 
 document.getElementById("secstreet").style.display="none"; 
 document.getElementById("seccity").style.display="none"; 
 document.getElementById("secphone").style.display="none"; 
}

function signoffvisibility() {
 document.getElementById("secname").style.display="inline"; 
 document.getElementById("secstreet").style.display="inline"; 
 document.getElementById("seccity").style.display="inline"; 
 document.getElementById("secphone").style.display="inline"; 
}
</script>


<?php 
if ($waitinglistbutton == 0 && $freeseats > 0) {
	print ('<input class="btn btn-primary" type="submit" name="submit" value="Anmelden zum Gottesdienst am '.stringDate(nextSunday(time())).'" />');
}
else {
	print("<div id='waitinglistsection'></div>");
	print ('<script> waitinglistvisibility()</script>');
	print("<input class=\"btn btn-primary\" type=\"submit\" name=\"waitinglist\" value=\"Bei freien Plätzen per E-Mail benachrichtigen\">");
}
?>


<script>
function signoffvisible() {
	var y = document.getElementById("signoffdiv"); 
	y.style.display="inline";
	
	signoffvisibility();
}
</script>
<BR>
<a class="btn btn-outline-secondary mt-5" href='javascript:signoffvisible();'>Abmelden</a>
<div id="signoffdiv">
  <br><br>Falls Du Dich doch leider wieder abmelden mu&szlig;t: Gib alle Angaben <b>oben</b> und zus&auml;tzlich <b>hier</b> Deine Registrierungsnummer an.<br>
  Jede Person mu&szlig; sich einzeln abmelden!<br>
  <table width="344" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>Nummer&nbsp;&nbsp;</td>
      <td><input class="form-control" name="number" autocomplete="off" type="text" id="number" value=""/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><label>
        <input class="btn btn-primary" type="submit" name="signoff" value="Abmelden vom Gottesdienst am <?php print(stringDate(nextSunday(time()))); ?>" />
      </label></td>
    </tr>
  </table>
</div>
<script>var x = document.getElementById("signoffdiv");x.style.display = "none";</script>
</form>

<script>
function adminvisible() {
	var y = document.getElementById("admin"); 
	y.style.display="inline";
}
</script>
<a class="btn btn-outline-secondary mt-5" href='javascript:adminvisible();'>Admin</a>
<div id="admin">
<?php 
	if (!$isAdmin) { 
		// #REQ055
		if ($wrongadminpassword) {
			print("<BR>not implemented<BR>");
		}
		// If not logged in as admin, display login password field
		// #REQ033
		require("attend-login.php");
    } else {
		// If logged in as admin, display all admin options
		// #REQ032
		require("attend-admin.php");
	}
?>
	
</div>

<p>
  <?php 
  // #REQ034
  if (!$isAdmin) { print("<script>var x = document.getElementById(\"admin\");x.style.display = \"none\";</script>"); } ?>
</p>

</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>