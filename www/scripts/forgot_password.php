<?php
	require_once "../util/functions.php";

	$error = "";
	$emailUser = "";
	insert_header2();

	if (isset($_POST['reset_password'])) {						
		if (!ctype_alnum($_POST['username'])) {			
			$error .= "<br />Please ensure 'USERNAME' is alphanumeric, e.g. someone";
		}
		
		if ($error == "") {
			//attempt to validate the username & email address			
			$username = $_POST['username'];
			$emailAddress = $username."@safaricom.co.ke";
			
			//reset password and send email
			$newPass = genRandomPassword();
			
			$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
			$db->open_connection();
			
			//let's check that the username exists
			$query = "select count(*) from web_user_list where username = '".$username."'";
			$user_data = $db->list_records($query, false);
			
			if (isset($user_data) && count($user_data) == 1 && $user_data[0][0] == 1) {
				$encryptedPassword = crypt(md5($newPass), md5($username));
				$query = "update web_user_list set password = '".$encryptedPassword."' where username = '".$username."'";
				$db->generic_sql($query);

				//send new password to user
				$message_body = "Your new password is: $newPass";						   

				$from = "bundles@safaricom.co.ke";
				$recepient_list = array(0 => array(0 => $emailAddress, 1 => $username));
				$subject = "Password Reset";
				$from_name = "Bundles Admin";

				$mailStatus = send_mail($from, $recepient_list, $subject, $message_body, null, null, $from_name);
				if ($mailStatus == 0) {
					$error .= "<br />Password reset successfully. Please check your inbox for the new password.";
				}
				else {
					$error .= "<br />Error sending email. Please contact system administrator";
				}
			}
			else {
				$error = "The user account specified does not exist!";
			}
			
			$db->close_connection();
		}
	}
	
	
	/*if (isset($_POST['confirm_deletion']) && $_POST['confirm_deletion'] != "") {
		$array_confirmed = split(";", $_POST['confirm_deletion']);
		$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
			for ($i = 0; $i < count($array_confirmed); $i++) {
				$query = "DELETE FROM user_list WHERE ID = ";
				if ($array_confirmed[$i] != null) {
					$query .= $array_confirmed[$i];
					$db->generic_sql($query);
					logmessage("INFO", "USER MANAGEMENT - USER: ".$_SESSION["USERID"]."; user account with ID: $array_confirmed[$i] deleted");
				}
			}
	}*/
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
		<!--<table class="tablebody border" width="100%">
			<tr>
				<th colspan="2" class="tableheader"><b>USER MANAGEMENT</b></th>
			</tr>
			<tr>
				<td><a href="user_registration.php">Add Record</a></td><td><a href="#" onclick="javascript: confirm_deletion('user_list');">Delete Record(s)</a></td>
			</tr>
		</table>-->
        <form name='sub_history_list' action='forgot_password.php' method='post'>
        <center>
        <table class="tablebody border" width="80%">
			<tr>
				<th colspan="2" class="tableheader"><b>USER DETAILS</b></th>
			</tr>
			<tr>
				<td><br />USERNAME:</td><td><br /><input type='text' name='username' /></td>
			</tr>			
            <tr>
                <td colspan="2"><br /><input type='submit' name='reset_password' /></td>
            </tr>
		</table>
        </center>
		<br /><br />
</div>

<!-- insert the footer -->
<?php
	insert_footer2();
?>

