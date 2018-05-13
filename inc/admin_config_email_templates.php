<?php
/**
 * Adminarea module cart
 */
class vstore_email_templates_ui extends e_admin_ui
{
			
		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';


		// optional
		protected $preftabs = array(); //array(LAN_GENERAL, "Shipping", "Emails", "How to Order", "Admin Area", "Check-Out", "Custom CSS", "Tax");


		protected $prefs = array(
			'email_templates'           => array('title'=> "Email templates", 'type'=>'method'),
		);



		// optional
		public function init()
		{
			if(!empty($_POST['email_templates']))
			{
				e107::getPlugConfig('vstore')->set('email_templates', $_POST['email_templates'])->save(true,true,true);

			}

		}


		/**
		 * @todo Move to a modal pop-up or some other 'on-demand' area.
		 * @return string
		 */
		private function renderTemplateKeys()
		{
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

			foreach ($email_fields as $key => $value)
			{
				$field_notes[] = sprintf('<div class="col-md-2 label-default" style="padding:7px">%s</div><div class="col-md-2" style="padding:7px">%s</div>', $key, $value);
			}

			return implode("\n", $field_notes);



		//	$this->prefs['email_templates']['title'] = $text;





		}

		
		public function templatesPage()
		{
			$frm = e107::getForm();		

			e107::wysiwyg(true);
	
			$orig_templates = e107::getTemplate('vstore', 'vstore_email');
	
	        $tab = array();

	        $curVal = e107::pref('vstore', 'email_templates');

			foreach (vstore::getEmailTypes() as $type => $label)
			{
	
				$orig_template = $orig_templates[$type];

				if (empty($curVal[$type]['template']))
				{
					$curVal = array();
					$curVal[$type]['template'] = $orig_template;
				}
				$isActive = isset($curVal[$type]['active']) ? $curVal[$type]['active'] : true;
				$isCC = isset($curVal[$type]['cc']) ? $curVal[$type]['cc'] : true;
	
				$text = '
				<div class="row" style="padding-top:20px">
					<div class="col-md-12 form-group">
						<label class="control-label col-3 col-xs-3">'.LAN_ACTIVE.'?</label>
						<div class="text-right col-3 col-xs-3">
						'.$frm->flipswitch('email_templates['.$type.'][active]', $isActive, null, array('switch'=>'small', 'title' => LAN_ACTIVE)).'
						</div>
						<div class="text-right col-6 col-xs-6">
							'.$frm->button('', '<span class="fa fa-undo"></span> '. 'Reset template', 'action', '', array('data-template' => rawurlencode($orig_template), 'data-type' => $type, 'class' => 'vstore-email-reset pull-right btn-sm', 'title' => 'Click & save to reset this template to the default template.')).'
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 form-group">
						<label class="control-label col-3 col-xs-3">Receive email in CC?</label>
						<div class="text-right col-3 col-xs-3">
						'.$frm->flipswitch('email_templates['.$type.'][cc]', $isCC, null, array('switch'=>'small', 'title' => 'Receive this email in CC?')).'
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
					'.$frm->textarea('email_templates['.$type.'][template]', $curVal[$type]['template'], 10, 80, array('class' => 'tbox form-control input-block-level e-autoheight e-wysiwyg')).'
					</div>
				</div>
				';
				
	
				$tab[] = array('caption'=>$label,'text' => $text);
	
			}

			$text = $frm->open('vstore_email_templates','post');

			$text .= $frm->tabs($tab);

				$tp = e107::getParser();

			$text .= "<div class='buttons-bar row'>
				<div class='col-md-4'></div>
				<div class='col-md-4 center'>".$frm->admin_button('save', 1, 'update', LAN_UPDATE)."</div>
				<div class='col-md-4 right'><a class='btn btn-sm btn-default e-expandit' href='#leg'>".$tp->toGlyph('fa-info-circle')." Field Options</a></div>
				</div>";

			$text .= $frm->close();

		//	$text .= "<hr />";

			$text .= '<div id="leg" class="small" style="display:none">'.$this->renderTemplateKeys().'</div>';




			return $text;
			
		}
	
			
}


class vstore_email_templates_form_ui extends e_admin_form_ui
{

	public function init()
	{

        $js = "
		$(function(){
			$('.vstore-email-reset').click(function(){
				var type = $(this).data('type');
				var template = decodeURIComponent($(this).data('template'));

				var id = 'email-templates-'+type+'-template';
				$('#'+id).val(template);
				$(tinymce.get(id).getBody()).html(template);
			});
		});
		";
        e107::js('footer-inline', $js);
        
	}

	function email_templates($curVal, $mode)
	{

		 		
	}

}		

?>