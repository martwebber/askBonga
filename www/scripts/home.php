<?php
	require_once("../util/functions.php");
	$error = "";
	
	//insert the structure of the page
	insert_header2();

	if (isset($_POST['submit'])) {
		//check login credentials
		$error = login();
		
		if ($error == "") {
			header("Location: home.php");
		}	
	}
?>

<div class="cspacer">	
	<center>
	<div style="width:80%;height:200px;text-align:left">

	<!-- <img src="images/Poster.jpg" width="500" alt="Mauzo Poa"/>-->

	</div>
	</center>
</div>

<!-- insert the footer -->
<?php
	insert_footer2();
?>		