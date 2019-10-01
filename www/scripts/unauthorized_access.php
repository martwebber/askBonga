<?php 
	include "../util/functions.php";
	
	insert_header2();
?>

<div class="cspacer">
	<div align="center" style="text-align: left; width:80%">
	<table class="tablebody border" width="100%">
		<tr><th class='tableheader'>Message</th></tr>
		<tr><td>You Are Not Authorized To Use This Functionality! <br /><br /></td>
		<?php
			if (isset($_SERVER['HTTP_REFERER'])) {
				echo "<br /><br />";
				echo "<tr><td>";
				echo "<a href=".$_SERVER['HTTP_REFERER'].">Go Back To Previous Page</a>";
				echo "</td></tr>";
			}
		?>
	</table>
	</div>
</div>

<!-- insert the footer -->
<?php
	insert_footer2();
?>
