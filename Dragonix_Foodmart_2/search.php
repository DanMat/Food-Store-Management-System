<?php if ($index_refer <> 1) { exit(); } ?>
  
  <table summary="search form" class="datatable" width="60%">
      <caption><?php echo $txt['search1'] ?></caption>
	  <tr>
	    <td>
	      <form method="post" action="index.php?page=browse">
	       <?php echo $txt['search2'] ?>
	       <input type="text" name="searchfor" size="40">
	       <input type="hidden" name="action" value="search">
	       <br />
	       <?php echo $txt['search3'] ?><br />
	                <SELECT NAME="searchmethod">
	                <OPTION VALUE="AND" SELECTED><?php echo $txt['search4'] ?>
	                <OPTION VALUE="OR"><?php echo $txt['search5'] ?>
	                </SELECT>
	                <br />
	                <br />
	       <div style="text-align:center;"><input type="submit" value="<?php echo $txt['search6'] ?>"></div>
	      </form>
	    </td>  
	  </tr>    
  </table>      