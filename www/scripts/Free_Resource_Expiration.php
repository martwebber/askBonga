<?php
require_once "../util/functions.php";

$error = "";

insert_header2();

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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">EXPIRE FREE RESOURCES</p>
        <br /> 
        <form name='Free_Resource_Expiration.php' action='Free_Resource_Expiration.php' method='post'>
            <center>
                <table class="tablebody border" width="80%">
                    <tr>
                        <th colspan="2" class="tableheader"><b>EXPIRE FREE RESOURCES</b></th>
                    </tr>
                    <tr>
                        <td><br />IMEI: </td><td><br /><input type='text' name='imei' placeholder="Enter IMEI" /></td>
                    </tr>
                    <tr>
                        <td><br />REASON:</td><td><br />
                            <select name="REASON_TYPE">
                                <option value="">--select--</option>
                                <option value="Vendor Authorized Swap">Vendor Authorized Swap</option>
                                <option value="Out of Box Failure">Out of Box Failure</option>
                                <option value="Delayed Repair Swap">Delayed Repair Swap</option>
                            </select>
                            <?php
                            if (!isset($_POST['REASON_TYPE'])) {

//                                $errorMessage = "<li>You forgot to select your business type!</li>";
//                                echo $errorMessage;
                            } else {
                                $reasontype = $_POST['REASON_TYPE'];
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
            if (isset($_POST['REASON_TYPE'])) {
                ?>
<!--                <input type="hidden" name="confirm_deletion" id="confirm_deletion" />-->
				<!--<input type="hidden" name="msisdn" id="msisdn" value="<?php echo $_POST['msisdn']; ?>" />-->
		<?php              
               # $dbconn = oci_connect("frrsrs", 'Tunukiwa123#', "172.29.226.31:1531/ERPPROD");
				$dbconn = oci_connect("frrsrs", 'Tunukiwa123#', "172.25.241.6:1531/ERPPROD");
				
                if (!$dbconn) {
                    echo "Not connnected";
                } else {

                    //echo "Connected";
                    //msisdn
                    $imei = $_POST['imei'];
                    $reason_type = $_POST['REASON_TYPE'];
				//	$SESSION_ID=$_SESSION['USERID'];  echo $SESSION_ID;
                    
                if($imei!='' && $reason_type!=''){
                        
                    // $msisdn = substr($msisdn, -9);
                    $sql = "UPDATE APPS.XXSFC_FR_WHITELIST_TBL SET IMEI_STATUS = '1', LAST_UPDATED_BY = '$SESSION_ID', EXPIRE_REASON = '$reason_type' WHERE IMEI = '$imei'";
                    $stid = oci_parse($dbconn, $sql);
                    oci_execute($stid);
                    oci_commit($dbconn);

                    oci_free_statement($stid);

                    oci_close($dbconn);
                     //echo 'Resource Expiration Successful';
                      echo '<i style="color:green;margin-left: 60px;font-size:30px;font-family:calibri ;">
						Resource Expiration Successful!</i> ';
                }
                else{
                        
	//             echo 'IMEI should not be empty';
					echo '<i style="color:red;font-size:30px;margin-left: 60px;font-family:calibri ;">
					Fields should not be empty! </i> ';
                }

                    //oci_commit($dbconn);
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
    ?>
