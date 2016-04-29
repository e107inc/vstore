<?php

if (!defined('e107_INIT'))
{
	require_once("../../class2.php");
}

require_once(e_PLUGIN.'vstore/vstore.class.php');


require_once(HEADERF);
$vstore = new vstore;
$vstore->init();
require_once(FOOTERF);
exit;



?>