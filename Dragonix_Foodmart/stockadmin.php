<?php
// admin check
if (IsAdmin() == false) {
  PutWindow($gfx_dir, $txt['general12'], $txt['general2'], $gfx_dir."/warning.gif", "50");
}
else {
	if (!empty($_POST['minimal_stock'])) {
	   $stock_warning_level = $_POST['minimal_stock'];
	}
?>
    <div align="center">
	    <table border="0">
		 <tr><td align="left">
	         <form method="POST" action="index.php?page=stockadmin">
               <strong><?php echo $txt['productadmin12']; ?> <</strong><br />
		       <input type="text" name="minimal_stock" value="<?php echo $stock_warning_level; ?>" size="5">
			   <input type="submit" value="<?php echo $txt['stockadmin2']; ?>">
		     </form>
		 </td></tr>
		</table>
	</div>	
<?php		
    $query ="SELECT * FROM ".$dbtablesprefix."product WHERE STOCK < ". $stock_warning_level . " ORDER BY STOCK ASC"; 
    $sql = mysql_query($query) or die(mysql_error());
?>
    <table width="100%" class="datatable">
      <caption><?php echo $txt['stockadmin3']; ?></caption>
     <tr> 
      <th><?php echo $txt['productadmin9']; ?></th>
      <th><?php echo $txt['productadmin12']; ?></th>
     </tr>

    <?php
    if (mysql_num_rows($sql) == 0) {
        echo "<tr><td colspan=\"5\">" . $txt['browse5'] ."</td></tr>";
    }
    else {
        while ($row = mysql_fetch_row($sql)) {
	       echo "<tr>";
		   echo "<td>". $row[1] . " <a href=\"?page=productadmin&action=edit_product&pcat=".$row[2]."&prodid=".$row[0]."\">".$txt['browse7']."</a></td>";
"</td>";   echo "<td>". $row[5] . "</td>";
		   echo "</tr>";
	    }
	}
    echo "</table>";
}	
?>