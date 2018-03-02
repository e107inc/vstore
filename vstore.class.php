<?php


e107::css('vstore','vstore.css');
e107::js('vstore','js/vstore.js');


require_once('vendor/autoload.php');


use Omnipay\Omnipay;


class vstore_plugin_shortcodes extends e_shortcode
{
	
	protected $vpref = array();
	protected $videos = array();
	protected $symbols = array();
	protected $curSymbol = null;
	protected $currency = null;
	protected $displayCurrency = false;
	protected $categories = array();
	public $captionOutOfStock = 'Out of Stock';
	protected $halt = false;
	
	public function __construct()
	{
	 	$this->vpref = e107::pref('vstore');	
				
		$this->symbols = array('USD'=>'$','EUR'=>'€','CAN'=>'$','GBP'=>'£', "BTC"=> "<i class='fa fa-btc'></i>");
		$currency = !empty($this->vpref['currency']) ? $this->vpref['currency'] : 'USD';

		$this->curSymbol = vartrue($this->symbols[$currency],'$');
		$this->currency = ($this->displayCurrency === true) ? $currency : '';
		
	}


	function sc_order_ship_firstname()
	{
		return $this->var['order_ship_firstname'];
	}

	function sc_order_ship_lastname()
	{
		return $this->var['order_ship_lastname'];
	}

	function sc_order_ship_country()
	{
		return e107::getForm()->getCountry($this->var['order_ship_country']);
	}

	function sc_order_date()
	{
		return e107::getParser()->toDate($this->var['order_date']);
	}

	function sc_order_ref()
	{
		return $this->var['order_ref'];
	}

	function sc_order_ship_address()
	{
		return $this->var['order_ship_address'];
	}

	function sc_order_ship_city()
	{
		return $this->var['order_ship_city'];
	}

	function sc_order_ship_state()
	{
		return $this->var['order_ship_state'];
	}



	function sc_order_ship_zip()
	{
		return $this->var['order_ship_zip'];
	}

	function sc_order_items()
	{
		$items = $this->var['order_items'];
		if (!is_array($items))
		{
			$items = e107::unserialize($items);
		}
		

		$text  = "<table class='table table-bordered'>
					<colgroup>	
			            <col style='width:50%' />
			            <col  />
			            <col  />
			            <col  />
				    </colgroup>
				<tr>
					<th>Description</th>
					<th class='text-right'>Unit Price</th>
					<th class='text-right'>Qty</th>
					<th class='text-right'>Amount</th>
				
				</tr>";

		foreach($items as $key=>$item)
		{
			$desc = $item['description'];

			if (!empty($item['vars']))
			{
				$desc .= '<br/>' . $item['vars'];
			}

			if ($item['id']>0 && varset($item['file']))
			{
				if ($this->var['order_status'] === 'C' || ($this->var['order_status'] === 'N' && $this->var['order_pay_status'] == 'complete'))
				{
					$linktext = 'Download';
				}
				else
				{
					$linktext = 'Download (will be available once the payment has been received)';
				}
				$desc .= '<br/><a href="'.e107::url('vstore', 'download', array('item_id' => $item['id']), array('mode'=>'full')).'">'.$linktext.'</a>';
			}

			$text .= "
					<tr>
						<td>".$desc."</td>
						<td class='text-right'>".format_number($this->curSymbol.$item['price'], 2)."</td>
						<td class='text-right'>".$item['quantity']."</td>
						<td class='text-right'>".$this->curSymbol.format_number($item['price'] * $item['quantity'], 2)."</tdclass>
					</tr>";
		}

		$text .= "
				<tr>
					<td colspan='3' class='text-right'><b>Shipping</b></td>
					<td class='text-right'>".$this->curSymbol.format_number($this->var['order_pay_shipping'], 2)."</td>
				</tr>";

		$text .= "
				<tr>
					<td colspan='3' class='text-right'><b>Total</b></td>
					<td class='text-right'>".$this->curSymbol.format_number($this->var['order_pay_amount'], 2)."</td>
				</tr>";


		$text .= "
		</table>";

		return $text;


	}

	function sc_order_merchant_info()
	{
		$info = e107::pref('vstore', 'merchant_info');

		if(empty($info))
		{
			return null;
		}

		return e107::getParser()->toHtml($info,true);

	}

	function sc_order_payment_instructions()
	{
		if($this->var['order_pay_gateway'] !== 'bank_transfer')
		{
			return null;
		}
	
		$bankTransfer = e107::pref('vstore','bank_transfer_details');

		return e107::getParser()->toHtml($bankTransfer,true);

	}

	function sc_sender_name()
	{
		$info = e107::pref('vstore', 'sender_name');

		if(empty($info))
		{
			return null;
		}

		return e107::getParser()->toHtml($info, true);
	}









	function setCategories($data)
	{
		$this->categories = $data;
	}
	
	function inStock()
	{
		$inStock = true;
		if(empty($this->var['item_vars'])){
			$inStock = empty($this->var['item_inventory']) ? false : ($this->var['item_inventory'] != 0);
		}
		else
		{
			$itemvars = explode(',', $this->var['item_vars']);
			$inv = e107::unserialize($this->var['item_vars_inventory']);
			if (empty($this->var['item_vars_inventory']))
			{
				$inStock = false;
			}
			elseif(count($itemvars) == 1)
			{
				$varX = array_keys($inv)[0];
				if (intval($inv[$varX]) == 0)
				{
					$inStock = false;
				}
			}
			elseif(count($itemvars) == 2)
			{
				$varX = array_keys($inv)[0];
				$varY = array_keys($inv[$varX])[0];
				if (intval($inv[$varX][$varY]) == 0)
				{
					$inStock = false;
				}
			}
		}
		return $inStock;		
	}

	function sc_item_id($parm=null)
	{
		return $this->var['item_id'];	
	}

	function sc_item_code($parm=null)
	{
		return $this->var['item_code'];	
	}
	
	function sc_item_name($parm=null)
	{
		return e107::getParser()->toHtml($this->var['item_name'], true,'TITLE');	
	}

	function sc_item_var_string($parm=null)
	{
		$itemvarstring = '';
		if (!empty($this->var['cart_item_var_keys']))
		{
			$itemvarkeys = explode(',', $this->var['cart_item_var_keys']);
			$itemvarvalues = explode(',', $this->var['cart_item_var_values']);

			foreach($itemvarkeys as $i => $key)
			{
				$itemvarstring .= ($itemvarstring ? ' / ' : '') . vstore::getItemVarString($key, $itemvarvalues[$i]);
			}
		}

		return e107::getParser()->toHtml($itemvarstring, true,'BODY');	
	}

	function sc_item_description($parm=null)
	{

		$tp = e107::getParser();

		$text = $this->var['item_desc'];

		if(!empty($parm['limit']) && !empty($text))
		{
			$text = $tp->text_truncate($text,$parm['limit']);
		}

		return $tp->toHtml($text, false, 'BODY');
	}
	
	function sc_item_details($parm=null)
	{
		return e107::getParser()->toHtml($this->var['item_details'], true,'BODY');
	}
	

	function sc_item_vars($parm=null)
	{
		$itemid = intval($this->var['item_id']);
		$baseprice = floatval($this->var['item_price']);
		$this->var['item_var_price'] = $baseprice;
		if (varset($this->var['item_vars']))
		{
			$ns = e107::getParser();
			$frm = e107::getForm();
			$sql = e107::getDb();
			if ($sql->select('vstore_items_vars', '*', 'FIND_IN_SET(item_var_id, "'.$this->var['item_vars'].'")'))
			{
				$text = '
					<div id="vstore-item-vars-'.$itemid.'">';
				while($row = $sql->fetch())
				{
					$attributes = e107::unserialize($row['item_var_attributes']);

					$select = $frm->select_open(
						'item_var['.$itemid.']['.$row['item_var_id'].']', 
						array('class' => 'vstore-item-var tbox select form-control', 'data-id'=>$itemid, 'data-name'=>varset($row['name'], 'foo'), 'required' => vartrue($row['item_var_compulsory']))
					);
					
					$selected = true;
					foreach($attributes as $var)
					{
						$varname = $var['name'];
						if (floatval($var['value']) > 0.0)
						{
							switch ($var['operator'])
							{
							case '%':
								if ($selected) $this->var['item_var_price'] += $baseprice * (floatval($var['value']) / 100.0);
								$varname .= ' (+ '.floatval($var['value']).'%)';
								break;
							case '+':
								if ($selected) $this->var['item_var_price'] += floatval($var['value']);
								$varname .= ' (+ '.$this->currency.$this->curSymbol.number_format(floatval($var['value']), 2).')';
								break;
							case '-':
								if ($selected) $this->var['item_var_price'] -= floatval($var['value']);
								$varname .= ' (- '.$this->currency.$this->curSymbol.number_format(floatval($var['value']), 2).')';
								break;
							}
						}

						$select .= $frm->option(
							$varname, 
							$frm->name2id($var['name']), 
							$selected, 
							array('data-op'=>$var['operator'], 'data-val'=>floatval($var['value']), 'data-id'=>$row['item_var_id'], 'data-item'=>$itemid)
						);
						$selected = false;
					}

					$select .= $frm->select_close();

					$text .= '
						<div>
							<label>'.$row['item_var_name'].'
							'.$select.'
							</label>
						</div>';
				}
				$text .= '
					</div>';


				if (varset($this->var['item_vars_inventory']))
				{
					e107::js('settings', array('vstore' => array(
							'stock' => array( 
								"x{$itemid}" => e107::unserialize($this->var['item_vars_inventory'])
								)
							)
						)
					);
				}
				else
				{
					e107::js('settings', array('vstore' => array(
							'stock' => array( 
								"x{$itemid}" => intval($this->var['item_inventory'])
								)
							)
						)
					);
			
				}
				return $text;
			}
		}
		else
		{
			e107::js('settings', array('vstore' => array(
					'stock' => array( 
						"x{$itemid}" => intval($this->var['item_inventory'])
						)
					)
				)
			);
		}
		return ''; // No item_vars set
	}

	
	
	
	function sc_item_reviews($parm=null)
	{
		// print_a($this->var['item_reviews']);
		$rev = str_replace("\r","",$this->var['item_reviews']);
		
		$tmp = explode("\n\n",$rev);
		
		if(empty($tmp))
		{
			return null;
		}

		$text = '';

		foreach($tmp as $val)
		{
			list($review, $by) = explode("--",$val);
			$text .= "<blockquote>".$review."<small>".$by."</small></blockquote>";	
		}
		
		return $text;
		//return e107::getParser()->toHtml($this->var['item_reviews'], true, 'BODY');
	}	
	
