<?php
/**
 * Adminarea module orders
 */

use Omnipay\Omnipay;


class vstore_gateways_ui extends e_admin_ui
{

	protected $pluginTitle = 'Vstore';
	protected $pluginName = 'vstore';
	// protected $eventName		= 'vstore_order'; // remove comment to enable event triggers in admin.
	// protected $table			= 'vstore_orders';
	// protected $pid				= 'order_id';
	// protected $perPage			= 10;
	// protected $batchDelete		= false;
	//	protected $batchCopy		= true;
	//	protected $sortField		= 'somefield_order';
	//	protected $orderStep		= 10;
	// protected $tabs				= array(LAN_GENERAL,'Details'); // Use 'tab'=>'paypal'  OR 'tab'=>'paypal_rest' in the $fields below to enable.

	//	protected $listQry      	= "SELECT o.*, SUM(c.cart_qty) as items FROM `#vstore_orders` AS o LEFT JOIN `#vstore_cart` AS  c ON o.order_session = c.cart_session  "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.

	// protected $listOrder		= 'order_id DESC';


	// protected $fields 		= array ( );

	// protected $fieldpref = array('order_id','order_ship_to', 'order_status', 'order_date', 'order_items', 'order_pay_transid','order_pay_amount','order_pay_status');

	// defined the tab order.
	protected $preftabs = array(
		'paypal'        => 'Paypal Express',
		'paypal_rest'   => 'Paypal REST',
		'mollie'        => 'Mollie',
		'bank_transfer' => 'Bank Transfer'
	);


	protected $prefs = array(
	//	'gateways'          => array('title'=>'Gateways', 'type'=>'hidden', 'tab' => 'paypal', 'data'=>'str'),
	/*	'paypal_active'    => array('title' => LAN_ACTIVE, 'type' => 'boolean', 'tab' => 'paypal', 'data' => 'int', 'help' => ''),
		'paypal_testmode'  => array('title' => "Paypal Testmode", 'type' => 'boolean', 'tab' => 'paypal', 'data' => 'int', 'writeParms' => array(), 'help' => 'Use Paypal Sandbox'),
		'paypal_username'  => array('title' => "Paypal Username", 'type' => 'text', 'tab' => 'paypal', 'data' => 'str', 'writeParms' => array('size' => 'xxlarge'), 'help' => ''),
		'paypal_password'  => array('title' => "Paypal Password", 'type' => 'password', 'tab' => 'paypal', 'data' => 'str', 'help' => '', 'writeParms' => array('size' => 'xxlarge')),
		'paypal_signature' => array('title' => "Paypal Signature", 'type' => 'text', 'tab' => 'paypal', 'data' => 'str', 'help' => '', 'writeParms' => array('size' => 'xxlarge')),

		'paypal_rest_active'   => array('title' => LAN_ACTIVE, 'type' => 'boolean', 'tab' => 'paypal_rest', 'data' => 'int', 'help' => ''),
		'paypal_rest_testmode' => array('title' => "Paypal REST Testmode", 'type' => 'boolean', 'tab' => 'paypal_rest', 'data' => 'int', 'writeParms' => array(), 'help' => 'Use Paypal Sandbox'),
		'paypal_rest_clientId' => array('title' => "Paypal Client Id", 'type' => 'text', 'tab' => 'paypal_rest', 'data' => 'str', 'writeParms' => array('size' => 'xxlarge'), 'help' => ''),
		'paypal_rest_secret'   => array('title' => "Paypal Secret", 'type' => 'password', 'tab' => 'paypal_rest', 'data' => 'str', 'help' => '', 'writeParms' => array('size' => 'xxlarge')),
	*/
		//	'paypal_signature'      => array('title'=>"Paypal Signature", 'type'=>'text', 'tab'=>'paypal', 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),

		'mollie_active'                 => array('title' => LAN_ACTIVE, 'type' => 'boolean', 'tab' => 'mollie', 'data' => 'int', 'help' => ''),
		'mollie_testmode'               => array('title' => "Mollie Testmode", 'type' => 'boolean', 'tab' => 'mollie', 'data' => 'int', 'writeParms' => array(), 'help' => 'Use Mollie Testmode'),
		'mollie_api_key_live'           => array('title' => "Mollie Live API key", 'type' => 'text', 'tab' => 'mollie', 'data' => 'str', 'note' => 'Get your api keys <a href="https://www.mollie.com/dashboard/developers/api-keys">here</a>', 'writeParms' => array('size' => 'xxlarge')),
		'mollie_api_key_test'           => array('title' => "Mollie Test API key", 'type' => 'text', 'tab' => 'mollie', 'data' => 'str', 'note' => '', 'help' => '', 'writeParms' => array('size' => 'xxlarge')),
		'mollie_payment_methods'        => array('title' => "Mollie Payment methods", 'type' => 'checkboxes', 'tab' => 'mollie', 'data' => 'str', 'note' => 'Select at least 1 payment method.\nThe payment method MUST BE enabled in your Mollie dashoard BEFORE you can use it with vstore!\nBe aware, that not all methods support all currencies!', 'help' => '', 'writeParms' => array('__options' => array('multiple' => true, 'size' => 'xxlarge'))),

		'gateways/bank_transfer/active'  => array('title' => LAN_ACTIVE, 'type' => 'boolean', 'tab' => 'bank_transfer', 'data' => 'int', 'help' => ''),
		'gateways/bank_transfer/details' => array('title' => "Bank Transfer", 'type' => 'textarea', 'tab' => 'bank_transfer', 'data' => 'str', 'writeParms' => array('placeholder' => "Bank Account Details"), 'help' => ''),

	);


