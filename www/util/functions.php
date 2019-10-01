<?php session_start();
ob_start();
require_once("../util/database.php");
require_once("../util/lms_functions.php");
require_once("../util/validate.php");
require_once('../util/class.phpmailer.php'); //email class
require_once("../html2fpdf/html2fpdf.php"); //pdf generator class

Date_default_timezone_set('Africa/Nairobi');

//---SET DATABASE VARIABLES---------------
/*
$DB_TYPE = "mysql";
$DATABASEHOST = "127.0.0.1";
$DATABASEPORT = "3306";
$DATABASEUSER = "root";
$DATABASEPASSWORD = "root";
$DATABASENAME = "surf2win";


$DB_TYPE = "oracle";
$DATABASEHOST = "10.25.200.106";
$DATABASEPORT = "1521";
$DATABASEUSER = "unlimited_data_r";
$DATABASEPASSWORD = "UnlimitedDATAR99";
$DATABASENAME = "promo106";
*/

$DB_TYPE = "oracle";
#$DATABASEHOST = "172.29.225.4";
$DATABASEHOST = "172.25.241.3";
$DATABASEPORT = "1521";
$DATABASEUSER = "bonyeza_chapa";
$DATABASEPASSWORD = "b0ny3zachapa";
$DATABASENAME = "heko";

//----SET SECURITY POLICY------------------
//default='prohibitive'; also supports 'permissive'
$SECURITY_POLICY = 'prohibitive';
//----SET LOG LEVEL------------------------
//DEBUG(0), INFO(1), WARNING(2), ERROR(3), CRITICAL(4)
//Anything of log level lower than $LOG_LEVEL will not be logged
$LOG_LEVEL = 0;
//----SET SESSION TIMEOUT------------------
//Session timeout - Time in minutes; Setting it to 0 disables it
//Session type - incremental: Refreshes your login info based on activity; validity period = $SESSION_TIMEOUT
//Session type - static: Will log you out after $SESSION_TIMEOUT regardless of activity
$SESSION_INFO_LOCATION='file';
$SESSION_TIMEOUT = 0;
$SESSION_TYPE = 'incremental';
//----SET SMTP SERVER----------------------
//$SMTP_SERVER = "172.31.100.69";
$SMTP_SERVER = "172.29.229.67";
//----SET MD (PREPAY BILLING) CONFIGS-----------
$MD_USERID = 'SFC_WEB';
$MD_PASSWORD = 'webportal';
$MD_TERMINALID = 4002;
//----SET RCI(POSTPAY BILLING) CONFIGS--------
$RCI_USERNAME = 'reserver';
$RCI_PASSWORD = 'Reservation1234';
$RCI_TAX_CODE = 'VAT';
$RCI_EXPIRATION = 86400;
$RCI_SOURCE = 'WEBPORTAL';
$RCI_SERVICE_DESC = 'Mobile Content';
//----VAR TO HOLD OUR BILLING DATA -------------
$BILLING_RESPONSE = '';
//------------------------------------------
//check that someone is logged in before accessing any page, unless of course it's the login page!
$page_name = basename($_SERVER['PHP_SELF']);
$array_globally_accessible = array("login.php", "unauthorized_access.php", "logout.php", "please_login.php", "index.php", "check_history.php", "php_info.php",
	"jaza_pesa_mobil.php",
"forgot_password.php",
"mpesa_partner_statements_requests.php","mpesa_partner_statements_check_activity.php",
"mpesa_partner_statements_check_registration.php",
"mpesa_partner_statements_resend.php", "mpesa_partner_statements_deactivate_sub.php","lnm_sci_query_airtime_status.php","cyp_query_sub.php",
"mpesa_agent_reversal_query.php","showmax_status_query.php","lnm_fa_query_airtime_status.php","lnm_fa_query_customer_history.php","outofbundle.php",
"mpesa_statements_deactivate_sub.php","mpesa_statements_check_sub_registration.php","mpesa_statements_resend_statement.php","cyp_query_history.php","ftth_ussd_requests.php", "Free_Resources.php",
"advantage_plus_status_query.php","kinda_promo.php",
"mpesa_statements_check_sub_requests.php","mpesa_statements_check_sub_activity.php","redcross_promotion.php",
"stori_ibambe_double_bonus.php","ftth_showmax_30daypromo.php",
"eol_cust_offer_purchase_history_v2.php","eol_cust_offer_history_detailed_v2.php","eol_cust_offer_history.php","mpesa_agent_reversal_2530.php","lipa_okoa_mdogo_history.php", "resubmit.php", "AutoOkoa.php",
"happyhour_query_whitelist.php","showmax_promotion.php","lnm_jamboj_query_airtime_status.php","lnm_jamboj_query_cust_history.php",
"lnm_jamboj_query_top100_awards.php","check_unclaimed_assets_status.php","RLNM_DSA.php",
 "RLNM_Entries.php", "RLNM_History.php", "RLNM_Retailer.php","tunukiwa_promo_details_2017.php","eol_bongalink_airpurchase_offer_retry.php",
 "tunukiwa_promo_history_2017.php", "tunukiwa_promo_points_2017.php", "tunukiwa_promo_winnings_2017.php", "RLNM_Till.php","lms_query_bonga_details.php", "lms_query_sms_status.php","dp_platinum_check_status.php","cvm_blacklist.php","cvm_report.php","cvm_unblacklist.php",
 "mpesa_tu_promo_details_2018.php", "mpesa_tu_promo_history_2018.php", "mpesa_tu_promo_points_2018.php", "mpesa_tu_promo_winnings_2018.php","ftth_gsm.php","iflix.php","cvm_in_trade.php",
 "biashara_ni_mpesa.php", "mpesa_me_promo.php", "mpesa_stawisha_agents.php","eol_bongalink_airpurchase_offer_retry.php","ftth_jubilee_insurance.php" );
//"check_unclaimed_assets_archived_number.php",


if (!in_array($page_name, $array_globally_accessible)) {
	$unauthorized_access_url = "unauthorized_access.php";
	check_security_level(null, $unauthorized_access_url);
}

/** insert_header && insert_footer are template functions */
function insert_header() {
	include_once("../tpl/header.php");
}

function insert_header2() {
	include_once "../tpl/header2.php";
}

function insert_footer() {
	include_once("../tpl/footer.php");
}

function insert_footer2() {
	include_once("../tpl/footer2.php");
}

/** Simply writes some data to a file
* @param $file the file name
* @param $string the data to be written to the file
*/
function file_write($file, $data)
{
	$fd = fopen($file, 'a+');// or die("Can't open file");
	fwrite($fd, $data);
	fclose($fd);
}

/** 5 types of log statuses: DEBUG, INFO, WARNING, ERROR, CRITICAL
* @param $status the type of log message
* @param $message the content of the message
*/
function logmessage($status, $message)
{
    global $LOG_LEVEL;
	$log_levels = array("DEBUG", "INFO", "WARNING", "ERROR", "CRITICAL");
    if ($LOG_LEVEL != null) {
    	for ($i = 0; $i < $LOG_LEVEL; $i++) {
    		array_shift($log_levels);
		}
		if (!in_array($status, $log_levels)) {
			return;
		}
	}
	//get today's date and then write to file
    $today = date("Y-m-d", mktime( date("m"), date("d"), date("Y") ) );
    $filename = "../logs/DataPromo-".$today;
    $timestamp = date ("Y-m-d H:i", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));

	$message = "[".$status."]\t[".$message."] [".$timestamp."]\n";
	file_write($filename, $message);
}

