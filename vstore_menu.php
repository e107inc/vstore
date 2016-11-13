<?php


require_once(e_PLUGIN.'vstore/vstore.class.php');
$vst = new vstore;

e107::lan('vstore',false, true);

$category = 1;  //TODO e_menu config.
$caption = LAN_VSTORE_001;

$text = $vst->productList(1, true, 'menu');


$ns = e107::getRender();

$ns->tablerender($caption, $text);
