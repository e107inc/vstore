<?php
	/**
	 * e107 website system
	 *
	 * Copyright (C) 2008-2017 e107 Inc (e107.org)
	 * Released under the terms and conditions of the
	 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
	 *
	 */

	$VSTORE_EMAIL_TEMPLATE = array();

	// Content of the email.

	$VSTORE_EMAIL_TEMPLATE['default'] = "
	
	Hello {BILLING: firstname} {BILLING: lastname},<br />
	<br />
	Thank you for your purchase.<br />
	Your order reference number is: #{REF}<br />
	<br />
	<table class='table'>
	<colgroup>	
		<col style='width:50%' />
		<col style='width:50%' />
	</colgroup>
	<tr>
		<th>Merchant</th>
		<th>Customer</th>
	</tr>
	<tr>
		<td>{ORDER_MERCHANT_INFO}</td>
		<td>
			<h4>Billing address</h4>
			{BILLING: firstname} {BILLING: lastname}<br />
			{BILLING: company}<br />
			{BILLING: address}<br />
			{BILLING: city} &nbsp;{BILLING: state} &nbsp;{BILLING: zip}<br />
			{BILLING: country}
			<br />
			<h4>Shipping address</h4>
			{SHIPPING: firstname} {SHIPPING: lastname}<br />
			{SHIPPING: company}<br />
			{SHIPPING: address}<br />
			{SHIPPING: city} &nbsp;{SHIPPING: state} &nbsp;{SHIPPING: zip}<br />
			{SHIPPING: country}
		</td>
	</tr>
	</table>
	
	
	{ORDER_ITEMS}
	
	<hr />
	{ORDER_PAYMENT_INSTRUCTIONS}
	
	<br />
	<br />
	Kind regards,<br />
	{SENDER_NAME}

	";

	$VSTORE_EMAIL_TEMPLATE['completed'] = "
	
	Hello {BILLING: firstname} {BILLING: lastname},<br />
	<br />
	your order #{REF} from {ORDER_DATA: order_date} has just been completed and will be shipped to you soon.<br />
	<br />
	<table class='table'>
	<colgroup>	
		<col style='width:50%' />
		<col style='width:50%' />
	</colgroup>
	<tr>
		<th>Merchant</th>
		<th>Customer</th>
	</tr>
	<tr>
		<td>{ORDER_MERCHANT_INFO}</td>
		<td>
			<h4>Billing address</h4>
			{BILLING: firstname} {BILLING: lastname}<br />
			{BILLING: company}<br />
			{BILLING: address}<br />
			{BILLING: city} &nbsp;{BILLING: state} &nbsp;{BILLING: zip}<br />
			{BILLING: country}
			<br />
			<h4>Shipping address</h4>
			{SHIPPING: firstname} {SHIPPING: lastname}<br />
			{SHIPPING: company}<br />
			{SHIPPING: address}<br />
			{SHIPPING: city} &nbsp;{SHIPPING: state} &nbsp;{SHIPPING: zip}<br />
			{SHIPPING: country}
		</td>
	</tr>
	</table>
	
	
	{ORDER_ITEMS}
	
	<br />
	<br />
	Kind regards,<br />
	{SENDER_NAME}
	
	";

	$VSTORE_EMAIL_TEMPLATE['cancelled'] = "
	
	Hello {BILLING: firstname} {BILLING: lastname},<br />
	<br />
	your order #{REF} from {ORDER_DATA: order_date} has just been cancelled.<br />
	<br />
	Any payment we received from you on this order will be refunded.<br />
	<br />
	Kind regards,<br />
	{SENDER_NAME}

	";

	$VSTORE_EMAIL_TEMPLATE['refunded'] = "
	
	Hello {BILLING: firstname} {BILLING: lastname},<br />
	<br />
	the payment for your order #{REF} from {ORDER_DATA: order_date} has just been refunded.<br />
	<br />
	Kind regards,<br />
	{SENDER_NAME}

	";
/*

	  $VSTORE_EMAIL_TEMPLATE['error'] = "
	 
	 <div class='alert alert-danger alert-block'>Something went wrong with your order.</div>
	 

	 
	 ";*/