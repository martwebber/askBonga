<?php
require_once("../nusoaplib/nusoap.php");

$SYSTEMID = 'Web';
$PASSWORD = 'admin';
/*$SYSTEMID = 'Posta';
$PASSWORD = 'admin';*/
$PROXY_PORT = 8080;
$PROXY_HOST = '172.31.160.19';
$SOAP_SERVER_HOST = '172.29.200.180'; //test server
//$SOAP_SERVER_HOST = '172.29.200.164';
$SOAP_SERVER_PORT = '5080';

$lms_login_wsdl = "http://".$SOAP_SERVER_HOST.":".$SOAP_SERVER_PORT."/nusoap/lib/LMS_login.php";
$lms_logout_wsdl = "http://".$SOAP_SERVER_HOST.":".$SOAP_SERVER_PORT."/nusoap/lib/LMS_logout.php";
$lms_enquiry_wsdl = "http://".$SOAP_SERVER_HOST.":".$SOAP_SERVER_PORT."/nusoap/lib/LMS_enquiry.php";
$lms_enroll_wsdl = "http://".$SOAP_SERVER_HOST.":".$SOAP_SERVER_PORT."/nusoap/lib/LMS_subscriber_enrolment.php";
$lms_personal_data_registration_wsdl = "http://".$SOAP_SERVER_HOST.":".$SOAP_SERVER_PORT."/nusoap/lib/LMS_personal_data_registration.php";
$lms_view_personal_data_wsdl = "http://".$SOAP_SERVER_HOST.":".$SOAP_SERVER_PORT."/nusoap/lib/LMS_view_personal_data.php";
$lms_merchandise_redemption_wsdl = "http://".$SOAP_SERVER_HOST.":".$SOAP_SERVER_PORT."/nusoap/lib/LMS_merchandise_redemption.php";
$lms_otp_wsdl = "http://".$SOAP_SERVER_HOST.":".$SOAP_SERVER_PORT."/nusoap/lib/LMS_otp.php";

//============================================= Function Definitions =====================================================//

/*
 * This function logs into the LMS (Loyalty Management System) Server
 * and returns an array...
 * @param
 * 	None
 * @return
 * 	1 dimenstional associative array ie $rcvdArray['RC'], $rcvdArray['MESSAGE'] and $rcvdArray['SESSIONID']
*/
function lms_login()
{
    global $lms_login_wsdl, $PROXY_HOST, $PROXY_PORT;
	global $SYSTEMID, $PASSWORD;

	$SYSTEMID = new soapval('SYSTEMID', "string", $SYSTEMID);
	$PASSWORD = new soapval('PASSWORD', "string", $PASSWORD);
	$params = array('SYSTEMID'=>$SYSTEMID, 'PASSWORD'=>$PASSWORD);
	$params = array($params);	

    //$client = new nusoapclient($lms_login_wsdl, false, $PROXY_HOST, $PROXY_PORT);
    $client = new nusoapclient($lms_login_wsdl, false);
    $rcvdArray = $client->call('LMS_login', $params);
	
	// Display the request and response
	//echo '<h2>Request</h2>';
	//echo '<pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
	//echo '<h2>Response</h2>';
	//echo '<pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';	
	// Display the debug messages
	//echo '<h2>Debug</h2>';
	//echo '<pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';	

	//print_r($rcvdArray);	
	return $rcvdArray;
}



/*
 * This function logs out of the LMS (Loyalty Management System) Server
 * and returns an array...
 * @param
 * 	SESSIONID
 * @return
 * 	1 dimenstional associative array ie $rcvdArray['RC'] and $rcvdArray['MESSAGE']
*/
function lms_logout($session_id)
{
	global $lms_logout_wsdl, $PROXY_HOST, $PROXY_PORT;
    $session_param_id;

    $session_param_id = new soapval('SESSIONID', "string", $session_id);
    $params = array('SESSIONID'=>$session_param_id);
    $params = array($params);

	//$client = new nusoapclient($lms_logout_wsdl, false, $PROXY_HOST, $PROXY_PORT);
    $client = new nusoapclient($lms_logout_wsdl, false);
    $rcvdArray = $client->call('LMS_logout', $params);

    // Display the request and response
    //echo '<h2>Request</h2>';
    //echo '<pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
    //echo '<h2>Response</h2>';
    //echo '<pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
    // Display the debug messages
    //echo '<h2>Debug</h2>';
    //echo '<pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';

    //print_r($rcvdArray);
    return $rcvdArray;
}



