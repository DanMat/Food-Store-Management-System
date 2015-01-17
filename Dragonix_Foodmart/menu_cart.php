<?php if ($index_refer <> 1) { exit(); } ?>
<?php
	   echo "<h1>".$txt['menu2']."</h1>\n"; 
	   echo "<ul class=\"navbarVert navbarVertLeft\">\n";
	   echo "<li"; if ($page == "cart") { echo " id=\"active\""; }; echo "><a href=\"?page=cart&action=show\">".$txt['cart5'].": ".CountCart($customerid)."<br />";
	   echo $txt['cart7'].": ".$currency_symbol_pre.myNumberFormat(CalculateCart($customerid), $number_format).$currency_symbol_post."</a></li>";
	   echo "</ul>";
?>