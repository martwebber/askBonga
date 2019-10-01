<?php
	require_once "../util/functions.php";

	$error = ""; 
	
	  $error = "";
		
	 //first check if user is logged in
     $please_login_url = "please_login.php";
	 $status = check_logged_in($please_login_url);
		
	if ($status == 1) { 
	
	insert_header2();

	//initialise database object
	/*$BONGA_DB_TYPE = "oracle";
	$BONGA_DATABASEHOST = "10.65.12.12";
	$BONGA_DATABASEPORT = "1521";
	$BONGA_DATABASEUSER = "lms_data";
	$BONGA_DATABASEPASSWORD = "lms123";
	$BONGA_DATABASENAME = "promodb";	*/	
		
	$BONGA_DB_TYPE = "oracle";
	#$BONGA_DATABASEHOST = "172.29.225.3";
	#$BONGA_DATABASEHOST = "172.28.226.35";
	$BONGA_DATABASEHOST = "172.29.226.17";
	$BONGA_DATABASEPORT = "1521";
	$BONGA_DATABASEUSER = "bonyeza_chapa";
	$BONGA_DATABASEPASSWORD = "b0ny3zachapa";
	$BONGA_DATABASENAME = "eirdb";	
		
    if (isset($_POST['msisdn'])) {
        $validator =  new Validate();
        if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
            $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
        }   
		
		$array_user_details = array (
			'msisdn' => array("MSISDN", $_POST['msisdn'], 1, 11),
			'sr_number' => array("INTERACTION ID", $_POST['sr_number'], 1, 14) 
		);  

        $error = validate($array_user_details);

		if ($error == "") {
			$msisdn = "254".substr($_POST['msisdn'],-9);
			// Bonga PIN URL changed from https to http
                        $url = "http://10.25.202.55/bongapin/bonga_pin_gen.php?msisdn=".$msisdn."&user=bongapin&pass=pinbonga";
                        
			//$url = "https://10.25.202.61/bongapin/bonga_pin_gen.php?msisdn=".$msisdn."&user=bongapin&pass=pinbonga1";
			$postVars = "msisdn=".$msisdn;
			$newPin = trim(curl_url_post ($url, "GET", $vars));

			if (substr($newPin, 0, 2) == "-1") {
				$error = "We are experiencing technical difficulties, please try again later.";
				$in_msisdn = $_POST['msisdn'];
				$in_message = $newPin;	
				logmessage("ERROR", $in_msisdn."; ".$_POST['sr_number']."; ".$in_message."; ".$_SESSION['USERID']);
			}


if (substr($newPin, 0, 2) == "") {
                                $error = "We are experiencing technical difficulties,please use CRM";
                                $in_msisdn = $_POST['msisdn'];
                                $in_message = $newPin;
                                logmessage("ERROR", $in_msisdn."; ".$_POST['sr_number']."; ".$in_message."; ".$_SESSION['USERID']);
                        }


			if ($error == "") {
				//create subscriber notification
				$db = new Database($BONGA_DB_TYPE, $BONGA_DATABASEHOST, $BONGA_DATABASEPORT, $BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASENAME);
				$db->open_connection();
				//SEND_SMS(IN_MSISDN IN NUMBER, IN_MESSAGE IN VARCHAR2)
				$query = "BEGIN lms_reset_pin(:IN_MSISDN, :IN_SR_NUMBER, :IN_MESSAGE, :IN_SYSTEM_USER); END;";
				$cursor = ocinewcursor($db->link);
				$stmt = ociparse ($db->link, $query);                                //bind variables
				$in_msisdn = $_POST['msisdn'];
				$in_sr_number = "CM: ".$_POST['sr_number'];
				$in_message = $newPin;
	
				logmessage("DEBUG", $in_msisdn."; ".$_POST['sr_number']."; ".$in_message."; ".$_SESSION['USERID']);
				ocibindbyname($stmt, ":IN_MSISDN", &$in_msisdn, -1);
				ocibindbyname($stmt, ":IN_SR_NUMBER", &$in_sr_number, -1);
				ocibindbyname($stmt, ":IN_MESSAGE", &$in_message, -1);
				ocibindbyname($stmt, ":IN_SYSTEM_USER", &$_SESSION['USERID'], -1);
	
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
				$db->close_connection();
			}
		}	
	}   





        /*if (isset($_POST['confirm_deletion']) && $_POST['confirm_deletion'] != "") {
                $array_confirmed = split(";", $_POST['confirm_deletion']);
                $db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
                        for ($i = 0; $i < count($array_confirmed); $i++) {
                                $query = "DELETE FROM user_list WHERE ID = ";
                                if ($array_confirmed[$i] != null) {
                                        $query .= $array_confirmed[$i];
                                        $db->generic_sql($query);
                                        logmessage("INFO", "USER MANAGEMENT - USER: ".$_SESSION["USERID"]."; user account with ID: $array_confirmed[$i] deleted");
                                }
                        }
        }*/
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
			<!--<table class="tablebody border" width="100%">
					<tr>
							<th colspan="2" class="tableheader"><b>USER MANAGEMENT</b></th>
					</tr>
					<tr>                                <td><a href="user_registration.php">Add Record</a></td><td><a href="#" onclick="javascript: confirm_deletion('user_list');">Delete Record(s)</a></td>
					</tr>
			</table>-->
		<form name='acc_history_list' action='reset_bonga_pin.php' method='post'>
		<center>
		<table class="tablebody border" width="80%">
			<tr>
				<th colspan="2" class="tableheader"><b>MSISDN SEARCH</b></th>
			</tr>
			<tr>
				<td><br />MSISDN:</td><td><br /><input type='text' name='msisdn' /></td>
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
				<!--<input type="hidden" name="msisdn" id="msisdn" value="<?php echo $_POST['msisdn']; ?>" />-->
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
?>
