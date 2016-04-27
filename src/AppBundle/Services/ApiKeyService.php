<?php
/**
 * Created by PhpStorm.
 * User: Neofox
 * Date: 23/04/2016
 * Time: 23:18
 */

namespace AppBundle\Services;


use Symfony\Component\DependencyInjection\Container;

/**
 * Class ApiKeyService
 * @package AppBundle\Services
 */
class ApiKeyService
{
    /**
     * @var Container
     */
    private $container;


    /**
     * ApiKeyService constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param $apiKey
     *
     * @return $this
     * @throws \Exception
     */
    public function setApiKey($apiKey)
    {
        if (true) { // TODO: validate api key
            $this->container->setParameter('gw2_api_key', $apiKey);
        } else {
            throw new \Exception(sprintf('the api key provided %s is not valid.', $apiKey));
        }

        return $this;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getApiKey()
    {
        if (!empty($this->container->getParameter('gw2_api_key'))) {
            $apiKey = $this->container->getParameter('gw2_api_key');
        } else {
            throw new \Exception('Api key is not set yet.');
        }

        return $apiKey;
    }

}