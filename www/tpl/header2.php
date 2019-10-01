<?php session_start();
ob_start();
$page_name = basename($_SERVER['PHP_SELF']);
$array_no_display = array("login.php", "please_login.php", "forgot_password.php");
$scCheck = false;
$pwCheck = false;

if (!in_array($page_name, $array_no_display) && isset($_SESSION['USERID'])) {
	$pwCheck = true;
	if ($_SESSION['SECURITY_LEVEL'] == 0) {
		$scCheck = true;
	}
}

?>
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
		<link rel="stylesheet" type="text/css" href="../jscalendar/skins/aqua/theme.css">
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
		<div id="sidebar-right">	
			<?php
				if ($pwCheck) {
			?>
                <br /><a href='change_password.php'>Change Password</a> <br /><br />
			<?php
					if ($scCheck) {
						echo "<a href='user_management.php'>User Management</a> <br /><br />";
					}
				}
			?>		
		</div>
		<div id="sidebar-left">
			<?php
				if (!in_array($page_name, $array_no_display)) {
			?>
			<!-- added on JUN 19th 2019 -->
             <br /><br /> 
               <p style='font-weight:bold;font-size:15px;text-transform: uppercase;'>Kindergarten campaign</p><br />    
              <a href= 'kinda_promo.php'>Kindergarten campaign</a> <br /><br />
				<!--added on June 19th 2019 -->
			
			
			<!-- added on Jan 14th 2019 -->
			<br /><br /> 
                        <p style='font-weight:bold;font-size:15px'>JAZA PESA PROMO</p><br />    
                        <a href='jaza_pesa_mobil.php'>Jaza Pesa Mobile Banking Promo</a> <br /><br />
                        

			<!--end -->		

			<!-- added on Oct 11th -->
			<br /><br /> 
                        <p style='font-weight:bold;font-size:15px'>MPESA CVM</p><br />    
                        <a href='mpesa_me_promo.php'>MPESA ME PROMO</a> <br /><br />
                        <a href='mpesa_stawisha_agents.php'>MPESA AGENT STAWISHA</a> <br /><br />
                        <a href='biashara_ni_mpesa.php'>BIASHARA NI MPESA TU</a> <br /><br />

			<!--end -->				



			<!-- added on September 14th 2018 -->
				<br /><br /> 
			     <p style='font-weight:bold;font-size:15px'>FREE RESOURCES</p><br />				
                     <a href='Free_Resources.php'>Query Free Resources</a> <br /><br />
					  <a href='Free_Resource_Expiration.php'>Expire Free Resources </a><br /><br />
			<!-- end -->
			
			<!-- added on June 13th 2018 -->
				<br /><br /> 
						<p style='font-weight:bold;font-size:15px'> Maisha ni Mpesa-Tu </p> <br />  
						<a href='mpesa_tu_promo_details_2018.php'>Query Customer details </a> <br /><br />                
						<a href='mpesa_tu_promo_history_2018.php'>Query Customer History Details</a> <br /><br />
						<a href='mpesa_tu_promo_points_2018.php'>Query Customer Points and Region Details</a> <br /><br />
						<a href='mpesa_tu_promo_winnings_2018.php'>Query Customer Winnings Details</a> 
				<br /><br /><!-- added on June 13th 2018 -->


			
			<br /><br /> 
            <p style='font-weight:bold;font-size:15px'>Shinda MaMili na Tunukiwa Promo</p><br />  
			<a href='tunukiwa_promo_details_2017.php'>Query Customer details </a> <br /><br />	
			<a href='tunukiwa_promo_history_2017.php'>Query Customer History Details</a> <br /><br />
			<a href='tunukiwa_promo_points_2017.php'>Query Customer Points and Region Details</a> <br /><br />
			<a href='tunukiwa_promo_winnings_2017.php'>Query Customer Winnings Details</a> <br /><br />
			
			
                        <br /><br /> 
                        <p style='font-weight:bold;font-size:15px'>Unclaimed Assets</p><br />   
                        <a href='check_unclaimed_assets_status.php'>Query Claim History</a> <br />
                        <a href='check_unclaimed_assets_archived_number.php'>Query Details</a> <br /><br />

			<p style='font-weight:bold;font-size:15px'>Jambo Jet LNM Promotion UAT</p><br />               
			<a href= 'lnm_jamboj_query_airtime_status.php'>Query Customer History Details</a> <br /><br />
			<a href='lnm_jamboj_query_cust_history.php'>Query Customer Airtime Status</a> <br /><br />
			<a href='lnm_jamboj_query_top100_awards.php'>Query Daily Top 100 Airtime Awards</a> <br /><br />

			
				<br /><br /> 
			<p style='font-weight:bold;font-size:15px'>ShowMax Promotion Query</p><br />	
			<a href='showmax_promotion.php'>Query Status</a> <br /><br />
			<a href='ftth_showmax_30daypromo.php'>FTTH SHOWMAX REWARD SERVICE </a> <br /><br />
						
				<br /><br /> 
			<p style='font-weight:bold;font-size:15px'>Happy Hour Whitelist Query</p><br />	
			<a href='happyhour_query_whitelist.php'>Query Status</a> <br /><br />
			
			
			<br /><br /> 
			<p style='font-weight:bold;font-size:15px'>FTTH *855# Request History</p><br />	
			<a href='ftth_ussd_requests.php'>Query using MSISDN or Circuit ID</a> <br /><br />
			
			<br /><br /> 
			<p style='font-weight:bold;font-size:15px'>Stori Ibambe 200% Bonus Promotion</p><br />	
			<a href='stori_ibambe_double_bonus.php'>Query Whitelist</a> <br /><br />
			
			<br /><br /> 
			<p style='font-weight:bold;font-size:15px'>Blaze</p><br />	
			<a href='cyp_query_history.php'>Query Request History</a> <br /><br />	
			<a href='cyp_query_sub.php'>Query Whitelist</a> <br /><br />	
			
	
			
			<br /><br />                                       
                                                
			<p style='font-weight:bold;font-size:15px'>Tunukiwa Daily Promotion</p><br />            
			<a href='eol_cust_offer_history.php'>Query Customer Tunukiwa Offers</a> <br /><br />
			<a href='eol_cust_offer_history_detailed_v2.php'>Query Customer Tunukiwa Details</a> <br /><br />
			<a href='eol_cust_offer_purchase_history_v2.php'>Query Customer Tunukiwa Purchase History</a> <br /><br />              

			
			<br /><br />


                        <p style='font-weight:bold;font-size:15px'>M-PESA Customer Statements</p><br />   
                        <a href='mpesa_statements_check_sub_registration.php'>Query Customer Registration Details</a> <br /><br />
                        <a href='mpesa_statements_check_sub_activity.php'>Query Customer Activity</a> <br /><br />
                        <a href='mpesa_statements_check_sub_requests.php'>Query Customer Request Details</a> <br /><br /> 
			


			<p style='font-weight:bold;font-size:15px'>OKOA </p><br />	
			<a href='lipa_okoa_mdogo_history.php'>OKOA Mdogo Mdogo</a> <br /><br />
			<a href='AutoOkoa.php'>OKOA Promo Type</a> <br /><br />
			
			<br /><br />
			<p style='font-weight:bold;font-size:15px'>Changa na Bonga Points</p><br />	
			<!--<a href='check_bonga_history.php'>Query Transactions</a> <br /><br />-->
			<a href='lms_query_bonga_details.php' > Bonga PIN Reset Details </a> <br /><br />
			<a href='lms_query_sms_status.php' > Bonga PIN Reset SMS Sent Details </a> <br /><br />
			
				<br /><br /> 
			<p style='font-weight:bold;font-size:15px'>ShowMax Status Query</p><br />	
			<a href='showmax_status_query.php'>Query Status</a> <br /><br />
			
				<br /><br />
			<p style='font-weight:bold;font-size:15px'>RedCross Bundle Promotion</p><br />	
			<a href='redcross_promotion.php'>Query Status</a> <br /><br />
			<a href='redcross_promotion1.php'>View all downloads</a> <br /><br />

			
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
			
                        <p style='font-weight:bold;font-size:15px'>M-PESA Partner Statements</p><br />    
                        <a href='mpesa_partner_statements_check_registration.php'>Query Partner Registration Details</a> <br /><br />
                        <a href='mpesa_partner_statements_check_activity.php'>Query Partner Activity</a> <br /><br />
                        <a href='mpesa_partner_statements_requests.php'>Query Partner Request Details</a> <br /><br />
                        <a href='mpesa_partner_statements_resend.php'>Resend Partner Statement</a> <br /><br />
                        <a href='mpesa_partner_statements_deactivate_sub.php'>De-activate Partner Subscription</a> <br /><br />

			
			
			<br /><br />			
			
			<p style='font-weight:bold;font-size:15px'>Stori Ibambe Promo 2016</p><br />	
			<a href='check_storo_details_2016.php'>Query Customer Storo Details</a> <br /><br />
			<a href='redcross_promotion.php'>RedCross Promotion Status Query</a> <br /><br />	
			<a href='redcross_promotion1.php'>RedCross all downloads</a> <br /><br />	
			<a href='check_storo_history_2016.php'>Query Customer History Details</a> <br /><br />
			<a href='check_storo_points_2016.php'>Query Customer Points Details</a> <br /><br />
			<a href='check_storo_winnings_2016.php'>Query Customer Winnings Details</a> <br /><br />
			
			<a href='tunukiwa_promo_details_2017.php'>Query Customer details </a> <br /><br />	
			<a href='tunukiwa_promo_history_2017.php'>Query Customer History Details</a> <br /><br />
			<a href='tunukiwa_promo_points_2017.php'>Query Customer Points and Region Details</a> <br /><br />
			<a href='tunukiwa_promo_winnings_2017.php'>Query Customer Winnings Details</a> <br /><br />
			
			
			

                       <br /><br /> 
	               <p style='font-weight:bold;font-size:15px'>CHANUA BIZ</p><br />		
	               <a href='RLNM_Retailer.php'>Query Retailer</a> <br /><br />	
	               <a href='RLNM_DSA.php'>Query DSA</a> <br /><br />	
	               <a href='RLNM_Entries.php'>Query CHANUA BIZ Entries</a> <br /><br />
	               <a href='RLNM_History.php'>Query Redeemable Airtime Value History</a> <br /><br />
				   <a href='RLNM_Till.php'>Query Whitelisted Till</a> <br /><br />
                       <br /><br />
		
				<br /><br /> 
	               <p style='font-weight:bold;font-size:15px'>DP Platinum</p><br />		
	               <a href='dp_platinum_check_status.php'>Query RequestStatus</a> <br /><br />	
	             
                <br /><br />
					   
		<br /><br /> 
                        <p style='font-weight:bold;font-size:15px'>CVM Retailer</p><br />			
                        <a href='cvm_blacklist.php'>Partner Blacklist</a> <br /><br />
			<a href='cvm_unblacklist.php'>UnBlacklist Partner</a> <br /><br />
			<a href='cvm_report.php'>Activation Reports</a> 
		<br /><br />

                <br /><br />
                        <p style='font-weight:bold;font-size:15px'>FTTH Internet Plus</p><br />
                        <a href='ftth_gsm.php'>FTTH Internet Plus</a> <br /><br />
                        <a href='ftth_jubilee_insurance.php'>FTTH Home Insurance</a> <br /><br />
                
                <br /><br />

                <br /><br />                        
                        <p style='font-weight:bold;font-size:15px'>Iflix</p><br />
                        <a href='iflix.php'>Iflix Query Details</a> <br /><br />

                <br /><br />
				
				
						
	
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
<?php ob_end_flush(); ?>		
