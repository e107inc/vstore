<?php

use Omnipay\Omnipay;

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

class vstore_order extends vstore
{
    /**
     * Order data array
     *
     * @var array
     */
    private $data = array();

    /**
     * Unchanged order data array
     *
     * @var array
     */
    private $old_data = array();

    /**
     * Contains the last error occured
     *
     * @var string
     */
    private $last_error = '';

    /**
     * defines if a order is loaded from db
     *
     * @var boolean
     */
    private $loaded = false;

    /**
     * The db object
     *
     * @var object
     */
    private $sql;

    /**
     * Constructor
     *
     * @param int $id (optional) order id of the order to load
     */
    public function __construct($id = null)
    {
        /** @var vstore_shortcodes sc */
        $this->sc = e107::getScParser()->getScObject('vstore_shortcodes', 'vstore', false);
        $this->sql = e107::getDb();
        $this->pref = e107::pref('vstore');

        if (!empty($id)) {
            $this->load($id);
        }
    }

    /**
     * Get a value from the order data array
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        return null;
    }

    /**
     * Set a value to the order data array
     *
     * @param string $name
     * @param mixed $value
     *
     * @return self
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
        return $this;
    }

    /**
     * Check if a key in the order data array is set
     *
     * @param sting $name
     *
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * Prevent cloning of the object
     *
     * @return void
     */
    public function __clone() { }

    /**
     * Replace the current order data array with the given array
     *
     * @param array $data
     *
     * @return self
     */
    public function setData($data)
    {
        if (is_array($data)) {
            $this->clear();
            $this->data = $data;
            $this->initData();
        }
        return $this;
    }

    /**
     * Get the order data array
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get the last error message saved
     *
     * @return string
     */
    public function getLastError()
    {
        return $this->last_error;
    }

    /**
     * Is an order loaded?
     *
     * @param int $id (optional) order_id to check if a specific order is loaded
     * 
     * @return boolean
     */
    public function isLoaded($id = null)
    {

	    if(!$this->loaded)
	    {
		    return false;
	    }

	    if(!empty($id) && $this->order_id != $id)
	    {
		    return false;
	    }

	    return true;
    }

	/**
	 * @param array $data
	 * @return bool
	 */
	private function hasData($data)
	{
		if(empty($data))
	    {
		    if($this->sql->getLastErrorNumber() > 0)
		    {
			    $this->last_error = 'Unable to load order! ' . $this->sql->getLastErrorText();
		    }
		    else
		    {
			    $this->last_error = 'Order not found!';
		    }

		    return false;
	    }

	    return true;

	}

    /**
     * Load an order from the database
     *
     * @param int $id id of the order to load
     *
     * @return bool
     */
    public function load($id)
    {
        $this->clear();
        $id = (int) $id;
        $this->data = $this->sql->retrieve('vstore_orders', '*', 'order_id=' . $id);

		if($this->hasData($this->data) === false)
		{
			return false;
		}


        $this->old_data = $this->data;
        $this->initData();
        $this->loaded = true;
        return true;
    }

    /**
     * Load an order from the database using the invoice nr.
     *
     * @param int $id invoice nr of the order to load
     *
     * @return bool
     */
    public function loadByInvoiceNr($id)
    {
        $this->clear();
        $id = intval($id);
        $this->data = $this->sql->retrieve('vstore_orders', '*', 'order_invoice_nr=' . $id);

		if($this->hasData($this->data) === false)
		{
			return false;
		}

        $this->old_data = $this->data;
        $this->initData();
        $this->loaded = true;
        return true;
    }

    /**
     * Clear object and reset internal data
     *
     * @return void
     */
    public function clear()
    {
        $this->data = array();
        $this->old_data = array();
        $this->loaded = false;
        $this->last_error = '';
    }

