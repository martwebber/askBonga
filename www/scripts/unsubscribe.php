<?php
	include "../util/functions.php";

	$error = "";
	$date = null;
	insert_header2();

	//initialise data array
	$array_draw_details = array (
		'msisdn' => array("MSISDN", "", 1, 15)
	);


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
                //BUNDLESPKG.UNSUBSCRIBE(IN_MSISDN IN NUMBER, IN_CHANNEL IN VARCHAR2, OUT_MESSAGE OUT NUMBER)
                $query = "BEGIN unsubscribe(:IN_MSISDN, :IN_CHANNEL, :OUT_MESSAGE); END;";
                $cursor = ocinewcursor($db->link);
                $stmt = ociparse ($db->link, $query);

                //bind variables
                $in_msisdn = substr($_POST['msisdn'], -9);
                $in_channel = "CM";
                $out_message = "";

                ocibindbyname($stmt, ":IN_MSISDN", &$in_msisdn, -1);
                ocibindbyname($stmt, ":IN_CHANNEL", &$in_channel, -1);
                ocibindbyname($stmt, ":OUT_MESSAGE", &$out_message, -1);

                //echo $quantity."<br />";
                //execute our stored procedure
                $result = ociexecute($stmt);

                if ($result) {
                    $error = "Request Successfully Submitted";
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
			echo "<div style='text-align: left; width: 80%'>\r\n";
			echo "<center>";
			echo "<table class='tablebody border' width='100%'>\r\n";
			echo "<th class='tableheader'>MESSAGE</th>\r\n";
			echo "<tr><td>\r\n";
			echo "<p class='error'>".$error."</p>";
			echo "</td></tr>\r\n";
			echo "</table>\r\n";
			echo "<br /><br />\r\n";
			echo "</center></div>\r\n";
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
        <form name='form_unsubscribe' action='unsubscribe.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
			<tr>
				<th colspan="2" class="tableheader"><b>DAILY MOBILE DATA: MSISDN SEARCH</b></th>
			</tr>
			<tr>
				<td><br />MSISDN:</td><td><br /><input type='text' name='msisdn' /></td>
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

	
