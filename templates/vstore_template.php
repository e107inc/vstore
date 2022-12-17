<?php

$VSTORE_TEMPLATE = array();


/**
 * Category list
 */
$VSTORE_TEMPLATE['cat']['start']   = '
	<div class="row">
';

$VSTORE_TEMPLATE['cat']['item']   = '
		{SETIMAGE: w=380&h=200&crop=1}
		<div class="vstore-category-list col-sm-4 col-lg-4 col-md-4 mb-4">
			<div class="panel panel-default card">
				<a href="{CAT_URL}">{CAT_PIC: class=img-responsive img-fluid card-img-top}</a>
				<div class="panel-body card-body">
					<div class="vstore-caption text-center">
						<h4 class="card-title"><a href="{CAT_URL}">{CAT_NAME}</a></h4>
						<p class="cat-description"><small>{CAT_DESCRIPTION}</small></p>
						
					</div>
				</div>
			</div>
		</div>
';

$VSTORE_TEMPLATE['cat']['end']   = '
	</div>
';


/**
 * Product list
 */
$VSTORE_TEMPLATE['list']['start']   = '<div class="row"><div class="col-md-12">{CAT_INFO}</div>';
$VSTORE_TEMPLATE['list']['item']    =  '
										{SETIMAGE: w=380&h=380&crop=1}
										<div class="vstore-product-list col-sm-4 col-lg-4 col-md-4 mb-4">
							                        <div class="panel panel-default card">
							                        <a href="{ITEM_URL}">{ITEM_PIC: class=img-responsive img-fluid card-img-top}</a>
								                        <div class="panel-body card-body">
								                            
								                            <div>
								                                <h4 class="clearfix card-title"><a href="{ITEM_URL}">{ITEM_NAME}</a><span class="pull-right"></span></h4>
								                                <p class="item-description clearfix">{ITEM_DESCRIPTION: limit=150}
																</p>

																{ITEM_VARS}
															   
								                                <div class="row">
								                                    <div class="col-md-5"><a class="item-price" href="{ITEM_URL}">{ITEM_PRICE}</a></div>
								                                    <div class="col-md-7 text-right text-end">{ITEM_ADDTOCART: class=btn btn-sm btn-success vstore-add&class0=btn btn-sm btn-default btn-secondary disabled vstore-add}</div>
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

$VSTORE_TEMPLATE['menu']['item'] =  '
			{SETIMAGE: w=100&h=100&crop=1}
			<div class="vstore-product-list">
				<div class="thumbnail" style="height:auto;">
					<div class="vstore-caption">
						<div class="row">
							<a href="{ITEM_URL}" class="col-xs-4">{ITEM_PIC}</a>
							<h4 class="col-xs-8"><a href="{ITEM_URL}">{ITEM_NAME}</a></h4>
						</div>
						<!-- <p class="item-description">{ITEM_DESCRIPTION}</p> -->
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<p class="lead">{CART_PRICE}</p>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right text-end">
								{ITEM_ADDTOCART: class=btn btn-sm btn-success vstore-add&class0=btn btn-sm btn-default btn-secondary disabled}
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
													<div class="row row-cols-2 mt-3 thumbnails">
														
														{ITEM_PIC: w=200&h=200&crop=1&item=0&link=1&class=thumbnail img-responsive img-fluid}
														{ITEM_PIC: w=200&h=200&crop=1&item=1&link=1&class=thumbnail img-responsive img-fluid}
														
												
														
														{ITEM_PIC: w=200&h=200&crop=1&item=2&link=1&class=thumbnail img-responsive img-fluid}
														{ITEM_PIC: w=200&h=200&crop=1&item=3&link=1&class=thumbnail img-responsive img-fluid}
														
														
														
														{ITEM_PIC: w=200&h=200&crop=1&item=4&link=1&class=thumbnail img-responsive img-fluid}
														{ITEM_PIC: w=200&h=200&crop=1&item=5&link=1&class=thumbnail img-responsive img-fluid}
														
													
														
														{ITEM_PIC: w=200&h=200&crop=1&item=6&link=1&class=thumbnail img-responsive img-fluid}
														{ITEM_PIC: w=200&h=200&crop=1&item=7&link=1&class=thumbnail img-responsive img-fluid}
														
													</div>
										        </div>
										        <div class="col-md-6">
										            <h3>{ITEM_NAME}</h3>

													<p>{ITEM_DESCRIPTION}</p>

													<p>{ITEM_VARS}</p>
												
													<p>{ITEM_WEIGHT}</p>
												
													<p>
										            Product Code: {ITEM_CODE}<br />
										            Availability: {ITEM_AVAILABILITY}<br /><br />
										            <small class="text-muted">Price may change due to exchange rate.</small>
										            </p>
										            <div class="row">
										                <div class="col-md-6 item-price"><h3>{ITEM_PRICE}</h3></div>
										                <div class="col-md-6 text-right text-end">{ITEM_ADDTOCART: class=btn btn-success vstore-add&class0=btn btn-sm btn-default btn-secondary disabled}</div>
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



