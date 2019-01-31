<?php
namespace App\Repository;
use App\Entity\DoubleGame;
use App\Entity\Game;
use App\Entity\NvutiGame;
use App\Entity\NvutiGameBet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function getUserGames($userId)
    {
        $nvuti_games = $this->_em->getRepository(NvutiGame::class)->findBy(['user_id' => $userId]);
        //$double_games = $this->_em->getRepository(DoubleGame::class)->findBy(['user_id' => $userId]);
        //$jackpot_games = $this->_em->getRepository(JackpotGame::class)->findBy(['user_id' => $userId]);

        return $nvuti_games;
    }
}