	function sc_item_related($parm=null)
	{
	
		if(empty($this->var['item_related']))
		{
			return false; 
		}
		$tp = e107::getParser();
		$row = e107::unserialize($this->var['item_related']);
		
	//	return print_a($row, true);
		
		list($table, $chapter) = explode("|", $row['src']);

		$text = '';
		
	
		if($table == 'page_chapters')
		{
			if($chp = e107::getDb()->retrieve('page', '*', 'page_chapter ='.$chapter.' AND page_class IN ('.USERCLASS_LIST.') ORDER BY page_order', true))
			{
				$sc = e107::getScBatch('page',null,'cpage');
				
				$text = "<ul>";
				foreach($chp as $row)
				{
					$sc->setVars($row);
		
					$text .= $tp->parseTemplate("<li>{CPAGELINK}</li>",true,$sc);	
					
					
				}
				
				$text .= "</ul>";
			}
			
		}
		
		return $text;
	}
	
	function sc_item_brand($parm=null)
	{
		return $this->var['item_brand'];	
	}

	function sc_item_pic($parm=null)
	{
		$index = (!empty($parm['item'])) ? intval($parm['item']) : 0; // intval($parm);
		$ival = e107::unserialize($this->var['item_pic']);
		$tp = e107::getParser();
		
		$images = array();
		foreach($ival as $i)
		{
			if($tp->isImage($i['path']))
			{
				$images[] = $i['path'];
			}
		}

		$path = vartrue($images[$index]);
		$pre = "";
		$post = "";


		if(!empty($parm['link']))
		{
			$parm['scale']= '3x';
			$link = $tp->thumbUrl($path, $parm);
			unset($parm['scale'],$parm['link']);
			$pre = "<a href='".$link."' data-standard='".$tp->thumbUrl($path, $parm)."'>";
			$post = "</a>";

		}

		return $pre. e107::getParser()->toImage($path,$parm) . $post;
	}
	
	function sc_item_video($parm=0)
	{
		$index = intval($parm);
		$ival = e107::unserialize($this->var['item_pic']);
		
		$videos = array();
		foreach($ival as $i)
		{
			if(substr($i['path'],-8) == '.youtube')
			{
				$videos[] = $i['path'];
			}
		}
		
		$path = vartrue($videos[$index]);
		return e107::getParser()->toVideo($path);	
		
	}
	

	
	
	
	
	// Categories	
	
	function sc_cat_id($parm=null)
	{
		return $this->var['cat_id'];	
	}
	
	function sc_cat_name($parm=null)
	{
		return e107::getParser()->toHtml($this->var['cat_name'], true,'TITLE');	
	}
	
	function sc_cat_sef($parm=null)
	{
		return e107::getParser()->toHtml($this->var['cat_sef'], true,'TITLE');	
	}

	function sc_cat_description($parm=null)
	{
		return e107::getParser()->toHtml($this->var['cat_description'], true, 'BODY');	
	}
	
	function sc_cat_info($parm=null)
	{
		return e107::getParser()->toHtml($this->var['cat_info'], true,'BODY');	
	}	
	
	function sc_cat_image($parm=0)
	{
		return e107::getParser()->thumbUrl($this->var['cat_image']);
	}
	
	function sc_cat_pic($parm=null)
	{
		return e107::getParser()->toImage($this->var['cat_image']);
	}

	function sc_cat_url($parm=null)
	{

		$urlData    = $this->var;
		$route      = 'category';

		if($this->var['cat_parent'] != 0 )
		{
			$urlData['subcat_name'] = $this->var['cat_name'];
			$urlData['subcat_sef']  = $this->var['cat_sef'];
			$urlData['subcat_id']   = $this->var['cat_id'];

			$pid    = $this->var['cat_parent'];
			$parent = $this->categories[$pid];

			$urlData['cat_name']    = $parent['cat_name'];
			$urlData['cat_id']      = $parent['cat_id'];
			$urlData['cat_sef']     = $parent['cat_sef'];

			$route = 'subcategory';
		}

		//e107::getDebug()->log($urlData);

		return e107::url('vstore',$route, $urlData);
	}
	
		
	function sc_pref_howtoorder()
	{
		return e107::getParser()->toHtml($this->vpref['howtoorder'],true,'BODY');	
	}
	
	function sc_item_files($parm=0)
	{

		if(empty($this->var['item_files']))
		{
			return null;
		}

		$ival = e107::unserialize($this->var['item_files']);
		
		$id = array();
		
		foreach($ival as $i)
		{
			if(!empty($i['path']) && !empty($i['id']))
			{
				$id[] = intval($i['id']);
			}	
		}

		if(empty($id))
		{
			return null;
		}


		$qry = 'SELECT media_id,media_name FROM #core_media WHERE media_id IN ('.implode(',',$id).') ORDER BY media_name ';
		$files = e107::getDb()->retrieve($qry,true);

		$tp = e107::getParser();
		
		$text = '<ul>';
		foreach($files as $i)
		{
			$bb = '[file='.$i['media_id'].']'.$i['media_name'].'[/file]';
			$text .= '<li>'.$tp->toHtml($bb,true).'</li>';
		}
		
		$text .= '</ul>';
		
		return $text;
	}
	
	
	function sc_item_price($parm=null)
	{
		$itemid = intval($this->var['item_id']);
		$baseprice = $price = floatval($this->var['item_price']);
		$varprice = floatval($this->var['item_var_price']);

		if ($varprice > 0.0 && $varprice != $baseprice)
		{
			$price = $varprice;
		}
		return $this->currency.$this->curSymbol.' <span class="vstore-item-price-'.$itemid.'">'.number_format($price, 2).'</span><span class="hidden vstore-item-baseprice-'.$itemid.'">'.$baseprice.'</span>'; 
		// return ($this->var['item_price'] == '0.00') ? "" : $this->currency.$this->curSymbol.' '.$this->var['item_price'];	
	}	
	
	
	function sc_item_addtocart($parm=null)
	{

		$class = empty($parm['class']) ? 'btn btn-success vstore-add' : $parm['class'];
		$classo = empty($parm['class0']) ? 'btn btn-default disabled vstore-add' : $parm['class0'];
		$itemid = ' data-vstore-item="'.varset($this->var['item_id'], 0).'"';

		if (!in_array('vstore-add', explode(' ', $class)))
		{
			$class .= ' vstore-add';
		}
		if (!in_array('vstore-add', explode(' ', $classo)))
		{
			$classo .= ' vstore-add';
		}
		$itemclass = ' vstore-add-item-'.varset($this->var['item_id'], 0);

		$class .= $itemclass;
		$class0 .= $itemclass;

		$inStock = $this->inStock();

		if(!$inStock)
		{
			return "<a href='#' class='btn-out-of-stock ".$classo."'".$itemid.">".$this->captionOutOfStock."</a>";
		}
		// if(empty($this->var['item_inventory']))
		// {
		// 	return "<a href='#' class='btn-out-of-stock ".$classo."'".$itemid.">".$this->captionOutOfStock."</a>";
		// }

	
		// $url = ($this->var['item_price'] == '0.00' || empty($this->var['item_inventory'])) ? $this->sc_item_url() :e107::url('vstore', 'addtocart', $this->var);
		// $label =  ($this->var['item_price'] == '0.00' || empty($this->var['item_inventory'])) ? LAN_READ_MORE : 'Add to cart';
		$label =  ($this->var['item_price'] == '0.00' || !$inStock) ? LAN_READ_MORE : 'Add to cart';


		// return '<a class="'.$class.'" '.$itemid.' href="'.$url.'"><span class="glyphicon glyphicon-shopping-cart"></span> '.$label.'</a>';
		return '<a class="'.$class.'" '.$itemid.' href="#"><span class="glyphicon glyphicon-shopping-cart"></span> '.$label.'</a>';
	}


	function sc_item_status($parm=null)
	{
		if($this->var['item_inventory'] != 0)
		{
			return '<span class="text-success"><strong>In Stock</strong></span>';
		}	

		return '<span class="text-danger"><strong>Out of Stock</strong></span>';
	}
	
	function sc_item_url($parm=null)
	{
		if(!empty($this->var['item_link']))
		{
			return $this->var['item_link'];
		}
	
		return e107::url('vstore','product', $this->var);
	}
	
	// -------------
	
	function sc_cart_price($parm=null)
	{
		return $this->curSymbol.$this->var['item_price'];		
	}
	
	function sc_cart_total($parm=null)
	{
		$total = ($this->var['item_price'] * $this->var['cart_qty']);
		return number_format($total,2);
	}
	
	function sc_cart_qty($parm=null)
	{
		if($parm == 'edit')
		{
			$readonly = '';

			if(!empty($this->var['item_download'])) // digital download so set to 1.
			{
				$this->var['cart_qty'] = 1;
				$readonly = 'readonly';
			}

			return '<input type="input" '.$readonly.' name="cartQty['.$this->var['cart_id'].'][qty]" class="form-control text-right cart-qty" id="cart-'.$this->var['cart_id'].'" value="'.intval($this->var['cart_qty']).'">';
		}
		
		
		return $this->var['cart_qty'];
	}
	
