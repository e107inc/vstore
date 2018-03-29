<?php
/**
 * Adminarea module cart
 */
class vstore_pref_ui extends e_admin_ui
{
			
		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';
		// protected $table			= 'vstore_cart';
		// protected $pid				= 'cart_id';
		// protected $perPage			= 10; 
		// protected $batchDelete		= true;
	//	protected $batchCopy		= true;		
	//	protected $sortField		= 'somefield_order';
	//	protected $orderStep		= 10;
	//	protected $tabs			= array('Tabl 1','Tab 2'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable. 
		
	//	protected $listQry      	= "SELECT * FROM #tableName WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.
	
		protected $listOrder		= 'cart_id DESC';
	
		// protected $fields 		= array ( );		
		
		// protected $fieldpref = array();

		// optional
		protected $preftabs = array(LAN_GENERAL, "Shipping", "Emails", "How to Order", "Admin Area", "Check-Out", "Custom CSS");


		protected $prefs = array(
			'caption'                   => array('title'=> 'Store Caption', 'tab'=>0, 'type'=>'text', 'help'=>'','writeParms'=>array('placeholder'=>'Vstore'),'multilan'=>true),
			'caption_categories'        => array('title'=> 'Category Caption', 'tab'=>0, 'type'=>'text', 'writeParms'=>array('placeholder'=>'Product Brands'),'multilan'=>true),
			'caption_outofstock'        => array('title'=> 'Out-of-Stock Caption', 'tab'=>0, 'type'=>'text', 'writeParms'=>array('placeholder'=>'Out of Stock'),'multilan'=>true),

			'currency'		            => array('title'=> 'Currency', 'tab'=>0, 'type'=>'dropdown', 'data' => 'string','help'=>'Select a currency'),
			'weight_unit'		        => array('title'=> 'Weight unit', 'tab'=>0, 'type'=>'dropdown', 'data' => 'string','help'=>'Select a weight unit'),
			'customer_userclass'        => array('title'=> 'Assign userclass', 'tab'=>0, 'type' => 'method', 'help' => 'Assign userclass to customer on purchase'),
			
			'shipping'		            => array('title'=> 'Calculate Shipping', 'tab'=>1, 'type'=>'boolean', 'data' => 'int','help'=>'Including shipping calculation at checkout.'),
			'shipping_method'	        => array('title'=> 'Calculation method', 'tab'=>1, 'type'=>'dropdown', 'data' => 'string', 'help'=>'Define a method to calculate the shipping cost.', 'writeParms' => array('size' => 'xxlarge')),
			'shipping_unit'	        	=> array('title'=> 'Value based on', 'tab'=>1, 'type'=>'dropdown', 'data' => 'string', 'help'=>'Define which value (subtotal or weight) will be used to calculate shipping costs.', 'writeParms' => array('money'=>'Cart subtotal', 'weight'=>'Cart total weight')),
			'shipping_limit'        	=> array('title'=> 'Cost are', 'tab'=>1, 'type'=>'dropdown', 'data' => 'string', 'help'=>'Define if the shipping cost are fixed to the spcified cost or limited to that value', 'writeParms' => array('fixed'=>'Fixed shipping costs', 'max'=>'Up to (max.) shipping costs')),
			'shipping_data'				=> array('title'=> 'Staggered shipping costs', 'tab'=>1, 'type'=>'method', 'data' => 'json'),

			'sender_name'               => array('title'=> 'Sender Name', 'tab'=>2, 'type'=>'text', 'writeParms'=>array('placeholder'=>'Sales Department'), 'help'=>'Leave blank to use system default','multilan'=>false),
			'sender_email'              => array('title'=> LAN_EMAIL, 'tab'=>2, 'type'=>'text', 'writeParms'=>array('placeholder'=>'orders@mysite.com'), 'help'=>'Leave blank to use system default', 'multilan'=>false),
			'merchant_info'             => array('title'=> "Merchant Name/Address", 'tab'=>2, 'type'=>'textarea', 'writeParms'=>array('placeholder'=>'My Store Inc. etc.'), 'help'=>'Will be displayed on customer email.', 'multilan'=>false),
			'email_templates'           => array('title'=> "Email templates", 'tab'=>2, 'type'=>'method'),
			
			'howtoorder'	            => array('title'=> 'How to order', 'tab'=>3, 'type'=>'bbarea', 'help'=>'Enter how-to-order info.'),

			'admin_items_perpage'	    => array('title'=> 'Products per page', 'tab'=>4, 'type'=>'number', 'help'=>''),
			'admin_categories_perpage'	=> array('title'=> 'Categories per page', 'tab'=>4, 'type'=>'number', 'help'=>''),

			'additional_fields'         => array('title'=>'Additional Fields', 'tab'=>5, 'type'=>'method'),
			
			// Not used anymore, because of the redisigned checkout process (Summary and order confirmation page)
			//'admin_confirm_order'		=> array('title'=> 'Confirm order', 'tab'=>4, 'type'=>'bool', 'help'=>'If ON, the customer has to confirm his order after selecting the payment method on the checkout page!'),

			'custom_css'	            => array('title'=> 'Custom CSS', 'tab'=>6, 'type' => 'textarea', 'data' => 'str', 'width' => '100%', 'readParms' => '', 'writeParms' => array('cols'=> 80, 'rows' => 10, 'size'=>'block-level'), 'help'=>'Use this field to enter any vstore related custom css, without the need to edit any source files.'),
		);



