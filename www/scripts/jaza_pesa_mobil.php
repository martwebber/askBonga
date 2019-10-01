<?php session_start();
ob_start();
/**
 * Created by Derrick Dickens Otina
 * modified by Teddy Nzioka
 */
        require_once "../util/functions.php";
        include (dirname(__DIR__) .DIRECTORY_SEPARATOR."/usr/local/lib/php/jaza_pesa_functions_test.php");

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
 //initialise database object for Test environment
    // $BONGA_DB_TYPE = "oracle";
    // $BONGA_DATABASEHOST = "10.184.7.83"; // or use svthk1-scan
    // $BONGA_DATABASEPORT = "1521";
    // $BONGA_DATABASEUSER = "mpesacvm";
    // $BONGA_DATABASEPASSWORD = "l3t#us3r1n";
    // $BONGA_DATABASENAME = "GTOSTK";
    
    // end Test
        
    //initialise database object for Production environment
    $BONGA_DB_TYPE = "oracle";
    $BONGA_DATABASEHOST = "10.184.30.11"; // or use svthk1-scan
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "B2CPromo";
    $BONGA_DATABASEPASSWORD = "L3t#b2cpr0m0#1n";
    $BONGA_DATABASENAME = "CVMDB";
    
    //end production
    
        $db = new Database($BONGA_DB_TYPE, $BONGA_DATABASEHOST, $BONGA_DATABASEPORT, $BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASENAME);

    $conn = oci_connect($BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASEHOST."/".$BONGA_DATABASENAME);
       
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
              
             if (strlen($_POST['msisdn'])==10) {
                $num = "254".substr($_POST['msisdn'],-9);
            }elseif(strlen($_POST['msisdn']) == 12) {
                $num = $_POST['msisdn'];
            }elseif (strlen($_POST['msisdn'])==9) {
                $num = "254".substr($_POST['msisdn'],-9);
            }else{
                $num = $_POST['msisdn'];
            }
                                
                                        
        ?>
                        <input type="hidden" name="confirm_deletion" id="confirm_deletion" />
                        <!--<input type="hidden" name="msisdn" id="msisdn" value="<?php //echo $_POST['msisdn']; ?>" />-->
                        <?php
                        switch ($report_type) {
                                case 'merchant_details':
                                    if (!$conn) {
                                        echo "No Connection to DB ";
                                    }else{
                                        $query = "SELECT * FROM JazaPesaCustomerDetails WHERE MSISDN = '".
                                           $num
                                        ."'";
                                        $statement = oci_parse($conn, $query);
                                        oci_execute($statement);
                                        if(oci_num_rows($statement) === false){
                                            echo "The Customer is not whitelisted!!!";
                                        }else{
                                            ?>
                                            
                                           <table class="tablebody border" width="100%">
                                              <thead class='tableheader'>
                                                <tr>
                                                    <th>MSISDN</th>
                                                    <th>CATEGORY</th>
                                                    <th>TARGET</th>
                                                    <th>TOTAL TRANSACTIONS</th>
                                                    <th>Status</th>
                                                    <!-- <th>ACTION</th> -->
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <?php  while ($row=oci_fetch_assoc($statement)) { ?>
                                                    <tr>
                                                        <td><?php echo  $row['MSISDN']; ?></td>
                                                        <td><?php echo  $row['CATEGORY']; ?></td>
                                                        <td><?php 
                                                                if ($row['CATEGORY'] == "New") {
                                                                    echo '4';
                                                                }elseif ($row['CATEGORY'] == "Active") {
                                                                    echo '5';
                                                                }else {
                                                                    echo '1';
                                                                }
                                                         ?>
                                                             
                                                         </td>
                                                         <td><?php echo  $row['TOTAL_TRANSACTIONS']; ?></td>
                                                <td><?php 
                                                    if($row['STATUS'] == 0){
                                                        echo "Opted In";
                                                    }elseif ($row['STATUS'] == 1) {
                                                        echo "Opted Out";
                                                    }elseif ($row['STATUS'] == 'X') {
                                                        echo "The customer is not whitelisted";
                                                    }

                                                  ?></td>
                                               
                                                    </tr>
                                                <?php } ?>
                                              </tbody>
                                          </table>

                                        <?php

                                        
                                        }
                                        oci_free_statement($statement);
                                        oci_close($conn);
                                      // echo "Not ready yet!!!"; 
                                    }
                                       
                                      break;
                                case 'merchant_hist':
                                    $tables = array("JazaPesaCustomerWinnings");
                                    $columns = array("MPESA_TRANSACTION_ID", "to_char(DATE_AWARDED, 'dd-MON-yyyy HH:MI:SS AM')","REWARD");
                                    $titles = array("MPESA ID", "DATE AWARDED", "AWARD");
                                    $advanced = "where msisdn = '".$num."' order by DATE_AWARDED desc";
                                                $display_str = $db->display_records($tables, $columns, $titles, "Customer Winnings", $advanced, null, null, "");
                                                //$db->edit_displayed_records($tables, $columns);
                                    echo $display_str;
                                       // echo "Not ready yet!!!";
                                        break;
                                case 'Redemption_hist':
                                    $tables = array("JazaPesaCustomerAccumHistory");
                                    $columns = array("MSISDN", "MPESA_TRANSACTION_ID", "to_char(DATE_POSTED, 'dd-MON-yyyy HH:MI:SS AM')","AMOUNT", "DEBIT_PARTY_NAME", "DEBIT_PARTY_SHORT_CODE");
                                    $titles = array("MSISDN", "MPESA ID", "DATE POSTED","AMOUNT", "DEBIT PARTY NAME", "DEBIT PARTY SHORTCODE");
                                    $advanced = "where msisdn = ".$num." order by DATE_POSTED desc";
                                                $display_str = $db->display_records($tables, $columns, $titles, "Customer Details", $advanced, null, null, "");
                                                //$db->edit_displayed_records($tables, $columns);
                                    echo $display_str;
                                    //     // echo "Not ready yet!!";
                                        break;
                                case 'Reversal_hist':
                                         $tables = array("JazaPesaCustomerHistory");
                                        $columns = array("MSISDN", "to_char(DATE_POSTED, 'dd-MON-yyyy HH:MI:SS AM')","EVENT", "DESCRIPTION");
                                        $titles = array("MSISDN", "DATE POSTED","EVENT", "DESCRIPTION");
                                        $advanced = "where msisdn = ".$num." order by DATE_POSTED desc";
                                                    $display_str = $db->display_records($tables, $columns, $titles, "Customer Details", $advanced, null, null, "");
                                                    //$db->edit_displayed_records($tables, $columns);
                                        echo $display_str;
                                            // echo "Not ready yet!!";
                                        break;
                                default:
                                       echo "No entry \t\n\n\tStill in Development stage";
                                        break;
                        }
                                
                                
                        ?>
                
        <?php
                
            }
        ?>
        
</div>

<!-- insert the footer -->
<?php
        insert_footer2();
		ob_end_flush();
?>