/** builds the html required to make a drop down list
* <br />Simply 'echo build_combo($name, $option_elements, $class, $js_function)' to generate a list
* <br />Taking an array element as {$key=>$value}, $key is the display element, while $value is the return value
* @param $name the name to assign to the drop down list 
* @param $option_elements an array containing the elements of the list
* @param $class the css class to assign to the drop down list
* @param $js_function the name(without '()') of the function to execute onChange
* @param $selected the element to mark as selected in the list; if set to '-1' then does not mark any as selected
* @param $first_element the element to display when no option has yet been selected, e.g '--Select--'
* @return the complete html string to build the list
*/
function build_combo($name, $option_elements, $class, $js_function, $selected = -1, $first_element = "--Select--") {
	if ($js_function != null)
		if (strpos($js_function, "(") === false) {
			$combo = "<select id='".$name."' name='".$name."' class='".$class."' onChange='javascript:".$js_function."();'>\r\n";
		}
		else {
			$combo = "<select id='".$name."' name='".$name."' class='".$class."' onChange='".$js_function.";'>\r\n";
		}
	else
		$combo = "<select id='".$name."' name='".$name."' class='".$class."'>\r\n";
	//add the initial element
	if ($selected == -1)
		$combo .= "<option value='none' selected>".$first_element."</option>\r\n";

	//build the options
	foreach($option_elements as $key=>$value) {
		if ($key == $selected) {
			$combo .= "<option value='".$key."' selected>".$value."</option>\r\n";
		}
		else {
			$combo .= "<option value='".$key."'>".$value."</option>\r\n";
		}
	}
	$combo .= "</select>\r\n";
	
	return $combo;
}

/** builds the html required to make a drop down list
* @param $name the name & id to assign to the drop down list 
* @param $start the first integer element of the list
* @param $stop the last integer element of the list
* @param $class the css class to assign to the drop down list
* @param $js_function the name(without '()') of the function to execute onChange
* @param $selected the element to mark as selected in the list; if set to '-1' then does not mark any as selected
* @param $first_element the element to display when no option has yet been selected, e.g '--Select--'
* @return the complete html string to build the list
*/
function build_combo_int($name, $start, $stop, $class, $js_function, $selected = -1, $first_element = "--") {
	if ($js_function != null)
		$combo = "<select id='".$name."' name='".$name."' class='".$class."' onChange='javascript:".$js_function."();'>\r\n";
	else
		$combo = "<select name='".$name."' class='".$class."'>\r\n";
	//add the initial element
	if ($selected == -1)
		$combo .= "<option value='none' selected>".$first_element."</option>\r\n";
	
	//build the options
	for($i = $start; $i <= $stop; $i++) {
		if ($i == $selected)
			$combo .= "<option value='".$i."' selected>".$i."</option>\r\n";
		else
			$combo .= "<option value='".$i."'>".$i."</option>\r\n";
	}
	$combo .= "</select>\r\n";
	
	return $combo;
}

/** builds the html required to make a drop down list
* <br />Simply 'echo build_combo($name, $option_elements, $class, $js_function)' to generate a list
* <br />Taking an array element as {$key=>$value}, $key is the display element, while $value is the return value
* @param $name the name to assign to the drop down list 
* @param $option_elements an array containing the elements of the list
* @param $class the css class to assign to the drop down list
* @param $js_function the name(without '()') of the function to execute onChange
* @param $selected the element to mark as selected in the list; if set to '-1' then does not mark any as selected
* @param $first_element the element to display when no option has yet been selected, e.g '--Select--'
* @return the complete html string to build the list
*/
function build_combo_disabled($name, $option_elements, $class, $js_function, $selected = -1, $first_element = "--Select--") {
	if ($js_function != null)
		$combo = "<select id='".$name."' name='".$name."' class='".$class."' onChange='javascript:".$js_function."();' disabled style='width:145px'>\r\n";
	else
		$combo = "<select id='".$name."' name='".$name."' class='".$class."' disabled style='width:145px'>\r\n";
	//add the initial element
	if ($selected == -1)
		$combo .= "<option value='none' selected>".$first_element."</option>\r\n";

	//build the options
	foreach($option_elements as $key=>$value) {
		if ($key == $selected) {
			$combo .= "<option value='".$key."' selected>".$value."</option>\r\n";
		}
		else {
			$combo .= "<option value='".$key."'>".$value."</option>\r\n";
		}
	}
	$combo .= "</select>\r\n";
	
	return $combo;
}

/** validates that the data_element meets with certain criteria
* <br />validation types:
* <br />case 0: no check
* <br />case 1: number
* <br />case 2: alphanumeric
* <br />case 3: alphabetic
* <br />case 11: msisdn
* <br />case 12: email
* <br />case 13: date
* <br />case 14: alphanumeric string with space, hyphen, underscore & dot
* <br />case 15: date with alphabetic month
* <br />case 16: good password - alphanumeric with caps btn 6 & 10 characters long
* @param $data_element the data to be checked
* @return empty string on success; error string on failure
*/ 
function validate($data_elements) {
	$error = "";

	//structure of $value is: description, posted_value, required (0|1), validation_type)
	foreach ($data_elements as $value) {
		list($description, $posted_value, $required, $validation) = $value;
		
		//if required is 1, ensure posted_value is not empty
		if ($required == 1) {
			if ($posted_value == "" || $posted_value == null) {
				$error .= "<br />Please ensure $description is not empty.";
				continue;
			}
		}
		else {
			if ($posted_value == "" || $posted_value == null) {
				continue;
			}
		}
		
		switch($validation) {
			case 0:
				//no validation
				break;
			case 1:
				//number
				if (!ctype_digit($posted_value)) {
					$error .= "<br />Please ensure $description is a valid number.";
				}
				break;
			case 2:
				//alphanumeric
				if (!ctype_alnum($posted_value)) {
					$error .= "<br />Please ensure $description consists only of letters and numbers.";
				}
				break;
			case 3:
				//alphabetic
				if (!ctype_alpha($posted_value)) {
					$error .= "<br />Please ensure $description consists only of alphabetic characters.";
				}
				break;
			case 11:
				//msisdn
				$validator =  new Validate();				
				if (!$validator->is_valid_msisdn($posted_value)) {
					$error .= "<br />Please ensure $description is valid phone number, e.g. 0722123456";
				}
				break;
			case 12:
				//email
				$validator = new Validate();
				if (!$validator->is_valid_email($posted_value)) {
					$error .= "<br />Please ensure $description is a valid email address.";
				}
				break;
			case 13:
				//date
				$validator = new Validate();
				//the null indicates we use default date formate - mm-dd-yyyy
				if (!$validator->is_valid_date($posted_value, null)) {
					$error .= "<br />Please ensure $description is a valid date.";
				}
				break;
			case 14:
				//alphanumeric string with space, hyphen, underscore & dot
				$validator = new Validate();
				if (!$validator->is_valid_general($posted_value)) {
					$error .= "<br />Please ensure $description only contains alphanumeric characters and underscore, dot, hyphen or space.";
				}
				break;
			case 15:
				//date
				$validator = new Validate();
				//the null indicates we use default date formate - mm-dd-yyyy
				if (!$validator->is_valid_alphabetic_date($posted_value, null)) {
					$error .= "<br />Please ensure $description is a valid date.";
				}
				break;
			case 16:
				//password
				$validator = new Validate();
				if (!$validator->is_good_password($posted_value)) {
					$error .= "<br />Please ensure $description is a valid password.";
					$error .= "<br />Password must be alphanumeric with atleast one capital letter and may contain [@._! $%^&*+=-]";
				}

				//enforce password length
				$length = strlen($posted_value);
				if (!($length > 5 && $length < 11)) {
					$error .= "<br />Password must be between 6 & 10 characters!";
				}
				break;
			case 17:
				//date
				$validator = new Validate();
				//the null indicates we use default date formate - mm-dd-yyyy
				if (!$validator->is_valid_alphabetic_date($posted_value, "yyyymmdd")) {
					$error .= "<br />Please ensure $description is a valid date.";
				}
				break;				
		}
	}
	
	return $error;
}