function lms_subscriber_enroll($session_id, $msisdn)
{
	global $lms_enroll_wsdl, $PROXY_HOST, $PROXY_PORT;
    $session_param_id;
	$session_param_msisdn;

    $session_param_id = new soapval('SESSIONID', "string", $session_id);
    $session_param_msisdn = new soapval('MSISDN', "string", $msisdn);
    $params = array('SESSIONID'=>$session_param_id, 'MSISDN'=>$session_param_msisdn);
    $params = array($params);

    //$client = new nusoapclient($lms_enroll_wsdl, false, $PROXY_HOST, $PROXY_PORT);
    $client = new nusoapclient($lms_enroll_wsdl, false);
    $rcvdArray = $client->call('LMS_subscriber_enrolment', $params);

    // Display the request and response
    //echo '<h2>Request</h2>';
    //echo '<pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
    //echo '<h2>Response</h2>';
    //echo '<pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
    // Display the debug messages
    //echo '<h2>Debug</h2>';
    //echo '<pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';

    //print_r($rcvdArray);
    return $rcvdArray;		
}

function lms_personal_data_registration($session_id, $msisdn, $name, $surname, $identity_id, $email, $postal_address, $birth_date, $gender, $street_address, $city, $postal_code, $country, $occupation, $dependants, $dependants_details, $msisdn_1, $msisdn_2, $msisdn_3, $msisdn_4, $msisdn_5)
{
	global $lms_personal_data_registration_wsdl, $PROXY_HOST, $PROXY_PORT;

    $session_param_id = new soapval('SESSIONID', "string", $session_id);
    $session_param_msisdn = new soapval('MSISDN', "string", $msisdn);
    $session_param_name = new soapval('NAME', "string", $name);
    $session_param_surname = new soapval('SURNAME', "string", $surname);
    $session_param_identity_id = new soapval('IDENTITY_ID', "string", $identity_id);
    $session_param_email = new soapval('EMAIL', "string", $email);
    $session_param_postal_address = new soapval('POSTAL_ADDRESS', "string", $postal_address);
    $session_param_birth_date = new soapval('BIRTH_DATE', "date", $birth_date);
    $session_param_gender = new soapval('GENDER', "string", $gender);
    $session_param_street_address = new soapval('STREET_ADDRESS', "string", $street_address);
    $session_param_city = new soapval('CITY', "string", $city);
    $session_param_postal_code = new soapval('POSTAL_CODE', "int", $postal_code);
    $session_param_country = new soapval('COUNTRY', "string", $country);
    $session_param_occupation = new soapval('OCCUPATION', "string", $occupation);
    $session_param_dependants = new soapval('DEPENDANTS', "string", $dependants);
    $session_param_dependants_details = new soapval('DEPENDANTS_DETAILS', "string", $dependants_details);
    $session_param_msisdn_1 = new soapval('MSISDN_1', "string", $msisdn_1);
    $session_param_msisdn_2 = new soapval('MSISDN_2', "string", $msisdn_2);
    $session_param_msisdn_3 = new soapval('MSISDN_3', "string", $msisdn_3);
    $session_param_msisdn_4 = new soapval('MSISDN_4', "string", $msisdn_4);
    $session_param_msisdn_5 = new soapval('MSISDN_5', "string", $msisdn_5);
	$params = array('SESSIONID'=>$session_param_id, 
					'MSISDN'=>$session_param_msisdn,
					'NAME'=>$session_param_name,
					'SURNAME'=>$session_param_surname,
					'IDENTITY_ID'=>$session_param_identity_id,
					'EMAIL'=>$session_param_email,
					'POSTAL_ADDRESS'=>$session_param_postal_address,
					'BIRTH_DATE'=>$session_param_birth_date,
					'GENDER'=>$session_param_gender,
					'STREET_ADDRESS'=>$session_param_street_address,
					'CITY'=>$session_param_city,
					'POSTAL_CODE'=>$session_param_postal_code,
					'COUNTRY'=>$session_param_country,
					'OCCUPATION'=>$session_param_occupation,
					'DEPENDANTS'=>$session_param_dependants,
					'DEPENDANTS_DETAILS'=>$session_param_dependants_details,
					'MSISDN_1'=>$session_param_msisdn_1,
					'MSISDN_2'=>$session_param_msisdn_2,
					'MSISDN_3'=>$session_param_msisdn_3,
					'MSISDN_4'=>$session_param_msisdn_4,
					'MSISDN_5'=>$session_param_msisdn_5
					);
    $params = array($params);

    //$client = new nusoapclient($lms_personal_data_registration_wsdl, false, $PROXY_HOST, $PROXY_PORT);
    $client = new nusoapclient($lms_personal_data_registration_wsdl, false);
    $rcvdArray = $client->call('LMS_personal_data_registration', $params);

    // Display the request and response
    //echo '<h2>Request</h2>';
    //echo '<pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
    //echo '<h2>Response</h2>';
    //echo '<pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
    // Display the debug messages
    //echo '<h2>Debug</h2>';
    //echo '<pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';

    //print_r($rcvdArray);
    return $rcvdArray;		
}