$VSTORE_WRAPPER['item']['ITEM_PIC: w=200&h=200&crop=1&item=0&link=1&class=thumbnail img-responsive img-fluid'] = "<div class='col-md-3 mb-2'>{---}</div>";
$VSTORE_WRAPPER['item']['ITEM_PIC: w=200&h=200&crop=1&item=1&link=1&class=thumbnail img-responsive img-fluid'] = "<div class='col-md-3 mb-2'>{---}</div>";
$VSTORE_WRAPPER['item']['ITEM_PIC: w=200&h=200&crop=1&item=2&link=1&class=thumbnail img-responsive img-fluid'] = "<div class='col-md-3 mb-2'>{---}</div>";
$VSTORE_WRAPPER['item']['ITEM_PIC: w=200&h=200&crop=1&item=3&link=1&class=thumbnail img-responsive img-fluid'] = "<div class='col-md-3 mb-2'>{---}</div>";
$VSTORE_WRAPPER['item']['ITEM_PIC: w=200&h=200&crop=1&item=4&link=1&class=thumbnail img-responsive img-fluid'] = "<div class='col-md-3 mb-2'>{---}</div>";
$VSTORE_WRAPPER['item']['ITEM_PIC: w=200&h=200&crop=1&item=5&link=1&class=thumbnail img-responsive img-fluid'] = "<div class='col-md-3 mb-2'>{---}</div>";
$VSTORE_WRAPPER['item']['ITEM_PIC: w=200&h=200&crop=1&item=6&link=1&class=thumbnail img-responsive img-fluid'] = "<div class='col-md-3 mb-2'>{---}</div>";
$VSTORE_WRAPPER['item']['ITEM_PIC: w=200&h=200&crop=1&item=7&link=1&class=thumbnail img-responsive img-fluid'] = "<div class='col-md-3 mb-2'>{---}</div>";


/**
 * Order summary and confirmation page
 */
$VSTORE_TEMPLATE['orderconfirm']['main'] = '
		<h3>Summary</h3>
		<div class="row">
			<div class="col-12 col-xs-12 col-sm-5 col-md-5">
				
				{CONFIRM_FIELD: billing_address}
				
				{CONFIRM_FIELD: shipping_address}

				<h4>Selected payment method</h4>
				<p>{ORDER_GATEWAY_ICON} {ORDER_GATEWAY_TITLE}</p>
			</div>

			<div class="col-12 col-xs-12 col-sm-7 col-md-7">
				<h4>Items</h4>
				{CONFIRM_ITEMS}

				<h4>Order notes</h4>
				{SHIPPING_FIELD: ship_notes}
			</div>
		</div>
		<hr />
		<div class="row">
			<div class="col-12 col-xs-12">
				<a class="btn btn-default btn-secondary btn-secondary vstore-btn-back-confirm" href="{ORDER_CHECKOUT_URL}">&laquo; Back</a>
				<button class="btn btn-primary vstore-btn-buy-now pull-right float-right float-end" type="submit" name="mode" value="confirmed">{ORDER_GATEWAY_ICON: size=1x} Buy now!</button>
			</div>
		</div>
		';

$VSTORE_TEMPLATE['orderconfirm']['billing'] = '
			<h4>{CONFIRM_FIELD: billing_title}</h4>

			<p>{CONFIRM_FIELD: cust_title} {CONFIRM_FIELD: cust_firstname} {CONFIRM_FIELD: cust_lastname}</p>
			<p>{CONFIRM_FIELD: cust_company}</p>
			<p>{CONFIRM_FIELD: cust_vat_id}</p>
			<p>{CONFIRM_FIELD: cust_taxcode}</p>
			<p>{CONFIRM_FIELD: cust_address}</p>
			<p>{CONFIRM_FIELD: cust_city}, {CONFIRM_FIELD: cust_state} {CONFIRM_FIELD: cust_zip}</p>
			<p>{CONFIRM_FIELD: cust_country}</p>
			<br />
			';

