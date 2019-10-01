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
	
	if (isset($_POST['submit_details'])) {
		$error = $db->open_connection();
		$query = "select password from web_user_list where id = ".$_SESSION['EMP_NO'];
		$array_user = $db->list_records($query);
		$encrypted_old_passwd = crypt(md5($_POST["old_passwd"]), md5($_SESSION['USERID']));
		
		if ($array_user[0][0] != $encrypted_old_passwd) {
			$error .= "Please ensure you correctly enter your current password";
		}
		
		if ($error == "") {
			$array_user_details = array (
				'password' => array("Password", $_POST['new_passwd1'], 1, 16)
			);		
						
			//make sure that the password is confirmed correctly
			if (isset($_POST['new_passwd1']) && $_POST['new_passwd1'] == $_POST['new_passwd2']) {
				//validate details
				$error .= validate($array_user_details);
				
				//if no error then save user details to db
				if ($error == "") {
					//encrypt the passwd
					$encrypted_passwd = crypt(md5($_POST["new_passwd1"]), md5($_SESSION['USERID']));
					$array_user_details['password'][1] = $encrypted_passwd;
					
					$query = build_update_sql($array_user_details, "web_user_list");
					$query .= "WHERE ID = ".$_SESSION['EMP_NO'];
					
					if ($query != "") {
						//save to db
						$ret = $db->generic_sql($query);

						if ($ret) {
							$message = "Record Successfully Updated for Username: ".$_SESSION['USERID'];
							logmessage("INFO", "CHANGE PASSWORD - USER: ".$_SESSION["USERID"]."; $message");
						}
						else {
							$message = "Error updating record for Username: ".$_SESSION['USERID'];
							logmessage("INFO", "CHANGE PASSWORD - USER: ".$_SESSION["USERID"]."; $message");							
						}
						
						unset($_POST['old_passwd']);
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

		<form name="user_details" action="change_password.php" method="post">
			<table class="tablebody border" width="100%">
				<tr>
					<th colspan="2" class="tableheader">CHANGE PASSWORD</th>
				</tr>
				<tr>
					<td>OLD PASSWORD:</td><td><input type="password" name="old_passwd" value="" /></td>
				</tr>
				<tr>
					<td>NEW PASSWORD:</td><td><input type="password" name="new_passwd1" value="" /></td>					
				</tr>
				<tr>
					<td>CONFIRM PASSWORD:</td><td><input type="password" name="new_passwd2" value="" /></td>
				</tr>
				<tr>
					<td colspan='2'><br /></td>					
				</tr>
				<tr>
					<td colspan='2'><input type='submit' value='Submit Details' name='submit_details'></td>
				</tr>
				<tr>
					<td></td><td></td>																																			
				</tr>
			</table>
		</form>

</div>

<!-- insert the footer -->
<?php
	insert_footer2();
?>
