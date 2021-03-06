<?php

namespace IMI\Contao\Command\Developer\Translate;

use IMI\Contao\Command\AbstractContaoCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ExportCommand extends AbstractContaoCommand
{
    protected function configure()
    {
        $this
            ->setName('dev:translate:export')
            ->setDescription('Export inline translations')
            ->addArgument('locale', InputOption::VALUE_REQUIRED, 'Locale')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Export filename')
            ->addOption('store', null, InputOption::VALUE_OPTIONAL, 'Limit to a special store')
        ;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->detectContao($output);
        if ($this->initContao($output)) {
            $db = $this->getHelper('database')->getConnection();

            $filename = $input->getArgument('filename');

            if(!$filename) {
                $filename = 'translate.csv';
            }

            $locale = $input->getArgument('locale');
            $output->writeln('Exporting to <info>' . $filename . '</info>');

            $parameters = array('locale' => $locale);
            $sql = "SELECT * FROM core_translate WHERE locale = :locale";
            if ($input->getOption('store')) {
                $sql .= ' AND store_id = :store_id';
                $parameters['store_id'] = \Mage::app()->getStore($input->getOption('store'));
            }
            $statement = $db->prepare($sql);
            $statement->execute($parameters);
            $result = $statement->fetchAll();
            $f = fopen($filename, 'w');

            foreach($result as $row) {
                fputcsv($f,array($row['string'],$row['translate']));
            }

            fclose($f);
        }
    }
}