	function sc_cart_vars($parm=null)
	{
		$text = $this->var['cart_item_var_keys'] . '|' . $this->var['cart_item_var_values'];
		if ($text == '|') $text = '';


		$text2 = '<input type="hidden" name="cartQty['.$this->var['cart_id'].'][id]" id="cart-id-'.$this->var['cart_id'].'" value="'.$this->var['cart_item'].'">';
		$text2 .='<input type="hidden" name="cartQty['.$this->var['cart_id'].'][vars]" id="cart-vars-'.$this->var['cart_id'].'" value="'.$text.'">';
		return $text2;
	}
	
	function sc_cart_removebutton($parm=null)
	{
		return '<button type="submit" name="cartRemove['.$this->var['cart_id'].']" class="btn btn-default" title="Remove">
			<span class="fa fa-trash"></span></button>';
		
	}
	
	function sc_cart_subtotal($parm=null)
	{
		return $this->curSymbol.number_format($this->var['cart_subTotal'], 2);
	}
	
	function sc_cart_shippingtotal($parm=null)
	{
		return $this->curSymbol.number_format($this->var['cart_shippingTotal'], 2);
	}

	function sc_cart_checkout_button()
	{
		$text = '<a href="'.e107::url('vstore','checkout').'" id="cart-checkout"  class="btn btn-success">
		                            Checkout <span class="glyphicon glyphicon-play"></span>
		                        </a>
		                        <button id="cart-qty-submit" style="display:none" type="submit" class="btn btn-warning">Re-Calculate</button>

		';

		return $text;

	}



	static function sc_cart_continueshop()
	{
		
		$link = e107::url('vstore','index');
		
		return '
		<a href="'.$link.'" class="btn btn-default">
			<span class="glyphicon glyphicon-shopping-cart"></span> Continue Shopping
		</a>';
	}

	function sc_item_availability()
	{
		//if(empty($this->var['item_inventory']))
		if (!$this->inStock())
		{
			return "<span class='label label-danger vstore-item-avail-".$this->var['item_id']."'>".$this->captionOutOfStock."</span>";
		}

		return "<span class='label label-success vstore-item-avail-".$this->var['item_id']."'>In Stock</span>";
	}
	
	
	function sc_cart_grandtotal($parm=null)
	{
		return $this->curSymbol.number_format( $this->var['cart_grandTotal'], 2);
	}
		
	public function sc_cart_currency_symbol($parm=null)
	{
		return $this->curSymbol;
	}

}



class vstore
{
	
	protected 	$cartId             = null;
	protected 	$sc;
	protected 	$perPage            = 9;
	protected   $from               = 0;
	protected 	$categories         = array(); // all categories;
	protected   $categorySEF        = array();
	protected 	$item               = array(); // current item.
	protected   $captionBase        = "Vstore";
	protected   $captionCategories  = "Product Brands";
	protected   $captionOutOfStock  = "Out of Stock";
	protected   $get                = array();
	protected   $post               = array();
	protected   $categoriesTotal    = 0;
	protected   $action             = array();
	protected   $pref               = array();
	protected   $parentData         = array();
	protected   $currency           = 'USD';

	protected   static $gateways    = array(
		'paypal'        => array('title'=>'Paypal', 'icon'=>'fa-paypal'),
		'paypal_rest'   => array('title'=>'Paypal', 'icon'=>'fa-paypal'),
		'amazon'        => array('title'=> 'Amazon', 'icon'=>'fa-amazon'),
		'coinbase'      => array('title'=> 'Bitcoin', 'icon'=>'fa-btc'),
		'bank_transfer' => array('title'=>'Bank Transfer', 'icon'=>'fa-bank'),
	);

	protected static $status = array(
		'N' => 'New',
		'P' => 'Processing',
		'H' => 'On Hold',
		'C' => 'Completed',
		'X' => 'Cancelled',
		'R' => 'Refunded'
	);

	protected static $emailTypes = array(
		'default' => 'Order confirmation', 
		'completed' => 'Order completed',
		'cancelled' => 'Order cancelled',
		'refunded' => 'Order refunded'
	);


	protected static $shippingFields = array(
		 'firstname',
		 'lastname',
		 'email',
		 'phone',
		 'company',
		 'address',
		 'city',
		 'state',
		 'zip',
		 'country',
		 'notes'
	);



	public function __construct()
	{
		$this->cartId = $this->getCartId();		
		$this->sc = new vstore_plugin_shortcodes();


		$this->get = $_GET;
		$this->post = $_POST;

		$pref = e107::pref('vstore');

		if(!empty($pref['currency']))
		{
			$this->currency = $pref['currency'];
		}

		if(!empty($pref['caption']) && !empty($pref['caption'][e_LANGUAGE]))
		{
			$this->captionBase = $pref['caption'][e_LANGUAGE];
		}

		foreach($pref['additional_fields'] as $k => $v)
		{
			if (vartrue($v['active'], false))
			{
				static::$shippingFields[] = 'add_field'.$k;
			}
		}

		if(!empty($pref['caption_categories']) && !empty($pref['caption_categories'][e_LANGUAGE]))
		{
			$this->captionCategories = $pref['caption_categories'][e_LANGUAGE];
			//e107::getDebug()->log("caption: ".$this->captionCategories);
		}

		if(!empty($pref['caption_outofstock']) && !empty($pref['caption_outofstock'][e_LANGUAGE]))
		{
			$this->captionOutOfStock = $pref['caption_outofstock'][e_LANGUAGE];
			$this->sc->captionOutOfStock = $this->captionOutOfStock;
		}


		e107::getDebug()->log($this->pref);
		e107::getDebug()->log("CartID:".$this->cartId);

		// get all category data.
		$query = 'SELECT * FROM #vstore_cat WHERE cat_active=1 ';
		if(!$data = e107::getDb()->retrieve($query, true))
		{

		}


		$count = 0;
		foreach($data as $row)
		{
			$id = $row['cat_id'];
			$this->categories[$id] = $row;
			$sef = vartrue($row['cat_sef'],'--undefined--');
			$this->categorySEF[$sef] = $id;

			if(empty($row['cat_parent']))
			{
				$count++;
			}
		}

		$this->categoriesTotal = $count;




		$active = array();

		foreach(self::$gateways as $k=>$icon)
		{
			$key = $k."_active";
			if(!empty($pref[$key]))
			{
				$active[$k] = $this->getGatewayIcon($k);

				foreach($pref as $key=>$v) // get gateway prefs.
				{
					if(strpos($key,$k) === 0)
					{
						$newkey = substr($key,(strlen($k)+1));
						$this->pref[$k][$newkey] = $v;
					}
				}
			}

		}


		if(getperms('0'))
		{
			e107::getDebug()->log($this->pref);
		}


		$this->active = $active;
	}





	function init()
	{
		// print_a($this->get);
		if(!empty($this->get['catsef']))
		{
			$sef = $this->get['catsef'];
			$this->get['cat'] = vartrue($this->categorySEF[$sef],0);
		}

		$this->process();
		

	}

	public static function getStatus($key=null)
	{
		if(!empty($key))
		{
			return self::$status[$key];
		}

		return self::$status;

	}

	public static function getEmailTypes($type=null)
	{
		if(!empty($type))
		{
			return self::$emailTypes[$type];
		}

		return self::$emailTypes;

	}

	public static function getShippingFields()
	{
		return self::$shippingFields;
	}


	private function process()
	{

		if(!empty($this->get['reset']))
		{
			$this->resetCart();
		}


		if(!empty($this->post['gateway']))
		{
			$this->setGatewayType($this->post['gateway']);

			if(!empty($this->post['firstname']))
			{
				$this->setShippingData($this->post);    // TODO Validate data before proceeding.
			}

			return $this->processGateway('init');
		}

		if($this->get['mode'] == 'return')
		{
			return $this->processGateway('return');
		}


		if(varset($this->post['cartQty']))
		{
			$this->updateCart('modify', $this->post['cartQty'], $this->post['cartVars']);
		}

		if(varset($this->post['cartRemove']))
		{
			$this->updateCart('remove', $this->post['cartRemove']);
		}

		if(!empty($this->get['add']))
		{
			if (!e_AJAX_REQUEST)
			{
				$this->addToCart($this->get['add'], $this->get['itemvar']);
			}
		}

	}


