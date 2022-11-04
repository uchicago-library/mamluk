<div class="crumbs">
	<!-- "BREADCRUMBS" -->
<div><strong>Arabic Literature Bibliography</strong><br></div>	

</div>
<div class="main">
  <!-- BEGIN WEB AUTHOR CONTENT CODE -->

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

<form action="arabic-primary.php" method="post">
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
<th align="right">Translator</th>
<td><input name="searchtranslator" value=""></input></td>
</tr>
<tr>
<th align="right">Editor</th>
<td><input name="searcheditor" value=""></input></td>
</tr>
<?php
//require("b.php");
require("funcs.php");
$table_name = "main_table";

$link = mysql_connect($mysql_server, $mysql_user, $mysql_password)
    or die('Could not connect: ' . mysql_error());

mysql_query("SET NAMES 'utf8'");

mysql_select_db($db_name) or die('Could not select database');

?>

<tr>
<th align="right">Genre</th>
<td><select name="searchpubtype">
<?php

//	$query = "SELECT distinct Citation_Type as l1 from " . $table_name . " where Citation_Type <> ''";	
	$query = "SELECT distinct Genre as l1 from " . $table_name . " where Genre <> ''";	
	
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	
	echo "<option>";
   	echo "";
	echo "</option>";
	
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo "<option>";
	   	echo $line['l1'];
		echo "</option>";
	}	
	
	// Free resultset
mysql_free_result($result);

// Closing connection
//mysql_close($link);
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
<a href="authors.php?id=1">Authors Browse</a> <strong>|</strong> <a href="subjects.php?id=1">Titles Browse</a></p><!-- Timings --> 
<!--0.735000total-->

<!-- END WEB AUTHOR CONTENT CODE -->