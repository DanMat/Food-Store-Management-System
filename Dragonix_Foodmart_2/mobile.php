<?php 
include ("./includes/startmodules.inc.php"); 
require_once ('./includes/lib/nusoap.php');
$client = new nusoap_client("http://localhost/Dragonix_Foodmart/server.php");
$gatherOrder = new nusoap_client("http://localhost/Dragonix_Foodmart/server.php");
?>
<!DOCTYPE html> 
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<title>Dragonix Foodmart Mobile</title> 
	<link rel="stylesheet" href="https://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.css" />
	<link rel="stylesheet" href="css/style.css" />
	<script src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
	<script src="https://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.js"></script>
	<script src="js/init.js"></script>
</head> 

	
<body> 


<!-- Start of first page: #one -->
<div data-role="page" id="home" data-theme="a">

	<div data-role="header">
		<h1>DRAGONIX FOODMART</h1>
		<?php
		if(LoggedIn()) echo "<a href='logout1.php' data-icon='user' data-theme='b' class='ui-btn-right' data-role='button' >Logout</a>";
		else echo "<a href='#popup' data-icon='user' data-theme='b' class='ui-btn-right' data-role='button' data-rel='dialog' data-transition='pop'>Login</a>";
		?>
	</div><!-- /header -->
	<?php if ($index_refer <> 1) { exit(); } ?>	
<?php
$prod_name = array();

		    echo "<div data-role='content'><ul id='list' data-role='listview' data-inset='true'><li data-role='list-divider'>".$txt['menu15']."</li>";
           // if the category is send, then use that to find out the group
           if ($cat != 0 && $group == 0) { $group = TheGroup($cat); }
           
           $query = "SELECT * FROM `".$dbtablesprefix."group` ORDER BY `NAME` ASC";
	       $sql = mysql_query($query) or die(mysql_error());
			
			
			
           if (mysql_num_rows($sql) == 0) {
	          echo $txt['menu17']; // no groups found
           }
	       else {
                while ($row = mysql_fetch_row($sql)) {
	                  // lets find out how many categories there are in the group
	                  $query_cat = sprintf("SELECT * FROM `".$dbtablesprefix."category` where `GROUPID`=%s ORDER BY `DESC` ASC", quote_smart($row[0]));
                      $sql_cat = mysql_query($query_cat) or die(mysql_error());
                      $row_cat = mysql_fetch_row($sql_cat);
	                  $ahref = "";

                        // if there are more categories in the group, then show the category list
                        if (mysql_num_rows($sql_cat) > 0) {
                            $ahref = "#product".$row[0];
                        }
                        // now show the menu link, if ahref is not empty
                        if ($ahref != "") {
		                    if ($group != $row[0]) { 
				                echo "<li><a data-icon=delete data-transition='slide' href=".$ahref.">" . $row[1] . "</a></li>\n"; 
								array_push($prod_name, $row[1]);
				            }
	                    }
                      
                }
                
           }
		   if(IsAdmin()==true)
			{
			   echo "<li data-role='list-divider'>".$txt['menu9']."</li>";
			   echo "<li><a data-icon=delete data-transition='slide' href='#customers'>" . $txt['admin3'] . "</a></li>\n"; 
			   echo "<li><a data-icon=delete data-transition='slide' href='#order'>" . $txt['details7'] . "</a></li>\n"; 
			}
			echo "</ul>\n";
?>
	</div><!-- /content -->
	
	<div data-role="footer" data-theme="a" data-position="fixed">
		<h6>Dragonix Foodmart | All rights reserved | ©2013-2014</h4>
	</div><!-- /footer -->
</div><!-- /page one -->

<?php
$response = $client->call('getallproductinfo');

if($client->fault)
{
	echo "FAULT: <p>Code: (".$client->faultcode.")</p>";
	echo "String: ".$client->faultstring;
}
else
{
	 
	$r = $response;
	$count = count($r);

    for($i=1;$i<=$r[$count-1]['GROUPID'];$i++){
		echo "<div data-role='page' data-add-back-btn='true' id='product".$i."'><div data-role='header' data-theme='a'>"."<a href='#' data-theme='b' data-role='button' data-icon='back' data-rel='back'>Back</a><h1>".$prod_name[i]."</h1>";
		if(LoggedIn()) echo "<a href='logout1.php' data-icon='user' data-theme='b' class='ui-btn-right' data-role='button' >Logout</a>";
		else echo "<a href='#popup' data-icon='user' data-theme='b' class='ui-btn-right' data-role='button' data-rel='dialog' data-transition='pop'>Login</a>";
		echo "</div>";
		echo "<div data-role='content' class='htFix' data-theme='a'>";
		echo "<ul id='categoryList' data-role='listview' data-inset='true'><li data-role='list-divider'>".$txt['groupadmin22']."</li>";
		$prevDup = '';
		for($j=0;$j<$count;$j++){
			
			if($i == $r[$j]['GROUPID'] && $prevDup != $r[$j]['CATEGORY']) {
				
				echo "<li><a data-icon=delete data-transition='slide' href='#category_".$r[$j]['CATEGORY']."'>" . $r[$j]['CATEGORY'] . "</a></li>\n"; 
				$prevDup = $r[$j]['CATEGORY'];
			}
		}
		echo "</ul>\n";
		echo "</div>";
		echo "<div data-role='footer' data-theme='a' data-position='fixed'><h6>Dragonix Foodmart | All rights reserved | ©2013-2014</h4></div></div>";
	}
	
}
	