    /**
     * Save the current order
     *
     * @return bool
     */
    public function save()
    {
        $this->last_error = '';
        $id = vartrue($this->data['order_id'], 0);
        unset($this->data['order_id']);

        $this->serialize('order_items');
        $this->serialize('order_log');
        $this->serialize('order_pay_rawdata');
        $this->serialize('order_billing');
        $this->serialize('order_shipping');
        $this->serialize('order_pay_tax');

        // Strip out any fields that have not changed
        $this->data = array_diff_assoc($this->data, $this->old_data);

	    if(empty($this->data))
	    {
		    // reset data
		    $this->loaded = false;
		    if(!empty($this->old_data))
		    {
			    $this->data = $this->old_data;
			    $this->loaded = true;
		    }

		    // nothing changed: Save not required
		    return true;
	    }


		$data = $this->data;

		if(isset($data['is_business']))
		{
			unset($data['is_business']);
		}
		if(isset($data['is_local']))
		{
			unset($data['is_local']);
		}

	    $insert = array(
		    'data' => $data
	    );


	    if(empty($id))
	    {
		    // New order
		    $result = $id = $this->sql->insert('vstore_orders', $insert, false, 'debug', 'vstore/order/insert');
	    }
	    else
	    {
		    $insert['WHERE'] = 'order_id = ' . $id;
		    $result = $this->sql->update('vstore_orders', $insert, false, 'debug', 'vstore/order/update');
	    }

	    if($result === false)
	    {
		    $this->last_error = 'Unable to inset/update the order! ' . $this->sql->getLastErrorText();

		    return false;
	    }
        // Clean load of the previously saved order
        return $this->load($id);
    }

    /**
     * Init the loaded order data by unserializing some fields
     *
     * @return void
     */
    private function initData()
    {
        $this->unserialize('order_items');
        $this->unserialize('order_log');
        $this->unserialize('order_pay_rawdata');
        $this->unserialize('order_billing');
        $this->unserialize('order_shipping');
        $this->unserialize('order_pay_tax');
    }

    /**
     * Internal serialize wrapper to serialize
     * the value of the given fieldname
     *
     * @param string $name name of the field to serialize
     *
     * @return string
     */
    private function serialize($name)
    {
        if (!isset($this->data[$name])) {
            $this->data[$name] = null;
        }
        if (is_array($this->data[$name])) {
            $this->data[$name] = e107::serialize($this->data[$name], 'json');
        } elseif (empty($this->data[$name])) {
            $this->data[$name] = '';
        }
        return $this->data[$name];
    }

    /**
     * Internal unserialize wrapper to unserialize
     * value of the given fieldname
     *
     * @param string $name name of the field to unserialize
     *
     * @return mixed
     */
    private function unserialize($name)
    {
        if (!isset($this->data[$name])) {
            $this->data[$name] = null;
        }
        if (!empty($this->data[$name]) && !is_array($this->data[$name])) {
            $this->data[$name] = e107::unserialize($this->data[$name]);
        } elseif (empty($this->data[$name])) {
            $this->data[$name] = array();
        }
        return $this->data[$name];
    }

    /**
     * Set the new order status
     *
     * @param string $new_status The new order status code
     * @param array  $raw_data   (optional) new rawdata data
     *
     * @return bool|string true on success, string otherwise
     */
	public function setOrderStatus($new_status, $raw_data = null)
	{

		$errorPrefix = EMESSLAN_TITLE_ERROR . "\n";

		if(!$this->loaded)
		{
			$this->last_error = $errorPrefix . 'Unable to change order status. No Order loaded!';
			trigger_error($this->last_error);
			return false;
		}
		// record found and new status is different to new one
		if($this->data['order_status'] !== $new_status)
		{
			if($new_status === 'C') // if new status is complete, assume the payment also to be complete
			{
				$this->data['order_pay_status'] = 'complete';
			}
			elseif($new_status === 'R') // if new status is refunded, set payment to refunded
			{
				$this->data['order_pay_status'] = 'refunded';
			}

			// Prepare rawdata array
			$rawdata = array();
			if(!empty($raw_data))
			{
				if(!empty($this->data['order_pay_rawdata']))
				{
					$rawdata = $this->data['order_pay_rawdata'];
					if(!isset($rawdata['purchase']) && !isset($rawdata['refund']))
					{
						// just in case order_pay_rawdata has the wrong structure
						$tmp = $rawdata;
						$rawdata = array('purchase' => $tmp);
						unset($tmp);
					}
				}
				$rawdata = array_merge($rawdata, $raw_data);
				$this->data['order_pay_rawdata'] = $rawdata;
				unset($rawdata, $raw_data);
			}

			$this->setOrderLog('Status', $this->data['order_status'], $new_status);
			$this->data['order_status'] = $new_status;

			$result = $this->save();

			if($result && $new_status === 'C')
			{
				// In case of a positive update and the order has been set to 'C' (complete)
				// set the customer userclass
				$items = $this->unserialize('order_items');
				self::setCustomerUserclass($this->data['order_e107_user'], $items);
			}
			elseif($result == false && intval(e107::getDb()->getLastErrorNumber()) != 0)
			{
				// There was an error, return the last database error
				$this->last_error = $errorPrefix . 'Unable to update order status. ' . e107::getDb()->getLastErrorText();
				trigger_error($this->last_error);
				return false;
			}

			// send out the "OnChange" emails
			$this->emailCustomerOnStatusChange();
		}

		// nothing to change (old status == new status)
		return true;
	}

