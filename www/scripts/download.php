<?php
	require_once "util/functions.php";
	
	$error = "";
	$message = "";
	$disclaimer = "";
	$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);		

	//adjust our image size to fit our predefined images sizes in steps of 5 from 100 to 260
	function round20($val) {
		$x = $val;

		if( !($x % 20) ) {
			$size = ($x);
		}
		else {
			//return((intval($x/5)+1)*5);
			$size = intval(($x/20))*20;
		}
		
		if ($size < 100) {
			return 100;
		}
		else if ($size > 260) {
			return 260;
		}
		else {
			return $size;
		}
	}		

	//function that checks if this is a browser; probably not full proof but will take care of the common ones
	function is_browser($http_user_agent) {
		if (strstr($http_user_agent, 'Opera Mini')) {
			return false;	
		}
		else {
			if (strstr($http_user_agent, 'Windows NT 6.0') || strstr($http_user_agent, 'Macintosh') || strstr($http_user_agent, 'i686') || strstr($http_user_agent, 'Konqueror') || strstr($http_user_agent, 'Wget') || strstr($http_user_agent, 'Lynx')) {
				return true;
			}
			else {
				if (strstr($http_user_agent, 'Windows NT 5.1') && strstr($http_user_agent,'Mozilla')) {
					return true;
				}
				else {
					return false;
				}
			}
		}
	}

	//open directory handle
	//$download_directory = "/var/www/eric";
	$download_directory = "/usr/local/apache2/htdocs/DataPromo/clips";		
	
	//validate filenames
	function name_verifier($filename) {
		$pattern = "[A-Za-z0-9. _&+-]+";
		$match = true;
		
		if (ereg($pattern, $filename)) {
			$value = ereg_replace($pattern, '', $filename);
		}
		else {
			$match = false;
		}
		
		if ($match && $value == '') {
			return true;
		}
		else {
			return false;
		}
	}
	

	//validate numbers
	function digit_verifier($value) {
		if (!ctype_digit($value)) {
			return false;
		}
		else {
			return true;
		}
	}

	if ((!isset($_GET['clip_name'])) && (!isset($_GET['cat']))) {
		$error = "Please start from the index page to correctly browse the site!";
	}

	//check that if the page is set it is a number
	if (isset($_GET['page']) && $_GET['page'] != "") {
		if (digit_verifier($_GET['page'])) {
			$page = $_GET['page'];
		}
		else {
			$error = "Please do not tamper with the parameters!";
		}
	}
	else {
		$page = 0;
	}

	//validate the 3gp field
	$capable_3gp = 0;
	if (isset($_GET['3gp']) && $_GET['3gp'] != "") {
		$capable_3gp = $_GET['3gp'];
		if (ctype_digit($capable_3gp) && ($capable_3gp == 1 || $capable_3gp == 0)) {
			//do nothing
		}
		else {
			$error .= "Please do not tamper with the parameters!";
		}
	}

	if (isset($_GET['clip_name'])) {
		//get msisdn info from headers
	        foreach($_SERVER as $key_name => $key_value) {
       		 	logmessage("INFO", $key_name . " = " . $key_value);
      		}
        
       		$file_name = $_GET['clip_name'];
        	//validate the parameters, just to make sure the sub isn't trying anything funny!
	        $f_verify = name_verifier($file_name);
		$cat_verify = name_verifier($_GET['cat']);
		$cat_id_verify = digit_verifier($_GET['cat_id']);
		$file_id = $_GET['id'];
		$id_verify = digit_verifier($file_id);
        
		if ($f_verify && $cat_verify && $cat_id_verify && $id_verify) {
			//$ext = '3gp';
			$ext = $_GET['mime_type'];
			//possible extensions
			$array_ext =  array("html"=>"text/html", "txt"=>"text/plain", "jpg"=>"image/jpg", "gif"=>"image/gif", "zip"=>"application/zip", 
				"pdf"=>"application/pdf", "midi" => "audio/midi", "mp3"=>"audio/mp3", "3gp"=>"video/3gpp", "jar" => "application/jar");
			
			
			for ($k = 0; $k < count($array_ext); $k++)
			{
				foreach ($array_ext as $key=>$value)
				{
					if ($ext == $key)
						$content = $value;
				}
			}
	        
		$msisdn = "";
	        if (isset($_SERVER['HTTP_X_JINNY_CID'])) {
	        	$msisdn = $_SERVER['HTTP_X_JINNY_CID'];
	        }
	        //echo $msisdn;       
	
	        //if (isset($_SERVER['HTTP_X_JINNY_APN']) && isset($_SERVER['HTTP_X_JINNY_SGSN_ID'])) {	        	
				$doc = $file_name;
				
				if (isset($_SESSION['GENRE'])) {
					$category = $_SESSION['CATEGORY'];
					$genre = $_SESSION['GENRE'];
					$file_name = "clips/$genre/$file_name";
				}
				else if (isset($_GET['genre'])) {
					$genre = $_GET['genre'];
					
					//validate the genre, just to be sure the sub isn't trying anything funny!
					$f_verify = name_verifier($genre);
					
					if ($f_verify) {
						$file_name = "clips/$genre/$file_name";
					}
					else {
						$file_name = "clips/$genre/$file_name";
						$error = "Please do not tamper with the parameters!";
					}
				}
				else {
					//$file_name = "clips/$file_name";
					$error = "Technical error. Please try again later.";
				}
				logmessage("INFO", $file_name);
				//echo $file_name." ".$content;
				
				if ($error == "") {
					//$file_name = urlencode($file_name);
					//echo $file_name; 
					$file_name = ereg_replace("[ ]", "_", $file_name);
					header("Location: ".$file_name);
					write_cdr($msisdn, $doc, $file_id);	
					/*if($fn = fopen($file_name, "rb")) {
						header("Content-Type: $content");
						//header("Content-transfer-encoding: binary");
						header("Content-Length: ".filesize($file_name)); 
						header('Content-Disposition: attachment; filename="'.$doc.'"');
						header('Content-Transfer-Encoding: binary');
						//read data
						//$doc = "sample.pdf";
						//readfile("reports\\".$doc);
						//ensure no caching
						header('Cache-Control: max-age=3600, must-revalidate');
						header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
						header('Pragma: public');
						
					  	fpassthru($fn);
			  			fclose($fn);
									
						$message = "DOWNLOADS: User ID - ".$msisdn." DOWNLOADED ".$doc;
						logmessage("INFO", $message);	
						write_cdr($msisdn, $doc);	
					}
					else {
			  			//exit("error....");
						$error = "Technical error. Please try again later.";
					}	
*/
					
				}
				else {
		  			//exit("error....");
					$error = "Technical error. Please try again later.";
				}
        	/*}
        	else {
        		$message = "Dear Safaricom subscriber, please <a href='download.php?cat=".$_GET['cat']."&cat_id=".$_GET['cat_id']."&3gp=$capable_3gp&page=$page'>refresh this page by clicking here.</a><br />The more you download the greater your chances to win.<br />";
        		$error = "This service is exclusive to Safaricom subscribers.";
        	}*/
		}
		else {
			$error = "Please do not tamper with the parameters!";
		}
	}
	else if (isset($_GET['cat']) && isset($_GET['cat_id'])) {
		$prev_parent_id = 0;
		//determine our genre
		$parent_id = $_GET['cat_id'];
		
		//verify cat name just to be sure sub isn't trying anything funny!
		$f_verify = name_verifier($_GET['cat']);
		//also confirm that the cat_id is a number
		if (!ctype_digit($parent_id)) {
			$error .= "Please ensure cat_id is a valid number.";
		}
		
		//$f_verify = true;
		if ($f_verify && $error == "") {		
			$query = "select parent_id from cat where id = ".$parent_id;
			$array_pid = $db->list_records($query);
			$pid = $array_pid[0][0];		 
			//details for the back button
			$prev_parent_id = $pid; 
			
			$db->open_connection();
			if ($pid == 0) {
				$query = "select parent_id, name from cat where id = ".$parent_id;
				$array_pid = $db->list_records($query);
			}
			else {
				while ($pid != 0) {
					$id = $pid;
					$query = "select parent_id, name from cat where id = ".$id;
					
					$array_pid = $db->list_records($query);
					$pid = $array_pid[0][0];					
				}
				//$_SESSION['GENRE'] = $id;
			}						
			$db->close_connection();
			
			$_SESSION['GENRE'] = $array_pid[0][1];
			$genre = $_SESSION['GENRE'];
			$_SESSION['CATEGORY'] = $_GET['cat'];
			
			echo $_SESSION['GENRE'];
			
			$query = "select clip_name, url_name, mime_type, id from videoclips where category=".$_GET['cat_id'];
			$query .= " and approval_status = 1 order by reviewed_on desc";
			$error = $db->open_connection();
			$page_size = 10;
			$array_temp = $db->list_records_range($query, false, $page_size*$page, $page_size+1);
			//let's check if we should display the 'next' link
			$next = false;
			if (count($array_temp) > 10) {
				array_pop($array_temp);
				$next = true;
			}

			$video = false;
			
			$array_download_list = array();
			for ($i = 0; $i < count($array_temp); $i++) {
				$array_download_list[$array_temp[$i][0]] = $array_temp[$i][1];

				if (!$video && $array_temp[$i][2] == '3gp') {
					$video = true;
				}
			}
			
			//check if device is 3gp capable
			if ((isset($_SESSION['3GP_CAPABLE']) && $_SESSION['3GP_CAPABLE'] == 1) || !$video) {
				//relax
			}
			else if ($capable_3gp == 1) {
				//relax
			}
			else {
				$disclaimer = "Please note your phone may not support video downloads.<br />Download audio content to stand 
		a chance to win great prizes.";
			}
		}
		else {
			$error = "Please do not tamper with the parameters!";
		}
	}
	else {
		$error = "Please do not tamper with the parameters!";
	}
