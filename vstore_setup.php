<?php
/*
* e107 website system
*
* Copyright (C) 2008-2013 e107 Inc (e107.org)
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
*
* Custom install/uninstall/update routines for blank plugin
**
*/

class vstore_setup
{
	
 	function install_pre($var)
	{
		// print_a($var);
		// echo "custom install 'pre' function<br /><br />";
	}

	/**
	 * For inserting default database content during install after table has been created by the blank_sql.php file. 
	 */
	function install_post($var)
	{
		// $sql = e107::getDb();
		// $mes = e107::getMessage();

		// $dump = "
		// INSERT INTO `#vstore_items` (`item_id`, `item_code`, `item_name`, `item_keywords`, `item_desc`, `item_cat`, `item_pic`, `item_files`, `item_price`, `item_shipping`, `item_details`, `item_reviews`, `item_order`, `item_inventory`, `item_link`, `item_download`, `item_related`) VALUES
		// (1, 'ITEM1', 'Product One', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris libero magna, ornare sit amet nibh vitae, feugiat luctus nulla. Pellentesque placerat vitae felis et dignissim. Donec eget euismod lacus. Nulla est odio, iaculis ac ligula ultricies, eui', 1, 'array (\n  0 => \n  array (\n    \'path\' => \'{e_MEDIA_IMAGE}2016-04/logo_alone_1050_.jpg\',\n  ),\n  1 => \n  array (\n    \'path\' => \'\',\n  ),\n  2 => \n  array (\n    \'path\' => \'\',\n  ),\n  3 => \n  array (\n    \'path\' => \'\',\n  ),\n  4 => \n  array (\n    \'path\' => \'\',\n  ),\n)', '', '3.00', '1.00', '', '', 1, 0, '', 0, 'array (\n  \'caption\' => \'\',\n  \'src\' => \'\',\n)'),
		// (2, 'ITEM2', 'Product Two', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris libero magna, ornare sit amet nibh vitae, feugiat luctus nulla. Pellentesque placerat Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris libero magna, ornare sit amet nibh v', 1, 'array (\n  0 => \n  array (\n    \'path\' => \'{e_MEDIA_IMAGE}2016-04/logo_alone_1050_.jpg\',\n  ),\n  1 => \n  array (\n    \'path\' => \'92sQjeFKL6o.youtube\',\n  ),\n  2 => \n  array (\n    \'path\' => \'\',\n  ),\n  3 => \n  array (\n    \'path\' => \'\',\n  ),\n  4 => \n  array (\n    \'path\' => \'\',\n  ),\n)', 'array (\n  0 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'e107_banners.zip\',\n    \'id\' => \'171\',\n  ),\n  1 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'\',\n    \'id\' => \'\',\n  ),\n  2 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'\',\n    \'id\' => \'\',\n  ),\n  3 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'\',\n    \'id\' => \'\',\n  ),\n  4 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'\',\n    \'id\' => \'\',\n  ),\n)', '25.00', '2.00', '[html]<p>Some details go here. </p>\r\n<p>Features</p>\r\n<ul>\n<li>feature 1</li>\r\n<li>feature 2</li>\r\n<li>feature 3</li>\r\n<li>feature 4</li>\r\n</ul>[/html]', 'Here&#039;s a review of sorts. ', 1, 6, '', NULL, 'array (\n  \'caption\' => \'\',\n  \'src\' => \'\',\n)'),
		// (3, 'ITEM3', 'Product Three', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris libero magna, ornare sit amet nibh vitae, feugiat luctus nulla. Pellentesque placerat Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris libero magna, ornare sit amet nibh v', 1, 'array (\n  0 => \n  array (\n    \'path\' => \'{e_MEDIA_IMAGE}2016-04/logo_alone_1050_.jpg\',\n  ),\n  1 => \n  array (\n    \'path\' => \'\',\n  ),\n  2 => \n  array (\n    \'path\' => \'\',\n  ),\n  3 => \n  array (\n    \'path\' => \'\',\n  ),\n  4 => \n  array (\n    \'path\' => \'\',\n  ),\n)', 'array (\n  0 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'e107_banners.zip\',\n    \'id\' => \'171\',\n  ),\n  1 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'\',\n    \'id\' => \'\',\n  ),\n  2 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'\',\n    \'id\' => \'\',\n  ),\n  3 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'\',\n    \'id\' => \'\',\n  ),\n  4 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'\',\n    \'id\' => \'\',\n  ),\n)', '3.00', '2.00', '[html]<p>Some details go here.</p>[/html]', 'Here&#039;s a review of sorts. ', 1, 6, '', NULL, 'array (\n  \'caption\' => \'\',\n  \'src\' => \'\',\n)');

		// INSERT INTO `#vstore_cat` (`cat_id`, `cat_name`, `cat_description`, `cat_sef`, `cat_image`, `cat_info`, `cat_class`, `cat_order`) VALUES
		// (1, 'General', 'Category Description here', 'general', '', '[html]<p>Some category details go here. </p>[/html]', '0', 0);
		// ";
		
		// if($sql->gen($dump))
		// {
		// 	//$mes->add("Custom - Install Message.", E_MESSAGE_SUCCESS);
		// }
		// else
		// {
		// 	$mes->addError("Failed to add default table data.");
		// }

	}

	
	function uninstall_options()
	{
	
	}

