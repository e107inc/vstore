<?php

$VSTORE_TEMPLATE = array();

// List View.
$VSTORE_TEMPLATE['list']['start']   = '<div class="row"><div class="col-md-12">{CAT_INFO}</div>';
$VSTORE_TEMPLATE['list']['item']    =  '
										{SETIMAGE: w=320&h=320&crop=1}
										<div class="vstore-product-list col-sm-4 col-lg-4 col-md-4">
							                        <div class="panel panel-default">
								                        <div class="panel-body">
								                            <a href="{ITEM_URL}">{ITEM_PIC}</a>
								                            <div>
								                                <h4 class="clearfix"><a href="{ITEM_URL}">{ITEM_NAME}</a><span class="pull-right"></span></h4>
								                                <p class="item-description clearfix">{ITEM_DESCRIPTION: limit=150}
																</p>

																{ITEM_VARS}
															   
								                                <div class="row">
								                                    <div class="col-md-5"><a class="item-price" href="{ITEM_URL}">{ITEM_PRICE}</a></div>
								                                    <div class="col-md-7 text-right">{ITEM_ADDTOCART: class=btn btn-sm btn-success vstore-add&class0=btn btn-sm btn-default disabled vstore-add}</div>
								                                </div>

							                            </div>
						                            </div>
						                            <!--
						                            <div class="ratings">
						                                <p class="pull-right">15 reviews</p>
														<p>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
						                                </p>
						                            </div>
						                            -->
						                        </div>
						                    </div>
						                    ';

$VSTORE_TEMPLATE['list']['end']         = '</div>';




$VSTORE_TEMPLATE['menu']['start'] =  '';
// $VSTORE_TEMPLATE['menu']['item'] =  '
// 			{SETIMAGE: w=320&h=250&crop=1}
// 			<div class="vstore-product-list col-sm-12 col-lg-12 col-md-12">
// 	                        <div class="thumbnail">
//                             <a href="{ITEM_URL}">{ITEM_PIC}</a>
//                             <div class="caption">
//                                 <h4><a href="{ITEM_URL}">{ITEM_NAME}</a></h4>
//                                 <p class="item-description">{ITEM_DESCRIPTION}</p>
//                                 <div class="row">
// 								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
// 									<p class="lead">{ITEM_PRICE}</p>
// 								</div>
// 								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
// 									{ITEM_ADDTOCART}
// 								</div>
// 							</div>
//                             </div>

//                         </div>
//                     </div>';

$VSTORE_TEMPLATE['menu']['item'] =  '
			{SETIMAGE: w=100&h=100&crop=1}
			<div class="vstore-product-list col-sm-12 col-lg-12 col-md-12">
				<div class="thumbnail" style="height:auto;">
					<div class="caption">
						<div class="row">
							<a href="{ITEM_URL}" class="col-xs-4">{ITEM_PIC}</a>
							<h4 class="col-xs-8"><a href="{ITEM_URL}">{ITEM_NAME}</a></h4>
						</div>
						<!-- <p class="item-description">{ITEM_DESCRIPTION}</p> -->
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<p class="lead">{ITEM_PRICE}</p>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								{ITEM_ADDTOCART: class=btn btn-sm btn-success vstore-add&class0=btn btn-sm btn-default disabled}
							</div>
						</div>
					</div>
				</div>
			</div>';

$VSTORE_TEMPLATE['menu']['end'] =  '';
// Item View.


$VSTORE_TEMPLATE['item']['main']        = '{SETIMAGE: w=600&h=600}
											<div class="vstore-product-view row">
												<div class="col-md-6">
													<div class="vstore-zoom thumbnail">
														{ITEM_PIC: item=0&link=1}
													</div>
													<div class="row thumbnails">
													{ITEM_PIC: w=200&h=200&crop=1&item=0&link=1&class=thumbnail img-responsive}
													{ITEM_PIC: w=200&h=200&crop=1&item=1&link=1&class=thumbnail img-responsive}
													{ITEM_PIC: w=200&h=200&crop=1&item=2&link=1&class=thumbnail img-responsive}
													{ITEM_PIC: w=200&h=200&crop=1&item=3&link=1&class=thumbnail img-responsive}
													{ITEM_PIC: w=200&h=200&crop=1&item=4&link=1&class=thumbnail img-responsive}
													{ITEM_PIC: w=200&h=200&crop=1&item=5&link=1&class=thumbnail img-responsive}
													{ITEM_PIC: w=200&h=200&crop=1&item=6&link=1&class=thumbnail img-responsive}
													{ITEM_PIC: w=200&h=200&crop=1&item=7&link=1&class=thumbnail img-responsive}

													</div>
										        </div>
										        <div class="col-md-6">
										            <h3>{ITEM_NAME}</h3>

													<p>{ITEM_DESCRIPTION}</p>

													<p>{ITEM_VARS}</p>
										            <p>
										            Product Code: {ITEM_CODE}<br />
										            Availability: {ITEM_AVAILABILITY}<br /><br />
										            <small class="text-muted">Price may change due to exchange rate.</small>
										            </p>
										            <div class="row">
										                <div class="col-md-6 item-price"><h3>{ITEM_PRICE}</h3></div>
										                <div class="col-md-6">{ITEM_ADDTOCART: class=btn btn-success vstore-add&class0=btn btn-sm btn-default disabled}</div>
										            </div>
												</div>
									        </div>
									        <hr />';

