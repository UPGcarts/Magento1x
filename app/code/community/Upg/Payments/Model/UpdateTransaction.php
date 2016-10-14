<?php

/**
 * This model represents an updateTransaction
 * Class Upg_Payments_Model_Transaction
 */
class Upg_Payments_Model_UpdateTransaction
    extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('upg_payments/updateTransaction');
        parent::_construct();
    }

    public function process()
    {
        $helper = Mage::helper('upg_payments/transaction');

        $oneMinute = new DateInterval('P0Y0M0DT0H1M0S');
        $oneMinuteAgo = (new DateTime())->sub($oneMinute);

        $jobs = Mage::getModel('upg_payments/updateTransaction')
            ->getCollection()
            ->addFieldToFilter('ut_processed', array('eq' => 0))
            ->addFieldToFilter('ut_error_processing', array('eq' => 0))
            ->addFieldToFilter('ut_timestamp', array('lt' => $oneMinuteAgo->getTimestamp()))
            ->setOrder('ut_timestamp', 'ASC');

        foreach ($jobs as $job) {
            $utInvoiceId = $job->getUtInvoiceId();

            $invoice = Mage::getModel('sales/order_invoice')
                ->getCollection()
                ->addFieldToFilter('upg_payments_ut_invoice_id', array('eq' => $utInvoiceId))
                ->addAttributeToSelect('*')
                ->getFirstItem();

            $invOrder = $invoice->getOrder();
            $invOrderId = $invOrder->getId();
            $jobOrderId = $job->getOrderId();

            if ($invoice->getId() === null) {
                Mage::helper('upg_payments')->log(
                    "Update Transaction failed for job #{$job->getUtId()}" .
                    " - Reason: Invoice id was null. Invoice capture must have failed");
                $job->setUtErrorProcessing(1);
                $job->save();
                continue;
            }

            if ($jobOrderId !== $invOrderId) {
                Mage::helper('upg_payments')->log(
                    "Update Transaction failed for job #{$job->getUtId()}" .
                    " - Reason: Invoice order_id did not match upg_payments_update_transaction order_id");
                $job->setUtErrorProcessing(1);
                $job->save();
                continue;
            }

            try {
                $request = $helper->getUpdateTransaction($invOrder, $invoice, $job->getUpgPaymentsUtInvoiceId());
                $helper->sendUpdateTransaction($invOrder, $request);
            }
            catch (Exception $e) {
                Mage::helper('upg_payments')->log("Update Transaction failed for job #{$job->getUtId()} - Reason: {$e->getMessage()}");
                $job->setUtErrorProcessing(1);
                $job->save();
                continue;
            }

            $job->setUtProcessed(1);
            $job->save();
        }
    }

    public function removeOldPDFs()
    {
        $var = Mage::getBaseDir('var');
        $folderName = 'ut_invoice_pdf';
        $pdfBasePath = $var . DS . $folderName;

        $removeAfterInterval = new DateInterval('P1D');
        $removeAfterDate = (new DateTime())->sub($removeAfterInterval);

        $pdfs = scandir($pdfBasePath);
        if ($pdfs === false) {
            Mage::helper('upg_payments')
                ->log("Error while trying to list all PDFs in $pdfBasePath");
            return;
        }
        $pdfs = array_slice($pdfs, 2);  // remove . and .. files

        $mtime = new DateTime();
        foreach ($pdfs as $pdf) {
            $pdfPath = $pdfBasePath . DS . $pdf;
            if (file_exists($pdfPath)) {
                $mtime->setTimestamp(filemtime($pdfPath));
                if ($mtime < $removeAfterDate) {
                    unlink($pdfPath);
                }
            }
        }
    }
}

