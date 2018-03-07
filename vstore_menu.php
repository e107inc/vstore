<?php


require_once(e_PLUGIN.'vstore/vstore.class.php');
$vst = new vstore;

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

$category = 1;  //TODO e_menu config.
$caption = "Products";

$text = $vst->productList(1, true, 'menu');


$ns = e107::getRender();

$ns->tablerender($caption, $text);

