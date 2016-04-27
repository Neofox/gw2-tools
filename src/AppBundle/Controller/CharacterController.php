<?php
/**
 * Created by PhpStorm.
 * User: Neofox
 * Date: 23/04/2016
 * Time: 21:53
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CharacterController extends Controller
{
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('@App/character/index.html.twig', []);
    }

}