<?php if ($index_refer <> 1) { exit(); } ?>
<?php
	// read product details
    $query = sprintf("SELECT * FROM `".$dbtablesprefix."product` where `ID`=%s", quote_smart($prod));
	$sql = mysql_query($query) or die(mysql_error());
    
	if (mysql_num_rows($sql) == 0) {
		PutWindow($gfx_dir, $txt['general12'], $txt['general9'], "warning.gif", "50");
	}
	else {
	    while ($row = mysql_fetch_row($sql)) {
	          $screenshot = "";
	          
	          if ($use_prodgfx == 1) {
	              if ($pictureid == 1) { 
		              $picture = $row[0]; 
	              }
	              else { $picture = $row[1]; }
	              
	              $thumb = "";
	              
		          if (thumb_exists($product_dir ."/". $picture . ".jpg")) { $thumb = $product_dir ."/". $picture . ".jpg"; }
		          if (thumb_exists($product_dir ."/". $picture . ".gif")) { $thumb = $product_dir ."/". $picture . ".gif"; }
		          if (thumb_exists($product_dir ."/". $picture . ".png")) { $thumb = $product_dir ."/". $picture . ".png"; }
		          
			      if ($thumb == "") { $thumb = $gfx_dir."/nothumb.jpg"; }
			      
		          $size = getimagesize("$thumb");
		          $height = $size[1];
		          $width = $size[0];
		          $resized = 0;
		          if ($height > $product_max_height)
		             {
		               $height = $product_max_height;
		               $percent = ($size[1] / $height);
		               $width = round(($size[0] / $percent));
		               $resized = 1;
		             }
		          if ($width > $product_max_width)
		             {
		               $width = $product_max_width;
		               $percent = ($size[0] / $width);
		               $height = round(($size[1] / $percent));
		               $resized = 1;
		             }
		          if ($resized == 0) { $screenshot = "<img class=\"borderimg\" src=\"".$thumb."\" height=".$height." width=".$width." alt=\"\" />"; }
		          else { 
			          if ($use_imagepopup == 0) {
			          	  $screenshot = "<a href=\"".$thumb."\"><img class=\"borderimg\" src=\"".$thumb."\" height=".$height." width=".$width." alt=\"\"/><br />".$txt['details9']."</a>"; 
			          }
			          else {$screenshot = "<a href=\"".$thumb."\" rel=\"lightbox\" title=\"".$txt['details2'].": ".$row[1]."\"><img class=\"borderimg\" src=\"".$thumb."\" height=".$height." width=".$width." alt=\"\"/><br /><br/>".$txt['details9']."</a>"; }
		          }

		      }
	
	          ?>
	          <table width="85%" class="datatable">
	            <caption><?php echo $txt['details1'] ?></caption>
	           <tr><td>
	               <h5><?php echo $txt['details2'] ?>: <?php echo $row[1] ?></h5><br />
	               <br />
	               <div style="text-align:center;"><?php echo $screenshot; ?>
	               <br />
	               <?php
		               // show extra admin options?
		               $admin_edit = "";
	                   if (IsAdmin() == true) {
	                       $admin_edit = "<br /><br />";
	                       $admin_edit = $admin_edit."<a href=\"?page=productadmin&action=edit_product&pcat=".$cat."&prodid=".$row[0]."\">".$txt['browse7']."</a>";
	                       $admin_edit = $admin_edit."&nbsp;|&nbsp;<a href=\"?page=productadmin&action=delete_product&pcat=".$cat."&prodid=".$row[0]."\">".$txt['browse8']."</a>";
	                       $admin_edit = $admin_edit."&nbsp;|&nbsp;<a href=\"?page=productadmin&action=picture_upload_form&picid=".$picture."\">".$txt['browse10']."</a>";
	                   } 
	               ?>
	                 <br />
	                 <br />
	                 <table class="borderless" width="90%">
	                  <tr><td class="borderless">
	                     <div style="text-align:left;">
		                   <em><strong><?php echo $txt['details4'] ?>:</strong></em>
		                   <ul><li><?php echo nl2br($row[3])." ".$admin_edit ?></li></ul>
		                 </div>
		              </td></tr>
		             </table>
		           </div>      
	               <br />
	               <form method="POST" action="index.php?page=cart&action=add">
	               <div style="text-align:right">
	                <input type="hidden" name="prodid" value="<?php echo $row[0] ?>">
	                <input type="hidden" name="prodprice" value="<?php echo $row[4] ?>">
	                                <?php
	                                       if (!$row[4] == 0) {
		                                       if ($no_vat == 1) {
	                                               $in_vat = myNumberFormat($row[4]);
	                                               echo "<big><strong>" . $txt['details5'] . ": ". $currency_symbol_pre.$in_vat.$currency_symbol_post."</strong></big>";
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
	                                                echo "<big><strong>" . $txt['details5'] . ": ".$currency_symbol_pre.$in_vat.$currency_symbol_post."</strong></big>";
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
	                                   <?php echo $txt['details6'] ?>: <input type="text" size="4" name="numprod" value="1" maxlength="4">&nbsp;<input type="submit" value="<?php echo $txt['details7'] ?>" name="sub">
	                                       <?php } ?>
	               </form>
	               </div>
	               </td>
	           </tr>
	          </table>
	          <?php
	            if (!isset($refermain)) {
		      ?>
	          <br />
	          <h4><a href="javascript:history.go(-1)"><?php echo $txt['details8'] ?></a></h4>
	          <?php
	           }
	       }
    }       
?>