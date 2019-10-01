<?php
	include "../util/functions.php";
	include "../util/security_level_list.php";
	
	$error = "";
	$display_option = "display";
	$record_id = null;	
	insert_header2();		
	
	//populate array with entire list of downloads
	//open directory handle
	$page_directory = ".";
	//$page_directory = "/usr/local/Zend/apache2/htdocs/PCK_admin";
	$dh = opendir($page_directory);
	$array_pages = array ();
	$array_no_display= array("temp.html", "temp.php", "temp_pp.php", "temp_pp.html", "sample.pdf", ".project", "index.php", "login.php", "logout.php", "unauthorized_access.php", "please_login.php");
	
	if (is_dir($page_directory)) {
	    if ($dh = opendir($page_directory)) {
	        while (($file = readdir($dh)) !== false) {
				if (!is_dir($file)) {
					$temp = array ($file => $file);
					$array_pages = array_merge($array_pages, $temp); 
	            }
	        }
	        closedir($dh);
	    }
	}
	$array_pages = array_diff($array_pages, $array_no_display);
	
	//initialise data array
	$array_page_details = array (
		'page_name' => array("Name", "", 1, 14),
		'reason' => array("Reason", "", 1, 14),
		'security_level' => array("Security Level", "", 1, 1)
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
		$query = "SELECT * FROM page_security WHERE ID = ".$record_id;
		$error = $db->open_connection();
		$array_details = $db->list_records($query);
		$db->close_connection();

		if (is_array($array_details)) {
			//clear data array for next record
			$array_page_details = array (
				'page_name' => array("Name", $array_details[0][0], 1, 14),
				'reason' => array("Reason", $array_details[0][2], 1, 14),
				'security_level' => array("Security Level", $array_details[0][1], 1, 1)
			);
		}
		else {
			$error = "Error selecting record";
		}
	}
	
	if (isset($_POST['submit_details'])) {
		$array_page_details = array (
			'page_name' => array("Name", $_POST['page_name'], 1, 14),
			'reason' => array("Reason", $_POST['reason'], 1, 14),
			'security_level' => array("Security Level", $_POST['security_level'], 1, 1)
		);		
		
		if (isset($_POST['id'])) {
			$display_option = 'edit';
		}
		
		//validate details
		$error = validate($array_page_details);
		
		//if no error then save user details to db
		if ($error == "") {
			if ($display_option == 'display') {
				//check if a record for this page already exists
				$query = "SELECT * FROM PAGE_SECURITY WHERE PAGE_NAME = '".$_POST['page_name']."' AND SECURITY_LEVEL = ".$_POST['security_level'];
				$error = $db->open_connection();
				$array_page = $db->list_records($query);
				
				if (!is_array($array_page)) {
					$query = build_insert_sql($array_page_details, "page_security");	
				}
				else {
					$query = "";
					$error = "A record already exists for this page. Please update.";
				}			
			}
			else if ($display_option == 'edit') {
				$query = build_update_sql($array_page_details, "page_security");
				$query .= "WHERE ID = ".$_POST['id'];
			}

			if ($query != "") {
				//save to db
				$db->generic_sql($query);
				
				//if record is successfully saved, show message & clear all fields
				$array_page_details = array (
					'name' => array("Name", "", 1, 14),
					'reason' => array("Reason", "", 1, 14),
					'security_level' => array("Security Level", "", 1, 1)
				);		
	
				if ($display_option == 'display') {
					$error .= "Record Successfully Created Page Security For: ".$_POST['page_name'];
					logmessage("INFO", "PAGE SECURITY CREATION - USER: ".$_SESSION["USERID"]."; $error");
				}
				else if ($display_option == 'edit') {
					$error .= "Record Successfully Updated Page Security For: ".$_POST['page_name'];
					logmessage("INFO", "PAGE SECURITY CREATION - USER: ".$_SESSION["USERID"]."; $error");
				}
				
				unset($_POST['page_name']);
				unset($_POST['reason']);
				unset($_POST['security_level']);
			}
			$db->close_connection();
		}
	}
	else if (isset($_POST['delete_details'])) {
		$record_id = $_POST['id'];
		$query = "DELETE FROM page_security WHERE ID = $record_id";
		$error = $db->open_connection();		
		//execute delete		
		$result = $db->generic_sql($query);
		$db->close_connection();
		
		if ($result) {
			logmessage("INFO", "PAGE SECURITY CREATION - USER: ".$_SESSION["USERID"]."; page security record with ID: $record_id deleted");
			$error = "SUCCESSFULLY DELETED RECORD";
		}
		else {
			logmessage("ERROR", "PAGE SECURITY CREATION - USER: ".$_SESSION["USERID"]."; DATABASE ERROR - attempted to delete page_security with ID: $record_id;");
			$error = "DATABASE ERROR - CONTACT ADMINISTRATOR";
		}
		
	}
?>

<div id="centercontent">
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
	
	<div  style="text-align: left; width: 80%">
		<br /><br />
		<form name="page_details" action="page_security_creation.php" method="post">
			<table class="tablebody border" width="100%">
				<tr>
					<th colspan="2" class="tableheader">PAGE SECURITY CONFIGURATION</th>
				</tr>
				<tr>
					<td>PAGE NAME:</td><td>
					<?php
						if (isset($array_page_details['page_name'][1]) && $array_page_details['page_name'][1] != "none")
							$page_name_val = $array_page_details['page_name'][1];
						else
							$page_name_val = -1;
							
						echo build_combo("page_name", $array_pages, "combobox", null, $page_name_val); 
					?>				
					</td>
				</tr>
				<tr>
					<td>SECURITY LEVEL</td><td>
					<?php
						if (isset($array_page_details['security_level'][1]) && $array_page_details['security_level'][1] != "none")
							$security_level_val = $array_page_details['security_level'][1];
						else
							$security_level_val = -1;
							
						echo build_combo("security_level", $array_security_levels, "combobox", null, $security_level_val); 
					?>					
				</tr>
				<tr>
					<td>REASON:</td><td><textarea name="reason"><?php echo $array_page_details['reason'][1]; ?></textarea></td>					
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
