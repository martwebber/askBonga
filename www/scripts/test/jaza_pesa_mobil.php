
<?php
/**
 * Created by Derrick Dickens Otina
 * 
 */
        require_once "../../util/functions.php";
        //include (dirname(__DIR__) .DIRECTORY_SEPARATOR."jaza_pesa_functions_test.php");

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
    $BONGA_DATABASEHOST = "10.184.7.83"; // or use svthk1-scan
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "mpesacvm";
    $BONGA_DATABASEPASSWORD = "l3t#us3r1n";
    $BONGA_DATABASENAME = "GTOSTK";
    
        $db = new Database($BONGA_DB_TYPE, $BONGA_DATABASEHOST, $BONGA_DATABASEPORT, $BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASENAME);
    

       
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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">Jaza Pesa Mobile Banking Promo</p>
        <br />
  
        <form name='points_list' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method='post'>
        <center>
        <table class="tablebody border" width="80%">
                        <tr>
                                <th colspan="2" class="tableheader"><b>SEARCH USING MSISDN</b></th>
                        </tr>
                        <tr>
                                <td><br />MSISDN:</td><td><br /><input type='text' name='msisdn' value='<?php echo $_POST['msisdn']; ?>'/></td>
                        </tr>
                        <tr>
                                <td><br />QUERY TYPE:</td>
                                <td><br />
                                <select name="REPORT_TYPE">
                                        <option value="merchant_details" <?php if ($report_type == "merchant_details") {
                                            echo "selected";
                                        } ?> > Customer Status</option>
                                        <option value="merchant_hist" <?php if ($report_type == "merchant_hist") {
                                            echo "selected";
                                        } ?> > Customer Winnings</option>
                                        <option value="Redemption_hist" <?php if ($report_type == "Redemption_hist") {
                                            echo "selected";
                                        } ?> > Customer Accumulation History</option>
                                        <option value="Reversal_hist" <?php if ($report_type == "Reversal_hist") {
                                            echo "selected";
                                        } ?> > Customer History</option>
                                        <!--  -->
                                </select>
                               
                                </td>
                        </tr>
            <tr>
                <td colspan="2"><br /><input type='submit' name='sub_msisdn' /></td>
            </tr>
                </table>
        </center>
        </form>
                <br /><br />
                <!-- select AUTH_CODE,TXN_TYPE,XID,TXN_AMOUNT,TXN_DATE,TILL_ID,CASHIER_MSISDN from skn_xen_ent_txn where CASHIER_MSISDN is not null and TXN_STATUS ='10007' order by TXN_DATE -->
        <?php
            if (isset($_POST['msisdn']) && $error == "" ) {
            //   if (substr($_POST['msisdn'], 0, 3) != '254' && substr($_POST['msisdn'], 0, 1) == '0') {
            //     $num = "254".substr($_POST['msisdn'], -9);
            //     //echo "was ".$_POST['msisdn']." now is  " .$num;
            //     $checkCust = checkCustmerStatus($num);

            //                     $history = customerHistory($num);
            //                     $hist = $history->History->EventInfo;

            //                     $accumilationhistory = CustomerAccumulationHistory($num);
            //                     $accumilations = $accumilationhistory->History->EventInfo;

            //                     $winnings = checkWinnings($num);
            //                     $rewards = $winnings->Rewards->Reward;

            //                    if (isset($_POST['opt']) && isset($_POST['msisdn'])) {
            //                       $flag = (int)$_POST['opt'];
            //                         $optin = toggleOptIn($num, $flag);
            //                         $report_type = "merchant_details";
            //                     echo $optin->ResponseMessage;
            //                    }
            //   }elseif (substr($_POST['msisdn'], 0, 3) != '254' && substr($_POST['msisdn'], 0, 1) == '7') {
            //     $num = "254".substr($_POST['msisdn'], -9);
            //     // echo "was ".$_POST['msisdn']." now is  " .$num;
            //     $checkCust = checkCustmerStatus($num);

            //                     $history = customerHistory($num);
            //                     $hist = $history->History->EventInfo;

            //                     $accumilationhistory = CustomerAccumulationHistory($num);
            //                     $accumilations = $accumilationhistory->History->EventInfo;

            //                     $winnings = checkWinnings($num);
            //                     $rewards = $winnings->Rewards->Reward;

            //                    if (isset($_POST['opt']) && isset($_POST['msisdn'])) {
            //                       $flag = (int)$_POST['opt'];
            //                         $optin = toggleOptIn($num, $flag);
            //                         $report_type = "merchant_details";
            //                     echo $optin->ResponseMessage;
            //                    }
            //   }else{
            //     $num = $_POST['msisdn'];
            //                     $checkCust = checkCustmerStatus($num);

            //                     $history = customerHistory($num);
            //                     $hist = $history->History->EventInfo;

            //                     $accumilationhistory = CustomerAccumulationHistory($num);
            //                     $accumilations = $accumilationhistory->History->EventInfo;

            //                     $winnings = checkWinnings($num);
            //                     $rewards = $winnings->Rewards->Reward;

            //                    if (isset($_POST['opt']) && isset($_POST['msisdn'])) {
            //                       $flag = (int)$_POST['opt'];
            //                         $optin = toggleOptIn($num, $flag);
            //                         $report_type = "merchant_details";
            //                     echo $optin->ResponseMessage;
            //                    }
            //   }
                      
                                
                                        
        ?>
                        <!-- <input type="hidden" name="confirm_deletion" id="confirm_deletion" /> -->
                        <!--<input type="hidden" name="msisdn" id="msisdn" value="<?php //echo $_POST['msisdn']; ?>" />-->
                        <?php
                        switch ($report_type) {
                                case 'merchant_details':
                                //      $tables = array("MPESACVM.vw_agent_details");
                                //                 $columns = array("STORE_NUMBER", "AGENT_NUMBER","TILL_MSISDN", "to_char(POINTS,'999,990.99')" ,"STATUS");
                                // $titles = array("MSISDN", "CATEGORY","TARGET","TOTAL TRANSACTIONS","STATUS","ACTION" );
                                // $advanced = strlen($_POST['msisdn'])>=9 ? "where substr(till_msisdn,-9) = ".substr($_POST['msisdn'],-9)." order by trx_date desc" :  "where AGENT_NUMBER = '".$_POST['msisdn']."' order by trx_date desc";

                                //                 $display_str = $db->display_records($tables, $columns, $titles, "Agent Details", $advanced, null, null, "");
                                //                 //$db->edit_displayed_records($tables, $columns);
                                // echo $display_str;
                               
                                echo "In Working progress...";
                                         break;
                                      
                                case 'merchant_hist':
                                       
                                       echo "No entry \t\n\n\tStill in Development stage";
                                        break;
                                case 'Redemption_hist':
                                        echo "No entry \t\n\n\tStill in Development stage";
                                        break;
                                case 'Reversal_hist':
                                       echo "No entry \t\n\n\tStill in Development stage";  
                                        break;
                                default:
                                       echo "No entry \t\n\n\tStill in Development stage";
                                        break;
                        }
                      }          
                                
                        ?>
                
     
        
</div>

<!-- insert the footer -->
<?php
        insert_footer2();
?>
