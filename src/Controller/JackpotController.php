<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class JackpotController extends AbstractController
{
    /**
     * @Route("/games/jackpot", name="jackpot")
     */
    public function index()
    {
        return $this->render('games/jackpot.html.twig');
    }
}
