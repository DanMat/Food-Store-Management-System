<?php if ($index_refer <> 1) { exit(); } ?>
<?php
	          $screenshot = "";
	          if ($use_prodgfx == 1) {
	              if ($pictureid == 1) { 
		              $picture = $f_row[0]; 
	              }
	              else { $picture = $f_row[1]; }
	              
	              $thumb = "";
	              
		          if (thumb_exists($product_dir ."/". $picture . ".jpg")) { $thumb = $product_dir ."/". $picture . ".jpg"; }
		          if (thumb_exists($product_dir ."/". $picture . ".gif")) { $thumb = $product_dir ."/". $picture . ".gif"; }
		          if (thumb_exists($product_dir ."/". $picture . ".png")) { $thumb = $product_dir ."/". $picture . ".png"; }
		          
			      if ($thumb == "") { $thumb = $gfx_dir."/nothumb.jpg"; }
				  $screenshot = "<img src=\"".$thumb."\" width=\"100\" height=\"100\" />";
		      }
			  if ($row_count == 1) { echo "<tr>"; }
	          echo '<td width="33%">
			       <h5>'.$f_row[1].'</h5>'.$screenshot.'<br />
				   <br />
                  <form method="get" action="index.php">
                       <input type="hidden" name="prod" value="'.$f_row[0].'">
                       <input type="hidden" name="cat" value="'.$f_row[2].'">
					   <input type="hidden" name="page" value="details">';
	                                       if (!$f_row[4] == 0) {
		                                       if ($no_vat == 1) {
	                                               $in_vat = myNumberFormat($f_row[4]);
	                                               echo "<normal\>" . $txt['details5'] . ": ". $currency_symbol_pre.$in_vat.$currency_symbol_post."</normal>";
			                                   }
			                                   else {    
		                                            if ($db_prices_including_vat == 1) {
		                                               $ex_vat = $f_row[4] / $vat;    
	                                                   $in_vat = myNumberFormat($f_row[4]);
	                                                   $ex_vat = myNumberFormat($ex_vat);
	                                                }
	                                                else {
		                                               $in_vat = $f_row[4] * $vat;    
	                                                   $ex_vat = myNumberFormat($f_row[4]);
	                                                   $in_vat = myNumberFormat($in_vat);
	                                                }
	                                                echo "<strong>" . $txt['details5'] . ": ".$currency_symbol_pre.$in_vat.$currency_symbol_post."</strong>";
	                                                echo "<br /><small>(".$currency_symbol_pre.$ex_vat.$currency_symbol_post." ".$txt['general6']." ".$txt['general5'].")</small>";
	                                           }	
											}   
	                                 
	                   echo '<br /><input name="sub" type="submit" class="button" value="'.$txt['frontpage1'].'" />
                   </form></td>';
				 if ($row_count == $prods_per_row) { echo "</tr>"; } 
?>