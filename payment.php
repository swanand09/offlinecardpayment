<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/offlinecardpayment.php');

if (!$cookie->isLogged())
    Tools::redirect('authentication.php?back=order.php');
	
$offlinecardpayment = new offlinecardpayment();

echo $offlinecardpayment->execPayment($context);

include_once(dirname(__FILE__).'/../../footer.php');

?>