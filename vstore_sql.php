

CREATE TABLE vstore_cart (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_session` varchar(250) DEFAULT NULL,
  `cart_e107_user` varchar(250) DEFAULT NULL,
  `cart_status` varchar(250) DEFAULT NULL,
  `cart_item` int(11) DEFAULT NULL,
  `cart_qty` int(11) DEFAULT NULL,
  `cart_paystat` varchar(250) DEFAULT NULL,
  `cart_paydate` varchar(250) DEFAULT NULL,
  `cart_paytrans` varchar(250) DEFAULT NULL,
  `cart_paygross` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cart_payshipping` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cart_payshipto` text NOT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=MyISAM;



CREATE TABLE vstore_orders (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_date` int(10) DEFAULT NULL,
  `order_session` varchar(250) DEFAULT NULL,
  `order_e107_user` int(6) DEFAULT NULL,
  `order_cust_id` int(6) DEFAULT NULL,
  `order_status` varchar(1) DEFAULT NULL,
  `order_items` text NOT NULL,
  `order_ship_firstname` varchar(100) DEFAULT NULL,
  `order_ship_lastname` varchar(100) DEFAULT NULL,
  `order_ship_email` varchar(100) DEFAULT NULL,
  `order_ship_phone` varchar(100) DEFAULT NULL,
  `order_ship_company` varchar(100) DEFAULT NULL,
  `order_ship_address` varchar(255) DEFAULT NULL,
  `order_ship_city` varchar(100) DEFAULT NULL,
  `order_ship_state` varchar(100) DEFAULT NULL,
  `order_ship_zip` varchar(20) DEFAULT NULL,
  `order_ship_country` varchar(100) DEFAULT NULL,
  `order_ship_notes` text NOT NULL,
  `order_pay_gateway` varchar(50) DEFAULT NULL,
  `order_pay_status` varchar(250) DEFAULT NULL,
  `order_pay_transid` varchar(250) DEFAULT NULL,
  `order_pay_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `order_pay_shipping` decimal(10,2) NOT NULL DEFAULT '0.00',
  `order_pay_rawdata` text NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM;


CREATE TABLE vstore_cat (
  `cat_id` int(5) NOT NULL AUTO_INCREMENT,
  `cat_parent` int(5) NOT NULL DEFAULT '0',
  `cat_name` varchar(250) DEFAULT NULL,
  `cat_description` varchar(250) DEFAULT NULL,
  `cat_sef` varchar(127) DEFAULT NULL,
  `cat_image` varchar(250) DEFAULT NULL,
  `cat_info` text,
  `cat_class` varchar(12) NOT NULL DEFAULT '',
  `cat_order` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM;


CREATE TABLE vstore_items (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` varchar(10) NOT NULL DEFAULT '',
  `item_name` varchar(127) DEFAULT NULL,
  `item_keywords` varchar(127) DEFAULT NULL,
  `item_desc` varchar(250) DEFAULT NULL,
  `item_cat` tinyint(4) DEFAULT NULL,
  `item_pic` text NOT NULL,
  `item_files` text NOT NULL,
  `item_price` decimal(10,2) DEFAULT NULL,
  `item_shipping` decimal(10,2) DEFAULT NULL,
  `item_details` text,
  `item_reviews` text,
  `item_order` tinyint(3) DEFAULT NULL,
  `item_inventory` int(6) DEFAULT NULL,
  `item_vars` varchar(255) DEFAULT NULL,
  `item_link` varchar(255) DEFAULT NULL,
  `item_download` varchar(255) DEFAULT NULL,
  `item_related` text NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM;

CREATE TABLE vstore_items_vars (
 `item_var_id` int(10) NOT NULL AUTO_INCREMENT,
 `item_var_name` varchar(255) NOT NULL DEFAULT '',
 `item_var_info` varchar(255) NOT NULL DEFAULT '',
 `item_var_attributes` text,
 `item_var_compulsory` int(2) NOT NULL DEFAULT '0',
 `item_var_userclass` int(4) NOT NULL,
 PRIMARY KEY (`item_var_id`)
) ENGINE=MyISAM;
