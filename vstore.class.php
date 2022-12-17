<?php
/**
 * e107 website system
 *
 * Copyright (C) 2008-2013 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Vstore shopping cart plugin
 *
 * @author CaMerOn <cameron@e107.org>
 * @author Achim Ennenbach <achim@simsync.de>
 * @copyright 2019 e107inc
 */


e107::js('vstore', 'js/vstore.js');

require_once('vendor/autoload.php');

use Omnipay\Omnipay;
use DvK\Vat\Rates\Exceptions\Exception;

class vstore
{

	protected $cartId = null;
	protected $sc;
	protected $perPage = 9;
	protected $from = 0;
	protected $categories = array(); // all categories;
	protected $categorySEF = array();
	protected $item = array(); // current item.
	protected $captionBase = "Vstore";
	protected $captionCategories = "Product Brands";
	protected $captionOutOfStock = "Out of Stock";
	protected $get = array();
	protected $post = array();
	protected $categoriesTotal = 0;
	protected $action = array();
	protected $pref = array();
//	protected $parentData = array();
	protected $currency = 'USD';

	/** @var vstore_order */
	public $order;
	private $html_invoice;

	/**
	 * Array with the available currencies
	 * 'key' is the 3-char ISO 4217 code of the currency
	 *    'title' is the name of the currency
	 *    'symbol' is the currency sign (usually only 1 letter)
	 *    'glyph' If no symbol is available, use the 'glyph' key to define a fontawesome or glyphicon symbol
	 *    Use only 'symbol' of 'glyph', not both!
	 *
	 * @var array
	 */
	protected static $currencies = array(
		'USD' => array(
			'title'  => 'US Dollars',
			'symbol' => '$'
		),
		'CAN' => array(
			'title'  => 'Canadian Dollars',
			'symbol' => '$'
		),
		'EUR' => array(
			'title'  => 'Euros',
			'symbol' => '€'
		),
		'GBP' => array(
			'title'  => 'GB Pounds',
			'symbol' => '£'
		),
		'BTC' => array(
			'title' => 'Bitcoin',
			'glyph' => 'fa-btc'
		),
	);

	/**
	 * Array with the available gateways and their corresponding icons
	 *
	 * @var array The available gateways
	 */
	protected static $gateways = array(
		'paypal'        => array('title' => 'Paypal', 'icon' => 'fa-paypal'),
		'paypal_rest'   => array('title' => 'Paypal', 'icon' => 'fa-paypal'),
		'mollie'        => array('title' => 'Mollie', 'icon' => 'fa-laptop'),

		'bank_transfer' => array('title' => 'Bank Transfer', 'icon' => 'fa-bank'),
	);

	/**
	 * Array with the payment methods of Mollie
	 *
	 * 'key' The payment method prefixed with mollie_
	 *     'title' The name of the payment method
	 *     'icon'  The icon of the payment methods (usually an svg in the images folder)
	 *
	 * All values are required when adding a new payment method!
	 *
	 * @var array The available payment methods of the Mollie gateway
	 */
	protected static $mollie_payment_methods = array(
		'mollie_bancontact'     => array(
			'title' => 'Bancontact',
			'icon'  => 'images/bancontact.svg'
		),
		'mollie_banktransfer'   => array(
			'title' => 'SEPA Bank transfer',
			'icon'  => 'images/sepa.svg'
		),
		'mollie_belfius'        => array(
			'title' => 'Belfius Direct Net',
			'icon'  => 'images/belfius.svg'
		),
		// 'mollie_bitcoin' => array(
		//     'title' => 'Bitcoin',
		//     'icon' => 'bitcoin'
		// ),
		'mollie_creditcard'     => array(
			'title' => 'Creditcard',
			'icon'  => 'images/amex.svg'
		),
		'mollie_directdebit'    => array(
			'title' => 'SEPA Direct debit',
			'icon'  => 'images/sepa.svg'
		),
		'mollie_eps'            => array(
			'title' => 'EPS',
			'icon'  => 'images/eps.svg'
		),
		'mollie_giftcard'       => array(
			'title' => 'Gift Cards',
			'icon'  => 'images/giftcards.svg'
		),
		'mollie_giropay'        => array(
			'title' => 'Giropay',
			'icon'  => 'images/giropay.svg'
		),
		'mollie_ideal'          => array(
			'title' => 'iDeal',
			'icon'  => 'images/ideal.svg'
		),
		'mollie_inghomepay'     => array(
			'title' => 'Ing Homepay',
			'icon'  => 'images/inghomepay.svg'
		),
		'mollie_kbc'            => array(
			'title' => 'Kbc Payment Button',
			'icon'  => 'images/kbc.svg'
		),
		'mollie_klarnapaylater' => array(
			'title' => 'Klarna Pay Later',
			'icon'  => 'images/klarnapaylater.svg'
		),
		'mollie_klarnasliceit'  => array(
			'title' => 'Klarna Slice it',
			'icon'  => 'images/klarnasliceit.svg'
		),
		'mollie_paypal'         => array(
			'title' => 'Paypal',
			'icon'  => 'images/paypal.svg'
		),
		'mollie_paysafecard'    => array(
			'title' => 'Paysafecard',
			'icon'  => 'images/paysafecard.svg'
		),
		// 'mollie_przelewy24' => array(
		//     'title' => 'Przelewy24',
		//     'icon' => 'images/klarnasliceit.svg'
		// ),
		'mollie_sofort'         => array(
			'title' => 'SOFORT Banking',
			'icon'  => 'images/sofort.svg'
		),
	);

	protected static $status = array(
		'N' => 'New',
		'P' => 'Processing',
		'H' => 'On Hold',
		'C' => 'Completed',
		'X' => 'Cancelled',
		'R' => 'Refunded'
	);

	/**
	 * @var array Array with email types
	 */
	protected static $emailTypes = array(
		'default'   => 'Order confirmation',
		'completed' => 'Order completed',
		'cancelled' => 'Order cancelled',
		'refunded'  => 'Order refunded'
	);

	/**
	 * @var array Array with shipping fieldnames
	 */
	protected static $shippingFields = array(
		'firstname',
		'lastname',
		'phone',
		'company',
		'address',
		'city',
		'state',
		'zip',
		'country',
		'notes' // Shipping notes
	);

	/**
	 * @var array Array with customer fieldnames
	 */
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

	/**
	 * @var array Array with official tay classes
	 */
	protected static $official_tax_classes = array(
		'none',
		'reduced',
		'reduced1',
		'reduced2',
		'super_reduced',
		'standard',
		'parking'
	);

	/**
	 * @var array This array keeps track of the item vars types during inventory checks
	 */
	protected static $itemVarsTypes = array();


	public function __construct()
	{
		$sql = e107::getDb();

		$this->cartId = $this->getCartId();

		/** @var vstore_shortcodes sc */
		$this->sc = e107::getScParser()->getScObject('vstore_shortcodes', 'vstore', false);

		$this->get = varset($_GET);
		$this->post = varset($_POST);

		$this->pref = e107::pref('vstore');

		$this->initPrefs();

	}

	public static function getGatewayPackageList()
	{

		$composerData = include(e_PLUGIN . 'vstore/vendor/composer/autoload_psr4.php');

		unset(
			$composerData['Omnipay\Common\\'],
			$composerData['Omnipay\PayPal\\'],
			$composerData['Omnipay\Mollie\\'],
		);

		$list = [];
		foreach($composerData as $package => $path)
		{
			if(strpos($package, 'Omnipay\\') === 0)
			{
				$tmp = explode('\\', $package);
				if(isset($tmp[1]))
				{
					$key = strtolower($tmp[1]);
					$list[$key] = ($tmp[1]);
				}
			}
		}


		return $list;

	}


