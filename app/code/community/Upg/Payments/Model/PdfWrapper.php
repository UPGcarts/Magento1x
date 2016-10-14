<?php

use Upg\Library\Request\Objects\Attributes\FileInterface;

class Upg_Payments_Model_PdfWrapper
    implements FileInterface
{
    /* @var $pdf Zend_Pdf */
    protected $pdf;
    protected $pdfPath;
    protected $pdfName;
    protected $base64Pdf;
    protected $varFolderName = 'ut_invoice_pdf';

    public function setPdf(Zend_Pdf $pdf)
    {
        $this->pdf = $pdf;
    }

    public function getFileBase64String()
    {
        if (! isset($this->base64Pdf)) {
            $renderedPdf = $this->pdf->render();
            $this->base64Pdf = base64_encode($renderedPdf);
        }
        return $this->base64Pdf;
    }

    public function getPath()
    {
        if (! isset($this->pdfPath)) {
            $this->pdfPath = Mage::getBaseDir('var') . DS . $this->varFolderName;
            if (! file_exists($this->pdfPath)) {
                mkdir($this->pdfPath);
            }
            $this->pdfName = time() . '.pdf';
            $this->pdf->save($this->pdfPath . DS . $this->pdfName);
        }
        return $this->pdfPath . DS . $this->pdfName;
    }
}
