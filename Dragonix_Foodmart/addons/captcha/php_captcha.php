<?php
	// make random string and paste it onto the image
	$RandomStr = md5(microtime());// md5 to generate the random string
	$ResultStr = substr($RandomStr,0,5);//trim 5 digit 
	$NewImage =imagecreatefromjpeg("img.jpg");//image create by existing image and as back ground 
	$LineColor = imagecolorallocate($NewImage,233,239,239);//line color 
	$TextColor = imagecolorallocate($NewImage, 255, 255, 255);//text color-white
	imageline($NewImage,1,1,40,40,$LineColor);//create line 1 on image 
	imageline($NewImage,1,100,60,0,$LineColor);//create line 2 on image 
	imagestring($NewImage, 5, 20, 10, $ResultStr, $TextColor);// Draw a random string horizontally 

	// now lets delete captcha files older than 15 minutes:
    if ($handle = @opendir("./")) {
      while (($filename = readdir($handle)) !== false) {
        if(time() - filemtime("./" . $filename) > 15 * 60 && substr($filename, strlen($filename) - 4) == '.key') {
          @unlink("./" . $filename);
        } 
      }
      closedir($handle);
    }
    // now save captcha key as file
    $handle = fopen ("./".$ResultStr.".key", "w+");
	if (!fwrite($handle, "dragonixfoodmart.com"))
	   {
	    $retVal = false;
	}
	else {
	      fclose($handle);
	}
    // output image to browser
	header("Content-type: image/jpeg");// out out the image 
	imagejpeg($NewImage);//Output image to browser 
?>
