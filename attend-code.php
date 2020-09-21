<?php
	$code = $_GET['code'];
	$test = $_GET['test'];
	
	// Load helper functions
	require("attend-functions.php");
	
	// Load a captcha calculation and display it as an image
    $codefile = "./captcha/".$code.".txt";
	$show = "";
	$returnValue = 0;
	if (file_exists($codefile)) {
		$handle = fopen($codefile, "r");
   	    $show = trim(fgets($handle));
		fclose($handle);
	} 	

	$target_layer = imagecreatetruecolor(92,28);
    $captcha_background = imagecolorallocate($target_layer, 204, 204, 204);
    imagefill($target_layer,0,0,$captcha_background);

	// Notify that test is enabled
	if ($test == $test_secret && $test_enabled == 1) {
    	$captcha_text_color = imagecolorallocate($target_layer, 255, 0, 0);
	    imagestring($target_layer, 5, 10, 5, "TEST MODE", $captcha_text_color);
	} else {
    	$captcha_text_color = imagecolorallocate($target_layer, 0, 0, 0);
	    imagestring($target_layer, 5, 10, 5, $show, $captcha_text_color);
	}
		
	header("Content-type: image/jpeg");
    imagejpeg($target_layer);
?>
