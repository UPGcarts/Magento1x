<?php

class Upg_Payments_Helper_Quote extends Mage_Core_Helper_Abstract
{
    /**
     * @param Mage_Sales_Model_Quote $quote
     * @return string
     */
    public function reserveOrderId(Mage_Sales_Model_Quote $quote)
    {
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $quoteId = $quote->getId();
        $quote->setReservedOrderId(null);

        $write->update(
            Mage::getSingleton('core/resource')->getTableName('sales/quote'),
            array('reserved_order_id'=>''),
            "entity_id={$quoteId}"
        );

        $quote->reserveOrderId();
        $quote->save();

        return $quote->getReservedOrderId();
    }
}