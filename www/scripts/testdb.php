<?php
	require_once "../util/functions.php";

	$error = "";

	//initialise database object
    $BONGA_DB_TYPE = "oracle";
    $BONGA_DATABASEHOST = "172.31.100.122";
    $BONGA_DATABASEPORT = "1529";
    $BONGA_DATABASEUSER = "mauzo2012";
    $BONGA_DATABASEPASSWORD = "mauzo#2012";
    $BONGA_DATABASENAME = "promo";

	$db = new Database($BONGA_DB_TYPE, $BONGA_DATABASEHOST, $BONGA_DATABASEPORT, $BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASENAME);
	$tables = array("airtime_pins");
	$columns = array("msisdn", "serial_number", "to_char(date_sent, 'dd-MON-yyyy')");
	$titles = array("msisdn", "serial_number", "draw_date");
	$advanced = "where msisdn = 721214848";
	$display_str = $db->display_records($tables, $columns, $titles, "points_list", $advanced, null, null, "");
	//$db->edit_displayed_records($tables, $columns);	
	
?>
