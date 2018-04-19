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

	// Content of the invoice.


	// $VSTORE_INVOICE_TEMPLATE['default'] = '

	// {INVOICE_ITEMS}';

	$VSTORE_INVOICE_TEMPLATE['default'] = '

<table>
	<thead>
	<tr>
		<td style="height:2.5cm" colspan="2">
			<!-- use the height to adjust the height of the addressfield  -->
		</td>
	</tr>
	<tr>
		<td width="65%" style="height: 5cm;">
			<span style="font-size:0.2cm;">{ORDER_MERCHANT_INFO: line}</span><br />
			<br />
			{ORDER_DATA: cust_firstname} {ORDER_DATA: cust_lastname}<br />
			{ORDER_DATA: cust_company}<br />
			{ORDER_DATA: cust_address}<br />
			{ORDER_DATA: cust_city} &nbsp;{ORDER_DATA: cust_state} &nbsp;{ORDER_DATA: cust_zip}<br />
			{ORDER_DATA: cust_country}		
		</td>

		<td  width="35%">
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
<table style="height: 3cm">
<tbody>
	<tr>
		<td style="vertical-align:top;font-size:0.25cm;" width="25%">
			{INVOICE_DATA: footer0}
		</td>
		<td style="vertical-align:top;font-size:0.25cm;" width="25%">
			{INVOICE_DATA: footer1}
		</td>
		<td style="vertical-align:top;font-size:0.25cm;" width="25%">
			{INVOICE_DATA: footer2}
		</td>
		<td style="vertical-align:top;font-size:0.25cm;" width="25%">
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
<table cellpadding="2" cellspacing="0">
<tr>
	<th style="width: 50%;"><b>Description</b></th>
	<th style="width: 10%;" align="right"><b>Tax</b></th>
	<th style="width: 15%;" align="right"><b>Unit Price</b></th>
	<th style="width: 10%;" align="right"><b>Qty</b></th>
	<th style="width: 15%;" align="right"><b>Amount</b></th>
</tr>
';

$VSTORE_INVOICE_TEMPLATE['invoice_items']['row'] = '
<tr>
	<td>{CART_DATA: name}</td>
	<td align="right">{CART_DATA: tax}</td>
	<td align="right">{CART_DATA: price}</td>
	<td align="right">{CART_DATA: quantity}</td>
	<td align="right">{CART_DATA: item_total}</td>
</tr>';

$VSTORE_INVOICE_TEMPLATE['invoice_items']['footer'] = '
<tr>
	<td></td>
	<td colspan="3"><b>Subtotal</b></td>
	<td align="right">{CART_DATA: sub_total}</td>
</tr>
<tr>
	<td></td>
	<td colspan="3"><b>Shipping</b></td>
	<td align="right">{CART_DATA: shipping_total}</td>
</tr>
{INVOICE_COUPON}
{INVOICE_TAX}
<tr>
	<td></td>
	<td colspan="3" style="border-top: 1px solid #cccccc"><b>Total</b></td>
	<td align="right" style="border-top: 1px solid #cccccc"><b>{CART_DATA: grand_total}</b></td>
</tr>
</table>
';

$VSTORE_INVOICE_TEMPLATE['invoice_items']['coupon'] = '
<tr>
	<td></td>
	<td colspan="3">Coupon: <b>[x]</b></td>
	<td align="right">[y]</td>
</tr>
';


$VSTORE_INVOICE_TEMPLATE['invoice_items']['tax'] = '
<tr>
	<td></td>
	<td colspan="2"><b>Included VAT</b></td>
	<td align="right">[x]</td>
	<td align="right">[y]</td>
</tr>
';

?>