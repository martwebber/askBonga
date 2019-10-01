<?php
	include "../util/functions.php";
	
	$error = "";
	//initialise database object
	$BONGA_DB_TYPE = "oracle";
	$BONGA_DATABASEHOST = "10.65.12.12";
	$BONGA_DATABASEPORT = "1521";
	$BONGA_DATABASEUSER = "lms_data";
	$BONGA_DATABASEPASSWORD = "lms123";
	$BONGA_DATABASENAME = "promodb";
		
	// Get a file into an array
	//$lines = file('postpay_msisdns.txt');
	$lines = file('postpay.txt');

	$db = new Database($BONGA_DB_TYPE, $BONGA_DATABASEHOST, $BONGA_DATABASEPORT, $BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASENAME);
	$db->open_connection();
	
	foreach ($lines as $line_num => $line) {
		// Loop through our array
		$error = "";
		$msisdn = substr($line, 0, 12);                        
		$url = "https://10.25.202.55/bongapin/bonga_pin_gen.php?msisdn=".$msisdn."&user=bongapin&pass=pinbonga";
		$postVars = "msisdn=".$msisdn;                        
		$newPin = trim(curl_url_post ($url, "GET", $vars));		
		
		if (substr($newPin, 0, 2) == "-1") {
			$error = "We are experiencing technical difficulties, please try again later.";
			logmessage("ERROR", "Could not reset $msisdn");
		}		
		
		if ($error == "") {
			$query = "BEGIN RESET_PIN(:IN_MSISDN, :IN_SR_NUMBER, :IN_MESSAGE, :IN_SYSTEM_USER); END;";
			$cursor = ocinewcursor($db->link);
			$stmt = ociparse ($db->link, $query);                                //bind variables
			$in_msisdn = substr($msisdn, 3);
			$in_sr_number = "CM: POST INIT";
			$in_message = $newPin;
			$system_user = "lndei";

			logmessage("DEBUG", $in_msisdn."; ".$in_sr_number."; ".$in_message."; ".$system_user);
			ocibindbyname($stmt, ":IN_MSISDN", &$in_msisdn, -1);
			ocibindbyname($stmt, ":IN_SR_NUMBER", &$in_sr_number, -1);
			ocibindbyname($stmt, ":IN_MESSAGE", &$in_message, -1);
			ocibindbyname($stmt, ":IN_SYSTEM_USER", &$system_user, -1);

			//echo $quantity."<br />";
			//execute our stored procedure
			$result = ociexecute($stmt);

			if ($result) {
					$error = "Request Successfully Submitted.";
			}
			else {
					$error = "Error submitting request. <br />Please contact system administrator";
			}

			$stmt_result = oci_free_statement($stmt);
			logmessage("DEBUG", "OCI_FREE_RESULT: STMT - $stmt_result");
		}		
	}	
	$db->close_connection();		
?>
