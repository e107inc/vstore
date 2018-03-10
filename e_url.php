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

		$config['download'] = array(
			'regex'			=> '^{alias}\/dl/([\d]*)$',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?download=$1&',
			'sef'			=> '{alias}/dl/{item_id}'
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

		
		$config['confirm'] = array(
			'regex'			=> '^{alias}/confirm/?$',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?mode=confirm',
			'sef'			=>  '{alias}/confirm/',
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
			'sef'			=> '{alias}/'
		);

		$config['product'] = array(
			'regex'			=> '^{alias}/([^\/]*)/([\d]*)/(.*)',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?item=$2',
			'sef'			=> '{alias}/{cat_sef}/{item_id}/{item_sef}'
		);

		$config['subcategory'] = array(
			'regex'			=> '^{alias}\/([^\/]*)\/([^\/\?]*)\/$',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?catsef=$2',
			'sef'			=> '{alias}/{cat_sef}/{subcat_sef}/'
		);

		$config['category'] = array(
			'regex'			=> '^{alias}\/([^\/\?\=]*)\/?\??',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?catsef=$1&',
			'sef'			=> '{alias}/{cat_sef}/'
		);

		return $config;
	}
	
}
