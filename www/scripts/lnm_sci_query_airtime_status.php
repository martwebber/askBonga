<?php
	require_once "../util/functions.php";

	$error = "";

	insert_header2();

    if (isset($_POST['msisdn'])) {
        $validator =  new Validate();
        /*if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
            $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
        }*/
    }

	//initialise database object

   
    /*$BONGA_DB_TYPE = "oracle";
    $BONGA_DATABASEHOST = "172.29.225.1";
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "sms_bundles";
    $BONGA_DATABASEPASSWORD = "Ang0la$123";
    $BONGA_DATABASENAME = "EIRDB";*/
	
	
	/*$BONGA_DB_TYPE = "oracle";
    $BONGA_DATABASEHOST = "172.28.226.33";
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "sms_bundles";
    $BONGA_DATABASEPASSWORD = "Ang0la$123";
    $BONGA_DATABASENAME = "heko";*/

	$BONGA_DB_TYPE = "oracle";
     //$BONGA_DATABASEHOST = "172.28.226.33";
	//$BONGA_DATABASEHOST = "172.29.226.12";
	//$BONGA_DATABASEHOST = "172.29.226.18";
	$BONGA_DATABASEHOST ="svjcc1-scan";
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "mauzo";
    $BONGA_DATABASEPASSWORD = "mauzo_12##";
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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">Supermarket and other BuyGoods Lipa Na M-PESA Airtime Awards</p>
        <br />
        <form name='sci_winner' action='lnm_sci_query_airtime_status.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
			<tr>
				<th colspan="2" class="tableheader"><b>MSISDN SEARCH</b></th>
			</tr>
			<tr>
				<td>
				<br />MSISDN: </td>
						<td>
							<br /><input type='text' name='msisdn' />&nbsp;&nbsp; (Format: 07XXYYYZZZ) 
				</td>
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
//$tables = array("TBL_SFCR_AWARDS");
				//$columns = array("MSISDN","TRX_DATE","mpesa_trx_id","AIRTIME_AWARD_DETAILS","AIRTIME_AWARD_DATE","FLOOR(TALK_TIME/60)"); 
                //$titles = array("msisdn", "TRX Date","MPESA TRX","Airtime Status","Airtime Award Date","Talk Time minutes");
                //$advanced = "where PROMO_ID=2 AND msisdn = '".'254'.substr($_POST['msisdn'],-9)."' order by trx_date desc";
				 //$advanced = "where PROMO_ID=2 AND msisdn = '".'254'.substr($_POST['msisdn'],-9)."' order by trx_date desc";
				 
				$tables = array("vw_sfcr_sci_awards");
				$columns = array("MSISDN","TRX_DATE","MPESA_TRX_ID","AIRTIME_AWARD_DETAILS","AIRTIME_AWARD_DATE","TALK_TIME","TRANSACTION_AMOUNT"); 
                $titles = array("msisdn", "TRX Date","MPESA TRX","Airtime Status","Airtime Award Date","Talk Time minutes","TRANSACTION_AMOUNT");
                $advanced = "where msisdn = '".'254'.substr($_POST['msisdn'],-9)."' order by trx_date desc";
				 //$advanced = "where PROMO_ID=2 AND msisdn = '".'254'.substr($_POST['msisdn'],-9)."' order by trx_date desc";
				 
				 
				 
				 
				 
				$display_str = $db->display_records($tables, $columns, $titles, "kcb_winner", $advanced, null, null, "");
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
