<?php if ($index_refer <> 1) { exit(); } ?>
<?php
           echo "<h1>".$txt['menu14']."</h1>\n"; 
           echo "<ul class=\"navbarVert navbarVertRight\">\n";
           echo "<li"; if ($page == "search") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=search\">" . $txt['menu4'] . "</a></li>\n";
           //if ($new_page == 1) { echo "<li"; if ($page == "browse" && $action=="shownew") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=browse&action=shownew\">" . $txt['menu15'] . "</a></li>\n"; }
           if ($conditions_page == 1) { echo "<li"; if ($page == "conditions" && $action == "show") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=conditions&action=show\">" . $txt['menu5'] . "</a></li>\n"; }
           
           if ($shipping_page == 1) { echo "<li"; if ($page == "info" && $action== "shipping") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=info&action=shipping\">" . $txt['menu6'] . "</a></li>\n"; }
           if ($guarantee_page == 1) { echo "<li"; if ($page == "info" && $action== "guarantee") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=info&action=guarantee\">" . $txt['menu7'] . "</a></li>\n"; }
           if ($aboutus_page == 1) { echo "<li"; if ($page == "info" && $action== "aboutus") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=info&action=aboutus\">" . $txt['menu18'] . "</a></li>\n"; }
           if (!isAdmin())        {   echo "<li"; if ($page == "contact") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=contact\">" . $txt['menu8'] . "</a></li>\n"; }
           echo "</ul>\n";
?>