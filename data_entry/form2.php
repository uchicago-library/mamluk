<div class="crumbs">
	<!-- "BREADCRUMBS" -->
<div><strong>Mamluk Secondary Bibliography</strong><br></div>	

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
  <li><font size="-1">Wildcards (%, _, and [ ]) may be used freely. For explanations, please see 
	the <a href="http://www.lib.uchicago.edu/e/su/mideast/mamluk/searchhelp.html">expanded search help</a> page.</font>
  </li>
  <li><font size="-1">Modern authors are not displayed in the primary bibliography's author browse 
	list but may be found via the author search.</font></li>
  <li><font size="-1">For explanations and examples of search features, please see the <a href="http://www.lib.uchicago.edu/e/su/mideast/mamluk/searchhelp.html">expanded search help</a> page.</font></li>
</ul>
<!-- search component ::CONFIG::secsearch -->

<form action="mamluk-secondary.php" method="post">
<input name="start" value= "1" type="hidden"></input>
<table>
<tbody><tr>
<th valign="center">
<select name="op">
<option selected="selected">AND</option>
<option>OR</option>
</select></th>
<td>
<table>
<tbody><tr>
<th align="right">Author</th>
<td><input name="searchauthor0" value=""></input></td>
<td><strong>OR</strong></td>
<td><input name="searchauthor1" value=""></input></td>
<td><strong>OR</strong></td>
<td><input name="searchauthor2" value=""></input></td>
</tr>
<tr>
<th align="right">Title</th>
<td><input name="searchtitle0" value=""></input></td>
<td><strong>OR</strong></td>
<td><input name="searchtitle1" value=""></input></td>
<td><strong>OR</strong></td>
<td><input name="searchtitle2" value=""></input></td>
</tr>
<tr>
<th align="right">Subject</th>
<td><select name="searchsubject">
<?php
$table_name = "bib2";
$sub_table_name = "subjectlist2";

require("funcs.php");

$link = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $db_name)
    or die('Could not connect: ' . mysqli_error($link));

mysqli_query($link, "SET NAMES 'utf8'");

	$query = "SELECT Field1 FROM " . $sub_table_name;
	
	$result = mysqli_query($link, $query) or die('Query failed: ' . mysqli_error($link));
	
	echo "<option>";
   	echo "";
	echo "</option>";
	
	while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
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
	
	$result = mysqli_query($link, $query) or die('Query failed: ' . mysqli_error($link));
	
	echo "<option>";
   	echo "";
	echo "</option>";
	
	while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		echo "<option>";
	   	echo $line['l1'];
		echo "</option>";
	}	
	
	
	// Free resultset
mysqli_free_result($result);

// Closing connection
//mysqli_close($link);
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
</select><br>

<input value="Submit" type="submit">
<input value="Reset" type="reset">
</form><p>
<a href="authors.php?id=2">Authors Browse</a> <strong>|</strong> <a href="subjects.php?id=2">Subjects Browse</a></p><!-- Timings --> 
<!--0.735000total-->

<!-- END WEB AUTHOR CONTENT CODE -->
