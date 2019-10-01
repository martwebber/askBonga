<?php
	require_once "../util/functions.php";

	$error = "";

	insert_header2();

    if (isset($_POST['serial_pin_'])) {
        /*$validator =  new Validate();
        if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
            $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
        }*/
    }

	//initialise database object

   
    $BONGA_DB_TYPE = "oracle";
    $BONGA_DATABASEHOST = "172.31.100.122";
    $BONGA_DATABASEPORT = "1529";
    $BONGA_DATABASEUSER = "mauzo2013";
    $BONGA_DATABASEPASSWORD = "Skippers_1QAZ2WSX";
    $BONGA_DATABASENAME = "promo";

	$db = new Database($BONGA_DB_TYPE, $BONGA_DATABASEHOST, $BONGA_DATABASEPORT, $BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASENAME);

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
	<div align="center" style="text-align:left; width:100%">
		<!--<table class="tablebody border" width="100%">
			<tr>
				<th colspan="2" class="tableheader"><b>USER MANAGEMENT</b></th>
			</tr>
			<tr>
				<td><a href="user_registration.php">Add Record</a></td><td><a href="#" onclick="javascript: confirm_deletion('user_list');">Delete Record(s)</a></td>
			</tr>
		</table>-->
<br />
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">Airtime Serial Details</p>
        <br />
        <form name='airtime_serial' action='query_airtime_serial.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
			<tr>
				<th colspan="2" class="tableheader"><b>Airtime Serial Search</b></th>
			</tr>
			<tr>
				<td><br />Msisdn: </td><td><br /><input type='text' name='airtime_serial_msisdn' />&nbsp;&nbsp; (Format : 07XXYYYZZZ) </td>
			</tr>
            <tr>
                <td colspan="2"><br /><input type='submit' name='airtime_serial_submit' /></td>
            </tr>
		</table>
        </center>
	 
        <?php
            if (isset($_POST['airtime_serial_submit']) && $error == "") {
        ?>
			<input type="hidden" name="confirm_deletion" id="confirm_deletion" />
			<!--<input type="hidden" name="msisdn" id="msisdn" value="<?php echo $_POST['serial_pin_']; ?>" />-->
			<?php
$tables = array("airtime_pins");
//serial_number,msisdn,decode(status,0,'Not Redeemed',1,'Redeemed','Unknown') status,denomination
				$columns = array("serial_number","decode(status,0,'Not Redeemed',1,'Redeemed','Unknown')","msisdn","denomination","decode(to_char(date_sent,'DD-MON-YYYY'),'28-OCT-2013','Draw 1','11-NOV-2013','Draw 2','25-NOV-2013','Draw 3','09-DEC-2013','Draw 4','23-DEC-2013','Draw 5','Unknown')");
                $titles = array("Airtime Serial", "STATUS","MSISDN", "REDEEM DATE","Draw");
                $advanced = "where substr(msisdn,4,9) = '".substr($_POST['airtime_serial_msisdn'],-9)."' order by 5 asc";
				$display_str = $db->display_records($tables, $columns, $titles, "airtime_serial", $advanced, null, null, "");
				//$db->edit_displayed_records($tables, $columns);
				echo $display_str;
			?>
		</form>
        <?php
            }
        ?>
</div>

<!-- insert the footer -->
<?php
	insert_footer2();
?>
