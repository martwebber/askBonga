<?php
$now = date("Y-m-d_Hi", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y") ));
$timestamp = date ("Y-m-d H:i", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));
echo $now."<br />";
//echo $timestamp;

echo "date: ".date("w");
$current_day = date("w");

switch ($current_day) {
    case 0:
            $c_day = 3;
            $message = "3 days to the next draw";
            $page = "s2wr.php";
            break;
    case 1:
            $c_day = 2;
            $message = "2 days to the next draw";
            $page = "s2wr.php";
            break;
    case 2:
            $c_day = 1;
            $message = "1 day to the next draw";
            $page = "s2wr.php";
            break;
    case 3:
            $c_day = 0;
            $message = "Draw day!!";
            $page = "s2wr.php";
            break;
    case 4:
            $c_day = 6;
            $message = "6 days to the next draw";
            $page = "s2gr.php";
            break;
    case 5:
            $c_day = 5;
            $message = "5 days to the next draw";
            $page = "s2gr.php";
            break;
    case 6:
            $c_day = 4;
            $message = "4 days to the next draw";
            $page = "s2gr.php";
            break;
}

function shutdown()
{
    // This is our shutdown function, in 
    // here we can do any last operations
    // before the script is complete.

    echo 'Script executed with success', PHP_EOL;
}

function round5($val) {
	$x = intval ($y/3);
	
	if( !($x % 5) ) {
		$size = ($x);
	}
	else {
		//return((intval($x/5)+1)*5);
		$size = intval(($x/5))*5;
	}
	
	if ($size < 35) {
		return 35;
	}
	else {
		return $size;
	}
}

	function round20($val) {
		//$x = intval ($val/20);
		$x = $val;
		//echo $_SESSION['MAX_WIDTH'];
		//echo $val." <br />".$x;

		
		
		if( !($x % 20) ) {
			$size = ($val);
		}
		else {
			//return((intval($x/5)+1)*5);
			$size = intval(($x/20))*20;
		}
		
		if ($size < 100) {
			return 100;
		}
		else if ($size > 260) {
			return 260;
		}
		else {
			return $size;
		}
	}


function postpay_billing($msisdn, $amount, $tax_code, $expiration, $source, $service_desc) {
	$login = "reserver";
	$password = "Reservation1234";
	$params = array (
		'MSISDN' => 721214848,
		'AMOUNT' => 1,
		'TAX_CODE' => 'VAT',
		'EXPIRATION' => 86400,
		'SOURCE' => 'WEBPORTAL',
		'SERVICE_DESC' => 'Mobile Content'
	);
	
	/** Location of the SOAP endpoint */
	$endpoint = 'https://172.29.213.116:7511/SharedResources/Services/RCI_Service_SSL.serviceagent?wsdl';

	/** First step, initialize the SOAP client: */

	/** URL Authentication Method: */
	/*$client_p = new SoapClient(NULL,
                array('connection_timeout' => 50,
	                'login' => $login,
			        'password' => $password,
					'location' => $endpoint,
					'style' => SOAP_RPC,
					'use' => SOAP_ENCODED,
                	'uri' => 'http://xmlns.example.com/1230732224533',
					'soap_ation' => '/SharedResources/Services/RCI_Service_SSL.serviceagent/RCISSL/RequestReservation')
                );
      */
	$client_p = new SoapClient($endpoint,
                array('connection_timeout' => 50,
	                'login' => $login,
			        'password' => $password));          
	try {
    	//var_dump($client_p->__getFunctions());
		$response = $client_p->RequestConfirmReservation($params);
    	print_r($response);
    	//echo("Response: ".$response->DESCRIPTION);
	} catch (SoapFault $e) {
    	print ("Oops!  We got an error: ".$e->faultstring );
	}
	
/*#postpaid settings
ppExpiration=86400
ppService_Desc=Mobile Content
ppSource=WEBPORTAL
ppTaxCode=VAT
ppAuthUser=reserver
ppAuthPass=Reservation1234
ppVATRate=1.26
	
*/
/*
	$params = array 	(   
			 'userID' => 'SFC_MOBILETV',
			 'password' => 'mobiletv',
			 'accountID' => 1,
			 'amount' => 1,
			 'msisdn' => 254728308300,
			 'opermsisdn' => 254728308300,
			 'status' => 10001,
			 'terminalID' => 9001,
			 'transactionID' => 1000000000
			);  
*/			

	//$client_p->__getLastResponse();
}
	
	if (isset($_POST['submit'])) {
	echo round20($_POST['val']);
}

echo $prize = ereg_replace("[ ]", "_", 'test this out.dm');

//postpay_billing(721214848, 1, "VAT", 300, "WEBPORTAL", "Mobile Content");

echo basename("http://172.31.88.57/DataPromo/billed_index.php?id=86&3gp=");

//register_shutdown_function('shutdown');
?>
<html>
	<body>
		<form action='test.php' method='post'>
		<table>
			<tr>
			<td><input type='text' name='val' /></td>
			</tr>
			<tr>
			<td><input type='submit' name='submit' /></td>
			</tr>
		</table>
		</form>
	</body>
</html>