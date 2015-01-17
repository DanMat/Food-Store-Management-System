<?php
    // general settings
    $index_refer = 1; // pages of the site cannot be opened if this value is unset
    error_reporting(E_ALL ^ E_NOTICE); // ^ E_NOTICE
    set_error_handler("user_error_handler");
    
	function CreateRandomCode($len) {
		$chars = "abcdefghijkmnpqrstuvwxyz23456789";
		srand((double)microtime()*1000000);
		$pass = '' ;
		$len++;

		for ($i=0;$i<=$len; $i++) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$pass = $pass . $tmp;
		}
		return $pass;
	}

    function InStr($String,$Find,$CaseSensitive = false) {
	        $i=0;
	        while (strlen($String)>=$i) {
		          unset($substring);
		          if ($CaseSensitive) {
			         $Find=strtolower($Find);
			         $String=strtolower($String);
		          }
		    	  $substring=substr($String,$i,strlen($Find));
		          if ($substring==$Find) return true;
		          $i++;
	        }
			return false;
    }
            
	function user_error_handler($severity, $msg, $filename, $linenum) {
		Global $dbtablesprefix;
		$query = sprintf("INSERT INTO ".$dbtablesprefix."errorlog (severity, message, filename, linenum, time) VALUES('$severity',%s,'".basename($filename)."',$linenum, '".date("F j, Y, g:i a")."')", quote_smart($msg));
		if (basename($filename) != "lang.txt" && $severity != 8 && InStr($msg,"date()",false) == false) { $sql = mysql_query($query) or die(mysql_error()); }
		
		switch($severity) {
			case E_USER_NOTICE:
			     break;
			case E_USER_WARNING:
			     break;
			case E_USER_ERROR:
			     PutWindow ("Fatal Error", $msg." in ".$filename.":".$linenum, "warning.gif", "50");
			     break;
			default:
			     //PutWindow ("Unknown Error", "Unknown error in ".$filename.":".$linenum, "warning.gif", "50");
			     break;
		}
	}

    function createthumb($name,$filename,$new_w,$new_h){
		if (file_exists($filename)) { unlink($filename); }
	    $system=explode('.',$name);
		if (preg_match('/jpg|jpeg/',$system[1])){
			$src_img=imagecreatefromjpeg($name);
		}
		if (preg_match('/png/',$system[1])){
			$src_img=imagecreatefrompng($name);
		}
		if (preg_match('/gif/',$system[1])){
			$src_img=imagecreatefromgif($name);
		}
		$old_x=imageSX($src_img);
		$old_y=imageSY($src_img);
		if ($old_x > $old_y) {
			$thumb_w=$new_w;
			$thumb_h=$old_y*($new_h/$old_x);
		}
		if ($old_x < $old_y) {
			$thumb_w=$old_x*($new_w/$old_y);
			$thumb_h=$new_h;
		}
		if ($old_x == $old_y) {
			$thumb_w=$new_w;
			$thumb_h=$new_h;
		}
			$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
			imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 
		if (preg_match("/jpg|jpeg/",$system[1])) {
			imagejpeg($dst_img,$filename); 
		}
		if (preg_match("/png/",$system[1])) {
			imagepng($dst_img,$filename); 
		}
		if (preg_match("/gif/",$system[1])) {
			imagegif($dst_img,$filename); 
		}
		imagedestroy($dst_img); 
		imagedestroy($src_img); 
        chmod($filename,0644); // new file can sometimes have wrong permissions
	}

    function createallthumbs($gfx_folder,$thumb_w,$thumb_h) {
		$pics=directory($gfx_folder,'jpg,JPG,JPEG,jpeg,png,PNG');
		$pics=ditchtn($pics,'tn_');
		if ($pics[0]!='')
		{
			foreach ($pics as $p)
			{
				createthumb($gfx_folder.'/'.$p,$gfx_folder.'/tn_'.$p,$thumb_w,$thumb_h);
			}
		}

    }
	function directory($dir,$filters) {
		$handle=opendir($dir);
		$files=array();
		if ($filters == "all"){while(($file = readdir($handle))!==false){$files[] = $file;}}
		if ($filters != "all") {
			$filters=explode(",",$filters);
		 	while (($file = readdir($handle))!==false) {
		    	for ($f=0;$f<sizeof($filters);$f++):
			   		$system=explode(".",$file);
			   		if ($system[1] == $filters[$f]){ 
				   		$files[] = $file;
			   		}
		  		endfor;
		 	}
		}
		closedir($handle);
		return $files;
	}

	function ditchtn($arr,$thumbname) {
		foreach ($arr as $item)	{
	 		if (!preg_match("/^".$thumbname."/",$item)){$tmparr[]=$item;}
		}
		return $tmparr;
	}

    function strip_slashes($value) {
	    $value = stripslashes($value);
	    $value = str_replace("/", "[fw$]", $value);
	    $value = str_replace(".", "[fw$]", $value);
	    $value = strip_tags($value);
	    return $value;
    }
    
	function quote_smart($value)
	{
	   if( is_array($value) ) { 
	       return array_map("quote_smart", $value);
	   } else {
	       if( get_magic_quotes_gpc() ) {
	           $value = stripslashes($value);
	       }
	       if( $value == '' ) {
	           $value = '';
	       } 
	       if( !is_numeric($value) || $value[0] == '0' ) {
	           $value = "'".mysql_real_escape_string($value)."'";
	       }
	       return $value;
	   }
	}

    function br2nl($text)
    {
	    $text = preg_replace('/<br\\\\s*?\\/?/i', "\\n", $text); 
	    return str_replace("<br />","\\n",$text); 
    }
    
	function mymail($from,$to,$subject,$message,$charset)
	{
		Global $use_phpmail;
		
		if ($use_phpmail == 1) {
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset='.$charset."\r\n";
			$headers .= 'From: '.$from.' <'.$from.'>' . "\r\n";
			mail($to, $subject, $message, $headers);
			return true;
		}
		else {
			require_once('./addons/email/email.php'); 
			$email = new Email(); 
			$email->addRecipient($to); 
			$email->setSubject($subject); 
			$email->setMessage($message);
			$email->addHeader('MIME-Version', '1.0'); 
			$email->addHeader('Content-type', 'text/html; charset='.$charset); 
			$email->addHeader('From', $from.' <'.$from.'>'); 
			$email->setAnnounceEmail($from); 
			$email->send();
			return true;
		}
	} 
	
    Function CheckBox($check) {
        // returns 1 if checkbox is checked or 0 if unchecked
	    if ($check == "on") { return 1; }
        else { return 0; }
    }
 	 // parameter security. not implemented yet.
 	Function escape_data($data){
             $pattern='-{2,}';
             $data=eregi_replace($pattern,'',$data);
             return $data;
    }
    // format numbers according to settings
    Function myNumberFormat ($aNumber) {
		Global $number_format;
	         if ($number_format == "1234,56") {
		          $aNumber = number_format($aNumber, 2, ',', '');
	         }
	         if ($number_format == "1.234,56") {
		          $aNumber = number_format($aNumber, 2, ',', '.');
	         }
	         if ($number_format == "1234.56") {
		          $aNumber = number_format($aNumber, 2, '.', '');
	         }
	         if ($number_format == "1,234.56") {
		          $aNumber = number_format($aNumber, 2, '.', ',');
	         }
	         return $aNumber;
    }
	// is the id of an admin?          
    Function IsAdmin() {
 			 Global $dbtablesprefix;
             if (!isset($_COOKIE['fws_cust'])) { return false; }
	         $fws_cust = explode("-", $_COOKIE['fws_cust']);
             $customerid = $fws_cust[1];
             $md5pass = $fws_cust[2];
             if (is_null($customerid)) { return false; }
	         $f_query = "SELECT * FROM ".$dbtablesprefix."customer WHERE ID = " . $customerid;
             $f_sql = mysql_query($f_query) or die(mysql_error());
             while ($f_row = mysql_fetch_row($f_sql)) {
                   if ($f_row[13] == "ADMIN" && md5($f_row[2]) == $md5pass) 
				   { 
				   		if ($f_row[6] == GetUserIP()) {
					   		  return true; } 
						else { 
							  return false; }
                   } else 
					{ 
				   		return false; 
					}
             }
             return false;
    }
    // read the language folder and show the flags
    Function ShowFlags($lang_dir,$gfx_dir) {
   			 if ($dir = @opendir($lang_dir)) {
                while (($file = readdir($dir)) !== false) {
                       if ($file != "." && $file != ".." && $file != "index.php") {
							// for redirection to current page after setlang.php
							$redir = $_SERVER['argv'][0];
							if (!empty($redir)){
								$redir = str_replace("=", "**", $redir);
								$redir = str_replace("&", "$$", $redir);
							}
							//added the $redir variable to the lang link
							echo "<a href=\"setlang.php?lang=".$file."&redirect_to=".$redir."\"><img src=\"".$gfx_dir."/flags/".$file.".png\" alt=\"".$file."\" /></a>";
                       }
                }  
                closedir($dir);
             }
    }
    
	// is the visitor logged in?          
    Function LoggedIn() {
 			 Global $dbtablesprefix;
             if (!isset($_COOKIE['fws_cust'])) { return false; }
	         $fws_cust = explode("-", $_COOKIE['fws_cust']);
             $customerid = $fws_cust[1];
             $md5pass = $fws_cust[2];
             if (is_null($customerid)) { return false; }
	         $f_query = "SELECT * FROM ".$dbtablesprefix."customer WHERE ID = " . $customerid;
             $f_sql = mysql_query($f_query) or die(mysql_error());
             while ($f_row = mysql_fetch_row($f_sql)) {
                   if (md5($f_row[2]) == $md5pass) 
				   { 
				   		if ($f_row[6] == GetUserIP()) {
					   		  return true; } 
						else { 
							  return false; }
                   } else 
					{ 
				   		return false; 
					}
             }
             return false;
    }
    
	// print the username
	Function PrintUsername($guestname) {
        if (!isset($_COOKIE['fws_cust'])) {
           echo $guestname;
		}
        else { 
		   $fws_cust = explode("-", $_COOKIE['fws_cust']);
           echo $fws_cust[0];
        }
	}

    // if we know the category but not the group, this is how we find out
    Function TheGroup($TheCat) {
			Global $dbtablesprefix;
	         $g_query = "SELECT * FROM `".$dbtablesprefix."category` WHERE `ID` = ".$TheCat;
             $g_sql = mysql_query($g_query) or die(mysql_error());
             while ($g_row = mysql_fetch_row($g_sql)) {
	             $FoundIt =  $g_row[2];
             }
             return $FoundIt;
    }
    // how many items in the cart?
    Function CountCart($customerid) {
			Global $dbtablesprefix;
             $num_prod=0;
             $query = "SELECT * FROM `".$dbtablesprefix."basket` WHERE (CUSTOMERID=".$customerid." AND ORDERID=0)";
             $sql = mysql_query($query) or die(mysql_error());
				while ($row = mysql_fetch_row($sql)) {
	             $num_prod = $num_prod + $row[6]; 
             }            
             return $num_prod;
    }
    Function CountOrders($customerid) {
			Global $dbtablesprefix;
             $num_orders=0;
             $query = "SELECT * FROM `".$dbtablesprefix."order` WHERE (CUSTOMERID=".$customerid.")";
             $sql = mysql_query($query) or die(mysql_error());
			 $num_orders = mysql_num_rows($sql);
             return $num_orders;
    }
    Function CountAllOrders() {
			Global $dbtablesprefix;
             $num_tot_orders=0;
             $query = "SELECT * FROM `".$dbtablesprefix."order`";
             $sql = mysql_query($query) or die(mysql_error());
			 $num_tot_orders = mysql_num_rows($sql);
             $query = "SELECT * FROM `".$dbtablesprefix."order` WHERE (STATUS<5)"; // orders that need your attention
             $sql = mysql_query($query) or die(mysql_error());
			 $num_att_orders = mysql_num_rows($sql);
             return $num_att_orders."/".$num_tot_orders;
    }
    Function CountCustomers($group) {
			Global $dbtablesprefix;
             $num_customers=0;
             $query = "SELECT * FROM ".$dbtablesprefix."customer WHERE (`GROUP`='".$group."')";
             $sql = mysql_query($query) or die(mysql_error());
			 $num_customers = mysql_num_rows($sql);
             return $num_customers;
    }
    Function CountProducts() {
			Global $dbtablesprefix;
             $num_products=0;
             $query = "SELECT * FROM ".$dbtablesprefix."product";
             $sql = mysql_query($query) or die(mysql_error());
			 $num_products = mysql_num_rows($sql);
             return $num_products;
    }
	Function StockWarning($stock_warning_level) {
			Global $dbtablesprefix;
			$num = 0;
            $query ="SELECT * FROM ".$dbtablesprefix."product WHERE STOCK < ". $stock_warning_level; 
            $sql = mysql_query($query) or die(mysql_error());
			$num = mysql_num_rows($sql);
			return $num;
	}
    // what is the total cart amount?
    Function CalculateCart($customerid) {
              // customer id from cookie
			  Global $dbtablesprefix;
             $total=0;
             $query = "SELECT * FROM ".$dbtablesprefix."basket WHERE (CUSTOMERID=".$customerid." AND ORDERID=0)";
             $sql = mysql_query($query) or die(mysql_error());
				while ($row = mysql_fetch_row($sql)) {
					   $productprice = $row[3]; // the price of a product
					   if (!empty($row[7])) { 
						   // features might involve extra costs, but we don't want to show them
						   $features = explode(", ", $row[7]);
						   $counter1 = 0;
						   while (!$features[$counter1] == NULL){
							   $feature = explode("+",$features[$counter1]);
							   $counter1 += 1;
							   $productprice += $feature[1]; // if there are extra costs, let's add them
						   }
					   }							   
					   $subtotal = $productprice * $row[6];
					   $total = $total + $subtotal;
             }            
             return $total;
    }    
    // what is the total weight of the cart ?
    Function WeighCart($customerid) {
              // customer id from cookie
			  Global $dbtablesprefix;
             $total=0;
             $query = "SELECT * FROM ".$dbtablesprefix."basket WHERE (CUSTOMERID=".$customerid." AND ORDERID=0)";
             $sql = mysql_query($query) or die(mysql_error());
				while ($row = mysql_fetch_row($sql)) {
				         $query = "SELECT * FROM `".$dbtablesprefix."product` where `ID`='" . $row[2] . "'";
				         $sql_details = mysql_query($query) or die(mysql_error());
				         while ($row_details = mysql_fetch_row($sql_details)) {
			                   $subtotal = $row_details[9] * $row[6];
	             			   $total = $total + $subtotal;
             			 }
             }            
             return $total;
    }    
    // general window to display misc. messages
    Function PutWindow($gfx_dir,$title,$message,$picture,$width) {
	         echo "<table width=\"".$width."%\" class=\"datatable\" style='margin-top: 0px'>";
	         echo "<caption>".$title."</caption>";
             echo "<tr><td><img src=\"".$gfx_dir."/".$picture."\" alt=\"".$picture."\"></td>";
             echo "<td>".$message."</td></tr></table>";
             echo "<br /><br />";
	}
    // single window to display misc. messages
    Function PutSingleWindow($title,$message,$width) {
	         echo "<table width=\"".$width."%\" class=\"datatable\">";
	         echo "<caption>".$title."</caption>";
             echo "<tr><td>".$message."</td></tr></table>";
             echo "<br /><br />";
	}
  // is the customer living in the default send country?
   Function IsCustomerFromDefaultSendCountry($f_send_default_country) {
            // determine sendcosts depending on the country of origin
			Global $dbtablesprefix;
            $fws_cust = explode("-", $_COOKIE['fws_cust']);
            $customerid = $fws_cust[1];

            $f_query="SELECT * FROM `".$dbtablesprefix."customer` WHERE `ID` = " . $customerid;
            $f_sql = mysql_query($f_query) or die(mysql_error());
            while ($f_row = mysql_fetch_row($f_sql)) {
                   $country = $f_row[14];
            }
            if ($country == $f_send_default_country) { 
	            return 1;
            }
            else { return 0; }
   }
   // split up a string, used by max_description
   Function stringsplit($the_string, $the_number)  {
	        $startoff_nr = 0;
            $the_output_array = array();
            for($z = 1; $z < ceil(strlen($the_string)/$the_number)+1 ; $z++) {
	           $startoff_nr = ($the_number*$z)-$the_number;
               $the_output_array[] = substr($the_string, $startoff_nr, $the_number);
            }
            return($the_output_array);
   }	   
   // see if url exists (for picture on remote host as well)
   function url_exists($url) {
	       $a_url = parse_url($url);
	       if (!isset($a_url['port'])) $a_url['port'] = 80;
	       $errno = 0;
	       $errstr = '';
	       $timeout = 5;
	       if(isset($a_url['host']) && $a_url['host']!=gethostbyname($a_url['host'])){
	           $fid = @fsockopen($a_url['host'], $a_url['port'], $errno, $errstr, $timeout);
	           if (!$fid) return false;
	           $page = isset($a_url['path'])  ?$a_url['path']:'';
	           $page .= isset($a_url['query'])?'?'.$a_url['query']:'';
	           fputs($fid, 'HEAD '.$page.' HTTP/1.0'."\r\n".'Host: '.$a_url['host']."\r\n\r\n");
	           $head = fread($fid, 4096);
	           fclose($fid);
	           return preg_match('#^HTTP/.*\s+[200|302]+\s#i', $head);
	       } else {
	           return false;
	       }
	}
    // check if local or remote picture exists   
    function thumb_exists($thumbnail) {
	         $pos = strpos($thumbnail, "://");
	         if ($pos === false) { 
		         return file_exists($thumbnail);
	         }
	         else {
	             return url_exists($thumbnail);
	         }
    }
    // get user IP
    function GetUserIP() {
          if (isset($_SERVER)) { if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) 
                                    { $ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; } 
                                 elseif(isset($_SERVER["HTTP_CLIENT_IP"])) 
                                    { $ip = $_SERVER["HTTP_CLIENT_IP"]; } 
                                 else { $ip = $_SERVER["REMOTE_ADDR"]; }
                               }  
          else { if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) 
                      { $ip = getenv( 'HTTP_X_FORWARDED_FOR' ); } 
                 elseif ( getenv( 'HTTP_CLIENT_IP' ) ) 
                      { $ip = getenv( 'HTTP_CLIENT_IP' ); } 
                 else { $ip = getenv( 'REMOTE_ADDR' ); }
               }
          return $ip;     
    }  
    // trim a string        
    function file_trim(&$value, $key){ 
	    $value = trim($value); 
    }
    // check if current user is banned
    function IsBanned() {
             // check ip from database
			 Global $dbtablesprefix;
	         if (!isset($_COOKIE['fws_cust'])) { return false; }
	         $fws_cust = explode("-", $_COOKIE['fws_cust']);
             $customerid = $fws_cust[1];
             if (is_null($customerid)) { return false; }
	         $f_query = "SELECT * FROM ".$dbtablesprefix."customer WHERE ID = " . $customerid;
             $f_sql = mysql_query($f_query) or die(mysql_error());
             while ($f_row = mysql_fetch_row($f_sql)) {
                    $userip = $f_row[6];
             }
             // get current computers ip
             $ip = GetUserIP(); 
             
             // now check both in the banlist
		     $file = file('banned.txt');
		     @array_walk($file, 'file_trim');
		     while (list($key, $val) = each($file)) {
		            if ($ip == $val) { return true; }
		            if ($userip == $val) { return true; }
		     }
		     return false;
    }
	function isvalid_email_address($email) {  
			 // First, we check that there's one @ symbol, and that the lengths are right  
			 if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {    
				// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.    
				return false;  
			}  
			// Split it into sections to make life easier  
			$email_array = explode("@", $email);  
			$local_array = explode(".", $email_array[0]);  
			
			for ($i = 0; $i < sizeof($local_array); $i++) {     
				if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {      
					return false;    
				}  
			}    
			if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { 
				// Check if domain is IP. If not, it should be valid domain name    
				$domain_array = explode(".", $email_array[1]);    
				if (sizeof($domain_array) < 2) {        
					return false; 
					// Not enough parts to domain    
				}    
				for ($i = 0; $i < sizeof($domain_array); $i++) {      
					if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
						return false;      
					}    
				}  
			}  
			return true;
	}
	function is__writable($path) {
	//will work in despite of Windows ACLs bug
	//NOTE: use a trailing slash for folders!!!
	//see http://bugs.php.net/bug.php?id=27609
	//see http://bugs.php.net/bug.php?id=30931

		if ($path{strlen($path)-1}=='/') // recursively return a temporary file path
			return is__writable($path.uniqid(mt_rand()).'.tmp');
		else if (is_dir($path))
			return is__writable($path.'/'.uniqid(mt_rand()).'.tmp');
		// check tmp file for read/write capabilities
		$rm = file_exists($path);
		$f = @fopen($path, 'a');
		if ($f===false)
			return false;
		fclose($f);
		if (!$rm)
			unlink($path);
		return true;
	}
	function gen_rand_value($num)
	{
	// for random session id >> accepts 1 - 36
	  switch($num)
	  {
	    case "1":
	     $rand_value = "a";
	    break;
	    case "2":
	     $rand_value = "b";
	    break;
	    case "3":
	     $rand_value = "c";
	    break;
	    case "4":
	     $rand_value = "d";
	    break;
	    case "5":
	     $rand_value = "e";
	    break;
	    case "6":
	     $rand_value = "f";
	    break;
	    case "7":
	     $rand_value = "g";
	    break;
	    case "8":
	     $rand_value = "h";
	    break;
	    case "9":
	     $rand_value = "i";
	    break;
	    case "10":
	     $rand_value = "j";
	    break;
	    case "11":
	     $rand_value = "k";
	    break;
	    case "12":
	     $rand_value = "l";
	    break;
	    case "13":
	     $rand_value = "m";
	    break;
	    case "14":
	     $rand_value = "n";
	    break;
	    case "15":
	     $rand_value = "o";
	    break;
	    case "16":
	     $rand_value = "p";
	    break;
	    case "17":
	     $rand_value = "q";
	    break;
	    case "18":
	     $rand_value = "r";
	    break;
	    case "19":
	     $rand_value = "s";
	    break;
	    case "20":
	     $rand_value = "t";
	    break;
	    case "21":
	     $rand_value = "u";
	    break;
	    case "22":
	     $rand_value = "v";
	    break;
	    case "23":
	     $rand_value = "w";
	    break;
	    case "24":
	     $rand_value = "x";
	    break;
	    case "25":
	     $rand_value = "y";
	    break;
	    case "26":
	     $rand_value = "z";
	    break;
	    case "27":
	     $rand_value = "1"; // no zeros, because if it starts with a zero, it might get cut off
	    break;
	    case "28":
	     $rand_value = "1";
	    break;
	    case "29":
	     $rand_value = "2";
	    break;
	    case "30":
	     $rand_value = "3";
	    break;
	    case "31":
	     $rand_value = "4";
	    break;
	    case "32":
	     $rand_value = "5";
	    break;
	    case "33":
	     $rand_value = "6";
	    break;
	    case "34":
	     $rand_value = "7";
	    break;
	    case "35":
	     $rand_value = "8";
	    break;
	    case "36":
	     $rand_value = "9";
	    break;
	  }
	return $rand_value;
	}
	
?>