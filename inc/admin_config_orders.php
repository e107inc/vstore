<?php
/**
 * Adminarea module orders
 */
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
?>