$VSTORE_TEMPLATE['orderconfirm']['shipping'] = '
			<h4>Shipping address</h4>

			<p>{CONFIRM_FIELD: ship_firstname} {CONFIRM_FIELD: ship_lastname}</p>
			<p>{CONFIRM_FIELD: ship_company}</p>
			<p>{CONFIRM_FIELD: ship_address}</p>
			<p>{CONFIRM_FIELD: ship_city}, {CONFIRM_FIELD: ship_state} {CONFIRM_FIELD: ship_zip}</p>
			<p>{CONFIRM_FIELD: ship_country}</p>
			<br />
			';

/**
 * Order items list
 * Used in emails and on order summary and confirmation
 */		

$VSTORE_TEMPLATE['confirm_items']['header'] = '
<table class="table table-bordered">
<colgroup>	
	<col style="width:50%" />
	<col  />
	<col  />
	<col  />
</colgroup>
<tr>
	<th>Description</th>
	<th class="text-right text-end">Unit Price</th>
	<th class="text-right text-end">Qty</th>
	<th class="text-right text-end">Amount</th>
</tr>
';

$VSTORE_TEMPLATE['confirm_items']['row'] = '
<tr>
	<td>{CONFIRM_DATA: name}</td>
	<td class="text-right text-end">{CONFIRM_DATA: price}</td>
	<td class="text-right text-end">{CONFIRM_DATA: quantity}</td>
	<td class="text-right text-end">{CONFIRM_DATA: item_total}</tdclass>
</tr>';

$VSTORE_TEMPLATE['confirm_items']['footer'] = '
<tr>
	<td colspan="3"><b>Subtotal</b></td>
	<td class="text-right text-end">{CONFIRM_DATA: sub_total}</td>
</tr>
<tr>
	<td colspan="3"><b>Shipping</b></td>
	<td class="text-right text-end">{CONFIRM_DATA: shipping_total}</td>
</tr>
{CONFIRM_COUPON}
{CONFIRM_TAX}
<tr>
	<td colspan="3"><b>Total</b></td>
	<td class="text-right text-end"><b>{CONFIRM_DATA: grand_total}</b></td>
</tr>
</table>
';

$VSTORE_TEMPLATE['confirm_items']['coupon'] = '
<tr>
	<td colspan="3"><b>Coupon:</b> {CONFIRM_DATA: coupon}</td>
	<td class="text-right text-end">{CONFIRM_DATA: coupon_amount}</td>
</tr>
';


$VSTORE_TEMPLATE['confirm_items']['tax'] = '
<tr>
	<td colspan="2"><b>Tax</b></td>
	<td class="text-right text-end">[x]</td>
	<td class="text-right text-end">[y]</td>
</tr>
';

/**
 * Order items list
 * Used in emails and on order summary and confirmation
 */		

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
	<th class="text-right text-end">Unit Price</th>
	<th class="text-right text-end">Qty</th>
	<th class="text-right text-end">Amount</th>
</tr>
';

$VSTORE_TEMPLATE['order_items']['row'] = '
<tr>
	<td>{CART_DATA: name}</td>
	<td class="text-right text-end">{CART_DATA: price}</td>
	<td class="text-right text-end">{CART_DATA: quantity}</td>
	<td class="text-right text-end">{CART_DATA: item_total}</tdclass>
</tr>';

$VSTORE_TEMPLATE['order_items']['footer'] = '
<tr>
	<td colspan="3"><b>Subtotal</b></td>
	<td class="text-right text-end">{CART_DATA: sub_total}</td>
</tr>
<tr>
	<td colspan="3"><b>Shipping</b></td>
	<td class="text-right text-end">{CART_DATA: shipping_total}</td>
</tr>
{ORDER_COUPON}
{ORDER_TAX}
<tr>
	<td colspan="3"><b>Total</b></td>
	<td class="text-right text-end">{CART_DATA: grand_total}</td>
</tr>
</table>
';

$VSTORE_TEMPLATE['order_items']['coupon'] = '
<tr>
	<td colspan="3" class="text-right text-end">Coupon: <b>{CART_DATA: coupon}</b></td>
	<td class="text-right">{CART_DATA: coupon_amount}</td>
