<?php
	require_once "../util/functions.php";

	$error = "";

	insert_header2();

    if (isset($_POST['imsi'])) {
	$imsi = trim($_POST['imsi']);
        if (!ctype_digit($imsi) || strlen($imsi) != 15) {
            $error = "<br />Please ensure 'IMSI' is valid number, e.g. 639027850197990";
        }
    }

	//initialise database object
    $PBB_DB_TYPE = "oracle";
    $PBB_DATABASEHOST = "10.65.12.12";
    $PBB_DATABASEPORT = "1521";
    $PBB_DATABASEUSER = "phone_backup";
    $PBB_DATABASEPASSWORD = "phone_backup123";
    $PBB_DATABASENAME = "promodb";

	$db = new Database($PBB_DB_TYPE, $PBB_DATABASEHOST, $PBB_DATABASEPORT, $PBB_DATABASEUSER, $PBB_DATABASEPASSWORD, $PBB_DATABASENAME);

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
        <form name='sub_history_list' action='check_imsi.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
			<tr>
				<th colspan="2" class="tableheader"><b>IMSI SEARCH</b></th>
			</tr>
			<tr>
				<td><br />IMSI:</td><td><br /><input type='text' name='imsi' /></td>
			</tr>
            <tr>
                <td colspan="2"><br /><input type='submit' name='sub_msisdn' /></td>
            </tr>
		</table>
        </center>
		<br /><br />
        <?php
            if (isset($_POST['imsi']) && $error == "") {
        ?>
			<input type="hidden" name="confirm_deletion" id="confirm_deletion" />
			<?php
				$tables = array("simcard_v3_data s");
				$columns = array("imsi", "msisdn", "simcard_version", "decode(to_number(simcard_version), '2.6', 'Not compatible', 'Compatible')");
                $titles = array("imsi", "msisdn", "simcard_version", "compatibility");
                $advanced = "where s.imsi = '".$imsi."'";
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