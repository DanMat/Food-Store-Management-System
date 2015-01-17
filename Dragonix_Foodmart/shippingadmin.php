<?php if ($index_refer <> 1) { exit(); } ?>
<?php
 // admin check
if (IsAdmin() == false) {
  PutWindow($gfx_dir, $txt['general12'], $txt['general2'], "warning.gif", "50");
}
else {
     // ok, let's do the updating/deleting/moving here
     
     // add a shipping method
     if ($action == "add_shipping") {
	      if (!empty($_POST['description'])) {
	          $description = $_POST['description'];
          }
          $rate = 0;
	      if (!empty($_POST['rate'])) {
	          $rate=$_POST['rate'];
          }
	      $country = CheckBox($_POST['country']);
	      
          if ($description != "") {
	          $query="INSERT INTO `".$dbtablesprefix."shipping` (`description`, `country`) VALUES ('".$description."', ".$country.")";
	          $sql = mysql_query($query) or die(mysql_error());
	          PutWindow($gfx_dir, $txt['general13'], $txt['shippingadmin1'], "notify.gif", "50");
          }
     }
    // delete shipping costs/weight rule
    if ($action == "delete_weight") {
	     if (!empty($_GET['wid'])) {
	          $wid=$_GET['wid'];
          }
         $query="DELETE FROM `".$dbtablesprefix."shipping_weight` WHERE `ID`=".$wid;
         $sql = mysql_query($query) or die(mysql_error());
	     PutWindow($gfx_dir, $txt['general13'], $txt['shippingadmin21'], "notify.gif", "50");
		 $action = "show_shipping";
	}
		 
     // edit a shipping method
     if ($action == "update_shipping") {
	      if (!empty($_POST['sid'])) {
	          $sid=$_POST['sid'];
          }
	      if (!empty($_POST['description'])) {
	          $description=$_POST['description'];
          }
          $country = CheckBox($_POST['country']);
          
          if ($description != "") {
	          // shipping data
	          $query="UPDATE `".$dbtablesprefix."shipping` SET `description`='".$description."', `country`=".$country." WHERE `id`=".$sid;
	          $sql = mysql_query($query) or die(mysql_error());
	          
	          // payment data
              $query="SELECT * FROM `".$dbtablesprefix."payment`";
			  $sql = mysql_query($query) or die(mysql_error());
	                 	          
              if (mysql_num_rows($sql) <> 0) { 
	              while ($row = mysql_fetch_row($sql)) {
		                 $selected=CheckBox($_POST[$row[0]]);
	  		             if ($selected == 1) {
		                 	$query_shippay="SELECT * FROM `".$dbtablesprefix."shipping_payment` WHERE `shippingid`='".$sid."' AND paymentid='".$row[0]."'";
				         	$sql_shippay = mysql_query($query_shippay) or die(mysql_error());
				            // if it's not found then add it
				            if (mysql_num_rows($sql_shippay) == 0) { 
						        $query_add="INSERT INTO `".$dbtablesprefix."shipping_payment` (`shippingid`, `paymentid`) VALUES ('".$sid."', '".$row[0]."')";
						        $sql_add = mysql_query($query_add) or die(mysql_error());
				            }
		                 }
		                 else {
			                    // it's not selected, so lets remove the record that binds this shipping to this payment method
						        $query_del="DELETE FROM `".$dbtablesprefix."shipping_payment` WHERE `shippingid`='".$sid."' AND `paymentid`='".$row[0]."'";
						        $sql_del = mysql_query($query_del) or die(mysql_error());
		                 }
	              }
              }
			  
			  // add the weights and costs of it was submitted
			  if ((!empty($_POST['from'])) || (!empty($_POST['to'])) || (!empty($_POST['price']))) {
			      if (empty($_POST['from'])) { $from = 0; }
				  else { $from = $_POST['from'];}
			      if (empty($_POST['to'])) { $to = 0; }
				  else { $to = $_POST['to'];}
			      if (empty($_POST['price'])) { $price = 0; }
				  else { $price = $_POST['price'];}
			      $query_add = "INSERT INTO `".$dbtablesprefix."shipping_weight` (`SHIPPINGID`, `FROM`, `TO`, `PRICE`) VALUES ('".$sid."', '".$from."', '".$to."', '".$price."')";
				  $sql_add = mysql_query($query_add) or die(mysql_error());
			  }
              
	          PutWindow($gfx_dir, $txt['general13'], $txt['shippingadmin3'], "notify.gif", "50");
          }
		  $action = "show_shipping";
      }      

     // delete a shipping method
     if ($action == "delete_shipping") {
	     if (!empty($_GET['sid'])) {
	          $sid=$_GET['sid'];
          }
          // remove shipping method
          $query="DELETE FROM `".$dbtablesprefix."shipping` WHERE `id`=".$sid;
          $sql = mysql_query($query) or die(mysql_error());
          
          // remove links to payment methods
          $query_del="DELETE FROM `".$dbtablesprefix."shipping_payment` WHERE `shippingid`='".$sid."'";
          $sql_del = mysql_query($query_del) or die(mysql_error());
          
          // remove links to weight rules
          $query_del="DELETE FROM `".$dbtablesprefix."shipping_weight` WHERE `SHIPPINGID`='".$sid."'";
          $sql_del = mysql_query($query_del) or die(mysql_error());
          
          PutWindow($gfx_dir, $txt['general13'], $txt['shippingadmin2'], "notify.gif", "50");
      } 
      // show a shipping method for editing
      if ($action == "show_shipping") {
	     if (!empty($_GET['sid'])) {
	          $sid=$_GET['sid'];
          }
         $query="SELECT * FROM `".$dbtablesprefix."shipping` WHERE `id`=".$sid;
         $sql = mysql_query($query) or die(mysql_error());
         
         while ($row = mysql_fetch_row($sql)) {	   
	          if ($row[3] == 1) { PutWindow($gfx_dir, $txt['general13'], $txt['shippingadmin15'], "warning.gif", "50"); }   // part of system!
	          echo "<table width=\"100%\" class=\"datatable\">";
	          echo "<caption>".$txt['shippingadmin14']."</caption>";
	          echo "<form method=\"POST\" action=\"index.php?page=shippingadmin&action=update_shipping\">";
	          echo "<input name=\"sid\" type=\"hidden\" value=\"".$row[0]."\">";
	          echo "<tr><td>";
	          echo $txt['shippingadmin5']."<br />";
	          echo "<input name=\"description\" type=\"text\" value=\"".$row[1]."\" size=\"30\" maxlength=\"150\"><br /><br />";
	          echo "<input name=\"country\" type=\"checkbox\" "; if ($row[2] == 1) echo "checked"; echo "> ";
	          echo $txt['shippingadmin7']."<br />";
	          echo "</td><td>";
	          echo $txt['shippingadmin13']."<br />";
	          // for every payment method we add a checkbox
	          $query_pay="SELECT * FROM `".$dbtablesprefix."payment`";
	          $sql_pay = mysql_query($query_pay) or die(mysql_error());
	         
	          while ($row_pay = mysql_fetch_row($sql_pay)) {	      
			          $query_shippay="SELECT * FROM `".$dbtablesprefix."shipping_payment` WHERE `shippingid`='".$sid."' AND paymentid='".$row_pay[0]."'";
			          $sql_shippay = mysql_query($query_shippay) or die(mysql_error());
			         
			          if (mysql_num_rows($sql_shippay) <> 0) { 
				             $checked = "checked"; }
			          else { $checked = ""; }
    	              echo "<input name=\"".$row_pay[0]."\" type=\"checkbox\" ".$checked.">&nbsp;".$row_pay[1]."<br />";
		      }
		      echo "</td></tr>"; 
			  echo "<tr><td colspan=\"2\">";
			  echo $txt['shippingadmin6']."<br />";
			  echo $txt['shippingadmin18']."(".$weight_metric.") <input type=\"text\" name=\"from\" size=\"9\" maxlength=\"9\"> ";
			  echo $txt['shippingadmin19']."(".$weight_metric.") <input type=\"text\" name=\"to\" size=\"9\" maxlength=\"9\"> ";
			  echo $txt['shippingadmin20']."(".$currency_symbol.") <input type=\"text\" name=\"price\" size=\"9\" maxlength=\"9\">";
		      echo "</td></tr>"; 
			  echo "<tr><td colspan=\"2\">";
			  
	          $query_weight = "SELECT * FROM `".$dbtablesprefix."shipping_weight` WHERE `SHIPPINGID`='".$row[0]."' ORDER BY `FROM`";
	          $sql_weight = mysql_query($query_weight) or die(mysql_error());
	         
	          while ($row_weight = mysql_fetch_row($sql_weight)) {	      
				  echo $txt['shippingadmin18']."(".$weight_metric.") <input type=\"text\" size=\"9\" value=\"".$row_weight[2]."\" disabled> ";
				  echo $txt['shippingadmin19']."(".$weight_metric.") <input type=\"text\" size=\"9\" value=\"".$row_weight[3]."\" disabled> ";
				  echo $txt['shippingadmin20']."(".$currency_symbol.") <input type=\"text\" size=\"9\" value=\"".$row_weight[4]."\" disabled> [<a href=\"?page=shippingadmin&action=delete_weight&wid=".$row_weight[0]."&sid=".$row[0]."\">".$txt['shippingadmin9']."</a>]<br />";
			  }
			  
			  echo "</td></tr>"; 
		      echo "<tr class=\"altrow\"><td colspan=\"2\">";
	          echo "<h4><input type=\"submit\" value=\"".$txt['shippingadmin23']."\"></h4>";
	          echo "</td></tr>";
              echo "</form>";
              echo "</table>";
              echo "<br /><br />";
         }
      }
     
         echo "<table width=\"100%\" class=\"datatable\">";
         echo "  <caption>".$txt['shippingadmin4']."</caption>";
         echo "  <tr><th>".$txt['shippingadmin5']."</th><th>".$txt['shippingadmin7']."</th><th>".$txt['shippingadmin12']."</th></tr>";
         // add a shipping method
         echo "  <form method=\"POST\" action=\"index.php?page=shippingadmin&action=add_shipping\">";
         echo "  <tr class=\"altrow\">";
         echo "    <td><input name=\"description\" type=\"text\" value=\"\" size=\"30\" maxlength=\"150\"></td>";
         echo "    <td><input name=\"country\" type=\"checkbox\"></td>";
         echo "    <td><input type=\"submit\" value=\"".$txt['shippingadmin10']."\"></td>";
         echo "  </tr>";
         echo "  </form>";
         
         $query="SELECT * FROM `".$dbtablesprefix."shipping`";
         $sql = mysql_query($query) or die(mysql_error());
         
         while ($row = mysql_fetch_row($sql)) {
	            echo "  <tr>";
	            echo "    <td>".$row[1]."</td>";
	            echo "    <td>".$row[2]."</td>";
	            echo "    <td><a class=\"plain\" href=\"?page=shippingadmin&sid=".$row[0]."&action=show_shipping\">".$txt['shippingadmin8']."</a><br />";
                if ($row[3] <> 1) { echo "    <a class=\"plain\" href=\"?page=shippingadmin&sid=".$row[0]."&action=delete_shipping\">".$txt['shippingadmin9']."</a></td>"; }
	            echo "  </tr>";
         }    
         echo "</table>";
         echo "<br /><br />";
         echo "<h6>".$txt['shippingadmin17']."</h6>";
         echo "<ul>";
         echo "<li><a class=\"plain\" href=\"?page=adminedit&filename=countries.txt&root=1&wysiwyg=0\">".$txt['shippingadmin11']."</a></li>";
         echo "<li><a class=\"plain\" href=\"?page=editsettings&show=2\">" .$txt['shippingadmin16']." (".$send_default_country.")</a></li>";
         echo "</ul>";
}         
?>