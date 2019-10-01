<?php
	require_once "../util/functions.php";

	$error = "";

	insert_header2();

    if (isset($_POST['paybill_name'])) {
        /*$validator =  new Validate();
        if (!$validator->is_valid_msisdn($_POST['paybill_name'])) {
            $error = "<br />Please ensure 'paybill_name' is valid phone number, e.g. 0722123456";
        }*/
    }

	//initialise database object

   
    $BONGA_DB_TYPE = "oracle";
    $BONGA_DATABASEHOST = "172.28.226.35";
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "samsung_promo";
    $BONGA_DATABASEPASSWORD = "samsun8_pt0m0#";
    $BONGA_DATABASENAME = "heko";


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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">Query Blacklisted Pay bill Accounts</p>
        <br />
        <form name='blacklist' action='lnm_2015_blacklisted_c2b.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
			<tr>
				<th colspan="2" class="tableheader"><b>MSISDN SEARCH</b></th>
			</tr>
			<tr>
				<td><br />Enter Paybill Name: </td><td><br /><input type='text' name='paybill_name' />&nbsp;&nbsp; </td>
			</tr>
            <tr>
                <td colspan="2"><br /><input type='submit' name='sub_msisdn' /></td>
            </tr>
		</table>
        </center>
	 
        <?php
            if (isset($_POST['paybill_name']) && $error == "") {
        ?>
			<input type="hidden" name="confirm_deletion" id="confirm_deletion" />
			<!--<input type="hidden" name="msisdn" id="msisdn" value="<?php echo $_POST['paybill_name']; ?>" />-->
			<?php
$tables = array("tbl_lnm_blacklist_c2b");
				$columns = array("store_name");
                $titles = array("Paybill Account Name");
                $advanced = "where lower(store_name) like '%".strtolower($_POST['paybill_name'])."%'";
				$display_str = $db->display_records($tables, $columns, $titles, "blacklist", $advanced, null, null, "");
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
