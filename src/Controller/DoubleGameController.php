<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 10.09.2018
 * Time: 12:27
 */

namespace App\Controller;


use App\Entity\Bet;
use App\Entity\DoubleGame;
use App\Entity\DoubleGameBet;
use App\Entity\Wallet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DoubleGameController extends Controller
{
    /**
     * @var \App\Repository\DoubleGameBetRepository
     */
    private $bet_repository;

    /**
     * @var \App\Repository\WalletRepository
     */
    private $wallet_repository;

    private $gameRepository;

    private $socket;

    public function __construct()
    {
        $this->bet_repository = $this->getDoctrine()->getRepository(DoubleGameBet::class);
        $this->wallet_repository = $this->getDoctrine()->getRepository(Wallet::class);
        $this->gameRepository = $this->getDoctrine()->getRepository(DoubleGame::class);

        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');

        $this->socket = $socket;
        $this->socket->connect("tcp://localhost:5555");
    }

    /**
     * @Route("/setDoubleBet", name="doubleBet")
     * @param Request $request
     */
    public function setBet(Request $request)
    {
        try {

            $user = $this->getUser();

            if (is_null($user)) {
                throw new \Exception('Please login');
            }

            if ($this->wallet_repository->getUserWallet($user->getUid())->getBalance() < $request->query->get('bet_amount')) {
                throw new \Exception('Not enough money');
            }

            $game = $this->gameRepository->findOneBy(['status' => 'pending']);
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


//            $bet = $this->bet_repository->createBetFromRequest($request, $this->getUser()->getUid(), $game);
           $data = ['room:double', 'data' => ['bet' => $bet]];
           $this->socket->send(json_encode($data));
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}