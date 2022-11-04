<?php
	require("header.php");
//	require("funcs.php");
?>
<SCRIPT TYPE="text/javascript" SRC="backlink.js"></SCRIPT>
<div class="crumbs">
	<!-- "BREADCRUMBS" -->
<SCRIPT TYPE="text/javascript">
<!--
var bl = new backlink();
bl.write();
//-->
</SCRIPT>	
<br>
<br>
<?php
	$link = mysql_connect($mysql_server, $mysql_user, $mysql_password)
	    or die('Could not connect: ' . mysql_error());
	
	mysql_query("SET NAMES 'utf8'");
	
	
	
	mysql_select_db($db_name, $link) or die('Could not select database');

//require 'db_connect.php';
//	connect();
	

if (isset($_GET['id']))  
{
	$id = $_GET['id'];
	settype($id, 'integer');
	$id = sprintf("%d", $id);

}
else
{
	$id = $_POST['id'];
	settype($id, 'integer');
	$id = sprintf("%d", $id);	
}

if (! is_numeric($id))
	exit("No valid limit arguments");

if ($id == 1)
{
	$table_name = "bib"; // primary
	$fldnum = 31;
}
else
{
	$table_name = "bib2"; // secondary
	$fldnum = 32;
}

	
$query = "SELECT * from " . $table_name;		
$result = mysql_query($query) or die('Query failed: ' . mysql_error());

for ($i = 1; $i < $fldnum; $i++)
{
	if (isset($_GET['id']))  
	{
		$newline[$i] = trim($_GET[mysql_field_name($result, $i)]);	
	}
	elseif (isset($_POST['id']))
	{
		$newline[$i] = trim($_POST[mysql_field_name($result, $i)]);	
	}
	else
		exit("No valid search arguments");	
}

$query = "INSERT INTO " . $table_name . " (";
for($i = 1; $i < $fldnum - 1; $i++)
{
	$query = $query . mysql_field_name($result, $i) . ", ";	
}
	$query = $query . mysql_field_name($result, $i) . ") VALUES (";
	
for($i = 1; $i < $fldnum - 1; $i++)
{
	$query = $query . "'" . $newline[$i] . "', ";	
}
	$query = $query . "'" . $newline[$i] . "')";	

echo $query;

$result = mysql_query($query) or die('Query failed: ' . mysql_error());
mysql_close($link);
?>

</div>
<?php
	echo '<br><a href="mamluk-search.php?id=' . $id . '">Return to Mamluk search page</a>';
	require("footer.htm");	
?>
</html>