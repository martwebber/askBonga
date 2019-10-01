<?php
	require_once "../util/functions.php";

	$error = "";
	$MASONKO_RESPONSE = '';
	$MASONKO_DATA = '';
	insert_header2();

    if (isset($_POST['msisdn'])) {
        $validator =  new Validate();
        if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
            $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
        }
		
		$msisdn = substr($_POST['msisdn'], 1);
		masonko_query($msisdn);
    }	

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
		<!--<table class="tablebody border" width="100%">
			<tr>
				<th colspan="2" class="tableheader"><b>USER MANAGEMENT</b></th>
			</tr>
			<tr>
				<td><a href="user_registration.php">Add Record</a></td><td><a href="#" onclick="javascript: confirm_deletion('user_list');">Delete Record(s)</a></td>
			</tr>
		</table>-->
        <form name='acc_history_list' action='masonko_query.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
			<tr>
				<th colspan="2" class="tableheader"><b>MSISDN SEARCH</b></th>
			</tr>
			<tr>
				<td><br />MSISDN:</td><td><br /><input type='text' name='msisdn' /></td>
			</tr>
            <tr>
                <td colspan="2"><br /><input type='submit' name='sub_msisdn' /></td>
            </tr>
		</table>

		<br /><br />
        <?php
            if (isset($_POST['msisdn']) && $error == "") {
        ?>		
        <table class="tablebody border" width="80%"> 
			<tr> 
				<th>MSISDN</th><th>SUBSCRIBER_TYPE</th><th>POINTS</th><th>LEFT_OVER</th><th>LAST_UPDATED</th>
			</tr> 
			<tr> 
				<td colspan='5'>&nbsp;</td>
			</tr>			
			<tr>
			<?php
				//echo "masonko: $MASONKO_DATA";
				//$MASONKO_DATA="masonko: 721214848_postpaid|SMSC_77_6_10/6/2010 8:55:57_";
				//$masonko_data = split("_", $MASONKO_DATA);
				$masonko_data = split("_", strip_tags($MASONKO_DATA, 'TYPE>'));
				//print_r($masonko_data);
				echo "<td>$masonko_data[0]</td>
				<td>$masonko_data[1]</td>
				<td>$masonko_data[2]</td>
				<td>$masonko_data[3]</td>
				<td>$masonko_data[4]</td>";
			?>
			</tr> 
		</table>
		</form>
		</center>
        <?php
            }
        ?>
</div>

<!-- insert the footer -->
<?php
	insert_footer2();
?>
