<?php

class Upg_Payments_Model_Totals_Quote_Total
    extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);
        $this->_setAmount(0)->_setBaseAmount(0);

        $feeAddedFlag = Mage::getSingleton('upg_payments/totals_quote_feeAddedFlag');

        // Check that address has items.
        // Only addresses with items are included in the grand total
        // so the fee needs to be added to an address with items.
        $addressHasItems = count($this->_getAddressItems($address)) > 0;

        if (! $feeAddedFlag->isFeeAdded() && $addressHasItems) {
            $quote = $address->getQuote();
            $fee = $quote->getData('upg_payments_fee');
            $base_fee = $quote->getData('upg_payments_base_fee');
            if (!is_null($fee) && !is_null($base_fee)) {
                $address->setGrandTotal($address->getGrandTotal() + $fee);
                $address->setBaseGrandTotal($address->getBaseGrandTotal() + $base_fee);
                $address->setData('upg_payments_fee', $fee);
                $feeAddedFlag->feeAdded();
            }
        }

        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $fee = $address->getData('upg_payments_fee');

        if (!is_null($fee) && $fee > 0) {
            $address->addTotal(array(
                'code'=> $this->getCode(),
                'title' => Mage::helper('upg_payments')->__('Payment Fee'),
                'value' => $fee,
            ));
        }

        return $this;
    }
}