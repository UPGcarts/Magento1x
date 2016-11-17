<?php

class Upg_Payments_Model_Totals_Quote_FeeAddedFlag
    extends Mage_Core_Model_Abstract
{
    private $_feeAdded = false;

    // This is called by an observer on the event sales_quote_collect_totals_before
    public function reset()
    {
        $this->_feeAdded = false;
    }

    public function isFeeAdded()
    {
        return $this->_feeAdded;
    }

    public function feeAdded()
    {
        $this->_feeAdded = true;
    }
}