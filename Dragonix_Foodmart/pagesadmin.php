<?php if ($index_refer <> 1) { exit(); } ?>
<?php
 // admin check
if (IsAdmin() == false) {
  PutWindow($gfx_dir, $txt['general12'], $txt['general2'], "warning.gif", "50");
}
else {
	
	if ($action == "rename" && !empty($_POST['newname'])) {
		if (rename($lang_dir."/".$lang."/".$_POST['oldname'].".fws", $lang_dir."/".$lang."/".$_POST['newname'].".fws")) {
			PutWindow($gfx_dir, $txt['general13'], $txt['pagesadmin6'], "notify.gif", "50");
		}
		else {
			PutWindow($gfx_dir, $txt['general12'], $txt['pagesadmin8'], "warning.gif", "50");
		}
	}

	if ($action == "delete" && !empty($_GET['filename'])) {
		if (unlink($lang_dir."/".$lang."/".$_GET['filename'].".fws")) {
			PutWindow($gfx_dir, $txt['general13'], $txt['pagesadmin7'], "notify.gif", "50");
		}
		else {
			PutWindow($gfx_dir, $txt['general12'], $txt['pagesadmin8'], "warning.gif", "50");
		}
	}
	
	if ($action == "add" && !empty($_POST['filename'])) {
        $handle = fopen ($lang_dir."/".$lang."/".$_POST['filename'].".fws", "a");
        fclose($handle);
        if (file_exists($lang_dir."/".$lang."/".$_POST['filename'].".fws")) {
			PutWindow($gfx_dir, $txt['general13'], $txt['pagesadmin9'], "notify.gif", "50");
		}
		else {
			PutWindow($gfx_dir, $txt['general12'], $txt['pagesadmin8'], "warning.gif", "50");
		}
	}
?>
          <table width="80%" class="datatable">
            <caption><?php echo $txt['pagesadmin1']." (".$lang.")"; ?></caption>
           <tr>
           <th><?php echo $txt['pagesadmin2']; ?></th>
           <th><?php echo $txt['pagesadmin3']; ?></th>
           </tr>
<?php               	
        echo "<tr><td colspan=\"2\"><form method=\"POST\" action=\"?page=pagesadmin\">\n<input type=\"hidden\" name=\"action\" value=\"add\">\n<input type=\"text\" name=\"filename\" value=\"\"> <input type=\"submit\" value=\"".$txt['pagesadmin5']."\">\n</td></tr></form>\n";
           
	    $pages = 0;
	    if ($dir = @opendir($lang_dir."/".$lang)) {
	        while (($file = readdir($dir)) !== false) {
	               if ($file != "." && $file != ".." && $file != "index.php") {
	                   $filename = explode(".",$file);
	                   if ($filename[1] == "fws") {
					      $hidden = "";
						  if (is_integer(strpos($filename[0],"~"))) { $hidden = " (".$txt['pagesadmin11'].")"; }
	                      echo "<tr><td><form method=\"POST\" action=\"?page=pagesadmin\">\n<input type=\"hidden\" name=\"action\" value=\"rename\">\n<input type=\"hidden\" name=\"oldname\" value=\"".$filename[0]."\">\n<input type=\"text\" name=\"newname\" value=\"".$filename[0]."\"> <input type=\"submit\" value=\"".$txt['pagesadmin4']."\">\n</td>";
	                      echo "<td><a href=\"?page=adminedit&filename=".$filename[0].".fws&root=0\"><img src=\"".$gfx_dir."/pagesadmin_edit.png\" alt=\"edit\" /></a> <a href=\"?page=pagesadmin&action=delete&filename=".$filename[0]."\"><img src=\"".$gfx_dir."/pagesadmin_delete.png\" alt=\"delete\" /></a>".$hidden."</td></tr></form>\n";
	                      $pages += 1;
	                  }
	               }
	        }  
	        closedir($dir);
        }
        if ($pages == 0) { echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>"; }
        echo "</table>";	
}         

?>