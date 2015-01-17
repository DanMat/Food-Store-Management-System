<?php if ($index_refer <> 1) { exit(); } ?>
<?php
	//Check if cookie is set
	if (LoggedIn() == false) {
		$pagetoload = $_SERVER['QUERY_STRING'];
        ?>
		  <table width="60%" class="datatable">
		    <caption><?php echo $txt['checklogin1'] ?></caption>
		    <tr><td>
		        <form name="login" method="POST" action="login.php">
		              <input type="hidden" value="<?php echo $pagetoload; ?>" name="pagetoload">
			          <table class="borderless" width="100%">
			                 <tr><td class="borderless"><?php echo $txt['checklogin2'] ?></td>
			                     <td class="borderless"><input type="text" name="name" size="20"></td>
			                 </tr>
			                 <tr><td class="borderless"><?php echo $txt['checklogin3'] ?></td>
			                     <td class="borderless"><input type="password" name="pass" size="20"></td>
			                 </tr>
			          </table>
			          <br />
			          <div style="text-align:center;"><input type="submit" value="<?php echo $txt['checklogin4'] ?>" name="sub"></div>
			          <br />
			          <div style="text-align:right;"><a href="?page=login&lostlogin=1"><?php echo $txt['checklogin11'] ?></a></div>
		  	    </form>
		  	    </td>
		  	</tr>
		  </table>
		  <br />
		  <div style="text-align:center;"><a href="index.php?page=customer&action=new"><?php echo $txt['checklogin5'] ?></a></div>
		  <br />
		  <br />
		  <br />
	 <?php
	      PutWindow($gfx_dir, $txt['checklogin6'], $txt['checklogin7'], "personal.jpg", "90");
	}
?>