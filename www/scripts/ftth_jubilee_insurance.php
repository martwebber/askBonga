<?php
        require_once "../util/functions.php";

        $error = "";

        insert_header2();

  /*  if (isset($_POST['msisdn'])) {
        $validator =  new Validate();
        if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
            $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
        }
    }
	*/

        //initialise database object
		$BONGA_DB_TYPE = "oracle";
		$BONGA_DATABASEHOST = 'svthk1-scan';
		$BONGA_DATABASEPORT = "1521";
		$BONGA_DATABASEUSER = "ftth_eir";
		$BONGA_DATABASEPASSWORD = "Policy#123";
		$BONGA_DATABASENAME = "EIRSB";
	    $db = new Database($BONGA_DB_TYPE, $BONGA_DATABASEHOST, $BONGA_DATABASEPORT, $BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASENAME);

       
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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">FTTH JUBILEE INSURANCE</p>
        <br />
  
        <form name='points_list' action='ftth_jubilee_insurance.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
                        <tr>
                                <th colspan="2" class="tableheader"><b>MSISDN OR CI SEARCH</b></th>
                        </tr>
                        <tr>
                                <td><br />MSISDN OR CI (Hint : 07xxxxxxxx OR 11xxxxxx ):</td><td><br /><input type='text' name='msisdn' value='<?php echo $_POST['msisdn']; ?>'/></td>
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
                        <input type="hidden" name="confirm_deletion" id="confirm_deletion"/>
                        <?php
								$tables = array("VW_FTTH_HOME_INSURANCE");
                                				$columns = array("CUSTOMER_NAME","SPONSOR_MSISDN","CI","POLICY_NUMBER","STATUS","AMOUNT","MPESA_TRXN","TRANSACTION_DATE");
								$titles = array("CUSTOMER NAME","MSISDN","FTTH ACCOUNT","POLICY NUMBER","STATUS","AMOUNT","MPESA TRANSACTION","TRANSACTION DATE");
								$advanced =  " WHERE SPONSOR_MSISDN = '".strtoupper(trim($_POST['msisdn']))."' OR CI = '".strtoupper(trim($_POST['msisdn']))."' ";
								$display_str = $db->display_records($tables, $columns, $titles, "transaction_details", $advanced, null, null, "");
                                echo $display_str;
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

