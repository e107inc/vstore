<?php
	/**
	 * e107 website system
	 *
	 * Copyright (C) 2008-2021 e107 Inc (e107.org)
	 * Released under the terms and conditions of the
	 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
	 *
	 */

define("LAN_VSTORE_ADMIN_000", "Dashboard");
define("LAN_VSTORE_ADMIN_001", "Sales");
define("LAN_VSTORE_ADMIN_002", "Products");
define("LAN_VSTORE_ADMIN_003", "Add Product");
define("LAN_VSTORE_ADMIN_004", "Product Variations");
define("LAN_VSTORE_ADMIN_005", "Add Product Variations");
define("LAN_VSTORE_ADMIN_006", "Coupons");
define("LAN_VSTORE_ADMIN_007", "Add coupon");
define("LAN_VSTORE_ADMIN_008", "Customers");
define("LAN_VSTORE_ADMIN_009", "Email Templates");
define("LAN_VSTORE_ADMIN_010", "Invoice settings");
define("LAN_VSTORE_ADMIN_011", "Payment Gateways");
define("LAN_VSTORE_ADMIN_012", "CC to yourself");
define("LAN_VSTORE_ADMIN_013", "Dashboard area"); //err. mess
define("LAN_VSTORE_ADMIN_014", "not implemented yet!"); //err mess

//Stats

define("LAN_VSTORE_STAT_001", "Complete");
define("LAN_VSTORE_STAT_002", "Orders last 7 days");
define("LAN_VSTORE_STAT_003", "Gross sales last 7 days");
define("LAN_VSTORE_STAT_004", "Gross sales last 31 days");
//type
//define("LAN_VSTORE_STAT_005", "Payed & Open orders");   toglobal
define("LAN_VSTORE_STAT_006", "Range:");
define("LAN_VSTORE_STAT_007", "From");
define("LAN_VSTORE_STAT_008", "To");
define("LAN_VSTORE_STAT_009", "Day");
define("LAN_VSTORE_STAT_010", "Number of Sales");

 //button text Stats
define("LAN_VSTORE_STAT_DAY", "Yesterday");
define("LAN_VSTORE_STAT_WEEK", "Week");
define("LAN_VSTORE_STAT_MONT", "Month");
define("LAN_VSTORE_STAT_YEAR", "Year");
define("LAN_VSTORE_STAT_WARN", "No data for chart available!");


// sales
define("LAN_VSTORE_SALES_001", "Based on variatons");
define("LAN_VSTORE_SALES_002", "Pay Status");
define("LAN_VSTORE_SALES_003", "Refund date");
define("LAN_VSTORE_SALES_004", "Invoice Nr");
define("LAN_VSTORE_SALES_005", "Billing to");
define("LAN_VSTORE_SALES_006", "Ship to");
//define("LAN_VSTORE_SALES_007", "");
define("LAN_VSTORE_SALES_008", "Gateway");
define("LAN_VSTORE_SALES_009", "TransID");
define("LAN_VSTORE_SALES_010", "Invoice successfully created!");
define("LAN_VSTORE_SALES_011", "Invoice couldn\'t be created!");
define("LAN_VSTORE_SALES_012", "Currency");
define("LAN_VSTORE_SALES_013", "Notes");
define("LAN_VSTORE_SALES_014", "Rawdata");
define("LAN_VSTORE_SALES_015", "Order could\'t be refunded!");
define("LAN_VSTORE_SALES_016", "Click to open/generate the invoice pdf");
define("LAN_VSTORE_SALES_017", "Hold order");//button
define("LAN_VSTORE_SALES_018", "Process order");//button
define("LAN_VSTORE_SALES_019", "");//button
//define("LAN_VSTORE_SALES_020", "Incomplete");//button> not used yet!!
define("LAN_VSTORE_SALES_021", "Unable to update order ref code!");  
define("LAN_VSTORE_SALES_022", "Do you really want to cancel this order?");
define("LAN_VSTORE_SALES_024", "Complete order");
define("LAN_VSTORE_SALES_023", "Refund order");
define("LAN_VSTORE_SALES_025", "Do you really want to refund this order?");
define("LAN_VSTORE_SALES_026", "Unable to save/Update customer data!");
define("LAN_VSTORE_SALES_027", "Check to force the creation of a new invoice pdf during save");
define("LAN_VSTORE_SALES_028", "No data found!");                                                                                              

