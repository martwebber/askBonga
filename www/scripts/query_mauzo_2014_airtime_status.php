<?php
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

    $BONGA_DB_TYPE = "oracle";
    $BONGA_DATABASEHOST = "172.29.225.1";
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "mauzo";
    $BONGA_DATABASEPASSWORD = "mauzo_12##";
    $BONGA_DATABASENAME = "eirdb";


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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">Mauzo Airtime Award Status Details</p>
        <br />
        <form name='winnings_list' action='query_mauzo_2014_airtime_status.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
			<tr>
				<th colspan="2" class="tableheader"><b>MSISDN SEARCH</b></th>
			</tr>
			<tr>
				<td><br />MSISDN: </td><td><br /><input type='text' name='msisdn' />&nbsp;&nbsp; (Format: 07XXYYYZZZ) </td>
			</tr>
            <tr>
                <td colspan="2"><br /><input type='submit' name='sub_msisdn' /></td>
            </tr>
		</table>
        </center>
	 
        <?php
            if (isset($_POST['msisdn']) && $error == "") {
        ?>
			<input type="hidden" name="confirm_deletion" id="confirm_deletion" />
			<!--<input type="hidden" name="msisdn" id="msisdn" value="<?php echo $_POST['msisdn']; ?>" />-->
			<?php
			  $tables = array("VW_MZ_WINNERS");
                                $columns = array("msisdn", "prize_name","airtime_award_status", "airtime_award_date");
								$titles = array("MSISDN", "Prize","Airtime Status", "Airtime Award Date");
								$advanced = "where substr(msisdn,-9) = ".substr($_POST['msisdn'],-9)." and Confirmed='YES' and prize_id=7";
                                $display_str = $db->display_records($tables, $columns, $titles, "winnings_list", $advanced, null, null, "");
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
?>
