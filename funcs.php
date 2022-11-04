<?php

$file = fopen("db_prm.txt","r");

$mysql_server =  fgets($file);
$mysql_user = fgets($file); // please use customized users with very limited privileges.
$mysql_password = fgets($file);
$db_name = fgets($file);

fclose($file);

# see: https://stackoverflow.com/questions/14629636/mysql-field-name-to-the-new-mysqli
function mysqli_field_name($result, $field_offset) {
    $properties = mysqli_fetch_field_direct($result, $field_offset);
    return is_object($properties) ? $properties->name : null;
}

function uniord($ch, &$bytenum) {

    $n = ord($ch[0]);
    $bytenum = 1;

    if ($n < 128) {
        return $n; // no conversion required
    }

    if ($n < 192 || $n > 253) {
        return false; // bad first byte || out of range
    }

    $arr = array(1 => 192, // byte position => range from
                 2 => 224,
                 3 => 240,
                 4 => 248,
                 5 => 252,
                 );

    foreach ($arr as $key => $val) {
        if ($n >= $val) { // add byte to the 'char' array
            $char[] = ord($ch[$key]) - 128;
            $range  = $val;
            $bytenum = $bytenum + 1;
        } else {
            break; // save some e-trees
        }
    }

    $retval = ($n - $range) * pow(64, sizeof($char));

    foreach ($char as $key => $val) {
        $pow = sizeof($char) - ($key + 1); // invert key
        $retval += $val * pow(64, $pow);   // dark magic
    }

    return $retval;
} 


function char_normalize($chr_src, &$bytenum)
{
	$chr_dst ='';
	$bytenum = 0;
	$code = uniord($chr_src, $bytenum);
	switch ($code)
	{
		case 702:
			$chr_dst = '';
			break;		
		case 703:
			$chr_dst = '';
			break;
		case 192: // character A
			$chr_dst = 'A';				
			break;			
		case 193:
			$chr_dst = 'A';				
			break;			
		case 194:
			$chr_dst = 'A';				
			break;			
		case 196:
			$chr_dst = 'A';				
			break;			
		case 197:
			$chr_dst = 'A';				
			break;			
		case 256:
			$chr_dst = 'A';				
			break;			
		case 224: // character a
			$chr_dst = 'a';				
			break;			
		case 225:
			$chr_dst = 'a';				
			break;			
		case 226:
			$chr_dst = 'a';				
			break;			
		case 228:
			$chr_dst = 'a';				
			break;			
		case 229:
			$chr_dst = 'a';				
			break;			
		case 257:
			$chr_dst = 'a';				
			break;			
		case 199: // character C
			$chr_dst = 'C';				
			break;			
		case 262: // character C
			$chr_dst = 'C';				
			break;			
		case 231: // character c
			$chr_dst = 'c';				
			break;			
		case 263: // character c
			$chr_dst = 'c';				
			break;			
		case 200: // character E
			$chr_dst = 'E';				
			break;			
		case 201:
			$chr_dst = 'E';				
			break;			
		case 232: // character e
			$chr_dst = 'e';				
			break;			
		case 233:
			$chr_dst = 'e';				
			break;			
		case 234:
			$chr_dst = 'e';				
			break;			
		case 235:
			$chr_dst = 'e';				
			break;			
		case 283:
			$chr_dst = 'e';				
			break;			
		case 7716: // character H
			$chr_dst = 'H';				
			break;			
		case 7722:
			$chr_dst = 'H';				
			break;			
		case 7717: // character h
			$chr_dst = 'h';				
			break;			
		case 7723:
			$chr_dst = 'h';				
			break;			
		case 206: // character I
			$chr_dst = 'I';				
			break;			
		case 207:
			$chr_dst = 'I';				
			break;			
		case 322:
			$chr_dst = 'I';				
			break;			
		case 298:
			$chr_dst = 'I';				
			break;			
		case 205:
			$chr_dst = 'I';				
			break;			
		case 236: // character i
			$chr_dst = 'i';				
			break;			
		case 238:
			$chr_dst = 'i';				
			break;			
		case 239:
			$chr_dst = 'i';				
			break;
		case 299:
			$chr_dst = 'i';				
			break;			
		case 237:
			$chr_dst = 'i';				
			break;									
		case 305:
			$chr_dst = 'i';				
			break;									
		case 212: // character O
			$chr_dst = 'O';				
			break;			
		case 214:
			$chr_dst = 'O';				
			break;			
		case 216:
			$chr_dst = 'O';				
			break;			
		case 332:
			$chr_dst = 'O';				
			break;			
		case 242: // character o
			$chr_dst = 'o';				
			break;			
		case 243:
			$chr_dst = 'o';				
			break;			
		case 244:
			$chr_dst = 'o';				
			break;			
		case 246:
			$chr_dst = 'o';				
			break;			
		case 248:
			$chr_dst = 'o';				
			break;			
		case 333:
			$chr_dst = 'o';				
			break;			
		case 219: // character U
			$chr_dst = 'U';				
			break;			
		case 220:
			$chr_dst = 'U';				
			break;			
		case 362:
			$chr_dst = 'U';				
			break;			
		case 251: // character u
			$chr_dst = 'u';				
			break;			
		case 252:
			$chr_dst = 'u';				
			break;			
		case 363:
			$chr_dst = 'u';				
			break;			
		case 288: // character G
			$chr_dst = 'G';				
			break;			
		case 486: // character G
			$chr_dst = 'G';				
			break;			
		case 289: // character g
			$chr_dst = 'g';				
			break;			
		case 487: // character g
			$chr_dst = 'g';				
			break;			
		case 7778: // character S
			$chr_dst = 'S';				
			break;			
		case 138: // character S
			$chr_dst = 'S';				
			break;			
		case 352: // character S
			$chr_dst = 'S';				
			break;			
		case 7779: // character s
			$chr_dst = 's';				
			break;			
		case 154: // character s
			$chr_dst = 's';				
			break;			
		case 353: // character s
			$chr_dst = 's';				
			break;			
		case 7788:	// character T	
			$chr_dst = 'T';				
			break;			
		case 7789:	// character t	
			$chr_dst = 't';				
			break;			
		case 7692: // character D
			$chr_dst = 'D';				
			break;			
		case 7693: // character d
			$chr_dst = 'd';				
			break;			
		case 7730: // character K
			$chr_dst = 'K';				
			break;			
		case 7731: // character k
			$chr_dst = 'k';				
			break;			
		case 379: // character Z
			$chr_dst = 'Z';				
			break;			
		case 7826:
			$chr_dst = 'Z';				
			break;			
		case 380: // character z
			$chr_dst = 'z';				
			break;			
		case 7827:
			$chr_dst = 'z';				
			break;			
		case 323: // character N
			$chr_dst = 'N';				
			break;			
		case 324: // character n
			$chr_dst = 'n';				
			break;			
		case 221: // character Y
			$chr_dst = 'Y';				
			break;			
		case 253: // character y
			$chr_dst = 'y';				
			break;			
		case 321: // character L
			$chr_dst = 'L';				
			break;			
		default:
			$chr_dst = $chr_src[0];
			break;
	}
	return $chr_dst;
}

