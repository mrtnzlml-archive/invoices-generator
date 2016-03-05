<?php declare(strict_types = 1);

namespace App;

use Nette\Utils\Html;

/**
 * @method string getName()
 * @method string getIc()
 * @method string getDic()
 */
class AddressDTO extends \Nette\Object
{

	private $name;

	private $street;

	private $city;

	private $ic;

	private $dic;

	public function __construct($name, $street, $city, $ic, $dic = NULL)
	{
		$this->name = $name;
		$this->street = $street;
		$this->city = $city;
		$this->ic = $ic;
		$this->dic = $dic;
	}

	public function getStreet()
	{
		return Html::el()->addText($this->street);
	}

	public function getCity()
	{
		return Html::el()->addText($this->city);
	}

}