//products bar texts and notes array descr.
define("LAN_VSTORE_PROD_001", "Inventory");
define("LAN_VSTORE_PROD_002", "Assign userclass");
define("LAN_VSTORE_PROD_003", "Assign userclass to customer on purchase"); //txt
define("LAN_VSTORE_PROD_004", "Images/Videos");
define("LAN_VSTORE_PROD_005", "Variations");
define("LAN_VSTORE_PROD_006", "Reviews");
define("LAN_VSTORE_PROD_007", "Tax Class");
define("LAN_VSTORE_PROD_008", "Weight");
define("LAN_VSTORE_PROD_009", "Detailed Description");
define("LAN_VSTORE_PROD_010", "Variations Inventory");
define("LAN_VSTORE_PROD_011", "Product Options");
define("LAN_VSTORE_PROD_012", "Related");
//define("LAN_VSTORE_PROD_013", "Files");
define("LAN_VSTORE_PROD_014", "External Link");
define("LAN_VSTORE_PROD_015", "Download File");
define("LAN_VSTORE_PROD_016", "Product variations DO track the inventory!");
define("LAN_VSTORE_PROD_017", "Track inventory");
define("LAN_VSTORE_PROD_018", "Attributes");
define("LAN_VSTORE_PROD_019", "Invalid type!");
define("LAN_VSTORE_PROD_020", "Customer data is missing or invalid!");
define("LAN_VSTORE_PROD_021", "Invalid tax class! Please inform the shop administrator!");
define("LAN_VSTORE_PROD_022", "Invalid rate");

