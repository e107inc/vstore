<?php
if (!defined('e107_INIT')) { exit; }


class vstore_dashboard extends vstore
{

    protected $dashboards;
    protected $actions;

    protected $area;
    protected $action;
    protected $id;
    protected $limit_from;
    protected $tp;
    protected $frm;
    protected $sql;
    protected $template;


    public function __construct()
    {
		Parent::__construct();

		$this->dashboards = array(
			'dashboard' => ''.LAN_VSTORE_CART_018.'' , 
			'orders' => ''.LAN_VSTORE_CART_019.'', 
			'files' => ''.LAN_VSTORE_CART_020.'', 
			'addresses' => ''.LAN_VSTORE_CART_021.'', 
			'account' => ''.LAN_VSTORE_CART_022.''
        );
        
        $this->actions = array(
            'orders' => array(
                'view' => ''.LAN_VSTORE_CART_023.'',
                'cancel' => ''.LAN_VSTORE_CART_024.''
            ),
            'addresses' => array(
                'edit' => ''.LAN_VSTORE_CART_025.''
            )
        );

        $this->area = strtolower(trim(vartrue($this->get['area'], 'dashboard')));
        $this->action = strtolower(trim(vartrue($this->get['action'])));
        $this->id = intval(vartrue($this->get['id'], 0));

		$this->tp = e107::getParser();
        $this->sql = e107::getDb();
        $this->frm = e107::getForm();

        $this->template = e107::getTemplate('vstore', 'vstore_dashboard');
		$this->from = vartrue($this->get['page'],1);

		$this->from = vartrue($this->get['page'],1);
		$this->limit_from = ($this->from - 1) * $this->perPage;        
    }

    /**
     * Return title for current area
     *
     * @return string
     */
    public function getArea()
    {
        return $this->dashboards[$this->area];
    }

    /**
     * Return title for current action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->actions[$this->area][$this->action];
    }

    /**
     * return action id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

	/**
	 * Render the customers shop dashboard
	 *
	 * @return string
	 */
	public function render()
	{
		// Access only for logged in users
		if (!USER) {
			e107::getMessage()->addError(' '.LAN_VSTORE_023.' <a href="'.SITEURL.'login.php">'.LAN_LOGIN.'</a> '.LAN_VSTORE_024.'', 'vstore');
			return '';
		}

		$this->sc->setVars(array('nav' => $this->dashboards, 'area' => $this->area));

		$text = $this->tp->parseTemplate($this->template['header'], true, $this->sc);

        $method = $this->area . '_' . $this->action;

        if (method_exists($this, $method) && $this->id)
        {
            $text .= $this->$method();
        }
        elseif (method_exists($this, $this->area))
        {
            $method = $this->area;
            $text .= $this->$method();
        }
        else
        {
            e107::getMessage()->addError(' '.LAN_VSTORE_ADMIN_014.' "'.$this->dashboards[$this->area].'" '.LAN_VSTORE_ADMIN_015.' ', 'vstore');
        }

		$text .= $this->tp->parseTemplate($this->template['footer'], true, $this->sc);
		return $text;

	}


	/**
	 * Dashboard landing page
	 *
	 * @return string
	 */
    private function dashboard()
    {
        return $this->tp->parseTemplate($this->template['dashboard'], true, $this->sc);
    }


	/**
	 * Render list of orders
	 *
	 * @return string
	 */
    private function orders()
    {
        $text = '';
        // List the orders
        if ($this->sql->gen('SELECT SQL_CALC_FOUND_ROWS *, o.* FROM `#vstore_orders` o WHERE order_e107_user = '.USERID.' ORDER BY order_id DESC LIMIT '.$this->limit_from.','.$this->perPage))
        {
            $count = e107::getDb()->foundRows();

            $text .= $this->tp->parseTemplate($this->template['order']['list']['header'], true, $this->sc);
            while($row = $this->sql->fetch())
            {
                $this->sc->setVars($row);
                $text .= $this->tp->parseTemplate($this->template['order']['list']['item'], true, $this->sc);
            }
            unset($row);

            $text .= $this->tp->parseTemplate($this->template['order']['list']['footer'], true, $this->sc);

            if ($count > intval($this->perPage))
            {
                $nextprev = array(
                        'tmpl'			=>'bootstrap',
                        'total'			=> ceil($count / intval($this->perPage)),
                        'amount'		=> intval($this->perPage),
                        'current'		=> $this->from,
                        'type'			=> 'page',
                        'url'			=> e107::url('vstore','dashboard', array('dash' => 'orders')).'?page=[FROM]'
                );
        
                global $nextprev_parms;
            
                $nextprev_parms  = http_build_query($nextprev,false);
        
                $text .= $this->tp->parseTemplate("{NEXTPREV: ".$nextprev_parms."}");
            }
        }
        else
        {
            $text .= '<p>'.LAN_VSTORE_CART_034.'</p>';
        }
        return $text;
    }


