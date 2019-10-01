<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<html>
<!--
   HTML 3.2
   Document type as defined on http://www.w3.org/TR/REC-html32
-->
<head>
                <title>Safaricom Foundation Promotion - Query Points</title>
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
                <div id="center">
                        <div id="center-in">                            
