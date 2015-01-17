<?php if ($index_refer <> 1) { exit(); } ?>
<?php
// admin check
if (IsAdmin() == false) {
  PutWindow($gfx_dir, $txt['general12'], $txt['general2'], "warning.gif", "50");
}
else {    
    // errors per page
    $errors_per_page = 20; // change this if you want
    
    if ($errors_per_page > 0) {
       if (!empty($_GET['num_page'])) {
          $num_page = $_GET['num_page'];
       }
       else { $num_page = 1; }
       $start_record = ($num_page -1) * $errors_per_page;
       $limit    = " LIMIT $start_record, $errors_per_page"; 
    }
    else { $limit = ""; }   
    
    if (isset($action)) {
	    if ($action == "clear") {
		    $query = "DELETE FROM `".$dbtablesprefix."errorlog`";
		    $sql = mysql_query($query) or die(mysql_error());
		    PutWindow($gfx_dir, $txt['general13'], $txt['errorlogadmin9'], "notify.gif", "50");
		}
	}
	    
    $query = "SELECT * FROM `".$dbtablesprefix."errorlog` ORDER BY `id` DESC";
    $sql = mysql_query($query) or die(mysql_error());
    $num_errors = mysql_num_rows($sql);
    $sql = mysql_query($query.$limit) or die(mysql_error());
    ?>


    <table width="100%" class="datatable">
      <caption><?php echo $txt['errorlogadmin1']; ?></caption>
     <tr> 
      <th><?php echo $txt['errorlogadmin2']; ?></th>
      <th><?php echo $txt['errorlogadmin3']; ?></th>
      <th><?php echo $txt['errorlogadmin4']; ?></th>
      <th><?php echo $txt['errorlogadmin5']; ?></th>
      <th><?php echo $txt['errorlogadmin6']; ?></th>
     </tr>

    <?php
    if (mysql_num_rows($sql) == 0) {
        echo "<tr><td colspan=\"5\">".$txt['errorlogadmin7']."</td></tr>";
    }
    else {
        while ($row = mysql_fetch_row($sql)) {
              echo "<tr><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td></tr>\n";
        }
    } 
    echo "</table>";
    
  // page code
  if ($errors_per_page > 0 && $num_errors > $errors_per_page) {
	  
	  $page_counter = 0;
	  $num_pages = 0;
	  $rest_errors = $num_errors;
	  
	  echo "<br /><h4>".$txt['browse11'].": ";
	  
	  for($i = 0; $i < $num_errors; $i++) { 
		  $page_counter++;
		  if ($page_counter == $errors_per_page) {
			  $num_pages++;
			  $page_counter = 0;
			  $rest_errors = $rest_errors - $errors_per_page;
			  if ($num_pages == $num_page) {
				  echo "<b>[$num_pages]</b>";
			  }
			  else { echo "<a href=\"index.php?page=errorlogadmin&action=$action&num_page=$num_pages\">[$num_pages]</a>"; }
			  echo " ";
		  }
      }
      // the rest (if any)
      if ($rest_errors > 0) {
		  $num_pages++;
		  if ($num_pages == $num_page) {
			  echo "<b>[$num_pages]</b>";
		  }
		  else { echo "<a href=\"index.php?page=errorlogadmin&action=$action&num_page=$num_pages\">[$num_pages]</a>"; }
	  }
	      
      echo "</h4>"; 
  }           
?>
    <br />
    <br />
    <h4><a href="?page=errorlogadmin&action=clear"><?php echo $txt['errorlogadmin8']; ?></a></h4>
<?php } ?>    