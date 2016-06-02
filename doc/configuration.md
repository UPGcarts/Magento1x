# Configuration

## Payment Gateway settings
Your Gateway provider will need to know the following urls for both callbacks in the checkout process and asynchronous transaction status notifications. The two urls are as follows:
* (http, https)://Domain to your magento store/sub directory if required/paymentmodule/callback/handle
* (http, https)://Domain to your magento store/sub directory if required/paymentmodule/mns/handle

For examples of some valid urls:

* http://somedoamain.com/paymentmodule/callback/handle
* http://somedoamain.com/paymentmodule/mns/handle
* http://somedoamain.com/subdirector/paymentmodule/callback/handle
* http://somedoamain.com/subdirector/paymentmodule/mns/handle

## Admin Settings
To configure your module please go to System->Configuration->Sales->Name of the gateway provider in your Magento admin. You will see several sections however a quick description of the sections follows:

* Global Settings : Global settings provided by your provider
* Store Settings : Settings for stores mainly what currency should be mapped to which Payment Gateway store
* B2B settings : Settings for b2b transactions done by the module
* Logging and Debug : Settings for logging and debug settings
* Customer Settings : Customer and customer group specific settings in relation to the payment module

## Admin Settings Section details

### Global Settings

* Enabled : Should the payment module be enabled. If disabled the payment method will not appear in the checkout.
* Payment Method Name : Name of the payment module in the checkout process
* Payment Method Description : Description of the payment module shown to the customer when the payment module is selected at checkout
* Upload File : Upload a image logo to show when users selects the payment option
* Merchant ID : Merchant Id provided by the payment gateway provider
* Merchant Key : Merchant key provided by the payment gateway provider
* Environment Mode : Select whether you want to use the test environment or the live environment.
* Auto Capture : Select whether transactions should be captured automatically.
	Autocapture off: Each payment by customers has to be captured manually. This is usually done when the order is getting shipped. This is important for certain payment methods that require the payment provider to know when exactly the products were shipped. Dunning procedures depend on this for example.
	Autocapture on: Each payment by customers will be captured automatically by the payment provider as soon as the funds are available.
* Risk Class : If not specified for users and/or products, the payment provider will use this risk class by default for all transactions. 
	Trusted: No solvency checks will be done. The customer will always be able to select every payment method.
	Default: Solvency checks will be executed, depending on the contract with your payment provider. Depending on the outcome the customer may be classified as high risk user and will only be able to use secure payment methods.
	High: All customers are treated as high risk users by default and will only be able to use secure payment methods.
	It is recommended to use 'Default'.
* Use Product Risk Class : Send the product risk class with each request. If this setting is enabled it means the product risk class has to be set for all products. By default all products are treated as 'Trusted'.

### Store Settings

* Currency to Store ID Mapping : Map a given currency to a store id provided by your gateway provider. Each store ID is bound to one currency. If you support multiple currencies please make sure to match the correct store ID to their respective currency.

### B2B settings

* Enable B2B transaction : Enable the transfer of business customer data for solvency checks. A customer is considered a business customer as long as he provides at least a company name and a company registration Id.
* Show extended B2B fields : Show extended B2B fields in checkout and customer account section. If you enable this the customer may provide a company registration Id in the registration form.
* Show Company Registration type : Show the company registration type field in checkout and customer account section.

### Logging and Debug

* Logging Toggle : Should logging for payment module be enabled. Log files will be created in the directory /var/log/ of your Magento installation.
* Logging File : Name of file to log module info.
* API Logging File : Name of the file to log API calls to for debug purposes.
* Log Level : Logging level for both files.

### Customer Settings

* Customer Risk class mapping : Map a customer group to a different risk class. This setting will override the default Risk Class.