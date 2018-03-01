<?php

if (!defined('e107_INIT'))
{
	require_once("../../class2.php");
}

//e107::library('load', 'jquery.easyZoom');
//e107::js('vstore', 'js/easyzoom.js');
//e107::css('vstore', 'css/easyzoom.css');

e107::js('vstore','js/jquery.zoom.min.js');
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

$vstore = e107::getSingleton('vstore',e_PLUGIN.'vstore/vstore.class.php');
$vstore->init();
require_once(HEADERF);
//$t = e107::library('load', 'jquery.easyZoom');

$vstore->render();
require_once(FOOTERF);
exit;



?>