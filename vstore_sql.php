CREATE TABLE vstore_customer (
  `cust_id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_userid` int(11) NOT NULL,
  `cust_datestamp` int(13) NOT NULL,
  `cust_prename` varchar(5) NOT NULL,
  `cust_firstname` varchar(50) NOT NULL,
  `cust_lastname` varchar(50) NOT NULL,
  `cust_company` varchar(50) NOT NULL,
  `cust_title` varchar(50) NOT NULL,
  `cust_address` text NOT NULL,
  `cust_city` varchar(100) NOT NULL,
  `cust_state` varchar(50) NOT NULL,
  `cust_postcode` varchar(10) NOT NULL,
  `cust_country` varchar(2) NOT NULL,
  `cust_email` varchar(50) NOT NULL,
  `cust_email2` varchar(50) NOT NULL,
  `cust_phone_day` varchar(20) NOT NULL,
  `cust_phone_night` varchar(20) NOT NULL,
  `cust_comments` text NOT NULL,
  `cust_website` varchar(100) NOT NULL,
  `cust_ip` varchar(50) NOT NULL,
  `cust_assigned_to` int(3) NOT NULL,
  `cust_interested` int(1) NOT NULL,
  `cust_notes` text NOT NULL,
  `cust_refcode` varchar(10) NOT NULL,
  PRIMARY KEY (`cust_id`)
) TYPE=MyISAM;


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


CREATE TABLE vstore_cat (
  `cat_id` int(5) NOT NULL AUTO_INCREMENT,
  `cat_parent` int(5) NOT NULL DEFAULT '0',
  `cat_name` varchar(250) DEFAULT NULL,
  `cat_description` varchar(250) DEFAULT NULL,
  `cat_sef` varchar(127) DEFAULT NULL,
  `cat_image` varchar(250) DEFAULT NULL,
  `cat_info` text,
  `cat_class` varchar(12) NOT NULL DEFAULT '',
  `cat_order` tinyint(3) NOT NULL DEFAULT '0',
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
  `item_link` varchar(255) DEFAULT NULL,
  `item_download` int(4) DEFAULT NULL,
  `item_related` text NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM;


