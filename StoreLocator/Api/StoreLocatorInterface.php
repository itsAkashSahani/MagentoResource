<?php

namespace Albatool\StoreLocator\Api;

interface StoreLocatorInterface {
	
    /**
     * @return mixed
     */
    public function execute();
	
	/**
     * @return mixed
     */
    public function getcity();

    /**
     * @return mixed
     */
    public function getLocationOfStore();

    /**
     * @return mixed
     */
    public function getStoresInCity();
}