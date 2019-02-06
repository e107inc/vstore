<?php
/**
 * Adminarea module cart
 */
class vstore_invoice_pref_ui extends e_admin_ui
{
			
		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';


		// optional
		protected $preftabs = array(LAN_PREFS, LAN_TEMPLATE);


		protected $prefs = array(
			'invoice_title'        		=> array('title'=> 'Title', 'tab'=>0, 'type'=>'text', 'data' => 'str', 'writeParms'=>array('placeholder'=>'Title', 'default'=>'INVOICE'),'multilan'=>true, 'help' => 'Title of the invoice'),
			'invoice_info_title'        => array('title'=> 'Information section caption', 'tab'=>0, 'type'=>'text', 'data' => 'str', 'writeParms'=>array('placeholder'=>'Information block title', 'default'=>'Information'),'multilan'=>true, 'help' => 'Title of the information block on the top right side of the invoice.'),
			'invoice_subject'        	=> array('title'=> 'Subject', 'tab'=>0, 'type'=>'text', 'data' => 'str', 'writeParms'=>array('size'=>'block-level', 'placeholder'=>'Subject', 'default'=>'This is the invoice for your order #{ORDER_DATA: order_ref} from {ORDER_DATA: order_date}'),'multilan'=>true, 'help'=>'This is rendered right above the items.'),
			'invoice_nr_prefix'  		=> array('title'=> 'Invoice number prefix', 'tab'=>0, 'type'=>'text', 'data' => 'str', 'writeParms'=>array('placeholder'=>'IN', 'default'=>'IN', 'maxlength' => '5'), 'help' => 'Define the prefix for your invoice number. This will be rendered e.g. IN000012'),
			'invoice_next_nr'  			=> array('title'=> 'Next invoice number', 'tab'=>0, 'type'=>'method', 'data' => 'int', 'writeParms'=>array('default'=>'1'), 'help' => 'This enables you to start at a given invoice number, or to step over a number. But it will ALWAYS be bigger than the last invoice number used!'),
			'invoice_date_format'		=> array('title'=> 'Invoice date format', 'tab'=>0, 'type'=>'text', 'data' => 'str', 'writeParms'=>array('placeholder'=>'Dateformat used on invoices (date only)', 'default'=>'%m/%d/%Y'), 'help' => 'Date format (date only) used on onvoices. e.g. 05/02/2018'),
			'invoice_payment_deadline'  => array('title'=> 'Default payment deadline (days)', 'tab'=>0, 'type'=>'number', 'data' => 'int', 'writeParms'=>array('default'=>'7'), 'help' => 'A notice will be added to the invoice, when the invoice should be paid latest.'),
			'invoice_hint'        		=> array('title'=> 'Hint', 'tab'=>1, 'type' =>'method', 'data' => 'str', 'help' => 'This will be rendered on the invoice below the items and can be used to add some information on each invoice.'),
			'invoice_finish_phrase'		=> array('title'=> 'Finishing phrase', 'tab'=>1, 'type' =>'method', 'data' => 'str', 'help' => 'This will be rendered on the invoice below the items and the hint.', 'writeParms' => array('placeholder' => 'Finishing phase', 'default' => "Thanks for your business!\n\n\nYours faithfully\n\n_______________________________________")),
			'invoice_footer'        	=> array('title'=> 'Footer content', 'tab'=>1, 'type'=>'method', 'data' => 'json', 'writeParms'=>'', 'help' => 'These fields will be rendered on the bottom of each page.'),
			
			// 'invoice_template'         => array('title'=> "Invoice template", 'type'=>'method', 'tab' => 1, 'data' => 'str'),
		);



		// optional
		public function init()
		{

			// $email_fields = array(
			// 	'{ORDER_DATA: order_ref}'		=> 'The order reference number',
			// 	'{ORDER_DATA: cust_firstname}'	=> 'The billing firstname',
			// 	'{ORDER_DATA: cust_lastname}' 	=> 'The billing lastname',
			// 	'{ORDER_DATA: cust_company}'	=> 'The billing company name',
			// 	'{ORDER_DATA: cust_address}'	=> 'The billing street',
			// 	'{ORDER_DATA: cust_city}'		=> 'The billing city',
			// 	'{ORDER_DATA: cust_state}'		=> 'The billing state',
			// 	'{ORDER_DATA: cust_zip}'		=> 'The billing zip code',
			// 	'{ORDER_DATA: cust_country}'	=> 'The billing country',
			// 	'{ORDER_DATA: ship_firstname}'	=> 'The shipping firstname',
			// 	'{ORDER_DATA: ship_lastname}' 	=> 'The shipping lastname',
			// 	'{ORDER_DATA: ship_company}'	=> 'The shipping company name',
			// 	'{ORDER_DATA: ship_address}'	=> 'The shipping street',
			// 	'{ORDER_DATA: ship_city}'		=> 'The shipping city',
			// 	'{ORDER_DATA: ship_state}'		=> 'The shipping state',
			// 	'{ORDER_DATA: ship_zip}'		=> 'The shipping zip code',
			// 	'{ORDER_DATA: ship_country}'	=> 'The shipping country',
			// 	'{ORDER_ITEMS}'					=> 'The ordered items',
			// 	'{ORDER_PAYMENT_INSTRUCTIONS}' 	=> 'In case of payment method "bank transfer", the bank transfer details',
			// 	'{ORDER_MERCHANT_INFO}'			=> 'Merchant name & adress',
			// 	'{SENDER_NAME}'					=> 'Sender name es defined in the vstore prefs'
			// );
	
			// foreach ($email_fields as $key => $value) {
			// 	$field_notes[] = sprintf('<div>%s</div><div class="col-sm-offset-1">%s</div>', $key, $value);
			// }
	
			// $text = '<div>'.$this->prefs['email_templates']['title'].'<br/><br/>Available fields<br/>';
			// $text .= '<div class="small">'.implode("\n", $field_notes).'</div></div>';
	
			// $this->prefs['email_templates']['title'] = $text;
			
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

        $js = "
		$(function(){
			$('.vstore-invoice-reset').click(function(){
				var template = decodeURIComponent($(this).data('template'));

				var id = 'invoice-template';
				$('#'+id).val(template);
			//	$(tinymce.get(id).getBody()).html(template);
			});
		});
		";
        e107::js('footer-inline', $js);
        
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

?>