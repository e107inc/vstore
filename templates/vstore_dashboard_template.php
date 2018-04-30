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

    <div class="col-12 col-xs-12 col-sm-6">
        <h4>Billing</h4>

        {DASHBOARD: billing_address}

        <br />
        {DASHBOARD: edit_billing}
    </div>

    <div class="col-12 col-xs-12 col-sm-6">
        <h4>Shipping</h4>

        {DASHBOARD: shipping_address}

        <br />
        {DASHBOARD: edit_shipping}
    </div>
    

';


/**
 * Shipping details form
 */
$VSTORE_DASHBOARD_TEMPLATE['address']['edit']['shipping']['body'] = '
	<h4>Shipping Details</h4>

	<div class="row">
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_firstname" class="required">First Name</label>
				{SHIPPING_FIELD: ship_firstname}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_lastname" class="required">Last Name</label>
				{SHIPPING_FIELD: ship_lastname}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col col-xs-12">
			<div class="form-group">
				<label for="ship_company">Company</label>
				{SHIPPING_FIELD: ship_company}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col col-xs-12">
			<div class="form-group">
				<label for="ship_address" class="required">Address</label>
				{SHIPPING_FIELD: ship_address}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_city" class="required">Town/City</label>
				{SHIPPING_FIELD: ship_city}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_state" class="required">State/Region</label>
				{SHIPPING_FIELD: ship_state}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_zip" class="required">Zip/Postcode</label>
				{SHIPPING_FIELD: ship_zip}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_country" class="required">Country</label>
				{SHIPPING_FIELD: ship_country}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col col-xs-12">
			<div class="form-group">
				<label for="ship_phone">Phone number</label>
				{SHIPPING_FIELD: ship_phone}
			</div>
		</div>
	</div>

	<div class="row">
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

	<div class="row">
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_firstname" class="required">First Name</label>
				{CUSTOMER_FIELD: cust_firstname}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_lastname" class="required">Last Name</label>
				{CUSTOMER_FIELD: cust_lastname}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col col-xs-12">
			<div class="form-group">
				<label for="cust_company">Company</label>
				{CUSTOMER_FIELD: cust_company}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_vat_id">VAT ID</label>
				{CUSTOMER_FIELD: cust_vat_id}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_taxcode">Tax code</label>
				{CUSTOMER_FIELD: cust_taxcode}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col col-xs-12">
			<div class="form-group">
				<label for="cust_address" class="required">Address</label>
				{CUSTOMER_FIELD: cust_address}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_city" class="required">Town/City</label>
				{CUSTOMER_FIELD: cust_city}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_state">State/Region</label>
				{CUSTOMER_FIELD: cust_state}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_zip" class="required">Zip/Postcode</label>
				{CUSTOMER_FIELD: cust_zip}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_country" class="required">Country</label>
				{CUSTOMER_FIELD: cust_country}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-12 col-xs-12">
			<div class="form-group">
				<label for="cust_email" class="required">Email address</label>
				{CUSTOMER_FIELD: cust_email}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_phone">Phone number</label>
				{CUSTOMER_FIELD: cust_phone}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_email">Fax number</label>
				{CUSTOMER_FIELD: cust_fax}
			</div>
		</div>
	</div>

	{CUSTOMER_FIELD: add_field0}

	{CUSTOMER_FIELD: add_field1}
	
	{CUSTOMER_FIELD: add_field2}
	
	{CUSTOMER_FIELD: add_field3}

	<div class="row">
		<div class="col-12 col-xs-12">
			<div class="form-group">
				<label class="required"></label> Required field
			</div>
		</div>
	</div>
';

$VSTORE_DASHBOARD_TEMPLATE['address']['edit']['billing']['additional']['item'] = '
	<div class="row">
		<div class="col-12 col-md-12">
			<div class="form-group">
				{CUSTOMER_ADD_LABEL}
				{CUSTOMER_ADD_FIELD}
			</div>
		</div>
	</div>
';
    