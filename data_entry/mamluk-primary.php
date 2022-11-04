<?php
	require("header.php");
?>

<div class="crumbs">
	<!-- "BREADCRUMBS" -->
	<div><strong>Mamluk Primary Bibliography</strong><br></div>
</div>
<div class="main">
  <!-- BEGIN WEB AUTHOR CONTENT CODE -->

<strong><a href="index.htm">
Mamluk Bibliography Online</a></strong><br>
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
	$table_name = "bib";
//	$searchArts = "Islam";
//	$searchArts = mysql_real_escape_string($searchArts);
//	echo "search arts = " . $searchArts . "<p></p>";

//$item = "Zak's Laptop";
//$item = mysql_escape_string($item);
//printf("Escaped string: %s\n", $item);
	

function item_display($line)
{
	$str_form = trim($line[1]);    
    $str_authrole = ucfirst(trim($line[6]));
    
// 3,4,5,7,8,10,11,12,13,14,15,16,17,18,19,20,21,29    
    $line_dot = $line;
	$line_comma = $line;

	for ($i = 3; $i < 30; $i++)
	{	
	    if ($line[$i] != "")
		{
	    	$line_dot[$i] = trim($line[$i]) . '. ';
	    	$line_comma[$i] = trim($line[$i]) . ', ';
	    
	    }	    
    }
    
    switch ($str_form) {
case "Book long":
    $str_display = $line_dot[3] . ' "' . $line_dot[4] . '" ' . trim($str_authrole) . ' ' . $line_dot[5] . ' In <I>' . trim($line[7]) . '</I>. ' . $line_dot[13] . ' ' . trim($line[15]) . ' ' . $line_dot[14] . ' ' . $line_dot[21] . ' ' . trim($line[16]) . ': ' . $line_comma[17] . ' ' . $line_dot[18];
    break;
case "Book short":
    $str_display = $line_dot[29];
    break;
case "Book Review":
    $str_display = $line_dot[3] . ' ' . $line_dot[4] . ' <I>' . trim($line[8]) . '</I> ' . $line_comma[19] . ' ' . trim($line[20]) . ' (' . trim($line[18]) . '): ' . $line_dot[21];
    break;
case "Dissertation":
    $str_display = $line_dot[3] . ' <I>"' . trim($line[7]) . '."</I> ' . trim($str_authrole) . " " . $line_dot[5] . ' ' . $line_dot[21] . ' ' . $line_comma[16] . ' ' . $line_dot[18];
    break;
case "Journal":
    $str_display = $line_dot[3] . ' "' . $line_dot[4] . '" <I>' . trim($line[8]) . '</I> ' . $line_comma[19] . ' ' . trim($line[20]) . ' (' . trim($line[18]) . '): ' . $line_dot[21];
    break;
case "Monographic long form":
    $str_display = $line_dot[3] . ' <I>' . trim($line[7]) . '</I>. ' . $line_dot[13] . ' ' . trim($str_authrole) . " " . $line_dot[5] . ' ' . $line_dot[21] . ' ' . trim($line[16]) . ': ' . $line_comma[17] . ' ' . $line_dot[18];
    break;
case "Conference":
    $str_display = $line_dot[3] . ' "' . $line_dot[4] . '" In <I>' . trim($line[7]) . '</I>, '. $line_comma[10] . ' ' . trim($str_authrole) . " " . $line_comma[5] . ' ' . $line_dot[21] . ' ' . $line_comma[12] . ' ' . $line_dot[11] . ' ' . trim($line[16]) . ': ' . $line_comma[17] . ' ' . $line_dot[18];
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
	$searchsubject = trim($_GET['searchsubject']);			
	$searchlanguage = trim($_GET['searchlanguage']);
	$limit = $_GET['limit'];
	
	settype($limit, 'integer');
	$limit = sprintf("%d", $limit);

	settype($start, 'integer');
	$start = sprintf("%d", $start);
	
	$checked_only = $_GET['checked_only'];
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
	$searchsubject = trim($_POST['searchsubject']);
	$searchlanguage = trim($_POST['searchlanguage']);
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
$searchsubjectb = $searchsubject;
$searchlanguageb = $searchlanguage;

//echo "searchsubject = " . $searchsubject;
?>

<!-- search component ::CONFIG::secsearch -->
<form action="mamluk-primary.php" method="post">
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
<th align="right">Subject</th>
<td><select name="searchsubject">

?>

<?php

$link = mysql_connect($mysql_server, $mysql_user, $mysql_password)
    or die('Could not connect: ' . mysql_error());

mysql_query("SET NAMES 'utf8'");



mysql_select_db($db_name) or die('Could not select database');

	$query = "SELECT Field1 FROM subjectlist";	
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	echo "<option>";
   	echo "";
	echo "</option>";
	
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if ($line['Field1'] == $searchsubject)
			echo "<option selected='selected'>";
		else
			echo "<option>";		
	   	echo $line['Field1'];
		echo "</option>";
	}	
?>
</select></td>
</tr>

<tr>
<th align="right">Language</th>
<td><select name="searchlanguage">
<?php
//require 'db_connect.php';
//	connect();

	$query = "SELECT distinct language as l1 from " . $table_name . " where language not like '%/%' " . 
	"UNION SELECT mid(language, 1, INSTR(language,'/') - 1) AS  language2 FROM " . $table_name . " where language like '%/%'" . 
	"UNION SELECT mid(language, INSTR(language,'/') + 1, length(language)) AS  language3 FROM " . $table_name . " where language like '%/%'";	
	
//	$query = "SELECT distinct language as l1 from bib where language not like '%/%'";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	echo "<option>";
   	echo "";
	echo "</option>";
	
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if ($line['l1'] == $searchlanguage)
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
<a href="authors.php?id=1">Authors Browse</a> <strong>|</strong> <a href="subjects.php?id=1">Subjects Browse</a></p><!-- Timings --> 
<!--0.735000total-->

<!-- END WEB AUTHOR CONTENT CODE -->

<?php 

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
		$list_out[$i] = mysql_real_escape_string($list_out[$i], $link);
		add_wildcard($list_out[$i]);
	}
	$str1 = and_clause('ORIGAUTHOR', $list_out, $list_num);
	$str2 = and_clause('MODAUTHOR', $list_out, $list_num);
	$str3 = and_clause('SUBSIDAUTHOR', $list_out, $list_num);
	$str4 = and_clause('NOTES', $list_out, $list_num);

	$str = "(" . $str1 . " or " . $str2 . " or " . $str3 . " or " . $str4 . ")"; 
	 
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
		$list_out[$i] = mysql_real_escape_string($list_out[$i], $link);
		add_wildcard($list_out[$i]);
	}
	
	$str1 = and_clause('ARTICLETITLE', $list_out, $list_num);
	$str2 = and_clause('BOOKTITLE', $list_out, $list_num);
	$str3 = and_clause('ORIGTITLE', $list_out, $list_num);
	$str4 = and_clause('SERIESTITLE', $list_out, $list_num);
	$str5 = and_clause('CONFTITLE', $list_out, $list_num);
	$str6 = and_clause('NOTES', $list_out, $list_num);

	$str = "(" . $str1 . " or " . $str2 . " or " . $str3 . " or " . $str4 . " or " . $str5 . " or " . $str6 . ")"; 
	 
	return $str;			
}

