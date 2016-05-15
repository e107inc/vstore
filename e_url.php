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
	function config() 
	{
		$config = array();

		$config['cancel'] = array(

			'regex'			=> '^vstore/checkout/cancel/?$',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?mode=cancel',
			'sef'			=>  'vstore/checkout/cancel/',
		);


		$config['return'] = array(

			'regex'			=> '^vstore/checkout/return/?$',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?mode=return',
			'sef'			=>  'vstore/checkout/return/',
		);


		$config['checkout'] = array(

			'regex'			=> '^vstore/checkout/?$',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?mode=checkout',
			'sef'			=>  'vstore/checkout/',
		);


		$config['cart'] = array(
			
			'regex'			=> '^vstore/cart/?$',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?mode=cart',
			'sef'			=>  'vstore/cart/',
		);


		$config['addtocart'] = array(

			'regex'			=> '^vstore/cart/add/([\d]*)$',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?mode=cart&add=$1',
			'sef'			=>  'vstore/cart/add/{item_id}',
		);

		$config['index'] = array(
			'regex'			=> '^vstore\/?([\?].*)?\/?$',
			'sef'			=> 'vstore/',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php$1',

		);


		$config['category'] = array(
			'regex'			=> '^vstore/([^\/]*)/?$',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?catsef=$1',
			'sef'			=> 'vstore/{cat_sef}'
		);

		$config['product'] = array(
			'regex'			=> '^vstore/([^\/]*)/([\d]*)/(.*)',
			'sef'			=> 'vstore/{cat_sef}/{item_id}/{item_sef}',
			'redirect'		=> '{e_PLUGIN}vstore/vstore.php?item=$2',

		);
		

		
		return $config;
	}
	
}