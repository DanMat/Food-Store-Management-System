<?php
	 include ("./includes/startmodules.inc.php");

     if(setcookie ("fws_cust","", time() - 3600)==TRUE)
			{
				?>
                 <html><head><link href="<?php echo $template_dir."/".$template."/stylesheet.css"; ?>" rel="stylesheet" type="text/css">
                 <META HTTP-EQUIV="Refresh" CONTENT="1; URL=index.php"></head>
                 <body><br /><br /><br /><br /><br /><br /><br />
                 <h4><?php echo $txt['logout1'] ?></h4></body></html>
				<?php

			}
?>