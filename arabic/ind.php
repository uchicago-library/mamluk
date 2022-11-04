<html>
<?php
phpinfo();
echo '<p> Connecting to M SQL database ... <p>';
// Connecting, selecting database
$link = mysql_connect("localhost", "root", "redrose")
    or die('Could not connect: ' . mysql_error());

echo 'Connected successfully';
mysql_select_db('mamluk2') or die('Could not select database');
mysql_get_server_info($link);

// Performing SQL query
$query = 'SELECT * FROM bib';
$result = mysql_query($query) or die('Query failed: ' . mysql_error());

// Printing results in HTML
echo "<table>\n";
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    echo "\t<tr>\n";
    foreach ($line as $col_value) {
        echo "\t\t<td>$col_value</td>\n";
    }
    echo "\t</tr>\n";
}
echo "</table>\n";

// Free resultset
mysql_free_result($result);

// Closing connection
mysql_close($link);

?> 
</html>