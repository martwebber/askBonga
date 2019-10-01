<?php
	require_once "../util/functions.php";

	$error = "";

	insert_header2();

    if (isset($_POST['icode'])) {
        $validator =  new Validate();
        if ((strlen($_POST['icode']) < 9) && (strlen($_POST['icode']) > 11)) {
            $error = "<br />Please ensure 'iCode' is 9 digit in length";
        }
    }

	//initialise database object

   
    /*$BONGA_DB_TYPE = "oracle";
    $BONGA_DATABASEHOST = "172.28.225.99";
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "cokepromo";
    $BONGA_DATABASEPASSWORD = "C0k#pr0mo";
    $BONGA_DATABASENAME = "EIRSB";*/
	
	$BONGA_DB_TYPE = "oracle";
     //$BONGA_DATABASEHOST = "172.28.226.33";
	$BONGA_DATABASEHOST = "172.29.226.12";
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "samsung_promo";
    $BONGA_DATABASEPASSWORD = "samsun8_pt0m0#";
    $BONGA_DATABASENAME = "heko";

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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">Coke Promo query iCode</p>
        <br />
        <form name='coke_query_icode' action='coke_query_icode.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
			<tr>
				<th colspan="2" class="tableheader"><b>iCode SEARCH</b></th>
			</tr>
			<tr>
				<td><br />iCode: </td><td><br /><input type='text' name='icode' />&nbsp;&nbsp; (8 Digit Code) </td>
			</tr>
            <tr>
                <td colspan="2"><br /><input type='submit' name='sub_msisdn' /></td>
            </tr>
		</table>
        </center>
	 
        <?php
            if (isset($_POST['icode']) && $error == "") {
        ?>
			<input type="hidden" name="confirm_deletion" id="confirm_deletion" />
			<!--<input type="hidden" name="msisdn" id="msisdn" value="<?php echo $_POST['icode']; ?>" />-->
			<?php
$tables = array("vw_icodes");
				$columns = array("msisdn","icode","redemption_status","redemption_date");
                $titles = array("msisdn", "iCode","Status","Redemption Date");
                $advanced = "where icode = upper(DBMS_OBFUSCATION_TOOLKIT.md5 (input => UTL_RAW.cast_to_raw(upper('".$_POST['icode']."'))))";
				$display_str = $db->display_records($tables, $columns, $titles, "coke_query_icode", $advanced, null, null, "");
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
