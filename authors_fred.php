<?php 

if (isset($_GET['id']))  
	$id = $_GET['id'];
else
	$id = 1;

require("header.htm");
if ($id == 2)  
{
	$table_name = "bib2";
	$fld_name = "AUTHOR";
	require("form2.php"); 
}
else
{
	$table_name = "bib";
	$fld_name = "ORIGAUTHOR";
	require("form1.php"); 
}	 




$link = mysql_connect($mysql_server, $mysql_user, $mysql_password)
    or die('Could not connect: ' . mysql_error());

mysql_query("SET NAMES 'utf8'");

mysql_select_db($db_name) or die('Could not select database');


	## $query = "SELECT distinct " . $fld_name . " FROM " . $table_name;	
	$query = "SELECT distinct " . $fld_name . ", count(*) as count FROM " . $table_name . " where (" . $fld_name . " <> '') group by " . $fld_name . " order by id";	
	## $query = "SELECT AUTHOR, AUTHORSIMPLE, count(*) as count FROM " . $table_name . " where ( AUTHOR <> '') group by AUTHOR order by AUTHORSIMPLE";	
	
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	echo "<ul>\n";
	$num = 0;
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$str = $line[$fld_name];
		author_wildcard_process($str);
		
		$namelist1[$num]=$str;
		$namelist2[$num]=$line['count'];
		$namelist3[$num]=$str;
		
		$num = $num+1;
	}	

	echo "</ul>";
	
	for ($i = 0; $i < $num; $i++)	
	{

		if (substr($namelist1[$i],0,3) == "de ")
			$namelist1[$i] = substr($namelist1[$i], 3);				

		if (substr($namelist1[$i],0,3) == "von")
			$namelist1[$i] = substr($namelist1[$i], 4);				

		if (substr($namelist1[$i],0,3) == "al-")
			$namelist1[$i] = substr($namelist1[$i], 3);				

		if (substr($namelist1[$i],0,7) == "Ibn al-")
		{
			$namelist1[$i] = substr($namelist1[$i], 7);				
			$namelist1[$i] = "Ibn " . $namelist1[$i];
		}
	}

	for ($i = 0; $i < $num; $i++)	
	{

		$strtmp = str_normalize($namelist1[$i]);
		$namelist1[$i] = implode("", $strtmp);			
	}

	
	array_multisort($namelist1, $namelist2, $namelist3);
	
	for ($i = 0; $i < $num; $i++)	
	{
		if ($id == 2)
			$str_url1 = "mamluk-secondary.php?caller=4&start=1&op=AND&searchauthor0=" . $namelist3[$i] . "&searchauthor1=&searchauthor2=&searchtitle0=&searchtitle1=&searchtitle2=&searchsubject=&limit=50&searchlanguage=";	
		else
			$str_url1 = "mamluk-primary.php?caller=4&start=1&op=AND&searchauthor0=" . $namelist3[$i] . "&searchauthor1=&searchauthor2=&searchtitle0=&searchtitle1=&searchtitle2=&searchsubject=&limit=50&searchlanguage=";
		
		$pos = strpos($namelist1[$i], 'century');
		$bytenum = 0;
		if ($id == 2 || ( four_digit($namelist1[$i]) || $pos !== false) )		
			echo "<li>$i. <a href='" . $str_url1 . "'>" . $namelist3[$i] . "</a></li>\n";	
	}
	
	


	// Free resultset
mysql_free_result($result);


require("footer.htm"); 
?>
