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
	$link = mysql_connect($mysql_server, $mysql_user, $mysql_password)
	    or die('Could not connect: ' . mysql_error());
	
	mysql_query("SET NAMES 'utf8'");
	
	
	
	mysql_select_db($db_name) or die('Could not select database');

//require 'db_connect.php';
//	connect();
	$table_name = "main_table";

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
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
//echo "\n<table ALIGN='LEFT' border=1>\n";	
echo "<table ALIGN='left' border=1 bordercolor='#E2E2E2' width='854'>";

while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {

	for($i = 1; $i < 29; $i++)
	{
		if ($i != 27)
		{
		    echo "<tr><td ALIGN='LEFT' VALIGN='TOP' bgcolor='#E2E2E2'><b><font size='2'>";
			if ($i != 2 && $i != 3)
				echo mysql_field_name($result, $i);	
			
			if ($i == 2)
				echo "Author's last name";	
			if ($i == 3)
				echo "Author's first name";	
			
			echo "</font></b></td>\n";
		
			echo "<td ALIGN='LEFT' VALIGN='TOP' width='646'><font size='2'>";
		
			echo trim($line[mysql_field_name($result, $i)]);
		
			echo "</font></td></tr>\n";
		}
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