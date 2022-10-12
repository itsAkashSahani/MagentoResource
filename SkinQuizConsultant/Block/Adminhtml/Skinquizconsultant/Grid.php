<?php
namespace Albatool\SkinQuizConsultant\Block\Adminhtml\Skinquizconsultant;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Albatool\SkinQuizConsultant\Model\skinquizconsultantFactory
     */
    protected $_skinquizconsultantFactory;

    /**
     * @var \Albatool\SkinQuizConsultant\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Albatool\SkinQuizConsultant\Model\skinquizconsultantFactory $skinquizconsultantFactory
     * @param \Albatool\SkinQuizConsultant\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Albatool\SkinQuizConsultant\Model\SkinquizconsultantFactory $SkinquizconsultantFactory,
        \Albatool\SkinQuizConsultant\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_skinquizconsultantFactory = $SkinquizconsultantFactory;
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
        $collection = $this->_skinquizconsultantFactory->create()->getCollection();
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
                'renderer'  => '\Albatool\SkinQuizConsultant\Block\Adminhtml\Skinquizconsultant\Grid\Renderer\StoreLabel'
            ]
        );

        $this->addColumn(
            'skin_quiz_q1',
            [
                'header' => __('Skin Quiz Question 1'),
                'index' => 'skin_quiz_q1',
            ]
        );

        $this->addColumn(
            'skin_quiz_q2',
            [
                'header' => __('Skin Quiz Question 2'),
                'index' => 'skin_quiz_q2',
            ]
        );

        $this->addColumn(
            'skin_quiz_q3',
            [
                'header' => __('Skin Quiz Question 3'),
                'index' => 'skin_quiz_q3',
            ]
        );

		
        // $this->addColumn(
        //     'skin_quiz_combination',
        //     [
        //         'header' => __('Skin Quiz Question Combination'),
        //         'index' => 'skin_quiz_combination',
        //     ]
        // );

        // $this->addColumn(
        //     'banner_image',
        //     [
        //         'header' => __('Skin Quiz Banner Image'),
        //         'index' => 'banner_image',
        //         'renderer'  => '\Albatool\SkinQuizConsultant\Block\Adminhtml\Skinquizconsultant\Grid\Renderer\Banner'
        //     ]
        // );

        // $this->addColumn(
        //     'mobile_banner_image',
        //     [
        //         'header' => __('Skin Quiz Mobile Banner Image'),
        //         'index' => 'mobile_banner_image',
        //         'renderer'  => '\Albatool\SkinQuizConsultant\Block\Adminhtml\Skinquizconsultant\Grid\Renderer\Banner'
        //     ]
        // );

        $this->addColumn(
            'skin_quiz_result_description',
            [
                'header' => __('Skin Quiz Result Description'),
                'index' => 'skin_quiz_result_description',
            ]
        );
        
        $this->addColumn(
            'consult_products',
            [
                'header' => __('Consult Product Skus'),
                'index' => 'consult_products',
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
		

		
		   $this->addExportType($this->getUrl('skinquizconsultant/*/exportCsv', ['_current' => true]),__('CSV'));
		   $this->addExportType($this->getUrl('skinquizconsultant/*/exportExcel', ['_current' => true]),__('Excel XML'));

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
        //$this->getMassactionBlock()->setTemplate('Albatool_SkinQuizConsultant::skinquizconsultant/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('skinquizconsultant');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('skinquizconsultant/*/massDelete'),
                'confirm' => __('Are you sure?')
            ]
        );

        $statuses = $this->_status->getOptionArray();

        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('skinquizconsultant/*/massStatus', ['_current' => true]),
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
        return $this->getUrl('skinquizconsultant/*/index', ['_current' => true]);
    }

    /**
     * @param \Albatool\SkinQuizConsultant\Model\skinquizconsultant|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		
        return $this->getUrl(
            'skinquizconsultant/*/edit',
            ['id' => $row->getId()]
        );
		
    }

	

}