<?php
	include "../util/functions.php";
	
	$error = "";
	$display_option = "display";
	$record_id = null;	
	insert_header2();		
	
	//initialise data array
	$array_security_details = array (
		'name' => array("Name", "", 1, 2),
		'description' => array("Description", "", 1, 2),
		'security_level' => array("Security Level", "", 1, 1),
		'restrict' => array("Restriction", "", 1, 1)
	);		
	
	//create database object	
	$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
	
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
		$query = "SELECT * FROM security_levels WHERE ID = ".$record_id;
		$error = $db->open_connection();
		$array_details = $db->list_records($query);
		$db->close_connection();
		
		if (is_array($array_details)) {
			//clear data array for next record
			$array_security_details = array (
				'name' => array("Name", $array_details[0][0], 1, 14),
				'description' => array("Description", $array_details[0][1], 1, 14),
				'security_level' => array("Security Level", $array_details[0][2], 1, 1),
				'restrict' => array("Restriction", $array_details[0][4], 1, 1)
			);
		}
		else {
			$error = "Error selecting record";
		}
	}
	
	if (isset($_POST['submit_details'])) {
		$array_security_details = array (
			'name' => array("Name", $_POST['name'], 1, 14),
			'description' => array("Description", $_POST['description'], 1, 14),
			'security_level' => array("Security Level", $_POST['security_level'], 1, 1),
			'restrict' => array("Restriction", $_POST['restrict'], 1, 1)
		);		
		
		if (isset($_POST['id'])) {
			$display_option = 'edit';
		}
		
		//validate details
		$error = validate($array_security_details);
		
		//if no error then save user details to db
		if ($error == "") {
			if ($display_option == 'display') {
				//check if a record for this level already exists
				$query = "SELECT * FROM SECURITY_LEVELS WHERE SECURITY_LEVEL = '".$_POST['security_level']."'";
				$error = $db->open_connection();
				$array_level = $db->list_records($query);
				
				if (!is_array($array_level)) {
					$query = build_insert_sql($array_security_details, "security_levels");	
				}
				else {
					$query = "";
					$error = "A record already exists for this level. Please update.";
				}
			}
			else if ($display_option == 'edit') {
				$query = build_update_sql($array_security_details, "security_levels");
				$query .= "WHERE ID = ".$_POST['id'];
			}

			if ($query != "") {
				//save to db
				$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
				$return = $db->generic_sql($query);
				
				//if record is successfully saved, show message & clear all fields
				if ($return) {
					$array_security_details = array (
						'name' => array("Name", "", 1, 14),
						'description' => array("Description", "", 1, 14),
						'security_level' => array("Security Level", "", 1, 1)
					);			
	
					if ($display_option == 'display') {
						$error .= "Successfully Created Security Level: ".$_POST['name'];
						logmessage("INFO", "SECURITY CREATION - USER: ".$_SESSION["USERID"]."; $error");
					}
					else if ($display_option == 'edit') {
						$error .= "Successfully Updated Security Level: ".$_POST['name'];
						logmessage("INFO", "SECURITY CREATION - USER: ".$_SESSION["USERID"]."; $error");
					}
									
					unset($_POST['name']);
					unset($_POST['description']);
					unset($_POST['security_level']);				
				}
				else {
					$error = "Database Error! Contact Administrator.";
				}
			}
			$db->close_connection();
		}
	}
	else if (isset($_POST['delete_details'])) {
		$record_id = $_POST['id'];
		$query = "DELETE FROM security_levels WHERE ID = $record_id";		
		$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
		$error = $db->open_connection();
		//execute delete		
		$result = $db->generic_sql($query);
		$db->close_connection();
		
		if ($result) {
			logmessage("INFO", "SECURITY CREATION - USER: ".$_SESSION["USERID"]."; security level with ID: $record_id deleted");
			$error = "SUCCESSFULLY DELETED RECORD";
		}
		else {
			logmessage("ERROR", "SECURITY CREATION - USER: ".$_SESSION["USERID"]."; DATABASE ERROR - attempted to delete security_level with ID: $record_id;");
			$error = "DATABASE ERROR - CONTACT ADMINISTRATOR";
		}
		
	}
?>

<div class="cspacer">
	<?php 
		if ($error !=  "") {
			echo "<div  style='text-align: left;width:80%'>\r\n";
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
	
	<div  style="text-align: left;width:80%">

		<form name="user_details" action="security_level_creation.php" method="post">
			<table class="tablebody border" width="100%">
				<tr>
					<th colspan="2" class="tableheader">SECURITY LEVEL CREATION</th>
				</tr>
				<tr>
					<td>NAME:</td><td><input type="text" name="name" value="<?php echo $array_security_details['name'][1]; ?>" /></td>
				</tr>
				<tr>
					<td>DESCRIPTION:</td><td><input type="text" name="description" value="<?php echo $array_security_details['description'][1]; ?>" /></td>					
				</tr>
				<tr>
					<td>SECURITY LEVEL</td><td><input type="text" name="security_level" value="<?php echo $array_security_details['security_level'][1]; ?>" /></td>
				</tr>
				<tr>
					<td>RESTRICTION</td>
					<td>
					<?php		
						$array_security_levels = array(0=>"No", 1=>"Yes");			
						if (isset($array_security_details['restrict'][1]) && $array_security_details['restrict'][1] != "none")
							$security_level_val = $array_security_details['restrict'][1];
						else
							$security_level_val = -1;

						echo build_combo("restrict", $array_security_levels, "combobox", null, $security_level_val); 
					?>
					</td>
				</tr>
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

			</table>
		</form>
	</div>
</div>

<!-- insert the footer -->
<?php
	insert_footer2();
?>