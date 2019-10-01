<?php
        include "../util/functions.php";

        $error = "";
        $date = null;
        insert_header2();

        //initialise data array
        if (!isset($_POST['msisdn'])) {
                $array_user_details = array (
                        'msisdn' => array("MSISDN", "", 1, 11),
                        'cos' => array("SUBSCRIBER_TYPE", "", 1, 1),
                        'bundle' => array("BUNDLE", "", 1, 1)
                );
        }
        else {
                $array_user_details = array (
                        'msisdn' => array("MSISDN", $_POST['msisdn'], 1, 11),
                        'cos' => array("SUBSCRIBER_TYPE", $_POST['sub_type'], 1, 14),
                        'sr_number' => array("SR NUMBER", $_POST['sr_number'], 1, 14),
                        'bundle' => array("BUNDLE", $_POST['bundle'], 1, 1)
                );      
        }

        $S_DATABASEHOST = "172.31.88.56";
        $S_DATABASEPORT = "1529";
        $S_DATABASEUSER = "bundles";
        $S_DATABASEPASSWORD = "bundles123";
        $S_DATABASENAME = "PROMODB";
        
        //initialise database object
        $db = new Database($DB_TYPE, $S_DATABASEHOST, $S_DATABASEPORT, $S_DATABASEUSER, $S_DATABASEPASSWORD, $S_DATABASENAME);

        //check the javascript to automatically fill the number of winners textbox
        if (isset($_POST['submit_request']) )
        {
            /*validate the MSISDN
            $validator =  new Validate();
            if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
                $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
            }
		*/
		
            $error = validate($array_user_details);

            if ($error == "") {
                //to obtain the resultset of winners run stored procedure
                $db->open_connection();
                //sms_bundle_subscription_ocs(IN_MSISDN IN NUMBER, IN_BUNDLE_ID IN NUMBER, IN_CHANNEL IN VARCHAR2, IN_SUB_TYPE IN VARCHAR2)
                $query = "BEGIN sms_bundle_subscription_ocs(:IN_MSISDN, :IN_BUNDLE_ID, :IN_CHANNEL, :IN_SUB_TYPE); END;";
                $cursor = ocinewcursor($db->link);
                $stmt = ociparse ($db->link, $query);

                //bind variables
                $in_msisdn = $_POST['msisdn'];
                $in_sr_number = $_POST['sr_number'];
                $in_channel = "CM: ".$_SESSION['USERID']."-".$in_sr_number;
                //$in_message = "10MB";
                $in_bundle_id = $_POST['bundle'];
                $in_sub_type = $_POST['sub_type'];


                logmessage("DEBUG", $in_channel."; ".$in_msisdn."; ".$_POST['sr_number']."; ".$in_bundle_id);
                ocibindbyname($stmt, ":IN_MSISDN", &$in_msisdn, -1);
                ocibindbyname($stmt, ":IN_BUNDLE_ID", &$in_bundle_id, -1);
                ocibindbyname($stmt, ":IN_CHANNEL", &$in_channel, -1);
                ocibindbyname($stmt, ":IN_SUB_TYPE", &$in_sub_type, -1);

                //echo $quantity."<br />";
                //execute our stored procedure
                $result = ociexecute($stmt);

                if ($result) {
                    $error = "Request Successfully Submitted. Please check the 'Query Subscription' link in a few minutes to verify the subscription.";
                }
                else {
                    $error = "Error submitting request. <br />Please contact system administrator";
                }

                $stmt_result = oci_free_statement($stmt);
                logmessage("DEBUG", "OCI_FREE_RESULT: STMT - $stmt_result");
                $db->close_connection();
            }
        }

?>

<div class="cspacer">
        <?php
                if ($error !=  "") {
                        echo "<center>\r\n";
                        echo "<div align='center' style='text-align: left; width: 80%'>\r\n";
                        echo "<table class='tablebody border' width='100%'>\r\n";
                        echo "<th class='tableheader'>MESSAGE</th>\r\n";
                        echo "<tr><td>\r\n";
                        echo "<p class='error'>".$error."</p>";
                        echo "</td></tr>\r\n";
                        echo "</table>\r\n";
                        echo "<br /><br />\r\n";
                        echo "</div>\r\n";
                        echo "</center>\r\n";
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
        <form name='form_subscribe' action='sms_subscribe.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
                        <tr>
                                <th colspan="2" class="tableheader"><b>MASAA YA SMS: MSISDN ENTRY</b></th>
                        </tr>
                        <tr>
                                <td><br />MSISDN:</td><td><br /><input type='text' name='msisdn' /></td>
                        </tr>
                        <tr>
                                <td><br /><br />CLASS OF SERVICE</td><td>
                                <?php                                   
                                        $array_sub_type_details = array ('prepaid|SMSC' => 'prepaid', 'postpaid|SMSC' => 'postpaid');
                                        
                                        if (isset($array_user_details['cos'][1]) && $array_user_details['cos'][1] != "none")
                                                $sub_type = $array_user_details['sub_type'][1];
                                        else
                                                $sub_type = -1;

                                        echo build_combo("sub_type", $array_sub_type_details, "combobox", null, $sub_type); 
                                ?>
                                </td>
                        </tr>                   
                        <tr>
                                <td><br /><br />SMS BUNDLE</td><td>
                                <?php
                                        $array_bundle_details = array ('4' => '20 SMS', '5' => 'UNLIMITED SMS');

                                        if (isset($array_user_details['bundle'][1]) && $array_user_details['bundle'][1] != "none")
                                                $bundle = $array_user_details['bundle'][1];
                                        else
                                                $bundle = -1;

                                        echo build_combo("bundle", $array_bundle_details, "combobox", null, $bundle);
                                ?>
                                </td>
                        </tr>
                        <tr>
                                <td><br />INTERACTION ID / SR NO:</td><td><br /><input type='text' name='sr_number' /></td>
                        </tr>                   
            <tr>
                <td colspan="2"><br /><input type='submit' name='submit_request' value ='Submit Request' /></td>
            </tr>
                </table>
        </center>
        </form>
                <br /><br />

</div>

<!-- insert the footer -->
<?php
        insert_footer2();
?>
