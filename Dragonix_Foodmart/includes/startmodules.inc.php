<?php
	include ("./includes/readcookie.inc.php");      // read the cookie
	include ("./includes/settings.inc.php");        // database settings
	include ("./includes/connectdb.inc.php");       // connect to db
	include ("./includes/subs.inc.php");            // general functions
	include ("./includes/readvals.inc.php");        // get and post values
	include ("./includes/readsettings.inc.php");    // read shop settigns
	include( "./includes/setfolders.inc.php");      // set appropriate folders
	include ("./includes/initlang.inc.php");        // init the language
    include ("./".$lang_file);                         // read the language
?>