		// optional
		public function init()
		{
			$this->prefs['currency']['writeParms'] = array('USD'=>'US Dollars', 'EUR'=>'Euros', 'CAN'=>'Canadian Dollars');

			$this->prefs['weight_unit']['writeParms'] = array('g' => 'Gram', 'kg'=>'Kilogram', 'lb'=>'Pound', 'oz'=>'Ounce', 'carat' => 'Carat');

			$this->prefs['shipping_method']['writeParms'] = array(
				'sum_simple'	=> 'Sum up shipping cost for all items', 
				'sum_unique'	=> 'Sum up shipping cost only for unique items', 
				'staggered'		=> 'Use settings from staggered shipping costs table',
			);

			$email_fields = array(
				'{ORDER_DATA: order_ref}'		=> 'The order reference number',
				'{ORDER_DATA: cust_firstname}'	=> 'The billing firstname',
				'{ORDER_DATA: cust_lastname}' 	=> 'The billing lastname',
				'{ORDER_DATA: cust_company}'	=> 'The billing company name',
				'{ORDER_DATA: cust_address}'	=> 'The billing street',
				'{ORDER_DATA: cust_city}'		=> 'The billing city',
				'{ORDER_DATA: cust_state}'		=> 'The billing state',
				'{ORDER_DATA: cust_zip}'		=> 'The billing zip code',
				'{ORDER_DATA: cust_country}'	=> 'The billing country',
				'{ORDER_DATA: ship_firstname}'	=> 'The shipping firstname',
				'{ORDER_DATA: ship_lastname}' 	=> 'The shipping lastname',
				'{ORDER_DATA: ship_company}'	=> 'The shipping company name',
				'{ORDER_DATA: ship_address}'	=> 'The shipping street',
				'{ORDER_DATA: ship_city}'		=> 'The shipping city',
				'{ORDER_DATA: ship_state}'		=> 'The shipping state',
				'{ORDER_DATA: ship_zip}'		=> 'The shipping zip code',
				'{ORDER_DATA: ship_country}'	=> 'The shipping country',
				'{ORDER_ITEMS}'					=> 'The ordered items',
				'{ORDER_PAYMENT_INSTRUCTIONS}' 	=> 'In case of payment method "bank transfer", the bank transfer details',
				'{ORDER_MERCHANT_INFO}'			=> 'Merchant name & adress',
				'{SENDER_NAME}'					=> 'Sender name es defined in the vstore prefs'
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


class vstore_pref_form_ui extends e_admin_form_ui
{

	private function varempty($val, $default=0)
	{
		if (!empty($val)) return $val;
		return $default;
	}

	public function init()
	{
		$prefs = e107::pref('vstore');

		$sd = $prefs['shipping_data'];
		if (!is_array($sd))
		{
			$sd = e107::unserialize($sd);
		}

		unset($sd['%ROW%']);

		// Make sure that the array is correctly indexed
		$tmp = array();
		foreach ($sd as $key => $value) {
			$tmp[] = array('cost' => floatval($value['cost']), 'unit' => floatval($value['unit']));
		}
		$sd = e107::serialize($tmp, 'json');

		e107::getConfig('vstore')->update('shipping_data', e107::serialize($tmp, 'json'))->save(false, false, false);

		$max = (int) max(array_keys($tmp));
		$max++;

		$js = "
		var rowcount = $max;
		$(function(){
			$('.vstore-email-reset').click(function(){
				var type = $(this).data('type');
				var template = decodeURIComponent($(this).data('template'));

				var id = 'email-templates-'+type+'-template';
				$('#'+id).val(template);
				$(tinymce.get(id).getBody()).html(template);
			});

			$('.vstore-shipping-add').click(function(){
				rowcount++;
				var row = $('#vstore-shipping-data-template').html();
				row = row.replace(new RegExp('%ROW%', 'g'), rowcount);
				row = row.replace(new RegExp('xxx>', 'g'), 'td>');
				row = '<tr>' + row + '</tr>';
				$('#vstore-shipping-data').append(row);
			});

			$('body').on('click', '.vstore-shipping-remove', function(){
				var rows = $('#vstore-shipping-data tr').length;
				if (rows > 2)
				{
					var row = $(this).parent().parent();
					row.remove();
				}
			});
		});
		";
		e107::js('footer-inline', $js);
	}


	function shipping_data($curVal, $mode)
	{
		$frm = e107::getForm();

		if (!empty($curVal) && !is_array($curVal))
		{
			$curVal = e107::unserialize($curVal);
		}elseif(!is_array($curVal)){
			$curVal = array();
		}

		$text = '
		<div class="row">
		<table class="table table-striped table-bordered" id="vstore-shipping-data">
		<tr>
			<td>Value</td>
			<td>Cost</td>
			<td> </td>
		</tr>
		';

		unset($curVal['%ROW%']);

		$i = 0;
		if (count($curVal) == 0)
		{
			$text .= '
			<tr>
				<td>'.$frm->text('shipping_data['.$i.'][unit]', '0.00', 8, array('pattern' => '^(\d+(\.\d{1,2}){0,1})$', 'min' => '0')).'</td>
				<td>'.$frm->text('shipping_data['.$i.'][cost]', '0.00', 8, array('pattern' => '^(\d+(\.\d{1,2}){0,1})$', 'min' => '0')).'</td>
				<td><button class="vstore-shipping-remove btn btn-danger" type="button"><i class="fa fa-times"></i> Remove</button></td>
			</tr>
			';
			
		}
		else
		{
			foreach ($curVal as $x => $val) {
				$text .= '
				<tr>
					<td>'.$frm->text('shipping_data['.$i.'][unit]', number_format($val['unit'], 2), 8, array('pattern' => '^(\d+(\.\d{1,2}){0,1})$', 'min' => '0')).'</td>
					<td>'.$frm->text('shipping_data['.$i.'][cost]', number_format($val['cost'], 2), 8, array('pattern' => '^(\d+(\.\d{1,2}){0,1})$', 'min' => '0')).'</td>
					<td><button class="vstore-shipping-remove btn btn-danger" type="button"><i class="fa fa-times"></i> Remove</button></td>
				</tr>
				';
				$i++;
			}
		}
		
		$text .= '
		</table>
		</div>

		<button class="vstore-shipping-add btn btn-success" type="button"><i class="fa fa-plus"></i> Add</button>
		';

		$text .= '
		<div id="vstore-shipping-data-template" style="display:none;">
			<xxx>'.$frm->text('shipping_data[%ROW%][unit]', '0.00', 8, array('pattern' => '^(\d+(\.\d{1,2}){0,1})$', 'min' => '0')).'</xxx>
			<xxx>'.$frm->text('shipping_data[%ROW%][cost]', '0.00', 8, array('pattern' => '^(\d+(\.\d{1,2}){0,1})$', 'min' => '0')).'</xxx>
			<xxx><button class="vstore-shipping-remove btn btn-danger" type="button"><i class="fa fa-times"></i> Remove</button></xxx>
		</div>
		';



		return $text;

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

		$text .= "
			<tr>
				<td>".LAN_ACTIVE."</td>
				<td>".LAN_CAPTION."</td>
				<td>Placeholder</span></td>
				<td>Fieldtype</td>
				<td>Required</td>
			</tr>
		";
			
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
					<td><span class='input-group'>".$this->text('additional_fields['.$i.'][caption]['.e_LANGUAGE.']', $capVal, 250, array('placeholder'=>LAN_CAPTION, 'size'=>'block-level')).$post."</span></td>
					<td><span class='input-group'>".$this->text('additional_fields['.$i.'][placeholder]['.e_LANGUAGE.']',$placeholderVal, 100, array('placeholder'=>"Placeholder", 'size'=>'block-level')).$post."</span></td>
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
	//	$text = '';
		$tab = array();
		foreach (vstore::getEmailTypes() as $type => $label) {

			// $orig_template = e107::getTemplate('vstore', 'vstore_email', $type);
			$orig_template = $orig_templates[$type];
			if (empty($curVal[$type]['template']))
			{
				$curVal = array();
				$curVal[$type]['template'] = $orig_template;
			}
			$isActive = isset($curVal[$type]['active']) ? $curVal[$type]['active'] : true;
			$isCC = isset($curVal[$type]['cc']) ? $curVal[$type]['cc'] : true;

		//	$text = '<div><label><b>'.$label.'</b>';
			$text = '
			<div class="row">
				<div class="form-group">
					<label class="control-label col-3 col-xs-3">'.LAN_ACTIVE.'?</label>
					<div class="text-right col-3 col-xs-3">
					'.$this->flipswitch('email_templates['.$type.'][active]', $isActive, null, array('switch'=>'small', 'title' => LAN_ACTIVE)).'
					</div>
					<div class="text-right col-6 col-xs-6">
						'.$this->button('', '<span class="fa fa-undo"></span> '. 'Reset template', 'action', '', array('data-template' => rawurlencode($orig_template), 'data-type' => $type, 'class' => 'vstore-email-reset pull-right btn-sm', 'title' => 'Click & save to reset this template to the default template.')).'
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<label class="control-label col-3 col-xs-3">Receive email in CC?</label>
					<div class="text-right col-3 col-xs-3">
					'.$this->flipswitch('email_templates['.$type.'][cc]', $isCC, null, array('switch'=>'small', 'title' => 'Receive this email in CC?')).'
					</div>
				</div>
			</div>
			<div class="row">
				'.$this->textarea('email_templates['.$type.'][template]', $curVal[$type]['template'], 10, 80, array('class' => 'tbox form-control input-block-level e-autoheight e-wysiwyg')).'
			</div>
			';
			

			$tab[] = array('caption'=>$label,'text' => $text);

		}

		return $this->tabs($tab);
		 		
	}

	function customer_userclass($curVal, $mode)
	{
		$frm = e107::getForm();
	
		$items = e107::getUserClass()->getClassList('nobody,member,classes');
		$items = array('-1' => 'As defined in product') + $items;
		return $frm->select('customer_userclass', $items, $curVal);
		
	}

}		

?>