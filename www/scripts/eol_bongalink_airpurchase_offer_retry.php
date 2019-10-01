<style>
.success_text{ font-size: 15px;	color: green; }
.not_found_text{ font-size: 15px; color: red; }
.re-submit_text { font-size: 15px; color: brown; }

.show-text{display:block;}
.hide-text{display:none;}
</style>

<?php
	require_once "../util/functions.php";

	$error = "";
	$exec_code = 0;

	insert_header2();

    // if (isset($_POST['msisdn'])) {
        // $validator =  new Validate();
        // if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
            // $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
        // }
    // }

	//initialise database object
	$BONGA_DB_TYPE = "oracle";
	$BONGA_DATABASEHOST = "svdt1-scan";
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "BI_CVM";
    $BONGA_DATABASEPASSWORD = "c0mp0ign12";
    $BONGA_DATABASENAME = "flexsb";
	
	/*UAT*/
	/*
	$BONGA_DB_TYPE = "oracle";
	$BONGA_DATABASEHOST = "172.29.127.43";
	$BONGA_DATABASEPORT = "1521";
	$BONGA_DATABASEUSER = "CVM_CAMPAIGN";
	$BONGA_DATABASEPASSWORD = "efguru123";
	$BONGA_DATABASENAME = "flextest";
	*/
	

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
<br/>
<p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">View Tunukiwa Offers</p>
<br />

<!--<form name='query_points' action='eol_bongalink_airpurchase_offer_retry.php' method='post'>-->
<form name='query_points' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method='post'>
	<center>
	<table class="tablebody border" width="80%">
		<tr>
			<th colspan="4" class="tableheader"><b>TRANSACTION SEARCH/RESUBMISSION</b></th>
		</tr>
		<tr>						
			<td><br />Tunukiwa Serial: </td><td><br /><input type='text' name='tunus_serial' value='<?php echo $_POST['tunus_serial']?>' required />&nbsp;&nbsp; <br/>(Ex: EO1_703764251201808150307) 
			<td><br />SR Number: </td><td><br /><input type='text' name='sr_number' value='<?php echo $_POST['sr_number']?>' required />&nbsp;&nbsp; <br/>(Ex: SR-TEST-001) 
		</tr>			
		<tr>
			<td colspan="2"><br />
				<button name="sub_msisdn" type="submit" value="sub_msisdn">Query Status</button>
			</td>
		</tr>
	<!--</table> 
	</center>			-->
		
	<?php
	if (isset($_POST['sub_msisdn'])) {
		if (isset($_POST['tunus_serial']) && $error == "") {
			
			$error = $db->open_connection();
			$query = 'BEGIN cvm_campaign.EO_BNGALNK_AIR_AWRD_RTRY(:PARAM1, :PARAM2, :PARAM3, :PARAM4, :PARAM5, :PARAM6, :PARAM7); END;';
			$stmt = ociparse ($db->link, $query);
			
			//bind variables
			$tunus_serial = ($_POST['tunus_serial']);
			$sr_number = ($_POST['sr_number']);
			$username = gethostbyaddr($_SERVER['REMOTE_ADDR']);
			$rqs_stage = 1;
				
			ocibindbyname($stmt,':PARAM1', $tunus_serial,32);
			ocibindbyname($stmt,':PARAM2', $sr_number, 32);
			ocibindbyname($stmt,':PARAM3', $username, 32);
			ocibindbyname($stmt,':PARAM4', $rqs_stage, 32,SQLT_CHR);
			ocibindbyname($stmt,':PARAM5', $v_code, 32,SQLT_CHR);
			ocibindbyname($stmt,':PARAM6', $v_resp_code, 32,SQLT_CHR);
			ocibindbyname($stmt,':PARAM7', $v_msg, 100,SQLT_CHR);
			
			if (ociexecute($stmt)){
				//echo($v_code." :: ".$v_resp_code." :: ".$v_msg);
				$exec_code = 1;
				
				if ($v_resp_code == 1){
					//Processed trx
					echo "<tr><td colspan=\"4\" class=\"success_text\"><br/>$v_msg</td></tr></table></center>";
				
				} else if ($v_resp_code == 99) {
					echo "<tr><td colspan=\"4\" class=\"not_found_text\"><br/>$v_msg</td></tr></table></center>";
					
				} else if ($v_resp_code == 11) {
					$exec_code = 2;
					echo "<tr><td colspan=\"4\" class=\"re-submit_text\"><br/>$v_msg</td></tr>";
	?>
		<tr>
			<td colspan="4"><br />
				<!--<input type='submit' name='retry_trx' value="Resubmit" />-->
				<button name="retry_trx" type="submit" value="Resubmit">Re-Submit</button>
			</td>
		</tr>
	</table> 
	</center>
	<?php					
				}
				
			} else {
				echo "Failed";
			}
			
			ocifreestatement($stmt);
			//ociclose($conn);
		}
	}
	
	if (isset($_POST['retry_trx'])) {
        if (isset($_POST['tunus_serial']) && $error == "") {
			
			$error = $db->open_connection();
			
			$query = 'BEGIN cvm_campaign.EO_BNGALNK_AIR_AWRD_RTRY(:PARAM1, :PARAM2, :PARAM3, :PARAM4, :PARAM5, :PARAM6, :PARAM7); END;';
			$stmt = ociparse ($db->link, $query);
			
			//bind variables
			$tunus_serial = ($_POST['tunus_serial']);
			$sr_number = ($_POST['sr_number']);
			$username = gethostbyaddr($_SERVER['REMOTE_ADDR']);
			$rqs_stage = 2;
				
			ocibindbyname($stmt,':PARAM1', $tunus_serial,32);
			ocibindbyname($stmt,':PARAM2', $sr_number, 32);
			ocibindbyname($stmt,':PARAM3', $username, 32);
			ocibindbyname($stmt,':PARAM4', $rqs_stage, 32);
			ocibindbyname($stmt,':PARAM5', $v_code, 32,SQLT_CHR);
			ocibindbyname($stmt,':PARAM6', $v_resp_code, 32,SQLT_CHR);
			ocibindbyname($stmt,':PARAM7', $v_msg, 100,SQLT_CHR);
			
			if (ociexecute($stmt)){
				//echo($v_code." :: ".$v_resp_code." :: ".$v_msg);
				$exec_code = 1;
				
				if ($v_resp_code == 55){
					//successfully resubmitted
					echo "<tr><td colspan=\"4\" class=\"success_text\"><br/>$v_msg</td></tr></table></center>";
				
				} else {
					$exec_code = 2;
				}
			}
		}
    }
	?>
</form>
       
</div>
</div>

<!-- insert the footer -->
<?php
	insert_footer2();
?>