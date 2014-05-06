<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/offlinecardpayment.php');
			

/* Gather submitted payment card details */
$cardBrand          = $_POST["cardBrand"];
$cardholderName     = $_POST['cardholderName'];
$cardNumber         = $_POST['cardNumber'];
$cvc                = $_POST["cvc"];
$sbmOrderId         = $_POST["sbmOrderId"];
$cardExpiration     = $_POST["cardExpiration"];
$offlinecardpayment = new offlinecardpayment();
$total = $context->cart->getOrderTotal(true, Cart::BOTH);

$offlinecardpayment->writePaymentcarddetails($sbmOrderId, $cardholderName, $cvc ,$cardNumber,$cardExpiration);
if(empty($offlinecardpayment->transactionId)){    
  echo Tools::jsonEncode(array("error"=>$offlinecardpayment->sbmOrderMsgSta)); exit;  
}
$transactionArr =  array(
                          "transaction_id"  =>$offlinecardpayment->transactionId,
                          "card_number"     =>$cardNumber,
                          "card_brand"      =>$cardBrand,  
                          "card_expiration" =>$cardExpiration,
                          "card_holder"     =>$cardholderName
                         );
switch($offlinecardpayment->sbmOrderStatus){
    case 0:
        $offlinecardpayment->validateOrder($cart->id,  _PS_OS_ERROR_, $total, $offlinecardpayment->displayName, NULL, $transactionArr, $currency->id);
    break;
    case 1:
        $offlinecardpayment->validateOrder($cart->id,  _PS_OS_PREPARATION_, $total, $offlinecardpayment->displayName, NULL, $transactionArr, $currency->id);
    break;
    case 2:
        $offlinecardpayment->validateOrder($cart->id,  _PS_OS_PAYMENT_, $total, $offlinecardpayment->displayName, NULL, $transactionArr, $currency->id);        
    break;
    case 3:
        $offlinecardpayment->validateOrder($cart->id,  _PS_OS_CANCELED_, $total, $offlinecardpayment->displayName, NULL, $transactionArr, $currency->id);
    break;
    case 4:
        $offlinecardpayment->validateOrder($cart->id,  _PS_OS_REFUND_, $total, $offlinecardpayment->displayName, NULL, $transactionArr, $currency->id);
    break;
    case 6:
        $offlinecardpayment->validateOrder($cart->id,  _PS_OS_ERROR_, $total, $offlinecardpayment->displayName, NULL, $transactionArr, $currency->id);
    break;
}
$offlinecardpayment->updateOrderPayment($transactionArr);
$order = new Order($offlinecardpayment->currentOrder);

//Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?id_cart='.$cart->id.'&id_module='.$offlinecardpayment->id.'&id_order='.$offlinecardpayment->currentOrder.'&key='.$order->secure_key);
echo Tools::jsonEncode(array(
                                "error"=> "none",
                                "redirectUrl"=>__PS_BASE_URI__.'order-confirmation.php?id_cart='.$cart->id.'&id_module='.$offlinecardpayment->id.'&id_order='.$offlinecardpayment->currentOrder.'&key='.$order->secure_key
                              )
                       );exit;

?>