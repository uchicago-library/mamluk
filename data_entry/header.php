<?php
	require("funcs.php");
	
	// Connects to your Database
	$link = mysql_connect($mysql_server, $mysql_user, $mysql_password)
	    or die('Could not connect: ' . mysql_error());
	mysql_query("SET NAMES 'utf8'");
	mysql_select_db($db_name, $link) or die('Could not select database');
	
	//checks cookies to make sure they are logged in
	if(isset($_COOKIE['ID_my_site']))
	{
	$username = $_COOKIE['ID_my_site'];
	$pass = $_COOKIE['Key_my_site'];
	$check = mysql_query("SELECT * FROM member WHERE username = '$username'")or die(mysql_error());
	while($info = mysql_fetch_array( $check ))
	{
	
	//if the cookie has the wrong password, they are taken to the login page
	if ($pass != $info['password'])
	{ 
		mysql_close($link);
		header("Location: login.php");
	}
	
	//otherwise they are shown the admin area
	else
	{

		mysql_close($link);
	}
	}
	}
	else
	
	//if the cookie does not exist, they are taken to the login screen
	{

		mysql_close($link);	
		header("Location: login.php");
	}
	require("header.htm");	
?>