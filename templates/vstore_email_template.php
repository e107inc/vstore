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
		
	 Hello {ORDER_SHIP_FIRSTNAME} {ORDER_SHIP_LASTNAME},<br />
	 <br />
	 Thank you for your purchase.<br />
	 Your order reference number is: #{ORDER_REF}<br />
	 <br />
	 <table class='table'>
	 	<colgroup>	
	 		<col style='width:50%' />
	 		<col style='width:50%' />
	 	</colgroup>
	 	<tr>
	 		<th>Merchant</th>
	 		<th class='text-right'>Customer</th>
	 	</tr>
	 	<tr>
	 		<td>{ORDER_MERCHANT_INFO}</td>
	 		<td class='text-right'>
	 			{ORDER_SHIP_FIRSTNAME} {ORDER_SHIP_LASTNAME}<br />
	 			{ORDER_SHIP_ADDRESS}<br />
	 			{ORDER_SHIP_CITY} &nbsp;{ORDER_SHIP_STATE} &nbsp;{ORDER_SHIP_ZIP}<br />
	 			{ORDER_SHIP_COUNTRY}
			</td>
	 	</tr>
	 </table>
	 
	 
	 {ORDER_ITEMS}
	 
	 <hr />
	 {ORDER_PAYMENT_INSTRUCTIONS}
	 
	 ";

	 $VSTORE_EMAIL_TEMPLATE['completed'] = "
		
	 Hello {ORDER_SHIP_FIRSTNAME} {ORDER_SHIP_LASTNAME},<br />
	 <br />
	 your order #{ORDER_REF} has just been completed and will be shipped to you soon.<br />
	 <br />
	 <table class='table'>
	 	<colgroup>	
	 		<col style='width:50%' />
	 		<col style='width:50%' />
	 	</colgroup>
	 	<tr>
	 		<th>Merchant</th>
	 		<th class='text-right'>Customer</th>
	 	</tr>
	 	<tr>
	 		<td>{ORDER_MERCHANT_INFO}</td>
	 		<td class='text-right'>
	 			{ORDER_SHIP_FIRSTNAME} {ORDER_SHIP_LASTNAME}<br />
	 			{ORDER_SHIP_ADDRESS}<br />
	 			{ORDER_SHIP_CITY} &nbsp;{ORDER_SHIP_STATE} &nbsp;{ORDER_SHIP_ZIP}<br />
	 			{ORDER_SHIP_COUNTRY}
			</td>
	 	</tr>
	 </table>
	 
	 
	 {ORDER_ITEMS}
	 
	 
	 ";
/*

	  $VSTORE_EMAIL_TEMPLATE['error'] = "
	 
	 <div class='alert alert-danger alert-block'>Something went wrong with your order.</div>
	 

	 
	 ";*/