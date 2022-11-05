<?php
	require("header.htm");
	require("funcs.php");
?>

<div class="main">
  <!-- BEGIN WEB AUTHOR CONTENT CODE -->

<!--unlikely comment 1-->

<ul>
  <li><font size="-1"><b>And</b> is implicit between terms in an input box. Do not type it, or 
	it will be included in the search.</font></li>
  <li><font size="-1">Do not use apostrophes, 'ayns, hamzas, or diacritics in search terms. Omit <b>al-</b> (except from phrase searches).</font></li>
  <li><font size="-1">Search an <b>exact</b> phrase by putting it in quotation marks. A phrase search and additional terms in the same box are joined by 
	the implicit <b>and</b>.</font></li>
  <li><font size="-1">Wildcards (% and _) may be used freely. For explanations, please see 
	the <a href="http://www.lib.uchicago.edu/e/su/mideast/mamluk/searchhelp.html">expanded search help</a> page.</font>
  </li>
  <li><font size="-1">Modern authors are not displayed in the primary bibliography's author browse 
	list but may be found via the author search.</font></li>
  <li><font size="-1">For explanations and examples of search features, please see the <a href="http://www.lib.uchicago.edu/e/su/mideast/mamluk/searchhelp.html">expanded search help</a> page.</font></li>
</ul>

<?php
	$table_name = "main_table";
//	$searchArts = "Islam";
//	$searchArts = mysqli_real_escape_string($link, $searchArts);
//	echo "search arts = " . $searchArts . "<p></p>";

//$item = "Zak's Laptop";
//$item = mysqli_escape_string($link, $item);
//printf("Escaped string: %s\n", $item);
	
function get_author_string($line)
{
	$lastname = trim($line[2]);
	$firstname = trim($line[3]);
	$auth_num = 0;
		
	$len = strlen($lastname);
	if ($len > 0)
		$auth_num = 1;
	
	// fill in the lastname list
	$k = 0;
	$j = 0;
	for ($i = 0; $i < $len; $i++)
	{		
		if ($lastname[$i] == ";")
		{
			$ln_list[$j] = substr($lastname, $k, $i-$k);
			$auth_num = $auth_num + 1;
			$j = $j + 1;
			$k = $i+1;	
		}
	}	

	$ln_list[$j] = substr($lastname, $k, $i - $k);
	
	// fill in the firstname list
	
	$k = 0;
	$j = 0;
	for ($i = 0; $i < strlen($firstname); $i++)
	{		
		if ($firstname[$i] == ";")
		{
			$fn_list[$j] = substr($firstname, $k, $i-$k);
			$j = $j + 1;
			$k = $i+1;	
		}
	}	
	$fn_list[$j] = substr($firstname, $k, $i - $k);
	
	
// if there is 1 author
	if ($auth_num == 1)
	{
		$str_author = $lastname . ", " . $firstname;
		return $str_author;			
	}

// if there are 2 authors	
	if ($auth_num == 2)
	{
		$str_author = $ln_list[0] . ", " . $fn_list[0] . " and " . $fn_list[0] . " " . $ln_list[0];
		return $str_author;						
	}	

// if there are more than 2 authors	
	if ($auth_num > 2)
	{
		$str_author = $ln_list[0] . ", " . $fn_list[0];
		
		for ($i = 1; $i < $auth_num; $i++)
		{
			$str_author = $str_author . ", " . $fn_list[$i] . " " . $ln_list[$i];				
		}
		$str_author = $str_author . ". <I>" . trim($line[11]) . "</I> (" . trim($line[16]) . ": " . trim($line[15]) . ", " . trim($line[17]) . ")."; 
		return $str_author;						
	}	


	
	return $str_author;	
}

function get_translator_string($line)
{
	$str_translators = trim($line[7]);	
	return $str_translators;	
}

