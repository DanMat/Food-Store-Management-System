<?php if ($index_refer <> 1) { exit(); } ?>
<?php
         // read values if needed
         if (!empty($_POST['action'])) {
             // captcha
             if ($use_captcha == 1) {
	             $number = "0"; 
	             $error = 0;
       			 if (!empty($_POST['image_code'])) { $number = $_POST['image_code']; }
      			 if(!file_exists("addons/captcha/".$number.".key") || $number == "0"){
	                PutWindow($gfx_dir, $txt['general12'], $txt['general16'], "warning.gif", "50");
	                $error = 1;
			     }
			     else { unlink ("addons/captcha/".$number.".key"); }
		     }
             $mail = 0;
	         if (!empty($_POST['name'])) {
   	            $name=strip_tags($_POST['name']);
   	            $mail = $mail +1;
	         }
			 else { $error = 1; }
	         if (!empty($_POST['email'])) {
   	            $email=$_POST['email'];
				if (isvalid_email_address($email)) {
					$mail = $mail +1;
				}
				else { $error = 2; }
	         }
			 else { $error = 1; }
	         if (!empty($_POST['message'])) {
   	            $message=strip_tags($_POST['message']);
   	            $mail = $mail +1;
	         }
			 else { $error = 1; }

	         if ($mail == 3 && $error == 0) {
			     // we will also log the ip address, so you can block a notorious spammer
			     $message = "<strong>message from:</strong>&nbsp;".$name." <a href=\"mailto:".$email."\">".$email."</a> <em><".GetUserIP()."></em><br /><br />".nl2br($message);
			     if (mymail($email, $webmaster_mail, $txt['contact1'], $message, $charset)) {
			     PutWindow($gfx_dir, $txt['general13'] , $txt['contact3'], "notify.gif", "90"); }
				 else { echo "Error!"; }
  	         }
  	         else {
                   if ($error == 1) { PutWindow($gfx_dir, $txt['general12'], $txt['contact5'], "warning.gif", "50"); }
                   if ($error == 2) { PutWindow($gfx_dir, $txt['general12'], $txt['customer10'], "warning.gif", "50"); }
  	         }

	     }
?>
    <table width="70%" class="datatable">
      <caption><?php echo $txt['contact6']; ?></caption>

     <tr><td>
          <?php echo $txt['contact7']." ".$shopname; ?><br />
         <br />
          <table class="borderless">
             <tr><td><img src="<?php echo $gfx_dir ?>/mail.gif" alt="" />&nbsp;&nbsp;<a href="mailto:<?php echo $webmaster_mail; ?>"><?php echo $webmaster_mail; ?></a></td></tr>
             <?php if (!$shoptel==NULL) { echo "<tr><td><img src=\"".$gfx_dir."/phone.gif\" alt=\"\" />&nbsp;&nbsp;".$shoptel."</td></tr>"; } ?>
             <?php if (!$shopfax==NULL) { echo "<tr><td><img src=\"".$gfx_dir."/fax.gif\" alt=\"\" />&nbsp;&nbsp;".$shopfax."</td></tr>"; } ?>
          </table>   
         <br />
         <br />
         <?php echo $txt['contact11']; ?><br />

         <form method="POST" action="index.php?page=contact">
	       <?php echo $txt['contact13']; ?><br />
	       <input type="text" name="name" size="25" maxlength="25" value=""><br />
	       <?php echo $txt['contact14']; ?><br />
	       <input type="text" name="email" size="45" maxlength="45" value=""><br />
	       <?php echo $txt['contact15']; ?><br />
	       <textarea name="message" rows=15 cols=50 value=""></textarea><br />
	       <?php
	             if ($use_captcha == 1) {
					 echo "<img src=\"addons/captcha/php_captcha.php\"><br />";
		             echo $txt['general15']." <input type=\"text\" name=\"image_code\" size=\"10\"><br />";
	             }
	       ?>      
           <input type="hidden" name="action" value="mailform">
           <input type="submit" value="<?php echo $txt['contact12']." ".$shopname; ?>">
         </form>
	     <br />
     </td></tr>
    </table>