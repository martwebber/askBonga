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
        <form name='query_ftth_gsm_info' action='ftth_gsm.php' method='post'>
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
				$tables = array("VW_FTTH_INTERNETPLUS");
				$columns = array("SPONSOR_MSISDN","BENEFICIARY_MSISDN","MPESA_TRX", "GSM_PRODUCT", "PRODUCT_AWARDED","TRX_DATE");
				$titles = array("SPONSOR MSISDN","BENEFICIARY MSISDN","MPESA TRANSACTION","GSM PRODUCT","PRODUCT AWARDED","TRANSACTION DATE");
                //$advanced = "where substr(msisdn,-9) = ".substr($_POST['msisdn'],-9)."";
				$advanced = " where SPONSOR_MSISDN = ".substr($_POST['msisdn'],-9)."";
				$display_str = $db->display_records($tables, $columns, $titles, "query_ftth_gsm_info", $advanced, null, null, "9");
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
