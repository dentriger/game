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
     * @Route("/setDoubleBet", name="doubleBet")
     * @param Request $request
     * @return string|static
     */
    public function setBet(Request $request)
    {
        try {

            $user = $this->getUser();

            if (is_null($user)) {
                throw new \Exception('Please login');
            }

            if ($this->getDoctrine()->getRepository(Wallet::class)->getUserWallet($user->getUid())->getBalance() < $request->query->get('bet_amount')) {
                throw new \Exception('Not enough money');
            }

            $game = $this->getDoctrine()->getRepository(DoubleGame::class)->findOneBy(['status' => 'pending']);
            $bet = new DoubleGameBet();
            $bet->setUserId($user->getUid());
            $bet->setBetTime(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));
            $bet->setStatus('pending');
            $bet->setGameType('double');
            $bet->setAmount($request->get('amount'));
            $bet->setCurrency('RUB');
            $bet->setAnticipatedEvent($request->get('anticipated_event'));
            $bet->setGameId($game->getId());

            $this->getDoctrine()->getManager()->persist($bet);
            $this->getDoctrine()->getManager()->flush();


          // $bet = $this->getDoctrine()->getRepository(DoubleGameBet::class)->createBetFromRequest($request, $this->getUser()->getUid(), $game);
           $data = ['room:double', 'data' => ['bet' => $bet]];
            $context = new \ZMQContext();
            $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');


            $socket->connect("tcp://localhost:5555");
            $msg = json_encode($data);
           $socket->send($msg);
           return $this->json(['bet' => $data]);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}