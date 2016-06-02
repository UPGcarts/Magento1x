<?php

class Upg_Payments_Adminhtml_PaymentmoduleController extends Mage_Adminhtml_Controller_Action
{
    private function getOrder($orderId)
    {
        $order = Mage::getModel('sales/order')->load($orderId);
        if($order->getId) {
            throw new Exception('Could not find the order');
        }
        return $order;
    }

    public function finishAction()
    {
        try {
            $order = $this->getOrder($this->getRequest()->getParam('order_id'));
            $request = Mage::helper('upg_payments/transaction')->getFinishTransaction($order);
            $response = Mage::helper('upg_payments/transaction')->sendFinishTransaction($request,$order);
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('upg_payments')->__('Finish call sent')
            );
        }catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('upg_payments')->__('Got a API finish error %s', $e->getMessage())
            );
        }

        $this->_redirect('*/sales_order/view', array('order_id' => $this->getRequest()->getParam('order_id')));
        return $this;

    }

    public function cancelAction()
    {
        try {
            $order = $this->getOrder($this->getRequest()->getParam('order_id'));
            $request = Mage::helper('upg_payments/transaction')->getCancelTransaction($order);
            $response = Mage::helper('upg_payments/transaction')->sendCancelTransaction($request,$order);
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('upg_payments')->__('Cancel call sent')
            );
        }catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('upg_payments')->__('Got a API cancel error %s', $e->getMessage())
            );
        }

        $this->_redirect('*/sales_order/view', array('order_id' => $this->getRequest()->getParam('order_id')));
        return $this;
    }
}