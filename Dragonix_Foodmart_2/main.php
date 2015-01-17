<?php if ($index_refer <> 1) { exit(); } ?>
<?php
      // if the shop is disabled, the admin can still do everything. let's make sure he/she does not forget it's disabled
      if ($shop_disabled == 1 && IsAdmin() == true) {
	      PutWindow($gfx_dir, $shop_disabled_title,"<font color=red><strong>".$txt['general8']."</strong></font>","warning.gif", "50");
          echo "<br /><br />";
      }
?>

    <h1>
	<?php
		echo $txt['main1']." "; PrintUsername($txt['header3']);
		if (IsAdmin() == true) { echo "[<a href=\"?page=adminedit&filename=main.txt&root=0\">".$txt['browse7']."</a>]"; }
 	?>
    </h1>
    <div class="datatable">
     <?php
      $fp = fopen($main_file, "rb") or die("Couldn't open ".$main_file.". Make sure it exists and is readable.");
      $main = fread($fp, filesize($main_file));
      fclose($fp);
      echo "<p>".nl2br($main)."</p>";
     ?>
    </div>
  <br />

    <?php
         // Are there any special offers (frontpage=1 in product details)?
		 $prods_per_row = 3;
		 $row_count = 0;
         $f_query = "SELECT * FROM `".$dbtablesprefix."product` WHERE `FRONTPAGE` = '1'";
         $f_sql = mysql_query($f_query) or die(mysql_error());
         if (mysql_num_rows($f_sql) != 0) {
			if (mysql_num_rows($f_sql) < $prods_per_row) { $prods_per_row = mysql_num_rows($f_sql); }
	        echo "<div style=\"text-align:center;\">";
			echo "<h2>".$txt['main2']."</h2>";
	        echo "<br />";
			echo '<table width="100%" class="borderless">';

            while ($f_row = mysql_fetch_row($f_sql)) {
				  $row_count++;
                  include ("frontpage.php");
				  if ($row_count == $prods_per_row) { $row_count = 0; }
            }
			echo "</table></div>";
         }
    ?>