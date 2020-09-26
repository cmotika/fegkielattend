<?php

$pw = $_POST['pw'];
$verify = $_POST['verify'];
$code = $_POST['code'];
$val = $_POST['val'];
$submit = $_POST['submit'];
$download = $_POST['download'];
$print = $_POST['print'];
$mobile = $_POST['mobile'];
$csvfile = $_POST['csvfile'];
$npw1 = $_POST['npw1'];
$npw2 = $_POST['npw2'];
$filecontent = $_POST['filecontent'];

$nmaxnum = $_POST['nmaxnum'];
$nswitchtime = $_POST['nswitchtime'];

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

 // Load helper functions
 require("attend-functions.php");
 // Load configuration
 // #REQ015
 require("attend.cfg.php");
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
	 // #REQ00
	 cleanupWrongLogins();
 }
 // Check if locked
 if (isLockedIp($ip)) {
 	 // If too many wrong logins, lock the server for one hour
	 // #REQ004
	 // #REQ005
	 lockIPPage();
 }
 if ($pw != "" && !$isAdmin) {
 	// Wrong password => count up for this ip
	 // #REQ005
	addWrongLogin($ip);
 } else if ($isAdmin) {
 	// Reset counter on successfull login
	 // #REQ005
	 // #REQ006
	resetWrongLogins($ip);
 }
 if (!$isAdmin) {	
 	$pw = "";
 }
 
// Testmail enabled for debugging only
//sendTestMail($mail_to);

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
   print("<script>window.print()</script>");
   exit;
 }	
?>

<?php
 // If mobile print mode: Print the requested csv file. Do this only for admins.
 // #REQ008
 if ($isAdmin && $mobile != "") {
	print('<!doctype html>');
	print('<html lang="de">');
	print('<head>');
	print('	<meta charset="utf-8">');
	print('    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">');
	print('	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">');
	print('	<title>Anmelden zum Gottesdienst</title>');
	print('</head>');
	print('<body>');
   print("<center>FeG Kiel<BR>Anmeldeliste f&uuml;r den GoDi am<BR>");
   print("<font size=7>".stringDateFull(getDateFromFile($csvfile))."</font><br>");
   print(getLinesFile($csvfile)." Personen angemeldet.<BR>Stand vom ".stringDateTime(time()).".");
   print("<br><br></center>");
   printTableHeaderMobile();
   if (file_exists($csvfile)) {
		$handle = fopen($csvfile, "r");
		while(!feof($handle) && $found == 0){
		  $line = fgets($handle);
		  if (trim($line) != "") {
			  $cols = explode(";", $line);
			  printTableRowMobile($cols[0], $cols[1], $cols[2], $cols[3], $cols[4], $cols[5]);
		  }
		}
		fclose($handle);
   }
   printTableFooterMobile();
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
-->
    </style>
</head>
<body>
<?php
if ($testmode) {
	print('<table width="100%" border="0" cellpadding="10" cellspacing="0" bgcolor="#FF0000"><tr><th scope="col"><span class="style1"><center>TEST MODE ONLY - FOR PRODUCTION USE, DISABLE TEST_ENABLED IN THE CODE  </center></span></th></tr></table>');
}
?>

<p>&nbsp;</p>
<div class="container">
<h1>Anmeldung f&uuml;r <?php print(stringDate(nextSunday(time()))); ?>
<?php
$num = $maxnum-getLines();
if ($num > 1) {
	print('<span class="badge badge-success">Noch '.$num.' Pl&auml;tze frei</span>');
} else if ($num == 1) {
	print('<span class="badge badge-success">Noch '.$num.' Platz frei</span>');
}
else {
	print('<span class="badge badge-danger">Keine freien Pl&auml;tze</span>');
}
?>
</h1>
<p>Registriere Dich <b>(bis sp&auml;testens sonntags um 10:00 Uhr)</b> mit dem untenstehenden Formular, wenn Du kommenden Sonntag, den <b><?php print(stringDate(nextSunday(time()))); ?></b>, vor Ort am Gottesdienst teilnehmen m&ouml;chtest.
Du stimmst damit zu, Dich an die g&uuml;ltigen Corona-Richtlinien zu halten. Diese findest Du auf unserer <a href="https://feg-kiel.de/2020-05-15-aktuelle-infos-zum-gottesdienst-neustart" target="_blank">Website</a>. Alternativ bist Du eingeladen, den Gottesdienst auf unserem Youtube-Kanal zu verfolgen unter <a href="http://youtube.feg-kiel.de">youtube.feg-kiel.de</a>.</p>
<p>Mit Deiner Registrierung erkl&auml;rst Du Dich au&szlig;erdem einverstanden, dass Deine pers&ouml;nlichen Daten im Rahmen der Corona-Landesverordnung für vier Wochen gespeichert werden und nur von berechtigten Personen zu administrativen Zwecken eingesehen werden können. Nach Ablauf der vier Wochen werden Deine Daten automatisch gel&ouml;scht.</p>

<?php  

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
 	$diff = time() - filectime($file);
	if($diff > $olderthanxxxweeks){
		$found = ($file == $default_csv_file);
		//print("unlink test:".$file." : '".$found."'<BR>");
		if (!$found) {
			// Do not delete the defaults file
			unlink($file);
   			//print("unlink:".$file." : ".$diff."<BR>");
		}
 	}
}

// Make sure current file exists
// #REQ011
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
	writeConfig();
	print("Saved");
}



// Current (succesffull) registration number
$regnumber = "";
// The name of the current (successfull) registration
$oldname = "";