	private function renderForm()
	{

		$frm = e107::getForm();

		$text = '<h3>Shipping Details</h3>
			    			<div class="row">
			    				<div class="col-xs-6 col-sm-6 col-md-6">
			    					<div class="form-group">
			    					 <label for="firstname">First Name</label>
			    					'.$frm->text('firstname', $this->post['firstname'], 100, array('placeholder'=>'First Name', 'required'=>1)).'

			    					</div>
			    				</div>
			    				<div class="col-xs-6 col-sm-6 col-md-6">
			    					<div class="form-group">
			    					<label for="lastname">Last Name</label>
			    						'.$frm->text('lastname', $this->post['lastname'], 100, array('placeholder'=>'Last Name', 'required'=>1)).'
			    					</div>
			    				</div>
			    			</div>

			    			<div class="form-group">
			    			<label for="company">Company</label>
			    				'.$frm->text('company', $this->post['company'], 200, array('placeholder'=>'Company')).'
			    			</div>

			    			<div class="form-group">
			    			<label for="address">Address</label>
			    				'.$frm->text('address', $this->post['address'], 200, array('placeholder'=>'Address', 'required'=>1)).'
			    			</div>

			    			<div class="row">
			    				<div class="col-xs-6 col-sm-6 col-md-6">
			    					<div class="form-group">
			    					<label for="city">Town/City</label>
			    						'.$frm->text('city', $this->post['city'], 100, array('placeholder'=>'Town/City', 'required'=>1)).'
			    					</div>
			    				</div>
			    				<div class="col-xs-6 col-sm-6 col-md-6">
			    					<div class="form-group">
			    					<label for="state">State/Region</label>
			    						'.$frm->text('state', $this->post['state'], 100, array('placeholder'=>'State/Region', 'required'=>1)).'
			    					</div>
			    				</div>
			    			</div>


							<div class="row">
			    				<div class="col-xs-6 col-sm-6 col-md-6">
			    					<div class="form-group">
			    					<label for="zip">Zip/Postcode</label>
			    						'.$frm->text('zip', $this->post['zip'], 15, array('placeholder'=>'Zip/Postcode', 'required'=>1)).'
			    					</div>
			    				</div>
			    				<div class="col-xs-6 col-sm-6 col-md-6">
			    					<div class="form-group">
			    					<label for="country">Country</label>
			    						'.$frm->country('country', $this->post['country'], array('placeholder'=>'Select Country...', 'required'=>1)).'
			    					</div>
			    				</div>
			    			</div>

						<div class="row">
			    				<div class="col-xs-6 col-sm-6 col-md-6">
			    					<div class="form-group">
			    					<label for="email">Email address</label>
			    						'.$frm->email('email', $this->post['email'], 100, array('placeholder'=>'Email address', 'required'=>1)).'
			    					</div>
			    				</div>
			    				<div class="col-xs-6 col-sm-6 col-md-6">
			    					<div class="form-group">
			    					<label for="phone">Phone number</label>
			    						'.$frm->text('phone', $this->post['phone'], 15, array('placeholder'=>'Phone number', 'required'=>1)).'
			    					</div>
			    				</div>
			    		</div>
			    		<div class="row">
			    		    <div class="col-md-12">
								<div class="form-group">
				                <label for="notes">Order Notes</label>
				                    '.$frm->textarea('notes', $this->post['notes'], 4, null, array('placeholder'=>'Special notes for delivery.', 'required'=>0)).'
				                </div>
			    			</div>
						</div>
			    		';


		/**
		 * Additional checkout fields
		 * Start
		 */
		$pref = e107::pref('vstore');
		$addFieldActive = 0;
		foreach ($pref['additional_fields'] as $k => $v) 
		{
			// Check if additional fields are enabled
			if (vartrue($v['active'], false))
			{
				$addFieldActive++;
			}
		}

		if ($addFieldActive > 0)
		{
			// If any additional fields are enabled
			// add active fields to form
			$text .= '<br/><div class="row">';
			foreach ($pref['additional_fields'] as $k => $v) 
			{
				if (vartrue($v['active'], false))
				{
					$fieldname = 'add_field'.$k;
					if ($v['type'] == 'text')
					{
						// Textboxes
						$field = $frm->text($fieldname, $this->post[$fieldname], 100, array('placeholder'=>varset($v['placeholder'][e_LANGUAGE], ''), 'required'=>($v['required'] ? 1 : 0)));
					}
					elseif ($v['type'] == 'checkbox')
					{
						// Checkboxes
						$field = '<div class="form-control">'.$frm->checkbox($fieldname, 1, $this->post[$fieldname], array('required'=>($v['required'] ? 1 : 0)));
						if (vartrue($v['placeholder']))
						{
							$field .= ' <span class="text-muted">&nbsp;'.$v['placeholder'][e_LANGUAGE].'</span>';
						}
						$field .= '</div>';
					}

					// Bootstrap wrapper for control
					$text .= '
						<div class="'.($addFieldActive == 1 ? 'col-md-12' : 'col-xs-6 col-sm-6 col-md-6').'">
							<div class="form-group">
								<label for="'.$fieldname.'">'.varset($v['caption'][e_LANGUAGE], 'Additional field '.$k).'</label>
								'.$field.'
							</div>
						</div>
					';			
				}
			}
			$text .= '</div>';
		}
		/**
		 * Additional checkout fields
		 * End
		 */

		if(!USER)
		{
			$text .= '<div class="row">
			    				<div class="col-xs-6 col-sm-6 col-md-6">
			    					<div class="form-group">
			    						<input type="password" name="password" id="password" class="form-control input-sm" placeholder="Password">
			    					</div>
			    				</div>
			    				<div class="col-xs-6 col-sm-6 col-md-6">
			    					<div class="form-group">
			    						<input type="password" name="password_confirmation" id="password_confirmation" class="form-control input-sm" placeholder="Confirm Password">
			    					</div>
			    				</div>
			    			</div>';


		}



		return $text;



	}


	private function getMode()
	{
		return vartrue($this->get['mode']);
	}

	private function setMode($mode)
	{
		$this->get['mode'] = $mode;
	}

	public function render()
	{

		$ns = e107::getRender();

		if($this->get['add'])
		{
			if(e_AJAX_REQUEST)
			{
				$js = e107::getJshelper();
				$js->_reset();
				$itemid = $this->get['add'];
				$itemvars = $this->get['itemvar'];
				if (!$this->addToCart($itemid, $itemvars))
				{
					$msg = e107::getMessage()->render('vstore');
					ob_clean();
					$js->addTextResponse($msg)->sendResponse();
					exit;
				}
				else
				{
					$sl = new vstore_sitelink();
					$msg = $sl->storeCart();
				}
				ob_clean();
				$js->addTextResponse('ok '.$msg)->sendResponse();
				exit;
			}
			$bread = $this->breadcrumb();
			$text = $this->cartView();
			$ns->tablerender($this->captionBase, $bread.$text, 'vstore-cart-view');
			return null;
		}

		if (!empty($this->get['download']))
		{
			if (!$this->downloadFile($this->get['download']))
			{
				echo e107::getMessage()->render('vstore');
				return null;
			}
		}
		
		if(!empty($this->get['reset']))
		{
			if(e_AJAX_REQUEST)
			{
				$this->resetCart();
				$sl = new vstore_sitelink();
				$msg = $sl->storeCart();
				ob_clean();
				$js = e107::getJshelper();
				$js->_reset();
				$js->addTextResponse('ok '.$msg)->sendResponse();
				exit;
			}
		}
		
		if(!empty($this->get['refresh']))
		{
			if(e_AJAX_REQUEST)
			{
				$sl = new vstore_sitelink();
				$msg = $sl->storeCart();
				ob_clean();
				$js = e107::getJshelper();
				$js->_reset();
				$js->addTextResponse('ok '.$msg)->sendResponse();
				exit;
			}
		}


		if($this->getMode() == 'return')
		{
			// print_a($this->post);
			$bread = $this->breadcrumb();
			$text = $this->checkoutComplete();

			//TODO Check for digital download purchase and render download button.

			$ns->tablerender($this->captionBase, $bread.$text, 'vstore-cart-complete');
			return null;
		}


		if($this->getMode() == 'checkout')
		{
			// print_a($this->post);
			$bread = $this->breadcrumb();
			$text = $this->checkoutView();
			$ns->tablerender($this->captionBase, $bread.$text, 'vstore-cart-list');
			return null;
		}



		if($this->getMode() == 'cart')
		{
			// print_a($this->post);
			$bread = $this->breadcrumb();
			$text = $this->cartView();
			$ns->tablerender($this->captionBase, $bread.$text, 'vstore-cart-list');
			return null;
		}





		if($this->get['item'])
		{
			$text = $this->productView($this->get['item']);
			$bread = $this->breadcrumb();
			$ns->tablerender($this->captionBase, $bread.$text, 'vstore-product-view');
			return null;
		}



		if($this->get['cat'])
		{
			if($subCategoryText = $this->categoryList($this->get['cat'],false))
			{
			    $subCategoryText .= "<hr />";
			}


			$text = $this->productList($this->get['cat'], true);
			$bread = $this->breadcrumb();
			$ns->tablerender($this->captionBase, $bread. $subCategoryText.$text, 'vstore-product-list');


		}
		else
		{

			$text = $this->categoryList(0, true);
			$bread = $this->breadcrumb();
			$ns->tablerender($this->captionBase, $bread.$text, 'vstore-category-list');
		}



	}





	private function breadcrumb()
	{
		$frm = e107::getForm();



		$array = array();
		
		$array[] = array('url'=> e107::url('vstore','index'), 'text'=>$this->captionCategories);
		
		if($this->get['cat'] || $this->get['item'])
		{
			$c = $this->get['cat'];
			$cp = $this->categories[$c]['cat_parent'] ;

			if(!empty($cp))
			{
				$pid = $this->categories[$cp]['cat_id'];
				$url = e107::url('vstore','category', $this->categories[$pid]);
				$array[] = array('url'=> $url, 'text'=>$this->categories[$pid]['cat_name']);
			}

			$id = ($this->get['item']) ? $this->item['item_cat'] : intval($this->get['cat']);
			$url = ($this->get['item']) ? e107::url('vstore','category', $this->categories[$id]) : null;
			$array[] = array('url'=> $url, 'text'=>$this->categories[$id]['cat_name']);	
		}
		
		if($this->get['item'])
		{
			$array[] = array('url'=> null, 'text'=> $this->item['item_name']);		
			
		}

		if($this->get['add'] || $this->get['mode'] == 'cart')
		{
			$array[] = array('url'=> null, 'text'=> "Shopping Cart");
		}

		if($this->get['mode'] == 'checkout')
		{
			$array[] = array('url'=> e107::url('vstore','cart'), 'text'=> "Shopping Cart");
			$array[] = array('url'=> null, 'text'=> "Checkout");

		}


		
		if(ADMIN)
		{
		//	print_a($this->categories);
		//	print_a($this->item);
		//	print_a($array);
		}
		return $frm->breadcrumb($array);	
		
	}


