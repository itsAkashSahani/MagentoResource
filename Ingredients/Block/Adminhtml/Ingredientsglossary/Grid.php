<?php
namespace Albatool\Ingredients\Block\Adminhtml\Ingredientsglossary;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Albatool\Ingredients\Model\ingredientsglossaryFactory
     */
    protected $_ingredientsglossaryFactory;

    /**
     * @var \Albatool\Ingredients\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Albatool\Ingredients\Model\ingredientsglossaryFactory $ingredientsglossaryFactory
     * @param \Albatool\Ingredients\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Albatool\Ingredients\Model\IngredientsglossaryFactory $IngredientsglossaryFactory,
        \Albatool\Ingredients\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_ingredientsglossaryFactory = $IngredientsglossaryFactory;
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
        $this->setDefaultSort('ingredientsglossary_id');
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
        $collection = $this->_ingredientsglossaryFactory->create()->getCollection();
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
            'ingredientsglossary_id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'ingredientsglossary_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );


		
				$this->addColumn(
					'name',
					[
						'header' => __('Name'),
						'index' => 'name',
					]
				);
				
				$this->addColumn(
					'shortcharacter',
					[
						'header' => __('Shortcharacter'),
						'index' => 'shortcharacter',
					]
				);
				
				$this->addColumn(
					'description',
					[
						'header' => __('Description'),
						'index' => 'description',
					]
				);
				
				$this->addColumn(
					'slug',
					[
						'header' => __('Slug'),
						'index' => 'slug',
					]
				);
				
				$this->addColumn(
					'type',
					[
						'header' => __('Type'),
						'index' => 'type',
					]
				);

                $this->addColumn(
                    'is_organic',
                    [
                        'header' => __('Is Organic'),
                        'index' => 'is_organic',
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
                        //'field' => 'ingredientsglossary_id'
                    //]
                //],
                //'filter' => false,
                //'sortable' => false,
                //'index' => 'stores',
                //'header_css_class' => 'col-action',
                //'column_css_class' => 'col-action'
            //]
        //);
		

		
		   $this->addExportType($this->getUrl('ingredients/*/exportCsv', ['_current' => true]),__('CSV'));
		   $this->addExportType($this->getUrl('ingredients/*/exportExcel', ['_current' => true]),__('Excel XML'));

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

        $this->setMassactionIdField('ingredientsglossary_id');
        //$this->getMassactionBlock()->setTemplate('Albatool_Ingredients::ingredientsglossary/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('ingredientsglossary');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('ingredients/*/massDelete'),
                'confirm' => __('Are you sure?')
            ]
        );

        $statuses = $this->_status->getOptionArray();

        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('ingredients/*/massStatus', ['_current' => true]),
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
        return $this->getUrl('ingredients/*/index', ['_current' => true]);
    }

    /**
     * @param \Albatool\Ingredients\Model\ingredientsglossary|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		
        return $this->getUrl(
            'ingredients/*/edit',
            ['ingredientsglossary_id' => $row->getId()]
        );
		
    }

	

}