$VSTORE_TEMPLATE['item']['details'] = '{ITEM_DETAILS}';
$VSTORE_TEMPLATE['item']['videos'] = '
					{ITEM_VIDEO=0}
					{ITEM_VIDEO=1}
					{ITEM_VIDEO=2}
					{ITEM_VIDEO=3}
			';


$VSTORE_TEMPLATE['item']['files']       = '{ITEM_FILES}';
$VSTORE_TEMPLATE['item']['reviews']     = '{ITEM_REVIEWS}';
$VSTORE_TEMPLATE['item']['related']     = '{ITEM_RELATED}';
$VSTORE_TEMPLATE['item']['howto']       = '{PREF_HOWTOORDER}';

$VSTORE_WRAPPER['item']['ITEM_DETAILS'] = "<p>{---}</p>";
$VSTORE_WRAPPER['item']['ITEM_FILES']   = "<p>{---}</p>";
$VSTORE_WRAPPER['item']['ITEM_REVIEWS'] = "<p>{---}</p>";
$VSTORE_WRAPPER['item']['ITEM_RELATED'] = "<p>{---}</p>";
$VSTORE_WRAPPER['item']['PREF_HOWTOORDER']     = "<p>{---}</p>";
$VSTORE_WRAPPER['item']['ITEM_VIDEO']   = "<div class='col-md-6'><p>{---}</p></div>";



$VSTORE_WRAPPER['item']['ITEM_PIC: w=200&h=200&crop=1&item=0&link=1&class=thumbnail img-responsive'] = "<div class='col-xs-3'><p>{---}</p></div>";
$VSTORE_WRAPPER['item']['ITEM_PIC: w=200&h=200&crop=1&item=1&link=1&class=thumbnail img-responsive'] = "<div class='col-xs-3'><p>{---}</p></div>";
$VSTORE_WRAPPER['item']['ITEM_PIC: w=200&h=200&crop=1&item=2&link=1&class=thumbnail img-responsive'] = "<div class='col-xs-3'><p>{---}</p></div>";
$VSTORE_WRAPPER['item']['ITEM_PIC: w=200&h=200&crop=1&item=3&link=1&class=thumbnail img-responsive'] = "<div class='col-xs-3'><p>{---}</p></div>";
$VSTORE_WRAPPER['item']['ITEM_PIC: w=200&h=200&crop=1&item=4&link=1&class=thumbnail img-responsive'] = "<div class='col-xs-3'><p>{---}</p></div>";
$VSTORE_WRAPPER['item']['ITEM_PIC: w=200&h=200&crop=1&item=5&link=1&class=thumbnail img-responsive'] = "<div class='col-xs-3'><p>{---}</p></div>";
$VSTORE_WRAPPER['item']['ITEM_PIC: w=200&h=200&crop=1&item=6&link=1&class=thumbnail img-responsive'] = "<div class='col-xs-3'><p>{---}</p></div>";
$VSTORE_WRAPPER['item']['ITEM_PIC: w=200&h=200&crop=1&item=7&link=1&class=thumbnail img-responsive'] = "<div class='col-xs-3'><p>{---}</p></div>";

