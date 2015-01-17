<ul>
<?php
	echo "<li"; if ($page == "main") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=main\">" . $txt['menu1'] . "</a></li>";
	if (IsAdmin() == false) {
		echo "<li"; if ($page == "cart") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=cart&action=show\">" . $txt['menu2'] . " (".CountCart($customerid).")</a></li>";
		if ($conditions_page == 1) { echo "<li"; if ($page == "conditions") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=conditions&action=checkout\">" . $txt['menu3'] . "</a></li>"; }
	else { echo "<li><a href=\"index.php?page=shipping\">" . $txt['menu3'] . "</a></li>"; }
	}

	if (IsAdmin() == true) {
		echo "<li"; if ($page == "admin") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=admin\">" . $txt['menu9'] . "</a></li>";
	}
	if (LoggedIn() == true) {    
		echo "<li"; if ($page == "my") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=my&id=".$customerid."\">".$txt['menu10']." ("; PrintUsername($txt['header3']); echo ")</a></li>";
		echo "<li><a href=\"logout.php\">" . $txt['menu11'] . "</a></li>";
	}
	else {
		echo "<li"; if ($page == "my") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=my\">" . $txt['menu12'] . "</a></li>";
		echo "<li"; if ($page == "customer") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=customer&action=new\">" . $txt['menu13'] . "</a></li>";
	}
?>
</ul>