<?php
    include "../util/functions.php";

    $error = "";
    $date = null;
    insert_header2();

    //initialise database object
    $BUNDLES_DB_TYPE = "oracle";
    $BUNDLES_DATABASEHOST = "10.25.200.103";
    $BUNDLES_DATABASEPORT = "1521";
    $BUNDLES_DATABASEUSER = "bundles_pcrf";
    $BUNDLES_DATABASEPASSWORD = "bundles_pcrf";
    $BUNDLES_DATABASENAME = "resdev";

    $db = new Database($BUNDLES_DB_TYPE, $BUNDLES_DATABASEHOST, $BUNDLES_DATABASEPORT, $BUNDLES_DATABASEUSER, $BUNDLES_DATABASEPASSWORD, $BUNDLES_DATABASENAME);

    $query = "SELECT bundle_id, description FROM bundles_info where description is not null and upper(description) not like '%UNLIMITED%' order by bundle_id";
    //create database object
    $db = new Database($BUNDLES_DB_TYPE, $BUNDLES_DATABASEHOST, $BUNDLES_DATABASEPORT, $BUNDLES_DATABASEUSER, $BUNDLES_DATABASEPASSWORD, $BUNDLES_DATABASENAME);
    $error = $db->open_connection();
    $array_load_levels = $db->list_records($query, false);
    $db->close_connection();

    $array_bundles = array();
    $temp = array();
    for($i=0; $i<count($array_load_levels); $i++)
    {
            $array_keys[$i] = $array_load_levels[$i][0];
            $array_values[$i] = $array_load_levels[$i][1];
    }

    if (is_array($array_load_levels))
            $array_bundles = array_combine($array_keys, $array_values);

    $array_bundle_details = array (
        'msisdn' => array("MSISDN", "", 1, 1),
        'amt' => array("AWARD", "", 1, 1),
        'ticket' => array("TICKET", "", 1, 1),
        'reason' => array("REASON", "", 1, 1)
    );

    if (isset($_POST['msisdn'])) {
        $array_bundle_details = array (
            'msisdn' => array("MSISDN", $_POST['msisdn'], 1, 11),
            'amt' => array("BUNDLE", $_POST['bundle'], 1, 1),
            'ticket' => array("TICKET", $_POST['ticket'], 1, 14),
            'trx_details' => array("ADDITIONAL INFO", $_POST['reason'], 1, 14)
        );

        //validate details
        $error .= validate($array_bundle_details);

        /*if ($_POST['amt'] > 150) {
            $error = "Compensation bundle cannot exceed 150 MBs!";
        }*/

        if ($error == "") {
            //to obtain the resultset of provisioning stored procedure
            //ADHOC_BUNDLES_REQUEST_CM(IN_MSISDN IN VARCHAR2, IN_MBS IN NUMBER, IN_USERNAME IN VARCHAR2, MESSAGE OUT VARCHAR2)
            $db->open_connection();
            $query = "BEGIN CM_ADHOC_BUNDLES_REQUEST_BILL(:IN_MSISDN, :IN_BUNDLE_ID, :IN_SR_NUMBER, :IN_SUB_TYPE, :IN_TRX_DETAIL); END;";
            $stmt = ociparse ($db->link, $query);

            //bind variables
            $msisdn = substr(trim($_POST['msisdn']), -9);
            $bundle_id = $_POST['bundle'];
            $sr_number = $_POST['ticket'];
            $reason = $_POST['reason'];
            $trx_detail = strtoupper($_SESSION['USERID']."; ".$reason);

            ocibindbyname($stmt, ":IN_MSISDN", &$msisdn, -1);
            ocibindbyname($stmt, ":IN_BUNDLE_ID", &$bundle_id, -1);
            ocibindbyname($stmt, ":IN_SR_NUMBER", &$sr_number, -1);
            ocibindbyname($stmt, ":IN_SUB_TYPE", &$sub_type, -1);
            ocibindbyname($stmt, ":IN_TRX_DETAIL", &$trx_detail, -1);

            //execute our stored procedure
            if (ociexecute($stmt)) {
                logmessage("INFO", "AWARD AD HOC DATA - USER: ".$_SESSION["USERID"].": Successfully executed adhoc_bundles_request($msisdn, $array_bundles[$bundle_id][1]]");
                $error = "Request Successfully Submitted";
            }
            else {
                $err = oci_error($db->link);
                logmessage("ERROR", "AWARD AD HOC DATA - USER: ".$_SESSION["USERID"].": Error executing adhoc_bundles_request($msisdn, $array_bundles[$bundle_id][1])");
                $error = "Error executing request! Please contact system administrator!; ".$err['code'].": ".$err['message'];
            }

            $db->close_connection();
        }
    }
?>

<div class="cspacer">
	<center>
	<?php
		if ($error !=  "") {
			echo "<div style='text-align: left; width: 80%'>\r\n";
			echo "<table class='tablebody border' width='100%'>\r\n";
			echo "<th class='tableheader'>MESSAGE</th>\r\n";
			echo "<tr><td>\r\n";
			echo "<p class='error'>".$error."</p>";
			echo "</td></tr>\r\n";
			echo "</table>\r\n";
			echo "<br /><br />\r\n";
			echo "</div>\r\n";
		}
	?>

	<div align="center" style="text-align:left; width:80%">
	<form name="award_adhoc" action="award_pcrf_bundle_with_billing.php" method="post">
		<center>
		<table class='tablebody border' width='100%'>
			<tr>
				<th colspan="2" class="tableheader">SUBMIT AD HOC BUNDLE WITH BILLING REQUEST</th>
			</tr>
			<tr>
				<td colspan="2" style="padding:5px 10px 5px 0px; color:#CC3300; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold;">NB: ** Request will be Billed on CBS</td>
			</tr>
			<tr>
				<td><br />MSISDN: </td>
                <td><br /><input type='text' name='msisdn' id='msisdn' /></td>
            </tr>
            <tr>
                <td><br />TICKET NO: </td>
                <td><br /><input type='text' name='ticket' id='ticket' /></td>
            </tr>
            <tr>
                    <td><br />CLASS OF SERVICE</td><td><br />
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
                <td><br />BUNDLE: </td>
                <td><br />
                <?php                    
                    if (isset($array_user_details['bundle'][1]) && $array_user_details['bundle'][1] != "none")
                            $bundle_id = $array_bundles['bundle'][1];
                    else
                            $bundle_id = -1;

                    echo build_combo("bundle", $array_bundles, "combobox", null, $bundle_id);
                ?>
                </td>
            </tr>
            <tr>
                    <td><br />ADDITIONAL INFO:</td><td><br /><textarea name="reason"><?php echo $array_bundle_details['reason'][1]; ?></textarea></td>
            </tr>
            <tr>
                <td colspan='2'><br /><input type='button' value='Submit' name='submit_form' onClick="javascript: confirm_pcrf_bundle();" /></td>
            </tr>
        </table>
        </center>
    </form>
    </div>

<!-- insert the footer -->
<?php
	insert_footer2();
?>