/** simply checks if someone/anyone is logged in
* @param $redirect the URL to go to incase no one is logged in
* @return true if someone is logged in; false if no one is logged in and $redirect is 'null'; $redirect_url if no is logged in & $redirect_url is specified
*/
function check_logged_in($redirect = null) {
	$status = false;
	
	if (isset($_SESSION['USERID'])) {
		$status = true;
	}
	else {
		if ($redirect != null) {
			$status = $redirect;
		}
	}

	return $status;
}

/** checks if the user currently logged in has clearance to access the current page 
* <br /> this function defaults to a 'restrictive' or 'prohibitive' policy
* <br /> this means that if the page access rights are not expressly defined, the page is assigned the highest security level
* @param $policy the security policy
* <br /> default='prohibitive'; also supports 'permissive' 
* @param $redirect the URL to go to incase the user does not have access rights
* @return true if user has access; false otherwise (of course this depends on whether $redirect is set)
*/
function check_security_level($policy = null, $redirect = null) {
		global $SECURITY_POLICY;
		global $DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME;
		$page_name = basename($_SERVER['PHP_SELF']);

		//first check if the user's inactivity has exceeded $SESSION_TIMEOUT
		expire_session();

		//set security level
		if ($policy == null) {
			$policy = $SECURITY_POLICY;
		}
		
		//first check if user is logged in
		$please_login_url = "please_login.php";
		$status = check_logged_in($please_login_url);
		
		//if $status == 1(or true) then we know someone is logged in
		if ($status == 1) {
			//retrieve security level of page			
			$query = "SELECT security_level FROM page_security WHERE page_name = '$page_name'";
			$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
			$error = $db->open_connection();
			$array_sec = $db->list_records($query);
			//get user's security level set when logging in		
			$my_security_level = $_SESSION['SECURITY_LEVEL'];
			
			//N.B: 0 is the highest security level
			if (is_array($array_sec)) {
				if (count($array_sec) > 1) {
					//multiple rules for this page, let's see if our user can access it though
					$max_sec_level = 0;
					for ($i = 0; $i < count($array_sec); $i++) {
						if ($array_sec[$i][0] == $my_security_level) {
							return true;
						}
						else {
							if ($array_sec[$i][0] > $max_sec_level) {
								$max_sec_level = $array_sec[$i][0];
							}							
						}
					}
					$page_security_level = $max_sec_level;
				}
				else {
					$page_security_level = $array_sec[0][0];
				}
			}
			else {
				if ($policy == 'permissive') {
					$query = "SELECT max(security_level) FROM security_levels";
					$array_highest_level = $db->list_records($query);
					$page_security_level = $array_highest_level[0][0];
				}
				else {
					$page_security_level = 0;
				}
			}
			
			//check restriction
			$query = "SELECT restrict FROM security_levels WHERE security_level = ".$my_security_level;
			$array_sec = $db->list_records($query);
			$db->close_connection();
			
			if(is_array($array_sec)) {
				$restriction = $array_sec[0][0];
			}
			else {
				$restriction = 0;
			}
			
			//if restriction = 1 then the user can only access pages of his/her security level
			//if restriction = 0 then the user can access all pages of his/her security level and lower
			
			if ($restriction == "0") {
				if ($my_security_level <= $page_security_level) {
					//grant access
					$message = "TRACKER - User ID: ".$_SESSION['USERID']."; Page: ".$page_name." - ACCESS GRANTED";
					logmessage("INFO", $message);
					return true;
				}
				else {
					$message = "TRACKER - User ID: ".$_SESSION['USERID']."; Page: ".$page_name." - ACCESS DENIED";
					logmessage("WARNING", $message);
					if ($redirect == null) {					
						return false;
					}
					else {
						//redirect user to page specified in $redirect					
						header("Location: $redirect");
					}			
				}
			}
			else {
				if ($my_security_level == $page_security_level) {
					//grant access
					$message = "TRACKER - User ID: ".$_SESSION['USERID']."; Page: ".$page_name." - ACCESS GRANTED";
					logmessage("INFO", $message);
					return true;
				}
				else {
					$message = "TRACKER - User ID: ".$_SESSION['USERID']."; Page: ".$page_name." - ACCESS DENIED";
					logmessage("WARNING", $message);
					if ($redirect == null) {					
						return false;
					}
					else {
						//redirect user to page specified in $redirect					
						header("Location: $redirect");
					}			
				}				
			}
		}
		else if ($status == false) {
			$message = "TRACKER - NO LOGIN INFORMATION FOUND; Page: ".$page_name." - ACCESS DENIED";
			logmessage("WARNING", $message);
			return false;
		} 
		else if ($status == $please_login_url)
		{
			$message = "TRACKER - NO LOGIN INFORMATION FOUND; Page: ".$page_name." - ACCESS DENIED";
			logmessage("WARNING", $message);
			header("Location: $status");
		}
}

/** builds an 'insert' SQL statement
* @param $array_data the array containing ($key=>$value) pairs constituting the data
* <br /> the $key is the column name; $value is the data to be saved in that column
* @param $table the name of the table into which data will be inserted
* @return the SQL query
*/
function build_insert_sql($array_data, $table, $date_format = null) {
	$default_date_format = 'dd-mm-yyyy hh24:mi:ss';
	
	$query = "INSERT INTO $table (";
	$data = "VALUES (";
	$first_rec = true;
	
	foreach ($array_data as $key=>$value) {
		if ($first_rec == true) {
			$query .= $key;
			
			if ($value[3] == 13 || $value[3] == 15) {
				if ($date_format != null)
					$data .= "to_date('$value[1]', '$date_format')";
				else
					$data .= "to_date('$value[1]', '$default_date_format')";
			}
			else if ($value[1] == 'SYSDATE' || $value[1] == 'CURRENT_TIMESTAMP') {
				//do not quote sysdate
				$data .= $value[1];
			}
			else
				$data .= "'".$value[1]."'";
			
			$first_rec = false;
		}
		else {
			$query .= ", ".$key;
			
			if ($value[3] == 13 || $value[3] == 15) {
				if ($date_format != null)
					$data .= ", "."to_date('$value[1]', '$date_format')";
				else
					$data .= ", "."to_date('$value[1]', '$default_date_format')";					
			}
			else if ($value[1] == 'SYSDATE') {
				//do not quote sysdate	
				$data .= ", ".$value[1];
			}
			else
				$data .= ", '".$value[1]."'";
		}				
	}
	$query .= ") ".$data.")";
	
	return $query;
}


/** builds an 'update' SQL statement
* @param $array_data the array containing ($key=>$value) pairs constituting the data
* <br /> the $key is the column name; $value is the data to be updated in that column
* @param $table the name of the table containing data to be updated
* @return the SQL query
*/
function build_update_sql($array_data, $table) {
	$query = "UPDATE $table SET ";
	$first_rec = true;
	
	//special construct for PCK
	foreach ($array_data as $key=>$value) {
		$array_data[$key] = $value[1];
	}
	
	foreach ($array_data as $key=>$value) {
		if ($first_rec == true) {
			//do not quote sysdate
			if ($value == 'SYSDATE')
				$query .= $key."=$value";
			else
				$query .= $key."='".$value."'";
			
			$first_rec = false;
		}
		else {
			//do not quote sysdate
			if ($value == 'SYSDATE')
				$query .= ", ".$key."=$value";	
			else
				$query .= ", ".$key."='".$value."'";
		}				
	}
	
	return $query;
}


