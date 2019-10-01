<?php
	//load list of security levels
/*	$array_security_levels = array (
		0 => "Admin",
		1 => "User"
	);
*/	
	//build the data array
	//load security levels from database...
	$query = "SELECT * FROM security_levels";
	//create database object
	$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
	$error = $db->open_connection();
	$array_load_levels = $db->list_records($query, false);
	$db->close_connection();
	
	$array_security_levels = array();
	$temp = array();
	for($i=0; $i<count($array_load_levels); $i++)
	{
		$array_keys[$i] = $array_load_levels[$i][2];	
		$array_values[$i] = $array_load_levels[$i][0];
	}
	
	if (is_array($array_load_levels))
		$array_security_levels = array_combine($array_keys, $array_values);
?>