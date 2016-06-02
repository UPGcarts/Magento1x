<?php require_once Mage::getModuleDir('', 'Upg_Payments').'/Api/vendor/autoload.php';

use Upg\Library\Risk\RiskClass;

class Upg_Payments_Model_SourceModels_RiskClass extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function toOptionArray()
    {
        return array(
            array('value' => RiskClass::RISK_CLASS_TRUSTED, 'label' => 'Trusted'),
            array('value' => RiskClass::RISK_CLASS_DEFAULT, 'label' => 'Default'),
            array('value' => RiskClass::RISK_CLASS_HIGH, 'label' => 'High'),
        );
    }

    public function getAllOptions()
    {
        if (is_null($this->_options))
        {
            return array(
                array(
                    'label' => Mage::helper('upg_payments')->__('Default'),
                    'value' => RiskClass::RISK_CLASS_DEFAULT
                ),
                array(
                    'label' => Mage::helper('upg_payments')->__('High'),
                    'value' => RiskClass::RISK_CLASS_HIGH
                ),
                array(
                    'label' => Mage::helper('upg_payments')->__('Trusted'),
                    'value' => RiskClass::RISK_CLASS_TRUSTED
                )
            );
        }
        return $this->_options;
    }

    public function getFlatColums()
    {
        $columns = array(
            $this->getAttribute()->getAttributeCode() => array(
                'type' => 'int',
                'unsigned' => true,
                'is_null' => true,
                'default' => RiskClass::RISK_CLASS_DEFAULT,
                'extra' => null
            )
        );
        return $columns;
    }
}