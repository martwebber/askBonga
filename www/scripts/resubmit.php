<?php

//$dbconn = oci_connect("TIBCOEHF", 'goodmonger123', "10.184.27.68:1521/TIBCODB"); //THIKA
$dbconn = oci_connect("TIBCOEHF", 'goodmonger123', "172.29.246.68:1521/TIBCODB"); //HQ

if (!$dbconn) {
    echo "Not connnected";
} else {

    //echo "Connected";


//  $process_status = "ERROR";
    $counter = "0";
    //$updated_status = 'NEW_DP';
    $pp_id = $_GET["PP_ID"];
	$pp_user_id = $_GET["USER_ID"];
    $p_status = $_GET["PP_PROCESS_STATUS"];
    $p_status = trim($p_status);
	//echo $pp_user_id;
    $gval = "ERROR";
	//'NEW' || chr(95) || 'DP'

    if (strval($p_status) == $gval) {
       // echo "Maze uko sawa mtu wangu";
       // $SESSION_ID = $_SESSION['USERID'];
        $sql = "UPDATE PP_PRE2HYB_REQUESTS SET PP_PROCESS_STATUS='NEW_DP', PP_BULK_RESUB_COUNTER = '$counter', PP_RESUBMITTED_BY ='$pp_user_id'  WHERE PP_ID = '$pp_id'";
		
        $stid = oci_parse($dbconn, $sql);
        oci_execute($stid);		
        oci_commit($dbconn);	
		

        header("Location: advantage_plus_status_query.php?success=Successfully Resubmitted");
    } else {
//echo "You did not write a";
        header("Location: advantage_plus_status_query.php");
    }
}
?>

