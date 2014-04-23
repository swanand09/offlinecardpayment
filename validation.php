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
$total = $context->cart->getOrderTotal(true, Cart::BOTH);
$offlinecardpayment->validateOrder($cart->id,  _PS_OS_PREPARATION_, $total, $offlinecardpayment->displayName, NULL, NULL, $currency->id);
$offlinecardpayment->writePaymentcarddetails($sbmOrderId, $cardholderName, $cvc ,$cardNumber);
$order = new Order($offlinecardpayment->currentOrder);

Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?id_cart='.$cart->id.'&id_module='.$offlinecardpayment->id.'&id_order='.$offlinecardpayment->currentOrder.'&key='.$order->secure_key);
//include_once(dirname(__FILE__).'/../../footer.php');

?>