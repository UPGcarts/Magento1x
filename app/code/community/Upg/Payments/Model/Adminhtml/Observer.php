<?php

class Upg_Payments_Model_Adminhtml_Observer
{
    const GENDER_REQUIRED = 'req';

    public function setGenderRequiredOnEnable(Varien_Event_Observer $event_Observer)
    {
        //Get the setting
        $genderSetting = Mage::getStoreConfig('customer/address/gender_show');

        //Check that the module is enabled
        $module = Mage::getStoreConfig('payment/payco/enabled');

        //If enabled, ensure gender is required in customer/address_options
        if ($module != 1)
        {
            //Payco disabled, no action needed
            return;
        }

        //Set gender as required
        $configModel = Mage::getModel('core/config');

        //TODO: Ensure this affects all store scopes
        $configModel->saveConfig('customer/address/gender_show', self::GENDER_REQUIRED, 'default', 0);
    }

    public function addUpgApiButtons(Varien_Event_Observer $event)
    {
        $block = $event->getBlock();

        if ($block instanceof Mage_Adminhtml_Block_Sales_Order_View) {
            $orderId = Mage::app()->getRequest()->getParam('order_id');

            $message = Mage::helper('upg_payments')->__('Are you sure you want to do this?');
            $block->addButton('upg_finish', array(
                'label'     => Mage::helper('upg_payments')->__('Send Finish API call'),
                'onclick'   => "confirmSetLocation('{$message}', '{$block->getUrl('*/paymentmodule/finish', array('order_id'=>$orderId))}')",
                'class'     => 'go upg-finish-api'
            ));

            $block->addButton('upg_cancel', array(
                'label'     => Mage::helper('upg_payments')->__('Send Cancel API call'),
                'onclick'   => "confirmSetLocation('{$message}', '{$block->getUrl('*/paymentmodule/cancel', array('order_id'=>$orderId))}')",
                'class'     => 'go upg-cancel-api'
            ));
        }
    }
}