function lms_view_personal_data($session_id, $msisdn)
{
        global $lms_view_personal_data_wsdl, $PROXY_HOST, $PROXY_PORT;
        $session_param_id;
        $session_param_msisdn;

    	$session_param_id = new soapval('SESSIONID', "string", $session_id);
        $session_param_msisdn = new soapval('MSISDN', "string", $msisdn);
        $params = array('SESSIONID'=>$session_param_id, 'MSISDN'=>$session_param_msisdn);
        $params = array($params);

        $client = new nusoapclient($lms_view_personal_data_wsdl, false);
        $rcvdArray = $client->call('LMS_view_personal_data', $params);

	//print_r($rcvdArray);
        return $rcvdArray;
}


function lms_enquiry($session_id, $msisdn)
{
	global $lms_enquiry_wsdl, $PROXY_HOST, $PROXY_PORT;
    $session_param_id;
    $session_param_msisdn;

    $session_param_id = new soapval('SESSIONID', "string", $session_id);
    $session_param_msisdn = new soapval('MSISDN', "string", $msisdn);
    $params = array('SESSIONID'=>$session_param_id, 'MSISDN'=>$session_param_msisdn);
    $params = array($params);

    //$client = new nusoapclient($lms_enquiry_wsdl, false, $PROXY_HOST, $PROXY_PORT);
    $client = new nusoapclient($lms_enquiry_wsdl, false);
    $rcvdArray = $client->call('LMS_enquiry', $params);

    // Display the request and response
    //echo '<h2>Request</h2>';
    //echo '<pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
    //echo '<h2>Response</h2>';
    //echo '<pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
    // Display the debug messages
    //echo '<h2>Debug</h2>';
    //echo '<pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';

    //print_r($rcvdArray);
    return $rcvdArray;		
}

function lms_otp($session_id, $msisdn, $delivery, $shop_user, $notes)
{
	global $lms_otp_wsdl;

	$session_param_id = new soapval('SESSIONID', 'string', $session_id);
	$session_param_msisdn = new soapval('MSISDN', 'string', $msisdn);
	$session_param_delivery = new soapval('DELIVERY', 'string', $delivery);
	$session_param_shop_user =  new soapval('SHOP_USER', 'string', $shop_user);
	$session_param_notes = new soapval('NOTES', 'string', $notes);

	$params = array('SESSIONID'=>$session_param_id, 'MSISDN'=>$session_param_msisdn, 'DELIVERY'=>$session_param_delivery, 'SHOP_USER'=>$session_param_shop_user, 'NOTES'=>$session_param_notes);
	$params = array($params);

	$client = new nusoapclient($lms_otp_wsdl, false);
	$rcvdArray = $client->call('LMS_otp', $params);

	return $rcvdArray;
}

