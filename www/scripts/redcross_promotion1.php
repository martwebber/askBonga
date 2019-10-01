<?php session_start();
ob_start();
        require_once "../util/functions.php";

        $error = "";

        insert_header2();

        //initialise database object
    $BONGA_DB_TYPE = "oracle";
	//$BONGA_DATABASEHOST = "172.28.226.18";
    //$BONGA_DATABASEHOST = "172.28.226.33";
	//$BONGA_DATABASEHOST = "172.29.226.12";
	$BONGA_DATABASEHOST = "172.29.221.151";
    $BONGA_DATABASEPORT = "1521";
	$BONGA_DATABASEUSER = "TIBCOEHF";
	$BONGA_DATABASEPASSWORD = "goodmonger123";
    $BONGA_DATABASENAME = "TIBCODB";
	
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
        <p style="font:Arial, Helvetica, sans-serif; font-size:16px; color:#FF3300; padding: 5px;">List of successful Downloads</p>
        <br />
  
        <form name='points_list' action='redcross_promotion1.php' method='post'>
            <br /><br />

                        <input type="hidden" name="confirm_deletion" id="confirm_deletion" />
                        <!--<input type="hidden" name="msisdn" id="msisdn" value="<?php echo $_POST['msisdn']; ?>" />-->
                        <?php
                                $tables = array("BUNDLES_PROMO_LOGS");
                                $columns = array("CUSTOMEMSISDN", "RESOURCEAWARDEDMBS", "to_char(AWARDTIME, 'dd-MON-yyyy hh24:mi')","RESPONSEMSG");
                $titles = array("CUSTOMEMSISDN", "RESOURCE AWARDED-MBS","AWARDDATE","RESPONSEMSG");
                $advanced = "where RESPONSECODE = 4000 ORDER BY AWARDTIME DESC";
                                $display_str = $db->display_records($tables, $columns, $titles, "points_list", $advanced, null, null, "");
                                //$db->edit_displayed_records($tables, $columns);
                                echo $display_str;
                        ?>
                </form>

</div>

<!-- insert the footer -->
<?php
        insert_footer2();
ob_end_flush();
?>