	private function getActiveGateways()
	{


		return $this->active;

	}



	private function checkoutComplete()
	{
		$text = e107::getMessage()->render('vstore');

		$text .= "<div class='alert-block'>".vstore_plugin_shortcodes::sc_cart_continueshop()."</div>";

		return $text;
	}



	private function checkoutView()
	{
		$active = $this->getActiveGateways();

		if(!empty($active))
		{
			$text = e107::getForm()->open('gateway-select','post', null, array('class'=>'form'));

			$text .= $this->renderForm();


			$text .= "<hr /><h3>Select payment method to continue</h3><div class='vstore-gateway-list row'>";

			foreach($active as $gateway => $icon)
			{

					$text .= "
						<div class='col-md-4'>
						<button class='btn btn-default btn-block btn-".$gateway."' name='gateway' type='submit' value='".$gateway."'>".$icon."
						<h4>".$this->getGatewayTitle($gateway)."</h4>
						</button>

						</div>";


			}

			$text .= "</div>";
			$text .= e107::getForm()->close();

			if (vartrue(e107::pref('vstore', 'admin_confirm_order')))
			{
				// If the user has to confirm the order
				$text .= '
				<script type="text/javascript">
				$(function(){
					$("#gateway-select").submit(function(e){
						if(!confirm("By clicking on OK you confirm that you order the content of the shopping cart for the shown cost!"))
						{
							e.preventDefault();
							return false;
						}
						return true;
					});
				});
				</script>
				';
			}

			return $text;
		}

		return "No Payment Options Set";


	}


	//  // help http://stackoverflow.com/questions/20756067/omnipay-paypal-integration-with-laravel-4
	// https://www.youtube.com/watch?v=EvfFN0-aBmI
	private function processGateway($mode = 'init')
	{
		$type = $this->getGatewayType();

		e107::getDebug()->log("Processing Gateway: " . $type);

		if(empty($type))
		{
			e107::getMessage()->addError("Invalid Payment Type", 'vstore');
			return false;
		}

		switch($type)
		{
			case "amazon":
				/** @var \Omnipay\Common\AbstractGateway $gateway */
				$gateway = Omnipay::create('AmazonPayments');
				$defaults = $gateway->getParameters();
				e107::getDebug()->log($defaults);
				break;

			case "coinbase":

				$gateway = Omnipay::create('Coinbase');

			/*	if(!empty($this->pref['paypal']['testmode']))
				{
					$gateway->setTestMode(true);
				}*/

				$gateway->setAccountId($this->pref['coinbase']['account']);
				$gateway->setSecret($this->pref['coinbase']['secret']);
				$gateway->setApiKey($this->pref['coinbase']['api_key']);
				break;

			case "paypal":
				/** @var \Omnipay\PayPal\ExpressGateway $gateway */
				$gateway = Omnipay::create('PayPal_Express');

				if(!empty($this->pref['paypal']['testmode']))
				{
					$gateway->setTestMode(true);
				}

				$gateway->setUsername($this->pref['paypal']['username']);
				$gateway->setPassword($this->pref['paypal']['password']);
				$gateway->setSignature($this->pref['paypal']['signature']);
				break;

			case "paypal_rest":
				/** @var \Omnipay\PayPal\RestGateway $gateway */
				$gateway = Omnipay::create('PayPal_Rest');

				if(!empty($this->pref['paypal_rest']['testmode']))
				{
					$gateway->setTestMode(true);
				}

				$gateway->setClientId($this->pref['paypal_rest']['clientId']);
				$gateway->setSecret($this->pref['paypal_rest']['secret']);
				break;

			case "bank_transfer":

				$mode = 'halt';
				$this->setMode('return');

				$message = e107::getParser()->toHtml($this->pref['bank_transfer']['details'],true);


				break;


			default:
				return false;
		}

		$cardInput = null;
		$data = $this->getCheckoutData();

		if(empty($data['items']))
		{
			e107::getMessage()->addError("Shopping Cart Empty",'vstore');
			return false;
		}
		else
		{
			$items = array();

			foreach($data['items'] as $var)
			{
				$itemvarstring = '';
				if (!empty($var['cart_item_var_keys']))
				{
					$itemvarkeys = explode(',', $var['cart_item_var_keys']);
					$itemvarvalues = explode(',', $var['cart_item_var_values']);
	
					foreach($itemvarkeys as $i => $key)
					{
						$itemvarstring .= ($itemvarstring ? ' / ' : '') . vstore::getItemVarString($key, $itemvarvalues[$i]);
					}
				}
					


				$items[] = array(
					'id'          => $var['item_id'],
					'name'        => $var['item_code'],
					'price'       => $var['item_price'],
					'description' => $var['item_name'],
					'quantity'    => $var['cart_qty'],
					'file'        => $var['item_download'],
					'vars'		  => $itemvarstring,
				);
			}
		}

		if($mode === 'halt') // eg. bank-transfer.
		{
			$transID = null;
			$transData = null;
			$this->saveTransaction($transID, $transData, $items);
			$this->resetCart();

			if(!empty($message))
			{
				e107::getMessage()->addSuccess($message,'vstore');
			}

			unset($_SESSION['vstore']['_data']);

			// Forcethe browser window to refresh the cart menu
			e107::js('inline-footer', '$(function(){ vstoreCartRefresh(); });');
			return null;
		}
		elseif($mode === 'init')
		{
			$method = $gateway->supportsAuthorize() ? 'authorize' : 'purchase';

			$_data = array(
				'cancelUrl'      => e107::url('vstore', 'cancel', null, array('mode' => 'full')),
				'returnUrl'      => e107::url('vstore', 'return', null, array('mode' => 'full')),
				'amount'         => $data['totals']['cart_grandTotal'],
				'shippingAmount' => $data['totals']['cart_shippingTotal'],
				'currency'       => $data['currency'],
				'items'          => $items,
				'transactionId'  => $this->getCheckoutData('id'),
				'clientIp'       => USERIP,
			);

			$_SESSION['vstore']['_data'] = $_data;
		}
		// Mode 'return'.
		else
		{
			$method = 'completePurchase';

			if ($gateway->supportsAuthorize() && $gateway->supportsCompleteAuthorize())
			{
				$method = 'completeAuthorize';
			}

			// Get stored data.
			$_data = $_SESSION['vstore']['_data'];
			// Add PayerID, paymentId, token, etc...
			$_data = array_merge($_data, $this->get);
		}

		try
		{
			/** @var \Omnipay\Common\Message\AbstractResponse $response */
			$response = $gateway->$method($_data)->send();
		} catch(Exception $e)
		{
			$message = $e->getMessage();
			e107::getMessage()->addError($message,'vstore');
			return false;
		}

		if($response->isRedirect())
		{
			// Get transaction ID from the Authorize response.
			if ($transID = $response->getTransactionReference())
			{
				// Store transaction ID for later use.
				$_SESSION['vstore']['_data']['transactionReference'] = $transID;
			}

			// Redirect to offsite payment gateway.
			$response->redirect();
		}
		elseif($response->isSuccessful())
		{
			$transData = $response->getData();
			$transID = $response->getTransactionReference();
			$message = $response->getMessage();

			e107::getMessage()->addSuccess($message,'vstore');

			$this->saveTransaction($transID, $transData, $items);
			$this->resetCart();

			unset($_SESSION['vstore']['_data']);
		}
		else
		{
			$message = $response->getMessage();
			e107::getMessage()->addError($message,'vstore');
		}
	}


	private function getOrderRef($id,$firstname,$lastname)
	{
		$text = substr($firstname,0,2);
		$text .= substr($lastname,0,2);
	//	$text .= date('Y');
		$text .= e107::getParser()->leadingZeros($id,6);

		return strtoupper($text);

	}


	private function saveTransaction($id, $transData, $items)
	{

		if(intval($transData['L_ERRORCODE0']) == 11607) // Duplicate REquest.
		{
			return false;
		}


        $shippingData = $this->getShippingData();
		$cartData  = $this->getCheckoutData();

		$insert =  array(
		    'order_id'            => 0,
		    'order_date'          => time(),
		    'order_session'       => $cartData['id'],
		    'order_e107_user'     => USERID,
		    'order_cust_id'       => '',
			'order_status'        => 'N' // New
		 );

		 $insert['order_items'] = json_encode($items, JSON_PRETTY_PRINT);

		foreach($shippingData as $fld=>$val)
		{
			$insert[$fld]    = $val;
		}

		$insert['order_pay_gateway']    = $this->getGatewayType();
		$insert['order_pay_status']     = empty($transData) ? 'incomplete' : 'complete';
		$insert['order_pay_transid']    = $id;
		$insert['order_pay_amount']     = $cartData['totals']['cart_grandTotal'];
		$insert['order_pay_shipping']   = $cartData['totals']['cart_shippingTotal'];
		$insert['order_pay_rawdata']    = json_encode($transData,JSON_PRETTY_PRINT);

		$mes = e107::getMessage();

	//	e107::getDebug()->log($insert);

		$nid = e107::getDb()->insert('vstore_orders',$insert);
		if( $nid !== false)
		{
			$refId = $this->getOrderRef($nid,$insert['order_ship_firstname'],$insert['order_ship_lastname']);
			$mes->addSuccess("Your order <b>#".$refId."</b> is complete",'vstore');
			$this->updateInventory($insert['order_items']);
			$this->emailCustomer('default', $refId, $insert);

			if (!empty($transData))
			{
				$this->setCustomerUserclass(USERID, $items);
			}
	
		}
		else
		{
			$mes->addError("Unable to save transaction");
			$this->emailCustomer('error', null, $insert);

		}


	}

