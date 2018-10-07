<?php

namespace App\Controller;

use App\Entity\NvutiGame;
use App\Entity\NvutiGameBet;
use RandomOrg\Random;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use App\Entity\Game;
use App\Entity\Wallet;

class NvutiController extends AbstractController
{
    const MIN_NUMBER = 0;

    const MAX_NUMBER = 999999;

    const STAKE_LESS = 'less';

    const STAKE_MORE = 'more';

    /**
     * @Route("/games/nvuti", name="nvuti")
     */
    public function nvutiGameRender()
    {
        $hash = $this->createGame();
        return $this->render('games/nvuti.html.twig', ['wallet'=>$this->getUserWallet(),'hash' => $hash]);
    }

    /**
     * @return Wallet|null
     */
    public function getUserWallet()
    {
        $user = $this->getUser();
        $uid = $user ? $user->getUid() : null;
        $wallet = $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user_id'=>$uid]);

        return $wallet;
    }

    public function createGame()
    {
        $number = $this->generateNumber();
        $salt = $this->generateSalt();
        $hash = $this->hashNumber($number, $salt);
        if(!$game = $this->getDoctrine()->getManager()->getRepository(NvutiGame::class)->findOneBy(['user_id' => $this->getUser()->getUid(), 'status' => 'pending', 'name' => 'nvuti'])) {

            $game = new NvutiGame();
        }
        $game->setName('nvuti');
        $game->setTime(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));
        $game->setStatus('pending');
        $game->setGameSalt($salt);
        $game->setGameNumber($number);
        $game->setGameHash($hash);
        $game->setUserId($this->getUser()->getUid());
        $this->getDoctrine()->getManager()->persist($game);
        $this->getDoctrine()->getManager()->flush();

        return $hash;
    }

    private function generateNumber()
    {
        $number = 0;
        try {

            $random = new Random(env('RANDOM_ORG_API_KEY'));
            $result = $random->generateIntegers(1, self::MIN_NUMBER, self::MAX_NUMBER, false);
            $number = $result['result']['random']['data'][0];
        } catch (\Exception $e) {

        }

        return $number;
    }

    private function hashNumber($number, $salt)
    {
        return hash('sha224', $number.$salt);
    }

    private function generateSalt()
    {
        return 'salt';
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
