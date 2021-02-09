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

		public static $taxClassesDefault = array(
			0 => array('name'=>'none', 		'description'=>'No tax', 		'value'=>'0.00'),
			1 => array('name'=>'reduced', 	'description'=>'Reduced tax', 	'value'=>'0.00'),
			2 => array('name'=>'standard', 	'description'=>'Standard tax', 	'value'=>'0.00'),
		);
	
		// optional
		protected $preftabs = array(
			'general'  => LAN_GENERAL,
			'ship'     => "Shipping",
			'tax'      => "Tax",
			'email'    => "Emails",
			'howto'    => "How to Order",
			'admin'    => "Admin Area",
			'checkout' => "Check-Out",
			'menu'     => "Menu",
			'css'      => "Custom CSS",
		);


		protected $prefs = array(
			'caption'                   => array('title'=> 'Store Caption', 'tab'=>'general', 'type'=>'text', 'help'=>'','writeParms'=>array('placeholder'=>'Vstore'),'multilan'=>true),
			'caption_categories'        => array('title'=> 'Category Caption', 'tab'=>'general', 'type'=>'text', 'writeParms'=>array('placeholder'=>'Product Brands'),'multilan'=>true),
			'caption_outofstock'        => array('title'=> 'Out-of-Stock Caption', 'tab'=>'general', 'type'=>'text', 'writeParms'=>array('placeholder'=>'Out of Stock'),'multilan'=>true),

			'currency'		            => array('title'=> 'Currency', 'tab'=>'general', 'type'=>'dropdown', 'data' => 'string','help'=>'Select a currency.'),
			'amount_format'	            => array('title'=> 'Price display', 'tab'=>'general', 'type'=>'dropdown', 'data' => 'string','help'=>'Select a format to be used for displaying item prices.'),
			'weight_unit'		        => array('title'=> 'Weight unit', 'tab'=>'general', 'type'=>'dropdown', 'data' => 'string','help'=>'Select a unit of weight.'),
			'customer_userclass'        => array('title'=> 'Assign userclass', 'tab'=>'general', 'type' => 'method', 'help' => 'Assign userclass to the customer after completing their purchase.'),
			'show_outofstock'     		=> array('title'=> 'Show/hide out-of-stock products', 'tab'=>'general', 'type' => 'bool', 'help' => 'Show or hide "Out-of-stock" products in product listings', 'writeParms' => array('enabled' => LAN_SHOW, 'disabled' => 'Hide')),
			
			'shipping'		            => array('title'=> 'Calculate Shipping', 'tab'=>'ship', 'type'=>'bool', 'data' => 'int','help'=>'Including shipping calculation at checkout.', 'writeParms' => array('label' => 'yesno')),
			'shipping_method'	        => array('title'=> 'Calculation method', 'tab'=>'ship', 'type'=>'dropdown', 'data' => 'string', 'help'=>'Define a method to calculate the shipping cost.', 'writeParms' => array('size'=>'large')),
			'shipping_unit'	        	=> array('title'=> 'Value based on', 'tab'=>'ship', 'type'=>false, 'data' => 'string'),
			'shipping_limit'        	=> array('title'=> 'Cost are', 'tab'=>'ship', 'type'=>false, 'data' => 'string'),
			'shipping_data'				=> array('title'=> 'Tiered shipping costs', 'tab'=>'ship', 'type'=>'method', 'data' => 'array', 'help'=>'Enter thresholds in the first column to set or limit shipping cost based on total order price or weight. Start with the lowest threshold and add more until your last threshold is higher than the maximum price/weight of a typical order. Setting the last threshold too low could result in no shipping cost at all.' ),

			'sender_name'               => array('title'=> 'Sender Name', 'tab'=>'email', 'type'=>'text', 'writeParms'=>array('placeholder'=>'Sales Department'), 'help'=>'Leave blank to use system default','multilan'=>false),
			'sender_email'              => array('title'=> LAN_EMAIL, 'tab'=>'email', 'type'=>'text', 'writeParms'=>array('placeholder'=>'orders@mysite.com'), 'help'=>'Leave blank to use system default', 'multilan'=>false),
			'merchant_info'             => array('title'=> "Merchant Name/Address", 'tab'=>'email', 'type'=>'textarea', 'writeParms'=>array('placeholder'=>'My Store Inc. etc.'), 'help'=>'Will be displayed on customer email.', 'multilan'=>false),
			
			'howtoorder'	            => array('title'=> 'How to order', 'tab'=>'howto', 'type'=>'bbarea', 'help'=>'Enter how-to-order info.'),

			'admin_items_perpage'	    => array('title'=> 'Products per page', 'tab'=>'admin', 'type'=>'number', 'help'=>''),
			'admin_categories_perpage'	=> array('title'=> 'Categories per page', 'tab'=>'admin', 'type'=>'number', 'help'=>''),

			'additional_fields'         => array('title'=>'Additional Fields', 'tab'=>'checkout', 'type'=>'method'),
			
			'custom_css'	            => array('title'=> 'Custom CSS', 'tab'=>'css', 'type' => 'textarea', 'data' => 'str', 'width' => '100%', 'readParms' => array(), 'writeParms' => array('cols'=> 80, 'rows' => 10, 'size'=>'block-level'), 'help'=>'Use this field to enter any vstore related custom css, without the need to edit any source files.'),

			'tax_calculate'	            => array('title'=> 'Calculate tax', 'tab'=>'tax', 'type'=>'bool', 'data' => 'int','help'=>'Enable to activate tax calculation.', 'writeParms' => array('label' => 'yesno')),
			'tax_business_country'		=> array('title'=> 'Business country', 'tab'=>'tax', 'type'=>'country', 'data' => 'string', 'help'=>'The country where the business is located.', 'writeParms' => array()),
			'tax_check_vat'	            => array('title'=> 'Check VAT id online (EU only!)', 'tab'=>'tax', 'type'=>'bool', 'data' => 'int','help'=>'Enable to activate online VAT id checking. (EU only!)', 'writeParms' => array('label' => 'yesno')),
			'tax_classes'				=> array('title'=> 'Tax classes', 'tab'=>'tax', 'type'=>'method', 'data' => 'array', 'help'=>'The tax classes and default tax value to use with the products.<br />Enter tax value as decimal number. eg. 0.19 for 19%', 'writeParms' => array()),
			
			'menu_cat'				    => array('title'=> 'Product category', 'tab'=>'menu', 'type'=>'dropdown', 'data' => 'int', 'help'=>'', 'writeParms' => array()),
			'menu_item_count'		    => array('title'=> 'Product limit', 'tab'=>'menu', 'type'=>'number', 'data' => 'int', 'help'=>'Enter the number of products to display in the menu.', 'writeParms' => array('decimals' => 0,'default' => 2)),
		);



		// optional
		public function init()
		{
			//$this->prefs['currency']['writeParms'] = array('USD'=>'US Dollars', 'EUR'=>'Euros', 'CAN'=>'Canadian Dollars', 'GBP'=>'GB Pounds');

			$currencies = vstore::getCurrencies();
			foreach($currencies as $k => $v)
			{
				$this->prefs['currency']['writeParms'][$k] = $v['title'];
			}
			
			$this->prefs['amount_format']['writeParms'] = array('0'=>'Currency before number', '1'=>'Currency behind number');

			$this->prefs['weight_unit']['writeParms'] = vstore::weightUnits();

			$this->prefs['shipping_method']['writeParms']['size'] = 'xxlarge';

			$this->prefs['shipping_method']['writeParms']['optArray'] = array(
				'sum_simple'	=> 'Sum of the shipping cost of all items',
				'sum_unique'	=> 'Sum of the shipping cost of only unique items',
				'tiered'		=> 'Use a tiered system based on price or weight',
			);

			// Get all active product categories 
			$this->prefs['menu_cat']['writeParms']['optArray'] = array();
			if ($data = e107::getDb()->retrieve('vstore_cat', 'cat_id,cat_name', 'ORDER BY cat_parent, cat_name', true))
			{
				foreach($data as $row)
				{
					$this->prefs['menu_cat']['writeParms']['optArray'][$row['cat_id']] = $row['cat_name'];
				}
			}

			if (!isset($this->prefs['show_outofstock'])) {
				// new pref... set default value
				$this->prefs['show_outofstock'] = 1;
			}

		}

		public function beforePrefsSave($new_data, $old_data)
		{
			// Fix the shipping data array
			if (isset($new_data['shipping_data']))
			{
				$sd = $new_data['shipping_data'];
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

				$new_data['shipping_data'] = $tmp;
			}

			// Fix the tax_classes array
			if (isset($new_data['tax_classes']))
			{
				$tc = $new_data['tax_classes'];
				if (!is_array($tc))
				{
					$tc = e107::unserialize($tc);
				}

				if (count($tc) == 0)
				{
					$tc = self::$taxClassesDefault;
				}
				$defaultKeys = array_column(self::$taxClassesDefault, 'name');
				// Make sure that the array is correctly indexed
				$tmp = array();
				$used = array();
				foreach ($tc as $key => $value) {
					if (empty($value['name'])) 
					{
						continue;
					}
					if ($key < count($defaultKeys) && $value['name'] != $defaultKeys[$key])
					{
						$forceSave = true;
						$value['name'] = $defaultKeys[$key];
						e107::getMessage()->addWarning('Tax classes seam not to be in order!<br>The first 3 must be "none", "reduced", "standard"!<br/>Add your country specific classes after them.');
					}
					if (in_array($value['name'], $used))
					{
						continue;
					}
					$used[] = $value['name'];

					$tmp[] = array(
						'name' => strtolower(trim(strip_tags($value['name']))), 
						'description' => trim(strip_tags($value['description'])), 
						'value' => floatval($value['value']));
				}

				$new_data['tax_classes'] = $tmp;

			}			
			
		}

		
		// public function customPage()
		// {
		// 	$ns = e107::getRender();
		// 	$text = 'Hello World!';
		// 	$ns->tablerender('Hello',$text);	
			
		// }
	
			
}


