<?php
	require_once "../util/functions.php";

	$error = "";

	insert_header2();

    if (isset($_POST['msisdn'])) {
        $validator =  new Validate();
	$msisdn = substr(trim($_POST['msisdn']),-9);
        if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
            $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
        }
    }

	//initialise database object
    $IEBC_DB_TYPE = "oracle";
    $IEBC_DATABASEHOST = "10.65.12.245";
    $IEBC_DATABASEPORT = "1521";
    $IEBC_DATABASEUSER = "iebc";
    $IEBC_DATABASEPASSWORD = "Iebc#1234";
    $IEBC_DATABASENAME = "iebc";

	$db = new Database($IEBC_DB_TYPE, $IEBC_DATABASEHOST, $IEBC_DATABASEPORT, $IEBC_DATABASEUSER, $IEBC_DATABASEPASSWORD, $IEBC_DATABASENAME);

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
        <form name='sub_history_list' action='check_iebc_status.php' method='post'>
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
			<?php
				$tables = array("registrations");
				$columns = array("msisdn", "case when registration_status < 0 then 'Unsubscribed' else 'Subscribed' end");
                $titles = array("msisdn", "registration_status");
                $advanced = "where msisdn = ".$msisdn."";
				$display_str = $db->display_records($tables, $columns, $titles, "acc_history_list", $advanced, null, null, null);
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
