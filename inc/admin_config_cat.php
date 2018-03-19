<?php
/**
 * Adminarea module categories
 */
 class vstore_cat_ui extends e_admin_ui
{
			
		protected $pluginTitle		= 'Vstore';
		protected $pluginName		= 'vstore';
		protected $table			= 'vstore_cat';
		protected $pid				= 'cat_id';
		protected $perPage			= 10; 
		protected $batchDelete		= true;
		protected $batchCopy		= true;
		protected $batchExport		= true;

		protected $sortField		= 'cat_order';
		protected $sortParent       = 'cat_parent';
		protected $treePrefix       = 'cat_name';


	//	protected $tabs			= array('Tabl 1','Tab 2'); // Use 'tab'=>0  OR 'tab'=>1 in the $fields below to enable. 

	
		protected $fields 		= array (  
			'checkboxes' 		=>   array ( 'title' => '', 'type' => null, 'data' => null, 'width' => '5%', 'thclass' => 'center', 'forced' => '1', 'class' => 'center', 'toggle' => 'e-multiselect',  ),
		  	'cat_id' 			=>   array ( 'title' => LAN_ID, 'data' => 'int', 'width' => '5%', 'help' => '', 'readParms' => 'url=category&target=dialog', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  	'cat_active' 		=>   array ( 'title' => LAN_ACTIVE, 'type'=>'boolean', 'data' => 'int', 'inline'=>true, 'width' => '5%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  	'cat_name' 			=>   array ( 'title' => LAN_TITLE, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => array('size'=>'xxlarge'), 'class' => 'left', 'thclass' => 'left',  ),
		    'cat_description' 	=>   array ( 'title' => LAN_DESCRIPTION, 'type' => 'textarea', 'data' => 'str', 'width' => '40%', 'help' => '', 'readParms' => '', 'writeParms' => array('maxlength' => 220, 'size'=>'xxlarge'), 'class' => 'left', 'thclass' => 'left',  ),
		  	'cat_sef' 			=>   array ( 'title' => LAN_SEFURL, 'type' => 'text', 'data' => 'str', 'width' => 'auto', 'batch'=>true, 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => array('size'=>'xxlarge','sef'=>'cat_name'), 'class' => 'left', 'thclass' => 'left',  ),
			'cat_parent'        =>   array ( 'title' => "Parent", 'type'=>'dropdown', 'data'=>'int', 'inline'=>true,  'width'=>'auto'),
		  	'cat_image' 		=>   array ( 'title' => LAN_IMAGE, 'type' => 'image', 'data' => 'str', 'width' => '40%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),	
		 	'cat_info' 			=>   array ( 'title' => "Details", 'type' => 'bbarea', 'data' => 'str', 'width' => '40%', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  	'cat_class' 		=>   array ( 'title' => LAN_USERCLASS, 'type' => 'userclass', 'data' => 'str', 'width' => 'auto', 'batch' => true, 'filter' => true, 'inline' => true, 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  	'cat_order' 		=>   array ( 'title' => LAN_ORDER, 'type' => 'text', 'data' => 'int', 'width' => 'auto', 'help' => '', 'readParms' => '', 'writeParms' => '', 'class' => 'left', 'thclass' => 'left',  ),
		  	'options' 			=>   array ( 'title' => 'Options', 'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced' => '1', 'sort'=>1  ),
		);		
		
		protected $fieldpref = array('cat_id', 'cat_active', 'cat_name', 'cat_sef', 'cat_class');



		public function beforeCreate($new_data,$old_data)
		{
			if(!empty($new_data['cat_name']) && isset($new_data['cat_sef']) && empty($new_data['cat_sef']))
			{
				$new_data['cat_sef'] = eHelper::title2sef($new_data['cat_name'], 'dashl');
			}
			
			if (isset($new_data['cat_sef']))
			{
				$new_data['cat_sef'] = $this->fix_sef($new_data['cat_sef'], $this->table, 'cat_sef');
			}

			return $new_data;
		}

		public function afterCreate($new_data, $old_data, $id)
		{
			// do something

		}

		public function beforeUpdate($new_data, $old_data, $id)
		{
			if(!empty($new_data['cat_name']) && isset($new_data['cat_sef']) && empty($new_data['cat_sef']))
			{
				$new_data['cat_sef'] = eHelper::title2sef($new_data['cat_name'], 'dashl');
			}

			if (isset($new_data['cat_sef']))
			{
				$new_data['cat_sef'] = $this->fix_sef($new_data['cat_sef'], $this->table, 'cat_sef', $this->pid, $old_data[$this->pid]);
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

		/**
		 * Check if a given sef string already exists and fix it by
		 * searching for a free sef string by adding a incrementation number at the end
		 * 
		 * @example if "cat1" exists, it wil check for "cat1-2", "cat1-3" and so on until there is one that isn't used
		 *
		 * @param string $sef The sef string to check (e.g. cat1)
		 * @param string $table The table to search in (e.g. vstore_cat)
		 * @param string $sef_field The sef table field name (e.g. cat_sef)
		 * @param string $id_field (optional) The id field of the table (e.g. cat_id)
		 * @param variant $id_value (optional) The id value of an existing record
		 * @param integer $try (Only used internally) defines which try it is, in case the tested sef was already in the table
		 * @return string a sef string that isn't used to this moment.
		 */
		private function fix_sef($sef, $table, $sef_field, $id_field=null, $id_value=null, $try=0)
		{
			$result = e107::getParser()->toDB($sef);
	
			if ($try > 0)
			{
				$result .= '-' . ($try + 1);
			}
	
			$where = "{$sef_field}='{$result}'";
			if (!empty($id_field) && !empty($id_value))
			{
				$where .= " AND {$id_field}!='{$id_value}'";
			}
				
			$count = (int) e107::getDb()->count($table, '(*)', $where);
	
			if ($count > 0)
			{
				$result = $this->fix_sef($sef, $table, $sef_field, $id_field, $id_value, ++$try);
			}
	
			return $result;
				
		}
			

				// Correct bad ordering based on parent/child relationship.
		private function checkOrder()
		{
			$sql = e107::getDb();
		//	$sql2 = e107::getDb('sql3');
			$count = $sql->select('vstore_cat', 'cat_id', 'cat_order = 0');

			if($count > 1)
			{
				$data = $sql->retrieve("SELECT cat_id,cat_name,cat_parent,cat_order FROM `#vstore_cat` ORDER BY COALESCE(NULLIF(cat_parent,0), cat_id), cat_parent > 0, cat_order ",true);

				$c = 0;
				$parent = 1;
				foreach($data as $row)
				{


					if(empty($row['cat_parent']))
					{

						$c = $parent * 10;
						$parent++;
					}
					else
					{
						$c = $c+1;
					}
					
					$sql->update('vstore_cat', 'cat_order = '.intval($c).' WHERE cat_id = '.intval($row['cat_id']).' LIMIT 1');
				}


			}


		}

		// optional
		public function init()
		{
			$this->perPage = e107::pref('vstore','admin_categories_perpage',10);

		//	$this->checkOrder();

			/*$data = e107::getDb()->retrieve('vstore_cat','cat_id,cat_name', "cat_parent = 0", true);

			$this->fields['cat_parent']['writeParms']['optArray'] = array(0=>'(Root)');

			foreach($data as $v)
			{
				$key = $v['cat_id'];
				$this->fields['cat_parent']['writeParms']['optArray'][$key] = $v['cat_name'];
			}*/

			$this->setVstoreCategoryTree();

		}



	private function setVstoreCategoryTree()
	{


		$sql = e107::getDb();
		$qry = $this->getParentChildQry(true);
		$sql->gen($qry);

		$this->fields['cat_parent']['writeParms']['optArray'] = array(0=>'(Root)');

		while($row = $sql->fetch())
		{
			$num = $row['_depth'] - 1;
			$id = $row['cat_id'];
			$this->fields['cat_parent']['writeParms']['optArray'][$id] = str_repeat("&nbsp;&nbsp;",$num).$row['cat_name'];
		}

		if($this->getAction() === 'edit') // make sure parent is not the same as ID.
		{
			$r = $this->getId();
			unset($this->fields['cat_parent']['writeParms']['optArray'][$r]);
		}

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
				


class vstore_cat_form_ui extends e_admin_form_ui
{

	/*
		function cat_name($curVal,$mode,$parm)
		{

			$frm = e107::getForm();

			if($mode == 'read')
			{
				return $curVal;
			}

			if($mode == 'write')
			{
				return $frm->text('cat_name',$curVal,255,'size=xxlarge');
			}

			if($mode == 'filter')
			{
				return false;
			}
			if($mode == 'batch')
			{
				return false;
			}

			if($mode == 'inline')
			{
				$parent 	= $this->getController()->getFieldVar('cat_parent');

				$ret = array('inlineType'=>'text');

				if(empty($parent))
				{

				}
				else
				{
					$ret['inlineParms'] = array('pre'=>'<img src="'.e_IMAGE_ABS.'generic/branchbottom.gif" class="level-1 icon" alt="" />');
				}

				return $ret;
			}
		}*/
}		
?>