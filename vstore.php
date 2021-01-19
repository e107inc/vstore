<?php

if (!defined('e107_INIT'))
{
	require_once("../../class2.php");
}

e107::js('vstore', 'js/jquery.zoom.min.js');

/// Moved to e_header.php issue #146 ///
// e107::js('vstore', 'js/vstore.js');
// e107::lan('vstore', false, true); // e107_plugins/vstore/languages/English_front.php

// $vstore_prefs = e107::pref('vstore');

// e107::js('settings', array('vstore' => array(
//         'url' => e107::url('vstore', 'index'),
// 		'cart' =>  array(
// 			'url' => e107::url('vstore', 'cart'),
// 			'addtocart' => LAN_VSTORE_001, // 'Add to cart',
// 			'outofstock' => empty($vstore_prefs['caption_outofstock'][e_LANGUAGE])
// 				? 'Out of stock'
// 				: $vstore_prefs['caption_outofstock'][e_LANGUAGE],
// 			'available' => 'In stock',
// 		),
// 		'ImageZoom' => array('url'=>'')
// 	)
// ));


// if (!empty($vstore_prefs['custom_css']))
// {
// 	// Add any custom css to the page
// 	e107::css('inline', "
// 	/* vstore custom css */
// 	" . $vstore_prefs['custom_css']);
// }

/** @var vstore $vstore */
$vstore = e107::getSingleton('vstore', e_PLUGIN.'vstore/vstore.class.php');
$vstore->init();
require_once(HEADERF);

$vstore->render();

require_once(FOOTERF);
exit;
