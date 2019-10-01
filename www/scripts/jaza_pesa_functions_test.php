<?php
$username = 'wanyonyi';
$password = '3$ichja#7HrT$!BY';
// $path = dirname(__DIR__) .DIRECTORY_SEPARATOR."jazaPesaWsdl.wsdl"; // old way 
// new way
$path = dirname(__DIR__) .DIRECTORY_SEPARATOR."jazaPesaWsdl.wsdl";
// $url = "http://10.184.38.63:2020/jaza-pesa"; // old port for old wsdl
// new way
$url = "https://10.184.38.63:2050/jaza-pesa";
$context = stream_context_create(array(
		    'ssl' => array(
		        // set some SSL/TLS specific options
		        'verify_peer' => false,
		        'verify_peer_name' => false,
		        'allow_self_signed' => true
		    )
		));

function checkCustmerStatus($msisdn){
	global $username, $password, $path, $url, $context;
	
	try {
		
		$client = new SoapClient($path, array('location'=>$url, 'trace'=>true, 'exceptions'=>true, 'stream_context' => $context)); // , 'stream_context' => $context
		// $login = $client->performLogin($username, $password);
        $response = $client->CheckCustomerStatus(array('MSISDN'=> $msisdn, 'UserID'=>$username, 'Password'=>$password));

        return $response;
        

	} catch (Exception $e) {
		echo "Error Here " .$e->getMessage();
	}
}

function checkWinnings($msisdn){
	global $username, $password, $path, $url, $context;
	try {
		$client = new SoapClient($path, array('location'=>$url, 'trace'=>true, 'exceptions'=>true, 'stream_context' => $context));
        $response = $client->CheckWinnings(array('MSISDN'=> $msisdn, 'Channel'=>'BongaLink', 'UserID'=>$username, 'Password'=>$password));
        
        //var_dump($response);

        //echo "Fullnames: " . $response->FullNames ."<br />";

        //$rewards = $response->Rewards->Reward;
        //var_dump($rewards);

        // foreach ($rewards as $key => $r) {
        // 	// var_dump($r);
        	
        // 	echo "DateRewarded " . $r->DateRewarded . "<br>";
        // 	echo "M-Pesa ID" . $r->MpesaTransactionID . "<br><br>";
        // }
        return $response;
        // echo "Desc: " . $response->{'G2Result'}->{'responseDesc'} ."<br />";

	} catch (Exception $e) {
		echo $e->getMessage();
	}
}

function customerHistory($msisdn){
	global $username, $password, $path, $url, $context;
	try {
		$client = new SoapClient($path, array('location'=>$url, 'trace'=>true, 'exceptions'=>true, 'stream_context' => $context));
        $response = $client->CustomerHistory(array('MSISDN'=> $msisdn, 'UserID'=>$username, 'Password'=>$password));
        
        return $response;

	} catch (Exception $e) {
		echo $e->getMessage();
	}
}

function toggleOptIn($msisdn, $flag){
	global $username, $password, $path, $url, $context;
	try {
		$client = new SoapClient($path, array('location'=>$url, 'trace'=>true, 'exceptions'=>true, 'stream_context' => $context));
        $response = $client->ToggleOptIn(array('MSISDN'=> $msisdn, 'Flag'=>$flag, 'Channel'=>'BongaLink', 'UserID'=>$username, 'Password'=>$password));
        
        return $response;

	} catch (Exception $e) {
		echo $e->getMessage();
	}
}

function checkTarget($msisdn){
	global $username, $password, $path, $url, $context;
	try {
		$client = new SoapClient($path, array('location'=>$url, 'trace'=>true, 'exceptions'=>true, 'stream_context' => $context));
        $response = $client->CheckTarget(array('MSISDN'=> $msisdn, 'Channel'=>'BongaLink', 'UserID'=>$username, 'Password'=>$password));
      
        return $response;
        
	} catch (Exception $e) {
		echo $e->getMessage();
	}
}

function CustomerAccumulationHistory($msisdn){
	global $username, $password, $path, $url, $context;
	try {
		$client = new SoapClient($path, array('location'=>$url, 'trace'=>true, 'exceptions'=>true, 'stream_context' => $context));
        $response = $client->CustomerAccumulationHistory(array('MSISDN'=> $msisdn, 'UserID'=>$username, 'Password'=>$password));
      
        return $response;
        
	} catch (Exception $e) {
		echo $e->getMessage();
	}
}

function check($msisdn){
	global $username, $password, $path, $url, $context;
	try {
		$client = new SoapClient($path, array('location'=>$url, 'trace'=>true, 'exceptions'=>true, 'stream_context' => $context));
        $response = $client->CheckCustomerStatus(array('MSISDN'=> $msisdn, 'UserID'=>$username, 'Password'=>$password));
        //print_r($response);
        //var_dump($response);
        echo "Flag: " . $response->Flag ."<br />";
        // echo "Desc: " . $response->{'G2Result'}->{'responseDesc'} ."<br />";

	} catch (Exception $e) {
		echo $e->getMessage();
	}
}

function rewardsLoop($msisdn){
	global $username, $password, $path, $url, $context;
	try {
		$client = new SoapClient($path, array('location'=>$url, 'trace'=>true, 'exceptions'=>true, 'stream_context' => $context));
        $response = $client->CheckWinnings(array('MSISDN'=> $msisdn, 'Channel'=>'BongaLink', 'UserID'=>$username, 'Password'=>$password));
        
        //var_dump($response);

        //echo "Fullnames: " . $response->FullNames ."<br />";

        $rewards = $response->Rewards->Reward;
        //var_dump($rewards);

        // foreach ($rewards as $key => $r) {
        // 	// var_dump($r);
        	
        // 	echo "DateRewarded " . $r->DateRewarded . "<br>";
        // 	echo "M-Pesa ID" . $r->MpesaTransactionID . "<br><br>";
        // }
        return $response;
        // echo "Desc: " . $response->{'G2Result'}->{'responseDesc'} ."<br />";

	} catch (Exception $e) {
		echo $e->getMessage();
	}
}