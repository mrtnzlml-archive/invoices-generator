<?php declare(strict_types = 1);

namespace App\Doctrine;

use Doctrine;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\ORMException;

class EntityManager extends \Kdyby\Doctrine\EntityManager
{

	/**
	 * Factory method to create EntityManager instances.
	 *
	 * @param array|\Doctrine\DBAL\Connection $conn
	 * @param \Doctrine\ORM\Configuration $config
	 * @param \Doctrine\Common\EventManager|NULL $eventManager
	 *
	 * @return \App\Doctrine\EntityManager
	 * @throws \Doctrine\DBAL\DBALException
	 * @throws \Doctrine\ORM\ORMException
	 */
	public static function create($conn, Doctrine\ORM\Configuration $config, Doctrine\Common\EventManager $eventManager = NULL)
	{
		if (!$config->getMetadataDriverImpl()) {
			throw ORMException::missingMappingDriverImpl();
		}

		switch (TRUE) {
			case (is_array($conn)):
				$conn = DriverManager::getConnection(
					$conn, $config, ($eventManager ?: new Doctrine\Common\EventManager())
				);
				break;

			case ($conn instanceof Doctrine\DBAL\Connection):
				if ($eventManager !== NULL && $conn->getEventManager() !== $eventManager) {
					throw ORMException::mismatchedEventManager();
				}
				break;

			default:
				throw new \InvalidArgumentException("Invalid connection");
		}

		return new EntityManager($conn, $config, $conn->getEventManager());
	}

}