?>

<html>
<!--
   HTML 3.2
   Document type as defined on http://www.w3.org/TR/REC-html32
-->
<head>
		<title>wap.safaricom.com</title>
		<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen">
</head>
<body>
<div>	
	<center>
	<div style='background-color:white'>
	<span style='font-size:80%;color:#FFFFFF;font-weight:bold;'>
			<?php 
				if (isset($_SESSION['MAX_WIDTH'])) {
					$banner_width = round20($_SESSION['MAX_WIDTH']);
				}
				else {
					$banner_width = 160; 
					$_SESSION['MAX_WIDTH'] = 160;
				}
				
				//echo "<img src='images/safaricom-".$banner_width."pixel.jpg' size='".$_SESSION['MAX_WIDTH']."' />";
		$width=$_SESSION['MAX_WIDTH']-50;
		echo "<img src='images/safaricom-".$banner_width."pixel.jpg' width='".$width."' />&nbsp;&nbsp;";
		if ($_SESSION['CATEGORY'] == 'KTN') {
			echo "<img src='images/KTN.jpg' width='50' />";					 
		}
		else if ($_SESSION['CATEGORY'] == 'NTV') {
			echo "<img src='images/NTV.jpg' width='50' />";					 
		}
		else if ($_SESSION['CATEGORY'] == 'Citizen') {
			echo "<img src='images/CTV.jpg' width='50' />";					 
		}
		else if ($genre == 'Music' || $genre == 'International Music') {
			echo "<img src='images/MCSK.jpg' width='50' />";
		}
		else if ($genre == 'Obama') {
			echo "<img src='images/VOA.jpg' width='50' />";
		}
			?>	
	</span>
	</div><br />
		<?php
			if ($prev_parent_id != 0) {
				echo "<a href='index.php?id=".$prev_parent_id."'>Back</a>&nbsp;&nbsp;&nbsp;&nbsp;";					
			}
			else {
				echo "<a href='index_graphics.php'>Back</a>&nbsp;&nbsp;&nbsp;&nbsp;";
			}
			echo "<a href='index_graphics.php'>Home</a><br />";
		?>	
	<div style="width:80%;text-align:left">
	
	<?php 
		if ($error !=  "") {
			echo "<div style='text-align: left; width: 80%'>\r\n";
			//echo "<table class='tablebody border' width='100%'>\r\n";
			//echo "<td class='tableheader'>MESSAGE</td>\r\n";
			//echo "<tr><td>\r\n";
			echo "<p class='message'>".$message."</p>";
			echo "<p class='error'>".$error."</p>";
			if ($message != "") {
				echo "<br /><p class='message'>Safaricom the better option</p>";
			}
			//echo "</td></tr>\r\n";
			//echo "</table>\r\n";
			//echo "<br /><br />\r\n";
			echo "</div>\r\n";	
		}
		
		if ($disclaimer !=  "") {
			//echo "<div style='text-align: left; width: 80%'>\r\n";
			echo "<p class='message'>".$disclaimer."</p>";
			//echo "</div>\r\n";	
		}
	?>
	
	<?php 
		if ($error == "") {	
	?>
	<form id="download_form" name="download_form" action="download.php" method="post">
		<table class='tablebody border' width='100%'>
			<tr>
				<th class='tableheader'></th>
			</tr>
			<?php							
				$rowtype = "odd";
				
				if (count($array_download_list) == 0) {
					echo "<tr class='oddrow' ><td>Sorry, no downloads in this category <br />Please check again later.</td></tr>";
				}
				
				$index = 0;
				
				if (isset($_SERVER["HTTP_USER_AGENT"])) {
					$browser = is_browser($_SERVER["HTTP_USER_AGENT"]);
				}
				else {
					$browser = true;
				}
				$browser = false;
	
				if (!$browser) {
					foreach ($array_download_list as $key => $value) {
						if ($rowtype == "odd") {
							echo "<tr class='oddrow' >";
							$rowtype = "even";
						}
						else {
							echo "<tr class='evenrow' >";
							$rowtype = "odd";
						}
			?>						
				<td><br />
				<?php
						//echo build_combo("report", $array_download_list, "combobox", null, null);
						//print_r($array_download_list);
						
						$genre_ = urlencode($genre);
						$key_ = urlencode($key);
						echo "<br /><a href='download.php?id=".$array_temp[$index][3]."&genre=$genre_&clip_name=$key_&mime_type=".$array_temp[$index][2]."&cat=".$_GET['cat']."&cat_id=".$_GET['cat_id']."&3gp=$capable_3gp'>".$value."</a>";
						//$url = "clips/".$_SESSION['GENRE']."/$key";
						//echo "<br /><a href='$url'>".$value."</a>";
						$index++; 
				?>
				</td>							
			<?php 
					}
					echo "</tr>";
				}	
				else {
					foreach ($array_download_list as $key => $value) {
						if ($rowtype == "odd") {
							echo "<tr class='oddrow' >";
							$rowtype = "even";
						}
						else {
							echo "<tr class='evenrow' >";
							$rowtype = "odd";
						}
			?>						
				<td><br />
				<?php
						//echo build_combo("report", $array_download_list, "combobox", null, null);
						//print_r($array_download_list);
						
						echo "<br />$value";
						//$url = "clips/".$_SESSION['GENRE']."/$key";
						//echo "<br /><a href='$url'>".$value."</a>";
						$index++; 
				?>
				</td>							
			<?php 
					}
					echo "</tr>";
				}	
			?>
			<tr>				
				<td>
					<?php
						if ($page == 0) {
							$back = $page;
						}
						else {
							$back = $page - 1;
						}
						$forward = $page + 1;
						if ($next) {
							echo "<br /><br /><a href='download.php?cat=".$_GET['cat']."&cat_id=".$_GET['cat_id']."&page=$back'>Previous</a>&nbsp;&nbsp;&nbsp;<a href='download.php?cat=".$_GET['cat']."&cat_id=".$_GET['cat_id']."&page=$forward'>Next</a><br /><br />";
						}
						else {
							echo "<br /><br /><a href='download.php?cat=".$_GET['cat']."&cat_id=".$_GET['cat_id']."&page=$back'>Previous</a>&nbsp;&nbsp;&nbsp;Next<br /><br />";
						}		
					?>
				</td>
			</tr>
			<tr>
				<td>
					<br /><br />
					<?php 
						if ($_SESSION['CATEGORY'] == 'KTN') {
							//echo "For more go to <span style='text-decoration: underline'>www.eastandard.net</span>";	 
							echo "For more go to <a href='http://www.eastandard.net/mobile/'>www.eastandard.net</a>";	 
						}
						else if ($_SESSION['CATEGORY'] == 'NTV') {
							//echo "For more go to <span style='text-decoration: underline'>www.eastandard.net</span>";	 
							echo "For more go to <a href='http://mobile.nation.co.ke'>mobile.nation.co.ke</a>";	 
						}
						else if ($_SESSION['CATEGORY'] == 'Citizen') {
							//echo "For more go to <span style='text-decoration: underline'>www.eastandard.net</span>";	 
							echo "For more go to <a href='www.citizentv.co.ke'>www.citizentv.co.ke</a>";	 
						}
						else if ($genre == 'Music' || $genre == 'International Music') {
							//echo "For more go to <span style='text-decoration: underline'>www.ims.co.ke/wap</span>";
							echo "For more go to <a href='http://www.ims.co.ke/waps/'>www.ims.co.ke/waps</a>";
						}
						else if ($genre == 'Obama') {
							//echo "For more go to <span style='text-decoration: underline'>www.ims.co.ke/wap</span>";
							echo "For more go to <a href='http://www.voanews.com/english/mobile/'>www.voamobile.com</a>";
						}

						
					?>
				</td>
			</tr>
		</table>
	</form>
	<?php 
		}
	?>
	</div>
	</center>
</div>
</body>
</html>