function str_normalize($str_src)
{
    error_log($str_src);

	$bytetotal = 0;	
	$bytenum = 0;
	$str_tmp = "";
	$str_tmp = substr($str_src, 0);
	$c = '';
	$j = 0;
	$i = 0;
	$str_dst = "";

    if ($str_src != '') {	
    	while ($i < strlen($str_src))
    	{			
    		$c = char_normalize($str_tmp, $bytenum);
            if ($c != '') {
    		    $str_dst[$j] = $c;
            }
    		$j = $j + 1;				
    		
    		$i = $i + $bytenum;		
    		$str_tmp = substr($str_src, $i);		
    	}
    }
	
	return $str_dst;
} 

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

// replace square brackets for place_of_pub
function squarebrack_process(&$str)
{
//	$len = strlen($str);
//	$mark = strpos($str, "[");
	
	if ($str[0] == "[")
	{
		$str_head = substr($str, 1, -1);	
		$str = $str_head;	

	}
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
//$_SESSION['link'] = mysqli_connect($_SESSION['mysql_server'], $_SESSION['mysql_user'], $_SESSION['mysql_password'], $_SESSION['db_name'])
//    or die('Could not connect: ' . mysqli_error($_SESSION['link']));



//$mysql_server = "localhost";
//$mysql_user = "root"; // please use customized users with very limited privileges.
//$mysql_password = "redrose";
//$db_name = "mamluk1";

$mysql_server = "localhost";
$mysql_user = "mh_web"; // please use customized users with very limited privileges.
$mysql_password = "mht_1005";
$db_name = "mamluk";       
?>
