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

e107::lan('vstore',false, true);
e107::lan('vstore', 'admin', true);

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
		

	);	
	
	
	protected $adminMenu = array(

	
	//	'main/create'		=> array('caption'=> LAN_CREATE, 'perm' => 'P'),

		'products/list'			=> array('caption'=> LAN_VSTORE_ADMIN_01, 'perm' => 'P'),
		'products/create'		=> array('caption'=> LAN_VSTORE_ADMIN_02, 'perm' => 'P'),
	//	'cart/create'		=> array('caption'=> LAN_CREATE, 'perm' => 'P'),

		'other/0'           => array('divider'=>true),

		'cat/list'			=> array('caption'=> LAN_CATEGORIES, 'perm' => 'P'),
		'cat/create'		=> array('caption'=> LAN_CREATE_CATEGORY, 'perm' => 'P'),

		'other/1'           => array('divider'=>true),

		'orders/list'		=> array('caption'=> LAN_VSTORE_ADMIN_03, 'perm' => 'P'),
		
	//	'main/list'			=> array('caption'=> "Customers", 'perm' => 'P'),

		'other/2'           => array('divider'=>true),
	
		'main/prefs' 		=> array('caption'=> LAN_PREFS, 'perm' => 'P'),

		'orders/prefs'		=> array('caption'=> LAN_VSTORE_ADMIN_04, 'perm' => 'P'),

		// 'main/custom'		=> array('caption'=> 'Custom Page', 'perm' => 'P')
	);

	protected $adminMenuAliases = array(
		'products/edit'	=> 'products/list',
		'orders/edit'	=> 'orders/list'
	);	
	
	protected $menuTitle = 'Vstore';

		function init()
		{
			if(deftrue('e_DEBUG'))
			{
				$this->adminMenu['products/grid'] = array('caption'=> "Products (Grid)", 'perm' => 'P');
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
		  'cust_userid' =>   array ( 'title' => USFLAN_6, 'type' => 'method', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_datestamp' =>   array ( 'title' => LAN_DATESTAMP, 'type' => 'datestamp', 'data' => 'int', 'width' => 'auto', 'filter' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'cust_prename' =>   array ( 'title' => LAN_VSTORE_ADMIN_026, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_firstname' =>   array ( 'title' => LAN_VSTORE_ADMIN_05, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_lastname' =>   array ( 'title' => LAN_VSTORE_ADMIN_06, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_company' =>   array ( 'title' => LAN_VSTORE_025, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_title' =>   array ( 'title' => LAN_TITLE, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'cust_address' =>   array ( 'title' => LAN_VSTORE_026, 'type' => 'textarea', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_city' =>   array ( 'title' => LAN_VSTORE_027, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_state' =>   array ( 'title' => LAN_VSTORE_028, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_postcode' =>   array ( 'title' => LAN_VSTORE_029, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_country' =>   array ( 'title' => LAN_VSTORE_030, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_email' =>   array ( 'title' => LAN_EMAIL, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_email2' =>   array ( 'title' => 'Email2', 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_phone_day' =>   array ( 'title' => LAN_VSTORE_ADMIN_027, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_phone_night' =>   array ( 'title' => LAN_VSTORE_ADMIN_028, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_comments' =>   array ( 'title' => LAN_COMMENTS, 'type' => 'textarea', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_website' =>   array ( 'title' => LAN_URL, 'type' => 'url', 'data' => 'str', 'width' => 'auto', 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'cust_ip' =>   array ( 'title' => 'Ip', 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_assigned_to' =>   array ( 'title' => LAN_VSTORE_ADMIN_029, 'type' => 'method', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_interested' =>   array ( 'title' => LAN_VSTORE_ADMIN_030, 'type' => 'method', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_notes' =>   array ( 'title' => LAN_VSTORE_ADMIN_031, 'type' => 'textarea', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_refcode' =>   array ( 'title' => LAN_VSTORE_ADMIN_032, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
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
	//	protected $eventName		= 'test-vstore_trans'; // remove comment to enable event triggers in admin.
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
		 'checkboxes'           =>   array ( 'title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => '1', 'class' => 'center', 'toggle' => 'e-multiselect',  ),
		  'order_id'            =>   array ( 'title' => LAN_ID, 'data' => 'int', 'width' => '5%', 'help' => '', 'readonly'=>true, 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'order_status'          => array('title'=>LAN_STATUS, 'type'=>'dropdown', 'data'=>'str', 'inline'=>true, 'filter'=>true, 'batch'=>true,'width'=>'5%'),
		  'order_date'          =>   array ( 'title' => LAN_DATESTAMP, 'type' => 'datestamp', 'data' => 'str',  'readonly'=>true, 'width' => 'auto', 'filter' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),

		  'order_session'       =>   array ( 'title' => LAN_VSTORE_ADMIN_013, 'type' => 'text', 'tab'=>1, 'data' => 'str', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'order_ship_to'      =>  array('title'=> LAN_VSTORE_ADMIN_018, 'type'=>'method', 'data'=>false, 'width'=>'20%'),
		  'order_items'     =>   array ( 'title' => LAN_VSTORE_ADMIN_019, 'type' => 'method', 'data' => false, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'right', 'thclass' => 'right',  ),
		 'order_e107_user'     =>   array ( 'title' => LAN_AUTHOR, 'type' => 'method', 'data' => 'str', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		 'order_pay_gateway'       =>   array ( 'title' => LAN_VSTORE_ADMIN_020, 'type' => 'text', 'data' => 'str', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		 'order_pay_status'        =>   array ( 'title' => LAN_VSTORE_ADMIN_021, 'type' => 'text',  'data' => 'str',  'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		 'order_pay_transid'       =>   array ( 'title' => 'TransID', 'type' => 'text', 'data' => 'str', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'order_pay_amount' =>   array ( 'title' => LAN_VSTORE_042, 'type' => 'method', 'data' => 'int', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'order_pay_shipping' =>   array ( 'title' => LAN_VSTORE_ADMIN_012, 'type' => 'number', 'data' => 'int', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'order_pay_rawdata' =>   array ( 'title' => LAN_VSTORE_ADMIN_022, 'type' => 'method', 'tab'=>1, 'data' => 'str', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'options' =>   array ( 'title' => LAN_OPTIONS, 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced' => '1',  ),
		);

		protected $fieldpref = array('order_id','order_ship_to', 'order_status', 'order_date', 'order_items', 'order_pay_transid','order_pay_amount','order_pay_status');


		protected $preftabs = array('Paypal', 'Amazon', 'Skrill');


		protected $prefs = array(
			'paypal_active'         => array('title'=>LAN_VSTORE_ADMIN_043, 'type'=>'boolean', 'tab'=>0, 'data'=>'int', 'help'=>''),
			'paypal_testmode'         => array('title'=>"Paypal Testmode", 'type'=>'boolean', 'tab'=>0, 'data'=>'int', 'writeParms'=>array(),'help'=>'Use Paypal Sandbox'),
			'paypal_username'       => array('title'=>LAN_VSTORE_ADMIN_044, 'type'=>'text', 'tab'=>0, 'data'=>'str', 'writeParms'=>array('size'=>'xxlarge'), 'help'=>''),
			'paypal_password'       => array('title'=>LAN_VSTORE_ADMIN_045, 'type'=>'text', 'tab'=>0, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
			'paypal_signature'      => array('title'=>LAN_VSTORE_ADMIN_046, 'type'=>'text', 'tab'=>0, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),

			'amazon_active'         => array('title'=>LAN_VSTORE_ADMIN_047, 'type'=>'boolean', 'tab'=>1, 'data'=>'int', 'help'=>''),
			'amazon_merchant_id'    => array('title'=>"Amazon Merchant ID", 'type'=>'text', 'tab'=>1, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
			'amazon_secret_key'     => array('title'=>"Amazon Secret Key", 'type'=>'text', 'tab'=>1, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
			'amazon_region'         => array('title'=> LAN_VSTORE_ADMIN_048, 'type'=>'dropdown', 'tab'=>1, 'data'=>'str', 'writeParms'=>array('optArray'=>array('us'=>'USA','de'=>"Germany",'uk'=>"United Kingdom",'jp'=>"Japan")), 'help'=>''),

			'skrill_active'         => array('title'=> LAN_VSTORE_ADMIN_049, 'type'=>'boolean', 'tab'=>2, 'data'=>'int', 'help'=>''),
			'skrill_email'          => array('title'=>"Skrill Email", 'type'=>'text', 'tab'=>2, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
		);



		public function init()
		{
			$this->fields['order_status']['writeParms']['optArray'] = vstore::getStatus();
			// Set drop-down values (if any).

			if(e_DEBUG !== true)
			{
				unset($this->preftabs[1],$this->preftabs[2]); // Disable Amazon and Skrill for Now until they work. // TODO //FIXME
			}
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
			return $new_data;
		}

		public function afterUpdate($new_data, $old_data, $id)
		{
			// do something
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
					<th>".LAN_TITLE."</th>
					<th>".LAN_VSTORE_ADMIN_023."</th>
					<th class='text-right'>Qty.</th>

					<th class='text-right'>".LAN_VSTORE_041."</th>
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
		  'cart_session' =>   array ( 'title' => LAN_VSTORE_ADMIN_013, 'type' => 'hidden', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_e107_user' =>   array ( 'title' => LAN_USER, 'type' => 'hidden', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_status' =>   array ( 'title' => LAN_VSTORE_049, 'type' => 'dropdown', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_item' =>   array ( 'title' => LAN_VSTORE_ADMIN_019, 'type' => 'method', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_qty' =>   array ( 'title' => 'Qty', 'type' => 'method', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_paystat' =>   array ( 'title' => 'Paystat', 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_paydate' =>   array ( 'title' => 'Paydate', 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_paytrans' =>   array ( 'title' => 'Paytrans', 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_paygross' =>   array ( 'title' => 'Paygross', 'type' => 'method', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_payshipping' =>   array ( 'title' => 'Payshipping', 'type' => 'method', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cart_payshipto' =>   array ( 'title' => 'Payshipto', 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'options' =>   array ( 'title' => LAN_OPTIONS, 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced' => '1',  ),
		);
		
		protected $fieldpref = array();



/*
 * merchant_id 	Default : null
Access Key 	access_key 	Default : null
Secret Key 	secret_key 	Default : null
Region 	region
 */
		// optional
		protected $preftabs = array(LAN_GENERAL, LAN_VSTORE_039, LAN_VSTORE_ADMIN_07);


		protected $prefs = array(
			'caption'                   => array('title'=> LAN_VSTORE_ADMIN_033, 'tab'=>0, 'type'=>'text', 'help'=>'','writeParms'=>array('placeholder'=>"Vstore"),'multilan'=>true),
			'caption_categories'          => array('title'=> LAN_VSTORE_ADMIN_034, 'tab'=>0, 'type'=>'text', 'writeParms'=>array('placeholder'=>LAN_VSTORE_001),'multilan'=>true),
			'caption_outofstock'          => array('title'=> LAN_VSTORE_ADMIN_035, 'tab'=>0, 'type'=>'text', 'writeParms'=>array('placeholder'=>LAN_VSTORE_002),'multilan'=>true),

			'currency'		            => array('title'=> LAN_VSTORE_ADMIN_036, 'type'=>'dropdown', 'data' => 'string','help'=> LAN_VSTORE_ADMIN_037),
			'shipping'		            => array('title'=> LAN_VSTORE_ADMIN_038, 'type'=>'boolean', 'data' => 'int','help'=> LAN_VSTORE_ADMIN_039),
			'howtoorder'	            => array('title'=> LAN_VSTORE_039, 'tab'=>1,'type'=>'bbarea', 'help'=> LAN_VSTORE_ADMIN_040),
			'admin_items_perpage'	    => array('title'=> LAN_VSTORE_ADMIN_041, 'tab'=>2, 'type'=>'number', 'help'=>''),
			'admin_categories_perpage'	=> array('title'=> LAN_VSTORE_ADMIN_042, 'tab'=>2, 'type'=>'number', 'help'=>''),

		);



		// optional
		public function init()
		{
			$this->prefs['currency']['writeParms'] = array('USD'=>'US Dollars', 'EUR'=>'Euros', 'CAN'=>'Canadian Dollars', 'GBP'=>'GB Pounds', 'HUF'=>'Hungarian Forint');
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
			'cat_parent'        =>  array('title'=> LAN_VSTORE_ADMIN_08, 'type'=>'dropdown', 'data'=>'int', 'inline'=>true,  'width'=>'auto'),
		  	'cat_image' 		=>   array ( 'title' => LAN_IMAGE, 'type' => 'image', 'data' => 'str', 'width' => '40%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),	
		 	'cat_info' 			=>   array ( 'title' => LAN_VSTORE_048, 'type' => 'bbarea', 'data' => 'str', 'width' => '40%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'cat_class' 		=>   array ( 'title' => LAN_USERCLASS, 'type' => 'userclass', 'data' => 'str', 'width' => 'auto', 'batch' => true, 'filter' => true, 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  	'cat_order' 		=>   array ( 'title' => LAN_ORDER, 'type' => 'text', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  	'options' 			=>   array ( 'title' => LAN_OPTIONS, 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced' => '1', 'sort'=>1  ),
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
		  'item_preview'       =>   array( 'title' => LAN_PREVIEW, 'type'=>'method', 'data'=>false, 'width'=>'5%', 'forced'=>1),
      'item_id' 			=>   array ( 'title' => LAN_ID, 			'type'=>'text', 'data' => 'int', 	'width' => '5%', 'help' => '', 'readParms'=>'link=sef&target=blank', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'item_code' 			=>   array ( 'title' => LAN_VSTORE_011, 			'type' => 'text', 'inline'=>true,	'data' => 'str', 'width' => '2%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'item_name'			=>   array ( 'title' => LAN_TITLE, 			'type' => 'text', 	'data' => 'str', 'width' => 'auto', 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => array('size'=>'xxlarge'), 'class' => 'left', 'thclass' => 'left',  ),
		  'item_desc' 			=>   array ( 'title' => LAN_VSTORE_ADMIN_023, 		'type' => 'textarea', 	'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => array('size'=>'xxlarge','maxlength'=>250), 'class' => 'center', 'thclass' => 'center',  ),
		  'item_cat' 			=>   array ( 'title' => LAN_CATEGORY, 		'type' => 'dropdown', 'data' => 'int', 'width' => 'auto', 'filter'=>true, 'batch'=>true, 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'item_pic' 			=>   array ( 'title' => LAN_VSTORE_ADMIN_011, 			'type' => 'images', 'data' => 'array', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => 'media=vstore&video=1&max=8', 'class' => 'center', 'thclass' => 'center',  ),
	 	  'item_files' 			=>   array ( 'title' => LAN_FILES, 			'type' => 'files', 'tab'=>3, 'data' => 'array', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => 'media=vstore_file_2', 'class' => 'center', 'thclass' => 'center',  ),
		  'item_price' 			=>   array ( 'title' => LAN_VSTORE_041, 			'type' => 'text', 'data' => 'str', 'width' => 'auto', 'inline'=>true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'right', 'thclass' => 'right',  ),
		  'item_shipping' 		=>   array ( 'title' => LAN_VSTORE_ADMIN_012, 		'type' => 'text', 'data' => 'str', 'width' => 'auto',  'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'item_details' 		=>   array ( 'title' => LAN_VSTORE_048, 			'type' => 'bbarea', 'tab'=>1, 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'item_reviews' 		=>   array ( 'title' => LAN_VSTORE_ADMIN_010, 			'type' => 'textarea', 'tab'=>2, 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => 'size=xxlarge', 'class' => 'center', 'thclass' => 'center',  ),
		  'item_related' 		=>   array ( 'title' => LAN_VSTORE_ADMIN_014, 			'type' => 'method', 'tab'=>2, 'data' => 'array', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => 'video=1', 'class' => 'center', 'thclass' => 'center',  ),	 
		  'item_order' 			=>   array ( 'title' => LAN_ORDER, 			'type' => 'hidden', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'item_inventory' 		=>   array ( 'title' => LAN_VSTORE_ADMIN_015, 		'type' => 'method', 'data' => 'int', 'width' => 'auto', 'inline'=>true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'right item-inventory', 'thclass' => 'right',  ),
		  'item_link' 			=>   array ( 'title' => LAN_VSTORE_ADMIN_016, 	'type' => 'text', 'tab'=>3, 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'item_download' 		=>   array ( 'title' => LAN_VSTORE_ADMIN_017, 	'type' => 'file', 'tab'=>3, 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => 'media=vstore_file', 'class' => 'center', 'thclass' => 'center',  ),
			
		  'options' 			=>   array ( 'title' => LAN_OPTIONS, 			'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'right last', 'class' => 'right last', 'forced' => '1',  ),
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
				return $frm->number('item_inventory',$curVal);
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
				return LAN_VSTORE_ADMIN_024. $frm->text('item_related[caption]',$curVal['caption'])."<br />".LAN_VSTORE_ADMIN_025 .$frm->select('item_related[src]',$options, $curVal['src'],null,true);		
			break;
			
			case 'filter':
			case 'batch':
				return  null;
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
