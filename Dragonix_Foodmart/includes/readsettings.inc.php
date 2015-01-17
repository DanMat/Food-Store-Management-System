<?php
    // ------------------------------------------------------
    // read the settings from the database
    // ------------------------------------------------------
    $query = "SELECT * FROM `".$dbtablesprefix."settings`";
    $sql = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_row($sql)) {

	    // shipping & currency
	    $send_default_country = $row[1]; 
	    $currency = $row[5]; 
	    $currency_symbol = $row[6]; 
	    $paymentdays = $row[7]; 
	    $vat = $row[8]; 
	    $show_vat = $row[9]; 
	    $db_prices_including_vat = $row[10]; 
	    
	    // shop settings
	    $sales_mail = $row[11];     
	    $shopname = $row[12];
	    $shopurl = $row[13]; 
	    $default_lang = $row[14];   
	    $order_prefix = $row[15]; 
	    $order_suffix = $row[16]; 
	    $stock_enabled = $row[17];
	    $ordering_enabled = $row[18];
	    $shop_disabled = $row[19]; 
	    $shop_disabled_title = $row[20];
	    $shop_disabled_reason = $row[21];
	    
	    // contact info
	    $webmaster_mail = $row[22];
	    $shoptel = $row[23];
	    $shopfax = $row[24];
	
	    // shop details & bank data
	    $bankaccount = $row[25]; // your bankaccount number
	    $bankaccountowner = $row[26];
	    $bankcity = $row[27];
	    $bankcountry = $row[28];
	    $bankname = $row[29];
	    $bankiban = $row[30];
	    $bankbic = $row[31];
	    $start_year = $row[32];   // the year you started your site
	    
	    // some pictures we need to specify
	    $shop_logo = $row[33];
	    
	    // some strings you might want to change
	    $slogan = $row[35];
	    $page_title = $row[36];
	    $page_footer = $row[37];
	    
	    $number_format = $row[41];
	    $max_description = $row[42];
	    $no_vat = $row[43];
	    $pricelist_format = $row[44];
	    $date_format = $row[45];
	    $search_prodgfx = $row[46];
	    $use_prodgfx = $row[47];
	    
	    // new in 2.1
	    $thumbs_in_pricelist = $row[55];
	    $keywords = $row[56];
	    $conditions_page = $row[58];
	    $guarantee_page = $row[59];
	    $shipping_page = $row[60];
	    
	    // new in 2.2
	    $pictureid = $row[61];
	    $aboutus_page = $row[62];
	    $pricelist_thumb_width = $row[64];
	    $pricelist_thumb_height = $row[65];
	    $category_thumb_width = $row[66];
	    $category_thumb_height = $row[67];
	    $product_max_width = $row[68];
	    $product_max_height = $row[69];
	    
	    // new in 2.2.4
	    $new_page = $row[70];
	    $use_wysiwyg = $row[71];
	    $make_thumbs = $row[72];
	    $description = $row[73];
	    $products_per_page = $row[74];
	    
	    // new in 2.2.5
	    $use_captcha = $row[0];
	    $use_imagepopup = $row[75];
	    
	    // new in 2.2.6
	    $currency_pos = $row[76];
	    $template = $row[48];
		
		// new in 2.2.7
        $stock_warning_level = $row[2];              
        $use_stock_warning = $row[3];              
        $weight_metric = $row[4];
	    $order_from_pricelist = $row[49];
	    $use_datefix = $row[50]; if ($use_datefix == 1) { date_default_timezone_set('UTC'); }
		
		// new in 2.2.8
		$orderby = $row[34];
		$autosubmit = $row[38];
		$create_pdf = $row[39];
		
		// new in 2.2.9
		$use_phpmail = $row[40];
		$hide_outofstock = $row[52];
		$show_stock = $row[53];
  }
   
	// currency symbol position
    if ($currency_pos == 1) { 
	    $currency_symbol_pre = $currency_symbol;
	    $currency_symbol_post = "";
    }
    else {
	    $currency_symbol_pre = "";
	    $currency_symbol_post = $currency_symbol;
    }
   
?>