<?php
/**
 * e107 website system
 *
 * Copyright (C) 2008-2016 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

 if(e107::isInstalled('vstore'))
 {


    class vstore_cart_icon
    {
        function __construct()
        {
          //  require_once(e_PLUGIN.'vstore/vstore.class.php');

            $vst = e107::getSingleton('vstore',e_PLUGIN.'vstore/vstore.class.php');

			$data = $vst->getCartData();

			$count = count($data);

	        $style = empty($count) ? '' : "class='active'";

			$text = "<span id='vstore-cart-icon' ".$style.">".e107::getParser()->toGlyph('fa-shopping-cart')."<span class='badge'>".$count."</span></span>";

		    define('LAN_PLUGIN_VSTORE_CARTICON', $text);


        }

    }


	new vstore_cart_icon;


 }