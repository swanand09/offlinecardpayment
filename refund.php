<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/offlinecardpayment.php');

if (!$cookie->isLogged())
    Tools::redirect('authentication.php?back=order.php');
	
$offlinecardpayment = new offlinecardpayment();
$idSbmOrder = $_POST["id_sbmorder"];
$amount     = $_POST["orderAmount"];
echo $offlinecardpayment->refundPayment($idSbmOrder,$amount);

include_once(dirname(__FILE__).'/../../footer.php');

?>