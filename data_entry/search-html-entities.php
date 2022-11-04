<?php 

//  search-html-entities.php
//
//  search for unicode characters encoded as html entities in all text fields in specified table
//  that appear as &#nnnn; where "nnnn" is 2 to 5 digits in length.
//
//  Give links to "Fix" a specific, Field, Row, Column, or All occurances in the table.
//

session_start();

$DOUPDATES = 1;	 // set to 0 during testing/debugging.
$DEBUG = 0;		  // set to 1 for dubugging output
$VERBOSE = 0;		  // set to 1 for verbose output

require("header.php");

?>
<div class="main">
<p>Instuctions for fixing/removing the HTML entity encoded unicode characters.</p>
<ul>
<li>In the Summary table</li>
   <ul>
   <li>Click "Fix All" to fix that field in ALL records</li>
   <li>Click "Fix All Records" to fix ALL fields in ALL records</li>
   </ul>
<li>In the Records table</li>
   <ul>
   <li>Click the record ID to View that record</li>
   <li>Click "Edit" to edit the record</li>
   <li>Click "Fix row" to update all fields in that record</li>
   <li>Click the Fieldname link to display a deatiled view of the fields before and after the update without actually updating the record.</li>
   </ul>
</ul>
<?php
// Connect to database.

# $link = mysqli_connect($mysql_server, $mysql_user, $mysql_password)
$mysqli = new mysqli($mysql_server, $mysql_user, $mysql_password);

if ( $mysqli->connect_errno ) {
	 die('ERROR - connect: ' . $mysqli->connect_error);
}

$mysqli->query("SET NAMES 'utf8'");
if ( $mysqli->error ) {
	die( "ERROR - SET NAMES: " . $mysqli->error );
}

$mysqli->select_db($db_name) ;
if ( $mysqli->error ) {
	die( "ERROR - select_db: " . $mysqli->error );
}

if ( ! $DOUPDATES ) {
	echo "<br />";
	echo "<p><strong>NOTE:  Updates are disabled at this time.  Clicking any \"Fix\" links will only display a summary of what would have changed.</strong></p>\n";
	echo "<br />";
}

// Process URL parameters

if ( isset( $_GET["bib"] ) ) {
	if ( preg_match('/^\d+$/', $_GET["bib"] , $matches ) ) {
		$bib = $matches[0];
		Debug_Print( "bib=$bib" );
	} else {
		die( "ERROR - bib is tainted" );
	}
	if ( $bib == 1 ) {
		$table_name="bib";
	} elseif ( $bib == 2 ) {
		$table_name="bib2";
	} else {
		die( "ERROR - Invalid bib." );
	}
} else {
	die( "ERROR - bib not set" );
}

if ( isset( $_GET["updatefield"] ) ) {
	if ( preg_match('/^\w+$/', $_GET["updatefield"] , $matches ) ) {
		$updatefield = $matches[0];
		Debug_Print( "updatefield=$updatefield" );
	} else {
		die( "ERROR - updatefield is tainted" );
	}
}

if ( isset( $_GET["updaterowid"] ) ) {
	if ( preg_match('/^\d+$/', $_GET["updaterowid"] , $matches ) ) {
		$updaterowid = $matches[0];
		Debug_Print( "updaterowid=$updaterowid" );
	} else {
		die( "ERROR - updaterowid is tainted" );
	}
}

if ( isset( $_GET["updateall"] ) ) {
	if ( preg_match('/^\w+$/', $_GET["updateall"] , $matches ) ) {
		$updateall = $matches[0];
		Debug_Print( "updateall=$updateall" );
	} else {
		die( "ERROR - updateall is tainted" );
	}
}

if ( isset( $_GET["verbose"] ) ) {
	if ( preg_match('/^\d+$/', $_GET["verbose"] , $matches ) ) {
		$VERBOSE = $matches[0];
		Debug_Print( "VERBOSE=$VERBOSE" );
	} else {
		die( "ERROR - verbose is tainted" );
	}
}

