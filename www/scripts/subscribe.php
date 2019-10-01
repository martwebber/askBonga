<?php
	include "../util/functions.php";

	$error = "";
	$date = null;
	insert_header2();

	//initialise data array
	if (!isset($_POST['msisdn'])) {
		$array_user_details = array (
			'msisdn' => array("MSISDN", "", 1, 15),
			'cos' => array("SUBSCRIBER_TYPE", "", 1, 1),
			'bundle' => array("BUNDLE", "", 1, 1)
		);
	}
	else {
		$array_user_details = array (
			'msisdn' => array("MSISDN", $_POST['msisdn'], 1, 15),
			'cos' => array("SUBSCRIBER_TYPE", $_POST['sub_type'], 1, 1),
			'sr_number' => array("SR NUMBER", $_POST['sr_number'], 1, 14)
		);	
	}

	$S_DATABASEHOST = "10.25.200.103";
	$S_DATABASEPORT = "1521";
	$S_DATABASEUSER = "bundles_pcrf";
	$S_DATABASEPASSWORD = "bundles_pcrf";
	$S_DATABASENAME = "RESDEV";
	
	//initialise database object
	$db = new Database($DB_TYPE, $S_DATABASEHOST, $S_DATABASEPORT, $S_DATABASEUSER, $S_DATABASEPASSWORD, $S_DATABASENAME);

	//check the javascript to automatically fill the number of winners textbox
	if (isset($_POST['submit_request']) )
	{
            //validate the MSISDN
            $validator =  new Validate();
            if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
                $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
            }

            if ($error == "") {
                //to obtain the resultset of winners run stored procedure
                $db->open_connection();
                //BUNDLESPKG.SUBSCRIPTION_BUNDLES_REQUEST(IN_MSISDN IN NUMBER, IN_SHORT_CODE IN NUMBER, IN_MESSAGE IN VARCHAR2, IN_CHANNEL IN VARCHAR2, IN_SUB_TYPE IN VARCHAR2)
                $query = "BEGIN SUBSCRIPTION_BUNDLES_REQUEST(:IN_MSISDN, :IN_SHORT_CODE, :IN_MESSAGE, :IN_CHANNEL, :IN_SUB_TYPE); END;";
                $cursor = ocinewcursor($db->link);
                $stmt = ociparse ($db->link, $query);

                //bind variables
                $in_msisdn = substr($_POST['msisdn'], -9);
		$in_sr_number = $_POST['sr_number'];
                $in_channel = "CM: ".$in_sr_number;
		//$in_message = "10MB";
		$in_message = $_POST['bundle'];
                $in_short_code = "";
		$in_sub_type = $_POST['sub_type'];


		logmessage("DEBUG", $in_channel."; ".$in_msisdn."; ".$_POST['sr_number']."; ".$in_message);
                ocibindbyname($stmt, ":IN_MSISDN", &$in_msisdn, -1);
		ocibindbyname($stmt, ":IN_SHORT_CODE", &$in_short_code, -1);
		ocibindbyname($stmt, ":IN_MESSAGE", &$in_message, -1);
                ocibindbyname($stmt, ":IN_CHANNEL", &$in_channel, -1);
                ocibindbyname($stmt, ":IN_SUB_TYPE", &$in_sub_type, -1);

                //echo $quantity."<br />";
                //execute our stored procedure
                $result = ociexecute($stmt);

                if ($result) {
                    $error = "Request Successfully Submitted. Please check the 'Query Subscription' link in a few minutes to verify the subscription.";
                }
                else {
                    $error = "Error submitting request. <br />Please contact system administrator";
                }

                $stmt_result = oci_free_statement($stmt);
                logmessage("DEBUG", "OCI_FREE_RESULT: STMT - $stmt_result");
                $db->close_connection();
            }
	}

?>

<div class="cspacer">
	<?php
		if ($error !=  "") {
			echo "<center>\r\n";
			echo "<div align='center' style='text-align: left; width: 80%'>\r\n";
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
			<tr>
				<td><a href="user_registration.php">Add Record</a></td><td><a href="#" onclick="javascript: confirm_deletion('user_list');">Delete Record(s)</a></td>
			</tr>
		</table>-->
        <form name='form_subscribe' action='subscribe.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
			<tr>
				<th colspan="2" class="tableheader"><b>DAILY MOBILE DATA: MSISDN ENTRY</b></th>
			</tr>
			<tr>
				<td><br />MSISDN:</td><td><br /><input type='text' name='msisdn' /></td>
			</tr>
			<tr>
				<td><br /><br />CLASS OF SERVICE</td><td>
				<?php					
					$array_sub_type_details = array ('prepaid|SMSC' => 'prepaid', 'postpaid|SMSC' => 'postpaid');
					
					if (isset($array_user_details['sub_type'][1]) && $array_user_details['sub_type'][1] != "none")
						$sub_type = $array_user_details['sub_type'][1];
					else
						$sub_type = -1;

					echo build_combo("sub_type", $array_sub_type_details, "combobox", null, $sub_type); 
				?>
				</td>
			</tr>			
			<tr>
				<td><br /><br />DAILY BUNDLE</td><td>
				<?php
					$array_bundle_details = array ('DAILY5MB' => 'DAILY5MB', 'DAILY10MB' => 'DAILY10MB', 'DAILY25MB' => 'DAILY25MB');

					if (isset($array_user_details['bundle'][1]) && $array_user_details['bundle'][1] != "none")
						$bundle = $array_user_details['bundle'][1];
					else
						$bundle = -1;

					echo build_combo("bundle", $array_bundle_details, "combobox", null, $bundle);
				?>
				</td>
			</tr>
			<tr>
				<td><br />INTERACTION ID / SR NO:</td><td><br /><input type='text' name='sr_number' /></td>
			</tr>			
            <tr>
                <td colspan="2"><br /><input type='submit' name='submit_request' value ='Submit Request' /></td>
            </tr>
		</table>
        </center>
        </form>
		<br /><br />

</div>

<!-- insert the footer -->
<?php
	insert_footer2();
?>

	