    /**
     * Add a order log entry to the log array
     *
     * @param string $title   The title to use in the log string
     * @param mixed  $oldVal  The old value
     * @param mixed  $newVal  The new value
     *
     * @return self
     */
    public function setOrderLog($title, $oldVal = null, $newVal = null)
    {
        $log = $this->unserialize('order_log');
        if (is_string($log)) {
            $log = e107::unserialize($log);
        }
        if (!is_array($log)) {
            $log = array();
        }

        $item = array(
            'datestamp' => time(),
            'user_id' => varset('USERID',0),
            'user_name' => varset('USERNAME', '_guest_')
        );

        if (!empty($oldVal) || !empty($newVal)) {
            // Value changed message
            $item['text'] = e107::getParser()->lanVars('Changed [x] from [y] to [z].', array(
                'x' => $title,
                'y' => varset($oldVal, '--'),
                'z' => varset($newVal, '--')
            ));
        } else {
            // custom message
            $item['text'] = $title;
        }

        $log[] = $item;

        $this->data['order_log'] = $log;

        return $this;
    }

    /**
     * Build a order reference number out of the order_id, first- & lastname
     *
     * @return bool true on success, false on error
     */
    public function setOrderRef()
    {
        if (!$this->isLoaded()) {
            $this->last_error = 'No order loaded. Unable to update order ref!';
            return false;
        }

        $this->unserialize('order_billing');
        $firstname = vartrue($this->data['order_billing']['firstname']);
        $lastname = vartrue($this->data['order_billing']['lastname']);

        // if ($firstname == '' && $lastname == '') {
        //     $this->last_error = 'No billing firstname and lastname entered!';
        //     return false;
        // }

        $text = '';
        // Just in case Firstname and/or Lastname is misssing,
        // use the Day and/or Monthname for the prefix...
        // If first-/lastname is to short, fill with dash (-)
        $text .= substr(($firstname ? $firstname : date('D')).'--', 0, 2);
        $text .= substr(($lastname ? $lastname : date('M')).'--', 0, 2);

        // $text = substr($firstname, 0, 2);
        // $text .= substr($lastname, 0, 2);
        $text .= e107::getParser()->leadingZeros($this->data['order_id'], 6);

        $this->data['order_refcode'] = strtoupper($text);

        $this->setOrderLog('Order Ref-Nr. assigned: ' . $this->data['order_refcode']);

        return true;
    }


    /**
     * Assign the next invoice nr to use
     *
     * @return int
     */
    public function setInvoiceNr()
    {
        // Get last used invoice nr.
        $last_nr = e107::getDB()->retrieve('vstore_orders', 'MAX(order_invoice_nr) AS last');
        // Get next nr. from prefs
        $pref = (int)e107::pref('vstore', 'invoice_next_nr');
        // if the pref nr is higher ...
        if (vartrue($pref) > (int)$last_nr['last']) {
            // ... use pref
            $this->data['order_invoice_nr'] = $pref;
        } else {
            // ... otherwise return next higher
            $this->data['order_invoice_nr'] = ($last_nr['last'] + 1);
        }

        $this->setOrderLog('Invoice-Nr. assigned: ' . $this->data['order_invoice_nr']);

    }

