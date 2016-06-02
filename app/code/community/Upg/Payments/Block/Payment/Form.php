<?php require_once Mage::getModuleDir('', 'Upg_Payments').'/Api/vendor/autoload.php';

class Upg_Payments_Block_Payment_Form extends Mage_Payment_Block_Form_Cc
{
    /**
     * Store our saved details
     */
    private $_savedDetails = false;

    /**
     * We can use the same token twice
     * @var bool
     */
    private $token = false;

    private $displayText;
    private $displayImage;

    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate("upg/payment/method/form.phtml");
    }

    public function hasDescription()
    {
        $this->populateConfigText();
        return !empty($this->displayText);
    }

    public function getDescription()
    {
        return $this->populateConfigText();
    }

    public function getImage()
    {
        return Mage::getBaseUrl('media') .'admin-config-uploads/'. $this->populateImage();
    }

    private function populateConfigText()
    {
        if(empty($this->displayText)) {
            $this->displayText = Mage::helper('upg_payments/config')->getCheckoutDescription();
        }

        return $this->displayText;
    }

    public function hasImage()
    {
        $this->populateImage();
        return !empty($this->displayImage);
    }

    private function populateImage()
    {
        if(empty($this->displayImage)) {
            $this->displayImage = Mage::getStoreConfig('payment/upg/option_image');
        }

        return $this->displayImage;
    }
}