class vstore_pref_form_ui extends e_admin_form_ui
{
	// private static $taxClassesDefault = array(
	// 	0 => array('name'=>'none', 		'description'=>'No tax', 		'value'=>'0.00'),
	// 	1 => array('name'=>'reduced', 	'description'=>'Reduced tax', 	'value'=>'0.00'),
	// 	2 => array('name'=>'standard', 	'description'=>'Standard tax', 	'value'=>'0.00'),
	// );

	public function init()
	{
		$d = e107::unserialize(e107::pref('vstore','shipping_data'));
		$max = 0;

		if(!empty($d))
		{
			$max = (int) max(array_keys($d));
		}

		$max++;

		$js = "
		var rowcount = $max;
		$(function(){
			$('.vstore-shipping-add').click(function(){
				rowcount++;
				var row = $('#vstore-shipping-data-template').html();
				row = row.replace(new RegExp('%ROW%', 'g'), rowcount);
				row = row.replace(new RegExp('xxx>', 'g'), 'td>');
				row = '<tr>' + row + '</tr>';

				$('#vstore-shipping-data').append(row).show('slow');

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

		if(!empty($curVal) )
		{
			$curVal = e107::unserialize($curVal); // a BC precaution for anyone who still has a json pref.
		}
		elseif(!is_array($curVal))
		{
			$curVal = array();
		}

		//	'shipping_unit'	        	=> array('title'=> 'Value based on', 'tab'=>'ship', 'type'=>'dropdown', 'data' => 'string', 'help'=>'Define which value (subtotal or weight) will be used to calculate shipping costs.', 'writeParms' => array('money'=>'Cart subtotal', 'weight'=>'Cart total weight')),
// 			'shipping_limit'        	=> array('title'=> 'Cost are', 'tab'=>'ship', 'type'=>'dropdown', 'data' => 'string', 'help'=>'Define if the shipping cost are fixed to the spcified cost or limited to that value', 'writeParms' => array('fixed'=>'Fixed shipping costs', 'max'=>'Up to (max.) shipping costs')),
		$pref = e107::pref('vstore');

		$unitDropdown = $this->select('shipping_unit', ['price'=>'When total cart price is below &mdash;', 'weight'=>'When total cart weight is below &mdash;'], varset($pref['shipping_unit']), ['size'=>'block-level']);

		$limitDropdown = $this->select('shipping_limit', ['fixed'=>'Set shipping cost to &mdash;', 'max'=>'Limit shipping cost to no more than &mdash;'], varset($pref['shipping_limit']), ['size'=>'block-level']);


		$text = '
		<div>
		<table class="table table-striped table-bordered table-condensed" id="vstore-shipping-data" style="margin-bottom:5px; width: 800px">
		<tr>
			<td style="width:45%">'.$unitDropdown.'</td>
			<td style="width:45%">'.$limitDropdown.'</td>
			<td> </td>
		</tr>
		';

		unset($curVal['%ROW%']);

		$i = 0;
		if (count($curVal) == 0)
		{
			$text .= '
			<tr>
				<td>'.$this->text('shipping_data['.$i.'][unit]', '0.00', 8, array('pattern' => '^(\d+(\.\d{1,2}){0,1})$', 'min' => '0', 'size'=>'block-level')).'</td>
				<td>'.$this->text('shipping_data['.$i.'][cost]', '0.00', 8, array('pattern' => '^(\d+(\.\d{1,2}){0,1})$', 'min' => '0', 'size'=>'block-level')).'</td>
				<td>&nbsp;</td>
			</tr>
			';
			
		}
		else
		{
			foreach ($curVal as $x => $val)
			{
				$text .= '
				<tr>
					<td>'.$this->text('shipping_data['.$i.'][unit]', number_format($val['unit'], 2), 8, array('pattern' => '^(\d+(\.\d{1,2}){0,1})$', 'min' => '0', 'size'=>'block-level')).'</td>
					<td>'.$this->text('shipping_data['.$i.'][cost]', number_format($val['cost'], 2), 8, array('pattern' => '^(\d+(\.\d{1,2}){0,1})$', 'min' => '0', 'size'=>'block-level')).'</td>
					<td>';

				$text .= ($i !== 0) ? '<button class="vstore-shipping-remove btn  btn-danger" type="button"><i class="fa fa-times"></i> </button>' : '&nbsp;';
				$text .= '</td>
				</tr>
				';
				$i++;
			}
		}
		
		$text .= '
		</table>
		</div>

		<button class="vstore-shipping-add btn btn-sm btn-primary" type="button"><i class="fa fa-plus"></i> '.LAN_ADD.'</button>
		';

		$text .= '
		<div id="vstore-shipping-data-template" style="display:none;">
			<xxx>'.$this->text('shipping_data[%ROW%][unit]', '0.00', 8, array('pattern' => '^(\d+(\.\d{1,2}){0,1})$', 'min' => '0', 'size'=>'block-level')).'</xxx>
			<xxx>'.$this->text('shipping_data[%ROW%][cost]', '0.00', 8, array('pattern' => '^(\d+(\.\d{1,2}){0,1})$', 'min' => '0', 'size'=>'block-level')).'</xxx>
			<xxx><button class="vstore-shipping-remove btn btn-danger" type="button"><i class="fa fa-times"></i> </button></xxx>
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


	function customer_userclass($curVal, $mode)
	{
		$items = e107::getUserClass()->getClassList('nobody,member,classes');
		$items = array('-1' => 'As defined in product') + $items;
		return $this->select('customer_userclass', $items, $curVal);
		
	}


	function tax_classes($curVal,$mode)
	{
		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;

			case 'write': // Edit Page

				if(!empty($curVal))
				{
					$cur = e107::unserialize($curVal); // a BC precaution for anyone who still has a json pref.
				}
				else
				{
					$cur = vstore_pref_ui::$taxClassesDefault;
				}

				foreach(vstore::getTaxClasses() as $v)
				{
					$tax_classes[$v] = $v; 
				}



				$text = '
					
					<div class="tax-classes-container">';

					foreach($cur as $i=>$v)
					{
						// Default class names are readonly!
						$readonly = in_array($v['name'], array('none', 'reduced', 'standard'));
						$text .= '	
							<div class="form-inline tax-classes-row" style="margin-bottom:5px">'.
							$this->select('tax_classes['.$i.'][name]', $tax_classes, $v['name'], array('id'=>null, 'size'=>'medium', 'placeholder'=>'Name', 'readonly' => $readonly)).
							" ".$this->text('tax_classes['.$i.'][description]', $v['description'], 150, array('id'=>null, 'size'=>'large', 'placeholder'=>'Description')).
							" ".$this->text('tax_classes['.$i.'][value]', $v['value'], 6, array('id'=>null, 'size'=>'small', 'placeholder'=> 'Tax', 'pattern' => '^0\.?[0-9]{0,4}$')).
							" ".$this->button('tax-remove', '1', 'action', "<i class='fa fa-times'></i> ", array('class'=>'btn btn-danger vstore-tax-remove'.($readonly ? ' hidden invisible' : '')))
							.'</div>';

					}

					$text .= '
					</div>
										
				';

				$text .= $this->button('clonetaxclass',1,'action', "<i class='fa fa-plus'></i> ".LAN_ADD, array('class'=>'btn btn-primary btn-sm'));

				e107::js('footer-inline', "
				
				
					var taxRowCount = $('.tax-classes-row').length;
					$('#clonetaxclass').on('click', function()
					{
						var row = $('.tax-classes-row:first').clone();
						//var rowCount = $('.tax-classes-row').length;
						taxRowCount++;
						
						//row.find('select').prop('readonly','');

						row.html(row.html().replace(/readonly=\"readonly\"/g,''));
						row.html(row.html().replace(new RegExp('value=\"[^\"]*\"', 'g'),'value=\"\"'));
						// fix empty option value
						row.html(row.html().replace(new RegExp('option value=\"\"', 'g'),'option'));
						row.html(row.html().replace(/\[0\]/g,'[' + taxRowCount + ']'));
						row.html(row.html().replace(/hidden/g,''));
						row.html(row.html().replace(/invisible/g,''));
					
						row.css('display', 'none');
						
						$('.tax-classes-container').append(row);
						row.show('slow');									
							
					});
				

					$('body').on('click', '.vstore-tax-remove', function(){
						var row = $(this).parent();
						row.remove();
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


