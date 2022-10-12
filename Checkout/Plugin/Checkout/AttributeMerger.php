<?php
namespace Albatool\Checkout\Plugin\Checkout;

class AttributeMerger
{
  public function afterMerge(\Magento\Checkout\Block\Checkout\AttributeMerger $subject, $result)
  {
    if (array_key_exists('street', $result)) {
		$result['street']['children'][0]['placeholder'] = __('Flat No/House No/Building No');
    }
	if (array_key_exists('firstname', $result)) {
		$result['firstname']['placeholder'] = __('Firstname');
    }
	if (array_key_exists('lastname', $result)) {
		$result['lastname']['placeholder'] = __('Lastname');
    }
	if (array_key_exists('city', $result)) {
		$result['city']['placeholder'] = __('City');
    }
	if (array_key_exists('telephone', $result)) {
		$result['telephone']['label'] = __('Mobile Number');
		$result['telephone']['placeholder'] = __('5XXXXXXXX');
    }
	
    return $result;
  }
}