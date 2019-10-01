<?php
require_once "../util/functions.php";
$error = "";
insert_header2();

if (isset($_POST['msisdn'])) {
	$validator =  new Validate();
	if (!$validator->is_valid_msisdn(substr($_POST['msisdn'],-9))) {
		$error = "<br/>Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
	}
}

    // initialise database object - COKEPROMO
    $BONGA_DB_TYPE = "oracle";
    $BONGA_DATABASEHOST = 'svthk1-scan' ;
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "COKEPROMO";
    $BONGA_DATABASEPASSWORD = "C0k#pr0mo";
    $BONGA_DATABASENAME = "eirsb";

    // prod links
    // $BONGA_DB_TYPE = "oracle";
    // $BONGA_DATABASEHOST = "172.28.220.7"; // or use svthk1-scan
    // $BONGA_DATABASEPORT = "1521";
    // $BONGA_DATABASEUSER = "TIBCOEHF";
    // $BONGA_DATABASEPASSWORD = "TIBCOEHF";
    // $BONGA_DATABASENAME = "TIBCODB";
    
    $db = new Database($BONGA_DB_TYPE, $BONGA_DATABASEHOST, $BONGA_DATABASEPORT, $BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASENAME);
    // $db = oci_connect("akrs", 'akrs123', "10.184.0.101:1521/TWIGAUAT");   


	// initialise database object - TUNUS
    // prod links
	$TUNUS_DB_TYPE = "oracle";
    $TUNUS_DATABASEHOST = "svdt1-scan";
    $TUNUS_DATABASEPORT = "1521";
    $TUNUS_DATABASEUSER = "BI_CVM";
    $TUNUS_DATABASEPASSWORD = "c0mp0ign12";
    $TUNUS_DATABASENAME = "flexsb";
	
    $db_tun = new Database($TUNUS_DB_TYPE, $TUNUS_DATABASEHOST, $TUNUS_DATABASEPORT, $TUNUS_DATABASEUSER, $TUNUS_DATABASEPASSWORD, $TUNUS_DATABASENAME);
?>

<?php
	if (!isset($_POST['REPORT_TYPE'])){
		//$error = "<br/>System error. Please try again later.";
	}else{
		$report_type = $_POST['REPORT_TYPE'];  
	}
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
        <br />
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">BINGWA SOKONI</p>
        <br />
  
        <form name='recommendations' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method='post'>
        <center>
			<table class="tablebody border" width="80%">
				<tr><th colspan="2" class="tableheader"><b>PARTNER/CUSTOMER SEARCH</b></th></tr>
				<tr>
					<td><br/>PARTNER/CUSTOMER MSISDN:</td><td><br /><input type='text' name='msisdn' placeholder='722000000' required value='<?php echo $_POST['msisdn']; ?>'/></td>
				</tr>
				<tr>
					<td><br/>QUERY TYPE:</td>
					<td><br/>
					<select name="REPORT_TYPE">
						<option value="partner_details" <?php if ($report_type == "partner_details") { echo "selected"; } ?> > Partner Details</option>
						<option value="recom_mendations" <?php if ($report_type == "recom_mendations") { echo "selected"; } ?> > Partner Recommendations</option>
						<option value="cvm_hist" <?php if ($report_type == "cvm_hist") { echo "selected"; } ?> > Bingwa History</option>
						<option value="tunus_hist" <?php if ($report_type == "tunus_hist") { echo "selected"; } ?> > Tunukiwa History</option>
							<!--  -->
					</select>
				   
					</td>
				</tr>
				<tr><td colspan="2"><br /><input type='submit' name='sub_msisdn' /></td></tr>
				<tr><td colspan="2"><br /><b>NB: For Query Type "Tunukiwa History", use Customer's MSISDN</b></td></tr>
            </table>
        </center>
        <br/><br/>
                <!-- select AUTH_CODE,TXN_TYPE,XID,TXN_AMOUNT,TXN_DATE,TILL_ID,CASHIER_MSISDN from skn_xen_ent_txn where CASHIER_MSISDN is not null and TXN_STATUS ='10007' order by TXN_DATE --> 
