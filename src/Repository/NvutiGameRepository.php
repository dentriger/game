<?php

namespace App\Repository;

use App\Entity\DoubleGame;
use App\Entity\NvutiGame;
use App\Services\RandomOrg;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NvutiGame|null find($id, $lockMode = null, $lockVersion = null)
 * @method NvutiGame|null findOneBy(array $criteria, array $orderBy = null)
 * @method NvutiGame[]    findAll()
 * @method NvutiGame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NvutiGameRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NvutiGame::class);
    }

    public function createGame($userId)
    {
        $number = RandomOrg::getInstance()->generateIntegers(1, NvutiGame::MIN_NUMBER, NvutiGame::MAX_NUMBER, false);
        $salt = RandomOrg::getInstance()->generateStrings(1, 6);
        $hash = $this->hashNumber($number, $salt);

        if(!$game = $this->_em->getRepository(NvutiGame::class)->findOneBy(['user_id' => $userId, 'status' => 'pending', 'name' => 'nvuti'])) {
            $game = new NvutiGame();
        }

        $game->setName('nvuti');
        $game->setTime(new \DateTime());
        $game->setStatus('pending');
        $game->setGameSalt($salt);
        $game->setGameNumber($number);
        $game->setGameHash($hash);
        $game->setUserId($userId);
        $this->_em->persist($game);
        $this->_em->flush();

        return $game;
    }

    private function hashNumber($number, $salt)
    {
        return hash('sha224', $number.$salt);
    }
}
