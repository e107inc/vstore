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
/*if(!e107::isInstalled('gsitemap'))
{ 
	return;
}*/


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
		
		$sql->select("vstore_cat","*","cat_id != '' ORDER BY cat_order,cat_name");
		
		while($row = $sql->fetch())
		{
			$sublinks[] = array(
				'link_name'			=> $tp->toHtml($row['cat_name'],'','TITLE'),
				'link_url'			=> e107::url('vstore','category',$row),
				//'link_url'			=> e107::url('vstore','cat',$row), // '{e_PLUGIN}vstore/vstore.php?item='.$row['item_id'], // 1e107::getUrl()->sc('faqs/list/all', array('category' => $row['faq_info_id'])),
				'link_description'	=> '',
				'link_button'		=> '',
				'link_category'		=> '',
				'link_order'		=> '',
				'link_parent'		=> '',
				'link_open'			=> '',
				'link_class'		=> 0
			);
		}
		
		return $sublinks;
	    
	}



	function storeCart() // http://bootsnipp.com/snippets/33gmp
	{

		$vst = e107::getSingleton('vstore',e_PLUGIN.'vstore/vstore.class.php');
		$sc = e107::getScBatch('vstore_plugin');

		$data = $vst->getCartData();
		$frm = e107::getForm();
		$tp = e107::getParser();
		$template = e107::getTemplate('vstore', 'vstore', 'navcart');

		//TODO Move into class.

		e107::getDebug()->log($data);

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


		foreach($data as $item)
		{
			$images = e107::unserialize($item['item_pic']);
			$img = $tp->toImage($images[0]['path'],array('w'=>60));

			$subtotal = ($item['item_price'] * $item['cart_qty']);
			$itemcount += $item['cart_qty'];

			$itemvarstring = '';
			if (!empty($item['cart_item_vars']))
			{
				$itemprop = vstore::getItemVarProperties($item['cart_item_vars'], $item['item_price']);

				if ($itemprop)
				{
					$itemvarstring = $itemprop['variation'];
					$subtotal = ($item['item_price'] + $itemprop['price']) * $item['cart_qty'];
				}

				if (!empty($itemvarstring))
				{
					$itemvarstring = '<br/><span class="vstore-cart-item-var small">' . $itemvarstring . '</span>';
				}
			}

			$sc->setVars(array(
				'item' => array(
					'pic' => $img,
					'item_total' => $subtotal,
					'name' => '<span class="vstore-navcart-name">'.$item['item_name'].'</span>'.$itemvarstring,
					'quantity' => $item['cart_qty']
					)
				)
			);
			$text .= $tp->parseTemplate($template['item'], true, $sc); 

			$total = $total + $subtotal;

		}


		$sc->setVars(array(
			'order_pay_amount' => $total,
			'item_count' => $itemcount,
		));
		$text .= $tp->parseTemplate($template['footer'], true, $sc); 
	
		if (!e_AJAX_REQUEST) {
			$text .= $tp->parseTemplate($template['end'], true, $sc);
		}		

		return $text;

	}

	
}