$guard = 0;
$guard = $guard + wildcard_process($searchauthor0);
$guard = $guard + wildcard_process($searchauthor1);
$guard = $guard + wildcard_process($searchauthor2);
$guard = $guard + wildcard_process($searchtitle0);
$guard = $guard + wildcard_process($searchtitle1);
$guard = $guard + wildcard_process($searchtitle2);
$guard = $guard + wildcard_process($searchsubject);
$guard = $guard + wildcard_process($searchlanguage);
//
//if ($guard > 0)
//	exit("");


//$searchauthor0 = mysql_escape_string($searchauthor0);

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
		$query_auth0 = "( ORIGAUTHOR like '" . $searchauthor0 . "')";
		
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

$searchsubject = mysql_real_escape_string($searchsubject, $link);	
if ($searchsubject != '')
	$query_subject = " (SUBJECT like '%" . $searchsubject . "%')";
else
	$query_subject = "";

//echo "searchsubject = " . $searchsubject;

$searchlanguage = mysql_real_escape_string($searchlanguage, $link);	
if ($searchlanguage != '')
	$query_language = " (LANGUAGE like '%" . $searchlanguage . "%')";
else
	$query_language = "";
	
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
//$link = mysql_connect($mysql_server, $mysql_user, $mysql_password)
//    or die('Could not connect: ' . mysql_error());

//echo 'Connected successfully';
//mysql_select_db($db_name) or die('Could not select database');



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

if ($query_subject != "")
{
	if ($prefix == 1)
		$query = $query . " " . $op . " " . $query_subject;
	else
	{
		$query = $query . $query_subject;
		$prefix = 1;
	}
}

if ($query_language != "")
{
	if ($prefix == 1)
		$query = $query . " " . $op . " " . $query_language;
	else
		$query = $query . $query_language;
}

	
if (($query_subject == "") && ($query_language == "") && ($auth_num == 0) && ($title_num == 0))
	$query = $query . $str_dummy;

