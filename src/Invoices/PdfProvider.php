<?php declare(strict_types = 1);

namespace App\Invoices;

use App\Doctrine\EntityManager;
use Nette\Http\Request;

class PdfProvider
{

	use \Nette\SmartObject;

	private $wwwDir;

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * @var Request
	 */
	private $httpRequest;

	public function __construct($wwwDir, EntityManager $em, Request $httpRequest)
	{
		$this->wwwDir = $wwwDir;
		$this->em = $em;
		$this->httpRequest = $httpRequest;
	}

	public function getPublicInvoicePath($invoiceId)
	{
		/** @var Invoice $invoice */
		$invoice = $this->em->find(Invoice::class, $invoiceId);
		$date = $invoice->getDate('Y');

		$dirPath = 'invoices/' . $date . '/' . $invoice->getInvoiceNumber() . '.pdf';
		$publicFilePath = $this->httpRequest->getUrl()->getBasePath() . $dirPath;

		if (!file_exists($this->getRealInvoicePath($invoiceId))) {
			$publicFilePath = FALSE;
		}
		return $publicFilePath;
	}

	public function getRealInvoicePath($invoiceId): string
	{
		/** @var Invoice $invoice */
		$invoice = $this->em->find(Invoice::class, $invoiceId);
		$date = $invoice->getDate('Y');

		$dirPath = 'invoices/' . $date . '/' . $invoice->getInvoiceNumber() . '.pdf';
		$realPath = $this->wwwDir . '/' . $dirPath;
		return $realPath;
	}

}
