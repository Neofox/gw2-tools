<?php
/**
 * Created by PhpStorm.
 * User: Neofox
 * Date: 24/04/2016
 * Time: 00:08
 */

namespace AppBundle\Services;


use AppBundle\Services\Gateway\WvWGateway;
use Doctrine\Common\Cache\ArrayCache;
use GW2Treasures\GW2Api\GW2Api;

class WvWService
{
    /**
     * @var array
     */
    protected $mapObjectives;
    /**
     * @var WorldService
     */
    private $worldService;

    private $cache;
    /**
     * @var WvWGateway
     */
    private $wvwGateway;

    /**
     * WvWService constructor.
     *
     * @param WorldService            $worldService
     *
     * @param WvWGateway              $wvwGateway
     * @param CacheService|ArrayCache $cache
     *
     * @internal param array $mapObjectives
     */
    public function __construct(WorldService $worldService, WvWGateway $wvwGateway, CacheService $cache)
    {
        $this->worldService = $worldService;
        $this->cache = $cache;
        $this->wvwGateway = $wvwGateway;
    }

    public function stringifyTakenObjectives($matchId)
    {
        $matchParticipants = $this->wvwGateway->getMatchParticipants($matchId);
        $mapObjectives = $this->getObjectives($matchId);
        $result = [];

        foreach ($mapObjectives as $map => $objectives) {
            foreach ($objectives as $key => $objective) {
                if ($objective->owner != $this->mapObjectives[$map][$key]->owner) {
                    $objectiveInfo = $this->wvwGateway->getObjectiveInfo($objective->id);
                    $exOwner = $matchParticipants[strtolower($this->mapObjectives[$map][$key]->owner)]->name;
                    $newOwner = $matchParticipants[strtolower($objective->owner)]->name;
                    
                    $result[] = ['msg' => "$objectiveInfo->name ( owned by $exOwner ) has been taken by $newOwner "];
                }
            }
        }

        $this->setMapObjectives($mapObjectives);

        return $result;
    }

    public function getObjectives($matchId)
    {
        $response = (new GW2Api())->wvw()->matches()->get($matchId);
        $maps = $response->maps;

        $objectives = [];
        foreach ($maps as $map) {
            $objectives[$map->id] = $map->objectives;
        }

        return $objectives;
    }

    /**
     * @param array $mapObjectives
     *
     * @return WvWService
     */
    public function setMapObjectives($mapObjectives)
    {
        $this->mapObjectives = $mapObjectives;

        return $this;
    }


}