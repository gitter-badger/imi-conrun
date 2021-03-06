<?php

namespace IMI\Contao\Command\ContaoConnect;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallExtensionCommand extends AbstractConnectCommand
{
    protected function configure()
    {
        $this
            ->setName('extension:install')
            ->addArgument('package', InputArgument::REQUIRED, 'Package to install')
            ->setDescription('Install contao-connect package')
        ;

        $help = <<<HELP
If the package could not be found a search for alternatives will be done.
If alternatives could be found you can select the package to install.

* Requires Contao's `mage` shell script.
* Does not work with Windows as operating system.
HELP;
        $this->setHelp($help);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $package
     */
    protected function doAction(InputInterface $input, OutputInterface $output, $package)
    {
        $output->writeln($this->callMageScript($input, $output, 'install community ' . $package));
    }
}
