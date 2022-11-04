<?php
$mysql_server = "localhost";
$mysql_user = "root";
$mysql_password = "redrose";
$db_name = "mamluk1";
$link;

function connect()
{
$link = mysql_connect($mysql_server, $mysql_user, $mysql_password)
    or die('Could not connect: ' . mysql_error());

mysql_select_db($db_name) or die('Could not select database');
}

function disconnect()
{
	// Closing connection
	mysql_close($link);	
}

?>