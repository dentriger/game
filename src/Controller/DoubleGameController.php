<?php

namespace App\Controller;


use App\Entity\DoubleGame;
use App\Entity\DoubleGameBet;
use App\Entity\Wallet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DoubleGameController extends AbstractController
{
    /**
     * @Route("/games/double", name="double")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->render('games/double.html.twig');
    }

    //function for set bet
}
