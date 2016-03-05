<?php declare(strict_types = 1);

namespace App\Addresses;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="addresses")
 */
class Address
{

	use \Kdyby\Doctrine\Entities\Attributes\Identifier;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $name;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $street;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $city;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 * @var string
	 */
	private $ic;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $dic;

	public function getName(): string
	{
		return $this->name;
	}

	public function getStreet(): string
	{
		return $this->street;
	}

	public function getCity(): string
	{
		return $this->city;
	}

	public function getIc(): string
	{
		return $this->ic;
	}

	public function getDic(): string
	{
		return $this->dic;
	}

}
