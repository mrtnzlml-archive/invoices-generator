<?php declare(strict_types = 1);

namespace App\Console;

use Kdyby\Wkhtmltopdf\Document;
use Nette\Application\LinkGenerator;
use Nette\Application\UI\Presenter;
use Nette\DI\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateInvoice extends Command
{

	/** @var \Nette\Application\Application @inject */
	public $app;

	protected function configure()
	{
		$this->setName('app:generate')
			->setDescription('Generates invoice PDF into temp directory');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		try {
			/** @var Container $container */
			$container = $this->getHelper('container')->getContainer();
			/** @var Presenter $presenter */
			$presenter = $this->getHelper('presenter')->getPresenter();

			$tempDir = $container->parameters['tempDir'];

			$output->writeln($presenter->link('//:Homepage:neplatce'));exit;

			$document = new Document($tempDir);
			$document->margin = [10, 0, 10, 0];
			$document->addFile($linkGenerator->link('Homepage:neplatce'));
			$document->save($tempDir . '/neplatce.pdf');

			$output->writeln('PDF generated!');
			return 0; // zero return code means everything is ok
		} catch (\Exception $exc) {
			$output->writeln('<error>' . $exc->getMessage() . '</error>');
			return 1; // non-zero return code means error
		}
	}

}
