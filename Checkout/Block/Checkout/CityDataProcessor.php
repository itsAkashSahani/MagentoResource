<?php
namespace Albatool\Checkout\Block\Checkout;

use Albatool\CityDropList\Model\CityDropList;

class CityDataProcessor implements \Magento\Checkout\Block\Checkout\LayoutProcessorInterface
{

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @return array
     */

    protected $cityDropListColl;

    public function __construct(
        CityDropList $cityDropListColl
    ) {
        $this->cityDropListColl = $cityDropListColl;
    }

    public function process($jsLayout)
    {
        if (!isset($jsLayout['components']['checkoutProvider']['dictionaries']['city'])) {
            $jsLayout['components']['checkoutProvider']['dictionaries']['city'] = !empty($this->getCityOptions())?$this->getCityOptions():[];
        }

        return $jsLayout;
    }

    /**
     * Get country options list.
     *
     * @return array
     */
    private function getCityOptions()
    {
        //Add your city list here
        $options = $this->getCityOptionsArr();

        if (count($options) > 0) {
            array_unshift(
                $options,
                ['title' => '', 'value' => '', 'label' => __('Select city')]
            );
        }

        return $options;
    }

    public function getCityOptionsArr()
    {
        $city_list_arr = array();

        /*$city_list_arr = [
            '1' => [
                'value'=>'city1',
                'name'=>'city1',
                'region_id'=>'1',//From database
            ],
            '2' => [
                'value'=>'city2',
                'name'=>'city2',
                'region_id'=>'2',//From databse
            ],
            '3' => [
                'value'=>'city3',
                'name'=>'city3',
                'region_id'=>'3',//From databse
            ]
        ];*/

        $cityColl = $this->cityDropListColl->getCollection();
        foreach ($cityColl as $coll) {
            $city_list_arr[] = ['value'=>$coll->getCity(), 'name'=>$coll->getCity(), 'region_id'=>$coll->getRegionId()];
        }

        return $city_list_arr;
    }
}