function item_display($line, $type)
{
	$str_form = trim($line[1]);    
    $str_authrole = ucfirst(trim($line[6]));
    $str_title=trim($line[11]);
    
    // if work_title is empty the replace by individual title
    if (strlen($str_title) < 1)
    	$str_title=trim($line[10]);
    
// 3,4,5,7,8,10,11,12,13,14,15,16,17,18,19,20,21,29    
    $line_dot = $line;
	$line_comma = $line;

	for ($i = 1; $i < 29; $i++)
	{	
	    if ($line[$i] != "")
		{
	    	$line_dot[$i] = trim($line[$i]) . '. ';
	    	$line_comma[$i] = trim($line[$i]) . ', ';	    
	    }	    
    }
	    
    switch ($type) {
case 1: // books with subtitles
    $str_display = get_author_string($line) . ". <I>" . $str_title . ": " . trim($line[12]) . "</I> Translated by " . get_translator_string($line) . ". (" . trim($line[16]) . ": " . trim($line[15]) . ", " . trim($line[17]) . ").";
    break;
case 2: // books as part of series
    $str_display = get_author_string($line) . ". <I>" . $str_title . "</I>, " . trim($line[22]) . "Translated by " . get_translator_string($line) . ". (" . trim($line[16]) . ": " . trim($line[15]) . ", " . trim($line[17]) . ").";
    break;
case 3: // journal articles
    $str_display = get_author_string($line) . '. "' . $str_title . '." Translated by ' .  get_translator_string($line) . ". <I>" . trim($line[22]) . "</I>: " . trim($line[23]) . ".";
    break;
case 4: // chapter in book
    $str_display = get_author_string($line) . '. "' . $str_title . '," in <I>' . trim($line[13]) . "</I> Translated by " . get_translator_string($line) . ". (" . trim($line[16]) . ": " . trim($line[15]) . "," . trim($line[17]) . "), " . trim($line[23]) . ".";
    break;
case 5: // anonymous book
    $str_display = ' <I>"' . $str_title . '."</I> (' . trim($line[14]) . ", " . trim($line[15]) . ").";
    break;
	}
	
	return $str_display;
}


  //create short variable names

//if (isset($_POST['searchauthor1']))
//	echo "searchauthor1 = " . $searchauthor1;
if (isset($_GET['start']))  
{
	$caller = $_GET['caller'];
	$start = $_GET['start']; 
	$op = $_GET['op'];      
	$searchauthor0 = trim($_GET['searchauthor0']);
	$searchauthor1 = trim($_GET['searchauthor1']);
	$searchauthor2 = trim($_GET['searchauthor2']);
	$searchtitle0 = trim($_GET['searchtitle0']);
	$searchtitle1 = trim($_GET['searchtitle1']);
	$searchtitle2 = trim($_GET['searchtitle2']);
	$searchtranslator = trim($_GET['searchtranslator']);
	$searcheditor = trim($_GET['searcheditor']);	
	$searchtype = trim($_GET['searchpubtype']);
	$limit = $_GET['limit'];
	
	settype($limit, 'integer');
	$limit = sprintf("%d", $limit);

	settype($start, 'integer');
	$start = sprintf("%d", $start);

}
elseif (isset($_POST['start']))
{
	$caller = 1; // called by submitting form
	$start = 1; 
	$op = $_POST['op'];      
	$searchauthor0 = trim($_POST['searchauthor0']);
	$searchauthor1 = trim($_POST['searchauthor1']);
	$searchauthor2 = trim($_POST['searchauthor2']);
	$searchtitle0 = trim($_POST['searchtitle0']);
	$searchtitle1 = trim($_POST['searchtitle1']);
	$searchtitle2 = trim($_POST['searchtitle2']);
	$searchtranslator = trim($_POST['searchtranslator']);
	$searcheditor = trim($_POST['searcheditor']);	
	$searchtype = trim($_POST['searchpubtype']);
	$limit = $_POST['limit'];

	settype($limit, 'integer');
	$limit = sprintf("%d", $limit);

}
else
	exit("No valid search arguments");

if (! is_numeric($limit))
	exit("No valid limit arguments");
	
$searchauthor0b = $searchauthor0;
$searchauthor1b = $searchauthor1;
$searchauthor2b = $searchauthor2;
$searchtitle0b = $searchtitle0;
$searchtitle1b = $searchtitle1;
$searchtitle2b = $searchtitle2;
$searchtranslatorb = $searchtranslator;
$searcheditorb = $searcheditor;	
$searchtypeb = $searchtype;

