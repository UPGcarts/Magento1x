<?php require_once Mage::getModuleDir('', 'Upg_Payments').'/Api/vendor/autoload.php';

use Upg\Library\Request\Objects\Company;

class Upg_Payments_Model_SourceModels_Registertype extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function toOptionArray()
    {
        return array(
            array('value' => '', 'label'=>Mage::helper('upg_payments')->__('Please Select')),
            array('value' => Company::COMPANY_TYPE_FN, 'label'=>Mage::helper('upg_payments')->__('Commercial register')),
            array('value' => Company::COMPANY_TYPE_HRA, 'label'=>Mage::helper('upg_payments')->__('Trade register department A')),
            array('value' => Company::COMPANY_TYPE_HRB, 'label'=>Mage::helper('upg_payments')->__('Trade register department B')),
            array('value' => Company::COMPANY_TYPE_PARTR, 'label'=>Mage::helper('upg_payments')->__('Partnership register')),
            array('value' => Company::COMPANY_TYPE_GENR, 'label'=>Mage::helper('upg_payments')->__('Cooperative society register')),
            array('value' => Company::COMPANY_TYPE_VERR, 'label'=>Mage::helper('upg_payments')->__('Register of associations')),
            array('value' => Company::COMPANY_TYPE_LUA, 'label'=>Mage::helper('upg_payments')->__('Luxembourg A')),
            array('value' => Company::COMPANY_TYPE_LUB, 'label'=>Mage::helper('upg_payments')->__('Luxembourg B')),
            array('value' => Company::COMPANY_TYPE_LUC, 'label'=>Mage::helper('upg_payments')->__('Luxembourg C')),
            array('value' => Company::COMPANY_TYPE_LUD, 'label'=>Mage::helper('upg_payments')->__('Luxembourg D')),
            array('value' => Company::COMPANY_TYPE_LUE, 'label'=>Mage::helper('upg_payments')->__('Luxembourg E')),
            array('value' => Company::COMPANY_TYPE_LUF, 'label'=>Mage::helper('upg_payments')->__('Luxembourg F')),
        );
    }

    public function getAllOptions()
    {
        return $this->toArray();
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            '0' => Mage::helper('upg_payments')->__('Please Select'),
            Company::COMPANY_TYPE_FN =>Mage::helper('upg_payments')->__('Commercial register'),
            Company::COMPANY_TYPE_HRA =>Mage::helper('upg_payments')->__('Trade register department A'),
            Company::COMPANY_TYPE_HRB =>Mage::helper('upg_payments')->__('Trade register department B'),
            Company::COMPANY_TYPE_PARTR =>Mage::helper('upg_payments')->__('Partnership register'),
            Company::COMPANY_TYPE_GENR =>Mage::helper('upg_payments')->__('Cooperative society register'),
            Company::COMPANY_TYPE_VERR =>Mage::helper('upg_payments')->__('Register of associations'),
            Company::COMPANY_TYPE_LUA =>Mage::helper('upg_payments')->__('Luxembourg A'),
            Company::COMPANY_TYPE_LUB =>Mage::helper('upg_payments')->__('Luxembourg B'),
            Company::COMPANY_TYPE_LUC =>Mage::helper('upg_payments')->__('Luxembourg C'),
            Company::COMPANY_TYPE_LUD =>Mage::helper('upg_payments')->__('Luxembourg D'),
            Company::COMPANY_TYPE_LUE =>Mage::helper('upg_payments')->__('Luxembourg E'),
            Company::COMPANY_TYPE_LUF =>Mage::helper('upg_payments')->__('Luxembourg F'),
        );
    }
}