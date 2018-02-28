<?php


require_once(e_PLUGIN.'vstore/vstore.class.php');
$vst = new vstore;

e107::js('settings', array('vstore' => array('cart' => array('url' => e107::url('vstore', 'cart').'cart.php'))));


$category = 1;  //TODO e_menu config.
$caption = "Products";

$text = $vst->productList(1, true, 'menu');


$ns = e107::getRender();

$ns->tablerender($caption, $text);

