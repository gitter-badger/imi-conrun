<?php

namespace IMI\Contao\Command\Eav\Attribute;

use IMI\Contao\Command\AbstractContaoCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class AttributeRemoveCommand
 */
class RemoveCommand extends AbstractContaoCommand
{
    protected function configure()
    {
        $this
            ->setName('eav:attribute:remove')
            ->addArgument('entityType', InputArgument::REQUIRED, 'Entity Type Code like catalog_product')
            ->addArgument('attributeCode', InputArgument::REQUIRED, 'Attribute Code')
            ->setDescription('Removes attribute for a given attribute code');
    }

    /**
     * @param InputInterface   $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->detectContao($output, true);
        if ($this->initContao()) {
            try {

                $entityType = $input->getArgument('entityType');
                if (!strstr($entityType, "_")) {
                    throw new \InvalidArgumentException(
                        sprintf('Entity type: "%s" is invalid', $entityType)
                    );
                }
                $setupTypeParts = explode("_", $entityType);
                $attributeCode  = $input->getArgument('attributeCode');

                $attribute = \Mage::getModel('eav/config')->getAttribute($entityType, $attributeCode);
                if (!$attribute->getId()) {
                    throw new \InvalidArgumentException(
                        sprintf('Attribute: "%s" does not exist for entity type: "%s"', $attributeCode, $entityType)
                    );
                }

                $setup = \Mage::getResourceModel($setupTypeParts[0] . "/setup", $setupTypeParts[0] . "_setup");
                $setup->removeAttribute($entityType, $attributeCode);

                $output->writeln(sprintf('<info>Successfully removed attribute: "%s" from entity type: "%s"</info>', $attributeCode, $entityType));

            } catch (Exception $e) {
                $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            }
        }
    }
}
