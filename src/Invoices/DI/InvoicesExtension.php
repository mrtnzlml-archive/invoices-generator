<?php declare(strict_types = 1);

namespace App\Invoices\DI;

class InvoicesExtension extends \Mrtnzlml\CompilerExtension implements \Kdyby\Doctrine\DI\IEntityProvider
{

    public function loadConfiguration()
    {
        $this->addConfig(__DIR__ . '/config.neon');
    }

    public function getEntityMappings()
    {
        return ['App\Invoices' => __DIR__ . '/..'];
    }

}