?>

<!-- search component ::CONFIG::secsearch -->
<form action="arabic-primary.php" method="post">
<?php echo "<input name='start' value= $start type='hidden'>"; ?>
<input name="search" value="::CONFIG::secsearch" type="hidden"><table>
<tbody><tr>
<th valign="center">
<select name="op">
<?php
if ($op == 'AND')
{
	$op = 'AND';
	echo "<option selected='selected'>AND</option>";
	echo "<option>OR</option>";
}
else
{
	$op = 'OR';
	echo "<option>AND</option>";
	echo "<option selected='selected'>OR</option>";	
}
?>

</select></th>
<td>
<table>
<tbody><tr>
<th align="right">Author</th>
<?php
	$searchauthor0c = $searchauthor0;
	$searchauthor1c = $searchauthor1;
	$searchauthor2c = $searchauthor2;
	if ($caller == 4)
	{
		$searchauthor0c = '"' . $searchauthor0 . '"';
//		$searchauthor1c = '"' . $searchauthor1 . '"';
//		$searchauthor2c = '"' . $searchauthor2 . '"';
	}
	
	echo "<td><input name='searchauthor0' value='" . $searchauthor0c . "'></td>";
	echo "<td><strong>OR</strong></td>";
	echo "<td><input name='searchauthor1' value='" . $searchauthor1c . "'></td>";
	echo "<td><strong>OR</strong></td>";
	echo "<td><input name='searchauthor2' value='" . $searchauthor2c . "'></td>";
?>

</tr>
<tr>
<th align="right">Title</th>
<?php
	echo "<td><input name='searchtitle0' value='" . $searchtitle0 . "'></td>";
	echo "<td><strong>OR</strong></td>";
	echo "<td><input name='searchtitle1' value='" . $searchtitle1 . "'></td>";
	echo "<td><strong>OR</strong></td>";
	echo "<td><input name='searchtitle2' value='" . $searchtitle2 . "'></td>";
?>

</tr>
<tr>
<th align="right">Translator</th>
<?php
	echo "<td><input name='searchtranslator' value='" . $searchtranslator . "'></td>";
?>
</tr>
<tr>
<th align="right">Editor</th>
<?php
	echo "<td><input name='searcheditor' value='" . $searcheditor . "'></td>";
?>
</tr>
<?php

$link = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $db_name)
    or die('Could not connect: ' . mysqli_error($link));

mysqli_query($link, "SET NAMES 'utf8'");



?>

<tr>
<th align="right">Genre</th>
<td><select name="searchpubtype">
<?php
//require 'db_connect.php';
//	connect();

	$query = "SELECT distinct Genre as l1 from " . $table_name . " where Genre <> ''";	
	
	$result = mysqli_query($link, $query) or die('Query failed: ' . mysqli_error($link));
	
	echo "<option>";
   	echo "";
	echo "</option>";
	
	while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		if ($line['l1'] == $searchtype)
			echo "<option selected='selected'>";
		else
			echo "<option>";
	   	echo $line['l1'];
		echo "</option>";
	}	
?>
</select></td>
</tr>

</tbody></table></td>
</tr>
</tbody></table>

Maximum number of hits per page: 
<select name="limit">
<option>25</option>
<option selected="selected">50</option>
<option>100</option>
<option>200</option>
<option>500</option>
<option>1000</option>
<?php 
echo "<option selected='selected'>$limit</option>";
?>
</select><br>

<input value="Submit" type="submit">
<input value="Reset" type="reset">

</form><p>

<!--0.735000total-->

<!-- END WEB AUTHOR CONTENT CODE -->

<?php 
//require("header.php");
//<a href="authors.php?id=1">Authors Browse</a> <strong>|</strong> <a href="subjects.php?id=1">Subjects Browse</a></p><!-- Timings --> 
$full_sub_list = array(0 => '');
$list_num = 0;

