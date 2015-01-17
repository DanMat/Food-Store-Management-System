<?php if ($index_refer <> 1) { exit(); } ?>
<?php include ("includes/checklogin.inc.php"); ?>

<?php
if (LoggedIn() == true) {
    PutWindow($gfx_dir, $txt['my3'], $txt['my4'], "personal.jpg", "80");
?>
     <br />
     <br />     
     <table width="80%" class="datatable">
      <caption><?php echo $txt['my5']; ?></caption>
      <tr><td>
           <table width="100%" class="borderless"><tr>
             <td><div style="text-align:center;"><img src="<?php echo $gfx_dir ?>/key.gif" alt="" /><br /><?php echo $txt['my6'] ?>: <?php echo $customerid ?></div></td>
             <td><div style="text-align:center;"><a class="plain" href="index.php?page=customer&action=show"><img src="<?php echo $gfx_dir ?>/customers.gif" alt="" /><br /><?php echo $txt['my7'] ?></a></div></td>
             <?php if(!isAdmin()){ ?><td><div style="text-align:center;"><a class="plain" href="index.php?page=orders&id=<?php echo $customerid; ?>"><img src="<?php echo $gfx_dir; ?>/orders.gif" alt="" /><br /><?php echo $txt['my8'] ?></a></div></td>
             <td><div style="text-align:center;"><a class="plain" href="index.php?page=cart&action=show"><img src="<?php echo $gfx_dir; ?>/carticon.gif" alt="" /><br /><?php echo $txt['my9'] ?></a></div></td>
             <?php } ?>
		   </tr></table>
          </td> 
      </tr>
     </table>
<?php } ?>     