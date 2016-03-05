<?php declare(strict_types = 1);

namespace App;

/**
 * @method string getDescription()
 * @method float getPrice()
 */
class ProductDTO extends \Nette\Object
{

	private $description;

	private $price;

	public function __construct($description, $price)
	{
		$this->description = $description;
		$this->price = $price;
	}

}
