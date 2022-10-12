<?php
namespace Ambab\EmiCalculator\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $newsTableName = $setup->getTable('ambab_banks');

        if($setup->getConnection()->isTableExists($newsTableName) != true) {

        $bankTable = $setup->getConnection()
            ->newTable($newsTableName)
            ->addColumn(
                'bank_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Bank ID'
            )
            ->addColumn(
                'bank_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''],
                    'Bank Name'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                    'Created At'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                    'Updated At'
            )
            ->addIndex(
                $setup->getIdxName('ambab_banks', ['bank_name']),
                ['bank_name']
            )
            ->setComment("Bank Table");

        $setup->getConnection()->createTable($bankTable);
        }

        //EMI Table

        $newsTableName = $setup->getTable('ambab_emi_options');

        if($setup->getConnection()->isTableExists($newsTableName) != true) {

        $bankTable = $setup->getConnection()
            ->newTable($newsTableName)
            ->addColumn(
                'emi_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Bank ID'
            )
            ->addColumn(
                'bank_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''],
                    'Bank ID'
            )
            ->addColumn(
                'month',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                    'Months'
            )
            ->addColumn(
                'interest',
                \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                null,
                ['nullable' => false],
                    'Intrest Rate'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                    'Created At'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                    'Updated At'
            )
            ->setComment("Emi Option Table");

        $setup->getConnection()->createTable($bankTable);
        }
        
    }
}
?>
