<html>
<?php
phpinfo();
echo '<p> Connecting to M SQL database ... <p>';
// Connecting, selecting database
$link = mysqli_connect("localhost", "root", "redrose", "mamluk2")
    or die('Could not connect: ' . mysqli_error($link));

echo 'Connected successfully';
mysqli_get_server_info($link);

// Performing SQL query
$query = 'SELECT * FROM bib';
$result = mysqli_query($link, $query) or die('Query failed: ' . mysqli_error($link));

// Printing results in HTML
echo "<table>\n";
while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    echo "\t<tr>\n";
    foreach ($line as $col_value) {
        echo "\t\t<td>$col_value</td>\n";
    }
    echo "\t</tr>\n";
}
echo "</table>\n";

// Free resultset
mysqli_free_result($result);

// Closing connection
mysqli_close($link);

?> 
</html>