	/**
	 * Render order details
	 *
	 * @return string
	 */
    private function orders_view()
    {
        $text = '';
        // Order actions (view, cancel)
        $data = $this->sql->retrieve('vstore_orders', '*', 'order_invoice_nr='.$this->id.' AND order_e107_user='.USERID);
        if (!$data)
        {
            // Order not found or not assigned to user
            $text .= '<p>'.LAN_VSTORE_SALES_028.'</p>';
        }
        else
        {
            // render view order details
            $this->sc->setVars($data);
            $text .= $this->tp->parseTemplate($this->template['order']['detail'], true, $this->sc);
        }
        return $text;      
    }


	/**
	 * Render cancel order confirmations
	 *
	 * @return string
	 */
    private function orders_cancel()
    {
        $text = '';
        // Order actions (view, cancel)
        $data = $this->sql->retrieve('vstore_orders', '*', 'order_invoice_nr='.$this->id.' AND order_e107_user='.USERID);
        if (!$data)
        {
            // Order not found or not assigned to user
            $text .= '<p>'.LAN_VSTORE_SALES_028.'</p>';
        }
        else
        {
            // render cancel order confirmation
            $text .= $this->frm->open('confirm-cancel', 'post', null, array('class'=>'form'));
            $text .= '
                <div class="alert alert-warning" role="alert">'.LAN_VSTORE_005.' '.$data['order_refcode'].'?</div>
                <a href="'.e107::url('vstore', 'dashboard', array('dash' => 'orders')).'" class="btn btn-primary" name="cancel_cancel" id="cancel_cancel">'.LAN_VSTORE_006.'</a>
                <button type="submit" class="btn btn-warning" name="cancel_order" id="cancel_order" value="'.$data['order_id'].'">'.LAN_VSTORE_007.'</button>
            ';
            $text .= $this->frm->close();

        }
        return $text;
    }


	/**
	 * Render paid downloads
	 *
	 * @return string
	 */
    private function files()
    {
        $text = '';
        // List the downloads
        if ($this->sql->gen('SELECT SQL_CALC_FOUND_ROWS *, o.* FROM `#vstore_orders` o WHERE order_e107_user = '.USERID.' AND order_items REGEXP \'"file": "[[:alnum:][:digit:][:punct:][:blank:]]+",\' AND FIND_IN_SET(order_status, "N,C,P") ORDER BY order_id DESC LIMIT '.$this->limit_from.','.$this->perPage))
        {
            $count = e107::getDb()->foundRows();

            $text .= $this->tp->parseTemplate($this->template['download']['list']['header'], true, $this->sc);
            while($row = $this->sql->fetch())
            {
                $this->sc->setVars($row);
                $text .= $this->tp->parseTemplate($this->template['download']['list']['item'], true, $this->sc);
            }
            unset($row);

            $text .= $this->tp->parseTemplate($this->template['download']['list']['footer'], true, $this->sc);

            if ($count > intval($this->perPage))
            {
                $nextprev = array(
                        'tmpl'			=>'bootstrap',
                        'total'			=> ceil($count / intval($this->perPage)),
                        'amount'		=> intval($this->perPage),
                        'current'		=> $this->from,
                        'type'			=> 'page',
                        'url'			=> e107::url('vstore','dashboard', array('dash' => 'downloads')).'?page=[FROM]'
                );
        
                global $nextprev_parms;
            
                $nextprev_parms  = http_build_query($nextprev,false,'&');
        
                $text .= $this->tp->parseTemplate("{NEXTPREV: ".$nextprev_parms."}",true);
            }
        }
        else
        {
            $text .= '<p>'.LAN_VSTORE_CART_035.'</p>';
        }        

        return $text;
    }


