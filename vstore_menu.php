<?php


require_once(e_PLUGIN.'vstore/vstore.class.php');
$vst = new vstore;


$category = 1;  //TODO e_menu config.
$caption = "Products";

$text = $vst->productList(1, true, 'menu');


$ns = e107::getRender();

$ns->tablerender($caption, $text);

