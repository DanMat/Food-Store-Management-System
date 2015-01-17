<?php if ($index_refer <> 1) { exit(); } ?>
<?php
      $searchmethod = " AND "; //default

      if (!empty($_POST['searchmethod'])) {
	      $searchmethod=$_POST['searchmethod'];
      }
      if (!empty($_GET['searchmethod'])) {
	      $searchmethod=$_GET['searchmethod'];
      }
      if (!empty($_POST['searchfor'])) {
	      $searchfor=$_POST['searchfor'];
      }
      if (!empty($_GET['searchfor'])) {
	      $searchfor=$_GET['searchfor'];
      }
      if (!empty($_GET['orderby'])) {
	      $orderby = $_GET['orderby'];
	  }
	  if ($orderby == 1) {
		  $orderby_field = "PRODUCTID"; 
	  }
	  else { $orderby_field = "PRICE"; }
?>

<?php
    if (!empty($cat)){
    // find the product category
     $query = sprintf("SELECT * FROM `".$dbtablesprefix."category` where `ID`=%s", quote_smart($cat));
     $sql = mysql_query($query) or die(mysql_error());

     while ($row = mysql_fetch_row($sql)) {
            $categorie = $row[1];
            }
     }
    else {
	     $categorie = $txt['browse1'] . " / " . $searchfor;
    }
    
    // products per page
    if ($products_per_page > 0) {
       if (!empty($_GET['num_page'])) {
          $num_page = $_GET['num_page'];
       }
       else { $num_page = 1; }
       $start_record = ($num_page -1) * $products_per_page;
       $limit    = " LIMIT $start_record, $products_per_page"; 
    }
    else { $limit = ""; }   
