<?php

namespace IMI\Contao\Command\System\Website;

use IMI\Contao\Command\AbstractContaoCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use IMI\Util\Console\Helper\Table\Renderer\RendererFactory;

class ListCommand extends AbstractContaoCommand
{
    /**
     * @var array
     */
    protected $infos;

    protected function configure()
    {
        $this
            ->setName('sys:website:list')
            ->setDescription('Lists all websites')
            ->addOption(
                'format',
                null,
                InputOption::VALUE_OPTIONAL,
                'Output Format. One of [' . implode(',', RendererFactory::getFormats()) . ']'
            )
        ;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->detectContao($output, true);

        if ($input->getOption('format') === null) {
            $this->writeSection($output, 'Contao Websites');
        }
        $this->initContao();

        foreach (\Mage::app()->getWebsites() as $store) {
            $table[$store->getId()] = array(
                $store->getId(),
                $store->getCode(),
            );
        }

        ksort($table);
        $this->getHelper('table')
            ->setHeaders(array('id', 'code'))
            ->renderByFormat($output, $table, $input->getOption('format'));
    }
}