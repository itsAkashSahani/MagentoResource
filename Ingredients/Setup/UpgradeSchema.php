<?php

namespace Albatool\Ingredients\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $installer = $setup;
        $installer->startSetup();
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
                $installer->getConnection()->addColumn(
                    $installer->getTable('ingredientsglossary'),
                        'is_organic',
                        [
                            'type' => 'text',
                            'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                            'comment' => 'Is Organic'
                        ]
                );
        }
        $installer->endSetup();
    }
}