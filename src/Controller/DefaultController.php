<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 06.09.2018
 * Time: 14:36
 */

namespace App\Controller;


use App\Entity\Wallet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexRender()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/games", name="games")
     */
    public function gamesRender()
    {
       return $this->render('default/games.html.twig');
    }
}