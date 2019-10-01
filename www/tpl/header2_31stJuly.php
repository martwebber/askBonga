<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<html>
<!--
   HTML 3.2
   Document type as defined on http://www.w3.org/TR/REC-html32
-->
<head>
		<title>Unlimited Data Promotion - Admin Site</title>
		<link rel="stylesheet" type="text/css" href="../styles/better-layout.css" media="screen">
		<link rel="stylesheet" type="text/css" href="../styles/styles.css" media="screen">
		<style type="text/css">@import url(../jscalendar/skins/aqua/theme.css);</style>
		<script type="text/javascript" src="../js/util.js"></script>
		<script type="text/javascript" src="../jscalendar/calendar.js"></script>
		<script type="text/javascript" src="../jscalendar/lang/calendar-en.js"></script>
		<script type="text/javascript" src="../jscalendar/calendar-setup.js"></script>
</head>
<body>
<div id="container">
	<div id="header">
		<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" align="center">
        <tbody>
            <tr>
                <td width="223" valign="top" align="left">
                    <img width="324" height="37" src="../images/intranet_header.gif"/>
                </td>
                <td width="340" valign="top" align="left"> </td>
                <td valign="top" align="left">
                    <img width="345" height="43" src="../images/1_logo_better_option.gif"/>
                </td>
            </tr>
        </tbody>
        </table>
	</div>
	<div id="content-wrap">
        <img width="100%" height="103" src="../images/header_home.jpg"/>
		<?php
			$page_name = basename($_SERVER['PHP_SELF']);
			$array_no_display = array("login.php", "please_login.php", "forgot_password.php");
		?>
		<div id="sidebar-right">	
			<?php
				if (!in_array($page_name, $array_no_display) && isset($_SESSION['USERID'])) {
			?>
                <br /><a href='change_password.php'>Change Password</a> <br /><br />
			<?php
					if ($_SESSION['SECURITY_LEVEL'] == 0) {
						echo "<a href='user_management.php'>User Management</a> <br /><br />";
					}
					if ($_SESSION['SECURITY_LEVEL'] == 0 || $_SESSION['SECURITY_LEVEL'] == 3) {
						echo "<a href='award_pcrf_bundle.php'>Award Bundle</a> <br /><br />";
					}
				}
			?>		
		</div>
		<div id="sidebar-left">
			<?php
				if (!in_array($page_name, $array_no_display)) {
					if ($_SESSION['SECURITY_LEVEL'] < 5) {
			?>
			<!--<br /><a href='home.php'>Home</a> <br /><br />
			<p style='font-weight:bold;font-size:15px'>Prepay Bundles</p><br />
			<a href='check_bundles_history.php'>Check Bundles History(IN)</a> <br /><br />
			<a href='check_pcrf_bundles_history_h.php'>Check Bundles History(PCRF) - Older</a> <br /><br />
			<a href='check_pcrf_bundles_history_t.php'>Check Bundles History(PCRF) - Newer</a> <br /><br />-->
            		<!--<a href='award_adhoc_bundle.php'>Compensate Bundle</a> <br /><br />
            		Compensate Bundle<br /><br />
			<br /><br />
			<p style='font-weight:bold;font-size:15px'>Daily 10MB Bundle</p><br />	
			-<a href='check_history.php'>Check Subscription History</a> <br /><br />
			<a href='check_history.php'>Check Subscription History</a> <br /><br />
			<a href='check_subscriber.php'>Query Subscription</a> <br /><br />
			<a href='subscribe.php'>Subscribe</a> <br /><br />
			<a href='unsubscribe.php'>Unsubscribe</a> <br /><br />
			<br /><br />
			<p style='font-weight:bold;font-size:15px'>Masaa Ya SMS</p><br />	
			<a href='check_sms_bundles_subscription.php'>Query Subscription</a> <br /><br />
			<a href='sms_subscribe.php'>Subscribe</a> <br /><br />
			<a href='sms_unsubscribe.php'>Unsubscribe</a> <br /><br /> -->
			<?php
				}
			?>
			
			<br /><br />
		    <p style='font-weight:bold;font-size:15px'>M-PESA Partner Statements</p><br />	
			<a href='mpesa_partner_statements_check_registration.php'>Query Partner Registration Details</a> <br /><br />
			<a href='mpesa_partner_statements_check_activity.php'>Query Partner Activity</a> <br /><br />
			<a href='mpesa_partner_statements_requests.php'>Query Partner Request Details</a> <br /><br />
			<a href='mpesa_partner_statements_resend.php'>Resend Partner Statement</a> <br /><br />
			<a href='mpesa_partner_statements_deactivate_sub.php'>De-activate Partner Subscription</a> <br /><br />
			
			<br /><br />
			<p style='font-weight:bold;font-size:15px'>Changa na Bonga Points</p><br />	
			<!--<a href='check_bonga_history.php'>Query Transactions</a> <br /><br />-->
			<a href='reset_bonga_pin.php'>Reset Bonga PIN</a> <br /><br />
			
				<br /><br /> 
			<p style='font-weight:bold;font-size:15px'>ShowMax Status Query</p><br />	
			<a href='showmax_status_query.php'>Query Status</a> <br /><br />
			
				<br /><br /> 
			<p style='font-weight:bold;font-size:15px'>ShowMax Promotion Query</p><br />	
			<a href='showmax_promotion.php'>Query Status</a> <br /><br />
			
				<br /><br /> 
			<p style='font-weight:bold;font-size:15px'>Happy Hour Whitelist Query</p><br />	
			<a href='happyhour_query_whitelist.php'>Query Status</a> <br /><br />
			
				<br /><br />
			<p style='font-weight:bold;font-size:15px'>RedCross Bundle Promotion</p><br />	
			<a href='redcross_promotion.php'>Query Status</a> <br /><br />
			<a href='redcross_promotion1.php'>View all downloads</a> <br /><br />

					<br /><br /> 
			<p style='font-weight:bold;font-size:15px'>Blaze Whitelist Query</p><br />	
			<a href='advantage_plus_status_query.php'>Query Whitelist</a> <br /><br />	
	
				<br /><br /> 
			<p style='font-weight:bold;font-size:15px'>Advantage Plus Status Query</p><br />	
			<a href='advantage_plus_status_query.php'>Query Status</a> <br /><br />		
			
				<br /><br />				
			<p style='font-weight:bold;font-size:15px'>Lipa na Fuel Promotion</p><br />	
			<a href='lnm_fa_query_customer_history.php'>Query Customer History Details</a> <br /><br />
			<a href='lnm_fa_query_airtime_status.php'>Query Customer Airtime Status</a> <br /><br />
			

			<br /><br /> 
			<p style='font-weight:bold;font-size:15px'>Data Manager Status Query</p><br />	
			<a href='outofbundle.php'>Query Status</a> <br /><br />	

			<br /><br /> 
			<p style='font-weight:bold;font-size:15px'>Mpesa Agent Reversals(2530) Query</p><br />	
			<a href='mpesa_agent_reversal_query.php'>Query Status</a> <br /><br />						
		
				<br /><br />				
			<p style='font-weight:bold;font-size:15px'>M-PESA Customer Statements</p><br />	
			<a href='mpesa_statements_check_sub_registration.php'>Query Customer Registration Details</a> <br /><br />
			<a href='mpesa_statements_check_sub_activity.php'>Query Customer Activity</a> <br /><br />
			<a href='mpesa_partner_statements_check_registration.php'>Query Customer Request Details</a> <br /><br />
			<a href='mpesa_statements_check_sub_requests.php'>Resend Customer Statement</a> <br /><br />
			<a href='mpesa_statements_deactivate_sub.php'>De-activate Customer Subscription</a> <br /><br />
			
			
			<br /><br />			
			
			<p style='font-weight:bold;font-size:15px'>Stori Ibambe Promo 2016</p><br />	
			<a href='check_storo_details_2016.php'>Query Customer Storo Details</a> <br /><br />
			<a href='redcross_promotion.php'>RedCross Promotion Status Query</a> <br /><br />	
			<a href='redcross_promotion1.php'>RedCross all downloads</a> <br /><br />	
			<a href='check_storo_history_2016.php'>Query Customer History Details</a> <br /><br />
			<a href='check_storo_points_2016.php'>Query Customer Points Details</a> <br /><br />
			<a href='check_storo_winnings_2016.php'>Query Customer Winnings Details</a> <br /><br />
			
			
			
			<!--
			<p style='font-weight:bold;font-size:15px'>Nguruma Ibambe</p><br />	
			<a href='check_nguruma_points.php'>Query Points</a> <br /><br />
			<a href='check_nguruma_airtime.php'>Query Airtime</a> <br /><br />
			<a href='resend_nguruma_airtime.php'>Resend Airtime</a> <br /><br />
			<!--<p style='font-weight:bold;font-size:15px'>Masonko</p><br />	
			<a href='masonko_query.php'>Masonko Points</a> <br /><br />-->
			<br /><br />
			<a href="logout.php">LOGOUT</a><br /><br />
			<?php
				}
			?>
		</div>
		<div id="center">
			<div id="center-in">				
