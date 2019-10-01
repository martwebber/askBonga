<?php
require_once "../util/functions.php";

$error = "";
$oprErr = "";

insert_header2();

function conn(){
	$BONGA_DB_TYPE = "oracle";
	$BONGA_DATABASEHOST = 'svthk1-scan';
	$BONGA_DATABASEPORT = "1521";
	$BONGA_DATABASEUSER = "cokepromo";
	$BONGA_DATABASEPASSWORD = "C0k#pr0mo";
	$BONGA_DATABASENAME = "EIRSB";
		
	$conn = oci_connect($BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASEHOST . ":" . $BONGA_DATABASEPORT . "/" . $BONGA_DATABASENAME);
    return $conn;
}

//$dbconn = oci_connect($BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASEHOST . ":" . $BONGA_DATABASEPORT . "/" . $BONGA_DATABASENAME);


if (isset($_POST['msisdn'])) {
    $validator = new Validate();
    if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
        $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
    }
		
	if(empty($_POST["reason"])){
		$error = "<br />Please provide a reason for this action";
	} 
}

function fetchBlacklistReasons(){
	$conn = conn();

	if (!$conn) {
		echo "Not connnected";
	} 
			
	$stid = oci_parse($conn, 'SELECT * FROM TBL_PR_BL_REASONS');
	oci_execute($stid);
    return $stid;
}





/* if (isset($_POST['confirm_deletion']) && $_POST['confirm_deletion'] != "") {
  $array_confirmed = split(";", $_POST['confirm_deletion']);
  $db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
  for ($i = 0; $i < count($array_confirmed); $i++) {
  $query = "DELETE FROM user_list WHERE ID = ";
  if ($array_confirmed[$i] != null) {
  $query .= $array_confirmed[$i];
  $db->generic_sql($query);
  logmessage("INFO", "USER MANAGEMENT - USER: ".$_SESSION["USERID"]."; user account with ID: $array_confirmed[$i] deleted");
  }
  }
  } */
?>
<!DOCTYPE html>
<html lang="en">
    <head>
 </head>
    <body class="">

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
            <!--<table class="tablebody border" width="100%">
                    <tr>
                            <th colspan="2" class="tableheader"><b>USER MANAGEMENT</b></th>
                    </tr>
                    <tr>
                            <td><a href="user_registration.php">Add Record</a></td><td><a href="#" onclick="javascript: confirm_deletion('user_list');">Delete Record(s)</a></td>
                    </tr>
            </table>-->
        <br />
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">CVM Blacklist</p>
        <br />
        <form name='query_points' action='cvm_blacklist.php' method='post'>
            <center>
                <table class="tablebody border" width="80%">
                    <tr>
                        <th colspan="2" class="tableheader"><b>MSISDN SEARCH</b></th>
                    </tr>
										
                    <tr>
                        <td><br />MSISDN: </td><td><br /><input type='text' name='msisdn' value='<?php echo $_POST['msisdn'] ?>' />&nbsp;&nbsp; (Format: 7XXYYYZZZ) 
                    </tr>
					<tr>
                        <td><br />REASON: </td><td><br />
						
                            <select name="reason"  >

							<option value="">Select Reason<option>
							<?php
                                $stid=fetchBlacklistReasons();
                                while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)){									
                                    echo "<option value=".$row['REASON'].">".$row['REASON']."</option>";
                                }
                            ?>
							
						</select>
                    </tr>					
                    <tr>
                        <td colspan="2"><br /><input type='submit' name='sub_blacklist' /></td>
                    </tr>
                </table> 
            </center>
			
	

             <?php
            if (isset($_POST['sub_blacklist']) && $error == ""  ) {
				
					//$dbconn = new Database($BONGA_DB_TYPE, $BONGA_DATABASEHOST, $BONGA_DATABASEPORT, $BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASENAME);
					//$dbconn->open_connection();
					
				$dbconn = conn();

                if (!$dbconn) {
                    echo "Not connnected";
                } else {
					
					$msisdn = $_POST['msisdn'];

                    $opr = '2';
					//Blacklist Operation as defined in the SP
					
					$rsn = $_POST['reason'];
				
					$sql = 'call PR_PARTNER_BLACKLIST(:V_MSISDN, :V_BLCKLST_OPR, :V_REASON, :V_CODE, :V_MSG)';

                    $stid = oci_parse($dbconn, $sql);

                    oci_bind_by_name($stid, ':V_MSISDN', $msisdn, 20);
                    oci_bind_by_name($stid, ':V_BLCKLST_OPR', $opr, 20);
                    oci_bind_by_name($stid, ':V_REASON', $rsn, 20);
                    oci_bind_by_name($stid, ':V_CODE', $respCode, 20);
                    oci_bind_by_name($stid, ':V_MSG', $respMsg, 120);

                    oci_execute($stid);
//                    $columns = array("IMEI", "STATUS", "CHANNEL", "RETRIEVED_DATE", "MSISDN", "RESOURCES");
//                    $titles = array("SERIAL", "BUNDLE STATUS", "CHANNEL", "DATE REDEEMED", "MSISDN", "RESOURCES");
//                    $display_str = $db->display_records($columns, $titles, "dsa_details", null, null, "");
//                    echo $display_str;


					$msisdn = "";

                    $opr = "";
					
					$rsn = "";

                    oci_free_statement($stid);

                    oci_close($dbconn);
					
					echo "<table class='tablebody border' width='100%'>";
                    echo "<tr>";
                    echo "<th>Response</th>";
                    echo "<tr>";

                    // Output a row
                    echo "<tr>";
                    echo "<td>$respMsg</td>";
                    echo "</tr>";
                    // Close the table
                    echo "</table>";
				}		
							
                ?>

            </form>
            <?php
        } else {
			$error = "Operation Required";
		}
        ?>
		
		
		
    </div>
	
	

	
	
	
    <!-- insert the footer -->
    <?php
    insert_footer2();
    ?>
	
    </body>
</html>
	
	