if ($checked_only == 1)
	$query = "STATUS='yes'";
	
$query_count = $query_count_pre . $query;
$query = $query_pre . $query . " ORDER BY ID LIMIT " . $limit . " OFFSET " . ($start - 1);


//echo $query . "\n";
$result_count = mysql_query($query_count) or die('Query failed: ' . mysql_error());
while ($line = mysql_fetch_row($result_count)) {
	$rec_count = $line[0];
}
//$rec_count = mysql_affected_rows();

$result = mysql_query($query) or die('Query failed: ' . mysql_error());

echo "<p>Records found: " . $rec_count . "<p>";
echo "<br>";
echo "<a href='insert.php?id=1'>Add new entry to the Mamluk Primary Bibliography</a>";
echo "<br>";
$str_url1 = "mamluk-primary.php?caller=" . $caller . "&start=1&op=" . $op . "&searchauthor0=" . $searchauthor0b . "&searchauthor1=" . $searchauthor1b . "&searchauthor2=" . $searchauthor2b . "&searchtitle0=" . $searchtitle0b . "&searchtitle1=" . $searchtitle1b . "&searchtitle2=" . $searchtitle2b . "&searchsubject=" . $searchsubject . "&limit=" . $limit . "&searchlanguage=" . $searchlanguage . "&checked_only=1";
echo "<a href='" . $str_url1 . "'>Show all unchecked entries</a>";
echo "<p>* <strong>If you can't see the diacritics correctly, please see </strong><a href='Config.htm'>Configuring Browsers for Unicode</a>. </p>";


echo "<p align = 'center'>";

if ($start > 1)
{
	$str_url1 = "mamluk-primary.php?caller=" . $caller . "&start=" . ($start - $limit) . "&op=" . $op . "&searchauthor0=" . $searchauthor0b . "&searchauthor1=" . $searchauthor1b . "&searchauthor2=" . $searchauthor2b . "&searchtitle0=" . $searchtitle0b . "&searchtitle1=" . $searchtitle1b . "&searchtitle2=" . $searchtitle2b . "&searchsubject=" . $searchsubject . "&limit=" . $limit . "&searchlanguage=" . $searchlanguage;
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
	$str_url2 = "mamluk-primary.php?caller=" . $caller . "&start=" . ($start + $limit) . "&op=" . $op . "&searchauthor0=" . $searchauthor0b . "&searchauthor1=" . $searchauthor1b . "&searchauthor2=" . $searchauthor2b . "&searchtitle0=" . $searchtitle0b . "&searchtitle1=" . $searchtitle1b . "&searchtitle2=" . $searchtitle2b . "&searchsubject=" . $searchsubject . "&limit=" . $limit . "&searchlanguage=" . $searchlanguage;
	$next = $limit;
	
	if ($rec_count - $stop < $limit)
		echo "<a href='" . $str_url2 . "'>Next " . ($rec_count - $stop) . " hits &gt;&gt;</a>";	
	else
		echo "<a href='" . $str_url2 . "'>Next " . ($limit) . " hits &gt;&gt;</a>";	
	
}

echo "</p><p>&nbsp;</p><p>&nbsp;</p>";

echo "\n<table ALIGN='LEFT' border=1>\n";

//if (!mysql_data_seek($result, $start - 1)) {
//	echo "Cannot seek to row $start: " . mysql_error() . "\n";	
//}

$i = 1;
	
