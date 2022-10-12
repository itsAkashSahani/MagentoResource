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



namespace Albatool\SkinQuizConsultant\Setup\UpgradeSchema;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema103 implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $connection = $setup->getConnection();

        $connection->addColumn(
            $setup->getTable('skin_quiz_consultant'), 
            'mobile_banner_image', 
        [
            'type' => Table::TYPE_TEXT,
            'nullable' => false,
            'length' => '512',
            'comment' => 'SKin Quiz Mobile Banner Image',
            'after' => 'banner_image'
        ]);
    }
}
