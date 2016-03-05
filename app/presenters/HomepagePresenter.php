<?php declare(strict_types = 1);

namespace App\Presenters;

use App\Doctrine\EntityManager;
use App\Invoices\InvoiceItem;
use App\Invoices\PdfProvider;
use App\Invoices\Invoice;
use App\ProductDTO;
use Kdyby\Wkhtmltopdf\Document;
use Nette\Application\UI;

/**
 * @property \stdClass $template
 */
class HomepagePresenter extends \Nette\Application\UI\Presenter
{

	use \App\Invoices\Components\TNewInvoiceForm;

	/**
	 * @var \App\Doctrine\EntityManager
	 */
	private $em;

	/**
	 * @var NULL | Invoice
	 */
	private $actualInvoice;

	/**
	 * @var PdfProvider
	 */
	private $pdfProvider;

	public function __construct(EntityManager $em, PdfProvider $pdfProvider)
	{
		parent::__construct();
		$this->em = $em;
		$this->pdfProvider = $pdfProvider;
	}

	public function renderDefault()
	{
		$qb = $this->em->createQueryBuilder();
		$qb->select('i')->from(\App\Invoices\Invoice::class, 'i');
		$qb->leftJoin('i.invoiceItems', 'ii')->addSelect('ii');
		$qb->leftJoin('i.dodavatel', 'ido')->addSelect('ido');
		$qb->leftJoin('i.odberatel', 'iod')->addSelect('iod');
		$qb->addOrderBy('i.date', 'DESC');
		$qb->addOrderBy('ii.insertedAt', 'ASC');
		$this->template->invoices = $qb->getQuery()->getResult();

		$this->template->pdfProvider = $this->pdfProvider;

		$this->template->totalPrice = $this->em->createQuery('SELECT SUM(i.total) FROM App\Invoices\Invoice i')->getSingleScalarResult();
		$this->template->totalPriceDpp = $this->em->createQuery('SELECT SUM(i.total) + SUM(i.totalDpp) FROM App\Invoices\Invoice i')->getSingleScalarResult();
	}

	public function renderNeplatce($invoiceId)
	{
		/** @var Invoice $invoice */
		$invoice = $this->em->find(Invoice::class, $invoiceId);
		$this->actualInvoice = $invoice;

		$products = [];
		/** @var InvoiceItem $invoiceItem */
		foreach ($invoice->getInvoiceItems() as $invoiceItem) {
			$products[] = new ProductDTO($invoiceItem->getDescription(), $invoiceItem->getPrice());
		}

		$this->template->account = $invoice->getAccount();
		$this->template->date = $invoice->getDate('d. m. Y');
		$this->template->dueDate = $invoice->getDueDate('d. m. Y');
		$this->template->bic = $invoice->getBic();
		$this->template->iban = $invoice->getIban();
		$this->template->invoiceNumber = $invoice->getInvoiceNumber();
		$this->template->products = $products;
		$this->template->total = $invoice->getTotal();
		$this->template->vs = $invoice->getVs();
	}

	public function handleGeneratePdf($invoiceId)
	{
		$document = new Document($this->context->parameters['tempDir']);
		$document->margin = [5, 0, 5, 0];
		//FIXME: neřešit přes odkaz, ale vygenerovat Latte!
		$document->addFile($this->link('//Homepage:neplatce', [$invoiceId]));
		$document->save($this->pdfProvider->getRealInvoicePath($invoiceId));

		$this->redirect('this');
	}

	protected function createComponentNewInvoice()
	{
		return $this->newInvoiceFormFactory->create();
	}

	public function createComponentDodavatel(): \App\Addresses\Components\Address
	{
		return new \App\Addresses\Components\Address($this->actualInvoice->getDodavatel());
	}

	public function createComponentOdberatel(): \App\Addresses\Components\Address
	{
		return (new \App\Addresses\Components\Address($this->actualInvoice->getOdberatel()))->changeType();
	}

}