	function uninstall_post($var)
	{
		// print_a($var);
	}

	
	/*
	 * Call During Upgrade Check. May be used to check for existance of tables etc and if not found return TRUE to call for an upgrade. 
	 * 
	 */
	function upgrade_required()
	{
		// Check if vstore_customer table exists
		if(!e107::getDb()->isTable('vstore_customer'))
		{
			return true;
		}

		// Check if vstore_orders contains the order_shipping field
		if(!e107::getDb()->field('vstore_orders', 'order_shipping'))
		{
			return true;	 // true to trigger an upgrade alert, and false to not. 	
		}

		// order_ship_* columns have been replaced ybe the order_billing and order_shipping columns
		// Move all data from the old order_ship_* columns into the new order_billing, order_shipping collumns
		$sql = e107::getDb('sql1');
		$sql2 = e107::getDb('sql2');
		if($sql->field('vstore_orders','order_ship_firstname'))
		{
			include_once 'vstore.class.php';
			$vstore = new vstore();
			if ($sql->select('vstore_orders', 'order_id, order_ship_firstname, order_ship_lastname, order_ship_company, order_ship_email, order_ship_phone, order_ship_address,order_ship_city, order_ship_state, order_ship_zip, order_ship_country, order_ship_notes', 'order_ship_firstname != "" AND order_shipping = ""'))
			{
				while($row = $sql->fetch())
				{
					$data = array();
					$id = $row['order_id'];
					if ($id)
					{
						unset($row['order_id']);
						foreach($row as $k => $v)
						{
							$data['order_billing'][str_replace('order_ship_', '', $k)] = $v;
							$data['order_shipping'][str_replace('order_ship_', '', $k)] = $v;
						}

						if ($data && count($data))
						{
							$data['order_use_shipping'] = 1;
							$data['order_refcode'] = $vstore->getOrderRef($id, $data['order_billing']['firstname'], $data['order_billing']['lastname']);
							
							unset($data['order_billing']['notes']);

							$data['order_billing'] = e107::serialize($data['order_billing'], 'json');
							$data['order_shipping'] = e107::serialize($data['order_shipping'], 'json');
							$sql2->update('vstore_orders', array('data' => $data, 'WHERE' => 'order_id='.$id));
						}
					}
				}
				return true;
			}
			else
			{
				// Drop fields that are no longer needed after the data has been consolidated
				$dropFields = array('order_ship_firstname', 'order_ship_lastname', 'order_ship_company', 'order_ship_email', 'order_ship_phone', 'order_ship_address', 'order_ship_city', 'order_ship_state', 'order_ship_zip', 'order_ship_country', 'order_ship_notes');

				foreach($dropFields as $field)
				{
					if ($sql->field('vstore_orders', $field))
					{
						// remove old fields only after all old data has been moved to the new columns
						$sql->gen("ALTER TABLE `#vstore_orders` DROP `{$field}`");
					}
				}
				return false;
			}
		}

		// Add missing invoice_nr to vstore_orders
		if($sql->field('vstore_orders','order_invoice_nr'))
		{
			$sql = e107::getDb('sql1');
			$sql2 = e107::getDb('sql2');
			if ($sql->select('vstore_orders','order_id', 'ISNULL(order_invoice_nr)=true ORDER BY order_id'))
			{
				// check pref
				$pref = e107::pref('vstore', 'invoice_next_nr', 1);
				if (intval($pref) < 1) $pref = 1;
				$pref--;

				// get next order_invoice_nr
				$max = $sql2->retrieve('vstore_orders', 'MAX(order_invoice_nr) AS max');
				$max = (is_array($max) ? intval($max['max']) : 0);
				$max = max($pref, $max);

				// Update order_invoice_nr
				while($row = $sql->fetch())
				{
					$id = $row['order_id'];
					$max++;
					$sql2->update('vstore_orders', array('data' => array('order_invoice_nr' => $max), 'WHERE' => 'order_id='.$id));
				}
				return true;
			}
		}
		return false;
	}	

	function upgrade_post($var)
	{
		// $sql = e107::getDb();
	}
	
}
?>