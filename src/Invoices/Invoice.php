<?php declare(strict_types = 1);

namespace App\Invoices;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Collections\ReadOnlyCollectionWrapper;

/**
 * @ORM\Entity
 * @ORM\Table(name="invoices")
 */
class Invoice
{

	use \Kdyby\Doctrine\Entities\Attributes\Identifier;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Addresses\Address", cascade={"persist"})
	 * @ORM\JoinColumn(name="dodavatel_id", referencedColumnName="id", nullable=FALSE)
	 * @var \App\Addresses\Address
	 */
	private $dodavatel;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Addresses\Address", cascade={"persist"})
	 * @ORM\JoinColumn(name="odberatel_id", referencedColumnName="id", nullable=FALSE)
	 * @var \App\Addresses\Address
	 */
	private $odberatel;

	/**
	 * @ORM\OneToMany(targetEntity="App\Invoices\InvoiceItem", mappedBy="invoice", cascade={"persist"})
	 * @var \App\Invoices\InvoiceItem[] | \Doctrine\Common\Collections\ArrayCollection
	 */
	private $invoiceItems;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $account = '78-2806390297/0100';

	/**
	 * @ORM\Column(type="datetime")
	 * @var \DateTimeInterface
	 */
	private $date;

	/**
	 * @ORM\Column(type="datetime")
	 * @var \DateTimeInterface
	 */
	private $dueDate;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $bic = 'KOMBCZPPXXX';

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $iban = 'CZ4101000000782806390297';

	/**
	 * @ORM\Column(type="string", unique=TRUE)
	 * @var string
	 */
	private $invoiceNumber;

	/**
	 * @ORM\Column(type="decimal", precision=10, scale=2)
	 * @var float
	 */
	private $total = 0;

	/**
	 * @ORM\Column(type="decimal", precision=10, scale=2, options={"default":10000})
	 * @var float
	 */
	private $totalDpp = 10000;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $vs;

	public function __construct(string $invoiceNumber)
	{
		$this->invoiceNumber = $invoiceNumber;
		$this->vs = strtr($invoiceNumber, ['-' => '']);

		$this->invoiceItems = new \Doctrine\Common\Collections\ArrayCollection;
		$this->date = new \DateTimeImmutable('now');
		$this->dueDate = $this->date->modify('+14 days');
	}

	public function setDodavatel(\App\Addresses\Address $address)
	{
		$this->dodavatel = $address;
	}

	public function getDodavatel(): \App\Addresses\Address
	{
		return $this->dodavatel;
	}

	public function setOdberatel(\App\Addresses\Address $address)
	{
		$this->odberatel = $address;
	}

	public function getOdberatel(): \App\Addresses\Address
	{
		return $this->odberatel;
	}

	public function addInvoiceItem(InvoiceItem $invoiceItem)
	{
		$this->total += $invoiceItem->getPrice();
		$invoiceItem->setInvoice($this);
		$this->invoiceItems->add($invoiceItem);
	}

	public function getInvoiceItems(): ReadOnlyCollectionWrapper
	{
		return new ReadOnlyCollectionWrapper($this->invoiceItems);
	}

	public function getAccount(): string
	{
		return $this->account;
	}

	public function getDate($format = NULL)
	{
		if ($format !== NULL) {
			return $this->date->format($format);
		}
		return $this->date;
	}

	public function getDueDate($format = NULL)
	{
		if ($format !== NULL) {
			return $this->dueDate->format($format);
		}
		return $this->dueDate;
	}

	public function getBic(): string
	{
		return $this->bic;
	}

	public function getIban(): string
	{
		return $this->iban;
	}

	public function getInvoiceNumber(): string
	{
		return $this->invoiceNumber;
	}

	public function getTotal(): float
	{
		return (float)$this->total;
	}

	public function getVs(): string
	{
		return $this->vs;
	}

}