</tr>
';


$VSTORE_TEMPLATE['order_items']['tax'] = '
<tr>
	<td colspan="2"><b>Tax</b></td>
	<td class="text-right text-end">[x]</td>
	<td class="text-right text-end">[y]</td>
</tr>
';

/**
 * Shipping details form
 */
$VSTORE_TEMPLATE['shipping']['header'] = '
	<h3>Shipping Details</h3>

	<div class="row g-3">
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_firstname" class="form-label required">First Name</label>
				{SHIPPING_FIELD: ship_firstname}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_lastname" class="form-label required">Last Name</label>
				{SHIPPING_FIELD: ship_lastname}
			</div>
		</div>

		<div class="col col-xs-12">
			<div class="form-group">
				<label for="ship_company" class="form-label">Company</label>
				{SHIPPING_FIELD: ship_company}
			</div>
		</div>

		<div class="col col-xs-12">
			<div class="form-group">
				<label for="ship_address" class="form-label required">Address</label>
				{SHIPPING_FIELD: ship_address}
			</div>
		</div>

		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_city" class="form-label required">Town/City</label>
				{SHIPPING_FIELD: ship_city}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_state" class="form-label required">State/Region</label>
				{SHIPPING_FIELD: ship_state}
			</div>
		</div>

		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_zip" class="form-label required">Zip/Postcode</label>
				{SHIPPING_FIELD: ship_zip}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="ship_country" class="form-label required">Country</label>
				{SHIPPING_FIELD: ship_country}
			</div>
		</div>

		<div class="col col-xs-12">
			<div class="form-group">
				<label for="ship_phone" class="form-label">Phone number</label>
				{SHIPPING_FIELD: ship_phone}
			</div>
		</div>

		<div class="col-12 col-xs-12 mt-3">
			<div class="form-group">
				<label class="required"></label> Required field
			</div>
		</div>
	</div>
	';



/**
 * Customer details form
 * @todo make VAT and Tax code optional by using wrappers and prefs. 
 */
$VSTORE_TEMPLATE['customer']['header'] = '
	<h3>Billing address</h3>

	<div class="row g-3">
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_firstname" class="form-label required">First Name</label>
				{CUSTOMER_FIELD: cust_firstname}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_lastname" class="form-label required">Last Name</label>
				{CUSTOMER_FIELD: cust_lastname}
			</div>
		</div>

		<div class="col col-xs-12">
			<div class="form-group">
				<label for="cust_company" class="form-label">Company</label>
				{CUSTOMER_FIELD: cust_company}
			</div>
		</div>

		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_vat_id" class="form-label">VAT ID</label>
				{CUSTOMER_FIELD: cust_vat_id}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_taxcode" class="form-label">Tax code</label>
				{CUSTOMER_FIELD: cust_taxcode}
			</div>
		</div>

		<div class="col col-xs-12">
			<div class="form-group">
				<label for="cust_address" class="form-label required">Address</label>
				{CUSTOMER_FIELD: cust_address}
			</div>
		</div>

		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_city" class="form-label required">Town/City</label>
				{CUSTOMER_FIELD: cust_city}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_state" class="form-label">State/Region</label>
				{CUSTOMER_FIELD: cust_state}
			</div>
		</div>

		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_zip" class="form-label required">Zip/Postcode</label>
				{CUSTOMER_FIELD: cust_zip}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_country" class="form-label required">Country</label>
				{CUSTOMER_FIELD: cust_country}
			</div>
		</div>

		<div class="col-12 col-xs-12">
			<div class="form-group">
				<label for="cust_email" class="form-label required">Email address</label>
				{CUSTOMER_FIELD: cust_email}
			</div>
		</div>

		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_phone" class="form-label">Phone number</label>
				{CUSTOMER_FIELD: cust_phone}
			</div>
		</div>
		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				<label for="cust_email" class="form-label">Fax number</label>
				{CUSTOMER_FIELD: cust_fax}
			</div>
		</div>


	{CUSTOMER_FIELD: add_field0}

	{CUSTOMER_FIELD: add_field1}
	
	{CUSTOMER_FIELD: add_field2}
	
	{CUSTOMER_FIELD: add_field3}


		<div class="col-12 col-xs-12">
			<div class="form-group">
				<label class="required"></label> Required field
			</div>
		</div>
	</div>
