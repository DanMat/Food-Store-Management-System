<?php if ($index_refer <> 1) { exit(); } ?>
<?php
// admin check
if (IsAdmin() == false) {
  PutWindow($gfx_dir, $txt['general12'], $txt['general2'], "warning.gif", "50");
}
else {  
    $mailinglist = "";
    $query = "SELECT * FROM `".$dbtablesprefix."customer` WHERE `NEWSLETTER` = '1'"; 
    $sql = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_row($sql)) {
	       $mailinglist = $row[12].", ".$mailinglist;
	}
?>
  <table class="borderless" width="100%"><caption><?php echo $txt['mailinglist1']; ?></caption>
   <tr><td>
    <textarea rows="30" cols="65" readonly><?php echo $mailinglist ?></textarea><br />
   </td></tr>
  </table>   
<?php	
}    
?>