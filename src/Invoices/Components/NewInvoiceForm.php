<?php declare(strict_types = 1);

namespace App\Invoices\Components;

use App\Addresses\Address;
use App\Doctrine\EntityManager;
use App\FormFactory;
use App\Invoices\Invoice;
use App\Invoices\InvoiceItem;
use Nette\Application\UI;

class NewInvoiceForm extends UI\Control
{

	/**
	 * @var FormFactory
	 */
	private $formFactory;

	/**
	 * @var EntityManager
	 */
	private $em;

	public function __construct(FormFactory $formFactory, EntityManager $em)
	{
		parent::__construct();
		$this->formFactory = $formFactory;
		$this->em = $em;
	}

	public function render()
	{
		$this->template->render(__DIR__ . '/NewInvoiceForm.latte');
	}

	/**
	 * @return UI\Form
	 */
	protected function createComponentNewInvoice()
	{
		$form = $this->formFactory->create();

		$addresses = [];
		/** @var Address $address */
		foreach ($this->em->getRepository(Address::class)->findAll() as $address) {
			$addresses[$address->getId()] = $address->getName() . ' (' . $address->getCity() . ', ' . $address->getStreet() . ')';
		}

		$form->addSelect('dodavatel', 'Adresa dodavatele', $addresses)->setDefaultValue(1);
		$form->addSelect('odberatel', 'Adresa odběratele', $addresses)->setDefaultValue(3);
		$date = new \DateTimeImmutable;
		$form->addText('date', 'Datum vystavení')
			->setDisabled() //FIXME
			->setDefaultValue($date->format($format = 'd. m. Y'));
		$form->addText('dueDate', 'Datum splatnosti')
			->setDisabled()
			->setDefaultValue($date->modify('+14 days')->format($format));
		$form->addText('invoiceNumber', 'Číslo faktury')->setRequired(); //TODO: musí se dělat automaticky
		$form->addText('dpp', 'DPP mimo fakturu:'); //TODO: dodělat

		$form->addSubmit('send', 'Odeslat');
		$form->onSuccess[] = function (UI\Form $form, $values) {

			$invoice = new Invoice($values->invoiceNumber);
			$itemPrices = $form->getHttpData($form::DATA_LINE, 'itemPrice[]');
			foreach ($form->getHttpData($form::DATA_LINE, 'item[]') as $index => $description) {
				$invoice->addInvoiceItem(new InvoiceItem($description, (float)$itemPrices[$index]));
			}

			$invoice->setDodavatel($this->em->find(Address::class, $values->dodavatel));
			$invoice->setOdberatel($this->em->find(Address::class, $values->odberatel));

			$this->em->persist($invoice);
			$this->em->flush();

			$this->presenter->redirect('default');
		};

		return $form;
	}

}
