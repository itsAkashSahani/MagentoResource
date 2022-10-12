<?php

namespace Albatool\HairQuizConsultant\Block\Adminhtml\Hairquizconsultant\Edit\Tab;

use Albatool\HairQuizConsultant\Model\HairQuizQuestions;

/**
 * Hairquizconsultant edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Albatool\HairQuizConsultant\Model\Status
     */
    protected $_status;

    protected $hairq1;
    protected $hairq2;
    protected $hairq3;
    protected $hairq4;
    protected $hairq5;
    protected $kitData;

    protected $storeList;

    protected $_wysiwygConfig;

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
        \Albatool\HairQuizConsultant\Model\Status $status,
        \Albatool\HairQuizConsultant\Model\HairQuizKit $kitData,
        HairQuizQuestions\HairQ1 $hairq1,
        HairQuizQuestions\HairQ2 $hairq2,
        HairQuizQuestions\HairQ3 $hairq3,
        HairQuizQuestions\HairQ4 $hairq4,
        HairQuizQuestions\HairQ5 $hairq5,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Albatool\HairQuizConsultant\Model\StoreList $storeList,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_status = $status;
        $this->hairq1 = $hairq1;
        $this->hairq2 = $hairq2;
        $this->hairq3 = $hairq3;
        $this->hairq4 = $hairq4;
        $this->hairq5 = $hairq5;
        $this->kitData = $kitData;
        $this->storeList = $storeList;
        $this->_wysiwygConfig = $wysiwygConfig;

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
        /* @var $model \Albatool\HairQuizConsultant\Model\BlogPosts */
        $model = $this->_coreRegistry->registry('hairquizconsultant');

        $isElementDisabled = false;

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Item Information')]);

        $fieldset->addType('image', '\Albatool\HairQuizConsultant\Block\Adminhtml\Hairquizconsultant\Helper\Image');

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }

        $fieldset->addField(
            'store_code',
            'select',
            [
                'name' => 'store_code',
                'label' => __('Response Scope'),
                'title' => __('Response Scope'),
                'options' => $this->storeList->getOptionArray(),
                'required' => true
            ]
        );

        $fieldset->addField(
            'hair_quiz_q3',
            'select',
            [
                'name' => 'hair_quiz_q3',
                'label' => __('Question 3'),
                'title' => __('Question 3'),
				'options' => $this->hairq3->getOptionArray(),
                'required' => true,
                'class' => 'select hair_q3'
            ]
        );

        $fieldset->addField(
            'hair_quiz_q4',
            'select',
            [
                'name' => 'hair_quiz_q4',
                'label' => __('Question 4'),
                'title' => __('Question 4'),
				'options' => $this->hairq4->getOptionArray(),
                'required' => true,
                'class' => 'select hair_q4'
            ]
        );

        $fieldset->addField(
            'hair_quiz_q5',
            'select',
            [
                'name' => 'hair_quiz_q5',
                'label' => __('Question 5'),
                'title' => __('Question 5'),
				'options' => $this->hairq5->getOptionArray(),
                'required' => true,
                'class' => 'select hair_q5'
            ]
        )->setAfterElementHtml('
            <script>
                require([
                    "jquery",
                ], function($){
                    var q3 = $(".hair_q3").val();
                    var q4 = $(".hair_q4").val();
                    var q5 = $(".hair_q5").val();

                    $(document).ready(function () {
                        if(q3 !== null && q4 !== null && q5 !== null) {
                            var combination = `${q3}::${q4}::${q5}`;
                            $(".combination").val(combination);
                            console.log(combination);
                        }

                        $(".hair_q3").change(function(){
                            q3 = $(".hair_q3").val();
                            if(q3 !== null && q4 !== null && q5 !== null) {
                                var combination = `${q3}::${q4}::${q5}`;
                                $(".combination").val(combination);
                                console.log(combination);
                            }
                        })

                        $(".hair_q4").change(function(){
                            q4 = $(".hair_q4").val();
                            if(q3 !== null && q4 !== null && q5 !== null) {
                                var combination = `${q3}::${q4}::${q5}`;
                                $(".combination").val(combination);
                                console.log(combination);
                            }
                        })

                        $(".hair_q5").change(function(){
                            q5 = $(".hair_q5").val();
                            if(q3 !== null && q4 !== null && q5 !== null) {
                                var combination = `${q3}::${q4}::${q5}`;
                                $(".combination").val(combination);
                                console.log(combination);
                            }
                        })
                    });
                });
            </script>
        ');
					
        $fieldset->addField(
            'hair_quiz_combination',
            'hidden',
            [
                'name' => 'hair_quiz_combination',
                'label' => __('Hair Quiz Question Combination'),
                'title' => __('Hair Quiz Question Combination'),
				'class' => 'combination',
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'banner_image',
            'editor',
            [
                'name' => 'banner_image',
                'label' => __('Beauty Routine Steps'),
                'title' => __('Beauty Routine Steps'),
                'rows' => '5',
                'cols' => '30',
                'wysiwyg' => true,
                'config' => $this->_wysiwygConfig->getConfig(['add_variables' => false, 'add_widgets' => false]),
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'mobile_banner_image',
            'editor',
            [
                'name' => 'mobile_banner_image',
                'label' => __('Mobile Banner Image'),
                'title' => __('Mobile Banner Image'),
                'rows' => '5',
                'cols' => '30',
                'wysiwyg' => true,
                'config' => $this->_wysiwygConfig->getConfig(['add_variables' => false, 'add_widgets' => false]),
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'quiz_kit',
            'select',
            [
                'name' => 'quiz_kit',
                'label' => __('Choose Bundle'),
                'title' => __('Choose Bundle'),
				'values' => $this->kitData->getOptionArray(),
                'class' => 'select'
            ]
        );

        $fieldset->addField(
            'hair_quiz_result_description',
            'textarea',
            [
                'name' => 'hair_quiz_result_description',
                'label' => __('Hair Quiz Result Description'),
                'title' => __('Hair Quiz Result Description'),
                
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'beauty_steps',
            'editor',
            [
                'name' => 'beauty_steps',
                'label' => __('Beauty Routine Steps'),
                'title' => __('Beauty Routine Steps'),
                'rows' => '5',
                'cols' => '30',
                'wysiwyg' => true,
                'config' => $this->_wysiwygConfig->getConfig(['add_variables' => false, 'add_widgets' => false]),
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'consult_products_hair',
            'text',
            [
                'name' => 'consult_products_hair',
                'label' => __('Consult Product Skus'),
                'title' => __('Consult Product Skus'),
				
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
