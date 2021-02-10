<?php

$VSTORE_DASHBOARD_TEMPLATE = array();


$VSTORE_DASHBOARD_TEMPLATE['header'] = '
    <h3>{DASHBOARD: title}</h3>

    {DASHBOARD: nav}
    <br />
    <div>
';

$VSTORE_DASHBOARD_TEMPLATE['footer'] = '
    </div>
';

/**
 * Dashboard navigation
 */
$VSTORE_DASHBOARD_TEMPLATE['nav']['start'] = '
	<ul class="nav nav-tabs">';
$VSTORE_DASHBOARD_TEMPLATE['nav']['end'] = '
	</ul>
';
$VSTORE_DASHBOARD_TEMPLATE['nav']['item'] = '
		<li role="presentation" class="nav-item [active]"><a class="nav-link" href="[url]">[caption]</a></li>';


/**
 * Dashboard
 */
$VSTORE_DASHBOARD_TEMPLATE['dashboard'] = '
    <p>From your account dashboard you can view your recent orders, manage your shipping and billing addresses and edit your password and account details.</p>
';


/**
 * List orders
 */
$VSTORE_DASHBOARD_TEMPLATE['order']['list']['header'] = '
    <table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Order info</th>
        <th>Shipping</th>
        <th>Items</th>
        <th>Total</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
';


$VSTORE_DASHBOARD_TEMPLATE['order']['list']['footer'] = '
    </tbody>
    </table>
';


$VSTORE_DASHBOARD_TEMPLATE['order']['list']['item'] = '
    <tr>
        <td>
            Date: {ORDER_DATA: order_date}<br />
            Order: {ORDER_DATA: order_ref}<br/>
            Invoice: {ORDER_DATA: order_invoice_nr}
        </td>
        <td>{ORDER_DATA: order_shipping_full}</td>
        <td>{ORDER_DATA: order_items_short}</td>
        <td>{ORDER_DATA: order_pay_amount}</td>
        <td>{ORDER_DATA: order_status_label}</td>
        <td>{ORDER_ACTIONS}</td>
    </tr>
';


/**
 * Order detail
 */
$VSTORE_DASHBOARD_TEMPLATE['order']['detail'] = '
    <h4>Order details {ORDER_DATA: order_ref} <span class="pull-right float-right">{ORDER_DATA: order_status_label}</span></h4>
    <div class="clearfix">
        <div class="col-sm-4">
            <b>Date</b> {ORDER_DATA: order_date}<br />
            <b>Order</b> {ORDER_DATA: order_ref}<br/>
            <b>Invoice</b> {ORDER_DATA: order_invoice_nr}<br/>
            <b>Payment method</b> {ORDER_DATA: order_gateway}<br/>
            <b>Payment complete</b> {ORDER_DATA: order_pay_status}<br/>
        </div>

        <div class="col-sm-4">
            <b>Shipping address</b> <br />
            {ORDER_DATA: order_shipping_full}<br/>
        </div>

        <div class="col-sm-4">
            <b>Billling address</b> <br />
            {ORDER_DATA: order_billing_full}<br/>
        </div>
    </div>

    <br />

    <div>
        <b>Items</b>
        {ORDER_ITEMS}
    </div>

    <div>
        <b>Log</b>
        {ORDER_DATA: order_log}
    </div>

    <hr />

    <div class="text-center">
        {ORDER_ACTIONS: cancel}
    </div>
';


/**
 * List downloads
 */
$VSTORE_DASHBOARD_TEMPLATE['download']['list']['header'] = '
    <table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Order info</th>
        <th>Items</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
';


$VSTORE_DASHBOARD_TEMPLATE['download']['list']['footer'] = '
    </tbody>
    </table>
';


$VSTORE_DASHBOARD_TEMPLATE['download']['list']['item'] = '
    <tr>
        <td>
            Date: {ORDER_DATA: order_date}<br />
            Order: {ORDER_DATA: order_ref}<br/>
            Invoice: {ORDER_DATA: order_invoice_nr}
        </td>
        <td>{ORDER_DATA: order_downloads}</td>
        <td>{ORDER_DATA: order_status_label}</td>
    </tr>
';


/**
 * List downloads
 */
$VSTORE_DASHBOARD_TEMPLATE['address']['view'] = '
	<div class="row">
	    <div class="col-12 col-xs-12 col-sm-6">
	    	<div class="card h-100">
		        <div class="card-body">
			        <h4 class="card_title">Billing</h4>
			
			        {DASHBOARD: billing_address}
			        
			    </div>
			     <div class="card-footer text-right text-end">
			        {DASHBOARD: edit_billing}
			     </div>
		     </div>
	    </div>
	
	    <div class="col-12 col-xs-12 col-sm-6">
	    <div class="card h-100">
	    		<div class="card-body">
		        <h4 class="card_title">Shipping</h4>
		
		        {DASHBOARD: shipping_address}
			      
		        </div>
		          <div class="card-footer text-right text-end">
			        {DASHBOARD: edit_shipping}
			      </div>
	        </div>
	    </div>
    </div>

