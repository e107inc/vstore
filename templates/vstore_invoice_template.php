<?php
	/**
	 * e107 website system
	 *
	 * Copyright (C) 2008-2017 e107 Inc (e107.org)
	 * Released under the terms and conditions of the
	 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
	 *
	 */

	$VSTORE_INVOICE_TEMPLATE = array();
	/**
	*  vstore Invoice template for use to be converted to pdf.

	ATTENTION!!!
	Do mainly use standard html elements as not all html5 tags
	or css is supported by the pdf plugin (TCPDF)!
	You need to test ALL changes to the templates 
	BEFORE using in a production environment!
	
	*/

/* WRAPPERS */

	$VSTORE_INVOICE_WRAPPER['default']['ORDER_MERCHANT_INFO: line'] = '<span style="font-size:0.2cm;">{---}</span><br />';
	$VSTORE_INVOICE_WRAPPER['default']['BILLING: company'] = '{---}<br />';
	$VSTORE_INVOICE_WRAPPER['default']['SHIPPING: company'] = '{---}<br />';
	$VSTORE_INVOICE_WRAPPER['default']['INVOICE_DATA: subject'] = '{---}<br />';

/* TEMPLATE */

	$VSTORE_INVOICE_TEMPLATE['default'] = '

<table style="width: 100%; margin-bottom:.5cm">

	<tr>
		<td style="height:0.5cm" colspan="3">
			<!-- use the height to adjust the height of the addressfield  -->
		</td>
	</tr>
	<tr>
		<td style="width: 33.3%; vertical-align:top">
			<h5>Billing</h5>
			{ORDER_MERCHANT_INFO: line}
			{BILLING: firstname} {BILLING: lastname}<br />
			{BILLING: company}
			{BILLING: address}<br />
			{BILLING: city} &nbsp;{BILLING: state} &nbsp;{BILLING: zip}<br />
			{BILLING: country}		
		</td>
		
		<td style="width: 33.3%; vertical-align:top">
			<h5>Shipping</h5>
			{SHIPPING: firstname} {SHIPPING: lastname}<br />
			{SHIPPING: company}
			{SHIPPING: address}<br />
			{SHIPPING: city} &nbsp;{SHIPPING: state} &nbsp;{SHIPPING: zip}<br />
			{SHIPPING: country}		
		</td>

		<td style="width: 33.3%; vertical-align:top">
			<h5>{INVOICE_DATA: info_title}</h5>
			<table style="width: 100%;">
			<tr>
				<td>Invoice #:</td><td class="text-right">{ORDER_DATA: order_invoice_nr}</td>
			</tr>
			<tr>
				<td>Order #:</td><td class="text-right">{ORDER_DATA: order_ref}</td>
			</tr>
			<tr>
				<td>Order date:</td><td class="text-right">{ORDER_DATA: order_date}</td>
			</tr>
			<tr>
				<td>Payment method:</td><td class="text-right">{ORDER_DATA: order_gateway}</td>
			</tr>
			<tr>
				<td>Due by:</td><td class="text-right">{INVOICE_DATA: payment_deadline}	
			</tr>
			</table>	
		</td>
	</tr>

</table>
<table style="width: 100%;">
	<tbody>
	<tr>
		<td colspan="3">
			{INVOICE_ITEMS}
		</td>
	</tr>

	<tr>
		<td colspan="3">
			{INVOICE_DATA: hint}
		</td>
	</tr>

	<tr>
		<td colspan="3">
			{INVOICE_DATA: finish_phrase}
		</td>
	</tr>
	</tbody>
</table>

';
	


$VSTORE_INVOICE_TEMPLATE['footer'] = '
<table style="height: 3cm; width: 100%;">
<tbody>
	<tr>
		<td style="vertical-align:top;font-size:0.25cm;width:25%;">
			{INVOICE_DATA: footer0}
		</td>
		<td style="vertical-align:top;font-size:0.25cm;width:25%;">
			{INVOICE_DATA: footer1}
		</td>
		<td style="vertical-align:top;font-size:0.25cm;width:25%;">
			{INVOICE_DATA: footer2}
		</td>
		<td style="vertical-align:top;font-size:0.25cm;width:25%;">
			{INVOICE_DATA: footer3}
		</td>
	</tr>
</tbody>
</table>
';

/**
 * Order items list
 * Used in emails and on order summary and confirmation
 */		