if ( isset( $_GET["update"] ) ) {
	if ( preg_match('/^\d+$/', $_GET["update"] , $matches ) ) {
		$DOUPDATES = $matches[0];
		Debug_Print( "DOUPDATES=$DOUPDATES" );
	} else {
		die( "ERROR - update is tainted" );
	}
}




// Build SQL queries for later use.

$textcolumnnames=Get_Text_Column_Names( $db_name, $table_name );

$summarycolumns = "";
$alltextfieldsquery = "";

foreach ( $textcolumnnames as $column ) {
	if ( $summarycolumns != "" ) {
		$summarycolumns .= ", ";
	}
	$summarycolumns .= 'COUNT( IF( '.$column.' REGEXP "&#[0-9][0-9]+", 1, NULL ) ) ' . $column ;
	if ( $alltextfieldsquery != "" ) {
		$alltextfieldsquery .= " OR ";
	}
	$alltextfieldsquery .= ' '.$column.' REGEXP "&#[0-9][0-9]+"';
}

$summaryquery = "SELECT " . $summarycolumns . ", COUNT(*) 'All_Records' FROM $table_name WHERE " . $alltextfieldsquery ;
$tablequery = "SELECT * FROM $table_name WHERE " . $alltextfieldsquery;


// Perform Updates.  Possibilities are:
//	by row and field 
//	by row (all fields)
//	by field (all rows)
//	all

if ( isset( $updatefield ) && isset( $updaterowid ) ) {
	// Update one field in one row
	Update_Row( $table_name, "ID = $updaterowid", $updatefield ) ;
	echo '<br /><br />';
	echo '<a href="?bib='.$bib.'">Return to search</a>';
	exit;

} elseif ( isset( $updaterowid ) ) {
	// Update all text fields in one row
	Update_Row( $table_name, "ID = $updaterowid", $textcolumnnames ) ;
	echo '<br /><br />';
	echo '<a href="?bib='.$bib.'">Return to search</a>';
	exit;

} elseif ( isset( $updatefield ) ) {
	// Update one field in all rows 
	Update_Row( $table_name, $updatefield.' REGEXP "&#[0-9][0-9]+"', $updatefield ) ;
	echo '<br /><br />';
	echo '<a href="?bib='.$bib.'">Return to search</a>';
	exit;

} elseif ( isset( $updateall ) ) {
	// Update all text fields in all rows
	Update_Row( $table_name, $alltextfieldsquery, $textcolumnnames ) ;
	echo '<br /><br />';
	echo '<a href="?bib='.$bib.'">Return to search</a>';
	exit;
}

// Display tables

echo "<br />\n";
echo "<br />\n";
Display_Summary( $summaryquery );

echo "<br />\n";
Display_Fields_With_Entities( $tablequery );

// Closing connection
$mysqli->close();
if ( $mysqli->error ) {
	echo "<p><strong>ERROR - close: $mysqli->error</strong></p>\n";
}

require("footer.htm");
?> 
</div>
</html>
<?php 

### #############################################################
###
### Function definitions...
###
### #############################################################

