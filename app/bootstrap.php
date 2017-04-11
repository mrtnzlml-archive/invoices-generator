<?php declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

Kdyby\Console\DI\BootstrapHelper::setupMode($configurator);

$configurator->setDebugMode(getenv('NETTE_DEBUG') === '1');
$configurator->enableDebugger(__DIR__ . '/../log');

$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

return $configurator->createContainer();
