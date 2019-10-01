<?php 
	include "util/functions.php";
	include "util/security_level_list.php";
	include "util/department_list.php";
	
	$error = "";
	$record_id = null;
	$display_option = "edit";	
	insert_header2();	
	
	//our database object
	$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
	
	$array_user_details = array (
		'name' => array("First Name", "", 1, 1),
		'surname' => array("Surname", "", 1, 1),
		'username' => array("Username", "", 1, 1),
		'passwd' => array("Password", "", 1, 1),
		'department' => array("Department", "none", 1, 1),
		'security_level' => array("Security Level", "none", 1, 1)
	);
	
	//set the display option (only after post)
	if (isset($_POST['display_option'])) {
		$display_option = $_POST['display_option'];
	}
	
	if (isset($_POST['delete_details'])) {
		$display_option = '';
	}
	
	if (isset($_GET['id'])) {
		$record_id = $_GET['id'];
		
		if (isset($_GET['display_option'])) {
			if ($_GET['display_option'] == 'capture') {
				$display_option = 'capture';
			}
			else if ($_GET['display_option'] == 'edit') {
				$display_option = 'edit';
			}
		}
		
		//if edit then select record to edit from db		
		$query = "SELECT * FROM user_list WHERE ID = ".$record_id;
		$error = $db->open_connection();
		$array_details = $db->list_records($query, true);
		$db->close_connection();
		
		if (is_array($array_details)) {
			//clear data array for next record
			$array_user_details = array (
				'name' => array("First Name", $array_details[0]['NAME'], 1, 1),
				'surname' => array("Surname", $array_details[0]['SURNAME'], 1, 1),
				'username' => array("Username", $array_details[0]['USERNAME'], 1, 1),
				'passwd' => array("Password", "", 1, 1),
				'department' => array("Department", $array_details[0]['DEPARTMENT'], 1, 1),
				'security_level' => array("Security Level", $array_details[0]['SECURITY_LEVEL'], 1, 1)
			);
		}
		else {
			$error = "Error selecting record";
		}
	}
	else if (isset($_GET['display_option'])) {
		$display_option = $_GET['display_option'];
	}
	else if (isset($_SESSION['USERID']) && !isset($_POST['submit_details']) && $display_option == 'edit') {
		$query = "SELECT * FROM USER_LIST WHERE USERNAME = '".$_SESSION['USERID']."'";
		$error = $db->open_connection();
		$array_details = $db->list_records($query, true);
		$db->close_connection();

		if (is_array($array_details)) {
			$array_user_details = array (
				'name' => array("First Name", $array_details[0]['NAME'], 1, 1),
				'surname' => array("Surname", $array_details[0]['SURNAME'], 1, 1),
				'username' => array("Username", $_SESSION['USERID'], 1, 1),
				'passwd' => array("Password", "", 1, 16),
				'department' => array("Department", $array_details[0]['DEPARTMENT'], 1, 1),
				'security_level' => array("Security Level", $array_details[0]['SECURITY_LEVEL'], 1, 1)
			);
			
			if (isset($_POST['confirm_passwd']))
				unset($_POST['confirm_passwd']);
		}
	}
	else if (isset($_SESSION['USERID']) && isset($_POST['submit_details']) && $display_option == 'edit') {
		$query = "SELECT * FROM USER_LIST WHERE USERNAME = '".$_SESSION['USERID']."'";
		$error = $db->open_connection();
		$array_details = $db->list_records($query, true);

		if (is_array($array_details)) {
			$array_user_details = array (
				'name' => array("First Name", $array_details[0]['NAME'], 1, 14),
				'surname' => array("Surname", $array_details[0]['SURNAME'], 1, 14),
				'username' => array("Username", $_SESSION['USERID'], 1, 14),
				'passwd' => array("Password", $_POST['passwd'], 1, 16),
				'department' => array("Department", $array_details[0]['DEPARTMENT'], 1, 14),
				'security_level' => array("Security Level", $array_details[0]['SECURITY_LEVEL'], 1, 1)
			);
		}
		
		$error = validate($array_user_details);
		
		if ($error == "") {
			if (isset($_POST['confirm_passwd']) && $_POST['confirm_passwd'] == $_POST['passwd']) {
				$encrypted_passwd = crypt(md5($_POST["passwd"]), md5($_SESSION['USERID']));
				//$encrypted_passwd = "";
				$query = "UPDATE USER_LIST SET PASSWORD = '".$encrypted_passwd."' WHERE USERNAME = '".$_SESSION['USERID']."'";			
				$db->generic_sql($query);
				header("Location: password_confirmed.php");
			}
			else {
				$query = "SELECT * FROM USER_LIST WHERE USERNAME = '".$_SESSION['USERID']."'";
				$array_details = $db->list_records($query, true);
		
				if (is_array($array_details)) {
					$array_user_details = array (
						'name' => array("First Name", $array_details[0]['NAME'], 1, 1),
						'surname' => array("Surname", $array_details[0]['SURNAME'], 1, 1),
						'username' => array("Username", $_SESSION['USERID'], 1, 1),
						'passwd' => array("Password", "", 1, 1),
						'department' => array("Department", $array_details[0]['DEPARTMENT'], 1, 1),
						'security_level' => array("Security Level", $array_details[0]['SECURITY_LEVEL'], 1, 1)
					);
	
					if (isset($_POST['confirm_passwd']))
						unset($_POST['confirm_passwd']);
		
					$error = "Please ensure that you correctly confirm your password!";
				}
			}
			$db->close_connection();
		}
		else {
			if (isset($_POST['confirm_passwd']))
				unset($_POST['confirm_passwd']);		
		}
	}
	else if (isset($_POST['submit_details'])) {
		$array_user_details = array (
			'name' => array("First Name", $_POST['name'], 1, 14),
			'surname' => array("Surname", $_POST['surname'], 1, 14),
			'username' => array("Username", $_POST['username'], 1, 3),
			'password' => array("Password", $_POST['passwd'], 1, 16),
			'department' => array("Department", $_POST['department'], 1, 3),
			'security_level' => array("Security Level", $_POST['security_level'], 1, 1)
		);
				
		//if $_POST['id'] is set then we are in edit mode, so skip password confirmation
		if (isset($_POST['id'])) {
			//$_POST['confirm_passwd'] = $_POST['passwd'];
			$display_option = 'edit';
		}
			
		//make sure that the password is confirmed correctly
		if (isset($_POST['confirm_passwd']) && $_POST['confirm_passwd'] == $_POST['passwd']) {
			//validate details
			$error = validate($array_user_details);
						
			//if no error then save user details to db
			if ($error == "") {
				//encrypt password
				$encrypted_passwd = crypt(md5($_POST["passwd"]), md5($_POST["username"]));
				$array_user_details['password'][1] = $encrypted_passwd;
				
				//set our display option
				if (isset($_POST['display_option'])) {
					$display_option = $_POST['display_option'];
				}
				
				if ($display_option == 'capture') {
					$query = build_insert_sql($array_user_details, "user_list");
				}
				else if ($display_option == 'edit') {
					$query = build_update_sql($array_user_details, "user_list");
					$query .= "WHERE ID = ".$_POST['id'];
				}
				
				$message = "USER REGISTRATION: User ID: - ".$_SESSION['USERID']." CREATED USER: ".$_POST['username'];
				logmessage("INFO", $message);
				
				//save to db
				$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
				$error = $db->open_connection();
				$db->generic_sql($query);
				$db->close_connection();
				
				//if record is successfully saved, show message & clear all fields
				$array_user_details = array (
					'name' => array("First Name", "", 1, 2),
					'surname' => array("Surname", "", 1, 2),
					'username' => array("Username", "", 1, 2),
					'passwd' => array("Password", "", 1, 16),
					'department' => array("Department", "none", 1, 3),
					'security_level' => array("Security Level", "none", 1, 1)
				);
				$error = "Record Successfully Created For Username: ".$_POST['name'];
				unset($_POST['name']);
				unset($_POST['surname']);
				unset($_POST['username']);
				unset($_POST['passwd']);
				unset($_POST['confirm_passwd']);
				unset($_POST['department']);
				unset($_POST['security_level']);					
			}
		}
		else {
			$error = "Please ensure that you correctly confirm your password!";
		}		
	}
	else if (isset($_POST['delete_details'])) {
		$record_id = $_POST['id'];
		$query = "DELETE FROM user_list WHERE ID = $record_id";
		$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
		//execute delete
		$error = $db->open_connection();
		$db->generic_sql($query);
		$db->close_connection();
		
		$error = "Record for ".$_POST['username']." Successfully Deleted!";
		$array_user_details = array (
			'name' => array("First Name", "", 1, 2),
			'surname' => array("Surname", "", 1, 2),
			'username' => array("Username", "", 1, 2),
			'passwd' => array("Password", "", 1, 16),
			'department' => array("Department", "none", 1, 3),
			'security_level' => array("Security Level", "none", 1, 1)
		);
		
		unset($_POST['name']);
		unset($_POST['surname']);
		unset($_POST['username']);
		unset($_POST['passwd']);
		unset($_POST['confirm_passwd']);
		unset($_POST['department']);
		unset($_POST['security_level']);	
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
	?>
	<div align="center" style="text-align:left; width:80%">
	<form name="user_details" action="user_registration2.php" method="post">
		<table class="tablebody border" width="100%">
				<tr>
					<th colspan="2" class="tableheader"><b>USER MANAGEMENT</b></th>
				</tr>
				<tr>
					<td>FIRST NAME:</td><td><input type="text" name="name" value="<?php echo $array_user_details['name'][1]; ?>" 
					<?php if (isset($_SESSION['USERID']) && $display_option == 'edit') //echo 'disabled' ?> /></td>
				</tr>
				<tr>
					<td>SURNAME:</td><td><input type="text" name="surname" value="<?php echo $array_user_details['surname'][1]; ?>" 
					<?php if (isset($_SESSION['USERID']) && $display_option == 'edit') //echo 'disabled' ?> /></td>					
				</tr>
				<tr>
					<td>USERNAME:</td><td><input type="text" name="username" value="<?php echo $array_user_details['username'][1]; ?>"
					<?php if (isset($_SESSION['USERID']) && $display_option == 'edit') //echo 'disabled' ?> /></td>
				</tr>
				<tr>
					<td>PASSWORD:</td><td><input type="password" name="passwd" value="<?php echo $array_user_details['passwd'][1]; ?>" /></td>
				</tr>
				<tr>
					<td>CONFIRM PASSWORD:</td><td><input type="password" name="confirm_passwd" 
						<?php
							if (isset($_POST['confirm_passwd']))
								echo 'value='.$_POST['confirm_passwd'];
							else
								echo ''; 
						?> />
					</td>
				</tr>
				<tr>
					<td>DEPARTMENT</td><td>			
					<?php
						if (isset($array_user_details['department'][1]) && $array_user_details['department'][1] != "none")
							$department_val = $array_user_details['department'][1];
						else
							$department_val = -1;
							
						echo build_combo("department", $array_departments, "combobox", null, $department_val); 
					?>					
					</td>
				</tr>
				<tr>
					<td>SECURITY LEVEL</td><td>
					<?php					
						if (isset($array_user_details['security_level'][1]) && $array_user_details['security_level'][1] != "none")
							$security_level_val = $array_user_details['security_level'][1];
						else
							$security_level_val = -1;

						if (isset($_SESSION['USERID']) && $display_option == 'edit') {
							//echo build_combo_disabled("security_level", $array_security_levels, "combobox", null, $security_level_val);
							echo build_combo("security_level", $array_security_levels, "combobox", null, $security_level_val);
						}
						else
							 echo build_combo("security_level", $array_security_levels, "combobox", null, $security_level_val);
					?>
					</td>
				</tr>
				<tr>
					<td></td><td></td>
				</tr>
				<tr>
					<?php
						echo "<input type='hidden' value='".$display_option."' name='display_option' />";
						if ($display_option == 'capture')
							echo "<td colspan='2'><input type='submit' value='Submit Details' name='submit_details'></td>";
						else {
							echo "<td><input type='submit' value='Submit Details' name='submit_details'></td>";
							echo "<input type='hidden' value='".$record_id."' name='id' />";
							echo "<td><input type='submit' value='Delete Details' name='delete_details'></td>";
						}
					?>
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
