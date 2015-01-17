<?php if ($index_refer <> 1) { exit(); } ?>
<?php if ($action == "checkout") { include ("./includes/checklogin.inc.php"); } ?>
<?php
  if (LoggedIn() == False && $action == "checkout") {
	  // do nothing
  }
  else {
	  $count = CountCart($customerid);
	  if ($count == 0 && $action == "checkout") {
		      PutWindow($gfx_dir, $txt['cart1'], $txt['cart2'], "carticon.gif", "50");
	  }
	  else {
		  if ($action == "checkout" && LoggedIn() == True) { 
		  	  echo "<h4><img src=\"".$gfx_dir."/arrow.gif\" alt=\"1\">&nbsp;<img src=\"".$gfx_dir."/1.gif\" alt=\"1\">&nbsp;<img src=\"".$gfx_dir."/2_.gif\" alt=\"2\">&nbsp;<img src=\"".$gfx_dir."/3_.gif\" alt=\"3\">&nbsp;<img src=\"".$gfx_dir."/4_.gif\" alt=\"4\">&nbsp;<img src=\"".$gfx_dir."/5_.gif\" alt=\"5\"></h4><br /><br />"; 
			  }
	  	  
		  // read the conditions file
		  $conditions_file = $lang_dir."/".$lang."/conditions.txt";
		  $fp = fopen($conditions_file, "rb") or die("Couldn't open ".$conditions_file.". Make sure it exists and is readable.");
		  if (filesize($conditions_file) > 0) { $conditions = fread($fp, filesize($conditions_file)); }
		  fclose($fp);
?>
  <table class="borderless" width="100%"><caption><?php echo $txt['conditions5']; ?></caption>
   <tr><td>
		 <form method="post" action="index.php?page=shipping">
		   <textarea rows="30" cols="65" readonly><?php echo $conditions ?></textarea><br />
<?php
	  
	  
	  if ($count != 0 && $action == "checkout" && $ordering_enabled == 1) {
		  echo "<input type=\"submit\" value=\"" . $txt['conditions1'] . "\"><br />";
	      }
?>
	 </form>
  </td></tr></table>	 
<?php
	  if (IsAdmin() == true && $action == "show") { echo "<h4><a href=\"?page=adminedit&filename=conditions.txt&root=0&wysiwyg=0\">".$txt['browse7']."</a></h4>"; }
      }    
  }
?> 