<?php

// Generated e107 Plugin Admin Area 

require_once('../../class2.php');
if (!getperms('P')) 
{
	header('location:'.e_BASE.'index.php');
	exit;
}



class vstore_admin extends e_admin_dispatcher
{

	protected $modes = array(	
	
		'main'	=> array(
			'controller' 	=> 'vstore_customer_ui',
			'path' 			=> null,
			'ui' 			=> 'vstore_customer_form_ui',
			'uipath' 		=> null
		),
		

		'cart'	=> array(
			'controller' 	=> 'vstore_cart_ui',
			'path' 			=> null,
			'ui' 			=> 'vstore_cart_form_ui',
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

	'products/list'			=> array('caption'=> "Products", 'perm' => 'P'),
		'products/create'		=> array('caption'=> "Add Product", 'perm' => 'P'),
	//	'cart/create'		=> array('caption'=> LAN_CREATE, 'perm' => 'P'),

		'cat/list'			=> array('caption'=> LAN_CATEGORIES, 'perm' => 'P'),
		'cat/create'		=> array('caption'=> LAN_CREATE_CATEGORY, 'perm' => 'P'),

	

		'cart/list'			=> array('caption'=> "Sales", 'perm' => 'P'),
		
		'main/list'			=> array('caption'=> "Customers", 'perm' => 'P'),
			
	
		'main/prefs' 		=> array('caption'=> LAN_PREFS, 'perm' => 'P'),

		'cart/prefs'		=> array('caption'=> "Payment Gateways", 'perm' => 'P'),

		// 'main/custom'		=> array('caption'=> 'Custom Page', 'perm' => 'P')
	);

	protected $adminMenuAliases = array(
		'products/edit'	=> 'products/list'
	);	
	
	protected $menuTitle = 'Vstore';
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
		
	//	protected $preftabs = array('Basic', 'Paypal');
		
	
		protected $prefs = array(	
			'currency'		=> array('title'=> 'Currency', 'type'=>'dropdown', 'data' => 'string','help'=>'Select a currency'),
			'shipping'		=> array('title'=> 'Calculate Shipping', 'type'=>'boolean', 'data' => 'int','help'=>'Including shipping calculation at checkout.'),
			'howtoorder'	=> array('title'=>'How to order', 'type'=>'bbarea', 'help'=>'Enter how-to-order info.'),

		); 

	
	
		// optional
		public function init()
		{
			$this->prefs['currency']['writeParms'] = array('USD'=>'US Dollars', 'EUR'=>'Euros', 'CAN'=>'Canadian Dollars');	
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


		protected $preftabs = array('Paypal', 'Amazon', 'Skrill');


		protected $prefs = array(
			'paypal_active'         => array('title'=>"Paypal Payments", 'type'=>'boolean', 'tab'=>0, 'data'=>'int', 'help'=>''),
			'paypal_username'       => array('title'=>"Paypal Username", 'type'=>'text', 'tab'=>0, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
			'paypal_password'       => array('title'=>"Paypal Password", 'type'=>'text', 'tab'=>0, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
			'paypal_signature'      => array('title'=>"Paypal Signature", 'type'=>'text', 'tab'=>0, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),

			'amazon_active'         => array('title'=>"Amazon Payments", 'type'=>'boolean', 'tab'=>1, 'data'=>'int', 'help'=>''),
			'amazon_merchant_id'    => array('title'=>"Amazon Merchant ID", 'type'=>'text', 'tab'=>1, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
			'amazon_secret_key'     => array('title'=>"Amazon Secret Key", 'type'=>'text', 'tab'=>1, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
			'amazon_region'         => array('title'=>"Amazon Region", 'type'=>'dropdown', 'tab'=>1, 'data'=>'str', 'writeParms'=>array('optArray'=>array('us'=>'USA','de'=>"Germany",'uk'=>"United Kingdom",'jp'=>"Japan")), 'help'=>''),

			'skrill_active'         => array('title'=>"Skrill Payments", 'type'=>'boolean', 'tab'=>2, 'data'=>'int', 'help'=>''),
			'skrill_email'          => array('title'=>"Skrill Email", 'type'=>'text', 'tab'=>2, 'data'=>'str', 'help'=>'', 'writeParms'=>array('size'=>'xxlarge')),
		);

/*
 * merchant_id 	Default : null
Access Key 	access_key 	Default : null
Secret Key 	secret_key 	Default : null
Region 	region
 */
		// optional
		public function init()
		{
			
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
		protected $orderStep		= 10;
	//	protected $tabs			= array('Tabl 1','Tab 2'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable. 
		
	//	protected $listQry      	= "SELECT * FROM #tableName WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.
	
	  protected $listOrder = 'cat_parent, cat_order ASC';
	
		protected $fields 		= array (  
			'checkboxes' 		=>   array ( 'title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => '1', 'class' => 'center', 'toggle' => 'e-multiselect',  ),
			'cat_id' 			=>   array ( 'title' => LAN_ID, 'data' => 'int', 'width' => '5%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'cat_name' 			=>   array ( 'title' => LAN_TITLE, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => array('size'=>'xxlarge'), 'class' => 'left', 'thclass' => 'left',  ),
			'cat_description' 	=>   array ( 'title' => LAN_DESCRIPTION, 'type' => 'textarea', 'data' => 'str', 'width' => '40%', 'help' => '', 'readParms' => '', 'writeParms' => array('maxlength' => 220, 'size'=>'xxlarge'), 'class' => 'left', 'thclass' => 'left',  ),
			'cat_sef' 			=>   array ( 'title' => LAN_SEFURL, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => array('size'=>'xxlarge','sef'=>'cat_name'), 'class' => 'left', 'thclass' => 'left',  ),
			'cat_parent'        =>  array('title'=>"Parent", 'type' => 'method', 'data'=>'int', 'width' => 'auto', 'batch'=>true, 'filter'=>true, 'thclass' => 'left first'),
			'cat_image' 		=>   array ( 'title' => LAN_IMAGE, 'type' => 'image', 'data' => 'str', 'width' => '40%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),	
			'cat_info' 			=>   array ( 'title' => "Details", 'type' => 'bbarea', 'data' => 'str', 'width' => '40%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'cat_class' 		=>   array ( 'title' => LAN_USERCLASS, 'type' => 'userclass', 'data' => 'str', 'width' => 'auto', 'batch' => true, 'filter' => true, 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'cat_order' 		=>   array ( 'title' => LAN_ORDER, 'type' => 'number', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'options' 			=>   array ( 'title' => 'Options', 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced' => '1',  'readParms'=>'sort=1'),
		);		
		
		protected $fieldpref = array('cat_name', 'cat_sef', 'cat_class', 'cat_parent', 'cat_order');
	
	
	
		public function beforeCreate($new_data,$old_data)
		{
			if(!empty($new_data['cat_name']) && isset($new_data['cat_sef']) && empty($new_data['cat_sef']))
			{
				$new_data['cat_sef'] = eHelper::title2sef($new_data['cat_name'], 'dashl');
			}
			else 
			{
				$new_data['cat_sef'] = eHelper::secureSef($new_data['cat_sef']);
			}			
			$sef = e107::getParser()->toDB($new_data['cat_sef']);

			if(e107::getDb()->count('vstore_cat', '(*)', "cat_sef='{$sef}'"))
			{
				e107::getMessage()->addError('Your SEF URL already exists');
				return false;
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
			
			$sef = e107::getParser()->toDB($new_data['cat_sef']);
			if(e107::getDb()->count('vstore_cat', '(*)', "cat_sef='{$sef}' AND cat_id!=".intval($id)))
			{
				e107::getMessage()->addError('Your SEF URL already exists');
				return false;
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

		// optional
		public function init()
		{
		/*	$data = e107::getDb()->retrieve('vstore_cat','cat_id,cat_name', "cat_parent = 0", true);

			$this->fields['cat_parent']['writeParms']['optArray'] = array(0=>'(Root)');

			foreach($data as $v)
			{
				$key = $v['cat_id'];
				$this->fields['cat_parent']['writeParms']['optArray'][$key] = $v['cat_name'];
			}
				 */
		}
	
	public function handleListLinkParentBatch($selected, $value)
	{
		$field = 'cat_parent';
		$ui = $this->getUI();
		$found = false;
		foreach ($selected as $k => $id)
		{
			// var_dump($ui->_has_parent($value, $id, $this->getLinkArray()));
			if($ui->_has_parent($value, $id, $this->getLinkArray()))
			{
				unset($selected[$k]);
				$found = true;
			}
		}
		if($found) e107::getMessage()->addWarning(LCLAN_108);
		if(!$selected) return;
		
		if(parent::handleListBatch($selected, $field, $value))
		{
			$this->_link_array = null; // reset batch/filters
			return true;
		}
		return false;
	}

	public function ListObserver()
	{
		$searchFilter = $this->_parseFilterRequest($this->getRequest()->getQuery('filter_options', ''));

		if($searchFilter && in_array('cat_parent', $searchFilter))
		{
			$this->getTreeModel()->current_id = intval($searchFilter[1]);
			$this->current_parent = intval($searchFilter[1]);
		}
		parent::ListObserver();

	}
	public function ListAjaxObserver()
	{
		$searchFilter = $this->_parseFilterRequest($this->getRequest()->getQuery('filter_options', ''));

		if($searchFilter && in_array('cat_parent', $searchFilter))
		{
			$this->getTreeModel()->current_id = intval($searchFilter[1]);
			$this->current_parent = intval($searchFilter[1]);
		}
		parent::ListAjaxObserver();
	}
	
	/**
	 * Product tree model
	 * @return vstore_cat_model_admin_tree
	 */
	public function _setTreeModel()
	{
		$this->_tree_model = new vstore_cat_model_admin_tree();
		return $this;
	}
	
	/**
	 * Link ordered array
	 * @return array
	 */
	public function getLinkArray($current_id = 0)
	{
		if(null === $this->_link_array)
		{
			if($this->getAction() != 'list')
			{
				$this->getTreeModel()->setParam('order', 'ORDER BY '.$this->listOrder)->load();
			}
			$tree = $this->getTreeModel()->getTree();
			$this->_link_array = array();
			foreach ($tree as $id => $model)
			{
				if($current_id == $id) continue;
				$this->_link_array[$model->get('cat_parent')][$id] = $model->get('cat_name');
			}
			asort($this->_link_array);
		}

		return $this->_link_array;
	}
			
}
				
class vstore_cat_model_admin_tree extends e_admin_tree_model
{
	public $modify = false;
	public $current_id = 0;

	protected $_db_table = 'vstore_cat';
	protected $_link_array	= null;
	protected $_link_array_modified	= null;

	protected $_field_id = 'cat_id';


	/**
	 * Get array of models
	 * Custom tree order
	 * @return array
	 */
	function getTree($force = false)
	{
		return $this->getOrderedTree($this->modify);
	}

	/**
	 * Get ordered by their parents models
	 * @return array
	 */
	function getOrderedTree($modified = false)
	{
		$var = !$modified ? '_link_array' : '_link_array_modified';
		if(null === $this->$var)
		{
			$tree = $this->get('__tree', array());

			$this->$var = array();
			$search = array();
			foreach ($tree as $id => $model)
			{
				$search[$model->get('cat_parent')][$id] = $model;
			}
			asort($search);
			$this->_tree_order($this->current_id, $search, $this->$var, 0, $modified);
		}
		//$this->buildTreeIndex();
		return $this->$var;
	}

	/**
	 * Reorder current tree
	 * @param $parent_id
	 * @param $search
	 * @param $src
	 * @param $level
	 * @return void
	 */
	function _tree_order($parent_id, $search, &$src, $level = 0, $modified = false)
	{
		if(!isset($search[$parent_id]))
		{
			return;
		}

		$level_image = $level ? '<img src="'.e_IMAGE_ABS.'generic/branchbottom.gif" class="icon" alt="" style="margin-left: '.($level * 20).'px" />&nbsp;' : '';
		foreach ($search[$parent_id] as $model)
		{
			$id = $model->get('cat_id');
			$src[$id] = $model;
			if($modified)
			{
				$model->set('cat_name', $this->bcClean($model->get('cat_name')))
					->set('cat_indent', $level_image);
			}
			$this->_tree_order($id, $search, $src, $level + 1, $modified);
		}
	}
	
	
	function bcClean($link_name)
	{
		if(substr($link_name, 0,8) == 'submenu.') // BC Fix. 
		{
			list($tmp,$tmp2,$link) = explode('.', $link_name, 3);	
		}
		else
		{
			$link = $link_name;	
		}
		
		return $link;		
	}
	
}

class vstore_cat_form_ui extends e_admin_form_ui
{

	protected $current_parent = null;
	
	private $linkFunctions;

 
	
	function cat_parent($value, $mode)
	{
		switch($mode)
		{
			case 'read':
				$current = $this->getController()->current_parent;
				if($current) // show only one parent
				{
					if(null === $this->current_parent)
					{
						if(e107::getDb()->db_Select('vstore_cat', 'cat_name', 'cat_id='.$current))
						{
							$tmp = e107::getDb()->db_Fetch();
							$this->current_parent = $tmp['cat_name'];
						}
					}
				}
				$cats	= $this->getController()->getLinkArray();
				$ret	= array();
				$this->_parents($value, $cats, $ret);
				if($this->current_parent) array_unshift($ret, $this->current_parent);
				return ($ret ? implode('&nbsp;&raquo;&nbsp;', $ret) : '-');
			break;

			case 'write':
				$catid	= $this->getController()->getId();
				$cats	= $this->getController()->getLinkArray($catid);
				$ret	= array();
				$this->_parent_select_array(0, $cats, $ret);
				return $this->selectbox('cat_parent', $ret, $value, array('default' => LAN_SELECT));
			break;

			case 'batch':
			case 'filter':
				$cats	= $this->getController()->getLinkArray();

				$ret[0]	= $mode == 'batch' ? 'REMOVE PARENT' : 'Main Only';
				$this->_parent_select_array(0, $cats, $ret);
				return $ret;
			break;
		}
	}
 
	/**
	 *
	 * @param integer $category_id
	 * @param array $search
	 * @param array $src
	 * @param boolean $titles
	 * @return array
	 */
	function _parents($link_id, $search, &$src, $titles = true)
	{
		foreach ($search as $parent => $cat)
		{
			if($cat && array_key_exists($link_id, $cat))
			{
				array_unshift($src, ($titles ? $cat[$link_id] : $link_id));
				if($parent > 0)
				{
					$this->_parents($parent, $search, $src, $titles);
				}
			}
		}
	}

 

	function _parent_select_array($parent_id, $search, &$src, $strpad = '&nbsp;&nbsp;&nbsp;', $level = 0)
	{
		if(!isset($search[$parent_id]))
		{
			return;
		}

		foreach ($search[$parent_id] as $id => $title)
		{
			$src[$id] = str_repeat($strpad, $level).($level != 0 ? '-&nbsp;' : '').$title;
			$this->_parent_select_array($id, $search, $src, $strpad, $level + 1);
		}
	}

	function _has_parent($link_id, $parent_id, $cats)
	{
		$path = array();
		$this->_parents($link_id, $cats, $path, false);
		return in_array($parent_id, $path);
	}
	
	/**
	 * New core feature - triggered before values are rendered
	 */
	function renderValueTrigger(&$field, &$value, &$params, $id)
	{
		if($field !== 'cat_name') return;
		$tree = $this->getController()->getTreeModel();
		// notify we need modified tree
		$tree->modify = true;
		
		//retrieve array of data models
		$data = $tree->getTree();
		// retrieve the propper model by id
		$model = varset($data[$id]);
		
		if(!$model) return;
		
		// Add indent as 'pre' parameter
		$params['pre'] = $model->get('cat_indent');
	}

	/**
	 * Override Create list view
	 *
	 * @return string
	 */
	public function getList($ajax = false)
	{
		$tp = e107::getParser();
		$controller = $this->getController();

		$request = $controller->getRequest();
		$id = $this->getElementId();
		$tree = $options = array();
		$tree[$id] = clone $controller->getTreeModel();
		$tree[$id]->modify = true;
		
		// if going through confirm screen - no JS confirm
		$controller->setFieldAttr('options', 'noConfirm', $controller->deleteConfirmScreen);

		$options[$id] = array(
			'id' => $this->getElementId(), // unique string used for building element ids, REQUIRED
			'pid' => $controller->getPrimaryName(), // primary field name, REQUIRED
			//'url' => e_SELF, default
			//'query' => $request->buildQueryString(array(), true, 'ajax_used'), - ajax_used is now removed from QUERY_STRING - class2
			'head_query' => $request->buildQueryString('field=[FIELD]&asc=[ASC]&from=[FROM]', false), // without field, asc and from vars, REQUIRED
			'np_query' => $request->buildQueryString(array(), false, 'from'), // without from var, REQUIRED for next/prev functionality
			'legend' => $controller->getPluginTitle(), // hidden by default
			'form_pre' => !$ajax ? $this->renderFilter($tp->post_toForm(array($controller->getQuery('searchquery'), $controller->getQuery('filter_options'))), $controller->getMode().'/'.$controller->getAction()) : '', // needs to be visible when a search returns nothing
			'form_post' => '', // markup to be added after closing form element
			'fields' => $controller->getFields(), // see e_admin_ui::$fields
			'fieldpref' => $controller->getFieldPref(), // see e_admin_ui::$fieldpref
			'table_pre' => '', // markup to be added before opening table element
			'table_post' => !$tree[$id]->isEmpty() ? $this->renderBatch($controller->getBatchDelete(),$controller->getBatchCopy()) : '',
			'fieldset_pre' => '', // markup to be added before opening fieldset element
			'fieldset_post' => '', // markup to be added after closing fieldset element
			'perPage' => $controller->getPerPage(), // if 0 - no next/prev navigation
			'from' => $controller->getQuery('from', 0), // current page, default 0
			'field' => $controller->getQuery('field'), //current order field name, default - primary field
			'asc' => $controller->getQuery('asc', 'desc'), //current 'order by' rule, default 'asc'
		);
		//$tree[$id]->modify = false;
		return $this->renderListForm($options, $tree, $ajax);
	}
	
	
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
		protected $tabs			= array('Basic','Details', 'Reviews', 'Files'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable. 
		
	//	protected $listQry      	= "SELECT * FROM #tableName WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.
	
		protected $listOrder		= 'item_id DESC';
	
		protected $fields 		= array (  
		  'checkboxes' 			=>   array ( 'title' => '', 'type' => null, 'data' => null, 	'width' => '5%', 'thclass' => 'center', 'forced' => '1', 'class' => 'center', 'toggle' => 'e-multiselect',  ),
		  'item_preview'       =>   array( 'title' => LAN_PREVIEW, 'type'=>'method', 'data'=>false, 'width'=>'5%', 'forced'=>1),
		   'item_id' 			=>   array ( 'title' => LAN_ID, 			'data' => 'int', 	'width' => '5%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'item_code' 			=>   array ( 'title' => 'Code', 			'type' => 'text', 'inline'=>true,	'data' => 'str', 'width' => '2%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'item_name'			=>   array ( 'title' => LAN_TITLE, 			'type' => 'text', 	'data' => 'str', 'width' => 'auto', 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => array('size'=>'xxlarge'), 'class' => 'left', 'thclass' => 'left',  ),
		  'item_desc' 			=>   array ( 'title' => 'Description', 		'type' => 'textarea', 	'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => array('size'=>'xxlarge','maxlength'=>250), 'class' => 'center', 'thclass' => 'center',  ),
		  'item_cat' 			=>   array ( 'title' => 'Category', 		'type' => 'dropdown', 'data' => 'int', 'width' => 'auto', 'filter'=>true, 'batch'=>true, 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'item_pic' 			=>   array ( 'title' => 'Images/Videos', 			'type' => 'images', 'data' => 'array', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => 'video=1', 'class' => 'center', 'thclass' => 'center',  ),
	 	  'item_files' 			=>   array ( 'title' => 'Files', 			'type' => 'files', 'tab'=>3, 'data' => 'array', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => 'video=1', 'class' => 'center', 'thclass' => 'center',  ),
		  'item_price' 			=>   array ( 'title' => 'Price', 			'type' => 'text', 'data' => 'str', 'width' => 'auto', 'inline'=>true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'right', 'thclass' => 'right',  ),
		  'item_shipping' 		=>   array ( 'title' => 'Shipping', 		'type' => 'text', 'data' => 'str', 'width' => 'auto',  'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'item_details' 		=>   array ( 'title' => 'Details', 			'type' => 'bbarea', 'tab'=>1, 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'item_reviews' 		=>   array ( 'title' => 'Reviews', 			'type' => 'textarea', 'tab'=>2, 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'item_related' 		=>   array ( 'title' => 'Related', 			'type' => 'method', 'tab'=>2, 'data' => 'array', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => 'video=1', 'class' => 'center', 'thclass' => 'center',  ),
	 
		  'item_order' 			=>   array ( 'title' => LAN_ORDER, 			'type' => 'hidden', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'item_inventory' 		=>   array ( 'title' => 'Inventory', 		'type' => 'number', 'data' => 'int', 'width' => 'auto', 'inline'=>true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'right', 'thclass' => 'right',  ),
		  'item_link' 			=>   array ( 'title' => 'External Link', 	'type' => 'text', 'tab'=>3, 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'item_download' 		=>   array ( 'title' => 'Download File', 	'type' => 'number', 'tab'=>3, 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
			
		  'options' 			=>   array ( 'title' => 'Options', 			'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'right last', 'class' => 'right last', 'forced' => '1',  ),
		);		
		
		protected $fieldpref = array('item_code', 'item_name', 'item_sef', 'item_cat', 'item_price', 'item_inventory');
				
		protected $categories = array();
	
		// optional
		public function init()
		{
			if($this->getAction() != 'list')
			{
				$this->fields['item_preview']['type'] = null;
			}
		//	print_a($_POST);
			
			$data = e107::getDb()->retrieve('SELECT cat_id,cat_name FROM #vstore_cat', true);
			
			foreach($data as $k=>$v)
			{
				$id = $v['cat_id'];
				$this->categories[$id] = $v['cat_name'];	
			}
			
			$this->fields['item_cat']['writeParms'] = $this->categories;
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

	function item_preview($curVal, $mode)
	{
		$tp = e107::getParser();

		if($mode == 'read')
		{
			$img = $this->getController()->getListModel()->get('item_pic');

			if($media = e107::unserialize($img))
			{
				foreach($media as $v)
				{
					if(!$tp->isVideo($v['path']))
					{
						return $tp->toImage($v['path'],array('w'=>80,'h'=>80));
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
}		
		
		
new vstore_admin();

require_once(e_ADMIN."auth.php");
e107::getAdminUI()->runPage();

require_once(e_ADMIN."footer.php");
exit;

?>