<?php 
	require_once "../util/functions.php";
	
	$error = "";
	
	insert_header2();

	if (isset($_POST['confirm_deletion']) && $_POST['confirm_deletion'] != "") {
		$array_confirmed = split(";", $_POST['confirm_deletion']);
		$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
			for ($i = 0; $i < count($array_confirmed); $i++) {
				$query = "DELETE FROM page_security WHERE ID = ";
				if ($array_confirmed[$i] != null) {
					$query .= $array_confirmed[$i];					
					$db->generic_sql($query);
					logmessage("INFO", "PAGE SECURITY MANAGEMENT - USER: ".$_SESSION["USERID"]."; page security record with ID: $array_confirmed[$i] deleted");
				}
			}
	}
?>

<div class="cspacer">
	<?php 
		if ($error !=  "") {
			echo "<p class='error'>".$error."</p>";
		}
	?>
	<div align="center" style="text-align: left; width:80%">
	<table class="tablebody border" width="100%">
			<tr>
				<th colspan="2" class="tableheader"><b>PAGE SECURITY MANAGEMENT</b></th>
			</tr>
			<tr>
				<td><a href="page_security_creation.php">Add Record</a></td><td><a href="#" onclick="javascript: confirm_deletion('page_security_list');">Delete Record(s)</a></td>
			</tr>
	</table>
	<br /><br />
	<form name="page_security_list" action="page_security_management.php" method="post">
		<input type="hidden" name="confirm_deletion" id="confirm_deletion" />
		<?php
			$tables = array("page_security");
			$columns = array("page_name", "security_level", "reason");
			$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
			$display_str = $db->display_records($tables, $columns, null, "page_security_list", null, "page_security_creation.php", true);
			echo $display_str;
		?>
	</form>

</div>

<!-- insert the footer -->
<div id="rightcontent">

<?php
	insert_footer2();
?>