/** builds a 'select' SQL statement
* @param $tables the array containing the tables to select from
* @param $columns the array containing the columns to select data from
* @param $select_id select extra field, like 'ID'
* <br /> you can leave it blank
* @return the SQL query
*/
function build_select_sql($tables, $columns, $select_id = "") {
	/*special construct for PCK
	foreach ($array_data as $key=>$value) {
		$array_data[$key] = $value[1];
	}*/
		
	//build query
	if ($select_id != "")
		$query = "SELECT $select_id, ";
	else
		$query = "SELECT ";
		
	$first_rec = true;
	foreach ($columns as $value) {
		if ($first_rec == true) {
			$query .= $value;
			$first_rec = false;
		}
		else {
			$query .= ",".$value;
		}				
	}
	
	$first_rec = true;
	$query .= " FROM ";
	foreach ($tables as $value) {
		if ($first_rec == true) {
			$query .= $value;
			$first_rec = false;
		}
		else {
			$query .= ",".$value;
		}				
	}
	
	return $query;
}

/** builds a 'select' SQL statement
* @param $tables the array containing the tables to select from
* @param $columns the array containing the columns to select data from
* @param $select_id select extra field, like 'ID'
* <br /> you can leave it blank
* @return the SQL query
*/
function build_select_sql2($array_data, $tables, $select_id = "") {	
	//build query
	if ($select_id != "")
		$query = "SELECT $select_id, ";
	else
		$query = "SELECT ";
	
	foreach ($array_data as $key=>$value) {
		$array_data[$key][0] = $value[1];
		$array_data[$key][1] = $value[3];
	}
	
	//data types that are of type 'date' are 13 & 15
	$first_rec = true;
	foreach ($array_data as $key=>$value) {
		if ($first_rec == true) {
			if ($value[1] == 13 || $value[1] == 15)
				$query .= "to_char('$value[0]', 'dd-mm-yy hh24:mi:ss')";
			else
				$query .= $value[0];
			$first_rec = false;
		}
		else {
			if ($value[1] == 13 || $value[1] == 15)
				$query .= "to_char('$value[0]', 'dd-mm-yy hh24:mi:ss')";
			else
				$query .= ",".$value[0];
		}				
	}
			
	$first_rec = true;
	$query .= " FROM ";
	foreach ($tables as $value) {
		if ($first_rec == true) {
			$query .= $value;
			$first_rec = false;
		}
		else {
			$query .= ",".$value;
		}				
	}
	
	return $query;
}

/** generates a pdf document from a html document using html2fpdf class
* @param $html_doc the name of the html document to convert to pdf
* @param $pdf_doc the name of the generated pdf document
*/
function generate_pdf($html_doc, $pdf_doc) {
	$pdf = new HTML2FPDF();
	$pdf->AddPage();
	$fp = fopen($html_doc, "r");
	
	if (!$fp) {
		$message = "GENERATE PDF: COULD NOT READ FILE: $html_doc";
		logmessage("ERROR", $message);
	}
	else  {
		$strContent = fread($fp, filesize($html_doc));
		fclose($fp);
		$pdf->WriteHTML($strContent);
		$pdf->Output($pdf_doc);

		header("download_list.php");
	}

	
}

/** Validate login credentials 
* @return empty string if login successful; error string if not successful
*/
function login() {
	$error = "";
	global $DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME;
    global $SESSION_TIMEOUT;
	$user_data = null;
	$encrypted_passwd = null;
	
	$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
	if (isset($_POST["username"]) && isset($_POST["passwd"])) {
		//validate data to ensure no sql injection					
		$array_credentials = array (
			'username' => array("Username", strtolower($_POST['username']), 1, 14),
			'passwd' => array("Password", $_POST['passwd'], 1, 16)
		);
		
		$error = validate($array_credentials);
		//echo $error;
		if ($error == "") {
			//encrypt password for comparison			
			$encrypted_passwd = crypt(md5($_POST["passwd"]), md5($_POST["username"]));
			//$encrypted_passwd = $_POST["passwd"];
						
			$query = "SELECT * FROM web_user_list where username = '".$_POST["username"]."' AND password = '".$encrypted_passwd."'";
			$error = $db->open_connection();
			//echo $query;
			$user_data = $db->list_records($query, false);			

			if (isset($user_data)) {
				if (count($user_data) == 1) {
					//set session variables
					$_SESSION['EMP_NO'] = $user_data[0][0];
					$_SESSION['USERID'] = $user_data[0][4];
					$_SESSION['USERNAME'] = $user_data[0][1]." ".$user_data[0][2];
					$_SESSION['SECURITY_LEVEL'] = $user_data[0][6];					

                    if ($SESSION_TIMEOUT > 0) {
                        $_SESSION['expire_time'] = mktime(date("H"), date("i")+ $SESSION_TIMEOUT, date("s"), date("m"), date("d"), date("Y"));
                    }
                    
					logmessage("INFO", "SITE LOGIN - User ID: ".$user_data[0][4]." - SUCCESSFUL");
				}
				else {
					$error = "Login Failed.<br />Contact Your Administrator For Assistance";
					logmessage("WARNING", "SITE LOGIN - User: ".$_POST["username"]." - SELECTED MULTIPLE RECORDS; POSSIBLE SQL INJECTION");
				}
			}
			else {
				$error = "Login Failed.<br />Username or Password is wrong!";
				$query = "SELECT * FROM user_list where username = '".$_POST["username"]."'";
				$user_data = $db->list_records($query, false);
				
				if (isset($user_data))
					logmessage("WARNING", "SITE LOGIN - ".$user_data[0][4]." - WRONG PASSWORD!");
				else
					logmessage("WARNING", "SITE LOGIN - ".$_POST['username']." - USER NOT FOUND!");
			}
			$db->close_connection();
		}			
	}

	return $error;
}



/**
 * this function expires our sessions after a configurable number of minutes of inactivity
 *
 */
function expire_session(){
	global $SESSION_INFO_LOCATION, $SESSION_TIMEOUT, $SESSION_TYPE;
	global $DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME;
    global $error;

    $c_hour = date("H");    //Current Hour
	$c_min = date("i");    //Current Minute
	$c_sec = date("s");    //Current Second
	$c_mon = date("m");    //Current Month
	$c_day = date("d");    //Current Day
	$c_year = date("Y");    //Current Year

    if ($SESSION_INFO_LOCATION == 'database') {
        $db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
        $query = "select session_timeout, session_type from session_info";

        $error = $db->open_connection();
        $array_session_data = $db->list_records($query, true);
        $db->close_connection();

        if (isset($array_session_data)) {
            $_SESSION['SESSION_TIMEOUT'] = $array_session_data[0][0];
            $_SESSION['SESSION_TYPE'] = $array_session_data[0][1];
        }
        else {
            //use default configs
            $_SESSION['SESSION_TIMEOUT'] = $SESSION_TIMEOUT;
            $_SESSION['SESSION_TYPE'] = $SESSION_TYPE;

            logmessage("WARNING", "expire_session; failed to retrieve session information from db; $error");
        }
    }
    else {
        $_SESSION['SESSION_TIMEOUT'] = $SESSION_TIMEOUT;
        $_SESSION['SESSION_TYPE'] = $SESSION_TYPE;
    }

    if ($_SESSION['SESSION_TIMEOUT'] > 0) {
        $c_timestamp = mktime($c_hour,$c_min,$c_sec,$c_mon,$c_day,$c_year);

        if (isset($_SESSION['expire_time']))
            $t_timestamp = $_SESSION['expire_time'];

        if (!$t_timestamp) {
            $message = urlencode("<b>ERROR:</b> Inactive monitor unable to establish time. Please login again.");
            logmessage("WARNING", "expire_session; $message");
            header("Location: logout.php");
            exit;
        }
        elseif ($t_timestamp < $c_timestamp) {
            if ($SESSION_TYPE == 'incremental') {
                $message = urlencode("<b>ERROR:</b> Your account has been inactive for ".$_SESSION['SESSION_TIMEOUT']." minutes. Please login again.");
                logmessage("INFO", "expire_session; ".$_SESSION['USERID']." $message");
                header("Location: session_expired.php");
            }
            else if ($SESSION_TYPE == 'static') {
                $message = urlencode("<b>ERROR:</b> Your account has been active for ".$_SESSION['SESSION_TIMEOUT']." minutes. Please login again.");
                logmessage("INFO", "expire_session; ".$_SESSION['USERID']." $message");
                header("Location: session_expired.php");
            }
        }

        //refresh our session information
        if ($SESSION_TYPE == 'incremental') {
            $t_timestamp = mktime($c_hour,$c_min+$_SESSION['SESSION_TIMEOUT'],$c_sec,$c_mon,$c_day,$c_year);
        }
        else if ($SESSION_TYPE == 'static') {
            $t_timestamp = $_SESSION['expire_time'];
        }
        $_SESSION['expire_time'] = $t_timestamp;
    }
}