	/**
	 * Send an email to the customer with a template depending on the order_status
	 * This is used on the sales admin pages, when changing the order_status
	 * 
	 * @param int $order_id
	 * @return void
	 */
	public function emailCustomerOnStatusChange($order_id)
	{
		if (intval($order_id) <= 0)
		{
			e107::getMessage()->addDebug('No order_id supplied or order_id "'.intval($order_id).'" is invalid!', 'vstore');
			return;
		}

		$sql = e107::getDB();

		$order = $sql->retrieve('vstore_orders', '*', 'order_id='.intval($order_id));

		if ($order && is_array($order))
		{
			$order['order_items'] = json_decode($order['order_items'], true);
			$refId = $this->getOrderRef($order['order_id'], $order['order_ship_firstname'], $order['order_ship_lastname']);

			$this->emailCustomer(strtolower($this->getStatus($order['order_status'])), $refId, $order);
		}
		else
		{
			e107::getMessage()->addDebug('No order with given order_id "'.intval($order_id).'" found!', 'vstore');
		}

	}

	/**
	 * Add userclass to customer
	 *
	 * @param int $userid Userid of the customer
	 * @param array $items Array of order_items
	 * @return void
	 */
	static function setCustomerUserclass($userid, $items)
	{
		$uc_global = e107::pref('vstore', 'customer_userclass');
		if ($uc_global == -1)
		{
			$usr = e107::getSystemUser($userid, true);
			// set userclass as defined in product
			if (!empty($items) && is_array($items))
			{
				$sql = e107::getDb();
				foreach ($items as $item) {
					$uc = $sql->retrieve('vstore_items', 'item_userclass', 'item_id='.intval($item['id']));
					if ($uc > 0 && $uc != 255)
					{
						$usr->addClass($uc);
					}
				}
			}
		}
		elseif ($uc_global != 255)
		{
			$usr = e107::getSystemUser($userid, true);
			// all classes except No One (inactive)
			$usr->addClass($uc_global);
		}
	}

	/**
	 * Return the userclasses that will be added to customer
	 *
	 * @param array $items array of order_items
	 * @return bool/string false, if no userclass, otherwise comma-separated list of userclasses
	 */
	static function getCustomerUserclass($items)
	{
		$uc_global = e107::pref('vstore', 'customer_userclass');
		if ($uc_global == -1)
		{
			// set userclass as defined in product
			if (!empty($items) && is_array($items))
			{
				$sql = e107::getDb();
				$ucs = array();
				foreach ($items as $item) {
					$uc = $sql->retrieve('vstore_items', 'item_userclass', 'item_id='.intval($item['id']));
					if ($uc > 0 && $uc != 255)
					{
						$ucs[] = $uc;
					}
				}
				$ucs = array_unique($ucs);
				if ($ucs && count($ucs))
				{
					return implode(',', $ucs);
				}
			}
		}
		elseif ($uc_global != 255)
		{
			// all classes except No One (inactive)
			return ''.$uc_global;
		}
		return false;
	}


	/**
	 * TODO - add a pref (multilan) containing the entire template which can be edited from within the admin area.
	 * TODO - fallback to template in file if pref is empty.
	 */
	private function getEmailTemplate($type='default')
	{
		if (empty($type))
		{
			$type = 'default';
		}
		$template = e107::pref('vstore', 'email_templates');
		if (isset($template[$type]['active']) && ($template[$type]['active'] ? false : true))
		{
			return '';
		}
		if (empty($template[$type]['template']))
		{
			$template = e107::getTemplate('vstore', 'vstore_email', $type);
			if (empty($template))
			{
				return '';
			}
		}
		else
		{
			$template = str_ireplace(array('[html]', '[/html'), '', $template[$type]);
		}
		return $template;
	}




	function emailCustomer($templateKey='default', $ref, $insert=array())
	{
		$tp = e107::getParser();
		$template = $this->getEmailTemplate($templateKey);

		if (empty($template))
		{
			// No template available... No mail to send ...
			e107::getMessage()->addDebug('No template found or template is empty!', 'vstore');
			return;
		}

		$insert['order_ref'] = $ref;

		$sc = new vstore_plugin_shortcodes;
		$sc->setVars($insert);

		$subject    = "Your Order #[x] at ".SITENAME; //todo add to template

		$email      = $insert['order_ship_email'];
		$name       = $insert['order_ship_firstname']." ".$insert['order_ship_lastname'];;

		$eml = array(
					'subject' 		=> $tp->lanVars($subject, array('x'=>$ref)),
					'sender_email'	=> e107::pref('vstore','sender_email'),
					'sender_name'	=> e107::pref('vstore','sender_name'),
			//		'replyto'		=> $email,
					'html'			=> true,
					'template'		=> 'default',
					'body'			=> $tp->parseTemplate($template,true,$sc)
		);

	//	$debug = e107::getEmail()->preview($eml);
	//	e107::getDebug()->log($debug);



		e107::getEmail()->sendEmail($email, $name, $eml);

	}





	private function updateInventory($json)
	{
		$sql = e107::getDb();
		$arr = json_decode($json,true);

		foreach($arr as $row)
		{
			if(!empty($row['quantity']) && !empty($row['id']) && !empty($row['name']))
			{
				$curQuantity = $sql->retrieve('vstore_items', 'item_inventory', 'item_id='.intval($row['id']).' AND item_code="'.$row['name'].'"');
				if ($curQuantity > 0)
				{
					$reduceBy = intval($row['quantity']);
					if ($reduceBy > $curQuantity)
					{
						$reduceBy = $curQuantity;
					}
					if($sql->update('vstore_items','item_inventory = item_inventory - '.$reduceBy.' WHERE item_id='.intval($row['id']).' AND item_code="'.$row['name'].'" LIMIT 1'))
					{
						e107::getMessage()->addDebug("Reduced inventory of ".$row['name']." by ".$row['quantity']);
					}
					else
					{
						e107::getMessage()->addDebug("Was UNABLE to reduce inventory of ".$row['name']." (".$row['id'].") by ".$row['quantity']);
					}
				}
				else
				{
					e107::getMessage()->addDebug("Unlimited item not reduced: ".$row['name']." (".$row['id'].")");
				}
		}
		}

	}


	private function getGatewayIcon($type='')
	{
		$text = !empty(self::$gateways[$type]) ? self::$gateways[$type]['icon'] : '';
		return e107::getParser()->toGlyph($text, array('size'=>'5x'));

	}


	public static function getGatewayTitle($key)
	{
		return self::$gateways[$key]['title'];

	}


	private function getGatewayType()
	{
		return $_SESSION['vstore']['gateway']['type'];
	}


	private function setGatewayType($type='')
	{
		 $_SESSION['vstore']['gateway']['type'] = $type;
	}


	
	public function setPerPage($num)
	{
		$this->perPage = intval($num);	
	}

	protected function updateCart($type = 'modify', $array)
	{
		$sql = e107::getDb();
		
		if($type == 'modify')
		{
			foreach($array as $id=>$val)
			{

				$itemid = (int) $val['id'];
				$qty = (int) $val['qty'];
				$itemvars = $val['vars'];
				if (!empty($itemvars))
				{
					list($itemkeys, $itemvalues) = explode('|', $itemvars);
					$itemkeys = explode(',', $itemkeys);
					$itemvalues = explode(',', $itemvalues);
					$itemvars = array();
					foreach ($itemkeys as $k=>$v) {
						$itemvars[$v] = $itemvalues[$k];
					}
				}

				$inStock = $this->getItemInventory($itemid, $itemvars);
				if ($qty > $inStock && $inStock >= 0)
				{
					$qty = $inStock;
					$itemname = $sql->retrieve('vstore_items', 'item_name', 'item_id=' . $itemid);
					$itemvarstring = '';
					if (!empty($itemvars))
					{
						foreach ($itemkeys as $k => $v) {
							$itemvarstring .= ($itemvarstring ? ' / ' : '') . vstore::getItemVarString($v, $itemvalues[$k]);
						}
						$itemvarstring = " ({$itemvarstring})";
					}
					$itemname .= $itemvarstring;
					e107::getMessage()->addWarning('The entered quantity for "'.$itemname.'" exceeds the number of items in stock!<br/>The quantity has been adjusted!', 'vstore');
				}

				$sql->update('vstore_cart', 'cart_qty = '.intval($qty).' WHERE cart_id = '.intval($id).' LIMIT 1');				
			}
		}
		
		if($type == 'remove')
		{
			foreach($array as $id=>$qty)
			{
				$sql->delete('vstore_cart', 'cart_id = '.intval($id).' LIMIT 1');				
			}	
		}	

		return null;
	}


	protected function resetCart()
	{
		// Delete cart from database
		e107::getDb()->delete('vstore_cart', 'cart_id='.$_COOKIE["cartId"]);
		$_COOKIE["cartId"] = false;
		cookie("cartId", null, time()-3600);
		$this->cartId = null;
		e107::getDebug()->log("Destroying CartID");
		return null;
	}



	protected function getCartId()
	{
		if(!empty($_COOKIE["cartId"]))
		{
			return $_COOKIE["cartId"];
		}
		else // There is no cookie set. We will set the cookie and return the value of the users session ID
		{
			e107::getDebug()->log("Renewing CartID");
			$value = md5(session_id().time());

			cookie("cartId", $value,  time() + ((3600 * 24) * 2));

			return $value;
		}
	}


