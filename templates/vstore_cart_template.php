<?php


/**
 * Shopping cart page
 */

$VSTORE_CART_TEMPLATE['start'] = '
<table class="table table-hover table-striped cart">
<thead>
	<tr>
		<th>Product</th>
		<th> </th>
		<th>Quantity</th>
		<th class="text-right text-end">Price</th>
		<th class="text-right text-end">Total</th>

	</tr>
</thead>
<tbody>';


$VSTORE_CART_TEMPLATE['item'] = '
{SETIMAGE: w=72&h=72&crop=1}
	<tr>
		<td>
			<div class="media d-flex">
				<div class="media-left me-2">
					<a href="{ITEM_URL}">{ITEM_PIC: class=media-object}</a>
				</div>
				<div class="media-body">
					<h5 class="media-heading"><a href="{ITEM_URL}">{ITEM_NAME}</a></h5>
					<h6 class="media-heading"> by <a href="{ITEM_BRAND_URL}">{ITEM_BRAND}</a></h6>
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
		<td colspan="4" class="text-right text-end">Subtotal</td>
		<td class="text-right text-end"><strong>{CART_SUBTOTAL}</strong></td>
	</tr>
	<tr>
		<td colspan="4" class="text-right text-end">Estimated shipping</td>
		<td class="text-right text-end"><strong>{CART_SHIPPINGTOTAL}</strong></td>
	</tr>
	{CART_COUPON}
	{CART_TAXTOTAL}
	</tbody>
	<tfoot>
	<tr>
		<td colspan="4" class="text-right text-end"><h4>Total</h4></td>
		<td class="text-right text-end"><h4>{CART_GRANDTOTAL}</h4></td>
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

