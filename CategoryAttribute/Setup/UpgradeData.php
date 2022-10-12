<?php

namespace Albatool\CategoryAttribute\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Catalog\Setup\CategorySetupFactory;

class UpgradeData implements UpgradeDataInterface
{
    private $categorySetupFactory;

    public function __construct(
        CategorySetupFactory $categorySetupFactory
    )
    {
        $this->categorySetupFactory = $categorySetupFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $setup = $this->categorySetupFactory->create(['setup' => $setup]);
            $setup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'category_first_banner_link',
                [
                    'type'         => 'varchar',
                    'label'        => 'Category First Banner Link',
                    'input'        => 'text',
                    'sort_order'   => 100,
                    'source'       => '',
                    'global'       => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'visible'      => true,
                    'required'     => false,
                    'group'        => 'General Information',
                    'backend'      => ''
                ]
            );

            $setup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'category_second_banner_link',
                [
                    'type'         => 'varchar',
                    'label'        => 'Category Second Banner Link',
                    'input'        => 'text',
                    'sort_order'   => 110,
                    'source'       => '',
                    'global'       => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'visible'      => true,
                    'required'     => false,
                    'group'        => 'General Information',
                    'backend'      => ''
                ]
            );

            $setup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'category_third_banner_link',
                [
                    'type'         => 'varchar',
                    'label'        => 'Category Third Banner Link',
                    'input'        => 'text',
                    'sort_order'   => 120,
                    'source'       => '',
                    'global'       => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'visible'      => true,
                    'required'     => false,
                    'group'        => 'General Information',
                    'backend'      => ''
                ]
            );
        }
        
    }
}