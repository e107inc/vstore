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
	$VSTORE_INVOICE_TEMPLATE['default'] = '

<table style="width: 100%;">
	<thead>
	<tr>
		<td style="height:2.5cm" colspan="2">
			<!-- use the height to adjust the height of the addressfield  -->
		</td>
	</tr>
	<tr>
		<td style="width: 65%;height: 5cm;">
			<span style="font-size:0.2cm;">{ORDER_MERCHANT_INFO: line}</span><br />
			<br />
			{ORDER_DATA: cust_firstname} {ORDER_DATA: cust_lastname}<br />
			{ORDER_DATA: cust_company}<br />
			{ORDER_DATA: cust_address}<br />
			{ORDER_DATA: cust_city} &nbsp;{ORDER_DATA: cust_state} &nbsp;{ORDER_DATA: cust_zip}<br />
			{ORDER_DATA: cust_country}		
		</td>

		<td style="width: 35%;">
			<b>{INVOICE_DATA: info_title}</b><br />
			Invoice-Nr.: {ORDER_DATA: order_invoice_nr}<br />
			Order-Nr.: {ORDER_DATA: order_ref}<br />
			Order date: {ORDER_DATA: order_date}<br />
			Payment method: {ORDER_DATA: order_gateway}<br />
			Payment deadline: {INVOICE_DATA: payment_deadline}		
		</td>
	</tr>

	<tr>
		<td colspan="2" style="height: 2.5cm;">
			<h2>{INVOICE_DATA: title}</h2>
			<br />
			{INVOICE_DATA: subject}<br />
		</td>
	</tr>
	</thead>

	<tbody>
	<tr>
		<td colspan="2">
			{INVOICE_ITEMS}
		</td>
	</tr>

	<tr>
		<td colspan="2">
			{INVOICE_DATA: hint}
		</td>
	</tr>

	<tr>
		<td colspan="2">
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
<br/>
<br/>
<table style="width: 100%;">
<tr>
	<th style="width: 5%; text-align:center; line-height: 2.5em;"><b>No.</b></th>
	<th style="width: 45%;line-height: 2.5em;"><b>Product</b></th>
	<th style="width: 10%; text-align:right; line-height: 2.5em;"><b>Tax</b></th>
	<th style="width: 15%; text-align:right; line-height: 2.5em;"><b>Unit Price</b></th>
	<th style="width: 10%; text-align:right; line-height: 2.5em;"><b>Qty</b></th>
	<th style="width: 15%; text-align:right; line-height: 2.5em;"><b>Amount</b></th>
</tr>
';

$VSTORE_INVOICE_TEMPLATE['invoice_items']['row'] = '
<tr>
	<td style=" text-align:right; line-height: 2.5em;">{CART_DATA: nr}</td>
	<td style="line-height: 2.5em;">{CART_DATA: name}</td>
	<td style="text-align:right; line-height: 2.5em;">{CART_DATA: tax}</td>
	<td style="text-align:right; line-height: 2.5em;">{CART_DATA: price}</td>
	<td style="text-align:right; line-height: 2.5em;">{CART_DATA: quantity}</td>
	<td style="text-align:right; line-height: 2.5em;">{CART_DATA: item_total}</td>
</tr>';

$VSTORE_INVOICE_TEMPLATE['invoice_items']['footer'] = '
<tr>
	<td></td>
	<td></td>
	<td colspan="3" style="line-height: 2.5em;"><b>Subtotal</b></td>
	<td style="text-align:right; line-height: 2.5em;">{CART_DATA: sub_total}</td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td colspan="3" style="line-height: 2.5em;"><b>Shipping</b></td>
	<td style="text-align:right; line-height: 2.5em;">{CART_DATA: shipping_total}</td>
</tr>
{INVOICE_COUPON}
{INVOICE_TAX}
<tr>
	<td></td>
	<td></td>
	<td colspan="3" style="border-top: 1px solid #cccccc; line-height: 2.5em; font-size: 1.5em;"><b>Total</b></td>
	<td style="text-align:right; border-top: 1px solid #cccccc; line-height: 2.5em; font-size: 1.5em;"><b>{CART_DATA: grand_total}</b></td>
</tr>
</table>
';

$VSTORE_INVOICE_TEMPLATE['invoice_items']['coupon'] = '
<tr>
	<td></td>
	<td></td>
	<td colspan="3">Coupon: <b>[x]</b></td>
	<td style="text-align:right;">[y]</td>
</tr>
';


$VSTORE_INVOICE_TEMPLATE['invoice_items']['tax'] = '
<tr>
	<td></td>
	<td></td>
	<td colspan="2"><b>Included VAT</b></td>
	<td style="text-align:right;">[x]</td>
	<td style="text-align:right;">[y]</td>
</tr>
';


$VSTORE_INVOICE_TEMPLATE['display'] = '
<style>
.vstore-invoice-wrapper { width: 100%; padding: 20px; box-shadow: 3px 3px 10px silver; }
.vstore-invoice-table { width: 100%; }
.vstore-invoice-header { }
.vstore-invoice-sitelogo { float: left; margin-right: 10px; }
.vstore-invoice-sitename { vertical-align: middle; line-height: 80px; font-size: 1.8em; }
.vstore-invoice-body { }
.vstore-invoice-footer { }
</style>
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
';
?>