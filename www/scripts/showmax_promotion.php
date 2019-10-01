<?php
	require_once "../util/functions.php";

	$error = "";

		
     //first check if user is logged in
     $please_login_url = "please_login.php";
	 $status = check_logged_in($please_login_url);
		
	if ($status == 1) { 
	
	insert_header2();

	//initialise database object

   
    $BONGA_DB_TYPE = "oracle";
	$BONGA_DATABASEHOST = "172.29.225.3";
	$BONGA_DATABASEPORT = "1521"; 
    $BONGA_DATABASEUSER = "showmax";
    $BONGA_DATABASEPASSWORD = "showmax1QAZ2WSX";
	$BONGA_DATABASENAME = "EIRDB";

	$db = new Database($BONGA_DB_TYPE, $BONGA_DATABASEHOST, $BONGA_DATABASEPORT, $BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASENAME);


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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">ShowMax Promotion Status</p>
        <br />
        <form name='showmax' action='showmax_promotion.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
			<tr>
				<th colspan="2" class="tableheader"><b>Enter Request Customer Number</b></th>
			</tr>
			<tr>
				<td><br />MSISDN: </td><td><br /><input type='text' name='msisdn' /></td>
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
			<?php
		$tables = array("TBL_SHOWMAX_PROMOTION");
				$columns = array("MSISDN","TRX_DATE","VOUCHER");
                $titles = array("MSISDN","TRX_DATE","VOUCHER");
				
                $advanced = "where SUBSTR(MSISDN,-9) = '".substr($_POST['msisdn'],-9)."' order by TRX_DATE desc ";
				$display_str = $db->display_records($tables, $columns, $titles, "showmax", $advanced, null, null, "");
			
				echo $display_str;
			?>
		</form>
        <?php
            }
			
			/* SELECT DATE_CREATED,
  REQUEST_MSISDN,
  BENEFIT_MSISDN,
  CBS_PRODUCT_DESC,
  CBS_PRODUCT_ID,
  MPESA_RESULT_DESC,
  MPESA_TRANSACTION_ID,
  CBS_RESPONSE_DESC,
  SHOWMAXTYPE,
  BUNDLE_AMOUNT,
  SHOWMAX_AMOUNT,
  TOTAL_AMOUNT,
  SHOWMAX_CODE
FROM VW_SHOWMAX_SUBSCRIPTIONS ;*/
        ?>
</div>

<?php
	insert_footer2();

	}else{
	
		header("Location: please_login.php");
	}
?>		