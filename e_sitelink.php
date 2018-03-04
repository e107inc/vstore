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


		//TODO Move into class.

e107::getDebug()->log($data);
		$text = '<div id="vstore-cart-dropdown" class="dropdown-menu">';

		if(empty($data))
		{
			$text .= '<div id="vstore-cart-dropdown-empty" class="alert alert-info">Your cart is empty.';
			$text .= ' <a class="alert-link" href="'.e107::url('vstore','index').'">Start Shopping</a>';
			$text .= '	</div></div>';

			return $text;
		}


		$text .= '
                    <div class="form-group alert alert-info" style="max-height: 400px;overflow-y:auto;">
                            <ul class="media-list list-unstyled">';

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
				$itemvars = vstore::item_vars_toArray($item['cart_item_vars']);

				foreach($itemvars as $k => $v)
				{
					$itemvarstring .= ($itemvarstring ? ' / ' : '') . vstore::getItemVarString($k, $v);
				}
				if (!empty($itemvarstring))
				{
					$itemvarstring = '<br/><span class="vstore-cart-item-var small">' . $itemvarstring . '</span>';
				}
			}
			$text .= '<li class="media">
					<span class="media-object pull-left">'.$img.'</span>
					<div class="media-body"><b>'.$item['item_name'].'</b>'.$itemvarstring.'<br />
						<span class="pull-right">'.$item['cart_qty'].' &times; '.$sc->sc_cart_currency_symbol().' '.number_format($subtotal,2).'</span>
					</div>
					</li>';

			$total = $total + $subtotal;

		}



           $text .= '

						<li class="media text-right"><h4>Total: '.$sc->sc_cart_currency_symbol().' '.number_format($total,2).'</h4></li>
                            </ul>
						<div id="vstore-item-count" class="hidden">'.$itemcount.'</div>
                    </div>

					<div>
						 <a class="btn btn-block btn-danger" href="#" onclick="vstoreCartReset()"><i class="fa fa-trash-o" aria-hidden="true"></i> Clear cart</a>
						 <a class="btn btn-block btn-primary col-xs-6" href="'.e107::url('vstore','cart').'"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Checkout</a>
					</div>
				</div>			';

		return $text;

	}

	
}
