<?php if ($index_refer <> 1) { exit(); } ?>
<?php
    if (file_exists('install.php')) { 
	    PutWindow($gfx_dir, $txt['header12'],$txt['header4'],"warning.gif", "50"); 
	}
    elseif ($shop_disabled == 1 && IsAdmin() == false && $page != "my") { 
	    PutWindow($gfx_dir, $shop_disabled_title,$shop_disabled_reason,"warning.gif", "50"); 
	}
	elseif (IsBanned() == true) { 
		PutWindow($gfx_dir, $txt['general12'],$txt['general10'],"warning.gif", "50"); 
	}
    else {
		    if (file_exists("$page.php")) {
		       include ("$page.php");
		    }
		    else {
		       PutWindow($gfx_dir, $txt['general12'], $txt['general9'], "warning.gif", "50");
		    }
	}
?>