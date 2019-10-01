<?php session_start();
ob_start();
        require_once "../util/functions.php";

        $error = "";

        insert_header2();

    if (isset($_POST['msisdn'])) {
        $validator =  new Validate();
        if (!$validator->is_valid_msisdn(substr($_POST['msisdn'],-9))) {
            $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">MPESA ME PROMOTION</p>
        <br />
  
        <form name='points_list' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method='post'>
        <center>
        <table class="tablebody border" width="80%">
                        <tr>
                                <th colspan="2" class="tableheader"><b>MSISDN SEARCH</b></th>
                        </tr>
                        <tr>
                                <td><br />MSISDN:</td><td><br /><input type='text' name='msisdn' value='<?php echo $_POST['msisdn']; ?>'/></td>
                        </tr>
                        <tr>
                                <td><br />QUERY TYPE:</td>
                                <td><br />
                                <select name="REPORT_TYPE">
                                        <option value="cust_details" <?php if ($report_type == "cust_details") {
                                            echo "selected";
                                        } ?> > Customer Details</option>
                                        <option value="cust_hist" <?php if ($report_type == "cust_hist") {
                                            echo "selected";
                                        } ?> > Customer History</option>
                                        <option value="Redemption_hist" <?php if ($report_type == "Redemption_hist") {
                                            echo "selected";
                                        } ?> > Redemption History</option>
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
                                case 'cust_details':
                                      $tables = array("MPESACVM.vw_mpesa_me_subs");
                                                $columns = array("MSISDN", "to_char(join_date, 'dd-MON-yyyy HH:MI:SS AM')","TARGET");
                                $titles = array("MSISDN", "JOIN DATE ","TARGET");
                                $advanced = "where substr(msisdn, -9) = ".substr($_POST['msisdn'],-9)." order by join_date desc";
                                                $display_str = $db->display_records($tables, $columns, $titles, "Customer Details", $advanced, null, null, "");
                                                //$db->edit_displayed_records($tables, $columns);
                                echo $display_str;
                               
                                // echo "In Working progress...";
                                         break;
                                case 'cust_hist':
                                        $tables = array("MPESACVM.vw_mpesa_me");
                                        $columns = array("MSISDN", "ACTION","ACTION_DETAILS", "to_char(trx_date, 'dd-MON-yyyy HH:MI:SS AM')");
                                        $titles = array("MSISDN", "Action","Details", "Date");
                                        $advanced = "where substr(msisdn, -9) = ".substr($_POST['msisdn'],-9)." order by trx_date desc";
                                                        $display_str = $db->display_records($tables, $columns, $titles, "Customer History", $advanced, null, null, "");
                                        //$db->edit_displayed_records($tables, $columns);
                                        echo $display_str;
                                        break;
                                case 'Redemption_hist':
                                        $tables = array("MPESACVM.vw_mpesa_me_redeem");
                                        $columns = array( "MSISDN", "to_char(REDEEM_DATE, 'dd-MON-yyyy HH:MI:SS AM')","MPESA_CODE", "AMOUNT");
                                        $titles = array("MSISDN", "DATE", "TRANSACTION ID", "AMOUNT REDEEMED");
                                        $advanced = "where substr(MSISDN, -9) = ".substr($_POST['msisdn'],-9)." order by redeem_date desc";
                                        $display_str = $db->display_records($tables, $columns, $titles, "Redemption", $advanced, null, null, "");
                                        //$db->edit_displayed_records($tables, $columns);
                                        echo $display_str;
                                // echo "In working progress...";
                                        break;
                               
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
ob_end_flush();
?>