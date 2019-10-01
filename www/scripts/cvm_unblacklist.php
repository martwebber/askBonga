<?php
require_once "../util/functions.php";

$error = "";
$oprErr = "";

insert_header2();

function conn(){
	$BONGA_DB_TYPE = "oracle";
	$BONGA_DATABASEHOST = 'svthk1-scan';
	$BONGA_DATABASEPORT = "1521";
	$BONGA_DATABASEUSER = "cokepromo";
	$BONGA_DATABASEPASSWORD = "C0k#pr0mo";
	$BONGA_DATABASENAME = "EIRSB";
		
	$conn = oci_connect($BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASEHOST . ":" . $BONGA_DATABASEPORT . "/" . $BONGA_DATABASENAME);
    return $conn;
}
						
if (isset($_POST['msisdn'])) {
    $validator = new Validate();
    if (!$validator->is_valid_msisdn($_POST['msisdn'])) {
        $error = "<br />Please ensure 'MSISDN' is valid phone number, e.g. 0722123456";
    }
		
	if(empty($_POST["reason"])){
		$error = "<br />Please provide a reason for this action";
	} 
}

if( isset($_POST['msisdnT'])) {
	//Unblacklist User
				$dbconn = conn();

                if (!$dbconn) {
                    echo "Not connnected";
                } else {
					
					$msisdn = $_POST['msisdnT'];

                    $opr = '1';
					//UNBlacklist Operation as defined in the SP
					
					$rsn = '';
				
					$sql = 'call PR_PARTNER_BLACKLIST(:V_MSISDN, :V_BLCKLST_OPR, :V_REASON, :V_CODE, :V_MSG)';

                    $stid = oci_parse($dbconn, $sql);

                    oci_bind_by_name($stid, ':V_MSISDN', $msisdn, 20);
                    oci_bind_by_name($stid, ':V_BLCKLST_OPR', $opr, 20);
                    oci_bind_by_name($stid, ':V_REASON', $rsn, 20);
                    oci_bind_by_name($stid, ':V_CODE', $respCode, 20);
                    oci_bind_by_name($stid, ':V_MSG', $respMsg, 120);

                    oci_execute($stid);
//                    $columns = array("IMEI", "STATUS", "CHANNEL", "RETRIEVED_DATE", "MSISDN", "RESOURCES");
//                    $titles = array("SERIAL", "BUNDLE STATUS", "CHANNEL", "DATE REDEEMED", "MSISDN", "RESOURCES");
//                    $display_str = $db->display_records($columns, $titles, "dsa_details", null, null, "");
//                    echo $display_str;

					
					$msisdn = "";

                    $opr = "";
					
					$rsn = "";

                    oci_free_statement($stid);

                    oci_close($dbconn);
					
					
					 echo "<table class='tablebody border' width='100%'>";
                    echo "<tr>";
                    echo "<th>Response</th>";
                    echo "<tr>";

                    // Output a row
                    echo "<tr>";
                    echo "<td>$respMsg</td>";
                    echo "</tr>";
                    // Close the table
                    echo "</table>";
					
					


					
					
					
				}		
}

function redirect($url) {
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
}

function fetchBlacklistReasons(){
	$conn = conn();

	if (!$conn) {
		echo "Not connnected";
	} 
	
		
	$stid = oci_parse($conn, 'SELECT * FROM TBL_PR_BL_REASONS');
	oci_execute($stid);
    return $stid;
}


//initialise database object   
/* $BONGA_DB_TYPE = "oracle";
  $BONGA_DATABASEHOST = "172.28.220.7";
  $BONGA_DATABASEPORT = "1521";
  $BONGA_DATABASEUSER = "TIBCOEHF";
  $BONGA_DATABASEPASSWORD = "TIBCOEHF";
  $BONGA_DATABASENAME = "TIBCODB"; */
  
 

$db = new Database($BONGA_DB_TYPE, $BONGA_DATABASEHOST, $BONGA_DATABASEPORT, $BONGA_DATABASEUSER, $BONGA_DATABASEPASSWORD, $BONGA_DATABASENAME);

/* if (isset($_POST['confirm_deletion']) && $_POST['confirm_deletion'] != "") {
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
  } */
?>

<!DOCTYPE html>
<html lang="en">
    <head>
 </head>
    <body class="">

<div class="cspacer">
    <?php
    if ($error != "") {
        echo "<div style='text-align: left; width: 80%'>\r\n";
        echo "<table class='tablebody border' width='100%'>\r\n";
        echo "<th class='tableheader'>MESSAGE</th>\r\n";
        echo "<tr><td>\r\n";
        echo "<p class='error'>" . $error . "</p>";
        echo "</td></tr>\r\n";
        echo "</table>\r\n";
        echo "<br /><br />\r\n";
        echo "</div>\r\n";
    }
    ?>

<style>
* {
  box-sizing: border-box;
}

#myInput {
  background-image: url('/css/searchicon.png');
  background-position: 10px 10px;
  background-repeat: no-repeat;
  width: 100%;
  font-size: 12px;
  padding: 6px 10px 6px 20px;
  border: 1px solid #ddd;
  margin-bottom: 6px;
}

#myTable {
  border-collapse: collapse;
  width: 80%;
  border: 1px solid #ddd;
  font-size: 12px;
}

#myTable th, #myTable td {
  text-align: left;
  padding: 2px;
}

#myTable tr {
  border-bottom: 1px solid #ddd;
}

#myTable tr.header, #myTable tr:hover {
  background-color: #f1f1f1;
}
</style>
</head>
<body>

 <br />
        <p style="font:Arial, Helvetica, sans-serif; font-size:12px; color:#FF3300; padding: 5px;">CVM Remove from Blacklist</p>
        <br />

<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search partner ..." title="Type in a name">

			<?php
            
					
				$conn = conn();

                if (!$conn) {
                    echo "Not connnected";
                } else {
					
					$is_bl = 1;
										
					$stid = oci_parse($conn, "SELECT PARTNER_MSISDN, CASE 
											  WHEN P_TYPE=2 then 'DEALER' 
											  WHEN P_TYPE=1 then 'AGENT'
											  ELSE 'Other'
											  END AS PARTNER_TYPE, BLACKLIST_REASON FROM TBL_PR_PARTNERS WHERE IS_BLACKLISTED='0'");
					oci_execute($stid);
					 
					
				
						
						print"<h2 id='title'>Partner Details </h2>";
						print"<br />";
						print"<br />";
						print"<table id='myTable' class='tablebody border' width='80%'>";
						print"<tr><th>PARTNER MSISDN</th><th>PARTNER TYPE</th><th>BLACKLIST REASON</th><th>Unblacklist</tr>";
					
					//<td><a href='records.php?id=" . $row[0] . "'>Unblacklist</a></td>
						while ($row=oci_fetch_row($stid)) {
							
							print"
							<tr>
							<td>$row[0]</td>
							<td>$row[1]</td>
							<td>$row[2]</td>
							
							<td><form method='post' action='' onsubmit='return validate($row[0]);'><input type='text' name='msisdnT' value='".$row[0]."' hidden /><input type='submit' name='unblacklist'/></form></>							
							</tr>";
							
						}
						print"</table></br>"; 	
					oci_free_statement($stid);
					oci_close($conn);

				}		
							
                ?>








<script>
function myFunction() {
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value;
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

function validate(msisdn) {
        return confirm("Are you sure you want to unblacklist " +msisdn +"?");
  
}
</script>
</body>
</html>

