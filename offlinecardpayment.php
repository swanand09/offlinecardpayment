<?php
include(dirname(__FILE__).'/gateway/PayPlugin.php');
class Offlinecardpayment extends PaymentModule
{
	
	private $_html = '';
	private $_postErrors = array();
	
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
		if (!parent::uninstall())
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
                    $response_reg = $pay->registerRequest( array(
                        "orderNumber" => $ordernumber,
                        "amount" => $context->cart->getOrderTotal(true, Cart::BOTH),
                        "currency" => $context->currency->iso_code_num,
                        "returnUrl" => $context->smarty->tpl_vars['base_dir']->value."modules/offlinecardpayment/payment.php"
                    ));
                    $sbmorderId = $response_reg["orderId"];
                    $result2 = $db->Execute('
                            INSERT INTO `'._DB_PREFIX_.'sbm_cartorder`
                            ( `id_cart`, `id_sbmorder`)
                            VALUES
                            ('.$ordernumber.',"'.$sbmorderId.'")');
                }
              	
		$smarty->assign(array(
			'orderId' =>  $sbmorderId,
			'this_path' => $this->_path,
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
            'this_path' 		=> $this->_path,
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
				'cardNumber' 		        => $paymentCarddetails['cardnumber'],
				'id_order'					=> $id_order,
				'this_page'					=> $_SERVER['REQUEST_URI'],
				'this_path' 				=> $this->_path,
            	'this_path_ssl' 			=> Configuration::get('PS_FO_PROTOCOL').$_SERVER['HTTP_HOST'].__PS_BASE_URI__."modules/{$this->name}/"));
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
                                        PRIMARY KEY (`id_sbmcartorder`)
                                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                                    ";
	 		        $db->Execute($query);
		
			return true;
	}
		
	/*
     *  Call this function to save the payment card details to the payment card table
     */
	
	function writePaymentcarddetails($id_order, $cardholderName, $cardNumber)
	{
		$db = Db::getInstance();
		$result = $db->Execute('
		INSERT INTO `ps_order_paymentcard`
		( `id_order`, `cardholdername`,`cardnumber`)
		VALUES
		("'.intval($id_order).'","'.$cardholderName.'","'.$cardNumber.'")');
		return;
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
