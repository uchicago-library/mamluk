<?php

$file = fopen("db_prm.txt","r");

$mysql_server =  fgets($file);
$mysql_user = fgets($file); // please use customized users with very limited privileges.
$mysql_password = fgets($file);
$db_name = fgets($file);

fclose($file);

function four_digit($str)
{
	for ($i = 0; $i < strlen($str)-4; $i++)
	{
		// four digits in a row
		if (is_numeric($str[$i]) && is_numeric($str[$i+1]) && is_numeric($str[$i+2]) && is_numeric($str[$i+3]))
			return 1;
	}
	return 0;
}

// replace all single quotes
function wildcard_process(&$str)
{
	$len = strlen($str);
	
	for ($i = 0; $i < $len; $i++)
	{
		if ($str[$i] == "'")
			$str[$i] = "_";		
//		if (($str[$i] == "+") || ($str[$i] == "=") || ($str[$i] == "*") || ($str[$i] == ">") || ($str[$i] == "<"))
//			return 1;
	}
	
	return 0;	
}

// replace "////" by "et al" in an author string
function author_wildcard_process(&$str)
{
	$len = strlen($str);
	$mark = strpos($str, "////");
	
	if ($mark !== false)
	{
		$str_head = substr($str, 0, $mark);	
		$str = $str_head . " et al ";	

	}

	$len = strlen($str);	
	for ($i = 0; $i < $len - 1; $i++)
	{
		if ($str[$i] . $str[$i + 1] == "//")
		{
			
		}	
	}
	
}

// extract child subject from the subject string
function child_extract(&$str)
{
	$mark1 = strpos($str, "--");
	$mark2 = strlen($str);
	
	$str1 = substr($str, $mark1 + 2, $mark2 - $mark1 - 2);
	$str = $str1;	
}

// Create the subject string for result display
function creat_full_list($str, &$list_count, &$full_sub_list)
{
	$i = 0;		
	$k = 0;
	
	for ($j = 0; $j < strlen($str); $j++)
	{
		if ($str[$j] == "/")	
		{
			$str1 = substr($str, $k, $j - $k);
			$full_sub_list[$i] = $str1; 
			$i = $i + 1;			
			$k = $j + 1;
		}			
	}
	
	$str1 = substr($str, $k, $j - $k);	
	$full_sub_list[$i] = $str1;
	$i = $i + 1;					
	$list_count = $i;	
}


// Get rid of all double quotes
function parse_doublequote(&$str_in, &$list_out, &$list_num)
{
	$len = strlen($str_in);

	$j = strpos($str_in, '"');
	if ($j !== false)
	{
		$quote_found = 0;
		for ($k = $j + 1; $k < $len; $k++)
		{
			if ($str_in[$k] == '"')
			{
				$list_out[$list_num] = substr($str_in, $j + 1, $k - $j - 1);
				$list_num = $list_num + 1;
				$quote_found = 1;
								
				$str1 = substr($str_in, 0, $j);
				$str2 = substr($str_in, $k - $len + 1);
				if ($k - $len + 1 == 0)
					$str2 = '';
				$str_in = trim($str1 . $str2);					
				
				break;	
			}
		}

		if ($quote_found == 0)
			die('Query failed: quote missed or misplaced');
	}		
}

// Get rid of all spaces
function parse_space($str_in, &$list_out, &$list_num)
{
	$k = 0;
	for ($j = 0; $j < strlen($str_in); $j++)
	{
		if ($str_in[$j] == " ")	
		{
			$str1 = substr($str_in, $k, $j - $k);
			if (strlen($str1) > 0)
			{
				$list_out[$list_num] = $str1; 
				$list_num = $list_num + 1;
			}	
			$k = $j + 1;			
		}		
	}
	
	$str1 = substr($str_in, $k, $j - $k);	
	if (strlen($str1) > 0)
	{
		$list_out[$list_num] = $str1; 
		$list_num = $list_num + 1;
	}	
}

// Generate the AND clause for each form field
function and_clause($fldname, $list_out, $list_num)
{
	if ($list_num == 1)
	{
		$str = 	"( " . $fldname . " like '" . $list_out[0] . "')";
		return $str;
	}
	
	
	$str = "(";
	for ($i = 0; $i < $list_num - 1; $i++)
		$str = $str . "( " . $fldname . " like '" . $list_out[$i] . "') and ";
	
		$str = $str . "( " . $fldname . " like '" . $list_out[$i] . "'))";
	
	return $str;	
}

// Add the % wildcard
function add_wildcard(&$str1)
{
	if ($str1 == '')
		return 0;
	if (substr($str1,0,1) != '%')
		$str1 = "%" . $str1;
	if (substr($str1,strlen($str1) - 1,1) != '%')
		$str1 = $str1 . "%";	
}

function add_wildcard1(&$str1)
{
	if ($str1 == '')
		return $str1;
	if (substr($str1,0,1) != '%')
		$str2 = "%" . $str1;
	if (substr($str1,strlen($str1) - 1,1) != '%')
		$str2 = $str2 . "%";	
	return $str2;
}



function session_started(){
    if(isset($_SESSION)){ return true; }else{ return false; }
}

//if(!session_started())
//    session_start();
//
//$_SESSION['mysql_server'] = "localhost";
//$_SESSION['mysql_user'] = "root"; // please use customized users with very limited privileges.
//$_SESSION['mysql_password'] = "redrose";
//$_SESSION['db_name'] = "mamluk1";
//    
//$_SESSION['link'] = mysql_connect($_SESSION['mysql_server'], $_SESSION['mysql_user'], $_SESSION['mysql_password'])
//    or die('Could not connect: ' . mysql_error());



$mysql_server = "localhost";
$mysql_user = "htho"; // please use customized users with very limited privileges.
$mysql_password = "hthom_1003";
$db_name = "mamluk";


?>