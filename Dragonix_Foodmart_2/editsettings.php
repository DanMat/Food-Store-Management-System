<?php if ($index_refer <> 1) { exit(); } ?>
<?php
    // admin check
if (IsAdmin() == false) {
  PutWindow($gfx_dir, $txt['general12'], $txt['general2'], "warning.gif", "50");
}
else {   
    if (!empty($_POST['action'])) { $action = $_POST['action']; }
     
    $show = "1";   
    if (!empty($_GET['show'])) { $show = $_GET['show']; }

    if ($action == "save") {
	    // shipping & currency
	    if (!empty($_POST['send_default_country'])) { $send_default_country = $_POST['send_default_country']; }  else { $send_default_country = ""; }
	    if (!empty($_POST['currency'])) { $currency = $_POST['currency'] ; } else { $currency = ""; }
	    if (!empty($_POST['currency_symbol'])) {$currency_symbol = $_POST['currency_symbol'] ; } else { $cuurency_symbol = ""; }
	    if (!empty($_POST['paymentdays'])) {$paymentdays = $_POST['paymentdays']; } else { $paymentdays = 0; }
	    if (!empty($_POST['vat'])) {$vat = $_POST['vat']; } else { $vat = 0; }
	    if (!empty($_POST['show_vat'])) {$show_vat = $_POST['show_vat']; } else { $show_vat = ""; }
	    $db_prices_including_vat = CheckBox($_POST['db_prices_including_vat']); 
	    
	    // shop settings
	    if (!empty($_POST['sales_mail'])) {$sales_mail = $_POST['sales_mail']; } else { $sales_mail = ""; }
	    if (!empty($_POST['shopname'])) {$shopname = $_POST['shopname']; } else { $shopname = ""; }
	    if (!empty($_POST['shopurl'])) {$shopurl = $_POST['shopurl']; } else { $shopurl = ""; }
	    if (!empty($_POST['default_lang'])) {$default_lang = $_POST['default_lang']  ; } else { $default_lang = ""; }
	    if (!empty($_POST['order_prefix'])) {$order_prefix = $_POST['order_prefix']; } else { $order_prefix = ""; }
	    if (!empty($_POST['order_suffix'])) {$order_suffix = $_POST['order_suffix']; } else { $order_suffix = ""; }
	    $stock_enabled = CheckBox($_POST['stock_enabled']);
	    $ordering_enabled = CheckBox($_POST['ordering_enabled']);
	    $shop_disabled = CheckBox($_POST['shop_disabled']);
	    if (!empty($_POST['shop_disabled_title'])) {$shop_disabled_title = $_POST['shop_disabled_title']; } else { $shop_disabled_title = ""; }
	    if (!empty($_POST['shop_disabled_reason'])) {$shop_disabled_reason = $_POST['shop_disabled_reason']; } else { $shop_disabled_reason = ""; }
	    
	    // contact info
	    if (!empty($_POST['webmaster_mail'])) {$webmaster_mail = $_POST['webmaster_mail']; } else { $webmaster_mail = ""; }
	    if (!empty($_POST['shoptel'])) {$shoptel = $_POST['shoptel']; } else { $shoptel = ""; }
	    if (!empty($_POST['shopfax'])) {$shopfax = $_POST['shopfax']; } else { $shopfax = ""; }
	
	    // shop details & bank data
	    if (!empty($_POST['bankaccount'])) {$bankaccount = $_POST['bankaccount']; } else { $bankaccount = ""; }
	    if (!empty($_POST['bankaccountowner'])) {$bankaccountowner = $_POST['bankaccountowner']; } else { $bankaccountowner = ""; }
	    if (!empty($_POST['bankcity'])) {$bankcity = $_POST['bankcity']; } else { $bankcity = ""; }
	    if (!empty($_POST['bankcountry'])) {$bankcountry = $_POST['bankcountry']; } else { $bankcountry = ""; }
	    if (!empty($_POST['bankname'])) {$bankname = $_POST['bankname']; } else { $bankname = ""; }
	    if (!empty($_POST['bankiban'])) {$bankiban = $_POST['bankiban']; } else { $bankiban = ""; }
	    if (!empty($_POST['bankbic'])) {$bankbic = $_POST['bankbic']; } else { $bankbic = ""; }
	    if (!empty($_POST['start_year'])) {$start_year = $_POST['start_year']; } else { $start_year = 2006; }
	   
	    // some pictures we need to specify
	    if (!empty($_POST['shop_logo'])) {$shop_logo = $_POST['shop_logo']; } else { $shop_logo = ""; }
	    
	    // some strings you might want to change
	    if (!empty($_POST['slogan'])) {$slogan = $_POST['slogan']; } else { $slogan = ""; }
	    if (!empty($_POST['page_title'])) {$page_title = $_POST['page_title']; } else { $page_title = ""; }
	    if (!empty($_POST['page_footer'])) {$page_footer = $_POST['page_footer']; } else { $page_footer = ""; }
	    
	    if (!empty($_POST['number_format'])) {$number_format = $_POST['number_format']; } else { $number_format = ""; }
	    if (!empty($_POST['max_description'])) {$max_description = $_POST['max_description']; } else { $max_description = 0; }
	    $no_vat = CheckBox($_POST['no_vat']);
	    if (!empty($_POST['pricelist_format'])) {$pricelist_format = $_POST['pricelist_format']; } else { $pricelist_format = 0; }
	    if (!empty($_POST['date_format'])) {$date_format = $_POST['date_format']; } else { $date_format = ""; }
	    $search_prodgfx = CheckBox($_POST['search_prodgfx']);
	    $use_prodgfx = CheckBox($_POST['use_prodgfx']);
	    
	    // new in 2.1
  	    $thumbs_in_pricelist = CheckBox($_POST['thumbs_in_pricelist']);
	    if (!empty($_POST['keywords'])) {$keywords = $_POST['keywords']; } else { $keywords = ""; }
  	    $conditions_page = CheckBox($_POST['conditions_page']);
  	    $guarantee_page = CheckBox($_POST['guarantee_page']);
  	    $shipping_page = CheckBox($_POST['shipping_page']);
  	    
  	    // new in 2.2
	    if (!empty($_POST['pictureid'])) {$pictureid = $_POST['pictureid']; } else { $pictureid = 0; }
  	    $aboutus_page = CheckBox($_POST['aboutus_page']);
  	    $live_news = CheckBox($_POST['live_news']);
	    if (!empty($_POST['pricelist_thumb_width'])) {$pricelist_thumb_width = $_POST['pricelist_thumb_width']; } else { $pricelist_thumb_width = 0; }
	    if (!empty($_POST['pricelist_thumb_height'])) {$pricelist_thumb_height = $_POST['pricelist_thumb_height']; } else { $pricelist_thumb_height = 0; }
	    if (!empty($_POST['category_thumb_width'])) {$category_thumb_width = $_POST['category_thumb_width']; } else { $category_thumb_width = 0; }
	    if (!empty($_POST['category_thumb_height'])) {$category_thumb_height = $_POST['category_thumb_height']; } else { $category_thumb_height = 0; }
	    if (!empty($_POST['product_max_width'])) {$product_max_width = $_POST['product_max_width']; } else { $product_max_width = 9999; }
	    if (!empty($_POST['product_max_height'])) {$product_max_height = $_POST['product_max_height']; } else { $product_max_height = 9999; }

	    // new in 2.2.4
	    $new_page = CheckBox($_POST['new_page']); 
	    if (!empty($_POST['use_wysiwyg'])) {$use_wysiwyg = $_POST['use_wysiwyg']; } else { $use_wysiwyg = 1; } //default is advanced
	    $make_thumbs = CheckBox($_POST['make_thumbs']); 
	    if (!empty($_POST['description'])) {$description = $_POST['description']; } else { $description = ""; }
	    if (!empty($_POST['products_per_page'])) {$products_per_page = $_POST['products_per_page']; } else { $products_per_page = 0; }
	    
	    // new in 2.2.5
	    $use_captcha = CheckBox($_POST['use_captcha']);
	    $use_imagepopup = CheckBox($_POST['use_imagepopup']);
	    
	    // new in 2.2.6
  	    if (!empty($_POST['currency_pos'])) {$currency_pos = $_POST['currency_pos']; } else { $currency_pos = 1; } // default = 1 ($1234)
	    if (!empty($_POST['template'])) {$template = $_POST['template']; } else { $template = "default"; }
		
		// new in 2.2.7
	    $use_stock_warning = CheckBox($_POST['use_stock_warning']);
	    if (!empty($_POST['stock_warning_level'])) {$stock_warning_level = $_POST['stock_warning_level']; } else { $stock_warning_level = 1; } //default warning level =1
	    if (!empty($_POST['weight_metric'])) {$weight_metric = $_POST['weight_metric']; } else { $weight_metric = ""; }
	    $order_from_pricelist = CheckBox($_POST['order_from_pricelist']);
	    $use_datefix = CheckBox($_POST['use_datefix']);
		
		// new in 2.2.8
	    if (!empty($_POST['orderby'])) {$orderby = $_POST['orderby']; } else { $orderby = 2; } //default is price
	    $autosubmit = CheckBox($_POST['autosubmit']);
	    $create_pdf = CheckBox($_POST['create_pdf']);
		
		// new in 2.2.9
	    $use_phpmail = CheckBox($_POST['use_phpmail']);
	    $hide_outofstock = CheckBox($_POST['hide_outofstock']);
	    $show_stock = CheckBox($_POST['show_stock']);
		
	    // save settings to db
	    $query = "UPDATE `".$dbtablesprefix."settings` SET ";
	    
	    if ($show == "1" || $show == "all") {
		    $query = $query.
		    "`currency` = '".$currency."', ".
		    "`currency_symbol` = '".$currency_symbol."', ".
		    "`paymentdays` = ".$paymentdays.", ".
		    "`no_vat` = ".$no_vat.", ".
		    "`vat` = ".$vat.", ".
		    "`show_vat` = '".$show_vat."', ".
		    "`db_prices_including_vat` = ".$db_prices_including_vat.", ".
		    "`order_prefix` = '".$order_prefix."', ".
		    "`order_suffix` = '".$order_suffix."', ".
		    "`number_format` = '".$number_format."', ".
		    "`currency_pos` = ".$currency_pos.", ".
		    "`date_format` = '".$date_format."', ".
		    "`weight_metric` = '".$weight_metric."', ".
		    "`autosubmit` = ".$autosubmit.", ".
			"`create_pdf` = ".$create_pdf;
	    }
	    if ($show == "all") { $query = $query . ", "; }
	    
	    if ($show == "2" || $show == "all") {
		    $query = $query.
		    "`sales_mail` = '".$sales_mail."', ".
		    "`shopname` = '".$shopname."', ".
		    "`shopurl` = '".$shopurl."', ".
		    "`default_lang` = '".$default_lang."', ".
		    "`send_default_country` = '".$send_default_country."', ".
		    "`stock_enabled` = ".$stock_enabled.", ".
		    "`ordering_enabled` = ".$ordering_enabled.", ".
		    "`shop_disabled` = ".$shop_disabled.", ".
		    "`shop_disabled_title` = '".$shop_disabled_title."', ".
		    "`shop_disabled_reason` = '".$shop_disabled_reason."', ".
		    "`webmaster_mail` = '".$webmaster_mail."', ".
		    "`shoptel` = '".$shoptel."', ".
		    "`shopfax` = '".$shopfax."', ".
		    "`start_year` = ".$start_year.", ".
		    "`keywords` = '".$keywords."', ".
		    "`description` = '".$description."', ".
		    "`pictureid` = ".$pictureid.", ".
		    "`use_captcha` = ".$use_captcha.", ".
		    "`use_stock_warning` = ".$use_stock_warning.", ".
		    "`stock_warning_level` = '".$stock_warning_level."', ".
			"`use_datefix` = ".$use_datefix.", ".
			"`use_phpmail` = ".$use_phpmail;
	    }
	    if ($show == "all") { $query = $query . ", "; }
	    
	    if ($show == "3" || $show == "all") {
		    $query = $query.
		    "`bankaccount` = '".$bankaccount."', ".
		    "`bankaccountowner` = '".$bankaccountowner."', ".
		    "`bankname` = '".$bankname."', ".
		    "`bankcity` = '".$bankcity."', ".
		    "`bankcountry` = '".$bankcountry."', ".
		    "`bankiban` = '".$bankiban."', ".
		    "`bankbic` = '".$bankbic."'";
	    }
	    if ($show == "all") { $query = $query . ", "; }
	    
	    if ($show == "4" || $show == "all") {
		    $query = $query.
		    "`shop_logo` = '".$shop_logo."', ".
		    "`slogan` = '".$slogan."', ".
		    "`page_title` = '".$page_title."', ".
		    "`page_footer` = '".$page_footer."', ".
		    "`max_description` = ".$max_description.", ".
		    "`pricelist_format` = ".$pricelist_format.", ".
		    "`search_prodgfx` = ".$search_prodgfx.", ".
		    "`use_prodgfx` = ".$use_prodgfx.", ".
		    "`thumbs_in_pricelist` = ".$thumbs_in_pricelist.", ".
		    "`make_thumbs` = ".$make_thumbs.", ".
		    "`use_wysiwyg` = ".$use_wysiwyg.", ".
		    "`conditions_page` = ".$conditions_page.", ".
		    "`guarantee_page` = ".$guarantee_page.", ".
		    "`shipping_page` = ".$shipping_page.", ".
		    "`aboutus_page` = ".$aboutus_page.", ".
		    "`new_page` = ".$new_page.", ".
		    "`live_news` = ".$live_news.", ".
		    "`products_per_page` = ".$products_per_page.", ".
		    "`pricelist_thumb_width` = ".$pricelist_thumb_width.", ".
		    "`pricelist_thumb_height` = ".$pricelist_thumb_height.", ".
		    "`category_thumb_width` = ".$category_thumb_width.", ".
		    "`category_thumb_height` = ".$category_thumb_height.", ".
		    "`product_max_width` = ".$product_max_width.", ".
		    "`product_max_height` = ".$product_max_height.", ".
		    "`use_imagepopup` = ".$use_imagepopup.", ".
		    "`template` = '".$template."', ".
			"`order_from_pricelist` = ".$order_from_pricelist.", ".
			"`hide_outofstock` = ".$hide_outofstock.", ".
			"`show_stock` = ".$show_stock.", ".
			"`pricelist_orderby` = ".$orderby;
	    }

	    $sql = mysql_query($query) or die(mysql_error());
        PutWindow($gfx_dir, $txt['general13'],$txt['editsettings44'], "notify.gif", "50");
     }
?>
            <h4> 
            <a href="index.php?page=editsettings&show=1"><?php echo $txt['editsettings48']; ?></a> |
            <a href="index.php?page=editsettings&show=2"><?php echo $txt['editsettings47']; ?></a> |
            <a href="index.php?page=editsettings&show=3"><?php echo $txt['editsettings45']; ?></a> |
            <a href="index.php?page=editsettings&show=4"><?php echo $txt['editsettings46']; ?></a> |
            <a href="index.php?page=editsettings&show=all"><?php echo $txt['editsettings86']; ?></a>
            </h4>
            <br /><br />            
	        <table width="80%" class="datatable">
	          <caption><?php echo $txt['editsettings1']; ?></caption>
             <tr><td>
                 <table width="100%" class="borderless">
                      <form method="POST" action="index.php?page=editsettings&show=<?php echo $show; ?>">
                      <input type="hidden" name="action" value="save">
                      
        	      <?php
                  if ($show == "1" || $show == "all") {
	              ?>
        	      <tr><td colspan="2"><h6><?php echo $txt['editsettings48'] ?></h6>
        	      <br />
        	      </td></tr>
	              <tr><td><?php echo $txt['editsettings8'] ?></td>
	                  <td><input type="text" name="currency" size="10" maxlength="10" value="<?php echo $currency ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings9'] ?></td>
	                  <td><input type="text" name="currency_symbol" size="3" maxlength="10" value="<?php echo $currency_symbol ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings10'] ?></td>
	                  <td><input type="text" name="paymentdays" size="4" maxlength="4" value="<?php echo $paymentdays ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings51'] ?></td>
	                  <td><input type="checkbox" name="no_vat" <?php if ($no_vat == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings11'] ?></td>
	                  <td><input type="text" name="vat" size="5" maxlength="5" value="<?php echo $vat ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings12'] ?></td>
	                  <td><input type="text" name="show_vat" size="10" maxlength="10" value="<?php echo $show_vat ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings13'] ?></td>
	                  <td><input type="checkbox" name="db_prices_including_vat" <?php if ($db_prices_including_vat == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings18'] ?></td>
	                  <td><input type="text" name="order_prefix" size="10" maxlength="10" value="<?php echo $order_prefix ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings19'] ?></td>
	                  <td><input type="text" name="order_suffix" size="10" maxlength="10" value="<?php echo $order_suffix ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings49'] ?></td>
	                  <td>
                        <SELECT NAME="number_format">
                          <OPTION VALUE="<?php echo $number_format ?>" SELECTED><?php echo $number_format ?>	                  
                          <OPTION VALUE="1234.56">1234.56
                          <OPTION VALUE="1,234.56">1,234.56
                          <OPTION VALUE="1234,56">1234,56
                          <OPTION VALUE="1.234,56">1.234,56
                        </SELECT>  
	                  </td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings97'] ?></td>
	                  <td>
                        <SELECT NAME="currency_pos">
                          <OPTION VALUE="<?php echo $currency_pos ?>" SELECTED>
                          <?php 
                                if ($currency_pos == 1) { echo "\$1234"; }
                                if ($currency_pos == 2) { echo "1234$"; }
                          ?>
                          <OPTION VALUE="1"><?php echo "\$1234"; ?>
                          <OPTION VALUE="2"><?php echo "1234$"; ?>
                        </SELECT>  
	                  </td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings57'] ?></td>
	                  <td>
                        <SELECT NAME="date_format">
                          <OPTION VALUE="<?php echo $date_format ?>" SELECTED><?php echo $date_format ?>	                  
                          <OPTION VALUE="d-m-Y @ G:i">d-m-Y @ G:i
                          <OPTION VALUE="m-d-Y @ G:i">m-d-Y @ G:i
                        </SELECT>  
	                  </td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings101'] ?></td>
	                  <td><input type="text" name="weight_metric" size="5" maxlength="10" value="<?php echo $weight_metric ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings107'] ?></td>
	                  <td><input type="checkbox" name="autosubmit" <?php if ($autosubmit == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings108'] ?></td>
	                  <td><input type="checkbox" name="create_pdf" <?php if ($create_pdf == 1) { echo "checked"; } ?>></td>
        	      </tr>
        	      <?php
    	          }
                  if ($show == "2" || $show == "all") {
	              ?>    
        	      
        	      <tr><td colspan="2"><h6><?php echo $txt['editsettings47'] ?></h6>
        	      <br />
        	      </td></tr>
	              <tr><td><?php echo $txt['editsettings14'] ?></td>
	                  <td><input type="text" name="sales_mail" size="30" maxlength="500" value="<?php echo $sales_mail ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings15'] ?></td>
	                  <td><input type="text" name="shopname" size="30" maxlength="100" value="<?php echo $shopname ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings16'] ?></td>
	                  <td><input type="text" name="shopurl" size="30" maxlength="100" value="<?php echo $shopurl ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings17'] ?></td>
	                  <td>
                        <SELECT NAME="default_lang">
                          <OPTION VALUE="<?php echo $default_lang ?>" SELECTED><?php echo $default_lang ?>	                  
                           <?php 
                             // detection of language files in the language dir. for each language it finds, it will add a flag :)
           			         if ($dir = @opendir($lang_dir)) {
                                while (($file = readdir($dir)) !== false) {
                                      list($language, $filetype) = split('[.]', $file);
                                      if ($file != "." && $file != ".." && $file != "index.php") {
	                                     echo "<OPTION VALUE=\"".$language."\">".$language;
                                      }
                                }           
                                closedir($dir);
                             }
        	               ?>
        	            </SELECT>
        	          </td>
        	      </tr>         
	              <tr><td><?php echo $txt['editsettings4'] ?></td>
	                  <td>
                        <SELECT NAME="send_default_country" WIDTH="200" STYLE="width: 200px">
                          <OPTION VALUE="<?php echo $send_default_country ?>" SELECTED><?php echo $send_default_country ?>	                  
                           <?php 
                             // read countries from countries.txt file
                             $file = file('countries.txt');
                             @array_walk($file, 'file_trim');
                             while (list($key, $val) = each($file)) {
                                     echo "<OPTION VALUE=\"".$val."\">".$val;
                             }
                          ?>
                        </SELECT>  
	                  </td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings20'] ?></td>
	                  <td><input type="checkbox" name="stock_enabled" <?php if ($stock_enabled == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings99'] ?></td>
	                  <td><input type="checkbox" name="use_stock_warning" <?php if ($use_stock_warning == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings100'] ?></td>
	                  <td><input type="text" name="stock_warning_level" size="5" maxlength="10" value="<?php echo $stock_warning_level ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings21'] ?></td>
	                  <td><input type="checkbox" name="ordering_enabled" <?php if ($ordering_enabled == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings22'] ?></td>
	                  <td><input type="checkbox" name="shop_disabled" <?php if ($shop_disabled == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings23'] ?></td>
	                  <td><input type="text" name="shop_disabled_title" size="30" maxlength="50" value="<?php echo $shop_disabled_title ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings24'] ?></td>
	                  <td><input type="text" name="shop_disabled_reason" size="30" maxlength="100" value="<?php echo $shop_disabled_reason ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings25'] ?></td>
	                  <td><input type="text" name="webmaster_mail" size="30" maxlength="500" value="<?php echo $webmaster_mail ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings26'] ?></td>
	                  <td><input type="text" name="shoptel" size="30" maxlength="50" value="<?php echo $shoptel ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings27'] ?></td>
	                  <td><input type="text" name="shopfax" size="30" maxlength="50" value="<?php echo $shopfax ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings34'] ?></td>
	                  <td><input type="text" name="start_year" size="4" maxlength="4" value="<?php echo $start_year ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings70'] ?></td>
	                  <td><input type="text" name="keywords" size="30" maxlength="200" value="<?php echo $keywords ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings90'] ?></td>
	                  <td><input type="text" name="description" size="30" maxlength="500" value="<?php echo $description ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings75'] ?></td>
	                  <td>
                        <SELECT NAME="pictureid">
                          <OPTION VALUE="<?php echo $pictureid ?>" SELECTED>
                          <?php 
                                if ($pictureid == 1) { echo $txt['editsettings76']; }
                                if ($pictureid == 2) { echo $txt['editsettings77']; }
                          ?>
                          <OPTION VALUE="1"><?php echo $txt['editsettings76'] ?>
                          <OPTION VALUE="2"><?php echo $txt['editsettings77'] ?>
                        </SELECT>  
	                  </td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings95'] ?></td>
	                  <td><input type="checkbox" name="use_captcha" <?php if ($use_captcha == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings103'] ?></td>
	                  <td><input type="checkbox" name="use_datefix" <?php if ($use_datefix == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings109'] ?></td>
	                  <td><input type="checkbox" name="use_phpmail" <?php if ($use_phpmail == 1) { echo "checked"; } ?>></td>
        	      </tr>
        	      
        	      <?php
    	          }
                  if ($show == "3" || $show == "all") {
	              ?>    
        	      
        	      <tr><td colspan="2"><h6><?php echo $txt['editsettings45'] ?></h6>
        	      <br />
        	      </td></tr>
	              <tr><td><?php echo $txt['editsettings52'] ?></td>
	                  <td><input type="text" name="bankname" size="30" maxlength="50" value="<?php echo $bankname ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings28'] ?></td>
	                  <td><input type="text" name="bankaccount" size="30" maxlength="50" value="<?php echo $bankaccount ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings29'] ?></td>
	                  <td><input type="text" name="bankaccountowner" size="30" maxlength="50" value="<?php echo $bankaccountowner ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings30'] ?></td>
	                  <td><input type="text" name="bankcity" size=30" maxlength="50" value="<?php echo $bankcity ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings31'] ?></td>
	                  <td><input type="text" name="bankcountry" size="30" maxlength="50" value="<?php echo $bankcountry ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings32'] ?></td>
	                  <td><input type="text" name="bankiban" size="30" maxlength="50" value="<?php echo $bankiban ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings33'] ?></td>
	                  <td><input type="text" name="bankbic" size="30" maxlength="50" value="<?php echo $bankbic ?>"></td>
        	      </tr>
        	      
        	      <?php
    	          }
                  if ($show == "4" || $show == "all") {
	              ?>    
        	      
        	      <tr><td colspan="2"><h6><?php echo $txt['editsettings46'] ?></h6>
        	      <br />
        	      </td></tr>
	              <tr><td><?php echo $txt['editsettings98'] ?></td>
	                  <td>
                        <SELECT NAME="template">
                          <OPTION VALUE="<?php echo $template ?>" SELECTED><?php echo $template; ?>
                          <?php 
		        	         if ($dir = @opendir($template_dir)) {
		                		while (($file = readdir($dir)) !== false) {
		                             if ($file != "." && $file != ".." && $file != "index.php") {
									     echo "<OPTION VALUE=".$file.">".$file;
		                             }
		                        }  
		                     closedir($dir);
		                     }
		                  ?>
                        </SELECT>  
	                  </td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings35'] ?></td>
	                  <td><input type="text" name="shop_logo" size="30" maxlength="50" value="<?php echo $shop_logo ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings37'] ?></td>
	                  <td><input type="text" name="slogan" size="30" maxlength="200" value="<?php echo $slogan ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings38'] ?></td>
	                  <td><input type="text" name="page_title" size="30" maxlength="200" value="<?php echo $page_title ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings39'] ?></td>
	                  <td><input type="text" name="page_footer" size="30" maxlength="100" value="<?php echo $page_footer ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings53'] ?></td>
	                  <td>
                        <SELECT NAME="pricelist_format">
                          <OPTION VALUE="<?php echo $pricelist_format ?>" SELECTED><?php 
                                                                                      if ($pricelist_format == 0) { echo $txt['editsettings54']; }
                                                                                      if ($pricelist_format == 1) { echo $txt['editsettings55']; }
                                                                                      if ($pricelist_format == 2) { echo $txt['editsettings56']; }
                                                                                   ?>	                  
                          <OPTION VALUE="0"><?php echo $txt['editsettings54'] ?>
                          <OPTION VALUE="1"><?php echo $txt['editsettings55'] ?>
                          <OPTION VALUE="2"><?php echo $txt['editsettings56'] ?>
                        </SELECT>  
	                  </td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings104'] ?></td>
	                  <td>
                        <SELECT NAME="orderby">
                          <OPTION VALUE="<?php echo $orderby ?>" SELECTED>
                          <?php 
                                if ($orderby == 1) { echo $txt['editsettings105']; }
                                if ($orderby == 2) { echo $txt['editsettings106']; }
                          ?>
                          <OPTION VALUE="1"><?php echo $txt['editsettings105'] ?>
                          <OPTION VALUE="2"><?php echo $txt['editsettings106'] ?>
                        </SELECT>  
	                  </td>
				  </tr>
	              <tr><td><?php echo $txt['editsettings50'] ?></td>
	                  <td><input type="text" name="max_description" size="2" maxlength="2" value="<?php echo $max_description ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings102'] ?></td>
	                  <td><input type="checkbox" name="order_from_pricelist" <?php if ($order_from_pricelist == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings58'] ?></td>
	                  <td><input type="checkbox" name="search_prodgfx" <?php if ($search_prodgfx == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings59'] ?></td>
	                  <td><input type="checkbox" name="use_prodgfx" <?php if ($use_prodgfx == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings69'] ?></td>
	                  <td><input type="checkbox" name="thumbs_in_pricelist" <?php if ($thumbs_in_pricelist == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings89'] ?></td>
	                  <td><input type="checkbox" name="make_thumbs" <?php if ($make_thumbs == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings88'] ?></td>
	                  <td>
                        <SELECT NAME="use_wysiwyg">
                          <OPTION VALUE="<?php echo $use_wysiwyg ?>" SELECTED>
                          <?php 
                                if ($use_wysiwyg == 3) { echo $txt['editsettings92']; }
                                if ($use_wysiwyg == 1) { echo $txt['editsettings93']; }
                                if ($use_wysiwyg == 2) { echo $txt['editsettings94']; }
                          ?>
                          <OPTION VALUE="3"><?php echo $txt['editsettings92'] ?>
                          <OPTION VALUE="1"><?php echo $txt['editsettings93'] ?>
                          <OPTION VALUE="2"><?php echo $txt['editsettings94'] ?>
                        </SELECT>  
	                  </td>
				  </tr>
	              <tr><td><?php echo $txt['editsettings72'] ?></td>
	                  <td><input type="checkbox" name="conditions_page" <?php if ($conditions_page == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings73'] ?></td>
	                  <td><input type="checkbox" name="guarantee_page" <?php if ($guarantee_page == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings74'] ?></td>
	                  <td><input type="checkbox" name="shipping_page" <?php if ($shipping_page == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings78'] ?></td>
	                  <td><input type="checkbox" name="aboutus_page" <?php if ($aboutus_page == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings87'] ?></td>
	                  <td><input type="checkbox" name="new_page" <?php if ($new_page == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings91'] ?></td>
	                  <td><input type="text" name="products_per_page" size="4" maxlength="4" value="<?php echo $products_per_page ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings80'] ?></td>
	                  <td><input type="text" name="pricelist_thumb_width" size="3" maxlength="3" value="<?php echo $pricelist_thumb_width ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings81'] ?></td>
	                  <td><input type="text" name="pricelist_thumb_height" size="3" maxlength="3" value="<?php echo $pricelist_thumb_height ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings82'] ?></td>
	                  <td><input type="text" name="category_thumb_width" size="3" maxlength="3" value="<?php echo $category_thumb_width ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings83'] ?></td>
	                  <td><input type="text" name="category_thumb_height" size="3" maxlength="3" value="<?php echo $category_thumb_height ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings84'] ?></td>
	                  <td><input type="text" name="product_max_width" size="5" maxlength="5" value="<?php echo $product_max_width ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings85'] ?></td>
	                  <td><input type="text" name="product_max_height" size="5" maxlength="5" value="<?php echo $product_max_height ?>"></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings96'] ?></td>
	                  <td><input type="checkbox" name="use_imagepopup" <?php if ($use_imagepopup == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings3'] ?></td>
	                  <td><input type="checkbox" name="hide_outofstock" <?php if ($hide_outofstock == 1) { echo "checked"; } ?>></td>
        	      </tr>
	              <tr><td><?php echo $txt['editsettings5'] ?></td>
	                  <td><input type="checkbox" name="show_stock" <?php if ($show_stock == 1) { echo "checked"; } ?>></td>
        	      </tr>
        	      
        	      <?php
    	          }
	              ?>    
        	      
        	      <tr><td colspan=2><div style="text-align:center;"><br /><br /><input type=submit value="<?php echo $txt['editsettings2'] ?>"></div></td></tr>
                 </form>
                </td>
               </tr>
              </table>
             </tr>
            </table>    
<?php } ?>            