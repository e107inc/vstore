<?php
/*
* Copyright (c) e107 Inc e107.org, Licensed under GNU GPL (http://www.gnu.org/licenses/gpl.txt)
* $Id: e_shortcode.php 12438 2011-12-05 15:12:56Z secretr $
*
* Featurebox shortcode batch class - shortcodes available site-wide. ie. equivalent to multiple .sc files.
*/

if (!defined('e107_INIT')) { exit; }

class vstore_shortcodes extends e_shortcode
{
	var $vs = null;
	
	function __construct()
	{
		 require_once(e_PLUGIN.'vstore/vstore.class.php');
		$this->vs = new vstore;
		
		
	}

	function sc_vstore_items()
	{
		$this->vs->setPerPage(3);
		return $this->vs->productList();		
		
	}

}
?>