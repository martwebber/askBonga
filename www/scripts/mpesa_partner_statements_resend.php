<?php session_start();
ob_start();
	require_once "../util/functions.php";

	$error = ""; 
	
			     //first check if user is logged in
     $please_login_url = "please_login.php";
	 $status = check_logged_in($please_login_url);
		
	if ($status == 1) { 
	
	insert_header2();

	//initialise database object
   $BONGA_DB_TYPE = "oracle";
   //$BONGA_DATABASEHOST = "172.29.221.57";
   $BONGA_DATABASEHOST = "10.5.72.82";
   #$BONGA_DATABASEHOST = "172.29.221.151";
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "merchant_statements";
    $BONGA_DATABASEPASSWORD = "l3tm31n#";
    $BONGA_DATABASENAME = "tibcodb";
		

    if (isset($_POST['msisdn'])) {
        $validator =  new Validate();
        if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
            $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
        }   
		
		$array_user_details = array (
			'msisdn' => array("MSISDN", $_POST['msisdn'], 1, 11),
			'sr_number' => array("INTERACTION ID", $_POST['sr_number'], 1, 14), 
			'serial_number' => array("SERIAL NUMBER", $_POST['serial_number'], 1, 1) 
		);  

        $error = validate($array_user_details);

		if ($error == "") {
			$msisdn = substr(trim($_POST['msisdn']),-9);
			$serial_number = trim($_POST['serial_number']);

			if ($error == "") {
				//create subscriber notification
				$db = new Database($BONGA_DB_TYPE, $BONGA_DATABASEHOST, $BONGA_DATABASEPORT, $BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASENAME);
				$db->open_connection();
				//SEND_SMS(IN_MSISDN IN NUMBER, IN_MESSAGE IN VARCHAR2)
				$query = "BEGIN STMT_RESEND_STATEMENT(:IN_MSISDN, :IN_SERIAL_NUMBER, :IN_SR_NUMBER, :IN_USER, :OUT_MESSAGE); END;";
				$cursor = ocinewcursor($db->link);
				$stmt = ociparse ($db->link, $query);                                //bind variables
				$in_msisdn = $msisdn;
				$in_sr_number = "".$_POST['sr_number'];
				$out_message = "Request queued for processing. Kindly confirm receipt with subscriber";
				$user = "".$_SESSION["USERID"];
	
				logmessage("DEBUG", "Resend STMT_RESEND_STATEMENT: ".$in_msisdn."; $serial_number; ".$_POST['sr_number']."; ".$_SESSION['USERID']);
				ocibindbyname($stmt, ":IN_MSISDN", &$in_msisdn, -1);
				ocibindbyname($stmt, ":IN_SERIAL_NUMBER", &$serial_number, -1);
				ocibindbyname($stmt, ":IN_SR_NUMBER", &$in_sr_number, -1);
				ocibindbyname($stmt, ":IN_USER", &$user, -1);
				ocibindbyname($stmt, ":OUT_MESSAGE", &$out_message, -1);
				
				//$_SESSION["USERID"]
	
				//echo $quantity."<br />";
				//execute our stored procedure
				$result = ociexecute($stmt);
	
				if ($result) {
					//$error = "Request Successfully Submitted.";
					$error = $out_message;
				}
				else {
					$error = "Error submitting request. <br />Please contact system administrator";
					$e = oci_error($stmt);
					logmessage("ERROR", $e['message']);
				}
	
				$stmt_result = oci_free_statement($stmt);
				//logmessage("DEBUG", "OCI_FREE_RESULT: STMT - $stmt_result");
				$db->close_connection();
			}
		}	
	}  


?>

<div class="cspacer">
	<?php
		if ($error !=  "") {
			echo "<center>\r\n";
			echo "<div style='text-align: left; width: 80%'>\r\n";
			echo "<table class='tablebody border' width='100%'>\r\n";
			echo "<th class='tableheader'>MESSAGE</th>\r\n";
			echo "<tr><td>\r\n";
			echo "<p class='error'>".$error."</p>";
			echo "</td></tr>\r\n";
			echo "</table>\r\n";				
			echo "<br /><br />\r\n";
			echo "</div>\r\n";
			echo "</center>\r\n";
		}
	?>
	<div align="center" style="text-align:left; width:100%">
		<br />
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">M-PESA Partner Statements - Resend Statement</p>
        <br />
	
		<form name='acc_history_list' action='mpesa_partner_statements_resend.php.php' method='post'>
		<center>
		<table class="tablebody border" width="80%">
			<tr>
				<th colspan="2" class="tableheader"><b>MSISDN, REQUEST & SR INFO</b></th>
			</tr>
			<tr>
				<td><br />MSISDN:</td><td><br /><input type='text' name='msisdn' /></td>
			</tr>                        
			<tr>
				<td><br />REQUEST_ID:</td><td><br /><input type='text' name='serial_number' /></td>
			</tr>                        
			<tr>
				<td><br />INTERACTION ID:</td><td><br /><input type='text' name='sr_number' /></td>
			</tr>
			<tr>
				<td colspan="2"><br /><input type='submit' name='sub_msisdn' /></td>
			</tr>
		</table>
		</center>
	<br /><br />
		<?php
			if (isset($_POST['msisdn']) && $error == "") {
		?>
				<input type="hidden" name="confirm_deletion" id="confirm_deletion" />
			
				</form>
		<?php
			}
		?>
	</div>

<!-- insert the footer -->
<?php
	insert_footer2();
	
	
}else{
	
		header("Location: please_login.php");
	}	
	
ob_end_flush();
?>								