function lms_merchandise_redemption($session_id, $msisdn, $redeem_points, $customer_otp, $merchandise_notes, $shop_user)
{
        global $lms_merchandise_redemption_wsdl;

    	$session_param_id = new soapval('SESSIONID', "string", $session_id);
        $session_param_msisdn = new soapval('MSISDN', "string", $msisdn);
        $session_param_redeem_points = new soapval('REDEEM_POINTS', "int", $redeem_points);
        $session_param_customer_otp = new soapval('CUSTOMER_OTP', "string", $customer_otp);
        $session_param_merchandise_notes = new soapval('MERCHANDISE_NOTES', "string", $merchandise_notes);
        $session_param_shop_user = new soapval('SHOP_USER', "string", $shop_user);
        $params = array('SESSIONID'=>$session_param_id, 'MSISDN'=>$session_param_msisdn, 'REDEEM_POINTS'=>$session_param_redeem_points,
		'CUSTOMER_OTP'=>$session_param_customer_otp,'MERCHANDISE_NOTES'=>$session_param_merchandise_notes,'SHOP_USER'=>$session_param_shop_user);
        $params = array($params);

        $client = new nusoapclient($lms_merchandise_redemption_wsdl, false);
        $rcvdArray = $client->call('LMS_merchandise_redemption', $params);

	//print_r($rcvdArray);
        return $rcvdArray;
}
//============================================= End of Function Definitions ================================================//




//===================================================== Test Cases  ========================================================//
/*
$session_id = "";
$msisdn = "721214949";
$name = "Eric";
$surname = "Mokaya";
$identity_id = "";
$email = "eric@yahoo.com";
$postal_address = "Address, Nairobi, Kenya.";
$birth_date = "20060909";
$gender = "M";
$street_address = "Upper Hill Road";
$city = "Nairobi";
$postal_code = "00200";
$country = "Kenya";
$occupation = "Astronaut";
$dependants = "Y";
$dependants_details = "Baby";
$msisdn_1 = "";
$msisdn_2 = "";
$msisdn_3 = "";
$msisdn_4 = "";
$msisdn_5 = "";

//echo "Testing lms_login...
$array_login_response = lms_login();
$session_id = $array_login_response["SESSIONID"];
echo "The Session ID returned by lms_login is: ".$session_id."<br><br>";

//delay for some 5 seconds to give time for session to be created...
sleep(2);

//Testing subscriber enrollment... 
$array_enroll_response = lms_subscriber_enroll($session_id, $msisdn);
echo "Subscriber enrollment Response Code is: ".$array_enroll_response["RC"]."<br>";
echo "Subscriber enrollment Response Message is: ".$array_enroll_response["MESSAGE"]."<br><br>";

//Testing personal data registration... 
$array_registration_response = lms_personal_data_registration($session_id, $msisdn, $name, $surname, $identity_id, $email, $postal_address, $birth_date, $gender, $street_address, $city, $postal_code, $country, $occupation, $dependants, $dependants_details, $msisdn_1, $msisdn_2, $msisdn_3, $msisdn_4, $msisdn_5);
echo "Subscriber Personal Data Registration Response Code is: ".$array_registration_response["RC"]."<br>";
echo "Subscriber Personal Data Registration Response Message is: ".$array_registration_response["MESSAGE"]."<br><br>";

//Testing subscriber enquiry...
$array_enquiry_response = lms_enquiry($session_id, $msisdn);
echo "Subscriber enquiry Response Code is: ".$array_enquiry_response["RC"]."<br>";
echo "Subscriber enquiry Response Message is: ".$array_enquiry_response["MESSAGE"]."<br><br>";
echo "Subscriber enquiry Loyalty Points is: <b>".$array_enquiry_response["LOYALTY_POINTS"]."</b><br><br>";

//Testing lms_logout...
$array_logout_response = lms_logout($session_id);
echo "Logout Response Code for $session_id is: ".$array_logout_response["RC"]."<br>";
echo "Logout Response Message for $session_id is: ".$array_logout_response["MESSAGE"]."<br><br>";

//===================================================== End of Test Cases  =================================================//
*/

?>
