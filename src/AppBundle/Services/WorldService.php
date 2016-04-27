<?php
/**
 * Created by PhpStorm.
 * User: Neofox
 * Date: 24/04/2016
 * Time: 14:51
 */

namespace AppBundle\Services;


use Doctrine\Common\Cache\ApcuCache;
use Doctrine\Common\Cache\ArrayCache;
use GuildWars2\Endpoints;
use GuildWars2\Wrapper;

class WorldService
{
    /**
     * @var ApcuCache
     */
    private $cache;

    /**
     * WorldService constructor.
     *
     * @param ArrayCache $cache
     */
    public function __construct(ArrayCache $cache)
    {
        $this->cache = $cache;
    }


    /**
     * @param $worldId
     *
     * @return mixed
     */
    public function getWorldsInfos($worldId = null)
    {
        $cacheKey = 'world_id_'.$worldId;

        if($this->cache->contains($cacheKey)){
            $response = $this->cache->fetch($cacheKey);
        }else{
            $wrapper = new Wrapper();
            $wrapper->setEndpoint(Endpoints::WORLDS);
            $response = $wrapper->callApi($worldId);
            $cached = $this->cache->save($cacheKey, $response);

            if(!$cached) {r('file not cached '.$cacheKey, $response);}
        }



        return $response;
    }

}