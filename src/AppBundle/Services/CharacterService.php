<?php
/**
 * Created by PhpStorm.
 * User: Neofox
 * Date: 23/04/2016
 * Time: 23:39
 */

namespace AppBundle\Services;


use GuildWars2\Endpoints;
use GuildWars2\Wrapper;

/**
 * Class CharacterService
 * @package AppBundle\Services
 */
class CharacterService
{

    /**
     * @param $apiKey
     *
     * @return mixed
     * @throws \Exception
     */
    public function getCharactersInfos($apiKey)
    {
        $wrapper = (new Wrapper())->setApiKey($apiKey);
        $wrapper->setEndpoint(Endpoints::ACCOUNT);

        return $wrapper->callApi();
    }
}