/**
 * this function sends mail
 *
 * @param string $from
 * @param array[email_address, name] $recepient_list
 * @param string $subject
 * @param string $body
 * @param array[email_address, name] $cc_list
 * @param array[email_address, name] $bcc_list
 * @param string $from_name
 * @param array[file_name] $attachment_list
 * @param int $body_type 0 indicates that the $body parameter is a string containing the actual body; 1 indicates a file
 * @param string $altbody
 * @return int 0 on success, 1 otherwise
 */
function send_mail($from, $recepient_list, $subject, $body, $cc_list = null, $bcc_list = null, $from_name = null, $attachment_list = null, $body_type = 0, $altbody = null) {
	global $SMTP_SERVER, $error, $message;

	$mail = new PHPMailer();

	if ($body_type == 1) {
		$mail_body = $mail->getFile($body);
		$mail_body = eregi_replace("[\]",'',$mail_body);
		$body = $mail_body;
	}

	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host = $SMTP_SERVER; // SMTP server

	$mail->From = $from;

	if (isset($from_name)) {
		$mail->FromName = $from_name;
	}

	$mail->Subject = $subject;

	if (isset($altbody)) {
		$mail->AltBody = $altbody;
	}
	else {
		$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	}

	//set the mail body
	$mail->MsgHTML($body);

	//add recepients
	for ($i = 0; $i < count($recepient_list); $i++) {
		$mail->AddAddress($recepient_list[$i][0], $recepient_list[$i][1]);
	}

	//add cc recepients
	if (isset($cc_list)) {
		for ($i = 0; $i < count($cc_list); $i++) {
			$mail->AddCC($cc_list[$i][0], $cc_list[$i][1]);
		}
	}

	//add bcc recepients
	if (isset($bcc_list)) {
		for ($i = 0; $i < count($bcc_list); $i++) {
			$mail->AddBCC($bcc_list[$i][0], $bcc_list[$i][1]);
		}
	}

	//add attachments
	for ($i = 0; $i < count($attachment_list); $i++) {
		$mail->AddAttachment($attachment_list[$i]);
	}

	//send the mail
	if(!$mail->Send()) {
		$error = "Mailer Error: " . $mail->ErrorInfo;

		return 1;
	} else {
		$message = "Message sent!";

		return 0;
	}
}

/**
 * Call to postpay billing
 *
 * @param number $msisdn
 * @param unknown_type $amount
 * @param unknown_type $tax_code
 * @param unknown_type $expiration
 * @param unknown_type $source
 * @param unknown_type $service_desc
 */
function postpay_billing($msisdn, $amount, $transaction_detail) {
	global $RCI_USERNAME , $RCI_PASSWORD, $RCI_TAX_CODE, $RCI_EXPIRATION, $RCI_SOURCE, $RCI_SERVICE_DESC;
	global $BILLING_RESPONSE;
	
	$params = array (
		'MSISDN' => $msisdn,
		'AMOUNT' => $amount,
		'TAX_CODE' => $RCI_TAX_CODE,
		'EXPIRATION' => $RCI_EXPIRATION,
		'SOURCE' => $RCI_SOURCE,
		'SERVICE_DESC' => $RCI_SERVICE_DESC,
        'TRANSACTION_DETAIL' => $transaction_detail
	);

	/** Location of the SOAP endpoint */
	$endpoint = 'https://172.29.213.116:7511/SharedResources/Services/RCI_Service_SSL.serviceagent?wsdl';

	/** First step, initialize the SOAP client: */

	/** URL Authentication Method: */
	$client_p = new SoapClient($endpoint, 
                array('connection_timeout' => 50,
	                'login' => $RCI_USERNAME,
			        'password' => $RCI_PASSWORD)
				);   
                
	try {
    	//var_dump($client_p->__getFunctions());
		 $BILLING_RESPONSE = $client_p->RequestConfirmReservation($params);
    	//print_r($response);
	} catch (SoapFault $e) {
    	logmessage("ERROR", "postpay_billing: SoapFault Exception: ".$e->faultstring );
	}
}

function prepay_billing($transactionid, $opermsisdn, $msisdn, $accountid, $amount) {
	global $MD_USERID, $MD_PASSWORD, $MD_TERMINALID;
	global $BILLING_RESPONSE;
	
	/** Location of the SOAP endpoint */
	$endpoint = 'https://10.5.4.234:8443/axis/services/Mediator?wsdl';
	$params = array 	(   
			 'userID' => $MD_USERID,
			 'password' => $MD_PASSWORD,
			 'accountID' => $accountid,
			 'amount' => $amount,
			 'msisdn' => $msisdn,
			 'opermsisdn' => $opermsisdn,
			 'status' => 10001,
			 'terminalID' => $MD_TERMINALID,
			 'transactionID' => $transactionid
			);  

	/** First step, initialize the SOAP client: */

	/** URL Authentication Method: */
	$client_p = new SoapClient($endpoint);

	try {
		//var_dump($client_p->__getFunctions()); 
		$BILLING_RESPONSE = $client_p->chargeAmount($params);
		//print_r($response);
	} catch (SoapFault $e) {
		logmessage("ERROR", "prepay_billing: SoapFault Exception: ".$e->faultstring );
	}
}		

function curl_url_post ($url, $method, $vars) {
   $ch = curl_init();

   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_HEADER, 0);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // RETURN THE CONTENTS OF THE CALL
   curl_setopt($ch, CURLOPT_TIMEOUT, 4);
   if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
   }

   ob_start();
   $data = curl_exec ($ch);

   if (!$data)
   {
        logmessage("ERROR", "CURL_EXEC: ".curl_errno($ch).": ".curl_error($ch)."; user: ".$_SESSION['USERID']);
   }
   else {
	logmessage("DEBUG", $method.": ".$url."; vars: ".$vars."; response: ".$data."; user: ".$_SESSION['USERID']);
   }


   curl_close ($ch);
   
   $string = ob_get_contents();

   ob_end_clean();
   
   //return $string;
   return $data;
}

/* function that checks if this is a browser; probably not full proof but will take care of the common ones
 * 
 */
