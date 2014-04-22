<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/offlinecardpayment.php');
			

/* Gather submitted payment card details */
$cardholderName     = $_POST['cardholderName'];
$cardNumber         = $_POST['cardNumber'];
$cvc                = $_POST["cvc"];
$sbmOrderId         = $_POST["sbmOrderId"];
$offlinecardpayment = new offlinecardpayment();
$offlinecardpayment->writePaymentcarddetails($sbmOrderId, $cardholderName, $cvc ,$cardNumber);
include_once(dirname(__FILE__).'/../../footer.php');

?>