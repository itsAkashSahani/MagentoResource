<?php
namespace Albatool\HairSkinQuiz\Block\Adminhtml\Hairskinquiz;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Albatool\HairSkinQuiz\Model\hairskinquizFactory
     */
    protected $_hairskinquizFactory;

    /**
     * @var \Albatool\HairSkinQuiz\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Albatool\HairSkinQuiz\Model\hairskinquizFactory $hairskinquizFactory
     * @param \Albatool\HairSkinQuiz\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Albatool\HairSkinQuiz\Model\HairskinquizFactory $HairskinquizFactory,
        \Albatool\HairSkinQuiz\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_hairskinquizFactory = $HairskinquizFactory;
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
        $collection = $this->_hairskinquizFactory->create()->getCollection();
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
					'hair_skin_quiz_name',
					[
						'header' => __('Name'),
						'index' => 'hair_skin_quiz_name',
					]
				);
				
				$this->addColumn(
					'hair_skin_quiz_email',
					[
						'header' => __('Email'),
						'index' => 'hair_skin_quiz_email',
					]
				);
				
				$this->addColumn(
					'hair_skin_quiz_gender',
					[
						'header' => __('Gender'),
						'index' => 'hair_skin_quiz_gender',
					]
				);
				
				$this->addColumn(
					'hair_skin_quiz_dob',
					[
						'header' => __('DOB'),
						'index' => 'hair_skin_quiz_dob',
					]
				);
				
				$this->addColumn(
					'hair_skin_quiz_medical_cond',
					[
						'header' => __('Medical Condition'),
						'index' => 'hair_skin_quiz_medical_cond',
					]
				);
				
				$this->addColumn(
					'hair_skin_quiz_special_offers',
					[
						'header' => __('Special Offers'),
						'index' => 'hair_skin_quiz_special_offers',
					]
				);
				
				$this->addColumn(
					'hair_skin_quiz_quetion_1',
					[
						'header' => __('Describe Your Hair'),
						'index' => 'hair_skin_quiz_quetion_1',
					]
				);
				
				$this->addColumn(
					'hair_skin_quiz_quetion_2',
					[
						'header' => __('Describe Your Scalp'),
						'index' => 'hair_skin_quiz_quetion_2',
					]
				);
				
				$this->addColumn(
					'hair_skin_quiz_quetion_3',
					[
						'header' => __('Describe Your Lenghts'),
						'index' => 'hair_skin_quiz_quetion_3',
					]
				);
				
				$this->addColumn(
					'hair_skin_quiz_quetion_4',
					[
						'header' => __('Beauty Routine'),
						'index' => 'hair_skin_quiz_quetion_4',
					]
				);
				


		

		
		   $this->addExportType($this->getUrl('hairskinquiz/*/exportCsv', ['_current' => true]),__('CSV'));
		   $this->addExportType($this->getUrl('hairskinquiz/*/exportExcel', ['_current' => true]),__('Excel XML'));

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
        //$this->getMassactionBlock()->setTemplate('Albatool_HairSkinQuiz::hairskinquiz/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('hairskinquiz');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('hairskinquiz/*/massDelete'),
                'confirm' => __('Are you sure?')
            ]
        );

        $statuses = $this->_status->getOptionArray();

        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('hairskinquiz/*/massStatus', ['_current' => true]),
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
        return $this->getUrl('hairskinquiz/*/index', ['_current' => true]);
    }

    /**
     * @param \Albatool\HairSkinQuiz\Model\hairskinquiz|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		return '#';
    }

	

}