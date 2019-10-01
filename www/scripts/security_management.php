<?php 
	require_once "../util/functions.php";
	
	$error = "";
	
	insert_header2();

	if (isset($_POST['confirm_deletion']) && $_POST['confirm_deletion'] != "") {
		$array_confirmed = split(";", $_POST['confirm_deletion']);
		$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
			for ($i = 0; $i < count($array_confirmed); $i++) {
				$query = "DELETE FROM security_levels WHERE ID = ";
				if ($array_confirmed[$i] != null) {
					$query .= $array_confirmed[$i];					
					$db->generic_sql($query);
					logmessage("INFO", "SECURITY MANAGEMENT - USER: ".$_SESSION["USERID"]."; security level with ID: $array_confirmed[$i] deleted");
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
	<div align="center" style="text-align: left; width:80%" >

	<table class="tablebody border" width="100%">
		<tr>
			<th colspan="2" class="tableheader"><b>SECURITY LEVEL MANAGEMENT</b></th>
		</tr>
			<tr>
				<td><a href="security_level_creation.php">Add Record</a></td><td><a href="#" onclick="javascript: confirm_deletion('security_list');">Delete Record(s)</a></td>
			</tr>
	</table>
	<br /><br />

	<form name="security_list" action="security_management.php" method="post">
		<input type="hidden" name="confirm_deletion" id="confirm_deletion" />
		<?php
			$tables = array("security_levels");
			$columns = array("name", "description", "security_level", "restrict");
			$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
			$advanced = " order by security_level";
			
			//issue warning
			/*echo "<div style='text-align: left; width: 100%'>\r\n";
			echo "<table class='tablebody border' width='100%'>\r\n";
			echo "<th class='tableheader'>MESSAGE</th>\r\n";
			echo "<tr><td>\r\n";
			echo "<p class='error'>Please note that if you make a security level 'restriced' then the security settings DO NOT escalate.<br />";
			echo "This means that you will have to add this security level specifically when confugiring page security!!";
			echo "</p>";
			echo "</td></tr>\r\n";
			echo "</table>\r\n";
			echo "<br /><br />\r\n";
			echo "</div>\r\n";			*/
			
			$display_str = $db->display_records($tables, $columns, null, "security_list", $advanced, "security_level_creation.php", true);
			//$db->edit_displayed_records($tables, $columns);
			echo $display_str;
		?>
	</form>

</div>

<!-- insert the footer -->
<?php
	insert_footer2();
?>