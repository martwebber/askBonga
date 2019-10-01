<?php
//Query all the entries to the CHANUA BIZ for a specific MSISDN

require_once "../util/functions.php";

$error = "";

insert_header2();

if (isset($_POST['msisdn'])) {
    $validator = new Validate();
    if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
        $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
    }
	
	if(empty($_POST["date_from"]) || empty($_POST["date_to"])){
		$error = "<br />Please provide report date range";
	} 
}

//initialise database object
//$PR_DB_TYPE = "oracle";
//$PR_DATABASEHOST = "172.28.220.7";
//$PR_DATABASEPORT = "1521";
//$PR_DATABASEUSER = "TIBCOEHF";
//$PR_DATABASEPASSWORD = "TIBCOEHF";
//$PR_DATABASENAME = "TIBCODB";

$BONGA_DB_TYPE = "oracle";
  $BONGA_DATABASEHOST = 'svthk1-scan' ;
  $BONGA_DATABASEPORT = "1521";
  $BONGA_DATABASEUSER = "cokepromo";
  $BONGA_DATABASEPASSWORD = "C0k#pr0mo";
  $BONGA_DATABASENAME = "EIRSB";

//$db = new Database($PR_DB_TYPE, $PR_DATABASEHOST, $PR_DATABASEPORT, $PR_DATABASEUSER, $PR_DATABASEPASSWORD, $PR_DATABASENAME);

$db = new Database($BONGA_DB_TYPE, $BONGA_DATABASEHOST, $BONGA_DATABASEPORT, $BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASENAME);

?>
<html>
<head>
	
	<title>Partner Activations</title>
	<!--edited--><link rel="stylesheet"href="cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/>
	<!--edited--><script src="cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
	
	<!------------ Including jQuery Date UI with CSS -------------->
	<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
	<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css"/>
	<!-- jQuery Code executes on Date Format option ----->
	<script src="js/script.js"></script>
	<link rel="stylesheet" href="css/style.css">
	
	
	
</head>
<body>

<div class="cspacer">
<?php
if ($error != "") {
    echo "<div style='text-align: left; width: 80%'>\r\n";
    echo "<table class='tablebody border' width='100%'>\r\n";
    echo "<th class='tableheader'>MESSAGE</th>\r\n";
    echo "<tr><td>\r\n";
    echo "<p class='error'>" . $error . "</p>";
    echo "</td></tr>\r\n";
    echo "</table>\r\n";
    echo "<br /><br />\r\n";
    echo "</div>\r\n";
}
?>
    <div align="center" style="text-align:left; width:100%">
        <br />
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">CVM in Retail (Partner Activations)</p>
        <br />

        <form name='partner_report' action='cvm_report.php' method='post'>
            <center>
                <table class="tablebody border" width="80%">
                    <tr>
                        <th colspan="2" class="tableheader"><b>MSISDN SEARCH</b></th>
                    </tr>
                    <tr>
                        <td><br />MSISDN:</td><td><br /><input type='text' name='msisdn' value='<?php if (isset($_POST['msisdn'])) {
																																	echo $_POST['msisdn'];
																																	} ?>'/>&nbsp;&nbsp; (Format: 7XXYYYZZZ) </td>
					</tr>
					
					<tr>
						<td><br />DATE FROM: </td><td><br /><input type='text' name='date_from' class="datepicker"  autocomplete='off' value='<?php echo $_POST['date_from'] ?>' />
					</tr>
								
					<tr>
						<td><br />DATE TO: </td><td><br /><input type='text' name='date_to' class="datepicker"  autocomplete='off' value='<?php echo $_POST['date_to'] ?>' />
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
				
				
				<br />
					<p style="font:Arial, Helvetica, sans-serif; font-size:10px; color:#0000FF; padding: 5px;">Eligible Activations</p>
				<br />
				
				
                <input type="hidden" name="confirm_deletion" id="confirm_deletion"/>
                <?php
				
				$dateFrom = date('Y-m-d', strtotime($_POST['date_from']));
				$dateTo = date('Y-m-d', strtotime($_POST['date_to']));
				$from = str_replace('-', '', $dateFrom);
				$to = str_replace('-', '', $dateTo);		 
				
				
                $tables = array("TBL_PR_RCMNDTION");
                $columns = array("PARTNER_MSISDN", "CUST_MSISDN", "CASE 
															  WHEN R_TYPE=0 then 'TUNUKIWA' 
															  WHEN R_TYPE=1 then 'SAFARICOM APP'
															  ELSE 'Other'
															  END AS R_TYPE", "AMOUNT", "AWARD", "to_char(TRX_TIMESTAMP, 'dd-MON-yyyy hh24:mi')");
                $titles = array("PARTNER_MSISDN", "CUST_MSISDN", "R_TYPE", "AMOUNT", "AWARD", "TRX_TIMESTAMP");
                $advanced = "WHERE PARTNER_MSISDN = '" . '254' . substr($_POST['msisdn'], -9) . "' AND ACTIVE_STATUS='0' AND P_TRX_DATE BETWEEN '".$from."' AND '".$to."'";
                $display_str = $db->display_records($tables, $columns, $titles, "partner_report", $advanced, null, null, "");
                echo $display_str;
                ?>
				
				<br />				
				<br />
					<p style="font:Arial, Helvetica, sans-serif; font-size:10px; color:#FF3300; padding: 5px;">InEligible Activations</p>
				<br />
				
				<input type="hidden" name="confirm_deletion2" id="confirm_deletion"/>
                <?php
				
				
				
                $tables2 = array("TBL_PR_RCMNDTION");
                $columns2 = array("PARTNER_MSISDN", "CUST_MSISDN", "CASE 
															  WHEN R_TYPE=0 then 'TUNUKIWA' 
															  WHEN R_TYPE=1 then 'SAFARICOM APP'
															  ELSE 'Other'
															  END AS R_TYPE", "FAILURE_REASON", "to_char(TRX_TIMESTAMP, 'dd-MON-yyyy hh24:mi')");
                $titles2 = array("PARTNER_MSISDN", "CUST_MSISDN", "R_TYPE", "FAILURE_REASON", "TRX_TIMESTAMP");
                $advanced2 = "WHERE PARTNER_MSISDN = '" . '254' . substr($_POST['msisdn'], -9) . "' AND ACTIVE_STATUS='1' AND P_TRX_DATE BETWEEN '".$from."' AND '".$to."'";
                $display_str2 = $db->display_records($tables2, $columns2, $titles2, "partner_report", $advanced2, null, null, "");
                echo $display_str2;
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


		</body>
		<html>
		
		<script>		
		
		$(document).ready(function() {
		// Datepicker Popups calender to Choose date.
			$(function() {
			$(".datepicker").datepicker();
			// Pass the user selected date format.
			$("#format").change(function() {
			$(".datepicker").datepicker("option", "dd/mm/yy", $(this).val());
			});
			});
			
		});
		

</script>