	public function init()
	{

		// print_a($this->get);
		if(!empty($this->get['catsef']))
		{
			$sef = $this->get['catsef'];
			$this->get['cat'] = vartrue($this->categorySEF[$sef], 0);
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
	public static function getStatus($key = null)
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
	public static function getEmailTypes($type = null)
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

	public static function getCurrencies()
	{

		return self::$currencies;
	}

	public static function getCurrencyTitle($currency)
	{
		return vartrue(self::$currencies[$currency]['title']);
	}

	public static function getCurrencySymbol($currency, $size = '1x')
	{

		$size = vartrue($size, '1x');
		if(isset(self::$currencies[$currency]['symbol']))
		{
			if(preg_match('/[0-9.]+x/', $size))
			{
				// convert '1x' sizes to '1em'
				$size = floatval($size) . 'em';
			}

			return '<span style="font-size: ' . $size . ';">' . self::$currencies[$currency]['symbol'] . '</span>';
		}
		elseif(isset(self::$currencies[$currency]['glyph']))
		{
			return e107::getParser()->toGlyph(self::$currencies[$currency]['glyph'], array('size' => $size));
		}

		return '';
	}

	/**
	 * Return the $mollie_payment_methods or a single entry
	 *
	 * @param string $method Name of the payment method or null
	 *
	 * @return array|string
	 */
	public static function getMolliePaymentMethods($method = null)
	{

		if(!empty($method))
		{
			return self::$mollie_payment_methods[$method];
		}

		return self::$mollie_payment_methods;
	}


	/**
	 * Return the icon for the given gateway
	 *
	 * @param string $type Payment method
	 * @param string $size 5x (1x, 2x, 3x, 4x, 5x)
	 *
	 * @return string Icon of the payment method
	 */
	public static function getMolliePaymentMethodIcon($type = '', $size = '5x')
	{

		$size = vartrue($size, '5x');
		if(preg_match('/^[0-9.]+x$/', $size))
		{
			// convert '1x' sizes to '1em'
			$size = (float) $size . 'em';
		}
		$size = intval($size);
		$size = vartrue($size, 1) . 'em';
		$text = (!empty(self::$mollie_payment_methods[$type])
			? e_PLUGIN_ABS . 'vstore/' . self::$mollie_payment_methods[$type]['icon']
			: '');

		return e107::getParser()->toImage($text, array(
			'style' => "width: " . $size . "; height: " . $size . ";",
			'class' => 'vstore-mollie-payment-icon img-circle'));
	}

	/**
	 * Return the title for the given gateway
	 *
	 * @param string $type Payment method
	 *
	 * @return string Title of the payment method
	 */
	public static function getMolliePaymentMethodTitle($type = '')
	{

		return !empty(self::$mollie_payment_methods[$type]) ? self::$mollie_payment_methods[$type]['title'] : '';
	}

	/**
	 * Return if gateway type is Mollie
	 *
	 * @param string $type
	 *
	 * @return bool true if is mollie gateway
	 */
	public static function isMollie($type = '')
	{

		return substr($type, 0, 6) == 'mollie';
	}

	/**
	 * Parse and return Mollie Error message
	 *
	 * @param $str    JSON string
	 *
	 * @return string Error message
	 */
	private function getMollieErrorMessage($str)
	{

		if($json = e107::unserialize($str))
		{
			if(vartrue($json['status']) == 'canceled')
			{
				return 'You have canceled your payment, but your cart is still available for further reference.';
			}
			elseif(vartrue($json['status']) == 'failed')
			{
				return 'Your payment failed for some reason, but your cart is still available for further reference.';
			}
			elseif(vartrue($json['detail']))
			{
				return $json['detail'];
			}

			return 'Generic error';
		}

		return $str;
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
			$js = e107::getJshelper();
			$js->_reset();
			// Process only ajax requests
			if($this->get['add'])
			{
				// Add item to cart
				$itemid = $this->get['add'];
				$itemvars = $this->get['itemvar'];
				if(!$this->addToCart($itemid, $itemvars))
				{
					$msg = e107::getMessage()->render('vstore');
					ob_clean();
					$js->sendTextResponse($msg);
					exit;
				}
				else
				{
					include_once 'e_sitelink.php';
					$sl = new vstore_sitelink();
					$msg = $sl->storeCart();
				}
				ob_clean();
				$js->sendTextResponse('ok ' . $msg);
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
				$js->sendTextResponse('ok ' . $msg);
				exit;
			}


			if(!empty($this->get['refresh']))
			{
				// Refresh cart menu
				include_once 'e_sitelink.php';
				$sl = new vstore_sitelink();
				$msg = $sl->storeCart();
				ob_clean();
				$js->sendTextResponse('ok ' . $msg);
				exit;
			}

			// Order processing
			if(isset($this->post['order']) && intval($this->post['id']) > 0 && ADMIN)
			{
				$this->order->load($this->post['id']);
				if($this->post['order'] === 'refund')
				{
					// Refund a payment, $order_refund contains the orderId, access only for Admins!
					$status = 'R';
					$result = $this->order->refundOrder();
				}
				elseif($this->post['order'] === 'complete')
				{
					// Complete an order, $order_complete contains the orderId, access only for Admins!
					$status = 'C';
					$result = $this->order->setOrderStatus($status);
				}
				elseif($this->post['order'] === 'cancel')
				{
					// Cancel an order, $order_cancel contains the orderId, access only for Admins!
					$status = 'X';
					$result = $this->order->setOrderStatus($status);
				}
				elseif($this->post['order'] === 'process')
				{
					// Cancel an order, $order_cancel contains the orderId, access only for Admins!
					$status = 'P';
					$result = $this->order->setOrderStatus($status);
				}
				elseif($this->post['order'] === 'hold')
				{
					// Hold an order, $order_cancel contains the orderId, access only for Admins!
					$status = 'H';
					$result = $this->order->setOrderStatus($status);
				}
				else
				{
					// In case that none of the above has handled the ajax request
					// (which shouldn't happen) just exit
					exit;
				}

				ob_clean();
				// send out the results to the browser
				// will be used in a javascript alert() box
				if($result === true)
				{
					// all went well
					$js->sendTextResponse(
						EMESSLAN_TITLE_SUCCESS . "\n" .
						e107::getParser()->lanVars(
							'Order updated to "[x]"',
							self::getStatus($status)
						)
					);
				}
				else
				{
					// some error occured
					$js->sendTextResponse(
						($this->order->getLastError()
							? $this->order->getLastError()
							: EMESSLAN_TITLE_ERROR . "\n" . e107::getParser()->lanVars(
								'Order couldn\'t be updated to "[x]"',
								self::getStatus($status)
							)
						)
					);
				}
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

		if(varset($this->post['mode']) == 'confirmed')
		{
			$this->setMode($this->post['mode']);
			if(empty($this->getGatewayType(true)))
			{
				e107::getMessage()->addError('No payment method selected!', 'vstore');

				return;
			}
			elseif(empty($this->getCheckoutData()))
			{
				e107::getMessage()->addError('No items to checkout!', 'vstore');

				return;
			}
			elseif(empty(vstore::getCustomerData(true)))
			{
				e107::getMessage()->addError('No customer data set!', 'vstore');

				return;
			}
			elseif(empty($this->getShippingData(true)))
			{
				e107::getMessage()->addError('No shipping data set!', 'vstore');

				return;
			}
			else
			{
				if(!empty(trim($this->post['ship']['notes'])))
				{
					// validate/filter order notes
					$tmp = $this->getShippingData(true);
					$tmp['notes'] = trim(strip_tags($this->post['ship']['notes']));
					$this->setShippingData($tmp);
				}

				$this->processGateway(); // init

				return;
			}
		}

		if(varset($this->get['mode']) == 'return')
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
			if(!e_AJAX_REQUEST)
			{
				$this->addToCart($this->get['add'], $this->get['itemvar']);
			}
		}

		// Cancel order
		if(isset($this->post['cancel_order']) && intval($this->post['cancel_order']) > 0 && USER)
		{
			$check = e107::getDb()->retrieve(
				'vstore_orders',
				'*',
				'order_id=' . intval($this->post['cancel_order']) . ' AND order_e107_user = ' . USERID
			);
			if($check)
			{
				$log = e107::unserialize($check['order_log']);
				$log[] = array(
					'datestamp' => time(),
					'user_id'   => USERID,
					'user_name' => USERNAME,
					'text'      => 'Order cancelled by user'
				);

				$update = array(
					'data'  => array(
						'order_status' => 'X',
						'order_log'    => e107::serialize($log, 'json')
					),
					'WHERE' => 'order_id=' . intval($this->post['cancel_order'])
				);

				e107::getDb()->update('vstore_orders', $update);

				$vc = e107::getSingleton('vstore_order', e_PLUGIN.'vstore/inc/vstore_order.class.php');
				$vc->emailCustomerOnStatusChange($check['order_id']);

				e107::redirect(e107::url('vstore', 'dashboard', array('dash' => 'orders')));
				exit;
			}
		}

		// Save address(es)
		if(isset($this->post['edit_address']) && intval($this->post['edit_address']) > 0 && USER)
		{
			$check = e107::getDb()->retrieve('vstore_customer', '*', 'cust_e107_user = ' . USERID);
			if($check)
			{
				$save = true;
				if(intval($this->post['edit_address']) === 1 && !empty($this->post['cust']['firstname']))
				{
					// Billing address
					$fields = $this->pref['additional_fields'];
					$add = array();
					foreach($fields as $key => $value)
					{
						if(isset($this->post['cust']['add_field' . $key]))
						{
							$add['add_field' . $key] = array(
								'caption' => strip_tags($value['caption'][e_LANGUAGE]),
								'value'   => ($value['type'] == 'text'
									? $this->post['cust']['add_field' . $key]
									: ($this->post['cust']['add_field' . $key] ? 'X' : '-'))
							);
							unset($this->post['cust']['add_field' . $key]);
						}
					}
					$this->post['cust']['additional_fields'] = e107::serialize($add, 'json');

					foreach($this->getCustomerFields() as $k)
					{
						if(isset($this->post['cust'][$k]))
						{
							$update['data']['cust_' . $k] = $this->post['cust'][$k];
						}
					}
				}
				elseif(intval($this->post['edit_address']) === 2 && !empty($this->post['ship']['firstname']))
				{
					// Shipping address
					$data = array();
					foreach($this->getShippingFields() as $k)
					{
						if(isset($this->post['ship'][$k]))
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

				if($save)
				{
					$update['WHERE'] = 'cust_e107_user = ' . USERID;
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

		$tp = e107::getParser();

		$template = e107::getTemplate('vstore', 'vstore', 'customer');

		return $tp->parseTemplate($template['guest'], true, $this->sc);

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
		$field = '';

		if(!isset($this->post['cust']['firstname']))
		{
			// load saved shipping data and assign to variables
			$data = vstore::getCustomerData();
			$fields = $this->getCustomerFields();
			$prefix = (isset($data['cust_firstname']) ? 'cust_' : '');
			foreach($fields as $field)
			{
				if($field != 'additional_fields')
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
		foreach($this->pref['additional_fields'] as $k => $v)
		{
			// Check if additional fields are enabled
			if(vartrue($v['active'], false))
			{
				$addFieldActive++;
			}
		}

		if($addFieldActive > 0)
		{
			// If any additional fields are enabled
			// add active fields to form
			foreach($this->pref['additional_fields'] as $k => $v)
			{
				if(vartrue($v['active'], false))
				{
					$fieldid = 'add_field' . $k;
					$fieldname = 'cust[' . $fieldid . ']';
					if(isset($this->post['cust'][$fieldid]))
					{
						$fieldvalue = $this->post['cust'][$fieldid];
					}
					else
					{
						$fieldvalue = $this->post['cust']['additional_fields']['value'][$fieldid];
					}
					if($v['type'] == 'text')
					{
						// Textboxes
						$field = $frm->text(
							$fieldname,
							$fieldvalue,
							100,
							array(
								'placeholder' => varset($v['placeholder'][e_LANGUAGE]),
								'required'    => ($v['required'] ? 1 : 0)
							)
						);
					}
					elseif($v['type'] == 'checkbox')
					{
						// Checkboxes
						$field = '<div class="form-control">' .
							$frm->checkbox(
								$fieldname,
								1,
								0,
								array('required' => ($v['required'] ? 1 : 0))
							);
						if(vartrue($v['placeholder']))
						{
							$field .= ' <label for="' .
								$frm->name2id($fieldname) . '-1" class="text-muted">&nbsp;' .
								$tp->toHTML($v['placeholder'][e_LANGUAGE]) . '</label>';
						}
						$field .= '</div>';
					}

					$this->sc->addVars(array(
						'fieldname'     => $fieldname,
						'fieldcaption'  => $tp->toHTML(varset($v['caption'][e_LANGUAGE], 'Additional field ' . $k)),
						'field'         => $field,
						'fieldcount'    => $addFieldActive,
						'fieldrequired' => $v['required']
					));

					$this->post['cust']['add'][$fieldid] = $tp->parseTemplate(
						$template['additional']['item'],
						true,
						$this->sc
					);
				}
			}
		}

		$this->sc->setVars($this->post);

		return $tp->parseTemplate($template['header'], true, $this->sc);

		/**
		 * Additional checkout fields
		 * End
		 */

		// if (!USER) {
		//     $text .= e107::getParser()->parseTemplate($template['guest'], true, $this->sc);
		// }


	}


	/**
	 * Render customer shipping information form
	 *
	 * @return string the form
	 */
	private function renderShippingForm()
	{

		$tp = e107::getParser();
		if(!isset($this->post['ship']['firstname']))
		{
			$prefix = '';
			// load saved shipping data and assign to variables
			$data = $this->getShippingData();
			if(empty($data) || empty($data['firstname']))
			{
				$data = vstore::getCustomerData(true);
				$prefix = isset($data['cust_firstname']) ? 'cust_' : '';
			}
			$fields = $this->getShippingFields();
			foreach($fields as $field)
			{
				$this->post['ship'][$field] = varset($data[$prefix . $field], null);
			}
		}

		$template = e107::getTemplate('vstore', 'vstore', 'shipping');

		$this->sc->setVars($this->post);

		return $tp->parseTemplate($template['header'], true, $this->sc);

	}

	/**
	 * Render the confirm order page to review a summary of the order before confirming the order
	 *
	 * @return string
	 */
	private function renderConfirmOrder()
	{
		$cust = vstore::getCustomerData(true);
		$ship = $this->getShippingData(true);
		$data = $this->prepareCheckoutData($this->getCheckoutData(), true);

		$template = e107::getTemplate('vstore', 'vstore', 'orderconfirm');

		$data['cust'] = $cust;
		$data['ship'] = $ship;
		$data['order_pay_gateway'] = $this->getGatewayType(true);

		if(!empty($ship['address']))
		{
			$data['order_use_shipping']  = 1;

		}

		$this->sc->setVars($data);

		$data['billing_address'] = e107::getParser()->parseTemplate($template['billing'], true, $this->sc);

		if($data['order_use_shipping'] == 1)
		{
			$data['shipping_address'] = e107::getParser()->parseTemplate($template['shipping'], true, $this->sc);
		}

		$this->sc->setVars($data);

		return e107::getParser()->parseTemplate($template['main'], true, $this->sc);
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

		if(!empty($this->get['download']))
		{
			if(!$this->downloadFile($this->get['download']))
			{
				// $bread = $this->setBreadcrumb();
				$this->setBreadcrumb();
				$msg = e107::getMessage()->render('vstore');

				// $ns->tablerender($this->captionBase, $bread . $msg, 'vstore-download-failed');
				$ns->tablerender($this->captionBase, $msg, 'vstre-download-failed');

				return null;
			}
			else
			{
				// Not needed but ...
				// $bread = $this->setBreadcrumb();
				$this->setBreadcrumb();
				$msg = e107::getMessage()->addSuccess('File successfully downloaded!')->render('vstore');

				// $ns->tablerender($this->captionBase, $bread . $msg, 'vstore-download-done');
				$ns->tablerender($this->captionBase, $msg, 'vstore-download-done');

				return null;
			}
		}

		if($this->getMode() == 'return')
		{
			// print_a($this->post);
			// $bread = $this->setBreadcrumb();
			$this->setBreadcrumb();
			$text = $this->checkoutComplete();
			$msg = e107::getMessage()->render('vstore');

			// $ns->tablerender($this->captionBase, $bread . $msg . $text, 'vstore-cart-complete');
			$ns->tablerender($this->captionBase, $msg . $text, 'vstore-cart-complete');

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
					$result = $this->validateCustomerData($this->post['cust']); // billing.
					if(!$result)
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
					if(!$result)
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
				// $bread = $this->setBreadcrumb();
				$this->setBreadcrumb();

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

				// $ns->tablerender($this->captionBase, $bread . $text, 'vstore-cart-list');
				$ns->tablerender($this->captionBase, $text, 'vstore-cart-list');

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

				if(empty(vstore::getCustomerData(true)))
				{
					$text .= e107::getMessage()->addError('Billing address is missing!', 'vstore')->render('vstore');
				}
				elseif(vartrue($this->post['order_use_shipping']) && empty($this->getShippingData(true)))
				{
					$text .= e107::getMessage()->addError('No shipping address set!', 'vstore')->render('vstore');
				}
				elseif(empty($this->getCheckoutData()))
				{
					$text .= e107::getMessage()->addError('No items to checkout!', 'vstore')->render('vstore');
				}
				else
				{
					// Order confirmation
					$text .= $this->confirmOrderView();
				}

				// $bread = $this->setBreadcrumb();
				$this->setBreadcrumb();
				// $ns->tablerender($this->captionBase, $bread . $text, 'vstore-cart-list');
				$ns->tablerender($this->captionBase, $text, 'vstore-cart-list');

				return null;
			}
			else
			{
				// Customer Data Form
				// $bread = $this->setBreadcrumb();
				$this->setBreadcrumb();

				if(empty($this->getCheckoutData()))
				{
					$text .= e107::getMessage()->addError('No items to checkout!', 'vstore')->render('vstore');
				}
				else
				{
					$text .= $this->checkoutView();
				}
				// $ns->tablerender($this->captionBase, $bread . $text, 'vstore-cart-list');
				$ns->tablerender($this->captionBase, $text, 'vstore-cart-list');

				return null;
			}
		}

		if($this->getMode() == 'confirmed')
		{
			// Order confirmation
			$msg = e107::getMessage()->render('vstore');

			if($msg)
			{
				// $bread = $this->setBreadcrumb();
				$this->setBreadcrumb();
				// $ns->tablerender($this->captionBase, $bread . $msg, 'vstore-cart-list');
				$ns->tablerender($this->captionBase, $msg, 'vstore-cart-list');
			}

			return null;
		}


		if((int)varset($this->get['invoice']) > 0)
		{
			// Display invoice
			$this->order->loadByInvoiceNr($this->get['invoice']);
			$data = $this->renderInvoice();
			$text = '';
			if($data)
			{
				if(vartrue($this->pref['invoice_create_pdf']))
				{
					// if invoice is correctly rendered, convert to pdf
					$this->invoiceToPdf($data, !false);
					$local_pdf = $this->pathToInvoicePdf($this->get['invoice'], $data['userid']);
					$this->downloadInvoicePdf($local_pdf);
				}
				else
				{
					$text = $data;
				}
			}

			$msg = e107::getMessage()->render('vstore');
			if(!empty($msg) || !empty($text))
			{
				// $bread = $this->setBreadcrumb();
				$this->setBreadcrumb();
				// $ns->tablerender($this->captionBase, $bread.$msg.$text, 'vstore-invoice');
				$ns->tablerender($this->captionBase, $msg . $text, 'vstore-invoice');
			}

			return null;
		}


		if($this->getMode() == 'dashboard')
		{
			// render dashboard
			include_once 'inc/vstore_dashboard.class.php';
			$dashboard = new vstore_dashboard();
			$text = $dashboard->render();

			// $bread = $this->setBreadcrumb();
			$this->setBreadcrumb();
			$msg = e107::getMessage()->render('vstore');
			// $ns->tablerender($this->captionBase, $bread . $msg . $text, 'vstore-dashboard');
			$ns->tablerender($this->captionBase, $msg . $text, 'vstore-dashboard');

			return null;
		}


		if($this->getMode() == 'cart')
		{
			// print_a($this->post);
			// $bread = $this->setBreadcrumb();
			$this->setBreadcrumb();
			$text = $this->cartView();
			$msg = e107::getMessage()->render('vstore');
			// $ns->tablerender($this->captionBase, $bread . $msg . $text, 'vstore-cart-list');
			$ns->tablerender($this->captionBase, $msg . $text, 'vstore-cart-list');

			return null;
		}


		if(!empty($this->get['item']))
		{
			$text = $this->productView($this->get['item']);
			// $bread = $this->setBreadcrumb();
			$this->setBreadcrumb();
			$msg = e107::getMessage()->render('vstore');
			// $ns->tablerender($this->captionBase, $bread . $msg . $text, 'vstore-product-view');
			$ns->tablerender($this->captionBase, $msg . $text, 'vstore-product-view');

			return null;
		}


		if($this->get['cat'])
		{
			if($subCategoryText = $this->categoryList($this->get['cat']))
			{
				$subCategoryText .= "<hr />";
			}

			$text = $this->productList($this->get['cat'], true);
			// $bread = $this->setBreadcrumb();
			$this->setBreadcrumb();
			$msg = e107::getMessage()->render('vstore');
			// $ns->tablerender($this->captionBase, $bread . $msg . $subCategoryText . $text, 'vstore-product-list');
			$ns->tablerender($this->captionBase, $msg . $subCategoryText . $text, 'vstore-product-list');
		}
		else
		{
			// No category set, render root category
			$text = $this->categoryList(0, true);
			// $bread = $this->setBreadcrumb();
			$this->setBreadcrumb();
			$msg = e107::getMessage()->render('vstore');
			// $ns->tablerender($this->captionBase, $bread . $msg . $text, 'vstore-category-list');
			$ns->tablerender($this->captionBase, $msg . $text, 'vstore-category-list');
		}
	}


	/**
	 * Custom function to calculate breadcrumb for the current page.
	 *
	 * @return void
	 */
	private function setBreadcrumb()
	{

		// $frm = e107::getForm();
		$array = array();

		$array[] = array('url' => e107::url('vstore', 'index'), 'text' => $this->captionBase);

		if(!isset($this->get['mode']))
		{
			if(!empty($this->get['download']))
			{
				$array[] = array('url' => e107::url('vstore', 'index'), 'text' => LAN_DOWNLOAD);
			}
			elseif(!empty($this->get['invoice']))
			{
				$array[] = array('url' => e107::url('vstore', 'index'), 'text' => 'Invoice');
				$array[] = array(
					'url'  => e107::url('vstore', 'invoice', array('order_invoice_nr' => $this->get['invoice'])),
					'text' => self::formatInvoiceNr($this->get['invoice'])
				);
			}
			else
			{
				$array[] = array('url' => e107::url('vstore', 'index'), 'text' => $this->captionCategories);
			}
		}

		if(!empty($this->get['cat']) || !empty($this->get['item']))
		{
			$c = varset($this->get['cat'],0);
			$cp = varset($this->categories[$c]['cat_parent']);

			if(!empty($cp))
			{
				$pid = $this->categories[$cp]['cat_id'];
				$url = e107::url('vstore', 'category', $this->categories[$pid]);
				$array[] = array('url' => $url, 'text' => $this->categories[$pid]['cat_name']);
			}

			$id = !empty($this->get['item']) ? (int) $this->item['item_cat'] : (int) $this->get['cat'];
			$url = !empty($this->get['item']) ? e107::url('vstore', 'category', $this->categories[$id]) : null;
			$array[] = array('url' => $url, 'text' => $this->categories[$id]['cat_name']);
		}

		if(!empty($this->get['item']))
		{
			$array[] = array('url' => null, 'text' => $this->item['item_name']);
		}

		if(!empty($this->get['add']) || varset($this->get['mode']) === 'cart')
		{
			$array[] = array('url' => null, 'text' => "Shopping Cart");
		}

		if(varset($this->get['mode']) === 'checkout')
		{
			$array[] = array('url' => e107::url('vstore', 'cart'), 'text' => "Shopping Cart");
			$array[] = array('url' => null, 'text' => "Checkout");
		}

		if(varset($this->get['mode']) === 'dashboard')
		{
			if(!empty(trim($this->get['area'])))
			{
				include_once 'inc/vstore_dashboard.class.php';
				$dashboard = new vstore_dashboard();
				$array[] = array(
					'url'  => e107::url('vstore', 'dashboard', array('dash' => $this->get['area'])),
					'text' => $dashboard->getArea()
				);
				if(!empty(trim($this->get['action'])))
				{
					$array[] = array(
						'url'  => e107::url('vstore', 'dashboard_action', array(
							'dash'   => $this->get['area'],
							'action' => $this->get['action'],
							'id'     => $dashboard->getId()
						)),
						'text' => $dashboard->getAction()
					);
				}
			}
			else
			{
				$array[] = array(
					'url'  => e107::url('vstore', 'dashboard', array('dash' => 'dashboard')),
					'text' => "My Dashboard"
				);
			}
		}


		if(ADMIN)
		{
			// print_a($this->categories);
			// print_a($this->item);
			//	$last = end($array);
		//	 print_a($last);
			/* @todo add caonical by taking the last breadcrumb and using e107::link  */
		}
		// return $frm->breadcrumb($array);

		// assign values to the Magic Shortcode:  {---BREADCRUMB---}



		e107::breadcrumb($array);
	}


	/**
	 * Return the active payment gateway information
	 *
	 * @return array
	 */
	public function getActiveGateways()
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

		e107::getMessage()->addSuccess('<br/>' . plugin_vstore_vstore_shortcodes::sc_cart_continueshop(), 'vstore');
		$text = e107::getMessage()->render('vstore');

		if(!empty($this->html_invoice))
		{
			$text .= $this->html_invoice;
		}

		// $text .= "<div class='alert-block'>" . plugin_vstore_vstore_shortcodes::sc_cart_continueshop() . "</div>";

		return $text;
	}


	/**
	 * Render checkout page to enter the customers shipping information
	 * @todo make template.
	 * @return string
	 */
	private function checkoutView()
	{

		$active = $this->getActiveGateways();

		$curGateway = $this->getGatewayType();

		if(!USER && !isset($_POST['as_guest']))
		{

			$text = e107::getForm()->open(
				'gateway-select',
				'post',
				e107::url('vstore', 'checkout', 'sef'),
				array('class' => 'form')
			);
			$text .= $this->renderGuestForm();
			$text .= e107::getForm()->close();


			return $text;
		}


		if(empty($active))
		{
			return "No Payment Options Set";
		}

		$text = e107::getForm()->open(
			'gateway-select',
			'post',
			e107::url('vstore', 'checkout', 'sef'),
			array('class' => 'form')
		);

		$text .= $this->renderCustomerForm();
		$text .= "<hr /><p>";
		$text .= "<i class='fa fa-truck' aria-hidden='true'></i> <a id='shipping-view-toggle' class='e-expandit' href='#shipping-view'>Add a different shipping address</a>";
		$text .= "</p><div id='shipping-view' style='display:none'>";
		$text .= $this->renderShippingForm();
		$text .= "</div>";

		$text .= "<hr /><h3>Select payment method to continue</h3><div class='vstore-gateway-list row'>";

		if(count($active) == 1 && empty($curGateway))
		{
			$curGateway = array_keys($active)[0];
		}
		foreach($active as $gateway => $icon)
		{
			$text .= "
                        <div class='col-6 col-xs-6 col-sm-4 col-4 d-grid' style='margin-bottom:15px'>
                            <label class='btn btn-default btn-light btn-secondary btn-block btn-" . $gateway . " " . ($curGateway == $gateway ? 'active' : '') . " vstore-gateway text-center'>
                                <input type='radio' name='gateway' value='" . $gateway . "' style='display:none;' class='vstore-gateway-radio' required " . ($curGateway == $gateway ? 'checked' : '') . ">
                                " . $icon . "
                                <h4>" . (self::isMollie($gateway) ? $this->getMolliePaymentMethodTitle($gateway) : $this->getGatewayTitle($gateway)) . "</h4>
                            </label>
                        </div>";
		}

		$text .= "</div>";

		/*

					$text .= '<br/>
					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-info">
							<button class="btn btn-default btn-secondary vstore-btn-add-shipping" type="submit" name="mode" value="shipping"><i class="fa fa-truck" aria-hidden="true"></i> Enter shipping address</button>
							<span class="help-text">Use this button to use or enter a separate shipping address.</span>
							</div>
						</div>
					</div>';
					*/
		$text .= '
       
            <div class="row mt-5 mb-5">
                <div class="col-12 col-xs-12">
                    <a class="btn btn-default btn-secondary vstore-btn-back-confirm" href="' . e107::url('vstore', 'cart', 'sef') . '">&laquo; Back</a>
                    <button class="btn btn-primary vstore-btn-buy-now pull-right float-right float-end" type="submit" name="mode" value="confirm">Continue &raquo;</button>
                </div>
            </div>';

		$text .= e107::getForm()->close();

		/**
		 * Only make shipping fields 'required' when they are visible.
		 */
		e107::js('footer-inline', "
		
		$(document).ready(function()
		{
		     $('#shipping-view-toggle.e-expandit').on('click', function() {
		         var opened = $(this).hasClass('open'); // ie. we're closing it. 
		        
		            var fields = ['firstname', 'lastname', 'address', 'state', 'city', 'zip', 'country'];
					fields.forEach(function(item)
					{
						$('#ship-' + item).attr('required', true); 					
					});
		        
		         if(opened === false)
		         {
		            fields.forEach(function(item)
					{
						$('#ship-' + item).attr('required', true); 
					});
		        
		         }
		         else
		         {
		             fields.forEach(function(item)
					{
						$('#ship-' + item).attr('required', false); 
					});
		         }
		            
		     });
		
});	
");





		return $text;


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
			$text = e107::getForm()->open(
				'gateway-select',
				'post',
				e107::url('vstore', 'checkout', 'sef'),
				array('class' => 'form')
			);

			$text .= $this->renderShippingForm();

			$text .= '<br/>
            <div class="row">
                <div class="col-12 col-xs-12">
                    <input type="hidden" name="order_use_shipping" value="1">
                    <a class="btn btn-default btn-secondary vstore-btn-back-confirm" href="' . e107::url('vstore', 'checkout', 'sef') . '">&laquo; Back</a>
                    <button class="btn btn-primary vstore-btn-buy-now pull-right float-right float-end" type="submit" name="mode" value="confirm">Continue &raquo;</button>
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

		$text = e107::getForm()->open('confirm-order', 'post', null, array('class' => 'form'));

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
	public function processGateway($mode = 'init')
	{

		$type = $this->getGatewayType(true);

		e107::getDebug()->log("Processing Gateway: " . $type);

		if(empty($type))
		{
			e107::getMessage()->addError("Invalid Payment Type", 'vstore');
			trigger_error("Invalid payment type");  // debug only
			return false;
		}

		$paymentMethod = '';
		if(self::isMollie($type))
		{
			$paymentMethod = substr($type, 7);
			$type = substr($type, 0, 6);
		}

		list($gateway, $message) = $this->loadGateway($type);

		if($type === 'bank_transfer')
		{
			$mode = 'halt';
		}

		$cardInput = null;
		$data = $this->getCheckoutData();

		if(empty($data['items']))
		{
			e107::getMessage()->addError("Shopping Cart Empty", 'vstore');
			trigger_error("Shopping Cart Empty"); // debug only
			return false;
		}
		else
		{
			$items = array();

			foreach($data['items'] as $var)
			{
				$price = $var['item_price'];
				$itemvarstring = '';
				if(!empty($var['cart_item_vars']))
				{
					$itemprop = self::getItemVarProperties($var['cart_item_vars'], $var['item_price']);

					if($itemprop)
					{
						$itemvarstring = $itemprop['variation'];
					}
				}


				$items[] = array(
					'id'          => $var['item_id'],
					'name'        => $var['item_code'],
					'price'       => (float) $price,
					'description' => $var['item_name'],
					'quantity'    => (int) $var['cart_qty'],
					'tax_rate'    => $var['tax_rate'],
					'file'        => $var['item_download'],
					'vars'        => $itemvarstring,
					'item_vars'   => $var['cart_item_vars']
				);
			}
		}

		if($mode === 'halt')
		{
			// eg. bank-transfer.
			$transID = null;
			$transData = null;
			$this->saveTransaction($transID, $transData, $items);
			$this->resetCart();

			if(!empty($message))
			{
				e107::getMessage()->addSuccess($message, 'vstore');
			}

			e107::getSession('vstore')->clear('_data');
		//	unset($_SESSION['vstore']['_data']);

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
				'amount'         => (float) $data['totals']['cart_grandTotal'],
				'shippingAmount' => (float) $data['totals']['cart_shippingTotal'],
				'currency'       => $data['currency'],
				'items'          => $items,
				'transactionId'  => $this->getCheckoutData('id'),
				'clientIp'       => USERIP,
				'description'    => 'Order date: ' . e107::getDate()->convert_date(time(), 'inputdate'), // required for Mollie
			);

			$tokenKey = $type.'Token';
			if(!empty($_POST[$tokenKey]))
			{
				$_data['token'] = $_POST[$tokenKey];
			}

			if($type == 'mollie')
			{
				$_data['paymentMethod'] = $paymentMethod;
			}

			e107::getSession('vstore')->set('_data', $_data);
			// file_put_contents(__DIR__."/checkoutData.log", var_export($_data,true));
		}
		else // Mode 'return'.
		{

			$method = 'completePurchase';

			if($gateway->supportsAuthorize() && $gateway->supportsCompleteAuthorize())
			{
				// Workaround to make sure the payment is complete and not in pending state
				if($type !== 'paypal')
				{
					$method = 'completeAuthorize';
				}
			}

			// Get stored data.
			$_data = (array) e107::getSession('vstore')->get('_data');
			// Add PayerID, paymentId, token, etc...

			$_data = array_merge($_data, $this->get);
		}

		try
		{
			/** @var \Omnipay\Common\Message\AbstractResponse $response */
			$response = $gateway->$method($_data)->send();
		}
		catch(Exception $e)
		{
			$message = $e->getMessage();
			if($type == 'mollie')
			{
				-$message = $this->getMollieErrorMessage($message);
			}
			e107::getMessage()->addError($message, 'vstore');

			return false;
		}

		if($response->isRedirect())
		{
			// Get transaction ID from the Authorize response.
			if($transID = $response->getTransactionReference())
			{
				// Store transaction ID for later use.
				e107::getSession('vstore')->set('_data/transactionReference', $transID);
			//	$_SESSION['vstore']['_data']['transactionReference'] = $transID;
			}

			// Redirect to offsite payment gateway.
			$response->redirect();
		}
		elseif($response->isSuccessful())
		{
			$order_status = 'P';
			if($response->isPending())
			{
				// is pending => set order status to a new different status
				//$order_status = 'P';
			}
			$transData = $response->getData();
			$transID = $response->getTransactionReference();
			$message = $response->getMessage();
			if($type == 'mollie')
			{
				$message = $this->getMollieErrorMessage($message);
			}

			e107::getMessage()->addSuccess($message, 'vstore');

			$this->saveTransaction($transID, $transData, $items, $order_status); // Order payed > save as Processing
			$this->resetCart();

			e107::getSession('vstore')->clear('_data');
		//	unset($_SESSION['vstore']['_data']);
		}
		else
		{
			$message = $response->getMessage();
			if($type == 'mollie')
			{
				$message = $this->getMollieErrorMessage($message);
			}
			e107::getMessage()->addError($message, 'vstore');
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
	public function getOrderRef($id, $firstname, $lastname)
	{

		$text = substr($firstname, 0, 2);
		$text .= substr($lastname, 0, 2);
		// $text .= date('Y');
		$text .= e107::getParser()->leadingZeros($id, 6);

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
	public function saveTransaction($id, $transData, $items, $order_status = 'N')
	{

		$this->html_invoice = null;

		if(isset($transData['L_ERRORCODE0']) && intval($transData['L_ERRORCODE0']) == 11607)
		{
			// Duplicate REquest.
			return false;
		}

		$customerData = vstore::getCustomerData();

		$fields = $this->pref['additional_fields'];
		$add = array();
		foreach($fields as $key => $value)
		{
			if(isset($customerData['add_field' . $key]))
			{
				$add['add_field' . $key] = array(
					'caption' => strip_tags($value['caption'][e_LANGUAGE]),
					'value'   => ($value['type'] == 'text'
						? $customerData['add_field' . $key]
						: ($customerData['add_field' . $key] ? 'X' : '-'))
				);
				unset($customerData['add_field' . $key]);
			}
		}
		$customerData['additional_fields'] = e107::serialize($add, 'json');


		if(!$this->getShippingType())
		{
			$this->setShippingData($customerData);
		}
		$shippingData = $this->getShippingData();

		$cartData = $this->getCheckoutData();

		$this->order->clear();

		$this->order->order_date = time();
		$this->order->order_session = $cartData['id'];
		$this->order->order_e107_user = USERID;
		$this->order->order_cust_id = '';
		$this->order->order_status = varset($order_status, 'N'); // New
		$this->order->order_items = $items;

		$this->order->order_use_shipping = $this->getShippingType();
		$this->order->order_billing = $customerData;
		$this->order->order_shipping = $shippingData;

		$this->order->order_pay_gateway = $this->getGatewayType(true);
		$this->order->order_pay_status = empty($transData) ? 'incomplete' : 'complete';
		$this->order->order_pay_transid = $id;
		$this->order->order_pay_amount = $cartData['totals']['cart_grandTotal'];
		$this->order->order_pay_currency = $cartData['currency'];
		$this->order->order_pay_tax = $cartData['totals']['cart_taxTotal'];
		$this->order->order_pay_shipping = $cartData['totals']['cart_shippingTotal'];
		$this->order->order_pay_coupon_code = $cartData['totals']['cart_coupon']['code'];
		$this->order->order_pay_coupon_amount = $cartData['totals']['cart_coupon']['amount'];
		$this->order->order_pay_rawdata = array('purchase' => $transData);
		$this->order->setInvoiceNr();
		$this->order->setOrderLog('Order created' . (empty($transData) ? '' : ' and paid') . '.');

		$mes = e107::getMessage();
		if($this->order->save())
		{
			// Order saved, update inventory before doing any "secondary" work...
			$this->updateInventory($this->order->order_items);

			if(USER && !$this->saveCustomer(
					$customerData,
					$shippingData,
					$this->getShippingType(),
					$this->getGatewayType(true)
				))
			{
				$mes->addError('Unable to save/Update customer data!', 'vstore');
			}

			// Set order ref code
			if(!$this->order->setOrderRef() || !$this->order->save())
			{
				$mes->addDebug('Unable to update order ref code!' . $this->order->getLastError(), 'vstore');
			}

			// Render the in
			$pdf_data = $this->renderInvoice();
			$pdf_file = '';
			if($this->pref['invoice_create_pdf'] && is_array($pdf_data))
			{
				$this->invoiceToPdf($pdf_data);
				$pdf_file = $this->pathToInvoicePdf($this->order->order_invoice_nr, $pdf_data['userid']);
			}

			$mes->addSuccess("Your order <b>#" . $this->order->order_refcode .
				"</b> is complete and you will receive a order confirmation " .
				"with all details within the next few minutes by email.", 'vstore');

			$this->order->emailCustomer('default', $pdf_file);

			if(!empty($transData))
			{
				$this->setCustomerUserclass(USERID, $items);
			}
		}
		else
		{
			$mes->addError("Unable to save transaction");
			$this->order->emailCustomer('error');
		}
		if(!$this->pref['invoice_create_pdf'] && !empty($pdf_data) && !is_array($pdf_data))
		{
			$this->html_invoice = $pdf_data;
		}

		return null;
	}

	private function saveCustomer($customerData, $shippingData, $use_shipping, $gateway)
	{

		$data = array();

		foreach($customerData as $key => $value)
		{
			$data['cust_' . $key] = $value;
		}


		$data['cust_shipping'] = json_encode($shippingData, JSON_PRETTY_PRINT);
		$data['cust_use_shipping'] = ($use_shipping ? 1 : 0);
		$data['cust_gateway'] = $gateway;
		$data['cust_datestamp'] = time();

		$sql = e107::getDb();

		if($sql->select('vstore_customer', 'cust_id', 'cust_e107_user=' . USERID))
		{
			$result = $sql->update('vstore_customer', array('data' => $data, 'WHERE' => 'cust_e107_user=' . USERID));
		}
		else
		{
			$data['cust_e107_user'] = USERID;
			$result = $sql->insert('vstore_customer', $data);
			if($result)
			{
				$ref = $this->getOrderRef($result, $customerData['firstname'], $customerData['lastname']);
				$result = $sql->update('vstore_customer', array(
					'data'  => array('cust_refcode' => $ref),
					'WHERE' => 'cust_e107_user=' . USERID
				));
			}
		}

		return $result;
	}

	/**
	 * Add userclass to customer
	 *
	 * @param int $userid Userid of the customer
	 * @param array $items Array of order_items
	 * @return void
	 */
	public static function setCustomerUserclass($userid, $items)
	{

		$uc_global = e107::pref('vstore', 'customer_userclass');
		if($uc_global == -1)
		{
			$usr = e107::getSystemUser($userid);
			// set userclass as defined in product
			if(!empty($items) && is_array($items))
			{
				$sql = e107::getDb();
				$ids = array();

				foreach($items as $item)
				{
					$ids[] = intval($item['id']);
				}

				if($sql->select(
					'vstore_items',
					'item_userclass',
					'FIND_IN_SET(item_id, "' . implode(',', $ids) . '")'
				))
				{
					while($row = $sql->fetch())
					{
						$uc = intval($row['item_userclass']);
						if($uc > 0 && $uc != 255)
						{
							$usr->addClass($uc);
						}
					}
				}
			}
		}
		elseif($uc_global != 255)
		{
			$usr = e107::getSystemUser($userid);
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
	public static function getCustomerUserclass($items)
	{

		$uc_global = e107::pref('vstore', 'customer_userclass');
		if($uc_global == -1)
		{
			// set userclass as defined in product
			if(!empty($items) && is_array($items))
			{
				$sql = e107::getDb();
				$ucs = array();
				foreach($items as $item)
				{
					$uc = $sql->retrieve('vstore_items', 'item_userclass', 'item_id=' . intval($item['id']));
					if($uc > 0 && $uc != 255)
					{
						$ucs[] = $uc;
					}
				}
				$ucs = array_unique($ucs);
				if($ucs && count($ucs))
				{
					return implode(',', $ucs);
				}
			}
		}
		elseif($uc_global != 255)
		{
			// all classes except No One (inactive)
			return '' . $uc_global;
		}

		return false;
	}

	/**
	 * Update the items inventory based on the given json string
	 *
	 * @param string|array $json json formatted string or array containing the data
	 * @return void
	 */
	private function updateInventory($json)
	{

		$sql = e107::getDb();
		if(!is_array($json))
		{
			$arr = e107::unserialize($json);
		}
		else
		{
			$arr = $json;
		}


		// todo: update item_vars_inventory (item_vars = cart_item_vars)

		foreach($arr as $row)
		{
			// Update inventory
			// item_vars: e.g. 1,2|iuitz,black
			// item_vars_inventory: e.g. {"balu": {"black": "10", "white": "20"}, "iuitz": {"black": "30", "white": "40"}}

			if(!empty($row['quantity']) && !empty($row['id']) && !empty($row['name']))
			{
				$reduceBy = (int) $row['quantity'];

				$itemdata = $sql->retrieve(
					'vstore_items',
					'item_inventory, item_vars_inventory',
					'item_id = ' . (int) $row['id']
				);
				$curQuantity = (int) $itemdata['item_inventory'];

				$varsdata = '';
				if(!empty($row['item_vars']))
				{
					$item_vars = array_values($this->item_vars_toArray($row['item_vars']));
					$varsdata = e107::unserialize($itemdata['item_vars_inventory']);
					$vars_quantity = -1;
					if(count($item_vars) == 1)
					{
						$vars_quantity = (int) varset($varsdata[$item_vars[0]], -1);
						if($vars_quantity > 0)
						{
							if($vars_quantity == -1)
							{
								$reduceToVars = -1;
							}
							elseif($reduceBy > $vars_quantity)
							{
								$reduceToVars = 0;
							}
							else
							{
								$reduceToVars = $vars_quantity - $reduceBy;
							}
							$varsdata[$item_vars[0]] = $reduceToVars;
						}
					}
					elseif(count($item_vars) == 2)
					{
						$vars_quantity = (int) varset($varsdata[$item_vars[0]][$item_vars[1]], -1);
						if($vars_quantity > 0)
						{
							if($vars_quantity == -1)
							{
								$reduceToVars = -1;
							}
							elseif($reduceBy > $vars_quantity)
							{
								$reduceToVars = 0;
							}
							else
							{
								$reduceToVars = $vars_quantity - $reduceBy;
							}
							$varsdata[$item_vars[0]][$item_vars[1]] = $reduceToVars;
						}
					}
					$varsdata = e107::serialize($varsdata, 'json');
				}


				if($curQuantity > 0 || !empty($varsdata))
				{
					if($curQuantity == -1)
					{
						$reduceTo = -1;
					}
					elseif($reduceBy > $curQuantity)
					{
						$reduceTo = 0;
					}
					else
					{
						$reduceTo = $curQuantity - $reduceBy;
					}

					$update = array(
						'data'  => array(
							'item_inventory' => $reduceTo,
						),
						'WHERE' => 'item_id = "' . intval($row['id']) . '"'
					);
					if(!empty($varsdata))
					{
						$update['data']['item_vars_inventory'] = $varsdata;
					}

					// if ($sql->update(
					//     'vstore_items',
					//     'item_inventory = item_inventory - ' . $reduceBy .
					//     ' WHERE item_id=' . intval($row['id']) . ' AND item_code="' . $row['name'] . '" LIMIT 1'
					// )) {
					if($sql->update('vstore_items', $update))
					{
						e107::getMessage()->addDebug(
							"Updated item_inventory of " . $row['name'] . " to " . $reduceTo
						);
						if(!empty($varsdata))
						{
							e107::getMessage()->addDebug(
								"Updated item_vars_inventory of " . $row['name'] . " to " . $reduceToVars
							);
						}
					}
					else
					{
						e107::getMessage()->addDebug(
							"Was UNABLE to update item_inventory of " . $row['name'] .
							" (" . $row['id'] . ") to " . $reduceTo
						);
						if(!empty($varsdata))
						{
							e107::getMessage()->addDebug(
								"Was UNABLE to update item_vars_inventory of " . $row['name'] .
								" (" . $row['id'] . ") to " . $reduceToVars
							);
						}
					}
				}
				else
				{
					e107::getMessage()->addDebug(
						"Unlimited item not reduced: " . $row['name'] . " (" . $row['id'] . ")"
					);
				}
			}
		}
	}
/*
	public static function getGateways()
	{
		return self::$gateways;
	}*/

	/**
	 * Return the icon for the given gateway
	 *
	 * @param string $type gateway name
	 * @param string $size default 5x (2x, 3x, 4x, 5x)
	 * @return string
	 */
	public static function getGatewayIcon($type = '', $size = '5x')
	{

		if(self::isMollie($type))
		{
			return self::getMolliePaymentMethodIcon($type, $size);
		}
		$text = !empty(self::$gateways[$type]) ? self::$gateways[$type]['icon'] : '';

		return e107::getParser()->toIcon($text, array('size' => $size, 'fw'=>true));
	}

	/**
	 * Return the title/name of the given gateway
	 *
	 * @param string $type
	 * @return string
	 */
	public static function getGatewayTitle($type)
	{

		if(self::isMollie($type))
		{
			return self::getMolliePaymentMethodTitle($type);
		}

		return self::$gateways[$type]['title'];
	}


	/**
	 * Return the type of the current gateway
	 *
	 * @param string $type
	 * @return string
	 */
	private function getGatewayType($forceSession = false)
	{

		if(!$gateways = e107::getSession('vstore')->get('gateway'))
		{
			trigger_error("No gateway selected!");
		}

		if(isset($gateways['type']) || $forceSession)
		{
			return $gateways['type'];
		}

		return e107::getDb()->retrieve('vstore_customer', 'cust_gateway', 'cust_e107_user=' . USERID);
	}


	/**
	 * Set the type of the current gateway
	 *
	 * @param string $type
	 * @return void
	 */
	public function setGatewayType($type = '')
	{

		e107::getSession('vstore')->set('gateway/type', strtolower($type));
		//$_SESSION['vstore']['gateway']['type'] = $type;
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
	protected function updateCart($type = 'modify', $array = array())
	{

		$sql = e107::getDb();

		if($type == 'modify')
		{
			foreach($array as $id => $val)
			{
				$itemid = (int) $val['id'];
				$qty = (int) $val['qty'];
				$itemvars = $val['vars'];

				if(!empty($itemvars))
				{
					list($itemkeys, $itemvalues) = explode('|', $itemvars);
					$itemkeys = explode(',', $itemkeys);
					$itemvalues = explode(',', $itemvalues);
					$itemvars = array();

					foreach($itemkeys as $k => $v)
					{
						$itemvars[$v] = $itemvalues[$k];
					}
				}

				// Check if item exists and is active
				$iteminfo = $sql->retrieve('vstore_items', 'item_active, item_name', 'item_id=' . $itemid);

				if($iteminfo && $iteminfo['item_active'] == 0)
				{
					// Item not found or not longer active => Remove from cart
					e107::getMessage()->addWarning(
						'We\'re sorry, but we could\'t find the selected item "' . $iteminfo['item_name'] .
						'" or it is no longer active!',
						'vstore'
					);
					$sql->delete(
						'vstore_cart',
						'cart_id = ' . intval($id) . ' AND cart_item = ' . intval($itemid) . ' LIMIT 1'
					);
					continue;
				}

				$itemname = $iteminfo['item_name'];

				// check if item is in stock
				$inStock = $this->getItemInventory($itemid, $itemvars);
				if($qty > $inStock && $inStock >= 0)
				{
					$qty = $inStock;
					$itemvarstring = '';
					if(!empty($itemvars))
					{
						$itemprop = vstore::getItemVarProperties($itemvars, 0);

						if($itemprop)
						{
							$itemvarstring = $itemprop['variation'];
						}
					}
					$itemname .= $itemvarstring;
					e107::getMessage()->addWarning(
						'The entered quantity for "' . $itemname .
						'" exceeds the number of items in stock!<br/>The quantity has been adjusted!',
						'vstore'
					);
				}

				$sql->update(
					'vstore_cart',
					'cart_qty = ' . intval($qty) . ' WHERE cart_id = ' . intval($id) . ' LIMIT 1'
				);
			}
		}

		if($type == 'remove')
		{
			foreach($array as $id => $qty)
			{
				$sql->delete('vstore_cart', 'cart_id = ' . intval($id) . ' LIMIT 1');
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
		e107::getDb()->delete('vstore_cart', 'cart_id=' . varset($_COOKIE["cartId"]));
		$_COOKIE["cartId"] = false;
		cookie("cartId", null, time() - 3600);
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
			$value = md5(session_id() . time());

			cookie("cartId", $value, time() + ((3600 * 24) * 2));

			return $value;
		}
	}

	/**
	 * Manually set the card id. - Mostly for unit testing.
	 * @param $id
	 */
	public function setCartId($id)
	{
		$this->cartId = $id;
	}

	/**
	 * Render the list of categories
	 *
	 * @param integer $parent 0 = root categories
	 * @param boolean $np true = render nextprev control; false = dont't render nextprev
	 * @return string
	 */
	public function categoryList($parent = 0, $np = false)
	{

		$this->from = vartrue($this->get['frm'], 0);

		$query = 'SELECT *
        FROM #vstore_cat
        WHERE cat_class IN (' . USERCLASS_LIST . ') AND cat_parent = ' . $parent . '
        ORDER BY cat_order
        LIMIT ' . $this->from . "," . $this->perPage;
		if((!$data = e107::getDb()->retrieve($query, true)) && intval($parent) == 0)
		{
			return e107::getMessage()->addInfo('No categories available!', 'vstore')->render('vstore');
		}
		elseif(!$data)
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
				'tmpl'    => 'bootstrap',
				'total'   => $this->categoriesTotal,
				'amount'  => intval($this->perPage),
				'current' => $this->from,
				'url'     => e107::url('vstore', 'base', null, array(
					'query' => array('frm' => '--FROM--')
				))
			);

			global $nextprev_parms;

			$nextprev_parms = http_build_query($nextprev, false);

			$text .= $tp->parseTemplate("{NEXTPREV: " . $nextprev_parms . "}");
		}

		return $text;
	}


	/**
	 * Render the list of products
	 *
	 * @param integer $category selected category id
	 * @param boolean $np render nextpref yes/no
	 * @param string $templateID name of the template to use
	 * @param int $item_count number of items to show (used for menu)
	 * @return string
	 */
	public function productList($category = 1, $np = false, $templateID = 'list', $item_count = null)
	{

		if(!empty($item_count))
		{
			$this->from = 0;
			$this->perPage = $item_count;
		}

		// Check if out-of-stock products should be displayed or not (default: show)
		$hide_outofstock = '';
		if(!varset($this->pref['show_outofstock'], true))
		{
			$hide_outofstock = ' AND item_inventory != 0';
		}

		if(!$data = e107::getDb()->retrieve(
			'SELECT SQL_CALC_FOUND_ROWS *, cat_class
            FROM #vstore_items
            LEFT JOIN #vstore_cat ON (item_cat = cat_id)
            WHERE cat_class IN (' . USERCLASS_LIST . ') AND item_active=1 AND item_cat = ' . (int) $category .
			$hide_outofstock . '
            ORDER BY item_order LIMIT ' . $this->from . ',' . $this->perPage,
			true
		))
		{
			return e107::getMessage()->addInfo("No products available in this category", 'vstore')->render('vstore');
		}

		$count = e107::getDb()->foundRows();

		$categoryRow = $this->categories[$category];

		$tp = e107::getParser();
		$this->sc->setVars($categoryRow);
		$template = e107::getTemplate('vstore', 'vstore', $templateID);

		// e107::getDebug()->log($this->sc);

		$text = $tp->parseTemplate($template['start'], true, $this->sc);

		foreach($data as $row)
		{
			$id = $row['item_cat'];
			$row['cat_id'] = $row['item_cat'];
			$row['cat_sef'] = $this->categories[$id]['cat_sef'];
			$row['item_sef'] = eHelper::title2sef($row['item_name'], 'dashl');

			$this->sc->setVars($row);
			$text .= $tp->parseTemplate($template['item'], true, $this->sc);
		}

		$text .= $tp->parseTemplate($template['end'], true, $this->sc);

		if($np === true)
		{
			// Check if SEF urls are deactivated for vstore
			$sefActive = e107::getPref('e_url_list');

			$nextprev = array(
				'tmpl'    => 'bootstrap',
				'total'   => $count,
				'amount'  => intval($this->perPage),
				'current' => $this->from,
				'url'     => e107::url('vstore', 'category', $row, array(
					'legacy' => empty($sefActive['vstore']),
					'query'  => array('frm' => '--FROM--')
				))
			);

			global $nextprev_parms;

			$nextprev_parms = http_build_query($nextprev, false);

			$text .= $tp->parseTemplate("{NEXTPREV: " . $nextprev_parms . "}");
		}


		return $text;
	}


	/**
	 * Render a single product/item
	 *
	 * @param integer $id item_id
	 * @return string
	 */
	protected function productView($id = 0)
	{

		if(!$row = e107::getDb()->retrieve(
			'SELECT * FROM #vstore_items WHERE item_active=1 AND item_id = ' . intval($id) . '  LIMIT 1',
			true
		))
		{
			e107::getMessage()->addInfo("No products available in this category", 'vstore');

			return null;
		}

		$this->item = $row[0];

		$tp = e107::getParser();
		$frm = e107::getForm();

		$catid = $this->item['item_cat'];
		$data = array_merge($row[0], $this->categories[$catid]);

		// print_a($data);

		$this->sc->setVars($data);
		$this->sc->wrapper('vstore/item');

		$tmpl = e107::getTemplate('vstore');


		$text = $tmpl['item']['main'];

		$tabData = array();

		if(!empty($data['item_details']))
		{
			$tabData['details'] = array('caption' => 'Details', 'text' => $tmpl['item']['details']);
		}

		if($media = e107::unserialize($data['item_pic']))
		{
			foreach($media as $v)
			{
				if($tp->isVideo($v['path']))
				{
					$tabData['videos'] = array('caption' => 'Videos', 'text' => $tmpl['item']['videos']);
					break;
				}
			}
		}

		if(!empty($data['item_reviews']))
		{
			$tabData['reviews'] = array('caption' => 'Reviews', 'text' => $tmpl['item']['reviews']);
		}


		if(!empty($data['item_related']))
		{
			$tmp = e107::unserialize($data['item_related']);
			if(!empty($tmp['src']))
			{
				$tabData['related'] = array(
					'caption' => varset($tmp['caption'], 'Related'),
					'text'    => $tmpl['item']['related']
				);
			}
		}

		if(!empty($data['item_files']))
		{
			$tmp = e107::unserialize($data['item_files']);
			if(!empty($tmp[0]['path']))
			{
				$tabData['files'] = array('caption' => 'Files', 'text' => $tmpl['item']['files']);
			}
		}

		if(!empty($this->pref['howtoorder']))
		{
			$tabData['howto'] = array('caption' => 'How to Order', 'text' => $tmpl['item']['howto']);
		}

		if(!empty($tabData))
		{
			$text .= $frm->tabs($tabData);
		}

		return $tp->parseTemplate($text, true, $this->sc);

	}


	/**
	 * Add a single item to the cart
	 * if the item is already on the list increase the quantity by 1
	 *
	 * @param int $id item_id
	 * @param array $itemvars array of item variations
	 * @return bool true on success
	 */
	public function addToCart($id, $itemvars = false)
	{

		// if (USERID === 0) {
		//     // Allow only logged in users to add items to the cart
		//     e107::getMessage()->addError('You must be logged in before adding products to the cart!', 'vstore');
		//     return false;
		// }

		$itemvars = $this->fixItemVarArray($itemvars);
		$sql = e107::getDb();

		$iteminfo = $sql->retrieve('vstore_items', 'item_active, item_tax_class', 'item_id=' . intval($id));
		if(!$iteminfo['item_active'])
		{
			e107::getMessage()->addWarning('We\'re sorry, but this item is not longer available!', 'vstore');
			$sql->delete('vstore_cart', 'cart_session="' . $this->cartId . '" AND cart_item=' . intval($id));

			return false;
		}

		$where = 'cart_session = "' . $this->cartId . '" AND cart_item = ' . intval($id);
		if(is_array($itemvars))
		{
			$where .= ' AND cart_item_vars LIKE "' . self::item_vars_toDB($itemvars) . '"';
		}


		// Item Exists.
		if($sql->select('vstore_cart', 'cart_qty, cart_item_vars', $where . ' LIMIT 1'))
		{
			$cart = $sql->fetch();

			$inventory = $this->getItemInventory(intval($id), $itemvars);

			if($inventory && (intval($cart['cart_qty']) + 1) <= $inventory)
			{
				if($sql->update('vstore_cart', 'cart_qty = cart_qty +1 WHERE ' . $where))
				{
					return true;
				}
			}
			e107::getMessage()->addWarning('Quantity of selected product exceeds the number of items in stock!<br/>' .
				'The quantity has been adjusted!', 'vstore');

			return false;
		}


		$insert = array(
			'cart_id'             => 0,
			'cart_session'        => $this->cartId,
			'cart_e107_user'      => USERID,
			'cart_status'         => '',
			'cart_item'           => intval($id),
			'cart_item_vars'      => $itemvars ? self::item_vars_toDB($itemvars) : '',
			'cart_item_tax_class' => vartrue($iteminfo['item_tax_class'], 'standard'),
			'cart_qty'            => 1
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

		if(!is_array($itemvars))
		{
			return false;
		}
		$result = array();
		if(array_key_exists(0, $itemvars))
		{
			foreach($itemvars as $value)
			{
				list($id, $name) = explode('-', $value, 2);
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

		if(!is_array($itemvarsarray))
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

		if(empty($itemvarsstring) || strpos($itemvarsstring, '|') === false)
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
	public function getItemInventory($itemid, $itemvars = false)
	{

		$itemvars = $this->fixItemVarArray($itemvars);

		$sql = e107::getDb();

		if($itemvars && count($itemvars))
		{
			$itemvarkeys = array_values($itemvars);
			$where = 'item_id=' . intval($itemid);

			if($sql->select('vstore_items', 'item_vars_inventory', $where))
			{
				$inventory = array_shift($sql->fetch());

				$inventory = e107::unserialize($inventory);

				if(count($itemvarkeys) == 1)
				{
					$qty = (int) $inventory[$itemvarkeys[0]];
				}
				elseif(count($itemvarkeys) == 2)
				{
					$qty = (int) $inventory[$itemvarkeys[0]][$itemvarkeys[1]];
				}
				else
				{
					e107::getMessage()->addDebug('Invalid number of item_vars!', 'vstore');

					return 0;
				}

				if($qty < 0)
				{
					return 9999999;
				}

				return $qty;
			}

			e107::getMessage()->addDebug('Item not found!', 'vstore');

			return 0;
		}
		else
		{
			$inventory = (int) $sql->retrieve('vstore_items', 'item_inventory', 'item_id = ' . intval($itemid));
			if($inventory < 0)
			{
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

		return e107::getDb()->retrieve('SELECT c.*, i.*, cat.cat_name, cat.cat_sef
        FROM `#vstore_cart` AS c
        LEFT JOIN `#vstore_items` as i ON (c.cart_item = i.item_id)
        LEFT JOIN `#vstore_cat` as cat ON (i.item_cat = cat.cat_id)
        WHERE c.cart_session = "' . $this->cartId . '" AND c.cart_status ="" ', true);
	}


	/**
	 * Render the cart
	 *
	 * @return string
	 */
	protected function cartView()
	{

		if(!$data = $this->getCartData())
		{
			return e107::getMessage()->addInfo("Your cart is empty.", 'vstore')->render('vstore');
		}

		$checkoutData = $this->prepareCheckoutData($data);

		if(!is_array($checkoutData))
		{
			return $checkoutData;
		}

		$tp = e107::getParser();
		$frm = e107::getForm();
		$template = e107::getTemplate('vstore', 'vstore_cart');

		$text = $frm->open('cart', 'post', e107::url('vstore', 'cart'));

		$text .= e107::getMessage()->render('vstore');

		$text .= '<div class="row">
                <div class="col-sm-12 col-md-12">';

		$text .= $tp->parseTemplate($template['start'], true, $this->sc);

		foreach($checkoutData['items'] as $row)
		{
			$this->sc->setVars($row);
			$text .= $tp->parseTemplate($template['item'], true, $this->sc);
		}

		$this->sc->setVars($checkoutData['totals']);

		$text .= $tp->parseTemplate($template['end'], true, $this->sc);
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
	public function prepareCheckoutData($data, $isCheckoutData = false, $fromSitelink = false)
	{
		$sql = e107::getDb();
		$cust = vstore::getCustomerData();
		$isBusiness = !empty($cust['vat_id']);

		$taxCountry = varset($this->pref['tax_business_country']);

		$country = isset($cust['country']) ? $cust['country'] : $taxCountry;

		$isLocal = ($country == $taxCountry);

		$coupon = '';
		$checkoutData['coupon'] = array('code' => '', 'amount' => 0.0, 'amount_net' => 0.0);

		$hasCoupon = false;

		if(!empty($this->post['cart_coupon_code']))
		{
			$this->post['cart_coupon_code'] = trim($this->post['cart_coupon_code']);
		}

		if(!$isCheckoutData && !empty($this->post['cart_coupon_code']))
		{
			// coupon code was posted
			$coupon = e107::getDb()->retrieve(
				'vstore_coupons',
				'*',
				sprintf('coupon_code="%s"', trim($this->post['cart_coupon_code']))
			);
			$hasCoupon = true;
		}
		elseif($isCheckoutData || !isset($this->post['cart_coupon_code']))
		{
			// data is cart data
			// or
			// reuse saved coupon code
			if($isCheckoutData)
			{
				$coupon = trim($data['coupon']['code']);
			}
			else
			{
				$chk = $this->getCheckoutData();
				$coupon = !empty($chk['coupon']['code']) ? trim($chk['coupon']['code']) : '';
				unset($chk);
			}
			if($coupon)
			{
				$coupon = e107::getDb()->retrieve('vstore_coupons', '*', sprintf('coupon_code="%s"', $coupon));
				$hasCoupon = true;
			}
		}

		if($coupon)
		{
			// assign coupon code
			$checkoutData['coupon']['code'] = strtoupper(trim($coupon['coupon_code']));
		}
		elseif($hasCoupon)
		{
			e107::getMessage()->addError('Invalid coupon-code!', 'vstore');
		}

		$subTotal = 0;
		$subTotalNet = 0;
		$couponTotal = 0;
		$netTotal = array();
		$taxTotal = array();

		$checkoutData['id'] = ($isCheckoutData ? $data['id'] : $this->getCartId());

		$count_active = 0;
		$items = $data;
		if($isCheckoutData)
		{
			$items = $data['items'];
		}
		unset($data);

		foreach($items as $row)
		{
			if(!$this->isItemActive($row['cart_item']))
			{
				e107::getMessage()->addWarning('We\'re sorry, but the item "' . $row['item_name'] .
					'" is missing or not longer active and has been removed from the cart!', 'vstore');
				$sql->delete('vstore_cart', 'cart_id=' . $row['cart_id'] . ' AND cart_item=' . $row['cart_item']);
				continue;
			}

			$count_active++;

			// Handle item variations
			$price = $row['item_price'];
			$row['itemvarstring'] = '';
			if(!empty($row['cart_item_vars']))
			{
				$varinfo = self::getItemVarProperties($row['cart_item_vars'], $row['item_price']);
				if($varinfo)
				{
					if(!$isCheckoutData)
					{
						$price += $varinfo['price'];
						$row['item_price'] = $price;
					}
					$row['itemvarstring'] = $varinfo['variation'];
				}
			}

			$item_total = $price * $row['cart_qty'];

			// Calc coupon amount for this item
			$coupon_amount = vstore::calcCouponAmount($coupon, $row);
			$checkoutData['coupon']['amount'] += $coupon_amount;

			$row['is_business']    = $isBusiness;
			$row['is_local']       = $isLocal;
			$row['tax_rate']       = vstore::getTaxRate($row['cart_item_tax_class'], varset($cust['country']));
			$row['tax_amount']     = vstore::calcTaxAmount($item_total, $row['tax_rate']);
			$row['item_price_net'] = $this->calcNetPrice($price, $row['tax_rate']);

			$row['item_total']     = $item_total;
			$row['item_total_net'] = $item_total; // $this->calcNetPrice($item_total, $row['tax_rate']);


			$rateKey = ''.$row['tax_rate'];

			if(!isset($taxTotal[$rateKey]))
			{
				$taxTotal[$rateKey] = 0;
			}

			if(!isset($netTotal[$rateKey]))
			{
				$netTotal[$rateKey] = 0;
			}

			if(!isset($taxTotal[$rateKey]))
			{
				$taxTotal[$rateKey] = 0;
			}


			$taxTotal[$rateKey] += vstore::calcTaxAmount($coupon_amount, $row['tax_rate']);
			$checkoutData['coupon']['amount_net'] += $this->calcNetPrice($coupon_amount, $row['tax_rate']);

			$netTotal[$rateKey] += $row['item_total_net'];
			$taxTotal[$rateKey] += $row['tax_amount'];

			$subTotal += $item_total;
			$subTotalNet += $row['item_total_net'];

			$checkoutData['items'][] = $row;
		}


		if($count_active == 0)
		{
			return ($fromSitelink
				? null
				: e107::getMessage()->addInfo("Your cart is empty.", 'vstore')->render('vstore'));
		}


		$shippingTotal = vstore::calcShippingCost($checkoutData['items'], $this->pref);
		$shippingNet = 0.0;

		// calc shipping tax
		if(count($netTotal) > 0)
		{
			$sum = array_sum($netTotal);
			foreach($netTotal as $tax_rate => $value)
			{
				$gross = ($value / $sum) * $shippingTotal;
				$taxTotal['' . $tax_rate] += vstore::calcTaxAmount($gross, $tax_rate);
				$shippingNet += $this->calcNetPrice($gross, $tax_rate);
			}
		}

		$grandTotal = $subTotal + $shippingTotal + $checkoutData['coupon']['amount'];
		foreach($taxTotal as $amount)
		{
			$grandTotal += $amount;
		}

		$grandNet = $subTotalNet + $shippingNet + $checkoutData['coupon']['amount_net'];

		$totals = array(
			'is_business'        => $isBusiness,
			'is_local'           => $isLocal,
			'cart_taxTotal'      => $taxTotal,
			'cart_subTotal'      => $subTotal,
			'cart_shippingTotal' => $shippingTotal,
			'cart_grandTotal'    => $grandTotal,

			'cart_subNet'      => round($subTotalNet,2),
			'cart_shippingNet' => $shippingNet,
			'cart_grandNet'    => round($grandNet,2),

			'cart_coupon' => $checkoutData['coupon']
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
	public function setCheckoutData($data = array())
	{

		$ret = $data;
		$ret['currency'] = $this->currency;

		e107::getSession('vstore')->set('checkout', $ret);
	}


	/**
	 * Store shipping data in session variable
	 *
	 * @param array $data data to store
	 * @return void
	 */
	private function setShippingData($data = array())
	{

		$fields = self::getShippingFields();
		$ret = array();
		foreach($fields as $fld)
		{
			if(!isset($data[$fld]))
			{
				continue;
			}

			$ret[$fld] = trim(strip_tags($data[$fld]));
		}

		e107::getSession('vstore')->set('shipping', $ret);
	}

	/**
	 * Return the shipping data from the session variable
	 *
	 * @return array
	 */
	private function getShippingData($forceSession = false)
	{
		$shipping = e107::getSession('vstore')->get('shipping');
		if(!empty($shipping) || $forceSession)
		{
			return $shipping;
		}

		return e107::unserialize(e107::getDb()->retrieve(
			'vstore_customer',
			'cust_shipping',
			'cust_e107_user=' . USERID
		));
	}


	/**
	 * Set the selected shipping option
	 *
	 * @param int 1 = use shipping address; 0 = use customer address
	 * @return void
	 */
	private function setShippingType($type)
	{
		e107::getSession('vstore')->set('shipping_type', (vartrue($type) ? 1 : 0));
	//	$_SESSION['vstore']['shipping_type'] = (vartrue($type) ? 1 : 0);
	}


	/**
	 * Return the selected shipping option
	 *
	 * @return int 1 = use shipping address; 0 = use customer address
	 */
	private function getShippingType()
	{
		$type = e107::getSession('vstore')->get('shipping_type');
		return ($type ? 1 : 0);
	}


	/**
	 * Store customer data in session variable
	 *
	 * @param array $data data to store
	 * @return void
	 */
	public function setCustomerData($data = array())
	{

		$fields = self::getCustomerFields();
		$ret = array();
		foreach($fields as $fld)
		{
			if(!isset($data[$fld]))
			{
				continue;
			}

			$ret[$fld] = trim(strip_tags($data[$fld]));
		}

		e107::getSession('vstore')->set('customer', $ret);
	}


	/**
	 * Return the customer data from the database if session is empty
	 *
	 * @param bool $forceSession
	 * @return array
	 */
	public static function getCustomerData($forceSession = false)
	{

		$customer = e107::getSession('vstore')->get('customer');

		if(!empty($customer) || $forceSession)
		{
			return $customer;
		}
		$row = e107::getDb()->retrieve('vstore_customer', '*', 'cust_e107_user=' . USERID);
		$result = false;
		if($row)
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
	public function getCheckoutData($id = null)
	{
		$tmp = e107::getSession('vstore')->get('checkout');

		if(!empty($id))
		{
			return $tmp[$id];
		}

		return $tmp;
	}

	/**
	 * Process a download request of a downloadable item
	 *
	 * @param int $item_id
	 * @return bool false on error
	 */
	private function downloadFile($item_id = null)
	{

		if($item_id == null || intval($item_id) <= 0)
		{
			e107::getMessage()->addDebug('Download id "' . intval($item_id) .
				'" to download missing or invalid!', 'vstore');

			return false;
		}

		if(USERID === 0)
		{
			return false;
		}

		if(!$this->hasItemPurchased($item_id))
		{
			return false;
		}

		$filepath = e107::getDb()->retrieve('vstore_items', 'item_download', 'item_id=' . intval($item_id));

		if(varset($filepath))
		{
			e107::getFile()->send($filepath);

			return true;
		}
		else
		{
			e107::getMessage()->addError(
				'Download id  "' . intval($item_id) . '" doesn\'t contain a file to download!',
				'vstore'
			);

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

		if($item_id == null || intval($item_id) <= 0)
		{
			e107::getMessage()->addDebug('Download id "' . intval($item_id) . '" missing or invalid!', 'vstore');

			return false;
		}

		if(USERID === 0)
		{
			e107::getMessage()->addError('You need to login to download the file!', 'vstore');

			return false;
		}
		$sql = e107::getDb();
		$orders = $sql->select(
			'vstore_orders',
			'*',
			'order_e107_user=' . USERID . ' AND order_items LIKE \'%"id": "' . intval($item_id) . '",%\'
            ORDER BY order_id DESC'
		);


		if(!$orders)
		{
			e107::getMessage()->addError(
				'We were unable to find your order and therefore the download has been denied!',
				'vstore'
			);

			return false;
		}

		$order_status = 'N';
		while($order = $sql->fetch())
		{
			$order_status = $order['order_status'];
			if($order['order_status'] == 'C')
			{
				// Status Completed = Payment OK, regardless of the orde_pay_status (e.g. in case of banktransfer)
				return true;
			}
			elseif($order['order_pay_status'] == 'complete' && $order['order_status'] == 'N')
			{
				// If order_status = New and pay_status = complete (e.g. in case of paypal payment)
				return true;
			}
		}
		// Order not completed or payment not complete + order_status = New
		e107::getMessage()->addError(
			'Your order is still in a state (' . vstore::getStatus($order_status) .
			') which doesn\'t allow to download the file!',
			'vstore'
		);

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

		if(intval($itemid) <= 0)
		{
			return false;
		}
		$sql = e107::getDb();

		if($sql->gen('SELECT item_id
        FROM `#vstore_items`
        LEFT JOIN `#vstore_cat` ON (item_cat = cat_id)
        WHERE item_active=1 AND cat_class IN (' . USERCLASS_LIST . ') AND item_id=' . intval($itemid)))
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

		if(empty($itemvars))
		{
			return false;
		}

		$baseprice = floatval($baseprice);

		if(is_string($itemvars))
		{
			$itemvars = self::item_vars_toArray($itemvars);
		}

		$result = array('price' => 0.0, 'variation' => array());

		$sql = e107::getDb();
		if($sql->select(
			'vstore_items_vars',
			'item_var_id, item_var_name, item_var_attributes',
			'FIND_IN_SET(item_var_id, "' . implode(',', array_keys($itemvars)) . '")'
		))
		{
			while($itemvar = $sql->fetch())
			{
				$attr = e107::unserialize($itemvar['item_var_attributes']);
				$text = $itemvar['item_var_name'];
				$value = $itemvars[$itemvar['item_var_id']];
				$operator = '';
				$op_val = 0.0;

				if(is_array($attr))
				{
					$frm = e107::getForm();
					foreach($attr as $row)
					{
						if($frm->name2id($row['name']) == $value)
						{
							$value = $row['name'];
							$operator = $row['operator'];
							$op_val = floatval($row['value']);
							break;
						}
					}
				}

				$result['variation'][] = "{$text}: {$value}";


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
	 * Returns the weight unit options or the label of the value entered.
	 * @param null $val
	 * @return array|string
	 */
	public static function weightUnits($val = null)
	{
		$opts = array(
			'g'     => 'Grams',
			'kg'    => 'Kilograms',
			'lb'    => 'Pounds',
			'oz'    => 'Ounces',
			'carat' => 'Carats'
		);

		return !empty($val) ? varset($opts[$val]) : $opts;

	}


	/**
	 * calculate the shipping cost depending on the current cart items
	 *
	 * @param array $items
	 * @return double
	 */
	public static function calcShippingCost($items, $pref = null)
	{
		if(empty($pref))
		{
			$pref = e107::pref('vstore');
		}

		// No shipping
		if(empty($pref['shipping']))
		{
			return 0.0;
		}

		$shipping = 0.0;
		$subtotal = 0.0;
		$weight = 0.0;

		foreach($items as $item)
		{
			if(varset($pref['shipping_method']) == 'sum_unique')
			{
				// sum_unique, sum_simple or tiered
				$shipping += (double) $item['item_shipping'];
			}
			else
			{
				$shipping += (double) ($item['item_shipping'] * $item['cart_qty']);
			}

			$subtotal += (double) ($item['item_price'] * $item['cart_qty']);
			$weight += (double) ($item['item_weight'] * $item['cart_qty']);
		}

		if(varset($pref['shipping_method']) === 'tiered'
			&& !empty($pref['shipping_limit'])
			&& !empty($pref['shipping_data'])
		)
		{
			$data = $pref['shipping_data'];
			unset($data['%ROW%']);
			$val = $subtotal;
			if(varset($pref['shipping_unit']) === 'weight')
			{
				// weight or subtotal
				$val = $weight;
			}

			$found = false;
			foreach($data as $v)
			{

				if($val <= floatval($v['unit']))
				{
					if($pref['shipping_limit'] === 'limit')
					{
						// limit or money
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
			if(!$found)
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
	 * @param array $coupon a row from the vstore_coupons table.
	 * @param array $item (should have the columns item_id, item_cat, item_price, cart_qty, item_name)
	 * @return double
	 */
	public static function calcCouponAmount($coupon, $item)
	{

		if(empty($coupon) || empty($item))
		{
			return 0.0;
		}

		// Coupon active?
		if(empty($coupon['coupon_active']))
		{
			e107::getMessage()->addError('Coupon is not available!', 'vstore');

			return 0.0;
		}

		// Coupon started
		if(!empty($coupon['coupon_start']) && time() < $coupon['coupon_start'])
		{
			e107::getMessage()->addError('Coupon is not yet available!', 'vstore');

			return 0.0;
		}

		// Coupon expired
		if(!empty($coupon['coupon_end']) && time() > $coupon['coupon_end'])
		{
			e107::getMessage()->addError('Coupon is no longer available!', 'vstore');

			return 0.0;
		}

		// Check limits
		$sql = e107::getDb();
		// Check how often this code was used so far
		if($coupon['coupon_limit_coupon'] > -1)
		{
			$usage = $sql->retrieve(
				'vstore_orders',
				'count(order_id) AS count_coupon',
				sprintf('order_pay_coupon_code="%s"', $coupon['coupon_code'])
			);
			if($usage >= $coupon['coupon_limit_coupon'])
			{
				e107::getMessage()->addError(
					'Coupon is no longer available!<br />It has exceeded it\'s allowed number of usage!',
					'vstore'
				);

				return 0.0;
			}
		}

		// Check how often the current user has used this code
		if($coupon['coupon_limit_user'] > -1)
		{
			$usage = $sql->retrieve(
				'vstore_orders',
				'count(order_id) AS count_coupon',
				sprintf('order_e107_user="%s" AND order_pay_coupon_code="%s"', USERID, $coupon['coupon_code'])
			);
			if($usage >= $coupon['coupon_limit_user'])
			{
				e107::getMessage()->addError(
					'Coupon is no longer available!<br />It has exceeded it\'s allowed number of usage!',
					'vstore'
				);

				return 0.0;
			}
		}

		$coupon['coupon_items'] = array_filter(explode(',', $coupon['coupon_items']));
		$coupon['coupon_items_ex'] = array_filter(explode(',', $coupon['coupon_items_ex']));
		$coupon['coupon_cats'] = array_filter(explode(',', $coupon['coupon_cats']));
		$coupon['coupon_cats_ex'] = array_filter(explode(',', $coupon['coupon_cats_ex']));

		$amount = 0.0;

		// Holds the usage data for the current items
		$usage = array();
		$itemID = $item['item_id'];

		// Check if items are defined
		if(count($coupon['coupon_items']) > 0)
		{
			if(!in_array($itemID, $coupon['coupon_items']))
			{
				// Item not included!
				return $amount;
			}
		}
		elseif(count($coupon['coupon_items_ex']) > 0 && in_array($itemID, $coupon['coupon_items_ex']))
		{
			// item excluded
			return $amount;
		}
		elseif(count($coupon['coupon_cats']) > 0)
		{
			// Check if categories are defined
			if(!in_array($item['item_cat'], $coupon['coupon_cats']))
			{
				// Category not included!
				return $amount;
			}
		}
		elseif(count($coupon['coupon_cats_ex']) > 0 && !in_array($item['item_cat'], $coupon['coupon_cats_ex']))
		{
			// Category excluded!
			return $amount;
		}

		$max_usage = 0;
		// Check how often this code has been used on this specific item
		if($coupon['coupon_limit_item'] > -1)
		{
			// Query database only the first time for this item (item_id can be duplicate due to item_variations)
			if(!isset($usage[$itemID]))
			{
				$data = $sql->retrieve(
					'vstore_orders',
					'order_items',
					sprintf(
						'order_items LIKE \'%%"id": "%d"%%\' AND order_pay_coupon_code="%s"',
						$itemID,
						$coupon['coupon_code']
					),
					true
				);
				if($data)
				{
					foreach($data as $row)
					{
						$item_info = e107::unserialize($row['order_items']);
						foreach($item_info as $info)
						{
							if($info['id'] == $itemID)
							{
								$usage[$itemID] += vartrue($info['quantity'], 0);
							}
						}
					}
				}
			}

			// Add items from this cart
			if(!isset($usage[$itemID]))
			{
				$usage[$itemID] = 0;
			}

			$usage[$itemID] += $item['cart_qty'];

			// Check if quantity exceeds limit
			if($usage[$itemID] > $coupon['coupon_limit_item'])
			{
				if(($usage[$itemID] - $item['cart_qty']) < $coupon['coupon_limit_item'])
				{
					$max_usage = $coupon['coupon_limit_item'] - ($usage[$itemID] - $item['cart_qty']);
					e107::getMessage()->addWarning(
						'Item quantity exceeds the allowed number of coupon code usage for this item "' .
						$item['item_name'] . '"!<br />The coupon will only used for remaining number of usages (' .
						$max_usage . 'x).',
						'vstore'
					);
				}
				else
				{
					e107::getMessage()->addError(
						'Coupon exceeds the allowed number of usage for this item "' . $item['item_name'] . '"!',
						'vstore'
					);

					return 0.0;
				}
			}
		}


		$qty = $item['cart_qty'];
		if($max_usage > 0)
		{
			// Apply code amount only to the remaining items
			$qty = $max_usage;
		}
		// Item included or not explicitly excluded = Apply coupon
		if($qty > 0)
		{
			if($coupon['coupon_type'] === '%')
			{
				$amount += (double) ($item['item_price'] * $qty) * $coupon['coupon_amount'] / 100;
			}
			elseif($coupon['coupon_type'] === 'F')
			{
				$amount += (double) ($qty * (float) $coupon['coupon_amount']);
			}
		}

		return ($amount * -1);
	}

	/**
	 * return the tax rate depending on the item's tax class and the customer country
	 *
	 * @param string $tax_class should be 'none', 'reduced', 'standard'
	 * @param string $customer_country should be the ISO 3166-1 alpha-2 country code of the customers (billing) country
	 * @return number
	 */
	public static function getTaxRate($tax_class, $customer_country = null, $pref = null)
	{

		if(empty($pref))
		{
			$pref = e107::pref('vstore');
		}

		$result = 0.0;

		if(empty($pref['tax_calculate']))
		{
			// Tax calculation is deactivated
			return $result;
		}

		if(varset($tax_class, 'standard') === 'none')
		{
			// Tax class is set to 'none' = no tax
			return $result;
		}
		$tax_class = strtolower($tax_class);

		static $customerCountry;

		$countries = new DvK\Vat\Countries();

		if(empty($customer_country) && empty($customerCountry))
		{
			$customer_ip = e107::getIPHandler()->getIP();
			$customerCountry = $countries->ip($customer_ip);
		}
		else
		{
			$customerCountry = $customer_country;
		}

		$businessCountry = $pref['tax_business_country'];


		if($customerCountry === $businessCountry) // customer is from the same country as the business
		{

			$tax_classes = e107::unserialize($pref['tax_classes']); // just a precaution - now an array.
			foreach($tax_classes as $tclass)
			{
				// lookup tax value
				if($tclass['name'] === $tax_class)
				{
					$result = floatval($tclass['value']);
					break;
				}
			}
		}
		elseif($countries->inEurope($businessCountry))
		{
			if(!$countries->inEurope($customerCountry))
			{
				// Customer is not in the EU
				// means no tax value
				return $result;
			}

			// Calc EU tax

			// get tax class by mapping
			$tax_class = self::getTaxClass($tax_class, $customerCountry);
			if(empty($tax_class))
			{
				return 0.0;
			}

			$rates = new DvK\Vat\Rates\Rates();
			try
			{
				// $result = $rates->country($customerCountry, $tax_class1);
				$result = $rates->country($customerCountry, $tax_class);
				// $check_rate = false;
			}
			catch(Exception $ex)
			{
				if($ex->getMessage() == 'Invalid rate.')
				{
					e107::getMessage()->addError('Invalid tax class! Please inform the shop administrator!', 'vstore');
					trigger_error('Invalid tax class!');
				}
			}

			if($result)
			{
				$result /= 100.0;
			}
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
			'reduced'       => array('reduced2', 'reduced1', 'super_reduced'),
			'reduced1'      => array('reduced', 'super_reduced', 'reduced2'),
			'reduced2'      => array('reduced', 'reduced1', 'super_reduced'),
			'super_reduced' => array('reduced', 'reduced1', 'reduced2'),
		);


		$rates = new DvK\Vat\Rates\Rates();
		$map = $rates->all();

		if(!array_key_exists($country, $map))
		{
			return '';
		}

		$periods = $map[$country]['periods'];
		if(empty($periods))
		{
			// Country not in table
			return '';
		}

		// Sort by date desc
		usort($periods, function ($period1, $period2)
		{

			return new \DateTime($period1['effective_from']) > new \DateTime($period2['effective_from']) ? -1 : 1;
		});

		$tax_classes = array_keys($periods[0]['rates']);

		if(!in_array($tax_class, $tax_classes))
		{
			// tax class not found...
			// try to map
			foreach($tax_classes as $tc)
			{
				foreach($map_classes[$tax_class] as $value)
				{
					if($tc == $value)
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
	public static function calcTaxAmount($netPrice, $tax_rate)
	{
		return round(($netPrice * $tax_rate) , 2);
	}

	/**
	 * Check if the given VAT ID exists in the EU and is in the correct format
	 *
	 * @param string $vat_id the VAT ID to check
	 * @return bool true if exists, $vat_id is empty or checking is disabled; false otherwise
	 */
	private function checkVAT_ID($vat_id, $country)
	{

		if(empty(trim($vat_id)))
		{
			// no VAT = VALID
			return true;
		}
		if(empty(trim($country)))
		{
			// Country missing = INVALID
			return false;
		}

		// Should the VAT ID be checked?
		if($this->pref['tax_check_vat'])
		{
			$country = trim(strtoupper($country));
			$vat_country = trim(strtoupper(substr($vat_id, 0, 2)));

			// Check if the countries match
			if($vat_country != $country)
			{
				// countrycode of the given vat_id
				// doesn't match the given country
				return false;
			}

			// Check if the VAT ID is from a EU country
			$countries = new DvK\Vat\Countries();
			if(!$countries->inEurope($vat_country))
			{
				// VAT ID is only used in the EU
				return true;
			}

			// Validate the given VAT ID
			$validator = new DvK\Vat\Validator();
			if(!$validator->validate($vat_id))
			{
				// Invalid VAT ID (checks format + existence)
				return false;
			}
		}

		// VAT ID ok, or not checked => return true
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
		if(empty($data) || !is_array($data))
		{
			$mes->addError('Customer data is missing or invalid!', 'vstore');

			return false;
		}
		if(empty($type) || !in_array($type, array('billing', 'shipping')))
		{
			$mes->addError('Invalid type!', 'vstore');

			return false;
		}

		$result = array();
		$fields = array();
		if($type == 'billing')
		{
			$fields = self::$customerFields;
		}
		elseif($type == 'shipping')
		{
			$fields = self::$shippingFields;
		}

		foreach($fields as $field)
		{
			if(substr($field, 0, 9) == 'add_field')
			{
				continue;
			}

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
					if(empty($result[$field]))
					{
						$mes->addError('The field ' . ucfirst($field) . ' is required!', 'vstore');

						return false;
					}
					if($field == 'email' && !filter_var($result[$field], FILTER_VALIDATE_EMAIL))
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
						if(!$this->checkVAT_ID($result[$field], $data['country']))
						{
							$mes->addError('The VAT-ID is invalid or doesn\'t match the selected country!', 'vstore');

							return false;
						}
					}
					break;

				// ADDITIONAL FIELDS
				case 'additional_fields':
					$addFields = $this->pref['additional_fields'];
					foreach($addFields as $i => $addField)
					{
						if($addField['active'])
						{
							$fieldName = 'add_field' . $i;
							if($addField['type'] == 'text')
							{
								$result[$fieldName] = trim(strip_tags($data[$fieldName]));
							}
							else
							{
								$result[$fieldName] = ($data[$fieldName] ? '1' : '');
							}
							if($addField['required'] && empty($result[$fieldName]))
							{
								$mes->addError('The field ' . $addField['caption'] . ' is required!', 'vstore');

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
		if(vartrue($pref) > (int) $last_nr['last'])
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
	public function getOrderIdFromInvoiceNr($invoice_nr)
	{

		return e107::getDb()->retrieve('vstore_orders', 'order_id', 'order_invoice_nr=' . intval($invoice_nr));
	}


	/**
	 * render the invoice by a given order_id
	 *
	 * @return boolean/array
	 */
	public function renderInvoice($forceUpdate = false)
	{

		if(!$this->order->isLoaded())
		{
			// Order ID missing or invalid
			e107::getMessage()->addDebug('No order loaded!', 'vstore');

			return false;
		}

		// check if the invoice belongs to the user (or is admin)
		if($this->order->order_e107_user != USERID)
		{
			// is user an admin
			if(!ADMIN)
			{
				e107::getMessage()->addError('Access denied!', 'vstore');

				return false;
			}
		}

		// check status of order: Invoice should be rendered only in status: N=New, C=Complete, P=Processing
		if(!self::validInvoiceOrderState($this->order->order_status))
		{
			e107::getMessage()->addError(
				e107::getParser()->lanVars(
					'Order in status "[x]". Invoice not available!',
					self::getStatus($this->order->order_status)
				),
				'vstore'
			);

			return false;
		}

		// Check if the invoice should be created as pdf
		$pdf_invoice = vartrue($this->pref['invoice_create_pdf'], 0);
		if($pdf_invoice)
		{
			// Check if invoice already exists
			$local_pdf = $this->pathToInvoicePdf($this->order->order_invoice_nr, $this->order->order_e107_user);
			if($local_pdf != '' && !$forceUpdate)
			{
				$this->downloadInvoicePdf($local_pdf);

				return;
			}
			if($local_pdf != '')
			{
				// Delete old pdf, to make sure it WILL get recreated!
				@unlink($local_pdf);
			}
		}

		$taxBusinessCountry = '';
		$billingCountry = '';

		if(isset($this->order->order_billing['country']))
		{
			$billingCountry = $this->order->order_billing['country'];
		}

		if(isset($this->pref['tax_business_country']))
		{
			$taxBusinessCountry = $this->pref['tax_business_country'];
		}

		$this->order->is_business = !empty($this->order->order_billing['vat_id']);
		$this->order->is_local = (vartrue($billingCountry, $taxBusinessCountry) === $taxBusinessCountry);

		return $this->renderInvoiceTemplate($pdf_invoice);

	}

	/**
	 * Check if pdf creation ie enabled and
	 * a pdf plugin is installed
	 *
	 * @param bool $checkPref (optional) true (default) checks first if the pdf creation is enabled
	 *
	 * @return bool true if the pdf plugin is installed,
	 *              false if pdf creation is deactivated or no pdf plugin is installed
	 */
	public static function checkPdfPlugin($checkPref = true)
	{

		// Check if pdf invoices should be created
		$create_pdf = e107::pref('vstore', 'invoice_create_pdf', '0');
		if($checkPref && !vartrue($create_pdf))
		{
			return false;
		}

		// Check if the pdf plugin is installed
		if(!e107::isInstalled('pdf'))
		{ // || !is_dir(e_PLUGIN . 'pdf/')) {
			//e107::getAdminLog()->addWarning(
			//    'PDF plugin not installed!<br/>This plugin is required by vstore to create invoice pdf\'s!',
			//    true,
			//    true
			//)->save('Vstore Pdf');
			e107::getMessage()->addWarning(
				e107::getParser()->lanVars(
					'PDF plugin not installed!\n' .
					'This plugin is required to create invoice pdf\'s!\n' .
					'You can download it from here: [x]',
					'<a href="https://github.com/e107inc/pdf">e107inc/pdf</a>'
				)
			);

			return false;
		}

		return true;
	}

	/**
	 * create a pdf invoice
	 *
	 * @param array $data
	 * @param boolean $saveToDisk
	 * @return void
	 */
	public function invoiceToPdf($data, $saveToDisk = true)
	{

		if(!self::checkPdfPlugin())
		{
			return;
		}

		require_once('inc/vstore_pdf.class.php'); //require the vstore_pdf class

		$pdf = new vstore_pdf();

		if($saveToDisk)
		{
			// Make sure the path is absolute
			$pdf->pdf_path = realpath(e107::getFile()->getUserDir($data['userid'], true));

			if($pdf->pdf_path == false || trim($pdf->pdf_path) == '')
			{
				e107::getLog()->add(
					'Vstore',
					'Unable to create invoice user folder: "' .
					e107::getFile()->getUserDir($data['userid']) . '"',
					E_LOG_WARNING
				);
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
	public function pathToInvoicePdf($invoice_nr, $e107_user_id = null)
	{

		if(is_null($e107_user_id))
		{
			$e107_user_id = USERID;
		}
		$title = varset($this->pref['invoice_title'][e_LANGUAGE], 'Invoice') . ' ' . self::formatInvoiceNr($invoice_nr);
		$file = e107::getFile()->getUserDir($e107_user_id) . e107::getForm()->name2id($title) . '.pdf';

		return (is_readable($file) ? $file : '');
	}


	/**
	 * Return the given pdf file as downloads
	 *
	 * @param string $local_pdf
	 * @return void
	 */
	public function downloadInvoicePdf($local_pdf)
	{

		if($local_pdf != '')
		{
			while(ob_end_clean())
			{
				;
			}
			header('Content-Description: File Transfer');
			if(headers_sent())
			{
			//	$this->Error('Some data has already been output to browser, can\'t send PDF file');
			}
			header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
			header('Pragma: public');
			header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			// force download dialog
			if(strpos(php_sapi_name(), 'cgi') === false)
			{
				header('Content-Type: application/force-download');
				header('Content-Type: application/octet-stream', false);
				header('Content-Type: application/download', false);
				header('Content-Type: application/pdf', false);
			}
			else
			{
				header('Content-Type: application/pdf');
			}
			// use the Content-Disposition header to supply a recommended filename
			header('Content-Disposition: attachment; filename="' . basename($local_pdf) . '"');
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

	/**
	 * Check if a given item_var_id is used for inventory tracking
	 *
	 * @param $varId item_var_id
	 * @return bool true if inventory must be tracked, false otherwise
	 */
	public static function isInventoryTrackingVar($varId)
	{

		// Init itemsVarsTypes array
		if(empty(self::$itemVarsTypes))
		{
			if($data = e107::getDb()->retrieve('vstore_items_vars', 'item_var_id, item_var_compulsory', null, true))
			{
				foreach($data as $row)
				{
					self::$itemVarsTypes[$row['item_var_compulsory']][] = $row['item_var_id'];
				}
			}
		}

		foreach(self::$itemVarsTypes as $type => $ids)
		{
			if(in_array($varId, $ids))
			{
				return ($type == 1);
			}
		}

		return false;
	}


	/**
	 * Filter the given string/array $curVal by $type (0 =non-tracking; 1=tracking)
	 *
	 * @param string/array $curVal  value to filter against §itemVarsType
	 * @param int $type (0 =non-tracking; 1=tracking)
	 * @param bool $asArray true return array, otherwise comma-separated string
	 * @return array|string
	 */
	public static function filterItemVarsByType($curVal, $type = 0, $asArray = false)
	{

		$curArr = array_filter(is_array($curVal) ? $curVal : explode(',', $curVal));
		if(count($curArr) == 0)
		{
			return $asArray ? array() : '';
		}

		// Init itemsVarsTypes array
		if(empty(self::$itemVarsTypes))
		{
			if($data = e107::getDb()->retrieve('vstore_items_vars', 'item_var_id, item_var_compulsory', null, true))
			{
				foreach($data as $row)
				{
					self::$itemVarsTypes[$row['item_var_compulsory']][] = $row['item_var_id'];
				}
			}
		}

		if(!isset(self::$itemVarsTypes[$type]))
		{
			return $asArray ? array() : '';
		}

		$result = array();
		foreach(self::$itemVarsTypes[$type] as $item)
		{
			if(in_array($item, $curArr))
			{
				$result[] = $item;
			}
		}

		return $asArray ? $result : implode(',', $result);
	}


	/**
	 * Add a order log entry to the log array
	 *
	 * @param array|string $log The log as string or array
	 * @param string $title The title to use in the log string
	 * @param variant $oldVal The old value
	 * @param variant $newVal The new value
	 * @param bool $asArray Return as array or string (default)
	 *
	 * @return array|string
	 */
	public static function addToOrderLog($log, $title, $oldVal, $newVal, $asArray = false)
	{

		if(is_string($log))
		{
			$log = e107::unserialize($log);
		}
		if(!is_array($log))
		{
			$log = array();
		}

		$log[] = array(
			'datestamp' => time(),
			'user_id'   => USERID,
			'user_name' => USERNAME,
			'text'      => e107::getParser()->lanVars('Changed [x] from [y] to [z].', array(
				'x' => $title,
				'y' => $oldVal,
				'z' => $newVal
			))
		);

		return $asArray ? $log : e107::serialize($log, 'json');
	}

	/**
	 * Mostly for unit testing.
	 * @param (array) $get
	 */
	function setGet($get)
	{
		$this->get = (array) $get;
	}

	/**
	 * Mostly for unit testing.
	 * @param (array) $post
	 */
	function setPost($post)
	{
		$this->post = (array) $post;
	}

	/**
	 * Set the prefs. - mostly used for unit testing.
	 * @param $pref
	 */
	public function setPrefs($pref)
	{
		$this->pref = (array) $pref;
	}

	public function getPrefs()
	{
		return $this->pref;
	}


	/**
	 * Compiles the prefs for usage within the class.
	 */
	public function initPrefs()
	{
		$active = array();


		// Load generic gateways into $gateways and set active icon status.
		if(!empty($this->pref['gateways']))
		{
			foreach($this->pref['gateways'] as $gate => $var)
			{
				self::$gateways[$gate] = $var;

				if(!empty($var['active']))
				{
					$active[$gate] = $this->getGatewayIcon($gate);
				}
			}
		}


		$this->order = e107::getSingleton('vstore_order', e_PLUGIN . 'vstore/inc/vstore_order.class.php');

		$this->currency = vartrue($this->pref['currency'], 'USD');

		if(!empty($this->pref['caption']) && !empty($this->pref['caption'][e_LANGUAGE]))
		{
			$this->captionBase = $this->pref['caption'][e_LANGUAGE];
		}

		if(!empty($this->pref['additional_fields']))
		{
			foreach($this->pref['additional_fields'] as $k => $v)
			{
				if(vartrue($v['active'], false))
				{
					static::$customerFields[] = 'add_field' . $k;
				}
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
		//	e107::getDebug()->log($this->pref);
		//	e107::getDebug()->log("CartID:" . $this->cartId);
		}
		// get all category data.
		$count = 0;
		$query = 'SELECT * FROM #vstore_cat WHERE cat_class IN (' . USERCLASS_LIST . ') ';
		if($data = e107::getDb()->retrieve($query, true))
		{
			foreach($data as $row)
			{
				$id = $row['cat_id'];
				$this->categories[$id] = $row;
				$sef = vartrue($row['cat_sef'], '--undefined--');
				$this->categorySEF[$sef] = $id;

				if(empty($row['cat_parent']))
				{
					$count++;
				}
			}
		}
		$this->categoriesTotal = $count;


		$tp = e107::getParser();
		foreach(self::$gateways as $k => $icon)
		{
			$key = $k . "_active";
			if(!empty($this->pref[$key]))
			{
				if(self::isMollie($k))
				{
					if(!empty($this->pref['mollie_payment_methods']))
					{
						$paymentMethods = array_keys($this->pref['mollie_payment_methods']);
						foreach($paymentMethods as $method)
						{
							$active[$method] = $this->getMolliePaymentMethodIcon($method);
						}
					}
				}
				else
				{
					$active[$k] = $this->getGatewayIcon($k);
				}

				// get gateway prefs. eg. paypal_password, paypal_active
				foreach($this->pref as $key => $v)
				{
					if(strpos($key, $k) === 0)
					{
						$newkey = substr($key, (strlen($k) + 1));
						$this->pref[$k][$newkey] = $v;
					}
				}
			}
		}


		if(deftrue('e_DEBUG_VSTORE') && getperms('0'))
		{
		//	e107::getDebug()->log($this->pref);
		}


		$this->active = $active;
	}

	/**
	 * Load the Gateway class and set the api keys etc.
	 * @param string $name paypal | mollie | etc.
	 * @return array
	 */
	public function loadGateway($name)
	{
		$mode = '';
		$message = '';
		$gateway = null;

		switch($name)
		{
			case "mollie":
				/** @var \Omnipay\Mollie\Gateway $gateway */
				$gateway = Omnipay::create('Mollie');

				if(!empty($this->pref['mollie']['testmode']))
				{
					$gateway->setApiKey($this->pref['mollie']['api_key_test']);
					$gateway->setTestMode(true);
				}
				else
				{
					$gateway->setApiKey($this->pref['mollie']['api_key_live']);
				}
				break;
/*
			case "paypal":

				$gateway = Omnipay::create('PayPal_Express'); // @var \Omnipay\PayPal\ExpressGateway $gateway

				if(!empty($this->pref['paypal']['testmode']))
				{
					$gateway->setTestMode(true);
				}

				$gateway->setUsername($this->pref['paypal']['username']);
				$gateway->setPassword($this->pref['paypal']['password']);
				$gateway->setSignature($this->pref['paypal']['signature']);
				break;

			case "paypal_rest":

				$gateway = Omnipay::create('PayPal_Rest'); // @var \Omnipay\PayPal\RestGateway $gateway

				if(!empty($this->pref['paypal_rest']['testmode']))
				{
					$gateway->setTestMode(true);
				}

				$gateway->setClientId($this->pref['paypal_rest']['clientId']);
				$gateway->setSecret($this->pref['paypal_rest']['secret']);
				break;*/

			case "bank_transfer":
				$this->setMode('return');

				if(!empty(self::$gateways['bank_transfer']['details']))
				{
					$message = '<br />Use the following bank account information for your payment:<br />';
					$message .= e107::getParser()->toHTML(self::$gateways['bank_transfer']['details'], true);
				}
				break;


			default:

				if(empty(self::$gateways[$name]['name']))
				{
					$message = "There was a configuration problem.";
					$gateway = null;
					trigger_error($message. ' Missing prefs for '.$name.".\n".print_r(self::$gateways,true));
				}
				else
				{
					$gatewayClass = self::$gateways[$name]['name'];

					try
					{
						$gateway = Omnipay::create($gatewayClass);
					}
					catch (Exception $e)
					{
					     $message = "Sorry, there is a problem loading the ".$name." payment option. Please notify the administrator.";
					     $message .= (ADMIN) ? $e->getMessage() : '';
					}

					if(is_object($gateway) && !empty(self::$gateways[$name]))
					{
						if(empty(self::$gateways[$name]['prefs']))
						{
							trigger_error('$gateways[$name][\'prefs\'] was empty.');
						}

						foreach(self::$gateways[$name]['prefs'] as $k=>$v)
						{
							$method = 'set'.ucfirst($k);
							try
							{
								$gateway->$method($v);
							}
							catch (Exception $e)
							{
								$message = "Sorry, there was a configuration issue. Please notify the administrator.";
							    $message .= (ADMIN) ? $e->getMessage() : '';
							    trigger_error($message, E_USER_WARNING);
							}


						}

					}
				}
				//return false;
		}

		return array($gateway, $message);
	}

	/**
	 * @param bool $pdf_invoice
	 * @return array|string
	 */
	public function renderInvoiceTemplate($pdf_invoice = false)
	{

		$template = e107::getTemplate('vstore', 'vstore_invoice');
		$invoice = varset($this->pref['invoice_template']);

		if(empty($invoice))
		{
			if(empty($template['default']))
			{
				// Template not found!
				e107::getMessage()->addDebug('Invoice template "default" not found!', 'vstore');
				trigger_error('Invoice template "default" not found!');
				return false;
			}

			$invoice = $template['default'];
		}


		$tp = e107::getParser();

		$this->sc->addVars($this->order->getData());
		$this->sc->wrapper('vstore_invoice/default');


		$text = $tp->parseTemplate($invoice, true, $this->sc);
		$footer = $tp->parseTemplate($template['footer'], true, $this->sc);

		$logo = $this->sc->sc_invoice_logo('path');

		if(!empty($logo))
		{
			$logo = ($pdf_invoice ? e_ROOT : e_HTTP) . $logo;
		}

		if($pdf_invoice)
		{
			$result = array(
				'userid'  => $this->order->order_e107_user,
				'subject' => varset($this->pref['invoice_title'][e_LANGUAGE], 'Invoice') . ' ' .
					self::formatInvoiceNr($this->order->order_invoice_nr),
				'text'    => $text,
				'footer'  => $footer,
				'logo'    => $logo,
				'url'     => e107::url(
					'vstore',
					'invoice',
					array('order_invoice_nr' => $this->order->order_invoice_nr),
					array('mode' => 'full')
				)
			);
		}
		else
		{
			$result = e107::getParser()->lanVars(
				$template['display'],
				array(
					'sitename' => SITENAME,
					'body'     => $text,
					'footer'   => $footer
				)
			);
			$result = e107::getParser()->parseTemplate($result);
		}

		return $result;
	}
}
