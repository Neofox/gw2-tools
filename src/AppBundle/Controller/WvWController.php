<?php
/**
 * Created by PhpStorm.
 * User: Neofox
 * Date: 23/04/2016
 * Time: 21:50
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class WvWController extends Controller
{
    public function indexAction(Request $request)
    {
        $matcheInfos ='';
        //$matcheInfos = $this->getWvWService()->getMatcheInfos(2104, true);

        // replace this example code with whatever you need
        return $this->render('@App/wvw/index.html.twig', [
            'matcheInfos' => $matcheInfos,
        ]);
    }

    /**
     * @return \AppBundle\Services\WvWService
     */
    protected function getWvWService()
    {
        return $this->get('service.wvw');
    }

    /**
     * @return \AppBundle\Services\WorldService
     */
    protected function getWorldService()
    {
        return $this->get('service.world');
    }

}