<?php
	if (isset($_POST['msisdn']) && $error == "") {
                                                               
?>
    <input type="hidden" name="confirm_deletion" id="confirm_deletion" />
    <!--<input type="hidden" name="msisdn" id="msisdn" value="<?php //echo $_POST['msisdn']; ?>" />-->
<?php
	switch ($report_type) {
		case 'partner_details': //Fetch partner details
            $tables = array("COKEPROMO.VW_BS_PARTNERS");
			/*BLACKLIST_REASON,PARTNER_MSISDN,TILL_MSISDN,ACTIVE_STATUS,LAST_UPDATE*/			
            $columns = array("PARTNER_MSISDN", "TILL_MSISDN", "ACTIVE_STATUS", "BLACKLIST_REASON", "to_char(LAST_UPDATE, 'dd-MON-yyyy HH:MI:SS AM')");
            $titles = array("PARTNER", "TILL", "STATUS", "REASON", "LAST UPDATE");
            $advanced = "where PARTNER_MSISDN = ".substr($_POST['msisdn'],-9)." order by last_update desc";
            $display_str = $db->display_records($tables, $columns, $titles, "Partner Details", $advanced, null, null, "");
            //$db->edit_displayed_records($tables, $columns);
            echo $display_str;
            //echo "In Working progress...";
            break;
        
		case 'recom_mendations': //Recommendations history
			$tables = array("COKEPROMO.VW_BS_RECOMMENDATION");
			/*CUST_MSISDN, STATUS, OFFER, AWARD, TRX_DATE*/
			$columns = array("CUST_MSISDN", "STATUS", "OFFER", "AWARD", "to_char(TRX_DATE, 'dd-MON-yyyy HH:MI:SS AM')");
			$titles = array("MSISDN", "STATUS", "OFFER NAME", "AWARD", "TRX DATE");
			$advanced = "where PARTNER_MSISDN = ".substr($_POST['msisdn'],-9)." order by TRX_DATE desc";
			$display_str = $db->display_records($tables, $columns, $titles, "Partner Recommendations", $advanced, null, null, "");
			//$db->edit_displayed_records($tables, $columns);
			echo $display_str;
			break;
		
		case 'cvm_hist':  //CVM History
			$tables = array("COKEPROMO.VW_BS_HISTORY");
			$columns = array( "PARTNER_MSISDN", "EVENT", "MESSAGE", "to_char(TRX_DATE, 'dd-MON-yyyy HH:MI:SS AM')");
			$titles = array("MSISDN", "EVENT", "DETAILS", "DATE");
			$advanced = "where PARTNER_MSISDN = ".substr($_POST['msisdn'],-9)." order by TRX_DATE desc";
			$display_str = $db->display_records($tables, $columns, $titles, "Bingwa History", $advanced, null, null, "");
			//$db->edit_displayed_records($tables, $columns);
			echo $display_str;
			// echo "In working progress...";
			break;
			
		case 'tunus_hist':	//Tunukiwa History
			$tables = array("CVM_CAMPAIGN.VW_BS_TUNUS_HISTORY");
			$columns = array( "MSISDN", "EVENT", "EVENT_DETAILS", "to_char(TRX_DATE, 'dd-MON-yyyy HH:MI:SS AM')");
			$titles = array("MSISDN", "EVENT", "DETAILS", "DATE");
			$advanced = "where MSISDN = ".substr($_POST['msisdn'],-9)." order by TRX_DATE desc";
			$display_str = $db_tun->display_records($tables, $columns, $titles, "Tunukiwa History", $advanced, null, null, "");
			//$db->edit_displayed_records($tables, $columns);
			echo $display_str;
			// echo "In working progress...";
			break;
			/*
			MSISDN,EVENT,EVENT_DETAILS,TRX_DATE
			*/
                               
        default:
            echo "No entry";
            break;
    }

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
