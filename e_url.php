<?php
/*
 * e107 Bootstrap CMS
 *
 * Copyright (C) 2008-2014 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)

 */
 
if (!defined('e107_INIT')) { exit; }

// v2.x Standard  - Simple mod-rewrite module. 

class vstore_url // plugin-folder + '_url' 
{

	public $alias = 'vstore';

	function config($pofile=null)
	{
		$config = array();

		$config['dashboard_action'] = array(
			'regex'			=> '^{alias}\/my\/?([a-zA-Z]*)\/?([a-zA-Z]*)\/?([\d]*)\/?$',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?mode=dashboard&area=$1&action=$2&id=$3',
			'sef'			=> '{alias}/my/{dash}/{action}/{id}'
		);

		$config['dashboard'] = array(
			'regex'			=> '^{alias}\/my\/?([a-zA-Z]*)\/?\??',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?mode=dashboard&area=$1&',
			'sef'			=> '{alias}/my/{dash}'
		);

		$config['invoice'] = array(
			'regex'			=> '^{alias}\/invoice\/([\d]*)\/?$',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?invoice=$1&',
			'sef'			=> '{alias}/invoice/{order_invoice_nr}/'
		);

		$config['download'] = array(
			'regex'			=> '^{alias}\/request/([\d]*)$',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?download=$1&',
			'sef'			=> '{alias}/request/{item_id}'
		);
		
		$config['cancel'] = array(
			'regex'			=> '^vstore/checkout/cancel/?$',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?mode=cancel',
			'sef'			=>  'vstore/checkout/cancel/',
		);


		$config['return'] = array(
			'regex'			=> '^vstore/checkout/return/?\??(.*)$',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?mode=return&$1',
			'sef'			=>  'vstore/checkout/return/',
		);


		$config['checkout'] = array(
			'regex'			=> '^{alias}/checkout/?$',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?mode=checkout',
			'sef'			=>  '{alias}/checkout/',
		);


		$config['addtocart'] = array(
			'regex'			=> '^{alias}/cart/add/([\d]*)$',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?mode=cart&add=$1',
			'sef'			=>  '{alias}/cart/add/{item_id}',
		);

		$config['cart'] = array(
			'regex'			=> '^{alias}/cart/?\??(.*)$',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?mode=cart&$1',
			'sef'			=>  '{alias}/cart/',
		);

		$config['index'] = array(
			'regex'			=> '^{alias}\/?([\?].*)?\/?$',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php$1',
			'sef'			=> '{alias}/',
			'legacy'		=> '{e_PLUGIN}vstore/vstore.php',
		);

		$config['product'] = array(
			'regex'			=> '^{alias}/([^\/]*)/([\d]*)/(.*)',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?item=$2',
			'sef'			=> '{alias}/{cat_sef}/{item_id}/{item_sef}',
			'legacy'		=> '{e_PLUGIN}vstore/vstore.php?item={item_id}',
		);

		$config['subcategory'] = array(
			'regex'			=> '^{alias}\/([^\/]*)\/([^\/\?]*)\/$',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?catsef=$2',
			'sef'			=> '{alias}/{cat_sef}/{subcat_sef}/'
		);

		$config['category'] = array(
			'regex'			=> '^{alias}\/([^\/\?\=]*)\/?\??',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?catsef=$1&',
			'legacy'		=> '{e_PLUGIN}vstore/vstore.php?catsef={cat_sef}',
			'sef'			=> '{alias}/{cat_sef}/'
		);

		return $config;
	}
	
}
