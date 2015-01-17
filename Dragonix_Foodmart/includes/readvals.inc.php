<?php
    // some values we read through get or post 
	$group = 0;
	$cat   = 0;
	$page  = "main";
	
	if (!empty($_GET['page'])) {
	    $page=htmlspecialchars(strip_slashes($_GET['page']));
	}
	if (!empty($_GET['action'])) {
	    $action=htmlspecialchars(strip_slashes($_GET['action']));
	}
	if (!empty($_POST['action'])) {
	    $action=htmlspecialchars(strip_slashes($_POST['action']));
	}
	if (!empty($_GET['cat'])) {
	    $cat=intval(htmlspecialchars(strip_slashes($_GET['cat'])));
	}
	if (!empty($_GET['prod'])) {
	    $prod=intval(htmlspecialchars(strip_slashes($_GET['prod'])));
	}
	if (!empty($_GET['group'])) {
	    $group=intval(htmlspecialchars(strip_slashes($_GET['group'])));
	}
?>