    /**
     * Refund an order
     *
     * @param bool $do_log   Update order log
     *
     * @return bool Returns true on success, false otherwise
     */
	public function refundOrder($do_log = true)
	{

		// $successPrefix = EMESSLAN_TITLE_SUCCESS . "\n";
		$warnPrefix = EMESSLAN_TITLE_WARNING . "\n";
		$errorPrefix = EMESSLAN_TITLE_ERROR . "\n";

		// By default, all gateways support automatic refunding
		$supportsRefund = true;

		// Check inputs
		if(!$this->loaded)
		{
			$this->last_error = $errorPrefix . "Order not loaded!";
			trigger_error('Order not loaded!');

			return false;
		}

		if($this->order_status == 'R')
		{
			$this->last_error = $errorPrefix . 'Order is already refunded!';
			trigger_error('Order is already refunded!');

			return false;
		}
		elseif(!in_array($this->order_status, array('P', 'H', 'C')))
		{
			$message = 'Only orders with status "Processing", "On Hold" and "Complete" can be refunded!';
			$this->last_error = $errorPrefix . $message;
			trigger_error($message. print_r($this,true));

			return false;
		}

		$transactionId = $this->order_pay_transid;
		$amount = $this->order_pay_amount;
		$currency = $this->order_pay_currency;
		$type = $this->order_pay_gateway;

		e107::getDebug()->log("Processing Gateway: " . $type);

		// Fix $type in case of Mollie Gateway
		if(self::isMollie($type))
		{
			$type = substr($type, 0, 6);
		}

		// array keeping the data required for refunding
		// usually the transactionid
		$refundDetails = array();
		$gateway = null;

		// Init payment gateway
		switch($type)
		{
			case "bank_transfer":
				// Normal bank transfer doesn't support automatic refunding
				$supportsRefund = false;
				break;

			case "paypal_rest":
			case "paypal":
			case "mollie":
			default:
				list($gateway, $message) = $this->loadGateway($type);

				$refundDetails = array(
					'transactionReference' => $transactionId,
					'amount'               => $amount,
					'currency'             => $currency
				);

				if(empty($gateway))
				{
					$this->last_error = $errorPrefix . "Missing pament gateway!";
					trigger_error($this->last_error);
					return false;
				}

		}

		// Check if selected gateway supports refunding
		if($supportsRefund && !$gateway->supportsRefund())
		{
			// gateway doesn't support refunding;
			$supportsRefund = false;
		}

		try
		{
			$data = array();
			if($supportsRefund)
			{
				// Check if selected gateway has it's refunding details set
				if(empty($refundDetails))
				{
					$this->last_error = $errorPrefix . "Refunding details not set!";
					trigger_error($this->last_error);
					return false;
				}

				// try to refund the money
				$request = $gateway->refund($refundDetails);
				$response = $request->send();
				if($response->isSuccessful())
				{
					$data = $response->getData();
				}
				else
				{
					// Refunding failed
					$this->last_error = $errorPrefix . $response->getMessage();
					trigger_error($this->last_error);
					return false;
				}
			}
			else
			{
				// Fill the rawdata with a meaningfull message to be added to rawdata array,
				// refunding can't be done automatically
				$data = array(
					'Refunded' => e107::getParser()->lanVars(
						'Order refunded on [x] by [y] ([z])',
						array(
							gmdate('Y-m-d H:i:s'),
							varset('USERNAME', '_guest_'),
							varset('USERID', 0)
						)
					)
				);
			}

			// Update order status (incl.sending out the email to the customer (if nescessary))
			$result = $this->setOrderStatus('R', array('refund' => $data));
			if($result !== true)
			{
				$this->last_error = $errorPrefix . $result;
				return false;
			}
			elseif(!$supportsRefund)
			{
				// In case of bank_transfers or other payment methods that do not support refunding,
				// return a warning, that the refunding of the money has to be done manually!
				$this->last_error = $warnPrefix . "The order has been marked as refunded, but the payment method '" .
					self::getGatewayTitle($type) .
					"' doesn't support automatic refunding!\nYou have to do it manually!";
				return false;
			}

		}
		catch(Exception $ex)
		{
			$this->last_error = $errorPrefix . "Refunding failed! " . $ex->getMessage();
			return false;
		}

		return true;
	}

    /**
     * Send an email to the customer with a template depending on the order_status
     * This is used on the sales admin pages, when changing the order_status
     *
     * @return void
     */
    public function emailCustomerOnStatusChange()
    {
        if (!$this->loaded) {
            e107::getMessage()->addDebug(
                'No order loaded!',
                'vstore'
            );
            trigger_error('No Order loaded!');
            return;
        }

        $refId = $this->data['order_refcode'];

        // Attach the invoice in case the order status is New, Complete or Processing
        $pdf_file = '';

        $createPDF = e107::pref('vstore', 'invoice_create_pdf', false);

        if ($createPDF && self::validInvoiceOrderState($this->data['order_status']))
        {
            $pdf_file = $this->pathToInvoicePdf($this->data['order_invoice_nr'], $this->data['order_e107_user']);
        }

        $this->emailCustomer(
            strtolower($this->getStatus($this->data['order_status'])),
            $pdf_file
        );
    }


