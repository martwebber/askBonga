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
	$BONGA_DB_TYPE = "oracle";
    $BONGA_DATABASEHOST = 'svthk1-scan';
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "ftth_eir";
    $BONGA_DATABASEPASSWORD = "Policy#123";
    $BONGA_DATABASENAME = "EIRSB";
	
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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">Query Details</p>
        <br />
        <form name='query_iflix_info' action='iflix.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
			<tr>
				<th colspan="2" class="tableheader"><b>MSISDN SEARCH</b></th>
			</tr>
			<tr>
				
				<td><br />MSISDN: </td><td><br /><input type='text' name='msisdn' value='<?php  if (isset($_POST['msisdn'])) { echo $_POST['msisdn']; }?>' />&nbsp;&nbsp; (Format: 07XXYYYZZZ)
			</tr>
            <tr>
                <td colspan="2"><br /><input type='submit' name='sub_msisdn' /></td>
            </tr>
		</table> 
        </center>
		
	 
        <?php
            if (isset($_POST['msisdn']) && $error == "") {
        ?>
			<input type="hidden" name="confirm_deletion" id="confirm_deletion" />
			<!--<input type="hidden" name="msisdn" id="msisdn" value="<?php echo $_POST['msisdn']; ?>" />-->
			<?php
				$tables = array("VW_FTTH_IFLIX_BONGALINK");
				$columns = array("VOUCHER_DAYS","SPONSOR_MSISDN", "BENEFICIARY_MSISDN", "AMOUNT", "MPESA_TRXN", "RESPONSE_DESC",  "VOUCHER_GIVEN","TRX_DATE");
				$titles = array("VOUCHER DAYS","SPONSOR MSISDN","BENEFICIARY MSISDN","AMOUNT","MPESA TRANSACTION","RESPONSE DESCRIPTION","VOUCHER AWARDED/EXTENDED","TRANSACTION DATE");
				$advanced = " where SPONSOR_MSISDN = ".substr($_POST['msisdn'],-9)."";
				$display_str = $db->display_records($tables, $columns, $titles, "query_iflix_info", $advanced, null, null, "9");
				echo "</br></br>";
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
