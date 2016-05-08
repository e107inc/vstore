<?php

if (!defined('e107_INIT'))
{
	require_once("../../class2.php");
}



$vstore = e107::getSingleton('vstore',e_PLUGIN.'vstore/vstore.class.php');
$vstore->init();
require_once(HEADERF);
$vstore->render();
require_once(FOOTERF);
exit;



?>