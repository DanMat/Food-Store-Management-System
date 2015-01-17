<?php if ($index_refer <> 1) { exit(); } ?>

<?php
      if (!empty($_POST['numprod'])) {
	      $numprod=intval($_POST['numprod']);
      }
      if (!empty($_POST['prodid'])) {
	      $prodid=intval($_POST['prodid']);
      }
      if (!empty($_POST['prodprice'])) {
	      $prodprice=$_POST['prodprice'];
      }
      if (!empty($_POST['basketid'])) {
	      $basketid=intval($_POST['basketid']);
      }

    // current date
    $today = getdate();
    $error = 0; // no errors found
    
    if (IsAdmin() == true) {
	    if (!empty($_GET['id']))
	        { $customerid = intval($_GET['id']); }
       }

    if ($action=="add" && $numprod != 0) {
	    // if we work with stock amounts, then lets check if there is enough in stock
	    if ($stock_enabled == 1) {
            // if you have 2 of product x in basket and stock is 2, you get an error if you try to add 1 more
		    $query = "SELECT `QTY` FROM `".$dbtablesprefix."basket` WHERE `CUSTOMERID` = '".$customerid."' AND `PRODUCTID` = '".$prodid."' AND ORDERID = 0";
	        $sql = mysql_query($query) or die(mysql_error());
	        if (mysql_num_rows($sql) != 0) {
	            $row = mysql_fetch_row($sql);
	  			$num_in_basket = $row[0];
	    	}
	    	else { $num_in_basket = 0; }
	        
            $query = sprintf("SELECT `STOCK` FROM `".$dbtablesprefix."product` WHERE `ID` = %s", quote_smart($prodid));
            $sql = mysql_query($query) or die(mysql_error());
            $row = mysql_fetch_row($sql);
			$numordered = $numprod + $num_in_basket;
            if ($numordered > $row[0] || $row[0] == 0) { // you're ordering more then whats in stock , or stock is 0
  		       $warning = $txt['checkout15']."<br /><br />".$txt['checkout7']." ".$numordered."<br />".$txt['checkout8']." ".$row[0];
  		       PutWindow($gfx_dir, $txt['general12'], $warning, "warning.gif", "50");
  		       $error = 1;
	        }
        }
        
        if ($error == 0) {
		    // product features
		    $query = sprintf("SELECT `FEATURES` FROM `".$dbtablesprefix."product` WHERE `ID` = %s", quote_smart($prodid));
		    $sql = mysql_query($query) or die(mysql_error());
		    $row = mysql_fetch_row($sql);
		   
		    $allfeatures = $row[0];
		    $productfeatures = "";
			
			// dit stuk is stuk. in row7 moeten de geselecteerde features. in row3 de prijs inclusief deze features.
		    if (!empty($allfeatures)) {
		       $features = explode("|", $allfeatures);
		       $counter1 = 0;
		       echo "<br /><br />";
		       while (!$features[$counter1] == NULL){
		           $feature = explode(":", $features[$counter1]);
		           $counter1 += 1;
				       if (!empty($_POST["$feature[0]"])) {
					       $detail = explode("+", $_POST["$feature[0]"]);
				       	   $productfeatures .= $feature[0].": ".$detail[0];
						   $prodprice += $detail[1];
			           }
					   if (!empty($features[$counter1])) { 
						   $productfeatures .= ", "; 
					   }
		       }
		    }
		    
		    // now lets check if the product we add is new, or we need to update an existing record
		    $query = "SELECT `ID` FROM `".$dbtablesprefix."basket` WHERE `CUSTOMERID` = '".$customerid."' AND `PRODUCTID` = '".$prodid."' AND `FEATURES` = '". $productfeatures . "' AND ORDERID = 0";
		    $sql = mysql_query($query) or die(mysql_error());
		    if (mysql_num_rows($sql) == 0) {
		    	$query = "INSERT INTO `".$dbtablesprefix."basket` ( `CUSTOMERID` , `PRODUCTID` , `PRICE` , `ORDERID` , `LINEADDDATE` , `QTY` , `FEATURES`) VALUES ('".$customerid."', '".$prodid."', '".$prodprice."', '0', '".Date("d-m-Y @ G:i")."', '".$numprod."', '".$productfeatures."')";
			}
			else {
		    	$query = "UPDATE `".$dbtablesprefix."basket` SET `QTY` = `QTY` + ".$numprod." WHERE `PRODUCTID` = '".$prodid."' AND `CUSTOMERID` = '".$customerid."' AND ORDERID = 0";
			}
		   	$sql = mysql_query($query) or die(mysql_error());
	   	}
   }
   
   if ($action=="update"){
	   if ($numprod == 0) {
		   $query = "DELETE FROM `".$dbtablesprefix."basket` WHERE `ID` = '". $basketid."' AND `ORDERID` = '0'";
           $sql = mysql_query($query) or die(mysql_error());
      }
      else {
	       // if we work with stock amounts, then lets check if there is enough in stock
           if ($stock_enabled == 1) {
               $query = "SELECT `STOCK` FROM `".$dbtablesprefix."product` WHERE `ID` = '".$prodid."'";
               $sql = mysql_query($query) or die(mysql_error());
               $row = mysql_fetch_row($sql);
               
               if ($numprod > $row[0] || $row[0] == 0) {
                  PutWindow($gfx_dir, $txt['general12'], $txt['checkout15']."<br />".$txt['checkout7']." ".$numprod."<br />".$txt['checkout8']." ".$row[0], "warning.gif", "50");
                  $error = 1;
			   }
		   }
		   if ($error == 0) {
              $query = "UPDATE `".$dbtablesprefix."basket` SET `QTY` = ".$numprod." WHERE `ID` = ".$basketid;
              $sql = mysql_query($query) or die(mysql_error());
           }
      }
   }

   if ($action=="empty"){
		   $query = "DELETE FROM ".$dbtablesprefix."basket WHERE `CUSTOMERID` = " . $customerid;
           $sql = mysql_query($query) or die(mysql_error());
   }

   // read basket
   $query = "SELECT * FROM ".$dbtablesprefix."basket WHERE (`CUSTOMERID` = ".$customerid." AND `ORDERID` = 0) ORDER BY ID";
   $sql = mysql_query($query) or die(mysql_error());
   $count = mysql_num_rows($sql);

   if ($count == 0) {
	   PutWindow($gfx_dir, $txt['cart1'], $txt['cart2'], "carticon.gif", "50");
   }
   else {
   ?>
   
   <table width="100%" class="datatable">
     <caption><?php echo $txt['cart11'] ?></caption>
     <tr>
       <th><?php echo $txt['cart3']; ?></th>
       <th><?php echo $txt['cart4']; ?></th>
       <th><?php echo $txt['cart5']; ?></th>
    </tr>

   <?php
   $optel = 0;

   while ($row = mysql_fetch_row($sql)) {
         $query = "SELECT * FROM `".$dbtablesprefix."product` where `ID`='" . $row[2] . "'";
         $sql_details = mysql_query($query) or die(mysql_error());
         while ($row_details = mysql_fetch_row($sql_details)) {
   	     $optel = $optel +1;
	     if ($optel == 3) { $optel = 1; }
	     if ($optel == 1) { $kleur = ""; }
	     if ($optel == 2) { $kleur = " class=\"altrow\""; }

         // is there a picture?
         if ($search_prodgfx == 1 && $use_prodgfx == 1) {
              
             if ($pictureid == 1) { $picture = $row_details[0]; }
             else { $picture = $row_details[1]; }
              
             // determine resize of thumbs
             $width = "";
             $height = "";
             $picturelink = "";
             $thumb = "";
             
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
          
         // make up the description to print according to the pricelist_format and max_description
         if ($pricelist_format == 0) { $print_description = $row_details[1]; }
         if ($pricelist_format == 1) { $print_description = $row_details[3]; }
         if ($pricelist_format == 2) { $print_description = $row_details[1]." - ".$row_details[3]; }
         if ($max_description != 0) {
            $description = stringsplit($print_description, $max_description); // so lets only show the first xx characters
            if (strlen($print_description) != strlen($description[0])) { $description[0] = $description[0] . ".."; }
            $print_description = $description[0];
         }
         $print_description = strip_tags($print_description); //remove html because of danger of broken tags
?>
               <tr<?php echo $kleur; ?>>
                   <td><a href="index.php?page=details&prod=<?php echo $row_details[0]; ?>"><?php echo $thumb.$print_description.$picturelink; ?></a>
<?php
                   $productprice = $row[3]; // the price of a product
                   $printvalue = $row[7];   // features
                   if (!$printvalue == "") { echo "<br />(".$printvalue.")"; }
?>
                   </td>
                   <td><?php 
                         echo $currency_symbol_pre;
                         $subtotaal = $productprice * $row[6];
                         if ($no_vat == 0 && $db_prices_including_vat == 0) { $subtotaal = $subtotaal * $vat; }
                         $printprijs = myNumberFormat($subtotaal);
                         echo $printprijs;
                         echo $currency_symbol_post;
                       ?>
                   </td>
                   <td>
                   <form method="POST" action="index.php?page=cart&action=update">
                    <input type="hidden" name="prodid" value="<?php echo $row_details[0] ?>">
                    <input type="hidden" name="basketid" value="<?php echo $row[0] ?>">
                    <div style="text-align:right;"><input type="text" size="4" name="numprod" value="<?php echo $row[6] ?>">&nbsp;<input type="submit" value="<?php echo $txt['cart10'] ?>" name="sub">
                   </form>
                   <form method="POST" action="index.php?page=cart&action=update">
                    <input type="hidden" name="prodid" value="<?php echo $row_details[0] ?>">
                    <input type="hidden" name="basketid" value="<?php echo $row[0] ?>">
                    <input type="hidden" name="numprod" value="0">
                    <div style="text-align:right;"><input type="submit" value="<?php echo $txt['cart6']; ?>" name="sub">
                   </form>
                   </td>
               </tr>
               <?php

               $totaal = $totaal + $subtotaal;
         }
   }
   if ($no_vat == 0 ) {
      $totaal_ex = $totaal / $vat;
      $totaal_ex = myNumberFormat($totaal_ex);
   }
   $totaal = myNumberFormat($totaal);
   ?>
      <tr><td colspan="3"><div style="text-align:right;"><strong><?php echo $txt['cart7']; ?></strong> <?php echo $currency_symbol_pre.$totaal.$currency_symbol_post; ?><br />
      <?php if ($no_vat == 0) { echo "<small>(".$currency_symbol_pre.$totaal_ex.$currency_symbol_post." ".$txt['general6']." ".$txt['general5'].")</small>"; } ?></div></td></tr>
   </table>
   <br />
   <br />
   <div style="text-align:center;">

    <table class="borderless" width="50%">
     <tr><td nowrap>
           <form method="post" action="index.php?page=cart&action=empty">
            <input type="submit" value="<?php echo $txt['cart8']; ?>">
           </form>
         </td>
         <td nowrap>
            <?php
               // if the conditions page is disabled, then we might as well skip it ;)
               if ($conditions_page == 1) { echo "<form method=\"post\" action=\"index.php?page=conditions&action=checkout\">"; }
               else { echo "<form method=\"post\" action=\"index.php?page=shipping\">"; }
               if ($ordering_enabled == 1) { echo "<input type=\"submit\" value=\"".$txt['cart9']."\">"; }
            ?>  
           </form>
         </td>
<?php
         if ($action=="add") {
	         echo "<td nowrap>";
	         // lets find out the category of the last added product
             $query = "SELECT `CATID` FROM `".$dbtablesprefix."product` WHERE `ID` = '".$prodid."'";
             $sql = mysql_query($query) or die(mysql_error());
               
             while ($row = mysql_fetch_row($sql)) {
	             $jump2cat = $row[0];
             }
             echo "<form method=\"post\" action=\"index.php?page=browse&action=list&cat=".$jump2cat."&orderby=DESCRIPTION\">";
             echo "<input type=\"submit\" value=\"".$txt['cart12']."\">";
             echo "</form>";
             echo "</td>";

         }
?>         
     </tr>
    </table>
   </div>
   <?php
   }
   ?>