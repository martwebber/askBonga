<?php
//Query all the entries to the free resources for a specific MSISDN

require_once "../util/functions.php";

$error = "";

insert_header2();

if (isset($_POST['msisdn'])) {
//    $validator = new Validate();
//    if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
//        $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
//    }
}
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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">FREE RESOURCES (Query Dealer Free Resources)</p>
        <br />

        <form name='fresources_details' action='Free_Resources.php' method='post'>
            <center>
                <table class="tablebody border" width="80%">
                    <tr>
                        <th colspan="2" class="tableheader"><b>IMEI SEARCH</b></th>
                    </tr>
                    <tr>
                        <td><br />IMEI:</td><td><br /><input type='text' name='msisdn' value='<?php
                            if (isset($_POST['msisdn'])) {
                                echo $_POST['msisdn'];
                            }
                            ?>'/></td>
                    </tr>
                    <tr>
                        <td colspan="2"><br /><input type='submit' name='sub_msisdn' /></td>
                    </tr>
                </table>
            </center>
            <br /><br />
<!--            <style>
                table, th {
                    border: 1px solid black;
                    column-span:2px;
                                    }
                table, td {
                    border: 1px solid red;
                }
            </style>-->

            <?php
            if (isset($_POST['msisdn']) && $error == "") {
                ?>
                <input type="hidden" name="confirm_deletion" id="confirm_deletion"/>
                <?php

				
                #$dbconn = oci_connect("frrsrs", 'Tunukiwa123#', "172.29.226.31:1531/ERPPROD");
				$dbconn = oci_connect("frrsrs", 'Tunukiwa123#', "172.25.241.6:1531/ERPPROD");

                if (!$dbconn) {
                    echo "Not connnected";
                } else {
                    //echo "Connected";

                    $msisdn = $_POST['msisdn'];
                    //$msisdn = substr($msisdn,-9);
					//$msisdn = '254'.$msisdn;

                    $imei = $_POST['msisdn'];
					$CHANNELTYPE = "";

                    //Request does not change
                    $sql = 'call APPS.XXSFC_FR_REDEMPTION_PKG.CHECKSTATUS(:V_MSISDN, :V_IMEI, :V_RESPONSE_CODE, :V_RESPONSE_DESC, :V_STATUS, :V_CHANNEL, :V_RETRIEVED_DATE, :V_RESOURCES)';

 
//echo $sql;
                   $stid = oci_parse($dbconn, $sql);

//echo $stid;
                    oci_bind_by_name($stid, ':V_MSISDN', $msisdnsent, 20);
                    oci_bind_by_name($stid, ':V_IMEI', $msisdn, 20);
                    oci_bind_by_name($stid, ':V_RESPONSE_CODE', $respcode, 20);
                    oci_bind_by_name($stid, ':V_RESPONSE_DESC', $respdesc, 20);
                    oci_bind_by_name($stid, ':V_STATUS', $status, 20);
                    oci_bind_by_name($stid, ':V_CHANNEL', $channel, 20);
                    oci_bind_by_name($stid, ':V_RETRIEVED_DATE', $regdate, 100);
                    oci_bind_by_name($stid, ':V_RESOURCES', $resources, 15);

                    oci_execute($stid);
//echo $stid;
					oci_free_statement($stid);
                    oci_close($dbconn);

	// echo $regdate;
	if($channel == "RD"){
	$CHANNELTYPE = "Retail/Dealer";
	}
	else if ($channel == "OM"){
	$CHANNELTYPE = "Open Market";
	}
                    echo "<table class='tablebody border' width='100%'>";
                    echo "<tr>";
                    // echo "<th>Serial</th>";
				    echo "<th>Response Description</th>";
                    echo "<th>Bundle Status</th>";
                    echo "<th>Channel</th>";
                    echo "<th>Date Redeemed</th>";
                    echo "<th>msisdn</th>";
                    echo "<th>Resources</th>";					 
                    echo "<tr>";

                    // Output a row
                    echo "<tr>";
                    //echo "<td>$imei</td>";
					echo "<td>$respdesc</td>";
                    echo "<td>$status</td>";
                    echo "<td>$CHANNELTYPE</td>";
                    echo "<td>$regdate</td>";
                    echo "<td>$msisdnsent</td>";
                    echo "<td>$resources</td>";					
                    echo "</tr>";
					
                    // Close the table
                    echo "</table>";

                }
                ?>
            </form>
            <?php
        }
        ?>
    </div>

<?php
//echo $sql;

//echo $msisdn;
//echo " ".$respcode;
//echo " ".$respcode;
//echo " ".$status;
//echo " ".$channel;
//echo " ".$regdate;
//echo " ". $resources;

?>
    <!-- insert the footer -->
    <?php
    insert_footer2();
    ?>
