<?php


/**
 * Shopping cart page
 */

$VSTORE_CART_TEMPLATE['start'] = '
<table class="table table-hover table-striped cart">
<thead>
	<tr>
		<th>'.LAN_VSTORE_CART_005.'</th>
		<th> </th>
		<th>'.LAN_VSTORE_CART_010.'</th>
		<th class="text-right text-end">'.LAN_PLUGIN_VSTORE_PRICE.'</th>
		<th class="text-right text-end">'.LAN_VSTORE_GEN_013.'</th>

	</tr>
</thead>
<tbody>';


$VSTORE_CART_TEMPLATE['item'] = '
{SETIMAGE: w=72&h=72&crop=1}
	<tr>
		<td>
			<div class="media">
				<div class="media-left">
					<a href="{ITEM_URL}">{ITEM_PIC: class=media-object}</a>
				</div>
				<div class="media-body">
					<h4 class="media-heading"><a href="{ITEM_URL}">{ITEM_NAME}</a></h4>
					<h5 class="media-heading">'.LAN_VSTORE_CART_015.'  <a href="{ITEM_BRAND_URL}">{ITEM_BRAND}</a></h5>
					{ITEM_VAR_STRING}
				</div>
			</div>
		</td>
		<td class="col-sm-1 col-md-1 text-center">{CART_REMOVEBUTTON}</td>
		<td class="col-sm-1 col-md-1 text-center">{CART_VARS}{CART_QTY=edit} </td>
		<td class="col-sm-1 col-md-1 text-right text-end">{CART_PRICE}</td>
		<td class="col-sm-1 col-md-1 text-right text-end"><strong>{CART_TOTAL}</strong></td>

	</tr>
	';

$VSTORE_CART_TEMPLATE['end'] = '     
	<tr>
		<td colspan="4" class="text-right text-end">'.LAN_VSTORE_CART_009.'</td>
		<td class="text-right text-end"><strong>{CART_SUBTOTAL}</strong></td>
	</tr>
	<tr>
		<td colspan="4" class="text-right text-end">'.LAN_VSTORE_CART_011.'</td>
		<td class="text-right text-end"><strong>{CART_SHIPPINGTOTAL}</strong></td>
	</tr>
	{CART_COUPON}
	{CART_TAXTOTAL}
	</tbody>
	<tfoot>
	<tr>
		<td colspan="4" class="text-right text-end"><h4>'.LAN_VSTORE_GEN_013.'</h4></td>
		<td class="text-right text-end"><h4><strong>{CART_GRANDTOTAL}</strong></h4></td>
	</tr>
	</tfoot>
	
	</table>
	<div class="row">
		<div class="col-md-6">{CART_CONTINUESHOP}</div>
		<div class="col-md-6 text-right text-end">{CART_CHECKOUT_BUTTON}</div>
	</div>
';

$VSTORE_CART_TEMPLATE['tax'] = '
<tr>
	<td colspan="3" class="text-right text-end">Tax</td>
	<td class="text-right text-end">[x]</td>
	<td class="text-right text-end"><strong>[y]</strong></td>
</tr>
';

$VSTORE_CART_TEMPLATE['coupon'] = '
	<tr>
		<td colspan="4"><div class="pull-right float-right float-end">{CART_COUPON_FIELD}</div></td>
		<td class="text-right text-end"><strong>{CART_COUPON_VALUE}</strong></td>
	</tr>
';