?>

<?php
$response = $client->call('getallproductinfo');

if($client->fault)
{
	echo "FAULT: <p>Code: (".$client->faultcode.")</p>";
	echo "String: ".$client->faultstring;
}
else
{
	 
	$r = $response;
	$count = count($r);
	$prevCat = '';
    for($i=1;$i<=$count;$i++){
	if($prevCat != $r[$i]['CATEGORY']) {
			echo "<div data-role='page' data-add-back-btn='true' id='category_".$r[$i]['CATEGORY']."'><div data-role='header' data-theme='a'>"."<a href='#' data-theme='b' data-role='button' data-icon='back' data-rel='back'>Back</a><h1>".$prod_name[i]."</h1>";
			if(LoggedIn()) echo "<a href='logout1.php' data-icon='user' data-theme='b' class='ui-btn-right' data-role='button' >Logout</a>";
			else echo "<a href='#popup' data-icon='user' data-theme='b' class='ui-btn-right' data-role='button' data-rel='dialog' data-transition='pop'>Login</a>";
			echo "</div>";
			echo "<div data-role='content' class='htFix' data-theme='a'>";
			
			for($j=0;$j<$count;$j++){	
				if($r[$i]['CATEGORY'] == $r[$j]['CATEGORY'] ) {
					echo "<div class='orderWrap'>";
					echo "<p class='cusRow'><span class='cusTitle'>Name: </span><span class='cusVal1'>".$r[$j]['PRODUCTID']."</span></p>";
					echo "<p class='cusRow'><span class='cusTitle'>Description: </span><span class='cusVal1'>".$r[$j]['DESC']."</span></p>";
					echo "<p class='cusRow'><span class='cusTitle'>Price: </span><span class='cusVal1'>".$r[$j]['PRICE']."</span></p>";
					echo "<p class='cusRow'><span class='cusTitle'>Stock: </span><span class='cusVal1'>".$r[$j]['STOCK']."</span></p>";
					echo "</div>";
				}
			}
			
			echo "</div>";
			echo "<div data-role='footer' data-theme='a' data-position='fixed'><h6>Dragonix Foodmart | All rights reserved | ©2013-2014</h4></div></div>";
			$prevCat = $r[$i]['CATEGORY'];
		}
	}
	
}
?>

<div data-role="page" data-add-back-btn='true' id="customers" data-theme="a">

	<div data-role="header">
	<a href='#' data-theme='b' data-role='button' data-icon='back' data-rel='back'>Back</a>
		<h1>Customers</h1>
		<?php
		if(LoggedIn()) echo "<a href='logout1.php' data-icon='user' data-theme='b' class='ui-btn-right' data-role='button' >Logout</a>";
		else echo "<a href='#popup' data-icon='user' data-theme='b' class='ui-btn-right' data-role='button' data-rel='dialog' data-transition='pop'>Login</a>";
		?>
	</div><!-- /header -->

	<div data-role="content" data-theme="a">	
		<?php
		$response = $client->call('getcustomerinfo');


if($client->fault)
{
	echo "FAULT: <p>Code: (".$client->faultcode.")</p>";
	echo "String: ".$client->faultstring;
}
else
{
	 
	$r = $response;
	$count = count($r);
	echo "<ul data-role='listview' data-inset='true' data-filter='true' data-autodividers='true'>";
	for($i=0;$i<=$count-1;$i++){
	echo "<li><a href='#cusPop' data-rel='dialog' data-transition='pop' class='cusID' rel='".$r[$i]['LOGINNAME']."|".$r[$i]['ID']."|".$r[$i]['INITIALS']."|".$r[$i]['MIDDLENAME']."|".$r[$i]['ADDRESS']."|".$r[$i]['ZIP']."|".$r[$i]['STATE']."|".$r[$i]['COUNTRY']."|".$r[$i]['PHONE']."'>".$r[$i]['LASTNAME']."</a></li>";
	}
	echo "</ul>";
	}
		?>
	</div><!-- /content -->
	
		<div data-role="footer" data-theme="a" data-position="fixed">
		<h6>Dragonix Foodmart | All rights reserved | ©2013-2014</h4>
	</div><!-- /footer -->
</div><!-- /page popup -->

<!-- Start of third page: #popup -->
<div data-role="page"  id="order">

	<div data-role="header" data-theme="a">
	<a href='#' data-theme='b' data-role='button' data-icon='back' data-rel='back'>Back</a>
		<h1>Customer list</h1>
		<?php
		if(LoggedIn()) echo "<a href='logout1.php' data-icon='user' data-theme='b' class='ui-btn-right' data-role='button' >Logout</a>";
		else echo "<a href='#popup' data-icon='user' data-theme='b' class='ui-btn-right' data-role='button' data-rel='dialog' data-transition='pop'>Login</a>";
		?>
	</div><!-- /header -->

	<div data-role="content" class='htFix' data-theme="a">	
		<?php
		$response = $client->call('getcustomerinfo');


