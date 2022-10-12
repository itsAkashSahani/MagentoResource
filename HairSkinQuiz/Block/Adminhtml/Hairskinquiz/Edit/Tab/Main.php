<?php

namespace Albatool\HairSkinQuiz\Block\Adminhtml\Hairskinquiz\Edit\Tab;

/**
 * Hairskinquiz edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Albatool\HairSkinQuiz\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Albatool\HairSkinQuiz\Model\Status $status,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_status = $status;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \Albatool\HairSkinQuiz\Model\BlogPosts */
        $model = $this->_coreRegistry->registry('hairskinquiz');

        $isElementDisabled = false;

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Item Information')]);

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }

		
        $fieldset->addField(
            'id',
            'text',
            [
                'name' => 'id',
                'label' => __('ID'),
                'title' => __('ID'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'hair_skin_quiz_name',
            'text',
            [
                'name' => 'hair_skin_quiz_name',
                'label' => __('Name'),
                'title' => __('Name'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'hair_skin_quiz_email',
            'text',
            [
                'name' => 'hair_skin_quiz_email',
                'label' => __('Email'),
                'title' => __('Email'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'hair_skin_quiz_gender',
            'text',
            [
                'name' => 'hair_skin_quiz_gender',
                'label' => __('Gender'),
                'title' => __('Gender'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'hair_skin_quiz_dob',
            'text',
            [
                'name' => 'hair_skin_quiz_dob',
                'label' => __('DOB'),
                'title' => __('DOB'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'hair_skin_quiz_medical_cond',
            'text',
            [
                'name' => 'hair_skin_quiz_medical_cond',
                'label' => __('Medical Condition'),
                'title' => __('Medical Condition'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'hair_skin_quiz_special_offers',
            'text',
            [
                'name' => 'hair_skin_quiz_special_offers',
                'label' => __('Special Offers'),
                'title' => __('Special Offers'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'hair_skin_quiz_quetion_1',
            'text',
            [
                'name' => 'hair_skin_quiz_quetion_1',
                'label' => __('Describe Your Hair'),
                'title' => __('Describe Your Hair'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'hair_skin_quiz_quetion_2',
            'text',
            [
                'name' => 'hair_skin_quiz_quetion_2',
                'label' => __('Describe Your Scalp'),
                'title' => __('Describe Your Scalp'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'hair_skin_quiz_quetion_3',
            'text',
            [
                'name' => 'hair_skin_quiz_quetion_3',
                'label' => __('Describe Your Lenghts'),
                'title' => __('Describe Your Lenghts'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'hair_skin_quiz_quetion_4',
            'text',
            [
                'name' => 'hair_skin_quiz_quetion_4',
                'label' => __('Beauty Routine'),
                'title' => __('Beauty Routine'),
				
                'disabled' => $isElementDisabled
            ]
        );
					

        if (!$model->getId()) {
            $model->setData('is_active', $isElementDisabled ? '0' : '1');
        }

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Item Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Item Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    public function getTargetOptionArray(){
    	return array(
    				'_self' => "Self",
					'_blank' => "New Page",
    				);
    }
}
