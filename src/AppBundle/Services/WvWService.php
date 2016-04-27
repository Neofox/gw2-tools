<?php
/**
 * Created by PhpStorm.
 * User: Neofox
 * Date: 24/04/2016
 * Time: 00:08
 */

namespace AppBundle\Services;


use Doctrine\Common\Cache\ApcuCache;
use Doctrine\Common\Cache\ArrayCache;
use GuildWars2\Endpoints;
use GuildWars2\Wrapper;

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
    /**
     * @var ApcuCache
     */
    private $cache;

    /**
     * WvWService constructor.
     *
     * @param WorldService $worldService
     *
     * @param ArrayCache    $cache
     *
     * @internal param array $mapObjectives
     */
    public function __construct(WorldService $worldService, ArrayCache $cache)
    {
        $this->worldService = $worldService;
        $this->cache = $cache;
    }

    public function stringifyTakenObjectives($matcheId)
    {
        $matcheParticipants = $this->getMatcheParticipants($matcheId);
        $mapObjectives = $this->getObjectives($matcheId);
        $result = [];

        foreach ($mapObjectives as $map => $objectives) {
            foreach ($objectives as $key => $objective) {

                if ($objective->owner != $this->mapObjectives[$map][$key]->owner) {
                    $objectiveInfos = $this->getObjectiveInfos($objective->id);

                    $exOwner = $matcheParticipants[strtolower($this->mapObjectives[$map][$key]->owner)]->name;
                    $newOwner = $matcheParticipants[strtolower($objective->owner)]->name;
                    
                    $result[] = ['msg' => "$objectiveInfos->name ( owned by $exOwner ) has been taken by $newOwner "];
                }
            }
        }

        $this->setMapObjectives($mapObjectives);

        return $result;
    }

    public function getMatcheParticipants($matcheId)
    {
        $matcheInfos = $this->getMatcheInfos($matcheId);

        return [
            'red'   => $this->worldService->getWorldsInfos($matcheInfos->worlds->red),
            'blue'  => $this->worldService->getWorldsInfos($matcheInfos->worlds->blue),
            'green' => $this->worldService->getWorldsInfos($matcheInfos->worlds->green),
        ];
    }

    /**
     * @param      $id
     * @param bool $isWorldId
     *
     * @return mixed
     */
    public function getMatcheInfos($id, $isWorldId = false)
    {
        $wrapper = (new Wrapper())->setDebug(false);
        $wrapper->setEndpoint(Endpoints::WVW_MATCHES);

        if ($isWorldId) {
            $response = $wrapper->callApi('', ['world' => $id]);
        } else {
            $response = $wrapper->callApi($id);
        }

        return $response;
    }

    public function getObjectives($matcheId)
    {
        $wrapper = new Wrapper();
        $wrapper->setEndpoint(Endpoints::WVW_MATCHES);

        $response = $wrapper->callApi($matcheId);
        $maps = $response->maps;

        $objectives = [];
        foreach ($maps as $map) {
            $objectives[$map->id] = $map->objectives;
        }

        return $objectives;
    }

    public function getObjectiveInfos($objectiveId)
    {
        $cacheKey = 'objective_id_'.$objectiveId;

        if($this->cache->contains($cacheKey)){
            $response = $this->cache->fetch($cacheKey);
        }else {
            $wrapper = new Wrapper();
            $wrapper->setEndpoint(Endpoints::WVW_OBJECTIVES);
            $response = $wrapper->callApi($objectiveId);
            $cached = $this->cache->save($cacheKey, $response);

            if(!$cached) {r('file not cached '.$cacheKey, $response);}
        }
        return $response;
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

    public function getWorldByColor($matcheId, $color)
    {
        $this->getMatcheInfos($matcheId);
    }
}