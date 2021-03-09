<?php
/**
 * Adminarea module cart
 */
class vstore_invoice_pref_ui extends e_admin_ui
{
			
		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';


		// optional
		protected $preftabs = array('pref'=>LAN_PREFS, 'temp'=>LAN_TEMPLATE, 'prev'=>LAN_PREVIEW);


		protected $prefs = array(
			'invoice_create_pdf'   		=> array('title'=> 'Create Pdf invoice', 'tab'=>'pref', 'type'=>'boolean', 'data' => 'int', 'writeParms'=>array('default'=>'0'),'help' => 'Enable to create the invoiceas pdf (pdf plugin required!)'),
			'invoice_title'        		=> array('title'=> 'Title', 'tab'=>'pref', 'type'=>'text', 'data' => 'str', 'writeParms'=>array('placeholder'=>'Title', 'default'=>'INVOICE'),'multilan'=>true, 'help' => 'Title of the invoice'),
			'invoice_info_title'        => array('title'=> 'Information section caption', 'tab'=>'pref', 'type'=>'text', 'data' => 'str', 'writeParms'=>array('placeholder'=>'Information block title', 'default'=>'Information'),'multilan'=>true, 'help' => 'Title of the information block on the top right side of the invoice.'),
			'invoice_subject'        	=> array('title'=> 'Subject', 'tab'=>'pref', 'type'=>'text', 'data' => 'str', 'writeParms'=>array('size'=>'block-level', 'placeholder'=>'Subject', 'default'=>'This is the invoice for your order #{ORDER_DATA: order_ref} from {ORDER_DATA: order_date}'),'multilan'=>true, 'help'=>'This is rendered right above the items.'),
			'invoice_nr_prefix'  		=> array('title'=> 'Invoice number prefix', 'tab'=>'pref', 'type'=>'text', 'data' => 'str', 'writeParms'=>array('placeholder'=>'IN', 'default'=>'IN', 'maxlength' => '5'), 'help' => 'Define the prefix for your invoice number. This will be rendered e.g. IN000012'),
			'invoice_next_nr'  			=> array('title'=> 'Next invoice number', 'tab'=>'pref', 'type'=>'method', 'data' => 'int', 'writeParms'=>array('default'=>'1'), 'help' => 'This enables you to start at a given invoice number, or to step over a number. But it will ALWAYS be bigger than the last invoice number used!'),
			'invoice_date_format'		=> array('title'=> 'Invoice date format', 'tab'=>'pref', 'type'=>'dropdown', 'data' => 'str', 'writeParms'=>array('placeholder'=>'Dateformat used on invoices (date only)', /*'default'=>'%m/%d/%Y'*/), 'help' => 'Date format (date only) used on onvoices. e.g. 05/02/2018'),
			'invoice_payment_deadline'  => array('title'=> 'Payment deadline (days)', 'tab'=>'pref', 'type'=>'number', 'data' => 'int', 'writeParms'=>array('default'=>'7'), 'help' => 'The number of days after the order date that the customer has to pay their outstanding balance.'),
			'invoice_hint'        		=> array('title'=> 'Hint', 'tab'=>'temp', 'type' =>'method', 'data' => 'str', 'help' => 'This will be rendered on the invoice below the items and can be used to add some information on each invoice.'),
			'invoice_finish_phrase'		=> array('title'=> 'Finishing phrase', 'tab'=>'temp', 'type' =>'method', 'data' => 'str', 'help' => 'This will be rendered on the invoice below the items and the hint.', 'writeParms' => array('placeholder' => 'Finishing phase', 'default' => "Thanks for your business!\n\n\nYours faithfully\n\n_______________________________________")),
			'invoice_footer'        	=> array('title'=> 'Footer content', 'tab'=>'temp', 'type'=>'method', 'data' => 'json', 'writeParms'=>'', 'help' => 'These fields will be rendered on the bottom of each page.'),
			'invoice_preview'        	=> array('title'=> LAN_PREVIEW, 'tab'=>'prev', 'type'=>'method', 'data' => false, 'writeParms'=>array('nolabel'=>true), 'help' => ''),

			// 'invoice_template'         => array('title'=> "Invoice template", 'type'=>'method', 'tab' => 1, 'data' => 'str'),
		);



