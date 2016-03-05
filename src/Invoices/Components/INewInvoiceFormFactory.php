<?php declare(strict_types = 1);

namespace App\Invoices\Components;

interface INewInvoiceFormFactory
{

    /** @return NewInvoiceForm */
    public function create();

}
