<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2009 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Sitelinks configuration module - gsitemap
 *
 * $Source: /cvs_backup/e107_0.8/e107_plugins/faqs/e_sitelink.php,v $
 * $Revision$
 * $Date$
 * $Author$
 *
*/

if (!defined('e107_INIT')) { exit; }


class vstore_sitelink // include plugin-folder in the name.
{
	function config()
	{

		$links = array();
			
		$links[] = array(
			'name'			=> "Vstore Categories",
			'function'		=> "storeCategories"
		);

			$links[] = array(
			'name'			=> "Vstore Shopping Cart",
			'function'		=> "storeCart"
		);
		
		
		return $links;
	}
	
	

	function storeCategories()
	{
		$sql = e107::getDb();
		$tp = e107::getParser();
		$sublinks = array();
		
		//$sql->select("vstore_cat","*","cat_id != '' ORDER BY cat_order,cat_name");
		$sql->gen('SELECT c.*, COUNT(i.item_id) AS items_count
			FROM e107_vstore_cat c
			LEFT JOIN e107_vstore_items i ON (c.cat_id = i.item_cat)
			WHERE i.item_inventory != 0 AND i.item_active = 1
			GROUP BY c.cat_id
			ORDER BY cat_order');
		
		while($row = $sql->fetch())
		{
			$sublinks[] = array(
				'link_name'			=> $tp->toHTML($row['cat_name'], '', 'TITLE'),
				'link_url'			=> e107::url('vstore','category', $row),
				'link_description'	=> $tp->toHTML($row['cat_description'], '', 'DESCRIPTION'),
				'link_button'		=> '',
				'link_category'		=> '',
				'link_order'		=> '',
				'link_parent'		=> '',
				'link_open'			=> '',
				'link_class'		=> 0,
				'link_badge'		=> $row['items_count']
			);
		}
		
		return $sublinks;
	    
	}



	function storeCart() // http://bootsnipp.com/snippets/33gmp
	{
		if (e_ADMIN_AREA) return;
		
		$vst = e107::getSingleton('vstore',e_PLUGIN.'vstore/vstore.class.php');
		$sc = e107::getScParser()->getScObject('vstore_shortcodes', 'vstore', false);

		$data = $vst->getCartData();
		$cust = $vst->getCustomerData();
		$data = $vst->prepareCheckoutData($data, false, true);

		$isBusiness = !empty($cust['vat_id']);
		$isLocal = (varset($cust['country'], e107::pref('vstore', 'tax_business_country')) == e107::pref('vstore', 'tax_business_country'));
		
		$frm = e107::getForm();
		$tp = e107::getParser();
		$template = e107::getTemplate('vstore', 'vstore', 'navcart');

		//TODO Move into class.

		$text = '';
		if (!e_AJAX_REQUEST)
		{
			$text = $tp->parseTemplate($template['start'], true, $sc);
		}

		if(empty($data))
		{
			$text .= $tp->parseTemplate($template['empty'], true, $sc); 
			if (!e_AJAX_REQUEST) {
				$text .= $tp->parseTemplate($template['end'], true, $sc);
			}
			return $text;
		}


		$text .= $tp->parseTemplate($template['header'], true, $sc); 
		$total = 0;
		$itemcount = 0;


		foreach($data['items'] as $item)
		{
			$images = e107::unserialize($item['item_pic']);
			$img = $tp->toImage($images[0]['path'],array('w'=>60));

			$itemcount += $item['cart_qty'];

			$sc->setVars(array(
				'item' => array(
					'pic' => $img,
					'price' => ($isBusiness && !$isLocal ? $item['item_price_net'] : $item['item_price']),
					'item_total' => ($isBusiness && !$isLocal ? $item['item_total_net'] : $item['item_total']),
					'name' => '<span class="vstore-navcart-name">'.$item['item_name'].'</span>'.($item['itemvarstring'] ? '<br/>'.$item['itemvarstring'] : ''),
					'quantity' => $item['cart_qty']					
				)
			));

			$text .= $tp->parseTemplate($template['item'], true, $sc); 

			//$total += $subtotal;

		}


		$sc->setVars(array(
			'order_pay_amount' => ($isBusiness && !$isLocal ? $data['totals']['cart_subNet'] : $data['totals']['cart_subTotal']),
			'item_count' => $itemcount,
		));
		$text .= $tp->parseTemplate($template['footer'], true, $sc); 
	
		if (!e_AJAX_REQUEST) {
			$text .= $tp->parseTemplate($template['end'], true, $sc);
		}		

		return $text;

	}

	
}
