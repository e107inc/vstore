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
								                                <div class="row">
								                                 <!--   <div class="col-md-5"><a class="item-price" href="{ITEM_URL}">{ITEM_PRICE}</a></div>
								                                    <div class="col-md-7 text-left">{ITEM_ADDTOCART: class=btn btn-sm btn-success&class0=btn btn-sm btn-default disabled}</div>-->
																	
																	<div class="col-md-5"><a class="item-price" href="{ITEM_URL}">{ITEM_PRICE}</a><br />
								                                    {ITEM_ADDTOCART: class=btn btn-sm btn-success&class0=btn btn-sm btn-default disabled}</div>
																	
								                                </div>

							                            </div>
						                            </div>
						                            <!--
						                            <div class="ratings">
						                                <p class="pull-right">15 reviews</p>
						                                <p>
						                                    <span class="glyphicon glyphicon-star"></span>
						                                    <span class="glyphicon glyphicon-star"></span>
						                                    <span class="glyphicon glyphicon-star"></span>
						                                    <span class="glyphicon glyphicon-star"></span>
						                                    <span class="glyphicon glyphicon-star"></span>
						                                </p>
						                            </div>
						                            -->
						                        </div>
						                    </div>
						                    ';

$VSTORE_TEMPLATE['list']['end']         = '</div>';




$VSTORE_TEMPLATE['menu']['start'] =  '';
$VSTORE_TEMPLATE['menu']['item'] =  '
			{SETIMAGE: w=320&h=250&crop=1}
			<div class="vstore-product-list col-sm-12 col-lg-12 col-md-12">
	                        <div class="thumbnail">
                            <a href="{ITEM_URL}">{ITEM_PIC}</a>
                            <div class="caption">
                                <h4><a href="{ITEM_URL}">{ITEM_NAME}</a></h4>
                                <p class="item-description">{ITEM_DESCRIPTION}</p>
                                <div class="row">
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
									<p class="lead">{ITEM_PRICE}<!--</p>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">-->
									{ITEM_ADDTOCART}</p>
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
										            <p>
										            '.LAN_VSTORE_011.': {ITEM_CODE}<br />
										            '.LAN_VSTORE_012.': {ITEM_AVAILABILITY}<br /><br />
										            <small class="text-muted">'.LAN_VSTORE_010.'</small>
										            </p>
										            <div class="row">
										                <div class="col-md-6 item-price"><h3>{ITEM_PRICE}</h3></div>
										                <div class="col-md-6">{ITEM_ADDTOCART}</div>
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

