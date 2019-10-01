<?php session_start();
ob_start();
		//Query all the entries to the CHANUA BIZ for a specific MSISDN
		
        require_once "../util/functions.php";

        $error = "";

        insert_header2();

    if (isset($_POST['msisdn'])) {
        $validator =  new Validate();
        if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
            $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
        }
    }

        //initialise database object
		$RLNM_DB_TYPE = "oracle";
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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">CHANUA BIZ(Query Entries)</p>
        <br />
  
        <form name='entries_list' action='RLNM_Entries.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
                        <tr>
                                <th colspan="2" class="tableheader"><b>MSISDN SEARCH</b></th>
                        </tr>
                        <tr>
                                <td><br />MSISDN:</td><td><br /><input type='text' name='msisdn' value='<?php  if (isset($_POST['msisdn'])) {echo $_POST['msisdn'];} ?>'/></td>
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
								$tables = array("TBL_RLNM_ENTRIES");
                                $columns = array("TID","MSISDN","TRX_DATE","PROMO_DATE","MPESA_TRX_ID","AMOUNT","RETAILER_PTS","DSA_PTS");
								$titles = array("ID","MSISDN","TRANSACTION DATE","PROMOTION DATE","MPESA TRANSACTION ID","AMOUNT","RETAILER POINTS","DSA POINTS");
								$advanced = "WHERE MSISDN = '".'254'.substr($_POST['msisdn'],-9)."' ORDER BY TID DESC";
                                $display_str = $db->display_records($tables, $columns, $titles, "entries_list", $advanced, null, null, "8");
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