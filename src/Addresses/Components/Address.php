<?php declare(strict_types = 1);

namespace App\Addresses\Components;

use App\AddressDTO;
use Nette\Application\UI;

/**
 * @property \stdClass $template
 */
class Address extends UI\Control
{

	private $type = 'dodavatel';

	/**
	 * @var \App\Addresses\Address
	 */
	private $address;

	public function __construct(\App\Addresses\Address $address)
	{
		parent::__construct();
		$this->address = $address;
	}

	public function render()
	{
		$this->template->type = $this->type;

		$address = $this->address;
		$this->template->subject = new AddressDTO(
			$address->getName(),
			$address->getStreet(),
			$address->getCity(),
			$address->getIc(),
			$address->getDic()
		);
		$this->template->render(__DIR__ . '/Address.latte');
	}

	public function changeType($type = 'odběratel')
	{
		$this->type = $type;
		return $this;
	}

}