//while (($line = mysql_fetch_row($result)) && ($i <= $next)) {
while ($line = mysql_fetch_row($result)) {
    
    $str_auth = trim($line[3]);    
    $str_form = trim($line[1]);    
	$str_notes = trim($line[25]);
	$str_title = trim($line[9]);
	$str_subject = trim($line[28]);
	$str_seriestitle = trim($line[22]);
	$str_seriesvol =  trim($line[23]);
	$str_other =  trim($line[29]);
	 	
	$full_sub_list = array(0 => '');
	$list_count = 0;
	
	$str_subject_out = "";
	if ($str_subject != "")
	{
		creat_full_list($str_subject, $list_count, $full_sub_list);
		
		$str_subject_out = "<br><b>Subjects: </b>";
		for ($j = 0; $j < $list_count - 1; $j++)
		{
			$sub_tmp = $full_sub_list[$j];
			wildcard_process($full_sub_list[$j]);
			$str_url3 = "mamluk-primary.php?caller=3&start=1&op=AND&searchauthor0=&searchauthor1=&searchauthor2=" . "&searchtitle0=&searchtitle1=&searchtitle2=&searchsubject=" . $full_sub_list[$j] . "&limit=50&searchlanguage=";		
	
			$str_subject_out = $str_subject_out . "<a href='" . $str_url3 . "'>" . $sub_tmp . "</a>/";
		}	
		$sub_tmp = $full_sub_list[$j];
		wildcard_process($full_sub_list[$j]);	
		$str_url3 = "mamluk-primary.php?caller=3&start=1&op=AND&searchauthor0=&searchauthor1=&searchauthor2=" . "&searchtitle0=&searchtitle1=&searchtitle2=&searchsubject=" . $full_sub_list[$j] . "&limit=50&searchlanguage=";
		$str_subject_out = $str_subject_out . "<a href='" . $str_url3 . "'>" . $sub_tmp . "</a>";
	}
	
//    $str_query = "SELECT * form bib WHERE ORIGAUTHOR = '" . $str_auth . "'";
//    $result1 = mysql_query($query) or die('Query failed: ' . mysql_error());
//    $line1 = mysql_fetch_row($result1);
//    echo $line1[3] . " --- " . $line1[4] . "<p></p>";
    
    echo "<tr>\n";
//    foreach ($line as $col_value) {
//        echo "\t\t<td>$col_value</td>\n";
	echo "<td ALIGN='RIGHT' VALIGN='TOP'>";
	$id=$i + $start - 1;
	echo '  <a href="fullview1.php?id=' . $line[0] . '">' . $id . ".</a> ";		
//	echo "  " . $i + $start - 1 . ". ";
	echo "</td>\n";

	echo "<td ALIGN='LEFT' VALIGN='TOP'>";

switch (trim($str_form)) {
case "Book long":
    echo item_display($line);
    if ($str_seriestitle != "")
    	echo "<br><b>Series: </b>" . $str_seriestitle . ", " . $str_seriesvol;
    if ($str_notes != "")
    	echo "<br><b>Notes: </b>" . $str_notes;
    if ($str_title != "")
    	echo "<br><b>Uniform Title: </b>" . $str_title;
    if ($str_subject != "")
		echo $str_subject_out;
    break;
case "Book short":
	echo item_display($line);
    break;
case "Book Review":
    echo item_display($line);
    if ($str_notes != "")
    	echo "<br><b>Notes: </b>" . $str_notes;
    if ($str_title != "")
    	echo "<br><b>Uniform Title: </b>" . $str_title;
    if ($str_subject != "")
		echo $str_subject_out;  
    break;
case "Dissertation":
    echo item_display($line);
    if ($str_notes != "")
    	echo "<br><b>Notes: </b>" . $str_notes;
    if ($str_title != "")
    	echo "<br><b>Uniform Title: </b>" . $str_title;
    if ($str_subject != "")
		echo $str_subject_out;  
    break;
case "Journal":
	echo item_display($line);
    if ($str_notes != "")
    	echo "<br><b>Notes: </b>" . $str_notes;
    if ($str_title != "")
    	echo "<br><b>Uniform Title: </b>" . $str_title;
    if ($str_subject != "")
		echo $str_subject_out;  
    break;
case "Monographic long form":
    echo item_display($line);
    if ($str_seriestitle != "")
    	echo "<br><b>Series: </b>" . $str_seriestitle . ", " . $str_seriesvol;
    if ($str_notes != "")
    	echo "<br><b>Notes: </b>" . $str_notes;
    if ($str_title != "")
    	echo "<br><b>Uniform Title: </b>" . $str_title;
    if ($str_subject != "")
		echo $str_subject_out;   
    break;
case "Conference":
	echo item_display($line);
    if ($str_seriestitle != "")
    	echo "<br><b>Series: </b>" . $str_seriestitle . ", " . $str_seriesvol;
    if ($str_notes != "")
    	echo "<br><b>Notes: </b>" . $str_notes;
    if ($str_title != "")
    	echo "<br><b>Uniform Title: </b>" . $str_title;
    if ($str_subject != "")
		echo $str_subject_out;    
    break;
}
	
//	echo "  " . $str_auth;
	echo "</td>\n";
	
//	link to edit
	echo "<td ALIGN='RIGHT' VALIGN='TOP'>";
	echo '  <a href="edit.php?bib=1&id=' . $line[0] . '">Edit</a>';		
	echo "</td>\n";
	
//	link to remove
	echo "<td ALIGN='RIGHT' VALIGN='TOP'>";
	echo '  <a href=javascript:MsgOkCancel("remove.php?bib=1&id=' . $line[0] . '"); >Remove</a>';		
	echo "</td>\n";
		
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
mysql_free_result($result);

// Closing connection
mysql_close($link);

require("footer.htm");
?> 
</html>