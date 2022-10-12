<?php
namespace Albatool\HairQuizConsultant\Block\Adminhtml\Hairquizconsultant;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Albatool\HairQuizConsultant\Model\hairquizconsultantFactory
     */
    protected $_hairquizconsultantFactory;

    /**
     * @var \Albatool\HairQuizConsultant\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Albatool\HairQuizConsultant\Model\hairquizconsultantFactory $hairquizconsultantFactory
     * @param \Albatool\HairQuizConsultant\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Albatool\HairQuizConsultant\Model\HairquizconsultantFactory $HairquizconsultantFactory,
        \Albatool\HairQuizConsultant\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_hairquizconsultantFactory = $HairquizconsultantFactory;
        $this->_status = $status;
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('postGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
        $this->setVarNameFilter('post_filter');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->_hairquizconsultantFactory->create()->getCollection();
        $this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn(
            'store_code',
            [
                'header' => __('Response Scope'),
                'index' => 'store_code',
                'renderer'  => '\Albatool\HairQuizConsultant\Block\Adminhtml\Hairquizconsultant\Grid\Renderer\StoreLabel'
            ]
        );

        // $this->addColumn(
        //     'hair_quiz_q1',
        //     [
        //         'header' => __('Hair Quiz Question 1'),
        //         'index' => 'hair_quiz_q1',
        //     ]
        // );

        // $this->addColumn(
        //     'hair_quiz_q2',
        //     [
        //         'header' => __('Hair Quiz Question 2'),
        //         'index' => 'hair_quiz_q2',
        //     ]
        // );

        $this->addColumn(
            'hair_quiz_q3',
            [
                'header' => __('Hair Quiz Question 3'),
                'index' => 'hair_quiz_q3',
            ]
        );

        $this->addColumn(
            'hair_quiz_q4',
            [
                'header' => __('Hair Quiz Question 4'),
                'index' => 'hair_quiz_q4',
            ]
        );

        $this->addColumn(
            'hair_quiz_q5',
            [
                'header' => __('Hair Quiz Question 5'),
                'index' => 'hair_quiz_q5',
            ]
        );

        // $this->addColumn(
        //     'hair_quiz_combination',
        //     [
        //         'header' => __('Hair Quiz Question Combination'),
        //         'index' => 'hair_quiz_combination',
        //     ]
        // );

        // $this->addColumn(
        //     'banner_image',
        //     [
        //         'header' => __('Hair Quiz Banner Image'),
        //         'index' => 'banner_image',
        //         'renderer'  => '\Albatool\HairQuizConsultant\Block\Adminhtml\Hairquizconsultant\Grid\Renderer\Banner'
        //     ]
        // );

        // $this->addColumn(
        //     'mobile_banner_image',
        //     [
        //         'header' => __('Hair Quiz Mobile Banner Image'),
        //         'index' => 'mobile_banner_image',
        //         'renderer'  => '\Albatool\HairQuizConsultant\Block\Adminhtml\Hairquizconsultant\Grid\Renderer\Banner'
        //     ]
        // );

        $this->addColumn(
            'hair_quiz_result_description',
            [
                'header' => __('Hair Quiz Result Description'),
                'index' => 'hair_quiz_result_description',
            ]
        );
        
        $this->addColumn(
            'consult_products_hair',
            [
                'header' => __('Consult Product Skus'),
                'index' => 'consult_products_hair',
            ]
        );
		
        //$this->addColumn(
            //'edit',
            //[
                //'header' => __('Edit'),
                //'type' => 'action',
                //'getter' => 'getId',
                //'actions' => [
                    //[
                        //'caption' => __('Edit'),
                        //'url' => [
                            //'base' => '*/*/edit'
                        //],
                        //'field' => 'id'
                    //]
                //],
                //'filter' => false,
                //'sortable' => false,
                //'index' => 'stores',
                //'header_css_class' => 'col-action',
                //'column_css_class' => 'col-action'
            //]
        //);
		
		   $this->addExportType($this->getUrl('hairquizconsultant/*/exportCsv', ['_current' => true]),__('CSV'));
		   $this->addExportType($this->getUrl('hairquizconsultant/*/exportExcel', ['_current' => true]),__('Excel XML'));

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

	
    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {

        $this->setMassactionIdField('id');
        //$this->getMassactionBlock()->setTemplate('Albatool_HairQuizConsultant::hairquizconsultant/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('hairquizconsultant');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('hairquizconsultant/*/massDelete'),
                'confirm' => __('Are you sure?')
            ]
        );

        $statuses = $this->_status->getOptionArray();

        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('hairquizconsultant/*/massStatus', ['_current' => true]),
                'additional' => [
                    'visibility' => [
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => __('Status'),
                        'values' => $statuses
                    ]
                ]
            ]
        );


        return $this;
    }
		

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('hairquizconsultant/*/index', ['_current' => true]);
    }

    /**
     * @param \Albatool\HairQuizConsultant\Model\hairquizconsultant|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		
        return $this->getUrl(
            'hairquizconsultant/*/edit',
            ['id' => $row->getId()]
        );
		
    }

	

}