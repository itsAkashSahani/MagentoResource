<?php

namespace Albatool\ProductAttribute\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

class UpgradeData implements UpgradeDataInterface
{
    protected $eavSetupFactory;
    private $attributeSetFactory;
    private $attributeSet;
    private $categorySetupFactory;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        AttributeSetFactory $attributeSetFactory, 
        CategorySetupFactory $categorySetupFactory
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->categorySetupFactory = $categorySetupFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        /* Compate you module version */
        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            /*Age Attribute*/
            $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'age');
 
            $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\AgeOptions';
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'age',
                [
                    'group' => 'Albatool Attributes',
                    'type' => 'text',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'AGE',
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
                    'unique' => false,
                ]
            );
            /*End Age Attribute*/
            /*Format Attribute*/
            $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'format');
 
            $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\FormatOptions';
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'format',
                [
                    'group' => 'Albatool Attributes',
                    'type' => 'text',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'FORMAT',
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
            /*End Format Attribute*/
        }
        else if (version_compare($context->getVersion(), '1.0.2', '<')) {
             /*SUN PROTECTION Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'sun_protection');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\SunprotectionOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'sun_protection',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'SUN PROTECTION',
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
            /*End SUN PROTECTION Attribute*/
        }
        else if (version_compare($context->getVersion(), '1.0.3', '<')) {
             /*ANTI AGING Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'anti_aging');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\AntiagingOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'anti_aging',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'ANTI AGING',
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
            /*End ANTI AGING Attribute*/
            /*TEXTURE Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'texture');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\TextureOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'texture',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'TEXTURE',
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
            /*End TEXTURE Attribute*/
            /*SKIN TYPE Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'skin_type');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\SkintypeOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'skin_type',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'SKIN TYPE',
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
            /*End SKIN TYPE Attribute*/
            /*PRODUCT TYPE Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'product_type');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\ProducttypeOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'product_type',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'PRODUCT TYPE',
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
            /*End PRODUCT TYPE Attribute*/
            /*CLEANSER TYPE Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'cleanser_type');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\CleansertypeOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'cleanser_type',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'CLEANSER TYPE',
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
            /*End CLEANSER TYPE Attribute*/
            /*LIMITED EDITION Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'limited_edition');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\LimitededitionOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'limited_edition',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'LIMITED EDITION',
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
            /*End LIMITED EDITION Attribute*/
            /*WHO Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'who');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\WhoOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'who',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'WHO',
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
            /*End WHO Attribute*/
            /*ENGAGEMENT Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'engagement');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\EngagementOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'engagement',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'ENGAGEMENT',
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
            /*End ENGAGEMENT Attribute*/
            /*COMPLEXION Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'complexion');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\ComplexionOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'complexion',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'COMPLEXION',
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
            /*End COMPLEXION Attribute*/
            /*COVERAGE Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'coverage');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\CoverageOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'coverage',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'COVERAGE',
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
            /*End COVERAGE Attribute*/
            /*EFFECT Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'effect');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\EffectOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'effect',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'EFFECT',
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
            /*End EFFECT Attribute*/
            /*RESULT Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'result');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\ResultOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'result',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'RESULT',
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
            /*End RESULT Attribute*/
            /*AREA Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'area');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\AreaOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'area',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'AREA',
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
            /*End AREA Attribute*/
            /*HAIR COLOR Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'hair_color');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\HaircolorOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'hair_color',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'HAIR COLOR',
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
            /*End HAIR COLOR Attribute*/
            /*HAIR TYPE Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'hair_type');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\HairtypeOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'hair_type',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'HAIR TYPE',
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
            /*End HAIR TYPE Attribute*/
            /*NOTE Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'note');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\NoteOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'note',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'NOTE',
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
            /*End NOTE Attribute*/
            /*INTENSITY Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'intensity');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\IntensityOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'intensity',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'INTENSITY',
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
            /*End INTENSITY Attribute*/
            /*SCENT Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'scent');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\ScentOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'scent',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'SCENT',
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
            /*End SCENT Attribute*/
        }
        else if (version_compare($context->getVersion(), '1.0.4', '<')) {
            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
            $attributeSet = $this->attributeSetFactory->create();
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
            $data = [
                'attribute_set_name' => 'FACECARE (00002)',
                'entity_type_id' => $entityTypeId,
                'sort_order' => 200,
            ];
            $attributeSet->setData($data);
            $attributeSet->validate();
            $attributeSet->save();
            $attributeSet->initFromSkeleton($attributeSetId);
            $attributeSet->save();
        }
        else if (version_compare($context->getVersion(), '1.0.5', '<')) {
            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
            $attributeSet = $this->attributeSetFactory->create();
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
            $data8 = [
                'attribute_set_name' => 'MULTI FAMILY (00991)',
                'entity_type_id' => $entityTypeId,
                'sort_order' => 208,
            ];
            $attributeSet->setData($data8);
            $attributeSet->validate();
            $attributeSet->save();
            $attributeSet->initFromSkeleton($attributeSetId);
            $attributeSet->save();

            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
            $attributeSet = $this->attributeSetFactory->create();
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
            $data1 = [
                'attribute_set_name' => 'MAKEUP (00007)',
                'entity_type_id' => $entityTypeId,
                'sort_order' => 201,
            ];
            $attributeSet->setData($data1);
            $attributeSet->validate();
            $attributeSet->save();
            $attributeSet->initFromSkeleton($attributeSetId);
            $attributeSet->save();

            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
            $attributeSet = $this->attributeSetFactory->create();
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
            $data2 = [
                'attribute_set_name' => 'BODY CARE (00003)',
                'entity_type_id' => $entityTypeId,
                'sort_order' => 202,
            ];
            $attributeSet->setData($data2);
            $attributeSet->validate();
            $attributeSet->save();
            $attributeSet->initFromSkeleton($attributeSetId);
            $attributeSet->save();

            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
            $attributeSet = $this->attributeSetFactory->create();
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
            $data3 = [
                'attribute_set_name' => 'SUN CARE (00006)',
                'entity_type_id' => $entityTypeId,
                'sort_order' => 203,
            ];
            $attributeSet->setData($data3);
            $attributeSet->validate();
            $attributeSet->save();
            $attributeSet->initFromSkeleton($attributeSetId);
            $attributeSet->save();

            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
            $attributeSet = $this->attributeSetFactory->create();
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
            $data4 = [
                'attribute_set_name' => 'HAIR CARE (00004)',
                'entity_type_id' => $entityTypeId,
                'sort_order' => 204,
            ];
            $attributeSet->setData($data4);
            $attributeSet->validate();
            $attributeSet->save();
            $attributeSet->initFromSkeleton($attributeSetId);
            $attributeSet->save();

            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
            $attributeSet = $this->attributeSetFactory->create();
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
            $data5 = [
                'attribute_set_name' => 'PERFUME (00001)',
                'entity_type_id' => $entityTypeId,
                'sort_order' => 205,
            ];
            $attributeSet->setData($data5);
            $attributeSet->validate();
            $attributeSet->save();
            $attributeSet->initFromSkeleton($attributeSetId);
            $attributeSet->save();

            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
            $attributeSet = $this->attributeSetFactory->create();
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
            $data6 = [
                'attribute_set_name' => 'HYGIENE (00005)',
                'entity_type_id' => $entityTypeId,
                'sort_order' => 206,
            ];
            $attributeSet->setData($data6);
            $attributeSet->validate();
            $attributeSet->save();
            $attributeSet->initFromSkeleton($attributeSetId);
            $attributeSet->save();

            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
            $attributeSet = $this->attributeSetFactory->create();
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
            $data7 = [
                'attribute_set_name' => 'DIETETIC PRODUCTS (00025)',
                'entity_type_id' => $entityTypeId,
                'sort_order' => 207,
            ];
            $attributeSet->setData($data7);
            $attributeSet->validate();
            $attributeSet->save();
            $attributeSet->initFromSkeleton($attributeSetId);
            $attributeSet->save();
        }
        else if (version_compare($context->getVersion(), '1.0.10', '<')) {
             /*ANTI AGING Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'anti_aging');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\AntiagingOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'anti_aging',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'ANTI AGING',
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
            /*End ANTI AGING Attribute*/
            /*TEXTURE Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'texture');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\TextureOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'texture',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'TEXTURE',
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
            /*End TEXTURE Attribute*/
            /*SKIN TYPE Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'skin_type');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\SkintypeOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'skin_type',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'SKIN TYPE',
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
            /*End SKIN TYPE Attribute*/
            /*PRODUCT TYPE Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'product_type');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\ProducttypeOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'product_type',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'PRODUCT TYPE',
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
            /*End PRODUCT TYPE Attribute*/
            /*CLEANSER TYPE Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'cleanser_type');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\CleansertypeOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'cleanser_type',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'CLEANSER TYPE',
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
            /*End CLEANSER TYPE Attribute*/
            /*LIMITED EDITION Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'limited_edition');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\LimitededitionOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'limited_edition',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'LIMITED EDITION',
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
            /*End LIMITED EDITION Attribute*/
            /*WHO Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'who');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\WhoOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'who',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'WHO',
                        'input' => 'select',
                        'class' => '',
                        'source' => $statusOptions,
                        'global' => ScopedAttributeInterface::SCOPE_,
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
            /*End WHO Attribute*/
            /*ENGAGEMENT Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'engagement');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\EngagementOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'engagement',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'ENGAGEMENT',
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
            /*End ENGAGEMENT Attribute*/
            /*COMPLEXION Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'complexion');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\ComplexionOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'complexion',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'COMPLEXION',
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
            /*End COMPLEXION Attribute*/
            /*COVERAGE Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'coverage');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\CoverageOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'coverage',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'COVERAGE',
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
            /*End COVERAGE Attribute*/
            /*EFFECT Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'effect');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\EffectOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'effect',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'EFFECT',
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
            /*End EFFECT Attribute*/
            /*RESULT Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'result');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\ResultOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'result',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'RESULT',
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
            /*End RESULT Attribute*/
            /*AREA Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'area');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\AreaOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'area',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'AREA',
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
            /*End AREA Attribute*/
            /*HAIR COLOR Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'hair_color');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\HaircolorOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'hair_color',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'HAIR COLOR',
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
            /*End HAIR COLOR Attribute*/
            /*HAIR TYPE Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'hair_type');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\HairtypeOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'hair_type',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'HAIR TYPE',
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
            /*End HAIR TYPE Attribute*/
            /*NOTE Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'note');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\NoteOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'note',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'NOTE',
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
            /*End NOTE Attribute*/
            /*INTENSITY Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'intensity');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\IntensityOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'intensity',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'INTENSITY',
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
            /*End INTENSITY Attribute*/
            /*SCENT Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'scent');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\ScentOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'scent',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'SCENT',
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
            /*End SCENT Attribute*/
        }
        else if (version_compare($context->getVersion(), '1.0.11', '<')) {
            /*Best Seller Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'best_seller');
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'best_seller',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Best Seller',
                        'input' => 'select',
                        'class' => '',
                        'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
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
            /*End Best Seller Attribute*/
            /*Ref Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'ref');
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'ref',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Ref',
                        'input' => 'text',
                        'class' => '',
                        'source' => '',
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
            /*End Ref Attribute*/
            /*Prod Other Price Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'prod_other_price');
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'prod_other_price',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Product Other Price',
                        'input' => 'text',
                        'class' => '',
                        'source' => '',
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
            /*End Prod Other Price Attribute*/
            /*Prod Discount Offer Price Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'prod_discount_offer_price');
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'prod_discount_offer_price',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Product Discount Offer Price',
                        'input' => 'text',
                        'class' => '',
                        'source' => '',
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
            /*End Prod Discount Offer Price Attribute*/
        }
        else if (version_compare($context->getVersion(), '1.0.12', '<')) {
            /*ingredients Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'ingredients');
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'ingredients',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                        'frontend' => '',
                        'label' => 'Ingredients',
                        'input' => 'multiselect',
                        'class' => '',
                        'source' => 'Albatool\ProductAttribute\Model\Config\Source\Ingredientsoption',
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
            /*End ingredients Attribute*/
        }
        else if (version_compare($context->getVersion(), '1.0.13', '<')) {
            /*tube qty Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'tube_qty');
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'tube_qty',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Tube Qty',
                        'input' => 'text',
                        'class' => '',
                        'source' => '',
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
            /*End tube qty Attribute*/
            /*color shades Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'color_shades');
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'color_shades',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Number of Color Shades',
                        'input' => 'text',
                        'class' => '',
                        'source' => '',
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
            /*End color shades Attribute*/
        }
        else if (version_compare($context->getVersion(), '1.0.14', '<')) {
            /*Action Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'action');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\ActionsOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'action',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Action',
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
            /*End Action Attribute*/
            /*Range Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'range');
                $statusOptions = 'Albatool\ProductAttribute\Model\Config\Source\RangeOptions';
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'range',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Range',
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
            /*End Range Attribute*/
        }
        else if (version_compare($context->getVersion(), '1.0.15', '<')) {
            $entityType = $eavSetup->getEntityTypeId('catalog_product');

            $eavSetup->updateAttribute($entityType, 'action', 'frontend_type','varchar', null);
            $eavSetup->updateAttribute($entityType, 'action', 'used_in_product_listing' , true, null);
        }
        else if (version_compare($context->getVersion(), '1.0.16', '<')) {
            $entityType = $eavSetup->getEntityTypeId('catalog_product');

            $eavSetup->updateAttribute($entityType, 'action', 'backend_type','varchar', null);
        }
        else if (version_compare($context->getVersion(), '1.0.17', '<')) {
            $entityType = $eavSetup->getEntityTypeId('catalog_product');

            $eavSetup->updateAttribute($entityType, 'product_type', 'attribute_code','product_type_val', null);
        }
        else if (version_compare($context->getVersion(), '1.0.18', '<')) {
            /*how to use Attribute*/
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'how_to_use');
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'how_to_use',
                    [
                        'group' => 'Albatool Attributes',
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'How To Use',
                        'input' => 'text',
                        'class' => '',
                        'source' => '',
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
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
            /*End how to use Attribute*/
    
        }
    }
}