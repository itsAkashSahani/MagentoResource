<?php
namespace Albatool\ProductAttribute\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
 
class InstallData implements InstallDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
 
    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }
 
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
 
        /**
         * Add attributes to the eav_attribute
         */
        /*Need Attribute*/
            $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'need');
            $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\NeedOptions';
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'need',
                [
                    'group' => 'Albatool Attributes',
                    'type' => 'text',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'NEED',
                    'input' => 'select',
                    'class' => '',
                    'source' => $statusOptions,
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => '',
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'is_used_in_grid' => true,
                    'visible_on_front' => false,
                    'used_in_product_listing' => true,
                    'unique' => false
                ]
            );
        /*End Need Attribute*/
        
    }
}