function Get_Text_Column_Names( $db_name, $table_name ) {

	// Search the columns table in the information_schema database for all fields
	// in $table_name in database $db_name with "text" or "char" in their type.

	global $mysqli;

	$columns=array();
	$result = $mysqli->query( "
		SELECT *
		FROM information_schema.columns 
		WHERE TABLE_SCHEMA='$db_name' 
		  AND TABLE_NAME='$table_name'
		  AND ( DATA_TYPE LIKE '%text%' OR DATA_TYPE LIKE '%char%' )
		" );
	if ( $mysqli->error ) {
		die( "ERROR - query information_schema.columns: " . $mysqli->error );
	}
	while ( $row = $result->fetch_assoc() ) {
		array_push( $columns, $row["COLUMN_NAME"] );
	}
	$result->free();
	return( $columns );
}


function utf8_entity_decode($entity){
	// Return the unicode UTF-8 character for the specified HTML entity number passed.
	// http://php.net/manual/en/function.mb-decode-numericentity.php

	$convmap = array(0x0, 0x10000, 0, 0xfffff);
	if ( is_array( $entity ) ) {
		return mb_decode_numericentity($entity[0], $convmap, 'UTF-8');
	} else {
		return mb_decode_numericentity($entity, $convmap, 'UTF-8');
	}
}


function Display_Summary( $summaryquery ) {
	// Display a table of field names and number of occurances of records with HTML entities in that fields
	// along with a link to "Fix" all occurances of those entities within the specified field.
	// At the bottom, display total number of records along with a link to "Fix" all fields in all records.

	global $mysqli;
	global $bib;
	
	$result = $mysqli->query( $summaryquery );
	if ( $mysqli->error ) {
		echo "<p>query=$summaryquery</p>\n";
		die( "ERROR - query: " . $mysqli->error );
	}
	echo '<table border="1">';
	echo "<tr><th colspan=\"2\"><br />Summary of fields and records with html entities encoded<br /><br /></ht></tr>\n";
	echo "<tr><th>Field name</th><th>Records</th></tr>\n";
	while ( $row = $result->fetch_assoc() ) {
		foreach ( $row as $col => $val ) {
			if ( $val > 0 ) {
				if ( $col <> "All_Records" ) {
					echo "<tr>";
					echo "<td nowrap><div style=\"float:left;\"><strong>$col</strong></div><div style=\"float:right;\"><a href=\"?bib=$bib&updatefield=$col\">Fix&nbspAll</a></div></td>";
					echo "<td align=\"right\">$val</td>";
					echo "</tr>";
				} else {
					echo "<tr>";
					echo "<td nowrap><div style=\"float:left;\"><strong>$col</strong></div><div style=\"float:right;\"><a href=\"?bib=$bib&updateall=$col\">Fix&nbspAll&nbsp;Records</a></div></td>";
					echo "<td align=\"right\">$val</td>";
					echo "</tr>";
				}
			}
		}
	}
	echo '</table>';
	echo '<br />';
	echo '<br />';
	$result->free();
}


function Display_Fields_With_Entities( $query ) {
	// Display a table of records along with a table of fields within each record that have HTML encoded entities.
	// Each field will have a link to "Fix" that field within that record.
	// Each record will also have links to "Fix" all fields in that record, as well as links to
	// "Display" the full record or "Edit" the record.

	global $bib ;
	global $mysqli;
	global $table_name;

	$result = $mysqli->query( $query );
	if ( $mysqli->error ) {
		die( "ERROR - query: " . $mysqli->error );
	}
	
	$id = 0;
	$num = 0;
	echo '<table border="1" >';
	echo "<tr><th colspan=\"2\"><br />All records in $table_name wtih HTML encoded unicode characters.<br />\n";
	echo $result->num_rows . ' Records displayed<br /><br /></th></tr>';
	echo '<tr>';
	echo '<th>ID</th>';
	echo '<th><br />Fields<br /><br /></th>';
	echo '</tr>';
	while ( $row = $result->fetch_assoc() ) {
		echo '<tr>';
		foreach ( $row as $col => $val ) {
			if ( $col == "ID" ) {
				$id = $val;
				echo "<td>";
				echo '<a href="fullview.php?bib=' . $bib . '&id=' . $id . '">' . $id . '</a><br />';
				echo '<a href="edit.php?bib=' . $bib . '&id=' . $id . '">Edit</a><br />';
				echo '<a href="?bib=' . $bib . '&updaterowid=' . $id . '">Fix&nbsp;row</a>';
				echo "</td>";
				echo "<td align=\"left\">";
				echo "<table border=\"0\" style=\"margin-left: 0em;\">";
			} else {
				if ( preg_match_all( '/(&#\d{2,5};)/', $val, $matches ) ) {
					$countattributes[$col]++;
					echo "<tr>";
					echo "<td>";
					# echo "<div style=\"float:left;\"><strong>$col</strong></div>";
					# echo "<div style=\"float:right;\"><a href=\"?bib=$bib&updaterowid=$id&updatefield=$col\">Fix&nbsp;field</a>:</div>";
					echo "<a href=\"?bib=$bib&updaterowid=$id&updatefield=$col&verbose=1&update=0\">$col</a>:";
					echo "</td>";
					echo "<td>";
					echo $val;
					echo '<br />';
					foreach ( $matches[1] as $match ) {
						$code = substr( $match, 1 );
						$countcodes[ $code ]++;
						$countattributecodes[ $col ][ $code ]++;
						Verbose_Print( "&amp;$code = $match : " . utf8_entity_decode( $match ) . '<br />' );
					}
					$newval = preg_replace_callback( '/(&#\d{2,5};)/', 'utf8_entity_decode', $val );
					Verbose_Print( $newval );
					if ( preg_match_all( '/(&#\d{2,5};)/', $newval, $newmatches ) ) {
						Verbose_Print( '<br />' );
						foreach ( $matches[1] as $match ) {
							Verbose_Print( "$match : " . utf8_entity_decode( $match ) . '<br />' );
						}
					}
					## Verbose_Print( '<br />Original/Updated lengths: ' . strlen( $val ) . '/' . strlen( $newval )  );
					echo '</td>';
					echo '</tr>';
				}
			}
		}
		echo "</table>";
		echo "</td>";
		echo '</tr>';
	}
	echo '</table>';
	echo '<br />';
	echo "<p><strong>Summary of code occurances by field</strong></p>\n";
	echo '<table border="1">';
	$r = 0;
	foreach ( $countattributes as $a => $ai ) {
		if ( $r++ == 0 ) {
			echo "<tr><th>Field / Codes</th>";
			foreach ( $countcodes as $c => $ci ) {
				echo "<th>$c &$c</th>";
			}
			echo "<tr>\n";
		}
		echo "<tr>";
		echo "<td>$a</<td>";
		foreach ( $countcodes as $c => $ci ) {
			echo "<td align=\"right\">" . $countattributecodes[ $a ][ $c ] . "</td>";
		}
		echo "</tr>";
	}
	$r = 0;
	foreach ( $countcodes as $c => $ci ) {
		if ( $r++ == 0 ) {
			echo "<tr><th>Totals</th>";
		}
		echo "<td align=\"right\"><strong>$ci</strong></td>";
	}
	echo "</tr></table>\n";

	$result->free();
}


function Verbose_Print( $message ) {
	// Print $message if VERBOSE mode is set.
	if ( $GLOBALS["VERBOSE"] ) {
		echo $message ;
	}
}


function Debug_Print( $message ) {
	// Print $message if DEBUG mode is set.
	if ( $GLOBALS["DEBUG"] ) {
		echo "<p>DEBUG: $message</p>\n" ;
	}
}


function Update_Row( $table_name, $wherecondition, $fields ) {
	// Update the field (or fields, if an array) in each row (or rows, if wherecondition meets more than one)

	global $mysqli;

	if ( ! is_array( $fields ) ) {
		$fields = array( $fields );
	}
	$query = "SELECT ID, " . implode(',', $fields ) . " FROM $table_name WHERE $wherecondition";
	$result = $mysqli->query( $query );
	Debug_Print( $query );
	if ( $mysqli->error ) {
		die( "ERROR - query ($query): " . $mysqli->error );
	}

	$rowsupdated = 0;
	$rowsselected = 0;
	$totalfieldsupdated = 0;
	$totalfieldsselected = 0;
	while ( $row = $result->fetch_assoc() ) {
		$rowid = $row["ID"];
		Debug_Print( "rowid: $rowid" );
		$rowsselected++;
		$fieldsselected=0;
		$fieldsupdated=0;
		foreach ( $fields as $updatefield ) {
			$val = $row[$updatefield];
			Debug_Print( "Checking col: $updatefield" );
			if ( preg_match_all( '/(&#\d{2,5};)/', $val, $matches ) ) {
				Debug_Print( "Found col: $updatefield" );
				$fieldsselected++;
				foreach ( $matches[1] as $match ) {
					$code = substr( $match, 1 );
					$code = str_pad( $code, 6 );
					$code=str_replace( ' ','&nbsp;',$code);
					Debug_Print( "&amp;$code = $match : " . utf8_entity_decode( $match ) );
					Verbose_Print("<div style=\"font-family: monospace\">&amp;$code = $match : " . utf8_entity_decode( $match ) . "</div>\n" );
				}
				$newval = preg_replace_callback( '/(&#\d{2,5};)/', 'utf8_entity_decode', $val );
				Debug_Print( "Updated value: $newval" );
				$displayval = preg_replace( '/&/', '&amp;', $val ) ;
				Verbose_Print( "Coded/Original/Updated values:<div style=\"font-family: monospace\">$displayval<br />$val<br />$newval</div>" );
				if ( preg_match_all( '/(&#\d{2,5};)/', $newval, $newmatches ) ) {
					echo 'WARNING:  Still found HTML encoded characters after utf8_entity_decode:<br />';
					foreach ( $matches[1] as $match ) {
						echo "$match : " . utf8_entity_decode( $match ) . '<br />';
					}
				}
				## Debug_Print( 'Original/Updated lengths: ' . strlen( $val ) . '/' . strlen( $newval ) ) ;
				## Verbose_Print( 'Original/Updated lengths: ' . strlen( $val ) . '/' . strlen( $newval ) . "<br />" ) ;
				$updatequery = "UPDATE $table_name SET $updatefield = ? WHERE ID = ?";
				$printablestring = preg_replace( array( '/\?/', '/\?/' ), array( $newval, $rowid ), $updatequery, 1 ) ;
				Debug_Print( $printablestring );
				$updatestatement = $mysqli->prepare( $updatequery );
				if ( $mysqli->error ) {
					die( "ERROR - prepare: " . $mysqli->error );
				}
				$updatestatement->bind_param( "si", $newval, $rowid );
				if ( $mysqli->error ) {
					die( "ERROR - bind_param: " . $mysqli->error );
				}
				if ( $GLOBALS["DOUPDATES"] ) {
					Debug_Print( "UPDATING..." );
					$updatestatement->execute();
					if ( $mysqli->error ) {
						echo "<br /><strong>ERROR - execute: " . $mysqli->error . "<br />\n";
					}
					$affectedrows = $updatestatement->affected_rows;
					$fieldsupdated+=$affectedrows;
					if ( $affectedrows > 1 ) {
						echo "<p><strong>NOTICE: more than 1 rows ($affectedrows) affected by this UPDATE</strong></p>\n";
					}
				} else {
					$affectedrows = 0;
				}
				echo "<p>$affectedrows records updated by $printablestring</p>\n"; 
				$updatestatement->free_result();
				if ( $mysqli->error ) {
					echo "ERROR - free_result: " . $mysqli->error ;
				}
			}
		}
		if ( $fieldsselected ) {
			$totalfieldsselected+=$fieldsselected;
		}
		if ( $fieldsupdated ) {
			$totalfieldsupdated+=$fieldsupdated;
		}
		if ( isset( $affectedrows ) && $affectedrows > 0 ) {
			$rowsupdated+=$affectedrows;
		}
	}
	$result->free();
	if ( $mysqli->error ) {
		echo "ERROR - free: " . $mysqli->error ;
	}
	if ( $GLOBALS["DOUPDATES"] ) {
		echo "<br />";
		echo "<strong>";
		echo "<p>Update complete.</p>\n";
		echo "</strong>\n";
	} else {
	}
	echo "<br />";
	echo "<table border=\"0\" style=\"margin-left: 0em;\">";
	echo "<tr><td align=\"right\">$totalfieldsselected</td><td>fields selected.</td</tr>\n";
	echo "<tr><td align=\"right\">$totalfieldsupdated</td><td>fields updated.</td</tr>\n";
	echo "<tr><td align=\"right\">$rowsselected</td><td>records selected.</td</tr>\n";
	echo "<tr><td align=\"right\">$rowsupdated</td><td>records updated.</td</tr>\n";
	echo "</table>";
}

?>
