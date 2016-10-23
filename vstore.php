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

$vstore = e107::getSingleton('vstore',e_PLUGIN.'vstore/vstore.class.php');
$vstore->init();
require_once(HEADERF);
//$t = e107::library('load', 'jquery.easyZoom');

$vstore->render();
require_once(FOOTERF);
exit;



?>