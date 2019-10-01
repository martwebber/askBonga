<?php
	require_once "../util/functions.php";

	$error = "";

	insert_header2();

    if (isset($_POST['msisdn'])) {
        $validator =  new Validate();
        if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
            $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
        }
    }

	//initialise database object
    $BUNDLES_DB_TYPE = "oracle";
    $BUNDLES_DATABASEHOST = "172.31.100.122";
    $BUNDLES_DATABASEPORT = "1529";
    $BUNDLES_DATABASEUSER = "mauzo_201109";
    $BUNDLES_DATABASEPASSWORD = "mauzo#20110912";
    $BUNDLES_DATABASENAME = "promo";

	$db = new Database($BUNDLES_DB_TYPE, $BUNDLES_DATABASEHOST, $BUNDLES_DATABASEPORT, $BUNDLES_DATABASEUSER, $BUNDLES_DATABASEPASSWORD, $BUNDLES_DATABASENAME);

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
        <form name='acc_history_list' action='kabambe_query.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
			<tr>
				<th colspan="2" class="tableheader"><b>MSISDN SEARCH</b></th>
			</tr>
			<tr>
				<td><br />MSISDN:</td><td><br /><input type='text' name='msisdn' value='<?php if (isset($_POST['msisdn'])) {echo $_POST['msisdn'];} ?>' /></td>
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
			<?php
				//$tables = array("adhoc_account_history_t a, bundles_info b, pcrf_status_codes p");
				$tables = array("registrations r, entry_points e", "cluster_codes c");
				//$columns = array("msisdn", "to_char(request_date, 'dd-MON-yyyy hh24:mi')", "to_char(provisioning_date, 'dd-MON-yyyy hh24:mi')", "description", "result_message");
				$columns = array("r.msisdn", "to_char(r.registration_date, 'dd-Mon-yyyy hh24:mi')", " r.retailer_name", "e.points", "e.date_updated", "c.cluster_code", "c.area_name");
                $titles = array("msisdn", "registration_date", "retailer_name", "points", "date_updated", "cluster_code", "area_name");
                //$advanced = "where a.bundle_id = b.bundle_id and a.provisioning_status = p.result_code and msisdn = ".$_POST['msisdn']." order by request_date desc";
                $advanced = "where r.msisdn = e.msisdn and r.cluster_code = c.cluster_code and r.msisdn = ".substr($_POST['msisdn'], -9);
				$display_str = $db->display_records($tables, $columns, $titles, "acc_history_list", $advanced, null, null, "r.id");
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
