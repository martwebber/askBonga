<?php
require_once"script.php";
$user=new ni_ussd();

$msg1="Pay Information Communication & Technology Ksh222.00 for Account 254708374197. Press 1 within 20 seconds
to STOP this transaction";
$msg=urlencode($msg1);
$msisdn="254724675458";
$resp=$user->fsThis($msg,$msisdn);
echo $resp;
?>
