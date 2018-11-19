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
        $game = $this->getDoctrine()->getRepository(NvutiGame::class)->createGame($this->getUser()->getUid());

        return $this->render('games/nvuti.html.twig', [
            'hash' => $game->getGameHash()
        ]);
    }

    /**
     * @Route("/setBet", name="nvutiBet")
     * @param Request $request
     */
    public function setBet(Request $request)
    {
        $response = new Response();
        $em = $this->getDoctrine()->getManager();
        try {
            if (!$user = $this->getUser()) {
                throw new \Exception('Please log in!');
            }

            if (!$request->get('chance') ) {
                throw new \Exception('Chance cannot be empty');
            }
            $chance = intval($request->get('chance'));
            if (!$request->get('amount')) {
                throw new \Exception('Amount cannot be empty');
            }
            $amount = floatval($request->get('amount'));
            $stake = $request->get('stake');
            $game = $em->getRepository(NvutiGame::class)->findOneBy(['user_id' => $user->getUid(), 'status' => 'pending']);
            $event = $this->closeGame($game, $amount, $chance, $request->get('stake'));
            $nvutiBet = new NvutiGameBet();
            $nvutiBet->setUserId($user->getUid());
            $nvutiBet->setGameId($game->getId());
            $nvutiBet->setGameType($game->getName());
            $nvutiBet->setAmount($amount);
            $nvutiBet->setCurrency('RUB');
            $nvutiBet->setStatus($event);
            $nvutiBet->setBetTime(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));
            if($stake == self::STAKE_LESS) {
                $range_max = round($chance/100 * self::MAX_NUMBER);
                $range_min = self::MIN_NUMBER;
            } elseif ($stake == self::STAKE_MORE) {
                $range_min = round(self::MAX_NUMBER - $chance/100 * self::MAX_NUMBER);
                $range_max = self::MAX_NUMBER;
            }
            $nvutiBet->setNumbersRange("$range_min-$range_max");
            $em->persist($nvutiBet);
            $em->flush();
            $this->addFlash(
                'notice',
                'Bet set'
            );
            $hash = $this->createGame();
        } catch (\Exception $e) {

        }
        return $this->json(['hash'=>$hash, 'wallet'=>$this->getUserWallet()]);
    }

    private function closeGame(Game $game, $amount, $chance, $stake)
    {
        $win_number = $game->getGameNumber();
        $wallet = $this->getUserWallet();
        $current_balance = $wallet->getBalance();
        $current_balance -= $amount;
        $event = 'lost';

        if($this->isStakeWin($stake, $chance, $win_number)) {
           $current_balance += round($amount/$chance * 100, 2);
           $event = 'win';
        }
        $wallet->setBalance($current_balance);
        $game->setStatus('closed');
        $this->getDoctrine()->getManager()->persist($wallet);
        $this->getDoctrine()->getManager()->persist($game);
        $this->getDoctrine()->getManager()->flush();

        return $event;
    }

    public function isStakeWin($stake, $chance, $number)
    {
        if($stake == self::STAKE_LESS) {
            $range_max = round($chance/100 * self::MAX_NUMBER);
            $range_min = self::MIN_NUMBER;
        } elseif ($stake == self::STAKE_MORE) {
            $range_min = round(self::MAX_NUMBER - $chance/100 * self::MAX_NUMBER);
            $range_max = self::MAX_NUMBER;
        }

        if(in_array($number, range($range_min, $range_max, 1))) {
            return true;
        }

        return false;
    }
}
