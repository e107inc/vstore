<?php
/**
 * Adminarea module customers
 */
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
		protected $tabs			= array('Billing','Shipping', 'Additional fields', 'Notes'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable. 
		
	//	protected $listQry      	= "SELECT * FROM #tableName WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.
	
		protected $listOrder		= 'cust_id DESC';
	
		protected $fields 		= array (  'checkboxes' =>   array ( 'title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => '1', 'class' => 'center', 'toggle' => 'e-multiselect',  ),
		  'cust_id' =>   array ( 'title' => LAN_ID, 'tab' => 0, 'data' => 'int', 'width' => '5%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'cust_refcode' =>   array ( 'title' => 'Refcode', 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_e107_user' =>   array ( 'title' => 'User', 'tab' => 0, 'type' => 'method', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center', 'readonly'=>!true ),
		  'cust_datestamp' =>   array ( 'title' => 'Last update', 'tab' => 0, 'type' => 'datestamp', 'data' => 'int', 'width' => 'auto', 'filter' => true, 'readonly' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'cust_title' =>   array ( 'title' => LAN_TITLE, 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  'cust_firstname' =>   array ( 'title' => 'Firstname', 'tab' => 0, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_lastname' =>   array ( 'title' => 'Lastname', 'tab' => 0, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_company' =>   array ( 'title' => 'Company', 'tab' => 0, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_vat_id' =>   array ( 'title' => 'VAT ID', 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_taxcode' =>   array ( 'title' => 'Tax code', 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_address' =>   array ( 'title' => 'Address', 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_city' =>   array ( 'title' => 'City', 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_state' =>   array ( 'title' => 'State', 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_zip' =>   array ( 'title' => 'Postcode/Zip', 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_country' =>   array ( 'title' => 'Country', 'tab' => 0, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_email' =>   array ( 'title' => 'Email', 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_phone' =>   array ( 'title' => 'Phone', 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_fax' =>   array ( 'title' => 'Fax', 'tab' => 0, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'cust_use_shipping' =>   array ( 'title' => 'Use shipping address', 'tab' => 1, 'type' => 'boolean', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center'),
		  'cust_shipping' =>   array ( 'title' => 'Last shipping address', 'tab' => 1, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center', 'readonly'=>true),
		  'cust_additional_fields' =>   array ( 'title' => 'Additional fields', 'tab' => 2, 'type' => 'method', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center', 'readonly'=>true),
		  'cust_notes' =>   array ( 'title' => 'Internal notes', 'tab' => 3, 'type' => 'bbarea', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'center', 'thclass' => 'center',  ),
		  'options' =>   array ( 'title' => 'Options', 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced' => '1',  ),
		);		
		
		protected $fieldpref = array('cust_refcode', 'cust_e107_user', 'cust_firstname', 'cust_lastname');
		

	
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
	function cust_e107_user($curVal, $mode)
	{
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				$u = e107::getDb()->retrieve('user', 'user_name, user_loginname', 'user_id='.intval($curVal));
				$text = $curVal . ') <span title="Login name">' . $u['user_loginname'] . '</span> <span title="Username">('. $u['user_name'] . ')</span>';
				return $text;
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
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $this->text('cust_firstname',$curVal);
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
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $this->text('cust_lastname',$curVal);
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
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
				return $this->text('cust_company',$curVal);
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
		switch($mode)
		{
			case 'read': // List Page

				return $this->getCountry($curVal);
			break;
			
			case 'write': // Edit Page
				return $this->country('cust_country',$curVal);

			break;
			
			case 'filter':
			case 'batch':
				return  null;
			break;
		}

		return null;
	}

	function cust_shipping($curVal, $mode)
	{

		$val = e107::unserialize($curVal);

		if (count($val) == 0) return 'No shipping address set';

		return varset($val['firstname']) . ' ' . varset($val['lastname']).'<br />'
		.varset($val['company']).'<br />'
		.varset($val['address']).'<br />'
		.varset($val['city']) . ', ' . varset($val['state']) . ' ' . varset($val['zip']).'<br />'
		.(empty($val['country']) ? '' : $this->getCountry($val['country']) . '<br />')
		.varset($val['phone']).'<br />'
		.'Notes:<br />'
		.varset($val['notes']);

	}

	function cust_additional_fields($curVal,$mode)
	{
		
		$val = e107::unserialize($curVal);

		if (count($val) == 0) return 'No additional field info set';

		$text = '';
		foreach ($val as $k => $v) {
			$text .= sprintf('%s (%s): %s<br/>', $v['caption'], $k, $v['value']) ;
		}

		return $text;

	}
	
}		
?>