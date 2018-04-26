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
        <td>{ORDER_DATA: order_status}</td>
        <td>{ORDER_ACTIONS}</td>
    </tr>
';



$VSTORE_DASHBOARD_TEMPLATE['order']['detail'] = '
    <h4>Order details {ORDER_DATA: order_ref}</h4>
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
        {ORDER_ITEMS}
    </div>
';