	public function categoryList($parent=0,$np=false)
	{
		
		$this->from = vartrue($this->get['frm'],0);

		$query = 'SELECT * FROM #vstore_cat WHERE cat_active=1 AND cat_parent = '.$parent.' ORDER BY cat_order LIMIT '.$this->from.",".$this->perPage;
		if(!$data = e107::getDb()->retrieve($query, true))
		{
			return e107::getMessage()->addInfo('No categories available!', 'vstore')->render('vstore');
			//return false;
		}


	//	$data = $this->categories;
		
		$tp = e107::getParser();


		$text = '
			<div class="row">
		       ';

			
		$template = '
		{SETIMAGE: w=320&h=250&crop=1}
		<div class="vstore-category-list col-sm-4 col-lg-4 col-md-4">
			<div class="thumbnail">
				<a href="{CAT_URL}">{CAT_PIC}</a>
				<div class="caption text-center">
					<h4><a href="{CAT_URL}">{CAT_NAME}</a></h4>
					<p class="cat-description"><small>{CAT_DESCRIPTION}</small></p>
					
				</div>
			</div>
		</div>';
					
		$this->sc->setCategories($this->categories);
		
		foreach($data as $row)
		{
			$this->sc->setVars($row);
			$text .= $tp->parseTemplate($template, true, $this->sc);		
		}
		
		
		
		$text .= '		
			</div>
		';


		if($np === true)
		{
			$nextprev = array(
					'tmpl'			=>'bootstrap',
					'total'			=> $this->categoriesTotal,
					'amount'		=> intval($this->perPage),
					'current'		=> $this->from,
					'url'			=> e107::url('vstore','base')."?frm=[FROM]"
			);
	
			global $nextprev_parms;
		
			$nextprev_parms  = http_build_query($nextprev,false,'&'); // 'tmpl_prefix='.deftrue('NEWS_NEXTPREV_TMPL', 'default').'&total='. $total_downloads.'&amount='.$amount.'&current='.$newsfrom.$nitems.'&url='.$url;
	
			$text .= $tp->parseTemplate("{NEXTPREV: ".$nextprev_parms."}",true);
		}



		return $text;
		

	}
		
	
	public function productList($category=1,$np=false,$templateID = 'list')
	{



//		if(!$data = e107::getDb()->retrieve('SELECT SQL_CALC_FOUND_ROWS * FROM #vstore_items WHERE cat_active=1 AND item_active=1 AND item_cat = '.intval($category).' ORDER BY item_order LIMIT '.$this->from.','.$this->perPage, true))
		if(!$data = e107::getDb()->retrieve('SELECT SQL_CALC_FOUND_ROWS *, cat_active FROM #vstore_items LEFT JOIN #vstore_cat ON (item_cat = cat_id) WHERE cat_active=1 AND item_active=1 AND item_cat = '.intval($category).' ORDER BY item_order LIMIT '.$this->from.','.$this->perPage, true))
		{

			return e107::getMessage()->addInfo("No products available in this category",'vstore')->render('vstore');
		}
		
		$count = e107::getDb()->foundRows();

		$categoryRow = $this->categories[$category];
		
		$tp = e107::getParser();
		$this->sc->setVars($categoryRow);
		$template = e107::getTemplate('vstore','vstore', $templateID);

	//	e107::getDebug()->log($this->sc);

		$text = $tp->parseTemplate($template['start'], true, $this->sc);
		
		foreach($data as $row)
		{
			$id = $row['item_cat'];
			$row['cat_id'] = $row['item_cat'];
			$row['cat_sef'] = $this->categories[$id]['cat_sef'];
			$row['item_sef'] = eHelper::title2sef($row['item_name'],'dashl');
			
			$this->sc->setVars($row);
			$text .= $tp->parseTemplate($template['item'], true, $this->sc);
		}

		$text .= $tp->parseTemplate($template['end'], true, $this->sc);

		if($np === true)
		{
			$nextprev = array(
					'tmpl'			=>'bootstrap',
					'total'			=> $count,
					'amount'		=> intval($this->perPage),
					'current'		=> $this->from,
					'url'			=> e107::url('vstore','base')."?frm=[FROM]"
			);
	
			global $nextprev_parms;
		
			$nextprev_parms  = http_build_query($nextprev,false,'&'); // 'tmpl_prefix='.deftrue('NEWS_NEXTPREV_TMPL', 'default').'&total='. $total_downloads.'&amount='.$amount.'&current='.$newsfrom.$nitems.'&url='.$url;
	
			$text .= $tp->parseTemplate("{NEXTPREV: ".$nextprev_parms."}",true);
		}


		return $text;
		

	}	
	
	
	
	protected function productView($id=0)
	{
		if(!$row = e107::getDb()->retrieve('SELECT * FROM #vstore_items WHERE item_active=1 AND item_id = '.intval($id).'  LIMIT 1',true))
		{
			e107::getMessage()->addInfo("No products available in this category",'vstore');
			return null;
		}
		
		$this->item = $row[0];
		
		$tp = e107::getParser();
		$frm = e107::getForm();
		
		$catid = $this->item['item_cat'];
		$data = array_merge($row[0],$this->categories[$catid]);
		
	//	print_a($data);
		
		$this->sc->setVars($data);
		$this->sc->wrapper('vstore/item');

        $tmpl = e107::getTemplate('vstore');


        $text = $tmpl['item']['main'];

		$tabData = array();

		if(!empty($data['item_details']))
		{
			$tabData['details'] =  array('caption'=>'Details', 'text'=>$tmpl['item']['details']);
		}

		if($media = e107::unserialize($data['item_pic']))
		{
			foreach($media as $v)
			{
				if($tp->isVideo($v['path']))
				{
					$tabData['videos']  = array('caption'=>'Videos', 'text'=> $tmpl['item']['videos']);
					break;
				}
			}
		}

		if(!empty($data['item_reviews']))
		{
			$tabData['reviews'] = array('caption'=>'Reviews', 'text'=> $tmpl['item']['reviews']);
		}
		
		
		if(!empty($data['item_related']))
		{
			$tmp = e107::unserialize($data['item_related']);
			if(!empty($tmp['src']))
			{	
				$tabData['related']	= array('caption'=>varset($tmp['caption'],'Related'), 'text'=> $tmpl['item']['related']);
			}		
		}

		if(!empty($data['item_files']))
		{
			$tmp = e107::unserialize($data['item_files']);
			if(!empty($tmp[0]['path']))
			{
				$tabData['files']		= array('caption'=>'Downloads', 'text'=> $tmpl['item']['files']);
			}
		}
		
		//if(!empty($data['cat_info']))
		if (!empty(e107::pref('vstore', 'howtoorder')))
		{
			$tabData['howto']		= array('caption'=>'How to Order', 'text'=> $tmpl['item']['howto']);
		}

		if(!empty($tabData))
		{
			$text .= $frm->tabs($tabData);
		}
	//	print_a($text);
		$parsed = $tp->parseTemplate($text, true, $this->sc);

		return $parsed;
	}
	
	
	protected function cartData()
	{

	}
	
	
	protected function addToCart($id, $itemvars=false)
	{
		if (USERID === 0){
			// Allow only logged in users to add items to the cart
			e107::getMessage()->addError('You must be logged in before adding products to the cart!', 'vstore');
			return false;
		}

		$itemvars = $this->fixItemVarArray($itemvars);
		$sql = e107::getDb();

		$where = 'cart_session = "'.$this->cartId.'" AND cart_item = ' . intval($id);
		if (is_array($itemvars))
		{
			$where .= ' AND cart_item_var_keys = "'.implode(',', array_keys($itemvars)).'"';
		}

		
		// Item Exists. 
		if ($sql->select('vstore_cart', 'cart_qty, cart_item_var_keys, cart_item_var_values', $where . ' LIMIT 1'))
		{
			$cart = $sql->fetch();

			$inventory = $this->getItemInventory(intval($id), $itemvars);

			if ($inventory && (intval($cart['cart_qty']) + 1) <= $inventory)
			{
				if($sql->update('vstore_cart', 'cart_qty = cart_qty +1 WHERE ' . $where))
				{
					return true;
				}
			}
			e107::getMessage()->addWarning('Quantity of selected product exceeds the number of items in stock!<br/>The quantity has been adjusted!', 'vstore');
			return false;
		}

		
		$insert = array(
			'cart_id' 			=> 0,
			'cart_session' 		=> $this->cartId,
	  		'cart_e107_user'	=> USERID,
	  		'cart_status'		=> '',
			'cart_item'			=> intval($id),
			'cart_item_var_keys'	=> $itemvars ? implode(',', array_keys($itemvars)) : '',
			'cart_item_var_values'	=> $itemvars ? implode(',', array_values($itemvars)) : '',
	  		'cart_qty'			=> 1
  		);

		// Add new Item. 
		return $sql->insert('vstore_cart', $insert);
	
	}

	private function fixItemVarArray($itemvars)
	{
		if (!is_array($itemvars))
		{
			return false;
		}
		$result = array();
		if (array_key_exists(0, $itemvars))
		{
			foreach ($itemvars as $value) {
				list($id, $name) = explode('-', $value);
				$result[$id] = $name;
			}
		}
		else
		{
			$result = $itemvars;
		}
		ksort($result);
		return $result;
	}

	private function getItemInventory($itemid, $itemvars=false)
	{

		$itemvars = $this->fixItemVarArray($itemvars);

		$sql = e107::getDb();

		if ($itemvars && count($itemvars))
		{
			$itemvarkeys = array_values($itemvars) ;
			$where = 'item_id=' . intval($itemid);

			if ($sql->select('vstore_items', 'item_vars_inventory', $where))
			{
				$inventory = array_shift($sql->fetch());

				$inventory = e107::unserialize($inventory);

				if (count($itemvarkeys) == 1)
				{
					$qty = (int) $inventory[$itemvarkeys[0]];
				}
				elseif (count($itemvarkeys) == 2)
				{
					$qty = (int) $inventory[$itemvarkeys[0]][$itemvarkeys[1]];
				}
				else
				{
					e107::getMessage()->addDebug('Invalid number of item_vars!', 'vstore');
					return 0;
				}

				if ($qty < 0){
					return 9999999;
				}
				return $qty;
			}

			e107::getMessage()->addDebug('Item not found!', 'vstore');
			return 0;

		}
		else
		{
			$inventory = (int) $sql->retrieve('vstore_items', 'item_inventory', 'cart_item = '.intval($id));
			if ($inventory < 0){
				return 9999999;
			}
			return $inventory;
		}

	}