	public function init()
	{

		//	unset($this->preftabs[3],$this->preftabs[4]); // Disable Amazon and Skrill for Now until they work. // TODO //FIXME


		$paymentMethods = vstore::getMolliePaymentMethods();
		foreach($paymentMethods as $k => $row)
		{
			//$this->prefs['mollie_payment_methods']['writeParms'][$k] = $row['title'];
			$this->prefs['mollie_payment_methods']['writeParms'][$k] = vstore::getMolliePaymentMethodIcon($k, '2x') . '  ' . $row['title'];
		}
		asort($this->prefs['mollie_payment_methods']['writeParms']);
		$this->prefs['mollie_payment_methods']['note'] = str_replace('\n', '<br/>', $this->prefs['mollie_payment_methods']['note']);


		/** Automatically build the preferences from the Gateway files         * */

		$this->loadAdditionalGateways();

	}


	private function getAvailableGateways()
	{
		$path = e_PLUGIN . "vstore/vendor/omnipay/";
		$list = [];

		$fixed = array(
			'paypal'        => array('paypal/src/ExpressGateway.php','PayPal_Express', 'fa-paypal' ),
			'paypal_rest'   => array('paypal/src/RestGateway.php', 'PayPal_Rest', 'fa-paypal' ),
		);

		// Load Paypal.
		foreach($fixed as $key => $var)
		{
			list($file, $class, $icon) = $var;

			require_once($path.$file);
			/** @var Omnipay\Omnipay $gt  */
			$gt     = Omnipay::create($class);
			$name   = $gt->getName();
			$parms  = $gt->getDefaultParameters();

			$list[$key] = array('name' => $name, 'parms' => $parms, 'icon'=> $icon);
		}


		// Scan for others..
		$dirs = scandir($path);
		unset($dirs[0], $dirs[1]);


		foreach($dirs as $folder)
		{
			$srcPath = $path . $folder . '/src/Gateway.php';

			if($folder === 'common' || !file_exists($srcPath))
			{
				continue;
			}


			require_once($srcPath);
			/** @var Omnipay\Omnipay $gt  */
			$gt     = Omnipay::create($folder);
			$name   = $gt->getName();
			$parms  = $gt->getDefaultParameters();

			$list[$folder] = array('name' => $name, 'parms' => $parms);
		}


		return $list;

	}



	function beforePrefsSave($new_data, $old_data)
	{
		e107::getMessage()->addDebug("<h4>Saving the following prefs:</h4> ".print_a($new_data,true));
	}

	/**
	 * Automatically loads and sets gateway parameter fields based on their their classes.
	 */
	private function loadAdditionalGateways()
	{

		$extraGateways = $this->getAvailableGateways();

		unset($extraGateways['mollie']); // already defined.

		foreach($extraGateways as $plug => $gates)
		{
			$this->prefs['gateways/'.$plug.'/active'] = array('title' => LAN_ACTIVE, 'type' => 'bool', 'tab' => $plug, 'data' => 'int', 'writeParms' => array());
			$this->prefs['gateways/'.$plug.'/title'] = array('title' => LAN_TITLE, 'type' => 'text', 'tab' => $plug,  'data' => 'str', 'writeParms' => array('size'=>'xxlarge'));
			$this->prefs['gateways/'.$plug.'/icon'] = array('title' => LAN_ICON, 'type' => 'icon', 'tab' => $plug, 'data' => 'str', 'writeParms' => array('default'=>varset($gates['icon'])));
			$this->prefs['gateways/'.$plug.'/name'] = array('title' => 'Classname', 'type' => 'hidden', 'tab' => $plug,  'data' => false, 'writeParms' => array('value'=>$gates['name']));

			foreach($gates['parms'] as $pref => $field)
			{
				$type = 'text';
				$data = 'str';
				$writeParms = array('size' => 'xxlarge');

				if(is_bool($field))
				{
					$type = 'bool';
					$data = 'bool';
					$writeParms = array();
				}
				elseif(is_array($field))
				{
					$type = 'dropdown';
					$writeParms['optArray'] = $field;
				}

				$name = $plug . "_" . $pref;
				$title = preg_replace('/([A-Z])/', ' $1', ucfirst($pref));
				$this->prefs['gateways/'.$plug.'/prefs/'.$pref] = array('title' => ltrim(ucwords($title)), 'type' => $type, 'tab' => $plug, 'data' => $data, 'writeParms' => $writeParms);

				$this->preftabs[$plug] = $gates['name'];
			}

		}
	}

}


class vstore_gateways_form_ui extends e_admin_form_ui
{
	function gateways($curVal,$mode, $att)
	{
		$curVal = e107::pref('vstore', 'gateways');

		list($tmp, $gateway) = explode('|', $att['field']);
// $curVal[$gateway]
		if($tmp === 'gateway_icon')
		{
			return $this->iconpicker('gateways['.$gateway.'][icon]', varset($curVal[$gateway]['icon']));
		}


		$text =  $this->text('gateways['.$gateway.'][title]', varset($curVal[$gateway]['title']), 50, ['size'=>'xxlarge']);

		if(isset($att['gatewayName'])) // the actual class name used by Omnipay::create().
		{
			$text .= $this->hidden('gateways['.$gateway.'][name]', $att['gatewayName']);
		}

		return $text;
	}
}

