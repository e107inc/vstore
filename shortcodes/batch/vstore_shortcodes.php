<?php
	class plugin_vstore_vstore_shortcodes extends e_shortcode
	{

		protected $vpref = array();
		protected $videos = array();
		//protected $symbols = array();
		protected $curSymbol = null;
		protected $currency = null;
		protected $displayCurrency = false;
		protected $categories = array();
		public $captionOutOfStock = LAN_VSTORE_003; // 'Out of Stock';
		protected $halt = false;

		public function __construct()
		{
			$this->vpref = e107::pref('vstore');

			$currency = !empty($this->vpref['currency']) ? $this->vpref['currency'] : 'USD';

			$this->curSymbol = vstore::getCurrencySymbol($currency);
			$this->currency = ($this->displayCurrency === true) ? $currency : '';

		}

		public function getCurrencySymbol()
		{
			return vstore::getCurrencySymbol($this->currency);
		}

		function format_amount($amount)
		{
			$format = varset($this->vpref['amount_format'], 0);
			$amount = floatval($amount);
			if ($format == 1)
			{
				return number_format($amount, 2).'&nbsp;'.$this->curSymbol/*.$this->currency*/;
			}
			else
			{
				return /*$this->currency.*/$this->curSymbol.'&nbsp;'.number_format($amount, 2);
			}
		}

		function format_address($address, $extended = false)
		{
			if (empty($address)) return '';
			if (!is_array($address)) $address = e107::unserialize($address);
			if (empty($address)) return '';

			$text = $address['firstname'] . ' ' . $address['lastname']. '<br />' .
				(!empty($address['company']) ? $address['company'].'<br />' : '').
				$address['address'] .'<br />'.
				$address['city'] . ' ' . $address['zip'] .'<br />'.
				e107::getForm()->getCountry($address['country']);

			if ($extended)
			{
				$text .= '<br />' .
					(!empty($address['vat_id']) ? 'VAT ID: ' . $address['vat_id'] . '<br />' : '').
					(!empty($address['taxcode']) ? 'Taxcode: ' . $address['taxcode'] . '<br />' : '').
					(!empty($address['email']) ? 'Email: ' . $address['email'] . '<br />' : '').
					(!empty($address['phone']) ? 'Phone: ' . $address['phone'] . '<br />' : '').
					(!empty($address['fax']) ? 'Fax: ' . $address['fax'] : '');
			}

			return $text;
		}

		function sc_order_actions($parm=null)
		{
			if (!USER) return '';
			$key = '';

			if (!empty($parm))
			{
				$key = array_keys($parm);
				if ($key) $key = strtolower($key[0]);
			}

			$cancellable = in_array($this->var['order_status'], array('N', 'P', 'H'));

			if (!empty($key))
			{
				if ($key == 'cancel' && $cancellable)
				{
					$text = sprintf('<a href="%s" class="btn btn-warning">%s</a>',
						e107::url('vstore', 'dashboard_action', array('dash' => 'orders', 'action' => 'cancel', 'id' => $this->var['order_invoice_nr'])),
						'Cancel order');
				}
				return $text;
			}

			$actions = array(
				sprintf('<a href="%s">%s</a>',
					e107::url('vstore', 'dashboard_action', array('dash' => 'orders', 'action' => 'view', 'id' => $this->var['order_invoice_nr'])),
					'View details')
			);

			if ($cancellable)
			{
				$actions[] = sprintf('<a href="%s">%s</a>',
					e107::url('vstore', 'dashboard_action', array('dash' => 'orders', 'action' => 'cancel', 'id' => $this->var['order_invoice_nr'])),
					'Cancel order');
			}

			return e107::getForm()->button('order_actions', $actions, 'dropdown', 'Actions', array('class' => 'btn-default'));
		}

		function sc_order_data($parm = null)
		{
			if (empty($parm)) return '';

			$key = array_keys($parm);
			if ($key) $key = strtolower($key[0]);
			$area = '';

			if (substr($key, 0, 5) == 'ship_' || substr($key, 0, 5) == 'cust_')
			{
				if(substr($key, 0, 5) == 'ship_') $area = 'order_shipping';
				if(substr($key, 0, 5) == 'cust_') $area = 'order_billing';
				if(is_string($this->var[$area])){
					$this->var[$area] = e107::unserialize($this->var[$area]);
				}
				$key = substr($key, 5);
			}


			$frm = e107::getForm();
			$text = '';

			switch($key)
			{
				case 'order_invoice_nr':
					$text = vstore::formatInvoiceNr($this->var[$key]);
					break;

				case 'order_date':
					$text = e107::getDateConvert()->convert_date($this->var[$key], 'short');
					break;

				case 'country':
					$text = e107::getForm()->getCountry($this->var[$area][$key]);
					break;

				case 'order_gateway':
					if (vstore::isMollie($this->var['order_pay_gateway'])) {
						$text = vstore::getMolliePaymentMethodTitle($this->var['order_pay_gateway']);
					} else {
						$text = vstore::getGatewayTitle($this->var['order_pay_gateway']);
					}
					break;

				case 'order_ref':
					$text = $this->var['order_refcode'];
					break;

				case 'order_pay_status':
					$text = ($this->var['order_status'] == 'C' || $this->var['order_pay_status'] == 'complete') ? '<span class="label label-success">Payed</span>' : '<span class="label label-warning">Open</span>';
					break;

				case 'order_pay_amount':
					$text = $this->format_amount($this->var['order_pay_amount']);
					break;

				case 'order_status':
					$text = vstore::getStatus($this->var['order_status']);
					break;

				case 'order_status_label':
					$label_classes = array(
						'N' => 'primary',
						'P' => 'info',
						'H' => 'warning',
						'C' => 'success',
						'X' => 'danger',
						'R' => 'default'
					);
					$text = '<span class="label label-'.$label_classes[$this->var['order_status']].'">' . vstore::getStatus($this->var['order_status']) .'</span>';
					break;

				case 'order_shipping_full':
					$text = $this->format_address($this->var['order_shipping']);
					break;

				case 'order_billing_full':
					$text = $this->format_address($this->var['order_billing']);
					break;

				case 'order_items_short':
					$items = varset($this->var['order_items']);
					if (!is_array($items)) $items = e107::unserialize($items);

					$text = '';
					foreach($items as $item)
					{
						$text .= sprintf("%dx %s<br/>", $item['quantity'], $item['description']);
					}
					break;

				case 'order_log':
					$log = varset($this->var['order_log']);
					if (!is_array($log)) $log = e107::unserialize($log);

					$dt = e107::getDateConvert();
					$text = '<table class="table table-bordered table-striped">
				<tr>
					<th>'.LAN_DATE.'</th>
					<th>'.LAN_USER.'</th>
					<th>'.LAN_MESSAGE.'</th>
				</tr>
				';
					foreach($log as $item)
					{
						$text .= '<tr>
						<td>'.$dt->convert_date($item['datestamp'], 'short').'</td>
						<td>'.$item['user_name'].'</td>
						<td>'.$item['text'].'</td>
					<tr>
					';
					}
					$text .= '</table>';
					break;

				case 'order_downloads':
					$items = varset($this->var['order_items']);
					if (!is_array($items)) $items = e107::unserialize($items);

					$text = '';
					foreach($items as $item)
					{
						if ($item['id']>0 && varset($item['file']) && isset($this->var['order_status']))
						{
							if ($this->var['order_status'] === 'C' || ($this->var['order_status'] === 'N' && $this->var['order_pay_status'] == 'complete'))
							{
								$linktext = 'Download';
							}
							else
							{
								$linktext = 'Download (will be available once the payment has been received)';
							}
							$text .= '' . sprintf('<div>%s<br/><a href="%s">%s</a></div><br/>',
									$item['description'],
									e107::url('vstore', 'download', array('item_id' => $item['id']), array('mode'=>'full')),
									$linktext
								);
						}
					}
					break;

				default:
					if ($area != '')
					{
						$text = varset($this->var[$area][$key]);
					}
					else
					{
						$text = varset($this->var[$key]);
					}
					break;
			}
			return $text;
		}

		function sc_order_date()
		{
			return e107::getParser()->toDate($this->var['order_date']);
		}

		function sc_order_items()
		{
			$items = $this->var['order_items'];
			if (!is_array($items))
			{
				$items = e107::unserialize($items);
			}


			$template = e107::getTemplate('vstore', 'vstore', 'order_items');

			$text = e107::getParser()->parseTemplate($template['header'], true, $this);

			foreach($items as $key=>$item)
			{
				$desc = $item['description'];

				if (!empty($item['vars']))
				{
					$desc .= '<br/>' . $item['vars'];
				}
				if ($item['id']>0 && varset($item['file']) && isset($this->var['order_status']))
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

				$item['name'] = $desc;
				$item['item_total'] = $item['price'] * $item['quantity'];

				$this->addVars(array('item' => $item));
				$text .= e107::getParser()->parseTemplate($template['row'], true, $this);
			}

			$text .= e107::getParser()->parseTemplate($template['footer'], true, $this);

			return $text;


		}

		function sc_order_coupon()
		{
			if (empty($this->var['cart_coupon']['code']))
			{
				return '';
			}

			$template = e107::getTemplate('vstore', 'vstore', 'order_items');

			$text = e107::getParser()->parseTemplate($template['coupon'], true, $this);
			return $text;
		}


		function sc_order_tax($parm=null)
		{
			if ($this->var['is_business'] && !$this->var['is_local'])
			{
				return '';
			}
			if (!is_array($this->var['order_pay_tax']))
			{
				$this->var['order_pay_tax'] = e107::unserialize($this->var['order_pay_tax']);
			}
			$template = e107::getTemplate('vstore', 'vstore', 'order_items');
			$text = $x = $y = '';
			foreach($this->var['order_pay_tax'] as $tax_rate => $value)
			{
				if (floatval($tax_rate) <= 0) continue;
				$x .= ($x != '' ? '<br />' : '').($tax_rate * 100).'%';
				$y .= ($y != '' ? '<br />' : '').$this->format_amount($value);
			}

			if ($x != '')
			{
				$text .= e107::getParser()->lanVars($template['tax'], array('x' => $x, 'y' => $y));
			}

			return $text;
		}

		function sc_order_merchant_info($parm=null)
		{
			$info = e107::pref('vstore', 'merchant_info');

			if(empty($info))
			{
				return null;
			}

			if (vartrue($parm))
			{
				$parm = array_keys($parm);
				$parm = $parm[0];

				if ($parm == 'line')
				{
					if (stripos($info, '<br') !== false)
					{
						$info = str_ireplace(array('<br>', '<br/>', '<br />'), ', ', $info);
						$info = str_ireplace(array("\r\n", "\n"), '', $info);
					}
					else
					{
						$info = str_ireplace(array("\r\n", "\n"), ', ', $info);
					}
				}
			}

			return e107::getParser()->toHtml($info, true);

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

		function sc_order_gateway_title($parm=null)
		{
			if (vstore::isMollie($this->var['order_pay_gateway'])) {
				$text = vstore::getMolliePaymentMethodTitle($this->var['order_pay_gateway']);
			} else {
				$text = vstore::getGatewayTitle($this->var['order_pay_gateway']);
			}
			return $text;
		}

		function sc_order_gateway_icon($parm=null)
		{
			if (!isset($parm['size'])) {
				$parm['size'] = '2x';
			}
			if (vstore::isMollie($this->var['order_pay_gateway'])) {
				$text = vstore::getMolliePaymentMethodIcon($this->var['order_pay_gateway'], $parm['size']);
			} else {
				$text = vstore::getGatewayIcon($this->var['order_pay_gateway'], $parm);
			}
			return $text;
		}

		function sc_sender_name()
		{
			$info = e107::pref('vstore', 'sender_name');

			if(empty($info))
			{
				return e107::getParser()->toHtml(e107::pref('core', 'siteadmin'), true);;
			}

			return e107::getParser()->toHtml($info, true);
		}


		function sc_order_checkout_url()
		{
			return e107::url('vstore', 'checkout', 'sef');
		}






		function setCategories($data)
		{
			$this->categories = $data;
		}

		function inStock()
		{
			$inStock = true;
			$itemvars = vstore::filterItemVarsByType(varset($this->var['item_vars']), 1, true);
			if(empty($itemvars)){
				$inStock = empty($this->var['item_inventory']) ? false : ($this->var['item_inventory'] != 0);
			}
			else
			{
				//$itemvars = explode(',', $this->var['item_vars']);
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
			return e107::getParser()->toHtml($this->var['itemvarstring'], true,'BODY');
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
			$stock = empty($this->var['item_vars_inventory'])
				? (isset($this->var['item_inventory']) ? $this->var['item_inventory'] : -1)
				: e107::unserialize($this->var['item_vars_inventory']);

			if (isset($this->var['item_vars']))
			{
				$vars = explode(',', $this->var['item_vars']);
				foreach($vars as $varid)
				{
					e107::js('settings', array('vstore' => array(
							'stock' => array("x{$itemid}-{$varid}" => vstore::isInventoryTrackingVar($varid)
								? $stock
								: (isset($this->var['item_inventory']) ? $this->var['item_inventory'] : -1)))
						)
					);
				}
			}

			$baseprice = floatval($this->var['item_price']);
			$this->var['item_var_price'] = $baseprice;

			if (isset($this->var['item_vars']))
			{
				$frm = e107::getForm();
				$sql = e107::getDb();

				if ($sql->select('vstore_items_vars', '*', 'FIND_IN_SET(item_var_id, "'.$this->var['item_vars'].'")'))
				{
					$text = '
					<div id="vstore-item-vars-'.$itemid.'">';
					while($row = $sql->fetch())
					{
						$attributes = e107::unserialize($row['item_var_attributes']);
						$varid = intval($row['item_var_id']);

						$select = $frm->select_open(
							'item_var[' . $itemid . '][' . $varid . ']',
							array('class' => 'vstore-item-var tbox select form-control', 'data-id' => $itemid, 'data-item' => $varid, 'data-name' => varset($row['item_var_name'], 'foo'), 'required' => vartrue($row['item_var_compulsory']))
						);

						$selected = true;
						foreach($attributes as $var)
						{
							$varname = $var['name'];
							if(floatval($var['value']) > 0.0)
							{
								switch($var['operator'])
								{
									case '%':
										if($selected)
										{
											$this->var['item_var_price'] *= (floatval($var['value']) / 100.0);
										}
										$varname .= ' (+ ' . floatval($var['value']) . '%)';
										break;
									case '+':
										if($selected)
										{
											$this->var['item_var_price'] += floatval($var['value']);
										}
										$varname .= ' (+ ' . $this->format_amount($var['value']) . ')';
										break;
									case '-':
										if($selected)
										{
											$this->var['item_var_price'] -= floatval($var['value']);
										}
										$varname .= ' (- ' . $this->format_amount($var['value']) . ')';
										break;
								}
							}

							$select .= $frm->option(
								$varname,
								$frm->name2id($var['name']),
								$selected,
								array('data-op' => $var['operator'], 'data-val' => floatval($var['value']), 'data-id' => $varid, 'data-item' => $itemid)
							);
							$selected = false;

						}

						$select .= $frm->select_close();

						$text .= '
						<div>
							<label style="width: 100%;">' . $row['item_var_name'] . '
							' . $select . '
							</label>
							<!-- fix #92: currency symbol used with product variations --> 
							<span class="text-hide" id="vstore-currency-symbol">' . varset($this->vpref['amount_format'], 0) . $this->curSymbol . '</span>
						</div>';
					}

					$text .= '
					</div>';

					return $text;
				}
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
			return e107::getParser()->toHtml($this->var['cat_name'], true,'TITLE');
		}

		function sc_item_brand_url($parm=null)
		{
			return e107::url('vstore', 'category', array('cat_sef' => $this->var['cat_sef']));
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

		/**
		 * Creates download links to the "attached" media files
		 * This are NOT the purchased files to download!
		 * Just "some" files which will be shown on the product page
		 *
		 * @param integer $parm
		 * @return string
		 */
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
				$text .= '<li>'.$tp->toHtml($bb, true).'</li>';
			}
			$text .= '</ul>';

			return $text;
		}


		function sc_item_price($parm=null)
		{
			$itemid = intval($this->var['item_id']);
			$baseprice = $price = floatval($this->var['item_price']);
			$varprice = floatval($this->var['item_var_price']);

			if ($varprice >= 0.0 && $varprice != $baseprice)
			{
				$price = $varprice;
			}

			return ' <span class="vstore-item-price-'.$itemid.'">'.$this->format_amount($price).'</span><input type="hidden" class="vstore-item-baseprice-'.$itemid.'" value="'.$baseprice.'"/>';
		}

		function sc_item_weight($parm=null)
		{
			$weight = $this->var['item_weight'];
			if ($weight <= 0) return '';
			return 'Weight: ' . $weight . $this->vpref['weight_unit'];
		}


		function sc_item_addtocart($parm=null)
		{

			$class = empty($parm['class']) ? 'btn btn-success vstore-add' : $parm['class'];
			$classo = empty($parm['class0']) ? 'btn btn-default btn-secondary disabled vstore-add' : $parm['class0'];
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
			$classo .= $itemclass;

			$inStock = $this->inStock();

			if(!$inStock)
			{
				if(!empty($this->var['item_link'])) // external link - redirect to URL, info only. ie. catalog mode
				{
					return "<a href='".$this->var['item_link']."' target='_blank' class='".$class."'>".LAN_READ_MORE."</a>";
				}

				return "<a href='#' class='btn-out-of-stock ".$classo."' ".$itemid.">".$this->captionOutOfStock."</a>";
			}

			$label = LAN_VSTORE_001; // 'Add to cart';

			return '<a class="'.$class.'" '.$itemid.' href="#">'.e107::getParser()->toGlyph('fa-shopping-cart').' '.$label.'</a>';
		}


		function sc_item_status($parm=null)
		{
			if($this->var['item_inventory'] != 0)
			{
				return '<span class="text-success"><strong>'.LAN_VSTORE_002.'</strong></span>';
			}

			return '<span class="text-danger"><strong>'.LAN_VSTORE_003.'</strong></span>'; // Out of stock
		}

		function sc_item_url($parm=null)
		{
			if(!empty($this->var['item_link']))
			{
			//	return $this->var['item_link'];
			}

			return e107::url('vstore','product', $this->var);
		}


		function sc_shipping_field($parm = null)
		{
			if (empty($parm)) return '';

			$key = array_keys($parm);
			if ($key) $key = $key[0];

			$frm = e107::getForm();
			$text = '';

			switch($key)
			{
				case 'ship_firstname':
					$text = $frm->text('ship[firstname]', $this->var['ship']['firstname'], 100, array('placeholder'=>'First Name', 'required'=>1));
					break;
				case 'ship_lastname':
					$text = $frm->text('ship[lastname]', $this->var['ship']['lastname'], 100, array('placeholder'=>'Last Name', 'required'=>1));
					break;
				case 'ship_company':
					$text = $frm->text('ship[company]', $this->var['ship']['company'], 200, array('placeholder'=>'Company'));
					break;
				case 'ship_address':
					$text = $frm->text('ship[address]', $this->var['ship']['address'], 200, array('placeholder'=>'Address', 'required'=>1));
					break;
				case 'ship_city':
					$text = $frm->text('ship[city]', $this->var['ship']['city'], 100, array('placeholder'=>'Town/City', 'required'=>1));
					break;
				case 'ship_state':
					$text = $frm->text('ship[state]', $this->var['ship']['state'], 100, array('placeholder'=>'State/Region', 'required'=>0));
					break;
				case 'ship_zip':
					$text = $frm->text('ship[zip]', $this->var['ship']['zip'], 15, array('placeholder'=>'Zip/Postcode', 'required'=>1));
					break;
				case 'ship_country':
					$text = $frm->country('ship[country]', $this->var['ship']['country'], array('placeholder'=>'Select Country...', 'required'=>1));
					break;
				case 'ship_phone':
					$text = $frm->text('ship[phone]', $this->var['ship']['phone'], 15, array('placeholder'=>'Phone number', 'required'=>0));
					break;
				case 'ship_notes':
					$text = $frm->textarea('ship[notes]', $this->var['ship']['notes'], 4, null, array('placeholder'=>'Special notes for delivery.', 'required'=>0, 'size'=>'large'));
					break;
			}
			return $text;
		}



		function sc_customer_add_label($parm = null)
		{
			return '<label for="'.$this->var['fieldname'].'" class="'.(empty($this->var['fieldname'])?'':'required').'">'.$this->var['fieldcaption'].'</label>';
		}

		function sc_customer_add_field($parm = null)
		{
			return $this->var['field'];
		}

		function sc_customer_field($parm = null)
		{
			if (empty($parm)) return '';

			$key = array_keys($parm);
			if ($key) $key = $key[0];

			$frm = e107::getForm();
			$text = '';

			switch($key)
			{
				case 'cust_firstname':
					$text = $frm->text('cust[firstname]', $this->var['cust']['firstname'], 100, array('placeholder'=>'First Name', 'required'=>1));
					break;
				case 'cust_lastname':
					$text = $frm->text('cust[lastname]', $this->var['cust']['lastname'], 100, array('placeholder'=>'Last Name', 'required'=>1));
					break;
				case 'cust_company':
					$text = $frm->text('cust[company]', $this->var['cust']['company'], 200, array('placeholder'=>'Company'));
					break;
				case 'cust_vat_id':
					$text = $frm->text('cust[vat_id]', $this->var['cust']['vat_id'], 50, array('placeholder'=>'VAT ID'));
					break;
				case 'cust_taxcode':
					$text = $frm->text('cust[taxcode]', $this->var['cust']['taxcode'], 50, array('placeholder'=>'Tax code'));
					break;
				case 'cust_address':
					$text = $frm->text('cust[address]', $this->var['cust']['address'], 200, array('placeholder'=>'Address', 'required'=>1));
					break;
				case 'cust_city':
					$text = $frm->text('cust[city]', $this->var['cust']['city'], 100, array('placeholder'=>'Town/City', 'required'=>1));
					break;
				case 'cust_state':
					$text = $frm->text('cust[state]', $this->var['cust']['state'], 100, array('placeholder'=>'State/Region', 'required'=>1));
					break;
				case 'cust_zip':
					$text = $frm->text('cust[zip]', $this->var['cust']['zip'], 15, array('placeholder'=>'Zip/Postcode', 'required'=>1));
					break;
				case 'cust_country':
					$text = $frm->country('cust[country]', $this->var['cust']['country'], array('placeholder'=>'Select Country...', 'required'=>1));
					break;
				case 'cust_email':
					$text = $frm->email('cust[email]', $this->var['cust']['email'], 100, array('placeholder'=>'Email address', 'required'=>1));
					break;
				case 'cust_phone':
					$text = $frm->text('cust[phone]', $this->var['cust']['phone'], 15, array('placeholder'=>'Phone number', 'required'=>0));
					break;
				case 'cust_fax':
					$text = $frm->text('cust[fax]', $this->var['cust']['fax'], 15, array('placeholder'=>'Fax number', 'required'=>0));
					break;
				case 'add_field0':
				case 'add_field1':
				case 'add_field2':
				case 'add_field3':
					$text = $this->var['cust']['add'][$key];
					break;
			}
			return $text;
		}


		function sc_confirm_field($parm = null)
		{
			if (empty($parm)) return '';

			$key = array_keys($parm);
			if ($key) $key = $key[0];

			$frm = e107::getForm();
			$text = '';

			switch($key)
			{

				case 'billing_address':
				case 'shipping_address':
					$text = varset($this->var[$key]);
					break;

				case 'billing_title':
					$text = (vartrue($this->var['order_use_shipping']) ? 'Billing address' : 'Billing & Shipping address');
					break;

				case 'ship_country':
				case 'cust_country':
					$area = substr($key, 0, 4);
					$key = substr($key, 5);
					if (varset($this->var[$area][$key]))
					{
						$text = e107::getForm()->getCountry($this->var[$area][$key]);
					}
					break;

				default:
					if (substr($key, 0, 5) == 'ship_')
					{
						$key = substr($key, 5);
						$text = varset($this->var['ship'][$key]);
					}
					elseif (substr($key, 0, 5) == 'cust_')
					{
						$key = substr($key, 5);
						$text = varset($this->var['cust'][$key]);
					}
					else
					{
						$text = varset($this->var[$key]);
					}

			}
			return $text;
		}

		function sc_confirm_data($parm = null)
		{
			if (empty($parm)) return '';

			$key = array_keys($parm);
			if ($key) $key = $key[0];

			$text = '';

			switch($key)
			{
				case 'name':
					$text = $this->var['item']['item_name'];
					if (vartrue($this->var['item']['itemvarstring']))
					{
						$text .= '<br />'.$this->var['item']['itemvarstring'];
					}
					break;

				case 'price':
					$key = 'item_'.$key.($this->var['item']['is_business'] && !$this->var['item']['is_local'] ? '_net' : '');
					$text = $this->format_amount($this->var['item'][$key]);
					break;

				case 'quantity':
					$text = $this->var['item']['cart_qty'];
					break;

				case 'item_total':
					$key .= ($this->var['item']['is_business'] && !$this->var['item']['is_local'] ? '_net' : '');
					$text = $this->format_amount($this->var['item'][$key]);
					break;

				case 'sub_total':
					$key = ($this->var['totals']['is_business'] && !$this->var['totals']['is_local'] ? 'cart_subNet' : 'cart_subTotal');
					$text = $this->format_amount($this->var['totals'][$key]);
					break;

				case 'shipping_total':
					$key = ($this->var['totals']['is_business'] && !$this->var['totals']['is_local'] ? 'cart_shippingNet' : 'cart_shippingTotal');
					$text = $this->format_amount($this->var['totals'][$key]);
					break;

				case 'grand_total':
					$key = ($this->var['totals']['is_business'] && !$this->var['totals']['is_local'] ? 'cart_grandNet' : 'cart_grandTotal');
					$text = $this->format_amount($this->var['totals'][$key]);
					break;

				case 'coupon':
					$text = $this->var['coupon']['code'];
					break;

				case 'coupon_amount':
					$key = ($this->var['totals']['is_business'] && !$this->var['totals']['is_local'] ? 'amount_net' : 'amount');
					$text = $this->format_amount($this->var['coupon'][$key]);
					break;

				default:
					$text = varset($this->var[$key]);
			}

			return $text;
		}


		function sc_confirm_items()
		{
			$items = $this->var['items'];
			if (!is_array($items))
			{
				$items = e107::unserialize($items);
			}


			$template = e107::getTemplate('vstore', 'vstore', 'confirm_items');

			$text = e107::getParser()->parseTemplate($template['header'], true, $this);

			foreach($items as $key=>$item)
			{
				$this->addVars(array('item' => $item));
				$text .= e107::getParser()->parseTemplate($template['row'], true, $this);
			}

			$text .= e107::getParser()->parseTemplate($template['footer'], true, $this);

			return $text;


		}


		function sc_confirm_tax($parm=null)
		{
			if ($this->var['is_business'] && !$this->var['is_local'])
			{
				return '';
			}
			$template = e107::getTemplate('vstore', 'vstore', 'confirm_items');
			$text = $x = $y = '';
			foreach($this->var['totals']['cart_taxTotal'] as $tax_rate => $value)
			{
				if (floatval($tax_rate) <= 0) continue;
				$x .= ($x != '' ? '<br />' : '').($tax_rate * 100).'%';
				$y .= ($y != '' ? '<br />' : '').$this->format_amount($value);
			}

			if ($x != '')
			{
				$text .= e107::getParser()->lanVars($template['tax'], array('x' => $x, 'y' => $y));
			}

			return $text;
		}


		function sc_confirm_coupon()
		{
			if (empty($this->var['coupon']['code']))
			{
				return '';
			}

			$template = e107::getTemplate('vstore', 'vstore', 'confirm_items');

			$text = e107::getParser()->parseTemplate($template['coupon'], true, $this);
			return $text;
		}


		function sc_cart_data($parm = null)
		{
			if (empty($parm)) return '';
			$key = array_keys($parm);
			if ($key) $key = $key[0];
			$text = '';
			switch($key)
			{
				case 'nr':
					$text = $this->var['item']['nr'];
					break;
				case 'name':
					$text = $this->var['item']['name'];
					break;
				case 'price':
					$field = ($this->var['is_business'] && !$this->var['is_local'] ? 'net_'.$key : $key);
					$text = $this->format_amount($this->var['item'][$field]);
					break;
				case 'quantity':
					$text = $this->var['item']['quantity'];
					break;
				case 'tax':
					$text = ($this->var['is_business'] && !$this->var['is_local']) ? '' : ($this->var['item']['tax_rate'] * 100) . '%';
					break;
				case 'item_total':
					$field = ($this->var['is_business'] && !$this->var['is_local'] ? 'item_total_net' : 'item_total');
					$value = $this->var['item'][$field];
					$text = $this->format_amount($value);
					//$text = $this->format_amount($this->var['item']['item_total']);
					break;
				case 'sub_total':
					$text = $this->format_amount($this->var['order_pay_amount']-$this->var['order_pay_shipping']-$this->var['cart_coupon']['amount']);
					break;
				case 'shipping_total':
					$text = $this->format_amount($this->var['order_pay_shipping']);
					break;
				case 'grand_total':
					$text = $this->format_amount($this->var['order_pay_amount']);
					break;
				case 'item_count':
					$text = $this->var['item_count'];
					break;
				case 'pic':
					$text = $this->var['pic'];
					break;
				case 'index_url':
					$text = e107::url('vstore','index');
					break;
				case 'cart_url':
					$text = e107::url('vstore','cart');
					break;
				case 'dashboard_url':
					$text = e107::url('vstore','dashboard', array('dash' => 'dashboard'));
					break;
				case 'coupon':
					$text = $this->var['cart_coupon']['code'];
					break;
				case 'coupon_amount':
					$text = $this->format_amount($this->var['cart_coupon']['amount']);
					break;
			}

			return $text;
		}


		function sc_cart_price($parm=null)
		{
			if ($this->var['is_business'] && !$this->var['is_local'])
			{
				return $this->format_amount($this->var['item_price_net']);
			}
			else
			{
				return $this->format_amount($this->var['item_price']);
			}
		}

		function sc_cart_total($parm=null)
		{
			if ($this->var['is_business'] && !$this->var['is_local'])
			{
				$total = $this->var['item_total_net'];
			}
			else
			{
				$total = $this->var['item_total'];
			}
			return $this->format_amount($total);
		}

		function sc_cart_qty($parm=null)
		{
			if($parm == 'edit')
			{
				$readonly = '';

				return '<input type="input" '.$readonly.' name="cartQty['.$this->var['cart_id'].'][qty]" class="form-control text-right cart-qty" id="cart-'.$this->var['cart_id'].'" value="'.intval($this->var['cart_qty']).'">';
			}


			return $this->var['cart_qty'];
		}

		function sc_cart_vars($parm=null)
		{
			$text = $this->var['cart_item_vars'];

			$text2 = '<input type="hidden" name="cartQty['.$this->var['cart_id'].'][id]" id="cart-id-'.$this->var['cart_id'].'" value="'.$this->var['cart_item'].'">';
			$text2 .='<input type="hidden" name="cartQty['.$this->var['cart_id'].'][vars]" id="cart-vars-'.$this->var['cart_id'].'" value="'.$text.'">';
			return $text2;
		}

		function sc_cart_removebutton($parm=null)
		{

			return '<button type="submit" name="cartRemove['.$this->var['cart_id'].']" class="btn btn-default btn-secondary vstore-cart-remove-item" title="Remove">
			'.e107::getParser()->toGlyph('fa-trash').'</button>';

		}

		function sc_cart_subtotal($parm=null)
		{
			if ($this->var['is_business'] && !$this->var['is_local'])
			{
				return $this->format_amount($this->var['cart_subNet']);
			}
			else
			{
				return $this->format_amount($this->var['cart_subTotal']);
			}
		}

		function sc_cart_shippingtotal($parm=null)
		{
			if ($this->var['is_business'] && !$this->var['is_local'])
			{
				return $this->format_amount($this->var['cart_shippingNet']);
			}
			else
			{
				return $this->format_amount($this->var['cart_shippingTotal']);
			}
		}

		function sc_cart_checkout_button()
		{
			$text = '<a href="'.e107::url('vstore','checkout').'" id="cart-checkout"  class="btn btn-success">
		                            Checkout '.e107::getParser()->toGlyph('fa-play').'
		                        </a>
		                        <button id="cart-qty-submit" style="display:none" type="submit" class="btn btn-warning">Re-Calculate</button>

		';

			return $text;

		}

		static function sc_cart_continueshop()
		{

			$link = e107::url('vstore','index');

			return '
		<a href="'.$link.'" class="btn btn-default btn-secondary">
		'.e107::getParser()->toGlyph('fa-shopping-cart').' Continue Shopping
		</a>';
		}

		function sc_cart_coupon()
		{
			$template = e107::getTemplate('vstore', 'vstore', 'cart');

			$text = e107::getParser()->parseTemplate($template['coupon'], true, $this);
			return $text;
		}

		function sc_cart_coupon_field()
		{
			$frm = e107::getForm();
			$text = '<div class="form-inline">';
			$text .= $frm->label('Coupon code:', 'cart_coupon_code');
			$text .= '&nbsp;' . $frm->text('cart_coupon_code', $this->var['cart_coupon']['code'], 50, array('placeholder' => 'Enter the coupon code if available', 'size' => 'large'));
			$text .= '</div>';
			return $text;
		}

		function sc_cart_coupon_value()
		{
			if ($this->var['is_business'] && !$this->var['is_local'])
			{
				return $this->format_amount($this->var['cart_coupon']['amount_net']);
			}
			else
			{
				return $this->format_amount($this->var['cart_coupon']['amount']);
			}
		}


		function sc_item_availability()
		{
			if (!$this->inStock())
			{
				return "<span class='label label-danger vstore-item-avail-".$this->var['item_id']."'>".$this->captionOutOfStock."</span>";
			}

			return "<span class='label label-success vstore-item-avail-".$this->var['item_id']."'>In Stock</span>";
		}


		function sc_cart_taxtotal($parm=null)
		{
			if ($this->var['is_business'] && !$this->var['is_local'])
			{
				return '';
			}
			$template = e107::getTemplate('vstore', 'vstore', 'cart');
			$text = $x = $y = '';
			foreach($this->var['cart_taxTotal'] as $tax_rate => $value)
			{
				if (floatval($tax_rate) <= 0) continue;
				$x .= ($x != '' ? '<br />' : '').($tax_rate * 100).'%';
				$y .= ($y != '' ? '<br />' : '').$this->format_amount($value);
			}

			if ($x != '')
			{
				$text .= e107::getParser()->lanVars($template['tax'], array('x' => $x, 'y' => $y));
			}

			return $text;
		}


		function sc_cart_grandtotal($parm=null)
		{
			if ($this->var['is_business'] && !$this->var['is_local'])
			{
				return $this->format_amount( $this->var['cart_grandNet']);
			}
			else
			{
				return $this->format_amount( $this->var['cart_grandTotal']);
			}
		}

		public function sc_cart_currency_symbol($parm=null)
		{
			return $this->curSymbol;
		}



		function sc_invoice_data($parm = null)
		{
			if (empty($parm)) return '';

			$key = array_keys($parm);
			if ($key) $key = $key[0];

			$frm = e107::getForm();
			$ns = e107::getParser();
			$text = '';

			switch($key)
			{
				case 'footer0':
				case 'footer1':
				case 'footer2':
				case 'footer3':
					$i = intval(substr($key, -1));
					if (!is_array($this->vpref['invoice_footer']))
					{
						$this->vpref['invoice_footer'] = e107::unserialize($this->vpref['invoice_footer']);
					}
					// if (!empty($this->vpref['invoice_footer'][$i]['title'][e_LANGUAGE]))
					// {
					// 	$text = "<b>" . $ns->toHTML($this->vpref['invoice_footer'][$i]['title'][e_LANGUAGE], true) . "</b>";
					// }
					// $text .= $ns->toHTML($this->vpref['invoice_footer'][$i]['text'][e_LANGUAGE], true);
					$text = $ns->toHTML($this->vpref['invoice_footer'][$i], true);
					break;

				case 'title':
					$text = $ns->toHTML($this->vpref['invoice_title'][e_LANGUAGE], true);
					break;

				case 'info_title':
					$text = $ns->toHTML($this->vpref['invoice_info_title'][e_LANGUAGE], true);
					break;

				case 'subject':
					$text = $ns->parseTemplate($this->vpref['invoice_subject'][e_LANGUAGE], true, $this);
					break;

				case 'hint':
					$text = $ns->toHTML($this->vpref['invoice_hint'][e_LANGUAGE], true);
					$text = ($text ? '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>' : '') . $text;
					break;

				case 'finish_phrase':
					$text = $ns->toHTML($this->vpref['invoice_finish_phrase'], true);
					$text = ($text ? '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>' : '') . $text;
					break;

				case 'payment_deadline':
					$datestamp = $this->var['order_date'];
					$datestamp += ($this->vpref['invoice_payment_deadline'] * 24 * 60 * 60);
					$format = varset($this->vpref['invoice_date_format'], '%m/%d/%Y');
					$text = e107::getDateConvert()->convert_date($datestamp, $format);
					break;
			}
			return $text;
		}

		function sc_invoice_items()
		{
			$items = $this->var['order_items'];

			$template = e107::getTemplate('vstore', 'vstore_invoice', 'invoice_items');

			$text = e107::getParser()->parseTemplate($template['header'], true, $this);

			foreach($items as $key=>$item)
			{
				$desc = $item['description'];

				if (!empty($item['vars']))
				{
					$desc .= '<br/>' . $item['vars'];
				}
				if ($item['id']>0 && varset($item['file']) && isset($this->var['order_status']))
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

				$item['nr'] = ($key + 1);
				$item['name'] = $desc;
				$item['item_total'] = $item['price'] * $item['quantity'];

				$this->addVars(array('item' => $item));
				$text .= e107::getParser()->parseTemplate($template['row'], true, $this);
			}

			$text .= e107::getParser()->parseTemplate($template['footer'], true, $this);

			return $text;

		}


		function sc_invoice_coupon()
		{
			if (empty($this->var['order_pay_coupon_code']))
			{
				return '';
			}

			$template = e107::getTemplate('vstore', 'vstore_invoice', 'invoice_items');
			$data = array('x' => $this->var['order_pay_coupon_code'], 'y' => $this->format_amount($this->var['order_pay_coupon_amount']));
			$text = e107::getParser()->lanVars($template['coupon'], $data);
			return $text;
		}


		function sc_invoice_tax($parm=null)
		{
			if ($this->var['is_business'] && !$this->var['is_local'])
			{
				return '';
			}
			if (!is_array($this->var['order_pay_tax']))
			{
				$this->var['order_pay_tax'] = e107::unserialize($this->var['order_pay_tax']);
			}
			$template = e107::getTemplate('vstore', 'vstore_invoice', 'invoice_items');
			$text = $x = $y = '';
			foreach($this->var['order_pay_tax'] as $tax_rate => $value)
			{
				if (floatval($tax_rate) <= 0) continue;
				$x .= ($x != '' ? '<br />' : '').($tax_rate * 100).'%';
				$y .= ($y != '' ? '<br />' : '').$this->format_amount($value);
			}

			if ($x != '')
			{
				$text .= e107::getParser()->lanVars($template['tax'], array('x' => $x, 'y' => $y));
			}

			return $text;
		}

		function sc_invoice_logo($parm)
		{
			// Paths to image file, link are relative to site base
			$tp = e107::getParser();

			$logopref = e107::getConfig('core')->get('sitelogo');
			$logop = $tp->replaceConstants($logopref);

			if(vartrue($logopref) && is_readable($logop))
			{
				$logo = $tp->replaceConstants($logopref,'abs');
				$path = $tp->replaceConstants($logopref);
			}
			elseif (isset($file) && $file && is_readable($file))
			{
				$logo = e_HTTP.$file;						// HTML path
				$path = e_BASE.$file;						// PHP path
			}
			else if (is_readable(THEME.'images/e_logo.png'))
			{
				$logo = THEME_ABS.'images/e_logo.png';		// HTML path
				$path = THEME.'images/e_logo.png';			// PHP path
			}
			else
			{
				$logo = '{e_IMAGE}logoHD.png';				// HTML path
				$path = e_IMAGE.'logoHD.png';					// PHP path
			}

			if ($parm === 'path')
			{
				return $path;
			}
			return '<image src="'.$path.'" style="max-width:150px;max-height: 150px;width:100%;height:auto;">';
		}


		function sc_dashboard($parm = null)
		{
			if (empty($parm)) return '';

			$key = array_keys($parm);
			if ($key) $key = $key[0];

			switch($key)
			{
				case 'title':
					$text = $this->var['nav'][$this->var['area']];
					break;

				case 'nav':
					$text = '<ul class="nav nav-tabs">
				';
					$nav = $this->var['nav'];
					foreach($nav as $a => $caption)
					{
						$active = ($this->var['area'] == $a ? 'class="active"': '') ;
						$text .= '<li role="presentation" '.$active.'><a href="'.e107::url('vstore', 'dashboard', array('dash' => $a)).'">'.$caption.'</a></li>
					';
					}
					$text .= '</ul>';
					break;

				case 'shipping_address':
					$data = e107::unserialize($this->var['cust_shipping']);
					$text = $this->format_address($data, true);
					break;

				case 'billing_address':
					$data = array(
						'firstname' => $this->var['cust_firstname'],
						'lastname' => $this->var['cust_lastname'],
						'company' => $this->var['cust_company'],
						'address' => $this->var['cust_address'],
						'city' => $this->var['cust_city'],
						'zip' => $this->var['cust_zip'],
						'country' => $this->var['cust_country'],
						'vat_id' => $this->var['cust_vat_id'],
						'taxcode' => $this->var['cust_taxcode'],
						'email' => $this->var['cust_email'],
						'phone' => $this->var['cust_phone'],
						'fax' => $this->var['cust_fax']
					);
					$text = $this->format_address($data, true);
					break;

				case 'edit_billing':
				case 'edit_shipping':
					$text = '<a href="'.e107::url('vstore', 'dashboard_action', array('dash' => 'addresses', 'action' => 'edit', 'id' => ($key == 'edit_billing' ? 1 : 2))).'" class="btn btn-default">'.LAN_EDIT.'</a>';
					break;

			}

			return $text;
		}


	}