		// optional
		public function init()
		{

			if($this->getAction() === 'preview')
			{
				echo $this->previewPage();
				exit;
			}

			$dateFormats = array(
				 'dd M, yy',
				 'dd M, yyyy',
				 'dd MM, yy',
				 'dd MM, yyyy',
				 'MM dd, yyyy',
				 'yyyy-mm-dd',
				 '%m/%d/%Y'
			);

			$opts = [];
			$tp = e107::getParser();

			foreach($dateFormats as $format)
			{
				$opts[$format] = $tp->toDate(time(), $format);
			}

			$this->prefs['invoice_date_format']['writeParms']['optArray'] = $opts;


		}


		public function previewPage()
		{
			/** @var vstore $vc */
			$vc = e107::getSingleton('vstore_order', e_PLUGIN.'vstore/inc/vstore_order.class.php');
			$vc->initPrefs();

			$country = e107::pref('vstore', 'tax_business_country');
			$rate = vstore::getTaxRate('standard', $country);
			$tax = ($rate * 125);
			$shipping = 5.00;
			$total = 125 + $tax + $shipping;


				$data = array(
					'order_id' => '1',
					'order_date' => '1615056941',
					'order_session' => '2edaa69d49daff8c93ae911215c97f22',
					'order_e107_user' => '1',
					'order_cust_id' => '0',
					'order_status' => 'N',
					'order_items' => '[
			    {
			        "id": "1",
			        "name": "ITEM1",
			        "price": "50.00",
			        "description": "Product One",
			        "quantity": "2",
			        "tax_rate": '.$rate.',
			        "file": "0",
			        "vars": "",
			        "item_vars": ""
			    },
			    {
			        "id": "2",
			        "name": "ITEM2",
			        "price": "25.00",
			        "description": "Product Two",
			        "quantity": "1",
			        "tax_rate": '.$rate.',
			        "file": "",
			        "vars": "",
			        "item_vars": ""
			    }
			]',
				'order_refcode' => 'JOSM000001',
				'order_invoice_nr' => '1',
				'order_billing' => '{
			    "firstname": "John",
			    "lastname": "Smith",
			    "company": "Company Inc.",
			    "address": "123 customer ave.",
			    "city": "My City",
			    "state": "State",
			    "zip": "54321",
			    "country": "'.$country.'",
			    "email": "customer1234@email.com",
			    "additional_fields": null
			}',
			'order_use_shipping' => '0',
			'order_shipping' => '{
			    "firstname": "Fred",
			    "lastname": "Jones",
			    "address": "333 shipping ave.",
			    "city": "Shipcity",
			    "state": "State",
			    "zip": "12345",
			    "country": "us"
			}',
			'order_pay_gateway' => 'bank_transfer',
			'order_pay_status' => 'refunded',
			'order_pay_transid' => NULL,
			'order_pay_amount' => $total,
			'order_pay_tax' => '{
			    "'.$rate.'": '.$tax.'
			}',
			'order_pay_shipping' => $shipping,
			'order_pay_currency' => 'USD',
			'order_pay_coupon_code' => '',
			'order_pay_coupon_amount' => '0.00',
			'order_pay_rawdata' => '{
			   "purchase": {
			        "purchase": null
			    },
			    "refund": {
			        "Refunded": "Order refunded on 2021-03-06 18:55:53 by e107-cli (1)"
			    }
			}',
				'order_refund_date' => NULL,
				'order_log' => ''
			);

			$vc->setData($data);

			$eml = array(
				'subject'		=> 'Test Subject',
				'body' 			=> $vc->renderInvoiceTemplate(),
				'template'		=> 'blank',
			);

			return e107::getEmail()->preview($eml);

		}

		/**
		 * User defined before pref saving logic
		 * @param $new_data
		 * @param $old_data
		 */
		public function beforePrefsSave($new_data, $old_data)
		{
			// Has the invoice_create_pdf setting be changed?
			if (vartrue($new_data['invoice_create_pdf'])
				&& !vstore::checkPdfPlugin(false)
			) {
				// pdf plugin not installed: reset setting
				$new_data['invoice_create_pdf'] = 0;
				e107::getMessage()->addInfo('Pdf creation has been disabled!');
			}
			return $new_data;
		}

		/**
		 * User defined before pref saving logic
		 */
		public function afterPrefsSave()
		{

		}

		
		// public function customPage()
		// {
		// 	$ns = e107::getRender();
		// 	$text = 'Hello World!';
		// 	$ns->tablerender('Hello',$text);	
			
		// }
	
			
}