	/**
	 * Render saved addresses
	 *
	 * @return string
	 */
    private function addresses()
    {
        $text = '';
        // Read data
        $data = $this->sql->retrieve('vstore_customer', '*', 'cust_e107_user = '.USERID);
        if ($data)
        {
            $this->sc->setVars($data);
            $text .= $this->tp->parseTemplate($this->template['address']['view'], true, $this->sc);
        }
        else
        {
            $text .= '<p>'.LAN_VSTORE_CART_036.'</p>';
        }
        unset($data);        
        return $text;
	}
	

	/**
	 * Render edit address form
	 *
	 * @return string
	 */
    private function addresses_edit()
    {
        $text = '';
        // Read data
        $data = $this->sql->retrieve('vstore_customer', '*', 'cust_e107_user = '.USERID);
        if ($data)
        {
			$text .= $this->frm->open('address_edit', 'post', null, array('class'=>'form'));

			if ($this->id === 1) // Billing address
			{

				foreach ($data as $key => $value) {
					$key2 = substr($key, 5);
					$data['cust'][$key2] = $value;
					unset($data[$key]);
				}
				if (!empty($data['cust']['additional_fields']))
				{
					$add = e107::unserialize($data['cust']['additional_fields']);
					foreach ($add as $key => $value) {
						$data['cust'][$key] = $value;
					}
				}
				//unset($data['cust']['additional_fields']);


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
							if (isset($data['cust'][$fieldid]))
							{
								$fieldvalue = $data['cust'][$fieldid]['value'];
							}
							else
							{
								$fieldvalue = $data['cust']['additional_fields']['value'][$fieldid];
							}
							if ($v['type'] == 'text')
							{
								// Textboxes
							$field = $this->frm->text($fieldname, $fieldvalue, 100, array('placeholder' =>varset($v['placeholder'][e_LANGUAGE]), 'required' =>($v['required'] ? 1 : 0)));
							}
							elseif ($v['type'] == 'checkbox')
							{
								// Checkboxes
								$field = '<div class="form-control">'.$this->frm->checkbox($fieldname, 1, 0, array('required'=>($v['required'] ? 1 : 0)));
								if (vartrue($v['placeholder']))
								{
									$field .= ' <label for="'.$this->name2id($fieldname).'-1" class="text-muted">&nbsp;'.$this->tp->toHTML($v['placeholder'][e_LANGUAGE]).'</label>';
								}
								$field .= '</div>';
							}

							$this->sc->addVars(array(
								'fieldname' => $fieldname,
								'fieldcaption' => $this->tp->toHTML(varset($v['caption'][e_LANGUAGE], 'Additional field '.$k)),
								'field' => $field,
								'fieldcount' => $addFieldActive,
								'fieldrequired' => $v['required']
							));

							$data['cust']['add'][$fieldid] = $this->tp->parseTemplate($this->template['address']['edit']['billing']['additional']['item'], true, $this->sc);

						}
					}

				}


				$this->sc->setVars($data);
				$text .= $this->tp->parseTemplate($this->template['address']['edit']['billing']['body'], true, $this->sc);
			}
			elseif ($this->id === 2) // Shipping address
			{
				$data['ship'] = e107::unserialize($data['cust_shipping']);
				$this->sc->setVars($data);
				$text .= $this->tp->parseTemplate($this->template['address']['edit']['shipping']['body'], true, $this->sc);
			}
			else
			{
				e107::getMessage()->addError(''.LAN_VSTORE_ERR_014.'', 'vstore');
			}

			$text .= '
				<hr/>
				<div class="text-center">
				<a href="'.e107::url('vstore', 'dashboard', array('dash' => 'addresses')).'"  class="btn btn-default btn-secondary">'. LAN_BACK.'</a>&nbsp;';
			$text .= $this->frm->button('edit_address', $this->id, 'submit', LAN_SAVE, array('class' => 'btn btn-primary'));
			$text .= '</div>';

			$text .= $this->frm->close();
        }
        else
        {
            $text .= '<p>'.LAN_VSTORE_CART_036.'</p>';
        }
        unset($data);        
        return $text;
    }	


    /**
     * jump to user profiles
     *
     * @return void
     */
    private function account()
    {
        e107::redirect(SITEURL.'usersettings.php');
        exit;
    }
}