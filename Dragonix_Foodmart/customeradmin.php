<?php if ($index_refer <> 1) { exit(); } ?>
<?php
// admin check
if (IsAdmin() == false) {
  PutWindow($gfx_dir, $txt['general12'], $txt['general2'], "warning.gif", "50");
}
else {  
   // make member of admin group   
   if ($action == "makeadmin") {
      $id = $_GET['id']; // get user id
	  $query = "UPDATE `".$dbtablesprefix."customer` SET `GROUP` = 'ADMIN' WHERE `ID` = ".$id; // make member of admin group
	  $sql = mysql_query($query) or die(mysql_error());
	  PutWindow($gfx_dir, $txt['general13'] , $txt['customeradmin7'], "notify.gif", "50");
	  $action = "showcustomers"; // now show the group
   }
      
   // make member of customer group   
   if ($action == "makecust") {
      $id = $_GET['id']; // get user id
      if ($id == 1) {
	     PutWindow($gfx_dir, $txt['general12'] , $txt['customer32'], "warning.gif", "50"); // dont make the main admin a customer
      }
	  else {
		  $query = "UPDATE `".$dbtablesprefix."customer` SET `GROUP` = 'CUSTOMER' WHERE `ID` = ".$id; // make member of admin group
		  $sql = mysql_query($query) or die(mysql_error());
		  PutWindow($gfx_dir, $txt['general13'] , $txt['customeradmin8'], "notify.gif", "50");
	  }
      $action = "showadmins"; // now show the group
   }
	     
    // show admins or customers?
   if ($action == "showadmins") {
	   $selectSQL = "WHERE `GROUP` = 'ADMIN'";
	   $group = $txt['admin29'];
	   $link1 = "<a href=\"?page=customeradmin&action=makecust&id=";
	   $link2 = "\"><img src=\"".$gfx_dir."/customeradmin_makecust.png\" alt=\"Make customer\" /></a>"; 
   } 
   
   if ($action == "showcustomers") {
	   $selectSQL = "WHERE `GROUP` = 'CUSTOMER'";
	   $group = $txt['admin3'];
	   $link1 = "<a href=\"?page=customeradmin&action=makeadmin&id=";
	   $link2 = "\"><img src=\"".$gfx_dir."/customeradmin_makeadmin.png\" alt=\"Make administrator\" /></a>";
   } 
   // search form
	 if (!empty($_POST['lastname'])) {
		 $lastname=$_POST['lastname'];
		 $selectSQL = $selectSQL." AND `LASTNAME` LIKE '%".$lastname."%'";
	 }


	 
    // search box	 
?>   
    <div align="center">
	    <table border="0">
		 <tr><td align="left">
             <form method="POST" action="index.php?page=customeradmin">		 
		       <strong><?php echo $txt['customeradmin1']; ?></strong><br />
		       <input type="text" name="lastname" value="<?php echo $lastname; ?>" size="15">
			   <input type="hidden" name="action" value="<?php echo $action; ?>">
			   <input type="submit" value="<?php echo $txt['customeradmin10']; ?>">
		     </form>
		 </td></tr>
		</table>
	</div>	
<?php
   
    // customers per page
    $customers_per_page = 20; // change this if you want
    if ($customers_per_page > 0) {
       if (!empty($_GET['num_page'])) {
          $num_page = $_GET['num_page'];
       }
       else { $num_page = 1; }
       $start_record = ($num_page -1) * $customers_per_page;
       $limit    = " LIMIT $start_record, $customers_per_page"; 
    }
    else { $limit = ""; }   
    $query = "SELECT * FROM `".$dbtablesprefix."customer` ".$selectSQL." ORDER BY `LASTNAME` ASC"; // zonder limit for the total count
    $sql = mysql_query($query) or die(mysql_error());
    $num_customers = mysql_num_rows($sql);
    $sql = mysql_query($query.$limit) or die(mysql_error()); // with limit for the page display
    ?>
    
    <table width="100%" class="datatable">
      <caption><?php echo $txt['customeradmin6']." (".$num_customers.")"; ?></caption>
     <tr> 
      <th><?php echo $txt['customeradmin1']; ?></th>
      <th><?php echo $txt['customeradmin2']; ?></th>
      <th><?php echo $txt['customeradmin4']; ?></th>
      <th colspan="2"><?php echo $txt['customeradmin5']; ?></th>
     </tr>

    <?php
    if ($num_customers == 0) {
	    echo "<tr><td colspan=\"5\">".$txt['customeradmin9']."</td></tr></table>";
    }
    else {
	    $color = $tb_pricelist_color2;
	    while ($row = mysql_fetch_row($sql)) {
		   echo "<tr>";
		   echo "<td>".$row[3]."</td>";
		   echo "<td>".$row[16]."</td>";
		   echo "<td>".$row[11]."</td>";
		   echo "<td>";
		   echo "<a href=\"?page=orders&id=".$row[0]."\"><img src=\"".$gfx_dir."/customeradmin_orders.png\" alt=\"Orders\" /></a><sup>(" . CountOrders($row[0]). ")</sup>&nbsp;";
		   echo "<a href=\"?page=cart&action=show&id=".$row[0]."\"><img src=\"".$gfx_dir."/customeradmin_cart.png\" alt=\"Cart\"></a><sup>(". CountCart($row[0]) . ")</sup>&nbsp;";
		   echo "</td><td>";
		   echo "<a href=\"javascript:alert('LOGIN: ".stripslashes($row[1])."')\"><img src=\"".$gfx_dir."/customeradmin_login.png\" alt=\"Login\"></a>&nbsp;";
		   echo "<a href=\"mailto:".$row[12]."\"><img src=\"".$gfx_dir."/customeradmin_mail.png\" alt=\"".$row[12]."\"></a>&nbsp;";
		   echo "<a href=\"index.php?page=customer&action=show&customerid=".$row[0]."\"><img src=\"".$gfx_dir."/customeradmin_edit.png\" alt=\"Edit\" /></a>&nbsp;";
		   if ($row[0] != 1) { echo $link1.$row[0].$link2; } // you can not UN-ADMIN the main admin
		   if ($row[13] != "ADMIN") {  
			   // you cannot remove a shop admin even if you wanted it
			   echo "<a href=\"?page=customer&action=delete&customerid=".$row[0]."\"><img src=\"".$gfx_dir."/customeradmin_delete.png\" alt=\"Delete\" /></a>&nbsp;";
		   }
		   echo "</td></tr>";
	    }
	    echo "</table>";
	    
		  // page code
		  if ($customers_per_page > 0 && $num_customers > $customers_per_page) {
			  
			  $page_counter = 0;
			  $num_pages = 0;
			  $rest_customers = $num_customers;
			  
			  echo "<br /><h4>".$txt['browse11'].": ";
			  
			  for($i = 0; $i < $num_customers; $i++) { 
				  $page_counter++;
				  if ($page_counter == $customers_per_page) {
					  $num_pages++;
					  $page_counter = 0;
					  $rest_customers = $rest_customers - $customers_per_page;
					  if ($num_pages == $num_page) {
						  echo "<b>[$num_pages]</b>";
					  }
					  else { echo "<a href=\"index.php?page=customeradmin&action=$action&num_page=$num_pages\">[$num_pages]</a>"; }
					  echo " ";
				  }
		      }
		      // the rest (if any)
		      if ($rest_customers > 0) {
				  $num_pages++;
				  if ($num_pages == $num_page) {
					  echo "<b>[$num_pages]</b>";
				  }
				  else { echo "<a href=\"index.php?page=customeradmin&action=$action&num_page=$num_pages\">[$num_pages]</a>"; }
			  }
			      
		      echo "</h4>"; 
		  }   
      }
}    
?>