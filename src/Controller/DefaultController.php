<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 06.09.2018
 * Time: 14:36
 */

namespace App\Controller;


use App\Entity\Wallet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexRender()
    {
        return $this->render('default/index.html.twig', [
            'wallet'=>$this->getUserWallet()
        ]);
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profileRender()
    {
        return $this->render('user/profile.html.twig', [
            'wallet'=>$this->getUserWallet()
        ]);
    }

    /**
     * @Route("/games", name="games")
     */
    public function gamesRender()
    {
       return $this->render('default/games.html.twig', [
          'wallet'=>$this->getUserWallet()
       ]);
    }

    /**
     * @Route("/games/nvuti", name="nvuti")
     */
    public function nvutiGameRender()
    {
        return $this->render('games/nvuti.html.twig', [
            'wallet'=>$this->getUserWallet()
        ]);
    }

    /**
     * @Route("/games/double", name="double")
     */
    public function doubleGameRender()
    {
        return $this->render('games/double.html.twig', [
            'wallet'=>$this->getUserWallet()
        ]);
    }

    /**
     * @Route("/games/jackpot", name="jackpot")
     */
    public function jackpotGameRender()
    {
        return $this->render('games/jackpot.html.twig', [
            'wallet'=>$this->getUserWallet()
        ]);
    }

    public function getUserWallet()
    {
        $user = $this->getUser();
        $uid = $user ? $user->getUid() : null;
        $wallet = $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user_id'=>$uid]);

        return $wallet;
    }
}