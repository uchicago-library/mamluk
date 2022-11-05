<?php
$mysql_server = "localhost";
$mysql_user = "root";
$mysql_password = "redrose";
$db_name = "mamluk1";
$link;

function connect()
{
$link = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $db_name)
    or die('Could not connect: ' . mysqli_error($link));
}

function disconnect()
{
	// Closing connection
	mysqli_close($link);	
}

?>