// If the sumbit button was pressed
if ($submit != "") {
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
	if (isAlreadyRegistered(currentFile(), $name, $street, $city, $phone, $email)) { 
		print(DIV_ALERT_DANGER . "Du bist f&uuml;r den ".stringDateFull(nextSunday(time()))." schon angemeldet!" . END_DIV);
		$err = 1;
	}
	// #REQ018
 	if (!isValidName($name)) {
		print(DIV_ALERT_WARNING . "Gib Deinen Vor- UND Nachnamen an." . END_DIV);
		$err = 1;
	}
	// #REQ019
 	if (strlen(trim($street)) < 5) {
		print(DIV_ALERT_WARNING . "Gib Deine Stra&szlig;e und Hausnummer an." . END_DIV);
		$err = 1;
	}
	// #REQ020
 	if (strlen(trim($city)) < 5) {
		print(DIV_ALERT_WARNING . "Gib Deine Postleitzahl und Stadt an." . END_DIV);
		$err = 1;
	}
	// #REQ021
 	if (!isValidPhoneNumnber($phone)) {
		print(DIV_ALERT_WARNING . "Gib Deine Telefonnummer an." . END_DIV);
		$err = 1;
	}
	// #REQ022
 	if (strlen(trim($email)) < 5) {
		print(DIV_ALERT_WARNING . "Gib Deine E-Mail-Adresse an." . END_DIV);
		$err = 1;
	}
	// #REQ023
	// #REQ024
	$numpersons = count(explode(",", $name));
	if (($maxnum-getLines()-$numpersons) < 0) {
		if ($maxnum-getLines() == 0) {
			// #REQ024
			print(DIV_ALERT_DANGER . "Es sind leider schon alle Pl&auml;tze vergeben." . END_DIV);
		} else {
			// #REQ023
			print(DIV_ALERT_DANGER . "Es sind leider nicht gen&uuml;gend Pl&auml;tze vorhanden." . END_DIV);
		}
		$err = 1;
	}
	if ($codecorrect == 0 && !$testmode) {
		// #REQ012
		print(DIV_ALERT_WARNING . "Bitte Rechenaufgabe korrekt l&ouml;sen!" . END_DIV);
		$err = 1;
	}

	//$err = 0;
	// #REQ026
	if ($err == 0) {
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
    	sendBackupMail(currentFile());
	}
 }
 
 // On successfull registration, print the name (backup is in oldname) and the current new registration number under which he or she is listed
 // #REQ027
 if ($regnumber != "") {
 	 $plural = (strpos($regnumber, ",") > -1);
	 if (!$plural) {
		 print("<h3>Hallo ".$oldname."! Du bist nun erfolgreich f&uuml;r den ".stringDate(nextSunday(time()))." angemeldet unter der Nummer ");
		 print("<span class='badge badge-success'>".$regnumber."</span>. ");
		 print("Bitte bringe die Nummer mit zum Gottesdienst. Wir freuen uns auf Dich!</h3>");
	 } else {
		 print("<h3>Hallo ".$oldname."! Ihr seid nun erfolgreich f&uuml;r den ".stringDate(nextSunday(time()))." angemeldet unter den Nummern ");
		 print("<span class='badge badge-success'>".$regnumber."</span>. ");
		 print("Bitte bringt die Nummern mit zum Gottesdienst. Wir freuen uns auf Euch!</h3>");
	 }
 }
 
  
// The following are the raw input fields and the administration pan or password entry (if not logged in as admin)
// #REQ031
// Old entries are printed if not cleared
?>

<form id="form1" name="form1" method="post" action="">
<div class="form-group">
	<label for="name">Vorname Nachname <font color =#88CC88>(mehrere Personen durch Komma trennen!)</font></label>
	<input class="form-control" name="name" type="text" id="name" value="<?php print($name);?>" placeholder="Lieschen M&uuml;ller, Max M&uuml;ller"/>
</div>
<div class="form-group">
	<label for="street">Stra&szlig;e Hausnummer</label>
	<input class="form-control" name="street" type="text" id="street" value="<?php print($street);?>" placeholder="M&uuml;llerstra&szlig;e 42"/>
</div>
<div class="form-group">
	<label for="city">PLZ Ort</label>
	<input class="form-control" name="city" type="text" id="city" value="<?php print($city);?>" placeholder="4242 M&uuml;llerstadt"/>
</div>
<div class="form-group">
	<label for="phone">Telefonnummer</label>
	<input class="form-control" name="phone" type="text" id="phone" value="<?php print($phone);?>" placeholder="0171424242"/>
</div>
<div class="form-group">
	<label for="email">E-Mail</label>
	<input class="form-control" name="email" type="text" id="email" value="<?php print($email);?>" placeholder="lieschenmueller@gmx.de"/>
</div>
<div class="form-group">
	<label for="verify"><img src="attend-code.php?code=<?php print($code); if($testmode){print("&test=".$test);}?>"/></label>
	<input name="code" type="hidden" id="code" value="<?php print($code);?>"/>
<?php
if($testmode) {
	print('<input name="test" type="hidden" id="code" value="'.$test.'"/>');
}
?>	
	
	<input class="form-control" name="verify" type="text" id="verify"  placeholder="Hier Ergebnis der Rechenaufgabe eintragen"/>
</div>
<input class="btn btn-primary" type="submit" name="submit" value="Anmelden zum Gottesdienst am <?php print(stringDate(nextSunday(time()))); ?>" />
</form>


<script>
function adminvisible() {
	var y = document.getElementById("admin"); 
	y.style.visibility="visible";
}
</script>

<a class="btn btn-outline-secondary mt-5" href='javascript:adminvisible();'>Admin</a>

<div id="admin">
<?php 
	if (!$isAdmin) { 
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
  if (!$isAdmin) { print("<script>var x = document.getElementById(\"admin\");x.style.visibility = \"hidden\";</script>"); } ?>
</p>

</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>