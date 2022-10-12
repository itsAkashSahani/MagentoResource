<?php
namespace Albatool\StorePickupOrder\Block\Adminhtml\User\Edit\Tab;

use Magento\Backend\Block\Widget\Form;

class Main extends \Magento\User\Block\User\Edit\Tab\Main
{
    /**
     * Prepare form fields
     *
     * @return Form
     */
    public function getRegOtpMessage()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $sourceList = $objectManager->get('\Magento\Inventory\Model\ResourceModel\Source\Collection');
        $sourceListArr = $sourceList->load();
        $options[] = ['label' => '-- Please Select --', 'value' => ''];

        $sourceList = array();
        foreach ($sourceListArr as $sourceItemName) {
            $options[] = [
                'label' => $sourceItemName->getName(),
                'value' => $sourceItemName->getSourceCode(),
            ];
        }
        return $options;
    }

    protected function _prepareForm()
    {
    
        $isElementDisabled = false;
        parent::_prepareForm();
        $form = $this->getForm();
        $model = $this->_coreRegistry->registry('permissions_user');
        $baseFieldset = $form->getElement('base_fieldset');
        $isShowPickup = 0;
        
        if($model->getData('is_show_otp') == 1) {
            $isShowPickup = 1;
        }

        $baseFieldset->addField(
            'storepickuporder',
            'select',
            [
                'label' => __('Store Pickup Order'),
                'title' => __('Store Pickup Order'),
                'name' => 'storepickuporder',
                'required' => false,
                'values' => ($this->getRegOtpMessage()),
                'disabled' => $isElementDisabled,
                 'note' => '<p><b style="font-size: 20px;text-transform: capitalize;">'.$model->getStorepickuporder().'</b>',

            ]
        );
       
        $baseFieldset->addField(
            'is_show_otp',
            'select',
            [
                'label' => __('Show Pickup OTP'),
                'title' => __('Show Pickup OTP'),
                'name' => 'is_show_otp',
                'required' => false,
                'value' => $isShowPickup,
                'options' => ['1' => __('Yes'), '0' => __('No')],
                'disabled' => $isElementDisabled
            ]
        );


       
        // $baseFieldset->addField(
        //     $model->getStorepickuporder(),
        //     'label',
        //     [
        //         'name' => 'news',
        //         'label' => __('Store Pickup Order'),
        //         'title' => __('Store Pickup Order'),
        //         'value' => $model->getStorepickuporder(),
        //     ]
        // );
        return $this;
    }
}