//$str_tmp = '"efg" 123 "and" 456 "and"     "abc"';
//do {
//	parse_doublequote($str_tmp, $list_out, $list_num);
//	echo "**" . $str_tmp . "**<p></p>";
//	echo "**" . $list_out[$list_num - 1] . "**<p></p>";
//	
//} while (strpos($str_tmp, '"') !== false);
//
//parse_space($str_tmp, $list_out, $list_num);
//for ($i = 0; $i < $list_num; $i++)
//	echo $i . "**" . $list_out[$i] . "**<p></p>";

function create_author_query($val, $link)
{
	$list_out = array(0 => '');
	$list_num = 0;	

// Process doublequotes and spaces	
	do {
		parse_doublequote($val, $list_out, $list_num);		
	} while (strpos($val, '"') !== false);
	
	parse_space($val, $list_out, $list_num);

	for ($i = 0; $i < $list_num; $i++)
	{
		$list_out[$i] = mysqli_real_escape_string($link, $list_out[$i]);
		add_wildcard($list_out[$i]);
	}
	$str1 = and_clause('Author_name', $list_out, $list_num);
	$str2 = and_clause('Author_translit', $list_out, $list_num);
	$str3 = and_clause('Author_fname', $list_out, $list_num);

	$str = "(" . $str1 . " or " . $str2 . " or " . $str3 . ")"; 
	 
//	$str = "( ORIGAUTHOR like '" . $val . "') or ";
//	$str = $str . "( MODAUTHOR like '" . $val . "') or ";
//	$str = $str . "( SUBSIDAUTHOR like '" . $val . "') or ";
//	$str = $str . "( NOTES like '" . $val . "')";
//	$str = "(" . $str . ")";
	return $str;	
}

function create_title_query($val, $link)
{
	$list_out = array(0 => '');
	$list_num = 0;	

// Process doublequotes and spaces	
	do {
		parse_doublequote($val, $list_out, $list_num);		
	} while (strpos($val, '"') !== false);
	
	parse_space($val, $list_out, $list_num);


	for ($i = 0; $i < $list_num; $i++)
	{
		$list_out[$i] = mysqli_real_escape_string($link, $list_out[$i]);
		add_wildcard($list_out[$i]);
	}
	
	$str1 = and_clause('Individual_title', $list_out, $list_num);
	$str2 = and_clause('Work_title', $list_out, $list_num);
	$str3 = and_clause('Uniform_title', $list_out, $list_num);
	$str4 = and_clause('Contents', $list_out, $list_num);
	$str5 = and_clause('Contents_translit', $list_out, $list_num);

	$str = "(" . $str1 . " or " . $str2 . " or " . $str3 . " or " . $str4 . " or " . $str5 . ")"; 
	 
	return $str;			
}

$guard = 0;
$guard = $guard + wildcard_process($searchauthor0);
$guard = $guard + wildcard_process($searchauthor1);
$guard = $guard + wildcard_process($searchauthor2);
$guard = $guard + wildcard_process($searchtitle0);
$guard = $guard + wildcard_process($searchtitle1);
$guard = $guard + wildcard_process($searchtitle2);
$guard = $guard + wildcard_process($searchtype);

//
//if ($guard > 0)
//	exit("");


//$searchauthor0 = mysqli_escape_string($link, $searchauthor0);

//add_wildcard($searchauthor0);
//add_wildcard($searchauthor1);
//add_wildcard($searchauthor2);
//add_wildcard($searchtitle0);
//add_wildcard($searchtitle1);
//add_wildcard($searchtitle2);

$query_auth0 = "";
$query_auth1 = "";
$query_auth2 = "";

$query_title0 = "";
$query_title1 = "";
$query_title2 = "";

$auth_num = 0;
$title_num = 0;

$str_dummy = " (1 = 1) ";

if ($searchauthor0 != '')
{
	if ($caller == 1)
		$query_auth0 = create_author_query($searchauthor0, $link);
	if ($caller == 4)
		$query_auth0 = "( Author_translit like '" . $searchauthor0 . "')";
		
	$auth_num = $auth_num + 4;
}

