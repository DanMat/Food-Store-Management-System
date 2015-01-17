<?php
    // connect to db
	if  ($dblocation == "" || $dbuser == "" ) { echo "<h1>Please run <a href=\"install.php\">the installation</a> first</h1>"; exit; }
    $db = @mysql_connect($dblocation,$dbuser,$dbpass) or die("<h1>Could not connect to the database. Please check your settings</h1>");
 	@mysql_select_db($dbname,$db) or die("<h1>Could not connect to the database. Please check your settings</h1>");
?>