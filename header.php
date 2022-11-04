<?php
	require("funcs.php");
	
	// Connects to your Database
	$link = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $db_name)
	    or die('Could not connect: ' . mysqli_error($link));
	mysqli_query($link, "SET NAMES 'utf8'");
	
	//checks cookies to make sure they are logged in
	if(isset($_COOKIE['ID_my_site']))
	{
	$username = $_COOKIE['ID_my_site'];
	$pass = $_COOKIE['Key_my_site'];
	$check = mysqli_query($link, "SELECT * FROM member WHERE username = '$username'")or die(mysqli_error($link));
	while($info = mysqli_fetch_array( $check ))
	{
	
	//if the cookie has the wrong password, they are taken to the login page
	if ($pass != $info['password'])
	{ 
		mysqli_close($link);
		header("Location: login.php");
	}
	
	//otherwise they are shown the admin area
	else
	{

		mysqli_close($link);
	}
	}
	}
	else
	
	//if the cookie does not exist, they are taken to the login screen
	{

		mysqli_close($link);	
		header("Location: login.php");
	}
	require("header.htm");	
?>