$VSTORE_INVOICE_TEMPLATE['invoice_items']['header'] = '
<h2>{INVOICE_DATA: title}</h2>
<table class="table table-striped table-bordered">
<tr>
	<th style="width: 5%; text-align:center; "><b>No.</b></th>
	<th style="width: 45%;"><b>Product</b></th>

	<th style="width: 15%; text-align:right; "><b>Unit Price</b></th>
	<th style="width: 10%; text-align:right; "><b>Qty</b></th>
	<th style="width: 15%; text-align:right;"><b>Amount</b></th>
</tr>
';

$VSTORE_INVOICE_TEMPLATE['invoice_items']['row'] = '
<tr>
	<td style=" text-align:right;">{CART_DATA: nr}</td>
	<td style="text-align:left;">{CART_DATA: name}</td>
	<td style="text-align:right;">{CART_DATA: price}</td>
	<td style="text-align:right;">{CART_DATA: quantity}</td>
	<td style="text-align:right;">{CART_DATA: item_total}</td>
</tr>';

$VSTORE_INVOICE_TEMPLATE['invoice_items']['footer'] = '
<tr>
	<td></td>
	<td></td>
	<td colspan="2" style="text-align:right"><b>Subtotal</b></td>
	<td style="text-align:right;">{CART_DATA: sub_total}</td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td colspan="2" style="text-align:right"><b>Shipping</b></td>
	<td style="text-align:right;">{CART_DATA: shipping_total}</td>
</tr>
{INVOICE_COUPON}
{INVOICE_TAX}
<tr>
	<td></td>
	<td></td>
	<td colspan="2" style="border-top: 1px solid #cccccc; font-size: 1.5em; text-align:right"><b>Total</b></td>
	<td style="text-align:right; border-top: 1px solid #cccccc; font-size: 1.5em;"><b>{CART_DATA: grand_total}</b></td>
</tr>
</table>
';

$VSTORE_INVOICE_TEMPLATE['invoice_items']['coupon'] = '
<tr>
	<td></td>
	<td></td>
	<td>Coupon: <b>[x]</b></td>
	<td style="text-align:right;">[y]</td>
</tr>
';


$VSTORE_INVOICE_TEMPLATE['invoice_items']['tax'] = '
<tr>
	<td ></td>
	<td ></td>
	<td colspan="2" style="text-align:right;"><b>Tax ([x])</b></td>
	<td style="text-align:right;">[y]</td>
</tr>
';


$VSTORE_INVOICE_TEMPLATE['display'] = '
<html lang="en">
  <head>
    <title>Invoice</title>
    <meta charset=utf-8>
    <style type="text/css">
body { padding:10px; background-color: #E1E1E1 }
table.table{ border-collapse:collapse; border-spacing:0; width:100%; }
.table-striped>tbody>tr:nth-child(2n+1)>td,.table-striped>tbody>tr:nth-child(2n+1)>th{ background-color:#F9F9F9; }
.table-bordered > thead>tr>th,.table-bordered>tbody>tr>th,.table-bordered>tfoot>tr>th,.table-bordered>thead>tr>td,.table-bordered>tbody>tr>td,.table-bordered>tfoot>tr>td{
border:1px solid #DDD; }
.table>thead>tr>th,.table>tbody>tr>th,.table>tfoot>tr>th,.table>thead>tr>td,.table>tbody>tr>td,.table>tfoot>tr>td{ padding:8px;
line-height:1.42857; vertical-align:top; border-top:1px solid #DDD;}											
.vstore-invoice-wrapper { padding:10px; width: 93%; max-width:1000px; background-color: #FFFFFF; border-radius: 5px; font-family: helvetica,arial }
.vstore-invoice-table { width: 100%; }
.vstore-invoice-header { }
.vstore-invoice-sitelogo { float: left; margin-right: 10px; }
.vstore-invoice-sitename { vertical-align: middle; line-height: 80px; font-size: 1.8em; }
.vstore-invoice-body { }
.vstore-invoice-footer { }
.text-right { text-align: right } 
</style>
<body>
<div class="vstore-invoice-wrapper">
<table class="table vstore-invoice-table">
	<thead>
	<tr>
		<td class="vstore-invoice-header">
			<div class="vstore-invoice-sitelogo">{SITELOGO:h=80}</div>
			<div class="vstore-invoice-sitename">[sitename]</div>
		</td>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td class="vstore-invoice-body">
			[body]
		</td>
	</tr>
	</tbody>
	<tfoot>
	<tr>
		<td class="vstore-invoice-footer">
			<br/>
			<br/>
			[footer]
		</td>
	</tr>
	</tfoot>
</table>
</div>
</body>
</html>
';
