<?php if ($index_refer <> 1) { exit(); } ?>
<?php
           echo "<h1>".$txt['menu15']."</h1>\n"; 
           // if the category is send, then use that to find out the group
           if ($cat != 0 && $group == 0) { $group = TheGroup($cat); }
           
           $query = "SELECT * FROM `".$dbtablesprefix."group` ORDER BY `NAME` ASC";
	       $sql = mysql_query($query) or die(mysql_error());

           if (mysql_num_rows($sql) == 0) {
	          echo $txt['menu17']; // no groups found
           }
	       else {
                echo "<ul class=\"navbarVert navbarVertLeft\">\n";
                while ($row = mysql_fetch_row($sql)) {
	                  // lets find out how many categories there are in the group
	                  $query_cat = sprintf("SELECT * FROM `".$dbtablesprefix."category` where `GROUPID`=%s ORDER BY `DESC` ASC", quote_smart($row[0]));
                      $sql_cat = mysql_query($query_cat) or die(mysql_error());
                      $row_cat = mysql_fetch_row($sql_cat);
	                  $ahref = "";

                        // if there is only 1 category in the group, then jump to the browse list instandly
                        if (mysql_num_rows($sql_cat) == 1) {
                            $ahref = "\"index.php?page=browse&action=list&orderby=DESCRIPTION&group=".$row[0]."&cat=".$row_cat[0]."\"";
                        }
                        // if there are more categories in the group, then show the category list
                        if (mysql_num_rows($sql_cat) > 1) {
                            $ahref = "\"index.php?page=categories&group=".$row[0]."\"";
                        }
                        // now show the menu link, if ahref is not empty
                        if ($ahref != "") {
		                    if ($group != $row[0]) { 
				                echo "<li><a href=".$ahref.">" . $row[1] . "</a></li>\n"; 
				            }
		                    else {
			                    //select/highlight
				                echo "<li id=\"active\"><a id=\"current\" href=".$ahref.">" . $row[1] . "</a></li>\n"; 
		                    }
	                    }
                      
                }
                echo "</ul>\n";
           }
?>