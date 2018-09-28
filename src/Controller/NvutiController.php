<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NvutiController extends AbstractController
{
    /**
     * @Route("/games/nvuti", name="nvuti")
     */
    public function nvutiGameRender()
    {
        return $this->render('games/nvuti.html.twig', [
            'wallet'=>$this->getUserWallet()
        ]);
    }


}
