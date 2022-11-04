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
	$sub_table_name = "subject_list2";
	require("form2.php"); 
}
else
{
	$table_name = "bib";
	$sub_table_name = "subject_list";
	require("form1.php"); 
}	

$link = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $db_name)
    or die('Could not connect: ' . mysqli_error($link));

mysqli_query($link, "SET NAMES 'utf8'");

//$expanded = 0;
$expnode = '';

if (isset($_GET['expnode']))  
{
//	$expanded = $_GET['expanded'];
	$expnode = $_GET['expnode'];
	$expnode = trim($expnode);
echo $expnode . "<p></p>";	
	$expnode = mysqli_real_escape_string($link, $expnode);
echo $expnode . "<p></p>";
}
$full_sub_list = array(0 => '');
$list_count = 0;
$childlist_count = 0;

function creat_full_subject_list($result, &$list_count, &$full_sub_list, $expnode)
{
	$i = 0;
	while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$str = $line['subject'];
		
		$k = 0;
		for ($j = 0; $j < strlen($str); $j++)
		{
			if ($str[$j] == "/")	
			{
				$str1 = substr($str, $k, $j - $k);
//				echo strpos($str1, $expnode);
				if (strpos($str1, $expnode . "--") !== false)
				{
					$full_sub_list[$i] = $str1; 
					$i = $i + 1;
				}	
				$k = $j;
//				$str = substr($str, $j + 1, strlen($str) - $j - 1);
				
			}
			
		}
		
		$str1 = substr($str, $k, $j - $k);	
		if (strpos($str1, $expnode . "--") !== false)
		{
			$full_sub_list[$i] = $str1;
			$i = $i + 1;
		}
		
	}		
	$list_count = $i;
}


$i = 0;

if ($expnode != '')
{
	$query1 = "SELECT subject FROM " . $table_name . " WHERE ( subject like '%" . $expnode . "--%') order by subject";	
	$result1 = mysqli_query($link, $query1) or die('Query failed: ' . mysqli_error($link));
	creat_full_subject_list($result1, $list_count, $full_sub_list, $expnode);
//	echo $query1 . "<p></p>";
//	echo $childlist_count . "<p></p>";
	for ($j = 0; $j < $list_count; $j++)
	{
//		echo $full_sub_list[$j] . "<p></p>";
	
		$str1 = $full_sub_list[$j];
		child_extract($str1);
//		wildcard_process($str1);
//		echo $str1 . "<p></p>"; 
//	echo $childlist_count . "<p></p>";	
		$found = 0;
		for ($k = 0; $k < $childlist_count; $k++)
		{
			if (strcasecmp(trim($child_list[$k]), trim($str1)) == 0)
//			if ($child_list[$k] == $str1)
			{
				$found = 1;
				break;
			}
		}
				
		if ($found == 0)
		{
			$child_list[$childlist_count] = ucfirst($str1); // capitalize the first letter 			
			$childlist_count = $childlist_count + 1;
			
		}
		
	}	
	
	
//	function cmp($a, $b)
//	{
//    	return strcasecmp($a["fruit"], $b["fruit"]);
//	}	
//	usort($child_list, "cmp");
	
	sort($child_list);
	

//	echo $childlist_count . "<p></p>";		


}

//	echo "<body>\n";	
	$query = "SELECT Field1 FROM " . $sub_table_name;	
	
	$result = mysqli_query($link, $query) or die('Query failed: ' . mysqli_error($link));
	echo "<ul>\n";
	while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$str = $line['Field1'];
		wildcard_process($str);
		$query_count = "SELECT count(*) as count FROM " . $table_name . " WHERE (subject LIKE '%" . $str . "%')";
		$query_exp_count = "SELECT count(*) as count FROM " . $table_name . " WHERE ( subject like '%" . $str . "--%')";
//		echo $query_count . "<p></p>";	
		$result_count = mysqli_query($link, $query_count) or die('Query failed: ' . mysqli_error($link));
		$result_exp_count = mysqli_query($link, $query_exp_count) or die('Query failed: ' . mysqli_error($link));

		while ($row = mysqli_fetch_assoc($result_count)) {
			$count = $row['count'];		
		}
		while ($row_exp = mysqli_fetch_assoc($result_exp_count)) {
			$count_exp = $row_exp['count'];		
		}
		
		if ($id == 2)
			$str_url1 = "mamluk-secondary.php?caller=3&start=1&op=AND&searchauthor0=&searchauthor1=&searchauthor2=" . "&searchtitle0=&searchtitle1=&searchtitle2=&searchsubject=" . $str . "&limit=50&searchlanguage=";		
		else
			$str_url1 = "mamluk-primary.php?caller=3&start=1&op=AND&searchauthor0=&searchauthor1=&searchauthor2=" . "&searchtitle0=&searchtitle1=&searchtitle2=&searchsubject=" . $str . "&limit=50&searchlanguage=";		
		
		if ($count_exp == 0)
		{
			if ($count > 0)
			echo "<li><a href='" . $str_url1 . "'>" . $line['Field1'] . "</a> (" . $count . ")</li>\n";
//			echo "</li>\n";
		}
		else
		{
			if ($expnode == $str)
				$str_url2 = "subjects.php?id=" . $id . "&expnode=";			
			else
				$str_url2 = "subjects.php?id=" . $id . "&expnode=" . $line['Field1'];
			
			echo "<li><a href='" . $str_url1 . "'>" . $line['Field1'] . "</a> (" . $count . ")<a href='" . $str_url2 . "'>+</a> </li>\n";
			
//			if (($expnode == $str) && ($expanded == 1))
			if ($expnode == $str)
			{
				echo "<ul>\n";
				
				for ($k = 0; $k < $childlist_count; $k++)
				{					
					$child_list_tmp = $child_list[$k];
					wildcard_process($child_list[$k]);	
					
					$query_count = "SELECT count(*) as count FROM " . $table_name . " WHERE (subject LIKE '%" . $str . "--" . $child_list[$k] . "%')";
					$result_count = mysqli_query($link, $query_count) or die('Query failed: ' . mysqli_error($link));
			
					while ($row = mysqli_fetch_assoc($result_count)) {
						$count = $row['count'];		
					}
					if ($id == 2)
						$str_url2 = "mamluk-secondary.php?caller=3&start=1&op=AND&searchauthor0=&searchauthor1=&searchauthor2=" . "&searchtitle0=&searchtitle1=&searchtitle2=&searchsubject=" . $str . "--" . $child_list[$k] . "&limit=50&searchlanguage=";						
					else		
						$str_url2 = "mamluk-primary.php?caller=3&start=1&op=AND&searchauthor0=&searchauthor1=&searchauthor2=" . "&searchtitle0=&searchtitle1=&searchtitle2=&searchsubject=" . $str . "--" . $child_list[$k] . "&limit=50&searchlanguage=";						
					
					echo "<li><a href='" . $str_url2 . "'>" . $child_list_tmp . "</a> (" . $count . ")</li>\n";

//					echo "<li>" . $child_list[$k] . "</li>";
				}					
				echo "</ul>\n";

				
			}		
		}		
	}	

	echo "</ul>";
//	echo "</li>";
//	echo "</ul>";

//	echo "</body>\n";	

	// Free resultset
mysqli_free_result($result);
// Closing connection
//mysqli_close($link);

require("footer.htm"); 
?>
