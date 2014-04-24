<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/offlinecardpayment.php');
			

/* Gather submitted payment card details */
$cardBrand          = $_POST["cardBrand"];
$cardholderName     = $_POST['cardholderName'];
$cardNumber         = $_POST['cardNumber'];
$cvc                = $_POST["cvc"];
$sbmOrderId         = $_POST["sbmOrderId"];
$cardExpiration     = $_POST["expDate_Year"].$_POST["expDate_Month"];
$offlinecardpayment = new offlinecardpayment();
$total = $context->cart->getOrderTotal(true, Cart::BOTH);
$offlinecardpayment->writePaymentcarddetails($sbmOrderId, $cardholderName, $cvc ,$cardNumber,$cardExpiration);
$transactionArr =  array(
                          "transaction_id"  =>$offlinecardpayment->transactionId,
                          "card_number"     =>$cardNumber,
                          "card_brand"      =>$cardBrand,  
                          "card_expiration" =>$cardExpiration,
                          "card_holder"     =>$cardholderName
                         );
d($transactionArr);
$offlinecardpayment->validateOrder($cart->id,  _PS_OS_PREPARATION_, $total, $offlinecardpayment->displayName, NULL, $transactionArr, $currency->id);

$order = new Order($offlinecardpayment->currentOrder);

Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?id_cart='.$cart->id.'&id_module='.$offlinecardpayment->id.'&id_order='.$offlinecardpayment->currentOrder.'&key='.$order->secure_key);
//include_once(dirname(__FILE__).'/../../footer.php');

?>