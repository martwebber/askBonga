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
    $BUNDLES_DATABASEHOST = "10.25.200.103";
    $BUNDLES_DATABASEPORT = "1521";
    $BUNDLES_DATABASEUSER = "bundles";
    $BUNDLES_DATABASEPASSWORD = "bundles123";
    $BUNDLES_DATABASENAME = "resdev";

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
        <form name='acc_history_list' action='check_bundles_history.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
			<tr>
				<th colspan="2" class="tableheader"><b>MSISDN SEARCH</b></th>
			</tr>
			<tr>
				<td><br />MSISDN:</td><td><br /><input type='text' name='msisdn' /></td>
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
			<input type="hidden" name="msisdn" id="msisdn" value="<?php echo $_POST['msisdn']; ?>" />
			<?php
				$tables = array("account_history");
				$columns = array("msisdn", "to_char(request_date, 'dd-MON-yyyy hh24:mi')", "to_char(processed_date, 'dd-MON-yyyy hh24:mi')", "decode(action, '18','300MB','19','700MB','21','1GB','25','40MB','26','100MB','20','2GB','22','3GB','23','5GB','24','8GB','27','15GB','28','20GB','29','30GB',substr(action, 4)||'MB') bundle",
"provisioning_comments", "charging_comments");
                $titles = array("msisdn", "request_date", "processed_date", "bundle", "status", "submitted_by");
                $advanced = "where msisdn = ".substr($_POST['msisdn'],1)." order by request_date desc";
				$display_str = $db->display_records($tables, $columns, $titles, "acc_history_list", $advanced, null, null);
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