if ($searchauthor1 != '')
{
	$query_auth1 = create_author_query($searchauthor1, $link);
	$auth_num = $auth_num + 2;
}

if ($searchauthor2 != '')
{
	$query_auth2 = create_author_query($searchauthor2, $link);
	$auth_num = $auth_num + 1;
}

if ($searchtitle0 != '')
{
	$query_title0 = create_title_query($searchtitle0, $link);
	$title_num = $title_num + 4;
}

if ($searchtitle1 != '')
{
	$query_title1 = create_title_query($searchtitle1, $link);
	$title_num = $title_num + 2;
}

if ($searchtitle2 != '')
{
	$query_title2 = create_title_query($searchtitle2, $link);
	$title_num = $title_num + 1;
}

$searchtype = mysqli_real_escape_string($link, $searchtype);

if ($searchtype != '')
	$query_type = " (Genre like '%" . $searchtype . "%')";
else
	$query_type = "";

$searchtranslator = mysqli_real_escape_string($link, $searchtranslator);	
if ($searchtranslator != '')
	$query_translator = " ((Translator_name like '%" . $searchtranslator . "%') or (Translator_translit like '%" . $searchtranslator . "%'))";
else
	$query_translator = "";

$searcheditor = mysqli_real_escape_string($link, $searcheditor);	
if ($searcheditor != '')
	$query_editor = " ((Editor_name like '%" . $searcheditor . "%') or (Editor_translit like '%" . $searcheditor . "%'))";
else
	$query_editor = "";
	
switch ($auth_num) {
case 0:
    $query_auth = "";
    break;
case 1:
    $query_auth = $query_auth2;
    break;
case 2:
    $query_auth = $query_auth1;
    break;
case 3:
    $query_auth = $query_auth1 . " or " . $query_auth2;
    break;
case 4:
    $query_auth = $query_auth0;
    break;
case 5:
    $query_auth = $query_auth0 . " or " . $query_auth2;
    break;
case 6:
    $query_auth = $query_auth0 . " or " . $query_auth1;
    break;
case 7:
    $query_auth = $query_auth0 . " or " . $query_auth1 . " or " . $query_auth2;
    break;  
}

switch ($title_num) {
case 0:
    $query_title = "";
    break;
case 1:
    $query_title = $query_title2;
    break;
case 2:
    $query_title = $query_title1;
    break;
case 3:
    $query_title = $query_title1 . " or " . $query_title2;
    break;
case 4:
    $query_title = $query_title0;
    break;
case 5:
    $query_title = $query_title0 . " or " . $query_title2;
    break;
case 6:
    $query_title = $query_title0 . " or " . $query_title1;
    break;
case 7:
    $query_title = $query_title0 . " or " . $query_title1 . " or " . $query_title2;
    break;  
}

//echo "query_auth = " . $query_auth;
//echo '<p>';
//echo "query_title = " . $query_title;
//echo '<p>';
  


//echo '<p> Connecting to M SQL database ... <p>';
// Connecting, selecting database
//$link = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $db_name)
//    or die('Could not connect: ' . mysqli_error($link));

//echo 'Connected successfully';


// Performing SQL query

$query_count_pre = "SELECT count(*) FROM " . $table_name . " WHERE ";
$query_pre = "SELECT * FROM " . $table_name . " WHERE ";
$query = "";

$prefix = 0;
if ($auth_num > 0)
{
	$query = $query . "(" . $query_auth . ")";
	$prefix = 1;
}
	
if ($title_num > 0)
{
	if ($prefix == 1)
		$query = $query . " " . $op . " (" . $query_title . ")";
	else
	{
		$query = $query . " (" .  $query_title . ")";
		$prefix = 1;
	}
}

if ($query_type != "")
{
	if ($prefix == 1)
		$query = $query . " " . $op . " " . $query_type;
	else
	{
		$query = $query . $query_type;
		$prefix = 1;
	}
}

if ($query_translator != "")
{
	if ($prefix == 1)
		$query = $query . " " . $op . " " . $query_translator;
	else
	{
		$query = $query . $query_translator;
		$prefix = 1;
	}
}

