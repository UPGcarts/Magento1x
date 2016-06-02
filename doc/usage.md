# Usage

The flow of payments is as follows if autocapture is not enabled.

* Order made
* Merchant logs in to the admin and captures payment
* Merchant sends then clicks on the Send Finish API call once everything has been captured


If autocapture is enabled once the payment notices come thorough an invoice will be automatically generated.

## Manual Capture

* Order made
* Merchant logs in to the admin and goes to Sales->Orders->Given Order
* Once in the view section for order go to Invoices then click on the Invoice button
* Select the items for invoices and ensure the capture type is 'Capture Online'
* Click on the Submit Invoice button

## Refunds

For refunding payments made by this module the refund must be done against a Magento Invoice which is related to a capture. To do this please follows the following steps:

* Merchant logs in to the admin and goes to Sales->Orders->Given Order
* Once in the view section for order go to Invoices then selects the invoice you wish to do the refund against. Please note you can only refund up to the amount of the invoice
* Once in the invoice click the 'Credit Memo' button
* Adjust the quantities of the items as needed please not we do not advise doing amount adjustments in magento
* Click on the 'Refund' button to send the api call

## Cancel a transaction

If autocapture is not enabled and you have not done any capture you are able to send through the cancel call. To do this follow the following steps:

* Merchant logs in to the admin and goes to Sales->Orders->Given Order
* Click on the 'Send Cancel API call' button

## Finish a transaction
If autocapture is not enabled and you have not capture a whole order then to release funds back to the customer you will need to send the finish call. To do this please follow the following steps:

* Merchant logs in to the admin and goes to Sales->Orders->Given Order
* Merchant must have done or do at lest one or more partial captures
* Click on the 'Send Finish API call' button