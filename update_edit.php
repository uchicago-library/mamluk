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
//	$table_name = "bib";

if (isset($_GET['id']))  
{
	$id = $_GET['id'];
	settype($id, 'integer');
	$id = sprintf("%d", $id);

	$bib= $_GET['bib'];
	settype($bib, 'integer');
	$id = sprintf("%d", $bib);

}
else
{
	$id = $_POST['id'];
	settype($id, 'integer');
	$id = sprintf("%d", $id);	

	$bib= $_POST['bib'];
	settype($bib, 'integer');
	$bib = sprintf("%d", $bib);

}

if (! is_numeric($id))
	exit("No valid limit arguments");
if (! is_numeric($bib))
	exit("No valid limit arguments");

if ($bib == 1)
{
	$table_name = "bib"; // primary
	$fldnum = 31;
}
else
{
	$table_name = "bib2"; // secondary
	$fldnum = 32;
}

	
$query = "SELECT * from " . $table_name . " where id = " . $id;		
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

$query = "UPDATE " . $table_name . " SET ";
for($i = 1; $i < $fldnum - 1; $i++)
{
	$query = $query . mysqli_field_name($result, $i) . " = '" . $newline[$i] . "', ";	
}
	$query = $query . mysqli_field_name($result, $i) . " = '" . $newline[$i] . "'";	

$query = $query . " WHERE id = " . $id;

echo $query;

$result = mysqli_query($link, $query) or die('Query failed: ' . mysqli_error($link));
mysqli_close($link);
?>

</div>
<?php
	echo '<br><a href="mamluk-search.php?id=' . $bib . '">Return to Mamluk search page</a>';
	require("footer.htm");	
?>
</html>