if ($query_editor != "")
{
	if ($prefix == 1)
		$query = $query . " " . $op . " " . $query_editor;
	else
	{
		$query = $query . $query_editor;
		$prefix = 1;
	}
}

if ($prefix == 0)
	$query = $query . $str_dummy;

//echo $query;

$query_count = $query_count_pre . $query;
$query = $query_pre . $query . " ORDER BY ID LIMIT " . $limit . " OFFSET " . ($start - 1);

//echo $query . "\n";
$result_count = mysqli_query($link, $query_count) or die('Query failed: ' . mysqli_error($link));
while ($line = mysqli_fetch_row($result_count)) {
	$rec_count = $line[0];
}
//$rec_count = mysqli_affected_rows($link);

$result = mysqli_query($link, $query) or die('Query failed: ' . mysqli_error($link));

echo "<p>Records found: " . $rec_count . "<p>";

echo "<p align = 'center'>";

if ($start > 1)
{
	$str_url1 = "arabic-primary.php?caller=" . $caller . "&start=" . ($start - $limit) . "&op=" . $op . "&searchauthor0=" . $searchauthor0b . "&searchauthor1=" . $searchauthor1b . "&searchauthor2=" . $searchauthor2b . "&searchtitle0=" . $searchtitle0b . "&searchtitle1=" . $searchtitle1b . "&searchtitle2=" . $searchtitle2b . "&limit=" . $limit . "&searchpubtype=" . $searchtype . "&searchtranslator=" . $searchtranslator . "&searcheditor=" . $searcheditor;
	
	echo "<a href='" . $str_url1 . "'>&lt;&lt; Previous " . $limit . " hits</a>";
}

$stop = $start + $limit - 1;

if ($stop >= $rec_count)
{
	$next = $limit + $rec_count - $stop;	
	$stop = $rec_count;	
}

echo "<b>&nbsp;&nbsp;" . $start . "-" . $stop . " of " . $rec_count . "&nbsp;&nbsp;</b>";

if ($stop < $rec_count)
{
	$str_url2 = "arabic-primary.php?caller=" . $caller . "&start=" . ($start + $limit) . "&op=" . $op . "&searchauthor0=" . $searchauthor0b . "&searchauthor1=" . $searchauthor1b . "&searchauthor2=" . $searchauthor2b . "&searchtitle0=" . $searchtitle0b . "&searchtitle1=" . $searchtitle1b . "&searchtitle2=" . $searchtitle2b . "&limit=" . $limit . "&searchpubtype=" . $searchtype . "&searchtranslator=" . $searchtranslator . "&searcheditor=" . $searcheditor;
	$next = $limit;
	
	if ($rec_count - $stop < $limit)
		echo "<a href='" . $str_url2 . "'>Next " . ($rec_count - $stop) . " hits &gt;&gt;</a>";	
	else
		echo "<a href='" . $str_url2 . "'>Next " . ($limit) . " hits &gt;&gt;</a>";		
}

echo "</p><p>&nbsp;</p><p>&nbsp;</p>";

echo "\n<table ALIGN='LEFT' border=1>\n";

//if (!mysqli_data_seek($result, $start - 1)) {
//	echo "Cannot seek to row $start: " . mysqli_error($link) . "\n";	
//}

$i = 1;
	
