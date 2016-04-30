<?php
/**
 * Created by PhpStorm.
 * User: Neofox
 * Date: 30/04/2016
 * Time: 12:06
 */

namespace AppBundle\Services\Gateway;


use AppBundle\Services\CacheService;
use AppBundle\Services\WorldService;
use GW2Treasures\GW2Api\GW2Api;

class WvWGateway
{
    protected $api;
    /**
     * @var CacheService
     */
    private $cache;
    /**
     * @var WorldGateway
     */
    private $worldGateway;

    /**
     * WvWGateway constructor.
     *
     * @param CacheService $cache
     * @param WorldGateway $worldGateway
     */
    public function __construct(CacheService $cache, WorldGateway $worldGateway)
    {
        $this->api = new GW2Api();
        $this->cache = $cache;
        $this->worldGateway = $worldGateway;
    }

    /**
     * @param      $id
     *
     * @return mixed
     */
    public function getMatchInfo($id)
    {
        return  (new GW2Api())->wvw()->matches()->get($id);
    }

    public function getObjectiveInfo($objectiveId)
    {
        $response = $this->cache->get($objectiveId, function () use ($objectiveId) {
            return (new GW2Api())->wvw()->objectives()->get($objectiveId);
        });

        return $response;
    }

    public function getMaps($matchId)
    {
        return $this->getMatchInfo($matchId)->maps;
    }

    public function getMatchParticipants($match_id)
    {
        $match = $this->api->wvw()->matches()->get($match_id);

        return [
            'red' => $this->worldGateway->getWorldsInfo($match->worlds->red),
            'blue' => $this->worldGateway->getWorldsInfo($match->worlds->blue),
            'green' => $this->worldGateway->getWorldsInfo($match->worlds->green)
        ];
    }
}