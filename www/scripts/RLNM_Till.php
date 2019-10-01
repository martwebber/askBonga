<?php session_start();
ob_start();
		//Query all the entries to the Cahnua Biz Campaign for a specific MSISDN
		
        require_once "../util/functions.php";

        $error = "";

        insert_header2();
/**
    if (isset($_POST['msisdn'])) {
        $validator =  new Validate();
        if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
            $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
        }
    }
**/
        //initialise database object
		$RLNM_DB_TYPE = "oracle";
		//$RLNM_DATABASEHOST = "svdt1-scan";
		$RLNM_DATABASEHOST = "172.29.226.17";
		$RLNM_DATABASEPORT = "1521";
		$RLNM_DATABASEUSER = "RLNM";
		$RLNM_DATABASEPASSWORD = "retlnmsaf";
		$RLNM_DATABASENAME = "RETLNM";
	    $db = new Database($RLNM_DB_TYPE, $RLNM_DATABASEHOST, $RLNM_DATABASEPORT, $RLNM_DATABASEUSER, $RLNM_DATABASEPASSWORD, $RLNM_DATABASENAME);

       
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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">CHANUA BIZ(Query Dealer Till)</p>
        <br />
  
        <form name='till_details' action='RLNM_Till.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
                        <tr>
                                <th colspan="2" class="tableheader"><b>DEALER TILL SEARCH:</b></th>
                        </tr>
                        <tr>
                                <td><br />TILL:</td><td><br /><input type='text' name='till' value='<?php  if (isset($_POST['till'])) {echo $_POST['till'];} ?>'/></td>
                        </tr>
            <tr>
                <td colspan="2"><br /><input type='submit' name='sub_msisdn' /></td>
            </tr>
                </table>
        </center>
                <br /><br />
        <?php
            if (isset($_POST['till']) && $error == "") {
        ?>
						<input type="hidden" name="confirm_deletion" id="confirm_deletion"/>
                        <?php
								$tables = array("TBL_RLNM_DSA_LIST");
                                $columns = array("ID","MSISDN","DEALER_CODE","TILL","TILL_MSISDN","REGISTER_DATE","CREDIT_VALUE","DEALER_NAME","DS_ORGA_NAME","REGISTERED");
								$titles = array("ID","MSISDN","DEALER CODE","TILL","TILL MSISDN","WHITELIST/REGISTRATION DATE","CREDIT VALUES","DEALER NAME","DEALER BRANCH","REGISTRATION STATUS");
								$advanced = "WHERE TILL = '".$_POST['till']."' ORDER BY ID DESC";
                                $display_str = $db->display_records($tables, $columns, $titles, "till_details", $advanced, null, null, "");
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
		ob_end_flush();
?>
