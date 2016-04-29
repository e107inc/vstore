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

class _blank_setup
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
		$sql = e107::getDb();
		$mes = e107::getMessage();

		$dump = "
		INSERT INTO `#vstore_items` (`item_id`, `item_code`, `item_name`, `item_keywords`, `item_desc`, `item_cat`, `item_pic`, `item_files`, `item_price`, `item_shipping`, `item_details`, `item_reviews`, `item_order`, `item_inventory`, `item_link`, `item_download`, `item_related`) VALUES
		(1, 'ITEM1', 'Product One', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris libero magna, ornare sit amet nibh vitae, feugiat luctus nulla. Pellentesque placerat vitae felis et dignissim. Donec eget euismod lacus. Nulla est odio, iaculis ac ligula ultricies, eui', 1, 'array (\n  0 => \n  array (\n    \'path\' => \'{e_MEDIA_IMAGE}2016-04/logo_alone_1050_.jpg\',\n  ),\n  1 => \n  array (\n    \'path\' => \'\',\n  ),\n  2 => \n  array (\n    \'path\' => \'\',\n  ),\n  3 => \n  array (\n    \'path\' => \'\',\n  ),\n  4 => \n  array (\n    \'path\' => \'\',\n  ),\n)', '', '3.00', '1.00', '', '', 1, 0, '', 0, 'array (\n  \'caption\' => \'\',\n  \'src\' => \'\',\n)'),
		(2, 'ITEM2', 'Product Two', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris libero magna, ornare sit amet nibh vitae, feugiat luctus nulla. Pellentesque placerat Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris libero magna, ornare sit amet nibh v', 1, 'array (\n  0 => \n  array (\n    \'path\' => \'{e_MEDIA_IMAGE}2016-04/logo_alone_1050_.jpg\',\n  ),\n  1 => \n  array (\n    \'path\' => \'92sQjeFKL6o.youtube\',\n  ),\n  2 => \n  array (\n    \'path\' => \'\',\n  ),\n  3 => \n  array (\n    \'path\' => \'\',\n  ),\n  4 => \n  array (\n    \'path\' => \'\',\n  ),\n)', 'array (\n  0 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'e107_banners.zip\',\n    \'id\' => \'171\',\n  ),\n  1 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'\',\n    \'id\' => \'\',\n  ),\n  2 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'\',\n    \'id\' => \'\',\n  ),\n  3 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'\',\n    \'id\' => \'\',\n  ),\n  4 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'\',\n    \'id\' => \'\',\n  ),\n)', '25.00', '2.00', '[html]<p>Some details go here. </p>\r\n<p>Features</p>\r\n<ul>\n<li>feature 1</li>\r\n<li>feature 2</li>\r\n<li>feature 3</li>\r\n<li>feature 4</li>\r\n</ul>[/html]', 'Here&#039;s a review of sorts. ', 1, 6, '', NULL, 'array (\n  \'caption\' => \'\',\n  \'src\' => \'\',\n)'),
		(3, 'ITEM3', 'Product Three', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris libero magna, ornare sit amet nibh vitae, feugiat luctus nulla. Pellentesque placerat Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris libero magna, ornare sit amet nibh v', 1, 'array (\n  0 => \n  array (\n    \'path\' => \'{e_MEDIA_IMAGE}2016-04/logo_alone_1050_.jpg\',\n  ),\n  1 => \n  array (\n    \'path\' => \'\',\n  ),\n  2 => \n  array (\n    \'path\' => \'\',\n  ),\n  3 => \n  array (\n    \'path\' => \'\',\n  ),\n  4 => \n  array (\n    \'path\' => \'\',\n  ),\n)', 'array (\n  0 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'e107_banners.zip\',\n    \'id\' => \'171\',\n  ),\n  1 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'\',\n    \'id\' => \'\',\n  ),\n  2 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'\',\n    \'id\' => \'\',\n  ),\n  3 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'\',\n    \'id\' => \'\',\n  ),\n  4 => \n  array (\n    \'path\' => \'\',\n    \'name\' => \'\',\n    \'id\' => \'\',\n  ),\n)', '3.00', '2.00', '[html]<p>Some details go here.</p>[/html]', 'Here&#039;s a review of sorts. ', 1, 6, '', NULL, 'array (\n  \'caption\' => \'\',\n  \'src\' => \'\',\n)');

		INSERT INTO `#vstore_cat` (`cat_id`, `cat_name`, `cat_description`, `cat_sef`, `cat_image`, `cat_info`, `cat_class`, `cat_order`) VALUES
(1, 'General', 'Category Description here', 'general', '', '[html]<p>Some category details go here. </p>[/html]', '0', 0);
		";
		
		if($sql->gen($dump))
		{
			//$mes->add("Custom - Install Message.", E_MESSAGE_SUCCESS);
		}
		else
		{
			$mes->addError("Failed to add default table data.");
		}

	}
	
	function uninstall_options()
	{
	

	}
	

	function uninstall_post($var)
	{
		// print_a($var);
	}

	function upgrade_post($var)
	{
		// $sql = e107::getDb();
	}
	
}
?>