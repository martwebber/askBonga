<?php session_start();
ob_start();
/**
 * Created by Derrick Dickens Otina
 * 
 */
        require_once "../util/functions.php";

        $error = "";

        insert_header2();

    if (isset($_POST['msisdn'])) {
        $validator =  new Validate();
        if (strlen($_POST['msisdn'])==9 && !$validator->is_valid_msisdn($_POST['msisdn'])) {
            $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456, or 254722123456";
        }elseif ($_POST['msisdn'] == null) {
            $error = "<br />Please ensure 'MSISDN' or 'TILL' Field is not Empty!!!";
        }
// 254795483392
    }

     //initialise database object
    $BONGA_DB_TYPE = "oracle";
    $BONGA_DATABASEHOST = "10.184.29.1"; // or use svthk1-scan
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "mpesacvm";
    $BONGA_DATABASEPASSWORD = "mp#sacvm_123";
    $BONGA_DATABASENAME = "CVMDB";
    
        $db = new Database($BONGA_DB_TYPE, $BONGA_DATABASEHOST, $BONGA_DATABASEPORT, $BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASENAME);
        // $db = oci_connect("akrs", 'akrs123', "10.184.0.101:1521/TWIGAUAT");

       
?>

                                <?php
                                    if (!isset($_POST['REPORT_TYPE'])){

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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">BIASHARA NI MPESA TU</p>
        <br />
  
        <form name='points_list' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method='post'>
        <center>
        <table class="tablebody border" width="80%">
                        <tr>
                                <th colspan="2" class="tableheader"><b>SEARCH USING TILL MSISDN / TILL / NOMINATED NUMBER</b></th>
                        </tr>
                        <tr>
                                <td><br />TILL MSISDN / TILL / NOMINATED NO:</td><td><br /><input type='text' name='msisdn' value='<?php echo $_POST['msisdn']; ?>'/></td>
                        </tr>
                        <tr>
                                <td><br />QUERY TYPE:</td>
                                <td><br />
                                <select name="REPORT_TYPE">
                                        <option value="merchant_details" <?php if ($report_type == "merchant_details") {
                                            echo "selected";
                                        } ?> > Merchant Details</option>
                                        <option value="merchant_hist" <?php if ($report_type == "merchant_hist") {
                                            echo "selected";
                                        } ?> > Merchant Points Accumulation</option>
                                        <option value="Redemption_hist" <?php if ($report_type == "Redemption_hist") {
                                            echo "selected";
                                        } ?> > Merchant Redemption History</option>
                                        <option value="Reversal_hist" <?php if ($report_type == "Reversal_hist") {
                                            echo "selected";
                                        } ?> > Merchant Reversal History</option>
                                        <!--  -->
                                </select>
                               
                                </td>
                        </tr>
            <tr>
                <td colspan="2"><br /><input type='submit' name='sub_msisdn' /></td>
            </tr>
                </table>
        </center>
                <br /><br />
                <!-- select AUTH_CODE,TXN_TYPE,XID,TXN_AMOUNT,TXN_DATE,TILL_ID,CASHIER_MSISDN from skn_xen_ent_txn where CASHIER_MSISDN is not null and TXN_STATUS ='10007' order by TXN_DATE -->
        <?php
            if (isset($_POST['msisdn']) && $error == "") {
                                
                                        
        ?>
                        <input type="hidden" name="confirm_deletion" id="confirm_deletion" />
                        <!--<input type="hidden" name="msisdn" id="msisdn" value="<?php //echo $_POST['msisdn']; ?>" />-->
                        <?php
                        switch ($report_type) {
                                case 'merchant_details':
                                      $tables = array("MPESACVM.vw_merchant_details");
                                                $columns = array("TILL", "to_char(trx_date, 'dd-MON-yyyy HH:MI:SS AM')","round(TARGET)", "to_char(POINTS,'999,990.99')" ,"CATEGORY");
                                $titles = array("MERCHANT TILL", "JOIN DATE","TARGET","TOTAL POINTS","CATEGORY" );
                                $advanced = strlen($_POST['msisdn'])>=9 ? "where substr(msisdn,-9) = ".substr($_POST['msisdn'],-9)."or nominated_number= ".substr($_POST['msisdn'],-9)." order by trx_date desc" :  "where till = '".$_POST['msisdn']."' order by trx_date desc";
                                                $display_str = $db->display_records($tables, $columns, $titles, "Merchant Details", $advanced, null, null, "");
                                                //$db->edit_displayed_records($tables, $columns);
                                echo $display_str;
                                
                               // echo "<br>".substr($_POST['msisdn'],-9)."<br>";
                              
                                // echo "In Working progress...";
                                         break;
                                case 'merchant_hist':
                                        $tables = array("MPESACVM.VW_MERCHANT_STATEMENTS");
                                        $columns = array("TILL_number", "to_char(POINTS_EARNED,'999,990.99')","to_char(ACCUMULATED_POINTS, '999,990.99')", "round(TARGET)","MPESA_TID","to_char(trx_date, 'dd-MON-yyyy HH:MI:SS AM')");
                                        $titles = array("MERCHANT TILL", "POINTS EARNED","POINTS ACCUMULATION", "TARGET", "DETAILS","DATE");
                                        $advanced = strlen($_POST['msisdn'])>=9 ? "where substr(msisdn,-9) = ".substr($_POST['msisdn'],-9)." or nominated_number= ".substr($_POST['msisdn'],-9)." order by trx_date desc" :  "where till_number = '".$_POST['msisdn']."' ";
                                               $display_str = $db->display_records($tables, $columns, $titles, "MERCHANT History", $advanced, null, null, "");
                                                        
                                        echo $display_str; 
                                                        
                                           
                                        // echo substr($_POST['msisdn'],-9);
                                         // echo "In Working progress...";
                                        break;
                                case 'Redemption_hist':
                                        $tables = array("MPESACVM.vw_merchant_redemptions");
                                        $columns = array("TILL_NUMBER", "to_char(TRX_DATE, 'dd-MON-yyyy HH:MI:SS AM')","to_char(POINTS_EARNED,'999,990.99')", "AMOUNT_TRANSACTED", "MPESA_TID");
                                        $titles = array("MERCHANT TILL","DATE", "POINTS REDEEMED", "AMOUNT", "DETAILS");
                                        $advanced = strlen($_POST['msisdn'])>=9 ? "where substr(msisdn,-9) = ".substr($_POST['msisdn'],-9)." order by trx_date desc" :  "where TILL_NUMBER = '".$_POST['msisdn']."' order by trx_date desc";
                                        $display_str = $db->display_records($tables, $columns, $titles, "Redemption", $advanced, null, null, "");//$db->edit_displayed_records($tables, $columns);
                                        echo $display_str;
                                       // echo "In working progress...";
                                        break;
                                case 'Reversal_hist':
                                        $tables = array("MPESACVM.vw_merchant_reversals");
                                        $columns = array("to_char(TRX_DATE, 'dd-MON-yyyy HH:MI:SS AM')","TRANSACTION_TYPE","to_char(points_reversed,'999,990.99')", "mpesa_transactionid", "agent_number");
                                        $titles = array("DATE", "TRANSACTION TYPE" ,"POINTS REVERSED", "TRANSACTION ID", "MERCHANT TILL");
                                        $advanced = strlen($_POST['msisdn'])>=9 ? "where substr(msisdn,-9) = ".substr($_POST['msisdn'],-9)." order by trx_date desc" :  "where AGENT_NUMBER = '".$_POST['msisdn']."' order by trx_date desc";
                                        $display_str = $db->display_records($tables, $columns, $titles, "Redemption", $advanced, null, null, "");
                                        //$db->edit_displayed_records($tables, $columns);
                                        echo $display_str;
                                       // echo "In working progress...";
                                        break;
                                default:
                                       echo "No entry \t\n\n\tStill in Development stage";
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
ob_end_flush();
?>