<?php if ($index_refer <> 1) { exit(); } ?>
<?php include ("./includes/checklogin.inc.php"); ?>
<?php
	if (!empty($_POST['shippingid'])) { $shippingid=intval($_POST['shippingid']); }
	if (!empty($_POST['weightid'])) { $weightid=intval($_POST['weightid']); }
	if (!empty($_POST['paymentid'])) { $paymentid=intval($_POST['paymentid']); }
	if (!empty($_POST['notes']))    { $notes=$_POST['notes']; } else { $notes = ""; }
	if (!empty($_POST['discount_code']))	{ $discount_code= stripslashes(htmlentities($_POST['discount_code'])); } else { $discount_code = ""; }
?>
<?php
if (LoggedIn() == True) {
	$error = 0;
	
    // if the cart is empty, then you shouldn't be here
   if (CountCart($customerid) == 0) {
       PutWindow($gfx_dir, $txt['general12'], $txt['checkout2'], "warning.gif", "50");
	   $error = 1;
   }

	// lets find out some customer details
	$query = sprintf("SELECT * FROM ".$dbtablesprefix."customer WHERE ID = %s", quote_smart($customerid));
	$sql = mysql_query($query) or die(mysql_error());
	 
	// we can not find you, so please leave
	if (mysql_num_rows($sql) == 0) {
	   PutWindow($gfx_dir, $txt['general12'], $txt['checkout2'], "warning.gif", "50");
	   $error = 1;
	}
	
	// if you gave a discount code, let's check if it's valid
	if ($discount_code <> "") {
		$discount_query="SELECT * FROM `".$dbtablesprefix."discount` WHERE `code` = '".$discount_code."' AND `orderid` = '0'";
		$discount_sql = mysql_query($discount_query) or die(mysql_error());
		if (mysql_num_rows($discount_sql) == 0) {
			PutWindow($gfx_dir, $txt['general12'], $txt['checkout1'], "warning.gif", "50");
			$error = 1;
		}
		else { 
			// let's read the discount
			while ($discount_row = mysql_fetch_row($discount_sql)) {
				$discount_amount = $discount_row[2];
				$discount_percentage = $discount_row[3];
			}
		}
	}
	
	if ($error == 0) {
        
	     // read the details
	     while ($row = mysql_fetch_row($sql)) {
	            $lastname = $row[3];
		        $middlename = $row[4];
				$initials = $row[5];
	            $address = $row[7];
	            $zipcode = $row[8];
	            $city = $row[9];
	            $stat = $row[10];
		        $to = $row[12];
	            $country = $row[14];
				$phone = $row[11];
				
	     }
	     
	     // process the order. NOTE: the price is calculated and added later on in this process!!! so $total is still empty at this point
	     $query = sprintf("INSERT INTO `".$dbtablesprefix."order` (`DATE`,`STATUS`,`SHIPPING`,`PAYMENT`,`CUSTOMERID`,`TOPAY`,`WEBID`,`NOTES`,`WEIGHT`) VALUES ('".Date($date_format)."','1',%s,%s,%s,'1','n/a',%s,%s)", quote_smart($shippingid), quote_smart($paymentid), quote_smart($customerid), quote_smart($notes), quote_smart($weightid));
	     $sql = mysql_query($query) or die(mysql_error());

	     // get the last id
	     $lastid = mysql_insert_id();
	
	     // make webID
	     $date_array = GetDate();
	     $this_year = $date_array['year'];
	     $webid = $order_prefix . $this_year. $lastid . $order_suffix;
	     $query = "UPDATE `".$dbtablesprefix."order` SET `WEBID` = '".$webid."' WHERE `ID` = ".$lastid;
	     $sql = mysql_query($query) or die(mysql_error());
	
	     include ($lang_file);
	     $message = $txt['checkout3'];
	      // now go through all all products from basket with status 'basket'
	
	     $query = "SELECT * FROM ".$dbtablesprefix."basket WHERE ( CUSTOMERID = ".$customerid." AND ORDERID = 0 )";
	     $sql = mysql_query($query) or die(mysql_error());
	     $total = 0;
		 
		 // let's format the product list a little
		 $message .= "<table width=\"100%\" class=\"borderless\">";
	
	     while ($row = mysql_fetch_row($sql)) {
		       $query_details = "SELECT * FROM ".$dbtablesprefix."product WHERE ID = '" . $row[2] . "'";
		       $sql_details = mysql_query($query_details) or die(mysql_error());
	
		       while ($row_details = mysql_fetch_row($sql_details)) {
			         $product_price = $row[3]; // read from the cart
			         // additional costs for features?
                     if (!empty($row[7])) { 
	                     // features might involve extra costs, but we don't want to show them
	                     $features = explode(", ", $row[7]);
				         $counter1 = 0;
						 $printvalue = "";
				         while (!$features[$counter1] == NULL){
	                         $feature = explode("+",$features[$counter1]);
	                         $printvalue .= $feature[0];    // don't show the extra costs here, just show the feature
  					         $counter1 += 1;
  				             if (!empty($features[$counter1])) { $printvalue .= ", "; }
	                         $product_price += $feature[1]; // if there are extra costs, let's add them
				         }
			         }
					 
			         if ($no_vat == 0 && $db_prices_including_vat == 0) { $product_price = $product_price * $vat; }
			         
			         // make up the description to print according to the pricelist_format and max_description
	         		 if ($pricelist_format == 0) { $print_description = $row_details[1]; }
	         		 if ($pricelist_format == 1) { $print_description = $row_details[3]; }
	         		 if ($pricelist_format == 2) { $print_description = $row_details[1]." - ".$row_details[3]; }
	         		 if ($max_description != 0) {
			             $description = stringsplit($print_description, $max_description); // so lets only show the first xx characters
			             if (strlen($print_description) != strlen($description[0])) { $description[0] = $description[0] . ".."; }
			             $print_description = $description[0];
			             $print_description = strip_tags($print_description); // strip html because half html can mess up the layout
			         }
			         $print_description = strip_tags($print_description); //remove html because of danger of broken tags
			         if (!empty($row[7])) { $print_description .= "<br />".$printvalue; } // product features
					 $total_add = $product_price * $row[6];
		             $message .= "<tr><td>".$row[6].$txt['checkout4']."</td><td>".$print_description."<br />".$currency_symbol_pre.myNumberFormat($product_price,$number_format).$currency_symbol_post.$txt['checkout5']."</td><td style=\"text-align: right\">".$currency_symbol_pre.myNumberFormat($total_add,$number_format).$currency_symbol_post."</tr>";
		             $total = $total + $total_add;
	
		             // update stock amount if needed
		             if ($stock_enabled == 1) {
			             if ($row[6] > $row_details[5] || $row_details[5] == 0) {
				             // the product stock is too low, so we have to cancel this order
	                         include ($lang_file);
				             PutWindow($gfx_dir, $txt['general12'], $txt['checkout15']." ".$print_description."<br />".$txt['checkout7']." ".$row[6]."<br />".$txt['checkout8']." ".$row_details[5], "warning.gif", "50");
						     $del_query = sprintf("DELETE FROM `".$dbtablesprefix."order` WHERE (`ID` = %s)", quote_smart($lastid));
						     $del_sql = mysql_query($del_query) or die(mysql_error());
						     $error = 1;
			             }
			             else {
				             $new_stock = $row_details[5] - $row[6];
		                     $update_query = "UPDATE `".$dbtablesprefix."product` SET `STOCK` = ".$new_stock." WHERE `ID` = '".$row_details[0]."'";
		                     $update_sql = mysql_query($update_query) or die(mysql_error());
	                     }
		            }
	           }
	     }
		// there might be a discount code
		if ($discount_code <> "") {
			$message.= '<tr><td>'.$txt['checkout14'].'</td><td>'.$txt['checkout18'].' '.$discount_code.'<br />';
			if ($discount_percentage == 1) {
				// percentage
				$discount_percentage = $discount_amount;
				$discount_amount = ($total / 100) * $discount_amount;
				$message.= $txt['checkout14'].' '.$discount_percentage.'%</td><td style="text-align: right"><strong>-'.$currency_symbol_pre.myNumberFormat($discount_amount,$number_format).$currency_symbol_post.'</strong></td></tr>';
			}
			else {
				$message.= $txt['checkout14'].' '.$currency_symbol_pre.myNumberFormat($discount_amount,$number_format).$currency_symbol_post.'</td><td style="text-align: right"><strong>-'.$currency_symbol_pre.myNumberFormat($discount_amount,$number_format).$currency_symbol_post.'</strong></td></tr>';
			}
			$total -= $discount_amount;
			// disable discount
			$query="UPDATE `".$dbtablesprefix."discount` SET `orderid` = '".$lastid."' WHERE `code` = '".$discount_code."'";
			$sql = mysql_query($query) or die(mysql_error());
		}
		 
		 // if the customer added additional notes/questions, we will display them too
		 if (!empty($_POST['notes'])) {
			$message = $message . $txt['checkout6'].$txt['checkout6']; // white line
			$message = $message . $txt['shipping3']."<br />".nl2br($notes);
		 }
	
		 // first the shipping description
		 $query = sprintf("SELECT * FROM `".$dbtablesprefix."shipping` WHERE `id` = %s", quote_smart($shippingid));
		 $sql = mysql_query($query) or die(mysql_error());
		 
		 while ($row = mysql_fetch_row($sql)) {
				$shipping_descr = $row[1];
		 }
		 
		 // read the shipping costs
		 $query = sprintf("SELECT * FROM `".$dbtablesprefix."shipping_weight` WHERE `ID` = %s", quote_smart($weightid));
		 $sql = mysql_query($query) or die(mysql_error());
		 
		 while ($row = mysql_fetch_row($sql)) {
				$sendcosts = $row[4];
		 }
		 include ($lang_file); // update sendcost in language file
		 $message .= '<tr><td>'.$txt['checkout16'].'</td><td>'.$shipping_descr.'</td><td style="text-align: right">'.$currency_symbol_pre.myNumberFormat($sendcosts,$number_format).$currency_symbol_post.'</td></tr>';
		 
		 $total = $total + $sendcosts;
		 $totalprint = myNumberFormat($total);
		 $print_sendcosts = myNumberFormat($sendcosts);
		 $total_nodecimals = number_format($total, 2,"","");
		 include ($lang_file);
		 $message .= '<tr><td>'.$txt['checkout24'].'</td><td>'.$txt['checkout25'].'</td><td style="text-align: right"><big><strong>'.$currency_symbol_pre.myNumberFormat($total,$number_format).$currency_symbol_post.'</strong></big></td></tr>';
		 $message .= "</table><br /><br />";
		 
		 // shippingmethod 2 is pick up at store. if you don't support this option, there is no need to remove this
		 if ($shippingid == "2") { // pickup from store
			 $message .= $txt['checkout18']; // appointment line
		 }
		 else {
			 $message .= $txt['checkout17']; // shipping address
		 }
		 $message = $message . $txt['checkout6'].$txt['checkout6']; // white line
		 
		 // now lets calculate the invoice total now we know the final addition, the shipping costs     
		 
		 // now the payment
		 $query = sprintf("SELECT * FROM `".$dbtablesprefix."payment` WHERE `id` = %s", quote_smart($paymentid));
		 $sql = mysql_query($query) or die(mysql_error());
		 
		 // read the payment method
		 while ($row = mysql_fetch_row($sql)) {
				$payment_descr = $row[1];
				$payment_code = $row[2];
				// there could be some variables in the code, like %total%, %webid% and %shopurl% so lets update them with the correct values
				$payment_code = str_replace("%total_nodecimals%", $total_nodecimals, $payment_code);
				$payment_code = str_replace("%total%", $total, $payment_code);
				$payment_code = str_replace("%webid%", $webid, $payment_code);
				$payment_code = str_replace("%shopurl%", $shopurl, $payment_code);
		 }
		 $message .= $txt['checkout19'].$payment_descr; // Payment method:
		 $message .= $txt['checkout6']; // line break
		 
		 // paypal and ideal use extra code to checkout. if there is extra code, then we paste it here
		 if ($payment_code <> "") {
			 $message .= $payment_code;
			 $message .= $txt['checkout6']; // line break
		 }
		 
		 // the two standard build in payment methods
		 if ($paymentid == "1") {
			 $message = $message . $txt['checkout20']; // bank info
		 }
		 if ($paymentid == "2") {
			 $message = $message . $txt['checkout21']; // cash payment
		 }
		 
		 // if the payment method is 'pay at the store', you don't need to pay within 14 days
		 if ($paymentid <> "2") { 
			 $message .= $txt['checkout6'].$txt['checkout6']; // new line
			 $message .= $txt['checkout26'];  // pay within xx days 
		 }
		 
		 $message .= $txt['checkout6']; // white line
		 $message .= $txt['checkout9']; // direct link to customer order for online status checking
	
		 // order update
		 $query = "UPDATE `".$dbtablesprefix."order` SET `TOPAY` = '".$total."' WHERE `ID` = ".$lastid;
		 $sql = mysql_query($query) or die(mysql_error());
	
		 //basket update
		 $query = sprintf("UPDATE `".$dbtablesprefix."basket` SET `ORDERID` = '".$lastid."' WHERE (`CUSTOMERID` = %s AND `ORDERID` = '0')", quote_smart($customerid));
		 $sql = mysql_query($query) or die(mysql_error());
		 
		 
		// now lets show the customer some details
		echo "<h4><img src=\"".$gfx_dir."/1_.gif\" alt=\"1\">&nbsp;<img src=\"".$gfx_dir."/2_.gif\" alt=\"step 2\">&nbsp;<img src=\"".$gfx_dir."/3_.gif\" alt=\"3\">&nbsp;<img src=\"".$gfx_dir."/4_.gif\" alt=\"4\">&nbsp;<img src=\"".$gfx_dir."/arrow.gif\" alt=\"arrow\">&nbsp;<img src=\"".$gfx_dir."/5.gif\" alt=\"5\"></h4><br /><br />";
		
		 // make pdf
		 $pdf = "";
		 $fullpdf = "";
		 if ($create_pdf == 1) {
			 require_once("./addons/dompdf/dompdf_config.inc.php");
			 $dompdf = new DOMPDF();
			 $dompdf->load_html($message);
			 $dompdf->render();
			 $output = $dompdf->output();
			 $random = CreateRandomCode(5);
			 $pdf = $webid."_".$random.".pdf";
			 $fullpdf = $orders_dir."/".$pdf;
			 file_put_contents($fullpdf, $output);
			 $query = "UPDATE `".$dbtablesprefix."order` SET `PDF` = '".$pdf."' WHERE `ID` = ".$lastid;
			 $sql = mysql_query($query) or die(mysql_error());
		 }
		 
		 // email subject
		 $subject       = $txt['checkout10'];
		 
		 if (mymail($sales_mail, $to, $subject, $message, $charset)) {
			PutWindow($gfx_dir, $txt['general13'], $txt['checkout11'], "notify.gif", "50");
		 }
		 else { PutWindow($gfx_dir, $txt['general12'], $txt['checkout12'], "warning.gif", "50"); }
		 
		 mymail($sales_mail, $sales_mail, $subject, $message, $charset); // no error checking here, because there is no use to report this to the customer
	
		 // save the order in order folder for administration
		 $security = "<?php if ($"."index_refer <> 1) { exit(); } ?>";
		 $handle = fopen ($orders_dir."/".strval($webid).".php", "w+");
		 if (!fwrite($handle, $security.$message))
			{
			 $retVal = false;
		 }
		 else {
			   fclose($handle);
		 }
		 // now print the confirmation on the screen
		echo '
		     <table width="100%" class="datatable">
		       <caption>'.$txt['checkout13'].'</caption>
		       <tr><td>'.$message.'
		       </td></tr>
		     </table>
		     <h4><a href="printorder.php?orderid='.$lastid.'">'.$txt['readorder1'].'</a>';
			if ($create_pdf == 1) { echo "<br /><a href=\"".$fullpdf."\">".$txt['checkout27']."</a></h4>"; }
       
	}
}
?>     