<?php

class Upg_Payments_Model_SourceModels_UnitsSelect
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'px', 'label' => 'px'),
            array('value' => '%', 'label' => '%')
        );
    }
}