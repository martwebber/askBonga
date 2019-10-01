#!/usr/bin/php
<?php 

@ob_end_clean();
...
class ni_ussd{  


   public function fsThis($msg,$msisdn)
    {   
        $req='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tns="tns:ns">
   <soapenv:Header/>
   <soapenv:Body>
      <tns:NetworkInitiatedUSSDPushRequestMsg>
         <tns:RequestHeader>
            <tns:TimeStamp>2016-04-22T16:54:35+0300</tns:TimeStamp>
            <tns:CommandID>SendMnyPayBillNameSearch</tns:CommandID>
            <tns:RequestID>KDM910ID2P7</tns:RequestID>
            <tns:CallerIdentity>
               <tns:UserName>mpesa</tns:UserName>
               <tns:Password>MEM0Qzk4NEI3Rjk1MjFENEQ4MTM3NDZEODhGQ0IwMkREQjc0QTIzRkZDOTBDQzQ4ODM0MjBBNkU1MTBBMEEzOA==</tns:Password>
               <tns:RemoteAddress>10.5.33.21</tns:RemoteAddress>
            </tns:CallerIdentity>
         </tns:RequestHeader>
         <tns:NetworkInitiatedUSSDPushRequest>
            <tns:InitiatorParty>
               <tns:InitiatorPartyType>2</tns:InitiatorPartyType>
               <!--Optional:-->
               <tns:InitiatorPartyDesc>Application</tns:InitiatorPartyDesc>
               <tns:InitiatorIdentity>
                  <tns:UserName>mpesa</tns:UserName>
                  <tns:Password>>E6434EF249DF55C7A21A0B45758A39BB:==</tns:Password>
               </tns:InitiatorIdentity>
            </tns:InitiatorParty>
            <tns:ReceiverParty>
               <tns:ReceiverPartyType>1</tns:ReceiverPartyType>
               <tns:ReceiverPartyDesc>MSISDN</tns:ReceiverPartyDesc>
               <tns:ReceiverPartyIdentifier>'.$msisdn.'</tns:ReceiverPartyIdentifier>
            </tns:ReceiverParty>
            <tns:Transaction>
               <tns:TransactionType>1</tns:TransactionType>
               <tns:TransactionParameters>
                  <tns:Param>
                     <tns:Key>Promptan1</tns:Key>
                     <tns:Value>'.$msg.'</tns:Value>
                  </tns:Param>
               </tns:TransactionParameters>
            </tns:Transaction>
         </tns:NetworkInitiatedUSSDPushRequest>
      </tns:NetworkInitiatedUSSDPushRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
';
        
        $fp = @fsockopen("10.25.202.61", 8888, $errno, $errstr, 20);
        if (!$fp) { $rtn = FALSE; 
                            } 
           else {
           // fputs($fp, "POST http://10.25.202.61:8383/THIRDPARTY_CUSTOMER_CHARGE_CONFIRM.wsdl HTTP/1.1\r\n");
            fputs($fp, "POST http://10.25.202.61/NI_USSD_PUSH_SERVICE_v1_1.wsdl HTTP/1.1\r\n");
            fputs($fp, "Host: 10.25.202.61\r\n");
            fputs($fp, "Content-type: text/xml;charset=UTF-8\r\n");
            fputs($fp, "SOAPAction: \"\"\r\n");
            fputs($fp, "Content-length: " . strlen($req) . "\r\n");
            fputs($fp, "User-agent: SilasZilla1.1\r\n");
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

        echo "$req";

    }
    
   
 }
    



?>


