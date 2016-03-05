<?php declare(strict_types = 1);

namespace App\Addresses\DI;

class AddressesExtension extends \Mrtnzlml\CompilerExtension implements \Kdyby\Doctrine\DI\IEntityProvider
{

	public function getEntityMappings()
	{
		return ['App\Addresses' => __DIR__ . '/..'];
	}

}
