<?php
	include "../util/functions.php";

	$error = "";
	$date = null;
	insert_header2();

	//initialise database object
    $BUNDLES_DB_TYPE = "oracle";
    $BUNDLES_DATABASEHOST = "10.25.200.103";
    $BUNDLES_DATABASEPORT = "1521";
    $BUNDLES_DATABASEUSER = "bundles";
    $BUNDLES_DATABASEPASSWORD = "bundles123";
    $BUNDLES_DATABASENAME = "resdev";

	$db = new Database($BUNDLES_DB_TYPE, $BUNDLES_DATABASEHOST, $BUNDLES_DATABASEPORT, $BUNDLES_DATABASEUSER, $BUNDLES_DATABASEPASSWORD, $BUNDLES_DATABASENAME);

	$array_bundle_details = array (
		'msisdn' => array("MSISDN", "", 1, 1),
        'amt' => array("AWARD", "", 1, 1),
        'ticket' => array("TICKET", "", 1, 14)
	);

    if (isset($_POST['msisdn'])) {
        $array_bundle_details = array (
            'msisdn' => array("MSISDN", $_POST['msisdn'], 1, 11),
            'amt' => array("BUNDLE", $_POST['bundle'], 1, 1),
            'ticket' => array("TICKET", $_POST['ticket'], 1, 14)
        );

        //validate details
        $error .= validate($array_bundle_details);

        if ($_POST['amt'] > 150) {
            $error = "Compensation bundle cannot exceed 150 MBs!";
        }

        if ($error == "") {
            //to obtain the resultset of provisioning stored procedure
            //ADHOC_BUNDLES_REQUEST_CM(IN_MSISDN IN VARCHAR2, IN_MBS IN NUMBER, IN_USERNAME IN VARCHAR2, MESSAGE OUT VARCHAR2)
            $db->open_connection();
            $query = "BEGIN ADHOC_BUNDLES_REQUEST_CM(:IN_MSISDN, :IN_MBS, :IN_USERNAME, :MESSAGE); END;";
            $stmt = ociparse ($db->link, $query);

            //bind variables
            $msisdn = substr($_POST['msisdn'], 1);
            $mbs = $_POST['bundle'];
            $ticket = $_POST['ticket'];
            $username = strtoupper($_SESSION['USERID']."; TICKET: ".$ticket);

            ocibindbyname($stmt, ":IN_MSISDN", $msisdn, -1);
            ocibindbyname($stmt, ":IN_MBS", &$mbs, -1);
            ocibindbyname($stmt, ":IN_USERNAME", &$username, -1);
            ocibindbyname($stmt, ":MESSAGE", &$message, 100);

            //execute our stored procedure
            if (ociexecute($stmt)) {
                logmessage("INFO", "AWARD AD HOC DATA - USER: ".$_SESSION["USERID"].": Successfully executed adhoc_bundles_request($msisdn, $mbs)");
                $error = $message;
            }
            else {
                $err = oci_error($db->link);
                logmessage("ERROR", "AWARD AD HOC DATA - USER: ".$_SESSION["USERID"].": Error executing adhoc_bundles_request($msisdn, $mbs)");
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
	<form name="award_adhoc" action="award_adhoc_bundle.php" method="post">
		<center>
		<table class='tablebody border' width='100%'>
			<tr>
				<th colspan="2" class="tableheader">SUBMIT AD HOC BUNDLE REQUEST</th>
			</tr>
			<tr>
				<td><br />MSISDN: </td>
                <td><br /><input type='text' name='msisdn' id='msisdn' /></td>
            </tr>
			<tr>
				<td><br />BUNDLE (MBS): </td>
                <td><br /><input type='text' name='bundle' id='bundle' /></td>
            </tr>
			<tr>
				<td><br />TICKET NO: </td>
                <td><br /><input type='text' name='ticket' id='ticket' /></td>
            </tr>
            <tr>
                <td colspan='2'><br /><input type='button' value='Submit' name='submit_form' onclick="javascript: confirm_award();" /></td>
            </tr>
        </table>
        </center>
    </form>
    </div>

<!-- insert the footer -->
<?php
	insert_footer2();
?>
