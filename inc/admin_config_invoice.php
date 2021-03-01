<?php
/**
 * Adminarea module cart
 */
class vstore_invoice_pref_ui extends e_admin_ui
{
			
		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';


		// optional
        protected $preftabs = array('pref'=>LAN_PREFS, 'temp'=>LAN_TEMPLATE);


		protected $prefs = array(
			'invoice_create_pdf'   		=> array('title'=> LAN_VSTORE_INV_001, 'tab'=>'pref', 'type'=>'boolean', 'data' => 'int', 'writeParms'=>array('default'=>'0'),'help' => LAN_VSTORE_INV_013),
			'invoice_title'        		=> array('title'=> LAN_TITLE, 'tab'=>'pref', 'type'=>'text', 'data' => 'str', 'writeParms'=>array('placeholder'=>'Title', 'default'=>'INVOICE'),'multilan'=>true, 'help' => LAN_VSTORE_INV_008),
			'invoice_info_title'        => array('title'=> LAN_VSTORE_INV_002, 'tab'=>'pref', 'type'=>'text', 'data' => 'str', 'writeParms'=>array('placeholder'=>'Information block title', 'default'=>'Information'),'multilan'=>true, 'help' => LAN_VSTORE_INV_009),
			'invoice_subject'        	=> array('title'=> LAN_SUBJECT, 'tab'=>'pref', 'type'=>'text', 'data' => 'str', 'writeParms'=>array('size'=>'block-level', 'placeholder'=>'Subject', 'default'=>'This is the invoice for your order #{ORDER_DATA: order_ref} from {ORDER_DATA: order_date}'),'multilan'=>true, 'help'=>LAN_VSTORE_INV_003),
			'invoice_nr_prefix'  		=> array('title'=> LAN_VSTORE_INV_004, 'tab'=>'pref', 'type'=>'text', 'data' => 'str', 'writeParms'=>array('placeholder'=>'IN', 'default'=>'IN', 'maxlength' => '5'), 'help' => LAN_VSTORE_INV_014),
			'invoice_next_nr'  			=> array('title'=> LAN_VSTORE_INV_005, 'tab'=>'pref', 'type'=>'method', 'data' => 'int', 'writeParms'=>array('default'=>'1'), 'help' => LAN_VSTORE_INV_015),
			'invoice_date_format'		=> array('title'=> LAN_VSTORE_INV_006, 'tab'=>'pref', 'type'=>'text', 'data' => 'str', 'writeParms'=>array('placeholder'=>''.LAN_VSTORE_INV_021.'', 'default'=>'%m/%d/%Y'), 'help' => LAN_VSTORE_INV_022),
			'invoice_payment_deadline'  => array('title'=> LAN_VSTORE_INV_007, 'tab'=>'pref', 'type'=>'number', 'data' => 'int', 'writeParms'=>array('default'=>'7'), 'help' => LAN_VSTORE_INV_017),
			'invoice_hint'        		=> array('title'=> LAN_VSTORE_INV_010, 'tab'=>'temp', 'type' =>'method', 'data' => 'str', 'help' => LAN_VSTORE_INV_018),
			'invoice_finish_phrase'		=> array('title'=> LAN_VSTORE_INV_011, 'tab'=>'temp', 'type' =>'method', 'data' => 'str', 'help' => LAN_VSTORE_INV_019, 'writeParms' => array('placeholder' => 'Finishing phase', 'default' => "Thanks for your business!\n\n\nYours faithfully\n\n_______________________________________")),
			'invoice_footer'        	=> array('title'=> LAN_VSTORE_INV_012, 'tab'=>'temp', 'type'=>'method', 'data' => 'json', 'writeParms'=>'', 'help' => LAN_VSTORE_INV_020),
			
			// 'invoice_template'         => array('title'=> "Invoice template", 'type'=>'method', 'tab' => 1, 'data' => 'str'),
		);



		// optional
		public function init()
		{

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
				e107::getMessage()->addInfo(''.LAN_VSTORE_INV_016.'');
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

	function invoice_template($curVal, $mode)
	{
		$orig_templates = e107::getTemplate('vstore', 'vstore_invoice');
		$orig_templates = $orig_templates['default'];

		$text = '
		<div class="row">
			<div class="form-group">
				<div class="text-right col-6 col-xs-6">
					'.$this->button('', '<span class="fa fa-undo"></span> '. ''.LAN_VSTORE_MAIL_001.'', 'action', '', array('data-template' => rawurlencode($orig_templates), 'class' => 'vstore-invoice-reset pull-right btn-sm', 'title' => ''.LAN_VSTORE_MAIL_002.'')).'
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