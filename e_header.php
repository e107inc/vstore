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
    if(deftrue('USER_AREA')) {
        // prevents inclusion of JS/CSS/meta in the admin area.
        e107::js('vstore', 'js/vstore.js');
        e107::lan('vstore', false, true); // e107_plugins/vstore/languages/English_front.php
        
        $vstore_prefs = e107::pref('vstore');
        
        e107::js('settings', array('vstore' => array(
                'url' => e107::url('vstore', 'index'),
                'cart' =>  array(
                    'url' => e107::url('vstore', 'cart'),
                    'addtocart' => LAN_VSTORE_001, // 'Add to cart',
                    'outofstock' => empty($vstore_prefs['caption_outofstock'][e_LANGUAGE])
                        ? 'Out of stock'
                        : $vstore_prefs['caption_outofstock'][e_LANGUAGE],
                    'available' => LAN_VSTORE_002,
                ),
                'ImageZoom' => array('url'=>'')
            )
        ));
        
        
        if (!empty($vstore_prefs['custom_css']))
        {
            // Add any custom css to the page
            e107::css('inline', "
            /* vstore custom css */
            " . $vstore_prefs['custom_css']);
        }
    }


	 if(!class_exists('vstore_cart_icon'))
	 {
	    class vstore_cart_icon
	    {
	        function __construct()
	        {
	          //  require_once(e_PLUGIN.'vstore/vstore.class.php');

	            if(e_ADMIN_AREA !== true)
	            {

	                $vst = e107::getSingleton('vstore',e_PLUGIN.'vstore/vstore.class.php');

	                $data = $vst->getCartData();

	                //$count = count($data);
	                // Sum up cart quantity for badge instead of only number of different products (items)
	                $count = 0;
	                if($data && count($data)){
	                    foreach ($data as $row) {
	                        $count += $row['cart_qty'];
	                    }
	                }


	            }
	            else
	            {
	                $count = 5;
	            }

	             $style = empty($count) ? '' : "class='active'";

				$text = '<span id="vstore-cart-icon" '.$style.'>'.e107::getParser()->toGlyph("fa-shopping-cart").'<span class="badge">'.$count.'</span></span>';

				if(!defined('LAN_PLUGIN_VSTORE_CARTICON'))
				{
			        define('LAN_PLUGIN_VSTORE_CARTICON', $text);
				}
	        }

	    }
	 }

	new vstore_cart_icon;


 }