//while (($line = mysqli_fetch_row($result)) && ($i <= $next)) {
while ($line = mysqli_fetch_row($result)) {
    
    $str_series = trim($line[22]);    
	$str_form = trim($line[28]);    
	$str_worktitle = trim($line[11]);

    $str_description = trim($line[23]);    
    $str_content = trim($line[18]);    
    $str_content_translit = trim($line[19]);    
    $str_notes = trim($line[21]);    
	$str_unititle = trim($line[12]);
	 	
//	$full_sub_list = array(0 => '');
//	$list_count = 0;
//	
//	$str_subject_out = "";
//	if ($str_subject != "")
//	{
//		creat_full_list($str_subject, $list_count, $full_sub_list);
//		
//		$str_subject_out = "<br><b>Subjects: </b>";
//		for ($j = 0; $j < $list_count - 1; $j++)
//		{
//			$sub_tmp = $full_sub_list[$j];
//			wildcard_process($full_sub_list[$j]);
//			$str_url3 = "arabic-primary.php?caller=3&start=1&op=AND&searchauthor0=&searchauthor1=&searchauthor2=" . "&searchtitle0=&searchtitle1=&searchtitle2=&searchsubject=" . $full_sub_list[$j] . "&limit=50&searchpubtype=";		
//	
//			$str_subject_out = $str_subject_out . "<a href='" . $str_url3 . "'>" . $sub_tmp . "</a>/";
//		}	
//		$sub_tmp = $full_sub_list[$j];
//		wildcard_process($full_sub_list[$j]);	
//		$str_url3 = "arabic-primary.php?caller=3&start=1&op=AND&searchauthor0=&searchauthor1=&searchauthor2=" . "&searchtitle0=&searchtitle1=&searchtitle2=&searchsubject=" . $full_sub_list[$j] . "&limit=50&searchpubtype=";
//		$str_subject_out = $str_subject_out . "<a href='" . $str_url3 . "'>" . $sub_tmp . "</a>";
//	}
	
//    $str_query = "SELECT * form bib WHERE ORIGAUTHOR = '" . $str_auth . "'";
//    $result1 = mysqli_query($link, $query) or die('Query failed: ' . mysqli_error($link));
//    $line1 = mysqli_fetch_row($result1);
//    echo $line1[3] . " --- " . $line1[4] . "<p></p>";
    
    echo "<tr>\n";
//    foreach ($line as $col_value) {
//        echo "\t\t<td>$col_value</td>\n";
	echo "<td ALIGN='RIGHT' VALIGN='TOP'>";
//	echo "  " . $i + $start - 1 . ". ";
	$id=$i + $start - 1;
	echo '  <a href="fullview.php?id=' . $line[0] . '">' . $id . ".</a>";	
	echo "</td>\n";

	echo "<td ALIGN='LEFT' VALIGN='TOP'><font size='2'>";

//echo '<a href="fullview.asp">' . "  " . $i + $start - 1 . ".</a>";
	
//    echo item_display($line,5);
    //echo "<br>"

$type = 5;    
// Books with subtitles    
if (trim($str_form) == 'book' && $str_worktitle != '')
	$type=1;

// Books as part of a series
if (trim($str_series) != '')
	$type=2;

// Journal articles
if (trim($str_form) == 'article')
	$type=3;

// chapters in books
if (trim($str_form) == 'inbook')
	$type=4;


echo item_display($line,$type);		

    
if ($str_description != "")
	echo "<br><b>Description: </b>" . $str_description;
if ($str_notes != "")
	echo "<br><b>Notes: </b>" . $str_notes;
if ($str_content != "")
	echo "<br><b>Contents: </b>" . $str_content;
if ($str_unititle != "")
	echo "<br><b>Uniform Title: </b>" . $str_unititle;
if ($str_content_translit != "")
	echo "<br><b>Contents translit: </b>" . $str_content_translit;


	echo "</font></td>\n";
    echo "\t</tr>\n";
	$i = $i + 1;
}

echo "</table>\n";
echo "<p>&nbsp;</p><p>&nbsp;</p><p align = 'center'>";

if ($start > 1)
{
	echo "<a href='" . $str_url1 . "'>&lt;&lt; Previous " . $limit . " hits</a>";
}

echo "<b>&nbsp;&nbsp;" . $start . "-" . $stop . " of " . $rec_count . "&nbsp;&nbsp;</b>";

if ($stop < $rec_count)
{
	if ($rec_count - $stop < $limit)
		echo "<a href='" . $str_url2 . "'>Next " . ($rec_count - $stop) . " hits &gt;&gt;</a>";	
	else
		echo "<a href='" . $str_url2 . "'>Next " . ($limit) . " hits &gt;&gt;</a>";	
}

echo "</p>";

// Free resultset
mysqli_free_result($result);

// Closing connection
mysqli_close($link);

require("footer.htm");
?> 
</html>