';

$VSTORE_TEMPLATE['customer']['additional']['item'] = '

		<div class="col-12 col-xs-12 col-sm-6">
			<div class="form-group">
				{CUSTOMER_ADD_LABEL}
				{CUSTOMER_ADD_FIELD}
			</div>
		</div>

';

$VSTORE_TEMPLATE['customer']['guest'] = '
	<div class="row">
		<div class="col-12 col-xs-12 col-sm-4">
			<h4>I\'m a new customer</h4>

			<p>By signing up on our site, you are able to order quicker, 
			know always the state of your orders and have always an 
			up-to-date overview about your orders.
			</p>

			<div class="form-group">
				<a class="btn btn-primary btn-block" id="register" href="{SITEURL}signup.php" title="Sign up">Sign up</a>
			</div>
		</div>

		<div class="col-12 col-xs-12 col-sm-4">
			<h4>Order as guest</h4>

			<p>When ordering as guest, no user account will be created.<br/>
			That means, in case of another order, you will have to enter
			all information once again.
			</p>

			<div class="form-group">
				<button name="as_guest" class="btn btn-default btn-secondary btn-block" type="submit" value="guest">Order as guest</button>
			</div>
		</div>
	
		<div class="col-12 col-xs-12 col-sm-4">
			<h4>I\'ve got a useraccount</h4>
			
			<p>Let me login...</p>
			<form method="post" onsubmit="hashLoginPassword(this);return true" accept-charset="UTF-8">
			<div class="form-group">
				<input type="text" name="username" id="username" class="form-control input-sm tbox login user" placeholder="Username or email address" value="" maxlength="100">
			</div>
			<div class="form-group">
				<input type="password" name="userpass" id="userpass" class="form-control input-sm tbox login pass" placeholder="Password" size="15" value="" maxlength="30">
			</div>
			<div class="form-group">
				<button name="userlogin" class="btn btn-default btn-secondary btn-block" type="submit">Login</button>
			</div>
			</form>
		</div>

	</div>
';



/**
 * Nav menu shopping cart
 */
$VSTORE_TEMPLATE['navcart']['empty'] = '
		<div id="vstore-cart-dropdown-empty" class="alert alert-info">
			Your cart is empty.
			<br/>
			<a class="alert-link" href="{CART_DATA: index_url}">Start Shopping</a>
		</div>
		<div>
			<a class="btn btn-block btn-default btn-secondary col-xs-6" href="{CART_DATA: dashboard_url}"><i class="fa fa-tachometer" aria-hidden="true"></i> My Dashboard</a>
		</div>
';

$VSTORE_TEMPLATE['navcart']['header'] = '
		<div class="form-group alert alert-info" style="max-height: 400px;overflow-y:auto;">
        	<ul class="media-list list-unstyled">
';

$VSTORE_TEMPLATE['navcart']['item'] = '
				<li class="media d-flex">
					<div class="media-left pull-left mr-2 me-2">{CART_DATA: pic}</div>
					<div class="media-body">{CART_DATA: name}<br />
						<span class="pull-right float-right float-end">{CART_DATA: quantity} &Cross; {CART_DATA: price}</span>
					</div>
				</li>
			';

$VSTORE_TEMPLATE['navcart']['footer'] = '
				<li class="media" style="font-size: 1.2em;">
					<span class="pull-right float-right float-end">{CART_DATA: grand_total}</span>
					<span class="">Subtotal:</span>
				</li>
			</ul>
			<input type="hidden" id="vstore-item-count" value="{CART_DATA: item_count}"/>
		</div>

		<div class="d-grid gap-2">
			<a class="btn btn-block btn-danger" href="#" onclick="vstoreCartReset()"><i class="fa fa-trash-o" aria-hidden="true"></i> Clear cart</a>
			<a class="btn btn-block btn-primary col-xs-6" href="{CART_DATA: cart_url}"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Checkout</a>
			<a class="btn btn-block btn-default btn-secondary col-xs-6" href="{CART_DATA: dashboard_url}"><i class="fa fa-tachometer" aria-hidden="true"></i> My Dashboard</a>
		</div>
';

$VSTORE_TEMPLATE['navcart']['start'] = '
	<div id="vstore-cart-dropdown" class="dropdown-menu">
';

$VSTORE_TEMPLATE['navcart']['end'] = '
	</div>
';
