<?php 
	include "../util/functions.php";
	include "../util/security_level_list.php";
	include "../util/department_list.php";
	
	$error = "";
	$message = "";
	$record_id = null;
	$display_option = "display";	
	insert_header2();
	
	$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
	
	$array_user_details = array (
		'name' => array("First Name", "", 1, 1),
		'surname' => array("Surname", "", 1, 1),
		'username' => array("Username", "", 1, 1),
		'password' => array("Password", "", 1, 1),
        'confirm_password' => array("Password", "", 1, 1),
		'department' => array("Department", "none", 1, 1),
		'security_level' => array("Security Level", "none", 1, 1)	
	);
	
	if (isset($_GET['id'])) {
		$record_id = $_GET['id'];
		
		if (isset($_GET['display_option'])) {
			if ($_GET['display_option'] == 'capture') {
				$display_option = 'display';
			}
			else if ($_GET['display_option'] == 'edit') {
				$display_option = 'edit';
			}
		}
		
		//if edit then select record to edit from db		
		$query = "SELECT * FROM web_user_list WHERE ID = ".$record_id;
		$error = $db->open_connection();
		$array_details = $db->list_records($query);
		$db->close_connection();
		
		//check the user's permissions
		if ($_SESSION['SECURITY_LEVEL'] > $array_details[0][6]) {
			$error .= "You are not authorised to view this account!";
			logmessage("INFO", "USER MANAGEMENT - USER: ".$_SESSION["USERID"]."; $error");			
		}
		else {
			if (is_array($array_details)) {
				//clear data array for next record
				$array_user_details = array (
					'name' => array("First Name", $array_details[0][1], 1, 1),
					'surname' => array("Surname", $array_details[0][2], 1, 1),
					'username' => array("Username", $array_details[0][4], 1, 1),
					'password' => array("Password", $array_details[0][5], 1, 1),
					'department' => array("Department", $array_details[0][3], 1, 1),
					'security_level' => array("Security Level", $array_details[0][6], 1, 1)
				);
			}
			else {
				$error = "Error selecting record";
			}	
		}		
	}
	
	if (isset($_POST['submit_details'])) {
		//set our array with user supplied data
		$array_user_details = array (
			'name' => array("First Name", ucwords(strtolower($_POST['name'])), 1, 2),
			'surname' => array("Surname", ucwords(strtolower($_POST['surname'])), 1, 2),
			'username' => array("Username", strtolower($_POST['username']), 1, 2),
			'password' => array("Password", $_POST['passwd'], 1, 16),
			'department' => array("Department", $_POST['department'], 1, 3),
			'security_level' => array("Security Level", $_POST['security_level'], 1, 1)
		);
		
		//if $_POST['id'] is set then we are in edit mode, so skip password confirmation
		if (isset($_POST['id'])) {
			unset ($array_user_details);
			
			$array_user_details = array (
				'name' => array("First Name", $_POST['name'], 1, 2),
				'surname' => array("Surname", $_POST['surname'], 1, 2),
				'username' => array("Username", strtolower($_POST['username']), 1, 2),
				'department' => array("Department", $_POST['department'], 1, 3),
				'security_level' => array("Security Level", $_POST['security_level'], 1, 1)
			);
			
			$display_option = 'edit';
			print_r($array_user_details);

			//check the user's permissions
			//check security level of record to be deleted
			$query = "select security_level from web_user_list where id = ".$_POST['id'];
			$error .= $db->open_connection();
			$array_details = $db->list_records($query);
			$db->close_connection();

			if ($_SESSION['SECURITY_LEVEL'] > $array_details[0][0]) {
				$error .= "You are not authorised to create/edit this account!";
				logmessage("INFO", "USER MANAGEMENT - USER: ".$_SESSION["USERID"]."; $error;".$_SESSION['SECURITY_LEVEL'].";".$_POST['security_level']);			
			}		
			else {
				$_POST['confirm_passwd'] = $_POST['passwd'] = "";
			}
		}
		else if ($_SESSION['SECURITY_LEVEL'] > $array_user_details['security_level'][1]) {			
			$error .= "You are not authorised to create/edit this account!";
			logmessage("INFO", "USER MANAGEMENT - USER: ".$_SESSION["USERID"]."; $error;".$_SESSION['SECURITY_LEVEL'].";".$_POST['security_level']);
		}
			
		//make sure that the password is confirmed correctly
		if (isset($_POST['confirm_passwd']) && $_POST['confirm_passwd'] == $_POST['passwd']) {
			//validate details
			$error .= validate($array_user_details);
			
			//if no error then save user details to db
			if ($error == "") {
				if ($display_option == 'display') {			
					//encrypt the passwd
					$encrypted_passwd = crypt(md5($_POST["passwd"]), md5($array_user_details['username'][1]));
					$array_user_details['password'][1] = $encrypted_passwd;	
					
					//check if a record for this user already exists
					$query = "SELECT * FROM web_user_list WHERE USERNAME = '".$array_user_details['username'][1]."'";
					$error .= $db->open_connection();
					$array_user = $db->list_records($query);
					
					if (!is_array($array_user)) {
						$query = build_insert_sql($array_user_details, "web_user_list");
					}
					else {
						$query = "";
						$error .= "A record already exists for this user. Please update.";
					}									
				}
				else if ($display_option == 'edit') {
					$query = build_update_sql($array_user_details, "web_user_list");
					$query .= "WHERE ID = ".$_POST['id'];
				}
				
				if ($query != "") {
					//save to db
					$error = $db->generic_sql($query);
					
					if ($error) {
                        //if record is successfully saved, show message & clear all fields
                        $array_user_details = array (
                            'name' => array("First Name", "", 1, 2),
                            'surname' => array("Surname", "", 1, 2),
                            'username' => array("Username", "", 1, 2),
                            'password' => array("Password", "", 1, 16),
                            'confirm_password' => array("Password", "", 1, 16),
                            'department' => array("Department", "none", 1, 3),
                            'security_level' => array("Security Level", "none", 1, 1)
                        );

                        if ($display_option == 'display') {
                            $error = "Record Successfully Created For Username: ".$_POST['username'];
                            logmessage("INFO", "USER MANAGEMENT - USER: ".$_SESSION["USERID"]."; $error");
                        }
                        else if ($display_option == 'edit') {
                            //go back to web_user_list
                            $message = "Record Successfully Updated for Username: ".$_POST['username'];
                            logmessage("INFO", "USER MANAGEMENT - USER: ".$_SESSION["USERID"]."; $error");
                            if ($_SESSION['SECURITY_LEVEL'] < 2) {
                                header("Location: user_management.php?message=$message");
                            }
                            else {
                                $message = "Record Successfully Updated for Username: ".$_POST['username'];
                            }
                        }

                        unset($_POST['name']);
                        unset($_POST['surname']);
                        unset($_POST['username']);
                        unset($_POST['passwd']);
                        unset($_POST['confirm_passwd']);
                        unset($_POST['department']);
                        unset($_POST['security_level']);
                    }
                    else {
                        $error = "Database Error; Could not create new user: ".$_POST['username']."<br />Please contact the system administrator!";
                        logmessage("ERROR", "USER MANAGEMENT - USER: ".$_SESSION["USERID"]."; $error");
                    }
				}
				$db->close_connection();
			}
		}
		else {
			$error = "Please ensure that you correctly confirm your password!";
		}		
	}
	else if (isset($_POST['delete_details'])) {
		$record_id = $_POST['id'];
		
		//check security level of record to be deleted
		$query = "select security_level from web_user_list where id = $record_id";
		$error .= $db->open_connection();
		$array_details = $db->list_records($query);
		
		if ($error == "") {
			if (is_array($array_details)) {
				//check the user's permissions
				if ($_SESSION['SECURITY_LEVEL'] > $array_details[0][0]) {
					$error .= "You are not authorised to delete this account!";
					logmessage("INFO", "USER MANAGEMENT - USER: ".$_SESSION["USERID"]."; $error");	
				}
				else {					
					$query = "DELETE FROM web_user_list WHERE ID = $record_id";
					
					//execute delete
					$result = $db->generic_sql($query);	

					if ($result) {
						logmessage("INFO", "USER MANAGEMENT - USER: ".$_SESSION["USERID"]."; user account with ID: $record_id deleted");
						$error = "SUCCESSFULLY DELETED RECORD";
						
						//go back to web_user_list
						header("Location: user_management.php");
					}
					else {
						logmessage("ERROR", "USER MANAGEMENT - USER: ".$_SESSION["USERID"]."; DATABASE ERROR - attempted to delete user with ID: $record_id;");
						$error = "DATABASE ERROR - CONTACT ADMINISTRATOR";
					}
				}			
			}
			else {
				$error = "Could not select record details";
				logmessage("ERROR", "USER MANAGEMENT - USER: ".$_SESSION["USERID"]."; DATABASE ERROR - attempted to delete user with ID: $record_id;".$error);
				
			}
			$db->close_connection();
		}		
		else {
			logmessage("ERROR", "USER MANAGEMENT - USER: ".$_SESSION["USERID"]."; DATABASE ERROR - attempted to delete user with ID: $record_id;");
			$error = "DATABASE ERROR - CONTACT ADMINISTRATOR";
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

		<form name="user_details" action="user_registration.php" method="post">
			<table class="tablebody border" width="100%">
				<tr>
					<th colspan="2" class="tableheader">USER REGISTRATION</th>
				</tr>
				<tr>
					<td>FIRST NAME:</td><td><input type="text" name="name" value="<?php echo $array_user_details['name'][1]; ?>" /></td>
				</tr>
				<tr>
					<td>SURNAME:</td><td><input type="text" name="surname" value="<?php echo $array_user_details['surname'][1]; ?>" /></td>					
				</tr>
				<tr>
					<td>USERNAME:</td><td><input type="text" name="username" value="<?php echo $array_user_details['username'][1]; ?>" /></td>
				</tr>
				<?php
					if ($display_option == 'display') {
				?>				
				<tr>
					<td>PASSWORD:</td><td><input type="password" name="passwd" value="<?php echo $array_user_details['password'][1]; ?>" /></td>
				</tr>
				<tr>
					<td>CONFIRM PASSWORD:</td><td><input type="password" name="confirm_passwd" value="<?php echo $array_user_details['confirm_password'][1]; ?>" />
					</td>
				</tr>
				<?php
					}
				?>
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

						echo build_combo("security_level", $array_security_levels, "combobox", null, $security_level_val); 
					?>
					</td>
				</tr>
				<?php
					if ($display_option == 'edit') {
				?>
				<tr>
					<td colspan ='2'><br /><?php echo "<a href='change_user_password.php?id=$record_id'>Reset Password</a>" ?>					</td>
				</tr>
				<?php
					}
				?>
				<tr>
					<td colspan='2'><br /></td>					
				</tr>
				<tr>
					<?php
						if ($display_option == 'display')
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
