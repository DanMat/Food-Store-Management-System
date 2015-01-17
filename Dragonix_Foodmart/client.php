<?php 
include ("./includes/startmodules.inc.php"); 
require_once ('./includes/lib/nusoap.php');

$client = new nusoap_client("http://localhost/Dragonix_Foodmart/server.php");


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

    echo "<table border='1'><tr><th>PRODUCTID</th><th>DESCRIPTION</th><th>CATEGORY</th><th>GroupID</th><th>PRICE</th><th>STOCK</th></tr>";
    for($i=0;$i<=$count-1;$i++){
    echo "<tr>";
    	echo "<td>".$r[$i]['PRODUCTID']."</td>";
    	echo "<td><p>".$r[$i]['DESC']."</p></td>";
		echo "<td>".$r[$i]['CATEGORY']."</td>";
    	echo "<td>".$r[$i]['GROUPID']."</td>";
		echo "<td>".$r[$i]['PRICE']."</td>";
    	echo "<td>".$r[$i]['STOCK']."</td>";
    echo "</tr>";
 
	}
	
    echo "</table><br/>";
	}

	
if(IsAdmin()==true)
{
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
	
    echo "<table border='1'><tr><th>LOGINNAME</th><th>CUSTOMERID</th><th>LASTNAME</th><th>INITIALS</th><th>MIDDLENAME</th><th>ADDRESS</th><th>ZIP</th><th>STATE</th><th>COUNTRY</th><th>PHONE</th></tr>";
    for($i=0;$i<=$count-1;$i++){
    echo "<tr>";
    	echo "<td>".$r[$i]['LOGINNAME']."</td>";
    	echo "<td><p>".$r[$i]['ID']."</p></td>";
		echo "<td>".$r[$i]['LASTNAME']."</td>";
    	echo "<td>".$r[$i]['INITIALS']."</td>";
		echo "<td>".$r[$i]['MIDDLENAME']."</td>";
    	echo "<td>".$r[$i]['ADDRESS']."</td>";
		echo "<td>".$r[$i]['ZIP']."</td>";
		echo "<td>".$r[$i]['STATE']."</td>";
		echo "<td>".$r[$i]['COUNTRY']."</td>";
		echo "<td>".$r[$i]['PHONE']."</td>";
    echo "</tr>";
 
	}
	
    echo "</table><br/>";
	}
	
	if (!empty($_GET['id']))
	        { $customerid = intval($_GET['id']);}
			
$response = $client->call('order_and_shipping_info', array("customerid" => $customerid));
if($client->fault)
{
	echo "FAULT: <p>Code: (".$client->faultcode.")</p>";
	echo "String: ".$client->faultstring;
}
else
{
	
	$error = $client->getError();
    if ($error) {
        echo "<h2>Error</h2><pre>" . $error . "</pre>";
    }
	
	else{
	$r = $response;
	$count = count($r);
    echo "<table border='1'><tr><th>Shipping_INFO</th><th>Payment_info</th><th>Order_info</th><th>Order</th><th>BILL_AMOUNT</th></tr>";
    for($i=0;$i<=$count-1;$i++){
    echo "<tr>";
    	echo "<td>".$r[$i]['SHIPPING_INFO']."</td>";
    	echo "<td>".$r[$i]['PAYMENT_INFO']."</td>";
    	echo "<td>".$r[$i]['ORDER_INFO']."</td>";
		echo "<td>".$r[$i]['ORDER']."</td>";
		echo "<td>".$r[$i]['BILL_AMOUNT']."</td>";
    echo "</tr>";
 
	}
	
    echo "</table>";
	}
}
}
?>
	
	