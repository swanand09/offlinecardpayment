<?php
include(dirname(__FILE__).'/gateway/PayPlugin.php');
class Offlinecardpayment extends PaymentModule
{
	
	private $_html = '';
	private $_postErrors = array();
	public $transactionId = '';
        public $sbmOrderMsgSta = '';
        public $sbmOrderStatus = '';
        public $pathPayment = '';
	function __construct()
	{
		$this->name = 'offlinecardpayment';
		$this->tab = 'payments_gateways';
		$this->version = 1;              
		parent::__construct(); // The parent construct is required for translations
                
		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('Offline Payments Module');
		$this->description = $this->l('Take Payment Card details for offline processing');
 
	}

		/**
	*	Function install()
	*	Is called when 'Install' in on this module within administration page
	*/
	    
	public function install()
	{
		if (!parent::install()
			OR !$this->createPaymentcardtbl() //calls function to create payment card table
            OR !$this->registerHook('invoice')
			OR !$this->registerHook('payment')
			OR !$this->registerHook('paymentReturn'))
			return false;
		return true;
	}
    

	public function uninstall()
	{
		if (!parent::uninstall()OR !$this->deletePaymentcardtbl())
			return false;
		return true;
	}
	
	/*
	 * This function will check display the card details form payment_execution.tpl
	 * It will check if the submit button on the form has been pressed and submit the card details to the database 
	 */
	
