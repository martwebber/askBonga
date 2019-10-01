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
    //$BONGA_DATABASEHOST = "svjcc1-scan";
	$BONGA_DATABASEHOST = "svdt1-scan";
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "BI_CVM";
    $BONGA_DATABASEPASSWORD = "c0mp0ign12";
    $BONGA_DATABASENAME = "flexsb";
	
		//initialise database object
	//$BONGA_DB_TYPE = "oracle";
    //$BONGA_DATABASEHOST = "svjcc1-scan";
	/*$BONGA_DATABASEHOST = "10.184.7.39";
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "BI_CVM";
    $BONGA_DATABASEPASSWORD = "c0mp0ign12";
    $BONGA_DATABASENAME = "flexdb";*/
	
	
	//initialise database object   
    /*$BONGA_DB_TYPE = "oracle";
    $BONGA_DATABASEHOST = "172.29.225.1";
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "bonyeza_chapa";
    $BONGA_DATABASEPASSWORD = "b0ny3zachapa";
    $BONGA_DATABASENAME = "EIRDB";*/

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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">View Purchase History</p>
        <br />
        <form name='query_purchase_history' action='eol_cust_offer_purchase_history_v2.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
			<tr>
				<th colspan="2" class="tableheader"><b>MSISDN SEARCH</b></th>
			</tr>
			<tr>
				
				<td><br />MSISDN: </td><td><br /><input type='text' name='msisdn' value='<?php echo $_POST['msisdn']?>' />&nbsp;&nbsp; (Format: 7XXYYYZZZ) 
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
$tables = array("cvm_campaign.vw_eo_offer_request_history");
				$columns = array("msisdn","to_char(trx_date,'DD-MON-YYYY HH24:MI:SS')","offer_name","purchase_type","request_status","to_char(cbs_trx_date,'DD-MON-YYYY HH24:MI:SS')","purchase_details","award_details","sms_sent");
                
				$titles = array("msisdn","date","offer","purchase type","status","Date Processed","purchase_details","award_details","sms_sent");
                //$advanced = "where substr(msisdn,-9) = ".substr($_POST['msisdn'],-9)."";
				$advanced = "where msisdn = ".$_POST['msisdn']."";
				$display_str = $db->display_records($tables, $columns, $titles, "query_purchase_history", $advanced, null, null, "");
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
