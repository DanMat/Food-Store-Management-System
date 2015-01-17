<?php if ($index_refer <> 1) { exit(); } ?>
<?php
// admin check
if (IsAdmin() == false) {
  PutWindow($gfx_dir, $txt['general12'], $txt['general2'], $gfx_dir."/warning.gif", "50");
}
else {
    $status = "%";
    if (!empty($_POST['status'])) {
	    $status=$_POST['status'];
    }
    if (!empty($_GET['status'])) {
	    $status=$_GET['status'];
    }
    if (!empty($_POST['newstatus'])) {
	    $newstatus=$_POST['newstatus'];
    }
    if (!empty($_POST['notify'])) {
	    $notify=$_POST['notify'];
    }
    if (!empty($_POST['orderid'])) {
	    $orderid=$_POST['orderid'];
    }
      
    // show pull down to choose new status
    if ($action == "showstatus") {
       if (!empty($_GET['orderid'])) {
	      $orderid=$_GET['orderid'];
       }
       if (!empty($_GET['oldstatus'])) {
	      $oldstatus=$_GET['oldstatus'];
       }
       // read old status text
       $query = "SELECT * FROM `".$dbtablesprefix."order` WHERE `ID` = ".$orderid;
       $sql = mysql_query($query) or die(mysql_error());
       while ($row = mysql_fetch_row($sql)) {
               $status_id = $row[0]; // status of this order
		       		   
			   echo "<table width=\"70%\" class=\"datatable\">";
		       echo "<caption>".$txt['orderadmin14']." ".$row[7]."</caption>";
		       echo "<tr><td>";
		       
		       // determin the status and show a colored picture accordingly
			   $colors = array('1'=>'blue','2'=>'red','3'=>'red','4'=>'orange','5'=>'green','6'=>'green','7'=>'green');
			   $status = $row[2];
			   $status_color = $colors[$status];
			   $status_text = $txt["db_status$status"];		       
		       echo "<img src=\"".$gfx_dir."/bullit_".$status_color.".gif\" alt=\"".$status_text."\" />&nbsp;".$status_text."<br />";
			   echo "<br />"; 
		       echo "<form method=\"post\" action=\"index.php?page=orderadmin&action=changestatus\">";
		       echo "<input type=\"hidden\" name=\"orderid\" value=\"" . $orderid . "\">";
			   echo "<SELECT NAME=\"newstatus\">";
		       echo "    <OPTION SELECTED VALUE=\"\">";
		       echo "    <OPTION VALUE=\"2\">" . $txt['db_status2'];
		       echo "    <OPTION VALUE=\"3\">" . $txt['db_status3'];
		       echo "    <OPTION VALUE=\"4\">" . $txt['db_status4'];
		       echo "    <OPTION VALUE=\"5\">" . $txt['db_status5'];
		       echo "    <OPTION VALUE=\"6\">" . $txt['db_status6'];
		       echo "    <OPTION VALUE=\"7\">" . $txt['db_status7'];
		       echo "    <OPTION VALUE=\"delete\">Delete";
		       echo "</SELECT><br />";
		       echo "<input type=\"checkbox\" name=\"notify\" value=\"yes\" checked>".$txt['orderadmin7']."<br />";
		       echo "<h4><input type=\"submit\" value=\"".$txt['orderadmin8']."\"></h4>";
		       echo "</form></td></tr></table>";
	   }
    }
    
    // change the order status
    if ($action == "changestatus" && $newstatus != "") {
	    // you shouldnt remove orders unless they are test orders
	    if ($newstatus == "delete") {
	       // first get the customerid from the order
	       $query = "SELECT * FROM `".$dbtablesprefix."order` WHERE `ID` = ".$orderid;
           $sql = mysql_query($query) or die(mysql_error());
               
           while ($row = mysql_fetch_row($sql)) {
           $webid = $row[7]; //webid of this order, so we can derive the filename from it
                    }		   
           $query = "DELETE FROM `".$dbtablesprefix."order` WHERE ID = " . $orderid; // delete the record
           $sql = mysql_query($query) or die(mysql_error());
           unlink($orders_dir."/".strval($webid).".php"); // delete the file
           PutWindow($gfx_dir, $txt['general13'], $txt['orderadmin3'], "notify.gif", "50");
        }
	    else {
           $query = "UPDATE `".$dbtablesprefix."order` SET `STATUS` = '" . $newstatus . "' WHERE `ID` = " . $orderid;
           $sql = mysql_query($query) or die(mysql_error());
           $message = $txt['orderadmin15'];
           // send notification to customer??
           
               if ($notify == "yes") {

	               // first get the customerid from the order
	               $query = "SELECT `CUSTOMERID`, `WEBID` FROM `".$dbtablesprefix."order` WHERE `ID` = '".$orderid."'";
                   $sql = mysql_query($query) or die(mysql_error());
               
                    while ($row = mysql_fetch_row($sql)) {
                          $custid = $row[0]; //customer id of current order
                          $webid = $row[1]; //web id of current order
                    }
	               $query = "SELECT `EMAIL` FROM `".$dbtablesprefix."customer` WHERE `ID` = '".$custid."'";
                   $sql = mysql_query($query) or die(mysql_error());
               
                    while ($row = mysql_fetch_row($sql)) {
                          $to = $row[0]; //email address of that customer
                    }
                    // found out what the new status is
			        $email_status = $txt["db_status$newstatus"]; 
			       
                    // prepare the email and send it
                    $subject = $txt['orderadmin1'] . $webid. $txt['orderadmin2'];
                    $body = $txt['orderadmin1'] . $webid. $txt['orderadmin2'].": ".$email_status.$txt['orderadmin4'].$custid. $txt['orderadmin5'];
		            mymail($webmaster_mail, $to, $subject, $body, $charset);
                    $message = $message."<br />".$txt['orderadmin6']." ".$to;
               }
               PutWindow($gfx_dir, $txt['general13'], $message, "notify.gif", "50");
           }
    }

    
    // orders per page
    $orders_per_page = 20; // change this if you want
    
    if ($orders_per_page > 0) {
       if (!empty($_GET['num_page'])) {
          $num_page = $_GET['num_page'];
       }
       else { $num_page = 1; }
       $start_record = ($num_page -1) * $orders_per_page;
       $limit    = " LIMIT $start_record, $orders_per_page"; 
    }
    else { $limit = ""; }       
    
    // cycle trough orders, if there are no search criterea, then show all
    if ($status == "%") {  $where = ""; }
    else { $where = "WHERE STATUS = '" . $status . "'"; }

    $query = "SELECT * FROM `".$dbtablesprefix."order` " . $where . " ORDER BY ID DESC";
    $sql = mysql_query($query) or die(mysql_error());
    $num_orders = mysql_num_rows($sql);
    $sql = mysql_query($query.$limit) or die(mysql_error());
    ?>

    <FORM METHOD="post" action="index.php?page=orderadmin">
     <SELECT NAME="status">
           <OPTION VALUE="%"><?php echo $txt['orderadmin9']; ?>
           <OPTION VALUE="1"><?php echo $txt['db_status1'] ?>
           <OPTION VALUE="2"><?php echo $txt['db_status2'] ?>
           <OPTION VALUE="3"><?php echo $txt['db_status3'] ?>
           <OPTION VALUE="4"><?php echo $txt['db_status4'] ?>
           <OPTION VALUE="5"><?php echo $txt['db_status5'] ?>
           <OPTION VALUE="6"><?php echo $txt['db_status6'] ?>
           <OPTION VALUE="7"><?php echo $txt['db_status7'] ?>
     </SELECT>
     <INPUT TYPE="submit" VALUE="<?php echo $txt['orderadmin11']; ?>">
    </FORM>

    <table width="100%" class="datatable">
      <caption><?php echo $txt['orderadmin13']; ?></caption>
     <tr> 
      <th><?php echo $txt['orders4']; ?></th>
      <th><?php echo $txt['orders5']; ?></th>
      <th><?php echo $txt['orders6']." (".$currency.")"; ?></th>
      <th><?php echo $txt['orders7']; ?></th>
      <th><?php echo $txt['orders8']; ?></th>
     </tr>

    <?php
    if (mysql_num_rows($sql) == 0) {
        echo "<tr><td colspan=\"5\">" . $txt['orderadmin10'] ."</td></tr>";
    }
    else {
     while ($row = mysql_fetch_row($sql)) {
	    
	   // lets first check if the order still has a local file in the Orders folder
	   if (file_exists($orders_dir ."/". $row[7].".php")) {
		   $sub_query = "SELECT * FROM ".$dbtablesprefix."customer WHERE ID = " . $row[5];
           $sub_sql = mysql_query($sub_query) or die(mysql_error());

           while ($sub_row = mysql_fetch_row($sub_sql)) {
	           echo "<tr><td nowrap>";
	           echo "<a href=\"?page=readorder&orderid=" . $row[0] . "\">" . $row[7] . "</a><br />";
	           
	           // if a pdf was created, lets show it here
	           if ($row[10] != "" && !is_null($row[10]) && file_exists($orders_dir ."/". $row[10])) { 
		           echo "<a href=\"".$orders_dir ."/". $row[10]."\"><img src=\"".$gfx_dir."/pdf.gif\" alt=\"PDF\"></a> "; 
		       }
	           // if customer added notes to the order, then lets bring this to the admins attention by adding a note icon
	           if ($row[8] != "" && !is_null($row[8])) { 
		           $note = nl2br($row[8]);
		           $note = addslashes($note);
		           $note = br2nl($note);
		           echo "<a href=\"javascript:alert('".stripslashes($txt['shipping3']).": \\n$note')\"><img src=\"".$gfx_dir."/admin_notes.gif\" alt=\"".$txt['orderadmin12']."\"></a> "; 
		       }
	           echo "</td><td>";
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
	           echo "<td><div style=\"text-align:right;\">".myNumberFormat($row[6], $number_format)."</div></td>";
	           echo "<td>".$row[1]."</td>";
               echo "<td>";
               // determin the status and show a colored picture accordingly
			   $colors = array('1'=>'blue','2'=>'red','3'=>'red','4'=>'orange','5'=>'green','6'=>'green','7'=>'green');
			   $order_status = $row[2];
			   $status_color = $colors[$order_status];
			   $status_text = $txt["db_status$order_status"];		       
	           echo "<img src=\"".$gfx_dir."/bullit_".$status_color.".gif\" alt=\"".$status_text."\" />&nbsp;<a href=\"index.php?page=orderadmin&action=showstatus&orderid=".$row[0]."&oldstatus=".$row[2]."\">".$status_text."</a><br />";
               echo "</td></tr>";
           }
       }    
     }
    } 
    echo "</table>";
    
  // page code
  if ($orders_per_page > 0 && $num_orders > $orders_per_page) {
	  $page_counter = 0;
	  $num_pages = 0;
	  $rest_orders = $num_orders;
	  echo "<br /><h4>".$txt['browse11'].": ";
	  
	  for($i = 0; $i < $num_orders; $i++) { 
		  $page_counter++;
		  if ($page_counter == $orders_per_page) {
			  $num_pages++;
			  $page_counter = 0;
			  $rest_orders = $rest_orders - $orders_per_page;
			  if ($num_pages == $num_page) {
				  echo "<b>[$num_pages]</b>";
			  }
			  else { echo "<a href=\"index.php?page=orderadmin&action=$action&status=$status&num_page=$num_pages\">[$num_pages]</a>"; }
			  echo " ";
		  }
      }
      // the rest (if any)
      if ($rest_orders > 0) {
		  $num_pages++;
		  if ($num_pages == $num_page) {
			  echo "<b>[$num_pages]</b>";
		  }
		  else { echo "<a href=\"index.php?page=orderadmin&action=$action&status=$status&num_page=$num_pages\">[$num_pages]</a>"; }
	  }
      echo "</h4>"; 
  }   
}    
?>