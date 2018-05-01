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
		protected $tabs				= array(LAN_GENERAL, 'Details', 'Log'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable.

		// protected $listQry      	= "SELECT o.*, SUM(c.cart_qty) as items FROM `#vstore_orders` AS o LEFT JOIN `#vstore_cart` AS  c ON o.order_session = c.cart_session  "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.

		protected $listOrder		= 'order_id DESC';



		protected $fields 		= array (
			'checkboxes'           	=> array ( 'title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => '1', 'class' => 'center', 'toggle' => 'e-multiselect',  ),
			'order_id'            	=> array ( 'title' => LAN_ID, 'data' => 'int', 'width' => '5%', 'help' => '', 'readonly'=>true, 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'order_status'          => array ( 'title' => 'Status', 'type'=>'method', 'data'=>'str', 'inline'=>true, 'filter'=>true, 'batch'=>true,'width'=>'5%'),
			'order_date'          	=> array ( 'title' => LAN_DATESTAMP, 'type' => 'datestamp', 'data' => 'str',  'readonly'=>true, 'width' => 'auto', 'filter' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),

			'order_invoice_nr'     	=> array ( 'title' => 'Invoice Nr', 'type'=>'method', 'data'=>false, 'width'=>'20%'),
			'order_billing'      	=> array ( 'title' => 'Billing to', 'type'=>'method', 'data'=>false, 'width'=>'20%'),
			'order_shipping'      	=> array ( 'title' => 'Ship to', 'type'=>'method', 'data'=>false, 'width'=>'20%'),
			'order_items'     		=> array ( 'title' => 'Items', 'type' => 'method', 'data' => false, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'right', 'thclass' => 'right',  ),
			'order_e107_user'     	=> array ( 'title' => LAN_AUTHOR, 'type' => 'method', 'data' => 'str', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'order_pay_gateway'     => array ( 'title' => 'Gateway', 'type' => 'text', 'data' => 'str', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'order_pay_status'      => array ( 'title' => 'Pay Status', 'type' => 'text',  'data' => 'str',  'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'order_pay_transid'     => array ( 'title' => 'TransID', 'type' => 'text', 'data' => 'str', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'order_pay_amount' 		=> array ( 'title' => 'Total', 'type' => 'method', 'data' => 'float', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'order_pay_shipping' 	=> array ( 'title' => 'Shipping', 'type' => 'number', 'data' => 'float', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),

			'order_ship_notes'      => array ( 'title' => 'Notes', 'type'=>'method', 'tab'=>1, 'data'=>false, 'width'=>'20%'),
			'order_session'       	=> array ( 'title' => 'Session', 'type' => 'text', 'tab'=>1, 'data' => 'str', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'order_pay_rawdata' 	=> array ( 'title' => 'Rawdata', 'type' => 'method', 'tab'=>1, 'data' => 'str', 'readonly'=>true, 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
			'order_log' 			=> array ( 'title' => 'Log', 'type' => 'method', 'tab'=>2, 'data' => 'json', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),

			'options' 				=> array ( 'title' => LAN_OPTIONS, 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced' => '1',  ),
		);

		protected $fieldpref = array('order_id','order_ship_to', 'order_status', 'order_invoice_nr', 'order_date', 'order_items', 'order_pay_transid','order_pay_amount','order_pay_status');


		// protected $preftabs = array();
		// protected $prefs = array( );



		public function init()
		{

			if ($_GET['filter_options'] == 'order_status__open')
			{
				// List all open orders: New, Processing, On Hold
				// Completed, Cancelled, Refunded will NOT be displayed!
				$this->filterQry = 'SELECT * FROM `#vstore_orders` WHERE FIND_IN_SET(order_status, "N,P,H")';
				//$this->setQuery('filter_options');
			}


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
			$tp = e107::getParser();

			if (array_key_exists('order_status', $new_data)) 
			{
				if ($old_data['order_status'] === 'C' && $new_data['order_status'] !== 'C')
				{
					// Check if this order "contains" any userclasses that have been assigned
					$uc = vstore::getCustomerUserclass(json_decode($old_data['order_items'], true));
					if ($uc)
					{
						$uc_list = e107::getDB()->retrieve('SELECT GROUP_CONCAT(userclass_name) AS ucs FROM e107_userclass_classes WHERE FIND_IN_SET(userclass_id, "'.$uc.'")');
						$msg = $tp->lanVars('The userclasses, the customer has been assigned to during the purchase can not be removed automatically.<br/>
							Click <a href="'.e_ADMIN.'users.php?searchquery=[x]">here</a> to remove the following userclasses manually.<br/>[y]', 
							array('x' => $old_data['order_e107_user'], 'y' => str_replace(',', ', ', $uc_list)));
						
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

			// Check for changes and add to the log
			$now = time();
			foreach ($new_data as $key => $value) {
				$oldval = $old_data[$key];
				if ($value !== $oldval && array_key_exists($key, $this->fields))
				{
					$title = $this->fields[$key]['title'];

					if ($key == 'order_status')
					{
						$value = vstore::getStatus($value);
						$oldval = vstore::getStatus($oldval);
					}

					$log[] = array(
						'datestamp' => $now,
						'user_id' => USERID,
						'user_name' => USERNAME,
						'text' => $tp->lanVars('Changed [x] from "[y]" to "[z]".', array('x' => $title, 'y' => $oldval, 'z' => $value))
					);
					
				}
			}

			$new_data['order_log'] = $log;
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

			$vs = e107::getSingleton('vstore');

			if (isset($_POST['force_new_invoice']) && intval($_POST['force_new_invoice']) == 1 && vstore::validInvoiceOrderState($new_data['order_status']))
			{
				// User requests to delete the current invoice pdf and to create a new one.
				$data = $vs->renderInvoice($new_data['order_id'], true);
				if ($data)
				{
					$vs->invoiceToPdf($data, true);
				}

				// Check if the pdf was created
				if (empty($vs->pathToInvoicePdf($new_data['order_invoice_nr'], $new_data['order_e107_user'])))
				{
					e107::getMessage()->addWarning('Invoice couldn\'t be created!');
				}
				else
				{
					e107::getMessage()->addSuccess('Invoice successfully created!');
				}
			}

			// Send our email to customer
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

	function order_invoice_nr($curVal, $mode)
	{
		$status = $this->getController()->getFieldVar('order_status');
		$exists = false;
		if (vstore::validInvoiceOrderState($status))
		{
			$text = '<a href="' . e107::url('vstore', 'invoice', array('order_invoice_nr' => $curVal)) . '" target="_BLANK">' . vstore::formatInvoiceNr($curVal) . '</a>';
			$exists = true;
		}
		else
		{
			$text = vstore::formatInvoiceNr($curVal);
		}

		switch($mode)
		{
			case 'read': // List Page
				return $text;
				break;

			case 'write': // Edit Page
				if ($exists)
				{
					return $text . ' &nbsp;&nbsp;&nbsp;&nbsp;' . e107::getForm()->checkbox_label('Check to force the creation of a new invoice pdf during save', 'force_new_invoice', 1);
				}
				else
				{
					return $text;
				}
				break;

			case 'filter':
				return null;
				break;

			case 'batch':
				return  null;
				break;
		}		
	}

	function order_status($curVal, $mode)
	{

		switch($mode)
		{
			case 'read': // List Page
				return vstore::getStatus($curVal);
				break;

			case 'write': // Edit Page
				return e107::getForm()->select('order_status', vstore::getStatus(), $curVal);
				break;

			case 'inline': // Inline Edit Page
				return array(
					'inlineType' => 'select',
					'inlineData' => vstore::getStatus()
				);
				break;

			case 'filter':
				$filter = vstore::getStatus();
				$filter['open'] = 'Open';
				return $filter;
				break;

			case 'batch':
				return  array();
				break;
		}		
	}

	// Custom Method/Function
	function order_e107_user($curVal,$mode)
	{

		$text = $curVal.') '.e107::getDb()->retrieve('user', 'user_name', 'user_id="'.$curVal.'"');
		return $text;

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


	function order_billing($curVal,$mode)
	{

		switch($mode)
		{

			case 'read': // List Page
			case 'write': // Edit Page
				$val = e107::unserialize($curVal);

				if (count($val) == 0) return 'No billing address set';

				return varset($val['firstname']) . ' ' . varset($val['lastname']).'<br />'
					.varset($val['company']).'<br />'
					.varset($val['address']).'<br />'
					.varset($val['city']) . ', ' . varset($val['state']) . ' ' . varset($val['zip']).'<br />'
					.(empty($val['country']) ? '' : $this->getCountry($val['country']) . '<br />')
					.varset($val['phone']);

				break;
			}
		}

	function order_shipping($curVal,$mode)
	{

		switch($mode)
		{

			case 'read': // List Page
			case 'write': // Edit Page
				$val = e107::unserialize($curVal);

				if (count($val) == 0) return 'No shipping address set';
		
				return varset($val['firstname']) . ' ' . varset($val['lastname']).'<br />'
					.varset($val['company']).'<br />'
					.varset($val['address']).'<br />'
					.varset($val['city']) . ', ' . varset($val['state']) . ' ' . varset($val['zip']).'<br />'
					.(empty($val['country']) ? '' : $this->getCountry($val['country']) . '<br />')
					.varset($val['phone']);

			break;
		}
	}

	function order_ship_notes($curVal, $mode)
	{
		switch($mode)
		{
			case 'read':
			case 'write':
				$notes = e107::unserialize($this->getController()->getFieldVar('order_shipping'));
				$notes = nl2br($notes['notes']);
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


	function order_log($curVal, $mode)
	{
		$items = e107::unserialize($curVal);

		$text = '<table class="table table-bordered table-striped">
			<tr>
				<th>Date/Time</th>
				<th>User</td>
				<td>Description</td>
			</tr>
			';
		foreach ($items as $item) {
			$text .= sprintf('
			<tr>
				<td>%s</td>
				<td>%s (%d)</td>
				<td>%s</td>
			</tr>', 
				e107::getDateConvert()->convert_date($item['datestamp']),
				$item['user_name'],
				$item['user_id'],
				e107::getParser()->toHTML($item['text']));
		}
		$text .= '</table>';
		return $text;
	}

}
?>