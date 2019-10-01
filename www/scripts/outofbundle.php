<?php
	require_once "../util/functions.php";

	$error = ""; 
	
	
     //first check if user is logged in
     $please_login_url = "please_login.php";
	 $status = check_logged_in($please_login_url);
		
	if ($status == 1) { 
	
	insert_header2();
	
  class prepare_request{  

   public function fsThis($msisdn)
    {   
        $req='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ws="http://ws.psd.safaricom.com/">
		   <soapenv:Header/>
		   <soapenv:Body>
			  <ws:getOutOfBundleInfo>
				  <msisdn>'.$msisdn.'</msisdn>
			  </ws:getOutOfBundleInfo>
		   </soapenv:Body>
	</soapenv:Envelope>';
        
		$fp = @fsockopen("10.184.18.24", 9090, $errno, $errstr, 20); 
        if (!$fp) { $rtn = FALSE; 
                            } 
           else {                
                      //echo $req;
                 
            fputs($fp, "POST http://10.184.18.24:9090/OnlineServices5/OnlineServices HTTP/1.1\r\n");
            fputs($fp, "Host: 10.184.18.24\r\n");
            fputs($fp, "Content-type: text/xml;charset=UTF-8\r\n");
            fputs($fp, "SOAPAction: \"\"\r\n");
            fputs($fp, "Content-length: " . strlen($req) . "\r\n");
            fputs($fp, "User-agent: WallaceZilla1.1\r\n");
            fputs($fp, "Connection: close\r\n\r\n");
            fputs($fp, $req);
			 
		
			 
            $buf = "";
            while (!feof($fp)) 
            {
               $buf .= fgets($fp,128);
            }
            fclose($fp); $rtn = $buf;
           }
		 
       return $rtn;
	       

    }   
   
 }
 
if (isset($_POST['msisdn'])) {
 
				$msisdn= trim($_POST['msisdn']);
				
				$msisdn= substr($msisdn,-9);
				
				$sub=array();	
	
				$user=new prepare_request();			
				
			
				$resp=$user->fsThis($msisdn);

				$dom = new DOMDocument;
				
				$resp = substr($resp, stripos($resp, "<?xml version='1.0' encoding='UTF-8'?>")+ 38);
				$resp = substr($resp, 0, stripos($resp, "</S:Envelope>") + 13);
				$dom->loadXML($resp);
				$sub['CANUSEMONEYINDATAP'] = $dom->getElementsByTagName('CANUSEMONEYINDATAP')->item(0)->nodeValue;
				$sub['CANUSEMONEYINDATAT'] = $dom->getElementsByTagName('CANUSEMONEYINDATAT')->item(0)->nodeValue;
				$sub['MODIFYPFLAGTIME'] = $dom->getElementsByTagName('MODIFYPFLAGTIME')->item(0)->nodeValue;
				
				$tap=$sub['CANUSEMONEYINDATAP'];
				//$tat=$sub['CANUSEMONEYINDATAT'];
				$time=$sub['MODIFYPFLAGTIME'];
				
				if ($tap == 1){
					$tap = "Out Of Bundle Management ACTIVATED";
					$error	= "Customer No: ".$_POST['msisdn']." ||  Status: ".$tap." || Activation Date: ".$time;	
				
				}elseif($tap == 2){
					$tap = "Out Of Bundle Management DEACTIVATED";
					$error	= "Customer No: ".$_POST['msisdn']." ||  Status: ".$tap." || Deactivation Date: ".$time;	
				}else{
					$error	= "Customer No: ".$_POST['msisdn']." ||  Status: Out Of Bundle Management NOT SET";	
				}			
				
				//echo $error;				
		
	
	}   

  

?>

<div class="cspacer">
	<?php
		if ($error !=  "") {
			echo "<center>\r\n";
			echo "<div style='text-align: left; width: 80%'>\r\n";
			echo "<table class='tablebody border' width='100%'>\r\n";
			echo "<th class='tableheader'>MESSAGE</th>\r\n";
			echo "<tr><td>\r\n";
			echo "<p class='error'>".$error."</p>";
			echo "</td></tr>\r\n";
			echo "</table>\r\n";				
			echo "<br /><br />\r\n";
			echo "</div>\r\n";
			echo "</center>\r\n";
		}
	?>
	<div align="center" style="text-align:left; width:100%">

		<form name='acc_history_list' action='outofbundle.php' method='post'>
		<center>
		<table class="tablebody border" width="80%">
			<tr>
				<th colspan="2" class="tableheader"><b>MSISDN SEARCH</b></th>
			</tr>
			<tr>
				<td><br />MSISDN:</td><td><br /><input type='text' name='msisdn' /></td>
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
				</form>
		<?php
			}
		?>
	</div>

<!-- insert the footer -->
<?php
	insert_footer2();
	
	}else{
	
		header("Location: please_login.php");
	}
?>								
