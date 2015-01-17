<?php

 include ("./includes/startmodules.inc.php"); 

//call library 
require_once ('./includes/lib/nusoap.php'); 

function getallproductinfo()
{

	$query = "SELECT * FROM  aws_product";
	$q2 = mysql_query($query);
	while($r1 = mysql_fetch_array($q2))
	{
	$sql = "SELECT * FROM aws_category where `id` = ".$r1['CATID'];
	$q	= mysql_query($sql);
	while($r = mysql_fetch_array($q)){
	  $items[] = array('PRODUCTID'=>$r1['PRODUCTID'],
                          'DESC'=>$r1['DESCRIPTION'],
						  'CATEGORY'=>$r['DESC'],
                          'GROUPID'=>$r['GROUPID'],
						  'PRICE'=>$r1['PRICE'],
						  'STOCK'=>$r1['STOCK']); 
	}
	}
	return $items;

}

function getcustomerinfo()
{

	$query = "SELECT * FROM  aws_customer ORDER BY `LASTNAME` ASC";
	$q2 = mysql_query($query);
	while($r1 = mysql_fetch_array($q2))
	{
	/*$sql = "SELECT * FROM aws_order where `customerid` = ".$r1['ID'];
	$q	= mysql_query($sql);
	while($r = mysql_fetch_array($q)){*/
	  $items[] = array('LOGINNAME'=>$r1['LOGINNAME'],
                          'ID'=>$r1['ID'],
						  'LASTNAME'=>$r1['LASTNAME'],
                          'INITIALS'=>$r1['INITIALS'],
						  'MIDDLENAME'=>$r1['MIDDLENAME'],
						  'ADDRESS'=>$r1['ADDRESS'],
						  'ZIP'=>$r1['ZIP'],
						  'STATE'=>$r1['STATE'],
						  'COUNTRY'=>$r1['COUNTRY'],
						  'PHONE'=>$r1['PHONE']); 
	//}
	}
	return $items;

}

function order_and_shipping_info($customerid)
{
	
	$query = "SELECT * FROM aws_order WHERE `CUSTOMERID` = ".$customerid;
    $sql = mysql_query($query) or die(mysql_error());
	if (mysql_num_rows($sql) == 0) {
			return "error";
	   	      }
       else {
	       
         while ($row = mysql_fetch_row($sql)) {
		 
			$webid = $row[7];
			$fp = fopen("orders/".$webid.".php", "rb") or die($txt['general6']);
	    $ordertext = fread($fp, filesize("orders/".$webid.".php"));
	       list($security, $order) = split("\?>", $ordertext);
		   $pos = strpos ($order, "<br />");
		if ($pos === false) { $order = nl2br($order); }
		
		list($junk,$important_stufF) = explode("When the products are in stock",$order);
		   
			  $shipping_info = "shipping details:";
			  $ship_query = "SELECT * FROM aws_shipping  WHERE `id`=".$row[3];
	          $ship_sql = mysql_query($ship_query) or die(mysql_error());
              while ($ship_row = mysql_fetch_row($ship_sql)) { $shipping_info .= $ship_row[1]; }
              
	          // find out shipping method
			  $payment_info = "payment_details:";
			  $pay_query = "SELECT * FROM aws_payment WHERE `id` =".$row[4];
	          $pay_sql = mysql_query($pay_query) or die(mysql_error());
              while ($pay_row = mysql_fetch_row($pay_sql)) { $payment_info .= $pay_row[1]; }

			  $items [] = array('SHIPPING_INFO' => $shipping_info,
                          'PAYMENT_INFO' => $payment_info,
                          'ORDER_INFO' => $row[1],
						  'BILL_AMOUNT' => $row[6],
						  'ORDER' => $junk);
			  
                       }
		  return $items;
         }
       
}


//$URL       = "www.test.com";
//$namespace = $URL . '?wsdl';
//using soap_server to create server object
$server    = new soap_server;
//$server->configureWSDL("Authenciation",$namespace);

$server->register("getallproductinfo");
$server->register("getcustomerinfo");
$server->register("order_and_shipping_info");

if ( !isset( $HTTP_RAW_POST_DATA ) ) $HTTP_RAW_POST_DATA =file_get_contents( 'php://input' );
$server->service($HTTP_RAW_POST_DATA);

?>  