';


/**
 * Shipping details form
 */
$VSTORE_DASHBOARD_TEMPLATE['address']['edit']['shipping']['body'] = '
	<h4>Shipping Details</h4>

	<div class="row g-3">
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_firstname" class="form-label required">First Name</label>
				{SHIPPING_FIELD: ship_firstname}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_lastname" class="form-label required">Last Name</label>
				{SHIPPING_FIELD: ship_lastname}
			</div>
		</div>

		<div class="col col-xs-12">
			<div class="form-group">
				<label for="ship_company" class="form-label">Company</label>
				{SHIPPING_FIELD: ship_company}
			</div>
		</div>

		<div class="col col-xs-12">
			<div class="form-group">
				<label for="ship_address" class="form-label required">Address</label>
				{SHIPPING_FIELD: ship_address}
			</div>
		</div>

		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_city" class="form-label required">Town/City</label>
				{SHIPPING_FIELD: ship_city}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_state" class="form-label required">State/Region</label>
				{SHIPPING_FIELD: ship_state}
			</div>
		</div>

		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_zip" class="form-label required">Zip/Postcode</label>
				{SHIPPING_FIELD: ship_zip}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_country" class="form-label required">Country</label>
				{SHIPPING_FIELD: ship_country}
			</div>
		</div>

		<div class="col col-xs-12">
			<div class="form-group">
				<label for="ship_phone" class="form-label">Phone number</label>
				{SHIPPING_FIELD: ship_phone}
			</div>
		</div>

		<div class="col-12 col-xs-12">
			<div class="form-group">
				<label class="required"></label> Required field
			</div>
		</div>
	</div>
    ';
    

/**
 * Customer details form
 */
$VSTORE_DASHBOARD_TEMPLATE['address']['edit']['billing']['body'] = '
	<h4>Billing address</h4>

	<div class="row g-3">
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_firstname" class="form-label required">First Name</label>
				{CUSTOMER_FIELD: cust_firstname}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_lastname" class="form-label required">Last Name</label>
				{CUSTOMER_FIELD: cust_lastname}
			</div>
		</div>

		<div class="col col-xs-12">
			<div class="form-group">
				<label for="cust_company" class="form-label">Company</label>
				{CUSTOMER_FIELD: cust_company}
			</div>
		</div>

		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_vat_id" class="form-label">VAT ID</label>
				{CUSTOMER_FIELD: cust_vat_id}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_taxcode" class="form-label">Tax code</label>
				{CUSTOMER_FIELD: cust_taxcode}
			</div>
		</div>

		<div class="col col-xs-12">
			<div class="form-group">
				<label for="cust_address" class="form-label required">Address</label>
				{CUSTOMER_FIELD: cust_address}
			</div>
		</div>

		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_city" class="form-label required">Town/City</label>
				{CUSTOMER_FIELD: cust_city}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_state" class="form-label">State/Region</label>
				{CUSTOMER_FIELD: cust_state}
			</div>
		</div>

		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_zip" class="form-label required">Zip/Postcode</label>
				{CUSTOMER_FIELD: cust_zip}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_country" class="form-label required">Country</label>
				{CUSTOMER_FIELD: cust_country}
			</div>
		</div>

		<div class="col-12 col-xs-12">
			<div class="form-group">
				<label for="cust_email" class="form-label required">Email address</label>
				{CUSTOMER_FIELD: cust_email}
			</div>
		</div>

		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_phone" class="form-label">Phone number</label>
				{CUSTOMER_FIELD: cust_phone}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_email" class="form-label">Fax number</label>
				{CUSTOMER_FIELD: cust_fax}
			</div>
		</div>


	{CUSTOMER_FIELD: add_field0}

	{CUSTOMER_FIELD: add_field1}
	
	{CUSTOMER_FIELD: add_field2}
	
	{CUSTOMER_FIELD: add_field3}


		<div class="col-12 col-xs-12">
			<div class="form-group">
				<label class="required"></label> Required field
			</div>
		</div>
	</div>
';

$VSTORE_DASHBOARD_TEMPLATE['address']['edit']['billing']['additional']['item'] = '

		<div class="col-12 col-md-12">
			<div class="form-group">
				{CUSTOMER_ADD_LABEL}
				{CUSTOMER_ADD_FIELD}
			</div>
		</div>

';
    