function is_browser($http_user_agent) {
    if (strstr($http_user_agent, 'Opera Mini')) {
        return false;
    }
    else {
        if (strstr($http_user_agent, 'Windows NT 6.0') || strstr($http_user_agent, 'Macintosh') || strstr($http_user_agent, 'i686') || strstr($http_user_agent, 'Konqueror') || strstr($http_user_agent, 'Wget') || strstr($http_user_agent, 'Lynx')) {
            return true;
        }
        else {
            if (strstr($http_user_agent, 'Windows NT 5.1') && strstr($http_user_agent,'Mozilla')) {
                return true;
            }
            else {
                return false;
            }
        }
    }
}

/************************************************************************************************
 *
 *  Project specific functions
 *
 * ***********************************************************************************************/

/**
 * write records of downloaded clips
 *
 * @param unknown_type $msisdn
 * @param unknown_type $clip
 * @param unknown_type $clip_id
 */
function write_cdr($msisdn, $clip, $clip_id)
{
	//get today's date and then write to file
    $now = date("Y-m-d_Hi", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y") ));
    $filename = "cdr/in/datapromocdr-".$now;
    $timestamp = date ("Y-m-d H:i", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));

	$message = $msisdn."|".$clip_id."|".$clip."|".$timestamp."\n";
	file_write($filename, $message);
}

/**
 * write records of all subs billed & the type of sub; 0 - prepaid, 1 - postpaid
 *
 * @param <type> $msisdn
 * @param <type> $sub_type
 */
function write_billed_cdr($msisdn, $sub_type, $amount = 5, $comment = "5 Bob Shop")
{
    $amount = 5;
	//get today's date and then write to file
    $now = date("Y-m-d_Hi", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y") ));
    $filename = "billed_cdr/in/datapromobilledcdr-".$now;
    $timestamp = date ("Y-m-d H:i", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));

	$message = $msisdn."|".$sub_type."|".$amount."|".$timestamp."|\"".$comment."\"\n";
	file_write($filename, $message);
}

/**
 * write records of downloaded clips
 *
 * @param unknown_type $msisdn
 * @param unknown_type $message
 */
function write_feedback($msisdn, $message)
{
	//get today's date and then write to file
    $now = date("Y-m-d_Hi", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y") ));
    $filename = "feedback/in/datapromofeedback-".$now;
    $timestamp = date ("Y-m-d H:i", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));

	$message = $msisdn."|".$message."|".$timestamp."\n";
	file_write($filename, $message);
}

/** this function generates the html report that is then converted to pdf by fpdf (our open source pdf generator class)
*/
function generate_draw_report() {
	global $DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME;
	//initialise database object
	$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
	//get date
	if (isset($_POST['date'])) {
		$date = $_POST['date'];
	}
	else {
		$date = date("Y-m-d H:i:s");
	}

	$draw = "DRAW ".$_POST['draw'];
	echo $date." ".$draw;
	//get number of winners
	$query = "SELECT COUNT(MSISDN) FROM WINNERS WHERE PRIZE_ID = '".$_POST['prize']."' AND DRAW_ID = ".$_POST['draw']." AND CONFIRM_WINNER = 'YES'";
	$error = $db->open_connection();
	$array_count_winners = $db->list_records($query);
	if (isset($array_count_winners)) {
		$total_winners = $array_count_winners[0][0];
	}
	else
		$total_winners = 0;

	//create file
	$file="temp.html";
	$fd = fopen($file, 'w+');// or die("Can't open file");
	$data = null;
	fwrite($fd, $data);
	fclose($fd);
	$file="temp.html";
	$fd = fopen($file, 'a+');// or die("Can't open file");

	//draw image
	$str = "<img src='images/saf_logo.jpg' width='406' /><b>ACTIVATE YOUR DRIVE PROMOTION</b>\r\n";
	//select prize name
	$query = "SELECT ID, PRIZE_NAME FROM PRIZES";
	$array_prizes_list = $db->list_records($query);
	$array_prizes = array();
	$array_keys = array();
	$array_values = array();

	for ($j = 0; $j < count($array_prizes_list); $j++) {
		$temp = array ($array_prizes_list[$j][0] => $array_prizes_list[$j][0]);
		$array_keys = array_merge($array_keys, $temp);
		$temp = array ($array_prizes_list[$j][1]);
		$array_values = array_merge($array_values, $temp);
	}
	$array_prizes = array_combine($array_keys, $array_values);

	foreach ($array_prizes as $key=>$value) {
		if ($_POST['prize'] == $key) {
			$prize = $value;
			break;
		}
	}
	$str .= $prize." WINNERS";

	//get draw details
	$query = "SELECT SYSTEM_USER, TO_CHAR(DATE_EXECUTED, 'DD-MM-YYYY HH24:MI') FROM DRAWS WHERE PRIZE_ID = '".$_POST['prize']."' AND DRAW_ID = ".$_POST['draw'];
	$array_user = $db->list_records($query);
	$run_by = $array_user[0][0];
	$run_at = $array_user[0][1];

	//draw details
	$str .= "<br /><br />\r\n";
	$str .= "<center><b>DRAW DETAILS</b></center><br />\r\n";
	$str .= "<table border='1'>\r\n";
	$str .= "<tr>\r\n";
	$str .= "<tr>\r\n";
	$str .= "<td width='50%'>Draw Date</td>\r\n";
	$str .= "<td width='50%'>$run_at</td>\r\n";
	$str .= "</tr>\r\n";
	$str .= "<tr>\r\n";
	$str .= "<tr>\r\n";
	$str .= "<td>Draw Number</td>\r\n";
	$str .= "<td>$draw</td>\r\n";
	$str .= "</tr>\r\n";
	$str .= "<tr>\r\n";
	$str .= "<td>Draw Run by</td>\r\n";
	$str .= "<td>".$run_by."</td>\r\n";
	$str .= "</tr>\r\n";
	$str .= "<tr>\r\n";
	$str .= "<td>Number of Winners</td>\r\n";
	$str .= "<td>$total_winners</td>\r\n";
	$str .= "</tr>\r\n";
	$str .= "<tr>\r\n";
	$str .= "<td width='50%'>Report Generated By</td>\r\n";
	$str .= "<td>".$_SESSION['USERNAME']."</td>\r\n";
	$str .= "</tr>\r\n";
	$str .= "<tr>\r\n";
	$str .= "<td width='50%'>Report Generated At</td>\r\n";
	$str .= "<td width='50%'>$date</td>\r\n";
	$str .= "</tr>\r\n";
	$str .= "<tr>\r\n";
	$str .= "</table>\r\n";

	//winners details
	$str .= "<br /><br />\r\n";
	$str .= "<center><b>WINNERS DETAILS</b></center><br />\r\n";
	$str .= "<table border='1'>\r\n";

	if (isset($_POST['manual_submit']))
		$str .= "<tr><th width='10%'>RECORD</th><th width='30%'>WINNING MSISDN</th><th width='30%'>CONFIRMED</th></tr>\r\n";
	else
		$str .= "<tr><th width='10%'>RECORD</th><th width='30%'>WINNING MSISDN</th></tr><th width='30%'>CONFIRMED</th>\r\n";

	//get all winners from db
	$query = "SELECT W.MSISDN, W.CONFIRM_WINNER FROM WINNERS W ";
	$query .= "WHERE PRIZE_ID = '".$_POST['prize']."' AND DRAW_ID = ".$_POST['draw'];
	echo $query;

	$array_winners = $db->list_records($query, true);
	$db->close_connection();
	if (is_array($array_winners)) {
		for ($i = 0; $i < count($array_winners); $i++) {
			echo "<tr>\r\n";
			$str .= "<tr>\r\n";
			$row = $i + 1;
			$str .= "<td>$row</td>";

			//select id_number
			//$query = "SELECT ID_NUMBER FROM REGISTRATIONS WHERE MSISDN = ".$array_winners[$i]['MSISDN'];
			//$array_msisdn = $db->list_records($query, true);
			//echo "<td>".$array_winners[$i]['ID_NUMBER']."</td>\r\n";

			$str .= "<td>".$array_winners[$i]['MSISDN']."</td>\r\n";
			//$str .= "<td>".$array_winners[$i]['ID_NUMBER']."</td>\r\n";
			//$str .= "<td>".$array_winners[$i]['ID_NUMBER']."</td>\r\n";

			if (isset($array_winners[$i]['CONFIRM_WINNER']) && $array_winners[$i]['CONFIRM_WINNER'] == 'YES')
				$str .= "<td>CONFIRMED</td>\r\n";
			else
				$str .= "<td>XXXXXXXXX</td>\r\n";

			$str .= "</tr>\r\n";
		}
	}
	$str .= "</table>\r\n";

	//signatures section
	$str .= "<br /><br />\r\n";
	$str .= "<center><b>SIGNATURES</b></center><br />\r\n";
	$str .= "<table border='1'>\r\n";
	$str .= "<tr><th>NAMES</th><th>SECTION</th><th>SIGNATURE</th></tr>\r\n";
	$str .= "<tr>\r\n";
	$str .= "<td width='30%'>&nbsp;</td><td width='40%'>SAFARICOM IT</td><td width='30%'></td>";
	$str .= "</tr>\r\n";
	$str .= "<tr>\r\n";
	$str .= "<td width='30%'>&nbsp;</td><td width='40%'>SAFARICOM RA</td><td width='30%'></td>";
	$str .= "</tr>\r\n";
	$str .= "<tr>\r\n";
	$str .= "<td width='30%'>&nbsp;</td><td width='40%'>SAFARICOM MARKETING</td><td width='30%'></td>";
	$str .= "</tr>\r\n";
	$str .= "<tr>\r\n";
	$str .= "<td width='30%'>&nbsp;</td><td width='40%'>BETTING CONTROL & LICENSING BOARD</td><td width='30%'></td>";
	$str .= "</tr>\r\n";
	$str .= "</table>\r\n";
	fwrite($fd, $str);
	fclose($fd);

	$date = str_replace(":", "_", $date);
	$prize = ereg_replace("[,.]", "", $prize);
	generate_pdf("temp.html", "reports\\report-$prize-$draw-$date.pdf");
	//header("Location: download_list.php");
}

