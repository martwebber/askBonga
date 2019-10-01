<?php 
	include "../util/functions.php";
	
	$error = "";
	$message = "";
	$record_id = null;
	$display_option = "display";	
	insert_header2();
	
	$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
	
	$array_user_details = array (
		'password' => array("Password", "", 1, 1)
	);	
	
	if (isset($_GET['id'])) {
		$record_id = $_GET['id'];
		
		//select record to edit from db		
		$query = "SELECT * FROM web_user_list WHERE ID = ".$record_id;
		$error = $db->open_connection();
		$array_details = $db->list_records($query);
		$db->close_connection();
		
		//check the user's permissions
		if ($_SESSION['SECURITY_LEVEL'] > $array_details[0][6]) {
			$error .= "You are not authorised to update this account!";
			logmessage("INFO", "RESET USER PASSWORD - USER: ".$_SESSION["USERID"]."; $error");			
		}
	}
	else if (isset($_POST['submit_details'])) {
		//check the user's permissions
		//check security level of record to be updated
		$query = "select username, security_level from web_user_list where id = ".$_POST['record_id'];
		$error .= $db->open_connection();
		$array_details = $db->list_records($query);
		$db->close_connection();

		if ($_SESSION['SECURITY_LEVEL'] > $array_details[0][1]) {
			$error .= "You are not authorised to create/edit this account!";
			logmessage("INFO", "RESET USER PASSWORD - USER: ".$_SESSION["USERID"]."; $error;".$_SESSION['SECURITY_LEVEL'].";".$array_details[0][0]);			
		}		
		
		$array_user_details = array (
			'password' => array("Password", $_POST['new_passwd1'], 1, 16)
		);
					
		if ($error == "") {
			//make sure that the password is confirmed correctly
			if (isset($_POST['new_passwd1']) && $_POST['new_passwd1'] == $_POST['new_passwd2']) {
				//validate details
				$error .= validate($array_user_details);
				
				//if no error then save user details to db
				if ($error == "") {
					//encrypt the passwd
					$encrypted_passwd = crypt(md5($_POST["new_passwd1"]), md5($array_details[0][0]));
					$array_user_details['password'][1] = $encrypted_passwd;
					
					$query = build_update_sql($array_user_details, "web_user_list");
					$query .= " WHERE ID = ".$_POST['record_id'];
					
					if ($query != "") {
						//save to db
						$ret = $db->generic_sql($query);
	
						if ($ret) {
							$message = "Record Successfully Updated for Username: ".$array_details[0][0];
							logmessage("INFO", "RESET USER PASSWORD - USER: ".$_SESSION["USERID"]."; $message");
						}
						else {
							$error = "Error updating record for Username: ".$array_details[0][0];
							logmessage("INFO", "RESET USER PASSWORD - USER: ".$_SESSION["USERID"]."; $error");							
						}
						
						unset($_POST['new_passwd1']);
						unset($_POST['new_passwd2']);	
					}
					$db->close_connection();
				}
			}
			else {
				$error = "Please ensure that you correctly confirm your password!";
			}		
		}
	}

?>

<div class="cspacer">
	<?php 
		if ($error !=  "") {
			echo "<div style='text-align: left; width: 80%'>\r\n";
			echo "<table class='tablebody border' width='100%'>\r\n";
			echo "<th class='tableheader'>MESSAGE</th>\r\n";
			echo "<tr><td>\r\n";
			echo "<p class='error'>".$error."</p>";
			echo "</td></tr>\r\n";
			echo "</table>\r\n";
			echo "<br /><br />\r\n";
			echo "</div>\r\n";
		}
		else if ($message !=  "") {
			echo "<div style='text-align: left; width: 80%'>\r\n";
			echo "<table class='tablebody border' width='100%'>\r\n";
			echo "<th class='tableheader'>MESSAGE</th>\r\n";
			echo "<tr><td>\r\n";
			echo "<p class='error'>".$message."</p>";
			echo "</td></tr>\r\n";
			echo "</table>\r\n";
			echo "<br /><br />\r\n";
			echo "</div>\r\n";
		}
	?>
	<div align="center" style="text-align: left; width:80%">
	<?php
		//if ($error == "") {
	?>		
		<form name="user_details" action="change_user_password.php" method="post">
			<table class="tablebody border" width="100%">
				<tr>
					<th colspan="2" class="tableheader">CHANGE PASSWORD</th>
				</tr>
				<tr>
					<td>NEW PASSWORD:</td><td><input type="password" name="new_passwd1" value="" /></td>					
				</tr>
				<tr>
					<td>CONFIRM PASSWORD:</td><td><input type="password" name="new_passwd2" value="" /></td>
				</tr>
				<tr>
					<td colspan='2'><input type='hidden' name='record_id' value='<?php echo $record_id ?>' /><br /></td>					
				</tr>
				<tr>
					<td colspan='2'><input type='submit' value='Submit Details' name='submit_details'></td>
				</tr>
				<tr>
					<td></td><td></td>																																			
				</tr>
			</table>
		</form>
	<?php 
		//}
	?>
</div>

<!-- insert the footer -->
<?php
	insert_footer2();
?>
