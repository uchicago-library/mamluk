<?php 
// id = 1 for primary
// id = 2 for secondary
if (isset($_GET['id']))  
	$id = $_GET['id'];
else
	$id = 1;

require("header.htm");
//echo "id = " . $id;
if ($id == 2)  
	require("form2.php"); 
else
	require("form1.php"); 
	
require("footer.htm"); 
?>