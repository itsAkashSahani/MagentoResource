<?php
namespace Albatool\SkinQuiz\Block\Adminhtml\Skinquiz;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Albatool\SkinQuiz\Model\skinquizFactory
     */
    protected $_skinquizFactory;

    /**
     * @var \Albatool\SkinQuiz\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Albatool\SkinQuiz\Model\skinquizFactory $skinquizFactory
     * @param \Albatool\SkinQuiz\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Albatool\SkinQuiz\Model\SkinquizFactory $SkinquizFactory,
        \Albatool\SkinQuiz\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_skinquizFactory = $SkinquizFactory;
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
        $collection = $this->_skinquizFactory->create()->getCollection();
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
					'skin_quiz_name',
					[
						'header' => __('Name'),
						'index' => 'skin_quiz_name',
					]
				);
				
				$this->addColumn(
					'skin_quiz_email',
					[
						'header' => __('Email'),
						'index' => 'skin_quiz_email',
					]
				);
				
				$this->addColumn(
					'skin_quiz_gender',
					[
						'header' => __('Gender'),
						'index' => 'skin_quiz_gender',
					]
				);
				
				$this->addColumn(
					'skin_quiz_dob',
					[
						'header' => __('DOB'),
						'index' => 'skin_quiz_dob',
					]
				);
				
				$this->addColumn(
					'skin_quiz_medical_cond',
					[
						'header' => __('Medical Condition'),
						'index' => 'skin_quiz_medical_cond',
					]
				);
				
				$this->addColumn(
					'skin_quiz_special_offers',
					[
						'header' => __('Special Offers'),
						'index' => 'skin_quiz_special_offers',
					]
				);
				
				$this->addColumn(
					'skin_quiz_quetion_1',
					[
						'header' => __('Describe Your Skin Type'),
						'index' => 'skin_quiz_quetion_1',
					]
				);
				
				$this->addColumn(
					'skin_quiz_quetion_2',
					[
						'header' => __('Main Concern About Skin'),
						'index' => 'skin_quiz_quetion_2',
					]
				);
				
				$this->addColumn(
					'skin_quiz_quetion_3',
					[
						'header' => __('Beauty Routine'),
						'index' => 'skin_quiz_quetion_3',
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
		

		
		   $this->addExportType($this->getUrl('skinquiz/*/exportCsv', ['_current' => true]),__('CSV'));
		   $this->addExportType($this->getUrl('skinquiz/*/exportExcel', ['_current' => true]),__('Excel XML'));

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
        //$this->getMassactionBlock()->setTemplate('Albatool_SkinQuiz::skinquiz/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('skinquiz');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('skinquiz/*/massDelete'),
                'confirm' => __('Are you sure?')
            ]
        );

        $statuses = $this->_status->getOptionArray();

        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('skinquiz/*/massStatus', ['_current' => true]),
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
        return $this->getUrl('skinquiz/*/index', ['_current' => true]);
    }

    /**
     * @param \Albatool\SkinQuiz\Model\skinquiz|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		
        return $this->getUrl(
            'skinquiz/*/edit',
            ['id' => $row->getId()]
        );
		
    }

	

}