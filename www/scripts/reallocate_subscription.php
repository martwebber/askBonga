<?php
	include "../util/functions.php";

	$error = "";
	$date = null;
	insert_header2();

	//initialise database object
	$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);

	$array_bundle_details = array (
		'msisdn' => array("MSISDN", "", 1, 1),
        'ticket' => array("TICKET", "", 1, 1)
	);

    if (isset($_POST['msisdn'])) {
        $array_bundle_details = array (
            'msisdn' => array("MSISDN", $_POST['msisdn'], 1, 11),
            'ticket' => array("TICKET", $_POST['ticket'], 1, 1)
        );

        //validate details
        $error .= validate($array_bundle_details);

        if ($error == "") {
            //to obtain the resultset of provisioning stored procedure
            //PREPAIDBUNDLESPKG.BUNDLES_REQUEST_CM(IN_MSISDN IN VARCHAR2, IN_CODE IN NUMBER, USERNAME IN VARCHAR2, MESSAGE OUT VARCHAR2)
            $db->open_connection();
            $query = "BEGIN reallocate_subscription(:IN_MSISDN, :IN_USERNAME); END;";
            $stmt = ociparse ($db->link, $query);

            //bind variables
            $msisdn = substr($_POST['msisdn'], 1);
            $ticket = $_POST['ticket'];
            $username = strtoupper($_SESSION['USERID']."; TICKET: ".$ticket);

            ocibindbyname($stmt, ":IN_MSISDN", &$msisdn, -1);
            ocibindbyname($stmt, ":IN_USERNAME", &$username, -1);

            //execute our stored procedure
            if (ociexecute($stmt)) {
                $message = "Request successfully submitted";
                logmessage("INFO", "AWARD SUBSCRIPTION - USER: ".$_SESSION["USERID"].": Successfully executed reallocate_subscription($msisdn)");
                $error = $message;
            }
            else {
                logmessage("ERROR", "AWARD SUBSCRIPTION - USER: ".$_SESSION["USERID"].": Error executing reallocate_subscription($msisdn)");
                $error = "Error executing request! Please contact system administrator!";
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
	<form name="award_subscription" action="reallocate_subscription.php" method="post">
		<center>
		<table class='tablebody border' width='100%'>
			<tr>
				<th colspan="2" class="tableheader">SUBMIT SUBSCRIPTION REQUEST</th>
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
                <td colspan='2'><br /><input type='button' value='Submit' name='submit_form' onclick="javascript: confirm_subscription();" /></td>
            </tr>
        </table>
        </center>
    </form>
    </div>

<!-- insert the footer -->
<?php
	insert_footer2();
?>