<?php session_start();
ob_start();
        require_once "../util/functions.php";

        $error = "";

        insert_header2();


		$longMSISDN = $_POST['msisdn'];
		
		 if (strlen($_POST['msisdn']) > 9 ){
		 
		 $longMSISDN = substr($longMSISDN,-9);
		 
		 }
	
    //initialise database object
	$BONGA_DB_TYPE = "oracle";
    $BONGA_DATABASEHOST = 'svthk1-scan';
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "lipa_na_mpesa";
    $BONGA_DATABASEPASSWORD = "mp3sa_lipa_123#";
    $BONGA_DATABASENAME = "eirsb";
	
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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">FTTH *855# Requests</p>
        <br />
  
        <form name='story_list' action='ftth_ussd_requests.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
                        <tr>
                                <th colspan="2" class="tableheader"><b>MSISDN/CIRCUIT SEARCH</b></th>
                        </tr>
                        <tr>
                                <td><br />MSISDN/CIRCUIT ID:</td><td><br /><input type='text' name='msisdn'  value='<?php echo $_POST['msisdn']; ?>'/></td>
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
                        <!--<input type="hidden" name="msisdn" id="msisdn" value="<?php echo $_POST['msisdn']; ?>" />-->
                        <?php
                                $tables = array("TBL_C2B_FTTH_REQUESTS");
                                $columns = array("TRX_DATE","SPONSOR_MSISDN","MSISDN", "PRODUCT_ID","MPESA_TRX_ID","AMOUNT", " decode(OPERATE_TYPE, '1','Recharge Only','Recharge and Subscription') CBS_RECHARGE_CODE",
								"decode(CBS_RECHARGE_CODE, '1','Success','Failure') CBS_RECHARGE_CODE", "RESULT_CODE", "RESULT_DESC");
								
								
								$titles = array("TRX_DATE","SPONSOR_MSISDN","MSISDN", "PRODUCT_ID","MPESA_TRX_ID","AMOUNT", "OPERATE_TYPE", "CBS_RECHARGE_DETAILS", "CBS SUBSCRIPTION  RESULT_CODE", "CBS SUBSCRIPTION RESULT_DESC");
					
							   $advanced = "where substr(SPONSOR_MSISDN,-9) = $longMSISDN or  MSISDN = $longMSISDN order by TRX_DATE desc";
								
                                $display_str = $db->display_records($tables, $columns, $titles, "story_list", $advanced, null, null, "");
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
		ob_end_flush();	
?>