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
	
	$bib= $_GET['bib'];
	settype($bib, 'integer');
	$bib = sprintf("%d", $bib);
}

else
	exit("No valid search arguments");

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


	
$query = "DELETE from " . $table_name . " where id = " . $id;		
$result = mysqli_query($link, $query) or die('Query failed: ' . mysqli_error($link));

mysqli_close($link);
?>

</div>
<?php
	echo '<br><a href="mamluk-search.php?id=' . $bib . '">Return to Mamluk search page</a>';

//	<a href="arabic-search.php"><div><strong>Arabic Literature Bibliography</strong><br></div></a>
	require("footer.htm");	
?>
</html>
