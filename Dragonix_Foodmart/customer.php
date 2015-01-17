<?php if ($index_refer <> 1) { exit(); } ?>
<?php
        if ($action == "show" || $action == "save") {
	         $eror = 0;
	         // read post values if needed
	         if (!empty($_POST['login'])) {
	   	         $login=$_POST['login'];
	         }
	         else { $error =1 ; }
	         if (!empty($_POST['pass1'])) {
		         $pass1=$_POST['pass1'];
	         }
	         else { $error =1 ; }
	         if (!empty($_POST['pass2'])) {
	   	         $pass2=$_POST['pass2'];
	         }
	         else { $error =1 ; }
	         if (!empty($_POST['name'])) {
		         $name=$_POST['name'];
	         }
	         else { $error =1 ; }
	         if (!empty($_POST['initials'])) {
		         $initials=$_POST['initials'];
	         }
	         else { $error =1 ; }
	         if (!empty($_POST['address'])) {
	   	         $address=$_POST['address'];
	         }
	         else { $error =1 ; }
	         if (!empty($_POST['zip'])) {
		         $zip=$_POST['zip'];
	         }
	         else { $error =1 ; }
	         if (!empty($_POST['city'])) {
	   	         $city=$_POST['city'];
	         }
	         else { $error =1 ; }
	         if (!empty($_POST['state'])) {
	   	         $state=$_POST['state'];
	         }
	         else { $error =1 ; }
	         if (!empty($_POST['email'])) {
	   	         $email=$_POST['email'];
	         }
	         else { $error =1 ; }
	         if (!empty($_POST['country'])) {
	   	         $country=$_POST['country'];
	         }
	         else { $error =1 ; }

	         // optional, so don't raise error if null
	         if (!empty($_POST['company'])) {
	   	         $company=$_POST['company'];
	         }
	         else { $company = ""; }
	         if (!empty($_POST['phone'])) {
		         $phone=$_POST['phone'];
	         }
	         else { $phone = ""; }
	         if (!empty($_POST['middle'])) {
	   	         $middle=$_POST['middle'];
	         }
	         else { $middle = ""; }
	         $newsletter = CheckBox($_POST['newsletter']);
         }

         // is it an admin?
         if (IsAdmin() == true) {
	        // customerid can be send from different forms in different ways so we need to check POST and GET
            if (!empty($_POST['customerid'])) {
   	           $customerid=intval($_POST['customerid']);
   	        }
            if (!empty($_GET['customerid'])) {
   	           $customerid=intval($_GET['customerid']);
   	        }
         }

      if ($action=="delete" && IsAdmin() == true) {
	     // are you removing a customer or accidently an admin?
         $query = "SELECT * FROM `".$dbtablesprefix."customer` WHERE ID = " . $customerid ;
         $sql = mysql_query($query) or die(mysql_error());
         $row = mysql_fetch_row($sql); 
         
         if ($row[13] != "ADMIN") {
             $del_query="DELETE FROM `".$dbtablesprefix."customer` WHERE ID = " . $customerid ;
             $del_sql = mysql_query($del_query) or die(mysql_error());
             PutWindow($gfx_dir, $txt['general13'] , $txt['customer2'], "dustbin.gif", "50");
         }
         else {
               PutWindow($gfx_dir, $txt['general12'] , $txt['customer32'], "warning.gif", "50");
         }
      }

      if ($action=="save") {
	      // are all values send?
	      if ($error == 1) {
		      PutWindow($gfx_dir, $txt['general12'], $txt['customer6'] . "<br /><br />" . $txt['customer5'], "warning.gif", "50");
	      }

	      // is the username alpanumeric?
	      if (!ctype_alnum($login) && $error == 0) {
		      PutWindow($gfx_dir, $txt['general12'], $txt['customer7'] . "<br /><br />" . $txt['customer5'], "warning.gif", "50");
		      $error =1;
	      }

	      // did the customer type the password twice the same?
	      if ($pass1<>$pass2 && $error == 0) {
		      PutWindow($gfx_dir, $txt['general12'], $txt['customer8'] . "<br /><br />" . $txt['customer5'], "warning.gif", "50");
		      $error =1;
	      }
          // ok, is it long enough (is it ever?)
	      if (strlen($pass1)< 5 && $error == 0) {
		      PutWindow($gfx_dir, $txt['general12'], $txt['customer9'] . "<br /><br />" . $txt['customer5'], "warning.gif", "50");
		      $error =1;
	      }

	      // is the email address somewhat ok? does it have a @ and a . in it?
	      if (isvalid_email_address($email) == false && $error == 0) {
		      PutWindow($gfx_dir, $txt['general12'], $txt['customer10'] . "<br /><br />" . $txt['customer5'], "warning.gif", "50");
		      $error =1;
	      }

          if ($error == 0) {
		      if (LoggedIn() == false) {
			      // new customer, so we need to check some stuff
			     
	              // captcha, for prevention of robot-users (R2D2)
	              if ($use_captcha == 1) {
		             $number = "0"; 
	       			 if (!empty($_POST['image_code'])) { $number = $_POST['image_code']; }
	      			 if(!file_exists("addons/captcha/".$number.".key") || $number == "0"){
		                 PutWindow($gfx_dir, $txt['general12'], $txt['general16'], "warning.gif", "50");
		                 $error = 1;
				      }
				      else { unlink ("addons/captcha/".$number.".key"); }
			      }
			      
                  // if you would want to check for reserved usernames, THIS would be the place!!!
                  // here you could check for loginnames like: ADMIN, WEBMASTER and bad language.
                  // [.. code not here yet ..]


	      	      // check if the loginname is unique
                  $query = sprintf("SELECT * FROM `".$dbtablesprefix."customer` WHERE `LOGINNAME` = %s", quote_smart($login));
                  $sql = mysql_query($query) or die(mysql_error());
                  if (!mysql_num_rows($sql) == 0) {
	                 PutWindow($gfx_dir, $txt['general12'], $txt['customer29'], "warning.gif", "50");
	                 $error =1;
                  }
	      	      // check if the email address is unique
                  $query = sprintf("SELECT * FROM `".$dbtablesprefix."customer` WHERE `EMAIL` = %s", quote_smart($email));
                  $sql = mysql_query($query) or die(mysql_error());
                  if (!mysql_num_rows($sql) == 0) {
	                 PutWindow($gfx_dir, $txt['general12'], $txt['customer34'], "warning.gif", "50");
                     $error =1;
                  }
                  // everything ok? then lets put the new customer in the database
	              if ($error == 0) {
			          include ($lang_file);
		              $query = sprintf("INSERT INTO `".$dbtablesprefix."customer` ( `LOGINNAME`, `PASSWORD`, `LASTNAME`, `MIDDLENAME`, `INITIALS`, `IP`, `ADDRESS`, `ZIP`, `CITY`, `STATE`, `PHONE`, `EMAIL`, `GROUP`, `COUNTRY`,`COMPANY`,`JOINDATE`,`NEWSLETTER`) VALUES (%s, %s, %s, %s, %s, '".GetUserIP()."', %s, %s, %s, %s, %s, %s, 'CUSTOMER', %s, %s, '".Date($date_format)."', '".$newsletter."')", quote_smart($login), quote_smart(md5($pass1)), quote_smart($name), quote_smart($middle), quote_smart($initials), quote_smart($address), quote_smart($zip), quote_smart($city), quote_smart($state), quote_smart($phone), quote_smart($email), quote_smart($country), quote_smart($company));
                      mymail($webmaster_mail, $webmaster_mail, $txt['customer36'], $txt['customer37']."<br /><br />".$txt['customer12'], $charset);
		              mymail($webmaster_mail, $email, $txt['customer11'], $txt['customer12'], $charset);
	                  $sql = mysql_query($query) or die(mysql_error());
	                  PutWindow($gfx_dir, $txt['general13'], $txt['customer13'], "notify.gif", "50"); // succesfully saved
			          echo "<h4><a href=\"index.php?page=my\">".$txt['customer35']."</a></h4>";  // click here to login
	              }
	          }
	          else {
	              // update existing customer
		          $query = sprintf("UPDATE `".$dbtablesprefix."customer` SET `LOGINNAME` =%s, `PASSWORD` = %s, `LASTNAME` = %s, `MIDDLENAME` = %s, `INITIALS` = %s, `IP` = '".GetUserIP()."', `ADDRESS` = %s, `ZIP` = %s, `CITY` = %s, `STATE` = %s, `PHONE` = %s, `EMAIL` = %s, `COUNTRY` = %s, `COMPANY` = %s, `NEWSLETTER` = '".$newsletter."' WHERE ID = %s", quote_smart($login), quote_smart(md5($pass1)), quote_smart($name), quote_smart($middle), quote_smart($initials), quote_smart($address), quote_smart($zip), quote_smart($city), quote_smart($state), quote_smart($phone), quote_smart($email), quote_smart($country), quote_smart($company), quote_smart($customerid));
	              $sql = mysql_query($query) or die(mysql_error());
	              PutWindow($gfx_dir, $txt['general13'], $txt['customer13'], "notify.gif", "50"); // succesfully saved
     			  $action =  "show";
	          }
          }
      }
      
      $country = $send_default_country; // if it's a new customer, let's suggest this country as the default one.
      
      if ($action == "show" && LoggedIn() == true) {
         $query = sprintf("SELECT * FROM `".$dbtablesprefix."customer` WHERE `ID` = %s", quote_smart($customerid));
         $sql = mysql_query($query) or die(mysql_error());
         $row = mysql_fetch_row($sql);
         $login      = $row[1];
         $pass1      = $row[2];
         $pass2      = $row[2];
         $name       = $row[3];
         $middle     = $row[4];
         $initials   = $row[5];
         $address    = $row[7];
         $zip        = $row[8];
         $city       = $row[9];
         $state      = $row[10];
         $phone      = $row[11];
         $email      = $row[12];
         $country    = $row[14];
         $company    = $row[15];
         $newsletter = $row[17];
      }
      if ($action != "delete" && $action != "save") {
         ?>
		    <table width="80%" class="datatable">
		      <caption><?php echo $txt['customer14']; ?></caption>
		       <tr><td>
                 <table width="100%" class="borderless">

                  <form method="POST" action="index.php?page=customer&action=save">
	              <tr><td><?php echo $txt['customer15'] ?> (*)</td>
	              <?php
	                    if ($action == show && IsAdmin() == false) { echo "<td>" . $login . "<input type=hidden name=login value='" . $login . "'></td>"; }
	                    else {
		                    ?>
		                    <td><input type="text" name="login" size="15" maxlength="15" value="<?php echo $login ?>"></td>
		          <?php } ?>
     	          </tr>
	              <tr><td><?php echo $txt['customer16'] ?> (*)</td>
	                  <td><input type="password" name="pass1" size="10" maxlength="10" value=""> <?php echo $txt['customer33']; ?></td>
        	      </tr>
	              <tr><td><?php echo $txt['customer17'] ?> (*)</td>
	                  <td><input type="password" name="pass2" size="10" maxlength="10" value=""></td>
	              </tr>
	              <tr><td><?php echo $txt['customer18'] ?> (*)</td>
	                  <td><input type="text" name="name" size="30" maxlength="30" value="<?php echo $name ?>"></td>
	              </tr>
	              <tr><td><?php echo $txt['customer19'] ?></td>
	                  <td><input type="text" name="middle" size="10" maxlength="10" value="<?php echo $middle ?>"></td>
	              </tr>
	              <tr><td><?php echo $txt['customer20'] ?> (*)</td>
	                  <td><input type="text" name="initials" size="10" maxlength="10" value="<?php echo $initials ?>"></td>
	              </tr>
	              <tr><td><?php echo $txt['customer30'] ?></td>
	                  <td><input type="text" name="company" size="30" maxlength="70" value="<?php echo $company ?>"></td>
	              </tr>
	              <tr><td><?php echo $txt['customer21'] ?> (*)</td>
	                  <td><input type="text" name="address" size="30" maxlength="75" value="<?php echo $address ?>"></td>
	              </tr>
	              <tr><td><?php echo $txt['customer22'] ?> (*)</td>
	                  <td><input type="text" name="zip" size="15" maxlength="15" value="<?php echo $zip ?>"></td>
	              </tr>
	              <tr><td><?php echo $txt['customer23'] ?> (*)</td>
	                  <td><input type="text" name="city" size="30" maxlength="50" value="<?php echo $city ?>"></td>
	              </tr>
	              <tr><td><?php echo $txt['customer1'] ?> (*)</td>
	                  <td><input type="text" name="state" size="30" maxlength="150" value="<?php echo $state ?>"></td>
	              </tr>
	              <tr><td><?php echo $txt['customer24'] ?> (*)</td>
	                  <td>
                         <SELECT NAME="country">
                          <OPTION VALUE="<?php echo $country ?>" SELECTED><?php echo $country ?>
                            <?php
                             // read countries
                             $file = file('countries.txt');
                             @array_walk($file, 'file_trim');
                             while (list($key, $val) = each($file)) {
                                     if ($val != $country) { echo "<OPTION VALUE=\"".$val."\">".$val; }
                             }
                          ?>
                         </SELECT>
                        </td>
                  </tr>
	              <tr><td><?php echo $txt['customer25'] ?></td>
	                  <td><input type="text" name="phone" size="20" maxlength="20" value="<?php echo $phone ?>"></td>
	              </tr>
	              <tr><td><?php echo $txt['customer26'] ?> (*)</td>
	                  <td><input type="text" name="email" size="30" maxlength="50" value="<?php echo $email ?>"></td>
	              </tr>
	              <tr><td><?php echo $txt['customer38'] ?></td>
	                  <td><input type="checkbox" name="newsletter" <?php if ($newsletter == 1) { echo "checked"; } ?>></td>
	              </tr>
<?php
			      if (LoggedIn() == false) {
				      // new customer, so lets use captcha to make sure it's human ;-)
		             if ($use_captcha == 1) {
						 echo "<tr><td><img src=\"addons/captcha/php_captcha.php\"><br />".$txt['general15']."</td>";
			             echo "<td><input type=\"text\" name=\"image_code\" size=\"10\"></td></tr>";
		             }
			      }
?> 	              
                  <tr><td class="borderless" colspan=2><div style="text-align:center;"><br /><input type="submit" value="<?php echo $txt['customer28'] ?>" name="sub"><br />
                      (<?php echo $txt['customer27'] ?>)</div></td>
	              </tr>
	              <input type="hidden" name="customerid" value="<?php echo $customerid ?>">
                 </form>
               </td></td>
              </table>
            </table>
<?php
      }
?>            