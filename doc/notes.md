# Notes

This module has some customisations and overwrites which integrators may need to be aware of. 

Firstly this module has been confirmed to work with the Magento checkout. Other checkouts such as One Page checkout is not supported at the moment however this is on the road map. We would also advise that the module is installed on a internet facing staging/development server to ensure functionality is working and to check if the B2B fields will show with your template if you intend to use the B2B mode.

# B2B fields

The module introduces some custom fields on the checkout and customer account area if the B2B fields is configured to be displayed.
However if you enabled the fields to show and they are not appearing then you may have custom templates and you will need to amend these templates.

## Checkout

Please see the following file app/design/frontend/base/default/template/upg/checkout/onepage/billing.phtml for the new fields. However here is a top line of what is needed:

* Locate the template for checkout/onepage/billing.phtml that is in use by your store
* Then add the following near the top
```php
    $paymentHelper = Mage::helper('upg_payments');
```
* Then in the appropriate position under the company name field add the following code under the code field in the template:
```php
    <?php if($paymentHelper->getB2bEnabled() && $paymentHelper->getShowExtendedB2bOnCheckout()): ?>
        <?php if($paymentHelper->getB2bShowRegistrationType()): ?>
            <div class="field">
                <label for="upg_company_registration_id"><?php echo $paymentHelper->__('Company Registration Id') ?></label>
                <div class="input-box">
                    <input type="text" name="billing[upg_company_registration_id]" id="billing:upg_company_registration_id" title="<?php echo $paymentHelper->__('Company Registration Id') ?>" value="<?php echo $this->escapeHtml($this->getAddress()->getUpgCompanyRegistrationId()) ?>" class="input-text <?php echo $this->helper('customer/address')->getAttributeValidationClass('upg_company_registration_id') ?>" />
                </div>
            </div>
        <?php endif; ?>
        <div class="field">
            <label for="upg_company_registration_type"><?php echo $paymentHelper->__('Company Registration Type') ?></label>
            <div class="input-box">
                <select name="billing[upg_company_registration_type]" id="billing:upg_company_registration_type">
                    <?php foreach($paymentHelper->getRegistrationTypeOptions() as $value=>$label): ?>
                        <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                    <?php endforeach; ?>
                </select>
                <script type="text/javascript">
                    //<![CDATA[
                    $('upg_company_registration_type').setValue('<?php echo $this->escapeHtml($this->getAddress()->getUpgCompanyRegistrationType()); ?>');
                    //]]>
                </script>
            </div>
        </div>
    <?php endif; ?>
```
* Save the template and ensure the fields are now showing

## Customer account section

Please see the following file app/design/frontend/base/default/template/upg/customer/address/edit.phtml for the new fields. However here is a top line of what is needed:

* Locate the template for /customer/address/edit.phtml that is in use by your store
* Then add the following near the top
```php
    $paymentHelper = Mage::helper('upg_payments');
```
* Then in the appropriate position under the company name field add the following code under the code field in the template:
```php
    <?php if($paymentHelper->getB2bEnabled() && $paymentHelper->getShowExtendedB2bOnCheckout()): ?>
        <?php if($paymentHelper->getB2bShowRegistrationType()): ?>
            <li class="wide">
                <label for="company"><?php echo $paymentHelper->__('Company Registration Id') ?></label>
                <div class="input-box">
                    <input type="text" name="upg_company_registration_id" id="upg_company_registration_id" title="<?php echo $paymentHelper->__('Company Registration Id') ?>" value="<?php echo $this->escapeHtml($this->getAddress()->getUpgCompanyRegistrationId()) ?>" class="input-text <?php echo $this->helper('customer/address')->getAttributeValidationClass('upg_company_registration_id') ?>" />
                </div>
            </li>
        <?php endif; ?>
        <li class="wide">
            <label for="upg_company_registration_type"><?php echo $paymentHelper->__('Company Registration Type') ?></label>
            <div class="input-box">
                <select name="upg_company_registration_type" id="upg_company_registration_type">
                    <?php foreach($paymentHelper->getRegistrationTypeOptions() as $value=>$label): ?>
                        <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                    <?php endforeach; ?>
                </select>
                <script type="text/javascript">
                    //<![CDATA[
                    $('upg_company_registration_type').setValue('<?php echo $this->escapeHtml($this->getAddress()->getUpgCompanyRegistrationType()); ?>');
                    //]]>
                </script>
            </div>
        </li>
    <?php endif; ?>
```
* Save the template and ensure the fields are now showing