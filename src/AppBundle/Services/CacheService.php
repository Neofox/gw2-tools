<?php
/**
 * Created by PhpStorm.
 * User: Neofox
 * Date: 30/04/2016
 * Time: 12:24
 */

namespace AppBundle\Services;


use Doctrine\Common\Cache\ArrayCache;

class CacheService
{
    protected $cache;
    protected $data = [];

    /**
     * CacheService constructor.
     *
     * @param ArrayCache $cache
     */
    public function __construct(ArrayCache $cache)
    {
        $this->cache = $cache;
    }

    public function set($data)
    {
        $cached = $this->cache->save($data->id, $data);
        if(!$cached) throw new \Exception('file not cached '. serialize($data));

        return $cached;
    }

    public function get($id, callable $callable)
    {
        if ($this->cache->contains($id)) {
            $res = $this->cache->fetch($id);
        } else {
            $res = call_user_func($callable);
            $this->set($res);
        }

        return $res;
    }

    public function flushAll()
    {
        return $this->cache->flushAll();
    }

    public function contains($id)
    {
        return $this->cache->contains($id);
    }
}