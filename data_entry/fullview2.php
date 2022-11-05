<?php
	require("header.htm");
	require("funcs.php");
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
	$table_name = "bib2";

if (isset($_GET['id']))  
{
	$id = $_GET['id'];
	settype($id, 'integer');
	$id = sprintf("%d", $id);

}

else
	exit("No valid search arguments");

if (! is_numeric($id))
	exit("No valid limit arguments");
	
$query = "SELECT * from " . $table_name . " where id = " . $id;		
$result = mysqli_query($link, $query) or die('Query failed: ' . mysqli_error($link));
	
//echo "\n<table ALIGN='LEFT' border=1>\n";	
echo "<table ALIGN='left' border=1 bordercolor='#E2E2E2' width='854'>";

while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

	for($i = 1; $i < 32; $i++)
	{
//		if ($i != 27)
//		{
		    echo "<tr><td ALIGN='LEFT' VALIGN='TOP' bgcolor='#E2E2E2'><b><font size='2'>";
//			if ($i != 2 && $i != 3)
				echo mysqli_field_name($result, $i);	
			
//			if ($i == 2)
//				echo "Author's last name";	
//			if ($i == 3)
//				echo "Author's first name";	
			
			echo "</font></b></td>\n";
		
			echo "<td ALIGN='LEFT' VALIGN='TOP' width='646'><font size='2'>";
		
			echo trim($line[mysqli_field_name($result, $i)]);
		
			echo "</font></td></tr>\n";
//		}
	}


}	

echo "</table>\n";
echo "<p>&nbsp;</p><p>&nbsp;</p><p align = 'center'>";

?>

</div>

<?php
//	<a href="arabic-search.php"><div><strong>Arabic Literature Bibliography</strong><br></div></a>
	require("footer.htm");	
?>
</html>
