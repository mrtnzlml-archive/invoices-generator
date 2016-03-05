<?php declare(strict_types = 1);

namespace App\Invoices;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="invoice_items")
 */
class InvoiceItem
{

    use \Kdyby\Doctrine\Entities\Attributes\Identifier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Invoices\Invoice", inversedBy="invoiceItems", cascade={"persist"})
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id", nullable=FALSE)
     * @var \App\Invoices\Invoice
     */
    private $invoice;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @var float
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTimeInterface
     */
    private $insertedAt;

    public function __construct(string $description, float $price)
    {
        $this->description = $description;
        $this->price = $price;
        $this->insertedAt = new \DateTimeImmutable;
    }

    public function setInvoice(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return (float)$this->price;
    }

}
