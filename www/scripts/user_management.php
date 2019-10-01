<?php 
	require_once "../util/functions.php";
	
	$error = "";
	if (isset($_GET['message'])) {
		$error = $_GET['message'];
	}
	insert_header2();
	
	if (isset($_POST['confirm_deletion']) && $_POST['confirm_deletion'] != "") {
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
		<table class="tablebody border" width="100%">
			<tr>
				<th colspan="2" class="tableheader"><b>USER MANAGEMENT</b></th>
			</tr>
			<tr>
			<?php
				if ($_SESSION['SECURITY_LEVEL'] == 0) {
					echo "<td><a href='user_registration.php'>Add Record</a></td><td><a href='#' onclick='javascript: confirm_deletion('user_list');'>Delete Record(s)</a></td>";
				}
				else {
					echo "<td><a href='user_registration.php'>Add Record</a></td><td>Delete Record(s)</td>";
				}
			?>
			</tr>
		</table>
		<br /><br />

		<form name="user_list" action="user_management.php" method="post">


	<table class="tablebody border" width="100%">
			<tr>
				<th colspan="2" class="tableheader"><b>SEARCH</b></th>
			</tr>
			<tr>
			<?php
				$username = "";
				if (isset($_POST['username'])) {
					$username = $_POST['username'];
				}
				
			?>
				<td>Username:</td><td><input type='text' value='<?php echo $username ?>' name='username' /></td>				
			</tr>
			<tr>
				<td colspan='2'><input type='submit' name='sub' value='submit' />
			</tr>			
	</table>
		<br /><br />
			<input type="hidden" name="confirm_deletion" id="confirm_deletion" />
			<?php
				$tables = array("web_user_list");
				$columns = array("name", "surname", "username");				
				$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);

				if ($username != "" && ctype_alnum($username)) {
					$advanced = " where username = '".strtolower($username)."'";
					$display_str = $db->display_records($tables, $columns, null, "user_list", $advanced, "user_registration.php", true);
				}
				else {
					$display_str = $db->display_records($tables, $columns, null, "user_list", $advanced, "user_registration.php", true);
				}

				//$db->edit_displayed_records($tables, $columns);
				echo $display_str;
			?>
		</form>
</div>

<!-- insert the footer -->
<?php
	insert_footer2();
?>
