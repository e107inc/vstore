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
		protected $tabs = array(LAN_BASIC, 'Images/Videos', LAN_ADVANCED, 'Variations', 'Reviews', 'Files'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable.

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
			'item_price'     => array('title' => 'Price', 'type' => 'number', 'data' => 'float', 'width' => 'auto', 'inline' => true, 'help' => 'Price is always the gross price incl. tax!', 'readParms' => array('decimals' => 2), 'writeParms' => array('decimals' => 2), 'class' => 'right', 'thclass' => 'right',),
			'item_inventory' => array('title' => 'Inventory', 'type' => 'method', 'data' => 'int', 'width' => 'auto', 'inline' => false, 'help' => 'Enter -1 if this item is always available', 'readParms' => array(), 'writeParms' => array('default' => -1), 'class' => 'right item-inventory', 'thclass' => 'right',),

			// Tab 1
			'item_pic'       => array('title' => 'Images/Videos', 'type' => 'images', 'tab' => 1, 'data' => 'array', 'width' => 'auto', 'help' => '', 'readParms' => array(), 'writeParms' => 'media=vstore&video=1&max=8', 'class' => 'center', 'thclass' => 'center',),

			// Tab 2
			'item_tax_class' => array('title' => 'Tax class', 'type' => 'method',  'tab' => 2,'data' => 'str', 'width' => 'auto', 'filter' => true, 'batch' => true, 'inline' => true, 'help' => '', 'readParms' => array(), 'writeParms' => array(), 'class' => 'left', 'thclass' => 'left'),
			'item_shipping'  => array('title' => 'Shipping', 'type' => 'number', 'tab' => 2, 'data' => 'float', 'width' => 'auto', 'help' => '', 'readParms' => array(), 'writeParms' => array('decimals' => 2), 'class' => 'center', 'thclass' => 'center',),
			'item_weight'    => array('title' => 'Weight', 'type' => 'number', 'tab' => 2, 'data' => 'float', 'width' => 'auto', 'help' => '', 'readParms' => array(), 'writeParms' => array('decimals' => 2), 'class' => 'center', 'thclass' => 'center',),

			'item_details' => array('title' => 'Detailed Description', 'type' => 'bbarea', 'tab' => 2, 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => array(), 'writeParms' => array(), 'class' => 'center', 'thclass' => 'center',),

			'item_userclass' => array('title' => 'Assign userclass', 'type' => 'method', 'tab'=>2, 'help' => 'Assign userclass to customer on purchase'),

			// Tab 3
			'item_vars'           => array('title' => 'Product Variations', 'tab'=>3, 'type' => 'method', 'data' => 'str', 'help' => 'You can select up to 2 variations', 'note' => 'Product variations DO track the inventory!'),
			'item_vars_inventory' => array('title' => 'Variations Inventory', 'tab'=>3, 'type' => 'method', 'data' => 'json'),
			//'item_vars_nt'        => array('title' => 'Product Options', 'tab'=>3, 'type' => 'method', 'data' => false, 'help' => 'Select up to 6 product options.', 'note' => 'Product options are NOT relevant for inventory tracking'),
			'item_vars_nt'        => array('title' => 'Product Options', 'tab'=>3, 'type' => 'method', 'data' => false, 'help' => 'Select up to 6 product options.', 'note' => 'Product options are NOT relevant for inventory tracking'),

			// Tab 4
			'item_reviews' => array('title' => 'Reviews', 'type' => 'textarea', 'tab' => 4, 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => array(), 'writeParms' => 'size=xxlarge', 'class' => 'center', 'thclass' => 'center',),
			'item_related' => array('title' => 'Related', 'type' => 'method', 'tab' => 4, 'data' => 'array', 'width' => 'auto', 'help' => '', 'readParms' => array(), 'writeParms' => 'video=1', 'class' => 'center', 'thclass' => 'center',),

			// Tab 5
			'item_files'     => array('title' => 'Files', 'type' => 'files', 'tab' => 5, 'data' => 'array', 'width' => 'auto', 'help' => '', 'readParms' => array(), 'writeParms' => 'media=vstore_file_2', 'class' => 'center', 'thclass' => 'center',),
			'item_link'     => array('title' => 'External Link', 'type' => 'text', 'inline'=>true, 'tab' => 5, 'data' => 'str', 'width' => 'auto', 'help' => 'Optional: Enter URL of item on external site. Used only when no inventory is available.', 'readParms' => array(), 'writeParms' => array('size'=>'xxlarge'), 'class' => 'left', 'thclass' => 'left',),
			'item_download' => array('title' => 'Download File', 'type' => 'file', 'tab' => 5, 'data' => 'str', 'width' => 'auto', 'help' => '', 'readParms' => array(), 'writeParms' => 'media=vstore_file', 'class' => 'center', 'thclass' => 'center',),

			'item_order'          => array('title' => LAN_ORDER, 'type' => 'hidden', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => array(), 'writeParms' => array(), 'class' => 'left', 'thclass' => 'left',),

			'options' => array('title' => LAN_OPTIONS, 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'right last', 'class' => 'right last', 'forced' => '1',),
		);

		protected $fieldpref = array('item_active', 'item_code', 'item_name', 'item_sef', 'item_cat', 'item_price', 'item_inventory');

		protected $categories     = array();
		protected $categoriesTree = array();

		protected $itemVarsType = array();
		// optional
		public function init()
		{

			if($this->getAction() != 'list' && $this->getAction() != 'grid')
			{
				$this->fields['item_preview']['type'] = null;
			}

			$this->perPage = e107::pref('vstore', 'admin_items_perpage', 10);

			$this->itemVarsType = array();
			if($data = e107::getDb()->retrieve('SELECT item_var_id, item_var_name, item_var_compulsory FROM #vstore_items_vars ORDER BY item_var_name', true))
			{
				$this->fields['item_vars']['writeParms'] = array();
				foreach($data as $k => $v)
				{
					$key = $v['item_var_id'];
					if ($v['item_var_compulsory'] == 0)
					{
						$this->fields['item_vars_nt']['writeParms'][$key] = $v['item_var_name'];
					}
					elseif ($v['item_var_compulsory'] == 1)
					{
						$this->fields['item_vars']['writeParms'][$key] = $v['item_var_name'];
					}

					$this->itemVarsType[$v['item_var_compulsory']][] = $key;
				}
			}
			//	print_a($_POST);


			$data = e107::getDb()->retrieve('SELECT cat_id, cat_name, cat_parent FROM #vstore_cat ORDER BY cat_order', true);
			$parent = array();


			foreach($data as $k => $row)
			{
				$id = $row['cat_id'];
				$parent[$id] = $row['cat_name'];
				$pid = (int) $row['cat_parent'];
				$name = varset($parent[$pid]);
				$this->categories[$id] = $row['cat_name'];
				$this->categoriesTree[$name][$id] = $row['cat_name'];
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

			// Initialize inventory
			$new_data['item_vars_inventory'] = '';

			// Make sure, only 2 tracking item vars are used (first 2)
			if(isset($new_data['item_vars'])){
				if(count($new_data['item_vars']) > 2){
					// Only 2 vars allowed
					$new_data['item_vars'] = array_slice($new_data['item_vars'], 0, 2);
				}
			}

			// Make sure max. 6 non tracking vars are used (first 6)
			if(isset($new_data['item_vars_nt'])){
				if(count($new_data['item_vars_nt']) > 6){
					// Only 6 vars allowed
					$new_data['item_vars_nt'] = array_slice($new_data['item_vars_nt'], 0, 6);
				}

				// Merge with tracking vars
				if (isset($new_data['item_vars'])){
					$new_data['item_vars'] = array_merge($new_data['item_vars'], $new_data['item_vars_nt']);
				}
				else{
					$new_data['item_vars'] = $new_data['item_vars_nt'];
				}
			}

			// Implode array to an comma-separated list
			if (isset($new_data['item_vars'])){
				$new_data['item_vars'] = array_unique($new_data['item_vars']);
				$new_data['item_vars'] = implode(',', $new_data['item_vars']);
			}

			return $new_data;
		}

		public function afterCreate($new_data, $old_data, $id)
		{
			// do something
		}

		public function beforeUpdate($new_data, $old_data, $id)
		{
			if(isset($new_data['item_inventory'])){
				$new_data['item_inventory'] = intval($new_data['item_inventory']);
				$new_data['item_inventory'] = $new_data['item_inventory'] < 0 ? -1 : $new_data['item_inventory'];
			}

			$oldVars = $this->filterItemVarsByType($old_data['item_vars'], 1, true);
			$oldVarsNt = $this->filterItemVarsByType($old_data['item_vars_nt'], 0, true);
			if(isset($new_data['item_vars'])){
				if($new_data['item_vars'] !== $oldVars){
					if($new_data['item_vars'] == ''){
						$new_data['item_vars_inventory'] = '';
					}
					else{
						if(count($new_data['item_vars']) > 2){
							// Only 2 vars allowed
							$new_data['item_vars'] = array_slice($new_data['item_vars'], 0, 2);
						}
						// Item vars have changed
						// Initialize inventory
						$new_data['item_vars_inventory'] = '';

					}
				} else {
					$inventory = 0;
					foreach ($new_data['item_vars_inventory'] as $key => $value) {
						if (count($new_data['item_vars']) == 1) {
							$inventory += $value;
							if ($value < 0) {
								$inventory = -1;
								break;
							}
						} elseif (count($new_data['item_vars']) == 2) {
							foreach ($value as $k => $v) {
								$inventory += $v;
								if ($v < 0) {
									$inventory = -1;
									break;
								}
							}
						}
						if ($inventory < 0) {
							break;
						}
					}
					$new_data['item_inventory'] = $inventory;
				}
			}else{
				$new_data['item_vars'] = array();
			}

			if(isset($new_data['item_vars_nt'])){
				if(count($new_data['item_vars_nt']) > 6){
					// Only 6 vars allowed
					$new_data['item_vars_nt'] = array_slice($new_data['item_vars_nt'], 0, 6);
				}

				if (isset($new_data['item_vars'])){
					$new_data['item_vars'] = array_merge($new_data['item_vars'], $new_data['item_vars_nt']);
				}
				elseif (!empty($oldVars)){
					$new_data['item_vars'] = array_merge($oldVars, $new_data['item_vars_nt']);
				}
				else{
					$new_data['item_vars'] = $new_data['item_vars_nt'];
				}
			}
			elseif (count($oldVarsNt) > 0){
				if (isset($new_data['item_vars'])){
					$new_data['item_vars'] = array_merge($new_data['item_vars'], $oldVarsNt);
				}
				elseif (!empty($oldVars)){
					$new_data['item_vars'] = array_merge($oldVars, $oldVarsNt);
				}
				else{
					$new_data['item_vars'] = $oldVarsNt;
				}
			}

			// Implode array to an comma-separated list
			if (isset($new_data['item_vars'])){
				$new_data['item_vars'] = array_unique($new_data['item_vars']);
				$new_data['item_vars'] = implode(',', $new_data['item_vars']);
			}

			if(isset($new_data['item_tax_class']) && !vartrue($new_data['item_tax_class'])){
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

		/**
		 * Filter the given string/array $curVal by $type (0 =non-tracking; 1=tracking)
		 *
		 * @param string/array $curVal  value to filter against §itemVarsType
		 * @param int  $type    (0 =non-tracking; 1=tracking)
		 * @param bool $asArray true return array, otherwise comma-separated string
		 * @return array|string
		 */
		public function filterItemVarsByType($curVal, $type=0, $asArray=false)
		{
			$curArr = array_filter(is_array($curVal) ? $curVal : explode(',', $curVal));
			if (empty($curVal)) {
				return $asArray ? array() : '';
			}

			if (!isset($this->itemVarsType[$type])) {
				return $asArray ? array() : '';
			}

			$result = array();
			foreach($this->itemVarsType[$type] as $item){
				if (in_array($item, $curArr)){
					$result[] = $item;
				}
			}

			return $asArray ? $result : implode(',', $result);
		}

	}


	class vstore_items_form_ui extends e_admin_form_ui
	{

		function item_preview($curVal, $mode, $parm)
		{

			$tp = e107::getParser();

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

					$inventory = $this->getController()->getFieldVar('item_vars_inventory');
					if (!empty($inventory))
					{
						return 'Based on variations';
					}
					return $curVal;
					break;

				case 'write': // Edit Page
					$inventory = $this->getController()->getFieldVar('item_vars_inventory');
					$icon = e107::getParser()->toGlyph('fa-info-circle');
					if (empty($inventory)) {
						$text = $this->number('item_inventory', varset($curVal, -1), null, array('class'=>'pull-left', 'decimals' => 0, 'min' => -1, 'readonly' => !empty($inventory))); // to allow also negative values (<0 = Item will not run out of stock)
						$text .= ' <span style="display:inline-block;padding:6px" title="In case of any Product Variations selected, this setting will ignored! You have to fill out the Variations Inventory instead!">'.$icon.'</span>';
					} else {
						$text = $this->hidden('item_inventory', varset($curVal, -1));
						$text .= ' <span class="help">The inventory is controlled by product variations inventory!</span>';
					}

					return $text;
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
						$value = varset($curVal[$nameX], -1);
						$text .= sprintf('<td>%s</td>', $this->number('item_vars_inventory[' . $nameX . ']', $value, null, array('class'=>'pull-left', 'decimals' => 0, 'min' => -1))); // to allow also negative values (<0 = Item will not run out of stock)
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
							$value = varset($curVal[$nameX][$nameY], -1);
							$text .= sprintf('<td>%s</td>', $this->number('item_vars_inventory[' . $nameX . '][' . $nameY . ']', $value, null, array('class'=>'pull-left', 'decimals' => 0, 'min' => -1)));
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

			$curVal = $this->getController()->filterItemVarsByType($curVal, 1, false);
			switch($mode)
			{
				case 'read': // List Page
					$values = e107::getDb()->retrieve('vstore_items_vars', 'GROUP_CONCAT(item_var_name)', 'item_var_compulsory = 1 AND FIND_IN_SET(item_var_id, "0,'.$curVal.'") ORDER BY item_var_name', false);
					return $values ? str_replace(',', ', ', $values) : LAN_NONE;
					break;

				case 'write': // Edit Page
					$opt_array = array();
					if($data = e107::getDb()->retrieve('SELECT item_var_id,item_var_name FROM #vstore_items_vars WHERE item_var_compulsory = 1 ORDER BY item_var_name', true))
					{
						foreach($data as $k => $v)
						{
							$key = $v['item_var_id'];
							$opt_array[$key] = $v['item_var_name'];
						}
					}

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

		function item_vars_nt($curVal, $mode)
		{

			if (empty($curVal)) {
				$curVal = $this->getController()->getFieldVar('item_vars');
			}
			$curVal = $this->getController()->filterItemVarsByType($curVal, 0, false);
			switch($mode)
			{
				case 'read': // List Page
					$values = e107::getDb()->retrieve('vstore_items_vars', 'GROUP_CONCAT(item_var_name)', 'item_var_compulsory = 0 AND FIND_IN_SET(item_var_id, "0,'.$curVal.'") ORDER BY item_var_name', false);
					return $values ? str_replace(',', ', ', $values) : LAN_NONE;
					break;

				case 'write': // Edit Page
					$opt_array = array();
					if($data = e107::getDb()->retrieve('SELECT item_var_id,item_var_name FROM #vstore_items_vars WHERE item_var_compulsory = 0 ORDER BY item_var_name', true))
					{
						foreach($data as $k => $v)
						{
							$key = $v['item_var_id'];
							$opt_array[$key] = $v['item_var_name'];
						}
					}

					$text = $this->select('item_vars_nt', $opt_array, $curVal, array('multiple' => 1));
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
			$currency = e107::pref('vstore', 'currency', 'EUR');
			switch($mode)
			{
				case 'read': // List Page
					return $curVal . ' ' . $currency;
					break;

				case 'write': // Edit Page
					//return $this->text('item_price', $curVal);
					return $this->number('item_price', $curVal, 10, array('decimals' => 2)) . ' ' . $currency;
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


