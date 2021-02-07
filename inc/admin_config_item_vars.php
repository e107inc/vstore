<?php
/**
 * Adminarea module item vars
 */
class vstore_items_vars_ui extends e_admin_ui
{

		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';
	//	protected $eventName		= 'vstore-vstore_items_vars'; // remove comment to enable event triggers in admin.
		protected $table			= 'vstore_items_vars';
		protected $pid				= 'item_var_id';
		protected $perPage			= 10;
		protected $batchDelete		= true;
		protected $batchExport     = true;
		protected $batchCopy		= true;

	//	protected $sortField		= 'somefield_order';
	//	protected $sortParent      = 'somefield_parent';
	//	protected $treePrefix      = 'somefield_title';

	//	protected $tabs				= array('Tabl 1','Tab 2'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable.

	//	protected $listQry      	= "SELECT * FROM `#tableName` WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.

		protected $listOrder		= 'item_var_id DESC';

		protected $fields 		= array (
		  'checkboxes'          =>   array ( 'title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => '1', 'class' => 'center', 'toggle' => 'e-multiselect',  ),
		  'item_var_id'         =>   array ( 'title' => LAN_ID, 'data' => 'int', 'width' => '5%', 'help' => '', 'readParms' => array(), 'writeParms' => array(), 'class' => 'left', 'thclass' => 'left',  ),
		  'item_var_name'       =>   array ( 'title' => LAN_NAME, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'inline' => true, 'help' => 'Enter a name for this category of variations.', 'readParms' => array(), 'writeParms'  => array('size'=>'xxlarge', 'placeholder'=>'Variation name. eg. Colors'), 'class' => 'left', 'thclass' => 'left',  ),
		  'item_var_attributes' =>   array ( 'title' => 'Price Modification', 'type' => 'method', 'data' => 'json', 'width' => 'auto', 'help' => 'Enter a name for each variation. Optionally increase or decrease the price of this product variation by a fixed amount (+/-). Or, adjust the price as a percentage (%) of the original price.', 'readParms' => array(), 'writeParms' => array(), 'class' => 'left', 'thclass' => 'left',  ),
		  'item_var_compulsory' =>   array ( 'title' => 'Track inventory', 'type' => 'boolean', 'data' => 'int', 'width' => 'auto', 'batch' => true, 'inline' => true, 'help' => 'When enabled, the inventory of this product variation will be tracked separately.', 'readParms' => array(), 'writeParms' => array('default' => 1), 'class' => 'left', 'thclass' => 'left',  ),
		  'item_var_userclass'  =>   array ( 'title' => LAN_VISIBILITY, 'type' => 'userclass', 'data' => 'int', 'width' => 'auto', 'batch' => true, 'inline' => true, 'help' => '', 'readParms' => array(), 'writeParms' => array(), 'class' => 'left', 'thclass' => 'left',  ),
		  'options'             =>   array ( 'title' => LAN_OPTIONS, 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced' => '1',  ),
		);

		// protected $fieldpref = array('item_var_name', 'item_var_info', 'item_var_compulsory', 'item_var_userclass');
		protected $fieldpref = array('item_var_name', 'item_var_attributes', 'item_var_compulsory', 'item_var_userclass');


	//	protected $preftabs        = array('General', 'Other' );
		protected $prefs = array(
		);


		public function init()
		{
			// Set drop-down values (if any).

		}


		// ------- Customize Create --------

		public function beforeCreate($new_data,$old_data)
		{
			if(!empty($new_data['item_var_attributes']))
			{
				$new_data['item_var_attributes'] = $this->cleanItemVarAttributes($new_data['item_var_attributes']);
			}
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
			if(!empty($new_data['item_var_attributes']))
			{
				$new_data['item_var_attributes'] = $this->cleanItemVarAttributes($new_data['item_var_attributes']);
			}

			return $new_data;
		}

		private function cleanItemVarAttributes($arr)
		{
			$ret = array();
			foreach($arr as $k=>$v)
			{
				if(empty($v['name']))
				{
					continue;
				}

				$ret[$k] = $v;

			}

			return $ret;

		}

		public function afterUpdate($new_data, $old_data, $id)
		{
			// do something
		}

		public function onUpdateError($new_data, $old_data, $id)
		{
			// do something
		}

		// left-panel help menu area.
		public function renderHelp()
		{
		//	$caption = LAN_HELP;
		///	$text = 'Some help text';

		//	return array('caption'=>$caption,'text'=> $text);

		}

	/*
		// optional - a custom page.
		public function customPage()
		{
			$text = 'Hello World!';
			$otherField  = $this->getController()->getFieldVar('other_field_name');
			return $text;

		}




	*/

}



class vstore_items_vars_form_ui extends e_admin_form_ui
{


	// Custom Method/Function
	function item_var_attributes($curVal,$mode)
	{

		switch($mode)
		{
			case 'read': // List Page
				$text = '';
				if (!empty($curVal))
				{
					$attributes = e107::unserialize($curVal);

					$text = "<table class='table table-condensed table-bordered' style='margin:0'>
					<colgroup>
						<col style='width:70%'>
						<col />

					</colgroup>";
					foreach($attributes as $att)
					{
						$att['value'] = (float) $att['value'];

						$number = ($att['operator'] === '%') ? $att['value'].'%' :  $att['operator'] ." ". number_format($att['value'],2);

						$text .= "<tr>
						<td>".$att['name']."</td>
						<td>".$number."</td>
						</tr>";
					}
					$text .= "</table>";
				}

				return $text;
			break;

			case 'write': // Edit Page

				$opts = array('+'=>'+', '-' => '-', '%' => '%');

				if(!empty($curVal))
				{
					$cur = e107::unserialize($curVal);
				}
				else
				{
					$cur = array(
						0 => array('name'=>null, 'operator'=>null, 'value'=>null, 'placeholder'=>'eg. Red'),
						1 => array('name'=>null, 'operator'=>null, 'value'=>null, 'placeholder'=>'eg. Blue')
					);
				}

				$text = '
					<div class="item-var-attributes-container">';

					foreach($cur as $i=>$v)
					{

						$text .= '	
							<div class="form-inline item-var-attributes-row" style="margin-bottom:5px">'.
							$this->text('item_var_attributes['.$i.'][name]', $v['name'], 255, array('id'=>null, 'size'=>'xlarge', 'placeholder'=>varset($v['placeholder']))).
							" ".$this->select('item_var_attributes['.$i.'][operator]', $opts, $v['operator'], array('id'=>null)).
							" ".$this->text('item_var_attributes['.$i.'][value]', $v['value'], 8, array('id'=>null, 'placeholder'=> '0.0', 'size' => 'small'))
							.'</div>';

					}

					$text .= '
					</div>
										
				';

				$text .= $this->button('clone',1,'action', "<i class='fa fa-plus'></i> ".LAN_ADD, array('class'=>'btn btn-primary btn-sm'));

				e107::js('footer-inline', "
				
				
					$('#clone').on('click', function()
					{
				
						var row = $('.item-var-attributes-row:first').clone();
						var rowCount = $('.item-var-attributes-row').length;
											
						row.find('input,select').val('');				
						row.html(row.html().replace(/\[0\]/g,'[' + rowCount + ']'));
					
						row.css('display', 'none');
						
						$('.item-var-attributes-container').append(row);
						row.show('slow');						
			
							
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