function genRandomPassword() {
    $length = 5;
    
	$numbers = '0123456789';
	$lowerAlphabetic = 'abcdefghijklmnopqrstuvwxyz';
	$upperAlphabetic = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$special = '@_!?$%&*+=';
	$characters = $numbers.$lowerAlphabetic.$upperAlphabetic.$special;

    $string = '';
    
	// generate 3 lower alphabetic
    for ($p = 0; $p < 3; $p++) {
        $string .= $lowerAlphabetic[mt_rand(0, (strlen($lowerAlphabetic) - 1))];
    }	
	
	// generate 1 number
    for ($p = 0; $p < 1; $p++) {
        $string .= $numbers[mt_rand(0, (strlen($numbers) - 1))];
    }	

	// generate 1 upper alphabetic
    for ($p = 0; $p < 1; $p++) {
        $string .= $upperAlphabetic[mt_rand(0, (strlen($upperAlphabetic) - 1))];
    }	
	
	//generate random 3
    for ($p = 0; $p < 3; $p++) {
        $string .= $characters[mt_rand(0, (strlen($characters) - 1))];
    }
    return $string;
}


function surf2win_expire_session(){
	global $SESSION_INFO_LOCATION, $SESSION_TIMEOUT, $SESSION_TYPE;
	global $DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME;
    global $error;

    $c_hour = date("H");    //Current Hour
	$c_min = date("i");    //Current Minute
	$c_sec = date("s");    //Current Second
	$c_mon = date("m");    //Current Month
	$c_day = date("d");    //Current Day
	$c_year = date("Y");    //Current Year

    if ($SESSION_INFO_LOCATION == 'database') {
        $db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
        $query = "select session_timeout, session_type from session_info";

        $error = $db->open_connection();
        $array_session_data = $db->list_records($query);
        $db->close_connection();

        if (isset($array_session_data)) {
            $_SESSION['SESSION_TIMEOUT'] = $array_session_data[0][0];
            $_SESSION['SESSION_TYPE'] = $array_session_data[0][1];
        }
        else {
            //use default configs
            $_SESSION['SESSION_TIMEOUT'] = $SESSION_TIMEOUT;
            $_SESSION['SESSION_TYPE'] = $SESSION_TYPE;

            logmessage("WARNING", "expire_session; failed to retrieve session information from db; $error");
        }
    }
    else {
        $_SESSION['SESSION_TIMEOUT'] = $SESSION_TIMEOUT;
        $_SESSION['SESSION_TYPE'] = $SESSION_TYPE;
    }

    if ($_SESSION['SESSION_TIMEOUT'] > 0) {
        $c_timestamp = mktime($c_hour,$c_min,$c_sec,$c_mon,$c_day,$c_year);

        if (isset($_SESSION['billed_url']))
            $t_timestamp = $_SESSION['billed_url'];

            logmessage("INFO", $t_timestamp);
        if (!$t_timestamp) {
            $message = urlencode("<b>ERROR:</b> Inactive monitor unable to establish time. Please login again.");
            logmessage("WARNING", "expire_session; $message");
            unset ($_SESSION['billed_url']);
            //header("Location: logout.php");
            //exit;
        }
        else if ($t_timestamp < $c_timestamp) {
            if ($SESSION_TYPE == 'incremental') {
                $message = urlencode("<b>ERROR:</b> Your account has been inactive for ".$_SESSION['SESSION_TIMEOUT']." minutes. Please login again.");
                logmessage("INFO", "expire_session; ".$_SESSION['USERID']." $message");
                unset ($_SESSION['billed_url']);
                //header("Location: session_expired.php");
            }
            else if ($SESSION_TYPE == 'static') {
                $message = urlencode("<b>ERROR:</b> Your account has been active for ".$_SESSION['SESSION_TIMEOUT']." minutes. Please login again.");
                logmessage("INFO", "expire_session; ".$_SESSION['USERID']." $message");
                unset ($_SESSION['billed_url']);
                //header("Location: session_expired.php");
            }
        }
        else {
            //refresh our session information
            if ($_SESSION['SESSION_TYPE'] == 'incremental') {
                $t_timestamp = mktime($c_hour,$c_min+$_SESSION['SESSION_TIMEOUT'],$c_sec,$c_mon,$c_day,$c_year);
            }
            else if ($_SESSION['SESSION_TYPE'] == 'static') {
                $t_timestamp = $_SESSION['billed_url'];
            }
            $_SESSION['billed_url'] = $t_timestamp;
        }
    }
    else {
        //do nothing
    }
}

