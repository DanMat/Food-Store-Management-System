<?php if ($index_refer <> 1) { exit(); } ?>
<?php 
  // Standard shop pages
  if ($action == "guarantee" || $action == "shipping" || $action == "aboutus") { 
	  $info_file = $lang_dir."/".$lang."/".$action.".txt";
	  $info_pic  = $action.".gif";
	  if ($action == "guarantee") { $header = $txt['guarantee1']; }
	  if ($action == "shipping") { $header = $txt['send1']; }
	  if ($action == "aboutus") { $header = $txt['menu18']; }
	  
	  // open the text file
	  $fp = fopen($info_file, "rb") or die("Sorry. This page encountered an error opening the ".$action." page.");
	  if (filesize($info_file) > 0) { $info = fread($fp, filesize($info_file)); }
	  fclose($fp);
	
	  PutWindow($gfx_dir, $header, nl2br($info), $info_pic, "100");
	  // make an edit link
	  if (IsAdmin() == true) { echo "<h4><a href=\"?page=adminedit&filename=".$action.".txt&root=0\">".$txt['browse7']."</a></h4>"; }  
  }
  // Custom shop pages
  else {
	  $info_file = $lang_dir."/".$lang."/".$action.".fws";
	  
	  // open the text file
	  $fp = fopen($info_file, "rb") or die("Sorry. This page encountered an error opening the ".$action." page.");
	  if (filesize($info_file) > 0) { $info = fread($fp, filesize($info_file)); }
	  fclose($fp);
	  
	  if (substr($action, 0, 1) == "~") { $action = substr($action, 1, strlen($action)-1); } // strip the hidden symbol
	  
	  PutSingleWindow($action, nl2br($info), "100");
	  // make an edit link
	  if (IsAdmin() == true) { echo "<h4><a href=\"?page=pagesadmin\">".$txt['browse7']."</a></h4>"; }  
  }
	  
  
  
  
?>