class vstore_invoice_pref_form_ui extends e_admin_form_ui
{

	public function init()
	{
		if (empty($_POST)) {
			// Just opened the prefs page...
			// check if pdf plugin is installed
			vstore::checkPdfPlugin();
		}

        $js = "
		$(function(){
			$('.vstore-invoice-reset').click(function(){
				var template = decodeURIComponent($(this).data('template'));

				var id = 'invoice-template';
				$('#'+id).val(template);
			});
		});
		";
        e107::js('footer-inline', $js);
        
	}

	function invoice_preview($curVal, $mode)
	{
	//	$text = '<iframe src="'.e_SELF.'?mode=invoice&action=preview&iframe=1" style="border:0; width:800px;height: 95vh " ></iframe>';
		$text = "<iframe src='".e_SELF."?mode=invoice&action=preview&id=default' width='100%' height='700'>Loading...</iframe>";

		return $text;
	}

	function invoice_template($curVal, $mode)
	{
		$orig_templates = e107::getTemplate('vstore', 'vstore_invoice');
		$orig_templates = $orig_templates['default'];

		$text = '
		<div class="row">
			<div class="form-group">
				<div class="text-right col-6 col-xs-6">
					'.$this->button('', '<span class="fa fa-undo"></span> '. 'Reset template', 'action', '', array('data-template' => rawurlencode($orig_templates), 'class' => 'vstore-invoice-reset pull-right btn-sm', 'title' => 'Click & save to reset this template to the default template.')).'
				</div>
			</div>
		</div>
		<div class="row">
			'.$this->textarea('invoice_template', $curVal, 5, 80, array('size' => 'block-level')).'
		</div>
		';

		return $text;
		 		
	}


	function invoice_hint($curVal, $mode)
	{
		return $this->textarea('invoice_hint['.e_LANGUAGE.']', (!empty($curVal[e_LANGUAGE]) ? $curVal[e_LANGUAGE] : ''), 5, 80, array('size' => 'block-level'));
	}

	function invoice_finish_phrase($curVal, $mode)
	{
		return $this->textarea('invoice_finish_phrase', (!empty($curVal) ? $curVal : ''), 6, 80, array('size' => 'block-level'));
	}


	function invoice_footer($curVal, $mode)
	{
		if (empty($curVal))
		{
			$curVal = array(
				0 => e107::pref('vstore', 'merchant_info'),
				1 => "Contact\nPhone: \nEmail: \nWebsite: ",
				2 => "Tax information\nVAT-ID: \nTax code: ",
				3 => "Bank information\nBank: \nOwner: \nIBAN: \nBIC/SWIFT: "
			);
		}

		if ($curVal && !is_array($curVal))
		{
			$curVal = e107::unserialize($curVal);
		}

		$tab = array();

		for($i=0; $i < 4; $i++)
		{
			$tab[$i] = array(
				'caption'   =>'Footer #' . ($i) ,
				'text'      => $this->textarea('invoice_footer['.$i.']', $curVal[$i], 6, 80, array('size'=>'block-level'), 'small')
			);
		}

		return $this->tabs($tab);

	}



	function invoice_next_nr($curVal, $mode)
	{
		$next = vstore::getNextInvoiceNr();
		if ($curVal < $next)
		{
			$curVal = $next;
		}

		return $this->number('invoice_next_nr', $curVal);

	}

}		

