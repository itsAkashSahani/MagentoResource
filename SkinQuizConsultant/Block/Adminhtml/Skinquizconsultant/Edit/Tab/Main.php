<?php

namespace Albatool\SkinQuizConsultant\Block\Adminhtml\Skinquizconsultant\Edit\Tab;

use Albatool\SkinQuizConsultant\Model\SkinQuizQuestions;

/**
 * Skinquizconsultant edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Albatool\SkinQuizConsultant\Model\Status
     */
    protected $_status;

    protected $skinq1;
    protected $skinq2;
    protected $skinq3;
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
        \Albatool\SkinQuizConsultant\Model\Status $status,
        \Albatool\SkinQuizConsultant\Model\SkinQuizKit $kitData,
        SkinQuizQuestions\SkinQ1 $skinq1,
        SkinQuizQuestions\SkinQ2 $skinq2,
        SkinQuizQuestions\SkinQ3 $skinq3,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Albatool\SkinQuizConsultant\Model\StoreList $storeList,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_status = $status;
        $this->skinq1 = $skinq1;
        $this->skinq2 = $skinq2;
        $this->skinq3 = $skinq3;
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
        /* @var $model \Albatool\SkinQuizConsultant\Model\BlogPosts */
        $model = $this->_coreRegistry->registry('skinquizconsultant');

        $isElementDisabled = false;

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Item Information')]);

        $fieldset->addType('image', '\Albatool\SkinQuizConsultant\Block\Adminhtml\Skinquizconsultant\Helper\Image');

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
            'skin_quiz_q1',
            'select',
            [
                'name' => 'skin_quiz_q1',
                'label' => __('Question 1'),
                'title' => __('Question 1'),
                'options' => $this->skinq1->getOptionArray(),
                'required' => true,
                'class' => 'select skin_q1'
            ]
        );

        $fieldset->addField(
            'skin_quiz_q2',
            'select',
            [
                'name' => 'skin_quiz_q2',
                'label' => __('Question 2'),
                'title' => __('Question 2'),
				'options' => $this->skinq2->getOptionArray(),
                'required' => true,
                'class' => 'select skin_q2'
            ]
        );

        $fieldset->addField(
            'skin_quiz_q3',
            'select',
            [
                'name' => 'skin_quiz_q3',
                'label' => __('Question 3'),
                'title' => __('Question 3'),
				'options' => $this->skinq3->getOptionArray(),
                'required' => true,
                'class' => 'select skin_q3'
            ]
        )->setAfterElementHtml('
            <script>
                require([
                    "jquery",
                ], function($){
                    var q1 = $(".skin_q1").val();
                    var q2 = $(".skin_q2").val();
                    var q3 = $(".skin_q3").val();

                    $(document).ready(function () {
                        if(q1 !== null && q2 !== null && q3 !== null) {
                            var combination = `${q1}::${q2}::${q3}`;
                            $(".combination").val(combination);
                            console.log(combination);
                        }
                        $(".skin_q1").change(function(){
                            q1 = $(".skin_q1").val();
                            if(q1 !== null && q2 !== null && q3 !== null) {
                                var combination = `${q1}::${q2}::${q3}`;
                                $(".combination").val(combination);
                                console.log(combination);
                            }
                        })

                        $(".skin_q2").change(function(){
                            q2 = $(".skin_q2").val();
                            if(q1 !== null && q2 !== null && q3 !== null) {
                                var combination = `${q1}::${q2}::${q3}`;
                                $(".combination").val(combination);
                                console.log(combination);
                            }
                        })

                        $(".skin_q3").change(function(){
                            q3 = $(".skin_q3").val();
                            if(q1 !== null && q2 !== null && q3 !== null) {
                                var combination = `${q1}::${q2}::${q3}`;
                                $(".combination").val(combination);
                                console.log(combination);
                            }
                        })
                    });
                });
            </script>
        ');
		
        /*$fieldset->addField(
            'id',
            'text',
            [
                'name' => 'id',
                'label' => __('ID'),
                'title' => __('ID'),
				
                'disabled' => $isElementDisabled
            ]
        );*/
					
        $fieldset->addField(
            'skin_quiz_combination',
            'hidden',
            [
                'name' => 'skin_quiz_combination',
                'label' => __('Skin Quiz Question Combination'),
                'title' => __('Skin Quiz Question Combination'),
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
            'skin_quiz_result_description',
            'textarea',
            [
                'name' => 'skin_quiz_result_description',
                'label' => __('Skin Quiz Result Description'),
                'title' => __('Skin Quiz Result Description'),
                
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
            'consult_products',
            'text',
            [
                'name' => 'consult_products',
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