$VSTORE_TEMPLATE['orderconfirm'] = '
		<h3>Summary</h3>
		<div class="row">
			<div class="col-12 col-xs-12 col-sm-6 col-md-6">
				<h4>Shipping address</h4>

				<p>{ORDER_SHIP_FIRSTNAME} {ORDER_SHIP_LASTNAME}</p>
				<p>{ORDER_SHIP_COMPANY}</p>
				<p>{ORDER_SHIP_ADDRESS}</p>
				<p>{ORDER_SHIP_CITY}, {ORDER_SHIP_STATE} {ORDER_SHIP_ZIP}</p>
				<p>{ORDER_SHIP_COUNTRY}</p>
				<br />
				<h4>Selected payment method</h4>
				<p>{ORDER_GATEWAY_ICON} {ORDER_GATEWAY_TITLE}</p>
			</div>

			<div class="col-6 col-xs-12 col-sm-6 col-md-6">
				<h4>Items</h4>
				{ORDER_ITEMS}
			</div>
		</div>
		<hr />
		<div class="row">
			<div class="col-12 col-xs-12">
				<a class="btn btn-default btn-secondary vstore-btn-back-confirm" href="{ORDER_CHECKOUT_URL}">&laquo; Back</a>
				<button class="btn btn-primary vstore-btn-buy-now pull-right float-right" type="submit" name="mode" value="confirm">{ORDER_GATEWAY_ICON: size=1x} Buy now!</button>
			</div>
		</div>
		';


$VSTORE_TEMPLATE['order_items']['header'] = '
<table class="table table-bordered">
<colgroup>	
	<col style="width:50%" />
	<col  />
	<col  />
	<col  />
</colgroup>
<tr>
	<th>Description</th>
	<th class="text-right">Unit Price</th>
	<th class="text-right">Qty</th>
	<th class="text-right">Amount</th>
</tr>
';

$VSTORE_TEMPLATE['order_items']['row'] = '
<tr>
	<td>{CART_CONTENT: name}</td>
	<td class="text-right">{CART_CONTENT: price}</td>
	<td class="text-right">{CART_CONTENT: quantity}</td>
	<td class="text-right">{CART_CONTENT: item_total}</tdclass>
</tr>';

$VSTORE_TEMPLATE['order_items']['footer'] = '
<tr>
	<td colspan="3" class="text-right"><b>Subtotal</b></td>
	<td class="text-right">{CART_CONTENT: sub_total}</td>
</tr>
<tr>
	<td colspan="3" class="text-right"><b>Shipping</b></td>
	<td class="text-right">{CART_CONTENT: shipping_total}</td>
</tr>
<tr>
	<td colspan="3" class="text-right"><b>Total</b></td>
	<td class="text-right">{CART_CONTENT: grand_total}</td>
</tr>
</table>
';



$VSTORE_TEMPLATE['cart']['header'] = '
<table class="table table-hover cart">
<thead>
	<tr>
		<th>Product</th>
		<th> </th>
		<th>Quantity</th>
		<th class="text-right">Price</th>
		<th class="text-right">Total</th>

	</tr>
</thead>
<tbody>';

$VSTORE_TEMPLATE['cart']['row'] = '
{SETIMAGE: w=72&h=72&crop=1}
	<tr>
		<td>
			<div class="media">
				<div class="media-left">
					<a href="{ITEM_URL}">{ITEM_PIC: class=media-object}</a>
				</div>
				<div class="media-body">
					<h4 class="media-heading"><a href="{ITEM_URL}">{ITEM_NAME}</a></h4>
					<h5 class="media-heading"> by <a href="{ITEM_BRAND_URL}">{ITEM_BRAND}</a></h5>
					{ITEM_VAR_STRING}
				</div>
			</div>
		</td>
		<td class="col-sm-1 col-md-1 text-center">{CART_REMOVEBUTTON}</td>
		<td class="col-sm-1 col-md-1 text-center">{CART_VARS}{CART_QTY=edit} </td>
		<td class="col-sm-1 col-md-1 text-right">{CART_PRICE}</td>
		<td class="col-sm-1 col-md-1 text-right"><strong>{CART_TOTAL}</strong></td>

	</tr>
	';

$VSTORE_TEMPLATE['cart']['footer'] = '     
	<tr>
		<td colspan="4" class="text-right"><h5>Subtotal</h5></td>
		<td class="text-right"><h5><strong>{CART_SUBTOTAL}</strong></h5></td>
	</tr>
	<tr>
		<td colspan="4" class="text-right"><h5>Estimated shipping</h5></td>
		<td class="text-right"><h5><strong>{CART_SHIPPINGTOTAL}</strong></h5></td>
	</tr>
	<tr>
		<td colspan="4" class="text-right"><h3>Total</h3></td>
		<td class="text-right"><h3><strong>{CART_GRANDTOTAL}</strong></h3></td>
	</tr>
	<tr>
		<td colspan="2">{CART_CONTINUESHOP}</td>
		<td colspan="3" class="text-right">{CART_CHECKOUT_BUTTON}</td>
	</tr>
	</tbody>
	</table>
';