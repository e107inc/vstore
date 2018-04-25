# Invoices

**In order to make the invoices work, you MUST install the PDF plugin!**

You can find the latest version here: https://github.com/e107inc/pdf


## Contents

1. Required settings
2. Other settings
3. Templates
4. Various


## Required settings

A few settings have to be done by the shop admin after installation to make sure the invoices are correctly created.

1. Make sure you have a merchant address entered (Preferences -> Emails -> Merchant Name/Address). This is used on the invoice at various places.

2. Check/Update the content of the footer (Invoice settings -> Footer content -> footer0, footer1, footer2, footer3).  This elements (footer0 to footer3) will be rendered at the footer of your invoice in 4 boxes.  
footer0 contains usually the merchant information. You may have to format it to your liking. Be aware that the space is limited to 5 rows.  
footer1 may contain direct contact information like phone number, email adress or website.  
footer2 can be used to tax information like your VAT-ID (if you're from the EU or your tax code).  
footer 3 can be used to display bank information (e.g. Bank name, Account owner, IBAN, BIC/SWIFT codes).  
Of course, it is up to you (and your local regulations), which information you enter and into which footer box.


## Other settings

This is a brief descriptions of the settings for invoices:

- Title: Is the title used on the invoice. By default it is `INVOICE`.
- Information section caption: This is the caption of the block on the top right, which contains information like Invoice nr., Order-Nr. and so on.
- Subject: This sentence is rendered right below the "Title" and above the list of purchased items. Usually it's something like "This is the invoice vor order #HGJ0981 from 2015-05-15 : 16:20"  
You can use shortcodes like {ORDER_DATA: order_ref} or {ORDER_DATA: order_date} here.
- Invoice number prefix: All invoice nr will start with this prefix. e.g. IN will render as "IN0123456".
- Next invoice number: This shows you the next invoice number that the system will use. But in case you come from a different shop system and do not want to start your invoice nr at 1 you can overwrite it with your desired number.  
**If you enter a number, smaller than current last used invoice number, it will be ignored! And the next invoice nr generated will be in sequence to the last used number!**
- Invoice date format: As you usually do not need the time in your invoice date, you should specify your date format here. Default is `%m/%d/%Y`
- Default payment deadline (days): Defines a number of dates, till when the invoice should be payed. Default is `7`, but you can change it to your liking.  
It is rendered in the "Information section".
- Hint: Here you can enter and format some text that you want to render right below the items list. maybe something like `Please checkout also our other products.` or something like that.
- Finishing phrase: This will be rendered right below the "Hint" and is by default something like  
`Thanks for your business!  
Yours faithfully
<your name>
_____________________________________________`
- Footer content: This define 4 boxes, which will be rendered on the bottom of the page (on every page!).


## Templates

The invoice is based on a template `template_invoice.php` that can be found in the `templates` folder. you can change the template to your liking, but make sure to test the resulting invoice pdf extensingly as the html to pdf converter doesn't support all html5 tags or css rules!


## Various

- Invoices will be created when the order is saved to the database.
- Invoices will be saved to the e107 Media datastore
- Invoices will be attached to emails that are send and are in one of the following states: **N**ew, **C**omplete, **P**rocessing
- Invoices are online accessible via the following scheme: http://example.com/vstore/invoice/<invoice_nr>  
**Only admins and the customer can access the invoice via that link.**
- In case, an invoice must be recreated, you can do this via the admin area:  
Open the order in edit mode, mark the checkbox near to the invoice nr. and click update.  
This will delete the current invoice from the media datastore and create a new one. **It will NOT be automatically send anywhere!**