if($client->fault)
{
	echo "FAULT: <p>Code: (".$client->faultcode.")</p>";
	echo "String: ".$client->faultstring;
}
else
{
	 
	$r = $response;
	$count = count($r);
	echo "<ul id='cusListOrder' data-role='listview' data-inset='true' data-filter='true' data-autodividers='true'><li data-role='list-divider'>".$txt['admin3']."</li>";
	for($i=0;$i<=$count-1;$i++){
	echo "<li><a href='#orderDetails' class='orderID' data-transition='slide'>".$r[$i]['LASTNAME']."</a>";
	$orderList = $gatherOrder->call('order_and_shipping_info', array("customerid" => $r[$i]['ID']));
	if($gatherOrder->fault)
	{
		echo "FAULT: <p>Code: (".$gatherOrder->faultcode.")</p>";
		echo "String: ".$gatherOrder->faultstring;
	}
	else
	{
		
		$error = $gatherOrder->getError();
		if ($error) {
			echo "<h2>Error</h2><pre>" . $error . "</pre>";
		}
		
		else{
		$res = $orderList;
		$countOrd = count($res);
		for($j=0;$j<$countOrd;$j++){
			if($res[$j]['ORDER'] != 'e')
			echo "<div class='hidden'>".$res[$j]['ORDER']."</div>";
			else
			echo "<div class='hidden'>User hasn't ordered anything.</div>";
		}		
		}
	}
	
	echo "</li>";
	}
	echo "</ul>";
	}
		?>
	</div><!-- /content -->
	
	<div data-role="footer" data-theme="a" data-position="fixed">
		<h6>Dragonix Foodmart | All rights reserved | ©2013-2014</h4>
	</div><!-- /footer -->
</div><!-- /page popup -->

<!-- Start of third page: #popup -->
<div data-role="page"  id="orderDetails">

	<div data-role="header" data-theme="a">
	<a href='#' data-theme='b' data-role='button' data-icon='back' data-rel='back'>Back</a>
		<h1 id="#cusNameTitle">Order Details</h1>
	<?php
		if(LoggedIn()) echo "<a href='logout1.php' data-icon='user' data-theme='b' class='ui-btn-right' data-role='button' >Logout</a>";
		else echo "<a href='#popup' data-icon='user' data-theme='b' class='ui-btn-right' data-role='button' data-rel='dialog' data-transition='pop'>Login</a>";
	?>
	</div><!-- /header -->

	<div data-role="content" class='htFix' data-theme="a">	
		<div id="orderContainer">
		
		</div>
	</div><!-- /content -->
	
	<div data-role="footer" data-theme="a" data-position="fixed">
		<h6>Dragonix Foodmart | All rights reserved | ©2013-2014</h4>
	</div><!-- /footer -->
</div><!-- /page popup -->

<!-- Start of third page: #popup -->
<div data-role="page"  id="cusPop">

	<div data-role="header" data-theme="a">
		<h1 id="#cusNameTitle">Details</h1>
	</div><!-- /header -->

	<div data-role="content" data-theme="a">	
		<h2>Customer details</h2>
		<p class="cusRow"><span class="cusTitle">Customer name: </span><span class="cusVal"></span></p>
		<p class="cusRow"><span class="cusTitle">Customer id: </span><span class="cusVal"></span></p>
		<p class="cusRow"><span class="cusTitle">Customer last name: </span><span class="cusVal"></span></p>
		<p class="cusRow"><span class="cusTitle">Customer initial: </span><span class="cusVal"></span></p>
		<p class="cusRow"><span class="cusTitle">Customer middle name: </span><span class="cusVal"></span></p>
		<p class="cusRow"><span class="cusTitle">Customer address: </span><span class="cusVal"></span></p>
		<p class="cusRow"><span class="cusTitle">Customer zip: </span><span class="cusVal"></span></p>
		<p class="cusRow"><span class="cusTitle">Customer country: </span><span class="cusVal"></span></p>
		<p class="cusRow"><span class="cusTitle">Customer phone: </span><span class="cusVal"></span></p>
	</div><!-- /content -->
	
	<div data-role="footer" data-theme="a" data-position="fixed">
		<h6>Dragonix Foodmart | All rights reserved | ©2013-2014</h4>
	</div><!-- /footer -->
</div><!-- /page popup -->

<!-- Start of third page: #popup -->
<div data-role="page"  id="popup">

	<div data-role="header" data-theme="a">
		<h1>Login</h1>
	</div><!-- /header -->

	<div data-role="content" data-theme="a">	
		<table width="100%" class="datatable">
		    <caption><?php echo $txt['checklogin1'] ?></caption>
		    <tr><td>
		        <form name="login" method="POST" action="login1.php">
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
	</div><!-- /content -->
	
	<div data-role="footer" data-theme="a" data-position="fixed">
		<h6>Dragonix Foodmart | All rights reserved | ©2013-2014</h4>
	</div><!-- /footer -->
</div><!-- /page popup -->
</body>
</html>