	public function getCartData()
	{
		return e107::getDb()->retrieve('SELECT c.*,i.* FROM #vstore_cart AS c LEFT JOIN #vstore_items as i ON c.cart_item = i.item_id WHERE c.cart_session = "'.$this->cartId.'" AND c.cart_status ="" ', true);
	}



	protected function cartView()
	{
		if(!$data = $this->getCartData() )
		{
			return e107::getMessage()->addInfo("Your cart is empty.",'vstore')->render('vstore');


		}

		$tp = e107::getParser();
		$frm = e107::getForm();
		
		$text = $frm->open('cart','post', e107::url('vstore','cart'));
		
		$text .= e107::getMessage()->render('vstore');

		$text .= '
		
		
		    <div class="row">
		        <div class="col-sm-12 col-md-12">
		            <table class="table table-hover cart">
		                <thead>
		                    <tr>
		                        <th>Product</th>
		                         <th> </th>
		                        <th>Quantity</th>
		                        <th class="text-right">Price</th>
		                        <th class="text-right">Total</th>

		                    </tr>
		                </thead>
		                <tbody>';
			
			
			
			$template = '
						{SETIMAGE: w=72&h=72&crop=1}
		                    <tr>
		                        <td>
		                        <div class="media">
		                        	<div class="media-left">
		                            <a href="{ITEM_URL}">{ITEM_PIC: class=media-object}</a>
		                           </div>
		                             <div class="media-body">
		                                <h4 class="media-heading"><a href="{ITEM_URL}">{ITEM_NAME}</a></h4>
		                                <h5 class="media-heading"> by <a href="#">Brand name</a></h5>
		                                {ITEM_VAR_STRING}
		                            </div>
		                        </div></td>
		                         <td class="col-sm-1 col-md-1 text-center">{CART_REMOVEBUTTON}</td>
		                        <td class="col-sm-1 col-md-1 text-center">{CART_VARS}{CART_QTY=edit} </td>
		                        <td class="col-sm-1 col-md-1 text-right">{CART_PRICE}</td>
		                        <td class="col-sm-1 col-md-1 text-right"><strong>{CART_TOTAL}</strong></td>

		                    </tr>
		           ';
			
			
			
			
			
			$subTotal 		= 0;
			$shippingTotal 	= 0;
			$checkoutData = array();

			$checkoutData['id'] = $this->getCartId();

			foreach($data as $row)
			{
			
				$subTotal += ($row['cart_qty'] * $row['item_price']);	
				$shippingTotal	+= ($row['cart_qty'] * $row['item_shipping']);	
						
				$this->sc->setVars($row);
				$checkoutData['items'][] = $row;
				$text .= $tp->parseTemplate($template, true, $this->sc);	
			}
			
			$grandTotal = $subTotal + $shippingTotal;
			$totals = array('cart_subTotal' => $subTotal, 'cart_shippingTotal'=>$shippingTotal, 'cart_grandTotal'=>$grandTotal);

			$this->sc->setVars($totals);

			$checkoutData['totals'] = $totals;

			
			$footer = '     
		                   <tr>
		                   <td>   </td>
		                        <td colspan="2"><div class="text-right" ></div></td>
		                        <td><h5>Subtotal</h5></td>
		                        <td class="text-right"><h5><strong>{CART_SUBTOTAL}</strong></h5></td>

		                    </tr>
		                    <tr>

								<td>   </td>
		                        <td colspan="3" class="text-right"><h5>Estimated shipping</h5></td>
		                        <td class="text-right"><h5><strong>{CART_SHIPPINGTOTAL}</strong></h5></td>


		                    </tr>
		                    <tr>
		                        <td>   </td>
		                        <td>   </td>
								 <td>   </td>
		                        <td><h3>Total</h3></td>
		                        <td class="text-right"><h3><strong>{CART_GRANDTOTAL}</strong></h3></td>


		                    </tr>
		                    <tr>
		                        <td colspan="2">
		                       {CART_CONTINUESHOP}</td>
		                        <td colspan="3" class="text-right">
		                        {CART_CHECKOUT_BUTTON}
		                        </td>

		                    </tr>
		                </tbody>
		            </table>
		        </div>
		    </div>
	
		';
		
		
		$text .= $tp->parseTemplate($footer, true, $this->sc);	
		
		$text .= $frm->close();

		$this->setCheckoutData($checkoutData);

		return $text;
	//	$ns->tablerender("Shopping Cart",$text,'vstore-view-cart');
		

	}
	


	private function setCheckoutData($data=array())
	{
		$_SESSION['vstore']['checkout'] = $data;
		$_SESSION['vstore']['checkout']['currency'] = $this->currency;
	}

	private function setShippingData($data=array())
	{
		$pref = e107::pref('vstore');
		$fields = self::getShippingFields();
		$order_ship_add_fields = array();
		foreach($fields as $fld)
		{
			if (substr($fld, 0, strlen('add_field')) == 'add_field')
			{
				$fieldid = intval(substr($fld, strlen('add_field')));
				$caption = strip_tags(varset($pref['additional_fields'][$fieldid]['caption'][e_LANGUAGE], 'Field '.$fieldid));
				if ($pref['additional_fields'][$fieldid]['type'] == 'text')
				{
					$order_ship_add_fields[] = $caption . ': ' . trim(strip_tags($data[$fld]));
				}
				elseif ($pref['additional_fields'][$fieldid]['type'] == 'checkbox')
				{
					$order_ship_add_fields[] = $caption . ': ' . (vartrue($data[$fld], false) ? 'Checked' : 'Unchecked');
				}
			}
			else
			{
				$_SESSION['vstore']['shipping']['order_ship_'.$fld] = trim(strip_tags($data[$fld]));
			}
		}
		if (varset($order_ship_add_fields))
		{
			if ($_SESSION['vstore']['shipping']['order_ship_notes'] != '')
			{
				$_SESSION['vstore']['shipping']['order_ship_notes'] .= "\n\n";
			}
			$_SESSION['vstore']['shipping']['order_ship_notes'] .= "Additional fields:\n";
			$_SESSION['vstore']['shipping']['order_ship_notes'] .= implode("\n", $order_ship_add_fields);
		}


	}

	private function getShippingData($data=array())
	{
		return $_SESSION['vstore']['shipping'];
	}


	private function getCheckoutData($id=null)
	{
		if(!empty($id))
		{
			return $_SESSION['vstore']['checkout'][$id];
		}

		return $_SESSION['vstore']['checkout'];
	}
	
	
	private function downloadFile($item_id=null)
	{
		if ($item_id == null || intval($item_id) <= 0)
		{
			e107::getMessage()->addDebug('Download id "'.intval($item_id).'" to download missing or invalid!','vstore');
		}

		if (USERID === 0)
		{
			return false;
		}

		if (!$this->hasItemPurchased($item_id))
		{
			return false;
		}

		$filepath = e107::getDb()->retrieve('vstore_items', 'item_download', 'item_id='.intval($item_id));

		if (varset($filepath))
		{
			e107::getFile()->send($filepath); 
		}
		else
		{
			e107::getMessage()->addError('Download id  "'.intval($item_id).'" doesn\'t contain a file to download!', 'vstore');
		}

	}
	
	/**
	 * Check if the current user has purchased (and payed) given item_id
	 *
	 * @param int $item_id
	 * @return boolean
	 */
	private function hasItemPurchased($item_id)
	{
		if ($item_id == null || intval($item_id)<=0)
		{
			e107::getMessage()->addDebug('Download id "'.intval($item_id).'" missing or invalid!','vstore');
			return false;
		}

		if (USERID === 0)
		{
			e107::getMessage()->addError('You need to login to download the file!', 'vstore');
			return false;
		}
		$sql = e107::getDb();
		$order = $sql->select('vstore_orders', '*', 'order_e107_user='.USERID.' AND order_items LIKE \'%"id": "'.intval($item_id).'",%\' ORDER BY order_id DESC');


		if (!$order)
		{
			e107::getMessage()->addError('We were unable to find your order and therefore the download has been denied!', 'vstore');
			return false;
		}

		while($order = $sql->fetch())
		{
			if ($order['order_status'] == 'C')
			{
				// Status Completed = Payment OK, regardless of the orde_pay_status (e.g. in case of banktransfer)
				return true;
			}
			elseif ($order['order_pay_status'] == 'complete' && $order['order_status'] == 'N')
			{
				// If order_status = New and pay_status = complete (e.g. in case of paypal payment)
				return true;
			}
		}
		// Order not completed or payment not complete + order_status = New 
		e107::getMessage()->addError('Your order is still in a state ('.vstore::getStatus($order['order_status']).') which doesn\'t allow to download the file!', 'vstore');
		return false;
	}
	

	public static function getItemVarString($itemvarid, $itemvarvalue)
	{
		if ($itemvarid == null || $itemvarid <= 0)
		{
			return '';
		}
		$itemvar = e107::getDb()->retrieve('vstore_items_vars', 'item_var_name, item_var_attributes', 'item_var_id='.$itemvarid);

		if (!$itemvar)
		{
			return '';
		}

		$attr = e107::unserialize($itemvar['item_var_attributes']);

		$text = $itemvar['item_var_name'];

		$value = $itemvarvalue;

		if (is_array($attr))
		{
			$frm = e107::getForm();
			foreach ($attr as $row) {
				if ($frm->name2id($row['name']) == $itemvarvalue)
				{
					$value = $row['name'];
				}
			}
		}

		return "{$text}: {$value}";
	}
	
	
}


