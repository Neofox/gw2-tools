<?php
/**
 * Created by PhpStorm.
 * User: Neofox
 * Date: 24/04/2016
 * Time: 14:51
 */

namespace AppBundle\Services;


use AppBundle\Services\Gateway\WorldGateway;

class WorldService
{
    /**
     * @var WorldGateway
     */
    private $worldGateway;

    /**
     * WorldService constructor.
     *
     * @param WorldGateway $worldGateway
     *
     * @internal param CacheService $cache
     */
    public function __construct(WorldGateway $worldGateway)
    {
        $this->worldGateway = $worldGateway;
    }

}