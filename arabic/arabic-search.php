<?php 

session_start();

if (isset($_GET['id']))  
	$id = $_GET['id'];
else
	$id = 1;

require("header.htm");
require("form1.php"); 

//if (!session_is_registered("counted")){
if (!isset($_SESSION['counted'])){
    mysqli_query($link, "UPDATE simplecount SET count=(count + 1) WHERE count_id=1");
//session_register("counted");
$_SESSION['counted'] = 1;
} 
	
require("footer.htm"); 
?>

