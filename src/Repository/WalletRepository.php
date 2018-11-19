<?php

namespace App\Repository;

use App\Entity\Wallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Wallet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wallet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wallet[]    findAll()
 * @method Wallet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WalletRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Wallet::class);
    }

    public function updateBalance($user_id, $amount, $win_multiplier = 1)
    {
        $wallet = $this->findOneBy(['user_id'=> $user_id]);

        $current_balance = $wallet->getBalance();

        $new_balance = $current_balance + $amount * $win_multiplier;

        $wallet->setBalance($new_balance);

        $this->_em->persist($wallet);
        $this->_em->flush();

        return $wallet;
    }

    public function createUserWallets()
    {
        $wallets = [];
        foreach (Wallet::WALLET_TYPES as $WALLET_TYPE) {
            $wallet = new Wallet();
            $wallet->setType($WALLET_TYPE);
            $wallets[] = $wallet;
        }

        return $wallets;
    }
}
