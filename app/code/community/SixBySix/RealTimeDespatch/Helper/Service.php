<?php

use \SixBySix\RealtimeDespatch\Api\Credentials;
use \SixBySix\RealtimeDespatch\Gateway\Factory\DefaultGatewayFactory;

use \SixBySix\RealtimeDespatch\Service\ProductService;
use \SixBySix\RealtimeDespatch\Service\OrderService;
use \SixBySix\RealtimeDespatch\Service\ReturnService;

/**
 * RTD Service Helper.
 */
class SixBySix_RealTimeDespatch_Helper_Service extends Mage_Core_Helper_Abstract
{
    /**
     * Returns the current API Credentials
     *
     * @return \SixBySix\RealtimeDespatch\Api\Credentials
     */
    public function getCredentials()
    {
        $configHelper = Mage::helper('realtimedespatch');
        $credentials  = new Credentials();

        $credentials->setEndpoint($configHelper->getApiEndpoint());
        $credentials->setUsername($configHelper->getApiUsername());
        $credentials->setPassword($configHelper->getApiPassword());
        $credentials->setOrganisation($configHelper->getApiOrganisation());
        $credentials->setChannel($configHelper->getApiChannel());

        return $credentials;
    }

    /**
     * Returns a product service instance.
     *
     * @return \SixBySix\RealtimeDespatch\Service\ProductService
     */
    public function getProductService()
    {
        $credentials = $this->getCredentials();
        $factory     = new DefaultGatewayFactory();
        $service     = new ProductService($factory->create($credentials));

        return $service;
    }

    /**
     * Returns an order service instance.
     *
     * @return \SixBySix\RealtimeDespatch\Service\OrderService
     */
    public function getOrderService()
    {
        $credentials = $this->getCredentials();
        $factory     = new DefaultGatewayFactory();
        $service     = new OrderService($factory->create($credentials));

        return $service;
    }

    /**
     * Returns a return service instance.
     *
     * @return \SixBySix\RealtimeDespatch\Service\ReturnService
     */
    public function getReturnService()
    {
        $credentials = $this->getCredentials();
        $factory     = new DefaultGatewayFactory();
        $service     = new ReturnService($factory->create($credentials));

        return $service;
    }
}