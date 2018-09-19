<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 10.09.2018
 * Time: 12:27
 */

namespace App\Controller;


use App\Entity\Bet;
use App\Entity\Wallet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DoubleGameController extends Controller
{
    /**
     * @var \App\Repository\BetRepository
     */
    private $bet_repository;

    /**
     * @var \App\Repository\WalletRepository
     */
    private $wallet_repository;

    public function __construct()
    {
        $this->bet_repository = $this->getDoctrine()->getRepository(Bet::class);
        $this->wallet_repository = $this->getDoctrine()->getRepository(Wallet::class);
    }

    private function genrerateNumber()
    {
        $number = rand(0, 14);
        return $number;
    }


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

            $bet = $this->bet_repository->createBetFromRequest($request);


        } catch (\Exception $exception) {
            return Response::create($exception->getMessage(), 500);
        }

        return Response::create('Bet settled', 200);
    }
}