?>

          <table width="100%" class="datatable">
            <caption><?php echo $txt['browse9']; ?></caption>
           <tr><th>
                    <?php 
                        echo $txt['browse2']." / ".$categorie;
                        echo "<br />";
                        if ($action == "list") { echo "<a href=\"index.php?page=browse&action=list&group=$group&cat=$cat&orderby=1\"><small>".$txt['browse4']."</small></a>";  }
                    ?>
               </th><th>
			        <?php 
			            echo "<div style=\"text-align:right;\">";
			            echo $txt['browse3']; 
			        	// if we use VAT, then display that the prices are including VAT in the list below
				        if ($no_vat == 0) { echo " (".$txt['general7']." ".$txt['general5'].")"; }
                        echo "<br />";
                        if ($action == "list") { echo "<a href=\"index.php?page=browse&action=list&group=$group&cat=$cat&orderby=2\"><small>".$txt['browse4']."</small></a>";  }
                        echo "</div>";
			        ?>
               </th>
           </tr>
  <?php


 	if ($action == "list") {
	 	if ($stock_enabled == 1 && $hide_outofstock == 1 && IsAdmin() == false) { // filter out products with stock lower than 1
	 	    $query = sprintf("SELECT * FROM `".$dbtablesprefix."product` where `STOCK` > 0 AND `CATID`=%s ORDER BY `$orderby_field` ASC", quote_smart($cat));
	 	}
	 	else { $query = sprintf("SELECT * FROM `".$dbtablesprefix."product` WHERE CATID=%s ORDER BY `$orderby_field` ASC", quote_smart($cat)); }
    }
    elseif ($action == "shownew") {
	 	if ($stock_enabled == 1 && IsAdmin() == false) { // filter out products with stock lower than 1
            $query = "SELECT * FROM `".$dbtablesprefix."product` WHERE `STOCK` > 0 AND `NEW` = '1' ORDER BY `$orderby_field` ASC";
        }
        else { $query = "SELECT * FROM `".$dbtablesprefix."product` WHERE `NEW` = '1' ORDER BY `$orderby_field` ASC"; }
    }
	    
    else {
         //search on the given terms
		 if ($searchfor != "") {
	         $searchitem = explode (" ", $searchfor);
	         if ($stock_enabled == 1) { $searchquery = "WHERE `STOCK` > 0 AND ("; }
			 else $searchquery = "WHERE (";

		     $counter = 0;
		     while (!$searchitem[$counter] == NULL){
				$searchquery .= "((DESCRIPTION LIKE '%" . $searchitem[$counter] . "%') OR (PRODUCTID LIKE '%" . $searchitem[$counter] . "%'))";
				$counter += 1;
				if (!$searchitem[$counter] == NULL) { $searchquery .= " ".$searchmethod." "; }
	   	     }
	         $searchquery .= ")";
		 }
		 else { $searchquery = "WHERE (DESCRIPTION = 'never_find_me')"; } // just to cause that the searchresult is empty
         $query = "SELECT * FROM `".$dbtablesprefix."product` $searchquery ORDER BY `$orderby_field` ASC";
	}
	
	$memcache = memcache_connect('localhost', 11211);
	if ($memcache) {
	$memcache->set("sql",$query );
	//var_dump($query);
	$query = $memcache->get('sql');
	//var_dump($query);
	}
	
	// total products without the limit
	$sql = mysql_query($query) or die(mysql_error());
    $num_products = mysql_num_rows($sql);
    
    // products optionally with the limit
	$sql = mysql_query($query.$limit) or die(mysql_error());
    if (mysql_num_rows($sql) == 0) {
	   echo "<tr><td>".$txt['browse5']."</td><td>&nbsp;</td></tr></table>";
    }
    else {
	    
      $optel = 0;
      


	  
      while ($row = mysql_fetch_row($sql)) {
	          $optel++;
	          if ($optel == 3) { $optel = 1; }
	          if ($optel == 1) { $kleur = ""; }
	          if ($optel == 2) { $kleur = " class=\"altrow\""; }
              
	          // the price gets calculated here
	          $printprijs = $row[4]; // from the database
	          if ($db_prices_including_vat == 0 && $no_vat == 0) { $printprijs = $row[4] * $vat; }
              $printprijs = myNumberFormat($printprijs); // format to our settings

              // reset values
              $picturelink = "";
              $new = "";
              $thumb = "";
			  $stocktext = "";
              
              // new product?
              if ($row[7] == 1) { $new = "<font color=\"red\"><strong>" . $txt['general3']. "</strong></font>"; }
              
              // is there a picture?
              if ($search_prodgfx == 1 && $use_prodgfx == 1) {
	              
	              if ($pictureid == 1) { $picture = $row[0]; }
	              else { $picture = $row[1]; }
	              
	              // determine resize of thumbs
                  $width = "";
                  $height = "";
                  if ($pricelist_thumb_width != 0) { $width = " width=\"".$pricelist_thumb_width."\""; }
                  if ($pricelist_thumb_height != 0) { $height = " height=\"".$pricelist_thumb_height."\""; }
                  
	              if (thumb_exists($product_dir ."/". $picture . ".jpg")) { $thumb = "<img class=\"imgleft\" src=\"".$product_dir."/".$picture.".jpg\"".$width.$height." alt=\"\" />"; }
	              if (thumb_exists($product_dir ."/". $picture . ".gif")) { $thumb = "<img class=\"imgleft\" src=\"".$product_dir."/".$picture.".gif\"".$width.$height." alt=\"\" />"; }
	              if (thumb_exists($product_dir ."/". $picture . ".png")) { $thumb = "<img class=\"imgleft\" src=\"".$product_dir."/".$picture.".png\"".$width.$height." alt=\"\" />"; }
	              
	              // if the script uses make_thumbs, then search for thumbs
	              if ($make_thumbs == 1) {
		              if (thumb_exists($product_dir ."/tn_". $picture . ".jpg")) { $thumb = "<img class=\"imgleft\" src=\"".$product_dir."/tn_".$picture.".jpg\" alt=\"\" />"; }
		              if (thumb_exists($product_dir ."/tn_". $picture . ".gif")) { $thumb = "<img class=\"imgleft\" src=\"".$product_dir."/tn_".$picture.".gif\" alt=\"\" />"; }
		              if (thumb_exists($product_dir ."/tn_". $picture . ".png")) { $thumb = "<img class=\"imgleft\" src=\"".$product_dir."/tn_".$picture.".png\" alt=\"\" />"; }
	              }
	              
	              if ($thumb != "" && $thumbs_in_pricelist == 0) {
		              // use a photo icon instead of a thumb
		              $picturelink = "<a href=\"".$product_dir."/".$picture.".jpg\"><img src=".$gfx_dir."/photo.gif></a>";
		              $thumb = "";
	              }
              }
              
              // see if you are an admin. if so, add a [EDIT] link to the line
              $admin_edit = "";
              if (IsAdmin() == true) {
                  $admin_edit = "<br /><br />";
                  if ($stock_enabled == 1) { $admin_edit .= $txt['productadmin12'].": ".$row[5]."<br />"; }
                  $admin_edit .= "<a href=\"?page=productadmin&action=edit_product&pcat=".$cat."&prodid=".$row[0]."\">".$txt['browse7']."</a>";
                  $admin_edit .= " | <a href=\"?page=productadmin&action=delete_product&pcat=".$cat."&prodid=".$row[0]."\">".$txt['browse8']."</a>";
                  $admin_edit .= " | <a href=\"?page=productadmin&action=picture_upload_form&picid=".$picture."\">".$txt['browse10']."</a>";
              }
              // make up the description to print according to the pricelist_format and max_description
              if ($pricelist_format == 0) { $print_description = $row[1]; }
              if ($pricelist_format == 1) { $print_description = $row[3]; }
              if ($pricelist_format == 2) { $print_description = $row[1]." - ".$row[3]; }
              if ($max_description != 0) {
                 $description = stringsplit($print_description, $max_description); // so lets only show the first xx characters
                 if (strlen($print_description) != strlen($description[0])) { $description[0] = $description[0] . ".."; }
                 $print_description = $description[0];
              }
              $print_description = strip_tags($print_description); //remove html because of danger of broken tags
              
              echo "<tr".$kleur.">";
              
              // see what the stock is
              if ($stock_enabled == 0) {
	              if ($row[5] == 1) { $stockpic = "<img class=\"imgleft\" src=\"".$gfx_dir."/bullit_green.gif\" alt=\"".$txt['db_stock1']."\" /> "; } // in stock
	              if ($row[5] == 0) { $stockpic = "<img class=\"imgleft\" src=\"".$gfx_dir."/bullit_red.gif\" alt=\"".$txt['db_stock2']."\" /> "; } // out of stock
	              if ($row[5] == 2) { $stockpic = "<img class=\"imgleft\" src=\"".$gfx_dir."/bullit_orange.gif\" alt=\"".$txt['db_stock3']."\" /> "; } // in backorder
              }
              else { 
				$stockpic = ""; 
				if ($hide_outofstock == 0 && $row[5] == 0) { $row[4] = 0; }
				if (IsAdmin() == FALSE && $show_stock == 1) {
					$stocktext = "<br /><small>".$txt['browse13'].": ".$row[5]."</small>";
				}
			  }
              
              echo "<td>".$stockpic."<a class=\"plain\" href=\"index.php?page=details&prod=".$row[0]."&cat=".$row[2]."&group=".$group."\">".$thumb.$print_description."</a> ".$picturelink." ".$new." ".$stocktext.$admin_edit."</td>";
              echo "<td><div style=\"text-align:right;\">";
              if ($order_from_pricelist == 1) {
			  ?>
	               <form method="POST" action="index.php?page=cart&action=add">
	               <div style="text-align:right">
	                <input type="hidden" name="prodid" value="<?php echo $row[0] ?>">
	                <input type="hidden" name="prodprice" value="<?php echo $row[4] ?>">
	                                <?php
	                                       if (!$row[4] == 0) {
		                                       if ($no_vat == 1) {
	                                               $in_vat = myNumberFormat($row[4]);
	                                               echo "<big><strong>". $currency_symbol_pre.$in_vat.$currency_symbol_post."</strong></big>";
			                                   }
			                                   else {    
		                                            if ($db_prices_including_vat == 1) {
		                                               $ex_vat = $row[4] / $vat;    
	                                                   $in_vat = myNumberFormat($row[4]);
	                                                   $ex_vat = myNumberFormat($ex_vat);
	                                                }
	                                                else {
		                                               $in_vat = $row[4] * $vat;    
	                                                   $ex_vat = myNumberFormat($row[4]);
	                                                   $in_vat = myNumberFormat($in_vat);
	                                                }
	                                                echo "<big><strong>".$currency_symbol_pre.$in_vat.$currency_symbol_post."</strong></big>";
	                                                echo "<br /><small>(".$currency_symbol_pre.$ex_vat.$currency_symbol_post." ".$txt['general6']." ".$txt['general5'].")</small>";
	                                           }
	                                           
	                                       // product features
	                                       $allfeatures = $row[8];
	                                       if (!empty($allfeatures)) {
		                                       $features = explode("|", $allfeatures);
		                                       $counter1 = 0;
		                                       echo "<br /><br />";
		                                       while (!$features[$counter1] == NULL){
                                                   if (strpos($features[$counter1],":")===FALSE){echo "<br />".$features[$counter1].":  <input type=\"text\" name=\"".$features[$counter1]."\"> ";$counter1 += 1;} 
												   else {
				                                       $feature = explode(":", $features[$counter1]);
				                                       $counter1 += 1;
				                                       echo "<br />".$feature[0].": ";
				                                       echo "<select name=\"".$feature[0]."\">";
				                                       $value = explode(",", $feature[1]);
			                                           $counter2 = 0;
				                                       while (!$value[$counter2] == NULL){
					                                       
					                                       // optionally you can specify the additional costs: color:red+1.50,green+2.00,blue+3.00 so lets deal with that
					                                       $extracosts = explode("+",$value[$counter2]);
					                                       if (!$extracosts[1] == NULL) {
						                                       // there are extra costs
						                                       $printvalue = $extracosts[0]." (+".$currency_symbol_pre.myNumberFormat($extracosts[1],$number_format).$currency_symbol_post.")";
					                                       }
					                                       else { 
						                                       $printvalue = $value[$counter2]; 
						                                   }
					                                       
					                                       // print the pulldown menu
														   $printvalue = str_replace("+".$currency_symbol_pre."-", "-".$currency_symbol_pre, $printvalue);
					                                       echo "<option value=\"".$value[$counter2]."\""; if ($counter2 == 0) { echo " SELECTED"; } echo ">".$printvalue;
					                                       $counter2 += 1;
			                                           }
			                                           echo "</select>";
												   }
		                                       }
	                                       }
	
	                                 ?>
	                                   <br />
	                                   <br />
	                                   <?php echo $txt['details6'] ?>:<br /><input type="text" size="4" name="numprod" value="1" maxlength="4">&nbsp;<input type="submit" value="<?php echo $txt['details7'] ?>" name="sub">
	                                   <?php 
										   }
										   else {
												if ($row[5] == 0 && $hide_outofstock == 0) { echo '<strong><big>'.$txt['browse12'].'</big></strong>'; }
										   }
										?>
	               </form>
              <?php
              }			  
			  else { echo "<big><strong>".$currency_symbol."&nbsp;".$printprijs."</strong></big>"; }
			  echo "</div></td>";
              echo "</tr>";
               } ?>
         </table>
       <div style="text-align:right;"><img src="<?php echo $gfx_dir ?>/photo.gif" alt="" /> <em><small><?php echo $txt['browse6'] ?></small></em></div>

<?php
  // page code
  if ($products_per_page > 0 && $num_products > $products_per_page) {
	  
	  $page_counter = 0;
	  $num_pages = 0;
	  $rest_products = $num_products;
	  
	  echo "<br /><h4>".$txt['browse11'].": ";
	  
	  for($i = 0; $i < $num_products; $i++) { 
		  $page_counter++;
		  if ($page_counter == $products_per_page) {
			  $num_pages++;
			  $page_counter = 0;
			  $rest_products = $rest_products - $products_per_page;
			  if ($num_pages == $num_page) {
				  echo "<b>[$num_pages]</b>";
			  }
			  else { echo "<a href=\"index.php?page=browse&action=$action&group=$group&cat=$cat&orderby=$orderby&searchmethod=$searchmethod&searchfor=$searchfor&num_page=$num_pages\">[$num_pages]</a>"; }
			  echo " ";
		  }
      }
      // the rest (if any)
      if ($rest_products > 0) {
		  $num_pages++;
		  if ($num_pages == $num_page) {
			  echo "<b>[$num_pages]</b>";
		  }
		  else { echo "<a href=\"index.php?page=browse&action=$action&group=$group&cat=$cat&orderby=$orderby&searchmethod=$searchmethod&searchfor=$searchfor&num_page=$num_pages\">[$num_pages]</a>"; }
	  }
	      
      echo "</h4>"; 
  }       
?>       
<?php      
  if ($stock_enabled == 0) {  
?>       
       <br />
       <br />
          <table width="50%" class="datatable">
            <caption><?php echo $txt['db_stock10'] ?></caption>
              <tr>
                  <td><?php echo "<img src=\"".$gfx_dir."/bullit_green.gif\" alt=\"".$txt['db_stock1']."\" />"; ?></td>
                  <td><?php echo $txt['db_stock11']; ?></td>
              </tr>
              <tr>
                  <td><?php echo "<img src=\"".$gfx_dir."/bullit_red.gif\" alt=\"".$txt['db_stock2']."\" />"; ?></td>
                  <td><?php echo $txt['db_stock12']; ?></td>
              </tr>
              <tr>
                  <td><?php echo "<img src=\"".$gfx_dir."/bullit_orange.gif\" alt=\"".$txt['db_stock3']."\" />"; ?></td>
                  <td><?php echo $txt['db_stock13']; ?></td>
              </tr>
       </table>
<?php       
     }
  }
?>  