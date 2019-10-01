<?php
	/*select the categories
	$query = "SELECT ID||';'||NAME, NAME FROM CAT WHERE PARENT_ID = 0";
	//$query = "select id, name from cat where parent_id = 0";
	//create database object
	$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
	$db->open_connection();
	$array_load_categories = $db->list_records($query, false);
	$db->close_connection();
	
	for($i=0; $i<count($array_load_categories); $i++)
	{	
		$array_categories[$array_load_categories[$i][0]] = $array_load_categories[$i][1];
	}	
	
	*/

	$array_categories = array(
		'1;Music' => 'Local Music',
		'86;International Music' => 'International Music',
		'8;Games' => 'Games',
		'9;News' =>	'News',
		'10;Comedy' =>	'Comedy',
		'11;Sports' =>	'Sports'	
	);



?>