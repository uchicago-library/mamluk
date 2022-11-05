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
	$link = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $db_name)
	    or die('Could not connect: ' . mysqli_error($link));
	
	mysqli_query($link, "SET NAMES 'utf8'");
	
	
	
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
	$fldnum = 32;
}
else
{
	$table_name = "bib2"; // secondary
	$fldnum = 33;
}

	
$query = "SELECT * from " . $table_name;		
$result = mysqli_query($link, $query) or die('Query failed: ' . mysqli_error($link));

for ($i = 1; $i < $fldnum; $i++)
{
	if (isset($_GET['id']))  
	{
		$newline[$i] = trim($_GET[mysqli_field_name($result, $i)]);	
	}
	elseif (isset($_POST['id']))
	{
		$newline[$i] = trim($_POST[mysqli_field_name($result, $i)]);	
	}
	else
		exit("No valid search arguments");	
}

$query = "INSERT INTO " . $table_name . " (";
for($i = 1; $i < $fldnum - 1; $i++)
{
	$query = $query . mysqli_field_name($result, $i) . ", ";	
}
	$query = $query . mysqli_field_name($result, $i) . ") VALUES (";
	
for($i = 1; $i < $fldnum - 1; $i++)
{
	$query = $query . "'" . $newline[$i] . "', ";	
}

	// the STATUS field
	if ($newline[$i] != '')
		$newline[$i] = 'no';
	else
		$newline[$i] = 'yes';
	
	$query = $query . "'" . $newline[$i] . "')";	

//echo $query;

$result = mysqli_query($link, $query) or die('Query failed: ' . mysqli_error($link));
mysqli_close($link);
?>

</div>
<?php
	echo '<br><a href="mamluk-search.php?id=' . $id . '">Return to Mamluk search page</a>';
	require("footer.htm");	
?>
</html>
