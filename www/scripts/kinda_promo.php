<?php session_start();
ob_start();
require_once "../util/functions.php";

        $error = "";

        insert_header2();

    if (isset($_POST['msisdn'])) {
        $validator =  new Validate();
        if (strlen($_POST['msisdn'])==9 && !$validator->is_valid_msisdn($_POST['msisdn'])) {
            $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456, or 254722123456";
        }elseif ($_POST['msisdn'] == null) {
            $error = "<br />Please ensure 'MSISDN' Field is not Empty!!!";
        }
// 254795483392
    }

     //initialise database object
    $BONGA_DB_TYPE = "oracle";
    $BONGA_DATABASEHOST = "10.184.30.11"; // or use svthk1-scan
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "KindergartenPromo";
    $BONGA_DATABASEPASSWORD = "Pr@m0K#nd3rgarten";
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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;text-transform: uppercase;">Kindergarten campaign</p>
        <br />
  
        <form name='points_list' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method='post'>
        <center>
        <table class="tablebody border" width="80%">
                        <tr>
                                <th colspan="2" class="tableheader"><b>SEARCH USING MSISDN </b></th>
                        </tr>
                        <tr>
                                <td><br />MSISDN:</td><td><br /><input type='text' name='msisdn' value='<?php echo $_POST['msisdn']; ?>'/></td>
                        </tr>
                        <tr>
                                <td><br />QUERY TYPE:</td>
                                <td><br />
                                <select name="REPORT_TYPE">
                                        <option value="kinda_cust" <?php if ($report_type == "kinda_cust") {
                                            echo "selected";
                                        } ?> > KINDERGARTEN CUSTOMERS</option>
                                        <option value="kinda_history" <?php if ($report_type == "kinda_history") {
                                            echo "selected";
                                        } ?> > KINDERGARTEN PROMO HISTORY</option>
                                        <option value="kinda_accum_history" <?php if ($report_type == "kinda_accum_history") {
                                            echo "selected";
                                        } ?> > KINDERGARTEN ACCUM HISTORY</option>
                                       <!--  <option value="Reversal_hist" <?php if ($report_type == "Reversal_hist") {
                                            // echo "selected";
                                        } ?> > Merchant Reversal History</option> -->
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
                                case 'kinda_cust':
                                      $tables = array("VW_KINDERGARTEN_CUSTOMERS");
                                                $columns = array("MSISDN","AGE","STATUS","to_char(DATE_OPTED_IN, 'dd-MON-yyyy HH:MI:SS AM')", "to_char(DATE_OPTED_OUT, 'dd-MON-yyyy HH:MI:SS AM')", "ORIGINAL_AGE", "ONEMONTHREWARD", "SECONDMONTHREWARD", "THIRDMONTHREWARD");
                                $titles = array("MSISDN","AGE", "STATUS","OPTED IN DATE","OPTED OUT DATE","ORIGINAL AGE","ONE MONTH REWARD","SECOND MONTH REWARD","THIRD MONTH REWARD");
                                $advanced = "where substr(msisdn,-9) = ".substr($_POST['msisdn'],-9)." order by DATE_OPTED_IN desc";
                                                $display_str = $db->display_records($tables, $columns, $titles, "Fuliza OPTINS", $advanced, null, null, "");
                                                //$db->edit_displayed_records($tables, $columns);
                                echo $display_str;
                                
                               // echo "<br>".substr($_POST['msisdn'],-9)."<br>";
                              
                                // echo "In Working progress...";
                                         break;
                                case 'kinda_history':
                                        $tables = array("VW_KINDERGARTEN_CUST_HISTORY");
                                        $columns = array("MSISDN","TITLE", "DESCRIPTION","to_char(DATE_POSTED, 'dd-MON-yyyy HH:MI:SS AM')","CUSTOMER_ID");
                                        $titles = array("MSISDN", "TITLE","DESCRIPTION", "DATE POSTED", "CUSTOMER ID");
                                        $advanced = "where substr(msisdn,-9) = ".substr($_POST['msisdn'],-9)." order by DATE_POSTED desc";
                                               $display_str = $db->display_records($tables, $columns, $titles, "FULIZA History", $advanced, null, null, "");
                                                        
                                        echo $display_str; 
                                                        
                                           
                                        // echo substr($_POST['msisdn'],-9);
                                         // echo "In Working progress...";
                                        break;
                                case 'kinda_accum_history':
                                        $tables = array("VW_KINDERGARTEN_ACCUM_HIST");
                                        $columns = array("MSISDN", "TRANSACTION_TYPE", "to_char(DATE_POSTED, 'dd-MON-yyyy HH:MI:SS AM')", "AMOUNT", "TRANSACTION_ID", "CUSTOMER_AGE", "DEBIT_PARTY", "IS_COUNTED");
                                        $titles = array("MSISDN","TRANSACTION TYPE","DATE POSTED","AMOUNT", "TRANSACTION ID","CUSTOMER AGE", "DEBIT PARTY", "IS_COUNTED");
                                        $advanced = "where substr(msisdn,-9) = ".substr($_POST['msisdn'],-9)." order by DATE_POSTED desc";
                                        $display_str = $db->display_records($tables, $columns, $titles, "FULIZA REWARDS", $advanced, null, null, "");//$db->edit_displayed_records($tables, $columns);
                                        echo $display_str;
                                       // echo "In working progress...";
                                        break;
                                // case 'Reversal_hist':
                                //         $tables = array("MPESACVM.vw_merchant_reversals");
                                //         $columns = array("to_char(TRX_DATE, 'dd-MON-yyyy HH:MI:SS AM')","TRANSACTION_TYPE","to_char(points_reversed,'999,990.99')", "mpesa_transactionid", "agent_number");
                                //         $titles = array("DATE", "TRANSACTION TYPE" ,"POINTS REVERSED", "TRANSACTION ID", "MERCHANT TILL");
                                //         $advanced = strlen($_POST['msisdn'])>=9 ? "where substr(msisdn,-9) = ".substr($_POST['msisdn'],-9)." order by trx_date desc" :  "where AGENT_NUMBER = '".$_POST['msisdn']."' order by trx_date desc";
                                //         $display_str = $db->display_records($tables, $columns, $titles, "Redemption", $advanced, null, null, "");
                                //         //$db->edit_displayed_records($tables, $columns);
                                //         echo $display_str;
                                //        // echo "In working progress...";
                                //         break;
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
