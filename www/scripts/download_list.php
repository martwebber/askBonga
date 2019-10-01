<?php	
	require_once "util/functions.php";
	
	$error = "";
	
	//possible extensions
	//set our extension to pdf
	$ext = 'pdf';
	$array_ext =  array("html"=>"text/html", "txt"=>"text/plain", "jpg"=>"image/jpg", "gif"=>"image/gif", "zip"=>"application/zip", "pdf"=>"application/pdf", "mp3"=>"audio/mpeg");
	
	for ($k = 0; $k < count($array_ext); $k++)
	{
		foreach ($array_ext as $key=>$value)
		{
			if ($ext == $key)
				$content = $value;
		}
	}
		
	//populate array with entire list of downloads
	//open directory handle
	$download_directory = "C:\\Program Files\\Apache Software Foundation\\Apache2.2\\htdocs\\twendeball\\reports";
	$dh = opendir($download_directory);
	$array_downloads = array ();
	$array_no_display= array("temp.html", "sample.pdf");
	//$array_no_display = array();
	$array_keys = array();
	$array_values = array();
	
	if (is_dir($download_directory)) {
	    if ($dh = opendir($download_directory)) {
	        while (($file = readdir($dh)) !== false) {
				if (!is_dir($file)) {
					
					$stats = stat($download_directory."\\".$file);
					$temp = array ($stats[10]);
					$array_keys = array_merge($array_keys, $temp);
					$temp = array ($file);
					$array_values = array_merge($array_values, $temp);
	            }
	        }
	        closedir($dh);
	    }
	}
	$array_downloads = array_combine($array_keys, $array_values);
	//sort array
	krsort($array_downloads);	
	//remove elements not to be displayed
	$array_downloads = array_diff($array_downloads, $array_no_display);

	//small rework to make $key=$value
	$array_download_list = array();
	$temp = array();
	foreach ($array_downloads as $value) {
		$temp = array($value => $value);
		$array_download_list = array_merge($array_download_list, $temp);
	}
	
	if (isset($_POST['download'])) {
		$file_name = $_POST['report'];
		$doc = $file_name;
		header("Content-Type: $content");
		header('Content-Disposition: attachment; filename="'.$file_name.'"');
		header('Content-Transfer-Encoding: binary');
		//read data
		//$doc = "sample.pdf";
		readfile("reports\\".$doc);
		//ensure no caching
		header('Cache-Control: max-age=3600, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Pragma: public');
		
		$message = "DOWNLOADS: User ID - ".$_SESSION['USERID']." DOWNLOADED ".$doc;
		logmessage("INFO", $message);
	}
	
	if (!isset($_POST['download'])) {
		//insert template
		insert_header2();
	}
?>

<?php
if (!isset($_POST['download'])) {
?>
<div class="cspacer">	
	<center>
	<div style="width:80%;text-align:left">
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
	
	<form id="download_form" name="download_form" action="download_list.php" method="post">
		<table class='tablebody border' width='100%'>
			<tr>
				<th colspan='2' class='tableheader'>DOWNLOAD REPORT</th>
			</tr>
			<tr>
				<td><br />Select Report:</td>
				<td><br />
					<?php
						if (!isset($_POST['download']))
							echo build_combo("report", $array_download_list, "combobox", null, null); 
					?>
				</td>				
			</tr>
			<tr>
				<td colspan="2"><br /><br /><input type="submit" value="DOWNLOAD" name="download" /></td>
			</tr>
		</table>
	</form>
	</div>
	</center>
</div>

<!-- insert the footer -->

<?php
	if (!isset($_POST['download']))
		insert_footer2();
}
?>
