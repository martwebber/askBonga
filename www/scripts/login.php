<?php session_start();
ob_start();
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
	<div style="width:80%;text-align:left">
	<?php 
		if ($error !=  "") {
			echo "<table class='tablebody border' width='100%'>\r\n";
			echo "<tr><th colspan='2' class='tableheader'>Message</th></tr>\r\n";
			echo "<tr><td colspan='2'>\r\n";
			echo "<p class='error'>".$error."</p>";
			echo "</td></tr>\r\n";
			echo "</table><br /><br />\r\n";			
		}
	?>
	
	<form name="login_form" action="login.php" method="post">
	<table class='tablebody border' width='100%'>
	<tr>
		<th colspan="2" class="tableheader">User Login</th>
	</tr>
	<tr><td colspan="2"><br /></td></tr>
	<tr>
		<td>Username: </td>
		<td><input name="username" type="text" /><br /><br /></td>		
	</tr>
	<tr>	
		<td>Password: </td>
		<td><input name="passwd" type="password" /><br /><br /></td>
	</tr>		
	<tr>	
		<td col='2'><a href='forgot_password.php'>Forgot Password?</a><br /><br /></td>
	</tr>		
	<tr>
		<td colspan="2" align="left" ><input class="regular-text" type="submit" name="submit" value="Submit" /></td>
	</tr>
	</table>
	<br /><br />
	</form>		
	</div>
	</center>
</div>

<!-- insert the footer -->
<?php
	insert_footer2();
	ob_end_flush();
?>

	
	
