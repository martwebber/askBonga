<?php 
 require_once("nusoaplib/nusoap.php");
 
$SYSTEMID = 'Web';
$PASSWORD = 'admin';
$PROXY_PORT = 8080;
$PROXY_HOST = '172.31.160.19';
$SOAP_SERVER_HOST = '172.29.200.164';
$SOAP_SERVER_PORT = '5080';

$lms_login_wsdl = "http://".$SOAP_SERVER_HOST.":".$SOAP_SERVER_PORT."/nusoap/lib/LMS_login.php";
$lms_logout_wsdl = "http://".$SOAP_SERVER_HOST.":".$SOAP_SERVER_PORT."/nusoap/lib/LMS_logout.php";
$lms_personal_data_registration_wsdl = "http://".$SOAP_SERVER_HOST.":".$SOAP_SERVER_PORT."/nusoap/lib/LMS_personal_data_registration.php";
$lms_merchandise_redemption = "http://".$SOAP_SERVER_HOST.":".$SOAP_SERVER_PORT."/nusoap/lib/LMS_merchandise_redemption";

/* * This function logs into the LMS (Loyalty Management System) Server
 * and returns an array... 
 * @param 
 *      None 
 * @return 
 *      1 dimenstional associative array ie $rcvdArray['RC'], $rcvdArray['MESSAGE'] and $rcvdArray['SESSIONID']*/
function lms_login()
{    
	global $lms_login_wsdl, $PROXY_HOST, $PROXY_PORT;        
	global $SYSTEMID, $PASSWORD;
	
	$SYSTEMID = new soapval('SYSTEMID', "string", $SYSTEMID);        
	$PASSWORD = new soapval('PASSWORD', "string", $PASSWORD);        
	$params = array('SYSTEMID'=>$SYSTEMID, 'PASSWORD'=>$PASSWORD);        
	$params = array($params);
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

/* * This function logs out of the LMS (Loyalty Management System) Server
 * and returns an array...
 * @param
 *      SESSIONID
 * @return
 *      1 dimenstional associative array ie $rcvdArray['RC'] and $rcvdArray['MESSAGE']
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


?>