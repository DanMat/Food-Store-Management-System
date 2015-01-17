<?php if ($index_refer <> 1) { exit(); } ?>
<?php include ("./includes/checklogin.inc.php"); ?>
<?php
if (LoggedIn() == true) {
    // if the cart is empty, then you shouldn't be here
   if (CountCart($customerid) == 0) {
      PutWindow($gfx_dir, $txt['cart1'], $txt['cart2'], "carticon.gif", "50");	   
	  exit();
   }
   
   // the shipping and payment selection is diveded in 2 steps
   $step =1;
   if (!empty($_POST['step'])) {
 	   $step=2;
   }
   if ($step == 2) { list($weightid, $shippingid) = split(":", $_POST['shipping']); }
?>
    <?php
        if ($step == 1) {
			echo "<h4><img src=\"".$gfx_dir."/1_.gif\" alt=\"1\">&nbsp;<img src=\"".$gfx_dir."/arrow.gif\" alt=\"2\">&nbsp;<img src=\"".$gfx_dir."/2.gif\" alt=\"step 2\">&nbsp;<img src=\"".$gfx_dir."/3_.gif\" alt=\"3\">&nbsp;<img src=\"".$gfx_dir."/4_.gif\" alt=\"4\">&nbsp;<img src=\"".$gfx_dir."/5_.gif\" alt=\"5\"></h4><br /><br />";
	?>
	<table width="100%" class="datatable">
    <caption><?php echo $txt['shipping1']; ?></caption>
    <tr><td>
        <form method="post" action="index.php?page=shipping">
         <input type="hidden" name="step" value="2">
          <?php echo $txt['shipping2'] ?><br />
          <SELECT NAME="shipping">
           <?php 
                 // find out the shipping methods
                 $query="SELECT * FROM `".$dbtablesprefix."shipping` ORDER BY `id`";
                 $sql = mysql_query($query) or die(mysql_error());
     
                 while ($row = mysql_fetch_row($sql)) {
	                    // there must be at least 1 payment option available, so lets check that
		                $pay_query="SELECT * FROM `".$dbtablesprefix."shipping_payment` WHERE `shippingid`=".$row[0];
		                $pay_sql = mysql_query($pay_query) or die(mysql_error());
	                    if (mysql_num_rows($pay_sql) <> 0) {
	                        if ($row[2] == 0 || ($row[2] == 1 && IsCustomerFromDefaultSendCountry($send_default_country) == 1)) { 
							    // now check the weight and the costs
								$cart_weight = WeighCart($customerid);
								$weight_query = "SELECT * FROM `".$dbtablesprefix."shipping_weight` WHERE '".$cart_weight."' >= `FROM` AND '".$cart_weight."' <= `TO` AND `SHIPPINGID` = '".$row[0]."'";
								$weight_sql = mysql_query($weight_query) or die(mysql_error());
								while ($weight_row = mysql_fetch_row($weight_sql)) { 
		                            echo "<OPTION VALUE=\"".$weight_row[0].":".$row[0]."\">".$row[1]."&nbsp;(".$currency_symbol_pre.myNumberFormat($weight_row[4],$number_format).$currency_symbol_post.")"; 
								}
	                        }
                        }
                 }
           ?>
          </SELECT>
      <?php
        }    
        else {
			echo "<h4><img src=\"".$gfx_dir."/1_.gif\" alt=\"1\">&nbsp;<img src=\"".$gfx_dir."/2_.gif\" alt=\"step 2\">&nbsp;<img src=\"".$gfx_dir."/arrow.gif\" alt=\"2\">&nbsp;<img src=\"".$gfx_dir."/3.gif\" alt=\"3\">&nbsp;<img src=\"".$gfx_dir."/4_.gif\" alt=\"4\">&nbsp;<img src=\"".$gfx_dir."/5_.gif\" alt=\"5\"></h4><br /><br />";
	?>
	<table width="100%" class="datatable">
    <caption><?php echo $txt['shipping1']; ?></caption>
    <tr><td>
        <form method="post" action="index.php?page=discount">
         <input type="hidden" name="shippingid" value="<?php echo $shippingid; ?>">
         <input type="hidden" name="weightid" value="<?php echo $weightid; ?>">
         <?php echo $txt['shipping10'] ?><br />
          <SELECT NAME="paymentid">
           <?php 
                 // find out the payment methods
                 $query="SELECT * FROM `".$dbtablesprefix."shipping_payment` WHERE `shippingid`='".$shippingid."' ORDER BY `paymentid`";
                 $sql = mysql_query($query) or die(mysql_error());
                 
                 while ($row = mysql_fetch_row($sql)) {
		                 $query_pay="SELECT * FROM `".$dbtablesprefix."payment` WHERE `id`='".$row[1]."'";
		                 $sql_pay = mysql_query($query_pay) or die(mysql_error());
		                 
		                 while ($row_pay = mysql_fetch_row($sql_pay)) {
                                echo "<OPTION VALUE=\"".$row_pay[0]."\">".$row_pay[1]; 
                         }
                 }
           ?>
          </SELECT>
         <br />
         <br />
       	 <?php echo $txt['shipping3']."<br /><textarea name=\"notes\" rows=\"15\" cols=\"65\">".$pdescription."</textarea><br />"; ?>
      <?php    
        }
      ?> 
         <br /><br /> 
         <div style="text-align:center;"><input type=submit value="<?php echo $txt['shipping9'] ?> >>"></div>
       </form>
	   </td>
    </tr>
   </table>
<?php } ?>   