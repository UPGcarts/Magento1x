<?php

class Upg_Payments_Model_Resource_Message extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('upg_payments/mns_message', 'entity_id');
    }
}