<?php
/**
 * Adminarea module orders
 */
class vstore_gateways_ui extends e_admin_ui
{

		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';
		// protected $eventName		= 'vstore_order'; // remove comment to enable event triggers in admin.
		// protected $table			= 'vstore_orders';
		// protected $pid				= 'order_id';
		// protected $perPage			= 10;
		// protected $batchDelete		= false;
	//	protected $batchCopy		= true;
	//	protected $sortField		= 'somefield_order';
	//	protected $orderStep		= 10;
		// protected $tabs				= array(LAN_GENERAL,'Details'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable.

	//	protected $listQry      	= "SELECT o.*, SUM(c.cart_qty) as items FROM `#vstore_orders` AS o LEFT JOIN `#vstore_cart` AS  c ON o.order_session = c.cart_session  "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.

		// protected $listOrder		= 'order_id DESC';



		// protected $fields 		= array ( );

		// protected $fieldpref = array('order_id','order_ship_to', 'order_status', 'order_date', 'order_items', 'order_pay_transid','order_pay_amount','order_pay_status');


		protected $preftabs = array('Paypal Express', 'Paypal REST', 'Mollie', 'Amazon', 'Skrill', 'Bank Transfer');


		protected $prefs = array(
			'paypal_active'         => array('title'=>"Paypal Express Payments", 'type'=>'boolean', 'tab'=>0, 'data'=>'int', 'help'=>''),
			'paypal_testmode'         => array('title'=>"Paypal Testmode", 'type'=>'boolean', 'tab'=>0, 'data'=>'int', 'writeParms'=>array(),'help'=>'Use Paypal Sandbox'),
			'paypal_username'       => array('title'=>"Paypal Username", 'type'=>'text', 'tab'=>0, 'data'=>'str', 'writeParms'=>array('size'=>'xxlarge'), 'help'=>''),
			'paypal_password'       => array('title'=>"Paypal Password", 'type'=>'password', 'tab'=>0, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
			'paypal_signature'      => array('title'=>"Paypal Signature", 'type'=>'text', 'tab'=>0, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),

			'paypal_rest_active'    => array('title'=>"Paypal REST Payments", 'type'=>'boolean', 'tab'=>1, 'data'=>'int', 'help'=>''),
			'paypal_rest_testmode'  => array('title'=>"Paypal REST Testmode", 'type'=>'boolean', 'tab'=>1, 'data'=>'int', 'writeParms'=>array(),'help'=>'Use Paypal Sandbox'),
			'paypal_rest_clientId'  => array('title'=>"Paypal Client Id", 'type'=>'text', 'tab'=>1, 'data'=>'str', 'writeParms'=>array('size'=>'xxlarge'), 'help'=>''),
			'paypal_rest_secret'    => array('title'=>"Paypal Secret", 'type'=>'password', 'tab'=>1, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
		//	'paypal_signature'      => array('title'=>"Paypal Signature", 'type'=>'text', 'tab'=>0, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),

//			'coinbase_active'     => array('title'=>"Coinbase Payments", 'type'=>'boolean', 'tab'=>2, 'data'=>'int', 'help'=>''),
//			'coinbase_account'    => array('title'=>"Coinbase Account ID", 'type'=>'text', 'tab'=>2, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
//			'coinbase_api_key'    => array('title'=>"Coinbase API key", 'type'=>'text', 'tab'=>2, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
//			'coinbase_secret'     => array('title'=>"Coinbase Secret Key", 'type'=>'password', 'tab'=>2, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),

			'mollie_active'         => array('title'=>"Mollie Payments", 'type'=>'boolean', 'tab'=>2, 'data'=>'int', 'help'=>''),
			'mollie_testmode'       => array('title'=>"Mollie Testmode", 'type'=>'boolean', 'tab'=>2, 'data'=>'int', 'writeParms'=>array(),'help'=>'Use Mollie Testmode'),
			'mollie_api_key_live'   => array('title'=>"Mollie Live API key", 'type'=>'text', 'tab'=>2, 'data'=>'str', 'note'=>'Get your api keys <a href="https://www.mollie.com/dashboard/developers/api-keys">here</a>', 'writeParms'=>array('size'=>'xxlarge')),
			'mollie_api_key_test'   => array('title'=>"Mollie Test API key", 'type'=>'text', 'tab'=>2, 'data'=>'str', 'note' => 'Goto your Mollie dashboard to activate testmode.', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
			'mollie_payment_methods'=> array('title'=>"Mollie Payment methods", 'type'=>'checkboxes', 'tab'=>2, 'data'=>'str', 'note' => 'Select at least 1 payment method. Be aware, that not all methods support all currencies!', 'help'=>'', 'writeParms'=>array('__options' => array('multiple' => true, 'size' => 'xxlarge'))),

//			'amazon_active'         => array('title'=>"Amazon Payments", 'type'=>'boolean', 'tab'=>3, 'data'=>'int', 'help'=>''),
//			'amazon_merchant_id'    => array('title'=>"Amazon Merchant ID", 'type'=>'text', 'tab'=>3, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
//			'amazon_secret_key'     => array('title'=>"Amazon Secret Key", 'type'=>'password', 'tab'=>3, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
//			'amazon_region'         => array('title'=>"Amazon Region", 'type'=>'dropdown', 'tab'=>3, 'data'=>'str', 'writeParms'=>array('optArray'=>array('us'=>'USA','de'=>"Germany",'uk'=>"United Kingdom",'jp'=>"Japan")), 'help'=>''),
//
//			'skrill_active'         => array('title'=>"Skrill Payments", 'type'=>'boolean', 'tab'=>4, 'data'=>'int', 'help'=>''),
//			'skrill_email'          => array('title'=>"Skrill Email", 'type'=>'text', 'tab'=>4, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),

			'bank_transfer_active'    => array('title'=>"Bank Transfer", 'type'=>'boolean', 'tab'=>5, 'data'=>'int', 'help'=>''),
			'bank_transfer_details'   => array('title'=>"Bank Transfer", 'type'=>'textarea', 'tab'=>5, 'data'=>'str', 'writeParms'=>array('placeholder'=>"Bank Account Details"), 'help'=>''),

		);



		public function init()
		{
			if(e_DEBUG !== true)
			{
				unset($this->preftabs[3],$this->preftabs[4]); // Disable Mollie, Amazon and Skrill for Now until they work. // TODO //FIXME
			}
			$paymentMethods = vstore::getMolliePaymentMethods();
			foreach($paymentMethods as $k => $row) {
				//$this->prefs['mollie_payment_methods']['writeParms'][$k] = $row['title'];
				$this->prefs['mollie_payment_methods']['writeParms'][$k] = vstore::getMolliePaymentMethodIcon($k, '2x') . '  ' . $row['title'];
			}
			asort($this->prefs['mollie_payment_methods']['writeParms']);

		}

}



class vstore_gateways_form_ui extends e_admin_form_ui
{

}
?>