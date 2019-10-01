<?php
        require_once "../util/functions.php";

        $error = "";

        insert_headerfoundation();

    if (isset($_POST['msisdn'])) {
        $validator =  new Validate();
        if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
            $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
        }
    }

        //initialise database object
    $BUNDLES_DB_TYPE = "oracle";
    $BUNDLES_DATABASEHOST = "10.65.12.7";
    $BUNDLES_DATABASEPORT = "1521";
    $BUNDLES_DATABASEUSER = "mpesa";
    $BUNDLES_DATABASEPASSWORD = "m\$nutd19";
    $BUNDLES_DATABASENAME = "mpesa";

        $db = new Database($BUNDLES_DB_TYPE, $BUNDLES_DATABASEHOST, $BUNDLES_DATABASEPORT, $BUNDLES_DATABASEUSER, $BUNDLES_DATABASEPASSWORD, $BUNDLES_DATABASENAME);

        /*if (isset($_POST['confirm_deletion']) && $_POST['confirm_deletion'] != "") {
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
        }*/
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
        <form name='acc_history_list' action='check_foundation_promo_points.php' method='post'>
        <center>
			<table class="tablebody border" width="80%">
				<tr>
						<th colspan="2" class="tableheader"><b>Safaricom Foundation Promo</b></th>
				</tr>
				<tr>
					<td><br />MSISDN:</td><td><br /><input type='text' name='msisdn' value='<?php if (isset($_POST['msisdn'])) {echo $_POST['msisdn'];} ?>' /></td>
				</tr>
				<tr>
					<td><br />Staff No.:</td><td><br /><input type='text' name='account' value='<?php if (isset($_POST['account'])) {echo $_POST['account'];} ?>' /></td>
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
            <?php
							
				$tables = array("entries_points a");
												
			
				$columns = array("msisdn", "account", "points","left_over","to_char(last_updated, 'dd-MON-yyyy hh24:mi')");
				
				$titles = array("msisdn", "Staff No.", "Points", "Left Over", "Last Updated Date");

			        #$advanced = "where msisdn =254".substr($_POST['msisdn'], -9)." and account = '".$_POST['account']."' order by msisdn desc";
			
				$advanced = "where msisdn =254".substr($_POST['msisdn'], -9)." and account = upper(regexp_replace('".$_POST['account']."', '[[:punct:]]|[[:space:]]|[[:blank:]]|[[:cntrl:]]|(\^.)', '')) order by msisdn desc";

				$display_str = $db->display_records($tables, $columns, $titles, "acc_history_list", $advanced, null, null,null);
												
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
