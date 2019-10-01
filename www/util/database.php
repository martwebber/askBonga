<?php

class Database
{
	var $DB_TYPE = "";
	var $link = null;
	var $DATABASEHOST = "";
	var $DATABASEPORT = "";
	var $DATABASEUSER = "";
	var $DATABASEPASSWORD = "";	
	var $DATABASENAME = "";
	
	/** Create a new Database object */
	function Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME) {
		$this->DB_TYPE = $DB_TYPE;
		
		if ($DATABASEHOST == null)
			$this->DATABASEHOST = "localhost";
		else
			$this->DATABASEHOST = $DATABASEHOST;
		
		if ($DATABASEPORT == null) {
			if ($DB_TYPE == "mysql")
				$this->DATABASEPORT = "3306";
			else if ($DB_TYPE == "oracle")
				$this->DATABASEPORT = "1521";
		}
		else
			$this->DATABASEPORT = $DATABASEPORT;
		
		$this->DATABASEUSER = $DATABASEUSER;
		$this->DATABASEPASSWORD = $DATABASEPASSWORD;
		$this->DATABASENAME = $DATABASENAME;
	}
	
	/** Simply writes some data to a file
	* @param $file the file name
	* @param $string the data to be written to the file
	*/
	function file_write($file, $data)
	{
		$fd = fopen($file, 'a+');// or die("Can't open file");
		fwrite($fd, $data);
		fclose($fd);
	}
	
	/** 5 types of log statuses: DEBUG, INFO, WARNING, ERROR, CRITICAL
	* @param $status the type of log message
	* @param $message the content of the message
	*/
	function logmessage($status, $message)
	{
	    $LOG_LEVEL = 0;
	    
		$log_levels = array("DEBUG", "INFO", "WARNING", "ERROR", "CRITICAL");
	    if ($LOG_LEVEL != null) {
	    	for ($i = 0; $i < $LOG_LEVEL; $i++) {
	    		array_shift($log_levels);
			}
			if (!in_array($status, $log_levels)) {
				return;
			}
		}
	    //get today's date and then write to file
	    $today = date("Y-m-d", mktime( date("m"), date("d"), date("Y") ) );
	    $filename = "../logs/DataPromo-".$today;
	    $timestamp = date ("Y-m-d H:i", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));
	
		$message = "[".$status."]\t[".$message."] [".$timestamp."]\n";
		$this->file_write($filename, $message);
	}
	
	/** establishes a connection to the specified database
	* <br /> to check if everything is ok check that $this->link is !null
	*/
	function open_connection()
	{	
		$error = "";
		
		if($this->DB_TYPE == "mysql")
		{
			$this->link = mysql_connect("".$this->DATABASEHOST."", "".$this->DATABASEUSER."", "".$this->DATABASEPASSWORD."");
			if (!$this->link) {				
				$this->logmessage("CRITICAL", "DATABASE: OPEN CONNECTION - Could not connect to database");
				$error = "DATABASE ERROR - Could not connect to database!";
			}
			else {
				$check = mysql_select_db("$this->DATABASENAME");
				if (!$check) {
					$this->logmessage("CRITICAL", "DATABASE: OPEN CONNECTION - Could not select database: ".mysql_error());	
					$error = "DATABASE ERROR - Could not select database!";					
				}
				else {
					$this->logmessage("DEBUG", "DATABASE: OPEN CONNECTION - SUCCESSFUL");
				}
			}				
		}
		else if($this->DB_TYPE == "oracle")
		{		
	        $this->link = ocilogon($this->DATABASEUSER, $this->DATABASEPASSWORD, "//".$this->DATABASEHOST.":".$this->DATABASEPORT."/".$this->DATABASENAME);// or die("Could not connect to database.") ;	
	        $err = ocierror();

	        if (is_array($err)) {
	            $this->logmessage("CRITICAL", "DATABASE (//".$this->DATABASEHOST.":".$this->DATABASEPORT."/".$this->DATABASENAME."): OPEN CONNECTION - Could not connect to database; ". htmlspecialchars($err[ 'message' ]));
		    $error = "DATABASE ERROR - Could not connect to database!";
	            //echo htmlspecialchars('Logon failed: ' . $err[ 'message' ]) . '<br />' . "\n";
	        }
			else {
				$this->logmessage("DEBUG", "DATABASE: OPEN CONNECTION - SUCCESSFUL");
			}
		}
		
		return $error;		
	}
	
	/** simply closes the connection */
	function close_connection()
	{
		if($this->DB_TYPE == "mysql")
		{
			$close = mysql_close($this->link);
			if (!$close) {
				$this->logmessage("WARNING", "DATABASE ERROR: Did not close database without incident");
			}
			else {
				$this->logmessage("DEBUG", "DATABASE: CLOSE CONNECTION - SUCCESSFUL");
			}
		}
		else if($this->DB_TYPE == "oracle")
		{
			$close = oci_close($this->link);
			if (!$close) {
				$err = oci_error();
	            $this->logmessage("WARNING", "DATABASE ERROR: Did not close database without incident: ".$err['code'].": ".$err['message']);
			}
			else {
				$this->logmessage("DEBUG", "DATABASE: CLOSE CONNECTION - SUCCESSFUL");
			}
		}
	}
	
	/** executes an SQL statement that does not return any resultset such as UPDATE, DELETE, DROP, INSERT
	* @param $query the statement to execute
	* @return true if successful; false otherwise
	*/
	function generic_sql($query)
	{
		$this->open_connection();
		$status = null;            
		
		if($this->DB_TYPE == "mysql")
		{
		        $status = mysql_query($query);// or die("Could not perform query: ".mysql_error());
	  	        if (!$status)
		        {
		            $this->logmessage("ERROR", "DATABASE ERROR: Could not perform query: ".mysql_error());
		        }
		        else {
	        		$this->logmessage("INFO", "DATABASE: Successfully executed query");
				}
		}
		else if($this->DB_TYPE == "oracle")
		{
	        $statement = oci_parse($this->link, $query);
	        $err = array();

	        //if error occurs log
	        if (!$statement)
	        {
	                $err = oci_error($this->link);
	                $this->logmessage("ERROR", "DATABASE ERROR: Could not perform query: ".$err['code'].": ".$err['message'].":".$DATABASEHOST);
	                $this->logmessage("DEBUG", $query);
	        }
	        else
	                $status = oci_execute($statement);
	
	        if (!$status)
	        {
	                $err = oci_error($statement);
	                $this->logmessage("ERROR", "DATABASE ERROR: Could not perform query: ".$err['code'].": ".$err['message'].":".$DATABASEHOST);
	                $this->logmessage("DEBUG", $query);
	        }
	        else {
	        	$this->logmessage("DEBUG", "DATABASE: Successfully executed query; $query");
			}
	        oci_free_statement($statement);
		}
		
		//$this->close_connection();
		return $status;
	}
	
	/** executes an SQL statement that returns a resultset such as a SELECT
	* @param $query the statement to execute
	* @param $assoc boolean value to return an associative array if desired
	* <br /> set to true to return associative; false otherwise
	* @return an array containing the records; null if any errors occurred
	*/
	function list_records($query, $assoc = false)
	{
		$this->open_connection();
		
		if($this->DB_TYPE == "mysql")
		{
			$result = mysql_query($query);
			$cnt = 0;
			$recordsArray = NULL;
			$status = true;
			$error = "";
			
			if($result == NULL)
				$status = false;
				
			if($status)
			{
				if(!(mysql_num_rows($result) > 0))
					$status = false;
				else
				{
					if($assoc == true)
					{
						while($rowArray = mysql_fetch_assoc($result))
						{
							$recordsArray[$cnt] = $rowArray;
							$cnt++;
						}
					}
					else
					{
						while($rowArray = mysql_fetch_array($result))
						{
							$recordsArray[$cnt] = $rowArray;
							$cnt++;
						}
					}
					mysql_free_result($result);
				}
			}
		}	
		else if($this->DB_TYPE == "oracle")
	    {
			$statement = oci_parse($this->link, $query);
			
			if (!$statement) { 
				$this->logmessage("ERROR", "Could not perform query: $query; ".oci_error($statement));
				$this->logmessage("DEBUG", $query);
			}	
				
	       	$status = oci_execute($statement);
	
			$cnt = 0;
	       	$recordsArray = NULL;
	       	$status = true;
	       	$error = "";
	
	       	if($statement == NULL)
	           	$status = false;
			
			//echo "Records count: ".oci_num_rows($statement)."<br>";
	
	       	if($status)
	       	{
				if($assoc == true)
				{
					while ( $rowArray = oci_fetch_array($statement, OCI_ASSOC) )
					{
						$recordsArray[$cnt] = $rowArray;
						$cnt++;
					}
				}
				else
				{
					while ( $rowArray = oci_fetch_array($statement, OCI_NUM) )
					{
						$recordsArray[$cnt] = $rowArray;
						$cnt++;
					}
				}
				
				oci_free_statement($statement);
				$this->logmessage("DEBUG", "DATABASE: Successfully retrieved records; $query");
	       	}
	       	else {
	       		$this->logmessage("ERROR", "Could not perform query: $query; ".oci_error($statement));
	       		$this->logmessage("DEBUG", $query);
	       	}
		}
		
		//$this->close_connection();
		return $recordsArray;//will return a NULL if no records were found
	}
	
	//returns an array after selecting records from the db
	function list_records_range($query, $assoc, $row_num, $limit)
	{
		$this->open_connection();
		
		if($this->DB_TYPE == "mysql")
		{
			$result = mysql_query($query);
			$recordsArray = NULL;
			$status = true;
			$error = "";
			
			if($result == NULL)
				$status = false;
				
			if($status)
			{
				$rows = mysql_num_rows($result);
				if(!($rows > 0))
					$status = false;
				else
				{			
					if($assoc == true)
					{
						$counter = 0;
						for($i=$row_num; $i<=($row_num + ($limit - 1)); $i++)
						{
							//no more records
							if ($i == $rows)
								break;
								
							$check = mysql_data_seek($result, $i);
							if (!$check) {
								break;
							}
							else {
								$rowArray = mysql_fetch_assoc($result);
								$recordsArray[$counter] = $rowArray;
								$counter++;
							}					
						}
					}
					else
					{
						$counter = 0;
						for($i=$row_num; $i<=($row_num + ($limit - 1)); $i++)
						{
							//no more records
							if ($i == $rows)
								break;

							$check = mysql_data_seek($result, $i);
							if (!$check) {
								break;
							}
							else {
								$rowArray = mysql_fetch_array($result);
								$recordsArray[$counter] = $rowArray;
								$counter++;
							}
						}
					}
					mysql_free_result($result);
				}
			}
		}		
		else if($this->DB_TYPE == "oracle")
	    {
	    	//echo "<br>".$query;
			$statement = oci_parse($this->link, $query) or die("Could not perform query:".oci_error());
	        $status = oci_execute($statement);
		
	        $recordsArray = NULL;
	        $status = true;
	        $error = "";
			
			$flags = 0;
			
	        if($statement == NULL)
	            $status = false;
	
	        if($status)
	        {
	        	if($assoc == false)
	            {
					 $flags |= OCI_NUM+OCI_FETCHSTATEMENT_BY_ROW;
	
					oci_fetch_all ( $statement, $recordsArray, $row_num, $limit, $flags );
	            }
	            else
	            {
					$flags |= OCI_ASSOC+OCI_FETCHSTATEMENT_BY_ROW;
	
	             	oci_fetch_all ( $statement, $recordsArray, $row_num, $limit, $flags );
	            }
	    	}
			
			oci_free_statement($statement);
		}
	
		$this->close_connection($this->link);
		
		//echo "Array count: ".count($recordsArray)."<br>";
		return $recordsArray;//will return a NULL if no records were found
	}
	
	/** simply returns the number of records that the query returns
	* <br /> N.B: for 'mysql' currently only valid for statements like SELECT or SHOW that return an actual resultset
	* <br /> N.B: for 'oracle' currently returns number of rows affected during statement execution (update/insert/delete) and this does not include rows selected!
	* @param query the SQL statement to execute
	* @return number of records; -1 on error
	*/
	function return_num_records($query)
	{
		//initialise to error
		$num_recs = -1;
		
		$this->open_connection();
				
		if($this->DB_TYPE == "mysql")
		{	
			$result = mysql_query($query);
	
			if($result != NULL) {
				$num_recs = mysql_num_rows($result);
				
				if ($num_recs == false)			
					$num_recs = -1;
			}
		}	
		else if($this->DB_TYPE == "oracle")
		{
			$statement = oci_parse($link, $query);//
			//logmessage("WARNING", " or die("Could not perform query:".oci_error($statement)");
	        
			if($statement != NULL)
	            $num_recs = oci_num_rows($result);
	        else
	            $num_recs = -1;
			
			oci_free_statement($statement);	
		}
	
		$this->close_connection($this->link);
		return $num_recs;//return number of records
	}
	
	/** retrieves data and displays it in a 'nice' looking table
	* @param $table an array containing the tables from which to retrieve our data
	* @param $columns an array containing the columns from which to retrieve our data
	* @param $titles an array containing the titles to display for the columns
	* <br />if null, then the column_names will be used
	* @form_name the name of the form within which this table will be contained
	* <br />N.B: it is absolutely necessary to brace with table with a form which uses POST
	* @param $advanced string containing filtering & sorting options
	* <br />e.g. 'where id > 12'
	* @param url the GET url to build into the first column of the table, e.g an edit page
	* @param id the primary key of the table
	* @return html_string with our table construct; false otherwise
	*/
	function display_records($tables, $columns, $titles, $form_name, $advanced = null, $url = null, $checkbox = null, $id = "ID") {
		//error check
		if (!is_array($columns) || !is_array($tables)) {
			$error = "No tables or columns selected";
			return $error;
		}
		
		if (is_array($titles))
		{
			if (count($titles) != count($columns)) {
				$error = "Columns and Titles have different number of elements!";
				return $error;
			}
		}
		else if ($titles == -1) {
			//do nothing
		}
		else {
			$titles = $columns;
		}
		
		//intialise paging variables
		$record_range = 10;
		$low_watermark = 0;
		$high_watermark = $record_range;
		$current_page = 0;
		if (isset($_GET['nav_mode'])) {
			echo $_GET['nav_mode'];
			$low_watermark = 0;
			$high_watermark = $record_range;
		}
		else {
			if (isset($_POST['record_range']) && $_POST['record_range'] != $_POST['previous_range']) {				
				$current_page = 0;
				$record_range = $_POST['record_range'];
				$low_watermark = 0;
				$high_watermark = $record_range;
			}
			else if (isset($_POST['page'])) {				
				$current_page = $_POST['page'];
				$record_range = $_POST['record_range'];
				$low_watermark = ($_POST['page'] - 1) * $record_range;
				$high_watermark = $low_watermark + $record_range;				
			}
			/*else {
				if (isset($_GET['record_range']) && $_GET['record_range'] != "") {				
					$record_range = $_GET['record_range'];
				}
				$low_watermark = $_GET['low_watermark'];
				$high_watermark = $_GET['high_watermark'];
			}		*/
		}

		//build query
		$query = build_select_sql($tables, $columns, $id);
		
		//add $advanced options
		if ($advanced != null)  {
			$query .= " ".$advanced;
		}
		
		//echo $query;
		//execute query
		$array_records = $this->list_records_range($query, false, $low_watermark, $record_range);
		if ($this->DB_TYPE == "mysql")
			$total_records = $this->return_num_records($query);
		else {
		//	if (!isset($_SESSION['TOTAL_RECORDS'])) {
				$this->open_connection();
				$array_total_records = $this->list_records($query);
				$_SESSION['TOTAL_RECORDS'] = count($array_total_records);
				$this->close_connection();
			//}
			$total_records = $_SESSION['TOTAL_RECORDS'];
		}
		
		//if an error occurred return it
		if (!is_array($array_records)) {
			$error = "Could not select records; Query: $query";
			return $error;
		}
		
		//build tables and place titles
		$table_string = "<table class='tablebody border' width='100%' >\r\n";
		//build titles
		if (is_array($titles)) {
			$table_string .= "<tr>\r\n";
			$table_string .= "<th class='tableheader'>ROW</th>";
			foreach ($titles as $value) {			
				$table_string .= "<th class='tableheader'>".strtoupper($value)."</th>\r\n";
			}
			$table_string .= "</tr>\r\n";
		}
		
		//if there are no records to display...
		if (count($array_records) == 0) {
			$table_string .= "<tr class='oddrow' >\r\n";
			$no_columns = count($columns) + 1;
			$table_string .= "<td colspan='$no_columns'><b>NO RECORDS TO DISPLAY</b></td>\r\n";
			$table_string .= "</tr>\r\n";
		}
		//put in data
		$row_type = "odd";
		for ($row = 0; $row < count($array_records); $row++) {
			if ($row_type == "odd") {
				$table_string .= "<tr class='oddrow' >\r\n";
				$row_type = "even";
			}
			else {
				$table_string .= "<tr class='evenrow' >\r\n";
				$row_type = "odd";
			}

			//$table_string .= "<tr>\r\n";
			$row_counter = $row + $low_watermark + 1;
			if ($checkbox != null)
				$table_string .= "<td>$row_counter&nbsp;<input type='checkbox' name='record_id' value='".$array_records[$row][0]."'  /></td>";
			else
				$table_string .= "<td>$row_counter</td>";
			
			if ($id == "") {
				$column = 0;
			} else {
				$column = 1;
			}
		
			for (; $column <= count($columns); $column++) {
				$table_string .= "<td>\r\n";
								
				if ($column == 1 && $url != null) {
				//if ($column == 1) {				
					//build get url
					$vars = "?id=".$array_records[$row][0]."&display_option=edit";
					//add $_GET variable															
					$table_string .= "<a class='tablebody' href=".$url.$vars.">".$array_records[$row][$column]."</a>";
				}
				else {					
					/*if ($column == 1) {
						$table_string .= "<input type='checkbox' name='$array_records[$row][0]' value='$array_records[$row][0]'>";
					}*/
						
					$table_string .= $array_records[$row][$column];					
				}
				$table_string .= "<br>"."</td>\r\n";
			} 
			$table_string .= "</tr>\r\n";
		}
				
		$table_string .= "</table>\r\n";
		$table_string .= "<br />\r\n";
		
		//add paging
		$pages = ceil($total_records/$record_range);
		if ($current_page == 0)
			$current_page = 1;
		
		$table_string .= "<span class='tablebody'>Page: ";
		$table_string .= build_combo_int("page", 1, $pages, "combobox", "document.$form_name.submit", $current_page, "--");
		$table_string .= "No of Records: ";
		$array_record_range = array(5=>"5", 10=>"10", 20=>"20", 50=>"50", 100=>"100");
		
		if (!isset($_POST['record_range']))
			$range = 10;
		else
			$range = $_POST['record_range'];
			
		//insert hidden field to keep track of record_range, if changed reset page to 1
		$table_string .= "<input type='hidden' value='$range' name='previous_range' />";
		$table_string .= build_combo("record_range", $array_record_range, "combobox", "document.$form_name.submit", $range);
		$table_string .="</span>";
		
		return $table_string;		

	}

}
?>