function surf2win_billing() {
    global $BILLING_RESPONSE;
    global $error;
    $bill = true;
    $bill_mode = 2;

    //set our session variable
    surf2win_expire_session();

    $server_php_self = basename($_SERVER['PHP_SELF']);
    if ($server_php_self == 'billed_index.php' || $server_php_self == 'billed_download.php' || $server_php_self == 'billed_index_detail.php') {
        $session_php_self = $_SESSION['PHP_SELF'];
        if (isset($_SESSION['PHP_SELF'])) {
            if ($session_php_self == 'billed_index.php') {
                if (isset($_SESSION['billed_url'])) {
                    $bill = false;
                }
            }
            else if ($session_php_self == 'billed_download.php') {
                if (isset($_SESSION['billed_url'])) {
                    $bill = false;
                }
            }
            else {
                if ($bill_mode == 0) {
                    //the sub's billed session is active until either they download or php expires the session
                    if (isset($_SESSION['billed_url'])) {
                        $bill = false;
                    }
                }
                /*else if ($bill_mode == 1) {
                    //no billing!
                    $bill = false;
                }*/
                else if ($bill_mode == 2) {
                    if (isset($_SESSION['billed_url'])) {
                        unset($_SESSION['billed_url']);
                    }
                }
            }
        }
    }
    else {
        if (isset($_SESSION['billed_url']) && $bill_mode == 2) {
            unset($_SESSION['billed_url']);
        }
    }

	if ($bill) {
        if (isset($_SERVER['HTTP_X_JINNY_CID'])) {
            $msisdn = $_SERVER['HTTP_X_JINNY_CID'];
            $short_msisdn = substr($msisdn, 3);

            //attempt to bill
            //determine subscriber type by sending a billing request
            //$timestamp = date ("Hi", mktime(date("H"), date("i")));
            /*$transactionid = "0".$short_msisdn;
            list($usec, $sec) = explode(" ",microtime());
            $transactionid = $transactionid + ($usec * 1000);
            $transactionid = substr(round($transactionid), 0, 10);
		*/

            $sq_DB_TYPE = "oracle";
            $sq_DATABASEHOST = "172.31.100.122";
            $sq_DATABASEPORT = "1529";
            $sq_DATABASEUSER = "promo";
            $sq_DATABASEPASSWORD = "Pr0m0_456";
            $sq_DATABASENAME = "PROMO";
            $seq_query = "select transaction_id_seq.nextval from dual";
            $seq_db = new Database($sq_DB_TYPE, $sq_DATABASEHOST, $sq_DATABASEPORT, $sq_DATABASEUSER, $sq_DATABASEPASSWORD, $sq_DATABASENAME);
            $error = $seq_db->open_connection();
            if ($error == "") {
                $array_seq = $seq_db->list_records($seq_query);
                $transactionid = $array_seq[0][0];
                $seq_db->close_connection();
            }
            else {
                logmessage("ERROR", $error);
                $transactionid = mt_rand(1000000000,9999999999);;
            }
            
            logmessage("INFO", "$msisdn; transaction_id: $transactionid");
			
            $opermsisdn = $msisdn;
            $accountid = 1;
            $amount = 500;
            prepay_billing($transactionid, $opermsisdn, $msisdn, $accountid, $amount);
            //echo $error;

            //echo "Got to status 13";
            //$response->status = 13;
            if ($BILLING_RESPONSE->opermsisdn == 'prepaid|SMSC' && $BILLING_RESPONSE->status == 1) {
                //request successful; now check return value
                $_SESSION['billed_url'] = mktime(date("H"), date("i")+ $_SESSION['SESSION_TIMEOUT'], date("s"), date("m"), date("d"), date("Y"));
                $error = "";
                $sub_type = 0;
                write_billed_cdr($short_msisdn, $sub_type);
                logmessage("INFO", "billed_url: ".$BILLING_RESPONSE->status." ".$BILLING_RESPONSE->opermsisdn);
            }
            else if ($BILLING_RESPONSE->opermsisdn == 'postpaid|SMSC') {
                //postpay sub
                $sub_type = 1;
                //attempt postpay bill
                $amount = 5;
                postpay_billing($short_msisdn, $amount);

                if ($BILLING_RESPONSE->STATUS == 'SUCCESS') {
                    //billing successful
                    $_SESSION['billed_url'] = mktime(date("H"), date("i")+ $_SESSION['SESSION_TIMEOUT'], date("s"), date("m"), date("d"), date("Y"));
                    $error = "";
                    write_billed_cdr($short_msisdn, $sub_type);
                    logmessage("INFO", "billed_url: $msisdn; ".$BILLING_RESPONSE->DESCRIPTION);
                }
                else if ($BILLING_RESPONSE->DESCRIPTION == 'PIN_FLD_PROFILE+PIN_ERR_CREDIT_LIMIT_EXCEEDED+exceeds credit limit') {
                    //billing unsuccessful
                    $error = "Sorry, you have insufficient funds to access this service";
                    logmessage("INFO", "billed_url: $msisdn; ".$BILLING_RESPONSE->DESCRIPTION);
                }
                else {
                    $error = "Sorry, this service is temporarily unavailable. Please try again later or contact customer care.";
                    logmessage("WARNING", "billed_url: $msisdn; ".$BILLING_RESPONSE->DESCRIPTION);
                }
            }
            else if ($BILLING_RESPONSE->status == 4) {
                //billing error
                $error = "Sorry, you have insufficient funds to access this service";
                logmessage("WARNING", "billed_url: $msisdn; insufficient funds; $BILLING_RESPONSE->status");
            }
            else if ($BILLING_RESPONSE->status == 9) {
                //attempt to resend transaction
                logmessage("ERROR", "billed_url: $msisdn; $BILLING_RESPONSE->status");
                surf2win_billing();
                /*while ($BILLING_RESPONSE->status == 9) {
                    $transactionid = "0".$short_msisdn;
                    list($usec, $sec) = explode(" ",microtime());
                    $transactionid = $transactionid + ($usec * 1000);
                    $transactionid = substr(round($transactionid), 0, 10);

                    $opermsisdn = $msisdn;
                    $accountid = 1;
                    $amount = 500;
                    prepay_billing($transactionid, $opermsisdn, $msisdn, $accountid, $amount);
                }*/
            }
            else {
                //billing error
                $error = "Sorry, this service is temporarily unavailable. Please try again later or contact customer care.";
                logmessage("ERROR", "billed_url: $msisdn; $BILLING_RESPONSE->status");
            }
        }
        else {
            $error = "Cannot determine your Safaricom phone number; If you are a Safaricom subscriber please refresh the page or try again later. This service is only available to Safaricom subscribers.";
        }
	}

    //set our 'last page visited' variable
    $_SESSION['PHP_SELF'] = $server_php_self;
}

//validate numbers
function digit_verifier($value) {
    if (!ctype_digit($value)) {
        return false;
    }
    else {
        return true;
    }
}

function strip_cdata($string)
{
    preg_match_all('/<!\[cdata\[(.*?)\]\]>/is', $string, $matches);
    return str_replace($matches[0], $matches[1], $string);
}

//query masonko entries
function masonko_query($msisdn) {
        global $MD_USERID, $MD_PASSWORD, $MD_TERMINALID;
        global $MASONKO_RESPONSE, $MASONKO_DATA;

        /** Location of the SOAP endpoint */
       //$endpoint = 'http://172.31.88.56:8080/axis2/services/Masonko?wsdl';
        $endpoint = 'http://172.29.200.183:8211/axis2/services/Masonko?wsdl';
        $params = array ('param0' => $msisdn);   

        /** First step, initialize the SOAP client: */

        /** URL Authentication Method: */
        $client_p = new SoapClient($endpoint);
		
        try {
			//var_dump($client_p->__getFunctions()); 
			$MASONKO_RESPONSE = $client_p->get_data ($params);
			$MASONKO_RESPONSE = strip_cdata($MASONKO_RESPONSE->return);
			$MASONKO_DATA = str_replace("\r\n", "_", $MASONKO_RESPONSE);
			//var_dump(get_object_vars($MASONKO_RESPONSE->response));
			//echo $MASONKO_DATA;
			//die();
			
        } catch (SoapFault $e) {
                logmessage("ERROR", "masonko_query: SoapFault Exception: ".$e->faultstring );
        }    
}
ob_end_flush();
?>