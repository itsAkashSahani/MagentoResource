<?php

namespace Albatool\SkinQuizConsultant\Setup\UpgradeSchema;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema102 implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $connection = $setup->getConnection();

        $connection->addColumn(
            $setup->getTable('skin_quiz_consultant'), 
            'banner_image', 
        [
            'type' => Table::TYPE_TEXT,
            'nullable' => false,
            'length' => '512',
            'comment' => 'Skin Quiz Banner Image',
            'after' => 'skin_quiz_result_description'
        ]);
    }
}
