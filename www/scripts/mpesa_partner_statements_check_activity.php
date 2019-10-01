<?php session_start();
ob_start();
        require_once "../util/functions.php";

        $error = "";

				     //first check if user is logged in
     $please_login_url = "please_login.php";
	 $status = check_logged_in($please_login_url);
		
	if ($status == 1) { 
		
        insert_header2();

    /*if (isset($_POST['msisdn'])) {
        $validator =  new Validate();
        if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
            $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
        }
    }*/
	
		$longMSISDN = $_POST['msisdn'];
		
		 if (strlen($_POST['msisdn']) > 9 ){
		 
		 $longMSISDN = substr($longMSISDN,-9);
		 
		 }
		

        //initialise database object
    $BONGA_DB_TYPE = "oracle";
   // $BONGA_DATABASEHOST = "172.29.221.57";
   $BONGA_DATABASEHOST = "10.5.72.82";
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "merchant_statements";
    $BONGA_DATABASEPASSWORD = "l3tm31n#";
    $BONGA_DATABASENAME = "tibcodb";
	
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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">M-PESA Statements - Query Activity</p>
        <br />
  
        <form name='activity_list' action='mpesa_partner_statements_check_activity.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
                        <tr>
                                <th colspan="2" class="tableheader"><b>SEARCH USING STORE NUMBER or MSISDN</b></th>
                        </tr>
                        <tr>
                                <td><br />Enter Number:</td><td><br /><input type='text' name='msisdn' value='<?php echo $_POST['msisdn']; ?>'/></td>
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
                        <input type="hidden" name="confirm_deletion" id="confirm_deletion" />
                        <!--<input type="hidden" name="msisdn" id="msisdn" value="<?php echo $_POST['msisdn']; ?>" />-->
                        <?php
                                $tables = array("vw_stmt_request_history");
                                $columns = array("msisdn", "short_code", "action","action_details", "to_char(action_date, 'dd-MON-yyyy hh24:mi')");
                $titles = array("MSISDN", "Store Number", "Action", "Details","Date");
				  $advanced = "where short_code = $longMSISDN or substr(msisdn,-9) = $longMSISDN";
				  
                //$advanced = "where substr(msisdn,-9) = ".substr($_POST['msisdn'],-9);
				
				
                                $display_str = $db->display_records($tables, $columns, $titles, "activity_list", $advanced, null, null, "");
                                //$db->edit_displayed_records($tables, $columns);
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
		
			
}else{
	
		header("Location: please_login.php");
	}	
	
ob_end_flush();	
?>