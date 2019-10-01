<?php
  include "util/functions.php";

  //insert header
  //insert_header2();
  include_once "templates/header3.php";
  
  $date = date("Y-m-d H:i:s"); //current time and date
  $prize = "50"; //id for the pickup prize - can be checked in the 'prizes' table
  $draw = "4"; //id of the draw - the fourth draw

  if (isset($_POST['submit'])) {
    //function in 'functions.php' that generates the pdf report
    generate_draw_report();
  }

?>
<div class="cspacer">	
	<center>
	<div style="width:80%;text-align:left">
	  <form name="generate_report" method="post" action="generate_report.php">
	    <table class='tablebody border' width='100%'>
			<tr>
				<th colspan="2" class="tableheader"><b>GENERATE REPORT</b></th>
			</tr>
			<tr>
				<td><br />Date: </td>
				<td><br /><input name='date' type='text' value="<?php echo $date ?>" disabled size='40' /></td>
			</tr>
			<tr>
				<td>Prizes: </td>
				<td>
					<?php
					//populate prize listbox
						if (isset($_POST["prize"]) && $_POST["prize"] != "none")
							$prize_val = $_POST["prize"];
						else
							$prize_val = -1;
							
						//build array
						//initialise database object
						$db = new Database($DB_TYPE, $DATABASEHOST, $DATABASEPORT, $DATABASEUSER, $DATABASEPASSWORD, $DATABASENAME);
						$query = "SELECT ID, PRIZE_NAME FROM PRIZES";	
						$error = $db->open_connection();					
						$array_prizes_list = $db->list_records($query);
						$db->close_connection();
						
						$array_prizes = array();
						$array_keys = array();
						$array_values = array();
						
						for ($j = 0; $j < count($array_prizes_list); $j++) {
							$temp = array ($array_prizes_list[$j][0] => $array_prizes_list[$j][0]);
							$array_keys = array_merge($array_keys, $temp);
							$temp = array ($array_prizes_list[$j][1]);
							$array_values = array_merge($array_values, $temp);					
						}
						$array_prizes = array_combine($array_keys, $array_values);
						echo build_combo("prize", $array_prizes, "combobox", null, $prize_val);			
					?>
				</td>
			</tr>
			<tr>
				<td>Draw</td>
				<td>
					<?php
						//populate draw listbox
						if (isset($_POST["draw"]) && $_POST["draw"] != "none")
							$draw_val = $_POST["draw"];
						else
							$draw_val = -1;
				
						$array_draws = array(1=>"Draw 1", 2=>"Draw 2", 3=>"Draw 3", 4=>"Draw 4", 5=>"Draw 5", 6=>"Draw 6", 7=>"Draw 7", 8=>"Draw 8");
						echo build_combo("draw", $array_draws, "combobox", null, $draw_val);
					?>
				</td>
	    	</tr>
	    	<tr>
	    		
	    		<td colspan='2'><br /><br /><input name="submit" type="submit" value="submit" /></td>
	    	</tr>		    
	    </table>
	    
	  </form>
	</div>
	</center>
</div>

<!-- insert the footer -->
<?php
	insert_footer2();
?>
  
  
  

