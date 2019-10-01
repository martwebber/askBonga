<?php 
	require_once "util/functions.php";
	
        foreach($_SERVER as $key_name => $key_value) {
        	logmessage("DEBUG", $key_name . " = " . $key_value);
        }

	$error = "";
	$children = true;
	$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
	$array_categories = array();
	
	function round20($val) {
		$x = $val;
		
		if( !($x % 20) ) {
			$size = ($x);
		}
		else {
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

	if (!isset($_GET['id'])) {
		$query = "select id, name from cat where parent_id = 0";
		$error = $db->open_connection();
		$array_categories = $db->list_records($query);
		$db->close_connection();
	}
	else {		
		$parent_id = $_GET['id'];	

		//also confirm that the cat_id is a number
		if (!ctype_digit($parent_id)) {
			$error .= "Please do not tamper with the parameters!";
		}
		//validate the 3gp field
		$capable_3gp = 0;
		if (isset($_GET['3gp']) && $_GET['3gp'] != "") {
			$capable_3gp = $_GET['3gp'];
			if (ctype_digit($capable_3gp) && ($capable_3gp == 1 || $capable_3gp == 0)) {
				//do nothing
			}
			else {
				$error = "Please do not tamper with the parameters!";
			}
		}

		if ($error == "") {
			$query = "select id, name from cat where parent_id = ".$parent_id." order by name";
			$error = $db->open_connection();
			$array_categories = $db->list_records($query);
			$query = "select parent_id from cat where id = ".$parent_id;
			$array_back = $db->list_records($query);
			$db->close_connection();
			
			//check if this id has any children
			if (count($array_categories) > 0) {
				//$children = true;
			}
			else {
				if ($parent_id == 89) {
					//continue to feedback textarea	
				}
				else if ($parent_id == 88) {
					//header("Location: terms.php");
					header("Location: as_requested.php");
				}
				else if ($parent_id == 101) {
					header("Location: email_links.html");
				}
				else if ($parent_id == 99) {
					header("Location: social_networks.html");
				}
				else if ($parent_id == 100) {
					header("Location: more_links.html");
				}
				else if ($parent_id == 116) {
					header("Location: games_links.html");
				}
				else {
					$query = "select id, name from cat where id = ".$parent_id;
					//echo "$query<br/>";
					$error = $db->open_connection();
					$array_categories = $db->list_records($query);
					$db->close_connection();
					
					$download_page = "download.php?cat=".urlencode($array_categories[0][1])."&cat_id=".urlencode($array_categories[0][0])."&3gp=$capable_3gp";
					header("Location: $download_page");
				}
			}
		}		
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
	<center>
	<div>	
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
		<div style='background-color:white'>
			<span style='font-size:80%;color:#FFFFFF;font-weight:white;'>
			<?php 
				if (isset($_SESSION['MAX_WIDTH'])) {
					$banner_width = round20($_SESSION['MAX_WIDTH']);
				}
				else if (isset($_GET['max_width'])) {
					if ($_GET['max_width'] != "") {
                				if (!ctype_digit($_GET['max_width'])) {
							$error .= "Please do not tamper with the parameters!";        
						}
					}
					else {
						//do nothing
					}

					if ($error == "") {
						$banner_width = round20($_GET['max_width']);
					}
					else {
						$banner_width = 160;
					}
				}
				else {
					$banner_width = 160; 
					$_SESSION['MAX_WIDTH'] = 160;
				}
				
				$width=$_SESSION['MAX_WIDTH']-50;
				echo "<img src='images/safaricom-".$banner_width."pixel.jpg' width='".$width."' />&nbsp;&nbsp;&nbsp;&nbsp;";
				
				$ktn_array = array(128);
				$citizen_array = array(127);
				$ntv_array = array(129);
				$mcsk_array = array(1, 2, 3, 86, 90, 91);
				$voa_array = array(112, 113, 114);
				
				if (in_array($parent_id, $ktn_array)) {
					echo "<img src='images/KTN.jpg' width='50' />";					 
				}
				else if (in_array($parent_id, $mcsk_array)) {
					echo "<img src='images/MCSK.jpg' width='50' />";
				}
				else if (in_array($parent_id, $voa_array)) {
					echo "<img src='images/VOA.jpg' width='50' />";
				}
				else if (in_array($parent_id, $ntv_array)) {
					echo "<img src='images/NTV.jpg' width='50' />";
				}
				else if (in_array($parent_id, $voa_array)) {
					echo "<img src='images/CTV.jpg' width='50' />";
				}
			?>
			</span>
		</div><br />
		<?php
			$prev_parent_id = $array_back[0][0];
			if (isset($_GET['id']) && $_GET['id'] != 0 && $prev_parent_id != 0) {
					echo "<a href='index.php?id=".$prev_parent_id."&3gp=$capable_3gp'>Back</a>&nbsp;&nbsp;&nbsp;&nbsp;";					
			}
			else {
				echo "<a href='index_graphics.php'>Back</a>&nbsp;&nbsp;&nbsp;&nbsp;";
			}
			echo "<a href='index_graphics.php'>Home</a><br />";
		?>
		<div style='background-color:CECFCE'>
			<center>
			<?php
			if ($parent_id != 89) {	
			?>
				<span style='font-size:80%;color:red;font-weight:bold;'>Please select your category</span>
			<?php
			}
			?>
			</center>
		</div><br />		
		<div style="width:80%;text-align:left;line-height:1.5em;">
			<?php
				if ($parent_id == 1 || $parent_id == 86 || $parent_id == 112) {
					if (isset($_GET['image_width'])) {
						$image_width = $_GET['image_width'];
					}
					else {
						$image_width = 60;
					}

					if (!ctype_digit($image_width)) {
						$error .= "Please do not tamper with the parameters!";
					}

					if ($error == "") {
						//do nothing
					}
					else {
						$image_width = 50;
					}

					echo "<center><table>";
					echo "<tr><td>";
					echo "<a href='index.php?id=".$array_categories[0][0]."&3gp=$capable_3gp'><img src='images/audio_$image_width.jpg'><br>Audio</a><br />";
					echo "</td><td>";
					echo "<a href='index.php?id=".$array_categories[1][0]."&3gp=$capable_3gp'><img src='images/video_$image_width.jpg'><br>Video</a><br />";
					echo "</td></tr>";
					echo "</table></center>"; 		
				}
				else if ($parent_id == 117) {
					echo "<center><table>";
					echo "<tr><td>";
					echo "<a href='index.php?id=".$array_categories[0][0]."&3gp=$capable_3gp'><img src='images/CTV.jpg'><br />Citizen</a><br />";
					echo "</td></tr><tr><td>";
					echo "<a href='index.php?id=".$array_categories[1][0]."&3gp=$capable_3gp'><img src='images/NTV.jpg'><br />NTV</a><br />";
					echo "</td></tr>";
					echo "</table></center>"; 		
				}
				else if ($parent_id == 118) {
					echo "<center><table>";
					echo "<tr><td>";
					echo "<a href='index.php?id=".$array_categories[0][0]."&3gp=$capable_3gp'><img src='images/inspekta.jpg'><br />Inspekta Mwala</a><br />";
					echo "</td></tr><tr><td>";
					echo "<a href='index.php?id=".$array_categories[1][0]."&3gp=$capable_3gp'><img src='images/mother_in_law.jpg'><br />Mother-In-Law</a><br />";
					echo "</td></tr><tr><td>";
					echo "<a href='index.php?id=".$array_categories[2][0]."&3gp=$capable_3gp'><img src='images/papa_shirandula.jpg'><br />Papa Shirandula</a><br />";
					echo "</td></tr><tr><td>";
					echo "<a href='index.php?id=".$array_categories[3][0]."&3gp=$capable_3gp'><img src='images/tabasamu.jpg'><br />Tabasamu</a><br />";
					echo "</td></tr><tr><td>";
					echo "<a href='index.php?id=".$array_categories[4][0]."&3gp=$capable_3gp'><img src='images/tahidi_high.jpg'><br />Tahidi High</a><br />";
					echo "</td></tr><tr><td>";
					echo "<a href='index.php?id=".$array_categories[5][0]."&3gp=$capable_3gp'><img src='images/waridi.jpg'><br />Waridi</a><br />";
					echo "</td></tr></table></center>"; 		
				}
				else if ($parent_id == 119) {
					echo "<center><table>";
					echo "<tr><td>";
					echo "<a href='index.php?id=".$array_categories[0][0]."&3gp=$capable_3gp'><img src='images/churchill.jpg'><br />Churchill Live</a><br />";
					echo "</td></tr></table></center>"; 		
					
				}
				/*else if ($parent_id == 8 || $parent_id == 34) {
					echo "<center><span style='font-weight: bold; color: red'>Coming Soon!!</span></center>";
				}*/
				else if ($parent_id == 89) {
					if (isset($_SERVER['HTTP_X_JINNY_CID'])) {
                        			$msisdn = $_SERVER['HTTP_X_JINNY_CID'];
                			}
					else {
						$msisdn = "";
					}

					echo "<form name='feedback_form' method='post' action='feedback.php'>"; 
					echo "<textarea name='feedback' columns='5' rows='8'></textarea><br />";
					echo "<input type='hidden' name='banner_width' value='$banner_width' />"; 
					echo "<input type='hidden' name='msisdn' value='$msisdn' />";
					echo "<input type='submit' value='submit' />";
					echo "</form>";
				}
				else {
					for ($i = 0; $i < count($array_categories); $i++) {
						echo "<a href='index.php?id=".$array_categories[$i][0]."&3gp=$capable_3gp'>".$array_categories[$i][1]."</a><br />";
					}
					if ($_GET['id'] == 3) {
						echo "<br /><h3>External Links</h3>";
						echo "<h5>Cellulant<h5>";
						echo "<ul>";
						echo "<li><a href='http://zion.cellulant.com/wap/topMusic.php?target=topMusic'>Today's Top 5 Tones</a></li>";
						echo "<li><a href='http://zion.cellulant.com/wap/listContent.php?contentType=track&genreID=1&target=song'>Latest  Music</a></li>";
						echo "<li><a href='http://zion.cellulant.com/wap/listContent.php?contentType=mp3&genreID=1&target=mclip'>Hottest True tones</a></li>";
						echo "</ul>";
					}
				}
			?>
		</div>

	</div>
	</center>
</body>
</html>
