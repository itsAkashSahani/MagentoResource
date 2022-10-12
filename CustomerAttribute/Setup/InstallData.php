<?php

namespace Albatool\CustomerAttribute\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /**
     * EAV setup factory
     *
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Customer setup factory
     *
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * Constructor
     *
     * @param EavSetupFactory $eavSetupFactory
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CustomerSetupFactory $customerSetupFactory
    ) 
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        /**
         * Create a file type attribute
         * For File or Image Upload
         */
        $attributeCode = 'my_customer_image';

        $customerSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY, 
            $attributeCode, 
            [
                'type' => 'text',
                'label' => 'My Customer File/Image',
                'input' => 'file',
                'source' => '',
                'required' => false,
                'visible' => true,
                'position' => 200,
                'system' => false,
                'backend' => ''
            ]
        );
        
        // show the attribute in the following forms
        $attribute = $customerSetup
                        ->getEavConfig()
                        ->getAttribute(
                            \Magento\Customer\Model\Customer::ENTITY, 
                            $attributeCode
                        )
                        ->addData(
                            ['used_in_forms' => [
                                'adminhtml_customer',
                                'adminhtml_checkout',
                                'customer_account_create',
                                'customer_account_edit'
                            ]
                        ]);

        $attribute->save();

        $setup->endSetup();
    }
}