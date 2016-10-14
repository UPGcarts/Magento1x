<?php require_once Mage::getModuleDir('', 'Upg_Payments').'/Api/vendor/autoload.php';

class Upg_Payments_Block_Adminhtml_Form_Field_Riskclass extends Mage_Core_Block_Html_Select
{

    private function _getRiskClasses()
    {
        $riskClass = array(
            array('value' => Upg\Library\Risk\RiskClass::RISK_CLASS_DEFAULT, 'label' => 'Default'),
            array('value' => Upg\Library\Risk\RiskClass::RISK_CLASS_HIGH, 'label' => 'High'),
            array('value' => Upg\Library\Risk\RiskClass::RISK_CLASS_TRUSTED, 'label' => 'Trusted'),
        );

        return $riskClass;
    }

    public function _toHtml()
    {
        $helper = Mage::helper('upg_payments');
        $this->setName($this->getInputName());
        if (!$this->getOptions()) {
            foreach ($this->_getRiskClasses() as $option) {
                $this->addOption($option['value'], $helper->__(addslashes($option['label'])));
            }
        }
        return parent::_toHtml();
    }
}
