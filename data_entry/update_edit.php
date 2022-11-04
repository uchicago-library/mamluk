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
	$fldnum = 32;
}
else
{
	$table_name = "bib2"; // secondary
	$fldnum = 33;
}

	
$query = "SELECT * from " . $table_name . " where id = " . $id;		
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

$query = "UPDATE " . $table_name . " SET ";
for($i = 1; $i < $fldnum - 1; $i++)
{
	$query = $query . mysql_field_name($result, $i) . " = '" . $newline[$i] . "', ";	
}

	// the STATUS field
	if ($newline[$i] != '')
		$newline[$i] = 'no';
	else
		$newline[$i] = 'yes';

	$query = $query . mysql_field_name($result, $i) . " = '" . $newline[$i] . "'";	

$query = $query . " WHERE id = " . $id;

//echo $query;

$result = mysql_query($query) or die('Query failed: ' . mysql_error());
mysql_close($link);
?>

</div>
<?php
	echo '<br><a href="mamluk-search.php?id=' . $bib . '">Return to Mamluk search page</a>';
	require("footer.htm");	
?>
</html>