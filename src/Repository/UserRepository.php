<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Wallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;


/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function createUserFromResponse(UserResponseInterface $response) {
        $user = new User();

        $user->setUid($response->getUsername());
        $user->setFirstName($response->getFirstName());
        $user->setLastName($response->getLastName());
        $user->setPhoto($response->getProfilePicture());
        $user->setPhotoRec($response->getProfilePicture());
        $user->setEmail($response->getEmail());
        $user->setHash(md5(getenv('VK_CLIENT_ID').$response->getUsername().getenv('VK_SECRET_KEY')));

        $wallet = new Wallet();
        $wallet->setUserId($user->getUid());
        $user->setWallet($wallet);
        $this->_em->persist($wallet);
        $this->_em->persist($user);
        $this->_em->flush();


        return $user;
    }

    /**
     * @param $email string
     *
     * @return $user User
     */
    public function loadUserByEmail($email) {
        $user = $this->findOneBy(['email'=>$email]);
        $wallets = $this->_em->getDoctrine()->getRepository(Wallet::class)->findBy(['user_id' => $user->getUid()]);
        $user->setWallet($wallets);
        return $user;
    }
}
