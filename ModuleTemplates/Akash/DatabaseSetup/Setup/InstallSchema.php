<?php
namespace Akash\DatabaseSetup\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        // $setup->startSetup();
        $newsTableName = $setup->getTable('ambab_banks');

        if($setup->getConnection()->isTableExists($newsTableName) != true) {

        $bankTable = $setup->getConnection()
            ->newTable($newsTableName)
            ->addColumn(
                'bank_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false],
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
                'bank_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'primary' => true],
                    'Bank Code'
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
                $setup->getIdxName(
                    'ambab_banks',
                    ['bank_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['bank_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName('ambab_banks', ['bank_name']),
                ['bank_name']
            )
            ->addIndex(
                $setup->getIdxName('ambab_banks', ['bank_code']),
                ['bank_code']
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
                'bank_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                    'Bank Code'
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
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '10,2',
                ['nullable' => false],
                    'Intrest Rate'
            )
            ->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                null,
                ['nullable' => false, 'unsigned' => true],
                  'Status'
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
            ->addForeignKey(
                $setup->getFkName(
                    'ambab_emi_options',
                    'bank_code',
                    'ambab_banks',
                    'bank_code'
                ),
                'bank_code',
                $setup->getTable('ambab_banks'), 
                'bank_code',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment("Emi Option Table");

        $setup->getConnection()->createTable($bankTable);
        // $setup->endSetup();
        }
        
    }
}
?>
