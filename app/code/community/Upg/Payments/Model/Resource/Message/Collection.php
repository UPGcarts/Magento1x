<?php

class Upg_Payments_Model_Resource_Message_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('upg_payments/message');
        parent::_construct();
    }
}