<?php 
//require("header.php"); 

if (isset($_GET['id']))  
	$id = $_GET['id'];
else
	$id = 1;

require("header.htm");
//echo "id = " . $id;
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


//function author_wildcard_process(&$str)
//{
//	$len = strlen($str);
//	$mark = strpos($str, "////");
//	
//	if ($mark !== false)
//	{
//		$str_head = substr($str, 0, $mark);	
//		$str = $str_head . " et al ";	
//
//	}
//
//	$len = strlen($str);	
//	for ($i = 0; $i < $len - 1; $i++)
//	{
//		if ($str[$i] . $str[$i + 1] == "//")
//		{
//			
//		}	
//	}
//	
//}



$link = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $db_name)
    or die('Could not connect: ' . mysqli_error($link));

mysqli_query($link, "SET NAMES 'utf8'");


	$query = "SELECT distinct " . $fld_name . " FROM " . $table_name;	
	$query = "SELECT distinct " . $fld_name . ", count(" . $fld_name . ") as count FROM " . $table_name . " where (" . $fld_name . " <> '') group by " . $fld_name;	
	
	$result = mysqli_query($link, $query) or die('Query failed: ' . mysqli_error($link));

	echo "<ul>\n";
	$num = 0;
	while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$str = $line[$fld_name];
		author_wildcard_process($str);
		
//		wildcard_process($str);
//		$query_count = "SELECT count(*) as count FROM bib WHERE (ORIGAUTHOR LIKE '%" . $str . "%')";
//		echo $query_count . "<p></p>";	
//		$result_count = mysqli_query($link, $query_count) or die('Query failed: ' . mysqli_error($link));
//		while ($row = mysqli_fetch_assoc($result_count)) {
//			$count = $row['count'];		
//		}
		$namelist1[$num]=$str;
		$namelist2[$num]=$line['count'];
		$namelist3[$num]=$str;
		
		$num = $num+1;
		if ($id == 2)
			$str_url1 = "mamluk-secondary.php?caller=4&start=1&op=AND&searchauthor0=" . $line[$fld_name] . 
	"&searchauthor1=&searchauthor2=&searchtitle0=&searchtitle1=&searchtitle2=&searchsubject=&limit=50&searchlanguage=";	
		else
			$str_url1 = "mamluk-primary.php?caller=4&start=1&op=AND&searchauthor0=" . $line[$fld_name] . 
	"&searchauthor1=&searchauthor2=&searchtitle0=&searchtitle1=&searchtitle2=&searchsubject=&limit=50&searchlanguage=";
//		echo "<li><a href='" . $str_url1 . "'>" . $str . "</a> (" . $line['count'] . ")</li>\n";

	}	

	echo "</ul>";
	
	for ($i = 0; $i < $num; $i++)	
	{
//		if (substr($namelist1[$i],0,4) == "Ibn ")
//			$namelist1[$i] = substr($namelist1[$i], 4);				

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
//		switch (uniord($namelist1[$i]))
//		{
//			case 703:
//				$namelist1[$i] = substr($namelist1[$i], 2);
//				break;
//			case 7716:
//				$namelist1[$i] = substr($namelist1[$i], 2);
//				$namelist1[$i]{0} = 'H';				
//				break;
//			case 256:
//				$namelist1[$i] = substr($namelist1[$i], 2);
//				$namelist1[$i]{0} = 'A';				
//				break;			
//			case 7778:
//				$namelist1[$i] = substr($namelist1[$i], 2);
//				$namelist1[$i]{0} = 'S';				
//				break;			
//			case 7788:		
//				$namelist1[$i] = substr($namelist1[$i], 2);
//				$namelist1[$i]{0} = 'T';				
//				break;			
//		}	

//		echo " before str" . $i . ": " . $namelist1[$i] . "<p>";
		$strtmp = str_normalize($namelist1[$i]);

        if (gettype($strtmp) == 'string') {
		    $namelist1[$i] = $strtmp;			
        } else if (gettype($strtmp) == 'array') {
		    $namelist1[$i] = implode("", $strtmp);			
        }
//		echo "after str" . $i . ": " . $namelist1[$i] . "<p>";
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
		if ($id==2 || ( four_digit($namelist1[$i]) || $pos !== false ) )		
			echo "<li><a href='" . $str_url1 . "'>" . $namelist3[$i] . "</a></li>\n";	
	}
	
	
//	echo "</li>";
//	echo "</ul>";

//	echo "</body>\n";	

	// Free resultset
mysqli_free_result($result);

// Closing connection
//mysqli_close($link);

require("footer.htm"); 
?>
