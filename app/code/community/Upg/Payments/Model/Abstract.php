<?php

abstract class Upg_Payments_Model_Abstract extends Mage_Core_Model_Abstract
{
    const UPG_STATUS_STARTED = "UPG_STATUS_STARTED";
    const UPG_STATUS_RETURNED = "UPG_STATUS_RETURNED";
    const UPG_STATUS_ERROR = "UPG_STATUS_ERROR";
}