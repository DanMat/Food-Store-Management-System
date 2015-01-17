<?php
	$time = time();
	$lang = $_GET['lang'];
	
	//added to improve redirect after language select
	//parse the redirect page from the lang link
	if (!empty($_GET['redirect_to'])) {
	    $redirect_to = "index.php?".$_GET['redirect_to'];
		$redirect_to = str_replace("**", "=", $redirect_to);
		$redirect_to = str_replace("$$", "&", $redirect_to);
	}else {
		$redirect_to = "index.php";
	}
	if (!$lang == NULL) {
                         if (setcookie ("cookie_lang",$lang, $time+30240000)==TRUE)
                            {
 		                    ?>
                             <html><head><link href="style.css" rel="stylesheet" type="text/css">
                             <META HTTP-EQUIV=Refresh CONTENT="0; URL=<?php echo $redirect_to;?>"></head>
                             <body><p></body></html>
				            <?php
                         }
	                     else
	                         {
                             ?>
                              <html><head><link href="style.css" rel="stylesheet" type="text/css">
                              <META HTTP-EQUIV=Refresh CONTENT="10; URL=index.php"></head>
                              <body><br /><br /><br /><br /><br /><br /><br />
                              <div align=center>Cookie Error!</a></div></body></html>
	                         <?php
	                     }
   }
   else {
	   $lang = $default_lang;
   }
   exit;
?>