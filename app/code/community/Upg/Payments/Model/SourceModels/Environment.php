<?php

class Upg_Payments_Model_SourceModels_Environment
{
    const LIVE = 'LIVE';
    const TEST = 'TEST';

    public function toOptionArray()
    {
        return array(
            array('value' => self::TEST, 'label' => 'Test'),
            array('value' => self::LIVE, 'label' => 'Live'),
        );
    }
}