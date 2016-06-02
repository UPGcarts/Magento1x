<?php

/**
 * Transaction held in DB
 * Class Upg_Payments_Model_Transaction
 */
class Upg_Payments_Model_Transaction extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('upg_payments/transaction');
        parent::_construct();
    }
}