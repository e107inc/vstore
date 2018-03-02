<?php

// Generated e107 Plugin Admin Area 

require_once('../../class2.php');
if (!getperms('P')) 
{
	header('location:'.e_BASE.'index.php');
	exit;
}

e107::css('inline',"

	img.level-1 { margin:0 5px 0 15px; }
	item-inventory { font-weight: bold }

");

require_once('vstore.class.php');

class vstore_admin extends e_admin_dispatcher
{

	protected $modes = array(	
	
		'main'	=> array(
			'controller' 	=> 'vstore_cart_ui',
			'path' 			=> null,
			'ui' 			=> 'vstore_cart_form_ui',
			'uipath' 		=> null
		),
		

		'orders'	=> array(
			'controller' 	=> 'vstore_order_ui',
			'path' 			=> null,
			'ui' 			=> 'vstore_order_form_ui',
			'uipath' 		=> null
		),
		

		'cat'	=> array(
			'controller' 	=> 'vstore_cat_ui',
			'path' 			=> null,
			'ui' 			=> 'vstore_cat_form_ui',
			'uipath' 		=> null
		),
		

		'products'	=> array(
			'controller' 	=> 'vstore_items_ui',
			'path' 			=> null,
			'ui' 			=> 'vstore_items_form_ui',
			'uipath' 		=> null
		),

		'vars'	=> array(
			'controller' 	=> 'vstore_items_vars_ui',
			'path' 			=> null,
			'ui' 			=> 'vstore_items_vars_form_ui',
			'uipath' 		=> null
		),
		

	);	
	
	
	protected $adminMenu = array(

	
	//	'main/create'		=> array('caption'=> LAN_CREATE, 'perm' => 'P'),

		'products/list'			=> array('caption'=> "Products", 'perm' => 'P'),
		'products/create'		=> array('caption'=> "Add Product", 'perm' => 'P'),
	//	'cart/create'		=> array('caption'=> LAN_CREATE, 'perm' => 'P'),
			'other/3'           => array('divider'=>true),

		'vars/list'			=> array('caption'=> "Product Variations", 'perm' => 'P'),
		'vars/create'		=> array('caption'=> "Add Product Variations", 'perm' => 'P'),


		'other/0'           => array('divider'=>true),

		'cat/list'			=> array('caption'=> LAN_CATEGORIES, 'perm' => 'P'),
		'cat/create'		=> array('caption'=> LAN_CREATE_CATEGORY, 'perm' => 'P'),

		'other/1'           => array('divider'=>true),

		'orders/list'		=> array('caption'=> "Sales", 'perm' => 'P'),
		
	//	'main/list'			=> array('caption'=> "Customers", 'perm' => 'P'),

		'other/2'           => array('divider'=>true),
	
		'main/prefs' 		=> array('caption'=> LAN_PREFS, 'perm' => 'P'),

		'orders/prefs'		=> array('caption'=> "Payment Gateways", 'perm' => 'P'),

		// 'main/custom'		=> array('caption'=> 'Custom Page', 'perm' => 'P')
	);

	protected $adminMenuAliases = array(
		'products/edit'	=> 'products/list',
		'vars/edit'     => 'vars/list',
		'products/grid' => 'products/list',
		'orders/edit'	=> 'orders/list'
	);	
	
	protected $menuTitle = 'Vstore';

		function init()
		{
			if(deftrue('e_DEBUG'))
			{
		//		$this->adminMenu['products/grid'] = array('caption'=> "Products (Grid)", 'perm' => 'P');
			}

			parent::init();
		}
}




				
class vstore_customer_ui extends e_admin_ui
{
			
		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';
		protected $table			= 'vstore_customer';
		protected $pid				= 'cust_id';
		protected $perPage			= 10; 
		protected $batchDelete		= true;
	//	protected $batchCopy		= true;		
	//	protected $sortField		= 'somefield_order';
	//	protected $orderStep		= 10;
	//	protected $tabs			= array('Tabl 1','Tab 2'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable. 
		
	//	protected $listQry      	= "SELECT * FROM #tableName WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.
	
		protected $listOrder		= 'cust_id DESC';
	
		protected $fields 		= array (  'checkboxes' =>   array ( 'title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => '1', 'class' => 'center', 'toggle' => 'e-multiselect',  ),
		  'cust_id' =>   array ( 'title' => LAN_ID, 'data' => 'int', 'width' => '5%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'cust_userid' =>   array ( 'title' => 'Userid', 'type' => 'method', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_datestamp' =>   array ( 'title' => LAN_DATESTAMP, 'type' => 'datestamp', 'data' => 'int', 'width' => 'auto', 'filter' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'cust_prename' =>   array ( 'title' => 'Prename', 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_firstname' =>   array ( 'title' => 'Firstname', 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_lastname' =>   array ( 'title' => 'Lastname', 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_company' =>   array ( 'title' => 'Company', 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_title' =>   array ( 'title' => LAN_TITLE, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'cust_address' =>   array ( 'title' => 'Address', 'type' => 'textarea', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_city' =>   array ( 'title' => 'City', 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_state' =>   array ( 'title' => 'State', 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_postcode' =>   array ( 'title' => 'Postcode', 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_country' =>   array ( 'title' => 'Country', 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_email' =>   array ( 'title' => 'Email', 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_email2' =>   array ( 'title' => 'Email2', 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_phone_day' =>   array ( 'title' => 'Day', 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_phone_night' =>   array ( 'title' => 'Night', 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_comments' =>   array ( 'title' => 'Comments', 'type' => 'textarea', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_website' =>   array ( 'title' => LAN_URL, 'type' => 'url', 'data' => 'str', 'width' => 'auto', 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'cust_ip' =>   array ( 'title' => 'Ip', 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_assigned_to' =>   array ( 'title' => 'To', 'type' => 'method', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_interested' =>   array ( 'title' => 'Interested', 'type' => 'method', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_notes' =>   array ( 'title' => 'Notes', 'type' => 'textarea', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_refcode' =>   array ( 'title' => 'Refcode', 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'options' =>   array ( 'title' => 'Options', 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced' => '1',  ),
		);		
		
		protected $fieldpref = array('cust_datestamp', 'cust_title');
		

	
	/*		
		public function customPage()
		{
			$ns = e107::getRender();
			$text = 'Hello World!';
			$ns->tablerender('Hello',$text);	
			
		}
	*/
			
}
				


class vstore_customer_form_ui extends e_admin_form_ui
{

	
	// Custom Method/Function 
	function cust_userid($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('cust_userid',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

	
	// Custom Method/Function 
	function cust_firstname($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('cust_firstname',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

	
	// Custom Method/Function 
	function cust_lastname($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('cust_lastname',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

	
	// Custom Method/Function 
	function cust_company($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('cust_company',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

	
	// Custom Method/Function 
	function cust_country($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('cust_country',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

	
	// Custom Method/Function 
	function cust_ip($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('cust_ip',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

	
	// Custom Method/Function 
	function cust_assigned_to($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('cust_assigned_to',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

	
	// Custom Method/Function 
	function cust_interested($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('cust_interested',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

}		
		



class vstore_order_ui extends e_admin_ui
{

		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';
		protected $eventName		= 'vstore_order'; // remove comment to enable event triggers in admin.
		protected $table			= 'vstore_orders';
		protected $pid				= 'order_id';
		protected $perPage			= 10;
		protected $batchDelete		= false;
	//	protected $batchCopy		= true;
	//	protected $sortField		= 'somefield_order';
	//	protected $orderStep		= 10;
		protected $tabs				= array(LAN_GENERAL,'Details'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable.

	//	protected $listQry      	= "SELECT o.*, SUM(c.cart_qty) as items FROM `#vstore_orders` AS o LEFT JOIN `#vstore_cart` AS  c ON o.order_session = c.cart_session  "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.

		protected $listOrder		= 'order_id DESC';



		protected $fields 		= array (
			'checkboxes'           	=> array ( 'title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => '1', 'class' => 'center', 'toggle' => 'e-multiselect',  ),
			'order_id'            	=> array ( 'title' => LAN_ID, 'data' => 'int', 'width' => '5%', 'help' => '', 'readonly'=>true, 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'order_status'          => array ( 'title' => 'Status', 'type'=>'dropdown', 'data'=>'str', 'inline'=>true, 'filter'=>true, 'batch'=>true,'width'=>'5%'),
			'order_date'          	=> array ( 'title' => LAN_DATESTAMP, 'type' => 'datestamp', 'data' => 'str',  'readonly'=>true, 'width' => 'auto', 'filter' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),

			'order_ship_to'      	=> array ( 'title' => 'Ship to', 'type'=>'method', 'data'=>false, 'width'=>'20%'),
			'order_items'     		=> array ( 'title' => "Items", 'type' => 'method', 'data' => false, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'right', 'thclass' => 'right',  ),
			'order_e107_user'     	=> array ( 'title' => LAN_AUTHOR, 'type' => 'method', 'data' => 'str', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'order_pay_gateway'     => array ( 'title' => 'Gateway', 'type' => 'text', 'data' => 'str', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'order_pay_status'      => array ( 'title' => 'Pay Status', 'type' => 'text',  'data' => 'str',  'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'order_pay_transid'     => array ( 'title' => 'TransID', 'type' => 'text', 'data' => 'str', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'order_pay_amount' 		=> array ( 'title' => 'Total', 'type' => 'method', 'data' => 'int', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'order_pay_shipping' 	=> array ( 'title' => 'Shipping', 'type' => 'number', 'data' => 'int', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'order_ship_notes'      => array ( 'title' => 'Notes', 'type'=>'method', 'tab'=>1, 'data'=>false, 'width'=>'20%'),
			'order_session'       	=> array ( 'title' => 'Session', 'type' => 'text', 'tab'=>1, 'data' => 'str', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'order_pay_rawdata' 	=> array ( 'title' => 'Rawdata', 'type' => 'method', 'tab'=>1, 'data' => 'str', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'options' 				=> array ( 'title' => LAN_OPTIONS, 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced' => '1',  ),
		);

		protected $fieldpref = array('order_id','order_ship_to', 'order_status', 'order_date', 'order_items', 'order_pay_transid','order_pay_amount','order_pay_status');


		protected $preftabs = array('Paypal Express', 'Paypal REST', 'Coinbase', 'Amazon', 'Skrill', 'Bank Transfer');


		protected $prefs = array(
			'paypal_active'         => array('title'=>"Paypal Express Payments", 'type'=>'boolean', 'tab'=>0, 'data'=>'int', 'help'=>''),
			'paypal_testmode'         => array('title'=>"Paypal Testmode", 'type'=>'boolean', 'tab'=>0, 'data'=>'int', 'writeParms'=>array(),'help'=>'Use Paypal Sandbox'),
			'paypal_username'       => array('title'=>"Paypal Username", 'type'=>'text', 'tab'=>0, 'data'=>'str', 'writeParms'=>array('size'=>'xxlarge'), 'help'=>''),
			'paypal_password'       => array('title'=>"Paypal Password", 'type'=>'text', 'tab'=>0, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
			'paypal_signature'      => array('title'=>"Paypal Signature", 'type'=>'text', 'tab'=>0, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),

			'paypal_rest_active'    => array('title'=>"Paypal REST Payments", 'type'=>'boolean', 'tab'=>1, 'data'=>'int', 'help'=>''),
			'paypal_rest_testmode'  => array('title'=>"Paypal REST Testmode", 'type'=>'boolean', 'tab'=>1, 'data'=>'int', 'writeParms'=>array(),'help'=>'Use Paypal Sandbox'),
			'paypal_rest_clientId'  => array('title'=>"Paypal Client Id", 'type'=>'text', 'tab'=>1, 'data'=>'str', 'writeParms'=>array('size'=>'xxlarge'), 'help'=>''),
			'paypal_rest_secret'    => array('title'=>"Paypal Secret", 'type'=>'text', 'tab'=>1, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
		//	'paypal_signature'      => array('title'=>"Paypal Signature", 'type'=>'text', 'tab'=>0, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),

			'coinbase_active'     => array('title'=>"Coinbase Payments", 'type'=>'boolean', 'tab'=>2, 'data'=>'int', 'help'=>''),
			'coinbase_account'    => array('title'=>"Coinbase Account ID", 'type'=>'text', 'tab'=>2, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
			'coinbase_api_key'    => array('title'=>"Coinbase API key", 'type'=>'text', 'tab'=>2, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
			'coinbase_secret'     => array('title'=>"Coinbase Secret Key", 'type'=>'text', 'tab'=>2, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),

			'amazon_active'         => array('title'=>"Amazon Payments", 'type'=>'boolean', 'tab'=>3, 'data'=>'int', 'help'=>''),
			'amazon_merchant_id'    => array('title'=>"Amazon Merchant ID", 'type'=>'text', 'tab'=>3, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
			'amazon_secret_key'     => array('title'=>"Amazon Secret Key", 'type'=>'text', 'tab'=>3, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
			'amazon_region'         => array('title'=>"Amazon Region", 'type'=>'dropdown', 'tab'=>3, 'data'=>'str', 'writeParms'=>array('optArray'=>array('us'=>'USA','de'=>"Germany",'uk'=>"United Kingdom",'jp'=>"Japan")), 'help'=>''),

			'skrill_active'         => array('title'=>"Skrill Payments", 'type'=>'boolean', 'tab'=>4, 'data'=>'int', 'help'=>''),
			'skrill_email'          => array('title'=>"Skrill Email", 'type'=>'text', 'tab'=>4, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),

			'bank_transfer_active'    => array('title'=>"Bank Transfer", 'type'=>'boolean', 'tab'=>5, 'data'=>'int', 'help'=>''),
			'bank_transfer_details'    => array('title'=>"Bank Transfer", 'type'=>'textarea', 'tab'=>5, 'data'=>'str', 'writeParms'=>array('placeholder'=>"Bank Account Details"), 'help'=>''),

		);



		public function init()
		{
			$this->fields['order_status']['writeParms']['optArray'] = vstore::getStatus();
			// Set drop-down values (if any).

			if(e_DEBUG !== true)
			{
				unset($this->preftabs[3],$this->preftabs[4]); // Disable Amazon and Skrill for Now until they work. // TODO //FIXME
			}

			// check for responses on inline editing
			// and display them
			$js = '
			$(function(){
				$(".e-editable").on("save", function(e, params){
					var msg = params.response;
					if ($("#vstore-message").length > 0)
					{
						$("#vstore-message").html(msg);
					}
					else
					{
						$("#admin-ui-list-filter").prepend("<div id=\"vstore-message\">" + msg + "</div>");
					}
				});
			});
			';
			e107::getJs()->footerInline($js);
		}


		// ------- Customize Create --------

		public function beforeCreate($new_data,$old_data)
		{
			return $new_data;
		}

		public function afterCreate($new_data, $old_data, $id)
		{
			// do something
		}

		public function onCreateError($new_data, $old_data)
		{
			// do something
		}


		// ------- Customize Update --------

		public function beforeUpdate($new_data, $old_data, $id)
		{
			if (array_key_exists('order_status', $new_data)) 
			{
				if ($old_data['order_status'] === 'C' && $new_data['order_status'] !== 'C')
				{
					// Check if this order "contains" any userclasses that have been assigned
					$uc = vstore::getCustomerUserclass(json_decode($old_data['order_items'], true));
					if ($uc)
					{
						$uc_list = e107::getDB()->retrieve('SELECT GROUP_CONCAT(userclass_name) AS ucs FROM e107_userclass_classes WHERE FIND_IN_SET(userclass_id, "'.$uc.'")');
						$msg = sprintf('The userclasses, the customer has been assigned to during the purchase can not be removed automatically.<br/>
							Click <a href="'.e_ADMIN.'users.php?searchquery=%d">here</a> to remove the following userclasses manually.<br/>
							%s', $old_data['order_e107_user'], str_replace(',', ', ', $uc_list));
						
						if (e_AJAX_REQUEST)
						{
							$response_msg = e107::getMessage()->addWarning($msg)->render();
							$new_data['etrigger_submit'] = 'Update';

							$response = $this->getResponse();
							$response->getJsHelper()->addResponse($response_msg);
						}
						else
						{
							e107::getMessage()->addWarning($msg);
						}
					}
				}
			}
			return $new_data;
		}

		public function afterUpdate($new_data, $old_data, $id)
		{
			if (array_key_exists('order_status', $new_data)) 
			{
				// Assign "purchased" userclasses to customer, once the order has been completed
				if ($new_data['order_status'] === 'C' && $old_data['order_status'] !== 'C')
				{
					// Update userclass
					vstore::setCustomerUserclass($old_data['order_e107_user'], json_decode($old_data['order_items'], true));
				}
			}
			// Send our email to customer
			$vs = e107::getSingleton('vstore');
			$vs->emailCustomerOnStatusChange($new_data['order_id']);
		}

		public function onUpdateError($new_data, $old_data, $id)
		{
			// do something
		}


	/*
		// optional - a custom page.
		public function customPage()
		{
			$text = 'Hello World!';
			return $text;

		}
	*/

}



class vstore_order_form_ui extends e_admin_form_ui
{


	// Custom Method/Function
	function order_e107_user($curVal,$mode)
	{
		$frm = e107::getForm();

		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;

			case 'write': // Edit Page
				return $frm->text('order_e107_user',$curVal, 255, 'size=large');
			break;

			case 'filter':
			case 'batch':
				return  array();
			break;
		}
	}

	function order_items($curVal,$mode)
	{

		switch($mode)
		{
			case 'read': // List Page
				if(!empty($curVal))
				{
					$val = json_decode($curVal, true);
					$total = 0;
					foreach($val as $row)
					{
						$total = $total + intval($row['quantity']);
					}
					return $total;
				}
			break;

			case 'write': // Edit Page
				if(empty($curVal))
				{
					return 'n/a';
				}



				$data = json_decode($curVal, true);

			//	return print_a($data,true);

				$text = "<table class='table table-striped table-bordered' style='margin:0;width:70%'>
				<thead>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th class='text-right'>Qty.</th>

					<th class='text-right'>Price</th>
				</tr>
				</thead>";

				foreach($data as $row)
				{

					$text .= "
					<tr>
						<td>".$row['name']."</td>
						<td>".$row['description']."</td>
						<td class='text-right'>".$row['quantity']."</td>
						<td class='text-right'>".$row['price']."</td>
					</tr>";
				}

				$text .= "</table>";

				return $text;
			break;

			case 'filter':
			case 'batch':
				return  array();
			break;
		}
	}


	function order_ship_to($curVal,$mode)
	{


		switch($mode)
		{

			case 'read': // List Page
			case 'write': // Edit Page

				$fname      = $this->getController()->getFieldVar('order_ship_firstname');
				$lname      = $this->getController()->getFieldVar('order_ship_lastname');
				$address    = $this->getController()->getFieldVar('order_ship_address');
				$city       = $this->getController()->getFieldVar('order_ship_city');
				$state      = $this->getController()->getFieldVar('order_ship_state');
				$zip        = $this->getController()->getFieldVar('order_ship_zip');
				$country    = $this->getController()->getFieldVar('order_ship_country');

				return $fname." ".$lname."<br />".$address."<br />".$city.", ".$state."  ".$zip."<br />".$this->getCountry($country);

			break;



		}


	}

	function order_ship_notes($curVal, $mode)
	{
		switch($mode)
		{
			case 'read':
			case 'write':
				$notes = nl2br($this->getController()->getFieldVar('order_ship_notes'));
				return $notes;
				break;
		}
	}


	function order_pay_amount($curVal,$mode)
	{


		switch($mode)
		{

			case 'read': // List Page
			case 'write': // Edit Page

				$via = $this->getController()->getFieldVar('order_pay_gateway');

			break;


			case 'filter':
			case 'batch':
				return  array();
			break;
		}

		return $curVal."<br /><span class='label label-primary'>".vstore::getGatewayTitle($via)."</span>";
	}


	// Custom Method/Function
	function order_pay_rawdata($curVal,$mode)
	{

		switch($mode)
		{
			case 'read': // List Page
			case 'write': // Edit Page

				if(!empty($curVal))
				{
					$data = json_decode($curVal, true);
					$text = "<table class='table table-bordered table-striped table-condensed'>
					<colgroup>
						<col style='width:50%' />
						<col />
					</colgroup>
					";
					foreach($data as $k=>$v)
					{
						$text .= "<tr><td>".$k."</td><td>".$v."</td></tr>";
					}

					$text .= "</table>";
					return $text;
				}

				return null;
			break;

			case 'filter':
			case 'batch':
				return  array();
			break;
		}
	}

}








				
class vstore_cart_ui extends e_admin_ui
{
			
		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';
		protected $table			= 'vstore_cart';
		protected $pid				= 'cart_id';
		protected $perPage			= 10; 
		protected $batchDelete		= true;
	//	protected $batchCopy		= true;		
	//	protected $sortField		= 'somefield_order';
	//	protected $orderStep		= 10;
	//	protected $tabs			= array('Tabl 1','Tab 2'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable. 
		
	//	protected $listQry      	= "SELECT * FROM #tableName WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.
	
		protected $listOrder		= 'cart_id DESC';
	
		protected $fields 		= array (  'checkboxes' =>   array ( 'title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => '1', 'class' => 'center', 'toggle' => 'e-multiselect',  ),
		  'cart_id' =>   array ( 'title' => LAN_ID, 'data' => 'int', 'width' => '5%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'cart_session' =>   array ( 'title' => 'Session', 'type' => 'hidden', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_e107_user' =>   array ( 'title' => 'User', 'type' => 'hidden', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_status' =>   array ( 'title' => 'Status', 'type' => 'dropdown', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_item' =>   array ( 'title' => 'Item', 'type' => 'method', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_qty' =>   array ( 'title' => 'Qty', 'type' => 'method', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_paystat' =>   array ( 'title' => 'Paystat', 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_paydate' =>   array ( 'title' => 'Paydate', 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_paytrans' =>   array ( 'title' => 'Paytrans', 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_paygross' =>   array ( 'title' => 'Paygross', 'type' => 'method', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_payshipping' =>   array ( 'title' => 'Payshipping', 'type' => 'method', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_payshipto' =>   array ( 'title' => 'Payshipto', 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'options' =>   array ( 'title' => 'Options', 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced' => '1',  ),
		);		
		
		protected $fieldpref = array();



/*
 * merchant_id 	Default : null
Access Key 	access_key 	Default : null
Secret Key 	secret_key 	Default : null
Region 	region
 */
		// optional
		protected $preftabs = array(LAN_GENERAL, "Emails", "How to Order", "Admin Area", "Check-Out");


		protected $prefs = array(
			'caption'                  => array('title'=> 'Store Caption', 'tab'=>0, 'type'=>'text', 'help'=>'','writeParms'=>array('placeholder'=>'Vstore'),'multilan'=>true),
			'caption_categories'       => array('title'=> 'Category Caption', 'tab'=>0, 'type'=>'text', 'writeParms'=>array('placeholder'=>'Product Brands'),'multilan'=>true),
			'caption_outofstock'       => array('title'=> 'Out-of-Stock Caption', 'tab'=>0, 'type'=>'text', 'writeParms'=>array('placeholder'=>'Out of Stock'),'multilan'=>true),

			'currency'		            => array('title'=> 'Currency', 'type'=>'dropdown', 'data' => 'string','help'=>'Select a currency'),
			'shipping'		            => array('title'=> 'Calculate Shipping', 'type'=>'boolean', 'data' => 'int','help'=>'Including shipping calculation at checkout.'),
		    'customer_userclass'        => array('title' => 'Assign userclass', 'type' => 'method', 'help' => 'Assign userclass to customer on purchase'),
			'howtoorder'	            => array('title'=> 'How to order', 'tab'=>2,'type'=>'bbarea', 'help'=>'Enter how-to-order info.'),

			'sender_name'               => array('title'=> 'Sender Name', 'tab'=>1, 'type'=>'text', 'writeParms'=>array('placeholder'=>'Sales Department'), 'help'=>'Leave blank to use system default','multilan'=>false),
			'sender_email'              => array('title'=> LAN_EMAIL, 'tab'=>1, 'type'=>'text', 'writeParms'=>array('placeholder'=>'orders@mysite.com'), 'help'=>'Leave blank to use system default', 'multilan'=>false),
			'merchant_info'             => array('title'=> "Merchant Name/Address", 'tab'=>1, 'type'=>'textarea', 'writeParms'=>array('placeholder'=>'My Store Inc. etc.'), 'help'=>'Will be displayed on customer email.', 'multilan'=>false),
			'email_templates'           => array('title'=> "Email templates", 'tab'=>1, 'type'=>'method'), //, 'writeParms'=>array('placeholder'=>'My Store Inc. etc.'), 'help'=>'Will be displayed on customer email.', 'multilan'=>false),


			'admin_items_perpage'	    => array('title'=> 'Products per page', 'tab'=>3, 'type'=>'number', 'help'=>''),
			'admin_categories_perpage'	=> array('title'=> 'Categories per page', 'tab'=>3, 'type'=>'number', 'help'=>''),

			'additional_fields'         => array('title'=>'Additional Fields', 'tab'=>4, 'type'=>'method'),
			'admin_confirm_order'		=> array('title'=> 'Confirm order', 'tab'=>4, 'type'=>'bool', 'help'=>'If ON, the customer has to confirm his order after selecting the payment method on the checkout page!'),

		);



		// optional
		public function init()
		{
			$this->prefs['currency']['writeParms'] = array('USD'=>'US Dollars', 'EUR'=>'Euros', 'CAN'=>'Canadian Dollars');

			$email_fields = array(
				'{ORDER_REF}'			=> 'The order reference number',
				'{ORDER_MERCHANT_INFO}'	=> 'Merchant name & adress',
				'{ORDER_SHIP_FIRSTNAME}'=> 'The customers firstname',
				'{ORDER_SHIP_LASTNAME}' => 'The customers lastname',
				'{ORDER_SHIP_ADDRESS}'	=> 'The customers shipping to street',
				'{ORDER_SHIP_CITY}'		=> 'The customers shipping to city',
				'{ORDER_SHIP_STATE}'	=> 'The customers shipping to state',
				'{ORDER_SHIP_ZIP}'		=> 'The customers shipping to zip code',
				'{ORDER_SHIP_COUNTRY}'	=> 'The customers shipping to country',
				'{ORDER_ITEMS}'			=> 'The ordered items',
				'{ORDER_PAYMENT_INSTRUCTIONS}' => 'In case of payment method "bank transfer", the bank transfer details',
				'{SENDER_NAME}'			=> 'Sender name es defined in the vstore prefs'
			);
	
			foreach ($email_fields as $key => $value) {
				$field_notes[] = sprintf('<div>%s</div><div class="col-sm-offset-1">%s</div>', $key, $value);
			}
	
			$text = '<div>'.$this->prefs['email_templates']['title'].'<br/><br/>Available fields<br/>';
			$text .= '<div class="small">'.implode("\n", $field_notes).'</div></div>';
	
			$this->prefs['email_templates']['title'] = $text;
		}

		/*
		public function customPage()
		{
			$ns = e107::getRender();
			$text = 'Hello World!';
			$ns->tablerender('Hello',$text);	
			
		}
	*/
			
}
				


class vstore_cart_form_ui extends e_admin_form_ui
{

	public function init()
	{
		$js = "
		$(function(){
			$('.vstore-email-reset').click(function(){
				var type = $(this).data('type');
				var template = decodeURIComponent($(this).data('template'));

				var id = 'email-templates-'+type+'-template';
				$('#'+id).val(template);
				$(tinymce.get(id).getBody()).html(template);
			});
		});
		";
		e107::js('footer-inline', $js);
	}

	function additional_fields($curVal,$mode)
	{
		
		$tmp = range(0,3);

		$text = "<table class='table table-striped table-bordered'>
			<colgroup>
				<col style='width:100px' />
				<col style='width:auto' />
				<col style='width:auto' />
				<col style='width:10%' />
				<col style='width:100px' />
			</colgroup>";

			$opts = array('text'=>"Text Box",'checkbox'=> "Check box");

		foreach($tmp as $i)
		{

			$activeVal       = !empty($curVal[$i]['active']) ? $curVal[$i]['active'] : null;
			$capVal          = !empty($curVal[$i]['caption'][e_LANGUAGE]) ? $curVal[$i]['caption'][e_LANGUAGE] : null;
			$placeholderVal  = !empty($curVal[$i]['placeholder'][e_LANGUAGE]) ? $curVal[$i]['placeholder'][e_LANGUAGE] : null;
			$reqVal          = !empty($curVal[$i]['required']) ? $curVal[$i]['required'] : null;
			$typeVal         = !empty($curVal[$i]['type']) ? $curVal[$i]['type'] : 'text';

			$post = '<small class="input-group-addon"><i class="fa fa-language"><!-- --></i></small>';

			$text .= "
				<tr>
					<td>".$this->flipswitch('additional_fields['.$i.'][active]', $activeVal, null, array('switch'=>'small', 'title' => LAN_ACTIVE))."</td>
					<td><span class='input-group'>".$this->text('additional_fields['.$i.'][caption]['.e_LANGUAGE.']', $capVal, 30, array('placeholder'=>LAN_CAPTION, 'size'=>'block-level')).$post."</span></td>
					<td><span class='input-group'>".$this->text('additional_fields['.$i.'][placeholder]['.e_LANGUAGE.']',$placeholderVal, 30, array('placeholder'=>"Placeholder", 'size'=>'block-level')).$post."</span></td>
					<td>".$this->select('additional_fields['.$i.'][type]', $opts, $typeVal )."</td>
					<td>".$this->flipswitch('additional_fields['.$i.'][required]', $reqVal, null, array('switch'=>'small', 'title' => 'Required'))."</td>
				</tr>
			";

		}

		$text .= "</table>";

		return $text;
	}


	function email_templates($curVal, $mode)
	{
		$frm = e107::getForm();		

		e107::wysiwyg(true);

		$orig_templates = e107::getTemplate('vstore', 'vstore_email');
		$text = '';
		foreach (vstore::getEmailTypes() as $type => $label) {

			// $orig_template = e107::getTemplate('vstore', 'vstore_email', $type);
			$orig_template = $orig_templates[$type];
			if (empty($curVal[$type]['template']))
			{
				$curVal[$type]['template'] = $orig_template;
			}
			$isActive = isset($curVal[$type]['active']) ? $curVal[$type]['active'] : true;

			$text .= '<div><label><b>'.$label.'</b>';
			$text .= '<div><label>'.LAN_ACTIVE.'? '. $this->flipswitch('email_templates['.$type.'][active]', $isActive, null, array('switch'=>'small', 'title' => LAN_ACTIVE)).'</label>';
			$text .= $this->button('', '<span class="fa fa-undo"></span> '. 'Reset template', 'action', '', array('data-template' => rawurlencode($orig_template), 'data-type' => $type, 'class' => 'vstore-email-reset pull-right btn-sm', 'title' => 'Click & save to reset this template to the default template.'));
			$text .= '</div>';
			$text .= $this->textarea('email_templates['.$type.'][template]', $curVal[$type]['template'], null, null, array('class' => 'e-wysiwyg'));		
			$text .= '</label></div><br/>
			';

		}

		return $text;
		 		
	}

	function customer_userclass($curVal, $mode)
	{
		$frm = e107::getForm();
	
		$items = e107::getUserClass()->getClassList('nobody,member,classes');
		$items = array('-1' => 'As defined in product') + $items;
		return $frm->select('customer_userclass', $items, $curVal);
		
	}

	
	// Custom Method/Function 
	function cart_item($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('cart_item',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

	
	// Custom Method/Function 
	function cart_qty($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('cart_qty',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

	
	// Custom Method/Function 
	function cart_paystat($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('cart_paystat',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

	
	// Custom Method/Function 
	function cart_paydate($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('cart_paydate',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

	
	// Custom Method/Function 
	function cart_paytrans($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('cart_paytrans',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

	
	// Custom Method/Function 
	function cart_paygross($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('cart_paygross',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

	
	// Custom Method/Function 
	function cart_payshipping($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('cart_payshipping',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

	
	// Custom Method/Function 
	function cart_payshipto($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('cart_payshipto',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

}		
		

				
class vstore_cat_ui extends e_admin_ui
{
			
		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';
		protected $table			= 'vstore_cat';
		protected $pid				= 'cat_id';
		protected $perPage			= 10; 
		protected $batchDelete		= true;
		protected $batchCopy		= true;
		protected $batchExport		= true;

		protected $sortField		= 'cat_order';
		protected $sortParent       = 'cat_parent';
		protected $treePrefix       = 'cat_name';


	//	protected $tabs			= array('Tabl 1','Tab 2'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable. 

	
		protected $fields 		= array (  
			'checkboxes' 		=>   array ( 'title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => '1', 'class' => 'center', 'toggle' => 'e-multiselect',  ),
		  	'cat_id' 			=>   array ( 'title' => LAN_ID, 'data' => 'int', 'width' => '5%', 'help' => '', 'readParms' => 'url=category&target=dialog', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  	'cat_name' 			=>   array ( 'title' => LAN_TITLE, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => array('size'=>'xxlarge'), 'class' => 'left', 'thclass' => 'left',  ),
		    'cat_description' 	=>   array ( 'title' => LAN_DESCRIPTION, 'type' => 'textarea', 'data' => 'str', 'width' => '40%', 'help' => '', 'readParms' => '', 'writeParms' => array('maxlength' => 220, 'size'=>'xxlarge'), 'class' => 'left', 'thclass' => 'left',  ),
		  	'cat_sef' 			=>   array ( 'title' => LAN_SEFURL, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'batch'=>true, 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => array('size'=>'xxlarge','sef'=>'cat_name'), 'class' => 'left', 'thclass' => 'left',  ),
			'cat_parent'        =>  array('title'=>"Parent", 'type'=>'dropdown', 'data'=>'int', 'inline'=>true,  'width'=>'auto'),
		  	'cat_image' 		=>   array ( 'title' => LAN_IMAGE, 'type' => 'image', 'data' => 'str', 'width' => '40%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),	
		 	'cat_info' 			=>   array ( 'title' => "Details", 'type' => 'bbarea', 'data' => 'str', 'width' => '40%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  	'cat_class' 		=>   array ( 'title' => LAN_USERCLASS, 'type' => 'userclass', 'data' => 'str', 'width' => 'auto', 'batch' => true, 'filter' => true, 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  	'cat_order' 		=>   array ( 'title' => LAN_ORDER, 'type' => 'text', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  	'options' 			=>   array ( 'title' => 'Options', 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced' => '1', 'sort'=>1  ),
		);		
		
		protected $fieldpref = array('cat_id', 'cat_name', 'cat_sef', 'cat_class');



		public function beforeCreate($new_data,$old_data)
		{
			if(!empty($new_data['cat_name']) && isset($new_data['cat_sef']) && empty($new_data['cat_sef']))
			{
				$new_data['cat_sef'] = eHelper::title2sef($new_data['cat_name'], 'dashl');
			}

			return $new_data;
		}

		public function afterCreate($new_data, $old_data, $id)
		{
			// do something

		}

		public function beforeUpdate($new_data, $old_data, $id)
		{
			if(!empty($new_data['cat_name']) && isset($new_data['cat_sef']) && empty($new_data['cat_sef']))
			{
				$new_data['cat_sef'] = eHelper::title2sef($new_data['cat_name'], 'dashl');
			}

			return $new_data;
		}

		public function afterUpdate($new_data, $old_data, $id)
		{

		}

		public function onCreateError($new_data, $old_data)
		{
			// do something
		}

		public function onUpdateError($new_data, $old_data, $id)
		{
			// do something
		}


				// Correct bad ordering based on parent/child relationship.
		private function checkOrder()
		{
			$sql = e107::getDb();
		//	$sql2 = e107::getDb('sql3');
			$count = $sql->select('vstore_cat', 'cat_id', 'cat_order = 0');

			if($count > 1)
			{
				$data = $sql->retrieve("SELECT cat_id,cat_name,cat_parent,cat_order FROM `#vstore_cat` ORDER BY COALESCE(NULLIF(cat_parent,0), cat_id), cat_parent > 0, cat_order ",true);

				$c = 0;
				$parent = 1;
				foreach($data as $row)
				{


					if(empty($row['cat_parent']))
					{

						$c = $parent * 10;
						$parent++;
					}
					else
					{
						$c = $c+1;
					}
					
					$sql->update('vstore_cat', 'cat_order = '.intval($c).' WHERE cat_id = '.intval($row['cat_id']).' LIMIT 1');
				}


			}


		}

		// optional
		public function init()
		{
			$this->perPage = e107::pref('vstore','admin_categories_perpage',10);

		//	$this->checkOrder();

			/*$data = e107::getDb()->retrieve('vstore_cat','cat_id,cat_name', "cat_parent = 0", true);

			$this->fields['cat_parent']['writeParms']['optArray'] = array(0=>'(Root)');

			foreach($data as $v)
			{
				$key = $v['cat_id'];
				$this->fields['cat_parent']['writeParms']['optArray'][$key] = $v['cat_name'];
			}*/

			$this->setVstoreCategoryTree();

		}



	private function setVstoreCategoryTree()
	{


		$sql = e107::getDb();
		$qry = $this->getParentChildQry(true);
		$sql->gen($qry);

		$this->fields['cat_parent']['writeParms']['optArray'] = array(0=>'(Root)');

		while($row = $sql->fetch())
		{
			$num = $row['_depth'] - 1;
			$id = $row['cat_id'];
			$this->fields['cat_parent']['writeParms']['optArray'][$id] = str_repeat("&nbsp;&nbsp;",$num).$row['cat_name'];
		}

		if($this->getAction() === 'edit') // make sure parent is not the same as ID.
		{
			$r = $this->getId();
			unset($this->fields['cat_parent']['writeParms']['optArray'][$r]);
		}

	}



	/*
		public function customPage()
		{
			$ns = e107::getRender();
			$text = 'Hello World!';
			$ns->tablerender('Hello',$text);	
			
		}
	*/
			
}
				


class vstore_cat_form_ui extends e_admin_form_ui
{/*
		function cat_name($curVal,$mode,$parm)
		{

			$frm = e107::getForm();

			if($mode == 'read')
			{
				return $curVal;
			}

			if($mode == 'write')
			{
				return $frm->text('cat_name',$curVal,255,'size=xxlarge');
			}

			if($mode == 'filter')
			{
				return false;
			}
			if($mode == 'batch')
			{
				return false;
			}

			if($mode == 'inline')
			{
				$parent 	= $this->getController()->getFieldVar('cat_parent');

				$ret = array('inlineType'=>'text');

				if(empty($parent))
				{

				}
				else
				{
					$ret['inlineParms'] = array('pre'=>'<img src="'.e_IMAGE_ABS.'generic/branchbottom.gif" class="level-1 icon" alt="" />');
				}

				return $ret;
			}
		}*/
}		
		

				
class vstore_items_ui extends e_admin_ui
{
			
		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';
		protected $table			= 'vstore_items';
		protected $pid				= 'item_id';
		protected $perPage			= 10; 
		protected $batchDelete		= true;
		protected $batchCopy		= true;		
		protected $sortField		= 'item_order';
	//	protected $orderStep		= 10;
		protected $tabs			    = array('Basic','Details', 'Reviews', 'Files'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable.
		
	//	protected $listQry      	= "SELECT * FROM #tableName WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.
	
		protected $listOrder		= 'item_id DESC';

		protected $grid             = array('title'=>'item_name', 'image'=>'item_preview', 'body'=>'',  'class'=>'col-md-2', 'perPage'=>12, 'carousel'=>true);
	
		protected $fields 		= array (  
		  'checkboxes' 			=>   array ( 'title' => '', 'type' => null, 'data' => null, 	'width' => '5%', 'thclass' => 'center', 'forced' => '1', 'class' => 'center', 'toggle' => 'e-multiselect',  ),
		  'item_preview'        =>   array( 'title' => LAN_PREVIEW, 'type'=>'method', 'data'=>false, 'width'=>'5%', 'forced'=>1),
		  'item_id' 			=>   array ( 'title' => LAN_ID, 			'type'=>'text', 'data' => 'int', 	'width' => '5%', 'help' => '', 'readParms'=>'link=sef&target=blank', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'item_code' 			=>   array ( 'title' => 'Code', 			'type' => 'text', 'inline'=>true,	'data' => 'str', 'width' => '2%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'item_name'			=>   array ( 'title' => LAN_TITLE, 			'type' => 'text', 	'data' => 'str', 'width' => 'auto', 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => array('size'=>'xxlarge'), 'class' => 'left', 'thclass' => 'left',  ),
		  'item_desc' 			=>   array ( 'title' => 'Description', 		'type' => 'textarea', 	'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => array('size'=>'xxlarge','maxlength'=>250), 'class' => 'center', 'thclass' => 'center',  ),
		  'item_cat' 			=>   array ( 'title' => 'Category', 		'type' => 'dropdown', 'data' => 'int', 'width' => 'auto', 'filter'=>true, 'batch'=>true, 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'item_pic' 			=>   array ( 'title' => 'Images/Videos', 			'type' => 'images', 'data' => 'array', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => 'media=vstore&video=1&max=8', 'class' => 'center', 'thclass' => 'center',  ),
	 	  'item_files' 			=>   array ( 'title' => 'Files', 			'type' => 'files', 'tab'=>3, 'data' => 'array', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => 'media=vstore_file_2', 'class' => 'center', 'thclass' => 'center',  ),
		  'item_price' 			=>   array ( 'title' => 'Price', 			'type' => 'text', 'data' => 'str', 'width' => 'auto', 'inline'=>true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'right', 'thclass' => 'right',  ),
		  'item_shipping' 		=>   array ( 'title' => 'Shipping', 		'type' => 'text', 'data' => 'str', 'width' => 'auto',  'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'item_details' 		=>   array ( 'title' => 'Details', 			'type' => 'bbarea', 'tab'=>1, 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
	 
		  'item_reviews' 		=>   array ( 'title' => 'Reviews', 			'type' => 'textarea', 'tab'=>2, 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => 'size=xxlarge', 'class' => 'center', 'thclass' => 'center',  ),
		  'item_related' 		=>   array ( 'title' => 'Related', 			'type' => 'method', 'tab'=>2, 'data' => 'array', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => 'video=1', 'class' => 'center', 'thclass' => 'center',  ),

		  'item_order' 			=>   array ( 'title' => LAN_ORDER, 			'type' => 'hidden', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'item_inventory' 		=>   array ( 'title' => 'Inventory', 		'type' => 'method', 'data' => 'int', 'width' => 'auto', 'inline'=>true, 'help' => 'Enter -1 if this item is always available', 'readParms' => '', 'writeParms' => '', 'class' => 'right item-inventory', 'thclass' => 'right',  ),
		  'item_vars' 	        =>   array ( 'title' => 'Product Variations', 	'type' => 'method'), //'type' => 'comma', 'data' => 'str', 'width' => 'auto', 'inline'=>true, 'help' => '', 'readParms' => '', 'writeParms' => array(), 'class' => 'right item-inventory', 'thclass' => 'right',  ),
		  'item_vars_inventory' =>   array ( 'title' => 'Variations Inventory', 'type' => 'method', 'data' => 'json'), //, 'data' => 'str', 'width' => 'auto', 'inline'=>true, 'help' => '', 'readParms' => '', 'writeParms' => array(), 'class' => 'right item-inventory', 'thclass' => 'right',  ),

		  'item_userclass'      =>   array ( 'title' => 'Assign userclass', 'type' => 'method', 'help' => 'Assign userclass to customer on purchase'),
		  
		  'item_link' 			=>   array ( 'title' => 'External Link', 	'type' => 'text', 'tab'=>3, 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'item_download' 		=>   array ( 'title' => 'Download File', 	'type' => 'file', 'tab'=>3, 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => 'media=vstore_file', 'class' => 'center', 'thclass' => 'center',  ),
			
		  'options' 			=>   array ( 'title' => LAN_OPTIONS, 		'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'right last', 'class' => 'right last', 'forced' => '1',  ),
		);		
		
		protected $fieldpref = array('item_code', 'item_name', 'item_sef', 'item_cat', 'item_price', 'item_inventory');
				
		protected $categories = array();
		protected $categoriesTree = array();
	
		// optional
		public function init()
		{
			if($this->getAction() != 'list' && $this->getAction() != 'grid')
			{
				$this->fields['item_preview']['type'] = null;
			}

			$this->perPage = e107::pref('vstore','admin_items_perpage',10);

			if($data = e107::getDb()->retrieve('SELECT item_var_id,item_var_name FROM #vstore_items_vars ORDER BY item_var_name', true))
			{
				foreach($data as $k=>$v)
				{
					$key = $v['item_var_id'];
					$this->fields['item_vars']['writeParms'][$key] = $v['item_var_name'];
				}
			}
		//	print_a($_POST);

			
			$data = e107::getDb()->retrieve('SELECT cat_id,cat_name,cat_parent FROM #vstore_cat ORDER BY cat_order', true);
			$parent = array();

			foreach($data as $k=>$v)
			{
				$id = $v['cat_id'];
				$parent[$id] = $v['cat_name'];
				$pid = $v['cat_parent'];
				$name = $parent[$pid];
				$this->categories[$id] = $v['cat_name'];
				$this->categoriesTree[$name][$id] = $v['cat_name'];
			}


			$this->fields['item_cat']['writeParms'] = ($this->getAction() == 'list') ? $this->categories : $this->categoriesTree;
		//	print_a($this->categories);
			
			e107::css('inline', 'table input.form-control{ width: 80px; }');
		}


		public function beforeCreate($new_data,$old_data)
		{
			return $new_data;
		}

		public function afterCreate($new_data, $old_data, $id)
		{
			// do something
		}

		public function beforeUpdate($new_data, $old_data, $id)
		{
			if ($new_data['item_vars'] !== explode(',', $old_data['item_vars']))
			{
				if ($new_data['item_vars'] == '')
				{
					$new_data['item_vars_inventory'] = '';
				}
				else //if ($old_data['item_vars'] != '')
				{

					$new = $new_data['item_vars'];
					if (count($new)>2)
					{
						// Only 2 vars allowed
						$new_data['item_vars'] = array($new[0], $new[1]);
						// $new = explode(',', $new_data['item_vars']);
					}
					// Item vars have changed
					// Initialize inventory
					$new_data['item_vars_inventory'] = '';

				}
			}
			if (array_key_exists('item_vars', $new_data))
			{
				$new_data['item_vars'] = implode(',', $new_data['item_vars']);
			}

			return $new_data;
		}

		public function afterUpdate($new_data, $old_data, $id)
		{

		}

		public function onCreateError($new_data, $old_data)
		{
			// do something
		}

		public function onUpdateError($new_data, $old_data, $id)
		{
			// do something
		}


	/*	
		
		public function customPage()
		{
			$ns = e107::getRender();
			$text = 'Hello World!';
			$ns->tablerender('Hello',$text);	
			
		}
	*/
			
}
				


class vstore_items_form_ui extends e_admin_form_ui
{

	function item_preview($curVal, $mode, $parm)
	{
		$tp = e107::getParser();


	//return print_a($parm, true);

		//var_dump($parm);

		$size = $this->getController()->getAction() === 'grid' ? 400: 80;

		if($mode == 'read')
		{
			$img = $this->getController()->getFieldVar('item_pic');

			if($media = e107::unserialize($img))
			{
				foreach($media as $v)
				{
					if(!$tp->isVideo($v['path']))
					{
						return $tp->toImage($v['path'], array('w'=>$size,'h'=>$size, 'crop'=>1));
					}
				}
			}


		}

		return false;


	}
	
	// Custom Method/Function 
	function item_cat($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('item_cat',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}
	}


		// Custom Method/Function
	function item_inventory($curVal,$mode)
	{
		$frm = e107::getForm();

		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;

			case 'write': // Edit Page
				$text = $frm->text('item_inventory', $curVal, null, array('pattern' => '^-?\d+$')); // to allow also negative values (<0 = Item will not run out of stock)
				$text .= '<span class="small">In case of any Product Variations selected, this setting will ignored! You have to fill out the Variations Inventory instead!</span>';
				return $text;
				// return $frm->text('item_inventory', $curVal, null, array('pattern' => '^-?\d+$')); // to allow also negative values (<0 = Item will not run out of stock)
				//return $frm->number('item_inventory',$curVal);
			break;

			case 'filter':
			case 'batch':
				return  null;
			break;

			case 'inline':
				$class = '';

				if($curVal < 1)
				{
					$class = 'text-danger';
				}
				elseif($curVal < 3)
				{
					$class = 'text-warning';
				}


				return array('inlineType'=>'text', 'inlineData'=>$curVal, 'inlineParms'=>array( 'class'=>$class));
			break;
		}
	}


	function item_vars_inventory($curVal, $mode)
	{
		$item_vars = $this->getController()->getFieldVar('item_vars');

		if ($item_vars == '')
		{
			return 'You need to select the Product Variations first!';
		}

		$sql = e107::getDb();
		$frm = e107::getForm();

		if ($sql->select('vstore_items_vars', '*', sprintf('FIND_IN_SET(item_var_id, "%s") LIMIT 2', $item_vars)))
		{

			if ($curVal && !is_array($curVal))
			{
				$curVal = e107::unserialize($curVal);
			}


			$col = array();
			$key = 'x';
			while($item = $sql->fetch())
			{
				$col[$key]['id'] = $item['item_var_id'];
				$col[$key]['caption'] = $item['item_var_name'];
				$attr = e107::unserialize($item['item_var_attributes']);
				foreach ($attr as $row) {
					$col[$key]['names'][] = $row['name'];
				}
				$key = 'y';
			}

			$text = '<table class="table table-striped table-bordered">
			';
			if (count($col)==2)
			{
				$text .= sprintf('<tr><th>%s</th><th colspan="%d">%s</th></tr>', 'Inventory', count($col['y']['names']), $col['y']['caption']);

				$text .= sprintf('<tr><th>%s</th>', $col['x']['caption']);
				foreach ($col['y']['names'] as $value) {
					$text .= sprintf('<th>%s</th>', $value);
				}
			
				$text .= '</tr>
				';
			}

			if (count($col) == 1)
			{
				$text .= sprintf('<tr><th style="width: 20%%;">%s</th><th>%s</th>', $col['x']['caption'], 'Inventory');
				foreach ($col['x']['names'] as $nameX) {
					$text .= sprintf('<tr><th style="width: 20%%;">%s</th>', $nameX);
					$nameX = $frm->name2id($nameX);
					$value = varset($curVal[$nameX], 0);
					$text .= sprintf('<td>%s</td>', $frm->text('item_vars_inventory['.$nameX.']', $value, 5, array('pattern' => '^-?\d+$', 'size' => 'sm')));
					$text .= '</tr>
					';
				}
			}
			else
			{
				foreach ($col['x']['names'] as $nameX) {
					$text .= sprintf('<tr><th style="width: 20%%;">%s</th>', $nameX);
					$nameX = $frm->name2id($nameX);
					foreach ($col['y']['names'] as $nameY) {
						$nameY = $frm->name2id($nameY);
						$value = varset($curVal[$nameX][$nameY], 0);
						$text .= sprintf('<td>%s</td>', $frm->text('item_vars_inventory['.$nameX.']['.$nameY.']', $value, 5, array('pattern' => '^-?\d+$', 'size' => 'sm')));
					}
					$text .= '</tr>
					';
				}
			}
			$text .= '</table>
			';

			return $text;
		}
		return e107::getMessage()->addError('Product Variations not found! Maybe they have been deleted in the meanwhile ...', 'vstore')->render('vstore');
	}

	function item_vars($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				$opt_array = array();
				if($data = e107::getDb()->retrieve('SELECT item_var_id,item_var_name FROM #vstore_items_vars ORDER BY item_var_name', true))
				{
					foreach($data as $k=>$v)
					{
						$key = $v['item_var_id'];
						$opt_array[$key] = $v['item_var_name'];
					}
				}

				$text = $frm->select('item_vars', $opt_array, $curVal, array('multiple'=>1));
				if ($curVal)
				{
					$text .= '<p class="small">Do not select more than 2 variations, as only the first 2 will be stored.<br>
						<b>Be aware, that changing this setting will initialize the Variations Inventory table during save!</b></p>';
				}
				else
				{
					$text .= '<p class="small">Select up to 2 variations, save product and reopen it to access the Variations Inventory table</p>';
				}
				return $text; 		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}
	}
	
	// Custom Method/Function 
	function item_pic($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('item_pic',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}
	}

	
	// Custom Method/Function 
	function item_price($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('item_price',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}
	}

	
	// Custom Method/Function 
	function item_ph($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('item_ph',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}
	}

	
	// Custom Method/Function 
	function item_details($curVal,$mode)
	{
		$frm = e107::getForm();		
		 		
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $frm->text('item_details',$curVal);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}
	}


	// Custom Method/Function 
	function item_related($curVal,$mode)
	{
		$frm = e107::getForm();		
		
		$chp = e107::getDb()->retrieve('page_chapters', '*', 'chapter_parent !=0 ORDER BY chapter_order', true);
				
		foreach($chp as $row)
		{
			$id = 'page_chapters|'.$row['chapter_id'];
			$opt[$id] = $row['chapter_name'];	
		}
		
		$options['Chapters'] = $opt; 
				
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return "Tab Name: ". $frm->text('item_related[caption]',$curVal['caption'])."<br />Source: ".$frm->select('item_related[src]',$options, $curVal['src'],null,true);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}
	}

	function item_userclass($curVal, $mode)
	{
		$frm = e107::getForm();
		$uc = intval(e107::pref('vstore', 'customer_userclass'));


		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				if ($uc !== -1)
				{
					$text = $frm->text('', e107::getDB()->retrieve('userclass_classes', 'userclass_name', 'userclass_id='.$uc), null, array('disabled' => true, 'title'=>'Userclass defined in store preferences'));
					$text .= $frm->hidden('item_userclass', $curVal);
					return $text;
				}
				else
				{
					$items = e107::getUserClass()->getClassList('nobody,member,classes');
					return $frm->select('item_userclass', $items, $curVal, array('readonly' => ($uc !== -1)));
				}
			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}
	}

	
}		



class vstore_items_vars_ui extends e_admin_ui
{

		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';
	//	protected $eventName		= 'vstore-vstore_items_vars'; // remove comment to enable event triggers in admin.
		protected $table			= 'vstore_items_vars';
		protected $pid				= 'item_var_id';
		protected $perPage			= 10;
		protected $batchDelete		= true;
		protected $batchExport     = true;
		protected $batchCopy		= true;

	//	protected $sortField		= 'somefield_order';
	//	protected $sortParent      = 'somefield_parent';
	//	protected $treePrefix      = 'somefield_title';

	//	protected $tabs				= array('Tabl 1','Tab 2'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable.

	//	protected $listQry      	= "SELECT * FROM `#tableName` WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.

		protected $listOrder		= 'item_var_id DESC';

		protected $fields 		= array (  'checkboxes' =>   array ( 'title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => '1', 'class' => 'center', 'toggle' => 'e-multiselect',  ),
		  'item_var_id'         =>   array ( 'title' => LAN_ID, 'data' => 'int', 'width' => '5%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'item_var_name'       =>   array ( 'title' => LAN_NAME, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'inline' => true, 'help' => 'Enter a name for the group, for eg. "Size" ', 'readParms' => '', 'writeParms'  => array('size'=>'xxlarge'), 'class' => 'left', 'thclass' => 'left',  ),
		//   'item_var_info'       =>   array ( 'title' => 'Info', 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'inline' => true,'help' => 'Only displays in admin area, help identify group.', 'readParms' => '', 'writeParms' => array('size'=>'xxlarge'), 'class' => 'left', 'thclass' => 'left',  ),
		  'item_var_attributes' =>   array ( 'title' => 'Attributes', 'type' => 'method', 'data' => 'json', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'item_var_compulsory' =>   array ( 'title' => 'Required', 'type' => 'boolean', 'data' => 'int', 'width' => 'auto', 'batch' => true, 'inline' => true, 'help' => 'A selection will be required', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'item_var_userclass'  =>   array ( 'title' => 'Userclass', 'type' => 'userclass', 'data' => 'int', 'width' => 'auto', 'batch' => true, 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'options'             =>   array ( 'title' => LAN_OPTIONS, 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced' => '1',  ),
		);

		// protected $fieldpref = array('item_var_name', 'item_var_info', 'item_var_compulsory', 'item_var_userclass');
		protected $fieldpref = array('item_var_name', 'item_var_compulsory', 'item_var_userclass');


	//	protected $preftabs        = array('General', 'Other' );
		protected $prefs = array(
		);


		public function init()
		{
			// Set drop-down values (if any).

		}


		// ------- Customize Create --------

		public function beforeCreate($new_data,$old_data)
		{
			if(!empty($new_data['item_var_attributes']))
			{
				$new_data['item_var_attributes'] = $this->cleanItemVarAttributes($new_data['item_var_attributes']);
			}
			return $new_data;
		}

		public function afterCreate($new_data, $old_data, $id)
		{
			// do something
		}

		public function onCreateError($new_data, $old_data)
		{
			// do something
		}


		// ------- Customize Update --------

		public function beforeUpdate($new_data, $old_data, $id)
		{
			if(!empty($new_data['item_var_attributes']))
			{
				$new_data['item_var_attributes'] = $this->cleanItemVarAttributes($new_data['item_var_attributes']);
			}

			return $new_data;
		}

		private function cleanItemVarAttributes($arr)
		{
			$ret = array();
			foreach($arr as $k=>$v)
			{
				if(empty($v['name']))
				{
					continue;
				}

				$ret[$k] = $v;

			}

			return $ret;

		}

		public function afterUpdate($new_data, $old_data, $id)
		{
			// do something
		}

		public function onUpdateError($new_data, $old_data, $id)
		{
			// do something
		}

		// left-panel help menu area.
		public function renderHelp()
		{
		//	$caption = LAN_HELP;
		///	$text = 'Some help text';

		//	return array('caption'=>$caption,'text'=> $text);

		}

	/*
		// optional - a custom page.
		public function customPage()
		{
			$text = 'Hello World!';
			$otherField  = $this->getController()->getFieldVar('other_field_name');
			return $text;

		}




	*/

}



class vstore_items_vars_form_ui extends e_admin_form_ui
{


	// Custom Method/Function
	function item_var_attributes($curVal,$mode)
	{


		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;

			case 'write': // Edit Page

				$opts = array('+'=>'+', '-' => '-', '%' => '%');

				if(!empty($curVal))
				{
					$cur = e107::unserialize($curVal);
				}
				else
				{
					$cur = array(0 => array('name'=>null, 'operator'=>null, 'value'=>null));
				}

				$text = '
					<div class="item-var-attributes-container">';

					foreach($cur as $i=>$v)
					{

						$text .= '	
							<div class="form-inline item-var-attributes-row" style="margin-bottom:5px">'.
							$this->text('item_var_attributes['.$i.'][name]',$v['name'],255, array('id'=>null, 'size'=>'xlarge','placeholder'=>'Option name')).
							" ".$this->select('item_var_attributes['.$i.'][operator]',$opts,$v['operator'], array('id'=>null)).
							" ".$this->text('item_var_attributes['.$i.'][value]', $v['value'], 8, array('id'=>null,'placeholder'=>"Price Modifier"))
							.'</div>';

					}

					$text .= '
					</div>
										
				';

				$text .= $this->button('clone',1,'action', "<i class='fa fa-plus'></i> ".LAN_ADD, array('class'=>'btn btn-primary btn-sm'));

				e107::js('footer-inline', "
				
				
					$('#clone').on('click', function()
					{
				
						var row = $('.item-var-attributes-row:first').clone();
						var rowCount = $('.item-var-attributes-row').length;
											
						row.find('input,select').val('');				
						row.html(row.html().replace(/\[0\]/g,'[' + rowCount + ']'));
					
						row.css('display', 'none');
						
						$('.item-var-attributes-container').append(row);
						row.show('slow');						
			
							
					});
				
				");



				return $text;
			break;

			case 'filter':
			case 'batch':
				return  array();
			break;
		}
	}

}
		
new vstore_admin();

require_once(e_ADMIN."auth.php");
e107::getAdminUI()->runPage();

require_once(e_ADMIN."footer.php");
exit;

?>
