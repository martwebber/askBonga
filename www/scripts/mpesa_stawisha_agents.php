<?php session_start();
ob_start();
        require_once "../util/functions.php";

        $error = "";

        insert_header2();

    if (isset($_POST['msisdn'])) {
        $validator =  new Validate();
        if (strlen($_POST['msisdn'])==9 && !$validator->is_valid_msisdn($_POST['msisdn'])) {
            $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
        }elseif ($_POST['msisdn'] == null) {
            $error = "<br />Please ensure 'MSISDN' or 'TILL' Field is not Empty!!!";
        }

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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">MPESA AGENT STAWISHA AREA</p>
        <br />
  
        <form name='points_list' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method='post'>
        <center>
        <table class="tablebody border" width="80%">
                        <tr>
                                <th colspan="2" class="tableheader"><b>SEARCH USING MSISDN OR TILL NUMBER</b></th>
                        </tr>
                        <tr>
                                <td><br />MSISDN OR TILL:</td><td><br /><input type='text' name='msisdn' value='<?php echo $_POST['msisdn']; ?>'/></td>
                        </tr>
                        <tr>
                                <td><br />QUERY TYPE:</td>
                                <td><br />
                                <select name="REPORT_TYPE">
                                        <option value="agent_details" <?php if ($report_type == "agent_details") {
                                            echo "selected";
                                        } ?> > Agent Details</option>
                                        <option value="agent_hist" <?php if ($report_type == "agent_hist") {
                                            echo "selected";
                                        } ?> > Agent History</option>
                                        <option value="Redemption_hist" <?php if ($report_type == "Redemption_hist") {
                                            echo "selected";
                                        } ?> > Agent Redemption History</option>
                                       
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
                                 case 'agent_details':
                                      $tables = array("MPESACVM.vw_agent_details");
                                                $columns = array("STORE_NUMBER", "AGENT_NUMBER","TILL_MSISDN", "to_char(POINTS,'999,999,990.99')" ,"STATUS");
                                $titles = array("SHORT CODE", "AGENT TILL","MSISDN","POINTS   ","STATUS" );
                                $advanced = strlen($_POST['msisdn'])>=9 ? "where substr(till_msisdn,-9) = ".substr($_POST['msisdn'],-9)." order by trx_date desc" :  "where AGENT_NUMBER = '".$_POST['msisdn']."' order by trx_date desc";

                                                $display_str = $db->display_records($tables, $columns, $titles, "Agent Details", $advanced, null, null, "");
                                                //$db->edit_displayed_records($tables, $columns);
                                echo $display_str;
                               
                                // echo "In Working progress...";
                                         break;
                                         
                                case 'agent_hist':
                                        $tables = array("MPESACVM.VW_AGENT_LOYALTY_HISTORY");
                                        $columns = array("to_char(trx_date, 'dd-MON-yyyy HH:MI:SS AM')","TRANSACTION_TYPE", "MPESA_TRANSACTIONID","ACCUMULATION_DETAILS","to_char(TOTAL_POINTS,'999,999,990.99')","AGENT_NUMBER","SHORT_CODE");
                                        $titles = array("DATE", "TRANSACTION", "M-PESA TID", "POINTS","BALANCE","AGENT NUMBER", "SHORT CODE");
                                        $advanced = strlen($_POST['msisdn'])>=9 ? "where substr(msisdn, -9) = ".substr($_POST['msisdn'],-9)." order by trx_date desc" :  "where AGENT_NUMBER = '".$_POST['msisdn']."' order by trx_date desc";
                                       
                                                        $display_str = $db->display_records($tables, $columns, $titles, "Customer History", $advanced, null, null, "");
                                        echo $display_str;
                                         // echo "In Working progress...";
                                        // this is the change -> to_char(ACCUMULATION_DETAILS,'999,990.99')
                                        break;

                                case 'Redemption_hist':
                                        $tables = array("MPESACVM.VW_AGENT_LOYALTY_REDEMPTION");
                                        $columns = array("to_char(TRX_DATE, 'dd-MON-yyyy HH:MI:SS AM')","MPESA_TRANSACTIONID","to_char(POINTS_REDEEMED,'999,999,990.99')", "to_char(TOTAL_POINTS,'999,999,990.99')" ,"VALUE_AWARDED", "AGENT_ID");
                                        $titles = array("DATE", "M-PESA TID", "POINTS REDEEMED", "BALANCE","VALUE AWARDED", "ASSISTANT ID");
                                        $advanced = strlen($_POST['msisdn'])>=9 ? "where substr(msisdn, -9) = ".substr($_POST['msisdn'],-9)." order by trx_date desc" :  "where AGENT_NUMBER = '".$_POST['msisdn']."' order by trx_date desc";
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