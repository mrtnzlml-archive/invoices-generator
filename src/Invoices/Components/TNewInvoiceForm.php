<?php declare(strict_types = 1);

namespace App\Invoices\Components;

trait TNewInvoiceForm
{

    /**
     * @var INewInvoiceFormFactory
     */
    private $newInvoiceFormFactory;

    public function injectNewInvoiceFormFactory(INewInvoiceFormFactory $newInvoiceFormFactory)
    {
        $this->newInvoiceFormFactory = $newInvoiceFormFactory;
    }

}
