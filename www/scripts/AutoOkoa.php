<?php session_start();
ob_start();
//Query all the entries to the CHANUA BIZ for a specific MSISDN

require_once "../util/functions.php";

$error = "";

insert_header2();

if (isset($_POST['msisdn'])) {
    $validator = new Validate();
    if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
        $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
    }
}

/*
 * 0 Normal
  1: AUTO OKOA
  2: LIPA MDOGO MDOGO
 */
$conn = oci_connect("OKOA", "mykopwd123", "172.29.226.17:1521/FLEXSB");
if (!$conn) {
    $m = oci_error();
    trigger_error(htmlentities($m['message']), E_USER_ERROR);
    echo "Not connnected";
}

//initialise database object
//$FLEXSB_DB_TYPE = "oracle";
//$FLEXSB_DATABASEHOST = "172.29.226.17";
//$FLEXSB_DATABASEPORT = "1521";
//$FLEXSB_DATABASEUSER = "OKOA";
//$FLEXSB_DATABASEPASSWORD = "mykopwd123";
//$FLEXSB_DATABASENAME = "FLEXSB";
//$db = new Database($FLEXSB_DB_TYPE, $FLEXSB_DATABASEHOST, $FLEXSB_DATABASEPORT, $FLEXSB_DATABASEUSER, $FLEXSB_DATABASEPASSWORD, $FLEXSB_DATABASENAME);
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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">AutoOkoa/Lipa Mdogo Mdogo(Query History)</p>
        <br />

        <form name='history_list' action='AutoOkoa.php' method='post'>
            <center>
                <table class="tablebody border" width="80%">
                    <tr>
                        <th colspan="2" class="tableheader"><b>MSISDN SEARCH</b></th>
                    </tr>
                    <tr>
                        <td><br />MSISDN:</td><td><br /><input type='text' name='msisdn' value='<?php
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
            <?php
            if (isset($_POST['msisdn']) && $error == "") {
                ?>
                        <!--                <input type="hidden" name="confirm_deletion" id="confirm_deletion"/>-->
                <!--            SELECT * FROM TBL_OKOA_WHITELIST WHERE MSISDN = '".substr($_POST['msisdn'], -9)."'-->
                <?php
				

//                $sql = 'SELECT * FROM TBL_OKOA_WHITELIST WHERE MSISDN = :didbv';
                $sql = 'SELECT  TRX_DATE, MSISDN,PROMO_TYPE FROM TBL_OKOA_WHITELIST WHERE MSISDN = :didbv';
                $stid = oci_parse($conn, $sql);
                $didbv = 60;
                oci_bind_by_name($stid, ':didbv', substr($_POST['msisdn'], -9));
                oci_execute($stid);
				
				
					echo "<table class='tablebody border' width='100%'>";
                    echo "<tr>";
//                    echo "<th>ID</th>";
                    echo "<th>MSISDN</th>";
                    echo "<th>PROMO TYPE</th>";
                    echo "<th>TRANSACTION DATE</th>";
                    echo "<tr>";
                while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS + OCI_ASSOC)) {

                    $typeid = $row['PROMO_TYPE'];
                    $numbs = $row['MSISDN'];
                    $date = $row['TRX_DATE'];
                    $getval = "";
                    if ($typeid == 1) {
                        $getval = "AUTO OKOA";
                    } else if ($typeid == 2) {

                        $getval = "LIPA MDOGO MDOGO";
                    } else if ($typeid == 0) {

                        $getval = "NORMAL OKOA";
                    }
                 

                    // Output a row
                    echo "<tr>";
                    echo "<td>$numbs</td>";
                    echo "<td>$getval</td>";
                    echo "<td>$date</td>";
                    echo "</tr>";
                    // Close the table
                    
//                    echo $row['PROMO_TYPE'] . "<br>\n";
//                    
//                    echo 'Hello';
                }

echo "</table>";


                oci_free_statement($stid);
                oci_close($conn);
//                $tables = array("TBL_OKOA_WHITELIST");
//                $columns = array("TID", "MSISDN", "PROMO_TYPE", "ACTIVE_STATUS");
//                $titles = array("TID", "MSISDN", "PROMO TYPE", "STATUS");
//                $advanced = "WHERE MSISDN = '" . substr($_POST['msisdn'], -9) . "' ";
//                $display_str = $db->display_records($tables, $columns, $titles, "history_list", $advanced, null, null, "6");
//                echo $display_str;
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