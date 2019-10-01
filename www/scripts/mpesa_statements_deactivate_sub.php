<?php
	require_once "../util/functions.php";

	$error = ""; 
	insert_header2();

	//initialise database object
/*	
    $BONGA_DB_TYPE = "oracle";
    $BONGA_DATABASEHOST = "10.65.12.11";
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "mauzo2012";
    $BONGA_DATABASEPASSWORD = "mauzo#2012";
    $BONGA_DATABASENAME = "mkeshodb";
*/

    /*$BONGA_DB_TYPE = "oracle";*/
   # $BONGA_DATABASEHOST = "172.29.225.1";
    $BONGA_DATABASEHOST = "172.28.226.35";
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "mauzo";
    $BONGA_DATABASEPASSWORD = "mauzo_12##";
    $BONGA_DATABASENAME = "eirdb";
	
	$BONGA_DB_TYPE = "oracle";
	//$BONGA_DATABASEHOST = "172.28.226.18";
    //$BONGA_DATABASEHOST = "172.28.226.33";
	//$BONGA_DATABASEHOST = "172.29.226.12";
      #$BONGA_DATABASEHOST = "svjcc1-scan";
    $BONGA_DATABASEHOST = "172.25.241.3";
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "sms_bundles";
    $BONGA_DATABASEPASSWORD = "Ang0la$123";
    $BONGA_DATABASENAME = "heko";
	
	

    if (isset($_POST['msisdn'])) {
        $validator =  new Validate();
        if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
            $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
        }   
		
		$array_user_details = array (
			'msisdn' => array("MSISDN", $_POST['msisdn'], 1, 11),
			'sr_number' => array("INTERACTION ID", $_POST['sr_number'], 1, 14), 
			'serial_number' => array("SERIAL NUMBER", "1000", 1, 1) 
		);  

        $error = validate($array_user_details);

		if ($error == "") {
			$msisdn = substr(trim($_POST['msisdn']),-9);
			//$serial_number = trim($_POST['serial_number']);

			if ($error == "") {
				//create subscriber notification
				$db = new Database($BONGA_DB_TYPE, $BONGA_DATABASEHOST, $BONGA_DATABASEPORT, $BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASENAME);
				$db->open_connection();
				//SEND_SMS(IN_MSISDN IN NUMBER, IN_MESSAGE IN VARCHAR2)
				$query = "BEGIN MSTMT_DEACTIVATE_SUB(:IN_MSISDN, :IN_SR_NUMBER, :IN_USER, :OUT_MESSAGE); END;";
				$cursor = ocinewcursor($db->link);
				$stmt = ociparse ($db->link, $query);                                //bind variables
				$in_msisdn = $msisdn;
				$in_sr_number = "".$_POST['sr_number'];
				$out_message = "Request queued for processing. Kindly confirm receipt with subscriber";
				$user = "".$_SESSION["USERID"];
	
				logmessage("DEBUG", "Call MSTMT_DEACTIVATE_SUB: ".$in_msisdn."; ".$_POST['sr_number']."; ".$_SESSION['USERID']);
				ocibindbyname($stmt, ":IN_MSISDN", &$in_msisdn, -1);
				//ocibindbyname($stmt, ":IN_SERIAL_NUMBER", &$serial_number, -1);
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
		<br />
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">M-PESA Statements - Deactivate Subscription</p>
        <br />
			<!--<table class="tablebody border" width="100%">
					<tr>
							<th colspan="2" class="tableheader"><b>USER MANAGEMENT</b></th>
					</tr>
					<tr>                                <td><a href="user_registration.php">Add Record</a></td><td><a href="#" onclick="javascript: confirm_deletion('user_list');">Delete Record(s)</a></td>
					</tr>
			</table>-->
		<form name='acc_history_list' action='mpesa_statements_deactivate_sub.php' method='post'>
		<center>
		<table class="tablebody border" width="80%">
			<tr>
				<th colspan="2" class="tableheader"><b>MSISDN & SR INFO</b></th>
			</tr>
			<tr>
				<td><br />MSISDN:</td><td><br /><input type='text' name='msisdn' /></td>
			</tr>                        
			<tr style='display:none;'>
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
				<!--<input type="hidden" name="msisdn" id="msisdn" value="<?php echo $_POST['msisdn']; ?>" />-->
				</form>
		<?php
			}
		?>
	</div>

<!-- insert the footer -->
<?php
	insert_footer2();
?>								
