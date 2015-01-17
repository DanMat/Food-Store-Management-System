<?php if ($index_refer <> 1) { exit(); } ?>
<?php include ("./includes/checklogin.inc.php"); ?>
<?php
    if (!empty($_GET['id'])) { $id=intval($_GET['id']); } 
if (IsAdmin() == false && $customerid != $id) {
    PutWindow($gfx_dir, $txt['general12'], $txt['general2'], "warning.gif", "50");
}
else {
    if ($id != 0) { $customerid = $id; } //omdat een admin het via orders?id=xx kan aanroepen

    // door alle orders van deze klant lopen..
    $query = sprintf("SELECT * FROM `".$dbtablesprefix."order` WHERE CUSTOMERID = %s ORDER BY ID DESC", quote_smart($customerid));
    $sql = mysql_query($query) or die(mysql_error());
?>

    <table width="100%" class="datatable">
      <caption><?php echo $txt['orders3']; ?></caption>
     <tr>
      <th><?php echo $txt['orders4']; ?></th>
      <th><?php echo $txt['orders5']; ?></th>
      <th><?php echo $txt['orders6']." (".$currency.")"; ?></th>
      <th><?php echo $txt['orders7']; ?></th>
      <th><?php echo $txt['orders8']; ?></th>
     </tr>

    <?php
       if (mysql_num_rows($sql) == 0) {
	      echo "<tr><td colspan=5>" . $txt['orders9'] . "</td></tr>";
	      }
       else {
	       
         while ($row = mysql_fetch_row($sql)) {
	       // lets first check if the order still has a local file in the Orders folder
	       if (file_exists($orders_dir ."/". $row[7] . ".php")) {
	          echo "<tr><td nowrap><a href=\"index.php?page=readorder&orderid=".$row[0]."\">".$row[7]."</a><br />";
	           // if a pdf was created, lets show it here
	           if ($row[10] != "" && !is_null($row[10]) && file_exists($orders_dir ."/". $row[10])) { 
		           echo "<a href=\"".$orders_dir ."/". $row[10]."\"><img src=\"".$gfx_dir."/pdf.gif\" alt=\"PDF\"></a> "; 
		       }
			  echo "</td>";
	          echo "<td>";
	          // find out shipping method
			  $ship_query = "SELECT * FROM `".$dbtablesprefix."shipping` WHERE `id` = ".$row[3];
	          $ship_sql = mysql_query($ship_query) or die(mysql_error());
              while ($ship_row = mysql_fetch_row($ship_sql)) { echo $ship_row[1]; }
              echo "<br />";
	          // find out shipping method
			  $pay_query = "SELECT * FROM `".$dbtablesprefix."payment` WHERE `id` = ".$row[4];
	          $pay_sql = mysql_query($pay_query) or die(mysql_error());
              while ($pay_row = mysql_fetch_row($pay_sql)) { echo $pay_row[1]; }
              echo "</td>";
	          echo "<td><div style=\"text-align:right;\">".myNumberFormat($row[6], $number_format) . "</div></td>";
	          echo "<td>".$row[1]."</td>";
	          echo "<td>";
	           if ($row[2] == "1") { echo "<img src=\"".$gfx_dir."/bullit_blue.gif\" alt=\"".$txt['db_status1']."\" />&nbsp;".$txt['db_status1'].""; }
	           if ($row[2] == "2") { echo "<img src=\"".$gfx_dir."/bullit_red.gif\" alt=\"".$txt['db_status2']."\" />&nbsp;".$txt['db_status2'].""; }
	           if ($row[2] == "3") { echo "<img src=\"".$gfx_dir."/bullit_red.gif\" alt=\"".$txt['db_status3']."\" />&nbsp;".$txt['db_status3'].""; }
	           if ($row[2] == "4") { echo "<img src=\"".$gfx_dir."/bullit_orange.gif\" alt=\"".$txt['db_status4']."\" />&nbsp;".$txt['db_status4'].""; }
	           if ($row[2] == "5") { echo "<img src=\"".$gfx_dir."/bullit_green.gif\" alt=\"".$txt['db_status5']."\" />&nbsp;".$txt['db_status5'].""; }
	           if ($row[2] == "6") { echo "<img src=\"".$gfx_dir."/bullit_green.gif\" alt=\"".$txt['db_status6']."\" />&nbsp;".$txt['db_status6'].""; }
	           if ($row[2] == "7") { echo "<img src=\"".$gfx_dir."/bullit_green.gif\" alt=\"".$txt['db_status7']."\" />&nbsp;".$txt['db_status7'].""; }
	          echo "</td></tr>";
          }
         }
       }

?>
    </table>
   <br /><br />

          <table width="50%" class="datatable">
            <caption><?php echo $txt['db_status10']; ?></caption>
              <tr>
                  <td><?php echo "<img src=\"".$gfx_dir."/bullit_blue.gif\" alt=\"".$txt['db_status1']."\" />"; ?></td>
                  <td><?php echo $txt['db_status11']; ?></td>
              </tr>
              <tr>
                  <td><?php echo "<img src=\"".$gfx_dir."/bullit_red.gif\" alt=\"".$txt['db_status2']."\" />"; ?></td>
                  <td><?php echo $txt['db_status12']; ?></td>
              </tr>
              <tr>
                  <td><?php echo "<img src=\"".$gfx_dir."/bullit_red.gif\" alt=\"".$txt['db_status3']."\" />"; ?></td>
                  <td><?php echo $txt['db_status13']; ?></td>
              </tr>
              <tr>
                  <td><?php echo "<img src=\"".$gfx_dir."/bullit_orange.gif\" alt=\"".$txt['db_status4']."\" />"; ?></td>
                  <td><?php echo $txt['db_status14']; ?></td>
              </tr>
              <tr>
                  <td><?php echo "<img src=\"".$gfx_dir."/bullit_green.gif\" alt=\"".$txt['db_status5']."\" />"; ?></td>
                  <td><?php echo $txt['db_status15']; ?></td>
              </tr>
              <tr>
                  <td><?php echo "<img src=\"".$gfx_dir."/bullit_green.gif\" alt=\"".$txt['db_status6']."\" />"; ?></td>
                  <td><?php echo $txt['db_status16']; ?></td>
              </tr>
              <tr>
                  <td><?php echo "<img src=\"".$gfx_dir."/bullit_green.gif\" alt=\"".$txt['db_status7']."\" />"; ?></td>
                  <td><?php echo $txt['db_status17']; ?></td>
              </tr>
       </table>
<?php } ?>       