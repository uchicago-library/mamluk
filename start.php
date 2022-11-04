<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head><!-- template: new (PRODUCTION) --><title>Mamluk Bibliography Database Editor</title>
<link href="Mamluk%20Bibliography%20Online_files/main.css" rel="stylesheet" type="text/css"><!--[if IE]><link href="http://www.lib.uchicago.edu/e/ie.css" rel="stylesheet" type="text/css"><![endif]--></head><body><div class="top">

<?php
// Connects to your Database
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

?>
	
<div class="crumbs">
	<!-- "BREADCRUMBS" -->
	<div><strong>Mamluk Bibliography Database Online Editor</strong><br></div>
</div>
<div class="main">
  <!-- BEGIN WEB AUTHOR CONTENT CODE -->

<p>This page provides access and functionalities to search, add, and update the Mamluk bibliography database.</p>

<table border="0" width="100%">
<tbody>
<tr>
<td valign="top"> 
      <p><b>Mamluk Primary Bibliography</b></p>
      <ul>
<li><a href="mamluk-search.php?id=1">Search Primary Sources</a></li>
<li><a href="subjects.php?id=1">Browse the Subject Guide</a></li>
<li><a href="authors.php?id=1">Browse Primary Source Authors</a></li>
</ul>
    </td>
<td valign="top"> 
      <p><b>Mamluk Secondary Bibliography</b></p>
      <ul>
<li><a href="mamluk-search.php?id=2">Search Secondary Sources</a></li>
<li><a href="subjects.php?id=2">Browse the Subject Guide</a></li>
<li><a href="authors.php?id=2">Browse Secondary Source Authors</a></li>
</ul>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </td>
</tr>

</tbody>
</table>
<br>
<a href="logout.php">Log out</a>

  <!-- END WEB AUTHOR CONTENT CODE -->
</div>

</body></html>
