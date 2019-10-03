
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
 	//$BONGA_DATABASEHOST = "172.28.226.18";
     //$BONGA_DATABASEHOST = "172.28.226.33";
	$BONGA_DATABASEHOST = "172.29.226.12";
    $BONGA_DATABASEPORT = "1521";
    $BONGA_DATABASEUSER = "bonyeza_chapa";
    $BONGA_DATABASEPASSWORD = "b0ny3zachapa";
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
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">Shangwe Mtaani 2015 Participant Questions History C10</p>
        <br />
  
        <form name='points_list' action='check_shangwe_qns_history_59242015.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
                        <tr>
                                <th colspan="2" class="tableheader"><b>MSISDN SEARCH</b></th>
                        </tr>
                        <tr>
                                <td><br />MSISDN:</td><td><br /><input type='text' name='msisdn' value='<?php echo $_POST['msisdn']; ?>'/></td>
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
                                /*$tables = array("vw_bo15_question_hist");
                                $columns = array("MSISDN", "ACTION","ACTION_DETAILS", "to_char(trx_date, 'dd-MON-yyyy hh24:mi')");
								$titles = array("MSISDN", "Action","Details", "Date");
								$advanced = "where msisdn = ".substr($_POST['msisdn'],-9)." order by trx_date desc";
                                $display_str = $db->display_records($tables, $columns, $titles, "points_list", $advanced, null, null, "");
                                //$db->edit_displayed_records($tables, $columns);
                                echo $display_str;*/
								
								
								
								$tables = array("vw_latest_qhist");
                                $columns = array("date_asked","date_answered","qns_id","question","correct_answer","subs_answer","remarks");
								$titles = array("date_asked","date_answered","qns","question","ans","subs_ans","remarks");
								$advanced = "where msisdn = ".substr($_POST['msisdn'],-9)." order by qns_id desc";
                                $display_str = $db->display_records($tables, $columns, $titles, "points_list", $advanced, null, null, "");
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
