<?php
namespace Akash\DatabaseSetup\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class Uninstall implements UninstallInterface
{
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->getConnection()->dropTable($setup->getTable('ambab_emi_options'));
        $setup->getConnection()->dropTable($setup->getTable('ambab_banks'));
    }
}