<?php session_start();
ob_start();

	include "../util/functions.php";

	//log logout
	$message = "SITE LOGOUT - User ID: ".$_SESSION['USERID'];
	logmessage("INFO", $message);

	//close session
	unset($_SESSION['USERID']);
	unset($_SESSION['SECURITY_LEVEL']);
	session_destroy();
	header("Location: login.php");
ob_end_flush();
?>