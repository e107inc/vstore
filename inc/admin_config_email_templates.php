<?php
/**
 * Adminarea module cart
 */
class vstore_email_templates_ui extends e_admin_ui
{
			
		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';

		protected $preftabs = array();
		protected $prefs = array();

		public function init()
		{
			e107::wysiwyg(true);
			$defaultTemplate = e107::getTemplate('vstore', 'vstore_email');

			foreach (vstore::getEmailTypes() as $type => $label)
			{
				$this->preftabs[$type] = $label;

				$this->prefs['email_templates/'.$type.'/active']    = array('title' => LAN_ACTIVE, 'tab' => $type, 'type'=>'bool');
				$this->prefs['email_templates/'.$type.'/cc']        = array('title' => "CC to yourself", 'tab' => $type, 'type'=>'bool');
				$this->prefs['email_templates/'.$type.'/template']  = array('title'=>'Email Template', 'tab' => $type, 'type'=>'bbarea', 'writeParms'=>array('default'=>$defaultTemplate[$type], 'size'=>'large'));
				$this->prefs['template_options'.$type]              = array('title' => LAN_OPTIONS, 'method'=>'templateOptions', 'tab' => $type, 'data'=>false,'type'=>'method', 'writeParms'=>array('nolabel'=>true, 'templateType'=>$type, 'template'=>$defaultTemplate[$type]));

			}

		}




/*
		
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


			return $text;
			
		}
	
			*/
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

	public function templateOptions($curVal, $mode, $att)
	{
		if($mode !== 'write')
		{
			return null;
		}
		$tp = e107::getParser();

		$text = $this->button('', '<span class="fa fa-undo"></span> '. 'Reset template', 'action', '', array('data-template' => rawurlencode($att['template']), 'data-type' => $att['templateType'], 'class' => 'vstore-email-reset btn-sm', 'title' => 'Click & save to reset this template to the default template.'));
		$text .= " <a class='btn btn-sm btn-default e-expandit' href='#leg'>".$tp->toGlyph('fa-info-circle')." Field Options</a>";
		$text .= '<div id="leg" class="small" style="display:none">'.$this->renderTemplateKeys().'</div>';

		return $text;

	}

	/**
	 * @return string
	 * @todo Move to a modal pop-up or some other 'on-demand' area.
	 */
	private function renderTemplateKeys()
	{
		$email_fields = array(
			'{REF}'                        => 'The order reference number',
			'{BILLING: firstname}'         => 'The billing firstname',
			'{BILLING: lastname}'          => 'The billing lastname',
			'{BILLING: company}'           => 'The billing company name',
			'{BILLING: address}'           => 'The billing street',
			'{BILLING: city}'              => 'The billing city',
			'{BILLING: state}'             => 'The billing state',
			'{BILLING: zip}'               => 'The billing zip code',
			'{BILLING: country}'           => 'The billing country',
			'{SHIPPING: firstname}'        => 'The shipping firstname',
			'{SHIPPING: lastname}'         => 'The shipping lastname',
			'{SHIPPING: company}'          => 'The shipping company name',
			'{SHIPPING: address}'          => 'The shipping street',
			'{SHIPPING: city}'             => 'The shipping city',
			'{SHIPPING: state}'            => 'The shipping state',
			'{SHIPPING: zip}'              => 'The shipping zip code',
			'{SHIPPING: country}'          => 'The shipping country',
			'{ORDER_ITEMS}'                => 'The ordered items',
			'{ORDER_PAYMENT_INSTRUCTIONS}' => 'In case of payment method "bank transfer", the bank transfer details',
			'{ORDER_MERCHANT_INFO}'        => 'Merchant name & address',
			'{SENDER_NAME}'                => 'Sender name es defined in the vstore prefs'

		);

		foreach($email_fields as $key => $value)
		{
			$field_notes[] = sprintf('<div class="col-xs-6 col-md-3 col-lg-2 label-default" style="padding:7px">%s</div><div class="col-md-2" style="padding:7px">%s</div>', $key, $value);
		}

		return implode("\n", $field_notes);


		//	$this->prefs['email_templates']['title'] = $text;


	}

}		

