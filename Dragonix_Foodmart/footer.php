<?php if ($index_refer <> 1) { exit(); } ?>
<?php
      //website start year and current year
      $current_year = date("Y");

      if ($start_year == $current_year) { 
	      $footer_year = $current_year; 
	  }
	  else { $footer_year = $start_year."-".$current_year; }
    
	  echo $shopname ?> | <?php if (!is_null($page_footer)) { echo $page_footer." | "; } ?> &copy;<?php echo $footer_year;

	  echo "<br /><br />";
    ?>
