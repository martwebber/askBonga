<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<html>
<!--
   HTML 3.2
   Document type as defined on http://www.w3.org/TR/REC-html32
-->
<head>
       <title>Bonga Partnership</title>
       <link rel="stylesheet" type="text/css" href="css/layout.css" media="screen">
       <link rel="stylesheet" type="text/css" href="css/styles.css" media="screen">
		<script type="text/javascript" src="js/util.js"></script>	
</head>
<div id="banner">SAFARICOM - PCK WEB INTERFACE</div>
<div id="leftcontent">
	<fieldset>
		<legend>Menu</legend>
	PCK * SAF PICS <br />
	<?php
		$array_no_display = array("login.php", "please_login.php");
		if (!in_array(basename($_SERVER['PHP_SELF']), $array_no_display))
			echo "<a href='logout.php'>LOGOUT</a>";
	?>
	</fieldset>
</div>