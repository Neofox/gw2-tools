<?php
/**
 * Created by PhpStorm.
 * User: Neofox
 * Date: 30/04/2016
 * Time: 16:51
 */

namespace AppBundle\Services\Gateway;


use AppBundle\Services\CacheService;
use GW2Treasures\GW2Api\GW2Api;

class WorldGateway
{
    /**
     * @var CacheService
     */
    private $cache;


    /**
     * WorldGateway constructor.
     *
     * @param CacheService $cache
     */
    public function __construct(CacheService $cache)
    {
        $this->cache = $cache;
    }

    public function getWorldsInfo($worldId)
    {
        $response = $this->cache->get($worldId, function () use ($worldId) {
            return (new GW2Api())->worlds()->get($worldId);
        });

        return $response;
    }
}