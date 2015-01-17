<?php
 	// get language from cookie
    if (isset($_COOKIE['cookie_lang'])) { $lang = $_COOKIE['cookie_lang']; }
 	// if the lang.txt file from the cookie doesnt exist (anymore), then switch to the default language
    if (!isset($lang)) { $lang = $default_lang; }    
    if (!file_exists($lang_dir."/".$lang."/lang.txt")) { $lang = $default_lang;}
    $lang_file = $lang_dir."/".$lang."/lang.txt";
    $main_file = $lang_dir."/".$lang."/main.txt";
?>