//HELP texts  +placeholders etc..
define("LAN_VSTORE_HELP_001", "Enter a name for this category of variations. ");
define("LAN_VSTORE_HELP_002", "Variation name eg Colors");
define("LAN_VSTORE_HELP_003", "Option name ");
define("LAN_VSTORE_HELP_004", "You need to select the Product Variations first!");
define("LAN_VSTORE_HELP_005", "Product options are NOT relevant for inventory tracking");
define("LAN_VSTORE_HELP_006", "Select up to 6 product options.");
define("LAN_VSTORE_HELP_007", "Select up to 2 variations, save product and reopen it to access the Variations Inventory table"); 
define("LAN_VSTORE_HELP_008", "Tab Name:");
define("LAN_VSTORE_HELP_009", "Source:");
define("LAN_VSTORE_HELP_010", "Optional: Enter URL of item on external site. Used only when no inventory is available.");
define("LAN_VSTORE_HELP_011", "Price is always the gross price incl. tax!");
define("LAN_VSTORE_HELP_012", "Enter -1 if this item is always available");
define("LAN_VSTORE_HELP_013", "In case of any Product Variations selected, this setting will ignored! You have to fill out the Variations Inventory instead!");
define("LAN_VSTORE_HELP_014", "No additional field info set");
define("LAN_VSTORE_HELP_015", "Unable to create invoice user folder!");
define("LAN_VSTORE_HELP_016", "Unable to create invoice user folder:");
define("LAN_VSTORE_HELP_017", "PDF plugin not installed!\n");
define("LAN_VSTORE_HELP_018", "This plugin is required to create invoice pdf\'s!\n");
define("LAN_VSTORE_HELP_019", "You can download it from here:");
define("LAN_VSTORE_HELP_020", "Invoice template 'default' not found!'");
define("LAN_VSTORE_HELP_021", "Order in status");
define("LAN_VSTORE_HELP_022", "No order loaded!");
define("LAN_VSTORE_HELP_023", "The VAT-ID is invalid or doesn\'t match the selected country!");
define("LAN_VSTORE_HELP_024", "Invoice not available!");
define("LAN_VSTORE_HELP_025", "Do you really want to set the order on hold");
define("LAN_VSTORE_HELP_026", "Tax classes seam not to be in order!<br>The first 3 must be 'none', 'reduced', 'standard'!<br />Add your country specific classes after them.");
define("LAN_VSTORE_HELP_027", "The inventory is controlled by product variations inventory!");
define("LAN_VSTORE_HELP_028", "Product Variations not found! Maybe they have been deleted in the meanwhile ...");
define("LAN_VSTORE_HELP_029", "Do not select more than 2 variations, as only the first 2 will be stored.<br>
						<b>Be aware, that changing this setting will initialize the Variations Inventory table during save!</b>");
define("LAN_VSTORE_HELP_030", "Userclass defined in store preferences");
define("LAN_VSTORE_HELP_031", "When enabled, the inventory of this product variation will be tracked separately.");
define("LAN_VSTORE_HELP_032", "Enter a name for each variation. Optionally increase or decrease the price of this product variation by a fixed amount (+/-). Or, adjust the price as a percentage (%) of the original price.");
define("LAN_VSTORE_HELP_033", "Who should this category be visible to?");
define("LAN_VSTORE_HELP_034", "The product code.Use only UPPERCASE letters, digits and hyphens");
define("LAN_VSTORE_HELP_035", "Enter the price of the item ");
define("LAN_VSTORE_HELP_036", "(without vat)");
define("LAN_VSTORE_HELP_037", "Enter the cost of shipping this item");
define("LAN_VSTORE_HELP_038", ".e.g. 2.00");
define("LAN_VSTORE_HELP_039", "The weight of the item");
define("LAN_VSTORE_HELP_040", "Enter decimal value only.");
define("LAN_VSTORE_HELP_041", "Enter -1 if this item is always available or the number of units you have in stock");
define("LAN_VSTORE_HELP_042", "Drag images and videos here to display them on the product page.");
define("LAN_VSTORE_HELP_043", "You can select up to 2 variations. Product variations inventory is tracked.");


//COUPON

define("LAN_VSTORE_COUP_003", "Discount type ");
define("LAN_VSTORE_COUP_004", "What kind of discount type will be used for this discount");
define("LAN_VSTORE_COUP_005", "Coupon Code");
define("LAN_VSTORE_COUP_006", "Enter a unique code for this coupon");
define("LAN_VSTORE_COUP_007", "Discount amount");
define("LAN_VSTORE_COUP_008", "Define the discount amount");
define("LAN_VSTORE_COUP_009", "Start date");
define("LAN_VSTORE_COUP_010", "When should the coupon become available?");
define("LAN_VSTORE_COUP_011", "End date");
define("LAN_VSTORE_COUP_012", "When will the coupon become unavailable?");
//define("LAN_VSTORE_COUP_013", "");
define("LAN_VSTORE_COUP_014", "Items this coupon will make use of.");
define("LAN_VSTORE_COUP_015", "Exclude items");
define("LAN_VSTORE_COUP_016", "Items this coupon will never make use of");
define("LAN_VSTORE_COUP_017", "Categories");
define("LAN_VSTORE_COUP_018", "Categories this coupon will be assigned to");
define("LAN_VSTORE_COUP_019", "Exclude categories");
define("LAN_VSTORE_COUP_020", "Categories this coupon will never be assigned to");
define("LAN_VSTORE_COUP_021", "Usage limit per coupon");
define("LAN_VSTORE_COUP_022", "How many times this coupon can be used before it is void. Enter -1 for unlimited usage.");
define("LAN_VSTORE_COUP_023", "Usage limit per user");
define("LAN_VSTORE_COUP_024", "How many times this coupon can be used by an individual user. Enter -1 for unlimited usage.");
define("LAN_VSTORE_COUP_025", "Limit usage to X items");
define("LAN_VSTORE_COUP_026", "The max number of individual items this coupon can apply to when using product discounts. Enter -1 to apply to all qualifying items in cart.");
define("LAN_VSTORE_COUP_027", "Percentage");
define("LAN_VSTORE_COUP_028", "Fixed");
define("LAN_VSTORE_COUP_029", "Enter coupon code without spaces");
define("LAN_VSTORE_COUP_030", "Restrictions");
define("LAN_VSTORE_COUP_031", "Limits");
define("LAN_VSTORE_COUP_032", "Coupon code already exists!");
define("LAN_VSTORE_COUP_033", "Invalid coupon code!");

//prefs
//define("LAN_VSTORE_PREF_001", "How to Order");//global
define("LAN_VSTORE_PREF_002", "Admin Area");
define("LAN_VSTORE_PREF_003", "Check-Out");
define("LAN_VSTORE_PREF_004", "Custom CSS");
define("LAN_VSTORE_PREF_005", "Store Caption");
define("LAN_VSTORE_PREF_006", "Category Caption");
define("LAN_VSTORE_PREF_007", "Out-of-Stock Caption");
define("LAN_VSTORE_PREF_008", "Currency");
define("LAN_VSTORE_PREF_009", "Amount format");
define("LAN_VSTORE_PREF_010", "Select a currency");
define("LAN_VSTORE_PREF_011", "Currency before number");
define("LAN_VSTORE_PREF_012", "Currency after number");
define("LAN_VSTORE_PREF_013", "Select a format to be used to format the amount");
define("LAN_VSTORE_PREF_014", "Select a weight unit");
define("LAN_VSTORE_PREF_015", "Gram");
define("LAN_VSTORE_PREF_016", "Kilogram");
define("LAN_VSTORE_PREF_017", "Pound");
define("LAN_VSTORE_PREF_018", "Ounce");
define("LAN_VSTORE_PREF_019", "Carat");
define("LAN_VSTORE_PREF_020", "Assign userclass to customer on purchase");
define("LAN_VSTORE_PREF_021", "As defined in product");
define("LAN_VSTORE_PREF_022", "Show/hide out-of-stock products");
define("LAN_VSTORE_PREF_023", "Show or hide 'Out-of-Stock' products in products listings");
define("LAN_VSTORE_PREF_024", "HIDE");
define("LAN_VSTORE_PREF_025", "Assign userclass");
define("LAN_VSTORE_PREF_026", "Weight Unit");
define("LAN_VSTORE_PREF_027", "Vstore");
define("LAN_VSTORE_PREF_028", "Product Brands");
define("LAN_VSTORE_PREF_029", "Out of Stock");
define("LAN_VSTORE_PREF_030", "Emails");
define("LAN_VSTORE_PREF_031", "Menu");
//shipping
define("LAN_VSTORE_PREF_032", "Calculate Shipping");
define("LAN_VSTORE_PREF_033", "Including shipping calculation at checkout.");
define("LAN_VSTORE_PREF_034", "Calculation method");
define("LAN_VSTORE_PREF_035", "Define a method to calculate the shipping cost.");
define("LAN_VSTORE_PREF_036", "Sum up shipping cost for all items");
define("LAN_VSTORE_PREF_037", "Sum up shipping cost only for unique items");
define("LAN_VSTORE_PREF_038", "Use settings from staggered shipping costs table");
define("LAN_VSTORE_PREF_039", "Value based on");
define("LAN_VSTORE_PREF_040", "Define which value (subtotal or weight) will be used to calculate shipping costs.");
define("LAN_VSTORE_PREF_041", "Define if the shipping cost are fixed to the specified cost or limited to that value");

define("LAN_VSTORE_PREF_042", "Cart subtotal");
define("LAN_VSTORE_PREF_043", "Cart total weight");
define("LAN_VSTORE_PREF_044", "Fixed shipping costs");
define("LAN_VSTORE_PREF_045", "Up to (max.) shipping costs");
define("LAN_VSTORE_PREF_046", "Cost are");
define("LAN_VSTORE_PREF_047", "Staggered shipping costs");

define("LAN_VSTORE_PREF_048", "Sender Name");
define("LAN_VSTORE_PREF_049", "Sales Department");
define("LAN_VSTORE_PREF_050", "Leave blank to use system default");
define("LAN_VSTORE_PREF_051", "orders@mysite.com");
define("LAN_VSTORE_PREF_052", "Merchant Name/Address");
define("LAN_VSTORE_PREF_053", "My Store Inc. etc.");
define("LAN_VSTORE_PREF_054", "Will be displayed on customer email.");

define("LAN_VSTORE_PREF_055", "Enter how-to-order info.");

define("LAN_VSTORE_PREF_056", "Products per page");
define("LAN_VSTORE_PREF_057", "Categories per page");
define("LAN_VSTORE_PREF_058", "Use this field to enter any vstore related custom css, without the need to edit any source files.'");
define("LAN_VSTORE_PREF_059", "Calculate tax");
define("LAN_VSTORE_PREF_060", "Enable to activate tax calculation.");
define("LAN_VSTORE_PREF_061", "Business country");
define("LAN_VSTORE_PREF_062", "The country where the business is located.");
define("LAN_VSTORE_PREF_063", "Check VAT id online (EU only!)");
define("LAN_VSTORE_PREF_064", "Enable to activate online VAT id checking. (EU only!)");
define("LAN_VSTORE_PREF_065", "Tax classes");
define("LAN_VSTORE_PREF_066", "Additional Fields");
define("LAN_VSTORE_PREF_067", "Value");
define("LAN_VSTORE_PREF_068", "Cost");
define("LAN_VSTORE_PREF_069", "Fieldtype");
define("LAN_VSTORE_PREF_070", "Placeholder");
define("LAN_VSTORE_PREF_071", "Product category");
define("LAN_VSTORE_PREF_072", "Nr. of products");
define("LAN_VSTORE_PREF_073", "The tax classes and default tax value to use with the products.<br />
					Enter tax value as decimal number (e.g. 19% => 0.19)!<br/>
					The classes 'none', 'reduced' and 'standard' can not be removed!");
define("LAN_VSTORE_PREF_074", "Enter thresholds in the first column to set or limit shipping cost based on total order price or weight. Start with the lowest threshold and add more until your last threshold is higher than the maximum price/weight of a typical order. Setting the last threshold too low could result in no shipping cost at all.");
define("LAN_VSTORE_PREF_075", "Enter the number of products to display in the menu.");

// invoice
define("LAN_VSTORE_INV_001", "Create Pdf invoice");
define("LAN_VSTORE_INV_002", "Information section caption");
define("LAN_VSTORE_INV_003", "This is rendered right above the items.");
define("LAN_VSTORE_INV_004", "Invoice number prefix");
define("LAN_VSTORE_INV_005", "Next invoice number");
define("LAN_VSTORE_INV_006", "Invoice date format");
define("LAN_VSTORE_INV_007", "Default payment deadline (days)");
define("LAN_VSTORE_INV_008", "Title of the invoice");
define("LAN_VSTORE_INV_009", "Title of the information block on the top right side of the invoice.");
define("LAN_VSTORE_INV_010", "Hint");
define("LAN_VSTORE_INV_011", "Finishing phrase");
define("LAN_VSTORE_INV_012", "Footer content");
define("LAN_VSTORE_INV_013", "Enable to create the invoiceas pdf (pdf plugin required!)");
define("LAN_VSTORE_INV_014", "Define the prefix for your invoice number. This will be rendered e.g. IN000012");
define("LAN_VSTORE_INV_015", "This enables you to start at a given invoice number, or to step over a number. But it will ALWAYS be bigger than the last invoice number used!");
define("LAN_VSTORE_INV_016", "Pdf creation has been disabled!");
define("LAN_VSTORE_INV_017", "A notice will be added to the invoice, when the invoice should be paid latest.");
define("LAN_VSTORE_INV_018", "This will be rendered on the invoice below the items and can be used to add some information on each invoice.");
define("LAN_VSTORE_INV_019", "This will be rendered on the invoice below the items and the hint.");
define("LAN_VSTORE_INV_020", "These fields will be rendered on the bottom of each page.");
define("LAN_VSTORE_INV_021", "Dateformat used on invoices (date only)");
define("LAN_VSTORE_INV_022", "Date format (date only) used on onvoices. e.g. 05/02/2018");
//payment providers -gateway
define("LAN_VSTORE_PAYP_001", "Paypal Express Payments");
define("LAN_VSTORE_PAYP_002", "Paypal Testmode");
define("LAN_VSTORE_PAYP_003", "Paypal Username");
define("LAN_VSTORE_PAYP_004", "Paypal Password");
define("LAN_VSTORE_PAYP_005", "Paypal Signature");
define("LAN_VSTORE_PAYP_006", "Paypal REST Payments");
define("LAN_VSTORE_PAYP_007", "Paypal REST Testmode");
define("LAN_VSTORE_PAYP_008", "Paypal Client Id");
define("LAN_VSTORE_PAYP_009", "Paypal Secret");
define("LAN_VSTORE_PAYP_010", "Mollie Payments");
define("LAN_VSTORE_PAYP_011", "Mollie Testmode");
define("LAN_VSTORE_PAYP_012", "Mollie Live API key");
define("LAN_VSTORE_PAYP_013", "Get your api keys");
define("LAN_VSTORE_PAYP_014", "Mollie Test API key");
define("LAN_VSTORE_PAYP_015", "Mollie Payment methods");
define("LAN_VSTORE_PAYP_016", "Select at least 1 payment method.
The payment method MUST BE enabled in your Mollie dashoard BEFORE you can use it with vstore!
Be aware, that not all methods support all currencies!");
define("LAN_VSTORE_PAYP_017", "here");
define("LAN_VSTORE_PAYP_018", "Use Mollie Testmode");
define("LAN_VSTORE_PAYP_019", "Use Paypal Sandbox");
 
 