    /**
     * Send an email to the customer
     *
     * @param string $templateKey the email type
     * @param string $pdf_file the path to the pdf invoice file (or empty)
     *
     * @return boolean
     */
    public function emailCustomer($templateKey = 'default', $pdf_file = '')
    {
        if (!$this->loaded)   // No order loaded... Load order first...
        {
            e107::getMessage()->addDebug('No order loaded!', 'vstore');
            trigger_error('No order loaded!');
            return false;
        }

		if(!$tmp = $this->compileEmail($templateKey, $pdf_file))
		{
			trigger_error('compileEmail() returned nothing with key: '.$templateKey);
			return false;
		}

	    list($email, $name, $eml) = $tmp;
	    // die(e107::getEmail()->preview($eml));

        $result = e107::getEmail()->sendEmail($email, $name, $eml);

		if($result !== true)
		{
            $this->last_error = $result;
            return false;
		}

		return true;
    }



    /**
     * Get the current email template
     * If it isn't defined in the admin area, load the template from the template folder
     *
     * @todo add a pref (multilan) containing the entire template which can be edited from within the admin area.
     * @param string $type email type
     * @return string the template
     */
    public function getEmailTemplate($type = 'default')
    {

	    if(empty($type))
	    {
		    $type = 'default';
	    }

	    $template = e107::pref('vstore','email_templates', array());

	    if(isset($template[$type]['active']) && ($template[$type]['active'] ? false : true))
	    {
		    return null;
	    }

	    if(empty($template[$type]['template']))
	    {
		    $template = e107::getTemplate('vstore', 'vstore_email', $type);
		    if(empty($template))
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
	 * @param string $templateKey
	 * @param string $pdf_file
	 * @return array
	 */
	public function compileEmail($templateKey, $pdf_file=null)
	{

		$tp = e107::getParser();
		$pref = e107::pref('vstore');
		$template = $this->getEmailTemplate($templateKey);

		if(empty($template))
		{
			// No template available... No mail to send ...
			e107::getMessage()->addDebug('No template found or template is empty!', 'vstore');
			return false;
		}

		$sender_name = varset($pref['sender_name']);
		$sender_email = varset($pref['sender_email']);

		if(empty($sender_email))
		{
			e107::getMessage()->addDebug('No explicit shop email defined!<br/>Will use siteadmin email!', 'vstore');
			$sender_email = e107::pref('core', 'siteadminemail');
		}

		if(empty($sender_name))
		{
			e107::getMessage()->addDebug('No explicit shop email name defined!<br/>Will use siteadmin name!', 'vstore');
			$sender_name = e107::pref('core', 'siteadmin');
		}

		if(empty($sender_email))
		{
			trigger_error("sender_email was empty");
			return false;
		}


		$templates = varset($pref['email_templates'], array());

		if(empty($templates))
		{
			trigger_error('Email templates pref was empty');
		}

		$cc = '';

		if(!empty($templates[$templateKey]['cc']))
		{
			$cc = $sender_email;
		}

		$receiver = (array) $this->unserialize('order_billing');

		$vars = $this->data;

		$vars['is_business'] = !empty($receiver['vat_id']);
		$vars['is_local'] = (varset($receiver['country'], varset($pref['tax_business_country'])) === varset( $pref['tax_business_country']));

		$this->sc->setVars($vars);

		//todo add to template
		$subject = "Your Order #[x] at " . SITENAME;

		$email = varset($receiver['email']);
		$name = varset($receiver['firstname']) . " " . varset($receiver['lastname']);

		$eml = array(
			'subject'      => $tp->lanVars($subject, varset($this->data['order_refcode'])),
			'sender_email' => $sender_email,
			'sender_name'  => $sender_name,
			'html'         => true,
			'template'     => 'default',
			'body'         => $tp->parseTemplate($template, true, $this->sc)
		);

		if(!empty($cc))
		{
			$eml['cc'] = $cc;
		}

		if(!empty($pdf_file))
		{
			$eml['attach'] = $pdf_file;
		}

		return array($email, $name, $eml);
	}
}
