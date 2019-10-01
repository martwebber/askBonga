<?php
require_once "../util/functions.php";

$error = "";

insert_header2();

//if (isset($_POST['msisdn'])) {
//    $validator = new Validate();
//}
//initialise database object
//$BONGA_DB_TYPE = "oracle";
//$BONGA_DATABASEHOST = "172.29.225.3";
//$BONGA_DATABASEPORT = "1521";
//$BONGA_DATABASEUSER = "lipa_na_mpesa";
//$BONGA_DATABASEPASSWORD = "mp3sa_lipa_123#";
//$BONGA_DATABASENAME = "eirsb";
//
//$BONGA_DB_TYPE = "oracle";
//$BONGA_DATABASEHOST = "172.28.220.7";
//$BONGA_DATABASEPORT = "1521";
//$BONGA_DATABASEUSER = "TIBCOEHF";
//$BONGA_DATABASEPASSWORD = "TIBCOEHF";
//$BONGA_DATABASENAME = "TIBCODB";
////$BONGA_DATABASENAME = "EIRDB";
//
//$db = new Database($BONGA_DB_TYPE, $BONGA_DATABASEHOST, $BONGA_DATABASEPORT, $BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASENAME);
?>

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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">Advantage Plus Request Status</p>
        <br />
        <form name='advantage_plus' action='advantage_plus_status_query.php' method='post'>
            <center>
                <table class="tablebody border" width="80%">


                    <tr>
                        <th colspan="2" class="tableheader"><b>SEARCH STATUS</b></th>
                    </tr>
                    <tr>
                        <td><br />MSISDN: </td><td><br /><input type='text' name='msisdn' placeholder="Optional" /></td>
                    </tr>
                    <tr>
                        <td><br />PROCESS STATUS:</td><td><br />
                            <select name="PROCCESS_TYPE">
                                <option value="">--SELECT--</option>
                                <option value="ERROR">ERROR</option>
                                <option value="CANCELLED">CANCELLED</option>
                                <option value="COMPLETE">COMPLETE</option>
                                <option value="PROCESSING">PROCESSING</option>
                            </select>
                            <?php
                            if (!isset($_POST['PROCESSING'])) {

//                                $errorMessage = "<li>You forgot to select your business type!</li>";
//                                echo $errorMessage;
                            } else {
                                $processtype = $_POST['PROCCESS_TYPE'];
                            }
                            ?>


                            <br><br>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><br /><input type='submit' name='sub_msisdn' /></td>
                    </tr>
                </table>
            </center>

            <?php
            if (isset($_POST['PROCCESS_TYPE'])) {
                ?>
                                                                                    <!--                <input type="hidden" name="confirm_deletion" id="confirm_deletion" />-->
                                                                                                    <!--<input type="hidden" name="msisdn" id="msisdn" value="<?php echo $_POST['msisdn']; ?>" />-->
                <?php
                //$dbconn = oci_connect("TIBCOEHF", 'goodmonger123', "172.29.246.68:1521/TIBCODB"); //HQ
				$dbconn = oci_connect("TIBCOEHF", 'goodmonger123', "10.184.27.68:1521/TIBCODB");  //THIKA

                if (!$dbconn) {
                    echo "Not connnected";
                } else {

//                    echo "Connected";
                    //msisdn
                    $msisdn = $_POST['msisdn'];
                    $process_status = "ERROR";
                    $process_status = $_POST['PROCCESS_TYPE'];

                    $msisdn = substr($msisdn, -9);
                    if ($msisdn != '') {
                        $sql = "SELECT PP_ID, PP_OMPUBLIC_ID, PP_ORDER_NUMBER, PP_MSISDN, PP_DEPOSIT_AMOUNT, PP_PROCESS_STATUS, PP_REQUEST_DATE, PP_ORDER_TYPE, PP_ORDER_PROFILE,PP_ERROR_MSG FROM PP_PRE2HYB_REQUESTS WHERE  PP_MSISDN = '$msisdn' ORDER BY PP_REQUEST_DATE DESC ";
                    } else {
                        $sql = "SELECT PP_ID, PP_OMPUBLIC_ID, PP_ORDER_NUMBER,PP_MSISDN, PP_DEPOSIT_AMOUNT, PP_PROCESS_STATUS, PP_REQUEST_DATE, PP_ORDER_TYPE, PP_ORDER_PROFILE,PP_ERROR_MSG FROM PP_PRE2HYB_REQUESTS WHERE  PP_PROCESS_STATUS = '$process_status' OR PP_MSISDN = '$msisdn' ORDER BY PP_REQUEST_DATE DESC";
                    }
                    $stid = oci_parse($dbconn, $sql);
                    oci_execute($stid);
                    // Fetch each row in an associative array

                    echo "<table class='tablebody border' width='100%'>";
                    echo "<tr>";
                    echo "<th>PP ID</th>";
                    echo "<th>MSISDN</th>";
                    echo "<th>OMP PUBIC ID</th>";
                    echo "<th>OMP ORDER NUMBER</th>";
                    // echo "<th>MSISDN</th>";
                    echo "<th>DEPOSIT AMOUNT</th>";
                    echo "<th>PROCESS STATUS</th>";
                    echo "<th>REQUEST DATE</th>";
                    echo "<th>ORDER TYPE</th>";
                    echo "<th>ORDER PROFILE</th>";
                    echo "<th>ERROR MESSAGE</th>";
                    echo "<th>ACTION</th>";
                    echo "<tr>";
                    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_ASSOC)) {

                        $pp_id = $row['PP_ID'];
                        $pp_msisdn = $row['PP_MSISDN'];
                        $pp_public_id = $row['PP_OMPUBLIC_ID'];
                        $pp_order_number = $row['PP_ORDER_NUMBER'];
                        $pp_damount = $row['PP_DEPOSIT_AMOUNT'];
                        $process_status = $row['PP_PROCESS_STATUS'];
                        $pp_r_date = $row['PP_REQUEST_DATE'];
                        $pp_ordertype = $row['PP_ORDER_TYPE'];
                        $pp_order_profile = $row['PP_ORDER_PROFILE'];
                        $pp_error_message = $row['PP_ERROR_MSG'];

                        // Output a row
                        echo "<tr>";
                        echo "<td>$pp_id</td>";
                        echo "<td>$pp_msisdn</td>";
                        echo "<td>$pp_public_id</td>";
                        echo "<td>$pp_order_number</td>";
                        echo "<td>$pp_damount</td>";
                        echo "<td>$process_status</td>";
                        echo "<td>$pp_r_date</td>";
                        echo "<td>$pp_ordertype</td>";
                        echo "<td>$pp_order_profile</td>";
                        echo "<td>" . substr($pp_error_message, 0, 70) . "</td>";
                        if ($process_status == "ERROR") {
                            echo '<td>';
                            echo ' ';
                            echo '<a class="btn btn-danger" href="resubmit.php?PP_ID=' . $row['PP_ID'] . ' &PP_PROCESS_STATUS= ' . $row['PP_PROCESS_STATUS'] . '">Resubmit</a>';
                            echo '</td>';
                        }
                        echo "</tr>";
                        // Close the table
                        //echo "</table>";
                    }

                    echo "</table>";
