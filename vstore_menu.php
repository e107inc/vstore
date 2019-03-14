<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2016 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * e107 Plugin - Vstore Menu
 *
*/

if (!defined('e107_INIT')) { exit; }


require_once(e_PLUGIN.'vstore/vstore.class.php');
$vst = new vstore;

// Loads e_PLUGIN."vstore/languages/English/English_front.php (if English is the current language)
e107::lan('vstore', false, true);

e107::js('vstore','js/vstore.js');

$vstore_prefs = e107::pref('vstore');

e107::js('settings', array('vstore' => 
	array(
		'cart' =>  array(
			'url' => e107::url('vstore', 'cart').'cart.php', 
			'addtocart' => 'Add to cart',
			'outofstock' => empty($vstore_prefs['caption_outofstock'][e_LANGUAGE]) ? 'Out of stock' : $vstore_prefs['caption_outofstock'][e_LANGUAGE],
			'available' => 'In stock',
		), 
		'ImageZoom' => array('url'=>'')
	)
));

if (!empty($vstore_prefs['custom_css']))
{
	// Add any custom css to the page
	e107::css('inline', "
	/* vstore custom css */
	" . $vstore_prefs['custom_css']);
}

$category = vartrue($vstore_prefs['menu_cat'], 1);
$num_items= vartrue($vstore_prefs['menu_item_count'], 2);

$caption = "Products";

$text = $vst->productList($category, false, 'menu', $num_items);


e107::getRender()->tablerender($caption, $text);
