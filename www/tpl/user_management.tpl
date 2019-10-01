<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<html>
<!--
   HTML 3.2
   Document type as defined on http://www.w3.org/TR/REC-html32
-->
<head>
		<title>LG - TWENDE BALL</title>
		<link rel="stylesheet" type="text/css" href="css/better-layout.css" media="screen">
		<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen">
		<style type="text/css">@import url(jscalendar/skins/aqua/theme.css);</style>
		<script type="text/javascript" src="js/util.js"></script>
		<script type="text/javascript" src="jscalendar/calendar.js"></script>
		<script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>
		<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
</head>
<body>
<div id="container">
	<div id="header" style='background:transparent url(images/topbar_rhs2.jpg) repeat-x'>
		<img src='images/topbar.jpg' />
	</div>
	<div id="content-wrap">
		<?php
			$page_name = basename($_SERVER['PHP_SELF']);
			$array_no_display = array("login.php", "please_login.php");
		?>
		<div id="sidebar-right">
			<?php
				if (!in_array($page_name, $array_no_display)) {
			?>
			<br /><a href='change_password.php'>Change Password</a> <br /><br />
			<?php
				}
			?>
		</div>
		<div id="sidebar-left">
			<?php
				if (!in_array($page_name, $array_no_display)) {
			?>
			<br /><a href='home.php'>Home</a> <br /><br />
			<a href='run_draw.php'>Run Draw</a> <br /><br />
			<a href='generate_report.php'>Generate Report</a> <br /><br />
			<a href='download_list.php'>Download Report</a> <br /><br />
			<br /><br />
			<a href="logout.php">LOGOUT</a><br /><br />
			<?php
				}
			?>
		</div>
		<div id="center">
			<div id="center-in">
			</div>
		</div>
	</div>
</div>
</body>
</html>