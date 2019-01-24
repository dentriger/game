<?php

namespace App\Controller;

use App\Entity\NvutiGame;
use App\Entity\NvutiGameBet;
use App\Services\RandomOrg;
use RandomOrg\Random;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use App\Entity\Game;

class NvutiController extends AbstractController
{
    /**
     * @Route("/games/nvuti", name="nvuti")
     */
    public function nvutiGameRender()
    {
        $hash = 'Please login in!';
        if ($this->getUser()) {
            $game = $this->getDoctrine()->getRepository(NvutiGame::class)->createGame($this->getUser()->getId());
            $hash = $game->getGameHash();
        }

        return $this->render('games/nvuti.html.twig', ['hash' => $hash]);
    }

    /**
     * @Route("/setBet", name="nvutiBet")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function setBet(Request $request)
    {
        if (!$this->getUser()) {
            return $this->json(['error' => 'Please log in!']);
        }
        $payload = $request->query->all();
        $this->getDoctrine()->getRepository(NvutiGameBet::class)->setBet($this->getUser()->getId(), $payload);
        $hash = $this->getDoctrine()->getRepository(NvutiGame::class)->createGame($this->getUser()->getId())->getGameHash();
        $this->addFlash('notice', 'Ставка принята');
        return $this->json(['hash'=>$hash]);
    }
}
