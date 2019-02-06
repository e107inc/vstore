<?php


	/**
	 * Adminarea module items
	 */
	class vstore_items_ui extends e_admin_ui
	{

		protected $pluginTitle = 'Vstore';
		protected $pluginName  = 'vstore';
		protected $table       = 'vstore_items';
		protected $pid         = 'item_id';
		protected $perPage     = 10;
		protected $batchDelete = true;
		protected $batchCopy   = true;
		protected $sortField   = 'item_order';
		//	protected $orderStep		= 10;
		protected $tabs = array(LAN_BASIC, LAN_ADVANCED, 'Variations', 'Reviews', 'Files'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable.

		//	protected $listQry      	= "SELECT * FROM #tableName WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.

		protected $listOrder = 'item_id DESC';

		protected $grid = array('title' => 'item_name', 'image' => 'item_preview', 'body' => '', 'class' => 'col-md-2', 'perPage' => 12, 'carousel' => true);

		protected $fields = array(

			// Tab 0
			'checkboxes'     => array('title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => '1', 'class' => 'center', 'toggle' => 'e-multiselect',),
			'item_preview'   => array('title' => LAN_PREVIEW, 'type' => 'method', 'data' => false, 'width' => '5%', 'forced' => 1),
			'item_id'        => array('title' => LAN_ID, 'type' => 'text', 'data' => 'int', 'width' => '5%', 'help' => '', 'readParms' => 'link=sef&target=blank', 'writeParms' => array(), 'class' => 'left', 'thclass' => 'left',),
			'item_active'    => array('title' => LAN_ACTIVE, 'type' => 'boolean', 'data' => 'int', 'inline' => true, 'width' => '5%', 'help' => '', 'readParms' => array(), 'writeParms' => array('default' => '1'), 'class' => 'center', 'thclass' => 'center'),
			'item_code'      => array('title' => 'Code', 'type' => 'text', 'inline' => true, 'data' => 'str', 'width' => '2%', 'help' => '', 'readParms' => array(), 'writeParms' => array(), 'class' => 'center', 'thclass' => 'center',),
			'item_name'      => array('title' => LAN_TITLE, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'inline' => true, 'help' => '', 'readParms' => array(), 'writeParms' => array('size' => 'xxlarge'), 'class' => 'left', 'thclass' => 'left',),
			'item_desc'      => array('title' => LAN_DESCRIPTION, 'type' => 'textarea', 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => array(), 'writeParms' => array('size' => 'xxlarge', 'maxlength' => 250), 'class' => 'center', 'thclass' => 'center',),
			'item_cat'       => array('title' => LAN_CATEGORY, 'type' => 'dropdown', 'data' => 'int', 'width' => 'auto', 'filter' => true, 'batch' => true, 'inline' => true, 'help' => '', 'readParms' => array(), 'writeParms' => array(), 'class' => 'left', 'thclass' => 'left',),
			'item_pic'       => array('title' => 'Images/Videos', 'type' => 'images', 'data' => 'array', 'width' => 'auto', 'help' => '', 'readParms' => array(), 'writeParms' => 'media=vstore&video=1&max=8', 'class' => 'center', 'thclass' => 'center',),
			'item_files'     => array('title' => 'Files', 'type' => 'files', 'tab' => 4, 'data' => 'array', 'width' => 'auto', 'help' => '', 'readParms' => array(), 'writeParms' => 'media=vstore_file_2', 'class' => 'center', 'thclass' => 'center',),
			'item_price'     => array('title' => 'Price', 'type' => 'number', 'data' => 'float', 'width' => 'auto', 'inline' => true, 'help' => 'Price is always the gross price incl. tax!', 'readParms' => array(), 'writeParms' => array('decimals' => 2), 'class' => 'right', 'thclass' => 'right',),


			// Tab 1
			'item_tax_class' => array('title' => 'Tax class', 'type' => 'method',  'tab' => 1,'data' => 'str', 'width' => 'auto', 'filter' => true, 'batch' => true, 'inline' => true, 'help' => '', 'readParms' => array(), 'writeParms' => array(), 'class' => 'left', 'thclass' => 'left'),
			'item_shipping'  => array('title' => 'Shipping', 'type' => 'number', 'tab' => 1, 'data' => 'float', 'width' => 'auto', 'help' => '', 'readParms' => array(), 'writeParms' => array('decimals' => 2), 'class' => 'center', 'thclass' => 'center',),
			'item_weight'    => array('title' => 'Weight', 'type' => 'number', 'tab' => 1, 'data' => 'float', 'width' => 'auto', 'help' => '', 'readParms' => array(), 'writeParms' => array('decimals' => 2), 'class' => 'center', 'thclass' => 'center',),

			'item_details' => array('title' => 'Detailed Description', 'type' => 'bbarea', 'tab' => 1, 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => array(), 'writeParms' => array(), 'class' => 'center', 'thclass' => 'center',),

			'item_link'     => array('title' => 'External Link', 'type' => 'text', 'inline'=>true, 'tab' => 1, 'data' => 'str', 'width' => 'auto', 'help' => 'Optional: Enter URL of item on external site. Used only when no inventory is available.', 'readParms' => array(), 'writeParms' => array('size'=>'xxlarge'), 'class' => 'left', 'thclass' => 'left',),

			'item_download' => array('title' => 'Download File', 'type' => 'file', 'tab' => 1, 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => array(), 'writeParms' => 'media=vstore_file', 'class' => 'center', 'thclass' => 'center',),
			'item_userclass' => array('title' => 'Assign userclass', 'type' => 'method', 'tab'=>1, 'help' => 'Assign userclass to customer on purchase'),


			// Tab 2
			'item_vars'           => array('title' => 'Product Variations', 'tab'=>2, 'type' => 'method'),
			'item_vars_inventory' => array('title' => 'Variations Inventory', 'tab'=>2, 'type' => 'method', 'data' => 'json'),



			// Tab 3
			'item_reviews' => array('title' => 'Reviews', 'type' => 'textarea', 'tab' => 3, 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => array(), 'writeParms' => 'size=xxlarge', 'class' => 'center', 'thclass' => 'center',),
			'item_related' => array('title' => 'Related', 'type' => 'method', 'tab' => 3, 'data' => 'array', 'width' => 'auto', 'help' => '', 'readParms' => array(), 'writeParms' => 'video=1', 'class' => 'center', 'thclass' => 'center',),

			'item_order'          => array('title' => LAN_ORDER, 'type' => 'hidden', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => array(), 'writeParms' => array(), 'class' => 'left', 'thclass' => 'left',),
			'item_inventory'      => array('title' => 'Inventory', 'type' => 'method', 'data' => 'int', 'width' => 'auto', 'inline' => true, 'help' => 'Enter -1 if this item is always available', 'readParms' => array(), 'writeParms' => array('default' => -1), 'class' => 'right item-inventory', 'thclass' => 'right',),




			'options' => array('title' => LAN_OPTIONS, 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'right last', 'class' => 'right last', 'forced' => '1',),
		);

		protected $fieldpref = array('item_active', 'item_code', 'item_name', 'item_sef', 'item_cat', 'item_price', 'item_inventory');

		protected $categories     = array();
		protected $categoriesTree = array();

		// optional
		public function init()
		{

			if($this->getAction() != 'list' && $this->getAction() != 'grid')
			{
				$this->fields['item_preview']['type'] = null;
			}

			$this->perPage = e107::pref('vstore', 'admin_items_perpage', 10);

			if($data = e107::getDb()->retrieve('SELECT item_var_id,item_var_name FROM #vstore_items_vars ORDER BY item_var_name', true))
			{
				$this->fields['item_vars']['writeParms'] = array();
				foreach($data as $k => $v)
				{
					$key = $v['item_var_id'];
					$this->fields['item_vars']['writeParms'][$key] = $v['item_var_name'];
				}
			}
			//	print_a($_POST);


			$data = e107::getDb()->retrieve('SELECT cat_id,cat_name,cat_parent FROM #vstore_cat ORDER BY cat_order', true);
			$parent = array();

			foreach($data as $k => $v)
			{
				$id = $v['cat_id'];
				$parent[$id] = $v['cat_name'];
				$pid = $v['cat_parent'];
				$name = $parent[$pid];
				$this->categories[$id] = $v['cat_name'];
				$this->categoriesTree[$name][$id] = $v['cat_name'];
			}


			$this->fields['item_cat']['writeParms'] = ($this->getAction() == 'list') ? $this->categories : $this->categoriesTree;
			//	print_a($this->categories);

			$tc = e107::pref('vstore', 'tax_classes');
			if(!is_array($tc))
			{
				$tc = e107::unserialize($tc);
			}

			foreach($tc as $tclass)
			{
				$this->fields['item_tax_class']['writeParms'][$tclass['name']] = sprintf('%s (%s%%)', $tclass['description'], ($tclass['value'] * 100.0));
			}

			e107::css('inline', 'table input.form-control{ width: 80px; }');
		}


		public function beforeCreate($new_data, $old_data)
		{

			if(!vartrue($new_data['item_tax_class']))
			{
				// set tax class to "standard" if not defined
				$new_data['item_tax_class'] = 'standard';
			}

			return $new_data;
		}

		public function afterCreate($new_data, $old_data, $id)
		{
			// do something
		}

		public function beforeUpdate($new_data, $old_data, $id)
		{

			if(array_key_exists('item_inventory', $new_data))
			{
				$new_data['item_inventory'] = intval($new_data['item_inventory']);
				$new_data['item_inventory'] = $new_data['item_inventory'] < 0 ? -1 : $new_data['item_inventory'];
			}

			if(array_key_exists('item_vars', $new_data))
			{
				if($new_data['item_vars'] !== explode(',', $old_data['item_vars']))
				{
					if($new_data['item_vars'] == '')
					{
						$new_data['item_vars_inventory'] = '';
					}
					else //if ($old_data['item_vars'] != '')
					{

						$new = $new_data['item_vars'];
						if(count($new) > 2)
						{
							// Only 2 vars allowed
							$new_data['item_vars'] = array($new[0], $new[1]);
							// $new = explode(',', $new_data['item_vars']);
						}
						// Item vars have changed
						// Initialize inventory
						$new_data['item_vars_inventory'] = '';

					}
				}
				$new_data['item_vars'] = implode(',', $new_data['item_vars']);
			}

			if(array_key_exists('item_tax_class', $new_data) && !vartrue($new_data['item_tax_class']))
			{
				// set tax class to "standard" if not defined
				$new_data['item_tax_class'] = 'standard';
			}

			return $new_data;
		}

		public function afterUpdate($new_data, $old_data, $id)
		{

		}

		public function onCreateError($new_data, $old_data)
		{
			// do something
		}

		public function onUpdateError($new_data, $old_data, $id)
		{
			// do something
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


	class vstore_items_form_ui extends e_admin_form_ui
	{

		function item_preview($curVal, $mode, $parm)
		{

			$tp = e107::getParser();


			//return print_a($parm, true);

			//var_dump($parm);

			$size = $this->getController()->getAction() === 'grid' ? 400 : 80;

			if($mode == 'read')
			{
				$img = $this->getController()->getFieldVar('item_pic');

				if($media = e107::unserialize($img))
				{
					foreach($media as $v)
					{
						if(!$tp->isVideo($v['path']))
						{
							return $tp->toImage($v['path'], array('w' => $size, 'h' => $size, 'crop' => 1));
						}
					}
				}


			}

			return false;


		}

		// Custom Method/Function
		function item_tax_class($curVal, $mode)
		{

			$opts = $this->getController()->getFields()['item_tax_class']['writeParms'];

			switch($mode)
			{
				case 'read': // List Page
					if(!empty($curVal))
					{
						$curVal = $opts[$curVal];
					}

					return $curVal;
					break;

				case 'write': // Edit Page
					if(empty($curVal))
					{
						$curVal = 'standard';
					}

					return $this->select('item_tax_class', $opts, $curVal);
					break;

				case 'filter':
				case 'batch':
					return $opts;
					break;
			}
		}


		// Custom Method/Function
		function item_cat($curVal, $mode)
		{
			switch($mode)
			{
				case 'read': // List Page
					return $curVal;
					break;

				case 'write': // Edit Page
					return $this->text('item_cat', $curVal);
					break;

				case 'filter':
				case 'batch':
					return null;
					break;
			}
		}


		// Custom Method/Function
		function item_inventory($curVal, $mode)
		{
			switch($mode)
			{
				case 'read': // List Page
					return $curVal;
					break;

				case 'write': // Edit Page
					//$text = $this->text('item_inventory', $curVal, null, array('pattern' => '^-?\d+$')); // to allow also negative values (<0 = Item will not run out of stock)
					$text = $this->number('item_inventory', $curVal, null, array('class'=>'pull-left', 'decimals' => 0, 'min' => -1)); // to allow also negative values (<0 = Item will not run out of stock)

					$icon = e107::getParser()->toGlyph('fa-info-circle');
					$text .= ' <span style="display:inline-block;padding:6px" title="In case of any Product Variations selected, this setting will ignored! You have to fill out the Variations Inventory instead!">'.$icon.'</span>';

					return $text;
					// return $this->text('item_inventory', $curVal, null, array('pattern' => '^-?\d+$')); // to allow also negative values (<0 = Item will not run out of stock)
					//return $this->number('item_inventory',$curVal);
					break;

				case 'filter':
				case 'batch':
					return null;
					break;

				case 'inline':
					$class = '';

					if($curVal < 1)
					{
						$class = 'text-danger';
					}
					elseif($curVal < 3)
					{
						$class = 'text-warning';
					}


					return array('inlineType' => 'text', 'inlineData' => $curVal, 'inlineParms' => array('class' => $class));
					break;
			}
		}


		function item_vars_inventory($curVal, $mode)
		{

			$item_vars = $this->getController()->getFieldVar('item_vars');

			if($item_vars == '')
			{
				return 'You need to select the Product Variations first!';
			}

			$sql = e107::getDb();


			if($sql->select('vstore_items_vars', '*', sprintf('FIND_IN_SET(item_var_id, "%s") LIMIT 2', $item_vars)))
			{

				if($curVal && !is_array($curVal))
				{
					$curVal = e107::unserialize($curVal);
				}
				else
				{
					$curVal = array();
				}

				$col = array();
				$key = 'x';
				while($item = $sql->fetch())
				{
					$col[$key]['id'] = $item['item_var_id'];
					$col[$key]['caption'] = $item['item_var_name'];
					$attr = e107::unserialize($item['item_var_attributes']);
					foreach($attr as $row)
					{
						$col[$key]['names'][] = $row['name'];
					}
					$key = 'y';
				}

				$text = '<table class="table table-striped table-bordered">
			';
				if(count($col) == 2)
				{
					$text .= sprintf('<tr><th>%s</th><th colspan="%d">%s</th></tr>', 'Inventory', count($col['y']['names']), $col['y']['caption']);

					$text .= sprintf('<tr><th>%s</th>', $col['x']['caption']);
					foreach($col['y']['names'] as $value)
					{
						$text .= sprintf('<th>%s</th>', $value);
					}

					$text .= '</tr>
				';
				}

				if(count($col) == 1)
				{
					$text .= sprintf('<tr><th style="width: 20%%;">%s</th><th>%s</th>', $col['x']['caption'], 'Inventory');
					foreach($col['x']['names'] as $nameX)
					{
						$text .= sprintf('<tr><th style="width: 20%%;">%s</th>', $nameX);
						$nameX = $this->name2id($nameX);
						$value = varset($curVal[$nameX], 0);
						$text .= sprintf('<td>%s</td>', $this->text('item_vars_inventory[' . $nameX . ']', $value, 5, array('pattern' => '^-?\d+$', 'size' => 'sm')));
						$text .= '</tr>
					';
					}
				}
				else
				{
					foreach($col['x']['names'] as $nameX)
					{
						$text .= sprintf('<tr><th style="width: 20%%;">%s</th>', $nameX);
						$nameX = $this->name2id($nameX);
						foreach($col['y']['names'] as $nameY)
						{
							$nameY = $this->name2id($nameY);
							$value = varset($curVal[$nameX][$nameY], 0);
							$text .= sprintf('<td>%s</td>', $this->text('item_vars_inventory[' . $nameX . '][' . $nameY . ']', $value, 5, array('pattern' => '^-?\d+$', 'size' => 'sm')));
						}
						$text .= '</tr>
					';
					}
				}
				$text .= '</table>
			';

				return $text;
			}

			return e107::getMessage()->addError('Product Variations not found! Maybe they have been deleted in the meanwhile ...', 'vstore')->render('vstore');
		}

		function item_vars($curVal, $mode)
		{



			switch($mode)
			{
				case 'read': // List Page
					return $curVal;
					break;

				case 'write': // Edit Page
					$opt_array = array();
					if($data = e107::getDb()->retrieve('SELECT item_var_id,item_var_name FROM #vstore_items_vars ORDER BY item_var_name', true))
					{
						foreach($data as $k => $v)
						{
							$key = $v['item_var_id'];
							$opt_array[$key] = $v['item_var_name'];
						}
					}

				//	$text = $this->checkboxes('item_vars',  $opt_array, $curVal, array('useKeyValues'=>1)); // needs to be saved even when unchecked.

					$text = $this->select('item_vars', $opt_array, $curVal, array('multiple' => 1));
					if($curVal)
					{
						$text .= '<p class="small">Do not select more than 2 variations, as only the first 2 will be stored.<br>
						<b>Be aware, that changing this setting will initialize the Variations Inventory table during save!</b></p>';
					}
					else
					{
						$text .= '<p class="small">Select up to 2 variations, save product and reopen it to access the Variations Inventory table</p>';
					}

					return $text;
					break;

				case 'filter':
				case 'batch':
					return null;
					break;
			}
		}

		// Custom Method/Function
		function item_pic($curVal, $mode)
		{
			switch($mode)
			{
				case 'read': // List Page
					return $curVal;
					break;

				case 'write': // Edit Page
					return $this->text('item_pic', $curVal);
					break;

				case 'filter':
				case 'batch':
					return null;
					break;
			}
		}


		// Custom Method/Function
		function item_price($curVal, $mode)
		{
			switch($mode)
			{
				case 'read': // List Page
					return $curVal;
					break;

				case 'write': // Edit Page
					//return $this->text('item_price', $curVal);
					return $this->number('item_price', $curVal, 10, array('decimals' => 2));
					break;

				case 'filter':
				case 'batch':
					return null;
					break;
			}
		}


		// Custom Method/Function
		function item_ph($curVal, $mode)
		{
			switch($mode)
			{
				case 'read': // List Page
					return $curVal;
					break;

				case 'write': // Edit Page
					return $this->text('item_ph', $curVal);
					break;

				case 'filter':
				case 'batch':
					return null;
					break;
			}
		}


		// Custom Method/Function
		function item_details($curVal, $mode)
		{
			switch($mode)
			{
				case 'read': // List Page
					return $curVal;
					break;

				case 'write': // Edit Page
					return $this->text('item_details', $curVal);
					break;

				case 'filter':
				case 'batch':
					return null;
					break;
			}
		}


		// Custom Method/Function
		function item_related($curVal, $mode)
		{
			$chp = e107::getDb()->retrieve('page_chapters', '*', 'chapter_parent !=0 ORDER BY chapter_order', true);

			foreach($chp as $row)
			{
				$id = 'page_chapters|' . $row['chapter_id'];
				$opt[$id] = $row['chapter_name'];
			}

			$options['Chapters'] = $opt;

			switch($mode)
			{
				case 'read': // List Page
					return $curVal;
					break;

				case 'write': // Edit Page
					return "Tab Name: " . $this->text('item_related[caption]', $curVal['caption']) . "<br />Source: " . $this->select('item_related[src]', $options, $curVal['src'], null, true);
					break;

				case 'filter':
				case 'batch':
					return null;
					break;
			}
		}

		function item_userclass($curVal, $mode)
		{
			$uc = intval(e107::pref('vstore', 'customer_userclass'));


			switch($mode)
			{
				case 'read': // List Page
					return $curVal;
					break;

				case 'write': // Edit Page
					if($uc !== -1)
					{
						$name = e107::getUserClass()->getName($uc);
						$text = "<span class='label label-success' title='Userclass defined in store preferences'>".$name."</span>";
						$text .= $this->hidden('item_userclass', $curVal);

						return $text;
					}
					else
					{
						$items = e107::getUserClass()->getClassList('nobody,member,classes');

						return $this->select('item_userclass', $items, $curVal, array('readonly' => ($uc !== -1)));
					}
					break;

				case 'filter':
				case 'batch':
					return null;
					break;
			}
		}


	}


?>