//                    oci_commit($dbconn);
                    // Commit transaction
//                    $committed = oci_commit($dbconn);
//                    // Test whether commit was successful. If error occurred, return error message
//                    if (!$committed) {
//                        $error = oci_error($conn);
//                        echo 'Commit failed. Oracle reports: ' . $error['message'];
//                    }
                    oci_free_statement($stid);

                    oci_close($dbconn);
                    //oci_commit($dbconn);
                }
//                $tables = array("PP_PRE2HYB_REQUESTS");
//                $columns = array("PP_MSISDN", "PP_DEPOSIT_AMOUNT", "PP_PROCESS_STATUS", "PP_REQUEST_DATE", "PP_ORDER_TYPE","PP_ORDER_PROFILE","PP_RESUBMITTED_BY", "Resubmit");
//                $titles = array("MSISDN", "DEPOSIT AMOUNT", "PROCESS STATUS","REQUEST_DATE", "ORDER TYPE", "ORDER PROFILE", "RESUBMITTED BY", "Resubmit");
//                $advanced = "where SUBSTR(PP_MSISDN,-9) = '" . substr($_POST['msisdn'], -9) . "'";
//                $display_str = $db->display_records($tables, $columns, $titles, "advantage_plus", $advanced, null, null, "");
//                //$db->edit_displayed_records($tables, $columns);
//                echo $display_str;
                ?>
            </form>





            <?php
        }
        echo $_GET['success'];
        ?>
    </div>

    <!-- insert the footer -->
    <?php
    insert_footer2();
    ?>