	public function execPayment($context)
	{
		if (!$this->active)
			return ;
   			
		global $cookie, $smarty;

		$pay = new PayPlugin(dirname(__FILE__).'/gateway/config.properties');
                
                $ordernumber = $context->cart->id;
              
                //verify if cart id is in db and has a SBM orderNo 
                $db = Db::getInstance();
		$result1 = $db->ExecuteS('
		SELECT * FROM `'._DB_PREFIX_.'sbm_cartorder`
		WHERE `id_cart` ='.$ordernumber.';');
                //if yes retrieve SBM orderNo
                if(!empty($result1[0])){                 
                    $sbmorderId = $result1[0]["id_sbmorder"];
                }else{
                    //if no insert cart id into table and fetch SBM orderNo                   
                    try{
                     $response_reg = $pay->registerRequest( array(
                         "orderNumber" => $ordernumber+10000000,
                         "amount" => $context->cart->getOrderTotal(true, Cart::BOTH),
                         "currency" => $context->currency->iso_code_num,
                         "returnUrl" => $context->smarty->tpl_vars['base_dir']->value."modules/offlinecardpayment/payment.php"
                     ));
                     if(isset($response_reg["error"]))
                     {
                         throw new Exception("Error connecting to SBM gateway<br>".$response_reg["error"]);
                     }
                     $sbmorderId = $response_reg["orderId"];
                     
                   }catch(Exception $e){
                       p($response_reg);
                       d($e->getMessage());
                   }
                    $db->Execute('
                            INSERT INTO `'._DB_PREFIX_.'sbm_cartorder`
                            ( `id_cart`, `id_sbmorder`)
                            VALUES
                            ('.$ordernumber.',"'.$sbmorderId.'")');
                    }
              	$this->pathPayment = $this->_path;
                if(!empty($_SESSION["sbmOrderMsgSta"])&&isset($_SESSION["sbmOrderMsgSta"])){
                     $this->sbmOrderMsgSta = $_SESSION["sbmOrderMsgSta"];
                }else{
                     $this->sbmOrderMsgSta = "";
                }
                 d($_SESSION["sbmOrderMsgSta"]);
		$smarty->assign(array(
                        'errCss'     => "display:none;",
                        'msgError'   => $this->sbmOrderMsgSta,
			'sbmOrderId' =>  $sbmorderId,
			'this_path'  => $this->_path,
			'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/'.$this->name.'/'
		));
                
               // p($cart);
		return $this->display(__FILE__, 'payment_execution.tpl');
	}
	
	/**
	*	hookPayment($params)
	*	Called in Front Office at Payment Screen - displays user this module as payment option
	*/
	function hookPayment($params)
	{
		global $smarty;
		
		$smarty->assign(array(
                     'this_path' 	=> $this->_path,
                    'this_path_ssl' 	=> Configuration::get('PS_FO_PROTOCOL').$_SERVER['HTTP_HOST'].__PS_BASE_URI__."modules/{$this->name}/"));
			
		return $this->display(__FILE__, 'payment.tpl');
	}

	function hookInvoice($params)
	{
		$id_order = $params['id_order'];
		
			global $smarty;
			$paymentCarddetails = $this->readPaymentcarddetails($id_order);
			
			$smarty->assign(array(
			    'cardHoldername'  	        => $paymentCarddetails['cardholdername'],
				'cardNumber' 		=> $paymentCarddetails['cardnumber'],
				'id_order'		=> $id_order,
				'this_page'		=> $_SERVER['REQUEST_URI'],
				'this_path' 		=> $this->_path,
                                'this_path_ssl' 	=> Configuration::get('PS_FO_PROTOCOL').$_SERVER['HTTP_HOST'].__PS_BASE_URI__."modules/{$this->name}/"));
			return $this->display(__FILE__, 'invoice_block.tpl');

	}
	
		
	
	function createPaymentcardtbl()
	{
			/**Function called by install - 
			 * creates the "order_paymentcard" table required for storing payment card details
		     * Column Descriptions: id_payment the primary key. 
		     * id order: Stores the order number associated with this payment card
		     * cardholder_name: Stores the card holder name
		     * cardnumber: Stores the card number
		     * expiry date: Stores date the card expires
		     */
		    		    
                    $db = Db::getInstance(); 
            		$query = " CREATE TABLE `"._DB_PREFIX_."sbm_cartorder` (
                                      `id_sbmcartorder` int(11) NOT NULL AUTO_INCREMENT,
                                      `id_cart` int(11) NOT NULL,
                                      `id_sbmorder` varchar(255) DEFAULT NULL,
                                      `sbm_orderstatus` tinyint(1) DEFAULT NULL,
                                      `sbm_approvalcode` int(11) DEFAULT NULL,
                                      `sbm_referenceNum` bigint(15) DEFAULT NULL,
                                      `sbm_orderNum` int(11) DEFAULT NULL,
                                      PRIMARY KEY (`id_sbmcartorder`)
                                    ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
                                    ";
	 		        $db->Execute($query);
		
			return true;
	}
		
        
        function deletePaymentcardtbl()
	{
			/**Function called by install - 
			 * creates the "order_paymentcard" table required for storing payment card details
		     * Column Descriptions: id_payment the primary key. 
		     * id order: Stores the order number associated with this payment card
		     * cardholder_name: Stores the card holder name
		     * cardnumber: Stores the card number
		     * expiry date: Stores date the card expires
		     */
		    		    
                    $db = Db::getInstance(); 
            		$query = "DROP TABLE `"._DB_PREFIX_."sbm_cartorder`;";
	 		        $db->Execute($query);
		
			return true;
	}
	/*
     *  Call this function to save the payment card details to the payment card table
     */
	
	function writePaymentcarddetails($sbmOrderId, $cardholderName, $cvc ,$cardNumber,$cardExpiration)
	{
		$pay = new PayPlugin(dirname(__FILE__).'/gateway/config.properties');
                $payment_arr = array(
                    "orderId" => $sbmOrderId,
                    'pan' => $cardNumber,
                    'cvc' => $cvc,
                    'expiration' => $cardExpiration,
                    "cardholder" => $cardholderName,
                    "language" => "en"
                );
                try{               
                    $response_pay = $pay->AuthorizePaymentRequest($payment_arr);

                    if(isset($response_pay["error"])){
                        throw new Exception("Error connecting to SBM gateway ".$response_pay["error"]);
                        $status       = $pay->StatusRequest(array("orderId" => $sbmOrderId)); 
                        $OrderStatus        = $status["OrderStatus"];  
                        $OrderNumber        = $status["OrderNumber"];  
                        $approvalCode       = $status["approvalCode"];
                        $referenceNumber    = $status["referenceNumber"]; 
                        
                    }else{
                        $parseDat = array();
                        $parseDat = parse_url($response_pay["redirect"]);
                        parse_str($parseDat["query"]);
                    }  
                }catch(Exception $e){
                    $this->sbmOrderMsgSta = $e->getMessage();
                    if(!isset($_SESSION))
                    {
                      session_start();
                      $_SESSION["sbmOrderMsgSta"] = $this->sbmOrderMsgSta;
                    }
                  
                    return;                    
                }   
                $db = Db::getInstance(); 
                $db->Execute('UPDATE `'._DB_PREFIX_.'sbm_cartorder` SET sbm_orderstatus = '.$OrderStatus.', sbm_approvalcode='.$approvalCode.', sbm_referenceNum='.$referenceNumber.', sbm_orderNum ='.$OrderNumber.' WHERE id_sbmorder="'.$sbmOrderId.'"');
                
                $this->transactionId = $referenceNumber;
                $this->sbmOrderStatus = $OrderStatus;
                switch($OrderStatus){
                   case 0:
                       $message = "Order is registered, but not paid";
                   break;
                   case 1:
                       $message = "Order amount is pre-authorized";
                   break;
                   case 2:
                       $message = "Order amount is authorized";
                   break;
                   case 3:
                       $message = "Authorization cancelled";
                   break;
                   case 4:
                       $message = "Transaction operation was refunded";
                   break;
                   case 5:
                       $message = "Authorization was initiated through the ACS of issuing bank";
                   break;
                   case 6:
                       $message = "Authorization rejected";
                   break;
               }
                  $this->sbmOrderMsgSta = $message;
	       return;// $this->display(__FILE__, 'validation.tpl');
	}
	
        
        public function hookPaymentReturn($params)
	{
		if (!$this->active)
			return;
               
		$psOrderStatus = $params['objOrder']->getCurrentState();
		
                if ($psOrderStatus == Configuration::get('PS_OS_PREPARATION')||$psOrderStatus == Configuration::get('PS_OS_PAYMENT'))
		{
			$this->smarty->assign(array(
				'total_to_pay' => Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false),				
				'status' => 'ok',
				'id_order' => $params['objOrder']->id
			));
			if (isset($params['objOrder']->reference) && !empty($params['objOrder']->reference))
				$this->smarty->assign('reference', $params['objOrder']->reference);
		}
		else
			$this->smarty->assign('status', 'failed');
		return $this->display(__FILE__, 'payment_return.tpl');
	}
    /*
     *  Call this function to read the payment card details from the payment card table
     */
	function readPaymentcarddetails($id_order)
	{
		$db = Db::getInstance();
		$result = $db->ExecuteS('
		SELECT * FROM `ps_order_paymentcard`
		WHERE `id_order` ="'.intval($id_order).'";');
		return $result[0];
	}
	
}	

?>
