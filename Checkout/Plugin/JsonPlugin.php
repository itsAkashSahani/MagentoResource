<?php

namespace Albatool\Checkout\Plugin;

use Magento\Framework\Webapi\Rest\Request;
use Magento\Framework\Webapi\Rest\Response\Renderer\Json;

class JsonPlugin
{

    /** @var Request */
    private $request;

    /**
     * JsonPlugin constructor.
     * @param Request $request
     */
    public function __construct(
        Request $request
    )
    {
        $this->request = $request;
    }

    /**
     * @param Json $jsonRenderer
     * @param callable $proceed
     * @param $data
     * @return mixed
     */
    public function aroundRender(Json $jsonRenderer, callable $proceed, $data)
    {
        if ((($this->request->getPathInfo() == "/V1/inventory/store-pickup/pickup-locations") ||($this->request->getPathInfo() == "/V1/inventory/store-pickup/getcity")) && $this->isJson($data)) {
            return $data;
        }
        return $proceed($data);
    }

    /**
    * @param $data
    * @return bool
    */
    private function isJson($data)
    {
		if (!is_string($data)) {
		   return false;
		}
		json_decode($data);
		return (json_last_error() == JSON_ERROR_NONE);
	}
}