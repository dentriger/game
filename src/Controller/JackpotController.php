<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class JackpotController extends AbstractController
{
    /**
     * @Route("/jackpot", name="jackpot")
     */
    public function index()
    {
        return $this->render('jackpot/index.html.twig', [
            'controller_name' => 'JackpotController',
        ]);
    }
}
