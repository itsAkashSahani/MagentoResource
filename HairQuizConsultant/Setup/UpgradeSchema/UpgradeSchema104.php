<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-product-kit
 * @version   1.0.36
 * @copyright Copyright (C) 2022 Mirasvit (https://mirasvit.com/)
 */



namespace Albatool\HairQuizConsultant\Setup\UpgradeSchema;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema104 implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $connection = $setup->getConnection();

        $connection->addColumn(
            $setup->getTable('hair_quiz_consultant'), 
            'hair_quiz_q1', 
        [
            'type' => Table::TYPE_TEXT,
            'nullable' => false,
            'length' => '512',
            'comment' => 'Hair Quiz Question 1',
        ]);

        $connection->addColumn(
            $setup->getTable('hair_quiz_consultant'), 
            'hair_quiz_q2', 
        [
            'type' => Table::TYPE_TEXT,
            'nullable' => false,
            'length' => '512',
            'comment' => 'Hair Quiz Question 2'
        ]);

        $connection->addColumn(
            $setup->getTable('hair_quiz_consultant'), 
            'hair_quiz_q3', 
        [
            'type' => Table::TYPE_TEXT,
            'nullable' => false,
            'length' => '512',
            'comment' => 'Hair Quiz Question 3'
        ]);

        $connection->addColumn(
            $setup->getTable('hair_quiz_consultant'), 
            'hair_quiz_q4', 
        [
            'type' => Table::TYPE_TEXT,
            'nullable' => false,
            'length' => '512',
            'comment' => 'Hair Quiz Question 4'
        ]);

        $connection->addColumn(
            $setup->getTable('hair_quiz_consultant'), 
            'hair_quiz_q5', 
        [
            'type' => Table::TYPE_TEXT,
            'nullable' => false,
            'length' => '512',
            'comment' => 'Hair Quiz Question 5'
        ]);
    }
}
