<?php


e107::css('vstore','vstore.css');
e107::js('vstore','js/vstore.js');


require_once('vendor/autoload.php');


use Omnipay\Omnipay;
use DvK\Vat\Rates\Exceptions\Exception;


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

	protected static $gateways    = array(
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
		//  'email',
		 'phone',
		 'company',
		 'address',
		 'city',
		 'state',
		 'zip',
		 'country',
		 'notes' // Shipping notes
	);

	protected static $customerFields = array(
		 'title',
		 'firstname',
		 'lastname',
		 'company',
		 'vat_id',
		 'taxcode',
		 'address',
		 'city',
		 'state',
		 'zip',
		 'country',
		 'email',
		 'phone',
		 'fax',
		 'additional_fields',
		//  'notes' // Customer notes are for internal use only
	);

	protected static $official_tax_classes = array(
		'none',
		'reduced',
		'reduced1',
		'reduced2',
		'super_reduced',
		'standard',
		'parking'
	);


	public function __construct()
	{
		$this->cartId = $this->getCartId();

		/** @var vstore_shortcodes sc */
		$this->sc = e107::getScParser()->getScObject('vstore_shortcodes', 'vstore', false);

		$this->get = $_GET;
		$this->post = $_POST;

		$this->pref = e107::pref('vstore');

		if(!empty($this->pref['currency']))
		{
			$this->currency = $this->pref['currency'];
		}

		if(!empty($this->pref['caption']) && !empty($this->pref['caption'][e_LANGUAGE]))
		{
			$this->captionBase = $this->pref['caption'][e_LANGUAGE];
		}

		foreach($this->pref['additional_fields'] as $k => $v)
		{
			if (vartrue($v['active'], false))
			{
				static::$customerFields[] = 'add_field'.$k;
			}
		}

		if(!empty($this->pref['caption_categories']) && !empty($this->pref['caption_categories'][e_LANGUAGE]))
		{
			$this->captionCategories = $this->pref['caption_categories'][e_LANGUAGE];
			//e107::getDebug()->log("caption: ".$this->captionCategories);
		}

		if(!empty($this->pref['caption_outofstock']) && !empty($this->pref['caption_outofstock'][e_LANGUAGE]))
		{
			$this->captionOutOfStock = $this->pref['caption_outofstock'][e_LANGUAGE];
			$this->sc->captionOutOfStock = $this->captionOutOfStock;
		}


		if(deftrue('e_DEBUG_VSTORE'))
		{
			e107::getDebug()->log($this->pref);
			e107::getDebug()->log("CartID:".$this->cartId);
		}
		// get all category data.
		$query = 'SELECT * FROM #vstore_cat WHERE cat_class IN ('.USERCLASS_LIST.') ';
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
			if(!empty($this->pref[$key]))
			{
				$active[$k] = $this->getGatewayIcon($k);

				foreach($this->pref as $key=>$v) // get gateway prefs.
				{
					if(strpos($key,$k) === 0)
					{
						$newkey = substr($key,(strlen($k)+1));
						$this->pref[$k][$newkey] = $v;
					}
				}
			}

		}


		if(deftrue('e_DEBUG_VSTORE') && getperms('0'))
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

		// Check for ajax requests and process them first 
		$this->process_ajax();

		// In case this is not an ajax request continue with processing
		$this->process();
		

	}

	/**
	 * Get status string from key or (if key is empty) complete status array
	 *
	 * @param string $key
	 * @return array/string
	 */
	public static function getStatus($key=null)
	{
		if(!empty($key))
		{
			return self::$status[$key];
		}

		return self::$status;

	}

	/**
	 * Get email type string from key or (if key is empty) complete email type array
	 *
	 * @param string $key
	 * @return array/string
	 */
	public static function getEmailTypes($type=null)
	{
		if(!empty($type))
		{
			return self::$emailTypes[$type];
		}

		return self::$emailTypes;

	}

	/**
	 * Return the official tax classes array
	 *
	 * @return array
	 */
	public static function getTaxClasses()
	{
		return self::$official_tax_classes;
	}

	/**
	 * Return the shippingFields array
	 *
	 * @return array
	 */
	public static function getShippingFields()
	{
		return self::$shippingFields;
	}

	/**
	 * Return the customerFields array
	 *
	 * @return array
	 */
	public static function getCustomerFields()
	{
		return self::$customerFields;
	}


	/**
	 * Handle & process all ajax requests 
	 *
	 * @return void
	 */
	private function process_ajax()
	{
		if(e_AJAX_REQUEST)
		{
			// Process only ajax requests
			if($this->get['add'])
			{
				// Add item to cart
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
					include_once 'e_sitelink.php';
					$sl = new vstore_sitelink();
					$msg = $sl->storeCart();
				}
				ob_clean();
				$js->addTextResponse('ok '.$msg)->sendResponse();
				exit;
			}
				
			if(!empty($this->get['reset']))
			{
				// Reset cart
				$this->resetCart();
				include_once 'e_sitelink.php';
				$sl = new vstore_sitelink();
				$msg = $sl->storeCart();
				ob_clean();
				$js = e107::getJshelper();
				$js->_reset();
				$js->addTextResponse('ok '.$msg)->sendResponse();
				exit;
			}
		

			if(!empty($this->get['refresh']))
			{
				// Refresh cart menu
				include_once 'e_sitelink.php';
				$sl = new vstore_sitelink();
				$msg = $sl->storeCart();
				ob_clean();
				$js = e107::getJshelper();
				$js->_reset();
				$js->addTextResponse('ok '.$msg)->sendResponse();
				exit;
			}

			// In case that none of the above has handled the ajax request
			// (which shouldn't happen) just exit
			exit;
		}

	}


	/**
	 * Handle & process all non-ajax requests
	 *
	 * @return void
	 */
	private function process()
	{

		if(!empty($this->get['reset']))
		{
			$this->resetCart();
		}

		if($this->post['mode'] == 'confirmed')
		{
			$this->setMode($this->post['mode']);
			if (empty($this->getGatewayType(true)))
			{
				e107::getMessage()->addError('No payment method selected!', 'vstore');
				return;
			}
			elseif (empty($this->getCheckoutData()))
			{
				e107::getMessage()->addError('No items to checkout!', 'vstore');
				return;
			}
			elseif (empty($this->getCustomerData(true)))
			{
				e107::getMessage()->addError('No customer data set!', 'vstore');
				return;
			}
			elseif (empty($this->getShippingData(true)))
			{
				e107::getMessage()->addError('No shipping data set!', 'vstore');
				return;
			}
			else
			{
				if (!empty(trim($this->post['ship']['notes'])))
				{
					// validate/filter order notes
					$tmp = $this->getShippingData(true);
					$tmp['notes'] = trim(strip_tags($this->post['ship']['notes']));
					$this->setShippingData($tmp);
				}

				$this->processGateway('init');
				return;
			}
		}

		if($this->get['mode'] == 'return')
		{
			$this->processGateway('return');
			return;
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

		// Cancel order
		if (isset($this->post['cancel_order']) && intval($this->post['cancel_order']) > 0 && USER)
		{
			$check = e107::getDb()->retrieve('vstore_orders', '*', 'order_id='.intval($this->post['cancel_order']).' AND order_e107_user = '.USERID);
			if ($check)
			{
				$log = e107::unserialize($check['order_log']);
				$log[] = array(
					'datestamp' => time(),
					'user_id' => USERID,
					'user_name' => USERNAME,
					'text' => 'Order cancelled by user'
				);

				$update = array(
					'data' => array(
						'order_status' => 'X',
						'order_log' => e107::serialize($log, 'json')
					),
					'WHERE' => 'order_id='.intval($this->post['cancel_order'])
				);

				e107::getDb()->update('vstore_orders', $update); 

				$this->emailCustomerOnStatusChange($check['order_id']);

				e107::redirect(e107::url('vstore', 'dashboard', array('dash' => 'orders')));
				exit;
			}
		}

		// Save address(es)
		if (isset($this->post['edit_address']) && intval($this->post['edit_address']) > 0 && USER)
		{
			$check = e107::getDb()->retrieve('vstore_customer', '*', 'cust_e107_user = '.USERID);
			if ($check)
			{
				$save = true;
				if (intval($this->post['edit_address']) === 1 && !empty($this->post['cust']['firstname'])) // Billing address
				{

					$fields = $this->pref['additional_fields'];
					$add = array();
					foreach ($fields as $key => $value) {
						if (isset($this->post['cust']['add_field'.$key]))
						{
							$add['add_field'.$key] = array('caption' => strip_tags($value['caption'][e_LANGUAGE]), 'value' => ($value['type'] == 'text'  ? $this->post['cust']['add_field'.$key] : ($this->post['cust']['add_field'.$key] ? 'X' : '-')));
							unset($this->post['cust']['add_field'.$key]);
						}
					}
					$this->post['cust']['additional_fields'] = e107::serialize($add, 'json');
								
					foreach ($this->getCustomerFields() as $k) 
					{
						if (isset($this->post['cust'][$k]))
						{
							$update['data']['cust_'.$k] = $this->post['cust'][$k];
						}
					}
				}
				elseif (intval($this->post['edit_address']) === 2 && !empty($this->post['ship']['firstname'])) // Shipping address
				{
					$data = array();
					foreach ($this->getShippingFields() as $k) 
					{
						if (isset($this->post['ship'][$k]))
						{
							$data[$k] = $this->post['ship'][$k];
						}

					}
					$update['data']['cust_shipping'] = e107::serialize($data, 'json');
				}
				else
				{
					$save = false;
					e107::getMessage()->addError('Something went wrong! Unable to save changes!', 'vstore', true);
				}

				if ($save)
				{
					$update['WHERE'] = 'cust_e107_user = '.USERID;
					$result = e107::getDb()->update('vstore_customer', $update); 
					e107::getMessage()->addSuccess('Changes successfully saved!', 'vstore', true);
					e107::redirect(e107::url('vstore', 'dashboard', array('dash' => 'addresses')));
					exit;
				}
			}
		}

	}

	/**
	 * Render a form in case the current user is not logged in
	 * for him to decide if he wants to buy as guest, create a
	 * new user account or to login with an existing user account
	 *
	 * @return string
	 */
	private function renderGuestForm()
	{
		$frm = e107::getForm();
		$tp = e107::getParser();

		$template = e107::getTemplate('vstore', 'vstore', 'customer');

		$text = $tp->parseTemplate($template['guest'], true, $this->sc);

		return $text;
	}


	/**
	 * Render customer (billing) information form
	 *
	 * @return string the form
	 */
	private function renderCustomerForm()
	{

		$frm = e107::getForm();
		$tp = e107::getParser();
		if (!isset($this->post['cust']['firstname']))
		{
			// load saved shipping data and assign to variables
			$data = $this->getCustomerData();
			$fields = $this->getCustomerFields();
			$prefix = (isset($data['cust_firstname']) ? 'cust_' : '');
			foreach ($fields as $field) {
				if ($field != 'additional_fields')
				{
					$this->post['cust'][$field] = varset($data[$prefix . $field], null);
				}
			}
		}

		$template = e107::getTemplate('vstore', 'vstore', 'customer');

		/**
		 * Additional checkout fields
		 * Start
		 */
		$addFieldActive = 0;
		foreach ($this->pref['additional_fields'] as $k => $v) 
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
			foreach ($this->pref['additional_fields'] as $k => $v) 
			{
				if (vartrue($v['active'], false))
				{
					$fieldid = 'add_field'.$k;
					$fieldname = 'cust['.$fieldid.']';
					if (isset($this->post['cust'][$fieldid]))
					{
						$fieldvalue = $this->post['cust'][$fieldid];
					}
					else
					{
						$fieldvalue = $this->post['cust']['additional_fields']['value'][$fieldid];
					}
					if ($v['type'] == 'text')
					{
						// Textboxes
						$field = $frm->text($fieldname, $fieldvalue, 100, array('placeholder'=>varset($v['placeholder'][e_LANGUAGE], ''), 'required'=>($v['required'] ? 1 : 0)));
					}
					elseif ($v['type'] == 'checkbox')
					{
						// Checkboxes
						$field = '<div class="form-control">'.$frm->checkbox($fieldname, 1, 0, array('required'=>($v['required'] ? 1 : 0)));
						if (vartrue($v['placeholder']))
						{
							$field .= ' <label for="'.$frm->name2id($fieldname).'-1" class="text-muted">&nbsp;'.$tp->toHTML($v['placeholder'][e_LANGUAGE]).'</label>';
						}
						$field .= '</div>';
					}

					$this->sc->addVars(array(
						'fieldname' => $fieldname,
						'fieldcaption' => $tp->toHTML(varset($v['caption'][e_LANGUAGE], 'Additional field '.$k)),
						'field' => $field,
						'fieldcount' => $addFieldActive,
						'fieldrequired' => $v['required']
					));

					$this->post['cust']['add'][$fieldid] = $tp->parseTemplate($template['additional']['item'], true, $this->sc);

				}
			}

		}

		$this->sc->setVars($this->post);

		$text = $tp->parseTemplate($template['header'], true, $this->sc);
		
		/**
		 * Additional checkout fields
		 * End
		 */

		// if(!USER)
		// {

		// 	$text .= e107::getParser()->parseTemplate($template['guest'], true, $this->sc);

		// }

		return $text;

	}



	/**
	 * Render customer shipping information form
	 *
	 * @return string the form
	 */
	private function renderShippingForm()
	{

		$frm = e107::getForm();
		$tp = e107::getParser();
		if (!isset($this->post['ship']['firstname']))
		{
			$prefix = '';
			// load saved shipping data and assign to variables
			$data = $this->getShippingData();
			if (empty($data) || empty($data['firstname']))
			{
				$data = $this->getCustomerData(true);
				$prefix = isset($data['cust_firstname']) ? 'cust_' : '';
			}
			$fields = $this->getShippingFields();
			foreach ($fields as $field) 
			{
				$this->post['ship'][$field] = varset($data[$prefix . $field], null);
			}

		}

		$template = e107::getTemplate('vstore', 'vstore', 'shipping');

		$this->sc->setVars($this->post);

		$text = $tp->parseTemplate($template['header'], true, $this->sc);


		return $text;

	}

	/**
	 * Render the confirm order page to review a summary of the order before confirming the order
	 *
	 * @return string
	 */
	private function renderConfirmOrder()
	{

		$cust = $this->getCustomerData(true);
		$isBusiness = !empty($cust['vat_id']);
		$isLocal = (varset($cust['country'], $this->pref['tax_business_country']) == $this->pref['tax_business_country']);

		$ship = $this->getShippingData(true);
		
		$data = $this->prepareCheckoutData($this->getCheckoutData(), true);

		$template = e107::getTemplate('vstore', 'vstore', 'orderconfirm');

		$data['cust'] = $cust;
		$data['ship'] = $ship;
		$data['order_pay_gateway'] = $this->getGatewayType(true);

		$this->sc->setVars($data);
		$data['billing_address'] = e107::getParser()->parseTemplate($template['billing'], true, $this->sc);
		if ($data['order_use_shipping'] == 1)
		{
			$data['shipping_address'] = e107::getParser()->parseTemplate($template['shipping'], true, $this->sc);
		}
		$this->sc->setVars($data);

		$text = e107::getParser()->parseTemplate($template['main'], true, $this->sc);

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

	/**
	 * Render the vstore pages
	 *
	 * @return string
	 */
	public function render()
	{

		$ns = e107::getRender();


		if (!empty($this->get['download']))
		{
			if (!$this->downloadFile($this->get['download']))
			{
				$bread = $this->breadcrumb();
				$msg = e107::getMessage()->render('vstore');
	
				$ns->tablerender($this->captionBase, $bread.$msg, 'vstore-download-failed');
				return null;
			}
			else
			{
				// Not needed but ...
				$bread = $this->breadcrumb();
				$msg = e107::getMessage()->addSuccess('File successfully downloaded!')->render('vstore');

				$ns->tablerender($this->captionBase, $bread.$msg, 'vstore-download-done');
				return null;
			}
		}
		
		if($this->getMode() == 'return')
		{
			// print_a($this->post);
			$bread = $this->breadcrumb();
			$text = $this->checkoutComplete();
			$msg = e107::getMessage()->render('vstore');

			$ns->tablerender($this->captionBase, $bread.$msg.$text, 'vstore-cart-complete');
			return null;
		}


		if($this->getMode() == 'checkout')
		{
			$text = '';

			// Validate posted data
			if($this->post['mode'] == 'shipping' || $this->post['mode'] == 'confirm')
			{
				if(!empty($this->post['cust']['firstname']))
				{
					// validate billing data
					$result = $this->validateCustomerData($this->post['cust'], 'billing');
					if (!$result)
					{
						// Something wrong. Stay at the billing address page
						$text .= e107::getMessage()->render('vstore');
						$this->post['mode'] = 'customer';
					}
					else
					{
						$this->post['cust'] = $result;
					}
				}
				elseif(!empty($this->post['ship']['firstname']))
				{
					// Validate shipping data
					$result = $this->validateCustomerData($this->post['ship'], 'shipping');
					if (!$result)
					{
						// Something wrong. Stay at the shipping address page
						$text .= e107::getMessage()->render('vstore');
						$this->post['mode'] = 'shipping';
					}
					else
					{
						$this->post['ship'] = $result;
					}
				}
			}

			// Render pages
			if($this->post['mode'] == 'shipping')
			{
				// Shipping data form
				$bread = $this->breadcrumb();

				if(!empty($this->post['cust']['firstname']))
				{
					$this->setCustomerData($this->post['cust']); 
					$this->setGatewayType($this->post['gateway']);
					$text .= $this->shippingView();
				}
				else
				{
					$text .= e107::getMessage()->addError('Billing address is missing!', 'vstore')->render('vstore');
				}

				$ns->tablerender($this->captionBase, $bread.$text, 'vstore-cart-list');
				return null;
			}
			elseif($this->post['mode'] == 'confirm')
			{
				// Confirm order form
				$this->setShippingType(0);
				$this->setShippingData(null);
				if(!empty($this->post['ship']['firstname']))
				{
					$this->setShippingType($this->post['order_use_shipping']);
					$this->setShippingData($this->post['ship']); 
				}

				if(!empty($this->post['cust']['firstname']))
				{
					$this->setCustomerData($this->post['cust']);
					$this->setGatewayType($this->post['gateway']);
				}

				if (empty($this->getCustomerData(true)))
				{
					$text .= e107::getMessage()->addError('Billing address is missing!', 'vstore')->render('vstore');
				}
				elseif (vartrue($this->post['order_use_shipping']) && empty($this->getShippingData(true)))
				{
					$text .= e107::getMessage()->addError('No shipping address set!', 'vstore')->render('vstore');
				}
				elseif (empty($this->getCheckoutData()))
				{
					$text .= e107::getMessage()->addError('No items to checkout!', 'vstore')->render('vstore');
				}
				else
				{
					// Order confirmation
					$text .= $this->confirmOrderView();
				}

				$bread = $this->breadcrumb();
				$ns->tablerender($this->captionBase, $bread.$text, 'vstore-cart-list');
	
				return null;
			}
			else
			{
				// Customer Data Form
				$bread = $this->breadcrumb();

				if (empty($this->getCheckoutData()))
				{
					$text .= e107::getMessage()->addError('No items to checkout!', 'vstore')->render('vstore');
				}
				else
				{
					$text .= $this->checkoutView();
				}
				$ns->tablerender($this->captionBase, $bread.$text, 'vstore-cart-list');
				return null;
			}
		}

		if($this->getMode() == 'confirmed')
		{
			// Order confirmation
			$msg = e107::getMessage()->render('vstore');

			if ($msg)
			{
				$bread = $this->breadcrumb();
				$ns->tablerender($this->captionBase, $bread.$msg, 'vstore-cart-list');
			}

			return null;
		}


		if(intval($this->get['invoice']) > 0)
		{
			// Display invoice
			$data = $this->renderInvoice($this->getOrderIdFromInvoiceNr($this->get['invoice']));
			
			if ($data)
			{
				// if invoice is correctly rendered, convert to pdf
				$this->invoiceToPdf($data, !false);
				$local_pdf = $this->pathToInvoicePdf($this->get['invoice'], $data['userid']);
				$this->downloadInvoicePdf($local_pdf);								
			}

			$msg = e107::getMessage()->render('vstore');
			if ($msg)
			{
				$bread = $this->breadcrumb();
				$ns->tablerender($this->captionBase, $bread.$msg, 'vstore-invoice');
			}

			return null;
		}


		if($this->getMode() == 'dashboard')
		{
			// render dashboard
			include_once 'inc/vstore_dashboard.class.php';
			$dashboard = new vstore_dashboard();
			$text = $dashboard->render();
			
			$bread = $this->breadcrumb();
			$msg = e107::getMessage()->render('vstore');
			$ns->tablerender($this->captionBase, $bread.$msg.$text, 'vstore-dashboard');

			return null;
		}


		if($this->getMode() == 'cart')
		{
			// print_a($this->post);
			$bread = $this->breadcrumb();
			$text = $this->cartView();
			$msg = e107::getMessage()->render('vstore');
			$ns->tablerender($this->captionBase, $bread.$msg.$text, 'vstore-cart-list');
			return null;
		}


		if($this->get['item'])
		{
			$text = $this->productView($this->get['item']);
			$bread = $this->breadcrumb();
			$msg = e107::getMessage()->render('vstore');
			$ns->tablerender($this->captionBase, $bread.$msg.$text, 'vstore-product-view');
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
			$msg = e107::getMessage()->render('vstore');
			$ns->tablerender($this->captionBase, $bread.$msg.$subCategoryText.$text, 'vstore-product-list');

		}
		else
		{

			$text = $this->categoryList(0, true);
			$bread = $this->breadcrumb();
			$msg = e107::getMessage()->render('vstore');
			$ns->tablerender($this->captionBase, $bread.$msg.$text, 'vstore-category-list');
		}

	}




	/**
	 * Render breadcrumb
	 *
	 * @return string the breadcrumb
	 */
	private function breadcrumb()
	{
		$frm = e107::getForm();



		$array = array();
		
		// $array[] = array('url'=> e107::url('vstore','index'), 'text'=>$this->captionCategories);
		$array[] = array('url'=> e107::url('vstore','index'), 'text'=>$this->captionBase);

		if (!isset($this->get['mode']))
		{
			if (!empty($this->get['download']))
			{
				$array[] = array('url'=> e107::url('vstore','index'), 'text'=>'Download');
			}
			else
			{
				$array[] = array('url'=> e107::url('vstore','index'), 'text'=>$this->captionCategories);
			}
		}
		
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

		if($this->get['mode'] == 'dashboard')
		{
			if (!empty(trim($this->get['area'])))
			{
				include_once 'inc/vstore_dashboard.class.php';
				$dashboard = new vstore_dashboard();
				$array[] = array('url'=> e107::url('vstore','dashboard', array('dash' => $this->get['area'])), 'text'=> $dashboard->getArea());
				if (!empty(trim($this->get['action'])))
				{
					$array[] = array('url'=> e107::url('vstore','dashboard_action', array('dash' => $this->get['area'], 'action' => $this->get['action'], 'id' => $dashboard->getId())), 'text'=> $dashboard->getAction());
				}
			}
			else			
			{
				$array[] = array('url'=> e107::url('vstore','dashboard', array('dash' => 'dashboard')), 'text'=> "My Dashboard");
			}
		}


		
		if(ADMIN)
		{
		//	print_a($this->categories);
		//	print_a($this->item);
		//	print_a($array);
		}
		return $frm->breadcrumb($array);	
		
	}


	/**
	 * Return the active payment gateway information
	 *
	 * @return array
	 */
	private function getActiveGateways()
	{

		return $this->active;

	}


	/**
	 * Render checkout complete message
	 *
	 * @return string
	 */
	private function checkoutComplete()
	{
		$text = e107::getMessage()->render('vstore');

		$text .= "<div class='alert-block'>".plugin_vstore_vstore_shortcodes::sc_cart_continueshop()."</div>";

		return $text;
	}


	/**
	 * Render checkout page to enter the customers shipping information
	 *
	 * @return string
	 */
	private function checkoutView()
	{
		$active = $this->getActiveGateways();
		$curGateway = $this->getGatewayType();

		if(!USER && !isset($_POST['as_guest']))
		{
			// TODO: Fill with life ...
			$text = e107::getForm()->open('gateway-select','post', e107::url('vstore', 'checkout', 'sef'), array('class'=>'form'));
			$text .= $this->renderGuestForm();
			$text .= e107::getForm()->close();


			return $text;
		}



		if(!empty($active))
		{
			$text = e107::getForm()->open('gateway-select','post', e107::url('vstore', 'checkout', 'sef'), array('class'=>'form'));

			$text .= $this->renderCustomerForm();

			$text .= "<hr /><h3>Select payment method to continue</h3><div class='vstore-gateway-list row'>";

			if (count($active) == 1 && empty($curGateway))
			{
				$curGateway = array_keys($active)[0];
			}
			foreach($active as $gateway => $icon)
			{

				$text .= "
						<div class='col-6 col-xs-6 col-sm-4'>
							<label class='btn btn-default btn-light btn-block btn-".$gateway." ".($curGateway == $gateway ? 'active' : '')." vstore-gateway'>
								<input type='radio' name='gateway' value='".$gateway."' style='display:none;' class='vstore-gateway-radio' required ".($curGateway == $gateway ? 'checked' : '').">
								".$icon."
								<h4>".$this->getGatewayTitle($gateway)."</h4>
							</label>
						</div>";

			}

			$text .= "</div>";

			$text .= '<br/>
			<div class="row">
				<div class="alert alert-info">
				<button class="btn btn-default btn-secondary vstore-btn-add-shipping" type="submit" name="mode" value="shipping"><i class="fa fa-truck" aria-hidden="true"></i> Enter shipping address</button>
				<span class="help-text">Use this button to use or enter a separate shipping address.</span>
				</div>
			</div>
			<br />
			<div class="row">
				<div class="col-12 col-xs-12">
					<a class="btn btn-default btn-secondary vstore-btn-back-confirm" href="'.e107::url('vstore', 'cart', 'sef').'">&laquo; Back</a>
					<button class="btn btn-primary vstore-btn-buy-now pull-right float-right" type="submit" name="mode" value="confirm">Continue &raquo;</button>
				</div>
			</div>';

			$text .= e107::getForm()->close();


			return $text;
		}

		return "No Payment Options Set";


	}


	/**
	 * Render shipping address page to enter the customers shipping information
	 *
	 * @return string
	 */
	private function shippingView()
	{
		$active = $this->getActiveGateways();
		$curGateway = $this->getGatewayType(true);
		if(!empty($active))
		{
			$text = e107::getForm()->open('gateway-select','post', e107::url('vstore', 'checkout', 'sef'), array('class'=>'form'));

			$text .= $this->renderShippingForm();

			$text .= '<br/>
			<div class="row">
				<div class="col-12 col-xs-12">
					<input type="hidden" name="order_use_shipping" value="1">
					<a class="btn btn-default btn-secondary vstore-btn-back-confirm" href="'.e107::url('vstore', 'checkout', 'sef').'">&laquo; Back</a>
					<button class="btn btn-primary vstore-btn-buy-now pull-right float-right" type="submit" name="mode" value="confirm">Continue &raquo;</button>
				</div>
			</div>';

			$text .= e107::getForm()->close();


			return $text;
		}

		return "No Payment Options Set";


	}


	/**
	 * Render confirm order page
	 *
	 * @return string
	 */
	private function confirmOrderView()
	{
		$text = e107::getForm()->open('confirm-order','post', null, array('class'=>'form'));

		$text .= $this->renderConfirmOrder();

		$text .= e107::getForm()->close();

		return $text;
	}


	/**
	 * Process the payment via selected payment gateway
	 *
	 * @see http://stackoverflow.com/questions/20756067/omnipay-paypal-integration-with-laravel-4
	 * @see https://www.youtube.com/watch?v=EvfFN0-aBmI
	 * @param string $mode
	 * @return boolean
	 */
	private function processGateway($mode = 'init')
	{
		$type = $this->getGatewayType(true);

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

				if (!empty($this->pref['bank_transfer']['details']))
				{
					$message = '<br />Use the following bank account information for your payment:<br />';
					$message .= e107::getParser()->toHtml($this->pref['bank_transfer']['details'],true);
				}

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
				$price = $var['item_price'];
				$itemvarstring = '';
				if (!empty($var['cart_item_vars']))
				{
					$itemprop = self::getItemVarProperties($var['cart_item_vars'], $var['item_price']);

					if ($itemprop)
					{
						$itemvarstring = $itemprop['variation'];
					}
				}
					


				$items[] = array(
					'id'          => $var['item_id'],
					'name'        => $var['item_code'],
					'price'       => $price,
					'description' => $var['item_name'],
					'quantity'    => $var['cart_qty'],
					'tax_rate'    => $var['tax_rate'],
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
			e107::js('footer-inline', '$(function(){ vstoreCartRefresh(); });');
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
				// Workaround to make sure the payment is complete and not in pending state
				if ($type != 'paypal')
				{
					$method = 'completeAuthorize';
				}
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
			$order_status = 'P';
			if ($response->isPending())
			{
				// is pending => set order status to a new different status
				//$order_status = 'P';
			}
			$transData = $response->getData();
			$transID = $response->getTransactionReference();
			$message = $response->getMessage();

			e107::getMessage()->addSuccess($message,'vstore');

			$this->saveTransaction($transID, $transData, $items, $order_status); // Order payed > save as Processing
			$this->resetCart();

			unset($_SESSION['vstore']['_data']);
		}
		else
		{
			$message = $response->getMessage();
			e107::getMessage()->addError($message,'vstore');
		}
	}

	/**
	 * Build a order reference number out of the order_id, first- & lastname
	 *
	 * @param int $id
	 * @param string $firstname
	 * @param string $lastname
	 * @return string
	 */
	public function getOrderRef($id,$firstname,$lastname)
	{
		$text = substr($firstname,0,2);
		$text .= substr($lastname,0,2);
	//	$text .= date('Y');
		$text .= e107::getParser()->leadingZeros($id,6);

		return strtoupper($text);

	}


	/**
	 * Save the transaction to the database
	 *
	 * @param string $id transaction id
	 * @param array $transData transaction data
	 * @param array $items purchased item
	 * @return void
	 */
	private function saveTransaction($id, $transData, $items, $order_status = 'N')
	{

		if(intval($transData['L_ERRORCODE0']) == 11607) // Duplicate REquest.
		{
			return false;
		}


		$customerData = $this->getCustomerData();

		$fields = $this->pref['additional_fields'];
		$add = array();
		foreach ($fields as $key => $value) {
			if (isset($customerData['add_field'.$key]))
			{
				$add['add_field'.$key] = array('caption' => strip_tags($value['caption'][e_LANGUAGE]), 'value' => ($value['type'] == 'text'  ? $customerData['add_field'.$key] : ($customerData['add_field'.$key]?'X':'-')));
				unset($customerData['add_field'.$key]);
			}
		}
		$customerData['additional_fields'] = json_encode($add, JSON_PRETTY_PRINT);


		if (!$this->getShippingType())
		{
			$this->setShippingData($customerData);
		}
		$shippingData = $this->getShippingData();

		$cartData	  = $this->getCheckoutData();

		$insert =  array(
		    'order_id'            => 0,
		    'order_date'          => time(),
		    'order_session'       => $cartData['id'],
		    'order_e107_user'     => USERID,
		    'order_cust_id'       => '',
			'order_status'        => varset($order_status, 'N') // New
		);

		$insert['order_items'] = json_encode($items, JSON_PRETTY_PRINT);

		$insert['order_use_shipping']    = $this->getShippingType();
		$insert['order_billing']    	= e107::serialize($customerData, 'json');
		$insert['order_shipping']    	= e107::serialize($shippingData, 'json');

		$insert['order_pay_gateway']    = $this->getGatewayType(true);
		$insert['order_pay_status']     = empty($transData) ? 'incomplete' : 'complete';
		$insert['order_pay_transid']    = $id;
		$insert['order_pay_amount']     = $cartData['totals']['cart_grandTotal'];
		$insert['order_pay_tax']     	= e107::serialize($cartData['totals']['cart_taxTotal'], 'json');
		$insert['order_pay_shipping']   = $cartData['totals']['cart_shippingTotal'];
		$insert['order_pay_coupon_code']= $cartData['totals']['cart_coupon']['code'];
		$insert['order_pay_coupon_amount']= $cartData['totals']['cart_coupon']['amount'];
		$insert['order_pay_rawdata']    = e107::serialize($transData, 'json');

		$log = array(array(
			'datestamp' => time(),
			'user_id' => USERID,
			'user_name' => (USER ? USERNAME : 'Guest'),
			'text' => 'Order created' . (empty($transData) ? '' : ' and paid') . '.'
		));
		$insert['order_log']    = e107::serialize($log, 'json');

		$mes = e107::getMessage();

	//	e107::getDebug()->log($insert);

		$nid = e107::getDb()->insert('vstore_orders',$insert);
		if( $nid !== false)
		{
			if (USER && !$this->saveCustomer($customerData, $shippingData, $this->getShippingType(), $this->getGatewayType(true)))
			{
				$mes->addError('Unable to save/Update customer data!', 'vstore');
			}

			$refId = $this->getOrderRef($nid, $customerData['firstname'], $customerData['lastname']);

			$log[] = array(
				'datestamp' => time(),
				'user_id' => USERID,
				'user_name' => (USER ? USERNAME : 'Guest'),
				'text' => 'Order Ref-Nr. assigned: '.$refId
			);
			
			$invoice_nr = vstore::getNextInvoiceNr();

			e107::getDb()->update('vstore_orders', array('data' => array('order_refcode' => $refId, 'order_log' => e107::serialize($log, 'json'), 'order_invoice_nr' => $invoice_nr), 'WHERE' => 'order_id='.$nid));
		
			$insert['order_refcode'] = $refId;
			$insert['order_invoice_nr'] = $invoice_nr;

			$pdf_data = $this->renderInvoice($nid);
			$pdf_file = '';
			if ($pdf_data)
			{
				$this->invoiceToPdf($pdf_data);
				$pdf_file = $this->pathToInvoicePdf($invoice_nr, $pdf_data['userid']);
			}

			$mes->addSuccess("Your order <b>#".$refId."</b> is complete and you will receive a order confirmation with all details within the next few minutes!",'vstore');
			$this->updateInventory($insert['order_items']);
			$this->emailCustomer('default', $refId, $insert, $pdf_file);

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

	private function saveCustomer($customerData, $shippingData, $use_shipping, $gateway)
	{
		$data = array();

		foreach ($customerData as $key => $value) {
			$data['cust_'.$key] = $value;
		}


		$data['cust_shipping'] = json_encode($shippingData, JSON_PRETTY_PRINT);
		$data['cust_use_shipping'] = ($use_shipping ? 1 : 0);
		$data['cust_gateway'] = $gateway;
		$data['cust_datestamp'] = time();

		$sql = e107::getDb();

		if ($sql->select('vstore_customer', 'cust_id', 'cust_e107_user='.USERID))
		{
			$result = $sql->update('vstore_customer', array('data' => $data, 'WHERE' => 'cust_e107_user='.USERID));
		}
		else
		{
			$data['cust_e107_user'] = USERID;
			$result = $sql->insert('vstore_customer', $data);
			if ($result)
			{
				$ref = $this->getOrderRef($result, $customerData['firstname'], $customerData['lastname']);	
				$result = $sql->update('vstore_customer', array('data' => array('cust_refcode' => $ref), 'WHERE' => 'cust_e107_user='.USERID));
			}
		}

		return $result;

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
			$receiver = json_decode($order['order_billing'], true);
			$refId = $order['order_refcode'];

			// Attach the invoice in case the order status is New, Complete or Processing
			$pdf_file = '';
			if (self::validInvoiceOrderState($order['order_status']))
			{
				$pdf_file = $this->pathToInvoicePdf($order['order_invoice_nr'], $order['order_e107_user']);
			}

			$this->emailCustomer(strtolower($this->getStatus($order['order_status'])), $refId, $order, $pdf_file);
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
	 * Get the current email template
	 * If it isn't defined in the admin area, load the template from the template folder
	 *
	 * @todo add a pref (multilan) containing the entire template which can be edited from within the admin area.
	 * @param string $type email type 
	 * @return string the template
	 */
	private function getEmailTemplate($type='default')
	{
		if (empty($type))
		{
			$type = 'default';
		}
		$template = $this->pref['email_templates'];
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
			$template = str_ireplace(array('[html]', '[/html]'), '', $template[$type]['template']);
		}
		return $template;
	}



	/**
	 * Send an email to the customer
	 *
	 * @param string $templateKey the email type
	 * @param string $ref the order ref.
	 * @param array $insert email contents
	 * @return void
	 */
	function emailCustomer($templateKey='default', $ref, $insert=array(), $pdf_file='')
	{
		$tp = e107::getParser();
		$template = $this->getEmailTemplate($templateKey);

		if (empty($template))
		{
			// No template available... No mail to send ...
			e107::getMessage()->addDebug('No template found or template is empty!', 'vstore');
			return;
		}

		$sender_name = $this->pref['sender_name'];
		$sender_email = $this->pref['sender_email'];
		if (empty($sender_email))
		{
			e107::getMessage()->addDebug('No explicit shop email defined!<br/>Will use siteadmin email!', 'vstore');
			$sender_email = e107::pref('core', 'siteadminemail');
		}

		if (empty($sender_name))
		{
			e107::getMessage()->addDebug('No explicit shop email name defined!<br/>Will use siteadmin name!', 'vstore');
			$sender_name = e107::pref('core', 'siteadmin');
		}


		$templates = $this->pref['email_templates'];
		$cc = '';
		if (vartrue($templates[$templateKey]['cc']))
		{
			$cc = $sender_email;
		}

		$receiver = e107::unserialize($insert['order_billing']);

		$insert['is_business'] = !empty($receiver['vat_id']);
		$insert['is_local'] = (varset($receiver['country'], $this->pref['tax_business_country']) == $this->pref['tax_business_country']);

		$insert['order_ref'] = (empty($ref) ? $insert['order_refcode'] : $ref);

		$this->sc->setVars($insert);

		$subject    = "Your Order #[x] at ".SITENAME; //todo add to template

		$email      = $receiver['email'];
		$name       = $receiver['firstname']." ".$receiver['lastname'];;

		$eml = array(
					'subject' 		=> $tp->lanVars($subject, array('x'=>$insert['order_ref'])),
					'sender_email'	=> $sender_email,
					'sender_name'	=> $sender_name,
					'html'			=> true,
					'template'		=> 'default',
					'body'			=> $tp->parseTemplate($template,true,$this->sc)
		);

		if (!empty($cc))
		{
			$eml['cc'] = $cc;
		}

		if (!empty($pdf_file))
		{
			$eml['attach'] = $pdf_file;
		}

		// die(e107::getEmail()->preview($eml));

		// $debug = e107::getEmail()->preview($eml);
		// e107::getDebug()->log($debug);



		e107::getEmail()->sendEmail($email, $name, $eml);

	}




	/**
	 * Update the items inventory based on the given json string
	 *
	 * @param string $json
	 * @return void
	 */
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

	public static function getGateways()
	{
		return self::$gateways;
	}

	/**
	 * Return the icon for the given gateway
	 *
	 * @param string $type
	 * @param string $size default 5x (2x, 3x, 4x, 5x)
	 * @return string
	 */
	private function getGatewayIcon($type='', $size='5x')
	{
		$text = !empty(self::$gateways[$type]) ? self::$gateways[$type]['icon'] : '';
		return e107::getParser()->toGlyph($text, array('size'=>$size));

	}

	/**
	 * Return the title/name of the given gateway
	 *
	 * @param string $type
	 * @return string
	 */
	public static function getGatewayTitle($type)
	{
		return self::$gateways[$type]['title'];

	}


	/**
	 * Return the type of the current gateway
	 *
	 * @param string $type
	 * @return string
	 */
	private function getGatewayType($forceSession=false)
	{
		if (isset($_SESSION['vstore']['gateway']['type']) || $forceSession)
		{
			return $_SESSION['vstore']['gateway']['type'];
		}
		return e107::getDb()->retrieve('vstore_customer', 'cust_gateway', 'cust_e107_user='.USERID);
	}


	/**
	 * Set the type of the current gateway
	 *
	 * @param string $type
	 * @return void
	 */
	private function setGatewayType($type='')
	{
		 $_SESSION['vstore']['gateway']['type'] = $type;
	}


	/**
	 * Set the number of items per page
	 *
	 * @param int $num
	 * @return void
	 */
	public function setPerPage($num)
	{
		$this->perPage = intval($num);	
	}

	/**
	 * Update the cart
	 *
	 * @param string $type (modify, remove)
	 * @param array $array of the ids and item used for modify or remove
	 * @return void
	 */
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

				// Check if item exists and is active
				$iteminfo = $sql->retrieve('vstore_items', 'item_active, item_name', 'item_id=' . $itemid);
				
				if ($iteminfo && $iteminfo['item_active'] == 0)
				{
					// Item not found or not longer active => Remove from cart
					e107::getMessage()->addWarning('We\'re sorry, but we could\'t find the selected item "'.$iteminfo['item_name'].'" or it is no longer active!', 'vstore');
					$sql->delete('vstore_cart', 'cart_id = '.intval($id).' AND cart_item = '.intval($itemid).' LIMIT 1');				
					continue;
				}

				$itemname = $iteminfo['item_name'];

				// check if item is in stock
				$inStock = $this->getItemInventory($itemid, $itemvars);
				if ($qty > $inStock && $inStock >= 0)
				{
					$qty = $inStock;
					$itemvarstring = '';
					if (!empty($itemvars))
					{
						$itemprop = vstore::getItemVarProperties($itemvars, 0);

						if ($itemprop)
						{
							$itemvarstring = $itemprop['variation'];
						}
								
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


	/**
	 * Reset the cart
	 * Remove all items from the cart
	 *
	 * @return void
	 */
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


	/**
	 * Return the current cart id
	 *
	 * @return string
	 */
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

	/**
	 * Render the list of categories
	 *
	 * @param integer $parent 0 = root categories
	 * @param boolean $np true = render nextprev control; false = dont't render nextprev
	 * @return string
	 */
	public function categoryList($parent=0,$np=false)
	{
		
		$this->from = vartrue($this->get['frm'],0);

		$query = 'SELECT * FROM #vstore_cat WHERE cat_class IN ('.USERCLASS_LIST.') AND cat_parent = '.$parent.' ORDER BY cat_order LIMIT '.$this->from.",".$this->perPage;
		if ((!$data = e107::getDb()->retrieve($query, true)) &&  intval($parent) == 0)
		{
			return e107::getMessage()->addInfo('No categories available!', 'vstore')->render('vstore');
		}
		elseif (!$data)
		{
			return '';
		}
		
		$tp = e107::getParser();

		$template = e107::getTemplate('vstore', 'vstore', 'cat');

		$text = $tp->parseTemplate($template['start'], true, $this->sc);
								
		$this->sc->setCategories($this->categories);
		
		foreach($data as $row)
		{
			$this->sc->setVars($row);
			$text .= $tp->parseTemplate($template['item'], true, $this->sc);		
		}
		
		$text .= $tp->parseTemplate($template['end'], true, $this->sc);

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
		
			$nextprev_parms = http_build_query($nextprev, false, '&');
	
			$text .= $tp->parseTemplate("{NEXTPREV: ".$nextprev_parms."}", true);
		}

		return $text;
		
	}
		
	
	/**
	 * Render the list of products
	 *
	 * @param integer $category selected category id
	 * @param boolean $np	render nextpref yes/no
	 * @param string $templateID name of the template to use
	 * @return string
	 */
	public function productList($category=1,$np=false,$templateID = 'list')
	{



		if(!$data = e107::getDb()->retrieve('SELECT SQL_CALC_FOUND_ROWS *, cat_class FROM #vstore_items LEFT JOIN #vstore_cat ON (item_cat = cat_id) WHERE cat_class IN ('.USERCLASS_LIST.') AND item_active=1 AND item_cat = '.intval($category).' ORDER BY item_order LIMIT '.$this->from.','.$this->perPage, true))
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
	
	
	/**
	 * Render a single product/item
	 *
	 * @param integer $id item_id
	 * @return string
	 */
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
				$tabData['files']		= array('caption'=>'Files', 'text'=> $tmpl['item']['files']);
			}
		}
		
		if (!empty($this->pref['howtoorder']))
		{
			$tabData['howto']		= array('caption'=>'How to Order', 'text'=> $tmpl['item']['howto']);
		}

		if(!empty($tabData))
		{
			$text .= $frm->tabs($tabData);
		}

		$parsed = $tp->parseTemplate($text, true, $this->sc);

		return $parsed;
	}
	
	
	/**
	 * Add a single item to the cart
	 * if the item is already on the list increase the quantity by 1
	 *
	 * @param int $id item_id
	 * @param array $itemvars array of item variations
	 * @return bool true on success
	 */	
	protected function addToCart($id, $itemvars=false)
	{
		// if (USERID === 0){
		// 	// Allow only logged in users to add items to the cart
		// 	e107::getMessage()->addError('You must be logged in before adding products to the cart!', 'vstore');
		// 	return false;
		// }

		$itemvars = $this->fixItemVarArray($itemvars);
		$sql = e107::getDb();

		// $isActive = $sql->retrieve('vstore_items', 'item_active', 'item_id='.intval($id));
		$iteminfo = $sql->retrieve('vstore_items', 'item_active, item_tax_class', 'item_id='.intval($id));
		// if (!$isActive)
		if (!$iteminfo['item_active'])
		{
			e107::getMessage()->addWarning('We\'re sorry, but this item is not longer available!', 'vstore');
			$sql->delete('vstore_cart', 'cart_session="'.$this->cartId.'" AND cart_item='.intval($id));
			return false;
		}

		$where = 'cart_session = "'.$this->cartId.'" AND cart_item = ' . intval($id);
		if (is_array($itemvars))
		{
			$where .= ' AND cart_item_vars LIKE "'.self::item_vars_toDB($itemvars).'"';
		}

		
		// Item Exists. 
		if ($sql->select('vstore_cart', 'cart_qty, cart_item_vars', $where . ' LIMIT 1'))
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
			'cart_item_vars'	=> $itemvars ? self::item_vars_toDB($itemvars) : '',
			'cart_item_tax_class'=> vartrue($iteminfo['item_tax_class'], 'standard'),
	  		'cart_qty'			=> 1
  		);

		// Add new Item. 
		return $sql->insert('vstore_cart', $insert);
	
	}

	/**
	 * fix the item variation array to be used in following processes
	 *
	 * @param array $itemvars
	 * @return array
	 */
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

	/**
	 * Format the item variation array for use in the db field
	 *
	 * @param array $itemvarsarray
	 * @return string
	 */
	public static function item_vars_toDB($itemvarsarray)
	{
		if (!is_array($itemvarsarray))
		{
			return '';
		}
		$result = implode(',', array_keys($itemvarsarray));
		$result .= '|' . implode(',', array_values($itemvarsarray));
		return $result;
	}

	/**
	 * Format the item variation string to an array
	 *
	 * @param string $itemvarsstring
	 * @return array
	 */
	public static function item_vars_toArray($itemvarsstring)
	{
		if (empty($itemvarsstring) || strpos($itemvarsstring, '|') === false)
		{
			return null;
		}
		list($k, $v) = explode('|', $itemvarsstring);
		return array_combine(explode(',', $k), explode(',', $v));
	}

	/**
	 * Get the current inventory of the given item / itemvars combination
	 *
	 * @param int $itemid
	 * @param array/boolean $itemvars
	 * @return int
	 */
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
			$inventory = (int) $sql->retrieve('vstore_items', 'item_inventory', 'item_id = '.intval($itemid));
			if ($inventory < 0){
				return 9999999;
			}
			return $inventory;
		}

	}

	/**
	 * Fetch the cart data 
	 *
	 * @return array
	 */
	public function getCartData()
	{
		return e107::getDb()->retrieve('SELECT c.*, i.*, cat.cat_name, cat.cat_sef FROM `#vstore_cart` AS c LEFT JOIN `#vstore_items` as i ON (c.cart_item = i.item_id) LEFT JOIN `#vstore_cat` as cat ON (i.item_cat = cat.cat_id) WHERE c.cart_session = "'.$this->cartId.'" AND c.cart_status ="" ', true);
	}


	/**
	 * Render the cart 
	 *
	 * @return string
	 */
	protected function cartView()
	{
		if(!$data = $this->getCartData() )
		{
			return e107::getMessage()->addInfo("Your cart is empty.",'vstore')->render('vstore');
		}

		$checkoutData = $this->prepareCheckoutData($data, false);
		
		if (!is_array($checkoutData))
		{
			return $checkoutData;
		}
		
		$tp = e107::getParser();
		$frm = e107::getForm();
		$template = e107::getTemplate('vstore', 'vstore', 'cart');
		
		$text = $frm->open('cart','post', e107::url('vstore','cart'));
		
		$text .= e107::getMessage()->render('vstore');

		$text .= '<div class="row">
		        <div class="col-sm-12 col-md-12">';

		$text .= $tp->parseTemplate($template['header'], true, $this->sc);
			
		foreach($checkoutData['items'] as $row)
		{
			$this->sc->setVars($row);
			$text .= $tp->parseTemplate($template['row'], true, $this->sc);	
		}

		$this->sc->setVars($checkoutData['totals']);

		$text .= $tp->parseTemplate($template['footer'], true, $this->sc);		
		$text .= '</div></div>';

		$text .= $frm->close();

		$this->setCheckoutData($checkoutData);

		return $text;

	}
	

	/**
	 * Prepare the checkout data
	 * calc item price, coupon reduction, tax, totals
	 *
	 * @param array $data item list or the checkoutdata array
	 * @param boolean $isCheckoutData true if $data is of type checkout data
	 * @return array
	 */
	public function prepareCheckoutData($data, $isCheckoutData=false, $fromSitelink=false)
	{
		$sql = e107::getDb();
		$cust = $this->getCustomerData();
		$isBusiness = !empty($cust['vat_id']);
		$isLocal = (varset($cust['country'], $this->pref['tax_business_country']) == $this->pref['tax_business_country']);

		$coupon = '';
		$checkoutData['coupon'] = array('code' => '', 'amount' => 0.0, 'amount_net' => 0.0);

		$hasCoupon = false;
		if (!$isCheckoutData && !empty(trim($this->post['cart_coupon_code'])))
		{
			// coupon code was posted
			$coupon = e107::getDb()->retrieve('vstore_coupons', '*', sprintf('coupon_code="%s"', trim($this->post['cart_coupon_code'])));
			$hasCoupon = true;
		}
		elseif ($isCheckoutData || !isset($this->post['cart_coupon_code']))
		{
			// data is cart data 
			// or
			// reuse saved coupon code
			if ($isCheckoutData)
			{
				$coupon = trim($data['coupon']['code']);
			}
			else
			{
				$chk = $this->getCheckoutData();
				$coupon = trim($chk['coupon']['code']);
				unset($chk);
			}
			if ($coupon)
			{
				$coupon = e107::getDb()->retrieve('vstore_coupons', '*', sprintf('coupon_code="%s"', $coupon));
				$hasCoupon = true;
			}
		}

		if ($coupon)
		{
			// assign coupon code
			$checkoutData['coupon']['code'] = strtoupper(trim($coupon['coupon_code']));
		}
		elseif ($hasCoupon)
		{
			e107::getMessage()->addError('Invalid coupon-code!', 'vstore');
		}

		$subTotal 		= 0;
		$subTotalNet	= 0;
		$couponTotal	= 0;
		$netTotal	 	= array();
		$taxTotal	 	= array();

		$checkoutData['id'] = ($isCheckoutData ? $data['id'] :  $this->getCartId());

		$count_active = 0;
		$items = $data;
		if ($isCheckoutData)
		{
			$items = $data['items'];
		}
		unset($data);

		foreach($items as $row)
		{

			if (!$this->isItemActive($row['cart_item']))
			{
				e107::getMessage()->addWarning('We\'re sorry, but the item "'.$row['item_name'].'" is missing or not longer active and has been removed from the cart!', 'vstore');
				$sql->delete('vstore_cart', 'cart_id='.$row['cart_id'].' AND cart_item='.$row['cart_item']);
				continue;
			}

			$count_active++;

			// Handle item variations
			$price = $row['item_price'];
			$row['itemvarstring'] = '';
			if (!empty($row['cart_item_vars']))
			{
				$varinfo = self::getItemVarProperties($row['cart_item_vars'], $row['item_price']);
				if ($varinfo)
				{
					if (!$isCheckoutData)
					{
						$price += $varinfo['price'];
						$row['item_price'] = $price;
					}
					$row['itemvarstring'] = $varinfo['variation'];
				}
			}

			$item_total = $price * $row['cart_qty'];

			// Calc coupon amount for this item
			$coupon_amount = $this->calcCouponAmount($coupon, $row);
			$checkoutData['coupon']['amount'] += $coupon_amount;

			$row['is_business'] = $isBusiness;
			$row['is_local'] = $isLocal;
			$row['tax_rate'] = $this->getTaxRate($row['cart_item_tax_class'], varset($cust['country']));
			$row['tax_amount'] = $this->calcTaxAmount($item_total, $row['tax_rate']);
			$row['item_price_net'] = $this->calcNetPrice($price, $row['tax_rate']);

			$row['item_total'] = $item_total;
			$row['item_total_net'] = $this->calcNetPrice($item_total, $row['tax_rate']);

			$taxTotal[''.$row['tax_rate']] += $this->calcTaxAmount($coupon_amount, $row['tax_rate']);
			$checkoutData['coupon']['amount_net'] += $this->calcNetPrice($coupon_amount, $row['tax_rate']);

			$netTotal[''.$row['tax_rate']] += $row['item_total_net'];
			$taxTotal[''.$row['tax_rate']] += $row['tax_amount'];

			$subTotal += $item_total;	
			$subTotalNet += $row['item_total_net'];

			$checkoutData['items'][] = $row;
		}

		
		if ($count_active == 0)
		{
			return ($fromSitelink ? null : e107::getMessage()->addInfo("Your cart is empty.",'vstore')->render('vstore'));
		}


		$shippingTotal = vstore::calcShippingCost($checkoutData['items']);
		$shippingNet = 0.0;

		// calc shipping tax
		if (count($netTotal)>0)
		{
			$sum = array_sum($netTotal);
			foreach ($netTotal as $tax_rate => $value) 
			{
				$gross = ($value / $sum) * $shippingTotal;
				$taxTotal[''.$tax_rate] += $this->calcTaxAmount($gross, $tax_rate);
				$shippingNet += $this->calcNetPrice($gross, $tax_rate);
			}
		}

		$grandTotal = $subTotal + $shippingTotal + $checkoutData['coupon']['amount']; 
		$grandNet = $subTotalNet + $shippingNet + $checkoutData['coupon']['amount_net']; 
		
		$totals = array(
			'is_business' 		=> $isBusiness,
			'is_local'			=> $isLocal,
			'cart_taxTotal'		=> $taxTotal,
			'cart_subTotal' 	=> $subTotal, 
			'cart_shippingTotal'=> $shippingTotal, 
			'cart_grandTotal'	=> $grandTotal, 

			'cart_subNet'		=> $subTotalNet,
			'cart_shippingNet'	=> $shippingNet, 
			'cart_grandNet'		=> $grandNet, 

			'cart_coupon' 		=> $checkoutData['coupon']
		);


		$checkoutData['totals'] = $totals;

		return $checkoutData;
	}

	/**
	 * Store checkout data in session variable
	 *
	 * @param array $data data to store in session
	 * @return void
	 */
	private function setCheckoutData($data=array())
	{
		$_SESSION['vstore']['checkout'] = $data;
		$_SESSION['vstore']['checkout']['currency'] = $this->currency;
	}


	/**
	 * Store shipping data in session variable
	 *
	 * @param array $data data to store
	 * @return void
	 */
	private function setShippingData($data=array())
	{
		$fields = self::getShippingFields();
		foreach($fields as $fld)
		{
			$_SESSION['vstore']['shipping'][$fld] = trim(strip_tags($data[$fld]));
		}
	}

	/**
	 * Return the shipping data from the session variable
	 *
	 * @return array
	 */
	private function getShippingData($forceSession=false)
	{
		if (!empty($_SESSION['vstore']['shipping']) || $forceSession)
		{
			return $_SESSION['vstore']['shipping'];
		}
		return e107::unserialize(e107::getDb()->retrieve('vstore_customer', 'cust_shipping', 'cust_e107_user='.USERID));
	}

	
	private function setShippingType($type)
	{
		$_SESSION['vstore']['shipping_type'] = (vartrue($type) ? 1 : 0);
	}


	private function getShippingType()
	{
		return ($_SESSION['vstore']['shipping_type']  ? 1 : 0);
	}
	

	/**
	 * Store shipping data in session variable
	 *
	 * @param array $data data to store
	 * @return void
	 */
	private function setCustomerData($data=array())
	{
		$fields = self::getCustomerFields();
		foreach($fields as $fld)
		{
			$_SESSION['vstore']['customer'][$fld] = trim(strip_tags($data[$fld]));
		}
	}
	
	
	/**
	 * Return the customer data from the database if session is empty
	 *
	 * @return array
	 */
	public function getCustomerData($forceSession=false)
	{
		if (!empty($_SESSION['vstore']['customer']) || $forceSession)
		{
			return $_SESSION['vstore']['customer'];
		}
		$row = e107::getDb()->retrieve('vstore_customer', '*', 'cust_e107_user='.USERID);
		$result = false;
		if ($row)
		{
			$result = array();
			foreach($row as $k => $v)
			{
				$result[substr($k, 5)] = $v;
			}
		}
		return $result;
	}
	

	/**
	 * Return the checkoutdata from the session variable
	 *
	 * @param int $id  
	 * @return array
	 */
	public function getCheckoutData($id=null)
	{
		if(!empty($id))
		{
			return $_SESSION['vstore']['checkout'][$id];
		}

		return $_SESSION['vstore']['checkout'];
	}
	
	/**
	 * Process a download request of a downloadable item
	 *
	 * @param int $item_id
	 * @return bool false on error	 
	 */
	private function downloadFile($item_id=null)
	{
		if ($item_id == null || intval($item_id) <= 0)
		{
			e107::getMessage()->addDebug('Download id "'.intval($item_id).'" to download missing or invalid!','vstore');
			return false;
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
			return true;
		}
		else
		{
			e107::getMessage()->addError('Download id  "'.intval($item_id).'" doesn\'t contain a file to download!', 'vstore');
			return false;
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

		$order_status = 'N';
		while($order = $sql->fetch())
		{
			$order_status = $order['order_status'];
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
		e107::getMessage()->addError('Your order is still in a state ('.vstore::getStatus($order_status).') which doesn\'t allow to download the file!', 'vstore');
		return false;
	}

	/**
	 * Is the item (incl. the category of the item) active?
	 *
	 * @param int $itemid
	 * @return boolean true = active; false = inactive
	 */	
	private function isItemActive($itemid)
	{
		if (intval($itemid) <= 0)
		{
			return false;
		}
		$sql = e107::getDb();
		
		if ($sql->gen('SELECT item_id FROM `#vstore_items` LEFT JOIN `#vstore_cat` ON (item_cat = cat_id) WHERE item_active=1 AND cat_class IN ('.USERCLASS_LIST.') AND item_id='.intval($itemid)))
		{
			return true;
		}
		return false;
	}

	
	/**
	 * Return an array containing the variatons string and the pricemodified
	 *
	 * @param array $itemvars
	 * @param double $baseprice
	 * @return array [price => x.x, variation => yyy]
	 */
	public static function getItemVarProperties($itemvars, $baseprice)
	{
		if (empty($itemvars))
		{
			return false;
		}
		
		$baseprice = floatval($baseprice);

		if (is_string($itemvars))
		{
			$itemvars = self::item_vars_toArray($itemvars);
		}

		$result = array('price' => 0.0, 'variation' => array());

		$sql = e107::getDb();
		if ($sql->select('vstore_items_vars', 'item_var_id, item_var_name, item_var_attributes', 'FIND_IN_SET(item_var_id, "'.implode(',', array_keys($itemvars)).'")'))
		{
			while($itemvar = $sql->fetch())
			{
				$attr = e107::unserialize($itemvar['item_var_attributes']);
				$text = $itemvar['item_var_name'];
				$value = $itemvars[$itemvar['item_var_id']];
				$operator = '';
				$op_val = 0.0;
				
				if (is_array($attr))
				{
					$frm = e107::getForm();
					foreach ($attr as $row) {
						if ($frm->name2id($row['name']) == $value)
						{
							$value = $row['name'];
							$operator = $row['operator'];
							$op_val = floatval($row['value']);
							break;
						}
					}
				}
		
				$result['variation'][] =  "{$text}: {$value}";
		

				switch($operator)
				{
					case '%':
						$result['price'] += ($baseprice * $op_val / 100.0);
						break;
					case '+':
						$result['price'] += $op_val;
						break;
					case '-':
						$result['price'] -= $op_val;
						break;
				}
			}
		}

		$result['variation'] = implode(' / ', $result['variation']);

		return $result;

	}

	/**
	 * calculate the shipping cost depending on the current cart items
	 *
	 * @param array $items
	 * @return double
	 */
	public static function calcShippingCost($items)
	{
		$pref = e107::pref('vstore');
		// No shipping
		if (!vartrue($pref['shipping']))
		{
			return 0.0;
		}

		$shipping = 0.0;
		$subtotal = 0.0;
		$weight = 0.0;
		foreach ($items as $item) {
			if (varset($pref['shipping_method']) == 'sum_unique') // sum_unique, sum_simple or staggered
			{
				$shipping += (double) $item['item_shipping'];
			}
			else
			{
				$shipping += (double) ($item['item_shipping'] * $item['cart_qty']);
			}
			$subtotal += (double) ($item['item_price'] * $item['cart_qty']);
			$weight += (double) ($item['item_weight'] * $item['cart_qty']);
		}

		if (varset($pref['shipping_method']) == 'staggered' && varset($pref['shipping_limit']) && varset($pref['shipping_data']))
		{
			$data = e107::unserialize($pref['shipping_data']);
			unset($data['%ROW%']);
			$val = $subtotal;
			if (varset($pref['shipping_unit']) == 'weight') // weight or subtotal
			{
				$val = $weight;
			}
			$found = false;
			foreach ($data as $v) {
				if ($val <= floatval($v['unit']))
				{
					if ($pref['shipping_limit'] == 'limit') // limit or money
					{
						$shipping = (double) (floatval($v['cost']) > $shipping ? $shipping : $v['cost']);
					}
					else
					{
						$shipping = (double) $v['cost'];
					}
					$found = true;
					break;
				}
			}
			if (!$found)
			{
				$shipping = 0.0;
			}
		}
		
		return $shipping;
	}

	/**
	 * Calculate the amount of the current coupon code
	 * will be 0.0 in case of missing data or if the coupon code isn't valid for some reason
	 * If the coupon is valid, the result is always <= 0.0
	 *
	 * @param array $coupon
	 * @param array $item (should have the columns item_id, item_cat, item_price, cart_qty, item_name)
	 * @return double
	 */
	public function calcCouponAmount($coupon, $item)
	{
		if (empty($coupon) || empty($item))
		{
			return 0.0;
		}

		// Coupon active?
		if (!vartrue($coupon['coupon_active']))
		{
			e107::getMessage()->addError('Coupon is not available!', 'vstore');
			return 0.0;
		}

		// Coupon started
		if (vartrue($coupon['coupon_start']) && time() < $coupon['coupon_start'])
		{
			e107::getMessage()->addError('Coupon is not yet available!', 'vstore');
			return 0.0;
		}

		// Coupon expired
		if (vartrue($coupon['coupon_end']) && time() > $coupon['coupon_end'])
		{
			e107::getMessage()->addError('Coupon is no longer available!', 'vstore');
			return 0.0;
		}

		// Check limits
		$sql = e107::getDb();
		// Check how often this code was used so far
		if ($coupon['coupon_limit_coupon'] > -1)
		{
			$usage = $sql->retrieve('vstore_orders', 'count(order_id) AS count_coupon', sprintf('order_pay_coupon_code="%s"', $coupon['coupon_code']));
			if ($usage >= $coupon['coupon_limit_coupon'])
			{
				e107::getMessage()->addError('Coupon is no longer available!<br />It has exceeded it\'s allowed number of usage!', 'vstore');
				return 0.0;
			}
		}

		// Check how often the current user has used this code
		if ($coupon['coupon_limit_user'] > -1)
		{
			$usage = $sql->retrieve('vstore_orders', 'count(order_id) AS count_coupon', sprintf('order_e107_user="%s" AND order_pay_coupon_code="%s"', USERID, $coupon['coupon_code']));
			if ($usage >= $coupon['coupon_limit_user'])
			{
				e107::getMessage()->addError('Coupon is no longer available!<br />It has exceeded it\'s allowed number of usage!', 'vstore');
				return 0.0;
			}
		}

		$coupon['coupon_items'] 	= array_filter(explode(',', $coupon['coupon_items']));
		$coupon['coupon_items_ex'] 	= array_filter(explode(',', $coupon['coupon_items_ex']));
		$coupon['coupon_cats'] 		= array_filter(explode(',', $coupon['coupon_cats']));
		$coupon['coupon_cats_ex'] 	= array_filter(explode(',', $coupon['coupon_cats_ex']));

		$amount = 0.0;

		// Holds the usage data for the current items
		$usage = array();

		// Check if items are defined
		if (count($coupon['coupon_items']) > 0)
		{
			if (!in_array($item['item_id'], $coupon['coupon_items']))
			{
				// Item not included!
				return $amount;
			}
		}
		elseif (count($coupon['coupon_items_ex']) > 0 && in_array($item['item_id'], $coupon['coupon_items_ex']))
		{
			// item excluded
			return $amount;
		}
		// Check if categories are defined
		elseif (count($coupon['coupon_cats']) > 0)
		{
			if (!in_array($item['item_cat'], $coupon['coupon_cats']))
			{
				// Category not included!
				return $amount;
			}
		}
		elseif (count($coupon['coupon_cats_ex']) > 0 && !in_array($item['item_cat'], $coupon['coupon_cats_ex']))
		{
			// Category excluded!
			return $amount;
		}
		
		$max_usage = 0;
		// Check how often this code has been used on this specific item
		if ($coupon['coupon_limit_item'] > -1)
		{
			// Query database only the first time for this item (item_id can be duplicate due to item_variations)
			if (!isset($usage[$item['item_id']]))
			{
				$data = $sql->retrieve('vstore_orders', 'order_items', sprintf('order_items LIKE \'%%"id": "%d"%%\' AND order_pay_coupon_code="%s"', $item['item_id'], $coupon['coupon_code']), true);
				if ($data)
				{
					foreach ($data as $row) {
						$item_info = e107::unserialize($row['order_items']);
						foreach ($item_info as $info)
						{
							if ($info['id'] == $item['item_id'])
							{
								$usage[$item['item_id']] += vartrue($info['quantity'], 0);
							}
						}
					}
				}
			}

			// Add items from this cart
			$usage[$item['item_id']] += $item['cart_qty'];

			// Check if quantity exceeds limit
			if ($usage[$item['item_id']] > $coupon['coupon_limit_item'])
			{
				if (($usage[$item['item_id']] - $item['cart_qty']) < $coupon['coupon_limit_item'])
				{
					$max_usage = $coupon['coupon_limit_item'] - ($usage[$item['item_id']] - $item['cart_qty']);
					e107::getMessage()->addWarning('Item quantity exceeds the allowed number of coupon code usage for this item "'.$item['item_name'].'"!<br />The coupon will only used for remaining number of usages ('.$max_usage.'x).', 'vstore');
				}
				else
				{
					e107::getMessage()->addError('Coupon exceeds the allowed number of usage for this item "'.$item['item_name'].'"!', 'vstore');
					return 0.0;
				}
			}
		}


		$qty = $item['cart_qty'];
		if ($max_usage > 0)
		{
			// Apply code amount only to the remaining items
			$qty = $max_usage;
		}
		// Item included or not explicitly excluded = Apply coupon
		if ($qty > 0)
		{
			if ($coupon['coupon_type'] == '%')
			{
				$amount += (double) ($item['item_price'] * $qty) * $coupon['coupon_amount'] / 100;
			}
			elseif ($coupon['coupon_type'] == 'F')
			{
				$amount += (double) ($item['item_price'] * $qty) - $coupon['coupon_amount'];
			}
		}

		return ($amount * -1);
	}

	/**
	 * return the tax rate depending on the items tax class and the customer country
	 *
	 * @param string $tax_class should be 'none', 'reduced', 'standard'
	 * @param string $customer_country should be the ISO 3166-1 alpha-2 country code of the customers (billing) country
	 * @return number
	 */
	public function getTaxRate($tax_class, $customer_country=null)
	{
		$result = 0.0;

		if (!vartrue($this->pref['tax_calculate']))
		{
			// Tax calculation is deactivated
			return $result;
		}

		if (varset($tax_class, 'standard') == 'none')
		{
			// Tax class is set to 'none' = no tax
			return $result;
		}
		$tax_class = strtolower($tax_class);

		$countries = new DvK\Vat\Countries();

		if (empty($customer_country))
		{
			$customer_ip = e107::getIPHandler()->getIP();
			$customerCountry = $countries->ip($customer_ip);
		}
		else
		{
			$customerCountry = $customer_country;
		}

		$businessCountry = $this->pref['tax_business_country'];
		

		if ($customerCountry == $businessCountry)
		{
			// customer is from the same country as the business
			$tax_classes = e107::unserialize($this->pref['tax_classes']);
			foreach ($tax_classes as $tclass) {
				// lookup tax value
				if ($tclass['name'] == $tax_class)
				{
					$result = floatval($tclass['value']);
					break;
				}
			}

		}
		elseif ($countries->inEurope($businessCountry))
		{
			if (!$countries->inEurope($customerCountry))
			{
				// Customer is not in the EU
				// means no tax value
				return $result;
			}

			// Calc EU tax

			// get tax class by mapping
			$tax_class = self::getTaxClass($tax_class, $customerCountry);
			if (empty($tax_class))
			{
				return 0.0;
			}

			$rates = new DvK\Vat\Rates\Rates();
			try{
				// $result = $rates->country($customerCountry, $tax_class1); 
				$result = $rates->country($customerCountry, $tax_class); 
				// $check_rate = false;
			}catch(Exception $ex) {
				if ($ex->getMessage() == 'Invalid rate.')
				{
					e107::getMessage()->addError('Invalid tax class! Please inform the shop administrator!', 'vstore');						
				}
			}

			if ($result) $result /= 100.0;
		}
		else
		{
			// customer is a foreign customer = no tax
		}

		return $result;
	}

	/**
	 * Check if the tax class is available in the customers country
	 * otherwise get the next "similar" class
	 *
	 * @param string $tax_class
	 * @param string $country
	 * @return void
	 */
	private function getTaxClass($tax_class, $country)
	{
		$country = strtoupper($country);

		// map the tax classes from one country to another
		// e.g. in Germany there is only the reduced class
		// in Austria they have no reduced, only reduced1 and reduced2
		// The method will try to substitute the non existing class with 
		// an existing one (e.g. reduced2 in the previous example)
		$map_classes = array(
			'reduced' => array('reduced2', 'reduced1', 'super_reduced'),
			'reduced1' => array('reduced', 'super_reduced', 'reduced2'),
			'reduced2' => array('reduced', 'reduced1', 'super_reduced'),
			'super_reduced' => array('reduced', 'reduced1', 'reduced2'),
		);


		$rates = new DvK\Vat\Rates\Rates();
		$map = $rates->all();

		if (!array_key_exists($country, $map))
		{
			return '';
		}

		$periods = $map[$country];
		if (empty($periods))
		{
			// Country not in table
			return '';
		}

        // Sort by date desc
        usort($periods, function ($period1, $period2) {
            return new \DateTime($period1['effective_from']) > new \DateTime($period2['effective_from']) ? -1 : 1;
        });
		
		$tax_classes = array_keys($periods[0][0]['rates']);

		if (!in_array($tax_class, $tax_classes))
		{
			// tax class not found...
			// try to map
			foreach($tax_classes as $tc)
			{
				foreach ($map_classes[$tax_class] as $value) {
					if ($tc == $value)
					{
						return $tc;
					}
				}
			}
			return '';
		}
		else
		{
			// tax class is available
			return $tax_class;
		}
	}

	/**
	 * calc the net price of the item depending on the tax_rate for this item
	 *
	 * e.g.
	 * grossprice: 120€
	 * tax_rate: 0.2
	 * net price: 100€
	 *
	 * @param number $grossprice
	 * @param number $tax_rate
	 * @return number
	 */
	private function calcNetPrice($grossprice, $tax_rate)
	{
		return round($grossprice / (1 + $tax_rate), 2);
	}

	/**
	 * calc the tax amount of the item depending on the tax_rate for this item
	 * 
	 * e.g.
	 * grossprice: 120€
	 * tax_rate: 0.2
	 * tax amount: 20€
	 *
	 * @param number $grossprice
	 * @param number $tax_rate
	 * @return number
	 */
	private function calcTaxAmount($grossprice, $tax_rate)
	{
		return round(($grossprice * $tax_rate) / (1 + $tax_rate), 2);
	}

	/**
	 * Check if the given VAT ID exists in the EU and is in the correct format
	 *
	 * @param string $vat_id the VAT ID to check
	 * @return bool true if exists, $vat_id is empty or checking is disabled; false otherwise
	 */
	private function checkVAT_ID($vat_id, $country)
	{
		if (empty(trim($vat_id)))
		{
			// no VAT = VALID
			return true;
		}
		if (empty(trim($country)))
		{
			// Country missing = INVALID
			return false;
		}

		$vat_country = strtoupper(substr($vat_id, 0, 2));

		$countries = new DvK\Vat\Countries();
		if (!$countries->inEurope($vat_country))
		{
			// VAT ID is only used in the EU
			return true;
		}

		if ($this->pref['tax_check_vat'])
		{
			$validator = new DvK\Vat\Validator();
			// check if VAT ID is valid
			if ($validator->validate($vat_id)) // false (checks format + existence)
			{
				// Is VAT ID from the customers country?
				if (strtoupper($country) != $vat_country)
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		return true;
	}

	/**
	 * Validate and filter the customer data
	 *
	 * @param array $data
	 * @return bool/array false if data is invalid, otherwise the filtered data
	 */
	private function validateCustomerData($data, $type = 'billing')
	{
		$mes = e107::getMessage();
		if (empty($data) || !is_array($data))
		{
			$mes->addError('Customer data is missing or invalid!', 'vstore');
			return false;
		}
		if (empty($type) || !in_array($type, array('billing', 'shipping')))
		{
			$mes->addError('Invalid type!', 'vstore');
			return false;
		}

		$result = array();
		$fields = array();
		if ($type == 'billing')
		{
			$fields = self::$customerFields;
		}
		elseif ($type == 'shipping')
		{
			$fields = self::$shippingFields;
		}

		foreach ($fields as $field) {
			if (substr($field, 0, 9) == 'add_field') continue;

			$result[$field] = trim(strip_tags($data[$field]));
			switch($field)
			{
				// REQUIRED
				case 'firstname':
				case 'lastname':
				case 'address':
				case 'city':
				case 'zip':
				case 'country':
				case 'email':
					if (empty($result[$field]))
					{
						$mes->addError('The field '.ucfirst($field).' is required!', 'vstore');
						return false;
					}
					if ($field == 'email' && !filter_var($result[$field], FILTER_VALIDATE_EMAIL))
					{
						$mes->addError('The given email address is invalid!', 'vstore');
						return false;
					}
					break;

				// OPTIONAL
				case 'title':		
				case 'company':
				case 'state':
				case 'taxcode':
				case 'phone':
				case 'fax':
				case 'notes':
					break;

				// VAT ID
				case 'vat_id':
					$result[$field] = strtoupper($result[$field]);
					if(!empty($result[$field]))
					{
						if (!$this->checkVAT_ID($result[$field], $data['country']))
						{
							$mes->addError('The VAT-ID is invalid or doesn\'t match the selected country!', 'vstore');
							return false;
						}
					}
					break;
				
				// ADDITIONAL FIELDS
				case 'additional_fields':
					$addFields = $this->pref['additional_fields'];
					foreach ($addFields as $i => $addField) {
						if ($addField['active'])
						{
							$fieldName = 'add_field'.$i;
							if ($addField['type'] == 'text')
							{
								$result[$fieldName] = trim(strip_tags($data[$fieldName]));
							}
							else
							{
								$result[$fieldName] = ($data[$fieldName] ? '1' : '');
							}
							if ($addField['required'] && empty($result[$fieldName]))
							{
								$mes->addError('The field '.$addField['caption'].' is required!', 'vstore');
								return false;										
							}
						}
					}
					break;
			}
		}

		return $result;
	}

	/**
	 * fetch the next invoice nr to use
	 *
	 * @return int
	 */
	public static function getNextInvoiceNr()
	{
		// Get last used invoice nr.
		$last_nr = e107::getDB()->retrieve('vstore_orders', 'MAX(order_invoice_nr) AS last');
		// Get next nr. from prefs
		$pref = (int) e107::pref('vstore', 'invoice_next_nr');
		// if the pref nr is higher ...
		if (vartrue($pref) > (int)$last_nr['last'])
		{
			// ... use pref
			return $pref;
		}
		// ... otherwise return next higher
		return (int) ($last_nr['last'] + 1);
	}


	/**
	 * Return a formated invoice nr incl. prefix
	 *
	 * @param int $invoice_nr
	 * @return string
	 */
	public static function formatInvoiceNr($invoice_nr)
	{
		$text = e107::pref('vstore', 'invoice_nr_prefix');
		$text .= e107::getParser()->leadingZeros($invoice_nr, 6);

		return $text;
		
	}


	/**
	 * fetch the corresponding order_id to a invoice_nr
	 *
	 * @param int $invoice_nr
	 * @return int
	 */
	function getOrderIdFromInvoiceNr($invoice_nr)
	{
		return e107::getDb()->retrieve('vstore_orders', 'order_id', 'order_invoice_nr='.intval($invoice_nr));
	}


	/**
	 * render the invoice by a given order_id
	 *
	 * @param int $order_id
	 * @return boolean/array
	 */
	function renderInvoice($order_id, $forceUpdate=false)
	{

		if (!vartrue($order_id))
		{
			// Order ID missing or invalid
			e107::getMessage()->addDebug('Order id "'.$order_id.'" missing or invalid!', 'vstore');
			return false;
		}

		// Get order data
		$order = e107::getDb()->retrieve('vstore_orders', '*', 'order_id='.$order_id);
		if (!$order)
		{
			// Order not found!
			e107::getMessage()->addDebug('Order id "'.$order_id.'" not found!', 'vstore');
			return false;
		}


		// check if the invoice belongs to the user (or is admin)
		if ($order['order_e107_user'] != USERID)
		{
			// is user an admin
			if (!ADMIN)
			{
				e107::getMessage()->addError('Access denied!', 'vstore');
				return false;
			}
		}

		// check status of order: Invoice should be rendered only in status: N=New, C=Complete, P=Processing
		if (!self::validInvoiceOrderState($order['order_status']))
		{
			e107::getMessage()->addError(e107::getParser()->lanVars('Order in status "[x]". Invoice not available!', self::getStatus($order['order_status'])) , 'vstore');
			return false;
		}


		// Check if invoice already exists
		$local_pdf = $this->pathToInvoicePdf($order['order_invoice_nr'], $order['order_e107_user']);
		if ($local_pdf != '' && !$forceUpdate)
		{
			$this->downloadInvoicePdf($local_pdf);
			return;
		}
		if ($local_pdf != '')
		{
			// Delete old pdf, to make sure it WILL get recreated!
			@unlink($local_pdf);
		}

		// Load template
		$template = e107::getTemplate('vstore', 'vstore_invoice');
		$invoice = $this->pref['invoice_template'];
		if (empty($invoice))
		{
			if (!vartrue($template['default']))
			{
				// Template not found!
				e107::getMessage()->addDebug('Order id "'.$order_id.'" not found!', 'vstore');
				return false;
			}
			$invoice = $template['default'];
		}


		$order['order_items'] = e107::unserialize($order['order_items']);
		$order['order_billing'] = e107::unserialize($order['order_billing']);
		$order['order_shipping'] = e107::unserialize($order['order_shipping']);
		$order['order_pay_tax'] = e107::unserialize($order['order_pay_tax']);

		$order['is_business'] = !empty($order['order_billing']['vat_id']);
		$order['is_local'] = (varset($order['order_billing']['country'], $this->pref['tax_business_country']) == $this->pref['tax_business_country']);


		$ns = e107::getParser();
		
		$this->sc->addVars($order);
		
		$text = $ns->parseTemplate($invoice, true, $this->sc);
		$footer = $ns->parseTemplate($template['footer'], true, $this->sc);

		$logo = $this->sc->sc_invoice_logo('path');
		if (!empty($logo))
		{
			$logo = e_ROOT . $logo;
		}

		$result = array(
			'userid' => $order['order_e107_user'],
			'subject' => varset($this->pref['invoice_title'][e_LANGUAGE], 'Invoice').' '.self::formatInvoiceNr($order['order_invoice_nr']),
			'text' => $text,
			'footer' => $footer,
			'logo' => $logo,
			'url' => e107::url('vstore', 'invoice', array('order_invoice_nr' => $order['order_invoice_nr']), array('mode' => 'full'))
		);

		return $result;
	}

	/**
	 * create a pdf invoice
	 *
	 * @param array $data
	 * @param boolean $saveToDisk
	 * @return void
	 */
	function invoiceToPdf($data, $saveToDisk=true)
	{

		if (!e107::isInstalled('pdf') || !is_dir(e_PLUGIN . 'pdf/'))
		{
			e107::getAdminLog()->addError('PDF plugin not installed!<br/>This plugin is required by vstore to create invoice pdf\'s!', true, true)->save('Vstore Pdf');

			e107::getMessage()->addError('PDF plugin not installed!<br/>This plugin is required to create invoice pdf\'s!<br/>Please inform the site-admin!', 'vstore');
			return false;
		}

		require_once('inc/vstore_pdf.class.php');	//require the vstore_pdf class

		$pdf = new vstore_pdf();

		if ($saveToDisk)
		{
			// Make sure the path is absolute
			$pdf->pdf_path = realpath(e107::getFile()->getUserDir($data['userid'], true));

			if ($pdf->pdf_path == false || trim($pdf->pdf_path) == '')
			{
				e107::getAdminLog()->add('Vstore', 'Unable to create invoice user folder: "'.e107::getFile()->getUserDir($data['userid'], false).'"', E_LOG_WARNING);
				e107::getMessage()->addError('Unable to create invoice user folder!', 'vstore');
				return;
			}
			$pdf->pdf_output = 'F';
		}
		else
		{
			$pdf->pdf_path = '';
		}

		$text = $data['text'];
		$footer = $data['footer'];
		$creator = SITENAME;
		$author = varset($this->pref['sender_name'], 'Sales');
		$title = $data['subject'];
		$subject = $data['subject'];
		$keywords = '';
		$url = $data['url'];
		$logo = $data['logo'];

		$pdf->makePDF($text, $footer, $creator, $author, $title, $subject, $keywords, $url, $logo);

		return;
	}

	/**
	 * Check if pdf of given invoice number already exists and return the fullpath incl. filename
	 *
	 * @param int $invoice_nr invoice nr used in the filename
	 * @param int $e107_user_id userid of the customer
	 * @return string empty string if file doesn't exists
	 */
	function pathToInvoicePdf($invoice_nr, $e107_user_id = null)
	{
		if (is_null($e107_user_id))
		{
			$e107_user_id = USERID;
		}
		$title = varset($this->pref['invoice_title'][e_LANGUAGE], 'Invoice').' '.self::formatInvoiceNr($invoice_nr);
		$file = e107::getFile()->getUserDir($e107_user_id, false) . e107::getForm()->name2id($title) . '.pdf';

		return (is_readable($file) ? $file : '');
	}


	/**
	 * Return the given pdf file as downloads
	 *
	 * @param string $local_pdf
	 * @return void
	 */
	function downloadInvoicePdf($local_pdf)
	{
		if ($local_pdf != '')
		{
			while(ob_end_clean());
			header('Content-Description: File Transfer');
			if (headers_sent()) {
				$this->Error('Some data has already been output to browser, can\'t send PDF file');
			}
			header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
			header('Pragma: public');
			header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
			// force download dialog
			if (strpos(php_sapi_name(), 'cgi') === false) {
				header('Content-Type: application/force-download');
				header('Content-Type: application/octet-stream', false);
				header('Content-Type: application/download', false);
				header('Content-Type: application/pdf', false);
			} else {
				header('Content-Type: application/pdf');
			}
			// use the Content-Disposition header to supply a recommended filename
			header('Content-Disposition: attachment; filename="'.basename($local_pdf).'"');
			header('Content-Transfer-Encoding: binary');

			header('Content-Length: ' . filesize($local_pdf));
			readfile($local_pdf);
			exit;
		}
		else
		{
			e107::getMessage()->addWarning('Invoice pdf not found!', 'vstore');
		}
	}

	/**
	 * Check if the current order status is valid for invoice creation
	 *
	 * @param string $order_status
	 * @return bool true, order is in a valid state (New, Complete, Processing); false otherwise
	 */
	public static function validInvoiceOrderState($order_status)
	{
		return in_array(strtoupper($order_status), array('N', 'C', 'P'));
	}


}
